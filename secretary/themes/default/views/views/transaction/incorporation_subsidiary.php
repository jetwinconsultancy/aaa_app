<div id="w2-incorp_subsidiary_form" class="panel">
	<h3>Investment / To a Company</h3>
	<form id="incorp_subsidiary_form" style="margin-top: 20px;">
		<input type="hidden" class="form-control company_code" id="company_code" name="company_code" value=""/>
		<input type="hidden" class="form-control transaction_incorp_subsidiary_id" id="transaction_incorp_subsidiary_id" name="transaction_incorp_subsidiary_id" value=""/>
		<div class="form-group">
			<label class="col-sm-2" for="w2-subsidiary_name">Company Name: </label>
			<div class="col-sm-8">
				<div style="width: 100%;">
					<div style="width: 75%;float:left;margin-right: 20px;">
						<input class="form-control" style="width: 300px; text-transform:uppercase;" name="subsidiary_name" id="subsidiary_name">
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2" for="w2-country_of_incorporation">Country of Incorporation: </label>
			<div class="col-sm-8">
				<div style="width: 100%;">
					<div style="width: 75%;float:left;margin-right: 20px;">
						<input class="form-control" style="width: 200px; text-transform:uppercase;" name="country_of_incorporation" id="country_of_incorporation">
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2" for="w2-currency">Currency: </label>
			<div class="col-sm-8">
				<div style="width: 100%;">
					<div style="width: 75%;float:left;margin-right: 20px;">
						<select class="form-control currency" style="width: 200px;" name="currency" id="currency"">
							<option value="0">Select Currency</option>
						</select>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2" for="w2-total_investment_amount">Total Investment Amount: </label>
			<div class="col-sm-8">
				<div style="width: 100%;">
					<div style="width: 75%;float:left;margin-right: 20px;">
						<input class="form-control numberdes" style="text-align: right; width: 200px;" name="total_investment_amount" id="total_investment_amount" pattern="^[0-9,]+$">
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2" for="w2-corp_rep_name">Corporate Representative Name: </label>
			<div class="col-sm-8">
				<div style="width: 100%;">
					<div style="width: 75%;float:left;margin-right: 20px;">
						<input class="form-control" style="width: 200px; text-transform:uppercase;" name="corp_rep_name" id="corp_rep_name">
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2" for="w2-corp_rep_identity_number">Corporate Representative NIRC/Passport: </label>
			<div class="col-sm-8">
				<div style="width: 100%;">
					<div style="width: 75%;float:left;margin-right: 20px;">
						<input class="form-control" style="width: 200px; text-transform:uppercase;" name="corp_rep_identity_number" id="corp_rep_identity_number">
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2" for="w2-propose_effective_date">Propose Effective Date: </label>
			<div class="col-sm-8">
				<div class="input-group mb-md" style="width: 200px;">
					<span class="input-group-addon">
						<i class="far fa-calendar-alt"></i>
					</span>
					<input type="text" class="form-control valid propose_effective_date" id="date_todolist" name="propose_effective_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" required="" value="" placeholder="DD/MM/YYYY">
				</div>
			</div>
		</div>
		<!-- <table class="table table-bordered table-striped table-condensed mb-none" id="corp_rep_table" style="width: 1050px">
			<thead>
				<tr>
					<th id="subsidiary_name" style="text-align: center;width:200px">Subsidiary Name</th>
					<th id="corp_rep_name" style="text-align: center;width:200px">Name</th>
					<th style="text-align: center;width:150px" id="corp_rep_identification_num">NIRC/Passport</th>
					<th style="text-align: center;width:150px" id="effective_date">Effective Date</th>
					<th style="text-align: center;width:150px" id="cessation_date">Cessation Date</th>
					<th><a href="javascript: void(0);" style="color: #D9A200;; outline: none !important;text-decoration: none;"><span id="corp_rep_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Corporate Representative" style="font-size:14px; width:200px"><i class="fa fa-plus-circle"></i> Add Corporate</span></a></th>
				</tr>
				
			</thead>
			<tbody id="body_corp_rep">
			</tbody>
			
		</table> -->

		<div class="form-group">
			<div class="col-sm-12">
				<input type="button" class="btn btn-primary submitIncorpSubsidiaryInfo" id="submitIncorpSubsidiaryInfo" value="Save" style="float: right; margin-bottom: 20px; margin-top: 20px;">
			</div>
		</div>
	</form>
</div>
<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>