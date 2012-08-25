function RoundListViewModel(domainModel, viewController){
	var self = this;
	var LOGGER = new Logger('RoundListViewModel',log4javascript.Level.TRACE);
	
	var domainModel = domainModel;
	var viewController = viewController;
	
	var allRounds = domainModel.getRounds();
	var filteredByPlayers = [];
	var filteredByCourses = allRounds;
	
	self.filteredRounds = ko.observableArray([]);
	self.requireAllParticipants = true;
	
	
	self.showScoreCard = function(round){
		var scorecardDialog = $("<div/>");
		var scorecard = $("<div data-bind='template: { name: \"scorecard-template\" }'></div>");
		scorecardDialog.append(scorecard);
		
		var c = domainModel.getCourse(round.getCourseId());
		
		var foo = function(){
			this.round = round;
			this.course = c;
			this.domainModel = domainModel;
		}
		ko.applyBindings(new foo(), scorecardDialog.get(0));
		scorecardDialog.dialog({ width: "auto", title: c.getName() + " " + round.getTimestamp() });
		return true;
	}
	
	 self.filterByPlayers = function(players){
		 self.filterSharedRounds(players);
		 // TODO: it should not be roundlistviewmodels responsibility to do this
		 viewController.roundGrouperViewModel.updateRounds(filteredByPlayers);
	};
	
	self.filterByRoundGroups = function(roundGroups){
		LOGGER.info("Enabling rounds for " + roundGroups.length + " courses");
		filteredByCourses = [];
		var courseIds = $.map(roundGroups, function(group, index){ return group.id; });
		$.each(allRounds, function(i,round){
			var isFiltered = Utils.contains(courseIds, function(element){ return round.getCourseId() == element; });
			if(isFiltered) 
				filteredByCourses.push(round);
			LOGGER.trace("Round included " + round.getId());
		});
		self.evaluateFiltered();
		LOGGER.debug("Total of " + self.filteredRounds().length + " are enabled by courses");
	};

	self.filterSharedRounds = function(players){
		LOGGER.debug("filterSharedRounds() filtering by  " + players.length + " players.");
		filteredByPlayers = [];
		var playerIDs = $.map(players, function(player,index){ return player.getId(); })
		$.each(allRounds, function(i,round){ 
			var isFiltered = round.hasAsParticipants(playerIDs, self.requireAllParticipants);
			if(isFiltered){
				filteredByPlayers.push(round);
				LOGGER.trace("filterSharedRounds() Including round " + round.getId());
			}
		});
		 self.evaluateFiltered();
	};
	
	// TODO: this is not optimized and is very slow. If becomes a problem use something like intersecting arrays of IDs here
	self.evaluateFiltered = function(){
		var filtered = [];
		LOGGER.info("Evaluating intersection of " + filteredByPlayers.length + " filtered by players and " + filteredByCourses.length + " filtered by courses");
		$.each(filteredByPlayers, function(index, round){
			if(Utils.contains(filteredByCourses, function(element){ return round.getId() == element.getId(); }))
				filtered.push(round);
		});
		LOGGER.info("Including " + filtered.length + " rounds selected by players and courses");
		self.filteredRounds(filtered);
		viewController.parGraphViewModel.update();
	};

}