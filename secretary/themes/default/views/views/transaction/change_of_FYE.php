<div id="w2-change_FYE_form" class="panel">
	<h3>Change of Financial Year End</h3>
	<form id="change_of_FYE_form" style="margin-top: 20px;">
		<input type="hidden" class="form-control company_code" id="company_code" name="company_code" value=""/>
		<input type="hidden" class="form-control hidden_company_name" id="hidden_company_name" name="company_name" value=""/>
		<input type="hidden" class="form-control hidden_old_FYE" id="hidden_old_FYE" name="old_FYE" value=""/>
		<input type="hidden" class="form-control hidden_old_period" id="hidden_old_period" name="old_period" value=""/>
		<input type="hidden" class="form-control transaction_change_FYE_id" id="transaction_change_FYE_id" name="transaction_change_FYE_id" value=""/>
		<button type="button" class="collapsible"><span style="font-size: 2.4rem;">Current Financial Year End</span></button>
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
				<label class="col-sm-2" for="w2-username">Company Name: </label>
				<div class="col-sm-8">
					<div style="width: 100%;">
						<div style="width: 75%;float:left;margin-right: 20px;">
							<label id="company_name"></label>
							
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2" for="w2-username">Year End: </label>
				<div class="col-sm-8">
					<div style="width: 100%;">
						<div style="width: 75%;float:left;margin-right: 20px;">
							<label id="FYE"></label>
							
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2" for="w2-username">Financial Year Cycle: </label>
				<div class="col-sm-8">
					<div style="width: 100%;">
						<div style="width: 75%;float:left;margin-right: 20px;">
							<label id="old_financial_year_period"></label>
						</div>
					</div>
				</div>
			</div>
		</div>
		<button type="button" class="collapsible"><span style="font-size: 2.4rem;">New Financial Year End</span></button>
		<div id="company_info" class="incorp_content">
			<div id="change_of_FYE_interface">
				<div class="form-group" style="margin-top: 20px">
					<label class="col-xs-3" for="w2-username">Year End: </label>
					<div class="col-xs-8">
						<div class="input-group mb-md" style="width: 200px;">
							<span class="input-group-addon">
								<i class="far fa-calendar-alt"></i>
							</span>
							<input type="text" class="form-control valid new_FYE" id="date_todolist" name="new_FYE" data-date-format="dd MM yyyy" data-plugin-datepicker="" required="" value="" placeholder="DD MM YYYY">
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3" for="w2-DS1">Financial Year Cycle:</label>
					<div class="col-sm-3">
						<select id="financial_year_period" class="form-control financial_year_period mb-md" id="financial_year_period" style="width:200px;" name="financial_year_period">
		                </select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3" for="w2-DS1">Effective Date:</label>
					<div class="col-sm-8">
						<div class="input-group" style="width: 200px;">
							<span class="input-group-addon">
								<i class="far fa-calendar-alt"></i>
							</span>
							<input type="text" class="form-control valid tab_2_effective_date" id="date_todolist" name="effective_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="DD/MM/YYYY">
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-12">
						<input type="button" class="btn btn-primary submitChangeFYEInfo" id="submitChangeFYEInfo" value="Save" style="float: right; margin-bottom: 20px; margin-top: 20px;">
					</div>
				</div>
			</div>
		</div>

		
	</form>
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
		      //content.style.border = "0px";
		    } else {
		      content.style.maxHeight = "100%";
		      //content.style.border = "1px solid #FEE8A6";
		    } 
		  });
		}
	});
</script>