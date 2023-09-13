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
								<li>
									<a href="#transaction_document" data-toggle="tab"><span>3</span>Compilation</a>
								</li>
								<li>
									<a href="#transaction_confirm" data-toggle="tab"><span>4</span>Confirmation</a>
								</li>
							</ul>
						</div>
		

						<div class="tab-content">
							<div id="transaction_trans" class="tab-pane active">
								<!-- <?php echo $transaction_code;?> --><!-- <?php echo $transaction_master_id;?>  -->
								<input type="hidden" class="form-control" id="transaction_master_id" name="transaction_master_id" value="<?=$transaction_master_id?>"/>
								<input type="hidden" class="form-control" id="transaction_code" name="transaction_code" value="<?=$transaction_code?>"/>
								<input type="hidden" class="form-control trans_company_code" id="company_code" name="company_code" value="<?=$transaction_company_code?>"/>
								<div class="form-group">
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
										<input type="text" class="form-control uen" name="uen" id="uen" value="<?=$registration_no?>" style="text-transform:uppercase"/>
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
										<input type="text" class="form-control client_name input_client_name" name="client_name" id="client_name" value="<?=$client_name?>" disabled style="display:none"/>
									</div>
								</div>

							</div>
							<div id="transaction_data" class="tab-pane">
								
											
										

							</div>
							<div id="transaction_document" class="tab-pane">
								<form id="generate_document" method="post">
									<table class="table table-bordered table-striped table-condensed mb-none" id="datatable-document">
										<thead>
											<tr>
												<th style="width: 100px; text-align: center;">No.</th>
												<th>Document</th>
												
											</tr>
										</thead>
										<tbody id="document_body">
											<!-- <?php
											$i = 1;
												foreach($document as $a)
												{
													// print_r($a);
													echo '<tr>
															<td>'.$i.'</td>
															<td><span href="" style="height:45px;font-weight:bold;" class="edit_currency  pointer amber" data-id="'.$a->id.'" data-info="'.$a->document_name.'">'.$a->document_name.'</span></td>
															
														</tr>';
														
																
													$i++;
												}
											?> -->
											
										</tbody>
										
									</table>
									<div class="form-group">
										<div class="col-sm-12">
											<input type="button" class="btn btn-primary submitGenerateDocument" id="submitGenerateDocument" value="Download Document" style="float: right; margin-bottom: 30px; margin-top: 20px;">
										</div>
									</div>
									</form>
									
								

							</div>
							<div id="transaction_confirm" class="tab-pane">
								
								<?php if ($status != 3 && $status != null && $transaction_task_id != 11 && $transaction_task_id != 29 && $transaction_task_id != 30) { ?>
									<div style="margin-bottom: 20px;">
										<span class="help-block">
										* Do you want to cancel this transaction? <a href="javascript:void(0)" class="btn btn-primary cancel_by_user" id="cancel_by_user"style="padding: 3px 5px; font-size: 10px">YES</a>
									</span>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>
					
					<div class="panel-footer">
						<ul class="pager wizard">
				            <li class="previous hidden"><a href="javascript: void(0);">Go To Previous Page</a></li>
				            <li class="next" style="float: right"><a href="javascript: void(0);">Go To Next Page</a></li>
				           <!--  <li class="other_next" style="float: right"><a href="<?= base_url();?>masterclient/view_transfer/<?=$company_code?>">Done</a></li> -->
				            <li class="cancel_transaction" style="float: right; margin-right: 10px;"><a href="<?= base_url();?>transaction" id="cancel_buyback">Cancel Transaction</a></li>
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
<script>
	var transaction_master = <?php echo json_encode($transaction_master);?>;
	var transaction_company_code = <?php echo json_encode($transaction_company_code)?>;

	//console.log(transaction_master);

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
</script>
<script src="themes/default/assets/js/intlTelInput.js?v=30eee4fc8d1b59e4584b0d39edfa2082" /></script>
<script src="themes/default/assets/js/defaultCountryIp.js?v=30eee4fc8d1b59e4584b0d39edfa2082" /></script>
<script src="themes/default/assets/js/utils.js?v=30eee4fc8d1b59e4584b0d39edfa2082" /></script>

<script src="themes/default/assets/js/edit_transaction.js?v=30eee4fc8d1b59e4584b0d39edfa2083" charset="utf-8"></script>
<!-- incorp_company -->
<script src="themes/default/assets/js/transaction_company_info.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>	
<script src="themes/default/assets/js/transaction_officer_info.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
<script src="themes/default/assets/js/transaction_controller.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>		
<script src="themes/default/assets/js/transaction_filing.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
<script src="themes/default/assets/js/transaction_billing.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
<script src="themes/default/assets/js/transaction_member_tab.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
<script src="themes/default/assets/js/transaction_previous_secretarial_tab.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
<!-- appoint_new_director -->
<script src="themes/default/assets/js/transaction_appoint_new_director.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
<!-- resign_director -->
<script src="themes/default/assets/js/transaction_resign_director.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
<!-- change_reg_ofis_address -->
<script src="themes/default/assets/js/transaction_reg_office_address.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
<!-- appt_and_resign_auditor -->
<script src="themes/default/assets/js/transaction_appoint_new_auditor.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
<!-- change_company_name -->
<script src="themes/default/assets/js/transaction_change_company_name.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
<!-- change_biz_activity -->
<script src="themes/default/assets/js/transaction_change_biz_activity.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
<!-- change_fye -->
<script src="themes/default/assets/js/transaction_change_FYE.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
<!-- share_allotment -->
<script src="themes/default/assets/js/transaction_share_allotment.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
<!-- share_transfer -->
<script src="themes/default/assets/js/transaction_share_transfer.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
<!-- agm_ar -->
<script src="themes/default/assets/js/transaction_agm_ar.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
<!-- opening_bank_acc -->
<script src="themes/default/assets/js/transaction_opening_bank_acc.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
<!-- Incorporation Subsidiary -->
<script src="themes/default/assets/js/transaction_incorporation_subsidiary.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
<!-- Issue Director Fee -->
<script src="themes/default/assets/js/transaction_issue_director_fee.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
<!-- Issue Dividend -->
<script src="themes/default/assets/js/transaction_issue_dividend.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
<!-- Strike Off -->
<script src="themes/default/assets/js/transaction_strike_off.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
<!-- appoint_new_secretarial -->
<script src="themes/default/assets/js/transaction_appoint_new_secretarial.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
<!-- service_proposal -->
<script src="themes/default/assets/js/transaction_service_proposal.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>
<!-- engagement_letter -->
<script src="themes/default/assets/js/transaction_engagement_letter.js?v=30eee4fc8d1b59e4584b0d39edfa2082" charset="utf-8"></script>