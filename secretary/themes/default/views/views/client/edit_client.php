<?php
	$now = getDate();
	///$this->session->set_userdata('unique_code', $client->unique_code);

	// if ($this->session->userdata('unique_code') && $this->session->userdata('unique_code') != '')
	// {
	// 	$unique_code =$this->session->userdata('unique_code');
	// } else {
	// 	$unique_code = $this->session->userdata('username').'_'.$now[0];
	// 	//$this->session->set_userdata('unique_code', $client->unique_code);
	// }
	// $ndate = $now['mday']."/".$this->sma->addzero($now['mon'],2)."/".$now['year'];

	// $type_of_doc[""] = [];
	// foreach ($typeofdoc as $cs) {
	// 	$type_of_doc[$cs->id] = $cs->typeofdoc;
	// }
	// $doc_category[""] = [];
	// foreach ($doccategory as $cs) {
	// 	$doc_category[$cs->id] = $cs->doccategory;
	// }
	// $svc[""] = [];
	// foreach ($service as $cs) {
	// 	$svc[$cs->id] = $cs->service_name;
	// }

	if ($this->session->userdata('company_code') && $this->session->userdata('company_code') != '')
	{
		$company_code =$this->session->userdata('company_code');
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
														<!-- <span class="badge hidden-xs">1</span> -->
														<b>1.</b> Company Info <?=$this->session->userdata("user_admin_code_id"); ?> a 
													</a>
												</li>
											<?php
												}
											?> 
											<!-- <li class="check_stat hidden">
												<a href="#w2-director" data-toggle="tab" class="text-center">
													<span class="badge hidden-xs">2</span>
													Director
												</a>
											</li> -->
											<?php if ($officer_module != 'none') { ?> 
											<li class="check_stat" id="li-officer" data-information="officer" >
												<a href="#w2-officer" data-toggle="tab" class="text-center ">
													<!-- <span class="badge hidden-xs">2</span> -->
													<b>2.</b> Officers
												</a>
											</li>
											<?php
												}
											?> 
											<?php if ($member_module != 'none') { ?> 
											<li class="check_stat" id="li-capital" data-information="capital">
												<a href="#w2-capital" data-toggle="tab" class="text-center">
													<!-- <span class="badge hidden-xs">3</span> -->
													<b>3.</b> Members
												</a>
											</li>
											<?php
												}
											?> 
											<?php if ($controller_module != 'none') { ?> 
											<li class="check_stat" id="li-controller" data-information="controller">
												<a href="#w2-controller" data-toggle="tab" class="text-center ">
													<!-- <span class="badge hidden-xs">4</span> -->
													<b>4.</b> ROC & ROND
												</a>
											</li>
											<?php
												}
											?> 
											<?php if ($charges_module != 'none') { ?> 
											<li class="check_stat" id="li-charges" data-information="charges">
												<a href="#w2-charges" data-toggle="tab" class="text-center ">
													<!-- <span class="badge hidden-xs">5</span> -->
													<b>5.</b> Charges
												</a>
											</li>
											<?php
												}
											?> 
											<?php if ($filing_module != 'none') { ?> 
											<li class="check_stat" id="li-filing" data-information="filing">
												<a href="#w2-filing" data-toggle="tab" class="text-center">
													<!-- <span class="badge hidden-xs">6</span> -->
													<b>6.</b> Filing
												</a>
											</li>
											<?php
												}
											?> 
											<?php if ($register_module != 'none') { ?>
											<li class="check_stat" id="li-register" data-information="register">
												<a href="#w2-register" data-toggle="tab" class="text-center">
													<!-- <span class="badge hidden-xs">7</span> -->
													<b>7.</b> Register
												</a>
											</li>
											<?php
												}
											?> 
											<?php if ($billing_module != 'none') { ?>
											<li class="check_stat" id="li-billing" data-information="billing">
												<a href="#w2-billing" data-toggle="tab" class="text-center">
													<!-- <span class="badge hidden-xs">8</span> -->
													<b>8.</b> Service Engagement
												</a>
											</li>
											<!-- Service Engagement -->
											<?php
												}
											?> 
											<?php if ($setup_module != 'none') { ?>
											<li class="check_stat" id="li-setup" data-information="setup">
												<a href="#w2-setup" data-toggle="tab" class="text-center">
													<!-- <span class="badge hidden-xs">9</span> -->
													<b>9.</b> Setup
												</a>
											</li>
											<?php
												}
											?> 
											<li class="dropdown check_stat" id="li-others" data-information="others">
									            <a class="dropdown-toggle" data-toggle="dropdown" href="#"><b>10.</b>Others<b class="caret"></b></a>
									            <ul class="dropdown-menu">
									                <li><a href="#services_list" role="tab" data-toggle="tab">Services List</a></li>
									                <li><a href="#letter_confirmation_to_auditor" role="tab" data-toggle="tab">Letter of Confirmation to Auditor</a></li>
									                <li><a href="#company_document" role="tab" data-toggle="tab">Company Document</a></li>
									                <!-- <li><a href="#bootstab" role="tab" data-toggle="tab">Bootstrap</a></li>
									                <li><a href="#htmltab" role="tab" data-toggle="tab">HTML</a></li> -->
									            </ul>
										  	</li>  
											<!-- <li class="check_stat" id="li-other" data-information="other" style="display: none">
												<a href="#w2-other" data-toggle="tab" class="text-center">
													<span class="badge hidden-xs">10</span>
													Others
												</a>
											</li> -->
										<?php
											}
										?> 
										<?php if ($Individual || $Client) {?>
											<li class="active check_stat" id="li-register" data-information="register">
												<a href="#w2-register" data-toggle="tab" class="text-center">
													<!-- <span class="badge hidden-xs">1</span> -->
													<b>1.</b> Register
												</a>
											</li>
											<li class="check_stat" id="li-setup" data-information="setup">
												<a href="#w2-setup" data-toggle="tab" class="text-center">
													<!-- <span class="badge hidden-xs">2</span> -->
													<b>2.</b> Setup
												</a>
											</li>
											<li class="check_stat" id="li-services_list" data-information="services_list">
												<a href="#services_list" data-toggle="tab" class="text-center">
													<!-- <span class="badge hidden-xs">2</span> -->
													<b>3.</b> Services List
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
												<?php echo form_open_multipart('', array('id' => 'upload_company_info', 'enctype' => "multipart/form-data")); ?>
													<div class="hidden"><input type="text" class="form-control" name="company_code" value="<?=$company_code?>"/></div>
													<input type="hidden" class="form-control latest_client_id" name="latest_client_id" value="<?=isset($client->id) ? $client->id : ''?>"/>
													<span style="font-size: 2.4rem;padding: 0; margin: 7px 0 4px 0;">Company Profile</span>
													<div class="form-group" style="margin-top: 20px;">
														<label class="col-sm-4 control-label" for="w2-username">Client Code: </label>
														<div class="col-sm-4 client_code_div">
															<input type="text" style="text-transform:uppercase;" class="form-control" maxlength="20" id="client_code" name="client_code" value="<?=isset($client->client_code) ? $client->client_code : ''?>" >
															<span data-toggle="tooltip" data-html="true" data-trigger="hover" data-original-title="System will auto detect the last client code and suggest the new client code after user fill in the company name." class="fa fa-info-circle errspan"></span>
															<div id="form_client_code"></div>
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
															<input type="text" style="text-transform:uppercase" class="form-control" id="registration_no" name="registration_no" value="<?=isset($client->registration_no) ? $client->registration_no : ''?>" >
															<div id="form_registration_no"></div>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-4 control-label" for="w2-username">Company Name: </label>
														<div class="col-sm-8">
															<input type="text" style="text-transform:uppercase" class="form-control" id="edit_company_name" name="company_name"  value="<?=isset($client->company_name) ? $client->company_name : ''?>">
															<div id="form_company_name"></div>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-4 control-label">w.e.f: </label>
														<div class="col-sm-8">
															<div class="input-group" style="width: 200px;">
																<span class="input-group-addon">
																	<i class="far fa-calendar-alt"></i>
																</span>
																<input type="text" class="form-control valid company_info_date" id="wef_date" name="change_name_effective_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" required="" value="<?=isset($client->change_name_effective_date) ? $client->change_name_effective_date : ''?>" placeholder="DD/MM/YYYY">
																
															</div>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-4 control-label" for="w2-username">Former Name (if any): </label>
														<div class="col-sm-8">
															<textarea class="form-control" id="former_name" name="former_name" /><?=isset($client->former_name) ? $client->former_name : ''?></textarea>
															<div id="form_former_name"></div>
														</div>
													</div>

													<div class="form-group">
														<label class="col-sm-4 control-label" for="w2-username">Incorporation Date: </label>
														<div class="col-sm-8">
															<div class="input-group" style="width: 200px;">
																<span class="input-group-addon">
																	<i class="far fa-calendar-alt"></i>
																</span>
																<input type="text" class="form-control valid company_info_date" id="date_todolist" name="incorporation_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" required="" value="<?=isset($client->incorporation_date) ? $client->incorporation_date : ''?>" placeholder="DD/MM/YYYY">
																
															</div>
															<div id="form_incorporation_date"></div>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-4 control-label" for="w2-username">Company Type: </label>
														<div class="col-sm-8">
															<select id="company_type" class="form-control company_type" style="text-align:right; width: 400px;" name="company_type">
											                    <option value="0">Select Company Type</option>
											                </select>
											                <div id="form_company_type"></div>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-4 control-label" for="w2-username">Status: </label>
														<div class="col-sm-8">
															
															<select id="status" class="form-control status" style="text-align:right;" name="status">
											                    <option value="0">Select Status</option>
											                </select>
											                <div id="form_status"></div>
																
														</div>
													</div>
													<span style="font-size: 2.4rem;padding: 0; margin: 7px 0px 4px 0;">Principal Activities</span>
													<div class="form-group" style="margin-top: 20px">
														<label class="col-sm-4 control-label" for="w2-username">Activity 1: </label>
														<div class="col-sm-8 div_autocomplete">
															<input type="text" style="text-transform:uppercase" class="form-control" class="activity1" id="activity1" name="activity1" value="<?=isset($client->activity1) ? $client->activity1 : ''?>" >
															<div id="form_activity1"></div>
														</div>
														
													</div>

													<div class="form-group" style="margin-top: 20px">
														<label class="col-sm-4 control-label" for="w2-username">Description 1: </label>
														<div class="col-sm-8">
															<textarea rows="4" cols="50" style="text-transform:uppercase" class="form-control" id="description1" name="description1"><?=isset($client->description1) ? $client->description1 : ''?>
															</textarea>
														</div>
													</div>

													<div class="form-group">
														<label class="col-sm-4 control-label" for="w2-username">Activity 2: </label>
														<div class="col-sm-8 div_autocomplete">
															<input type="text" style="text-transform:uppercase" class="form-control" id="activity2" name="activity2" value="<?=isset($client->activity2) ? $client->activity2 : ''?>" >
															<div id="form_activity2"></div>
														</div>
														
													</div>

													<div class="form-group" style="margin-top: 20px">
														<label class="col-sm-4 control-label" for="w2-username">Description 2: </label>
														<div class="col-sm-8">
															<textarea rows="4" cols="50" style="text-transform:uppercase" class="form-control" id="description2" name="description2"><?=isset($client->description2) ? $client->description2 : ''?>
															</textarea>
														</div>
														
													</div>
													<span style="font-size: 2.4rem;padding: 0; margin: 7px 0px 4px 0;">Registered Office Address</span>
													<div class="form-group div_local" style="margin-top: 20px">
														<label class="col-xs-4 control-label" for="w2-username">Use Our Registered Address: </label>
														<div class="col-xs-1">
															<input type="checkbox" class="" id="" name="use_registered_address" <?=isset($client->registered_address)?(($client->registered_address)?'checked':''):'';?> onclick="fillRegisteredAddressInput(this);">
														</div>
														<div class="col-xs-4 service_reg_off_area" style="display: none;">
															<select id="service_reg_off" class="form-control service_reg_off" style="text-align:right;" name="service_reg_off">
											                    <option value="0">Select Service Name</option>
											                </select>
														</div>
													</div>
													<div class="form-group div_local">
														<label class="col-sm-4 control-label" for="w2-username">Registered Office Address: </label>
														<div class="col-sm-8">
															<div style="width: 100%;">
																<div style="width: 15%;float:left;margin-right: 20px;">
																	<label>Postal Code :</label>
																</div>
																<div style="width: 65%;float:left;margin-bottom:5px;">
																	<div class="" style="width: 20%;" >
																		<input type="text" style="text-transform:uppercase" class="form-control" id="postal_code" name="postal_code" value="<?=isset($client->postal_code) ? $client->postal_code : ''?>" maxlength="6">
																	</div>
																	<div id="form_postal_code"></div>
																</div>
															</div>
															
														</div>
														
													</div>
													<div class="form-group div_local">
														<label class="col-sm-4 control-label" for="w2-username"></label>
														<div class="col-sm-8">
															<div style="width: 100%;">
																<div style="width: 15%;float:left;margin-right: 20px;">
																	<label>Street Name :</label>
																</div>
																<div style="width: 71%;float:left;margin-bottom:5px;">
																	<div class="" style="width: 100%;" >
																		<input type="text" style="text-transform:uppercase" class="form-control" id="street_name" name="street_name" value="<?=isset($client->street_name) ? $client->street_name : ''?>">
																	</div>
																	<div id="form_street_name"></div>
												
																</div>
															</div>

														</div>
													</div>
													<div class="form-group div_local">
														<label class="col-sm-4 control-label" for="w2-username"></label>
														<div class="col-sm-8">
															<div style="width: 100%;">
																<div style="width: 15%;float:left;margin-right: 20px;">
																	<label>Building Name :</label>
																</div>
																<div style="width: 71%;float:left;margin-bottom:5px;">
																	<div class="" style="width: 100%;" >
																		<input type="text" style="text-transform:uppercase" class="form-control" id="building_name" name="building_name" value="<?=isset($client->building_name) ? $client->building_name : '' ?>">
																	</div>
																	<div id="form_street_name"></div>
												
																</div>
															</div>
														</div>
													</div>
													<div class="form-group div_local">
														<label class="col-sm-4 control-label" for="w2-username"></label>
														<div class="col-sm-8">
															<label style="width: 15%;float:left;margin-right: 20px;">Unit No :</label>
															<input style="width: 8%; float: left; margin-right: 10px; text-transform:uppercase;" type="text" class="form-control" id="unit_no1" name="unit_no1" value="<?=isset($client->unit_no1) ? $client->unit_no1 : ''?>" maxlength="3">
															<label style="float: left; margin-right: 10px;" >-</label>
															<input style="width: 14%; text-transform:uppercase;" type="text" class="form-control" id="unit_no2" name="unit_no2" value="<?=isset($client->unit_no2) ? $client->unit_no2 : ''?>" maxlength="10">
														</div>
													</div>
													<div class="form-group div_foreign hidden" style="margin-top: 20px">
														<label class="col-xs-4 control-label" for="w2-username">Use Our Registered Address: </label>

														<div class="col-xs-1">
															<input type="checkbox" class="" id="" name="use_registered_address" <?=isset($client->registered_address)?(($client->registered_address)?'checked':''):'';?> onclick="fillForeignRegisteredAddressInput(this);">
														</div>
														<div class="col-xs-4 service_foreign_reg_off_area" style="display: none;">
															<select id="service_foreign_reg_off" class="form-control service_foreign_reg_off" style="text-align:right;" name="service_reg_off">
											                    <option value="0">Select Service Name</option>
											                </select>
														</div>
														
													</div>
													<div class="form-group div_foreign hidden">
														<label class="col-sm-4 control-label" for="w2-foreign_add_1">Foreign Address: </label>
														<div class="col-sm-8">
															<div style="width: 100%;">
																
																<div style="width: 85%;float:left;margin-bottom:5px;">
																	<div class="" >
																		<input type="text" style="text-transform:uppercase" class="form-control" id="foreign_add_1" name="foreign_add_1" value="<?=isset($client->foreign_add_1) ? $client->foreign_add_1 : '' ?>">
																	</div>
																	<div id="form_foreign_add_1"></div>
																</div>
															</div>
														</div>
													</div>
													<div class="form-group div_foreign hidden">
														<label class="col-sm-4 control-label" for="w2-foreign_add_2"></label>
														<div class="col-sm-8">
															<div style="width: 100%;">
																<div style="width: 85%;float:left;margin-bottom:5px;">
																	<div class="" style="width: 100%;" >
																		<input type="text" style="text-transform:uppercase" class="form-control" id="foreign_add_2" name="foreign_add_2" value="<?=isset($client->foreign_add_2) ? $client->foreign_add_2 : ''?>">
																	</div>
																	<div id="form_foreign_add_2"></div>
																</div>
															</div>
														</div>
													</div>
													<div class="form-group div_foreign hidden">
														<label class="col-sm-4 control-label" for="w2-foreign_add_3"></label>
														<div class="col-sm-8">
															<div style="width: 100%;">
																<div style="width: 85%;float:left;margin-bottom:5px;">
																	<div class="" style="width: 100%;" >
																		<input type="text" style="text-transform:uppercase" class="form-control" id="foreign_add_3" name="foreign_add_3" value="<?=isset($client->foreign_add_3) ? $client->foreign_add_3 : ''?>">
																	</div>
																	<div id="form_foreign_add_3"></div>
												
																</div>
															</div>
														</div>
													</div>
													<div class="form-group div_foreign hidden">
														<label class="col-sm-4 control-label" for="w2-use_foreign_add_as_billing_add">Use Foreign Address as Billing Address: </label>
														<div class="col-sm-8">
															<input type="checkbox" class="" id="use_foreign_add_as_billing_add" name="use_foreign_add_as_billing_add" <?=isset($client->use_foreign_add_as_billing_add)?(($client->use_foreign_add_as_billing_add)?'checked':''):'checked';?> disabled/>
														</div>
													</div>
													<!-- <div class="form-group hidden">
														<label class="col-sm-4 control-label" for="w2-username">Listed Company: </label>
														<div class="col-sm-8">
															<input type="checkbox" class="" id="listedcompany" name="listedcompany" <?=$client->listed_company?'checked':'';?> />
														</div>
													</div> -->
												<?= form_close(); ?>
											</div>
											<?php
												}
											?>
											<?php if ($officer_module != 'none') { ?>
											<div id="w2-officer" class="tab-pane">
												<div style="margin-bottom: 23px">
													<form id="filter_position">
														<span style="font-size: 2.4rem;padding: 0; margin: 7px 0 4px 0;">Officers</span>

													<div class="hidden"><input type="text" class="form-control" name="company_code" value="<?=$company_code?>"/></div>
													<select class="form-control" id="search_position" name="search_position" style="float:right; width: 100px;">
														<option value="all" <?=isset($_POST['search_position'])=='all'?'selected':'';?>>All</option>
														<option value="director" <?=isset($_POST['search_position'])=='director'?'selected':'';?>>Director</option>
														<option value="ceo" <?=isset($_POST['search_position'])=='ceo'?'selected':'';?>>CEO</option>
														<option value="manager" <?=isset($_POST['search_position'])=='manager'?'selected':'';?>>Manager</option>
														<option value="secretary" <?=isset($_POST['search_position'])=='secretary'?'selected':'';?>>Secretary</option>
														<option value="auditor" <?=isset($_POST['search_position'])=='auditor'?'selected':'';?>>Auditor</option>
														<option value="managing_director" <?=isset($_POST['search_position'])=='managing_director'?'selected':'';?>>Managing Director</option>
														<option value="alternate_director" <?=isset($_POST['search_position'])=='alternate_director'?'selected':'';?>>Alternate Director</option>
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
											</div>
											<?php
												}
											?>
											<?php if ($controller_module != 'none') { ?>
											<div id="w2-controller" class="tab-pane">
												<button type="button" class="controller-collapsible"><span style="font-size: 2.4rem;">Register of Controller</span></button>
												<div id="controller_info" class="controller-content">
													<div style="padding-top: 18px; height: 65px;">
														<div style="font-size: 1.4rem;float:left;padding-top:5px; padding-right: 5px; height: 30px;"><span>Filter: </span></div>
														<select class="form-control" id="search_filter_categoryposition" name="search_filter_categoryposition" style="float:left; width: 100px;">
															<option value="all" <?=isset($_POST['search_filter_categoryposition'])=='all'?'selected':'';?>>All</option>
															<option value="individual" <?=isset($_POST['search_filter_categoryposition'])=='individual'?'selected':'';?>>Individual</option>
															<option value="corporate" <?=isset($_POST['search_filter_categoryposition'])=='corporate'?'selected':'';?>>Corporate</option>
														</select>

														<a href="javascript: void(0);" class="btn btn-primary" id="create_controller" class="create_controller" style="float: right;">Create</a>
														<a href="javascript: void(0);" class="btn btn-primary" id="refresh_controller" class="refresh_controller" style="float: right; margin-right: 10px;">Refresh</a>
													</div>
													<div style="padding-bottom: 18px;">
														<table style="width: 100%;" class="table table-bordered table-striped mb-none" id="controller_table">
											                <thead>
											                	<!-- <tr>
													                <th style="text-align: center">ID/UEN</th>
													                <th style="text-align: center">Date of Birth/Date of Incorporation</th>
													                <th rowspan="2" style="text-align: center">Address</th>
													                <th style="text-align: center">Date of registration</th>
													                <th style="text-align: center">Confirmation received date</th> 
													                <th rowspan="2" style="text-align: center">Date of cessation</th>
													                <th rowspan="2"></th>
												                </tr>
												                <tr>
													                <th style="text-align: center">Name</th>
													                <th style="text-align: center">Nationality/Country of Incorporation</th>
													                <th style="text-align: center">Date of notice</th> 
													                <th style="text-align: center">Date of entry</th>
												                </tr> -->
												                <tr>
													                <th style="text-align: center; width: 150px !important;">Notice sent</th>
													                <th style="text-align: center; width: 150px !important;">Confirmation Received</th>
													                <th style="text-align: center; width: 150px !important;">Date of entry/update</th>
													                <th style="text-align: center">Controller Particulars</th>
													                <th style="text-align: center; width: 200px !important;">Supporting Docs</th>
													                <th style="width: 100px !important;"></th>
												                </tr>
												            </thead>
												            <tbody id="table_body_controller">
															</tbody>
										                </table>

														<!-- <table class="table table-bordered table-striped table-condensed mb-none" id="controller_table">
															<thead>
																<div class="tr">
																	<div class="th" id="id_controller_header" style="text-align: center;width:140px;">ID/UEN</div>
																	<div class="th" id="id_date_of_birth" style="text-align: center;width:140px;">Date of Birth/Date of Incorporation</div>
																	<div class="th rowspan" style="border-bottom:none;"></div>
																	<div class="th rowspan" id="id_date_of_registration" style="text-align: center;border-bottom:none;">Date of registration</div>
																	<div class="th rowspan" id="id_confirmation_received_date" style="text-align: center;border-bottom:none;">Confirmation received date</div>
																	<div class="th rowspan" id="id_date_of_cessation" style="text-align: center;border-bottom:none;">Date of cessation</div>
																	<a href="javascript: void(0);" class="th rowspan" style="color: #D9A200;width:170px;border-bottom:none; outline: none !important;text-decoration: none;"><span id="controller_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Click to Add Controller" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add Controller</span></a>
																</div>
																<div class="tr">
																	<div class="th" id="id_controller_name" style="text-align: center;width:140px;">Name</div>
																	<div class="th" id="id_nationality" style="text-align: center;width:140px;">Nationality/Country of Incorporation</div>
																	<div class="th empty" id="id_address" style="text-align: center;width:170px;">Address</div>
																	<div class="th rowspan" id="id_date_of_notice" style="text-align: center;">Date of notice</div>
																	<div class="th rowspan" id="id_date_of_entry" style="text-align: center;">Date of entry</div>
																	<div class="th" style="text-align: center;width:200px;border-top:none;"></div>
																	<div class="th empty"></div>
																</div class="tr">
															</thead>
															<div class="tbody" id="body_controller">
															</div>
														</table> -->
													</div>
												</div>
												<!-- --------------ND---------------------- -->
												<button type="button" class="controller-collapsible"><span style="font-size: 2.4rem;">Register of Nominee Director</span></button>
												<div id="controller_info" class="controller-content">
													<div style="padding-top: 18px; height: 65px;">
														<a href="javascript: void(0);" class="btn btn-primary" id="create_nominee_director" class="create_nominee_director" style="float: right;">Create</a>
														<a href="javascript: void(0);" class="btn btn-primary" id="refresh_nominee_director" class="refresh_nominee_director" style="float: right; margin-right: 10px;">Refresh</a>
													</div>
													<div style="padding-bottom: 18px;">
														<table style="width: 100%;" class="table table-bordered table-striped mb-none nominee_director_table display nowrap" id="nominee_director_table">
											                <thead>
											                	<tr>
													                <th style="text-align: center; width: 150px !important;">Date of entry/update</th>
													                <th style="text-align: center; width: 200px !important;">Name of Nominee Director</th>
													                <th style="text-align: center">Particulars of nominator</th>
													                <th style="text-align: center; width: 200px !important;">Supporting Docs</th>
													                <th style="width: 100px !important;"></th>
												                </tr>
												            </thead>
												            <tbody id="table_body_nominee_director">
															</tbody>
										                </table>
										            </div>
												</div>
												<!-- --------------End ND---------------------- -->

												<!-- <div style="margin-bottom: 23px">
													<span style="font-size: 2.4rem;padding: 0; margin: 7px 0 4px 0;">Controller</span>

													<a href="javascript: void(0);" class="btn btn-primary" id="refresh_controller" class="refresh_controller" style="float: right;">Refresh</a>
												</div>
												<table class="table table-bordered table-striped table-condensed mb-none" id="controller_table">
													<thead>
														<div class="tr">
															<div class="th" id="id_controller_header" style="text-align: center;width:140px;">ID/UEN</div>
															<div class="th" id="id_date_of_birth" style="text-align: center;width:140px;">Date of Birth/Date of Incorporation</div>
															<div class="th rowspan" style="border-bottom:none;"></div>
															<div class="th rowspan" id="id_date_of_registration" style="text-align: center;border-bottom:none;">Date of registration</div>
															
															<div class="th rowspan" id="id_confirmation_received_date" style="text-align: center;border-bottom:none;">Confirmation received date</div>
															
															<div class="th rowspan" id="id_date_of_cessation" style="text-align: center;border-bottom:none;">Date of cessation</div>
															<a href="javascript: void(0);" class="th rowspan" style="color: #D9A200;width:170px;border-bottom:none; outline: none !important;text-decoration: none;"><span id="controller_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Click to Add Controller" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add Controller</span></a>
														</div>
														<div class="tr">
															
															<div class="th" id="id_controller_name" style="text-align: center;width:140px;">Name</div>
															<div class="th" id="id_nationality" style="text-align: center;width:140px;">Nationality/Country of Incorporation</div>
															<div class="th empty" id="id_address" style="text-align: center;width:170px;">Address</div>
															<div class="th rowspan" id="id_date_of_notice" style="text-align: center;">Date of notice</div>
															<div class="th rowspan" id="id_date_of_entry" style="text-align: center;">Date of entry</div>
															<div class="th" style="text-align: center;width:200px;border-top:none;"></div>
															<div class="th empty"></div>
														</div class="tr">
													</thead>
													<div class="tbody" id="body_controller">
													</div>
												</table> -->
											</div>
											<?php
												}
											?>
											<?php if ($member_module != 'none') { ?>
											<!-- members -->
											<div id="w2-capital" class="tab-pane">
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
													<h3 style="margin-top: 15px;">Members</h3>
													<div style="padding: 5px 0px;">
														<a href="<?= base_url();?>masterclient/view_allotment/<?=$company_code?>" class="btn btn-primary" target="_blank">Allotment</a>
														<a href="<?= base_url();?>masterclient/view_transfer/<?=$company_code?>" class="btn btn-primary" target="_blank">Transfer</a>

														<a href="javascript: void(0);" class="btn btn-primary" id="refresh" class="refresh" style="float: right;">Refresh</a>
													</div>
													<table class="table table-bordered table-striped table-condensed mb-none" >
														<thead>
															<tr>
																<th rowspan=2 style="text-align: center">No</th>
																<th style="text-align: center; width: 150px;">ID</th>
																<th style="text-align: center">Class</th>
																<th style="text-align: center">Number of Shares Issued</th>
																<th style="text-align: center">Number of Shares Paid Up</th>
															</tr>
															<tr>
																<th style="text-align: center; width: 150px;">Name</th>
																<th style="text-align: center">Currency</th>
																<th style="text-align: center">Amount of Shares Issued</th>
																<th style="text-align: center">Amount of Shares Paid Up</th>
															</tr>
														</thead>
														<tbody id="body_members_capital">
															<?php
																$i = 1;
																if($member != false)
																{
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
																}
															?>
														</tbody>
													</table>
													<h3 style="margin-top: 15px;">Certificate</h3>
													<table class="table table-bordered table-striped table-condensed mb-none" id="datatable_certificate">
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
																if ($member_certificate != false)
																{
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
																}
															?>
														</tbody>
													</table>
												</div>
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
													<div class="tbody" id="body_charges">
													</div>
												</table>
											</div>
											<?php
												}
											?>
											
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
													<div class="hidden"><input type="text" class="form-control company_code" name="company_code" value="<?=$company_code?>"/></div>

													<div class="form-group">
														<div class="col-sm-6">
															<h3>Service Engagement Information</h3>
														</div>
														<div class="col-sm-6">
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
													<div class="hidden"><input type="text" class="form-control filing_id" name="filing_id" value=""/></div>
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
																			<input type="text" class="form-control year_end_date" id="year_end_date" name="year_end" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="dd MMMM yyyy ">
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
														<label class="col-sm-3" for="w2-DS1">Financial Year Period:</label>
														<div class="col-sm-9">
															<div class="input-bar">
																<div class="input-bar-item">
														            <div class="input-daterange input-group" data-plugin-datepicker data-date-format="dd/mm/yyyy">
																		<span class="input-group-addon">
																			<i class="far fa-calendar-alt"></i>
																		</span>
																		<input type="text" class="form-control filing_financial_year_period1" id="filing_financial_year_period1" name="filing_financial_year_period1" value="" placeholder="dd MMMM yyyy" data-date-format="dd/mm/yyyy">
																		<span class="input-group-addon">to</span>
																		<input type="text" class="form-control filing_financial_year_period2" id="filing_financial_year_period2" name="filing_financial_year_period2" value="" placeholder="dd MMMM yyyy" data-date-format="dd/mm/yyyy">
																	</div>
																</div>
															</div>
														</div>
													</div>
													<div class="form-group row_colunm_date_175">
														<label class="col-sm-3" for="w2-DS1">Due Date AGM (S.175):</label>
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
														<label class="col-sm-3" for="w2-DS2">Due Date New AGM (S.175):</label>
														
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
														<label class="col-sm-3" for="w2-DS3">Due Date AR (S.197):</label>
														
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

																			<input type="text" class="form-control" id="agm_date" name="agm" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="dd MMMM yyyy">
																		</div>
																		<div id="validate_year_end_date"></div>
															        </div>
															      <!-- </form> -->
															    </div>
															    <div class="input-bar-item input-bar-item-btn">
															      <button type="button" class="btn btn-primary dispense_agm_button" onclick="dispense_agm(this)" style="display: none">Dispense AGM</button>
															    </div>
															</div>
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
																	<th style="text-align: center">Financial Year Cycle</th>
																	<th style="text-align: center">Financial Year Period</th>
																	<th style="text-align: center">Due Date AGM (S.175)</th> 
																	<th style="text-align: center">Due Date New AGM (S.175)</th> 
																	<th style="text-align: center">Due Date AR (S.197)</th>
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
													<div class="hidden"><input type="text" class="form-control eci_id" name="eci_id" value=""/></div>

													
													
													<div class="form-group" style="margin-top: 20px; margin-bottom: 20px;">
														<label class="col-xs-3" for="w2-show_all">ECI Tax Period: </label>
														<div class="col-sm-9 form-inline">
															<div class="input-bar">
															    <div class="input-bar-item">
														            <div class="input-group" style="width: 200px;">
																		<span class="input-group-addon">
																			<i class="far fa-calendar-alt"></i>
																		</span>
																		<input type="text" class="form-control eci_tax_period" id="eci_tax_period" name="eci_tax_period" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="dd MMMM yyyy ">
																	</div>
																	<div id="validate_eci_tax_period"></div>
															    </div>
															</div>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-3" for="w2-DS1">Next ECI Filing Due Date:</label>
														<div class="col-sm-3">
															<input type="text" name="next_eci_filing_due_date" class="form-control" value="" id="next_eci_filing_due_date" readonly="true" style="width: 200px;"/>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-3" for="w2-DS1">ECI Filing Date: </label>
															<div class="col-sm-3">
																<div class="input-group" style="width: 200px;">
																<span class="input-group-addon">
																	<i class="far fa-calendar-alt"></i>
																</span>
																<input type="text" class="form-control eci_filing_date" id="eci_filing_date" name="eci_filing_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="dd MMMM yyyy ">
															</div>
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
																	<th style="text-align: center">Next ECI Filing Due Date</th>
																	<th style="text-align: center">ECI Filing Date</th>
																	<th></th>
																</tr> 

															</table>
														</div>	
													</div>
													<?= form_close(); ?>
												</div>
												<!---ECI--->
												<button type="button" class="collapsible"><span style="font-size: 2.4rem;">Corporate Tax</span></button>
												<div class="incorp_content">
												<?php echo form_open_multipart('', array('id' => 'tax_form', 'enctype' => "multipart/form-data")); ?>

													<div class="hidden"><input type="text" class="form-control" name="company_code" value="<?=$company_code?>"/></div>
													<div class="hidden"><input type="text" class="form-control tax_id" name="tax_id" value=""/></div>

													
													
													<div class="form-group" style="margin-top: 20px; margin-bottom: 20px;">
														<label class="col-xs-3" for="w2-show_all">Corporate Tax Period: </label>
														<div class="col-sm-9 form-inline">
															<div class="input-bar">
															    <div class="input-bar-item">
														            <div class="input-group" style="width: 200px;">
																		<span class="input-group-addon">
																			<i class="far fa-calendar-alt"></i>
																		</span>
																		<input type="text" class="form-control coporate_tax_period" id="coporate_tax_period" name="coporate_tax_period" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="dd MMMM yyyy ">
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
														<label class="col-sm-3" for="w2-DS1">Tax Filing Due Date:</label>
														<div class="col-sm-3">
															<input type="text" name="tax_filing_due_date" class="form-control" value="" id="tax_filing_due_date" readonly="true" style="width: 200px;"/>
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
													<div>
														<div class="form-group">
															<div class="col-sm-12">
																<button type="button" class="btn btn-primary update_tax" id="update_tax" style="float: right">Update</button>
															</div>
														</div>
														
														<div style="margin-bottom: 20px;">
															<h3>Corporate Tax Filing History</h3>
															<table style="border:1px solid black" class="allotment_table" id="tax_filing_table">
												
																<tr> 
																	<th style="width:50px !important;text-align: center">No</th>
																	<th style="text-align: center">Corporate Tax Period</th> 
																	<th style="text-align: center">Tax Filing Period</th>
																	<th style="text-align: center">Tax Filing Due Date</th> 
																	<th style="text-align: center">Filing Date</th> 
																	
																	<th></th>
																</tr> 

															</table>
														</div>	
													</div>
													<?= form_close(); ?>
												</div>
												<!--Coporate Tax-->
												<button type="button" class="collapsible"><span style="font-size: 2.4rem;">GST</span></button>
												<div class="incorp_content">
												<?php echo form_open_multipart('', array('id' => 'gst_form', 'enctype' => "multipart/form-data")); ?>

													<div class="hidden"><input type="text" class="form-control" name="company_code" value="<?=$company_code?>"/></div>

													<div class="hidden"><input type="text" class="form-control gst_id" name="gst_id" value=""/></div>
						
													<div class="form-group" style="margin-top: 20px; margin-bottom: 20px;">
														<label class="col-xs-3" for="w2-show_all">GST Year End: </label>
														<div class="col-sm-9 form-inline">
															<div class="input-bar">
															    <div class="input-bar-item">
														            <div class="input-group" style="width: 200px;">
																		<span class="input-group-addon">
																			<i class="far fa-calendar-alt"></i>
																		</span>
																		<input type="text" class="form-control gst_year_end" id="gst_year_end" name="gst_year_end" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="dd MMMM yyyy ">
																	</div>
																	<div id="validate_gst_year_end"></div>
															    </div>
															</div>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-3" for="w2-DS1">Filing Cycle:</label>
															<div class="col-sm-3">
																<select id="gst_filing_cycle" class="form-control gst_filing_cycle" id="gst_filing_cycle" style="width:200px;" name="gst_filing_cycle">
												                    <option value="0">Please Select</option>
												                </select>
															</div>

													</div>
													<div class="form-group">
														<label class="col-sm-3" for="w2-DS1">Filing Period: </label>
														<div class="col-sm-9">
															<div class="input-bar">
																<div class="input-bar-item">
														            <div class="input-daterange input-group" data-plugin-datepicker data-date-format="dd/mm/yyyy">
																		<span class="input-group-addon">
																			<i class="far fa-calendar-alt"></i>
																		</span>
																		<input type="text" class="form-control gst_filing_period1" id="gst_filing_period1" name="gst_filing_period1" value="" placeholder="dd MMMM yyyy">
																		<span class="input-group-addon">to</span>
																		<input type="text" class="form-control gst_filing_period2" id="gst_filing_period2" name="gst_filing_period2" value="" placeholder="dd MMMM yyyy">
																	</div>
																</div>
															</div>
														</div>
													</div>
													<div class="form-group" style="margin-top: 20px; margin-bottom: 20px;">
														<label class="col-xs-3" for="w2-show_all">Filing Due Date: </label>
														<div class="col-sm-9 form-inline">
															<div class="input-bar">
															    <div class="input-bar-item">
														            <div class="input-group" style="width: 200px;">
																		<span class="input-group-addon">
																			<i class="far fa-calendar-alt"></i>
																		</span>
																		<input type="text" class="form-control gst_filing_due_date" id="gst_filing_due_date" name="gst_filing_due_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="dd MMMM yyyy">
																	</div>
															    </div>
															</div>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-3" for="w2-filingDate">Filing Date:</label>
														<div class="col-sm-9 form-inline">
															<div class="input-bar">
															    <div class="input-bar-item">
															        <div class="year_end_group">
															            <div class="input-group" style="width: 200px;">
																			<span class="input-group-addon">
																				<i class="far fa-calendar-alt"></i>
																			</span>

																			<input type="text" class="form-control gst_filing_date" id="gst_filing_date" name="gst_filing_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="dd MMMM yyyy ">
																		</div>
																		<div id="validate_gst_filing_date"></div>
															        </div>
															    </div>
															</div>
											            </div>
													</div>
													<div class="form-group">
														<div class="col-sm-12 form-inline">
														    <div class="input-bar-item-btn">
														      <button type="button" class="btn btn-primary de_register_button" onclick="de_register(this)">De Register</button>
														    </div>
											            </div>
													</div>
													<div class="form-group de_register_date_section" style="display: none">
														<label class="col-sm-3" for="w2-DS1">De Registration Date:</label>
														<div class="col-sm-9">
															<div class="input-group" style="width: 200px;">
																<span class="input-group-addon">
																	<i class="far fa-calendar-alt"></i>
																</span>
																<input type="text" class="form-control gst_de_registration_date" id="gst_de_registration_date" name="gst_de_registration_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="dd MMMM yyyy ">
															</div>
															<div id="validate_gst_de_registration_date"></div>
														</div>
													</div>
													<div>
														<div class="form-group">
															<div class="col-sm-12">
																<button type="button" class="btn btn-primary update_gst" id="update_gst" style="float: right">Update</button>
															</div>
														</div>
														
														<div style="margin-bottom: 20px;">
															<h3>GST Filing History</h3>
															<table style="border:1px solid black" class="allotment_table" id="gst_filing_table">
												
																<tr> 
																	<th style="width:50px !important;text-align: center">No</th>
																	<th style="text-align: center">GST Year End</th> 
																	<th style="text-align: center">Filing Cycle</th>
																	<th style="text-align: center">Filing Period</th> 
																	<th style="text-align: center">Filing Due Date</th> 
																	<th style="text-align: center">Filing Date</th> 
																	<th style="text-align: center">De Registration Date</th> 
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
										<?php if ($setup_module != 'none') { ?>
										<!-- Setup -->
										<div id="w2-setup" class="tab-pane">
											
											<form id="signing_information_form">
												<div class="hidden"><input type="text" class="form-control" name="company_code" value="<?=$company_code?>"/></div>
											
												<button type="button" class="setup_collapsible" style="margin-top: 10px;">
													<span style="font-size: 2.4rem;">Signing Information</span>
												</button>
												<!-- <h3>Signing Information</h3> -->
												<div class="setup_content">
													<div style="margin-top: 20px; margin-bottom: 20px;">
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
																<?php
																	if($director_retiring != false)
																	{
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
																	}
																?>
															</table>
														</div>
													</div>
													<div class="form-group">
														<div class="col-sm-12">
															<button type="button" class="btn btn-primary save_signing_information" id="save_signing_information" style="float: right">Save</button>
														</div>
													</div>
												</div>
											</form>
											<form id="contact_information_form">
												<div class="hidden"><input type="text" class="form-control" name="company_code" value="<?=$company_code?>"/></div>
												<button type="button" class="setup_collapsible" style="margin-top: 10px;">
													<span style="font-size: 2.4rem;">Contact Information</span>
												</button>
												<div class="setup_content">

												<div style="margin-top: 20px; margin-bottom: 50px;">
												<div class="form-group">
													<label class="col-sm-3" for="w2-chairman">Name:</label>
													<div class="col-sm-9">
														<input type="text" style="width:400px;text-transform:uppercase;" class="form-control" name="contact_name" id="contact_name" value="<?=isset($client_contact_info[0]->name) ? $client_contact_info[0]->name : ''?>"/>
										            </div>
													
												</div>
												<div class="form-group">
													<label class="col-sm-3" for="w2-chairman">Phone:</label>
													<div class="col-sm-9">
														<div class="input-group fieldGroup_contact_phone">
															<input type="tel" class="form-control check_empty_contact_phone main_contact_phone hp" id="contact_phone" name="contact_phone[]" value="<?=isset($client_contact_info[0]->contact_phone) ? $client_contact_info[0]->contact_phone : ''?>"/>

															<input type="hidden" class="form-control input-xs hidden_contact_phone main_hidden_contact_phone" id="hidden_contact_phone" name="hidden_contact_phone[]" value=""/>

															<label class="radio-inline control-label" style="margin-top: -30px; margin-left: 20px;"><input type="radio" class="contact_phone_primary main_contact_phone_primary" name="contact_phone_primary" value="1" checked> Primary</label>

															<input class="btn btn-primary button_increment_contact_phone addMore_contact_phone" type="button" id="create_button" value="+" style="margin-left: 20px; margin-top: -26px; border-radius: 3px;visibility: hidden; width: 35px;"/>

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
														</div>
														<div id="form_contact_phone"></div>
										            </div>
												</div>
												<div class="form-group">
													<label class="col-sm-3" for="w2-chairman">Email:</label>
														<div class="col-sm-9">
															<div class="input-group fieldGroup_contact_email" style="display: block !important;">
																<input type="text" class="form-control input-xs check_empty_contact_email main_contact_email" id="contact_email" name="contact_email[]" value="<?=isset($client_contact_info[0]->contact_email) ? $client_contact_info[0]->contact_email : '' ?>" style="text-transform:uppercase; width:400px;"/>

																<label class="radio-inline control-label" style="margin-left: 20px;"><input type="radio" class="contact_email_primary main_contact_email_primary" name="contact_email_primary" value="1" checked> Primary</label>

																<input class="btn btn-primary button_increment_contact_email addMore_contact_email" type="button" id="create_button" value="+" style="margin-left: 20px; border-radius: 3px;visibility: hidden; width: 35px;"/>

																<button type="button" class="btn btn-default btn-sm show_contact_email" style="margin-left: 20px; visibility: hidden;">
																	  <span class="fa fa-arrow-down" aria-hidden="true"></span>&nbsp<span class="toggle_word">Show more</span>
																</button>
															</div>
															<div class="contact_email_toggle">
															</div>

															<div class="input-group fieldGroupCopy_contact_email contact_email_disabled" style="display: none;">
																<input type="text" class="form-control input-xs check_empty_contact_email second_contact_email" id="contact_email" name="contact_email[]" value="" style="width:400px; text-transform:uppercase; "/>
																<label class="radio-inline control-label" style="margin-left: 20px;"><input type="radio" class="contact_email_primary" name="contact_email_primary" value="1"> Primary</label>
															</div>

															<div id="form_contact_email"></div>
									            		</div>
													</div>
												</div>
												<div class="form-group">
													<div class="col-sm-12">
														<button type="button" class="btn btn-primary save_contact_information" id="save_contact_information" style="float: right">Save</button>
													</div>
												</div>
												</div>
											</form>
											<?php if ((!$Individual && $Individual == true) || (!$Individual && $Individual == null && !$Client)) {?>
												<form id="reminder_form">
													<div class="hidden"><input type="text" class="form-control" name="company_code" value="<?=$company_code?>"/></div>
													<button type="button" class="setup_collapsible" style="margin-top: 10px;">
														<span style="font-size: 2.4rem;">Reminder</span>
													</button>
													<div class="setup_content">
														<div style="margin-top: 20px;">
															<div class="form_select_reminder form-group">
																<label class="col-sm-3" for="w2-DS2">Reminder:</label>
																<div class="col-sm-9">
																	<div class="select_reminder_group" style="float: left;margin-right: 10px; width: 300px;">
														                <select class="form-control" id="select_reminder" multiple="multiple" name="select_reminder[]">
									                                    </select>
														            </div>
																</div>
															</div>
														</div>
														<div class="form-group">
															<div class="col-sm-12">
																<button type="button" class="btn btn-primary save_reminder" id="save_reminder" style="float: right">Save</button>
															</div>
														</div>
													</div>
												</form>
												<form id="corporate_representative_form">
													<div class="hidden"><input type="text" class="form-control" name="company_code" value="<?=$company_code?>"/></div>
													<button type="button" class="setup_collapsible" style="margin-top: 10px;">
														<span style="font-size: 2.4rem;">Corporate Representative</span>
													</button>
													<div class="setup_content">
														<div style="margin-top: 20px; margin-bottom: 20px;">
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
														</div>
														<div class="form-group">
															<div class="col-sm-12">
																<button type="button" class="btn btn-primary save_corporate_representative" id="save_corporate_representative" style="float: right">Save</button>
															</div>
														</div>
													</div>
												</form>
												<form id="related_group_form">
													<input type="hidden" class="form-control related_group_client_id" name="related_group_client_id" value="<?=isset($client->id) ? $client->id : ''?>"/>
													<button type="button" class="setup_collapsible group_related_party" style="margin-top: 10px;">
														<span style="font-size: 2.4rem;">Group/Related Party</span>
													</button>
													<div class="setup_content">
														<div style="margin-top: 20px;">
															<div class="form_select_group form-group">
																<label class="col-sm-3" for="w2-DS2">Group:</label>
																<div class="col-sm-9">
																	<div class="select_group" style="float: left;margin-right: 10px; width: 300px;">
														                <select class="form-control setup_select_group" id="select_group" multiple="multiple" name="select_group[]">
									                                    </select>
														            </div>
																</div>
															</div>
														</div>
														<div style="margin-top: 20px;">
															<div class="form_select_related_party form-group">
																<label class="col-sm-3" for="w2-DS2">Related Party:</label>
																<div class="col-sm-9">
																	<div class="select_related_party_group" style="float: left;margin-right: 10px; width: 300px;">
														                <select class="form-control setup_select_related_party" id="select_related_party" multiple="multiple" name="select_related_party[]">
									                                    </select>
														            </div>
																</div>
															</div>
														</div>
														<div class="form-group">
															<div class="col-sm-12">
																<button type="button" class="btn btn-primary save_related_party" id="save_related_party" style="float: right">Save</button>
															</div>
														</div>
													</div>
												</form>
											<?php
												}
											?>
										</div>
										<?php
											}
										?>
										<?php if ($register_module != 'none') { ?>
										<div id="w2-register" class="tab-pane <?php if ($Individual || $Client) {?> active <?php }?>">
											<?php echo form_open_multipart('', array('id' => 'register_form', 'enctype' => "multipart/form-data")); ?> 
											<div class="hidden"><input type="text" class="form-control" name="company_code" value="<?=$company_code?>"/></div>

											<span style="font-size: 2.4rem;">Register</span>
											<a href="javascript: void(0);" class="btn btn-default" id="printBtn" class="printBtn" style="float: right;">Print</a>
											
											<div class="form-group" style="margin-top: 20px;">
												<label class="col-xs-3 control-label" for="w2-show_all">Register: </label>
												<div class="col-sm-9 form-inline">
													<select id="register" class="form-control register" id="register" style="width:200px;" name="register">
									                    <option value="0">Please Select</option>
									                    <option value="all" selected <?=isset($_POST['type']) == "all"?'selected':'';?>>All</option>
									                    <option value="profile" <?=isset($_POST['type']) == "profile"?'selected':'';?>>Profile</option>
									                    <option value="officer" <?=isset($_POST['type']) == "officer"?'selected':'';?>>Officers</option>
									                    <option value="nominee_director" <?=isset($_POST['type']) == "nominee_director"?'selected':'';?>>Nominee Director</option>
									                    <option value="member" <?=isset($_POST['type']) == "member"?'selected':'';?>>Members</option>
									                    <option value="transfer" <?=isset($_POST['type']) == "transfer"?'selected':'';?>>Transfer</option>
									                    <option value="controller" <?=isset($_POST['type']) == "controller"?'selected':'';?>>Controller</option>
									                    <option value="charges" <?=isset($_POST['type']) == "charges"?'selected':'';?>>Charges</option>
														<option value="filing" <?=isset($_POST['type'])=='filing'?'selected':'';?>>Filing</option>
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
												<div id="register_table">
												</div>
											</div>
											
											<?= form_close(); ?>
										</div>
										<?php
											}
										?>
										<div class="tab-pane" id="services_list">
											<div style="margin-bottom: 23px">
												<span style="font-size: 2.4rem;padding: 0; margin: 7px 0 4px 0;">Services List</span>
											</div>
											<table class="table table-bordered table-striped table-condensed mb-none" id="datatable-transaction">
												<thead>
													<tr>
														<th>No</th>
														<!-- <th>Firm Name</th> <td>'.$a->firm_name.'</td>-->
														<th>Transaction Type</th>
														<th>Create Date</th>
														<th>Effective Date</th>
														<th>Lodgement Date</th>
														<th>Transaction ID</th>
														<th>Document</th>
														<th>Remarks</th>
														<th>Status</th>
													</tr>
												</thead>
												<tbody id="service_body">
													<?php
														$i = 1;
														if(isset($transaction) && $transaction != false)
														{
															foreach($transaction as $a)
															{
																//echo json_encode($a);
																$document_link = $a->transaction_pending_documents_id.'/trans';
																if($a->transaction_pending_documents_id != "")
																{
																	if($a->received_on == null)
																	{
																		$document_button = '<a id="add_pending_document_file" href="documents/add_pending_document_file/'.$document_link.'" class="btn btn-primary add_pending_document_file" target="_blank">Update</a>';
																	}
																	else
																	{
																		$document_button = '<a href="documents/edit_pending_document_file/'.$document_link.'" class="pointer mb-sm mt-sm mr-sm" target="_blank">'.$a->received_on.'';
																	}
																}
																else
																{
																	$document_button = "";
																}
																echo '<tr>
																		<td>'.$i.'</td>
																		<td>
																		<a class="'.(($a->status == 4 || $a->status == 5)?'link_disabled':'').'" href="'.site_url("transaction/edit/".$a->id).'" target="_blank" style="cursor:pointer" data-toggle="tooltip" data-trigger="hover" data-original-title="Edit This Transaction">'.$a->transaction_task.'</a>
																		</td>
																		<td><span style="display:none">'.date("Ymd",strtotime($a->created_at)).'</span>'.date("d F Y",strtotime($a->created_at)).'</td>
																		<td style="text-align:center;"><span style="display:none">'.($a->lodgement_date != ""?date("Ymd",strtotime($a->effective_date)):"").'</span>'.($a->lodgement_date != ""?($a->effective_date != "")?$a->effective_date:"-":"").'</td>
																		<td><span style="display:none">'.date("Ymd",strtotime($a->lodgement_date)).'</span>'.$a->lodgement_date.'</td>
																		<td>'.$a->transaction_code.'</td>
																		<td>'.$document_button.'</td>
																		<td>'.$a->remarks.'</td>
																		<td>'.$a->transaction_status.'</td>
																		
																	</tr>';
																$i++;
															}
														}
													?>
												</tbody>
											</table>
										</div>
										<div class="tab-pane" id="letter_confirmation_to_auditor">
											<?php echo form_open_multipart('', array('id' => 'letter_confirmation_to_auditor_form', 'enctype' => "multipart/form-data")); ?> 
												<div class="hidden"><input type="text" class="form-control" name="company_code" value="<?=$company_code?>"/></div>

												<div style="margin-bottom: 23px">
													<span style="font-size: 2.4rem;padding: 0; margin: 7px 0 4px 0;">Letter of Confirmation to Auditor</span>

													<!-- <a href="javascript: void(0);" class="btn btn-primary" id="email_letter_conf_pdf" class="email_letter_conf_pdf" style="float: right;">Email</a> -->

													<a href="javascript: void(0);" class="btn btn-primary" id="generate_letter_conf_pdf" class="generate_letter_conf_pdf" style="float: right; margin-right: 5px;">PDF</a>
												</div>
												<div style="margin-bottom: 80px !important;">
													<label class="col-sm-2" for="w2-show_all" style="padding-left: 0px !important;">Date Range: </label>
													<div class="col-sm-5 form-inline" style="padding-left: 0px !important;">
														<div class="input-daterange input-group" data-plugin-datepicker>
															<span class="input-group-addon">
																<i class="far fa-calendar-alt"></i>
															</span>
															<input type="text" class="form-control" id="letter_conf_auditor_date_from" name="letter_conf_auditor_date_from" value="" placeholder="From" data-date-format="dd/mm/yyyy">
															<span class="input-group-addon">to</span>
															<input type="text" class="form-control" id="letter_conf_auditor_date_to" name="letter_conf_auditor_date_to" value="" placeholder="To" data-date-format="dd/mm/yyyy">
														</div>
													</div>

													<div class="col-md-2" style="padding-left: 0px !important;">
														<input type="button" class="btn btn-primary search_report" id="searchLetterConfAuditor" name="searchLetterConfAuditor" value="Search"/>
													</div>
												</div>
											<?= form_close(); ?>
											<table class="table table-bordered table-striped table-condensed mb-none" id="datatable-letter_confirmation_to_auditor">
												<thead>
													<tr>
														<th style="width: 50px;">No</th>
														<th>Lodgement Date</th>
														<th>Transactions</th>
													</tr>
												</thead>
												<tbody id="letter_confirmation_to_auditor_body">
													<?php
														$i = 1;
														if(isset($list_of_confirmation_auditor) && $list_of_confirmation_auditor != false)
														{
															foreach($list_of_confirmation_auditor as $a)
															{
																echo '<tr class="tr_letter_confirmation_to_auditor">
																		<td>'.$i.'</td>
																		<td><span style="display:none">'.date("Ymd",strtotime($a->lodgement_date)).'</span>'.$a->lodgement_date.'</td>
																		<td>'.$a->transaction_task.'</td>
																	</tr>';
																$i++;
															}
														}
													?>
												</tbody>
											</table>
										</div>
										<div class="tab-pane" id="company_document">
											<form id="company_document_form" enctype="multipart/form-data" accept-charset="utf-8">
												<div class="hidden"><input type="text" class="form-control company_doc_company_code" name="company_code" value="<?=$company_code?>"/></div>
												<div class="hidden"><input type="text" class="form-control document_type_id" name="document_type_id" value=""/></div>
												<div class="form-group">
													<label class="col-xs-2">Document Type: </label>
													<div class="col-xs-7 form-inline">
														<select id="document_type" class="form-control document_type" style="width:200px;" name="document_type">
										                    <!-- <option value="0">Please Select</option> -->
										                    <option value="Constitution">Constitution</option>
										                    <option value="Business Profile">Business Profile</option>
										                    <option value="Financial Statement">Financial Statement</option>
										                    <option value="Others">Others</option>
														</select>
													</div>
												</div>
												<div class="form-group div_document_others_name" style="display:none">
													<label class="col-xs-2">Others: </label>
													<div class="col-xs-7 form-inline">
														<input type="text" class="form-control" id="document_others_name" name="document_others_name" value="" placeholder="Others"  style="width:200px">
													</div>
												</div>
												<div class="form-group">
													<label class="col-xs-2">Document: </label>
													<div class="col-xs-7 form-inline">
														<input type="file" style="display:none" id="attachment" multiple="" name="attachment[]">
														<label for="attachment" class="btn btn-primary" class="attachment">Attachment</label>
														<br/><span class="file_name"></span><input type="hidden" class="hidden_attachment" name="hidden_attachment" value=""/>
													</div>
												</div>
												<div class="col-sm-12" style="padding-right: 0px;">
													<button type="button" class="btn btn-primary update_company_document" id="update_company_document" style="float: right">Update</button>
												</div>
											</form>
											<div style="padding-left: 15px; margin-top: 70px;">
												<table class="table table-bordered table-striped table-condensed mb-none" id="datatable-company_document">
													<thead>
														<tr>
															<th>Document Type</th>
															<th>Document</th>
															<th></th>
														</tr>
													</thead>
													<tbody id="company_document_body">
														
														<?php
															$i = 1;
															if(isset($list_of_company_document) && $list_of_company_document != false)
															{
																foreach($list_of_company_document as $a)
																{
																	$file_result = json_decode($a->attachment);
												                    $filename = "";
												                    $string_filename = "";
												                    //console.log(this.files);
												                    for($i = 0; $i < count($file_result); $i++)
												                    {
												                        if($i == 0)
												                        {
												                            $filename = '<a href="'.base_url().'uploads/company_document/'.$file_result[$i].'" target="_blank">'.$file_result[$i].'</a>';
												                            $string_filename = $file_result[$i];
												                        }
												                        else
												                        {
												                            $filename = $filename . ", " . '<a href="'.base_url().'uploads/company_document/'.$file_result[$i].'" target="_blank">'.$file_result[$i].'</a>';
												                            $string_filename = $string_filename . ", " . $file_result[$i];
												                        }
												                    }
																	echo '<tr class="company_document_table">
																			<td><span style="height:45px;font-weight:bold;cursor: pointer;" class="amber editCompanyDocument" data-document_id = "'.$a->id.'" data-document_type = "'.$a->document_type.'" data-document_others_name = "'.$a->document_others_name.'" data-string_filename = "'.$string_filename.'" data-array_filename = '.$a->attachment.'>'.(($a->document_type != "Others")?$a->document_type:$a->document_others_name).'</span></td>
																			<td>'.$filename.'</td>
																			<td><button type="button" class="btn btn-primary" onclick="deleteCompanyDocument('.$a->id.')">Delete</button></td>
																		</tr>';
																	$i++;
																}
															}
														?>
													</tbody>
												</table>
											</div>
										</div>
										
    
										<!-- <div class="tab-pane" id="bootstab">Bootstrap Content here
										<ul>
										<li>Bootstrap forms</li>
										<li>Bootstrap buttons</li>
										<li>Bootstrap navbar</li>
										<li>Bootstrap footer</li>
										</ul>
										</div>
										<div class="tab-pane" id="htmltab">Hypertext Markup Language</div>  -->
									<!-- </div> -->
									</div>
								</div>
							</div>
						</div>
					</div>
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-12 number text-right client_footer_button" id="client_footer_button">
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
<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>
<div class="loading" id='loadingControllerMessage' style='display:none; z-index: 9999 !important;'>Loading&#8230;</div>
<div id="modal_controller" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;">
	<div class="modal-dialog">
		<div class="modal-content">
			<header class="panel-heading">
				<h2 class="panel-title">Update Register of Controller</h2>
			</header>
			
				<div class="panel-body">
					<div class="col-md-12">
						<label class="font_14">Category:&nbsp;&nbsp;</label>
						<label><input type="radio" id="reg_cont_individual_edit" name="field_type" value="Individual" class="check_stat" data-information="individual" checked="checked"/>&nbsp;&nbsp;Individual</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label><input type="radio" id="reg_cont_company_edit" name="field_type" value="company" class="check_stat" data-information="company"/>&nbsp;&nbsp;Corporate</label>
					</div>
					<form id="form_individual_register_of_controller" autocomplete="off" enctype="multipart/form-data">
						<div class="individual_register_controller">
							<!-- <div class="col-md-6">
								<div class="form-group">
		                        	<label class="font_14" for="identification_type">Identification Type<span class="color_red">*</span></label>
		                            <select class="form-control" id="identification_type" name="identification_type" disabled="true">
										<option value="NRIC (Singapore citizen)">NRIC (Singapore Citizen)</option>
										<option value="NRIC (PR)">NRIC (Singapore Permanent Resident)</option>
										<option value="FIN Number">Passholders</option>
										<option value="Passport/ Others">Passport/ Others</option>
									</select>
		                        </div>
							</div> -->
							<div class="col-md-12" style="padding-left: 0px !important; padding-right: 0px !important;">
								<div class="col-md-6">
									<div class="form-group div_identification_no">
			                        	<label class="font_14" for="gid_add_controller_officer">Identification No.<span class="color_red">*</span></label>
			                            <input style="text-transform:uppercase" type="text" id="gid_add_controller_officer" class="form-control" placeholder="Identification No." value="" name="identification_no" required="required"/>
			                            <a class="add_office_person_link" href="" style="cursor:pointer;" id="add_office_person_link" target="_blank" hidden onclick="add_controller_person(this)"><div style="cursor:pointer;">Click here to <span style="font-weight:bold">Add Person</span></div></a>
			                            <a class="refresh_a_controller" href="javascript:void(0)" style="cursor:pointer;" id="refresh_controller" hidden onclick="refresh_person()"><div style="cursor:pointer;"><span class="refresh_controller" style="font-weight:bold">Refresh</span></div></a>
			                            <a class="indi_view_edit_person" href="" style="cursor:pointer;" id="indi_view_edit_person" target="_blank" hidden><div style="cursor:pointer;"><span style="font-weight:bold">View/Edit</span></div></a>

			                        </div>
								</div>
								<div class="col-md-6">
									<div class="form-group div_controller_name">
			                        	<label class="font_14" for="individual_controller_name">Name<span class="color_red">*</span></label>
			                            <input style="text-transform:uppercase" type="text" id="individual_controller_name" class="form-control" placeholder="Name" value="" name="individual_controller_name" required="required" readonly="true"/>
			                            <input type="hidden" id="client_controller_id" value="" name="client_controller_id"/>
			                            <input type="hidden" id="company_code" value="" name="company_code"/>
			                            <input type="hidden" id="officer_id" value="" name="officer_id"/>
			                            <input type="hidden" id="officer_field_type" value="" name="officer_field_type"/>
			                            <input type="hidden" id="date_of_birth" value="" name="date_of_birth"/>
			                            <input type="hidden" id="nationality" value="" name="nationality"/>
			                            <input type="hidden" id="controller_address" value="" name="controller_address"/>
			                        </div>
								</div>
							</div>
							<!-- <div class="col-md-6">
								<div class="form-group">
		                        	<label class="font_14" for="aliases">Aliases (if any)</label>
		                            <input style="text-transform:uppercase" type="text" id="aliases" class="form-control" placeholder="Aliases" value="" name="aliases" readonly="true"/>
		                        </div>
							</div> -->
							<div class="col-md-6">
								<div class="form-group div_date_appoint">
		                        	<label class="font_14" for="aliases">Date appointed as registrable controller<span class="color_red">*</span></label>
		                            <div class="input-group mb-md" style="width: 100%;">
										<span class="input-group-addon">
											<i class="far fa-calendar-alt"></i>
										</span>
										<input type="text" class="form-control input-xs" id="date_appointed" name="date_appointed" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="DD/MM/YYYY">
									</div>
		                        </div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
		                        	<label class="font_14" for="aliases">Date ceased as registrable controller</label>
		                            <div class="input-group mb-md" style="width: 100%;">
										<span class="input-group-addon">
											<i class="far fa-calendar-alt"></i>
										</span>
										<input type="text" class="form-control input-xs" id="date_ceased" name="date_ceased" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="DD/MM/YYYY">
									</div>
		                        </div>
							</div>
							<div class="col-md-12" style="padding-left: 0px !important; padding-right: 0px !important;">
								<div class="col-md-6">
									<div class="form-group">
			                        	<label class="font_14" for="aliases">Date of notice</span></label>
			                            <div class="input-group mb-md" style="width: 100%;">
											<span class="input-group-addon">
												<i class="far fa-calendar-alt"></i>
											</span>
											<input type="text" class="form-control input-xs" id="date_of_notice" name="date_of_notice" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="DD/MM/YYYY">
										</div>
			                        </div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
			                        	<label class="font_14" for="aliases">Date of entry/update</span></label>
			                            <div class="input-group mb-md" style="width: 100%;">
											<span class="input-group-addon">
												<i class="far fa-calendar-alt"></i>
											</span>
											<input type="text" class="form-control input-xs" id="date_of_entry" name="date_of_entry" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="DD/MM/YYYY">
										</div>
			                        </div>
								</div>
							</div>
							<div class="form-group" style="margin-bottom: 0px !important;">
								<div class="col-md-12">
		                    		<label class="font_14" for="aliases">Confirmation by Registrable Controller<span class="color_red">*</span></label>
		                    	</div>
		                    	<div class="col-md-12 div_radio_confirm_controller">
		                        	<label><input type="radio" id="individual_is_confirm_registrable_controller" name="individual_confirm_registrable_controller" value="yes" class="check_is_confirm_registrable_controller" data-information="yes"/>&nbsp;&nbsp;Yes</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<label><input type="radio" id="individual_not_confirm_registrable_controller" name="individual_confirm_registrable_controller" value="no" class="check_is_confirm_registrable_controller" data-information="no"/>&nbsp;&nbsp;No</label>
									<input type="hidden" id="radio_individual_confirm_registrable_controller" value="" name="radio_individual_confirm_registrable_controller"/>
								</div>
								<div class="col-md-6 individual_div_date_confirmation div_date_confirmation" style="display: none;">
		                            <div class="input-group mb-md" style="width: 100%;">
										<span class="input-group-addon">
											<i class="far fa-calendar-alt"></i>
										</span>
										<input type="text" class="form-control input-xs" id="date_confirmation" name="date_confirmation" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="DD/MM/YYYY">
									</div>
								</div>
		                    </div>
							<div class="col-md-12">
								<div class="form-group">
		                        	<label class="font_14" for="aliases">Supporting Document (if any)</label>
		                        	<div class="input-group">
		                        		<input type="file" style="display:none" id="supporting_document" class="supporting_document" name="supporting_document">
		                        		<label for="supporting_document" class="btn btn-primary" class="supporting_document">Attachment</label><br/>
		                        		<span class="file_name"></span>
		                        		<input type="hidden" class="hidden_supporting_document" name="hidden_supporting_document" value=""/>
		                        	</div>
		                        </div>
							</div>
						</div>
					</form>
					<form id="form_company_register_of_controller" autocomplete="off" enctype="multipart/form-data">
						<div class="company_register_controller" style="display: none;">
							<div class="col-md-12" style="padding-left: 0px !important; padding-right: 0px !important;">
								<div class="col-md-6">
									<div class="form-group div_controller_uen">
			                        	<label class="font_14" for="gid_add_controller_officer">UEN<span class="color_red">*</span></label>
			                            <input style="text-transform:uppercase" type="text" id="gid_add_controller_officer" class="form-control" placeholder="UEN" value="" name="controller_uen" required="required"/>
			                            <a class="add_office_person_link" href="" style="cursor:pointer;" id="add_office_person_link" target="_blank" hidden onclick="add_controller_person(this)"><div style="cursor:pointer;">Click here to <span style="font-weight:bold">Add Person</span></div></a>
			                            <a class="refresh_a_controller" href="javascript:void(0)" style="cursor:pointer;" id="refresh_controller" hidden onclick="refresh_person()"><div style="cursor:pointer;"><span class="refresh_controller" style="font-weight:bold">Refresh</span></div></a>
			                            <a class="corp_view_edit_person" href="" style="cursor:pointer;" id="corp_view_edit_person" target="_blank" hidden><div style="cursor:pointer;"><span style="font-weight:bold">View/Edit</span></div></a>
			                        </div>
								</div>
								<div class="col-md-6">
									<div class="form-group div_entity_name">
			                        	<label class="font_14" for="entity_name">Entity Name<span class="color_red">*</span></label>
			                            <input style="text-transform:uppercase" type="text" id="entity_name" class="form-control" placeholder="Entity Name" value="" name="entity_name" required="required" readonly="true"/>
			                            <input type="hidden" id="client_controller_id" value="" name="client_controller_id"/>
			                            <input type="hidden" id="company_code" value="" name="company_code"/>
			                            <input type="hidden" id="officer_id" value="" name="officer_id"/>
			                            <input type="hidden" id="officer_field_type" value="" name="officer_field_type"/>
			                            <input type="hidden" id="date_of_birth" value="" name="date_of_birth"/>
			                            <input type="hidden" id="nationality" value="" name="nationality"/>
			                            <input type="hidden" id="controller_address" value="" name="controller_address"/>
			                        </div>
								</div>
							</div>
							<div class="col-md-12" style="padding-left: 0px !important; padding-right: 0px !important;">
								<div class="col-md-6">
									<div class="form-group div_date_appoint">
			                        	<label class="font_14" for="date_appointed">Date appointed as registrable controller<span class="color_red">*</span></label>
			                            <div class="input-group mb-md" style="width: 100%;">
											<span class="input-group-addon">
												<i class="far fa-calendar-alt"></i>
											</span>
											<input type="text" class="form-control input-xs" id="date_appointed" name="date_appointed" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="DD/MM/YYYY">
										</div>
			                        </div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
			                        	<label class="font_14" for="date_ceased">Date ceased as registrable controller</label>
			                            <div class="input-group mb-md" style="width: 100%;">
											<span class="input-group-addon">
												<i class="far fa-calendar-alt"></i>
											</span>
											<input type="text" class="form-control input-xs" id="date_ceased" name="date_ceased" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="DD/MM/YYYY">
										</div>
			                        </div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
		                        	<label class="font_14" for="aliases">Date of notice</span></label>
		                            <div class="input-group mb-md" style="width: 100%;">
										<span class="input-group-addon">
											<i class="far fa-calendar-alt"></i>
										</span>
										<input type="text" class="form-control input-xs" id="date_of_notice" name="date_of_notice" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="DD/MM/YYYY">
									</div>
		                        </div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
		                        	<label class="font_14" for="aliases">Date of entry/update</span></label>
		                            <div class="input-group mb-md" style="width: 100%;">
										<span class="input-group-addon">
											<i class="far fa-calendar-alt"></i>
										</span>
										<input type="text" class="form-control input-xs" id="date_of_entry" name="date_of_entry" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="DD/MM/YYYY">
									</div>
		                        </div>
							</div>
							<div class="form-group" style="margin-bottom: 0px !important;">
								<div class="col-md-12">
		                    		<label class="font_14">Confirmation by Registrable Controller<span class="color_red">*</span></label>
		                    	</div>
		                    	<div class="col-md-12 div_radio_corp_confirm_registrable_controller">
		                        	<label><input type="radio" id="corp_is_confirm_registrable_controller" name="corp_confirm_registrable_controller" value="yes" class="check_is_confirm_registrable_controller" data-information="yes"/>&nbsp;&nbsp;Yes</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<label><input type="radio" id="corp_not_confirm_registrable_controller" name="corp_confirm_registrable_controller" value="no" class="check_is_confirm_registrable_controller" data-information="no"/>&nbsp;&nbsp;No</label>
									<input type="hidden" id="radio_corp_confirm_registrable_controller" value="" name="radio_corp_confirm_registrable_controller"/>
								</div>
								<div class="col-md-6 corp_div_date_confirmation" style="display: none;">
		                            <div class="input-group mb-md" style="width: 100%;">
										<span class="input-group-addon">
											<i class="far fa-calendar-alt"></i>
										</span>
										<input type="text" class="form-control input-xs" id="date_confirmation" name="date_confirmation" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="DD/MM/YYYY">
									</div>
								</div>
		                    </div>
							<div class="col-md-12">
								<div class="form-group">
		                        	<label class="font_14">Supporting Document (if any)</label>
		                        	<div class="input-group">
		                        		<input type="file" style="display:none" id="corp_supporting_document" class="supporting_document" name="supporting_document">
		                        		<label for="corp_supporting_document" class="btn btn-primary" class="supporting_document">Attachment</label><br/>
		                        		<span class="corp_file_name"></span>
		                        		<input type="hidden" class="corp_hidden_supporting_document" name="hidden_supporting_document" value=""/>
		                        	</div>
		                        </div>
							</div>
						</div>
					</form>
				</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" name="saveRegController" id="saveRegController">Save</button>
				<input type="button" class="btn btn-default " data-dismiss="modal" name="cancelRegController" value="Cancel">
			</div>
		</div>
	</div>
</div>
<div id="modal_nominee_director" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;">
	<div class="modal-dialog">
		<div class="modal-content">
			<header class="panel-heading">
				<h2 class="panel-title">Update Register of Nominee Director</h2>
			</header>
			<form id="form_register_of_nominee_director" autocomplete="off" enctype="multipart/form-data">
				<div class="panel-body register_of_nominee_director">
					<div class="col-md-12">
						<span style="font-size: 1.8rem;"><u>Nominee Director</u></span>
					</div>
					<input type="hidden" id="client_nominee_director_id" value="" name="client_nominee_director_id"/>
					<input type="hidden" id="nomi_company_code" value="" name="nomi_company_code"/>
					<div class="col-md-6">
						<div class="form-group div_nd_id">
                        	<label class="font_14" for="nd_gid_add_controller_officer">Identification No.<span class="color_red">*</span></label>
                            <input style="text-transform:uppercase" type="text" id="nd_gid_add_controller_officer" class="form-control" placeholder="Identification No." value="" name="nd_identification_no" required="required"/>
                            <a class="nd_add_office_person_link" href="" style="cursor:pointer;" id="nd_add_office_person_link" target="_blank" hidden onclick="nd_add_controller_person(this)"><div style="cursor:pointer;">Click here to <span style="font-weight:bold">Add Person</span></div></a>
                            <a class="nd_a_refresh_controller" href="javascript:void(0)" style="cursor:pointer;" id="nd_refresh_controller" onclick="nd_refresh_controller()"><div style="cursor:pointer;"><span class="nd_refresh_controller" style="font-weight:bold" hidden>Refresh</span></div></a>
                            <a class="nd_view_edit_person" href="" style="cursor:pointer;" id="nd_view_edit_person" target="_blank" hidden><div style="cursor:pointer;"><span style="font-weight:bold">View/Edit</span></div></a>
                        </div>
					</div>

					<div class="col-md-6">
						<div class="form-group div_nd_name">
                        	<label class="font_14" for="nd_name">Name<span class="color_red">*</span></label>
                            <input style="text-transform:uppercase" type="text" id="nd_name" class="form-control" placeholder="Name" value="" name="nd_name" required="required" readonly="true" />
                            <!-- <input type="hidden" id="nd_controller_id" value="" name="nd_client_controller_id"/> -->
                            <!-- <input type="hidden" id="nd_company_code" value="" name="nd_company_code"/> -->
                            <input type="hidden" id="nd_officer_id" value="" name="nd_officer_id"/>
                            <input type="hidden" id="nd_officer_field_type" value="" name="nd_officer_field_type"/>
                        </div>
					</div>
					<div class="col-md-12" style="padding-left: 0px !important; padding-right: 0px !important;">
						<div class="col-md-6">
							<div class="form-group div_nd_date_entry">
	                        	<label class="font_14" for="nd_date_entry">Date of entry/update<span class="color_red">*</span></label>
	                            <div class="input-group mb-md" style="width: 100%;">
									<span class="input-group-addon">
										<i class="far fa-calendar-alt"></i>
									</span>
									<input type="text" class="form-control input-xs" id="nd_date_entry" name="nd_date_entry" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="DD/MM/YYYY">
								</div>
	                        </div>
						</div>
					</div>
					<div class="col-md-12">
						<span style="font-size: 1.8rem;"><u>Particulars of Nominator</u></span>
					</div>

                    <div class="col-md-6">
						<div class="form-group div_nomi_id">
                        	<label class="font_14" for="nomi_gid_add_controller_officer">Identification No.<span class="color_red">*</span></label>
                            <input style="text-transform:uppercase" type="text" id="nomi_gid_add_controller_officer" class="form-control" placeholder="Identification No." value="" name="nomi_identification_no" required="required"/>
                            <a class="nomi_add_office_person_link" href="" style="cursor:pointer;" id="nomi_add_office_person_link" target="_blank" hidden onclick="nomi_add_controller_person(this)"><div style="cursor:pointer;">Click here to <span style="font-weight:bold">Add Person</span></div></a>
                            <a class="nomi_a_refresh_controller" href="javascript:void(0)" style="cursor:pointer;" id="nomi_refresh_controller" onclick="nomi_refresh_controller()"><div style="cursor:pointer;"><span class="nomi_refresh_controller" style="font-weight:bold" hidden>Refresh</span></div></a>
                            <a class="nomi_view_edit_person" href="" style="cursor:pointer;" id="nomi_view_edit_person" target="_blank" hidden><div style="cursor:pointer;"><span style="font-weight:bold">View/Edit</span></div></a>
                        </div>
					</div>
					
					<div class="col-md-6">
						<div class="form-group div_nomi_name">
                        	<label class="font_14" for="nomi_name">Name<span class="color_red">*</span></label>
                            <input style="text-transform:uppercase" type="text" id="nomi_name" class="form-control" placeholder="Name" value="" name="nomi_name" required="required" readonly="true" />
                            <!-- <input type="hidden" id="nomi_controller_id" value="" name="nomi_client_controller_id"/> -->
                            <!-- <input type="hidden" id="nomi_company_code" value="" name="nomi_company_code"/> -->
                            <input type="hidden" id="nomi_officer_id" value="" name="nomi_officer_id"/>
                            <input type="hidden" id="nomi_officer_field_type" value="" name="nomi_officer_field_type"/>
                        </div>
					</div>
					<div class="col-md-12" style="padding-left: 0px !important; padding-right: 0px !important;">
						<div class="col-md-6">
							<div class="form-group div_date_become_nominator">
	                        	<label class="font_14" for="date_become_nominator">Date which becomes a nominator<span class="color_red">*</span></label>
	                            <div class="input-group mb-md" style="width: 100%;">
									<span class="input-group-addon">
										<i class="far fa-calendar-alt"></i>
									</span>
									<input type="text" class="form-control input-xs" id="date_become_nominator" name="date_become_nominator" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="DD/MM/YYYY">
								</div>
	                        </div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
	                        	<label class="font_14" for="date_ceased_nominator">Date ceased as nominator</label>
	                            <div class="input-group mb-md" style="width: 100%;">
									<span class="input-group-addon">
										<i class="far fa-calendar-alt"></i>
									</span>
									<input type="text" class="form-control input-xs" id="date_ceased_nominator" name="date_ceased_nominator" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="DD/MM/YYYY">
								</div>
	                        </div>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
                        	<label class="font_14">Supporting Document (if any)</label>
                        	<div class="input-group">
                        		<input type="file" style="display:none" id="nd_supporting_document" class="nd_supporting_document" name="nd_supporting_document">
                        		<label for="nd_supporting_document" class="btn btn-primary">Attachment</label><br/>
                        		<span class="nd_file_name"></span>
                        		<input type="hidden" class="nd_hidden_supporting_document" name="nd_hidden_supporting_document" value=""/>
                        	</div>
                        </div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" name="saveRegNomineeDirector" id="saveRegNomineeDirector">Save</button>
					<input type="button" class="btn btn-default " data-dismiss="modal" name="cancelRegController" value="Cancel">
				</div>
			</form>
		</div>
	</div>
</div>
<div id="modal_import_client_to_qb" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;">
	<div class="modal-dialog">
		<div class="modal-content">
			<header class="panel-heading">
				<h2 class="panel-title">Import to Quickbook Online</h2>
			</header>
			<form id="form_import_client_to_qb">
				<div class="panel-body">
					<div style="height: 300px;">
						<h5>Please choose one currency before import this data to Quickbook Online.</h5>
						<div class="div_qb_currencySelector"><select id="client_qb_currency" class="form-control client_qb_currency" style="width: 100%;" name="client_qb_currency"><option value="0">Select Currency</option></select></div>
						<h5 class="help-block">* All the client will be under the currecy you choose.</h5>
						<h5 class="client_qbs" style="display: none">Now Quickbook Online has the following client:</h5>
						<ul class="client_qbs client_qb_data" style="display: none"></ul>
						<input type="hidden" name="client_id_to_qb" class="client_id_to_qb">
					</div>
				</div>
			</form>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" name="saveImportClientToQB" id="saveImportClientToQB">Import</button>
				<input type="button" class="btn btn-default" data-dismiss="modal" name="cancelImportClientToQB" value="Cancel">
			</div>
		</div>
	</div>
</div>
<script>
var client = <?php echo json_encode(isset($client) ? $client : '');?>;
var client_officers = <?php echo json_encode(isset($client_officers) ? $client_officers : '');?>;
var client_guarantee = <?php echo json_encode(isset($client_guarantee) ? $client_guarantee : '');?>;
var client_controller = <?php echo json_encode(isset($client_controller) ? $client_controller : '');?>;
var client_nominee_director = <?php echo json_encode(isset($client_nominee_director) ? $client_nominee_director : '');?>;
var client_charges = <?php echo json_encode(isset($client_charges) ? $client_charges : '');?>;
var client_share_capital = <?php echo json_encode(isset($client_share_capital) ? $client_share_capital : '');?>;
var client_billing_info = <?php echo json_encode(isset($client_billing_info) ? $client_billing_info : '');?>;
var service_category_list;
var client_signing_info = <?php echo json_encode(isset($client_signing_info) ? $client_signing_info : '');?>;
var client_contact_info = <?php echo json_encode(isset($client_contact_info) ? $client_contact_info : '');?>;
var client_contact_info_email = <?php echo json_encode(isset($client_contact_info[0]->client_contact_info_email) ? $client_contact_info[0]->client_contact_info_email : '');?>;
var client_selected_reminder = <?php echo json_encode(isset($client_reminder_info) ? $client_reminder_info : '') ?>;
var client_selected_setup_group = <?php echo json_encode(isset($client_setup_group_info) ? $client_setup_group_info : '') ?>;
var client_selected_related_party = <?php echo json_encode(isset($client_setup_related_party_info) ? $client_setup_related_party_info : '') ?>;
var client_contact_info_phone = <?php echo json_encode(isset($client_contact_info[0]->client_contact_info_phone) ? $client_contact_info[0]->client_contact_info_phone : '');?>;
var filing_data = <?php echo json_encode(isset($filing_data) ? $filing_data : '');?>;
var eci_filing_data = <?php echo json_encode(isset($eci_filing_data) ? $eci_filing_data : '');?>;
var tax_filing_data = <?php echo json_encode(isset($tax_filing_data) ? $tax_filing_data : '');?>;
var gst_filing_data = <?php echo json_encode(isset($gst_filing_data) ? $gst_filing_data : '');?>;
var firm_info = <?php echo json_encode(isset($firm_info) ? $firm_info : '');?>;

var template = <?php echo json_encode(isset($template) ? $template : '');?>;
var company_code = "<?php echo ($company_code);?>";
var access_right_client_module = <?php echo json_encode(isset($client_module) ? $client_module : '');?>;
console.log("access_right_client_module", access_right_client_module);
var access_right_company_info_module = <?php echo json_encode($company_info_module);?>;
var access_right_officer_module = <?php echo json_encode($officer_module);?>;
var access_right_member_module = <?php echo json_encode($member_module);?>;
var access_right_controller_module = <?php echo json_encode($controller_module);?>;
var access_right_charge_module = <?php echo json_encode($charges_module);?>;
var access_right_filing_module = <?php echo json_encode($filing_module);?>;
var access_right_register_module = <?php echo json_encode($register_module);?>;
var access_right_setup_module = <?php echo json_encode($setup_module);?>;
var company_type = <?php echo json_encode(isset($client->company_type) ? $client->company_type : '')?>;
var client_status = <?php echo json_encode(isset($client->status) ? $client->status : '')?>;
var first_time = <?php echo json_encode($first_time)?>;
var admin = <?php echo json_encode($Admin)?>;
var manager = <?php echo json_encode($Manager)?>;
var corp_rep_data = <?php echo json_encode(isset($corp_rep_data) ? $corp_rep_data : ''); ?>;
var date = new Date();
var tab = <?php echo json_encode(isset($tab) ? $tab : '');?>;
var base_url = '<?php echo base_url() ?>';
var login_user_id = <?php echo json_encode(isset($login_user_id) ? $login_user_id : ''); ?>;

// console.log(login_user_id);
</script>
<script src="themes/default/assets/js/intlTelInput.js?v=30eee4fc8d1b59e4584b0d39edfa2082" /></script>
<script src="themes/default/assets/js/defaultCountryIp.js?v=30eee4fc8d1b59e4584b0d39edfa2082" /></script>
<script src="themes/default/assets/js/utils.js?v=30eee4fc8d1b59e4584b0d39edfa2082" /></script>
<script src="themes/default/assets/js/companyType.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
<script src="themes/default/assets/js/officers_tab.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
<script src="themes/default/assets/js/controller_tab.js?v=7434fdfsdfdrw32323233w1323" charset="utf-8"></script>
<script src="themes/default/assets/js/nominee_director_tab.js?v=7434fdfsdfdrw32323233w1323" charset="utf-8"></script>
<script src="themes/default/assets/js/charges_tab.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
<script src="themes/default/assets/js/members_tab.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
<script src="themes/default/assets/js/guarantee_tab.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
<script src="themes/default/assets/js/setup_tab.js?v=40eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
<script src="themes/default/assets/js/billing_tab.js?v=50eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
<script src="themes/default/assets/js/eci_filing_tab.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
<script src="themes/default/assets/js/tax_filing_tab.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
<script src="themes/default/assets/js/filing_tab.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
<script src="themes/default/assets/js/gst_filing_tab.js?v=4032323234fc8323d1b59e4584b0d39edfa2632" charset="utf-8"></script>
<script src="themes/default/assets/js/register_tab.js?v=40432432434ffdf33eew584b0d39edfa2082" charset="utf-8"></script>
<script src="themes/default/assets/js/list_of_confirmation_to_auditor.js?v=40432432434ffdf33eew584b0d39edfa2082" charset="utf-8"></script>
<script src="themes/default/assets/js/company_document.js?v=40432432434ffdf33eew584b0d39edfa2082" charset="utf-8"></script>
<!-- <script src="themes/default/assets/js/addpersonprofile.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script> -->
<script>
$tab_aktif = "companyInfo";

var setup_section, submit_setup_link, submit_setup_data;

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

//console.log(client);

incorp_coll = document.getElementsByClassName("collapsible");

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

if(client)
{
	if(client["foreign_add_1"] != "")//(client["postal_code"] != "" && client["street_name"] != "")
	{
		$(".div_foreign").removeClass("hidden");
		$(".div_local").addClass("hidden");
		$('#use_foreign_add_as_billing_add').prop("disabled", false);
		$('#postal_code').prop("disabled", true);
		$('#street_name').prop("disabled", true);

		$('#foreign_add_1').prop("disabled", false);
		$('#foreign_add_2').prop("disabled", false);
	}
	else
	{
		$(".div_local").removeClass("hidden");
		$(".div_foreign").addClass("hidden");
		$('#use_foreign_add_as_billing_add').prop("disabled", true);
		$('#postal_code').prop("disabled", false);
		$('#street_name').prop("disabled", false);

		$('#foreign_add_1').prop("disabled", true);
		$('#foreign_add_2').prop("disabled", true);
	}
}
else
{
	if(firm_info)
	{
		if(firm_info[0]["currency_name"] != "SGD")
		{
			$(".div_foreign").removeClass("hidden");
			$(".div_local").addClass("hidden");
			$('#use_foreign_add_as_billing_add').prop("disabled", false);
			$('#postal_code').prop("disabled", true);
			$('#street_name').prop("disabled", true);

			$('#foreign_add_1').prop("disabled", false);
			$('#foreign_add_2').prop("disabled", false);
		}
		else
		{
			$('#use_foreign_add_as_billing_add').prop("disabled", true);
			$('#postal_code').prop("disabled", false);
			$('#street_name').prop("disabled", false);

			$('#foreign_add_1').prop("disabled", true);
			$('#foreign_add_2').prop("disabled", true);
		}
	}
}

for (var g = 0; g < incorp_coll.length; g++) {
	incorp_coll[g].classList.toggle("incorp_active");
	incorp_coll[g].nextElementSibling.style.maxHeight = "100%";
}

for (var i = 0; i < incorp_coll.length; i++) {
	incorp_coll[i].addEventListener("click", function() {
	this.classList.toggle("incorp_active");
	var content = this.nextElementSibling;
	if (content.style.maxHeight){
	  content.style.maxHeight = null;
	} else {
	  content.style.maxHeight = "100%";
	} 
	});
}

$(document).ready(function () {
	var setup_coll = document.getElementsByClassName("setup_collapsible");

	for (var g = 0; g < setup_coll.length; g++) {
		if(setup_coll[g].classList[1] != "group_related_party")
		{
			setup_coll[g].classList.toggle("setup_active");
			setup_coll[g].nextElementSibling.style.maxHeight = "100%";
		}
	}

	for (var i = 0; i < setup_coll.length; i++) {
		setup_coll[i].addEventListener("click", function() {
			this.classList.toggle("setup_active");
			var content = this.nextElementSibling;
			if (content.style.maxHeight){
			  content.style.maxHeight = null;
			} else {
			  content.style.maxHeight = "100%";
			  if(this.classList[1] == "group_related_party")
			  {
			   	search_related_group_function();
			  }
			} 
		});
	}
});

var controller_coll = document.getElementsByClassName("controller-collapsible");

for (var g = 0; g < controller_coll.length; g++) {
	controller_coll[g].classList.toggle("controller-active");
	controller_coll[g].nextElementSibling.style.maxHeight = "100%";
}

for (var i = 0; i < controller_coll.length; i++) {
	controller_coll[i].addEventListener("click", function() {
		this.classList.toggle("controller-active");
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
	if(!admin && !manager)
	{
		console.log("client", client);
		console.log("not admin not manager");
		// comment out by pps
		// $(".acquried_by").attr('disabled', 'disabled');
	}
}

var business_activity_list_data = JSON.parse(localStorage.getItem("business_activity_list"));

$('#activity1').autoSuggestList({
	list: business_activity_list_data,
});

$('#activity2').autoSuggestList({
	list: business_activity_list_data,
});

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

$('.company_info_date').datepicker().datepicker('setStartDate', '01/01/1960');
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
	console.log("client_status", client_status);
	console.log("first_time", first_time);
	console.log("TTTTTTTTTTTTTT");
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
	console.log("access_right_client_module", access_right_client_module);
	// toastr.error("You have only read access.", "Error");
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
	console.log("access_right_company_info_module", access_right_company_info_module);
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

//service_foreign_reg_off

// if(firm_info[0]["currency_name"] != "SGD")
// 		{
// 			$(".div_foreign").removeClass("hidden");
// 			$(".div_local").addClass("hidden");

var registered_address_info = <?php echo json_encode($registered_address_info);?>;

$.each(registered_address_info, function(key, val) {
	if(firm_info[0]["currency_name"] != "SGD" || (client && client["foreign_add_1"] != ""))
	{
		if(val['jurisdiction_name'].toUpperCase() != "SINGAPORE")
		{
			var option = $('<option />');
			option.attr('data-foreign_address_1', val['foreign_address_1']).attr('data-foreign_address_2', val['foreign_address_2']).attr('data-foreign_address_3', val['foreign_address_3']).attr('value', val['id']).text(val['service_name']);
			if(client != null)
			{
			    if(client['our_service_regis_address_id'] != undefined && val['id'] == client['our_service_regis_address_id'])
			    {
			        option.attr('selected', 'selected');
			    }
			}
			$("#service_foreign_reg_off").append(option);
			$("#service_reg_off").attr('disabled', true);
		}
	}
	else
	{
		if(val['jurisdiction_name'].toUpperCase() == "SINGAPORE")
		{
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
			$("#service_foreign_reg_off").attr('disabled', true);
		}
	}
});

$(document).on('change','#service_reg_off',function(e){
	var postal_codeValue = $(this).find(':selected').data('postal_code');
	var street_nameValue = $(this).find(':selected').data('street_name');
	var building_nameValue = $(this).find(':selected').data('building_name');
	var unit_no1Value = $(this).find(':selected').data('unit_no1');
	var unit_no2Value = $(this).find(':selected').data('unit_no2');

	if(postal_codeValue == undefined || street_nameValue == undefined)
	{
		document.getElementById("postal_code").value = "";
		document.getElementById("street_name").value = "";
		document.getElementById("building_name").value = "";
		document.getElementById("unit_no1").value = "";
		document.getElementById("unit_no2").value = "";
	}
	else
	{
		document.getElementById("postal_code").value = postal_codeValue;
		document.getElementById("street_name").value = street_nameValue;
		document.getElementById("building_name").value = building_nameValue;
		document.getElementById("unit_no1").value = unit_no1Value;
		document.getElementById("unit_no2").value = unit_no2Value;
	}
});

$(document).on('change','#service_foreign_reg_off',function(e){
	var foreign_address_1Value = $(this).find(':selected').data('foreign_address_1');
	var foreign_address_2Value = $(this).find(':selected').data('foreign_address_2');
	var foreign_address_3Value = $(this).find(':selected').data('foreign_address_3');

	if(foreign_address_1Value == undefined || foreign_address_2Value == undefined)
	{
		document.getElementById("foreign_add_1").value = "";
		document.getElementById("foreign_add_2").value = "";
		document.getElementById("foreign_add_3").value = "";
	}
	else
	{
		document.getElementById("foreign_add_1").value = foreign_address_1Value;
		document.getElementById("foreign_add_2").value = foreign_address_2Value;
		document.getElementById("foreign_add_3").value = foreign_address_3Value;
	}

	$( '#form_foreign_add_1' ).html("");
	$( '#form_foreign_add_2' ).html("");
	$( '#form_foreign_add_3' ).html("");
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

	$("#service_reg_off").attr('disabled', false);
	$("#service_foreign_reg_off").attr('disabled', true);
}

function fillForeignRegisteredAddressInput(cbox)
{
	if (cbox.checked) {
		//console.log(cbox);
		$(".service_foreign_reg_off_area").show();
		// document.getElementById("postal_code").value = registered_address_info[0].postal_code;
		// document.getElementById("street_name").value = registered_address_info[0].street_name;
		// document.getElementById("building_name").value = registered_address_info[0].building_name;
		// document.getElementById("unit_no1").value = registered_address_info[0].unit_no1;
		// document.getElementById("unit_no2").value = registered_address_info[0].unit_no2;

		document.getElementById("foreign_add_1").readOnly = true;
		document.getElementById("foreign_add_2").readOnly = true;
		document.getElementById("foreign_add_3").readOnly = true;

		$( '#form_foreign_add_1' ).html("");
		$( '#form_foreign_add_2' ).html("");
		$( '#form_foreign_add_3' ).html("");
	}
	else
	{
		$(".service_foreign_reg_off_area").hide();
		$("#service_foreign_reg_off").val(0);

		document.getElementById("foreign_add_1").value = "";
		document.getElementById("foreign_add_2").value = "";
		document.getElementById("foreign_add_3").value = "";

		document.getElementById("foreign_add_1").readOnly = false;
		document.getElementById("foreign_add_2").readOnly = false;
		document.getElementById("foreign_add_3").readOnly = false;
	}

	$("#service_foreign_reg_off").attr('disabled', false);
	$("#service_reg_off").attr('disabled', true);
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





var client_info = <?php echo json_encode(isset($client) ? $client : '');?>;
var Individual = <?php echo json_encode(isset($Individual) ? $Individual : '');?>;
var Client = <?php echo json_encode(isset($Client) ? $Client : '');?>;
//console.log(client_info["registered_address"]);
if(client_info != null && access_right_company_info_module != "none")
{	
	if((!Individual && Individual == true) || (!Individual && Individual == "" && !Client))
	{
		if(client_info["registered_address"] == 1)
		{
			if(client["foreign_add_1"] != "")
			{
				$(".service_foreign_reg_off_area").show();
				document.getElementById("foreign_add_1").readOnly = true;
		      	document.getElementById("foreign_add_2").readOnly = true;
		      	document.getElementById("foreign_add_3").readOnly = true;
			}
			else
			{
				$(".service_reg_off_area").show();
				document.getElementById("postal_code").readOnly = true;
		      	document.getElementById("street_name").readOnly = true;
		      	document.getElementById("building_name").readOnly = true;
		      	document.getElementById("unit_no1").readOnly = true;
		      	document.getElementById("unit_no2").readOnly = true;
		    }
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

	$(".client_footer_button").hide();
	$tab_aktif = "filing";
}
else if(tab == "setup")
{
	$('#myTab #li-companyInfo').removeClass("active");
	$('.tab-content #w2-companyInfo').removeClass("active");

	$('#myTab #li-setup').addClass("active");
	$('.tab-content #w2-setup').addClass("active");

	$(".client_footer_button").hide();
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
	$('#loadingmessage').show();
    $.ajax({ //Upload common input
        url: "masterclient/get_client_code",
        type: "POST",
        data: {"company_name": $("#edit_company_name").val()},
        dataType: 'json',
        success: function (response) {
        	$('#loadingmessage').hide();
        	var new_text = "Previous client code: " + response["previous_client_code"] + "<br>" + "Suggestion client code: " + response["latest_client_code"];
        	$(".client_code_div .errspan").attr("data-original-title", new_text);
        }
    });
	
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
$(".activity1").live('change',function(){
	$( '#form_activity1' ).html("");
});
$("#activity2").live('change',function(){
	$( '#form_activity2' ).html("");
});
$("#postal_code").live('change',function(){
	$( '#form_postal_code' ).html("");
});
$("#street_name").live('change',function(){
	$( '#form_street_name' ).html("");
});
$("#foreign_add_1").live('change',function(){
	$( '#form_foreign_add_1' ).html("");
});
$("#foreign_add_2").live('change',function(){
	$( '#form_foreign_add_2' ).html("");
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
});

$('.nav li').not('.active').addClass('disabled');    

if(client)
{
	$('.nav li').removeClass('disabled');
}
else
{
	$('.disabled').click(function (e) {
        e.preventDefault();

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

$(document).on('submit', function (e) {
	console.log("Submit");
    e.preventDefault();
    var link;
    var form = $("#w2-"+$tab_aktif+" form");
    var form_data; 

    if($tab_aktif == "companyInfo")
	{
		link = "masterclient/save";
		$(".acquried_by").attr("disabled", false);
		$("#client_code").attr("disabled", false);
		$("#registration_no").attr("disabled", false);
		$("#edit_company_name").attr("disabled", false);
		$("input[name='change_name_effective_date']").attr("disabled", false);
		$("#former_name").attr("disabled", false);
		$("input[name='incorporation_date']").attr("disabled", false);
		$("#company_type").attr("disabled", false);
		$("#activity1").attr("disabled", false);
		$("#description1").attr("disabled", false);
		$("#activity2").attr("disabled", false);
		$("#description2").attr("disabled", false);
		$("input[name='use_registered_address']").attr("disabled", false);
		if($("#postal_code").val())
		{
			$("#service_reg_off").attr("disabled", false);
			$("#service_foreign_reg_off").attr('disabled', true);
		}
		else
		{
			$("#service_reg_off").attr("disabled", true);
			$("#service_foreign_reg_off").attr('disabled', false);
		}
		$("#postal_code").attr("disabled", false);
		$("#street_name").attr("disabled", false);
		$("#building_name").attr("disabled", false);
		$("#unit_no1").attr("disabled", false);
		$("#unit_no2").attr("disabled", false);
		form_data = form.serialize();
	}
	else if($tab_aktif == "setup") 
	{
		if(setup_section == "signing_information_form")
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

	        link = "masterclient/submit_signing_information";
	        form_data = $("#signing_information_form").serialize();
	    }
	    else if(setup_section == "contact_information_form")
	    {
	    	link = "masterclient/submit_contact_information";
	        form_data = $("#contact_information_form").serialize();
	    }
	    else if(setup_section == "reminder_form")
	    {
	    	link = "masterclient/submit_reminder";
	        form_data = $("#reminder_form").serialize();
	    }
	    else if(setup_section == "corporate_representative_form")
	    {
	    	link = "masterclient/submit_corporate_representative";
	        form_data = $("#corporate_representative_form").serialize();
	    }
	    else if(setup_section == "related_group_form")
	    {
	    	link = "masterclient/submit_related_group";
	        form_data = $("#related_group_form").serialize();
	    }

		// var $form = $(e.target);

		//       // and the FormValidation instance
		//       var fv = $form.data('formValidation');
		//       // Get the first invalid field
		//          var $invalidFields = fv.getInvalidFields().eq(0);
		//          // Get the tab that contains the first invalid field
		//          var $tabPane     = $invalidFields.parents('.tab-pane');
		//       var valid_setup = fv.isValidContainer($tabPane);

		//       if(valid_setup == false)
		//       {
		//       	toastr.error("Please complete all required field", "Error");
		//       }

				// link = "masterclient/add_setup_info";
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

		link = "masterclient/add_client_billing_info";
		form_data = form.serialize() + '&array_client_billing_info_id=' + JSON.stringify(array_client_billing_info_id);
	}

	if($tab_aktif == "companyInfo" || setup_section == "contact_information_form" || setup_section == "reminder_form" || setup_section == "corporate_representative_form" || setup_section == "related_group_form" || valid_setup == true)
	{
		if(valid_setup == true)
		{
			var num = []; 
			$("#body_billing_info").find('div').each(function(e) {

				if($(this).find("input#to").prop('disabled'))
				{
					num.push($(this).attr("num"));
				}

		        $(this).find("input#to").removeAttr('disabled');
		        $(this).find("input#from").removeAttr('disabled');
		    });
		}
	    
		
		if(client)
		{
			if (client["status"] == "1")
			{
				$(".acquried_by").attr('disabled', false);
			}
		}

		// if($tab_aktif == "billing")
		// {
		// 	var form_data = form.serialize() + '&array_client_billing_info_id=' + JSON.stringify(array_client_billing_info_id);
		// }
		// else
		// {
		// 	var form_data = form.serialize();
		// }
		
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

							//billing(response.client_billing["client_billing_data"]);
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
								console.log("Not admin");
                    			$(".acquried_by").attr('disabled', 'disabled');
                    		}

                    		company_type =  $("#company_type").val();

                    		$(".latest_client_id").val(response.client_id);
                    		$(".related_group_client_id").val(response.client_id);

                    		$.each(response.currency, function(key, val) {
			                    var option = $('<option />');
			                    option.attr('value', key).text(val);
			                    $('.client_qb_currency').append(option);
			                });
                    		
			                $(".client_id_to_qb").val(response.client_id);

			                let client_qb_data_arr = response.check_client_qb_info;
			                if(client_qb_data_arr.length > 0)
			                {
			                	$(".client_qbs").show();
			                	$('.list_qb_cus_name').remove();
			                	$.each(client_qb_data_arr, function(key, val) {
				                    var option = "<li class='list_qb_cus_name'>"+client_qb_data_arr[key]["company_name"] +" ("+ client_qb_data_arr[key]["currency_name"] +")"+"</li>";
				                    $('.client_qb_data').append(option);
				                });
			                }
			                else
			                {
			                	$(".client_qbs").hide();
			                }

			                if(response.qb_company_id != "" && response.qb_company_id != null)
			                {
                    			$('#modal_import_client_to_qb').modal('toggle');
			                }
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
                    	if(setup_section == "contact_information_form")
	    				{
	    					toastr.warning(response.message, response.title);
	    				}
	    				else
	    				{
                    		toastr.error(response.message, response.title);
	    				}
                    }
                    else if (response.Status === 3) {
                    	if(setup_section == "contact_information_form")
	    				{
	    					toastr.error(response.message, response.title);
	    				}
	    				else
	    				{
	                    	toastr.success(response.message, response.title);
	                    	location.reload();
	                    }
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

	                    	if (response.error["foreign_add_1"] != "")
	                    	{
	                    		var errorsForeignAdd1 = '<span class="help-block">*' + response.error["foreign_add_1"] + '</span>';
	                    		$( '#form_foreign_add_1' ).html( errorsForeignAdd1 );

	                    	}
	                    	else
	                    	{
	                    		var errorsForeignAdd1 = '';
	                    		$( '#form_foreign_add_1' ).html( errorsForeignAdd1 );
	                    	}

	                    	if (response.error["foreign_add_2"] != "")
	                    	{
	                    		var errorsForeignAdd2 = '<span class="help-block">*' + response.error["foreign_add_2"] + '</span>';
	                    		$( '#form_foreign_add_2' ).html( errorsForeignAdd2 );

	                    	}
	                    	else
	                    	{
	                    		var errorsForeignAdd2 = '';
	                    		$( '#form_foreign_add_2' ).html( errorsForeignAdd2 );
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

$(document).on("click","#saveImportClientToQB",function(element) {
	var client_id = $(this).parent().parent().find('.client_id_to_qb').val(); 
    var client_qb_currency = $(this).parent().parent().find('.client_qb_currency').val(); 

    if(client_qb_currency != 0)
    {
        $('#loadingmessage').show();
        $.ajax({
            type: 'POST',
            url: "masterclient/import_qb_client_to_quickbook",
            data: {"client_id": client_id, "client_qb_currency": client_qb_currency},
            dataType: 'json',
            success: function(response){
                $('#loadingmessage').hide();
                if(response.Status == 1)
                {
                    //$('#modal_import_services_to_qb').modal('toggle');
                    toastr.success(response.message, response.title);
                } 
                else if(response.Status == 2)
                {
                    toastr.warning(response.message, response.title);
                }
                else if(response.Status == 3)
                {
                    toastr.error(response.message, response.title);
                }
            }
        });
    }
    else
    {
        toastr.error("Please select one Income Account before you save.", "Error");
    }
});

$(document).on('click',".check_stat",function() {

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
		}

		if($tab_aktif == "register")
		{
			search_register_function();
		}

		if($tab_aktif == "billing")
		{
			search_billing_function();
		}

		if($tab_aktif == "officer" || $tab_aktif == "capital" || $tab_aktif == "charges" || $tab_aktif == "filing" || $tab_aktif == "register" || $tab_aktif == "controller" || $tab_aktif == "setup" || $tab_aktif == "setup" || $tab_aktif == "others")
		{
			//console.log($("#client_footer_button"));
			$(".client_footer_button").hide();
		}
		else
		{
			$(".client_footer_button").show();
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

		console.log("check_client_data")

		$.ajax({
			type: "POST",
			url: "masterclient/check_client_data",
			data: $("#w2-companyInfo form").serialize(), // <--- THIS IS THE CHANGE
			// dataType: "json",
			async: false,
			success: function(response)
			{
				console.log(response)
				if (response == "Duplicate Registration No") {
					// alert("Registration number is already exist!");
					toastr.error("Registration number is already exist!", "Error");
				} else
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

				// if (response == "Duplicate Registration No") {
				// 	alert("Registration number is already exist!");
				// } else {
				// 	$.ajax({
				// 		type: "POST",
				// 		url: "masterclient/update_client",
				// 		data: $("#w2-companyInfo form").serialize(), // <--- THIS IS THE CHANGE
				// 		// dataType: "json",
				// 		async: false,
				// 		success: function(response)
				// 		{
				// 			if (response) {
				// 				window.location.reload();
				// 			}
				// 		}
				// 	})
				// }
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
if(typeof localStorage.getItem('slitems') == "undefined")
{
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
});


if (parseInt(login_user_id) == 120) { // for Rayce user id is 120
	setTimeout(() => {
		$('select[name="acquried_by"]').removeAttr('disabled');
	}, 1000);
}
</script>
