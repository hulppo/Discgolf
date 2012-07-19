function CourseDAO(){
	var self = this;
	self.LOGGER = new Logger('CourseDAO',log4javascript.Level.TRACE);
	
	
	self.getCourseListingP = function(){
		LOGGER.info("Loading list of courses");
		var courseListPromise = (new AjaxRunner).xhrP(Constants.REST_ROOT + "/courses.json");
		return courseListPromise.pipe(function(courseListJSON){  LOGGER.info("Course list loaded"); return  self.coursesFromJSON(courseListJSON)});
	}
	
	self.loadCourseDetailsP = function(courseList){
		LOGGER.info("Loading course details for " + courseList.length + " courses");
		var waitFor = [];
		$.each(courseList, function(index, course){
			var coursePromise = (new AjaxRunner).xhrP(Constants.REST_ROOT +  "/courses/" + course.getId() + ".json");
			waitFor.push(coursePromise.pipe(function(JSON){ return self.courseFromJSON(JSON); }));
		});
		return $.when.apply($, waitFor).pipe(function(){ LOGGER.info("All course details loaded."); return arguments; });
	};
	
	self.getCoursesP = function(){
		return self.getCourseListingP().pipe(self.loadCourseDetailsP);
	};
	
	
	self.coursesFromJSON = function(JSON){
		return $.map(JSON, 
				function(itemData) { 
					return self.courseFromJSON(itemData);
				});
	};
	
	self.courseFromJSON = function(JSON){
		return new CourseDTO(JSON);
	};
}