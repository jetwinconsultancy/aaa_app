<!-- <div class="header_between_all_section">
<?php if ((!$Individual && $Individual == true) || (!$Individual && $Individual == null && !$Client)) {?>
	<div class="col-lg-6 col-xs-12 header_between_page">		
		<section class="panel">
			<header class="panel-heading">
				<div class="panel-actions">
					<a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
				</div>
		
				<h2 class="panel-title">Billing Info</h2>
			</header>
			<div class="panel-body" style="height:250px;">	
				<div class="row text-center">
					<div class="col-xs-12 col-md-12">
						<canvas id="myChart" style="display: block; width: 100% !important; height: 240px !important;"></canvas>
					</div>
				</div>
			</div>
		</section>
	</div>
<?php } ?>
</div> -->

<div class="header_between_all_section">
	<h3>Welcome to Financial Statement System!</h3>
</div>
		
<script>

	$("#header_our_firm").removeClass("header_disabled");
	$("#header_manage_user").removeClass("header_disabled");
	$("#header_access_right").removeClass("header_disabled");
	$("#header_user_profile").removeClass("header_disabled");
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
