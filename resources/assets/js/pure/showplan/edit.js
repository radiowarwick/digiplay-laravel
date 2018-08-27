var loc;

function move_up(event) {
	bottom_row = $(this).closest("tr");
	top_row = bottom_row.prev();

	if(top_row.length > 0)
		swap_items(top_row, bottom_row);
}

function move_down(event) {
	top_row = $(this).closest("tr");
	bottom_row = top_row.next();

	if(bottom_row.length > 0)
		swap_items(top_row, bottom_row);
}

function swap_items(top_item, bottom_item) {
	top_item_id = top_item.attr("data-item-id");
	bottom_item_id = bottom_item.attr("data-item-id");

	$.get(loc + "swap/" + top_item_id + "/" + bottom_item_id, function(data){
		if(data.message == "success")
			top_item.before(bottom_item);
	});
}

function remove_item(event) {
	row = $(this).closest("tr");
	id = row.attr("data-item-id");

	$.get(loc + "remove/" + id, function(data){
		if(data.message == "success")
			row.remove();
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

		data = {
			"query": query,
			"type": ["Music"],
			"filter": filter,
			"censor": false,
			"limit": 50
		};
		$.post("/ajax/search", data, function(data){
			is_searching = false;
			$(".btn-search").removeAttr("disabled");

			table = $(".showplan-search-results").find("tbody");
			table.empty();

			for(i = 0; i < data.length; i++) {
				row = $("<tr data-audio-id=\"" + data[i].id + "\"></tr>");
				if(data[i].censor == "f")
					row.append("<td class=\"icon\"><i class=\"fa fa-music\"></i></td>");
				else
					row.append("<td class=\"text-danger icon\"><i class=\"fa fa-exclamation-circle\"></i></td>");
				row.append("<td class=\"artist text-truncate\">" + data[i].artist + "</td>");
				row.append("<td class=\"title text-truncate\">" + data[i].title + "</td>");
				row.append("<td class=\"album text-truncate\">" + data[i].album + "</td>");
				row.append("<td class=\"length text-truncate\">" + data[i].length_string + "</td>");
				row.append("<td class=\"add\"><button class=\"btn btn-warning btn-add-audio\">Add</button></td>");
				table.append(row);
			}

			$(".btn-add-audio").click(add_item);
			$(".modal-search-results").modal("show");
		});
	}
}

function add_item(event) {
	row = $(this).closest("tr");
	id = row.attr("data-audio-id");
	$(".modal-search-results").modal("hide");

	$.get(loc + "add/" + id, function(data){
		if(data.message == "success") {
			row = $("<tr data-item-id=\"" + data.audio.item + "\"></tr>");
			if(data.audio.censor == "f")
				row.append("<td class=\"icon\"><i class=\"fa fa-music\"></i></td>");
			else
				row.append("<td class=\"text-danger icon\"><i class=\"fa fa-exclamation-circle\"></i></td>");
			row.append("<td>" + data.audio.artist + "</td>");
			row.append("<td>" + data.audio.title + "</td>");
			row.append("<td>" + data.audio.album + "</td>");
			row.append("<td>" + data.audio.length + "</td>");
			row.append("<td class=\"text-warning\"><i class=\"fa fa-lg fa-arrow-circle-up showplan-move-up\"></i> <i class=\"fa fa-lg fa-arrow-circle-down showplan-move-down\"></i></td>");
			row.append("<td><button class=\"btn btn-danger showplan-remove\">Remove</button></td>");
			$(".table-showplan").find("tbody").append(row);
			reset_item_binds();
		}
	});
}

function reset_item_binds() {
	$(".showplan-move-up").unbind("click");
	$(".showplan-move-down").unbind("click");
	$(".showplan-remove").unbind("click");

	$(".showplan-move-up").click(move_up);
	$(".showplan-move-down").click(move_down);
	$(".showplan-remove").click(remove_item);
}

$(document).ready(function(){
	loc = location.href;
	if(loc.substr(loc.length - 1) != '/')
		loc = loc + '/';

	$(".showplan-move-up").click(move_up);
	$(".showplan-move-down").click(move_down);
	$(".showplan-remove").click(remove_item);

	$(".btn-search").click(search);
});