// TODO: remove roundListViewModel as class member. figure out correct way for viewmodels to interact

function PlayerListViewModel(domainModel, viewController){
	var self = this;
	var LOGGER = new Logger('PlayerListViewModel',log4javascript.Level.TRACE);
	
	var domainModel = domainModel;
	var viewController = viewController;
	self.players = ko.observableArray([]);
	
	self.setPlayers = function(players){
		self.augmentPlayers(players);
		self.players(players);
	}
	
	// TODO: Not really nice to extend the data model this way. Fix.
	self.augmentPlayers = function(players){
		$.each(players, function(index, player){ 
			player.selected = ko.observable(false);
		});
	}
	
	 self.getSelectedPlayers = function(){
		 var selectedPlayers = $.grep(self.players(), function(player,i) { return player.selected(); });
		 LOGGER.trace("getSelectedPlayers() Returning " + selectedPlayers.length + " players");
		 return selectedPlayers;
	 }
	
	self.selectedPlayersCount = ko.computed(
			function() {
				return self.getSelectedPlayers().length;
			}, this); 
	
	 self.togglePlayer = function(player){
		 player.selected(!player.selected());
		 self.update();
	 };
	 
	self.update = function(){
		viewController.roundListViewModel.filterByPlayers(self.getSelectedPlayers());
		viewController.powerTableViewModel.populate(viewController.playerListViewModel.getSelectedPlayers(),viewController.roundListViewModel.filteredRounds());
	};
	
	self.setPlayers(domainModel.getPlayers());
}

