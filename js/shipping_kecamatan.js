 
(function($){
	shipping_kecamatan = function(){
			 $('#shipping_city').on('change',function(){
						$('#div_epeken_popup').css('display','block');
                                                $.get(PT_Ajax_Ship_Kec.ajaxurl, 
                                                                {
                                                                        action: 'get_list_kecamatan',
                                                                        nextNonce: PT_Ajax_Ship_Kec.nextNonce,
                                                                        kota: this.value        
                                                                },
                                                                function(data,status){
                                                                $('#shipping_address_2').empty();
                                                                        var arr = data.split(';');
                                                                           //$('#shipping_address_2').append('<option value="">Please Select Kecamatan</option>'); 
                                                                        $.each(arr, function (i,valu) {
                                                                         if (valu != '' && valu != '0') {               
                                                                           $('#shipping_address_2').append('<option value="'+valu+'">'+valu+'</option>');       
                                                                         }
                                                                        });
                                                                $('#shipping_address_2').trigger('chosen:updated');
                                                });
						$('#div_epeken_popup').css('display','none');
						$("#shipping_state option[value='']").attr("selected","selected");
                                                $("#shipping_state").attr("class","");
                                                $("#shipping_address_2").attr("class","");
                                        });
	}
})(jQuery);
