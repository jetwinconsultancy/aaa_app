<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/dataTables.checkboxes.min.js"></script>
<link rel="stylesheet" href="<?=base_url()?>assets/vendor/jquery-datatables/media/css/dataTables.checkboxes.css" />
<script src="<?=base_url()?>assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/natural.js"></script>
<script src="<?= base_url() ?>node_modules/bootbox/bootbox.min.js"></script>
<script src="<?= base_url() ?>node_modules/moment/moment.js"></script>
<link rel="stylesheet" href="<?=base_url()?>node_modules/fullcalendar/dist/fullcalendar.css" />
<script src="<?= base_url() ?>node_modules/fullcalendar/dist/fullcalendar.js"></script>
<script src="<?= base_url() ?>node_modules/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>node_modules/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.css" />
<script src="<?= base_url() ?>node_modules/bootstrap-switch/dist/js/bootstrap-switch.js"></script>
<script src="<?= base_url() ?>node_modules/bootstrap-datepicker/dist/js/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css" />
<link rel="stylesheet" href="<?= base_url() ?>application/css/plugin/toastr.min.css" />
<script src="<?= base_url() ?>application/js/toastr.min.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>node_modules/bootstrap-multiselect/dist/css/bootstrap-multiselect.css" />
<script src="<?= base_url() ?>node_modules/bootstrap-multiselect/dist/js/bootstrap-multiselect.js"></script>


<section role="main" class="content_section" style="margin-left:0;">
<section class="panel" style="margin-top: 30px;">
	<header class="panel-heading">
		<div class="panel-actions">
			<a class="apply_leave themeColor_purple" href="leave/apply_leave_admin" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Create Interview" ><i class="fa fa-plus-circle themeColor_purple" style="font-size:16px;height:45px;"></i> Apply Leave</a>
		</div>
		<h2></h2>
	</header>

	<div class="panel-body">
		<div class="col-md-12">
			<div class="tabs">				
				<ul class="nav nav-tabs nav-justify">

					<li class="active check_state" data-information="pending">
						<a href="#w2-pending" data-toggle="tab" class="text-center">
							<span class="badge hidden-xs">1</span>
							Pending
						</a>
					</li>
					<li class="check_state" data-information="day_off">
						<a href="#w2-day_off" data-toggle="tab" class="text-center">
							<span class="badge hidden-xs">2</span>
							Day Off
						</a>
					</li>
					<li class="check_state" data-information="history">
						<a href="#w2-history" data-toggle="tab" class="text-center">
							<span class="badge hidden-xs">3</span>
							History
						</a>
					</li>
					<li class="check_state" data-information="remaining_leave">
						<a href="#w2-remaining_leave" data-toggle="tab" class="text-center">
							<span class="badge hidden-xs">4</span>
							Remaining Leave
						</a>
					</li>

				</ul>
				<div class="tab-content clearfix">
					<div id="w2-pending" class="tab-pane active">
						<div style="font-size: 2.4rem;padding: 0; margin: 7px 0 14px 0;">Pending</div>
						<table class="table table-bordered table-striped mb-none datatable-default" id="" style="width:100%">
							<thead>
								<tr style="background-color:white;">

									<th class="text-left">Leave no.</th>
									<th class="text-left">Employee</th>
									<th class="text-left">Firm</th>
									<th class="text-left">Start Date</th>
									<th class="text-left">End Date</th>
									<th class="text-left">Type of Leave</th>
									<th class="text-center">Total Days</th>
									<th class="text-center">Remaining (Before Approve)</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php 
									foreach($leave_list as $row)
						  			{
						  				echo '<tr>';
						  				echo '<input type="hidden" class="leave_id" value="'. $row->id .'">';
						  				echo '<input type="hidden" class="employee_id" value="'. $row->employee_id .'">';
						  				echo '<input type="hidden" class="type_of_leave_id" value="'. $row->type_of_leave_id .'">';
						  				echo '<td>
						  						<a href="leave/apply_leave/'.$row->id.'/view">'.$row->leave_no.'</a></td>';
						  				echo '<td>'.$row->employee_name.'</td>';
						  				echo '<td>'.$row->firm_name.'</td>';
						  				echo '<td>'.date('d F Y', strtotime($row->start_date)).'</td>';
						  				echo '<td>'.date('d F Y', strtotime($row->end_date)).'</td>';
						  				echo '<td>'.$row->leave_name.'</td>';
						  				echo '<td>'.$row->total_days.'</td>';
						  				echo '<td>'.$row->remaining_al.'</td>';

						  				if($row->status == '1'){
					  						echo '<td>
					  								<button class="btn btn_purple" onclick="change_status(true,'. $row->id .','. $row->employee_id .','. $row->type_of_leave_id .','. $row->total_days .','. $row->remaining_al .')" style="margin-right:10px; margin-bottom:10px;">Approve</button>

					  								<button class="btn btn_purple" onclick="change_status(false,'. $row->id .','. $row->employee_id .','. $row->type_of_leave_id .','. $row->total_days .','. $row->remaining_al .')" style="margin-bottom:10px;">Reject</button>

													  <button class="btn btn_purple" onclick="cash_out(true,'. $row->id .','. $row->employee_id .','. $row->type_of_leave_id .','. $row->total_days .','. $row->remaining_al .')" style="margin-right:10px; margin-bottom:10px;">Cash Out</button>
					  								</td>';
					  					}
					  					else{
					  						echo '<td><button class="btn btn_purple" onclick="withdraw_leave('. $row->id .','. $row->employee_id .','. $row->total_days .','. $row->type_of_leave_id .','. $row->status.')" style="margin-bottom:10px;">Withdraw</button></td>';
					  					}

						  				echo '</tr>';
						  			}
								?>
							</tbody>
						</table>
						<div style="font-size: 2.4rem;padding: 0; margin: 7px 0 14px 0;">Calendar</div>
						<div>
					        <div style="float: left; width: 200px;">
								<label class="control-label">Offices : </label>
								<?php
									echo form_dropdown('office', $office, isset($office)?$office[0]:'', ' id="office_filter" class="select2" style="width:85%;"');
								?>
							</div> 
							<div style="float: left; width: 200px;">
								<label class="control-label">Departments : </label>
								<?php
									echo form_dropdown('department', $department, isset($department)?$department[0]:'', ' id="department_filter" class="select2" style="width:85%;"');
								?>
							</div>
					    </div>
						<div style="clear: both; padding-top: 20px" id="calender_container"></div>
					</div>

					<div id="w2-day_off" class="tab-pane">
						<div style="font-size: 2.4rem;padding: 0; margin: 7px 0 14px 0;">Day Off</div>
						<form id="day_off" method="POST" autocomplete="off">

							<div class="form-group row">
							    <label class="col-sm-3 col-form-label">Employee Name: </label>
						    	<div class="col-sm-5">
						    		<?php 
                                    	echo form_dropdown('employee_list', $employee_list, isset($employee_list['id'])?$employee_list['id']:'', 'id="multiple_day_off_employee_list" class="form-control employee_list" style="width:57.6%% !important" multiple="multiple" required');

                                    	// echo form_dropdown('users_list2', $users_list2, isset($users_list2[0]->first_name)?$users_list2[0]->first_name:'', 'id="multi-select-staff" class="multi-select-staff" style="width:85%;" multiple="multiple" required');
                                    ?>
					            </div>
							</div>

							<div class="form-group row">
							    <label class="col-sm-3 col-form-label">Remaining Annual Leave: </label>
							    <div class="col-sm-2">
							    	<input type='number' class="form-control" name="RAL" id='RAL' value="0" style="width: 35%; text-align: center; display:inline-block !important;" readonly /> 
							    	<label style="display:inline-block ; padding-left: 10px">day(s)</label>
							    </div>
							</div>

							<div class="form-group row">
							    <label class="col-sm-3 col-form-label">Number of Day Off: </label>
							    <div class="col-sm-2">
						            <input disabled type='number' class="form-control" name="NDF" id='NDF' min="1" style="width: 35%; text-align: center; display:inline-block !important;" required /> 
						            <label style="display:inline-block !important ; padding-left: 10px">day(s)</label>
							    </div>
							</div>

			            	<!-- <div class="form-group row">
							    <label for="leave_start_date" class="col-sm-3 col-form-label">Start Date: </label>
							    <div class="col-sm-3">
							    	<div class='input-group date datepicker' id='start_datepicker'>
							    		<span class="input-group-addon">
											<i class="far fa-calendar-alt"></i>
										</span>
					                    <input type='text' class="form-control leave_start_date" name="leave_start_date" id='start_date' required disabled/>
					                </div>
							    </div>
							    <div class="col-sm-2">
							    	<div class="col-sm-10">
							    		<input type="checkbox" name="start_time" id="start_time"/>
							    		<input type="input" class="hidden" name="start_time_checkbox" id="start_time_checkbox"/>
						            </div>
							    </div>
							</div>

							<div class="form-group row">
							    <label for="leave_end_date" class="col-sm-3 col-form-label">End Date: </label>
							    <div class="col-sm-3">
							    	<div class='input-group date datepicker' id='end_datepicker'>
							    		<span class="input-group-addon">
											<i class="far fa-calendar-alt"></i>
										</span>
					                    <input type='text' class="form-control leave_end_date" name="leave_end_date" id='end_date' required disabled/>
					                </div>
							    </div>
							    <div class="col-sm-2">
							    	<div class="col-sm-10">
							    		<input type="checkbox" name="end_time" id="end_time"/>
							    		<input type="input" class="hidden" name="end_time_checkbox" id="end_time_checkbox"/>
						            </div>
							    </div>
							</div>


							<div class="form-group row">
							    <label for="mc_reason" class="col-sm-3 col-form-label">Total Day Off: </label>
							    <div class="col-sm-5">
							    	<label id="total_leave_apply" style="display:inline-block;"/>
							    		<?php echo '0' ?>
							    	</label>
							    	<input id="leave_total_days" name="leave_total_days" type="hidden" value="">
							    	<input id="leave_total_days_backup" name="leave_total_days_backup" type="hidden" value="">
							    	<label style="display:inline-block;">day(s)</label>
							    </div>
							</div> -->

							<div class="form-group">
				                <div style="width: 100%;">
				                    <div style="width: 25%;float:left;margin-right: 20px;">
				                        <label></label>
				                    </div>
				                    <div style="float:right;margin-bottom:5px;">
				                        <div class="input-group">
				                        	<button class="btn btn_purple" type="submit">Apply</button>
				                        </div>
				                    </div>
				                </div>
				            </div>
						</form>
					</div>

					<div id="w2-history" class="tab-pane">
						<div style="font-size: 2.4rem;padding: 0; margin: 7px 0 14px 0;">History</div>
						<table class="table table-bordered table-striped mb-none datatable-default" id="" style="width:100%;">
							<thead>
								<tr style="background-color:white;">
									<th rowspan="2" class="text-left">Leave no.</th>
									<th rowspan="2" class="text-left">Employee</th>
									<th rowspan="2" class="text-left">Firm</th>
									<th rowspan="2" class="text-left">Start Date</th>
									<th rowspan="2" class="text-left">End Date</th>
									<th rowspan="2" class="text-center">Total Days</th>
									<th rowspan="2" class="text-center">Type of Leave</th>
									<th colspan="2" class="text-center">Remaining Annual Leave</th>
									<th rowspan="2" class="text-center">Status</th>
									<th rowspan="2" class="text-center">Status updated by</th>
								</tr>
								<tr>
									<th class="text-center">Before Approve</th>
									<th class="text-center">After Approve</th>
								</tr>
							</thead>
							<tbody>
								<?php 
									foreach($history_list as $row)
						  			{
						  				echo '<tr>';
						  				echo '<input type="hidden" class="leave_id" value="'. $row->id .'">';
						  				echo '<td>
						  						<a href="leave/apply_leave/'.$row->id.'/view">'.$row->leave_no.'</a></td>';
						  				echo '<td>'.$row->employee_name.'</td>';
						  				echo '<td>'.$row->firm_name.'</td>';
						  				echo '<td>'.date('d F Y', strtotime($row->start_date)).'</td>';
						  				echo '<td>'.date('d F Y', strtotime($row->end_date)).'</td>';
						  				echo '<td>'.$row->total_days.'</td>';
						  				echo '<td>'.$row->leave_name.'</td>';
						  				echo '<td>'.$row->al_left_before.'</td>';
						  				echo '<td>'.$row->al_left_after.'</td>';
						  				echo '<td>'.$row->status.'</td>';
						  				echo '<td>'.$row->status_updated_by.'</td>';
						  				echo '</tr>';
						  			}
								?>
							</tbody>
						</table>
					</div>

					<div id="w2-remaining_leave" class="tab-pane">
						<div style="font-size: 2.4rem;padding: 0; margin: 7px 0 14px 0;">Remaining Leave</div>
						<table class="table table-bordered table-striped mb-none datatable-default" id="" style="width:100%;">
							<thead>
								<tr style="background-color:white;">
									<th rowspan="2" class="text-left">Employee Name</th>
									<th rowspan="2" class="text-left">Firm</th>
									<th colspan="3" class="text-center">Remaining Leave</th>
								</tr>
								<tr>
									<th class="text-center">Annual Leave</th>
									<th class="text-center">Sick Leave</th>
									<th class="text-center">Hospitalization</th>
								</tr>
							</thead>
							<tbody>
								<?php 
									foreach($latest_leave_list as $leave){
										echo '</tr>';
										echo '<td>'.$leave->name.'</td>';
										echo '<td>'.$leave->firm_name.'</td>';
										echo '<td>'.$leave->AL.'</td>';
										echo '<td>'.$leave->SL.'</td>';
										echo '<td>'.$leave->HL.'</td>';
										echo '</tr>';
									}
								?>
							</tbody>
						</table>
					</div>
					
				</div>
			</div>
		</div>
	</div>
</section>
</section>

<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>
<script>
	var base_url = '<?php echo base_url(); ?>';
	// var calender_list = JSON.parse('<?php echo json_encode($calender_list) ?>');
	var calender_list = <?php echo json_encode(isset($calender_list)?$calender_list:FALSE) ?>;
	var holiday_list =  <?php echo json_encode(isset($holiday_list)?$holiday_list:"") ?>;
	var start_time_state = false;
	var end_time_state = false;
	var calender_event = [];

	$('.select2').select2();

	$(document).on('click',".check_state",function(){
		var index_tab_aktif = $(this).data("information");
		if(index_tab_aktif == "pending")
		{
			$(".apply_leave").show();
		}
		else{

			$(".apply_leave").hide();
		}
	});

	
	if(calender_list.length > 0)
	{	
		for(var y = 0; y < calender_list.length; y++)
		{
			calender_event.push({
				title: calender_list[y]['employee_name'], 
				start: moment(calender_list[y]["start_date"]).format('YYYY-MM-DD'), 
				end: moment(calender_list[y]["end_date"], 'YYYY-MM-DD').add(1, 'days'),
			});
		}
	}

	if(holiday_list.length > 0)
	{	
		for(var y = 0; y < holiday_list.length; y++)
		{
			calender_event.push({
				title: holiday_list[y]['description'], 
			 	start: moment(holiday_list[y]["holiday_date"]).format('YYYY-MM-DD'), 
				allDay: true,
				color:"#c4001d !important",
			 	rendering: "background"
			});
		}
	}

	$(document).on('change',"#office_filter",function(){
		var selected_filter = $('#office_filter').val();
		var selected_department = $('#department_filter').val();

		$('#calender_container').fullCalendar('removeEvents');
		var calender_event = [];

		for(var y = 0; y < holiday_list.length; y++)
		{
			calender_event.push({
				title: holiday_list[y]['description'], 
			 	start: moment(holiday_list[y]["holiday_date"]).format('YYYY-MM-DD'), 
				allDay: true,
				color:"#c4001d !important",
			 	rendering: "background"
			});
		}

		for(var y = 0; y < calender_list.length; y++)
		{

			if(selected_filter == 0 && selected_department == 0)
			{
				calender_event.push({
					title: calender_list[y]['employee_name'], 
					start: moment(calender_list[y]["start_date"]).format('YYYY-MM-DD'), 
					end: moment(calender_list[y]["end_date"], 'YYYY-MM-DD').add(1, 'days'),
				});
			}
			else if(selected_filter == 0 && selected_department != 0)
			{
				if(calender_list[y]['department'] == selected_department)
				{
					calender_event.push({
						title: calender_list[y]['employee_name'], 
						start: moment(calender_list[y]["start_date"]).format('YYYY-MM-DD'), 
						end: moment(calender_list[y]["end_date"], 'YYYY-MM-DD').add(1, 'days'),
					});
				}
			}
			else if(selected_filter != 0 && selected_department == 0)
			{
				if(calender_list[y]['office'] == selected_filter)
				{
					calender_event.push({
						title: calender_list[y]['employee_name'], 
						start: moment(calender_list[y]["start_date"]).format('YYYY-MM-DD'), 
						end: moment(calender_list[y]["end_date"], 'YYYY-MM-DD').add(1, 'days'),
					});
				}
			}
			else
			{
				if(calender_list[y]['office'] == selected_filter && calender_list[y]['department'] == selected_department)
				{
					calender_event.push({
						title: calender_list[y]['employee_name'], 
						start: moment(calender_list[y]["start_date"]).format('YYYY-MM-DD'), 
						end: moment(calender_list[y]["end_date"], 'YYYY-MM-DD').add(1, 'days'),
					});
				}
			}
		}

		$('#calender_container').fullCalendar('addEventSource', calender_event);
	});

	$(document).on('change',"#department_filter",function(){

		var selected_filter = $('#office_filter').val();
		var selected_department = $('#department_filter').val();

		$('#calender_container').fullCalendar('removeEvents');
		var calender_event = [];

		for(var y = 0; y < holiday_list.length; y++)
		{
			calender_event.push({
				title: holiday_list[y]['description'], 
			 	start: moment(holiday_list[y]["holiday_date"]).format('YYYY-MM-DD'), 
				allDay: true,
				color:"#c4001d !important",
			 	rendering: "background"
			});
		}

		for(var y = 0; y < calender_list.length; y++)
		{

			if(selected_filter == 0 && selected_department == 0)
			{
				calender_event.push({
					title: calender_list[y]['employee_name'], 
					start: moment(calender_list[y]["start_date"]).format('YYYY-MM-DD'), 
					end: moment(calender_list[y]["end_date"], 'YYYY-MM-DD').add(1, 'days'),
				});
			}
			else if(selected_filter == 0 && selected_department != 0)
			{
				if(calender_list[y]['department'] == selected_department)
				{
					calender_event.push({
						title: calender_list[y]['employee_name'], 
						start: moment(calender_list[y]["start_date"]).format('YYYY-MM-DD'), 
						end: moment(calender_list[y]["end_date"], 'YYYY-MM-DD').add(1, 'days'),
					});
				}
			}
			else if(selected_filter != 0 && selected_department == 0)
			{
				if(calender_list[y]['office'] == selected_filter)
				{
					calender_event.push({
						title: calender_list[y]['employee_name'], 
						start: moment(calender_list[y]["start_date"]).format('YYYY-MM-DD'), 
						end: moment(calender_list[y]["end_date"], 'YYYY-MM-DD').add(1, 'days'),
					});
				}
			}
			else
			{
				if(calender_list[y]['office'] == selected_filter && calender_list[y]['department'] == selected_department)
				{
					calender_event.push({
						title: calender_list[y]['employee_name'], 
						start: moment(calender_list[y]["start_date"]).format('YYYY-MM-DD'), 
						end: moment(calender_list[y]["end_date"], 'YYYY-MM-DD').add(1, 'days'),
					});
				}
			}
		}

		$('#calender_container').fullCalendar('addEventSource', calender_event);
	});


	(function( $ ) {

		'use strict';
		var initCalendar = function() {
		var $calendar = $('#calender_container');
		$calendar.fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay,listWeek'
			},
			themeButtonIcons: {
				prev: 'fa fa-caret-left',
				next: 'fa fa-caret-right',
			},

			eventRender: function (event, element) {
			    if (event.rendering == 'background') {
			    	
			        element.append(event.title);
			        element.css('color', '#000000');
			        element.css('font-weight', 'bold');
			        element.css('font-size', 'large');
			        element.css('text-align', 'center');
			    }
			},

			editable: false,
			droppable: false,
			displayEventTime: false,
			navLinks: true, // can click day/week names to navigate views
			eventLimit: true, // allow "more" link when too many events
			events: calender_event
		});

		// FIX INPUTS TO BOOTSTRAP VERSIONS
		var $calendarButtons = $calendar.find('.fc-toolbar > .fc-right > button');

		$calendarButtons
			.not('.fc-button-prev, .fc-button-today, .fc-button-next')
				.wrapAll('<div class="btn-group mb-sm mt-sm"></div>');

		$calendarButtons
			.attr({ 'class': 'btn btn-sm btn-default' });
		};

		$(function() {
			initCalendar();
		});

	}).apply(this, [ jQuery ]);

	$(document).ready( function () {
	    $('.datatable-default').DataTable( {
	    	"order": []
	    } );

	    // $('.datatable-leave_history').DataTable( {
	    // 	"order": []
	    // } );
	} );

	function confirmationOL(id){
		$.post("./offer_letter/sendOL_NewEmployee", { 'id': id }, function(data, status){
			$('.modal-body').empty();
            $('.modal-body').prepend(data);
            $('#exampleModal').modal('show');
        });
	}

	function change_status(isApprove, leave_id, employee_id, type_of_leave_id, leave_days, remain_leave){

	        if(isApprove)
	        {
	        	var result = remain_leave - leave_days;
	        	
	        	if(!(result <= -20))
	        	{
	        		bootbox.confirm({
				        message: "Confirm to APPROVE the selected leave?",
				        closeButton: false,
				        buttons: {
				            confirm: {
				                label: 'Yes',
				                className: 'btn_purple'
				            },
				            cancel: {
				                label: 'No',
				                className: 'btn_cancel'
				            }
				        },
				        callback: function (result) {
				        	if(result == true)
				        	{
				        		$("#loadingmessage").show();

						    	$.post("<?php echo site_url('leave/change_status'); ?>", { leave_id: leave_id, is_approve: 1, employee_id: employee_id, type_of_leave_id: type_of_leave_id, reason: result }, function (data, status)
						    		{
						    			if(status)
						    			{
						    				$("#loadingmessage").hide();

						    				window.location = base_url + "leave";
						    			}
						    		} 
						    	);
							}
				        }
				    })
	        	}
	        	else
	        	{
	        		toastr.error('Annual Leave Over The Limit (-20)', 'Warning');
	        	}

	        }else{
	        	bootbox.prompt({
	        		closeButton: false,
			        title: "Confirm to REJECT the selected leave? Reason?",
			        message: "<p>Reason?</p>",
			        inputType:'textarea',
			        buttons: {
			            confirm: {
			                label: 'Yes',
			                className: 'btn_purple'
			            },
			            cancel: {
			                label: 'No',
			                className: 'btn_cancel'
			            }
			        },
			        callback: function (result) {
			        	if(result != null)
			        	{
			        		$("#loadingmessage").show();

							$.post("<?php echo site_url('leave/change_status'); ?>", { leave_id: leave_id, is_approve: 0, employee_id: employee_id, type_of_leave_id: type_of_leave_id, reason: result}, function (data, status)
								{
					    			if(status)
					    			{
					    				$("#loadingmessage").hide();

					    				window.location = base_url + "leave";
					    			}
					    		} 
					    	);
						}
			        }
			    })
	        }
	}

	function cash_out(leave_id, employee_id, type_of_leave_id, leave_days, remain_leave){

		// if(isApprove)
		// {
			var result = remain_leave - leave_days;
			
			if(!(result <= -20))
			{
				bootbox.confirm({
					message: "Confirm to cash out the selected leave?",
					closeButton: false,
					buttons: {
						confirm: {
							label: 'Yes',
							className: 'btn_purple'
						},
						cancel: {
							label: 'No',
							className: 'btn_cancel'
						}
					},
					callback: function (result) {
						if(result == true)
						{
							$("#loadingmessage").show();

							$.post("<?php echo site_url('leave/cash_out'); ?>", { leave_id: leave_id, is_approve: 1, employee_id: employee_id, type_of_leave_id: type_of_leave_id, reason: result }, function (data, status)
								{
									if(status)
									{
										$("#loadingmessage").hide();

										window.location = base_url + "leave";
									}
								} 
							);
						}
					}
				})
			}
			else
			{
				toastr.error('Annual Leave Over The Limit (-20)', 'Warning');
			}
	}

	function withdraw_leave(leave_id, employee_id, total_days, type_of_leave_id, status_id){

    	bootbox.confirm({
	        message: "Confirm to WITHDRAW the selected leave?",
	        closeButton: false,
	        buttons: {
	            confirm: {
	                label: 'Yes',
	                className: 'btn_purple'
	            },
	            cancel: {
	                label: 'No',
	                className: 'btn_cancel'
	            }
	        },
	        callback: function (result) {
	        	if(result == true)
	        	{
	        		$("#loadingmessage").show();

	        		$.post("<?php echo site_url('leave/withdraw_leave'); ?>", { leave_id: leave_id, employee_id: employee_id, total_days: total_days, type_of_leave_id: type_of_leave_id, status_id:status_id }, function (data, status)
	        			{
			    			if(status)
			    			{
			    				$("#loadingmessage").hide();
			    				
			    				window.location = base_url + "leave";
			    			}
			    		} 
			    	);
	        	}
	        }
	    })

    }

	// $('.datepicker').datepicker({
	//     format: 'dd MM yyyy',
	// });

	// $("[name='start_time']").bootstrapSwitch({
	//     size: 'normal',
	//     onColor: 'purple',
	//     onText: 'Half',
	//     offText: 'Full',
	//     // Text of the center handle of the switch
	//     labelText: '&nbsp',
	//     // Width of the left and right sides in pixels
	//     handleWidth: '75px',
	//     // Width of the center handle in pixels
	//     labelWidth: 'auto',
	//     baseClass: 'bootstrap-switch',
	//     wrapperClass: 'wrapper'
	// });

	// $("[name='end_time']").bootstrapSwitch({
	//     size: 'normal',
	//     onColor: 'purple',
	//     onText: 'Half',
	//     offText: 'Full',
	//     // Text of the center handle of the switch
	//     labelText: '&nbsp',
	//     // Width of the left and right sides in pixels
	//     handleWidth: '75px',
	//     // Width of the center handle in pixels
	//     labelWidth: 'auto',
	//     baseClass: 'bootstrap-switch',
	//     wrapperClass: 'wrapper'
	// });

	// $(".employee_list").change(function (e){

	// 	var from = new Date($("#start_date").val());
	// 	var to = new Date($("#end_date").val());

	// 	if($(".employee_list").val() != "")
	// 	{
	// 		document.getElementById('start_date').removeAttribute('disabled');
	// 		document.getElementById('end_date').removeAttribute('disabled');
	// 	}
	// 	else
	// 	{
	// 		document.getElementById('start_date').disabled = "true";
	// 		document.getElementById('end_date').disabled = "true";
	// 		$('#start_date').val("");
	// 		$('#end_date').val("");
	// 		$('#balance').val("");
	// 	}

	// 	if(from != 'Invalid Date' && to != 'Invalid Date')
	// 	{
	// 		$.ajax({
	// 	        type: "POST",
	// 	        url: "<?php echo site_url('leave/calculate_days_off'); ?>",
	// 	        data: { 'employee_id': $(".employee_list").val() , 'start_date': $("#start_date").val() , 'end_date': $("#end_date").val() , 'start_time': start_time_state , 'end_time': end_time_state },
	// 	        dataType: "json",
	// 	        success: function(data)
	// 	        {
	// 	        	document.getElementById('total_leave_apply').innerHTML = data['total_working_days'];
	// 	        	$('#leave_total_days').val(data['total_working_days']);
	// 	        	$('#leave_total_days_backup').val(data['total_working_days']);
	// 	        }
	// 	   	});
	// 	}
	// });

	// $("#start_date").change(function (e){

	// 	var from = new Date($("#start_date").val());
	// 	var to = new Date($("#end_date").val());

	// 	if(to < from)
	// 	{
	// 		to = new Date($("#start_date").val());
	// 		$("#end_date").val($("#start_date").val());
	// 		$('#end_datepicker').datepicker("setDate", new Date($("#start_date").val()) );

	// 		from = new Date("");
	// 		$("#start_date").val('');
	// 		$('#start_datepicker').datepicker("setDate", false );
			
	// 	}

	// 	if(from != 'Invalid Date' && to != 'Invalid Date')
	// 	{
	// 		$.ajax({
	// 	        type: "POST",
	// 	        url: "<?php echo site_url('leave/calculate_days_off'); ?>",
	// 	        data: { 'employee_id': $(".employee_list").val() , 'start_date': $("#start_date").val() , 'end_date': $("#end_date").val() , 'start_time': start_time_state , 'end_time': end_time_state },
	// 	        dataType: "json",
	// 	        success: function(data)
	// 	        {
	// 	        	document.getElementById('total_leave_apply').innerHTML = data['total_working_days'];
	// 	        	$('#leave_total_days').val(data['total_working_days']);
	// 	        	$('#leave_total_days_backup').val(data['total_working_days']);
	// 	        }
	// 	   	});
	// 	}

	// 	if($("#start_date").val() == "")
	// 	{
	// 		document.getElementById('total_leave_apply').innerHTML = '0';
	// 	}
	// });

	// $("#end_date").change(function (e){

	// 	var from = new Date($("#start_date").val());
	// 	var to = new Date($("#end_date").val());

	// 	if(to < from)
	// 	{
	// 		from = new Date($("#end_date").val());
	// 		$("#start_date").val($("#end_date").val());
	// 		$('#start_datepicker').datepicker("setDate", new Date($("#end_date").val()) );

	// 		to = new Date("");
	// 		$("#end_date").val('');
	// 		$('#end_datepicker').datepicker("setDate", false );
	// 	}

	// 	if(from != 'Invalid Date' && to != 'Invalid Date')
	// 	{
	// 		$.ajax({
	// 	        type: "POST",
	// 	        url: "<?php echo site_url('leave/calculate_days_off'); ?>",
	// 	        data: { 'employee_id': $(".employee_list").val() , 'start_date': $("#start_date").val() , 'end_date': $("#end_date").val() , 'start_time': start_time_state , 'end_time': end_time_state },
	// 	        dataType: "json",
	// 	        success: function(data)
	// 	        {
	// 	        	document.getElementById('total_leave_apply').innerHTML = data['total_working_days'];
	// 	        	$('#leave_total_days').val(data['total_working_days']);
	// 	        	$('#leave_total_days_backup').val(data['total_working_days']);
	// 	        }
	// 	   	});
	// 	}

	// 	if($("#end_date").val() == "")
	// 	{
	// 		document.getElementById('total_leave_apply').innerHTML = '0';
	// 	}
	// });

	// $("[name='start_time']").on('switchChange.bootstrapSwitch', function(event, state) {

	// 	start_time_state = state;

	// 	$.ajax({
	// 	        type: "POST",
	// 	        url: "<?php echo site_url('leave/calculate_days_off'); ?>",
	// 	        data: { 'employee_id': $(".employee_list").val() , 'start_date': $("#start_date").val() , 'end_date': $("#end_date").val() , 'start_time': start_time_state , 'end_time': end_time_state },
	// 	        dataType: "json",
	// 	        success: function(data)
	// 	        {
	// 	        	document.getElementById('total_leave_apply').innerHTML = data['total_working_days'];
	// 	        	$('#leave_total_days').val(data['total_working_days']);
	// 	        	$('#leave_total_days_backup').val(data['total_working_days']);
	// 	        }
	// 	   	});
	// });

	// $("[name='end_time']").on('switchChange.bootstrapSwitch', function(event, state) {

	// 	end_time_state = state;

	// 	$.ajax({
	// 	        type: "POST",
	// 	        url: "<?php echo site_url('leave/calculate_days_off'); ?>",
	// 	        data: { 'employee_id': $(".employee_list").val() , 'start_date': $("#start_date").val() , 'end_date': $("#end_date").val() , 'start_time': start_time_state , 'end_time': end_time_state },
	// 	        dataType: "json",
	// 	        success: function(data)
	// 	        {
	// 	        	document.getElementById('total_leave_apply').innerHTML = data['total_working_days'];
	// 	        	$('#leave_total_days').val(data['total_working_days']);
	// 	        	$('#leave_total_days_backup').val(data['total_working_days']);
	// 	        }
	// 	   	});

	// });
	$('#multiple_day_off_employee_list').multiselect({
	    allSelectedText: 'All',
	    enableFiltering: true,
	    enableCaseInsensitiveFiltering: true,
	    maxHeight: 200,
	    includeSelectAllOption: true
	});

	$("#day_off").submit(function(e) {

        var form = $(this);
        var id = $(".employee_list").val();
		var leave = '1';
		var dayoff_total_days = $('#NDF').val();

		bootbox.confirm({
	        message: "Do you wanna to give day off award?",
	        closeButton: false,
	        buttons: {
	            confirm: {
	                label: 'Yes',
	                className: 'btn_purple'
	            },
	            cancel: {
	                label: 'No',
	                className: 'btn_cancel'
	            }
	        },
	        callback: function (result) {
	        	if(result == true){
	        		$.ajax({
			           type: "POST",
			           url : "leave/submit_day_off",
			           data: { 'id': id , 'leave': leave , 'dayoff_total_days':dayoff_total_days }, // serializes the form's elements.
			           success: function(data)
			           {	
			           		if(data)
			           		{
			           			toastr.success('Day Off Successfully Awarded ', 'Successful');

			           			$("#multiple_day_off_employee_list").multiselect("clearSelection");
				       			document.getElementById('NDF').disabled = "true";
								$('#NDF').val("");
								$('#RAL').val(0);
			           		}
			           }
			       	});
	        	}
	        	else{
	        		$("#multiple_day_off_employee_list").multiselect("clearSelection");
	       			document.getElementById('NDF').disabled = "true";
					$('#NDF').val("");
					$('#RAL').val(0);
	        	}
	        }
    	});
    	e.preventDefault(); // avoid to execute the actual submit of the form.
    });

	$(".employee_list").change(function (e){

		var from = new Date($("#start_date").val());
		var to = new Date($("#end_date").val());

		if($(".employee_list").val() != "")
		{
			document.getElementById('NDF').removeAttribute('disabled');
		}
		else
		{
			document.getElementById('NDF').disabled = "true";
			$('#NDF').val("");
			$('#RAL').val(0);
		}

		if($(".employee_list").val() != ""){

			let employee_list = $(".employee_list").val();

			if(employee_list.length == 1){
				for(var a = 0; a < employee_list.length; a++)
				{
					$.ajax({
				        type: "POST",
				        url: "leave/check_remainAL",
				        data: {'employee_id': employee_list[a]},
				        success: function(data)
				        {
				        	if(data){
					        	var result = JSON.parse(data);
					        	$('#RAL').val(result[0]['annual_leave_days']);
					        }
				        }
				   	});
				}
			} else {
				$('#RAL').val(0);
			}
		}
	});

</script>