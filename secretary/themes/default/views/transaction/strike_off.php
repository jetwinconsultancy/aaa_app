<div id="w2-strike_off_form" class="panel">
	<h3>Strike Off Application</h3>
	<input type="hidden" class="form-control company_code" id="company_code" name="company_code" value=""/>
	<input type="hidden" class="form-control transaction_strike_off_id" id="transaction_strike_off_id" name="transaction_strike_off_id" value=""/>
	<form id="notice_form" style="margin-top: 20px;">
  		<button type="button" class="collapsible"><span style="font-size: 2.4rem;">Notice</span></button>
		<div id="notice_info" class="incorp_content">
			<div class="form-group" style="margin-top: 20px;">
				<label class="col-sm-3">Company Name: </label>
				<div class="col-sm-8">
					<label id="company_name"></label>
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
			<div class="form-group agm_date_section">
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
			<!-- <div class="form-group date_fs_section">
				<label class="col-sm-3">Date FS sent to members: </label>
				<div class="col-sm-4">
					<div class="input-group agm_date_input" style="width: 200px;">
						<span class="input-group-addon">
							<i class="far fa-calendar-alt"></i>
						</span>
						<input type="text" class="form-control valid date_fs_sent_to_member" id="date_todolist date_fs_sent_to_member" name="date_fs_sent_to_member" data-date-format="dd MM yyyy" data-plugin-datepicker="" required="" value="" placeholder="DD/MM/YYYY">
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
							<div style="width: 75%;">
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
			<input type="button" class="btn btn-primary submitStrikeOffNoticeInfo" id="submitStrikeOffNoticeInfo" value="Save" style="float: right; margin-bottom: 20px; margin-top: 20px;">
		</div>
	</form>
	<form id="strike_off_form" style="margin-top: 20px;">
  		<button type="button" class="collapsible"><span style="font-size: 2.4rem;">Reason</span></button>
		<div id="notice_info" class="incorp_content">
			<!-- <input type="hidden" class="form-control company_code" id="company_code" name="company_code" value=""/> -->
			<!-- <input type="hidden" class="form-control transaction_strike_off_id" id="transaction_strike_off_id" name="transaction_strike_off_id" value=""/> -->
			<div class="form-group" style="margin-top: 20px;">
				<label class="col-sm-2" for="w2-reason_for_application">Reason For Application: </label>
				<div class="col-sm-8">
					<select class="form-control reason_for_application" style="width: 500px;" name="reason_for_appication" id="reason_for_appication"><option value="0">Select Reason Of Application</option></select>
				</div>
			</div>
			<div class="form-group ceased_date_row" style="display: none;">
				<label class="col-sm-2" for="w2-ceased_date">Ceased Date: </label>
				<div class="col-sm-8">
					<div class="input-group mb-md" style="width: 200px;">
						<span class="input-group-addon">
							<i class="far fa-calendar-alt"></i>
						</span>
						<input type="text" class="form-control valid ceased_date" id="date_todolist" name="ceased_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" required="" value="" placeholder="DD/MM/YYYY" disabled="disabled">
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="col-sm-12">
					<input type="button" class="btn btn-primary submitStrikeOffInfo" id="submitStrikeOffInfo" value="Save" style="float: right; margin-bottom: 20px; margin-top: 20px;">
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