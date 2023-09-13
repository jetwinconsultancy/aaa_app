<section class="panel">
	<div class="panel-body">
		<div class="col-md-12">
				<section class="panel">
											<header class="panel-heading">
												<h2 class="panel-title">Filing <a data-toggle="modal" data-target="#modal_history_filing" class="btn btn-default mb-sm mt-sm mr-sm  "><i class="fa fa-history"></i></a></h2>
											</header>
											<div class="panel-body">
												<table class="table table-bordered table-striped table-condensed mb-none" >
													
													<tr class="hidden">
														<td>Filing NO</td>
														<td colspan = 2>5</td>
													</tr>
													<tr>
														<td>Year End</td>
														<td colspan = 2><input type="text" data-plugin-datepicker data-date-format="dd/mm/yyyy" data-date-start-date="0d" value="02/05/2016"></td>
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
														<td><input type="file"/></td>
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
														<td><input type="file"/></td>
													</tr>
													<tr>
														<td>AGM<br/><br/><label><input type="checkbox"/> &nbsp; Dispense AGM</label></td>
														<td colspan = 2><input type="text" data-plugin-datepicker data-date-format="dd/mm/yyyy" data-date-start-date="0d" value="02/05/2016"></td>
													</tr>
													
												</table>
											</div>
											<footer class="panel-footer">
												<div class="row">
													<div class="col-md-12 number text-right">
														<!--button class="btn btn-primary modal-confirm">Confirm</button-->
														<a href="<?= base_url();?>masterclient" class="btn btn-primary">Save</a>
														
														<a href="<?= base_url();?>masterclient" class="btn btn-default">Close</a>
													</div>
												</div>
											</footer>
										</section>
									
		</div>
	</div>
	
<!-- end: page -->
</section>
<div id="modal_history_filing" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<header class="panel-heading">
					<h2 class="panel-title">History</h2>
				</header>
				<div class="panel-body">
					<table class="table table-bordered table-striped table-condensed mb-none" >
						<tr>
							<th>Filing No</th>
							<th>Year End</th>
							<th>AGM</th>
							<th>Document Related</th>
						</tr>
						<tr>
							<td>1</td>
							<td>02/05/2017</td>
							<td>02/05/2017</td>
							<td><a href="#">Sample Document</a></td>
						</tr>
						<tr>
							<td>2</td>
							<td>03/05/2018</td>
							<td>03/05/2018</td>
							<td><a href="#">Sample Document</a></td>
						</tr>
						<tr>
							<td>3</td>
							<td>04/05/2019</td>
							<td>04/05/2019</td>
							<td><a href="#">Sample Document</a></td>
						</tr>
					</table>
				</div>
				<div class="modal-footer">
						<!--button class="btn btn-primary modal-confirm">Confirm</button-->
						<button class="btn btn-primary modal-dismiss" data-dismiss="modal">OK</button>
			</footer>
		</section>
	</div>
	