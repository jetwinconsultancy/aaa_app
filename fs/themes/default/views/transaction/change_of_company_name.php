<div id="w2-change_company_name_form" class="panel">
	<h3>Change of Company Name</h3>
	<form id="change_of_company_name_form" style="margin-top: 20px;">
		<input type="hidden" class="form-control company_code" id="company_code" name="company_code" value=""/>
		<input type="hidden" class="form-control hidden_company_name" id="hidden_company_name" name="company_name" value=""/>
		<input type="hidden" class="form-control hidden_old_company_name" id="hidden_old_company_name" name="old_company_name" value=""/>
		<input type="hidden" class="form-control transaction_change_company_name_id" id="transaction_change_company_name_id" name="transaction_change_company_name_id" value=""/>
		<h4>Current Company Name</h4>
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
		<h4>New Company Name</h4>
		<div id="change_of_company_name_interface">
			<div class="form-group" style="margin-top: 20px">
				<label class="col-xs-2" for="w2-username">Company Name: </label>
				<div class="col-xs-8">
					<input type="text" style="text-transform:uppercase; width: 300px;" class="form-control" id="new_company_name" name="new_company_name" value="">
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-12">
				<input type="button" class="btn btn-primary submitChangeCompanyNameInfo" id="submitChangeCompanyNameInfo" value="Save" style="float: right; margin-bottom: 20px; margin-top: 20px;">
			</div>
		</div>
	</form>
</div>
<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>