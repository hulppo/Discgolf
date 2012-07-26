var logr = log4javascript.getLogger('rootLogger');
var logAppender = new log4javascript.PopUpAppender();
var popUpLayout = new log4javascript.PatternLayout("%d{HH:mm:ss} %-5p %c - %m%n");
logAppender.setLayout(popUpLayout);
logAppender.setThreshold(log4javascript.Level.ERROR); // only display errors
logr.addAppender(logAppender);

var uiLog = new UILogger();

function Logger(name, level){
	var self = this;
	var logger = log4javascript.getLogger(name);
	logger.setLevel(level);
	logger.addAppender(logAppender);
	
	self.info = function(message){
		logger.info(message);
		uiLog.info(message);
	};
	
	self.debug = function(message){
		logger.debug(message);
	};
	
	self.trace = function(message){
		logger.trace(message);
	};
	
	self.warn = function(message){
		logger.warn(message);
	};
	
	self.error = function(message){
		logger.error(message);
		uiLog.error(message);
	};
	
}