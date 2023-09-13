<section class="panel" style="margin-top: 30px;">
	<?php echo $breadcrumbs;?>
	<div class="panel-body">
		<div class="col-md-12">
			
			<section class="panel">
				<form id="pending_document_form">
				<div class="panel-body">
					<?php
						if($pending_document[0]->type != null)
						{
							$document_id = $pending_document[0]->id.'/'.$pending_document[0]->type;
						}
						else
						{
							$document_id = $pending_document[0]->id;
						}
					?>
					<input type="hidden" name="pending_document_id" class="form-control" id="pending_document_id" value="<?=$document_id ?>"/>
					<table class="table table-bordered table-striped table-condensed mb-none">
						<tr>
							<th>Client Name</th>
							<td>
								<div class="document-input-group" style="width: 100%;">
									<div class="validate" style="width: 50%;"><input type="text" name="client_name" class="form-control client_name" id="client_name" value="<?=(($this->data['pending_document'][0]->client_name != null)?$this->data['pending_document'][0]->client_name:$this->data['pending_document'][0]->company_name)?>"/><input type="hidden" name="client_id" class="form-control" id="client_id" value="<?=$pending_document[0]->client_id?>"/></div>
						        </div>
						        <div id="form_client_name"></div>
							</td>
						</tr>
						<tr>
							<th>Document Name</th>
							<td>
								<div class="document-input-group">
									<div class="validate" style="width: 50%;"><input type="text" name="document_name" class="form-control document_name" id="document_name" value="<?=$pending_document[0]->document_name?>"/></div>
								</div>
								<div id="form_pending_document_name"></div>
							</td>
						</tr>
						<tr>
							<th>Transaction Date</th>
							<td>
								<div style="width:400px">
								    <div style="margin-right:5px;float: left">
								        <input type="checkbox" name="document_date_checkbox" <?=$pending_document[0]->document_date_checkbox?'checked':'';?>/>
                                        <input type="hidden" name="hidden_document_date_checkbox" value=""/>
								    </div>
								    <div>
								        <div class="document-input-group" id="document_transaction_date" style="width: 220px;position: relative;display: table;border-collapse: separate;">
											<span class="input-group-addon">
												<i class="far fa-calendar-alt"></i>
											</span>
											<!-- <?=$pending_document[0]->transaction_date?> -->
											<input type="text" class="document_transaction_date form-control" name="document_transaction_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker required value="<?=$pending_document[0]->transaction_date?>" style="border-bottom-left-radius: 0;border-top-left-radius: 0;">
											<!-- <?php $now = getDate();echo $now['mday'].'/'.$now['mon']."/".$now['year'];?> -->
										</div>
										<div id="form_document_transaction_date"></div>
								    </div>
								</div>
							</td>
						</tr>
						
						
					</table>
					<br/>
					<div class="document-input-group">
						<textarea id="pending_document_tinymce" class="tinymce" name="pending_document_content"><?=$pending_document[0]->content?></textarea>
						<div id="form_pending_document_content"></div>
					</div>
				</div>
				</form>	
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-12 number text-right">
							<!--button class="btn btn-primary modal-confirm">Confirm</button-->
							<button type="submit" class="btn btn-primary" name="savePendingDocument" id="savePendingDocument">Save</button>
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
	var pending_document = <?php echo json_encode($pending_document) ?>;
	var access_right_document_module = <?php echo json_encode($document_module);?>;
	var access_right_pending_module = <?php echo json_encode($pending_module);?>;
	var access_right_all_module = <?php echo json_encode($all_module);?>;

	if(access_right_document_module == "read" || access_right_pending_module == "read" || access_right_all_module == "read")
	{
	    $('input').attr("disabled", true);

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
<script src="themes/default/assets/js/create_pending_document.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>