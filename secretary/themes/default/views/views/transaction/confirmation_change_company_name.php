<div id="transaction_confirmation_change_company_name">
	<span style="color: inherit; font-size: 2.4rem; letter-spacing: -1px; font-weight: 500; margin-top: 20px; margin-bottom: 10px; font-family: inherit; line-height: 2.1;">Change of Company Name</span><a href="javascript:void(0)" class="btn btn-primary edit_change_company_name" id="edit_change_company_name"style="margin-left: 10px; margin-top: -5px; padding: 6px 10px; font-size: 12px">Edit</a>
	<h4>Company Info</h4>
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
	<h4>Notice Info</h4>
	<div class="form-group">
		<label class="col-sm-2" for="w2-username">Director(s) Meeting Date: </label>
		<div class="col-sm-4">
			<div class="confirmation_director_meeting_date" id="confirmation_director_meeting_date"></div>
		</div>
		<label class="col-sm-1" for="w2-username">Time: </label>
		<div class="col-sm-4 form-group">
			<div class="confirmation_director_meeting_time" id="confirmation_director_meeting_time"></div>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2" for="w2-username">Member(s) Meeting Date: </label>
		<div class="col-sm-4">
			<div class="confirmation_member_meeting_date" id="confirmation_member_meeting_date"></div>
		</div>
		<label class="col-sm-1" for="w2-username">Time: </label>
		<div class="col-sm-4 form-group">
			<div class="confirmation_member_meeting_time" id="confirmation_member_meeting_time"></div>
		</div>
	</div>
	<table class="table table-bordered table-striped table-condensed">
		<tr>
			<th style="width: 200px">Address</th>
			<td><label><input type="radio" id="confirmation_register_address_edit" name="address_type" value="Registered Office Address"/>&nbsp;&nbsp;Registered Office Address</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<label><input type="radio" id="confirmation_local_edit" name="address_type" value="Local"/>&nbsp;&nbsp;Local Address</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<label><input type="radio" id="confirmation_foreign_edit" name="address_type" value="Foreign"/>&nbsp;&nbsp;Foreign Address</label></td>
		</tr>
		<tr id="confirmation_tr_registered_edit">
			<th></th>
			<td>
				<div class="row">
					<div class="col-sm-2">
						<label>Postal Code :</label>
					</div>
					<div class="col-sm-10">
						<div class="input-group confirmation_registered_postal_code1" id="confirmation_registered_postal_code1"></div>
					</div>
				</div>

				<div class="row">
					<div class="col-sm-2">
						<label>Street Name :</label>
					</div>
					<div class="col-sm-10"> 
						<div class="input-group confirmation_registered_street_name1" id="confirmation_registered_street_name1">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-2">
						<label>Building Name :</label>
					</div>
					<div class="col-sm-10"> 
						<div class="input-group confirmation_registered_building_name1" style="width: 100%;" id="confirmation_registered_building_name1"></div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-2">
						<label>Unit No :</label>
					</div>
					<div class="col-sm-10"> 
						<div class="confirmation_registered_unit_no1" id="confirmation_registered_unit_no1" style="display: inline-block"></div> - 
						<div class="confirmation_registered_unit_no2" id="confirmation_registered_unit_no2" style="width: 15%;display: inline-block;" ></div>
					</div>
				</div>
			</td>
		</tr>
		<tr id="confirmation_tr_local_edit">
			<th></th>
			<td>
				<div class="row">
					<div class="col-sm-2">
						<label>Postal Code :</label>
					</div>
					<div class="col-sm-10">
						<div class="input-group confirmation_postal_code1" id="confirmation_postal_code1"></div>
					</div>
				</div>

				<div class="row">
					<div class="col-sm-2">
						<label>Street Name :</label>
					</div>
					<div  class="col-sm-10">
						<div class="input-group confirmation_street_name1" id="confirmation_street_name1">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-2">
						<label>Building Name :</label>
					</div>
					<div class="col-sm-10">
						<div class="input-group confirmation_building_name1" style="width: 100%;" id="confirmation_building_name1">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-2">
						<label >Unit No :</label>
					</div>
					<div class="col-sm-10"> 
						<div class="confirmation_unit_no1" id="confirmation_unit_no1" style="display: inline-block"></div> -
						<div class="confirmation_unit_no2" id="confirmation_unit_no2" style="width: 15%;display: inline-block;" ></div>
					</div>
				</div>
				
			</td>
		</tr>
		<tr id="confirmation_tr_foreign_edit">
			<td></td>
			<td colspan="2">
				<div class="row">
					<div class="col-sm-2">
						<label>Foreign Address :</label>
					</div>
					<div class="col-sm-10">
						<div class="input-group confirmation_foreign_address1" id="confirmation_foreign_address1">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-2">
						<label></label>
					</div>
					<div class="col-sm-10">
						<div class="input-group confirmation_foreign_address2" id="confirmation_foreign_address2">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-2">
						<label></label>
					</div>
					<div class="col-sm-10">
						<div class="input-group confirmation_foreign_address3" id="confirmation_foreign_address3">
						</div>
					</div>
				</div>
				
			</td>
		</tr>
	</table>
	<h4>New Company Name</h4>
	<div id="change_of_company_name_interface">
		<div class="form-group" style="margin-top: 20px">
			<label class="col-xs-2" for="w2-username">Company Name: </label>
			<div class="col-xs-8">
				<div class="new_company_name" id="new_company_name"></div>
			</div>
		</div>
		<div class="form-group" style="margin-top: 20px">
			<label class="col-xs-2" for="w2-username">Effective Date: </label>
			<div class="col-xs-8">
				<div class="confirm_effective_date" id="confirm_effective_date"></div>
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
	</div>
	<div>
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
<script src="themes/default/assets/js/confirmation_change_company_name.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>