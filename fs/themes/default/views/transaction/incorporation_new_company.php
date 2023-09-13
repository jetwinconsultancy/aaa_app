<?php
	$now = getDate();
	if ($this->session->userdata('transaction_company_code') && $this->session->userdata('transaction_company_code') != '')
	{
		$transaction_company_code = $this->session->userdata('transaction_company_code');
		//echo json_encode($company_code);
	} 
	else 
	{
		$transaction_company_code = 'company_'.$now[0];
		$this->session->set_userdata('transaction_company_code', $transaction_company_code);
	}
	
	
?>
<section class="panel" style="margin-bottom: 0px;">
	<div class="panel-body">
		<div class="modal-wrapper">
			<div class="modal-text">

				<button type="button" class="collapsible"><span style="font-size: 2.4rem;">Company Information</span></button>
				<div id="w2-companyInfo" class="incorp_content">
				  <?php echo form_open_multipart('', array('id' => 'upload_company_info', 'enctype' => "multipart/form-data")); ?>

						<div class="hidden"><input type="text" class="form-control transaction_company_code" name="transaction_company_code" value="<?=$transaction_company_code?>"/></div>
						
						<!-- <div class="form-group" style="padding: 0 18px; margin-top: 20px;">
							<span style="font-size: 2.4rem;">Company Profile</span>
						</div> -->
						<div class="form-group" style="margin-top: 20px;">
							<label class="col-sm-2" for="w2-username">Client Code: </label>
							<div class="col-sm-4">
								<input type="text" style="text-transform:uppercase" class="form-control" maxlength="20" id="client_code" name="client_code" value="<?=$transaction_client->client_code?>" >
								<div id="form_client_code"></div>
								
							</div>
							<!-- <div class="col-sm-4">
								
									<div style="float: right;">
										<select id="acquried_by" class="form-control acquried_by" style="text-align:right; text-transform:uppercase;" name="acquried_by">
						                    <option value="0">Acquried by:</option>
						                </select>
						                <div id="form_acquried_by"></div>
									</div>
								
							</div> -->
						</div>
						
						<div class="form-group">
							<label class="col-sm-2" for="w2-username">Company Name: </label>
							<div class="col-sm-8">
								<input type="text" style="text-transform:uppercase" class="form-control" id="edit_company_name" name="company_name"  value="<?=$transaction_client->company_name?>">
								<div id="form_company_name"></div>
								
							</div>
						</div>
						<!-- <div class="form-group">
							<label class="col-sm-4 control-label" for="w2-username">Former Name (if any): </label>
							<div class="col-sm-8">
								<textarea class="form-control" style="text-transform:uppercase" id="former_name" name="former_name" /><?=$client->former_name?></textarea>
								<div id="form_former_name"></div>
								
							</div>
						</div> -->
						
						<!-- <div class="form-group">
							<label class="col-sm-4 control-label" for="w2-username">Incorporation Date: </label>
							<div class="col-sm-8">
								<div class="input-group mb-md" style="width: 200px;">
									<span class="input-group-addon">
										<i class="far fa-calendar-alt"></i>
									</span>
									<input type="text" class="form-control valid" id="date_todolist" name="incorporation_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" required="" value="<?=$client->incorporation_date?>" placeholder="DD/MM/YYYY">
									

									
								</div>
								<div id="form_incorporation_date"></div>
								
							</div>
						</div> -->
						<div class="form-group">
							<label class="col-sm-2" for="w2-username">Company Type: </label>
							<div class="col-sm-8">
								<select id="company_type" class="form-control company_type" style="text-align:right; width: 400px;" name="company_type">
				                    <option value="0">Select Company Type</option>
				                </select>
				                <div id="form_company_type"></div>
				             

				                

								
							</div>
						</div>
						<!-- <div class="form-group">
							<label class="col-sm-4 control-label" for="w2-username">Status: </label>
							<div class="col-sm-8">
								
								<select id="status" class="form-control status" style="text-align:right;" name="status">
				                    <option value="0">Select Status</option>
				                </select>
				                <div id="form_status"></div>
									
								
							</div>
						</div> -->
						<!-- <span style="font-size: 2.4rem;padding: 0; margin: 7px 0px 4px 0;">Principal Activities</span> -->
						<div class="form-group" style="margin-top: 20px">
							<label class="col-sm-2" for="w2-username">Principal Activity 1: </label>
							<div class="col-sm-8">
								<input type="text" style="text-transform:uppercase" class="form-control" id="activity1" name="activity1" value="<?=$transaction_client->activity1?>" >
								<div id="form_activity1"></div>
								
							</div>
							
						</div>
						
						<div class="form-group">
							<label class="col-sm-2" for="w2-username">Principal Activity 2: </label>
							<div class="col-sm-8">
								<input type="text" style="text-transform:uppercase" class="form-control" id="activity2" name="activity2" value="<?=$transaction_client->activity2?>" >
								<div id="form_activity2"></div>
								
							</div>
							
						</div>
						
						<!-- <span style="font-size: 2.4rem;padding: 0; margin: 7px 0px 4px 0;">Registered Office Address</span> -->
						<div class="form-group" style="margin-top: 20px">
							<label class="col-xs-2" for="w2-username">Use Our Registered Address: </label>
							<div class="col-xs-8">
								<div class="col-xs-1">
									<input type="checkbox" class="" id="use_registered_address" name="use_registered_address" onclick="fillRegisteredAddressInput(this);">
								</div>
								<div class="col-xs-6 service_reg_off_area" style="display: none;">
									<select id="service_reg_off" class="form-control service_reg_off" style="text-align:right;" name="service_reg_off">
					                    <option value="0">Select Service Name</option>
					                </select>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2" for="w2-username">Registered Office Address: </label>
							<div class="col-sm-8">
								<div style="width: 100%;">
									<div style="width: 15%;float:left;margin-right: 20px;">
										<label>Postal Code :</label>
									</div>
									<div style="width: 65%;float:left;margin-bottom:5px;">
										<div class="" style="width: 20%;" >
											<input type="text" style="text-transform:uppercase" class="form-control" id="postal_code" name="postal_code" value="" maxlength="6">
										</div>
										<div id="form_postal_code"></div>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2" for="w2-username"></label>
							<div class="col-sm-8">
								<div style="width: 100%;">
									<div style="width: 15%;float:left;margin-right: 20px;">
										<label>Street Name :</label>
									</div>
									<div style="width: 71%;float:left;margin-bottom:5px;">
										<div class="" style="width: 100%;" >
											<input type="text" style="text-transform:uppercase" class="form-control" id="street_name" name="street_name" value="<?=$transaction_client->street_name?>">
										</div>
										<div id="form_street_name"></div>
					
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2" for="w2-username"></label>
							<div class="col-sm-8">
								<div style="width: 100%;">
									<div style="width: 15%;float:left;margin-right: 20px;">
										<label>Building Name :</label>
									</div>
									<div style="width: 71%;float:left;margin-bottom:5px;">
										<div class="" style="width: 100%;" >
											<input type="text" style="text-transform:uppercase" class="form-control" id="building_name" name="building_name" value="<?=$transaction_client->building_name?>">
										</div>
										<div id="form_street_name"></div>
					
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2" for="w2-username"></label>
							<div class="col-sm-8">
								<label style="width: 15%;float:left;margin-right: 20px;">Unit No :</label>
								<input style="width: 8%; float: left; margin-right: 10px; text-transform:uppercase;" type="text" class="form-control" id="unit_no1" name="unit_no1" value="<?=$transaction_client->unit_no1?>" maxlength="3">
								<label style="float: left; margin-right: 10px;" >-</label>
								<input style="width: 14%; text-transform:uppercase;" type="text" class="form-control" id="unit_no2" name="unit_no2" value="<?=$transaction_client->unit_no2?>" maxlength="10">
							</div>
						</div>
						<div class="form-group hidden">
							<label class="col-sm-2" for="w2-username">Listed Company: </label>
							<div class="col-sm-8">
								<input type="checkbox" class="" id="listedcompany" name="listedcompany" <?=$transaction_client->listed_company?'checked':'';?> />
							</div>
						</div>
					<?= form_close(); ?>
					<div class="form-group">
						<div class="col-sm-12">
							<input type="button" class="btn btn-primary submitCompanyInfo" id="submitCompanyInfo" value="Save" style="float: right; margin-bottom: 20px;">
						</div>
					</div>
				</div>
				<button type="button" class="collapsible"><span style="font-size: 2.4rem;">Officers</span></button>
				<div id="w2-officer" class="incorp_content">
					  <!-- <div style="margin-bottom: 23px; margin-top: 20px;"> -->
						<!-- <form id="filter_position"> -->
							<!-- <span style="font-size: 2.4rem;padding: 0; margin: 7px 0 4px 0;">Officers</span> -->

								<!-- <div class="hidden"><input type="text" class="form-control" name="transaction_company_code" value="<?=$transaction_company_code?>"/></div>
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
							
								<div style="font-size: 1.4rem;float:right;padding-top:5px; padding-right: 5px; height: 30px;"><span>Filter: </span></div> -->
						<!-- </form> -->
					<!-- </div> -->
					<form id="officer_form" style="margin-top: 20px;">
						<div class="hidden"><input type="text" class="form-control" name="company_code" value="<?=$transaction_company_code?>"/></div>
						<table class="table table-bordered table-striped table-condensed mb-none" id="officer_table" style="width: 1000px">
							<thead>
								<tr>
									<th id="id_position" style="text-align: center;width:270px">Position</th>
									<th id="id_header" style="text-align: center;width:270px">ID</th>
									<th style="text-align: center;width:270px" id="id_name">Name</th>
									
									<th><a href="javascript: void(0);" owspan=2 style="color: #D9A200;; outline: none !important;text-decoration: none;"><span id="officers_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Officer" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add Officer</span></a></th>
								</tr>
								
							</thead>
							<tbody id="body_officer">
							</tbody>
							
						</table>

						<div class="form-group">
							<div class="col-sm-12">
								<input type="button" class="btn btn-primary submitOfficerInfo" id="submitOfficerInfo" value="Save" style="float: right; margin-bottom: 20px; margin-top: 20px;">
							</div>
						</div>
					</form>
				</div>

				<button type="button" class="collapsible"><span style="font-size: 2.4rem;">Members</span></button>
				<div id="w2-capital" class="incorp_content">
				  	<div id="guarantee" style="display: none; margin-top: 20px;">
						
						<!-- <div style="margin-bottom: 23px; margin-top: 20px;">
							<h3>Members</h3>
						</div> -->
						<table class="table table-bordered table-striped table-condensed mb-none" id="guarantee_table">
						<thead>
							<div class="tr">
								
								<div class="th" id="id_guarantee_header" style="text-align: center;width:200px">ID</div>
								<div class="th" style="text-align: center;width:200px" id="id_guarantee_name">Name</div>
								<div class="th" style="text-align: center;width:200px" id="id_currency">Currency</div>
								<div class="th" id="id_guarantee" style="text-align: center;width:200px">Limit Of Guarantee</div>
								<div class="th" id="id_guarantee_start_date" style="text-align: center;width:200px">Transaction Date</div>
								
								<a href="javascript: void(0);" class="th" rowspan =2 style="color: #D9A200;width:170px; outline: none !important;text-decoration: none;"><span id="guarantee_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Guarantee" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add Guarantee</span></a>
							</div>
							
						</thead>
						

						<div class="tbody" id="body_guarantee">
							

						</div>
						
						</table>
						</div>


						<div id="non-guarantee" style="margin-top: 20px;">
							<!-- <div style="margin-bottom: 23px; margin-top: 20px;">
								<h3>Issued & Paid-Up Share Capital</h3>
							</div>
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
							
							</table> -->
							
							<!-- <h3 style="margin-top: 15px;">Members</h3> -->
							<!-- <div style="padding: 5px 0px;">
								<a href="<?= base_url();?>masterclient/view_allotment/<?=$company_code?>" class="btn btn-primary" target="_blank">Allotment</a>
								<a href="<?= base_url();?>masterclient/view_buyback/<?=$company_code?>" class="btn btn-primary" target="_blank">Buyback</a>
								<a href="<?= base_url();?>masterclient/view_transfer/<?=$company_code?>" class="btn btn-primary" target="_blank">Transfer</a>

								<a href="javascript: void(0);" class="btn btn-primary" id="refresh" class="refresh" style="float: right;">Refresh</a>
							</div> -->
							<form id="member_form">
								<div class="hidden"><input type="text" class="form-control" name="company_code" value="<?=$transaction_company_code?>"/></div>
								<div class="hidden"><input type="text" class="form-control" name="transaction_type" value="Allotment"/></div>
								<table class="table table-bordered table-striped table-condensed mb-none" style="width: 1000px">
									<thead>
										<tr>
											<!-- <div class="th" style="border-bottom:none;" valign=middle>No</div> -->
											<th style="text-align: center;width: 180px">ID</th>
											<th style="text-align: center;width: 180px">Class</th>
											<th style="text-align: center;width: 177px">Number of Shares Issued</th>
											<th style="text-align: center;width: 185px">Number of Shares Paid Up</th>
											<th style="border-bottom:none;"></th>
											<!-- <div class="th" style="border-bottom:none;">Certificate No.</div> -->
											<th rowspan=2><a href="javascript: void(0);" style="border-bottom:none;color: #D9A200;width:140px; outline: none !important;text-decoration: none;"><span id="allotment_member_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Allotment" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add Allotment</span></a></th>

											<!-- <div class="th" style="font-size:25px;border-bottom:none;width: 50px;"><span id="allotment_member_Add"><i class="fa fa-plus-circle"></i></span></div> -->
										</tr>
										<tr>
											<!-- <div class="th empty"></div> -->
											<th style="text-align: center;width: 180px">Name</th>
											<th style="text-align: center;width: 180px">Currency</th>
											<th style="text-align: center;width: 177px">Amount of Shares Issued</th>
											<th>Amount of Shares Paid Up</th>
											<th style="text-align: center;width:120px;border-top:none;">Certificate</th>
											<!-- <div class="th empty"></div> -->
											
										</tr>
									</thead>

									<tbody id="allotment_add">
															

									</tbody>
									
								</table>
							</form>
							
							<div class="form-group">
								<div class="col-sm-12">
									<input type="button" class="btn btn-primary submitMemberInfo" id="submitMemberInfo" value="Save" style="float: right; margin-bottom: 20px; margin-top: 20px;">
								</div>
							</div>
						</div>
				</div>

				<button type="button" class="collapsible"><span style="font-size: 2.4rem;">Controllers</span></button>
				<div id="w2-controller" class="incorp_content">
					<!-- <div style="margin-bottom: 23px; margin-top: 20px;">
				  		<h3>Controller</h3>
				  	</div> -->
				  	<form id="controller_form" style="margin-top: 20px;">
				  		<div class="hidden"><input type="text" class="form-control" name="company_code" value="<?=$transaction_company_code?>"/></div>
						<table class="table table-bordered table-striped table-condensed mb-none" id="controller_table"style="width: 1000px">
						

						<thead>
							<tr>
								<th id="id_controller_header" style="text-align: center;width:270px;">ID/UEN</th>
								<th id="id_date_of_birth" style="text-align: center;width:270px;">Date of Birth</th>
								<th style="border-bottom:none;"></th>
								
								<th rowspan="2"><a href="javascript: void(0);" style="color: #D9A200; outline: none !important;text-decoration: none;"><span id="controller_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Click to Add Controller" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add Controller</span></a></th>
							</tr>
							<tr>
								
								<th id="id_controller_name" style="text-align: center;width:270px;">Name</th>
								<th id="id_nationality" style="text-align: center;width:270px;">Nationality</th>
								<th id="id_address" style="text-align: center;width:270px;border-top:none;">Address</th>
								
								
								
								
								<!-- <th  style="border-top:none;"></th> -->
							</tr>
						</thead>
						<tbody id="body_controller">
						</tbody>

						
						
						</table>
						<div class="form-group">
							<div class="col-sm-12">
								<input type="button" class="btn btn-primary submitControllerInfo" id="submitControllerInfo" value="Save" style="float: right; margin-bottom: 20px; margin-top: 20px;">
							</div>
						</div>
					</form>
				</div>

				<button type="button" class="collapsible"><span style="font-size: 2.4rem;">Filing</span></button>
				<div id="w2-filing" class="incorp_content">
				  <?php echo form_open_multipart('', array('id' => 'filing_form', 'enctype' => "multipart/form-data")); ?>

					<div class="hidden"><input type="text" class="form-control" name="company_code" value="<?=$transaction_company_code?>"/></div>

					<div class="hidden"><input type="text" class="form-control filing_id" name="filing_id" value="<?=$filing->id?>"/></div>
					<!-- <div style="margin-bottom: 23px; margin-top: 20px;">
						<h3>Filing</h3>
					</div> -->
					<div class="form-group" style="margin-top: 20px;">
						<label class="col-xs-3" for="w2-show_all">Year End: </label>
						<div class="col-sm-9 form-inline">
							<div class="input-bar">
							    <div class="input-bar-item">
							     
							         <div class="year_end_group">
							            <div class="input-group" style="width: 200px;">
											<span class="input-group-addon">
												<i class="far fa-calendar-alt"></i>
											</span>
											<input type="text" class="form-control year_end_date" id="year_end_date" name="year_end" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="dd MMMM yyyy ">
										</div>
										<div id="validate_year_end_date"></div>
							        </div>
							      
							    </div>

							</div>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-3" for="w2-DS1">Financial Year Cycle:</label>
							<div class="col-sm-3">
								<select id="financial_year_period" class="form-control financial_year_period" id="financial_year_period" style="width:200px;" name="financial_year_period">
				                  
				                </select>
							</div>

					</div>
					<div class="form-group">
						<div class="col-sm-12">
							<input type="button" class="btn btn-primary submitFilingInfo" id="submitFilingInfo" value="Save" style="float: right; margin-bottom: 20px; margin-top: 20px;">
						</div>
					</div>
					<?= form_close(); ?>
				</div>

				<button type="button" class="collapsible"><span style="font-size: 2.4rem;">Service Engagement</span></button>
				<div id="w2-billing" class="incorp_content">

					<!-- <div class="form-group" style="margin-bottom: 23px; margin-top: 20px;">
						<div class="col-sm-4">
							<h3>Billing Information</h3>
						</div>

					</div> -->
					<form id="billing_form" style="margin-top: 20px;">
						<div class="hidden"><input type="text" class="form-control" name="company_code" value="<?=$transaction_company_code?>"/></div>
						<table class="table table-bordered table-striped table-condensed mb-none" id="billing_table"style="width: 1000px">
							<thead>
								<tr> 
									<th valign=middle style="width:210px;text-align: center">Service</th>
									<th valign=middle style="width:250px;text-align: center">Invoice Description</th>
									<th style="width:180px;text-align: center">Currency</th>
									<th style="width:180px;text-align: center">Amount</th>
									<th style="width:180px;text-align: center">Unit Pricing</th>
									<th style="width:80px;"><a href="javascript: void(0);" style="color: #D9A200;width:230px; outline: none !important;text-decoration: none;"><span id="billing_info_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Service Engagement Information" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add</span></a></th>
								</tr>
							</thead>
							<tbody id="body_billing_info">
							</tbody>
						</table>

						<div class="form-group">
							<div class="col-sm-12">
								<input type="button" class="btn btn-primary submitBillingInfo" id="submitBillingInfo" value="Save" style="float: right; margin-bottom: 20px; margin-top: 20px;">
							</div>
						</div>
					</form>
				</div>

				<button type="button" class="collapsible"><span style="font-size: 2.4rem;">Setup</span></button>
			<!-- <div class="relative-wrap"> -->
				<div id="w2-setup" class="incorp_content">
				  <?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form', 'id' => 'setup_form');
								echo form_open_multipart("masterclient/add_setup_info", $attrib);?>

					<div class="hidden"><input type="text" class="form-control" name="company_code" value="<?=$transaction_company_code?>"/></div>

					<div class="hidden"><input type="text" class="form-control" name="client_signing_info_id" value="<?=$client_signing_info[0]->id?>"/></div>

					<!-- <div class="form-group" style="margin-bottom: 23px; margin-top: 20px;">
						<div class="col-sm-4">
							<h3>Signing Information</h3>
						</div>
					</div> -->
						

					<div class="form_chairman form-group" style="margin-top: 20px;">
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
					
					<!-- <h3 style="margin-top: 20px;">Contact Information</h3> -->
					<div class="form-group">
						<label class="col-sm-3" for="w2-chairman">Contact Person Name:</label>
						<div class="col-sm-9">
							<input type="text" style="width:400px;" class="form-control" name="contact_name" id="contact_name" value=""/>
			            </div>
						
					</div>
					<div class="form-group">
						<label class="col-sm-3" for="w2-chairman">Contact Person Phone:</label>
						<div class="col-sm-9">
							<div class="input-group fieldGroup_contact_phone">
								<input type="tel" class="form-control check_empty_contact_phone main_contact_phone hp" id="contact_phone" name="contact_phone[]" value="<?=$client_contact_info[0]->contact_phone?>"/>

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

								<input class="btn btn-primary button_decrease_contact_phone remove_contact_phone" type="button" id="create_button" value="-" style="margin-left: 20px; margin-top: -26px; border-radius: 3px; width: 35px;"/>
							</div>

							<div id="form_contact_phone"></div>
			            </div>
					</div>
					<div class="form-group">
						<label class="col-sm-3" for="w2-chairman">Contact Person Email:</label>
						<div class="col-sm-9">
							<div class="input-group fieldGroup_contact_email" style="display: block !important;">
								<input type="text" class="form-control input-xs check_empty_contact_email main_contact_email" id="contact_email" name="contact_email[]" value="<?=$client_contact_info[0]->contact_email?>" style="width:400px;text-transform:uppercase;"/>

								<label class="radio-inline control-label" style="margin-left: 20px;"><input type="radio" class="contact_email_primary main_contact_email_primary" name="contact_email_primary" value="1" checked> Primary</label>
								
								
									<input class="btn btn-primary button_increment_contact_email addMore_contact_email" type="button" id="create_button" value="+" style="margin-left: 20px; border-radius: 3px;visibility: hidden; width: 35px;"/>
								

								<button type="button" class="btn btn-default btn-sm show_contact_email" style="margin-left: 20px; visibility: hidden;">
									  <span class="fa fa-arrow-down" aria-hidden="true"></span>&nbsp<span class="toggle_word">Show more</span>
								</button>

							</div>

							<div class="contact_email_toggle">
							</div>

							<div class="input-group fieldGroupCopy_contact_email contact_email_disabled" style="display: none;">
								<input type="text" class="form-control input-xs check_empty_contact_email second_contact_email" id="contact_email" name="contact_email[]" value="" style="width:400px;text-transform:uppercase;"/>
								<label class="radio-inline control-label" style="margin-left: 20px;"><input type="radio" class="contact_email_primary" name="contact_email_primary" value="1"> Primary</label>
								
								
									<input class="btn btn-primary button_decrease_contact_email remove_contact_email" type="button" id="create_button" value="-" style="margin-left: 20px; border-radius: 3px; width: 35px;"/>
								
							</div>

							<div id="form_contact_email"></div>
			            </div>
						
					</div>

					<!-- <div class="form-group">
						<div class="col-sm-4">
							<h3>Reminder</h3>
						</div>
					</div> -->

					<div class="form_select_reminder form-group no-overflow">
						<label class="col-sm-3" for="w2-DS2">Reminder:</label>
						<div class="col-sm-9">
							<div class="select_reminder_group" style="float: left;margin-right: 10px">
				                <select class="form-control" id="select_reminder" multiple="multiple" name="select_reminder[]" style="overflow:visible;">
		                        </select>
				            </div>
						</div>
					</div>
				<?= form_close(); ?>
				<div class="form-group">
					<div class="col-sm-12">
						<input type="button" class="btn btn-primary submitSetupInfo" id="submitSetupInfo" value="Save" style="float: right; margin-bottom: 30px; margin-top: 20px;">
					</div>
				</div>
						
				</div>
			<!-- </div> -->

				
	<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>
</section>
<script src="themes/default/assets/js/companyType.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>	
<script src="themes/default/assets/js/transaction_setup.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
<script type="text/javascript">
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

            var myString = "";
            
            var status = data.Status;

            if (status.code == 200) {         
              for (var i = 0; i < data.Placemark.length; i++) {
                var placemark = data.Placemark[i];
                var status = data.Status[i];
                $("#street_name").val(placemark.AddressDetails.Country.Thoroughfare.ThoroughfareName);

                if(placemark.AddressDetails.Country.AddressLine == "undefined")
                {
                    $("#building_name").val("");
                }
                else
                {
                    $("#building_name").val(placemark.AddressDetails.Country.AddressLine);
                }

              }
              $( '#form_postal_code' ).html('');
              $( '#form_street_name' ).html('');

            } else if (status.code == 603) {
                $( '#form_postal_code' ).html('<span class="help-block">*No Record Found</span>');

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
</script>
