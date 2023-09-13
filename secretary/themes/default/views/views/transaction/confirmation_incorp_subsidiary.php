<div id="transaction_confirmation_incorp_subsidiary">
	<span style="color: inherit; font-size: 2.4rem; letter-spacing: -1px; font-weight: 500; margin-top: 20px; margin-bottom: 10px; font-family: inherit; line-height: 2.1;">Investment / To a Company</span><a href="javascript:void(0)" class="btn btn-primary edit_incorp_subsidiary" id="edit_incorp_subsidiary"style="margin-left: 10px; margin-top: -5px; padding: 6px 10px; font-size: 12px">Edit</a>
	<div id="incorp_subsidiary_interface">
		<div class="form-group" style="margin-top: 20px">
			<label class="col-xs-3" for="w2-subsidiary_name">Company Name: </label>
			<div class="col-xs-6">
				<div class="confirmation_subsidiary_name" id="subsidiary_name"></div>
			</div>
		</div>
		<div class="form-group" style="margin-top: 20px">
			<label class="col-xs-3" for="w2-country_of_incorporation">Country of Incorporation: </label>
			<div class="col-xs-6">
				<div class="confirmation_country_of_incorporation" id="country_of_incorporation"></div>
			</div>
		</div>
		<div class="form-group" style="margin-top: 20px">
			<label class="col-xs-3" for="w2-currency">Currency: </label>
			<div class="col-xs-6">
				<div class="confirmation_currency" id="currency"></div>
			</div>
		</div>
		<div class="form-group" style="margin-top: 20px">
			<label class="col-xs-3" for="w2-total_investment_amount">Total Investment Amount: </label>
			<div class="col-xs-6">
				<div class="confirmation_total_investment_amount" id="total_investment_amount"></div>
			</div>
		</div>
		<div class="form-group" style="margin-top: 20px">
			<label class="col-xs-3" for="w2-corp_rep_name">Corporate Representative Name: </label>
			<div class="col-xs-6">
				<div class="confirmation_corp_rep_name" id="corp_rep_name"></div>
			</div>
		</div>
		<div class="form-group" style="margin-top: 20px">
			<label class="col-xs-3" for="w2-corp_rep_identity_number">Corporate Representative NIRC/Passport: </label>
			<div class="col-xs-6">
				<div class="confirmation_corp_rep_identity_number" id="corp_rep_identity_number"></div>
			</div>
		</div>
		<div class="form-group" style="margin-top: 20px">
			<label class="col-xs-3" for="w2-propose_effective_date">Propose Effective Date: </label>
			<div class="col-xs-6">
				<div class="confirmation_propose_effective_date" id="propose_effective_date"></div>
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
<script src="themes/default/assets/js/confirmation_incorp_subsidiary.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>