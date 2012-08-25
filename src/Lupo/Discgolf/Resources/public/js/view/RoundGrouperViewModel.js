

function RoundGrouperViewModel(domainModel, viewController){
	var self = this;
	var LOGGER = new Logger('RoundGrouperViewModel',log4javascript.Level.TRACE);
	var domainModel = domainModel;
	var viewController = viewController;
	
	self.courses = [];
	self.roundGroups = ko.observableArray([]);
	self.requireAll = ko.observable(true);
	
	self.setCourses = function(courses){
		self.courses = courses;
	};
	
	self.getCourses = function(){ return self.courses; };
	
	self.toggleRoundGroup = function(roundGroup){
		roundGroup.selected(!roundGroup.selected());
		LOGGER.info((roundGroup.selected() ? "Enabled" : "Disabled") +  " round group " + roundGroup.id + " with " + roundGroup.rounds().length + " rounds. Enabled round groups: " +  filterSelectedRoundGroups(self.roundGroups()).length);
		viewController.roundListViewModel.filterByRoundGroups(filterSelectedRoundGroups(self.roundGroups()));
		viewController.powerTableViewModel.populate(viewController.playerListViewModel.getSelectedPlayers(),viewController.roundListViewModel.filteredRounds());
	};
	
	self.toggleRequireAll = function(value){
		viewController.roundListViewModel.requireAllParticipants = self.requireAll();
		viewController.playerListViewModel.update();
		return true;
	};
	
	self.addRound = function(round){
		var targetGroup = self.getRoundGroupFor(round);
		targetGroup.rounds.push(round);
	};
	
	self.updateRounds = function(rounds){
		LOGGER.info("Grouping " + rounds.length + " rounds under course list");
		$.each(self.roundGroups(), function(i,roundgroup) { 
			//RoundGrouperLogger.debug(roundgroup.rounds().length);
			roundgroup.rounds.removeAll();
		});
		
		$.each(rounds, function(i,round) { 
				self.addRound(round) 
		});
	}
	
	self.removeAllRounds = function(){
		$.each(self.roundGroups(), function(i,roundGroup){ roundGroup.rounds = []; });
	};
	

	self.getRoundGroupFor = function(round){
		var roundGroup = $.grep(self.roundGroups(), 
				function(roundGroup){ 
					return round.getCourseId() == roundGroup.id;
				})[0];
		
		if(roundGroup){
			return roundGroup;
		}else{
			var courseId = round.getCourseId();
			var par = domainModel.getCourse(courseId).getPar();
			roundGroup = new RoundGroup(courseId, par);
			self.roundGroups.push(roundGroup);
			return roundGroup;
		}
	};
	
	
}


function filterSelectedRoundGroups(roundGroups){
	var selectedGroups = $.grep(roundGroups, function(roundGroup) { return roundGroup.selected(); });
	return selectedGroups;
}



