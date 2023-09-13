<div id="w2-strike_off_form" class="panel">
	<h3>Strike Off</h3>
	<form id="strike_off_form" style="margin-top: 20px;">
		<input type="hidden" class="form-control company_code" id="company_code" name="company_code" value=""/>
		<!-- <input type="hidden" class="form-control transaction_strike_off_id" id="transaction_strike_off_id" name="transaction_strike_off_id" value=""/> -->
		<div class="form-group">
			<label class="col-sm-2" for="w2-reason_for_application">Reason For Application: </label>
			<div class="col-sm-8">
				<select class="form-control reason_for_application" style="width: 800px;" name="reason_for_appication" id="reason_for_appication""><option value="0">Select Reason Of Application</option></select>
			</div>
		</div>
		<div class="form-group ceased_date_row" style="display: none;">
			<label class="col-sm-2" for="w2-ceased_date">Ceased Date: </label>
			<div class="col-sm-8">
				<div class="input-group mb-md" style="width: 200px;">
					<span class="input-group-addon">
						<i class="far fa-calendar-alt"></i>
					</span>
					<input type="text" class="form-control valid ceased_date" id="date_todolist" name="ceased_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" required="" value="" placeholder="DD/MM/YYYY" disabled="disabled">
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-12">
				<input type="button" class="btn btn-primary submitStrikeOffInfo" id="submitStrikeOffInfo" value="Save" style="float: right; margin-bottom: 20px; margin-top: 20px;">
			</div>
		</div>
	</form>
</div>
<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>