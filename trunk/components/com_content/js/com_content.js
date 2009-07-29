$(document).ready(function() {
	if((jQuery.inArray("load_tooltip", _js_defines)>-1)){
		$("a.edit_button").tooltip({ 
			track: true,
			delay: 0,
			showURL: false,
			showBody: " - ",
			extraClass: "pretty",
			fixPNG: true,
			opacity: 0.95
		});
	}
});