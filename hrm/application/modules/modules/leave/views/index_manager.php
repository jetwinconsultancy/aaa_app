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
<link rel="stylesheet" href="<?= base_url() ?>application/css/plugin/toastr.min.css" />
<script src="<?= base_url() ?>application/js/toastr.min.js"></script>

<!-- <div style="margin-left:3%; margin-right:3%; margin-top: 30px;">
	<?php echo $breadcrumbs;?>
</div> -->
<section role="main" class="content_section" style="margin-left:0;">
<section class="panel" style="margin-top: 30px;">
	<header class="panel-heading">
		<div class="panel-actions">

			<a class="create_leave themeColor_purple" href="leave/apply_leave/" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Create Interview" ><i class="fa fa-plus-circle themeColor_purple" style="font-size:16px;height:45px;"></i> Apply Leave</a>

		</div>
		<h2></h2>
	</header>
	<!-- <div class="pull-right" style="margin: 0.5%;">
		<button class="btn btn_purple" onclick="change_status(true)">Approve Leave</button>
		<button class="btn btn-danger" onclick="change_status(false)">Reject Leave</button>
	</div> -->
	<div class="panel-body">
		<div class="col-md-12">
			<div class="tabs">				
				<ul class="nav nav-tabs nav-justify">
					<li class="active check_state" data-information="leave">
						<a href="#w2-leave" data-toggle="tab" class="text-center">
							<span class="badge hidden-xs">1</span>
							Apply
						</a>
					</li>
					<li class="check_state" data-information="pending">
						<a href="#w2-pending" data-toggle="tab" class="text-center">
							<span class="badge hidden-xs">2</span>
							Pending
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

					<!-- <li class="check_state" data-information="general">
						<a href="#w2-general" data-toggle="tab" class="text-center">
							<span class="badge hidden-xs">4</span>
							General
						</a>
					</li>
					<li class="check_state" data-information="setup">
						<a href="#w2-setup" data-toggle="tab" class="text-center">
							<span class="badge hidden-xs">5</span>
							Setup
						</a>
					</li> -->
				</ul>
				<div class="tab-content clearfix">
					<div id="w2-leave" class="tab-pane active">
						<div style="font-size: 2.4rem;padding: 0; margin: 7px 0 14px 0;">Leave</div>
						<table class="table table-bordered table-striped mb-none datatable-default" id="" style="width:100%">
							<thead>
								<tr style="background-color:white;">
									<th class="text-left">Leave No.</th>
									<th class="text-left">Start Date</th>
									<th class="text-left">End Date</th>
									<th class="text-left">Leave Type</th>
									<th class="text-center">Total Days</th>
									<th class="text-center">Leave Status</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php
									$date = date('d F Y');

									foreach($employee_leave_list as $row)
						  			{
						  				echo '<tr>';
						  				echo '<td><a href="leave/apply_leave/'.$row->id.'">'.$row->leave_no.'</a></td>';
						  				echo '<td>'.date('d F Y', strtotime($row->start_date)).'</td>';
						  				echo '<td>'.date('d F Y', strtotime($row->end_date)).'</td>';
						  				echo '<td>'.$row->leave_name.'</td>';
						  				echo '<td>'.$row->total_days.'</td>';
						  				echo '<td>'.$row->status.'</td>';

						  				if($row->status != 'Pending')
						  				{
						  					$a = new DateTime($date);
					  						$b = new DateTime(date('d F Y', strtotime($row->start_date)));
					  						
						  					if($a < $b)
							  				{
							  					if($row->status == 'Rejected' || $row->status == 'Withdraw')
							  					{
							  						echo '<td></td>';
							  					}
							  					else{
							  						echo '<td><button class="btn btn_purple" onclick="withdraw_leave('. $row->id .','. $row->employee_id .','. $row->total_days .','. $row->type_of_leave_id .','. $row->status_id .')" style="margin-bottom:10px;">Withdraw</button></td>';
							  					}
							  				}
							  				else{
							  					echo '<td></td>';
							  				}
						  				}
						  				else{

						  					echo '<td><button class="btn btn_purple" onclick="withdraw_leave('. $row->id .','. $row->employee_id .','. $row->total_days .','. $row->type_of_leave_id .','. $row->status_id .')" style="margin-bottom:10px;">Withdraw</button></td>';
						  				}

						  				echo '</tr>';
						  			}
								?>
							</tbody>
						</table>
					</div>
					<div id="w2-pending" class="tab-pane">
						<div style="font-size: 2.4rem;padding: 0; margin: 7px 0 14px 0;">Pending</div>
						<table class="table table-bordered table-striped mb-none datatable-default" id="" style="width:100%">
							<thead>
								<tr style="background-color:white;">
									<!-- <th style="width: 50px;"></th> -->
									<th class="text-left">Leave No.</th>
									<th class="text-left">Employee</th>
									<th class="text-left">Firm</th>
									<th class="text-left">Start Date</th>
									<th class="text-left">End Date</th>
									<th class="text-left">Type of Leave</th>
									<th class="text-center">Total Days</th>
									<!-- <th class="text-center">Days left (Before Apply)</th>
									<th class="text-center">Days left (After Approve)</th> -->
									<th class="text-center">Remaining (Before Approve)</th>
									<th></th>
									<!-- <th class="text-center">Leave Status</th> -->
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
						  				// echo '<td><input type="checkbox" name="select_leave"></td>';
						  				echo '<td>
						  						<a href="leave/apply_leave/'.$row->id.'/view">'.$row->leave_no.'</a></td>';
						  				echo '<td>'.$row->employee_name.'</td>';
						  				echo '<td>'.$row->firm_name.'</td>';
						  				echo '<td>'.date('d F Y', strtotime($row->start_date)).'</td>';
						  				echo '<td>'.date('d F Y', strtotime($row->end_date)).'</td>';
						  				echo '<td>'.$row->leave_name.'</td>';
						  				echo '<td>'.$row->total_days.'</td>';
						  				echo '<td>'.$row->remaining_al.'</td>';

						  				if($row->user_id == $this->user_id)
						  				{
						  					echo '<td></td>';
						  				}
						  				else
						  				{
						  					if($row->status == '1'){
						  						echo '<td>
						  								<button class="btn btn_purple" onclick="change_status(true,'. $row->id .','. $row->employee_id .','. $row->type_of_leave_id .','. $row->total_days .','. $row->remaining_al .')" style="margin-right:10px; margin-bottom:10px;">Approve</button>

						  								<button class="btn btn_purple" onclick="change_status(false,'. $row->id .','. $row->employee_id .','. $row->type_of_leave_id .','. $row->total_days .','. $row->remaining_al .')" style="margin-bottom:10px;">Reject</button>
						  								</td>';
						  					}
						  					else{
						  						echo '<td><button class="btn btn_purple" onclick="withdraw_leave('. $row->id .','. $row->employee_id .','. $row->total_days .','. $row->type_of_leave_id .','. $row->status.')" style="margin-bottom:10px;">Withdraw</button></td>';
						  					}
						  				}
						  				echo '</tr>';
						  			}
								?>
							</tbody>
						</table>
						<div style="font-size: 2.4rem;padding: 0; margin: 7px 0 14px 0;">Calendar</div>
						<div id="calender_container"></div>
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
									<!-- <th class="text-center">Days left (Before Apply)</th>
									<th class="text-center">Days left (After Approve)</th> -->
									<th colspan="2" class="text-center">Remaining Annual Leave</th>
									<th rowspan="2" class="text-center">Status</th>
									<th rowspan="2" class="text-center">Status updated by</th>
									<!-- <th class="text-center">Leave Status</th> -->
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
						  				// echo '<td>'.$row->remaining_al.'</td>';
						  				// echo '<td>'.$row->days_left_before.'</td>';
						  				// echo '<td>'.$row->days_left_after.'</td>';
						  				// echo '<td>'. $row->status.'</td>';
						  				// echo '<td>'. form_dropdown('leave_status', $action_list, $row->status, 'onchange="change_status(this)"') .'</td>';
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

<!-- <div class="row datatables-header form-inline">
				<div class="col-sm-12 col-md-12">
					<div class="dataTables_filter" id="datatable-default_filter">
						<input style="width: 45%;" aria-controls="datatable-default" placeholder="Search" id="search"  name="search" value="<?=$_POST['search']?$_POST['search']:'';?>" class="form-control" type="search">
							<input type="submit" class="btn btn_purple" value="Search"/>
							<a href="Interview" class="btn btn_purple">Show All Interview</a>
						<?= form_close();?>
					</div>
				</div>
				<div id="buttonclick" style="display:block;padding-top:10px;table-layout: fixed;width:100%">
					

					<hr/>
					<div style="text-align: center;">
						<h3>History</h3>
					</div>
					
					
					<hr>
				</div>
			</div> -->

<!-- Modal -->
<!-- <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    	<div class="modal-header">
          	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          	<span aria-hidden="true">&times;</span>
	    	</button>
          	<h4 class="modal-title">Send Offer Letter</h4>
        </div>
      <div class="modal-body"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn_purple">Send</button>
      </div>
    </div>
  </div>
</div> -->

<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>
<script>
	var base_url = '<?php echo base_url(); ?>';
	// var calender_list = JSON.parse('<?php echo json_encode($calender_list) ?>');
	var calender_list = <?php echo json_encode(isset($calender_list)?$calender_list:FALSE) ?>;
	var holiday_list = <?php echo json_encode(isset($holiday_list)?$holiday_list:"") ?>;
	var index_tab_aktif;

	$(document).on('click',".check_state",function(){
		index_tab_aktif = $(this).data("information");
		if(index_tab_aktif == "leave")
		{
			$(".create_leave").show();
		}
		else
		{
			$(".create_leave").hide();
			if(index_tab_aktif == "pending")
			{
				$('#calender_container').fullCalendar( 'rerenderEvents' );
			}
		}
	});

	var calender_event = [];
	
	if(calender_list.length > 0)
	{	
		for(var y = 0; y < calender_list.length; y++)
		{
			calender_event.push({title: calender_list[y]['employee_name'], start: moment(calender_list[y]["start_date"]).format('YYYY-MM-DD'), end: moment(calender_list[y]["end_date"], 'YYYY-MM-DD').add(1, 'days')});
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
			        // font-size: large;
			    }
			},

			editable: false,
			droppable: false,
			displayEventTime: false,
		  	//defaultDate: '2019-01-12',
			navLinks: true, // can click day/week names to navigate views
			eventLimit: true, // allow "more" link when too many events
			events: calender_event
			//[
			    // {
			    //   title: 'All Day Event',
			    //   start: '2019-01-01',
			    // },
			    // {
			    //   title: 'Long Event',
			    //   start: '2019-01-07',
			    //   end: '2019-01-10'
			    // },
			    // {
			    //   id: 999,
			    //   title: 'Repeating Event',
			    //   start: '2019-01-09T16:00:00'
			    // },
			    // {
			    //   id: 999,
			    //   title: 'Repeating Event',
			    //   start: '2019-01-16T16:00:00'
			    // },
			    // {
			    //   title: 'Conference',
			    //   start: '2019-01-11',
			    //   end: '2019-01-13'
			    // },
			    // {
			    //   title: 'Meeting',
			    //   start: '2019-01-12T10:30:00',
			    //   end: '2019-01-12T12:30:00'
			    // },
			    // {
			    //   title: 'Lunch',
			    //   start: '2019-01-12T12:00:00'
			    // },
			    // {
			    //   title: 'Meeting',
			    //   start: '2019-01-12T14:30:00'
			    // },
			    // {
			    //   title: 'Happy Hour',
			    //   start: '2019-01-12T17:30:00'
			    // },
			    // {
			    //   title: 'Dinner',
			    //   start: '2019-01-12T20:00:00'
			    // },
			    // {
			    //   title: 'Birthday Party',
			    //   start: '2019-01-13T07:00:00'
			    // },
			    // {
			    //   title: 'Click for Google',
			    //   url: 'http://google.com/',
			    //   start: '2019-01-28'
			    // }
		  	//]
		});

		// FIX INPUTS TO BOOTSTRAP VERSIONS
		var $calendarButtons = $calendar.find('.fc-toolbar > .fc-right > button');
		//console.log($calendarButtons);
		// $calendarButtons
		// 	.filter('.fc-button-prev, .fc-button-today, .fc-button-next')
		// 		.wrapAll('<div class="btn-group mt-sm mr-md mb-sm ml-sm"></div>')
		// 		.parent()
		// 		.after('<br class="hidden"/>');

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

			    				window.location = base_url + "leave/index";
			    			}
			    		} 
			    	);
	        	}
	        }
	    })

    }

	// function confirmationOL(id){
	// 	$.post("./offer_letter/sendOL_NewEmployee", { 'id': id }, function(data, status){
	// 		$('.modal-body').empty();
 //            $('.modal-body').prepend(data);
 //            $('#exampleModal').modal('show');
 //            // $('#exampleModal').show();
 //        });
	// }

	// function change_status(element){
	// 	console.log($(element));

	// 	$.ajax({
	//         type: "POST",
	//         url: "<?php echo site_url('leave/change_status_leave'); ?>",
	//         data: { 'leave_status': $(element).val() },
	//         dataType: "html",
	//         success: function(data){
	//         	var response = JSON.parse(data);

	//         	console.log(response);

	//         	if(response.result){
	//         		$('input[name=leave_id]').val(response.data.id);
	//         	}
	//         },
	//         // error: function() { alert("Error posting feed."); }
	//    });

	// 	// $(element).parent().parent().remove();
	// }

	function change_status(isApprove, leave_id, employee_id, type_of_leave_id, leave_days, remain_leave){
		//var selected_length = $("input[name='select_leave']:checked").length;
  		//console.log(leave_id);
  		//if(selected_length > 0){
	        if(isApprove){

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

								$.post("<?php echo site_url('leave/change_status'); ?>", { leave_id: leave_id, is_approve: 1, employee_id: employee_id, type_of_leave_id: type_of_leave_id }, function (data, status)
									{
					    				if(status)
					    				{
					    					$("#loadingmessage").hide();
					    					window.location = base_url + "leave/index";
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
			        		//$.each($("input[name='select_leave']:checked"), function(){  
			        			// var leave_id = $(this).parent().parent().find('.leave_id').val();   
			        			// var employee_id = $(this).parent().parent().find('.employee_id').val();
			        			// var type_of_leave_id = $(this).parent().parent().find('.type_of_leave_id').val();    
			        			$("#loadingmessage").show();
					            
					            $.post("<?php echo site_url('leave/change_status'); ?>", { leave_id: leave_id, is_approve: 0, employee_id: employee_id, type_of_leave_id: type_of_leave_id, reason: result}, function (data, status)
					            	{
						    			if(status)
						    			{
						    				$("#loadingmessage").hide();
						    				
						    				window.location = base_url + "leave/index";
						    			}
						    		} 
						    	);
					        //});
						}
			        }
			    })
	   //      	if(confirm("Confirm to REJECT the selected leave?")){
	   //      		$.each($("input[name='select_leave']:checked"), function(){  
	   //      			var leave_id = $(this).parent().parent().find('.leave_id').val();   
	   //      			var employee_id = $(this).parent().parent().find('.employee_id').val();    
			            
			 //            $.post("<?php echo site_url('leave/change_status'); ?>", { leave_id: leave_id, is_approve: 0, employee_id: employee_id }, function (data, status){
				//     			if(status){
				//     				window.location = base_url + "leave/index";
				//     			}
				//     		} 
				//     	);
			 //        });
				// }
	        }
	    // }
	    // else{
	    // 	alert("No leave is selected.");
	    // }
	}
</script>