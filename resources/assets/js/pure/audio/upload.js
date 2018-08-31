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

function uploaded(event) {
	console.log("UPLOADED");
}

function progress_update(event) {
	if(event.lengthComputable) {
		percent = Math.round((event.loaded / event.total) * 100) + "%";
		$(".progress-bar").css("width", percent);
		$(".progress-bar").text(percent);
	}
}

$(document).ready(function(){
	$("#btn-upload").click(upload);
});