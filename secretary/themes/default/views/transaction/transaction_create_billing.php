<section class="panel transaction_create_billing_interface" style="margin-top: 30px;">
	<header class="panel-heading">
		<div class="panel-actions" style="height:80px">

			<a href="#" data-toggle="modal" class="open_client_billing amber" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Create Billing" ><i class="fa fa-plus-circle  amber" style="font-size:16px;height:45px;"></i> Create Billing</a>
		</div>
		<h2></h2>
			
	</header>
	<div class="panel-body">
		<!-- <h3>Create Billing</h3> -->
		<div class="col-md-12">
			<!-- <section class="panel">
				<form id="transaction_create_billing_form">
				<div class="panel-body">
					<input type="hidden" name="gst_rate" class="form-control" id="gst_rate" value=""/>
					<table class="table table-bordered table-striped table-condensed mb-none">
						<tr>
							<th>Invoice No</th>
							<td><div class="validate" style="width: 50%;"><input type="text" name="invoice_no" class="form-control" id="invoice_no" value="" /><input type="hidden" name="previous_invoice_no" class="form-control" id="previous_invoice_no" value="" /></div></td>
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
						        <div class="input_client_name" style="width: 100%;">
									<input type="text" id="transaction_client_name" class="form-control transaction_client_name" style="width: 100%;" name="transaction_client_name">
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
								<a href="javascript: void(0);" class="th" style="color: #D9A200; width:5%; outline: none !important;text-decoration: none;"><span id="transaction_billing_service_info_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Billing" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add Line</span></a>
							</div>
						</thead>
						<div class="tbody" id="body_create_billing" style="display:none;">
						</div>
						<div class="tr" id="sub_total_create_billing" style="display:none;">
							<div class="th">Sub-Total</div>
							<div class="th"></div>
							<div class="th" style="text-align: right;" id="sub_total">0</div>
							<div class="th"></div>
							<div class="th"></div>
						</div>
						<div class="tr" id="gst_create_billing" style="display:none;">
							<div class="th">GST <span id="gst_with_rate"></span></div>
							<div class="th"></div>
							<div class="th" style="text-align: right;" id="gst">0</div>
							<div class="th"></div>
							<div class="th"></div>
						</div>
						<div class="tr" id="grand_total_create_billing" style="display:none;">
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
							<button type="submit" class="btn btn-primary" name="saveBilling" id="saveBilling">Save</button>
							
						</div>
					</div>
				</footer>
			</section> -->

			<table class="table table-bordered table-striped mb-none" id="datatable-paid">
				<thead><tr>
					<th style="text-align: center">No</th>
					<th style="text-align: center">Company Name</th>
					<th style="text-align: center">Invoice Date</th>
					<th style="text-align: center">Invoice No</th>
					<th style="text-align: center">Currency</th>
					<th style="text-align: center">Amount</th>
					<th style="text-align: center">Outstanding</th>
					<th style="text-align: center">Receipt No</th>
					<th style="text-align: center">Status</th>
					<th style="text-align: center; width: 225px;"></th>
				</tr>
				</thead>
				<tbody id="billing_body">
					<!-- <?php
						$i = 1;
						foreach($billings as $bill)
						{
							$date_1 = $bill->invoice_date;
							$array = explode('/', $date_1);
							$tmp = $array[0];
							$array[0] = $array[1];
							$array[1] = $tmp;
							unset($tmp);
							$date_2 = implode('/', $array);
					?>
					<tr>
						
						<td style="text-align: right"></td>
						<td><?=$bill->company_name?></td>
						<td style="text-align: center"><span style="display:none"><?=date("Ymd",strtotime($date_2))?></span><?=$bill->invoice_date?></td>
						<td><a href="billings/edit_bill/<?=$bill->id?>" class="pointer mb-sm mt-sm mr-sm  "><?=$bill->invoice_no?></a></td>
						<td><?=$bill->currency_name?></td>
						<td class="text-right"><?=number_format($bill->amount,2)?></td>
						<td class="text-right"><?=number_format($bill->outstanding,2)?></td>
						<td><?=$bill->receipt_no?></td>
						<td>Unpaid</td>
						<td><a data-toggle="modal" data-code="<?=$bill->company_code?>" class="btn btn-primary open_reciept pointer mb-sm mr-sm">Receipt</a><a data-toggle="modal" data-code="<?=$bill->company_code?>" class="btn btn-primary open_credit_note pointer mb-sm mr-sm">Credit Note</a><a data-toggle="modal" data-code="<?=$bill->company?>" onclick="exportPDF(<?=$bill->id?>)" class="btn btn-primary p_code?>ointer mb-sm mr-sm">PDF</a><a style="" data-toggle="modal" data-code="<?=$bill->company_code?>" onclick="deleteBilling(<?=$bill->id?>)" class="btn btn-primary pointer mb-sm">Delete</a></td>
					</tr>
					<?php
						$i++;
							}
					?>
					<?php
						foreach($paid_billings as $paid_bill)
						{
							$date_1 = $paid_bill->invoice_date;
							$array = explode('/', $date_1);
							$tmp = $array[0];
							$array[0] = $array[1];
							$array[1] = $tmp;
							unset($tmp);
							$date_2 = implode('/', $array);
					?>
					<tr>
						<td style="text-align: right"></td>
						<td><?=$paid_bill->company_name?></td>
						<td style="text-align: center"><span style="display:none"><?=date("Ymd",strtotime($date_2))?></span><?=$paid_bill->invoice_date?></td>
						<td><a href="billings/review_paid_bill/<?=$paid_bill->id?>" class="pointer mb-sm mt-sm mr-sm  "><?=$paid_bill->invoice_no?></a></td>
						<td><?=$paid_bill->currency_name?></td>
						<td style="text-align: right"><?=number_format($paid_bill->amount,2)?></td>
						<td style="text-align: right"><?=number_format($paid_bill->outstanding,2)?></td>
						<td><?=$paid_bill->receipt_no?></td>
						<td>Paid</td>
						<td><a data-toggle="modal" data-code="<?=$bill->company_code?>" onclick="exportPDF(<?=$paid_bill->id?>)" class="btn btn-primary pointer mb-sm mr-sm">PDF</a></td>
					</tr>
					<?php
						$i++;
						}
						
					?> -->
				</tbody>
			</table>		
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
	<div id="modal_billing" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;">
					
		<div class="modal-dialog" style="width: 1250px !important;">
			<div class="modal-content">
				<header class="panel-heading">
					<h2 class="panel-title">Create Billing</h2>
				</header>
				<form id="transaction_create_billing_form">
					<div class="panel-body">
						<input type="hidden" name="old_gst_rate" class="form-control" id="old_gst_rate" value=""/>
						<table class="table table-bordered table-striped table-condensed mb-none">
							<tr class="row_of_invoice_no" style="display: none">
								<th>Invoice No</th>
								<td><div class="validate" style="width: 50%;"><input type="text" name="invoice_no" class="form-control" id="invoice_no" value="" /><input type="hidden" name="previous_invoice_no" class="form-control" id="previous_invoice_no" value="" /></div></td>
							</tr>
							<tr>
								<th>Date</th>
								<td>
									<div class="billing_date_div">
									<div class="input-group" id="billing_date" style="width: 30%;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="billing_date form-control" name="billing_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker required value="">
									</div>
								</td>
							</tr>
							<tr class="text_field_client_name">
								<th>Client Name</th>
								<td>
							        <div class="input_client_name" style="width: 100%;">
										<input type="text" id="transaction_client_name" class="form-control transaction_client_name" style="width: 100%;" name="transaction_client_name">
							        </div>
								</td>
							</tr>
							<tr class="dropdown_client_name" style="display: none">
								<th>Client Name</th>
								<td>
									<div class="input-group" style="width: 100%;">
										<select id="transaction_drop_client_name" class="form-control transaction_drop_client_name" style="width: 100%;" name="transaction_drop_client_name">
							                    <option value="0">Select Client Name</option>
							            </select>
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
									<a href="javascript: void(0);" class="th" style="color: #D9A200; width:5%; outline: none !important;text-decoration: none;"><span id="transaction_billing_service_info_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Billing" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add Line</span></a>
								</div>
							</thead>
							<div class="tbody" id="body_create_billing" style="display:none;">
							</div>
							<div class="tr" id="sub_total_create_billing" style="display:none;">
								<div class="th">Sub-Total</div>
								<div class="th"></div>
								<div class="th" style="text-align: right;" id="sub_total">0</div>
								<div class="th"></div>
								<div class="th"></div>
							</div>
							<div class="tr" id="gst_create_billing" style="display:none;">
								<div class="th">GST <span id="gst_with_rate"></span></div>
								<div class="th"></div>
								<div class="th" style="text-align: right;" id="gst">0</div>
								<div class="th"></div>
								<div class="th"></div>
							</div>
							<div class="tr" id="grand_total_create_billing" style="display:none;">
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
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary" name="saveBilling" id="saveBilling">Save</button>
					<input type="button" class="btn btn-default " data-dismiss="modal" name="cancel_billing" value="Cancel">
				</div>
			</div>
		</div>
	</div>
<!-- end: page -->
<script src="themes/default/assets/js/transaction_create_billing.js?v=5sadsde212dafdfdsdfdf" charset="utf-8"></script>
</section>

