<section class="panel" style="margin-top: 30px;">
	<?php echo $breadcrumbs;?>
	<div class="panel-body">
		<div class="col-md-12">
			
			<section class="panel">
				<form id="pending_document_file_form">
				<div class="panel-body">

					<input type="hidden" name="pending_document_id" class="form-control" id="pending_document_id" value="<?=$pending_document_info[0]->id ?>"/>
					<input type="hidden" name="pending_document_file_id" class="form-control" id="pending_document_file_id" value="<?=$pending_document_info[0]->pending_document_file_id ?>"/>
					<table class="table table-bordered table-striped table-condensed mb-none">
						<tr>
							<th style="padding: 10px 5px;">Client Name</th>
							<td style="padding: 10px 5px;">
								<?=(($pending_document_info[0]->company_name != null)?$pending_document_info[0]->company_name: $pending_document_info[0]->client_name)?>
								
								<input type="hidden" name="doc_type" class="form-control" id="doc_type" value="<?=$pending_document_info[0]->type?>"/>
								<input type="hidden" name="company_name" class="form-control" id="company_name" value="<?=(($pending_document_info[0]->company_name != null)?$pending_document_info[0]->company_name: $pending_document_info[0]->client_name)?>"/>
								<!-- <div class="document-input-group" style="width: 100%;">
									<div class="validate" style="width: 50%;"><input type="text" name="client_name" class="form-control client_name" id="client_name" value="<?=$pending_document[0]->company_name?>"/><input type="hidden" name="client_id" class="form-control" id="client_id" value="<?=$pending_document[0]->client_id?>"/></div>
						        </div>
						        <div id="form_client_name"></div> -->
							</td>
						</tr>
						<!-- <tr>
							<th style="padding: 10px 5px;">Document Name</th>
							<td style="padding: 10px 5px;">
								<?=$pending_document_info[0]->document_name?>
							</td>
						</tr> -->
						<tr>
							<th>Received On</th>
							<td>
								<div style="width:400px">
								    
								    <div>
								        <div class="document-input-group" id="document_file_received_on" style="width: 220px;position: relative;display: table;border-collapse: separate;">
											<span class="input-group-addon">
												<i class="far fa-calendar-alt"></i>
											</span>
											<input type="text" class="document_file_received_on form-control" name="document_file_received_on" data-date-format="dd/mm/yyyy" data-plugin-datepicker required value="<?=$pending_document_info[0]->received_on?>" style="border-bottom-left-radius: 0;border-top-left-radius: 0;">
											<!-- <?php $now = getDate();echo $now['mday'].'/'.$now['mon']."/".$now['year'];?> -->
										</div>
								    </div>
								</div>
							</td>
						</tr>
						<tr>
							<th>File Upload</th>
							<td>
						            <div class="file-loading">
						                <input type="file" id="multiple_pending_document_file" class="file" name="uploadpendingdocumentfiles[]" multiple data-min-file-count="0">
						            </div>

							</td>
						</tr>
						
						
					</table>
					<br/>
					<div class="document-input-group">
						
					</div>
				</div>
				</form>	
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-12 number text-right">
							<!--button class="btn btn-primary modal-confirm">Confirm</button-->
							<button type="button" class="btn btn-primary" name="savePendingDocumentFile" id="savePendingDocumentFile">Save</button>
							<a href="<?= base_url();?>documents" class="btn btn-default">Back</a>
						</div>
					</div>
				</footer>
			</section>
						
		</div>
	</div>
	
<!-- end: page -->
</section>
<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>
<script>
	var base_url = '<?php echo base_url() ?>';
	var pending_document_files = <?php echo json_encode(isset($pending_document_info[0]->company_files) ? $pending_document_info[0]->company_files : '')?>;
	//var pending_document_files = <?php echo json_encode($pending_document_info[0]->company_files)?>;
	var pending_document_info = <?php echo json_encode(isset($pending_document_info[0]) ? $pending_document_info[0] : '')?>;

	//console.log(<?php echo json_encode($pending_document_info[0]->company_files); ?>);
	//console.log(pending_document_info["received_on"]);
	// <?php echo json_encode(isset($corp_rep_data) ? $corp_rep_data : ''); ?>;
</script>
<script src="themes/default/assets/js/add_document_file.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>