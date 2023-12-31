<div class="row">

    <div class="col-sm-2">
        <div class="row">
            <div class="col-sm-12 text-center">
                <div style="max-width:200px; margin: 0 auto;">
                    <?=
                    $user->avatar ? '<img alt="" src="' . base_url() . 'assets/uploads/avatars/thumbs/' . $user->avatar . '" class="avatar">' :
                        '<img alt="" src="' . base_url() . 'assets/images/' . $user->gender . '.png" class="avatar">';
                    ?>
                </div>
                <h4><?= lang('login_email'); ?></h4>

                <p><i class="fa fa-envelope"></i> <?= $user->email; ?></p>
            </div>
        </div>
    </div>
    <div class="col-sm-10">
	<div class="tabs">
											
		<ul class="nav nav-tabs nav-justify">
			<li class="active check_stat" id="#li-account" data-information="account">
				<a href="#w2-account" data-toggle="tab" class="text-center">
					Edit
				</a>
			</li>
			<li class="check_stat" id="#li-change_password" data-information="change_password">
				<a href="#w2-change_password" data-toggle="tab" class="text-center">
					Change Password
				</a>
			</li>
			<li class="check_stat" id="#li-Image" data-information="Image">
				<a href="#w2-Image" data-toggle="tab" class="text-center">
					Image
				</a>
			</li>
		</ul>
	
		<div class="tab-content">
			<div id="w2-account" class="tab-pane active">
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
											<?php echo form_input('first_name', $user->first_name, 'class="form-control" id="first_name" required="required"'); ?>
										</div>
									</div>

									<div class="form-group">
										<?php echo lang('last_name', 'last_name'); ?>

										<div class="controls">
											<?php echo form_input('last_name', $user->last_name, 'class="form-control" id="last_name" required="required"'); ?>
										</div>
									</div>
									<?php if (!$this->ion_auth->in_group('customer', $id) && !$this->ion_auth->in_group('supplier', $id)) { ?>
										<div class="form-group">
											<?php echo lang('company', 'company'); ?>
											<div class="controls">
												<?php echo form_input('company', $user->company, 'class="form-control" id="company" required="required"'); ?>
											</div>
										</div>
									<?php } else {
										echo form_hidden('company', $user->company);
									} ?>
									<div class="form-group">

										<?php echo lang('phone', 'phone'); ?>
										<div class="controls">
											<input type="tel" name="phone" class="form-control" id="phone"
												   required="required" value="<?= $user->phone ?>"/>
										</div>
									</div>

									<div class="form-group">
										<?= lang('gender', 'gender'); ?>
										<div class="controls">  <?php
											$ge[''] = array('male' => lang('male'), 'female' => lang('female'));
											echo form_dropdown('gender', $ge, (isset($_POST['gender']) ? $_POST['gender'] : $user->gender), 'class="tip form-control" id="gender" required="required"');
											?>
										</div>
									</div>
									<?php if (($Owner || $Admin) && $id != $this->session->userdata('user_id')) { ?>
									<div class="form-group">
										<?= lang('award_points', 'award_points'); ?>
										<?= form_input('award_points', set_value('award_points', $user->award_points), 'class="form-control tip" id="award_points"  required="required"'); ?>
									</div>
									<?php } ?>

									<?php if ($Owner && $id != $this->session->userdata('user_id')) { ?>
										<div class="form-group">
											<?php echo lang('username', 'username'); ?>
											<input type="text" name="username" class="form-control"
												   id="username" value="<?= $user->username ?>"
												   required="required"/>
										</div>
										<div class="form-group">
											<?php echo lang('email', 'email'); ?>

											<input type="email" name="email" class="form-control" id="email"
												   value="<?= $user->email ?>" required="required"/>
										</div>

									<?php } ?>

								</div>
								<div class="col-md-5 col-md-offset-1">
									<?php if ($Owner && $id != $this->session->userdata('user_id')) { ?>
										<div class="row">
											<div class="panel panel-warning">
												<div
													class="panel-heading"><?= lang('if_you_need_to_rest_password_for_user') ?></div>
												<div class="panel-body" style="padding: 5px;">
													<div class="col-md-12">
														<div class="col-md-12">
															<div class="form-group">
																<?php echo lang('password', 'password'); ?>
																<?php echo form_input($password); ?>
															</div>

															<div class="form-group">
																<?php echo lang('confirm_password', 'password_confirm'); ?>
																<?php echo form_input($password_confirm); ?>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<?php if (!$this->ion_auth->in_group('customer', $id) && !$this->ion_auth->in_group('supplier', $id)) { ?>
											<div class="row">
												<div class="panel panel-warning">
													<div class="panel-heading"><?= lang('user_options') ?></div>
													<div class="panel-body" style="padding: 5px;">
														<div class="col-md-12">
															<div class="col-md-12">
																<div class="form-group">
																	<?= lang('status', 'status'); ?>
																	<?php
																	$opt = array(1 => lang('active'), 0 => lang('inactive'));
																	echo form_dropdown('status', $opt, (isset($_POST['status']) ? $_POST['status'] : $user->active), 'id="status" required="required" class="form-control input-tip select" style="width:100%;"');
																	?>
																</div>

																<div class="form-group">
																	<?= lang("group", "group"); ?>
																	<?php
																	$gp[""] = "";
																	foreach ($groups as $group) {
																		if ($group['name'] != 'customer' && $group['name'] != 'supplier') {
																			$gp[$group['id']] = $group['name'];
																		}
																	}
																	echo form_dropdown('group', $gp, (isset($_POST['group']) ? $_POST['group'] : $user->group_id), 'id="group" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("group") . '" required="required" class="form-control input-tip select" style="width:100%;"');
																	?>
																</div>
																<div class="clearfix"></div>
																<div class="no">
																	<div class="form-group">
																		<?= lang("biller", "biller"); ?>
																		<?php
																		$bl[""] = "";
																		foreach ($billers as $biller) {
																			$bl[$biller->id] = $biller->company != '-' ? $biller->company : $biller->name;
																		}
																		echo form_dropdown('biller', $bl, (isset($_POST['biller']) ? $_POST['biller'] : $user->biller_id), 'id="biller" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("biller") . '" required="required" class="form-control input-tip select" style="width:100%;"');
																		?>
																	</div>

																	<div class="form-group">
																		<?= lang("warehouse", "warehouse"); ?>
																		<?php
																		// $wh[''] = '';
																		// foreach ($warehouses as $warehouse) {
																			// $wh[$warehouse->id] = $warehouse->name;
																		// }
																		echo form_input('warehouse', 1, 'id="warehouse" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("warehouse") . '" required="required" style="width:100%;" ');
																		?>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>

										<?php } ?>
									<?php } ?>
									<?php echo form_hidden('id', $id); ?>
									<?php echo form_hidden($csrf); ?>
								</div>
							</div>
						</div>
						<p><?php echo form_submit('update', lang('update'), 'class="btn btn-primary"'); ?></p>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
			<div id="w2-change_password" class="tab-pane">
				<div class="row">
					<div class="col-lg-12">
						<div class="row">
                            <div class="col-lg-12">
                                <?php echo form_open("auth/change_password", 'id="change-password-form"'); ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <?php echo lang('old_password', 'curr_password'); ?> <br/>
                                                <?php echo form_password('old_password', '', 'class="form-control" id="curr_password" required="required" pattern="[A-Za-z0-9]{7,}"'); ?>
                                            </div>

                                            <div class="form-group">
                                                <label
                                                    for="new_password"><?php echo sprintf(lang('new_password'), $min_password_length); ?></label>
                                                <br/>
                                                <?php echo form_password('new_password', '', 'class="form-control" id="new_password" required="required" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"'); ?>
                                                <span class="help-block"><?= lang('pasword_hint') ?></span>
                                            </div>

                                            <div class="form-group">
                                                <?php echo lang('confirm_password', 'new_password_confirm'); ?> <br/>
                                                <?php echo form_password('new_password_confirm', '', 'class="form-control" id="new_password_confirm" required="required" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" data-bv-identical="true" data-bv-identical-field="new_password" data-bv-identical-message="' . lang('pw_not_same') . '"'); ?>

                                            </div>
                                            <?php echo form_input($user_id); ?>
                                            <p><?php echo form_submit('change_password', lang('change_password'), 'class="btn btn-primary"'); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>
                    
					</div>
				</div>
			</div>
			<div id="w2-Image" class="tab-pane">
				<div class="row">
					<div class="col-lg-12">
					    <div class="row">
                            <div class="col-lg-12">
                                <div class="col-md-5">
                                    <div style="position: relative;">
                                        <?php if ($user->avatar) { ?>
                                            <img alt=""
                                                 src="<?= base_url() ?>assets/uploads/avatars/<?= $user->avatar ?>"
                                                 class="profile-image img-thumbnail">
                                            <a href="#" class="btn btn-danger btn-xs po"
                                               style="position: absolute; top: 0;" title="<?= lang('delete_avatar') ?>"
                                               data-content="<p><?= lang('r_u_sure') ?></p><a class='btn btn-block btn-danger po-delete' href='<?= site_url('auth/delete_avatar/' . $id . '/' . $user->avatar) ?>'> <?= lang('i_m_sure') ?></a> <button class='btn btn-block po-close'> <?= lang('no') ?></button>"
                                               data-html="true" rel="popover"><i class="fa fa-trash-o"></i></a><br>
                                            <br><?php } ?>
                                    </div>
                                    <?php echo form_open_multipart("auth/update_avatar"); ?>
                                    <div class="form-group">
                                        <?= lang("change_avatar", "change_avatar"); ?>
                                        <input type="file" name="avatar" id="product_image" required="required"
                                               data-show-upload="false" data-show-preview="false" accept="image/*"
                                               class="form-control file"/>
                                    </div>
                                    <div class="form-group">
                                        <?php echo form_hidden('id', $id); ?>
                                        <?php echo form_hidden($csrf); ?>
                                        <?php echo form_submit('update_avatar', lang('update_avatar'), 'class="btn btn-primary"'); ?>
                                        <?php echo form_close(); ?>
                                    </div>

                                </div>
                            </div>
                        </div>
                    
					</div>
				</div>
			</div>
		</div>
	</div>
        
    </div>
    <script>
        $(document).ready(function () {
            $('#change-password-form').bootstrapValidator({
                message: 'Please enter/select a value',
                submitButtons: 'input[type="submit"]'
            });
        });
    </script>
    <?php if ($Owner && $id != $this->session->userdata('user_id')) { ?>
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
            var group = <?=$user->group_id?>;
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
    </script>
<?php } ?>