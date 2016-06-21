jQuery(function(){
	jQuery('.button-notify').click(function(){
		botao = jQuery(this);
		id = jQuery(this).data('id');
		redirect = jQuery(this).data('redirect');

		jQuery.ajax({
			url: getBaseUrl() + 'admin-ajax.php',
			type: 'POST',
			data : {
				action : 'bs_events_send_notification',
				id : id
			},
			beforeSend: function(){
				jQuery(botao).attr('disabled', true);
			},
			success: function(response){
				jQuery(botao).addClass('button-primary');
			},
			error: function(response){
				console.log(response);
			},
			complete: function(){
				jQuery(botao).attr('disabled', false);	
			}
		})
	})
});

function getBaseUrl() {
    return window.location.href.match(/^.*\//);
}