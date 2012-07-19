function HoleDTO(JSONData){
	var self = this;
	var LOGGER = new Logger('HoleDTO',log4javascript.Level.TRACE);
	
	var par;
	var number;
	
	self.getPar = function(){ return par; };
	self.getNumber = function(){ return number;}
	
	self.init = function(JSONData){
		par = JSONData.par; 
		number = JSONData.number;
		LOGGER.trace("Created new HoleDTO: number: " + number + " par: " + par);
	};
	
	self.init(JSONData);
}