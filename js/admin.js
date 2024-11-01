jQuery(document).ready(function() {
	jQuery('#ithemes-thememan_preview').click(function(e) {
		window.location.href = jQuery('#ithemes-thememan_themes option:selected').attr("title");
		//alert( jQuery('#ithemes-thememan_themes option:selected').attr("title") );
	});
	jQuery('#ithemes-thememan_activate').click(function(e) {
		window.location.href = jQuery('#ithemes-thememan_themes').val();
	});
});
