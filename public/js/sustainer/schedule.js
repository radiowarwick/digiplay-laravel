$(document).ready(function(){
	$(".slot").dblclick(edit_slot);

	$("#modal-save").click(save_slot);
	$("#modal-clear").click(clear_prerec);

	$("#modal-prerec").selectpicker({
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

var slot_id;

function edit_slot() {
	slot_id = $(this).attr("data-slot-id");
	playlist_id = $(this).attr("data-playlist-id");
	prerec_id = $(this).attr("data-prerec-id");

	$(".modal").find("[value=\"" + playlist_id + "\"]").attr("selected", "selected");

	$(".modal").modal("show");
}

function save_slot() {
	data = {
		_token: $("[name=\"_token\"]").val(),
		id: slot_id,
		playlist: $("#modal-playlist").val(),
		audio: $("#modal-prerec").val()
	}

	$.ajax({
		url: window.location.href,
		method: "POST",
		data: data,
		success: function(result){
			if(result.status == "ok") {
				$("[data-slot-id=\"" + slot_id + "\"]").css("background", "#" + result.colour);

				$(".modal").modal("hide");
			}
		}
	})
}

function clear_prerec() {
	$("#modal-prerec").val("-1");
}