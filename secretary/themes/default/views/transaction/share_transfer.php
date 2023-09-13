<div id="w2-share_transfer_form" class="panel">
	<h3>Share Transfer</h3>
	<form id="share_transfer_form" style="margin-top: 20px;">
		<input type="hidden" class="form-control company_code" id="company_code" name="company_code" value=""/>
		<input type="hidden" class="form-control hidden_company_name" id="hidden_company_name" name="company_name" value=""/>
		<input type="hidden" class="form-control transaction_share_transfer_id" id="transaction_share_transfer_id" name="transaction_share_transfer_id" value=""/>
		<input type="hidden" class="form-control" name="client_member_share_capital_id" id="client_member_share_capital_id" value=""/>
		<div class="hidden"><input type="text" class="form-control" name="transaction_type" value="Transfer"/></div>
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
		<!-- <div class="form-group">
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
		
		<h3>Share Transfer Info</h3> -->
		<div class="form-group">
			<label class="col-sm-2" for="w2-username">Class: </label>
			<div class="col-sm-8">
				<div style="width: 100%;">
					<div style="width: 75%;float:left;margin-right: 20px;">
						<select class="form-control" style="text-align:right;width: 200px;" name="class" id="class">
							<option value="0">Select Class</option>
						</select>
						
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2" for="w2-username">Currency: </label>
			<div class="col-sm-8">
				<div style="width: 100%;">
					<div style="width: 75%;float:left;margin-right: 20px;">
						<input type="text" style="width: 200px;" class="form-control" name="currency" id="currency" value="" readonly/>
						
					</div>
				</div>
			</div>
		</div>

		<div id="transfer_member">
			<h3 align="left">From</h3>	
			<table class="table table-bordered table-striped table-condensed mb-none" style="width: 800px">
				<thead>
					<tr>
						<th style="width:150px; text-align: center">ID/Name/Share Number</th>
						<!-- <th style="width:150px; text-align: center">Name</th> -->
						<th style="width:180px; text-align: center">Current Number of Shares</th>
						<th style="width:200px; text-align: center">Number of Shares to Transfer</th>
						<th>Consideration</th>
						<!-- <th>Certificate</th> -->
						<!-- <th><a href="javascript: void(0);" style="border-bottom:none;color: #D9A200;width:140px; outline: none !important;text-decoration: none;"><span id="transfer_member_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Transfer" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add Transferor</span></a></th> -->
					</tr>
				</thead>
				<tbody id="transfer_add">
										

				</tbody>
				<tr id="total_share">
					<th>Total</th>
					<th style="border-right: none;"></th>
					<!-- <th style="border-left: none;"></th> -->
					<th style="text-align: right;" id="total_from">0</th>
					<th></th>
					<!-- <th></th> -->
					<!-- <th></th> -->
				</tr>
			</table>


			<h3 class="to" align="left">To</h3>	
			<table class="table table-bordered table-striped table-condensed mb-none" style="width: 800px">
				<thead>
					<tr id="table_transfer_to">
						<th style="width:220px;">ID</th>
						<th style="width:220px;">Name</th>
						<th style="width:190px;">Number of Shares</th>
						<!-- <th>Certificate</th> -->
						<!-- <th><a href="javascript: void(0);" style="border-bottom:none;color: #D9A200;width:140px; outline: none !important;text-decoration: none;"><span id="transfer_to_member_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Transfer" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add Transferee</span></a></th> -->
					</tr>
				</thead>
				<tbody id="transfer_to_add">
										

				</tbody>
				<tr id="total_share_transfer_to">
					<th>Total</th>
					<th style=""></th>
					<th style="text-align: right;" id="total_to">0</th>
					<!-- <th></th> -->
					<!-- <th></th> -->
				</tr>
			</table>
		</div>

		

		<div class="form-group">
			<div class="col-sm-12">
				<input type="button" class="btn btn-primary submitShareTransferInfo" id="submitShareTransferInfo" value="Save" style="float: right; margin-bottom: 20px; margin-top: 20px;">
			</div>
		</div>
	</form>
	<div class="register_member_table" id="register_table_member" style="width:100%;">
		<table style="border:1px solid black; width: 100%;" class="allotment_table" id="register_filing_table">
			<thead>
				<tr>
					<th style="text-align:center; width:40px !important;padding-right:2px !important;padding-left:2px !important;">No</th>
					<th style="word-break:break-all;text-align: center;width:80px !important;padding-right:2px !important;padding-left:2px !important;">Transferor ID</th>
					<th style="word-break:break-all;text-align: center; width:150px !important;padding-right:2px !important;padding-left:2px !important;">Transferor Name</th>
					<th style="word-break:break-all;text-align: center;width:80px !important;padding-right:2px !important;padding-left:2px !important;">Transferee ID</th>
					<th style="word-break:break-all;text-align: center; width:80px !important;padding-right:2px !important;padding-left:2px !important;">Transferee Name</th>
					<th style="text-align: center; width:70px !important;padding-right:2px !important;padding-left:2px !important;">Class</th>
					<!-- <th style="text-align: center; width:20px !important;padding-right:2px !important;padding-left:2px !important;">Share Certificate No.</th> -->
					<th style="text-align: center; width:50px !important;padding-right:2px !important;padding-left:2px !important;">CCY</th>
					<!-- 1 -->
					<th style="text-align: center; width:10px !important;padding-right:2px !important;padding-left:2px !important;">No. of Shares Transferred</th>
					<th style="width:20px !important;"></th>
					<!-- <th style="text-align: center; width:10px !important;padding-right:2px !important;padding-left:2px !important;">Balance No of Shares</th> -->
								
				</tr>
			</thead>
			<tbody id="transfer_info_add">
										

			</tbody>
		</table>
	</div>
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