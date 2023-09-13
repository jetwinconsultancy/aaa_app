<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <base href="<?= site_url() ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?> - <?= $Settings->site_name ?></title>
    <link rel="shortcut icon" href="<?= $assets ?>images/dot.ico"/>
    <link href="<?= $assets ?>styles/print.css" rel="stylesheet"/>
	
		<link rel="stylesheet" href="<?= $assets ?>/styles/token-input.css" type="text/css" />
		<link rel="stylesheet" href="assets/vendor/magnific-popup/magnific-popup/magnific-popup.css" />
		
		<link rel="stylesheet" href="<?= $assets ?>js/bootstrap-datepicker/css/datepicker3.css" />
		<link rel="stylesheet" href="<?= $assets ?>js/bootstrap-multiselect/bootstrap-multiselect.css" />
    <script type="text/javascript" src="<?= $assets ?>js/jquery-2.0.3.min.js"></script>
    <script type="text/javascript" src="<?= $assets ?>js/jquery-migrate-1.2.1.min.js"></script>
		<script type="text/javascript" src="<?= $assets ?>js/jquery.tokeninput.js"></script>
		<link rel="stylesheet" href="<?= $assets ?>js/select2/select2.css" />
	<!--script type="text/javascript" src="<?= $assets ?>js/printThis.js"></script-->

    <!--[if lt IE 9]>
    <script src="<?= $assets ?>js/jquery.js"></script>
    <![endif]-->
    <noscript><style type="text/css">#loading { display: none; }</style></noscript>
    <?php if ($Settings->rtl) { ?>
        <link href="<?= $assets ?>styles/helpers/bootstrap-rtl.min.css" rel="stylesheet"/>
		<link rel="stylesheet" href="assets/vendor/bootstrap-multiselect/bootstrap-multiselect.css" />
        <link href="<?= $assets ?>styles/style-rtl.css" rel="stylesheet"/>
        <script type="text/javascript">
            $(document).ready(function () { $('.pull-right, .pull-left').addClass('flip'); });
        </script>
    <?php } ?>
    <script type="text/javascript">
		var $add_sale_price = 0;
        $(window).load(function () {
            $("#loading").fadeOut("slow");
        });
    </script>
<?php
$arr = get_defined_vars();
// print_r($quote_items);

?>
<style>
	ul.menu_padding5 li{
		padding-left:15px;
	}
</style>
		
		<!-- Basic -->
		<meta charset="UTF-8">

		<title>DOT EXE PTE, LTD</title>
		<meta name="keywords" content="DOT EXE PTE, LTD" />
		<meta name="description" content="Corporate Secretary System">
		<meta name="author" content="Graphica">

		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

		<!-- Web Fonts  -->
		<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

		<!-- Vendor CSS -->
		<link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.css" />

		<link rel="stylesheet" href="assets/vendor/font-awesome/css/font-awesome.css" />
		<link rel="stylesheet" href="assets/vendor/magnific-popup/magnific-popup.css" />
		<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/css/datepicker3.css" />

		<!-- Specific Page Vendor CSS -->
		<link rel="stylesheet" href="assets/vendor/jquery-ui/css/ui-lightness/jquery-ui-1.10.4.custom.css" />
		<link rel="stylesheet" href="assets/vendor/bootstrap-multiselect/bootstrap-multiselect.css" />
		<link rel="stylesheet" href="assets/vendor/morris/morris.css" />
		<link rel="stylesheet" href="assets/vendor/fullcalendar/fullcalendar.css" />
		<link rel="stylesheet" href="assets/vendor/fullcalendar/fullcalendar.print.css" media="print" />

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

			<!-- start: header -->
			<header class="header">
				<div class="logo-container">
					<a href="" class="logo" style="padding:0px;">
						<img src="<?=base_url('assets/uploads/logos/dot.ico')?>" width=35 style="margin:5px" height=auto alt="" />
					</a>
				</div>
			
				<!-- start: search & user box -->
				<div class="header-right">
			
					<form action="pages-search-results.html" class="search nav-form">
						<div class="input-group input-search">
							<input type="text" class="form-control" name="q" id="q" placeholder="Search...">
							<span class="input-group-btn">
								<button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
							</span>
						</div>
					</form>
			
					<span class="separator">
					</span>
			
					<ul class="notifications">
						<li>
							<a href="#" class="dropdown-toggle notification-icon" data-toggle="dropdown">
								<i class="fa fa-tasks"></i>
								<span class="badge">3</span>
							</a>
			
							<div class="dropdown-menu notification-menu large">
								<div class="notification-title">
									<span class="pull-right label label-default">3</span>
									Document
								</div>
			
								<div class="content">
									<ul>
										<li>
											<p class="clearfix mb-xs">
												<span class="message pull-left">AGM Related</span>
											</p>
											<a href="<?=base_url();?>documents" style="padding:0px;">
											<p class="clearfix" style="margin:0px;">
												<span class="message pull-left">Unreceived Document :</span>
												<span class="pull-right amber" style="padding:0px;font-size:12px;">10</span>
												
											</p></a>
											<p class="clearfix mb-xs">
												<span class="message pull-left">Company :</span>
												<span class="message pull-right text-dark">12</span>
											</p>
										</li>
										<hr/>
										<li>
											<p class="clearfix mb-xs">
												<span class="message pull-left">Non-AGM Related</span>
											</p>
											<a href="<?=base_url();?>documents">
											<p class="clearfix " style="margin:0px;">
												<span class="message pull-left">Unreceived Document :</span>
												<span class=" pull-right amber" style="padding:0px;font-size:12px;">2</span>
											</p>
											</a>
											<p class="clearfix mb-xs">
												<span class="message pull-left">Company :</span>
												<span class="message pull-right text-dark">10</span>
											</p>
										</li>
			
									</ul>
								</div>
							</div>
						</li>
						<li>
							<a href="#" class="dropdown-toggle notification-icon" data-toggle="dropdown">
								<i class="fa fa-bell"></i>
								<span class="badge">3</span>
							</a>
			
							<div class="dropdown-menu notification-menu">
								<div class="notification-title">
									<span class="pull-right label label-default">3</span>
									Alerts
								</div>
			
								<div class="content">
									<ul>
										<li>
											<a href="#" class="clearfix">
												<div class="image">
													<i class="fa fa-thumbs-down bg-danger"></i>
												</div>
												<span class="title">Server is Down!</span>
												<span class="message">Just now</span>
											</a>
										</li>
										<li>
											<a href="#" class="clearfix">
												<div class="image">
													<i class="fa fa-lock bg-warning"></i>
												</div>
												<span class="title">User Locked</span>
												<span class="message">15 minutes ago</span>
											</a>
										</li>
										<li>
											<a href="#" class="clearfix">
												<div class="image">
													<i class="fa fa-signal bg-success"></i>
												</div>
												<span class="title">Connection Restaured</span>
												<span class="message">10/10/2014</span>
											</a>
										</li>
									</ul>
			
									<hr />
			
									<div class="text-right">
										<a href="#" class="view-more">View All</a>
									</div>
								</div>
							</div>
						</li>
						<li>
							<a href="#" class="dropdown-toggle notification-icon" title="Draft" data-toggle="dropdown">
								<i class="fa fa-dashboard"></i>
								<span class="badge">3</span>
							</a>
			
							<div class="dropdown-menu notification-menu">
								<div class="notification-title">
									<span class="pull-right label label-default">2</span>
									Draft
								</div>
			
								<div class="content">
									<ul>
										<li>
											<a href="#" class="clearfix">
												<span class="title">Company 1</span>
												<span class="message">Company Agreement</span>
											</a>
										</li>
										<li>
											<a href="#" class="clearfix">
												<span class="title">Company 2</span>
												<span class="message">Stockholders Changes Letter</span>
											</a>
										</li>
									</ul>
			
									<hr />
			
									<div class="text-right">
										<a href="#" class="view-more">View All</a>
									</div>
								</div>
							</div>
						</li>
					</ul>
			
					<span class="separator"></span>
			
					<div id="userbox" class="userbox">
						<a href="#" data-toggle="dropdown">
							<figure class="profile-picture">
								<img src="<?= $this->session->userdata('avatar') ? site_url() . 'assets/uploads/avatars/thumbs/' . $this->session->userdata('avatar') : base_url('assets/images/' . $this->session->userdata('gender') . '.png'); ?>" alt="<?= $this->session->userdata('role'); ?>" class="img-circle" data-lock-picture="assets/images/!logged-user.jpg" />
							</figure>
							<div class="profile-info" data-lock-name="<?= $this->session->userdata('username'); ?>" data-lock-email="admin@graphica.co.id">
								<span class="name"><?= $this->session->userdata('username'); ?></span>
								<span class="role"><?= $this->session->userdata('role'); ?></span>
							</div>
			
							<i class="fa custom-caret"></i>
						</a>
			
						<div class="dropdown-menu">
							<ul class="list-unstyled">
								<li class="divider"></li>
								<li><a href="<?= site_url('users/profile/' . $this->session->userdata('user_id')); ?>"><i
											class="fa fa-user"></i> <?= lang('profile'); ?></a></li>
								<?php if($Owner) { ?>
								<li><a href="<?= site_url('auth/create_user/'); ?>"><i
											class="fa fa-users"></i>Add User</a></li>
								<?php } ?>
								<?php if($Owner) { ?>
								<li><a href="<?= site_url('auth/users/'); ?>"><i
											class="fa fa-users"></i>List of User</a></li>
								<?php } ?>
								<?php if($Owner) { ?>
								<li><a href="<?= site_url('master/'); ?>"><i
											class="fa fa-gear"></i>Master</a></li>
								<?php } ?>
								<?php if($Owner) { ?>
								<li><a href="<?= site_url('system_settings/backups'); ?>"><i
											class="fa fa-gear"></i>Bakup</a></li>
								<?php } ?>
								<li class="hidden">
									<a href="<?= site_url('users/profile/' . $this->session->userdata('user_id') . '/#cpassword'); ?>"><i
											class="fa fa-key"></i> <?= lang('change_password'); ?></a></li>
								<li class="divider"></li>
								<li><a href="<?= site_url('logout'); ?>"><i
                                        class="fa fa-sign-out"></i> <?= lang('logout'); ?></a></li>
							</ul>
						</div>
					</div>
					
					<span class="separator"></span>
					
					
				</div>
				<!-- end: search & user box -->
				</div>
			</header>
			<!-- end: header -->

			<div class="inner-wrapper">
				<section role="main" class="content-body" style="margin-left:0;">
					<header class="page-header" style="background-color:#FFBF00;">
						<h2><?php if ($page_name != "") echo $page_name; else echo "Dashboard";?></h2>
					
						<div class="right-wrapper pull-right">
							<ul class="notifications " style="margin:10px;">
								<li>
									<a href="#" class="dropdown-toggle notification-icon" data-toggle="dropdown">
										<i class="fa fa-bars" style="font-size:14px"></i>
									</a>
					
									<div class="dropdown-menu notification-menu">
					
										<div class="content">
											<ul>
												<?php if ($page_title == 'Dashboard') { ?>
												<li>
														Dashboard
												</li>
												<?php } else {
												?>
												<li>
													<a href="<?= base_url();?>welcome" class="btn-default amber">
														Dashboard
													</a>
												</li>
												<?php
												}
												?>
												<?php if ($page_title == 'Clients') { ?>
												<li>
														Clients
												</li>
												<?php } else {
												?>
												<li>
													<a href="<?= base_url();?>masterclient" class="btn-default amber">
														Clients
													</a>
												</li>
												<?php
												}
												?>
												<?php if ($page_title == 'Person') { ?>
												<li>
														Person
												</li>
												<?php } else {
												?>
												<li>
													<a href="<?= base_url();?>personprofile" class="btn-default amber">
														Person
													</a>
												</li>
												<?php
												}
												?>
												<?php if ($page_title == 'Documents') { ?>
												<li>
														Documents
												</li>
												<?php } else {
												?>
												<li>
													<a href="<?= base_url();?>documents" class="btn-default amber">
														Documents
													</a>
												</li>
												<?php
												}
												?>
												<?php if ($page_title == 'Billings') { ?>
												<li>
														Billing
												</li>
												<?php } else {
												?>
												<li>
													<a href="<?= base_url();?>billings" class="btn-default amber">
														Billing
													</a>
												</li>
												<?php
												}
												?>
												<li>
													<a href="#" class="btn-default amber">
														Report
													</a>
												</li>
					
											</ul>
										</div>
									</div>
								</li>
						
							</ul>
				
							<span class="separator" style="width:20px">&nbsp;</span>
							
						</div>
					</header>