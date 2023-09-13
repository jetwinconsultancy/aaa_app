<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?= $title ?></title>
    <script type="text/javascript">if (parent.frames.length !== 0) {
            top.location = '<?=site_url('pos')?>';
        }</script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="<?= $assets ?>images/ACT_logo_dark_pink.ico"/>

    <!-- Vendor CSS -->
        <link rel="stylesheet" href="<?=site_url('assets/vendor/bootstrap/css/bootstrap.css')?>" />
        <link rel="stylesheet" href="<?=site_url('assets/vendor/font-awesome/css/font-awesome.css')?>" />
        <link rel="stylesheet" href="<?=site_url('assets/vendor/magnific-popup/magnific-popup.css')?>" />
        <link rel="stylesheet" href="<?=site_url('assets/vendor/bootstrap-datepicker/css/datepicker3.css')?>" />

    <!-- Theme CSS -->
        <link rel="stylesheet" href="<?=site_url('assets/stylesheets/theme.css')?>" />

    <!-- Skin CSS -->
        <link rel="stylesheet" href="<?=site_url('assets/stylesheets/skins/default.css')?>" />

    <!-- Theme Custom CSS -->
        <link rel="stylesheet" href="<?=site_url('assets/stylesheets/theme-custom.css')?>">

        <!-- Head Libs -->
        <script src="<?=site_url('assets/vendor/modernizr/modernizr.js')?>"></script>


        <script type="text/javascript" src="<?= $assets ?>js/jquery-2.0.3.min.js"></script>
        <script type="text/javascript" src="<?= $assets ?>js/bootstrapValidator.min.js"></script>

</head>

<body class="login-page" style="background: url('<?=site_url('assets/uploads/login_background.jpg')?>'); background-size: cover;
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
<section class="body-sign">
                    <div class="center-sign">
            
             <div class="panel panel-sign">
           
            <div class="panel-body" style="background-color: white; border-radius: 10px;" >
    <!-- <div class="text-center"><?php if ($Settings->logo2) {
            echo '<img src="' . base_url('assets/uploads/logos/' . $Settings->logo2) . '" alt="' . $Settings->site_name . '" style="margin-bottom:10px;" />';
        } ?></div> -->
        <div class="text-center"><?php if ($Settings->logo2) {
                                echo '<img src="' . base_url('assets/uploads/logos/secretary_logo.png') . '" alt="' . $Settings->site_name . '" style="margin-bottom:10px;" height="130" />';
                            } ?></div>
    <!-- <div id="login"> -->

        <!-- <div class=" container"> -->

            <!-- <div class="login-form-div">
                <div class="login-content"> -->
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

                    <?php $attrib = array('id' => 'reset_password_form','class' => 'form-horizontal login', 'data-toggle' => 'validator', 'role' => 'form');
                        echo form_open('auth/reset_password/' . $code, $attrib);
                        ?>

                    <div class="div-title">
                        <h3 class="text-primary"><?php echo (lang('reset_password_email')); ?></h3>
                        <h4 style="color:#c19000"><?php echo ($identity_label); ?></h4><br>
                    </div>

                    <div class="form-group">
                        <div class="input-group input-group-icon">
                            <!-- <input type="password" required="required" class="form-control input-lg" id="new" name="new"
                                   placeholder="New Password" style="font-size:18px;" pattern="^.{8}.*$" value=""/> -->
                            <?php echo form_password('new_password', 'New Password', '', 'class="form-control input-lg" id="new_password" required="required" style="font-size:18px;" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"'); ?>
                            <span class="input-group-addon">
                                <span class="icon icon-lg">
                                    <i class="fa fa-lock"></i>
                                </span>
                            </span>
                        </div>
                        <span style="color:#D9A200;display: block;font-size: 12px;margin-top: 5px;"><?= lang('pasword_hint') ?></span>
                    </div>

                    <div class="form-group mb-lg">
                        <div class="input-group input-group-icon">
                            <!-- <input type="password" required="required" class="form-control input-lg" id="new_confirm" name="new_confirm"
                                   placeholder="Comfirm New Password" style="font-size:18px;" pattern="^.{8}.*$" onChange="checkPasswordMatch();" value=""/> -->
                            <?php echo form_password('new_confirm', 'Confirm New Password', '', 'class="form-control input-lg" id="new_password_confirm" style="font-size:18px;" required="required" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" data-bv-identical="true" data-bv-identical-field="new_password" data-bv-identical-message="' . lang('pw_not_same') . '"'); ?>
                            <span class="input-group-addon">
                                <span class="icon icon-lg">
                                    <i class="fa fa-lock"></i>
                                </span>
                            </span>
                        </div>
                        <span class="help-block registrationFormAlert" id="divCheckPasswordMatch"></span>
                    </div>

                    <!-- <div class="textbox-wrap">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-key"></i></span>
                            <?php echo form_input($new_password); ?>
                        </div>
                    </div>
                    <div class="textbox-wrap">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-key"></i></span>
                            <?php echo form_input($new_password_confirm); ?>
                        </div>
                    </div> -->
                    <?php echo form_input($user_id); ?>
                    <?php echo form_hidden($csrf); ?>

                    <!-- <div class="form-action clearfix">
                        <a class="btn btn-success pull-left login_link" href="<?= site_url('login') ?>"><i
                                class="fa fa-chevron-left"></i> <?= lang('back_to_login') ?>  </a>
                        <button type="submit" class="btn btn-primary pull-right"><?= lang('submit') ?> &nbsp;&nbsp; <i
                                class="fa fa-send"></i></button>
                    </div> -->

                    <div class="form-action clearfix">
                        <!-- <a class="btn btn-lg btn-primary pull-left login_link" href="<?= site_url('login') ?>"><i
                                class="fa fa-chevron-left"></i> <?= lang('back') ?>  </a> -->
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
    <script>
        /*function checkPasswordMatch() {
            var password = $("#new").val();
            var confirmPassword = $("#new_confirm").val();
            console.log(password);
            console.log(confirmPassword);
            if (password != confirmPassword)
                $("#divCheckPasswordMatch").html("*Password does not match!");
            else
                $("#divCheckPasswordMatch").html("");
        }*/

        $(document).ready(function () {
           //$("#new, #new_confirm").keyup(checkPasswordMatch);

           $('#reset_password_form').bootstrapValidator({
                //message: 'Please enter a password',
                submitButtons: 'button[type="submit"]',
                fields: {
                    new_password: {
                        validators: {
                            notEmpty: {
                                message: 'The New Password is required.'
                            },
                            identical: {
                                field: 'new_confirm',
                                message: 'The password and its confirm are not the same'
                            }
                        }
                    },
                    new_confirm: {
                        validators: {
                            notEmpty: {
                                message: 'The Confirm New Password is required.'
                            },
                            identical: {
                                field: 'new_password',
                                message: 'The password and its confirm are not the same'
                            }
                        }
                    }
                }
            });
        });
    </script>
                <!-- </div>
            </div> -->
        <!-- </div> -->
    <!-- </div> -->
<!-- </div> -->

</body>
</html>