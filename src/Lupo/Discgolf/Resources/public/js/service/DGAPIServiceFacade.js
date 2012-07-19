function DGAPIServiceFacade(){
	var self = this;
	self.courseDAO = new CourseDAO();
	self.playerDAO = new PlayerDAO();
	self.roundDAO = new RoundDAO();
	
	var LOGGER = new Logger('DGAPIServiceFacade',log4javascript.Level.TRACE);
	
	self.getCourseP = function(id){
		return self.courseDAO.getCourseP(id).pipe(function(courseDto){ return new Course(courseDto)});
	};
	
	self.getCoursesP = function(){
		return self.courseDAO.getCoursesP().pipe(function(courseDTOs){
			return ($.map(courseDTOs, function(courseDTO,index){ return new Course(courseDTO); }));
		});
	};
	
	self.getPlayersP = function(){
		return self.playerDAO.getPlayersP().pipe(function(playerDTOs){
			return ($.map(playerDTOs, function(playerDTO,index){ return new Player(playerDTO); }));
		});
	};
	
	self.getRoundsP = function(){
		return self.roundDAO.getRoundsP().pipe(function(roundDTOs){
			return ($.map(roundDTOs, function(roundDTO,index){
				return new Round(roundDTO); 
			}));
		});
	};
	
	
}