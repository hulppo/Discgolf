// TODO: Move to own viewmodel file
function PowerTableViewModel(domainModel){
	var self = this;
	var LOGGER = new Logger('PowerTableViewModel',log4javascript.Level.TRACE);
	
	var domainModel = domainModel;
	
	self.viewModes = { VALUES: "values", PERCENT: "percent"};
	
	self.results = ko.observableArray();
	self.viewMode = ko.observable(self.viewModes.PERCENT);
	self.toggleViewMode = function(){ 
							if(self.viewMode() == self.viewModes.VALUES) 
								self.viewMode(self.viewModes.PERCENT);
							else
								self.viewMode(self.viewModes.VALUES);
							};
	
	
	self.power = new PowerTable();
	
	self.populate = function(players, rounds){
		self.power.populate(players, rounds);
		self.results(self.power.table.entries());
	};
	
}

function PowerRecord(){
	var self = this;
	self.roundCount = 0;
	self.winCount = 0;
	self.loseCount = 0;
	self.tieCount = 0;
	self.powerFor = null;
	self.powerAgainst = null;
	self.winPercent = function(){ return Math.round(self.winCount / self.roundCount * 100); };
	
	self.incRoundCount = function(){ self.roundCount++; };
	self.incWinCount = function(){ self.winCount++; };
	self.incLoseCount = function(){ self.loseCount++; };
	self.incTieCount = function(){ self.tieCount++; };
	
	self.againstOneSelf = function(){ return self.powerFor == self.powerAgainst; };
}


function PowerTable(){
	var self = this;
	var table = new Hashtable();
	var LOGGER = new Logger('PowerTable',log4javascript.Level.TRACE);
	
	// TODO: powers are now calculated twice for each player pair A vs B and B vs A. Should re-use the earlier calculation A vs B.
	self.populate = function(players, rounds){
		LOGGER.info("Calculating power table for " + players.length + " players and " + rounds.length + " rounds.");
		self.clear();
		self.init(players);
		
		$.each(players, function(index, player){
			self.populatePlayerResults(player, players, rounds);
		});
		LOGGER.info("Power table calculated");
	};
	
	self.populatePlayerResults = function(player, players, rounds){
		var playerResults = self.table.get(player);
		$.each(rounds, function(index, round){
			if(round.hasAsParticipant(player.getId())){
				$.each(players, function(index, compareToPlayer){
					if(round.hasAsParticipant(compareToPlayer.getId())){
						var currentPowerRecord = playerResults.get(compareToPlayer);
						currentPowerRecord.incRoundCount();
						var power = round.powers(player,compareToPlayer);
						
						if(power == 0 )
							currentPowerRecord.incTieCount();
						
						if(power > 0 )
							currentPowerRecord.incWinCount();
						
						if(power <  0 )
							currentPowerRecord.incLoseCount();
					}
				});
			}
		});
		
	};
	
	self.init = function(players){
		$.each(players, function(i, playerForRow){
			var results = new Hashtable();
			
			$.each(players, function(i, playerForColumn){
				var powerRecord = new PowerRecord();
				powerRecord.powerFor = playerForRow;
				powerRecord.powerAgainst = playerForColumn;
				results.put(playerForColumn, powerRecord);
			});
			
			self.table.put(playerForRow, results);
		});
	};
	
	self.clear = function(){ self.table = new Hashtable(); };
}


function stringifyHashtable(table){
	var print = "";
	$.each(table.entries(), function(i,entry){
		print += JSON.stringify(entry[0]) + ": " + JSON.stringify(entry[1]) + ", ";
	});
	return print;
}