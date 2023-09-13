<div id="w2-officer" class="panel">
	<h3>Appointment and Resignation of Secretarial</h3>
	<form id="apointment_of_secretarial_form" style="margin-top: 20px;">
		<input type="hidden" class="form-control company_code" id="company_code" name="company_code" value=""/>
		<input type="hidden" class="form-control hidden_company_name" id="hidden_company_name" name="company_name" value=""/>
		<button type="button" class="collapsible"><span style="font-size: 2.4rem;">Company Info</span></button>
		<div id="company_info" class="incorp_content">
			<div class="form-group" style="margin-top: 20px;">
				<label class="col-sm-2">Registration No: </label>
				<div class="col-sm-8">
					<div style="width: 100%;">
						<div style="width: 75%;float:left;margin-right: 20px;">
							<label id="registration_no"></label>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2">Company Name: </label>
				<div class="col-sm-8">
					<div style="width: 100%;">
						<div style="width: 75%;float:left;margin-right: 20px;">
							<label id="company_name"></label>
						</div>
					</div>
				</div>
			</div>
		</div>
		<button type="button" class="collapsible"><span style="font-size: 2.4rem;">Appointment of Secretarial</span></button>
		<div id="company_info" class="incorp_content">
		<table class="table table-bordered table-striped table-condensed" id="officer_table" style="margin-top: 20px; width: 1000px">
			<thead>
				<tr>
					<th id="id_position" style="text-align: center;width:170px">Position</th>
					<th id="id_header" style="text-align: center;width:270px">ID</th>
					<th style="text-align: center;width:270px" id="id_name">Name</th>
					<th style="text-align: center;width:170px">Date Of Appointment</div>
					<th><a href="javascript: void(0);" owspan=2 style="color: #D9A200;; outline: none !important;text-decoration: none;"><span id="appoint_new_secretarial_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Officer" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add Officer</span></a></th>
				</tr>
			</thead>
			<tbody id="body_appoint_new_secretarial">
			</tbody>
		</table>
		</div>
		<button type="button" class="collapsible"><span style="font-size: 2.4rem;">Resignation of Secretarial</span></button>
		<div id="company_info" class="incorp_content">
			<div class="form-group" style="margin-top: 20px">
				<label class="col-xs-3">Resignation of Corporate Secretarial Agent: </label>
				<div class="col-xs-5 div_autocomplete">
					<input type="text" style="text-transform:uppercase;" class="form-control" id="resignation_of_corporate_secretarial_agent" name="resignation_of_corporate_secretarial_agent" value="">
				</div>
			</div>
			<div class="form-group" style="margin-top: 20px">
				<label class="col-xs-3">Resignation of Corporate Secretarial Agent Address: </label>
				<div class="col-xs-5">
					<textarea type="text" style="text-transform:uppercase;" class="form-control" id="resignation_of_corporate_secretarial_agent_address" name="resignation_of_corporate_secretarial_agent_address" value=""></textarea>
				</div>
			</div>
			<div id="resign_auditor_table" style="margin-top: 20px;">
				<table class="table table-bordered table-striped table-condensed resign_secretarial" id="officer_table" style="width: 1010px">
					<thead>
						<tr>
							<th id="id_position" style="text-align: center;width:170px">Position</th>
							<th id="id_header" style="text-align: center;width:170px">ID</th>
							<th style="text-align: center;width:170px" id="id_name">Name</th>
							<th style="text-align: center;width:170px">Date Of Appointment</th>
							<th style="text-align: center;width:170px">Date Of Cessation</th>
							<th style="text-align: center;width:220px">Reason</th>
							<th></th>
						</tr>
					</thead>
					<tbody id="body_resign_secretarial">
					</tbody>
				</table>
			</div>
			<div id="no_secretarial_resign" style="margin-top: 20px;">
				<div>
					<span class="help-block">
						* No secretarial can resign.
					</span>
				</div>
			</div>
		</div>
	</form>
	<button type="button" class="collapsible"><span style="font-size: 2.4rem;">Service Engagement</span></button>
	<div id="company_info" class="incorp_content">
		<form id="billing_form" style="margin-top: 20px;">
			<div class="hidden"><input type="text" class="form-control" name="company_code" id="company_code" value="<?= isset($transaction_company_code) ? $transaction_company_code : ''?>"/></div>
			<table class="table table-bordered table-striped table-condensed" id="billing_table"style="margin-top: 20px;width: 1000px">
				<thead>
					<tr> 
						<th valign=middle style="width:210px;text-align: center">Service</th>
						<th valign=middle style="width:200px;text-align: center">Invoice Description</th>
						<th style="width:200px;text-align: center">Fee</th>
						<th style="width:180px;text-align: center">Unit Pricing</th>
						<th style="width:180px;text-align: center">Servicing Firm</th>
						<th style="width:80px;"><a href="javascript: void(0);" style="color: #D9A200;width:230px; outline: none !important;text-decoration: none;"><span id="billing_info_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Service Engagement Information" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add</span></a></th>
					</tr>
				</thead>
				<tbody id="body_billing_info">
				</tbody>
			</table>
		</form>
	</div>
	<div class="form-group">
		<div class="col-sm-12">
			<input type="button" class="btn btn-primary submitSecretarialInfo" id="submitSecretarialInfo" value="Save" style="float: right; margin-bottom: 20px; margin-top: 20px;">
		</div>
	</div>
</div>
<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>
<script type="text/javascript">
	$( document ).ready(function() {
		var coll = document.getElementsByClassName("collapsible");
		for (var g = 0; g < coll.length; g++) {
		    coll[g].classList.toggle("incorp_active");
		    coll[g].nextElementSibling.style.maxHeight = "100%";
		}
		for (var i = 0; i < coll.length; i++) {
		  coll[i].addEventListener("click", function() {
		    this.classList.toggle("incorp_active");
		    var content = this.nextElementSibling;
		    if (content.style.maxHeight){
		      content.style.maxHeight = null;
		    } else {
		      content.style.maxHeight = "100%";
		    } 
		  });
		}
	});
</script>