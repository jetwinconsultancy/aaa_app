							<div class="header_between_all_section">
							<section class="panel">
								<header class="panel-heading">
									<div class="panel-actions">
										<a class="edit_client_transfer amber" href="<?= base_url();?>masterclient/transfer/<?=$company_code?>" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Initiate Shares Transfer For This Client" ><i class="fa fa-plus-circle  amber" style="font-size:16px;height:45px;"></i> Initiate Transfer</a>
																
										<!-- a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a-->
										<!--a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a-->
									</div>
									<h2></h2>
								</header>
								<div class="panel-body">
									<div class="col-md-12">
										<div class="row datatables-header form-inline">
											<div class="col-sm-12 col-md-12">
												<div class="dataTables_filter" id="datatable-default_filter">
												
														<?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
															echo form_open_multipart("masterclient/view_transfer/".$company_code."", $attrib);
															
														?>
														<select class="form-control" name="type">
															<option value="all" <?=$_POST['type']=='All'?'selected':'';?>>All</option>
															<option value="member_name" <?=$_POST['type']=='member_name'?'selected':'';?>>Member Name</option>
															<option value="class" <?=$_POST['type']=='class'?'selected':'';?>>Class</option>
															<option value="certificate_number" <?=$_POST['type']=='certificate_number'?'selected':'';?>>Certificate Number</option>
															
														</select>

														<input aria-controls="datatable-default" placeholder="Search" id="search"  name="search" value="<?=$_POST['search']?$_POST['search']:'';?>" class="form-control search_input_width" type="search">

														<div class="search_group_button" style="display: inline;">
															<button name="search_button" type="submit" class="btn btn-primary" tabindex="-1">Search</button>

															<a href="<?= base_url();?>masterclient/view_transfer/<?=$company_code?>" class="btn btn-primary">Show All Transfer</a>

															<a href="javascript: void(0);" class="btn btn-primary" id="transfer_back_button" style="float: right">Back to Members</a>
														</div>
														<?= form_close();?>
														<!-- <?= form_close();?> -->
												</div>
											</div>
										</div>
										
											<div id="buttonclick" style="display:block;padding-top:10px;">
												
												<table class="table table-bordered table-striped mb-none" id="datatable-default">
													<thead>
														<tr style="background-color:white;">
															<th class="text-center" style="vertical-align: middle;font-weight:bold; width:150px;">Transaction Date</th>
															<th class="text-center" style="vertical-align: middle;font-weight:bold;width:150px;">Class of Shares</th>
															<th class="text-center" style="vertical-align: middle;font-weight:bold;width:150px;">Member</th>
															<!-- <th class="text-center" style="vertical-align: middle;font-weight:bold;width:150px;">Class</th>
															<th class="text-center" style="font-weight:bold;">Currency</th> -->
															<th class="text-center" style="vertical-align: middle;font-weight:bold;width:150px;">Number of Shares Issued</th>
															<th class="text-center" style="vertical-align: middle;font-weight:bold; width:150px;">Amount of Share Issued</th>
															<th class="text-center" style="vertical-align: middle;font-weight:bold; width:150px;">Number of Shares Paid Up</th>
															<th class="text-center" style="vertical-align: middle;font-weight:bold; width:150px;">Amount of Shares Paid Up</th>
															<th class="text-center" style="vertical-align: middle;font-weight:bold; width:150px;">Certificate No</th>
															<th class="text-center" style="display: none;vertical-align: middle;font-weight:bold; width:150px;"></th>
														</tr>
														
													</thead>
													<tbody>
														<?php
															$i=1;
															$last_transaction_id = null;
															//PRINT_R($transfer);
															//$client = $this->db_model->getClient();
															foreach($transfer as $trans)
															{
																/*$alamat = ($c->streetname?" ".$c->streetname:'').($c->buildingname?" ".$c->buildingname:'').($c->unitno?" ".$c->unitno:'').($c->unitno1?" ".$c->unitno1:'').($c->city?" ".$c->city:'').($c->postal_code?" ".$c->postal_code:'');*/
																// echo $alamat."<br/>";
														?>
														<!-- <tr>
															<td style="text-align: center;">
																
																<a href="<?=site_url('masterclient/edit_transfer/'.$trans->transaction_id.'/'.$trans->client_member_share_capital_id.'/'.$trans->company_code);?>" data-toggle="tooltip" data-trigger="hover" data-original-title="Edit this Transfer">
																<?php 
																	$array = explode('/', $trans->transaction_date);
																	$tmp = $array[0];
																	$array[0] = $array[1];
																	$array[1] = $tmp;
																	unset($tmp);
																	$date_2 = implode('/', $array);
																	$time = strtotime($date_2);
																	$newformat = date('d F Y',$time);
																	echo $newformat;
																	//echo($date_2);
																?>
																</a>
															</td> -->

														
														<tr>
															<td style="text-align: center">
																<!-- <a href="<?=site_url('masterclient/edit_transfer/'.$trans->transaction_id.'/'.$trans->client_member_share_capital_id.'/'.$trans->company_code);?>" data-toggle="tooltip" data-trigger="hover" data-original-title="Edit this Transfer"> -->
																<span  class="hidden"><?php 
																	echo $trans->transaction_date; ?>
																</span>
																<?php 
																	//echo $allot->transaction_date;
																	$array = explode('/', $trans->transaction_date);
																	$tmp = $array[0];
																	$array[0] = $array[2];
																	$array[2] = $tmp;
																	$tmp1 = $array[0];
																	$array[0] = $array[1];
																	$array[1] = $tmp1;
																	unset($tmp);
																	$date_2 = implode('/', $array);
																	$time = strtotime($date_2);
																	$newformat = date('d F Y',$time);
																	echo $newformat;
																	//echo($date_2);
																?>
																<!-- </a> -->	
															</td>
															
															<td>
																<?php
																	if($trans->sharetype_name == "Ordinary Share" || $trans->sharetype_name == "Preferred Share")
																	{
																		echo $trans->sharetype_name;
																	}
																	elseif($trans->sharetype_name == "Others")
																	{
																		echo $trans->other_class;
																	}

																?>
																
															</td>
															<td>
																<?php
																	if($trans->name != null)
																	{
																		echo $trans->name;
																	}
																	elseif($trans->company_name != null)
																	{
																		echo $trans->company_name;
																	}
																	elseif($trans->client_company_name != null)
																	{
																		echo $trans->client_company_name;
																	}

																?>
																
															</td>
															<!-- <td><a href="<?=site_url('masterclient/edit_allotment/'.$allot->client_member_share_capital_id.'/'.$allot->company_code);?>"><span class="pointer amber"><?=$allot->sharetype?></a></td> -->
															<td style="text-align: right;"><?=number_format($trans->number_of_share)?></td>
															<td style="text-align: right;"><?=number_format($trans->amount_share, 2)?></td>
															<td style="text-align: right;"><?=number_format($trans->no_of_share_paid)?></td>
															<td style="text-align: right;"><?=number_format($trans->amount_paid, 2)?></td>
															<td style="text-align: center;">
																<?=$trans->certificate_no?>
																<input type="hidden" name="cert_no" value="<?=$trans->certificate_no?>"/>

																<input type="hidden" name="transaction_id" value="<?=$trans->transaction_id?>"/>
																
																<input type="hidden" name="client_member_share_capital_id" value="<?=$trans->client_member_share_capital_id	?>"/>
																<input type="hidden" name="company_code" value="<?=$trans->company_code	?>"/>
															</td>
															<td style="text-align: center;display: none">
																<button type="button" class="btn btn-danger delete_transfer" onclick="delete_transfer(this)">Delete</button>
															</td>
															<!-- <td><a class="" href="<?=site_url('masterclient/edit/'.$c->id);?>" data-name="<?=$c->company_name?>" style="cursor:pointer"><?=ucwords(substr($c->company_name,0,20))?><span id="f<?=$i?>" style="display:none;cursor:pointer"><?=ucwords(substr($c->company_name,20,strlen($c->company_name)))?></span></a><a class="tonggle_readmore" data-id="f<?=$i?>">...</a></td>
															<td><?=ucwords(substr($alamat,0,6))?><span id="g<?=$i?>" style="display:none"><?=ucwords(substr($alamat,6,strlen($alamat)))?></span><a class="tonggle_readmore" data-id="g<?=$i?>">...</a></td>
															<td><?=$c->formername?></td>
															<td class="text-left"><?=$c->phone?></td>
															<td class="text-left"><?=$c->email?></td>
															
															<td class="text-right" ><a class="" href="<?= base_url();?>masterclient/unpaid_invoice" style="cursor:pointer">$<?=number_format($c->unpaid_invoice,2)?></a></td>
															<td class="text-right" ><a class="" href="<?= base_url();?>masterclient/unreceived_doc" style="cursor:pointer"><?=number_format($c->unreceived_doc,0)?> Doc</a></td>
															<td><a class="" href="<?= base_url();?>masterclient/setting_filing" style="cursor:pointer"><?=$this->sma->remove_time($c->filing_date)?></a></td></td> -->
															<!-- <td width="10px;"><a class="" href="<?= base_url();?>masterclient/delete/<?=$c->id?>" style="cursor:pointer"><span  class="fa fa-trash"></span></a></td> -->
														</tr>
														<?php
																$i++;
																$last_transaction_id = $trans->transaction_id;
															}
														?>
													</tbody>
												</table>
											</div>
											<style>
												.tonggle_readmore {
													font-size:30px;
													line-height:12px;
												}
											</style>
											<script>
												$(document).on('click','.tonggle_readmore',function (){
													$id = $(this).data('id');
													$("#"+$id).toggle();
												});
											</script>
								</div>
								
					</div>
							<!-- end: page -->
							</section>
							</div>
						</div>

					</section>
					<script>
						//console.log(<?php echo json_encode($transfer);?>);
						var access_right_member_module = <?php echo json_encode($member_module);?>;
						var client_status = <?php echo json_encode($client_status);?>;
						var cert_info;
						if(access_right_member_module == "read" || client_status != "1")
						{
							$(".edit_client_transfer").hide();
						}

						$("#transfer_back_button").on("click", function() {
							//console.log("inin");
							window.close();
						});

						toastr.options = {
						  "positionClass": "toast-bottom-right"
						}

						function delete_transfer(element){
							var tr = jQuery(element).parent().parent();
							var certificate_no = tr.find('input[name="cert_no"]').val();
							var client_member_share_capital_id = tr.find('input[name="client_member_share_capital_id"]').val();
							var company_code = tr.find('input[name="company_code"]').val();
							var transaction_id = tr.find('input[name="transaction_id"]').val();
							//var certificate_no = tr.find('input[name="certificate_no"]').val();

							//console.log(tr);
							//console.log(certificate_no);
							if(certificate_no != undefined)
							{
								$('#loadingmessage').show();
								$.ajax({ //Upload common input
							        url: "masterclient/delete_transfer_follow_by_cert",
							        type: "POST",
							        data: {"transaction_id": transaction_id, "client_member_share_capital_id": client_member_share_capital_id},
							        dataType: 'json',
							        async: false,
							        success: function (response) {
							        	//console.log(response);
							        	$('#loadingmessage').hide();

							        	if(response.status == 1)
							        	{
							        		//console.log(response.query_certificate_merge);
							        		cert_info = response.query_certificate_merge;
							        		bootbox.alert("This action will result in negative balance of number of share for <a href=javascript: void(0);' target='_blank' class='click_some_member'>some members</a>.", function (result) {
							        				
									            
									        });
							        	}
							        	else if(response.status == 2)
							        	{
							        		toastr.success(response.message, response.title);
							        		location.reload(); 
							        	}

							        	//toastr.success(response.message, response.title);
							        	//location.reload();

							        	


							  
							        }
							    });
							}
							//tr.remove();

							
							//check_due_date_175();
							//$("#year_end_date").prop('disabled', false);
							//$(".change_year_end_button").hide();
						}

						$(document).on('click', '.click_some_member', function (event) {
						    bootbox.hideAll();
						    $.post('masterclient/check_transfer_share', { "cert_info": cert_info }, function(){
							    window.open('masterclient/open_transfer_share');
							});
						    /*$.ajax({ //Upload common input
						        url: "masterclient/check_transfer_share",
						        type: "POST",
						        data: {"cert_info": response.query_certificate_merge},
						        dataType: 'json',
						        success: function (response) {

						        	window.open('masterclient/open_transfer_share');
						        }
						    });*/
						});
						
					</script>
					<style>
	#buttonclick .datatables-header {
		display:none;
	}
</style>