<div id="transaction_confirmation_engagement_letter">
	<span style="color: inherit; font-size: 2.4rem; letter-spacing: -1px; font-weight: 500; margin-top: 20px; margin-bottom: 10px; font-family: inherit; line-height: 2.1;">Engagement Letter Info</span><a href="javascript:void(0)" class="btn btn-primary edit_engagement_letter" id="edit_engagement_letter" style="margin-left: 10px; margin-top: -5px; padding: 6px 10px; font-size: 12px">Edit</a>
	<div id="engagement_letter_interface">
		<!-- <span style="font-size: 1.7rem;padding: 0; margin: 7px 0px 4px 0;">Engagement Letter</span> -->
		<div class="form-group" style="margin-top: 20px">
			<label class="col-sm-2" for="w2-username">Engagement Letter Date:</label>
			<div class="col-sm-8">
				<span class="confirmation_engagement_letter_date" id="confirmation_engagement_letter_date" name="confirmation_engagement_letter_date"></span>
			</div>
		</div>
		<div class="form-group" style="margin-top: 20px">
			<label class="col-sm-2" for="w2-username">Registration No:</label>
			<div class="col-sm-8">
				<span style="text-transform:uppercase" class="confirmation_el_uen" id="confirmation_el_uen" name="confirmation_el_uen"></span>
			</div>
		</div>
		<div class="form-group" style="margin-top: 20px">
			<label class="col-sm-2" for="w2-username">FYE Date:</label>
			<div class="col-sm-8">
				<span class="confirmation_fye_date" id="confirmation_fye_date" name="confirmation_fye_date"></span>
			</div>
		</div>
		<div class="form-group" style="margin-top: 20px">
			<label class="col-sm-2" for="w2-username">Director Signing:</label>
			<div class="col-sm-8">
				<span style="text-transform:uppercase" class="confirmation_director_signing" id="confirmation_director_signing" name="confirmation_director_signing"></span>
			</div>
		</div>
		<table class="table table-bordered table-striped table-condensed mb-none" id="confirm_engagement_letter_table" style="width: 1010px; margin-top: 20px">
			<thead>
				<tr>
					<th style="text-align: center;width:70px"></th>
					<th style="text-align: center;width:170px">Engagement Letter Name</th>
					<th style="text-align: center;width:170px">Currency</th>
					<th style="text-align: center;width:170px">Fee</th>
					<th style="text-align: center;width:220px">Unit Pricing (Per)</th>
					<th style="text-align: center;width:170px">Servicing Firm</th>
				</tr>
				
			</thead>
			<tbody id="body_confirm_engagement_letter">
			</tbody>
			
		</table>
	</div>
	<div id = "document">
		<h3>Compilation</h3>
		<table class="table table-bordered table-striped mb-none" id="datatable-pending" style="width:100%;">
			<thead>
				<tr>
					<th style="text-align: center;width:20px !important;padding-right:2px !important;padding-left:2px !important;">No</th>
					<!-- <th style="text-align: center;width:250px !important;padding-right:2px !important;padding-left:2px !important;">Client</th> -->
					<th style="text-align: center;width:310px !important;padding-right:2px !important;padding-left:2px !important;">Document Name</th>
					<!-- <th style="text-align: center;width:150px !important;padding-right:2px !important;padding-left:2px !important;">Created On</th> -->
					<th style="text-align: center;width:30px !important;padding-right:2px !important;padding-left:2px !important;">Received On</th>
				</tr>
			</thead>
			<tbody id="pending_doc_body">
				
			</tbody>
		</table>
	</div>
	<div>
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
	</div>
</div>
<script src="themes/default/assets/js/confirmation_engagement_letter.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>