<div class="header_between_all_section">
<section class="panel">
	<?php echo $breadcrumbs;?>
	<div class="panel-body">
		<div class="col-md-12">
			<div id="modal_transfer" class="">
				<section id="wTransfer">
					<?php $attrib = array('class' => 'form-horizontal transfer_form', 'data-toggle' => 'validator', 'role' => 'form', 'id' => 'transfer_form');
								echo form_open_multipart("masterclient/save_transfer", $attrib);
							?>	
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
											<div id="transfer_member" class="tab-pane" align="center">
												<h3 align="left">From</h3>	
												<table  class="table table-bordered table-striped table-condensed mb-none">
													<thead>
														<div class="tr">
															<!-- <div class="th empty"></div> -->
															<div class="th">ID</div>
															<div class="th">Name</div>
															<div class="th">Current Number of Shares</div>
															<div class="th">Number of Shares to Transfer</div>
															<div class="th">Consideration</div>
															<!-- <div class="th" style="font-size:25px;border-bottom:none;width: 50px;"><span id="transfer_member_Add"><i class="fa fa-plus-circle"></i></span></div> -->
															<a href="javascript: void(0);" class="th" rowspan =2 style="border-bottom:none;color: #D9A200;width:140px; outline: none !important;text-decoration: none;"><span id="transfer_member_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Transfer" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add Transfer</span></a>
														</div class="tr">
													</thead>
													<div class="tbody" id="transfer_add">
																			

													</div>
													<div class="tr" id="total_share">
													<!-- <div class="th" valign=middle>No</div> -->
														<div class="th">Total</div>
														<div class="th" style="border-right: none;"></div>
														<div class="th" style="border-left: none;"></div>
														<div class="th" style="text-align: right;" id="total_from">0</div>
														<div class="th"></div>
														<div class="th"></div>
														
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
															<!-- <div class="th">Certificate No</div> -->
															<!-- <div class="th" style="font-size:25px;border-bottom:none;width: 50px;"><span id="transfer_to_member_Add"><i class="fa fa-plus-circle"></i></span></div> -->
															<a href="javascript: void(0);" class="th" rowspan =2 style="border-bottom:none;color: #D9A200;width:140px; outline: none !important;text-decoration: none;"><span id="transfer_to_member_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Transfer" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add Transfer</span></a>
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
														<div class="th"></div>
														
													</div>
												</table>
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
												<table style="border:1px solid black" class="transfer_table" id="confirm_transfer_add">
												
													<tr> 
														<th rowspan="2" style="text-align: center">No</th> 
														<th rowspan="2" style="text-align: center">Members</th> 
														<th colspan="3" style="text-align: center">Number of Shares</th> 
														<!-- <th rowspan="2">Certificate No.</th>  -->
													</tr> 
													<tr> 
														<th style="text-align: center">Existing</th> 
														<th style="text-align: center">Transfer</th> 
														<th style="text-align: center">New</th> 
														
													</tr> 
													
													<!-- <div class="tbody" id="confirm_transfer_add_row">
														
													</div> -->
												</table>


												<!-- <table  class="table table-bordered table-striped table-condensed mb-none">
													<thead>
														<div class="tr">
															<div class="th" style="border-bottom:none;width: 100px">No</div>
															<div class="th" style="border-bottom:none;width: 99px">Members</div>
															<div class="th colspan3">Number of Shares</div>
															<div class="th" style="border-bottom:none;width: 99px" valign=middle>Certificate No.</div>
														</div>
													</thead>
												</table> -->
												<!-- <table  class="table table-bordered table-striped table-condensed mb-none">
													<thead>
														<div class="tr">
															<div class="th empty"></div>
															<div class="th empty"></div>
															
															<div class="th" style="width: 100px !important;">Existing</div>
															<div class="th" style="width: 100px !important;">Transfer</div>
															<div class="th" style="width: 100px !important;">New</div>
															<div class="th empty"></div>
														</div>
													</thead>
													<div class="tbody" id="confirm_allotment_add">
																			
														<div class="tr">
															
															
															<div class="th" style="width: 100px !important;">Name</div>
															<div class="th" style="width: 100px !important;">Currency</div>
															<div class="th" style="width: 100px !important;">Amount Share</div>
															<div class="th" style="width: 100px !important;">Amount Share</div>
															<div class="th" style="width: 100px !important;">Amount Share</div>
															<div class="th" style="width: 100px !important;">Amount Share</div>
															
														</div>
													</div>
												</table> -->
												<!-- <table class="table table-bordered">
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
													<div class="form-group">
														<label>Certificate:</label>
													</div>
													<div class="form-group">
														<label><input type="radio" name="certificate" class="unmanual">&nbsp;&nbsp;&nbsp;Cancel all existing and replace with new certificate</label>
													</div>
													<div class="form-group">
														<label><input type="radio" name="certificate" class="unmanual">&nbsp;&nbsp;&nbsp;New certificate number for shares alloted</label>
													</div>
													<div class="form-group">
														<label><input type="radio" name="certificate"  id="manual">&nbsp;&nbsp;&nbsp;Manual Changes</label>
														<table class="table table-striped" id="A" style="display:none" >
															<tr>
																<td>No</td>
																<td>Date</td>
																<td>Members</td>
																<td>Share</td>
																<td>Certificate</td>
															</tr>
															<tr>
																<td>1</td>
																<td>01/01/2016</td>
																<td>Durt</td>
																<td>1000</td>
																<td><a>CRT118888</a></td>
															</tr>
														</table>
													</div>
												</div> -->
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
</script>
<script src="themes/default/assets/js/transfer.js?v=30eee4fc8d1b59e4584b0d39edfa2082"></script>
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