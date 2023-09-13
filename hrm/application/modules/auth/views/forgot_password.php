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
	 <div id="forgot_password">
	    <section class="body-sign">
	        <div class="center-sign">
	             <div class="panel panel-sign">
	           
		            <div class="panel-body" style="background-color: white; border-radius: 10px;" >
		                <div class="text-center">
		                	<?php 
		                		// if ($Settings->logo2) {
	                        echo '<img src="' . base_url('assets/logo/logo.png') . '" style="margin-bottom:10px;" height="130" />';
		                            // } 
		                    ?>
			            </div>
	                    <!-- <?php if ($error) { ?>
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
	                    <?php } ?> -->
	                    <div class="div-title">
	                        <h3 class="text-primary">Forget Password</h3><br>
	                    </div>
	                    <?php echo form_open("auth/forgot_password", 'class="login" data-toggle="validator"'); ?>
	                    <div class="form-group mb-lg">
	                        <div class="input-group input-group-icon">
	                            <input type="email" required="required" class="form-control input-lg" name="forgot_email"
	                                   placeholder="Email Address" style="font-size:18px;" required="required"/>
	                            <span class="input-group-addon">
	                                <span class="icon icon-lg">
	                                    <i class="fa fa-envelope"></i>
	                                </span>
	                            </span>
	                        </div>
	                    </div>

	                    <div class="form-action clearfix">
	                        <a class="btn btn-lg btn_purple pull-left login_link" href="#login"><i
	                                class="fa fa-chevron-left"></i> Back  </a>
	                        <button type="submit" class="btn btn_purple btn-lg pull-right">Submit &nbsp;&nbsp; 
	                            <i class="fa fa-envelope"></i>
	                        </button>
	                    </div>
	                    <?php echo form_close(); ?>
	                </div>
	            </div>
	        </div>
	    </section>
    </div>
</div>
</body>