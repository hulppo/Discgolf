
// Globals
// TODO: Get rid of these
var playerListViewModel = null;
var roundListViewModel = null;
var dgAPI = new DGAPIServiceFacade();
LOGGER = new Logger('ROOT',log4javascript.Level.TRACE);


function createPlayerListViewModelP(){
	var playersPromise = dgAPI.getPlayersP();
	playersPromise.fail( function() { LOGGER.error("Fatal error: Failed to load player listing. Will abort");  });
	
	playersPromise.done(
			function(players){
				LOGGER.info("Successfully loaded player list with " + players.length + " players");
				playerListViewModel = new PlayerListViewModel();
				playerListViewModel.setPlayers(players);
			}
	);
	return playersPromise;
}

function createRoundListViewModelP(domainModel){
	var roundsPromise = dgAPI.getRoundsP();
	roundsPromise.fail( function() { LOGGER.error("Fatal error: Failed to load round listing. Will abort");  });
	
	roundsPromise.done(
			function(rounds){
				
				var round1 = rounds[0];
				LOGGER.error(round1.getResults().length + " / " +    round1.getDTO().getResults().length)
				if(round1.getDTO().getResults().length == 0)
					throw "pöö";
				
				
				LOGGER.debug("Creating new RoundListViewModel");
				roundListViewModel = new RoundListViewModel();
				roundListViewModel.setRounds(rounds);
				roundListViewModel.setDomainModel(domainModel);
			}
	);
	
	return roundsPromise;
}


function createRoundGrouperViewModelP(){
	var allDone = $.Deferred();
	var coursesPromise = dgAPI.getCoursesP();
	coursesPromise.fail(function(){ LOGGER.error("Fatal error: Failed to load course listing. Will abort"); });
	
	coursesPromise.done( function(courses) {
		/*
		 * LOGGER.error("Loading data for courses"); var promise2 =
		 * dgAPI.loadCourseDetailsP(courses); promise2.fail(
		 * function(){ LOGGER.error("Fatal error. Cannot continue. Aborting.");
		 * }); promise2.done(function(){
		 */ 
			LOGGER.error("Courses loaded successfully"); 
			initRoundGrouperViewModel();
			roundGrouperViewModel.setCourses(courses);
			allDone.resolve();
		// });
		
	});
	
	return allDone;
	
}

function createPowerTableViewModelP(){
	initPowerTableViewModel();
}

function bindUI(viewModels){
	LOGGER.info("Finally, applying the UI bindings.");
	ko.applyBindings(viewModels.playerListViewModel, $('#playerSelectList').get(0));
	ko.applyBindings(viewModels.roundListViewModel,$('#filteredRoundList').get(0));
	ko.applyBindings(viewModels.powerTableViewModel, $('#powerTable').get(0));
	ko.applyBindings(viewModels.roundGrouperViewModel,$('#roundsByCourseList').get(0));
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
		
		viewController.roundListViewModel = roundListViewModel;
		viewController.playerListViewModel = playerListViewModel;
		viewController.roundGrouperViewModel = roundGrouperViewModel;
		viewController.powerTableViewModel = powerTableViewModel;

		bindUI(
				{
					playerListViewModel: playerListViewModel, 
					roundListViewModel: roundListViewModel,
					roundGrouperViewModel: roundGrouperViewModel,
					powerTableViewModel: powerTableViewModel
				});
	});
	
	domainModelInitPromise.fail(function(){ 
		LOGGER.error("Initializing failed.");
		unblockUI();
	});
});	


