<div id="w2-engagement_letter" class="panel">
	<h3>Engagement Letter Info</h3>
	<form id="engagement_letter_form" style="margin-top: 20px;">
		<input type="hidden" class="form-control company_code" id="company_code" name="company_code" value=""/>
		<input type="hidden" class="form-control trans_master_service_proposal_id" id="trans_master_service_proposal_id" name="trans_master_service_proposal_id" value=""/>
		<!-- <span style="font-size: 1.7rem;padding: 0; margin: 7px 0px 4px 0;">Services</span> -->
		<div class="form-group">
			<label class="col-sm-2" for="w2-DS2">Engagement Letter Date:</label>
			<div class="col-sm-8">
				<div class="input-group" style="width: 200px;">
					<span class="input-group-addon">
						<i class="far fa-calendar-alt"></i>
					</span>
					<input type="text" class="form-control valid engagement_letter_date" id="date_todolist" name="engagement_letter_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" required="" value="" placeholder="DD/MM/YYYY">
				</div>
			</div>
		</div>
		<div class="form-group" style="margin-top: 20px">
			<label class="col-sm-2" for="w2-username">Registration No: </label>
			<div class="col-sm-2">
				<input type="text" style="text-transform:uppercase" class="form-control" id="el_uen" name="uen" value="" >
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2" for="w2-DS2">FYE Date:</label>
			<div class="col-sm-8">
				<div class="input-group" style="width: 200px;">
					<span class="input-group-addon">
						<i class="far fa-calendar-alt"></i>
					</span>
					<input type="text" class="form-control valid fye_date" id="date_todolist" name="fye_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" required="" value="" placeholder="DD/MM/YYYY">
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2" for="w2-username">Director Signing: </label>
			<div class="col-sm-3">
				<input type="text" style="text-transform:uppercase" class="form-control" id="director_signing" name="director_signing" value="" >
			</div>
		</div>
		<table class="table table-bordered table-striped table-condensed mb-none" id="engagement_letter_table" style="width: 1010px; margin-top: 20px">
			<thead>
				<tr>
					<th style="text-align: center;width:70px"></th>
					<th style="text-align: center;width:200px">Engagement Letter Name</th>
					<th style="text-align: center;width:170px">Currency</th>
					<th style="text-align: center;width:170px"><span data-toggle="tooltip" data-trigger="hover" data-original-title="If the fee is set to $0.00, some content will be hide. (Only for ML Quarterly Statements, PMFT Audit, and Secretarial Services Agreement)" style="font-size:14px;">Fee <i class="fa fa-info-circle"></i></span></th>
					<th style="text-align: center;width:220px">Unit Pricing (Per)</th>
					<th style="text-align: center;width:170px">Servicing Firm</th>
				</tr>
				
			</thead>
			<tbody id="body_engagement_letter">
			</tbody>
			
		</table>
		<div class="form-group">
			<div class="col-sm-12">
				<input type="button" class="btn btn-primary submitEngagementLetterInfo" id="submitEngagementLetterInfo" value="Save" style="float: right; margin-bottom: 20px; margin-top: 20px;">
			</div>
		</div>
	</form>
</div>
<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>
<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
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
		      //content.style.border = "0px";
		    } else {
		      content.style.maxHeight = "100%";
		      //content.style.border = "1px solid #FEE8A6";
		    } 
		  });
		}
	});
</script>