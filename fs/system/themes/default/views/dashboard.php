<?php
	$user_arrays = [];
	// print_r($users);
	foreach($users as $u)
	{
		$user_arrays[$u->id] = ucfirst($u->first_name);
	}
?>
						<div class="col-lg-6 col-xs-12" style="min-height:350px;">		
							<section class="panel">
								<header class="panel-heading">
									<div class="panel-actions">
										<a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
										<!-- <a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a> -->
									</div>
							
									<h2 class="panel-title">Chart</h2>
								</header>
								<div class="panel-body" style="height:250px;">	
									<div class="row text-center">
										<div class="col-xs-6 col-md-6">
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
										</div>
									</div>
								</div>
							</section>
						</div>
						<div class="col-lg-6 col-xs-12" style="height:350px;">
								<section class="panel">
									<header class="panel-heading">
										<div class="panel-actions">
											<a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
											<!-- <a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a> -->
										</div>

										<h2 class="panel-title">Deadline</h2>
									</header>
									<div class="panel-body" style="height:250px;overflow:auto;">
										<div class="table-responsive">
											<table class="table table-striped mb-none">
												<thead>
													<tr>
														<th>#</th>
														<th>Task</th>
														<th>Assign To</th>
														<?php if ($Owner) {?>
														<th>Assign By</th>
														<?php } ?>
														<th>Due Date</th>
													</tr>
												</thead>
												<tbody >
													<?php
														// print_r($user_arrays);
														$i=1;
														$class_if_urgent = 'style="color:red;font-weight:bold;"';
														foreach($user_task as $us)
														{
														$ass_to = explode('|',$us->assign_to);
															if (!$us->urgent) echo '<tr >'; else echo '<tr '.$class_if_urgent.'>'; 
															echo '	<td>'.$i.'</td>
																	<td>'.ucfirst($us->task).'</td>';
															echo '<td>';
															$byk_user_assign=0;
															foreach($ass_to as $ass_tol)
															{
																if ($ass_tol != '')
																{
																	if ($byk_user_assign > 0) echo ",";
																	echo $user_arrays[$ass_tol];
																	$byk_user_assign++;
																}
															}
															echo '</td>';
															if ($Owner)	echo '<td>'.ucfirst($user_arrays[$us->assign_by]).'</td>';
															
															echo '	<td><a href="" class="filing_date mb-xs mt-xs mr-xs modal-sizes">'.$us->task_date.'</a></td>
																</tr>';
																$i++;
														}
													?>
												</tbody>
											</table>
										</div>
									</div>
								</section>
							
							</div>
					
					<div class="row">
						<div class="col-md-6" style="width: 100%;">
							<section class="panel panel-transparent">
								<header class="panel-heading">
									<div class="panel-actions">
										<!--a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
										<!-- <a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a> -->
									</div>

									<h2 class="panel-title">To Do List</h2>
								</header>
								<div class="panel-body">
									<!-- start: page -->
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
																	<i class="fa fa-calendar"></i>
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
						</div>
					
					
						<div class="col-xl-6 col-lg-12">
							<section class="panel">
								<header class="panel-heading panel-heading-transparent">
									<div class="panel-actions">
										<!--a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
										<!-- <a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a> -->
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
																<img class="img-responsive" width="215" src="assets/images/projects/project-4.jpg">
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
						</div>
					</div>
					<div class="row">
						
					</div>
					<!-- end: page -->
				</section>
			</div>
<script>
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
              console.log(data);
			  
				var event={id:1 , title: $("#task_todolist").val(), start:  $("#date_todolist").val()};
				$('#calendar').fullCalendar( 'renderEvent', event, true);
            }
          });
					var date = new Date();
					var d = date.getDate();
					var m = date.getMonth();
					var y = date.getFullYear();
		  
	});
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
								foreach($user_task as $us)
								{
									echo '{';
									echo '	title  : "'.$us->task.'",';
									echo '	start  : "'.$us->task_date.'",';
									echo '},';
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
