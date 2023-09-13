
<!-- Modal -->
	<div id="modal_officer" class="modal-block modal-block-lg mfp-hide">
		<section class="panel" id="wOfficer">
			<header class="panel-heading">
				<h2 class="panel-title">Add Officer</h2>
			</header>
			<?php
				$attrib = array('data-toggle' => 'validator', 'role' => 'form');
				echo form_open_multipart("masterclient/add_officer", $attrib); 
			?>
			<div class="panel-body">
				<table class="table table-bordered table-striped table-condensed mb-none" >
					<thead>
						<tr>
							<th>Name</th>
							<td><input type="text" class="form-control input-xs" name="nama" value=""/></td>
						</tr>
						<tr>
							<th>ID</th>
							<td><input type="text" class="form-control" name="id" value=""/></td>
						</tr>
						<tr>
							<th>Date Of Appoinment</th>
							<td><input type="text" class="form-control " name="date_of_appointment" data-plugin-datepicker data-date-format="dd/mm/yyyy" value="<?=$ndate;?>"/></td>
						</tr>
						<tr>
							<th>Date Of Cessation</th>
							<td><input type="text" class="form-control " name="date_of_cessation"  data-plugin-datepicker data-date-format="dd/mm/yyyy" value="<?=$ndate;?>"/></td>
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
							<td><input type="text" class="form-control"  name="date_of_birth" data-plugin-datepicker data-date-format="dd/mm/yyyy" value="01/01/1986"/></td>
							
						</tr>
					</thead>
				</table>
				
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 number text-right">
						<!--button class="btn btn-primary modal-confirm">Confirm</button-->
						<input type="submit" class="btn btn-primary" />
						<button class="btn btn-danger modal-dismiss">Close</button>
					</div>
				</div>
			</footer>
			</form>
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
					<div class="col-md-12 number text-right">
						<!--button class="btn btn-primary modal-confirm">Confirm</button-->
						<button class="btn btn-success modal-dismiss">Save</button>
						<button class="btn btn-danger modal-dismiss">Close</button>
					</div>
				</div>
			</footer>
		</section>
	</div>
	
	<div id="modalbilling" class="modal-block modal-block-lg mfp-hide">
		<section class="panel">
			<div class="panel-body">
				<ul class="nav nav-tabs nav-justify">
					<li class="active">
						<a href="#w2-billing" data-toggle="tab" class="text-center">
							<span class="badge hidden-xs">1</span>
							Billing
						</a>
					</li>
					<li>
						<a href="#w2-payment1" data-toggle="tab" class="text-center">
							<span class="badge hidden-xs">2</span>
							Payment
						</a>
					</li>
				</ul>
				<div class="tab-content">
					<div id="w2-billing" class="tab-pane active">
						<header class="panel-heading">
							<h2 class="panel-title">Billing</h2>
						</header>
						<div class="panel-body">
							<table class="table table-bordered table-striped table-condensed mb-none">
								<tr>
									<th>No</th>
									<th>Created</th>
									<th>Invoice No</th>
									<th>Amount</th>
									<th>Receipt</th>
								</tr>
								<tbody id="billing_body">
									<tr>
										<td><label><input type="checkbox">&nbsp;1</label></td>
										<td>2016-01-01</td>
										<td>INV-0001</td>
										<td align=right>10,000</td>
										<td align=right	>0</td>
									</tr>
									<tr>
										<td><label><input type="checkbox">&nbsp;2</label></td>
										<td>2016-01-01</td>
										<td>INV-0002</td>
										<td align=right>15,000</td>
										<td align=right>0</td>
									</tr>
								</tbody>
							</table>
						</div>
						<footer class="panel-footer">
							<div class="row">
								<div class="col-md-12 number text-right">
									<!--button class="btn btn-primary modal-confirm">Confirm</button-->
									<button href="#modal_create_billing" class="btn btn-default modal-sizes">Create Invoice</button>
									<button href="#w2-payment" class="btn btn-default modal-sizes">Receipt</button>
									<button class="btn btn-default modal-dismiss">Close</button>
								</div>
							</div>
						</footer>
					</div>
					<div id="w2-payment1" class="tab-pane">
							<header class="panel-heading">
								<h2 class="panel-title">Receipt</h2>
							</header>
							<div class="panel-body">
								<table class="table table-bordered table-striped table-condensed mb-none" >
									<tr>
										<th>No</th>
										<th>Reference No</th>
										<th>Amount</th>
										<th>Date</th>
									</tr>
								</table>
							</div>
							<footer class="panel-footer">
								<div class="row">
									<div class="col-md-12 number text-right">
										<button class="btn btn-default modal-dismiss">Close</button>
									</div>
								</div>
							</footer>
					</div>
					<div id="w2-payment" class="tab-pane">
							<header class="panel-heading">
								<h2 class="panel-title">Receipt</h2>
							</header>
							<div class="panel-body">
								<table class="table table-bordered table-striped table-condensed mb-none" >
									<tr>
										<td align=right colspan=3>Company</td>
										<td colspan=2>XXXX LTE</td>
									</tr>
									<tr>
										<td align=right colspan=3> Date</td>
										<td colspan=2><input type="text" data-plugin-datepicker data-date-format="dd/mm/yyyy" data-date-start-date="0d" placeholder="Date"/></td>
									</tr>
									<tr>
										<td align=right colspan=3>Total Amount Received</td>
										<td colspan=2><input type="text" /></td>
									</tr>
									<tr>
										<td align=right colspan=3>Reference No</td>
										<td colspan=2><input type="text" /></td>
									</tr>
									<tr style="background-color:white;">
										<th class="text-center" style="vertical-align: middle;">No</th>
										<th class="text-center" style="vertical-align: middle;">Invoice Date</th>
										<th class="text-center" style="vertical-align: middle;">Invoice No</th>
										<th class="text-center" style="vertical-align: middle;">Invoice Amount</th>
										<th class="text-center">Unpaid</th>
										<th class="text-center">Received</th>
									</tr>
									<tr>
										<td>1</td>
										<td>2016-01-01</td>
										<td>INV-0001</td>
										<td align=right >10,000</td>
										<td align=right >10,000</td>
										<td><input type="number" style="width:100%;text-align:right" placeholder="Amount"/></td>
									</tr>
									<tr>
										<td>2</td>
										<td>2016-01-01</td>
										<td>INV-0002</td>
										<td align=right >15.000</td>
										<td align=right >10,000</td>
										<td><input type="number" style="width:100%;text-align:right" placeholder="Amount"/></td>
									</tr>
									<tr>
										<td align=right colspan=4>Total</td>
										<td align=right >20,000.00</td>
										<td>0</td>
									</tr>
									<tr>
										<td align=right colspan=3>Payment</td>
										<td><input type="number" placeholder="Amount"/></td>
									</tr>
								</table>
							</div>
							<footer class="panel-footer">
								<div class="row">
									<div class="col-md-12 number text-right">
										<!--button class="btn btn-primary modal-confirm">Confirm</button-->
										<button class="btn btn-default modal-dismiss">Save</button>
										<button class="btn btn-default modal-dismiss">Close</button>
									</div>
								</div>
							</footer>
					</div>
				</div>
			</div>
		</section>
	</div>
	
	<div id="modal_create_billing" class="modal-block modal-block-lg mfp-hide">
		<section class="panel">
			<header class="panel-heading">
				<h2 class="panel-title">Billing</h2>
			</header>
			<div class="panel-body">
				
				<table class="table table-bordered table-striped table-condensed mb-none">
					<tr>
						<th>Inv No</th>
						<th>INV-0001</th>
					</tr>
					<tr>
						<th>Date</th>
						<th><input type="text" readonly value="2016-01-01"/></th>
					</tr>
					<tr>
						<th>Company Name</th>
						<th>
							<select data-plugin-selectTwo class="form-control input-sm populate" style="width: 200px;">
								<optgroup label="Company">
									<option value="user 1">Company 1</option>
									<option value="user 2">Company 2</option>
									<option value="user 3">Company 3</option>
									<option value="user 3">XX 3</option>
								</optgroup>
							</select>
						</th>
					</tr>
					<tr>
						<th>Bill To</th>
						<th><textarea style="width:200px;height:120px;">Mr. XXXXX
						Orchard Road Singapore, 212312
						Phone : 1022992
						Fax : 292991
						</textarea></th>
					</tr>
				</table>
				<table class="table table-bordered table-striped table-condensed mb-none">
					<tr>
						<th>No.</th>
						<th>Service</th>
						<th>Amount</th>
					</tr>
					<tr>
						<td>1</td>
						<td>Service 1</td>
						<td>10.000</td>
					</tr>
					<tr>
						<td>2</td>
						<td>Service 2</td>
						<td>15.000</td>
					</tr>
					<tr>
						<td>3</td>
						<td>Service 3</td>
						<td>20.000</td>
					</tr>
					<tr>
						<td colspan=2>Total</td>
						<td>45.000</td>
					</tr>
				</table>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 number text-right">
						<!--button class="btn btn-primary modal-confirm">Confirm</button-->
						<a href="invoice.html" target="blank" class="btn btn-default">Save</a>
						<button class="btn btn-default modal-dismiss">Close</button>
					</div>
				</div>
			</footer>
		</section>
	</div>
	
	
	<div id="modal_add_officer0" class="modal-block modal-block-lg mfp-hide">
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
										<table class="table table-bordered table-striped table-condensed mb-none" >
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
					<div class="col-md-12 number text-right">
						<a class="modal-sizes"  href="#modalLG"><button class="btn btn-default modal-dismiss">Close</button></a>
					</div>
				</div>
			</footer>
		</section>
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
					<div class="col-md-12 number text-right">
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
					<div class="col-md-12 number text-right">
						<!--button class="btn btn-primary modal-confirm">Confirm</button-->
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
						<th><a id="add_service"><i class="fa fa-plus-circle"></i></a></th>
						
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
					<div class="col-md-12 number text-right">
						<!--button class="btn btn-primary modal-confirm">Confirm</button-->
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
				<h2 class="panel-title">Filing History <a href="#modal_setting_filing" class="filing_date modal-sizes"><i class="fa fa-gear"></i></a></h2>
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
					<div class="col-md-12 number text-right">
						<!--button class="btn btn-primary modal-confirm">Confirm</button-->
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
					<div class="col-md-12 number text-right">
						<!--button class="btn btn-primary modal-confirm">Confirm</button-->
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
					<div class="col-md-12 number text-right">
						<!--button class="btn btn-primary modal-confirm">Confirm</button-->
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
							<th><a id="add_issued_share_capital"><i class="fa fa-plus-circle"></i></a></th>
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
												<a href="<?= base_url();?>masterclient/allotment" class="btn btn-default">Allotment</a>
												<a href="<?= base_url();?>masterclient/buyback" class="btn btn-default">Buyback</a>
				<h3>B. Paid-Up Share Capital</h3>
				<table class="table table-bordered table-striped table-condensed mb-none" >
					<thead>
						<tr>
							<th>No</th>
							<th>Amount</th>
							<th>Number of Shares</th>
							<th>Currency</th>
							<th>Share Type</th>
							<th><a id="paid_issued_share_capital"><i class="fa fa-plus-circle"></i></a></th>
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
							<th rowspan =2><a id="member_issued_share_capital"><i class="fa fa-plus-circle"></i></a></th>
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
					<div class="col-md-12 number text-right">
						<!--button class="btn btn-primary modal-confirm">Confirm</button-->
						<button class="btn btn-primary modal-confirm">Save</button>
						<button class="btn btn-default modal-dismiss">Close</button>
					</div>
				</div>
			</footer>
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
					<div class="col-md-12 number text-right">
						<!--button class="btn btn-primary modal-confirm">Confirm</button-->
						<button class="btn btn-primary modal-confirm">Save</button>
						<button class="btn btn-default modal-dismiss">Close</button>
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
							<th rowspan =2><a id="Officer_Add"><i class="fa fa-plus-circle"></i></a></th>
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
					<div class="col-md-12 number text-right">
						<!--button class="btn btn-primary modal-confirm">Confirm</button-->
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
							<th>Company UEN</th>
							<th><input type="text" class="form-control" /></th>
						</tr>
						<tr>
							<th>Company Name</th>
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
					<div class="col-md-12 number text-right">
						<!--button class="btn btn-primary modal-confirm">Confirm</button-->
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
							<th>Company UEN</th>
							<th><input type="text" class="form-control"  value="XXX.XXXX.XX"/></th>
						</tr>
						<tr>
							<th>Company Name</th>
							<th><input type="text" class="form-control" value="ABC"/></th>
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
					<div class="col-md-12 number text-right">
						<!--button class="btn btn-primary modal-confirm">Confirm</button-->
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
							<th><a href="#modal_unrecieved" class="unrecieved_document modal-sizes">$10.000,-</a></th>
						</tr>
					</thead>
				</table>
					

			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 number text-right">
						<!--button class="btn btn-primary modal-confirm">Confirm</button-->
						<button class="btn btn-default modal-dismiss">OK</button>
					</div>
				</div>
			</footer>
		</section>
	</div>
	
	<div id="modal_message" class="modal-block modal-block-lg mfp-hide">
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
					<div class="col-md-12 number text-right">
						<!--button class="btn btn-primary modal-confirm">Confirm</button-->
						<button class="btn btn-default modal-dismiss">Send</button>
						<button class="btn btn-default modal-dismiss">Close</button>
					</div>
				</div>
			</footer>
		</section>
	</div>