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

	$("#modal-prerec").on("change", function(event) {
		id = $(this).val();
		text = $("[value=\"" + $(this).val() + "\"]").text();

		$("#modal-prerec-name").text(text);
		$("#modal-prerec-name").attr("data-id", id);
	});
});

var slot_id;

function edit_slot() {
	slot_id = $(this).attr("data-slot-id");
	playlist_id = $(this).attr("data-playlist-id");
	prerec_id = $(this).attr("data-prerec-id");

	hour = $(this).attr("data-hour");
	day = $(this).attr("data-day");

	days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];

	hour = (hour < 10 ? "0" + hour : hour) + ":00";
	$("#modal-time").text(days[day-1] + " " + hour);

	$(".modal").find("[value=\"" + playlist_id + "\"]").attr("selected", "selected");
	if(prerec_id == "") {
		$("#modal-prerec-name").text("None");
		$("#modal-prerec-name").attr("data-id", "-1");
		$(".modal").modal("show");
	}
	else {
		$.ajax({
			url: "/ajax/detail",
			method: "POST",
			data: {
				_token: $("[name=\"_token\"]").val(),
				id: prerec_id
			},
			success: function(result){
				if(result.status == "ok") {
					$("#modal-prerec-name").text(result.title + " by " + result.artist);
					$("#modal-prerec-name").attr("data-id", result.id);
				}
				else {
					$("#modal-prerec-name").text("None");
					$("#modal-prerec-name").attr("data-id", "-1");
				}
				$(".modal").modal("show");
			}
		});
	}
}

function save_slot() {
	data = {
		_token: $("[name=\"_token\"]").val(),
		id: slot_id,
		playlist: $("#modal-playlist").val(),
		audio: $("#modal-prerec-name").attr("data-id")
	}

	$.ajax({
		url: window.location.href,
		method: "POST",
		data: data,
		success: function(result){
			if(result.status == "ok") {
				$("[data-slot-id=\"" + slot_id + "\"]").css("background", "#" + result.colour);

				if(result.audio != null) {
					$("[data-slot-id=\"" + slot_id + "\"]").html("<i class=\"fa fa-clock-o\"></i>");
					$("[data-slot-id=\"" + slot_id + "\"]").attr("data-prerec-id", result.audio);
				}
				else {
					$("[data-slot-id=\"" + slot_id + "\"]").attr("data-prerec-id", result.audio);
					$("[data-slot-id=\"" + slot_id + "\"]").html("");
				}

				$(".modal").modal("hide");
			}
		}
	})
}

function clear_prerec() {
	$("#modal-prerec-name").text("None");
	$("#modal-prerec-name").attr("data-id", "-1");
}