function Course(courseDTO){
	var self = this;
	var LOGGER = new Logger('Course',log4javascript.Level.TRACE);
	
	var courseDTO = courseDTO;
	
	self.getName = function(){ return courseDTO.getName(); };
	self.getId = function(){ return courseDTO.getId(); };
	self.getHoles = function(){ return courseDTO.getHoles(); };
	
	self.getPar = function(){
		return Utils.sum(self.getPars());
	};
	
	self.getPars = function(){
		return $.map(self.getHoles(), function(hole, index){
			return hole.getPar();
		});
	}
	
	self.parIn = function(){
		return Utils.sum(self.getPars().splice(0,9));
	};
	
	self.parOut = function(){
		return Utils.sum(self.getPars().splice(9));
	};
	
}

