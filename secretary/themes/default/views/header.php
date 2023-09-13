<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Expires" content="0" />
    <base href="<?= site_url() ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google" content="notranslate" />
    <!-- <meta http-equiv="refresh" content="10;url=<?php echo base_url("logout"); ?>" /> -->
    <title><?= $page_title ?> - <?= $Settings->site_name ?></title>
    <link rel="shortcut icon" href="<?= $assets ?>images/secretary_logo_icon.ico"/>
    <link href="<?= $assets ?>styles/print.css" rel="stylesheet"/>
	<link rel="stylesheet" href="<?= $assets ?>/styles/token-input.css" type="text/css" />
	<!-- <link rel="stylesheet" href="assets/vendor/magnific-popup/magnific-popup/magnific-popup.css" /> -->
	<link rel="stylesheet" href="<?= $assets ?>js/bootstrap-datepicker/css/datepicker3.css" />
	<link rel="stylesheet" href="<?= $assets ?>js/bootstrap-multiselect/bootstrap-multiselect.css" />
    <script type="text/javascript" src="<?= $assets ?>js/jquery-2.0.3.min.js"></script>
    <script type="text/javascript" src="<?= $assets ?>js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="<?= $assets ?>js/jquery-migrate-1.2.1.min.js"></script>
    <script src="assets/vendor/fullcalendar/lib/moment.min.js"></script>
	<script src="assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
	<script src="<?= $assets ?>js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
	<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script> -->
	<script type="text/javascript" src="<?= $assets ?>js/jquery.tokeninput.js"></script>
	<script src="<?= $assets ?>js/select2/select2.js"></script>
	<link rel="stylesheet" href="<?= $assets ?>js/select2/select2.css" />
	<link rel="stylesheet" href="<?= $assets ?>styles/fine-uploader-new.css" />
	<script type="text/javascript" src="<?= $assets ?>js/jquery.fine-uploader.js"></script>
	<link href="<?= $assets ?>styles/formValidation.css" media="all" rel="stylesheet" type="text/css"/>
	<link href="<?= $assets ?>styles/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
	<link href="<?= $assets ?>styles/toastr.min.css" media="all" rel="stylesheet" type="text/css"/>
	<link href="<?= $assets ?>styles/bootstrap-switch.min.css" media="all" rel="stylesheet" type="text/css"/>
	<link href="<?= $assets ?>styles/easy-autocomplete.min.css" media="all" rel="stylesheet" type="text/css"/>
	<script src="<?= $assets ?>js/fileinput.js" type="text/javascript"></script>
	<!-- sortable plugin for sorting/rearranging initial preview -->
	<script src="<?= $assets ?>js/sortable.js" type="text/javascript"></script>
	<!-- purify plugin for safe rendering HTML content in preview -->
	<script src="<?= $assets ?>js/purify.js" type="text/javascript"></script>
	<script src="<?= $assets ?>js/jquery.serialize-object.min.js" type="text/javascript"></script>
	<script src="<?= $assets ?>js/formValidation.js" type="text/javascript"></script>
	<script src="assets/vendor/bootstrap/js/bootstrap.js"></script>
	<script src="<?= $assets ?>js/framework/bootstrap.min.js" type="text/javascript"></script>
	<script src="<?= $assets ?>js/jquery.fileDownload.js" type="text/javascript"></script>
	<script src="<?= $assets ?>js/toastr.min.js" type="text/javascript"></script>
	<script src="<?= $assets ?>js/jQuery.print.js" type="text/javascript"></script>

	<script src="<?= $assets ?>js/bootstrap-switch.min.js" type="text/javascript"></script>
	
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB-fi60fNrmkDVHrAmeuD2S4dCw9CUxOLw&libraries=places">
  	</script>
  	<script type="text/javascript" src="https://gothere.sg/jsapi?sensor=false" async defer ></script>
	<script type="text/javascript" src="<?= $assets ?>js/printThis.js"></script>
	<script type="text/javascript" src="<?= $assets ?>js/jquery.sortElements.js"></script>

	<script type="text/javascript" src="<?= $assets ?>js/tinymce/jquery.tinymce.min.js"></script>
	<script type="text/javascript" src="<?= $assets ?>js/tinymce/tinymce.min.js"></script>
	<script type="text/javascript" src="<?= $assets ?>js/jquery.easy-autocomplete.min.js"></script>

	<!-- <script type="text/javascript" src="<?= $assets ?>js/jquery.dataTables.dtFilter.min.js"></script> -->
	<script src="assets/vendor/jquery-datatables/media/js/jquery.dataTables.min.js"></script>
	<script src="assets/vendor/jquery-datatables/media/js/dataTables.rowsGroup.js"></script>
	<script src="assets/vendor/jquery-datatables/media/js/dataTables.checkboxes.min.js"></script>
	<link rel="stylesheet" href="assets/vendor/jquery-datatables/media/css/dataTables.checkboxes.css" />
	<script src="assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js"></script>
	<script src="assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
	<script src="assets/javascripts/tables/examples.datatables.default.js"></script>
	<script src="assets/vendor/jquery-datatables/media/js/natural.js"></script>
	<script src="https://cdn.datatables.net/plug-ins/1.10.16/sorting/custom-data-source/dom-checkbox.js"></script>

	<!-- <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script> -->
	<script src="<?= $assets ?>js/datatable/dataTables.buttons.min.js" type="text/javascript"></script>
	<script src="<?= $assets ?>js/datatable/jszip.min.js" type="text/javascript"></script>
	<script src="<?= $assets ?>js/datatable/pdfmake.min.js" type="text/javascript"></script>
	<script src="<?= $assets ?>js/datatable/vfs_fonts.js" type="text/javascript"></script>
	<script src="<?= $assets ?>js/datatable/buttons.html5.min.js" type="text/javascript"></script>
	<!-- <script src="<?= $assets ?>styles/datatable/buttons.dataTables.min.css" type="text/javascript"></script> -->
	<!-- <script src="https://cdn.datatables.net/rowgroup/1.1.1/js/dataTables.rowGroup.min.js" type="text/javascript"></script> -->
	<!-- <link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.1.1/css/rowGroup.dataTables.min.css" /> -->

	<script type="text/javascript" src="<?= $assets ?>js/bootbox.min.js"></script>
	<link rel="stylesheet" href="<?= $assets ?>styles/intlTelInput.css" />
	<script src="assets/vendor/jquery-validation/jquery.validate.js"></script>

	<script src="https://sdk.amazonaws.com/js/aws-sdk-2.579.0.min.js"></script>
	<script src="node_modules/amazon-cognito-identity-js/dist/amazon-cognito-identity.min.js"></script>
	<script src="<?= $assets ?>js/crypto-js.js"></script>
	<script type="<?= $assets ?>js/aes-min.js"></script>
	<link rel="stylesheet" href="<?= $assets ?>js/auto-suggest-list/auto-suggest-list.css" />
	<script type="text/javascript" src="<?= $assets ?>js/auto-suggest-list/auto-suggest-list-v1.0.0.min.js"></script>
	<link rel="stylesheet" href="<?= $assets ?>js/smartselect/dist/smartselect.min.css" />
	<script type="text/javascript" src="<?= $assets ?>js/smartselect/dist/jquery.smartselect.min.js"></script>
    <!--[if lt IE 9]>
    <script src="<?= $assets ?>js/jquery.js"></script>
    <![endif]-->
    <noscript><style type="text/css">#loading { display: none; }</style></noscript>
    <?php if ($Settings->rtl) { ?>
        <link href="<?= $assets ?>styles/helpers/bootstrap-rtl.min.css" rel="stylesheet"/>
		<!-- <link rel="stylesheet" href="assets/vendor/bootstrap-multiselect/bootstrap-multiselect.css" /> -->
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

	<style>
		ul.menu_padding5 li{
			padding-left:15px;
		}
	</style>
			
		<!-- Basic -->
		<meta charset="UTF-8">

		<!-- <title>Acumen Cognitive Technology Pte Ltd</title> -->
		<meta name="keywords" content="Acumen Cognitive Technology Pte Ltd" />
		<meta name="description" content="Corporate Secretary System">
		<meta name="author" content="Graphica">

		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

		<!-- Web Fonts  -->
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

		<!-- Vendor CSS -->
		<link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.css" />

		<!-- <link rel="stylesheet" href="assets/vendor/font-awesome/css/font-awesome.css" /> -->
		<link rel="stylesheet" href="assets/vendor/font-awesome/css/all.css" />
		<link rel="stylesheet" href="assets/vendor/magnific-popup/magnific-popup.css" />
		<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/css/datepicker3.css" />
		<link rel="stylesheet" href="assets/vendor/chosen/chosen.min.css" />

		<!-- Specific Page Vendor CSS -->
		<!-- <link rel="stylesheet" href="assets/vendor/jquery-ui/css/ui-lightness/jquery-ui-1.10.4.custom.css" /> -->
		<!-- <link rel="stylesheet" href="assets/vendor/bootstrap-multiselect/bootstrap-multiselect.css" />
 -->	<link rel="stylesheet" href="assets/vendor/morris/morris.css" />
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
		<script src="assets/vendor/chosen/chosen.jquery.js"></script>
	</head>
	<body data-spy="scroll" data-target=".scrollspy">
		<section class="body">
			<div class="inner-wrapper">
				<section role="main" class="content-body" style="margin-left:0;">
					<!-- start: header -->
					<header class="header">
						<div class="logo-container">
							<a href="" class="logo" style="padding:0px;">
								<!-- <?php echo ($logo) != null?> -->
								<?php if($logo != null) { ?>
									<img src="<?php echo base_url('uploads/logo/'.$logo.'');?>" width=35 style="margin:5px" height=auto alt="" />
								<?php } ?>
							</a>
						</div>
						<div class="firm_name">
							<?php echo $firm_name?>
						</div>
						<!-- start: search & user box -->
						<div class="header-right">
							<span class="separator"></span>
							<div id="userbox" class="userbox">
								<a href="#" data-toggle="dropdown">
									<!-- <figure class="profile-picture">
										<img src="<?= $this->session->userdata('avatar') ? site_url() . 'assets/uploads/avatars/thumbs/' . $this->session->userdata('avatar') : base_url('assets/images/' . $this->session->userdata('gender') . '.png'); ?>" alt="<?= $this->session->userdata('role'); ?>" class="img-circle" data-lock-picture="assets/images/!logged-user.jpg" />
									</figure> -->
									<div class="profile-info" data-lock-name="<?= $this->session->userdata('first_name'); ?>" data-lock-email="admin@graphica.co.id">
										<span class="name"><?= $this->session->userdata('first_name'); ?></span>
										<span class="role"><?= $this->session->userdata('role'); ?></span>
									</div>
					
									<i class="fa custom-caret"></i>
								</a>
					
								<div class="dropdown-menu">
									<ul class="list-unstyled">
										<li class="divider"></li>
										<!-- <li><a href="<?= site_url('users/profile/' . $this->session->userdata('user_id')); ?>"><i
													class="fa fa-user"></i> <?= lang('profile'); ?></a></li> -->
										<!-- <?php if($Owner) { ?>
										<li><a href="<?= site_url('auth/create_user'); ?>"><i
													class="fa fa-users"></i>Add User</a></li>
										<?php } ?> -->
										<?php if ((!$Individual && $Individual == true) || (!$Individual && $Individual == null && !$Client) || (!$Bookkeeper && $Bookkeeper == true) || (!$Bookkeeper && $Bookkeeper == null && !$Client)) {?>
											<li id="header_our_firm"><a href="<?= site_url('our_firm/'); ?>"><i
													class="fa fa-user"></i> Our Firm</a></li>
										<?php } ?>
										<?php if (($Admin && !$Individual) || ($Manager && !$Individual)) {?>
											<li id="header_our_services"><a href="<?= site_url('our_services/'); ?>"><i class="fas fa-user-tie"></i> Our Services</a></li>
										<?php } ?>
										<?php if(($Admin && !$Individual)) { ?>
										<li id="header_manage_user">
											<a href="<?= site_url('auth/users/'); ?>">
											<i class="fas fa-users-cog" style="font-size: 1.2rem;"></i> Manage User</a>
										</li>
										<?php } ?>
										<?php if(($Admin && !$Individual) || ($Manager && !$Individual)) { ?>
										<li id="header_access_right">
											<a href="<?= site_url('system_settings/user_permissions/'); ?>">
											<i class="fas fa-unlock-alt"></i> Access Right</a>
										</li>
										<?php } ?>
										<!-- <?php if($Owner) { ?> -->
										
										<!-- <?php } ?> -->
										<!-- <?php if($Admin) { ?>
										<li><a href="<?= site_url('master/'); ?>"><i
													class="fa fa-gear"></i>Master</a></li>
										<?php } ?> -->
										<?php if(($Admin && !$Individual) || ($Manager && !$Individual)) { ?>
										<li id="header_client_access" style="display: none;">
											<a href="<?= site_url('auth/client/'); ?>">
											<i class="fas fa-users-cog" style="font-size: 1.2rem;"></i> Client Access</a>
										</li>
										<?php } ?>
										<li id="header_user_profile">
											<a href="<?= site_url('users/profile/' . $this->session->userdata('user_id') . '/#cpassword'); ?>"><i class="fas fa-users" style="font-size: 1.2rem;"></i> User Profile</a>
										</li>
										<!-- <?php if($Owner) { ?> -->
										<!-- <?php } ?> -->
										<?php if($Admin) { ?>
										<li id="header_backup"><a href="<?= site_url('system_settings/backups'); ?>">
											<i class="fas fa-hdd" style="font-size: 1.3rem;"></i> Backup</a></li>
										<?php } ?>
										<?php if(($Admin && !$Individual) || ($Manager && !$Individual) || ($this->session->userdata('email_for_user') == "justin@aaa-global.com" && !$Individual)) { ?>
										<li id="header_email_history">
											<a href="<?= site_url('system_settings/email_history/'); ?>">
											<i class="fas fa-envelope"></i> Email History</a>
										</li>
										<?php } ?>
										<?php if(($Admin && !$Individual) || ($Manager && !$Individual) || ($this->session->userdata('email_for_user') == "justin@aaa-global.com" && !$Individual) || ($Bookkeeper && !$Individual)) { ?>
										<li id="header_audit_trail">
											<a href="<?= site_url('system_settings/audit_trail/'); ?>">
											<i class="fas fa-file-alt" style="margin-left: 3px;"></i> Audit Trail</a>
										</li>
										<?php } ?>
										<!-- <li id="header_rules">
											<a href="<?= site_url('system_settings/rules/'); ?>">
											<i class="fas fa-envelope"></i> Rules</a>
										</li> -->
										<?php if($Admin) { ?>
										<li  id="header_setting">
											<a href="<?= site_url('admin_setting/'); ?>">
											<i class="fas fa-cog"></i> Settings</a>
										</li>
										<?php } ?>
										<li class="divider"></li>
										<!-- <li><a href="<?= site_url('logout'); ?>"><i
		                                        class="fa fa-sign-out"></i> <?= lang('logout'); ?></a></li> -->
		                                <li><a href="javascript:void(0)" onclick="user_logout()"><i
		                                        class="fa fa-sign-out"></i> <?= lang('logout'); ?></a></li>
									</ul>
								</div>
							</div>
							<span class="separator"></span>
						</div>
						<!-- end: search & user box -->
					</header>
					<!-- end: header -->
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
												<li id="header_dashboard">
													<a href="<?= base_url();?>welcome" class="btn-default amber">
														Dashboard
													</a>
												</li>
												<?php if($Owner) { ?>
													<li>
														<a href="<?= base_url();?>user_billings" class="btn-default amber">
															Billing
														</a>
													</li>
												<?php } ?> 
												<?php if(!$Owner) { ?>

													<li id="header_transaction">
														<a href="<?= site_url('transaction/'); ?>" class="btn-default amber">Services</a>
													</li>
													<?php if ($client_module != 'none') { ?>
													<li id="header_client">
														<a href="<?= base_url();?>masterclient" class="btn-default amber">
															Clients
														</a>
													</li>
													<?php
														}
													?>

													<?php if ($person_module != 'none') { ?>
													<li id="header_person">
														<a href="<?= base_url();?>personprofile" class="btn-default amber">
															Person
														</a>
													</li>
													<?php
														}
													?>

													<?php if ($document_module != 'none') { ?>
													<li id="header_document">
														<a href="<?= base_url();?>documents" class="btn-default amber">
															Documents
														</a>
													</li>
													<?php
														}
													?>

													<?php if ($report_module != 'none') { ?>
													<li id="header_report">
														<a href="<?= base_url();?>report" class="btn-default amber">
															Report
														</a>
													</li>
													<?php
														}
													?>

													<?php if ($billing_module != 'none') { ?>
													<li id="header_billings">
														<a href="<?= base_url();?>billings" class="btn-default amber">
															Billings
														</a>
													</li>
													<?php
														}
													?>

													<?php if ($billing_module != 'none') { ?>
													<li id="header_payment_voucher">
														<a href="<?= base_url();?>payment_voucher" class="btn-default amber">
															Payment
														</a>
													</li>
													<?php
														}
													?>
												<?php } ?> 
					
											</ul>
										</div>
									</div>
								</li>
							</ul>
							<span class="separator" style="width:20px">&nbsp;</span>
						</div>
					</header>