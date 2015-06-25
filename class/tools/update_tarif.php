<?php

if($_FILES["woocommerce_epeken_jne_tariff"]["error"] != UPLOAD_ERR_NO_FILE)
{
 $upload_path = $_FILES["woocommerce_epeken_jne_tariff"]["tmp_name"];
 $ext = strtolower(end(explode('.', $_FILES['woocommerce_epeken_jne_tariff']['name'])));
 $name = $_FILES['woocommerce_epeken_jne_tariff']['name'];
 $name = trim($name);
 if($name != 'jne_tariff.csv')
  {
        $this -> admin_error('Invalid File Name. File Name MUST BE <strong>jne_tariff.csv</strong>');

 }else{
                        deactivate();
                        if(!copy($upload_path,EPEKEN_JNE_TARIF)){
                         $this -> admin_error('Error copying tarif file. Please check the error message you have in above screen and contact epeken.');
                        }
                        ?>
                        <script language='javascript'>
                        location.href = "<?php echo admin_url('admin.php?page=wc-settings&epupdtarif=1')?>";
                        </script>
                        <?php
 }

 $enable_cekresi = $this -> settings['enable_cekresi_page'];
 if($enable_cekresi === 'yes') {
       $this->create_cek_resi_page();
       $this->add_cek_resi_page_to_prim_menu();
 }else{
       $this -> delete_cek_resi();
 }
}
?>

