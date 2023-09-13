<section class="panel" style="margin-top: 30px;">
	<header class="panel-heading">
		<div class="panel-actions" style="height:80px">
									
			<!-- a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a-->
			<!--a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a-->
			<a class="edit_client_billing amber" href="#" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Create Billing" ><i class="fa fa-plus-circle  amber" style="font-size:16px;height:45px;"></i> Create Billing</a>
		</div>
		<h2></h2>
			
	</header>
	
	<div class="panel-body">
		<div class="col-md-12">
			
			<div class="row datatables-header">
				<?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form', 'id' => 'form_search_billing');
					echo form_open_multipart("billings", $attrib);
					
				?>
				<div class="col-sm-7">
					<div class="col-md-5">
						<select class="form-control" name="type">
							<option value="all" <?=$_POST['type'] == "all"?'selected':'';?>>All</option>
							<option value="company_name" <?=$_POST['type']=='company_name'?'selected':'';?>>Company Name</option>
							<option value="former_name" <?=$_POST['type'] == "former_name"?'selected':'';?>>Former Name</option>
							<option value="registration_no" <?=$_POST['type'] == "registration_no"?'selected':'';?>>Registration No</option>
						</select>
					</div>
					<div class="col-md-7">
						<input type="text" class="form-control" id="w2-username" name="search" placeholder="Search" value="<?=$_POST['search']?$_POST['search']:'';?>">
					</div>
					<div class="col-sm-12">
					<label class="control-label">Date range</label>
					<div class="input-daterange input-group" data-plugin-datepicker data-date-format="dd/mm/yyyy">
						<span class="input-group-addon">
							<i class="far fa-calendar-alt"></i>
						</span>
						<input type="text" class="form-control" name="start" value="<?=$_POST['start']?$_POST['start']:'';?>">
						<span class="input-group-addon">to</span>
						<input type="text" class="form-control" name="end" value="<?=$_POST['end']?$_POST['end']:'';?>">
					</div>
					</div>
					
					
					
						<!-- <label class="control-label">Date range</label>
							<div class="input-daterange input-group" data-plugin-datepicker>
								<span class="input-group-addon">
									<i class="far fa-calendar-alt"></i>
								</span>
								<input type="text" class="form-control" name="start">
								<span class="input-group-addon">to</span>
								<input type="text" class="form-control" name="end">
							</div>
					<button name="search" type="button" id="button" class="btn btn-primary" tabindex="-1">search</button> -->
					<!-- <a href="<?= base_url();?>/masterclient/create_billing" class="btn btn-default">Create Invoice</a> -->
				</div>
				<?= form_close();?>
				<div class="col-md-5">
					<input type="submit" class="btn btn-primary" id="searchResult" name="searchResult" value="Search"/>
					<a href="billings" class="btn btn-primary">Show All Billings</a>
					<input type="button" class="btn btn-primary" onclick="exportPDF()" name="export_pdf" value="Export PDF">
					<!-- <a href="personprofile" class="btn btn-primary">Show All Person</a> -->
				</div>
			</div>
			
			
					<!-- <div id="buttonclick" style="display:none;"> -->
					<div id="buttonclick">
						<div class="panel-body">
						<ul class="nav nav-tabs nav-justify">
							
								<li class="check_state active" data-information="billing">
									<a href="#w2-billing" data-toggle="tab" class="text-center">
										<span class="badge hidden-xs">1</span>
										Unpaid
									</a>
								</li>
							
							<li class="check_state" data-information="payment1">
								<a href="#w2-payment1" data-toggle="tab" class="text-center">
									<span class="badge hidden-xs">2</span>
									Paid
								</a>
							</li>
							
							<li class="check_state" data-information="receipt">
								<a href="#w2-receipt" data-toggle="tab" class="text-center">
									<span class="badge hidden-xs">3</span>
									Receipt
								</a>
							</li>
							
							<li class="check_state" data-information="template">
								<a href="#w2-template" data-toggle="tab" class="text-center">
									<span class="badge hidden-xs">4</span>
									Template
								</a>
							</li>
							
						</ul>
						<div class="tab-content">
							
							<div id="w2-billing" class="tab-pane active">
								<table class="table table-bordered table-striped mb-none" id="datatable-paid">
									<thead><tr>
										<th><input type="checkbox" name="selectall" class="selectall"/></th>
										<th style="text-align: center">No</th>
										<th style="text-align: center">Company Name</th>
										<th style="text-align: center">Invoice Date</th>
										<th style="text-align: center">Invoice No</th>
										<th style="text-align: center">Amount</th>
										<th style="text-align: center">Outstanding</th>
										<th style="text-align: center"></th>
									</tr>
									</thead>
									<tbody id="billing_body">
										<?php
											$i = 1;
											//print_r($billings);
											foreach($billings as $bill)
											{
										?>
										<tr>
											<td style="width:50px"><input type="checkbox" class="" id="" name="billing_checkbox" value="<?=$bill->id?>"></td>
											<td style="text-align: right"><label> &nbsp;<?=$i?></label></td>
											<td><?=$bill->company_name?></td>
											<td style="text-align: center"><?=$bill->invoice_date?></td>
											<td><a href="billings/edit_bill/<?=$bill->id?>" class="pointer mb-sm mt-sm mr-sm  "><?=$bill->invoice_no?></a></td>
											<td class="text-right"><?=number_format($bill->amount,2)?></td>
											<td class="text-right"><?=number_format($bill->outstanding,2)?></td>
											<!-- <td><a data-toggle="modal" data-target="#modal_payment" class="pointer mb-sm mt-sm mr-sm  ">Reciept</a></td> -->
											<td><a data-toggle="modal" data-code="<?=$bill->company_code?>" class="open_reciept pointer mb-sm mt-sm mr-sm  ">Receipt</a></td>
										</tr>
										<?php
											$i++;
												}
										?>
									</tbody>
								</table>
							</div>
							
							<div id="w2-payment1" class="tab-pane">
								
								<table class="table table-bordered table-striped mb-none" id="datatable-default">
									<thead><tr>
										<th style="text-align: center">No</th>
										<th style="text-align: center">Company Name</th>
										<th style="text-align: center">Invoice Date</th>
										<th style="text-align: center">Invoice No</th>
										<th style="text-align: center">Amount</th>
										<th style="text-align: center">Receipt No</th>
										<th style="text-align: center">Receipt Date</th>
										<th style="text-align: center">Receipt Amount</th>
										<th style="text-align: center">Reference No</th>
										<th style="text-align: center">Payment Mode</th>

										<!-- <th style="text-align: center">No</th>
										<th style="text-align: center">Company Name</th>
										<th style="text-align: center">Invoice Date</th>
										<th style="text-align: center">Invoice No</th>
										<th style="text-align: center">Amount</th>
										<th style="text-align: center">Outstanding</th> -->
									</tr>
									</thead>
									<tbody id="billing_body">
										<?php
											$i = 1;
											//print_r($billings);
											foreach($paid_billings as $paid_bill)
											{
										?>
										<tr>
											

											<!-- <td><label> &nbsp;<?=$i?></label></td>
											<td><?=$paid_bill->company_name?></td>
											<td><?=$paid_bill->invoice_date?></td>
											<td><?=$paid_bill->invoice_no?></td>
											<td><?=number_format($paid_bill->amount,2)?></td>
											
											<td><?=number_format($paid_bill->outstanding,2)?></td> -->
											

											<td><label> &nbsp;<?=$i?></label></td>
											<td><?=$paid_bill->company_name?></td>
											<td><?=$paid_bill->invoice_date?></td>
											<td><?=$paid_bill->invoice_no?></td>
											<td><?=number_format($paid_bill->amount,2)?></td>
											<td><?=$paid_bill->receipt_no?></td>
											<td><?=$paid_bill->receipt_date?></td>
											<td><?=number_format($paid_bill->received,2)?></td>
											<td><?=$paid_bill->reference_no?></td>
											<td><?=$paid_bill->payment_mode?></td> 
										</tr>
										<?php
											$i++;
												}
										?>
									</tbody>
								</table>
							</div>
							
							<div id="w2-receipt" class="tab-pane">
								
								<table class="table table-bordered table-striped mb-none" id="datatable-receipt">
									<thead><tr>
										<th style="text-align: center">No</th>
										<th style="text-align: center">Company Name</th>
										<th style="text-align: center">Invoice Date</th>
										<th style="text-align: center">Invoice No</th>
										<th style="text-align: center">Amount</th>
										<th style="text-align: center">Receipt No</th>
										<th style="text-align: center">Receipt Date</th>
										<th style="text-align: center">Receipt Amount</th>
										<th style="text-align: center">Reference No</th>
										<th style="text-align: center">Payment Mode</th>
									</tr>
									</thead>
									<tbody id="billing_body">
										<?php
											$i = 1;
											//print_r($billings);
											foreach($receipt as $paid_receipt)
											{
										?>
										<tr>
											<td><label> &nbsp;<?=$i?></label></td>
											<td><?=$paid_receipt->company_name?></td>
											<td><?=$paid_receipt->invoice_date?></td>
											<td><?=$paid_receipt->invoice_no?></td>
											<td><?=number_format($paid_receipt->amount,2)?></td>
											<td><a data-toggle="modal" data-id="<?=$paid_receipt->receipt_id?>" class="open_edit_reciept pointer mb-sm mt-sm mr-sm  "><?=$paid_receipt->receipt_no?></a></td>
											<td><?=$paid_receipt->receipt_date?></td>
											<td><?=number_format($paid_receipt->received,2)?></td>
											<td><?=$paid_receipt->reference_no?></td>
											<td><?=$paid_receipt->payment_mode?></td>
										</tr>
										<?php
											$i++;
												}
										?>
									</tbody>
								</table>
							</div>
							
							<div id="w2-template" class="tab-pane">
								<form id="form_template">
								<table class="table table-bordered table-striped mb-none">
									<thead>
										<div class="tr"> 
											<!-- <div class="th" valign=middle>No</div> -->
											<div class="th" valign=middle style="width:200px;text-align: center">Service</div>
											<div class="th" valign=middle style="width:250px;text-align: center">Invoice Description</div>
											<div class="th" style="width:180px;text-align: center">Amount</div>
											<div class="th" style="width:190px;text-align: center">Recurring</div>
											<div class="th" style="width:150px;text-align: center">Frequency</div>
											<div class="th" style="font-size:25px;"><span id="billing_info_Add"><i class="fa fa-plus-circle"></i></span></div> 
										</div>
										
									</thead>
									

									<div class="tbody" id="body_billing_info">
										

									</div>
									
									</table>
								</form>
							</div>
							
						</div>

				</div>
				<!-- <from id="form_pdf_id" action="createbillingpdf/create_billing_pdf" method="post"> -->
				<!-- <?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form', 'id' => 'form_pdf_id');echo form_open("createbillingpdf/create_billing_pdf", $attrib);?>
					<input name="pdf_id" id="pdf_id" type="hidden" value="">
				 <?php echo form_close(); ?> -->
				
				<div id="modal_payment" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					
					<div class="modal-dialog">
						<div class="modal-content">
							<header class="panel-heading">
								<h2 class="panel-title">Receipt</h2>
							</header>
							<form id="form_receipt">
							<div class="panel-body">
								<table class="table table-bordered table-striped table-condensed mb-none" id="unpaid_amount">
									<tr>
										<td align=right colspan=3>Company Name</td>
										<td colspan=2><div id="receipt_company_name"></div></td>
									</tr>
									<tr>
										<td align=right colspan=3> Date</td>
										<td colspan="2">
											<div class="receipt_date_div">
											<div class="input-group" id="receipt_date" style="width: 100%;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="receipt_date form-control" name="receipt_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker required value="">
											</div>
										</div>
										</td>
										<!-- <?php $now = getDate();echo $now['mday'].'/'.$now['mon']."/".$now['year'];?> -->
										<!-- <td colspan=2><input type="text" data-plugin-datepicker data-date-format="dd/mm/yyyy" data-date-start-date="0d" placeholder="Date"/></td> -->

									</tr>
									<tr>
										
											<td align=right colspan=3>Total Amount Received</td>
											<td colspan=2><div class="input-group" style="width: 100%;"><input type="text" name="total_amount_received" class="form-control numberdes text-right" id="total_amount_received" value=""/></div></td>
										
									</tr>
									<tr>
										<td align=right colspan=3>Payment Mode</td>
										<td colspan=2><div class="input-group" style="width: 100%;"><select id="payment_mode" class="form-control payment_mode" style="text-align:right; width: 100%;" name="payment_mode">
						                    <option value="0">Select Payment Mode</option>
						                </select></div></td>
									</tr>
									<tr>
										<td align=right colspan=3>Receipt No</td>
										<td colspan=2><div class="input-group" style="width: 100%;"><input type="text" name="receipt_no" class="form-control receipt_no"/></div></td>
									</tr>
									<tr>
										<td align=right colspan=3>Reference No</td>
										<td colspan=2><div class="input-group" style="width: 100%;"><input type="text" name="reference_no" class="form-control reference_no"/></div></td>
									</tr>
									<tr style="background-color:white;">
										<th class="text-center" style="vertical-align: middle;">No</th>
										<th class="text-center" style="vertical-align: middle;">Invoice Date</th>
										<th class="text-center" style="vertical-align: middle;">Invoice No</th>
										<th class="text-center" style="vertical-align: middle;">Amount</th>
										<th class="text-center" style="vertical-align: middle;">Outstanding</th>
										<th class="text-center" style="vertical-align: middle;">Received</th>
									</tr>
									<tbody id="receipt_info">
									
										<!-- <td>1</td>
										<td>2016-01-01</td>
										<td>INV-0001</td>
										<td align=right >10,000</td>
										<td align=right >10,000</td>
										<td><input type="text" class="numberdes" style="width:100%;text-align:right" placeholder="Amount"/></td> -->
									
									</tbody>
									<tbody id="receipt_total">
									</tbody>
									<!-- <tr>
										<td>2</td>
										<td>2016-01-01</td>
										<td>INV-0002</td>
										<td align=right >15.000</td>
										<td align=right >10,000</td>
										<td><input type="text" class="numberdes" style="width:100%;text-align:right" placeholder="Amount"/></td>
									</tr> -->
									<!-- <tr>
										<td align=right colspan=4>Total</td>
										<td align=right >20,000.00</td>
										<td align=right>0</td>
									</tr> -->
									<!--tr>
										<td align=right colspan=3>Payment</td>
										<td><input type="number" placeholder="Amount"/></td>
									</tr-->
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
				<div id="modal_invoice" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<header class="panel-heading">
								<h2 class="panel-title">Invoice No</h2>
							</header>
							<div class="panel-body">
								<table class="table table-bordered table-striped table-condensed mb-none">
									
									<tr>
										<th>Date</th>
										<th>04/10/2017</th>
									</tr>
									<tr>
										<th>Company Name</th>
										<th>Dot Exe, LTE PTE
										</th>
									</tr>
									<tr>
										<th>Bill To</th>
										<th>Mr. XXXXX<br/>
										Orchard Road Singapore, 212312<br/>
										Phone : 1022992<br/>
										Fax : 292991<br/></th>
									</tr>
								</table>
								<br/>
								<table class="table table-bordered table-striped table-condensed mb-none">
									<tr>
										<th>No.</th>
										<th>Service</th>
										<th>Amount</th>
									</tr>
									<tr>
										<td>1</td>
										<td width="80%">Service 1</td>
										<td>SGD 10.000,00</td>
									</tr>
									<tr>
										<td>2</td>
										<td>Service 2</td>
										<td>SGD 15.000,00</td>
									</tr>
									<tr>
										<td>3</td>
										<td>Service 3</td>
										<td>SGD 20.000,00</td>
									</tr>
									<tr>
										<td colspan=2>Total</td>
										<td class="text-right">SGD 45.000</td>
									</tr>
								</table>
							</div>
							<div class="modal-footer">
								<button class="btn btn-primary">Save</button>
								<button class="btn btn-default " data-dismiss="modal">Cancel</button>
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
						<!--input type="button" value="Save As Draft" id="save_draft" class="btn btn-default"-->
						<!-- <input type="button" value="Save" id="save_template" class="btn btn-primary "> -->
						<button type="submit" class="btn btn-primary" name="save_template" id="save_template">Save</button>
						<a href="<?= base_url();?>masterclient/" class="btn btn-default">Close</a>
					</div>
				</div>
			</footer>
			</section>
		</div>
				<div id="" class="modal-block modal-block-lg mfp-hide">
			<section class="panel">
				<header class="panel-heading">
					<h2 class="panel-title">Payment <a href="#modal_setting_filing" class="filing_date mb-xs mt-xs mr-xs modal-sizes"><i class="fa fa-gear"></i></a></h2>
				</header>
				<div class="panel-body">
					<table class="table table-bordered table-striped table-condensed mb-none" >
						<tr>
							<th>No</th>
							<th>Date</th>
							<th>Invoice NO</th>
							<th>Amount</th>
						</tr>
						<tr>
							<td>1</td>
							<td>01/01/2016</td>
							<td>INV-0001</td>
							<td>10.000</td>
						</tr>
						<tr>
							<td>2</td>
							<td>01/01/2016</td>
							<td>INV-0002</td>
							<td>15.000</td>
						</tr>
						<tr>
							<td align=right colspan=3>Total</td>
							<td>25.000</td>
						</tr>
						<tr>
							<td align=right colspan=3>Payment</td>
							<td><input type="text" placeholder="Amount"/></td>
						</tr>
					</table>
				</div>
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-12 number text-right">
							<!--button class="btn btn-primary modal-confirm">Confirm</button-->
							<button class="btn btn-default modal-dismiss">Confirm Payment</button>
						</div>
					</div>
				</footer>
			</section>
		</div>
	</div>
</section>
</div>

<script>
	var template = <?php echo json_encode($template);?>;
	var bool_open_receipt = <?php echo json_encode($open_receipt);?>;
	var company_code = <?php echo json_encode($company_code);?>;

	
	/*$("#button").click(function(){$("#buttonclick").toggle(); });
	$("#button1").click(function(){$("#buttonclick").toggle(); });*/
	//console.log(<?php echo json_encode($paid_billings);?>);
	

	/*$(document).on('click',"#saveReceipt",function(e){
		//e.preventDefault();
		$("#form_receipt").submit();
		//e.preventDefault();
	});*/
</script> 
<script src="themes/default/assets/js/user_billings.js?v=001" charset="utf-8"></script>
<style>
	#buttonclick .datatables-header {
		display:none;
	}
</style>