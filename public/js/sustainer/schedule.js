$(document).ready(function(){
	$("#prerec-select").selectpicker({
		liveSearch: true
	}).ajaxSelectPicker({
		ajax: {
			url: "/ajax/search",
			method: "POST",
			data: function() {
				data = {
					_token: $("[name=\"_token\"]").val(),
					query: "{{{q}}}",
					type: ["Prerec"],
					filter: ["title", "artist", "album"],
				}
				return data;
			}
		},
		locale: {
			emptyTitle: "Search for prerecs"
		},
		preprocessData: function(data) {
			tracks = [];
			for(i = 0; i < data.length; i++) {
				entry = {
					value: data[i].id,
					text: data[i].title + " by " + data[i].artist
				}
				tracks.push(entry);
			}
			return tracks;
		}
	});
});