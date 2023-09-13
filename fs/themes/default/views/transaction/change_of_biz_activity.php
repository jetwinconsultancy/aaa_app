<div id="w2-change_biz_activity_form" class="panel">
	<h3>Change of Biz Activity</h3>
	<form id="change_of_biz_activity_form" style="margin-top: 20px;">
		<input type="hidden" class="form-control company_code" id="company_code" name="company_code" value=""/>
		<input type="hidden" class="form-control hidden_company_name" id="hidden_company_name" name="company_name" value=""/>
		<input type="hidden" class="form-control hidden_old_activity1" id="hidden_old_activity1" name="old_activity1" value=""/>
		<input type="hidden" class="form-control hidden_old_activity2" id="hidden_old_activity2" name="old_activity2" value=""/>
		<input type="hidden" class="form-control transaction_change_biz_activity_id" id="transaction_change_biz_activity_id" name="transaction_change_biz_activity_id" value=""/>
		<h4>Current Activity</h4>
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
			<label class="col-sm-2" for="w2-username">Activity 1: </label>
			<div class="col-sm-8">
				<div style="width: 100%;">
					<div style="width: 75%;float:left;margin-right: 20px;">
						<label id="activity_1"></label>
						
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2" for="w2-username">Activity 2: </label>
			<div class="col-sm-8">
				<div style="width: 100%;">
					<div style="width: 75%;float:left;margin-right: 20px;">
						<label id="activity_2"></label>
						
					</div>
				</div>
			</div>
		</div>
		<h4>New Activity</h4>
		<div id="change_of_biz_activity_interface">
			<div class="form-group" style="margin-top: 20px">
				<label class="col-xs-2" for="w2-username">Activity 1: </label>
				<div class="col-xs-8">
					<input type="text" style="text-transform:uppercase;" class="form-control" id="new_activity1" name="new_activity1" value="">
				</div>
			</div>
			<div class="form-group" style="margin-top: 20px">
				<label class="col-xs-2" for="w2-username">Remove Activity 2: </label>
				<div class="col-xs-8">
					<input type="checkbox" class="remove_activity_2" id="remove_activity_2" name="remove_activity_2" onclick="removeActivity2(this);">
				</div>
			</div>
			
			<div class="form-group" style="margin-top: 20px">
				<label class="col-xs-2" for="w2-username">Activity 2: </label>
				<div class="col-xs-8">
					<input type="text" style="text-transform:uppercase;" class="form-control" id="new_activity2" name="new_activity2" value="">
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