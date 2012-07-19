

function UILogger() {
	var self = this;
	
	self.info = function(message){
		var logDiv = $("<div class='logEntry'/>").appendTo("#rollingLog");
		self.logAppend(logDiv, message);
		self.scroll();
		return logDiv;
	};

	self.error = function(message){
		var logDiv = $("<div class='logEntry'/>").appendTo("#rollingLog");
		self.errorAppend(logDiv, message);
		self.scroll();
		return logDiv;
	};
	
	self.logAppend = function(logDiv, message){
		$("<span class='logNormal'/>").text(message).appendTo(logDiv);
	};
	
	self.errorAppend = function(logDiv, message){
		$("<span class='logError'/>").text(message).appendTo(logDiv);
	};
	
	self.success = function(message, logDiv){
		if(!logDiv) logDiv = $("#rollingLog > DIV:last");
		$("<span class='logSuccess'/>").text(message).appendTo(logDiv);
	}

	

	self.scroll = function() {
	    var height = $("#rollingLog").get(0).scrollHeight;
	    $("#rollingLog").animate({
	        scrollTop: height
	    }, 500);
	};
	
}