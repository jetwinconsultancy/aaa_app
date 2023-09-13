<section class="panel">
	<div class="panel-body">
		<div class="col-md-12">
			<section class="panel">
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
							<th>Currency</th>
							<th>
								<select data-plugin-selectTwo class="form-control input-sm populate" style="width: 200px;">
									<optgroup label="Company">
										<option value="user 1">SGD</option>
										<option value="user 2">USD</option>
										<option value="user 3">RM</option>
										<option value="user 3">IDR</option>
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
					<br/>
					<table class="table table-condensed mb-none">
					<tr>
						<td>
							<select class="form-control">
								<option value="">Service 1 </option>
								<option value="">Service 2 </option>
								<option value="">Service 3 </option>
								<option value="">Service 4 </option>
							</select>
						</td>
						<td style="vertical-align:middle;">
							<a  class="pointer mb-sm mt-sm mr-sm" style="font-size:16px;font-weigth:bold">Add</a>
						</td>
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
							<td><input type="text" class="numberdes text-right input-sm" value="10.000,00"/></td>
						</tr>
						<tr>
							<td>2</td>
							<td>Service 2</td>
							<td><input type="text" class="numberdes text-right input-sm" value="15.000,00"/></td>
						</tr>
						<tr>
							<td>3</td>
							<td>Service 3</td>
							<td><input type="text" class="numberdes text-right input-sm" value="20.000,00"/></td>
						</tr>
						<tr>
							<td colspan=2>Total</td>
							<td class="text-right">45.000</td>
						</tr>
					</table>
				</div>
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-12 number text-right">
							<!--button class="btn btn-primary modal-confirm">Confirm</button-->
							<a href="invoice.html" target="blank" class="btn btn-default">Save</a>
							<a href="<?= base_url();?>masterclient/unpaid_invoice" class="btn btn-default">Back</a>
						</div>
					</div>
				</footer>
			</section>
									
		</div>
	</div>
	
<!-- end: page -->
</section>