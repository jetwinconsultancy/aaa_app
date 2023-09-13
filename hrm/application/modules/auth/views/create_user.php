<!-- <h1><?php echo lang('create_user_heading');?></h1>
<p><?php echo lang('create_user_subheading');?></p>

<div id="infoMessage"><?php echo $message;?></div>

<?php echo form_open("auth/create_user");?>

      <p>
            <?php echo lang('create_user_fname_label', 'first_name');?> <br />
            <?php echo form_input($first_name);?>
      </p>

      <p>
            <?php echo lang('create_user_lname_label', 'last_name');?> <br />
            <?php echo form_input($last_name);?>
      </p>
      
      <?php
      if($identity_column!=='email') {
          echo '<p>';
          echo lang('create_user_identity_label', 'identity');
          echo '<br />';
          echo form_error('identity');
          echo form_input($identity);
          echo '</p>';
      }
      ?>

      <p>
            <?php echo lang('create_user_company_label', 'company');?> <br />
            <?php echo form_input($company);?>
      </p>

      <p>
            <?php echo lang('create_user_email_label', 'email');?> <br />
            <?php echo form_input($email);?>
      </p>

      <p>
            <?php echo lang('create_user_phone_label', 'phone');?> <br />
            <?php echo form_input($phone);?>
      </p>

      <p>
            <?php echo lang('create_user_password_label', 'password');?> <br />
            <?php echo form_input($password);?>
      </p>

      <p>
            <?php echo lang('create_user_password_confirm_label', 'password_confirm');?> <br />
            <?php echo form_input($password_confirm);?>
      </p>


      <p><?php echo form_submit('submit', lang('create_user_submit_btn'));?></p>

<?php echo form_close();?> -->


<link href="../application/css/theme.css" rel="stylesheet" type="text/css">
<link href="../node_modules/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<link href="../node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

<script src="../node_modules/bootstrap/dist/js/bootstrap.min.js"></script>

<!-- <h1><?php echo lang('forgot_password_heading');?></h1>
<p><?php echo sprintf(lang('forgot_password_subheading'), $identity_label);?></p>

<div id="infoMessage"><?php echo $message;?></div>

<?php echo form_open("auth/forgot_password");?>

      <p>
        <label for="identity"><?php echo (($type=='email') ? sprintf(lang('forgot_password_email_label'), $identity_label) : sprintf(lang('forgot_password_identity_label'), $identity_label));?></label> <br />
        <?php echo form_input($identity);?>
      </p>

      <p><?php echo form_submit('submit', lang('forgot_password_submit_btn'));?></p>

<?php echo form_close();?>
 -->

<body class="login-page" style="background: url('../assets/background/login_background.jpg'); background-size: cover; background-repeat: no-repeat;background-position: center;">
<!-- <noscript>
    <div class="global-site-notice noscript">
        <div class="notice-inner">
            <p><strong>JavaScript seems to be disabled in your browser.</strong><br>You must have JavaScript enabled in
                your browser to utilize the functionality of this website.</p>
        </div>
    </div>
</noscript> -->

<div class="page-back">
  <div id="register">
    <section class="body-sign">
      <div class="center-sign">
        <div class="panel panel-sign">
          <div class="panel-body" style="background-color: white; border-radius: 10px;" >
            <div class="text-center">
              <?php
               // if ($Settings->logo2) {
                echo '<img src="' . base_url('assets/uploads/logos/HR_logo.png') . '" alt="' . $Settings->site_name . '" style="margin-bottom:10px;" height="130" />';
              ?>
            </div>
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
                        <span style="color:#9349BD;display: block;font-size: 12px;margin-top: 5px;"><?= lang('pasword_hint') ?></span>
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

                </fieldset>
                <div class="form-action clearfix">
                    <a class="btn btn-lg btn_purple pull-left login_link" href="#login"><i
                            class="fa fa-chevron-left"></i> <?= lang('back') ?>  </a>
                    <button type="submit" class="btn btn_purple btn-lg pull-right"><?= lang('register') ?> &nbsp;&nbsp; 
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
<!-- <?php } ?> -->

 <!-- <?php if ($Settings->allow_reg) { ?> -->
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
</div>
</body>