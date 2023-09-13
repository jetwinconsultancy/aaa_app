		<?php
			$now = getDate();
			// $this->sma->print_arrays($client);
			//echo json_encode($now[0]);
				$this->session->set_userdata('unique_code', $client->unique_code);

			if ($this->session->userdata('unique_code') && $this->session->userdata('unique_code') != '')
			{
				$unique_code =$this->session->userdata('unique_code');
			} else {
				$unique_code = $this->session->userdata('username').'_'.$now[0];
				$this->session->set_userdata('unique_code', $client->unique_code);
			}
			$ndate = $now['mday']."/".$this->sma->addzero($now['mon'],2)."/".$now['year'];
			// $this->sma->print_arrays($ndate);
			// echo $ndate;
			$type_of_doc[""] = [];
			foreach ($typeofdoc as $cs) {
				$type_of_doc[$cs->id] = $cs->typeofdoc;
			}
			$doc_category[""] = [];
			foreach ($doccategory as $cs) {
				$doc_category[$cs->id] = $cs->doccategory;
			}
			$svc[""] = [];
			foreach ($service as $cs) {
				$svc[$cs->id] = $cs->service_name;
			}

			if ($this->session->userdata('company_code') && $this->session->userdata('company_code') != '')
			{
				$company_code =$this->session->userdata('company_code');
				//echo json_encode($company_code);
			} 
			else 
			{
				$company_code = 'company_'.$now[0];
				$this->session->set_userdata('company_code', $company_code);
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

												<!-- <?php if ($company_info_module != 'none') { ?> -->
												<!-- <?php
													}
												?> -->
												<?php if ((!$Individual && $Individual == true) || (!$Individual && $Individual == null && !$Client)) {?>
													<?php if ($company_info_module != 'none') { ?> 
														<li class="active check_stat" id="li-companyInfo" data-information="companyInfo">
															<a href="#w2-companyInfo" data-toggle="tab" class="text-center">
																<span class="badge hidden-xs">1</span>
																Company Info
															</a>
														</li>
													<?php
														}
													?> 
													<li class="check_stat hidden">
														<a href="#w2-director" data-toggle="tab" class="text-center">
															<span class="badge hidden-xs">2</span>
															Director
														</a>
													</li>
													<?php if ($officer_module != 'none') { ?> 
													<li class="check_stat" id="li-officer" data-information="officer" >
														<a href="#w2-officer" data-toggle="tab" class="text-center ">
															<span class="badge hidden-xs">2</span>
															Officers
														</a>
													</li>
													<?php
														}
													?> 
													<?php if ($member_module != 'none') { ?> 
													<li class="check_stat" id="li-capital" data-information="capital">
														<a href="#w2-capital" data-toggle="tab" class="text-center">
															<span class="badge hidden-xs">3</span>
															Members
														</a>
													</li>
													<?php
														}
													?> 
													<?php if ($controller_module != 'none') { ?> 
													<li class="check_stat" id="li-controller" data-information="controller">
														<a href="#w2-controller" data-toggle="tab" class="text-center ">
															<span class="badge hidden-xs">4</span>
															Controllers
														</a>
													</li>
													<?php
														}
													?> 
													<?php if ($charges_module != 'none') { ?> 
													<li class="check_stat" id="li-charges" data-information="charges">
														<a href="#w2-charges" data-toggle="tab" class="text-center ">
															<span class="badge hidden-xs">5</span>
															Charges
														</a>
													</li>
													<?php
														}
													?> 
													<?php if ($filing_module != 'none') { ?> 
													<li class="check_stat" id="li-filing" data-information="filing">
														<a href="#w2-filing" data-toggle="tab" class="text-center">
															<span class="badge hidden-xs">6</span>
															Filing
														</a>
													</li>
													<?php
														}
													?> 
													<?php if ($register_module != 'none') { ?>
													<li class="check_stat" id="li-register" data-information="register">
														<a href="#w2-register" data-toggle="tab" class="text-center">
															<span class="badge hidden-xs">7</span>
															Register
														</a>
													</li>
													<?php
														}
													?> 
													<?php if ($billing_module != 'none') { ?>
													<li class="check_stat" id="li-billing" data-information="billing">
														<a href="#w2-billing" data-toggle="tab" class="text-center">
															<span class="badge hidden-xs">8</span>
															Service Engagement
														</a>
													</li>
													<?php
														}
													?> 
													<?php if ($setup_module != 'none') { ?>
													<li class="check_stat" id="li-setup" data-information="setup">
														<a href="#w2-setup" data-toggle="tab" class="text-center">
															<span class="badge hidden-xs">9</span>
															Setup
														</a>
													</li>
													<?php
														}
													?> 
													
													<li class="check_stat" id="li-other" data-information="other" style="display: none">
														<a href="#w2-other" data-toggle="tab" class="text-center">
															<span class="badge hidden-xs">10</span>
															Others
														</a>
													</li>
												<?php
													}
												?> 
												<?php if ($Individual || $Client) {?>
													<li class="active check_stat" id="li-register" data-information="register">
														<a href="#w2-register" data-toggle="tab" class="text-center">
															<span class="badge hidden-xs">1</span>
															Register
														</a>
													</li>
													<li class="check_stat" id="li-setup" data-information="setup">
														<a href="#w2-setup" data-toggle="tab" class="text-center">
															<span class="badge hidden-xs">2</span>
															Setup
														</a>
													</li>
												<?php
													}
												?> 
											</ul>
											
															
												<div class="tab-content">
													<?php if ((!$Individual && $Individual == true) || (!$Individual && $Individual == null && !$Client)) {?>
													<?php if ($company_info_module != 'none') { ?>
													
													<div id="w2-companyInfo" class="tab-pane active">
														<!-- <?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
															echo form_open_multipart("masterclient/save", $attrib);
															
														?> -->

														<?php echo form_open_multipart('', array('id' => 'upload_company_info', 'enctype' => "multipart/form-data")); ?>

															<div class="hidden"><input type="text" class="form-control" name="company_code" value="<?=$company_code?>"/></div>
														<!-- <?php	
															if ($client->file_setup_for == '')
															{
														?>
														<div class="form-group">
														
															<label class="col-xs-4 control-label" for="w2-username">File Setup For</label>
															<div class="col-xs-6">
															<select class="col-xs-8 input-sm" style="text-align:right;" name="Slct_file_setup_for">
																<option value="incorporation_of_company">Incorporation of company</option>
																<option value="transferred_from_other_service_provider">Transferred from other service provider</option>
															</select>
															</div>
															<div class="col-xs-2">
																	<input type="file" id="" class="form-control input-sm" name="file_setup_for"/>
															</div>
														</div>
														<?php
															} else {
														?>
															<div class="form-group">
														
															<label class="col-xs-4 control-label" for="w2-username">File Setup For
																<strong><?=$client->Slct_file_setup_for?></strong></label>
															<label class="col-xs-4 control-label" for="w2-username">File :
																<a href="<?=site_url('uploads/'.$client->file_setup_for);?>"><?=$client->file_setup_for?></a></label>
															</div>
														<?php
															}
														?> -->
														<span style="font-size: 2.4rem;padding: 0; margin: 7px 0 4px 0;">Company Profile</span>
														<div class="form-group" style="margin-top: 20px;">
															<label class="col-sm-4 control-label" for="w2-username">Client Code: </label>
															<div class="col-sm-4">
																<input type="text" style="text-transform:uppercase" class="form-control" maxlength="20" id="client_code" name="client_code" value="<?=$client->client_code?>" >
																<div id="form_client_code"></div>
																<!-- <?php echo form_error('client_code','<span class="help-block">*','</span>'); ?> -->
															</div>
															<div class="col-sm-4">
																
																	<div style="float: right;">
																		<select id="acquried_by" class="form-control acquried_by" style="text-align:right; text-transform:uppercase;" name="acquried_by">
														                    <option value="0">client status:</option>
														                </select>
														                <div id="form_acquried_by"></div>
																	</div>
																
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-4 control-label" for="w2-username">Registration No: </label>
															<div class="col-sm-4">
																<input type="text" style="text-transform:uppercase" class="form-control" id="registration_no" name="registration_no" value="<?=$client->registration_no?>" >
																<div id="form_registration_no"></div>
																<!-- maxlength="10" -->
																<!-- <?php echo form_error('registration_no','<span class="help-block">*','</span>'); ?> -->
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-4 control-label" for="w2-username">Company Name: </label>
															<div class="col-sm-8">
																<input type="text" style="text-transform:uppercase" class="form-control" id="edit_company_name" name="company_name"  value="<?=$client->company_name?>">
																<div id="form_company_name"></div>
																<!-- <?php echo form_error('company_name','<span class="help-block">*','</span>'); ?> -->
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-4 control-label" for="w2-username">Former Name (if any): </label>
															<div class="col-sm-8">
																<textarea class="form-control" style="text-transform:uppercase" id="former_name" name="former_name" /><?=$client->former_name?></textarea>
																<div id="form_former_name"></div>
																<!-- <?php echo form_error('former_name','<span class="help-block">*','</span>'); ?> -->
																<!-- <input type="text" class="form-control input-sm" id="" name="former_name" value="<?=$client->former_name?>" > -->
															</div>
														</div>
														<!-- <div class="form-group" style="display:none;" id="file_add_client">
															<label class="col-sm-4 control-label" for="w2-username">File Setup 1</label>
															<div class="col-sm-6">
																	<input type="file" id="" class="form-control" name="file_setup1"/>
															</div>
															<label class="col-sm-4 control-label" for="w2-username">File Setup 2</label>
															<div class="col-sm-6">
																	<input type="file" id="" class="form-control" name="file_setup2" />
															</div>
														</div> -->
														<div class="form-group">
															<label class="col-sm-4 control-label" for="w2-username">Incorporation Date: </label>
															<div class="col-sm-8">
																<div class="input-group mb-md" style="width: 200px;">
																	<span class="input-group-addon">
																		<i class="far fa-calendar-alt"></i>
																	</span>
																	<input type="text" class="form-control valid" id="date_todolist" name="incorporation_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" required="" value="<?=$client->incorporation_date?>" placeholder="DD/MM/YYYY">
																	

																	<!-- <input type="date" id="date_todolist" name="incorporation_date" class="form-control" value="<?=$client->incorporation_date?>"> -->
																	<!-- <input type="text" id="date_todolist" class="form-control" name="date_incorporation" style="" data-plugin-datepicker data-date-format="dd/mm/yyyy" value="<?=$this->sma->fed($client->date_incorporation)?>" /> -->
																	<?php //data-date-start-date="0d"?>
																</div>
																<div id="form_incorporation_date"></div>
																<!-- <?php echo form_error('incorporation_date','<span class="help-block">*','</span>'); ?> -->
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-4 control-label" for="w2-username">Company Type: </label>
															<div class="col-sm-8">
																<select id="company_type" class="form-control company_type" style="text-align:right; width: 400px;" name="company_type">
												                    <option value="0">Select Company Type</option>
												                </select>
												                <div id="form_company_type"></div>
												               <!-- <?php echo form_error('company_type','<span class="help-block">*','</span>'); ?> -->

												                

																<!-- <select class="input-sm" style="text-align:right;" name="company_type">
																	<option value="EXEMPT PRIVATE COMPANY LIMITED BY SHARES" <?=$client->company_type=="EXEMPT PRIVATE COMPANY LIMITED BY SHARES"?'selected':'';?>>EXEMPT PRIVATE COMPANY LIMITED BY SHARES</option>
																	<option value="PRIVATE COMPANY LIMITED BY SHARES" <?=$client->company_type=="PRIVATE COMPANY LIMITED BY SHARES"?'selected':'';?>>PRIVATE COMPANY LIMITED BY SHARES</option>
																</select> -->
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-4 control-label" for="w2-username">Status: </label>
															<div class="col-sm-8">
																
																<select id="status" class="form-control status" style="text-align:right;" name="status">
												                    <option value="0">Select Status</option>
												                </select>
												                <div id="form_status"></div>
																	
																<!-- <select class="input-sm" style="text-align:right;" name="status">
																	<option value="live" <?=$client->status=="live"?'selected':'';?>>Live</option>
																	<option value="struck-off" <?=$client->status=="struck-off"?'selected':'';?>>Struck-Off</option>
																	<option value="liquidated" <?=$client->status=="liquidated"?'selected':'';?>>Liquidated</option>
																</select> -->
															</div>
														</div>

														<!-- <div class="form-group">
															<label class="col-xs-4 control-label" for="w2-username">Use Our Registered Address</label>
															<div class="col-xs-8">
																<input type="checkbox" class="" id="" name="use_registered_address" value="<?=$client->use_registered_address?>" >
															</div>
														</div> -->
														<!-- <div style="background-color: #FFBF00;padding-left: 10px;">
															<p>Principal Activities</p>
														</div> -->
														<span style="font-size: 2.4rem;padding: 0; margin: 7px 0px 4px 0;">Principal Activities</span>
														<div class="form-group" style="margin-top: 20px">
															<label class="col-sm-4 control-label" for="w2-username">Activity 1: </label>
															<div class="col-sm-8">
																<input type="text" style="text-transform:uppercase" class="form-control" id="activity1" name="activity1" value="<?=$client->activity1?>" >
																<div id="form_activity1"></div>
																<!-- <?php echo form_error('activity1','<span class="help-block">*','</span>'); ?> -->
															</div>
															
														</div>
														<!-- <div class="form-group">
															<label class="col-sm-4 control-label" for="w2-username">Description 1</label>
															<div class="col-sm-8">
																<input type="text" class="form-control input-sm" id="" name="description1" value="<?=$client->description1?>" >
															</div>
														</div> -->
														<div class="form-group">
															<label class="col-sm-4 control-label" for="w2-username">Activity 2: </label>
															<div class="col-sm-8">
																<input type="text" style="text-transform:uppercase" class="form-control" id="activity2" name="activity2" value="<?=$client->activity2?>" >
																<div id="form_activity2"></div>
																<!-- <?php echo form_error('activity2','<span class="help-block">*','</span>'); ?> -->
															</div>
															
														</div>
														<!-- <div class="form-group">
															<label class="col-sm-4 control-label" for="w2-username">Description 2</label>
															<div class="col-sm-8">
																<input type="text" class="form-control input-sm" id="" name="description2" value="<?=$client->description2?>" >
															</div>
														</div> -->
														<!-- <div style="background-color: #FFBF00;padding-left: 10px;">
															<p>Registered Office Address</p>
														</div> -->
														<span style="font-size: 2.4rem;padding: 0; margin: 7px 0px 4px 0;">Registered Office Address</span>
														<div class="form-group" style="margin-top: 20px">
															<label class="col-xs-4 control-label" for="w2-username">Use Our Registered Address: </label>
															<div class="col-xs-8">
																<div class="col-xs-1">
																	<input type="checkbox" class="" id="" name="use_registered_address" <?=$client->registered_address?'checked':'';?> onclick="fillRegisteredAddressInput(this);">
																</div>
																<div class="col-xs-6 service_reg_off_area" style="display: none;">
																	<select id="service_reg_off" class="form-control service_reg_off" style="text-align:right;" name="service_reg_off">
													                    <option value="0">Select Service Name</option>
													                </select>
																</div>
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-4 control-label" for="w2-username">Registered Office Address: </label>



															


															<div class="col-sm-8">
																<div style="width: 100%;">
																	<div style="width: 15%;float:left;margin-right: 20px;">
																		<label>Postal Code :</label>
																	</div>
																	<div style="width: 65%;float:left;margin-bottom:5px;">
																		<div class="" style="width: 20%;" >
																			<input type="text" style="text-transform:uppercase" class="form-control" id="postal_code" name="postal_code" value="<?=$client->postal_code?>" maxlength="6">
																		</div>
																		<div id="form_postal_code"></div>
																		<!-- <?php echo form_error('postal_code','<span class="help-block">*','</span>'); ?> -->
													
																	</div>
																</div>
																
																<!-- <label style="width: 15%;float:left;margin-right: 20px;">Postal Code :</label>
																<input style="width: 15%; float: left; margin-right: 10px;" type="text" class="form-control input-sm" id="postal_code" name="postal_code"  value="<?=$client->postal_code?>">
																<?php echo form_error('postal_code','<span class="help-block">*','</span>'); ?> -->
																<!-- <div class="input-group" style="width: 40%;" >
																<input type="text" class="form-control input-sm" id="" name="city"    value="<?=$client->city?>">
																</div> -->
															</div>
															
														</div>
														<div class="form-group">
															<label class="col-sm-4 control-label" for="w2-username"></label>
															<div class="col-sm-8">
																<div style="width: 100%;">
																	<div style="width: 15%;float:left;margin-right: 20px;">
																		<label>Street Name :</label>
																	</div>
																	<div style="width: 71%;float:left;margin-bottom:5px;">
																		<div class="" style="width: 100%;" >
																			<input type="text" style="text-transform:uppercase" class="form-control" id="street_name" name="street_name" value="<?=$client->street_name?>">
																		</div>
																		<div id="form_street_name"></div>
													
																	</div>
																</div>

																<!-- <label style="width: 15%;float:left;margin-right: 20px;">Street Name :</label>
																<input style="width: 71%;" type="text" class="form-control input-sm" id="street_name" name="street_name" value="<?=$client->street_name?>">
																<div id="form_street_name"></div> -->
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-4 control-label" for="w2-username"></label>
															<div class="col-sm-8">


																<!-- <label style="width: 18%;float:left;margin-right: 0px;">Building Name :</label>
																<input style="width: 71%;" type="text" class="form-control input-sm" id="building_name" name="building_name" value="<?=$client->building_name?>"> -->

																<div style="width: 100%;">
																	<div style="width: 15%;float:left;margin-right: 20px;">
																		<label>Building Name :</label>
																	</div>
																	<div style="width: 71%;float:left;margin-bottom:5px;">
																		<div class="" style="width: 100%;" >
																			<input type="text" style="text-transform:uppercase" class="form-control" id="building_name" name="building_name" value="<?=$client->building_name?>">
																		</div>
																		<div id="form_street_name"></div>
													
																	</div>
																</div>
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-4 control-label" for="w2-username"></label>
															<div class="col-sm-8">
																<label style="width: 15%;float:left;margin-right: 20px;">Unit No :</label>
																<input style="width: 8%; float: left; margin-right: 10px; text-transform:uppercase;" type="text" class="form-control" id="unit_no1" name="unit_no1" value="<?=$client->unit_no1?>" maxlength="3">
																<label style="float: left; margin-right: 10px;" >-</label>
																<input style="width: 14%; text-transform:uppercase;" type="text" class="form-control" id="unit_no2" name="unit_no2" value="<?=$client->unit_no2?>" maxlength="10">
															</div>
														</div>
														<div class="form-group hidden">
															<label class="col-sm-4 control-label" for="w2-username">Listed Company: </label>
															<div class="col-sm-8">
																<input type="checkbox" class="" id="listedcompany" name="listedcompany" <?=$client->listed_company?'checked':'';?> />
															</div>
														</div>

														<!-- <div class="form-group">
															<label class="col-sm-4 control-label" for="w2-username">Date of Last AGM:</label>
															<div class="col-sm-8">
																<div class="input-group mb-md" style="width: 100px;">
																	<span class="input-group-addon">
																		<i class="far fa-calendar-alt"></i>
																	</span>
																	<input type="date" id="date_todolist" name="last_AGM" class="form-control" value="<?=$client->last_AGM?>" >
																	
																</div>
															</div>
														</div>

														<div class="form-group">
															<label class="col-sm-4 control-label" for="w2-username">Date of Last AR:</label>
															<div class="col-sm-8">
																<div class="input-group mb-md" style="width: 100px;">
																	<span class="input-group-addon">
																		<i class="far fa-calendar-alt"></i>
																	</span>
																	<input type="date" id="date_todolist" name="last_AR" class="form-control" value="<?=$client->last_AR?>">
																	
																</div>
															</div>
														</div>

														<div class="form-group">
															<label class="col-sm-4 control-label" for="w2-username">Date of A/C Laid at Last AGM:</label>
															<div class="col-sm-8">
																<div class="input-group mb-md" style="width: 100px;">
																	<span class="input-group-addon">
																		<i class="far fa-calendar-alt"></i>
																	</span>
																	<input type="date" id="date_todolist" name="account_laid_last_AGM" class="form-control" value="<?=$client->account_laid_last_AGM?>">
																	
																</div>
															</div>
														</div>

														<div class="form-group">
															<label class="col-sm-4 control-label" for="w2-username">Date of Next AGM:</label>
															<div class="col-sm-8">
																<div class="input-group mb-md" style="width: 100px;">
																	<span class="input-group-addon">
																		<i class="far fa-calendar-alt"></i>
																	</span>
																	<input type="date" id="date_todolist" name="next_AGM" class="form-control" value="<?=$client->next_AGM?>">
																	
																</div>
															</div>
														</div>

														<div class="form-group">
															<label class="col-sm-4 control-label" for="w2-username">Date of Next AR:</label>
															<div class="col-sm-8">
																<div class="input-group mb-md" style="width: 100px;">
																	<span class="input-group-addon">
																		<i class="far fa-calendar-alt"></i>
																	</span>
																	<input type="date" id="date_todolist" name="next_AR" class="form-control" value="<?=$client->next_AR?>">
																	
																</div>
															</div>
														</div>

														<div class="form-group">
															<label class="col-sm-4 control-label" for="w2-username">Date of A/C Laid at Next AGM:</label>
															<div class="col-sm-8">
																<div class="input-group mb-md" style="width: 100px;">
																	<span class="input-group-addon">
																		<i class="far fa-calendar-alt"></i>
																	</span>
																	<input type="date" id="date_todolist" name="account_laid_next_AGM" class="form-control" value="<?=$client->account_laid_next_AGM?>">
																	
																</div>
															</div>
														</div> -->
														
														<!-- <div class="form-group">
															<label class="col-sm-4 control-label" for="w2-username">Contact Person</label>
															<div class="col-sm-8">
																<input type="text" class="form-control input-sm" id="" name="cp" value="<?=$client->cp?>" >
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-4 control-label" for="w2-username">Phone</label>
															<div class="col-sm-8">
																<div class="input-group">
																	<input id="phone" maxlength="20" placeholder="(123) 123-1234" name="phone" class="form-control" value="<?=$client->phone?>"  >
																</div>
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-4 control-label" for="w2-username">E-Mail</label>
															<div class="col-sm-8">
																	<input type="email" name="email" class="form-control" placeholder="eg.: email@email.com" value="<?=$client->email?>" />
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-4 control-label" for="w2-username">Fax</label>
															<div class="col-sm-8">
																<div class="input-group">
																<input type="text" class="form-control" id="" name="fax" value="<?=$client->fax?>" >
																</div>
															</div>
														</div> -->
														<!-- <div class="form-group hidden">
															<label class="col-sm-4 control-label" for="w2-username">Chairman</label>
															<div class="col-sm-8">
																<input type="text" class="form-control input-sm" id="" name="chairman" value="<?=$client->chairman?>"  >
															</div>
														</div> -->
														<!-- <div class="form-group">
															<label class="col-sm-4 control-label" for="w2-username">Listed Company</label>
															<div class="col-sm-8">
																<input type="checkbox" class="" id="" name="listedcompany" <?=$client->listedcompany?'checked':'';?> />
															</div>
														</div> -->
														
													
														<?= form_close(); ?>
													</div>
													<?php
													}
												?>


													<?php if ($officer_module != 'none') { ?>
													<div id="w2-officer" class="tab-pane">
														<!-- <?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
															echo form_open_multipart("masterclient/add_officer", $attrib);
															
														?> -->
															<!-- <div class="hidden"><input type="text" class="form-control" name="unique_code" value="<?=$unique_code?>"/></div> -->
															<div style="margin-bottom: 23px">
																<form id="filter_position">
														<span style="font-size: 2.4rem;padding: 0; margin: 7px 0 4px 0;">Officers</span>

															<div class="hidden"><input type="text" class="form-control" name="company_code" value="<?=$company_code?>"/></div>
															<select class="form-control" id="search_position" name="search_position" style="float:right; width: 100px;">
																<option value="all" <?=$_POST['search_position']=='all'?'selected':'';?>>All</option>
																<option value="director" <?=$_POST['search_position']=='director'?'selected':'';?>>Director</option>
																<option value="ceo" <?=$_POST['search_position']=='ceo'?'selected':'';?>>CEO</option>
																<option value="manager" <?=$_POST['search_position']=='manager'?'selected':'';?>>Manager</option>
																<option value="secretary" <?=$_POST['search_position']=='secretary'?'selected':'';?>>Secretary</option>
																<option value="auditor" <?=$_POST['search_position']=='auditor'?'selected':'';?>>Auditor</option>
																<option value="managing_director" <?=$_POST['search_position']=='managing_director'?'selected':'';?>>Managing Director</option>
																<option value="alternate_director" <?=$_POST['search_position']=='alternate_director'?'selected':'';?>>Alternate Director</option>
															</select>
														
														<div style="font-size: 1.4rem;float:right;padding-top:5px; padding-right: 5px; height: 30px;"><span>Filter: </span></div>
														</form>
													</div>

														<table class="table table-bordered table-striped table-condensed mb-none" id="officer_table">
														<thead>
															<div class="tr">
																<div class="th" id="id_position" style="text-align: center;width:170px">Position</div>
																<div class="th" id="id_header" style="text-align: center;width:170px">ID</div>
																<div class="th" style="text-align: center;width:170px" id="id_name">Name</div>
																<div class="th" id="id_dateofappointment" style="text-align: center;width:170px">Date of Appointment</div>
																<div class="th" id="id_dateofcessation" style="text-align: center;width:170px">Date Of Cessation</div>
																<a href="javascript: void(0);" class="th" rowspan=2 style="color: #D9A200;width:140px; outline: none !important;text-decoration: none;"><span id="officers_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Officer" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add Officer</span></a>
															</div>
															
														</thead>
														

														<div class="tbody" id="body_officer">
															

														</div>
														
														</table>
														
														<!-- <?= form_close(); ?> -->
														<!-- <table class="table table-bordered table-striped table-condensed mb-none" >
															<thead>
																<tr>
																	<th>No</th>
																	<th>Officer and Position</th>
																	<th>
																		<a href="#modal_officer" id="adds_officer" class="fa fa-plus modal-sizes amber" data-toggle="tooltip" data-trigger="hover" style="color:black;margin:0px;float:left;" data-original-title="Add Officer" ></a>
																	</th>
																</tr>
															</thead>
															<tbody id="body_officer">
															<?php
																$position[] = 'Director';
																$position[] = 'CEO';
																$position[] = 'Manager';
																$position[] = 'Secretary';
																$position[] = 'Auditor';
																$position[] = 'Managing Director';
																$position[] = 'Alternate Director';
																$oppicer = [];
																$oppicer[0] = '---Please Select---';
																foreach($officer as $of){
																	$oppicer[$of->id] = $of->name;
																}
																$i = 0;
																foreach($officer as $of)
																{
																	if($of->name != "")
																	{
															?>
															<tr>
																<td>
																<label class="ads"></label></td><td style="padding-top:15px;"><h6><?=$of->name?></h6>
																<table class="table table-bordered table-striped table-condensed mb-none" >
																	<thead>
																		<tr>
																			<th>Position</th>
																			<th style="width:2%;">Date of Appointment</th>
																			<th style="width:2%;">Date of Cesasion</th>
																		</tr>
																	</thead>
																	<tbody id="body_content">
																		<?php
																			foreach($position as $p)
																			{
																				
																				echo '<tr><td><label><input type="checkbox" ';
																				if ($of->position == $p)
																				{
																					echo ' checked=checked';
																				}
																				echo ' name="'.$p.'" value="'.$p.'"';
																				if ($p == 'Alternate Director')
																				{
																					echo ' class="alternatedir" data-id="'.$i.'">&nbsp;&nbsp;'.$p.'</label>';
																					if ($oppicer != '')
																					{
																						echo '<div id="alternatedir'.$i.'" style="display:none">
																						<select class="form-control input-sm">';
																						foreach($oppicer as $key=>$value)
																						{
																						echo '<option value="'.$key.'">'.$value.'</option>';
																						}
																						echo '</select>
																						</div>';
																					}
																				} else {
																					
																					echo '>'.$p.'</label>';
																				}
																				echo '</td>
																				<td><input type="text" data-plugin-datepicker data-date-format="dd/mm/yyyy" data-date-start-date="0d"';
																				if ($of->position == $p)
																				{
																					echo 'value="'.$of->date_of_appointment.'"';
																				}
																				echo '/></td>
																				<td><input type="text" data-plugin-datepicker data-date-format="dd/mm/yyyy" data-date-start-date="0d" ';
																				if ($of->position == $p)
																				{
																					echo 'value="'.$of->date_of_cessation.'"';
																				}
																				echo '/></td> </tr>';
																			}
																		?>
																		
																		
																	</tbody>
																</table>
																</td>
																<td>
																<a ><i class="fa fa-timer"></i></a>
																<a  class="delete_officer" data-id="<?=$of->id?>"><i class="fa fa-trash" style="font-size:16px;"></i></a>
																</td>
																</tr>
															<?php
																	}
																}?>
															</tbody>
														</table> -->
														
													</div>
													<?php
														}
													?>
													<?php if ($controller_module != 'none') { ?>
													<div id="w2-controller" class="tab-pane">
														<!-- <?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
															echo form_open_multipart("masterclient/add_officer", $attrib);
															
														?> -->
															<!-- <div class="hidden"><input type="text" class="form-control" name="unique_code" value="<?=$unique_code?>"/></div> -->
															<!-- <div style="margin-bottom: 15px">
																<form id="filter_position">
														<span style="font-size: 2.4rem;padding: 0; margin: 7px 0 4px 0;">Officers</span>

															<div class="hidden"><input type="text" class="form-control" name="company_code" value="<?=$company_code?>"/></div>
															<select class="form-control" id="search_position" name="search_position" style="float:right; width: 100px;">
																<option value="all" <?=$_POST['search_position']=='all'?'selected':'';?>>All</option>
																<option value="director" <?=$_POST['search_position']=='director'?'selected':'';?>>Director</option>
																<option value="ceo" <?=$_POST['search_position']=='ceo'?'selected':'';?>>CEO</option>
																<option value="manager" <?=$_POST['search_position']=='manager'?'selected':'';?>>Manager</option>
																<option value="secretary" <?=$_POST['search_position']=='secretary'?'selected':'';?>>Secretary</option>
																<option value="auditor" <?=$_POST['search_position']=='auditor'?'selected':'';?>>Auditor</option>
																<option value="managing_director" <?=$_POST['search_position']=='managing_director'?'selected':'';?>>Managing Director</option>
																<option value="alternate_director" <?=$_POST['search_position']=='alternate_director'?'selected':'';?>>Alternate Director</option>
															</select>
														
														<span style="font-size: 1.4rem;float:right;padding-right: 5px;">Filter: </span>
														</form>
													</div> -->
														<h3>Controller</h3>
														<table class="table table-bordered table-striped table-condensed mb-none" id="controller_table">
														<!-- <thead>
															<div class="tr">
																<div class="th" id="id_position" style="text-align: center;width:170px">Position</div>
																<div class="th" id="id_header" style="text-align: center;width:170px">ID</div>
																<div class="th" style="text-align: center;width:170px" id="id_name">Name</div>
																<div class="th" id="id_dateofappointment" style="text-align: center;width:170px">Date of Appointment</div>
																<div class="th" id="id_dateofcessation" style="text-align: center;width:170px">Date Of Cessation</div>
																<a href="javascript: void(0);" class="th" rowspan =2 style="color: #D9A200;width:170px; outline: none !important;text-decoration: none;"><span id="officers_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Officer" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add Officer</span></a>
															</div>
															
														</thead> -->

														<thead>
															<div class="tr">
																
																
																<div class="th" id="id_controller_header" style="text-align: center;width:140px;">ID/UEN</div>
																<div class="th" id="id_date_of_birth" style="text-align: center;width:140px;">Date of Birth</div>
																<div class="th rowspan" style="border-bottom:none;"></div>
																<div class="th rowspan" id="id_date_of_registration" style="text-align: center;border-bottom:none;">Date of registration</div>
																
																<div class="th rowspan" id="id_confirmation_received_date" style="text-align: center;border-bottom:none;">Confirmation received date</div>
																
																<div class="th rowspan" id="id_date_of_cessation" style="text-align: center;border-bottom:none;">Date of cessation</div>
																<a href="javascript: void(0);" class="th rowspan" style="color: #D9A200;width:170px;border-bottom:none; outline: none !important;text-decoration: none;"><span id="controller_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Click to Add Controller" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add Controller</span></a>
															</div>
															<div class="tr">
																
																<div class="th" id="id_controller_name" style="text-align: center;width:140px;">Name</div>
																<div class="th" id="id_nationality" style="text-align: center;width:140px;">Nationality</div>
																<div class="th empty" id="id_address" style="text-align: center;width:170px;">Address</div>
																<div class="th rowspan" id="id_date_of_notice" style="text-align: center;">Date of notice</div>
																<div class="th rowspan" id="id_date_of_entry" style="text-align: center;">Date of entry</div>
																
																<!-- <div class="th" style="text-align: center;width:200px;border-top:none;"></div> -->
																<div class="th" style="text-align: center;width:200px;border-top:none;"></div>
																<div class="th empty"></div>
															</div class="tr">
														</thead>
														

														<div class="tbody" id="body_controller">
															

														</div>
														
														</table>
													</div>
													<?php
														}
													?>
													<?php if ($member_module != 'none') { ?>
													<!-- members -->
													<div id="w2-capital" class="tab-pane">
														<!-- <?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
															echo form_open_multipart("masterclient/save_capital", $attrib);
															
															// print_r($sales);
															$cr[""] = [];
															foreach ($currency as $cs) {
																$cr[$cs->id] = $cs->currency;
															}
															$bl[""] = [];
															foreach ($sharetype as $share) {
																$bl[$share->id] = $share->sharetype;
															}
														?> -->
															<!-- <div class="hidden"><input type="text" class="form-control" name="unique_code" value="<?=$unique_code?>"/></div> -->
														<div id="guarantee" style="display: none">
															
														<h3>Members</h3>
														<table class="table table-bordered table-striped table-condensed mb-none" id="guarantee_table">
														<thead>
															<div class="tr">
																
																<div class="th" id="id_guarantee_header" style="text-align: center;width:200px">ID</div>
																<div class="th" style="text-align: center;width:200px" id="id_guarantee_name">Name</div>
																<div class="th" style="text-align: center;width:200px" id="id_currency">Currency</div>
																<div class="th" id="id_guarantee" style="text-align: center;width:200px">Limit Of Guarantee</div>
																<div class="th" id="id_guarantee_start_date" style="text-align: center;width:200px">Transaction Date</div>
																<!-- <div class="th" id="id_position" style="text-align: center;width:170px">End</div> -->
																<a href="javascript: void(0);" class="th" rowspan =2 style="color: #D9A200;width:170px; outline: none !important;text-decoration: none;"><span id="guarantee_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Guarantee" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add Guarantee</span></a>
															</div>
															
														</thead>
														

														<div class="tbody" id="body_guarantee">
															

														</div>
														
														</table>
														</div>


														<div id="non-guarantee" style="display: none">
														<h3>Issued & Paid-Up Share Capital</h3>
															<table class="table table-bordered table-striped table-condensed mb-none" >
																<thead>
																	<div class="tr">
																		<div class="th" style="text-align: center;width:160px;">Class</div>
																		<div class="th" style="text-align: center;width:160px;">Number of Shares Issued</div>
																		<div class="th" style="text-align: center;width:160px;">Currency</div>
																		<div class="th" style="text-align: center;width:160px;">Amount of Shares Issued</div>
																		<div class="th" style="text-align: center;width:160px;">Amount of Shares Paid Up</div>

																		<a href="javascript: void(0);" class="th" rowspan =2 style="color: #D9A200;width:170px; outline: none !important;text-decoration: none;"><span id="share_capital_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Issued & Paid-Up Share Capital" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add class of shares</span></a>
																	</div>
																	
																</thead>
																

																<div class="tbody" id="body_share_capital">
																	

																</div>
															
															</table>
															<!-- <table class="table table-bordered table-striped table-condensed mb-none" >
																<thead>
																	<tr>
																		<th>No</th>
																		<th>Amount</th>
																		<th>Number of Shares</th>
																		<th>Currency</th>
																		<th>Share Type</th>
																		<th><a id="add_issued_share_capital"><i class="fa fa-plus-circle"></i></a></th>
																	</tr>
																</thead>
																<tbody id="body_issued_share_capital">
																		<?php
																			$i = 1;
																			foreach ($allotment as $iss)
																			{
																		?>
																	<tr>
																		<td><?=$i?></td>
																		<td><input type="text" class="form-control number text-right" name="issued_amount_member[]" disabled value="<?=number_format($iss->allotment_share_amount,2)?>"/></td>
																		<td><input type="text" class="form-control number text-right" name="no_of_share_member[]"  disabled value="<?=number_format($iss->allotment_share,2)?>"/></td>
																		<td><?php
																			echo form_dropdown('issued_currency_member[]', $cr, $iss->currency, 'id="currency"  class="form-control" style="width:100%;"  disabled');
																		
																			?>
																		</td>
																		<td>
																		<?php
																			// echo form_dropdown('sharetype_member[]', $bl, '', 'id="slsales"  class="form-control input-sm  input-sm select" style="width:100%;" ');
																			echo form_dropdown('issued_sharetype_member[]', $bl, $iss->sharetype_allotment, 'id="slsales"  class="form-control" style="width:100%;" disabled');
																		?>
																			
																		</td>
																		<td><a ><i class="fa fa-times hapus_allotment" data-id="<?=$iss->id?>"></i></a></td>
																	</tr>
																			<?php 
																				$i++;
																			} 
																		for($j=$i;$j<0;$j++)
																		{
																			?>
																	<tr>
																		<td><?=$j?></td>
																		<td><input type="text" class="form-control number text-right" name="issued_amount_member[]"  disabled value=""/></td>
																		<td><input type="text" class="form-control number text-right" name="no_of_share_member[]" disabled value=""/></td>
																		<td><?php
																			echo form_dropdown('issued_currency_member[]', $cr, '', 'id="currency"  class="form-control" style="width:100%;" disabled');
																		
																			?>
																		</td>
																		<td>
																		<?php
																			// echo form_dropdown('sharetype_member[]', $bl, '', 'id="slsales"  class="form-control input-sm  input-sm select" style="width:100%;" ');
																			echo form_dropdown('issued_sharetype_member[]', $bl, '', 'id="slsales"  class="form-control" style="width:100%;" disabled');
																		?>
																			
																		</td>
																		<td><a ><i class="fa fa-times hapus_baris"></i></a></td>
																	</tr>
																	<?php
																			}
																	?>
																</tbody>
															</table> -->
															
															<!-- <table class="table table-bordered table-striped table-condensed mb-none" >
																<thead>
																	<tr>
																		<th>No</th>
																		<th>Amount</th>
																		<th>Number of Shares</th>
																		<th>Currency</th>
																		<th>Share Type</th>
																		<th><a  id="paid_issued_share_capital"><i class="fa fa-plus-circle"></i></a></th>
																	</tr>
																</thead>
																<tbody id="body_paid_issued_share_capital">
																		<?php
																			$i = 1;
																			foreach ($paid_share as $ps)
																			{
																		?>
																	<tr>
																		<td><?=$i?></td>
																		<td><input type="text" class="form-control number text-right" value="<?=number_format($ps->paid_amount_member,2)?>" disabled  name="paid_amount_member[]"/></td>
																		<td><input type="text" class="form-control number text-right" disabled  value="<?=number_format($ps->paid_no_of_share_member,2)?>" name="paid_no_of_share_member[]"/></td>
																		<td><?php echo form_dropdown('paid_currency_member[]', $cr, $ps->paid_currency_member, 'id="currency"  class="form-control" style="width:100%;" disabled ');
																		?>
																		</td>
																		<td>
																			<?php echo form_dropdown('paid_sharetype_member[]', $bl, $ps->paid_sharetype_member, 'id="slsales"  class="form-control" style="width:100%;" disabled '); ?>
																		</td>
																		<td><a ><i class="fa fa-times hapus_baris"></i></a></td>
																	</tr>
																		<?php 
																			$i++;
																		} 
																		for($j=$i;$j<4;$j++)
																		{
																		?>
																		<tr>
																			<td><?=$j?></td>
																			<td><input type="text" class="form-control number text-right" value="" disabled  name="paid_amount_member[]"/></td>
																			<td><input type="text"  disabled class="form-control number text-right" value="" name="paid_no_of_share_member[]"/></td>
																			<td><?php echo form_dropdown('paid_currency_member[]', $cr, '', 'id="currency"  class="form-control" style="width:100%;" disabled ');
																			?>
																			</td>
																			<td>
																				<?php echo form_dropdown('paid_sharetype_member[]', $bl, '', 'id="slsales"  class="form-control" style="width:100%;" disabled '); ?>
																			</td>
																			<td><a ><i class="fa fa-times hapus_baris"></i></a></td>
																		</tr>
																	<?php
																			}
																	?>
																</tbody>
															</table> -->
															<h3 style="margin-top: 15px;">Members</h3>
															<div style="padding: 5px 0px;">
																<a href="<?= base_url();?>masterclient/view_allotment/<?=$company_code?>" class="btn btn-primary" target="_blank">Allotment</a>
																<a href="<?= base_url();?>masterclient/view_buyback/<?=$company_code?>" class="btn btn-primary" target="_blank">Buyback</a>
																<a href="<?= base_url();?>masterclient/view_transfer/<?=$company_code?>" class="btn btn-primary" target="_blank">Transfer</a>

																<a href="javascript: void(0);" class="btn btn-primary" id="refresh" class="refresh" style="float: right;">Refresh</a>
															</div>
															<!-- <h3 style="margin-top: 10px;">B. Paid-Up Share Capital</h3> -->
															<table class="table table-bordered table-striped table-condensed mb-none" >
																<thead>
																	<tr>
																		<th rowspan=2 style="text-align: center">No</th>
																		<th style="text-align: center; width: 150px;">ID</th>
																		<th style="text-align: center">Class</th>
																		<th style="text-align: center">Number of Shares Issued</th>
																		<th style="text-align: center">Number of Shares Paid Up</th>
																		<!-- <th>Certificate</th> -->
																		<!--th><a id="member_issued_share_capital"><i class="fa fa-plus-circle"></i></a></th-->
																	</tr>
																	<tr>
																		<th style="text-align: center; width: 150px;">Name</th>
																		<th style="text-align: center">Currency</th>
																		<th style="text-align: center">Amount of Shares Issued</th>
																		<th style="text-align: center">Amount of Shares Paid Up</th>
																		<!-- <th>SettlePayment</th> -->
																		<!-- <th><a  id="add_member_capital"><i class="fa fa-plus-circle"></i></a></th> -->
																	</tr>
																</thead>
																<tbody id="body_members_capital">
																	<?php
																			$i = 1;
																			foreach ($member as $mb)
																			{
																		?>
																	<tr class="member_tr_1">
																		<td rowspan=2><?=$i?></td>
																		<td>
																			<?php
																				if($mb->identification_no != null)
																				{
																					echo $mb->identification_no;
																				}
																				elseif($mb->register_no != null)
																				{
																					echo $mb->register_no;
																				}
																				elseif($mb->registration_no != null)
																				{
																					echo $mb->registration_no;
																				}

																			?>
																			
																		</td>
																		<td>
																			<?=$mb->sharetype?> 
																			<?php
																				if($mb->sharetype == "Others")
																				{
																					echo ('('.$mb->other_class.')');
																				}
																				
																			?>
																		</td>
																		<td style="text-align: right"><?=number_format($mb->number_of_share)?></td>
																		<td style="text-align: right"><?=number_format($mb->no_of_share_paid)?></td>
																		
																	</tr>
																	<tr class="member_tr_2">
																		<td>
																			<?php
																				if($mb->name != null)
																				{
																					echo $mb->name;
																				}
																				elseif($mb->company_name != null)
																				{
																					echo $mb->company_name;
																				}
																				elseif($mb->client_company_name != null)
																				{
																					echo $mb->client_company_name;
																				}

																			?>
																		</td>
																		<td><?=$mb->currency?></td>
																		<td style="text-align: right"><?=number_format($mb->amount_share, 2)?></td>
																		<td style="text-align: right"><?=number_format($mb->amount_paid, 2)?></td>
																		
																	</tr>
																	<?php 
																			$i++;
																		} 
																	?>
																	
																<!-- 
																		<?php
																			$i = 1;
																			foreach ($allotment_member as $ms)
																			{
																		?>
																	<tr>
																		<td rowspan=2><?=$i?></td>
																		<td><input type="text" class="form-control input-xs" disabled  value="<?=$ms->nama?>"/></td>
																		<td>
																			<?php echo form_dropdown('sharetype_member[]', $bl, $ms->sharetype_member, 'id="slsales"  class="form-control" style="width:100%;" disabled '); ?>
																		</td>
																		<td><input type="text" name="shares_member_capital[]" class="form-control number text-right" disabled  value="<?=$ms->shares_member_capital?>"/></td>
																		<td><input type="text" name="no_share_paid_member_capital[]" disabled  class="form-control number text-right" value="<?=$ms->no_share_paid_member_capital?>"/></td>
																		<td><?php
																			if ($ms->certificate != ''){
																		?>
																		<a href="./uploads/<?=$ms->certificate?>"><?=$ms->certificate?></a>
																		<?php
																			} else {
																		?>
																		<input type="file" name="upload_certificate<?=$i?>" class="form-control number text-right" value=""/>
																		<?php
																			}
																		?></td>
																		<td rowspan=2><a ><i class="fa fa-times hapus_duabaris"></i></a></td>
																	</tr>
																	<tr>
																		<td><input type="text" name="gid_member_capital[]"  class="form-control" value=""/></td>
																		<td><?php echo form_dropdown('currency_member_capital[]', $cr, $ms->currency_member_capital, 'id="currency"  class="form-control" style="width:100%;"');
																		?>
																		</td>
																		<td><input type="text" name="amount_share_member_capital[]" class="form-control number text-right" value="<?=$ms->amount_share_member_capital?>"/></td>
																		<td><input type="text" name="amount_share_paid_member_capital[]" class="form-control number text-right" value="<?=$ms->amount_share_paid_member_capital?>"/></td>
																		<td><a>SettlePayment</a></td>
																	</tr>
																		<?php 
																			$i++;
																		} 
																		for($j=$i;$j<4;$j++)
																		{
																		?>
																	<tr>
																		<td rowspan=2><?=$j?></td>
																		<td><input type="text"  name="nama_member_capital[]" class="form-control input-xs" value=""/></td>
																		<td>
																			<?php echo form_dropdown('sharetype_member[]', $bl, '', 'id="slsales"  class="form-control" style="width:100%;"'); ?>
																		</td>
																		<td><input type="text" name="shares_member_capital[]" class="form-control number text-right" value=""/></td>
																		<td><input type="text" name="no_share_paid_member_capital[]" class="form-control number text-right" value=""/></td>
																		<td><input type="file" name="upload_certificate[]" class="form-control number text-right" value=""/></td>
																		<td>
																			<a href="#"><i class="fa fa-pencil"></i></a>
																			<a href="#modal_transfer" class="modal-sizes"><i class="fa fa-share-alt"></i></a>
																		</td
																		<td rowspan=2><a ><i class="fa fa-times hapus_baris"></i></a></td>
																	</tr>
																	<tr>
																		<td><input type="text" name="gid_member_capital[]"  class="form-control" value=""/></td>
																		<td><?php echo form_dropdown('currency_member_capital[]', $cr, '', 'id="currency"  class="form-control" style="width:100%;"');
																		?>
																		</td>
																		<td><input type="text" name="amount_share_member_capital[]" class="form-control number text-right" value=""/></td>
																		<td><input type="text" name="amount_share_paid_member_capital[]"  class="form-control number text-right" value=""/></td>
																		<td><a>SettlePayment</a></td>
																	</tr>
																	<?php
																			}
																		// echo "<script>";
																		// echo "	$byk_member_capital =".$i.";";
																		// echo "</script>";
																	?> -->
																	
																</tbody>
															</table>
															<h3 style="margin-top: 15px;">Certificate</h3>
															<table class="table table-bordered table-striped table-condensed mb-none" >
																<thead>
																	<tr>
																		<th style="text-align: center">No</th>
																		<th style="text-align: center; width: 150px;">ID</th>
																		<th style="text-align: center; width: 200px;">Name</th>
																		<th style="text-align: center">Class (Currency)</th>
																		<th style="text-align: center">Certificate Number</th> 
																		<th style="text-align: center; width: 200px;">Number of Shares in Certificate</th>

																	</tr>
																</thead>
																<tbody id="body_members_certificate">
																	<?php
																			$i = 1;
																			foreach ($member_certificate as $mc)
																			{
																		?>
																	<tr class="member_certificate_tr_1">
																		<td><?=$i?></td>
																		<td>
																			<?php
																				if($mc->identification_no != null)
																				{
																					echo $mc->identification_no;
																				}
																				elseif($mc->register_no != null)
																				{
																					echo $mc->register_no;
																				}
																				elseif($mc->registration_no != null)
																				{
																					echo $mc->registration_no;
																				}
																			?>
																			
																		</td>
																		<td>
																			<?php
																				if($mc->name != null)
																				{
																					echo $mc->name;
																				}
																				elseif($mc->company_name != null)
																				{
																					echo $mc->company_name;
																				}
																				elseif($mc->client_company_name != null)
																				{
																					echo $mc->client_company_name;
																				}

																			?>
																		</td>
																		<td>
																			<?=$mc->sharetype?> 
																			<?php
																				if($mc->sharetype == "Others")
																				{
																					echo ('('.$mc->other_class.')');
																				}
																				
																			?>
																			( <?=$mc->currency?> )
																		</td>
																		<td>
																			<?php
																				if($mc->certificate_no != null)
																				{
																					echo $mc->certificate_no;
																				}
																				elseif($mc->new_certificate_no != null)
																				{
																					echo $mc->new_certificate_no;
																				}

																			?>
																		</td>
																		<td style="text-align: right"><?=number_format($mc->number_of_share)?></td>
																		
																	</tr>

																	<?php 
																			$i++;
																		} 
																	?>
																</tbody>
															</table>
															</div>
														<!-- <?= form_close(); ?> -->
													</div>
													<?php
														}
													?>
													<?php if ($charges_module != 'none') { ?>
													<!-- charges -->
													<div id="w2-charges" class="tab-pane">
														<h3>Charges</h3>
														<table class="table table-bordered table-striped table-condensed mb-none" >
														<thead>
															<div class="tr">
																<div class="th" style="border-bottom:none;width:200px;"></div>
																<div class="th" style="border-bottom:none;width:200px;text-align: center">Nature of </div>
																
																<div class="th" style="text-align: center;width:200px;">Date of Registration</div>
																<div class="th" style="text-align: center;width:200px;">Charge No.</div>
																<!--  -->
																<div class="th" style="text-align: center;width:170px;">Currency</div>
																<div class="th" style="border-bottom:none;text-align: center;width:200px;">Secured by</div>

																<a href="javascript: void(0);" class="th rowspan" style="color: #D9A200;width:170px;border-bottom:none; outline: none !important;text-decoration: none;"><span id="charges_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Charge" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add Charge</span></a>
															</div>
															<div class="tr">
																<div class="th empty" style="text-align: center;width:200px;" valign=middle>Chargee</div>
																<div class="th empty" style="text-align: center;width:200px;" valign=middle>Charge</div>
																<div class="th" style="text-align: center;width:200px;">Date Satisfied</div>
																<div class="th" style="text-align: center;width:200px;">Satisfactory No.</div>
																<div class="th" style="text-align: center;width:200px;">Amount</div>
																<div class="th rowspan" style="border-top:none"></div>
																<div class="th empty"></div>
															</div class="tr">
														</thead>
														<!-- <thead>
															<div class="tr">
																<div class="th" valign=middle>Position</div>
																<div class="th" valign=middle>ID</div>
																<div class="th">Name</div>
																<div class="th">Date of Appointment</div>
																<div class="th">Date Of Cessation</div>
																<div class="th" rowspan =2 style="font-size:25px;"><span id="officers_Add"><i class="fa fa-plus-circle"></i></span></div>
															</div>
															
														</thead> -->
														

														<div class="tbody" id="body_charges">
															

														</div>
														
														</table>
														<!-- <?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
															echo form_open_multipart("masterclient/save_charges", $attrib);
															
														?>
															<div class="hidden"><input type="text" class="form-control" name="unique_code" value="<?=$unique_code?>"/></div>
														<h3>Charges</h3>
														<table class="table table-bordered table-striped table-condensed mb-none" >
														<thead>
															<tr>
																<th rowspan =2 valign=middle>Chargee</th>
																<th rowspan =2 valign=middle>Nature of Charges</th>
																<th>Date Registration</th>
																<th>Chargee No.</th>
																<th rowspan=2>Currency</th>
																<th>Amount</th>
																<th rowspan =2 style="font-size:25px;"><span id="add_charges"><i class="fa fa-plus-circle"></i></span></th>
															</tr>
															<tr>
																<th>Date Satisfied</th>
																<th>Satisfactory No.</th>
																<th>Secured</th>
															</tr>
														</thead>
														<tbody id="body_chargee">
															<?php
																$i = 1;
																foreach ($chargee as $ch)
																{
																	// print_r($service);
															?>
															<tr>
																<td>
																<?php echo form_dropdown('chargee_name[]', $svc, $ch->chargee_name, 'id="currency"  class="form-control" style="width:100%;"');?></td>
																<td><input type="text" name="chargee_nature_of[]" class="form-control" value="<?=$ch->chargee_nature_of?>"/></td>
																<td><input type="text" name="chargee_date_reg[]" id=chargee_date_reg1" class="form-control" data-plugin-datepicker data-date-format="dd/mm/yyyy" value="<?=$ch->chargee_date_reg?>" /></td>
																<td><input type="text" name="chargee_no[]" class="form-control" value="<?=$ch->chargee_no?>"/></td>
																<td>
																	<?php echo form_dropdown('chargee_currency[]', $cr, $ch->chargee_currency, 'id="currency"  class="form-control" style="width:100%;"');
																		?>
																</td>
																<td><input type="text" name="chargee_amount[]" class="form-control number" value="<?=$ch->chargee_amount?>"/></td>
																
															</tr>
															<tr>
																<td></td>
																<td></td>
																<td><input type="text" name="chargee_date_satisfied[]" class="form-control " data-plugin-datepicker data-date-format="dd/mm/yyyy" value="<?=$ch->chargee_date_satisfied?>"/></td>
																<td><input type="text" name="chargee_satisfied_no[]" class="form-control" value="<?=$ch->chargee_satisfied_no?>"/></td>
																<td></td>
																<td></td>
																
															</tr>															
															<?php
																}
															?>

														</tbody>
														</table>
														
														<?= form_close(); ?> -->
													</div>
													<?php
														}
													?>
													<?php
													}
												?>
													<div id="w2-other" class="tab-pane">
														
														<?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
															echo form_open_multipart("masterclient/save_other", $attrib);
														?>
															<div class="hidden"><input type="text" class="form-control" name="unique_code" value="<?=$unique_code?>"/></div>
														<div class="form-group">
															<label class="col-sm-4 control-label" for="w2-email">Type Of Doc</label>
															<div class="col-sm-8">
																<?php echo form_dropdown('typeofdoc', $type_of_doc, '', 'id="currency"  class="form-control" style="width:100%;"');?>
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-4 control-label" for="w2-email">Category</label>
															<div class="col-sm-8">
																<?php echo form_dropdown('doccategory', $doc_category, '', 'id="currency"  class="form-control" style="width:100%;"');
																		?>
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-4 control-label" for="w2-email">File</label>
															<div class="col-sm-8">
																<input type="file" name="upload_file_others" class="form-control number text-right" value=""/>
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-4 control-label" for="w2-email">Remarks</label>
															<div class="col-sm-8">
																<textarea class="form-control" style="height:180px;" name="others_remarks">
																</textarea>
															</div>
														</div>
													
														<?= form_close(); ?>
														<table class="table table-bordered table-striped table-condensed" >
															<tr>
																<th>No</th>
																<th>Type Of Doc</th>
																<th>Doc Category</th>
																<th>Remarks</th>
																<th>Files</th>
															</tr>
															<?php
																$i = 1;
																// print_r($type_of_doc);
																foreach($client_others as $tod)
																{
															?>
															<tr>
																<td><?=$i?></td>
																<td><?=$type_of_doc[$tod->type_of_doc]?></td>
																<td><?=$doc_category[$tod->others_category]?></td>
																<td><?=$tod->others_remarks?></td>
																<td><a href='./uploads/<?=$tod->files?>'><?=$tod->files?></a></td>
															</tr>
															<?php 
																	$i++;
																}
															?>
														</table>
													</div>
													
													<?php if ($setup_module != 'none') { ?>
													<!-- Setup -->
													<div id="w2-setup" class="tab-pane">
														<?php if ((!$Individual && $Individual == true) || (!$Individual && $Individual == null && !$Client)) {?>
														<!-- <?php if ($client->auto_generate == 0) { ?>
															<div style="margin-bottom: 20px;">
																<span class="help-block">
																	* Have you completed importing all information of client that you took over/newly inquired? Auto billing and generate document will be activated subsequent to this and invoice will be automatically generated from your firm to your client for changes that you made for this client from this point onward. <a href="javascript:void(0)" class="btn btn-primary create_auto_generate" id="create_auto_generate"style="padding: 3px 5px; font-size: 10px">YES</a>
																</span>
															</div>
														<?php } ?> -->
														<?php
																	}
																?>
														<?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form', 'id' => 'setup_form');
															echo form_open_multipart("masterclient/add_setup_info", $attrib);
															// print_r($oppicer);
														?>
															<div class="hidden"><input type="text" class="form-control" name="company_code" value="<?=$company_code?>"/></div>
															
															<div class="hidden"><input type="text" class="form-control" name="client_signing_info_id" value="<?=$client_signing_info[0]->id?>"/></div>
															<h3>Signing Information</h3>
															<!-- <?=$client?>
															<?php echo json_encode($client);?>
															<?=$client_signing_info[0]->show_all?> -->
															
														<!-- <div class="form-group">
															<label class="col-xs-3" for="w2-show_all">Show all: </label>
															<div class="col-xs-9">

																<input type="checkbox" class="" id="" name="show_all_members" <?=$client_signing_info[0]->show_all?'checked':'';?> onclick="showAllChairman(this);">
															</div>
														</div> -->

														<div class="form_chairman form-group" style="margin-top: 10px;">
															<label class="col-sm-3" for="w2-chairman">Chairman:</label>
															<div class="col-sm-9">
																<div class="chairman_group" style="float: left;margin-right: 10px">
																	<select id="chairman" class="form-control chairman" style="text-align:right; width: 400px;" name="chairman">
													                    <option value="0">Select Chairman</option>
													                </select>
													            </div>
												                <input  type="button" class="btn btn-primary btnShowAllChairman" onclick="showAllChairman(this);" value='Show All' style="float: left;">
												            </div>
												            
															<!-- <div class="col-sm-9">
																<?php echo form_dropdown('setup_chairman', $oppicer, $client_setup[0]->setup_chairman, 'id="currency" class="form-control director_jgn_sama" style="width:100%;"');?>
															</div> -->
														</div>

														<div class="form_director_signature_1 form-group">
															<label class="col-sm-3" for="w2-DS1">Director Signature 1:</label>
															<div class="col-sm-9">
																<div class="director_signature_1_group" style="float: left;margin-right: 10px">
																	<select id="director_signature_1" class="form-control director_signature_1" style="text-align:right; width: 400px;" name="director_signature_1">
													                    <option value="0">Select Director Signature 1</option>
													                </select>
													            </div>
												                <input  type="button" class="btn btn-primary btnShowAllDirectorSig1" onclick="showAllDirectorSig1(this);" value='Show All' style="float: left;">

																<!-- <?php echo form_dropdown('setup_director_signature1', $oppicer, $client_setup[0]->setup_director_signature1, 'id="currency" class="form-control director_jgn_sama" style="width:100%;"');?> -->
															</div>
															
														</div>
														<div class="form_director_signature_2 form-group">
															<label class="col-sm-3" for="w2-DS2">Director Signature 2:</label>
															<div class="col-sm-9">
																<div class="director_signature_2_group" style="float: left;margin-right: 10px">
																	<select id="director_signature_2" class="form-control director_signature_2" style="text-align:right; width: 400px;" name="director_signature_2" disabled="disabled">
													                    <option value="0">Select Director Signature 2</option>
													                </select>
													            </div>
												                <input  type="button" class="btn btn-primary btnShowAllDirectorSig2" onclick="showAllDirectorSig2(this);" value='Show All' style="float: left;">

																<!-- <?php echo form_dropdown('setup_director_signature2', $oppicer, $client_setup[0]->setup_director_signature2, 'id="currency" class="form-control director_jgn_sama" style="width:100%;"');?> -->
															</div>
														</div>
														<div id="setup_director_retiring_table">
															<table class="table table-bordered table-striped mb-none  director_retiring_table" id="director_retiring_table" style="width:100%;">
															<thead>
																<tr>
																	<th style="text-align:center; width:10px !important;padding-right:2px !important;padding-left:2px !important;">No</th>

																	<th style="text-align:center; width:100px !important;padding-right:2px !important;padding-left:2px !important;">NRIC</th>

																	<th style="text-align:center; width:100px !important;padding-right:2px !important;padding-left:2px !important;">Director Name</th>

																	<th style="text-align:center; width:100px !important;padding-right:2px !important;padding-left:2px !important;">Retiring</th>
																</tr>
															</thead>
															<!-- <div class="tbody body_director_retiring"> -->
																<?php
																	for($b = 0; $b < count($director_retiring); $b++)
																	{
																?>
																<tr class="tr_director_retiring">
																	<td><?= $b + 1?><input type="hidden" class="form-control" name="director_retiring_client_officer_id[]" value="<?=$director_retiring[$b]->id?>"/></td>
																	<td><?= $director_retiring[$b]->identification_no ?></td>
																	<td><?= $director_retiring[$b]->name ?></td>
																	<td><input type="checkbox" name="director_retiring_checkbox" <?= $director_retiring[$b]->retiring?'checked':''; ?>/>
	    																<input type="hidden" name="hidden_director_retiring_checkbox[]" value="<?= $director_retiring[$b]->retiring ?>"/>
	    															</td>
																</tr>
																<?php
																	}
																?>
															<!-- </div> -->
															</table>
														</div>
														<?php if ((!$Individual && $Individual == true) || (!$Individual && $Individual == null && !$Client)) {?>
														<h3 style="margin-top: 20px;">Contact Information</h3>
														<div class="form-group">
															<label class="col-sm-3" for="w2-chairman">Name:</label>
															<div class="col-sm-9">
																<input type="text" style="width:400px;text-transform:uppercase;" class="form-control" name="contact_name" id="contact_name" value="<?=$client_contact_info[0]->name?>"/>
												            </div>
															
														</div>
														<div class="form-group">
															<label class="col-sm-3" for="w2-chairman">Phone:</label>
															<div class="col-sm-9">
																<!-- <input type="text" style="width:400px;" class="form-control" name="contact_phone" id="contact_phone" value="<?=$client_contact_info[0]->phone?>"/> -->

																<div class="input-group fieldGroup_contact_phone">
											<input type="tel" class="form-control check_empty_contact_phone main_contact_phone hp" id="contact_phone" name="contact_phone[]" value="<?=$client_contact_info[0]->contact_phone?>"/>

											<input type="hidden" class="form-control input-xs hidden_contact_phone main_hidden_contact_phone" id="hidden_contact_phone" name="hidden_contact_phone[]" value=""/>

											<label class="radio-inline control-label" style="margin-top: -30px; margin-left: 20px;"><input type="radio" class="contact_phone_primary main_contact_phone_primary" name="contact_phone_primary" value="1" checked> Primary</label>

											<!-- <span class="input-group-btn" style="vertical-align: top !important;"> -->
												<input class="btn btn-primary button_increment_contact_phone addMore_contact_phone" type="button" id="create_button" value="+" style="margin-left: 20px; margin-top: -26px; border-radius: 3px;visibility: hidden; width: 35px;"/>
											<!-- </span> -->

											<button type="button" class="btn btn-default btn-sm show_contact_phone" style="margin-left: 20px; margin-top: -23px; visibility: hidden;">
												  <span class="fa fa-arrow-down" aria-hidden="true"></span>&nbsp<span class="toggle_word">Show more</span>
											</button>
	
										</div>

										<div class="contact_phone_toggle">
										</div>

										<div class="input-group fieldGroupCopy_contact_phone contact_phone_disabled" style="display: none;">
											<input type="tel" class="form-control check_empty_contact_phone second_contact_phone second_hp" id="contact_phone" name="contact_phone[]" value=""/>

											<input type="hidden" class="form-control input-xs hidden_contact_phone" id="hidden_contact_phone" name="hidden_contact_phone[]" value=""/>

											<label class="radio-inline control-label" style="margin-top: -30px; margin-left: 20px;"><input type="radio" class="contact_phone_primary" name="contact_phone_primary" value="1"> Primary</label>

											<!-- <span class="input-group-btn" style="vertical-align: top !important;"> -->
												<input class="btn btn-primary button_decrease_contact_phone remove_contact_phone" type="button" id="create_button" value="-" style="margin-left: 20px; margin-top: -26px; border-radius: 3px; width: 35px;"/>
											<!-- </span> -->
										</div>

										<div id="form_contact_phone"></div>

												            </div>
															
														</div>
														<div class="form-group">
															<label class="col-sm-3" for="w2-chairman">Email:</label>
															<div class="col-sm-9">
																<!-- <input type="email" style="width:400px;" class="form-control" name="contact_email" id="contact_email" value="<?=$client_contact_info[0]->email?>"/> -->
																<div class="input-group fieldGroup_contact_email" style="display: block !important;">
											<input type="text" class="form-control input-xs check_empty_contact_email main_contact_email" id="contact_email" name="contact_email[]" value="<?=$client_contact_info[0]->contact_email?>" style="text-transform:uppercase; width:400px;"/>

											<label class="radio-inline control-label" style="margin-left: 20px;"><input type="radio" class="contact_email_primary main_contact_email_primary" name="contact_email_primary" value="1" checked> Primary</label>
											
											<!-- <span class="input-group-btn" style="vertical-align: top !important;"> -->
												<input class="btn btn-primary button_increment_contact_email addMore_contact_email" type="button" id="create_button" value="+" style="margin-left: 20px; border-radius: 3px;visibility: hidden; width: 35px;"/>
											<!-- </span> -->

											<button type="button" class="btn btn-default btn-sm show_contact_email" style="margin-left: 20px; visibility: hidden;">
												  <span class="fa fa-arrow-down" aria-hidden="true"></span>&nbsp<span class="toggle_word">Show more</span>
											</button>
	
										</div>

										<div class="contact_email_toggle">
										</div>

										<div class="input-group fieldGroupCopy_contact_email contact_email_disabled" style="display: none;">
											<input type="text" class="form-control input-xs check_empty_contact_email second_contact_email" id="contact_email" name="contact_email[]" value="" style="width:400px; text-transform:uppercase; "/>
											<label class="radio-inline control-label" style="margin-left: 20px;"><input type="radio" class="contact_email_primary" name="contact_email_primary" value="1"> Primary</label>
											
											<!-- <span class="input-group-btn" style="vertical-align: top !important;"> -->
												<input class="btn btn-primary button_decrease_contact_email remove_contact_email" type="button" id="create_button" value="-" style="margin-left: 20px; border-radius: 3px; width: 35px;"/>
											<!-- </span> -->
										</div>

										<div id="form_contact_email"></div>
												            </div>
															
														</div>


														<div class="form-group">
															<div class="col-sm-4">
																<h3>Reminder</h3>
															</div>
														</div>

														<div class="form_select_reminder form-group">
															<label class="col-sm-3" for="w2-DS2">Reminder:</label>
															<div class="col-sm-9">
																<div class="select_reminder_group" style="float: left;margin-right: 10px">
													                <select class="form-control" id="select_reminder" multiple="multiple" name="select_reminder[]">
								                                    </select>
													            </div>
															</div>
														</div>

														<div class="form-group">
															<div class="col-sm-4">
																<h3>Corporate Representative</h3>
															</div>
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
														<?php
																	}
																?>
														<!-- <h3>Billing Information</h3> -->

														<!-- <div class="form-group">
															<div class="col-sm-4">
																<h3>Reminder</h3>
															</div>
															<div class="col-sm-8">
																<button type="button" class="btn btn-primary update_template" id="update_template" style="float: right" data-toggle="tooltip" data-trigger="hover" data-original-title="Click Here To Follow The Template of Billing">Template</button>
															</div>
														</div>

														<div style="display: table; border-collapse: collapse;">
														<thead>
															<div class="tr"> 

																<div class="th" valign=middle style="width:210px;text-align: center">Service</div>
																<div class="th" valign=middle style="width:250px;text-align: center">Invoice Description</div>
																<div class="th" style="width:180px;text-align: center">Amount</div>
																<div class="th" style="width:190px;text-align: center">Your Last Billing Cycle</div>
																<div class="th" style="width:190px;text-align: center">Recurring</div>
																

																<a href="javascript: void(0);" class="th" rowspan =2 style="color: #D9A200;width:130px; outline: none !important;text-decoration: none;"><span id="billing_info_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Billing Information" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add billing</span></a>
															</div>
															
														</thead>
														

														<div class="tbody" id="body_billing_info">
															

														</div>
														
														</div> -->


														<!-- <table class="table table-bordered">
															<tr>
																<th class="text-center">No</th>
																<th class="text-center">Service</th>
																<th class="text-center">Amount</th>
																<th class="text-center">Recurring</th>
																<th class="text-center">Frequency</th>
																<th style="font-size:25px">
																<span id="billing_add"><i class="fa fa-plus-circle"></i></span>
																</th>
															</tr>
															<tbody id="tbody_setup"> -->
															<!-- <?php
															$i = 1;
															// print_r($client_service);
																foreach($client_service as $cs)
																{
															?>
															<tr>
																<td class="ads1"><?=$i?></td>
																<td>
																<?php echo form_dropdown('service_name[]', $svc, $cs->service_name, 'id="currency"  class="form-control  populate" style="width:100%;"');?>
																</td>
																<td>
																	<input type="text" name="service_amount[]" class="form-control number number text-right" value="<?=number_format($cs->service_amount,2)?>"/>
																</td>
																<td>
																	<span style="float:left;margin:5px 3px;width:20%;font-size:12px;">Start Date</span><input type="text" class="form-control" name="service_start_recurring[]"  style="float:left;width:75%;" data-date-format="dd/mm/yyyy"  value="<?=date_format(date_create($cs->service_start_recurring),'d/m/Y')?>" data-date-start-date="0d" data-plugin-datepicker/>
																	<br/>
																	<span style="float:left;margin:3px;width:20%;">End Date</span><input type="text" class="form-control" style="float:left;width:75%;" name="service_end_recurring[]" data-date-format="dd/mm/yyyy"  value="<?=date_format(date_create($cs->service_end_recurring),'d/m/Y')?>" data-date-start-date="0d" data-plugin-datepicker>
																</td>
																<td>
																	<select name="service_frequency[]">
																		<optgroup label="Frequency">
																			<option value="0">On Change</option>
																			<option value="7">1 Week</option>
																			<option value="14">2 Week</option>
																			<option value="21">3 Week</option>
																			<option value="30">1 Month</option>
																			<option value="60">2 Month</option>
																			<option value="90">3 Month</option>
																			<option value="180">6 Month</option>
																			<option value="356">1 Year</option>
																		</optgroup>
																	</select>
																</td>
																<td ><a ><i class="fa fa-times hapus_baris"></i></a></td>
															</tr>
															<?php
																	$i++;
																}
															?>
															<tr>
																<td class="ads1"><?=$i?></td>
																<td>
																<?php echo form_dropdown('service_name[]', $svc, $ch->chargee_name, 'id="currency"  class="form-control  populate" style="width:100%;"');?>
																</td>
																<td>
																	<input type="text" name="service_amount[]" class="form-control number text-right" value="<?=$ch->service_amount?>"/>
																</td>
																<td>
																	<span style="float:left;margin:5px 3px;width:20%;">Start Date</span><input type="text" class="form-control" name="service_start_recurring[]"  style="float:left;width:75%;" data-date-format="dd/mm/yyyy" data-date-start-date="0d" data-plugin-datepicker value="<?=$ch->service_start_recurring?>"/>
																	<br/>
																	<span style="float:left;margin:3px;width:20%;">End Date</span><input type="text" class="form-control" style="float:left;width:75%;" name="service_end_recurring[]" data-date-format="dd/mm/yyyy" data-date-start-date="0d" data-plugin-datepicker value="<?=$ch->service_end_recurring?>">
																</td>
																<td>
																	<select name="service_frequency[]">
																		<optgroup label="Frequency">
																			<option value="0" <?=$ch->service_frequency==0?'selected':'';?>>On Change</option>
																			<option value="7" <?=$ch->service_frequency==7?'selected':'';?>>1 Week</option>
																			<option value="14" <?=$ch->service_frequency==14?'selected':'';?>>2 Week</option>
																			<option value="21" <?=$ch->service_frequency==21?'selected':'';?>>3 Week</option>
																			<option value="30" <?=$ch->service_frequency==30?'selected':'';?>>1 Month</option>
																			<option value="60" <?=$ch->service_frequency==60?'selected':'';?>>2 Month</option>
																			<option value="90" <?=$ch->service_frequency==90?'selected':'';?>>3 Month</option>
																			<option value="180" <?=$ch->service_frequency==180?'selected':'';?>>6 Month</option>
																			<option value="356" <?=$ch->service_frequency==365?'selected':'';?>>1 Year</option>
																		</optgroup>
																	</select>
																</td>
																<td><a ><i class="fa fa-times hapus_baris"></i></a></td>
															</tr> -->
															<!-- </tbody> -->
														<!-- </table> -->
														
														<?= form_close(); ?>
													</div>
													<?php
														}
													?>
													<?php if ((!$Individual && $Individual == true) || (!$Individual && $Individual == null && !$Client)) {?>
													<?php if ($billing_module != 'none') { ?>
													<!-- Setup -->
													<div id="w2-billing" class="tab-pane">
														<?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form', 'id' => 'billing_form');
															echo form_open_multipart("masterclient/add_client_billing_info", $attrib);
														?>
															<div class="hidden"><input type="text" class="form-control" name="company_code" value="<?=$company_code?>"/></div>

															<div class="form-group">
															<div class="col-sm-6">
																<h3>Service Engagement Information</h3>
															</div>
															<div class="col-sm-6">
																<button type="button" class="btn btn-primary update_template" id="update_template" style="float: right" data-toggle="tooltip" data-trigger="hover" data-original-title="Click Here To Follow The Template of Service Engagement">Template</button>
															</div>
														</div>
														<!-- <table class="table table-bordered table-striped mb-none" id="datatable-default"> -->
														<div style="display: table; border-collapse: collapse;">
														<thead>
															<div class="tr"> 
																<!-- <div class="th" valign=middle>No</div> -->
																<div class="th" valign=middle style="width:210px;text-align: center">Service</div>
																<div class="th" valign=middle style="width:250px;text-align: center">Invoice Description</div>
																<div class="th" style="width:180px;text-align: center">Currency</div>
																<div class="th" style="width:180px;text-align: center">Amount</div>
																<div class="th" style="width:180px;text-align: center">Unit Pricing</div>
																<!-- <div class="th" style="width:190px;text-align: center">Your Last Billing Cycle</div> -->
																<!-- <div class="th" style="width:190px;text-align: center">Recurring</div> -->
																
																<!-- <div class="th" style="font-size:25px;"><span id="billing_info_Add"><i class="fa fa-plus-circle"></i></span></div> -->

																<a href="javascript: void(0);" class="th" rowspan =2 style="color: #D9A200;width:230px; outline: none !important;text-decoration: none;"><span id="billing_info_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Service Engagement Information" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add Service Engagement</span></a>
															</div>
															
														</thead>
														

														<div class="tbody" id="body_billing_info">
															

														</div>
														
														</div>

														<?= form_close(); ?>
													</div>
													<?php
														}
													?>

													<?php if ($filing_module != 'none') { ?>
													<div id="w2-filing" class="tab-pane">
														<h3>Key Filing</h3>
														<button type="button" class="collapsible" style="margin-top: 10px;"><span style="font-size: 2.4rem;">AGM and Annual Return</span></button>
														<div class="incorp_content">
														<?php echo form_open_multipart('', array('id' => 'filing_form', 'enctype' => "multipart/form-data")); ?>

															<div class="hidden"><input type="text" class="form-control" name="company_code" value="<?=$company_code?>"/></div>
															<div class="hidden"><input type="text" class="form-control filing_id" name="filing_id" value="<?=$filing->id?>"/></div>

															
															
															<div class="form-group" style="margin-top: 20px; margin-bottom: 20px;">
																<label class="col-xs-3" for="w2-show_all">Year End: </label>
																<div class="col-sm-9 form-inline">
																	<div class="input-bar">
																	    <div class="input-bar-item">
																	      <!-- <form> -->
																	         <div class="year_end_group">
																	            <div class="input-group" style="width: 200px;">
																					<span class="input-group-addon">
																						<i class="far fa-calendar-alt"></i>
																					</span>
																					<input type="text" class="form-control year_end_date" id="year_end_date" name="year_end" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="<?=$filing->year_end?>" placeholder="dd MMMM yyyy ">
																				</div>
																				<div id="validate_year_end_date"></div>
																	        </div>
																	      <!-- </form> -->
																	    </div>
																	    <div class="input-bar-item input-bar-item-btn">
																	      <button type="button" class="btn btn-primary change_year_end_button" onclick="change_year_end(this)" style="display: none">Change</button>
																	    </div>
																	</div>
																</div>
															</div>

															

															
															<div class="form-group">
																<label class="col-sm-3" for="w2-DS1">Financial Year Cycle:</label>
																	<div class="col-sm-3">
																		<select id="financial_year_period" class="form-control financial_year_period" id="financial_year_period" style="width:200px;" name="financial_year_period">
														                    <!-- <option value="0">Please Select</option> -->
														                </select>
																	</div>

															</div>
															<div class="form-group">
																<label class="col-sm-3" for="w2-DS1">Due Date (S.175):</label>
																	<div class="col-sm-3">
																		<input type="text" name="due_date_175" class="form-control" value="" id="due_date_175" readonly="true" style="width: 200px;"/>
																	</div>
																	
																	<div class="col-sm-6">
																		<label class="col-sm-3" for="extended_to">Extended to</label>
																		<select id="extended_to_175" class="form-control extended_to_175" id="extended_to_175" style="width:200px;" name="extended_to_175">
														                    <option value="0">Please Select</option>
														                </select>
														            </div>
															</div>
															<div class="form-group">
																<label class="col-sm-3" for="w2-DS2">Due Date (S.201):</label>
																
																<div class="col-sm-3">
																	<input type="text" name="due_date_201" class="form-control" value="" id="due_date_201" readonly="true" style="width: 200px;"/>
																</div>
																
																<div class="col-sm-6">
																	<label class="col-sm-3" for="extended_to">Extended to</label>
																	<select id="extended_to_201" class="form-control extended_to_201" id="extended_to_201" style="width:200px;" name="extended_to_201">
													                    <option value="0">Please Select</option>
													                </select>
													            </div>
															</div>
															<div class="form-group">
																<label class="col-sm-3" for="w2-DS3">Due Date (S.197):</label>
																
																<div class="col-sm-3">
																	<input type="text" name="due_date_197" class="form-control" value="" id="due_date_197" readonly="true" style="width: 200px;"/>
																</div>
																
																<div class="col-sm-6">
																	<label class="col-sm-3" for="extended_to">Extended to</label>
																	<select id="extended_to_197" class="form-control extended_to_197" id="extended_to_197" style="width:200px;" name="extended_to_197">
													                    <option value="0">Please Select</option>
													                </select>
													            </div>
															</div>
															<div class="form-group">
																<label class="col-sm-3" for="w2-AGM">AGM:</label>
																<div class="col-sm-9 form-inline">
																	<div class="input-bar">
																	    <div class="input-bar-item">
																	      <!-- <form> -->
																	        <div class="year_end_group">
																	            <div class="input-group" style="width: 200px;">
																					<span class="input-group-addon">
																						<i class="far fa-calendar-alt"></i>
																					</span>

																					<input type="text" class="form-control" id="agm_date" name="agm" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="<?=$filing->agm?>" placeholder="dd MMMM yyyy">
																				</div>
																				<div id="validate_year_end_date"></div>
																	        </div>
																	      <!-- </form> -->
																	    </div>
																	    <div class="input-bar-item input-bar-item-btn">
																	      <button type="button" class="btn btn-primary dispense_agm_button" onclick="dispense_agm(this)" style="display: none">Dispense AGM</button>
																	    </div>
																	</div>




																	<!-- <div class="input-group" style="width: 200px;">
																		<span class="input-group-addon">
																			<i class="far fa-calendar-alt"></i>
																		</span>
																		<input type="text" class="form-control valid" id="agm_date" name="agm" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="<?=$filing->agm?>" placeholder="dd MMMM yyyy">
																	</div> -->
																	
													            </div>

															</div>
															<div class="form-group">
																<label class="col-sm-3" for="w2-DS1">AR Filing Date:</label>
																	<div class="col-sm-3">
																		<div class="year_end_group">
																	           <div class="input-group" style="width: 200px;">
																				<span class="input-group-addon">
																					<i class="far fa-calendar-alt"></i>
																				</span>
																				<input type="text" name="ar_filing_date" data-date-format="dd/mm/yyyy" class="form-control" data-plugin-datepicker="" value="" id="ar_filing_date" placeholder="dd MMMM yyyy"/>
																			</div>
																		</div>
																	</div>

															</div>
															<div>
																<div class="form-group">
																	<div class="col-sm-12">
																		<button type="button" class="btn btn-primary update_filling" id="update_filling" style="float: right">Update</button>
																	</div>
																</div>
																
																<div style="margin-bottom: 20px;">
																	<h3>AGM and Annual Return Filing History</h3>
																	<table style="border:1px solid black" class="allotment_table" id="filing_table">
														
																		<tr> 
																			<th style="width:50px !important;text-align: center">No</th>
																			<th style="text-align: center">Year End</th> 
																			<th style="text-align: center">Financial Year Period</th>
																			<th style="text-align: center">Due Date (S.175)</th> 
																			<th style="text-align: center">Due Date (S.201)</th> 
																			<th style="text-align: center">Due Date (S.197)</th>
																			<th style="text-align: center">AGM</th> 
																			<th style="text-align: center">AR Filing Date</th>
																			<th></th>
																		</tr> 

																	</table>
																</div>	
															</div>
															<?= form_close(); ?>
														</div>
														<!---ECI--->
														<button type="button" class="collapsible"><span style="font-size: 2.4rem;">Estimated Chargeable Income (ECI)</span></button>
														<div class="incorp_content">
														<?php echo form_open_multipart('', array('id' => 'eci_form', 'enctype' => "multipart/form-data")); ?>

															<div class="hidden"><input type="text" class="form-control" name="company_code" value="<?=$company_code?>"/></div>
															<div class="hidden"><input type="text" class="form-control eci_id" name="eci_id" value="<?=$eci->id?>"/></div>

															
															
															<div class="form-group" style="margin-top: 20px; margin-bottom: 20px;">
																<label class="col-xs-3" for="w2-show_all">ECI Tax Period: </label>
																<div class="col-sm-9 form-inline">
																	<div class="input-bar">
																	    <div class="input-bar-item">
																            <div class="input-group" style="width: 200px;">
																				<span class="input-group-addon">
																					<i class="far fa-calendar-alt"></i>
																				</span>
																				<input type="text" class="form-control eci_tax_period" id="eci_tax_period" name="eci_tax_period" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="<?=$eci->eci_tax_period?>" placeholder="dd MMMM yyyy ">
																			</div>
																			<div id="validate_eci_tax_period"></div>
																	    </div>
																	</div>
																</div>
															</div>
															<div class="form-group">
																<label class="col-sm-3" for="w2-DS1">ECI Filing Date: </label>
																	<div class="col-sm-3">
																		<div class="input-group" style="width: 200px;">
																		<span class="input-group-addon">
																			<i class="far fa-calendar-alt"></i>
																		</span>
																		<input type="text" class="form-control eci_filing_date" id="eci_filing_date" name="eci_filing_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="<?=$eci->eci_filing_date?>" placeholder="dd MMMM yyyy ">
																	</div>
																	</div>

															</div>
															<div class="form-group">
																<label class="col-sm-3" for="w2-DS1">Next ECI Filing Due Date:</label>
																<div class="col-sm-3">
																	<input type="text" name="next_eci_filing_due_date" class="form-control" value="" id="next_eci_filing_due_date" readonly="true" style="width: 200px;"/>
																</div>
															</div>
															<div>
																<div class="form-group">
																	<div class="col-sm-12">
																		<button type="button" class="btn btn-primary update_eci" id="update_eci" style="float: right">Update</button>
																	</div>
																</div>
																
																<div style="margin-bottom: 20px;">
																	<h3>ECI Filing History</h3>
																	<table style="border:1px solid black" class="allotment_table" id="eci_filing_table">
														
																		<tr> 
																			<th style="width:50px !important;text-align: center">No</th>
																			<th style="text-align: center">ECI Tax Period</th> 
																			<th style="text-align: center">ECI Filing Date</th>
																			<th style="text-align: center">Next ECI Filing Due Date</th> 
																			<th></th>
																		</tr> 

																	</table>
																</div>	
															</div>
															<?= form_close(); ?>
														</div>
														<!---ECI--->
														<button type="button" class="collapsible"><span style="font-size: 2.4rem;">Coporate Tax</span></button>
														<div class="incorp_content">
														<?php echo form_open_multipart('', array('id' => 'tax_form', 'enctype' => "multipart/form-data")); ?>

															<div class="hidden"><input type="text" class="form-control" name="company_code" value="<?=$company_code?>"/></div>
															<div class="hidden"><input type="text" class="form-control tax_id" name="tax_id" value="<?=$tax->id?>"/></div>

															
															
															<div class="form-group" style="margin-top: 20px; margin-bottom: 20px;">
																<label class="col-xs-3" for="w2-show_all">Coporate Tax Period: </label>
																<div class="col-sm-9 form-inline">
																	<div class="input-bar">
																	    <div class="input-bar-item">
																            <div class="input-group" style="width: 200px;">
																				<span class="input-group-addon">
																					<i class="far fa-calendar-alt"></i>
																				</span>
																				<input type="text" class="form-control coporate_tax_period" id="coporate_tax_period" name="coporate_tax_period" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="<?=$eci->coporate_tax_period?>" placeholder="dd MMMM yyyy ">
																			</div>
																			<div id="validate_coporate_tax_period"></div>
																	    </div>
																	</div>
																</div>
															</div>
															<div class="form-group">
																<label class="col-sm-3" for="w2-DS1">Tax Filing Period:</label>
																<div class="col-sm-3">
																	<input type="text" name="tax_filing_period" class="form-control" value="" id="tax_filing_period" readonly="true" style="width: 200px;"/>
																</div>
															</div>
															<div class="form-group">
																<label class="col-sm-3" for="w2-DS1">Filing Date: </label>
																<div class="col-sm-3">
																	<div class="input-group" style="width: 200px;">
																		<span class="input-group-addon">
																			<i class="far fa-calendar-alt"></i>
																		</span>
																		<input type="text" class="form-control tax_filing_date" id="tax_filing_date" name="tax_filing_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="dd MMMM yyyy ">
																	</div>
																</div>
															</div>
															<div class="form-group">
																<label class="col-sm-3" for="w2-DS1">Tax Filing Due Date:</label>
																<div class="col-sm-3">
																	<input type="text" name="tax_filing_due_date" class="form-control" value="" id="tax_filing_due_date" readonly="true" style="width: 200px;"/>
																</div>
															</div>
															<div>
																<div class="form-group">
																	<div class="col-sm-12">
																		<button type="button" class="btn btn-primary update_tax" id="update_tax" style="float: right">Update</button>
																	</div>
																</div>
																
																<div style="margin-bottom: 20px;">
																	<h3>Coporate Tax Filing History</h3>
																	<table style="border:1px solid black" class="allotment_table" id="tax_filing_table">
														
																		<tr> 
																			<th style="width:50px !important;text-align: center">No</th>
																			<th style="text-align: center">Coporate Tax Period</th> 
																			<th style="text-align: center">Tax Filing Period</th>
																			<th style="text-align: center">Filing Date</th> 
																			<th style="text-align: center">Tax Filing Due Date</th> 
																			<th></th>
																		</tr> 

																	</table>
																</div>	
															</div>
															<?= form_close(); ?>
														</div>
													</div>
														<?php
														}
													?>
													<?php
														}
													?>
													<?php if ($register_module != 'none') { ?>
													<div id="w2-register" class="tab-pane <?php if ($Individual || $Client) {?> active <?php }?>">
														<?php echo form_open_multipart('', array('id' => 'register_form', 'enctype' => "multipart/form-data")); ?> 
														
														<!-- <?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form', 'id' => 'register_form');
															echo form_open_multipart("masterclient/search_register", $attrib);
														?> -->
															<div class="hidden"><input type="text" class="form-control" name="company_code" value="<?=$company_code?>"/></div>


															<span style="font-size: 2.4rem;">Register</span>
															<a href="javascript: void(0);" class="btn btn-default" id="printBtn" class="printBtn" style="float: right;">Print</a>
															
															<div class="form-group" style="margin-top: 20px;">
																<label class="col-xs-3 control-label" for="w2-show_all">Register: </label>
																<div class="col-sm-9 form-inline">
																	<select id="register" class="form-control register" id="register" style="width:200px;" name="register">
													                    <option value="0">Please Select</option>
													                    <option value="all" selected <?=$_POST['type'] == "all"?'selected':'';?>>All</option>
													                    <option value="profile" <?=$_POST['type'] == "profile"?'selected':'';?>>Profile</option>
													                    <option value="officer" <?=$_POST['type'] == "officer"?'selected':'';?>>Officers</option>
													                    <option value="member" <?=$_POST['type'] == "member"?'selected':'';?>>Members</option>
													                    <option value="controller" <?=$_POST['type'] == "controller"?'selected':'';?>>Controller</option>
													                    <option value="charges" <?=$_POST['type'] == "charges"?'selected':'';?>>Charges</option>
																		<option value="filing" <?=$_POST['type']=='filing'?'selected':'';?>>Filing</option>
																		
																		
																		
													                </select>
																</div>
															</div>

															<div class="form-group">
																<label class="col-xs-3 control-label" for="w2-show_all">Date Range: </label>
																<div class="col-sm-5 form-inline">
																	<div class="input-daterange input-group" data-plugin-datepicker >
																		<span class="input-group-addon">
																			<i class="far fa-calendar-alt"></i>
																		</span>
																		<input type="text" class="form-control" name="from" value="" placeholder="From" id="register_from" data-date-format="dd/mm/yyyy">
																		<span class="input-group-addon">to</span>
																		<input type="text" class="form-control" name="to" value="" placeholder="To" id="register_to" data-date-format="dd/mm/yyyy">
																	</div>
																</div>
																<div class="col-md-3" style="padding-left: 0px;">
																	<input type="button" class="btn btn-primary" id="searchRegister" name="searchRegister" value="Search"/>
																</div>
															</div>
															<HR SIZE=10></HR>


																<div class="printable" style="width: 100%">
																	<!-- <button type="button" class="collapsible"><span style="font-size: 2.4rem;">PROFILE</span></button>
														<div class="incorp_content">
																<div id="register_profile" style="display: none">
																	
																	<table class="table" style="border:none">
																		<tr>
																			<td style="border:none;width: 200px;">
																				Client Code: 
																			</td>
																			<td style="border:none">
																				<div style="" id="register_client_code"></div>
																			</td>
																			
																			
																		</tr>
																		<tr>
																			<td style="border:none;width: 200px;">
																				Registration No:
																			</td>
																			<td style="border:none">
																				<div style="" id="register_registration_no"></div>
																			</td>
																			
																			
																		</tr>
																		<tr>
																			<td style="border:none;width: 200px;">
																				Company Name:
																			</td>
																			<td style="border:none">
																				<div style="" id="register_company_name"></div>
																			</td>
																			
																			
																		</tr>
																		<tr class="form-group former_name">
																			<td style="border:none;width: 200px;">
																				Former Name (if any):
																			</td>
																			<td style="border:none">
																				<div style="" id="register_former_name"></div>
																			</td>
																			
																			
																		</tr>
																		<tr class="form-group">
																			<td style="border:none;width: 200px;">
																				Incorporation Date:
																			</td>
																			<td style="border:none">
																				<div style="" id="register_incorporation_date"></div>
																			</td>
																			
																			
																		</tr>
																		<tr class="form-group">
																			<td style="border:none;width: 200px;">
																				Company Type:
																			</td>
																			<td style="border:none">
																				<div style="" id="register_company_type"></div>
																			</td>
																			
																			
																		</tr>
																		<tr class="form-group">
																			<td style="border:none;width: 200px;">
																				Status:
																			</td>
																			<td style="border:none">
																				<div style="" id="register_status"></div>
																			</td>
																			
																			
																		</tr>
																		<tr class="form-group">
																			<td style="border:none;">
																				<b>Principal Activities</b>
																			</td>
																		</tr>
																		<tr class="form-group">
																			<td style="border:none;width: 200px;">
																				Activity 1:
																			</td>
																			<td style="border:none">
																				<div style="" id="register_activity1"></div>
																			</td>
																			
																			
																		</tr>
																		<tr class="form-group activity2">
																			<td style="border:none;width: 200px;">
																				Activity 2:
																			</td>
																			<td style="border:none">
																				<div style="" id="register_activity2"></div>
																			</td>
																			
																			
																		</tr>
																		<tr class="form-group">
																			<td style="border:none;">
																				<b>Registered Office Address</b>
																			</td>
																		</tr>
																		<tr class="form-group">
																			<td style="border:none;width: 200px;">
																				Registered Office Address:
																			</td>
																			<td style="border:none">
																				<div style="width: 100%;">
																					<div style="width: 25%;float:left;margin-right: 20px;">
																						<label>Postal Code :</label>
																					</div>
																					<div style="width: 65%;float:left;margin-bottom:5px;">
																						<div style="width: 20%;" id="register_postal_code"></div>
																					</div>
																				</div> 
																			</td>

																		</tr>
																		<tr class="form-group">
																			<td style="border:none;width: 200px;">
																			</td>
																			<td style="border:none">
																				<div style="width: 100%;">
																					<div style="width: 25%;float:left;margin-right: 20px;">
																						<label>Street Name :</label>
																					</div>
																					<div style="width: 65%;float:left;margin-bottom:5px;">
																						<div style="width: 100%;" id="register_street_name"></div>
																					</div>
																				</div> 
																			</td>

																		</tr>
																		<tr class="form-group">
																			<td style="border:none;width: 200px;">
																			</td>
																			<td style="border:none">
																				<div style="width: 100%;">
																					<div style="width: 25%;float:left;margin-right: 20px;">
																						<label>Building Name :</label>
																					</div>
																					<div style="width: 65%;float:left;margin-bottom:5px;">
																						<div style="width: 100%;" id="register_building_name"></div>
																					</div>
																				</div> 
																			</td>

																		</tr>
																		<tr class="form-group">
																			<td style="border:none;width: 200px;">
																			</td>
																			<td style="border:none">
																				<div style="width: 100%;">
																					<div style="width: 25%;float:left;margin-right: 20px;">
																						<label>Unit No :</label>
																					</div>
																					<div style="width: 65%;float:left;margin-bottom:5px;">
																						<div style="width: 5%; float: left;" id="register_unit_no1"></div>
																						
																						<label style="float: left;margin-right: 10px;margin-left: 10px;" >-</label>
																						<div style="width: 8%;float: left;" id="register_unit_no2"></div>
																					</div>
																				</div> 
																			</td>

																		</tr>
																		
																		
																	</table>
														</div>
													</div> -->
													
																<div id="register_table">
																	
																</div>
																
						</div>
															
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
							</div>
							<footer class="panel-footer">
								<div class="row">
									<div class="col-md-12 number text-right" id="client_footer_button">
										<!--input type="button" value="Save As Draft" id="save_draft" class="btn btn-default"-->
										<input type="button" value="Save" id="save" class="btn btn-primary ">
										<a href="<?= base_url();?>masterclient/" class="btn btn-default">Cancel</a>
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
		<div class="loading" id='loadingClient'>Loading&#8230;</div>
	</div>
</section>
<!-- Add Officer -->
<div id="modal_officer" class="modal-block modal-block-lg mfp-hide">
	<section class="panel" id="wOfficer">
		<header class="panel-heading">
			<h2 class="panel-title">Add Officer</h2>
		</header>
		<div class="panel-body">
			<?php 
				$attrib2= array( 'id' => 'Save_Officer','method' => 'POST');
				echo form_open_multipart("masterclient/add_officer",$attrib2); 
				
			// <form action="masterclient/add_officer" method="POST" id="Save_Officer">
			?>
			<input type="hidden" class="form-control" value="<?=$unique_code?>" name="unique_code"/>
			<table class="table table-bordered table-striped table-condensed mb-none" >
				<thead>
					<tr>
						<th>ID</th>
						<td>
						<!-- <input type="text" class="form-control" name="id" value="" id="gid_add_officer1111"/> --></td>
					</tr>
					<tr>
						<th>Name</th>
						<td><input type="text" class="form-control input-xs" name="nama" id="add_officer_nama" value=""/></td>
					</tr>
					<tr>
						<th>Position</th>
						<td>
							<select name="position" class="form-control " id="position">
								<option value="Director">Director</option>
								<option value="CEO">CEO</option>
								<option value="Manager">Manager</option>
								<option value="Secretary">Secretary</option>
								<option value="Auditor">Auditor</option>
								<option value="Managing Director">Managing Director</option>
								<option value="Alternate Director">Alternate Director</option>
							</select>
						</td>
					</tr>
					<tr>
						<th>Date Of Appoinment</th>
						<td><input type="text" class="form-control " name="date_of_appointment" id="date_of_appointment" data-plugin-datepicker data-date-format="dd/mm/yyyy" value="<?=$ndate;?>"/></td>
					</tr>
					<tr>
						<th>Date Of Cessation</th>
						<td><input type="text" class="form-control " name="date_of_cessation" id="date_of_cessation"  data-plugin-datepicker data-date-format="dd/mm/yyyy" value="<?=$ndate;?>"/></td>
					</tr>
					<tr>
						<th>Address</th>
									<td>
										<div style="margin-bottom:5px;">
											<label style="width: 25%;float:left;margin-right: 20px;">Postal Code :</label>
											<div class="input-group" style="width: 40%;" >
											<input type="text" class="form-control input-sm" id="add_officer_postal_code" name="zipcode" required placeholder="Search Postal">
											<span class="input-group-btn hidden">
												<button class="btn btn-default" type="submit" style="height:30px;"><i class="fa fa-search"></i></button>
											</span>
											</div>
										</div>
										<div style="margin-bottom:5px;">
											<label style="width: 25%;float:left;margin-right: 20px;">Street Name :</label>
														<input style="width: 51%;" type="text" class="form-control input-sm" id="add_officer_street" name="street">
										
										</div>
										<div style="margin-bottom:5px;">
											<label style="width: 25%;float:left;margin-right: 20px;">Building Name :</label>
											<input style="width: 51%;" type="text" class="form-control input-sm" id="add_officer_buildingname" name="buildingname">
										
										</div>
										<div style="margin-bottom:5px;">
											<label style="width: 25%;float:left;margin-right: 20px;">Unit No :</label>
														<input style="width: 10%; float: left; margin-right: 10px;" type="text" class="form-control input-sm" id="unit_no1" name="unit_no1">
														<input style="width: 10%;" type="text" class="form-control input-sm" id="unit_no2" name="unit_no2" >
										
										</div>
									</td>
								</tr>
					<tr>
						<th>Alternate Address</th>
						<td><textarea style="height:100px;" name="alternate_address" id="alternate_address" ></textarea></td>
					</tr>
					<tr>
						<th>Nationality</th>
						<td><input type="text" class="form-control" name="nationality" id="nationality" value=""/></td>
					</tr>
					<tr>
						<td>Citizen</td>
						<td><?php
								// print_r($sales);
								$ctz[""] = [];
								foreach ($citizen as $cs) {
									$ctz[$cs->id] = $cs->citizen;
								}
								echo form_dropdown('citizen', $ctz, '', 'id="citizen"  class="form-control" style="width:100%;"');
							
								?>
						</td></tr>
					<tr>
						<th>Date Of Birth</th>
						<td><input type="text" class="form-control"  name="date_of_birth"  id="date_of_birth" data-plugin-datepicker data-date-format="dd/mm/yyyy" data-date-start="0" value=""/></td>
							
					</tr>
				</thead>
			</table>
			
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 number text-right">
					<!--button class="btn btn-primary modal-confirm">Confirm</button-->
					<button class="btn btn-primary" id="btn_add_officer">Submit</button>
					<button class="btn btn-danger modal-dismiss">Close</button>
				</div>
			</div>
		</footer>
		<?=form_close();?>
	</section>
</div>
<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>
<script>
	var client = <?php echo json_encode($client);?>;
	var client_officers = <?php echo json_encode($client_officers);?>;
	var client_guarantee = <?php echo json_encode($client_guarantee);?>;
	var client_controller = <?php echo json_encode($client_controller);?>;
	var client_charges = <?php echo json_encode($client_charges);?>;
	var client_share_capital = <?php echo json_encode($client_share_capital);?>;
	var allotment = <?php echo json_encode($allotment);?>;
	var client_billing_info = <?php echo json_encode($client_billing_info);?>;
	var client_signing_info = <?php echo json_encode($client_signing_info);?>;
	var client_contact_info = <?php echo json_encode($client_contact_info);?>;
	var client_contact_info_email = <?php echo json_encode($client_contact_info[0]->client_contact_info_email);?>;
	var client_selected_reminder = <?php echo json_encode($client_reminder_info) ?>;
	var client_contact_info_phone = <?php echo json_encode($client_contact_info[0]->client_contact_info_phone);?>;
	var filing_data = <?php echo json_encode($filing_data);?>;
	var eci_filing_data = <?php echo json_encode($eci_filing_data);?>;
	var tax_filing_data = <?php echo json_encode($tax_filing_data);?>;
	var template = <?php echo json_encode($template);?>;
	var company_code = "<?php echo ($company_code);?>";
	var access_right_client_module = <?php echo json_encode($client_module);?>;
	var access_right_company_info_module = <?php echo json_encode($company_info_module);?>;
	var access_right_officer_module = <?php echo json_encode($officer_module);?>;
	var access_right_member_module = <?php echo json_encode($member_module);?>;
	var access_right_controller_module = <?php echo json_encode($controller_module);?>;
	var access_right_charge_module = <?php echo json_encode($charges_module);?>;
	var access_right_filing_module = <?php echo json_encode($filing_module);?>;
	var access_right_register_module = <?php echo json_encode($register_module);?>;
	var access_right_setup_module = <?php echo json_encode($setup_module);?>;
	var company_type = <?php echo json_encode($client->company_type)?>;
	var client_status = <?php echo json_encode($client->status)?>;
	var first_time = <?php echo json_encode($first_time)?>;
	var admin = <?php echo json_encode($Admin)?>;
	var corp_rep_data = <?php echo json_encode($corp_rep_data); ?>;
	var date = new Date();
	var tab = <?php echo json_encode($tab);?>;
//console.log(client_contact_info);
</script>
<script src="themes/default/assets/js/intlTelInput.js?v=30eee4fc8d1b59e4584b0d39edfa2082" /></script>
<script src="themes/default/assets/js/defaultCountryIp.js?v=30eee4fc8d1b59e4584b0d39edfa2082" /></script>
<script src="themes/default/assets/js/utils.js?v=30eee4fc8d1b59e4584b0d39edfa2082" /></script>
<script src="themes/default/assets/js/companyType.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
<script src="themes/default/assets/js/officers_tab.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
<script src="themes/default/assets/js/controller_tab.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
<script src="themes/default/assets/js/charges_tab.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
<script src="themes/default/assets/js/members_tab.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
<script src="themes/default/assets/js/guarantee_tab.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
<script src="themes/default/assets/js/setup_tab.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
<script src="themes/default/assets/js/eci_filing_tab.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
<script src="themes/default/assets/js/tax_filing_tab.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
<script src="themes/default/assets/js/filing_tab.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
<script src="themes/default/assets/js/register_tab.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
<script src="themes/default/assets/js/addpersonprofile.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
<script>
	$tab_aktif = "companyInfo";
	var base_url = '<?php echo base_url() ?>';

	$("#header_our_firm").removeClass("header_disabled");
	$("#header_manage_user").removeClass("header_disabled");
	$("#header_access_right").removeClass("header_disabled");
	$("#header_user_profile").removeClass("header_disabled");
	$("#header_setting").removeClass("header_disabled");
	$("#header_dashboard").removeClass("header_disabled");
	$("#header_client").addClass("header_disabled");
	$("#header_person").removeClass("header_disabled");
	$("#header_document").removeClass("header_disabled");
	$("#header_report").removeClass("header_disabled");
	$("#header_billings").removeClass("header_disabled");

	//console.log(base_url);

	coll = document.getElementsByClassName("collapsible");

	// if(transaction_client)
	// {
	//     for (var g = 0; g < coll.length; g++) {
	//         coll[g].classList.toggle("incorp_active");
	//         coll[g].nextElementSibling.style.maxHeight = "100%";
	//     }
	// }
	// else
	// {
	//     coll[0].classList.toggle("incorp_active");
	//     coll[0].nextElementSibling.style.maxHeight = "100%";
	// }

	for (var g = 0; g < coll.length; g++) {
	    coll[g].classList.toggle("incorp_active");
	    coll[g].nextElementSibling.style.maxHeight = "100%";
	}

	for (var i = 0; i < coll.length; i++) {
	  coll[i].addEventListener("click", function() {
	    this.classList.toggle("incorp_active");
	    var content = this.nextElementSibling;
	    if (content.style.maxHeight){
	      content.style.maxHeight = null;
	    } else {
	      content.style.maxHeight = "100%";
	    } 
	  });
	}

	if (client)
	{
		if(!admin)
		{
			$(".acquried_by").attr('disabled', 'disabled');
		}
	}
	
	$('.create_auto_generate').on("click",function(e){
		e.preventDefault();
		bootbox.confirm("Are you comfirm completed importing all information of client?", function (result) {
            if (result) {
               $('#loadingmessage').show();
		        $.ajax({ //Upload common input
		                url: "masterclient/change_auto_generate",
		                type: "POST",
		                data: {"company_code": company_code},
		                dataType: 'json',
		                success: function (response) {
		                	$('#loadingmessage').hide();
		                	//console.log(response);
		                	toastr.success(response.message, response.title);
		                	window.location.href = base_url + "masterclient/edit/" + response.client_id;
		                	//location.reload();
		                }
		            });
            }
        });
	})
	// window.onbeforeunload = function () {

	// 	if(window.event.clientX >= 1300)
	// 	{
	// 		alert('user is leaving..');
	// 		PageMethods.AbandonSession();
	// 	}
	// 	else
	// 	{
	// 		alert(window.event.clientX);
	// 		alert('the User click a refresh ..');
	// 	}
	// }

	/*$(window).on("beforeunload", function() {
		return "Are you sure? You didn't finish the form!";
	});*/

	if(client_status != "1" && first_time == true)
	{
		$(':input:not([name=from], [name=to], [name=searchRegister], [name=company_code], [id=save])').attr('disabled', true);
		$('select[name="acquried_by"]').attr('disabled', true);
		$('select[name="company_type"]').attr('disabled', true);
		$('select[name="status"]').attr('disabled', false);
		$('select[name="extended_to_175"]').attr('disabled', true);
		$('select[name="extended_to_201"]').attr('disabled', true);
		$('select[name="chairman"]').attr('disabled', true);
		$('select[name="director_signature_1"]').attr('disabled', true);
		$('select[name="director_signature_2"]').attr('disabled', true);
		$('select[id="service"]').attr('disabled', true);
		$('select[id="frequency"]').attr('disabled', true);
		$('select[id="register"]').attr('disabled', false);
		$('select[name="search_position"]').attr('disabled', false);
		$('textarea').attr('disabled', true);
		$('button').attr('disabled', true);
		$('#officers_Add').hide();
		$('#share_capital_Add').hide();
		$('#controller_Add').hide();
		$('#charges_Add').hide();
		$('#guarantee_Add').hide();
	}

	if(access_right_company_info_module == "none")
	{
		$('li[data-information="officer"]').addClass("active");
		$('#w2-officer').addClass("active");
	}
	if(access_right_company_info_module == "none" && access_right_officer_module == "none")
	{
		$('li[data-information="capital"]').addClass("active");
		$('#w2-capital').addClass("active");
	}
	if(access_right_company_info_module == "none" && access_right_officer_module == "none" && access_right_member_module == "none")
	{
		$('li[data-information="controller"]').addClass("active");
		$('#w2-controller').addClass("active");
	}
	if(access_right_company_info_module == "none" && access_right_officer_module == "none" && access_right_member_module == "none" && access_right_controller_module == "none")
	{
		$('li[data-information="charges"]').addClass("active");
		$('#w2-charges').addClass("active");
	}
	if(access_right_company_info_module == "none" && access_right_officer_module == "none" && access_right_member_module == "none" && access_right_controller_module == "none" && access_right_charge_module == "none")
	{
		$('li[data-information="filing"]').addClass("active");
		$('#w2-filing').addClass("active");
	}
	if(access_right_company_info_module == "none" && access_right_officer_module == "none" && access_right_member_module == "none" && access_right_controller_module == "none" && access_right_charge_module == "none" && access_right_filing_module == "none")
	{
		$('li[data-information="register"]').addClass("active");
		$('#w2-register').addClass("active");
	}
	if(access_right_company_info_module == "none" && access_right_officer_module == "none" && access_right_member_module == "none" && access_right_controller_module == "none" && access_right_charge_module == "none" && access_right_filing_module == "none" && access_right_register_module == "none")
	{
		$('li[data-information="setup"]').addClass("active");
		$('#w2-setup').addClass("active");
	}
	
	if(access_right_client_module == "read")
	{
		$(':input:not([name=from], [name=to], [name=searchRegister], [name=company_code])').attr('disabled', true);
		$('select[name="acquried_by"]').attr('disabled', true);
		$('select[name="company_type"]').attr('disabled', true);
		$('select[name="status"]').attr('disabled', true);
		$('select[name="extended_to_175"]').attr('disabled', true);
		$('select[name="extended_to_201"]').attr('disabled', true);
		$('select[name="chairman"]').attr('disabled', true);
		$('select[name="director_signature_1"]').attr('disabled', true);
		$('select[name="director_signature_2"]').attr('disabled', true);
		$('select[id="service"]').attr('disabled', true);
		$('select[id="frequency"]').attr('disabled', true);
		$('select[id="register"]').attr('disabled', false);
		$('select[name="search_position"]').attr('disabled', false);
		$('textarea').attr('disabled', true);
		$('button').attr('disabled', true);
		$('#officers_Add').hide();
		$('#share_capital_Add').hide();
		$('#controller_Add').hide();
		$('#charges_Add').hide();
		$('#guarantee_Add').hide();
		$('#billing_info_Add').hide();

		$('#w2-setup .show_contact_phone').attr('disabled', false);
		$('#w2-setup .show_contact_email').attr('disabled', false);

		$('#save').hide();
		//console.log($('#save'));

	}

	if(access_right_company_info_module == "read")
	{
		$('#w2-companyInfo input').attr('disabled', true);
		$('#w2-companyInfo textarea').attr('disabled', true);
		$('select[name="acquried_by"]').attr('disabled', true);
		$('select[name="company_type"]').attr('disabled', true);
		$('select[name="status"]').attr('disabled', true);

		if($tab_aktif == "companyInfo")
		{
			$("#save").attr('disabled', true);
		}
	}

	if(access_right_officer_module == "read")
	{
		$('#w2-officer input').attr('disabled', true);
		$('#w2-officer button').attr('disabled', true);
		$('#officers_Add').hide();
	}

	if(access_right_member_module == "read")
	{
		$('#w2-capital input').attr('disabled', true);
		$('#w2-capital button').attr('disabled', true);
		$('#w2-capital select').attr('disabled', true);
		$('#share_capital_Add').hide();
		$('#guarantee_Add').hide();
	}

	if(access_right_controller_module == "read")
	{
		$('#w2-controller input').attr('disabled', true);
		$('#w2-controller button').attr('disabled', true);
		$('#controller_Add').hide();
	}

	if(access_right_charge_module == "read")
	{
		$('#w2-charges input').attr('disabled', true);
		$('#w2-charges button').attr('disabled', true);
		$('#charges_Add').hide();
	}

	if(access_right_filing_module == "read")
	{
		$('#w2-filing input').attr('disabled', true);
		$('#w2-filing button').attr('disabled', true);
		$('#w2-filing select').attr('disabled', true);
	}

	if(access_right_setup_module == "read")
	{
		$('#w2-setup input').attr('disabled', true);
		$('#w2-setup button').attr('disabled', true);
		$('#w2-setup select').attr('disabled', true);
		$('#w2-setup textarea').attr('disabled', true);

		$('#w2-setup .show_contact_phone').attr('disabled', false);
		$('#w2-setup .show_contact_email').attr('disabled', false);

		$('#save').attr('disabled', true);
	}

	//var state_director_retiring_checkbox = true;
	//$("[name='hidden_director_retiring_checkbox']").val(1);
	
	

	$(document).ready(function() {
	    $('#loadingClient').hide();

	    $("[name='director_retiring_checkbox']").bootstrapSwitch({
		    //state: state_checkbox,
		    size: 'small',
		    onColor: 'primary',
		    onText: 'YES',
		    offText: 'NO',
		    // Text of the center handle of the switch
		    labelText: '&nbsp',
		    // Width of the left and right sides in pixels
		    handleWidth: '20px',
		    // Width of the center handle in pixels
		    labelWidth: 'auto',
		    baseClass: 'bootstrap-switch',
		    wrapperClass: 'wrapper'


		});
	});

	var registered_address_info = <?php echo json_encode($registered_address_info);?>;
	//console.log(registered_address_info);
	

	$.each(registered_address_info, function(key, val) {
        var option = $('<option />');
        option.attr('data-postal_code', val['postal_code']).attr('data-street_name', val['street_name']).attr('data-building_name', val['building_name']).attr('data-unit_no1', val['unit_no1']).attr('data-unit_no2', val['unit_no2']).attr('value', val['id']).text(val['service_name']);
        if(client != null)
        {
	        if(client['our_service_regis_address_id'] != undefined && val['id'] == client['our_service_regis_address_id'])
	        {
	            option.attr('selected', 'selected');
	        }
	    }
        $("#service_reg_off").append(option);
    });

    $(document).on('change','#service_reg_off',function(e){
	   // var num = $(this).parent().parent().parent().attr("num");
	    //console.log(num);
	    var postal_codeValue = $(this).find(':selected').data('postal_code');
	    var street_nameValue = $(this).find(':selected').data('street_name');
	    var building_nameValue = $(this).find(':selected').data('building_name');
	    var unit_no1Value = $(this).find(':selected').data('unit_no1');
	    var unit_no2Value = $(this).find(':selected').data('unit_no2');

	    document.getElementById("postal_code").value = postal_codeValue;
      	document.getElementById("street_name").value = street_nameValue;
      	document.getElementById("building_name").value = building_nameValue;
      	document.getElementById("unit_no1").value = unit_no1Value;
      	document.getElementById("unit_no2").value = unit_no2Value;

	});
	
	function fillRegisteredAddressInput(cbox) {
		//console.log(cbox);
      if (cbox.checked) {
      	//console.log(cbox);
      	$(".service_reg_off_area").show();
      	// document.getElementById("postal_code").value = registered_address_info[0].postal_code;
      	// document.getElementById("street_name").value = registered_address_info[0].street_name;
      	// document.getElementById("building_name").value = registered_address_info[0].building_name;
      	// document.getElementById("unit_no1").value = registered_address_info[0].unit_no1;
      	// document.getElementById("unit_no2").value = registered_address_info[0].unit_no2;

      	document.getElementById("postal_code").readOnly = true;
      	document.getElementById("street_name").readOnly = true;
      	document.getElementById("building_name").readOnly = true;
      	document.getElementById("unit_no1").readOnly = true;
      	document.getElementById("unit_no2").readOnly = true;

      	$( '#form_postal_code' ).html("");
      	$( '#form_street_name' ).html("");
      }
      else
      {
      	$(".service_reg_off_area").hide();
      	$("#service_reg_off").val(0);

      	document.getElementById("postal_code").value = "";
      	document.getElementById("street_name").value = "";
      	document.getElementById("building_name").value = "";
      	document.getElementById("unit_no1").value = "";
      	document.getElementById("unit_no2").value = "";

      	document.getElementById("postal_code").readOnly = false;
      	document.getElementById("street_name").readOnly = false;
      	document.getElementById("building_name").readOnly = false;
      	document.getElementById("unit_no1").readOnly = false;
      	document.getElementById("unit_no2").readOnly = false;
      }
    }

    if(client_signing_info)
    {
    	if(client_signing_info[0]['show_all'])
    	{
    		cm1.getAllChairman();
    	}

    	if(client_signing_info[0]['director_signature_2'])
    	{
    		cm1.getDirectorSignature2(client_signing_info[0]['director_signature_1']);
    		$(".director_signature_2").removeAttr("disabled");
    	}
    }

    var showCM, showDS1, showDS2;
    if(client_signing_info)
    {
    	showCM = false;
    	showDS1 = false;
    	$(".btnShowAllChairman").prop('value', 'Show Today');
    	$(".btnShowAllDirectorSig1").prop('value', 'Show Today');
    	if(client_signing_info[0]["director_signature_2"] != "0")
    	{
    		showDS2 = false;
    		$(".btnShowAllDirectorSig2").prop('value', 'Show Today');
    	}
    	else
    	{
    		showDS2 = true;
    		$(".btnShowAllDirectorSig2").prop('value', 'Show All');
    		$(".director_signature_2").attr("disabled", "disabled");
    	}
    	
    }
    else
    {
    	showCM = true;
    	showDS1 = true;
    	showDS2 = true;
    	$(".btnShowAllChairman").prop('value', 'Show All');
    	$(".btnShowAllDirectorSig1").prop('value', 'Show All');
    	$(".btnShowAllDirectorSig2").prop('value', 'Show All');
    }

    function showAllChairman(chairmanbox) {
	    var tr = jQuery(chairmanbox).parent().parent();
		//chairmanbox.checked
	    if (showCM) 
	    {
	        tr.find('select[name="chairman"]').html(""); 
        	tr.find('select[name="chairman"]').append($('<option>', {
			    value: '0',
			    text: 'Select Chairman'
			}));
	        cm1.getAllChairman();

	        showCM = false;
	        $(".btnShowAllChairman").prop('value', 'Show Today');
	    }
	    else
	    {
	        tr.find('select[name="chairman"]').html(""); 
        	tr.find('select[name="chairman"]').append($('<option>', {
			    value: '0',
			    text: 'Select Chairman'
			}));
	        cm1.getChairman();

	        showCM = true;
	        $(".btnShowAllChairman").prop('value', 'Show All');
	    }

    	/*$('.form_chairman').addClass("has-error");
        $('.form_chairman').removeClass("has-success");
        $('.form_chairman .help-block').show();*/
	}

	function showAllDirectorSig1(directorsig1box) {
	    var tr = jQuery(directorsig1box).parent().parent();
		//chairmanbox.checked
	    if (showDS1) 
	    {
	        tr.find('select[name="director_signature_1"]').html(""); 
        	tr.find('select[name="director_signature_1"]').append($('<option>', {
			    value: '0',
			    text: 'Select Director Signature 1'
			}));
	        cm1.getDirectorSignature1();

	        showDS1 = false;
	        $(".btnShowAllDirectorSig1").prop('value', 'Show Today');
	    }
	    else
	    {
	        tr.find('select[name="director_signature_1"]').html(""); 
        	tr.find('select[name="director_signature_1"]').append($('<option>', {
			    value: '0',
			    text: 'Select Director Signature 1'
			}));
	        cm1.getTodayDirectorSignature1();

	        showDS1 = true;
	        $(".btnShowAllDirectorSig1").prop('value', 'Show All');
	    }

    	/*$('.form_director_signature_1').addClass("has-error");
        $('.form_director_signature_1').removeClass("has-success");
        $('.form_director_signature_1 .help-block').show();*/
	}

	function showAllDirectorSig2(directorsig2box) {
	    var tr = jQuery(directorsig2box).parent().parent();
	    var ds_1_id = $('select[name="director_signature_1"]').val();
	    $(".director_signature_2").attr("disabled", false);
		//chairmanbox.checked
	    if (showDS2) 
	    {
	        tr.find('select[name="director_signature_2"]').html(""); 
        	tr.find('select[name="director_signature_2"]').append($('<option>', {
			    value: '0',
			    text: 'Select Director Signature 2'
			}));
	        cm1.getDirectorSignature2(ds_1_id);

	        showDS2 = false;
	        $(".btnShowAllDirectorSig2").prop('value', 'Show Today');
	    }
	    else
	    {
	        tr.find('select[name="director_signature_2"]').html(""); 
        	tr.find('select[name="director_signature_2"]').append($('<option>', {
			    value: '0',
			    text: 'Select Director Signature 2'
			}));
	        cm1.getTodayDirectorSignature2(ds_1_id);

	        showDS2 = true;
	        $(".btnShowAllDirectorSig2").prop('value', 'Show All');
	    }

    	/*$('.form_director_signature_2').addClass("has-error");
        $('.form_director_signature_2').removeClass("has-success");
        $('.form_director_signature_2 .help-block').show();*/
	}





    var client_info = <?php echo json_encode($client);?>;
    var Individual = <?php echo json_encode($Individual);?>;
    var Client = <?php echo json_encode($Client);?>;
    //console.log(client_info["registered_address"]);
    if(client_info != null && access_right_company_info_module != "none")
    {
    	if((!Individual && Individual == true) || (!Individual && Individual == null && !Client))
    	{
	    	if(client_info["registered_address"] == 1)
	    	{
	    		$(".service_reg_off_area").show();
	    		document.getElementById("postal_code").readOnly = true;
		      	document.getElementById("street_name").readOnly = true;
		      	document.getElementById("building_name").readOnly = true;
		      	document.getElementById("unit_no1").readOnly = true;
		      	document.getElementById("unit_no2").readOnly = true;
	    	}
	    }
    	/*else
    	{
    		$("#street_name").attr("readonly", true);
			$("#building_name").attr("readonly", true);
    	}*/
    }

    if(<?php echo json_encode($Individual);?> || Client)
	{
		search_register_function();
	}

	var latest_incorporation_date;
	
	/*(function() {
		$('#myTab #li-filing a[href="#w2-filing"]').tab('show');
	})();*/
	if(tab == "filing")
	{
		$('#myTab #li-companyInfo').removeClass("active");
		$('.tab-content #w2-companyInfo').removeClass("active");

		$('#myTab #li-filing').addClass("active");
		$('.tab-content #w2-filing').addClass("active");

		$("#client_footer_button").hide();
		$tab_aktif = "filing";
	}
	else if(tab == "setup")
	{
		$('#myTab #li-companyInfo').removeClass("active");
		$('.tab-content #w2-companyInfo').removeClass("active");

		$('#myTab #li-setup').addClass("active");
		$('.tab-content #w2-setup').addClass("active");
		$tab_aktif = "setup";
	}
	else if(tab == "billing")
	{
		$('#myTab #li-companyInfo').removeClass("active");
		$('.tab-content #w2-companyInfo').removeClass("active");

		$('#myTab #li-billing').addClass("active");
		$('.tab-content #w2-billing').addClass("active");
		$tab_aktif = "billing";
	}

	//$('.nav-tabs #li-filing').tab('show');
	
// $('form#Save_Officer').submit(function(e) {

//     var form = $(this);

//     e.preventDefault();

//     $.ajax({
//         type: "POST",
//         url: "masterclient/add_officer",
//         data: form.serialize(), // <--- THIS IS THE CHANGE
//         dataType: "html",
//         success: function(data){
// 			console.log(data);
// 			if (data =='Duplicate Goverment ID')
// 			{
// 				alert("Goverment ID Duplicate");
// 			} else {
// 				alert(data);
// 				$('#modal_officer').modal('hide');
// 				$html ='<tr><td><label class="ads"></label></td><td style="padding-top:15px;"><h6>'+$("#add_officer_nama").val()+'</h6>';
// 				$html +='<table class="table table-bordered table-striped table-condensed mb-none" >';
// 				$html +='	<thead>';
// 				$html +='		<tr>';
// 				$html +='			<th>Position</th>';
// 				$html +='			<th style="width:2%;">Date of Appointment</th>';
// 				$html +='			<th style="width:2%;">Date of Cesasion</th>';
// 				$html +='		</tr>';
// 				$html +='	</thead>';
// 				$html +='	<tbody id="body_content">';
// 				$html +='		<tr><td><label><input type="checkbox" value="">&nbsp;&nbsp;Director</label></td>';
// 				$html +='			<td><input type="text" data-plugin-datepicker data-date-format="dd/mm/yyyy" data-date-start-date="0d"/></td>';
// 				$html +='			<td><input type="text" data-plugin-datepicker data-date-format="dd/mm/yyyy" data-date-start-date="0d"/></td>';
// 				$html +='		</tr>';
// 				$html +='		<tr>';
// 				$html +='			<td><label><input type="checkbox" value="">&nbsp;&nbsp;CEO</label>';
// 				$html +='			</td>';
// 				$html +='			<td><input type="text" data-plugin-datepicker data-date-format="dd/mm/yyyy" data-date-start-date="0d"/></td>';
// 				$html +='			<td><input type="text" data-plugin-datepicker data-date-format="dd/mm/yyyy" data-date-start-date="0d"/></td>';
// 				$html +='		</tr>';
// 				$html +='		<tr>';
// 				$html +='			<td><label><input type="checkbox" value="">&nbsp;&nbsp;Manager</label></div></td>';
// 				$html +='			<td><input type="text" data-plugin-datepicker data-date-format="dd/mm/yyyy" data-date-start-date="0d"/></td>';
// 				$html +='			<td><input type="text" data-plugin-datepicker data-date-format="dd/mm/yyyy" data-date-start-date="0d"/></td>';
// 				$html +='		</tr>';
// 				$html +='		<tr>';
// 				$html +='			<td><label><input type="checkbox" value="">&nbsp;&nbsp;Secretary</label></div></td>';
// 				$html +='			<td><input type="text" data-plugin-datepicker data-date-format="dd/mm/yyyy" data-date-start-date="0d"/></td>';
// 				$html +='			<td><input type="text" data-plugin-datepicker data-date-format="dd/mm/yyyy" data-date-start-date="0d"/></td>';
// 				$html +='		</tr>';
// 				$html +='		<tr>';
// 				$html +='			<td><label><input type="checkbox" value="">&nbsp;&nbsp;Auditor</label></td>';
// 				$html +='			<td><input type="text" data-plugin-datepicker data-date-format="dd/mm/yyyy" data-date-start-date="0d"/></td>';
// 				$html +='			<td><input type="text" data-plugin-datepicker data-date-format="dd/mm/yyyy" data-date-start-date="0d"/></td>';
// 				$html +='		</tr>';
// 				$html +='		<tr>';
// 				$html +='			<td><label><input type="checkbox" value="">&nbsp;&nbsp;Managing Director</label></td>';
// 				$html +='			<td><input type="text" data-plugin-datepicker data-date-format="dd/mm/yyyy" data-date-start-date="0d"/></td>';
// 				$html +='			<td><input type="text" data-plugin-datepicker data-date-format="dd/mm/yyyy" data-date-start-date="0d"/></td>';
// 				$html +='		</tr>';
// 				$html +='		<tr>';
// 				$html +='			<td><label><input type="checkbox" class="alternatedir" value="1">Alternate Director</label>';
// 				$html +='				<div id="alternatedir1" style="display:none">';
// 				$html +='					<select data-plugin-selectTwo class="form-control populate input-sm">';
// 				$html +='						<option>Varder</option>';
// 				$html +='						<option>Durt</option>';
// 				$html +='					</select>';
// 				$html +='				</div>';
// 				$html +='			</td>';
// 				$html +='			<td><input type="text" data-plugin-datepicker data-date-format="dd/mm/yyyy" data-date-start-date="0d"/></td>';
// 				$html +='			<td><input type="text" data-plugin-datepicker data-date-format="dd/mm/yyyy" data-date-start-date="0d"/></td>';
// 				$html +='		</tr>';
// 				$html +='	</tbody>';
// 				$html +='</table>';
// 				$html +='</td>';
// 				$html +='<td>';
// 				$html +='<a ><i class="fa fa-timer"></i></a>';
// 				$html +='<a  class="delete_officer" data-id="'+data+'"><i class="fa fa-trash" style="font-size:16px;"></i></a>';
// 				$html +='</td>';
// 				$html +='</tr>';
// 				$("#body_officer").append($html);
// 				no_urut("ads");
// 				form.trigger("reset");
// 			}
//             // $('#feed-container').prepend(data);
//         },
//         error: function() { alert("Error posting feed."); }
//    });

// });
		/*$( '#form_client_code' ).html( errors );
		$( '#form_registration_no' ).html( errors );
		$( '#form_company_name' ).html( errors );
		$( '#form_former_name' ).html( errors );
		$( '#form_incorporation_date' ).html( errors );
		$( '#form_company_type' ).html( errors );
		$( '#form_activity1' ).html( errors );
		$( '#form_activity2' ).html( errors );
		$( '#form_postal_code' ).html( errors );*/

		

		$("#client_code").live('change',function(){
			$( '#form_client_code' ).html("");
		});
		$("#acquried_by").live('change',function(){
			$( '#form_acquried_by' ).html("");
		});
		$("#registration_no").live('change',function(){
			$( '#form_registration_no' ).html("");
		});
		$("#edit_company_name").live('change',function(){
			$( '#form_company_name' ).html("");
		});
		$("#former_name").live('change',function(){
			$( '#form_former_name' ).html("");
		});
		$("#date_todolist").live('change',function(){
			$( '#form_incorporation_date' ).html("");
		});
		$("#company_type").live('change',function(){
			$( '#form_company_type' ).html("");
		});
		$("#status").live('change',function(){
			$( '#form_status' ).html("");
		});
		$("#activity1").live('change',function(){
			$( '#form_activity1' ).html("");
		});
		$("#activity2").live('change',function(){
			$( '#form_activity2' ).html("");
		});
		$("#postal_code").live('change',function(){
			$( '#form_postal_code' ).html("");
		});

		$(".check_empty_contact_phone").live('change',function(){
			$( '#form_contact_phone' ).html("");
		});

		$(".check_empty_contact_email").live('change',function(){
			$( '#form_contact_email' ).html("");
		});

		toastr.options = {

		  "positionClass": "toast-bottom-right"

		}

		$('#postal_code').keyup(function(){
	  		if($(this).val().length == 6)
	  		{
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
				    /*myString += "Status.code: " + status.code + "\n";
				    myString += "Status.request: " + status.request + "\n";
				    myString += "Status.name: " + status.name + "\n";*/
				    
				    if (status.code == 200) {         
				      for (var i = 0; i < data.Placemark.length; i++) {
				        var placemark = data.Placemark[i];
				        var status = data.Status[i];
				        //console.log(placemark.AddressDetails.Country.Thoroughfare.ThoroughfareName);
				        $("#street_name").val(placemark.AddressDetails.Country.Thoroughfare.ThoroughfareName);

				        if(placemark.AddressDetails.Country.AddressLine == "undefined")
				        {
				        	$("#building_name").val("");
				        }
				        else
				        {
				        	$("#building_name").val(placemark.AddressDetails.Country.AddressLine);
				        }
				        

				        /*$("#street_name").attr("readonly", true);
				        $("#building_name").attr("readonly", true);*/

				        
				        /*myString += "============================\n";
				        myString += "Placemark.id: " + placemark.id + "\n";
				        myString += "Placemark.address: " + placemark.address + "\n";
				        myString += "Placemark.AddressDetails.Country.CountryName: " + placemark.AddressDetails.Country.CountryName + "\n";
				        myString += "Placemark.AddressDetails.Country.AddressLine: " + placemark.AddressDetails.Country.AddressLine + "\n";
				        myString += "Placemark.AddressDetails.Country.Thoroughfare.ThoroughfareName: " + placemark.AddressDetails.Country.Thoroughfare.ThoroughfareName + "\n";
				        myString += "Placemark.AddressDetails.Country.CountryNameCode: " + placemark.AddressDetails.Country.CountryNameCode + "\n";
				        myString += "Placemark.AddressDetails.Accuracy: " + placemark.AddressDetails.Accuracy + "\n";
				        myString += "Placemark.Point.coordinates: [" + placemark.Point.coordinates[0] + ", " + placemark.Point.coordinates[1] + ", " + placemark.Point.coordinates[2] + "]\n";
				        myString += "============================\n";*/
				      }
				      $( '#form_postal_code' ).html('');
				      $( '#form_street_name' ).html('');
				      //field.val(myString);
				    } else if (status.code == 603) {
				    	$( '#form_postal_code' ).html('<span class="help-block">*No Record Found</span>');
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
			/*else
			{*/
				/*$("#street_name").val("");
				$("#building_name").val("");*/

				/*$("#street_name").attr("readonly", false);
				$("#building_name").attr("readonly", false);*/
			//}
		});

		//$(document).ready(function() {
		    /*disable non active tabs*/
		    $('.nav li').not('.active').addClass('disabled');
			/*to actually disable clicking the bootstrap tab, as noticed in comments by user3067524*/
		    //$('.nav li').not('.active').find('a').removeAttr("data-toggle");

		    

			if(client)
			{
				console.log("client");
				$('.nav li').removeClass('disabled');
	    		/*$("#li-capital").closest('.disabled').removeClass('disabled');
	    		$("#li-charges").closest('.disabled').removeClass('disabled');
	    		$("#li-filing").closest('.disabled').removeClass('disabled');
	    		$("#li-register").closest('.disabled').removeClass('disabled');
	    		$("#li-setup").closest('.disabled').removeClass('disabled');*/
			}
			else
			{
				$('.disabled').click(function (e) {
			        e.preventDefault();
			        //console.log($(this).hasClass("disabled"));
			        if($(this).hasClass("disabled"))
			        {
			        	return false;
			        }
			        else
			        {
			        	return true;
			        }
			        
				});
			}

		//});

		
		


		$(document).on('submit', function (e) {
	        e.preventDefault();

	        var link;
	        if($tab_aktif == "companyInfo")
			{
				link = "masterclient/save";
			}
			else if($tab_aktif == "setup") 
			{
				var $form = $(e.target);

		        // and the FormValidation instance
		        var fv = $form.data('formValidation');
		        // Get the first invalid field
	            var $invalidFields = fv.getInvalidFields().eq(0);
	            // Get the tab that contains the first invalid field
	            var $tabPane     = $invalidFields.parents('.tab-pane');
		        var valid_setup = fv.isValidContainer($tabPane);

		        if(valid_setup == false)
		        {
		        	toastr.error("Please complete all required field", "Error");
		        }

				link = "masterclient/add_setup_info";
			}
			else if($tab_aktif == "billing") 
			{
				var $form = $(e.target);

		        // and the FormValidation instance
		        var fv = $form.data('formValidation');
		        // Get the first invalid field
	            var $invalidFields = fv.getInvalidFields().eq(0);
	            // Get the tab that contains the first invalid field
	            var $tabPane     = $invalidFields.parents('.tab-pane');
		        var valid_setup = fv.isValidContainer($tabPane);

		        if(valid_setup == false)
		        {
		        	toastr.error("Please complete all required field", "Error");
		        }
		    //     $.validator.setDefaults({
      //     ignore: ":hidden:not('select')" // validate all hidden select elements
      // });
		        

		    //     $("#w2-billing form").validate();

		    //     $(".currency").rules("add", { 
						// 		  required:true
						// 		});

		    //     $(".service").each(function (item) {
		    //     	console.log($('.service'));
			   //      $(this).rules("add", {
			   //          required: true
			   //      });
			   //  });

		         //console.log(validator);
		  //       $.validator.addMethod('chosen-required', function (value, element, requiredValue) {
		  //       	console.log(element);
				//     return requiredValue == false || element.value != '';
				// }, $.validator.messages.required);

				link = "masterclient/add_client_billing_info";
			}
			if($tab_aktif == "companyInfo" || valid_setup == true)
			{
				/*$form.find("#to").attr('disabled', false);
				$form.find("#from").attr('disabled', false);*/
				//console.log("valid_setup============"+valid_setup);
				//console.log("tab_aktif============"+link);
				if(valid_setup == true)
				{
					var num = []; 
					$("#body_billing_info").find('div').each(function(e) {
						//console.log($(this).attr("num"));
						//console.log($(this).find("#to"));
						//console.log($(this).find("input#to").prop('disabled'));
						if($(this).find("input#to").prop('disabled'))
						{
							num.push($(this).attr("num"));
						}
						//console.log($(this).find("input #to"));
						//console.log($(this).parent().parent().parent().parent().parent().attr("num"));
				        $(this).find("input#to").removeAttr('disabled');
				        $(this).find("input#from").removeAttr('disabled');
				    });
				    //console.log(num);
				}
				

			   /* var newArray = new Array();
				  for (var i = 0; i < num.length; i++) {
				    if (num[i]) {
				      newArray.push(num[i]);
				    }
				  }

				  console.log(newArray);*/
				  
			    //num.each(function(e) {
					//console.log($(this).attr("num"));
					//console.log($(this).find("#to"));
					//console.log($(this).find("input#to").prop('disabled'));
					/*if($(this).find("input#to").prop('disabled'))
					{
						num.push($(this).attr("num"));
					}*/
					//console.log($(this).find("input #to"));
					//console.log($(this).parent().parent().parent().parent().parent().attr("num"));
			        //$(this).find("input#to").removeAttr('disabled');
			        /*tr.parent().find('input[name="from['+input_num+']"]').attr('disabled', 'disabled');
            		tr.parent().find('input[name="to['+input_num+']"]').attr('disabled', 'disabled');
			    });*/

			    
				var form = $("#w2-"+$tab_aktif+" form");
				//console.log(form);
				if(client)
				{
					if (client["status"] == "1")
					{
						$(".acquried_by").attr('disabled', false);
					}
				}

				if($tab_aktif == "billing")
				{
					var form_data = form.serialize() + '&array_client_billing_info_id=' + JSON.stringify(array_client_billing_info_id);
				}
				else
				{
					var form_data = form.serialize();
				}
				
				$('#loadingmessage').show();
		        $.ajax({ //Upload common input
		                url: link,
		                type: "POST",
		                data: form_data,
		                dataType: 'json',
		                success: function (response,data) {
		                	$('#loadingmessage').hide();
		                	//console.log(response);
		                    if (response.Status === 1) {
		                    	toastr.success(response.message, response.title);
							    //$("#w2-setup").replaceWith($('#w2-setup', $(response)));
							    //$('#w2-setup').html(data);
							    if($tab_aktif == "companyInfo")
		                    	{
								    var errors = '';
		                    		$( '#form_client_code' ).html( errors );
		                    		$( '#form_registration_no' ).html( errors );
		                    		$( '#form_company_name' ).html( errors );
		                    		$( '#form_former_name' ).html( errors );
		                    		$( '#form_incorporation_date' ).html( errors );
		                    		$( '#form_company_type' ).html( errors );
		                    		$( '#form_activity1' ).html( errors );
		                    		$( '#form_activity2' ).html( errors );
		                    		$( '#form_postal_code' ).html( errors );

		                    		$('.nav li').removeClass('disabled');

		                    		/*$('.disabled').click(function (e) {
								        e.preventDefault();
								        console.log($('.disabled'));
								        return true;
									});*/

									billing(response.client_billing["client_billing_data"]);
		                    		/*if(response.client_billing)
		                    		{
		                    			
		                    			if(response.client_billing['check_available_in_client_billing_info'] == 0)
		                    			{
		                    				template = response.client_billing["client_billing_data"];
		                    				billing(response.client_billing["client_billing_data"]);
		                    			}
		                    			else
		                    			{
		                    				template = response.client_billing["template"];
		                    			}
		                    			
		                    		}*/

		                    		if($("select[name='status']").val() != 1)
		                    		{
		                    			location.reload();
		                    		}

		                    		if(response.change_company_name)
		                    		{
		                    			location.reload();
		                    		}

		                    		if(!admin)
		                    		{
		                    			$(".acquried_by").attr('disabled', 'disabled');
		                    		}

		                    		company_type =  $("#company_type").val();
		                    		
		                    	}
		                    	else if($tab_aktif == "setup")
		                    	{
		                    		//window.location.href = base_url + 'masterclient/edit/'+response.client_id+'/setup';
		                    	}
		                    	else if($tab_aktif == "billing")
		                    	{
		                    		//window.location.href = base_url + 'masterclient/edit/'+response.client_id+'/billing';
		                    	}
		                    }
		                    else if (response.Status === 2) {
		                    	toastr.error(response.message, response.title);
		                    }
		                    else if (response.Status === 3) {
		                    	toastr.success(response.message, response.title);
		                    	location.reload();
		                    }
		                    else
		                    {
		                    	//console.log("fail");
		                    	toastr.error(response.message, response.title);
		                    	//console.log(response.error);
		                    	if($tab_aktif == "companyInfo")
		                    	{
			                    	if (response.error["client_code"] != "")
			                    	{
			                    		var errorsClientCode = '<span class="help-block">*' + response.error["client_code"] + '</span>';
			                    		$( '#form_client_code' ).html( errorsClientCode );

			                    	}
			                    	else
			                    	{
			                    		var errorsClientCode = '';
			                    		$( '#form_client_code' ).html( errorsClientCode );
			                    	}
			                    	if (response.error["status"] != "")
			                    	{
			                    		var errorsStatus = '<span class="help-block">*' + response.error["status"] + '</span>';
			                    		$( '#form_status' ).html( errorsStatus );

			                    	}
			                    	else
			                    	{
			                    		var errorsStatus = '';
			                    		$( '#form_status' ).html( errorsStatus );
			                    	}
			                    	if (response.error["acquried_by"] != "")
			                    	{
			                    		var errorsAcquriedBy = '<span class="help-block">*' + response.error["acquried_by"] + '</span>';
			                    		$( '#form_acquried_by' ).html( errorsAcquriedBy );

			                    	}
			                    	else
			                    	{
			                    		var errorsAcquriedBy = '';
			                    		$( '#form_acquried_by' ).html( errorsAcquriedBy );
			                    	}
			                    	if (response.error["registration_no"] != "")
			                    	{
			                    		var errorsRegistrationNo = '<span class="help-block">*' + response.error["registration_no"] + '</span>';
			                    		$( '#form_registration_no' ).html( errorsRegistrationNo );

			                    	}
			                    	else
			                    	{
			                    		var errorsRegistrationNo = '';
			                    		$( '#form_registration_no' ).html( errorsRegistrationNo );
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
			                    	/*if (response.error["former_name"] != "")
			                    	{
			                    		var errorsFormerName = '<span class="help-block">*' + response.error["former_name"] + '</span>';
			                    		$( '#form_former_name' ).html( errorsFormerName );

			                    	}
			                    	else
			                    	{
			                    		var errorsFormerName = '';
			                    		$( '#form_former_name' ).html( errorsFormerName );
			                    	}*/
			                    	if (response.error["incorporation_date"] != "")
			                    	{
			                    		var errorsIncorporationDate = '<span class="help-block">*' + response.error["incorporation_date"] + '</span>';
			                    		$( '#form_incorporation_date' ).html( errorsIncorporationDate );

			                    	}
			                    	else
			                    	{
			                    		var errorsIncorporationDate = '';
			                    		$( '#form_incorporation_date' ).html( errorsIncorporationDate );
			                    	}
			                    	if (response.error["company_type"] != "")
			                    	{
			                    		var errorsCompanyType = '<span class="help-block">' + response.error["company_type"] + '</span>';
			                    		$( '#form_company_type' ).html( errorsCompanyType );

			                    	}
			                    	else
			                    	{
			                    		var errorsCompanyType = '';
			                    		$( '#form_company_type' ).html( errorsCompanyType );
			                    	}
			                    	if (response.error["activity1"] != "")
			                    	{
			                    		var errorsActivity1 = '<span class="help-block">*' + response.error["activity1"] + '</span>';
			                    		$( '#form_activity1' ).html( errorsActivity1 );

			                    	}
			                    	else
			                    	{
			                    		var errorsActivity1 = '';
			                    		$( '#form_activity1' ).html( errorsActivity1 );
			                    	}
			                    	/*if (response.error["activity2"] != "")
			                    	{
			                    		var errorsActivity2 = '<span class="help-block">*' + response.error["activity2"] + '</span>';
			                    		$( '#form_activity2' ).html( errorsActivity2 );

			                    	}
			                    	else
			                    	{
			                    		var errorsActivity2 = '';
			                    		$( '#form_activity2' ).html( errorsActivity2 );
			                    	}*/
			                    	if (response.error["postal_code"] != "")
			                    	{
			                    		var errorsPostalCode = '<span class="help-block">*' + response.error["postal_code"] + '</span>';
			                    		$( '#form_postal_code' ).html( errorsPostalCode );

			                    	}
			                    	else
			                    	{
			                    		var errorsPostalCode = '';
			                    		$( '#form_postal_code' ).html( errorsPostalCode );
			                    	}

			                    	if (response.error["street_name"] != "")
			                    	{
			                    		var errorsStreetName = '<span class="help-block">*' + response.error["street_name"] + '</span>';
			                    		$( '#form_street_name' ).html( errorsStreetName );

			                    	}
			                    	else
			                    	{
			                    		var errorsStreetName = '';
			                    		$( '#form_street_name' ).html( errorsStreetName );
			                    	}
			                    }
			                    else if($tab_aktif == "setup")
			                    {

			                    	if (response.error["contact_phone"] != "")
			                    	{
			                    		var errorsContactPhone = '<span class="help-block">*' + response.error["contact_phone"] + '</span>';
			                    		$( '#form_contact_phone' ).html( errorsContactPhone );

			                    	}
			                    	else
			                    	{
			                    		var errorsContactPhone = '';
			                    		$( '#form_contact_phone' ).html( errorsContactPhone );
			                    	}

			                    	if (response.error["contact_email"] != "")
			                    	{
			                    		var errorsContactEmail = '<span class="help-block">*' + response.error["contact_email"] + '</span>';
			                    		$( '#form_contact_email' ).html( errorsContactEmail );

			                    	}
			                    	else
			                    	{
			                    		var errorsContactEmail = '';
			                    		$( '#form_contact_email' ).html( errorsContactEmail );
			                    	}
			                    	// if (response.error["service"] != "")
                        // 			{
			                     //        var errorsService = '<span class="help-block">*' + response.error["service"] + '</span>';
			                     //        tr.find("DIV#form_service").html( errorsService );

			                     //    }
			                     //    else
			                     //    {
			                     //        var errorsService = ' ';
			                     //        tr.find("DIV#form_service").html( errorsService );
			                     //    }

			                     //    if (response.error["amount"] != "")
			                     //    {
			                     //        var errorsAmount = '<span class="help-block">' + response.error["amount"] + '</span>';
			                     //        tr.find("DIV#form_amount").html( errorsAmount );

			                     //    }
			                     //    else
			                     //    {
			                     //        var errorsAmount = ' ';
			                     //        tr.find("DIV#form_amount").html( errorsAmount );
			                     //    }

			                     //    if (response.error["to"] != "")
			                     //    {
			                     //        var errorsTo = '<span class="help-block">*' + response.error["to"] + '</span>';
			                     //        tr.find("DIV#form_to").html( errorsTo );

			                     //    }
			                     //    else
			                     //    {
			                     //        var errorsTo = ' ';
			                     //        tr.find("DIV#form_to").html( errorsTo );
			                     //    }

			                     //    if (response.error["from"] != "")
			                     //    {
			                     //        var errorsFrom = '<span class="help-block">*' + response.error["from"] + '</span>';
			                     //        tr.find("DIV#form_from").html( errorsFrom );

			                     //    }
			                     //    else
			                     //    {
			                     //        var errorsFrom = ' ';
			                     //        tr.find("DIV#form_from").html( errorsFrom );
			                     //    }

			                     //    if (response.error["frequency"] != "")
			                     //    {
			                     //        var errorsFrequency = '<span class="help-block">*' + response.error["frequency"] + '</span>';
			                     //        tr.find("DIV#form_frequency").html( errorsFrequency );

			                     //    }
			                     //    else
			                     //    {
			                     //        var errorsFrequency = ' ';
			                     //        tr.find("DIV#form_frequency").html( errorsFrequency );
			                     //    }
			                    }
		                    }
		                }
		            });
					if(valid_setup == true)
					{
						for (var i = 0; i < num.length; i++) 
						{
					    	if (num[i]) 
					    	{
						      	$('input[name="from['+num[i]+']"]').attr('disabled', 'disabled');
		            			$('input[name="to['+num[i]+']"]').attr('disabled', 'disabled');
						    }
						}
					}
				}
	    });

		$(document).on('click',".check_stat",function() {
			// $('ul li.active').css('display', 'none');
				//var link;
				//console.log($tab_aktif);
				//console.log($(this).data("information"));
/*
				if($tab_aktif == "companyInfo")
				{
					link = "masterclient/save";
				}
				else if($tab_aktif == "officer") 
				{
					link = "masterclient/add_officer";
				}*/
				/*if($(this).data("information") != $tab_aktif)
				{*/
					/*if (confirm("Save Changes ?"))
					{
						if ($tab_aktif == 'officer')
						{
							alert("Success Add");
						} else {*/
						// $().submit(function (e){
							/*var form = $("#w2-"+$tab_aktif+" form");
							// form.submit();
							//console.log(form.attr('action'));

							$.ajax({
								type: "POST",
								url: link,
								data: form.serialize(), 
								dataType: "html",
								success: function(data){
									//console.log(data);
									if (data =='Error')
									{
										//alert("Error");
									} else {
										//alert("Success Add");
										// form.trigger("reset");
									}
									$('#feed-container').prepend(data);
								},*/
								/*error: function(data) { 
									//console.log(data);
									alert("Error posting feed."); }*/
						   //});
					/*}	*/					
				/*}else {
				}*/
			
				$tab_aktif = $(this).data("information");

				if($tab_aktif == "controller" || $tab_aktif == "capital" || $tab_aktif == "officer" || $tab_aktif == "filing"|| $tab_aktif == "register" || $tab_aktif == "charges" || $tab_aktif == "billing" || $tab_aktif == "setup" || $tab_aktif == "billing")
				{
					$.ajax({
						type: "POST",
						url: "masterclient/check_incorporation_date",
						data: {"company_code": company_code}, // <--- THIS IS THE CHANGE
						dataType: "json",
						async: false,
						success: function(response)
						{
							//console.log("incorporation_date==="+response[0]["incorporation_date"]);
							$array = response[0]["incorporation_date"].split("/");
							$tmp = $array[0];
							$array[0] = $array[1];
							$array[1] = $tmp;
							//unset($tmp);
							$date_2 = $array[0]+"/"+$array[1]+"/"+$array[2];
							//console.log(new Date($date_2));

							latest_incorporation_date = new Date($date_2);

							//officer_change_date(latest_incorporation_date);
							controller_change_date(latest_incorporation_date);
							charge_change_date(latest_incorporation_date);
							register_change_date(latest_incorporation_date);
							setup_change_date(latest_incorporation_date);
							/*date.setDate(date.getDate()-1)
				*/
							//console.log(new Date());
							if($tab_aktif == "filing")
							{
								change_incorporation_date(latest_incorporation_date);
								// if(!filing_data)
								// {
								// 	check_due_date_175();
								// }

								if(company_type == "3" || company_type == "6")
								{
									dispense_agm_button("public");
								}
								else
								{
									dispense_agm_button("private");
								}
								//dispense_agm_button
							}
						}
					});


				}

				if($tab_aktif == "capital")
				{
					/*$("#company_type").val() == "1" || $("#company_type").val() == "2" || $("#company_type").val() == "3" || */
					if(company_type == "4" || company_type == "5" || company_type == "6")
					{
						$("#guarantee").show();
						$("#non-guarantee").hide();
					}
					else if(company_type == "1" || company_type == "2" || company_type == "3")
					{
						$("#non-guarantee").show();
						$("#guarantee").hide();
					}
				}

				if($tab_aktif == "setup")
				{
					$.ajax({
						type: "POST",
						url: "masterclient/get_latest_retire_director",
						data: {"company_code": company_code}, // <--- THIS IS THE CHANGE
						dataType: "json",
						async: false,
						success: function(response)
						{
							$(".tr_director_retiring").remove();
							//console.log(response);
							for(var i = 0; i < response.director_retiring.length; i++)
					    	{
								$b=""; 
						        $b += '<tr class="tr_director_retiring">';
						        $b += '<td>'+(i+1)+'<input type="hidden" class="form-control" name="director_retiring_client_officer_id[]" value="'+response.director_retiring[i]["id"]+'"/></td>';
						        $b += '<td>'+response.director_retiring[i]["identification_no"]+'</td>';
						        $b += '<td>'+response.director_retiring[i]["name"]+'</td>';
						        $b += '<td><input type="checkbox" name="director_retiring_checkbox" '+((response.director_retiring[i]["retiring"] == "1")?'checked':'')+'/><input type="hidden" name="hidden_director_retiring_checkbox[]" value="'+response.director_retiring[i]["retiring"]+'"/></td>';
						        $b += '</tr>';

						        $(".director_retiring_table").append($b);

						        $("[name='director_retiring_checkbox']").bootstrapSwitch({
								    //state: state_checkbox,
								    size: 'small',
								    onColor: 'primary',
								    onText: 'YES',
								    offText: 'NO',
								    // Text of the center handle of the switch
								    labelText: '&nbsp',
								    // Width of the left and right sides in pixels
								    handleWidth: '20px',
								    // Width of the center handle in pixels
								    labelWidth: 'auto',
								    baseClass: 'bootstrap-switch',
								    wrapperClass: 'wrapper'


								});
						    }
						}
					});
					
					//$(document).ready(function() {
						$("[name='director_retiring_checkbox']").bootstrapSwitch({
						    //state: state_checkbox,
						    size: 'small',
						    onColor: 'primary',
						    onText: 'YES',
						    offText: 'NO',
						    // Text of the center handle of the switch
						    labelText: '&nbsp',
						    // Width of the left and right sides in pixels
						    handleWidth: '20px',
						    // Width of the center handle in pixels
						    labelWidth: 'auto',
						    baseClass: 'bootstrap-switch',
						    wrapperClass: 'wrapper'


						});
						//});

					// Triggered on switch state change.
					$("[name='director_retiring_checkbox']").on('switchChange.bootstrapSwitch', function(event, state) {
					    if(state == true)
					    {
					        $(event.target).parent().parent().parent().find("[name='hidden_director_retiring_checkbox[]']").val(1);

					    }
					    else
					    {
					       $(event.target).parent().parent().parent().find("[name='hidden_director_retiring_checkbox[]']").val(0);
					    }
					});

					/*$('#director_retiring_table').DataTable({"paging": false,});
				    $('#setup_director_retiring_table .datatables-header').hide();
				    $('#setup_director_retiring_table .datatables-footer').hide();*/
				}

				if($tab_aktif == "register")
				{
					search_register_function();
				}

				if($tab_aktif == "officer" || $tab_aktif == "capital" || $tab_aktif == "charges" || $tab_aktif == "filing" || $tab_aktif == "register" || $tab_aktif == "controller")
				{
					//console.log($("#client_footer_button"));
					$("#client_footer_button").hide();
				}
				else
				{
					$("#client_footer_button").show();
				}

				if(access_right_company_info_module == "read")
				{

					if($tab_aktif == "companyInfo")
					{
						$("#save").attr('disabled', true);
					}
				}
				else
				{
					if($tab_aktif == "companyInfo")
					{
						$("#save").attr('disabled', false);
					}
				}

				if(access_right_setup_module == "read")
				{

					if($tab_aktif == "setup")
					{
						$("#save").attr('disabled', true);
					}
				}
				else
				{
					if($tab_aktif == "setup")
					{
						$("#save").attr('disabled', false);
					}
				}

		});

		$(document).on('click',"#save",function(e){
			if($tab_aktif == "companyInfo"){
				$.ajax({
					type: "POST",
					url: "masterclient/check_client_data",
					data: $("#w2-companyInfo form").serialize(), // <--- THIS IS THE CHANGE
					dataType: "json",
					async: false,
					success: function(response)
					{
						if(response)
						{
							/*if (confirm('Do you want to submit?')) 
							{
					           $("#w2-"+$tab_aktif+" form").submit();
							} 
							else 
							{
							   return false;
							}*/
							bootbox.confirm("Do you want to submit? Did you want to overwrite previous document?", function (result) {
					            if (result) {
					            	$("#w2-"+$tab_aktif+" form").submit();
					            }
					            else
					            {
					            	return false;
					            }
					        });
						}
						else
						{
							//console.log($tab_aktif);
							$("#w2-"+$tab_aktif+" form").submit();
						}
					}
				});
			}
			else
			{
				$("#w2-"+$tab_aktif+" form").submit();
			}
			
		});
		$(document).on('click',"#save_draft",function(){
			$("#w2-"+$tab_aktif+" form").submit();
		});


</script>
<script>
	$(document).on('ready',function(){
		// $("#adds_officer").on("click",function(){
			// $("#modal_officer").modal('show');
		// });
		/* $("#btn_add_officer").on("click",function(){
			var url="masterclient/add_officer";
			add_officer_form = $("#Save_Officer").serialize();
			// var formData = new FormData(this);
			// console.log(add_officer_form);
			// console.log(formData);
			$.ajax({
				type: "POST",
				url: "<?php echo base_url()?>"+url,
				data: add_officer_form,
				success: function(response) {
					console.log(response);
					
				},
				error: function() {
					alert('system Error, Try Later Again');
				}
			});
			// e.preventDefault();
		}); */
		if(typeof localStorage.getItem('slitems') == "undefined")
		{
		// alert(localStorage.getItem('slitems'));
			// alert($("#edit_company_name").val());
			$("#edit_company_name").val(localStorage.getItem('slitems'));
		}
		$(document).on('click',".delete_officer",function() {
			if(confirm("Delete This Record?"))
			{
				var jqxhr = $.get("<?php echo base_url()?>masterclient/delete_officer/"+$(this).data('id'), function() {
				
				}).done(function(){
					no_urut('ads'); 
					alert("Deleted" );
				}).fail(function() {
					alert( "Error" );
				});
					$(this).closest("tr").remove();
			}
		});
		$(document).on('click',".hapus_baris",function() {
			if(confirm("Delete This Record?"))
			{
				$(this).closest("tr").remove();
			}
		});
		$(document).on('click',".hapus_allotment",function() {
			if(confirm("Delete This Record?"))
			{
				$.get('masterclient/hapus_allotment/'+$(this).data('id'));
				$(this).closest("tr").remove();
			}
		});
		$(document).on('click',".hapus_duabaris",function() {
			if(confirm("Delete This Record?"))
			{
				$(this).closest("tr").next('tr').remove();
				$(this).closest("tr").remove();
			}
		});
		$(document).on('click','.alternatedir', function() {
			if($(this).is(':checked')){
				$("#alternatedir"+$(this).data('id')).show();
			}else{
				$("#alternatedir"+$(this).data('id')).hide();
			}
			// alert($(this).val());
		});
		
		$("#btn_officer_search_person").on('click',function(){
			$("#div_officer_person	").show();
		});
		$byk_issued_share_capital = 3;
			$(document).on('click',"#add_issued_share_capital",function() {
				$byk_issued_share_capital++;
				$a = $("#body_issued_share_capital").html();
				$a += ' <tr>';
				$a += '		<td>'+$byk_issued_share_capital+'</td>';
				$a += '		<td><input type="text" class="form-control numberdes text-right" value=""/></td>';
				$a += '		<td><input type="text" class="form-control number text-right" value=""/></td>';
				$a += '		<td><?php echo form_dropdown_clear('issued_currency_member[]', $cr, '', 'id="currency"  class="form-control" style="width:100%;"'); ?></td>';
				$a += '		<td><?php echo form_dropdown_clear('issued_sharetype_member[]', $bl, '', 'id="slsales"  class="form-control" style="width:100%;"'); ?>';
				
				$a += '		</td>';
				$a += '		<td><a href="#"><i class="fa fa-times"></i></a></td>';
				$a += ' </tr>';
				$("#body_issued_share_capital").html($a);
			});
			$byk_paid_share_capital = 3;
			$(document).on('click',"#paid_issued_share_capital",function() {
				$byk_issued_share_capital++;
				$a = $("#body_paid_issued_share_capital").html();
				$a += ' <tr>';
				$a += '		<td>'+$byk_issued_share_capital+'</td>';
				$a += '		<td><input type="text" class="form-control number text-right" value=""/></td>';
				$a += '		<td><input type="text" class="form-control number text-right" value=""/></td>';
				$a += '		<td><?php echo form_dropdown_clear('paid_currency_member[]', $cr, '', 'id="currency"  class="form-control" style="width:100%;"'); ?></td>';
				$a += '		<td><?php echo form_dropdown_clear('paid_sharetype_member[]', $bl, '', 'id="slsales"  class="form-control" style="width:100%;"'); ?>';
				$a += '		<td><a href="#"><i class="fa fa-times"></i></a></td>';
				$a += ' </tr>';
				$("#body_paid_issued_share_capital").html($a);
			});
			$(document).on('click',"#member_issued_share_capital",function() {
				$byk_member_share_capital++;
				$a = $("#body_member_issued_share_capital").html();
				$a += ' <tr>';
				$a += '		<td rowspan =4>'+$byk_member_share_capital+'</td>';
				$a += '		<td><input type="text" class="form-control input-xs" value=""/></td>';
				$a += '		<td><input type="text" class="form-control" value=""/></td>';
				$a += '		<td><input type="text" class="form-control" value=""/></td>';
				$a += '		<td><?php echo form_dropdown_clear('sharetype_member[]', $bl, '', 'id="slsales"  class="form-control" style="width:100%;"'); ?>';
				$a += '		<td><input type="text" class="form-control number" value=""/></td>';
				$a += '		<td><input type="text" class="form-control number" value=""/></td>';
				$a += '		<td rowspan =4>Certificate</td>';
				$a += '		<td rowspan =4>';
				$a += '			<a href="#"><i class="fa fa-pencil"></i></a>';
				$a += '			<a href="#"><i class="fa fa-share-alt"></i></a>';
				$a += '		</td>';
				$a += '	</tr>';
				$a += '	<tr>';
				$a += '		<td rowspan =3><textarea style="height:100px;"></textarea></td>';
				$a += '		<td><input type="text" class="form-control" value=" "/></td>';
				$a += '		<td><input type="text" class="form-control" value=""/></td>';
				$a += '		<td><?php echo form_dropdown_clear('currency_member[]', $cr, '', 'id="currency"  class="form-control" style="width:100%;"'); ?></td>';
				$a += '		<td><input type="text" class="form-control number" value=""/></td>';
				$a += '		<td><input type="text" class="form-control number" value=""/></td>';
				$a += '	</tr>';
				$a += '	<tr>';
				$a += '		<td>';
				$a += '			<select>';
				$a += '				<option>Singapore</option>';
				$a += '				<option>Singapore P.R</option>';
				$a += '			</select>';
				$a += '		</td>';
				$a += '		<td>Local Phone</td>';
				$a += '		<td><input type="text" class="form-control"  data-plugin-masked-input data-input-mask="(+99) 999-9999" placeholder="(+23) 123-1234"/></td>';
				$a += '		<td></td>';
				$a += '		<td></td>';
				$a += '	</tr>';
				$a += '	<tr>';
				$a += '		<td></td>';
				$a += '		<td>Email</td>';
				$a += '		<td><input type="email" class="form-control" value="+65 1111 2222"/></td>';
				$a += '		<td></td>';
				$a += '		<td></td>';
				$a += '	</tr>';
				$("#body_member_issued_share_capital").html($a);
			});
			
			
			// $("#selectperson").select2({
			  // tags: true
			// });
			
	});

	
			
			$byk_member_capital=1;
			$(document).on('click',"#add_member_capital",function() {
				// alert("A");
				 $a="";
				// $b = $("#body_chargee").html();
				$a += '<tr>';
				$a += '	<td rowspan=2>'+$byk_member_capital+'</td>';
				$a += '		<td><input type="text"  name="nama_member_capital[]" class="form-control input-xs" value=""/></td>';
				$a += '		<td>';
				$a += '		<?php echo form_dropdown_clear('sharetype_member[]', $bl, '', 'id="slsales"  class="form-control" style="width:100%;"'); ?>';
				$a += '	</td>';
				$a += '		<td><input type="text" name="shares_member_capital[]" class="form-control number text-right" value=""/></td>';
				$a += '		<td><input type="text" name="no_share_paid_member_capital[]" class="form-control number text-right" value=""/></td>';
				$a += '		<td>Certificate</td>';
				$a += '		<td rowspan=2><a href="#"><i class="fa fa-times hapus_duabaris"></i></a></td>';
				$a += '		</tr>';
				$a += '		<tr>';
				$a += '		<td><input type="text" name="gid_member_capital[]"  class="form-control" value=""/></td>';
				$a += '		<td><?php echo form_dropdown_clear('currency_member_capital[]', $cr, '', 'id="currency"  class="form-control" style="width:100%;"'); ?>';
				$a += '		</td>';
				$a += '		<td><input type="text" name="amount_share_member_capital[]" class="form-control number text-right" value=""/></td>';
				$a += '		<td><input type="text" name="amount_share_paid_member_capital[]"  nameclass="form-control number text-right" value=""/></td>';
				$a += '		<td><a>SettlePayment</a></td>';
				$a += '		</tr>';
				$("#body_members_capital").prepend($a); 
				$('.datepicker').datepicker({ dateFormat:'yyyy-mm=dd'});
					
				$("input.number").bind({
					keydown: function(e) {
						if (e.shiftKey === true ) {
							if (e.which == 9) {
								return true;
							}
							return false;
						}
						if (e.which > 57) {
							return false;
						}
						if (e.which==32) {
							return false;
						}
						return true;
					}
				});
				$byk_member_capital++;
			});
			$byk_billing =2;
			$(document).on('click',"#billing_add",function() {
				$byk_billing++;
				$a = "";
				$a += '<tr>';
				$a += '	<td class="ads1"></td>';
				$a += '	<td>';
				$a += '		<?php echo form_dropdown_clear('service_name[]', $svc, $ch->chargee_name, 'id="currency"  class="form-control  populate" style="width:100%;"');?>';
				$a += '	</td>';
				$a += '	<td>';
				$a += '		<input type="text" class="form-control" value=""/>';
				$a += '	</td>';
				$a += '	<td>';
				$a += '		<span style="float:left;margin:5px 3px;width:20%;">Start Date</span><input type="text" class="form-control" name="service_start_recurring[]"  style="float:left;width:75%;" data-date-format="dd/mm/yyyy" data-date-start-date="0d" data-plugin-datepicker/>';
				$a += '		<br/>';
				$a += '		<span style="float:left;margin:3px;width:20%;">End Date</span><input type="text" class="form-control" style="float:left;width:75%;" name="service_end_recurring[]" data-date-format="dd/mm/yyyy" data-date-start-date="0d" data-plugin-datepicker>';
				$a += '	</td>';
				$a += '	<td>';
				$a += '		<select data-plugin-selectTwo name="service_frequency[]">';
				$a += '			<optgroup label="Frequency">';
				$a += '				<option value="7">1 Week</option>';
				$a += '				<option value="14">2 Week</option>';
				$a += '				<option value="21">3 Week</option>';
				$a += '				<option value="30">1 Month</option>';
				$a += '				<option value="60">2 Month</option>';
				$a += '				<option value="90">3 Month</option>';
				$a += '				<option value="180">6 Month</option>';
				$a += '				<option value="356">1 Year</option>';
				$a += '			</optgroup>';
				$a += '		</select>';
				$a += '	</td>';
				$a += '	<td>';
				$a += '		<a href="#"><i class="fa fa-trash"></i></a>';
				$a += '	</td>';
				$a += '</tr>';	
				$("#tbody_setup").prepend($a);
				$('.datepicker').datepicker({ dateFormat:'yyyy-mm=dd',yearRange: '1950:2500',});
					
				$("input.number").bind({
					keydown: function(e) {
						if (e.shiftKey === true ) {
							if (e.which == 9) {
								return true;
							}
							return false;
						}
						if (e.which > 57) {
							return false;
						}
						if (e.which==32) {
							return false;
						}
						return true;
					}
				});
				no_urut("ads1");
			});
		$(".director_jgn_sama").each(function(){
			
		});
</script>
