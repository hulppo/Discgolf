function PlayerResult(playerId,  results){
	var self = this;
	
	var playerId = playerId;
	var results = results;
	
	self.getPlayerId = function(){ return playerId; };
	
	self.getSum = function(){
		return Utils.sum(results);
	};
	
	self.getIn = function(){
		return Utils.sum(results.slice(0,9));
	};
	
	self.getOut = function(){
		return Utils.sum(results.slice(9));
	};
	
	self.getResults = function(){
		return results;
	};
}


PlayerResult.arrayToString = function(resultArray){
	var string = "";
	$.each(resultArray, function(index, value){
		string += "player: " + value.getPlayerId() + " [" + value.getResults().join() + "] ";
	});
	return string;
};