<!-- <?php
	$user_arrays = [];
	// print_r($users);
	// foreach($users as $u)
	// {
	// 	$user_arrays[$u->id] = ucfirst($u->first_name);
	// }					
	?> -->				<div class="header_between_all_section">
						<?php if ((!$Individual && $Individual == true) || (!$Individual && $Individual == null && !$Client)) {?>
							<div class="col-lg-6 col-xs-12 header_between_page">		
								<section class="panel">
									<header class="panel-heading">
										<div class="panel-actions">
											<a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
											<!-- <a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a> -->
										</div>
								
										<h2 class="panel-title">Total Billing Info</h2>
									</header>
									<div class="panel-body" style="height:250px;">	
										<div class="row text-center">
											<div class="col-xs-12 col-md-12">
												<canvas id="myChart" style="display: block; width: 100% !important; height: 240px !important;"></canvas>
											</div>
											<!-- width="180" height="75" -->
											<!-- <div class="col-xs-6 col-md-6">
												<div class="gauge-chart">
													<canvas id="gaugeBasic" width="180" height="110" data-plugin-options='{ "value": <?= $uncomplete_task ?>, "maxValue": <?= $complete_task ?> }'></canvas>
													<strong>Uncompleted Task</strong>
													<label id="gaugeBasicTextfield"></label>
												</div>
											</div>
											<div class="col-xs-6 col-md-6">
												<div class="gauge-chart">
													<canvas id="gaugeAlternative" width="180" height="110" data-plugin-options='{ "value": 1650, "maxValue": 2000 }'></canvas>
													<strong>Unpaid Bill</strong>
													<div class="col-xs-4 col-xs-offset-4" style="min-width:120px;" >
													<label style="float:left;">$</label><label id="gaugeAlternativeTextfield" style="float:left;"></label>
													</div>
												</div>
											</div> -->
										</div>
									</div>
								</section>
							</div>
						<?php } ?>
						<?php if ((!$Individual && $Individual == true) || (!$Individual && $Individual == null && !$Client)) {?>
							<div class="col-lg-6 col-xs-12 header_between_page">		
								<section class="panel">
									<header class="panel-heading">
										<div class="panel-actions">
											<a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
											<!-- <a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a> -->
										</div>
								
										<h2 class="panel-title">Client Billing Info</h2>
									</header>
									<div class="panel-body" style="height:250px;overflow:auto;">	
										<div class="table-responsive">
											<table class="table table-striped mb-none">
												<thead>
													<tr>
														<th>No.</th>
														<th>Company Name</th>
														<!-- <th>Invoice Number</th> -->
														<th>Total Unpaid</th>
													</tr>
												</thead>
												<tbody >
													<?php
														$i=1;
														if($unpaid_billings != false)
														{
															for($n = 0; $n < count($unpaid_billings); $n++)
															{

																echo '<tr>';
																echo '<td>'.$i.'</td>';
																echo '<td><a href="'.site_url("billings").'" class="">'.$unpaid_billings[$n]->company_name.'</a></td>';
																// echo '<td><a href="'.site_url("masterclient/edit/".$unpaid_billings[$n]->client_id."/setup").'" class="">'.$unpaid_billings[$n]->company_name.'</a></td>';
																// echo '<td><a href="'.site_url("billings/edit_bill/".$unpaid_billings[$n]->id."").'" class="">'.$unpaid_billings[$n]->invoice_no.'</a></td>';
																echo '<td>'.$unpaid_billings[$n]->currency_name.number_format($unpaid_billings[$n]->total_unpaid_amount,2).'</td>';
																echo '</tr>';

																$i++;
															}
														}
													?>
												</tbody>
											</table>
										</div>
									</div>
								</section>
							</div>
						<?php } ?>
						<?php if ((!$Individual && $Individual == true) || (!$Individual && $Individual == null && !$Client)) {?>
							<div class="col-lg-6 col-xs-12 header_between_page">		
								<section class="panel">
									<header class="panel-heading">
										<div class="panel-actions">
											<a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
											<!-- <a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a> -->
										</div>
								
										<h2 class="panel-title">Services Pending</h2>
									</header>
									<div class="panel-body" style="height:250px;overflow:auto;">	
										<div class="table-responsive">
											<table class="table table-striped mb-none">
												<thead>
													<tr>
														<th>No.</th>
														<th>Company Name</th>
														<th>Transaction Type</th>
														<th>Create Date</th>
													</tr>
												</thead>
												<tbody >
													<?php
														$i=1;
														if($transaction != false)
														{
															for($n = 0; $n < count($transaction); $n++)
															{
																if($transaction[$n]->status == 1)
																{
																	echo '<tr>';
																	echo '<td>'.$i.'</td>';
																	echo '<td>'.($transaction[$n]->client_name != NULL ? $transaction[$n]->client_name : $transaction[$n]->company_name).'</td>';
																	echo '<td><a href="'.site_url("transaction/edit/".$transaction[$n]->id).'" style="cursor:pointer" data-toggle="tooltip" data-trigger="hover" data-original-title="Edit This Transaction">'.$transaction[$n]->transaction_task.'</a></td>';
																	echo '<td>'.date("d F Y",strtotime($transaction[$n]->created_at)).'</td>';
																	echo '</tr>';

																	$i++;
																}
															}
														}
													?>
												</tbody>
											</table>
										</div>
									</div>
								</section>
							</div>
						
							<!-- <div class="col-lg-6 col-xs-12 header_between_page">
								<section class="panel">
									<header class="panel-heading">
										<div class="panel-actions">
											<a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
										</div>
										<h2 class="panel-title">FYE</h2>
									</header>
									<div class="panel-body" style="height:250px;overflow:auto;">
										<div class="table-responsive">
											<table class="table table-striped mb-none">
												<thead>
													<tr>
														<th>No.</th>
														<th>Company Name</th>
														<th>Year End</th>
														 <th>Deadline (days)</th>
													</tr>
												</thead>
												<tbody >
													<?php
														//echo json_encode($due_date);
														$i=1;
														if(isset($fye))
														{
															for($p = 0; $p < count($fye); $p++)
															{
																$array = explode(' ', $fye[$p]["year_end"]);
														        $year = $array[2];
																$month = $array[1];
																$day = $array[0];
																
																if($fye[$p]["year_end"] != null && date('Y-m', strtotime($month.'-'.(int)$year)) == date('Y-m'))
																{
																	echo '<tr>';
																	echo '<td>'.$i.'</td>';
																	echo '<td><a href="'.site_url("masterclient/edit/".$fye[$p]["id"]."/filing").'" class="">'.ucfirst($fye[$p]["company_name"]).'</a></td>';
																	echo '<td>'.$fye[$p]["year_end"].'</td>';
																	// echo '<td>'.$due_date[$p]["days"].'</td></tr>';
																	$i++;
																}
															}
														}
													?>
												</tbody>
											</table>
										</div>
									</div>
								</section>
							</div> 
							<div class="col-lg-6 col-xs-12 header_between_page">
								<section class="panel">
									<header class="panel-heading">
										<div class="panel-actions">
											<a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
										</div>
										<h2 class="panel-title">AGM & AR</h2>
									</header>
									<div class="panel-body" style="height:250px;overflow:auto;">
										<div class="table-responsive">
											<table class="table table-striped mb-none">
												<thead>
													<tr>
														<th>No.</th>
														<th>Company Name</th>
														<th>AGM Due Date</th>
														<th>AR Due Date</th>
													</tr>
												</thead>
												<tbody >
													<?php
														//echo json_encode($agm);
														$i=1;
														if(isset($agm))
														{
															for($p = 0; $p < count($agm); $p++)
															{
																$array = explode(' ', $agm[$p]["due_date_201"]);
														        $year = $array[2];
																$month = $array[1];
																$day = $array[0];
																
																if($agm[$p]["due_date_201"] != null && date('Y-m', strtotime($month.'-'.(int)$year)) == date('Y-m'))
																{
																	echo '<tr>';
																	echo '<td>'.$i.'</td>';
																	echo '<td><a href="'.site_url("masterclient/edit/".$agm[$p]["client_id"]."/filing").'" class="">'.ucfirst($agm[$p]["company_name"]).'</a></td>';
																	echo '<td>'.$agm[$p]["due_date_201"].'</td>';
																	echo '<td>'.$agm[$p]["due_date_197"].'</td>';
																	
																	$i++;
																}
															}
														}
													?>
												</tbody>
											</table>
										</div>
									</div>
								</section>
							</div> -->
						<?php } ?>
						<!-- <?php if ($Admin && !$Individual) {?>
							<div class="col-lg-6 col-xs-12 header_between_page">		
								<section class="panel">
									<header class="panel-heading">
										<div class="panel-actions">
											<a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
											
										</div>
								
										<h2 class="panel-title">System Info</h2>
									</header>
									<div class="panel-body" style="height:250px;">	
										<div style="padding: 10px;">
											<span>Expiry Date:</span>
										</div>
										
									</div>
								</section>
							</div>
						<?php } ?> -->
						<!-- <?php if ((!$Individual && $Individual == true) || (!$Individual && $Individual == null && !$Client)) {?>
							<div class="col-lg-6 col-xs-12 header_between_page">		
								<section class="panel">
									<header class="panel-heading">
										<div class="panel-actions">
											<a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
										</div>
								
										<h2 class="panel-title">Document Pending</h2>
									</header>
									<div class="panel-body" style="height:250px;overflow:auto;">	
										<div class="table-responsive">
											<table class="table table-striped mb-none">
												<thead>
													<tr>
														<th>No.</th>
														<th>Company Name</th>
														<th>Document Pending</th>
													</tr>
												</thead>
												<tbody >
													<?php
														$i=1;
														for($n = 0; $n < count($pending_documents); $n++)
														{
															echo '<tr>';
															echo '<td>'.$i.'</td>';
															echo '<td>'.$pending_documents[$n]["company_name"].'</td>';
															if($pending_documents[$n]["received_on"] == null)
															{
																
																echo '<td><a href="'.site_url("documents/add_pending_document_file/".$pending_documents[$n]["id"]."").'" class="">'.ucfirst($pending_documents[$n]["document_name"]).'</a></td>';
															}
															else
															{
																
																echo '<td><a href="'.site_url("documents/edit_pending_document_file/".$pending_documents[$n]["id"]."").'" class="">'.ucfirst($pending_documents[$n]["document_name"]).'</a></td>';
															}
															echo '</tr>';
															$i++;
														}
													?>
												</tbody>
											</table>
										</div>
									</div>
								</section>
							</div>
						<?php } ?>
						
							<?php if ((!$Individual && $Individual == true) || (!$Individual && $Individual == null && !$Client)) {?>
								<div class="col-lg-6 col-xs-12 header_between_page">		
									<section class="panel">
										<header class="panel-heading">
											<div class="panel-actions">
												<a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
											</div>
									
											<h2 class="panel-title">Recent</h2>
										</header>
										<div class="panel-body" style="height:250px;overflow:auto;">
											<div class="table-responsive">
												<table class="table table-striped mb-none">
													<thead>
														<tr>
															<th>No.</th>
															<th>Company Name</th>
														</tr>
													</thead>
													<tbody >
														<?php
															$i=1;
															for($n = 0; $n < count($recent_add_company); $n++)
															{
																echo '<tr>';
																echo '<td>'.$i.'</td>';
																echo '<td><a href="'.site_url("masterclient/edit/".$recent_add_company[$n]["id"]."").'" class="">'.ucfirst($recent_add_company[$n]["company_name"]).'</a></td>';
																echo '</tr>';
																$i++;
															}
														?>
													</tbody>
												</table>
											</div>
										</div>
									</section>
								</div>
							<?php } ?> -->
							</div>
							<!-- <div id="calendar"></div> -->
					<!-- <div class="row"> -->
						<!-- <div class="col-md-6" style="width: 100%;">
							<section class="panel panel-transparent">
								<header class="panel-heading">
									<div class="panel-actions">
										
									</div>

									<h2 class="panel-title">To Do List</h2>
								</header>
								<div class="panel-body">
									
									<section class="panel">
										<div class="panel-body">
											<div class="row">
												<div class="col-md-9">
													<div id="calendar"></div>
												</div>
												<div class="col-md-3">

													<div id='external-events'>
														<div class="col-sm-12">
															<?php 
																$attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form', 'id' => 'form_task');
																echo form_open_multipart("welcome/save_task", $attrib);
																
															?>
															<h4>Task</h4>
															<div class="input-group mb-md">
																<span class="input-group-addon">
																	<i class="fa fa-tasks"></i>
																</span>
																<input type="text" id="task_todolist" name="task_todolist" class="form-control"/>
															</div>
															<h4>Deadline</h4>
															<div class="input-group mb-md">
																<span class="input-group-addon">
																	<i class="far fa-calendar-alt"></i>
																</span>
																<input type="text" id="date_todolist" name="date_todolist" class="form-control" data-plugin-datepicker data-date-format="dd/mm/yyyy" data-date-start-date="0d"/>
															</div>
															<h4>Add Assign To</h4>
															<div class="input-group mb-md">
																
																<select name="assign_to[]" multiple="multiple" data-plugin-multiselect class="form-control">
																	<?php
																		foreach($users as $u)
																		{
																			if($u->email != $this->session->userdata('email'))
																			{
																			echo '<option value="'.$u->id.'">'.$u->email.'</option>';
																			}
																		}
																	?>
																</select>
															</div>
															<div class="input-group mb-md">
																<label class="form-control" style="border:0px;background:none;box-shadow:none;"	><input  type="checkbox" name="urgent"/>&nbsp;Urgent</label>
															</div>
															<div class="input-group mb-md">
																<div class="col-md-6">
																	<input type="submit" id="btn_tambah" class="btn btn-primary" value="Save" />
																</div>
																<div class="col-md-6">
																	<input type="reset" id="btn_batal_edit_event" class="btn btn-default" value="Cancel" />
																</div>
															</div>
															<?= form_close(); ?>
														
														<hr />
														</div>
													</div>
												</div>
											</div>
										</div>
									</section>
								</div>
							</section>
						</div> -->
					
					
						<!-- <div class="col-xl-6 col-lg-12">
							<section class="panel">
								<header class="panel-heading panel-heading-transparent">
									<div class="panel-actions">
										
									</div>

									<h2 class="panel-title">Personal Activity</h2>
								</header>
								<div class="panel-body">
									<div class="timeline timeline-simple mt-xlg mb-md">
										<div class="tm-body">
											<div class="tm-title">
												<h3 class="h5 text-uppercase">October 2016</h3>
											</div>
											<ol class="tm-items">
												<li>
													<div class="tm-box">
														<p class="text-muted mb-none">Now (22/10/16)</p>
														<p>
															Creating <span class="text-primary">#awesome</span> document
														</p>
													</div>
												</li>
												<li>
													<div class="tm-box">
														<p class="text-muted mb-none">1 days ago.</p>
														<p>
															Update Profile
														</p>
														<div class="thumbnail-gallery hidden">
															<a class="img-thumbnail lightbox" href="assets/images/projects/project-4.jpg" data-plugin-options='{ "type":"image" }'>
																
																<span class="zoom">
																	<i class="fa fa-search"></i>
																</span>
															</a>
														</div>
													</div>
												</li>
											</ol>
										</div>
									</div>
								</div>
							</section>
						</div> -->
					<!-- </div> -->
					<!-- <div class="row">
						
					</div> -->
					<!-- end: page -->
				</section>
			</div>
		
<script>
	var acknowledgement = <?php echo json_encode($acknowledgement) ?>;

	$("#header_our_firm").removeClass("header_disabled");
	$("#header_manage_user").removeClass("header_disabled");
	$("#header_access_right").removeClass("header_disabled");
	$("#header_user_profile").removeClass("header_disabled");
	$("#header_backup").removeClass("header_disabled");
	$("#header_setting").removeClass("header_disabled");
	$("#header_dashboard").addClass("header_disabled");
	$("#header_client").removeClass("header_disabled");
	$("#header_person").removeClass("header_disabled");
	$("#header_document").removeClass("header_disabled");
	$("#header_report").removeClass("header_disabled");
	$("#header_billings").removeClass("header_disabled");

	$("#btn_add_assigned").on('click',function() {
		$a = $('#txt_assigned_user').val();
		$('#txt_assigned_user').val($a + $("#cmb_users").val());
		// alert($a + $("#cmb_users").val());
		
	});
	
	$("#btn_batal_edit_event").on('click',function() {
		$("#btn_tambah").val('Save');
		$("#btn_tambah").removeClass('btn-info');
	});
	$("#form_task").on('submit',function (e) {
        e.preventDefault();
		$.ajax({
            type: 'post',
            url: 'welcome/save_task',
            data: $(this).serialize(),
            success: function (data) {
              //console.log(data);
			  
				var event={id:1 , title: $("#task_todolist").val(), start:  $("#date_todolist").val()};
				$('#calendar').fullCalendar( 'renderEvent', event, true);
            }
          });
					var date = new Date();
					var d = date.getDate();
					var m = date.getMonth();
					var y = date.getFullYear();
		  
	});

	if(acknowledgement == "normal")
	{
		bootbox.dialog({
			title: 'Notice',
			closeButton: false,
            message: "<div style='text-align: justify;'><p>Over the past few months we have observed at times slow-down in jobs delivery and at best below par work quality for some of us. Without taking away the credit that is due to our hardworking co-workers, we noticed the performance of a small group have been outstanding. We want to commend those who have contributed exceptionally to the organization in this trying moment and we want to take this opportunity to demand for immediate improvements for those who have fallen short.</p><p>As our organization is moving to its testing season of the year, we must work harder to ensure that deadline is met and to the extent that we work even smarter to be more productive. In the past we might have been working and living within our comfort zone, without the need to stretch to progress. This notion unfortunately, has evolved to be an unpleasant norm where some of us no longer has that drive within us to move forward. That is not right in the sense of approach and is eternally wrong in the sense of work ethic. Such demeanor not only costs additional financial burden to the organization, it also put on additional burden to our fellow more hardworking co-workers who have to cover more workloads of those who are less productive.</p><p>This message should not come as a surprise as many of our hardworking co-workers have not received a deserving appreciation whereas the unproductive co-workers have gone unchallenged. Such situation creates division and is no longer tenable for any of us to endure. The management hears your voice and understands your need to be recognized and appreciated. In response to these concerns, the management has decided to step up our review process and bring it forward to recognize each of your work, good or otherwise.</p><p>With this notice, the organization would like to offer some token of our appreciation to our hardworking co-workers. At the same time, we also want to see an immediate improvement at least through the deadlines of two weeks from the date of our meeting with you which will be documented separately. Failure to meet the work quality required will be subject to another hearing and disciplinary action up to termination.</p><p>To this end, I would like to extend my gratitude to everyone who have endured this testing time with great fortitude and incredible attitude.</p></div>",
            buttons: {
                understood: {
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
</script>
<script> //Calendar
					/*
			Name: 			Pages / Calendar - Examples
			Written by: 	Okler Themes - (http://www.okler.net)
			Theme Version: 	1.4.1
			*/

			(function( $ ) {

				'use strict';

				var initCalendarDragNDrop = function() {
					$('#external-events div.external-event').each(function() {

						// create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
						// it doesn't need to have a start or end
						var eventObject = {
							title: $.trim($(this).text()) // use the element's text as the event title
						};

						// store the Event Object in the DOM element so we can get to it later
						$(this).data('eventObject', eventObject);

						// make the event draggable using jQuery UI
						$(this).draggable({
							zIndex: 999,
							revert: true,      // will cause the event to go back to its
							revertDuration: 0  //  original position after the drag
						});

					});
				};

				var initCalendar = function() {
					var $calendar = $('#calendar');
					var date = new Date();
					var d = date.getDate();
					var m = date.getMonth();
					var y = date.getFullYear();

					$calendar.fullCalendar({
						header: {
							left: 'title',
							right: 'prev,today,next,basicDay,basicWeek,month'
						},

						timeFormat: 'h:mm',

						titleFormat: {
							month: 'MMMM YYYY',      // September 2009
							week: "MMM D YYYY",      // Sep 13 2009
							day: 'dddd, MMM D, YYYY' // Tuesday, Sep 8, 2009
						},

						themeButtonIcons: {
							prev: 'fa fa-caret-left',
							next: 'fa fa-caret-right',
						},

						editable: true,
						droppable: true, // this allows things to be dropped onto the calendar !!!
						drop: function(date, allDay) { // this function is called when something is dropped
							var $externalEvent = $(this);
							// retrieve the dropped element's stored Event Object
							var originalEventObject = $externalEvent.data('eventObject');

							// we need to copy it, so that multiple events don't have a reference to the same object
							var copiedEventObject = $.extend({}, originalEventObject);

							// assign it the date that was reported
							copiedEventObject.start = date;
							copiedEventObject.allDay = allDay;
							copiedEventObject.className = $externalEvent.attr('data-event-class');

							// render the event on the calendar
							// the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
							// $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

							// is the "remove after drop" checkbox checked?
							if ($('#RemoveAfterDrop').is(':checked')) {
								// if so, remove the element from the "Draggable Events" list
								$(this).remove();
							}

						},
						events: [
							<?php
								if(isset($user_task))
								{
									foreach($user_task as $us)
									{
										echo '{';
										echo '	title  : "'.$us->task.'",';
										echo '	start  : "'.$us->task_date.'",';
										echo '},';
									}
								}
							?>
							/*
							{
								title: 'All Day Event',
								start: new Date(y, m, 1)
							},
							{
								title: 'Long Event',
								start: new Date(y, m, d-5),
								end: new Date(y, m, d-2)
							},
							{
								id: 999,
								title: 'Repeating Event',
								start: new Date(y, m, d-3, 16, 0),
								allDay: false
							},
							{
								id: 999,
								title: 'Repeating Event',
								start: new Date(y, m, d+4, 16, 0),
								allDay: false
							},
							{
								title: 'Meeting',
								start: new Date(y, m, d, 10, 30),
								allDay: false
							},
							{
								title: 'Lunch',
								start: new Date(y, m, d, 12, 0),
								end: new Date(y, m, d, 14, 0),
								allDay: false,
								className: 'fc-event-danger'
							},
							{
								title: 'Birthday Party',
								start: new Date(y, m, d+1, 19, 0),
								end: new Date(y, m, d+1, 22, 30),
								allDay: false
							},
							{
								title: 'Click for Google',
								start: new Date(y, m, 28),
								end: new Date(y, m, 29),
								url: 'http://google.com/'
							}
							*/
						]
					});

					// FIX INPUTS TO BOOTSTRAP VERSIONS
					var $calendarButtons = $calendar.find('.fc-header-right > span');
					$calendarButtons
						.filter('.fc-button-prev, .fc-button-today, .fc-button-next')
							.wrapAll('<div class="btn-group mt-sm mr-md mb-sm ml-sm"></div>')
							.parent()
							.after('<br class="hidden"/>');

					$calendarButtons
						.not('.fc-button-prev, .fc-button-today, .fc-button-next')
							.wrapAll('<div class="btn-group mb-sm mt-sm"></div>');

					$calendarButtons
						.attr({ 'class': 'btn btn-sm btn-default' });
				};

				$(function() {
					initCalendar();
					initCalendarDragNDrop();
				});

			}).apply(this, [ jQuery ]);	
		</script>