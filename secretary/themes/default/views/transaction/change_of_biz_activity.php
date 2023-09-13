<div id="w2-change_biz_activity_form" class="panel">
	<h3>Change of Business Activity</h3>
	<form id="change_of_biz_activity_form" style="margin-top: 20px;">
		<input type="hidden" class="form-control company_code" id="company_code" name="company_code" value=""/>
		<input type="hidden" class="form-control hidden_company_name" id="hidden_company_name" name="company_name" value=""/>
		<input type="hidden" class="form-control hidden_old_activity1" id="hidden_old_activity1" name="old_activity1" value=""/>
		<input type="hidden" class="form-control hidden_old_description1" id="hidden_old_description1" name="old_description1" value=""/>
		<input type="hidden" class="form-control hidden_old_activity2" id="hidden_old_activity2" name="old_activity2" value=""/>
		<input type="hidden" class="form-control hidden_old_description2" id="hidden_old_description2" name="old_description2" value=""/>
		<input type="hidden" class="form-control transaction_change_biz_activity_id" id="transaction_change_biz_activity_id" name="transaction_change_biz_activity_id" value=""/>
		<button type="button" class="collapsible"><span style="font-size: 2.4rem;">Current Business Activity</span></button>
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
			<div class="form-group">
				<label class="col-sm-2">Activity 1: </label>
				<div class="col-sm-8">
					<div style="width: 100%;">
						<div style="width: 75%;float:left;margin-right: 20px;">
							<label id="activity_1"></label>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2">Description 1: </label>
				<div class="col-sm-8">
					<div style="width: 100%;">
						<div style="width: 75%;float:left;margin-right: 20px;">
							<label id="description_1"></label>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2">Activity 2: </label>
				<div class="col-sm-8">
					<div style="width: 100%;">
						<div style="width: 75%;float:left;margin-right: 20px;">
							<label id="activity_2"></label>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2">Description 2: </label>
				<div class="col-sm-8">
					<div style="width: 100%;">
						<div style="width: 75%;float:left;margin-right: 20px;">
							<label id="description_2"></label>
						</div>
					</div>
				</div>
			</div>
		</div>
		<button type="button" class="collapsible"><span style="font-size: 2.4rem;">New Business Activity</span></button>
		<div id="company_info" class="incorp_content">
			<div id="change_of_biz_activity_interface">
				<div class="form-group" style="margin-top: 20px">
					<label class="col-xs-2">Activity 1: </label>
					<div class="col-xs-8 div_autocomplete">
						<input type="text" style="text-transform:uppercase;" class="form-control" id="new_activity1" name="new_activity1" value="">
					</div>
				</div>
				<div class="form-group" style="margin-top: 20px">
					<label class="col-xs-2">Description 1: </label>
					<div class="col-xs-8">
						<textarea type="text" style="text-transform:uppercase;" class="form-control" id="new_description1" name="new_description1" value=""></textarea>
					</div>
				</div>
				<div class="form-group" style="margin-top: 20px">
					<label class="col-xs-2">Remove Activity 2: </label>
					<div class="col-xs-8">
						<input type="checkbox" class="remove_activity_2" id="remove_activity_2" name="remove_activity_2" onclick="removeActivity2(this);">
					</div>
				</div>
				
				<div class="form-group" style="margin-top: 20px">
					<label class="col-xs-2">Activity 2: </label>
					<div class="col-xs-8 div_autocomplete">
						<input type="text" style="text-transform:uppercase;" class="form-control" id="new_activity2" name="new_activity2" value="">
					</div>
				</div>
				<div class="form-group" style="margin-top: 20px">
					<label class="col-xs-2">Description 2: </label>
					<div class="col-xs-8">
						<textarea type="text" style="text-transform:uppercase;" class="form-control" id="new_description2" name="new_description2" value=""></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-2" for="w2-DS1">Effective Date:</label>
					<div class="col-xs-8">
						<div class="input-group" style="width: 200px;">
							<span class="input-group-addon">
								<i class="far fa-calendar-alt"></i>
							</span>
							<input type="text" class="form-control valid tab_2_effective_date" id="date_todolist" name="effective_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="DD/MM/YYYY">
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-12">
				<input type="button" class="btn btn-primary submitChangeBizActivityInfo" id="submitChangeBizActivityInfo" value="Save" style="float: right; margin-bottom: 20px; margin-top: 20px;">
			</div>
		</div>
	</form>
</div>
<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>
<script type="text/javascript">
	var business_activity_list_data = JSON.parse(localStorage.getItem("business_activity_list"));

	$('#new_activity1').autoSuggestList({
		list: business_activity_list_data,
	});

	$('#new_activity2').autoSuggestList({
		list: business_activity_list_data,
	});

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