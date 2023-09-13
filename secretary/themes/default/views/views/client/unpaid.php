<section class="panel">
	<div class="panel-body">
		<div class="col-md-12">
			<section class="panel">
				<div class="panel-body">
							<header class="panel-heading">
								<h2 class="panel-title">Billing Client 1</h2>
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
											<td style="padding:8px;"><label><input type="checkbox">&nbsp;1</label></td>
											<td>2016-01-01</td>
											<td>INV-0001</td>
											<td align=right>10,000</td>
											<td align=right	>0</td>
										</tr>
										<tr>
											<td style="padding:8px;"><label><input type="checkbox">&nbsp;2</label></td>
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
										<!-- href="<?= base_url();?>/masterclient/create_billing" class="btn btn-default">Create Invoice</a-->
										<a href="<?= base_url();?>billings" class="btn btn-primary">Receipt</a>
										<a href="<?= base_url();?>masterclient" class="btn btn-default">Close</a>
									</div>
								</div>
							</footer>
					</div>
				</div>
			</section>
		
									
		</div>
	</div>
	
<!-- end: page -->
</section>