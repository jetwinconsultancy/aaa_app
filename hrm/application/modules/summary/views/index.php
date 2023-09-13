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
				<!-- <a class="themeColor_purple" href="location/create" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Create Arrangement" ><i class="fa fa-plus-circle themeColor_purple" style="font-size:16px;height:45px;"></i> New Arrangement </a> -->
				<label id='week_range'>Week Range: <label id="start"></label> ~ <label id="last"></label></label>
			</div>
			<h2></h2>
		</header>
		<div class="panel-body">
			<div class="col-md-12">
				<div class="tabs">				
					<ul class="nav nav-tabs nav-justify">

						<li class="check_state active" data-information="no_job_completed_updated">
							<a href="#w2-no_job_completed_updated" data-toggle="tab" class="text-center">
								<b>No. Of Job Updated/Completed</b>
							</a>
						</li>

						<li class="check_state" data-information="no_job_remain">
							<a href="#w2-no_job_remain" data-toggle="tab" class="text-center">
								<b>Job Remain</b>
							</a>
						</li>

					</ul>

					<div class="tab-content clearfix">

						<div id="w2-no_job_completed_updated" class="tab-pane active">
							<table class="table table-bordered table-striped mb-none datatable-no_job_completed_updated" id="" style="width:100%">
								<thead>
									<tr style="background-color:white;">
										<th class="text-left">NAME</th>
										<th class="text-left">STATUS UPDATED</th>
										<th class="text-left">REMARK UPDATED</th>
										<th class="text-left">COMPLETED</th>
									</tr>
								</thead>
								<tbody>
									<?php 
										foreach($updated_or_completed_list as $updated_or_completed)
							  			{
							  				echo '<tr>';
							  				// echo '<td>'.$updated_or_completed['name'].'</td>';
							  				echo '<td><a href="summary/show_details/JUC/'.$updated_or_completed['emp_id'].'">'.$updated_or_completed['name'].'</a></td>';
							  				echo '<td>'.$updated_or_completed['number_status_updated'].'</td>';
							  				echo '<td>'.$updated_or_completed['number_remark_updated'].'</td>';
							  				echo '<td>'.$updated_or_completed['number_completed'].'</td>';
							  				echo '</tr>';
							  			}
									?>
								</tbody>
							</table>
						</div>

						<div id="w2-no_job_remain" class="tab-pane">
							<table class="table table-bordered table-striped mb-none datatable-no_job_remain" id="datatable-no_job_remain">
								<thead>
									<tr style="background-color:white;">
										<th class="text-left" style="min-width: 180px">NAME</th>
										<th class="text-left" style="min-width: 180px">TOTAL</th>
										<th class="text-left" style="min-width: 180px">ACCOUNT NOT IN</th>
										<th class="text-left" style="min-width: 180px">YET TO START</th>
										<th class="text-left" style="min-width: 180px">PLANNING WITHOUT ACCOUNT</th>
										<th class="text-left" style="min-width: 180px">PLANNING COMPLETED - WITHOUT ACCOUNT</th>
										<th class="text-left" style="min-width: 180px">PLANNING WITH ACCOUNT</th>
										<th class="text-left" style="min-width: 180px">PLANNING COMPLETED - WITH ACCOUNT</th>
										<th class="text-left" style="min-width: 180px">INTERIM COMPLETED</th>
										<th class="text-left" style="min-width: 180px">WIP</th>
										<th class="text-left" style="min-width: 180px">KIV</th>
										<th class="text-left" style="min-width: 180px">FINALIZING</th>
										<th class="text-left" style="min-width: 180px">REVIEWING - TEAM LEAD</th>
										<th class="text-left" style="min-width: 180px">REVIEWING - MANAGER</th>
										<th class="text-left" style="min-width: 180px">REVIEWING - PARTNER</th>
										<th class="text-left" style="min-width: 180px">CLEARING REVIEW POINTS</th>
										<th class="text-left" style="min-width: 180px">SENT OUT FOR ADOPTION</th>
										<th class="text-left" style="min-width: 180px">SIGNED</th>
										<th class="text-left" style="min-width: 180px">PENDING DOCS & PAYMENT</th>
										<th class="text-left" style="min-width: 180px">PENDING DOCS</th>
										<th class="text-left" style="min-width: 180px">PENDING PAYMENT</th>
										<!-- <th class="text-left" style="min-width: 180px">COMPLETED</th> -->
									</tr>
								</thead>
								<tbody>
									<?php 
										foreach($job_remain_list as $job_remain)
							  			{
							  				echo '<tr>';
							  				// echo '<td>'.$job_remain['name'].'</td>';
							  				echo '<td><a href="summary/show_details/JR/'.$job_remain['emp_id'].'">'.$job_remain['name'].'</a></td>';
							  				echo '<td>'.$job_remain['total'].'</td>';
							  				echo '<td>'.$job_remain['ACCOUNT_NOT_IN'].'</td>';
							  				echo '<td>'.$job_remain['YET_TO_START'].'</td>';
							  				echo '<td>'.$job_remain['PLANNING_WITHOUT_ACCOUNT'].'</td>';
							  				echo '<td>'.$job_remain['PLANNING_COMPLETED_WITHOUT_ACCOUNT'].'</td>';
							  				echo '<td>'.$job_remain['PLANNING_WITH_ACCOUNT'].'</td>';
							  				echo '<td>'.$job_remain['PLANNING_COMPLETED_WITH_ACCOUNT'].'</td>';
							  				echo '<td>'.$job_remain['INTERIM_COMPLETED'].'</td>';
							  				echo '<td>'.$job_remain['WIP'].'</td>';
							  				echo '<td>'.$job_remain['KIV'].'</td>';
							  				echo '<td>'.$job_remain['FINALIZING'].'</td>';
							  				echo '<td>'.$job_remain['REVIEWING_TEAM_LEAD'].'</td>';
							  				echo '<td>'.$job_remain['REVIEWING_MANAGER'].'</td>';
							  				echo '<td>'.$job_remain['REVIEWING_PARTNER'].'</td>';
							  				echo '<td>'.$job_remain['CLEARING_REVIEW_POINTS'].'</td>';
							  				echo '<td>'.$job_remain['SENT_OUT_FOR_ADOPTION'].'</td>';
							  				echo '<td>'.$job_remain['SIGNED'].'</td>';
							  				echo '<td>'.$job_remain['PENDING_DOCS_PAYMENT'].'</td>';
							  				echo '<td>'.$job_remain['PENDING_DOCS'].'</td>';
							  				echo '<td>'.$job_remain['PENDING_PAYMENT'].'</td>';
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
var active_tab = <?php echo json_encode(isset($active_tab)?$active_tab:"no_job_completed_updated") ?>;

$(document).ready(function ()
{
	if(active_tab != null)
	{  
		pv_index_tab_aktif = active_tab;

	    if(active_tab != "no_job_completed_updated")
	    {
	    	$("#week_range").hide();
	        $('li[data-information="'+active_tab+'"]').addClass("active");
	        $('#w2-'+active_tab+'').addClass("active");
	        $('li[data-information="no_job_completed_updated"]').removeClass("active");
	        $('#w2-no_job_completed_updated').removeClass("active");
	    }
	}

	var curr = new Date; // get current date
	var first = curr.getDate() - curr.getDay(); // First day is the day of the month - the day of the week
	var last = first + 6; // last day is the first day + 6
	var firstday = new Date(curr.setDate(first)).toUTCString();
	var lastday = new Date(curr.setDate(last)).toUTCString();
	$('#start').text(moment(firstday).format('ddd, DD MMM YYYY'));
	$('#last').text(moment(lastday).format('ddd, DD MMM YYYY'));

	$('#start').wrapInner("<strong />");
	$('#last').wrapInner("<strong />");
	$('#week_range').wrapInner("<strong />");

  $(".datatable-no_job_remain").DataTable({
    "order": [],
    "scrollY": "600px",
    "scrollX": true,
    "scrollCollapse": true,
    fixedColumns: {leftColumns: 1}
  });

  $(".datatable-no_job_completed_updated").DataTable({
    "order": [],
    // "scrollY": "600px",
    // "scrollX": true,
    // "scrollCollapse": true,
    // fixedColumns: {leftColumns: 1}
  });
});

$(document).on('click',".check_state",function(){
	var index_tab_aktif = $(this).data("information");
	if(index_tab_aktif == "no_job_completed_updated")
	{
		$("#week_range").show();
	}
	else
	{
		$("#week_range").hide();
	}
	
});
</script>
