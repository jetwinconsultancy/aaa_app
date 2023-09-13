<div id="transaction_share_allotment">
	<div id="transaction_confirmation_member_table">
		<span style="color: inherit; font-size: 2.4rem; letter-spacing: -1px; font-weight: 500; margin-top: 20px; margin-bottom: 10px; font-family: inherit; line-height: 2.1;">Meeting Info</span><a href="javascript:void(0)" class="btn btn-primary edit_share_allotment_info" id="edit_share_allotment_info"style="margin-left: 10px; margin-top: -5px; padding: 6px 10px; font-size: 12px">Edit</a>
		<div class="form-group">
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
		</table>
		<h3>Share Allotment Info</h3>
		<table class="table table-bordered table-striped table-condensed mb-none" style="width: 1000px">
			<thead>
				<tr>
					<th style="text-align: center;width: 180px">ID</th>
					<th style="text-align: center;width: 180px">Class</th>
					<th style="text-align: center;width: 177px">Number of Shares Issued</th>
					<th style="text-align: center;width: 185px">Number of Shares Paid Up</th>
					<th style="border-bottom:none;"></th>

				</tr>
				<tr>
					<th style="text-align: center;width: 180px">Name</th>
					<th style="text-align: center;width: 180px">Currency</th>
					<th style="text-align: center;width: 177px">Amount of Shares Issued</th>
					<th>Amount of Shares Paid Up</th>
					<th style="text-align: center;width:120px;border-top:none;">Certificate</th>
					
				</tr>
			</thead>

			<tbody id="confirmation_share_allotment_table">
									

			</tbody>
			
		</table>
	</div>
	

	<div id = "document">
		<h3>Compilation</h3>
		<table class="table table-bordered table-striped mb-none" id="datatable-pending" style="width:100%;">
		<thead>
			<tr>
				<th style="text-align: center;width:20px !important;padding-right:2px !important;padding-left:2px !important;">No</th>
				<!-- <th style="text-align: center;width:250px !important;padding-right:2px !important;padding-left:2px !important;">Client</th> -->
				<th style="text-align: center;width:310px !important;padding-right:2px !important;padding-left:2px !important;">Document Name</th>
				<!-- <th style="text-align: center;width:150px !important;padding-right:2px !important;padding-left:2px !important;">Created On</th> -->
				<th style="text-align: center;width:30px !important;padding-right:2px !important;padding-left:2px !important;">Received On</th>
			</tr>
		</thead>
		<tbody id="pending_doc_body">
			
		</tbody>
	</table>

	</div>
	<div>
		<h3>Follow Up History</h3>
		<div class="create_follow_up_form_section">
			<div class="col-sm-12">
				<a class="create_follow_up amber" href="javascript:void(0)" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;float: right;" data-original-title="Create Follow Up" ><i class="fa fa-plus-circle amber" style="font-size:16px;height:45px;"></i> Create Follow Up</a>
			</div>
		</div>
		<div class="follow_up_form_section" style="display: none;">
			<?php echo form_open_multipart('', array('id' => 'follow_up_form', 'enctype' => "multipart/form-data")); ?>
				<input type="hidden" class="form-control valid follow_up_history_id" id="follow_up_history_id" name="follow_up_history_id" value="">
				<div class="form-group">
					<label class="col-sm-2" for="w2-username">Date of Follow Up: </label>
					<div class="col-sm-4">
						<div class="input-group" style="width: 200px;">
							<span class="input-group-addon">
								<i class="far fa-calendar-alt"></i>
							</span>
							<input type="text" class="form-control valid date_of_follow_up" id="date_todolist" name="date_of_follow_up" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" required="" value="" placeholder="DD/MM/YYYY">
						</div>
					</div>
					<label class="col-sm-1" for="w2-username">Time: </label>
					<div class="col-sm-4">
						<div class="input-group date" style="width: 200px;">
							<span class="input-group-addon">
								<i class="far fa-clock"></i>
							</span>
							<input type="text" class="form-control valid time_of_follow_up" id="datetimepicker5" name="time_of_follow_up" required="" value="" placeholder="">
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2" for="w2-username">Remark: </label>
					<div class="col-sm-8">
						<textarea class="form-control follow_up_remark" id="follow_up_remark" name="follow_up_remark" rows="3"></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2" for="w2-username">Outcome: </label>
					<div class="col-sm-8">
						<select class="form-control follow_up_outcome" style="width: 500px;" name="follow_up_outcome" id="follow_up_outcome">
							<option value="0">Select Outcome</option>
						</select>
					</div>
				</div>
				<div class="action_part" style="display: none;">
					<div class="form-group">
						<label class="col-sm-2" for="w2-username">Next Follow Up Action: </label>
						<div class="col-sm-8">
							<select class="form-control follow_up_action" style="width: 200px;" name="follow_up_action" id="follow_up_action" disabled>
								<option value="0">Select Action</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2" for="w2-username">Next Follow Up Date: </label>
						<div class="col-sm-4">
							<div class="input-group" style="width: 200px;">
								<span class="input-group-addon">
									<i class="far fa-calendar-alt"></i>
								</span>
								<input type="text" class="form-control valid next_follow_up_date" id="date_todolist" name="next_follow_up_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" required="" value="" placeholder="DD/MM/YYYY" disabled>
							</div>
						</div>
						<label class="col-sm-1" for="w2-username">Time: </label>
						<div class="col-sm-4 form-group">
							<div class="input-group date" style="width: 200px;">
								<span class="input-group-addon">
									<i class="far fa-clock"></i>
								</span>
								<input type="text" class="form-control valid next_follow_up_time" id="datetimepicker6" name="next_follow_up_time" required="" value="" placeholder="" disabled>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-12">
						<button type="button" class="btn btn-primary save_follow_up" id="save_follow_up" style="float: right">Save</button>
					</div>
				</div>
			<?= form_close(); ?>
		</div>
		<table class="table table-bordered table-striped table-condensed mb-none" style="width: 1000px">
			<thead>
				<tr>
					<th style="text-align: center;width: 180px">ID</th>
					<th style="text-align: center;width: 230px">Current Follow Up Date & Time</th>
					<th style="text-align: center;width: 237px">Next Follow Up Date & Time</th>
					<th style="text-align: center;width: 200px">Follow Up By</th>
					<th style="width: 100px"></th>
				</tr>
			</thead>

			<tbody id="follow_up_table">									
			</tbody>
			
		</table>
	</div>

	<div>
		<h3>Service Status</h3>

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
			<label class="col-sm-3" for="w2-DS2">Status:</label>
			<div class="col-sm-8 mb-md">
				<select class="form-control tran_status" style="width: 500px;" name="tran_status" id="tran_status">
					<option value="0">Select Status</option>
				</select>
			</div>
			<div class="reason_cancellation_textfield" style="display: none">
				<label class="col-sm-3" for="reason_cancellation">Reason Cancellation Transaction:</label>
				<div class="col-sm-8">
					<textarea class="form-control cancellation_reason" rows="4" cols="50" name="cancellation_reason"></textarea>
				</div>
			</div>
		</div>
	</div>

</div>
<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>
<script src="themes/default/assets/js/confirmation_share_allotment.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
