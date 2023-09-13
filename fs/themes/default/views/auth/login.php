<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>
        <?php 
                
                if($this->session->flashdata('title') == "Register")
                { 
                    echo ("Create Account"); 
                }
                else if($this->session->flashdata('title') == "forgot_password")
                {
                    echo ("Forgot Password"); 
                }
                else
                { 
                    echo ($title); 
                }
        ?>     
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="<?= $assets ?>images/ACT_logo_dark_pink.ico"/>
   <!--  <link href="<?= $assets ?>styles/theme.css" rel="stylesheet"/>
    <link href="<?= $assets ?>styles/style.css" rel="stylesheet"/>
    <link href="<?= $assets ?>styles/helpers/login.css" rel="stylesheet"/> -->

    <!-- Vendor CSS -->
        <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.css" />
        <link rel="stylesheet" href="assets/vendor/font-awesome/css/all.css" />
        <link rel="stylesheet" href="assets/vendor/magnific-popup/magnific-popup.css" />
        <link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/css/datepicker3.css" />

    <!-- Theme CSS -->
        <link rel="stylesheet" href="assets/stylesheets/theme.css" />

    <!-- Skin CSS -->
        <link rel="stylesheet" href="assets/stylesheets/skins/default.css" />

        <!-- Theme Custom CSS -->
        <link rel="stylesheet" href="assets/stylesheets/theme-custom.css">

        <!-- Head Libs -->
        <script src="assets/vendor/modernizr/modernizr.js"></script>

    <script type="text/javascript" src="<?= $assets ?>js/jquery-2.0.3.min.js"></script>
    <script type="text/javascript" src="<?= $assets ?>js/bootstrapValidator.min.js"></script>

    <script type="text/javascript" src="<?= $assets ?>js/bootbox.min.js"></script>
    <!--[if lt IE 9]>
    <script src="<?= $assets ?>js/respond.min.js"></script>
    <![endif]-->

</head>

<body class="login-page" style="background: url('assets/uploads/login_background.jpg'); background-size: cover;
background-repeat: no-repeat;background-position: center;">
<noscript>
    <div class="global-site-notice noscript">
        <div class="notice-inner">
            <p><strong>JavaScript seems to be disabled in your browser.</strong><br>You must have JavaScript enabled in
                your browser to utilize the functionality of this website.</p>
        </div>
    </div>
</noscript>
<div class="page-back">
    
    <div id="login">
         <section class="body-sign">
             <div class="center-sign">
            
             <div class="panel panel-sign">
                   <!--  <div class="panel-title-sign mt-xl text-right">
                        <h2 class="title text-uppercase text-weight-bold m-none" style="font-size:14px; font-weight:normal;" ><i class="fa fa-user mr-xs"></i> Sign In</h2>
                    </div> -->
           
            <div class="panel-body" style="background-color: white; border-radius: 10px;" >
                <div class="text-center"><?php if ($Settings->logo2) {
                                echo '<img src="' . base_url('assets/uploads/logos/ACT_logo_dark_pink.png') . '" alt="' . $Settings->site_name . '" style="margin-bottom:10px;" height="130" />';
                            } ?></div>
            <!-- <div class="login-form-div"> -->
                <!-- <div class="login-content"> -->
                    <?php if ($Settings->mmode) { ?>
                        <div class="alert alert-warning">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <?= lang('site_is_offline') ?>
                        </div>
                    <?php }
                    if ($error) { ?>
                        <div class="alert alert-danger">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <ul class="list-group"><?= $error; ?></ul>
                        </div>
                    <?php }
                    if ($message) { ?>
                        <div class="alert alert-success">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <ul class="list-group"><?= $message; ?></ul>
                        </div>
                    <?php } ?>
                    <?php echo form_open("auth/login", 'class="login" id="login_form" data-toggle="validator"'); ?>
                    <div class="div-title">
                        <h3 class="text-primary"><?= lang('login_to_your_account') ?></h3><br>
                    </div>
                    <div class="form-group mb-lg">
                        <div class="input-group input-group-icon">
                            <input type="email" required="required" class="form-control input-lg" name="identity"
                                   placeholder="<?= lang('Email') ?>" style="font-size:18px;"  value="<?=$this->session->flashdata('regEmailAddress')?$this->session->flashdata('regEmailAddress'):'';?>"/>
                            <span class="input-group-addon">
                                <span class="icon icon-lg">
                                    <i class="fa fa-user"></i>
                                </span>
                            </span>
                        </div>
                        
                    </div>
                    <div class="form-group">
                        <div class="input-group input-group-icon">
                            <input type="password" required="required" class="form-control input-lg" name="password"
                                   placeholder="<?= lang('pw') ?>" style="font-size:18px;" />
                            <span class="input-group-addon">
                                <span class="icon icon-lg">
                                    <i class="fa fa-lock"></i>
                                </span>
                            </span>
                        </div>
                        
                    </div>
                    <!-- <?php if ($Settings->captcha) { ?>
                        <div class="textbox-wrap">

                            <div class="row">
                                <div class="col-sm-6 div-captcha-left">
                                    <span class="captcha-image"><?php echo $image; ?></span>
                                </div>
                                <div class="col-sm-6 div-captcha-right">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <a href="<?= base_url(); ?>auth/reload_captcha" class="reload-captcha">
                                                <i class="fa fa-refresh"></i>
                                            </a>
                                        </span>
                                        <?php echo form_input($captcha); ?>
                                    </div>
                                </div>
                            </div>

                        </div>
                    <?php }  echo $recaptcha_html;  ?> -->
                    
                    <div class="form-action clearfix" style="margin-bottom: 20px;margin-top: -20px !important;">
                        <!-- <div class="checkbox pull-left">
                            <div class="custom-checkbox">
                                <?php echo form_checkbox('remember', '1', FALSE, 'id="remember"'); ?>
                            </div>
                            <span class="checkbox-text pull-left"><label
                                    for="remember"><?= lang('remember_me') ?></label></span>
                        </div> --><br>

                        <button type="submit" class="btn btn-primary btn-lg pull-right"><?= lang('login') ?> &nbsp; 
                            <i class="fas fa-sign-in-alt"></i>
                        </button>
                    </div>
                    <?php echo form_close(); ?>
                    <?php if ($Settings->allow_reg) { ?>
                        <div class="login-form-links link1 pull-left">
                            <a href="<?= site_url('login/register'); ?>" class="register_link" target="_blank" style="color: #D9A200;">Create Account</a>
                            <!-- <h4 class="text-info"><?= lang('dont_have_account') ?></h4> -->
                            <!-- <span><?= lang('no_worry') ?></span> -->
                            <!-- <a href="#register" class="text-info register_link" target="_blank"><?= lang('click_here') ?></a> -->
                            <!-- <span>to Sign Up</span> -->
                            <!-- <span><?= lang('to_register') ?></span> -->
                        </div>
                    <?php } ?>

                    <div class="login-form-links link2 pull-right">
                        <a href="<?= site_url('login/forgot_password'); ?>" class="text-danger forgot_password_link" target="_blank">Forgot Password?</a>
                        <!-- <h4 class="text-danger"><?= lang('forgot_your_password') ?></h4>
                        <span><?= lang('dont_worry') ?></span>
                        <a href="#forgot_password" class="text-danger forgot_password_link" target="_blank"><?= lang('click_here') ?></a>
                        <span><?= lang('to_rest') ?></span> -->
                    </div>


                    
                    
                </div>
               
                
            <!-- </div> -->
            <!-- </div> -->
            </div>
        </div>
    </div>

    <div id="forgot_password" style="display: none;">
<!--         <div class=" container">

            <div class="login-form-div">
                <div class="login-content"> -->
                    <section class="body-sign">
                    <div class="center-sign">
            
             <div class="panel panel-sign">
           
            <div class="panel-body" style="background-color: white; border-radius: 10px;" >
                <div class="text-center"><?php if ($Settings->logo2) {
                                echo '<img src="' . base_url('assets/uploads/logos/secretary_logo.png') . '" alt="' . $Settings->site_name . '" style="margin-bottom:10px;" height="130" />';
                            } ?></div>
                    <?php if ($error) { ?>
                        <div class="alert alert-danger">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <ul class="list-group"><?= $error; ?></ul>
                        </div>
                    <?php }
                    if ($message) { ?>
                        <div class="alert alert-success">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <ul class="list-group"><?= $message; ?></ul>
                        </div>
                    <?php } ?>
                    <div class="div-title">
                        <h3 class="text-primary"><?= lang('forgot_password') ?></h3><br>
                    </div>
                    <?php echo form_open("auth/forgot_password", 'class="login" data-toggle="validator"'); ?>
                    <div class="form-group mb-lg">
                        <div class="input-group input-group-icon">
                            <input type="email" required="required" class="form-control input-lg" name="forgot_email"
                                   placeholder="<?= lang('email_address') ?>" style="font-size:18px;" required="required"/>
                            <span class="input-group-addon">
                                <span class="icon icon-lg">
                                    <i class="fa fa-envelope"></i>
                                </span>
                            </span>
                        </div>
                        
                    </div>

                    <!-- <div class="form-group" style="margin-bottom: 50px">
                        <div class="textbox-wrap">
                            <div class="input-group">
                                <span class="input-group-addon "><i class="fa fa-envelope"></i></span>
                                <input type="email" name="forgot_email" class="form-control input-lg"
                                       placeholder="<?= lang('email_address') ?>" required="required"/>
                            </div>
                        </div>
                    </div> -->
                    <div class="form-action clearfix">
                        <a class="btn btn-lg btn-primary pull-left login_link" href="#login"><i
                                class="fa fa-chevron-left"></i> <?= lang('back') ?>  </a>
                        <button type="submit" class="btn btn-primary btn-lg pull-right"><?= lang('submit') ?> &nbsp;&nbsp; 
                            <i class="fa fa-envelope"></i>
                        </button>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>


        </div>
    </section>
    </div>
    <?php if ($Settings->allow_reg) { ?>
        <div id="register">
            <section class="body-sign">
                    <div class="center-sign">
            
             <div class="panel panel-sign">
           
            <div class="panel-body" style="background-color: white; border-radius: 10px;" >
                <div class="text-center"><?php if ($Settings->logo2) {
                                echo '<img src="' . base_url('assets/uploads/logos/secretary_logo.png') . '" alt="' . $Settings->site_name . '" style="margin-bottom:10px;" height="130" />';
                            } ?></div>
                    <?php if ($this->mmode) { ?>
                        <div class="alert alert-warning">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <?= lang('site_is_offline') ?>
                        </div>
                    <? }
                    if ($message) { ?>
                        <div class="alert alert-success">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <?= $message; ?>
                        </div>
                    <?php } ?>
                    <?php
                    if ($error) { ?>
                        <div class="alert alert-danger">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <?= $error; ?>
                        </div>
                    <?php } ?>
                        <div class="div-title reg-header">
                            <h3 class="text-primary"><?= lang('register_account_heading') ?></h3>

                        </div>
                        <?php echo form_open("auth/register", 'class="login" id="register_user_form" '); ?>
                        <fieldset class="col-sm-12"  style="margin-bottom: 20px;margin-top: 20px;">

                        <div class="form-group row">
                            <!-- <?php echo lang('first_name'); ?> -->
                            <!-- <?php echo( $this->session->userdata('regFirstName')) ?> -->
                            <div class="controls">
                                <div class="input-group col-sm-12">
                                    <!-- <?php echo form_input($first_name, "First Name"); ?> -->

                                    <!-- <span class="input-group-addon"><i class="fa fa-angle-right"></i></span> -->
                                    <input style="border-bottom-left-radius: 4px;border-top-left-radius: 4px;" type="text" name="first_name" class="form-control input-lg"
                                       placeholder="<?= lang('first_name') ?>" value="<?=$this->session->flashdata('regFirstName')?$this->session->flashdata('regFirstName'):'';?>"/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <!-- <?php echo lang('last_name'); ?> -->
                            <div class="controls">
                                <div class="input-group col-sm-12">
                                    <!-- <?php echo form_input($last_name); ?> -->
                                    <!-- <span class="input-group-addon"><i class="fa fa-angle-right"></i></span> -->
                                    <input style="border-bottom-left-radius: 4px;border-top-left-radius: 4px;" type="text" name="last_name" class="form-control input-lg"
                                       placeholder="<?= lang('last_name') ?>" value="<?=$this->session->flashdata('regLastName')?$this->session->flashdata('regLastName'):'';?>"/>
                                </div>
                            </div>
                        </div>

                        <!-- <div class="form-group row">
                            <?php echo lang('company'); ?>
                            <div class="controls">
                                <div class="input-group col-sm-12">
                                    
                                    <span class="input-group-addon"><i class="fa fa-angle-right"></i></span>
                                    <input type="text" name="company" class="form-control "
                                       placeholder="<?= lang('company') ?>" value="<?=$this->session->flashdata('regCompany')?$this->session->flashdata('regCompany'):'';?>"/>
                                </div>
                            </div>
                        </div> -->

                        <!-- <div class="form-group row">
                            <?php echo lang('phone'); ?>
                            <div class="controls">
                                <div class="input-group col-sm-12">
                                    
                                    <span class="input-group-addon"><i class="fa fa-angle-right"></i></span>
                                    <input type="text" name="phone" class="form-control "
                                       placeholder="<?= lang('phone') ?>" value="<?=$this->session->flashdata('regPhone')?$this->session->flashdata('regPhone'):'';?>"/>
                                </div>
                            </div>
                        </div> -->
                        <div class="form-group row">
                            <!-- <?php echo lang('email'); ?> -->
                            <div class="controls">
                                <div class="input-group col-sm-12">
                                    <!-- <?php echo form_input($email); ?> -->
                                    <!-- <span class="input-group-addon"><i class="fa fa-angle-right"></i></span> -->
                                    <select id="user_type" class="form-control input-lg user_type" style="border-bottom-left-radius: 4px;border-top-left-radius: 4px;" name="user_type">
                                        <option value="">Select User Type</option>
                                    </select>
                                    <!-- <input style="border-bottom-left-radius: 4px;border-top-left-radius: 4px;" type="email" name="email" class="form-control input-lg"
                                       placeholder="<?= lang('email_address') ?>" value="<?=$this->session->flashdata('regEmail')?$this->session->flashdata('regEmail'):'';?>"/> -->
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <!-- <?php echo lang('email'); ?> -->
                            <div class="controls">
                                <div class="input-group col-sm-12">
                                    <!-- <?php echo form_input($email); ?> -->
                                    <!-- <span class="input-group-addon"><i class="fa fa-angle-right"></i></span> -->
                                    <input style="border-bottom-left-radius: 4px;border-top-left-radius: 4px;" type="email" name="email" class="form-control input-lg"
                                       placeholder="<?= lang('email_address') ?>" value="<?=$this->session->flashdata('regEmail')?$this->session->flashdata('regEmail'):'';?>"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <!-- <?php echo lang('password'); ?> -->
                            <div class="controls">
                                <div class="input-group col-sm-12">
                                    <!-- <?php echo form_input($password); ?> -->
                                    <!-- <span class="input-group-addon"><i class="fa fa-angle-right"></i></span> -->
                                    <input style="border-bottom-left-radius: 4px;border-top-left-radius: 4px;" type="password" name="password" class="form-control input-lg"
                                       placeholder="<?= lang('password') ?>" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"/>
                                </div>
                            </div>
                            <span style="color:#D9A200;display: block;font-size: 12px;margin-top: 5px;"><?= lang('pasword_hint') ?></span>
                        </div>

                        <div class="form-group row">
                            <!-- <?php echo lang('confirm_password'); ?> -->
                            <div class="controls">
                                <div class="input-group col-sm-12">
                                    <!-- <?php echo form_input($password_confirm); ?> -->
                                    <!-- <span class="input-group-addon"><i class="fa fa-angle-right"></i></span> -->
                                    <input style="border-bottom-left-radius: 4px;border-top-left-radius: 4px;" type="password" name="password_confirm" class="form-control input-lg" placeholder="<?= lang('confirm_password') ?>" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" data-bv-identical="true" data-bv-identical-field="password" data-bv-identical-message="<?= lang('pw_not_same') ?>"/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="controls">
                                <div class="input-group col-sm-12">
                                    <input type="checkbox" name="terms" id="" onchange="activateButton(this)" value = "1">  I/We have read and agreed with the <a href="<?= base_url();?>term_and_condition" target="_blank">Terms & Conditions</a>
                                </div>
                            </div>
                        </div>

                        

                        

                    </fieldset>
                    <div class="form-action clearfix">
                        <a class="btn btn-lg btn-primary pull-left login_link" href="#login"><i
                                class="fa fa-chevron-left"></i> <?= lang('back') ?>  </a>
                        <button type="submit" class="btn btn-primary btn-lg pull-right" id="regsiter_submit"><?= lang('register') ?> &nbsp;&nbsp; 
                            <i class="fa fa-user"></i>
                        </button>
                    </div>

                    
                    <!-- <div class="registration-form-action clearfix">
                        <a href="#login" class="btn btn-success pull-left login_link">
                            <i class="fa fa-chevron-left"></i> <?= lang('back') ?>
                        </a>
                        <button type="submit" class="btn btn-primary pull-right"><?= lang('register') ?> <i
                                class="fa fa-user"></i></button>

                    </div> -->
                    <?php echo form_close(); ?>
                </div>
            </div>
            </div>
        </section>
        </div>
    <?php } ?>

     <?php if ($Settings->allow_reg) { ?>
        <div id="after_register" style="display: none;">
            <section class="body-sign">
                    <div class="center-sign">
            
             <div class="panel panel-sign">
           
            <div class="panel-body" style="background-color: white; border-radius: 10px;" >
                <div class="text-center"><?php if ($Settings->logo2) {
                                echo '<img src="' . base_url('assets/uploads/logos/secretary_logo.png') . '" alt="' . $Settings->site_name . '" style="margin-bottom:10px;" height="130" />';
                            } ?></div>
                    <?php if ($Settings->mmode) { ?>
                        <div class="alert alert-warning">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <?= lang('site_is_offline') ?>
                        </div>
                    <?php }
                    if ($error) { ?>
                        <div class="alert alert-danger">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <ul class="list-group"><?= $error; ?></ul>
                        </div>
                    <?php }
                    if ($message) { ?>
                        <div class="alert alert-success">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <ul class="list-group"><?= $message; ?></ul>
                        </div>
                    <?php } ?>
                    <div class="form-action clearfix" style="margin-bottom: 20px;margin-top: 20px !important;">
                        <a href="<?= base_url();?>" class="btn btn-primary pull-right">OK</a>
                        
                    </div>
                </div>
            </div>
            </div>
        </section>
        </div>
    <?php } ?>

    <div id="after_forgot_password" style="display: none;">
<!--         <div class=" container">

            <div class="login-form-div">
                <div class="login-content"> -->
                    <section class="body-sign">
                    <div class="center-sign">
            
             <div class="panel panel-sign">
           
            <div class="panel-body" style="background-color: white; border-radius: 10px;" >
                <div class="text-center"><?php if ($Settings->logo2) {
                                echo '<img src="' . base_url('assets/uploads/logos/secretary_logo.png') . '" alt="' . $Settings->site_name . '" style="margin-bottom:10px;" height="130" />';
                            } ?></div>
                    <?php if ($error) { ?>
                        <div class="alert alert-danger">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <ul class="list-group"><?= $error; ?></ul>
                        </div>
                    <?php }
                    if ($message) { ?>
                        <div class="alert alert-success">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <ul class="list-group"><?= $message; ?></ul>
                        </div>
                    <?php } ?>
                    <div class="form-action clearfix" style="margin-bottom: 20px;margin-top: 20px !important;">
                        <a href="<?= base_url();?>" class="btn btn-primary pull-right">OK</a>
                        <!-- <button type="button" class="btn btn-danger btn-lg pull-right">OK &nbsp; 
                            <i class="fa fa-sign-in"></i>
                        </button> -->
                    </div>
                </div>
            </div>


        </div>
    </section>
    </div>
</div>
<!-- 
<script src="<?= $assets ?>js/jquery.js"></script> -->
<!-- <script src="<?= $assets ?>js/bootstrap.min.js"></script> -->
<script src="<?= $assets ?>js/jquery.cookie.js"></script>
<script src="<?= $assets ?>js/login.js"></script> 
<script type="text/javascript">
    $(document).ready(function () {
        var hash = window.location.hash;
        if (hash && hash != '') {
            $("#login").hide();
            $(hash).show();
        }
    });
</script>
    <script type="text/javascript">

        disableSubmit();

        function disableSubmit() {
          document.getElementById("regsiter_submit").disabled = true;
        }

        function activateButton(element) {
            console.log(element.checked);
          if(element.checked) {
            document.getElementById("regsiter_submit").disabled = false;
           }
           else  {
            document.getElementById("regsiter_submit").disabled = true;
          }

        }
        $(document).ready(function () {
            $dataProduk = <?php echo json_encode($produk);
            // foreach ($produk as $a)
            // {
                // print_r($a);
                // echo "dataProduk[".$a->id."][0] = '".$a->code."';";
                // echo "dataProduk[".$a->id."][1] = '".$a->name."';";
            // };
            ?>;
            localStorage.setItem('produk',$dataProduk);
            $dataMy = localStorage.getItem('produk');
                $dataStefSource = (JSON.parse($dataMy));
                //alert($dataStefSource.length);
            // $("#login").html($dataStefSource);
            if (parent.frames.length !== 0) {
                top.location = '<?=site_url('pos')?>';
            }
        });

        $(document).ready(function () {
        $('#login_form').bootstrapValidator({
            submitButtons: 'button[type="submit"]',
            fields: {
                identity: {
                      validators: {
                        notEmpty: {
                          message: 'The email is required'
                        }
                  }
                }
                
                
            }
        });

        $.ajax({
            type: "GET",
            url: "auth/get_user_type",
            dataType: "json",
            async: false,
            success: function(data){
                if(data.tp == 1){
                    //$("#user_type option").remove();
                    $.each(data['result'], function(key, val) {
                        var option = $('<option />');
                        option.attr('value', key).text(val);
                        $("#user_type").append(option);
                    });
                    
                }
                else{
                    alert(data.msg);
                }

                
            }               
        });
        /*$('input').on('blur keyup', function() {
            if ($("#myform").valid()) {
                $('#submit').prop('disabled', false);  
            } else {
                $('#submit').prop('disabled', 'disabled');
            }
        });*/

        $('#register_user_form').bootstrapValidator({
                submitButtons: 'button[type="submit"]',
                fields: {
                    first_name: {
                        validators: {
                            notEmpty: {
                                message: 'The First Name is required'
                            }
                        }
                    },
                    last_name: {
                        validators: {
                            notEmpty: {
                                message: 'The Last Name is required'
                            }
                        }
                    },
                    user_type: {
                        validators: {
                            notEmpty: {
                                message: 'The User Type is required'
                            }
                        }
                    },
                    email: {
                          validators: {
                            notEmpty: {
                              message: 'The Email is required'
                            }/*,
                            regexp: {
                              regexp: '^[^@\\s]+@([^@\\s]+\\.)+[^@\\s]+$',
                              message: 'The value is not a valid email'
                            }*/
                      }
                    },
                    password: {
                        validators: {
                            notEmpty: {
                              message: 'The Password is required'
                            },
                            identical: {
                                field: 'password_confirm',
                                message: 'The password and its confirm are not the same'
                            }
                        }
                    },
                    password_confirm: {
                        validators: {
                            notEmpty: {
                              message: 'The Confirm Password is required'
                            },
                            identical: {
                                field: 'password',
                                message: 'The password and its confirm are not the same'
                            }
                        }
                    },
                    terms: {
                          validators: {
                            notEmpty: {
                              message: 'The Term & Condition is required'
                            }/*,
                            regexp: {
                              regexp: '^[^@\\s]+@([^@\\s]+\\.)+[^@\\s]+$',
                              message: 'The value is not a valid email'
                            }*/
                      }
                    }
                }
            });

            var work_pass_expire_flag = <?php echo json_encode(isset($work_pass_expire_flag)?$work_pass_expire_flag:"") ?>;

            if(work_pass_expire_flag)
            {
                bootbox.confirm({
                    message: "<strong>Please Update Your Work Pass Expiry Date</strong>",
                    closeButton: false,
                    buttons: {
                        confirm: {
                        label: 'Proceed to HRM',
                        className: 'btn_blue'
                    },
                    cancel: {
                        label: 'No',
                        className: 'hidden'
                    }
                },
                callback: function (result){
                    if(result == true)
                    {
                        window.location.replace("https://bizfiles.com.sg/hrm/auth/login");
                    }
                }
                });
            }

            $( '#register_user_form' ).on( 'status.field.bv', function( e, data ) {
                let $this = $( this );
                let formIsValid = true;

                $( '.form-group', $this ).each( function() {
                    formIsValid = formIsValid && $( this ).hasClass( 'has-success' );
                });

                $( '#regsiter_submit', $this ).attr( 'disabled', !formIsValid );
            });
        });
    </script>

    <!-- Vendor -->
        <!-- <script src="assets/vendor/jquery/jquery.js"></script> -->
        <script src="assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
        <script src="assets/vendor/bootstrap/js/bootstrap.js"></script>
        <script src="assets/vendor/nanoscroller/nanoscroller.js"></script>
        <script src="assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
        <script src="assets/vendor/magnific-popup/magnific-popup.js"></script>
        <script src="assets/vendor/jquery-placeholder/jquery.placeholder.js"></script>

    <!-- Theme Custom -->
        <script src="assets/javascripts/theme.custom.js"></script>

    <!-- Theme Base, Components and Settings -->
        <script src="assets/javascripts/theme.js"></script>

    <!-- Theme Initialization Files -->
        <script src="assets/javascripts/theme.init.js"></script>    

</body>
</html>
