
function RoundGroup(groupId, par){
	this.rounds = ko.observableArray([]);
	this.selected = ko.observable(true);
	this.id = groupId;
	this.par = par;
}