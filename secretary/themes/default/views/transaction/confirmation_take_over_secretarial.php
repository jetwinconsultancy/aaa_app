<div id="transaction_incorporation" style="display: none">
	<div id="transaction_confirm_company_info">
		<span style="color: inherit; font-size: 2.4rem; letter-spacing: -1px; font-weight: 500; margin-top: 20px; margin-bottom: 10px; font-family: inherit; line-height: 2.1;">Company Information</span><a href="javascript:void(0)" class="btn btn-primary edit_company_info" id="edit_company_info"style="margin-left: 10px; margin-top: -5px; padding: 6px 10px; font-size: 12px">Edit</a>
		<table class="table" style="border:none">
			<tr>
				<td style="border:none;width: 200px;">
					Client Code: 
				</td>
				<td style="border:none">
					<div style="" id="transaction_client_code"></div>
				</td>
				<!-- <td style="float: right;border:none">
					<div style="" id="register_acquried_by"></div>
				</td> -->
				
			</tr>
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
			<tr class="activity2">
				<td style="border:none;width: 200px;">
					Principal Activity 2:
				</td>
				<td style="border:none">
					<div style="" id="transaction_activity2"></div>
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
					<th style="width:180px;text-align: center">Currency</th>
					<th style="width:180px;text-align: center">Amount</th>
					<th style="width:180px;text-align: center">Unit Pricing</th>
				</tr>
			</thead>
			<tbody id="confirmation_billing_table">
			</tbody>
		</table>
	</div>
	<div id="transaction_confirm_company_info">
		<span style="color: inherit; font-size: 2.4rem; letter-spacing: -1px; font-weight: 500; margin-top: 20px; margin-bottom: 10px; font-family: inherit; line-height: 2.1;">Previous Secretarial Information</span><a href="javascript:void(0)" class="btn btn-primary edit_previous_secretarial_info" id="edit_previous_secretarial_info"style="margin-left: 10px; margin-top: -5px; padding: 6px 10px; font-size: 12px">Edit</a>
		<table class="table" style="border:none">
			<tr>
				<td style="border:none;width: 200px;">
					Company Name:
				</td>
				<td style="border:none">
					<div style="" id="transaction_previous_secretarial_company_name"></div>
				</td>
				
				
			</tr>
			<tr>
				<td style="border:none;width: 200px;">
					Postal Code:
				</td>
				<td style="border:none">
					<div style="" id="transaction_previous_secretarial_postal_code"></div>
				</td>
				
			</tr>
			<tr>
				<td style="border:none;width: 200px;">
					Street Name:
				</td>
				<td style="border:none">
					<div style="" id="transaction_previous_secretarial_street_name"></div>
				</td>
				
			</tr>
			<tr>
				<td style="border:none;width: 200px;">
					Building Name:
				</td>
				<td style="border:none">
					<div style="" id="transaction_previous_secretarial_building_name"></div>
				</td>
				
			</tr>
			<tr>
				<td style="border:none;width: 200px;">
					Unit No:
				</td>
				<td style="border:none">
					<div style="width: 350px;float:left;margin-bottom:5px;">
						<div style="width: 5%; float: left;" id="transaction_previous_secretarial_unit_no1"></div>
						
						<label style="float: left; margin-right: 10px;" >-</label>
						<div style="width: 8%;float: left;" id="transaction_previous_secretarial_unit_no2"></div>
					</div>
				</td>
				
			</tr>
			
			
		</table>
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

	</div>

	<div>
		<h3>Logdement Info</h3>
		
		<div class="form-group">
			<label class="col-sm-3" for="w2-DS2">Registration No:</label>
			<div class="col-sm-8">
				<div style="width: 200px;">
					<input type="text" class="form-control" name="registration_no" id="registration_no">
				</div>
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-sm-3" for="w2-DS2">Effective Date:</label>
			<div class="col-sm-8">
				<div class="input-group mb-md" style="width: 200px;">
					<span class="input-group-addon">
						<i class="far fa-calendar-alt"></i>
					</span>
					<input type="text" class="form-control valid lodgement_date" id="date_todolist" name="lodgement_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" required="" value="" placeholder="DD/MM/YYYY">
				</div>
			</div>
		</div>
	</div> -->

</div>

<script src="themes/default/assets/js/confirmation_take_over_secretarial.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
