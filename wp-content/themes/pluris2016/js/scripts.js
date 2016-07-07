jQuery(function(){
	jQuery('.article_sent').click(function(){
		if (jQuery(this).val() == 1) {
			jQuery('#articles_select').css('visibility','inherit');
		} else {
			jQuery('#articles_select').css('visibility','hidden');
		}
	})
});