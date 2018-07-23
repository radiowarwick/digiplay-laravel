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

		$("[data-wall-page=" + page + "]").remove();
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
	walls = $("div.list-group-item[data-wall-page]");

	i = 0;
	walls.each(function(){
		page = parseInt($(this).attr("data-wall-page"));
		$("[data-wall-page=" + page + "]").attr("data-wall-page", i++);
	});
}

$(".audiowall-move-up").click(move_up);
$(".audiowall-move-down").click(move_down);
$(".audiowall-remove").click(remove);
$(".audiowall-remove").mouseleave(remove_reset);