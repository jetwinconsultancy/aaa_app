<section class="panel" style="margin-top: 30px;">
	<?php echo $breadcrumbs;?>
	<div class="panel-body">
		<div class="col-md-12">
			
			<section class="panel">
				<form id="create_recurring_form">
				<div class="panel-body">
					<!-- <?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form', 'id' => 'create_recurring_form');
						echo form_open_multipart("masterclient/add_client_recurring_info", $attrib);
						// print_r($oppicer);
					?> -->
					<input type="hidden" name="gst_rate" class="form-control" id="gst_rate" value=""/>
					<table class="table table-bordered table-striped table-condensed mb-none">
						<tr>
							<th>Recurring No</th>
							<td><div class="validate" style="width: 50%;"><input type="text" name="invoice_no" class="form-control" id="invoice_no" value="" disabled /></div></td>
						</tr>
						<tr>
							<th>Date</th>
							<td>
								<div class="billing_date_div">
								<div class="input-group" id="billing_date" style="width: 30%;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="billing_date form-control" name="billing_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker required value="">
									<!-- <?php $now = getDate();echo $now['mday'].'/'.$now['mon']."/".$now['year'];?> -->
								</div>
							</td>
						</tr>
						<tr>
							<!-- <?php echo json_encode($edit_bill_service) ?>

							<?php echo json_encode($get_client_recurring_info) ?> -->
							<th>Client Name</th>
							<td>
								<div class="input-group" style="width: 100%;">
									<select id="client_name" class="form-control client_name" style="width: 100%;" name="client_name">
						                    <option value="0">Select Client Name</option>
						            </select>
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
							<th>Currency</th>
							<td>
								<div class="input-group" style="width: 100%;">
									<select id="currency" class="form-control currency" style="width: 100%;" name="currency">
						                    <option value="0">Select Currency</option>
						            </select>
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
								</div>
							</td>
						</tr>
						<tr>
							<th>Recurring Status</th>
							<td>
								<div style="margin-right:5px;float: left">
							        <input type="checkbox" name="recuring_checkbox" <?=$edit_recurring_bill[0]->recurring_status?'checked':'';?>/>
                                    <input type="hidden" name="hidden_recurring_checkbox" value=""/>
							    </div>
							</td>
						</tr>
						<tr>
							<th style="width: 230px;">Billed issued on</th>
							<td>
								<div class="period-input-group">
									<select class="form-control" style="text-align:right;width: 200px;" name="frequency" id="frequency" onchange="optionCheckService(this);"><option value="0" >Select Frequency</option></select>
								</div>
							</td>
						</tr>
						<!-- <tr class="recurring_part" style="display: none">
							<th>Invoice Issued</th>
							<td>
								<div class="period-input-group">
									<select class="form-control" style="text-align:right;width: 200px; display: inline !important;" name="type_of_day" id="type_of_day" disabled>
										<option value="0">Select Day</option>
									</select> 
									<input type="text" name="days" class="form-control" value="" id="days" style="width:80px; display: inline !important;" disabled/> days
								</div>
								<div class="help-block remark"></div>
							</td>
						</tr> -->
						<tr class="recurring_part" style="display: none">
							<th style="width: 230px;">Recurring Invoice Issues Date</th>
							<td>
								<div class="period-input-group">
									<div class="input-group" id="from_datepicker">
										<span class="input-group-addon">
											<i class="far fa-calendar-alt"></i>
										</span>
										<input type="text" class="form-control datepicker recurring_issue_date_datepicker" id="recurring_issue_date" name="recurring_issue_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker value="" disabled style="width: 30%;">
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<th>Pre-printed Letterhead</th>
							<td>
								<div style="width:400px">
								    <div style="margin-right:5px;float: left">
								        <input type="checkbox" name="own_letterhead_checkbox"/>
                                        <input type="hidden" name="hidden_own_letterhead_checkbox" value=""/>
								    </div>
								</div>
							</td>
						</tr>
						<!-- <tr class="recurring_part" style="display: none">
							<th>Recurring End Date</th>
							<td>
								<div class="period-input-group">
									<div class="input-group" id="to_datepicker">
										<span class="input-group-addon">
											<i class="far fa-calendar-alt"></i>
										</span>
										<input type="text" class="form-control datepicker to_datepicker" id="to" name="to" data-date-format="dd/mm/yyyy" data-plugin-datepicker value="" disabled>
									</div>
								</div>
							</td>
						</tr> -->
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
					<table class="table table-bordered table-striped mb-none" style="width: 100%">
						<thead>
							<div class="tr" id="create_recurring_service" style="display:none;">
								<!-- <div class="th" valign=middle>No</div> -->
								<div class="th" valign=middle style="width:2%">Service</div>
								<div class="th" valign=middle style="width:1%">Invoice Description</div>
								<div class="th" style="width:1%">Amount</div>
								<div class="th" style="width:1%">Unit Pricing</div>
								<!-- <div class="th" style="width:190px">Recurring</div>
								<div class="th" style="width:150px">Frequency</div> -->
								<!-- <div class="th" style="font-size:25px;"><span id="recurring_service_info_Add"><i class="fa fa-plus-circle"></i></span></div> -->

								<a href="javascript: void(0);" class="th" style="color: #D9A200; width:5%; outline: none !important;text-decoration: none;"><span id="recurring_service_info_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Billing" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add Line</span></a>
							</div>
							
						</thead>
						

						<div class="tbody" id="body_create_recurring" style="display:none;">
							

						</div>
						<div class="tr" id="sub_total_create_recurring" style="display:none;">
						<!-- <div class="th" valign=middle>No</div> -->
							<div class="th">Sub-Total</div>
							<div class="th"></div>
							<div class="th" style="text-align: right;" id="sub_total">0</div>
							<div class="th"></div>
							<div class="th"></div>
						</div>
						<div class="tr" id="gst_create_recurring" style="display:none;">
						<!-- <div class="th" valign=middle>No</div> -->
							<div class="th">GST <span id="gst_with_rate"></span></div>
							<div class="th"></div>
							<div class="th" style="text-align: right;" id="gst">0</div>
							<div class="th"></div>
							<div class="th"></div>
						</div>
						<div class="tr" id="grand_total_create_recurring" style="display:none;">
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
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-12 number text-right">
							<!--button class="btn btn-primary modal-confirm">Confirm</button-->
							<button type="submit" class="btn btn-primary" name="saveRecurring" id="saveRecurring">Save</button>
							<a href="<?= base_url();?>billings" class="btn btn-default">Back</a>
						</div>
					</div>
				</footer>
			</section>
						
		</div>
	</div>
	<div class="loading" id='loadingBilling'>Loading&#8230;</div>
<!-- end: page -->
</section>
<script>
	var recurring_top_info = <?php echo json_encode($edit_recurring_bill) ?>;
	var recurring_below_info = <?php echo json_encode($edit_recurring_bill_service) ?>;
	var service_dropdown = <?php echo json_encode($get_client_recurring_billing_info) ?>;
	var service_category = <?php echo json_encode($get_service_category) ?>;
	var get_unit_pricing = <?php echo json_encode($get_unit_pricing) ?>;
	// var access_right_billing_module = <?php echo json_encode($billing_module);?>;
	// var access_right_unpaid_module = <?php echo json_encode($unpaid_module);?>;
	//console.log(service_dropdown);
	// if(access_right_billing_module == "read" || access_right_unpaid_module == "read")
	// {
	// 	$('input').attr("disabled", true);
	// 	$('button').attr("disabled", true);
	// 	$('select').attr("disabled", true);

	// }
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
<script src="themes/default/assets/js/create_recurring.js?v=30eee4fc8d1b59e4584b0d39edfb2022" charset="utf-8"></script>
<script type="text/javascript">
	$(document).ready(function(){
	    $('#body_create_recurring').find('.tr_recurring').each(function(){
	        console.log($(this).find(".period_start_date"));
	        var period_start_date = $(this).find(".period_start_date").val();
	        var period_end_date = $(this).find(".period_end_date").val();

	        console.log(period_start_date);
	        console.log(period_end_date);
	        console.log(recurring_top_info[0]['billing_period']);
	        if(recurring_top_info[0]['billing_period'] != 0 && recurring_top_info[0]['billing_period'] != 1 && $('#recurring_issue_date').val() != "" && period_start_date != "" && period_end_date != "")
	        {
	            changeRemark($(this), recurring_top_info[0]['billing_period'], $('#recurring_issue_date').val(),period_start_date, period_end_date);
	        }
	    });
	});
</script>