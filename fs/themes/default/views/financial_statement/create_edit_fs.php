<style type="text/css">
.btn-word {
    color: #fff;
    background-color: #007bff;
    border-color: #007bff;
}

.btn-word:hover {
    color: #fff;
    background-color: #0069d9;
    border-color: #0062cc;
}

.upload_SR, .upload_DT {
  position: relative;
  overflow: hidden;
  margin-top: 0;
}

.upload_SR input.upload_signing_report, .upload_DT input.upload_template {
  position: absolute;
  top: 0;
  right: 0;
  margin: 0;
  padding: 0;
  font-size: 20px;
  cursor: pointer;
  opacity: 0;
  filter: alpha(opacity=0);
  width: 100%;
}

/*Chrome fix*/
input::-webkit-file-upload-button {
  cursor: pointer !important;
  width: 100%;
}

svg {
  width: 25px;
  display: block;
}

.path {
  stroke-dasharray: 1000;
  stroke-dashoffset: 0;
  &.circle {
    -webkit-animation: dash .9s ease-in-out;
    animation: dash .9s ease-in-out;
  }
  &.line {
    stroke-dashoffset: 1000;
    -webkit-animation: dash .9s .35s ease-in-out forwards;
    animation: dash .9s .35s ease-in-out forwards;
  }
  &.check {
    stroke-dashoffset: -100;
    -webkit-animation: dash-check .9s .35s ease-in-out forwards;
    animation: dash-check .9s .35s ease-in-out forwards;
  }
}

@-webkit-keyframes dash {
  0% {
    stroke-dashoffset: 1000;
  }
  100% {
    stroke-dashoffset: 0;
  }
}

@keyframes dash {
  0% {
    stroke-dashoffset: 1000;
  }
  100% {
    stroke-dashoffset: 0;
  }
}

@-webkit-keyframes dash-check {
  0% {
    stroke-dashoffset: -100;
  }
  100% {
    stroke-dashoffset: 900;
  }
}

@keyframes dash-check {
  0% {
    stroke-dashoffset: -100;
  }
  100% {
    stroke-dashoffset: 900;
  }
}

</style>

<!-- <?php
	$now = getDate();
	if ($this->session->userdata('transaction_company_code') && $this->session->userdata('transaction_company_code') != '')
	{
		$transaction_company_code = $this->session->userdata('transaction_company_code');
		//echo json_encode($company_code);
	} 
	else 
	{
		$transaction_company_code = 'company_'.$now[0];
		$this->session->set_userdata('transaction_company_code', $transaction_company_code);
	}
?> -->

<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>

<div class="header_between_all_section">
<section class="panel">
	<?php echo $breadcrumbs;?>
	<div class="panel-body">
		<div class="col-md-12">
			<div id="modal_fs" class="">
				<!-- <?php $attrib = array('class' => 'form-horizontal transaction_form', 'data-toggle' => 'validator', 'role' => 'form', 'id' => 'transaction_form');
								echo form_open_multipart("financial_statement/lodge_transaction", $attrib);
							?> -->
				<!-- <form> -->
				<section id="wFS_document">
					<div class="panel-body">
						<div class="wizard-progress wizard-progress-lg">
							<div class="steps-progress">
								<div class="progress-indicator"></div>
							</div>
							<ul class="wizard-steps" id="fs_tab">
								<!-- <li class="active">
									<a href="#FS_info" data-toggle="tab"><span>1</span>Information</a>
								</li> -->
								<!-- <li class="active">
									<a href="#FS_setting" data-toggle="tab"><span>1</span>Company Particular</a>
								</li> -->
								<!-- <li>
									<a href="#FS_director_statement" data-toggle="tab"><span>2</span>Directors' Interest in Shares</a>
								</li> -->
								<!-- <li>
									<a href="#FS_indepent_aud_report" data-toggle="tab"><span>3</span>Firm's Report</a>
								</li> -->
								<!-- <li>
									<a href="#FS_doc_setup" data-toggle="tab"><span>4</span>Document Setup</a>
								</li> -->
								<!-- <li>
									<a href="#FS_ntfs" data-toggle="tab"><span>5</span>Notes to FS</a>
								</li> -->
								<li>
									<a href="#FS_corporate_information" data-toggle="tab"><span>1</span>Corporate <br/> information</a>
								</li>
								<li>
									<a href="#FS_financial_statement" data-toggle="tab"><span>2</span>Financial <br/> Statement</a>
								</li>
								<li>
									<a href="#FS_generate_report" data-toggle="tab"><span>3</span>Generate <br/> Report</a>
								</li>
								<li>
									<a href="#FS_signing_report" data-toggle="tab"><span>4</span>Signing <br/> Report</a>
								</li>
							</ul>
						</div>

						<div class="tab-content">
							<input type="hidden" id="client_id" name="client_id" value="<?=isset($client_id)?$client_id: ''?>" />
							<input type="hidden" id="company_code" name="company_code" value="<?=isset($company_code)?$company_code: ''?>" />
							<input type="hidden" id="fs_company_info_id" name="fs_company_info_id" value="<?=isset($fs_report_details->id)?$fs_report_details->id: ''?>" />

							<!-- <div id="FS_info" class="tab-pane active"> -->
								<!-- <input type="hidden" class="form-control" id="transaction_master_id" name="transaction_master_id" value="<?=$transaction_master_id?>"/> -->
								<!-- <input type="hidden" class="form-control" id="transaction_code" name="transaction_code" value="<?=$transaction_code?>"/> -->
								<!-- <input type="hidden" class="form-control trans_company_code" id="company_code" name="company_code" value="<?=isset($fs_report_details->company_code)?$fs_report_details->company_code: ''?>"/> -->
								
								<!-- <div class="form-group">
	                                <div style="width: 100%;">
	                                    <div style="width: 25%;float:left;margin-right: 20px;">
	                                        <label>Firm :</label>
	                                    </div>
	                                    <div style="width: 65%;float:left;margin-bottom:5px;">
	                                        <div class="input-group" style="width: 20%;">
	                                        	<?php
    												echo form_dropdown('interview_company_name', $firm_list, isset($interview_detail['firm'])?$interview_detail['firm']: '', 'class="firm-select" style="width:200%"');
    											?>
	                                        </div> -->
	                                        <!-- <div class="input-group style="width: 30%">
	                                        	<label style="color:red;" id="err_interview_company_name"></label>
	                                        </div> -->
	                                    <!-- </div>
	                                </div>
	                            </div> -->

								<!-- <div class="form-group fs_firm">
									<label class="col-sm-4 control-label" for="Firm">Client Name</label>
									<div class="col-sm-4">
                                    	<?php
											echo form_dropdown('interview_company_name', $firm_list, isset($fs_report_details->company_code)?$fs_report_details->company_code: '', 'id="firm_dp" class="firm-select"');
										?>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-4 control-label">Registration No</label>
									<div class="col-sm-3">
										<label id="FS_reg_no"></label>
									</div>
								</div> -->
							<!-- </div> -->

							<!-- <div id="FS_setting" class="tab-pane active"></div> -->

							<!-- <div id="FS_director_statement" class="tab-pane"></div> -->

							<!-- <div id="FS_indepent_aud_report" class="tab-pane"></div> -->

							<!-- <div id="FS_doc_setup" class="tab-pane"></div> -->

							<!-- <div id="FS_ntfs" class="tab-pane"></div> -->

							<div id="FS_corporate_information" class="tab-pane active"></div>

							<div id="FS_financial_statement" class="tab-pane"></div>

							<div id="FS_generate_report" class="tab-pane">
								<div id="fs_doc_checklist">
									<div class="form-group">
										<div class="col-sm-12" style="text-align: center;">
											<h3>Documents are INCOMPLETED!</h3>
											<span>Please check and complete the documents before generate the report.</span>
										</div>
									</div>

									<table class="table">
										<thead>
											<tr>
												<th>Document name</th>
												<th>Status</th>
											</tr>
										</thead>
										<tbody id="fs_doc_checklist_list"></tbody>	
									</table>
								</div>

								<div id="fs_generate_report_link" style="display: none;">
									<div class="form-group">
										<div class="col-sm-12" style="text-align: center;">
											<h3>Report is ready to be generated!</h3>
											<span>Click button to generate report.</span>
										</div>
									</div>

									<!-- Written by 16/04/2020 for reupload document template -->
									<div class="form-group">
										<div class="col-sm-12" style="text-align: center;">
											<span>Last document: 
												<?php 
													if(!empty($last_document))
													{
														// echo '<a style="cursor:pointer;" onclick="open_link(\'' . $current_report_template_used['filepath'] . '\')">' . $last_document . '</a>';
														echo $last_document;
													}
													else
													{
														echo 'Not available';
													}
												?>
											</span>
											<i id="fs_report_template_info" class="fa fa-info-circle" aria-hidden="true" style="font-size: 12pt; margin: 10px; cursor:pointer;" data-name="fs_report_template_info" data-toggle="tooltip" data-trigger="hover" data-original-title="Click to remove or replace current document template" onclick="update_fs_report_template()"></i>
										</div>
									</div>

									<?php
										$display_none = '';
										$disabled 	  = '';
										$checked  	  = '';
										$style 	  	  = '';

										if(is_null($generate_without_tag))
										{
											$display_none = 'style="display:none;"';
											$disabled 	  = 'disabled';
											$style	  	  = 'style="cursor: not-allowed;"';
										}
										else
										{
											if($generate_without_tag == true)
											{
												$checked = 'checked';
											}
										}
									?>

									<div class="form-group" <?php echo $display_none; ?>>
										<div class="col-sm-12" style="text-align: center;">
											<?php
												$first_generate_report = 0;

												if(is_null($generate_without_tag))
												{
													$first_generate_report = 1;
												}

												echo '<input type="hidden" class="first_generate_report" value="' . $first_generate_report . '">';
											?>
											<input type="hidden" class="fs_settings_id" value="<?=$fs_settings_id;?>">
											<span style="padding-right: 10px;">Generate without tag:</span>
											<label class="switch"><input class="in_use_switch" type="checkbox" <?php echo $disabled; ?> <?php echo $checked; ?>><span class="slider round" <?php echo $style; ?>></span></label>
										</div>
									</div>

									<div class="form-group">
										<div class="col-sm-12" style="text-align: center;">
											<!-- <input type="button" class="btn btn-danger" value="Generate" onclick="export_front_page_pdf()" style="margin-bottom: 20px; margin-top: 20px;"> -->
											<!-- <input type="button" class="btn btn-danger" value="PDF" data-toggle="modal" data-target="#myModal" style="margin-bottom: 20px; margin-top: 20px;"> -->
											<input type="button" class="btn btn-word" value="WORD" data-toggle="modal" data-target="#myModal_word" style="margin-bottom: 20px; margin-top: 20px;">
										</div>
									</div>
								</div>

								<!-- <?php if ($status != 3 && $status != null) { ?>
									<div style="margin-bottom: 20px;">
										<span class="help-block">
										* Do you want to cancel this transaction? <a href="javascript:void(0)" class="btn btn-primary cancel_by_user" id="cancel_by_user"style="padding: 3px 5px; font-size: 10px">YES</a>
									</span>
									</div>
								<?php } ?> -->
							</div>

							<div id="FS_signing_report" class="tab-pane">
								<div class="form-group">
									<div class="col-sm-12" style="text-align: center;">
										<h3>Upload Signing Report</h3>
										<span>Click button to select file and upload.</span>
									</div>
								</div>

								<div class="form-group">
									<div class="col-sm-12" style="text-align: center; margin-top: 20px;">
										<?php	
											$fs_signing_report_id = 0;

											if(!empty($fs_signing_report[0]['id']))
											{
												$fs_signing_report_id = $fs_signing_report[0]['id'];
											}

											echo '<input type="hidden" id="fs_signing_report_id" value="' . $fs_signing_report_id . '">';

											if(isset($fs_signing_report[0]['file_name']))
											{
												echo '<a style="cursor:pointer;" onclick="open_link(\'documents/Signing Report/'. $fs_signing_report[0]["file_name"] .'\')">' . $fs_signing_report[0]["file_name"] . '</a>';
											}	
											else
											{
												echo '<strong>No file is uploaded.</strong>';
											}
										?>
									</div>
								</div>

								<div class="form-group">
									<div class="col-sm-12" style="text-align: center; margin-bottom: 20px; margin-top: 20px;">
										<div class="upload_SR btn btn-primary">
									        <span>Upload file</span>
									        <input type="file" class="upload_signing_report">
								      	</div>
										<!-- <input type="file" name="upload_signing_report" class="btn btn-primary" value="Upload file" style="margin-bottom: 20px; margin-top: 20px;"> -->
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="panel-footer">
						<ul class="pager wizard">
				            <li class="previous"><a href="javascript: void(0);">Go To Previous Page</a></li>
				            <li class="next" style="float: right"><a href="javascript: void(0);">Go To Next Page</a></li>
				           <!--  <li class="other_next" style="float: right"><a href="<?= base_url();?>masterclient/view_transfer/<?=$company_code?>">Done</a></li> -->
				            <li class="cancel_transaction" style="float: right; margin-right: 10px;"><a href="<?= base_url();?>financial_statement" id="cancel_buyback">Cancel</a></li>
				        </ul>
					</div>
				</section>
				<!-- <?= form_close(); ?> -->
			<!-- </form> -->
			</div>
									
		</div>
	</div>

	<!-- Modal to display upload or remove current document -->
	<div class="modal fade" id="update_fs_report_template_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog" style="width:70%">
	    <div class="modal-content">
	      <div class="modal-header">
	        <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
	        <h4 class="modal-title" id="myModalLabel">Report Template</h4>
	      </div>
	      <div class="modal-body">
	      	<div class="form-group" style="text-align: left;">
	      		<div class="col-sm-4">Current report template used:</div>
	      		<div class="col-sm-8">
	      			<?php
	      				if(!empty($current_report_template_used['rt_id']))
	      				{
	      					echo '<a style="cursor: pointer" onclick="download_doc_template(' . $fs_report_details->id . ')">' . $current_report_template_used['filename'] . '</a>' .
								 '<a style="cursor: pointer; margin-left: 10px;" onclick="remove_report_template(' . $current_report_template_used['rt_id'] . ')" data-toggle="tooltip" data-trigger="hover" data-original-title="Delete report template file"><i class="fa fa-trash-alt"></i></a>';
	      				}
	      				else
	      				{
	      					echo '<span>' . $current_report_template_used['filename'] . '</span>';
	      				}
	      			?>
				</div>
			</div>
			<div class="form-group" style="text-align: left;">
				<div class="col-sm-4">Upload new template:</div>
				<div class="col-sm-8">
					<div class="upload_DT btn btn-primary">
				        <span>Upload</span>
				        <input type="file" class="upload_template" accept=".doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" onclick="this.value=null">
			      	</div>
			      	<p style="color:red;">** Previous template will be replaced after upload new template.</p>
				</div>
			</div>
	        <!-- <span>Replace current document template</span> -->
	      </div>
	      <div class="modal-footer">
	        <button type="button" data-dismiss="modal" class="btn">Cancel</button>
	      </div>
	    </div>
	  </div>
	</div>
	<!-- END OF Modal to display upload or remove current document -->

	<!-- Display confirmation box to delete report template -->
	<!-- <div class="modal fade" id="confirmation_delete_rt" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <strong>ACCOUNT CODE LIST</strong>
	        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	      </div>
	      <div class="modal-body"></div>
	      <div class="modal-footer">
	        <button type="button" data-dismiss="modal" class="btn">Cancel</button>
	        <button id="btn_edit_sub" type="button" class="btn btn-primary">Insert</button>
	      </div>
	    </div>
	  </div>
	</div> -->
	<!-- END OF Display confirmation box to delete report template -->

	<div class="loading" id='loadingTransaction' style="">Loading&#8230;</div>
	<div class="loading" id='loadingWizardMessage' style='display:none'>Loading&#8230;</div>

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel">Pre-Print Letter Head</h4>
          </div>
          <div class="modal-body">
            	Do you want to print Pre-printed Letterhead document?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" onclick="export_front_page_pdf(0)">No</button>
            <button type="button" class="btn btn-primary" onclick="export_front_page_pdf(1)">Yes</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="myModal_word" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel">Draft Report</h4>
          </div>
          <div class="modal-body">
            	Do you want to print a Draft Report?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" onclick="export_doc_in_word(0)">No</button>
            <button type="button" class="btn btn-primary" onclick="export_doc_in_word(1)">Yes</button>
          </div>
        </div>
      </div>
    </div>
<!-- end: page -->
</section>
</div>

<script>
	$('.firm-select').select2();

	var fs_report_details   = '<?php echo json_encode($fs_report_details);?>';
	var fs_state_cash_flows = <?php echo json_encode(isset($fs_state_cash_flows)?$fs_state_cash_flows:"")?>;
	var firm_id 		    = '<?php echo $this->session->userdata('firm_id');?>';
	var firm_details   	    = <?php echo isset($firm_details)?$firm_details:''; ?>;

	if(firm_details != '')
	{
		var firm_reg_no	  = firm_details[0]["registration_no"];

		$('#FS_reg_no').text(firm_reg_no);
	}

	toastr.options = {
      	"positionClass": "toast-bottom-right"
    }

    $("#header_our_firm").removeClass("header_disabled");
	$("#header_manage_user").removeClass("header_disabled");
	$("#header_access_right").removeClass("header_disabled");
	$("#header_user_profile").removeClass("header_disabled");
	$("#header_setting").removeClass("header_disabled");
	$("#header_dashboard").removeClass("header_disabled");
	$("#header_transaction").addClass("header_disabled");
	$("#header_client").removeClass("header_disabled");
	$("#header_person").removeClass("header_disabled");
	$("#header_document").removeClass("header_disabled");
	$("#header_report").removeClass("header_disabled");
	$("#header_billings").removeClass("header_disabled");
	// $(document).ready(function() {
	// 	// go to the latest tab, if it exists:
	    
	//     $('#loadingTransaction').hide();
	// });
	
	// if(fs_report_details == "")
	// {
		
	// }

	$("#FS_generate_report .in_use_switch").change(function() 
	{
		var checkbox = $(this);
		var checked = this.checked;

		// checkbox.prop('checked', checkbox.prop("checked"));

		var fs_settings_id = $('#FS_generate_report .fs_settings_id').val();

		$.ajax({
			type: "POST",
			url: "financial_statement/update_selected_generate_docs_without_tags",
			data: {"fs_settings_id": fs_settings_id, "checked":checked, "fs_company_info_id": fs_company_info_id},
			dataType: "json",
			success: function(response)
			{
				if(response.status)
				{
					if(checked)
					{
						checkbox.prop('checked', true);
					}
					else
					{
						checkbox.prop('checked', false);
					}

					toastr.success(response.msg, 'Success');

					// update fs_settings_id
					$('#FS_generate_report .fs_settings_id').val(response.fs_settings_id);
				}
				else
				{
					if(checked) // change back to the status
					{
						checkbox.prop('checked', false);
					}
					else
					{
						checkbox.prop('checked', true);
					}

					toastr.error(response.msg, 'Error');
				}
			}
		});
	});

	function open_link(filename)
	{
		window.location.href = '<?= base_url();?>' + filename;
	}
	
</script>

<script type="text/javascript">
	var client_signing_info = <?php echo json_encode($client_signing_info);?>;
</script>

<script src="themes/default/assets/js/intlTelInput.js" /></script>
<script src="themes/default/assets/js/defaultCountryIp.js" /></script>
<script src="themes/default/assets/js/utils.js" /></script>

<!-- for thousand separator -->
<script src="composer_plugin/node_modules/numeral/numeral.js"></script>	

<script src="themes/default/assets/js/financial_statement/functions.js" charset="utf-8"></script>

<script src="themes/default/assets/js/financial_statement/partial_company_particular.js" charset="utf-8"></script>
<script src="themes/default/assets/js/financial_statement/financial_statement.js" charset="utf-8"></script>
<!-- <script src="themes/default/assets/js/financial_statement/aud_report.js" charset="utf-8"></script> -->
<script src="themes/default/assets/js/financial_statement/export_pdf.js" charset="utf-8"></script>

<!-- Notes to financial statement list -->
<script src="themes/default/assets/js/financial_statement/partial_ntfs_layout.js" charset="utf-8"></script>

<!-- Statement of Detailed Profit or Loss -->
<script src="themes/default/assets/js/financial_statement/state_detailed_pro_loss.js" charset="utf-8"></script> 

<!-- Statement of Comprehensive Income -->
<script src="themes/default/assets/js/financial_statement/partial_state_comp_income.js" charset="utf-8"></script>
<script src="themes/default/assets/js/financial_statement/fs_notes.js" charset="utf-8"></script>

<!-- Statement of Cash Flows -->
<script src="themes/default/assets/js/financial_statement/partial_state_cash_flows.js" charset="utf-8"></script>

<!-- Statement of Changes in Equity -->
<script src="themes/default/assets/js/financial_statement/partial_state_changes_in_equity.js" charset="utf-8"></script>

<!-- Statement of Financial Position -->
<script src="themes/default/assets/js/financial_statement/partial_financial_position.js" charset="utf-8"></script>

<!-- <script src="composer_plugin/node_modules/handsontable-pro/dist/handsontable.full.min.js"></script> -->
<!-- <link href="composer_plugin/node_modules/handsontable-pro/dist/handsontable.full.min.css" rel="stylesheet" media="screen"> -->
<!-- <script src="themes/default/assets/js/financial_statement/fs_notes.js" charset="utf-8"></script> -->

<!-- <script src="themes/default/assets/js/edit_transaction.js" charset="utf-8"></script> -->
<!-- incorp_company -->
<!-- <script src="themes/default/assets/js/transaction_company_info.js" charset="utf-8"></script>	
<script src="themes/default/assets/js/transaction_officer_info.js" charset="utf-8"></script>
<script src="themes/default/assets/js/transaction_controller.js" charset="utf-8"></script>		
<script src="themes/default/assets/js/transaction_filing.js" charset="utf-8"></script>
<script src="themes/default/assets/js/transaction_billing.js" charset="utf-8"></script>
<script src="themes/default/assets/js/transaction_member_tab.js" charset="utf-8"></script>
<script src="themes/default/assets/js/transaction_previous_secretarial_tab.js" charset="utf-8"></script> -->
<!-- appoint_new_director -->
<!-- <script src="themes/default/assets/js/transaction_appoint_new_director.js" charset="utf-8"></script> -->
<!-- resign_director -->
<!-- <script src="themes/default/assets/js/transaction_resign_director.js" charset="utf-8"></script> -->
<!-- change_reg_ofis_address -->
<!-- <script src="themes/default/assets/js/transaction_reg_office_address.js" charset="utf-8"></script> -->
<!-- appt_and_resign_auditor -->
<!-- <script src="themes/default/assets/js/transaction_appoint_new_auditor.js" charset="utf-8"></script> -->
<!-- change_company_name -->
<!-- <script src="themes/default/assets/js/transaction_change_company_name.js" charset="utf-8"></script> -->
<!-- change_biz_activity -->
<!-- <script src="themes/default/assets/js/transaction_change_biz_activity.js" charset="utf-8"></script> -->
<!-- change_fye -->
<!-- <script src="themes/default/assets/js/transaction_change_FYE.js" charset="utf-8"></script> -->
<!-- share_allotment -->
<!-- <script src="themes/default/assets/js/transaction_share_allotment.js" charset="utf-8"></script> -->
<!-- share_transfer -->
<!-- <script src="themes/default/assets/js/transaction_share_transfer.js" charset="utf-8"></script> -->
<!-- agm_ar -->
<!-- <script src="themes/default/assets/js/transaction_agm_ar.js" charset="utf-8"></script> -->
<!-- opening_bank_acc -->
<!-- <script src="themes/default/assets/js/transaction_opening_bank_acc.js" charset="utf-8"></script> -->
<!-- Incorporation Subsidiary -->
<!-- <script src="themes/default/assets/js/transaction_incorporation_subsidiary.js" charset="utf-8"></script> -->
<!-- Issue Director Fee -->
<!-- <script src="themes/default/assets/js/transaction_issue_director_fee.js" charset="utf-8"></script> -->
<!-- Issue Dividend -->
<!-- <script src="themes/default/assets/js/transaction_issue_dividend.js" charset="utf-8"></script> -->
<!-- Strike Off -->
<!-- <script src="themes/default/assets/js/transaction_strike_off.js" charset="utf-8"></script> -->
<!-- appoint_new_secretarial -->
<!-- <script src="themes/default/assets/js/transaction_appoint_new_secretarial.js" charset="utf-8"></script> -->