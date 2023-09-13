<div id="transaction_share_transfer">
	<div id="transaction_confirmation_member_table">
		<div style="margin-bottom: 20px;">
			<span class="help-block cert_remind_tag"></span>
		</div>
		<span style="color: inherit; font-size: 2.4rem; letter-spacing: -1px; font-weight: 500; margin-top: 20px; margin-bottom: 10px; font-family: inherit; line-height: 2.1;">Transferor Record</span>

		<form id="confirm_transfer_cert_form">
			<input type="hidden" class="hidden_latest_cert_no" value=""/>
			<!-- <input type="hidden" class="hidden_transaction_master_id" value=""/> -->
			<table style="border:1px solid black; width: 1070px;" class="allotment_table" id="transferee_table">
				<thead>
					<tr>
						<th style="width:300px; text-align: center">ID/Name</th>
						<th style="width:170px; text-align: center">Currency/Class</th>
						<th style="width:100px; text-align: center">Old Number of Shares</th>
						<th style="width:50px; text-align: center">Old Certificate</th>
						
						<th style="width:100px; text-align: center">Total Number of Shares to Transfer</th>
						<th style="width:100px; text-align: center">Number of Shares to Transfer</th>
						<th style="width:130px; text-align: center">New Number of Shares</th>
						<th style="width:100px; text-align: center">New Certificate</th>
					</tr>
				</thead>
				<tbody class="transferor_table_add">
				</tbody>
			</table>
			
			<span style="color: inherit; font-size: 2.4rem; letter-spacing: -1px; font-weight: 500; margin-top: 20px; margin-bottom: 10px; font-family: inherit; line-height: 2.1;">Transferee Record</span>
			<table style="border:1px solid black; width: 1070px;" class="allotment_table" id="certificate_table">
				<thead>
					<tr>
						<th style="width:300px; text-align: center">ID/Name</th>
						<th style="width:200px; text-align: center">Currency/Class</th>
						<th style="width:150px; text-align: center">New Number of Shares</th>
						<!-- <th style="width:50px; text-align: center">Old Certificate</th>
						<th style="width:150px; text-align: center">New Number of Shares</th> -->
						<th style="width:150px; text-align: center">New Certificate</th>
					</tr>
				</thead>
				<tbody class="certificate_table_add">
				</tbody>
			</table>
			<div class="form-group">
				<div class="col-sm-12">
					<input type="button" class="btn btn-primary submitShareTransferCertInfo" id="submitShareTransferCertInfo" value="Save" style="float: right; margin-bottom: 20px; margin-top: 20px;">
				</div>
			</div>
			<!-- <div class="form-group">
				<div class="col-sm-12">
					<input type="button" class="btn btn-primary submitShareTransferInfo" id="submitShareTransferInfo" value="Save" style="float: right; margin-bottom: 20px; margin-top: 20px;">
				</div>
			</div> -->
		</form>
		
		<span style="color: inherit; font-size: 2.4rem; letter-spacing: -1px; font-weight: 500; margin-top: 20px; margin-bottom: 10px; font-family: inherit; line-height: 2.1;">Share Transfer Info</span><a href="javascript:void(0)" class="btn btn-primary edit_share_transfer_info" id="edit_share_transfer_info" style="margin-left: 10px; margin-top: -5px; padding: 6px 10px; font-size: 12px">Edit</a>
		<!-- <div class="form-group">
			<label class="col-sm-2" for="w2-username">Director(s) Meeting Date: </label>
			<div class="col-sm-4">
				<div class="confirmation_director_meeting_date" id="confirmation_director_meeting_date"></div>
			</div>
			<label class="col-sm-1" for="w2-username">Time: </label>
			<div class="col-sm-4 form-group">
				<div class="confirmation_director_meeting_time" id="confirmation_director_meeting_time"></div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2" for="w2-username">Member(s) Meeting Date: </label>
			<div class="col-sm-4">
				<div class="confirmation_member_meeting_date" id="confirmation_member_meeting_date"></div>
			</div>
			<label class="col-sm-1" for="w2-username">Time: </label>
			<div class="col-sm-4 form-group">
				<div class="confirmation_member_meeting_time" id="confirmation_member_meeting_time"></div>
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
		</table> -->
		<!-- <h3>Share Transfer Info</h3> -->
		
		<table style="border:1px solid black; width: 1070px;" class="allotment_table" id="register_filing_table">
			<thead>
				<tr>
					<th style="text-align:center; width:20px !important;padding-right:2px !important;padding-left:2px !important;">No</th>
					<th style="word-break:break-all;text-align: center;width:60px !important;padding-right:2px !important;padding-left:2px !important;">Transferor ID</th>
					<th style="text-align: center; width:150px !important;padding-right:2px !important;padding-left:2px !important;">Transferor Name</th>
					<th style="word-break:break-all;text-align: center;width:80px !important;padding-right:2px !important;padding-left:2px !important;">Transferee ID</th>
					<th style="word-break:break-all;text-align: center; width:80px !important;padding-right:2px !important;padding-left:2px !important;">Transferee Name</th>
					<th style="text-align: center; width:70px !important;padding-right:2px !important;padding-left:2px !important;">Class</th>
					<!-- <th style="text-align: center; width:20px !important;padding-right:2px !important;padding-left:2px !important;">Share Certificate No.</th> -->
					<th style="text-align: center; width:50px !important;padding-right:2px !important;padding-left:2px !important;">CCY</th>
					<!-- 1 -->
					<th style="text-align: center; width:10px !important;padding-right:2px !important;padding-left:2px !important;">No. of Shares Transferred</th>
					<!-- <th style="text-align: center; width:10px !important;padding-right:2px !important;padding-left:2px !important;">Balance No of Shares</th> -->
								
				</tr>
			</thead>
			<tbody id="confirm_transfer_info_add">
										

			</tbody>
		</table>

		
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
	
</div>

<script src="themes/default/assets/js/confirmation_share_transfer.js?v=6434fdfsdfdrw32323233w1323" charset="utf-8"></script>
