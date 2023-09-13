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

<section role="main" class="content_section" style="margin-left:0;">
	<section class="panel" style="margin-top: 30px;">
		<div class="panel-body">

			<div class="tabs">
				<ul class="nav nav-tabs nav-justify">
					<li class="check_state annually active" data-information="annually" style="width: 15%">
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
				</ul>
				<div class="tab-content clearfix">
					<div id="w2-annually" class="tab-pane active">
						<div class="col-sm-12 col-md-12">
							<div style="float: left;width: 200px;">
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
							<div style="width: 200px; float: left;">
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
								<div style="float: left;padding-top:24px;">
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
</script>

<script src="<?= base_url() ?>/application/modules/portfolio/js/annually_portfolio_filtering.js" /></script>
<script src="<?= base_url() ?>/application/modules/portfolio/js/quarterly_portfolio_filtering.js" /></script>
<script src="<?= base_url() ?>/application/modules/portfolio/js/monthly_portfolio_filtering.js" /></script>