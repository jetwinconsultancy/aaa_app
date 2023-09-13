						<div class="header_between_all_section">
							<section class="panel">
								<header class="panel-heading">
									<div class="panel-actions">
										<a class="edit_client_allotment amber" href="<?= base_url();?>masterclient/allotment/<?=$company_code?>" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Allot New Shares" ><i class="fa fa-plus-circle  amber" style="font-size:16px;height:45px;"></i> Allot New Shares</a>
																
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
															echo form_open_multipart("masterclient/view_allotment/".$company_code."", $attrib);
															
														?>

														<select class="form-control" name="type">
															<option value="all" <?=$_POST['type']=='All'?'selected':'';?>>All</option>
															<option value="member_name" <?=$_POST['type']=='member_name'?'selected':'';?>>Member Name</option>
															<option value="class" <?=$_POST['type']=='class'?'selected':'';?>>Class</option>
															<option value="certificate_number" <?=$_POST['type']=='certificate_number'?'selected':'';?>>Certificate Number</option>
															<!-- <option value="formername" <?=$_POST['tipe']=='formername'?'selected':'';?>>Former Name</option>
															<option value="uen" <?=$_POST['tipe']=='uen'?'selected':'';?>>UEN</option> -->
														</select>

														<input aria-controls="datatable-default" placeholder="Search" id="search"  name="search" value="<?=$_POST['search']?$_POST['search']:'';?>" class="form-control search_input_width" type="search">
														
														<div class="search_group_button" style="display: inline;">

															<button name="search_button" type="submit" class="btn btn-primary" tabindex="-1">Search</button>

															<a href="<?= base_url();?>masterclient/view_allotment/<?=$company_code?>" class="btn btn-primary">Show All Allotment</a>

															<a href="javascript: void(0);" class="btn btn-primary" id="allot_back_button" style="float: right">Back to Members</a>
														</div>
														<!-- <button name="showall" type="submit" name="show_all_allotment" class="btn btn-primary" tabindex="-1">Show All Allotment</button> -->
														<?= form_close();?>
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
															<th class="text-center" style="vertical-align: middle;font-weight:bold; width:150px;"></th>
															<!-- <th class="text-center" style="vertical-align: middle;font-weight:bold; width:150px;">Certificate No</th> -->
														</tr>
														
													</thead>
													<tbody>
														<?php
															$i=1;
															$last_transaction_id = null;
															//PRINT_R($allotment);
															//$client = $this->db_model->getClient();
															foreach($allotment as $allot)
															{
																/*$alamat = ($c->streetname?" ".$c->streetname:'').($c->buildingname?" ".$c->buildingname:'').($c->unitno?" ".$c->unitno:'').($c->unitno1?" ".$c->unitno1:'').($c->city?" ".$c->city:'').($c->postal_code?" ".$c->postal_code:'');*/
																// echo $alamat."<br/>";
														?>
														
															
														<tr>
															<td style="text-align: center">
																<a href="<?=site_url('masterclient/edit_allotment/'.$allot->transaction_id.'/'.$allot->client_member_share_capital_id.'/'.$allot->company_code);?>" data-toggle="tooltip" data-trigger="hover" data-original-title="Edit this allotment">
																<span  class="hidden"><?php 
																	echo $allot->transaction_date; ?>
																</span>
																<?php 
																	//echo $allot->transaction_date;
																	$array = explode('/', $allot->transaction_date);
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
																</a>	
															</td>
															
															<td>
																<?php
																	if($allot->sharetype_name == "Ordinary Share")
																	{
																		echo $allot->sharetype_name;
																	}
																	elseif($allot->sharetype_name == "Others")
																	{
																		echo $allot->other_class;
																	}

																?>
																
															</td>
															<td>
																<?php
																	if($allot->name != null)
																	{
																		echo $allot->name;
																	}
																	elseif($allot->company_name != null)
																	{
																		echo $allot->company_name;
																	}
																	elseif($allot->client_company_name != null)
																	{
																		echo $allot->client_company_name;
																	}

																?>
																
															</td>
															<!-- <td><a href="<?=site_url('masterclient/edit_allotment/'.$allot->client_member_share_capital_id.'/'.$allot->company_code);?>"><span class="pointer amber"><?=$allot->sharetype?></a></td> -->
															<td style="text-align: right"><?=number_format($allot->number_of_share)?></td>
															<td style="text-align: right"><?=number_format($allot->amount_share,2)?></td>
															<td style="text-align: right"><?=number_format($allot->no_of_share_paid)?></td>
															<td style="text-align: right"><?=number_format($allot->amount_paid,2)?></td>
															<td style="text-align: center"><?=$allot->new_certificate_no?></td>
															<td style="text-align: center">
																
																<button type="button" class="btn btn-primary delete_allotment" onclick="delete_allotment(this)">Delete</button>
																<input type="hidden" name="cert_no" value="<?=$allot->certificate_no?>"/>
																<input type="hidden" name="client_member_share_capital_id" value="<?=$allot->client_member_share_capital_id	?>"/>
																<input type="hidden" name="company_code" value="<?=$allot->company_code	?>"/>
															
																
															</td>
															<!-- <td style="text-align: center"><?=$allot->certificate_no?></td> -->




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
																$last_transaction_id = $allot->transaction_id;
																//echo $last_transaction_id;
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
					<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>
					<script>
						//console.log(<?php echo json_encode($allotment);?>);
						var access_right_member_module = <?php echo json_encode($member_module);?>;
						var client_status = <?php echo json_encode($client_status);?>;
						if(access_right_member_module == "read" || client_status != "1")
						{
							$(".edit_client_allotment").hide();
						}

						$("#allot_back_button").on("click", function() {
							//console.log("inin");
							window.close();
						});

						toastr.options = {
						  "positionClass": "toast-bottom-right"
						}

						function delete_allotment(element){
							var tr = jQuery(element).parent().parent();
							var certificate_no = tr.find('input[name="cert_no"]').val();
							var client_member_share_capital_id = tr.find('input[name="client_member_share_capital_id"]').val();
							var company_code = tr.find('input[name="company_code"]').val();
							//var certificate_no = tr.find('input[name="certificate_no"]').val();

							//console.log(tr);
							//console.log(certificate_no);
							if(certificate_no != undefined)
							{
								$('#loadingmessage').show();
								$.ajax({ //Upload common input
							        url: "masterclient/delete_allot_follow_by_cert",
							        type: "POST",
							        data: {"certificate_no": certificate_no, "client_member_share_capital_id": client_member_share_capital_id},
							        dataType: 'json',
							        success: function (response) {
							        	//console.log(response);
							        	$('#loadingmessage').hide();

							        	if(response.status == 1)
							        	{
							        		bootbox.alert("This action will result in negative balance of number of share for <a href='masterclient/check_share/"+company_code+"/"+response.client_member_share_capital_id+"/"+response.officer_id+"/"+response.field_type+"/"+certificate_no+"/Allotment"+"' target='_blank' class='click_some_member'>some members</a>.", function (result) {
							        				//bootbox.hideAll();
									            /*if (!result) {
									            	$('#wBuyback').bootstrapWizard('show', 1);
									            }*/
									            
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
						});
					</script>
					<style>
	#buttonclick .datatables-header {
		display:none;
	}
</style>