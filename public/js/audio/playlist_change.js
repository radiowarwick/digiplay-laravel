$(document).ready(function(){
	$(".playlist-change").click(open_playlist_modal);
	$(".playlist-item").click(playlist_update);
});

var audio_id;

function open_playlist_modal() {
	audio_id = $(this).attr("data-audio-id");

	$.ajax({
		url: "/ajax/playlist",
		type: "POST",
		data: {
			_token: $("[name=\"_token\"]").val(),
			id: audio_id
		},
		success: function(data) {
			$("[data-playlist-id]").removeClass("bg-warning");
			for(i = 0; i < data.length; i++) {
				$("[data-playlist-id=\"" + data[i] + "\"]").addClass("bg-warning");
			}
			$(".playlist-modal").modal();
		}
	});
}

function playlist_update() {
	playlist_id = $(this).attr("data-playlist-id");
	remove = $(this).hasClass("bg-warning");

	$.ajax({
		url: "/audio/playlist/update",
		type: "POST",
		data: {
			_token: $("[name=\"_token\"]").val(),
			audio_id: audio_id,
			playlist_id: playlist_id,
			remove: remove
		},
		success: function(data) {
			item = $("[data-playlist-id=\"" + playlist_id + "\"]");
			if(data.removed == "true") {
				item.removeClass("bg-warning");
			}
			else {
				item.addClass("bg-warning");
			}
		}
	});
}