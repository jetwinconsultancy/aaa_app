<div id="w2-takeover_secretarial_form" class="panel">
	<h3>Strike Off</h3>
	<form id="strike_off_form" style="margin-top: 20px;">
		<input type="hidden" class="form-control company_code" id="company_code" name="company_code" value=""/>
		<!-- <input type="hidden" class="form-control transaction_strike_off_id" id="transaction_strike_off_id" name="transaction_strike_off_id" value=""/> -->
		<div class="form-group">
			<label class="col-sm-2" for="w2-reason_for_application">Company Name: </label>
			<div class="col-sm-8">
				<input type="text" class="form-control" name="company_name">
			</div>
		</div>
		<div class="form-group ceased_date_row" style="display: none;">
			<label class="col-sm-2" for="w2-ceased_date">Company Address: </label>
			<div class="col-sm-8">
				<div class="input-group mb-md" style="width: 200px;">
					<input type="text" class="form-control valid" id="date_todolist" name="company_address" value="">
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-12">
				<input type="button" class="btn btn-primary submitTakeOverSecretarialInfo" id="submitTakeOverSecretarialInfo" value="Save" style="float: right; margin-bottom: 20px; margin-top: 20px;">
			</div>
		</div>
	</form>
</div>
<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>