<!DOCTYPE html>
<html>
<head>
	<link rel="shortcut icon" href="<?= base_url() ?>assets/logo/logo.ico" />
    <meta charset="utf-8">
    <base href="<?= site_url() ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <meta http-equiv="refresh" content="10;url=<?php echo base_url("logout"); ?>" /> -->
    <!-- <title><?= $page_title ?> - <?= $Settings->site_name ?></title> -->
    <title><?= $page_title ?></title>
    <!-- <link rel="shortcut icon" href="<?= $assets ?>images/ACT_payroll.ico"/> -->

    <!-- Basic -->
	<meta charset="UTF-8">

	<!-- <script src="assets/vendor/bootstrap/js/bootstrap.js"></script>
	<script src="<?= $assets ?>js/framework/bootstrap.min.js" type="text/javascript"></script> -->

	<!-- Vendor CSS -->
	<script src="<?= base_url() ?>node_modules/jquery/dist/jquery.min.js"></script>

	<link rel="stylesheet" href="<?=base_url()?>node_modules/bootstrap/dist/css/bootstrap.css" />
	<script src="<?=base_url()?>node_modules/bootstrap/dist/js/bootstrap.js"></script>

	<!-- <title>Acumen Cognitive Technology Pte Ltd</title> -->
	<meta name="keywords" content="Acumen Cognitive Technology Pte Ltd" />
	<meta name="description" content="Corporate Secretary System">
	
	<!-- Theme CSS -->
	<link rel="stylesheet" href="<?=base_url()?>application/css/theme.css" />

	<!-- Skin CSS -->
	<!-- <link rel="stylesheet" href="assets/stylesheets/skins/default.css" /> -->

	<!-- Theme Custom CSS -->
	<link rel="stylesheet" href="<?=base_url()?>application/css/theme-custom.css">

	<!-- Head Libs -->
	<!-- <script src="assets/vendor/modernizr/modernizr.js"></script> -->
</head>
<body>
	<section class="body">
		<div>
			<section role="main" class="content-body" style="margin-left:0;">
				<header class="page-header" style="background-color:#9349BD;">
					<h2>Interview</h2>
				</header>

				<div style="margin-top: 30px;">
				    <!-- <?php echo $breadcrumbs;?> -->
				</div>

				<div class="box" style="margin-bottom: 30px;">
                    <div class="box-content">
                    	<?php echo validation_errors(); ?>
                    	<label style="color:red;"><?php echo $errorMsg ?></label>

                       <!--  <form class="form-inline" action="applicant/submit_interview_no" method="post">
                    		<div class="form-group mb-2" style="margin: 1%">
					  			<label>Enter Interview No: </label>
						  	</div>
						  	<div class="form-group mx-sm-3 mb-2">
						    	<input type="text" class="form-control" name="interview_no" placeholder="Interview No.">
						  	</div>
						  	
						  	<div class="form-group">
						  		<button type="submit" class="btn btn_purple mb-2">Submit</button>
						  	</div>
						</form> -->

						<form action="applicant/submit_interview_no" style="margin-right: 30px;" method="post">
							<div class="form-group form-inline">
								<label style="margin-right: 15px;">Enter Interview No: </label>
								<input type="text" class="form-control" name="interview_no" placeholder="Interview No.">
							</div>
							<div class="form-group">
								<button type="submit" class="btn btn_purple mb-2 pull-right">Submit</button>
							</div>
						</form>
                    </div>
                </div>
			</section>
		</div>
	</section>
</body>