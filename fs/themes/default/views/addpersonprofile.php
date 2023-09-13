<div class="header_between_all_section">
<section class="panel">
	<?php echo $breadcrumbs;?>
	<div class="panel-body">
		<div class="col-md-12">
		
				<section class="panel" id="wPerson">
					<header class="panel-heading">
						<h2 class="panel-title"><?=$page_name?></h2>
					</header>
					
					<div class="panel-body">
						<!-- <table class="table table-bordered table-striped table-condensed mb-none" > -->
							
						<!-- </table> -->
						<!-- <?php echo json_encode($person); ?> -->
						<!-- <form>
						<table class="table table-bordered table-striped table-condensed mb-none" >
							
							<tr>
								<th style="width: 200px">Fixed Line No</th>
								<td class="added-text-field">

									<div class="input-group fieldGroup_local_fix_line">

										<input type="tel" class="form-control input-xs check_empty_local_fix_line main_local_fix_line hp" id="local_fix_line" name="local_fix_line[]" value=""/>

										<label class="radio-inline control-label" style="margin-top: -30px; margin-left: 20px;"><input type="radio" name="fixed_line_no_primary" value="1"> Primary</label>
											 
										
										<div id="form_local_fix_line"></div>
										<span class="input-group-btn">
											<input class="btn btn-success button_increment_local_fix_line addMore_local_fix_line" type="button" id="create_button" value="+" style="border-radius: 3px;visibility: hidden; width: 35px;"/>
										</span>
									</div>


									<div class="input-group fieldGroupCopy_local_fix_line" style="display: none;">
										<input type="tel" class="form-control input-xs check_empty_local_fix_line second_local_fix_line hp" id="local_fix_line" name="local_fix_line[]" value=""/>

										<label class="radio-inline control-label" style="margin-top: -30px; margin-left: 20px;"><input type="radio" name="fixed_line_no_primary" value="1"> Primary</label>

										<div id="form_local_fix_line"></div>
										<span class="input-group-btn">
											<input class="btn btn-danger button_decrease_local_fix_line remove_local_fix_line" type="button" id="create_button" value="-" style="border-radius: 3px; width: 35px;"/>
										</span>
									</div>



								</td>
								
							</tr>
						
						</table>
						</form> -->
						<table class="table table-bordered table-striped table-condensed mb-none" >
							
								<tr>
									<th style="width: 200px">Type<!--  <?=$person->tipe?> --></th>
									<td>
										
										
											<label><input type="radio" id="individual_edit" name="field_type" <?php print $person->individual_status; ?> value="Individual" class="check_stat" data-information="individual" <?php if($person->individual_disabled=='true') echo ' disabled ';?>/>&nbsp;&nbsp;Individual</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											<label><input type="radio" id="company_edit" name="field_type" <?php print $person->company_status; ?> value="company" class="check_stat" data-information="company" <?php if($person->company_disabled=='true') echo ' disabled ';?>/>&nbsp;&nbsp;Company</label>
										
										
									</td>
								</tr>
							
						</table>

						<?php echo form_open_multipart('', array('id' => 'upload', 'enctype' => "multipart/form-data")); ?>
							<table class="table table-bordered table-striped table-condensed mb-none" id="tr_individual_edit" <?php print $person->individual_table; ?>>

								<!-- <?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
									echo form_open_multipart("personprofile/update", $attrib);
									
								?>  -->

								
								<input type="hidden" class="form-control input-sm" id="close_page" name="close_page" value="<?=$close_page?>">
								<input type="hidden" class="form-control input-sm" id="field_type" name="field_type" value="individual">
								<tr>
									<input type="hidden" class="form-control input-sm" id="officer_id" name="officer_id" value="<?=$person->id?>">
									<th>Identification Type</th>
									<td>
										
										<!--select data-plugin-selectTwo class="form-control populate"-->
										<select class="form-control" id="identification_type" name="identification_type">
												<option value="NRIC (Singapore citizen)" <?php if($person->identification_type=='NRIC (Singapore citizen)') echo ' Selected ';?>>NRIC (Singapore Citizen)</option>
												<option value="NRIC (PR)" <?php if($person->identification_type=='NRIC (PR)') echo ' Selected ';?>>NRIC (Singapore Permanent Resident)</option>
												<option value="FIN Number" <?php if($person->identification_type=='FIN Number') echo ' Selected ';?>>Passholders</option>
												<option value="Passport/ Others" <?php if($person->identification_type=='Passport/ Others') echo ' Selected ';?>>Passport/ Others</option>

										</select>
									</td>
								</tr>
								<tr>
									<th>Identification No.<!--  <?php print_r($person);?> --></th>
									<td>
										<!--div class="input-group col-md-8" style="float:left;">
											<input type="text" class="form-control input-sm" id="w2-username" name="username" required placeholder="Search ID">
											<span class="input-group-btn">
												<button class="btn btn-default" type="submit" style="height:30px;"><i class="fa fa-search"></i></button>
											</span>
										</div>
										<div class=" col-md-4"   style="padding:0px 0px 0px 5px;">
											<input type="file" class="form-control input-sm" id="w2-username" name="username" required placeholder="Upload">
										</div-->
										<!-- <div class="input-group col-md-8" style="float:left;"> -->
											<input type="hidden" class="form-control input-sm" id="old_identification_no" name="old_identification_no" style="text-transform:uppercase" value="<?=htmlspecialchars($person->identification_no, ENT_QUOTES)?>">
											<input type="hidden" class="form-control input-sm" id="company_register_no" name="company_register_no" style="text-transform:uppercase" value="<?=htmlspecialchars($person->register_no, ENT_QUOTES)?>">
											<input type="text" class="form-control input-xs" id="identification_no" name="identification_no" style="text-transform:uppercase" required value="<?=htmlspecialchars($person->identification_no, ENT_QUOTES)?>">
											<div id="form_identification_no"></div>
											<!-- <?php echo form_error('identification_no','<span class="help-block">*','</span>'); ?> -->
										<!-- </div> -->
										
									</td>				
								</tr>
								<tr>
									<th>Name</th>
									<td><input type="text" class="form-control input-xs" id="name" name="name" style="text-transform:uppercase" value="<?=htmlspecialchars($person->name, ENT_QUOTES)?>"/>
									<div id="form_name"></div>
										<!-- <?php echo form_error('name','<span class="help-block">*','</span>'); ?></td> -->
									</td>
								</tr>
								<tr>
									<th>Date Of Birth</th>
									<td><!-- <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" value="<?=$person->date_of_birth?>"> -->
										<div class="input-group mb-md" style="width: 200px;">
											<span class="input-group-addon">
												<i class="far fa-calendar-alt"></i>
											</span>
											<input type="text" class="form-control input-xs valid" id="date_of_birth" name="date_of_birth" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" required="" value="<?=$person->date_of_birth?>" placeholder="DD/MM/YYYY">
											<!-- <input type="text" id="date_todolist" class="form-control" name="date_incorporation" style="" data-plugin-datepicker data-date-format="dd/mm/yyyy" value="<?=$this->sma->fed($client->date_incorporation)?>" /> -->
											<?php //data-date-start-date="0d"?>
										</div>
										
									<div id="form_date_of_birth"></div>
										<!-- <?php echo form_error('date_of_birth','<span class="help-block">*','</span>'); ?></td> -->
									
									<!-- <td><input type="text" class="form-control" name="date_of_birth" data-plugin-datepicker data-date-format="dd/mm/yyyy" value="<?=$this->sma->fed($person->date_of_birth)?>"/></td> -->
									</td>
								</tr>
								<tr>
									<th style="width: 200px">Address</th>
									<td><label><input type="radio" id="local_edit" name="address_type" <?php print $person->local_status; ?> value="Local"/>&nbsp;&nbsp;Local Address</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<label><input type="radio" id="foreign_edit" name="address_type" <?php print $person->foreign_status; ?> value="Foreign"/>&nbsp;&nbsp;Foreign Address</label></td>
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
													
													<!-- <?php echo form_error('postal_code1','<span class="help-block">*','</span>'); ?> -->
													<!-- <span class="input-group-btn">
														<button class="btn btn-default" type="submit" style="height:30px;"><i class="fa fa-search"></i></button>
													</span> -->
												</div>

												<div id="form_postal_code1"></div>
											</div>
										</div>

										<div style="margin-bottom:5px;">
											<label style="width: 25%;float:left;margin-right: 20px;">Street Name :</label>
											<div style="width: 65%;float:left;margin-bottom:5px;">
												<div class="input-group" style="width: 84.5%;">
													<input style="width: 100%; text-transform:uppercase" type="text" class="form-control input-xs" id="street_name1" name="street_name1" value="<?=$person->street_name1?>">
													<!-- <?php echo form_error('street_name1','<span class="help-block">*','</span>'); ?> -->
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
													<!-- <textarea class="form-control" name="foreign_address1" id="foreign_address1" style="width:100%;height:80px;"><?=$person->foreign_address1?></textarea> -->


													<input style="margin-bottom: 5px;" type="text" class="form-control input-xs" id="foreign_address1" name="foreign_address1" value="<?=$person->foreign_address1?>"> 
													
													<!-- <?php echo form_error('postal_code1','<span class="help-block">*','</span>'); ?> -->
													<!-- <span class="input-group-btn">
														<button class="btn btn-default" type="submit" style="height:30px;"><i class="fa fa-search"></i></button>
													</span> -->
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


												<!-- <div style="margin-bottom:5px;">
													<label style="width: 25%;float:left;margin-right: 20px;">Postal Code :</label>
													<div class="input-group" style="width: 20%;" >
													<input type="text" class="form-control input-sm" id="postal_code2" name="postal_code2" value="<?=$person->postal_code2?>">
													
													</div>
												</div> -->
												<div style="margin-bottom:5px;">
													<label style="width: 25%;float:left;margin-right: 20px;">Street Name :</label>
													
																<!-- <input style="width: 51%;" type="text" class="form-control input-sm" id="street_name2" name="street_name2" value="<?=$person->street_name2?>"> -->
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
													<!-- <?php echo form_error('building_name2','<span class="help-block">*','</span>'); ?> -->
												
												</div>
												<div style="margin-bottom:5px;">
													<label style="width: 25%;float:left;margin-right: 20px;">Unit No :</label>
																<input style="width: 7%; float: left; margin-right: 3px; text-transform:uppercase" type="text" class="form-control input-xs" id="unit_no3" name="unit_no3" value="<?=$person->unit_no3?>">
																<!-- <?php echo form_error('unit_no3','<span class="help-block">*','</span>'); ?> -->
																<input style="width: 12%; text-transform:uppercase" type="text" class="form-control input-xs" id="unit_no4" name="unit_no4" value="<?=$person->unit_no4?>" maxlength="10">
																<!-- <?php echo form_error('unit_no4','<span class="help-block">*','</span>'); ?> -->
												
												</div>
											</div>
											<!-- <textarea style="width:50%;height:100px;display:none" name="alternate_address"></textarea> -->
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
						                <!-- <input type="hidden" class="form-control input-sm nationality" id="nationalityId" name="nationality" value=""> -->
										<!--select data-plugin-selectTwo class="form-control populate"-->
										<!-- <select class="form-control">

												<option value="Malaysia" <?php if($person->tipe=='Malaysia') echo ' Selected ';?>>Malaysia</option>
												<option value="Singapore" <?php if($person->tipe=='Singapore') echo ' Selected ';?>>Singapore</option>

										</select></td> -->
									</td>
								</tr>
								<!-- <tr>
									<th>Citizen</th>
									<td><?php
											// print_r($sales);
											$ctz[""] = [];
											foreach ($citizen as $cs) {
												$ctz[$cs->id] = $cs->citizen;
											}
											echo form_dropdown('citizen', $ctz, $person->citizen, 'id="citizen" name="citizen" class="form-control" style="width:100%;"');
										
											?>
									</td>
								</tr> -->
								<!-- <tr class="fieldGroup">
									<th >Local Fixed Line No</th>
									<td>
										<div class="input-group">
											<input type="text" class="form-control input-xs check_empty" id="local_fix_line" name="local_fix_line" value="<?=$person->local_fix_line?>" style="width: 50%;"/>
											<div id="form_local_fix_line"></div>
											<span class="input-group-btn">
												<input class="btn btn-success button_increment addMore" type="button" id="create_button" value="+" style="border-radius: 3px;"/>
											</span>
										</div>
									</td>
									
								</tr> -->

								<tr>
									<th style="width: 200px">Fixed Line No</th>
									<td class="added-text-field">

										<div class="input-group fieldGroup_local_fix_line">

											<input type="tel" class="form-control input-xs check_empty_local_fix_line main_local_fix_line hp" id="local_fix_line" name="local_fix_line[]" value="<?=$person->officer_mobile_no?>"/>

											<input type="hidden" class="form-control input-xs hidden_local_fix_line main_hidden_local_fix_line" id="hidden_local_fix_line" name="hidden_local_fix_line[]" value=""/>

											<label class="radio-inline control-label" style="margin-top: -30px; margin-left: 20px;"><input type="radio" class="fixed_line_no_primary main_fixed_line_no_primary" name="fixed_line_no_primary" value="1" checked> Primary</label>
												 
											
											
											<!-- <span style="vertical-align: top !important;"> -->
												<input class="btn btn-primary button_increment_local_fix_line addMore_local_fix_line" type="button" id="create_button" value="+" style="margin-left: 20px; margin-top: -26px; border-radius: 3px; visibility: hidden;"/> 

											<!-- </span> -->
											<!-- <span class="input-group-btn" style="vertical-align: top !important;"> -->

												<!-- <a href="javaScript:void(0);" class="show_local_fix_line"><i class="fa fa-arrow-down "></i></a> -->

												<button type="button" class="btn btn-default btn-sm show_local_fix_line" style="margin-left: 20px; margin-top: -23px; visibility: hidden;">
												  <span class="fa fa-arrow-down" aria-hidden="true"></span>&nbsp<span class="toggle_word">Show more</span>
												</button>
											<!-- </span> -->
										</div>

										<div class="local_fix_line_toggle">
										</div>


										<div class="input-group fieldGroupCopy_local_fix_line local_fix_line_disabled" style="display: none;">
											<input type="tel" class="form-control input-xs check_empty_local_fix_line second_local_fix_line second_hp" id="local_fix_line" name="local_fix_line[]" value=""/>

											<input type="hidden" class="form-control input-xs hidden_local_fix_line" id="hidden_local_fix_line" name="hidden_local_fix_line[]" value=""/>

											<label class="radio-inline control-label" style="margin-top: -30px; margin-left: 20px;"><input type="radio" class="fixed_line_no_primary" name="fixed_line_no_primary" value="1"> Primary</label>

											<!-- <div id="form_local_fix_line"></div> -->
											<!-- <span class="input-group-btn" style="vertical-align: top !important;"> -->
												<input class="btn btn-primary button_decrease_local_fix_line remove_local_fix_line" type="button" id="create_button" value="-" style="margin-left: 20px; margin-top: -26px;border-radius: 3px; width: 35px;"/>
											<!-- </span> -->
										</div>


										<div id="form_local_fix_line"></div>
									</td>
									
								</tr>
								<!-- <tr>
									<td>
										Test
									</td>
									<td>
										<div id="buildyourform">
											<label class="radio-inline control-label" style="margin-top: -30px; margin-left: 20px;"><input type="radio" name="fixed_line_no_primary" value="1"> Primary</label>

											<input type="button" value="Add a field" class="add" id="add" />
										</div>
									</td>
								</tr> -->
								<tr>
									<th style="width: 200px">Mobile No</th>
									<td class="added-text-field">

									

										<div class="input-group fieldGroup_local_mobile">
											<input type="tel" class="form-control input-xs check_empty_local_mobile main_local_mobile hp" id="local_mobile" name="local_mobile[]" value=""/>

											<input type="hidden" class="form-control input-xs hidden_local_mobile main_hidden_local_mobile" id="hidden_local_mobile" name="hidden_local_mobile[]" value=""/>

											<label class="radio-inline control-label" style="margin-top: -30px; margin-left: 20px;"><input type="radio" class="local_mobile_primary main_local_mobile_primary" name="local_mobile_primary" value="1" checked> Primary</label>

											
											<!-- <span class="input-group-btn" style="vertical-align: top !important;"> -->
												<input class="btn btn-primary button_increment_local_mobile addMore_local_mobile" type="button" id="create_button" value="+" style="margin-left: 20px; margin-top: -26px;border-radius: 3px;visibility: hidden; width: 35px;"/>
											<!-- </span> -->

											<button type="button" class="btn btn-default btn-sm show_local_mobile" style="margin-left: 20px; margin-top: -23px; visibility: hidden;">
												  <span class="fa fa-arrow-down" aria-hidden="true"></span>&nbsp<span class="toggle_word">Show more</span>
											</button>
										</div>

										<div class="local_mobile_toggle">
										</div>


										<div class="input-group fieldGroupCopy_local_mobile local_mobile_disabled" style="display: none;">
											<input type="tel" class="form-control input-xs check_empty_local_mobile second_local_mobile second_hp" id="local_mobile" name="local_mobile[]" value=""/>

											<input type="hidden" class="form-control input-xs hidden_local_mobile" id="hidden_local_mobile" name="hidden_local_mobile[]" value=""/>

											<label class="radio-inline control-label" style="margin-top: -30px; margin-left: 20px;"><input type="radio" class="local_mobile_primary" name="local_mobile_primary" value="1"> Primary</label>

											<!-- <div id="form_local_mobile"></div> -->
											<!-- <span class="input-group-btn" style="vertical-align: top !important;"> -->
												<input class="btn btn-primary button_decrease_local_mobile remove_local_mobile" type="button" id="create_button" value="-" style="margin-left: 20px; margin-top: -26px;border-radius: 3px; width: 35px;"/>
											<!-- </span> -->


										</div>


										<div id="form_local_mobile"></div>
									</td>
									
								</tr>

								<tr>
									<th style="width: 200px">Email</th>
									<td class="added-text-field_email">

									

										<div class="input-group fieldGroup_email" style="display: block !important;">
											<input type="text" class="form-control input-xs check_empty_email main_email" id="email" name="email[]" value="" style="text-transform:uppercase;"/>

											<label class="radio-inline control-label" style="margin-top: 5px; margin-left: 20px;"><input type="radio" class="email_primary main_email_primary" name="email_primary" value="1" checked> Primary</label>
											
											<!-- <span class="input-group-btn" style="vertical-align: top !important;"> -->
												<input class="btn btn-primary button_increment_email addMore_email" type="button" id="create_button" value="+" style="margin-left: 20px; border-radius: 3px;visibility: hidden; width: 35px;"/>
											<!-- </span> -->

											<button type="button" class="btn btn-default btn-sm show_email" style="margin-left: 20px; visibility: hidden;">
												  <span class="fa fa-arrow-down" aria-hidden="true"></span>&nbsp<span class="toggle_word">Show more</span>
											</button>
										</div>

										<div class="local_email_toggle">
										</div>


										<div class="input-group fieldGroupCopy_email email_disabled" style="display: none;">
											<input type="text" class="form-control input-xs check_empty_email second_email" id="email" name="email[]" value="" style="text-transform:uppercase;"/>
											<label class="radio-inline control-label" style="margin-top: 5px; margin-left: 20px;"><input type="radio" class="email_primary" name="email_primary" value="1"> Primary</label>
											<!-- <div id="form_email"></div> -->
											<!-- <span class="input-group-btn" style="vertical-align: top !important;"> -->
												<input class="btn btn-primary button_decrease_email remove_email" type="button" id="create_button" value="-" style="margin-left: 20px; border-radius: 3px; width: 35px;"/>
											<!-- </span> -->
										</div>


										<div id="form_email"></div>
									</td>
									
								</tr>
								<?php if($person->non_verify == 1){ ?>
									<tr>
										<th>Verify</th>
										<td>
											<!-- <?=$person->non_verify?> -->
											<div style="margin-right:5px;float: left">
										        <input type="checkbox" name="non_verify_checkbox" <?=$person->non_verify?'checked':'';?>/>
		                                        <input type="hidden" name="hidden_non_verify_checkbox" value="<?=$person->non_verify?>"/>
										    </div>
										</td>
									</tr>
								<?php } ?>

								<!-- <tr>
									<th>Local Mobile No</th>
									<td><input type="text" class="form-control input-xs" id="local_mobile" name="local_mobile"  value="<?=$person->local_mobile?>"/>
										<div id="form_local_mobile"></div>
										
									</td>
									
								</tr>
								<tr>
									<th>Email</th>
									<td><input type="text" class="form-control input-xs" id="email" name="email" value="<?=$person->email?>"/>
										<div id="form_email"></div>
										
									</td>

								</tr> -->
								<tr>
									<th>File Upload - gif,jpg,jpeg,png,pdf</th>
									<td>
										<!-- <div id="filediv"><input name="file[]" type="file" id="file"/></div> -->
										<!-- <input type="file" class="input-sm" id="file" name="uploadimage[]" multiple>
										<div id="preview"></div> -->

										<!-- <div class="form-group"> -->
								            <div class="file-loading">
								                <input type="file" id="multiple_file" class="file" name="uploadimages[]" multiple data-min-file-count="0">
								            </div>
								            

								        <!-- </div>
 -->										<!-- <div id="fine-uploader-manual-trigger"></div> -->
									</td>
								</tr>
								<!-- <tr id="represen_edit">
									<th>Representative</th>
									<td><input type="text" class="form-control input-xs" value=""/></td>
								</tr> -->
								
								
							</table>
							<?= form_close();?>

							<?php echo form_open_multipart('', array('id' => 'submit_company', 'enctype' => "multipart/form-data")); ?>
							<table class="table table-bordered table-striped table-condensed mb-none" id="tr_company_edit" <?php print $person->company_table; ?>>
								<!-- <?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
									echo form_open_multipart("personprofile/updateCompany", $attrib);
									
								?> -->
								
								<input type="hidden" class="form-control input-sm" id="close_page" name="close_page" value="<?=$close_page?>">
								<input type="hidden" class="form-control input-sm" id="field_type" name="field_type" value="company">
								<input type="hidden" class="form-control input-sm" id="officer_company_id" name="officer_company_id" value="<?=$person->id?>">
								<tr>
									<th>UEN</th>
									<input type="hidden" class="form-control input-sm" id="old_register_no" name="old_register_no" value="<?=htmlspecialchars($person->register_no, ENT_QUOTES)?>">
									<input type="hidden" class="form-control input-sm" id="individual_identification_no" name="individual_identification_no" value="<?=htmlspecialchars($person->identification_no, ENT_QUOTES)?>">
									<td><input type="text" style="text-transform:uppercase" class="form-control input-xs" id="register_no" name="register_no" value="<?=htmlspecialchars($person->register_no, ENT_QUOTES)?>"/><div id="form_register_no"></div><!-- <?php echo form_error('register_no','<span class="help-block">*','</span>'); ?> --></td>
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
										<!-- <div>
											<div id="form_company_name"></div>
										</div> -->
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
										<!-- <div style="margin-bottom:5px;">
											<label style="width: 25%;float:left;margin-right: 20px;">Postal Code :</label>
											<div class="input-group" style="width: 20%;" >
											<input type="text" class="form-control input-sm" id="company_postal_code" name="company_postal_code" value="<?=$person->company_postal_code?>">
											<?php echo form_error('company_postal_code','<span class="help-block">*','</span>'); ?>
											
											</div>
										</div> -->
										<div style="margin-bottom:5px;">
											<!-- <label style="width: 25%;float:left;margin-right: 20px;">Street Name :</label>
														<input style="width: 51%;" type="text" class="form-control input-sm" id="company_street_name" name="company_street_name" value="<?=$person->company_street_name?>"> -->


											<div style="width: 25%;float:left;margin-right: 20px;">
												<label>Street Name :</label>
											</div>
											<div style="width: 65%;float:left;margin-bottom:5px;">
												<div class="" style="width: 84.5%;">
													<input style="width: 100%; text-transform:uppercase" type="text" class="form-control input-xs" id="company_street_name" name="company_street_name" value="<?=$person->company_street_name?>">
												</div>
												<div id="form_company_street_name"></div>
												<!-- <?php echo form_error('company_postal_code','<span class="help-block">*','</span>'); ?> -->
							
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
													<!-- <textarea class="form-control" name="company_foreign_address1" id="company_foreign_address1" style="width:100%;height:80px;"><?=$person->company_foreign_address1?></textarea> -->

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
											
											<!-- <span class="input-group-btn" style="vertical-align: top !important;"> -->
												<input class="btn btn-primary button_increment_company_email addMore_company_email" type="button" id="create_button" value="+" style="margin-left: 20px; border-radius: 3px;visibility: hidden; width: 35px;"/>
											<!-- </span> -->

											<button type="button" class="btn btn-default btn-sm show_company_email" style="margin-left: 20px; visibility: hidden;">
												  <span class="fa fa-arrow-down" aria-hidden="true"></span>&nbsp<span class="toggle_word">Show more</span>
											</button>
	
										</div>

										<div class="company_email_toggle">
										</div>

										<div class="input-group fieldGroupCopy_company_email company_email_disabled" style="display: none;">
											<input type="text" class="form-control input-xs check_empty_company_email second_company_email" id="company_email" name="company_email[]" value="" style="text-transform:uppercase;"/>
											<label class="radio-inline control-label" style="margin-top: 5px; margin-left: 20px;"><input type="radio" class="company_email_primary" name="company_email_primary" value="1"> Primary</label>
											
											<!-- <span class="input-group-btn" style="vertical-align: top !important;"> -->
												<input class="btn btn-primary button_decrease_company_email remove_company_email" type="button" id="create_button" value="-" style="margin-left: 20px; border-radius: 3px; width: 35px;"/>
											<!-- </span> -->
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

											<!-- <span class="input-group-btn" style="vertical-align: top !important;"> -->
												<input class="btn btn-primary button_increment_company_phone_number addMore_company_phone_number" type="button" id="create_button" value="+" style="margin-left: 20px; margin-top: -26px; border-radius: 3px;visibility: hidden; width: 35px;"/>
											<!-- </span> -->

											<button type="button" class="btn btn-default btn-sm show_company_phone_number" style="margin-left: 20px; margin-top: -23px; visibility: hidden;">
												  <span class="fa fa-arrow-down" aria-hidden="true"></span>&nbsp<span class="toggle_word">Show more</span>
											</button>
	
										</div>

										<div class="company_phone_number_toggle">
										</div>

										<div class="input-group fieldGroupCopy_company_phone_number company_phone_number_disabled" style="display: none;">
											<input type="tel" class="form-control input-xs check_empty_company_phone_number second_company_phone_number second_hp" id="company_phone_number" name="company_phone_number[]" value=""/>

											<input type="hidden" class="form-control input-xs hidden_company_phone_number" id="hidden_company_phone_number" name="hidden_company_phone_number[]" value=""/>

											<label class="radio-inline control-label" style="margin-top: -30px; margin-left: 20px;"><input type="radio" class="company_phone_number_primary" name="company_phone_number_primary" value="1"> Primary</label>

											<!-- <span class="input-group-btn" style="vertical-align: top !important;"> -->
												<input class="btn btn-primary button_decrease_company_phone_number remove_company_phone_number" type="button" id="create_button" value="-" style="margin-left: 20px; margin-top: -26px; border-radius: 3px; width: 35px;"/>
											<!-- </span> -->
										</div>

										<div id="form_company_phone_number"></div>
									</td>
									
								</tr>

								
								<!-- <tr>
									<th>Email</th>
									<td><input type="text" class="form-control input-xs" id="company_email" name="company_email" value="<?=$person->company_email?>"/>
										<div>
											<div id="form_company_email"></div>
										</div>
									</td>
								</tr>
								
								<tr>
									<th>Phone Number</th>
									<td><input type="text" class="form-control input-xs" id="company_phone_number" name="company_phone_number" value="<?=$person->company_phone_number?>"/>
										<div>
											<div id="form_company_phone_number"></div>
										</div>
									</td>
								</tr> -->
								
								<!-- <tr>
									<th>Corporate Representative</th>
									<td><input type="text" style="text-transform:uppercase" class="form-control input-xs" id="company_corporate_representative" name="company_corporate_representative" value="<?=$person->company_corporate_representative?>"/>
										<div>
											<div id="form_company_corporate_representative"></div>
										</div>
									</td>
								</tr>
								<tr>
									<th>Identity Number</th>
									<td><input type="text" style="text-transform:uppercase" class="form-control input-xs" id="company_identity_number" name="company_identity_number" value="<?=$person->identity_number?>"/>
										<div>
											<div id="form_company_identity_number"></div>
										</div>
									</td>
								</tr> -->
								<?php if($person->non_verify == 1){ ?>
									<tr>
										<th>Verify</th>
										<td>
											<!-- <?=$person->non_verify?> -->
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
										<!-- <div id="filediv"><input name="file[]" type="file" id="file"/></div> -->
										<!-- <input type="file" class="input-sm" id="file" name="uploadimage[]" multiple>
										<div id="preview"></div> -->

										<!-- <div class="form-group"> -->
								            <div class="file-loading">
								                <input type="file" id="multiple_company_file" class="file" name="uploadcompanyimages[]" multiple data-min-file-count="0">
								            </div>
								            

								        <!-- </div>
 -->										<!-- <div id="fine-uploader-manual-trigger"></div> -->
									</td>
								</tr>
								<tr>
									<!-- <th>Corporate Representative</th> -->
									<td colspan="2">
										<h4>Corporate Representative</h4>
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
								<!--button class="btn btn-primary modal-confirm">Confirm</button-->
								<input type="submit" class="btn btn-primary" value="Save" id="save">
							<a href="<?= base_url();?>personprofile" class="btn btn-default">Cancel</a>
							</div>
						</div>
					</footer>
				</section>
						
		</div>
	</div>
	
<!-- end: page -->
</section>
</div>
<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>
<script src="themes/default/assets/js/nationality.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
<script src="themes/default/assets/js/intlTelInput.js?v=30eee4fc8d1b59e4584b0d39edfa2082" /></script>
<script src="themes/default/assets/js/defaultCountryIp.js?v=30eee4fc8d1b59e4584b0d39edfa2082" /></script>
<script src="themes/default/assets/js/utils.js?v=30eee4fc8d1b59e4584b0d39edfa2082" /></script>
<script type="text/javascript">
	var corp_rep_data = <?php echo json_encode($corp_rep_data); ?>;
</script>
<script src="themes/default/assets/js/addpersonprofile.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
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

	

	var access_right_person_module = <?php echo json_encode($person_module);?>;
	var non_verify = <?php echo json_encode($person->non_verify)?>;

	if(access_right_person_module == "read")
	{
		$('input').attr('disabled', true);
		$('select').attr('disabled', true);
	}

	if(non_verify == 1)
	{
		var state_non_verify_checkbox = false;
	}
	else
	{
		var state_non_verify_checkbox = true;
	}
	$("[name='non_verify_checkbox']").bootstrapSwitch({
	    state: state_non_verify_checkbox,
	    size: 'normal',
	    onColor: 'primary',
	    onText: 'YES',
	    offText: 'NOT',
	    // Text of the center handle of the switch
	    labelText: '&nbsp',
	    // Width of the left and right sides in pixels
	    handleWidth: '75px',
	    // Width of the center handle in pixels
	    labelWidth: 'auto',
	    baseClass: 'bootstrap-switch',
	    wrapperClass: 'wrapper'


	});

	$("[name='non_verify_checkbox']").on('switchChange.bootstrapSwitch', function(event, state) {
	    //console.log(this); // DOM element
	    //console.log(event); // jQuery event
	    console.log(state); // true | false

	    if(state == true)
	    {
	        $("[name='hidden_non_verify_checkbox']").val(0);
	        // /$("[name='gst_value']").attr("value", "");

	    }
	    else
	    {
	        $("[name='hidden_non_verify_checkbox']").val(1);
	    }
	});

	toastr.options = {

	  "positionClass": "toast-bottom-right"

	}
	
	var base_url = '<?php echo base_url() ?>';
	var person = <?php echo json_encode($person); ?>;
	var files = <?php echo json_encode($person->files); ?>;
	var company_files = <?php echo json_encode($person->company_files); ?>;
	var officer_fixed_line_no = <?php echo json_encode($person->officer_fixed_line_no); ?>;
	var officer_mobile_no = <?php echo json_encode($person->officer_mobile_no); ?>;
	var officer_email = <?php echo json_encode($person->officer_email); ?>;

	var officer_company_email = <?php echo json_encode($person->officer_company_email); ?>;
	var officer_company_phone_number = <?php echo json_encode($person->officer_company_phone_number); ?>;

	console.log(corp_rep_data);
	
	var date = new Date();
	//date.setDate(date.getDate()-1);

	$('.show_local_fix_line').click(function(e){
        e.preventDefault();
        $(this).closest('td').find(".local_fix_line_toggle").toggle();
        var icon = $(this).find(".fa");
        if(icon.hasClass("fa-arrow-down"))
        {
			icon.addClass("fa-arrow-up").removeClass("fa-arrow-down");
			$(this).find(".toggle_word").text('Show less');
		}
		else
		{
			icon.addClass("fa-arrow-down").removeClass("fa-arrow-up");
			$(this).find(".toggle_word").text('Show more');
		}
    });

    $('.show_local_mobile').click(function(e){
        e.preventDefault();
        $(this).closest('td').find(".local_mobile_toggle").toggle();
        var icon = $(this).find(".fa");
        if(icon.hasClass("fa-arrow-down"))
        {
			icon.addClass("fa-arrow-up").removeClass("fa-arrow-down");
			$(this).find(".toggle_word").text('Show less');
		}
		else
		{
			icon.addClass("fa-arrow-down").removeClass("fa-arrow-up");
			$(this).find(".toggle_word").text('Show more');
		}
    });

    $('.show_email').click(function(e){
        e.preventDefault();
        $(this).closest('td').find(".local_email_toggle").toggle();
        var icon = $(this).find(".fa");
        if(icon.hasClass("fa-arrow-down"))
        {
			icon.addClass("fa-arrow-up").removeClass("fa-arrow-down");
			$(this).find(".toggle_word").text('Show less');
		}
		else
		{
			icon.addClass("fa-arrow-down").removeClass("fa-arrow-up");
			$(this).find(".toggle_word").text('Show more');
		}
    });

    $('.show_company_email').click(function(e){
        e.preventDefault();
        $(this).closest('td').find(".company_email_toggle").toggle();
        var icon = $(this).find(".fa");
        if(icon.hasClass("fa-arrow-down"))
        {
			icon.addClass("fa-arrow-up").removeClass("fa-arrow-down");
			$(this).find(".toggle_word").text('Show less');
		}
		else
		{
			icon.addClass("fa-arrow-down").removeClass("fa-arrow-up");
			$(this).find(".toggle_word").text('Show more');
		}
    });

    $('.show_company_phone_number').click(function(e){
        e.preventDefault();
        $(this).closest('td').find(".company_phone_number_toggle").toggle();
        var icon = $(this).find(".fa");
        if(icon.hasClass("fa-arrow-down"))
        {
			icon.addClass("fa-arrow-up").removeClass("fa-arrow-down");
			$(this).find(".toggle_word").text('Show less');
		}
		else
		{
			icon.addClass("fa-arrow-down").removeClass("fa-arrow-up");
			$(this).find(".toggle_word").text('Show more');
		}
    });

	$('.hp').intlTelInput({
    	preferredCountries: [ "sg", "my"],
	    initialCountry: "auto",
	    formatOnDisplay: false,
	    nationalMode: true,
	    geoIpLookup: function(callback) {
	        jQuery.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
	            var countryCode = (resp && resp.country) ? resp.country : "";
	            callback(countryCode);
	        });
	    },
	    customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
		  return "" ;
		},
	    utilsScript: "../themes/default/js/utils.js"
	});

	//edit
	if(officer_fixed_line_no != null)
	{
		for (var h = 0; h < officer_fixed_line_no.length; h++) 
		{
		  	var officerFixLineNoArray = officer_fixed_line_no[h].split(',');

		  	if(officerFixLineNoArray[2] == 1)
		  	{
		  		$(".fieldGroup_local_fix_line").find('.main_local_fix_line').intlTelInput("setNumber", officerFixLineNoArray[1]);
		  		$(".fieldGroup_local_fix_line").find('.main_hidden_local_fix_line').attr("value", officerFixLineNoArray[1]);
		  		$(".fieldGroup_local_fix_line").find('.main_fixed_line_no_primary').attr("value", officerFixLineNoArray[1]);
		  		$(".fieldGroup_local_fix_line").find(".button_increment_local_fix_line").css({"visibility": "visible"});
		  	}
		  	else
		  	{
		  		
	        	$(".fieldGroupCopy_local_fix_line").find('.hidden_local_fix_line').attr("value", officerFixLineNoArray[1]);
	        	$(".fieldGroupCopy_local_fix_line").find('.fixed_line_no_primary').attr("value", officerFixLineNoArray[1]);


	            var fieldHTML = '<div class="input-group fieldGroup_local_fix_line" style="margin-top:10px;">'+$(".fieldGroupCopy_local_fix_line").html()+'</div>';

	            /*$('body').find('.fieldGroup_local_fix_line:first').after(fieldHTML);*/
	           //$(".test_toggle").after();
	            $( fieldHTML).prependTo(".local_fix_line_toggle");
	            
	            $('.local_fix_line_toggle .fieldGroup_local_fix_line').eq(0).find('.second_hp').intlTelInput({
	            	preferredCountries: [ "sg", "my"],
	            	formatOnDisplay: false,
	            	nationalMode: true,
				    geoIpLookup: function(callback) {
				        jQuery.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
				            var countryCode = (resp && resp.country) ? resp.country : "";
				            callback(countryCode);
				        });
				    },
				    customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
					  return "" ;
					},
				    utilsScript: "../themes/default/js/utils.js"
				});

				$('.local_fix_line_toggle .fieldGroup_local_fix_line').eq(0).find('.second_hp').intlTelInput("setNumber", officerFixLineNoArray[1]);

				$('.local_fix_line_toggle .fieldGroup_local_fix_line').eq(1).find('.second_hp').on({
				  keydown: function(e) {
				    if (e.which === 32)
				      return false;
				  },
				  change: function() {
				    this.value = this.value.replace(/\s/g, "");
				  }
				});

	            $(".fieldGroupCopy_local_fix_line").find('.hidden_local_fix_line').attr("value", "");
	            $(".fieldGroupCopy_local_fix_line").find('.fixed_line_no_primary').attr("value", "");
		  	}
		}
	}
	else
	{
		$(".fieldGroup_local_fix_line").find('.main_local_fix_line').intlTelInput("setNumber", "");
	}

	if(officer_mobile_no != null)
	{
		for (var h = 0; h < officer_mobile_no.length; h++) 
		{
		  	var officerMobileNoArray = officer_mobile_no[h].split(',');

		  	if(officerMobileNoArray[2] == 1)
		  	{
		  		$(".fieldGroup_local_mobile").find('.main_local_mobile').intlTelInput("setNumber", officerMobileNoArray[1]);
		  		$(".fieldGroup_local_mobile").find('.main_hidden_local_mobile').attr("value", officerMobileNoArray[1]);
		  		$(".fieldGroup_local_mobile").find('.main_local_mobile_primary').attr("value", officerMobileNoArray[1]);
		  		$(".fieldGroup_local_mobile").find(".button_increment_local_mobile").css({"visibility": "visible"});
		  	}
		  	else
		  	{
		  		
	        	$(".fieldGroupCopy_local_mobile").find('.hidden_local_mobile').attr("value", officerMobileNoArray[1]);
	        	$(".fieldGroupCopy_local_mobile").find('.local_mobile_primary').attr("value", officerMobileNoArray[1]);

	            var fieldHTML = '<div class="input-group fieldGroup_local_mobile" style="margin-top:10px;">'+$(".fieldGroupCopy_local_mobile").html()+'</div>';

	            //$('body').find('.fieldGroup_local_mobile:first').after(fieldHTML);
	            $( fieldHTML).prependTo(".local_mobile_toggle");

	            $('.local_mobile_toggle .fieldGroup_local_mobile').eq(0).find('.second_hp').intlTelInput({
	            	preferredCountries: [ "sg", "my"],
	            	formatOnDisplay: false,
	            	nationalMode: true,
				    geoIpLookup: function(callback) {
				        jQuery.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
				            var countryCode = (resp && resp.country) ? resp.country : "";
				            callback(countryCode);
				        });
				    },
				    customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
					  return "" ;
					},
				    utilsScript: "../themes/default/js/utils.js"
				});

				$('.local_mobile_toggle .fieldGroup_local_mobile').eq(0).find('.second_hp').intlTelInput("setNumber", officerMobileNoArray[1]);

				$('.local_mobile_toggle .fieldGroup_local_mobile').eq(0).find('.second_hp').on({
				  keydown: function(e) {
				    if (e.which === 32)
				      return false;
				  },
				  change: function() {
				    this.value = this.value.replace(/\s/g, "");
				  }
				});

	            $(".fieldGroupCopy_local_mobile").find('.hidden_local_mobile').attr("value", "");
	            $(".fieldGroupCopy_local_mobile").find('.local_mobile_primary').attr("value", "");
		  	}
		}
	}

	if(officer_email != null)
	{
		for (var h = 0; h < officer_email.length; h++) 
		{
		  	var officerEmailArray = officer_email[h].split(',');

		  	if(officerEmailArray[2] == 1)
		  	{
		  		$(".fieldGroup_email").find('.main_email').attr("value", officerEmailArray[1]);
		  		$(".fieldGroup_email").find('.main_email_primary').attr("value", officerEmailArray[1]);

		  		$(".fieldGroup_email").find(".button_increment_email").css({"visibility": "visible"});
		  	}
		  	else
		  	{
		  		$(".fieldGroupCopy_email").find('.second_email').attr("value", officerEmailArray[1]);

	        	$(".fieldGroupCopy_email").find('.email_primary').attr("value", officerEmailArray[1]);

	            var fieldHTML = '<div class="input-group fieldGroup_email" style="margin-top:10px; display: block;">'+$(".fieldGroupCopy_email").html()+'</div>';

	            //$('body').find('.fieldGroup_email:first').after(fieldHTML);
	            $( fieldHTML).prependTo(".local_email_toggle");

				$(".fieldGroupCopy_email").find('.second_email').attr("value", "");
	            $(".fieldGroupCopy_email").find('.email_primary').attr("value", "");
		  	}
		}
	}

	if(officer_company_email != null)
	{
		for (var h = 0; h < officer_company_email.length; h++) 
		{
		  	var officerCompanyEmailArray = officer_company_email[h].split(',');

		  	if(officerCompanyEmailArray[2] == 1)
		  	{
		  		$(".fieldGroup_company_email").find('.main_company_email').attr("value", officerCompanyEmailArray[1]);
		  		$(".fieldGroup_company_email").find('.main_company_email_primary').attr("value", officerCompanyEmailArray[1]);

		  		$(".fieldGroup_company_email").find(".button_increment_company_email").css({"visibility": "visible"});
		  	}
		  	else
		  	{
		  		$(".fieldGroupCopy_company_email").find('.second_company_email').attr("value", officerCompanyEmailArray[1]);

	        	$(".fieldGroupCopy_company_email").find('.company_email_primary').attr("value", officerCompanyEmailArray[1]);

	            var fieldHTML = '<div class="input-group fieldGroup_company_email" style="margin-top:10px; display: block !important;">'+$(".fieldGroupCopy_company_email").html()+'</div>';

	            //$('body').find('.fieldGroup_company_email:first').after(fieldHTML);
	            $( fieldHTML).prependTo(".company_email_toggle");

				$(".fieldGroupCopy_company_email").find('.second_company_email').attr("value", "");
	            $(".fieldGroupCopy_company_email").find('.company_email_primary').attr("value", "");
		  	}
		}
	}


	if(officer_company_phone_number != null)
	{
		for (var h = 0; h < officer_company_phone_number.length; h++) 
		{
		  	var officerCompanyPhoneNumberArray = officer_company_phone_number[h].split(',');



		  	if(officerCompanyPhoneNumberArray[2] == 1)
		  	{
		  		$(".fieldGroup_company_phone_number").find('.main_company_phone_number').intlTelInput("setNumber", officerCompanyPhoneNumberArray[1]);
		  		$(".fieldGroup_company_phone_number").find('.main_hidden_company_phone_number').attr("value", officerCompanyPhoneNumberArray[1]);
		  		$(".fieldGroup_company_phone_number").find('.main_company_phone_number_primary').attr("value", officerCompanyPhoneNumberArray[1]);
		  		$(".fieldGroup_company_phone_number").find(".button_increment_company_phone_number").css({"visibility": "visible"});
		  	}
		  	else
		  	{
		  		
	        	$(".fieldGroupCopy_company_phone_number").find('.hidden_company_phone_number').attr("value", officerCompanyPhoneNumberArray[1]);
	        	$(".fieldGroupCopy_company_phone_number").find('.company_phone_number_primary').attr("value", officerCompanyPhoneNumberArray[1]);


	            var fieldHTML = '<div class="input-group fieldGroup_company_phone_number" style="margin-top:10px;">'+$(".fieldGroupCopy_company_phone_number").html()+'</div>';

	            //$('body').find('.fieldGroup_company_phone_number:first').after(fieldHTML);
	            $( fieldHTML).prependTo(".company_phone_number_toggle");

	            $('.company_phone_number_toggle .fieldGroup_company_phone_number').eq(0).find('.second_hp').intlTelInput({
	            	preferredCountries: [ "sg", "my"],
	            	formatOnDisplay: false,
	            	nationalMode: true,
				    geoIpLookup: function(callback) {
				        jQuery.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
				            var countryCode = (resp && resp.country) ? resp.country : "";
				            callback(countryCode);
				        });
				    },
				    customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
					  return "" ;
					},
				    utilsScript: "../themes/default/js/utils.js"
				});

				$('.company_phone_number_toggle .fieldGroup_company_phone_number').eq(0).find('.second_hp').intlTelInput("setNumber", officerCompanyPhoneNumberArray[1]);

				$('.company_phone_number_toggle .fieldGroup_company_phone_number').eq(0).find('.second_hp').on({
				  keydown: function(e) {
				    if (e.which === 32)
				      return false;
				  },
				  change: function() {
				    this.value = this.value.replace(/\s/g, "");
				  }
				});

	            $(".fieldGroupCopy_company_phone_number").find('.hidden_company_phone_number').attr("value", "");
	            $(".fieldGroupCopy_company_phone_number").find('.company_phone_number_primary').attr("value", "");
		  	}
		}
	}
	else
	{
		$(".fieldGroup_company_phone_number").find('.main_company_phone_number').intlTelInput("setNumber", "");
	}

	//put to hidden and radio button value when finish typing
	//individual
	$(document).on('blur', '.check_empty_local_fix_line', function(){
	    $(this).parent().parent().find(".hidden_local_fix_line").attr("value", $(this).intlTelInput("getNumber", intlTelInputUtils.numberFormat.E164));
	    $(this).parent().parent().find(".fixed_line_no_primary").attr("value", $(this).intlTelInput("getNumber", intlTelInputUtils.numberFormat.E164));
	});

	$(document).on('blur', '.check_empty_local_mobile', function(){
		console.log($(this).val());
		$(this).parent().parent().find(".hidden_local_mobile").attr("value", $(this).intlTelInput("getNumber", intlTelInputUtils.numberFormat.E164));
	    $(this).parent().parent().find(".local_mobile_primary").attr("value", $(this).intlTelInput("getNumber", intlTelInputUtils.numberFormat.E164));
	});

	$(document).on('blur', '.check_empty_email', function(){

	    $(this).parent().find(".email_primary").attr("value", $(this).val());
	});

	//company
	$(document).on('blur', '.check_empty_company_email', function(){

	    $(this).parent().find(".company_email_primary").attr("value", $(this).val());
	});

	$(document).on('blur', '.check_empty_company_phone_number', function(){

	    $(this).parent().parent().find(".hidden_company_phone_number").attr("value", $(this).intlTelInput("getNumber", intlTelInputUtils.numberFormat.E164));
	    $(this).parent().parent().find(".company_phone_number_primary").attr("value", $(this).intlTelInput("getNumber", intlTelInputUtils.numberFormat.E164));
	});

	$(document).ready(function() {

		$(document).on('click', '.fixed_line_no_primary', function(event){
			event.preventDefault();
			var fixed_line_no_primary_radio_button = $(this);
	    	bootbox.confirm("Are you comfirm set as primary for this Fixed Line No?", function (result) {
	            if (result) {
	            	fixed_line_no_primary_radio_button.prop( "checked", true );
	            }
	        });
		});
		
		$(document).on('click', '.local_mobile_primary', function(event){
			event.preventDefault();
			var local_mobile_primary_radio_button = $(this);
	    	bootbox.confirm("Are you comfirm set as primary for this Mobile No?", function (result) {
	            if (result) {
	            	local_mobile_primary_radio_button.prop( "checked", true );
	            }
	        });
		    
		});

		$(document).on('click', '.email_primary', function(event){	
			event.preventDefault();
			var email_primary_radio_button = $(this);
	    	bootbox.confirm("Are you comfirm set as primary for this Email?", function (result) {
	            if (result) {
	            	email_primary_radio_button.prop( "checked", true );
	            }
	        });
		});

		$(document).on('click', '.company_email_primary', function(event){	
			event.preventDefault();
			var company_email_primary_radio_button = $(this);
	    	bootbox.confirm("Are you comfirm set as primary for this Email?", function (result) {
	            if (result) {
	            	company_email_primary_radio_button.prop( "checked", true );
	            }
	        });
		});

		$(document).on('click', '.company_phone_number_primary', function(event){	
			event.preventDefault();
			var company_phone_number_primary_radio_button = $(this);
	    	bootbox.confirm("Are you comfirm set as primary for this Phone Number?", function (result) {
	            if (result) {
	            	company_phone_number_primary_radio_button.prop( "checked", true );
	            }
	        });
		});

		$(".check_empty_local_fix_line").on({
		  keydown: function(e) {
		    if (e.which === 32)
		      return false;
		  },
		  change: function() {
		    this.value = this.value.replace(/\s/g, "");
		  }
		});

		$(".check_empty_local_mobile").on({
		  keydown: function(e) {
		    if (e.which === 32)
		      return false;
		  },
		  change: function() {
		    this.value = this.value.replace(/\s/g, "");
		  }
		});

		$(".check_empty_company_phone_number").on({
		  keydown: function(e) {
		    if (e.which === 32)
		      return false;
		  },
		  change: function() {
		    this.value = this.value.replace(/\s/g, "");
		  }
		});

		//individual_local_fix_line
		$(".addMore_local_fix_line").click(function(){
        	var number = $(".main_local_fix_line").intlTelInput("getNumber", intlTelInputUtils.numberFormat.E164);

        	var countryData = $(".main_local_fix_line").intlTelInput("getSelectedCountryData");

        	$(".local_fix_line_toggle").show();
        	$(".show_local_fix_line").find(".fa").addClass("fa-arrow-up").removeClass("fa-arrow-down");
        	$(".show_local_fix_line").find(".toggle_word").text('Show less');
        	

        	$(".fieldGroupCopy_local_fix_line").find('.second_local_fix_line').attr("value", $(".main_local_fix_line").val());
        	//console.log($(".main_local_fix_line").val());
        	$(".fieldGroupCopy_local_fix_line").find('.hidden_local_fix_line').attr("value", number);
        	$(".fieldGroupCopy_local_fix_line").find('.fixed_line_no_primary').attr("value", number);
        	//$(".fieldGroupCopy_local_fix_line").find('.hidden_local_fix_line').intlTelInput("setNumber", number);
        	//$(".fieldGroupCopy_local_fix_line").find('.second_local_fix_line').intlTelInput("setCountry", countryData.iso2);

            var fieldHTML = '<div class="input-group fieldGroup_local_fix_line" style="margin-top:10px;">'+$(".fieldGroupCopy_local_fix_line").html()+'</div>';

            //$('body').find('.fieldGroup_local_fix_line:first').after(fieldHTML);
           	$( fieldHTML).prependTo(".local_fix_line_toggle");
           	
            $('.local_fix_line_toggle .fieldGroup_local_fix_line').eq(0).find('.second_hp').intlTelInput({
            	preferredCountries: [ "sg", "my"],
            	formatOnDisplay: false,
            	nationalMode: true,
			    initialCountry: countryData.iso2,
			    geoIpLookup: function(callback) {
			        jQuery.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
			            var countryCode = (resp && resp.country) ? resp.country : "";
			            callback(countryCode);
			        });
			    },
			    customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
				  return "" ;
				},
			    utilsScript: "../themes/default/js/utils.js"
			});

			$('.local_fix_line_toggle .fieldGroup_local_fix_line').eq(0).find('.second_hp').on({
			  keydown: function(e) {
			    if (e.which === 32)
			      return false;
			  },
			  change: function() {
			    this.value = this.value.replace(/\s/g, "");
			  }
			});

			if ($(".main_fixed_line_no_primary").is(":checked")) 
			{
				$('.local_fix_line_toggle .fieldGroup_local_fix_line').eq(0).find('.fixed_line_no_primary').prop( "checked", true );
			}

            $(".button_increment_local_fix_line").css({"visibility": "hidden"});

            if ($(".local_fix_line_toggle").find(".second_local_fix_line").length > 0) 
			{
				$(".show_local_fix_line").css({"visibility": "visible"});

	       	}
	       	else {
	       		$(".show_local_fix_line").css({"visibility": "hidden"});
	       		
	       	}
           
            $(".main_local_fix_line").val("");
            $(".main_local_fix_line").parent().parent().find(".hidden_local_fix_line").val("");
            $(".main_local_fix_line").parent().parent().find(".fixed_line_no_primary").val("");
            $(".fieldGroupCopy_local_fix_line").find('.second_local_fix_line').attr("value", "");
            $(".fieldGroupCopy_local_fix_line").find('.hidden_local_fix_line').attr("value", "");
            $(".fieldGroupCopy_local_fix_line").find('.fixed_line_no_primary').attr("value", "");
	    });

	    $("body").on("click",".remove_local_fix_line",function(){ 
	    	var remove_local_fix_line_button = $(this);
	    	bootbox.confirm("Are you comfirm delete this Fixed Line No?", function (result) {
	            if (result) {
	            	
	            	remove_local_fix_line_button.parents(".fieldGroup_local_fix_line").remove();

	            	if (remove_local_fix_line_button.parent().find(".fixed_line_no_primary").is(":checked")) 
					{
						if ($(".local_fix_line_toggle").find(".second_local_fix_line").length > 0) 
						{
							$('.local_fix_line_toggle .fieldGroup_local_fix_line').eq(0).find('.fixed_line_no_primary').prop( "checked", true );
						}
						else
						{
							$(".main_fixed_line_no_primary").prop( "checked", true );
						}
					}

	            	if ($(".local_fix_line_toggle").find(".second_local_fix_line").length > 0) 
					{
						$(".show_local_fix_line").css({"visibility": "visible"});

			       	}
			       	else {
			       		$(".show_local_fix_line").css({"visibility": "hidden"});
			       		
			       	}
	            }
	        });
	        
	    });

		$('.main_local_fix_line').keyup(function(){

			if ($(this).val()) {
				$(".button_increment_local_fix_line").css({"visibility": "visible"});

	       	}
	       	else {
	       		$(".button_increment_local_fix_line").css({"visibility": "hidden"});
	       	}
		});

		//individual_mobile_no
		$(".addMore_local_mobile").click(function(){
        	var number = $(".main_local_mobile").intlTelInput("getNumber", intlTelInputUtils.numberFormat.E164);

        	var countryData = $(".main_local_mobile").intlTelInput("getSelectedCountryData");

        	$(".local_mobile_toggle").show();
        	$(".show_local_mobile").find(".fa").addClass("fa-arrow-up").removeClass("fa-arrow-down");
        	$(".show_local_mobile").find(".toggle_word").text('Show less');

        	$(".fieldGroupCopy_local_mobile").find('.second_local_mobile').attr("value", $(".main_local_mobile").val());
        	$(".fieldGroupCopy_local_mobile").find('.hidden_local_mobile').attr("value", number);
        	$(".fieldGroupCopy_local_mobile").find('.local_mobile_primary').attr("value", number);
        	//$(".fieldGroupCopy").find('.second_local_fix_line').intlTelInput("setNumber", number);
        	//$(".fieldGroupCopy_local_mobile").find('.second_local_mobile').intlTelInput("setCountry", countryData.iso2);
        	//console.log($(".local_mobile_toggle").find(".second_local_mobile").length == 0 && $(".main_local_mobile_primary").is(":checked"));
         	

            var fieldHTML = '<div class="input-group fieldGroup_local_mobile" style="margin-top:10px;">'+$(".fieldGroupCopy_local_mobile").html()+'</div>';

            //$('body').find('.fieldGroup_local_mobile:first').after(fieldHTML);
            $( fieldHTML).prependTo(".local_mobile_toggle");

            $('.local_mobile_toggle .fieldGroup_local_mobile').eq(0).find('.second_hp').intlTelInput({
            	preferredCountries: [ "sg", "my"],
            	formatOnDisplay: false,
            	nationalMode: true,
			    initialCountry: countryData.iso2,
			    geoIpLookup: function(callback) {
			        jQuery.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
			            var countryCode = (resp && resp.country) ? resp.country : "";
			            callback(countryCode);
			        });
			    },
			    customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
				  return "" ;
				},
			    utilsScript: "../themes/default/js/utils.js"
			});

			$('.local_mobile_toggle .fieldGroup_local_mobile').eq(0).find('.second_hp').on({
			  keydown: function(e) {
			    if (e.which === 32)
			      return false;
			  },
			  change: function() {
			    this.value = this.value.replace(/\s/g, "");
			  }
			});

			if ($(".main_local_mobile_primary").is(":checked")) 
			{
				$('.local_mobile_toggle .fieldGroup_local_mobile').eq(0).find('.local_mobile_primary').prop( "checked", true );
			}

            $(".button_increment_local_mobile").css({"visibility": "hidden"});

            if ($(".local_mobile_toggle").find(".second_local_mobile").length > 0) 
			{
				$(".show_local_mobile").css({"visibility": "visible"});

	       	}
	       	else {
	       		$(".show_local_mobile").css({"visibility": "hidden"});
	       		
	       	}

            $(".main_local_mobile").val("");
            $(".main_local_mobile").parent().parent().find(".hidden_local_mobile").val("");
            $(".main_local_mobile").parent().parent().find(".local_mobile_primary").val("");
            $(".fieldGroupCopy_local_mobile").find('.second_local_mobile').attr("value", "");
            $(".fieldGroupCopy_local_mobile").find('.hidden_local_mobile').attr("value", "");
            $(".fieldGroupCopy_local_mobile").find('.local_mobile_primary').attr("value", "");
            $(".fieldGroupCopy_local_mobile").find('.local_mobile_primary').prop( "checked", false );

	    });

	    $("body").on("click",".remove_local_mobile",function(){ 
	        var remove_local_mobile_button = $(this);
	    	bootbox.confirm("Are you comfirm delete this Mobile No?", function (result) {
	            if (result) {
	            	remove_local_mobile_button.parents(".fieldGroup_local_mobile").remove();

	            	if (remove_local_mobile_button.parent().find(".local_mobile_primary").is(":checked")) 
					{
						if ($(".local_mobile_toggle").find(".second_local_mobile").length > 0) 
						{
							$('.local_mobile_toggle .fieldGroup_local_mobile').eq(0).find('.local_mobile_primary').prop( "checked", true );
						}
						else
						{
							$(".main_local_mobile_primary").prop( "checked", true );
						}
					}

	            	if ($(".local_mobile_toggle").find(".second_local_mobile").length > 0) 
					{
						$(".show_local_mobile").css({"visibility": "visible"});

			       	}
			       	else {
			       		$(".show_local_mobile").css({"visibility": "hidden"});
			       		
			       	}
			       	$( '#form_local_mobile' ).html("");
	            }
	        });
	    });

		$('.main_local_mobile').keyup(function(){

			if ($(this).val()) {
				$(".button_increment_local_mobile").css({"visibility": "visible"});

	       	}
	       	else {
	       		$(".button_increment_local_mobile").css({"visibility": "hidden"});
	       	}
		});



		/*$('.second_local_mobile').keyup(function(){
			console.log($(this).val());
		});*/

		//individual_email
		$(".addMore_email").click(function(){

			$(".local_email_toggle").show();
        	$(".show_email").find(".fa").addClass("fa-arrow-up").removeClass("fa-arrow-down");
        	$(".show_email").find(".toggle_word").text('Show less');

        	$(".fieldGroupCopy_email").find('.second_email').attr("value", $(".main_email").val());

        	$(".fieldGroupCopy_email").find('.email_primary').attr("value", $(".main_email").val());
        	//$(".fieldGroupCopy").find('.second_local_fix_line').intlTelInput("setNumber", number);
        	//$(".fieldGroupCopy_email").find('.second_email').intlTelInput("setCountry", countryData.iso2);

            var fieldHTML = '<div class="input-group fieldGroup_email" style="margin-top:10px; display: block;">'+$(".fieldGroupCopy_email").html()+'</div>';

            //$('body').find('.fieldGroup_email:first').after(fieldHTML);
            $( fieldHTML).prependTo(".local_email_toggle");

            if ($(".main_email_primary").is(":checked")) 
			{
				$(".local_email_toggle .fieldGroup_email").eq(0).find('.email_primary').prop( "checked", true );
			}

            $(".button_increment_email").css({"visibility": "hidden"});

            if ($(".local_email_toggle").find(".second_email").length > 0) 
			{
				$(".show_email").css({"visibility": "visible"});

	       	}
	       	else {
	       		$(".show_email").css({"visibility": "hidden"});
	       		
	       	}
           
            $(".main_email").val("");
            $(".main_email").parent().find(".email_primary").val("");
            $(".fieldGroupCopy_email").find('.second_email').attr("value", "");
            $(".fieldGroupCopy_email").find('.email_primary').attr("value", "");

	    });

	    $("body").on("click",".remove_email",function(){ 
	        var remove_email = $(this);
	    	bootbox.confirm("Are you comfirm delete this Email?", function (result) {
	            if (result) {

	            	remove_email.parents(".fieldGroup_email").remove();

	            	if (remove_email.parent().find(".email_primary").is(":checked")) 
					{
						if ($(".local_email_toggle").find(".second_email").length > 0) 
						{
							$(".local_email_toggle .fieldGroup_email").eq(0).find('.email_primary').prop( "checked", true );
						}
						else
						{
							$(".main_email_primary").prop( "checked", true );
						}
					}

	            	if ($(".local_email_toggle").find(".second_email").length > 0) 
					{
						$(".show_email").css({"visibility": "visible"});

			       	}
			       	else {
			       		$(".show_email").css({"visibility": "hidden"});
			       		
			       	}
			       	
					$( '#form_email' ).html("");
	            }
	        });
	    });

		$('.main_email').keyup(function(){

			if ($(this).val()) {
				$(".button_increment_email").css({"visibility": "visible"});

	       	}
	       	else {
	       		$(".button_increment_email").css({"visibility": "hidden"});
	       	}
		});

		//company_phone_number
		$(".addMore_company_phone_number").click(function(){

        	var number = $(".main_company_phone_number").intlTelInput("getNumber", intlTelInputUtils.numberFormat.E164);

        	var countryData = $(".main_company_phone_number").intlTelInput("getSelectedCountryData");

        	$(".company_phone_number_toggle").show();
        	$(".show_company_phone_number").find(".fa").addClass("fa-arrow-up").removeClass("fa-arrow-down");
        	$(".show_company_phone_number").find(".toggle_word").text('Show less');

        	$(".fieldGroupCopy_company_phone_number").find('.second_company_phone_number').attr("value", $(".main_company_phone_number").val());
        	$(".fieldGroupCopy_company_phone_number").find('.hidden_company_phone_number').attr("value", number);
        	$(".fieldGroupCopy_company_phone_number").find('.company_phone_number_primary').attr("value", number);
        	//$(".fieldGroupCopy").find('.second_local_fix_line').intlTelInput("setNumber", number);
        	//$(".fieldGroupCopy_company_phone_number").find('.second_company_phone_number').intlTelInput("setCountry", countryData.iso2);

            var fieldHTML = '<div class="input-group fieldGroup_company_phone_number" style="margin-top:10px;">'+$(".fieldGroupCopy_company_phone_number").html()+'</div>';

            //$('body').find('.fieldGroup_company_phone_number:first').after(fieldHTML);
            $( fieldHTML).prependTo(".company_phone_number_toggle");

            $('.company_phone_number_toggle .fieldGroup_company_phone_number').eq(0).find('.second_hp').intlTelInput({
            	preferredCountries: [ "sg", "my"],
            	formatOnDisplay: false,
            	nationalMode: true,
			    initialCountry: countryData.iso2,
			    geoIpLookup: function(callback) {
			        jQuery.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
			            var countryCode = (resp && resp.country) ? resp.country : "";
			            callback(countryCode);
			        });
			    },
			    customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
				  return "" ;
				},
			    utilsScript: "../themes/default/js/utils.js"
			});

			$('.company_phone_number_toggle .fieldGroup_company_phone_number').eq(0).find('.second_hp').on({
			  keydown: function(e) {
			    if (e.which === 32)
			      return false;
			  },
			  change: function() {
			    this.value = this.value.replace(/\s/g, "");
			  }
			});

			if ($(".main_company_phone_number_primary").is(":checked")) 
			{
				$('.company_phone_number_toggle .fieldGroup_company_phone_number').eq(0).find('.company_phone_number_primary').prop( "checked", true );
			}


            $(".button_increment_company_phone_number").css({"visibility": "hidden"});

            if ($(".company_phone_number_toggle").find(".second_company_phone_number").length > 0) 
			{
				$(".show_company_phone_number").css({"visibility": "visible"});

	       	}
	       	else {
	       		$(".show_company_phone_number").css({"visibility": "hidden"});
	       		
	       	}
           
            $(".main_company_phone_number").val("");
            $(".main_company_phone_number").parent().parent().find(".hidden_company_phone_number").val("");
            $(".main_company_phone_number").parent().parent().find(".company_phone_number_primary").val("");
            $(".fieldGroupCopy_company_phone_number").find('.second_company_phone_number').attr("value", "");
            $(".fieldGroupCopy_company_phone_number").find('.hidden_company_phone_number').attr("value", "");
            $(".fieldGroupCopy_company_phone_number").find('.company_phone_number_primary').attr("value", "");

	    });

	    $("body").on("click",".remove_company_phone_number",function(){ 
	        var remove_company_phone_number_button = $(this);
	    	bootbox.confirm("Are you comfirm delete this Phone Number?", function (result) {
	            if (result) {

	            	remove_company_phone_number_button.parents(".fieldGroup_company_phone_number").remove();

	            	if (remove_company_phone_number_button.parent().find(".company_phone_number_primary").is(":checked")) 
					{
						if ($(".company_phone_number_toggle").find(".second_company_phone_number").length > 0) 
						{
							$('.company_phone_number_toggle .fieldGroup_company_phone_number').eq(0).find('.company_phone_number_primary').prop( "checked", true );
						}
						else
						{
							$(".main_company_phone_number_primary").prop( "checked", true );
						}
						
					}

	            	if ($(".company_phone_number_toggle").find(".second_company_phone_number").length > 0) 
					{
						$(".show_company_phone_number").css({"visibility": "visible"});

			       	}
			       	else {
			       		$(".show_company_phone_number").css({"visibility": "hidden"});
			       		
			       	}
	            }
	        });
	    });

		$('.main_company_phone_number').keyup(function(){

			if ($(this).val()) {
				$(".button_increment_company_phone_number").css({"visibility": "visible"});

	       	}
	       	else {
	       		$(".button_increment_company_phone_number").css({"visibility": "hidden"});
	       	}
		});

		//company_email
		$(".addMore_company_email").click(function(){

			$(".company_email_toggle").show();
        	$(".show_company_email").find(".fa").addClass("fa-arrow-up").removeClass("fa-arrow-down");
        	$(".show_company_email").find(".toggle_word").text('Show less');

        	$(".fieldGroupCopy_company_email").find('.second_company_email').attr("value", $(".main_company_email").val());
        	//$(".fieldGroupCopy").find('.second_local_fix_line').intlTelInput("setNumber", number);
        	//$(".fieldGroupCopy_email").find('.second_email').intlTelInput("setCountry", countryData.iso2);
        	$(".fieldGroupCopy_company_email").find('.company_email_primary').attr("value", $(".main_company_email").val());

            var fieldHTML = '<div class="input-group fieldGroup_company_email" style="margin-top:10px; display: block !important;">'+$(".fieldGroupCopy_company_email").html()+'</div>';

            //$('body').find('.fieldGroup_company_email:first').after(fieldHTML);
            $( fieldHTML).prependTo(".company_email_toggle");

            if ($(".main_company_email_primary").is(":checked")) 
			{
				$(".company_email_toggle .fieldGroup_company_email").eq(0).find('.company_email_primary').prop( "checked", true );
			}
			
            $(".button_increment_company_email").css({"visibility": "hidden"});
           
           if ($(".company_email_toggle").find(".second_company_email").length > 0) 
			{
				$(".show_company_email").css({"visibility": "visible"});

	       	}
	       	else {
	       		$(".show_company_email").css({"visibility": "hidden"});
	       		
	       	}

            $(".main_company_email").val("");
            $(".main_company_email").parent().find(".main_company_email_primary").val("");
            $(".fieldGroupCopy_company_email").find('.second_company_email').attr("value", "");
            $(".fieldGroupCopy_company_email").find('.company_email_primary').attr("value", "");

	    });

	    $("body").on("click",".remove_company_email",function(){ 
	        var remove_company_email_button = $(this);
	    	bootbox.confirm("Are you comfirm delete this Email?", function (result) {
	            if (result) {

	            	remove_company_email_button.parents(".fieldGroup_company_email").remove();

	            	if (remove_company_email_button.parent().find(".company_email_primary").is(":checked")) 
					{
						if ($(".company_email_toggle").find(".second_company_email").length > 0) 
						{
							$(".company_email_toggle .fieldGroup_company_email").eq(0).find('.company_email_primary').prop( "checked", true );
						}
						else
						{
							$(".main_company_email_primary").prop( "checked", true );
						}
					}

	            	if ($(".company_email_toggle").find(".second_company_email").length > 0) 
					{
						$(".show_company_email").css({"visibility": "visible"});

			       	}
			       	else {
			       		$(".show_company_email").css({"visibility": "hidden"});
			       		
			       	}
	            }
	        });
	    });

		$('.main_company_email').keyup(function(){

			if ($(this).val()) {
				$(".button_increment_company_email").css({"visibility": "visible"});

	       	}
	       	else {
	       		$(".button_increment_company_email").css({"visibility": "hidden"});
	       	}
		});

		//console.log($(".local_fix_line_toggle").find(".second_local_fix_line").length);

		if ($(".local_fix_line_toggle").find(".second_local_fix_line").length > 0) 
		{
			$(".show_local_fix_line").css({"visibility": "visible"});
			$(".local_fix_line_toggle").hide();

       	}
       	else {
       		$(".show_local_fix_line").css({"visibility": "hidden"});
       		$(".local_fix_line_toggle").hide();
       	}

       	if ($(".local_mobile_toggle").find(".second_local_mobile").length > 0) 
		{
			$(".show_local_mobile").css({"visibility": "visible"});
			$(".local_mobile_toggle").hide();

       	}
       	else {
       		$(".show_local_mobile").css({"visibility": "hidden"});
       		$(".local_mobile_toggle").hide();
       	}

       	if ($(".local_email_toggle").find(".second_email").length > 0) 
		{
			$(".show_email").css({"visibility": "visible"});
			$(".local_email_toggle").hide();

       	}
       	else {
       		$(".show_email").css({"visibility": "hidden"});
       		$(".local_email_toggle").hide();
       	}

       	if ($(".company_email_toggle").find(".second_company_email").length > 0) 
		{
			$(".show_company_email").css({"visibility": "visible"});
			$(".company_email_toggle").hide();

       	}
       	else {
       		$(".show_company_email").css({"visibility": "hidden"});
       		$(".company_email_toggle").hide();
       	}

       	if ($(".company_phone_number_toggle").find(".second_company_phone_number").length > 0) 
		{
			$(".show_company_phone_number").css({"visibility": "visible"});
			$(".company_phone_number_toggle").hide();

       	}
       	else {
       		$(".show_company_phone_number").css({"visibility": "hidden"});
       		$(".company_phone_number_toggle").hide();
       	}
	});


	$('#date_of_birth').datepicker({ 
	    endDate: date
	});

	$("#identification_type").live('change',function(){
		$( '#form_identification_type' ).html("");
	});
	$("#identification_no").live('change',function(){
		$( '#form_identification_no' ).html("");
	});
	$("#name").live('change',function(){
		$( '#form_name' ).html("");
	});
	$("#date_of_birth").live('change',function(){
		$( '#form_date_of_birth' ).html("");
	});
	$("#postal_code1").live('change',function(){
		$( '#form_postal_code1' ).html("");
	});
	$("#street_name1").live('change',function(){
		$( '#form_street_name1' ).html("");
	});
	$("#postal_code2").live('change',function(){
		$( '#form_postal_code2' ).html("");
	});
	$("#street_name2").live('change',function(){
		$( '#form_street_name2' ).html("");
	});
	$("#foreign_address1").live('change',function(){
		$( '#form_foreign_address1' ).html("");
	});
	$("#foreign_address2").live('change',function(){
		$( '#form_foreign_address2' ).html("");
	});
	$(".nationality").live('change',function(){
		$( '#form_nationality' ).html("");
	});
	$("#local_fix_line").live('change',function(){
		$( '#form_local_fix_line' ).html("");
	});
	$("#local_mobile").live('change',function(){
		$( '#form_local_mobile' ).html("");
	});
	$("#email").live('change',function(){
		$( '#form_email' ).html("");
	});

	$("#company_name").live('change',function(){
		$( '#form_company_name' ).html("");
	});
	$("#register_no").live('change',function(){
		$( '#form_register_no' ).html("");
	});
	$("#date_of_incorporation").live('change',function(){
		$( '#form_date_of_incorporation' ).html("");
	});
	$("#country_of_incorporation").live('change',function(){
		$( '#form_country_of_incorporation' ).html("");
	});
	$("#company_postal_code").live('change',function(){
		$( '#form_company_postal_code' ).html("");
	});
	$("#company_street_name").live('change',function(){
		$( '#form_company_street_name' ).html("");
	});
	$("#company_foreign_address1").live('change',function(){
		$( '#form_company_foreign_address1' ).html("");
	});
	$("#company_foreign_address2").live('change',function(){
		$( '#form_company_foreign_address2' ).html("");
	});
	$("#company_email").live('change',function(){
		$( '#form_company_email' ).html("");
	});
	$("#company_phone_number").live('change',function(){
		$( '#form_company_phone_number' ).html("");
	});
	$("#company_corporate_representative").live('change',function(){
		$( '#form_company_corporate_representative' ).html("");
	});

	$(document).on('submit', '#submit_company', function (e) {
        e.preventDefault();
        var form = $('#submit_company');
        //document.getElementById("nationalityId").disabled=false;
        $('#loadingmessage').show();
        $(".company_phone_number_disabled .check_empty_company_phone_number").attr("disabled", "disabled");
		$(".company_phone_number_disabled .hidden_company_phone_number").attr("disabled", "disabled");
		$(".company_email_disabled .check_empty_company_email").attr("disabled", "disabled");
        $.ajax({ //Upload common input
                url: "personprofile/updateCompany",
                type: "POST",
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                	$('#loadingmessage').hide();
                	$(".company_phone_number_disabled .check_empty_company_phone_number").removeAttr("disabled");
					$(".company_phone_number_disabled .hidden_company_phone_number").removeAttr("disabled");
					$(".company_email_disabled .check_empty_company_email").removeAttr("disabled");
                	//console.log(response.Status);
                    if (response.Status === 1) {
					    $('#multiple_company_file').fileinput('upload');
                    }
                    else if(response.Status === 2)
                    {
                    	toastr.error("This Register No already in the system.", "Error");
                    }
                    else
                    {
                    	toastr.error("Please complete all required field", "Error");
                    	if (response.error["register_no"] != "")
                    	{
                    		var errorsRegisterNo = '<span class="help-block">*' + response.error["register_no"] + '</span>';
                    		$( '#form_register_no' ).html( errorsRegisterNo );

                    	}
                    	else
                    	{
                    		var errorsRegisterNo = '';
                    		$( '#form_register_no' ).html( errorsRegisterNo );
                    	}

                    	if (response.error["company_name"] != "")
                    	{
                    		var errorsCompanyName = '<span class="help-block">*' + response.error["company_name"] + '</span>';
                    		$( '#form_company_name' ).html( errorsCompanyName );

                    	}
                    	else
                    	{
                    		var errorsCompanyName = '';
                    		$( '#form_company_name' ).html( errorsCompanyName );
                    	}

						/*if (response.error["date_of_incorporation"] != "")
                    	{
                    		var errorsDateOfIncorporation = '<span class="help-block">*' + response.error["date_of_incorporation"] + '</span>';
                    		$( '#form_date_of_incorporation' ).html( errorsDateOfIncorporation );

                    	}
                    	else
                    	{
                    		var errorsDateOfIncorporation = '';
                    		$( '#form_date_of_incorporation' ).html( errorsDateOfIncorporation );
                    	}

                    	if (response.error["country_of_incorporation"] != "")
                    	{
                    		var errorsCountryOfIncorporation = '<span class="help-block">*' + response.error["country_of_incorporation"] + '</span>';
                    		$( '#form_country_of_incorporation' ).html( errorsCountryOfIncorporation );

                    	}
                    	else
                    	{
                    		var errorsCountryOfIncorporation = '';
                    		$( '#form_country_of_incorporation' ).html( errorsCountryOfIncorporation );
                    	}*/

                    	if (response.error["company_postal_code"] != "")
                    	{
                    		var errorsCompanyPostalCode = '<span class="help-block">*' + response.error["company_postal_code"] + '</span>';
                    		$( '#form_company_postal_code' ).html( errorsCompanyPostalCode );

                    	}
                    	else
                    	{
                    		var errorsCompanyPostalCode = '';
                    		$( '#form_company_postal_code' ).html( errorsCompanyPostalCode );
                    	}

                    	if (response.error["company_street_name"] != "")
                    	{
                    		var errorsCompanyStreetName = '<span class="help-block">*' + response.error["company_street_name"] + '</span>';
                    		$( '#form_company_street_name' ).html( errorsCompanyStreetName );

                    	}
                    	else
                    	{
                    		var errorsCompanyStreetName = '';
                    		$( '#form_company_street_name' ).html( errorsCompanyStreetName );
                    	}

                    	if (response.error["company_foreign_address1"] != "")
                    	{
                    		var errorsComapanyForeignAdd1 = '<span class="help-block">*' + response.error["company_foreign_address1"] + '</span>';
                    		$( '#form_company_foreign_address1' ).html( errorsComapanyForeignAdd1 );

                    	}
                    	else
                    	{
                    		var errorsComapanyForeignAdd1 = '';
                    		$( '#form_company_foreign_address1' ).html( errorsComapanyForeignAdd1 );
                    	}

                    	/*if (response.error["company_foreign_address2"] != "")
                    	{
                    		var errorsComapanyForeignAdd2 = '<span class="help-block">*' + response.error["company_foreign_address2"] + '</span>';
                    		$( '#form_company_foreign_address2' ).html( errorsComapanyForeignAdd2 );

                    	}
                    	else
                    	{
                    		var errorsComapanyForeignAdd2 = '';
                    		$( '#form_company_foreign_address2' ).html( errorsComapanyForeignAdd2 );
                    	}*/






                    	if (response.error["company_email"] != "")
                    	{
                    		var errorsComapanyEmail = '<span class="help-block">*' + response.error["company_email"] + '</span>';
                    		$( '#form_company_email' ).html( errorsComapanyEmail );

                    	}
                    	else
                    	{
                    		var errorsComapanyEmail = '';
                    		$( '#form_company_email' ).html( errorsComapanyEmail );
                    	}

                    	if (response.error["company_phone_number"] != "")
                    	{
                    		var errorsComapanyPhoneNumber = '<span class="help-block">*' + response.error["company_phone_number"] + '</span>';
                    		$( '#form_company_phone_number' ).html( errorsComapanyPhoneNumber );

                    	}
                    	else
                    	{
                    		var errorsComapanyPhoneNumber = '';
                    		$( '#form_company_phone_number' ).html( errorsComapanyPhoneNumber );
                    	}

                    	/*if (response.error["company_corporate_representative"] != "")
                    	{
                    		var errorsComapanyCorporateRepresentative = '<span class="help-block">*' + response.error["company_corporate_representative"] + '</span>';
                    		$( '#form_company_corporate_representative' ).html( errorsComapanyCorporateRepresentative );

                    	}
                    	else
                    	{
                    		var errorsComapanyCorporateRepresentative = '';
                    		$( '#form_company_corporate_representative' ).html( errorsComapanyCorporateRepresentative );
                    	}*/
                    }
                }
   			 })
	});
	var boolNationality = "false";
	$(document).on('submit', '#upload', function (e) {
        e.preventDefault();
        var form = $('#upload');
       // console.log(document.getElementById("nationalityId").disabled);
        if(document.getElementById("nationalityId").disabled == true && boolNationality == "false")
        {
        	document.getElementById("nationalityId").disabled=false;
        	boolNationality = "true";
        }
        
        $('#loadingmessage').show();
        
		$(".local_fix_line_disabled .check_empty_local_fix_line").attr("disabled", "disabled");
		$(".local_fix_line_disabled .hidden_local_fix_line").attr("disabled", "disabled");
		$(".local_mobile_disabled .check_empty_local_mobile").attr("disabled", "disabled");
		$(".local_mobile_disabled .hidden_local_mobile").attr("disabled", "disabled");
		$(".email_disabled .check_empty_email").attr("disabled", "disabled");
        $.ajax({ //Upload common input
                url: "personprofile/update",
                type: "POST",
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                	$('#loadingmessage').hide();
                	$(".local_fix_line_disabled .check_empty_local_fix_line").removeAttr("disabled");
                	$(".local_fix_line_disabled .hidden_local_fix_line").removeAttr("disabled");
					$(".local_mobile_disabled .check_empty_local_mobile").removeAttr("disabled");
					$(".local_mobile_disabled .hidden_local_mobile").removeAttr("disabled");
					$(".email_disabled .check_empty_email").removeAttr("disabled");
                	console.log(response);
                    if (response.Status === 1) {

					    $('#multiple_file').fileinput('upload');
                    }
                    else if(response.Status === 2)
                    {
                    	toastr.error("This Identification No already in the system.", "Error");
                    }
                    else if(response.Status === 3)
                    {
                    	toastr.error("Local address and alternate address cannot be the same", "Error");
                    }
                    else
                    {
                    	//console.log("fail");
                    	toastr.error("Please complete all required field", "Error");
                    	//console.log(response.error["nationality"]);
                    	if (response.error["identification_no"] != "")
                    	{
                    		var errorsIdentificationNo = '<span class="help-block">*' + response.error["identification_no"] + '</span>';
                    		$( '#form_identification_no' ).html( errorsIdentificationNo );

                    	}
                    	else
                    	{
                    		var errorsIdentificationNo = '';
                    		$( '#form_identification_no' ).html( errorsIdentificationNo );
                    	}
                    	if (response.error["name"] != "")
                    	{
                    		var errorsName = '<span class="help-block">*' + response.error["name"] + '</span>';
                    		$( '#form_name' ).html( errorsName );

                    	}
                    	else
                    	{
                    		var errorsName = '';
                    		$( '#form_name' ).html( errorsName );
                    	}
                    	if (response.error["date_of_birth"] != "")
                    	{
                    		var errorsDateOfBirth = '<span class="help-block">*' + response.error["date_of_birth"] + '</span>';
                    		$( '#form_date_of_birth' ).html( errorsDateOfBirth );

                    	}
                    	else
                    	{
                    		var errorsDateOfBirth = '';
                    		$( '#form_date_of_birth' ).html( errorsDateOfBirth );
                    	}
                    	if (response.error["postal_code1"] != "")
                    	{
                    		var errorsPostalCode1 = '<span class="help-block">*' + response.error["postal_code1"] + '</span>';
                    		$( '#form_postal_code1' ).html( errorsPostalCode1 );

                    	}
                    	else
                    	{
                    		var errorsPostalCode1 = '';
                    		$( '#form_postal_code1' ).html( errorsPostalCode1 );
                    	}
                    	if (response.error["street_name1"] != "")
                    	{
                    		var errorsStreetName1 = '<span class="help-block">*' + response.error["street_name1"] + '</span>';
                    		$( '#form_street_name1' ).html( errorsStreetName1 );

                    	}
                    	else
                    	{
                    		var errorsStreetName1 = '';
                    		$( '#form_street_name1' ).html( errorsStreetName1 );
                    	}
                    	if (response.error["postal_code2"] != "")
                    	{
                    		var errorsPostalCode2 = '<span class="help-block">*' + response.error["postal_code2"] + '</span>';
                    		$( '#form_postal_code2' ).html( errorsPostalCode2 );

                    	}
                    	else
                    	{
                    		var errorsPostalCode2 = '';
                    		$( '#form_postal_code2' ).html( errorsPostalCode2 );
                    	}
                    	if (response.error["street_name2"] != "")
                    	{
                    		var errorsStreetName2 = '<span class="help-block">*' + response.error["street_name2"] + '</span>';
                    		$( '#form_street_name2' ).html( errorsStreetName2 );

                    	}
                    	else
                    	{
                    		var errorsStreetName2 = '';
                    		$( '#form_street_name2' ).html( errorsStreetName2 );
                    	}
                    	if (response.error["nationality"] != "")
                    	{
                    		var errorsNationality = '<span class="help-block">*' + response.error["nationality"] + '</span>';
                    		$( '#form_nationality' ).html( errorsNationality );

                    	}
                    	else
                    	{
                    		var errorsNationality = '';
                    		$( '#form_nationality' ).html( errorsNationality );
                    	}
                    	if (response.error["local_fix_line"] != "")
                    	{
                    		var errorsLocalFixLine = '<span class="help-block">*' + response.error["local_fix_line"] + '</span>';
                    		$( '#form_local_fix_line' ).html( errorsLocalFixLine );

                    	}
                    	else
                    	{
                    		var errorsLocalFixLine = '';
                    		$( '#form_local_fix_line' ).html( errorsLocalFixLine );
                    	}
                    	if (response.error["local_mobile"] != "")
                    	{
                    		var errorsLocalMobile = '<span class="help-block">*' + response.error["local_mobile"] + '</span>';
                    		$( '#form_local_mobile' ).html( errorsLocalMobile );

                    	}
                    	else
                    	{
                    		var errorsLocalMobile = '';
                    		$( '#form_local_mobile' ).html( errorsLocalMobile );
                    	}
                    	if (response.error["foreign_address1"] != "")
                    	{
                    		var errorsForeignAddress1 = '<span class="help-block">*' + response.error["foreign_address1"] + '</span>';
                    		$( '#form_foreign_address1' ).html( errorsForeignAddress1 );

                    	}
                    	else
                    	{
                    		var errorsForeignAddress1 = '';
                    		$( '#form_foreign_address1' ).html( errorsForeignAddress1 );
                    	}
                    	/*if (response.error["foreign_address2"] != "")
                    	{
                    		var errorsForeignAddress2 = '<span class="help-block">*' + response.error["foreign_address2"] + '</span>';
                    		$( '#form_foreign_address2' ).html( errorsForeignAddress2 );

                    	}
                    	else
                    	{
                    		var errorsForeignAddress2 = '';
                    		$( '#form_foreign_address2' ).html( errorsForeignAddress2 );
                    	}*/
                    	if (response.error["email"] != "")
                    	{
                    		var errorsEmail = '<span class="help-block">*' + response.error["email"] + '</span>';
                    		$( '#form_email' ).html( errorsEmail );

                    	}
                    	else
                    	{
                    		var errorsEmail = '';
                    		$( '#form_email' ).html( errorsEmail );
                    	}
                    }
                }
            });
			//document.getElementById("nationalityId").disabled=true;
			//console.log(boolNationality);
			if(document.getElementById("nationalityId").disabled == false && boolNationality == "true")
	        {
	        	document.getElementById("nationalityId").disabled=true;
	        	boolNationality = "false";
	        }
    });
	//console.log(person['building_name1'] != null);
	/*if(person['company_street_name'] != null || person['company_building_name'] != null)
	{
		$("#company_street_name").attr("readonly", true);
		$("#company_building_name").attr("readonly", true);
	}

	if(person['street_name1'] != "" || person['building_name1'] != "")
	{
		if(person['street_name1'] != undefined || person['building_name1'] != undefined)
		{
			$("#street_name1").attr("readonly", true);
			$("#building_name1").attr("readonly", true);
		}
	}*/


	/*if(person['street_name2'] != "" || person['building_name2'] != "")
	{
		if(person['street_name2'] != undefined || person['building_name2'] != undefined)
		{
			$("#street_name2").attr("readonly", true);
			$("#building_name2").attr("readonly", true);
		}
	}*/
	
	$('#company_postal_code').keyup(function(){
  		if($(this).val().length == 6){
	  		var zip = $(this).val();
			//var address = "068914";
			$.ajax({
			  url:    'https://gothere.sg/maps/geo',
			  dataType: 'jsonp',
			  data:   {
			    'output'  : 'json',
			    'q'     : zip,
			    'client'  : '',
			    'sensor'  : false
			  },
			  type: 'GET',
			  success: function(data) {
			    //console.log(data);
			    //var field = $("textarea");
			    var myString = "";
			    
			    var status = data.Status;
			    
			    if (status.code == 200) {         
			      for (var i = 0; i < data.Placemark.length; i++) {
			        var placemark = data.Placemark[i];
			        var status = data.Status[i];

			        $("#company_street_name").val(placemark.AddressDetails.Country.Thoroughfare.ThoroughfareName);

			        if(placemark.AddressDetails.Country.AddressLine == "undefined")
			        {
			        	$("#company_building_name").val("");
			        }
			        else
			        {
			        	$("#company_building_name").val(placemark.AddressDetails.Country.AddressLine);
			        }
			        

			        /*$("#company_street_name").attr("readonly", true);
			        $("#company_building_name").attr("readonly", true);*/

			   
			      }
			      $( '#form_company_postal_code' ).html('');
			      $( '#form_company_street_name' ).html('');
			      //field.val(myString);
			    } else if (status.code == 603) {
			    	$( '#form_company_postal_code' ).html('<span class="help-block">*No Record Found</span>');
			      //field.val("No Record Found");
			    }

			  },
			  statusCode: {
			    404: function() {
			      alert('Page not found');
			    }
			  },
		    });
		}
		else
		{
			$("#company_street_name").val("");
			$("#company_building_name").val("");

			/*$("#company_street_name").attr("readonly", false);
			$("#company_building_name").attr("readonly", false);*/
		}
	});

	$('#postal_code1').keyup(function(){
  		if($(this).val().length == 6){
	  		var zip = $(this).val();
			//var address = "068914";
			$.ajax({
			  url:    'https://gothere.sg/maps/geo',
			  dataType: 'jsonp',
			  data:   {
			    'output'  : 'json',
			    'q'     : zip,
			    'client'  : '',
			    'sensor'  : false
			  },
			  type: 'GET',
			  success: function(data) {
			    //console.log(data);
			    //var field = $("textarea");
			    var myString = "";
			    
			    var status = data.Status;
			    
			    if (status.code == 200) {         
			      for (var i = 0; i < data.Placemark.length; i++) {
			        var placemark = data.Placemark[i];
			        var status = data.Status[i];

			        $("#street_name1").val(placemark.AddressDetails.Country.Thoroughfare.ThoroughfareName);

			        if(placemark.AddressDetails.Country.AddressLine == "undefined")
			        {
			        	$("#building_name1").val("");
			        }
			        else
			        {
			        	$("#building_name1").val(placemark.AddressDetails.Country.AddressLine);
			        }
			        

			        /*$("#street_name1").attr("readonly", true);
			        $("#building_name1").attr("readonly", true);*/

			   
			      }
			      $( '#form_postal_code1' ).html('');
			      $( '#form_street_name1' ).html('');
			      //field.val(myString);
			    } else if (status.code == 603) {
			    	$( '#form_postal_code1' ).html('<span class="help-block">*No Record Found</span>');
			      //field.val("No Record Found");
			    }

			  },
			  statusCode: {
			    404: function() {
			      alert('Page not found');
			    }
			  },
		    });
		}
		else
		{
			$("#street_name1").val("");
			$("#building_name1").val("");

			/*$("#street_name1").attr("readonly", false);
			$("#building_name1").attr("readonly", false);*/
		}
	});

	$('#postal_code2').keyup(function(){
  		if($(this).val().length == 6)
  		{
	  		var zip = $(this).val();
			//var address = "068914";
			if($("#postal_code1").val() == zip)
			{
				toastr.error("Local address and alternate address cannot be the same", "Error");
			}
			else
			{
				$.ajax({
				  url:    'https://gothere.sg/maps/geo',
				  dataType: 'jsonp',
				  data:   {
				    'output'  : 'json',
				    'q'     : zip,
				    'client'  : '',
				    'sensor'  : false
				  },
				  type: 'GET',
				  success: function(data) {
				    //console.log(data);
				    //var field = $("textarea");
				    var myString = "";
				    
				    var status = data.Status;
				    
				    if (status.code == 200) {         
				      for (var i = 0; i < data.Placemark.length; i++) {
				        var placemark = data.Placemark[i];
				        var status = data.Status[i];

				        $("#street_name2").val(placemark.AddressDetails.Country.Thoroughfare.ThoroughfareName);

				        if(placemark.AddressDetails.Country.AddressLine == "undefined")
				        {
				        	$("#building_name2").val("");
				        }
				        else
				        {
				        	$("#building_name2").val(placemark.AddressDetails.Country.AddressLine);
				        }
				        

				        /*$("#street_name2").attr("readonly", true);
				        $("#building_name2").attr("readonly", true);*/

				   
				      }
				      $( '#form_postal_code2' ).html('');
				      //field.val(myString);
				    } else if (status.code == 603) {
				    	$( '#form_postal_code2' ).html('<span class="help-block">*No Record Found</span>');
				      //field.val("No Record Found");
				    }

				  },
				  statusCode: {
				    404: function() {
				      alert('Page not found');
				    }
				  },
			    });
			}
		}
		else
		{
			$("#street_name2").val("");
			$("#building_name2").val("");

			/*$("#street_name2").attr("readonly", false);
			$("#building_name2").attr("readonly", false);*/
		}

	});

    var initialPreviewArray = []; 
	var initialPreviewConfigArray = [];

	if(person['foreign_address1'] != "" || person['foreign_address2'] != "" || person['foreign_address3'] != "")
	{
		if (undefined !== person['foreign_address1'] && person['foreign_address1'].length || undefined !== person['foreign_address2'] && person['foreign_address2'].length) 
		{
			$('input[name="foreign_address1"]').removeAttr('disabled');
			$('input[name="foreign_address2"]').removeAttr('disabled');
			$('input[name="foreign_address3"]').removeAttr('disabled');
		}
		else
		{
			$('input[name="foreign_address1"]').attr('disabled', 'true');
			$('input[name="foreign_address2"]').attr('disabled', 'true');
			$('input[name="foreign_address3"]').attr('disabled', 'true');
		}
		/*$('input[name="foreign_address1"]').removeAttr('disabled');
		$('input[name="foreign_address2"]').removeAttr('disabled');*/
		
	}
	else if(person['foreign_address1'] == "" || person['foreign_address2'] == "" || person['foreign_address3'] == "")
	{
		$('input[name="foreign_address1"]').attr('disabled', 'true');
		$('input[name="foreign_address2"]').attr('disabled', 'true');
		$('input[name="foreign_address3"]').attr('disabled', 'true');
	}
	else
	{
		$('input[name="foreign_address1"]').attr('disabled', 'true');
		$('input[name="foreign_address2"]').attr('disabled', 'true');
		$('input[name="foreign_address3"]').attr('disabled', 'true');
	}
	
	if(person['postal_code1'] != "")
	{

		$('input[name="postal_code1"]').removeAttr('disabled');

	}
	else
	{
		$('input[name="postal_code1"]').attr('disabled', 'true');
	}

	if(person['street_name1'] != "")
	{

		$('input[name="street_name1"]').removeAttr('disabled');

	}
	else
	{
		$('input[name="street_name1"]').attr('disabled', 'true');
	}

	var alternate_address = document.getElementById('alternate_address');
	if(alternate_address.checked)
	{
		if(person['postal_code2'] != "")
		{

			$('input[name="postal_code2"]').removeAttr('disabled');

		}
		else
		{
			$('input[name="postal_code2"]').attr('disabled', 'true');
		}

		if(person['street_name2'] != "")
		{

			$('input[name="street_name2"]').removeAttr('disabled');

		}
		else
		{
			$('input[name="street_name2"]').attr('disabled', 'true');
		}
	}
	else
	{
		$('input[name="postal_code2"]').attr('disabled', 'true');
		$('input[name="street_name2"]').attr('disabled', 'true');
	}

	
	
	//console.log("person['company_foreign_address1']="+person['company_foreign_address1']);
	if(person['company_foreign_address1'] != "" || person['company_foreign_address2'] != "" || person['company_foreign_address3'] != "")
	{
		$('input[name="company_foreign_address1"]').removeAttr('disabled');
		$('input[name="company_foreign_address2"]').removeAttr('disabled');
		$('input[name="company_foreign_address3"]').removeAttr('disabled');
	}
	else if(person['company_foreign_address1'] == "" || person['company_foreign_address2'] == "" || person['company_foreign_address3'] == "")
	{
		$('input[name="company_foreign_address1"]').attr('disabled', 'true');
		$('input[name="company_foreign_address2"]').attr('disabled', 'true');
		$('input[name="company_foreign_address3"]').attr('disabled', 'true');
	}
	else
	{
		$('input[name="company_foreign_address1"]').attr('disabled', 'true');
		$('input[name="company_foreign_address2"]').attr('disabled', 'true');
		$('input[name="company_foreign_address3"]').attr('disabled', 'true');
	}

	if(person['company_postal_code'] != "")
	{
		$('input[name="company_postal_code"]').removeAttr('disabled');
	}
	else
	{
		$('input[name="company_postal_code"]').attr('disabled', 'true');
	}

	if(person['company_street_name'] != "")
	{
		$('input[name="company_street_name"]').removeAttr('disabled');
	}
	else
	{
		$('input[name="company_street_name"]').attr('disabled', 'true');
	}
	

	//console.log(files);
	if(files != null)
	{
		for (var i = 0; i < files.length; i++) {
			
		  var url = base_url + "uploads/images_or_pdf/";
		  var fileArray = files[i].split(',');
		  //console.log(fileArray[0]);
		  initialPreviewArray.push( url + fileArray[1] );
		  var file_type = fileArray[1].substring(fileArray[1].lastIndexOf('.'));
		  //console.log(file_type);
		  	if(file_type == ".pdf" || file_type == ".PDF")
		  	{
			  initialPreviewConfigArray.push({
				  type: "pdf",
			      caption: fileArray[1],
			      url: "/secretary/personprofile/deleteFile/" + fileArray[0],
			      width: "120px",
			      key: i+1
			  });
			}
			else
			{
				initialPreviewConfigArray.push({
			      caption: fileArray[1],
			      url: "/secretary/personprofile/deleteFile/" + fileArray[0],
			      width: "120px",
			      key: i+1
			  });
			}
		}
	}

	

	$("#multiple_file").fileinput({
        theme: 'fa',
        uploadUrl: '/secretary/personprofile/uploadFile', // you must set a valid URL here else you will get an error
        uploadAsync: false,
        browseClass: "btn btn-primary",
        fileType: "any",
        showCaption: false,
        showUpload: false,
        showRemove: false,
        fileActionSettings: {
                        showRemove: true,
                        showUpload: false,
                        showZoom: true,
                        showDrag: true,
                    },
        previewFileIcon: "<i class='glyphicon glyphicon-king'></i>",
        overwriteInitial: false,
        initialPreviewAsData: true,
        initialPreviewDownloadUrl: base_url + 'uploads/images_or_pdf/{filename}',
        initialPreview: initialPreviewArray,
     	initialPreviewConfig: initialPreviewConfigArray,
     	//deleteUrl: "/dot/personprofile/deleteFile",
     	/*maxFileSize: 20000048,
     	maxImageWidth: 1000,
	    maxImageHeight: 1500,
	    resizePreference: 'height',
	    resizeImage: true,*/
     	purifyHtml: true // this by default purifies HTML data for preview
        /*uploadExtraData: { 
        	officer_id: $('input[name="officer_id"]').val() 
        }*/
        /*width:auto;height:auto;max-width:100%;max-height:100%;*/

    }).on('filesorted', function(e, params) {
	    console.log('File sorted params', params);
	}).on('filebatchuploadsuccess', function(event, data, previewId, index) {
		if($("#close_page").val() == 1)
		{
			window.close();
		}
		else
		{
			window.location.href = base_url + "personprofile";
			toastr.success("Information Updated", "Success");
		}
		
	    //console.log(data);
	}).on('fileuploaderror', function(event, data, msg) {
		if($("#close_page").val() == 1)
		{
			window.close();
		}
		else
		{
			window.location.href = base_url + "personprofile";
			toastr.success("Information Updated", "Success");
		}
		//toastr.error("Error", "Error");
	});

	//console.log(company_files);
	if(company_files != null)
	{
		for (var i = 0; i < company_files.length; i++) {
			
		  var url = base_url + "uploads/company_images_or_pdf/";
		  var fileArray = company_files[i].split(',');
		  //console.log(fileArray[0]);
		  initialPreviewArray.push( url + fileArray[1] );
		  var file_type = fileArray[1].substring(fileArray[1].lastIndexOf('.'));
		  //console.log(file_type);
		  	if(file_type == ".pdf" || file_type == ".PDF")
		  	{
			  initialPreviewConfigArray.push({
				  type: "pdf",
			      caption: fileArray[1],
			      url: "/secretary/personprofile/deleteCompanyFile/" + fileArray[0],
			      width: "120px",
			      key: i+1
			  });
			}
			else
			{
				initialPreviewConfigArray.push({
			      caption: fileArray[1],
			      url: "/secretary/personprofile/deleteCompanyFile/" + fileArray[0],
			      width: "120px",
			      key: i+1
			  });
			}
		}
	}

	$("#multiple_company_file").fileinput({
        theme: 'fa',
        uploadUrl: '/secretary/personprofile/uploadCompanyFile', // you must set a valid URL here else you will get an error
        uploadAsync: false,
        browseClass: "btn btn-primary",
        fileType: "any",
        showCaption: false,
        showUpload: false,
        showRemove: false,
        fileActionSettings: {
                        showRemove: true,
                        showUpload: false,
                        showZoom: true,
                        showDrag: true,
                    },
        previewFileIcon: "<i class='glyphicon glyphicon-king'></i>",
        overwriteInitial: false,
        initialPreviewAsData: true,
        initialPreviewDownloadUrl: base_url + 'uploads/company_images_or_pdf/{filename}',
        initialPreview: initialPreviewArray,
     	initialPreviewConfig: initialPreviewConfigArray,
     	//deleteUrl: "/dot/personprofile/deleteFile",
     	/*maxFileSize: 20000048,
     	maxImageWidth: 1000,
	    maxImageHeight: 1500,
	    resizePreference: 'height',
	    resizeImage: true,*/
     	purifyHtml: true // this by default purifies HTML data for preview
        /*uploadExtraData: { 
        	officer_id: $('input[name="officer_id"]').val() 
        }*/
        /*width:auto;height:auto;max-width:100%;max-height:100%;*/

    }).on('filesorted', function(e, params) {
	    console.log('File sorted params', params);
	}).on('filebatchuploadsuccess', function(event, data, previewId, index) {
		if($("#close_page").val() == 1)
		{
			window.close();
		}
		else
		{
			window.location.href = base_url + "personprofile";
			toastr.success("Information Updated", "Success");
		}
		
	    //console.log(data);
	}).on('fileuploaderror', function(event, data, msg) {
		if($("#close_page").val() == 1)
		{
			window.close();
		}
		else
		{
			window.location.href = base_url + "personprofile";
			toastr.success("Information Updated", "Success");
		}
		//toastr.error("Error", "Error");
	});

    /*$("#save").on('click', function (e) {
    	e.preventDefault();
    	console.log("save");
      

        $('#multiple_file').fileinput('upload');
    });*/


    /*$(".btn-warning").on('click', function () {
        var $el = $("#file-4");
        if ($el.attr('disabled')) {
            $el.fileinput('enable');
        } else {
            $el.fileinput('disable');
        }
    });
    $(".btn-info").on('click', function () {
        $("#file-4").fileinput('refresh', {previewClass: 'bg-info'});
    });*/
	/*$('#fine-uploader-manual-trigger').fineUploader({
            template: 'qq-template-manual-trigger',
            request: {
                endpoint: '/dot/uploads/images_or_pdf'
            },
            thumbnails: {
                placeholders: {
                    waitingPath: '/dot/img/waiting-generic.png',
                    notAvailablePath: '/dot/img/not_available-generic.png'
                }
            },
            validation: {
	            allowedExtensions: ['jpeg', 'jpg', 'pdf', 'png'],
	            sizeLimit: 10000000 // 50 kB = 50 * 1024 bytes
	        },
            autoUpload: false
        });

        $('#trigger-upload').click(function() {
            $('#fine-uploader-manual-trigger').fineUploader('uploadStoredFiles');
        });*/

     

    /* var abc = 0;*/
	 /*function handleFileSelect(event) {
	    var input = this;

	    var ext = input.files[0].type.substring(input.files[0].type.lastIndexOf('/'));
	    var str_image_application = input.files[0].type.replace(ext, "");

	    console.log(ext);

	    if (str_image_application == "image")
	    {
		    if (input.files && input.files.length) {
		    	
		    	for (var i = 0; i < input.files.length; i++) {
		    		var f = input.files[i];
		    		console.log(f);
			        var reader = new FileReader();
			        this.enabled = false
			        reader.onload = (function (e) {
			        console.log($("#preview").removeAttr("display"));
			        $("#preview").removeAttr("style");
			        $("#preview").attr("style","width: 600px; height:250px;");
			        
			            $("#preview").html(['<div class="image"><div class="img"><img src="', e.target.result, '" title="', input.files[0].name, '" style="max-width:100%;max-height:100%;"/><span class="icon-remove blue delete">x</span></div></div>'].join(''))
			        });
			        reader.readAsDataURL(f);
			    }
		    }
		}
		else if(str_image_application == "application")
		{
			if (input.files && input.files.length) {
		        var reader = new FileReader();
		        this.enabled = false
		        reader.onload = (function (e) {
		        	console.log(input.files);
		        	$("#preview").removeAttr("style");
			        
			        $("#preview").html(['<div class="image"><div class="img"><a href="', e.target.result, '" download>', input.files[0].name, '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><span class="icon-remove blue delete">x</span></div></div>'].join(''))
		        	
		        });
		        //console.log(reader.readAsDataURL(input.files[0]));
		        reader.readAsDataURL(input.files[0]);
		    }
		}
	}
	$('#file').change(handleFileSelect);
	$('#preview').on('click', '.delete', function () {
		$("#preview").removeAttr("style");
	    $("#preview").empty()
	    $("#file").val("");
	});*/

	$tab_aktif = "<?php print $person->field_type; ?>";
	
	if($tab_aktif == '')
	{
		$tab_aktif = "individual";
	}
	//console.log($tab_aktif);
	$(document).on('click',".check_stat",function() {
			
				
			
				$tab_aktif = $(this).data("information");
				//console.log($tab_aktif);
		});
		$(document).on('click',"#save",function(){
			console.log($tab_aktif);
			$("form #tr_"+$tab_aktif+"_edit").submit();
		});
		//$("#tr_company_edit").hide();

			$("#individual_edit_p").hide();
			$("#company_edit_p").hide();
			$("#shareholder_edit_p").hide();
			 $("#represen_edit").hide();
			$("#individual_add_p").hide();
			$("#company_add_p").hide();
			 $("#represen_add").hide();
			$("#shareholder_add_p").hide();
	$("#select_edit_p").on('change',function(){
			$("#individual_edit_p").hide();
			$("#company_edit_p").hide();
			$("#shareholder_edit_p").hide();
			 $("#represen_edit").hide();
		if ($(this).val() == 'comp'){
			$("#company_edit_p").show();
			 $("#represen_edit").show();
		}else if ($(this).val() == 'indv'){
			$("#individual_edit_p").show();
			$("#shareholder_edit_p").show();
		}
	});
	$("#select_add_p").on('change',function(){
			$("#individual_add_p").hide();
			$("#company_add_p").hide();
			 $("#represen_add").hide();
			$("#shareholder_add_p").hide();
		if ($(this).val() == 'comp'){
			$("#company_add_p").show();
			 $("#represen_add").show();
		}else if ($(this).val() == 'indv'){
			$("#individual_edit_p").show();
			$("#shareholder_edit_p").show();
		}
	});
	$("#local_add").click(function() {
		$("#tr_foreign_add").hide();
		$("#tr_local_add").show();
	});
	$("#foreign_add").click(function() {
		$("#tr_foreign_add").show();
		$("#tr_local_add").hide();
	});
	$("#local_edit").click(function() {
		$("#tr_foreign_edit").hide();
		$("#tr_local_edit").show();

		var alternate_address = document.getElementById('alternate_address');

		var foreign_address1 = document.getElementById('foreign_address1');
		var foreign_address2 = document.getElementById('foreign_address2');
		var foreign_address3 = document.getElementById('foreign_address3');

		$('input[name="postal_code1"]').removeAttr('disabled');
		$('input[name="street_name1"]').removeAttr('disabled');

		 if(alternate_address.checked == false)
        {
        	$('input[name="postal_code2"]').attr('disabled', 'true');
        	$('input[name="street_name2"]').attr('disabled', 'true');
        }

		/*$('input[name="postal_code2"]').removeAttr('disabled');
		$('input[name="street_name2"]').removeAttr('disabled');*/

		$('input[name="foreign_address1"]').attr('disabled', 'true');
		$('input[name="foreign_address2"]').attr('disabled', 'true');
		$('input[name="foreign_address3"]').attr('disabled', 'true');

		//console.log(foreign_address1);
	    /*for (var i = 0; i<foreign_address1.value.length; i++) {*/
	        switch (foreign_address1.type) {
	            case 'text':
	                foreign_address1.value = '';
	                break;
	        }
	    //}
	    /*for (var i = 0; i<foreign_address2.value.length; i++) {*/
	        switch (foreign_address2.type) {
	            case 'hidden':
	            case 'text':
	                foreign_address2.value = '';
	                break;
	            case 'radio':
	            case 'checkbox': 
	        }
	    //}
	    	switch (foreign_address3.type) {
	            case 'hidden':
	            case 'text':
	                foreign_address3.value = '';
	                break;
	            case 'radio':
	            case 'checkbox': 
	        }
	});
	$("#company_local_edit").click(function() {
		$("#tr_company_foreign_edit").hide();
		$("#tr_company_local_edit").show();

		var company_foreign_address1 = document.getElementById('company_foreign_address1');
		var company_foreign_address2 = document.getElementById('company_foreign_address2');
		var company_foreign_address3 = document.getElementById('company_foreign_address3');

		$('input[name="company_postal_code"]').removeAttr('disabled');
		$('input[name="company_street_name"]').removeAttr('disabled');

		$('input[name="company_foreign_address1"]').attr('disabled', 'true');
		$('input[name="company_foreign_address2"]').attr('disabled', 'true');
		$('input[name="company_foreign_address3"]').attr('disabled', 'true');

		$("#company_street_name").attr("readonly", false);
		$("#company_building_name").attr("readonly", false);

		//console.log(company_foreign_address1);
	    /*for (var i = 0; i<foreign_address1.value.length; i++) {*/
	        switch (company_foreign_address1.type) {
	            case 'text':
	                company_foreign_address1.value = '';
	                break;
	        }
	    //}
	    /*for (var i = 0; i<foreign_address2.value.length; i++) {*/
	        switch (company_foreign_address2.type) {
	            case 'hidden':
	            case 'text':
	                company_foreign_address2.value = '';
	                break;
	            case 'radio':
	            case 'checkbox': 
	        }
	    //}
	    	switch (company_foreign_address3.type) {
	            case 'hidden':
	            case 'text':
	                company_foreign_address3.value = '';
	                break;
	            case 'radio':
	            case 'checkbox': 
	        }

	});
	$("#foreign_edit").click(function() {
		$("#tr_foreign_edit").show();
		$("#tr_local_edit").hide();

		var alternate_address = document.getElementById('alternate_address');
		/*if(alternate_address.checked){
	        $("#alternate_text_edit").toggle();
	    }

		switch (alternate_address.type) {
            case 'checkbox':
                alternate_address.checked = false; 
                break;
        }*/

        $('input[name="postal_code1"]').attr('disabled', 'true');
        $('input[name="street_name1"]').attr('disabled', 'true');

        if(alternate_address.checked == false)
        {
        	$('input[name="postal_code2"]').attr('disabled', 'true');
        	$('input[name="street_name2"]').attr('disabled', 'true');
        }
        
        $('input[name="foreign_address1"]').removeAttr('disabled');
		$('input[name="foreign_address2"]').removeAttr('disabled');
		$('input[name="foreign_address3"]').removeAttr('disabled');

		/*$("#street_name1").attr("readonly", false);
		$("#building_name1").attr("readonly", false);

		$("#street_name2").attr("readonly", false);
		$("#building_name2").attr("readonly", false);*/


		for (var i = 1; i < 2; i++) {
			window['postal_code'+i] = document.getElementById('postal_code'+i);
			window['street_name'+i] = document.getElementById('street_name'+i);
			window['building_name'+i] = document.getElementById('building_name'+i);

			switch (window['postal_code'+i].type) {
	            case 'text':
	                window['postal_code'+i].value = '';
	                break;
	        }
	        switch (window['street_name'+i].type) {
	            case 'text':
	                window['street_name'+i].value = '';
	                break;
	        }
	        switch (window['building_name'+i].type) {
	            case 'text':
	                window['building_name'+i].value = '';
	                break;
	        }
		}
		for (var i = 1; i < 3; i++) {
			window['unit_no'+i] = document.getElementById('unit_no'+i);
			switch (window['unit_no'+i].type) {
	            case 'text':
	                window['unit_no'+i].value = '';
	                break;
	        }
		}

	});

	$("#company_foreign_edit").click(function() {
		$("#tr_company_foreign_edit").show();
		$("#tr_company_local_edit").hide();

		//var alternate_address = document.getElementById('alternate_address');
		// console.log(alternate_address);
		// console.log(alternate_address.checked);
		/*if(alternate_address.checked){
	        $("#alternate_text_edit").toggle();
	    }

		switch (alternate_address.type) {
            case 'checkbox':
                alternate_address.checked = false; 
                break;
        }*/

        $('input[name="company_postal_code"]').attr('disabled', 'true');
        $('input[name="company_street_name"]').attr('disabled', 'true');
        $('input[name="company_foreign_address1"]').removeAttr('disabled');
		$('input[name="company_foreign_address2"]').removeAttr('disabled');
		$('input[name="company_foreign_address3"]').removeAttr('disabled');

		$("#company_street_name").attr("readonly", false);
		$("#company_building_name").attr("readonly", false);


		/*for (var i = 1; i < 3; i++) {*/
			window['company_postal_code'] = document.getElementById('company_postal_code');
			window['company_street_name'+i] = document.getElementById('company_street_name');
			window['company_building_name'+i] = document.getElementById('company_building_name');

			switch (window['company_postal_code'].type) {
	            case 'text':
	                window['company_postal_code'].value = '';
	                break;
	        }
	        switch (window['company_street_name'].type) {
	            case 'text':
	                window['company_street_name'].value = '';
	                break;
	        }
	        switch (window['company_building_name'].type) {
	            case 'text':
	                window['company_building_name'].value = '';
	                break;
	        }
		//}

		for (var i = 1; i < 3; i++) {
			window['company_unit_no'+i] = document.getElementById('company_unit_no'+i);
			switch (window['company_unit_no'+i].type) {
	            case 'text':
	                window['company_unit_no'+i].value = '';
	                break;
	        }
		}
		

	});
	$("#company_edit").click(function() {
		$("#tr_individual_edit").hide();
		$("#tr_company_edit").show();

		var identification_type = document.getElementById('identification_type');
		identification_type.selectedIndex = 0;

		var individual_identification_no = document.getElementById('individual_identification_no');
		var identification_no = document.getElementById('identification_no');
		individual_identification_no.value = identification_no.value;
        identification_no.value = '';

        var name = document.getElementById('name');
        name.value = '';
        var date_of_birth = document.getElementById('date_of_birth');
        //console.log(date_of_birth);
        date_of_birth.value = '';

		var alternate_address = document.getElementById('alternate_address');
		if(alternate_address.checked){
	        $("#alternate_text_edit").toggle();
	    }
        alternate_address.checked = false; 

        var local_edit = document.getElementById('local_edit');
        local_edit.checked = "checked"; 

        var foreign_edit = document.getElementById('foreign_edit');
        foreign_edit.checked = false; 

        $("#tr_foreign_edit").hide();
		$("#tr_local_edit").show();

		$('input[name="postal_code1"]').removeAttr('disabled');
		$('input[name="street_name1"]').removeAttr('disabled');
		$('input[name="postal_code2"]').removeAttr('disabled');
		$('input[name="street_name2"]').removeAttr('disabled');

		$('input[name="foreign_address1"]').attr('disabled', 'true');
		$('input[name="foreign_address2"]').attr('disabled', 'true');
		$('input[name="foreign_address3"]').attr('disabled', 'true');

		$('input[name="company_foreign_address1"]').attr('disabled', 'true');
		$('input[name="company_foreign_address2"]').attr('disabled', 'true');
		$('input[name="company_foreign_address3"]').attr('disabled', 'true');

		$("#company_street_name").attr("readonly", false);
		$("#company_building_name").attr("readonly", false);

		/*$("#street_name").attr("readonly", false);
		$("#building_name").attr("readonly", false);

		$("#street_name1").attr("readonly", false);
		$("#building_name1").attr("readonly", false);

		$("#street_name2").attr("readonly", false);
		$("#building_name2").attr("readonly", false);*/

	    for (var i = 1; i < 3; i++) {
			window['postal_code'+i] = document.getElementById('postal_code'+i);
			window['street_name'+i] = document.getElementById('street_name'+i);
			window['building_name'+i] = document.getElementById('building_name'+i);

			/*$("#street_name"+i+"").attr("readonly", false);
			$("#building_name"+i+"").attr("readonly", false);*/

	        window['postal_code'+i].value = '';
	        window['street_name'+i].value = '';
	        window['building_name'+i].value = '';
		}
		for (var i = 1; i < 5; i++) {
			window['unit_no'+i] = document.getElementById('unit_no'+i);
	        window['unit_no'+i].value = '';
		}

		/*var foreign_address1 = document.getElementById('foreign_address1');
		var foreign_address2 = document.getElementById('foreign_address2');

	    foreign_address1.value = '';
	    foreign_address2.value = '';*/

	    /*var nationalityId = document.getElementById('nationalityId');
		nationalityId.selectedIndex = 0;*/

		var local_fix_line = document.getElementById('local_fix_line');
		var local_mobile = document.getElementById('local_mobile');
		var email = document.getElementById('email');

		local_fix_line.value = '';
	    local_mobile.value = '';
	    email.value = '';
	});
	$("#individual_edit").click(function() {
		$("#tr_individual_edit").show();
		$("#tr_company_edit").hide();

		var company_name = document.getElementById('company_name');
		var register_no = document.getElementById('register_no');
		var date_of_incorporation = document.getElementById('date_of_incorporation');
		var country_of_incorporation = document.getElementById('country_of_incorporation');
		var company_postal_code = document.getElementById('company_postal_code');
		var company_street_name = document.getElementById('company_street_name');
		var company_building_name = document.getElementById('company_building_name');
		var company_unit_no1 = document.getElementById('company_unit_no1');
		var company_unit_no2 = document.getElementById('company_unit_no2');
		var company_register_no = document.getElementById('company_register_no');

		company_register_no.value = register_no.value;

	    company_name.value = '';
	    register_no.value = '';
	    date_of_incorporation.value = '';
	    country_of_incorporation.value = '';
	    company_postal_code.value = '';
	    company_street_name.value = '';
	    company_building_name.value = '';
	    company_unit_no1.value = '';
	    company_unit_no2.value = '';

	    var company_local_edit = document.getElementById('company_local_edit');
        company_local_edit.checked = "checked"; 

        var company_foreign_edit = document.getElementById('company_foreign_edit');
        company_foreign_edit.checked = false; 

        $("#tr_company_foreign_edit").hide();
		$("#tr_company_local_edit").show();

		$('input[name="company_postal_code"]').removeAttr('disabled');
		$('input[name="company_street_name"]').removeAttr('disabled');
		$('input[name="foreign_address1"]').attr('disabled', 'true');
		$('input[name="foreign_address2"]').attr('disabled', 'true');
		$('input[name="foreign_address3"]').attr('disabled', 'true');

		$('input[name="company_foreign_address1"]').attr('disabled', 'true');
		$('input[name="company_foreign_address2"]').attr('disabled', 'true');
		$('input[name="company_foreign_address3"]').attr('disabled', 'true');

		$("#company_street_name").attr("readonly", false);
		$("#company_building_name").attr("readonly", false);

		/*$("#street_name").attr("readonly", false);
		$("#building_name").attr("readonly", false);

		$("#street_name1").attr("readonly", false);
		$("#building_name1").attr("readonly", false);

		$("#street_name2").attr("readonly", false);
		$("#building_name2").attr("readonly", false);*/

        window['company_postal_code'] = document.getElementById('company_postal_code');
			window['company_street_name'+i] = document.getElementById('company_street_name');
			window['company_building_name'+i] = document.getElementById('company_building_name');

			switch (window['company_postal_code'].type) {
	            case 'text':
	                window['company_postal_code'].value = '';
	                break;
	        }
	        switch (window['company_street_name'].type) {
	            case 'text':
	                window['company_street_name'].value = '';
	                break;
	        }
	        switch (window['company_building_name'].type) {
	            case 'text':
	                window['company_building_name'].value = '';
	                break;
	        }
		//}

		for (var i = 1; i < 3; i++) {
			window['company_unit_no'+i] = document.getElementById('company_unit_no'+i);
			switch (window['company_unit_no'+i].type) {
	            case 'text':
	                window['company_unit_no'+i].value = '';
	                break;
	        }
		}

		var company_foreign_address1 = document.getElementById('company_foreign_address1');
		var company_foreign_address2 = document.getElementById('company_foreign_address2');
		var company_foreign_address3 = document.getElementById('company_foreign_address3');

	    company_foreign_address1.value = '';
	    company_foreign_address2.value = '';
	    company_foreign_address3.value = '';
	});
	$("#alternate_label_edit").click(function() {
		$("#alternate_text_edit").toggle();
		var alternate_address = document.getElementById('alternate_address');
		if(alternate_address.checked)
		{
			$('input[name="postal_code2"]').removeAttr('disabled');
			$('input[name="street_name2"]').removeAttr('disabled');
		}
		else
		{
			$('input[name="postal_code2"]').attr('disabled', 'true');
			$('input[name="street_name2"]').attr('disabled', 'true');
		}
		$("#postal_code2").val("");
		$("#street_name2").val("");
		$("#building_name2").val("");
		$("#unit_no3").val("");
		$("#unit_no4").val("");
	});
	$("#alternate_label_add").click(function() {
		$("#alternate_text_add").toggle();
	});

	if(access_right_person_module == "read")
	{
		$('input').attr('disabled', true);
	}
</script>