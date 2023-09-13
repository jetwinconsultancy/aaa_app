<div class="header_between_all_section">
<section class="panel">
	<?php echo $breadcrumbs;?>
	<div class="panel-body">
		<div class="col-md-12">
			<div id="modal_transfer" class="">
				<section id="wTransfer">
					<!-- <?php $attrib = array('class' => 'form-horizontal transfer_form', 'data-toggle' => 'validator', 'role' => 'form', 'id' => 'transfer_form');
								echo form_open_multipart("masterclient/save_transfer", $attrib);
							?>	 -->
					<?php echo form_open_multipart('', array('id' => 'transfer_form', 'class' => 'form-horizontal', 'enctype' => "multipart/form-data")); ?>
					<div class="panel-body">
									<div class="wizard-progress wizard-progress-lg">
										<div class="steps-progress">
											<div class="progress-indicator"></div>
										</div>
										<ul class="wizard-steps" id="transfer_tab">
											<li class="active">
												<a href="#transfer_number_shares" data-toggle="tab"><span>1</span>Number Shares</a>
											</li>
											<li>
												<a href="#transfer_member" data-toggle="tab"><span>2</span>Member</a>
											</li>
											<li>
												<a href="#transfer_confirm" data-toggle="tab"><span>3</span>Certificate</a>
											</li>
										</ul>
									</div>
					
									<!-- <form class="form-horizontal" novalidate="novalidate"> -->
										<div class="hidden"><input type="text" class="form-control" name="company_code" value="<?=$company_code?>"/></div>
										<div class="hidden"><input type="text" class="form-control" name="edit_transaction_id" value="<?=$transaction_id?>"/></div>
										<div class="hidden"><input type="text" class="form-control" name="transaction_type" value="Transfer"/></div>
										<div class="hidden"><input type="text" class="form-control" name="edit_transaction_date" value="<?=$transfer[0]->transaction_date?>"/></div>
										<div class="tab-content">
											<div id="transfer_number_shares" class="tab-pane active">
												<div class="hidden"><input type="text" class="form-control" name="client_member_share_capital_id" id="client_member_share_capital_id" value="<?=$transfer[0]->client_member_share_capital_id?>"/></div>
												<div class="form-group">
													<label class="col-sm-5 control-label">Transaction Date</label>
													<div class="col-sm-3">
														<div class="input-group" id="date_datepicker" style="width: 100%;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control transfer_date" name="date" id="transaction_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker required value="<?php echo $transfer[0]->transaction_date; ?>">
														</div>
													</div>
												</div>	
												<div class="form-group">
													<label class="col-sm-5 control-label" for="allotment_sharetype">Class</label>
													<div class="col-sm-3">
														
														<select class="form-control" style="text-align:right;width: 100%;" name="class" id="class">
															<option value="0">Select Class</option>
														</select>
													</div>
												</div>
												<div class="form-group" id="other_class" hidden>
													<label class="col-sm-5 control-label" for="transfer_others">Others</label>
													<div class="col-sm-3">
														<input type="text" class="form-control" name="others" id="others" value="<?=$transfer[0]->other_class?>" readonly/>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-5 control-label" for="Currency">Currency</label>
													<div class="col-sm-3">
														<input type="text" class="form-control" name="currency" id="currency" value="<?=$transfer[0]->currency?>" readonly/>
													</div>
												</div>
											</div>
											<div id="transfer_member" class="tab-pane">
												
												<h3 align="left">From</h3>	
												<table  class="table table-bordered table-striped table-condensed mb-none">
													<thead>
														<div class="tr">
															<!-- <div class="th empty"></div> -->
															<div class="th">ID/Name/Share Number</div>
															<!-- <div class="th">Name</div> -->
															<div class="th">Current Number of Shares</div>
															<div class="th">Number of Shares to Transfer</div>
															<div class="th">Consideration</div>
															<!-- <a href="javascript: void(0);" class="th" rowspan =2 style="border-bottom:none;color: #D9A200;width:140px; outline: none !important;text-decoration: none;"><span id="transfer_member_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Transfer" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add Transfer</span></a> -->
														</div class="tr">
													</thead>
													<div class="tbody" id="transfer_add">
																			

													</div>
													<div class="tr" id="total_share">
													<!-- <div class="th" valign=middle>No</div> -->
														<div class="th">Total</div>
														<!-- <div class="th" style="border-right: none;"></div> -->
														<div class="th" style="border-left: none;"></div>
														<div class="th" style="text-align: right;" id="total_from">0</div>
														<div class="th"></div>
														<!-- <div class="th"></div> -->
														
													</div>
												</table>
												<!-- <table  class="table table-bordered table-striped table-condensed mb-none">
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
														<td class="text-right">
																1,000,000
														</td>
														<td>
															<div>
																<input type="text" class="form-control number text-right" value="1000" required>
															</div>
														</td>
														<td class="text-center center">
															<a class="fa fa-trash amber" style="font-size:16px;"></a>
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
														<td class="text-right">
																200,000
														</td>
														<td>
															<div>
																<input type="text" class="form-control number text-right" value="200" required>
															</div>
														</td>
														<td class="text-center center">
															<a class="fa fa-trash amber" style="font-size:16px;"></a>
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
																<input type="text" class="form-control number text-right" value="" required>
															</div>
														</td>
													</tr>
													<tfoot>
														<tr>
															<td colspan =4>Total</td>
															<td class="text-right">1,200</td>
														</tr>
													</tfoot>
												</table> -->

												<h3 align="left">To</h3>	
												<table class="table table-bordered table-striped table-condensed mb-none">
													<thead>
														<div class="tr" id="table_transfer_to">
															<!-- <div class="th empty"></div> -->
															<div class="th">ID</div>
															<div class="th">Name</div>
															<div class="th">Number of Shares</div>
															
															<!-- <a href="javascript: void(0);" class="th" rowspan =2 style="border-bottom:none;color: #D9A200;width:140px; outline: none !important;text-decoration: none;"><span id="transfer_to_member_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Transfer" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add Transfer</span></a> -->
														</div class="tr">
													</thead>
													<div class="tbody" id="transfer_to_add">
																			

													</div>
													<div class="tr" id="total_share_transfer_to">
													<!-- <div class="th" valign=middle>No</div> -->
														<div class="th">Total</div>
														<div class="th" style=""></div>
														<div class="th" style="text-align: right;" id="total_to">0</div>
														<!-- <div class="th" style=""></div> -->
														<!-- <div class="th"></div> -->
														
													</div>
												</table>
												<div class="form-group">
													<div class="col-sm-12">
														<input type="button" class="btn btn-primary addShareTransferInfo" id="addShareTransferInfo" value="ADD" style="float: right; margin-bottom: 20px; margin-top: 20px;">
													</div>
												</div><!-- submitShareTransferInfo -->
												<div class="register_member_table" id="register_table_member" style="width:100%;">
													<table style="border:1px solid black; width: 100%;" class="allotment_table" id="share_transfer_table">
														<thead>
															<tr>
																<th style="text-align:center; width:40px !important;padding-right:2px !important;padding-left:2px !important;">No</th>
																<th style="word-break:break-all;text-align: center;width:80px !important;padding-right:2px !important;padding-left:2px !important;">Transferor ID</th>
																<th style="word-break:break-all;text-align: center; width:150px !important;padding-right:2px !important;padding-left:2px !important;">Transferor Name</th>
																<th style="word-break:break-all;text-align: center;width:80px !important;padding-right:2px !important;padding-left:2px !important;">Transferee ID</th>
																<th style="word-break:break-all;text-align: center; width:80px !important;padding-right:2px !important;padding-left:2px !important;">Transferee Name</th>
																<th style="text-align: center; width:70px !important;padding-right:2px !important;padding-left:2px !important;">Class</th>
																<!-- <th style="text-align: center; width:20px !important;padding-right:2px !important;padding-left:2px !important;">Share Certificate No.</th> -->
																<th style="text-align: center; width:50px !important;padding-right:2px !important;padding-left:2px !important;">CCY</th>
																<!-- 1 -->
																<th style="text-align: center; width:10px !important;padding-right:2px !important;padding-left:2px !important;">No. of Shares Transferred</th>
																<th style="width:20px !important;"></th>
																<!-- <th style="text-align: center; width:10px !important;padding-right:2px !important;padding-left:2px !important;">Balance No of Shares</th> -->
																			
															</tr>
														</thead>
														<tbody id="transfer_info_add">
																					

														</tbody>
													</table>
												</div>

											<!-- <table  class="table table-bordered table-striped table-condensed mb-none">
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
															<input type="text" class="form-control number text-right" value="1,200" required>
														</div>
													</td>
													<td>
														<div>
															<input type="file" class="form-control" required>
														</div>
													</td>
													<td class="text-center center">
														<a class="fa fa-trash amber" style="font-size:16px;"></a>
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
															<input type="text" class="form-control number text-right" value="" required>
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
											</table> -->
											</div>
										<div id="transfer_confirm" class="tab-pane">
											<div style="margin-bottom: 20px;">
												<span class="help-block cert_remind_tag"></span>
											</div>
											<span style="color: inherit; font-size: 2.4rem; letter-spacing: -1px; font-weight: 500; margin-top: 20px; margin-bottom: 10px; font-family: inherit; line-height: 2.1;">Transferor Record</span>

											<form id="confirm_transfer_cert_form">
												<input type="hidden" class="hidden_latest_cert_no" value=""/>
												<!-- <input type="hidden" class="hidden_transaction_master_id" value=""/> -->
												<table style="border:1px solid black; width: 1070px;" class="allotment_table" id="transferee_table">
													<thead>
														<tr>
															<th style="width:300px; text-align: center">ID/Name</th>
															<th style="width:170px; text-align: center">Currency/Class</th>
															<th style="width:100px; text-align: center">Old Number of Shares</th>
															<th style="width:50px; text-align: center">Old Certificate</th>
															
															<th style="width:100px; text-align: center">Total Number of Shares to Transfer</th>
															<th style="width:100px; text-align: center">Number of Shares to Transfer</th>
															<th style="width:130px; text-align: center">New Number of Shares</th>
															<th style="width:100px; text-align: center">New Certificate <span data-toggle="tooltip" data-trigger="hover" data-original-title="Please insert number for certificate number." style="font-size:14px;"><i class="fa fa-info-circle"></i></span></th>
														</tr>
													</thead>
													<tbody class="transferor_table_add">
													</tbody>
												</table>
												
												<span style="color: inherit; font-size: 2.4rem; letter-spacing: -1px; font-weight: 500; margin-top: 20px; margin-bottom: 10px; font-family: inherit; line-height: 2.1;">Transferee Record</span>
												<table style="border:1px solid black; width: 1070px;" class="allotment_table" id="certificate_table">
													<thead>
														<tr>
															<th style="width:300px; text-align: center">ID/Name</th>
															<th style="width:200px; text-align: center">Currency/Class</th>
															<th style="width:150px; text-align: center">New Number of Shares</th>
															<!-- <th style="width:50px; text-align: center">Old Certificate</th>
															<th style="width:150px; text-align: center">New Number of Shares</th> -->
															<th style="width:150px; text-align: center">New Certificate <span data-toggle="tooltip" data-trigger="hover" data-original-title="Please insert number for certificate number." style="font-size:14px;"><i class="fa fa-info-circle"></i></span></th>
														</tr>
													</thead>
													<tbody class="certificate_table_add">
													</tbody>
												</table>
												<!-- <div class="form-group">
													<div class="col-sm-12">
														<input type="button" class="btn btn-primary submitShareTransferCertInfo" id="submitShareTransferCertInfo" value="Save" style="float: right; margin-bottom: 20px; margin-top: 20px;">
													</div>
												</div> -->
											<!-- <table style="border:1px solid black" class="transfer_table" id="confirm_transfer_add">
											
												<tr> 
													<th rowspan="2" style="text-align: center">No</th> 
													<th rowspan="2" style="text-align: center">Members</th> 
													<th colspan="3" style="text-align: center">Number of Shares</th> 
												</tr> 
												<tr> 
													<th style="text-align: center">Existing</th> 
													<th style="text-align: center">Transfer</th> 
													<th style="text-align: center">New</th> 
													
												</tr> 
											</table> -->
										</div>
									<!-- </form> -->
								
								

					</div>
					</div>
					
				<div class="panel-footer">
					<ul class="pager wizard">
			            <!-- <li class="previous hidden"><a href="javascript: void(0);">Previous</a></li>
			            <li class="next" style="float: right"><a href="javascript: void(0);">Next</a></li>
			            <li class="cancel_transfer" style="float: right; margin-right: 10px;"><a href="javascript: void(0);" id="cancel_transfer">Cancel</a></li> -->

			            <li class="previous hidden"><a href="javascript: void(0);">Go To Previous Page</a></li>
			            <li class="next" style="float: right"><a href="javascript: void(0);">Go To Next Page</a></li>
			            <li class="other_next" style="float: right"><a href="<?= base_url();?>masterclient/view_transfer/<?=$company_code?>">Done</a></li>
			            <!-- <li class="cancel_transfer" style="float: right; margin-right: 10px;"><a href="javascript: void(0);" id="cancel_transfer">Cancel Transfer</a></li> -->
			            <li class="cancel_transfer" style="float: right; margin-right: 10px;"><a href="<?= base_url();?>masterclient/view_transfer/<?=$company_code?>" id="cancel_buyback">Cancel Transfer</a></li>
			        </ul>
					<!-- <ul class="pager">
						<li class="previous disabled">
							<a><i class="fa fa-angle-left"></i> Previous</a>
						</li>
						<li class="finish  modal-dismiss hidden pull-right">
							<a>Save</a>
						</li>
						<li class="next">
							<a>Next <i class="fa fa-angle-right"></i></a>
						</li>
					</ul> -->
				</div>	
				<?= form_close(); ?>
				<div class="loading" id='loadingTransfer'>Loading&#8230;</div>
				</section>
				
			</div>
									
		</div>
	</div>
	
<!-- end: page -->
</section>
</div>
<script>
	var company_class = <?php echo json_encode($company_class);?>;
	var transfer = <?php echo json_encode($transfer);?>;
	var company_code = "<?php echo ($company_code);?>";
	var access_right_member_module = <?php echo json_encode($member_module);?>;
	var client_status = <?php echo json_encode($client_status);?>;
	if(access_right_member_module == "read" || client_status != "1")
	{
		$("select#class").attr("disabled", true);
		$(".transfer_date").attr("disabled", true);
		$("#transfer_member_Add").hide();
		$("#transfer_to_member_Add").hide();
	}
	localStorage.removeItem("shareTransferArray");
	localStorage.removeItem("shareTransferInfoArray");
	$('[data-toggle="tooltip"]').tooltip();

</script>
<script src="themes/default/assets/js/transfer.js?v=40eee4fc8d1b59e4584b0d39edfa2082"></script>
<script>
	$(document).ready(function() {
	    $('#loadingTransfer').hide();
	});
	$(document).on('click','#manual',function(){
		$("#A").show();
	});
	$(document).on('click','.unmanual',function(){
		$("#A").hide();
	});
</script>