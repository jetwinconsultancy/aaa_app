<section class="panel" style="margin-top: 30px;">	
<?php echo $breadcrumbs;?>						
	<div class="panel-body">
		<div class="col-md-12">
			<form id="document_toggle">
				<input type="hidden" class="form-control" id="document_master_id" name="document_master_id" value="<?=isset($document_master[0]->id)?$document_master[0]->id:''?>"/>
				<table class="table table-bordered table-striped table-condensed mb-none">
					<tr>
						<th style="padding: 10px 5px;">Document Name</th>
						<td style="padding: 10px 5px;">
                            <input type="text" class="form-control" id="document_name" name="document_name" value="<?=isset($document_master[0]->document_name)?$document_master[0]->document_name:''?>" style="width: 400px;"/>
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

				<textarea id="document_tinymce" class="tinymce" name="document_content"><?=isset($document_master[0]->document_content)?$document_master[0]->document_content:''?></textarea>
				<div id="form_document_content"></div>
			    <div class="text-right" style="margin-top: 10px;">
                    <?php echo form_submit('save_document_toggle', lang('Save'), 'class="btn btn-primary"'); ?>
					<a href="<?= base_url();?>documents" class="btn btn-default">Cancel</a>
                </div>
			</form>
		</div>
	</section>
	<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>
	<script>
		var base_url = '<?php echo base_url() ?>';
		var document_master = <?php echo json_encode(isset($document_master)?$document_master:''); ?>;

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