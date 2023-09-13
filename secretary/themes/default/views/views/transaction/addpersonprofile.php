<div class="header_between_all_section">
	<section class="panel">
		<?php echo $breadcrumbs;?>
		<div class="panel-body">
			<div class="modal-wrapper">
                <div class="modal-text">
                    <div class="tabs">
                        <ul class="nav nav-tabs nav-justify" id="myTab">
                            <li class="active person_check_state" id="li-customerProfile" data-information="customerProfile">
                                <a href="#w2-customerProfile" data-toggle="tab" class="text-center">
                                    <span class="badge hidden-xs">1</span>
                                    Info
                                </a>
                            </li>
                            <li class="person_check_state" id="li-kycScreening" data-information="kycScreeningInfo" >
                                <a href="#w2-kycScreening" data-toggle="tab" class="text-center ">
                                    <span class="badge hidden-xs">2</span>
                                    KYC Screening
                                </a>
                            </li>
                            <li class="person_check_state" id="li-screening" data-information="screening" >
                                <a href="#w2-screening" data-toggle="tab" class="text-center ">
                                    <span class="badge hidden-xs">3</span>
                                    Screening Result
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div id="w2-customerProfile" class="tab-pane active">
								<section class="panel" id="wPerson">
									<header class="panel-heading">
										<h2 class="panel-title"><?=$page_name?></h2>
									</header>
									<div class="panel-body">
										<table class="table table-bordered table-striped table-condensed mb-none" >
											<tr>
												<th style="width: 200px">Type</th>
												<td>
													<label><input type="radio" id="individual_edit" name="field_type" <?php print $person->individual_status; ?> value="Individual" class="check_stat" data-information="individual" <?php if($person->individual_disabled=='true') echo ' disabled ';?>/>&nbsp;&nbsp;Individual</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
													<label><input type="radio" id="company_edit" name="field_type" <?php print $person->company_status; ?> value="company" class="check_stat" data-information="company" <?php if($person->company_disabled=='true') echo ' disabled ';?>/>&nbsp;&nbsp;Company</label>
												</td>
											</tr>
										</table>

											<?php echo form_open_multipart('', array('id' => 'upload', 'enctype' => "multipart/form-data")); ?>
												<table class="table table-bordered table-striped table-condensed mb-none" id="tr_individual_edit" <?php print $person->individual_table; ?>>
													<input type="hidden" class="form-control input-sm" name="close_page" value="<?=$close_page?>">
													<input type="hidden" class="form-control input-sm" name="field_type" value="individual">
													<tr>
														<input type="hidden" class="form-control input-sm" id="officer_id" name="officer_id" value="<?=$person->id?>">
														<th>Identification Type</th>
														<td>
															<select class="form-control" id="identification_type" name="identification_type">
																<option value="NRIC (Singapore citizen)" <?php if($person->identification_type=='NRIC (Singapore citizen)') echo ' Selected ';?>>NRIC (Singapore Citizen)</option>
																<option value="NRIC (PR)" <?php if($person->identification_type=='NRIC (PR)') echo ' Selected ';?>>NRIC (Singapore Permanent Resident)</option>
																<option value="FIN Number" <?php if($person->identification_type=='FIN Number') echo ' Selected ';?>>Passholders</option>
																<option value="Passport/ Others" <?php if($person->identification_type=='Passport/ Others') echo ' Selected ';?>>Passport/ Others</option>

															</select>
														</td>
													</tr>
													<tr>
														<th>Identification No.</th>
														<td>
															<input type="hidden" class="form-control input-sm" id="old_identification_no" name="old_identification_no" style="text-transform:uppercase" value="<?=htmlspecialchars($person->identification_no, ENT_QUOTES)?>">
															<input type="hidden" class="form-control input-sm" id="company_register_no" name="company_register_no" style="text-transform:uppercase" value="<?=htmlspecialchars($person->register_no, ENT_QUOTES)?>">
															<input type="text" class="form-control input-xs" id="identification_no" name="identification_no" style="text-transform:uppercase" required value="<?=htmlspecialchars($person->decrypt_identification_no, ENT_QUOTES)?>">
															<div id="form_identification_no"></div>
														</td>				
													</tr>
													<tr>
														<th>Name</th>
														<td><input type="text" class="form-control input-xs" id="name" name="name" style="text-transform:uppercase" value="<?=htmlspecialchars($person->name, ENT_QUOTES)?>"/>
														<div id="form_name"></div>
														</td>
													</tr>
													<tr>
														<th>Date Of Birth</th>
														<td>
															<div class="input-group mb-md" style="width: 200px;">
																<span class="input-group-addon">
																	<i class="far fa-calendar-alt"></i>
																</span>
																<input type="text" class="form-control input-xs valid" id="date_of_birth" name="date_of_birth" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" required="" value="<?=$person->date_of_birth?>" placeholder="DD/MM/YYYY">
															</div>
															<div id="form_date_of_birth"></div>
														</td>
													</tr>
													<tr>
														<th style="width: 200px">Address</th>
														<td>
															<label>
																<input type="radio" id="local_edit" name="address_type" <?php print $person->local_status; ?> value="Local"/>&nbsp;&nbsp;Local Address
															</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
															<label>
																<input type="radio" id="foreign_edit" name="address_type" <?php print $person->foreign_status; ?> value="Foreign"/>&nbsp;&nbsp;Foreign Address
															</label>
														</td>
													</tr>
													<tr id="tr_local_edit" <?php print $person->address_type=="Local"?'style=""':'style="display:none;"'; ?>>
														<th></th>
														<td>
															<div style="width: 100%;">
																<div style="width: 25%;float:left;margin-right: 20px;">
																	<label>Postal Code :</label>
																</div>
																<div style="width: 65%;float:left;margin-bottom:5px;">
																	<div class="input-group" style="width: 20%;" >
																		<input type="text" class="form-control input-xs" id="postal_code1" name="postal_code1" style="text-transform:uppercase" value="<?=$person->postal_code1?>">
																	</div>
																	<div id="form_postal_code1"></div>
																</div>
															</div>
															<div style="margin-bottom:5px;">
																<label style="width: 25%;float:left;margin-right: 20px;">Street Name :</label>
																<div style="width: 65%;float:left;margin-bottom:5px;">
																	<div class="input-group" style="width: 84.5%;">
																		<input style="width: 100%; text-transform:uppercase" type="text" class="form-control input-xs" id="street_name1" name="street_name1" value="<?=$person->street_name1?>">
																	</div>
																	<div id="form_street_name1"></div>
																</div>
															</div>
															<div style="margin-bottom:5px;">
																<label style="width: 25%;float:left;margin-right: 20px;">Building Name :</label>
																<div class="input-group" style="width: 55%;" >
																	<input style="width: 100%; text-transform:uppercase" type="text" class="form-control input-xs" id="building_name1" name="building_name1" value="<?=$person->building_name1?>">
																	<?php echo form_error('building_name1','<span class="help-block">*','</span>'); ?>
																</div>
															
															</div>
															<div style="margin-bottom:5px;">
																<div style="width: 25%;">
																<label style="width: 100%;float:left;margin-right: 20px;">Unit No :</label>
															</div>
																<div style="width: 75%;" > 
																	<div class="" style="width: 10%;display: inline-block">
																		<input style="width: 100%; margin-right: 10px; text-transform:uppercase" type="text" class="form-control input-xs" id="unit_no1" name="unit_no1" value="<?=$person->unit_no1?>">
																			<?php echo form_error('unit_no1','<span class="help-block">*','</span>'); ?>
																	</div>
																	<div class="" style="width: 15%;display: inline-block;" >
																		<input style="width: 100%; text-transform:uppercase" type="text" class="form-control input-xs" id="unit_no2" name="unit_no2" value="<?=$person->unit_no2?>" maxlength="10">
																		
																		<?php echo form_error('unit_no2','<span class="help-block">*','</span>'); ?>
																	</div>
																</div>
															</div>
														</td>
													</tr>
													<tr id="tr_foreign_edit" <?php print $person->address_type=="Foreign"?'style=""':'style="display:none;"'; ?>>
														<td></td>
														<td colspan="2">
															<div style="width: 100%;">
																<div style="width: 25%;float:left;margin-right: 20px;">
																	<label>Foreign Address :</label>
																</div>
																<div style="width: 65%;float:left;margin-bottom:5px;">
																	<div class="input-group" style="width: 65%;" >
																		<input style="margin-bottom: 5px;" type="text" class="form-control input-xs" id="foreign_address1" name="foreign_address1" value="<?=$person->foreign_address1?>"> 
																	</div>
																	<div id="form_foreign_address1"></div>
																</div>
															</div>
															<div style="width: 100%;">
																<div style="width: 25%;float:left;margin-right: 20px;">
																	<label></label>
																</div>
																<div style="width: 65%;float:left;margin-bottom:5px;">
																	<div class="input-group" style="width: 65%;" >
																		<input style="margin-bottom: 5px;" type="text" class="form-control input-xs" id="foreign_address2" name="foreign_address2" value="<?=$person->foreign_address2?>">
																	</div>
																	<div id="form_foreign_address2"></div>
																</div>
															</div>
															<div style="width: 100%;">
																<div style="width: 25%;float:left;margin-right: 20px;">
																	<label></label>
																</div>
																<div style="width: 65%;float:left;margin-bottom:5px;">
																	<div class="input-group" style="width: 65%;" >
																		<input type="text" class="form-control input-xs" id="foreign_address3" name="foreign_address3" value="<?=$person->foreign_address3?>">
																	</div>
																	<div id="form_foreign_address3"></div>
																</div>
															</div>
															
														</td>
													</tr>
													<tr>
														<th>Alternate Address</th>
														<td>
															<div style="margin-bottom:5px;">
																<label  id="alternate_label_edit" style="float:left;margin-right: 20px;"><input type="hidden" name="alternate_address" value="0"><input type="checkbox" id="alternate_address" name="alternate_address" <?php print $person->alternate_address_status; ?> value="1"></label>Alternate Address : <br><br>
																<div id="alternate_text_edit" <?php print $person->alternate_text_status; ?>>
																	<div style="width: 100%;">
																		<div style="width: 25%;float:left;margin-right: 20px;">
																			<label>Postal Code :</label>
																		</div>
																		<div style="width: 65%;float:left;margin-bottom:5px;">
																			<div class="input-group" style="width: 20%;" >
																				<input type="text" style="text-transform:uppercase" class="form-control input-xs" id="postal_code2" name="postal_code2" value="<?=$person->postal_code2?>">
																			</div>

																			<div id="form_postal_code2"></div>

																		</div>
																	</div>
																	<div style="margin-bottom:5px;">
																		<label style="width: 25%;float:left;margin-right: 20px;">Street Name :</label>
																		<div style="width: 65%;float:left;margin-bottom:5px;">
																			<div class="input-group" style="width: 84.5%;">
																				<input style="width: 100%; text-transform:uppercase" type="text" class="form-control input-xs" id="street_name2" name="street_name2" value="<?=$person->street_name2?>">
																			</div>
																			<div id="form_street_name2"></div>
																		</div>
																	</div>
																	<div style="margin-bottom:5px;">
																		<label style="width: 25%;float:left;margin-right: 20px;">Building Name :</label>
																		<input style="width: 51%; text-transform:uppercase" type="text" class="form-control input-xs" id="building_name2" name="building_name2" value="<?=$person->building_name2?>">
																	</div>
																	<div style="margin-bottom:5px;">
																		<label style="width: 25%;float:left;margin-right: 20px;">Unit No :
																		</label>
																		<input style="width: 7%; float: left; margin-right: 3px; text-transform:uppercase" type="text" class="form-control input-xs" id="unit_no3" name="unit_no3" value="<?=$person->unit_no3?>">

																		<input style="width: 12%; text-transform:uppercase" type="text" class="form-control input-xs" id="unit_no4" name="unit_no4" value="<?=$person->unit_no4?>" maxlength="10">
																	</div>
																</div>
															</div>
														</td>
													</tr>
													<tr>
														<th>Nationality</th>
														<td>
															<select id="nationalityId" class="form-control input-xs nationality" name="nationality">
											                    <option value="0">Select Nationality</option>
											                </select>
											                <div id="form_nationality"></div>
														</td>
													</tr>
													<tr>
														<th style="width: 200px">Fixed Line No</th>
														<td class="added-text-field">
															<div class="input-group fieldGroup_local_fix_line">
																<input type="tel" class="form-control input-xs check_empty_local_fix_line main_local_fix_line hp" id="local_fix_line" name="local_fix_line[]" value="<?=$person->officer_mobile_no?>"/>

																<input type="hidden" class="form-control input-xs hidden_local_fix_line main_hidden_local_fix_line" name="hidden_local_fix_line[]" value=""/>

																<label class="radio-inline control-label" style="margin-top: -30px; margin-left: 20px;"><input type="radio" class="fixed_line_no_primary main_fixed_line_no_primary" name="fixed_line_no_primary" value="1" checked> Primary</label>

																<button type="button" class="btn btn-default btn-sm show_local_fix_line" style="margin-left: 20px; margin-top: -23px; visibility: hidden;">
																  <span class="fa fa-arrow-down" aria-hidden="true"></span>&nbsp<span class="toggle_word">Show more</span>
																</button>
															</div>
															<div class="local_fix_line_toggle">
															</div>
															<div class="input-group fieldGroupCopy_local_fix_line local_fix_line_disabled" style="display: none;">
																<input type="tel" class="form-control input-xs check_empty_local_fix_line second_local_fix_line second_hp" name="local_fix_line[]" value=""/>

																<input type="hidden" class="form-control input-xs hidden_local_fix_line" id="hidden_local_fix_line" name="hidden_local_fix_line[]" value=""/>

																<label class="radio-inline control-label" style="margin-top: -30px; margin-left: 20px;"><input type="radio" class="fixed_line_no_primary" name="fixed_line_no_primary" value="1"> Primary</label>

																<input class="btn btn-primary button_decrease_local_fix_line remove_local_fix_line" type="button" value="-" style="margin-left: 20px; margin-top: -26px;border-radius: 3px; width: 35px;"/>
															</div>
															<div id="form_local_fix_line"></div>
														</td>
														
													</tr>
													<tr>
														<th style="width: 200px">Mobile No</th>
														<td class="added-text-field">
															<div class="input-group fieldGroup_local_mobile">
																<input type="tel" class="form-control input-xs check_empty_local_mobile main_local_mobile hp" id="local_mobile" name="local_mobile[]" value=""/>

																<input type="hidden" class="form-control input-xs hidden_local_mobile main_hidden_local_mobile" id="hidden_local_mobile" name="hidden_local_mobile[]" value=""/>

																<label class="radio-inline control-label" style="margin-top: -30px; margin-left: 20px;"><input type="radio" class="local_mobile_primary main_local_mobile_primary" name="local_mobile_primary" value="1" checked> Primary</label>

																<input class="btn btn-primary button_increment_local_mobile addMore_local_mobile" type="button" value="+" style="margin-left: 20px; margin-top: -26px;border-radius: 3px;visibility: hidden; width: 35px;"/>

																<button type="button" class="btn btn-default btn-sm show_local_mobile" style="margin-left: 20px; margin-top: -23px; visibility: hidden;">
																	  <span class="fa fa-arrow-down" aria-hidden="true"></span>&nbsp<span class="toggle_word">Show more</span>
																</button>
															</div>

															<div class="local_mobile_toggle">
															</div>


															<div class="input-group fieldGroupCopy_local_mobile local_mobile_disabled" style="display: none;">
																<input type="tel" class="form-control input-xs check_empty_local_mobile second_local_mobile second_hp" name="local_mobile[]" value=""/>

																<input type="hidden" class="form-control input-xs hidden_local_mobile" name="hidden_local_mobile[]" value=""/>

																<label class="radio-inline control-label" style="margin-top: -30px; margin-left: 20px;"><input type="radio" class="local_mobile_primary" name="local_mobile_primary" value="1"> Primary</label>

																<input class="btn btn-primary button_decrease_local_mobile remove_local_mobile" type="button" value="-" style="margin-left: 20px; margin-top: -26px;border-radius: 3px; width: 35px;"/>
															</div>
															<div id="form_local_mobile"></div>
														</td>
														
													</tr>

													<tr>
														<th style="width: 200px">Email</th>
														<td class="added-text-field_email">								
															<div class="input-group fieldGroup_email" style="display: block !important;">
																<input type="text" class="form-control input-xs check_empty_email main_email" name="email[]" value="" style="text-transform:uppercase;"/>

																<label class="radio-inline control-label" style="margin-top: 5px; margin-left: 20px;"><input type="radio" class="email_primary main_email_primary" name="email_primary" value="1" checked> Primary</label>

																<input class="btn btn-primary button_increment_email addMore_email" type="button" value="+" style="margin-left: 20px; border-radius: 3px;visibility: hidden; width: 35px;"/>

																<button type="button" class="btn btn-default btn-sm show_email" style="margin-left: 20px; visibility: hidden;">
																	  <span class="fa fa-arrow-down" aria-hidden="true"></span>&nbsp<span class="toggle_word">Show more</span>
																</button>
															</div>

															<div class="local_email_toggle">
															</div>


															<div class="input-group fieldGroupCopy_email email_disabled" style="display: none;">
																<input type="text" class="form-control input-xs check_empty_email second_email" name="email[]" value="" style="text-transform:uppercase;"/>
																<label class="radio-inline control-label" style="margin-top: 5px; margin-left: 20px;"><input type="radio" class="email_primary" name="email_primary" value="1"> Primary</label>

																<input class="btn btn-primary button_decrease_email remove_email" type="button" value="-" style="margin-left: 20px; border-radius: 3px; width: 35px;"/>
															</div>

															<div id="form_email"></div>
														</td>
														
													</tr>
													<?php if($person->non_verify == 1){ ?>
														<tr>
															<th>Verify</th>
															<td>
																<div style="margin-right:5px;float: left">
															        <input type="checkbox" name="non_verify_checkbox" <?=$person->non_verify?'checked':'';?>/>
							                                        <input type="hidden" name="hidden_non_verify_checkbox" value="<?=$person->non_verify?>"/>
															    </div>
															</td>
														</tr>
													<?php } ?>
													<tr>
														<th>File Upload - gif,jpg,jpeg,png,pdf</th>
														<td>
												          	<div class="file-loading">
												                <input type="file" id="multiple_file" class="file" name="uploadimages[]" multiple data-min-file-count="0">
												            </div>
														</td>
													</tr>
												</table>
												<?= form_close();?>

												<?php echo form_open_multipart('', array('id' => 'submit_company', 'enctype' => "multipart/form-data")); ?>
												<table class="table table-bordered table-striped table-condensed mb-none" id="tr_company_edit" <?php print $person->company_table; ?>>
													<input type="hidden" class="form-control input-sm" name="close_page" value="<?=$close_page?>">
													<input type="hidden" class="form-control input-sm" name="field_type" value="company">
													<input type="hidden" class="form-control input-sm" id="officer_company_id" name="officer_company_id" value="<?=$person->id?>">
													<tr>
														<th>UEN</th>
														<input type="hidden" class="form-control input-sm" id="old_register_no" name="old_register_no" value="<?=htmlspecialchars($person->register_no, ENT_QUOTES)?>">
														<input type="hidden" class="form-control input-sm" id="individual_identification_no" name="individual_identification_no" value="<?=htmlspecialchars($person->identification_no, ENT_QUOTES)?>">
														<td>
															<input type="text" style="text-transform:uppercase" class="form-control input-xs" id="register_no" name="register_no" value="<?=htmlspecialchars($person->decrypt_register_no, ENT_QUOTES)?>"/>
															<div id="form_register_no"></div>
														</td>
													</tr>
													<tr>
														<th>Company Name</th>
														<td><input type="text" style="text-transform:uppercase" class="form-control input-xs" id="company_name" name="company_name" value="<?=htmlspecialchars($person->company_name, ENT_QUOTES)?>"/>
															<div>
																<div id="form_company_name"></div>
															</div>
														</td>
													</tr>
													<tr>
														<th>Former Name (if any)</th>
														<td><input type="text" style="text-transform:uppercase" class="form-control input-xs" id="company_former_name" name="company_former_name" value="<?=htmlspecialchars($person->company_former_name, ENT_QUOTES)?>"/>
														</td>
													</tr>
													<tr>
														<th>Date of Incorporation</th>
														<td>
															<div class="input-group" style="width: 200px;">
																<span class="input-group-addon">
																	<i class="far fa-calendar-alt"></i>
																</span>
																<input type="text" class="form-control input-xs valid" id="date_of_incorporation" name="date_of_incorporation" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" required="" value="<?=$person->date_of_incorporation?>" placeholder="DD/MM/YYYY">
															</div>
															<div id="form_date_of_incorporation"></div>
														</td>
													</tr>
													<tr>
														<th>Country of Incorporation</th>
														<td><input type="text" style="text-transform:uppercase" class="form-control input-xs" id="country_of_incorporation" name="country_of_incorporation" value="<?=$person->country_of_incorporation?>"/>
															<div>
																<div id="form_country_of_incorporation"></div>
															</div>
														</td>
													</tr>
													<tr>
														<th style="width: 200px">Address</th>
														<td><label><input type="radio" id="company_local_edit" name="address_type" <?php print $person->local_status; ?> value="Local"/>&nbsp;&nbsp;Local Address</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
															<label><input type="radio" id="company_foreign_edit" name="address_type" <?php print $person->foreign_status; ?> value="Foreign"/>&nbsp;&nbsp;Foreign Address</label></td>
													</tr>
													<tr id="tr_company_local_edit" <?php print $person->address_type=="Local"?'style=""':'style="display:none;"'; ?>>
														<th></th>
														<td>
															<div style="width: 100%;">
																<div style="width: 25%;float:left;margin-right: 20px;">
																	<label>Postal Code :</label>
																</div>
																<div style="width: 65%;float:left;margin-bottom:5px;">
																	<div class="" style="width: 20%;" >
																		<input type="text" style="text-transform:uppercase" class="form-control input-xs" id="company_postal_code" name="company_postal_code" value="<?=$person->company_postal_code?>">
																	</div>
																	<div id="form_company_postal_code"></div>
																	<!-- <?php echo form_error('company_postal_code','<span class="help-block">*','</span>'); ?> -->
												
																</div>
															</div>
															<div style="margin-bottom:5px;">
																<div style="width: 25%;float:left;margin-right: 20px;">
																	<label>Street Name :</label>
																</div>
																<div style="width: 65%;float:left;margin-bottom:5px;">
																	<div class="" style="width: 84.5%;">
																		<input style="width: 100%; text-transform:uppercase" type="text" class="form-control input-xs" id="company_street_name" name="company_street_name" value="<?=$person->company_street_name?>">
																	</div>
																	<div id="form_company_street_name"></div>
																</div>
															</div>
															<div style="margin-bottom:5px;">
																<label style="width: 25%;float:left;margin-right: 20px;">Building Name :</label>
																<input style="width: 55%; text-transform:uppercase" type="text" class="form-control input-xs" id="company_building_name" name="company_building_name" value="<?=$person->company_building_name?>">
															
															</div>
															<div style="margin-bottom:5px;">
																<label style="width: 25%;float:left;margin-right: 20px;">Unit No :</label>
																<input style="width: 10%; float: left; margin-right: 10px; text-transform:uppercase" type="text" class="form-control input-xs" id="company_unit_no1" name="company_unit_no1" value="<?=$person->company_unit_no1?>">
																<input style="width: 10%; text-transform:uppercase" type="text" class="form-control input-xs" id="company_unit_no2" name="company_unit_no2" value="<?=$person->company_unit_no2?>">
															
															</div>
														</td>
													</tr>
													<tr id="tr_company_foreign_edit" <?php print $person->address_type=="Foreign"?'style=""':'style="display:none;"'; ?>>
														<th></th>
														<td colspan="2">
															<div style="width: 100%;">
																<div style="width: 25%;float:left;margin-right: 20px;">
																	<label>Foreign Address :</label>
																</div>
																<div style="width: 65%;float:left;margin-bottom:5px;">
																	<div class="input-group" style="width: 65%;" >
																		<input style="margin-bottom: 5px; text-transform:uppercase" type="text" class="form-control input-xs" id="company_foreign_address1" name="company_foreign_address1" value="<?=$person->company_foreign_address1?>">
																	</div>
																	<div id="form_company_foreign_address1"></div>
																</div>
															</div>
															<div style="width: 100%;">
																<div style="width: 25%;float:left;margin-right: 20px;">
																	<label></label>
																</div>
																<div style="width: 65%;float:left;margin-bottom:5px;">
																	<div class="input-group" style="width: 65%;" >
																		<input style="margin-bottom: 5px; text-transform:uppercase" type="text" class="form-control input-xs" id="company_foreign_address2" name="company_foreign_address2" value="<?=$person->company_foreign_address2?>">
																	</div>
																	<div id="form_company_foreign_address2"></div>
																</div>
															</div>

															<div style="width: 100%;">
																<div style="width: 25%;float:left;margin-right: 20px;">
																	<label></label>
																</div>
																<div style="width: 65%;float:left;margin-bottom:5px;">
																	<div class="input-group" style="width: 65%;" >
																		<input style="text-transform:uppercase" type="text" class="form-control input-xs" id="company_foreign_address3" name="company_foreign_address3" value="<?=$person->company_foreign_address3?>">
																	</div>
																	<div id="form_company_foreign_address3"></div>
																</div>
															</div>
														</td>
													</tr>
													<tr>
														<th style="width: 200px">Email</th>
														<td class="added-text-field_email">
															<div class="input-group fieldGroup_company_email" style="display: block !important;">
																<input type="text" class="form-control input-xs check_empty_company_email main_company_email" id="company_email" name="company_email[]" value="<?=$person->company_email?>" style="text-transform:uppercase;"/>

																<label class="radio-inline control-label" style="margin-top: 5px; margin-left: 20px;"><input type="radio" class="company_email_primary main_company_email_primary" name="company_email_primary" value="1" checked> Primary</label>
																
																<input class="btn btn-primary button_increment_company_email addMore_company_email" type="button" value="+" style="margin-left: 20px; border-radius: 3px;visibility: hidden; width: 35px;"/>

																<button type="button" class="btn btn-default btn-sm show_company_email" style="margin-left: 20px; visibility: hidden;">
																	  <span class="fa fa-arrow-down" aria-hidden="true"></span>&nbsp<span class="toggle_word">Show more</span>
																</button>

															</div>

															<div class="company_email_toggle">
															</div>

															<div class="input-group fieldGroupCopy_company_email company_email_disabled" style="display: none;">
																<input type="text" class="form-control input-xs check_empty_company_email second_company_email" id="second_company_email" name="company_email[]" value="" style="text-transform:uppercase;"/>
																<label class="radio-inline control-label" style="margin-top: 5px; margin-left: 20px;"><input type="radio" class="company_email_primary" name="company_email_primary" value="1"> Primary</label>

																<input class="btn btn-primary button_decrease_company_email remove_company_email" type="button" value="-" style="margin-left: 20px; border-radius: 3px; width: 35px;"/>
															</div>
															<div id="form_company_email"></div>
														</td>
													</tr>
													<tr>
														<th style="width: 200px">Phone Number</th>
														<td class="added-text-field">
															<div class="input-group fieldGroup_company_phone_number">
																<input type="tel" class="form-control input-xs check_empty_company_phone_number main_company_phone_number hp" id="company_phone_number" name="company_phone_number[]" value="<?=$person->company_phone_number?>"/>

																<input type="hidden" class="form-control input-xs hidden_company_phone_number main_hidden_company_phone_number" id="hidden_company_phone_number" name="hidden_company_phone_number[]" value=""/>

																<label class="radio-inline control-label" style="margin-top: -30px; margin-left: 20px;"><input type="radio" class="company_phone_number_primary main_company_phone_number_primary" name="company_phone_number_primary" value="1" checked> Primary</label>

																<input class="btn btn-primary button_increment_company_phone_number addMore_company_phone_number" type="button" value="+" style="margin-left: 20px; margin-top: -26px; border-radius: 3px;visibility: hidden; width: 35px;"/>

																<button type="button" class="btn btn-default btn-sm show_company_phone_number" style="margin-left: 20px; margin-top: -23px; visibility: hidden;">
																	  <span class="fa fa-arrow-down" aria-hidden="true"></span>&nbsp<span class="toggle_word">Show more</span>
																</button>

															</div>

															<div class="company_phone_number_toggle">
															</div>

															<div class="input-group fieldGroupCopy_company_phone_number company_phone_number_disabled" style="display: none;">
																<input type="tel" class="form-control input-xs check_empty_company_phone_number second_company_phone_number second_hp" id="second_company_phone_number" name="company_phone_number[]" value=""/>

																<input type="hidden" class="form-control input-xs hidden_company_phone_number" name="hidden_company_phone_number[]" value=""/>

																<label class="radio-inline control-label" style="margin-top: -30px; margin-left: 20px;"><input type="radio" class="company_phone_number_primary" name="company_phone_number_primary" value="1"> Primary</label>

																<input class="btn btn-primary button_decrease_company_phone_number remove_company_phone_number" type="button" value="-" style="margin-left: 20px; margin-top: -26px; border-radius: 3px; width: 35px;"/>
															</div>

															<div id="form_company_phone_number"></div>
														</td>
														
													</tr>

													<?php if($person->non_verify == 1){ ?>
														<tr>
															<th>Verify</th>
															<td>
																<div style="margin-right:5px;float: left">
															        <input type="checkbox" name="non_verify_checkbox" <?=$person->non_verify?'checked':'';?>/>
							                                        <input type="hidden" name="hidden_non_verify_checkbox" value="<?=$person->non_verify?>"/>
															    </div>
															</td>
														</tr>
													<?php } ?>
													<tr>
														<th>File Upload</th>
														<td>
												           	<div class="file-loading">
												                <input type="file" id="multiple_company_file" class="file" name="uploadcompanyimages[]" multiple data-min-file-count="0">
												            </div>
														</td>
													</tr>
													<tr>
														<td colspan="2">
															<div style="margin: 10px;">
																<h4>Corporate Representative</h4>
															</div>
															<table class="table table-bordered table-striped table-condensed mb-none" id="corp_rep_table" style="width: 1050px">
																<thead>
																	<tr>
																		<th id="subsidiary_name" style="text-align: center;width:200px">Subsidiary Name</th>
																		<th id="corp_rep_name" style="text-align: center;width:200px">Name</th>
																		<th style="text-align: center;width:150px" id="corp_rep_identification_num">NIRC/Passport</th>
																		<th style="text-align: center;width:150px" id="effective_date">Effective Date</th>
																		<th style="text-align: center;width:150px" id="cessation_date">Cessation Date</th>
																		<th><a href="javascript: void(0);" style="color: #D9A200;; outline: none !important;text-decoration: none;"><span id="corp_rep_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Corporate Representative" style="font-size:14px; width:200px"><i class="fa fa-plus-circle"></i> Add Corporate</span></a></th>
																	</tr>
																	
																</thead>
																<tbody id="body_corp_rep">
																</tbody>
															</table>
														</td>
													</tr>
												
											</table>
											<?= form_close();?>	
										
										</div>
										<footer class="panel-footer">
											<div class="row">
												<div class="col-md-12 text-right">
													<input type="submit" class="btn btn-primary" value="Save" id="save">
												<a href="<?= base_url();?>personprofile" class="btn btn-default">Cancel</a>
												</div>
											</div>
										</footer>
									</section>		
								</div>
								<div  id="w2-kycScreening" class="tab-pane">
									<div class="container kycScreeningMainCSS">
                						<!-- <div class="row"> -->
											<div class="col-md-12 kycScreeningMainCSS-right">
												<!-- <div class="tabs"> -->
												<ul class="nav nav-tabs nav-justify" id="myTab">
						                            <li class="nav-item active kyc_check_state" id="li-kycScreeningIndividual" data-information="kycScreeningIndividual">
						                                <a href="#w2-kycScreeningIndividual" data-toggle=tab class="text-center nav-link disabled">
						                                    Individual
						                                </a>
						                            </li>
						                            <li class="nav-item kyc_check_state" id="li-kycScreeningCorporate" data-information="kycScreeningCorporate" >
						                                <a href="#w2-kycScreeningCorporate" data-toggle=tab class="text-center nav-link disabled">
						                                    Corporate
						                                </a>
						                            </li>
						                        </ul>
						                        <div class="tab-content kyc-tab-content">
						                        	<hr>
                            						<div id="w2-kycScreeningIndividual" class="tab-pane active">
                            							<form id="kycScreeningIndividual-form">
                            							<input type="hidden" class="form-control input-sm" id="individual_officer_id" name="individual_officer_id" value="">
						                                <input type="hidden" class="form-control input-sm" id="customer_id" name="customer_id" value="">
						                                <input type="hidden" class="form-control input-sm" id="record_id" name="record_id" value="">
						                                <h6 class="kycScreeningMainCSS-heading">Note: Mandatory fields are marked with an asterisk (<span class="color_red">*</span>). If unsure, choose UNKNOWN.</h6>
						                                <h6 class="kycScreeningMainCSS-heading">
						                                	<div class="checkbox">
													          <label>
													          	<input type="hidden" name="individual_customer_active" value="0">
													            <input type="checkbox" value="1" checked="checked" name="individual_customer_active" id="individual_customer_active">
													            <span class="checkbox_text">Customer is active <span class="color_red">*</span></span>
													          </label>
													        </div>
													    </h6>
													    <h4 style="margin-bottom: 10px; display: none" id="show_individual_status">
													    	Approval Status: <span id="individual_status"></span>
													    </h4>
						                                <div class="row kycScreeningMainCSS-form">
						                                	<div class="col-md-12">
							                                	<div class="col-md-3" style="padding-left: 0;">
							                                		<label for="salutation">Salutation</label>
							                                		<select class="form-control" id="salutation" name="salutation">
							                                			<option class="hidden" selected disabled>Please select Salutation</option>
																		<option value="DR">DR</option>
																		<option value="MR">MR</option>
																		<option value="MS">MS</option>
																		<option value="MRS">MRS</option>
																		<option value="MDM">MDM</option>
																	</select>
							                                		<!-- <input type="text" id="salutation" class="form-control" placeholder="Salutation" value=""/> -->
							                                	</div>
							                                	<div class="form-group col-md-6">
							                                		<label for="individual_name">Name<span class="color_red">*</span></label>
							                                		<input style="text-transform:uppercase" type="text" id="individual_name" class="form-control" placeholder="Name" value="" name="individual_name" required="required"/>
							                                	</div>
							                                	<div class="col-md-3">
							                                		<label for="alias">Alias</label>
							                                		<input style="text-transform:uppercase" type="text" id="alias" class="form-control" placeholder="Alias" value="" name="alias"/>
							                                	</div>
							                                </div>
						                                	<div class="col-md-12">
						                                		<div class="col-md-6" style="padding-left: 0;">
							                                        <div class="form-group kycInputFieldMargin">
							                                        	<label for="gender">Gender<span class="color_red">*</span></label>
							                                            <select class="form-control" id="gender" name="gender">
							                                            	<option class="hidden" selected disabled>Please select Gender</option>
																			<option value="MALE">MALE</option>
																			<option value="FEMALE">FEMALE</option>
																			<option value="UNKNOWN">UNKNOWN</option>
																		</select>
							                                        </div>
							                                        <div class="form-group kycInputFieldMargin">
							                                        	<label for="individual_country_of_residence">Country of Residence<span class="color_red">*</span></label>
							                                            <select class="form-control" id="individual_country_of_residence" name="individual_country_of_residence">
							                                            	<option class="hidden" selected disabled>Please select Country of Residence</option>
							                                            </select>
							                                        </div>
							                                    </div>
							                                    <div class="col-md-6" style="padding-left: 0">
							                                    	<div class="form-group kycInputFieldMargin">
							                                        	<label for="individual_nationality">Nationality<span class="color_red">*</span></label>
							                                            <select class="form-control" id="individual_nationality" name="individual_nationality">
							                                            	<option class="hidden" selected disabled>Please select Nationality</option>
							                                            </select>
							                                        </div>
							                                        <div class="form-group kycInputFieldMargin">
							                                        	<label for="individual_identity_document_type">Identity Document Type</label>
							                                            <select class="form-control" id="individual_identity_document_type" name="individual_identity_document_type">
							                                            	<option class="hidden" selected disabled>Please select Identity Document Type</option>
							                                            </select>
							                                        </div>
							                                        <div class="other_identity_document_type_div" style="display: none;">
									                                    <div style="padding-right:0px;">
									                                    	<div class="form-group kycInputFieldMargin">
									                                            <label for="individual_other_identity_document_type">Other Identity Document Type</label>
									                                            <textarea style="text-transform:uppercase" class="form-control" rows="5" id="individual_other_identity_document_type" name="individual_other_identity_document_type" placeholder="Other Identity Document Type"></textarea>
									                                        </div>
									                                    </div>
									                                </div>
							                                    </div>
						                                	</div>
						                                	<div class="col-md-12">
						                                		<div class="col-md-6" style="padding-left: 0">
							                                        <div class="form-group kycInputFieldMargin">
							                                        	<label for="identity_number">Identity Number</label>
							                                            <input style="text-transform:uppercase" type="text" id="identity_number" class="form-control" placeholder="Identity Number" value="" name="identity_number"/>
							                                        </div>
							                                        <div class="form-group kycInputFieldMargin">
							                                            <label for="individual_industry">Industry<span class="color_red">*</span></label>
							                                            <select class="form-control" id="individual_industry" name="individual_industry">
							                                            	<option class="hidden" selected disabled>Please select Industry</option>
							                                            </select>
							                                        </div>
							                                        <div class="form-group kycInputFieldMargin">
							                                            <label for="individual_onboarding_mode">Onboarding Mode<span class="color_red">*</span></label>
							                                            <select class="form-control" id="individual_onboarding_mode" name="individual_onboarding_mode">
							                                            	<option class="hidden" selected disabled>Please select Onboarding Mode</option>
							                                            </select>
							                                        </div>
							                                        <div class="form-group kycInputFieldMargin">
							                                            <label for="individual_product_service_complexity">Product Service Complexity<span class="color_red">*</span></label>
							                                            <select class="form-control" id="individual_product_service_complexity" name="individual_product_service_complexity">
							                                            	<option class="hidden" selected disabled>Please select Product Service Complexity</option>
							                                            </select>
							                                        </div>
							                                        <div class="form-group kycInputFieldMargin">
							                                            <label for="individual_source_of_funds">Source of Funds</label>
							                                            <select class="form-control" id="individual_source_of_funds" name="individual_source_of_funds">
							                                            	<option class="hidden" selected disabled>Please select Source of Funds</option>
							                                            </select>
							                                        </div>
							                                    </div>
							                                    <div class="col-md-6" style="padding-left: 0">
							                                        <div class="form-group kycInputFieldMargin">
							                                            <label for="individual_country_of_birth">Country of Birth</label>
							                                            <select class="form-control" id="individual_country_of_birth" name="individual_country_of_birth">
							                                            	<option class="hidden" selected disabled>Please select Country of Birth</option>
							                                            </select>
							                                        </div>
							                                        <div class="form-group kycInputFieldMargin">
							                                            <label for="individual_occupation">Occupation<span class="color_red">*</span></label>
							                                            <select class="form-control" id="individual_occupation" name="individual_occupation">
							                                            	<option class="hidden" selected disabled>Please select Occupation</option>
							                                            </select>
							                                        </div>
							                                        <div class="form-group kycInputFieldMargin">
							                                            <label for="individual_payment_mode">Payment Mode<span class="color_red">*</span></label>
							                                            <div class="multiselect_div" style="width: 100%">
								                                            <select id="individual_payment_mode" multiple="multiple" name="individual_payment_mode[]">
								                                            </select>
							                                        	</div>
							                                        </div>
							                                        <div class="form-group kycInputFieldMargin">
							                                        	<label for="reference_number">Reference Number</label>
							                                            <input style="text-transform:uppercase" type="text" id="reference_number" class="form-control" placeholder="Reference Number" value="" name="reference_number"/>
							                                        </div>
							                                        <div class="form-group kycInputFieldMargin">
							                                        	<label for="individual_date_of_birth">Date of Birth</label>
							                                            <div class="input-group mb-md" style="width: 100%;">
																			<span class="input-group-addon">
																				<i class="far fa-calendar-alt"></i>
																			</span>
																			<input type="text" class="form-control input-xs" id="individual_date_of_birth" name="individual_date_of_birth" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="DD/MM/YYYY">
																		</div>
							                                        </div>
							                                    </div>
						                                	</div>
						                                    <div class="col-md-12 other_source_of_funds_div" style="display: none;">
							                                    <div class="col-md-6" style="padding-left:0px;">
							                                    	<div class="form-group kycInputFieldMargin">
							                                            <label for="individual_other_source_of_funds">Other Source of Funds</label>
							                                            <textarea style="text-transform:uppercase" class="form-control" rows="5" id="individual_other_source_of_funds" name="individual_other_source_of_funds" placeholder="Other Source of Funds"></textarea>
							                                        </div>
							                                    </div>
							                                    <div class="col-md-6">
							                                    </div>
							                                </div>
						                                    <div class="col-md-6">
						                                    	<div class="form-group kycInputFieldMargin">
						                                        	<label for="individual_address">Address</label>
						                                        	<div class="input-group fieldGroup_individual_address" style="display: block !important;width: 100%">
																		<input type="text" class="form-control input-xs check_empty_individual_address main_individual_address" id="individual_address" name="individual_address[]" value="" style="text-transform:uppercase; width:80%;"/>

																		<!-- <label class="radio-inline control-label" style="margin-top: 5px; margin-left: 20px;"><input type="radio" class="individual_address_primary main_individual_address_primary" name="individual_address_primary" value="1" checked> Primary</label> -->

																		<input class="btn btn-primary button_increment_individual_address addMore_individual_address" type="button" value="+" style="margin-left: 10px; border-radius: 3px;visibility: hidden; width: 35px;"/>

																		<button type="button" class="btn btn-default btn-sm show_individual_address" style="margin-left: 10px; visibility: hidden;">
																			  <span class="fa fa-arrow-down" aria-hidden="true"></span><!-- &nbsp<span class="toggle_word">Show more</span> -->
																		</button>
																	</div>

																	<div class="local_individual_address_toggle">
																	</div>

																	<div class="input-group fieldGroupCopy_individual_address individual_address_disabled" style="display: none;">
																		<input type="text" class="form-control input-xs check_empty_individual_address second_individual_address" id="second_individual_address" name="individual_address[]" value="" style="text-transform:uppercase;width:80%;"/>
																		<!-- <label class="radio-inline control-label" style="margin-top: 5px; margin-left: 20px;"><input type="radio" class="individual_address_primary" name="individual_address_primary" value="1"> Primary</label> -->

																		<input class="btn btn-primary button_decrease_individual_address remove_individual_address" type="button" value="-" style="margin-left: 10px; border-radius: 3px; width: 35px;"/>
																	</div>
						                                        </div>
						                                        <div class="form-group kycInputFieldMargin">
						                                        	<label for="individual_email_address">Email Address</label>
						                                        	<div class="input-group fieldGroup_individual_email_address" style="display: block !important;width: 100%">
																		<input type="text" class="form-control input-xs check_empty_individual_email_address main_individual_email_address" id="individual_email_address" name="individual_email_address[]" value="" style="text-transform:uppercase; width:80%;"/>

																		<!-- <label class="radio-inline control-label" style="margin-top: 5px; margin-left: 20px;"><input type="radio" class="individual_email_address_primary main_individual_email_address_primary" name="individual_email_address_primary" value="1" checked> Primary</label> -->

																		<input class="btn btn-primary button_increment_individual_email_address addMore_individual_email_address" type="button" value="+" style="margin-left: 10px; border-radius: 3px;visibility: hidden; width: 35px;"/>

																		<button type="button" class="btn btn-default btn-sm show_individual_email_address" style="margin-left: 10px; visibility: hidden;">
																			  <span class="fa fa-arrow-down" aria-hidden="true"></span><!-- &nbsp<span class="toggle_word">Show more</span> -->
																		</button>
																	</div>

																	<div class="local_individual_email_address_toggle">
																	</div>

																	<div class="input-group fieldGroupCopy_individual_email_address individual_email_address_disabled" style="display: none;">
																		<input type="text" class="form-control input-xs check_empty_individual_email_address second_individual_email_address" name="individual_email_address[]" value="" style="text-transform:uppercase;width:80%;"/>
																		<!-- <label class="radio-inline control-label" style="margin-top: 5px; margin-left: 20px;"><input type="radio" class="individual_email_address_primary" name="individual_email_address_primary" value="1"> Primary</label> -->

																		<input class="btn btn-primary button_decrease_individual_email_address remove_individual_email_address" type="button" value="-" style="margin-left: 10px; border-radius: 3px; width: 35px;"/>
																	</div>
						                                        </div>
						                                    </div>
						                                    <div class="col-md-6" style="padding-left: 0px;">
						                                    	<div class="form-group kycInputFieldMargin">
						                                        	<label for="individual_phone_number">Phone Number</label>
						                                        	<div class="input-group fieldGroup_individual_phone_number" style="width: 100%;">
																		<input type="tel" class="form-control check_empty_individual_phone_number main_individual_phone_number hp" id="individual_phone_number" name="individual_phone_number[]" value=""/>

																		<input type="hidden" class="form-control input-xs hidden_individual_phone_number main_hidden_individual_phone_number" id="hidden_individual_phone_number" name="hidden_individual_phone_number[]" value=""/>

																		<!-- <label class="radio-inline control-label" style="margin-top: -30px; margin-left: 20px;"><input type="radio" class="individual_phone_number_primary main_individual_phone_number_primary" name="individual_phone_number_primary" value="1" checked> Primary</label> -->

																		<input class="btn btn-primary button_increment_individual_phone_number addMore_individual_phone_number" type="button" value="+" style="margin-left: 10px; margin-top: -26px;border-radius: 3px;visibility: hidden; width: 35px;"/>

																		<button type="button" class="btn btn-default btn-sm show_individual_phone_number" style="margin-left: 10px; margin-top: -23px; visibility: hidden;">
																			  <span class="fa fa-arrow-down" aria-hidden="true"></span><!-- &nbsp<span class="toggle_word">Show more</span> -->
																		</button>
																	</div>

																	<div class="individual_phone_number_toggle">
																	</div>


																	<div class="input-group fieldGroupCopy_individual_phone_number individual_phone_number_disabled" style="display: none; width: 100%;">
																		<input type="tel" class="form-control input-xs check_empty_individual_phone_number second_individual_phone_number second_hp" name="individual_phone_number[]" value=""/>

																		<input type="hidden" class="form-control input-xs hidden_individual_phone_number" name="hidden_individual_phone_number[]" value=""/>

																		<!-- <label class="radio-inline control-label" style="margin-top: -30px; margin-left: 20px;"><input type="radio" class="individual_phone_number_primary" name="individual_phone_number_primary" value="1"> Primary</label> -->

																		<input class="btn btn-primary button_decrease_individual_phone_number remove_individual_phone_number" type="button" value="-" style="margin-left: 10px; margin-top: -26px;border-radius: 3px; width: 35px;"/>
																	</div>
						                                        </div>
						                                        <div class="form-group kycInputFieldMargin">
						                                        	<label for="individual_bank_account">Bank Account</label>
						                                        	<div class="input-group fieldGroup_individual_bank_account" style="display: block !important;width: 100%">
																		<input type="text" class="form-control input-xs check_empty_individual_bank_account main_individual_bank_account" id="individual_bank_account" name="individual_bank_account[]" value="" style="text-transform:uppercase; width:80%;"/>

																		<!-- <label class="radio-inline control-label" style="margin-top: 5px; margin-left: 20px;"><input type="radio" class="individual_bank_account_primary main_individual_bank_account_primary" name="individual_bank_account_primary" value="1" checked> Primary</label> -->

																		<input class="btn btn-primary button_increment_individual_bank_account addMore_individual_bank_account" type="button" value="+" style="margin-left: 10px; border-radius: 3px;visibility: hidden; width: 35px;"/>

																		<button type="button" class="btn btn-default btn-sm show_individual_bank_account" style="margin-left: 10px; visibility: hidden;">
																			  <span class="fa fa-arrow-down" aria-hidden="true"></span><!-- &nbsp<span class="toggle_word">Show more</span> -->
																		</button>
																	</div>

																	<div class="local_individual_bank_account_toggle">
																	</div>

																	<div class="input-group fieldGroupCopy_individual_bank_account individual_bank_account_disabled" style="display: none;">
																		<input type="text" class="form-control input-xs check_empty_individual_bank_account second_individual_bank_account" name="individual_bank_account[]" value="" style="text-transform:uppercase;width:80%;"/>
																		<!-- <label class="radio-inline control-label" style="margin-top: 5px; margin-left: 20px;"><input type="radio" class="individual_bank_account_primary" name="individual_bank_account_primary" value="1"> Primary</label> -->

																		<input class="btn btn-primary button_decrease_individual_bank_account remove_individual_bank_account" type="button" value="-" style="margin-left: 10px; border-radius: 3px; width: 35px;"/>
																	</div>
						                                        </div>
						                                    </div>
						                                    <div class="col-md-12">
						                                    	<div class="form-group">
						                                    		<label for="individual_nature_of_business_relationship">Nature of Business Relationship</label>
						                                    		<textarea class="form-control" rows="5" id="individual_nature_of_business_relationship" name="individual_nature_of_business_relationship" placeholder="Nature of Business Relationship"></textarea>
						                                    	</div>
						                                    </div>
						                                    <div class="col-md-6">
						                                    </div>
						                                    <div class="col-md-6 text-right">
						                                    	<input type="button" class="btn btn-primary individual_kyc_submit indi_save_as_draft_button" value="Save as Draft" style="display: none"/>
						                                    	<input type="button" class="btn btn-primary individual_kyc_submit indi_update_button" value="Update" style="display: none"/>
						                                    	<input type="button" class="btn btn-primary individual_kyc_submit_screening" value="Complete And Submit For Screening"/>
						                                    </div>
						                                </div>
						                            	</form>
						                            </div>
						                            <div id="w2-kycScreeningCorporate" class="tab-pane">
						                                <form id="kycScreeningCorporate-form">
                            							<input type="hidden" class="form-control input-sm" id="corporate_officer_id" name="corporate_officer_id" value="">
						                                <input type="hidden" class="form-control input-sm" id="customer_id" name="customer_id" value="">
						                                <input type="hidden" class="form-control input-sm" id="record_id" name="record_id" value="">
						                                <h6 class="kycScreeningMainCSS-heading">Note: Mandatory fields are marked with an asterisk (<span class="color_red">*</span>). If unsure, choose UNKNOWN.</h6>
						                                <h6 class="kycScreeningMainCSS-heading">
						                                	<div class="checkbox">
													          <label>
													          	<input type="hidden" name="corporate_customer_active" value="0">
													            <input type="checkbox" value="1" checked="checked" name="corporate_customer_active" id="corporate_customer_active">
													            <span class="checkbox_text">Customer is active <span class="color_red">*</span></span>
													          </label>
													        </div>
													        <div class="checkbox">
													          <label>
													          	<input type="hidden" name="corporate_company_incorp" value="0">
													            <input type="checkbox" value="1" checked="checked" name="corporate_company_incorp" id="corporate_company_incorp">
													            <span class="checkbox_text">Company has been incorporated <span class="color_red">*</span></span>
													          </label>
													        </div>
													    </h6>
													    <h4 style="margin-bottom: 10px; display: none" id="show_corp_status">
													    	Approval Status: <span id="corp_status"></span>
													    </h4>
						                                <div class="row kycScreeningMainCSS-form">
						                                	<div class="col-md-12">
							                                	<div class="form-group">
							                                		<label for="corporate_name">Company Name<span class="color_red">*</span></label>
							                                		<input style="text-transform:uppercase" type="text" id="corporate_name" class="form-control" placeholder="Company Name" value="" name="corporate_name" required="required"/>
							                                	</div>
							                                </div>
							                                <div class="col-md-12" style="padding-right: 0;">
						                                		<div class="col-md-6" style="padding-left: 0;">
							                                        <div class="form-group kycInputFieldMargin">
							                                        	<label for="corporate_entity_type">Entity Type<span class="color_red">*</span></label>
							                                            <select class="form-control" id="corporate_entity_type" name="corporate_entity_type">
							                                            	<option class="hidden" selected disabled>Please select Entity Type</option>
							                                            </select>
							                                        </div>
							                                        <div class="form-group kycInputFieldMargin">
							                                        	<label for="corporate_country_of_incorporation">Country of Incorporation<span class="color_red">*</span></label>
							                                            <select class="form-control" id="corporate_country_of_incorporation" name="corporate_country_of_incorporation">
							                                            	<option class="hidden" selected disabled>Please select Country of Incorporation</option>
							                                            </select>
							                                        </div>
							                                    </div>
							                                    <div class="col-md-6" style="padding-left: 0">
							                                    	<div class="form-group kycInputFieldMargin">
							                                        	<label for="corporate_ownership_structure_layer">Ownership Structure Layer<span class="color_red">*</span></label>
							                                            <select class="form-control" id="corporate_ownership_structure_layer" name="corporate_ownership_structure_layer">
							                                            	<option class="hidden" selected disabled>Please select Ownership Structure Layer</option>
							                                            </select>
							                                        </div>
							                                        <div class="form-group kycInputFieldMargin">
							                                        	<label for="corporate_date_of_incorporation">Date of Incorporation</label>
							                                            <div class="input-group mb-md" style="width: 100%;">
																			<span class="input-group-addon">
																				<i class="far fa-calendar-alt"></i>
																			</span>
																			<input type="text" class="form-control input-xs" id="corporate_date_of_incorporation" name="corporate_date_of_incorporation" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="DD/MM/YYYY">
																		</div>
							                                        </div>
							                                    </div>
						                                	</div>
						                                	<div class="col-md-12" style="padding-right: 0;">
						                                		<div class="col-md-6" style="padding-left: 0;">
							                                        <div class="form-group kycInputFieldMargin">
							                                        	<label for="corporate_country_of_major_operation">Country of Major Operation<span class="color_red">*</span></label>
							                                            <select class="form-control" id="corporate_country_of_major_operation" name="corporate_country_of_major_operation">
							                                            	<option class="hidden" selected disabled>Please select Country of Major Operation</option>
							                                            </select>
							                                        </div>
							                                        
							                                    </div>
							                                    <div class="col-md-6" style="padding-left: 0;">
							                                        <div class="form-group kycInputFieldMargin">
							                                        	<label for="corporate_primary_business_activity">Primary Business Activity<span class="color_red">*</span></label>
							                                            <select class="form-control" id="corporate_primary_business_activity" name="corporate_primary_business_activity">
							                                            	<option class="hidden" selected disabled>Please select Primary Business Activity</option>
							                                            </select>
							                                        </div>
							                                    </div>
						                                	</div>
						                                	<div class="col-md-12" style="padding-right: 0;">
						                                		<div class="col-md-6" style="padding-left: 0">
						                                			<div class="form-group kycInputFieldMargin">
							                                            <label for="corporate_onboarding_mode">Onboarding Mode<span class="color_red">*</span></label>
							                                            <select class="form-control" id="corporate_onboarding_mode" name="corporate_onboarding_mode">
							                                            	<option class="hidden" selected disabled>Please select Onboarding Mode</option>
							                                            </select>
							                                        </div>
							                                        <div class="form-group kycInputFieldMargin">
							                                            <label for="corporate_product_service_complexity">Product Service Complexity<span class="color_red">*</span></label>
							                                            <select class="form-control" id="corporate_product_service_complexity" name="corporate_product_service_complexity">
							                                            	<option class="hidden" selected disabled>Please select Product Service Complexity</option>
							                                            </select>
							                                        </div>
						                                		</div>
						                                		<div class="col-md-6" style="padding-left: 0">
							                                    	<div class="form-group kycInputFieldMargin">
							                                            <label for="corporate_payment_mode">Payment Mode<span class="color_red">*</span></label>
							                                            <div class="multiselect_div" style="width: 100%">
								                                            <select id="corporate_payment_mode" multiple="multiple" name="corporate_payment_mode[]">
								                                            </select>
							                                        	</div>
							                                        </div>
							                                        <!-- <div class="form-group kycInputFieldMargin">
							                                            <label for="corporate_payment_mode">Payment Mode</label>
							                                            <select class="form-control" id="corporate_payment_mode" name="corporate_payment_mode">
							                                            </select>
							                                        </div> -->
							                                        <div class="form-group kycInputFieldMargin">
							                                            <label for="corporate_source_of_funds">Source of Funds</label>
							                                            <select class="form-control" id="corporate_source_of_funds" name="corporate_source_of_funds">
							                                            	<option class="hidden" selected disabled>Please select Source of Funds</option>
							                                            </select>
							                                        </div>
							                                        <div class="other_corp_identity_document_type_div" style="display: none;">
									                                    <div style="padding-right:0px;">
									                                    	<div class="form-group kycInputFieldMargin">
									                                            <label for="corporate_other_source_of_funds">Other Source of Funds</label>
									                                            <textarea style="text-transform:uppercase" class="form-control" rows="5" id="corporate_other_source_of_funds" name="corporate_other_source_of_funds" placeholder="Other Source of Funds"></textarea>
									                                        </div>
									                                    </div>
									                                </div>
							                                    </div>
						                                	</div>
						                                	<div class="col-md-12" style="padding-right: 0;">
						                                		<div class="col-md-6" style="padding-left: 0">
						                                			<div class="form-group kycInputFieldMargin">
							                                        	<label for="reference_number">Reference Number</label>
							                                            <input style="text-transform:uppercase" type="text" id="corp_reference_number" class="form-control" placeholder="Reference Number" value="" name="reference_number"/>
							                                        </div>
						                                		</div>
						                                		<div class="col-md-6" style="padding-left: 0">
						                                			<div class="form-group kycInputFieldMargin">
							                                        	<label for="incorporation_number">Incorporation Number</label>
							                                            <input style="text-transform:uppercase" type="text" id="incorporation_number" class="form-control" placeholder="Incorporation Number" value="" name="incorporation_number"/>
							                                        </div>
						                                		</div>
						                                	</div>
						                                	<div class="col-md-12" style="padding-right: 0;">
							                                	<div class="col-md-6" style="padding-left: 0">
							                                    	<div class="form-group kycInputFieldMargin">
							                                        	<label for="corporate_address">Address</label>
							                                        	<div class="input-group fieldGroup_corporate_address" style="display: block !important;width: 100%">
																			<input type="text" class="form-control input-xs check_empty_corporate_address main_corporate_address" id="corporate_address" name="corporate_address[]" value="" style="text-transform:uppercase; width:80%;"/>

																			<!-- <label class="radio-inline control-label" style="margin-top: 5px; margin-left: 20px;"><input type="radio" class="corporate_address_primary main_corporate_address_primary" name="corporate_address_primary" value="1" checked> Primary</label> -->

																			<input class="btn btn-primary button_increment_corporate_address addMore_corporate_address" type="button" value="+" style="margin-left: 10px; border-radius: 3px;visibility: hidden; width: 35px;"/>

																			<button type="button" class="btn btn-default btn-sm show_corporate_address" style="margin-left: 10px; visibility: hidden;">
																				  <span class="fa fa-arrow-down" aria-hidden="true"></span><!-- &nbsp<span class="toggle_word">Show more</span> -->
																			</button>
																		</div>

																		<div class="local_corporate_address_toggle">
																		</div>

																		<div class="input-group fieldGroupCopy_corporate_address corporate_address_disabled" style="display: none;">
																			<input type="text" class="form-control input-xs check_empty_corporate_address second_corporate_address" id="second_corporate_address" name="corporate_address[]" value="" style="text-transform:uppercase;width:80%;"/>
																			<!-- <label class="radio-inline control-label" style="margin-top: 5px; margin-left: 20px;"><input type="radio" class="corporate_address_primary" name="corporate_address_primary" value="1"> Primary</label> -->

																			<input class="btn btn-primary button_decrease_corporate_address remove_corporate_address" type="button" value="-" style="margin-left: 10px; border-radius: 3px; width: 35px;"/>
																		</div>
							                                        </div>
							                                        <div class="form-group kycInputFieldMargin">
							                                        	<label for="corporate_email_address">Email Address</label>
							                                        	<div class="input-group fieldGroup_corporate_email_address" style="display: block !important;width: 100%">
																			<input type="text" class="form-control input-xs check_empty_corporate_email_address main_corporate_email_address" id="corporate_email_address" name="corporate_email_address[]" value="" style="text-transform:uppercase; width:80%;"/>

																			<!-- <label class="radio-inline control-label" style="margin-top: 5px; margin-left: 20px;"><input type="radio" class="corporate_email_address_primary main_corporate_email_address_primary" name="corporate_email_address_primary" value="1" checked> Primary</label> -->

																			<input class="btn btn-primary button_increment_corporate_email_address addMore_corporate_email_address" type="button" value="+" style="margin-left: 10px; border-radius: 3px;visibility: hidden; width: 35px;"/>

																			<button type="button" class="btn btn-default btn-sm show_corporate_email_address" style="margin-left: 10px; visibility: hidden;">
																				  <span class="fa fa-arrow-down" aria-hidden="true"></span><!-- &nbsp<span class="toggle_word">Show more</span> -->
																			</button>
																		</div>

																		<div class="local_corporate_email_address_toggle">
																		</div>

																		<div class="input-group fieldGroupCopy_corporate_email_address corporate_email_address_disabled" style="display: none;">
																			<input type="text" class="form-control input-xs check_empty_corporate_email_address second_corporate_email_address" id="second_corporate_email_address" name="corporate_email_address[]" value="" style="text-transform:uppercase;width:80%;"/>
																			<!-- <label class="radio-inline control-label" style="margin-top: 5px; margin-left: 20px;"><input type="radio" class="corporate_email_address_primary" name="corporate_email_address_primary" value="1"> Primary</label> -->

																			<input class="btn btn-primary button_decrease_corporate_email_address remove_corporate_email_address" type="button" value="-" style="margin-left: 10px; border-radius: 3px; width: 35px;"/>
																		</div>
							                                        </div>
							                                    </div>
							                                    <div class="col-md-6" style="padding-left: 0">
							                                    	<div class="form-group kycInputFieldMargin">
							                                        	<label for="corporate_phone_number">Phone Number</label>
							                                        	<div class="input-group fieldGroup_corporate_phone_number" style="width: 100%;">
																			<input type="tel" class="form-control check_empty_corporate_phone_number main_corporate_phone_number hp" id="corporate_phone_number" name="corporate_phone_number[]" value=""/>

																			<input type="hidden" class="form-control input-xs hidden_corporate_phone_number main_hidden_corporate_phone_number" id="hidden_corporate_phone_number" name="hidden_corporate_phone_number[]" value=""/>

																			<!-- <label class="radio-inline control-label" style="margin-top: -30px; margin-left: 20px;"><input type="radio" class="corporate_phone_number_primary main_corporate_phone_number_primary" name="corporate_phone_number_primary" value="1" checked> Primary</label> -->

																			<input class="btn btn-primary button_increment_corporate_phone_number addMore_corporate_phone_number" type="button" value="+" style="margin-left: 10px; margin-top: -26px;border-radius: 3px;visibility: hidden; width: 35px;"/>

																			<button type="button" class="btn btn-default btn-sm show_corporate_phone_number" style="margin-left: 10px; margin-top: -23px; visibility: hidden;">
																				  <span class="fa fa-arrow-down" aria-hidden="true"></span><!-- &nbsp<span class="toggle_word">Show more</span> -->
																			</button>
																		</div>

																		<div class="corporate_phone_number_toggle">
																		</div>


																		<div class="input-group fieldGroupCopy_corporate_phone_number corporate_phone_number_disabled" style="display: none; width: 100%;">
																			<input type="tel" class="form-control input-xs check_empty_corporate_phone_number second_corporate_phone_number second_hp" id="second_corporate_phone_number" name="corporate_phone_number[]" value=""/>

																			<input type="hidden" class="form-control input-xs hidden_corporate_phone_number" name="hidden_corporate_phone_number[]" value=""/>

																			<!-- <label class="radio-inline control-label" style="margin-top: -30px; margin-left: 20px;"><input type="radio" class="corporate_phone_number_primary" name="corporate_phone_number_primary" value="1"> Primary</label> -->

																			<input class="btn btn-primary button_decrease_corporate_phone_number remove_corporate_phone_number" type="button" value="-" style="margin-left: 10px; margin-top: -26px;border-radius: 3px; width: 35px;"/>
																		</div>
							                                        </div>
							                                        <div class="form-group kycInputFieldMargin">
							                                        	<label for="corporate_bank_account">Bank Account</label>
							                                        	<div class="input-group fieldGroup_corporate_bank_account" style="display: block !important;width: 100%">
																			<input type="text" class="form-control input-xs check_empty_corporate_bank_account main_corporate_bank_account" id="corporate_bank_account" name="corporate_bank_account[]" value="" style="text-transform:uppercase; width:80%;"/>

																			<!-- <label class="radio-inline control-label" style="margin-top: 5px; margin-left: 20px;"><input type="radio" class="corporate_bank_account_primary main_corporate_bank_account_primary" name="corporate_bank_account_primary" value="1" checked> Primary</label> -->

																			<input class="btn btn-primary button_increment_corporate_bank_account addMore_corporate_bank_account" type="button" value="+" style="margin-left: 10px; border-radius: 3px;visibility: hidden; width: 35px;"/>

																			<button type="button" class="btn btn-default btn-sm show_corporate_bank_account" style="margin-left: 10px; visibility: hidden;">
																				  <span class="fa fa-arrow-down" aria-hidden="true"></span><!-- &nbsp<span class="toggle_word">Show more</span> -->
																			</button>
																		</div>

																		<div class="local_corporate_bank_account_toggle">
																		</div>

																		<div class="input-group fieldGroupCopy_corporate_bank_account corporate_bank_account_disabled" style="display: none;">
																			<input type="text" class="form-control input-xs check_empty_corporate_bank_account second_corporate_bank_account" id="second_corporate_bank_account" name="corporate_bank_account[]" value="" style="text-transform:uppercase;width:80%;"/>
																			<!-- <label class="radio-inline control-label" style="margin-top: 5px; margin-left: 20px;"><input type="radio" class="corporate_bank_account_primary" name="corporate_bank_account_primary" value="1"> Primary</label> -->

																			<input class="btn btn-primary button_decrease_corporate_bank_account remove_corporate_bank_account" type="button" value="-" style="margin-left: 10px; border-radius: 3px; width: 35px;"/>
																		</div>
							                                        </div>
							                                    </div>
							                                </div>
						                                    <div class="col-md-12">
						                                    	<div class="form-group">
						                                    		<label for="corporate_nature_of_business_relationship">Nature of Business Relationship</label>
						                                    		<textarea class="form-control" rows="5" id="corporate_nature_of_business_relationship" name="corporate_nature_of_business_relationship" placeholder="Nature of Business Relationship"></textarea>
						                                    	</div>
						                                    </div>
															<div class="col-md-6">
						                                    </div>
															<div class="col-md-6 text-right">
						                                    	<input type="button" class="btn btn-primary corporate_kyc_submit corp_save_as_draft_button" value="Save as Draft" style="display: none"/>
						                                    	<input type="button" class="btn btn-primary corporate_kyc_submit corp_update_button" value="Update" style="display: none"/>
						                                    	<input type="button" class="btn btn-primary corporate_kyc_submit_screening" value="Complete And Submit For Screening"/>
						                                    </div>
						                                </div>
						                            	</form>
						                            </div>
						                        <!-- </div> -->
						                    </div>
						                </div>
						            </div>
								</div>
								<div id="w2-screening" class="tab-pane">
									<!-- <div class="container"> -->
										<div class="row" style="margin-right: 0px;">
											<div class="col-md-12" style="padding-right: 0px;">
												<input type="hidden" id="customer_id">
												<input type="hidden" id="refreshKYCInfo" value="false">
												<input type="button" class="btn btn-primary downloadRiskReport" id="downloadRiskReport" name="downloadRiskReport" value="Download Report" style="float: right;margin-bottom: 10px; cursor: pointer;" data-toggle="tooltip" data-placement="top" title="Download Customer Acceptance Form & Detailed Report"/>

												<input type="button" class="btn btn-primary refreshRiskReport" id="refreshRiskReport" name="refreshRiskReport" value="Refresh" style="float: right;margin-bottom: 10px; margin-right: 10px; cursor: pointer;" data-toggle="tooltip" data-placement="top" title="Refresh after update screening conclusion"/>
											</div>
										</div>
									<!-- </div> -->
									<table class="table table-bordered table-striped table-condensed mb-none datatable-screening" id="datatable-screening">
										<thead>
											<tr>
												<th>Updated At</th>
												<th>Updated By</th>
												<th>Risk Score</th>
												<th>Computed Risk Rating</th>
												<th>Override Risk Rating</th>
												<th>Approval Status</th>
												<th>Risk Report</th>
												<!-- <th>Transaction Type</th>
												<th>Remarks</th>
												<th>Status</th> -->
											</tr>
										</thead>
										<tbody id="service_body">
											<!-- <?php
											$i = 1;
												foreach($transaction as $a)
												{
													echo '<tr>
															<td>'.$i.'</td>
															<td>'.($a->client_name != NULL ? $a->client_name : $a->company_name).'</td>
															<td><span style="display:none">'.date("Ymd",strtotime($a->created_at)).'</span>'.date("d F Y",strtotime($a->created_at)).'</td>
															<td><span style="display:none">'.date("Ymd",strtotime($a->effective_date)).'</span>'.$a->effective_date.'</td>
															<td>'.$a->transaction_code.'</td>
															<td>
															<a class="'.(($a->status == 4 || $a->status == 5)?'link_disabled':'').'" href="'.site_url("transaction/edit/".$a->id).'" style="cursor:pointer" data-toggle="tooltip" data-trigger="hover" data-original-title="Edit This Transaction">'.$a->transaction_task.'</a>
															</td>
															<td>'.$a->remarks.'</td>
															<td>'.$a->transaction_status.'</td>
															
														</tr>';
													$i++;
												}
											?> -->
											
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<!-- The KYC login modal. Don't display it initially -->
<form id="loginForm" method="post" class="form-horizontal" style="display: none;">
	<input type="hidden" class="form-control" name="screening" />
    <div class="form-group">
        <label class="col-sm-3 control-label">Username</label>
        <div class="col-sm-5">
            <input type="text" class="form-control" name="username" />
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label">Password</label>
        <div class="col-sm-5">
            <input type="password" class="form-control" name="password" />
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-5 col-sm-offset-3">
            <button type="submit" class="btn btn-primary">Login</button>
        </div>
    </div>
</form>

<!-- The KYC scan modal. Don't display it initially -->
<form id="ScanForm" method="post" class="form-horizontal" style="display: none;">
	<input type="hidden" class="form-control" name="accessToken" />
	<input type="hidden" class="form-control" name="record_id" />
	<input type="hidden" class="form-control" name="customer_id" />
	<h6>
		<div class="checkbox">
	      <label>
	        <input type="checkbox" value="WORLD-CHECK" name="checkbox_scan[]" id="checkbox_scan1">
	        <span class="checkbox_text">WORLD-CHECK</span>
	      </label>
	    </div>
	</h6>
	<h6>
	    <div class="checkbox">
	      <label>
	        <input type="checkbox" value="INTERNET SEARCH" checked="checked" name="checkbox_scan[]" id="checkbox_scan2">
	        <span class="checkbox_text">INTERNET SEARCH</span>
	      </label>
	    </div>
	</h6>
    <h6>
	    <div class="checkbox">
	      <label>
	        <input type="checkbox" value="OWN RESTRICTED LIST" checked="checked" name="checkbox_scan[]" id="checkbox_scan3">
	        <span class="checkbox_text">OWN RESTRICTED LIST</span>
	      </label>
	    </div>
	</h6>
	<h6>
	    <div class="checkbox">
	      <label>
	        <input type="checkbox" value="ARTEMISCAN" checked="checked" name="checkbox_scan[]" id="checkbox_scan4">
	        <span class="checkbox_text">ARTEMISCAN</span>
	      </label>
	    </div>
	</h6>
    <div class="form-group">
        <div class="col-sm-12" >
            <button type="button" class="btn btn-primary pull-right" id="start_to_scan">Scan</button>
        </div>
    </div>
</form>

<!-- The KYC risk report modal. Don't display it initially -->
<div class="risk_report_table" style="display: none;">
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Category</th>
				<th>Criteria</th>
				<th>Weight</th>
				<th>Score</th>
			</tr>
		</thead>
		<tbody class="risk_report_table_body">

		</tbody>
	</table>
	<div class="form-group">
        <div class="col-sm-12" >
	       <span class="pull-right generate_by" style="font-style: italic; font-size: 10px;"></span>
	    </div>
    </div>
</div>
<!-- end: page -->
<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>
<script src="themes/default/assets/js/nationality.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
<script src="themes/default/assets/js/intlTelInput.js?v=30eee4fc8d1b59e4584b0d39edfa2082" /></script>
<script src="themes/default/assets/js/defaultCountryIp.js?v=30eee4fc8d1b59e4584b0d39edfa2082" /></script>
<script src="themes/default/assets/js/utils.js?v=30eee4fc8d1b59e4584b0d39edfa2082" /></script>
<script>
	$("#header_our_firm").removeClass("header_disabled");
	$("#header_manage_user").removeClass("header_disabled");
	$("#header_access_right").removeClass("header_disabled");
	$("#header_user_profile").removeClass("header_disabled");
	$("#header_setting").removeClass("header_disabled");
	$("#header_dashboard").removeClass("header_disabled");
	$("#header_client").removeClass("header_disabled");
	$("#header_person").addClass("header_disabled");
	$("#header_document").removeClass("header_disabled");
	$("#header_report").removeClass("header_disabled");
	$("#header_billings").removeClass("header_disabled");

	var corp_rep_data = <?php echo json_encode($corp_rep_data); ?>;
	var access_right_person_module = <?php echo json_encode($person_module);?>;
	var non_verify = <?php echo json_encode($person->non_verify)?>;

	var base_url = '<?php echo base_url() ?>';
	var person = <?php echo json_encode($person); ?>;
	var get_kyc_individual_info = <?php echo json_encode($get_kyc_individual_info); ?>;
	var get_kyc_corporate_info = <?php echo json_encode($get_kyc_corporate_info); ?>;
	var files = <?php echo json_encode($person->files); ?>;
	var company_files = <?php echo json_encode($person->company_files); ?>;
	var officer_fixed_line_no = <?php echo json_encode($person->officer_fixed_line_no); ?>;
	var officer_mobile_no = <?php echo json_encode($person->officer_mobile_no); ?>;
	var officer_email = <?php echo json_encode($person->officer_email); ?>;

	var officer_company_email = <?php echo json_encode($person->officer_company_email); ?>;
	var officer_company_phone_number = <?php echo json_encode($person->officer_company_phone_number); ?>;
	
	var date = new Date();

    var initialPreviewArray = []; 
	var initialPreviewConfigArray = [];

	var tab_aktif = "<?php print $person->field_type; ?>";
	var person_tab_aktif = "customerProfile";

	var user_pool_id = <?php echo json_encode($artemis_user_pool_id);?>;
    var client_id = <?php echo json_encode($artemis_client_id);?>;
    var REGION = <?php echo json_encode($artemis_region);?>;
    //var username = <?php echo json_encode($artemis_username);?>;
    //var password = <?php echo json_encode($artemis_password);?>;
    var riskReport;
    var individual_reload_link = false;
    var corporate_reload_link = false;

// '{"individualRecords":[{"aliasNames":['+sub_alias+'],"title":"'+sub_salutation+'","name":"'+sub_individual_name+'","nationality":"'+sub_individual_nationality+'","countryOfResidence":"'+sub_individual_country_of_residence+'","gender":"'+sub_gender+'","dateOfBirth":'+sub_new_individual_date_of_birth+',"industry":"'+sub_individual_industry+'","occupation":"'+sub_individual_occupation+'","addresses":'+string_filtered_sub_address_value+',"phoneNumbers":'+string_filtered_sub_contact_no_value+',"referenceId":"'+sub_reference_number+'","sourceOfFunds":"'+sub_individual_source_of_funds+'","emailAddresses":'+string_filtered_sub_email_address_value+',"bankAccounts":'+string_filtered_sub_bank_account_value+',"countryOfBirth":"'+sub_individual_country_of_birth+'","idType":"'+sub_individual_identity_document_type+'","idNumber":"'+sub_identity_number+'","primary":true}],"users":["'+user_kyc_id+'"],"domains":[1],"paymentModes":'+sub_individual_payment_mode+',"onboardingMode":"'+sub_individual_onboarding_mode+'","productServiceComplexity":"'+sub_individual_product_service_complexity+'","natureOfBusinessRelationship":"'+sub_individual_nature_of_business_relationship+'","referenceId":"'+sub_reference_number+'","isActiveCustomer":'+sub_individual_active+'}';
        
	
// '{"corporateRecords": [{"isIncorporated": '+sub_corporate_company_incorp+',"name": "'+sub_corporate_name+'","entityType": "'+sub_corporate_entity_type+'","ownershipStructureLayers": "'+sub_corporate_ownership_structure_layer+'","countryOfIncorporation": "'+sub_corporate_country_of_incorporation+'","countryOfOperations": "'+sub_corporate_country_of_major_operation+'","businessActivity": "'+sub_corporate_primary_business_activity+'","addresses": '+string_filtered_sub_address_value+',"phoneNumbers": '+string_filtered_sub_contact_no_value+',"sourceOfFunds": "'+sub_corporate_source_of_funds+'","emailAddresses": '+string_filtered_sub_email_address_value+',"bankAccounts": '+string_filtered_sub_bank_account_value+',"incorporationNumber": "'+sub_incorporation_number+'","incorporationDate": "'+sub_new_corporate_date_of_incorporation+'","primary": true}],"isActiveCustomer": '+sub_corporate_active+',"referenceId": '+sub_reference_number+',"paymentModes": '+sub_corporate_payment_mode+',"onboardingMode": "'+sub_corporate_onboarding_mode+'","productServiceComplexity": "'+sub_corporate_product_service_complexity+'","natureOfBusinessRelationship": "'+sub_corporate_nature_of_business_relationship+'","users": ["'+user_kyc_id+'"],"domains": [1]}'
	//-----------------------------------Test---------------------------------------
	// var user_pool_id = <?php echo json_encode($artemis_user_pool_id);?>;
 //    var client_id = <?php echo json_encode($artemis_client_id);?>;
	// var poolData = {
 //        UserPoolId: user_pool_id,
 //        ClientId: client_id
 //    };

 //    var userPool = new AmazonCognitoIdentity.CognitoUserPool(poolData);

	// var cognitoUser = userPool.getCurrentUser();

	// console.log(cognitoUser);

	// cognitoUser.getSession(function(err, session) {
	//     if (err) {
	//       alert(err.message || JSON.stringify(err));
	//       return;
	//     }
	//     console.log(session.isValid());
 //    });

 //    console.log(localStorage.getItem("accessToken"));

 //    $.ajax({
	//     url: 'https://a2-acumenalphaadvisory-uat-be.cynopsis.co/client/records/countries/',
	//     headers: {
	//     	'Content-Type': "application/json",
	// 		'X-ARTEMIS-DOMAIN': "1",
	//         'Authorization': 'Bearer '+ localStorage.getItem("accessToken"),
	//     },
	//     method: 'GET',
	//     //data: YourData,
	//     success: function(data){
	//       console.log('succes: '+data);
	//     }
	// });

	
	//var YourData = '{"individualRecords":[{"aliasNames":["john steward"],"title":"MR","name":"CHAN CHANDDSS","nationality":"SINGAPORE","countryOfResidence":"SINGAPORE","gender":"MALE","dateOfBirth":"1991-06-02T17:00:00.000Z","industry":"[2018] 01119 - GROWING OF FOOD CROPS (NON-HYDROPONICS) N.E.C.","occupation":"DRIVER","addresses":[],"phoneNumbers":[],"referenceId":"","sourceOfFunds":"LOTTERY/WINDFALL","emailAddresses":[],"bankAccounts":[],"countryOfBirth":"SINGAPORE","idType":"NATIONAL ID","idNumber":"4918405717","primary":true}],"users":["4a1d45db-d44a-44f9-97f1-bf548437a466"],"domains":[1],"paymentModes":["VIRTUAL CURRENCY"],"onboardingMode":"NON FACE-TO-FACE","productServiceComplexity":"SIMPLE","natureOfBusinessRelationship":"Nature of business relationship","referenceId":"","isActiveCustomer":true}';

	//{"individualRecords":
// 	[{
// 		"aliasNames":["john steward"],
// 		"title":"MR",
// 		"name":"CHAN CHANDDSS",
// 		"nationality":"SINGAPORE",
// 		"countryOfResidence":"SINGAPORE",
// 		"gender":"MALE",
// 		"dateOfBirth":"1991-06-02T17:00:00.000Z",
// 		"industry":"[2018] 01119 - GROWING OF FOOD CROPS (NON-HYDROPONICS) N.E.C.",
// 		"occupation":"DRIVER",
// 		"addresses":[],
// 		"phoneNumbers":[],
// 		"referenceId":"",
// 		"sourceOfFunds":"LOTTERY/WINDFALL",
// 		"emailAddresses":[],
// 		"bankAccounts":[],
// 		"countryOfBirth":"SINGAPORE",
// 		"idType":"NATIONAL ID",
// 		"idNumber":"4918405717",
// 		"primary":true
// 	}],
// 	"users":["4a1d45db-d44a-44f9-97f1-bf548437a466"],
// 	"domains":[1],
// 	"paymentModes":["VIRTUAL CURRENCY"],
// 	"onboardingMode":"NON FACE-TO-FACE",
// 	"productServiceComplexity":"SIMPLE",
// 	"natureOfBusinessRelationship":"Nature of business relationship",
// 	"referenceId":"",
// 	"isActiveCustomer":true
// }

	// var YourData = '{"individualRecords": [{"id":11,"title": "MR","name": "UUUUSSSSSUUUU","aliasNames": ["john steward"],"nationality": "SINGAPORE","countryOfResidence": "SINGAPORE","gender": "MALE","dateOfBirth": "1991-06-02T17:00:00.000Z","industry": "[2018] 01119 - GROWING OF FOOD CROPS (NON-HYDROPONICS) N.E.C.","occupation": "DRIVER","addresses": [],"phoneNumbers": [],"sourceOfFunds": "LOTTERY/WINDFALL","emailAddresses": [],"bankAccounts": [],"referenceId":"","countryOfBirth": "SINGAPORE","idType": "NATIONAL ID","idNumber": "4918405717","primary": true}],"isActiveCustomer": true,"paymentModes": ["VIRTUAL CURRENCY"],"onboardingMode": "NON FACE-TO-FACE","productServiceComplexity": "SIMPLE","natureOfBusinessRelationship": "Nature of business relationship","users": ["4a1d45db-d44a-44f9-97f1-bf548437a466"],"domains": [1]}';

	// {"individualRecords": 
	// 	[{
	// 		"id":11,
	// 		"title": "MR",
	// 		"name": "UUUUSSSSSUUUU",
	// 		"aliasNames": ["john steward"],
	// 		"nationality": "SINGAPORE",
	// 		"countryOfResidence": "SINGAPORE",
	// 		"gender": "MALE",
	// 		"dateOfBirth": "1991-06-02T17:00:00.000Z",
	// 		"industry": "[2018] 01119 - GROWING OF FOOD CROPS (NON-HYDROPONICS) N.E.C.",
	// 		"occupation": "DRIVER",
	// 		"addresses": [],
	// 		"phoneNumbers": [],
	// 		"sourceOfFunds": "LOTTERY/WINDFALL",
	// 		"emailAddresses": [],
	// 		"bankAccounts": [],
	// 		"referenceId":"",
	// 		"countryOfBirth": "SINGAPORE",
	// 		"idType": "NATIONAL ID",
	// 		"idNumber": "4918405717",
	// 		"primary": true
	// 	}],
	// 	"isActiveCustomer": true,
	// 	"paymentModes": ["VIRTUAL CURRENCY"],
	// 	"onboardingMode": "NON FACE-TO-FACE",
	// 	"productServiceComplexity": "SIMPLE",
	// 	"natureOfBusinessRelationship": "Nature of business relationship",
	// 	"users": ["4a1d45db-d44a-44f9-97f1-bf548437a466"],
	// 	"domains": [1]
	// }
	

	// $.ajax({
	//     url: 'https://a2-acumenalphaadvisory-uat-be.cynopsis.co/client/customers/',
	//     headers: {
	//     	'Content-Type': "application/json",
	// 		'X-ARTEMIS-DOMAIN': "1",
	//         'Authorization': 'Bearer '+ localStorage.getItem("accessToken"),
	//     },
	//     method: 'POST',
	//     data: YourData,
	//     success: function(data){
	//       console.log('succes: '+data);
	//     }
	// });

	// $.ajax({
	//     url: 'https://a2-acumenalphaadvisory-uat-be.cynopsis.co/client/customers/15/',
	//     headers: {
	//     	'Content-Type': "application/json",
	// 		'X-ARTEMIS-DOMAIN': "1",
	//         'Authorization': 'Bearer '+ localStorage.getItem("accessToken"),
	//     },
	//     method: 'PATCH',
	//     data: YourData,
	//     success: function(data){
	//       console.log('succes: '+data);
	//     }
	// });

	// var result = {"id":9,"createdBy":{"id":"4a1d45db-d44a-44f9-97f1-bf548437a466","name":"Justin","email":"justin@aaa-global.com","oauthId":"4a1d45db-d44a-44f9-97f1-bf548437a466","isActive":true,"mfaEnabled":false},"updatedBy":{"id":"4a1d45db-d44a-44f9-97f1-bf548437a466","name":"Justin","email":"justin@aaa-global.com","oauthId":"4a1d45db-d44a-44f9-97f1-bf548437a466","isActive":true,"mfaEnabled":false},"name":"CLIENT API","riskRating":"UNKNOWN","status":"DRAFT","customerType":"INDIVIDUAL","createdAt":"2019-12-17T08:06:36.618802+08:00","updatedAt":"2019-12-17T08:06:36.657070+08:00","onboardingMode":"NON FACE-TO-FACE","productServiceComplexity":"SIMPLE","paymentModes":["VIRTUAL CURRENCY"],"profileType":"","isActiveCustomer":true,"natureOfBusinessRelationship":"Nature of business relationship","referenceId":"111","domains":[1],"users":["4a1d45db-d44a-44f9-97f1-bf548437a466"]}
</script>
<script src="themes/default/assets/js/addpersonprofile.js?v=40eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
<script src="themes/default/assets/js/add_individual_person_profile.js?v=40eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
<script src="themes/default/assets/js/add_company_person_profile.js?v=40eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
<script src="themes/default/assets/js/kyc_individual_and_corporate.js?v=40eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
<script src="themes/default/assets/js/kyc_individual.js?v=90eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
<script src="themes/default/assets/js/kyc_corporate.js?v=50eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>