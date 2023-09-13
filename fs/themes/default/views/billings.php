<div class="header_between_all_section">
<section class="panel">
	<header class="panel-heading">
		<div class="panel-actions" style="height:80px">
									
			<!-- a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a-->
			<!--a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a-->
			<a class="edit_client_billing amber" href="<?= base_url();?>billings/create_billing" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Create Billing" ><i class="fa fa-plus-circle  amber" style="font-size:16px;height:45px;"></i> Create Billing</a>

			<a class="edit_client_recurring amber" href="<?= base_url();?>billings/create_recurring" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;display: none;" data-original-title="Create Recurring" ><i class="fa fa-plus-circle  amber" style="font-size:16px;height:45px;"></i> Create Recurring</a>
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
							<!-- <option value="former_name" <?=$_POST['type'] == "former_name"?'selected':'';?>>Former Name</option> -->
							<option value="registration_no" <?=$_POST['type'] == "registration_no"?'selected':'';?>>Registration No</option>
						</select>
					</div>
					<div class="col-md-7 person_search_input">
						<input type="text" class="form-control" id="w2-username" name="search" placeholder="Search" value="<?=$_POST['search']?$_POST['search']:'';?>">
						<input type="hidden" class="form-control submit_billing_check_state" name="billing_check_state" value="<?=$_POST['billing_check_state']?$_POST['billing_check_state']:'billing';?>">
					</div>
					<div class="col-sm-8">
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
					<div class="col-sm-4">
						<input type="button" class="btn btn-primary" onclick="exportStatement()" name="export_pdf" value="Export Statement" style="margin-top: 22px;">
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
				<div class="col-md-5 search_group_button">
					<input type="submit" class="btn btn-primary" id="searchResult" name="searchResult" value="Search"/>
					<a href="billings" class="btn btn-primary">Show All Billings</a>
					<!-- <input type="button" class="btn btn-primary" onclick="exportPDF()" name="export_pdf" value="Export PDF"> -->
					<!-- <button class="btn btn-primary" style="margin-right: 10px" onclick="deleteBilling()">Delete</button> -->
					<!-- <a href="personprofile" class="btn btn-primary">Show All Person</a> -->
				</div>
			</div>
			
			
					<!-- <div id="buttonclick" style="display:none;"> -->
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
							<!-- <?php if ($paid_module != 'none') { ?> 
							<li class="billing_check_state" data-information="payment1">
								<a href="#w2-payment1" data-toggle="tab" class="text-center">
									<span class="badge hidden-xs">2</span>
									Paid
								</a>
							</li>
							<?php
								}
							?> -->
							<?php if ($receipt_module != 'none') { ?> 
							<li class="billing_check_state" data-information="receipt">
								<a href="#w2-receipt" data-toggle="tab" class="text-center">
									<span class="badge hidden-xs">2</span>
									Receipt
								</a>
							</li>
							<?php
								}
							?>
							<!-- <?php if ($credit_note_module != 'none') { ?>  -->
							<li class="billing_check_state" data-information="credit_note">
								<a href="#w2-credit_note" data-toggle="tab" class="text-center">
									<span class="badge hidden-xs">3</span>
									Credit Note
								</a>
							</li>
							<!-- <?php
								}
							?> -->
							<!-- <?php if ($recurring_module != 'none') { ?>  -->
							<li class="billing_check_state" data-information="recurring">
								<a href="#w2-recurring" data-toggle="tab" class="text-center">
									<span class="badge hidden-xs">4</span>
									Recurring
								</a>
							</li>
							<!-- <?php
								}
							?> -->
							<!-- <?php if ($template_module != 'none') { ?> 
							<li class="billing_check_state" data-information="template">
								<a href="#w2-template" data-toggle="tab" class="text-center">
									<span class="badge hidden-xs">4</span>
									Template
								</a>
							</li>
							<?php
								}
							?> -->
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
										<!-- <th style="text-align: center">Receipt Date</th> -->
										<!-- <th style="text-align: center">Receipt Amount</th> -->
										<!-- <th style="text-align: center">Reference No</th> -->
										<!-- <th style="text-align: center">Payment Mode</th> -->
										<th style="text-align: center">Status</th>
										<th style="text-align: center; width: 225px;"></th>
									</tr>
									</thead>
									<tbody id="billing_body">
										<?php
											$i = 1;
											//print_r($billings);
				
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
											<!-- <td><a data-toggle="modal" data-target="#modal_payment" class="pointer mb-sm mt-sm mr-sm  ">Reciept</a></td> -->
											<td><?=$bill->receipt_no?></td>
											<!-- <td></td> -->
											<!-- <td></td> -->
											<!-- <td></td> -->
											<td>Unpaid</td>
											<td><a data-toggle="modal" data-code="<?=$bill->company_code?>" class="btn btn-primary open_reciept pointer mb-sm mr-sm">Receipt</a><a data-toggle="modal" data-code="<?=$bill->company_code?>" class="btn btn-primary open_credit_note pointer mb-sm mr-sm">Credit Note</a><a data-toggle="modal" data-code="<?=$bill->company?>" onclick="exportPDF(<?=$bill->id?>)" class="btn btn-primary p_code?>ointer mb-sm mr-sm">PDF</a><a style="" data-toggle="modal" data-code="<?=$bill->company_code?>" onclick="deleteBilling(<?=$bill->id?>)" class="btn btn-primary pointer mb-sm">Delete</a></td>
										</tr>
										<?php
											$i++;
												}
										?>
										<?php
											//$i = 1;
											//print_r($billings);
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
											

											<!-- <td><label> &nbsp;<?=$i?></label></td>
											<td><?=$paid_bill->company_name?></td>
											<td><?=$paid_bill->invoice_date?></td>
											<td><?=$paid_bill->invoice_no?></td>
											<td><?=number_format($paid_bill->amount,2)?></td>
											
											<td><?=number_format($paid_bill->outstanding,2)?></td> -->
											

											<td style="text-align: right"><!-- <label> &nbsp;<?=(int)$i?></label> --></td>
											<td><?=$paid_bill->company_name?></td>
											<td style="text-align: center"><span style="display:none"><?=date("Ymd",strtotime($date_2))?></span><?=$paid_bill->invoice_date?></td>
											<!-- <td style="text-align: center"><?php 

													$array = explode('/',$paid_bill->invoice_date);
													$tmp = $array[0];
													$array[0] = $array[1];
													$array[1] = $tmp;
													unset($tmp);
													$date_2 = implode('/', $array);
													$time = strtotime($date_2);
													$newformat = date('d F Y',$time);

													echo $newformat;
													
												?>
													
											</td> -->
											<td><a href="billings/review_paid_bill/<?=$paid_bill->id?>" class="pointer mb-sm mt-sm mr-sm  "><?=$paid_bill->invoice_no?></a></td>
											<td><?=$paid_bill->currency_name?></td>
											<td style="text-align: right"><?=number_format($paid_bill->amount,2)?></td>
											<td style="text-align: right"><?=number_format($paid_bill->outstanding,2)?></td>
											<td><?=$paid_bill->receipt_no?></td>
											<!-- <td style="text-align: center"><?=$paid_bill->receipt_date?></td> -->
											<!-- <td style="text-align: center"><?php 

													$array = explode('/',$paid_bill->receipt_date);
													$tmp = $array[0];
													$array[0] = $array[1];
													$array[1] = $tmp;
													unset($tmp);
													$date_2 = implode('/', $array);
													$time = strtotime($date_2);
													$newformat = date('d F Y',$time);

													echo $newformat;
													
												?>
													
											</td> -->
											
											<!-- <td style="text-align: right"><?=number_format($paid_bill->received,2)?></td> -->
											<!-- <td><?=$paid_bill->reference_no?></td> -->
											<!-- <td><?=$paid_bill->payment_mode?></td>  -->
											<td>Paid</td>
											<td><a data-toggle="modal" data-code="<?=$bill->company_code?>" onclick="exportPDF(<?=$paid_bill->id?>)" class="btn btn-primary pointer mb-sm mr-sm">PDF</a></td>
										</tr>
										<?php
											$i++;
											}
											
										?>
									</tbody>
								</table>
							</div>
							<?php
								}
							?>
							<!-- <?php if ($paid_module != 'none') { ?> 
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
											<td style="text-align: right"><label> &nbsp;<?=$i?></label></td>
											<td><?=$paid_bill->company_name?></td>
											<td style="text-align: center"><?php 

													$array = explode('/',$paid_bill->invoice_date);
													$tmp = $array[0];
													$array[0] = $array[1];
													$array[1] = $tmp;
													unset($tmp);
													$date_2 = implode('/', $array);
													$time = strtotime($date_2);
													$newformat = date('d F Y',$time);

													echo $newformat;
													
												?>
													
											</td>
											<td><a href="billings/review_paid_bill/<?=$paid_bill->id?>" class="pointer mb-sm mt-sm mr-sm  "><?=$paid_bill->invoice_no?></a></td>
											<td style="text-align: right"><?=number_format($paid_bill->amount,2)?></td>
											<td><?=$paid_bill->receipt_no?></td>
											<td style="text-align: center"><?php 

													$array = explode('/',$paid_bill->receipt_date);
													$tmp = $array[0];
													$array[0] = $array[1];
													$array[1] = $tmp;
													unset($tmp);
													$date_2 = implode('/', $array);
													$time = strtotime($date_2);
													$newformat = date('d F Y',$time);

													echo $newformat;
													
												?>
													
											</td>
											
											<td style="text-align: right"><?=number_format($paid_bill->received,2)?></td>
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
							<?php
								}
							?> -->
							<?php if ($receipt_module != 'none') { ?> 
							<div id="w2-receipt" class="tab-pane">
								
								<table class="table table-bordered table-striped mb-none" id="datatable-receipt">
									<thead><tr>
										<!-- <th><input type="checkbox" name="selectallreceipt" class="selectallreceipt"/></th> -->
										<th style="text-align: center">No</th>
										<th style="text-align: center">Company Name</th>
										<!-- <th style="text-align: center">Invoice Date</th>
										<th style="text-align: center">Invoice No</th>
										<th style="text-align: center">Amount</th> -->
										<th style="text-align: center">Receipt No</th>
										<th style="text-align: center">Receipt Date</th>
										<th style="text-align: center">Receipt Amount</th>
										<th style="text-align: center">Reference No</th>
										<th style="text-align: center">Payment Mode</th>
										<th style="text-align: center">Invoice No</th>
										<th></th>
									</tr>
									</thead>
									<tbody id="billing_body">
										<?php
											$i = 1;
											//print_r($receipt);
											foreach($receipt as $paid_receipt)
											{
												$date_1 = $paid_receipt->receipt_date;
												$array = explode('/', $date_1);
												$tmp = $array[0];
												$array[0] = $array[1];
												$array[1] = $tmp;
												unset($tmp);
												$date_2 = implode('/', $array);
										?>
										<tr>
											<!-- <td style="width:50px"><input type="checkbox" class="receipt_checkbox" id="" name="receipt_checkbox" value="<?=$paid_receipt->receipt_id?>"><input type="hidden" name="billing_id_chkbox" value="<?=$paid_receipt->billing_id?>"></td> -->
											<td style="text-align: right"><!-- <label> &nbsp;<?=(int)$i?></label> --></td>
											<td><?=$paid_receipt->company_name?></td>
											<!-- <td><?=$paid_receipt->invoice_date?></td>
											<td><?=$paid_receipt->invoice_no?></td>
											<td><?=number_format($paid_receipt->amount,2)?></td> -->
											<td><a data-toggle="modal" data-id="<?=$paid_receipt->receipt_id?>" class="open_edit_reciept pointer mb-sm mt-sm mr-sm"><?=$paid_receipt->receipt_no?></a></td>
											<td style="text-align: center"><span style="display:none"><?=date("Ymd",strtotime($date_2))?></span><?=$paid_receipt->receipt_date?></td>
											<td style="text-align: right"><?=number_format($paid_receipt->received,2)?></td>
											<td><?=$paid_receipt->reference_no?></td>
											<td><?=$paid_receipt->payment_mode?></td>
											<td><?=$paid_receipt->invoice_no?></td>
											<td><a data-toggle="modal" data-code="<?=$paid_receipt->company_code?>" onclick="exportPDF(null, <?=$paid_receipt->receipt_id?>)" class="btn btn-primary pointer mb-sm mr-sm">PDF</a><a style="" data-toggle="modal" data-code="<?=$paid_receipt->company_code?>" onclick="deleteBilling(<?=$paid_receipt->billing_id?>, <?=$paid_receipt->receipt_id?>)" class="btn btn-primary pointer mb-sm mr-sm">Delete</a></td>
										</tr>
										<?php
											$i++;
												}
										?>
									</tbody>
								</table>
							</div>
							<?php
								}
							?>
							<div id="w2-credit_note" class="tab-pane">
								
								<table class="table table-bordered table-striped mb-none" id="datatable-credit_note">
									<thead><tr>
										<!-- <th><input type="checkbox" name="selectallreceipt" class="selectallreceipt"/></th> -->
										<th style="text-align: center">No</th>
										<th style="text-align: center">Company Name</th>
										<!-- <th style="text-align: center">Invoice Date</th>
										<th style="text-align: center">Invoice No</th>
										<th style="text-align: center">Amount</th> -->
										<th style="text-align: center">Credit Note No</th>
										<th style="text-align: center">Credit Note Date</th>
										<th style="text-align: center">Credit Note Amount</th>
										<th style="text-align: center">Invoice No</th>
										<th></th>
									</tr>
									</thead>
									<tbody id="billing_body">
										<?php
											$i = 1;
											//print_r($receipt);
											foreach($credit_note as $paid_credit_note)
											{
												$date_1 = $paid_credit_note->credit_note_date;
												$array = explode('/', $date_1);
												$tmp = $array[0];
												$array[0] = $array[1];
												$array[1] = $tmp;
												unset($tmp);
												$date_2 = implode('/', $array);
										?>
										<tr>
											<td style="text-align: right"><!-- <label> &nbsp;<?=(int)$i?></label> --></td>
											<td><?=$paid_credit_note->company_name?></td>
											<td><a data-toggle="modal" data-id="<?=$paid_credit_note->credit_note_id?>" class="open_edit_credit_note pointer mb-sm mt-sm mr-sm"><?=$paid_credit_note->credit_note_no?></a></td>
											<td style="text-align: center"><span style="display:none"><?=date("Ymd",strtotime($date_2))?></span><?=$paid_credit_note->credit_note_date?></td>
											<td style="text-align: right"><?=number_format($paid_credit_note->received,2)?></td>
											<td><?=$paid_credit_note->invoice_no?></td>
											<td><a data-toggle="modal" data-code="<?=$paid_credit_note->company_code?>" onclick="exportPDF(null, null, <?=$paid_credit_note->credit_note_id?>)" class="btn btn-primary pointer mb-sm mr-sm">PDF</a><a style="" data-toggle="modal" data-code="<?=$paid_credit_note->company_code?>" onclick="deleteBilling(<?=$paid_credit_note->billing_id?>, null, <?=$paid_credit_note->credit_note_id?>)" class="btn btn-primary pointer mb-sm">Delete</a></td>
										</tr>
										<?php
											$i++;
												}
										?>
									</tbody>
								</table>
							</div>
							<div id="w2-recurring" class="tab-pane">
								
								<table class="table table-bordered table-striped mb-none" id="datatable-recurring">
									<thead><tr>
										<th style="text-align: center">No</th>
										<th style="text-align: center">Company Name</th>
										<th style="text-align: center">Issues Date</th>
										<th style="text-align: center">Invoice No</th>
										<th style="text-align: center">Amount</th>
										<th style="text-align: center">Status</th>
										<th style="text-align: center; width: 100px;"></th>
									</tr>
									</thead>
									<tbody id="recurring_body">
										<?php
											$i = 1;
											//print_r($receipt);
											foreach($recurring_billing as $recurring_bill)
											{
												$date_1 = $recurring_bill->recu_invoice_issue_date;
												$array = explode('/', $date_1);
												$tmp = $array[0];
												$array[0] = $array[1];
												$array[1] = $tmp;
												unset($tmp);
												$date_2 = implode('/', $array);
										?>
										<tr>
											
											<td style="text-align: right"><!-- <label> &nbsp;<?=$i?></label> -->
											<td><?=$recurring_bill->company_name?></td>
											<td style="text-align: center"><span style="display:none"><?=date("Ymd",strtotime($date_2))?></span><?=$recurring_bill->recu_invoice_issue_date?></td>
											<td><a href="billings/edit_recurring_bill/<?=$recurring_bill->id?>" class="pointer mb-sm mt-sm mr-sm  "><?=$recurring_bill->invoice_no?></a></td>
											<td class="text-right"><?=number_format($recurring_bill->amount,2)?></td>
											<td class="text-right">
												<?php
													if($recurring_bill->recurring_status == 0)
													{
														echo "Non-Active";
													}
													else
													{
														echo "Active";
													}
												?>
											</td>
											<td><a style="" data-toggle="modal" data-code="<?=$recurring_bill->company_code?>" onclick="deleteBilling(<?=$recurring_bill->id?>)" class="btn btn-primary pointer mb-sm">Delete</a></td>
										</tr>
										<?php
											$i++;
												}
										?>
									</tbody>
								</table>
							</div>
							
						</div>

				</div>
				<!-- <from id="form_pdf_id" action="createbillingpdf/create_billing_pdf" method="post"> -->
				<!-- <?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form', 'id' => 'form_pdf_id');echo form_open("createbillingpdf/create_billing_pdf", $attrib);?>
					<input name="pdf_id" id="pdf_id" type="hidden" value="">
				 <?php echo form_close(); ?> -->
				
				<div id="modal_payment" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;">
					
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
										<td colspan=2><div class="input-group" style="width: 100%;"><input type="text" id="reference_no" name="reference_no" class="form-control reference_no"/></div></td>
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
				<div id="modal_credit_note" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;">
					
					<div class="modal-dialog">
						<div class="modal-content">
							<header class="panel-heading">
								<h2 class="panel-title">Credit Note</h2>
							</header>
							<form id="form_credit_note">
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
											<div class="input-group" id="credit_note_date" style="width: 100%;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="credit_note_date form-control" name="credit_note_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker required value="">
											</div>
										</div>
										</td>
										<!-- <?php $now = getDate();echo $now['mday'].'/'.$now['mon']."/".$now['year'];?> -->
										<!-- <td colspan=2><input type="text" data-plugin-datepicker data-date-format="dd/mm/yyyy" data-date-start-date="0d" placeholder="Date"/></td> -->

									</tr>
									<tr>
										<td align=right colspan=3>Total Amount Discounted</td>
										<td colspan=2><div class="input-group" style="width: 100%;"><input type="text" name="total_amount_discounted" class="form-control numberdes text-right" id="total_amount_discounted" value=""/></div></td>
									</tr>
									<!-- <tr>
										<td align=right colspan=3>Payment Mode</td>
										<td colspan=2><div class="input-group" style="width: 100%;"><select id="payment_mode" class="form-control payment_mode" style="text-align:right; width: 100%;" name="payment_mode">
						                    <option value="0">Select Payment Mode</option>
						                </select></div></td>
									</tr> -->
									<tr>
										<td align=right colspan=3>Credit Note No</td>
										<td colspan=2><div class="input-group" style="width: 100%;"><input type="text" name="credit_note_no" class="form-control credit_note_no"/></div></td>
									</tr>
									<!-- <tr>
										<td align=right colspan=3>Reference No</td>
										<td colspan=2><div class="input-group" style="width: 100%;"><input type="text" id="reference_no" name="reference_no" class="form-control reference_no"/></div></td>
									</tr> -->
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
								<button type="submit" class="btn btn-primary" name="saveCreditNote" id="saveCreditNote">Save</button>
								<input type="button" class="btn btn-default " data-dismiss="modal" name="cancel_credit_note" value="Cancel">
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

	//var template = <?php echo json_encode($template);?>;
	var billing_info = <?php echo json_encode($billings);?>;
	var currency_info = <?php echo json_encode($currency);?>;
	var bool_open_receipt = <?php echo json_encode($open_receipt);?>;
	var company_code = <?php echo json_encode($company_code);?>;
	var billing_check_state = <?php echo json_encode($_POST['billing_check_state']) ?>;

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

	if(access_right_paid_module == "read")
	{
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
	/*$("#button").click(function(){$("#buttonclick").toggle(); });
	$("#button1").click(function(){$("#buttonclick").toggle(); });*/
	//console.log(<?php echo json_encode($paid_billings);?>);
	

	/*$(document).on('click',"#saveReceipt",function(e){
		//e.preventDefault();
		$("#form_receipt").submit();
		//e.preventDefault();
	});*/
	//?v=<?=$this->config->item("curr_ver");?>
</script> 
<script src="themes/default/assets/js/billings.js?v=30eee4fc8d1b59e4584b0d39edfa42058" charset="utf-8"></script>
<style>
	#buttonclick .datatables-header {
		display:none;
	}
</style>