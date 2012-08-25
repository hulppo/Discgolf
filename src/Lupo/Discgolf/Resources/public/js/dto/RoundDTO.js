
function RoundDTO(JSONData){
	var self = this;
	var LOGGER = new Logger('RoundDTO',log4javascript.Level.TRACE);
	
	var id;
	var timestamp;
	var courseId;
	var courseName;
	var playerIDs = [];
	var results = [];
	
	self.getId = function(){ return id; };
	self.getTimestamp = function(){ return timestamp; };
	self.getCourseId = function(){ return courseId; };
	self.getCourseName = function(){ return courseName; };
	self.getPlayerIDs = function(){ return playerIDs; };
	self.getResults = function(){ return results; };
	
	self.init = function(JSONData){
		id = JSONData.id;
		timestamp = Utils.parseDate(JSONData.timestamp);
		courseId = JSONData.course.id;
		courseName = JSONData.course.name;
		playerIDs = $.map(JSONData.participants, function(participant,index){ return participant.player.id; });

		if(JSONData.results)
			results = $.map(JSONData.results, function(result, index){ return new ResultDTO(result); } );
		
		LOGGER.trace("Created RoundDTO id:" + self.getId() + " players: " + self.getPlayerIDs().length + " results: " + self.getResults().length);
	};
	
	self.init(JSONData);
}


