<div id="w2-issue_director_fee_form" class="panel">
	<h3>Meeting Info</h3>
	<form id="issue_director_fee_form" style="margin-top: 20px;">
		<input type="hidden" class="form-control company_code" id="company_code" name="company_code" value=""/>
		<input type="hidden" class="form-control transaction_issue_director_fee_id" id="transaction_issue_director_fee_id" name="transaction_issue_director_fee_id" value=""/>
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
		<h3>Issue Director Fee</h3>
		<div class="form-group">
			<label class="col-sm-2" for="w2-declare_of_fye">Declare of FYE: </label>
			<div class="col-sm-8">
				<div class="input-group mb-md" style="width: 200px;">
					<span class="input-group-addon">
						<i class="far fa-calendar-alt"></i>
					</span>
					<input type="text" class="form-control valid declare_of_fye" id="date_todolist" name="declare_of_fye" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" required="" value="" placeholder="DD/MM/YYYY">
				</div>
			</div>
		</div>
		<!-- <div class="form-group">
			<label class="col-sm-2" for="w2-resolution_date">Resolution Date: </label>
			<div class="col-sm-8">
				<div class="input-group mb-md" style="width: 200px;">
					<span class="input-group-addon">
						<i class="far fa-calendar-alt"></i>
					</span>
					<input type="text" class="form-control valid resolution_date" id="date_todolist" name="resolution_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" required="" value="" placeholder="DD/MM/YYYY">
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2" for="w2-meeting_date">Meeting Date: </label>
			<div class="col-sm-8">
				<div class="input-group mb-md" style="width: 200px;">
					<span class="input-group-addon">
						<i class="far fa-calendar-alt"></i>
					</span>
					<input type="text" class="form-control valid meeting_date" id="date_todolist" name="meeting_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" required="" value="" placeholder="DD/MM/YYYY">
				</div>
			</div>
		</div> -->
		<div class="form-group">
			<label class="col-sm-2" for="w2-notice_date">Notice Date: </label>
			<div class="col-sm-8">
				<div class="input-group mb-md" style="width: 200px;">
					<span class="input-group-addon">
						<i class="far fa-calendar-alt"></i>
					</span>
					<input type="text" class="form-control valid notice_date" id="date_todolist" name="notice_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" required="" value="" placeholder="DD/MM/YYYY">
				</div>
			</div>
		</div>
		<table class="table table-bordered table-striped table-condensed mb-none" id="latest_director_table" style="width: 750px">
			<thead>
				<tr>
					<!-- <th id="id_position" style="text-align: center;width:170px">Position</th> -->
					<th id="id_header" style="text-align: center;width:270px">ID</th>
					<th style="text-align: center;width:270px" id="id_name">Director Name</th>
					<th style="text-align: center;width:270px" id="id_name">Date of Appointment</th>
					<th style="text-align: center;width:270px">Currency</div>
					<th style="text-align: center;width:270px">Fee</div>
				</tr>
				
			</thead>
			<tbody id="body_issue_director_fee">
			</tbody>
			
		</table>

		<div class="form-group">
			<div class="col-sm-12">
				<input type="button" class="btn btn-primary submitIssueDirectorFeeInfo" id="submitIssueDirectorFeeInfo" value="Save" style="float: right; margin-bottom: 20px; margin-top: 20px;">
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