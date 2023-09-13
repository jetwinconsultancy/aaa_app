<div id="transaction_confirmation_agm_ar">
	<span style="color: inherit; font-size: 2.4rem; letter-spacing: -1px; font-weight: 500; margin-top: 20px; margin-bottom: 10px; font-family: inherit; line-height: 2.1;">Annual General Meeting (AGM) and Annual Return (AR)</span><!-- <a href="javascript:void(0)" class="btn btn-primary edit_agm_ar" id="edit_agm_ar"style="margin-left: 10px; margin-top: -5px; padding: 6px 10px; font-size: 12px">Edit</a> -->
	<div id="agm_ar_interface">
		<span style="color: inherit; font-size: 2.4rem; letter-spacing: -1px; font-weight: 500; margin-top: 20px; margin-bottom: 10px; font-family: inherit; line-height: 2.1;">Company Information and Status</span><a href="javascript:void(0)" class="btn btn-primary edit_company_info_and_status" id="edit_company_info_and_status"style="margin-left: 10px; margin-top: -5px; padding: 6px 10px; font-size: 12px">Edit</a>
		<div class="form-group" style="margin-top: 20px;">
			<label class="col-sm-3">Company Name: </label>
			<div class="col-sm-8">
				<label id="confirmation_company_name"></label>
			</div>
		</div>
		<div class="form-group" style="margin-top: 20px">
			<label class="col-sm-3" for="w2-username">Company Status: </label>
			<div class="col-xs-8">
				<div class="confirmation_activity_status" id="confirmation_activity_status"></div>
			</div>
		</div>
		<div class="form-group" style="margin-top: 20px">
			<label class="col-sm-3" for="w2-username">Solvency Status: </label>
			<div class="col-xs-8">
				<div class="confirmation_solvency_status" id="confirmation_solvency_status"></div>
			</div>
		</div>
		<div class="form-group" style="margin-top: 20px">
			<label class="col-sm-3" for="w2-username">Small Company: </label>
			<div class="col-xs-8">
				<div class="confirmation_small_company" id="confirmation_small_company"></div>
			</div>
		</div>
		<div class="form-group" style="margin-top: 20px">
			<label class="col-sm-3" for="w2-username">XBRL: </label>
			<div class="col-xs-8">
				<div class="confirmation_xbrl" id="confirmation_xbrl"></div>
			</div>
		</div>
		<span style="color: inherit; font-size: 2.4rem; letter-spacing: -1px; font-weight: 500; margin-top: 20px; margin-bottom: 10px; font-family: inherit; line-height: 2.1;">Notice</span><a href="javascript:void(0)" class="btn btn-primary edit_notice" id="edit_notice"style="margin-left: 10px; margin-top: -5px; padding: 6px 10px; font-size: 12px">Edit</a>
		<div class="form-group" style="margin-top: 20px">
			<label class="col-sm-3" for="w2-username">First AGM/ Annual Return: </label>
			<div class="col-xs-8">
				<div class="confirmation_first_agm" id="confirmation_first_agm"></div>
			</div>
		</div>
		<div class="form-group" style="margin-top: 20px">
			<label class="col-sm-3" for="w2-username">Year End: </label>
			<div class="col-xs-8">
				<div class="confirmation_fye" id="confirmation_fye"></div>
			</div>
		</div>
		<div class="form-group" style="margin-top: 20px">
			<label class="col-sm-3" for="w2-username">Is Company required to hold AGM? </label>
			<div class="col-xs-8">
				<div class="confirmation_require_hold_agm_list_name" id="confirmation_require_hold_agm_list_name"></div>
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
		<div class="form-group conf_date_fs" style="margin-top: 20px">
			<label class="col-sm-3" for="w2-username">Date FS sent to members: </label>
			<div class="col-xs-8">
				<div class="confirmation_date_fs_sent_to_members" id="confirmation_date_fs_sent_to_members"></div>
			</div>
		</div>
		<div class="form-group conf_agm_time" style="margin-top: 20px">
			<label class="col-sm-3" for="w2-username">AGM Time: </label>
			<div class="col-xs-8">
				<div class="confirmation_agm_time" id="confirmation_agm_time"></div>
			</div>
		</div>
		
		<!-- <div class="form-group" style="margin-top: 20px">
			<label class="col-xs-2" for="w2-username">Resolution Time: </label>
			<div class="col-xs-8">
				<div class="confirmation_reso_time" id="confirmation_reso_time"></div>
			</div>
		</div> -->
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
		<span style="color: inherit; font-size: 2.4rem; letter-spacing: -1px; font-weight: 500; margin-top: 20px; margin-bottom: 10px; font-family: inherit; line-height: 2.1;">Agenda</span><a href="javascript:void(0)" class="btn btn-primary edit_agenda" id="edit_agenda"style="margin-left: 10px; margin-top: -5px; padding: 6px 10px; font-size: 12px">Edit</a>
		
		<!-- <div class="form-group" style="margin-top: 20px">
			<label class="col-xs-2" for="w2-username">EPC Status: </label>
			<div class="col-xs-8">
				<div class="confirmation_epc_status" id="confirmation_epc_status"></div>
			</div>
		</div> -->
		<div class="form-group" style="margin-top: 20px">
			<label class="col-sm-3" for="w2-username">Chairman: </label>
			<div class="col-xs-8">
				<div class="confirmation_chairman" id="confirmation_chairman"></div>
			</div>
		</div>
		<div class="form-group" style="margin-top: 20px">
			<label class="col-sm-3" for="w2-username">Financial Statements Audited: </label>
			<div class="col-xs-8">
				<div class="confirmation_financial_statements_audited" id="confirmation_financial_statements_audited"></div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-5" for="w2-username">
				<input type="checkbox" class="confirm_reappointment_auditor" id="confirm_reappointment_auditor" name="confirm_reappointment_auditor" onclick="reappointmentAuditor(this);" disabled="true">  Reappointment of Auditor
			</label>
		</div>

		<div class="form-group confirm_reappointment_auditor_div" style="display: none;">
			<div class="col-sm-8">
				<table class="table table-bordered table-striped table-condensed mb-none" style="width: 250px">
					<thead>
						<tr>
							<th style="width:250px; text-align: center">Auditor Name</th>
							
						</tr>
					</thead>
					<tbody id="confirm_reappointment_auditor_add">
											
					</tbody>
				</table>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-5" for="w2-username">
				<input type="checkbox" class="confirm_director_fee" id="confirm_director_fee" name="confirm_director_fee" onclick="directorFee(this);" disabled="true">  Director Fee
			</label>
			
		</div>
		<div class="form-group confirm_director_fee_div" style="display: none;">
			<div class="col-sm-8">
				<table class="table table-bordered table-striped table-condensed mb-none">
					<thead>
						<tr>
							<th style="width:300px; text-align: center">Director Name</th>
							<th style="width:150px; text-align: center">Currency</th>
							<th style="width:150px; text-align: center">Salary</th>
							<th style="width:150px; text-align: center">CPF</th>
							<th style="width:150px; text-align: center">Fees</th>
							<th style="width:150px; text-align: center">Total</th>
						</tr>
					</thead>
					<tbody id="confirm_director_fee_add">
											
					</tbody>
				</table>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-5" for="w2-username">
				<input type="checkbox" class="confirm_amount_due_from_director" id="confirm_amount_due_from_director" name="confirm_amount_due_from_director" onclick="amountDueFromDirector(this);" disabled="true">  Amount Due From Director
			</label>
		</div>
		<div class="form-group confirm_amount_due_from_director_div" style="display: none;">
			<div class="col-sm-8">
				<table class="table table-bordered table-striped table-condensed mb-none" style="width: 400px">
					<thead>
						<tr>
							<th style="width:250px; text-align: center">Director Name</th>
							<th style="width:150px; text-align: center">Amount</th>
						</tr>
					</thead>
					<tbody id="confirm_amount_due_from_director_add">
											
					</tbody>
				</table>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-5" for="w2-username">
				<input type="checkbox" class="confirm_director_retirement" id="confirm_director_retirement" name="confirm_director_retirement" onclick="directorRetirement(this);" disabled="true">  Director Retirement
			</label>
		</div>
		<div class="form-group confirm_director_retirement_div" style="display: none;">
			<div class="col-sm-8">
				<table class="table table-bordered table-striped table-condensed mb-none" style="width: 700px">
					<thead>
						<tr>
							<th style="width:50px; text-align: center">No</th>
							<th style="width:250px; text-align: center">NRIC</th>
							<th style="width:250px; text-align: center">Director Name</th>
							<th style="width:150px; text-align: center">Retiring</th>
						</tr>
					</thead>
					<tbody id="confirm_director_retirement_add">
											
					</tbody>
				</table>
			</div>
		</div>
		
	</div>
	<div id="bottom_interface" style="margin-top: 10px;">
		<span style="color: inherit; font-size: 2.4rem; letter-spacing: -1px; font-weight: 500; margin-top: 20px; margin-bottom: 10px; font-family: inherit; line-height: 2.1;">Annual Return Declaration</span><a href="javascript:void(0)" class="btn btn-primary edit_ar_declaration" id="edit_ar_declaration"style="margin-left: 10px; margin-top: -5px; padding: 6px 10px; font-size: 12px">Edit</a>
		<div class="form-group" style="margin-top: 20px">
			<label class="col-sm-3" for="w2-username">Share Transfer: </label>
			<div class="col-xs-8">
				<div class="confirmation_share_transfer" id="confirmation_share_transfer"></div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3" for="w2-username">Where the Register of Controllers is kept: </label>
			<div class="col-sm-8">
				<div class="confirmation_register_of_controller" id="confirmation_register_of_controller"></div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3" for="w2-username">Where the Register of Nominee Directors is kept: </label>
			<div class="col-sm-8">
				<div class="confirmation_register_of_nominee_directors" id="confirmation_register_of_nominee_directors"></div>
			</div>
		</div>
		<!-- <div class="form-group">
			<label class="col-sm-5" for="w2-username">
				<input type="checkbox" class="confirm_dividend" id="confirm_dividend" name="confirm_dividend" onclick="dividendCheckBox(this);" disabled="true">  Dividend
			</label>
		</div>
		<div class="form-group confirm_dividend_div" style="display: none;">
			<div class="col-sm-8">
				<table class="table table-bordered table-striped table-condensed mb-none" style="width: 400px">
					<thead>
						<tr>
							<th style="text-align:center;">
								Total Dividend Declared
							</th>
							<th>
								<input type="text" style="text-transform:uppercase;" name="confirm_total_dividend" id="confirm_total_dividend" class="form-control numberdes" value="" readonly/>
							</th>
						</tr>
						<tr>
							<th style="width:250px; text-align: center">Member Name</th>
							<th style="width:150px; text-align: center">Dividend</th>
						</tr>
					</thead>
					<tbody id="confirm_dividend_add">
											
					</tbody>
					
				</table>
			</div>
		</div> -->
		

		
		<!-- <h4>Register of Controller</h4>
		<div class="form-group">
			<label class="col-sm-2" for="w2-username">Exemption: </label>
			<div class="col-sm-8">
				<div class="confirmation_controller_exempt" id="confirmation_controller_exempt"></div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2" for="w2-username">Is kept at: </label>
			<div class="col-sm-8">
				<div class="confirmation_controller_kept" id="confirmation_controller_kept"></div>
			</div>
		</div>
		<h4>Register of Nominee Directors</h4>
		<div class="form-group">
			<label class="col-sm-2" for="w2-username">Exemption: </label>
			<div class="col-sm-8">
				<div class="confirmation_director_exempt" id="confirmation_director_exempt"></div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2" for="w2-username">Is kept at: </label>
			<div class="col-sm-8">
				<div class="confirmation_director_kept" id="confirmation_director_kept"></div>
			</div>
		</div> -->
	</div>
	<!-- <div id = "document">
		<h3>Compilation</h3>
		<table class="table table-bordered table-striped mb-none" id="datatable-pending" style="width:100%;">
			<thead>
				<tr>
					<th style="text-align: center;width:20px !important;padding-right:2px !important;padding-left:2px !important;">No</th>
					<th style="text-align: center;width:310px !important;padding-right:2px !important;padding-left:2px !important;">Document Name</th>
					<th style="text-align: center;width:30px !important;padding-right:2px !important;padding-left:2px !important;">Received On</th>
				</tr>
			</thead>
			<tbody id="pending_doc_body">
				
			</tbody>
		</table>
	</div> -->
	<!-- <div>
		<h3>Logdement Info</h3>
		
		<div class="form-group">
			<label class="col-sm-3" for="w2-DS2">Effective Date:</label>
			<div class="col-sm-8">
				<div class="input-group mb-md" style="width: 200px;">
					<span class="input-group-addon">
						<i class="far fa-calendar-alt"></i>
					</span>
					<input type="text" class="form-control valid lodgement_date" id="date_todolist" name="lodgement_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" required="" value="" placeholder="DD/MM/YYYY">
				</div>
			</div>
		</div>
	</div> -->
</div>
<script src="themes/default/assets/js/confirmation_agm_ar.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>