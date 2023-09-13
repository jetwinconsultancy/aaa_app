<?php
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
?>
<div class="header_between_all_section">
<section class="panel">
	<?php echo $breadcrumbs;?>
	<div class="panel-body">
		<div class="col-md-12">
			<div id="modal_transaction" class="">
				<?php $attrib = array('class' => 'form-horizontal transaction_form', 'data-toggle' => 'validator', 'role' => 'form', 'id' => 'transaction_form');
								echo form_open_multipart("transaction/lodge_transaction", $attrib);
							?>	
				<section id="wTransaction">
					
					<div class="panel-body">
						<div class="wizard-progress wizard-progress-lg">
							<div class="steps-progress">
								<div class="progress-indicator"></div>
							</div>
							<ul class="wizard-steps" id="transaction_tab">
								<li class="active">
									<a href="#transaction_trans" data-toggle="tab"><span>1</span>Transaction</a>
								</li>
								<li>
									<a href="#transaction_data" data-toggle="tab"><span>2</span>Information</a>
								</li>
								<!-- Quotation -->
								<li class="for_incorp for_other_services" style="display: none">
									<a href="#transaction_create_billing" data-toggle="tab"><span>3</span>Invoice</a>
								</li>
								<li>
									<a href="#transaction_confirm" data-toggle="tab"><span class="confirmation_number">3</span>Confirmation</a>
								</li>
								<li class="transaction_document_tab">
									<a href="#transaction_document" data-toggle="tab"><span class="compilation_number">4</span>Compilation</a>
								</li>
								<li class="hide_completion_tab">
									<a href="#transaction_completion" data-toggle="tab"><span class="completion_number">5</span>Completion</a>
								</li>
							</ul>
						</div>
		

						<div class="tab-content">
							<div id="transaction_trans" class="tab-pane active">
								<input type="hidden" class="form-control" id="transaction_master_id" name="transaction_master_id" value="<?= isset($transaction_master_id) ? $transaction_master_id : ''?>"/>
								<input type="hidden" class="form-control" id="transaction_code" name="transaction_code" value="<?=isset($transaction_code) ? $transaction_code : ''?>"/>
								<input type="hidden" class="form-control trans_company_code" id="company_code" name="company_code" value="<?=$transaction_company_code?>"/>
								<div class="form-group transaction_task_section">
									<label class="col-sm-5 control-label" for="Transaction">Service</label>
									<div class="col-sm-3">
										
										<select class="transaction_task" style="width: 100%;" name="transaction_task" id="transaction_task">
											<option value="0">Select Service</option>
										</select>
									</div>
								</div>

								<div class="form-group uen_section">
									<label class="col-sm-5 control-label" for="UEN">Registration No</label>
									<div class="col-sm-3">
										<input type="text" class="form-control uen" name="uen" id="uen" value="<?=isset($registration_no) ? $registration_no : ''?>" style="text-transform:uppercase"/>
									</div>
								</div>
								<div class="form-group client_section" style="display:none">
									<label class="col-sm-5 control-label" for="client">Client</label>
									<div class="col-sm-3">
										<select class="form-control client_type" style="width: 100%;" name="client_type" id="client_type" disabled>
											<option value="0">Select Client</option>
										</select>
									</div>
								</div>

								<div class="form-group client_name_section" style="display:none">
									<label class="col-sm-5 control-label" for="client">Client Name</label>
									<div class="col-sm-3">
										<select class="form-control client_name dropdown_client_name" style="width: 100%; display:none" name="client_name" id="client_name" disabled>
											<option value="0">Select Client Name</option>
										</select>
										<input type="text" class="form-control client_name input_client_name" name="client_name" id="client_name" value="<?=isset($client_name) ? $client_name : ''?>" disabled style="display:none"/>
									</div>
								</div>

							</div>
							<div id="transaction_data" class="tab-pane">
							</div>
							<div id="transaction_create_billing" class="tab-pane for_incorp for_other_services" style="display: none">
							</div>
							<div id="transaction_document" class="tab-pane">
								<div class="col-sm-4">
									<select class="form-control" name="change_condition" id="change_condition" style="width: 200px !important; float: left; display: none;" title="This is for V2.">
										<option value="0">Please Select Condition</option>
										<option value="pre">Pre-Incorporation</option>
										<option value="pre">Post-Incorporation</option>
									</select>
								</div>
								<div class="col-sm-8">
									<select class="form-control" name="change_version" id="change_version" style="width: 200px !important; float: right;">
										<option value="0">Please Select Version</option>
										<option value="null">Not Generated</option>
									</select>
								</div>
								<div class="col-sm-4"><h3>Compilation</h3></div>
								
								<!-- <form id="generate_document"> -->
								<div class="col-sm-12">
								<table class="table table-bordered table-striped table-condensed mb-none" id="datatable-document" style="width:100%">
									<thead>
										<tr>
											<th style="width: 50px !important; text-align: center;">No.</th>
											<th>Document</th>
											<th style="width: 200px !important;">Version</th>
										</tr>
									</thead>
									<tbody id="document_body">
									</tbody>
								</table>
							</div>
								<div class="form-group">
									<div class="col-sm-12">
										<input type="button" class="btn btn-primary submitGenerateDocument" id="submitGenerateDocument" value="Download Document" style="float: right; margin-bottom: 30px; margin-top: 20px;">
									</div>
								</div>
								<!-- </form> -->
							</div>
							<div id="transaction_completion" class="tab-pane">
								<?php if((!$Individual && $Individual == true) || (!$Individual && $Individual == null && !$Client)) {?>
									<div>
										<!-- <h3>Follow Up History</h3> -->
										<button type="button" class="collapsible"><span style="font-size: 2.4rem;">Follow Up History</span></button>
										<div id="company_info" class="incorp_content">
											<div class="create_follow_up_form_section" style="margin-top: 20px;">
												<div class="col-sm-12">
													<a class="create_follow_up amber" href="javascript:void(0)" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;float: right;" data-original-title="Create Follow Up" ><i class="fa fa-plus-circle amber" style="font-size:16px;height:45px;"></i> Create Follow Up</a>
												</div>
											</div>
											<div class="follow_up_form_section" id="follow_up_form_section" style="margin-top: 20px;display: none;">
												<!-- <?php echo form_open_multipart('', array('id' => 'follow_up_form', 'enctype' => "multipart/form-data")); ?> -->
													<input type="hidden" class="form-control valid follow_up_history_id" id="follow_up_history_id" name="follow_up_history_id" value="">
													<div class="form-group">
														<label class="col-sm-2" for="w2-username">Date of Follow Up: </label>
														<div class="col-sm-4">
															<div class="input-group" style="width: 200px;">
																<span class="input-group-addon">
																	<i class="far fa-calendar-alt"></i>
																</span>
																<input type="text" class="form-control valid date_of_follow_up" id="date_todolist" name="date_of_follow_up" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" required="" value="" placeholder="DD/MM/YYYY">
															</div>
														</div>
														<label class="col-sm-1" for="w2-username">Time: </label>
														<div class="col-sm-4">
															<div class="input-group date" style="width: 200px;">
																<span class="input-group-addon">
																	<i class="far fa-clock"></i>
																</span>
																<input type="text" class="form-control valid time_of_follow_up" id="datetimepicker5" name="time_of_follow_up" required="" value="" placeholder="">
															</div>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-2" for="w2-username">Remark: </label>
														<div class="col-sm-8">
															<textarea class="form-control follow_up_remark" id="follow_up_remark" name="follow_up_remark" rows="3"></textarea>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-2" for="w2-username">Outcome: </label>
														<div class="col-sm-8">
															<select class="form-control follow_up_outcome" style="width: 500px;" name="follow_up_outcome" id="follow_up_outcome">
																<option value="0">Select Outcome</option>
															</select>
														</div>
													</div>
													<div class="action_part" style="display: none;">
														<div class="form-group">
															<label class="col-sm-2" for="w2-username">Next Follow Up Action: </label>
															<div class="col-sm-8">
																<select class="form-control follow_up_action" style="width: 200px;" name="follow_up_action" id="follow_up_action" disabled>
																	<option value="0">Select Action</option>
																</select>
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-2" for="w2-username">Next Follow Up Date: </label>
															<div class="col-sm-4">
																<div class="input-group" style="width: 200px;">
																	<span class="input-group-addon">
																		<i class="far fa-calendar-alt"></i>
																	</span>
																	<input type="text" class="form-control valid next_follow_up_date" id="date_todolist" name="next_follow_up_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" required="" value="" placeholder="DD/MM/YYYY" disabled>
																</div>
															</div>
															<label class="col-sm-1" for="w2-username">Time: </label>
															<div class="col-sm-4 form-group">
																<div class="input-group date" style="width: 200px;">
																	<span class="input-group-addon">
																		<i class="far fa-clock"></i>
																	</span>
																	<input type="text" class="form-control valid next_follow_up_time" id="datetimepicker6" name="next_follow_up_time" required="" value="" placeholder="" disabled>
																</div>
															</div>
														</div>
													</div>
													<div class="form-group">
														<div class="col-sm-12">
															<button type="button" class="btn btn-primary save_follow_up" id="save_follow_up" style="float: right">Save</button>
														</div>
													</div>
												<!-- <?= form_close(); ?> -->
											</div>
											<table class="table table-bordered table-striped table-condensed" style="margin-bottom: 20px; width: 1000px">
												<thead>
													<tr>
														<th style="text-align: center;width: 180px">ID</th>
														<th style="text-align: center;width: 230px">Current Follow Up Date & Time</th>
														<th style="text-align: center;width: 237px">Next Follow Up Date & Time</th>
														<th style="text-align: center;width: 200px">Follow Up By</th>
														<th style="width: 100px"></th>
													</tr>
												</thead>

												<tbody id="follow_up_table">									
												</tbody>
											</table>
										</div>
									</div>
								<?php } ?>
								<div>
									<button type="button" class="collapsible"><span style="font-size: 2.4rem;">Document</span></button>
									<div id="company_info" class="incorp_content">
										<a href="javascript: void(0);" class="btn btn-primary" id="refresh_document_list" class="refresh_document_list" style="float: right; margin-top: 20px; margin-bottom: 20px;">Refresh</a>
										<table class="table table-bordered table-striped" id="datatable-all_doc" style="margin-top: 20px; margin-bottom: 20px; width: 100%;">
											<thead>
												<tr>
													<th style="text-align: center;width:250px !important;padding-right:2px !important;padding-left:2px !important;">Client</th>
													<th style="text-align: center;width:150px !important;padding-right:2px !important;padding-left:2px !important;">Transaction ID</th>
													<th style="text-align: center;width:310px !important;padding-right:2px !important;padding-left:2px !important;">Transaction Name</th>
													<th style="text-align: center;width:150px !important;padding-right:2px !important;padding-left:2px !important;">Created On</th>
													<th style="text-align: center;width:150px !important;padding-right:2px !important;padding-left:2px !important;">Created By</th>
													<th style="text-align: center;width:30px !important;padding-right:2px !important;padding-left:2px !important;">Received On</th>
													<th style="text-align: center;width:150px !important;padding-right:2px !important;padding-left:2px !important;">Received By</th>
												</tr>
											</thead>
											<tbody id="all_document_body">
											</tbody>
										</table>
									</div>
								</div>
								<div>
									<button type="button" class="collapsible"><span style="font-size: 2.4rem;">Logdement Info</span></button>
										<div id="company_info" class="incorp_content">
										<div id="lodgement_info_form">
											<div class="form-group for_incorp" style="margin-top: 20px;">
												<label class="col-sm-3">Client Code: </label>
												<div class="col-sm-8">
													<input style="width: 200px;" type="text" style="text-transform:uppercase" class="form-control" maxlength="20" id="client_code" name="client_code">
												</div>
											</div>
											<div class="form-group for_incorp" style="display: none">
												<label class="col-sm-3">Registration No:</label>
												<div class="col-sm-8">
													<div style="width: 200px;">
														<input type="text" class="form-control" name="registration_no" id="registration_no">
													</div>
												</div>
											</div>

											<?php if ($transaction_task_id != 29 && $transaction_task_id != 8 && $transaction_task_id != 9 && $transaction_task_id != 29 && $transaction_task_id != 30 && $transaction_task_id != 31) { ?>
												<div class="form-group effec_date" style="margin-top: 20px; display: none">
													<label class="col-sm-3">Logdement Date:</label>
													<div class="col-sm-8">
														<div class="input-group" style="width: 200px;">
															<span class="input-group-addon">
																<i class="far fa-calendar-alt"></i>
															</span>
															<input type="text" class="form-control valid lodgement_date effective_date" id="date_todolist" name="lodgement_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="DD/MM/YYYY" disabled="true">
														</div>
													</div>
												</div>
											<?php } ?>  

												<div class="form-group date_of_update_cont" style="margin-top: 20px; display: none">
													<label class="col-sm-3">Date of the confirmation received:</label>
													<div class="col-sm-8">
														<div class="div_radio_corp_confirm_completion">
								                        	<label><input type="radio" id="is_confirm_registrable_controller" name="confirm_registrable_controller" value="yes" class="check_is_confirm_completion" data-information="yes"/>&nbsp;&nbsp;Yes</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
															<label><input type="radio" id="not_confirm_registrable_controller" name="confirm_registrable_controller" value="no" class="check_is_confirm_completion" data-information="no" checked="true" />&nbsp;&nbsp;No</label>
															<input type="hidden" id="radio_confirm_registrable_controller" value="no" name="radio_confirm_registrable_controller"/>
														</div>
														<div class="input-group div_date_of_conf_received" style="width: 200px; display: none">
															<span class="input-group-addon">
																<i class="far fa-calendar-alt"></i>
															</span>
															<input type="text" class="form-control valid date_of_the_conf_received" id="date_todolist" name="date_of_the_conf_received" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="DD/MM/YYYY" disabled="true">
														</div>
													</div>
												</div>
												<div class="form-group date_of_update_entry" style="margin-top: 20px; display: none">
													<label class="col-sm-3">Date of entry/update:</label>
													<div class="col-sm-8">
														<div class="input-group" style="width: 200px;">
															<span class="input-group-addon">
																<i class="far fa-calendar-alt"></i>
															</span>
															<input type="text" class="form-control valid date_of_entry_or_update" id="date_todolist" name="date_of_entry_or_update" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="DD/MM/YYYY" disabled="true">
														</div>
													</div>
												</div>
			
											<div class="form-group accept_date" style="margin-top: 20px; display: none">
												<label class="col-sm-3" for="w2-DS2">Acceptance Date:</label>
												<div class="col-sm-8">
													<div class="input-group mb-md" style="width: 200px;">
														<span class="input-group-addon">
															<i class="far fa-calendar-alt"></i>
														</span>
														<input type="text" class="form-control valid lodgement_date accepted_date" id="date_todolist" name="lodgement_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="DD/MM/YYYY" disabled="true">
													</div>
												</div>
											</div>
											<div class="form-group" style="margin-top: 20px;">
												<label class="col-sm-3" for="w2-DS2">Status:</label>
												<div class="col-sm-8 mb-md">
													<select class="form-control tran_status" style="width: 500px;" name="tran_status" id="tran_status">
														<option value="0">Select Status</option>
													</select>
												</div>
												<div class="reason_cancellation_textfield" style="display: none">
													<label class="col-sm-3" for="reason_cancellation">Reason Cancellation Transaction:</label>
													<div class="col-sm-8">
														<textarea class="form-control cancellation_reason" rows="4" cols="50" name="cancellation_reason"></textarea>
													</div>
												</div>
											</div>
											<div class="form-group">
												<div class="col-sm-12">
													<button type="button" class="btn btn-primary save_lodgement_info" id="save_lodgement_info" style="float: right">Save Draft</button>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div id="transaction_confirm" class="tab-pane">
								<!-- <?php if (isset($status) && isset($transaction_task_id)) {  if ($status != 3 && $status != null && $transaction_task_id != 1 && $transaction_task_id != 2 && $transaction_task_id != 3 && $transaction_task_id != 4 && $transaction_task_id != 5 && $transaction_task_id != 6 && $transaction_task_id != 7 && $transaction_task_id != 8 && $transaction_task_id != 9 && $transaction_task_id != 10 && $transaction_task_id != 11&& $transaction_task_id != 12 && $transaction_task_id != 15 && $transaction_task_id != 24 && $transaction_task_id != 28 && $transaction_task_id != 29 && $transaction_task_id != 30 && $transaction_task_id != 31 && $transaction_task_id != 32  && $transaction_task_id != 33) { ?>
									<div style="margin-bottom: 20px;">
										<span class="help-block">
										* Do you want to cancel this transaction? <a href="javascript:void(0)" class="btn btn-primary cancel_by_user" id="cancel_by_user" style="padding: 3px 5px; font-size: 10px">YES</a>
										</span>
									</div>
								<?php } }?> -->
							</div>
						</div>
					</div>
					
					<div class="panel-footer">
						<ul class="pager wizard">
				            <li class="previous hidden"><a href="javascript: void(0);">Go To Previous Page</a></li>
				            <li class="next" style="float: right"><a href="javascript: void(0);">Go To Next Page</a></li>
				            <li class="cancel_transaction" style="float: right; margin-right: 10px;"><a href="<?= base_url();?>transaction" id="cancel_buyback">Exit Transaction</a></li>
				        </ul>

					</div>	
				
				</section>
				<?= form_close(); ?>
			</div>
									
		</div>
	</div>
	<div class="loading" id='loadingTransaction'>Loading&#8230;</div>
	<div class="loading" id='loadingWizardMessage' style='display:none'>Loading&#8230;</div>
<!-- end: page -->
</section>
</div>
<div class="loading" id='loadingClient'>Loading&#8230;</div>
<script>
	var transaction_master = <?php echo json_encode(isset($transaction_master) ? $transaction_master : null);?>;
	var transaction_company_code = <?php echo json_encode(isset($transaction_company_code) ? $transaction_company_code : null)?>;
	var user_base_individual = <?php echo json_encode(isset($Individual) ? $Individual : null)?>;
	var user_base_client = <?php echo json_encode(isset($Client) ? $Client : null)?>;

	toastr.options = {
      "positionClass": "toast-bottom-right"
    }
    var base_url = '<?php echo base_url() ?>';

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
</script>
<script src="themes/default/assets/js/intlTelInput.js?v=30eee4fc8d1b59e4584b0d39edfa2082" /></script>
<script src="themes/default/assets/js/defaultCountryIp.js?v=30eee4fc8d1b59e4584b0d39edfa2082" /></script>
<script src="themes/default/assets/js/utils.js?v=30eee4fc8d1b59e4584b0d39edfa2082" /></script>

<script src="themes/default/assets/js/edit_transaction.js?v=344222fdfsdfdrw32323233w1323" charset="utf-8"></script>
<!-- incorp_company -->
<script src="themes/default/assets/js/transaction_company_info.js?v=6434fdfsdfdrw32323233w1323" charset="utf-8"></script>	
<script src="themes/default/assets/js/transaction_officer_info.js?v=6434fdfsdfdrw32323233w1323" charset="utf-8"></script>
<script src="themes/default/assets/js/transaction_controller.js?v=6434fdfsdfdrw32323233w1323" charset="utf-8"></script>		
<script src="themes/default/assets/js/transaction_filing.js?v=6434fdfsdfdrw32323233w1323" charset="utf-8"></script>
<script src="themes/default/assets/js/transaction_billing.js?v=6434fdfsdfdrw32323233w1323" charset="utf-8"></script>
<script src="themes/default/assets/js/transaction_member_tab.js?v=6434fdfsdfdrw32323233w1323" charset="utf-8"></script>
<script src="themes/default/assets/js/transaction_previous_secretarial_tab.js?v=6434fdfsdfdrw32323233w1323" charset="utf-8"></script>
<!-- appoint_new_director -->
<script src="themes/default/assets/js/transaction_appoint_new_director.js?v=6434fdfsdfdrw32323233w1323" charset="utf-8"></script>
<!-- resign_director -->
<script src="themes/default/assets/js/transaction_resign_director.js?v=6434fdfsdfdrw32323233w1323" charset="utf-8"></script>
<!-- change_reg_ofis_address -->
<script src="themes/default/assets/js/transaction_reg_office_address.js?v=6434fdfsdfdrw32323233w1323" charset="utf-8"></script>
<!-- appt_and_resign_auditor -->
<script src="themes/default/assets/js/transaction_appoint_new_auditor.js?v=6434fdfsdfdrw32323233w1323" charset="utf-8"></script>
<!-- change_company_name -->
<script src="themes/default/assets/js/transaction_change_company_name.js?v=6434fdfsdfdrw32323233w1323" charset="utf-8"></script>
<!-- change_biz_activity -->
<script src="themes/default/assets/js/transaction_change_biz_activity.js?v=6434fdfsdfdrw32323233w1323" charset="utf-8"></script>
<!-- change_fye -->
<script src="themes/default/assets/js/transaction_change_FYE.js?v=6434fdfsdfdrw32323233w1323" charset="utf-8"></script>
<!-- share_allotment -->
<script src="themes/default/assets/js/transaction_share_allotment.js?v=7434fdfsdfdrw32323233w1323" charset="utf-8"></script>
<!-- share_transfer -->
<script src="themes/default/assets/js/transaction_share_transfer.js?v=6434fdfsdfdrw32323233w1323" charset="utf-8"></script>
<!-- agm_ar -->
<script src="themes/default/assets/js/transaction_agm_ar.js?v=75554fdfsdfdrw32323233w1323" charset="utf-8"></script>
<!-- opening_bank_acc -->
<script src="themes/default/assets/js/transaction_opening_bank_acc.js?v=6434fdfsdfdrw32323233w1323" charset="utf-8"></script>
<!-- Incorporation Subsidiary -->
<script src="themes/default/assets/js/transaction_incorporation_subsidiary.js?v=6434fdfsdfdrw32323233w1323" charset="utf-8"></script>
<!-- Issue Director Fee -->
<script src="themes/default/assets/js/transaction_issue_director_fee.js?v=7734fdfsdfdrw32323233w1323" charset="utf-8"></script>
<!-- Issue Dividend -->
<script src="themes/default/assets/js/transaction_issue_dividend.js?v=7734fdfsdfdrw32323233w1323" charset="utf-8"></script>
<!-- Strike Off -->
<script src="themes/default/assets/js/transaction_strike_off.js?v=6434fdfsdfdrw32323233w1323" charset="utf-8"></script>
<!-- appoint_new_secretarial -->
<script src="themes/default/assets/js/transaction_appoint_new_secretarial.js?v=6434fdfsdfdrw32323233w1323" charset="utf-8"></script>
<!-- service_proposal -->
<script src="themes/default/assets/js/transaction_service_proposal.js?v=6434fdfsdfdrw32323233w1323" charset="utf-8"></script>
<!-- engagement_letter -->
<script src="themes/default/assets/js/transaction_engagement_letter.js?v=6434fdfsdfdrw32323233w1323" charset="utf-8"></script>
<!-- every_services_billing -->
<script src="themes/default/assets/js/transaction_services_create_billing.js?v=6434fdfsdfdrw32323233w1323" charset="utf-8"></script>
<!-- ml_quarterly_statements -->
<script src="themes/default/assets/js/transaction_ml_quarterly_statements.js?v=6434fdfsdfdrw32323233w1323" charset="utf-8"></script>
<!-- update_register_of_nominee_director -->
<script src="themes/default/assets/js/transaction_update_register_of_nominee_director.js?v=6434fdfsdfdrw32323233w1323" charset="utf-8"></script>
<!-- omp_grant -->
<script src="themes/default/assets/js/transaction_omp_grant.js?v=6434fdfsdfdrw32323233w1323" charset="utf-8"></script>
<!-- purchase_common_seal -->
<script src="themes/default/assets/js/transaction_purchase_common_seal.js?v=6434fdfsdfdrw32323233w1323" charset="utf-8"></script>
<script type="text/javascript">
	document.onreadystatechange = function() {
	    if (document.readyState == "complete") {
			$('#loadingClient').hide();
		}
	};
</script>
