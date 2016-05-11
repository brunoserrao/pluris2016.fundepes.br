jQuery(document).ready(function($) {
	
	//console.log(eufdc_obj);
});

function layered_js(){
				
	jQuery('.woocommerce form').attr('enctype','multipart/form-data');
	jQuery('.woocommerce form').attr('encoding', 'multipart/form-data');
	
	if(jQuery('.woocommerce-cart .woocommerce form').find('input[name^="file_during_checkout"]').length>0){
	
		jQuery('.woocommerce-cart .woocommerce form').attr('action', eufdc_obj.checkout_url);
		
		jQuery('.woocommerce-cart .woocommerce form').append('<div class="wc-proceed-to-checkout"><input type="submit" class="checkout-button button alt wc-forward" value="Proceed to Checkout" style="float: right; font-size: 20px; padding: 20px 56px;" /></div>');
		
		jQuery('a.checkout-button').remove();
	}
	
	if(jQuery('.woocommerce form').hasClass('checkout')){
		
		jQuery('<span class="temp_checkout">').insertAfter('.woocommerce form.checkout');
		/*
		var obj = jQuery('.woocommerce form.checkout');
		var preserve = obj.removeClass('checkout');
		obj.remove();
		jQuery('span.temp_checkout').html(obj); */					
		
		//jQuery('.woocommerce form.checkout').removeClass('checkout');
		jQuery( ".woocommerce form.checkout").unbind( "submit" );
	}	
	
}