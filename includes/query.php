<?php
  if ( ! defined( 'ABSPATH' ) ) exit;
  function get_list_of_kota_kabupaten ()
	{
		global $wpdb;
		$query="SELECT distinct kota_kabupaten FROM wp_jne_tariff";
		$kotakab = $wpdb -> get_results($query);
		$kotakab = json_decode(json_encode($kotakab), true);
		$kotakabreturn = array();
		$kotakabreturn [''] = 'Please Select Kota/Kabupaten';
		foreach( $kotakab as $value ){
			$kotakabreturn[$value['kota_kabupaten']] = $value['kota_kabupaten'];
		}
		return $kotakabreturn;
	}
  
  function get_list_of_kecamatan ($kotakab)
	{
		$kotakab = sanitize_text_field(trim($kotakab));
		global $wpdb;
		if ($kotakab === ''){
		  return null;
		}
		if ($kotakab === 'init'){
		  $kecamatanreturn [''] = 'Please Select Kecamatan';
		  return $kecamatanreturn;
		}
		$query = "SELECT kecamatan from wp_jne_tariff where kota_kabupaten = %s";
		$kecamatan = $wpdb -> get_results($wpdb->prepare($query,array($kotakab)));
		$kecamatan = json_decode(json_encode($kecamatan),true);
		$kecamatanreturn = array();
		foreach($kecamatan as $value){
			$kecamatanreturn [$value['kecamatan']] = $value['kecamatan'];	
		}
		return $kecamatanreturn;
	}

  function get_tarif($kotakab, $kecamatan, $jne_class) {
		global $wpdb;
		$kolom_selection = '';
		$jne_class = sanitize_text_field($jne_class);
		if ($jne_class === "REGULAR"){
			$kolom_selection = 'tarif_regular';	
		}
		$query = 'select '.$kolom_selection.' from wp_jne_tariff where kota_kabupaten = %s and kecamatan = %s';
	  	$rate = $wpdb -> get_var($wpdb->prepare($query,array($kotakab, $kecamatan)));	
		return $rate;
	}
?>
