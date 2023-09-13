<head>
    <meta charset="utf-8">
    <base href="<?= site_url() ?>"/>
    <link rel="shortcut icon" href="<?= base_url() ?>assets/logo/logo.ico" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <meta http-equiv="refresh" content="10;url=<?php echo base_url("logout"); ?>" /> -->

    <!-- <title><?= $page_title ?> - <?= $Settings->site_name ?></title> -->
    <title><?= $page_title ?></title>

    <!-- <link rel="shortcut icon" href="<?= $assets ?>images/ACT_payroll.ico"/> -->
    <!-- <link href="<?= $assets ?>styles/print.css" rel="stylesheet"/> -->
	
	<!-- <link rel="stylesheet" href="<?= $assets ?>/styles/token-input.css" type="text/css" /> -->
		
	<!-- <link rel="stylesheet" href="<?= $assets ?>js/bootstrap-datepicker/css/datepicker3.css" /> -->
	<!-- <link rel="stylesheet" href="<?= $assets ?>js/bootstrap-multiselect/bootstrap-multiselect.css" /> -->
    <!-- <script type="text/javascript" src="<?= $assets ?>js/jquery-2.0.3.min.js"></script> -->
    <!-- <script src="<?= base_url() ?>node_modules/jquery/dist/jquery.min.js"></script> -->

    <link href="<?= base_url() ?>application/css/theme-default.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url() ?>application/css/theme-custom.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url() ?>application/css/theme.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url() ?>node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url() ?>node_modules/font-awesome/css/font-awesome.min.css" rel="stylesheet">

    <script src="<?= base_url() ?>node_modules/jquery/dist/jquery.min.js"></script>

    <script src="<?= base_url() ?>node_modules/moment/moment.js"></script>
    <script src="<?= base_url() ?>node_modules/moment/min/moment.min.js"></script>
    <script src="<?= base_url() ?>node_modules/bootstrap/js/transition.js"></script>
    <script src="<?= base_url() ?>node_modules/bootstrap/js/collapse.js"></script>
    <script src="<?= base_url() ?>node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="<?= base_url() ?>node_modules/bootstrap/dist/js/bootstrap.js"></script>

    <!-- <script src="<?= base_url() ?>node_modules/bootstrap-datepicker/js/bootstrap-datepicker.js"></script> -->

    <script src="<?= base_url() ?>node_modules/bootstrap-datepicker/dist/js/bootstrap-datepicker.js"></script>
    <link rel="stylesheet" href="<?= base_url() ?>node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css" />

    <noscript><style type="text/css">#loading { display: none; }</style></noscript>

   <!--  <?php if ($Settings->rtl) { ?>
        <link href="<?= $assets ?>styles/helpers/bootstrap-rtl.min.css" rel="stylesheet"/>
		<link rel="stylesheet" href="assets/vendor/bootstrap-multiselect/bootstrap-multiselect.css" />
        <link href="<?= $assets ?>styles/style-rtl.css" rel="stylesheet"/>
        <script type="text/javascript">
            $(document).ready(function () { $('.pull-right, .pull-left').addClass('flip'); });
        </script>
    <?php } ?> -->

    <!-- <script type="text/javascript">
		var $add_sale_price = 0;
        $(window).load(function () {
            $("#loading").fadeOut("slow");
        });
    </script> -->

	<!-- <?php
	$arr = get_defined_vars();
	// print_r($quote_items);

	?> -->
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
	<link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.css" />

	<link rel="stylesheet" href="node_modules/font-awesome/css/font-awesome.css" />
	<!-- <link rel="stylesheet" href="assets/vendor/magnific-popup/magnific-popup.css" /> -->
	<!-- <link rel="stylesheet" href="node_modules/bootstrap-datepicker/css/datepicker3.css" /> -->

	<!-- Specific Page Vendor CSS -->
	<!-- <link rel="stylesheet" href="assets/vendor/jquery-ui/css/ui-lightness/jquery-ui-1.10.4.custom.css" /> -->
	<!-- <link rel="stylesheet" href="assets/vendor/bootstrap-multiselect/bootstrap-multiselect.css" /> -->
	<!-- <link rel="stylesheet" href="assets/vendor/morris/morris.css" />
	<link rel="stylesheet" href="assets/vendor/fullcalendar/fullcalendar.css" />
	<link rel="stylesheet" href="assets/vendor/fullcalendar/fullcalendar.print.css" media="print" />
 -->
	<!-- Chosen plugin for dropdown list with search -->
	<!-- <link rel="stylesheet" href="node_modules/chosen_v1.8.7/chosen.css" /> -->	
    <link rel="stylesheet" href="<?= base_url() ?>node_modules/chosen-js/chosen.css" />
    <script src="<?= base_url() ?>node_modules/chosen-js/chosen.jquery.js"></script>

    <!-- File input (Drag and drop) -->
    <link rel="stylesheet" href="<?= base_url() ?>application/css/plugin/fileinput.css" />
    <script src="<?= base_url() ?>application/js/fileinput.js"></script>

    <!-- Toastr plugin to pop out message -->
    <link rel="stylesheet" href="<?= base_url() ?>application/css/plugin/toastr.min.css" />
    <script src="<?= base_url() ?>application/js/toastr.min.js"></script>

	<!-- Theme CSS -->
	<link rel="stylesheet" href="<?= base_url() ?>application/css/theme.css" />
    <link rel="stylesheet" href="<?= base_url() ?>application/css/modules/applicant/form.css" />

    <!-- SELECT2 -->
    <link href="<?= base_url() ?>node_modules/select2/dist/css/select2.min.css" rel="stylesheet" />
    <script src="<?= base_url() ?>node_modules/select2/dist/js/select2.min.js"></script>

    <script src="<?= base_url() ?>plugin/jQuery-Mask-Plugin-master/dist/jquery.mask.min.js"></script>

	<!-- Skin CSS -->
	<!-- <link rel="stylesheet" href="application/stylesheets/skins/default.css" /> -->

	<!-- Theme Custom CSS -->
	<!-- <link rel="stylesheet" href="assets/stylesheets/theme-custom.css"> -->

	<!-- Head Libs -->
	<!-- <script src="assets/vendor/modernizr/modernizr.js"></script> -->
</head>

<!-- Custom js files -->
<script src="<?= base_url() ?>application/js/custom/applicant_profile_image.js"></script>

<body>
	<section class="body">
        <!-- <?php echo json_encode($applicant); ?> -->
		<div>
			<section role="main" class="content-body" style="margin-left:0; display: inherit;">
				<header class="page-header" style="background-color:#9349BD;">
					<!-- <h2>Interview - Applicant's Detail</h2> -->
                    <h2>Application Form</h2>
				</header>

				<div style="margin-top: 30px;">
				  <!--   <?php echo $breadcrumbs;?> -->
				</div>

                <div class="box" style="margin-bottom: 30px;">
                    <div class="box-content">
                        <div class="row">
                            <div class="col-lg-12">
                                <form id ="applicant_info" method="post" enctype="multipart/form-data">
                                <?php echo form_open_multipart('Applicant/save_applicant', array('id' => 'applicant_info', 'enctype' => "multipart/form-data")); ?>
                                <input type="hidden" class="form-control" id="applicant_id" name="applicant_id" value="<?=$applicant_id ?>"/>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-12">

                                        	<div class="form-group">
                		                        <div style="width: 100%;">
                		                            <div style="width: 25%;float:left;margin-right: 20px;">
                		                                <label>CV Photo :</label>
                		                            </div>
                		                            <div style="width: 65%;float:left;margin-bottom:5px;">
                		                                
                                                        <div class="profile">
                                                            <div class="photo">
                                                                <input type="file" accept="image/*" name="applicant_pic">
                                                                <div class="photo__helper">
                                                                    <div class="photo__frame photo__frame--circle">
                                                                        <canvas class="photo__canvas"></canvas>
                                                                        <div class="message is-empty">
                                                                            <p class="message--desktop">Drop your photo here or browse your computer.</p>
                                                                            <p class="message--mobile">Tap here to select your picture.</p>
                                                                        </div>
                                                                        <div class="message is-loading">
                                                                            <i class="fa fa-2x fa-spin fa-spinner"></i>
                                                                        </div>
                                                                        <div class="message is-dragover">
                                                                            <i class="fa fa-2x fa-cloud-upload"></i>
                                                                            <p>Drop your photo</p>
                                                                        </div>
                                                                        <div class="message is-wrong-file-type">
                                                                            <p>Only images allowed.</p>
                                                                            <p class="message--desktop">Drop your photo here or browse your computer.</p>
                                                                            <p class="message--mobile">Tap here to select your picture.</p>
                                                                        </div>
                                                                        <div class="message is-wrong-image-size">
                                                                            <p>Your photo must be larger than 350px.</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="okBtn" style="display: none;">
                                                                    <a id="previewBtn" style="cursor: pointer;">SAVE</a>
                                                                    /
                                                                    <a class="remove" style="cursor: pointer;">REMOVE</a>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="showImage" style="display: none; width:300px; text-align: center;">
                                                            <div>
                                                                <img src="<?=$applicant->pic ?>" alt="" class="preview">
                                                                <input type="hidden" id="applicant_pic" name="applicant_preview_pic" value="<?=$applicant->pic ?>" />
                                                            </div>
                                                            <div>
                                                                <a id="editProfilePicBtn" style="cursor: pointer;">Edit</a>
                                                                <a id="removeProfilePicBtn" style="cursor: pointer; color:red; display: none">Remove</a>
                                                            </div>
                                                        </div>
                		                            </div>
                		                        </div>
                		                    </div>

                                        	<div class="form-group">
                                                <div style="width: 100%;">
                                                    <div style="width: 25%;float:left;margin-right: 20px;">
                                                        <label>Position Applied For:</label>
                                                    </div>
                                                    <div style="width: 65%;float:left;margin-bottom:5px;">
                                                        <div class="input-group" style="width: 20%;">
                                                        	<input type="text" class="form-control" id="applicant_position" name="applicant_position" value="<?=$applicant->position?>" style="width: 400px;"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div style="width: 100%;">
                                                    <div style="width: 25%;float:left;margin-right: 20px;">
                                                        <label>Full Name :</label>
                                                    </div>
                                                    <div style="width: 65%;float:left;margin-bottom:5px;">
                                                        <div class="input-group" style="width: 20%;">
                                                        	<input type="text" class="form-control" id="applicant_name" name="applicant_name" value="<?=$applicant->name?>" style="width: 400px;"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div style="width: 100%;">
                                                    <div style="width: 25%;float:left;margin-right: 20px;">
                                                        <label>NRIC/Passport No. :</label>
                                                    </div>
                                                    <div style="width: 65%;float:left;margin-bottom:5px;">
                                                        <div class="input-group" style="width: 20%;">
                                                            <input type="text" class="form-control" id="applicant_ic_passport_no" name="applicant_ic_passport_no" value="<?=$applicant->ic_passport_no?>" style="width: 400px;"/>
                                                        </div>
                                                        <div id="form_fax"></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div style="width: 100%;">
                                                    <div style="width: 25%;float:left;margin-right: 20px;">
                                                        <label>Phone No. :</label>
                                                    </div>
                                                    <div style="width: 65%;float:left;margin-bottom:5px;">
                                                        <div class="input-group" style="width: 20%;" >
                                                            <input type="text" class="form-control" id="applicant_phoneno" name="applicant_phoneno" value="<?=$applicant->phoneno?>" style="width: 400px;"/>
                                                        </div>
                                                    <div id="form_telephone"></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div style="width: 100%;">
                                                    <div style="width: 25%;float:left;margin-right: 20px;">
                                                        <label>Email :</label>
                                                    </div>
                                                    <div style="width: 65%;float:left;margin-bottom:5px;">
                                                        <div class="input-group" style="width: 20%;">
                                                            <input type="email" class="form-control" id="applicant_email" name="applicant_email" value="<?=$applicant->email?>" style="width: 400px;" placeholder="eg. examples@gmail.com"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div style="width: 100%;">
                                                    <div style="width: 25%;float:left;margin-right: 20px;">
                                                        <label>Nationality :</label>
                                                    </div>
                                                    <div style="width: 65%;float:left;margin-bottom:5px;">
                                                        <div class="input-group" style="width: 20%;" >
                                                        	<?php
                												echo form_dropdown('applicant_nationality', $nationality_list, $applicant->nationality_id, 'class="nationality-select select2" style="width:400px;"');
                											?>
                                                        </div>
                                                        <div id="form_url"></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div style="width: 100%;">
                                                    <div style="width: 25%;float:left;margin-right: 20px;">
                                                        <label>Residential Address :</label>

                                                    </div>
                                                    <div style="width: 65%;float:left;margin-bottom:5px;">
                                                        <div class="input-group" style="width: 20%;" >
                                                            <?php 
                                                                echo '<textarea class="form-control" rows="5" id="applicant_address" name="applicant_address" style="width:400px;">'.$applicant->address.'</textarea>';
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                		                    <div class="form-group">
                		                    	<div style="width: 100%;">
                			                    	<div style="width: 25%;float:left;margin-right: 20px;">
                			                            <label>Date of Birth :</label>
                			                        </div>
                			                    	<div style="float: left;">
                			                            <div class="input-group date datepicker" style="width:400px;">
                                                            <div class="input-group-addon">
                                                                <span class="glyphicon glyphicon-calendar"></span>
                                                            </div>
                                                            <input type='text' class="form-control" name="applicant_DOB" id='applicant_DOB' value="<?=isset($applicant->dob)?date('d F Y', strtotime($applicant->dob)):''?>"/>
                                                        </div>
                			                        </div>
                			                    </div>
                		                    </div>

                		                    <div class="form-group">
                		                        <div style="width: 100%;">
                		                            <div style="width: 25%;float:left;margin-right: 20px;">
                		                                <label>Gender : </label>
                		                            </div>
                		                            <div style="width: 65%;float:left;margin-bottom:5px;">
                		                                <div class="input-group" style="width: 100%;" >
                		                                	<?php
                                                                echo form_dropdown('applicant_gender', $gender, $applicant->gender, 'class="select2" style="width:200px;"');
                                                            ?>
                		                                </div>
                		                            </div>
                		                        </div>
                		                    </div>

                                            <div class="form-group">
                                                <div style="width: 100%;">
                                                    <div style="width: 25%;float:left;margin-right: 20px;">
                                                        <label>Race : </label>
                                                    </div>
                                                    <div style="width: 65%;float:left;margin-bottom:5px;">
                                                        <div class="input-group" style="width: 100%;" >
                                                            <input type="text" class="form-control" id="applicant_race" name="applicant_race" value="<?=isset($applicant->race)?$applicant->race:''?>" style="width: 400px;"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div style="width: 100%;">
                                                    <div style="width: 25%;float:left;margin-right: 20px;">
                                                        <label>Marital Status : </label>
                                                    </div>
                                                    <div style="width: 65%;float:left;margin-bottom:5px;">
                                                        <div class="input-group" style="width: 100%;" >
                                                            <input type="text" class="form-control" id="applicant_marital_status" name="applicant_marital_status" value="<?=isset($applicant->marital_status)?$applicant->marital_status:''?>" style="width: 400px;"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                		                    <div class="form-group">
                		                        <div style="width: 100%;">
                		                            <div style="width: 25%;float:left;margin-right: 20px;">
                		                                <label>Academic Qualification :</label>
                		                            </div>
                		                            <div style="width: 65%;float:left;margin-bottom:5px;">
                		                            	<div class="input-group">
                		                            		<!-- <a class="btn btn_purple" onclick="add_education()">
                                                                <span class="glyphicon glyphicon-plus-sign" style="margin-right: 5%"></span>
                                                                <label class="addBtn_title">Add Education</label>
                		                            		</a> -->
                                                            <button type="button" onclick="add_education()" class="btn btn_purple" style="width:200px;">
                                                                <span class="glyphicon glyphicon-plus-sign"></span>
                                                                <label style="margin-bottom:0 !important">Add Education</label>
                                                            </button>
                                                        </div>
                                                        <div id="education_section" class="wrap input-group"></div>
                		                            </div>
                		                        </div>
                		                    </div>

                                            <div class="form-group">
                                                <div style="width: 100%;">
                                                    <div style="width: 25%;float:left;margin-right: 20px;">
                                                        <label>Membership of Social / Professional Bodies :</label>
                                                    </div>
                                                    <div style="width: 65%;float:left;margin-bottom:5px;">
                                                        <div class="input-group">
                                                            <!-- <a class="btn btn_purple" onclick="add_professional()">
                                                                <span class="glyphicon glyphicon-plus-sign" style="margin-right: 3%"></span>
                                                                <label class="addBtn_title">Add Professional Membership</label>
                                                            </a> -->

                                                            <button type="button" onclick="add_professional()" class="btn btn_purple" style="width:200px;">
                                                                <span class="glyphicon glyphicon-plus-sign"></span>
                                                                <label style="margin-bottom:0 !important">Add Professional</label>
                                                            </button>
                                                        </div>
                                                        <div id="professional_section" class="wrap input-group"></div>
                                                    </div>
                                                </div>
                                            </div>

                		                    <div class="form-group">
                		                        <div style="width: 100%;">
                		                            <div style="width: 25%;float:left;margin-right: 20px;">
                		                                <label>Employment History :</label>
                		                            </div>
                		                            <div style="width: 65%;float:left;margin-bottom:5px;">
                		                                <!-- <div class="input-group"> -->
                		                            		<!-- <a class="btn btn_purple" onclick="add_experience()">
                                                                <span class="glyphicon glyphicon-plus-sign" style="margin-right: 5%"></span>
                                                                <label class="addBtn_title">Add Experience</label>
                		                            		</a> -->

                                                            <button type="button" onclick="add_experience()" class="btn btn_purple" style="width:200px;">
                                                                <span class="glyphicon glyphicon-plus-sign"></span>
                                                                <label style="margin-bottom:0 !important">Add Experience</label>
                                                            </button>
                                                        <!-- </div> -->
                                                            <div id="experience_section" class="wrap input-group"></div>
                		                            </div>
                		                        </div>
                		                    </div>

                		                    <!-- <div class="form-group">
                		                        <div style="width: 100%;">
                		                            <div style="width: 25%;float:left;margin-right: 20px;">
                		                                <label>Skills :</label>
                		                            </div>
                		                            <div style="width: 65%;float:left;margin-bottom:5px;">
                		                                <div class="input-group" style="width: 20%;">
                                                        	<input type="text" class="form-control" id="applicant_skills" name="applicant_skills" value="<?=$applicant->skills?>" style="width: 400px;"/>
                                                        </div>
                		                            </div>
                		                        </div>
                		                    </div> -->

                                            <div class="form-group">
                                                <div style="width: 100%;">
                                                    <div style="width: 25%;float:left;margin-right: 20px;">
                                                        <label>Immediate Family Member :</label>
                                                    </div>
                                                    <div style="width: 65%;float:left;margin-bottom:5px;">
                                                        <div class="input-group">
                                                            <!-- <a class="btn btn_purple" onclick="add_education()">
                                                                <span class="glyphicon glyphicon-plus-sign" style="margin-right: 5%"></span>
                                                                <label class="addBtn_title">Add Education</label>
                                                            </a> -->
                                                            <button type="button" onclick="add_family()" class="btn btn_purple" style="width:200px;">
                                                                <span class="glyphicon glyphicon-plus-sign"></span>
                                                                <label style="margin-bottom:0 !important">Add Family Member</label>
                                                            </button>
                                                        </div>
                                                        <div id="family_section" class="wrap input-group"></div>
                                                    </div>
                                                </div>
                                            </div>

                		                    <div class="form-group">
                		                        <div style="width: 100%;">
                		                            <div style="width: 25%;float:left;margin-right: 20px;">
                		                                <label>Characters Referee :</label>
                		                            </div>
                                                    <div style="width: 65%;float:left;margin-bottom:5px;">
                                                        <div class="input-group">
                                                            <!-- <a class="btn btn_purple" onclick="add_referral()">
                                                                <span class="glyphicon glyphicon-plus-sign" style="margin-right: 5%"></span>
                                                                <label class="addBtn_title">Add Referral</label>
                                                            </a> -->

                                                            <button type="button" onclick="add_referral()" class="btn btn_purple" style="width:200px;">
                                                                <span class="glyphicon glyphicon-plus-sign"></span>
                                                                <label style="margin-bottom:0 !important">Add Referral</label>
                                                            </button>
                                                        </div>
                                                        <div id="referral_section" class="wrap input-group"></div>
                                                    </div>
                		                        </div>
                		                    </div>

                                            <div class="form-group">
                                                <div style="width: 100%;">
                                                    <div style="width: 25%;float:left;margin-right: 20px;">
                                                        <label>Language Proficiency :</label>
                                                    </div>
                                                    <div style="width: 65%;float:left;margin-bottom:5px;">
                                                            <div class="input-group" style="width: 100%;">
                                                                <label>Proficiency level: 1 - Poor, 10 - Excellent</label>
                                                                <table class="table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Languages</th>
                                                                            <th>Spoken</th>
                                                                            <th>Written</th>
                                                                            <th>Read</th>
                                                                            <th>
                                                                                <span class="glyphicon glyphicon-plus-sign purple_addBtn" onclick="add_language_tr()"></span>
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="language_tr"></tbody>
                                                                </table>
                                                            </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div style="width: 100%;">
                                                    <div style="width: 25%;float:left;margin-right: 20px;">
                                                        <label>Current Salary :</label>

                                                    </div>
                                                    <div style="width: 65%;float:left;margin-bottom:5px;">
                                                        <div class="input-group" style="width: 100%;" >
                                                            <input type="text" class="form-control input_num_val" id="applicant_last_drawn_salary" name="applicant_last_drawn_salary" value="<?=$applicant->last_drawn_salary?>" style="width: 400px;"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div style="width: 100%;">
                                                    <div style="width: 25%;float:left;margin-right: 20px;">
                                                        <label>Expected Salary :</label>
                                                    </div>
                                                    <div style="width: 65%;float:left;margin-bottom:5px;">
                                                        <div class="input-group" style="width: 100%;">
                                                            <input type="text" class="form-control input_num_val" id="applicant_expected_salary" name="applicant_expected_salary" value="<?=$applicant->expected_salary?>" style="width: 400px;"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div style="width: 100%;">
                                                    <div style="width: 25%;float:left;margin-right: 20px;">
                                                        <label>Others :</label>

                                                    </div>
                                                    <div style="width: 65%;float:left;margin-bottom:5px;">
                                                        <?php 
                                                            echo '<textarea class="form-control" rows="5" id="applicant_about" name="applicant_about" placeholder="Any other matters you wish to bring to our attention?">'.$applicant->about.'</textarea>';
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div style="width: 100%;">
                                                    <div style="width: 25%;float:left;margin-right: 20px;">
                                                        <label>Upload Documents (PDF Only) :</label>
                                                    </div>
                                                    <div style="width: 65%;float:left;margin-bottom:5px;">
                                                        <div class="input-group" style="width:100%;">
                                                            <div class="file-loading">
                                                                <!-- <input type="file" id="applicant_resume" class="file" name="applicant_resume" data-min-file-count="0" accept="pdf"> -->
                                                                <input type="file" id="applicant_resume" class="file" name="applicant_resume[]" multiple="true" accept="pdf">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div style="width: 100%;">
                                                    <label>1. Tell us a little bit about yourself ?</label>
                                                    <?php 
                                                        echo '<textarea class="form-control" rows="5" id="q1" name="q1" >'.$appendix->q1.'</textarea>';
                                                    ?>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div style="width: 100%;">
                                                    <label>2. Why do you want this job ?</label>
                                                    <?php 
                                                        echo '<textarea class="form-control" rows="5" id="q2" name="q2" >'.$appendix->q2.'</textarea>';
                                                    ?>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div style="width: 100%;">
                                                    <label>3. What are your greatest professional strengths which is NOT the standard answer found on any job portal, such as you are fast learner, hard worker, etc ?</label>
                                                    <?php 
                                                        echo '<textarea class="form-control" rows="5" id="q3" name="q3" >'.$appendix->q3.'</textarea>';
                                                    ?>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div style="width: 100%;">
                                                    <label>4. What do you consider to be your weaknesses ?</label>
                                                    <?php 
                                                        echo '<textarea class="form-control" rows="5" id="q4" name="q4" >'.$appendix->q4.'</textarea>';
                                                    ?>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div style="width: 100%;">
                                                    <label>5. What is your greatest professional or academic achievement ?</label>
                                                    <?php 
                                                        echo '<textarea class="form-control" rows="5" id="q5" name="q5" >'.$appendix->q5.'</textarea>';
                                                    ?>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div style="width: 100%;">
                                                    <label>6. Tell us about a challenge or conflict you faced, and how you dealt with it ?</label>
                                                    <?php 
                                                        echo '<textarea class="form-control" rows="5" id="q6" name="q6" >'.$appendix->q6.'</textarea>';
                                                    ?>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div style="width: 100%;">
                                                    <label>7. Where do you see yourself in five years ?</label>
                                                    <?php 
                                                        echo '<textarea class="form-control" rows="5" id="q7" name="q7" >'.$appendix->q7.'</textarea>';
                                                    ?>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div style="width: 100%;">
                                                    <label>8. Why are you leaving your current job ? (if applicable)</label>
                                                    <?php 
                                                        echo '<textarea class="form-control" rows="5" id="q8" name="q8" >'.$appendix->q8.'</textarea>';
                                                    ?>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div style="width: 100%;">
                                                    <label>9. What are you looking for in a new position ?</label>
                                                    <?php 
                                                        echo '<textarea class="form-control" rows="5" id="q9" name="q9" >'.$appendix->q9.'</textarea>';
                                                    ?>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div style="width: 100%;">
                                                    <label>10. How would your boss and co-workers describe you ? (if applicable)</label>
                                                    <?php 
                                                        echo '<textarea class="form-control" rows="5" id="q10" name="q10" >'.$appendix->q10.'</textarea>';
                                                    ?>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div style="width: 100%;">
                                                    <label>11. How do you deal with pressure or stressful situations ?</label>
                                                    <?php 
                                                        echo '<textarea class="form-control" rows="5" id="q11" name="q11" >'.$appendix->q11.'</textarea>';
                                                    ?>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div style="width: 100%;">
                                                    <label>12. How do you get to know us ?</label>
                                                    <?php 
                                                        echo '<textarea class="form-control" rows="5" id="q12" name="q12" >'.$appendix->q12 .'</textarea>';
                                                    ?>
                                                </div>
                                            </div>

                                            <hr>

                                            <div class="form-group">
                                                <div style="width: 100%;">
                                                    <div style="width: auto;float:left;margin-right: 20px;">
                                                        <label><b>Declaration : <b></label>
                                                    </div>
                                                    <div style="width: auto;float:left;margin-bottom:5px;">
                                                        <div class="input-group" style="width: 100%;display:flex;" >
                                                            <input type="checkbox" id="applicant_declaration_check" name="applicant_declaration_check" onclick="declaration_check()" style="margin-right: 5px"/>
                                                            <label>I verify that the above information is correct to the best of my knowledge. I accept that providing false information deliberately counld result in my dismissal.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                		                </div>
                		                <div class="col-md-12 text-right" style="margin-top: 10px;">
                                            <button id="submit_btn" class="btn btn_purple" type="submit" disabled>Submit</button>
                		                    <!-- <?php echo form_submit('save_applicant', 'Save', 'class="btn btn_purple"'); ?> -->
                                            <a href="applicant/" class="btn btn_cancel">Cancel</a>
                		                </div>

                                <!-- <?php echo form_close(); ?> -->
                                    </div>
                                </div>

                                </form>
                                <div class="col-md-12 text-right" style="margin-top: 10px;">
                                    <button type="button" class="btn btn_purple" onclick="generate_pdf();">Generate PDF</button>
                                </div>
                            </div>
                    </div>
<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>

<script type="text/javascript" charset="utf-8">
    var count_edu     = 0;
    var count_exp     = 0;
    var count_pro     = 0;
    var count_ref     = 0;
    var count_lang_tr = 0;
    var count_family = 0;

    var education    = <?php echo json_encode($education); ?>;
    var experience   = <?php echo json_encode($experience); ?>;
    var professional = <?php echo json_encode($professional); ?>;
    var referral     = <?php echo json_encode($referral); ?>;
    var family       = <?php echo json_encode($family); ?>;
    var language     = <?php echo json_encode($language); ?>;

    var delete_edu    = [];
    var delete_exp    = [];
    var delete_pro    = [];
    var delete_ref    = [];
    var delete_family = [];
    var delete_lang   = [];

    // console.log(language);

    if(<?php echo !empty($applicant->pic)?1:0 ?>){
        $('.showImage').show();
        $('.photo').hide();

        $('#editProfilePicBtn').hide(); // hide edit button.
        $('#removeProfilePicBtn').show(); // show remove button.
    }

    $.ajaxSetup({async: false}); 

    for(var index in language){
        add_language_tr(language[index]);
    }

    for(var index in education){
        add_education(education[index]);
    }

    for(var index in experience){
        add_experience(experience[index]);
    }

    for(var index in professional){
        add_professional(professional[index]);
    }

    for(var index in family){
        add_family(family[index]);
    }

    for(var index in referral){
        add_referral(referral[index]);
    }

    $.ajaxSetup({async: true}); 

	// $(".nationality-select").chosen({no_results_text: "Oops, nothing found!"});

    $('.datepicker').datepicker({format: 'dd MM yyyy',});

    $(document).ready( function (){ $(".select2").select2(); });

	function add_education(){
        // console.log('<?php echo base_url(); ?>' + "applicant/education_partial");

		$.post('applicant/education_partial', { 'count': count_edu }, function(data, status){
			$('#education_section').prepend(data);

            count_edu++;
	    });
	}

    function add_education(content){
        // console.log('<?php echo base_url(); ?>' + "applicant/education_partial");

        $.post('applicant/education_partial', { 'count': count_edu, 'content': content }, function(data, status){
            $('#education_section').prepend(data);

            count_edu++;
        });
    }

	function cancel_education(element, edu_id){
		var education_form = $(element).closest(".education_detail");

        if(edu_id != undefined){
            delete_edu.push(edu_id);
        }

		education_form.remove();
	}

	function add_experience(){

        // console.log(count_exp);

		$.post("applicant/experience_partial", { 'count': count_exp }, function(data, status){
			$('#experience_section').prepend(data);

            count_exp++;
	    });

	}

    function add_experience(content){

        // console.log(count_exp);

        $.post("applicant/experience_partial", { 'count': count_exp, 'content': content }, function(data, status){
            $('#experience_section').prepend(data);

            count_exp++;
        });

    }

    function cancel_experience(element, exp_id){
        var education_form = $(element).closest(".experience_detail");

        if(exp_id != undefined){
            delete_exp.push(exp_id);
        }

        education_form.remove();
    }

    function add_language_tr(){
        // console.log('count_lang_tr', count_lang_tr);
        $.post("applicant/add_language_tr_partial", { 'count': count_lang_tr }, function(data, status){
            $('#language_tr').prepend(data);

            count_lang_tr++;
        });
    }

    function add_language_tr(content){
        // console.log('count_lang_tr', count_lang_tr);
        $.post("applicant/add_language_tr_partial", { 'count': count_lang_tr, 'content': content }, function(data, status){
            $('#language_tr').prepend(data);

            count_lang_tr++;
        });
    }

    function cancel_lang(element, lang_id){
        var lang_tr = $(element).closest(".lang_tr");

        if(lang_id != undefined){
            delete_lang.push(lang_id);
        }

        lang_tr.remove();
    }

    function add_professional(){
        $.post("applicant/professional_partial", { 'count': count_pro }, function(data, status){
            $('#professional_section').prepend(data);

            count_pro++;
        });
    }

    function add_professional(content){
        $.post("applicant/professional_partial", { 'count': count_pro, 'content': content }, function(data, status){
            $('#professional_section').prepend(data);

            count_pro++;
        });
    }

    function cancel_professional(element, pro_id){
        var referral_form = $(element).closest(".professional_detail");

        if(pro_id != undefined){
            delete_pro.push(pro_id);
        }

        referral_form.remove();
    }

    function add_referral(){
        $.post("applicant/referral_partial", { 'count': count_ref }, function(data, status){
            $('#referral_section').prepend(data);

            count_ref++;
        });
    }

    function add_referral(content){
        $.post("applicant/referral_partial", { 'count': count_ref, 'content':content }, function(data, status){
            $('#referral_section').prepend(data);

            count_ref++;
        });
    }

    function cancel_referral(element, ref_id){
        var referral_form = $(element).closest(".referral_detail");

        if(ref_id != undefined){
            delete_ref.push(ref_id);
        }

        referral_form.remove();
    }

    function add_family(){
        // console.log('<?php echo base_url(); ?>' + "applicant/education_partial");

        $.post('applicant/family_partial', { 'count': count_family }, function(data, status){
            $('#family_section').prepend(data);

            count_family++;
        });
    }

    function add_family(content){
        // console.log('<?php echo base_url(); ?>' + "applicant/education_partial");

        $.post('applicant/family_partial', { 'count': count_family, 'content': content }, function(data, status){
            $('#family_section').prepend(data);

            count_family++;
        });
    }

    function cancel_family(element, famil_id){
        var famil_form = $(element).closest(".family_detail");

        if(famil_id != undefined){
            delete_family.push(famil_id);
        }

        famil_form.remove();
    }

    function declaration_check(){
        var checkBox = document.getElementById("applicant_declaration_check");

        if (checkBox.checked == true)
        {
            document.getElementById('submit_btn').removeAttribute('disabled');
        }
        else
        {
            document.getElementById('submit_btn').disabled = "true";
        }
    }

    $("#applicant_info").submit(function(e) {
        var form = $(this);

        if($('input[name="applicant_preview_pic"]').val() == '')
        {
            toastr.error("Please provide your photo.", "Error");
        }
        else
        {
            $.ajax({
               type: "POST",
               url: "applicant/save_applicant",
               data: form.serialize(), // serializes the form's elements.
               success: function(data)
               {
                    $.post("applicant/delete_data", { 'edu': delete_edu, 'exp': delete_exp, 'pro': delete_pro, 'ref': delete_ref, 'lang': delete_lang, 'family': delete_family }, function(data, status){});

                    $('#applicant_resume').fileinput('upload');

                    toastr.success('Information Updated', 'Updated');

                    setTimeout(function(){location.reload();}, 1000);
                    // setTimeout(function(){window.location = base_url + "applicant";}, 1000);

                    // generate_pdf(<?= $applicant_id ?>);
               }
            });
        }

        e.preventDefault(); // avoid to execute the actual submit of the form.
    });

    $('.input_num_val').mask('#,##0.00', {
        reverse: true,
        translation: {
            '#': {
                pattern: /-|\d/,
                recursive: true
            }
        },
        onChange: function(value, e) {
            var target = e.target,
                position = target.selectionStart; // Capture initial position

            target.value = value.replace(/(?!^)-/g, '').replace(/^,/, '').replace(/^-,/, '-');

            target.selectionEnd = position; // Set the cursor back to the initial position.
        }
    });

/* image settings in js/custom/applicant_profile_image.js */

/* This section contains file input plugin settings */
var initialPreviewArray       = []; 
var initialPreviewConfigArray = []; 
var base_url                  = '<?php echo base_url() ?>';
var files                     = '<?php echo isset($applicant->uploaded_resume)?$applicant->uploaded_resume:''; ?>';

// console.log(base_url + "uploads/applicant_resume/" + files);

if(files != '')
{
    files = JSON.parse(files);
    for($a = 0; $a < files.length; $a++)
    {
        var url = base_url + "uploads/applicant_resume/";
        // console.log(url + files);

        initialPreviewArray.push( url + files[$a] );
        initialPreviewConfigArray.push({
            type: "pdf",
            caption: files[$a],
            // url: base_url + "applicant/delete_resume/" + '<?php echo isset($applicant->id)?$applicant->id:''; ?>',
            url: base_url + "applicant/delete_resume/" + files[$a],
            // url: base_url + "applicant/delete_resume/?filename="+files[$a]+"&applicant_id=" + <?php echo isset($applicant->id)?$applicant->id:''; ?>,
            width: "120px",
            key: 0
        });
    }
}

$("#applicant_resume").fileinput({
    'async' : false,
    theme: 'fa',
    // uploadUrl: base_url + 'applicant/uploadFile/' + '<?php echo $applicant_id; ?>',
    uploadUrl: base_url + 'applicant/uploadFile',
    uploadAsync: false,
    browseClass: "btn btn_purple",
    fileType: "any",
    required: false,
    showCaption: false,
    showUpload: false,
    showRemove: false,
    showClose: false,
    fileActionSettings:{
        showRemove: true,
        showUpload: false,
        showZoom: true,
        showDrag: true,
    },
    previewFileIcon: "<i class='glyphicon glyphicon-king'></i>",
    overwriteInitial: false,
    initialPreviewAsData: true,
    initialPreviewDownloadUrl: base_url + 'uploads/applicant_resume/{filename}',
    initialPreview: initialPreviewArray,
    initialPreviewConfig: initialPreviewConfigArray,
    uploadExtraData: function() {
        return {
            applicant_id:'<?php echo $applicant_id; ?>'
        };
    }
})
.on('filebatchuploadsuccess', function(event, data, previewId, index){

    toastr.success('Documents Uploaded', 'Uploaded');
});

// function generate_pdf($applicant_id) {
function generate_pdf()
{
    $("#loadingmessage").show();

    $.ajax({
        type: "POST",
        url: "<?php echo site_url('applicant/application_form'); ?>",
        // data: {"applicant_id":$applicant_id},
        data: {"applicant_id":<?= $applicant_id ?>},
        dataType: "json",
        'async':false,
        success: function(response)
        {
            window.open(response.link,'_blank');
            filename = response.filename;

            $("#loadingmessage").hide();
        }               
    });
}

</script>
</body>

