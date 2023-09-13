<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/dataTables.checkboxes.min.js"></script>
<link rel="stylesheet" href="<?=base_url()?>assets/vendor/jquery-datatables/media/css/dataTables.checkboxes.css" />
<script src="<?=base_url()?>assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
<script src="<?=base_url()?>node_modules/datatables.net-fixedcolumns/js/dataTables.fixedColumns.min.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/natural.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>application/css/plugin/toastr.min.css" />
<script src="<?= base_url() ?>application/js/toastr.min.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css" />
<script src="<?= base_url() ?>node_modules/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>node_modules/bootstrap-multiselect/dist/css/bootstrap-multiselect.css" />
<script src="<?= base_url() ?>node_modules/bootstrap-multiselect/dist/js/bootstrap-multiselect.js"></script>
<script src="<?= base_url()?>assets/vendor/dataTables.rowsGroup.js"></script>

<section role="main" class="content_section" style="margin-left:0;">
	<section class="panel" style="margin-top: 30px;">
		<div class="panel-body">

			<div class="tabs">
				<?php if($this->data['Designation'] == 'PARTNER'){ ?>
					<ul class="nav nav-tabs nav-justify">
						<li class="check_state assign" data-information="assign">
							<a href="#w2-assign" data-toggle="tab" class="text-center">
								<b>Assign Portfolio</b>
							</a>
						</li>
					</ul>
				<?php }else{ ?>
					<ul class="nav nav-tabs nav-justify">
						<li class="check_state annually" data-information="annually" style="width: 15%">
							<a href="#w2-annually" data-toggle="tab" class="text-center">
								<b>Annually</b>
							</a>
						</li>
						<li class="check_state quarterly" data-information="quarterly"  style="width: 15%">
							<a href="#w2-quarterly" data-toggle="tab" class="text-center">
								<b>Quarterly</b>
							</a>
						</li>
						<li class="check_state monthly" data-information="monthly"  style="width: 15%">
							<a href="#w2-monthly" data-toggle="tab" class="text-center">
								<b>Monthly</b>
							</a>
						</li>
						<li class="check_state assign" data-information="assign">
							<a href="#w2-assign" data-toggle="tab" class="text-center">
								<b>Assign Portfolio</b>
							</a>
						</li>
					</ul>
				<?php } ?>
				<div class="tab-content clearfix">
					<div id="w2-annually" class="tab-pane">
						<div class="col-sm-12 col-md-12">
							<div style="float: left;">
								<label class="control-label" style="display: block;">Partner / Reviewer</label>
								<?php
									// echo form_dropdown('AP_partner_filter', $partner_list, "", ' id="AP_partner_filter" style="width:85%;"');
									echo form_dropdown('AP_partner_filter', $partner_reviewer_list, "", 'id="AP_partner_filter" class="AP_partner_filter" style="width:85%;" multiple="multiple"');
								?>
							</div>
							<div style="width: 200px; float: left; padding-left:25px;">
								<label class="control-label">Job</label>
								<?php
									echo form_dropdown('AP_job_filter', $assignment_job_list, "", ' id="AP_job_filter" style="width:85%;"');
								?>
							</div>
							<div style="width: 200px; float: left;">
								<label class="control-label">Year</label>
								<input type="text" class="form-control" id="AP_year_filter" name="AP_year_filter" data-date-format="yyyy" placeholder="Select Year" autocomplete="off" style="width:85%;">
							</div>
							<div style="float: left;padding-top:24px;">
								<button type="submit" id ="generate_annually_portfolio_Excel" class="btn btn_purple" >Generate Excel</button>
							</div>
						</div>

						<div class="col-sm-12 col-md-12">
							<div id="buttonclick" style="display:block;padding-top:10px;width:100%">
								<table class="table table-bordered table-striped mb-none datatable-annually_portfolio" id="datatable-annually_portfolio">
									<thead>
										<tr style="background-color:white;">
											<th class="text-left" style="min-width: 500px">Client Name</th>
											<th class="text-left" style="min-width: 250px">Statutory Audit</th>
											<th class="text-left" style="min-width: 250px">PMFT Audit</th>
											<th class="text-left" style="min-width: 250px">Querterly Statement Review</th>
											<th class="text-left" style="min-width: 250px">GTO Audit</th>
											<th class="text-left" style="min-width: 250px">Internal Audit</th>
											<th class="text-left" style="min-width: 250px">Compilation</th>
											<th class="text-left" style="min-width: 250px">Accounting</th>
											<th class="text-left" style="min-width: 250px">Taxation</th>
											<th class="text-left" style="min-width: 250px">GST Submission</th>
											<th class="text-left" style="min-width: 250px">Half Yearly Review</th>
											<th class="text-left" style="min-width: 250px">Consolidation</th>
											<th class="text-left" style="min-width: 250px">Roll-Over</th>
											<th class="text-left" style="min-width: 250px">Others</th>
										</tr>
									</thead>
									<tbody class="annually_portfolio_table">
									</tbody>
								</table>
							</div>
						</div>
					</div>

					<div id="w2-quarterly" class="tab-pane">
						<div class="col-sm-12 col-md-12">
							<div style="padding-top:1px; float: left;">
								<label class="control-label" style="display: block;">Partner / Reviewer</label>
								<?php
									// echo form_dropdown('QP_partner_filter', $partner_list, isset($partner_list[0]->partner_name)?$partner_list[0]->partner_name:'', ' id="QP_partner_filter" style="width:85%;"');
									echo form_dropdown('QP_partner_filter', $partner_reviewer_list, "", 'id="QP_partner_filter" class="QP_partner_filter" style="width:85%;" multiple="multiple"');
								?>
							</div>
							<div style="width: 200px; float: left;padding-left:25px;">
								<label class="control-label">Job</label>
								<?php
									echo form_dropdown('QP_job_filter', $assignment_job_list, "", ' id="QP_job_filter" style="width:85%;"');
								?>
							</div>
							<div style="width: 200px; float: left;">
								<label class="control-label">Year</label>
								<input type="text" class="form-control" id="QP_year_filter" name="QP_year_filter" data-date-format="yyyy" placeholder="Select Year" autocomplete="off" style="width:85%;">
							</div>
							<div style="float: left;padding-top:24px;">
								<button type="submit" id ="generate_quarterly_portfolio_Excel" class="btn btn_purple" >Generate Excel</button>
							</div>
						</div>

						<div class="col-sm-12 col-md-12">
							<div id="buttonclick" style="display:block;padding-top:10px;width:100%">
								<table class="table table-bordered table-striped mb-none datatable-quarterly_portfolio" id="datatable-quarterly_portfolio">
									<thead>
										<tr style="background-color:white;">
											<th class="text-left" style="min-width: 500px">Client Name</th>
											<th class="text-left" style="min-width: 300px">Statutory Audit</th>
											<th class="text-left" style="min-width: 300px">PMFT Audit</th>
											<th class="text-left" style="min-width: 300px">Querterly Statement Review</th>
											<th class="text-left" style="min-width: 300px">GTO Audit</th>
											<th class="text-left" style="min-width: 300px">Internal Audit</th>
											<th class="text-left" style="min-width: 300px">Compilation</th>
											<th class="text-left" style="min-width: 300px">Accounting</th>
											<th class="text-left" style="min-width: 300px">Taxation</th>
											<th class="text-left" style="min-width: 300px">GST Submission</th>
											<th class="text-left" style="min-width: 300px">Half Yearly Review</th>
											<th class="text-left" style="min-width: 300px">Consolidation</th>
											<th class="text-left" style="min-width: 300px">Roll-Over</th>
											<th class="text-left" style="min-width: 300px">Others</th>
										</tr>
									</thead>
									<tbody class="quarterly_portfolio_table">
									</tbody>
								</table>
							</div>
						</div>
					</div>

					<div id="w2-monthly" class="tab-pane">
						<div class="col-sm-12 col-md-12">
							<div style="float: left;padding-bottom:20px;">
								<div style="padding-top:1px; float: left;">
									<label class="control-label" style="display: block;">Partner / Reviewer</label>
									<?php
					    				echo form_dropdown('MP_partner_filter', $partner_reviewer_list, "", 'id="MP_partner_filter" class="MP_partner_filter" style="width:85%;" multiple="multiple"');
					    			?>
								</div>
								<div style="float: left;padding-top:24px;padding-left:25px;">
									<button type="submit" id ="generate_monthly_portfolio_Excel" class="btn btn_purple" >Generate Excel</button>
								</div>
							</div>
						</div>

						<div class="col-sm-12 col-md-12">
							<div id="buttonclick" style="display:block;padding-top:10px;table-layout: fixed;width:100%">
								<table class="table table-bordered table-striped mb-none datatable-monthly_portfolio" id="datatable-monthly_portfolio" style="width:100%">
									<thead>
										<tr style="background-color:white;">
											<th class="text-left" style="min-width: 9%">Client Name</th>
											<th class="text-left" style="min-width: 7%">Month</th>
											<!-- <th class="text-left" style="width: 7%">Statutory Audit</th>
											<th class="text-left" style="width: 7%">PMFT Audit</th>
											<th class="text-left" style="width: 7%">Querterly Statement Review</th>
											<th class="text-left" style="width: 7%">GTO Audit</th>
											<th class="text-left" style="width: 7%">Internal Audit</th>
											<th class="text-left" style="width: 7%">Compilation</th> -->
											<th class="text-left" style="min-width: 7%">Accounting</th>
											<!-- <th class="text-left" style="width: 7%">Taxation</th> -->
											<th class="text-left" style="min-width: 7%">GST Submission</th>
											<th class="text-left" style="min-width: 7%">Others</th>
											<!-- <th class="text-left" style="width: 7%">Half Yearly Review</th> -->
											<!-- <th class="text-left" style="width: 7%">Consolidation</th> -->
											<!-- <th class="text-left" style="width: 7%">Roll-Over</th> -->
										</tr>
									</thead>
									<tbody class="monthly_portfolio_table">
									</tbody>
								</table>
							</div>
						</div>
					</div>

					<?php if($Admin || $User == '79') { ?>
					<div id="w2-assign" class="tab-pane">

						<div class="col-sm-12 col-md-12">
							<div style="float: left;padding-right:25px;">
								<label class="control-label" style="display: block;">Partner: </label>
								<?php
									echo form_dropdown('partner_filter', $partner_list, "", 'id="partner_filter" class="partner_filter" onchange="filter();" style="width:85%;" multiple="multiple"');
								?>
							</div>
							<div style="float: left;padding-right:25px;">
								<label class="control-label" style="display: block;">Reviewer: </label>
								<?php
									echo form_dropdown('reviewer_filter', $reviewer_list, "", 'id="reviewer_filter" class="reviewer_filter" onchange="filter();" style="width:85%;" multiple="multiple"');
								?>
							</div>
							<div style="width: 200px; float: left;">
								<label class="control-label" style="display: block;">Type Of Job: </label>
								<?php
									// echo form_dropdown('TypeOfJob_input', $jobs_list, '', ' id="TypeOfJob_input" onchange="TypeOfJobChange();"');
			                        echo form_dropdown('TypeOfJob_input', $jobs_list, '', 'id="TypeOfJob_input" class="TypeOfJob_input" onchange="filter();" style="width:85%;" multiple="multiple"');
								?>
							</div>
						</div>

						<div class="col-sm-12 col-md-12" style="padding-bottom: 25px;">
							<hr>
							<div style="float: left;padding-right:25px;">
								<label class="control-label" style="display: block;">Set Partner: </label>
								<?php
									echo form_dropdown('only_partner', $only_partner, '', 'id="only_partner" class="only_partner" style="width:250px"');
								?>
							</div>
							<div style="float: left; padding-right:25px;">
								<label class="control-label" style="display: block;">Set Reviewer: </label>
								<?php
									echo form_dropdown('only_reviewer', $only_reviewer, '', 'id="only_reviewer" class="only_reviewer" style="width:250px"');
								?>
							</div>
							<div style="float: left;padding-top:24px;">
								<button type='button' class='btn btn_purple' onclick='Set_portfolio()' title='Assign'>
									Assign <i class="fas fa-angle-double-right"></i>
								</button>
							</div>
						</div>

						<div class="col-sm-12 col-md-12">
							<table class="table table-bordered table-striped mb-none" id="portfolio_table">
								<thead>
									<tr style="background-color:white;">
										<th class="text-left" style="width: 5%"></th>
										<th class="text-left" style="width: 45%">Client Name</th>
										<th class="text-left" style="width: 20%">Type of Job</th>
										<th class="text-left" style="width: 15%">Partner</th>
										<th class="text-left" style="width: 15%">Reviewer</th>
									</tr>
								</thead>
								<tbody id="portfolio_table_body">
								</tbody>
							</table>
						</div>
					</div>
					<?php }else{ ?>
					<div id="w2-assign" class="tab-pane">
						<div class="col-sm-12 col-md-12">
							<table class="table table-bordered table-striped mb-none" id="portfolio_table">
								<thead>
									<tr style="background-color:white;">
										<th class="text-left" style="width: 5%"></th>
										<th class="text-left" style="width: 45%">Client Name</th>
										<th class="text-left" style="width: 20%">Type of Job</th>
										<th class="text-left" style="width: 15%">Partner</th>
										<th class="text-left" style="width: 15%">Reviewer</th>
									</tr>
								</thead>
								<tbody id="portfolio_table_body">
									<?php
										foreach($portfolio_list as $portfolio)
										{
											echo '<tr>';
											echo '<td class="text-left" style="text-align:center;"></td>';
											echo '<td style="width: 45%">'.$portfolio->company_name.'</td>';
											echo '<td style="width: 20%">'.$portfolio->jobName.'</td>';
											echo '<td style="width: 15%;text-align:center;">'.$portfolio->partner.'</td>';
											echo '<td style="width: 15%;text-align:center;">'.$portfolio->reviewer.'</td>';
											echo '</tr>';
										}
									?>
								</tbody>
							</table>
						</div>
					</div>
					<?php } ?>

				</div>
			</div>
		</div>
	</section>
</section>

<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>

<script>
	var annually_list  = <?php echo json_encode(isset($annually_list)?$annually_list:"") ?>;
	var quarterly_list = <?php echo json_encode(isset($quarterly_list)?$quarterly_list:"") ?>;
	var monthly_list   = <?php echo json_encode(isset($monthly_list)?$monthly_list:"") ?>;

	<?php if($this->data['Designation'] == 'PARTNER'){ ?>
		$('li[data-information="assign"]').addClass("active");
	    $('#w2-assign').addClass("active");
	<?php }else{ ?>
		$('li[data-information="annually"]').addClass("active");
	    $('#w2-annually').addClass("active");
	<?php } ?>
</script>

<script src="<?= base_url() ?>/application/modules/portfolio/js/annually_portfolio_filtering.js" /></script>
<script src="<?= base_url() ?>/application/modules/portfolio/js/quarterly_portfolio_filtering.js" /></script>
<script src="<?= base_url() ?>/application/modules/portfolio/js/monthly_portfolio_filtering.js" /></script>
<script src="<?= base_url() ?>/application/modules/portfolio/js/assign_portfolio.js" /></script>