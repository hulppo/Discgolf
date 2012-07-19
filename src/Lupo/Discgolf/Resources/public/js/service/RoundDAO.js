function RoundDAO(){
	var self = this;
	var LOGGER = new Logger('RoundDAO',log4javascript.Level.TRACE);
	
	self.getRoundListingP = function(){
		var roundListPromise = (new AjaxRunner).xhrP(Constants.REST_ROOT +  "/rounds.json");
		return roundListPromise.pipe(function(roundListJSON){ return  self.roundsFromJSON(roundListJSON)});
	}
	
	self.loadRoundDetailsP = function(roundList){
		var waitFor = [];
		$.each(roundList, function(index, round){
			var roundPromise = (new AjaxRunner).xhrP(Constants.REST_ROOT +  "/rounds/" + round.getId() + ".json");
			waitFor.push(roundPromise.pipe(function(JSON){ return self.roundFromJSON(JSON); }));
		});
		return $.when.apply($, waitFor);
	};
	
	self.getRoundsP = function(){
		return self.getRoundListingP().pipe(self.loadRoundDetailsP).pipe(function(){ return arguments; });
	};
	
	self.roundsFromJSON = function(JSON){
		return $.map(JSON, 
				function(itemData) { 
					return self.roundFromJSON(itemData);
				});
	};
	
	self.roundFromJSON = function(JSON){
		return new RoundDTO(JSON);
	};
	
}