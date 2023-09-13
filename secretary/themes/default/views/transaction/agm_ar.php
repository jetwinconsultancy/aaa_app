<div id="w2-agm_ar_form" class="panel">
	<h3>Annual General Meeting (AGM) and Annual Return (AR)</h3>
	<input type="hidden" class="form-control company_code" id="company_code" name="company_code" value=""/>
	<input type="hidden" class="form-control hidden_company_name" id="hidden_company_name" name="company_name" value=""/>
	<input type="hidden" class="form-control transaction_agm_ar_id" id="transaction_agm_ar_id" name="transaction_agm_ar_id" value=""/>
	<!-- <form id="agm_ar_form" style="margin-top: 20px;"> -->
	<form id="company_info_and_status_form" style="margin-top: 20px;">
		<button type="button" class="collapsible"><span style="font-size: 2.4rem;">Company Information and Status</span></button>
		<div id="company_info" class="incorp_content">
			<div class="form-group" style="margin-top: 20px;">
				<label class="col-sm-3">Company Name: </label>
				<div class="col-sm-8">
					<label id="company_name"></label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3">Company Type: </label>
				<div class="col-sm-4">
					<select class="form-control" style="text-align:right;" name="company_type" id="company_type">
						<option value="0">Select Company Type</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3">Company Status: </label>
				<div class="col-sm-8">
					<select class="form-control" style="text-align:right;width: 200px;" name="activity_status" id="activity_status">
						<option value="0">Select Company Status</option><!-- Activity Status -->
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3">Solvency Status: </label>
				<div class="col-sm-8">
					<select class="form-control" style="text-align:right;width: 200px;" name="solvency_status" id="solvency_status">
						<option value="0">Select Solvency Status</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3">Small Company Audit Exemption: </label>
				<div class="col-sm-8">
					<select class="form-control small_company" style="text-align:right;width: 200px;" name="small_company" id="small_company">
						<option value="0">Select Small Company Audit Exemption</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3">XBRL: </label>
				<div class="col-sm-8">
					<select class="form-control xbrl_list" style="text-align:right;width: 200px;" name="xbrl" id="xbrl_list">
						<option value="0">Select XBRL</option>
					</select>
				</div>
			</div>
			<input type="button" class="btn btn-primary submitCompanyInfoAndStatus" id="submitCompanyInfoAndStatus" value="Save" style="float: right; margin-bottom: 20px; margin-top: 20px;">
		</div>
	</form>
	<form id="notice_form" style="margin-top: 20px;">
  		<button type="button" class="collapsible"><span style="font-size: 2.4rem;">Notice</span></button>
		<div id="notice_info" class="incorp_content">
			<div class="form-group" style="margin-top: 20px;">
				<label class="col-sm-3">First AGM/ Annual Return: </label>
				<div class="col-sm-8">
					<select class="form-control" style="text-align:right;width: 200px;" name="first_agm" id="first_agm">
						<option value="0">Select First AGM</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3">Year End: </label>
				<div class="col-sm-8">
					<div class="input-group" style="width: 200px;">
						<span class="input-group-addon">
							<i class="far fa-calendar-alt"></i>
						</span>
						<input type="text" class="form-control valid fye_date" id="date_todolist" name="fye_date" data-date-format="dd MM yyyy" data-plugin-datepicker="" required="" value="" placeholder="DD/MM/YYYY">
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3">Is Company required to hold AGM? </label>
				<div class="col-sm-4">
					<select class="form-control" style="text-align:right;" name="require_hold_agm_list" id="require_hold_agm_list">
						<option value="0">Select Is Company required to hold AGM?</option>
					</select>
				</div>
			</div>
			<div class="form-group shorter_notice_section">
				<label class="col-sm-3">Consent For Shorter Notice: </label>
				<div class="col-sm-4">
					<select class="form-control" style="text-align:right;width: 200px;" name="shorter_notice" id="shorter_notice">
						<option value="0">Select Consent For Shorter Notice</option>
					</select>
				</div>
			</div>
			<div class="form-group notice_date_section">
				<!-- reso_date -->
				<label class="col-sm-3">Notice Date: </label>
				<div class="col-sm-4">
					<div class="input-group notice_date_input" style="width: 200px;"> 
						<span class="input-group-addon">
							<i class="far fa-calendar-alt"></i>
						</span>
						<input type="text" class="form-control valid notice_date" id="date_todolist" name="notice_date" data-date-format="dd MM yyyy" data-plugin-datepicker="" required="" value="" placeholder="DD/MM/YYYY">
					</div>
				</div>
			</div>
			<div class="form-group agm_date_section" style="display: none">
				<label class="col-sm-3">AGM Date: </label>
				<div class="col-sm-4">
					<div class="input-group agm_date_input" style="width: 200px;">
						<span class="input-group-addon">
							<i class="far fa-calendar-alt"></i>
						</span>
						<input type="text" class="form-control valid agm_date_info" id="date_todolist agm_date" name="agm_date" data-date-format="dd MM yyyy" data-plugin-datepicker="" required="" value="" placeholder="DD/MM/YYYY">
					</div>
				</div>
			</div>
			<div class="form-group date_fs_section" style="display: none">
				<label class="col-sm-3">Date FS sent to members: </label>
				<div class="col-sm-4">
					<div class="input-group agm_date_input" style="width: 200px;">
						<span class="input-group-addon">
							<i class="far fa-calendar-alt"></i>
						</span>
						<input type="text" class="form-control valid date_fs_sent_to_member" id="date_todolist date_fs_sent_to_member" name="date_fs_sent_to_member" data-date-format="dd MM yyyy" data-plugin-datepicker="" required="" value="" placeholder="DD/MM/YYYY">
					</div>
				</div>
			</div>
			<!-- <div class="form-group">
				<label class="col-sm-2">AGM Date: </label>
				<div class="col-sm-4">
					<div style="width: 100%;">
						<div style="width: 200px;float:left;margin-right: 20px;">
							<div class="input-group agm_date_input" style="width: 200px;">
								<span class="input-group-addon">
									<i class="far fa-calendar-alt"></i>
								</span>
								<input type="text" class="form-control valid agm_date_info" id="date_todolist agm_date" name="agm_date" data-date-format="dd MM yyyy" data-plugin-datepicker="" required="" value="" placeholder="DD/MM/YYYY">
							</div>
						</div>
						<div class="input-bar-item input-bar-item-btn">
					      <button type="button" class="btn btn-primary dispense_agm_button" onclick="dispense_agm(this)" >Dispense AGM</button>
					    </div>
					</div>
				</div>
			</div> -->
			<div class="form-group agm_time_section">
				<label class="col-sm-3">AGM Time: </label>
				<div class="col-sm-4">
					<div class="input-group date" style="width: 200px;">
						<span class="input-group-addon">
							<i class="far fa-clock"></i>
						</span>
						<input type="text" class="form-control valid agm_time" id="agm_time" name="agm_time" required="" value="" placeholder="">
					</div>
				</div>
				<!-- <label class="col-sm-2">Resolution Time: </label>
				<div class="col-sm-4">
					<div style="width: 100%;">
						<div style="width: 75%;float:left;margin-right: 20px;">
							<div class="input-group mb-md date" style="width: 200px;">
								<span class="input-group-addon">
									<i class="far fa-clock"></i>
								</span>
								<input type="text" class="form-control valid resolution_time" id="resolution_time" name="resolution_time" required="" value="" placeholder="">
							</div>
									
						</div>
					</div>
				</div> -->
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
			<input type="button" class="btn btn-primary submitNoticeInfo" id="submitNoticeInfo" value="Save" style="float: right; margin-bottom: 20px; margin-top: 20px;">
		</div>
	</form>
	<form id="agenda_form" style="margin-top: 20px;">
  		<button type="button" class="collapsible"><span style="font-size: 2.4rem;">Agenda</span></button>
		<div id="agenda_info" class="incorp_content">
			<!-- <div class="form-group" style="margin-top: 20px;">
				<label class="col-sm-2">EPC Status: </label>
				<div class="col-sm-8">
					<div style="width: 100%;">
						<div style="width: 75%;float:left;margin-right: 20px;">
							<select class="form-control epc_status" style="text-align:right;width: 200px;" name="epc_status" id="epc_status">
								<option value="0">Select EPC Status</option>
							</select>
						</div>
					</div>
				</div>
			</div> -->
			<div class="form-group" style="margin-top: 20px;">
				<label class="col-sm-3">Chairman: </label>
				<div class="col-sm-4">
					<select class="form-control" style="text-align:right;" name="chairman" id="transaction_agm_chairman">
						<option value="0">Select Chairman</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3">Financial Statements Audited: </label>
				<div class="col-sm-4">
					<select class="form-control" style="text-align:right;" name="audited_fs" id="audited_fs">
						<option value="0">Select Financial Statements Audited</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5">
					<input type="checkbox" class="reappointment_auditor" id="reappointment_auditor" name="reappointment_auditor" onclick="reappointmentAuditor(this);">  Reappointment of Auditor
				</label>
			</div>
			<div class="form-group reappointment_auditor_div" style="display: none;">
				<div class="col-sm-8">
					<table class="table table-bordered table-striped table-condensed mb-none" style="width: 250px">
						<thead>
							<tr>
								<th style="width:250px; text-align: center">Auditor Name</th>
							</tr>
						</thead>
						<tbody id="reappointment_auditor_add">
												
						</tbody>
					</table>
				</div>
			</div>
			<div id="bottom_interface">
				<div class="form-group">
					<label class="col-sm-5">
						<input type="checkbox" class="director_fee" id="director_fee" name="director_fee" onclick="directorFee(this);">  Director Remuneration
					</label>
					
				</div>
				<div class="form-group director_fee_div" style="display: none;">
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
							<tbody id="director_fee_add">
													
							</tbody>
						</table>
					</div>
				</div>
				<!-- <div class="form-group">
					<label class="col-sm-5">
						<input type="checkbox" class="dividend" id="dividend" name="dividend" onclick="dividendCheckBox(this);">  Dividend
					</label>
				</div>
				<div class="form-group dividend_div" style="display: none;">
					<div class="col-sm-8">
						<table class="table table-bordered table-striped table-condensed mb-none" style="width: 400px">
							<thead>
								<tr>
									<th style="text-align:center;">
										Total Dividend Declared
									</th>
									<th>
										<input type="text" style="text-transform:uppercase;" name="total_dividend" id="total_dividend" class="form-control numberdes" value=""/>
									</th>
								</tr>
								<tr>
									<th style="width:250px; text-align: center">Member Name</th>
									<th style="width:150px; text-align: center">Dividend</th>
								</tr>
							</thead>
							<tbody id="dividend_add">
													
							</tbody>
							
						</table>
					</div>
				</div> -->
				<div class="form-group">
					<label class="col-sm-5">
						<input type="checkbox" class="amount_due_from_director" id="amount_due_from_director" name="amount_due_from_director" onclick="amountDueFromDirector(this);">  Amount Due From Director
					</label>
				</div>
				<div class="form-group amount_due_from_director_div" style="display: none;">
					<div class="col-sm-8">
						<table class="table table-bordered table-striped table-condensed mb-none" style="width: 400px">
							<thead>
								<tr>
									<th style="width:250px; text-align: center">Director Name</th>
									<th style="width:150px; text-align: center">Amount</th>
								</tr>
							</thead>
							<tbody id="amount_due_from_director_add">
													
							</tbody>
						</table>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-5">
						<input type="checkbox" class="director_retirement" id="director_retirement" name="director_retirement" onclick="directorRetirement(this);">  Director Retirement
					</label>
				</div>
				<div class="form-group director_retirement_div" style="display: none;">
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
							<tbody id="director_retirement_add">
													
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<input type="button" class="btn btn-primary submitAgendaInfo" id="submitAgendaInfo" value="Save" style="float: right; margin-bottom: 20px; margin-top: 20px;">
		</div>
	</form>
	<form id="ar_declaration_form" style="margin-top: 20px;">
  		<button type="button" class="collapsible"><span style="font-size: 2.4rem;">Annual Return Declaration</span></button>
		<div id="ar_declaration_info" class="incorp_content">
			<div class="form-group" style="margin-top: 20px;">
				<label class="col-sm-3">Share Transfer: </label>
				<div class="col-sm-8">
					<select class="form-control" style="text-align:right;width: 200px;" name="share_transfer" id="agm_share_transfer">
						<option value="0">Select Share Transfer</option>
					</select>
				</div>
			</div>
			<h4><u>Where the Register of Controllers is kept:</u></h4>
			<div class="radio">
				<label><input type="radio" name="register_of_controller" value="Registered office of the company">Registered office of the company</label>
			</div>
			<div class="radio">
				<label><input type="radio" name="register_of_controller" value="Registered office of a registered filing agent appointed by the company">Registered office of a registered filing agent appointed by the company</label>
			</div>
			<div class="radio">
				<label><input type="radio" name="register_of_controller" value="Exempted from the requirement to keep a register">Exempted from the requirement to keep a register</label>
			</div>
			<!-- <div class="form-group">
				<label class="col-sm-3">Exemption: </label>
				<div class="col-sm-8">
					<div style="width: 100%;">
						<div style="width: 75%;float:left;margin-right: 20px;">
							<select class="form-control controller_exempt" style="text-align:right;width: 200px;" name="controller_exempt" id="controller_exempt">
								<option value="0">Select Exemption</option>
							</select>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3">Is kept at: </label>
				<div class="col-sm-8">
					<div style="width: 100%;">
						<div style="width: 75%;float:left;margin-right: 20px;">
							<select class="form-control controller_kept" style="text-align:right;width: 600px;" name="controller_kept" id="controller_kept">
								<option value="0">Select Where Is Kept</option>
							</select>
						</div>
					</div>
				</div>
			</div> -->
			<br/>
			<h4><u>Where the Register of Nominee Directors is kept:</u></h4>
			<div class="radio">
				<label><input type="radio" name="register_of_nominee_director" value="Registered office of the company">Registered office of the company</label>
			</div>
			<div class="radio">
				<label><input type="radio" name="register_of_nominee_director" value="Registered office of a registered filing agent appointed by the company">Registered office of a registered filing agent appointed by the company</label>
			</div>
			<div class="radio">
				<label><input type="radio" name="register_of_nominee_director" value="Exempted from the requirement to keep a register">Exempted from the requirement to keep a register</label>
			</div>

			<!-- <div class="form-group">
				<label class="col-sm-3">Exemption: </label>
				<div class="col-sm-8">
					<div style="width: 100%;">
						<div style="width: 75%;float:left;margin-right: 20px;">
							<select class="form-control director_exempt" style="text-align:right;width: 200px;" name="director_exempt" id="director_exempt">
								<option value="0">Select Exemption</option>
							</select>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3">Is kept at: </label>
				<div class="col-sm-8">
					<div style="width: 100%;">
						<div style="width: 75%;float:left;margin-right: 20px;">
							<select class="form-control director_kept" style="text-align:right;width: 600px;" name="director_kept" id="director_kept">
								<option value="0">Select Where Is Kept</option>
							</select>
						</div>
					</div>
				</div>
			</div> -->
			<input type="button" class="btn btn-primary submitArDeclarationInfo" id="submitArDeclarationInfo" value="Save" style="float: right; margin-bottom: 20px; margin-top: 20px;">
		</div>
	</form>
		<!-- <div class="form-group">
			<div class="col-sm-12">
				<input type="button" class="btn btn-primary submitAgmArInfo" id="submitAgmArInfo" value="Save" style="float: right; margin-bottom: 20px; margin-top: 20px;">
			</div>
		</div> -->
	<!-- </form> -->
</div>
<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>
<script>
	$("#total_dividend").change(function(){
		var total_dividend = $(this).val();
		$('.row_dividend').each(function(){
			var dividend = 0;
			// console.log($(this).attr('data-numberOfShare'));
			 //console.log(total_dividend);
			// console.log(total_number_of_share);
			dividend = (parseInt($(this).attr('data-numberOfShare')) * parseInt(removeCommas(total_dividend))) / parseInt(total_number_of_share);
			//* parseInt($(this).val())
			// console.log(dividend);
			// console.log(total_number_of_share);
			$(this).find("#dividend_fee").val(addCommas(dividend.toFixed(2)));
		});
	});

	

	$(".small_company").change(function(){
            console.log($(this).val());
            if($(this).val() == 2) //No
            {
                $("#audited_fs").val("1");
                $("#audited_fs").prop("disabled", true);
                $('#reappointment_auditor').prop('checked', true);
                $("#loadingmessage").show();
                $(".row_reappointment_auditor").remove();
				$.post("transaction/get_resign_auditor_info", {id: transaction_id, company_code: transaction_company_code, transaction_task_id: transaction_task_id, registration_no: $(".uen_section #uen").val()}, function(data){
			        $("#loadingmessage").hide();
			        create_reappoint_auditor_interface(data);
			        //console.log(JSON.parse(data));
			     //    array_for_auditor_info = JSON.parse(data);

			     //    for(var i = 0; i < array_for_auditor_info.length; i++)
				    // {
				    //     $a="";
			     //        $a += '<tr class="row_reappointment_auditor">';
			     //        $a += '<td><input type="text" style="text-transform:uppercase;" name="reappointment_auditor_name[]" id="name" class="form-control" value="'+ (array_for_auditor_info[i]["company_name"]!=null ? array_for_auditor_info[i]["company_name"] : array_for_auditor_info[i]["name"]) +'" readonly/><input type="hidden" class="form-control" name="reappointment_auditor_identification_register_no[]" id="hidden_resign_identification_register_no" value="'+ (array_for_auditor_info[i]["identification_no"]!=null ? array_for_auditor_info[i]["identification_no"] : array_for_auditor_info[i]["register_no"]) +'"/><div class="hidden"><input type="text" class="form-control" name="reappointment_auditor_client_officer_id[]" id="client_officer_id" value="'+array_for_auditor_info[i]["id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="reappointment_auditor_officer_id[]" id="officer_id" value="'+array_for_auditor_info[i]["officer_id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="reappointment_auditor_officer_field_type[]" id="officer_field_type" value="'+array_for_auditor_info[i]["field_type"]+'"/></div></td>';
			     //        $a += '</tr>';

			     //        $("#reappointment_auditor_add").append($a);
			     //    }
			    });
				$(".reappointment_auditor_div").show();
            }
            else if($(this).val() == 1)
            {
            	$("#audited_fs").val("0");
            	$("#audited_fs").prop("disabled", false);
            	$('#reappointment_auditor').prop('checked', false);
            	$(".reappointment_auditor_div").hide();
				$(".row_reappointment_auditor").remove();
            }
        });

	$("#audited_fs").change(function(){
		if($(this).val() == 0 || $(this).val() == 2) //No
        {
        	$('#reappointment_auditor').prop('checked', false);
        	$(".reappointment_auditor_div").hide();
			$(".row_reappointment_auditor").remove();
        }
        else if($(this).val() == 1)
        {
        	$('#reappointment_auditor').prop('checked', true);
        	reappointmentAuditor($('#reappointment_auditor')[0]);
        }
	});

	$("#solvency_status").change(function(){
		if($(this).val() == 2)
		{
			var xbrl_list = $(".xbrl_list").val();
			if(xbrl_list == 3)
			{
				$(".xbrl_list").val(0);
				toastr.error("Your are required to file XBRL due to insolvency.", "Error");
			}
		}
	});

	$("#xbrl_list").change(function(){
		if($(this).val() == 3)
		{
			var solvency_status = $("#solvency_status").val();
			if(solvency_status == 2)
			{
				$("#solvency_status").val(0);
				toastr.error("Your are required to file XBRL due to insolvency.", "Error");
			}
		}
	});
	
	//PRIVATE COMPANY LIMITED BY SHARES
	$("#company_type").change(function(){
		console.log($("#company_type option:selected").text());
		if($("#company_type option:selected").text() == "PRIVATE COMPANY LIMITED BY SHARES")
		{
			$(".xbrl_list").val("1");
			$(".xbrl_list").attr("disabled", true);
		}
		else
		{
			$(".xbrl_list").val("0");
			$(".xbrl_list").attr("disabled", false);
		}
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