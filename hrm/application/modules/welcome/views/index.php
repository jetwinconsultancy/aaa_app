<script src="<?= base_url() ?>node_modules/bootbox/bootbox.min.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>application/css/plugin/toastr.min.css" />
<script src="<?= base_url() ?>application/js/toastr.min.js"></script>

<style>
	.test{
	    white-space: nowrap;
  		overflow: hidden;
  		text-overflow: ellipsis;
	}
</style>

<section role="main" class="content_section" style="margin-left:0;">

	<!-- 1 -->
		<div class="col-lg-6 col-xs-12 header_between_page">		
			<section class="panel">
				<header class="panel-heading">
					<div class="panel-actions">
						<a class="panel-action panel-action-toggle" data-panel-toggle></a>
						<!-- <a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a> -->
					</div>
			
					<h2 class="panel-title">Jobs Due</h2>
				</header>
				<!-- <div id="staff_leave_pending"> -->
					<div class="panel-body" style="height:250px;overflow:auto;">	
						<div class="table-responsive">
							<table class="table table-striped mb-none">
								<thead>
									<tr>
										<th>No.</th>
										<th>Assignment Code</th>
										<th>Client Name</th>
										<th>Due Date</th>
									</tr>
								</thead>
								<tbody >
									<?php
										$i = 1;
										$top10 = 1;
										foreach($jobs_due_list as $jobDue){
											if(date("Y-m-d") >= date("Y-m-d",strtotime($jobDue->expected_completion_date))){
												echo '<tr style="color:red">';
											}else if(date("Y-m-d", strtotime("+5 day")) >= date("Y-m-d",strtotime($jobDue->expected_completion_date))){
												echo '<tr style="font-weight:bold">';
											}
											else{
												$top10++;
												echo '<tr>';
											}
											echo '<td>'.$i.'</td>';
											echo '<td>'.$jobDue->assignment_id.'</td>';
											echo '<td>'.$jobDue->client_name.'</td>';
											echo '<td>'.date('d F Y', strtotime($jobDue->expected_completion_date)).'</td>';
											echo '</tr>';
											$i++;

											if($top10=='10'){
												break;
											}
										}
									?>
								</tbody>
							</table>
						</div>
					</div>
				<!-- <div> -->
			</section>
		</div>

	<!-- FOR JAMES & FELICIA ONLY -->
	<?php if($user == 78 || $user == 107) { ?>
		<div class="col-lg-6 col-xs-12 header_between_page">		
			<section class="panel">
				<header class="panel-heading">
					<div class="panel-actions">
						<a class="panel-action panel-action-toggle" data-panel-toggle></a>
						<!-- <a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a> -->
					</div>
			
					<h2 class="panel-title">Team Member On / Coming Leave</h2>
				</header>
				<!-- <div id="staff_leave_pending"> -->
					<div class="panel-body" style="height:250px;overflow:auto;">	
						<div class="table-responsive">
							<table class="table table-striped mb-none">
								<thead>
									<tr>
										<th>No.</th>
										<th>Name</th>
										<th>Start Date</th>
										<th>End Date</th>
										<th>Total Day(s)</th>
									</tr>
								</thead>
								<tbody >
									<?php
										$i = 1;
										foreach($member_on_leave_list as $onleave){

											if(date("Y-m-d") >= date("Y-m-d",strtotime($onleave->start_date))){
												echo '<tr style="font-weight:bold">';
											}
											else{
												$top10++;
												echo '<tr>';
											}

											echo '<td>'.$i.'</td>';
											echo '<td>'.$onleave->employee_name.'</td>';
											echo '<td>'.date('d F Y', strtotime($onleave->start_date)).'</td>';
											echo '<td>'.date('d F Y', strtotime($onleave->end_date)).'</td>';
											echo '<td>'.$onleave->total_days.'</td>';
											echo '</tr>';
											$i++;
										}
									?>
								</tbody>
							</table>
						</div>
					</div>
				<!-- <div> -->
			</section>
		</div>
	<?php } ?>

	<!-- 2 -->
	<?php if($Admin || $Manager) { ?>
		<div class="col-lg-6 col-xs-12 header_between_page">		
			<section class="panel">
				<header class="panel-heading">
					<div class="panel-actions">
						<a class="panel-action panel-action-toggle" data-panel-toggle></a>
						<!-- <a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a> -->
					</div>
			
					<h2 class="panel-title">Staff On Leave</h2>
				</header>
				<!-- <div id="staff_leave_pending"> -->
					<div class="panel-body" style="height:250px;overflow:auto;">	
						<div class="table-responsive">
							<table class="table table-striped mb-none">
								<thead>
									<tr>
										<th>No.</th>
										<th>Name</th>
										<th>Leave</th>
										<th>Start Date</th>
										<th>End Date</th>
										<th>Total Day(s)</th>
									</tr>
								</thead>
								<tbody >
									<?php
										$i = 1;
										foreach($on_leave_list as $onleave){
											echo '<tr>';
											echo '<td>'.$i.'</td>';
											echo '<td>'.$onleave->employee_name.'</td>';

											// if($onleave->type_of_leave_id === '1'){
											// 	echo '<td>AL</td>';
											// }else if($onleave->type_of_leave_id === '2'){
											// 	echo '<td>MC</td>';
											// }else if($onleave->type_of_leave_id === '3'){
											// 	echo '<td>HL</td>';
											// }

											echo '<td>'.$onleave->leave_name.'</td>';

											echo '<td>'.date('d F Y', strtotime($onleave->start_date)).'</td>';
											echo '<td>'.date('d F Y', strtotime($onleave->end_date)).'</td>';
											echo '<td>'.$onleave->total_days.'</td>';
											echo '</tr>';
											$i++;
										}
									?>
								</tbody>
							</table>
						</div>
					</div>
				<!-- <div> -->
			</section>
		</div>
	<?php } ?>

	<!-- 3 -->
	<?php if($Admin || $Manager) { ?>
		<div class="col-lg-6 col-xs-12 header_between_page">		
			<section class="panel">
				<header class="panel-heading">
					<div class="panel-actions">
						<a class="panel-action panel-action-toggle" data-panel-toggle></a>
						<!-- <a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a> -->
					</div>
			
					<h2 class="panel-title">Workpass Expiry Date</h2>
				</header>
				<!-- <div id="staff_leave_pending"> -->
					<div class="panel-body" style="height:250px;overflow:auto;">	
						<div class="table-responsive">
							<table class="table table-striped mb-none">
								<thead>
									<tr>
										<th>No.</th>
										<th>Name</th>
										<th>Workpass</th>
										<th>Expiry Date</th>
										<th>Remaining Day(s)</th>
									</tr>
								</thead>
								<tbody >
									<?php
										$i = 1;
										foreach($pass_expiry_list as $passExpiry){
											echo '<tr>';
											echo '<td>'.$i.'</td>';
											echo '<td>'.$passExpiry->name.'</td>';
											echo '<td>'.$passExpiry->pass.'</td>';
											echo '<td>'.date('d F Y', strtotime($passExpiry->expiry_date)).'</td>';
											echo '<td>'.$passExpiry->remaining_days.'</td>';
											echo '</tr>';
											$i++;
										}
									?>
								</tbody>
							</table>
						</div>
					</div>
				<!-- <div> -->
			</section>
		</div>
	<?php } ?>

	<!-- 4 -->
	<?php if($Admin || $Manager) { ?>
		<div class="col-lg-6 col-xs-12 header_between_page">		
			<section class="panel">
				<header class="panel-heading">
					<div class="panel-actions">
						<a class="panel-action panel-action-toggle" data-panel-toggle></a>
						<!-- <a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a> -->
					</div>
			
					<h2 class="panel-title">Staff Leave Pending</h2>
				</header>
				<!-- <div id="staff_leave_pending"> -->
					<div class="panel-body" style="height:250px;overflow:auto;">	
						<div class="table-responsive">
							<table class="table table-striped mb-none">
								<thead>
									<tr>
<!-- 										<th>No.</th> -->
										<th>Name</th>
										<th>Leave</th>
										<th>Start Date</th>
										<th>End Date</th>
										<th>Total Day(s)</th>
										<th></th>
									</tr>
								</thead>
								<tbody >
									<?php
										$i = 1;
										foreach($leave_pending_list as $leave){
											echo '<tr>';
											// echo '<td>'.$i.'</td>';
											echo '<input type="hidden" class="leave_id" value="'. $leave->id .'">';
						  					echo '<input type="hidden" class="employee_id" value="'. $leave->employee_id .'">';
						  					echo '<input type="hidden" class="type_of_leave_id" value="'. $leave->type_of_leave_id .'">';
											echo '<td>'.$leave->employee_name.'</td>';

											// if($leave->type_of_leave_id === '1'){
											// 	echo '<td>AL</td>';
											// }else if($leave->type_of_leave_id === '2'){
											// 	echo '<td>MC</td>';
											// }else if($leave->type_of_leave_id === '3'){
											// 	echo '<td>HL</td>';
											// }

											echo '<td>'.$leave->leave_name.'</td>';

											echo '<td>'.date('d-m-Y', strtotime($leave->start_date)).'</td>';
											echo '<td>'.date('d-m-Y', strtotime($leave->end_date)).'</td>';
											echo '<td>'.$leave->total_days.'</td>';
											echo '<td>
												  <div>
												  <button title="APPROVE" class="btn btn_purple" onclick="change_status(true,'. $leave->id .','. $leave->employee_id .','. $leave->type_of_leave_id .','. $leave->total_days .','. $leave->remaining_al .')"><i class="fas fa-check-circle"></i></button>
												  <button title="REJECT" class="btn btn_purple" onclick="change_status(false,'. $leave->id .','. $leave->employee_id .','. $leave->type_of_leave_id .','. $leave->total_days .','. $leave->remaining_al .')" ><i class="fas fa-times-circle"></i></button>
												  </div>
												  </td>';
											echo '</tr>';
											$i++;
										}
									?>
								</tbody>
							</table>
						</div>
					</div>
				<!-- <div> -->
			</section>
		</div>
	<?php } ?>

	<!-- 5 -->
	<?php if($Admin || $Manager) { ?>
		<div class="col-lg-6 col-xs-12 header_between_page">		
			<section class="panel">
				<header class="panel-heading">
					<div class="panel-actions">
						<a class="panel-action panel-action-toggle" data-panel-toggle></a>
						<!-- <a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a> -->
					</div>
			
					<h2 class="panel-title">Expected Completion Date Changed</h2>
				</header>
				<!-- <div id="staff_leave_pending"> -->
					<div class="panel-body" style="height:250px;overflow:auto;">	
						<div class="table-responsive">
							<table class="table table-striped mb-none">
								<thead>
									<tr>
										<th>Assignment Code</th>
										<th>Client Name</th>
										<th>From</th>
										<th>To</th>
										<th>Reason</th>
									</tr>
								</thead>
								<tbody >
									<?php
										$i = 1;
										foreach($ECD_list as $list){
											echo '<tr>';
											echo '<td class="test">'.$list->assignment_id.'</td>';
											echo '<td class="test">'.$list->client_name.'</td>';
											echo '<td class="test">'.date('d F Y', strtotime($list->change_from)).'</td>';
											echo '<td class="test">'.date('d F Y', strtotime($list->change_to)).'</td>';
											echo '<td>'.$list->reason.'</td>';
											echo '</tr>';
											$i++;
										}
									?>
								</tbody>
							</table>
						</div>
					</div>
				<!-- <div> -->
			</section>
		</div>
	<?php } ?>

</section>

<script>

var acknowledgement = <?php echo json_encode($acknowledgement) ?>;

if(acknowledgement == "normal")
{
	bootbox.dialog({
		title: 'Notice',
		closeButton: false,
        message: "<div style='text-align: justify;'><p>Over the past few months we have observed at times slow-down in jobs delivery and at best below par work quality for some of us. Without taking away the credit that is due to our hardworking co-workers, we noticed the performance of a small group have been outstanding. We want to commend those who have contributed exceptionally to the organization in this trying moment and we want to take this opportunity to demand for immediate improvements for those who have fallen short.</p><p>As our organization is moving to its testing season of the year, we must work harder to ensure that deadline is met and to the extent that we work even smarter to be more productive. In the past we might have been working and living within our comfort zone, without the need to stretch to progress. This notion unfortunately, has evolved to be an unpleasant norm where some of us no longer has that drive within us to move forward. That is not right in the sense of approach and is eternally wrong in the sense of work ethic. Such demeanor not only costs additional financial burden to the organization, it also put on additional burden to our fellow more hardworking co-workers who have to cover more workloads of those who are less productive.</p><p>This message should not come as a surprise as many of our hardworking co-workers have not received a deserving appreciation whereas the unproductive co-workers have gone unchallenged. Such situation creates division and is no longer tenable for any of us to endure. The management hears your voice and understands your need to be recognized and appreciated. In response to these concerns, the management has decided to step up our review process and bring it forward to recognize each of your work, good or otherwise.</p><p>With this notice, the organization would like to offer some token of our appreciation to our hardworking co-workers. At the same time, we also want to see an immediate improvement at least through the deadlines of two weeks from the date of our meeting with you which will be documented separately. Failure to meet the work quality required will be subject to another hearing and disciplinary action up to termination.</p><p>To this end, I would like to extend my gratitude to everyone who have endured this testing time with great fortitude and incredible attitude.</p></div>",
        buttons: {
            understood: {
            	className: 'btn_purple',
                label: 'I have read and understood.',
                callback: function(){
	        		$.ajax({
						url: "welcome/update_acknowledgement",
						type: "POST",
						data: { 'understood': 1 },
						dataType: 'json',
						success: function (response,data) {
							
						}
				    })            
	            }
            }
        }
    });
}
else if(acknowledgement == "warning")
{
	bootbox.dialog({
		title: 'Warning',
		closeButton: false,
        message: "<div style='text-align: justify;'><p style='color:red;'>Please acknowledge if you have read and understood this notice. Stern action will be taken for failure in acknowledging this.</p><p>Over the past few months we have observed at times slow-down in jobs delivery and at best below par work quality for some of us. Without taking away the credit that is due to our hardworking co-workers, we noticed the performance of a small group have been outstanding. We want to commend those who have contributed exceptionally to the organization in this trying moment and we want to take this opportunity to demand for immediate improvements for those who have fallen short.</p><p>As our organization is moving to its testing season of the year, we must work harder to ensure that deadline is met and to the extent that we work even smarter to be more productive. In the past we might have been working and living within our comfort zone, without the need to stretch to progress. This notion unfortunately, has evolved to be an unpleasant norm where some of us no longer has that drive within us to move forward. That is not right in the sense of approach and is eternally wrong in the sense of work ethic. Such demeanor not only costs additional financial burden to the organization, it also put on additional burden to our fellow more hardworking co-workers who have to cover more workloads of those who are less productive.</p><p>This message should not come as a surprise as many of our hardworking co-workers have not received a deserving appreciation whereas the unproductive co-workers have gone unchallenged. Such situation creates division and is no longer tenable for any of us to endure. The management hears your voice and understands your need to be recognized and appreciated. In response to these concerns, the management has decided to step up our review process and bring it forward to recognize each of your work, good or otherwise.</p><p>With this notice, the organization would like to offer some token of our appreciation to our hardworking co-workers. At the same time, we also want to see an immediate improvement at least through the deadlines of two weeks from the date of our meeting with you which will be documented separately. Failure to meet the work quality required will be subject to another hearing and disciplinary action up to termination.</p><p>To this end, I would like to extend my gratitude to everyone who have endured this testing time with great fortitude and incredible attitude.</p></div>",
        buttons: {
            understood: {
            	className: 'btn_purple',
                label: 'I have read and understood.',
                callback: function(){
	        		$.ajax({
						url: "welcome/update_acknowledgement",
						type: "POST",
						data: { 'understood': 1 },
						dataType: 'json',
						success: function (response,data) {
							
						}
				    })            
	            }
            }
        }
    });
}

// WORKPASS EXPIRE DATE CHECK
$(document).ready(function (){

	$.ajax({
       type: "POST",
       url: "welcome/workpass_expire_date_check",
       success: function(data)
       {	
       		if(data!='[]')
       		{
       			var today = new Date();
				var day = today.getDay();

	       		var object = (JSON.parse(data));
	       		if(object[0]["workpass"]!="Not Applicable" && object[0]["pass_expire"] == null)
	       		{
	       			if(day == 1){
	       				bootbox.confirm({
					        message: "<h4>Employee Info - Empty Workpass Expiry Date</h4><h5>- Please Remember to Update Your Workpass Expiry Date !</h5>",
					        closeButton: false,
					        buttons: {
					            confirm: {
					                label: 'OK',
					                className: 'btn_purple'
					            },
					            cancel: {
					                label: 'No',
					                className: 'hidden'
					            }
					        },
					        callback: function (result){

					        }
					    })
	       			}
	       		}
	       	}
       }
   	});

});


function change_status(isApprove, leave_id, employee_id, type_of_leave_id, leave_days, remain_leave){
		//var selected_length = $("input[name='select_leave']:checked").length;
  		//console.log(leave_id);
  		//if(selected_length > 0){
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
				        		$.post("<?php echo site_url('leave/change_status'); ?>", { leave_id: leave_id, is_approve: 1, employee_id: employee_id, type_of_leave_id: type_of_leave_id }, function (data, status){
							    			if(status){
							    				location.reload();
							    			}
							    });
							}
				        }
				    })
	        	}
	        	else
	        	{
	        		toastr.error('Annual Leave Over The Limit (-20)', 'Warning');
	        	}

	        }
	        else
	        {
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
			        		$.post("<?php echo site_url('leave/change_status'); ?>", { leave_id: leave_id, is_approve: 0, employee_id: employee_id, type_of_leave_id: type_of_leave_id, reason: result}, function (data, status){
						    			if(status){
						    				location.reload();
						    			}
						    });
						}
			        }
			    })
	        }
	}

// Panels
(function( $ ) {

	$(function() {
		$('.panel')
			.on( 'panel:toggle', function() {
				var $this,
					direction;

				$this = $(this);
				direction = $this.hasClass( 'panel-collapsed' ) ? 'Down' : 'Up';

				$this.find('.panel-body, .panel-footer')[ 'slide' + direction ]( 200, function() {
					$this[ (direction === 'Up' ? 'add' : 'remove') + 'Class' ]( 'panel-collapsed' )
				});
			})
			.on( 'panel:dismiss', function() {
				var $this = $(this);

				if ( !!( $this.parent('div').attr('class') || '' ).match( /col-(xs|sm|md|lg)/g ) && $this.siblings().length === 0 ) {
					$row = $this.closest('.row');
					$this.parent('div').remove();
					if ( $row.children().length === 0 ) {
						$row.remove();
					}
				} else {
					$this.remove();
				}
			})
			.on( 'click', '[data-panel-toggle]', function( e ) {
				e.preventDefault();
				$(this).closest('.panel').trigger( 'panel:toggle' );
			})
			.on( 'click', '[data-panel-dismiss]', function( e ) {
				e.preventDefault();
				$(this).closest('.panel').trigger( 'panel:dismiss' );
			})
			/* Deprecated */
			.on( 'click', '.panel-actions a.fa-caret-up', function( e ) {
				e.preventDefault();
				var $this = $( this );

				$this
					.removeClass( 'fa-caret-up' )
					.addClass( 'fa-caret-down' );

				$this.closest('.panel').trigger( 'panel:toggle' );
			})
			.on( 'click', '.panel-actions a.fa-caret-down', function( e ) {
				e.preventDefault();
				var $this = $( this );

				$this
					.removeClass( 'fa-caret-down' )
					.addClass( 'fa-caret-up' );

				$this.closest('.panel').trigger( 'panel:toggle' );
			})
			.on( 'click', '.panel-actions a.fa-times', function( e ) {
				e.preventDefault();
				var $this = $( this );

				$this.closest('.panel').trigger( 'panel:dismiss' );
			});
	});

})( jQuery );

</script>