
// Globals
// TODO: Get rid of these
var playerListViewModel = null;
var roundListViewModel = null;
var dgAPI = new DGAPIServiceFacade();
LOGGER = new Logger('ROOT',log4javascript.Level.TRACE);


function bindUI(viewModels){
	LOGGER.info("Finally, applying the UI bindings.");
	ko.applyBindings(viewModels.playerListViewModel, $('#playerSelectList').get(0));
	ko.applyBindings(viewModels.roundListViewModel,$('#filteredRoundList').get(0));
	ko.applyBindings(viewModels.powerTableViewModel, $('#powerTable').get(0));
	ko.applyBindings(viewModels.roundGrouperViewModel,$('#roundsByCourseList').get(0));
	ko.applyBindings(viewModels.parGraphViewModel,$('#parGraph').get(0));
	
	LOGGER.info("Ready to rock and roll");
	unblockUI();
}

function blockUI(){
	$.blockUI({ css: { 
        border: 'none', 
        padding: '15px', 
        backgroundColor: '#fff', 
        '-webkit-border-radius': '10px', 
        '-moz-border-radius': '10px', 
        opacity: .5, 
        color: '#000' 
    } }); 
}

function unblockUI(){
	$.unblockUI();
}

$(document).ready(function() {
	
	
	
	window.onerror = function(msg, url, line) {
		LOGGER.error("Ooops! Something went wrong. " + msg);
		unblockUI();
	};
	
	LOGGER.info("Initializing");
	blockUI();
	
	var domainModel = new DomainModel();
	var domainModelInitPromise = domainModel.initP();
	
	domainModelInitPromise.done(function(){ 
		var viewController = new ViewController();
		var roundListViewModel = new RoundListViewModel(domainModel, viewController);
		var playerListViewModel = new PlayerListViewModel(domainModel,viewController);
		var roundGrouperViewModel = new RoundGrouperViewModel(domainModel,viewController);
		var powerTableViewModel = new PowerTableViewModel(domainModel,viewController);
		var parGraphViewModel = new ParGraphViewModel(domainModel, viewController);
		
		viewController.roundListViewModel = roundListViewModel;
		viewController.playerListViewModel = playerListViewModel;
		viewController.roundGrouperViewModel = roundGrouperViewModel;
		viewController.powerTableViewModel = powerTableViewModel;
		viewController.parGraphViewModel = parGraphViewModel;

		bindUI(
				{
					playerListViewModel: playerListViewModel, 
					roundListViewModel: roundListViewModel,
					roundGrouperViewModel: roundGrouperViewModel,
					powerTableViewModel: powerTableViewModel,
					parGraphViewModel: parGraphViewModel
				});
	});
	
	domainModelInitPromise.fail(function(){ 
		LOGGER.error("Initializing failed.");
		unblockUI();
	});
});	


