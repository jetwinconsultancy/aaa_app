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

							<?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
							echo form_open_multipart("masterclient", $attrib);

							?>
							<select id="service_category" class="form-control service_category" style="width:200px;" name="service_category">
								<option value="0" <?=$_POST['service_category']=='0'?'selected':'';?>>All</option>
								<option value="1" <?=$_POST['service_category']=='1'?'selected':'';?>>Statutory Audit</option>
								<option value="2" <?=$_POST['service_category']=='2'?'selected':'';?>>Accounting</option>
								<option value="3" <?=$_POST['service_category']=='3'?'selected':'';?>>Tax</option>
								<option value="4" <?=$_POST['service_category']=='4'?'selected':'';?>>Secretary</option>
								<option value="5" <?=$_POST['service_category']=='5'?'selected':'';?>>Administrative</option>
								<option value="6" <?=$_POST['service_category']=='6'?'selected':'';?>>Human Resource</option>
								<option value="7" <?=$_POST['service_category']=='7'?'selected':'';?>>Registered Office Address</option>
								<option value="8" <?=$_POST['service_category']=='8'?'selected':'';?>>Others</option>
								<option value="9" <?=$_POST['service_category']=='9'?'selected':'';?>>I.T.</option>
								<option value="10" <?=$_POST['service_category']=='10'?'selected':'';?>>Other Assurance</option>
							</select>
							<select class="form-control" name="tipe" style="display: none;">
								<option value="All" <?=$_POST['tipe']=='All'?'selected':'';?>>All</option>
								<option value="company_name" <?=$_POST['tipe']=='company_name'?'selected':'';?>>Company Name</option>
								<option value="former_name" <?=$_POST['tipe']=='former_name'?'selected':'';?>>Former Name</option>
								<option value="registration_no" <?=$_POST['tipe']=='registration_no'?'selected':'';?>>Registration No</option>
							</select>

							<input class="form-control search_input_width" aria-controls="datatable-default" placeholder="Search" id="search"  name="search" value="<?=$_POST['search']?$_POST['search']:'';?>" type="search">
							<div class="search_group_button" style="display: inline;">
								<input type="submit" class="btn btn-primary" value="Search"/>
								<a href="masterclient" class="btn btn-primary">Show All Clients</a>
							</div>
							<?= form_close();?>
						</div>
					</div>
				</div>

				<div id="buttonclick" style="display:block;padding-top:10px;table-layout: fixed;width:100%">
					<table class="table table-bordered table-striped mb-none" id="datatable-default" style="width:100%">
						<thead>
							<?php if((!$Individual && $Individual == true) || (!$Individual && $Individual == null && !$Client)) {?>
								<tr style="background-color:white;">
									<th rowspan="2" class="text-center">Registration No</th>
									<th rowspan="2" class="text-center">Company</th>
									<th colspan="3" class="text-center">Contact</th>
									<th rowspan="2" class="text-center">Unpaid Invoice</th>
									<th rowspan="2" class="text-center">Unreceived Document</th>
									<th rowspan="2" class="text-center">Filing Deadline</th>
									<th rowspan="2"></th>
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

							foreach($client as $key=>$c )
							{
								$alamat = ($c["street_name"]?$c["street_name"]:'').($c["unit_no1"]?" #".$c["unit_no1"]:'').($c["unit_no2"]?"-".$c["unit_no2"]:'').($c["building_name"]?" ".$c["building_name"]:'').($c["postal_code"]?" Singapore ".$c["postal_code"]:'');
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
						<div style="width: 110px; word-break:break-all;" >
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
						<td class="text-right" ><div style="width: 110px; word-break:break-all;" ><?=number_format($c["num_document"],0)?> Doc</div></td>
					<?php } else { ?>
						<td class="text-right" ><div style="width: 110px; word-break:break-all;" ><a class="" href="<?= base_url();?>documents" style="cursor:pointer" data-toggle="tooltip" data-trigger="hover" data-original-title="Update Documents Received"><?=number_format($c["num_document"],0)?> Doc</a></div></td>
					<?php } ?>
					<td style="text-align: center">
						<?php
						if($c["175_extended_to"] != 0)
						{
							$due_date_175 = $c["175_extended_to"];
						}
						else
						{
							$due_date_175 = $c["due_date_175"];
						}

						if($c["201_extended_to"] != 0)
						{
							$due_date_201 = $c["201_extended_to"];
						}
						else
						{
							$due_date_201 = $c["due_date_201"];
						}
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
				<?php if((!$Individual && $Individual == true) || (!$Individual && $Individual == null && !$Client)) {?>
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
	var Admin = <?php echo json_encode($Admin) ?>;
	$(document).ready(function () {
		$('.create_client').click(function (e) {
			e.preventDefault();

			if(no_of_client == total_no_of_client)
			{
				bootbox.alert("Cannot exceed the total number of clients.", function() {
											                  //Example.show("Hello world callback");
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

	function delete_client(element){
		var tr = jQuery(element).parent().parent();
		var each_client_id = tr.find('input[name="each_client_id"]').val();

													/*console.log(tr);
													console.log(each_customer_id);*/
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
															        	//console.log(response);
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
											</script>
										</div>

									</div>
									<!-- end: page -->
								</section>
							</div>
						</section>
					</div>
					<!-- Modal LG -->

					<script>
						var access_right_client_module = <?php echo json_encode($client_module);?>;
	//var service_category = <?php echo json_encode($service_category);?>;

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
	</script>
	<style>
		#buttonclick .datatables-header {
			display:none;
		}
	</style>