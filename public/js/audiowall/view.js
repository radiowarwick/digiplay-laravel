function reset_wall_binds() {
	$("div.list-group-item[data-wall-page]").click(function(){
		$("div.list-group-item[data-wall-page]").removeClass("active");
		$(this).addClass("active");
		
		page = $(this).attr('data-wall-page');
		$("div.wall-page").hide();
		$("div.wall-page[data-wall-page=" + page + "]").show();
	});
}

reset_wall_binds();