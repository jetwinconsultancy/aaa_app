<div id="transaction_confirmation_service_proposal">
	<span style="color: inherit; font-size: 2.4rem; letter-spacing: -1px; font-weight: 500; margin-top: 20px; margin-bottom: 10px; font-family: inherit; line-height: 2.1;">Service Proposal</span><a href="javascript:void(0)" class="btn btn-primary edit_service_proposal" id="edit_service_proposal" style="margin-left: 10px; margin-top: -5px; padding: 6px 10px; font-size: 12px">Edit</a>
	<div id="service_proposal_interface">
		<div class="form-group">
			<label class="col-sm-2" for="w2-DS2">Proposal Date:</label>
			<div class="col-sm-8">
				<span style="text-transform:uppercase" class="confirmation_proposal_date" id="confirmation_proposal_date" name="confirmation_proposal_date"></span>
			</div>
		</div>
		<span style="font-size: 1.7rem;padding: 0; margin: 7px 0px 4px 0;">Principal Activities</span>
		<div class="form-group" style="margin-top: 20px">
			<label class="col-sm-2" for="w2-username">Activity 1: </label>
			<div class="col-sm-8">
				<span style="text-transform:uppercase" class="confirmation_activity1" id="confirmation_activity1" name="confirmation_activity1"></span>
			</div>
			
		</div>
		<div class="form-group">
			<label class="col-sm-2" for="w2-username">Activity 2: </label>
			<div class="col-sm-8">
				<span style="text-transform:uppercase" class="confirmation_activity2" id="confirmation_activity2" name="confirmation_activity2"></span>
			</div>
		</div>
		<span style="font-size: 1.7rem;padding: 0; margin: 7px 0px 4px 0;">Registered Office Address</span>
		<div class="form-group" style="margin-top: 20px">
			<div class="col-sm-2">
				<label>Postal Code :</label>
			</div>
			<div class="col-sm-8">
				<div style="width: 100%;">
					<div style="width: 65%;float:left;margin-bottom:5px;">
						<div class="" style="width: 20%;" >
							<span style="text-transform:uppercase" class="confirmation_postal_code" id="confirmation_postal_code" name="confirmation_postal_code"></span>
						</div>
					</div>
				</div>
			</div>
			
		</div>
		<div class="form-group">
			<div class="col-sm-2">
				<label>Street Name :</label>
			</div>
			<div class="col-sm-8">
				<div style="width: 100%;">
					<div style="width: 71%;float:left;margin-bottom:5px;">
						<div class="" style="width: 100%;" >
							<span style="text-transform:uppercase" class="confirmation_street_name" id="confirmation_street_name" name="confirmation_street_name"></span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-2">
				<label>Building Name :</label>
			</div>
			<div class="col-sm-8">
				<div style="width: 100%;">
					<div style="width: 71%;float:left;margin-bottom:5px;">
						<div class="" style="width: 100%;" >
							<span style="text-transform:uppercase" class="confirmation_building_name" id="confirmation_building_name" name="confirmation_building_name"></span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2">Unit No :</label>
			<div class="col-sm-8">
				<span style="width: 1%; float: left; margin-right: 10px; text-transform:uppercase;" class="confirmation_unit_no1" id="confirmation_unit_no1" name="confirmation_unit_no1"></span>
				<label style="float: left; margin-right: 10px;" >-</label>
				<span style="width: 14%; text-transform:uppercase;" class="confirmation_unit_no2" id="confirmation_unit_no2" name="confirmation_unit_no2"></span>
			</div>
		</div>
		<span style="font-size: 1.7rem;padding: 0; margin: 7px 0px 4px 0;">Contact Information</span>
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
		<span style="font-size: 1.7rem;padding: 0; margin: 7px 0px 4px 0;">Services</span>
		<table class="table table-bordered table-striped table-condensed mb-none" id="confirm_service_proposal_table" style="width: 1010px; margin-top: 20px">
			<thead>
				<tr>
					<th style="text-align: center;width:70px"></th>
					<th style="text-align: center;width:170px">Service Name</th>
					<th style="text-align: center;width:170px">Currency</th>
					<th style="text-align: center;width:170px">Fee</th>
					<th style="text-align: center;width:220px">Unit Pricing (Per)</th>
					<th style="text-align: center;width:170px">Servicing Firm</th>
					<th style="text-align: center;width:50px">Sequence</th>
				</tr>
				
			</thead>
			<tbody id="body_confirm_service_proposal">
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
		<h3>Status</h3>
		
		<div class="form-group">
			<label class="col-sm-3" for="w2-DS2">Acceptance Date:</label>
			<div class="col-sm-8">
				<div class="input-group mb-md" style="width: 200px;">
					<span class="input-group-addon">
						<i class="far fa-calendar-alt"></i>
					</span>
					<input type="text" class="form-control valid lodgement_date" id="date_todolist" name="lodgement_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" required="" value="" placeholder="DD/MM/YYYY">
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3" for="w2-DS2">Status:</label>
			<div class="col-sm-8 mb-md">
				<select class="form-control tran_status" style="width: 500px;" name="tran_status" id="tran_status">
					<option value="0">Select Status</option>
				</select>
			</div>
			<div class="reason_cancellation_textfield" style="display: none">
				<label class="col-sm-3" for="reason_cancellation">Reason Cancellation Transaction:</label>
				<div class="col-sm-8">
					<textarea class="form-control cancellation_reason" rows="4" cols="50" name="cancellation_reason"></textarea>
				</div>
			</div>
		</div>
	</div> -->
</div>
<script src="themes/default/assets/js/confirmation_service_proposal.js?v=44eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>