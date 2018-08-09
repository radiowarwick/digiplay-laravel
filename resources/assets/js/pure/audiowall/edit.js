function serialize() {
	json = {};

	$("div.list-group-item[data-wall-page]").each(function(){
		wall_number = $(this).attr("data-wall-page");

		json[wall_number] = {};
		json[wall_number]["title"] = strip_string($(this).text());
		json[wall_number]["audio"] = [];

		wall = $("div.wall-page[data-wall-page=" + wall_number + "]");
		count = 0;
		$(wall).find(".audiowall-item[data-wall-audio-id!='']").each(function(){
			title = $(this).find(".audiowall-title").first().text();
			title = strip_string(title);

			data = {};
			data["id"] = $(this).attr("data-wall-audio-id");
			data["name"] = title;
			data["fg"] = $(this).attr("data-fg");
			data["bg"] = $(this).attr("data-bg");
			data["position"] = $(this).attr("data-wall-item");

			json[wall_number]["audio"][count++] = data;
		});
	});

	return btoa(JSON.stringify(json));
}

// Takes a string, removes whitespace before/after string and any newlines/tabs in the string
function strip_string(string) {
	string = string.trim();
	string = string.replace(/(\r\n|\n|\r|\t)/gm,"");
	return string;
}

function move_up(event) {
	event.stopPropagation();
	row = $(this).closest(".list-group-item");
	page = parseInt(row.attr("data-wall-page"));
	
	if(page > 0) {
		previous = $("div.list-group-item[data-wall-page=" + (page - 1) + "]");
		previous.before(row);

		current = $("[data-wall-page=" + page + "]");
		before = $("[data-wall-page=" + (page - 1) + "]");
		current.attr("data-wall-page", page - 1);
		before.attr("data-wall-page", page);
	}	
}

function move_down(event) {
	event.stopPropagation();
	row = $(this).closest(".list-group-item");
	page = parseInt(row.attr("data-wall-page"));
	
	if(page < $("div.list-group-item[data-wall-page]").length - 1) {
		next = $("div.list-group-item[data-wall-page=" + (page + 1) + "]");
		next.after(row);

		current = $("[data-wall-page=" + page + "]");
		after = $("[data-wall-page=" + (page + 1) + "]");
		current.attr("data-wall-page", page + 1);
		after.attr("data-wall-page", page);
	}
}

function remove(event) {
	event.stopPropagation();

	if($(this).attr("data-state") == "ready") {
		$(this).popover("show");
		$(this).attr("data-state", "show");
	}
	else {
		$(this).popover("hide");

		row = $(this).closest(".list-group-item");
		page = parseInt(row.attr("data-wall-page"));
		change = row.hasClass("active");

		$("[data-wall-page=" + page.toString() + "]").remove();
		renumber_walls();
		reset_add_bar();
		reset_wall_binds();

		if(change)
			$("div.list-group-item[data-wall-page=0]").click();
	}
}

function remove_reset(event) {
	event.stopPropagation();

	$(this).attr("data-state", "ready");
	$(this).popover("hide");
}

function renumber_walls() {
	walls = $("div.list-group-item[data-wall-page!='']");

	i = 0;
	walls.each(function(){
		page = parseInt($(this).attr("data-wall-page"));
		$("[data-wall-page=" + page + "]").attr("data-wall-page", i);

		j = 0;
		$("div.wall-page[data-wall-page=" + i + "]").find(".audiowall-item").each(function(){
			$(this).attr("data-wall-item", j++);
		});
		i++;
	});
}

var is_moving_element = false;
var is_adding_element = false;
var moving_element;
var adding_element;
var template_item;

function move(event) {
	item = $(this).closest(".audiowall-item");
	if(is_moving_element) {
		if(moving_element != item) {
			copy1 = item.clone(true);
			copy2 = moving_element.clone(true);

			moving_element.replaceWith(copy1);
			item.replaceWith(copy2);

			renumber_walls();
			reset_binds();
		}
		is_moving_element = false;

		item_undim_all();
	}
	else if(is_adding_element) {
		new_element = item.clone(true);
		new_element.attr("data-wall-audio-id", adding_element.attr("data-wall-audio-id"));
		new_element.css("background", "#" + adding_element.attr("data-bg"));
		new_element.css("color", "#" + adding_element.attr("data-fg"));
		new_element.find(".audiowall-item-title-text").text(adding_element.find(".audiowall-item-title-text").text());
		new_element.find(".audiowall-time-text").text(adding_element.find(".audiowall-time-text").text());

		item.replaceWith(new_element);

		renumber_walls();
		reset_binds();
		item_undim_all();

		is_adding_element = false;
	}
	else {
		moving_element = item;
		is_moving_element = true;

		$(".audiowall-item").addClass("audiowall-item-transparent");
		moving_element.removeClass("audiowall-item-transparent");
	}
}

function delete_move(event) {
	if(is_moving_element || is_adding_element) {
		if(is_moving_element) {
			is_moving_element = false;
			moving_element.replaceWith(template_item.clone(true));
		}
		else {
			is_adding_element = false;
		}

		item_undim_all();
		renumber_walls();
		reset_binds();
	}
}

var editing = false;
var editing_row;
var editing_hidden_row;

function start_edit(event) {
	event.stopPropagation();

	if(editing) {
		editing_row.hide();
		editing_hidden_row.show();
	}
	else 
		editing = true;
	
	editing_hidden_row = $(this).closest(".list-group-item");
	editing_hidden_row.hide();
	editing_row.find("input").val(strip_string(editing_hidden_row.text()));
	editing_row.insertAfter(editing_hidden_row);
	reset_edit_binds();
	editing_row.show();
}

function cancel_edit(event) {
	event.stopPropagation();
	editing = false;

	editing_row.hide();
	editing_hidden_row.show();
}

function save_edit(event) {
	event.stopPropagation();
	editing = false;

	text = strip_string(editing_row.find("input").val());
	if(text !== "")
		editing_hidden_row.find(".audiowall-wall-name").text(text);
	
	editing_row.hide();
	editing_hidden_row.show();
}

function audiowall_add(event) {
	adding_element = $(this).closest(".audiowall-item");
	is_adding_element = true;

	$(".audiowall-item").addClass("audiowall-item-transparent");
	$(".audiowall-search-results").modal("hide");
}

var adding = false;
var adding_row_template;

function start_add(event) {
	event.stopPropagation();

	$(this).closest(".list-group-item").hide();
	$(".audiowall-add-row").show();
	$(".audiowall-add-row").find("input").val("");
}

function cancel_add(event) {
	event.stopPropagation();

	$(".audiowall-add-row").hide();
	$(".audiowall-add-yes").show();
}

function save_add(event) {
	event.stopPropagation();

	text = strip_string($(".audiowall-add-row").find("input").val());
	new_row = adding_row_template.clone(true);
	new_row.removeClass("audiowall-add-template");
	new_row.find(".audiowall-wall-name").text(text);
	new_row.attr("data-wall-page", "100");
	new_row.show();
	$("div.list-group-item[data-wall-page]").last().after(new_row);

	$(".audiowall-add-row").hide();
	$(".audiowall-add-row").find("input").val("");

	wall = $("<div class=\"row wall-page\" data-wall-page=\"100\" style=\"display:none;\">");
	$(".audiowall-wall-container").append(wall);
	for(i = 0; i < 12; i++) {
		wall.append(template_item.clone(true));
	}

	renumber_walls();
	reset_sidebar_binds();
	reset_add_bar();
	reset_wall_binds();
	reset_binds();
}

function reset_add_bar() {
	page = $("div.list-group-item[data-wall-page]").last().attr("data-wall-page");
	if(parseInt(page) >= 7) {
		$(".audiowall-add-yes").hide();
		$(".audiowall-add-no").show();
	}
	else {
		$(".audiowall-add-no").hide();
		$(".audiowall-add-yes").show();
	}
}

function item_dim(event) {
	item = $(this).closest(".audiowall-item");
	if((is_moving_element && !item.is(moving_element)) || is_adding_element) {
		item.addClass("audiowall-item-transparent");
	}
}

function item_undim(event) {
	item = $(this).closest(".audiowall-item");
	item.removeClass("audiowall-item-transparent");
}

function item_undim_all() {
	$(".audiowall-item").removeClass("audiowall-item-transparent");
}

function search(event) {
	event.preventDefault();

	param = {
		type: ["Jingle", "Music"],
		filter: ["title", "artist", "album"],
		query: $(".audiowall-search-input").val(),
		_token: $("[name=_token]").val()
	};

	$.post("/ajax/search", param, function(data){
		$(".audiowall-search-results").modal("show");

		$(".audiowall-search-results-container").empty();

		for(i = 0; i < data.length; i++) {
			clone = $(".audiowall-item-search").clone(true);
			clone.find(".audiowall-item-title-text").text(data[i].title);
			clone.attr("data-wall-audio-id", data[i].id);
			clone.find(".audiowall-time-text").text(data[i].length_string)
			clone.removeClass("audiowall-item-search");
			clone.show();

			$(".audiowall-search-results-container").append(clone);
		}
	});

	reset_search_binds();
}

var is_saving = false;

function save(event) {
	if(!is_saving) {
		is_saving = true;
		data = {
			wall: serialize(),
			_token: $("[name=_token]").val()
		};

		$(this).html("<i class=\"fa fa-refresh fa-spin\"></i>");

		$.post(window.location.href + "/save", data, function(data){
			location.reload();
		}).fail(function(){
			is_saving = false;
			$(this).html("Save");
			alert("An error occured whilst saving");
		});
	}
}

var editing_item;

function item_show_edit(event) {
	$(".audiowall-item-settings").modal("show");
	editing_item = $(this).closest(".audiowall-item");

	bg = editing_item.attr("data-bg");
	item_colour_update(bg);

	title = strip_string(editing_item.find(".audiowall-item-title-text").text());
	$(".audiowall-item-name").val(title);
}

function item_settings_save(event) {
	bg = $(".audiowall-edit-example").attr("data-bg");
	fg = $(".audiowall-edit-example").attr("data-fg");

	editing_item.attr("data-bg", bg);
	editing_item.attr("data-fg", fg);
	editing_item.css("background", "#" + bg);
	editing_item.css("color", "#" + fg);

	title = strip_string($(".audiowall-item-name").val());
	editing_item.find(".audiowall-item-title-text").text(title);

	$(".audiowall-item-settings").modal("hide");
}

function item_colour_option(event) {
	bg = $(this).attr("data-background");
	item_colour_update(bg);
}

function item_open_colour_picker(event) {
	$(".audiowall-item-settings-colour").focus();
	$(".audiowall-item-settings-colour").val("#" + $(".audiowall-edit-example").attr("data-bg"));
	$(".audiowall-item-settings-colour").click();
}

function item_colour_picker_change(event) {
	hex = $(this).val();
	hex = hex.replace("#", "");
	item_colour_update(hex);
}

function item_colour_update(bg) {
	fg = foreground_colour(bg);
	
	$(".audiowall-edit-example").css("background", "#" + bg);
	$(".audiowall-edit-example").css("color", "#" + fg);

	$(".audiowall-edit-example").attr("data-bg", bg);
	$(".audiowall-edit-example").attr("data-fg", fg);
}

function foreground_colour(hex) {
	red = parseInt(hex.substring(0, 2), 16);
	green = parseInt(hex.substring(2, 4), 16);
	blue = parseInt(hex.substring(4), 16);

	if((red * 0.299 + green * 0.587 + blue * 0.114) > 186)
		return '000000';
	return 'ffffff';
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

function time_to_string(time) {
	seconds = Math.floor(time % 60);
	time = Math.floor(time / 60);
	string = seconds + "s";

	if(time > 0) {
		string = time + "m " + string;
	}

	return string;
}

function reset_binds() {
	$(".audiowall-move").unbind("click");
	$(".audiowall-move").click(move);

	$(".audiowall-settings").unbind("click");
	$(".audiowall-settings").click(item_show_edit);

	$(".audiowall-move").mouseleave(item_dim);
	$(".audiowall-move").mouseenter(item_undim);
}

function reset_edit_binds() {
	$(".audiowall-edit-cancel").unbind("click");
	$(".audiowall-edit-cancel").click(cancel_edit);
	
	$(".audiowall-edit-save").unbind("click");
	$(".audiowall-edit-save").click(save_edit);

	$(".audiowall-edit-input").unbind("keypress");
	$(".audiowall-edit-input").keypress(function(event){
		keycode = event.keyCode || event.which;
		if(keycode == '13')
			save_edit(event);
	});
}

function reset_sidebar_binds() {	
	$(".audiowall-move-up").unbind("click");
	$(".audiowall-move-up").click(move_up);
	
	$(".audiowall-move-down").unbind("click");
	$(".audiowall-move-down").click(move_down);
	
	$(".audiowall-remove").unbind("click");
	$(".audiowall-remove").click(remove);

	$(".audiowall-remove").unbind("mouseleave");
	$(".audiowall-remove").mouseleave(remove_reset);
	
	$(".audiowall-edit").unbind("click");
	$(".audiowall-edit").click(start_edit);
}

function reset_search_binds() {
	$(".audiowall-search-add").unbind("click");
	$(".audiowall-search-add").click(audiowall_add);
}

reset_binds();
reset_edit_binds();
reset_sidebar_binds();

$(document).ready(function(){
	template_item = $(".audiowall-item-template").clone(true);
	template_item.removeClass("audiowall-item-template");

	editing_row = $(".edit-row");
	adding_row_template = $(".audiowall-add-template").clone(true);
	template_item.removeClass("audiowall-add-template");

	$(".audiowall-trash").click(delete_move);

	$(".audiowall-add-yes").click(start_add);
	$(".audiowall-add-cancel").click(cancel_add);
	$(".audiowall-add-add").click(save_add);

	$(".audiowall-search").submit(search);
	$(".audiowall-save").click(save);

	$(".audiowall-colour-option").click(item_colour_option);
	$(".audiowall-item-settings-colour-btn").click(item_open_colour_picker);
	$(".audiowall-item-settings-colour").change(item_colour_picker_change);
	$(".audiowall-item-save").click(item_settings_save);

	$(".audiowall-time").click(item_play_stop);
});