<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <base href="<?= site_url() ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <meta http-equiv="refresh" content="10;url=<?php echo base_url("logout"); ?>" /> -->
    <title><?= $page_title ?> - <?= $Settings->site_name ?></title>
    <link rel="shortcut icon" href="<?= $assets ?>images/ACT_payroll.ico"/>

    <!-- Basic -->
	<meta charset="UTF-8">

	<!-- <script src="assets/vendor/bootstrap/js/bootstrap.js"></script>
	<script src="<?= $assets ?>js/framework/bootstrap.min.js" type="text/javascript"></script> -->

	<!-- Vendor CSS -->
	<link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.css" />

	<!-- <title>Acumen Cognitive Technology Pte Ltd</title> -->
	<meta name="keywords" content="Acumen Cognitive Technology Pte Ltd" />
	<meta name="description" content="Corporate Secretary System">
	
	<!-- Theme CSS -->
	<link rel="stylesheet" href="assets/stylesheets/theme.css" />

	<!-- Skin CSS -->
	<link rel="stylesheet" href="assets/stylesheets/skins/default.css" />

	<!-- Theme Custom CSS -->
	<link rel="stylesheet" href="assets/stylesheets/theme-custom.css">

	<!-- Head Libs -->
	<script src="assets/vendor/modernizr/modernizr.js"></script>
</head>
<body>
	<section class="body">
		<div>
			<section role="main" class="content-body" style="margin-left:0;">
				<header class="page-header" style="background-color:#9349BD;">
					<h2>Interview</h2>
				</header>

				<div style="margin-top: 30px;">
				    <?php echo $breadcrumbs;?>
				</div>

				<div class="box" style="margin-bottom: 30px;">
                    <div class="box-content">
                        <label>Successfully submitted. We are happy to see you soon.</label>
                    </div>
                </div>
			</section>
		</div>
	</section>
</body>