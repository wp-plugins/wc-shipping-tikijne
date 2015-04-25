<?php
if ( ! defined( 'ABSPATH' ) ) exit; 
/*
Plugin Name: Epeken JNE Plugin - Free Version
Plugin URI: https://wordpress.org/plugins/wc-shipping-tikijne 
Description: Epeken JNE Plugin for Indonesia Market Place ecommerce shipping method, with Bank Mandiri, BCA and BNI payment method. Free Version. Wanna get volume metrics functionality ? <a href="http://www.epeken.com/shop/woo-commerce-jne-plugin-full-version/" target="_blank">Buy Full Version</a>.
Version: 1.2.2
Author: www.epeken.com
Author URI: http://www.epeken.com
License: GPL2
*/
include_once('wc_shipping_tikijne_load_trf.php');
include_once('wc_shipping_tikijne_kec.php');
if (in_array('woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins'))) || array_key_exists( 'woocommerce/woocommerce.php', maybe_unserialize( get_site_option( 'active_sitewide_plugins') ) )) {

	function wc_shipping_tikijne_init() {
		if(!class_exists('WC_Shipping_Tikijne'))
 		{
    			include_once('class/shipping.php');   
    
 		}
	}
	add_action( 'woocommerce_shipping_init', 'wc_shipping_tikijne_init' );
	function add_tikijne_shipping_method( $methods ) {
			$methods[] = 'WC_Shipping_Tikijne';
			return $methods;
		}
	add_filter( 'woocommerce_shipping_methods', 'add_tikijne_shipping_method' );
	add_action( 'plugins_loaded', 'bank_mandiri_payment_method_init', 0 );
	function bank_mandiri_payment_method_init(){
		if(!class_exists('Mandiri')){
			include_once('class/mandiri_payment_method.php');
		}
	}
	function add_bank_mandiri_payment_method( $methods ) {
          $methods[] = 'Mandiri';
          return $methods;
    	}	
	add_filter( 'woocommerce_payment_gateways', 'add_bank_mandiri_payment_method' );
	add_action( 'plugins_loaded', 'bank_bca_payment_method_init', 0 );
	function bank_bca_payment_method_init(){
		if(!class_exists('BCA')){
			include_once('class/bca_payment_method.php');
		}
	}
	function add_bank_bca_payment_method( $methods ) {
          $methods[] = 'BCA';
          return $methods;
    	}	
	add_filter( 'woocommerce_payment_gateways', 'add_bank_bca_payment_method' );
        add_action( 'plugins_loaded', 'bank_bni_payment_method_init', 0 );
	function bank_bni_payment_method_init(){
		if(!class_exists('BNI')){
			include_once('class/bni_payment_method.php');
		}
	}
	function add_bank_bni_payment_method( $methods ) {
          $methods[] = 'BNI';
          return $methods;
    	}	
	add_filter( 'woocommerce_payment_gateways', 'add_bank_bni_payment_method' );
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

add_action("template_redirect", 'epeken_theme_redirect');

function epeken_theme_redirect(){
  $plugindir = dirname( __FILE__ );
  if (get_the_title() == 'cekresi') {
        $templatefilename = 'cekresi.php';
        $return_template = $plugindir . '/templates/' . $templatefilename;
        do_theme_redirect($return_template);
    }
}

function do_theme_redirect($url) {
    global $post, $wp_query;
    if (have_posts()) {
        include($url);
        die();
    } else {
        $wp_query->is_404 = true;
    }
}

