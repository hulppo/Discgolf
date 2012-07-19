function DomainModel(){
	var self = this;
	
	var LOGGER = new Logger('DomainModel',log4javascript.Level.TRACE);
	var dgAPI = new DGAPIServiceFacade();
	
	var courses;
	var players;
	var rounds;
	
	self.setCourses = function(coursesToSet){ courses = coursesToSet; };
	self.getCourses = function(){ return courses; };
	self.getCourse = function(courseId){ 
		return Utils.findAndRequireOne(self.getCourses(), function(element){ return element.getId() == courseId; }); 
	};
	
	
	self.setPlayers = function(playersToSet){ players = playersToSet; };
	self.getPlayers = function(){ return players; };
	self.getPlayer = function(playerId){
		return Utils.findAndRequireOne(self.getPlayers(), function(element){ return element.getId() == playerId; }); 
	};
	
	self.setRounds = function(roundsToSet){ rounds = roundsToSet; };
	self.getRounds = function(){ return rounds; };
	self.getRound = function(roundId){
		return Utils.findAndRequireOne(self.getRounds(), function(element){ return element.getId() == roundId; }); 
	};
		
	
	self.initP = function(){
		return self.loadCourses().pipe(self.loadPlayers).pipe(self.loadRounds);
	};
	
	
	self.loadCourses = function(){
		LOGGER.info("Loading all courses");
		var coursesPromise = dgAPI.getCoursesP();
		coursesPromise.done(function(courses){
			LOGGER.info("Courses loaded successfully.");
			self.setCourses(courses);  
			});
		coursesPromise.fail(function(){ throw "Error initing domain model. Cannot load courses.";  });
		return coursesPromise;
	};
	
	
	self.loadPlayers = function(){
		LOGGER.info("Loading all players");
		var playersPromise = dgAPI.getPlayersP();
		playersPromise.done(function(players){
			LOGGER.info("Players loaded successfully.");
			self.setPlayers(players); 
			});
		playersPromise.fail(function(){ throw "Error initing domain model. Cannot load players.";  });
		return playersPromise;
	};
	
	self.loadRounds = function(){
		LOGGER.info("Loading all rounds");
		var roundsPromise = dgAPI.getRoundsP();
		roundsPromise.done(function(rounds){ 
			LOGGER.info("Rounds loaded successfully.");
			self.setRounds(rounds); 
			});
		roundsPromise.fail(function(){ throw "Error initing domain model. Cannot load rounds.";  });
		return roundsPromise;
	}
	
}



