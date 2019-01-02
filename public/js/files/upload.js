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
			success: function(){
				location.reload();
			}
		});
	}
}

function progress_update(event) {
	if(event.lengthComputable) {
		percent = Math.round((event.loaded / event.total) * 100) + "%";
		$(".progress-bar").css("width", percent);
		$(".progress-bar").text(percent + " - " + filename);
	}
}

var filename;

$(document).ready(function(){
	$("#btn-upload").click(upload);

	$("input[name=\"file\"]").change(function(event){
		fileparts = $(this).val().split("\\");
		filename = fileparts[fileparts.length - 1];

		$(".progress-bar").css("width", "auto");
		$(".progress-bar").text("0% - " + filename);
		$("#btn-upload").removeAttr("disabled");
	});
});