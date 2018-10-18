$(document).ready(function(){
	$(".slot").dblclick(edit_slot);

	$("#modal-save").click(save_slot);
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
		playlist: $("#modal-playlist").val()
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