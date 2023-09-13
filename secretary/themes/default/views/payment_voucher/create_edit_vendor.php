<?php
	$now = getDate();

	if ($this->session->userdata('supplier_code') && $this->session->userdata('supplier_code') != '')
	{
		$supplier_code =$this->session->userdata('supplier_code');
	} 
	else 
	{
		$supplier_code = 'supplier_'.$now[0];
		$this->session->set_userdata('supplier_code', $supplier_code);
	}
	
?>
<div class="header_between_all_section">
	<section class="panel">
		<?php echo $breadcrumbs;?>
		<div class="panel-body">
			<div class="col-md-12">
				<div id="modalLG" class="modal-block modal-block-lg" style="max-width: 100%	;margin: 0px auto;">
					<section class="panel" style="margin-bottom: 0px;">
						<div class="panel-body">
							<div class="modal-wrapper">
								<div class="modal-text">
									<div class="tabs">
										
										<ul class="nav nav-tabs nav-justify" id="myTab">
											<?php if ((!$Individual && $Individual == true) || (!$Individual && $Individual == null && !$Client)) {?>
												<?php if ($company_info_module != 'none') { ?> 
													<li class="active check_stat" id="li-vendorInfo" data-information="vendorInfo">
														<a href="#w2-vendorInfo" data-toggle="tab" class="text-center">
															<span class="badge hidden-xs">1</span>
															Vendor Info
														</a>
													</li>
												<?php
													}
												?> 
												<?php if ($setup_module != 'none') { ?>
												<li class="check_stat" id="li-vendorSetup" data-information="vendorSetup">
													<a href="#w2-vendorSetup" data-toggle="tab" class="text-center">
														<span class="badge hidden-xs">2</span>
														Setup
													</a>
												</li>
												<?php
													}
												?> 
												
											<?php
												}
											?> 
										</ul>
										
														
										<div class="tab-content">
											<?php if ((!$Individual && $Individual == true) || (!$Individual && $Individual == null && !$Client)) {?>
											<?php if ($company_info_module != 'none') { ?>
											
											<div id="w2-vendorInfo" class="tab-pane active">

												<?php echo form_open_multipart('', array('id' => 'upload_vendor_info', 'enctype' => "multipart/form-data")); ?>
												<div class="hidden"><input type="text" class="form-control" name="supplier_code" value="<?=$supplier_code?>"/></div>

												<span style="font-size: 2.4rem;padding: 0; margin: 7px 0 4px 0;">Vendor Profile</span>
												<div class="form-group" style="margin-top: 20px;">
													<label class="col-sm-4 control-label" for="w2-username">Vendor Code: </label>
													<div class="col-sm-4">
														<input type="text" style="text-transform:uppercase" class="form-control" maxlength="20" id="vendor_code" name="vendor_code" value="<?=isset($vendor->vendor_code)?$vendor->vendor_code:''?>">
														<div id="form_vendor_code"></div>

													</div>
												</div>
												<!-- <div class="form-group">
													<label class="col-sm-4 control-label" for="w2-username">Registration No: </label>
													<div class="col-sm-4">
														<input type="text" style="text-transform:uppercase" class="form-control" id="vendor_registration_no" name="vendor_registration_no" value="<?=$vendor->registration_no?>" >
														<div id="form_vendor_registration_no"></div>
													</div>
												</div> -->
												<div class="form-group">
													<label class="col-sm-4 control-label" for="w2-username">Company Name: </label>
													<div class="col-sm-8">
														<input type="text" style="text-transform:uppercase" class="form-control" id="vendor_company_name" name="vendor_company_name"  value="<?=isset($vendor->company_name)?$vendor->company_name:''?>">
														<div id="form_vendor_company_name"></div>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-4 control-label" for="w2-username">Former Name (if any): </label>
													<div class="col-sm-8">
														<textarea class="form-control" style="text-transform:uppercase" id="vendor_former_name" name="vendor_former_name" /><?=isset($vendor->former_name)?$vendor->former_name:''?></textarea>
														<div id="form_vendor_former_name"></div>

													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-4 control-label">Registered Office Address: </label>
													<div class="col-sm-8">
														<div>
	                                                        <label><input type="radio" id="local_edit" name="address_type" <?php print isset($vendor->local_status)?$vendor->local_status:''; ?> value="Local"/>&nbsp;&nbsp;Singapore Address</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label><input type="radio" id="foreign_edit" name="address_type" <?php print isset($vendor->foreign_status)?$vendor->foreign_status:''; ?> value="Foreign"/>&nbsp;&nbsp;Foreign Address</label>
	                                                    </div>
	                                                </div>
												</div>
												<div id="tr_local_edit" <?php print $vendor->address_type=="Local"?'style=""':'style="display:none;"'; ?>>
													<div class="form-group">
														<label class="col-sm-4 control-label"></label>
														<div class="col-sm-8">
															<div>
																<div style="width: 15%;float:left;margin-right: 20px;">
																	<label>Postal Code :</label>
																</div>
																<div style="width: 65%;float:left;margin-bottom:5px;">
																	<div class="" style="width: 20%;" >
																		<input type="text" style="text-transform:uppercase" class="form-control" id="vendor_postal_code" name="vendor_postal_code" value="<?=isset($vendor->postal_code)?$vendor->postal_code:''?>" maxlength="6">
																	</div>
																	<div id="form_vendor_postal_code"></div>
																</div>
															</div>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-4 control-label"></label>
														<div class="col-sm-8">
															<div>
																<div style="width: 15%;float:left;margin-right: 20px;">
																	<label>Street Name :</label>
																</div>
																<div style="width: 71%;float:left;margin-bottom:5px;">
																	<div class="" style="width: 100%;" >
																		<input type="text" style="text-transform:uppercase" class="form-control" id="vendor_street_name" name="vendor_street_name" value="<?=isset($vendor->street_name)?$vendor->street_name:''?>">
																	</div>
																	<div id="form_vendor_street_name"></div>
												
																</div>
															</div>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-4 control-label"></label>
														<div class="col-sm-8">
															<div>
																<div style="width: 15%;float:left;margin-right: 20px;">
																	<label>Building Name :</label>
																</div>
																<div style="width: 71%;float:left;margin-bottom:5px;">
																	<div class="" style="width: 100%;" >
																		<input type="text" style="text-transform:uppercase" class="form-control" id="vendor_building_name" name="vendor_building_name" value="<?=isset($vendor->building_name)?$vendor->building_name:''?>">
																	</div>
																	<div id="form_vendor_street_name"></div>
																</div>
															</div>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-4 control-label"></label>
														<div class="col-sm-8">
															<label style="width: 15%;float:left;margin-right: 20px;">Unit No :</label>
															<input style="width: 8%; float: left; margin-right: 10px; text-transform:uppercase;" type="text" class="form-control" id="vendor_unit_no1" name="vendor_unit_no1" value="<?=isset($vendor->unit_no1)?$vendor->unit_no1:''?>" maxlength="3">
															<label style="float: left; margin-right: 10px;" >-</label>
															<input style="width: 14%; text-transform:uppercase;" type="text" class="form-control" id="vendor_unit_no2" name="vendor_unit_no2" value="<?=isset($vendor->unit_no2)?$vendor->unit_no2:''?>" maxlength="10">
														</div>
													</div>
												</div>
												<div id="tr_foreign_edit" <?php print $vendor->address_type=="Foreign"?'style=""':'style="display:none;"'; ?>>
														<div class="form-group">
	                                                        <label class="col-sm-4 control-label"></label>
															<div class="col-sm-8">
																<div>
	                                                                <div class="add-input-group" style="width: 95%;" >
	                                                                    <input style="margin-bottom: 5px;" type="text" class="form-control input-xs" id="vendor_foreign_address1" name="vendor_foreign_address1" value="<?=isset($vendor->foreign_address1)?$vendor->foreign_address1:''?>">
	                                                                </div>
	                                                                <div id="form_vendor_foreign_address1"></div>
	                                                            </div>
	                                                        </div>
	                                                    </div>
                                                        <div class="form-group">
	                                                        <label class="col-sm-4 control-label"></label>
															<div class="col-sm-8">
																<div>
	                                                                <div class="add-input-group" style="width: 95%;" >
	                                                                    <input style="margin-bottom: 5px;" type="text" class="form-control input-xs" id="vendor_foreign_address2" name="vendor_foreign_address2" value="<?=isset($vendor->foreign_address2)?$vendor->foreign_address2:''?>">
	                                                                </div>
	                                                            </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
	                                                        <label class="col-sm-4 control-label"></label>
															<div class="col-sm-8">
																<div>
	                                                                <div class="add-input-group" style="width: 95%;" >
	                                                                    <input type="text" class="form-control input-xs" id="vendor_foreign_address3" name="vendor_foreign_address3" value="<?=isset($vendor->foreign_address3)?$vendor->foreign_address3:''?>">
	                                                                </div>
	                                                            </div>
                                                            </div>
                                                        </div>
                                                    </div>

												<?= form_close(); ?>
											</div>
											<?php
												}
											?>
											<?php
												}
											?>
											<?php if ($setup_module != 'none') { ?>
											<div id="w2-vendorSetup" class="tab-pane">
												<?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form', 'id' => 'vendor_setup_form');
													echo form_open_multipart("payment_voucher/add_vendor_setup_info", $attrib);
												?>

												<?php if ((!$Individual && $Individual == true) || (!$Individual && $Individual == null && !$Client)) {?>
												<div class="hidden"><input type="text" class="form-control" name="supplier_code" value="<?=$supplier_code?>"/></div>
												<h3 style="margin-bottom: 20px;">Contact Information</h3>
												<div class="form-group">
													<label class="col-sm-3" for="w2-chairman">Name:</label>
													<div class="col-sm-9">
														<input type="text" style="width:400px;text-transform:uppercase;" class="form-control" name="contact_name" id="contact_name" value="<?=isset($vendor_contact_info[0]->name)?$vendor_contact_info[0]->name:''?>"/>
										            </div>
													
												</div>
												<div class="form-group">
													<label class="col-sm-3" for="w2-chairman">Phone:</label>
													<div class="col-sm-9">

														<div class="input-group fieldGroup_contact_phone">
															<input type="tel" class="form-control check_empty_contact_phone main_contact_phone hp" id="contact_phone" name="contact_phone[]" value="<?=isset($client_contact_info[0]->contact_phone)?$client_contact_info[0]->contact_phone:''?>"/>

															<input type="hidden" class="form-control input-xs hidden_contact_phone main_hidden_contact_phone" id="hidden_contact_phone" name="hidden_contact_phone[]" value=""/>

															<label class="radio-inline control-label" style="margin-top: -30px; margin-left: 20px;"><input type="radio" class="contact_phone_primary main_contact_phone_primary" name="contact_phone_primary" value="1" checked> Primary</label>
															<input class="btn btn-primary button_increment_contact_phone addMore_contact_phone" type="button" id="create_button" value="+" style="margin-left: 20px; margin-top: -26px; border-radius: 3px;visibility: hidden; width: 35px;"/>

															<button type="button" class="btn btn-default btn-sm show_contact_phone" style="margin-left: 20px; margin-top: -23px; visibility: hidden;">
																  <span class="fa fa-arrow-down" aria-hidden="true"></span>&nbsp<span class="toggle_word">Show more</span>
															</button>

														</div>

														<div class="contact_phone_toggle"></div>

														<div class="input-group fieldGroupCopy_contact_phone contact_phone_disabled" style="display: none;">
															<input type="tel" class="form-control check_empty_contact_phone second_contact_phone second_hp" id="contact_phone" name="contact_phone[]" value=""/>

															<input type="hidden" class="form-control input-xs hidden_contact_phone" id="hidden_contact_phone" name="hidden_contact_phone[]" value=""/>

															<label class="radio-inline control-label" style="margin-top: -30px; margin-left: 20px;"><input type="radio" class="contact_phone_primary" name="contact_phone_primary" value="1"> Primary</label>
															<input class="btn btn-primary button_decrease_contact_phone remove_contact_phone" type="button" id="create_button" value="-" style="margin-left: 20px; margin-top: -26px; border-radius: 3px; width: 35px;"/>
														</div>

														<div id="form_contact_phone"></div>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3" for="w2-chairman">Email:</label>
													<div class="col-sm-9">
														<div class="input-group fieldGroup_contact_email" style="display: block !important;">
															<input type="text" class="form-control input-xs check_empty_contact_email main_contact_email" id="contact_email" name="contact_email[]" value="<?=isset($client_contact_info[0]->contact_email)?$client_contact_info[0]->contact_email:''?>" style="text-transform:uppercase; width:400px;"/>

															<label class="radio-inline control-label" style="margin-left: 20px;"><input type="radio" class="contact_email_primary main_contact_email_primary" name="contact_email_primary" value="1" checked> Primary</label>
															
																<input class="btn btn-primary button_increment_contact_email addMore_contact_email" type="button" id="create_button" value="+" style="margin-left: 20px; border-radius: 3px;visibility: hidden; width: 35px;"/>

															<button type="button" class="btn btn-default btn-sm show_contact_email" style="margin-left: 20px; visibility: hidden;">
																  <span class="fa fa-arrow-down" aria-hidden="true"></span>&nbsp<span class="toggle_word">Show more</span>
															</button>

														</div>

														<div class="contact_email_toggle"></div>

														<div class="input-group fieldGroupCopy_contact_email contact_email_disabled" style="display: none;">
															<input type="text" class="form-control input-xs check_empty_contact_email second_contact_email" id="contact_email" name="contact_email[]" value="" style="width:400px; text-transform:uppercase; "/>
															<label class="radio-inline control-label" style="margin-left: 20px;"><input type="radio" class="contact_email_primary" name="contact_email_primary" value="1"> Primary</label>
																<input class="btn btn-primary button_decrease_contact_email remove_contact_email" type="button" id="create_button" value="-" style="margin-left: 20px; border-radius: 3px; width: 35px;"/>
														</div>
														<div id="form_contact_email"></div>
										            </div>
												</div>

												<?php
													}
												?>
												<?= form_close(); ?>
											</div>
											<?php
												}
											?>
										</div>

									</div>
									</div>
								</div>
							</div>
							<footer class="panel-footer">
								<div class="row">
									<div class="col-md-12 number text-right" id="client_footer_button">
										<!--input type="button" value="Save As Draft" id="save_draft" class="btn btn-default"-->
										<input type="button" value="Save" id="save_vendor" class="btn btn-primary">
										<a href="<?= base_url();?>payment_voucher/" class="btn btn-default">Cancel</a>
									</div>
								</div>
							</footer>
						</section>
					</div>
				</div>
			</div>
		<!-- end: page -->
		</section>
	</div>
		<!-- <div class="loading" id='loadingClient'>Loading&#8230;</div> -->
	</div>
</section>
<script type="text/javascript">
	$("#header_our_firm").removeClass("header_disabled");
   	$("#header_manage_user").removeClass("header_disabled");
  	$("#header_access_right").removeClass("header_disabled");
  	$("#header_client_access").removeClass("header_disabled");
  	$("#header_user_profile").removeClass("header_disabled");
  	$("#header_setting").removeClass("header_disabled");
 	$("#header_dashboard").removeClass("header_disabled");
  	$("#header_client").removeClass("header_disabled");
  	$("#header_person").removeClass("header_disabled");
 	$("#header_document").removeClass("header_disabled");
 	$("#header_report").removeClass("header_disabled");
	$("#header_billings").removeClass("header_disabled");
	$("#header_payment_voucher").addClass("header_disabled");
	
	var vendor_info = <?php echo json_encode(isset($vendor)?$vendor:'');?>;
	var vendor_contact_info = <?php echo json_encode(isset($vendor_contact_info)?$vendor_contact_info:'');?>;
	var vendor_contact_info_email = <?php echo json_encode(isset($vendor_contact_info[0]->vendor_contact_info_email)?$vendor_contact_info[0]->vendor_contact_info_email:'');?>;
	var vendor_contact_info_phone = <?php echo json_encode(isset($vendor_contact_info[0]->vendor_contact_info_phone)?$vendor_contact_info[0]->vendor_contact_info_phone:'');?>;
	var tab = <?php echo json_encode(isset($tab)?$tab:'');?>;
</script>
<script src="themes/default/assets/js/intlTelInput.js?v=30eee4fc8d1b59e4584b0d39edfa2082" /></script>
<script src="themes/default/assets/js/defaultCountryIp.js?v=30eee4fc8d1b59e4584b0d39edfa2082" /></script>
<script src="themes/default/assets/js/utils.js?v=30eee4fc8d1b59e4584b0d39edfa2082" /></script>
<script src="themes/default/assets/js/create_edit_vendor.js?v=40eee4fc8d1b59e4584b0d39edfa2d082" charset="utf-8"></script>
<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>
