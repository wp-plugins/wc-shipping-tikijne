 
(function($){
	init_control = function(){
 			 $('#shipping_city').attr('class','state_select'); 
                         $('#shipping_address_2').attr('class','state_select');
                         $('#billing_city').attr('class','state_select');
                         $('#billing_address_2').attr('class','state_select');
                         $('#shipping_city').trigger('change');
                         $('#billing_city').trigger('change');
	}
})(jQuery);
