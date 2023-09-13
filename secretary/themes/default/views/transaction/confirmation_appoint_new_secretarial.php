<div id="transaction_confirmation_officer_table">
	<span style="color: inherit; font-size: 2.4rem; letter-spacing: -1px; font-weight: 500; margin-top: 20px; margin-bottom: 10px; font-family: inherit; line-height: 2.1;">Company Info</span><a href="javascript:void(0)" class="btn btn-primary edit_officer_info" id="edit_officer_info"style="margin-left: 10px; margin-top: -5px; padding: 6px 10px; font-size: 12px">Edit</a>
	<div class="form-group">
		<label class="col-sm-2">Registration No: </label>
		<div class="col-sm-4">
			<div class="confirmation_registration_no" id="confirmation_registration_no"></div>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2">Company Name: </label>
		<div class="col-sm-4">
			<div class="confirmation_company_name" id="confirmation_company_name"></div>
		</div>
	</div>
	<div id="confirmation_appoint_officer">
		<h3>Appointment of Secretarial</h3>
		<!-- <div class="form-group">
			<label class="col-sm-4">Resignation of Company Secretary: </label>
			<div class="col-sm-5">
				<div class="confirmation_resignation_of_company_secretary" id="confirmation_resignation_of_company_secretary"></div>
			</div>
		</div> -->
		<div class="form-group">
			<label class="col-sm-4">Resignation of Corporate Secretarial Agent: </label>
			<div class="col-sm-5">
				<div class="confirmation_resignation_of_corporate_secretarial_agent" id="confirmation_resignation_of_corporate_secretarial_agent"></div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-4">Resignation of Corporate Secretarial Agent Address: </label>
			<div class="col-sm-5">
				<div class="confirmation_resignation_of_corporate_secretarial_agent_address" id="confirmation_resignation_of_corporate_secretarial_agent_address"></div>
			</div>
		</div>
		<table class="table table-bordered table-striped table-condensed mb-none" id="officer_table" style="width: 1000px">
			<thead>
				<tr>
					<th id="id_position" style="text-align: center;width:270px">Position</th>
					<th id="id_header" style="text-align: center;width:270px">ID</th>
					<th style="text-align: center;width:270px" id="id_name">Name</th>
					<th style="text-align: center;width:170px">Date Of Appointment</th>
				</tr>
				
			</thead>
			<tbody id="confirmation_appoint_officer_table">
			</tbody>
			
		</table>
	</div>
	<div id="confirmation_resign_officer">
		<span style="color: inherit; font-size: 2.4rem; letter-spacing: -1px; font-weight: 500; margin-top: 20px; margin-bottom: 10px; font-family: inherit; line-height: 2.1;">Resign of Secretarial</span>
		<table class="table table-bordered table-striped table-condensed mb-none" id="officer_table" style="width: 1000px">
			<thead>
				<tr>
					<th id="id_position" style="text-align: center;width:170px">Position</th>
					<th id="id_header" style="text-align: center;width:170px">ID</th>
					<th style="text-align: center;width:170px" id="id_name">Name</th>
					<th style="text-align: center;width:170px">Date Of Appointment</th>
					<th style="text-align: center;width:170px">Date Of Cessation</th>
					<th style="text-align: center;width:220px">Reason</th>
				</tr>
			</thead>
			<tbody id="confirmation_officer_table">
			</tbody>
		</table>
	</div>
	<div id="transaction_confirm_billing_info">
		<span style="color: inherit; font-size: 2.4rem; letter-spacing: -1px; font-weight: 500; margin-top: 20px; margin-bottom: 10px; font-family: inherit; line-height: 2.1;">Service Engagement</span><!-- <a href="javascript:void(0)" class="btn btn-primary edit_billing_info" id="edit_billing_info"style="margin-left: 10px; margin-top: -5px; padding: 6px 10px; font-size: 12px">Edit</a> -->
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
	
</div>
<script src="themes/default/assets/js/confirmation_appoint_new_director.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>