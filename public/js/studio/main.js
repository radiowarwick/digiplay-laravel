var loc;

function is_censor_period() {
	hour = new Date().getHours();

	if(hour >= CENSOR_START && hour < CENSOR_END)
		return true;
	return false;
}

function view_message(event) {
	id = $(this).attr("data-message-id");
	$.get(loc + "message/" + id, function(data){
		$("#studio-message-subject").text(data.subject);
		$("#studio-message-body").text(data.body);
		$("#studio-message-body").html($("#studio-message-body").html().replace(/\n/g,'<br/>'));

		id = data.id;
		$("[data-message-id]").removeClass("active");
		$("[data-message-id='" + id + "']").addClass("active");
		$("[data-message-id='" + id + "']").find(".fa-envelope").remove();
	});
}

var is_searching = false;

function search(event) {
	if(!is_searching) {
		is_searching = true;
		query = $("[name='query']").val();
		
		filter = [];
		if($("#studio-check-title").is(":checked"))
			filter.push("title");
		if($("#studio-check-artist").is(":checked"))
			filter.push("artist");
		if($("#studio-check-album").is(":checked"))
			filter.push("album");

		$(".btn-search").attr("disabled", "disabled");
		$(".studio-song-search-table").hide();
		$(".studio-song-search-welcome").hide();
		$(".studio-song-search-none").hide();
		$(".studio-song-search-loading").show();
		$(".studio-song-search-table-results").empty();

		data = {
			"query": query,
			"type": ["Music"],
			"filter": filter,
			"censor": is_censor_period(),
			"limit": 50
		};
		$.post("/ajax/search", data, function(data){
			$(".btn-search").removeAttr("disabled");
			$(".studio-song-search-loading").hide();
			if(data.length > 0) {
				for(i = 0; i < data.length; i++) {
					row = $("<tr data-audio-id=\"" + data[i].id + "\"></tr>");
					if(data[i].censor == "f")
						row.append("<td class=\"icon\"><i class=\"fa fa-music\"></i></td>");
					else
						row.append("<td class=\"icon censor\"><i class=\"fa fa-exclamation-circle\"></i></td>");

					row.append("<td class=\"artist text-truncate\">" + data[i].artist + "</td>");
					row.append("<td class=\"title text-truncate\">" + data[i].title + "</td>");
					row.append("<td class=\"album text-truncate\">" + data[i].album + "</td>");
					row.append("<td class=\"length text-truncate\">" + data[i].length_string + "</td>");
					$(".studio-song-search-table-results").append(row);
				}
				reset_search_result_binds();
				$(".studio-song-search-table").show();
			}
			else
				$(".studio-song-search-none").show();
		}).fail(function(){
			$(".studio-song-search-loading").hide();
			$(".studio-song-search-none").show();
		});

		is_searching = false;
	}
}

function load_song(event) {
	id = $(this).attr("data-audio-id");
	$.get(loc + "addplan/" + id, function(data){
		if(data.message == "success") {
			card = $("<div class=\"studio-card card\" data-item-id=\"" + data.id + "\"></div>");
			card.append($("<div class=\"card-body\"></div>"));

			right = $("<div class=\"pull-right\"></div>");
			right.text(data.length_string + " ");
			right.html(right.html() + "<span class=\"studio-card-remove\"><i class=\"fa fa-lg fa-times-circle\"></i></span>");

			if(data.censor == "t")
				icon = "<i class=\"censor fa fa-exclamation-circle\"></i>";
			else
				icon = "<i class=\"fa fa-music\"></i>";

			card_body = card.find(".card-body");
			card_body.text(" " + data.title + " - " + data.artist);
			card_body.html(icon + card_body.html());
			card_body.append(right);

			$(".studio-showplan").append(card);
			reset_showplan_binds();
		}
	});
}


function remove_song(event) {
	event.stopPropagation();

	item = $(this).closest(".studio-card");
	$.get(loc + "removeplan/" + item.attr("data-item-id"), function(data){
		if(data.message == "success") {
			item.remove();
		}
	});
}

function select_song(event) {
	item = $(this);
	$.get(loc + 'selectitem/' + item.attr("data-item-id"), function(data){
		if(data.message == "success") {
			$(".studio-card").removeClass("active");
			item.addClass("active");
		}
	});
}

function log_song(event) {
	artist = $(".studio-log-artist").val();
	title = $(".studio-log-title").val();

	if(artist.length > 0 && title.length > 0) {
		data = {
			"artist": artist,
			"title": title
		};

		$.post(loc + "log", data, function(data){
			if(data.message == "success") {
				$(".studio-log-artist").val("");
				$(".studio-log-title").val("");
			}
		});
	}
}

function reset_message_binds() {
	$("[data-message-id]").unbind("click");
	$("[data-message-id]").click(view_message);
}

function reset_search_result_binds() {
	$(".studio-song-search-table-results").find("tr").unbind("dblclick");
	$(".studio-song-search-table-results").find("tr").dblclick(load_song);
}

function reset_showplan_binds() {
	$(".studio-card-remove").unbind("click");
	$(".studio-card-remove").click(remove_song);

	$(".studio-card").unbind("dblclick");
	$(".studio-card").dblclick(select_song);
}

$(document).ready(function(){
	reset_message_binds();
	reset_showplan_binds();

	$("[name='query']").keypress(function(event){
		keycode = event.keyCode || event.which;
		if(keycode == '13')
			search(event);
	});
	$(".btn-search").click(search);

	$("[name='submit-log']").click(log_song);

	$(".studio-playlist-container").find("tr[data-audio-id]").dblclick(load_song);

	loc = location.href;
	if(loc.substr(loc.length - 1) != '/')
		loc = loc + '/';
});