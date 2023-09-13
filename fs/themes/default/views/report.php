<div class="header_between_all_section">
<section class="panel">

	<div class="panel-body">
		<?php echo form_open('', array('id' => 'report_form')); ?>
			<div class="col-md-12">
				
				<a href="javascript: void(0);" class="btn btn-default" id="printReportBtn" class="printReportBtn" style="float: right;">Print</a>
				<input type="button" class="btn btn-primary search_report" id="searchRegister" name="searchRegister" value="Search" style="float: right;margin-right: 10px;"/>											
				<div class="form-group">
					<label class="col-xs-3" for="w2-show_all">Report to generate: </label>
					<div class="col-sm-7 form-inline">
						<select id="report_to_generate" class="form-control report_to_generate" style="width:200px;" name="report_to_generate">
		                    <option value="0">Please Select</option>
		                    <option value="person_profile" <?=$_POST['type'] == "person_profile"?'selected':'';?>>Person Profile</option>
		                    <option value="client_list" <?=$_POST['type'] == "client_list"?'selected':'';?>>Client List</option>
		                    <option value="due_date" <?=$_POST['type'] == "due_date"?'selected':'';?>>Due Date</option>
		                    <option value="list_of_invoice" <?=$_POST['type'] == "list_of_invoice"?'selected':'';?>>List Of Invoice</option>
		                </select>
					</div>
				</div>

				<div id="hidden_section1_report" style="display:none; margin-bottom: 15px;">
					<div class="form-group">
						<label class="col-xs-3" for="w2-show_all">ID: </label>
						<div class="col-sm-7 form-inline">
							<input type="text" class="form-control" id="client_id" name="client_id" value="" style="width: 200px;"/>
						</div>
					</div>
				</div>

				<div class="form-group hidden_section3_report" style="display:none; margin-bottom: 15px;">
					<label class="col-xs-3" for="w2-show_all">Firm: </label>
					<div class="col-sm-7 form-inline">
						<select id="firm" class="form-control firm" style="width:200px;" name="firm">
		                </select>
					</div>
				</div>

				<div id="hidden_section2_report" style="display:none;">
					<div class="form-group">
						<label class="col-xs-3" for="w2-show_all">Date Range: </label>
						<div class="col-sm-6 form-inline">
							<div class="input-daterange input-group" data-plugin-datepicker data-date-format="dd/mm/yyyy">
								<span class="input-group-addon">
									<i class="far fa-calendar-alt"></i>
								</span>
								<input type="text" class="form-control" name="from" value="" placeholder="From">
								<span class="input-group-addon">to</span>
								<input type="text" class="form-control" name="to" value="" placeholder="To">
							</div>
						</div>
						<div class="col-md-3">
							<input type="button" class="btn btn-primary search_report" id="searchRegister" name="searchRegister" value="Search"/>
						</div>
					</div>
				</div>
				<HR SIZE=10></HR>
				<div class="printablereport" style="width: 100%">
					<div id="report_table">
																		
					</div>
					
				</div>
						
			</div>
		<?= form_close(); ?>
	</div>

</section>
</div>
<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>
<script type="text/javascript">
	var firm = <?php echo json_encode($firm);?>;
</script>
<script src="themes/default/assets/js/report.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>		