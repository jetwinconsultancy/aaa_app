<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/dataTables.checkboxes.min.js"></script>
<link rel="stylesheet" href="<?=base_url()?>assets/vendor/jquery-datatables/media/css/dataTables.checkboxes.css" />
<script src="<?=base_url()?>assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/natural.js"></script>
<link rel="stylesheet" href="<?=base_url()?>node_modules/fullcalendar/dist/fullcalendar.css" />
<script src="<?= base_url() ?>node_modules/fullcalendar/dist/fullcalendar.js"></script>
<script src="<?= base_url() ?>node_modules/bootbox/bootbox.min.js"></script>

<section role="main" class="content_section" style="margin-left:0;">
	<section class="panel" style="margin-top: 30px;">
		<header class="panel-heading">
			<div class="panel-actions" style="height:80px">
				<a class="themeColor_purple" href="location/create" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Create Arrangement" ><i class="fa fa-plus-circle themeColor_purple" style="font-size:16px;height:45px;"></i> New Arrangement </a>
			</div>
			<h2></h2>
		</header>
		<div class="panel-body">
			<div class="col-md-12">
				<div class="tabs">				
					<ul class="nav nav-tabs nav-justify">
						<li class="active check_state" data-information="new_arrangement">
							<a href="#w2-new_arrangement" data-toggle="tab" class="text-center">
								<span class="badge hidden-xs">1</span>
								Arrangement
							</a>
						</li>
						<li class="check_state" data-information="old_arrangement">
							<a href="#w2-old_arrangement" data-toggle="tab" class="text-center">
								<span class="badge hidden-xs">2</span>
								History
							</a>
						</li>
					</ul>

					<div class="tab-content clearfix">
						<div id="w2-new_arrangement" class="tab-pane active">
							<table class="table table-bordered table-striped mb-none datatable-default-newArrangement" id="" style="width:100%">
								<thead>
									<tr style="background-color:white;">
										<th class="text-left">Employee</th>
										<th class="text-left">Location</th>
										<th class="text-left">Start Date / Time</th>
										<th class="text-left">End Date / Time</th>
										<th class="text-left"></th>
									</tr>
								</thead>
								<tbody>
									<?php 
										foreach($new_arrangement as $new)
							  			{
							  				$link = 'location/create/'.$new->id;
							  				echo '<tr>';
							  				echo '<td>'.$new->name.'</td>';
							  				echo '<td>'.$new->arrangement_location.'</td>';
							  				echo '<td>'.date('d M Y - g:i a', strtotime($new->arrangement_start)).'</td>';
							  				echo '<td>'.date('d M Y - g:i a', strtotime($new->arrangement_end)).'</td>';
							  				echo '<td>
							  						<button class="btn btn_purple" onclick="update_arrangement('.$new->id.')" style="margin-bottom:10px;">Update</button>
							  						<button class="btn btn_purple" onclick="withdraw_arrangement('.$new->id.')" style="margin-bottom:10px;">Withdraw</button>
							  					  </td>';
							  				echo '</tr>';
							  			}
									?>
								</tbody>
							</table>
						</div>

						<div id="w2-old_arrangement" class="tab-pane">
							<table class="table table-bordered table-striped mb-none datatable-default-oldArrangement" id="" style="width:100%">
								<thead>
									<tr style="background-color:white;">
										<th class="text-left">Employee</th>
										<th class="text-left">Location</th>
										<th class="text-left">Start Date / Time</th>
										<th class="text-left">End Date / Time</th>
										<th class="text-left"></th>
									</tr>
								</thead>
								<tbody>
									<?php 
										foreach($old_arrangement as $old)
							  			{
							  				echo '<tr>';
							  				echo '<td>'.$old->name.'</td>';
							  				echo '<td>'.$old->arrangement_location.'</td>';
							  				echo '<td>'.date('d M Y - g:i a', strtotime($old->arrangement_start)).'</td>';
							  				echo '<td>'.date('d M Y - g:i a', strtotime($old->arrangement_end)).'</td>';
							  				echo '<td>
							  						<button class="btn btn_purple" onclick="view_arrangement('.$old->id.')" style="margin-bottom:10px;">View</button>
							  					  </td>';
							  				echo '</tr>';
							  			}
									?>
								</tbody>
							</table>
						</div>
					</div>

				</div>
			</div>

			<div id="calender_container"></div>
		</div>
	</section>
</section>

<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>

<script>

	// INITIALIZATION
	var all_arrangement = JSON.parse('<?php echo json_encode(isset($all_arrangement)?$all_arrangement:"") ?>');
	var calender_event  = [];

	$(document).ready( function () {
	    $('.datatable-default-newArrangement').DataTable( {
	    	"order": []
	    } );

	    $('.datatable-default-oldArrangement').DataTable( {
	    	"order": []
	    } );
	} );
	// END INITIALIZATION

	// PUSH CALENDER EVENT
	if(all_arrangement.length)
	{
		for(var a = 0; a < all_arrangement.length; a++)
		{
			var s = new Date(all_arrangement[a]['arrangement_start']);
		    var e = new Date(all_arrangement[a]['arrangement_end']);

		    var x = moment(x).set({ 
		    	hour: parseInt(s.getHours()), 
		    	minute: parseInt(s.getMinutes()), 
		    	date: parseInt(s.getDate()), 
		    	month: parseInt(s.getMonth()), 
		    	year: parseInt(s.getFullYear()) })
		    .toDate();

		    var y = moment(y).set({ 
		    	hour: parseInt(e.getHours()), 
		    	minute: parseInt(e.getMinutes()), 
		    	date: parseInt(e.getDate()), 
		    	month: parseInt(e.getMonth()), 
		    	year: parseInt(e.getFullYear()) })
		    .toDate();

			calender_event.push({
				title: all_arrangement[a]['name']+' > '+all_arrangement[a]['arrangement_location'], 
				start:  x,
		        end: y,
				color: '#9c27b0 !important' 
			});
		}
	}
	// END PUSH CALENDER EVENT

	// INITIALIZATION FULLCALENDER
	(function( $ ) {

		'use strict';
		var initCalendar = function() {
		var $calendar = $('#calender_container');
		$calendar.fullCalendar({
			// initialView: 'dayGridWeek',
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay,listWeek'
			},
			themeButtonIcons: {
				prev: 'fa fa-caret-left',
				next: 'fa fa-caret-right',
			},

			editable: false,
			droppable: false,
			displayEventTime: false,
			navLinks: true, // can click day/week names to navigate views
			eventLimit: true, // allow "more" link when too many events
			events: calender_event,
			slotEventOverlap:false
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

	$('#calender_container').fullCalendar( 'rerenderEvents' ); // DRAW CALENDER
	// END INITIALIZATION FULLCALENDER

	// UPDATE ARRANGEMENT
	function update_arrangement(id)
	{
		window.location.href= '<?php base_url() ?>/hrm/location/create/'+id+'/update';
	}
	// END UPDATE ARRANGEMENT

	// WITHDRAW ARRANGEMENT
	function withdraw_arrangement(id)
	{
		bootbox.confirm({
	        message: "<strong>Confirm to WITHDRAW the arrangement?</strong>",
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

	        		$.post("<?php echo site_url('location/withdraw_arrangement'); ?>", { id: id }, function (data, status){
		    			if(status)
		    			{
		    				location.reload();
		    				$("#loadingmessage").hide();
		    			}
			    	});
	        	}
	        }
	    })
	}
	// END WITHDRAW ARRANGEMENT

	// VIEW ARRANGEMENT
	function view_arrangement(id)
	{
		window.location.href= '<?php base_url() ?>/hrm/location/create/'+id+'/view';
	}
	// END VIEW ARRANGEMENT
</script>
