var ws;

$(document).ready(function(){
	ws = WaveSurfer.create({
		container: "#wavesurfer",
		waveColor: "white",
		progressColor: "#d8b222",
		skipLength: 10
	});

	href = window.location.href.split("/");
	id = href[href.length - 1];

	ws.load("/audio/preview/" + id + ".mp3");

	$("#btn-forward").click(function(){
		ws.skipForward();
	});

	$("#btn-backward").click(function(){
		ws.skipBackward();
	});

	$("#btn-play-pause").click(function(){
		ws.playPause();

		btn = $(this);
		if(ws.isPlaying())
			btn.html("<i class=\"fa fa-pause\"></i>");
		else
			btn.html("<i class=\"fa fa-play\"></i>");
	});
});