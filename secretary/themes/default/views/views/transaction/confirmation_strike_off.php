<div id="transaction_confirmation_strike_off">
	<h3>Strike Off Application</h3>
	<span style="color: inherit; font-size: 2.4rem; letter-spacing: -1px; font-weight: 500; margin-top: 20px; margin-bottom: 10px; font-family: inherit; line-height: 2.1;">Notice</span><a href="javascript:void(0)" class="btn btn-primary edit_strike_off_notice" id="edit_strike_off_notice"style="margin-left: 10px; margin-top: -5px; padding: 6px 10px; font-size: 12px">Edit</a>
	<div id="strike_off_notice_interface">
		<div class="form-group" style="margin-top: 20px;">
			<label class="col-sm-3">Company Name: </label>
			<div class="col-sm-8">
				<label id="confirmation_company_name"></label>
			</div>
		</div>
		<div class="form-group conf_shorter_notice" style="margin-top: 20px">
			<label class="col-sm-3" for="w2-username">Consent For Shorter Notice: </label>
			<div class="col-xs-8">
				<div class="confirmation_shorter_notice" id="confirmation_shorter_notice"></div>
			</div>
		</div>
		<div class="form-group conf_notice_date" style="margin-top: 20px">
			<label class="col-sm-3" for="w2-username">Notice Date: </label>
			<div class="col-xs-8">
				<div class="confirmation_notice_date" id="confirmation_notice_date"></div>
			</div>
		</div>
		<div class="form-group conf_agm_date" style="margin-top: 20px">
			<label class="col-sm-3" for="w2-username">AGM Date: </label>
			<div class="col-xs-8">
				<div class="confirmation_agm" id="confirmation_agm"></div>
			</div>
		</div>
		<!-- <div class="form-group conf_date_fs" style="margin-top: 20px">
			<label class="col-sm-3" for="w2-username">Date FS sent to members: </label>
			<div class="col-xs-8">
				<div class="confirmation_date_fs_sent_to_members" id="confirmation_date_fs_sent_to_members"></div>
			</div>
		</div> -->
		<div class="form-group conf_agm_time" style="margin-top: 20px">
			<label class="col-sm-3" for="w2-username">AGM Time: </label>
			<div class="col-xs-8">
				<div class="confirmation_agm_time" id="confirmation_agm_time"></div>
			</div>
		</div>
		<table class="table table-bordered table-striped table-condensed">
			<tr>
				<th style="width: 200px">Address</th>
				<td><label><input type="radio" id="confirmation_register_address_edit" name="address_type" value="Registered Office Address"/>&nbsp;&nbsp;Registered Office Address</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<label><input type="radio" id="confirmation_local_edit" name="address_type" value="Local"/>&nbsp;&nbsp;Local Address</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<label><input type="radio" id="confirmation_foreign_edit" name="address_type" value="Foreign"/>&nbsp;&nbsp;Foreign Address</label></td>
			</tr>
			<tr id="confirmation_tr_registered_edit">
				<th></th>
				<td>
					<div class="row">
						<div class="col-sm-2">
							<label>Postal Code :</label>
						</div>
						<div class="col-sm-10">
							<div class="input-group confirmation_registered_postal_code1" id="confirmation_registered_postal_code1"></div>
						</div>
					</div>

					<div class="row">
						<div class="col-sm-2">
							<label>Street Name :</label>
						</div>
						<div class="col-sm-10"> 
							<div class="input-group confirmation_registered_street_name1" id="confirmation_registered_street_name1">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-2">
							<label>Building Name :</label>
						</div>
						<div class="col-sm-10"> 
							<div class="input-group confirmation_registered_building_name1" style="width: 100%;" id="confirmation_registered_building_name1"></div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-2">
							<label>Unit No :</label>
						</div>
						<div class="col-sm-10"> 
							<div class="confirmation_registered_unit_no1" id="confirmation_registered_unit_no1" style="display: inline-block"></div> - 
							<div class="confirmation_registered_unit_no2" id="confirmation_registered_unit_no2" style="width: 15%;display: inline-block;" ></div>
						</div>
					</div>
				</td>
			</tr>
			<tr id="confirmation_tr_local_edit">
				<th></th>
				<td>
					<div class="row">
						<div class="col-sm-2">
							<label>Postal Code :</label>
						</div>
						<div class="col-sm-10">
							<div class="input-group confirmation_postal_code1" id="confirmation_postal_code1"></div>
						</div>
					</div>

					<div class="row">
						<div class="col-sm-2">
							<label>Street Name :</label>
						</div>
						<div  class="col-sm-10">
							<div class="input-group confirmation_street_name1" id="confirmation_street_name1">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-2">
							<label>Building Name :</label>
						</div>
						<div class="col-sm-10">
							<div class="input-group confirmation_building_name1" style="width: 100%;" id="confirmation_building_name1">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-2">
							<label >Unit No :</label>
						</div>
						<div class="col-sm-10"> 
							<div class="confirmation_unit_no1" id="confirmation_unit_no1" style="display: inline-block"></div> -
							<div class="confirmation_unit_no2" id="confirmation_unit_no2" style="width: 15%;display: inline-block;" ></div>
						</div>
					</div>
					
				</td>
			</tr>
			<tr id="confirmation_tr_foreign_edit">
				<td></td>
				<td colspan="2">
					<div class="row">
						<div class="col-sm-2">
							<label>Foreign Address :</label>
						</div>
						<div class="col-sm-10">
							<div class="input-group confirmation_foreign_address1" id="confirmation_foreign_address1">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-2">
							<label></label>
						</div>
						<div class="col-sm-10">
							<div class="input-group confirmation_foreign_address2" id="confirmation_foreign_address2">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-2">
							<label></label>
						</div>
						<div class="col-sm-10">
							<div class="input-group confirmation_foreign_address3" id="confirmation_foreign_address3">
							</div>
						</div>
					</div>
					
				</td>
			</tr>
		</table>
	</div>
	<span style="color: inherit; font-size: 2.4rem; letter-spacing: -1px; font-weight: 500; margin-top: 20px; margin-bottom: 10px; font-family: inherit; line-height: 2.1;">Reason</span><a href="javascript:void(0)" class="btn btn-primary edit_strike_off" id="edit_strike_off"style="margin-left: 10px; margin-top: -5px; padding: 6px 10px; font-size: 12px">Edit</a>
	<div id="strike_off_interface">
		<div class="form-group" style="margin-top: 20px">
			<label class="col-xs-3" for="w2-reason_for_appication">Reason For Application: </label>
			<div class="col-xs-6">
				<div class="confirmation_reason_for_appication" id="confirmation_reason_for_appication"></div>
			</div>
		</div>
		<div class="form-group confirmation_ceased_date_row" style="margin-top: 20px" style="display: none;">
			<label class="col-xs-3" for="w2-ceased_date">Ceased Date: </label>
			<div class="col-xs-6">
				<div class="confirmation_ceased_date" id="confirmation_ceased_date"></div>
			</div>
		</div>
	</div>
</div>
<script src="themes/default/assets/js/confirmation_strike_off.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>