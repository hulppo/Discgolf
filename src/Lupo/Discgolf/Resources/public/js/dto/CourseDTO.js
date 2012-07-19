function CourseDTO(JSONData){
	var self = this;
	var LOGGER = new Logger('CourseDTO',log4javascript.Level.TRACE);

	var name;
	var id;
	var holes = [];

	self.getName = function(){ return name; };
	self.getId = function(){ return id; };
	self.getHoles = function(){ return holes; };
	
	self.init = function(JSONData){
		name = JSONData.name;
		id = JSONData.id;
		if(JSONData.holes)
			holes = $.map(JSONData.holes, function(hole,index){ return new HoleDTO(hole); });
		LOGGER.trace("Created courseDTO id:" + id + " name: " + name + " holes: " + holes.length);
	};
	
	self.init(JSONData);
}