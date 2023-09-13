<section class="panel">
	<div class="panel-body">
		<div class="col-md-12">
			<div class="row datatables-header form-inline">
				<div class="col-sm-12">
					<select class="form-control">
						<option>Company Name</option>
						<option>Former Name</option>
						<option>UEN</option>
					</select>
					<input aria-controls="datatable-default" placeholder="Search" class="form-control" type="search">
						<label class="control-label">Date range</label>
							<div class="input-daterange input-group" data-plugin-datepicker>
								<span class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</span>
								<input type="text" class="form-control" name="start">
								<span class="input-group-addon">to</span>
								<input type="text" class="form-control" name="end">
							</div>
					<button name="search" type="button" id="button" class="btn btn-primary" tabindex="-1">search</button>
					<a href="<?= base_url();?>/masterclient/create_billing" class="btn btn-default">Create Invoice</a>
				</div>
			</div>
					<div id="buttonclick" style="display:none;">
						<div class="panel-body">
						<ul class="nav nav-tabs nav-justify">
							<li class="active">
								<a href="#w2-billing" data-toggle="tab" class="text-center">
									<span class="badge hidden-xs">1</span>
									Unpaid
								</a>
							</li>
							<li>
								<a href="#w2-payment1" data-toggle="tab" class="text-center">
									<span class="badge hidden-xs">2</span>
									Paid
								</a>
							</li>
						</ul>
						<div class="tab-content">
							<div id="w2-billing" class="tab-pane active">
								<table class="table table-bordered table-striped mb-none" id="datatable-default">
									<thead><tr>
										<th>No</th>
										<th>Company Name</th>
										<th>Invoice Date</th>
										<th>Invoice No</th>
										<th>Invoice</th>
										<th>Unpaid</th>
										<th></th>
									</tr>
									</thead>
									<tbody id="billing_body">
										<tr>
											<td><label> &nbsp;1</label></td>
											<td>XXX LTE</td>
											<td>01/01/2016</td>
											<td><a data-toggle="modal" data-target="#modal_invoice" class="pointer mb-sm mt-sm mr-sm  ">INV-0001</a></td>
											<td class="text-right">10,000.00</td>
											<td class="text-right">0.00</td>
											<td><a data-toggle="modal" data-target="#modal_payment" class="pointer mb-sm mt-sm mr-sm  ">Reciept</a></td>
										</tr>
										<tr>
											<td><label> &nbsp;2</label></td>
											<td>YYY PTE</td>
											<td>01/01/2016</td>
											<td><a data-toggle="modal" data-target="#modal_payment" class="pointer mb-sm mt-sm mr-sm  ">INV-0002</a></td>
											<td class="text-right">15,000.00</td>
											<td class="text-right">0.00</td>
											<td><a data-toggle="modal" data-target="#modal_payment" class="pointer mb-sm mt-sm mr-sm  ">Reciept</a></td>
										</tr>
									</tbody>
								</table>
							</div>
							<div id="w2-payment1" class="tab-pane">
								
								<table class="table table-bordered table-striped mb-none" id="datatable-default">
									<thead><tr>
										<th>No</th>
										<th>Company Name</th>
										<th>Inv Date</th>
										<th>Inv No</th>
										<th>Inv Amount</th>
										<th>Receipt Date</th>
										<th>Receipt Amount</th>
										<th>Reference No</th>
									</tr>
									</thead>
									<tbody >
									</tbody>
								</table>
							</div>
						</div>
				</div>
				
				<div id="modal_payment" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<header class="panel-heading">
								<h2 class="panel-title">Receipt</h2>
							</header>
							<div class="panel-body">
								<table class="table table-bordered table-striped table-condensed mb-none" >
									<tr>
										<td align=right colspan=3>Client</td>
										<td colspan=2>XXXX LTE</td>
									</tr>
									<tr>
										<td align=right colspan=3> Date</td>
										<td colspan=2><input type="text" data-plugin-datepicker data-date-format="dd/mm/yyyy" data-date-start-date="0d" placeholder="Date"/></td>
									</tr>
									<tr>
										<td align=right colspan=3>Total Amount Received</td>
										<td colspan=2><input type="text" class="numberdes text-right" /></td>
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
										<td><input type="text" class="numberdes" style="width:100%;text-align:right" placeholder="Amount"/></td>
									</tr>
									<tr>
										<td>2</td>
										<td>2016-01-01</td>
										<td>INV-0002</td>
										<td align=right >15.000</td>
										<td align=right >10,000</td>
										<td><input type="text" class="numberdes" style="width:100%;text-align:right" placeholder="Amount"/></td>
									</tr>
									<tr>
										<td align=right colspan=4>Total</td>
										<td align=right >20,000.00</td>
										<td align=right>0</td>
									</tr>
									<!--tr>
										<td align=right colspan=3>Payment</td>
										<td><input type="number" placeholder="Amount"/></td>
									</tr-->
								</table>
							</div>
							<div class="modal-footer">
								<button class="btn btn-primary " data-dismiss="modal">Save</button>
								<button class="btn btn-default " data-dismiss="modal">Cancel</button>
							</div>
						</div>
					</div>
				</div>
				<div id="modal_invoice" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<header class="panel-heading">
								<h2 class="panel-title">Invoice No</h2>
							</header>
							<div class="panel-body">
								<table class="table table-bordered table-striped table-condensed mb-none">
									
									<tr>
										<th>Date</th>
										<th>04/10/2017</th>
									</tr>
									<tr>
										<th>Company Name</th>
										<th>Dot Exe, LTE PTE
										</th>
									</tr>
									<tr>
										<th>Bill To</th>
										<th>Mr. XXXXX<br/>
										Orchard Road Singapore, 212312<br/>
										Phone : 1022992<br/>
										Fax : 292991<br/></th>
									</tr>
								</table>
								<br/>
								<table class="table table-bordered table-striped table-condensed mb-none">
									<tr>
										<th>No.</th>
										<th>Service</th>
										<th>Amount</th>
									</tr>
									<tr>
										<td>1</td>
										<td width="80%">Service 1</td>
										<td>SGD 10.000,00</td>
									</tr>
									<tr>
										<td>2</td>
										<td>Service 2</td>
										<td>SGD 15.000,00</td>
									</tr>
									<tr>
										<td>3</td>
										<td>Service 3</td>
										<td>SGD 20.000,00</td>
									</tr>
									<tr>
										<td colspan=2>Total</td>
										<td class="text-right">SGD 45.000</td>
									</tr>
								</table>
							</div>
							<div class="modal-footer">
								<button class="btn btn-primary " data-dismiss="modal">Save</button>
								<button class="btn btn-default " data-dismiss="modal">Cancel</button>
							</div>
						</div>
					</div>
				</div>
					</div>
				</div>
			</section>
		</div>
				<div id="" class="modal-block modal-block-lg mfp-hide">
			<section class="panel">
				<header class="panel-heading">
					<h2 class="panel-title">Payment <a href="#modal_setting_filing" class="filing_date mb-xs mt-xs mr-xs modal-sizes"><i class="fa fa-gear"></i></a></h2>
				</header>
				<div class="panel-body">
					<table class="table table-bordered table-striped table-condensed mb-none" >
						<tr>
							<th>No</th>
							<th>Date</th>
							<th>Invoice NO</th>
							<th>Amount</th>
						</tr>
						<tr>
							<td>1</td>
							<td>01/01/2016</td>
							<td>INV-0001</td>
							<td>10.000</td>
						</tr>
						<tr>
							<td>2</td>
							<td>01/01/2016</td>
							<td>INV-0002</td>
							<td>15.000</td>
						</tr>
						<tr>
							<td align=right colspan=3>Total</td>
							<td>25.000</td>
						</tr>
						<tr>
							<td align=right colspan=3>Payment</td>
							<td><input type="text" placeholder="Amount"/></td>
						</tr>
					</table>
				</div>
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-12 number text-right">
							<!--button class="btn btn-primary modal-confirm">Confirm</button-->
							<button class="btn btn-default modal-dismiss">Confirm Payment</button>
						</div>
					</div>
				</footer>
			</section>
		</div>
	</div>
</section>
</div>

	<script>
			$("#button").click(function(){$("#buttonclick").toggle(); });
			$("#button1").click(function(){$("#buttonclick").toggle(); });
		</script> 
<style>
	#buttonclick .datatables-header {
		display:none;
	}
</style>