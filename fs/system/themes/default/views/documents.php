<section class="panel">							
	<div class="panel-body">
		<div class="col-md-12">
			<div class="row datatables-header form-inline">
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
										<i class="fa fa-calendar"></i>
									</span>
									<input type="text" class="form-control" name="start">
									<span class="input-group-addon">to</span>
									<input type="text" class="form-control" name="end">
								</div>
						<input name="search" type="submit" class="btn btn-primary" tabindex="-1" value="Search"/>
					
														<?= form_close(); ?>
				</div>
			</div>
				<div id="buttonclick" style="display:block">
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