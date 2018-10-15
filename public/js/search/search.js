$(document).ready(function(){
	$("#btn-search-options").tooltip({
		html: true,
		trigger: "click",
		template: $("#search-options-template").html()
	});
});