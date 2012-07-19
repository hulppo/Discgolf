function ResultDTO(JSONData){
	var self = this;
	var LOGGER = new Logger('ResultDTO',log4javascript.Level.TRACE);
	
	var throwCount;
	var playerId;
	
	
	self.getThrows = function(){ return throwCount; };
	self.getPlayerId = function(){ return playerId; };
	
	self.init = function(JSONData){ 
		throwCount = JSONData['throws'];
		playerId = JSONData.player.id;
		
		LOGGER.debug("Created new ResultDTO: throwCount " + self.getThrows() + " playerId: " + self.getPlayerId());
	};
	
	self.init(JSONData);
}