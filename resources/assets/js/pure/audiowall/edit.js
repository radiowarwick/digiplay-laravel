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
			console.log(wall_number + " - " + title);

			data = {};
			data["id"] = $(this).attr("data-wall-audio-id");
			data["name"] = title;

			json[wall_number]["audio"][count++] = data;
		});
	});

	return JSON.stringify(json);
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
var moving_element;
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
	}
	else {
		moving_element = item;
		is_moving_element = true;
	}
}

function delete_move(event) {
	if(is_moving_element) {
		is_moving_element = false;
		moving_element.replaceWith(template_item.clone(true));
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

	text = editing_row.find("input").val();
	editing_hidden_row.find(".audiowall-wall-name").text(text);
	editing_row.hide();
	editing_hidden_row.show();

}

$(document).ready(function(){
	template_item = $(".audiowall-item-template").clone(true);
	template_item.removeClass("audiowall-item-template");

	editing_row = $(".edit-row");
});

function reset_binds() {
	$(".audiowall-move").unbind("click");
	$(".audiowall-move").click(move);
}

function reset_edit_binds() {
	$(".audiowall-edit-cancel").unbind("click");
	$(".audiowall-edit-save").unbind("click");

	$(".audiowall-edit-cancel").click(cancel_edit);
	$(".audiowall-edit-save").click(save_edit);
}

$(".audiowall-move-up").click(move_up);
$(".audiowall-move-down").click(move_down);
$(".audiowall-remove").click(remove);
$(".audiowall-remove").mouseleave(remove_reset);
$(".audiowall-edit").click(start_edit);

$(".audiowall-trash").click(delete_move);

reset_binds();
reset_edit_binds();