jQuery(document).ready(function($) {
	try{
		$('#advSilo').click(function(){
				$('#advanced-silo').toggle();
		});
	}catch(err){}
	try{
		$("#advReset").click(function(){
				$("#wpu-silo-title").val('Article_headline');
				$("#wpu-silo-slug").val('primary_keyword_pagename');
				$("#wpu-article-title").val('Article_headline');
				$("#wpu-article-slug").val('primary_keyword_pagename');
				$("#wpu-altail-title").val('Article_headline');
				$("#wpu-altail-slug").val('primary_keyword_pagename');
		});
	}catch(err){}
try{
	$("#wpu-reset-styles").live("click", function() {
		$('.color-picker').miniColors('value','#000000');
		$("#wpus-link-size").val('13');
		$('select#wpus-link-size-type option:first').attr('selected',true);
		$("#wpus-line-height").val('1.5');
		 return false;
	});
		
function init() {
		// Enabling miniColors
					$('.color-picker').miniColors({
						change: function(hex, rgb) {
							$('#console').prepend('change: ' + hex + ', rgb(' + rgb.r + ', ' + rgb.g + ', ' + rgb.b + ')<br>');
						},
						open: function(hex, rgb) {
							$('#console').prepend('open: ' + hex + ', rgb(' + rgb.r + ', ' + rgb.g + ', ' + rgb.b + ')<br>');
						},
						close: function(hex, rgb) {
							$('#console').prepend('close: ' + hex + ', rgb(' + rgb.r + ', ' + rgb.g + ', ' + rgb.b + ')<br>');
						}
					});
				}				
				
	init();
} catch(err){}
});