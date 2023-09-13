		<?php
			$now = getDate();
			// $this->sma->print_arrays($client);
				$this->session->set_userdata('unique_code', $client->unique_code);
			if ($this->session->userdata('unique_code') && $this->session->userdata('unique_code') != '')
			{
				$unique_code =$this->session->userdata('unique_code');
			} else {
				$unique_code = $this->session->userdata('username').'_'.$now[0];
				$this->session->set_userdata('unique_code', $client->unique_code);
			}
			$ndate = $now['mday']."/".$this->sma->addzero($now['mon'],2)."/".$now['year'];
			// $this->sma->print_arrays($ndate);
			// echo $ndate;
			$type_of_doc[""] = [];
			foreach ($typeofdoc as $cs) {
				$type_of_doc[$cs->id] = $cs->typeofdoc;
			}
			$doc_category[""] = [];
			foreach ($doccategory as $cs) {
				$doc_category[$cs->id] = $cs->doccategory;
			}
			$svc[""] = [];
			foreach ($service as $cs) {
				$svc[$cs->id] = $cs->service_name;
			}
			
		?>
                
		<section class="panel">
			<div class="panel-body">
				<div class="col-md-12">
					<div id="modalLG" class="modal-block modal-block-lg" style="max-width: 100%	;margin: 0px auto;">
						<section class="panel" style="margin-bottom: 0px;">
							<div class="panel-body">
								<div class="modal-wrapper">
									<div class="modal-text">
										<div class="tabs">
											
											<ul class="nav nav-tabs nav-justify">
												<li class="active check_stat" id="#li-account" data-information="account">
													<a href="#w2-account" data-toggle="tab" class="text-center">
														<span class="badge hidden-xs">1</span>
														Company Info
													</a>
												</li>
												<li class="check_stat hidden">
													<a href="#w2-director" data-toggle="tab" class="text-center">
														<span class="badge hidden-xs">2</span>
														Director
													</a>
												</li>
												<li class="check_stat" id="#li-officer" data-information="officer">
													<a href="#w2-officer" data-toggle="tab" class="text-center ">
														<span class="badge hidden-xs">2</span>
														Officers
													</a>
												</li>
												<li class="check_stat" id="#li-capital" data-information="capital">
													<a href="#w2-capital" data-toggle="tab" class="text-center">
														<span class="badge hidden-xs">3</span>
														Members
													</a>
												</li>
												<li class="check_stat" id="#li-charges" data-information="charges">
													<a href="#w2-charges" data-toggle="tab" class="text-center ">
														<span class="badge hidden-xs">4</span>
														Charges
													</a>
												</li>
												<li class="check_stat" id="#li-other" data-information="other">
													<a href="#w2-other" data-toggle="tab" class="text-center">
														<span class="badge hidden-xs">5</span>
														Others
													</a>
												</li>
												<li class="check_stat" id="#li-setup" data-information="setup">
													<a href="#w2-setup" data-toggle="tab" class="text-center">
														<span class="badge hidden-xs">6</span>
														Setup
													</a>
												</li>
											</ul>
											
															
												<div class="tab-content">
													<div id="w2-account" class="tab-pane active">
														<?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form', 'id' => 'myform', 'name' => 'myform');
															echo form_open_multipart("masterclient/save", $attrib);
															
														?>
															<div class="hidden"><input type="text" class="form-control" name="unique_code" value="<?=$unique_code?>"/></div>
														<?php	
															if ($client->file_setup_for == '')
															{
														?>
														<div class="form-group">
														
															<label class="col-xs-4 control-label" for="w2-username">File Setup For</label>
															<div class="col-xs-6">
															<select class="col-xs-8 input-sm" style="text-align:right;" name="Slct_file_setup_for">
																<option value="incorporation_of_company">Incorporation of company</option>
																<option value="transferred_from_other_service_provider">Transferred from other service provider</option>
															</select>
															</div>
															<div class="col-xs-2">
																	<input type="file" id="" class="form-control input-sm" name="file_setup_for"/>
															</div>
														</div>
														<?php
															} else {
														?>
															<div class="form-group">
														
															<label class="col-xs-4 control-label" for="w2-username">File Setup For
																<strong><?=$client->Slct_file_setup_for?></strong></label>
															<label class="col-xs-4 control-label" for="w2-username">File :
																<a href="<?=site_url('uploads/'.$client->file_setup_for);?>"><?=$client->file_setup_for?></a></label>
															</div>
														<?php
															}
														?>
														<div class="form-group">
															<label class="col-sm-4 control-label" for="w2-username">Client Code</label>
															<div class="col-sm-4">
																<input type="text" class="form-control input-sm" maxlength="20"  name="clientcode" value="<?=$client->clientcode?>" >
															</div>
															<label class="col-sm-2 control-label" for="w2-username">Status</label>
															<select class="col-sm-2 input-sm" style="text-align:right;" name="status">
																<option value="1" <?=$client->status==1?'selected':'';?>>Live</option>
																<option value="2" <?=$client->status==2?'selected':'';?>>Struck-Off</option>
																<option value="3" <?=$client->status==3?'selected':'';?>>Liquidated</option>
															</select>
														</div>
														<div class="form-group">
															<label class="col-sm-4 control-label" for="w2-username">UEN</label>
															<div class="col-sm-4">
																<input type="text" class="form-control input-sm" maxlength="10"  name="uen" value="<?=$client->uen?>" required >
															</div>
														</div>
														<div class="form-group" style="display:none;" id="file_add_client">
															<label class="col-sm-4 control-label" for="w2-username">File Setup 1</label>
															<div class="col-sm-6">
																	<input type="file" id="" class="form-control" name="file_setup1"/>
															</div>
															<label class="col-sm-4 control-label" for="w2-username">File Setup 2</label>
															<div class="col-sm-6">
																	<input type="file" id="" class="form-control" name="file_setup2" />
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-4 control-label" for="w2-username">Date Incorporation</label>
															<div class="col-sm-8">
																<div class="input-group mb-md">
																	<span class="input-group-addon">
																		<i class="fa fa-calendar"></i>
																	</span>
																	<input type="text" id="date_todolist" class="form-control" name="date_incorporation" style="" data-plugin-datepicker data-date-format="dd/mm/yyyy" value="<?=$this->sma->fed($client->date_incorporation)?>" data-date-start-date="0d"/>
																</div>
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-4 control-label" for="w2-username">Client Name</label>
															<div class="col-sm-8">
																<input type="text" class="form-control input-sm" id="edit_company_name" name="client_name"  value="<?=$client->client_name?>">
															</div>
														</div>
														<div class="form-group">
															<label class="col-xs-4 control-label" for="w2-username">Use Our Registered Address</label>
															<div class="col-xs-8">
																<input type="checkbox" class="" id="" name="use_registered_address" value="<?=$client->use_registered_address?>" >
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-4 control-label" for="w2-username">Registered Address</label>
															<div class="col-sm-8">
																<label style="width: 13%;float:left;margin-right: 20px;">Postal Code :</label>
																<input style="width: 10%; float: left; margin-right: 10px;" type="text" class="form-control input-sm" id="" name="postal_code"  value="<?=$client->postal_code?>">
																<div class="input-group" style="width: 40%;" >
																<input type="text" class="form-control input-sm" id="" name="city"    value="<?=$client->city?>">
																</div>
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-4 control-label" for="w2-username"></label>
															<div class="col-sm-8">
																<label style="width: 13%;float:left;margin-right: 20px;">Street Name :</label>
																<input style="width: 51%;" type="text" class="form-control input-sm" id="" name="streetname" value="<?=$client->streetname?>">
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-4 control-label" for="w2-username"></label>
															<div class="col-sm-8">
																<label style="width: 13%;float:left;margin-right: 20px;">Building Name :</label>
																<input style="width: 51%;" type="text" class="form-control input-sm" id="" name="buildingname" value="<?=$client->buildingname?>">
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-4 control-label" for="w2-username"></label>
															<div class="col-sm-8">
																<label style="width: 13%;float:left;margin-right: 20px;">Unit No :</label>
																<input style="width: 5%; float: left; margin-right: 10px;" type="text" class="form-control input-sm" id="" name="unitno" value="<?=$client->unitno?>">
																<label style="float: left; margin-right: 10px;" >-</label>
																<input style="width: 5%;" type="text" class="form-control input-sm" id="" name="unitno1" value="<?=$client->unitno1?>" >
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-4 control-label" for="w2-username">Principle Activity 1</label>
															<div class="col-sm-8">
																<input type="text" class="form-control input-sm" id="" name="activity1" value="<?=$client->activity1?>" >
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-4 control-label" for="w2-username">Principle Activity 2</label>
															<div class="col-sm-8">
																<input type="text" class="form-control input-sm" id="" name="activity2" value="<?=$client->activity2?>" >
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-4 control-label" for="w2-username">Contact Person</label>
															<div class="col-sm-8">
																<input type="text" class="form-control input-sm" id="" name="cp" value="<?=$client->cp?>" >
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-4 control-label" for="w2-username">Phone</label>
															<div class="col-sm-8">
																<div class="input-group">
																	<input id="phone" maxlength="20" placeholder="(123) 123-1234" name="phone" class="form-control" value="<?=$client->phone?>"  >
																</div>
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-4 control-label" for="w2-username">E-Mail</label>
															<div class="col-sm-8">
																	<input type="email" name="email" class="form-control" placeholder="eg.: email@email.com" value="<?=$client->email?>" />
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-4 control-label" for="w2-username">Fax</label>
															<div class="col-sm-8">
																<div class="input-group">
																<input type="text" class="form-control" id="" name="fax" value="<?=$client->fax?>" >
																</div>
															</div>
														</div>
														<div class="form-group hidden">
															<label class="col-sm-4 control-label" for="w2-username">Chairman</label>
															<div class="col-sm-8">
																<input type="text" class="form-control input-sm" id="" name="chairman" value="<?=$client->chairman?>"  >
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-4 control-label" for="w2-username">Listed Company</label>
															<div class="col-sm-8">
																<input type="checkbox" class="" id="" name="listedcompany" <?=$client->listedcompany?'checked':'';?> />
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-4 control-label" for="w2-username">Former Name</label>
															<div class="col-sm-8">
																<input type="text" class="form-control input-sm" id="" name="formername" value="<?=$client->formername?>" >
															</div>
														</div>
													
														<?= form_close(); ?>
													</div>
													<div id="w2-officer" class="tab-pane">
														<table class="table table-bordered table-striped table-condensed mb-none" >
															<thead>
																<tr>
																	<th>No</th>
																	<th>Officer and Position</th>
																	<th>
																		<a href="#modal_officer" id="adds_officer" class="fa fa-plus modal-sizes amber" data-toggle="tooltip" data-trigger="hover" style="color:black;margin:0px;float:left;" data-original-title="Add Officer" ></a>
																	</th>
																</tr>
															</thead>
															<tbody id="body_officer">
															<?php
																$position[] = 'Director';
																$position[] = 'CEO';
																$position[] = 'Manager';
																$position[] = 'Secretary';
																$position[] = 'Auditor';
																$position[] = 'Managing Director';
																$position[] = 'Alternate Director';
																$oppicer = [];
																foreach($officer as $of){
																	$oppicer[$of->id] = $of->nama;
																}
																$i = 0;
																foreach($officer as $of)
																{
																	if($of->nama != "")
																	{
															?>
															<tr>
																<td>
																<label class="ads"></label></td><td style="padding-top:15px;"><h6><?=$of->nama?></h6>
																<table class="table table-bordered table-striped table-condensed mb-none" >
																	<thead>
																		<tr>
																			<th>Position</th>
																			<th style="width:2%;">Date of Appointment</th>
																			<th style="width:2%;">Date of Cesasion</th>
																		</tr>
																	</thead>
																	<tbody id="body_content">
																		<?php
																			foreach($position as $p)
																			{
																				
																				echo '<tr><td><label><input type="checkbox" ';
																				if ($of->position == $p)
																				{
																					echo ' checked=checked';
																				}
																				echo ' name="'.$p.'" value="'.$p.'"';
																				if ($p == 'Alternate Director')
																				{
																					echo ' class="alternatedir" data-id="'.$i.'">'.$p.'</label>';
																					if ($oppicer != '')
																					{
																						echo '<div id="alternatedir'.$i.'" style="display:none">
																						<select class="form-control input-sm">';
																						foreach($oppicer as $key=>$value)
																						{
																						echo '<option value="'.$key.'">'.$value.'</option>';
																						}
																						echo '</select>
																						</div>';
																					}
																				} else {
																					
																					echo '>'.$p.'</label>';
																				}
																				echo '</td>
																				<td><input type="text" data-plugin-datepicker data-date-format="dd/mm/yyyy" data-date-start-date="0d"';
																				if ($of->position == $p)
																				{
																					echo 'value="'.$of->date_of_appointment.'"';
																				}
																				echo '/></td>
																				<td><input type="text" data-plugin-datepicker data-date-format="dd/mm/yyyy" data-date-start-date="0d" ';
																				if ($of->position == $p)
																				{
																					echo 'value="'.$of->date_of_cessation.'"';
																				}
																				echo '/></td> </tr>';
																			}
																		?>
																		
																		
																	</tbody>
																</table>
																</td>
																<td>
																<a ><i class="fa fa-timer"></i></a>
																<a  class="delete_officer" data-id="<?=$of->id?>"><i class="fa fa-trash" style="font-size:16px;"></i></a>
																</td>
																</tr>
															<?php
																	}
																}?>
															</tbody>
														</table>
														
													</div>
													<div id="w2-capital" class="tab-pane">
														<?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
															echo form_open_multipart("masterclient/save_capital", $attrib);
															
															// print_r($sales);
															$cr[""] = [];
															foreach ($currency as $cs) {
																$cr[$cs->id] = $cs->currency;
															}
															$bl[""] = [];
															foreach ($sharetype as $share) {
																$bl[$share->id] = $share->sharetype;
															}
														?>
															<div class="hidden"><input type="text" class="form-control" name="unique_code" value="<?=$unique_code?>"/></div>
														<h3>A. Issued Share Capital</h3>
															<table class="table table-bordered table-striped table-condensed mb-none" >
																<thead>
																	<tr>
																		<th>No</th>
																		<th>Amount</th>
																		<th>Number of Shares</th>
																		<th>Currency</th>
																		<th>Share Type</th>
																		<th><a id="add_issued_share_capital"><i class="fa fa-plus-circle"></i></a></th>
																	</tr>
																</thead>
																<tbody id="body_issued_share_capital">
																		<?php
																			$i = 1;
																			foreach ($issued_sharetype as $iss)
																			{
																		?>
																	<tr>
																		<td><?=$i?></td>
																		<td><input type="text" class="form-control number text-right" name="issued_amount_member[]" value="<?=$iss->issued_amount_member?>"/></td>
																		<td><input type="text" class="form-control number text-right" name="no_of_share_member[]" value="<?=$iss->no_of_share_member?>"/></td>
																		<td><?php
																			echo form_dropdown('issued_currency_member[]', $cr, $iss->issued_currency_member, 'id="currency"  class="form-control" style="width:100%;"');
																		
																			?>
																		</td>
																		<td>
																		<?php
																			// echo form_dropdown('sharetype_member[]', $bl, '', 'id="slsales"  class="form-control input-sm  input-sm select" style="width:100%;" ');
																			echo form_dropdown('issued_sharetype_member[]', $bl, $iss->issued_sharetype_member, 'id="slsales"  class="form-control" style="width:100%;"');
																		?>
																			
																		</td>
																		<td><a ><i class="fa fa-times hapus_baris"></i></a></td>
																	</tr>
																			<?php 
																				$i++;
																			} 
																		for($j=$i;$j<4;$j++)
																		{
																			?>
																	<tr>
																		<td><?=$j?></td>
																		<td><input type="text" class="form-control number text-right" name="issued_amount_member[]" value=""/></td>
																		<td><input type="text" class="form-control number text-right" name="no_of_share_member[]" value=""/></td>
																		<td><?php
																			echo form_dropdown('issued_currency_member[]', $cr, '', 'id="currency"  class="form-control" style="width:100%;"');
																		
																			?>
																		</td>
																		<td>
																		<?php
																			// echo form_dropdown('sharetype_member[]', $bl, '', 'id="slsales"  class="form-control input-sm  input-sm select" style="width:100%;" ');
																			echo form_dropdown('issued_sharetype_member[]', $bl, '', 'id="slsales"  class="form-control" style="width:100%;"');
																		?>
																			
																		</td>
																		<td><a ><i class="fa fa-times hapus_baris"></i></a></td>
																	</tr>
																	<?php
																			}
																	?>
																</tbody>
															</table>
															<a href="<?= base_url();?>masterclient/allotment/<?=$unique_code?>" class="btn btn-default">Allotment</a>
															<a href="<?= base_url();?>masterclient/buyback/<?=$unique_code?>" class="btn btn-default">Buyback</a>
															<a href="<?= base_url();?>masterclient/transfer/<?=$unique_code?>" class="btn btn-default">Transfer</a>
															<h3 style="margin-top: 10px;">B. Paid-Up Share Capital</h3>
															<table class="table table-bordered table-striped table-condensed mb-none" >
																<thead>
																	<tr>
																		<th>No</th>
																		<th>Amount</th>
																		<th>Number of Shares</th>
																		<th>Currency</th>
																		<th>Share Type</th>
																		<th><a  id="paid_issued_share_capital"><i class="fa fa-plus-circle"></i></a></th>
																	</tr>
																</thead>
																<tbody id="body_paid_issued_share_capital">
																		<?php
																			$i = 1;
																			foreach ($paid_share as $ps)
																			{
																		?>
																	<tr>
																		<td><?=$i?></td>
																		<td><input type="text" class="form-control number text-right" value="<?=$ps->paid_amount_member?>" name="paid_amount_member[]"/></td>
																		<td><input type="text" class="form-control number text-right" value="<?=$ps->paid_no_of_share_member?>" name="paid_no_of_share_member[]"/></td>
																		<td><?php echo form_dropdown('paid_currency_member[]', $cr, $ps->paid_currency_member, 'id="currency"  class="form-control" style="width:100%;"');
																		?>
																		</td>
																		<td>
																			<?php echo form_dropdown('paid_sharetype_member[]', $bl, $ps->paid_sharetype_member, 'id="slsales"  class="form-control" style="width:100%;"'); ?>
																		</td>
																		<td><a ><i class="fa fa-times hapus_baris"></i></a></td>
																	</tr>
																		<?php 
																			$i++;
																		} 
																		for($j=$i;$j<4;$j++)
																		{
																		?>
																		<tr>
																			<td><?=$j?></td>
																			<td><input type="text" class="form-control number text-right" value="" name="paid_amount_member[]"/></td>
																			<td><input type="text" class="form-control number text-right" value="" name="paid_no_of_share_member[]"/></td>
																			<td><?php echo form_dropdown('paid_currency_member[]', $cr, '', 'id="currency"  class="form-control" style="width:100%;"');
																			?>
																			</td>
																			<td>
																				<?php echo form_dropdown('paid_sharetype_member[]', $bl, '', 'id="slsales"  class="form-control" style="width:100%;"'); ?>
																			</td>
																			<td><a ><i class="fa fa-times hapus_baris"></i></a></td>
																		</tr>
																	<?php
																			}
																	?>
																</tbody>
															</table>
															<h3 style="margin-top: 15px;">C. Members</h3>
															
															<table class="table table-bordered table-striped table-condensed mb-none" >
																<thead>
																	<tr>
																		<th rowspan=2 align=middle>No</th>
																		<th>Name</th>
																		<th>Share Type</th>
																		<th>Shares</th>
																		<th>No of Share Paid</th>
																		<th>Certificate</th>
																		<!--th><a id="member_issued_share_capital"><i class="fa fa-plus-circle"></i></a></th-->
																	</tr>
																	<tr>
																		<th>ID</th>
																		<th>Currency</th>
																		<th>Amount Share</th>
																		<th>Amount Paid</th>
																		<th>SettlePayment</th>
																		<th><a  id="add_member_capital"><i class="fa fa-plus-circle"></i></a></th>
																	</tr>
																</thead>
																<tbody id="body_members_capital">
																
																		<?php
																			$i = 1;
																			foreach ($member_capital as $ms)
																			{
																		?>
																	<tr>
																		<td rowspan=2><?=$i?></td>
																		<td><input type="text"  name="nama_member_capital[]" class="form-control input-xs" value="<?=$ms->nama_member_capital?>"/></td>
																		<td>
																			<?php echo form_dropdown('sharetype_member[]', $bl, $ms->sharetype_member, 'id="slsales"  class="form-control" style="width:100%;"'); ?>
																		</td>
																		<td><input type="text" name="shares_member_capital[]" class="form-control number text-right" value="<?=$ms->shares_member_capital?>"/></td>
																		<td><input type="text" name="no_share_paid_member_capital[]" class="form-control number text-right" value="<?=$ms->no_share_paid_member_capital?>"/></td>
																		<td><?php
																			if ($ms->certificate != ''){
																		?>
																		<a href="./uploads/<?=$ms->certificate?>"><?=$ms->certificate?></a>
																		<?php
																			} else {
																		?>
																		<input type="file" name="upload_certificate<?=$i?>" class="form-control number text-right" value=""/>
																		<?php
																			}
																		?></td>
																		<td rowspan=2><a ><i class="fa fa-times hapus_duabaris"></i></a></td>
																	</tr>
																	<tr>
																		<td><input type="text" name="gid_member_capital[]"  class="form-control" value=""/></td>
																		<td><?php echo form_dropdown('currency_member_capital[]', $cr, $ms->currency_member_capital, 'id="currency"  class="form-control" style="width:100%;"');
																		?>
																		</td>
																		<td><input type="text" name="amount_share_member_capital[]" class="form-control number text-right" value="<?=$ms->amount_share_member_capital?>"/></td>
																		<td><input type="text" name="amount_share_paid_member_capital[]" class="form-control number text-right" value="<?=$ms->amount_share_paid_member_capital?>"/></td>
																		<td><a>SettlePayment</a></td>
																	</tr>
																		<?php 
																			$i++;
																		} 
																		for($j=$i;$j<4;$j++)
																		{
																		?>
																	<tr>
																		<td rowspan=2><?=$j?></td>
																		<td><input type="text"  name="nama_member_capital[]" class="form-control input-xs" value=""/></td>
																		<td>
																			<?php echo form_dropdown('sharetype_member[]', $bl, '', 'id="slsales"  class="form-control" style="width:100%;"'); ?>
																		</td>
																		<td><input type="text" name="shares_member_capital[]" class="form-control number text-right" value=""/></td>
																		<td><input type="text" name="no_share_paid_member_capital[]" class="form-control number text-right" value=""/></td>
																		<td><input type="file" name="upload_certificate[]" class="form-control number text-right" value=""/></td>
																		<!--td>
																			<a href="#"><i class="fa fa-pencil"></i></a>
																			<a href="#modal_transfer" class="modal-sizes"><i class="fa fa-share-alt"></i></a>
																		</td-->
																		<td rowspan=2><a ><i class="fa fa-times hapus_baris"></i></a></td>
																	</tr>
																	<tr>
																		<td><input type="text" name="gid_member_capital[]"  class="form-control" value=""/></td>
																		<td><?php echo form_dropdown('currency_member_capital[]', $cr, '', 'id="currency"  class="form-control" style="width:100%;"');
																		?>
																		</td>
																		<td><input type="text" name="amount_share_member_capital[]" class="form-control number text-right" value=""/></td>
																		<td><input type="text" name="amount_share_paid_member_capital[]"  class="form-control number text-right" value=""/></td>
																		<td><a>SettlePayment</a></td>
																	</tr>
																	<?php
																			}
																		// echo "<script>";
																		// echo "	$byk_member_capital =".$i.";";
																		// echo "</script>";
																	?>
																	
																</tbody>
															</table>
															
														<?= form_close(); ?>
													</div>
													<div id="w2-charges" class="tab-pane">
														<?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
															echo form_open_multipart("masterclient/save_charges", $attrib);
															
														?>
															<div class="hidden"><input type="text" class="form-control" name="unique_code" value="<?=$unique_code?>"/></div>
														<h3>Charges</h3>
														<table class="table table-bordered table-striped table-condensed mb-none" >
														<thead>
															<tr>
																<th rowspan =2 valign=middle>Chargee</th>
																<th rowspan =2 valign=middle>Nature of Charges</th>
																<th>Date Registration</th>
																<th>Chargee No.</th>
																<th rowspan=2>Currency</th>
																<th>Amount</th>
																<th rowspan =2 style="font-size:25px;"><span id="chargee_Add"><i class="fa fa-plus-circle"></i></span></th>
															</tr>
															<tr>
																<th>Date Satisfied</th>
																<th>Satisfactory No.</th>
																<th>Secured</th>
															</tr>
														</thead>
														<tbody id="body_chargee">
															<?php
																$i = 1;
																foreach ($chargee as $ch)
																{
																	// print_r($service);
															?>
															<tr>
																<td>
																<?php echo form_dropdown('chargee_name[]', $svc, $ch->chargee_name, 'id="currency"  class="form-control" style="width:100%;"');?></td>
																<td><input type="text" name="chargee_nature_of[]" class="form-control" value="<?=$ch->chargee_nature_of?>"/></td>
																<td><input type="text" name="chargee_date_reg[]" id=chargee_date_reg1" class="form-control" data-plugin-datepicker data-date-format="dd/mm/yyyy" value="<?=$ch->chargee_date_reg?>" /></td>
																<td><input type="text" name="chargee_no[]" class="form-control" value="<?=$ch->chargee_no?>"/></td>
																<td>
																	<?php echo form_dropdown('chargee_currency[]', $cr, $ch->chargee_currency, 'id="currency"  class="form-control" style="width:100%;"');
																		?>
																</td>
																<td><input type="text" name="chargee_amount[]" class="form-control number" value="<?=$ch->chargee_amount?>"/></td>
																
															</tr>
															<tr>
																<td></td>
																<td></td>
																<td><input type="text" name="chargee_date_satisfied[]" class="form-control " data-plugin-datepicker data-date-format="dd/mm/yyyy" value="<?=$ch->chargee_date_satisfied?>"/></td>
																<td><input type="text" name="chargee_satisfied_no[]" class="form-control" value="<?=$ch->chargee_satisfied_no?>"/></td>
																<td></td>
																<td></td>
																
															</tr>															
															<?php
																}
															?>

														</tbody>
														</table>
														
														<?= form_close(); ?>
													</div>
													<div id="w2-other" class="tab-pane">
														
														<?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
															echo form_open_multipart("masterclient/save_other", $attrib);
														?>
															<div class="hidden"><input type="text" class="form-control" name="unique_code" value="<?=$unique_code?>"/></div>
														<div class="form-group">
															<label class="col-sm-4 control-label" for="w2-email">Type Of Doc</label>
															<div class="col-sm-8">
																<?php echo form_dropdown('typeofdoc', $type_of_doc, '', 'id="currency"  class="form-control" style="width:100%;"');?>
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-4 control-label" for="w2-email">Category</label>
															<div class="col-sm-8">
																<?php echo form_dropdown('doccategory', $doc_category, '', 'id="currency"  class="form-control" style="width:100%;"');
																		?>
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-4 control-label" for="w2-email">File</label>
															<div class="col-sm-8">
																<input type="file" name="upload_file_others" class="form-control number text-right" value=""/>
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-4 control-label" for="w2-email">Remarks</label>
															<div class="col-sm-8">
																<textarea class="form-control" style="height:180px;" name="others_remarks">
																</textarea>
															</div>
														</div>
													
														<?= form_close(); ?>
														<table class="table table-bordered table-striped table-condensed" >
															<tr>
																<th>No</th>
																<th>Type Of Doc</th>
																<th>Doc Category</th>
																<th>Remarks</th>
																<th>Files</th>
															</tr>
															<?php
																$i = 1;
																// print_r($type_of_doc);
																foreach($client_others as $tod)
																{
															?>
															<tr>
																<td><?=$i?></td>
																<td><?=$type_of_doc[$tod->type_of_doc]?></td>
																<td><?=$doc_category[$tod->others_category]?></td>
																<td><?=$tod->others_remarks?></td>
																<td><a href='./uploads/<?=$tod->files?>'><?=$tod->files?></a></td>
															</tr>
															<?php 
																	$i++;
																}
															?>
														</table>
													</div>
													<div id="w2-setup" class="tab-pane">
														<?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
															echo form_open_multipart("masterclient/save_setup", $attrib);
															// print_r($oppicer);
														?>
															<div class="hidden"><input type="text" class="form-control" name="unique_code" value="<?=$unique_code?>"/></div>
															<h2>Signing Information</h2>
														<div class="form-group">
															<label class="col-sm-4 control-label" for="w2-email">Chairman</label>
															<div class="col-sm-8">
																<?php echo form_dropdown('setup_chairman', $oppicer, $setup_d->setup_chairman, 'id="currency" class="form-control" style="width:100%;"');?>
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-4 control-label" for="w2-email">Director Signature 1</label>
															<div class="col-sm-8">
																<?php echo form_dropdown('setup_director_signature1', $oppicer, $setup_d->setup_director_signature1, 'id="currency" class="form-control" style="width:100%;"');?>
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-4 control-label" for="w2-email">Director Signature 2</label>
															<div class="col-sm-8">
																<?php echo form_dropdown('setup_director_signature2', $oppicer, $setup_d->setup_director_signature2, 'id="currency" class="form-control" style="width:100%;"');?>
															</div>
														</div>
														<h2>Billing Information</h2>
														<table class="table table-bordered">
															<tr>
																<th class="text-center">No</th>
																<th class="text-center">Service</th>
																<th class="text-center">Amount</th>
																<th class="text-center">Recurring</th>
																<th class="text-center">Frequency</th>
																<th style="font-size:25px">
																<span id="billing_add"><i class="fa fa-plus-circle"></i></span>
																</th>
															</tr>oppicer
															<tbody id="tbody_setup">
															<tr>
																<td class="ads1">1</td>
																<td>
																<?php echo form_dropdown('service_name[]', $svc, $ch->chargee_name, 'id="currency"  class="form-control  populate" style="width:100%;"');?>
																</td>
																<td>
																	<input type="text" name="service_amount[]" class="form-control number number text-right" value=""/>
																</td>
																<td>
																	<span style="float:left;margin:5px 3px;width:20%;">Start Date</span><input type="text" class="form-control" name="service_start_recurring[]"  style="float:left;width:75%;" data-date-format="dd/mm/yyyy" data-date-start-date="0d" data-plugin-datepicker/>
																	<br/>
																	<span style="float:left;margin:3px;width:20%;">End Date</span><input type="text" class="form-control" style="float:left;width:75%;" name="service_end_recurring[]" data-date-format="dd/mm/yyyy" data-date-start-date="0d" data-plugin-datepicker>
																</td>
																<td>
																	<select name="service_frequency[]">
																		<optgroup label="Frequency">
																			<option value="7">1 Week</option>
																			<option value="14">2 Week</option>
																			<option value="21">3 Week</option>
																			<option value="30">1 Month</option>
																			<option value="60">2 Month</option>
																			<option value="90">3 Month</option>
																			<option value="180">6 Month</option>
																			<option value="356">1 Year</option>
																		</optgroup>
																	</select>
																</td>
																<td rowspan=2><a ><i class="fa fa-times hapus_baris"></i></a></td>
															</tr>
															</tbody>
														</table>
														<?= form_close(); ?>
													</div>
												</div>
											
										</div>
									</div>
								</div>
							</div>
							<footer class="panel-footer">
								<div class="row">
									<div class="col-md-12 number text-right">
										<input type="button" value="Save As Draft" id="save_draft" class="btn btn-default">
										<input type="button" value="Confirm Change" id="save" class="btn btn-primary ">
										<a href="<?= base_url();?>masterclient/" class="btn btn-default">Close</a>
									</div>
								</div>
							</footer>
						</section>
					</div>
				
				</div>
			</div>
			
		<!-- end: page -->
		</section>
	</div>
</section>

<div id="modal_officer" class="modal-block modal-block-lg mfp-hide">

		<section class="panel" id="wOfficer">
			<header class="panel-heading">
				<h2 class="panel-title">Add Officer</h2>
			</header>
			<div class="panel-body">
                <?php 
					$attrib2= array( 'id' => 'Save_Officer','method' => 'POST');
					echo form_open_multipart("masterclient/add_officer",$attrib2); 
					
				// <form action="masterclient/add_officer" method="POST" id="Save_Officer">
				?>
				<input type="text" class="form-control" value="<?=$unique_code?>" name="unique_code"/>
				<table class="table table-bordered table-striped table-condensed mb-none" >
					<thead>
						<tr>
							<th>Name</th>
							<td><input type="text" class="form-control input-xs" name="nama" id="add_officer_nama" value=""/></td>
						</tr>
						<tr>
							<th>ID</th>
							<td><input type="text" class="form-control" name="id" value=""/></td>
						</tr>
						<tr>
							<th>Position</th>
							<td>
								<select name="position" class="form-control " id="position">
									<option value="Director">Director</option>
									<option value="CEO">CEO</option>
									<option value="Manager">Manager</option>
									<option value="Secretary">Secretary</option>
									<option value="Auditor">Auditor</option>
									<option value="Managing Director">Managing Director</option>
									<option value="Alternate Director">Alternate Director</option>
								</select>
							</td>
						</tr>
						<tr>
							<th>Date Of Appoinment</th>
							<td><input type="text" class="form-control " name="date_of_appointment" id="date_of_appointment" data-plugin-datepicker data-date-format="dd/mm/yyyy" value="<?=$ndate;?>"/></td>
						</tr>
						<tr>
							<th>Date Of Cessation</th>
							<td><input type="text" class="form-control " name="date_of_cessation" id="date_of_cessation"  data-plugin-datepicker data-date-format="dd/mm/yyyy" value="<?=$ndate;?>"/></td>
						</tr>
						<tr>
							<th>Address</th>
							<td><textarea style="height:100px;" name="address" ></textarea></td>
						</tr>
						<tr>
							<th>Alternate Address</th>
							<td><textarea style="height:100px;" name="alternate_address" ></textarea></td>
						</tr>
						<tr>
							<th>Nationality</th>
							<td><input type="text" class="form-control" name="nationality" value=""/></td>
						</tr>
						<tr>
							<td>Citizen</td>
							<td><?php
									// print_r($sales);
									$ctz[""] = [];
									foreach ($citizen as $cs) {
										$ctz[$cs->id] = $cs->citizen;
									}
									echo form_dropdown('citizen', $ctz, '', 'id="citizen"  class="form-control" style="width:100%;"');
								
									?>
							</td></tr>
						<tr>
							<th>Date Of Birth</th>
							<td><input type="text" class="form-control"  name="date_of_birth" data-plugin-datepicker data-date-format="dd/mm/yyyy" data-date-start="0" value=""/></td>
								
						</tr>
					</thead>
				</table>
				
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 number text-right">
						<!--button class="btn btn-primary modal-confirm">Confirm</button-->
						<button class="btn btn-primary" id="btn_add_officer">Submit</button>
						<button class="btn btn-danger modal-dismiss">Close</button>
					</div>
				</div>
			</footer>
			<?=form_close();?>
		</section>
	</div>
	

<script>
	$tab_aktif = "account";
$('form#Save_Officer').submit(function(e) {

    var form = $(this);

    e.preventDefault();

    $.ajax({
        type: "POST",
        url: "masterclient/add_officer",
        data: form.serialize(), // <--- THIS IS THE CHANGE
        dataType: "html",
        success: function(data){
			console.log(data);
			if (data =='Duplicate Goverment ID')
			{
				alert("Goverment ID Duplicate");
			} else {
				alert(data);
				$('#modal_officer').modal('hide');
				$html ='<tr><td><label class="ads"></label></td><td style="padding-top:15px;"><h6>'+$("#add_officer_nama").val()+'</h6>';
				$html +='<table class="table table-bordered table-striped table-condensed mb-none" >';
				$html +='	<thead>';
				$html +='		<tr>';
				$html +='			<th>Position</th>';
				$html +='			<th style="width:2%;">Date of Appointment</th>';
				$html +='			<th style="width:2%;">Date of Cesasion</th>';
				$html +='		</tr>';
				$html +='	</thead>';
				$html +='	<tbody id="body_content">';
				$html +='		<tr><td><label><input type="checkbox" value="">Director</label></td>';
				$html +='			<td><input type="text" data-plugin-datepicker data-date-format="dd/mm/yyyy" data-date-start-date="0d"/></td>';
				$html +='			<td><input type="text" data-plugin-datepicker data-date-format="dd/mm/yyyy" data-date-start-date="0d"/></td>';
				$html +='		</tr>';
				$html +='		<tr>';
				$html +='			<td><label><input type="checkbox" value="">CEO</label>';
				$html +='			</td>';
				$html +='			<td><input type="text" data-plugin-datepicker data-date-format="dd/mm/yyyy" data-date-start-date="0d"/></td>';
				$html +='			<td><input type="text" data-plugin-datepicker data-date-format="dd/mm/yyyy" data-date-start-date="0d"/></td>';
				$html +='		</tr>';
				$html +='		<tr>';
				$html +='			<td><label><input type="checkbox" value="">Manager</label></div></td>';
				$html +='			<td><input type="text" data-plugin-datepicker data-date-format="dd/mm/yyyy" data-date-start-date="0d"/></td>';
				$html +='			<td><input type="text" data-plugin-datepicker data-date-format="dd/mm/yyyy" data-date-start-date="0d"/></td>';
				$html +='		</tr>';
				$html +='		<tr>';
				$html +='			<td><label><input type="checkbox" value="">Secretary</label></div></td>';
				$html +='			<td><input type="text" data-plugin-datepicker data-date-format="dd/mm/yyyy" data-date-start-date="0d"/></td>';
				$html +='			<td><input type="text" data-plugin-datepicker data-date-format="dd/mm/yyyy" data-date-start-date="0d"/></td>';
				$html +='		</tr>';
				$html +='		<tr>';
				$html +='			<td><label><input type="checkbox" value="">Auditor</label></td>';
				$html +='			<td><input type="text" data-plugin-datepicker data-date-format="dd/mm/yyyy" data-date-start-date="0d"/></td>';
				$html +='			<td><input type="text" data-plugin-datepicker data-date-format="dd/mm/yyyy" data-date-start-date="0d"/></td>';
				$html +='		</tr>';
				$html +='		<tr>';
				$html +='			<td><label><input type="checkbox" value="">Managing Director</label></td>';
				$html +='			<td><input type="text" data-plugin-datepicker data-date-format="dd/mm/yyyy" data-date-start-date="0d"/></td>';
				$html +='			<td><input type="text" data-plugin-datepicker data-date-format="dd/mm/yyyy" data-date-start-date="0d"/></td>';
				$html +='		</tr>';
				$html +='		<tr>';
				$html +='			<td><label><input type="checkbox" class="alternatedir" value="1">Alternate Director</label>';
				$html +='				<div id="alternatedir1" style="display:none">';
				$html +='					<select data-plugin-selectTwo class="form-control populate input-sm">';
				$html +='						<option>Varder</option>';
				$html +='						<option>Durt</option>';
				$html +='					</select>';
				$html +='				</div>';
				$html +='			</td>';
				$html +='			<td><input type="text" data-plugin-datepicker data-date-format="dd/mm/yyyy" data-date-start-date="0d"/></td>';
				$html +='			<td><input type="text" data-plugin-datepicker data-date-format="dd/mm/yyyy" data-date-start-date="0d"/></td>';
				$html +='		</tr>';
				$html +='	</tbody>';
				$html +='</table>';
				$html +='</td>';
				$html +='<td>';
				$html +='<a ><i class="fa fa-timer"></i></a>';
				$html +='<a  class="delete_officer" data-id="'+data+'"><i class="fa fa-trash" style="font-size:16px;"></i></a>';
				$html +='</td>';
				$html +='</tr>';
				$("#body_officer").append($html);
				no_urut("ads");
				form.trigger("reset");
			}
            // $('#feed-container').prepend(data);
        },
        error: function() { alert("Error posting feed."); }
   });

});

		$(document).on('click',".check_stat",function() {
			// $('ul li.active').css('display', 'none');
			
				console.log($tab_aktif);
				if($(this).data("information") != $tab_aktif)
				{
					if (confirm("Save Changes ?"))
					{
						if ($tab_aktif == 'officer')
						{
							alert("Success Add");
						} else {
						// $().submit(function (e){
							var form = $("#w2-"+$tab_aktif+" form");
							// form.submit();
							console.log(form.attr('action'));

							$.ajax({
								type: "POST",
								url: form.attr('action'),
								data: form.serialize(), 
								dataType: "html",
								success: function(data){
									console.log(data);
									if (data =='Error')
									{
										alert("Error");
									} else {
										alert("Success Add");
										// form.trigger("reset");
									}
									$('#feed-container').prepend(data);
								},
								error: function() { alert("Error posting feed."); }
						   });
						}
					}						
				}else {
				}
			
					$tab_aktif = $(this).data("information");
		});
		$(document).on('click',"#save",function(){
			$("#w2-"+$tab_aktif+" form").submit();
		});
		$(document).on('click',"#save_draft",function(){
			$("#w2-"+$tab_aktif+" form").submit();
		});
</script>
<script>
	$(document).on('ready',function(){
		// $("#adds_officer").on("click",function(){
			// $("#modal_officer").modal('show');
		// });
		/* $("#btn_add_officer").on("click",function(){
			var url="masterclient/add_officer";
			add_officer_form = $("#Save_Officer").serialize();
			// var formData = new FormData(this);
			// console.log(add_officer_form);
			// console.log(formData);
			$.ajax({
				type: "POST",
				url: "<?php echo base_url()?>"+url,
				data: add_officer_form,
				success: function(response) {
					console.log(response);
					
				},
				error: function() {
					alert('system Error, Try Later Again');
				}
			});
			// e.preventDefault();
		}); */
		if(typeof localStorage.getItem('slitems') == "undefined")
		{
		// alert(localStorage.getItem('slitems'));
			// alert($("#edit_company_name").val());
			$("#edit_company_name").val(localStorage.getItem('slitems'));
		}
		$(document).on('click',".delete_officer",function() {
			if(confirm("Delete This Record?"))
			{
				var jqxhr = $.get("<?php echo base_url()?>masterclient/delete_officer/"+$(this).data('id'), function() {
				
				}).done(function(){
					no_urut('ads'); 
					alert("Deleted" );
				}).fail(function() {
					alert( "Error" );
				});
					$(this).closest("tr").remove();
			}
		});
		$(document).on('click',".hapus_baris",function() {
			if(confirm("Delete This Record?"))
			{
				$(this).closest("tr").remove();
			}
		});
		$(document).on('click',".hapus_duabaris",function() {
			if(confirm("Delete This Record?"))
			{
				$(this).closest("tr").next('tr').remove();
				$(this).closest("tr").remove();
			}
		});
		$(document).on('click','.alternatedir', function() {
			if($(this).is(':checked')){
				$("#alternatedir"+$(this).data('id')).show();
			}else{
				$("#alternatedir"+$(this).data('id')).hide();
			}
			// alert($(this).val());
		});
		
		$("#btn_officer_search_person").on('click',function(){
			$("#div_officer_person	").show();
		});
		$("#myform").validator();
		$byk_issued_share_capital = 3;
			$(document).on('click',"#add_issued_share_capital",function() {
				$byk_issued_share_capital++;
				$a = $("#body_issued_share_capital").html();
				$a += ' <tr>';
				$a += '		<td>'+$byk_issued_share_capital+'</td>';
				$a += '		<td><input type="text" class="form-control numberdes text-right" value=""/></td>';
				$a += '		<td><input type="text" class="form-control number text-right" value=""/></td>';
				$a += '		<td><?php echo form_dropdown_clear('issued_currency_member[]', $cr, '', 'id="currency"  class="form-control" style="width:100%;"'); ?></td>';
				$a += '		<td><?php echo form_dropdown_clear('issued_sharetype_member[]', $bl, '', 'id="slsales"  class="form-control" style="width:100%;"'); ?>';
				
				$a += '		</td>';
				$a += '		<td><a href="#"><i class="fa fa-times"></i></a></td>';
				$a += ' </tr>';
				$("#body_issued_share_capital").html($a);
			});
			$byk_paid_share_capital = 3;
			$(document).on('click',"#paid_issued_share_capital",function() {
				$byk_issued_share_capital++;
				$a = $("#body_paid_issued_share_capital").html();
				$a += ' <tr>';
				$a += '		<td>'+$byk_issued_share_capital+'</td>';
				$a += '		<td><input type="text" class="form-control number text-right" value=""/></td>';
				$a += '		<td><input type="text" class="form-control number text-right" value=""/></td>';
				$a += '		<td><?php echo form_dropdown_clear('paid_currency_member[]', $cr, '', 'id="currency"  class="form-control" style="width:100%;"'); ?></td>';
				$a += '		<td><?php echo form_dropdown_clear('paid_sharetype_member[]', $bl, '', 'id="slsales"  class="form-control" style="width:100%;"'); ?>';
				$a += '		<td><a href="#"><i class="fa fa-times"></i></a></td>';
				$a += ' </tr>';
				$("#body_paid_issued_share_capital").html($a);
			});
			$(document).on('click',"#member_issued_share_capital",function() {
				$byk_member_share_capital++;
				$a = $("#body_member_issued_share_capital").html();
				$a += ' <tr>';
				$a += '		<td rowspan =4>'+$byk_member_share_capital+'</td>';
				$a += '		<td><input type="text" class="form-control input-xs" value=""/></td>';
				$a += '		<td><input type="text" class="form-control" value=""/></td>';
				$a += '		<td><input type="text" class="form-control" value=""/></td>';
				$a += '		<td><?php echo form_dropdown_clear('sharetype_member[]', $bl, '', 'id="slsales"  class="form-control" style="width:100%;"'); ?>';
				$a += '		<td><input type="text" class="form-control number" value=""/></td>';
				$a += '		<td><input type="text" class="form-control number" value=""/></td>';
				$a += '		<td rowspan =4>Certificate</td>';
				$a += '		<td rowspan =4>';
				$a += '			<a href="#"><i class="fa fa-pencil"></i></a>';
				$a += '			<a href="#"><i class="fa fa-share-alt"></i></a>';
				$a += '		</td>';
				$a += '	</tr>';
				$a += '	<tr>';
				$a += '		<td rowspan =3><textarea style="height:100px;"></textarea></td>';
				$a += '		<td><input type="text" class="form-control" value=" "/></td>';
				$a += '		<td><input type="text" class="form-control" value=""/></td>';
				$a += '		<td><?php echo form_dropdown_clear('currency_member[]', $cr, '', 'id="currency"  class="form-control" style="width:100%;"'); ?></td>';
				$a += '		<td><input type="text" class="form-control number" value=""/></td>';
				$a += '		<td><input type="text" class="form-control number" value=""/></td>';
				$a += '	</tr>';
				$a += '	<tr>';
				$a += '		<td>';
				$a += '			<select>';
				$a += '				<option>Singapore</option>';
				$a += '				<option>Singapore P.R</option>';
				$a += '			</select>';
				$a += '		</td>';
				$a += '		<td>Local Phone</td>';
				$a += '		<td><input type="text" class="form-control"  data-plugin-masked-input data-input-mask="(+99) 999-9999" placeholder="(+23) 123-1234"/></td>';
				$a += '		<td></td>';
				$a += '		<td></td>';
				$a += '	</tr>';
				$a += '	<tr>';
				$a += '		<td></td>';
				$a += '		<td>Email</td>';
				$a += '		<td><input type="email" class="form-control" value="+65 1111 2222"/></td>';
				$a += '		<td></td>';
				$a += '		<td></td>';
				$a += '	</tr>';
				$("#body_member_issued_share_capital").html($a);
			});
			
			
	});
	$byk_chargee =1;
			$(document).on('click',"#chargee_Add",function() {
				// alert("A");
				$byk_chargee++;
				 $a="";
				// $b = $("#body_chargee").html();
				$a += '<tr>';
				$a += '<td><?php echo form_dropdown_clear('chargee_name[]', $svc, '', 'id="currency"  class="form-control" style="width:100%;"');?></td>';
				$a += '<td><input type="text" name="chargee_nature_of[]" class="form-control" value=""/></td>';
				$a += '<td><input type="text" name="chargee_date_reg[]" class="form-control datepicker" data-plugin-datepicker data-date-format="dd/mm/yyyy" /></td>';
				$a += '<td><input type="text" name="chargee_no[]" class="form-control" value=""/></td>';
				$a += '<td>';
				$a += '<?php echo form_dropdown_clear('chargee_currency[]', $cr, '', 'id="currency"  class="form-control" style="width:100%;"'); ?>';
				$a += '</td>';
				$a += '<td><input type="text" name="chargee_amount[]" class="form-control number" value=""/></td>';
				$a += '</tr>';
				$a += '<tr>';
				$a += '<td></td>';
				$a += '<td></td>';
				$a += '<td><input type="text" name="chargee_date_satisfied[]" class="form-control datepicker" data-plugin-datepicker data-date-format="dd/mm/yyyy" value=""/></td>';
				$a += '<td><input type="text" name="chargee_satisfied_no[]" class="form-control" value=""/></td>';
				$a += '<td></td>';
				$a += '<td></td>';
				$a += '</tr>';
				$("#body_chargee").prepend($a); 
				$('.datepicker').datepicker({ dateFormat:'yyyy-mm=dd'});
					
				$("input.number").bind({
					keydown: function(e) {
						if (e.shiftKey === true ) {
							if (e.which == 9) {
								return true;
							}
							return false;
						}
						if (e.which > 57) {
							return false;
						}
						if (e.which==32) {
							return false;
						}
						return true;
					}
				});
			});
			$byk_member_capital=1;
			$(document).on('click',"#add_member_capital",function() {
				// alert("A");
				 $a="";
				// $b = $("#body_chargee").html();
				$a += '<tr>';
				$a += '	<td rowspan=2>'+$byk_member_capital+'</td>';
				$a += '		<td><input type="text"  name="nama_member_capital[]" class="form-control input-xs" value=""/></td>';
				$a += '		<td>';
				$a += '		<?php echo form_dropdown_clear('sharetype_member[]', $bl, '', 'id="slsales"  class="form-control" style="width:100%;"'); ?>';
				$a += '	</td>';
				$a += '		<td><input type="text" name="shares_member_capital[]" class="form-control number text-right" value=""/></td>';
				$a += '		<td><input type="text" name="no_share_paid_member_capital[]" class="form-control number text-right" value=""/></td>';
				$a += '		<td>Certificate</td>';
				$a += '		<td rowspan=2><a href="#"><i class="fa fa-times hapus_duabaris"></i></a></td>';
				$a += '		</tr>';
				$a += '		<tr>';
				$a += '		<td><input type="text" name="gid_member_capital[]"  class="form-control" value=""/></td>';
				$a += '		<td><?php echo form_dropdown_clear('currency_member_capital[]', $cr, '', 'id="currency"  class="form-control" style="width:100%;"'); ?>';
				$a += '		</td>';
				$a += '		<td><input type="text" name="amount_share_member_capital[]" class="form-control number text-right" value=""/></td>';
				$a += '		<td><input type="text" name="amount_share_paid_member_capital[]"  nameclass="form-control number text-right" value=""/></td>';
				$a += '		<td><a>SettlePayment</a></td>';
				$a += '		</tr>';
				$("#body_members_capital").prepend($a); 
				$('.datepicker').datepicker({ dateFormat:'yyyy-mm=dd'});
					
				$("input.number").bind({
					keydown: function(e) {
						if (e.shiftKey === true ) {
							if (e.which == 9) {
								return true;
							}
							return false;
						}
						if (e.which > 57) {
							return false;
						}
						if (e.which==32) {
							return false;
						}
						return true;
					}
				});
				$byk_member_capital++;
			});
			$byk_billing =2;
			$(document).on('click',"#billing_add",function() {
				$byk_billing++;
				$a = "";
				$a += '<tr>';
				$a += '	<td class="ads1"></td>';
				$a += '	<td>';
				$a += '		<?php echo form_dropdown_clear('service_name[]', $svc, $ch->chargee_name, 'id="currency"  class="form-control  populate" style="width:100%;"');?>';
				$a += '	</td>';
				$a += '	<td>';
				$a += '		<input type="text" class="form-control" value=""/>';
				$a += '	</td>';
				$a += '	<td>';
				$a += '		<span style="float:left;margin:5px 3px;width:20%;">Start Date</span><input type="text" class="form-control" name="service_start_recurring[]"  style="float:left;width:75%;" data-date-format="dd/mm/yyyy" data-date-start-date="0d" data-plugin-datepicker/>';
				$a += '		<br/>';
				$a += '		<span style="float:left;margin:3px;width:20%;">End Date</span><input type="text" class="form-control" style="float:left;width:75%;" name="service_end_recurring[]" data-date-format="dd/mm/yyyy" data-date-start-date="0d" data-plugin-datepicker>';
				$a += '	</td>';
				$a += '	<td>';
				$a += '		<select data-plugin-selectTwo name="service_frequency[]">';
				$a += '			<optgroup label="Frequency">';
				$a += '				<option value="7">1 Week</option>';
				$a += '				<option value="14">2 Week</option>';
				$a += '				<option value="21">3 Week</option>';
				$a += '				<option value="30">1 Month</option>';
				$a += '				<option value="60">2 Month</option>';
				$a += '				<option value="90">3 Month</option>';
				$a += '				<option value="180">6 Month</option>';
				$a += '				<option value="356">1 Year</option>';
				$a += '			</optgroup>';
				$a += '		</select>';
				$a += '	</td>';
				$a += '	<td>';
				$a += '		<a href="#"><i class="fa fa-trash"></i></a>';
				$a += '	</td>';
				$a += '</tr>';	
				$("#tbody_setup").prepend($a);
				$('.datepicker').datepicker({ dateFormat:'yyyy-mm=dd'});
					
				$("input.number").bind({
					keydown: function(e) {
						if (e.shiftKey === true ) {
							if (e.which == 9) {
								return true;
							}
							return false;
						}
						if (e.which > 57) {
							return false;
						}
						if (e.which==32) {
							return false;
						}
						return true;
					}
				});
				no_urut("ads1");
			});
</script>
