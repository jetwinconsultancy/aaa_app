<div id="login">
         <section class="body-sign">
             <div class="center-sign">
                <div class="panel panel-sign">
                       <!--  <div class="panel-title-sign mt-xl text-right">
                            <h2 class="title text-uppercase text-weight-bold m-none" style="font-size:14px; font-weight:normal;" ><i class="fa fa-user mr-xs"></i> Sign In</h2>
                        </div> -->
                    <ul class="nav nav-tabs nav-justify login_nav" id="myTab">
                        <li class="active check_stat" id="li-passwordLogin" data-information="passwordLogin">
                            <a href="#nav-passwordLogin" data-toggle="tab" class="text-center header_login">
                                Password Login
                            </a>
                        </li>
                        <li class="check_stat" id="li-singpassLogin" data-information="singpassLogin" >
                            <a href="#nav-singpassLogin" data-toggle="tab" class="text-center header_login">
                                SingPass Login
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
                        <div id="nav-singpassLogin" class="tab-pane">
                            <h3>Scan the QR code with your SingPass mobile app to login</h3>
                            <!-- element to contain the all SPCPQR Elements -->
                            <div id="qr_wrapper"></div>
                            <div class="register-mobile">
                                <p class="dont_have_singapss">Don't have SingPass Mobile? Download from</p>
                                    <a target="_blank" class="app-store" href="https://itunes.apple.com/us/app/singpass-mobile/id1340660807">App Store </a> or <a target="_blank" class="app-store" href="https://play.google.com/store/apps/details?id=sg.ndi.sp&amp;hl=en-GB"> Google Play Store</a>
                            </div>
                            <?php if ($Settings->allow_reg) { ?>
                                <div class="login-form-links link1 pull-left">
                                    <a href="<?= site_url('login/register'); ?>" class="register_link" target="_blank" style="color: #D9A200;">Create Account</a>
                                </div>
                            <?php } ?>
                            <div class="login-form-links link2 pull-right">
                                <?php if ($Settings->logo2) {
                                    echo '<img src="' . base_url('assets/uploads/logos/secretary_logo.png') . '" alt="' . $Settings->site_name . '" style="margin-bottom:10px;" height="40" />';
                                } ?> 
                            </div>
                        </div>
                    </div>

                    <!-- old method -->
                    <div class="panel-body" style="background-color: white; border-radius: 10px;" >
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
                    <!-- old method end -->
                </div>
            </div>
        </section>
    </div>