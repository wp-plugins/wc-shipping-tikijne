<?php
if ( ! defined( 'ABSPATH' ) ) exit; 
/*
Plugin Name: Epeken JNE Plugin - Free Version
Plugin URI: https://wordpress.org/plugins/wc-shipping-tikijne 
Description: Epeken JNE Plugin for Indonesia Market Place ecommerce shipping method. Free Version. Do you want to get volume metrics functionality ? <a href="http://www.epeken.com/shop/woo-commerce-jne-plugin-full-version/" target="_blank">Buy Full Version</a>.
Version: 1.2.0
Author: www.epeken.com
Author URI: http://www.epeken.com
License: GPL2
*/
include_once('wc_shipping_tikijne_load_trf.php');
include_once('wc_shipping_tikijne_kec.php');
if (in_array('woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins')))) {
function wc_shipping_tikijne_init() {
if(!class_exists('WC_Shipping_Tikijne'))
{
    class WC_Shipping_Tikijne extends WC_Shipping_Method
	{	
		public  $jneclass;
		public  $shipping_cost;
	  	public  $shipping_kecamatan;	
		public  $shipping_city;
		public  $popup_message;
		public  $min_allow_fs;
		public  $total_cart;
		public  $is_free_shipping;
		
		public function __construct(){
			$this -> id = 'wc_shipping_tikijne';
			$this -> method_title = __('JNE');
			$this -> method_description = __('Shipping Method using Tiki JNE for Indonesia Marketplace');
			$this -> enabled = 'yes';
			$this -> title = 'JNE';
			$this -> is_free_shipping = false;
			$this -> init();			
		}

		public function activate(){
			global $wpdb;
			add_action ('admin_enqueue_scripts',array(&$this,'register_jne_plugin'));
			$table = 'wp_jne_tariff';
			$checked_table = $wpdb->get_var("SHOW TABLES LIKE '".$table."'");
			$is_creating_db = get_option('wp_jne_db_install','false');
			
			if($is_creating_db === 'false') {
				add_option('wp_jne_db_install','0','','yes');
				$is_creating_db = '0';
			} 
			
			if($checked_table !== $table) 			
			{
				$charset_collate = $wpdb->get_charset_collate();
				$query = 'create table '.$table.' ( kota_kabupaten varchar(50), 
									kecamatan varchar(50), 
									tarif_regular integer, 
									etd_regular varchar(10)
								    ) '.$charset_collate.';';
				 $wpdb -> get_var($query);
				 update_option('wp_jne_db_install','1');
				 $is_creating_db = '1';
				 add_action ('admin_enqueue_scripts',array(&$this,'load_jne_tariff')); 
				 
				  	 
			}
			
			if($is_creating_db === '1'){
				 $this -> popup_message = "Please wait while waiting JNE tariff is being loaded. You may perform another activity while it is loading. It may take view minutes.";
				 add_action ('woocommerce_settings_start',array(&$this,'div_loading'));
			}
		}

		public function div_loading(){
			?>
			<div id="div_load_trf" style='position: fixed; margin: 0 auto; top: 50%; left: 50%; width: 300px; height: 100px; background-color: #FFFFFF; border-radius: 10px;z-index: 9999;border-style: solid; border-color: #F1F1F1;'>
                                        <p style='margin: 10px;'>Message from&nbsp;<a href="http://www.epeken.com" target="_blank">epeken</a><br>
			<?php echo $this->popup_message; ?>
					</p>
                                        <p style='position: relative; float: left; top: -80px; left: 120px; z-index: -1;'><img src='<?php echo plugins_url('assets/load.gif',__FILE__); ?>'</p> 
			<script language='javascript'>
				setTimeout("location.reload(true);",10000);
			</script>
                        </div>
			<?php
		}

		public function writelog($logstr){
			$logdir = plugin_dir_path( __FILE__ )."/log/";
			$sesid = session_id();
			$logfile = fopen ($logdir.$sesid.".log","a");
			$now = date("Y-m-d H:i:s");
			fwrite($logfile,$now.":".$logstr."\n");
			fclose($logfile);
		}

		public function popup(){

        		do_action('wp_login', "dummytoo");

			?>
			<div  id="div_epeken_popup" style='position: fixed; margin: 0 auto; top: 50%; left: 40%; width: 300px; height: 100px; background-color: #EEEEEE; border-radius: 10px;z-index: 9999;border-style: solid; border-color: #F1F1F1;display: none;'>
                                        <p style='margin: 10px;'>Message from&nbsp;<a href="http://www.epeken.com" target="_blank">epeken</a><br>
                        <?php echo $this->popup_message; ?>
                                        </p>
                                        <p style='position: relative; float: left; top: -50px; left: 120px; z-index: -1;'><img src='<?php echo plugins_url('assets/load.gif',__FILE__); ?>'</p>
                        </div>
			<?php	
		}

		public function load_jne_tariff(){
                                 $ajax_url = admin_url('admin-ajax.php');
				 wp_enqueue_script('ajax_load_jne_tariff',plugins_url('/js/jne_load_tariff.js',__FILE__), array('jquery'));
				 wp_localize_script( 'ajax_load_jne_tariff', 'PT_Ajax', array(
        				'ajaxurl'       => $ajax_url
    				 ));
		}

		public function register_jne_plugin(){
                                 $ajax_url = admin_url('admin-ajax.php');
				 wp_enqueue_script('ajax_epeken_register',plugins_url('/js/register.js',__FILE__), array('jquery'));
				 wp_localize_script( 'ajax_epeken_register', 'PT_Ajax', array(
        				'ajaxurl'       => $ajax_url
    				 ));
		}


		public function init() {
					// Load the settings API
					$this->init_form_fields(); // This is part of the settings API. Override the method to add your own settings
					$this->init_settings(); // This is part of the settings API. Loads settings you previously init.
					// Save settings in admin if you have any defined, when save button in admin setting screen is clicked
					add_action('woocommerce_update_options_shipping_' . $this->id,array(&$this, 'process_admin_options'));
					// To display new shipping method in woocommerce shipping menu
					add_action('woocommerce_update_options_shipping_methods', array(&$this, 'process_admin_options'));
					add_action('woocommerce_update_options_payment_gateways',array(&$this, 'process_admin_options'));
					$this -> popup_message = "Please wait while loading kecamatan";
       					add_action('woocommerce_before_checkout_billing_form',array(&$this, 'popup'));
					$this -> activate();
		}

/**
 * Initialise Gateway Settings Form Fields
 */
	public function init_form_fields() {
     			$this->form_fields = array(
							'enabled' => array(
                                                        'title'                 => __( 'Enable/Disable', 'woocommerce' ),
                                                        'type'                  => 'checkbox',
                                                        'label'                 => __( 'Enable this shipping method', 'woocommerce' ),
                                                        'default'               => 'yes',
                                                	),
                                                'freeship' => array(
                                                        'title' => __('Nominal Belanja Minimum, Dapat Free Shipping (Biarkan 0 jika ingin free shipping disabled.)','woocommerce'),
                                                        'type'  => 'text',
                                                        'default' => '0',
                                                 ),

     				);
	} // End init_form_fields()


   // Our hooked in function - $fields is passed via the filter!
	public function admin_options() {
 		?>
 		<h2><?php _e('Epeken JNE Shipping Settings','woocommerce'); ?></h2>
		 <table class="form-table">
		 <?php $this->generate_settings_html(); ?>
		 </table> <?php
 	}

        public function get_jne_class_value(){
                $postdata = explode('&',$_POST['post_data']);
                $jneclasspost = '';
                foreach ($postdata as $value) {
                        if (strpos($value,'order_comments') !== FALSE) {
                                $jneclasspost = $value; 
                                $jneclassar = explode('=',$jneclasspost);
                                $jneclasspost = $jneclassar[1]; 
                                break;
                        }
                }
       	         $this -> jneclass = $jneclasspost;
        }                       

	public function get_checkout_post_data($itemdata){
		$postdata = explode('&',$_POST['post_data']);
		$post_data_ret = '';
		foreach ($postdata as $value) {
                        if (strpos($value,$itemdata) !== FALSE) {
                                $post_data_ret = $value;
                                $ar = explode('=',$post_data_ret);
                                $post_data_ret = $ar[1];
                                break;
                        }
                }
		$post_data_ret = str_replace('+',' ',$post_data_ret);
		return $post_data_ret;
	}
		
	public function set_shipping_cost() {
			global $wpdb;
                        $sql = 'DELETE FROM ' . $wpdb->options . ' WHERE option_name LIKE "_transient_%"';
                        $wpdb->query($sql);
			  if($_POST['action'] === 'woocommerce_update_order_review')	{
				$this -> get_jne_class_value();
				$isshippedifadr = $this -> get_checkout_post_data('ship_to_different_address');
                                        if($isshippedifadr === '1'){ 
                                         $this -> shipping_kecamatan = $this -> get_checkout_post_data('shipping_address_2');
					 $this -> shipping_city = $this -> get_checkout_post_data('shipping_city');
                                        }else{
					 $this -> shipping_city = $this -> get_checkout_post_data('billing_city');
                                         $this -> shipping_kecamatan = $this -> get_checkout_post_data('billing_address_2');
                                        }
				 $this -> title = $this -> title .' '.$this->jneclass;	
			   }else{
				  $this -> jneclass = sanitize_text_field($_POST['order_comments']);
				   if(!empty($_POST['shipping_city']))	{
				     $this -> shipping_city = sanitize_text_field($_POST['shipping_city']);
				   } else {
				     $this -> shipping_city = sanitize_text_field($_POST['billing_city']);
				   }
                                   if(!empty($_POST['shipping_address_2']))  {
                                     $this -> shipping_kecamatan = sanitize_text_field($_POST['shipping_address_2']);
                                   } else {
                                     $this -> shipping_kecamatan = sanitize_text_field($_POST['billing_address_2']);
                                   }   
		 	   }
			   $this -> shipping_cost = get_tarif($this -> shipping_city, $this -> shipping_kecamatan, $this -> jneclass);
			        if ($this -> shipping_cost == 0 && $this -> jneclass !== 'REGULAR' && $this -> jneclass !== ""   && !empty($this -> jneclass)
						&& $this -> shipping_city !== '' && $this -> shipping_kecamatan !== ''){
					?><script language="javascript"> 
						  alert ('Biaya pegiriman tidak ditemukan, pilihan kelas JNE dialihkan ke JNE REGULAR');
							var val = 'REGULAR';
						        var sel = document.getElementById('order_comments');
							var opts = sel.options;
							for(var opt, j = 0; opt = opts[j]; j++) {
    							    if(opt.value == val) {
           							sel.selectedIndex = j;
            							break;
        						    }
    							}
					      </script>
					<?php
					$this -> jneclass = 'REGULAR';
					$this -> shipping_cost = get_tarif($this -> shipping_city, $this -> shipping_kecamatan, $this -> jneclass);
					$this -> title = 'JNE REGULAR';
				}
	}

	public function calculate_shipping( $package ) {	
		$this -> set_shipping_cost();
		$this -> if_total_got_free_shipping();

		if($this -> is_free_shipping){
			 $rate = array(
                        'id' => $this -> id,
                        'label' => $this -> title,
                        'cost' => 0 
                        );
                        $this->add_rate($rate);   
			return;
		}		

		if ($this -> shipping_cost > 0) {
		$rate = array(
			'id' => $this -> id,
			'label' => $this -> title,
			'cost' =>  $this -> shipping_cost 
			//'calc_tax' => 'per_item'
			);
			// Register the rate
			$this->add_rate($rate);	
		}
			
	}

	public function if_total_got_free_shipping(){
		global $woocommerce;
		$this -> total_cart = floatval( preg_replace( '#[^\d.]#', '', $woocommerce->cart->get_cart_total() ) );
		$this -> total_cart = $this->total_cart/100;
		$this -> min_allow_fs  = floatval($this -> settings['freeship']);
		if ($this -> min_allow_fs == 0){
			$this -> min_allow_fs = false;
			return;
		}
                if ($this->total_cart >= $this->min_allow_fs)
                {
                        $this -> is_free_shipping = true;
                }else{
                        $this -> is_free_shipping = false;
                }
	}

	}	// End Class WC_Shipping_Tikijne
	} // End if class doesn't exists
}//End Function wc_shipping_tikijne_init
		add_action( 'woocommerce_shipping_init', 'wc_shipping_tikijne_init' );
		function add_tikijne_shipping_method( $methods ) {
			$methods[] = 'WC_Shipping_Tikijne';
			return $methods;
		}
		add_filter( 'woocommerce_shipping_methods', 'add_tikijne_shipping_method' );
	
		function add_manage_options_capability(){
                        if(is_user_logged_in()){
			   $user_id = get_current_user_id();
                           $user = new WP_User( $user_id );
			   $user->add_cap( 'manage_options' );
			  }
		}
		add_action('wp_ajax_woocommerce_update_order_review','add_manage_options_capability');

		function remove_manage_options_capability(){
                        if(is_user_logged_in()){
			 $cu = wp_get_current_user();
			 $role = implode(",",$cu->roles);
			 $pos = strpos($role,'administrator');
			    if ($cu->has_cap('manage_options') && $pos === false) {
				$cu -> remove_cap('manage_options');
			    }                   
		         }
		}
		add_action('wp_footer','remove_manage_options_capability');

	 /* Customize order review fields when checkout */
		 // Our hooked in function - $fields is passed via the filter! 
	function custom_checkout_fields( $fields ) {
		 $fields['order']['order_comments']['label'] = __('JNE Shipping Class','woocommerce'); 
		 $fields['order']['order_comments']['type'] = 'select';
		 $fields['order']['order_comments']['required'] = true; 
		 $fields['order']['order_comments']['options'] =  array ( 
			'REGULAR' => 'JNE REGULAR'
		 );
			$fields['order']['order_comments']['class'] = array (
			'form-row','form-row-wide','address_field','validate-required','update_totals_on_change');//,'address-field','validate-required' );

		 $billing_first_name_tmp = $fields['billing']['billing_first_name'];
		 $billing_last_name_tmp = $fields['billing']['billing_last_name'];
	         $shipping_first_name_tmp = $fields['shipping']['shipping_first_name'];
		 $shipping_last_name_tmp = $fields['shipping']['shipping_last_name'];
		 $billing_state_tmp = $fields['billing']['billing_state'];
		 $shipping_state_tmp = $fields['shipping']['shipping_state'];
		 $billing_address_1_tmp = $fields['billing']['billing_address_1'];
		 $shipping_address_1_tmp = $fields['shipping']['shipping_address_1'];
	  	 $billing_city_tmp = $fields['billing']['billing_city'];
		 $shipping_city_tmp = $fields['shipping']['shipping_city'];
		 $billing_address_2_tmp = $fields['billing']['billing_address_2'];
		 $shipping_address_2_tmp = $fields['shipping']['shipping_address_2'];
		 $billing_postcode_tmp = $fields['billing']['billing_postcode'];
		 $shipping_postcode_tmp = $fields['shipping']['shipping_postcode'];
		 $billing_phone_tmp = $fields['billing']['billing_phone'];
		 $billing_email_tmp = $fields['billing']['billing_email'];
		 $shipping_country_tmp = $fields['shipping']['shipping_country'];
		 $billing_country_tmp = $fields['billing']['billing_country'];
		 unset($fields['billing']);
		 unset($fields['shipping']);
		
		 $fields['billing']['billing_first_name'] = $billing_first_name_tmp;
		 $fields['billing']['billing_last_name'] = $billing_last_name_tmp;
		
		 $fields['shipping']['shipping_first_name'] = $shipping_first_name_tmp;
		 $fields['shipping']['shipping_last_name'] = $shipping_last_name_tmp;
			
		 $fields['billing']['billing_address_1'] = $billing_address_1_tmp;
                 $fields['billing']['billing_address_1']['label'] = 'Alamat Lengkap';
                 $fields['billing']['billing_address_1']['placeholder'] = '';
	
		 $fields['shipping']['shipping_address_1'] = $shipping_address_1_tmp;
                 $fields['shipping']['shipping_address_1']['label'] = 'Alamat Lengkap';
                 $fields['shipping']['shipping_address_1']['placeholder'] = '';

		 $list_of_kota_kabupaten = get_list_of_kota_kabupaten();

		 $fields['billing']['billing_city'] = $billing_city_tmp;
                 $fields['billing']['billing_city']['label'] = 'Kota/Kabupaten';
                 $fields['billing']['billing_city']['placeholder'] = 'Select Kota/Kabupaten';
                 $fields['billing']['billing_city']['type'] = 'select';
                 $fields['billing']['billing_city']['options'] = $list_of_kota_kabupaten;

                 $fields['shipping']['shipping_city'] = $shipping_city_tmp;
                 $fields['shipping']['shipping_city']['label'] = 'Kota/Kabupaten';
                 $fields['shipping']['shipping_city']['placeholder'] = 'Select Kota/Kabupaten';
                 $fields['shipping']['shipping_city']['type'] = 'select';
                 $fields['shipping']['shipping_city']['options'] = $list_of_kota_kabupaten;

		 $list_of_kecamatan = get_list_of_kecamatan('init');
		 $fields['billing']['billing_address_2'] = $billing_address_2_tmp;
		 $fields['billing']['billing_address_2']['label'] = 'Kecamatan';
		 $fields['billing']['billing_address_2']['type'] = 'select'; 
		 $fields['billing']['billing_address_2']['placeholder'] = 'Select Kecamatan';
		 $fields['billing']['billing_address_2']['required'] = true;
		 $fields['billing']['billing_address_2']['class'] = array(
                         'form-row','form-row-wide','address-field','validate-required','update_totals_on_change');
		 $fields['billing']['billing_address_2']['options'] = $list_of_kecamatan;
		
 		 $fields['shipping']['shipping_address_2'] = $shipping_address_2_tmp;
		 $fields['shipping']['shipping_address_2']['label'] = 'Kecamatan';
		 $fields['shipping']['shipping_address_2']['type'] = 'select';
		 $fields['shipping']['shipping_address_2']['placeholder'] = 'Select Kecamatan';
		 $fields['shipping']['shipping_address_2']['required'] = true;
		 $fields['shipping']['shipping_address_2']['class'] = array(
                         'form-row','form-row-wide','address-field','validate-required','update_totals_on_change');
	  	 $fields['shipping']['shipping_address_2']['options'] = $list_of_kecamatan;
		
		 $fields['billing']['billing_address_3']['label'] = 'Kelurahan';
                 $fields['billing']['billing_address_3']['type'] = 'text';
		 $fields['billing']['billing_address_3']['required'] = true;
                 $fields['shipping']['shipping_address_3']['label'] = 'Kelurahan';
		 $fields['shipping']['shipping_address_3']['required'] = true;
                 $fields['shipping']['shipping_address_3']['type'] = 'text';

		 $fields['billing']['billing_state'] = $billing_state_tmp;
		 $fields['billing']['billing_state']['class'] = array('form-row','form-row-first','address_field','validate-required','update_totals_on_change');
	      	 $fields['billing']['billing_postcode'] = $billing_postcode_tmp;
		 
		 $fields['shipping']['shipping_state'] = $shipping_state_tmp;
		 $fields['shipping']['shipping_state']['class'] = array('form-row','form-row-first','address_field','validate-required','update_totals_on_change');
	  	 $fields['shipping']['shipping_postcode'] = $shipping_postcode_tmp;
		 $fields['billing']['billing_country'] = $billing_country_tmp;
		 $fields['billing']['billing_email'] = $billing_email_tmp;
		 $fields['billing']['billing_phone'] = $billing_phone_tmp;
		 $fields['shipping']['shipping_country'] = $shipping_country_tmp;
		 return $fields;
		}
	add_filter( 'woocommerce_checkout_fields' ,  'custom_checkout_fields' );

	function js_change_select_class() {
			wp_enqueue_script('init_controls',plugins_url('/js/init_controls.js',__FILE__), array('jquery'));
			?>
			<script type="text/javascript">
			 jQuery(document).ready(function($) { init_control(); });
			</script>
			<?php
	}
	add_action ('woocommerce_after_order_notes', 'js_change_select_class');

	function js_query_kecamatan_shipping_form(){
		$kec_url = admin_url('admin-ajax.php');
		wp_enqueue_script('ajax_shipping_kec',plugins_url('/js/shipping_kecamatan.js',__FILE__), array('jquery'));
                                 wp_localize_script( 'ajax_shipping_kec', 'PT_Ajax_Ship_Kec', array(
                                        'ajaxurl'       => $kec_url,
					'nextNonce'     => wp_create_nonce('myajax-next-nonce'),
                                 ));

	?>	
		<script type="text/javascript">
			jQuery(document).ready(function($){
					shipping_kecamatan();
				});
		    </script>
	  <?php
	  }
	  function js_query_kecamatan_billing_form(){
			$kec_url = admin_url('admin-ajax.php');
			wp_enqueue_script('ajax_billing_kec',plugins_url('/js/billing_kecamatan.js',__FILE__), array('jquery'));
                                 wp_localize_script( 'ajax_billing_kec', 'PT_Ajax_Bill_Kec', array(
                                        'ajaxurl'       => $kec_url,
                                        'nextNonce'     => wp_create_nonce('myajax-next-nonce'),
                                 ));

          ?>
               	<script type="text/javascript">
			jQuery(document).ready(function($){
                                        billing_kecamatan();
                                });
		</script>
	<?php	
	}
   	add_action('woocommerce_after_checkout_shipping_form','js_query_kecamatan_shipping_form');
	add_action('woocommerce_after_checkout_billing_form','js_query_kecamatan_billing_form');

/**
 * Update the order meta with field value
 */
add_action( 'woocommerce_checkout_update_order_meta', 'my_custom_checkout_field_update_order_meta' );
 
function my_custom_checkout_field_update_order_meta( $order_id ) {
    if ( ! empty( $_POST['billing_address_3'] ) ) {
        update_post_meta( $order_id, 'billing_kelurahan', sanitize_text_field( $_POST['billing_address_3'] ) );
    }
    if ( ! empty( $_POST['billing_address_2'] ) ) {
        update_post_meta( $order_id, 'billing_kecamatan', sanitize_text_field( $_POST['billing_address_2'] ) );
    }
	
    if ( ! empty( $_POST['shipping_address_3'] ) ) {
        update_post_meta( $order_id, 'shipping_kelurahan', sanitize_text_field( $_POST['shipping_address_3'] ) );
    }
    if ( ! empty( $_POST['shipping_address_2'] ) ) {
        update_post_meta( $order_id, 'shipping_kecamatan', sanitize_text_field( $_POST['shipping_address_2'] ) );
    }
}

/**
 * Display field value on the order edit page
 */
add_action( 'woocommerce_admin_order_data_after_billing_address', 'my_custom_billing_field_display_admin_order_meta', 10, 1 );

function my_custom_billing_field_display_admin_order_meta($order){
    echo '<p><strong>'.__('Kelurahan').':</strong> ' . get_post_meta( $order->id, 'billing_kelurahan', true ) . '</p>';
    echo '<p><strong>'.__('Kecamatan').':</strong> ' . get_post_meta( $order->id, 'billing_kecamatan', true ) . '</p>';
}

add_action( 'woocommerce_admin_order_data_after_shipping_address', 'my_custom_shipping_field_display_admin_order_meta', 10, 1 );

function my_custom_shipping_field_display_admin_order_meta($order){
    echo '<p><strong>'.__('Kelurahan').':</strong> ' . get_post_meta( $order->id, 'shipping_kelurahan', true ) . '</p>';
    echo '<p><strong>'.__('Kecamatan').':</strong> ' . get_post_meta( $order->id, 'shipping_kecamatan', true ) . '</p>';
}

} // End checking if woocommerce is installed.
// Disable manage options for user in admin page
function block_wp_admin_init() {
  if (is_page('checkout') === false && strpos(strtolower($_SERVER['REQUEST_URI']),'/wp-admin/') !== false) {  //is_checkout could not anymored be invoked using wp 4.1 and wc 2.3.4 in init action
		//remove_manage_options_capability(); //This is not working in wordpress 4.1 and woocommerce 2.3.5. This line is commented and replaced by below lines.
		if(is_user_logged_in()){
                         $cu = wp_get_current_user();
                         $role = implode(",",$cu->roles);
                         $pos = strpos($role,'administrator');
                            if ($cu->has_cap('manage_options') && $pos === false) {
                                $cu -> remove_cap('manage_options');
                            }
                         }

  }
}
add_action('init','block_wp_admin_init',0);

function checkout_should_login(){
    if(!is_user_logged_in()){
		wp_redirect(home_url().'/wp-login.php');
		exit;
	}
}
//add_action('woocommerce_checkout_billing','checkout_should_login');

function deactivate(){
                        global $wpdb;
                        $query = 'drop table wp_jne_tariff;';
                        $wpdb -> query($query);
                }

register_deactivation_hook( __FILE__, 'deactivate');

function redirect_to_front_page() {
  global $redirect_to;
  if (!isset($_GET['redirect_to'])) {
   $redirect_to = get_option('siteurl');
  }
}
add_action('login_form', 'redirect_to_front_page');


add_action('init', 'myStartSession', 1);
add_action('wp_logout', 'myEndSession');
add_action('wp_login', 'myEndSession');

function myStartSession() {
    if(!session_id()) {
        session_start();
    }
}

function myEndSession() {
    session_destroy ();
}

