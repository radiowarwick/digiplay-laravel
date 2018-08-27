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

$(document).ready(function(){
	loc = location.href;
	if(loc.substr(loc.length - 1) != '/')
		loc = loc + '/';

	$(".showplan-move-up").click(move_up);
	$(".showplan-move-down").click(move_down);
	$(".showplan-remove").click(remove_item);
});