<?php
  get_header();
  $_noresi = isset($_GET["noresi"]) ? $_GET['noresi'] : '';
?>

 <div class="clearfix"> </div>
 <div style="position: relative; float: left; width: 100%;height: auto; margin-top: 10px;margin-bottom: 10px;">
  <div style="margin: 0 auto;width: 61%; border: 1">
   <div id="form_div">
   <h3>Cek Resi JNE</h3>
   <form method="GET" action="http://www.cekresi.com" target="_blank" id="cekresiform" name="cekresiform">
     <div style="margin: 2px;">
      <label for="noresi" style="width: 20%">Nomor Resi:</label>
      <input type="text"  name="noresi" style="width: 40%;border: 1px solid #286090" id="noresi" value="<?php echo $_noresi; ?>"/>
      <input type="hidden" name="v" value="w1"/>
	<button type="submit" class="btn btn-primary" style="width: 20%;" id="cekbutton">Cek Resi</button>      
     </div>
	<div><em>Powered by cekresi.com</em></div>
   </form>
    </div>
    <div id="result_div" style="display: none;">
    </div>
  </div>
 </div>
<?php
  get_footer();
?>
