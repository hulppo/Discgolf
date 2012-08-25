//TODO: all objects. change to use prototyping

function Utils(){
	var LOGGER = new Logger('Utils',log4javascript.Level.TRACE); 
}

Utils.getById = function(array, id){
	return Utils.findAndRequireOne(array, function(element){ return element.getId() == id;});
}

Utils.sum = function(array){
	var sum = 0;
	$.each(array, function(){ sum+= this;});
	return sum;
}

Utils.contains = function(array, comparator){
	return Utils.filter(array,comparator).length >= 1;
};


Utils.filter = function(array, comparator){
	return $.grep(array, function(element, i){ 
		 return comparator(element);
 	});
}


Utils.findAndRequireOne = function(array, comparator){
	var filtered = Utils.filter(array, comparator);
	if(filtered.length == 0)
		throw "utils.js find(): Couldn't find object";
	if(filtered.length > 1)
		throw "utils.js find(): Too many matches found";
	
	return filtered[0];
};

Utils.parseDate = function(timestamp){
	// Timstamp is in format 2011-05-28T17:28:00+0300
	var day = (timestamp.split("T")[0]).split("-");
	var time = (timestamp.split("T")[1]).split(":");
	var date = new Date();
	
	date.setFullYear(day[0],(day[1]-1), (day[2]-1));
	date.setHours(time[0]);
	date.setMinutes(time[1]);
	date.setSeconds(0);
	date.setMilliseconds(0);
	return date;
};
