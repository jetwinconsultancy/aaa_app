<?php if ($Owner) { ?>
<div class="col-md-12">
<section class="panel">
	<header class="panel-heading">
		<h2 class="panel-title"><i class="fa fa-users" ></i> <?= lang('create_user'); ?></h2>
    </header>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
				echo form_open('auth/edit_user/' . $user->id, $attrib);
				?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-5">
                            <div class="form-group">
                                <?php echo lang('first_name', 'first_name'); ?>
                                <div class="controls">
                                    <?php echo form_input('first_name', $user->first_name, 'class="form-control" id="first_name"   pattern=".{3,10}"'); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <?php echo lang('last_name', 'last_name'); ?>
                                <div class="controls">
                                    <?php echo form_input('last_name', $user->last_name, 'class="form-control" id="last_name"  '); ?>
                                </div>
                            </div>
                            <div class="form-group">
								<?= lang('gender', 'gender'); ?>
								<div class="controls">  <?php
									$ge[''] = array('male' => lang('male'), 'female' => lang('female'));
									echo form_dropdown('gender', $ge, (isset($_POST['gender']) ? $_POST['gender'] : $user->gender), 'class="tip form-control" id="gender"  ');
									?>
								</div>
                            </div>

                            <div class="hidden">
								<?php if (!$this->ion_auth->in_group('customer', $id) && !$this->ion_auth->in_group('supplier', $id)) { ?>
									<div class="form-group">
										<?php echo lang('company', 'company'); ?>
										<div class="controls">
											<?php echo form_input('company', $user->company, 'class="form-control" id="company"'); ?>
										</div>
									</div>
								<?php } else {
									echo form_hidden('company', $user->company);
								} ?>
                            </div>

							<div class="form-group">

								<?php echo lang('phone', 'phone'); ?>
								<div class="controls">
									<input type="tel" name="phone" class="form-control" id="phone"
										     value="<?= $user->phone ?>"/>
								</div>
							</div>

							<?php if ($Owner && $id != $this->session->userdata('user_id')) { ?>
                            
							<div class="form-group">
								<?php echo lang('email', 'email'); ?>

								<input type="email" name="email" class="form-control" id="email"
									   value="<?= $user->email ?>"  />
							</div>
							<?php } ?>
                        </div>
                        <div class="col-md-5 col-md-offset-1">

							<?php if ($Owner && $id != $this->session->userdata('user_id')) { ?>
								<div class="form-group">
									<?php echo lang('username', 'username'); ?>
									<input type="text" name="username" class="form-control"
										   id="username" value="<?= $user->username ?>"
										    />
								</div>
							<?php } ?>
							
                            <div class="form-group">
                                <?php echo lang('password', 'password'); ?>
                                <div class="controls">
                                    <?php echo form_password('password', '', 'class="form-control tip" id="password"   pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"'); ?>
                                    <span class="help-block"><?= lang('pasword_hint') ?></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <?php echo lang('confirm_password', 'confirm_password'); ?>
                                <div class="controls">
                                    <?php echo form_password('confirm_password', '', 'class="form-control" id="confirm_password"   pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" data-bv-identical="true" data-bv-identical-field="password" data-bv-identical-message="' . lang('pw_not_came') . '"'); ?>
                                </div>
                            </div>
							<div class="form-group">
								<?= lang('status', 'status'); ?>
								<?php
								$opt = array(1 => lang('active'), 0 => lang('inactive'));
								echo form_dropdown('status', $opt, (isset($_POST['status']) ? $_POST['status'] : $user->active), 'id="status"   class="form-control input-tip select" style="width:100%;"');
								?>
							</div>
                            <div class="row">
                                <div class="col-md-8"><label class="checkbox" for="notify"><input type="checkbox"
                                                                                                  name="notify"
                                                                                                  value="1" id="notify"
                                                                                                  checked="checked"/> <?= lang('notify_user_by_email') ?>
                                    </label>
                                </div>
                                <div class="clearfix"></div>
                            </div>

                        </div>
                    </div>
                </div>

                <p></p>
                <p><?php echo form_submit('update', lang('update'), 'class="btn btn-primary"'); ?>
				<a href="welcome/" class="btn btn-default">Cancel</a></p>
                <p></p>

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</section>
</div>
<script type="text/javascript" charset="utf-8">
    $(document).ready(function () {
        $('#group').change(function (event) {
            var group = $(this).val();
            if (group == 1 || group == 2) {
                $('.no').slideUp();
                $('form[data-toggle="validator"]').bootstrapValidator('removeField', 'biller');
                $('form[data-toggle="validator"]').bootstrapValidator('removeField', 'warehouse');
            } else {
                $('.no').slideDown();
                $('form[data-toggle="validator"]').bootstrapValidator('addField', 'biller');
                $('form[data-toggle="validator"]').bootstrapValidator('addField', 'warehouse');
            }
        });
    });
</script>
<?php } ?>