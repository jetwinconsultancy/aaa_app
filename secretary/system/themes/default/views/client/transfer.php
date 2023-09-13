<section class="panel">
	<div class="panel-body">
		<div class="col-md-12">
			<div id="modal_transfer" class="">
				<section id="wTransfer">
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
													<label class="col-sm-5 control-label">Date</label>
													<div class="col-sm-3">
														<input type="text" class="form-control" name="date" data-date-format="dd/mm/yyyy" data-date-start-date="0d"data-plugin-datepicker  required>
													</div>
												</div>	
												<div class="form-group">
													<label class="col-sm-5 control-label" for="allotment_sharetype">Share Type</label>
													<div class="col-sm-3">
														
														<select class="form-control">
															<option>ORDINARY</option>
															<option>Share Type 1</option>
															<option>Share Type 2</option>
															<option>Share Type 3</option>
														</select>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-5 control-label" for="Currency">Currency</label>
													<div class="col-sm-3">
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
									
		</div>
	</div>
	
<!-- end: page -->
</section>
<script>
	$(document).on('click','#manual',function(){
		$("#A").show();
	});
	$(document).on('click','.unmanual',function(){
		$("#A").hide();
	});
</script>