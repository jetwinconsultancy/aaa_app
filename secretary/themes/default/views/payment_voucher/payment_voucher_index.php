<div class="header_between_all_section">
<section class="panel">
	<header class="panel-heading">
		<div class="panel-actions" style="height:80px">
									
			<a class="create_vendor amber" href="<?= base_url();?>payment_voucher/create_vendor" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Create Vendor" ><i class="fa fa-plus-circle  amber" style="font-size:16px;height:45px;"></i> Create Vendor</a>
			<?php
				if($_SESSION['group_id'] == 2 || $_SESSION['group_id'] == 5 || $_SESSION['group_id'] == 6)
				{
					echo '<a class="create_payment_voucher amber" href="'.base_url().'payment_voucher/create_payment_voucher" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;display: none;" data-original-title="Create Payment Voucher" ><i class="fa fa-plus-circle amber" style="font-size:16px;height:45px;"></i> Create Payment</a>';
				}
			?>

			<a class="create_claim amber" href="<?= base_url();?>payment_voucher/create_claim" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;display: none;" data-original-title="Create Claim" ><i class="fa fa-plus-circle amber" style="font-size:16px;height:45px;"></i> Create Claim</a>

			<a class="create_pv_receipt amber" href="<?= base_url();?>payment_voucher/create_pv_receipt" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;display: none;" data-original-title="Create Receipt" ><i class="fa fa-plus-circle amber" style="font-size:16px;height:45px;"></i> Create Receipt</a>
		</div>
		<h2></h2>
	</header>
	
	<div class="panel-body">
		<div class="col-md-12">
			
			<div class="row datatables-header">
				<?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form', 'id' => 'form_search_payment_voucher');
					echo form_open_multipart("payment_voucher", $attrib);
					
				?>
				<div class="col-sm-7">
					<div class="col-md-5" style="display: none">
						<select class="form-control" name="type">
							<option value="all" <?=$_POST['type'] == "all"?'selected':'';?>>All</option>
							<option value="company_name" <?=$_POST['type']=='company_name'?'selected':'';?>>Company Name</option>
						</select>
					</div>
					<div class="col-md-12 person_search_input">
						<input type="text" class="form-control search" id="w2-username" name="search" placeholder="Search" value="<?=isset($_POST['search'])?$_POST['search']:'';?>">
						<input type="hidden" class="form-control submit_pv_check_state" name="pv_check_state" value="<?=isset($_POST['pv_check_state'])?$_POST['pv_check_state']:'vendor_info';?>">
					</div>
					<div class="col-sm-8">
						<label class="control-label">Date range</label>
						<div class="input-daterange input-group" data-plugin-datepicker data-date-format="dd/mm/yyyy">
							<span class="input-group-addon">
								<i class="far fa-calendar-alt"></i>
							</span>
							<input type="text" class="form-control start" name="start" value="<?=isset($_POST['start'])?$_POST['start']:'';?>">
							<span class="input-group-addon">to</span>
							<input type="text" class="form-control end" name="end" value="<?=isset($_POST['end'])?$_POST['end']:'';?>">
						</div>
					</div>
					
				</div>
				<?= form_close();?>
				<div class="col-md-5 search_group_button">
					<input type="submit" class="btn btn-primary" id="searchResult" name="searchResult" value="Search"/>
					<a href="payment_voucher" class="btn btn-primary">Show All</a>
					<input type="button" class="btn btn-primary" id="exportExcel" name="exportExcel" value="Export Excel" style="display: none"/>
				</div>
			</div>
		
					<div id="buttonclick">
						<div class="panel-body">
						<ul class="nav nav-tabs nav-justify">
							<li class="pv_check_state active" data-information="vendor_info">
								<a href="#w2-vendor_info" data-toggle="tab" class="text-center">
									<span class="badge hidden-xs">1</span>
									Vendor
								</a>
							</li>

							<li class="pv_check_state" data-information="payment_voucher">
								<a href="#w2-payment_voucher" data-toggle="tab" class="text-center">
									<span class="badge hidden-xs">2</span>
									Payment
								</a>
							</li>

							<li class="pv_check_state" data-information="claim">
								<a href="#w2-claim" data-toggle="tab" class="text-center">
									<span class="badge hidden-xs">3</span>
									Claim
								</a>
							</li>

							<li class="pv_check_state" data-information="pv_receipt">
								<a href="#w2-pv_receipt" data-toggle="tab" class="text-center">
									<span class="badge hidden-xs">4</span>
									Receipt
								</a>
							</li>
						</ul>

						<div class="tab-content">
							<?php if ($vendor_module != 'none') { ?> 
							<div id="w2-vendor_info" class="tab-pane active">
								<table class="table table-bordered table-striped mb-none" id="datatable-vendor_info">
									<thead>
										<tr style="background-color:white;">
											<th rowspan="2" style="text-align: center">No</th>
											<th rowspan="2" class="text-center">Vendor No</th>
											<th rowspan="2" class="text-center">Company Name</th>
											<th colspan="3" class="text-center">Contact</th>
											<th rowspan="2"></th>

										</tr>
										<tr style="background-color:white;">
											<th style="text-align: center">Name</th>
											<th class="text-center">Phone</th>
											<th class="text-center">E-Mail</th>
										</tr>
									</thead>
									<tbody id="vendor_info_body">
										<?php
											$i=1;
											if($vendor)
											{
												foreach($vendor as $key=>$c )
												{
										?>
										<tr>
											<td style="text-align: right"></td>
											<td style="width: 100px;"><?=$c["vendor_code"]?></td>
											<td>
												<div style="width: 200px; word-break:break-all;" >
													<a class="" href="<?=site_url('payment_voucher/edit_vendor/'.$c["id"]);?>" data-name="<?=$c["company_name"]?>" style="cursor:pointer" data-toggle="tooltip" data-trigger="hover" data-original-title="Edit This Client"><?=ucwords(substr($c["company_name"],0,24))?><span id="f<?=$i?>" style="display:none;cursor:pointer"><?=substr($c["company_name"],24,strlen($c["company_name"]))?></span></a>
													<?php
														if(strlen($c['company_name']) > 24)
														{
															echo '<a class="tonggle_readmore" data-id=f'.$i.'>...</a>';
														}
														
													?>
												</div>
											</td>

											<?php if((!$Individual && $Individual == true) || (!$Individual && $Individual == null && !$Client)) {?>
											<td>
												<div style="width: 110px; word-break:break-all;" >
													
													<?php if($setup_module != null)
														{
															 if($setup_module != "full" && !$Admin){  ?>
														<?=ucwords(substr($c["name"],0,12))?><span id="e<?=$i?>" style="display:none;"><?=substr($c["name"],12,strlen($c["name"]))?>
														</span>
													<?php } else { ?>
														<a class="" href="<?=site_url('payment_voucher/edit_vendor/'.$c["id"]."/vendor_setup");?>" style="cursor:pointer" data-toggle="tooltip" data-trigger="hover" data-original-title="Edit This Contact"><?=ucwords(substr($c["name"],0,12))?><span id="e<?=$i?>" style="display:none;"><?=substr($c["name"],12,strlen($c["name"]))?>
														</span></a>
													
													<?php } } else { ?>
														<a class="" href="<?=site_url('payment_voucher/edit_vendor/'.$c["id"]."/vendor_setup");?>" style="cursor:pointer" data-toggle="tooltip" data-trigger="hover" data-original-title="Edit This Contact"><?=ucwords(substr($c["name"],0,12))?><span id="e<?=$i?>" style="display:none;"><?=substr($c["name"],12,strlen($c["name"]))?>
														</span></a>
													<?php } ?>

													
													<?php
														if(strlen($c['name']) > 12)
														{
															echo '<a class="tonggle_readmore" data-id=e'.$i.'>...</a>';
														}
														
													?>
												</div>
											</td>
										
											<td class="text-left">
												<div style="width: 110px; word-break:break-all;" >
													<?php if($setup_module != null)
														{ 
															if($setup_module != "full" && !$Admin){  ?>
														<?=ucwords(substr($c["phone"],0,12))?><span id="k<?=$i?>" style="display:none;"><?=substr($c["phone"],12,strlen($c["phone"]))?></span>
													<?php } else { ?>
														<a class="" href="<?=site_url('payment_voucher/edit_vendor/'.$c["id"]."/vendor_setup");?>" style="cursor:pointer" data-toggle="tooltip" data-trigger="hover" data-original-title="Edit This Contact"><?=ucwords(substr($c["phone"],0,12))?><span id="k<?=$i?>" style="display:none;"><?=substr($c["phone"],12,strlen($c["phone"]))?>
														</span></a>
													
													<?php } } else { ?>
														<a class="" href="<?=site_url('payment_voucher/edit_vendor/'.$c["id"]."/vendor_setup");?>" style="cursor:pointer" data-toggle="tooltip" data-trigger="hover" data-original-title="Edit This Contact"><?=ucwords(substr($c["phone"],0,12))?><span id="k<?=$i?>" style="display:none;"><?=substr($c["phone"],12,strlen($c["phone"]))?>
														</span></a>
													<?php } ?>
													<?php
														if(strlen($c['phone']) > 12)
														{
															echo '<a class="tonggle_readmore" data-id=k'.$i.'>...</a>';
														}
														
													?>
												</div>
											</td>
											<td class="text-left">
												<div style="width: 150px; word-break:break-all;" >

													<?php if($setup_module != null)
														{
															if($setup_module != "full" && !$Admin){  ?>
														<?=(substr($c["email"],0,12))?><span id="n<?=$i?>" style="display:none;"><?=substr($c["email"],12,strlen($c["email"]))?></span>
													<?php } else { ?>
														<a class="" href="<?=site_url('payment_voucher/edit_vendor/'.$c["id"]."/vendor_setup");?>" style="cursor:pointer" data-toggle="tooltip" data-trigger="hover" data-original-title="Edit This Contact"><?=(substr($c["email"],0,12))?><span id="n<?=$i?>" style="display:none;"><?=substr($c["email"],12,strlen($c["email"]))?>
														</span></a>
													
													<?php } } else { ?>
														<a class="" href="<?=site_url('payment_voucher/edit_vendor/'.$c["id"]."/vendor_setup");?>" style="cursor:pointer" data-toggle="tooltip" data-trigger="hover" data-original-title="Edit This Contact"><?=(substr($c["email"],0,12))?><span id="n<?=$i?>" style="display:none;"><?=substr($c["email"],12,strlen($c["email"]))?>
														</span></a>
													<?php } ?>
													<?php
														if(strlen($c['email']) > 12)
														{
															echo '<a class="tonggle_readmore" data-id=n'.$i.'>...</a>';
														}
														
													?>
												</div>

												<?php } ?>
											</td>
											<td>
												<a style="" data-toggle="modal" data-code="<?=$c["supplier_code"]?>" onclick="deletePV(<?=$c["id"]?>)" class="btn btn-primary pointer mb-sm">Delete</a>
											</td>
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

							<?php if ($payment_voucher_list_module != 'none') { ?> 
							<div id="w2-payment_voucher" class="tab-pane">
								<table class="table table-bordered table-striped mb-none" id="datatable-payment_voucher">
									<thead>
										<tr>
											<th style="text-align: center">No</th>
											<th style="text-align: center">Company Name</th>
											<th style="text-align: center">Payment Date</th>
											<th style="text-align: center">Payment No</th>
											<th style="text-align: center">Currency</th>
											<th style="text-align: center">Amount</th>
											<th style="text-align: center">Status</th>
											<th style="text-align: center; width: 210px;"></th>
										</tr>
									</thead>
									<tbody id="payment_voucher_body">
										<?php
											$i = 1;
											if($payment_voucher)
											{
												foreach($payment_voucher as $pv)
												{
													$date_1 = $pv->payment_voucher_date;
													$array = explode('/', $date_1);
													$tmp = $array[0];
													$array[0] = $array[1];
													$array[1] = $tmp;
													unset($tmp);
													$date_2 = implode('/', $array);

													if(($_SESSION['group_id'] == 6 && $pv->bank_acc_id == 0) || $_SESSION['group_id'] == 2 || $this->session->userdata('email_for_user') == "penny@aaa-global.com" || $_SESSION['group_id'] == 5)
													{
										?>
										<tr>
											
											<td style="text-align: right"></td>
											<td><?=$pv->vendor_name?></td>
											<td style="text-align: center"><span style="display:none"><?=date("Ymd",strtotime($date_2))?></span><?=$pv->payment_voucher_date?></td>
											<?php
												if(($_SESSION['group_id'] != 2 && $_SESSION['group_id'] != 6 && $_SESSION['group_id'] != 5 && $pv->approved_by != NULL))
												{
													echo '<td>'.$pv->payment_voucher_no.'</td>';
												}
												elseif($_SESSION['group_id'] == 5 && $this->session->userdata('email_for_user') == "penny@aaa-global.com")
												{
													echo '<td><a href="payment_voucher/edit_payment_voucher/'.$pv->id.'" class="pointer mb-sm mt-sm mr-sm  ">'.$pv->payment_voucher_no.'</a></td>';
												}
												else
												{
													echo '<td><a href="payment_voucher/edit_payment_voucher/'.$pv->id.'" class="pointer mb-sm mt-sm mr-sm  ">'.$pv->payment_voucher_no.'</a></td>';
												}
												
											?>
											<td><?=$pv->currency_name?></td>
											<td class="text-right"><?=number_format($pv->amount,2)?></td>
											<?php
												if($pv->status == 0)
												{
													echo '<td>Pending</td>';
												}
												else if($pv->status == 1)
												{
													echo '<td>Deleted</td>';
												}
												else if($pv->status == 2)
												{
													echo '<td>Rejected</td>';
												}
												else if($pv->status == 3)
												{
													echo '<td>Approved - Unpaid</td>';
												}
												else if($pv->status == 4)
												{
													echo '<td>Approved & Paid</td>';
												}
												
											?>
											<td>
												<?php

													if($_SESSION['group_id'] == 6)
													{
														if($pv->status == 2)
														{
															$reason_value = "reasonPV('".$pv->cancel_reason."')";
															echo '<a data-toggle="modal" data-code="'.$pv->supplier_code.'" onclick="'.$reason_value.'" class="btn btn-primary pointer mb-sm mr-sm">Reason</a>';
														}
														elseif($pv->status == 3 || $pv->status == 4)
														{
															if($pv->bank_acc_id == 0)
															{
																echo '<a data-toggle="modal" data-code="'.$pv->supplier_code.'" onclick="exportPVPDF('.$pv->id.')" class="btn btn-primary pointer mb-sm mr-sm">PDF</a><a data-toggle="modal" onclick="updatePaymentChequeNum('.$pv->id.')" class="btn btn-primary pointer mb-sm mr-sm">Update</a>';
															}
															else
															{
																echo '<a data-toggle="modal" data-code="'.$pv->supplier_code.'" onclick="exportPVPDF('.$pv->id.')" class="btn btn-primary pointer mb-sm mr-sm">PDF</a>';
															}
														}
														else
														{
															echo '<a data-toggle="modal" data-code="'.$pv->supplier_code.'" onclick="exportPVPDF('.$pv->id.')" class="btn btn-primary pointer mb-sm mr-sm">PDF</a><a data-toggle="modal" data-code="'.$pv->supplier_code.'" onclick="cancelPV('.$pv->id.')" class="btn btn-primary open_credit_note pointer mb-sm mr-sm">Cancel</a>';
														}
													}
													else if($_SESSION['group_id'] == 2 || $_SESSION['group_id'] == 5)
													{
														if($pv->status == 2)
														{
															$reason_value = "reasonPV('".$pv->cancel_reason."')";
															echo '<a data-toggle="modal" data-code="'.$pv->supplier_code.'" onclick="'.$reason_value.'" class="btn btn-primary pointer mb-sm mr-sm">Reason</a><a style="" data-toggle="modal" data-code="'.$pv->supplier_code.'" onclick="deletePV('.$pv->id.')" class="btn btn-primary pointer mb-sm">Delete</a>';
														}
														elseif($pv->status == 3 || $pv->status == 4)
														{
															if($pv->bank_acc_id == 0)
															{
																echo '<a data-toggle="modal" data-code="'.$pv->supplier_code.'" onclick="exportPVPDF('.$pv->id.')" class="btn btn-primary pointer mb-sm mr-sm">PDF</a><a data-toggle="modal" onclick="updatePaymentChequeNum('.$pv->id.')" class="btn btn-primary pointer mb-sm mr-sm">Update</a>';
															}
															else
															{
																echo '<a data-toggle="modal" data-code="'.$pv->supplier_code.'" onclick="exportPVPDF('.$pv->id.')" class="btn btn-primary pointer mb-sm mr-sm">PDF</a>';
															}
															
														}
														else
														{

															if($this->session->userdata("user_id") == $claims->user_id)
															{
																$cancel_button = '<a data-toggle="modal" data-code="'.$pv->supplier_code.'" onclick="cancelPV('.$pv->id.')" class="btn btn-primary pointer mb-sm mr-sm">Cancel</a>';
															}
															else
															{
																$cancel_button = '<a data-toggle="modal" data-code="'.$pv->supplier_code.'" onclick="cancelPV('.$pv->id.')" class="btn btn-primary pointer mb-sm mr-sm">Reject</a>';
															}

															echo '<a data-toggle="modal" data-code="'.$pv->supplier_code.'" onclick="exportPVPDF('.$pv->id.')" class="btn btn-primary pointer mb-sm mr-sm">PDF</a>'.$cancel_button.'<a data-toggle="modal" data-code="'.$pv->supplier_code.'" onclick="approvePV('.$pv->id.')" class="btn btn-primary pointer mb-sm mr-sm">Approve</a><a style="" data-toggle="modal" data-code="'.$pv->supplier_code.'" onclick="deletePV('.$pv->id.')" class="btn btn-primary pointer mb-sm">Delete</a>';
														}
													}
													else
													{
														if($pv->status == 2)
														{
															$reason_value = "reasonPV('".$pv->cancel_reason."')";
															echo '<a data-toggle="modal" data-code="'.$pv->supplier_code.'" onclick="'.$reason_value.'" class="btn btn-primary pointer mb-sm mr-sm">Reason</a>';
														}
														elseif($pv->status == 3 || $pv->status == 4)
														{
															echo '<a data-toggle="modal" data-code="'.$pv->supplier_code.'" onclick="exportPVPDF('.$pv->id.')" class="btn btn-primary pointer mb-sm mr-sm">PDF</a>';
														}
														else
														{
															echo '<a data-toggle="modal" data-code="'.$pv->supplier_code.'" onclick="exportPVPDF('.$pv->id.')" class="btn btn-primary pointer mb-sm mr-sm">PDF</a>';
														}
													}
													
												?>
											</td>
										</tr>
										<?php
													$i++;
												}
											}
										}
										?>
									</tbody>
								</table>
							</div>
							<?php
								}
							?>

							<?php if ($claim_list_module != 'none') { ?> 
							<div id="w2-claim" class="tab-pane">
								<table class="table table-bordered table-striped mb-none" id="datatable-claim">
									<thead>
										<tr>
											<th style="text-align: center">No</th>
											<th style="text-align: center">User Name</th>
											<th style="text-align: center">Claim Date</th>
											<th style="text-align: center">Claim No</th>
											<th style="text-align: center">Currency</th>
											<th style="text-align: center">Amount</th>
											<th style="text-align: center">Status</th>
											<th style="text-align: center; width: 210px;"></th>
										</tr>
									</thead>
									<tbody id="claim_body">
										<?php
											$i = 1;
											if($claim)
											{
												foreach($claim as $claims)
												{
													$date_1 = $claims->claim_date;
													$array = explode('/', $date_1);
													$tmp = $array[0];
													$array[0] = $array[1];
													$array[1] = $tmp;
													unset($tmp);
													$date_2 = implode('/', $array);

													if(($_SESSION['group_id'] == 6 && $claims->bank_acc_id == 0) || $_SESSION['group_id'] == 2 || $_SESSION['group_id'] == 3 || $_SESSION['group_id'] == 5)
													{
										?>
										<tr>
											
											<td style="text-align: right"></td>
											<td><?=$claims->user_name?></td>
											<td style="text-align: center"><?=$claims->claim_date?><span style="display:none"><?=date("Ymd",strtotime($date_2))?></span></td>
											<?php
												echo '<td><a href="payment_voucher/edit_claim/'.$claims->id.'" class="pointer mb-sm mt-sm mr-sm  ">'.$claims->claim_no.'</a></td>';
											?>

											<td><?=$claims->currency_name?></td>
											<td class="text-right"><?=number_format($claims->amount,2)?></td>
											
											<?php
												if($claims->status == 0)
												{
													echo '<td>Pending</td>';
												}
												else if($claims->status == 1)
												{
													echo '<td>Deleted</td>';
												}
												else if($claims->status == 2)
												{
													echo '<td>Rejected</td>';
												}
												else if($claims->status == 3)
												{
													echo '<td>Approved - Unpaid</td>';
												}
												else if($claims->status == 4)
												{
													echo '<td>Approved & Paid</td>';
												}
												
											?>
											
											<td>
												<?php
													if($_SESSION['group_id'] == 6)
													{
														if($claims->status == 2)
														{
															$reason_value = "reasonClaim('".$claims->cancel_reason."')";
															echo '<a data-toggle="modal" data-code="'.$claims->user_id.'" onclick="'.$reason_value.'" class="btn btn-primary pointer mb-sm mr-sm">Reason</a>';
														}
														elseif($claims->status == 3 || $claims->status == 4)
														{
															if($claims->bank_acc_id == 0)
															{
																echo '<a data-toggle="modal" data-code="'.$claims->user_id.'" onclick="exportClaimPDF('.$claims->id.')" class="btn btn-primary pointer mb-sm mr-sm">PDF</a><a data-toggle="modal" data-code="'.$claims->user_id.'" onclick="updateClaimChequeNum('.$claims->id.')" class="btn btn-primary pointer mb-sm mr-sm">Update</a>';
															}
															else
															{
																echo '<a data-toggle="modal" data-code="'.$claims->user_id.'" onclick="exportClaimPDF('.$claims->id.')" class="btn btn-primary pointer mb-sm mr-sm">PDF</a>';
															}
														}
														else
														{
															echo '<a data-toggle="modal" data-code="'.$claims->user_id.'" onclick="exportClaimPDF('.$claims->id.')" class="btn btn-primary pointer mb-sm mr-sm">PDF</a><a data-toggle="modal" data-code="'.$claims->user_id.'" onclick="cancelClaim('.$claims->id.')" class="btn btn-primary open_credit_note pointer mb-sm mr-sm">Reject</a>';
														}
													}
													else if($_SESSION['group_id'] == 2 || $_SESSION['group_id'] == 5)
													{
														if($claims->status == 2)
														{
															$reason_value = "reasonClaim('".$claims->cancel_reason."')";
															echo '<a data-toggle="modal" data-code="'.$claims->user_id.'" onclick="'.$reason_value.'" class="btn btn-primary pointer mb-sm mr-sm">Reason</a><a style="" data-toggle="modal" data-code="'.$claims->user_id.'" onclick="deletePV('.$claims->id.')" class="btn btn-primary pointer mb-sm">Delete</a>';
														}
														elseif($claims->status == 3 || $claims->status == 4)
														{
															if($claims->bank_acc_id == 0)
															{
																echo '<a data-toggle="modal" data-code="'.$claims->user_id.'" onclick="exportClaimPDF('.$claims->id.')" class="btn btn-primary pointer mb-sm mr-sm">PDF</a><a data-toggle="modal" data-code="'.$claims->user_id.'" onclick="updateClaimChequeNum('.$claims->id.')" class="btn btn-primary pointer mb-sm mr-sm">Update</a>';
															}
															else
															{
																echo '<a data-toggle="modal" data-code="'.$claims->user_id.'" onclick="exportClaimPDF('.$claims->id.')" class="btn btn-primary pointer mb-sm mr-sm">PDF</a>';
															}
														}
														else
														{
															if($this->session->userdata("user_id") == $claims->user_id)
															{
																$cancel_button = '<a data-toggle="modal" data-code="'.$claims->user_id.'" onclick="cancelClaim('.$claims->id.')" class="btn btn-primary pointer mb-sm mr-sm">Cancel</a>';
															}
															else
															{
																$cancel_button = '<a data-toggle="modal" data-code="'.$claims->user_id.'" onclick="cancelClaim('.$claims->id.')" class="btn btn-primary pointer mb-sm mr-sm">Reject</a>';
															}

															echo '<a data-toggle="modal" data-code="'.$claims->user_id.'" onclick="exportClaimPDF('.$claims->id.')" class="btn btn-primary pointer mb-sm mr-sm">PDF</a>'.$cancel_button.'<a data-toggle="modal" data-code="'.$claims->user_id.'" onclick="approveClaim('.$claims->id.')" class="btn btn-primary pointer mb-sm mr-sm">Approve</a><a style="" data-toggle="modal" data-code="'.$claims->user_id.'" onclick="deletePV('.$claims->id.')" class="btn btn-primary pointer mb-sm">Delete</a>';
														}
													}
													else
													{
														if($claims->status == 2)
														{
															$reason_value = "reasonClaim('".$claims->cancel_reason."')";
															echo '<a data-toggle="modal" data-code="'.$claims->user_id.'" onclick="'.$reason_value.'" class="btn btn-primary pointer mb-sm mr-sm">Reason</a>';
														}
														elseif($claims->status == 3 || $claims->status == 4)
														{
															echo '<a data-toggle="modal" data-code="'.$claims->user_id.'" onclick="exportClaimPDF('.$claims->id.')" class="btn btn-primary pointer mb-sm mr-sm">PDF</a>';
														}
														else
														{
															echo '<a data-toggle="modal" data-code="'.$claims->user_id.'" onclick="exportClaimPDF('.$claims->id.')" class="btn btn-primary pointer mb-sm mr-sm">PDF</a>';
														}
													}
													
												?>

												
											</td>
										</tr>
										<?php
												$i++;
												}
											}
										}
										?>
										
									</tbody>
								</table>
							</div>
							<?php
								}
							?>

							<?php if ($pv_receipt_list_module != 'none') { ?> 
							<div id="w2-pv_receipt" class="tab-pane">
								<table class="table table-bordered table-striped mb-none" id="datatable-pv_receipt">
									<thead>
										<tr>
											<th style="text-align: center">No</th>
											<th style="text-align: center">Client Name</th>
											<th style="text-align: center">Receipt Date</th>
											<th style="text-align: center">Receipt No</th>
											<th style="text-align: center">Currency</th>
											<th style="text-align: center">Amount</th>
											<th style="text-align: center; width: 210px;"></th>
										</tr>
									</thead>
									<tbody id="pv_receipt_body">
										<?php
											$i = 1;
											if($pv_receipt)
											{
												foreach($pv_receipt as $receipt)
												{
													$date_1 = $receipt->receipt_date;
													$array = explode('/', $date_1);
													$tmp = $array[0];
													$array[0] = $array[1];
													$array[1] = $tmp;
													unset($tmp);
													$date_2 = implode('/', $array);
										?>
										<tr>
											
											<td style="text-align: right"></td>
											<td><?=$receipt->client_name?></td>
											<td style="text-align: center"><span style="display:none"><?=date("Ymd",strtotime($date_2))?></span><?=$receipt->receipt_date?></td>
											<?php
												if(($_SESSION['group_id'] != 2 && $_SESSION['group_id'] != 5 && $_SESSION['group_id'] != 6))
												{
													echo '<td>'.$receipt->receipt_no.'</td>';
												}
												else
												{
													echo '<td><a href="payment_voucher/edit_pv_receipt/'.$receipt->id.'" class="pointer mb-sm mt-sm mr-sm  ">'.$receipt->receipt_no.'</a></td>';
												}
												
											?>

											<td><?=$receipt->currency_name?></td>
											<td class="text-right"><?=number_format($receipt->amount,2)?></td>
											<td>
												<?php
													echo '<a data-toggle="modal" data-code="'.$receipt->company_code.'" onclick="exportReceiptPDF('.$receipt->id.')" class="btn btn-primary pointer mb-sm mr-sm">PDF</a><a style="" data-toggle="modal" data-code="'.$receipt->company_code.'" onclick="deletePV('.$receipt->id.')" class="btn btn-primary pointer mb-sm">Delete</a>';
												?>
											</td>
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
					</div>
				</div>
			</div>
			<div id="modal_receipt_cheque_list" class="modal fade modal_receipt_cheque" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;">
				<div class="modal-dialog modal-dialog_cliam_cheque">
					<div class="modal-content">
						<header class="panel-heading">
							<h2 class="panel-title">Update</h2>
						</header>
						<form id="form_receipt_cheque">
						<div class="modal-body">
							<input type="hidden" name="receipt_cheque_id" id="receipt_cheque_id" value="">
							<table class="table table-bordered table-striped table-condensed mb-none" id="receipt_cheque_list_table">
								<tbody id="receipt_cheque_info">
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
											<div class="cheque-number-input-group">
												<div class="validate" style="width: 50%;">
													<input type="text" name="cheque_number" class="form-control cheque_number" id="cheque_number" value=""/>
												</div>
											</div>
										</td>
									</tr>
								</tbody>
	
							</table>
						</div>
						</form>
						<div class="modal-footer">
							<button type="button" class="btn btn-primary" name="saveReceiptChequeList" id="saveReceiptChequeList">Update</button>
							<input type="button" class="btn btn-default " data-dismiss="modal" name="cancelReceiptChequeList" value="Cancel">
						</div>
					</div>
				</div>
			</div>
			<div id="modal_claim_cheque_list" class="modal fade modal_claim_cheque" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;">
				<div class="modal-dialog modal-dialog_cliam_cheque">
					<div class="modal-content">
						<header class="panel-heading">
							<h2 class="panel-title">Update</h2>
						</header>
						<form id="form_claim_cheque">
						<div class="modal-body">
							<input type="hidden" name="claim_cheque_id" id="claim_cheque_id" value="">
							<table class="table table-bordered table-striped table-condensed mb-none" id="claim_cheque_list_table">
								<tbody id="claim_cheque_info">
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
											<div class="cheque-number-input-group">
												<div class="validate" style="width: 50%;">
													<input type="text" name="cheque_number" class="form-control cheque_number" id="cheque_number" value=""/>
												</div>
											</div>
										</td>
									</tr>
								</tbody>
	
							</table>
						</div>
						</form>
						<div class="modal-footer">
							<button type="button" class="btn btn-primary" name="saveClaimChequeList" id="saveClaimChequeList">Update</button>
							<input type="button" class="btn btn-default " data-dismiss="modal" name="cancelClaimChequeList" value="Cancel">
						</div>
					</div>
				</div>
			</div>
			<div id="modal_payment_cheque_list" class="modal fade modal_payment_cheque" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;">
				<div class="modal-dialog modal-dialog_cliam_cheque">
					<div class="modal-content">
						<header class="panel-heading">
							<h2 class="panel-title">Update</h2>
						</header>
						<form id="form_payment_cheque">
						<div class="modal-body">
							<input type="hidden" name="payment_cheque_id" id="payment_cheque_id" value="">
							<table class="table table-bordered table-striped table-condensed mb-none" id="payment_cheque_list_table">
								<tbody id="payment_cheque_info">
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
											<div class="cheque-number-input-group">
												<div class="validate" style="width: 50%;">
													<input type="text" name="cheque_number" class="form-control cheque_number" id="cheque_number" value=""/>
												</div>
											</div>
										</td>
									</tr>
								</tbody>
	
							</table>
						</div>
						</form>
						<div class="modal-footer">
							<button type="button" class="btn btn-primary" name="savePaymentChequeList" id="savePaymentChequeList">Update</button>
							<input type="button" class="btn btn-default " data-dismiss="modal" name="cancelPaymentChequeList" value="Cancel">
						</div>
					</div>
				</div>
			</div>
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

	var active_tab = <?php echo json_encode($active_tab) ?>;
	var pv_check_state = <?php echo json_encode(isset($_POST['pv_check_state'])?$_POST['pv_check_state']:'') ?>;

</script> 
<script src="themes/default/assets/js/payment_voucher_index.js?v=7777772312rewrewrwer" charset="utf-8"></script>
<style>
	#buttonclick .datatables-header {
		display:none;
	}
</style>