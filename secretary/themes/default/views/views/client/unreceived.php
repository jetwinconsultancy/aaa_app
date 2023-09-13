<section class="panel">
	<div class="panel-body">
		<div class="col-md-12">
			<section class="panel">
				<div class="panel-body">
					<h3>Client 1</h3>	
					<div class="tabs">
						<ul class="nav nav-tabs nav-justify">
							<li class="active">
								<a href="#w2-unreceived" data-toggle="tab" class="text-center">
									<span class="badge hidden-xs">1</span>
									Unreceived Document
								</a>
							</li>
							<li>
								<a href="#w2-received" data-toggle="tab" class="text-center">
									<span class="badge hidden-xs">2</span>
									Received Document
								</a>
							</li>
						</ul>
						<div class="tab-content">
							<div id="w2-unreceived" class="tab-pane active">
								<header class="panel-heading">
									<h2 class="panel-title">Unreceived Document</h2>
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
											<td>User</td>
											<td><a href="#">Upload</a>&nbsp;&nbsp;&nbsp;<a href="#">Download</a></td>
										</tr>
										<tr>
											<td>2</td>
											<td>Memorandum</td>
											<td>02/05/2016</td>
											<td>User</td>
											<td><a href="#">Upload</a>&nbsp;&nbsp;&nbsp;<a href="#">Download</a></td>
										</tr>
									</table>
								</div>
								<footer class="panel-footer">
									<div class="row">
										<div class="col-md-12 number text-right">
											<!--button class="btn btn-primary modal-confirm">Confirm</button-->
										<a href="<?= base_url();?>masterclient" class="btn btn-default">Close</a>
										</div>
									</div>
								</footer>
							</div>
							<div id="w2-received" class="tab-pane">
								<header class="panel-heading">
									<h2 class="panel-title">Received Document</h2>
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
											<td>User</td>
											<td><a href="#">Download</a></td>
										</tr>
										<tr>
											<td>2</td>
											<td>Memorandum</td>
											<td>02/05/2016</td>
											<td>User</td>
											<td><a href="#">Download</a></td>
										</tr>
									</table>
								</div>
								<footer class="panel-footer">
									<div class="row">
										<div class="col-md-12 number text-right">
											<!--button class="btn btn-primary modal-confirm">Confirm</button-->
											<a href="<?= base_url();?>masterclient" class="btn btn-default">Close</a>
										
										</div>
									</div>
								</footer>
							</div>
						</div>
					</div>
				</div>
			</section>

		</div>
	</div>
	
<!-- end: page -->
</section>
									