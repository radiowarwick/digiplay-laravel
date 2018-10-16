var ws;

$(document).ready(function(){
	ws = WaveSurfer.create({
		container: "#wavesurfer",
		waveColor: "white",
		progressColor: "#d8b222",
		skipLength: 10,
		plugins: [
			WaveSurferRegions.create({
				regions: [
					{
						start: $("#btn-set-vocal-in").attr("data-seconds"),
						end: $("#btn-set-vocal-out").attr("data-seconds"),
						drag: false,
						resize: false,
						color: "rgba(255, 0, 0, 0.5)"
					}
				]
			}),
			WaveSurferTimeline.create({
				container: "#wavesurfer-timeline",
				primaryFontColor: "#fff",
				secondaryFontColor: "#fff"
			})
		]
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

	$("#btn-set-vocal-out").click(function(){
		set_vocal($(this));
	});
	$("#btn-set-vocal-in").click(function(){
		set_vocal($(this));
	});
});

function set_vocal(btn) {
	time = ws.getCurrentTime();
	btn.attr("data-seconds", time);

	ws.regions.clear();
	ws.regions.add({
		start: $("#btn-set-vocal-in").attr("data-seconds"),
		end: $("#btn-set-vocal-out").attr("data-seconds"),
		drag: false,
		resize: false,
		color: "rgba(255, 0, 0, 0.5)"
	});
}