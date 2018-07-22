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
			console.log(title);

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