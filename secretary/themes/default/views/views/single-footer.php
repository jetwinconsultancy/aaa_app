<div class="clearfix"></div>
</div></div></div></div></div>
<div class="clearfix"></div>

<footer>
    <p style="text-align:center;width: 100%;height:20px; background: #171717;background-color: #FFBF00; position:relative; bottom:0; margin-bottom: 0px;">&copy; <?= date('Y') . " " . $Settings->site_name; ?>  <?php if ($_SERVER["REMOTE_ADDR"] == '127.0.0.1') {
            echo ' - Page rendered in <strong>{elapsed_time}</strong> seconds';
        } ?></p>
</footer>
</div>

<div class="modal fade in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
<div class="modal fade in" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true"></div>

<?php unset($Settings->setting_id, $Settings->smtp_user, $Settings->smtp_pass, $Settings->smtp_port, $Settings->update, $Settings->reg_ver, $Settings->allow_reg, $Settings->default_email, $Settings->mmode, $Settings->timezone, $Settings->restrict_calendar, $Settings->restrict_user, $Settings->auto_reg, $Settings->reg_notification, $Settings->protocol, $Settings->mailpath, $Settings->smtp_crypto, $Settings->corn, $Settings->customer_group, $Settings->envato_username, $Settings->purchase_code); ?>
<!-- get business activity list -->
<script type="text/javascript">
	if(localStorage.getItem("business_activity_list") == null)
    {
		$.ajax({
		    type: "GET",
			url: "masterclient/get_business_activity_list",
			dataType: "json",
		    success: function(data){
		    	localStorage.setItem("business_activity_list", JSON.stringify(Object.values(data)));  	
		    }
		});
	}
</script>
<?php
$s2_lang_file = read_file('./assets/config_dumps/s2_lang.js');
foreach (lang('select2_lang') as $s2_key => $s2_line) {
    $s2_data[$s2_key] = str_replace(array('{', '}'), array('"+', '+"'), $s2_line);
}
$s2_file_date = $this->parser->parse_string($s2_lang_file, $s2_data, true);
?>		

<!-- <script type="text/javascript" src="<?= $assets ?>js/bootstrap-notify.min.js"></script> -->
<!-- <script type="text/javascript" src="<?= $assets ?>js/bootstrapValidator.min.js"></script> -->
<!-- <script src="assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script> -->

<script src="assets/vendor/nanoscroller/nanoscroller.js"></script>

<!-- <script src="assets/vendor/magnific-popup/magnific-popup.js"></script>
<script src="assets/vendor/jquery-placeholder/jquery.placeholder.js"></script> -->

<!-- Specific Page Vendor -->
<!-- <script src="assets/vendor/jquery-appear/jquery.appear.js"></script> -->
<!-- <script src="<?= $assets ?>js/bootstrap-multiselect/bootstrap-multiselect.js"></script> -->
<!-- <script src="assets/vendor/jquery-easypiechart/jquery.easypiechart.js"></script> -->
<!-- <script src="assets/vendor/flot/jquery.flot.js"></script> -->
<!-- <script src="assets/vendor/flot-tooltip/jquery.flot.tooltip.js"></script> -->
<!-- <script src="assets/vendor/flot/jquery.flot.pie.js"></script> -->
<!-- <script src="assets/vendor/flot/jquery.flot.categories.js"></script> -->
<!-- <script src="assets/vendor/flot/jquery.flot.resize.js"></script> -->
<!-- <script src="assets/vendor/jquery-sparkline/jquery.sparkline.js"></script> -->
<!-- <script src="assets/vendor/raphael/raphael.js"></script> -->
<!-- <script src="assets/vendor/morris/morris.js"></script> -->
<!-- <script src="assets/vendor/gauge/gauge.js"></script> -->
<!-- <script src="assets/vendor/chart/Chart.min.js"></script> -->
<!-- <script src="assets/vendor/snap-svg/snap.svg.js"></script> -->
<!-- <script src="assets/vendor/liquid-meter/liquid.meter.js"></script> -->

<!-- <script src="assets/vendor/bootstrap-wizard/jquery.bootstrap.wizard.js"></script> -->
<!-- <script src="assets/vendor/pnotify/pnotify.custom.js"></script> -->

<!-- <script src="assets/vendor/jqvmap/jquery.vmap.js"></script>
<script src="assets/vendor/jqvmap/data/jquery.vmap.sampledata.js"></script>
<script src="assets/vendor/jqvmap/maps/jquery.vmap.world.js"></script>
<script src="assets/vendor/jqvmap/maps/continents/jquery.vmap.africa.js"></script>
<script src="assets/vendor/jqvmap/maps/continents/jquery.vmap.asia.js"></script>
<script src="assets/vendor/jqvmap/maps/continents/jquery.vmap.australia.js"></script>
<script src="assets/vendor/jqvmap/maps/continents/jquery.vmap.europe.js"></script>
<script src="assets/vendor/jqvmap/maps/continents/jquery.vmap.north-america.js"></script>
<script src="assets/vendor/jqvmap/maps/continents/jquery.vmap.south-america.js"></script> -->

<!-- <script src="assets/vendor/fullcalendar/fullcalendar.js"></script>
<script src="assets/vendor/bootstrap-maxlength/bootstrap-maxlength.js"></script> -->

<!-- <link rel="stylesheet" href="assets/vendor/bootstrap-tagsinput/bootstrap-tagsinput.css" /> -->

<!-- <script src="assets/vendor/bootstrap-tagsinput/bootstrap-tagsinput.js"></script> -->
<!-- Theme Base, Components and Settings -->
<script src="assets/javascripts/theme.js"></script>

<!-- Theme Custom -->
<script src="assets/javascripts/theme.custom.js"></script>

<!-- Theme Initialization Files -->
<script src="assets/javascripts/theme.init.js"></script>
<!-- <script src="assets/javascripts/ui-elements/examples.modals.js"></script> -->


<!-- Examples -->
<!-- <script src="assets/javascripts/forms/examples.wizard.js"></script> -->
<!-- <script src="assets/javascripts/forms/examples.client.js?v=44444wqec8dewewew9wewewedfa2082"></script> -->
<link href="<?= $assets ?>styles/blue.css" rel="stylesheet"/>
<script type="text/javascript">
	//-------------------------------Artemis KYC API------------------------------
    var user_pool_id = CryptoJS.AES.decrypt(<?php echo json_encode($artemis_user_pool_id);?>, "Chanel", {format: CryptoJSAesJson}).toString(CryptoJS.enc.Utf8).replace(/['"]+/g, '');
    var client_id = CryptoJS.AES.decrypt(<?php echo json_encode($artemis_client_id);?>, "Chanel", {format: CryptoJSAesJson}).toString(CryptoJS.enc.Utf8).replace(/['"]+/g, '');
    var REGION = CryptoJS.AES.decrypt(<?php echo json_encode($artemis_region);?>, "Chanel", {format: CryptoJSAesJson}).toString(CryptoJS.enc.Utf8).replace(/['"]+/g, '');
    var username = CryptoJS.AES.decrypt(<?php echo json_encode($artemis_username);?>, "Chanel", {format: CryptoJSAesJson}).toString(CryptoJS.enc.Utf8).replace(/['"]+/g, '');
    var password = CryptoJS.AES.decrypt(<?php echo json_encode($artemis_password);?>, "Chanel", {format: CryptoJSAesJson}).toString(CryptoJS.enc.Utf8).replace(/['"]+/g, '');
    //--------------------------------------End Artemis KYC API----------------------------------------
</script>
<script type="text/javascript" src="<?= $assets ?>js/artemis-data.js?ver=0001"></script>

<style>
	.number{
		text-align:right;
	}

	.select2-container {
	    display:block;
	}
	.dataTables_length label{
	    width:100%;
	}

</style>
<script type="text/javascript">
	function user_logout() {
		$.ajax({
			type: 'GET',
			url: 'auth/logout',
			cache: 'false',
			success: function(response) {
				localStorage.clear();
				location.reload();
			},
			error: function() {

			}
		});
	}
</script>
</body>
</html>
