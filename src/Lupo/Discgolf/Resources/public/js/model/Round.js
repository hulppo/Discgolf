
function Round(roundDTO){
	var self = this;
	
	var LOGGER = new Logger('Round',log4javascript.Level.TRACE);
	var roundDTO = roundDTO;
	var results = null;
	
	self.getId = function(){ return roundDTO.getId(); };
	self.getCourseName = function(){ return roundDTO.getCourseName(); };
	self.getTimestamp = function(){ return roundDTO.getTimestamp(); };
	self.getCourseId = function(){ return roundDTO.getCourseId(); };
	
	
	self.powers = function(player, compareTo){
		var playerTotal = self.getPlayerTotal(player.getId());
		var compareToTotal = self.getPlayerTotal(compareTo.getId());
		var powers = 0;
		if(playerTotal < compareToTotal) powers = 1;
		if(playerTotal > compareToTotal) powers = -1;
		LOGGER.debug("Compared player " + player.getId() + "(throws " + playerTotal + ") to player " + compareTo.getId() + "(throws " + compareToTotal + ") on round " + self.getId() + " and result is: " + powers);
		return powers;
	};
	
	self.getPlayerTotal = function(playerId){
		if(!self.hasAsParticipant(playerId))
			throw "Round.js: countPlayerTotal: Requested player has not participated in the round!";
		
		return Utils.sum(self.getResultsForPlayer(playerId).getResults());
		
	};
	
	// Returns an array of PlayerResult objects
	self.getResults = function(){
		if(results == null){
			results = $.map(roundDTO.getPlayerIDs(), function(playerId, index){
				return new PlayerResult(playerId, self.getThrows(playerId));
			});
		}
		LOGGER.trace("Results for round " + self.getId() + ": " + PlayerResult.arrayToString(results));
		return results;
	};
	
	self.getResultsForPlayer = function(playerId){
		var playerResult =  Utils.findAndRequireOne(self.getResults(), function(result){ return result.getPlayerId() == playerId; });
		LOGGER.trace("Round " + self.getId() + " results for player " + playerId + ":" + playerResult.getResults().join());
		return playerResult;
	};
	
	
	self.getDTO = function(){ return roundDTO; };
	
	// Returns an array of player scores for this round
	self.getThrows = function(playerId){
		LOGGER.debug(roundDTO.getResults().length);
		var playerResultDTOs = $.grep(roundDTO.getResults(), 
				function(resultDTO,index){ 
					return resultDTO.getPlayerId() == playerId; 
				});
		LOGGER.debug(playerResultDTOs.length);
		var playerThrows = $.map(playerResultDTOs, function(resultDTO, index){ return resultDTO.getThrows(); });
		LOGGER.trace("On round " + self.getId() + " player " + playerId + " throws are " + playerThrows.join());
		return playerThrows;
	};
	
	
	
	self.hasAsParticipant= function(playerId){
		return Utils.contains(roundDTO.getPlayerIDs(), function(participantId){ return participantId == playerId});
	}
	
	self.hasAsParticipants = function(playerIDs, requireAllParticipants){
		participantIntersect = $.grep(playerIDs, function(playerId,i){
			return self.hasAsParticipant(playerId);
		});
		
		var result = (!requireAllParticipants && participantIntersect.length >=1) || 
					 (requireAllParticipants && participantIntersect.length == playerIDs.length);
		LOGGER.trace("hasAsParticipants() Checked " + playerIDs.length + " players for roundId " + roundDTO.getId() + ": " + result);
		return result;
	}
	
	
	
}


