


// TODO: all objects in project: use private methods and fields instead of public
function AjaxRunner(){
	var self = this;
	var LOGGER = new Logger("AjaxRunner",log4javascript.Level.TRACE);
	
	// Properties to set before calling run()
	self.elements = [];
	self.ajaxUrl = function(elements, element){ return "You need to define the ajaxUrl function" };
	self.mapFn = function(data, elements, element){ };
	self.errorFn = function(elements, element){ };
	
	
	
	// The main function to start the show
	self.runP = function(){
		var waitFor = [];
		$.each(self.elements, function(index, element){
			waitFor.push(self.makeRequest(element));
		});
		
		return $.when.apply($, waitFor);
	};
	
	self.makeRequest = function(element){
		var ajaxTarget = self.ajaxUrl(self.elements, element);
		var xhrPromise = self.xhrP(ajaxTarget);
		
		xhrPromise.done(function(allData) {
			self.mapFn(allData, self.elements, element);
		});
		
		xhrPromise.fail(function(url){ self.errorFn(self.elements, element); });
		return xhrPromise;
	};
	
	self.xhrP = function(url, args){
		LOGGER.trace("Making XHR request to: " + url);
		if(!args)
			args = {};
		if(!args.xhrDeferred)
			args.xhrDeferred = $.Deferred();
		if(!args.retries)
			args.retries = 3;
		if(!args.timeout)
			args.timeout = 500;
		
		$.getJSON(url)
			.success(function(data){ 
				LOGGER.debug("Received response for: " + url);
				args.xhrDeferred.resolve(data); 
			})
			.error(function(){ 
				LOGGER.debug("Failed to get response for: " + url);
				if(args.retries-1 <= 0)
					args.xhrDeferred.reject(url);
				else{
					args.retries = args.retries-1;
					setTimeout(function(){self.xhrP(url, args);}, args.timeout);
				}
			});
		
		return args.xhrDeferred.promise();
		
	};

}