<div id="transaction_confirmation_change_regis_ofis_address">
	<span style="color: inherit; font-size: 2.4rem; letter-spacing: -1px; font-weight: 500; margin-top: 20px; margin-bottom: 10px; font-family: inherit; line-height: 2.1;">New Registration Office Address</span><a href="javascript:void(0)" class="btn btn-primary edit_change_regis_ofis_address" id="edit_change_regis_ofis_address"style="margin-left: 10px; margin-top: -5px; padding: 6px 10px; font-size: 12px">Edit</a>
	<div id="change_of_reg_ofis_interface">
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
		<div class="form-group" style="margin-top: 20px">
			<label class="col-xs-2" for="w2-username">Use Our Registered Address: </label>
			<div class="col-xs-8">
				<input type="checkbox" class="" id="confirmation_use_registered_address" name="confirmation_use_registered_address" disabled="disabled">
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
						<div class="confirmation_postal_code" style="width: 20%;" id="confirmation_postal_code">
						</div>
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
						<div class="confirmation_street_name" style="width: 100%;" id="confirmation_street_name">
						</div>
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
						<div class="confirmation_building_name" style="width: 100%;" id="confirmation_building_name">
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2" for="w2-username"></label>
			<div class="col-sm-8">
				<label style="width: 15%;float:left;margin-right: 20px;">Unit No :</label>
				<span style="float: left; margin-right: 10px; text-transform:uppercase;" id="confirmation_unit_no1" name="confirmation_unit_no1"></span>
				<label style="float: left; margin-right: 10px;" >-</label>
				<span style="width: 14%; text-transform:uppercase;" id="confirmation_unit_no2" name="confirmation_unit_no2"></span>
			</div>
		</div>
		<div class="form-group" style="margin-top: 20px">
			<label class="col-xs-2" for="w2-username">Effective Date: </label>
			<div class="col-xs-8">
				<div class="confirm_effective_date" id="confirm_effective_date"></div>
			</div>
		</div>
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
	<!-- <div>
		<h3>Logdement Info</h3>
		
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
<script src="themes/default/assets/js/confirmation_change_regis_ofis_address.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>