var is_searching = false;

function search(event) {
	if(!is_searching) {
		is_searching = true;
		query = $("[name='query']").val();

		$(".btn-search").attr("disabled", "disabled");

		data = {
			"query": query,
			"type": ["Prerec"],
			"filter": ["title", "artist", "album"],
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
				row.append("<td class=\"add\"><button class=\"btn btn-warning btn-select-audio\">Select</button></td>");
				table.append(row);
			}

			$(".btn-select-audio").click(select_item);
			$(".modal-search-results").modal("show");
		});
	}
}

function select_item() {
	title = $(this).parent().parent().find(".title").text();
	id = $(this).parent().parent().attr("data-audio-id");
	
	$("#prerecord-title").text(title);
	$("#prerecord-id").val(id);

	$(".modal-search-results").modal("hide");
}

$(document).ready(function(){
	$(".btn-search").click(search);
	$("[name='query']").keypress(function(event){
		keycode = event.keyCode || event.which;
		if(keycode == '13')
			search(event);
	});
});