<div id="transaction_confirmation_issue_dividend">
	<span style="color: inherit; font-size: 2.4rem; letter-spacing: -1px; font-weight: 500; margin-top: 20px; margin-bottom: 10px; font-family: inherit; line-height: 2.1;">Issue Director Fee</span><a href="javascript:void(0)" class="btn btn-primary edit_issue_dividend" id="edit_issue_dividend"style="margin-left: 10px; margin-top: -5px; padding: 6px 10px; font-size: 12px">Edit</a>
	<div id="issue_dividend_interface">
		<div class="form-group" style="margin-top: 20px">
			<label class="col-xs-3" for="w2-currency">Currency: </label>
			<div class="col-xs-6">
				<div class="confirmation_currency" id="confirmation_currency"></div>
			</div>
		</div>
		<div class="form-group" style="margin-top: 20px">
			<label class="col-xs-3" for="w2-total_dividend_amount">Total Dividend Amount: </label>
			<div class="col-xs-6">
				<div class="confirmation_total_dividend_amount" id="confirmation_total_dividend_amount"></div>
			</div>
		</div>
		<div class="form-group" style="margin-top: 20px">
			<label class="col-xs-3" for="w2-declare_of_fye">Declare of FYE: </label>
			<div class="col-xs-6">
				<div class="confirmation_declare_of_fye" id="confirmation_declare_of_fye"></div>
			</div>
		</div>
		<div class="form-group" style="margin-top: 20px">
			<label class="col-xs-3" for="w2-devidend_of_cut_off_date">Devidend of Cut Off Date: </label>
			<div class="col-xs-6">
				<div class="confirmation_devidend_of_cut_off_date" id="confirmation_devidend_of_cut_off_date"></div>
			</div>
		</div>
		<div class="form-group" style="margin-top: 20px">
			<label class="col-xs-3" for="w2-devidend_payment_date">Devidend Payment Dat: </label>
			<div class="col-xs-6">
				<div class="confirmation_devidend_payment_date" id="confirmation_devidend_payment_date"></div>
			</div>
		</div>
		<div class="form-group" style="margin-top: 20px">
			<label class="col-xs-3" for="w2-nature">Nature: </label>
			<div class="col-xs-6">
				<div class="confirmation_nature" id="confirmation_nature"></div>
			</div>
		</div>
		<h4>Member</h4>
		<table class="table table-bordered table-striped table-condensed mb-none" id="latest_director_table" style="width: 750px">
			<thead>
				<tr>
					<th style="text-align: center;width:270px" id="id_name">Shareholder Name</th>
					<th style="text-align: center;width:270px">Number of Share</th>
					<th style="text-align: center;width:270px">Devidend Paid</div>
				</tr>
				
			</thead>
			<tbody id="confirmation_body_issue_dividend">
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
<script src="themes/default/assets/js/confirmation_issue_dividend.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>