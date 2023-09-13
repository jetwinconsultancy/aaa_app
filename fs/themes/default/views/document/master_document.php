<section class="panel" style="margin-top: 30px;">	
<?php echo $breadcrumbs;?>						
	<div class="panel-body">
		<div class="col-md-12">
			<!-- <div class="row datatables-header form-inline">
				<div class="col-sm-12">
					<?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
						echo form_open_multipart("documents", $attrib);
						// print_r($oppicer);
					?>
						<select class="form-control" name="type_search">
							<option>Company Name</option>
							<option>Former Name</option>
							<option>UEN</option>
						</select>
						<input aria-controls="datatable-default" placeholder="Search" name="keyword" class="form-control" type="search">
							<label class="control-label">Date range</label>
								<div class="input-daterange input-group" data-plugin-datepicker>
									<span class="input-group-addon">
										<i class="far fa-calendar-alt"></i>
									</span>
									<input type="text" class="form-control" name="start">
									<span class="input-group-addon">to</span>
									<input type="text" class="form-control" name="end">
								</div>
						<input name="search" type="submit" class="btn btn-primary" tabindex="-1" value="Search"/>
					
														<?= form_close(); ?>
				</div>
			</div> -->
				<!-- <div id="buttonclick" style="display:block">
					
					<h3></h3>
					<table class="table table-bordered table-striped mb-none" id="datatable-default">
						<thead>
						<tr>
							<th>No</th>
							<th>Company Name</th>
							<th>Document</th>
							<th>Date Created</th>
							<th>Date Upload</th>
							<th>Konten</th>
							<th></th>
						</tr>
						</thead>
						<?php
							// print_r($hasil);
							$i = 1;
							foreach($hasil as $h)
							{
								// print_r($h);
								$konten = json_decode($h->content);
								// print_r($konten);
						?>
						<tr>
							<td><?=$i?></td>
							<td><?=$h->client_name?></td>
							<td><?=$h->document_name?></td>
							<td><?=$h->tgl?></td>
							<td><?php 
								if ($h->uploadedfile){
									echo '<a href="'.$h->uploadedfile.'">Preview</a>';
								} else {
							?><input type="file" name="files"/>
							<?php
								}
							?></td>
							<td><?php foreach((array) $konten->konten as $key=>$value)
							{
								echo $key." => ".$value."<br/>";
							}?></td>
							<td>
							<?php
							$generated = false;
								if ($h->upload_file) { 
									echo '<a href="'.$h->upload_file.'" target="_blank">File 1</a><br/>';
									$generated = true;
								}
								if ($h->uploadfile2) { 
									echo '<a href="'.$h->uploadfile2.'" target="_blank">File 2</a><br/>';
									$generated = true;
								}
								if ($h->uploadfile3) { 
									echo '<a href="'.$h->uploadfile3.'" target="_blank">File 3</a><br/>';
									$generated = true;
								}
								if ($h->uploadfile4) { 
									echo '<a href="'.$h->uploadfile4.'" target="_blank">File 4</a><br/>';
									$generated = true;
								}
								if ($h->uploadfile5) { 
									echo '<a href="'.$h->uploadfile5.'" target="_blank">File 5</a><br/>';
									$generated = true;
								}
								if ($h->uploadfile6) { 
									echo '<a href="'.$h->uploadfile5.'" target="_blank">File 6</a><br/>';
									$generated = true;
								}
								if ($generated==false)
								{
							?>
							<a href="generatedoc.php?id=<?=$h->id?>" target="_blank">Generate</a>
							<?php
								}
							?>
								</td>
						</tr>
						<?php
							$i++;
								// break;
							}
						?>
						
					</table>
				</div> -->
				<!--
					<div class="col-sm-12 col-md-6">
						<div id="datatable-default_length" class="dataTables_length">
							<label>
								<select class="" aria-controls="datatable-default" name="datatable-default_length">
								<option value="10">10</option>
								<option value="25">25</option>
								<option value="50">50</option>
								<option value="100">100</option>
								</select> records per page</label>
						</div>
					</div>
					-->
					<!-- <ul class="dis-tags"><li title="Id of the soo. This is useful for links and reference.">[SooID]</li><li title="The user first name.">[FirstName]</li><li title="The user last name.">[LastName]</li></ul> -->
					<!-- <ul id="keywords">
    <li>drag one</li>
    <li>drag two</li>
    <li>drag three</li>
</ul> -->


					<form id="document_toggle">
						<input type="hidden" class="form-control" id="document_master_id" name="document_master_id" value="<?=$document_master[0]->id?>"/>
						<table class="table table-bordered table-striped table-condensed mb-none">
							<tr>
								<th style="padding: 10px 5px;">Document Name</th>
								<td style="padding: 10px 5px;">
		                            <input type="text" class="form-control" id="document_name" name="document_name" value="<?=$document_master[0]->document_name?>" style="width: 400px;"/>
		                            <div id="form_document_name"></div>
								</td>
							</tr>
							<tr>
								<th style="padding: 10px 5px;">Triggered By</th>
								<td style="padding: 10px 5px;">
		                            <select class="input-sm form-control" style="text-align:right;width: 250px;" name="triggered_by" id="triggered_by"><option value="0" >Select Service</option></select>
		                            <div id="form_triggered_by"></div>
								</td>
							</tr>
						</table>

						<br/>

						<textarea id="document_tinymce" class="tinymce" name="document_content"><?=$document_master[0]->document_content?></textarea>
						<div id="form_document_content"></div>
					    <div class="text-right" style="margin-top: 10px;">
		                    <?php echo form_submit('save_document_toggle', lang('Save'), 'class="btn btn-primary"'); ?>
		                    <!-- <input type="button" value="Save" id="save" class="btn btn-primary "> -->
							<a href="<?= base_url();?>" class="btn btn-default">Cancel</a>
		                </div>
					</form>
					<!-- <input type="text" id="test" value="AAA"> -->
					<!-- <i class="fa fa-search" aria-hidden="true"></i> -->

	</div>
	</section>
	<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>
	<script>
		var base_url = '<?php echo base_url() ?>';
		var document_master = <?php echo json_encode($document_master); ?>;

		var access_right_document_module = <?php echo json_encode($document_module);?>;
		var access_right_master_module = <?php echo json_encode($master_module);?>;

		if(access_right_document_module == "read" || access_right_master_module == "read")
		{
			$('button').attr("disabled", true);
			$('input').attr("disabled", true);
			$('select').attr("disabled", true);
		}

		$("#header_our_firm").removeClass("header_disabled");
		$("#header_manage_user").removeClass("header_disabled");
		$("#header_access_right").removeClass("header_disabled");
		$("#header_user_profile").removeClass("header_disabled");
		$("#header_setting").removeClass("header_disabled");
		$("#header_dashboard").removeClass("header_disabled");
		$("#header_client").removeClass("header_disabled");
		$("#header_person").removeClass("header_disabled");
		$("#header_document").addClass("header_disabled");
		$("#header_report").removeClass("header_disabled");
		$("#header_billings").removeClass("header_disabled");
	</script>
	<script src="themes/default/assets/js/master_document.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>			
	<!-- <script>
		$("#button").click(function(){$("#buttonclick").toggle(); });
		$("#button1").click(function(){$("#buttonclick").toggle(); });
		
	</script> 
<style>
	#buttonclick .datatables-header {
		display:none;
	}




</style> -->