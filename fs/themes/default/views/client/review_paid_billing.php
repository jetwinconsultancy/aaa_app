<section class="panel" style="margin-top: 30px;">
	<?php echo $breadcrumbs;?>
	<div class="panel-body">
		<div class="col-md-12">
			
			<section class="panel">
				<form id="create_billing_form">
				<div class="panel-body">
					<!-- <?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form', 'id' => 'create_billing_form');
						echo form_open_multipart("masterclient/add_client_billing_info", $attrib);
						// print_r($oppicer);
					?> -->
					<input type="hidden" name="gst_rate" class="form-control" id="gst_rate" value=""/>
					<table class="table table-bordered table-striped table-condensed mb-none">
						<tr>
							<th style="padding: 10px;">Invoice No</th>
							<td style="padding: 10px;"><span id="invoice_no"/></span></td>
						</tr>
						<tr>
							<th style="padding: 10px;">Date</th>
							<td style="padding: 10px;">
								<span id="billing_date"></span>

							</td>
						</tr>
						<tr>
							<!-- <?php echo json_encode($edit_bill_service) ?>

							<?php echo json_encode($get_client_billing_info) ?> -->
							<th style="padding: 10px;">Client Name</th>
							<td style="padding: 10px;">
								<div class="input-group" style="width: 100%;">
									<span id="client_name"></span>
									
						        </div>
								<!-- <div class="input-group" style="width: 100%;"><input type="text" name="client_name" class="form-control" id="client_name" value=""/></div> -->
								<!-- <select data-plugin-selectTwo class="form-control input-sm populate" style="width: 200px;">
									<optgroup label="Company">
										<option value="user 1">Company 1</option>
										<option value="user 2">Company 2</option>
										<option value="user 3">Company 3</option>
										<option value="user 3">XX 3</option>
									</optgroup>
								</select> -->
							</td>
						</tr>
						<tr>
							<th style="padding: 10px;">Currency</th>
							<td style="padding: 10px;">
								<div class="input-group" style="width: 100%;">
									<span id="currency_name"></span>
									
						        </div>
								<!-- <select data-plugin-selectTwo class="form-control input-sm populate" style="width: 200px;">
									<optgroup label="Company">
										<option value="user 1">SGD</option>
										<option value="user 2">USD</option>
										<option value="user 3">RM</option>
										<option value="user 3">IDR</option>
									</optgroup>
								</select> -->
							</td>
						</tr>
						<tr>
							<th style="padding: 10px;">Rate</th>
							<td style="padding: 10px;">
								<div class="rate-input-group">
									<span id="rate"></span>
								</div>
							</td>
						</tr>
						<tr>
							<th style="padding: 10px;">Address</th>
							<td style="padding-right: 10px;padding-left: 10px;padding-bottom: 10px;padding-top: -10px !important; ">
								<span style="white-space: pre-line" id="address"></span>
							</td>
						</tr>
						<!-- <tr>
							<th>Attention</th>
							<td>
								<div class="input-group" style="width: 100%;"><input type="text" name="attention" class="form-control" id="attention" value=""/></div> -->
								<!-- <select data-plugin-selectTwo class="form-control input-sm populate" style="width: 200px;">
									<optgroup label="Company">
										<option value="user 1">Company 1</option>
										<option value="user 2">Company 2</option>
										<option value="user 3">Company 3</option>
										<option value="user 3">XX 3</option>
									</optgroup>
								</select> -->
							<!-- </td>
						</tr> -->
					</table>
					<br/>
					<!-- <table class="table table-condensed mb-none">
					<tr>
						<td>
							<select class="form-control">
								<option value="">Service 1 </option>
								<option value="">Service 2 </option>
								<option value="">Service 3 </option>
								<option value="">Service 4 </option>
							</select>
						</td>
						<td style="vertical-align:middle;">
							<a  class="pointer mb-sm mt-sm mr-sm" style="font-size:16px;font-weigth:bold">Add</a>
						</td>
					</tr>
					</table>
					<br/> -->
					<!-- <table class="table table-bordered table-striped table-condensed mb-none">
						<tr>
							<th>No.</th>
							<th>Service</th>
							<th>Amount</th>
						</tr>
						<tr>
							<td>1</td>
							<td width="80%">Service 1</td>
							<td><input type="text" class="numberdes text-right input-sm" value="10.000,00"/></td>
						</tr>
						<tr>
							<td>2</td>
							<td>Service 2</td>
							<td><input type="text" class="numberdes text-right input-sm" value="15.000,00"/></td>
						</tr>
						<tr>
							<td>3</td>
							<td>Service 3</td>
							<td><input type="text" class="numberdes text-right input-sm" value="20.000,00"/></td>
						</tr>
						<tr>
							<td colspan=2>Total</td>
							<td class="text-right">45.000</td>
						</tr>
					</table> -->
					<table class="table table-bordered table-striped mb-none" style="width: 100%;">
						<thead>
							<div class="tr" id="create_billing_service" style="display:none;">
								<!-- <div class="th" valign=middle>No</div> -->
								<div class="th" valign=middle style="width:30%;padding: 10px;">Service</div>
								<div class="th" valign=middle style="width:60%;padding: 10px;">Invoice Description</div>
								<div class="th" style="width:200px;padding: 10px;">Amount</div>
								<!-- <div class="th" style="width:190px">Recurring</div>
								<div class="th" style="width:150px">Frequency</div> -->
								<!-- <div class="th" style="font-size:25px;"><span id="billing_service_info_Add"><i class="fa fa-plus-circle"></i></span></div> -->

								<!-- <a href="javascript: void(0);" class="th" style="color: #D9A200; width:10%; outline: none !important;text-decoration: none;"><span id="billing_service_info_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Billing" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add Billing</span></a> -->
							</div>
							
						</thead>
						

						<div class="tbody" id="body_create_billing" style="display:none;">
							

						</div>
						<div class="tr" id="sub_total_create_billing" style="display:none;">
						<!-- <div class="th" valign=middle>No</div> -->
							<div class="th">Sub-Total</div>
							<div class="th"></div>
							<div class="th" style="text-align: right;" id="sub_total">0</div>
							<!-- <div class="th"></div> -->
						</div>
						<div class="tr" id="gst_create_billing" style="display:none;">
						<!-- <div class="th" valign=middle>No</div> -->
							<div class="th">GST <span id="gst_with_rate"></span></div>
							<div class="th"></div>
							<div class="th" style="text-align: right;" id="gst">0</div>
							<!-- <div class="th"></div> -->
						</div>
						<div class="tr" id="grand_total_create_billing" style="display:none;">
						<!-- <div class="th" valign=middle>No</div> -->
							<div class="th">Grand Total</div>
							<div class="th"></div>
							<div class="th" style="text-align: right;" id="grand_total">0</div>
							<!-- <div class="th"></div> -->
							<input type="hidden" name="grand_total" class="form-control" id="hidden_grand_total" value="0"/>
						</div>
					</table>
					<!-- <?= form_close(); ?> -->
				</div>
				</form>	
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-12 number text-right">
							<!--button class="btn btn-primary modal-confirm">Confirm</button-->
							<!-- <button type="submit" class="btn btn-primary" name="saveBilling" id="saveBilling">Save</button> -->
							<a href="<?= base_url();?>billings" class="btn btn-default">Back</a>
						</div>
					</div>
				</footer>
			</section>
						
		</div>
	</div>
	
<!-- end: page -->
</section>
<script>
	var billing_top_info = <?php echo json_encode($edit_bill) ?>;
	var billing_below_info = <?php echo json_encode($edit_bill_service) ?>;
	var service_dropdown = <?php echo json_encode($get_client_billing_info) ?>;
	var access_right_billing_module = <?php echo json_encode($billing_module);?>;
	var access_right_unpaid_module = <?php echo json_encode($unpaid_module);?>;

	if(access_right_billing_module == "read" || access_right_unpaid_module == "read")
	{
		$('input').attr("disabled", true);
		$('button').attr("disabled", true);
		$('select').attr("disabled", true);

	}
</script>
<script src="themes/default/assets/js/review_paid_billing.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>