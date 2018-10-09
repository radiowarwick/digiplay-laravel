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
	$("#btn-upload").removeAttr("disabled");
	$("input[name=\"file\"]").removeAttr("disabled");
	is_uploading = false;

	random = Math.floor(Math.random() * 1000);
	card = card_template.clone(true);

	card.find(".card-header").attr("href", "#track-" + random);
	card.find(".card-body").attr("id", "track-" + random);

	card.find("[for=\"censor-\"]").attr("for", "censor-" + random);
	card.find("#censor-").attr("id", "censor-" + random);

	setValueRandom(card, "title", random, data["title"]);
	setValueRandom(card, "artist", random, data["artist"]);
	setValueRandom(card, "album", random, data["album"]);

	card.find(".file-name").text(data["filename"]);
	card.find(".card-body").attr("data-filename", data["filename"]);

	$(".card-container").append(card);
	reset_binds();
}

function setValueRandom(card, name, random, value) {
	card.find("[name=\"" + name + "\"]").val(value);
	card.find("[name=\"" + name + "\"]").attr("id", name + "-" + random);
	card.find("[for=\"" + name + "-\"]").attr("for", name + "-" + random);
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
		"_token": $("[name=\"_token\"]").val(),
		"filename": body.attr("data-filename").trim(),
		"title": body.find("[name=\"title\"]").val().trim(),
		"artist": body.find("[name=\"artist\"]").val().trim(),
		"album": body.find("[name=\"album\"]").val().trim(),
		"censored": body.find("[name=\"censored\"]").is(":checked"),
		"type": body.find("[name=\"type\"]").val()
	};
	body.find("input,select").each(function(){
		data[$(this).attr("name")] = $(this).val().trim();
	});

	errors = new Array();
	if(data["title"] == "")
		errors.push("You must give the track a title.");
	if(data["artist"] == "")
		errors.push("You must give the track an artist.");

	if(errors.length > 0)
		body.find(".error").html(errors.join("<br>"));
	else {
		$.ajax({
			url: "/audio/upload/import",
			type: "POST",
			data: data,
			success: function(result){
				if(result.status == "error") {
					body.find(".error").html(result.errors.join("<br>"));
				}
				else {
					body.on("hidden.bs.collapse", function(event){
						body.remove();
					});

					body.collapse("hide");
					header = body.parent().find(".card-header");
					icon = header.find(".fa");

					icon.removeClass("fa-arrow-circle-right");
					icon.removeClass("text-warning");
				
					icon.addClass("fa-check-circle");
					icon.addClass("text-success");
				}
			}
		});
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
			card.hide({
				effect: "blind",
				complete: function(){
					card.remove();
				}
			});
		}
	})
}

function reset_binds() {
	$(".btn-import").unbind("click");
	$(".btn-import").click(import_audio);

	$(".btn-delete").unbind("click");
	$(".btn-delete").click(delete_audio);
}

var card_template;

$(document).ready(function(){
	$("#btn-upload").click(upload);
	$(".btn-import").click(import_audio);
	$(".btn-delete").click(delete_audio);

	$("input[name=\"file\"]").change(function(event){
		$(".progress-bar").css("width", "auto");
		$(".progress-bar").text("0%");
	});

	card_template = $(".audio-upload-card-template").clone();
	card_template.removeClass("d-none");
	card_template.removeClass("audio-upload-card-template");
});