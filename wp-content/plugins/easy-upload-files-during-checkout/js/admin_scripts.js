// JavaScript Document

jQuery(document).ready(function($){
	$('#easy_ufdc_req').change(function(){
		if($(this).is(':checked')){
			$(this).val(1);
		}else{
			$(this).val(0);
		}
	});
});