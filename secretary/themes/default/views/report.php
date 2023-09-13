<div class="header_between_all_section">
<section class="panel">

	<div class="panel-body">
		<?php echo form_open('', array('id' => 'report_form')); ?>
			<div class="col-md-12">
				
				<a href="javascript: void(0);" class="btn btn-default" id="printReportBtn" class="printReportBtn" style="float: right;">Print</a>
				<input type="button" class="btn btn-primary search_report" id="searchRegister" name="searchRegister" value="Search" style="float: right;margin-right: 10px;"/>											
				<div class="form-group">
					<label class="col-xs-3" for="w2-show_all">Report to generate: </label>
					<div class="col-xs-7 form-inline" style="width:40%">
						<select id="report_to_generate" class="form-control report_to_generate" style="width:200px;" name="report_to_generate">
		                    <option value="0">Please Select</option>
		                    <option value="person_profile" <?=isset($_POST['type'])?($_POST['type'] == "person_profile"?'selected':''):''?>>Person Profile</option>
		                    <option value="client_list" <?=isset($_POST['type'])?($_POST['type'] == "client_list"?'selected':''):''?>>Client List</option>
		                    <option value="due_date" <?=isset($_POST['type'])?($_POST['type'] == "due_date"?'selected':''):''?>>Due Date</option>
		                    <option value="list_of_invoice" <?=isset($_POST['type'])?($_POST['type'] == "list_of_invoice"?'selected':''):''?>>List of Invoice</option>
		                    <option value="list_of_credit_note" <?=isset($_POST['type'])?($_POST['type'] == "list_of_credit_note"?'selected':''):''?>>List of Credit Note</option>
		                    <option value="invoice_period" <?=isset($_POST['type'])?($_POST['type'] == "invoice_period"?'selected':''):''?>>Invoice Period</option>
		                    <option value="payment" <?=isset($_POST['type'])?($_POST['type'] == "payment"?'selected':''):''?>>Payment</option>
							<?php if($Admin || $this->session->userdata('email_for_user') == "penny@aaa-global.com") { ?>
		                    	<option value="bank_transaction" <?=isset($_POST['type'])?($_POST['type'] == "bank_transaction"?'selected':''):''?>>Bank Transaction</option>
		                    <?php } ?>
		                    <option value="sales_report" <?=isset($_POST['type'])?($_POST['type'] == "sales_report"?'selected':''):''?>>Sales Report</option>
		                    <option value="register_contorller" <?=isset($_POST['type'])?($_POST['type'] == "register_contorller"?'selected':''):''?>>Register of Controllers</option>
		                    <option value="list_of_recurring" <?=isset($_POST['type'])?($_POST['type'] == "list_of_recurring"?'selected':''):''?>>List of Recurring</option>
		                    <option value="list_of_receipt" <?=isset($_POST['type'])?($_POST['type'] == "list_of_receipt"?'selected':''):''?>>List of Receipt</option>
		                    <option value="list_of_document" <?=isset($_POST['type'])?($_POST['type'] == "list_of_document"?'selected':''):''?>>List of Document</option>
		                    <option value="gst_report" <?=isset($_POST['type'])?($_POST['type'] == "gst_report"?'selected':''):''?>>GST/SST Report</option>
		                    <option value="csp_report" <?=isset($_POST['type'])?($_POST['type'] == "csp_report"?'selected':''):''?>>CSP Report</option>
		                    <option value="progress_bill_report" <?=isset($_POST['type'])?($_POST['type'] == "progress_bill_report"?'selected':''):''?>>Progress Billing Report</option>
		                </select>
					</div>
				</div>

				<div id="hidden_section1_report" style="display:none; margin-bottom: 15px;">
					<div class="form-group">
						<label class="col-xs-3" for="w2-show_all">ID: </label>
						<div class="col-xs-7 form-inline" style="width:40%">
							<input type="text" class="form-control" id="client_id" name="client_id" value="" style="width: 200px;"/>
						</div>
					</div>
				</div>

				<div class="form-group hidden_section11_report" style="display:none; margin-bottom: 15px;">
					<label class="col-xs-3" for="w2-show_all">Type: </label>
					<div class="col-xs-7 form-inline" style="width:40%">
						<select id="csp_report_type" class="form-control csp_report_type" style="width:200px;" name="csp_report_type">
							<!-- <option value="all">ALL</option> -->
							<option value="csp_auto_billing_report">CSP Auto Billing Report</option>
							<option value="csp_revenue_report">CSP Revenue Report</option>
		                </select>
					</div>
				</div>

				<div class="form-group hidden_section3_report" style="display:none; margin-bottom: 15px;">
					<label class="col-xs-3" for="w2-show_all">Firm: </label>
					<div class="col-xs-7 form-inline" style="width:40%">
						<select id="firm" class="form-control firm" style="width:200px;" name="firm">
							<option value="all">ALL</option>
		                </select>
					</div>
				</div>

				<div class="form-group hidden_section10_report" style="display:none; margin-bottom: 15px;">
					<label class="col-xs-3" for="w2-show_all">Firm: </label>
					<div class="col-xs-7 form-inline" style="width:40%">
						<select id="gst_report_firm" class="form-control gst_report_firm" style="width:200px;" name="gst_report_firm">
							<option data-gst_register_date="2020-09-14" value="aaa_all">ACUMEN ALPHA ADVISORY PTE. LTD. (ALL)</option>
		                </select>
					</div>
				</div>

				<div class="form-group hidden_section9_report" style="display:none; margin-bottom: 15px;">
					<label class="col-xs-3" for="w2-bank_account">Bank Account: </label>
					<div class="col-xs-7 form-inline" style="width:40%">
						<select id="bank_account" class="form-control bank_account" style="width:400px;" name="bank_account">
							<option value="0">Please Select</option>
		                </select>
					</div>
				</div>

				<div class="form-group hidden_section4_report" style="display:none; margin-bottom: 15px;">
					<label class="col-xs-3" for="w2-show_all">Type of Due Date: </label>
					<div class="col-xs-7 form-inline" style="width:40%">
						<select id="type_of_due_date" class="form-control type_of_due_date" style="width:200px;" name="type_of_due_date">
							<option value="0">Please Select</option>
							<option value="year_end">Year End</option>
							<option value="due_date_agm">Due Date AGM (S.175)</option>
							<option value="due_date_new_agm">Due Date New AGM (S.175)</option>
							<option value="due_date_ar">Due Date AR (S.197)</option>
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
								<input type="text" class="form-control report_date_from" name="from" value="" placeholder="From">
								<span class="input-group-addon">to</span>
								<input type="text" class="form-control report_date_to" name="to" value="" placeholder="To">
							</div>
						</div>
						<div class="col-md-3">
							<input type="button" class="btn btn-primary search_report" id="searchRegister" name="searchRegister" value="Search"/>
						</div>
					</div>
				</div>

				<div class="form-group hidden_section5_report" style="display:none; margin-bottom: 15px;">
					<label class="col-xs-3" for="w2-show_all">Service Type: </label>
					<div class="col-xs-7 form-inline" style="width:40%">
						<select id="service_category" class="form-control service_category" style="width:200px;" name="service_category">
							<option value="0">All</option>
		                </select>
					</div>
				</div>

				<div class="form-group hidden_section6_report" style="display:none; margin-bottom: 15px;">
					<label class="col-xs-3" for="w2-show_all">Type for Payment: </label>
					<div class="col-xs-7 form-inline" style="width:40%">
						<select id="type_of_payment" class="form-control type_of_payment" style="width:200px;" name="type_of_payment">
							<option value="supplier">Supplier</option>
							<option value="client">Client</option>
							<option value="claim">Claim</option>
		                </select>
					</div>
				</div>

				<div class="form-group hidden_section7_report" style="display:none; margin-bottom: 15px;">
					<label class="col-xs-3" for="w2-show_all">Status: </label>
					<div class="col-xs-7 form-inline" style="width:40%">
						<select id="payment_status" class="form-control payment_status" style="width:200px;" name="payment_status">
							<option value="all">ALL</option>
							<option value="0">Pending</option>
							<option value="2">Rejected</option>
							<option value="3">Approved</option>
		                </select>
					</div>
				</div>

				<div class="form-group hidden_section8_report" style="display:none; margin-bottom: 15px;">
					<label class="col-xs-3" for="w2-show_all">Name: </label>
					<div class="col-xs-7 form-inline" style="width:40%">
						<select id="payment_username" class="form-control payment_username" style="width:200px;" name="payment_username">
							<option value="all">ALL</option>
		                </select>
					</div>
				</div>

				<HR SIZE=10></HR>
				<div class="printablereport">
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
	var service_category = <?php echo json_encode($service_category);?>;
	var gst_firm = <?php echo json_encode($gst_firm);?>;
	console.log(gst_firm);
</script>
<script src="themes/default/assets/js/report.js?v=86666eee4fc8d1b59e4584b0d39edfa2085" charset="utf-8"></script>		