<div id="w2-ml_quarterly_statements" class="panel">
	<h3>ML Quarterly Statements</h3>
	<form id="ml_quarterly_statements_form" style="margin-top: 20px;">
		<input type="hidden" class="form-control company_code" id="company_code" name="company_code" value=""/>
		<div class="form-group">
			<label class="col-sm-2" for="w2-username">Company Name: </label>
			<div class="col-sm-8">
				<div style="width: 100%;">
					<div style="width: 75%;float:left;margin-right: 20px;">
						<label id="company_name"></label>
					</div>
				</div>
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
							<input type="text" style="text-transform:uppercase" class="form-control" id="postal_code" name="postal_code" value="" maxlength="6">
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
							<input type="text" style="text-transform:uppercase" class="form-control" id="street_name" name="street_name" value="">
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
							<input type="text" style="text-transform:uppercase" class="form-control" id="building_name" name="building_name" value="">
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2">Unit No :</label>
			<div class="col-sm-8">
				<input style="width: 8%; float: left; margin-right: 10px; text-transform:uppercase;" type="text" class="form-control" id="unit_no1" name="unit_no1" value="" maxlength="3">
				<label style="float: left; margin-right: 10px;" >-</label>
				<input style="width: 14%; text-transform:uppercase;" type="text" class="form-control" id="unit_no2" name="unit_no2" value="" maxlength="10">
			</div>
		</div>
		


		<div class="form-group">
			<div class="col-sm-12">
				<input type="button" class="btn btn-primary submitMlQuarterlyStatementsInfo" id="submitMlQuarterlyStatementsInfo" value="Save" style="float: right; margin-bottom: 20px; margin-top: 20px;">
			</div>
		</div>
	</form>
</div>
<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>