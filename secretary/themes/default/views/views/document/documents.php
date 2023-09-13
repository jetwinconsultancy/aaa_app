<div class="header_between_all_section">
<section class="panel">
	<!-- <header class="panel-heading">
		<div class="panel-actions" style="height:80px">
									

			<a class="edit_client amber" href="<?= base_url();?>billings/create_billing" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Create Billing" ><i class="fa fa-plus-circle  amber" style="font-size:16px;height:45px;"></i> Create Billing</a>
		</div>
		<h2></h2>
			
	</header> -->
	
	<div class="panel-body">
		<div class="col-md-12">
			
			<div class="row datatables-header">
				<?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form', 'id' => 'form_search_document');
					echo form_open_multipart("documents", $attrib);
					
				?>
				<div class="col-sm-7">
					<div class="col-md-5">
						<select class="form-control" name="type">
							<option value="all" <?=isset($_POST['type'])?($_POST['type'] == "all"?'selected':''):''?>>All</option>
							<!-- <option value="company_name" <?=isset($_POST['type'])?($_POST['type']=='company_name'?'selected':''):''?>>Company Name</option> -->
							<option value="document_name" <?=isset($_POST['type'])?($_POST['type'] == "document_name"?'selected':''):''?>>Document Name</option>
						</select>
					</div>
					<!-- <?=$_POST['type']?> -->
					<div class="col-md-7 person_search_input">
						<input type="text" class="form-control" id="w2-username" name="search" placeholder="Search" value="<?=isset($_POST['search'])?$_POST['search']:'';?>">
						<input type="hidden" class="form-control submit_document_check_state" name="document_check_state" value="<?=isset($_POST['document_check_state'])?$_POST['document_check_state']:'pending';?>">
					</div>
					<div class="col-sm-12">
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
				</div>
				<?= form_close();?>
				<div class="col-md-5 search_group_button">
					<input type="submit" class="btn btn-primary" id="searchResult" name="searchResult" value="Search"/>
					<a href="documents" class="btn btn-primary">Show All Documents</a>
					<!-- need hide  -->
					<!-- <?php if((!$Individual && $Individual == true) || (!$Individual && $Individual == null && !$Client)) {?>
						<input type="button" class="btn btn-primary" onclick="exportDocumentPDF()" value="Export PDF">
					<?php
						}
					?>  -->
					<!-- <a href="personprofile" class="btn btn-primary">Show All Person</a> -->
				</div>
			</div>
			
			
					<!-- <div id="buttonclick" style="display:none;"> -->
					<div id="buttonclick">
						<div class="panel-body">
						<ul class="nav nav-tabs nav-justify">

							<?php if((!$Individual && $Individual == true) || (!$Individual && $Individual == null && !$Client)) {?>
								<?php if ($pending_module != 'none') { ?> 
								<li class="document_check_state active" data-information="pending">
									<a href="#w2-pending" data-toggle="tab" class="text-center">
										<span class="badge hidden-xs">1</span>
										Pending
									</a>
								</li>
								<?php
									}
								?> 
								<?php if ($all_module != 'none') { ?> 
								<li class="document_check_state" data-information="all_document">
									<a href="#w2-all_document" data-toggle="tab" class="text-center">
										<span class="badge hidden-xs">2</span>
										All
									</a>
								</li>
								<?php
									}
								?> 
								<?php if ($master_module != 'none') { ?> 
								<li class="document_check_state" data-information="master">
									<a href="#w2-master" data-toggle="tab" class="text-center">
										<span class="badge hidden-xs">3</span>
										Master
									</a>
								</li>
								<?php
									}
								?> 
								<?php if ($reminder_module != 'none') { ?> 
								<li class="document_check_state" data-information="reminder">
									<a href="#w2-reminder" data-toggle="tab" class="text-center">
										<span class="badge hidden-xs">4</span>
										Reminder
									</a>
								</li>
								<?php
									}
								?> 
							<?php
								}
							?> 
							<?php if($Individual || $Client) {?>
								<li class="document_check_state active" data-information="all_document">
									<a href="#w2-all_document" data-toggle="tab" class="text-center">
										<span class="badge hidden-xs">1</span>
										All
									</a>
								</li>
							<?php
								}
							?>
						</ul>

						<div class="tab-content">
							<?php if((!$Individual && $Individual == true) || (!$Individual && $Individual == null && !$Client)) {?>
							<?php if ($pending_module != 'none') { ?> 
							<div id="w2-pending" class="tab-pane active">
								<!-- <a data-toggle="modal" data-code="<?=$bill->company_code?>" class="open_update pointer mb-sm mt-sm mr-sm">Update</a> -->
								
								<!-- <div style="height: 40px;"><a href="<?= base_url();?>documents/create_pending_document" style="color: #FFBF00;width:170px;border-bottom:none; outline: none !important;text-decoration: none;font-weight:bold;position: absolute;right:15px;"><span class="pending_Add" id="pending_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Click to Add Document" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Create Document</span></a></div> -->
								
								<table class="table table-bordered table-striped mb-none" id="datatable-pending" style="width:100%;">
									<thead>
										<tr>
											<!-- need hide  -->
											<th style="display: none; width:20px !important;"><input type="checkbox" name="selectallpendingdocument" class="selectallpendingdocument"/></th>
											 <!-- style="display: none;" -->
											<th style="text-align: center;width:20px !important;padding-right:2px !important;padding-left:2px !important;">No</th>
											<th style="text-align: center;width:250px !important;padding-right:2px !important;padding-left:2px !important;">Client</th>
											<th style="text-align: center;width:150px !important;padding-right:2px !important;padding-left:2px !important;">Transaction ID</th>
											<th style="text-align: center;width:310px !important;padding-right:2px !important;padding-left:2px !important;">Transaction Name</th>
											<!-- <th style="text-align: center;width:310px !important;padding-right:2px !important;padding-left:2px !important;">Document Name</th> --><!-- need hide -->
											<th style="text-align: center;width:150px !important;padding-right:2px !important;padding-left:2px !important;">Created On</th>
											<th style="text-align: center;width:150px !important;padding-right:2px !important;padding-left:2px !important;">Created By</th>
											<th style="text-align: center;width:30px !important;padding-right:2px !important;padding-left:2px !important;">Received On</th>
											<th style="text-align: center;width:30px !important;padding-right:2px !important;padding-left:2px !important;"></th>
										</tr>
									</thead>
									<tbody id="pending_body">
										<?php
											$i = 1;
											//print_r($pending_documents);
											if($pending_documents)
											{
												foreach($pending_documents as $pending)
												{
													if($pending->type != null)
													{
														$document_link = $pending->id.'/'.$pending->type;
													}
													else
													{
														$document_link = $pending->id;
													}
										?>


										<tr>
											<!-- need hide  -->
											<td style="display: none;width:50px"><input type="checkbox" class="" id="" name="pending_document_checkbox" value="<?=$document_link?>" style="width:50px"></td>  
											<!-- style="display: none;width:50px" -->
											<td style="text-align: right"><label></label></td>
											<td><?=ucwords(substr($pending->company_name,0,31))?><span id="g<?=$i?>" style="display:none;"><?=substr($pending->company_name,31,strlen($pending->company_name))?>
												</span>
												<?php
													if(strlen($pending->company_name) > 31)
													{
														echo '<a class="tonggle_readmore" data-id=g'.$i.'>...</a>';
													}
													
												?>
												<input class="hidden" name="each_document_id" value="<?=$pending->id?>">
												<input class="hidden" name="each_company_name" value="<?=$pending->company_name?>">
											</td>
											<td><?= $pending->transaction_code?></td>
											<td><?=ucwords(substr($pending->triggered_by_name,0,36))?><span id="f<?=$i?>" style="display:none;"><?=substr($pending->triggered_by_name,36,strlen($pending->triggered_by_name))?>
												</span>
												<?php
													if(strlen($pending->triggered_by_name) > 36)
													{
														echo '<a class="tonggle_readmore" data-id=f'.$i.'>...</a>';
													}
												?>
											</td>
											<!-- <td><a href="documents/edit_pending_document/<?=$document_link?>" class="pointer mb-sm mt-sm"><?=ucwords(substr($pending->document_name,0,36))?><span id="f<?=$i?>" style="display:none;"><?=substr($pending->document_name,36,strlen($pending->document_name))?>
												</span></a>
												<?php
													if(strlen($pending->document_name) > 36)
													{
														echo '<a class="tonggle_readmore" data-id=f'.$i.'>...</a>';
													}
													
												?>
											</td> --><!-- need hide -->
											<td style="text-align: center"><span style="display:none"><?=date("Ymd",strtotime($pending->created_at))?></span><?=date("d F Y",strtotime($pending->created_at))?></td>
											<td style="text-align: center"><?= $pending->first_name." ".$pending->last_name?></td>
											<td><?php 
													if($pending->received_on == null)
													{
														echo '<a id="add_pending_document_file" href="documents/add_pending_document_file/'.$document_link.'" class="btn btn-primary add_pending_document_file">Update</a>';
													}
													else
													{
														echo '<a href="documents/edit_pending_document_file/'.$document_link.'" class="pointer mb-sm mt-sm mr-sm">'.$pending->received_on.'';
													}
												?>
													
											</td>
											<td>
												<?php
													if($pending->type != null)
													{
														echo '<button type="button" class="btn btn-primary delete_pending_document" onclick="delete_transaction_pending_document(this)">Delete</button>';
													}
													else
													{
														echo '<button type="button" class="btn btn-primary delete_pending_document" onclick="delete_pending_document(this)">Delete</button>';
													}
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
							<?php } ?> 
							<?php if ($all_module != 'none') { ?> 
							<div id="w2-all_document" class="tab-pane <?php if($Individual || $Client) {?>active<?php } ?>">
								<!-- <div style="height: 40px;"><a href="<?= base_url();?>documents/create_pending_document" style="color: #FFBF00;width:170px;border-bottom:none; outline: none !important;text-decoration: none;font-weight:bold;position: absolute;right:15px;"><span class="all_Add" id="all_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Click to Add Document" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Create Document</span></a></div> -->
								<table class="table table-bordered table-striped mb-none" id="datatable-all_doc" style="width: 100%;">
									<thead>
										<tr>
											<th style="text-align: center;width:20px !important;padding-right:2px !important;padding-left:2px !important;">No</th>
											<th style="text-align: center;width:250px !important;padding-right:2px !important;padding-left:2px !important;">Client</th>
											<th style="text-align: center;width:150px !important;padding-right:2px !important;padding-left:2px !important;">Transaction ID</th>
											<th style="text-align: center;width:310px !important;padding-right:2px !important;padding-left:2px !important;">Transaction Name</th>
											<!-- <th style="text-align: center;width:310px !important;padding-right:2px !important;padding-left:2px !important;">Document Name</th> -->
											<th style="text-align: center;width:150px !important;padding-right:2px !important;padding-left:2px !important;">Created On</th>
											<th style="text-align: center;width:150px !important;padding-right:2px !important;padding-left:2px !important;">Created By</th>
											<th style="text-align: center;width:30px !important;padding-right:2px !important;padding-left:2px !important;">Received On</th>
											<?php if((!$Individual && $Individual == true) || (!$Individual && $Individual == null && !$Client)) {?>
												<th style="text-align: center;width:150px !important;padding-right:2px !important;padding-left:2px !important;">Received By</th>
												<th style="text-align: center;width:30px !important;padding-right:2px !important;padding-left:2px !important;"></th>
											<?php }?>
										</tr>
									</thead>
									<tbody id="all_document_body">
										<?php
											$i = 1;
											if($all_documents)
											{
												foreach($all_documents as $all)
												{
													if($all->type != null)
													{
														$document_link = $all->id.'/'.$all->type;
													}
													else
													{
														$document_link = $all->id;
													}
										?>
										<tr>
											<td style="text-align: right"><label></label></td>
											
											<td><?=ucwords(substr($all->company_name,0,29))?><span id="r<?=$i?>" style="display:none;"><?=substr($all->company_name,29,strlen($all->company_name))?>
												</span>
												<?php
													if(strlen($all->company_name) > 29)
													{
														echo '<a class="tonggle_readmore" data-id=r'.$i.'>...</a>';
													}
													
												?>
												<input class="hidden" name="each_document_id" value="<?=$all->id?>">
												<input class="hidden" name="each_company_name" value="<?=$all->company_name?>">
											</td>

											<!-- <?php if((!$Individual && $Individual == true) || (!$Individual && $Individual == null && !$Client)) {?>
												<td><a href="documents/edit_pending_document/<?=$document_link?>" class="pointer mb-sm mt-sm"><?=ucwords(substr($all->document_name,0,31))?><span id="t<?=$i?>" style="display:none;"><?=substr($all->document_name,31,strlen($all->document_name))?>
													</span></a>
													<?php
														if(strlen($all->document_name) > 31)
														{
															echo '<a class="tonggle_readmore" data-id=t'.$i.'>...</a>';
														}
														
													?>
												</td>
											<?php } ?> -->
											<!-- <?php if($Individual || $Client) {?>
												<td><?=ucwords(substr($all->document_name,0,31))?><span id="t<?=$i?>" style="display:none;"><?=substr($all->document_name,31,strlen($all->document_name))?>
													</span>
													<?php
														if(strlen($all->document_name) > 31)
														{
															echo '<a class="tonggle_readmore" data-id=t'.$i.'>...</a>';
														}
														
													?>
												</td>
											<?php } ?> -->
											<td><?= $all->transaction_code?></td>
											<td><?=ucwords(substr($all->triggered_by_name,0,31))?><span id="t<?=$i?>" style="display:none;"><?=substr($all->triggered_by_name,31,strlen($all->triggered_by_name))?>
												</span>
												<?php
													if(strlen($all->triggered_by_name) > 31)
													{
														echo '<a class="tonggle_readmore" data-id=t'.$i.'>...</a>';
													}
													
												?>
											</td>
											<td style="text-align: center"><span style="display:none"><?=date("Ymd",strtotime($all->created_at))?></span><?=date("d F Y",strtotime($all->created_at))?></td>
											<td style="text-align: center"><?= $all->created_by_first_name." ".$all->created_by_last_name?></td>
											<td><?php 
													if($all->received_on == null)
													{
														echo '<a id="add_all_document_file" href="documents/add_pending_document_file/'.$document_link.'" class="btn btn-primary add_all_document_file">Update</a>';
													}
													else
													{
														echo '<span style="display:none">'.date("Ymd",strtotime($all->received_on)).'</span><a href="documents/edit_pending_document_file/'.$document_link.'" class="pointer mb-sm mt-sm mr-sm">'.$all->received_on.'';
													}
												?>
													
											</td>
											<?php if((!$Individual && $Individual == true) || (!$Individual && $Individual == null && !$Client)) {?>
												<td style="text-align: center"><?= $all->received_by_first_name." ".$all->received_by_last_name?></td>
												<!-- <td><button type="button" class="btn btn-primary delete_pending_document" onclick="delete_pending_document(this)">Delete</button></td> -->
												<td>
													<?php
														if($all->type != null)
														{
															echo '<button type="button" class="btn btn-primary delete_pending_document" onclick="delete_transaction_pending_document(this)">Delete</button>';
														}
														else
														{
															echo '<button type="button" class="btn btn-primary delete_pending_document" onclick="delete_pending_document(this)">Delete</button>';
														}
													?>
												</td>
											<?php } ?>
											<!-- <td><a href="billings/edit_bill/<?=$bill->id?>" class="pointer mb-sm mt-sm mr-sm  "><?=$bill->invoice_no?></a></td>
											<td class="text-right"><?=number_format($bill->amount,2)?></td>
											<td class="text-right"><?=number_format($bill->outstanding,2)?></td>
											<td><a data-toggle="modal" data-code="<?=$bill->company_code?>" class="open_reciept pointer mb-sm mt-sm mr-sm  ">Receipt</a></td> -->
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
							<?php if((!$Individual && $Individual == true) || (!$Individual && $Individual == null && !$Client)) {?>
							<?php if ($master_module != 'none') { ?> 
							<div id="w2-master" class="tab-pane">
								<div style="height: 40px;"><a href="<?= base_url();?>documents/master" style="color: #FFBF00;width:170px;border-bottom:none; outline: none !important;text-decoration: none;font-weight:bold;position: absolute;right:15px;"><span id="master_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Click to Add Document" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Create Document</span></a></div>
								<table class="table table-bordered table-striped mb-none" id="datatable-master">
									<thead>
										<tr>
											<th style="text-align: center">No</th>
											<th style="text-align: center">Document Name</th>
											<th style="text-align: center">Triggered By</th>
											<th style="text-align: center"></th>
										</tr>
									</thead>
									<tbody id="master_body">
										<?php
											$i = 1;
											if($document_master)
											{
												foreach($document_master as $master)
												{
										?>
										<tr>
											<td style="text-align: right"><label><?=$i?></label></td>
											<td><a href="documents/edit_master/<?=$master->id?>" class="pointer mb-sm mt-sm"><?=ucwords(substr($master->document_name,0,31))?><span id="w<?=$i?>" style="display:none;"><?=substr($master->document_name,31,strlen($master->document_name))?>
												</span></a>
												<?php
													if(strlen($master->document_name) > 31)
													{
														echo '<a class="tonggle_readmore" data-id=w'.$i.'>...</a>';
													}
													
												?>
												<input class="hidden" name="each_master_id" value="<?=$master->id?>">
												<input class="hidden" name="each_master_name" value="<?=$master->document_name?>">
											</td>
											<td style=""><?= $master->triggered_by?></td>
											<td><button type="button" class="btn btn-primary delete_master_document" onclick="delete_master_document(this)">Delete</button></td>
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
							<?php if ($reminder_module != 'none') { ?> 
							<div id="w2-reminder" class="tab-pane">
								<div style="height: 40px;"><a href="<?= base_url();?>documents/reminder" style="color: #FFBF00;width:170px;border-bottom:none; outline: none !important;text-decoration: none;font-weight:bold;position: absolute;right:15px;"><span id="reminder_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Click to Add Reminder" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Create Reminder</span></a></div>

								<table class="table table-bordered table-striped mb-none" id="datatable-reminder">
									<thead>
										<tr>
											<th style="text-align: center">No</th>
											<th style="text-align: center">Reminder Tag</th>
											<th style="text-align: center">Reminder Name</th>
											<th style="text-align: center">Active</th>
											<!-- <th style="text-align: center">Type</th>
											<th style="text-align: center">Set On</th>
											<th style="text-align: center">Start On</th> -->
											<th style="text-align: center"></th>
										</tr>
									</thead>
									<tbody id="reminder_body">
										<?php
											$i = 1;
											if($document_reminder)
											{
												foreach($document_reminder as $reminder)
												{
										?>
										<tr>
											<td><label><?=$i?></label></td>
											<td><?=$reminder->reminder_tag_name?></td>
											<td><a href="documents/edit_reminder/<?=$reminder->id?>" class="pointer mb-sm mt-sm"><?=ucwords(substr($reminder->reminder_name,0,31))?><span id="e<?=$i?>" style="display:none;"><?=substr($reminder->reminder_name,31,strlen($reminder->reminder_name))?>
												</span></a>
												<?php
													if(strlen($reminder->reminder_name) > 31)
													{
														echo '<a class="tonggle_readmore" data-id=e'.$i.'>...</a>';
													}
													
												?>
												<input class="hidden" name="each_reminder_id" value="<?=$reminder->id?>">
												<input class="hidden" name="each_reminder_name" value="<?=$reminder->reminder_name?>">
											</td>
											<?php
												if($reminder->active == 1)
												{
											?>
												<td>ON</td>
											<?php
												}else{
											?>
												<td>OFF</td>
											<?php
												}
											?>
											<!-- <td><a href="documents/edit_reminder/<?=$reminder->id?>" class="pointer mb-sm mt-sm"><?=ucwords(substr($reminder->type,0,31))?><span id="e<?=$i?>" style="display:none;"><?=substr($reminder->type,31,strlen($reminder->type))?>
												</span></a>
												<?php
													if(strlen($reminder->type) > 31)
													{
														echo '<a class="tonggle_readmore" data-id=e'.$i.'>...</a>';
													}
													
												?>
												<input class="hidden" name="each_reminder_id" value="<?=$reminder->id?>">
											</td>
											<td><?=$reminder->before_year_end?> days before year end<br/><?=$reminder->before_due_date?> days before due date</td>
											<td><?=$reminder->start_on?></td> -->
											<td><button type="button" class="btn btn-primary delete_reminder_document" onclick="delete_reminder_document(this)">Delete</button></td>
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
							<?php
								}
							?> 
						</div>

				</div>
				
					</div>
				</div>
			</div>
			<!-- <footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 number text-right" id="billing_footer_button">
						<button type="submit" class="btn btn-primary" name="save_template" id="save_template">Save</button>
						<a href="<?= base_url();?>masterclient/" class="btn btn-default">Close</a>
					</div>
				</div>
			</footer> -->
			</section>
		</div>
		<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>

<script>
	var template = <?php echo json_encode(isset($template)?$template:'');?>;
	var bool_open_receipt = <?php echo json_encode(isset($open_receipt)?$open_receipt:'');?>;
	var company_code = <?php echo json_encode(isset($company_code)?$company_code:'');?>;
	var access_right_document_module = <?php echo json_encode($document_module);?>;
	var access_right_pending_module = <?php echo json_encode($pending_module);?>;
	var access_right_all_module = <?php echo json_encode($all_module);?>;
	var access_right_master_module = <?php echo json_encode($master_module);?>;
	var access_right_reminder_module = <?php echo json_encode($reminder_module);?>;
	var document_check_state = <?php echo json_encode(isset($_POST['document_check_state'])?$_POST['document_check_state']:'') ?>;
	//console.log(document_check_state);

	$("#header_our_firm").removeClass("header_disabled");
	$("#header_manage_user").removeClass("header_disabled");
	$("#header_access_right").removeClass("header_disabled");
	$("#header_user_profile").removeClass("header_disabled");
	$("#header_setting").removeClass("header_disabled");
	$("#header_dashboard").removeClass("header_disabled");
	$("#header_client").removeClass("header_disabled");
	$("#header_person").removeClass("header_disabled");
	$("#header_document").addClass("header_disabled");
	$("#header_report").removeClass("header_disabled");
	$("#header_billings").removeClass("header_disabled");

	if(access_right_document_module == "read")
	{
		$('#pending_Add').hide();
		$('#all_Add').hide();
		$('#master_Add').hide();
		$('#reminder_Add').hide();
		$('button').attr("disabled", true);
		$('.add_pending_document_file').attr("disabled", true);
		$('.add_all_document_file').attr("disabled", true);
	}

	if(access_right_pending_module == "read")
	{
		$('#pending_Add').hide();
		$('#w2-pending button').attr("disabled", true);
		$('.add_pending_document_file').attr("disabled", true);
	}

	if(access_right_all_module == "read")
	{
		$('#all_Add').hide();
		$('#w2-all_document button').attr("disabled", true);
		$('.add_all_document_file').attr("disabled", true);
	}

	if(access_right_master_module == "read")
	{
		$('#master_Add').hide();
		$('#w2-master button').attr("disabled", true);
	}

	if(access_right_reminder_module == "read")
	{
		$('#reminder_Add').hide();
		$('#w2-reminder button').attr("disabled", true);
	}

	if(access_right_pending_module == "none")
	{
		$('li[data-information="all_document"]').addClass("active");
		$('#w2-all_document').addClass("active");
	}
	if(access_right_pending_module == "none" && access_right_all_module == "none")
	{
		$('li[data-information="master"]').addClass("active");
		$('#w2-master').addClass("active");
	}
	if(access_right_pending_module == "none" && access_right_all_module == "none" && access_right_master_module == "none")
	{
		$('li[data-information="reminder"]').addClass("active");
		$('#w2-reminder').addClass("active");
	}
</script> 
<script src="themes/default/assets/js/documents.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
<style>
	#buttonclick .datatables-header {
		display:none;
	}
</style>