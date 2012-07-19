function Player(playerDTO){
	var self = this;
	
	// privates
	var playerDTO = playerDTO;
	
	// public
	self.getName = function(){ return playerDTO.getName(); }
	self.getId = function(){ return playerDTO.getId(); }
	
}