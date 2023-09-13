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

    <!---- Content Security Policy (CSP) header --->
    <meta http-equiv="Content-Security-Policy" content="default-src *; style-src 'self' http://* https://*.singpass.gov.sg 'unsafe-inline'; script-src 'self' http://* https://*.singpass.gov.sg 'unsafe-inline' 'unsafe-eval'; connect-src 'self' https://*.singpass.gov.sg; img-src 'self' https://*.singpass.gov.sg data:; font-src 'self' https://fonts.gstatic.com;" />

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="<?= $assets ?>images/secretary_logo_icon.ico"/>

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
    <script src="assets/vendor/bootstrap/js/bootstrap.js"></script>
    <!-- <script src="<?= $assets ?>js/bootbox.min.js"></script> -->
    <script type="text/javascript" src="<?= $assets ?>js/bootbox.min.js"></script>
    <!-- <script src="<?= $assets ?>js/bootbox.all.js"></script> -->
    <!-- singpass -->
    <script type="text/javascript" src="https://id.singpass.gov.sg/static/ndi_embedded_auth.js"></script>
</head>
<body class="login-page" style="background: url('assets/uploads/login_background.jpg'); background-size: cover;
background-repeat: no-repeat;background-position: center;" onload="init()">
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
                    <ul class="nav nav-tabs nav-justify login_nav" id="myTab">
                        <li class="active check_stat" id="li-passwordLogin" data-information="passwordLogin">
                            <a href="#nav-passwordLogin" data-toggle="tab" class="text-center header_login">
                                Password Login
                            </a>
                        </li>
                        <li class="check_stat" id="li-singpassLogin" data-information="singpassLogin" >
                            <a href="#nav-singpassLogin" data-toggle="tab" class="text-center header_login">
                                Singpass Login
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content login-tab-content">
                        <div id="nav-passwordLogin" class="tab-pane active">
                            <div class="text-center">
                                <?php if ($Settings->logo2) {
                                    echo '<img src="' . base_url('assets/uploads/logos/secretary_logo.png') . '" alt="' . $Settings->site_name . '" style="margin-bottom:10px;" height="130" />';
                                } ?> 
                            </div>
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
                                    <!-- <a href="<?= site_url('login/register'); ?>" class="register_link" target="_blank" style="color: #D9A200;">Create Account</a> -->

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
                        <div id="nav-singpassLogin" class="tab-pane">
                            <div class="success-sp">
                                <h3>Scan the QR code with your Singpass mobile app to login</h3>
                                <!-- element to contain the all SPCPQR Elements -->
                                <!-- <div id="qr_wrapper"></div> -->
                                <div id="ndi-qr"></div>
                                <div class="register-mobile">
                                    <p class="dont_have_singapss">Don't have Singpass Mobile? Download from</p>
                                        <a target="_blank" class="app-store singpass-anchor" href="https://itunes.apple.com/us/app/singpass-mobile/id1340660807">App Store </a> or <a target="_blank" class="app-store singpass-anchor" href="https://play.google.com/store/apps/details?id=sg.ndi.sp&amp;hl=en-GB"> Google Play Store</a>
                                </div>
                                <div class="error-SL-description sp-error-descrp" style="display: none;">Sorry, you are not registered with us.</div>
                                <div class="error-SL-description sp-error-descrp" style="display: none;">You may try using your Email instead or contact us for further assistence.</div>
                                <a href="javascript:void(0)" onclick="funcWhyCantLogin()" class="singpass-anchor sp-error-descrp" style="display: none;">Why can't I login with Singpass?</a>
                            </div>
                            <!-- <div class="error-sp toggle-error" style="display: none;">
                                <div class="singPassWhiteOverlay" style="height: 100%; width: 100%; position: fixed; left: 0px; top: 0px; z-index: 9998; opacity: 0.6; background-color: rgb(0, 0, 0); display: none;">
                                </div>
                                <div class="error-SL-img">
                                    <img src="/internet-banking/Content/themes/common/images/icon.svg" alt="" width="34" height="34">
                                </div>
                                <div class="error-SL-description">Sorry, we are unable to log you in right now.</div>
                                <div class="error-SL-subdescription">You may try using your Email instead.</div>
                                <a onclick="javascript:funcWhyCantLogin();">Why can't I login with Singpass?</a>
                            </div> -->
                            <?php if ($Settings->allow_reg) { ?>
                                <div class="login-form-links link1 pull-left">
                                    <!-- <a href="<?= site_url('login/register'); ?>" class="register_link singpass-anchor" target="_blank" style="color: #D9A200;">Create Account</a> -->
                                </div>
                            <?php } ?>
                            <div class="login-form-links link2 pull-right">
                                <?php if ($Settings->logo2) {
                                    echo '<img src="' . base_url('assets/uploads/logos/secretary_logo.png') . '" alt="' . $Settings->site_name . '" style="margin-bottom:10px;" height="40" />';
                                } ?> 
                            </div>
                        </div>

                        
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div id="forgot_password" style="display: none;">
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
                                <?php if ($Settings->mmode) { ?>
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
                                        <div class="controls">
                                            <div class="input-group col-sm-12">
                                                <input style="border-bottom-left-radius: 4px;border-top-left-radius: 4px;" type="text" name="first_name" class="form-control input-lg"
                                                   placeholder="<?= lang('first_name') ?>" value="<?=$this->session->flashdata('regFirstName')?$this->session->flashdata('regFirstName'):'';?>"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="controls">
                                            <div class="input-group col-sm-12">
                                                <input style="border-bottom-left-radius: 4px;border-top-left-radius: 4px;" type="text" name="last_name" class="form-control input-lg"
                                                   placeholder="<?= lang('last_name') ?>" value="<?=$this->session->flashdata('regLastName')?$this->session->flashdata('regLastName'):'';?>"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="controls">
                                            <div class="input-group col-sm-12">
                                                <select id="user_type" class="form-control input-lg user_type" style="border-bottom-left-radius: 4px;border-top-left-radius: 4px;" name="user_type">
                                                    <option value="">Select User Type</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="controls">
                                            <div class="input-group col-sm-12">
                                                <input style="border-bottom-left-radius: 4px;border-top-left-radius: 4px;" type="email" name="email" class="form-control input-lg"
                                                   placeholder="<?= lang('email_address') ?>" value="<?=$this->session->flashdata('regEmail')?$this->session->flashdata('regEmail'):'';?>"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="controls">
                                            <div class="input-group col-sm-12">
                                                <input style="border-bottom-left-radius: 4px;border-top-left-radius: 4px;" type="password" name="password" class="form-control input-lg"
                                                   placeholder="<?= lang('password') ?>" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"/>
                                            </div>
                                        </div>
                                        <span style="color:#D9A200;display: block;font-size: 12px;margin-top: 5px;"><?= lang('pasword_hint') ?></span>
                                    </div>
                                    <div class="form-group row">
                                        <div class="controls">
                                            <div class="input-group col-sm-12">
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
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<div id="modal_why_cannot_login_sp" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <header class="panel-heading">
                <span class="panel-title">Why can't I login with Singpass?</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </header>
            <div class="panel-body">
                <div class="panel-group" id="accordion">
                  <div class="panel panel-default">
                    <div class="panel-heading">
                      <h6 class="panel-title sp-question-collapse">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse1" class="singpass-anchor">
                        You do not have an account or product with ACT Secretary.</a>
                      </h6>
                    </div>
                    <div id="collapse1" class="panel-collapse collapse"> <!-- in -->
                      <div class="panel-body">You may instantly apply for an account <a href="<?= site_url('login/register'); ?>" class="register_link singpass-anchor" target="_blank" style="color: #D9A200;">here</a>.</div>
                    </div>
                  </div>
                  <div class="panel panel-default">
                    <div class="panel-heading">
                      <h6 class="panel-title sp-question-collapse">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse2" class="singpass-anchor">
                        You do not have a NRIC or FIN record with ACT Secretary.</a>
                      </h6>
                    </div>
                    <div id="collapse2" class="panel-collapse collapse">
                      <div class="panel-body">You can only use Singpass login if we have a record of your NRIC or FIN. Alternatively, you may continue logging in using your Email instead of Singpass.</div>
                    </div>
                  </div>
                  <div class="panel panel-default">
                    <div class="panel-heading">
                      <h4 class="panel-title sp-question-collapse">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse3" class="singpass-anchor">
                        Need more assistance? You may call our hotline.</a>
                      </h4>
                    </div>
                    <div id="collapse3" class="panel-collapse collapse">
                      <div class="panel-body">Email: enquiry@aaa-global.com</div>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= $assets ?>js/jquery.cookie.js"></script>
<script src="<?= $assets ?>js/login.js"></script> 
<script src="<?= $assets ?>js/randexp.js-0.5.3/build/randexp.min.js"></script> 
<script type="text/javascript">
    $(document).ready(function () {
        var hash = window.location.hash;
        if (hash && hash != '') {
            $("#login").hide();
            $(hash).show();
        }
    });

    //------------------------------------singpass--------------------------------------
    const regular_exp = new RandExp(/[A-Za-z0-9/+_\-=.]+/).gen();//Max 100 char: [A-Za-z0-9/+_\-=.]{100}$ //To test (https://regex101.com/)
    const genRanHex = size => [...Array(size)].map(() => Math.floor(Math.random() * 16).toString(16)).join('');
    const hex_encoded_rd_number = genRanHex(12);

    async function init() {
        const authParamsSupplier = async () => {
          // Replace the below with an `await`ed call to initiate an auth session on your backend
          // which will generate state+nonce values, e.g
          // state parameter so as to mitigate replay attacks against the RP’s redirection endpoint (redirectUri)
          // nonce parameter so as to mitigate MITM replay attacks against the ASP Service’s Token Endpoint and its resulting ID Token
          return { state: regular_exp, nonce: hex_encoded_rd_number };
        };

        const onError = (errorId, message) => {
          console.log(`onError. errorId:${errorId} message:${message}`);
          if(message == "auth_session_expired")
          {
            init();
          }
        };

        const initAuthSessionResponse = window.NDI.initAuthSession(
            'ndi-qr',
            {
                clientId: 'pjTydwFIkNTkEQntyHYwBIcxinFJwF75', // Replace with your client ID
                redirectUri: 'https://bizfiles.com.sg/secretary/welcome',        // Replace with a registered redirect URI
                scope: 'openid',
                responseType: 'code'
            },
            authParamsSupplier,
            onError
        );

        console.log('initAuthSession: ', initAuthSessionResponse);
    }

    let singpass_error_title = <?php echo json_encode($this->session->flashdata('error_title')); ?>;

    if(singpass_error_title == "singpass_issue")
    {
        $("#nav-passwordLogin").removeClass("active"); // this deactivates the tab
        $("#nav-singpassLogin").addClass("active");    // this activates the tab
        $("#li-passwordLogin").removeClass("active"); // this deactivates the tab
        $("#li-singpassLogin").addClass("active");    // this activates the tab

        $(".sp-error-descrp").show();
    }
    else
    {
        $("#nav-passwordLogin").addClass("active"); 
        $("#nav-singpassLogin").removeClass("active");  
        $("#li-passwordLogin").addClass("active"); 
        $("#li-singpassLogin").removeClass("active");  

        $(".sp-error-descrp").hide();
    }

    function funcWhyCantLogin()
    {
        $("#modal_why_cannot_login_sp").modal("show");
    }
    //--------------------------------------------------------------------------------
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

        $( '#register_user_form' ).on( 'status.field.bv', function( e, data ) {
            let $this = $( this );
            let formIsValid = true;

            $( '.form-group', $this ).each( function() {
                formIsValid = formIsValid && $( this ).hasClass( 'has-success' );
            });

            $( '#regsiter_submit', $this ).attr( 'disabled', !formIsValid );
        });
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
                    className: 'btn-primary'
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
</script>
<!-- Vendor -->
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
