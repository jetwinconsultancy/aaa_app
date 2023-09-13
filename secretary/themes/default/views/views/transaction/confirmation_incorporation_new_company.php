<div id="transaction_incorporation" style="display: none">
	<div id="transaction_confirm_company_info">
		<span style="color: inherit; font-size: 2.4rem; letter-spacing: -1px; font-weight: 500; margin-top: 20px; margin-bottom: 10px; font-family: inherit; line-height: 2.1;">Company Information</span><a href="javascript:void(0)" class="btn btn-primary edit_company_info" id="edit_company_info"style="margin-left: 10px; margin-top: -5px; padding: 6px 10px; font-size: 12px">Edit</a>
		<table class="table" style="border:none">
			<!-- <tr>
				<td style="border:none;width: 200px;">
					Client Code: 
				</td>
				<td style="border:none">
					<div style="" id="transaction_client_code"></div>
				</td>
			</tr> -->
			<tr>
				<td style="border:none;width: 200px;">
					Company Name:
				</td>
				<td style="border:none">
					<div style="" id="transaction_company_name"></div>
				</td>
				
				
			</tr>
			<tr>
				<td style="border:none;width: 200px;">
					Company Type:
				</td>
				<td style="border:none">
					<div style="" id="transaction_company_type"></div>
				</td>
				
				
			</tr>
			<tr>
				<td style="border:none;width: 200px;">
					Principal Activity 1:
				</td>
				<td style="border:none">
					<div style="" id="transaction_activity1"></div>
				</td>
				
				
			</tr>
			<tr class="confirm_description1">
				<td style="border:none;width: 200px;">
					Description 1:
				</td>
				<td style="border:none">
					<div style="" id="transaction_description1"></div>
				</td>
			</tr>
			<tr class="activity2">
				<td style="border:none;width: 200px;">
					Principal Activity 2:
				</td>
				<td style="border:none">
					<div style="" id="transaction_activity2"></div>
				</td>
				
				
			</tr>
			<tr class="confirm_description2">
				<td style="border:none;width: 200px;">
					Description 2:
				</td>
				<td style="border:none">
					<div style="" id="transaction_description2"></div>
				</td>
			</tr>
			<tr>
				<td style="border:none;width: 200px;">
					Postal Code:
				</td>
				<td style="border:none">
					<div style="" id="transaction_postal_code"></div>
				</td>
				
			</tr>
			<tr>
				<td style="border:none;width: 200px;">
					Street Name:
				</td>
				<td style="border:none">
					<div style="" id="transaction_street_name"></div>
				</td>
				
			</tr>
			<tr>
				<td style="border:none;width: 200px;">
					Building Name:
				</td>
				<td style="border:none">
					<div style="" id="transaction_building_name"></div>
				</td>
				
			</tr>
			<tr>
				<td style="border:none;width: 200px;">
					Unit No:
				</td>
				<td style="border:none">
					<div style="width: 350px;float:left;margin-bottom:5px;">
						<div style="width: 5%; float: left;" id="transaction_unit_no1"></div>
						
						<label style="float: left; margin-right: 10px;" >-</label>
						<div style="width: 8%;float: left;" id="transaction_unit_no2"></div>
					</div>
				</td>
				
			</tr>
			
			
		</table>
	</div>
	<div id="transaction_confirmation_officer_table">
		<span style="color: inherit; font-size: 2.4rem; letter-spacing: -1px; font-weight: 500; margin-top: 20px; margin-bottom: 10px; font-family: inherit; line-height: 2.1;">Officers</span><a href="javascript:void(0)" class="btn btn-primary edit_officer_info" id="edit_officer_info"style="margin-left: 10px; margin-top: -5px; padding: 6px 10px; font-size: 12px">Edit</a>
		<table class="table table-bordered table-striped table-condensed mb-none" id="officer_table" style="width: 1000px">
			<thead>
				<tr>
					<th id="id_position" style="text-align: center;width:270px">Position</th>
					<th id="id_header" style="text-align: center;width:270px">ID</th>
					<th style="text-align: center;width:270px" id="id_name">Name</th>
					
				</tr>
				
			</thead>
			<tbody id="confirmation_officer_table">
			</tbody>
			
		</table>
	</div>
	<div id="transaction_confirmation_member_table">
		<span style="color: inherit; font-size: 2.4rem; letter-spacing: -1px; font-weight: 500; margin-top: 20px; margin-bottom: 10px; font-family: inherit; line-height: 2.1;">Members</span><a href="javascript:void(0)" class="btn btn-primary edit_member_info" id="edit_member_info"style="margin-left: 10px; margin-top: -5px; padding: 6px 10px; font-size: 12px">Edit</a>
		<table class="table table-bordered table-striped table-condensed mb-none" style="width: 1000px">
			<thead>
				<tr>
					<th style="text-align: center;width: 180px">ID</th>
					<th style="text-align: center;width: 180px">Class</th>
					<th style="text-align: center;width: 177px">Number of Shares Issued</th>
					<th style="text-align: center;width: 185px">Number of Shares Paid Up</th>
					<th style="border-bottom:none;"></th>

				</tr>
				<tr>
					<th style="text-align: center;width: 180px">Name</th>
					<th style="text-align: center;width: 180px">Currency</th>
					<th style="text-align: center;width: 177px">Amount of Shares Issued</th>
					<th>Amount of Shares Paid Up</th>
					<th style="text-align: center;width:120px;border-top:none;">Certificate</th>
					
				</tr>
			</thead>

			<tbody id="confirmation_member_table">
									

			</tbody>
			
		</table>
	</div>
	<div id="transaction_confirmation_controller_table">
		<span style="color: inherit; font-size: 2.4rem; letter-spacing: -1px; font-weight: 500; margin-top: 20px; margin-bottom: 10px; font-family: inherit; line-height: 2.1;">Controllers</span><a href="javascript:void(0)" class="btn btn-primary edit_controller_info" id="edit_controller_info"style="margin-left: 10px; margin-top: -5px; padding: 6px 10px; font-size: 12px">Edit</a>
		<table class="table table-bordered table-striped table-condensed mb-none" id="controller_table"style="width: 1000px">
			<thead>
				<tr>
					<th id="id_controller_header" style="text-align: center;width:270px;">ID/UEN</th>
					<th id="id_date_of_birth" style="text-align: center;width:270px;">Date of Birth</th>
					<th style="border-bottom:none;"></th>
					
				</tr>
				<tr>
					
					<th id="id_controller_name" style="text-align: center;width:270px;">Name</th>
					<th id="id_nationality" style="text-align: center;width:270px;">Nationality</th>
					<th id="id_address" style="text-align: center;width:270px;border-top:none;">Address</th>
				</tr>
			</thead>
			<tbody id="confirmation_controller_table">
			</tbody>
		</table>
	</div>
	<div id="transaction_confirm_filing_info">
		<span style="color: inherit; font-size: 2.4rem; letter-spacing: -1px; font-weight: 500; margin-top: 20px; margin-bottom: 10px; font-family: inherit; line-height: 2.1;">Filing</span><a href="javascript:void(0)" class="btn btn-primary edit_filing_info" id="edit_filing_info"style="margin-left: 10px; margin-top: -5px; padding: 6px 10px; font-size: 12px">Edit</a>
		<table class="table" style="border:none">
			<tr>
				<td style="border:none;width: 200px;">
					Year End: 
				</td>
				<td style="border:none">
					<div style="" id="transaction_filing_year_end"></div>
				</td>
				<!-- <td style="float: right;border:none">
					<div style="" id="register_acquried_by"></div>
				</td> -->
				
			</tr>
			<tr>
				<td style="border:none;width: 200px;">
					Financial Year Cycle:
				</td>
				<td style="border:none">
					<div style="" id="transaction_filing_cycle"></div>
				</td>
				
				
			</tr>
		</table>
	</div>
	<div id="transaction_confirm_billing_info">
		<span style="color: inherit; font-size: 2.4rem; letter-spacing: -1px; font-weight: 500; margin-top: 20px; margin-bottom: 10px; font-family: inherit; line-height: 2.1;">Billing</span><a href="javascript:void(0)" class="btn btn-primary edit_billing_info" id="edit_billing_info"style="margin-left: 10px; margin-top: -5px; padding: 6px 10px; font-size: 12px">Edit</a>
		<table class="table table-bordered table-striped table-condensed mb-none" id="billing_table"style="width: 1000px">
			<thead>
				<tr> 
					<th valign=middle style="width:210px;text-align: center">Service</th>
					<th valign=middle style="width:250px;text-align: center">Invoice Description</th>
					<th style="width:180px;text-align: center">Fee</th>
					<th style="width:180px;text-align: center">Unit Pricing</th>
					<th style="width:180px;text-align: center">Servicing Firm</th>
				</tr>
			</thead>
			<tbody id="confirmation_billing_table">
			</tbody>
		</table>
	</div>
	<div id="transaction_confirm_billing_info">
		<span style="color: inherit; font-size: 2.4rem; letter-spacing: -1px; font-weight: 500; margin-top: 20px; margin-bottom: 10px; font-family: inherit; line-height: 2.1;">Setup</span><a href="javascript:void(0)" class="btn btn-primary edit_setup_info" id="edit_setup_info"style="margin-left: 10px; margin-top: -5px; padding: 6px 10px; font-size: 12px">Edit</a>
		<div id="w2-setup">

			<div class="form_chairman form-group" style="margin-top: 20px;">
				<label class="col-sm-3" for="w2-chairman">Chairman:</label>
				<div class="col-sm-9">
					<div style="float: left;margin-right: 10px" id="confirm_chairman_name">
						
		            </div>
	            </div>
	            
				
			</div>

			<div class="form_director_signature_1 form-group">
				<label class="col-sm-3" for="w2-DS1">Director Signature 1:</label>
				<div class="col-sm-9">
					<div style="float: left;margin-right: 10px" id="confirm_director_name_1">
		            </div>
				
				</div>
				
			</div>
			<div class="confirm_director_signature_2 form-group">
				<label class="col-sm-3" for="w2-DS2">Director Signature 2:</label>
				<div class="col-sm-9">
					<div style="float: left;margin-right: 10px" id="confirm_director_name_2">
		            </div>
				</div>
			</div>
			
			<!-- <h3 style="margin-top: 20px;">Contact Information</h3> -->
			<div class="form-group">
				<label class="col-sm-3" for="w2-chairman">Contact Person Name:</label>
				<div class="col-sm-9" id="confirm_contact_name">
					
	            </div>
				
			</div>
			<div class="form-group">
				<label class="col-sm-3" for="w2-chairman">Contact Person Phone:</label>
				<div class="col-sm-9">
					<div class="input-group confirm_fieldGroup_contact_phone">
						<span class="confirm_contact_phone"></span>

						<label class="radio-inline control-label" style="margin-left: 20px; padding-top: 0px;"><input type="radio" class="contact_phone_primary confirm_main_contact_phone_primary" name="contact_phone_primary" value="1" checked disabled> Primary</label>

					</div>

					<div class="confirm_contact_phone_toggle">
					</div>

					<div class="input-group confirm_fieldGroupCopy_contact_phone contact_phone_disabled" style="display: none;">

						<span class="confirm_second_hp"></span>

						<label class="radio-inline control-label" style="margin-left: 20px; padding-top: 0px;"><input type="radio" class="confirm_contact_phone_primary" name="contact_phone_primary" value="1" disabled> Primary</label>
					</div>

					<div id="form_contact_phone"></div>
	            </div>
			</div>
			<div class="form-group">
				<label class="col-sm-3" for="w2-chairman">Contact Person Email:</label>
				<div class="col-sm-9">
					<div class="input-group confirm_fieldGroup_contact_email" style="display: block !important;">

						<span class="confirm_contact_email"></span>

						<label class="radio-inline control-label" style="margin-left: 20px; padding-top: 0px;"><input type="radio" class="contact_email_primary confirm_main_contact_email_primary" name="contact_email_primary" value="1" checked disabled> Primary</label>

					</div>

					<div class="confirm_contact_email_toggle">
					</div>

					<div class="input-group confirm_fieldGroupCopy_contact_email contact_email_disabled" style="display: none;">

						<span class="confirm_second_contact_email"></span>

						<label class="radio-inline control-label" style="margin-left: 20px; padding-top: 0px;"><input type="radio" class="confirm_contact_email_primary" name="contact_email_primary" value="1" disabled> Primary</label>

						
					</div>

					<div id="form_contact_email"></div>
	            </div>
				
			</div>

			<div class="form_select_reminder form-group no-overflow">
				<label class="col-sm-3" for="w2-DS2">Reminder:</label>
				<div class="col-sm-9">
					<div class="confirm_select_reminder_group" style="float: left;margin-right: 10px">
		            </div>
				</div>
			</div>

				
		</div>
	</div>

	<!-- <div id = "document">
		<h3>Compilation</h3>
		<table class="table table-bordered table-striped mb-none" id="datatable-pending" style="width:100%;">
		<thead>
			<tr>
				<th style="text-align: center;width:20px !important;padding-right:2px !important;padding-left:2px !important;">No</th>
				<th style="text-align: center;width:310px !important;padding-right:2px !important;padding-left:2px !important;">Document Name</th>
				<th style="text-align: center;width:30px !important;padding-right:2px !important;padding-left:2px !important;">Received On</th>
			</tr>
		</thead>
		<tbody id="pending_doc_body">
			
		</tbody>
	</table>

	</div> -->
	

</div>

<script src="themes/default/assets/js/confirmation_incorporation_new_company.js?v=6434fdfsdfdrw32323233w1323" charset="utf-8"></script>
