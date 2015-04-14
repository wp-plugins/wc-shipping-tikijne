(function($){
  plugin_register = function(){
                                                        $.get(PT_Ajax.ajaxurl, 
                                                        {
                                                                action: 'wc_shipping_tikijne_plugin_register',
                                                        }, 
                                                        function(data,status){
                                                            //do nothing
                                                        });
                                               }
})(jQuery);
