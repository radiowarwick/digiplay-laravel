function view_message(event) {
	id = $(this).attr("data-message-id");
	$.get(location.href + "/message/" + id, function(data){
		$("#studio-message-subject").text(data.subject);
		$("#studio-message-body").text(data.body);
		$("#studio-message-body").html($("#studio-message-body").html().replace(/\n/g,'<br/>'));

		id = data.id;
		$(".studio-message-row.active").removeClass("active");
		$("[data-message-id='" + id + "']").addClass("active");
		$("[data-message-id='" + id + "']").find(".fa-envelope").remove();
	});
}

function reset_message_binds() {
	$(".studio-message-row").unbind("click");
	$(".studio-message-row").click(view_message);
}

$(document).ready(function(){
	reset_message_binds();
});