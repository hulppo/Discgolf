function PlayerDTO(JSONData){
	var self = this;
	var LOGGER = new Logger('PlayerDTO',log4javascript.Level.TRACE);
	
	var id;
	var name;
	
	self.getId = function(){
		return id;
	}
	
	self.getName = function(){
		return name;
	}
	
	self.init = function(JSONData){
		id = JSONData.id;
		name = JSONData.name;
		LOGGER.trace("Created new PlayerDTO: id:" + id + " name: " + name);
	};
	
	self.init(JSONData);
}

