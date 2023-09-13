<div id="w2-officer" class="panel">
	<h3>Opening Bank Account</h3>
	<form id="opening_bank_account_form" style="margin-top: 20px;">
		<input type="hidden" class="form-control company_code" id="company_code" name="company_code" value=""/>
		<div class="form-group">
			<label class="col-sm-2" for="w2-username">Bank: </label>
			<div class="col-sm-8">
				<div style="width: 100%;">
					<div style="width: 75%;float:left;margin-right: 20px;">
						<select class="form-control" style="width: 200px;" name="bank" id="bank">
							<option value="0">Select Bank</option>
						</select>
						
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2" for="w2-username">Manner of Operation: </label>
			<div class="col-sm-8">
				<div style="width: 100%;">
					<div style="width: 75%;float:left;margin-right: 20px;">
						<select class="form-control" style="width: 230px;" name="manner_of_operation" id="manner_of_operation">
							<option value="0">Select Manner Of Operation</option>
						</select>
						
					</div>
				</div>
			</div>
		</div>
		<table class="table table-bordered table-striped table-condensed mb-none" id="officer_table" style="width: 750px">
			<thead>
				<tr>
					<!-- <th id="id_position" style="text-align: center;width:170px">Position</th> -->
					<th id="id_header" style="text-align: center;width:270px">ID</th>
					<th style="text-align: center;width:270px" id="id_name">Name</th>
					<!-- <th style="text-align: center;width:170px">Date Of Appointment</div> -->
					<th><a href="javascript: void(0);" owspan=2 style="color: #D9A200;; outline: none !important;text-decoration: none;"><span id="authorised_person_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Authorised Person" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add Authorised Person</span></a></th>
				</tr>
				
			</thead>
			<tbody id="body_authorised_person">
			</tbody>
			
		</table>
		<div>
			<h4>Banker's Contact Person</h4>
		</div>
		<div class="form-group">
			<label class="col-sm-2" for="w2-username">Name: </label>
			<div class="col-sm-8">
				<div style="width: 100%;">
					<div style="width: 75%;float:left;margin-right: 20px;">
						<select class="form-control" style="width: 200px;" name="banker_name" id="banker_name">
							<option value="0">Select Name</option>
						</select>
						
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2" for="w2-username">Email: </label>
			<div class="col-sm-8">
				<div style="width: 100%;">
					<div style="width: 75%;float:left;margin-right: 20px;">
						<label id="banker_email"></label>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2" for="w2-username">Office Number: </label>
			<div class="col-sm-8">
				<div style="width: 100%;">
					<div style="width: 75%;float:left;margin-right: 20px;">
						<label id="office_number"></label>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2" for="w2-username">Mobile Number: </label>
			<div class="col-sm-8">
				<div style="width: 100%;">
					<div style="width: 75%;float:left;margin-right: 20px;">
						<label id="mobile_number"></label>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2" for="w2-username">Bank: </label>
			<div class="col-sm-8">
				<div style="width: 100%;">
					<div style="width: 75%;float:left;margin-right: 20px;">
						<label id="banker_bank_name"></label>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-12">
				<input type="button" class="btn btn-primary submitAuthorisedPersonInfo" id="submitAuthorisedPersonInfo" value="Save" style="float: right; margin-bottom: 20px; margin-top: 20px;">
			</div>
		</div>
	</form>
</div>
<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>