<div id="w2-officer" class="panel appoint_auditor" id="appoint_new_director">
	<h3>Appointment and Resignation of Director</h3>
	<form id="resign_of_director_form" style="margin-top: 20px;">
	<input type="hidden" class="form-control company_code" id="company_code" name="company_code" value=""/>

	<button type="button" class="collapsible"><span style="font-size: 2.4rem;">Company Info</span></button>
	<div id="company_info" class="incorp_content">
		<div class="form-group" style="margin-top: 20px;">
			<label class="col-sm-2" for="w2-username">Registration No: </label>
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
	</div>

	<!-- <button type="button" class="collapsible"><span style="font-size: 2.4rem;">Notice Info</span></button>
	<div id="company_info" class="incorp_content">
		<div style="margin-top: 20px;padding-bottom: 18px;">
			<div class="form-group">
				<label class="col-sm-2" for="w2-username">Notice Date: </label>
				<div class="col-sm-8">
					<div class="input-group mb-md" style="width: 200px;">
						<span class="input-group-addon">
							<i class="far fa-calendar-alt"></i>
						</span>
						<input type="text" class="form-control valid notice_date" id="date_todolist" name="notice_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" required="" value="" placeholder="DD/MM/YYYY">
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2" for="w2-username">Director(s) Meeting Date: </label>
				<div class="col-sm-4">
					<div class="input-group mb-md" style="width: 200px;">
						<span class="input-group-addon">
							<i class="far fa-calendar-alt"></i>
						</span>
						<input type="text" class="form-control valid director_meeting_date" id="date_todolist" name="director_meeting_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" required="" value="" placeholder="DD/MM/YYYY">
					</div>
				</div>
				<label class="col-sm-1" for="w2-username">Time: </label>
				<div class="col-sm-4 form-group">
					<div class="input-group mb-md date" style="width: 200px;">
						<span class="input-group-addon">
							<i class="far fa-clock"></i>
						</span>
						<input type="text" class="form-control valid director_meeting_time" id="datetimepicker3" name="director_meeting_time" required="" value="" placeholder="">
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2" for="w2-username">Member(s) Meeting Date: </label>
				<div class="col-sm-4">
					<div class="input-group mb-md" style="width: 200px;">
						<span class="input-group-addon">
							<i class="far fa-calendar-alt"></i>
						</span>
						<input type="text" class="form-control valid member_meeting_date" id="date_todolist" name="member_meeting_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" required="" value="" placeholder="DD/MM/YYYY">
					</div>
				</div>
				<label class="col-sm-1" for="w2-username">Time: </label>
				<div class="col-sm-4 form-group">
					<div class="input-group mb-md date" style="width: 200px;">
						<span class="input-group-addon">
							<i class="far fa-clock"></i>
						</span>
						<input type="text" class="form-control valid member_meeting_time" id="datetimepicker4" name="member_meeting_time" required="" value="" placeholder="">
					</div>
				</div>
			</div>
			<table class="table table-bordered table-striped table-condensed mb-none">
				<tr>
					<th style="width: 200px">Address</th>
					<td><label><input type="radio" id="register_address_edit" name="address_type" value="Registered Office Address"/>&nbsp;&nbsp;Registered Office Address</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label><input type="radio" id="local_edit" name="address_type" value="Local"/>&nbsp;&nbsp;Local Address</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label><input type="radio" id="foreign_edit" name="address_type" value="Foreign"/>&nbsp;&nbsp;Foreign Address</label></td>
				</tr>
				<tr id="tr_registered_edit">
					<th></th>
					<td>
						<div style="width: 100%;">
							<div style="width: 25%;float:left;margin-right: 20px;">
								<label>Postal Code :</label>
							</div>
							<div style="width: 65%;float:left;margin-bottom:5px;">
								<div class="input-group" style="width: 20%;" >
									<input type="text" class="form-control input-xs" id="registered_postal_code1" name="registered_postal_code1" style="text-transform:uppercase" value="">
								</div>
							</div>
						</div>

						<div style="margin-bottom:5px;">
							<label style="width: 25%;float:left;margin-right: 20px;">Street Name :</label>
							<div style="width: 65%;float:left;margin-bottom:5px;">
								<div class="input-group" style="width: 84.5%;">
									<input style="width: 100%; text-transform:uppercase" type="text" class="form-control input-xs" id="registered_street_name1" name="registered_street_name1" value="">
								</div>
							</div>
									
						
						</div>
						<div style="margin-bottom:5px;">
							<label style="width: 25%;float:left;margin-right: 20px;">Building Name :</label>
							<div class="input-group" style="width: 55%;" >
								<input style="width: 100%; text-transform:uppercase" type="text" class="form-control input-xs" id="registered_building_name1" name="registered_building_name1" value="">
							</div>
						
						</div>
						<div style="margin-bottom:5px;">
							<div style="width: 25%;">
							<label style="width: 100%;float:left;margin-right: 20px;">Unit No :</label>
						</div>
							<div style="width: 75%;" > 
							<div class="" style="width: 10%;display: inline-block">
										<input style="width: 100%; margin-right: 10px; text-transform:uppercase" type="text" class="form-control input-xs" id="registered_unit_no1" name="registered_unit_no1">
									</div>
									<div class="" style="width: 15%;display: inline-block;" >
										<input style="width: 100%; text-transform:uppercase" type="text" class="form-control input-xs" id="registered_unit_no2" name="registered_unit_no2" value="" maxlength="10">
									</div>
								</div>
								
						
						</div>
						
					</td>
				</tr>
				<tr id="tr_local_edit">
					<th></th>
					<td>
						<div style="width: 100%;">
							<div style="width: 25%;float:left;margin-right: 20px;">
								<label>Postal Code :</label>
							</div>
							<div style="width: 65%;float:left;margin-bottom:5px;">
								<div class="input-group" style="width: 20%;" >
									<input type="text" class="form-control input-xs" id="postal_code1" name="postal_code1" style="text-transform:uppercase" value="">
									
								</div>

								<div id="form_postal_code1"></div>
							</div>
						</div>

						<div style="margin-bottom:5px;">
							<label style="width: 25%;float:left;margin-right: 20px;">Street Name :</label>
							<div style="width: 65%;float:left;margin-bottom:5px;">
								<div class="input-group" style="width: 84.5%;">
									<input style="width: 100%; text-transform:uppercase" type="text" class="form-control input-xs" id="street_name1" name="street_name1" value="">
								</div>
							</div>
									
						
						</div>
						<div style="margin-bottom:5px;">
							<label style="width: 25%;float:left;margin-right: 20px;">Building Name :</label>
							<div class="input-group" style="width: 55%;" >
								<input style="width: 100%; text-transform:uppercase" type="text" class="form-control input-xs" id="building_name1" name="building_name1" value="">
							</div>
						
						</div>
						<div style="margin-bottom:5px;">
							<div style="width: 25%;">
							<label style="width: 100%;float:left;margin-right: 20px;">Unit No :</label>
						</div>
							<div style="width: 75%;" > 
								<div class="" style="width: 10%;display: inline-block">
									<input style="width: 100%; margin-right: 10px; text-transform:uppercase" type="text" class="form-control input-xs" id="unit_no1" name="unit_no1" value="">
									
								</div>
								<div class="" style="width: 15%;display: inline-block;" >
									<input style="width: 100%; text-transform:uppercase" type="text" class="form-control input-xs" id="unit_no2" name="unit_no2" value="" maxlength="10">

								</div>
							</div>
						</div>
					</td>
				</tr>
				<tr id="tr_foreign_edit">
					<td></td>
					<td colspan="2">
						<div style="width: 100%;">
							<div style="width: 25%;float:left;margin-right: 20px;">
								<label>Foreign Address :</label>
							</div>
							<div style="width: 65%;float:left;margin-bottom:5px;">
								<div class="input-group" style="width: 65%;" >
									<input style="margin-bottom: 5px;" type="text" class="form-control input-xs" id="foreign_address1" name="foreign_address1" value=""> 
								
								</div>
							</div>
						</div>
						<div style="width: 100%;">
							<div style="width: 25%;float:left;margin-right: 20px;">
								<label></label>
							</div>
							<div style="width: 65%;float:left;margin-bottom:5px;">
								<div class="input-group" style="width: 65%;" >
									<input style="margin-bottom: 5px;" type="text" class="form-control input-xs" id="foreign_address2" name="foreign_address2" value="">
								</div>

								<div id="form_foreign_address2"></div>
							</div>
						</div>
						<div style="width: 100%;">
							<div style="width: 25%;float:left;margin-right: 20px;">
								<label></label>
							</div>
							<div style="width: 65%;float:left;margin-bottom:5px;">
								<div class="input-group" style="width: 65%;" >
									<input type="text" class="form-control input-xs" id="foreign_address3" name="foreign_address3" value="">
									
									
								</div>

								<div id="form_foreign_address3"></div>
							</div>
						</div>
						
					</td>
				</tr>
			</table>
		</div>
	</div> -->

	<label style="font-weight: bold;"><input style="vertical-align: middle; position: relative; bottom: 1px;" type="checkbox" class="add_nominee_director" id="add_nominee_director" onclick="addNomineeDirector(this);"> Add Nominee Director</label>

	<button type="button" class="collapsible"><span style="font-size: 2.4rem;">Appointment of Director</span></button>
	<div id="company_info" class="incorp_content">
		<div style="margin-top: 20px;padding-bottom: 18px;">
			<table class="table table-bordered table-striped table-condensed mb-none" id="officer_table" style="width: 1000px" style="margin-top: 20px;">
				<thead>
					<tr>
						<th id="id_position" style="text-align: center;width:170px">Position</th>
						<th id="id_header" style="text-align: center;width:270px">ID</th>
						<th style="text-align: center;width:270px" id="id_name">Name</th>
						<th style="text-align: center;width:170px">Date Of Appointment</th>
						<th><a href="javascript: void(0);" owspan=2 style="color: #D9A200;; outline: none !important;text-decoration: none;"><span id="appoint_new_director_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Officer" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add Officer</span></a></th>
					</tr>
				</thead>
				<tbody id="body_appoint_new_director">
				</tbody>
			</table>
		</div>
	</div>

	<button type="button" class="collapsible"><span style="font-size: 2.4rem;">Resign of Director</span></button>
	<div id="company_info" class="incorp_content">
		<div style="margin-top: 20px;padding-bottom: 18px;">
			<div id="resign_director_table">
				<table class="table table-bordered table-striped table-condensed mb-none resign_director" id="officer_table" style="width: 1010px">
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

					<tbody id="body_resign_director">
					</tbody>
				</table>
			</div>

			<div id="no_director_resign">
				<div>
					<span class="help-block">
						* No director can resign.
					</span>
				</div>
			</div>
		</div>
	</div>

	<div class="nominee_director" style="display: none;">
		<button type="button" class="collapsible"><span style="font-size: 2.4rem;">Register of Nominee Director</span></button>
		<div id="company_info" class="incorp_content">
			<div style="padding-top: 18px; height: 65px;">
				<a href="javascript: void(0);" class="btn btn-primary" id="create_nominee_director" class="create_nominee_director" style="float: right;">Create</a>
			</div>
			<div style="padding-bottom: 18px;">
				<table style="width: 100%;" class="table table-bordered table-striped mb-none latest_nominee_director_table display nowrap" id="latest_nominee_director_table">
	                <thead>
	                	<tr>
			                <!-- <th style="text-align: center; width: 150px !important;">Date of entry/update</th> -->
			                <th style="text-align: center; width: 200px !important;">Name of Nominee Director</th>
			                <th style="text-align: center">Particulars of nominator</th>
			                <th style="text-align: center; width: 200px !important;">Supporting Docs</th>
			                <th style="width: 100px !important;"></th>
		                </tr>
		            </thead>
		            <tbody id="table_body_latest_nominee_director">
					</tbody>
	            </table>
	        </div>
		</div>
	</div>
	</form>
	<button type="button" class="collapsible"><span style="font-size: 2.4rem;">Service Engagement</span></button>
	<div id="company_info" class="incorp_content">
		<form id="billing_form" style="margin-top: 20px;">
		<!-- <div id="billing_form" style="margin-top: 20px;"> -->
			<div class="hidden"><input type="text" class="form-control" name="company_code" id="company_code" value="<?= isset($transaction_company_code) ? $transaction_company_code : ''?>"/></div>
			<table class="table table-bordered table-striped table-condensed" id="billing_table"style="margin-top: 20px;width: 1000px">
				<thead>
					<tr> 
						<th valign=middle style="width:210px;text-align: center">Service</th>
						<th valign=middle style="width:200px;text-align: center">Invoice Description</th>
						<th style="width:200px;text-align: center">Fee</th>
						<!-- <th style="width:180px;text-align: center">Amount</th> -->
						<th style="width:180px;text-align: center">Unit Pricing</th>
						<th style="width:180px;text-align: center">Servicing Firm</th>
						<th style="width:80px;"><a href="javascript: void(0);" style="color: #D9A200;width:230px; outline: none !important;text-decoration: none;"><span id="billing_info_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Service Engagement Information" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add</span></a></th>
					</tr>
				</thead>
				<tbody id="body_billing_info">
				</tbody>
			</table>

			<!-- <div class="form-group">
				<div class="col-sm-12">
					<input type="button" class="btn btn-primary submitBillingInfo" id="submitBillingInfo" value="Save" style="float: right; margin-bottom: 20px; margin-top: 20px;">
				</div>
			</div> -->
		</form>
		<!-- </div> -->
	</div>
	<!-- <div class="form-group">
		<label class="col-sm-2" for="w2-username">Registration No: </label>
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
	</div> -->
		
	<!-- <h3>Resign of Director</h3>
	<form id="resign_of_director_form" style="margin-top: 20px;">
		<input type="hidden" class="form-control company_code" id="company_code" name="company_code" value=""/>
		<table class="table table-bordered table-striped table-condensed mb-none" id="officer_table" style="width: 1010px">
			<thead>
				<tr>
					<th id="id_position" style="text-align: center;width:170px">Position</th>
					<th id="id_header" style="text-align: center;width:170px">ID</th>
					<th style="text-align: center;width:170px" id="id_name">Name</th>
					<th style="text-align: center;width:170px">Date Of Appointment</div>
					<th style="text-align: center;width:170px">Date Of Cessation</div>
					<th style="text-align: center;width:220px">Reason</div>
					<th></th>
				</tr>
				
			</thead>
			<tbody id="body_resign_director">
			</tbody>
			
		</table>
		<div id="appoint_new_director" style="display:none">
			<h3>Appointment of Director</h3>
			<div>
				<span class="help-block">
					* Must have one director in this company.
				</span>
			</div>
			<table class="table table-bordered table-striped table-condensed mb-none" id="officer_table" style="width: 1000px">
				<thead>
					<tr>
						<th id="id_position" style="text-align: center;width:170px">Position</th>
						<th id="id_header" style="text-align: center;width:270px">ID</th>
						<th style="text-align: center;width:270px" id="id_name">Name</th>
						<th><a href="javascript: void(0);" owspan=2 style="color: #D9A200;; outline: none !important;text-decoration: none;"><span id="appoint_new_director_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Officer" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add Officer</span></a></th>
					</tr>
					
				</thead>
				<tbody id="body_appoint_new_director">
				</tbody>
				
			</table>
		</div> -->
		<div class="form-group">
			<div class="col-sm-12">
				<input type="button" class="btn btn-primary submitResignDirectorInfo" id="submitResignDirectorInfo" value="Save" style="float: right; margin-bottom: 20px; margin-top: 20px;">
			</div>
		</div>
	
</div>
<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>
<div class="loading" id='loadingControllerMessage' style='display:none; z-index: 9999 !important;'>Loading&#8230;</div>
<!-- update_register_of_nominee_director -->
<script src="themes/default/assets/js/update_register_of_nominee_director.js?v=6434fdfsdfdrw32323233w1323" charset="utf-8"></script>
<div id="modal_nominee_director" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;">
	<div class="modal-dialog">
		<div class="modal-content">
			<header class="panel-heading">
				<h2 class="panel-title">Update Register of Nominee Director</h2>
			</header>
			<form id="form_register_of_nominee_director" autocomplete="off" enctype="multipart/form-data">
				<div class="panel-body register_of_nominee_director">
					<div class="col-md-12">
						<span style="font-size: 1.8rem;"><u>Nominee Director</u></span>
					</div>
					<input type="hidden" id="client_nominee_director_id" value="" name="client_nominee_director_id"/>
					<input type="hidden" id="nomi_company_code" value="" name="nomi_company_code"/>
					<div class="col-md-6">
						<div class="div_nd_id">
                        	<label class="font_14" for="nd_gid_add_controller_officer">Identification No.<span class="color_red">*</span></label>
                            <input style="text-transform:uppercase" type="text" id="nd_gid_add_controller_officer" class="form-control" placeholder="Identification No." value="" name="nd_identification_no" required="required"/>
                            <a class="nd_add_office_person_link" href="" style="cursor:pointer;" id="nd_add_office_person_link" target="_blank" hidden onclick="nd_add_controller_person(this)"><div style="cursor:pointer;">Click here to <span style="font-weight:bold">Add Person</span></div></a>
                            <a class="nd_a_refresh_controller" href="javascript:void(0)" style="cursor:pointer;" id="nd_refresh_controller" onclick="nd_refresh_controller()"><div style="cursor:pointer;"><span class="nd_refresh_controller" style="font-weight:bold" hidden>Refresh</span></div></a>
                            <a class="nd_view_edit_person" href="" style="cursor:pointer;" id="nd_view_edit_person" target="_blank" hidden><div style="cursor:pointer;"><span style="font-weight:bold">View/Edit</span></div></a>
                        </div>
					</div>

					<div class="col-md-6">
						<div class="div_nd_name">
                        	<label class="font_14" for="nd_name">Name<span class="color_red">*</span></label>
                            <input style="text-transform:uppercase" type="text" id="nd_name" class="form-control" placeholder="Name" value="" name="nd_name" required readonly="true" />
                            <!-- <input type="hidden" id="nd_controller_id" value="" name="nd_client_controller_id"/> -->
                            <!-- <input type="hidden" id="nd_company_code" value="" name="nd_company_code"/> -->
                            <input type="hidden" id="nd_officer_id" value="" name="nd_officer_id"/>
                            <input type="hidden" id="nd_officer_field_type" value="" name="nd_officer_field_type"/>
                            <input type="hidden" id="nd_date_entry" name="nd_date_entry" value="">
                        </div>
					</div>
					<!-- <div class="col-md-12" style="padding-left: 0px !important; padding-right: 0px !important;">
						<div class="col-md-6">
							<div class="div_nd_date_entry">
	                        	<label class="font_14" for="nd_date_entry">Date of entry/update<span class="color_red">*</span></label>
	                            <div class="input-group" style="width: 100%;">
									<span class="input-group-addon">
										<i class="far fa-calendar-alt"></i>
									</span>
									<input type="text" class="form-control input-xs" id="nd_date_entry" name="nd_date_entry" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="DD/MM/YYYY">
								</div>
	                        </div>
						</div>
					</div> -->
					<div class="col-md-12" style="margin-top: 15px;">
						<span style="font-size: 1.8rem;"><u>Particulars of Nominator</u></span>
					</div>

                    <div class="col-md-6">
						<div class="div_nomi_id">
                        	<label class="font_14" for="nomi_gid_add_controller_officer">Identification No.<span class="color_red">*</span></label>
                            <input style="text-transform:uppercase" type="text" id="nomi_gid_add_controller_officer" class="form-control" placeholder="Identification No." value="" name="nomi_identification_no" required="required"/>
                            <a class="nomi_add_office_person_link" href="" style="cursor:pointer;" id="nomi_add_office_person_link" target="_blank" hidden onclick="nomi_add_controller_person(this)"><div style="cursor:pointer;">Click here to <span style="font-weight:bold">Add Person</span></div></a>
                            <a class="nomi_a_refresh_controller" href="javascript:void(0)" style="cursor:pointer;" id="nomi_refresh_controller" onclick="nomi_refresh_controller()"><div style="cursor:pointer;"><span class="nomi_refresh_controller" style="font-weight:bold" hidden>Refresh</span></div></a>
                            <a class="nomi_view_edit_person" href="" style="cursor:pointer;" id="nomi_view_edit_person" target="_blank" hidden><div style="cursor:pointer;"><span style="font-weight:bold">View/Edit</span></div></a>
                        </div>
					</div>
					
					<div class="col-md-6">
						<div class="div_nomi_name">
                        	<label class="font_14" for="nomi_name">Name<span class="color_red">*</span></label>
                            <input style="text-transform:uppercase" type="text" id="nomi_name" class="form-control" placeholder="Name" value="" name="nomi_name" required="required" readonly="true" />
                            <!-- <input type="hidden" id="nomi_controller_id" value="" name="nomi_client_controller_id"/> -->
                            <!-- <input type="hidden" id="nomi_company_code" value="" name="nomi_company_code"/> -->
                            <input type="hidden" id="nomi_officer_id" value="" name="nomi_officer_id"/>
                            <input type="hidden" id="nomi_officer_field_type" value="" name="nomi_officer_field_type"/>
                        </div>
					</div>
					<div class="col-md-12" style="padding-left: 0px !important; padding-right: 0px !important;">
						<div class="col-md-6">
							<div class="div_date_become_nominator">
	                        	<label class="font_14" for="date_become_nominator">Date which becomes a nominator<span class="color_red">*</span></label>
	                            <div class="input-group" style="width: 100%;">
									<span class="input-group-addon">
										<i class="far fa-calendar-alt"></i>
									</span>
									<input type="text" class="form-control input-xs" id="date_become_nominator" name="date_become_nominator" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="DD/MM/YYYY">
								</div>
	                        </div>
						</div>
						<div class="col-md-6">
							<div>
	                        	<label class="font_14" for="date_ceased_nominator">Date ceased as nominator</label>
	                            <div class="input-group" style="width: 100%;">
									<span class="input-group-addon">
										<i class="far fa-calendar-alt"></i>
									</span>
									<input type="text" class="form-control input-xs" id="date_ceased_nominator" name="date_ceased_nominator" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="DD/MM/YYYY">
								</div>
	                        </div>
						</div>
					</div>
					<div class="col-md-12">
                    	<label class="font_14">Supporting Document (if any)</label>
                    	<div class="input-group">
                    		<input type="file" style="display:none" id="nd_supporting_document" class="nd_supporting_document" name="nd_supporting_document">
                    		<label for="nd_supporting_document" class="btn btn-primary">Attachment</label><br/>
                    		<span class="nd_file_name"></span>
                    		<input type="hidden" class="nd_hidden_supporting_document" name="nd_hidden_supporting_document" value=""/>
                    	</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" name="saveRegNomineeDirector" id="saveRegNomineeDirector">Save</button>
					<input type="button" class="btn btn-default " data-dismiss="modal" name="cancelRegController" value="Cancel">
				</div>
			</form>
		</div>
	</div>
</div>

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