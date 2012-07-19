function RoundListViewModel(domainModel, viewController){
	var self = this;
	var LOGGER = new Logger('RoundListViewModel',log4javascript.Level.TRACE);
	
	var domainModel = domainModel;
	var viewController = viewController;
	
	self.rounds = ko.observableArray([]);
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
	
	self.setRounds = function(rounds){
		self.augmentRounds(rounds);
		self.rounds(rounds);
	}
	
	// TODO: Not really nice to extend the data model this way. Fix.
	self.augmentRounds = function(rounds){
		$.each(rounds, function(index, round){ 
			round.filteredByPlayers = ko.observable(false);
			round.filteredByCourses = ko.observable(true);
			round.filtered = ko.computed(function() {
			        return round.filteredByPlayers() && round.filteredByCourses();
			}, this);
		});
	};
	
	
	self.getFilteredRounds = function(){
		 var filteredRounds =  $.grep(self.rounds(), function(round){
			 return round.filtered();
		 });
		 LOGGER.trace("getFilteredRounds() returning " + filteredRounds.length + " rounds.");
		 return filteredRounds;
	 };
	 
	 self.filterByPlayers = function(players){
		 self.filterSharedRounds(self.rounds(), $.map(players, function(player,index){ return player.getId(); }));
		 viewController.roundGrouperViewModel.updateRounds(self.rounds());
	};
	
	self.filterByRoundGroups = function(roundGroups){
		$.each(self.rounds(), function(i,round){
			var isFiltered = (round.data.course.id == roundGroups[0].id);
			round.filteredByCourses(isFiltered);
		});
		
	};

	self.filterSharedRounds = function(rounds, playerIDs){
		LOGGER.debug("filterSharedRounds() filtering by " + rounds.length + " rounds and " + playerIDs.length + " players.");
		$.each(rounds, function(i,round){ 
			var isFiltered = round.hasAsParticipants(playerIDs, self.requireAllParticipants);
			// TODO: will cause knockout binding evaluation. should we rather do these in one chunk and then refresh manually.
			round.filteredByPlayers(isFiltered);
			LOGGER.trace("filterSharedRounds() Setting round " + round.getId() + " filteredByPlayers: " + isFiltered);
		});
	};

	self.setRounds(domainModel.getRounds());
}