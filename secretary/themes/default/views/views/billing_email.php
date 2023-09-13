<div class="header_between_all_section">
	<section class="panel">
		<header class="panel-heading">
			<h2></h2>
		</header>
		<div class="panel-body">
			<div class="col-xs-12 col-sm-9 col-md-12">
				<div class="row datatables-header">
					<?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form', 'id' => 'form_search_billing');
						echo form_open_multipart("billings/billing_email", $attrib);
					?>
					<div class="col-sm-7">
						<div class="col-md-5">
							<select class="form-control" name="type">
								<option value="all" <?=isset($_POST['type'])?($_POST['type'] == "all"?'selected':''):''?>>All</option>
								<option value="company_name" <?=isset($_POST['type'])?($_POST['type']=='company_name'?'selected':''):''?>>Company Name</option>
								<option value="registration_no" <?=isset($_POST['type'])?($_POST['type'] == "registration_no"?'selected':''):''?>>Registration No</option>
							</select>
						</div>
						<div class="col-md-7 person_search_input">
							<input type="text" class="form-control billing_search" id="w2-username" name="search" placeholder="Search" value="<?=isset($_POST['search'])?$_POST['search']:'';?>">
							<input type="hidden" class="form-control submit_billing_check_state" name="billing_check_state" value="<?=isset($_POST['billing_check_state'])?$_POST['billing_check_state']:'billing';?>">
						</div>
						<div class="col-sm-8">
							<label class="control-label">Date range</label>
							<div class="input-daterange input-group" data-plugin-datepicker data-date-format="dd/mm/yyyy">
								<span class="input-group-addon">
									<i class="far fa-calendar-alt"></i>
								</span>
								<input type="text" class="form-control" name="start" value="<?=isset($_POST['start'])?$_POST['start']:'';?>">
								<span class="input-group-addon">to</span>
								<input type="text" class="form-control" name="end" value="<?=isset($_POST['end'])?$_POST['end']:'';?>">
							</div>
						</div>
						<!-- <div class="col-sm-4">
							<input type="button" class="btn btn-primary" onclick="exportStatement()" name="export_pdf" value="Export Statement" style="margin-top: 22px;">
						</div> -->
					</div>
					<?= form_close();?>
					<div class="col-md-5 search_group_button">
						<input type="submit" class="btn btn-primary" id="searchResult" name="searchResult" value="Search"/>
						<a href="billings" class="btn btn-primary">Show All Billings</a>
					</div>
				</div>
				<div class="billing_statement" style="text-align: right; font-weight: bold; font-size: 14px;">
					<div class="amber" style="display: inline-block; vertical-align:top">Total Statement Amount:</div>
					<div style="margin-right: 50px; color: red; display: inline-block; vertical-align:top" class="statement_amount"></div>
				</div>
				<div id="buttonclick">
					<div class="panel-body">
						<ul class="nav nav-tabs nav-justify">
							<?php if ($unpaid_module != 'none') { ?> 
								<li class="billing_check_state active" data-information="billing">
									<a href="#w2-billing" data-toggle="tab" class="text-center">
										<span class="badge hidden-xs">1</span>
										All
									</a>
								</li>
							<?php
								}
							?>
						</ul>
						<div class="tab-content">
							<?php if ($unpaid_module != 'none') { ?> 
							<div id="w2-billing" class="tab-pane active">
								<table class="table table-bordered table-striped mb-none" id="datatable-paid">
									<thead><tr>
										<!-- <th><input type="checkbox" name="selectall" class="selectall"/></th> -->
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
										<?php
											$i = 1;
											if($billings)
											{
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
											
											<td style="text-align: right"><!-- <label> &nbsp;<?=(int)$i?></label> --><!-- <input type="hidden" class="billing_checkbox" id="" name="billing_checkbox" value="<?=$bill->id?>"> --></td>
											<td><?=$bill->company_name?></td>
											<td style="text-align: center"><span style="display:none"><?=date("Ymd",strtotime($date_2))?></span><?=$bill->invoice_date?></td>
											<td><a href="billings/edit_bill/<?=$bill->id?>" class="pointer mb-sm mt-sm mr-sm  "><?=$bill->invoice_no?></a></td>
											<td><?=$bill->currency_name?></td>
											<td class="text-right"><?=number_format($bill->amount,2)?></td>
											<td class="text-right"><?=number_format($bill->outstanding,2)?></td>
											<td><?=isset($bill->receipt_no)?$bill->receipt_no:''?></td>
											<td>Unpaid</td>
											<td><a data-toggle="modal" data-code="<?=$bill->company_code?>" onclick="resendEmail(<?=$bill->id?>)" class="btn btn-primary pointer mb-sm mr-sm">Resend Email</a></td>
										</tr>
										<?php
											$i++;
												}
											}
										?>
										<?php
											if($paid_billings)
											{
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
										    <td><a data-toggle="modal" data-code="<?=$bill->company_code?>" onclick="resendEmail(<?=$paid_bill->id?>)" class="btn btn-primary pointer mb-sm mr-sm">Resend Email</a></td>
										</tr>
										<?php
												$i++;
												}
											}
											
										?>
									</tbody>
								</table>
							</div>
							<?php
								}
							?>
						</div>
					</div>
					<div id="modal_payment" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;">
						<div class="modal-dialog">
							<div class="modal-content">
								<header class="panel-heading">
									<h2 class="panel-title">Receipt</h2>
								</header>
								<form id="form_receipt" autocomplete="off">
								<div class="panel-body">
									<table class="table table-bordered table-striped table-condensed mb-none" id="unpaid_amount">
										<tr>
											<td align=right colspan=3>Company Name</td>
											<td colspan="4"><div id="receipt_company_name"></div></td>
										</tr>
										<tr>
											<td align=right colspan=3>Date</td>
											<td colspan="4">
												<div class="receipt_date_div">
													<div class="input-group" id="receipt_date" style="width: 100%;">
														<span class="input-group-addon">
															<i class="far fa-calendar-alt"></i>
														</span>
														<input type="text" class="receipt_date form-control" name="receipt_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker required value="">
													</div>
												</div>
											</td>
										</tr>
										<tr class="tr_bank_account">
											<td align=right colspan=3>Bank Account</td>
											<td colspan="4"><div class="input-group" style="width: 100%;"><select id="bank_account" class="form-control bank_account" style="text-align:right; width: 100%;" name="bank_account">
							                    <option value="0">Select Bank Account</option>
							                </select></div></td>
										</tr>
										<tr class="tr_unassign_amount_receipt" style="display: none">
											<td align=right colspan=3>Unassign Amount</td>
											<td colspan="4">
												<div class="input-group" style="width: 100%;">
													<input type="text" id="unassign_ccy" name="unassign_ccy" class="form-control unassign_ccy" readonly="true" style="width: 33.31%; margin-right: 4px;"/>
													<input type="text" id="unassign_amt" name="unassign_amt" class="form-control unassign_amt numberdes text-right" readonly="true" style="width: 65%; border-radius: 4px;"/>
													<input type="hidden" id="unassign_company_code" name="unassign_company_code" class="form-control unassign_company_code" readonly="true"/>
												</div>
											</td>
										</tr>
										<tr>
											<td align=right colspan=3>Total Amount Received</td>
											<td colspan="4">
												<div class="input-group" style="width: 100%;">
													<select id="currency_total_amount_received" class="form-control currency_total_amount_received" style="width: 33.31%; margin-right: 4px;" name="currency_total_amount_received" disabled="true">
							                		</select>
													<input type="text" name="total_amount_received" class="form-control numberdes text-right" id="total_amount_received" value="" style="width: 65%; border-radius: 4px;"/><!-- 65%  readonly="true" -->
												</div>
											</td>
										</tr>
										<tr class="tr_payment_mode">
											<td align=right colspan=3>Payment Mode</td>
											<td colspan="4">
												<div class="input-group" style="width: 100%;">
													<select id="payment_mode" class="form-control payment_mode" style="text-align:right; width: 100%;" name="payment_mode">
							                    		<option value="0">Select Payment Mode</option>
							                		</select>
							                	</div>
							                </td>
										</tr>
										<tr>
											<td align=right colspan=3>Receipt No</td>
											<td colspan="4"><div class="input-group" style="width: 100%;"><input type="text" name="receipt_no" class="form-control receipt_no"/></div></td>
										</tr>
										<tr>
											<td align=right colspan=3>Reference No</td>
											<td colspan="4"><div class="input-group" style="width: 100%;"><input type="text" id="reference_no" name="reference_no" class="form-control reference_no"/><input type="hidden" id="hidden_reference_no" name="hidden_reference_no" class="form-control hidden_reference_no"/></div></td>
										</tr>
										<tr style="background-color:white;">
											<th class="text-center" style="vertical-align: middle;">No</th>
											<th class="text-center" style="vertical-align: middle;">Invoice Date</th>
											<th class="text-center" style="vertical-align: middle;">Invoice No</th>
											<th class="text-center" style="vertical-align: middle;">Currency</th>
											<th class="text-center" style="vertical-align: middle;">Amount</th>
											<th class="text-center" style="vertical-align: middle;">Outstanding</th>
											<th class="text-center" style="vertical-align: middle;">Original Amount</th>
											<th class="text-center receipt_currency_rate" style="vertical-align: middle; display: none">Equivalent Amount</th>
										</tr>
										<tbody id="receipt_info" class="receipt_info">
										</tbody>
										<tbody id="receipt_total">
										</tbody>
										<tbody id="receipt_out_of_balance_total">
										</tbody>
									</table>
								</div>
								</form>
								<div class="modal-footer">
									<button type="submit" class="btn btn-primary" name="saveReceipt" id="saveReceipt">Save</button>
									<input type="button" class="btn btn-default " data-dismiss="modal" name="cancel_receipt" value="Cancel">
								</div>
							</div>
						</div>
					</div>
					<div id="modal_credit_note" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;">
						<div class="modal-dialog">
							<div class="modal-content">
								<header class="panel-heading">
									<h2 class="panel-title">Credit Note</h2>
								</header>
								<form id="form_credit_note">
									<div class="panel-body">
										<table class="table table-bordered table-striped table-condensed" id="unpaid_amount">
											<tr>
												<td align=right width="200">Credit Note No</td>
												<td><div class="cn_group" style="width: 100%;"><input type="text" name="latest_credit_note_no" class="form-control latest_credit_note_no" id="latest_credit_note_no"/></div><input type="hidden" id="billing_outstanding" name="billing_outstanding" value=""/><input type="hidden" id="billing_no" name="billing_no" value=""/><input type="hidden" id="credit_note_id" name="credit_note_id" value=""/></td>
											</tr>
											<tr>
												<td align=right width="200">Company Name</td>
												<td><!-- <div id="latest_credit_note_company_name"></div> -->
													<div class="client_company_code_group" style="width: 100%;">
														<select id="client_id" class="form-control client_id" style="width: 100%;" name="client_company_code">
								                    		<option value="0">Select Company Name</option>
								                		</select>
								                	</div>
												</td>
											</tr>
											<tr>
												<td align=right width="200">Invoice Number</td>
												<td>
													<div class="cn_group" style="width: 100%;">
														<select id="latest_invoice_no_for_cn" class="form-control latest_invoice_no_for_cn" style="width: 100%;" name="latest_invoice_no_for_cn_id">
								                    		<option value="0">Select Invoice Number</option>
								                		</select>
								                	</div>
												</td>
											</tr>
											<tr>
												<td align=right width="200">Credit Note Date</td>
												<td>
													<div class="credit_note_date_div">
														<div class="input-group" id="credit_note_date" style="width: 100%;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="latest_credit_note_date form-control" name="latest_credit_note_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker value="">
														</div>
													</div>
												</td>
											</tr>
											<tr>
												<td align=right width="200">Currency</td>
												<td>
													<div class="cn_group" style="width: 100%;">
														<select id="currency" class="form-control currency" style="width: 100%;" name="currency">
											                    <option value="0">Select Currency</option>
											            </select>
											        </div>
												</td>
											</tr>
											<tr>
												<td align=right width="200">Rate</td>
												<td>
													<div class="cn_group" style="width: 100%;">
														<input type="text" name="cn_rate" class="form-control numberdes text-right" id="cn_rate" value="1.0000"/>
													</div>
												</td>
											</tr>
											<tr>
												<td align=right width="200">Total Amount Discounted</td>
												<td>
													<div class="cn_group" style="width: 100%;">
														<input type="text" name="latest_total_amount_discounted" class="form-control numberdes text-right" id="latest_total_amount_discounted" value=""/>
														<input type="hidden" name="company_name">
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
										<table class="table table-bordered table-striped table-condensed mb-none" id="unpaid_amount">
											<tr style="background-color:white;">
												<th class="text-center" style="vertical-align: middle;">Service</th>
												<th class="text-center" style="vertical-align: middle;">Invoice Amount</th>
												<th class="text-center" style="vertical-align: middle;">CN Amount</th>
											</tr>
											<tbody id="latest_credit_note_info">
											</tbody>
											<tbody id="latest_credit_note_total">
											</tbody>
										</table>
									</div>
								</form>
								<div class="modal-footer">
									<button type="button" class="btn btn-primary" name="saveCreditNote" id="saveCreditNote">Save</button>
									<input type="button" class="btn btn-default" data-dismiss="modal" name="cancel_credit_note" value="Cancel">
								</div>
							</div>
						</div>
					</div>
					<div id="modal_edit_previous_credit_note" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important; z-index: 10000000 !important;">
						<div class="modal-dialog">
							<div class="modal-content">
								<header class="panel-heading">
									<h2 class="panel-title">Credit Note</h2>
								</header>
								<form id="form_edit_previous_credit_note">
								<div class="panel-body">
									<table class="table table-bordered table-striped table-condensed mb-none" id="unpaid_amount">
										<tr>
											<td align=right colspan=3>Company Name</td>
											<td colspan=2><div id="credit_note_company_name"></div></td>
										</tr>
										<tr>
											<td align=right colspan=3>Date</td>
											<td colspan="2">
												<div class="credit_note_date_div">
												<div class="input-group" id="credit_note_date" style="width: 100%;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="credit_note_date form-control" name="credit_note_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker required value="" disabled="true">
												</div>
											</div>
											</td>
										</tr>
										<tr>
											<td align=right colspan=3>Total Amount Discounted</td>
											<td colspan=2><div class="input-group" style="width: 100%;"><input type="text" name="total_amount_discounted" class="form-control numberdes text-right" id="total_amount_discounted" value="" disabled="true"/></div></td>
										</tr>
										<tr>
											<td align=right colspan=3>Credit Note No</td>
											<td colspan=2><div class="input-group" style="width: 100%;"><input type="text" name="credit_note_no" class="form-control credit_note_no" disabled="true"/></div></td>
										</tr>
										<tr style="background-color:white;">
											<th class="text-center" style="vertical-align: middle;">No</th>
											<th class="text-center" style="vertical-align: middle;">Invoice Date</th>
											<th class="text-center" style="vertical-align: middle;">Invoice No</th>
											<th class="text-center" style="vertical-align: middle;">Amount</th>
											<th class="text-center" style="vertical-align: middle;">Outstanding</th>
											<th class="text-center" style="vertical-align: middle;">Received</th>
										</tr>
										<tbody id="credit_note_info">
										</tbody>
										<tbody id="credit_note_total">
										</tbody>
									</table>
								</div>
								</form>
								<div class="modal-footer">
									<!-- <button type="submit" class="btn btn-primary" name="saveCreditNote" id="saveCreditNote">Save</button> -->
									<input type="button" class="btn btn-default " data-dismiss="modal" name="cancel_edit_previous_credit_note" value="Cancel">
								</div>
							</div>
						</div>
					</div>
					<div id="modal_previous_credit_note" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;">
						<div class="modal-dialog" style="width: 80%; ">
							<div class="modal-content">
								<header class="panel-heading">
									<h2 class="panel-title">Previous Credit Note</h2>
								</header>
								<div class="panel-body">
									<table class="table table-bordered table-striped mb-none" id="datatable-previous_credit_note">
										<thead><tr>
											<th style="text-align: center">No</th>
											<th style="text-align: center">Company Name</th>
											<th style="text-align: center">Credit Note No</th>
											<th style="text-align: center">Credit Note Date</th>
											<th style="text-align: center">Credit Note Amount</th>
											<th style="text-align: center">Invoice No</th>
											<th></th>
										</tr>
										</thead>
										<tbody id="previous_credit_note_body">
										</tbody>
									</table>
								</div>
								<div class="modal-footer">
									<input type="button" class="btn btn-default " data-dismiss="modal" name="cancel_previous_credit_note" value="Cancel">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 number text-right" id="billing_footer_button">
					<button type="submit" class="btn btn-primary" name="save_template" id="save_template">Save</button>
					<a href="<?= base_url();?>masterclient/" class="btn btn-default">Close</a>
				</div>
			</div>
		</footer>
	</section>
</div>
</div>
</section>
</div>

<!-- linda div id="modal_import_billings_to_qb" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;">
	<div class="modal-dialog">
		<div class="modal-content">
			<header class="panel-heading">
				<h2 class="panel-title">Import <span class="module_name"></span> to Quickbook Online</h2>
			</header>
			<form id="form_import_billings_to_qb">
				<div class="panel-body">
					<div style="height: 200px;">
						<h5>Please choose a range of date for import to Quickbook Online. (Only can import 10 data in a row.)</h5>
						<label class="control-label">Date range</label>
						<div class="input-daterange input-group" data-plugin-datepicker data-date-format="dd/mm/yyyy">
							<span class="input-group-addon">
								<i class="far fa-calendar-alt"></i>
							</span>
							<input type="text" class="form-control import_start_date" name="start" value="">
							<span class="input-group-addon">to</span>
							<input type="text" class="form-control import_end_date" name="end" value="">
						</div>
					</div>
				</div>
			</form>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" name="saveImportBillingsToQB" id="saveImportBillingsToQB">Import</button>
				<input type="button" class="btn btn-default" data-dismiss="modal" name="cancelImportBillingsToQB" value="Cancel">
			</div>
		</div>
	</div>
</div>
-->
<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>
<script>
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

	var billing_info = <?php echo json_encode(isset($billings)?$billings:'');?>;
	var currency_info = <?php echo json_encode(isset($currency)?$currency:'');?>;
	var bool_open_receipt = <?php echo json_encode(isset($open_receipt)?$open_receipt:'');?>;
	var company_code = <?php echo json_encode(isset($company_code)?$company_code:'');?>;
	var billing_check_state = <?php echo json_encode(isset($_POST['billing_check_state'])?$_POST['billing_check_state']:null) ?>;
	var system_name = <?php echo json_encode($this->systemName)?>;
	var check_qb_token = <?php echo json_encode(isset($check_qb_token)?$check_qb_token:'');?>;
	var qb_company_id = <?php echo json_encode(isset($qb_company_id)?$qb_company_id:'');?>;

	if(billing_check_state != "billing" && billing_check_state != null)
	{
		$tab_aktif = billing_check_state;
	}
	else
	{
		$tab_aktif = "billing";
	}

	var access_right_billing_module = <?php echo json_encode($billing_module);?>;
	var access_right_unpaid_module = <?php echo json_encode($unpaid_module);?>;
	var access_right_paid_module = <?php echo json_encode($paid_module);?>;
	var access_right_receipt_module = <?php echo json_encode($receipt_module);?>;
	var access_right_template_module = <?php echo json_encode($template_module);?>;

	if(access_right_billing_module != "full" && access_right_unpaid_module != "full" && access_right_paid_module != "full" && access_right_receipt_module != "full" && access_right_template_module != "full")
	{
		$(".edit_client_billing").hide();
	}
	else
	{
		$(".edit_client_billing").show();
	}

	if(access_right_billing_module == "read")
	{
		$('.edit_client').hide();
		$('.open_reciept').hide();
		$('#saveReceipt').attr("disabled", true);
		
		$(':input:not([name="searchResult"], [name="search"], [name="start"], [name="end"], [name="export_pdf"], [name="cancel_receipt"])').attr("disabled", true);
		$('#payment_mode').attr("disabled", true);
		$('select[name=type]').attr("disabled", false);
		$('.amount').attr("disabled", true);
		$("button[name=save_template]").attr("disabled", true);
	}

	if(access_right_unpaid_module == "read")
	{
		$('.edit_client').hide();
		$('.open_reciept').hide();
	}

	if(access_right_receipt_module == "read")
	{
		$(':input:not([name="searchResult"], [name="search"], [name="start"], [name="end"], [name="export_pdf"], [name="cancel_receipt"])').attr("disabled", true);
		$('#payment_mode').attr("disabled", true);
		$('select[name=type]').attr("disabled", false);
		$('#saveReceipt').attr("disabled", true);
	}

	if(access_right_template_module == "read")
	{	
		$("#w2-template input").attr("disabled", true);
		$("button[name=save_template]").attr("disabled", true);
	}

	if(access_right_unpaid_module == "none")
	{
		$('li[data-information="payment1"]').addClass("active");
		$('#w2-payment1').addClass("active");
	}

	if(access_right_unpaid_module == "none" && access_right_paid_module == "none")
	{
		$('li[data-information="receipt"]').addClass("active");
		$('#w2-receipt').addClass("active");
	}

	if(access_right_unpaid_module == "none" && access_right_paid_module == "none" && access_right_receipt_module == "none")
	{
		$('li[data-information="template"]').addClass("active");
		$('#w2-template').addClass("active");
	}
</script> 
<script src="themes/default/assets/js/billings.js?v=001" charset="utf-8"></script>
<style>
	#buttonclick .datatables-header {
		display:none;
	}
	#datatable-previous_credit_note_wrapper .datatables-header {
		display:block;
	} 
</style>