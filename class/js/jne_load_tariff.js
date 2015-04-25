(function($){
  install = function(){
                                                        $.get(PT_Ajax.ajaxurl, 
                                                        {
                                                                action: 'wc_shipping_tikijne_install',
                                                        }, 
                                                        function(data,status){
                                                            $('#div_load_trf').html(data);
                                                        });
                                               }
  install();
})(jQuery);
