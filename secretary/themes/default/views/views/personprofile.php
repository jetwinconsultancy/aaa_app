						<div class="header_between_all_section">
							<section class="panel">
								<header class="panel-heading">
									<?php if((!$Individual && $Individual == true) || (!$Individual && $Individual == null && !$Client)) {?>
										<div class="panel-actions" style="height:80px">
											<a class="edit_client amber" href="<?= base_url();?>personprofile/add" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Create Person" ><i class="fa fa-plus-circle  amber" style="font-size:16px;height:45px;"></i> Create Person</a>
										</div>
										<h2></h2>
									<?php } ?>
								</header>
								<div class="panel-body">
									<?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
										echo form_open_multipart("personprofile", $attrib);
										
									?>
											<div class="col-md-12">
												<?php if((!$Individual && $Individual == true) || (!$Individual && $Individual == null && !$Client)) {?>
													<div class="col-md-2" style="left: 150px; z-index: 200">
														<select class="form-control" name="type">
															<option value="all" <?=$type == "all"?'selected':'';?>>All</option>									
															<option value="individual" <?=$type == "individual"?'selected':'';?>>Individual</option>
															<option value="company" <?=$type == "company"?'selected':'';?>>Company</option>
															
														</select>
													</div>
													<div class="col-md-5 person_search_input" style="left: 150px; z-index: 200">
															<input type="text" class="form-control" id="w2-search" name="search" placeholder="Search" value="<?=isset($_POST['search'])?$_POST['search']:'';?>">
													</div>
													<div class="col-md-4 search_group_button" style="left: 150px; z-index: 200">
														<input type="submit" class="btn btn-primary" value="Search"/>
														<a href="personprofile" class="btn btn-primary">Show All Person</a>
													</div>
												<?php } ?>
												<?php if($type == "all"){ ?>
													<?php if((!$Individual && $Individual == true) || (!$Individual && $Individual == null && !$Client)) {?>
														<div class="col-md-12" id="div_person" style="margin-top:-50px;">
													<?php } else { ?>
														<div class="col-md-12" id="div_person">
													<?php } ?>
														<table class="table table-bordered table-striped table-condensed mb-none" id="datatable-default" style="width: 100%">
															<thead>
																<tr>
																	<th style="text-align: center;width: 100px">Type</th>
																	<th style="text-align: center;width: 200px">Identification No/ UEN</th>
																	<th style="text-align: center;width: 300px">Name</th>
																	<th style="text-align: center;width: 150px">Phone</th>
																	<th style="text-align: center;width: 200px">Email</th>
																</tr>
															</thead>
															<tbody>

																<!-- <a class="" href="<?=site_url('masterclient/edit/'.$c["id"]);?>" data-name="<?=$c["company_name"]?>" style="cursor:pointer"><?=ucwords(substr($c["company_name"],0,29))?><span id="f<?=$i?>" style="display:none;cursor:pointer"><?=substr($c["company_name"],29,strlen($c["company_name"]))?></span></a>
																<?php
																	if(strlen($c['company_name']) > 28)
																	{
																		echo '<a class="tonggle_readmore" data-id=f'.$i.'>...</a>';
																	}
																	
																?>  -->
																<?php
																	$i=1;
																	if($person)
																	{
																		foreach($person as $p)
																		{
																			
																			
																			echo '<tr>';
																			if($p["field_type"] == "individual")
																			{
																				echo '<td>'.ucwords($p["field_type"]).'</td>';
																				echo '<td style="word-break:break-all;">';
																				echo ''.ucwords(substr($p["decrypt_identification_no"],0,25)).'<span id="g'.$i.'" style="display:none;cursor:pointer">'.substr($p["decrypt_identification_no"],25,strlen($p["decrypt_identification_no"])).'</span>';
																					if(strlen($p['decrypt_identification_no']) > 25)
																					{
																						echo '<a class="tonggle_readmore_person" data-id="g'.$i.'">...</a>';
																					}
																				echo '</td>';
																				echo '<td style="word-break:break-all;">';
	echo "<a href='personprofile/edit/".urlencode(urlencode($p['identification_no']))."' data-name='".$p['name']."' style='cursor:pointer;'>".ucwords(substr($p['name'],0,37))."<span id='b".$i."' style='display:none;cursor:pointer'>".substr($p['name'],37,strlen($p['name']))."</span></a>";
																					if(strlen($p['name']) > 37)
																					{
																						echo '<a class="tonggle_readmore_person" data-id="b'.$i.'">...</a>';
																					}
																				if($p["non_verify"] == 1)
																				{
																					echo '<span style="color:red;"data-toggle="tooltip" data-trigger="hover"  data-original-title="Not Verify"> *</span>';
																				}
																				echo '</td>';
																				echo '<td>'.$p["local_mobile"].'</td>';
																				echo '<td>'.$p["email"].'</td>';

																				
																				$i++;
																			}
																			else
																			{
																				echo '<td>'.ucwords($p["field_type"]).'</td>';
																				echo '<td style="word-break:break-all;">';
																				echo ''.ucwords(substr($p["decrypt_register_no"],0,25)).'<span id="t'.$i.'" style="display:none;cursor:pointer">'.substr($p["decrypt_register_no"],25,strlen($p["decrypt_register_no"])).'</span>';
																					if(strlen($p['decrypt_register_no']) > 25)
																					{
																						echo '<a class="tonggle_readmore_person" data-id="t'.$i.'">...</a>';
																					}
																				echo '</td>';
																				echo '<td style="word-break:break-all;">';
																				/*echo '<a class="" href="personprofile/editCompany/'.$p["register_no"].'" data-name="'.$p["company_name"].'" style="cursor:pointer">'.ucwords(substr($p["company_name"],0,37)).'<span id="b'.$i.'" style="display:none;cursor:pointer">'.substr($p["company_name"],37,strlen($p["company_name"])).'</span></a>';*/
	echo "<a href='personprofile/editCompany/".urlencode(urlencode($p['register_no']))."' data-name='".$p['company_name']."' style='cursor:pointer;'>".ucwords(substr($p['company_name'],0,37))."<span id='b".$i."' style='display:none;cursor:pointer'>".substr($p['company_name'],37,strlen($p['company_name']))."</span></a>";
																					if(strlen($p['company_name']) > 37)
																					{
																						echo '<a class="tonggle_readmore_person" data-id="b'.$i.'">...</a>';
																					}
																				if($p["non_verify"] == 1)
																				{
																					echo '<span style="color:red;"data-toggle="tooltip" data-trigger="hover"  data-original-title="Not Verify"> *</span>';
																				}
																				echo '</td>';
																				echo '<td>'.$p["company_phone_number"].'</td>';
																				echo '<td>'.$p["company_email"].'</td>';
																				$i++;
																			}
																			
																			echo '</tr>';

																			
																		}
																	}
																?>
																<!--tr>
																	<td><span data-target="#modal_edit_person" data-toggle="modal" class="pointer amber">YYY PTE</span></td>
																	<td>0004</td>
																	<td>MMMM</td>
																	<td>+65 2312545</td>
																	<td>mmm@yyy.com</td>
																</tr-->
															</tbody>
														</table>
														<br/>
													</div>
												<?php }elseif($type == "individual"){ ?>
												<div class="col-md-12" id="div_person" style="margin-top:-50px;">
													<table class="table table-bordered table-striped table-condensed mb-none" id="datatable-default" >
														<thead>
															<tr>
																<th style="text-align: center;width: 100px">Type</th>
																<th style="text-align: center;width: 200px">Identification No/ UEN</th>
																<th style="text-align: center;width: 300px">Name</th>
																<th style="text-align: center;width: 150px">Phone</th>
																<th style="text-align: center;width: 200px">Email</th>
															</tr>
														</thead>
														<tbody>
															<?php
																$i = 1;
																foreach($person as $p)
																{
																	echo '<tr>';
																	echo '<td>'.ucwords($p["field_type"]).'</td>';
																	/*echo '<td>'.$p->identification_no.'</td>';*/
																	echo '<td style="word-break:break-all;">';
																	echo ''.ucwords(substr($p["decrypt_identification_no"],0,25)).'<span id="v'.$i.'" style="display:none;cursor:pointer">'.substr($p["decrypt_identification_no"],25,strlen($p["decrypt_identification_no"])).'</span>';
																		if(strlen($p["decrypt_identification_no"]) > 25)
																		{
																			echo '<a class="tonggle_readmore_person" data-id="v'.$i.'">...</a>';
																		}
																	echo '</td>';
																	echo '<td style="word-break:break-all;">';
echo "<a href='personprofile/edit/".urlencode(urlencode($p["identification_no"]))."' data-name='".$p["name"]."' style='cursor:pointer;'>".ucwords(substr($p["name"],0,37))."<span id='b".$i."' style='display:none;cursor:pointer'>".substr($p["name"],37,strlen($p["name"]))."</span></a>";

																		if(strlen($p["name"]) > 37)
																		{
																			echo '<a class="tonggle_readmore_person" data-id="b'.$i.'">...</a>';
																		}
																	if($p["non_verify"] == 1)
																	{
																		echo '<span style="color:red;"data-toggle="tooltip" data-trigger="hover"  data-original-title="Not Verify"> *</span>';
																	}
																	echo '</td>';
																	echo '<td>'.$p["local_mobile"].'</td>';
																	echo '<td>'.$p["email"].'</td>';
																	echo '</tr>';

																	$i++;
																}
															?>
															<!--tr>
																<td><span data-target="#modal_edit_person" data-toggle="modal" class="pointer amber">YYY PTE</span></td>
																<td>0004</td>
																<td>MMMM</td>
																<td>+65 2312545</td>
																<td>mmm@yyy.com</td>
															</tr-->
														</tbody>
													</table>
													<br/>
												</div>
												<?php }elseif($type == "company"){ ?>
												<div class="col-md-12" id="div_person" style="margin-top:-50px;">
													<table class="table table-bordered table-striped table-condensed mb-none" id="datatable-default" >
														<thead>
															<tr>
																<th style="text-align: center;width: 100px">Type</th>
																<th style="text-align: center;width: 200px">Identification No/ UEN</th>
																<th style="text-align: center;width: 300px">Name</th>
																<th style="text-align: center;width: 150px">Phone</th>
																<th style="text-align: center;width: 200px">Email</th>
															</tr>
														</thead>
														<tbody>
															<?php
																$i = 1;
																foreach($person as $c)
																{

																	echo '<tr>';
																	echo '<td>'.$c["field_type"].'</td>';
																	/*echo '<td>'.$c->register_no.'</td>';*/
																	echo '<td style="word-break:break-all;">';
																	echo ''.ucwords(substr($c["decrypt_register_no"],0,25)).'<span id="z'.$i.'" style="display:none;cursor:pointer">'.substr($c["decrypt_register_no"],25,strlen($c["decrypt_register_no"])).'</span>';
																		if(strlen($c["decrypt_register_no"]) > 25)
																		{
																			echo '<a class="tonggle_readmore_person" data-id="z'.$i.'">...</a>';
																		}
																	echo '</td>';
																	echo '<td style="word-break:break-all;">';
																	/*echo '<a class="" href="personprofile/editCompany/'.$c->register_no.'" data-name="'.$c->company_name.'" style="cursor:pointer">'.ucwords(substr($c->company_name,0,37)).'<span id="b'.$i.'" style="display:none;cursor:pointer">'.substr($c->company_name,37,strlen($c->company_name)).'</span></a>';*/
echo "<a href='personprofile/editCompany/".urlencode(urlencode($p["register_no"]))."' data-name='".$c["company_name"]."' style='cursor:pointer;'>".ucwords(substr($c["company_name"],0,37))."<span id='b".$i."' style='display:none;cursor:pointer'>".substr($c["company_name"],37,strlen($c["company_name"]))."</span></a>";
																		if(strlen($c["company_name"]) > 37)
																		{
																			echo '<a class="tonggle_readmore_person" data-id="b'.$i.'">...</a>';
																		}
																	if($p["non_verify"] == 1)
																	{
																		echo '<span style="color:red;"data-toggle="tooltip" data-trigger="hover"  data-original-title="Not Verify"> *</span>';
																	}
																	echo '</td>';
																	echo '<td>'.$c["company_phone_number"].'</td>';
																	echo '<td>'.$c["company_email"].'</td>';
																	echo '</tr>';
																	$i++;
																}
															?>
														</tbody>
													</table>
													<br/>
												</div>
												<?php } ?>
											</div>
									<?= form_close();?>
								</div>
							<!-- end: page -->
					</section>
								<div id="modal_person" class="modal fade" >
									<div class="modal-dialog modal-lg">
										<div class="modal-content">
										  <div class="modal-header">
											<header class="panel-heading">
												<h2 class="panel-title">Add Person</h2>
											</header>
										  </div>
											<div class="modal-body">
												<table class="table table-bordered table-striped table-condensed mb-none" >
													<thead>
														<tr>
															<th>Type</th>
															<td>
																<select data-plugin-selectTwo id="select_add_p" class="form-control populate">
																	<optgroup label="Type">
																		<option value="aud">Auditor</option>
																		<option value="comp">Company</option>
																		<option value="indv">Individual</option>
																	</optgroup>
																</select></td>
														</tr>
														<tr id="individual_add_p">
															<th>ID Type</th>
															<td>
																<select data-plugin-selectTwo class="form-control populate">
																	<optgroup label="Type">
																		<option value="AK">Singapore P.R.</option>
																		<option value="FIN">FIN</option>
																		<option value="Pas">Pasport/Other</option>
																	</optgroup>
																</select></td>
														</tr>
														<tr id="shareholder_edit_p">
															<th></th>
															<td><label><input type="checkbox" class="" value=""/>ShareHolder</label>&nbsp;<label><input type="checkbox" class="" value=""/>Director</label></td>
														</tr>
														<tr>
															<th>ID</th>
															<td>
																<div class="input-group"  >
																	<input type="text" class="form-control input-sm" name="username" required placeholder="Search ID">
																	<span class="input-group-btn">
																		<button class="btn btn-default" type="submit" style="height:30px;"><i class="fa fa-search"></i></button>
																	</span>
																</div>
															</td>				
														</tr>
														<tr>
															<th>Name</th>
															<td><input type="text" class="form-control input-xs" value="Dart"/></td>
														</tr>
														<tr>
															<th>Date Of Birth</th>
															<td><input type="text" class="form-control" data-plugin-datepicker data-date-format="dd/mm/yyyy" /></td>
															
														</tr>
														<tr>
															<th>Address</th>
															<td><label><input type="radio" id="local_add" name="address"/>Local</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																<label><input type="radio" id="foreign_add" name="address"/>Foreign Address</label></td>
														</tr>
														<tr id="tr_local_add">
															<th></th>
															<td>
																<div style="margin-bottom:5px;">
																	<label style="width: 25%;float:left;margin-right: 20px;">Postal Code :</label>
																	<div class="input-group" style="width: 40%;" >
																	<input type="text" class="form-control input-sm" name="username" required placeholder="Search Postal">
																	<span class="input-group-btn">
																		<button class="btn btn-default" type="submit" style="height:30px;"><i class="fa fa-search"></i></button>
																	</span>
																	</div>
																</div>
																<div style="margin-bottom:5px;">
																	<label style="width: 25%;float:left;margin-right: 20px;">Street Name :</label>
																				<input style="width: 51%;" type="text" class="form-control input-sm" name="username">
																
																</div>
																<div style="margin-bottom:5px;">
																	<label style="width: 25%;float:left;margin-right: 20px;">Building Name :</label>
																	<input style="width: 51%;" type="text" class="form-control input-sm" name="username">
																
																</div>
																<div style="margin-bottom:5px;">
																	<label style="width: 25%;float:left;margin-right: 20px;">Unit No :</label>
																				<input style="width: 10%; float: left; margin-right: 10px;" type="text" class="form-control input-sm" name="username">
																				<label style="float: left; margin-right: 10px;" >-</label>
																				<input style="width: 10%;" type="text" class="form-control input-sm" name="username" >
																
																</div>
																<div style="margin-bottom:5px;">
																	<label  id="alternate_label_edit" style="width: 25%;float:left;margin-right: 20px;"><input type="checkbox"></label>Alternate Address : 
																	<textarea style="width:50%;height:100px;display:none" id="alternate_text_edit"></textarea>
																</div>
															</td>
														</tr>
														<tr id="tr_foreign_add" class="hide">
															
															<th></th>
															<td>
																<label style="width: 25%;float:left;margin-right: 20px;"> Foreign Address :</label>
																<textarea style="width:70%;height:100px;"></textarea>
															</td>
														</tr>
														<tr>
															<th>Nationality</th>
															<td>
																<select data-plugin-selectTwo class="form-control populate">
																	<optgroup label="Nationality">
																		<option value="AK">Malaysia</option>
																		<option value="HI">Singapore</option>
																	</optgroup>
																</select></td>
														</tr>
														<tr>
															<th>Local Fixed Line</th>
															<td><input type="text" class="form-control input-xs" value=""/></td>
														</tr>
														<tr>
															<th>Local Mobile</th>
															<td><input type="text" class="form-control input-xs" value=""/></td>
														</tr>
														<tr>
															<th>Email</th>
															<td><input type="text" class="form-control input-xs" value=""/></td>
														</tr>
														<tr id="represen_add">
															<th>Representative</th>
															<td><input type="text" class="form-control input-xs" value=""/></td>
														</tr>
														
													</thead>
												</table>
												
											</div>
											<div class="modal-footer">
												<div class="row">
													<div class="col-md-12 text-right">
														<!--button class="btn btn-primary modal-confirm">Confirm</button-->
														<button class="btn btn-primary modal-dismiss">Save</button>
														<button class="btn btn-default modal-dismiss">Cancel</button>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div id="modal_edit_person" class="modal fade" >
									<div class="modal-dialog modal-lg">
										<div class="modal-content">
										  <div class="modal-header">
											<header class="panel-heading">
												<h2 class="panel-title">Edit Person</h2>
											</header>
										  </div>
											<div class="modal-body">
												<table class="table table-bordered table-striped table-condensed mb-none" >
													<thead>
														<tr>
															<th>Type</th>
															<td>
																<select data-plugin-selectTwo id="select_edit_p" class="form-control populate">
																	<optgroup label="Type">
																		<option value="aud">Auditor</option>
																		<option value="comp">Company</option>
																		<option value="indv">Individual</option>
																	</optgroup>
																</select></td>
														</tr>
														<tr id="individual_edit_p">
															<th>ID Type</th>
															<td>
																<select data-plugin-selectTwo class="form-control populate">
																	<optgroup label="Type">
																		<option value="AK">SIN Citizen</option>
																		<option value="HI">SIN PR</option>
																		<option value="FIN">FIN</option>
																		<option value="Pas">Pasport/Other</option>
																	</optgroup>
																</select></td>
														</tr>
														<tr id="company_edit_p">
															<th>UEN</th>
															<td><input type="text" class="form-control"></td>
														</tr>
														<tr id="shareholder_edit_p">
															<th></th>
															<td><label><input type="checkbox" class="" value=""/>ShareHolder</label>&nbsp;<label><input type="checkbox" class="" value=""/>Director</label></td>
														</tr>
														<tr>
															<th>ID</th>
															<td>
																<div class="input-group"  >
																	<input type="text" class="form-control input-sm" name="username" required placeholder="Search ID">
																	<span class="input-group-btn">
																		<button class="btn btn-default" type="submit" style="height:30px;"><i class="fa fa-search"></i></button>
																	</span>
																</div>
															</td>				
														</tr>
														<tr>
															<th>Name</th>
															<td><input type="text" class="form-control input-xs" value="Dart"/></td>
														</tr>
														<tr>
															<th>Date Of Birth</th>
															<td><input type="text" class="form-control" data-plugin-datepicker data-date-format="dd/mm/yyyy" /></td>
															
														</tr><tr>
															<th>Address</th>
															<td><label><input type="radio" id="local_edit" name="address"/>Local</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																<label><input type="radio" id="foreign_edit" name="address"/>Foreign Address</label></td>
														</tr>
														<tr id="tr_local_edit">
															<th></th>
															<td>
																<label style="width: 25%;float:left;margin-right: 20px;">Postal Code :</label>
																<div class="input-group" style="width: 40%;" >
																<input type="text" class="form-control input-sm" name="username" required placeholder="Search Postal">
																<span class="input-group-btn">
																	<button class="btn btn-default" type="submit" style="height:30px;"><i class="fa fa-search"></i></button>
																</span>
																</div>
																<label style="width: 25%;float:left;margin-right: 20px;">Street Name :</label>
																				<input style="width: 51%;" type="text" class="form-control input-sm" name="username">
																<label style="width: 25%;float:left;margin-right: 20px;">Building Name :</label>
																<input style="width: 51%;" type="text" class="form-control input-sm" name="username">
																<label style="width: 25%;float:left;margin-right: 20px;">Unit No :</label>
																				<input style="width: 10%; float: left; margin-right: 10px;" type="text" class="form-control input-sm" name="username">
																				<label style="float: left; margin-right: 10px;" >-</label>
																				<input style="width: 10%;" type="text" class="form-control input-sm" name="username" >
																<label  id="alternate_label_edit" style="width: 25%;float:left;margin-right: 20px;"><input type="checkbox">Alternate Address : </label>
																<textarea style="width:50%;height:100px;display:none" id="alternate_text_edit"></textarea>
															</td>
														</tr>
														<tr id="tr_foreign_edit" style="display:none;">
															
															<td></td>
															<td>
																<label style="width: 25%;float:left;margin-right: 20px;"> Foreign Address :</label>
																<textarea style="width:50%;height:100px;"></textarea>
															</td>
														</tr>
														<tr>
															<th>Nationality</th>
															<td>
																<select data-plugin-selectTwo class="form-control populate">
																	<optgroup label="Nationality">
																		<option value="AK">Malaysia</option>
																		<option value="HI">Singapore</option>
																	</optgroup>
																</select></td>
														</tr>
														<tr>
															<th>Local Fixed Line</th>
															<td><input type="text" class="form-control input-xs" value=""/></td>
														</tr>
														<tr>
															<th>Local Mobile</th>
															<td><input type="text" class="form-control input-xs" value=""/></td>
														</tr>
														<tr>
															<th>Email</th>
															<td><input type="text" class="form-control input-xs" value=""/></td>
														</tr>
														<tr id="represen_edit">
															<th>Representative</th>
															<td><input type="text" class="form-control input-xs" value=""/></td>
														</tr>
														
													</thead>
												</table>
												
											</div>
											<div class="modal-footer">
												<div class="row">
													<div class="col-md-12 text-right">
														<!--button class="btn btn-primary modal-confirm">Confirm</button-->
														<button class="btn btn-primary modal-dismiss">Save</button>
														<button class="btn btn-default modal-dismiss">Cancel</button>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								</div>
			
<style>
	/*#div_person .datatables-header {
		display:none;
	}*/
	#div_person .dataTables_filter {
		display:none;
	}

	#div_person .datatables-header {
		
	    width: 350px;
	    position: relative;
	    /*left: 15px;*/
	    top: 15px;
	    /*bottom: -50px;*/
	    z-index: 100;

	}
</style>
<!-- <style>
	.tonggle_readmore_person {
		font-size:30px;
		line-height:12px;
	}
</style> -->
<script>
	var access_right_person_module = <?php echo json_encode($person_module);?>;

	if(access_right_person_module == "read")
	{
		$('.edit_client').hide();
	}

	$(document).on('click','.tonggle_readmore_person',function (event){
		event.preventDefault();
		$id = $(this).data('id');
		

		if($("#"+$id).css('display') == 'none')
		{
			$("#"+$id).show();
		}
		else
		{
			$("#"+$id).hide();
		}
		//console.log($("#"+$id).show());
	});
</script>
<script>
	$("#header_our_firm").removeClass("header_disabled");
	$("#header_manage_user").removeClass("header_disabled");
	$("#header_access_right").removeClass("header_disabled");
	$("#header_user_profile").removeClass("header_disabled");
	$("#header_setting").removeClass("header_disabled");
	$("#header_dashboard").removeClass("header_disabled");
	$("#header_client").removeClass("header_disabled");
	$("#header_person").addClass("header_disabled");
	$("#header_document").removeClass("header_disabled");
	$("#header_report").removeClass("header_disabled");
	$("#header_billings").removeClass("header_disabled");
	
			$("#individual_edit_p").hide();
			$("#company_edit_p").hide();
			$("#shareholder_edit_p").hide();
			 $("#represen_edit").hide();
			$("#individual_add_p").hide();
			$("#company_add_p").hide();
			 $("#represen_add").hide();
			$("#shareholder_add_p").hide();
	$("#select_edit_p").on('change',function(){
			$("#individual_edit_p").hide();
			$("#company_edit_p").hide();
			$("#shareholder_edit_p").hide();
			 $("#represen_edit").hide();
		if ($(this).val() == 'comp'){
			$("#company_edit_p").show();
			 $("#represen_edit").show();
		}else if ($(this).val() == 'indv'){
			$("#individual_edit_p").show();
			$("#shareholder_edit_p").show();
		}
	});
	$("#select_add_p").on('change',function(){
			$("#individual_add_p").hide();
			$("#company_add_p").hide();
			 $("#represen_add").hide();
			$("#shareholder_add_p").hide();
		if ($(this).val() == 'comp'){
			$("#company_add_p").show();
			 $("#represen_add").show();
		}else if ($(this).val() == 'indv'){
			$("#individual_edit_p").show();
			$("#shareholder_edit_p").show();
		}
	});
	$("#local_add").click(function() {
		$("#tr_foreign_add").hide();
		$("#tr_local_add").show();
	});
	$("#foreign_add").click(function() {
		$("#tr_foreign_add").show();
		$("#tr_local_add").hide();
	});
	$("#local_edit").click(function() {
		$("#tr_foreign_edit").hide();
		$("#tr_local_edit").show();
	});
	$("#foreign_edit").click(function() {
		$("#tr_foreign_edit").show();
		$("#tr_local_edit").hide();
	});
	$("#alternate_label_edit").click(function() {
		$("#alternate_text_edit").toggle();
	});
	$("#alternate_label_add").click(function() {
		$("#alternate_text_add").toggle();
	});
</script>

