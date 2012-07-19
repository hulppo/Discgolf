function PlayerDAO(){
	var self = this;
	self.LOGGER = new Logger('PlayerDAO',log4javascript.Level.TRACE);
	
	self.getPlayersP = function(){
		LOGGER.info("Loading list of players");
		var promise = (new AjaxRunner).xhrP(Constants.REST_ROOT +  "/players.json");
		return promise.pipe( function(data){ LOGGER.info("List of players loaded"); return self.playersFromJSON(data);  });
	}
	
	self.playersFromJSON = function(JSON){
		return $.map(JSON, 
				function(itemData) { 
					return self.playerFromJSON(itemData);
				});
	};
	
	self.playerFromJSON = function(JSON){
		return new Player( new PlayerDTO(JSON));
	};
}