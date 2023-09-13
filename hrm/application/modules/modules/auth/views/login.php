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
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="shortcut icon" href="<?= base_url() ?>assets/logo/logo.ico"/>
    
    <link href="<?= base_url() ?>application/css/theme.css" rel="stylesheet" type="text/css"/>
    <link href="<?= base_url() ?>node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="<?= base_url() ?>node_modules/font-awesome/css/font-awesome.min.css" rel="stylesheet"/>

    <script src="<?= base_url() ?>node_modules/jquery/dist/jquery.min.js"></script>
    <script src="<?= base_url() ?>node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    
    <script type="text/javascript" src="<?= base_url() ?>node_modules/bootstrapvalidator/dist/js/bootstrapValidator.min.js"></script>

    <script src="<?= base_url() ?>node_modules/bootbox/bootbox.min.js"></script>
    <script src="<?= base_url() ?>node_modules/bootbox/bootbox.all.js"></script>
    <!--[if lt IE 9]>
    <script src="<?= $assets ?>js/respond.min.js"></script>
    <![endif]-->

</head>

<body class="login-page" style="background: url('../assets/background/login_background.jpg'); background-size: cover;
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
                <div class="text-center">
                    <?php 
                        echo '<img src="' . base_url('assets/logo/logo.png') . '" style="margin-bottom:10px;" height="130" />';
                    ?>
                </div>
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
                                   placeholder="<?= lang('Password') ?>" style="font-size:18px;" />
                            <span class="input-group-addon">
                                <span class="icon icon-lg">
                                    <i class="fa fa-lock"></i>
                                </span>
                            </span>
                        </div>
                        
                    </div>
                    
                    <div class="form-action clearfix" style="margin-bottom: 20px;margin-top: -20px !important;">
                        <!-- <div class="checkbox pull-left">
                            <div class="custom-checkbox">
                                <?php echo form_checkbox('remember', '1', FALSE, 'id="remember"'); ?>
                            </div>
                            <span class="checkbox-text pull-left"><label
                                    for="remember"><?= lang('remember_me') ?></label></span>
                        </div> --><br>

                        <button type="submit" class="btn btn_purple btn-lg pull-right"><?= lang('login') ?> &nbsp; 
                            <i class="fa fa-sign-in"></i>
                        </button>
                    </div>
                    <?php echo form_close(); ?>
                        <div class="login-form-links link1 pull-left">
                            <!-- <a href="<?= site_url('auth/login/register'); ?>" class="register_link" target="_blank">Create Account</a> -->
                        </div>

                    <div class="login-form-links link2 pull-right">
                        <a href="<?= site_url('auth/login/forgot_password'); ?>" class="text-danger forgot_password_link" target="_blank">Forgot Password?</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="forgot_password" style="display: none;">
        <section class="body-sign">
            <div class="center-sign">
            
             <div class="panel panel-sign">
           
            <div class="panel-body" style="background-color: white; border-radius: 10px;" >
                <div class="text-center">
                    <?php
                        echo '<img src="' . base_url('assets/logo/logo.png') . '" alt="' . '" style="margin-bottom:10px;" height="130" />';
                    ?>
                </div>
                    <?php
                    if ($message) { ?>
                        <div class="alert alert-success">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <ul class="list-group"><?= $message; ?></ul>
                        </div>
                    <?php } ?>
                    <div class="div-title">
                        <h3 class="text-primary"><?= lang('forgot_password') ?></h3><br>
                    </div>
                    <?php echo form_open("auth/forgot_password", 'class="login" id="forgot_password_form" data-toggle="validator"'); ?>
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
                        <a class="btn btn-lg btn_purple pull-left login_link" href="#login"><i
                                class="fa fa-chevron-left"></i> <?= lang('back') ?>  </a>
                        <button type="submit" class="btn btn_purple btn-lg pull-right"><?= lang('submit') ?> &nbsp;&nbsp; 
                            <i class="fa fa-envelope"></i>
                        </button>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>


        </div>
    </section>
    </div>
        <div id="register" style="display:none;">
            <section class="body-sign">
                    <div class="center-sign">
            
             <div class="panel panel-sign">
           
            <div class="panel-body" style="background-color: white; border-radius: 10px;" >
                <div class="text-center">
                    <?php
                        echo '<img src="' . base_url('assets/logo/logo.png') . '" alt="' . '" style="margin-bottom:10px;" height="130" />';
                    ?></div>
                        
                    <?php
                    if ($message) { ?>
                        <div class="alert alert-success">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <?= $message; ?>
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
                                    <!-- <?php echo form_input($last_name); ?> -->
                                    <!-- <span class="input-group-addon"><i class="fa fa-angle-right"></i></span> -->
                                    <input style="border-bottom-left-radius: 4px;border-top-left-radius: 4px;" type="text" name="last_name" class="form-control input-lg"
                                       placeholder="<?= lang('last_name') ?>" value="<?=$this->session->flashdata('regLastName')?$this->session->flashdata('regLastName'):'';?>"/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="controls">
                                <div class="input-group col-sm-12">
                                    <!-- <?php echo form_input($email); ?> -->
                                    <!-- <span class="input-group-addon"><i class="fa fa-angle-right"></i></span> -->
                                    <input style="border-bottom-left-radius: 4px;border-top-left-radius: 4px;" type="email" name="email" required="required" class="form-control input-lg"
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
                                       placeholder="<?= lang('Password') ?>" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"/>
                                </div>
                            </div>
                            <span style="color:#9349BD;display: block;font-size: 12px;margin-top: 5px;"><?= lang('pasword_hint') ?></span>
                        </div>

                        <div class="form-group row">
                            <div class="controls">
                                <div class="input-group col-sm-12">
                                    <input style="border-bottom-left-radius: 4px;border-top-left-radius: 4px;" type="password" name="password_confirm" class="form-control input-lg" placeholder="<?= lang('confirm_password') ?>" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" data-bv-identical="true" data-bv-identical-field="password" data-bv-identical-message="<?= lang('pw_not_same') ?>"/>
                                </div>
                            </div>
                        </div>

                    </fieldset>
                    <div class="form-action clearfix">
                        <a class="btn btn-lg btn_purple pull-left login_link" href="#login"><i
                                class="fa fa-chevron-left"></i> <?= lang('back') ?>  </a>
                        <button type="submit" class="btn btn_purple btn-lg pull-right"><?= lang('register') ?> &nbsp;&nbsp; 
                            <i class="fa fa-user"></i>
                        </button>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
            </div>
        </section>
        </div>

        <div id="after_register" style="display: none;">
            <section class="body-sign">
                    <div class="center-sign">
            
             <div class="panel panel-sign">
           
            <div class="panel-body" style="background-color: white; border-radius: 10px;" >
                <div class="text-center">
                    <?php
                        echo '<img src="' . base_url('assets/logo/logo.png') . '" alt="' . '" style="margin-bottom:10px;" height="130" />';
                    ?>
                </div>
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
                        <!-- <a href="<?= base_url();?>" class="btn btn-primary pull-right">OK</a> -->
                        <a href="login" class="btn btn_purple pull-right">OK</a>
                    </div>
                </div>
            </div>
            </div>
        </section>
        </div>

    <div id="after_forgot_password" style="display: none;">
        <section class="body-sign">
            <div class="center-sign">
            
             <div class="panel panel-sign">
           
            <div class="panel-body" style="background-color: white; border-radius: 10px;" >
                <div class="text-center">
                    <?php
                        echo '<img src="' . base_url('assets/logo/logo.png') . '" style="margin-bottom:10px;" height="130" />';
                    ?>
                </div>
                    <?php
                    if ($message) { ?>
                        <div class="alert alert-success">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <ul class="list-group"><?= $message; ?></ul>
                        </div>
                    <?php } ?>
                    <div class="form-action clearfix" style="margin-bottom: 20px;margin-top: 20px !important;">
                        <a href="<?= base_url('auth/login');?>" class="btn btn_purple pull-right">OK</a>
                    </div>
                </div>
            </div>


        </div>
    </section>
    </div>
</div>
<script src="../application/modules/auth/js/jquery.cookie.js"></script>
<script src="../application/modules/auth/js/login.js"></script> 
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
        

        $('#forgot_password_form').bootstrapValidator({
            submitButtons: 'button[type="submit"]',
            fields: {
                forgot_email: {
                      validators: {
                        notEmpty: {
                          message: 'The email is required'
                        }
                  }
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
                    }
                }
            });
    });
</script>
    


</body>
</html>
