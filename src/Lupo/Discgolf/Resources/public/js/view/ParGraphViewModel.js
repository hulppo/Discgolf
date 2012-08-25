function ParGraphViewModel(domainModel, viewController){
	var self = this;
	var LOGGER = new Logger('ParGraphViewModel',log4javascript.Level.TRACE);
	var domainModel = domainModel;
	var viewController = viewController;

	var previousPoint = null;
	var plotter = null;
	
	self.isReady = ko.observable(false);
	
	self.update = function(){
		LOGGER.info("Plotting distance to par");
		
		var data = [];
		$.each(viewController.playerListViewModel.getSelectedPlayers(), function(index, player){
			data.push(self.produceData(player));
		});
		
		if(data.length > 0){
			if(plotter == null){
				self.initPlotter(data);
			}else {
				plotter.setData(data);
				plotter.setupGrid();
				plotter.draw();
			}
		}
		
		self.isReady(data.length > 0);
	}
	
	self.initPlotter = function(data){
		plotter = $.plot($("#distanceToParOverTime"), data,  
				 {
		             series: {
		                 lines: { show: true },
		                 points: { show: true }
		             },
		             grid: { hoverable: true, clickable: true },
		             xaxis: { mode: "time" } 
		           });

				 $("#distanceToParOverTime").bind("plotclick", function (event, pos, item) {
				        if (item) {
				            var series = plotter.getData();
				            var value = series[item.seriesIndex].data[item.dataIndex];
				            viewController.roundListViewModel.showScoreCard(value.round);
				        }
				    });
				 
				 $("#distanceToParOverTime").bind("plothover", function (event, pos, item) {
				        $("#x").text(pos.x.toFixed(2));
				        $("#y").text(pos.y.toFixed(2));

				            if (item) {
				                if (previousPoint != item.dataIndex) {
				                    previousPoint = item.dataIndex;
				                    
				                    $("#tooltip").remove();
				                    
				                    var series = plotter.getData();
				                    var value = series[item.seriesIndex].data[item.dataIndex];
				                    var courseName = domainModel.getCourse(value.round.getCourseId()).getName();
				                    var timestamp = value.round.getTimestamp();
				                    var tooltipContent = courseName + " " + timestamp;
				                    showTooltip(item.pageX, item.pageY,
				                    		tooltipContent);
				                }
				            }
				            else {
				                $("#tooltip").remove();
				                previousPoint = null;            
				            }
				    });

	};
	
	self.produceData = function(player){
		var data = {};
		data.label = player.getName();
		data.data = [];
		$.each(viewController.roundListViewModel.filteredRounds(), function(index, round){
			if(round.hasAsParticipant(player.getId())){
				var total = round.getPlayerTotal(player.getId());
				var par = domainModel.getCourse(round.getCourseId()).getPar();
				var entry = [round.getTimestamp(), total-par];
				entry.round = round;
				data.data.push(entry);
			}
		});
		
		return data;
	}
	
	function showTooltip(x, y, contents) {
        $('<div id="tooltip">' + contents + '</div>').css( {
            position: 'absolute',
            display: 'none',
            top: y + 5,
            left: x + 5,
            border: '1px solid #fdd',
            padding: '2px',
            color: 'black',
            'background-color': '#fee',
            opacity: 0.80
        }).appendTo("body").fadeIn(200);
    }
	
}