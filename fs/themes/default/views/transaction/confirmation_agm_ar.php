<div id="transaction_confirmation_agm_ar">
	<span style="color: inherit; font-size: 2.4rem; letter-spacing: -1px; font-weight: 500; margin-top: 20px; margin-bottom: 10px; font-family: inherit; line-height: 2.1;">AGM & AR</span><a href="javascript:void(0)" class="btn btn-primary edit_agm_ar" id="edit_agm_ar"style="margin-left: 10px; margin-top: -5px; padding: 6px 10px; font-size: 12px">Edit</a>
	<div id="agm_ar_interface">
		<div class="form-group" style="margin-top: 20px">
			<label class="col-xs-2" for="w2-username">First AGM/ Annual Return: </label>
			<div class="col-xs-8">
				<div class="confirmation_first_agm" id="confirmation_first_agm"></div>
			</div>
		</div>
		<div class="form-group" style="margin-top: 20px">
			<label class="col-xs-2" for="w2-username">Year End: </label>
			<div class="col-xs-8">
				<div class="confirmation_fye" id="confirmation_fye"></div>
			</div>
		</div>
		<div class="form-group" style="margin-top: 20px">
			<label class="col-xs-2" for="w2-username">AGM: </label>
			<div class="col-xs-8">
				<div class="confirmation_agm" id="confirmation_agm"></div>
			</div>
		</div>
		<div class="form-group" style="margin-top: 20px">
			<label class="col-xs-2" for="w2-username">Resolution Date: </label>
			<div class="col-xs-8">
				<div class="confirmation_reso_date" id="confirmation_reso_date"></div>
			</div>
		</div>
		<h4>Agenda</h4>
		<div class="form-group" style="margin-top: 20px">
			<label class="col-xs-2" for="w2-username">Activity Status: </label>
			<div class="col-xs-8">
				<div class="confirmation_activity_status" id="confirmation_activity_status"></div>
			</div>
		</div>
		<div class="form-group" style="margin-top: 20px">
			<label class="col-xs-2" for="w2-username">Solvency Status: </label>
			<div class="col-xs-8">
				<div class="confirmation_solvency_status" id="confirmation_solvency_status"></div>
			</div>
		</div>
		<div class="form-group" style="margin-top: 20px">
			<label class="col-xs-2" for="w2-username">EPC Status: </label>
			<div class="col-xs-8">
				<div class="confirmation_epc_status" id="confirmation_epc_status"></div>
			</div>
		</div>
		<div class="form-group" style="margin-top: 20px">
			<label class="col-xs-2" for="w2-username">Small Company: </label>
			<div class="col-xs-8">
				<div class="confirmation_small_company" id="confirmation_small_company"></div>
			</div>
		</div>
		<div class="form-group" style="margin-top: 20px">
			<label class="col-xs-2" for="w2-username">Financial Statements Audited: </label>
			<div class="col-xs-8">
				<div class="confirmation_financial_statements_audited" id="confirmation_financial_statements_audited"></div>
			</div>
		</div>
		<div class="form-group" style="margin-top: 20px">
			<label class="col-xs-2" for="w2-username">Share Transfer: </label>
			<div class="col-xs-8">
				<div class="confirmation_share_transfer" id="confirmation_share_transfer"></div>
			</div>
		</div>
		<div class="form-group" style="margin-top: 20px">
			<label class="col-xs-2" for="w2-username">Consent For Shorter Notice: </label>
			<div class="col-xs-8">
				<div class="confirmation_shorter_notice" id="confirmation_shorter_notice"></div>
			</div>
		</div>
		<div class="form-group" style="margin-top: 20px">
			<label class="col-xs-2" for="w2-username">Chairman: </label>
			<div class="col-xs-8">
				<div class="confirmation_chairman" id="confirmation_chairman"></div>
			</div>
		</div>
	</div>
	<div id="bottom_interface" style="margin-top: 10px;">
		<div class="form-group">
			<label class="col-sm-5" for="w2-username">
				<input type="checkbox" class="confirm_director_fee" id="confirm_director_fee" name="confirm_director_fee" onclick="directorFee(this);" disabled="true">  Director Fee
			</label>
			
		</div>
		<div class="form-group confirm_director_fee_div" style="display: none;">
			<div class="col-sm-8">
				<table class="table table-bordered table-striped table-condensed mb-none" style="width: 400px">
					<thead>
						<tr>
							<th style="width:250px; text-align: center">Director Name</th>
							<th style="width:150px; text-align: center">Fee</th>
						</tr>
					</thead>
					<tbody id="confirm_director_fee_add">
											
					</tbody>
				</table>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-5" for="w2-username">
				<input type="checkbox" class="confirm_dividend" id="confirm_dividend" name="confirm_dividend" onclick="dividendCheckBox(this);" disabled="true">  Dividend
			</label>
		</div>
		<div class="form-group confirm_dividend_div" style="display: none;">
			<div class="col-sm-8">
				<table class="table table-bordered table-striped table-condensed mb-none" style="width: 400px">
					<thead>
						<tr>
							<th style="text-align:center;">
								Total Dividend Declared
							</th>
							<th>
								<input type="text" style="text-transform:uppercase;" name="confirm_total_dividend" id="confirm_total_dividend" class="form-control numberdes" value="" readonly/>
							</th>
						</tr>
						<tr>
							<th style="width:250px; text-align: center">Member Name</th>
							<th style="width:150px; text-align: center">Dividend</th>
						</tr>
					</thead>
					<tbody id="confirm_dividend_add">
											
					</tbody>
					
				</table>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-5" for="w2-username">
				<input type="checkbox" class="confirm_amount_due_from_director" id="confirm_amount_due_from_director" name="confirm_amount_due_from_director" onclick="amountDueFromDirector(this);" disabled="true">  Amount Due From Director
			</label>
		</div>
		<div class="form-group confirm_amount_due_from_director_div" style="display: none;">
			<div class="col-sm-8">
				<table class="table table-bordered table-striped table-condensed mb-none" style="width: 400px">
					<thead>
						<tr>
							<th style="width:250px; text-align: center">Director Name</th>
							<th style="width:150px; text-align: center">Amount</th>
						</tr>
					</thead>
					<tbody id="confirm_amount_due_from_director_add">
											
					</tbody>
				</table>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-5" for="w2-username">
				<input type="checkbox" class="confirm_director_retirement" id="confirm_director_retirement" name="confirm_director_retirement" onclick="directorRetirement(this);" disabled="true">  Director Retirement
			</label>
		</div>
		<div class="form-group confirm_director_retirement_div" style="display: none;">
			<div class="col-sm-8">
				<table class="table table-bordered table-striped table-condensed mb-none" style="width: 700px">
					<thead>
						<tr>
							<th style="width:50px; text-align: center">No</th>
							<th style="width:250px; text-align: center">NRIC</th>
							<th style="width:250px; text-align: center">Director Name</th>
							<th style="width:150px; text-align: center">Retiring</th>
						</tr>
					</thead>
					<tbody id="confirm_director_retirement_add">
											
					</tbody>
				</table>
			</div>
		</div>

		<div class="form-group">
			<label class="col-sm-5" for="w2-username">
				<input type="checkbox" class="confirm_reappointment_auditor" id="confirm_reappointment_auditor" name="confirm_reappointment_auditor" onclick="reappointmentAuditor(this);" disabled="true">  Reappointment of Auditor
			</label>
		</div>

		<div class="form-group confirm_reappointment_auditor_div" style="display: none;">
			<div class="col-sm-8">
				<table class="table table-bordered table-striped table-condensed mb-none" style="width: 250px">
					<thead>
						<tr>
							<th style="width:250px; text-align: center">Auditor Name</th>
							
						</tr>
					</thead>
					<tbody id="confirm_reappointment_auditor_add">
											
					</tbody>
				</table>
			</div>
		</div>

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
	</div>
</div>
<script src="themes/default/assets/js/confirmation_agm_ar.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>