var is_uploading = false;

function upload(event) {
	if(!is_uploading) {
		is_uploading = true;
		$("#btn-upload").attr("disabled", "disabled");
		$("input[name=\"file\"]").attr("disabled", "disabled");

		form_data = new FormData();
		form_data.append("file", $("#form-upload").find("[name=\"file\"]").get(0).files[0]);
		form_data.append("_token", $("#form-upload").find("[name=\"_token\"]").val());

		$.ajax({
			url: "/audio/upload",
			type: "POST",
			contentType: false,
			cache: false,
			processData: false,
			data: form_data,
			xhr: function(){
				XHR = new window.XMLHttpRequest();

				XHR.upload.addEventListener("progress", progress_update);

				return XHR;
			},
			success: uploaded
		});
	}
}

function uploaded(data) {
	console.log(data);
	$("#btn-upload").removeAttr("disabled");
	$("input[name=\"file\"]").removeAttr("disabled");
	is_uploading = false;

	card = card_template.clone(true);
	card.find("[name=\"title\"]").val(data["title"]);
	card.find("[name=\"artist\"]").val(data["artist"]);
	card.find("[name=\"album\"]").val(data["album"]);
	card.find(".file-name").text(data["filename"]);

	console.log(card);

	$(".card-container").append(card);
}

function progress_update(event) {
	if(event.lengthComputable) {
		percent = Math.round((event.loaded / event.total) * 100) + "%";
		$(".progress-bar").css("width", percent);
		$(".progress-bar").text(percent);
	}
}

function import_audio(event) {
	body = $(this).closest(".card-body");
	body.find(".error").html("");

	data = {
		"filename": body.attr("data-filename").trim()
	};
	body.find("input,select").each(function(){
		data[$(this).attr("name")] = $(this).val().trim();
	});

	errors = new Array();
	if(data["title"] == "")
		errors.push("You must give the track a title.");
	if(data["artist"] == "")
		errors.push("You must give the track an artist.");

	console.log(data);

	if(errors.length > 0)
		body.find(".error").html(errors.join("<br>"));
	else {

	}
}

function delete_audio(event) {
	card = $(this).closest(".card");
	filename = card.find(".card-header").text().trim();

	$.ajax({
		url: "/audio/upload/delete",
		type: "POST",
		data: {
			"filename": filename,
			"_token": $("[name=\"_token\"]").val()
		},
		success: function(result){
			card_container = card.parent();
			card_container.hide({
				effect: "blind",
				complete: function(){
					card_container.remove();
				}
			});
		}
	})
}

var card_template;

$(document).ready(function(){
	$("#btn-upload").click(upload);
	$(".btn-import").click(import_audio);
	$(".btn-delete").click(delete_audio);

	$("input[name=\"file\"]").on("change", function(event){
		$(".progress-bar").css("width", "auto");
		$(".progress-bar").text("0%");
	});

	card_template = $(".audio-upload-card-template").clone();
	card_template.removeClass("d-none");
	card_template.removeClass("audio-upload-card-template");
});