<section class="panel" style="margin-top: 30px;">	
<?php echo $breadcrumbs;?>						
	<div class="panel-body">
		<div class="col-md-12">
			

			<form id="document_reminder">
				<input type="hidden" class="form-control" id="document_reminder_id" name="document_reminder_id" value="<?=isset($document_reminder[0]->id)?$document_reminder[0]->id:''?>"/>

				<table class="table table-bordered table-striped table-condensed mb-none">
					<tr>
						<th style="padding: 10px 5px;">Reminder Tag</th>
						<td style="padding: 10px 5px;">
                            <select class="form-control" style="text-align:right;width: 250px;" name="reminder_tag" id="reminder_tag"><option value="0" >Select Reminder Tag</option></select>
                            <div id="form_reminder_tag"></div>
						</td>
					</tr>
					<tr>
						<th style="padding: 10px 5px;">Reminder Name</th>
						<td style="padding: 10px 5px;">
                            <input type="text" class="form-control" id="reminder_name" name="reminder_name" value="<?=isset($document_reminder[0]->reminder_name)?$document_reminder[0]->reminder_name:''?>" style="width: 400px;"/>
                            <div id="form_reminder_name"></div>
						</td>
					</tr>
					<tr>
						<th>Active</th>
						<td>
							<div style="width:400px">
							    <div style="margin-right:5px;float: left">
							        <input type="checkbox" name="document_active_checkbox" <?=isset($document_reminder[0]->document_active_checkbox)?'checked':'';?>/>
                                    <input type="hidden" name="hidden_document_active_checkbox" value=""/>
							    </div>
							</div>
						</td>
					</tr>
					<tr style="display: none">
						<th style="padding: 10px 5px;">Set On</th>
						<td style="padding: 10px 5px;">
							<div>
	                            <label style="float:left;margin-right: 2px;">
	                                <input type="text" class="form-control" id="before_year_end" name="before_year_end" value="<?=isset($document_reminder[0]->before_year_end)?$document_reminder[0]->before_year_end:''?>" style="width: 100px;">
	                            </label>
	                            <div style="width: 15%; float: left;margin-left: 10px;margin-top: 5px;"> days before year end</div>
                            </div>
                            <br/>
                            <br/>
                            <div id="form_before_year_end"></div>
						</td>
					</tr>
					<tr style="display: none">
						<th style="padding: 10px 5px;"></th>
						<td style="padding: 10px 5px;">
							<div>
	                            <label style="float:left;margin-right: 2px;">
	                                <input type="text" class="form-control" id="before_due_date" name="before_due_date" value="<?=isset($document_reminder[0]->before_due_date)?$document_reminder[0]->before_due_date:''?>" style="width: 100px;">
	                            </label>
	                            <div style="width: 15%; float: left;margin-left: 10px;margin-top: 5px;"> days before due date</div>
                            </div>
                            <br/>
                            <br/>
                            <div id="form_before_due_date"></div>
						</td>
					</tr>
					<!-- <tr>
						<th style="padding: 10px 5px;">Send To</th>
						<td style="padding: 10px 5px;">
                            <textarea id="send_to" class="form-control send_to" name="send_to" style="width:400px;height:80px;"><?=$document_reminder[0]->send_to?></textarea>
                            
                            <div id="form_send_to"></div>
						</td>
					</tr> -->
					<tr style="display: none">
						<th style="padding: 10px 5px;">Start On</th>
						<td style="padding: 10px 5px;">
                            <div class="document-input-group" id="start_on" style="width: 220px;position: relative;display: table;border-collapse: separate;">
								<span class="input-group-addon">
									<i class="far fa-calendar-alt"></i>
								</span>
								<input type="text" class="start_on form-control" name="start_on" data-date-format="dd/mm/yyyy" data-plugin-datepicker value="<?=isset($document_reminder[0]->start_on)?$document_reminder[0]->start_on:''?>" style="border-bottom-left-radius: 0;border-top-left-radius: 0;">

							</div>
                            
                            <div id="form_start_on"></div>
						</td>
					</tr>
				</table>
				<br/>


               

				<textarea id="reminder_document_tinymce" class="tinymce" name="reminder_document_content"><?=isset($document_reminder[0]->document_content)?$document_reminder[0]->document_content:''?></textarea>
				<div id="form_reminder_document_content"></div>
			    <div class="text-right" style="margin-top: 10px;">
                    <button type="submit" class="btn btn-primary" name="saveReminderDocument" id="saveReminderDocument">Save</button>
                    <!-- <input type="button" value="Save" id="save" class="btn btn-primary "> -->
					<a href="<?= base_url();?>documents" class="btn btn-default">Cancel</a>
                </div>
			</form>


	</div>
	</section>
	<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>
	<script>
		var base_url = '<?php echo base_url() ?>';
		var document_reminder = <?php echo json_encode(isset($document_reminder)?$document_reminder:''); ?>;

		var access_right_document_module = <?php echo json_encode($document_module);?>;
		var access_right_reminder_module = <?php echo json_encode($reminder_module);?>;

		if(access_right_document_module == "read" || access_right_reminder_module == "read")
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
	<script src="themes/default/assets/js/reminder_document.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>			
	