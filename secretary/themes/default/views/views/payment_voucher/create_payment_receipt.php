<section class="panel" style="margin-top: 30px;">
	<?php echo $breadcrumbs;?>
	<div class="panel-body">
		<div class="col-md-12">
			
			<section class="panel">
				<form id="create_payment_receipt_form" enctype="multipart/form-data">
				<div class="panel-body">
					<!-- <input type="hidden" name="gst_rate" class="form-control" id="gst_rate" value=""/> -->
					<table class="table table-bordered table-striped table-condensed mb-none">
						<tr>
							<th>Receipt No</th>
							<td><div class="validate" style="width: 50%;"><input type="text" name="payment_receipt_no" class="form-control" id="payment_receipt_no" value="" /><input type="hidden" name="previous_payment_receipt_no" class="form-control" id="previous_payment_receipt_no" value="" /></div></td><!-- disabled -->
						</tr>
						<tr>
							<th>Date</th>
							<td>
								<div class="payment_receipt_date_div">
								<div class="input-group" id="payment_receipt_date" style="width: 30%;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="payment_receipt_date form-control" name="payment_receipt_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker required value="">
								</div>
							</td>
						</tr>
						<tr>
							<th>Client Name</th>
							<td>
						        <div class="input_client_name" style="width: 100%;">
									<input type="text" id="client_name" class="form-control client_name" style="width: 100%;" name="client_name">
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
									<!-- <input type="hidden" name="hidden_postal_code">
									<input type="hidden" name="hidden_street_name">
									<input type="hidden" name="hidden_building_name">
									<input type="hidden" name="hidden_unit_no1">
									<input type="hidden" name="hidden_unit_no2"> -->
								</div>
							</td>
						</tr>
						<tr>
							<th>Bank Account</th>
							<td>
								<div class="input-group dropdown_bank_account" style="width: 100%">
									<select id="bank_account" class="form-control bank_account" style="width: 100%;" name="bank_account">
						                    <option value="0">Select Bank Account</option>
						            </select>
						        </div>
							</td>
						</tr>
						<tr>
							<th>Cheque Number</th>
							<td>
								<div class="rate-input-group">
									<div class="validate" style="width: 50%;">
										<input type="text" name="cheque_number" class="form-control cheque_number" id="cheque_number" value=""/>
									</div>
								</div>
							</td>
						</tr>
					</table>
					<br/>
					
					<table class="table table-bordered table-striped mb-none" style="width: 100%">
						<thead>
							<div class="tr" id="create_payment_receipt_service">
								<div class="th" valign=middle style="width:2%">Type</div>
								<div class="th" valign=middle style="width:1%">Payment Description</div>
								<div class="th" style="width:1%">Amount</div>
								<div class="th">Attachment</div>
								<a href="javascript: void(0);" class="th" style="color: #D9A200; width:5%; outline: none !important;text-decoration: none;"><span id="payment_receipt_service_info_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Payment Voucher" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add Line</span></a>
							</div>
							
						</thead>
						

						<div class="tbody" id="body_create_payment_receipt">
							

						</div>
						<div class="tr" id="grand_total_create_payment_receipt">
							<div class="th">Grand Total</div>
							<div class="th"></div>
							<div class="th" style="text-align: right;" id="grand_total">0</div>
							<div class="th"></div>
							<div class="th"></div>
							<input type="hidden" name="grand_total" class="form-control" id="hidden_grand_total" value="0"/>
						</div>
					</table>
				</div>
				</form>	
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-12 number text-right">
							<!--button class="btn btn-primary modal-confirm">Confirm</button-->
							<button type="submit" class="btn btn-primary" name="savePaymentReceipt" id="savePaymentReceipt">Save</button>
							<a href="<?= base_url();?>payment_voucher" class="btn btn-default">Back</a>
						</div>
					</div>
				</footer>
			</section>
						
		</div>
	</div>
	<div class="loading" id='loadingPaymentReceipt'>Loading&#8230;</div>
<!-- end: page -->
</section>
<script>

	$("#header_our_firm").removeClass("header_disabled");
   	$("#header_manage_user").removeClass("header_disabled");
  	$("#header_access_right").removeClass("header_disabled");
  	$("#header_client_access").removeClass("header_disabled");
  	$("#header_user_profile").removeClass("header_disabled");
  	$("#header_setting").removeClass("header_disabled");
 	$("#header_dashboard").removeClass("header_disabled");
  	$("#header_client").removeClass("header_disabled");
  	$("#header_person").removeClass("header_disabled");
 	$("#header_document").removeClass("header_disabled");
 	$("#header_report").removeClass("header_disabled");
	$("#header_billings").removeClass("header_disabled");
	$("#header_payment_voucher").addClass("header_disabled");
	
	var payment_receipt_top_info = <?php echo json_encode(isset($edit_payment_receipt)?$edit_payment_receipt:null) ?>;
	var payment_receipt_below_info = <?php echo json_encode(isset($edit_payment_receipt_service)?$edit_payment_receipt_service:null) ?>;
	var type_array = <?php echo json_encode(isset($type_list)?$type_list:null) ?>;
	var base_url = "<?= base_url();?>";
	var firm_info = <?php echo json_encode(isset($firm_info) ? $firm_info : '');?>;

	if(payment_receipt_top_info != null)
	{
		if(payment_receipt_top_info[0]['status'] == 2 || payment_receipt_top_info[0]['status'] == 3 || payment_receipt_top_info[0]['status'] == 4)
		{
			$("#payment_receipt_service_info_Add").remove();
			$("#create_payment_receipt_form :input").prop('readonly', true);
			$('#create_payment_receipt_form select').attr('disabled', true);
			$('.panel-footer').hide();
		}
	}
</script>
<script src="themes/default/assets/js/create_payment_receipt.js?v=40eee4fc8wewqewqeqewss082" charset="utf-8"></script>