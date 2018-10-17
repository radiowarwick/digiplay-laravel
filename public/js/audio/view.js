var ws;

$(document).ready(function(){
	ws = WaveSurfer.create({
		container: "#wavesurfer",
		waveColor: "white",
		progressColor: "#d8b222",
		skipLength: 10,
		cursorColor: "white",
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
		set_vocal($(this), "vocal-out");
	});
	$("#btn-set-vocal-in").click(function(){
		set_vocal($(this), "vocal-in");
	});
});

function set_vocal(btn, id) {
	time = parseFloat(ws.getCurrentTime());
	btn.attr("data-seconds", time);

	milliseconds_string = Math.floor((time % 1) * 100);
	if(milliseconds_string < 0)
		milliseconds_string = "00";
	else if(milliseconds_string < 10)
		milliseconds_string = milliseconds_string + "0";

	seconds_string = Math.floor(time % 60);
	if(seconds_string < 10)
		seconds_string = "0" + seconds_string;
	
	time = Math.floor(time / 60);
	if(time == 0)
		time = "0";

	$("#" + id).text(time + ":" + seconds_string + "." + milliseconds_string);

	ws.regions.clear();
	ws.regions.add({
		start: $("#btn-set-vocal-in").attr("data-seconds"),
		end: $("#btn-set-vocal-out").attr("data-seconds"),
		drag: false,
		resize: false,
		color: "rgba(255, 0, 0, 0.5)"
	});
}