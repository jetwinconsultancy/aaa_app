<div id="w2-service_proposal" class="panel">
	<h3>Service Proposal</h3>
	<form id="service_proposal_form" style="margin-top: 20px;">
		<input type="hidden" class="form-control company_code" id="company_code" name="company_code" value=""/>
		<div class="form-group">
			<label class="col-sm-2" for="w2-DS2">Proposal Date:</label>
			<div class="col-sm-8">
				<div class="input-group mb-md" style="width: 200px;">
					<span class="input-group-addon">
						<i class="far fa-calendar-alt"></i>
					</span>
					<input type="text" class="form-control valid proposal_date" id="date_todolist proposal_date" name="proposal_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" required="" value="" placeholder="DD/MM/YYYY">
				</div>
			</div>
		</div>
		<span style="font-size: 1.7rem;padding: 0; margin: 7px 0px 4px 0;">Principal Activities</span>
		<div class="form-group" style="margin-top: 20px">
			<label class="col-sm-2" for="w2-username">Activity 1: </label>
			<div class="col-sm-8">
				<input type="text" style="text-transform:uppercase" class="form-control" id="activity1" name="activity1" value="" >
			</div>
			
		</div>
		<div class="form-group">
			<label class="col-sm-2" for="w2-username">Activity 2: </label>
			<div class="col-sm-8">
				<input type="text" style="text-transform:uppercase" class="form-control" id="activity2" name="activity2" value="" >
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
		<span style="font-size: 1.7rem;padding: 0; margin: 7px 0px 4px 0;">Contact Information</span>
			<div class="form-group" style="margin-top: 20px">
				<label class="col-sm-2" for="w2-chairman">Name:</label>
				<div class="col-sm-9">
					<input type="text" style="width:400px;text-transform:uppercase;" class="form-control" name="contact_name" id="contact_name" value="<?=$client_contact_info[0]->name?>"/>
	            </div>
				
			</div>
			<div class="form-group">
				<label class="col-sm-2" for="w2-chairman">Phone:</label>
				<div class="col-sm-9">
					<!-- <input type="text" style="width:400px;" class="form-control" name="contact_phone" id="contact_phone" value="<?=$client_contact_info[0]->phone?>"/> -->

				<div class="input-group fieldGroup_contact_phone">
					<input type="tel" class="form-control check_empty_contact_phone main_contact_phone hp" id="contact_phone" name="contact_phone[]" value="<?=$client_contact_info[0]->contact_phone?>"/>

					<input type="hidden" class="form-control input-xs hidden_contact_phone main_hidden_contact_phone" id="hidden_contact_phone" name="hidden_contact_phone[]" value=""/>

					<label class="radio-inline control-label" style="margin-top: -30px; margin-left: 20px;"><input type="radio" class="contact_phone_primary main_contact_phone_primary" name="contact_phone_primary" value="1" checked> Primary</label>

					<!-- <span class="input-group-btn" style="vertical-align: top !important;"> -->
					<input class="btn btn-primary button_increment_contact_phone addMore_contact_phone" type="button" id="create_button" value="+" style="margin-left: 20px; margin-top: -26px; border-radius: 3px;visibility: hidden; width: 35px;"/>
					<!-- </span> -->

					<button type="button" class="btn btn-default btn-sm show_contact_phone" style="margin-left: 20px; margin-top: -23px; visibility: hidden;">
					  <span class="fa fa-arrow-down" aria-hidden="true"></span>&nbsp<span class="toggle_word">Show more</span>
					</button>

				</div>

				<div class="contact_phone_toggle">
				</div>

				<div class="input-group fieldGroupCopy_contact_phone contact_phone_disabled" style="display: none;">
					<input type="tel" class="form-control check_empty_contact_phone second_contact_phone second_hp" id="contact_phone" name="contact_phone[]" value=""/>

					<input type="hidden" class="form-control input-xs hidden_contact_phone" id="hidden_contact_phone" name="hidden_contact_phone[]" value=""/>

					<label class="radio-inline control-label" style="margin-top: -30px; margin-left: 20px;"><input type="radio" class="contact_phone_primary" name="contact_phone_primary" value="1"> Primary</label>

					<!-- <span class="input-group-btn" style="vertical-align: top !important;"> -->
					<input class="btn btn-primary button_decrease_contact_phone remove_contact_phone" type="button" id="create_button" value="-" style="margin-left: 20px; margin-top: -26px; border-radius: 3px; width: 35px;"/>
				<!-- </span> -->
				</div>

				<div id="form_contact_phone"></div>

	        </div>
		</div>
		<div class="form-group">
			<label class="col-sm-2" for="w2-chairman">Email:</label>
			<div class="col-sm-9">
						<!-- <input type="email" style="width:400px;" class="form-control" name="contact_email" id="contact_email" value="<?=$client_contact_info[0]->email?>"/> -->
				<div class="input-group fieldGroup_contact_email" style="display: block !important;">
					<input type="text" class="form-control input-xs check_empty_contact_email main_contact_email" id="contact_email" name="contact_email[]" value="<?=$client_contact_info[0]->contact_email?>" style="text-transform:uppercase; width:400px;"/>

					<label class="radio-inline control-label" style="margin-left: 20px;"><input type="radio" class="contact_email_primary main_contact_email_primary" name="contact_email_primary" value="1" checked> Primary</label>

					<!-- <span class="input-group-btn" style="vertical-align: top !important;"> -->
					<input class="btn btn-primary button_increment_contact_email addMore_contact_email" type="button" id="create_button" value="+" style="margin-left: 20px; border-radius: 3px;visibility: hidden; width: 35px;"/>
					<!-- </span> -->

					<button type="button" class="btn btn-default btn-sm show_contact_email" style="margin-left: 20px; visibility: hidden;">
					  <span class="fa fa-arrow-down" aria-hidden="true"></span>&nbsp<span class="toggle_word">Show more</span>
					</button>

				</div>

				<div class="contact_email_toggle">
				</div>

				<div class="input-group fieldGroupCopy_contact_email contact_email_disabled" style="display: none;">
					<input type="text" class="form-control input-xs check_empty_contact_email second_contact_email" id="contact_email" name="contact_email[]" value="" style="width:400px; text-transform:uppercase; "/>
					<label class="radio-inline control-label" style="margin-left: 20px;"><input type="radio" class="contact_email_primary" name="contact_email_primary" value="1"> Primary</label>

					<!-- <span class="input-group-btn" style="vertical-align: top !important;"> -->
					<input class="btn btn-primary button_decrease_contact_email remove_contact_email" type="button" id="create_button" value="-" style="margin-left: 20px; border-radius: 3px; width: 35px;"/>
					<!-- </span> -->
				</div>

				<div id="form_contact_email"></div>
	        </div>
			
		</div>
		<span style="font-size: 1.7rem;padding: 0; margin: 7px 0px 4px 0;">Services</span>
		<table class="table table-bordered table-striped table-condensed mb-none" id="service_proposal_table" style="width: 1010px; margin-top: 20px">
			<thead>
				<tr>
					<th style="text-align: center;width:70px"></th>
					<th style="text-align: center;width:170px">Service Name</th>
					<th style="text-align: center;width:170px">Currency</th>
					<th style="text-align: center;width:170px">Fee</th>
					<th style="text-align: center;width:220px">Unit Pricing (Per)</th>
					<th style="text-align: center;width:170px">Servicing Firm</th>
				</tr>
				
			</thead>
			<tbody id="body_service_proposal">
			</tbody>
			
		</table>
		<div class="form-group">
			<div class="col-sm-12">
				<input type="button" class="btn btn-primary submitServiceProposalInfo" id="submitServiceProposalInfo" value="Save" style="float: right; margin-bottom: 20px; margin-top: 20px;">
			</div>
		</div>
	</form>
</div>
<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>