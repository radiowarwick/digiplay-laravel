$(document).ready(function(){
	$(".playlist-change").click(open_playlist_modal);
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
	})
}