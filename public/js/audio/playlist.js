$(document).ready(function(){
	$('.remove-track').popover();

	$(".remove-track").click(delete_track);
	$(".remove-track").mouseout(reset_delete);
});

function delete_track() {
	btn = $(this);
	state = btn.attr("data-state");

	if(state == "ready") {
		btn.attr("data-state", "primed");
		btn.popover("show");
	}
	else if(state == "primed") {
		$.ajax({
			url: "/audio/playlist/remove",
			type: "POST",
			data: {
				_token: $("[name=\"_token\"]").val(),
				id: btn.attr("data-id")
			},
			success: function(data){
				if(data.status == "ok") {
					btn.closest("tr").remove();
				}
			}
		});
	}
}

function reset_delete() {
	$(this).attr("data-state", "ready");
	$(this).popover("hide");
}