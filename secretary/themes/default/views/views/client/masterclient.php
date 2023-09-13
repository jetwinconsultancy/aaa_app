						<div class="header_between_all_section">
							<section class="panel">
								<header class="panel-heading">
									<?php if((!$Individual && $Individual == true) || (!$Individual && $Individual == null && !$Client)) {?>
										<div class="panel-actions">
											<a class="create_client amber" href="#" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Create Client" ><i class="fa fa-plus-circle amber" style="font-size:16px;height:45px;"></i> Create Client</a>
																	
											<!-- a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a-->
											<!--a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a-->
										</div>
										<h2></h2>
									<?php } ?>
								</header>
								<div class="panel-body">
									<div class="col-md-12">
										<div class="row datatables-header form-inline">
											<div class="col-sm-12 col-md-12">
												<div class="dataTables_filter" id="datatable-default_filter">
												
														<!-- <select id="service_category" class="form-control service_category" style="width:200px;" name="service_category">
															<option value="0" <?=isset($_POST['service_category'])?($_POST['service_category']=='0'?'selected':''):''?>>All</option>
										                	<option value="1" <?=isset($_POST['service_category'])?($_POST['service_category']=='1'?'selected':''):''?>>Statutory Audit</option>
										                	<option value="2" <?=isset($_POST['service_category'])?($_POST['service_category']=='2'?'selected':''):''?>>Accounting</option>
										                	<option value="3" <?=isset($_POST['service_category'])?($_POST['service_category']=='3'?'selected':''):''?>>Tax</option>
										                	<option value="4" <?=isset($_POST['service_category'])?($_POST['service_category']=='4'?'selected':''):''?>>Secretary</option>
										                	<option value="5" <?=isset($_POST['service_category'])?($_POST['service_category']=='5'?'selected':''):''?>>Administrative</option>
										                	<option value="6" <?=isset($_POST['service_category'])?($_POST['service_category']=='6'?'selected':''):''?>>Human Resource</option>
										                	<option value="7" <?=isset($_POST['service_category'])?($_POST['service_category']=='7'?'selected':''):''?>>Registered Office Address</option>
										                	<option value="8" <?=isset($_POST['service_category'])?($_POST['service_category']=='8'?'selected':''):''?>>Others</option>
										                	<option value="9" <?=isset($_POST['service_category'])?($_POST['service_category']=='9'?'selected':''):''?>>I.T.</option>
										                	<option value="10" <?=isset($_POST['service_category'])?($_POST['service_category']=='10'?'selected':''):''?>>Other Assurance</option>
										                </select>
														<select class="form-control" name="tipe" style="display: none;">
															<option value="All" <?=$_POST['tipe']=='All'?'selected':'';?>>All</option>
															<option value="company_name" <?=isset($_POST['tipe'])?($_POST['tipe']=='company_name'?'selected':''):''?>>Company Name</option>
															<option value="former_name" <?=isset($_POST['tipe'])?($_POST['tipe']=='former_name'?'selected':''):''?>>Former Name</option>
															<option value="registration_no" <?=isset($_POST['tipe'])?($_POST['tipe']=='registration_no'?'selected':''):''?>>Registration No</option>
														</select>

														<input class="form-control search_input_width" aria-controls="datatable-default" placeholder="Search" id="search"  name="search" value="<?=isset($_POST['search'])?$_POST['search']:'';?>" type="search"> -->
														<div class="search_group_button" style="display: inline;">
															<!-- <input type="button" class="btn btn-primary search_client" value="Search"/>
															<a href="masterclient" class="btn btn-primary">Show All Clients</a> -->
															<?php if($qb_company_id != "" && $qb_company_id != null) {?> 
																<input type="button" class="btn btn-primary import_cl_to_quickbook" onclick="import_client_to_quickbook()" name="import_cl_to_quickbook" value="Import" data-toggle="tooltip" data-trigger="hover" data-original-title="Import to Quickbook Online">
															<?php } ?>  
														</div>
												</div>
											</div>
										</div>
											<!-- id="buttonclick" -->
											<div style="display:block;padding-top:10px;table-layout: fixed;width:100%">
												
												<table class="table table-bordered table-striped mb-none" id="datatable-client" style="width:100%">
													<thead>
														<?php if((!$Individual && $Individual == true) || (!$Individual && $Individual == null && !$Client)) {?>
															<tr style="background-color:white;">
																<th rowspan="2" class="text-center">Registration No</th>
																<th rowspan="2" class="text-center">Company</th>
																<!-- <th rowspan="2" style="vertical-align: middle;font-weight:bold;width:200px;">Address</th> -->
																
																<th colspan="3" class="text-center">Contact</th>
																<th rowspan="2" class="text-center">Unpaid Invoice</th>
																
																<th rowspan="2" class="text-center">Unreceived Document</th>
																<th rowspan="2" class="text-center">Filing Deadline</th>
																<th rowspan="2" class="text-center">QuickBooks</th>
																<th rowspan="2"></th>
																<!-- <th rowspan="2" style="vertical-align: middle;font-weight:bold; width:50px;">Delete</th> -->
															</tr>
															<tr style="background-color:white;">
																<th style="text-align: center">Name</th>
																<th class="text-center">Phone</th>
																<th class="text-center">E-Mail</th>
															</tr>
														<?php } ?>
														<?php if($Individual) {?>
															<tr style="background-color:white;">
																<th class="text-center">Registration No</th>
																<th class="text-center">Company</th>
																<th class="text-center">Unreceived Document</th>
																<th class="text-center">Filing Deadline</th>
																<th ></th>

															</tr>
															
														<?php } ?>
														<?php if($Client) {?>
															<tr style="background-color:white;">
																<th class="text-center">Registration No</th>
																<th class="text-center">Company</th>
																<th class="text-center">Unpaid Invoice</th>
																<th class="text-center">Unreceived Document</th>
																<th class="text-center">Filing Deadline</th>
															</tr>
														<?php } ?>
													</thead>
													<tbody>
														<?php
															$i=1;
															 //PRINT_R($Admin);
															//echo json_encode(!$Admin);
															//$client = $this->db_model->getClient();
															foreach($client as $key=>$c )
															{
																//echo json_encode($c["registration_no"]);
																$alamat = ($c["street_name"]?$c["street_name"]:'').($c["unit_no1"]?" #".$c["unit_no1"]:'').($c["unit_no2"]?"-".$c["unit_no2"]:'').($c["building_name"]?" ".$c["building_name"]:'').($c["postal_code"]?" Singapore ".$c["postal_code"]:'');

																/*$alamat = $c->street_name?." #".$c->unit_no1."-".$c->unit_no2." ".$c->building_name." Singapore ".$c->postal_code;*/
																// echo $alamat."<br/>";
														?>
														<tr>
															<td>
																<div style="width: 100px; word-break:break-all;" >
																	<?=ucwords(substr($c["registration_no"],0,11))?><span id="p<?=$i?>" style="display:none;"><?=substr($c["registration_no"],11,strlen($c["registration_no"]))?>
																	</span>
																	<?php
																		if(strlen($c['registration_no']) > 11)
																		{
																			echo '<a class="tonggle_readmore" data-id=p'.$i.'>...</a>';
																		}
																		
																	?>
																</div>
																<!-- <?=$c["registration_no"]?> -->
															</td>
															<td>
																<div style="width: 200px; word-break:break-all;" >
																	<a class="" href="<?=site_url('masterclient/edit/'.$c["id"]);?>" data-name="<?=$c["company_name"]?>" style="cursor:pointer" data-toggle="tooltip" data-trigger="hover" data-original-title="Edit This Client"><?=ucwords(substr($c["company_name"],0,24))?><span id="f<?=$i?>" style="display:none;cursor:pointer"><?=substr($c["company_name"],24,strlen($c["company_name"]))?></span></a>
																	<?php
																		if(strlen($c['company_name']) > 24)
																		{
																			echo '<a class="tonggle_readmore" data-id=f'.$i.'>...</a>';
																		}
																		
																	?>
																</div>
															</td>
															<!-- <td><?=ucwords(substr($alamat,0,6))?><span id="g<?=$i?>" style="display:none"><?=substr($alamat,6,strlen($alamat))?></span><a class="tonggle_readmore" data-id="g<?=$i?>">...</a>
															</td> -->
															<?php if((!$Individual && $Individual == true) || (!$Individual && $Individual == null && !$Client)) {?>
															<td>
																<div style="width: 110px; word-break:break-all;" >
																	
																	<?php if($setup_module != null)
																		{
																			 if($setup_module != "full" && !$Admin){  ?>
																		<?=ucwords(substr($c["name"],0,12))?><span id="e<?=$i?>" style="display:none;"><?=substr($c["name"],12,strlen($c["name"]))?>
																		</span>
																	<?php } else { ?>
																		<a class="" href="<?=site_url('masterclient/edit/'.$c["id"]."/setup");?>" style="cursor:pointer" data-toggle="tooltip" data-trigger="hover" data-original-title="Edit This Contact"><?=ucwords(substr($c["name"],0,12))?><span id="e<?=$i?>" style="display:none;"><?=substr($c["name"],12,strlen($c["name"]))?>
																		</span></a>
																	
																	<?php } } else { ?>
																		<a class="" href="<?=site_url('masterclient/edit/'.$c["id"]."/setup");?>" style="cursor:pointer" data-toggle="tooltip" data-trigger="hover" data-original-title="Edit This Contact"><?=ucwords(substr($c["name"],0,12))?><span id="e<?=$i?>" style="display:none;"><?=substr($c["name"],12,strlen($c["name"]))?>
																		</span></a>
																	<?php } ?>

																	
																	<?php
																		if(strlen($c['name']) > 12)
																		{
																			echo '<a class="tonggle_readmore" data-id=e'.$i.'>...</a>';
																		}
																		
																	?>
																</div>
																<!-- <?=$c["name"]?> -->
																	
															</td>
														
															<td class="text-left">
																<div style="width: 110px; word-break:break-all;" >
																	<?php if($setup_module != null)
																		{ 
																			if($setup_module != "full" && !$Admin){  ?>
																		<?=ucwords(substr($c["phone"],0,12))?><span id="k<?=$i?>" style="display:none;"><?=substr($c["phone"],12,strlen($c["phone"]))?></span>
																	<?php } else { ?>
																		<a class="" href="<?=site_url('masterclient/edit/'.$c["id"]."/setup");?>" style="cursor:pointer" data-toggle="tooltip" data-trigger="hover" data-original-title="Edit This Contact"><?=ucwords(substr($c["phone"],0,12))?><span id="k<?=$i?>" style="display:none;"><?=substr($c["phone"],12,strlen($c["phone"]))?>
																		</span></a>
																	
																	<?php } } else { ?>
																		<a class="" href="<?=site_url('masterclient/edit/'.$c["id"]."/setup");?>" style="cursor:pointer" data-toggle="tooltip" data-trigger="hover" data-original-title="Edit This Contact"><?=ucwords(substr($c["phone"],0,12))?><span id="k<?=$i?>" style="display:none;"><?=substr($c["phone"],12,strlen($c["phone"]))?>
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
																<div style="width: 110px; word-break:break-all;" >

																	<?php if($setup_module != null)
																		{
																			if($setup_module != "full" && !$Admin){  ?>
																		<?=(substr($c["email"],0,12))?><span id="n<?=$i?>" style="display:none;"><?=substr($c["email"],12,strlen($c["email"]))?></span>
																	<?php } else { ?>
																		<a class="" href="<?=site_url('masterclient/edit/'.$c["id"]."/setup");?>" style="cursor:pointer" data-toggle="tooltip" data-trigger="hover" data-original-title="Edit This Contact"><?=(substr($c["email"],0,12))?><span id="n<?=$i?>" style="display:none;"><?=substr($c["email"],12,strlen($c["email"]))?>
																		</span></a>
																	
																	<?php } } else { ?>
																		<a class="" href="<?=site_url('masterclient/edit/'.$c["id"]."/setup");?>" style="cursor:pointer" data-toggle="tooltip" data-trigger="hover" data-original-title="Edit This Contact"><?=(substr($c["email"],0,12))?><span id="n<?=$i?>" style="display:none;"><?=substr($c["email"],12,strlen($c["email"]))?>
																		</span></a>
																	<?php } ?>
																	<?php
																		if(strlen($c['email']) > 12)
																		{
																			echo '<a class="tonggle_readmore" data-id=n'.$i.'>...</a>';
																		}
																		
																	?>
																</div>
															</td>
															<?php } ?>
															<?php if((!$Individual && $Individual == true) || (!$Individual && $Individual == null && $Client) || (!$Individual && $Individual == null && !$Client && $Client == null)) {?>
															<td class="text-right" >
																<div style="word-break:break-all;" >
																	<?php 
																		if($billing_module != null)
																		{
																			if($billing_module != "full" && !$Admin){  ?>

																				$<?=number_format($c["outstanding"],2)?>
																	<?php } else { ?>
																		<a class="" href="<?=site_url('billings/index/'.$c["company_code"]);?>" style="cursor:pointer" data-toggle="tooltip" data-trigger="hover" data-original-title="Update Payment of This Balance">$<?=number_format($c["outstanding"],2)?></a>

																	<?php } } elseif($unpaid_module != "full" && !$Admin){  ?>

																		$<?=number_format($c["outstanding"],2)?>
																	

																	<?php } else { ?>
																		<a class="" href="<?=site_url('billings/index/'.$c["company_code"]);?>" style="cursor:pointer" data-toggle="tooltip" data-trigger="hover" data-original-title="Update Payment of This Balance">$<?=number_format($c["outstanding"],2)?></a>
																	<?php } ?>
																	
																</div>
															</td>
															<?php } ?>
															<?php if($Individual || $Client) { ?>
																<td class="text-right" ><div style="word-break:break-all;" ><?=number_format($c["num_document"],0)?> Doc</div></td>
															<?php } else { ?>
																<td class="text-right" ><div style="word-break:break-all;" ><a class="" href="<?= base_url();?>documents" style="cursor:pointer" data-toggle="tooltip" data-trigger="hover" data-original-title="Update Documents Received"><?=number_format($c["num_document"],0)?> Doc</a></div></td>
															<?php } ?>
															<td style="text-align: center">
																<?php
																	if(isset($c["175_extended_to"]))
																	{
																		if($c["175_extended_to"] != 0)
																		{
																			$due_date_175 = $c["175_extended_to"];
																		}
																		else
																		{
																			if(isset($c["due_date_175"]))
																			{
																				$due_date_175 = $c["due_date_175"];
																			}
																			else
																			{
																				$due_date_175 = '';
																			}
																		}
																	}
																	else
																	{
																		if(isset($c["due_date_175"]))
																		{
																			$due_date_175 = $c["due_date_175"];
																		}
																		else
																		{
																			$due_date_175 = '';
																		}
																	}

																	if(isset($c["201_extended_to"]))
																	{
																		if($c["201_extended_to"] != 0)
																		{
																			$due_date_201 = $c["201_extended_to"];
																		}
																		else
																		{
																			if(isset($c["due_date_201"]))
																			{
																				$due_date_201 = $c["due_date_201"];
																			}
																			else
																			{
																				$due_date_201 = '';
																			}
																		}
																	}
																	else
																	{
																		if(isset($c["due_date_201"]))
																		{
																			$due_date_201 = $c["due_date_201"];
																		}
																		else
																		{
																			$due_date_201 = '';
																		}
																	}

																	// if($c["201_extended_to"] != 0)
																	// {
																	// 	$due_date_201 = $c["201_extended_to"];
																	// }
																	// else
																	// {
																	// 	$due_date_201 = $c["due_date_201"];
																	// }
																	if( strtotime($due_date_175) > strtotime($due_date_201) || strtotime($due_date_175) == strtotime($due_date_201))
																	{
																		if($filing_module != null)
																		{
																			if($filing_module != "full" && !$Admin)
																			{
																				echo ''.$due_date_201.'';
																			}
																			else
																			{
																				echo '<a class="" href="'.site_url("masterclient/edit/".$c["id"]."/filing").'" style="cursor:pointer" data-toggle="tooltip" data-trigger="hover" data-original-title="Update Filing Information">'.$due_date_201.'</a>';
																			}
																		}
																		else if($Individual || $Client)
																		{
																			echo ''.$due_date_201.'';
																		}
																		else
																		{
																			echo '<a class="" href="'.site_url("masterclient/edit/".$c["id"]."/filing").'" style="cursor:pointer" data-toggle="tooltip" data-trigger="hover" data-original-title="Update Filing Information">'.$due_date_201.'</a>';
																		}
																		
																	}
																	elseif( strtotime($due_date_201) > strtotime($due_date_175))
																	{
																		if($filing_module != null)
																		{
																			if($filing_module != "full" && !$Admin)
																			{
																				echo ''.$due_date_201.'';
																			}
																			else
																			{
																				echo '<a class="" href="'.site_url("masterclient/edit/".$c["id"]."/filing").'" style="cursor:pointer" data-toggle="tooltip" data-trigger="hover" data-original-title="Update Filing Information">'.$due_date_201.'</a>';
																			}
																		}
																		else if($Individual || $Client)
																		{
																			echo ''.$due_date_175.'';
																		}
																		else
																		{
																			echo '<a class="" href="'.site_url("masterclient/edit/".$c["id"]."/filing").'" style="cursor:pointer" data-toggle="tooltip" data-trigger="hover" data-original-title="Update Filing Information">'.$due_date_201.'</a>';
																		}
																	}
																?>
															</td>
														</td>
														<?php if((!$Individual && $Individual == true) || (!$Individual && $Individual == null && !$Client)) {
															if(count($c["concat_currency_name"]) > 0 && $c["concat_currency_name"] != "")
															{

																$client_qb_status = implode(", ", $c["concat_currency_name"]);
															}
															else
															{
																$client_qb_status = "Not";
															}
															echo '<td>'.$client_qb_status.'</td>';
														?>
															<td>
																<input type="text" class="form-control hidden" name="each_client_id" id="each_client_id" value="<?=$c["id"]?>"/>
																<button type="button" class="btn btn-primary delete_client" onclick="delete_client(this)">Delete</button>
															</td>
														<?php } ?>
														</tr>
														<?php
																$i++;
															}
														?>
													</tbody>
												</table>
											</div>
											<div id="modal_import_client_to_qb" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;">
												<div class="modal-dialog">
													<div class="modal-content">
														<header class="panel-heading">
															<h2 class="panel-title">Import to Quickbook Online</h2>
														</header>
														<form id="form_import_client_to_qb">
															<div class="panel-body">
																<div style="height: 300px;">
																	<h5>Do you want to push this selected info to Quickbook Online? (One time only can import 20 client)</h5><ul><li>Company Name must not contain colon (:), tab, or newline characters.</li><li>Contact Email must contain an at sign (@) and dot (.).</li><li>The email address format must follow the RFC 822 standard.</li></ul>
																	<h5>Please choose one currency before import this data to Quickbook Online.</h5>
																	<div class="div_qb_currencySelector"><select id="client_qb_currency" class="form-control client_qb_currency" style="width: 100%;" name="client_qb_currency"><option value="0">Select Currency</option></select></div>
																	<h5 class="help-block">* All the client will be under the currency you choose.</h5>
																</div>
															</div>
														</form>
														<div class="modal-footer">
															<button type="button" class="btn btn-primary" name="saveImportClientToQB" id="saveImportClientToQB">Import</button>
															<input type="button" class="btn btn-default" data-dismiss="modal" name="cancelImportClientToQB" value="Cancel">
														</div>
													</div>
												</div>
											</div>
											<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>
											<script>

												$("#header_our_firm").removeClass("header_disabled");
												$("#header_manage_user").removeClass("header_disabled");
												$("#header_access_right").removeClass("header_disabled");
												$("#header_user_profile").removeClass("header_disabled");
												$("#header_setting").removeClass("header_disabled");
												$("#header_dashboard").removeClass("header_disabled");
												$("#header_client").addClass("header_disabled");
												$("#header_person").removeClass("header_disabled");
												$("#header_document").removeClass("header_disabled");
												$("#header_report").removeClass("header_disabled");
												$("#header_billings").removeClass("header_disabled");
												
												var no_of_client = <?php echo json_encode($no_of_client) ?>;
											    var total_no_of_client = <?php echo json_encode($total_no_of_client) ?>;
											    var currency = <?php echo json_encode($currency) ?>;
											    var qb_company_id = <?php echo json_encode($qb_company_id) ?>;
											    var Admin = <?php echo json_encode($Admin) ?>;

											    $(document).ready(function () {
											        $('.create_client').click(function (e) {
											            e.preventDefault();

											            if(no_of_client == total_no_of_client)
											            {
											                bootbox.alert("Cannot exceed the total number of clients.", function() {
											                });
											            }
											            else
											            {
											                window.location.href = '<?= base_url();?>masterclient/add';
											            }
											            
											        });
											    });
												toastr.options = {
												  "positionClass": "toast-bottom-right"
												}
												$(document).on('click','.tonggle_readmore',function (){
													$id = $(this).data('id');
													$("#"+$id).toggle();
												});

												var access_right_client_module = <?php echo json_encode($client_module);?>;
												var access_right_company_info_module = <?php echo json_encode($company_info_module);?>;
												var access_right_officer_module = <?php echo json_encode($officer_module);?>;
												var access_right_member_module = <?php echo json_encode($member_module);?>;
												var access_right_controller_module = <?php echo json_encode($controller_module);?>;
												var access_right_charge_module = <?php echo json_encode($charges_module);?>;
												var access_right_filing_module = <?php echo json_encode($filing_module);?>;
												var access_right_register_module = <?php echo json_encode($register_module);?>;
												var access_right_setup_module = <?php echo json_encode($setup_module);?>;
												var access_right_person_module = <?php echo json_encode($person_module);?>;

												if(access_right_client_module != null && access_right_person_module != null)
												{
													if(access_right_client_module != "full" && access_right_person_module != "full" && !Admin)
														{
															$(".create_client").hide();
														}
												}
												else if(access_right_client_module != null)
												{
													if(access_right_client_module != "full" && !Admin)
														{
															$(".create_client").hide();
														}
												}
												else if(access_right_company_info_module != "full" && access_right_officer_module != "full" && access_right_member_module != "full" && access_right_controller_module != "full" && access_right_charge_module != "full" && access_right_filing_module != "full" && access_right_register_module != "full" && access_right_setup_module != "full" && access_right_person_module != "full" && !Admin)
												{
													$(".create_client").hide();
													
												}
												else
												{
													$(".create_client").show();
												}

												// $(".search_client").click(function(){
												// 	$('#datatable-client').DataTable().destroy();
												// 	$('#datatable-client tbody').empty();
												// 	$('#datatable-client').DataTable({
												// 		"processing": true,
												// 		//"serverSide": true,
												// 		//"order": [[0, "desc" ]],
												// 		"ajax":{
												// 		      	url :  'masterclient/showClientDO',
												// 		      	type : 'POST',
												// 		      	data: {"service_category": $(".service_category").val(), "user_search": $("#search").val()},
												// 				dataType: 'json',
												// 		    },
												// 	});
											 //    }); 

												function delete_client(element){
													var tr = jQuery(element).parent().parent();
													var each_client_id = tr.find('input[name="each_client_id"]').val();

													bootbox.confirm("Are you confirm delete this client?", function (result) {
											            if (result) 
											            {
															if(each_client_id != undefined)
															{
																$('#loadingmessage').show();
																$.ajax({ //Upload common input
															        url: "masterclient/delete_client",
															        type: "POST",
															        data: {"client_id": each_client_id},
															        dataType: 'json',
															        success: function (response) {
															        	$('#loadingmessage').hide();

															        	toastr.success(response.message, response.title);
															        	//activity_data = response.activity_data;
															        }
															    });
															}
															tr.remove();
														}
													});
												}

												if(qb_company_id == "")
												{
													$(".import_cl_to_quickbook").hide();
												}
												else
												{
													$(".import_cl_to_quickbook").show();
												}

												$.each(currency, function(key, val) {
								                    var option = $('<option />');
								                    option.attr('value', key).text(val);
								                    $('.client_qb_currency').append(option);
								                });

												function import_client_to_quickbook(){
													$('#modal_import_client_to_qb').modal('toggle');
												}

												$(document).on("click","#saveImportClientToQB",function(element) {
													var client_qb_currency = $(this).parent().parent().find('.client_qb_currency').val(); 
													
													if(client_qb_currency != 0)
												    {
												    	$('#loadingmessage').show();
														$.ajax({ 
													        url: "masterclient/import_client_to_quickbook",
													        type: "POST",
													        dataType: 'json',
													        data: {"client_qb_currency": client_qb_currency},
													        success: function (response) {
													        	$('#loadingmessage').hide();
													        	if(response.Status == 1)
													        	{
													        		toastr.success(response.message, response.title);
													        	} 
													        	else if(response.Status == 2)
													        	{
													        		toastr.warning(response.message, response.title);
													        	}
													        	else if(response.Status == 3)
													        	{
													        		toastr.error(response.message, response.title);
													        	}
													        }
													    });
													}
												    else
												    {
												        toastr.error("Please select one Income Account before you save.", "Error");
												    }
												});
											</script>
											<style type="text/css">
												/*#datatable-client_filter {
											        display: none;
											    }*/

											    /*#datatable-client_length {
											        display: none;
											    }*/
											</style>
								</div>
								
					</div>
							<!-- end: page -->
							</section>
						</div>
					</section>
				</div>
			<!-- Modal LG -->
									
	<!-- 								<div id="modal_add_officer0" class="modal-block modal-block-lg mfp-hide">
										<section class="panel">
											<div class="panel-body">
												<div class="modal-wrapper">
													<div class="modal-text">
														<div class="tabs">
															<form class="form-horizontal" novalidate="novalidate">
																<div class="tab-content">
																	<div id="w2-account" class="tab-pane active">
																		<form>
																			<p style="float:left;margin-right:10px;">ID</p>
																			<div style="float: left; margin-right: 10px;"class="input-group mb-md">
																				<input style="20%;margin-right:10px;float:left;" type="text" id="task_todolist" class="form-control"/>
																			</div>
																			<div style="float: left; margin-right: 40px;"  class="input-group-btn">
																				<button style="height: 26px; padding: 0px;" type="button" id="button" class="btn btn-primary" tabindex="-1">Go</button>
																			</div>
																			<p style="float:left;margin-right:10px;">Name</p>
																			<div style="float: left; margin-right: 10px;"class="input-group mb-md">
																				<input style="20%;margin-right:10px;float:left;" type="text" id="task_todolist" class="form-control"/>
																			</div>
																		</form>
																		<table class="table table-bordered table-striped table-condensed mb-none"  id="datatable-default">
																			<thead>
																				<tr>
																					<th>No</th>
																					<th>Officer and Position</th>
																					<th></th>
																				</tr>
																			</thead>
																			<tbody id="body_officer">
																				<tr>
																					<td rowspan =4>1</td>
																					<td>
																						<table class="table table-bordered table-striped table-condensed mb-none" >
																							<thead>
																								<tr>
																									<th>Position</th>
																									<th>Date of Appointment</th>
																									<th>Date of Cesasion</th>
																									<th style="width:2%;"></th>
																								</tr>
																							</thead>
																							<tbody id="body_content">
																								<tr>
																									<td>
																										<div class="checkbox">
																											<label>
																												<input type="checkbox" value="">
																												Dir
																											</label>
																										</div>
																									</td>
																									<td>
																										<input type="text" data-plugin-datepicker data-date-format="dd/mm/yyyy" data-date-start-date="0d"/>
																									</td>
																									<td>
																										<input type="text" data-plugin-datepicker data-date-format="dd/mm/yyyy" data-date-start-date="0d"/>
																									</td>
																								</tr>
																								<tr>
																									
																									<td>
																										<div class="checkbox">
																											<label>
																												<input type="checkbox" value="">
																												Ceo
																											</label>
																										</div>
																									</td>
																									<td>
																										<input type="text" data-plugin-datepicker data-date-format="dd/mm/yyyy" data-date-start-date="0d"/>
																									</td>
																									<td>
																										<input type="text" data-plugin-datepicker data-date-format="dd/mm/yyyy" data-date-start-date="0d"/>
																									</td>
																								</tr>
																								<tr>
																									
																									<td>
																										<div class="checkbox">
																											<label>
																												<input type="checkbox" value="">
																												Md
																											</label>
																										</div>
																									</td>
																									<td>
																										<input type="text" data-plugin-datepicker data-date-format="dd/mm/yyyy" data-date-start-date="0d"/>
																									</td>
																									<td>
																										<input type="text" data-plugin-datepicker data-date-format="dd/mm/yyyy" data-date-start-date="0d"/>
																									</td>
																								</tr>
																								<tr>
																									
																									<td>
																										<div class="checkbox">
																											<label>
																												<input type="checkbox" value="">
																												Alternate Dir
																											</label>
																										</div>
																									</td>
																									<td>
																										<input type="text" data-plugin-datepicker data-date-format="dd/mm/yyyy" data-date-start-date="0d"/>
																									</td>
																									<td>
																										<input type="text" data-plugin-datepicker data-date-format="dd/mm/yyyy" data-date-start-date="0d"/>
																									</td>
																								</tr>
																							</tbody>
																						</table>
																					</td>
																					<td rowspan =4>
																						<a href="#"><i class="fa fa-pencil"></i></a>
																						<a href="#"><i class="fa fa-trash"></i></a>
																					</td>
																				</tr>													
																			</tbody>
																		</table>
																	</div>
																</div>
															</form>
														</div>
													</div>
												</div>
											</div>
											<footer class="panel-footer">
												<div class="row">
													<div class="col-md-12 text-right">
														<a class="modal-sizes"  href="#modalLG"><button class="btn btn-default modal-dismiss">Close</button></a>
													</div>
												</div>
											</footer>
										</section>
									</div>
									<div id="modal_add_officer" class="modal-block modal-block-lg mfp-hide">
									</div>
									
									
									
						<div id="modal_next" class="modal-block modal-block-lg mfp-hide">
										<section class="panel">
											<div class="panel-body">
												<div class="modal-wrapper">
													<div class="modal-text">
														<div class="tabs">
										<form class="form-horizontal" novalidate="novalidate">
											<div class="tab-content">
												<div id="w2-account" class="tab-pane active">
													<table class="table table-bordered table-striped table-condensed mb-none">
													<thead>
											<tr style="background-color:white;">
												<td class="text-center" style="font-weight:bold;">Tabs</th>
												<td class="text-center" style="font-weight:bold;">Field</th>
												<td class="text-center" style="font-weight:bold;">Existing</th>
												<td class="text-center" style="font-weight:bold;">Changes</th>
											</tr>
											</thead>
										<tbody>
											<tr>
												<td class="text-left">Company Info</td>
												<td class="text-left">Company Name</td>
												<td class="text-center">XXXX</td>
												<td class="text-center">YYYY</td>
											</tr>
											<tr>
												<td class="text-left">Company Info</td>
												<td class="text-left">Building Name</td>
												<td class="text-center">AAAA</td>
												<td class="text-center">BBBB</td>
											</tr>
											<tr>
												<td class="text-left">Director</td>
												<td class="text-left">Dart -> Share Paid</td>
												<td class="text-center">10,000</td>
												<td class="text-center">100,000</td>
											</tr>
											<tr>
												<td class="text-left">Transfer</td>
												<td class="text-left">Dart -(1,000)</td>
												<td class="text-center">100,000</td>
												<td class="text-center">99,000</td>
											</tr>
											<tr>
												<td class="text-left">Transfer</td>
												<td class="text-left">Durt -(200)</td>
												<td class="text-center">10,000</td>
												<td class="text-center">9,800</td>
											</tr>
											<tr>
												<td class="text-left">Transfer</td>
												<td class="text-left">Dort +(1,200)</td>
												<td class="text-center">1,000</td>
												<td class="text-center">2,200</td>
											</tr>
											<tr>	
												<td class="text-left">Billing Info</td>
												<td class="text-left">Service 2 (Edit)</td>
												<td class="text-center">500</td>
												<td class="text-center">700</td>
											</tr>
											<tr>	
												<td class="text-left">Billing Info</td>
												<td class="text-left">Service 3 (ADD)</td>
												<td class="text-center">-</td>
												<td class="text-center">1,200</td>
											</tr>
										</tbody>
									</table>
												</div>
											</div>
										</form>
									</div>
													</div>
												</div>
											</div>
											<footer class="panel-footer">
												<div class="row">
													<div class="col-md-12 text-right">
														<a class="modal-sizes"  href="#modalLG"><button class="btn btn-default modal-dismiss">Back To Edit</button></a>
														<a href=""><button class="btn btn-success modal-dismiss">Print</button></a>
														<a  href=""><button class="btn btn-info modal-dismiss">Save</button></a>
														<a  href=""><button class="btn btn-danger modal-dismiss">Close</button></a>
													</div>
												</div>
											</footer>
										</section>
									</div>

					
									<div id="modal_unrecieved" class="modal-block modal-block-lg mfp-hide">
									<section class="panel">
											<div class="panel-body">
												<div class="tabs">
													<ul class="nav nav-tabs nav-justify">
														<li class="active">
															<a href="#w2-unreceived" data-toggle="tab" class="text-center">
																<span class="badge hidden-xs">1</span>
																Unreceived Document
															</a>
														</li>
														<li>
															<a href="#w2-received" data-toggle="tab" class="text-center">
																<span class="badge hidden-xs">2</span>
																Received Document
															</a>
														</li>
													</ul>
													<div class="tab-content">
														<div id="w2-unreceived" class="tab-pane active">
															<header class="panel-heading">
																<h2 class="panel-title">Unreceived Document</h2>
															</header>
															<div class="panel-body">
																<table class="table table-bordered table-striped table-condensed mb-none">
																	<tr>
																		<th>No</th>
																		<th>Document</th>
																		<th>Date Created</th>
																		<th>PIC</th>
																		<th></th>
																	</tr>
																	<tr>
																		<td>1</td>
																		<td>Akta Notaris</td>
																		<td>02/05/2016</td>
																		<td>User</td>
																		<td><a href="#">Upload</a>&nbsp;&nbsp;&nbsp;<a href="#">Download</a></td>
																	</tr>
																	<tr>
																		<td>2</td>
																		<td>Memorandum</td>
																		<td>02/05/2016</td>
																		<td>User</td>
																		<td><a href="#">Upload</a>&nbsp;&nbsp;&nbsp;<a href="#">Download</a></td>
																	</tr>
																</table>
															</div>
															<footer class="panel-footer">
																<div class="row">
																	<div class="col-md-12 text-right">
																		
																		<button class="btn btn-default modal-dismiss">OK</button>
																	</div>
																</div>
															</footer>
														</div>
														<div id="w2-received" class="tab-pane">
															<header class="panel-heading">
																<h2 class="panel-title">Received Document</h2>
															</header>
															<div class="panel-body">
																<table class="table table-bordered table-striped table-condensed mb-none">
																	<tr>
																		<th>No</th>
																		<th>Document</th>
																		<th>Date Created</th>
																		<th>PIC</th>
																		<th></th>
																	</tr>
																	<tr>
																		<td>1</td>
																		<td>Akta Notaris</td>
																		<td>02/05/2016</td>
																		<td>User</td>
																		<td><a href="#">Download</a></td>
																	</tr>
																	<tr>
																		<td>2</td>
																		<td>Memorandum</td>
																		<td>02/05/2016</td>
																		<td>User</td>
																		<td><a href="#">Download</a></td>
																	</tr>
																</table>
															</div>
															<footer class="panel-footer">
																<div class="row">
																	<div class="col-md-12 text-right">
																		
																		<button class="btn btn-default modal-dismiss">Close</button>
																	</div>
																</div>
															</footer>
														</div>
													</div>
												</div>
											</div>
										</section>
									</div>
									<div id="modal_document" class="modal-block modal-block-lg mfp-hide">
										<section class="panel">
											<header class="panel-heading">
												<h2 class="panel-title">Document Uploaded</h2>
											</header>
											<div class="panel-body">
												<table class="table table-bordered table-striped table-condensed mb-none">
													<tr>
														<th>No</th>
														<th>Document</th>
														<th>Date Created</th>
														<th>PIC</th>
														<th></th>
													</tr>
													<tr>
														<td>1</td>
														<td>Akta Notaris</td>
														<td>02/05/2016</td>
														<td>Admin</td>
														<td><a href="#">Preview</a></td>
													</tr>
													<tr>
														<td>2</td>
														<td>Memorandum</td>
														<td>02/05/2016</td>
														<td>User</td>
														<td><a href="#">Preview</a></td>
													</tr>
													<tr>
														<td>3</td>
														<td>ID CARD MR. XXXX</td>
														<td>02/05/2016</td>
														<td>User</td>
														<td><a href="#">Preview</a></td>
													</tr>
													<tr>
														<td>4</td>
														<td>ID CARD MR. YYYYY</td>
														<td>02/05/2016</td>
														<td>User</td>
														<td><a href="#">Preview</a></td>
													</tr>
													<tr>
														<td>5</td>
														<td>ID CARD MR. ZZZZZ</td>
														<td>02/05/2016</td>
														<td>User</td>
														<td><a href="#">Preview</a></td>
													</tr>
												</table>
											</div>
											<footer class="panel-footer">
												<div class="row">
													<div class="col-md-12 text-right">
														
														<button class="btn btn-default modal-dismiss">Close</button>
													</div>
												</div>
											</footer>
										</section>
									</div>
									
									<div id="modal_service" class="modal-block modal-block-lg mfp-hide">
										<section class="panel">
											<header class="panel-heading">
												<h2 class="panel-title">Service</h2>
											</header>
											<div class="panel-body">
												<table class="table table-bordered table-striped table-condensed mb-none">
													<tr>
														<th>No</th>
														<th>Date</th>
														<th>Service</th>
														<th>Charges</th>
														<th>Payment</th>
														<th>Created</th>
														<th><a href="#" id="add_service"><i class="fa fa-plus-circle"></i></a></th>
														
													</tr>
													<tbody id="service_body">
														<tr>
															<td><label><input type="checkbox">1</label></td>
															<td><input type="text" value="2016-01-01"/></td>
															<td><select class="form-control input-xs">
																	<option>Service 1</option>
																	<option>Service 2</option>
																	<option>Service 3</option>
																</select>
															</td>
															<td><input type="text" value="10.000"/></td>
															<td>Paid</td>
															<td>2016-01-01</td>
															<td><a href="#">Edit</a></td>
														</tr>
														<tr>
															<td><label><input type="checkbox">2</label></td>
															<td><input type="text" value="2016-01-01"/></td>
															<td><select class="form-control input-xs">
																	<option>Service 1</option>
																	<option>Service 2</option>
																	<option>Service 3</option>
																</select>
															</td>
															<td><input type="text" value="10.000"/></td>
															<td>Billed</td>
															<td>2016-01-01</td>
															<td><a href="#">Edit</a></td>
														</tr>
														<tr>
															<td><label><input type="checkbox">3</label></td>
															<td><input type="text" value="2016-01-01"/></td>
															<td><select class="form-control input-xs">
																	<option>Service 1</option>
																	<option>Service 2</option>
																	<option>Service 3</option>
																</select>
															</td>
															<td><input type="text" value="10.000"/></td>
															<td>none</td>
															<td>2016-01-01</td>
															<td><a href="#">Edit</a></td>
														</tr>
													</tbody>
												</table>
											</div>
											<footer class="panel-footer">
												<div class="row">
													<div class="col-md-12 text-right">
														
														<button href="#modal_create_billing" class="btn btn-default modal-sizes">Create Billing</button>
														<button class="btn btn-default modal-dismiss">OK</button>
													</div>
												</div>
											</footer>
										</section>
									</div>
									<div id="modal_history_filing" class="modal-block modal-block-lg mfp-hide">
										<section class="panel">
											<header class="panel-heading">
												<h2 class="panel-title">Filing History <a class="" href="<?= base_url();?>masterclient/setting_filing"><i class="fa fa-gear"></i></a></h2>
											</header>
											<div class="panel-body">
												<table class="table table-bordered table-striped table-condensed mb-none" >
													<tr>
														<th>No</th>
														<th>Filing Date</th>
														<th>Document</th>
														<th>PIC</th>
													</tr>
													<tr>
														<td>1</td>
														<td>02/05/2016</td>
														<td><a href="#">Annual Filing</a></td>
														<td>Admin</td>
													</tr>
													<tr>
														<td>2</td>
														<td>02-May-2015</td>
														<td><a href="#">Annual Filing</a></td>
														<td>User</td>
													</tr>
													<tr>
														<td>2</td>
														<td>02-May-2014</td>
														<td><a href="#">Annual Filing</a></td>
														<td>User</td>
													</tr>
													<tr>
														<td>2</td>
														<td>02-May-2013</td>
														<td><a href="#">Annual Filing</a></td>
														<td>User</td>
													</tr>
												</table>
											</div>
											<footer class="panel-footer">
												<div class="row">
													<div class="col-md-12 text-right">
														
														<button class="btn btn-default modal-dismiss">OK</button>
													</div>
												</div>
											</footer>
										</section>
									</div>
									<div id="modal_setting_filing" class="modal-block modal-block-lg mfp-hide">
										<section class="panel">
											<header class="panel-heading">
												<h2 class="panel-title">Filing Setting <a href="#modal_history_filing" class="filing_date modal-sizes hidden"><i class="fa fa-history"></i></a></h2>
											</header>
											<div class="panel-body">
												<table class="table table-bordered table-striped table-condensed mb-none" >
													
													<tr class="hidden">
														<td>Filing NO</td>
														<td>5</td>
													</tr>
													<tr>
														<td>Year End</td>
														<td><input type="text" data-plugin-datepicker data-date-format="dd/mm/yyyy" data-date-start-date="0d" value="02/05/2016"></td>
													</tr>
													<tr>
														<td>Due Date S175</td>
														<td>02/05/2016
															<strong><span>&nbsp;Extension</span></strong>
															<select class="input-sm">
																<option>--Select--</option>
																<option>02/05/2016</option>
																<option>02/05/2016</option>
															</select>
														</td>
													</tr>
													<tr>
														<td>Due Date S201</td>
														<td>02/05/2016
														<strong><span>&nbsp;Extension</span></strong>
														<select class="input-sm">
																<option>--Select--</option>
																<option>02/05/2016</option>
																<option>02/05/2016</option>
															</select>
														</td>
													</tr>
													<tr>
														<td>AGM<br/><br/><label><input type="checkbox"/> &nbsp; Dispense AGM</label></td>
														<td><input type="text" data-plugin-datepicker data-date-format="dd/mm/yyyy" data-date-start-date="0d" value="02/05/2016"></td>
													</tr>
													
												</table>
											</div>
											<footer class="panel-footer">
												<div class="row">
													<div class="col-md-12 text-right">
														
														<button class="btn btn-default btn-info modal-dismiss">Save</button>
														<button class="btn btn-default btn-danger modal-dismiss">Close</button>
													</div>
												</div>
											</footer>
										</section>
									</div>
									<div id="modal_history_filing" class="modal-block modal-block-lg mfp-hide">
										<section class="panel">
											<header class="panel-heading">
												<h2 class="panel-title">History</h2>
											</header>
											<div class="panel-body">
												<table class="table table-bordered table-striped table-condensed mb-none" >
													
													<tr>
														<td>Filing NO</td>
														<td>5</td>
													</tr>
													<tr>
														<td>Year End</td>
														<td><input type="text" value="02/05/2016"></td>
													</tr>
													<tr>
														<td>AGM<br/><label><input type="checkbox"/>Dispense AGM</label></td>
														<td><input type="text" value="02/05/2016"></td>
													</tr>
													<tr>
														<td>Document Related</td>
														<td><a href="#">Document</a></td>
													</tr>
												</table>
											</div>
											<footer class="panel-footer">
												<div class="row">
													<div class="col-md-12 text-right">
														
														<button class="btn btn-default modal-dismiss">OK</button>
													</div>
												</div>
											</footer>
										</section>
									</div>
									<div id="modal_capital" class="modal-block modal-block-lg mfp-hide">
										<section class="panel">
											<header class="panel-heading">
												<h2 class="panel-title">Capital</h2>
											</header>
											<div class="panel-body">
												<h3>A. Issued Share Capital</h3>
												<table class="table table-bordered table-striped table-condensed mb-none" >
													<thead>
														<tr>
															<th>No</th>
															<th>Amount</th>
															<th>Number of Shares</th>
															<th>Currency</th>
															<th>Share Type</th>
															<th><a href="#" id="add_issued_share_capital"><i class="fa fa-plus-circle"></i></a></th>
														</tr>
													</thead>
													<tbody id="body_issued_share_capital">
														<tr>
															<td>1</td>
															<td><input type="text" class="form-control" value="1,000"/></td>
															<td><input type="text" class="form-control" value="1,000"/></td>
															<td><input type="text" class="form-control" value="SGD"/></td>
															<td>
																<select class="form-control">
																	<option>ORDINARY</option>
																	<option>Share Type 1</option>
																	<option>Share Type 2</option>
																	<option>Share Type 3</option>
																</select>
															</td>
															<td></td>
														</tr>
														<tr>
															<td>2</td>
															<td><input type="text" class="form-control" value="1,000"/></td>
															<td><input type="text" class="form-control" value="1,000"/></td>
															<td><input type="text" class="form-control" value="SGD"/></td>
															<td>
																<select class="form-control">
																	<option>ORDINARY</option>
																	<option>Share Type 1</option>
																	<option>Share Type 2</option>
																	<option>Share Type 3</option>
																</select>
															</td>
															<td></td>
														</tr>
														<tr>
															<td>3</td>
															<td><input type="text" class="form-control" value="1,000"/></td>
															<td><input type="text" class="form-control" value="1,000"/></td>
															<td><input type="text" class="form-control" value="SGD"/></td>
															<td>
																<select class="form-control">
																	<option>ORDINARY</option>
																	<option>Share Type 1</option>
																	<option>Share Type 2</option>
																	<option>Share Type 3</option>
																</select>
															</td>
															<td></td>
														</tr>
													</tbody>
												</table>
												<a href="#modal_allotment" class="modal-sizes btn btn-default">Allotment</a>
												<a href="#modal_buyback" class="modal-sizes btn btn-default">Buyback</a>
												<h3>B. Paid-Up Share Capital</h3>
												<table class="table table-bordered table-striped table-condensed mb-none" >
													<thead>
														<tr>
															<th>No</th>
															<th>Amount</th>
															<th>Number of Shares</th>
															<th>Currency</th>
															<th>Share Type</th>
															<th><a href="#" id="paid_issued_share_capital"><i class="fa fa-plus-circle"></i></a></th>
														</tr>
													</thead>
													<tbody id="body_paid_issued_share_capital">
														<tr>
															<td>1</td>
															<td><input type="text" class="form-control" value="1,000"/></td>
															<td><input type="text" class="form-control" value="1,000"/></td>
															<td><input type="text" class="form-control" value="SGD"/></td>
															<td>
																<select class="form-control">
																	<option>ORDINARY</option>
																	<option>Share Type 1</option>
																	<option>Share Type 2</option>
																	<option>Share Type 3</option>
																</select>
															</td>
															<td></td>
														</tr>
														<tr>
															<td>2</td>
															<td><input type="text" class="form-control" value="1,000"/></td>
															<td><input type="text" class="form-control" value="1,000"/></td>
															<td><input type="text" class="form-control" value="SGD"/></td>
															<td>
																<select class="form-control">
																	<option>ORDINARY</option>
																	<option>Share Type 1</option>
																	<option>Share Type 2</option>
																	<option>Share Type 3</option>
																</select>
															</td>
															<td></td>
														</tr>
														<tr>
															<td>3</td>
															<td><input type="text" class="form-control" value="1,000"/></td>
															<td><input type="text" class="form-control" value="1,000"/></td>
															<td><input type="text" class="form-control" value="SGD"/></td>
															<td>
																<select class="form-control">
																	<option>ORDINARY</option>
																	<option>Share Type 1</option>
																	<option>Share Type 2</option>
																	<option>Share Type 3</option>
																</select>
															</td>
															<td></td>
														</tr>
													</tbody>
												</table>
												<h3>C. Members</h3>
												<table class="table table-bordered table-striped table-condensed mb-none" >
													<thead>
														<tr>
															<th rowspan =2 valign=middle>No</th>
															<th>Name</th>
															<th>ID</th>
															<th>Nationality</th>
															<th>Share Type</th>
															<th>No of Share</th>
															<th>No of Share Paid</th>
															<th rowspan =2>Certificate</th>
															<th rowspan =2><a href="#" id="member_issued_share_capital"><i class="fa fa-plus-circle"></i></a></th>
														</tr>
														<tr>
															<th>Address</th>
															<th>Date Of Birth</th>
															<th>Occupation</th>
															<th>Currency</th>
															<th>Amount</th>
															<th>Amount Paid</th>
														</tr>
													</thead>
													<tbody id="body_member_issued_share_capital">
														<tr>
															<td rowspan =4>1</td>
															<td><input type="text" class="form-control input-xs" value="Dart"/></td>
															<td><input type="text" class="form-control" value="S8484841Z"/></td>
															<td><input type="text" class="form-control" value="MALAYSIAN"/></td>
															<td>
																<select class="form-control">
																	<option>ORDINARY</option>
																	<option>Share Type 1</option>
																	<option>Share Type 2</option>
																	<option>Share Type 3</option>
																</select>
															</td>
															<td><input type="text" class="form-control" value="400"/></td>
															<td><input type="text" class="form-control" value="400"/></td>
															<td rowspan =4>Certificate</td>
															<td rowspan =4>
																<a href="#"><i class="fa fa-pencil"></i></a>
																<a href="#modal_transfer" class="modal-sizes"><i class="fa fa-share-alt"></i></a>
															</td>
														</tr>
														<tr>
															<td rowspan =3><textarea style="height:100px;">1 Random Road, #01-01 Singapore 123001</textarea></td>
															<td><input type="text" class="form-control" value="01.01.84"/></td>
															<td><input type="text" class="form-control" value="DIRECTOR"/></td>
															<td><input type="text" class="form-control" value="SGD"/></td>
															<td><input type="text" class="form-control" value="400"/></td>
															<td><input type="text" class="form-control" value="400"/></td>
														</tr>
														<tr>
															
															<td>
																<select class="form-control">
																	<option>Singapore</option>
																	<option>Singapore P.R</option>
																</select>
															</td>
															<td>Local Phone </td>
															<td><input type="text" class="form-control" value="+65 1111 2222"/></td>
															<td></td>
															<td></td>
														</tr>
														<tr>
															<td></td>
															<td>Email</td>
															<td><input type="email" class="form-control" value="user@user.com"/></td>
															<td></td>
															<td></td>
														</tr>
														<tr>
															<td rowspan =4>2</td>
															<td><input type="text" class="form-control input-xs" value="Durt"/></td>
															<td><input type="text" class="form-control" value="S8555551Z"/></td>
															<td><input type="text" class="form-control" value="Singapore"/></td>
															<td>
																<select class="form-control">
																	<option>ORDINARY</option>
																	<option>Share Type 1</option>
																	<option>Share Type 2</option>
																	<option>Share Type 3</option>
																</select>
															</td>
															<td><input type="text" class="form-control" value="700"/></td>
															<td><input type="text" class="form-control" value="700"/></td>
															<td rowspan =4>Certificate</td>
															<td rowspan =4>
																<a href="#"><i class="fa fa-pencil"></i></a>
																<a href="#modal_transfer" class="modal-sizes"><i class="fa fa-share-alt"></i></a>
															</td>
														</tr>
														<tr>
															<td rowspan =3><textarea style="height:100px;">1 Random Road, #02-02 Singapore 98888</textarea></td>
															<td><input type="text" class="form-control" value="01.01.88"/></td>
															<td><input type="text" class="form-control" value="DIRECTOR"/></td>
															<td><input type="text" class="form-control" value="USD"/></td>
															<td><input type="text" class="form-control" value="700"/></td>
															<td><input type="text" class="form-control" value="700"/></td>
														</tr>
														<tr>
															
															<td>
																<select class="form-control">
																	<option>Singapore</option>
																	<option selected>Singapore P.R</option>
																</select>
															</td>
															<td>Local Phone </td>
															<td><input type="text" class="form-control" value="+65 1111 3333"/></td>
															<td></td>
															<td></td>
														</tr>
														<tr>
															<td></td>
															<td>Email</td>
															<td><input type="email" class="form-control" value="Admin@admin.com"/></td>
															<td></td>
															<td></td>
														</tr>
														
													</tbody>
												</table>
											</div>
											<footer class="panel-footer">
												<div class="row">
													<div class="col-md-12 text-right">
														
														<button class="btn btn-primary modal-confirm">Save</button>
														<button class="btn btn-default modal-dismiss">Close</button>
													</div>
												</div>
											</footer>
										</section>
									</div>
									<div id="modal_allotment" class="modal-block modal-block-lg mfp-hide">
										<section class="panel" id="wAllotment">
											<header class="panel-heading">
												<h2 class="panel-title">Allotment</h2>
											</header>
											<div class="panel-body">
															<div class="wizard-progress wizard-progress-lg">
																<div class="steps-progress">
																	<div class="progress-indicator"></div>
																</div>
																<ul class="wizard-steps">
																	<li class="active">
																		<a href="#alloment_number_shares" data-toggle="tab"><span>1</span>Number Shares</a>
																	</li>
																	<li>
																		<a href="#allotment_member" data-toggle="tab"><span>2</span>Member</a>
																	</li>
																	<li>
																		<a href="#allotment_confirm" data-toggle="tab"><span>3</span>Confirmation</a>
																	</li>
																</ul>
															</div>
											
															<form class="form-horizontal" novalidate="novalidate">
																<div class="tab-content">
																	<div id="alloment_number_shares" class="tab-pane active">
																		<div class="form-group">
																			<label class="col-sm-3 control-label">Date</label>
																			<div class="col-sm-9">
																				<input type="text" class="form-control" name="date" data-date-format="dd/mm/yyyy" data-plugin-datepicker required>
																			</div>
																		</div>
																		<div class="form-group">
																			<label class="col-sm-3 control-label" for="allotment_sharetype">Share Type</label>
																			<div class="col-sm-9">
																				
																				<select class="form-control">
																					<option>ORDINARY</option>
																					<option>Share Type 1</option>
																					<option>Share Type 2</option>
																					<option>Share Type 3</option>
																				</select>
																			</div>
																		</div>
																		<div class="form-group">
																			<label class="col-sm-3 control-label" for="allotment_sharetype">Currency</label>
																			<div class="col-sm-9">
																				
																				<select class="form-control">
																					<option>SGD</option>
																					<option>USD</option>
																				</select>
																			</div>
																		</div>
																		<div class="form-group">
																			<label class="col-sm-3 control-label" for="Allotment_Share">No of Share</label>
																			<div class="col-sm-9">
																				<input type="text" class="form-control" name="Allotment_Share" id="Allotment_Share" required>
																			</div>
																		</div>
																		<div class="form-group">
																			<label class="col-sm-3 control-label" for="Allotment_Share_amount">Amount</label>
																			<div class="col-sm-9">
																				<input type="text" class="form-control" name="Allotment_Share_amount" id="Allotment_Share_amount" required>
																			</div>
																		</div>
																	</div>
																	<div id="allotment_member" class="tab-pane">
																		<div class="col-md-12">
																			<div class="col-md-2">
																				<select class="form-control input-sm">
																					<option>ID</option>
																					<option>Name</option>
																				</select>
																			</div>
																			<div class="col-md-5">
																				<div class=" input-group">
																					<input type="text" class="form-control input-sm" id="w2-username" name="username" placeholder="Search">
																					<span class="input-group-btn">
																						<button class="btn btn-default" type="submit" style="height:30px;"><i class="fa fa-search"></i></button>
																					</span>
																				</div>
																			</div>
																			<div class="col-md-6">
																				<table class="table table-bordered table-striped table-condensed mb-none" >
																					<thead>
																						<tr>
																							<th>ID</th>
																							<th>Name</th>
																							<th width=20px></th>
																						</tr>
																					</thead>
																					<tbody>
																						<tr>
																							<td>x1y2z3</td>
																							<td>Person 1</td>
																							<td><button class="add_director">Add</button></td>
																						</tr>
																						<tr>
																							<td>Abcde</td>
																							<td>Person 2</td>
																							<td><button class="add_director">Add</button></td>
																						</tr>
																						<tr>
																							<td>Efghi</td>
																							<td>Person 3</td>
																							<td><button class="add_director">Add</button></td>
																						</tr>
																					</tbody>
																				</table>
																				<br/>
																			</div>
																		</div><table  class="table table-bordered table-striped table-condensed mb-none">
																			<tr>
																				<th>No.</th>
																				<th>ID</th>
																				<th>Name</th>
																				<th>Share</th>
																				<th>Amount</th>
																				<th>Share Paid</th>
																				<th>Amount Paid</th>
																				<th>Certificate No.</th>
																			</tr>
																			<tr>
																				<td>1</td>
																				<td>
																					<div>
																						<input type="text" class="form-control" value="S8484841Z" required>
																					</div>
																				</td>
																				<td>
																					<div>
																						<input type="text" class="form-control" value="Dart" required>
																					</div>
																				</td>
																				<td>
																					<div>
																						<input type="text" class="form-control" value="100" required>
																					</div>
																				</td>
																				<td>
																					<div>
																						<input type="text" class="form-control" value="1000" required>
																					</div>
																				</td>
																				<td>
																					<div>
																						<input type="text" class="form-control" value="100" required>
																					</div>
																				</td>
																				<td>
																					<div>
																						<input type="text" class="form-control" value="1000" required>
																					</div>
																				</td>
																				<td>
																					<div>
																						<input type="text" class="form-control" value="CRT1199191" required>
																					</div>
																				</td>
																				<td>
																					<a href="#"><i class="fa fa-pencil"></i></a>
																					<a href="#"><i class="fa fa-trash"></i></a>
																				</td>
																			</tr>
																			<tr>
																				<td>2</td>
																				<td>
																					<div>
																						<input type="text" class="form-control" value="S2525252Z" required>
																					</div>
																				</td>	
																				<td>
																					<div>
																						<input type="text" class="form-control" value="Vart" required>
																					</div>
																				</td>
																				<td>
																					<div>
																						<input type="text" class="form-control" value="80" required>
																					</div>
																				</td>
																				<td>
																					<div>
																						<input type="text" class="form-control" value="800" required>
																					</div>
																				</td>
																				<td>
																					<div>
																						<input type="text" class="form-control" value="80" required>
																					</div>
																				</td>
																				<td>
																					<div>
																						<input type="text" class="form-control" value="800" required>
																					</div>
																				</td>
																				<td>
																					<div>
																						<input type="text" class="form-control" value="CRT1199191" required>
																					</div>
																				</td>
																				<td>
																					<a href="#"><i class="fa fa-pencil"></i></a>
																					<a href="#"><i class="fa fa-trash"></i></a>
																				</td>
																			</tr>
																		</table>
																	</div>
																	<div id="allotment_confirm" class="tab-pane">
																		<div class="form-group">
																			<table class="table table-bordered table-condensed">
																				<tr>
																					<td>ID</td>
																					<td>Name</td>
																					<td>No Of Share Paid</td>
																					<td>Amount Share</td>
																				</tr>
																				<tr>
																					<td width=80px>SG345678</td>
																					<td>Dart</td>
																					<td width=80px class="text-right">10,000.00</td>
																					<td width=80px class="text-right">$1,000,000.00</td>
																				</tr>
																				<tr>
																					<td>SG123123123</td>
																					<td>Durt</td>
																					<td class="text-right">1,000.00</td>
																					<td class="text-right">$100,000.00</td>
																				</tr>
																			</table>
																		</div>
																		<div class="form-group">
																			<label>Certificate:</label>
																		</div>
																		<div class="form-group">
																			<label><input type="radio">Close all existing and replace with new certificate</label>
																		</div>
																		<div class="form-group">
																			<label><input type="radio">New certificate number for shares alloted</label>
																		</div>
																		<div class="form-group">
																			<label><input type="radio">Manual Changes</label>
																		</div>
																	</div>
																</div>
															</form>
														
														
						
											</div>
											
											<div class="panel-footer">
												<ul class="pager">
													<li class="previous disabled">
														<a><i class="fa fa-angle-left"></i> Previous</a>
													</li>
													<li class="finish  modal-dismiss hidden pull-right">
														<a>Save</a>
													</li>
													<li class="next">
														<a>Next <i class="fa fa-angle-right"></i></a>
													</li>
												</ul>
											</div>
										</section>
									</div>
									<div id="modal_transfer" class="modal-block modal-block-lg mfp-hide">
										<section class="panel" id="wTransfer">
											<header class="panel-heading">
												<h2 class="panel-title">Transfer Share</h2>
											</header>
											<div class="panel-body">
															<div class="wizard-progress wizard-progress-lg">
																<div class="steps-progress">
																	<div class="progress-indicator"></div>
																</div>
																<ul class="wizard-steps">
																	<li class="active">
																		<a href="#transfer_number_shares" data-toggle="tab"><span>1</span>Number Shares</a>
																	</li>
																	<li>
																		<a href="#transfer_member" data-toggle="tab"><span>2</span>Member</a>
																	</li>
																	<li>
																		<a href="#transfer_confirm" data-toggle="tab"><span>3</span>Confirmation</a>
																	</li>
																</ul>
															</div>
											
															<form class="form-horizontal" novalidate="novalidate">
																<div class="tab-content">
																	<div id="transfer_number_shares" class="tab-pane active">
																		<div class="form-group">
																			<label class="col-sm-3 control-label">Date</label>
																			<div class="col-sm-9">
																				<input type="text" class="form-control" name="date" data-date-format="dd/mm/yyyy" data-date-start-date="0d"data-plugin-datepicker  required>
																			</div>
																		</div>	
																		<div class="form-group">
																			<label class="col-sm-3 control-label" for="allotment_sharetype">Share Type</label>
																			<div class="col-sm-9">
																				
																				<select class="form-control">
																					<option>ORDINARY</option>
																					<option>Share Type 1</option>
																					<option>Share Type 2</option>
																					<option>Share Type 3</option>
																				</select>
																			</div>
																		</div>
																		<div class="form-group">
																			<label class="col-sm-3 control-label" for="Currency">Currency</label>
																			<div class="col-sm-9">
																				<input type="text" class="form-control" name="Currency" id="Currency" value="SGD" required>
																			</div>
																		</div>
																	</div>
																	<div id="transfer_member" class="tab-pane">
																		<h3>From</h3>	
																		<table  class="table table-bordered table-striped table-condensed mb-none">
																			<tr>
																				<th>No.</th>
																				<th>ID</th>
																				<th>Name</th>
																				<th>Share</th>
																				<th>Share to Transfer</th>
																			</tr>
																			<tr>
																				<td>1</td>
																				<td>
																					<div>
																						<select class="form-control input-sm">
																							<option>--Select--</option>
																							<option selected>2882822 - Dart</option>
																							<option>3832837 - Durt</option>
																							<option>7362682 - Dort</option>
																						</select>
																					</div>
																				</td>
																				<td>
																					<div>Dart
																					</div>
																				</td>
																				<td>
																					<div>
																						1,000,000
																					</div>
																				</td>
																				<td>
																					<div>
																						<input type="text" class="form-control" value="1000" required>
																					</div>
																				</td>
																			</tr>
																			<tr>
																				<td>2</td>
																				<td>
																					<div>
																						<select class="form-control input-sm">
																							<option>--Select--</option>
																							<option>2882822 - Dart</option>
																							<option selected>3832837 - Durt</option>
																							<option>7362682 - Dort</option>
																						</select>
																					</div>
																				</td>
																				<td>
																					<div>Durt
																					</div>
																				</td>
																				<td>
																					<div>
																						200,000
																					</div>
																				</td>
																				<td>
																					<div>
																						<input type="text" class="form-control" value="200" required>
																					</div>
																				</td>
																			</tr>
																			<tr>
																				<td>3</td>
																				<td>
																					<div>
																						<select class="form-control input-sm">
																							<option>--Select--</option>
																							<option>2882822 - Dart</option>
																							<option>3832837 - Durt</option>
																							<option>7362682 - Dort</option>
																						</select>
																					</div>
																				</td>
																				<td>
																					<div>
																					</div>
																				</td>
																				<td>
																					<div>
																						
																					</div>
																				</td>
																				<td>
																					<div>
																						<input type="text" class="form-control" value="" required>
																					</div>
																				</td>
																			</tr>
																			<tfoot>
																				<tr>
																					<td colspan =4>Total</td>
																					<td align=right>1,200</td>
																				</tr>
																			</tfoot>
																		</table>
																		<h3>To</h3>	
																		<table  class="table table-bordered table-striped table-condensed mb-none">
																			<tr>
																				<th>No.</th>
																				<th>ID</th>
																				<th>Name</th>
																				<th>Share</th>
																				<th>Certificate</th>
																			</tr>
																			<tr>
																				<td>1</td>
																				<td>
																					<div>
																						<select class="form-control input-sm">
																							<option>--Select--</option>
																							<option >2882822 - Dart</option>
																							<option>3832837 - Durt</option>
																							<option selected>7362682 - Dort</option>
																						</select>
																					</div>
																				</td>
																				<td>
																					<div>Dort
																					</div>
																				</td>
																				<td>
																					<div>
																						<input type="text" class="form-control" value="1200" required>
																					</div>
																				</td>
																				<td>
																					<div>
																						<input type="file" class="form-control" required>
																					</div>
																				</td>
																			</tr>
																			<tr>
																				<td>2</td>
																				<td>
																					<div>
																						<select class="form-control input-sm">
																							<option>--Select--</option>
																							<option >2882822 - Dart</option>
																							<option>3832837 - Durt</option>
																							<option>7362682 - Dort</option>
																						</select>
																					</div>
																				</td>
																				<td>
																					<div>
																					</div>
																				</td>
																				<td>
																					<div>
																						<input type="text" class="form-control" value="" required>
																					</div>
																				</td>
																				<td>
																					<div>
																						<input type="file" class="form-control" required>
																					</div>
																				</td>
																			</tr>
																			<tfoot>
																				<tr>
																					<td colspan =3>Total</td>
																					<td align=right>1,200</td>
																					<td></td>
																				</tr>
																			</tfoot>
																		</table>
																	</div>
																	<div id="transfer_confirm" class="tab-pane">
																		<table class="table table-bordered">
																			<tr>
																				<td>No</td>
																				<td>Name</td>
																				<td>Share</td>
																				<td>Share To Transfer</td>
																				<td>Share Received</td>
																			</tr>
																			<tr>
																				<td>1</td>
																				<td>Dart</td>
																				<td align=right>1,000,000</td>
																				<td align=right style="color:red">1,000</td>
																				<td align=right></td>
																			</tr>
																			<tr>
																				<td>2</td>
																				<td>Durt</td>
																				<td align=right>200,000</td>
																				<td align=right style="color:red">200</td>
																				<td align=right></td>
																			</tr>
																			<tr>
																				<td>3</td>
																				<td>Dort</td>
																				<td align=right>10,000</td>
																				<td align=right></td>
																				<td align=right style="color:green">1,200</td>
																			</tr>
																		</table>
																		<div class="form-group">
																			<div class="col-sm-3">Certificate:</div>
																			<div class="col-sm-9">
																				<div >
																						<input type="radio"  name="certificate" required/>
																					<label>
																					Close All Existing and replace with new one</label>
																				</div>
																			</div>
																			<div class="col-sm-3"></div>
																			<div class="col-sm-9">
																				<div >
																						<input type="radio" name="certificate" required/>
																					<label>
																					Manual Changes</label>
																				</div>
																				<table class="table table-bordered" id="A">
																					<tr>
																						<td><a href="#">File1</a></td>
																						<td><a href="#">Replace</a></td>
																					</tr>
																					<tr>
																						<td><a href="#">File2</a></td>
																						<td><a href="#">Replace</a></td>
																					</tr>
																					<tr>
																						<td><a href="#">File3</a></td>
																						<td><a href="#">Replace</a></td>
																					</tr>
																				</table>
																			</div>	
																		</div>
																</div>
															</form>
														
														
						
											</div>
											</div>
											
											<div class="panel-footer">
												<ul class="pager">
													<li class="previous disabled">
														<a><i class="fa fa-angle-left"></i> Previous</a>
													</li>
													<li class="finish  modal-dismiss hidden pull-right">
														<a>Save</a>
													</li>
													<li class="next">
														<a>Next <i class="fa fa-angle-right"></i></a>
													</li>
												</ul>
											</div>
										</section>
									</div>
									<div id="modal_settle_payment" class="modal-block modal-block-lg mfp-hide">
										<section class="panel" id="wSettle_Payment">
											<header class="panel-heading">
												<h2 class="panel-title">Settle Payment</h2>
											</header>
											<div class="panel-body">
												<form class="form-horizontal" novalidate="novalidate">
													<div class="form-group">
														<label class="col-sm-3 control-label" for="date">Date</label>
														<div class="col-sm-9">
															<input type="text" class="form-control" name="date" data-plugin-datepicker data-date-format="dd/mm/yyyy" data-date-start-date="0d" id="date" value="" required>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-3 control-label" for="transferamount">No Of Share</label>
														<div class="col-sm-9">
															<input type="text" class="form-control" name="transferamount" id="transferamount" value=100 required>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-3 control-label" for="Amount">Amount</label>
														<div class="col-sm-9">
															<input type="text" class="form-control" name="Amount" id="Amount" value=10000 required>
														</div>
													</div> 
												</form>
														
														
						
											</div>
											<footer class="panel-footer">
												<div class="row">
													<div class="col-md-12 text-right">
														
														<button class="btn btn-primary modal-confirm">Save</button>
														<button class="btn btn-default modal-dismiss">Close</button>
													</div>
												</div>
											</footer>
										</section>
									</div>
									<div id="modal_officer" class="modal-block modal-block-lg mfp-hide">
										<section class="panel" id="wOfficer">
											<header class="panel-heading">
												<h2 class="panel-title">Add Officer</h2>
											</header>
											<div class="panel-body">
												<table class="table table-bordered table-striped table-condensed mb-none" >
													<thead>
														<tr>
															<th>Name</th>
															<td><input type="text" class="form-control input-xs" value="Dart"/></td>
														</tr>
														<tr>
															<th>ID</th>
															<td><input type="text" class="form-control" value="S8484841Z"/></td>
														</tr>
														<tr>
															<th>Nationality</th>
															<td><input type="text" class="form-control" value="MALAYSIAN"/></td>
														</tr>
														<tr>
															<th>Date Of Appoinment</th>
															<td><input type="text" class="form-control " data-plugin-datepicker data-date-format="dd/mm/yyyy" value="2010-01-01"/></td>
														</tr>
														<tr>
															<th>Date Of Cessation</th>
															<td><input type="text" class="form-control " data-plugin-datepicker data-date-format="dd/mm/yyyy" value="2016-01-01"/></td>
														</tr>
														<tr>
															<th>Address</th>
															<td><textarea style="height:100px;">1 Random Road, #01-01 Singapore 123001</textarea></td>
														</tr>
														<tr>
															<th>Alternate Address</th>
															<td><textarea style="height:100px;"></textarea></td>
														</tr>
														<tr>
															<td></td>
															<td>
																<select class="form-control">
																	<option>Singapore</option>
																	<option>Singapore P.R</option>
																</select>
															</td></tr>
														<tr>
															<th>Date Of Birth</th>
															<td><input type="text" class="form-control" value="01.01.84"/></td>
															
														</tr>
													</thead>
												</table>
												
											</div>
											<footer class="panel-footer">
												<div class="row">
													<div class="col-md-12 text-right">
														
														<button class="btn btn-successc modal-dismiss">Save</button>
														<button class="btn btn-danger modal-dismiss">Close</button>
													</div>
												</div>
											</footer>
										</section>
									</div>
									<div id="modal_person" class="modal-block modal-block-lg mfp-hide">
										<section class="panel" id="wPerson">
											<header class="panel-heading">
												<h2 class="panel-title">Add Person</h2>
											</header>
											<div class="panel-body">
												<table class="table table-bordered table-striped table-condensed mb-none" >
													<thead>
														<tr>
															<th>Name</th>
															<td><input type="text" class="form-control input-xs" value="Dart"/></td>
														</tr>
														<tr>
															<th>ID</th>
															<td><input type="text" class="form-control" value="S8484841Z"/></td>
														</tr>
														<tr>
															<th>Nationality</th>
															<td><input type="text" class="form-control" value="MALAYSIAN"/></td>
														</tr><tr>
															<th>Address</th>
															<td><textarea style="height:100px;">1 Random Road, #01-01 Singapore 123001</textarea></td>
														</tr>
														<tr>
															<th>Alternate Address</th>
															<td><textarea style="height:100px;"></textarea></td>
														</tr>
														<tr>
															<td></td>
															<td>
																<select class="form-control">
																	<option>Singapore</option>
																	<option>Singapore P.R</option>
																</select>
															</td></tr>
														<tr>
															<th>Date Of Birth</th>
															<td><input type="text" class="form-control" value="01.01.84"/></td>
															
														</tr>
														<tr>
															<td colspan=4>
																<table class="table table-condensed">
																	<tr>
																		<th>Position</th>
																		<th>Date Of Appoinment</th>
																		<th>Date Of Cessation</th>
																	</tr>
																	<tr>
																		<td><label><input type="checkbox" />Director</label></td>
																		<td><input type="text" data-date-format="dd/mm/yyyy" data-plugin-datepicker /> </td>
																		<td><input type="text" data-date-format="dd/mm/yyyy" data-plugin-datepicker /> </td>
																	</tr>
																	<tr>
																		<td><label><input type="checkbox" />CEO</label></td>
																		<td><input type="text" data-date-format="dd/mm/yyyy" data-plugin-datepicker /> </td>
																		<td><input type="text" data-date-format="dd/mm/yyyy" data-plugin-datepicker /> </td>
																	</tr>
																	<tr>
																		<td><label><input type="checkbox" />Managing Director</label></td>
																		<td><input type="text" data-date-format="dd/mm/yyyy" data-plugin-datepicker /> </td>
																		<td><input type="text" data-date-format="dd/mm/yyyy" data-plugin-datepicker /> </td>
																	</tr>
																	<tr>
																		<td><label><input type="checkbox" />Manager</label></td>
																		<td><input type="text" data-date-format="dd/mm/yyyy" data-plugin-datepicker /> </td>
																		<td><input type="text" data-date-format="dd/mm/yyyy" data-plugin-datepicker /> </td>
																	</tr>
																	<tr>
																		<td><label><input type="checkbox" />Secretary</label></td>
																		<td><input type="text" data-date-format="dd/mm/yyyy" data-plugin-datepicker /> </td>
																		<td><input type="text" data-date-format="dd/mm/yyyy" data-plugin-datepicker /> </td>
																	</tr>
																	<tr>
																		<td><label><input type="checkbox" />Auditor</label></td>
																		<td><input type="text" data-date-format="dd/mm/yyyy" data-plugin-datepicker /> </td>
																		<td><input type="text" data-date-format="dd/mm/yyyy" data-plugin-datepicker /> </td>
																	</tr>
																	<tr>
																		<td><label><input type="checkbox" />Alternate Director</label></td>
																		<td><input type="text" data-date-format="dd/mm/yyyy" data-plugin-datepicker /> </td>
																		<td><input type="text" data-date-format="dd/mm/yyyy" data-plugin-datepicker /> </td>
																	</tr>
																</table>
															</td>
														</tr>
														
													</thead>
												</table>
												
											</div>
											<footer class="panel-footer">
												<div class="row">
													<div class="col-md-12 text-right">
														
														<button class="btn btn-success modal-dismiss">Save</button>
														<button class="btn btn-danger modal-dismiss">Close</button>
													</div>
												</div>
											</footer>
										</section>
									</div>
									<div id="modal_other_officer" class="modal-block modal-block-lg mfp-hide">
										<section class="panel" id="wOtherOfficer">
											<header class="panel-heading">
												<h2 class="panel-title">Company Structure</h2>
											</header>
											<div class="panel-body">
												<h3>Officer</h3>
												<table class="table table-bordered table-striped table-condensed mb-none" >
													<thead>
														<tr>
															<th rowspan =2 valign=middle>No</th>
															<th>Name</th>
															<th>ID</th>
															<th>Position</th>
															<th rowspan=2>Date Of Appoinment</th>
															<th rowspan=2>Date Of Cessation</th>
															<th rowspan =2><a href="#" id="Officer_Add"><i class="fa fa-plus-circle"></i></a></th>
														</tr>
														<tr>
															<th>Address</th>
															<th>Date Of Birth</th>
															<th>Nationality</th>
														</tr>
													</thead>
													<tbody id="body_officer">
														<tr>
															<td rowspan =4>1</td>
															<td><input type="text" class="form-control input-xs" value="Officer Name/Company User"/></td>
															<td><input type="text" class="form-control" value="S8484841Z"/></td>
															<td><input type="text" class="form-control" value="Secretary"/></td>
															<td><input type="text" class="form-control " data-plugin-datepicker data-date-format="dd/mm/yyyy" value="2010-01-01"/></td>
															<td><input type="text" class="form-control " data-plugin-datepicker data-date-format="dd/mm/yyyy" value="2016-01-01"/></td>
															
															<td rowspan =4>
																<a href="#"><i class="fa fa-pencil"></i></a>
																<a href="#"><i class="fa fa-trash"></i></a>
															</td>
														</tr>
														<tr>
															<td rowspan =3><textarea style="height:100px;">1 Random Road, #01-01 Singapore 123001</textarea></td>
															<td><input type="text" class="form-control" value="01.01.84"/></td>
															
															<td colspan=3>
																<select class="form-control" style="width:50%;float:left;">
																	<option>Singapore</option>
																	<option>MALAYSIA</option>
																</select>
																&nbsp;
																<label><input type="checkbox">Singapore P.R</label>
															</td>
														</tr>
														<tr>
															<td>Local Phone </td>
															<td colspan=3><input type="text" class="form-control" value="+65 1111 2222"/></td>
														</tr>
														<tr>
															<td>Email</td>
															<td colspan=3><input type="email" class="form-control" value="user@user.com"/></td>
														</tr>
														<tr>
															<td rowspan =4>2</td>
															<td><input type="text" class="form-control input-xs" value="Officer Name/Company User"/></td>
															<td><input type="text" class="form-control" value="S829282Z"/></td>
															<td><input type="text" class="form-control" value="Admin"/></td>
															<td><input type="text" class="form-control " data-plugin-datepicker data-date-format="dd/mm/yyyy" value="2010-01-01"/></td>
															<td><input type="text" class="form-control " data-plugin-datepicker data-date-format="dd/mm/yyyy" value="2016-01-01"/></td>
															
															<td rowspan =4>
																<a href="#"><i class="fa fa-pencil"></i></a>
																<a href="#"><i class="fa fa-trash"></i></a>
															</td>
														</tr>
														<tr>
															<td rowspan =3><textarea style="height:100px;">1 Cecil Park, #01-01 Singapore 123001</textarea></td>
															<td><input type="text" class="form-control" value="01.01.84"/></td>
															
															<td colspan=3>
																<select class="form-control" style="width:50%;float:left;">
																	<option>Singapore</option>
																	<option>MALAYSIA</option>
																</select>
																&nbsp;
																<label><input type="checkbox">Singapore P.R</label>
															</td>
														</tr>
														<tr>
															<td>Local Phone </td>
															<td colspan=3><input type="text" class="form-control" value="+65 1111 2222"/></td>
														</tr>
														<tr>
															<td>Email</td>
															<td colspan=3><input type="email" class="form-control" value="user@user.com"/></td>
														</tr>
																												
													</tbody>
												</table>
													
						
											</div>
											<footer class="panel-footer">
												<div class="row">
													<div class="col-md-12 text-right">
														
														<button class="btn btn-default modal-dismiss">OK</button>
													</div>
												</div>
											</footer>
										</section>
									</div>
									<div id="modal_add" class="modal-block modal-block-lg mfp-hide">
										<section class="panel" id="wAddCompany">
											<header class="panel-heading">
												<h2 class="panel-title">Company Add</h2>
											</header>
											<div class="panel-body">
												<table class="table table-bordered table-striped table-condensed mb-none" >
													<thead>
														<tr>
															<th>Client Code</th>
															<th><input type="text" class="form-control" /></th>
														</tr>
														<tr>
															<th>Company Name</th>
															<th><input type="text" class="form-control" /></th>
														</tr>
														<tr>
															<th>Company UEN</th>
															<th><input type="text" class="form-control" /></th>
														</tr>
														<tr>
															<th>Date Incorp</th>
															<th>
																<div class="input-group">
																	<span class="input-group-addon">
																		<i class="far fa-calendar-alt"></i>
																	</span><input type="text" class="form-control"  data-plugin-datepicker data-date-format="dd/mm/yyyy" data-date-start-date="0d" style="height:30px;"/>
																</div>
															</th>
														</tr>
														<tr>
															<th>Register Address</th>
															<th><textarea type="text" class="form-control"></textarea></th>
														</tr>
														<tr>
															<th>Principle Activity 1</th>
															<th><input type="text" class="form-control" /></th>
														</tr>
														<tr>
															<th>Principle Activity 2</th>
															<th><input type="text" class="form-control" /></th>
														</tr>
														<tr>
															<th>Contact Person</th>
															<th><input type="text" class="form-control" /></th>
														</tr>
														<tr>
															<th>Phone</th>
															<th><input type="text" class="form-control" /></th>
														</tr>
														<tr>
															<th>Email</th>
															<th><input type="email" class="form-control" /></th>
														</tr>
														<tr>
															<th>Fax</th>
															<th><input type="text" class="form-control" /></th>
														</tr>
														<tr>
															<th>Use Company Office</th>
															<th><label><input type="checkbox"></label></th>
														</tr>
														<tr>
															<th>Listed Company</th>
															<th><label><input type="checkbox"></label></th>
														</tr>
													</thead>
												</table>						
											</div>
											<footer class="panel-footer">
												<div class="row">
													<div class="col-md-12 text-right">
														
														<button class="btn btn-default modal-dismiss">Save</button>
														<button class="btn btn-default modal-dismiss">Close</button>
													</div>
												</div>
											</footer>
										</section>
									</div>
									<div id="modal_edit" class="modal-block modal-block-lg mfp-hide">
										<section class="panel" id="wEditCompany">
											<header class="panel-heading">
												<h2 class="panel-title">Company Add</h2>
											</header>
											<div class="panel-body">
												<table class="table table-bordered table-striped table-condensed mb-none" >
													<thead>
														<tr>
															<th>Client Code</th>
															<th><input type="text" class="form-control" value="Client001"/></th>
														</tr>
														<tr>
															<th>Company Name</th>
															<th><input type="text" class="form-control" value="ABC"/></th>
														</tr>
														<tr>
															<th>Company UEN</th>
															<th><input type="text" class="form-control"  value="XXX.XXXX.XX"/></th>
														</tr>
														<tr>
															<th>Date Incorp</th>
															<th>
																<div class="input-group">
																	<span class="input-group-addon">
																		<i class="far fa-calendar-alt"></i>
																	</span><input type="text" class="form-control"  data-plugin-datepicker data-date-format="dd/mm/yyyy" data-date-start-date="0d" style="height:30px;" value="2016-01-01"/>
																</div>
															</th>
														</tr>
														<tr>
															<th>Register Address</th>
															<th><textarea type="text" class="form-control">1st Random Count Star, MALAYSIA 155111</textarea></th>
														</tr>
														<tr>
															<th>Principle Activity 1</th>
															<th><input type="text" class="form-control" value="Developer" /></th>
														</tr>
														<tr>
															<th>Principle Activity 2</th>
															<th><input type="text" class="form-control" value="-"/></th>
														</tr>
														<tr>
															<th>Contact Person</th>
															<th><input type="text" class="form-control" value="DART" /></th>
														</tr>
														<tr>
															<th>Phone</th>
															<th><input type="text" class="form-control"  value="+61 1122 2222"/></th>
														</tr>
														<tr>
															<th>Email</th>
															<th><input type="email" class="form-control"  value="user@client.com"/></th>
														</tr>
														<tr>
															<th>Fax</th>
															<th><input type="text" class="form-control"  value="+61 1122 2223"/></th>
														</tr>
														<tr>
															<th>Use Company Office</th>
															<th><label><input type="checkbox" checked></label></th>
														</tr>
														<tr>
															<th>Listed Company</th>
															<th><label><input type="checkbox"></label></th>
														</tr>
													</thead>
												</table>						
											</div>
											<footer class="panel-footer">
												<div class="row">
													<div class="col-md-12 text-right">
														
														<button class="btn btn-primary modal-confirm">Save</button>
														<button class="btn btn-default modal-dismiss">Close</button>
													</div>
												</div>
											</footer>
										</section>
									</div>
									<div id="modal_overview" class="modal-block modal-block-lg mfp-hide">
										<section class="panel" id="wOverview">
											<header class="panel-heading">
												<h2 class="panel-title">Company Overview</h2>
											</header>
											<div class="panel-body">
												<table class="table table-bordered table-striped table-condensed mb-none" >
													<thead>
														<tr>
															<th>Company :</th>
															<th>ABCD</th>
														</tr>
														<tr>
															<th>UEN :</th>
															<th>XXX.XXXX.XX</th>
														</tr>
														<tr>
															<th>Address</th>
															<th>1 Random Road, #01-01 Singapore 123001</th>
														</tr>
														<tr>
															<th>Contact</th>
															<th>Dart<br/>S8484841Z<br/>+65 1111 2222<br/>user@user.com</th>
														</tr>
														<tr>
															<th>Unpaid Invoice</th>
															<th><a href="#modal_billing" class="modal-sizes">$10.000,-</a></th>
														</tr>
														<tr>
															<th>Unreceived Document</th>
															<th><a class="edit_client" href="<?= base_url();?>masterclient/unreceived_doc">$10.000,-</a></th>
														</tr>
													</thead>
												</table>
													
						
											</div>
											<footer class="panel-footer">
												<div class="row">
													<div class="col-md-12 text-right">
														
														<button class="btn btn-default modal-dismiss">OK</button>
													</div>
												</div>
											</footer>
										</section>
									</div>
									
									<div id="modal_buyback" class="modal-block modal-block-lg mfp-hide">
										<section class="panel" id="wBuyback">
											<header class="panel-heading">
												<h2 class="panel-title">Buyback</h2>
											</header>
											<div class="panel-body">
												<div class="wizard-progress wizard-progress-lg">
													<div class="steps-progress">
														<div class="progress-indicator"></div>
													</div>
													<ul class="wizard-steps">
														<li class="active">
															<a href="#buyback_number_shares" data-toggle="tab"><span>1</span>Shares</a>
														</li>
														<li>
															<a href="#buyback_member" data-toggle="tab" id="calculate_buyback"><span>2</span>Member</a>
														</li>
														<li>
															<a href="#allotment_confirm" data-toggle="tab"><span>3</span>Confirmation</a>
														</li>
													</ul>
												</div>
								
												<form class="form-horizontal" novalidate="novalidate">
													<div class="tab-content">
														<div id="buyback_number_shares" class="tab-pane active">
															<div class="form-group">
																<label class="col-sm-3 control-label">Date</label>
																<div class="col-sm-9">
																	<input type="text" class="form-control" data-date-format="dd/mm/yyyy" data-plugin-datepicker required>
																</div>
															</div>
															<div class="form-group">
																<label class="col-sm-3 control-label" for="allotment_sharetype">Share Type</label>
																<div class="col-sm-9">
																	
																	<select class="form-control">
																		<option>ORDINARY</option>
																		<option>Share Type 1</option>
																		<option>Share Type 2</option>
																			<option>Share Type 3</option>
																	</select>
																</div>
															</div>
															<div class="form-group">
																<label class="col-sm-3 control-label" for="allotment_sharetype">Currency</label>
																<div class="col-sm-9">
																	
																	<select class="form-control">
																		<option>SGD</option>
																		<option>USD</option>
																	</select>
																</div>
															</div>
															<div class="form-group">
																<label class="col-sm-3 control-label" for="Allotment_Share">Buyback Share (%)</label>
																<div class="col-sm-9">
																	<input type="text" class="form-control" name="Allotment_Share" id="Allotment_Share" value="15"  required>
																</div>
															</div>
															<div class="form-group">
																<label class="col-sm-3 control-label" for="Allotment_Share_amount">Share Amount</label>
																<div class="col-sm-9">
																	<input type="text" class="form-control" name="Allotment_Share_amount" id="Allotment_Share_amount"  value="10000" required>
																</div>
															</div>
														</div>
														<div id="buyback_member" class="tab-pane">
															<h1>Total Share Buyback : <span id="t_s_bb"> </span></h1>
															<table  class="table table-bordered table-striped table-condensed mb-none">
																<tr>
																	<th>No.</th>
																	<th>Name</th>
																	<th>ID</th>
																	<th>Share</th>
																	<th>Amount</th>
																	<th>Share Buyback</th>
																	<th>Amount Paid</th>
																	<th>Certificate No.</th>
																</tr>
																<tr>
																	<td>1</td>
																	<td>
																		<div>
																			<input type="text" class="form-control" value="Dart" required>
																		</div>
																	</td>
																	<td>
																		<div>
																			<input type="text" class="form-control" value="S8484841Z" required>
																		</div>
																	</td>
																	<td>
																		<div>
																			<input type="text" id="shareori_bb1" class="form-control" value="100" required>
																		</div>
																	</td>
																	<td>
																		<div>
																			<input type="text" id="amountori_bb1" class="form-control" value="1000" required>
																		</div>
																	</td>
																	<td>
																		<div>
																			<input type="text" class="share_bb form-control" data-id=1 value="100" required>
																		</div>
																	</td>
																	<td>
																		<div>
																			<input type="text" id="amount_bb1" class="form-control" value="1000" required>
																		</div>
																	</td>
																	<td>
																		<div>
																			<input type="text" class="form-control" value="CRT1199191" required>
																		</div>
																	</td>
																	<td>
																		<a href="#"><i class="fa fa-pencil"></i></a>
																		<a href="#"><i class="fa fa-trash"></i></a>
																	</td>
																</tr>
																<tr>
																	<td>2</td>
																	<td>
																		<div>
																			<input type="text" class="form-control" value="Durt" required>
																		</div>
																	</td>
																	<td>
																		<div>
																			<input type="text" class="form-control" value="S7272727Z" required>
																		</div>
																	</td>
																	<td>
																		<div>
																			<input type="text" id="shareori_bb2" class=" form-control" value="100" required>
																		</div>
																	</td>
																	<td>
																		<div>
																			<input type="text" id="amountori_bb2" class=" form-control" value="1000" required>
																		</div>
																	</td>
																	<td>
																		<div>
																		<input type="text" class="share_bb form-control" data-id="2" value="100" required>
																		</div>
																	</td>
																	<td>
																		<div>
																			<input type="text" id="amount_bb2" class=" form-control" value="1000" required>
																		</div>
																	</td>
																	<td>
																		<div>
																			<input type="text" class="form-control" value="CRT22333441" required>
																		</div>
																	</td>
																	<td>
																		<a href="#"><i class="fa fa-pencil"></i></a>
																		<a href="#"><i class="fa fa-trash"></i></a>
																	</td>
																</tr>
																<tr>
																	<td colspan=3>Total</td>
																	<td id="total_Share"></td>
																	<td id="total_amount"></td>
																	<td id="total_shareBB"></td>
																	<td id="total_amountBB"></td>
																	<td id=""></td>
																	<td id=""></td>
																</tr>	
															</table>
														</div>
														<div id="allotment_confirm" class="tab-pane">
															<table class="table table-bordered">
																<tr>
																	<td>No</td>
																	<td>Name</td>
																	<td>Share</td>
																	<td>Amount</td>
																	<td>Share BB</td>
																	<td>Amount BB</td>
																	<td>Total Share</td>
																	<td>Total Amount</td>
																</tr>
																<tr>
																	<td>1</td>
																	<td>Dart</td>
																	<td align=right>100</td>
																	<td align=right>1,000</td>
																	<td align=right>15</td>
																	<td align=right>6,666.67</td>
																	<td align=right>115</td>
																	<td align=right>7,666.67</td>
																</tr>
																<tr>
																	<td>2</td>
																	<td>Durt</td>
																	<td align=right>100</td>
																	<td align=right>1,000</td>
																	<td align=right>15</td>
																	<td align=right>6,666.67</td>
																	<td align=right>115</td>
																	<td align=right>7,666.67</td>
																</tr>
															</table>
															<div class="form-group">
																<div class="col-sm-3">Certificate:</div>
																<div class="col-sm-9">
																	<div >
																			<input type="radio"  name="certificate" required/>
																		<label>
																		Close All Existing and replace with new one</label>
																	</div>
																</div>
																<div class="col-sm-3"></div>
																<div class="col-sm-9">
																	<div >
																			<input type="radio" name="certificate" required/>
																		<label>
																		Manual Changes</label>
																	</div>
																	<table class="table table-bordered" id="A">
																		<tr>
																			<td><a href="#">File1</a></td>
																			<td><a href="#">Replace</a></td>
																		</tr>
																		<tr>
																			<td><a href="#">File2</a></td>
																			<td><a href="#">Replace</a></td>
																		</tr>
																		<tr>
																			<td><a href="#">File3</a></td>
																			<td><a href="#">Replace</a></td>
																		</tr>
																	</table>
																</div>	
															</div>
														</div>
													</div>
												</form>
														
														
						
											</div>
											<div class="panel-footer">
												<ul class="pager">
													<li class="previous disabled">
														<a><i class="fa fa-angle-left"></i> Previous</a>
													</li>
													<li class="finish  modal-dismiss hidden pull-right">
														<a>Save</a>
													</li>
													<li class="next">
														<a>Next <i class="fa fa-angle-right"></i></a>
													</li>
												</ul>
											</div>
										</section>
									</div> -->
									<!-- <div id="modal_message" class="modal-block modal-block-lg mfp-hide">
										<section class="panel" id="wMessage">
											<header class="panel-heading">
												<h2 class="panel-title">Send Messages</h2>
											</header>
											<div class="panel-body">
												<h4>Send To</h4>
												<div class="input-group mb-md">
													<select id="ddlViewBy" class="form-control">
														<option value="user 1">user 1</option>
														<option value="user 2">user 2</option>
														<option value="user 3">user 3</option>
													</select>
												</div>	
												<h4>Messages</h4>
												<div class="input-group mb-md">
													<textarea class="form-control"></textarea>
												</div>						
											</div>
											<footer class="panel-footer">
												<div class="row">
													<div class="col-md-12 text-right">
														<utton class="btn btn-primary modal-confirm">Confirm</button
														<button class="btn btn-default modal-dismiss">Send</button>
														<button class="btn btn-default modal-dismiss">Close</button>
													</div>
												</div>
											</footer>
										</section>
									</div> -->

<script>
	var access_right_client_module = <?php echo json_encode($client_module);?>;

	if(access_right_client_module == "read")
	{
		$('.edit_client').hide();
		$('.delete_client').attr("disabled", true);
	}

	$(document).on('click','.edit_client',function() {
		// alert($(this).data('name'));
		localStorage.setItem('slitems', $(this).data('name'));
			$("#file_add_client").hide();
		location.href = "<?= base_url();?>masterclient/edit";
	});

	// $.each(service_category, function(key, val) {
	//     var option = $('<option />');
	//     option.attr('value', key).text(val);

	//     $("#service_category").append(option);
	// });
	
		// $(document).on('click','.edit_client',function(){
		// });	
		$(document).on('ready',function() {
			$("#pencarian").focus();
			$("#pencarian").select();
		});


		console.log('<?=$this->session->userdata('user_id')?>')
</script>
<style>
	#buttonclick .datatables-header {
		display:none;
	}
</style>