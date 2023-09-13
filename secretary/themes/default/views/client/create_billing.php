<section class="panel" style="margin-top: 30px;">
	<?php echo $breadcrumbs;?>
	<div class="panel-body">
		<div class="col-md-12">
			<section class="panel">
				<form id="create_billing_form">
				<div class="panel-body">
					<input type="hidden" name="old_gst_rate" class="form-control" id="old_gst_rate" value=""/>
					<input type="hidden" name="services_company_code" class="form-control" id="services_company_code" value=""/>
					<table class="table table-bordered table-striped table-condensed mb-none">
						<tr>
							<th>Invoice No</th>
							<td><div class="validate" style="width: 50%;"><input type="text" name="invoice_no" class="form-control" id="invoice_no" value="" /><input type="hidden" name="previous_invoice_no" class="form-control" id="previous_invoice_no" value="" /></div></td><!-- disabled -->
						</tr>
						<tr>
							<th>Date</th>
							<td>
								<div class="billing_date_div">
								<div class="input-group" id="billing_date" style="width: 30%;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="billing_date form-control" name="billing_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker required value="">
								</div>
							</td>
						</tr>
						<tr>
							<th>Client Name</th>
							<td>
								<div class="input-group dropdown_client_name" style="width: 100%; display: none">
									<select id="client_name" class="form-control client_name" style="width: 100%;" name="client_name">
						                    <option value="0">Select Client Name</option>
						            </select>
						        </div>
						        <div class="input_client_name" style="width: 100%; display: none">
									<input type="text" id="text_client_name" class="form-control text_client_name" style="width: 100%;" name="text_client_name">
						        </div>
							</td>
						</tr>
						<tr>
							<th>Currency</th>
							<td>
								<div class="input-group" style="width: 100%;">
									<select id="currency" class="form-control currency" style="width: 100%;" name="currency">
						                    <option value="0">Select Currency</option>
						            </select>
						        </div>
							</td>
						</tr>
						<tr>
							<th>Rate</th>
							<td>
								<div class="rate-input-group">
									<div class="validate" style="width: 50%;"><input type="text" name="rate" class="form-control rate" id="rate" value="" style="text-align: right"/></div>
								</div>
							</td>
						</tr>
						<tr>
							<th>Address</th>
							<td>
								<div class="input-group">
									<textarea class="form-control" name="address" id="address" style="width:400px;height:120px;">
									</textarea>
									<input type="hidden" name="hidden_postal_code">
									<input type="hidden" name="hidden_street_name">
									<input type="hidden" name="hidden_building_name">
									<input type="hidden" name="hidden_unit_no1">
									<input type="hidden" name="hidden_unit_no2">
									<input type="hidden" name="hidden_foreign_address1">
									<input type="hidden" name="hidden_foreign_address2">
									<input type="hidden" name="hidden_foreign_address3">
								</div>
							</td>
						</tr>
					</table>
					<br/>
					<table class="table table-bordered table-striped mb-none" style="width: 100%">
						<thead>
							<div class="tr" id="create_billing_service" style="display:none;">
								<div class="th" valign=middle style="width:2%">Service</div>
								<div class="th" valign=middle style="width:1%">Invoice Description</div>
								<div class="th" style="width:1%">Amount</div>
								<div class="th" style="width:1%">Unit Pricing</div>
								<a href="javascript: void(0);" class="th" style="color: #D9A200; width:5%; outline: none !important;text-decoration: none;"><span id="billing_service_info_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Billing" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add Line</span></a>
							</div>
						</thead>
						<div class="tbody" id="body_create_billing" style="display:none;">
						</div>
						<div class="tr" id="sub_total_create_billing" style="display:none;">
						<!-- <div class="th" valign=middle>No</div> -->
							<div class="th">Sub-Total</div>
							<div class="th"></div>
							<div class="th" style="text-align: right;" id="sub_total">0</div>
							<div class="th"></div>
							<div class="th"></div>
						</div>
						<div class="tr" id="gst_create_billing" style="display:none;">
						<!-- <div class="th" valign=middle>No</div> -->
							<div class="th"><?=($firm_info[0]->firm_currency == "1") ? 'GST' : 'SST'?> <span id="gst_with_rate"></span></div>
							<div class="th"></div>
							<div class="th" style="text-align: right;" id="gst">0</div>
							<div class="th"></div>
							<div class="th"></div>
						</div>
						<div class="tr" id="grand_total_create_billing" style="display:none;">
						<!-- <div class="th" valign=middle>No</div> -->
							<div class="th">Grand Total</div>
							<div class="th"></div>
							<div class="th" style="text-align: right;" id="grand_total">0</div>
							<div class="th"></div>
							<div class="th"></div>
							<input type="hidden" name="grand_total" class="form-control" id="hidden_grand_total" value="0"/>
						</div>
					</table>
					<!-- <?= form_close(); ?> -->
				</div>
				</form>	
				<div id="modal_claim_list" class="modal fade modal_claim" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;">
					
					<div class="modal-dialog modal-dialog_cliam">
						<div class="modal-content">
							<header class="panel-heading">
								<h2 class="panel-title">Claim List</h2>
							</header>
							<!-- form id="form_credit_note"> -->
							<div class="modal-body">
								<table class="table table-bordered table-striped table-condensed mb-none" id="claim_list_table">
									<tr style="background-color:white;">
										<th class="text-center" style="vertical-align: middle;"></th>
										<th class="text-center" style="vertical-align: middle;">Date</th>
										<th class="text-center" style="vertical-align: middle;">Type</th>
										<th class="text-center" style="vertical-align: middle;">Description</th>
										<th class="text-center" style="vertical-align: middle;">Currency</th>
										<th class="text-center" style="vertical-align: middle;">Amount</th>
									</tr>
									<tbody id="claim_info">
									</tbody>
									<tbody id="claim_total">
										<tr>
											<td colspan="5"></td>
											<td style="text-align: right"><div class="total_selected">0</div></td>
										</tr>
									</tbody>
								</table>
							</div>
							<!-- </form> -->
							<div class="modal-footer">
								<button type="button" class="btn btn-primary" name="selectClaimList" id="selectClaimList">Select</button>
								<input type="button" class="btn btn-default " data-dismiss="modal" name="cancelClaimList" value="Cancel">
							</div>
						</div>
					</div>
					
				</div>
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-12 number text-right">
							<!--button class="btn btn-primary modal-confirm">Confirm</button-->
							<button type="submit" class="btn btn-primary" name="saveBilling" id="saveBilling">Save</button>
							<a href="<?= base_url();?>billings" class="btn btn-default">Back</a>
						</div>
					</div>
				</footer>
			</section>
						
		</div>
	</div>
	<div class="loading" id='loadingBilling'>Loading&#8230;</div>
	<!-- Progress Billing Modal -->
	<div class="modal fade" id="progressBillingModalScrollable" tabindex="-1" role="dialog" aria-labelledby="progressBillingModalScrollableTitle" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-scrollable" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="progressBillingModalScrollableTitle">History for Progress Billing</h5>
	        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"> -->
	          <!-- <span aria-hidden="true">&times;</span> -->
	        <!-- </button> -->
	      </div>
	      <div class="modal-body">
	      	<table class="table table-bordered table-striped table-condensed mb-none">
				<tr style="background-color:white;">
					<th class="text-center" style="vertical-align: middle;">No</th>
					<th class="text-center" style="vertical-align: middle;">Invoice No</th>
					<th class="text-center" style="vertical-align: middle;">Services</th>
					<th class="text-center" style="vertical-align: middle;">POC</th>
					<th class="text-center" style="vertical-align: middle;">Period Start Date</th>
					<th class="text-center" style="vertical-align: middle;">Period End Date</th>
					<th class="text-center" style="vertical-align: middle;">Amount</th>
				</tr>
				<tbody id="tbody_poc_info" class="tbody_poc_info">
				</tbody>
			</table>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
	      </div>
	    </div>
	  </div>
	</div>
<!-- end: page -->
</section>
<script>
	var billing_top_info = <?php echo json_encode(isset($edit_bill)?$edit_bill:null) ?>;
	var billing_below_info = <?php echo json_encode(isset($edit_bill_service)?$edit_bill_service:null) ?>;
	var service_dropdown = <?php echo json_encode(isset($get_client_billing_info)?$get_client_billing_info:'') ?>;
	var service_category = <?php echo json_encode(isset($get_service_category)?$get_service_category:'') ?>;
	var get_unit_pricing = <?php echo json_encode(isset($get_unit_pricing)?$get_unit_pricing:'') ?>;
	var access_right_billing_module = <?php echo json_encode($billing_module);?>;
	var access_right_unpaid_module = <?php echo json_encode($unpaid_module);?>;
	var firm_info = <?php echo json_encode(isset($firm_info) ? $firm_info : '');?>;
	var get_assignment_result = <?php echo json_encode(isset($assignment_result)?$assignment_result:'') ?>;

	if(access_right_billing_module == "read" || access_right_unpaid_module == "read")
	{
		$('input').attr("disabled", true);
		$('button').attr("disabled", true);
		$('select').attr("disabled", true);

	}
	$("#header_our_firm").removeClass("header_disabled");
	$("#header_manage_user").removeClass("header_disabled");
	$("#header_access_right").removeClass("header_disabled");
	$("#header_user_profile").removeClass("header_disabled");
	$("#header_setting").removeClass("header_disabled");
	$("#header_dashboard").removeClass("header_disabled");
	$("#header_client").removeClass("header_disabled");
	$("#header_person").removeClass("header_disabled");
	$("#header_document").removeClass("header_disabled");
	$("#header_report").removeClass("header_disabled");
	$("#header_billings").addClass("header_disabled");
</script>
<script src="themes/default/assets/js/create_billing.js?v=9434fdfsdfdrw32323233w1323" charset="utf-8"></script>