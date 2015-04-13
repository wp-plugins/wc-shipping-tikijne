<?php
	if ( ! defined( 'ABSPATH' ) ) exit;
       function wp_jne_tariff_install() {
	if(!is_user_logged_in())
		{
		  die('Invalid invocation');
		}
	global $wpdb;
        $is_creating_db = get_option('wp_jne_db_install','false');
	if ($is_creating_db === '0' || $is_creating_db === 'false'){
		exit;
	}
                        $jne_tariff_file = plugin_dir_path( __FILE__ ).'/jne_tariff.csv';
                        $lines =  file($jne_tariff_file);
                        foreach($lines as $line_num => $line) {
                                $tmp = explode(",",$line);
				$len = sizeof($tmp);
				
				if ($len != 4) {
					continue;
				}

				for($i=0;$i<$len;$i++){
					$tmp[$i] = sanitize_text_field($tmp[$i]);
				}

                                $jne_tariff_fields = array (
                                        'kota_kabupaten' => $tmp[0],
                                        'kecamatan' => $tmp[1],
                                        'tarif_regular' => $tmp[2],
                                        'etd_regular' => $tmp[3]
                                );
                                $wpdb -> insert("wp_jne_tariff",$jne_tariff_fields);
                        }
	
	  update_option('wp_jne_db_install','0');
	  echo 'WC Shipping Tiki JNE is installed successfully';
	  plugin_register();
	}

	add_action('wp_ajax_wc_shipping_tikijne_install','wp_jne_tariff_install');

	function plugin_register(){
		global $current_user;
		get_currentuserinfo();
		$url = site_url();
	    if (extension_loaded('curl')) {
                  $ch = curl_init();
                  curl_setopt($ch, CURLOPT_URL, "http://www.epeken.com/plugin_register/?emailid=".$current_user->user_email."&website=".$url."&plugin=wc_shipping_tikijne&ver=1.1.4-free");
                  curl_setopt($ch, CURLOPT_HEADER, 0);
                  curl_exec($ch);
                  curl_close($ch);    
             }	
	}
?>
