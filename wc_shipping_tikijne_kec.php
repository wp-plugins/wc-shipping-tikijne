<?php
  if ( ! defined( 'ABSPATH' ) ) exit;

  include_once('includes/query.php');
  function get_list_kecamatan() {
	$kotakab = sanitize_text_field($_GET['kota']);
	$nextnonce = sanitize_text_field($_GET['nextNonce']);
	
	if(!wp_verify_nonce($nextnonce,'myajax-next-nonce')){
			die('Invalid Invocation');
		}
	$li_kecamatan = array();
	if(!empty($kotakab))
	{
		$li_kecamatan = get_list_of_kecamatan($kotakab);		
	}

	foreach($li_kecamatan as $value){
		echo trim($value).';';
	} 
    }
   add_action('wp_ajax_get_list_kecamatan','get_list_kecamatan');
   add_action('wp_ajax_nopriv_get_list_kecamatan','get_list_kecamatan');
?>
