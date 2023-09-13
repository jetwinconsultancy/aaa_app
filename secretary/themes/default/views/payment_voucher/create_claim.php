<section class="panel" style="margin-top: 30px;">
	<?php echo $breadcrumbs;?>
	<div class="panel-body">
		<div class="col-md-12">
			
			<section class="panel">
				<form id="create_claim_form" enctype="multipart/form-data">
				<div class="panel-body">
					<!-- <input type="hidden" name="gst_rate" class="form-control" id="gst_rate" value=""/> -->
					<table class="table table-bordered table-striped table-condensed mb-none">
						<tr>
							<th>Payment No</th>
							<td><div class="validate" style="width: 50%;"><input type="text" name="claim_no" class="form-control" id="claim_no" value="" /><input type="hidden" name="previous_claim_no" class="form-control" id="previous_claim_no" value="" /></div></td><!-- disabled -->
						</tr>
						<tr>
							<th>Date</th>
							<td>
								<div class="claim_date_div">
								<div class="input-group" id="claim_date" style="width: 30%;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="claim_date form-control" name="claim_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker required value="">
								</div>
							</td>
						</tr>
						<tr>
							<th>User Name</th>
							<td>
								<div class="input-group dropdown_user_name" style="width: 100%; display: none">
									<select id="user_name" class="form-control user_name" style="width: 100%;" name="user_name">
						                    <option value="0">Select User Name</option>
						            </select>
						        </div>
						        <div class="input_user_name" style="width: 100%; display: none">
									<input type="text" id="text_user_name" class="form-control text_user_name" style="width: 100%;" name="text_user_name">
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
						<!-- <tr>
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
								</div>
							</td>
						</tr> -->
						<tr>
							<th>Bank Account</th>
							<td>
								<div class="input-group dropdown_bank_account" style="width: 100%">
									<select id="bank_account" class="form-control bank_account" style="width: 100%;" name="bank_account" disabled="true">
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
										<input type="text" name="cheque_number" class="form-control cheque_number" id="cheque_number" value="" readonly="true" />
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<th>Remarks</th>
							<td>
								<div class="rate-input-group remarks" style="color: red">
								</div>
							</td>
						</tr>
					</table>
					<br/>
					
					<table class="table table-bordered table-striped mb-none" style="width: 100%">
						<thead>
							<div class="tr" id="create_claim_service">
								<div class="th" valign=middle style="width:2%">Type</div>
								<div class="th" valign=middle style="width:2%">Client</div>
								<div class="th" valign=middle style="width:1%">Payment Description</div>
								<div class="th" style="width:1%">Amount</div>
								<div class="th">Attachment</div>
								<a href="javascript: void(0);" class="th" style="color: #D9A200; width:5%; outline: none !important;text-decoration: none;"><span id="claim_service_info_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Payment Voucher" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add Line</span></a>
							</div>
							
						</thead>
						

						<div class="tbody" id="body_create_claim">
							

						</div>
						<!-- <div class="tr" id="sub_total_create_claim">
							<div class="th">Sub-Total</div>
							<div class="th"></div>
							<div class="th" style="text-align: right;" id="sub_total">0</div>
							<div class="th"></div>
						</div>
						<div class="tr" id="gst_create_claim">
							<div class="th">GST <span id="gst_with_rate"></span></div>
							<div class="th"></div>
							<div class="th" style="text-align: right;" id="gst">0</div>
							<div class="th"></div>
						</div> -->
						<div class="tr" id="grand_total_create_claim">
							<div class="th">Grand Total</div>
							<div class="th"></div>
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
							<button type="submit" class="btn btn-primary" name="saveClaim" id="saveClaim">Save</button>
							<a href="<?= base_url();?>payment_voucher" class="btn btn-default">Back</a>
						</div>
					</div>
				</footer>
			</section>
						
		</div>
	</div>
	<div class="loading" id='loadingPaymentVoucher'>Loading&#8230;</div>
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
	
	var claim_top_info = <?php echo json_encode(isset($edit_claim)?$edit_claim:null) ?>;
	var claim_below_info = <?php echo json_encode(isset($edit_claim_service)?$edit_claim_service:null) ?>;
	var type_array = <?php echo json_encode(isset($type_list)?$type_list:'') ?>;
	var client_array = <?php echo json_encode(isset($client_list)?$client_list:'') ?>;
	var claim_for_transport_query = <?php echo json_encode(isset($claim_for_transport_query)?$claim_for_transport_query:'') ?>;
	var base_url = "<?= base_url();?>";
	var firm_info = <?php echo json_encode(isset($firm_info) ? $firm_info : '');?>;
	//console.log(claim_for_transport_query);
	if(claim_top_info != null)
	{
		if(claim_top_info[0]['status'] == 2 || claim_top_info[0]['status'] == 3 || claim_top_info[0]['status'] == 4)
		{
			$("#claim_service_info_Add").remove();
			$("#create_claim_form :input").prop('readonly', true);
			$('#create_claim_form select').attr('disabled', true);
			$('.panel-footer').hide();
		}
	}
</script>
<script src="themes/default/assets/js/create_claim.js?v=50eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>