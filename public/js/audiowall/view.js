function reset_wall_binds() {
	$("div.list-group-item[data-wall-page]").click(function(){
		$("div.list-group-item[data-wall-page]").removeClass("active");
		$(this).addClass("active");
		
		page = $(this).attr('data-wall-page');
		$("div.wall-page").hide();
		$("div.wall-page[data-wall-page=" + page + "]").show();
	});
}

function item_play_stop(event) {
	item = $(this).closest(".audiowall-item");
	id = item.attr("data-wall-audio-id");
	status = item.attr("data-play-status");

	if(status == "playing") {
		audio = item.find("audio").eq(0).get(0);
		audio.pause();
		item.attr("data-play-status", "paused");

		item.find(".audiowall-time-play").html("<i class='fa fa-play'></i>");
		item_play_update(event);
	}
	else {
		audio = item.find("audio");
		if(audio.length == 0) {
			audio = $("<audio class='d-none'></audio>");
			audio.append("<source src='/audio/preview/" + id + ".mp3' type='audio/mpeg'>");
			audio.bind("timeupdate", item_play_update);
			audio.bind("ended", item_play_ended);
			item.append(audio);
		}

		audio = audio.eq(0).get(0);
		audio.currentTime = 0;
		audio.play();

		item.attr("data-play-status", "playing");

		item.find(".audiowall-time-play").html("<i class='fa fa-stop'></i>");
	}
}

function item_play_update(event) {
	item = $(this).closest(".audiowall-item");
	if(item.attr("data-play-status") == "playing") {
		audio = item.find("audio").eq(0).get(0);
		length = item.attr("data-item-length");
		time_left = length - $(this).eq(0).get(0).currentTime;
		item.find(".audiowall-time-text").text(time_to_string(time_left));
	}
	else {
		item.find(".audiowall-time-text").text(item.attr("data-item-length-string"));
	}
}

function item_play_ended(event) {
	item = $(this).closest(".audiowall-item");
	item.attr("data-play-status", "paused");
	item.find(".audiowall-time-play").html("<i class='fa fa-play'></i>");

	audio = item.find("audio").eq(0).get(0);
	audio.currentTime = 0;
	audio.pause();

	item_play_update(event);
}

function time_to_string(time) {
	seconds = Math.floor(time % 60);
	time = Math.floor(time / 60);
	string = seconds + "s";

	if(time > 0) {
		string = time + "m " + string;
	}

	return string;
}

function silence_audio() {
	$("[data-play-status=playing]").find(".audiowall-time").trigger("click");
}

$(document).ready(function(){
	reset_wall_binds();

	$(".audiowall-time").unbind("click");
	$(".audiowall-time").click(item_play_stop);
})
