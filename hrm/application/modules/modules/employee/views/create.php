<link rel="stylesheet" href="<?= base_url() ?>node_modules/chosen-js/chosen.css" />
<script src="<?= base_url() ?>node_modules/chosen-js/chosen.jquery.js"></script>
<script src="<?= base_url() ?>node_modules/bootstrap-datepicker/dist/js/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css" />
<link rel="stylesheet" href="<?= base_url()?>application/css/plugin/intlTelInput.css" />
<link rel="stylesheet" href="<?= base_url() ?>node_modules/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.css" />
<script src="<?= base_url() ?>node_modules/bootstrap-switch/dist/js/bootstrap-switch.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>application/css/plugin/toastr.min.css" />
<script src="<?= base_url() ?>application/js/toastr.min.js"></script>
<script src="<?= base_url() ?>node_modules/bootbox/bootbox.min.js"></script>
<script src="<?= base_url() ?>application/js/custom/applicant_profile_image.js"></script>

<!-- Theme CSS -->
<link rel="stylesheet" href="<?= base_url() ?>application/css/theme.css" />
<link rel="stylesheet" href="<?= base_url() ?>application/css/modules/applicant/form.css" />

<section role="main" class="content_section" style="margin-left:0;">
	<?php echo $breadcrumbs;?>
	<section class="panel" style="margin-top: 30px;">
		<form id="employee" method="POST" enctype="multipart/form-data"> 
		<div class="panel-body">
			<div class="col-md-12">
				<div class="tabs">				
					<ul class="nav nav-tabs nav-justify">
						<?php 
							$staff_data = isset($staff)?$staff:FALSE;
							//echo json_encode($staff_data);
							if($staff_data == FALSE)
							{
						?>
							<li class="active check_state" data-information="particulars">
								<a href="#w2-particulars" data-toggle="tab" class="text-center">
									<span class="badge hidden-xs">1</span>
									Particulars
								</a>
							</li>
							<li class="check_state" data-information="settings">
								<a href="#w2-settings" data-toggle="tab" class="text-center">
									<span class="badge hidden-xs">2</span>
									General
								</a>
							</li>
							
						<?php 
							}else{
						?>
							<li class="active check_state" data-information="settings">
								<a href="#w2-settings" data-toggle="tab" class="text-center">
									<span class="badge hidden-xs">1</span>
									General
								</a>
							</li>
							<li class="check_state" data-information="particulars">
								<a href="#w2-particulars" data-toggle="tab" class="text-center">
									<span class="badge hidden-xs">2</span>
									Particulars
								</a>
							</li>
						<?php 
							}
						?>
						<li class="check_state" data-information="family">
							<a href="#w2-family" data-toggle="tab" class="text-center">
								<span class="badge hidden-xs">3</span>
								Family
							</a>
						</li>
						<?php 
							// if($staff_data == TRUE)
							// {
						?>
						<li class="check_state" data-information="history">
							<a href="#w2-history" data-toggle="tab" class="text-center">
								<span class="badge hidden-xs">4</span>
								History
							</a>
						</li>
						<?php 
							// }
						?>
			
						<li class="check_state <?php if($Manager && ($staff[0]->id != $this->user_id)){echo 'disabled';}else{echo '';}?>" data-information="payslip">
							<a href="<?php if($Manager && ($staff[0]->id != $this->user_id)){echo '#';}else{echo '#w2-payslip';}?>" data-toggle="tab" class="text-center">
								<span class="badge hidden-xs">5</span>
								Compensation
							</a>
						</li>
					
					</ul>
					
					<div class="tab-content clearfix">
							<?php 
								$staff_data = isset($staff)?$staff:FALSE;
								if($staff_data == FALSE)
								{
							?>
								<div id="w2-settings" class="tab-pane">
							<?php 
								}else{
							?>
								<div id="w2-settings" class="tab-pane active">
							<?php 
								}
							?>
								<input type="hidden" name="previous_staff_status" class="previous_staff_status" value="<?php echo isset($staff[0]->employee_status_id)?$staff[0]->employee_status_id:''?>">
								<input type="hidden" name="previous_status_date" class="previous_status_date" value="<?php echo isset($staff[0]->status_date)?$staff[0]->status_date:''?>">
								<div class="form-group">
	                                <div style="width: 100%;">
	                                    <div style="width: 25%;float:left;margin-right: 20px;">
	                                        <label>Status :</label>
	                                    </div>
	                                    <div style="width: 65%;float:left;">
	                                        <div style="width: 30%;float:left;">
	                                        	<?php 
	                                        		echo form_dropdown('staff_status', $status_list, isset($staff[0]->employee_status_id)?$staff[0]->employee_status_id:'', 'class="form-control general employee_status"');
	                                        	?>
	                                        </div>
	                                        <div style="width: 30%;float:left;">
					                        	<button  type="button" class="btn btn_purple resign_details_btn" style="margin-left:5px;">Resign</button>
					                        </div>
	                                    </div>
	                                </div>
	                            </div>
	                            <div class="form-group date_of_letter_div" style="display: none;">
    		                    	<div style="width: 100%;">
    			                    	<div style="width: 25%;float:left;margin-right: 20px;">
    			                            <label>Date of letter :</label>
    			                        </div>
    			                    	<div style="width: 30%;float: left;">
    			                            <div class="input-group date datepicker" data-provide="datepicker">
    			                            	<div class="input-group-addon">
											        <span class="far fa-calendar-alt"></span>
											    </div>
    			                            	<input type="text" class="form-control general" id="date_of_letter" name="date_of_letter" data-date-format="d F Y" value="<?=isset($staff[0]->date_of_letter)?date("d F Y",strtotime($staff[0]->date_of_letter)):''?>">
    										</div>
    			                        </div>
    			                    </div>
    		                    </div>
	                            <div class="form-group effective_date_div" style="display: none;">
    		                    	<div style="width: 100%;">
    			                    	<div style="width: 25%;float:left;margin-right: 20px;">
    			                            <label>Confirmation Date :</label>
    			                        </div>
    			                    	<div style="width: 30%;float: left;">
    			                            <div class="input-group date datepicker" data-provide="datepicker">
    			                            	<div class="input-group-addon">
											        <span class="far fa-calendar-alt"></span>
											    </div>
    			                            	<input type="text" class="form-control general" id="status_date" name="status_date" data-date-format="d F Y" value="<?=isset($staff[0]->status_date)?date("d F Y",strtotime($staff[0]->status_date)):''?>">
    										</div>
    			                        </div>
    			                    </div>
    		                    </div>
    		                    <?php 
	                            	echo
	                            		'<div class="form-group">
					                    	<div style="width: 100%;">
						                    	<div style="width: 25%;float:left;margin-right: 20px;">
						                            <label>AWS eligibility :</label>
						                        </div>
						                    	<div style="width: 200px;float:left;margin-bottom:5px;">
						                    		<div>
												        <input type="checkbox" name="staff_aws_given" />
												        <input type="hidden" name="hidden_staff_aws_given" value="'. (isset($staff[0]->aws_given)? $staff[0]->aws_given : 0) .'"/>
												    </div>
						                    	</div>
						                    </div>
					                    </div>';
	                            ?>
	                            <div class="form-group for_non_pass_holder" style="display: none">
	                                <div style="width: 100%;">
	                                    <div style="width: 25%;float:left;margin-right: 20px;">
	                                        <label>CPF (Employee) :</label>
	                                    </div>
	                                    <div style="width: 65%;float:left;margin-bottom:5px;">
	                                        <div class="input-group" style="width: 40%;">
	                                        	<input type="number" step="0.01" class="form-control general" id="staff_cpf_employee" name="staff_cpf_employee" value="<?=isset($staff[0]->cpf_employee)?$staff[0]->cpf_employee:'' ?>" style="width: 100px;border-radius: 4px;"/>
	                                        	<label style="margin:3%">%</label>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
	                            <div class="form-group for_non_pass_holder" style="display: none">
	                                <div style="width: 100%;">
	                                    <div style="width: 25%;float:left;margin-right: 20px;">
	                                        <label>CPF (Employer) :</label>
	                                    </div>
	                                    <div style="width: 65%;float:left;margin-bottom:5px;">
	                                        <div class="input-group" style="width: 40%;">
	                                        	<input type="number" step="0.01" class="form-control general" id="staff_cpf_employer" name="staff_cpf_employer" value="<?=isset($staff[0]->cpf_employer)?$staff[0]->cpf_employer:'' ?>" style="width: 100px;border-radius: 4px;"/>
	                                        	<label style="margin:3%">%</label>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
	                            <div class="form-group">
	                                <div style="width: 100%;">
	                                    <div style="width: 25%;float:left;margin-right: 20px;">
	                                        <label>Workpass :</label>
	                                    </div>
	                                    <div style="width: 65%;float:left;margin-bottom:5px;">
	                                        <div style="width: 30%;">
	                                        	<?php 
	                                        		echo form_dropdown('staff_workpass', $workpass_list, isset($staff[0]->workpass)?$staff[0]->workpass:'', 'class="form-control staff_workpass general"');
	                                        	?>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
	                            <div class="form-group for_pass_holder" style="display: none">
	                                <div style="width: 100%;">
	                                    <div style="width: 25%;float:left;margin-right: 20px;">
	                                        <label>FIN NO :</label>
	                                    </div>
	                                    <div style="width: 65%;float:left;margin-bottom:5px;">
	                                        <div style="width: 30%;">
	                                        	<input type="text" class="form-control general" id="wp_fin_no" name="wp_fin_no" value="<?=isset($staff[0]->wp_fin_no)?$staff[0]->wp_fin_no:'' ?>"/>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
								
	                            <div class="form-group">
    		                    	<div style="width: 100%;">
    			                    	<div style="width: 25%;float:left;margin-right: 20px;">
    			                            <label>Expire on :</label>
    			                        </div>
    			                    	<div style="width: 30%;float: left;">
    			                            <div class="input-group date datepicker" data-provide="datepicker">
    			                            	<div class="input-group-addon">
											        <span class="far fa-calendar-alt"></span>
											    </div>
    			                            	<input type="text" class="form-control" id="staff_pass_expire" name="staff_pass_expire" data-date-format="d F Y" value="<?=isset($staff[0]->pass_expire)?date("d F Y",strtotime($staff[0]->pass_expire)):''?>">
    										</div>
    			                        </div>
    			                        <div style="width: 30%;float: left;">
    			                        	<button  type="button" class="btn btn_purple renewed_btn" style="margin-left:5px;">Renew</button>
    			                        </div>
    			                    </div>
    		                    </div>
								
	                            <div class="form-group">
	                                <div style="width: 100%;">
	                                    <div style="width: 25%;float:left;margin-right: 20px;">
	                                        <label>Firm :</label>
	                                    </div>
	                                    <div style="width: 65%;float:left;margin-bottom:5px;">
	                                        <div style="width: 20%;">
	                                        	<?php
    												echo form_dropdown('firm_id', $firm_list, isset($staff[0]->firm_id)?$staff[0]->firm_id: '', 'class="firm-select general" style="width:150%;"');
    											?>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
    		                    <div class="form-group">
    		                    	<div style="width: 100%;">
    			                    	<div style="width: 25%;float:left;margin-right: 20px;">
    			                            <label>Date joined :</label>
    			                        </div>
    			                    	<div style="width: 30%;float: left;">
    			                    		<div class="input-group date datepicker" data-provide="datepicker">
    			                    			<div class="input-group-addon">
											        <span class="far fa-calendar-alt"></span>
											    </div>
											    <input type="text" class="form-control general" id="staff_joined" name="staff_joined" data-date-format="d F Y" value="<?=isset($staff[0]->date_joined)?date("d F Y",strtotime($staff[0]->date_joined)):''?>">
											</div>
    			                        </div>
    			                    </div>
    		                    </div>
    		                    <div class="form-group">
    		                    	<div style="width: 100%;">
    			                    	<div style="width: 25%;float:left;margin-right: 20px;">
    			                            <label>Date of cessation :</label>
    			                        </div>
    			                    	<div style="width: 30%;float: left;">
    			                            <div class="input-group date datepicker" data-provide="datepicker">
    			                            	<div class="input-group-addon">
											        <span class="far fa-calendar-alt"></span>
											    </div>
    			                            	<!-- <input type="text" class="form-control" id="staff_cessation" name="staff_cessation" data-date-format="d F Y" value="<?=isset($staff[0]->date_cessation)?date("d F Y",strtotime($staff[0]->date_cessation)):''?>" disabled> -->
    			                            	<?php if($Admin || $Manager) { ?>
	                                            	<input type="text" class="form-control" id="staff_cessation" name="staff_cessation" data-date-format="d F Y" value="<?=isset($staff[0]->date_cessation)?date("d F Y",strtotime($staff[0]->date_cessation)):''?>" >
	                                            <?php }else{ ?>
	                                            	<input type="text" class="form-control" id="staff_cessation" name="staff_cessation" data-date-format="d F Y" value="<?=isset($staff[0]->date_cessation)?date("d F Y",strtotime($staff[0]->date_cessation)):''?>" disabled>
	                                            <?php } ?>
    										</div>
    			                        </div>
    			                    </div>
    		                    </div>
    		                    <div class="form-group">
	                                <div style="width: 100%;">
	                                    <div style="width: 25%;float:left;margin-right: 20px;">
	                                        <label>Office :</label>
	                                    </div>
	                                    <div style="width: 65%;float:left;margin-bottom:5px;">
	                                        <div style="width: 40%;">
	                                        	<?php 
	                                        		echo form_dropdown('staff_office', $office_list, isset($staff[0]->office)?$staff[0]->office:'', 'class="form-control staff_office general"');
	                                        	?>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
    		                    <div class="form-group">
	                                <div style="width: 100%;">
	                                    <div style="width: 25%;float:left;margin-right: 20px;">
	                                        <label>Department :</label>
	                                    </div>
	                                    <div style="width: 65%;float:left;margin-bottom:5px;">
	                                        <div style="width: 40%;">
	                                        	<?php 
	                                        		echo form_dropdown('staff_department', $department_list, isset($staff[0]->department)?$staff[0]->department:'', 'class="form-control staff_department general"');
	                                        	?>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
    		                    <div class="form-group">
	                                <div style="width: 100%;">
	                                    <div style="width: 25%;float:left;margin-right: 20px;">
	                                        <label>Designation :</label>
	                                    </div>
	                                    <div class="charge_out_rate_designation_div_div" style="width: 65%;float:left;margin-bottom:5px;">
	                                        <div class="charge_out_rate_designation_div general" style="width: 40%;">
	                                        	<!-- <input type="text" class="form-control" id="staff_designation" name="staff_designation" value="<?=isset($staff[0]->designation)?$staff[0]->designation:'' ?>" style="width: 400px;"/> -->
												<select class="form-control charge_out_rate_designation" id="staff_designation" name="staff_designation" style="width: 100% !important">
													<option value="" selected="selected">Please Select Designation</option>
												</select>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>

								<!-- <div class="form-group">
	                                <div style="width: 100%;">
	                                    <div style="width: 25%;float:left;margin-right: 20px;">
	                                        <label>Annual leave/year :</label>
	                                    </div>
	                                    <div style="width: 65%;float:left;margin-bottom:5px;">
	                                        <div class="input-group" style="width: 40%;">
	                                        	<input type="number" class="form-control" id="staff_annual_leave" name="staff_annual_leave" value="<?=isset($staff[0]->annual_leave_year)?$staff[0]->annual_leave_year:'' ?>" style="width: 50%; border-radius: 4px;"/>
	                                        	<label style="margin:3%">days</label>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div> -->
	                            
	                            <div class="form-group">
	                                <div style="width: 100%;">
	                                    <div style="width: 25%;float:left;margin-right: 20px;">
	                                        <label>Remark :</label>
	                                    </div>
	                                    <div style="width: 65%;float:left;margin-bottom:5px;">
	                                        <div style="width: 20%;">
	                                        	<textarea class="form-control general" id="staff_remark" name="staff_remark" style="width: 400px;"><?php echo isset($staff[0]->remark)?$staff[0]->remark:'' ?></textarea>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
	                            <!-- <div class="form-group">
	                                <div style="width: 100%;">
	                                    <div style="width: 25%;float:left;margin-right: 20px;">
	                                        <label>Username :</label>
	                                    </div>
	                                    <div style="width: 65%;float:left;margin-bottom:5px;">
	                                        <div style="width: 20%;">
	                                        	<input type="text" class="form-control" id="staff_username" name="staff_username" value="<?=isset($staff[0]->username)?$staff[0]->username:'' ?>" style="width: 400px;"/>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div> -->
	                            <div class="form-group" style="display: none">
	                                <div style="width: 100%;">
	                                    <div style="width: 25%;float:left;margin-right: 20px;">
	                                        <label>Supervisor :</label>
	                                    </div>
	                                    <div style="width: 65%;float:left;margin-bottom:5px;">
	                                        <div style="width: 20%;">
	                                        	<input type="text" class="form-control" id="staff_supervisor" name="staff_supervisor" value="<?=isset($staff[0]->supervisor)?$staff[0]->supervisor:'' ?>" style="width: 400px;"/>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
	                            <h3>Leave</h3>
	                            <table class="table table-bordered table-striped mb-none" style="width:100%">
									<thead>
										<tr style="background-color:white;">
											<th class="text-left" style="width:50px;">Active</th>
											<th class="text-left">Leave Name</th>
											<th class="text-left">Days</th>
										</tr>
									</thead>
									<tbody>
										<?php 
											$active_type_of_leave = isset($active_type_of_leave)?$active_type_of_leave:FALSE;
											foreach($type_of_leave_list as $key => $leave)
								  			{
								  				echo '<tr>';
								  				if($active_type_of_leave)
								  				{
								  					echo '<td style="text-align: center;vertical-align: middle;"><input type="checkbox" class="general checkbox'.$leave->id.'" name="active[]" value="'.$leave->id.'"></td>';
								  				}
								  				else
								  				{
								  					echo '<td style="text-align: center;vertical-align: middle;"><input type="checkbox" class="general checkbox'.$leave->id.'" name="active[]" value="'.$leave->id.'" checked></td>';
								  				}

								  				echo '<td>'.$leave->leave_name.'</td>';

								  				if($active_type_of_leave){
								  					echo '<td class="text-right"><input type="text" class="form-control general leave_days'.$leave->id.'" name="leave_days[]" value="'.$active_type_of_leave[$key]->days.'" style="width: 70px;"></td>';
								  				}else{
								  					echo '<td class="text-right"><input type="text" class="form-control leave_days'.$leave->id.'" name="leave_days[]" value="'.$leave->days.'" style="width: 70px;"></td>';
								  				}
								  				
								  				echo '</tr>';
								  			}
										?>
									</tbody>
								</table>

								<?php
								if(isset($staff[0])?TRUE:FALSE){
								// if($staff[0]->employee_status_id != 1){ 
								?>
									<div>
										<h3>Other Entitled Leave</h3>
			                            <table class="table table-bordered table-striped mb-none" style="width:100%">
											<thead>
												<tr style="background-color:white;">
													<th class="text-left" style="width:50px;">Active</th>
													<th class="text-left">Leave Name</th>
												</tr>
											</thead>
											<tbody>
												<?php

													foreach($other_type_of_leave_list as $key => $leave)
										  			{
										  				if($leave->leave_name == 'Maternity Leave')
										  				{
										  					$date 	= new DateTime(date('Y-m-d', strtotime($staff[0]->date_joined)));
										  					$today 	= new DateTime(date('Y-m-d'));
										  					$today->modify('-3 month');

										  					// if($staff[0]->marital_status == 1 && $staff[0]->gender == 0)
										  					if($staff[0]->employee_status_id != 1 || ($staff[0]->employee_status_id == 1 && $date<=$today) || $staff[0]->office_country == "MALAYSIA"){
											  					if($staff[0]->gender == 0)
											  					{
											  						echo '<tr>';
													  				echo '<td style="text-align: center;vertical-align: middle;"><i class="fas fa-check"></i></td>';
													  				echo '<td>'.$leave->leave_name.'</td>';
													  				echo '</tr>';
											  					}
											  				}
										  				}
										  				else if($leave->leave_name == 'Paternity Leave')
										  				{
										  					if($staff[0]->employee_status_id != 1){
											  					if($staff[0]->marital_status == 1 && $staff[0]->gender == 1)
											  					{
											  						echo '<tr>';
													  				echo '<td style="text-align: center;vertical-align: middle;"><i class="fas fa-check"></i></td>';
													  				echo '<td>'.$leave->leave_name.'</td>';
													  				echo '</tr>';
											  					}
											  				}
										  				}
										  				else if($leave->leave_name == 'Childcare Leave')
										  				{
										  					if($staff[0]->employee_status_id != 1){
											  					// if($staff[0]->nationality_id == 165 && $staff[0]->gender == 0)
											  					if($staff[0]->marital_status == 1)
											  					{
											  						echo '<tr>';
													  				echo '<td style="text-align: center;vertical-align: middle;"><i class="fas fa-check"></i></td>';
													  				echo '<td>'.$leave->leave_name.'</td>';
													  				echo '</tr>';
											  					}
											  				}
										  				}
										  				else if($leave->leave_name == 'Wedding Leave')
										  				{
										  					if($staff[0]->employee_status_id != 1){
											  					if($staff[0]->marital_status != 1)
											  					{
											  						echo '<tr>';
													  				echo '<td style="text-align: center;vertical-align: middle;"><i class="fas fa-check"></i></td>';
													  				echo '<td>'.$leave->leave_name.'</td>';
													  				echo '</tr>';
											  					}
											  				}
										  				}
										  				else
										  				{
										  					if($staff[0]->employee_status_id != 1){
											  					echo '<tr>';
												  				echo '<td style="text-align: center;vertical-align: middle;"><i class="fas fa-check"></i></td>';
												  				echo '<td>'.$leave->leave_name.'</td>';
												  				echo '</tr>';
												  			}
										  				}
										  			}
											  	?>
											</tbody>
										</table>
									</div>
								<?php 
								// }
								} 
								?>
							</div>
							<?php 
								$staff_data = isset($staff)?$staff:FALSE;
								if($staff_data == FALSE)
								{
							?>
								<div id="w2-particulars" class="tab-pane active">
							<?php 
								}else{
							?>
								<div id="w2-particulars" class="tab-pane">
							<?php 
								}
							?>

							<div class="form-group">
		                        <div style="width: 100%;">
		                            <div style="width: 25%;float:left;margin-right: 20px;">
		                                <label>Photo :</label>
		                            </div>
		                            <div style="width: 65%;float:left;margin-bottom:5px;"> 
                                        <div class="profile">
                                            <div class="photo">
                                                <input type="file" accept="image/*" name="applicant_pic">
                                                <div class="photo__helper">
                                                    <div class="photo__frame photo__frame--circle">
                                                        <canvas class="photo__canvas"></canvas>
                                                        <div class="message is-empty">
                                                            <p class="message--desktop">Drop your photo here or browse your computer.</p>
                                                            <p class="message--mobile">Tap here to select your picture.</p>
                                                        </div>
                                                        <div class="message is-loading">
                                                            <i class="fa fa-2x fa-spin fa-spinner"></i>
                                                        </div>
                                                        <div class="message is-dragover">
                                                            <i class="fa fa-2x fa-cloud-upload"></i>
                                                            <p>Drop your photo</p>
                                                        </div>
                                                        <div class="message is-wrong-file-type">
                                                            <p>Only images allowed.</p>
                                                            <p class="message--desktop">Drop your photo here or browse your computer.</p>
                                                            <p class="message--mobile">Tap here to select your picture.</p>
                                                        </div>
                                                        <div class="message is-wrong-image-size">
                                                            <p>Your photo must be larger than 350px.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="okBtn" style="display: none;">
                                                    <a id="previewBtn" style="cursor: pointer;">SAVE</a>
                                                    /
                                                    <a class="remove" style="cursor: pointer;">REMOVE</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="showImage" style="display: none; width:300px; text-align: center;">
                                            <div>
                                                <img src="<?= isset($staff[0]->pic)? $staff[0]->pic:'' ?>" alt="" class="preview">
                                                <input type="hidden" id="applicant_pic" name="applicant_preview_pic" value="<?= isset($staff[0]->pic)? $staff[0]->pic:'' ?>" />
                                            </div>
                                            <div>
                                                <a id="editProfilePicBtn" style="cursor: pointer;">Edit</a>
                                                <a id="removeProfilePicBtn" style="cursor: pointer; color:red; display: none">Remove</a>
                                            </div>
                                        </div>
		                            </div>
		                        </div>
		                    </div>
							
							<div class="form-group">
	                    		<input type="hidden" class="form-control" id="staff_id" name="staff_id" value="<?= isset($staff[0]->id)? $staff[0]->id:'' ?>" style="width: 400px;"/>
                                <div style="width: 100%;">
                                    <div style="width: 25%;float:left;margin-right: 20px;">
                                        <label>Full Name :</label>
                                    </div>
                                    <div style="width: 65%;float:left;margin-bottom:5px;">
                                        <div style="width: 20%;">
                                        	<input type="text" class="form-control" id="staff_name" name="staff_name" value="<?=isset($staff[0]->name)? $staff[0]->name:'' ?>" style="width: 400px;"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div style="width: 100%;">
                                    <div style="width: 25%;float:left;margin-right: 20px;">
                                        <label>Nationality :</label>
                                    </div>
                                    <div style="width: 15%;float:left;margin-bottom:5px;">
                                        <div>
                                        	<?php
												echo form_dropdown('staff_nationality', $nationality_list, isset($staff[0]->nationality_id)?$staff[0]->nationality_id: '', 'class="nationality-select" style="width:150%;"');
											?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group singapore_pr_checkbox" style="display: none">
                                <div style="width: 100%;">
                                    <div style="width: 25%;float:left;margin-right: 20px;">
                                        <label>Singapore PR :</label>
                                    </div>
                                    <div style="width: 190px;float:left;margin-bottom:5px;">
                                    	<div>
									        <input type="checkbox" name="singapore_pr" />
									        <input type="hidden" name="hidden_singapore_pr" value="<?php echo (isset($staff[0]->singapore_pr)? $staff[0]->singapore_pr : 0) ?>"/>
									    </div>
                                    </div>
                                </div>
                            </div>
							<div class="form-group pr_issued_date" style="display: none">
								<div style="width: 100%;">
									<div style="width: 25%;float:left;margin-right: 20px;">
										<label>PR Issued Date :</label>
									</div>
									<div style="width: 30%;float: left;">
										<div class="input-group date datepicker" data-provide="datepicker">
											<div class="input-group-addon">
												<span class="far fa-calendar-alt"></span>
											</div>
											<input type="text" class="form-control" id="pr_issued_date" name="pr_issued_date" data-date-format="d F Y" value="<?=isset($staff[0]->pr_issued_date)?date("d F Y",strtotime($staff[0]->pr_issued_date)):''?>">
										</div>
									</div>
								</div>
							</div>
                            <div class="form-group attachment_singapore_pr_button" style="display: none">
                                <div style="width: 100%;">
                                    <div style="width: 25%;float:left;margin-right: 20px;">
                                        <label>Attach Singapore PR :</label>
                                    </div>
                                    <div style="width: 55%;float:left;margin-bottom:5px;">
                                    	<div class='input-group'>
		                                	<input type='file' style='display:none' id='attachment_singapore_pr' multiple='' name='attachment_singapore_pr[]'>
		                                	<label for='attachment_singapore_pr' class='btn btn_purple attachment_singapore_pr'>Select Attachment</label><br/>
		                                	<span class='file_name_singapore_pr' id='file_name_singapore_pr'></span>
		                                	<input type='hidden' class='hidden_attachment_singapore_pr' name='hidden_attachment_singapore_pr' value='<?php echo isset($staff[0]->attachment_singapore_pr)?$staff[0]->attachment_singapore_pr: ''?>'/>
		                                </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div style="width: 100%;">
                                    <div style="width: 25%;float:left;margin-right: 20px;">
                                        <label>NRIC/Passport No :</label>
                                    </div>
                                    <div style="width: 65%;float:left;margin-bottom:5px;">
                                        <div style="width: 20%;">
                                        	<input type="text" class="form-control" id="staff_nric_finno" name="staff_nric_finno" value="<?=isset($staff[0]->nric_fin_no)?$staff[0]->nric_fin_no:'' ?>" style="width: 400px;"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group attachment_nric_button">
                                <div style="width: 100%;">
                                    <div style="width: 25%;float:left;margin-right: 20px;">
                                        <label>Attach NRIC/Passport No :</label>
                                    </div>
                                    <div style="width: 55%;float:left;margin-bottom:5px;">
                                    	<div class='input-group'>
		                                	<input type='file' style='display:none' id='attachment_nric' multiple='' name='attachment_nric[]'>
		                                	<label for='attachment_nric' class='btn btn_purple attachment_nric'>Select Attachment</label><br/>
		                                	<span class='file_name_nric' id='file_name_nric'></span>
		                                	<input type='hidden' class='hidden_attachment_nric' name='hidden_attachment_nric' value='<?php echo isset($staff[0]->attachment_nric)?$staff[0]->attachment_nric: ''?>'/>
		                                </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div style="width: 100%;">
                                    <div style="width: 25%;float:left;margin-right: 20px;">
                                        <label>Address :</label>
                                    </div>
                                    <div style="width: 65%;float:left;margin-bottom:5px;">
                                        <div style="width: 20%;">
                                        	<textarea class="form-control" id="staff_address" name="staff_address" style="width: 400px;"><?php echo (isset($staff[0]->address))?$staff[0]->address:'';?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div style="width: 100%;">
                                    <div style="width: 25%;float:left;margin-right: 20px;">
                                        <label>Mobile Phone :</label>
                                    </div>
                                    <div style="width: 65%;float:left;margin-bottom:5px;">

                                        <div class="input-group fieldGroup_telephone">
                                            <input type="tel" class="form-control check_empty_telephone main_telephone hp" id="telephone" name="telephone[]" value="" onkeypress="validate(event)"/>

                                            <input type="hidden" class="form-control input-xs hidden_telephone main_hidden_telephone" id="hidden_telephone" name="hidden_telephone[]" value=""/>

                                            <label class="radio-inline control-label" style="margin-top: -30px; margin-left: 20px;"><input type="radio" class="telephone_primary main_telephone_primary" name="telephone_primary" value="1" checked> Primary</label>

                                            <input class="btn btn_purple button_increment_telephone addMore_telephone" type="button" id="create_button" value="+" style="margin-left: 20px; margin-top: -26px; border-radius: 3px;visibility: hidden; width: 35px;"/>

                                            <button type="button" class="btn btn-default btn-sm show_telephone" style="margin-left: 20px; margin-top: -23px; visibility: hidden;">
                                                  <span class="fa fa-arrow-down" aria-hidden="true"></span>&nbsp<span class="toggle_word">Show more</span>
                                            </button>
                                        </div>

                                        <div class="telephone_toggle"></div>

                                        <div class="input-group fieldGroupCopy_telephone telephone_disabled" style="display: none;">
                                            <input type="tel" class="form-control check_empty_telephone second_telephone second_hp" id="telephone" name="telephone[]" value="" onkeypress="validate(event)"/>

                                            <input type="hidden" class="form-control input-xs hidden_telephone" id="hidden_telephone" name="hidden_telephone[]" value=""/>

                                            <label class="radio-inline control-label" style="margin-top: -30px; margin-left: 20px;"><input type="radio" class="telephone_primary" name="telephone_primary" value="1"> Primary</label>
                                        </div>

                                        <div id="form_telephone"></div>

                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
		                    	<div style="width: 100%;">
			                    	<div style="width: 25%;float:left;margin-right: 20px;">
			                            <label>D.O.B :</label>
			                        </div>
			                    	<div style="width: 30%;float: left;">
			                    		<div class="input-group date datepicker" data-provide="datepicker">
			                    			<div class="input-group-addon">
										        <span class="far fa-calendar-alt"></span>
										    </div>
										    <input type="text" class="form-control" id="staff_DOB" name="staff_DOB" value="<?=isset($staff[0]->dob)?date("d F Y",strtotime($staff[0]->dob)):''?>">
										    
										</div>
			                        </div>
			                    </div>
		                    </div>
		                    <div class="form-group">
                                <div style="width: 100%;">
                                    <div style="width: 25%;float:left;margin-right: 20px;">
                                        <label>Gender :</label>
                                    </div>
                                    <div style="width: 250px;float:left;margin-bottom:5px;">
                                    	<div>
									        <input type="checkbox" id="gender" name="gender" />
									        <input type="hidden" name="hidden_gender" value="<?php echo (isset($staff[0]->gender)? $staff[0]->gender : 1) ?>"/>
									    </div>
                                    </div>
                                </div>
                            </div>
		                    <div class="form-group">
                                <div style="width: 100%;">
                                    <div style="width: 25%;float:left;margin-right: 20px;">
                                        <label>Marital Status :</label>
                                    </div>
                                    <div style="width: 190px;float:left;margin-bottom:5px;">
                                    	<div>
									        <input type="checkbox" name="marital_status" />
									        <input type="hidden" name="hidden_marital_status" value="<?php echo (isset($staff[0]->marital_status)? $staff[0]->marital_status : 0) ?>"/>
									    </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group attachment_marital_status_button" style="display: none">
                                <div style="width: 100%;">
                                    <div style="width: 25%;float:left;margin-right: 20px;">
                                        <label>Attach Marital Status :</label>
                                    </div>
                                    <div style="width: 55%;float:left;margin-bottom:5px;">
                                    	<div class='input-group'>
		                                	<input type='file' style='display:none' id='attachment_marital_status' multiple='' name='attachment_marital_status[]'>
		                                	<label for='attachment_marital_status' class='btn btn_purple attachment_marital_status'>Select Attachment</label><br/>
		                                	<span class='file_name_marital_status' id='file_name_marital_status'></span>
		                                	<input type='hidden' class='hidden_attachment_marital_status' name='hidden_attachment_marital_status' value='<?php echo isset($staff[0]->attachment_marital_status)?$staff[0]->attachment_marital_status: ''?>'/>
		                                </div>
                                    </div>
                                </div>
                            </div>

						</div>

						<div id="w2-family" class="tab-pane">
							<div>
                                <table class="table table-bordered table-striped table-condensed mb-none" id="family_info_table">
                                    <thead>
                                        <div class="tr">
                                            <div class="th" id="family_name" style="text-align: center;width:150px">Name</div>
                                            <div class="th" id="nric" style="text-align: center;width:170px">NRIC/Passport</div>
                                            <div class="th" style="text-align: center;width:190px" id="dob">D.O.B</div>
                                            <div class="th" id="nationality " style="text-align: center;width:160px">Nationality </div>
                                            <div class="th" id="relationship" style="text-align: center;width:150px">Relationship</div>
                                            <div class="th" id="contact" style="text-align: center;width:150px">Contact</div>
                                            <div class="th" id="document" style="text-align: center;width:150px">Proof of Document</div>
                                            <a href="javascript: void(0);" class="th" rowspan=2 style="color: #D9A200;width:80px; outline: none !important;text-decoration: none;"><span id="family_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Family Info" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add </span></a>
                                            <div class="th" id="in_use" style="text-align: center;width:80px">Verify</div>
                                        </div>
                                        
                                    </thead>
                                    <div class="tbody" id="body_family_info">
                                        

                                    </div>
                                    
                                </table>
                            </div>
						</div>

						<div id="w2-history" class="tab-pane">
							<div>
                                <table class="table table-bordered table-striped table-condensed mb-none" id="event_info_table" style="width: 100%">
                                    <thead>
                                        <div class="tr">
                                            <div class="th" id="date" style="text-align: center;width:250px">Date</div>
                                            <div class="th" id="event" style="text-align: center;width:250px">Event</div>
                                            <div class="th" id="document" style="text-align: center;width:250px">Attachment</div>
                                           <!--  <?php if($Admin || $Manager) { ?>
                                            <a href="javascript: void(0);" class="th" rowspan=2 style="color: #D9A200;width:150px; outline: none !important;text-decoration: none; text-align: center;"><span id="event_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Family Info" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add </span></a>
                                            <?php } ?> -->
                                        </div>
                                    </thead>
                                    <div class="tbody" id="body_event_info"></div>
                                </table>
                            </div>
						</div>

						<div id="w2-payslip" class="tab-pane">
							<table class="table table-bordered table-striped mb-none" style="width:50%;table-layout: fixed;border-right:none;border-bottom:none;" id="salary_tbl">
								<thead style="width:100%;">
									<tr>
										<th style="height:35px;width:27%;" valign=middle>Salary</th>
										<th style="height:35px;width:25%;" valign=middle>Currency</th>
										<th style="height:35px;width:30%;" valign=middle>Effective date</th>
										<?php if($Admin) { ?>
											<th style="height:35px;border-left:1px solid #ddd !important;width:18%;" valign=middle>
												<a href="javascript: void(0);" data-toggle="tooltip" data-trigger="hover" style="font-weight:bold;" data-original-title="Add salary" id="salary_add" ><i class="fa fa-plus-circle amber" style="font-size:16px;"></i> Add Line</a>
											</th>
										<?php } ?>
									</tr>
								</thead>

								<tbody id="body_add_salary">
							
								</tbody>
					
							</table>

							<br/>

							


							<!-- <div class="form-group">
								<div style="width: 100%;">
									<div style="width: 25%;float:left;margin-right: 20px;">
										<label>Salary ($) :</label>
									</div>
									<div style="width: 65%;float:left;margin-bottom:5px;">
										<div style="width: 20%;">
											<input type="number" class="form-control general" id="staff_salary" name="staff_salary" value="<?=isset($staff[0]->salary)?$staff[0]->salary:'' ?>" style="width: 400px;" />
										</div>
									</div>
								</div>
							</div> -->
<!-- 
							<div class="form-group for_non_pass_holder" style="display: none">
								<div style="width: 100%;">
									<div style="width: 25%;float:left;margin-right: 20px;">
										<label>CDAC/MENDAKI/SINDA ($) :</label>
									</div>
									<div style="width: 65%;float:left;margin-bottom:5px;">
										<div style="width: 20%;">
											<input type="number" step="0.01" class="form-control general" id="staff_cdac" name="staff_cdac" value="<?=isset($staff[0]->cdac)?$staff[0]->cdac:'' ?>" style="width: 100px;"/>
										</div>
									</div>
								</div>
							</div> -->
<!-- 
							<div class="form-group" style="margin-top:10px;">
								<div style="width: 100%;">
									<div style="width: 25%;float:left;margin-right: 20px;">
										<label>Bond :</label>
									</div>
									<div style="width: 250px;float:left;margin-bottom:10px;">
										<div>
											<input type="checkbox" id="bond" name="bond" />
											<input type="hidden" name="hidden_bond" value="<?php echo (isset($staff[0]->bond)? $staff[0]->bond : 0) ?>"/>
										</div>
									</div>
								</div>
							</div> -->

							<table class="table table-bordered table-striped mb-none bond_group" style="width:90%;table-layout: fixed;" id="bond_tbl">
								<thead style="width:100%;">
									<tr>
										<th style="height:35px;width:30%;" valign=middle>Start Date of Bond</th>
										<th style="height:35px;width:30%;" valign=middle>End Date of Bond</th>
										<th style="height:35px;width:17%;" valign=middle>Period of Bond</th>
										<th style="height:35px;width:10%;" valign=middle>Currency</th>
										<th style="height:35px;width:17%;" valign=middle>Bond Allowance</th>
										<th style="height:35px;width:14%;" valign=middle>No of Months Completed</th>
										<th style="height:35px;width:15%;" valign=middle>Total bond allowance</th>
										<?php if($Admin) { ?>
											<th style="height:35px;width:13%;" valign=middle>
												<a href="javascript: void(0);" data-toggle="tooltip" data-trigger="hover" style="font-weight:bold;" data-original-title="Add bond" id="bond_add" ><i class="fa fa-plus-circle amber" style="font-size:16px;"></i> Add Line</a>
											</th>
										<?php } ?>
									</tr>
								</thead>

								<tbody id="body_add_bond">
							
								</tbody>
					
							</table>

							<!-- <div class="form-group bond_group" style="display: none">
								<div style="width: 100%;">
									<div style="width: 25%;float:left;margin-right: 20px;">
										<label>Bond Allowance:</label>
									</div>
									<div style="width: 65%;float:left;margin-bottom:5px;">
										<div style="width: 20%;">
											<input type="number" step="0.01" class="form-control general" id="bond_allowance" name="bond_allowance" value="<?=isset($staff[0]->bond_allowance)?$staff[0]->bond_allowance:'0'?>" style="width: 400px;"/>
										</div>
									</div>
								</div>
							</div>

							<div class="form-group bond_group" style="display: none">
								<div style="width: 100%;">
									<div style="width: 25%;float:left;margin-right: 20px;">
										<label>Start Date of Bond :</label>
									</div>
									<div style="width: 30%;float:left;margin-bottom:5px;">
										<div class="input-group date datepicker" data-provide="datepicker">
											<div class="input-group-addon">
												<span class="far fa-calendar-alt"></span>
											</div>
											<input type="text" class="form-control general" id="start_bond" name="start_bond" data-date-format="d F Y" value="<?=isset($staff[0]->start_bond)?date("d F Y",strtotime($staff[0]->start_bond)):''?>">
										</div>
									</div>
								</div>
							</div>

							<div class="form-group bond_group" style="display: none">
								<div style="width: 100%;">
									<div style="width: 25%;float:left;margin-right: 20px;">
										<label>Period of Bond :</label>
									</div>
									<div style="width: 250px;float:left;margin-bottom:5px;">
										<div>
											<input type='number' class="form-control general" name="bond_period" id='bond_period' value="<?=isset($staff[0]->bond_period)?$staff[0]->bond_period:'0'?>" style="width: 35%; text-align: center; display:inline-block !important;"/> 
											<label style="display:inline-block ; padding-left: 10px">Month(s)</label>
										</div>
									</div>
								</div>
							</div>

							<div class="form-group bond_group" style="display: none">
								<div style="width: 100%;">
									<div style="width: 25%;float:left;margin-right: 20px;">
										<label>No of Months Completed :</label>
									</div>
									<div style="width: 250px;float:left;margin-bottom:5px;">
										<div>
											<input type='number' class="form-control general" name="bond_completed" id='bond_completed' value="" style="width: 35%; text-align: center; display:inline-block !important;" readonly/> 
											<label style="display:inline-block ; padding-left: 10px">Month(s)</label>
										</div>
									</div>
								</div>
							</div>

							<div class="form-group bond_group" style="display: none">
								<div style="width: 100%;">
									<div style="width: 25%;float:left;margin-right: 20px;">
										<label>Total bond allowance received :</label>
									</div>
									<div style="width: 450px;float:left;margin-bottom:5px;">
										<div>
											<input type='number' class="form-control general" name="total_bond_allowance" id='total_bond_allowance' value="" style="width: 35%; display:inline-block !important;" readonly/> 
											
										</div>
									</div>
								</div>
							</div> -->


						</div>

					</div>
					
				</div>
			</div>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 number text-right" id="client_footer_button">
					<!--input type="button" value="Save As Draft" id="save_draft" class="btn btn-default"-->
					<!-- <input type="button" value="Save" id="save" class="btn btn-primary ">
					<a href="<?= base_url();?>masterclient/" class="btn btn-default">Cancel</a>

					<div style="width: 65%;float:left;margin-bottom:5px;"> -->
                    	<?php 
                    		echo '<a href="'.base_url().'employee" class="btn pull-right btn_cancel" style="margin:0.5%; cursor: pointer;">Cancel</a>';

                    		if($create){
                    			echo '<button class="btn btn_purple pull-right" style="margin:0.5%">Create</button>';
                    		}
                    		else{
                    			echo '<button class="btn btn_purple pull-right" style="margin:0.5%">Save</button>';
                    		}
                    	?>
                    <!-- </div> -->
				</div>
				<div class="col-md-12 number text-right" id="client_footer_cancel_button" style="display: none;">
                    	<?php 
                    		echo '<a href="'.base_url().'employee" class="btn pull-right btn_cancel" style="margin:0.5%; cursor: pointer;">Cancel</a>';
                    	?>
				</div>
			</div>
		</footer>
		</form>

		<table id="clone_salary_model" style="display: none;" >
			<tr class="" method="post" name="form" id="form" num="">
				<td>
					<div>
						<input type="number" class="form-control general staff_salary" name="staff_salary[]" value="<?=isset($staff[0]->salary)?$staff[0]->salary:'' ?>"/>
						<input type="hidden" class="form-control" name="salary_info_id[]" value=""/>
					</div>
				</td>
				<td >
					<div>
						<?php
							echo form_dropdown('salary_currency[]', $currency_list, isset($salary[0]->currency)?$salary[0]->currency: '', 'class="currency-select general" "');
						?>
					</div>
				</td>
				<td >
					<div class="input-group date datepicker" data-provide="datepicker">
						<div class="input-group-addon">
							<span class="far fa-calendar-alt"></span>
						</div>
						<input type="text" class="form-control general effective_date" name="effective_start_date[]" data-date-format="d F Y" value="<?=isset($salary[0]->effective_start_date)?date("d F Y",strtotime($salary[0]->effective_start_date)):''?>">
					</div>
				</td>
				<?php if($Admin) { ?>
					<td>
						<div style="display: inline-block; margin-right: 5px; margin-bottom: 5px;">
							<button type="button" class="btn btn_purple delete_salary_info_button" onclick="delete_salary_info(this)" >Delete</button>
						</div>
					</td>
				<?php } ?>
			</tr>
		</table>

		<table id="clone_bond_model" style="display: none;" >
			<tr class="" method="post" name="form" id="form" num="">
				<td >
					<div class="input-group date datepicker" data-provide="datepicker">
						<div class="input-group-addon">
							<span class="far fa-calendar-alt"></span>
						</div>
						<input type="text" class="form-control general" name="bond_start_date[]" data-date-format="d F Y" value="<?=isset($bond[0]->bond_start_date)?date("d F Y",strtotime($bond[0]->bond_start_date)):''?>" disabled>
					</div>
				</td>
				<td >
					<div class="input-group date datepicker" data-provide="datepicker">
						<div class="input-group-addon">
							<span class="far fa-calendar-alt"></span>
						</div>
						<input type="text" class="form-control general" name="bond_end_date[]" data-date-format="d F Y" value="<?=isset($bond[0]->bond_end_date)?date("d F Y",strtotime($bond[0]->bond_end_date. '-1 day')):''?>" disabled>
					</div>
				</td>
				<td>
					<div style="width: 100%;float:left;margin-bottom:5px;">
						<div>
							<input type='number' class="form-control general" name="bond_period[]"  value="<?=isset($staff[0]->bond_period)?$staff[0]->bond_period:'0'?>" style="width: 50%; text-align: center; display:inline-block !important;" disabled/> 
							<label style="display:inline-block ; padding-left: 10px">Month(s)</label>
						</div>
					</div>
				</td>
				<td>
					<div style="width: 100%;float:left;margin-bottom:5px;">
						<div style="width: 100%;">
						<?php
							echo form_dropdown('bond_currency[]', $currency_list, isset($bond[0]->currency)?$bond[0]->currency: '', 'class="currency-select general" disabled');
						?>
						</div>
					</div>
				</td>
				<td>
					<div style="width: 100%;float:left;margin-bottom:5px;">
						<div style="width: 100%;">
							<input type="number" step="0.01" class="form-control general" name="bond_allowance[]" value="<?=isset($bond[0]->bond_allowance)?$bond[0]->bond_allowance:'0'?>" style="width: 100%;" disabled/>
							<input type="hidden" class="form-control" name="bond_info_id[]" value=""/>
							
						</div>
					</div>
				</td>
				<td>
					<div style="width: 100%;float:left;margin-bottom:5px;">
						<div>
							<input type='number' class="form-control general" name="bond_completed[]" value="" style="width: 50%; text-align: center; display:inline-block !important;" disabled/> 
							<label style="display:inline-block ; padding-left: 10px">Month(s)</label>
						</div>
					</div>
				</td>
				<td>
					<div style="width: 100%;float:left;margin-bottom:5px;">
						<div>
							<input type='number' class="form-control general" name="total_bond_allowance[]" value="" style="width: 100%; display:inline-block !important;" disabled/> 
							
						</div>
					</div>
				</td>
				<?php if($Admin) { ?>
					<td>
						<div style="display: inline-block; margin-right: 5px; margin-bottom: 5px;">
							<button type="button" class="btn btn_purple delete_bond_info_button" onclick="delete_bond_info(this);">Delete</button>
						</div>
					</td>
				<?php } ?>
			</tr>
			
		</table>
	</section>
</section>

<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>

<div id="resignation" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;" data-backdrop="static">
	<div class="modal-dialog" style="width: 800px !important;">
		<div class="modal-content">
				<header class="panel-heading">
					<h2 class="panel-title"><strong>Resignation</strong></h2>
				</header>
				<div class="panel-body">
					<div class="col-md-12">
						<input type="text" class="hidden" id='emp_id' value="<?=isset($staff[0]->id)? $staff[0]->id:'' ?>" />
						<div class="form-group">
							<div style="width: 100%;">
								<div style="width: 25%;float:left;margin-right: 20px;">
		                            <label>Notice Period :</label>
		                        </div>
		                        <div style="width: 65%;float:left;margin-bottom:5px;">
		                        	<input type="text" class="hidden" id='hidden_notice_period'/>
		                        	<input type="text" class="form-control" id='notice_period' value="" style="width: 60%;text-align: center;" disabled />
		                        </div>
		                    </div>
		                </div>
						<div class="form-group">
							<div style="width: 100%;">
								<div style="width: 25%;float:left;margin-right: 20px;">
		                            <label>Last Working Day :</label>
		                        </div>
		                        <div style="width: 65%;float:left;margin-bottom:5px;">
		                            <div style="width: 75%;float:left;">
		                            	<div class="input-group date datepicker" data-provide="datepicker">
			                            	<div class="input-group-addon">
										        <span class="far fa-calendar-alt"></span>
										    </div>
			                            	<input type="text" class="form-control" id="resign_last_day" name="resign_last_day" data-date-format="d F Y">
										</div>
		                            </div>
		                            <a class="pending" href='javascript:void(0)' style='font-weight:bold;font-size:20px;float:left;padding-top: 6px;padding-left: 5px;color:#d50000 !important;display: none;' data-toggle='tooltip' data-placement='right' data-original-title='To Be Confirm'><i class="fas fa-user-clock"></i></a>
		                            <a class="approve" href='javascript:void(0)' style='font-weight:bold;font-size:20px;float:left;padding-top: 6px;padding-left: 5px;color:#1faa00 !important;display: none;' data-toggle='tooltip' data-placement='right' data-original-title='Confirmed'><i class="fas fa-user-check" ></i></a>

		                            <input type="text" class="hidden" id='last_day_confirmed' value="" />
		                        </div>
		                    </div>
		                </div>
		                <div class="form-group">
							<div style="width: 100%;">
								<div style="width: 25%;float:left;margin-right: 20px;">
		                            <label>Resign Letter :</label>
		                        </div>
		                        <div style="width: 65%;float:left;margin-bottom:5px;">
		                            <div style="width: 20%;">
		                            	<div class='input-group'>
		                                	<input type='file' style='display:none' id='attachment_resign_letter' multiple='' name='attachment_resign_letter'>
		                                	<label for='attachment_resign_letter' class='btn btn_purple attachment_resign_letter' style="width: 250px;">Select Attachment</label><br/>
		                                	<span class='file_name_resign_letter' id='file_name_resign_letter' value=''></span>
		                                	<input type='hidden' class='hidden_attachment_resign_letter' name='hidden_attachment_resign_letter' value=''/>
		                                </div>
		                            </div>
		                        </div>
		                    </div>
		                </div>
					</div>
				</div>
			<div class="modal-footer" id="resignation_footer1">
				<button class="btn btn_purple" id="confirm_resign">Confirm</button>
				<input type="button" class="btn btn-default cancel_resign" data-dismiss="modal" name="" value="Cancel">
			</div>
			<div class="modal-footer" id="resignation_footer2" style='display:none'>
				<button class="btn btn_purple" id="approve_resignation">Approve</button>
				<button class="btn btn-default" id="reject_resignation">Reject</button>
			</div>
		</div>
	</div>
</div>

<div id="principal_statement" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;" data-backdrop="static">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    	<div class="modal-header">
          	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          	<span aria-hidden="true">&times;</span>
	    	</button>
          	<h4 class="modal-title">ADD SALARY</h4>
          	<input type="hidden" id="EC_today_date" name="EC_today_date" value="<?php echo date('d F Y');?>"/>
        </div>
      	<div class="modal-body"></div>
      	<div class="modal-footer">
        	<button type="submit" class="btn btn_purple" id="EC_Proceed">Proceed</button>
     	</div>
    </div>
  </div>
</div>

<div id="bond_statement" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;" data-backdrop="static">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    	<div class="modal-header">
          	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          	<span aria-hidden="true">&times;</span>
	    	</button>
          	<h4 class="modal-title">ADD BOND</h4>
          	<input type="hidden" id="AB_today_date" name="AB_today_date" value="<?php echo date('d F Y');?>"/>
        </div>
      	<div class="modal-body"></div>
      	<div class="modal-footer">
        	<button type="submit" class="btn btn_purple" id="bond_Proceed">Proceed</button>
     	</div>
    </div>
  </div>
</div>

<script src="<?= base_url()?>application/js/intlTelInput.js" /></script>
<script src="<?= base_url()?>application/js/intlTelInput.js?v=30eee4fc8d1b59e4584b0d39edfa2082" /></script>
<script src="<?= base_url()?>application/js/defaultCountryIp.js?v=30eee4fc8d1b59e4584b0d39edfa2082" /></script>
<script src="<?= base_url()?>application/js/utils.js?v=30eee4fc8d1b59e4584b0d39edfa2082" /></script>

<script type="text/javascript">

	var base_url = '<?php echo base_url(); ?>';
	var active_type_of_leave = <?php echo json_encode(isset($active_type_of_leave)?$active_type_of_leave:FALSE) ?>;
	var staff = <?php echo json_encode(isset($staff)?$staff:FALSE) ?>;
	var family_info = <?php echo json_encode(isset($family_info)?$family_info:FALSE) ?>;
	var event_info = <?php echo json_encode(isset($event_info)?$event_info:FALSE) ?>;
	var Admin = <?php echo json_encode(isset($Admin)?true:false) ?>;
	var Manager = <?php echo json_encode($Manager) ?>;
	var user_id = <?php echo json_encode($this->user_id) ?>;
	var renewed_value = 0;
	var index_tab_aktif;
	var telephone = <?php echo json_encode(isset($staff[0]->employee_telephone)?$staff[0]->employee_telephone:null);?>;
	var employee_id = <?php echo json_encode(isset($staff[0]->id)? $staff[0]->id:'') ?>;
	var salary = <?php echo json_encode(isset($salary)?$salary:FALSE) ?>;
	var bond = <?php echo json_encode(isset($bond)?$bond:FALSE) ?>;
	var add_salary_link = "<?php echo site_url('employee/add_salary'); ?>";
	var add_bond_link = "<?php echo site_url('employee/add_bond'); ?>";
	var get_statement_content_link = "<?php echo site_url('employee/principalStatement'); ?>";
	var get_bond_statement_content_link = "<?php echo site_url('employee/bondStatement'); ?>";
	var delete_salary_link = "<?php echo site_url('employee/delete_salary_info'); ?>";
	var delete_bond_link = "<?php echo site_url('employee/delete_bond_info'); ?>";


	if(active_type_of_leave)
	{
		for(var t = 0; t < active_type_of_leave.length; t++)
		{
			$('.checkbox'+active_type_of_leave[t]["type_of_leave_id"]).prop("checked", true);
		}
	}

	toastr.options = {
	  "positionClass": "toast-bottom-right"
	}

	$(".nationality-select").select2();

	$(".firm-select").select2();

	$('.datepicker').datepicker({
	    format: 'dd MM yyyy',
	});

	$('[data-toggle="tooltip"]').tooltip({html:true});

	$(document).on('click',".check_state",function(){
		index_tab_aktif = $(this).data("information");
		if(index_tab_aktif == "family" || index_tab_aktif == "history" || index_tab_aktif == "payslip")
		{
			$("#client_footer_cancel_button").show();
			$("#client_footer_button").hide();
		}
		else
		{
			$("#client_footer_button").show();
			$("#client_footer_cancel_button").hide();
		}

		if(index_tab_aktif == "history")
		{
			$('.declaration').remove();
			// if(staff[0]['id'] != 44)
			if(staff[0]['id'] != 38 && staff[0]['id'] != 44) // EXCEPT YEO KARN LEE & TAY CHEW SEE
			{
				$.ajax({
		            type: "POST",
		            url: "<?php echo site_url('employee/generate_new_declaration'); ?>",
		            data: {"emp_id":staff[0]['id']},
		            dataType: "json",
		            'async':false,
		            success: function(data)
		            {
		            	if(data != "")
		            	{
		            		show_new_declaration(data);
		            	}
		            }               
		        });
			}
		}
	});

	$(".staff_workpass").change(function (){
		var value = $(".staff_workpass").val();

		if(value == 'Not Applicable')
		{
			$(".for_non_pass_holder").show();
			$(".for_pass_holder").hide();
		}
		else
		{
			$(".for_non_pass_holder").hide();
			$(".for_pass_holder").show();
		}
	});

	if(staff != false)
	{
		var value = $(".staff_workpass").val();

		if(value == 'Not Applicable')
		{
			$(".for_non_pass_holder").show();
			$(".for_pass_holder").hide();
		}
		else
		{
			$(".for_non_pass_holder").hide();
			$(".for_pass_holder").show();
		}
	}

	$(".nationality-select").change(function (){
		var selected_nationality = $(".nationality-select option:selected").text();
		if(selected_nationality == "SINGAPORE CITIZEN")
		{
			$(".singapore_pr_checkbox").hide();
			$("[name='singapore_pr']").bootstrapSwitch('state', false);
			$("[name='hidden_singapore_pr']").val(0);
			$(".attachment_singapore_pr_button").hide();
			$(".file_name_singapore_pr").html("");
	    	$(".hidden_attachment_singapore_pr").val("");
	    	$(".staff_workpass").val("Not Applicable");
	    	//$(".staff_workpass").attr("disabled", true);
	    	$("#staff_pass_expire").val("");
	    	//$("#staff_pass_expire").attr("disabled", true);
		}
		else
		{
			$(".singapore_pr_checkbox").show();
			$(".staff_workpass").val("");
			//$(".staff_workpass").attr("disabled", false);
			$("#staff_pass_expire").val("");
			//$("#staff_pass_expire").attr("disabled", false);
		}
	});

	//renewed_btn
	$(".renewed_btn").click(function (){
		//console.log("in");
		if(renewed_value == 1)
		{
			$("#staff_pass_expire").attr("disabled", false);
		}
	});

	if(staff != false)
	{
		renewed_value = 1;
		$("#staff_pass_expire").attr("disabled", true);
	}
	//Employee Status
	$(".employee_status").change(function (){

		var selected_employee_status = $(".employee_status option:selected").text();

		if(selected_employee_status == "Confirmed")
		{
			$(".date_of_letter_div").show();
			$(".effective_date_div").show();

			// $(".resign_details_btn").hide();
		}
		else if(selected_employee_status == "Resigned")
		{
			$(".date_of_letter_div").hide();
			$(".effective_date_div").hide();

			// resign();
			// $(".resign_details_btn").show();
		}
		else
		{
			$(".date_of_letter_div").hide();
			$(".effective_date_div").hide();

			// $(".resign_details_btn").hide();
		}
	});

	if(staff != false)
	{
		var value = $(".employee_status option:selected").text();

		if(value == 'Resigned')
		{
			var result = '';
			// $(".resign_details_btn").show();

			$(".resign_details_btn").text('Resignation Details');

			var data = new FormData();
			data.append('employee_id', staff[0]['id']);

			$.ajax({
	           type: "POST",
	           url: "<?php echo site_url('employee/get_resignation_details'); ?>",
	           data: data,
	           dataType: 'json',
	           cache: false,
	           contentType: false,
	           processData: false,
	           'async':false,
	           success: function(data)
	           {	
	           		result = data;

					$("#notice_period").val(data[0]['notice_period']);
					var sp = data[0]['notice_period'].split("~");
					$('#hidden_notice_period').val(sp[1]);
					$("#resign_last_day").val(moment(data[0]['last_day']).format('DD MMMM YYYY'));

					if(data[0]['last_day_confirmed'] == 1)
					{
						$('.approve').show();
						$('.pending').hide();
					}
					else
					{
						$('.approve').hide();
						$('.pending').show();
					}

					var file =  JSON.parse(data[0]['resignation_letter']);

			        var filename = "";

				    filename = '<a href="'+base_url+'uploads/resignation_letter/'+file['file_name']+'" target="_blank">'+file['file_name']+'</a>';

			        $("#file_name_resign_letter").html(filename);

			        $(".hidden_attachment_resign_letter").val(data[0]['resignation_letter']);

	           }
	       	});


			<?php if($Admin || $this->user_id == 79) { ?>
				
				if(result[0]['last_day_confirmed'] == 0)
				{
					$('#resignation_footer1').hide();
					$('#resignation_footer2').show();
					resign();
				}

			<?php } ?>
		}

	}

	if(staff != false)
	{
		if(staff[0]['employee_status_id'] == 2)
		{
			$(".date_of_letter_div").show();
			$(".effective_date_div").show();
		}
		else
		{
			$(".date_of_letter_div").hide();
			$(".effective_date_div").hide();
		}

		// if(staff[0]['bond'] == 1)
		// {
		// 	$(".bond_group").show();

		// 	// var today = new Date();

		// 	// if($("#start_bond").val() != '')
		// 	// {
		// 	// 	var month = monthsDiff($("#start_bond").val(),today);

		// 	// 	$("#bond_completed").val(month);
		// 	// 	$("#total_bond_allowance").val(month*($('#bond_allowance').val()));
		// 	// }
		// }
		// else
		// {
		// 	$(".bond_group").hide();
		// }
	}

	// SHOW RESIGNATION DETAILS
	$(".resign_details_btn").click(function (){
		resign();
	});

	$("#resign_last_day").change(function ()
	{
		if($('#resign_last_day').val() == '')
		{
				$('.approve').hide();
				$('.pending').hide();
				$("#last_day_confirmed").val(0);
		}
		else
		{
			var y = new Date($('#hidden_notice_period').val());
			var x = new Date($('#resign_last_day').val());

			if(x >= y)
			{
				// x >= y
				$('.approve').show();
				$('.pending').hide();
				$("#last_day_confirmed").val(1);
			}
			else if(x < y)
			{
				// x < y
				$('.approve').hide();
				$('.pending').show();
				$("#last_day_confirmed").val(0);
			}
		}

	});

	function resign() 
	{
		$("#resignation").modal("show");

		var status = $('.previous_staff_status').val();
		var today = "<?php echo date('d F Y')?>";
		var notice_period = 0;
		var notice_until = "";

		if(status == 1)
		{
			notice_period = 14;
		}
		else if(status == 2)
		{
			notice_period = 30;
		}

		$.ajax({
            type: "POST",
            url: "<?php echo site_url('employee/get_notice_period'); ?>",
            data: {"notice_period":notice_period}, // <--- THIS IS THE CHANGE
            'async':false,
            success: function(data)
            {
            	notice_until = data;
            }               
        });

		if(staff[0]['employee_status_id'] != 3)
		{
			$('#notice_period').val(today+' ~ '+notice_until);
			$('#hidden_notice_period').val(notice_until);
		}
		else
		{
			document.getElementById('resign_last_day').disabled = "true";
			$('#confirm_resign').hide();
		}
	}

	$(document).on('change','[id=attachment_resign_letter]',function(){
	    var filename = "";
	    //console.log(this.files[0]);
	    for(var i = 0; i < this.files.length; i++)
	    {
		    if(i == 0)
		    {
		        filename = this.files[i].name;
		    }
		    else
		    {
		        filename = filename + ", " + this.files[i].name;
		    }
	    }
	    //console.log(filename);
	    $(this).parent().find(".file_name_resign_letter").html(filename);
	    $(this).parent().find(".hidden_attachment_resign_letter").val("");
	});

	$(".cancel_resign").click(function(){
		location.reload();
	});

	$("#confirm_resign").click(function(){

		if($("#resign_last_day").val() == '')
		{
			toastr.error('Please Enter The Last Day On Work', 'Error');
		}
		else if($("#attachment_resign_letter")[0]['files'][0] == undefined && $(".hidden_attachment_resign_letter").val() == '')
		{
			toastr.error('Please Provide Your Resignation Letter', 'Error');
		}
		else
		{
			if ($("#attachment_resign_letter")[0]['files'][0] == undefined) 
			{
				var attachment_flag = 0;
			}
			else
			{
				var attachment_flag = 1;
			}

			var data = new FormData();
			data.append('employee_id', $("#emp_id").val());
			data.append('notice_period', $("#notice_period").val());
			data.append('attachment_flag', attachment_flag);
			data.append('attachment_resign_letter', $("#attachment_resign_letter")[0]['files'][0]);
			data.append('resign_last_day', $("#resign_last_day").val());
			data.append('last_day_confirmed', $("#last_day_confirmed").val());
			data.append('hidden_attachment_resign_letter', $(".hidden_attachment_resign_letter").val());

		 	$.ajax({
	           type: "POST",
	           url: "<?php echo site_url('employee/submit_resignation'); ?>",
	           data: data,
	           dataType: 'json',
	           cache: false,
	           contentType: false,
	           processData: false,
	           success: function(data)
	           {	
	        		location.reload();
	           },
	           error: function(error) 
	           { 
	           		console.log(error);
	           }

	       	});
		}

	});

	$("#approve_resignation").click(function(){

		$.ajax({
           type: "POST",
           url: "<?php echo site_url('employee/approve_or_reject_resignation_date'); ?>",
           data: "&employee_id="+$("#emp_id").val()+"&resign_last_day="+$("#resign_last_day").val()+"&status=1",
           dataType: 'json',
           success: function(data)
           {	
        		location.reload();
           }
       	});
	});

	$("#reject_resignation").click(function(){

		bootbox.confirm({
	        message: "<strong>Confirm Reject?? - Last Working Day will change to "+$('#hidden_notice_period').val()+"</strong>",
	        closeButton: false,
	        buttons: {
	            confirm: {
	                label: 'Yes',
	                className: 'btn_purple'
	            },
	            cancel: {
	                label: 'No',
	                className: 'btn_cancel'
	            }
	        },
	        callback: function (result) 
	        {
	        	if(result == true)
	        	{
	        		$.ajax({
			           type: "POST",
			           url: "<?php echo site_url('employee/approve_or_reject_resignation_date'); ?>",
			           data: "&employee_id="+$("#emp_id").val()+"&resign_last_day="+$("#hidden_notice_period").val()+"&status=0",
			           dataType: 'json',
			           success: function(data)
			           {	
			        		location.reload();
			           }
			       	});
	        	}
	        }
	    });

	});

	//Attach Singapore PR
	if(staff != false)
	{
		// console.log(staff);
		if(staff[0]["nationality_id"] != 165)
		{
			$(".singapore_pr_checkbox").show();
			if(staff[0]["singapore_pr"] == 1)
			{
				$(".attachment_singapore_pr_button").show();
				$(".pr_issued_date").show();
			}
			
		}
		if(staff[0]["singapore_pr"] == 1)
		{
			var file_result_singapore_pr = JSON.parse(staff[0]["attachment_singapore_pr"]);
	        var filename_singapore_pr = "";
	        //console.log(file_result_singapore_pr.length);
	        for(var i = 0; i < file_result_singapore_pr.length; i++)
	        {
		        if(i == 0)
		        {
		            filename_singapore_pr = '<a href="'+base_url+'uploads/singapore_pr/'+file_result_singapore_pr[i]+'" target="_blank">'+file_result_singapore_pr[i]+'</a>';
		        }
		        else
		        {
		            filename_singapore_pr = filename_singapore_pr + ", " + '<a href="'+base_url+'uploads/singapore_pr/'+file_result_singapore_pr[i]+'" target="_blank">'+file_result_singapore_pr[i]+'</a>';
		        }
	        }
	        $("#file_name_singapore_pr").html(filename_singapore_pr);
		}
	}

	$(document).on('change','[id=attachment_singapore_pr]',function(){
	    var filename = "";
	    //console.log(this.files[0]);
	    for(var i = 0; i < this.files.length; i++)
	    {
		    if(i == 0)
		    {
		        filename = this.files[i].name;
		    }
		    else
		    {
		        filename = filename + ", " + this.files[i].name;
		    }
	    }
	    //console.log(filename);
	    $(this).parent().find(".file_name_singapore_pr").html(filename);
	    $(this).parent().find(".hidden_attachment_singapore_pr").val("");
	});

  	$("[name='singapore_pr']").bootstrapSwitch({
	    state: <?php echo isset($staff[0]->singapore_pr)? $staff[0]->singapore_pr : 0 ?>,
	    size: 'normal',
	    onColor: 'purple',
	    onText: 'Yes',
	    offText: 'No',
	    // Text of the center handle of the switch
	    labelText: '&nbsp',
	    // Width of the left and right sides in pixels
	    handleWidth: '55px',
	    // Width of the center handle in pixels
	    labelWidth: 'auto',
	    baseClass: 'bootstrap-switch',
	    wrapperClass: 'wrapper'
	});

	$("[name='singapore_pr']").on('switchChange.bootstrapSwitch', function(event, state) {
		if(state == true)
	    {
			$("[name='hidden_singapore_pr']").val(1);
			$(".attachment_singapore_pr_button").show();
			$(".pr_issued_date").show();

			$(".staff_workpass").val("Not Applicable");
	    	//$(".staff_workpass").attr("disabled", true);
	    	$("#staff_pass_expire").val("");
	    	//$("#staff_pass_expire").attr("disabled", true);
		}
		else
		{
			$("[name='hidden_singapore_pr']").val(0);
			$(".attachment_singapore_pr_button").hide();
			$(".file_name_singapore_pr").html("");
	    	$(".hidden_attachment_singapore_pr").val("");
			$("#pr_issued_date").val("");

			$(".staff_workpass").val("");
			//$(".staff_workpass").attr("disabled", false);
			$("#staff_pass_expire").val("");
			//$("#staff_pass_expire").attr("disabled", false);
		}
	});

	//Attach NRIC/Passport No
	if(staff != false)
	{
		if(0 < JSON.parse(staff[0]["attachment_nric"]).length)
		{
			var file_result_nric = JSON.parse(staff[0]["attachment_nric"]);
	        var filename_nric = "";
	        //console.log(file_result_nric.length);
	        for(var i = 0; i < file_result_nric.length; i++)
	        {
		        if(i == 0)
		        {
		            filename_nric = '<a href="'+base_url+'uploads/nric/'+file_result_nric[i]+'" target="_blank">'+file_result_nric[i]+'</a>';
		        }
		        else
		        {
		            filename_nric = filename_nric + ", " + '<a href="'+base_url+'uploads/nric/'+file_result_nric[i]+'" target="_blank">'+file_result_nric[i]+'</a>';
		        }
	        }
	        $("#file_name_nric").html(filename_nric);
		}
	}

  	$(document).on('change','[id=attachment_nric]',function(){
	    var filename = "";
	    //console.log(this.files[0]);
	    for(var i = 0; i < this.files.length; i++)
	    {
		    if(i == 0)
		    {
		        filename = this.files[i].name;
		    }
		    else
		    {
		        filename = filename + ", " + this.files[i].name;
		    }
	    }
	    //console.log(filename);
	    $(this).parent().find(".file_name_nric").html(filename);
	    $(this).parent().find(".hidden_attachment_nric").val("");
	});
	//Attach Marital Status
	if(staff != false)
	{
		if(staff[0]["marital_status"] == 1)
		{
			$(".attachment_marital_status_button").show();

			var file_result_marital_status = JSON.parse(staff[0]["attachment_marital_status"]);
	        var filename_marital_status = "";
	        //console.log(file_result_marital_status.length);
	        for(var i = 0; i < file_result_marital_status.length; i++)
	        {
		        if(i == 0)
		        {
		            filename_marital_status = '<a href="'+base_url+'uploads/marital_status/'+file_result_marital_status[i]+'" target="_blank">'+file_result_marital_status[i]+'</a>';
		        }
		        else
		        {
		            filename_marital_status = filename_marital_status + ", " + '<a href="'+base_url+'uploads/marital_status/'+file_result_marital_status[i]+'" target="_blank">'+file_result_marital_status[i]+'</a>';
		        }
	        }
	        $("#file_name_marital_status").html(filename_marital_status);
		}
	}

	$(document).on('change','[id=attachment_marital_status]',function(){
	    var filename = "";
	    //console.log(this.files[0]);
	    for(var i = 0; i < this.files.length; i++)
	    {
		    if(i == 0)
		    {
		        filename = this.files[i].name;
		    }
		    else
		    {
		        filename = filename + ", " + this.files[i].name;
		    }
	    }
	    //console.log(filename);
	    $(this).parent().find(".file_name_marital_status").html(filename);
	    $(this).parent().find(".hidden_attachment_marital_status").val("");
	});

	if(!Admin && !Manager)
	{
		$("[name='bond']").bootstrapSwitch({
		    state: <?php echo isset($staff[0]->bond)? $staff[0]->bond : 0 ?>,
		    size: 'normal',
		    onColor: 'purple',
		    onText: 'Yes',
		    offText: 'No',
		    // Text of the center handle of the switch
		    labelText: '&nbsp',
		    // Width of the left and right sides in pixels
		    handleWidth: '55px',
		    // Width of the center handle in pixels
		    labelWidth: 'auto',
		    baseClass: 'bootstrap-switch',
		    wrapperClass: 'wrapper',
		    disabled:true
		});

		
	}
	else
	{
		$("[name='bond']").bootstrapSwitch({
		    state: <?php echo isset($staff[0]->bond)? $staff[0]->bond : 0 ?>,
		    size: 'normal',
		    onColor: 'purple',
		    onText: 'Yes',
		    offText: 'No',
		    // Text of the center handle of the switch
		    labelText: '&nbsp',
		    // Width of the left and right sides in pixels
		    handleWidth: '55px',
		    // Width of the center handle in pixels
		    labelWidth: 'auto',
		    baseClass: 'bootstrap-switch',
		    wrapperClass: 'wrapper'
		});


	}

	// $("[name='bond']").on('switchChange.bootstrapSwitch', function(event, state) {
	// 	if(state == true)
	//     {
	// 		$("[name='hidden_bond']").val(1);
	// 		$(".bond_group").show();
	// 	}
	// 	else
	// 	{
	// 		$("[name='hidden_bond']").val(0);
	// 		$(".bond_group").hide();
	// 	}
	// });

	$("[name='gender']").bootstrapSwitch({
	    state: <?php echo isset($staff[0]->gender)? $staff[0]->gender : 1 ?>,
	    size: 'normal',
	    onColor: 'purple',
	    offColor: 'purple',
	    onText: 'Male',
	    offText: 'Female',
	    // Text of the center handle of the switch
	    labelText: '&nbsp',
	    // Width of the left and right sides in pixels
	    handleWidth: '55px',
	    // Width of the center handle in pixels
	    labelWidth: 'auto',
	    baseClass: 'bootstrap-switch',
	    wrapperClass: 'wrapper'
	});

	$("[name='gender']").on('switchChange.bootstrapSwitch', function(event, state) {
		if(state == true)
	    {
			$("[name='hidden_gender']").val(1);
		}
		else
		{
			$("[name='hidden_gender']").val(0);
		}
	});

  	$("[name='marital_status']").bootstrapSwitch({
	    state: <?php echo isset($staff[0]->marital_status)? $staff[0]->marital_status : 0 ?>,
	    size: 'normal',
	    onColor: 'purple',
	    onText: 'Yes',
	    offText: 'No',
	    // Text of the center handle of the switch
	    labelText: '&nbsp',
	    // Width of the left and right sides in pixels
	    handleWidth: '55px',
	    // Width of the center handle in pixels
	    labelWidth: 'auto',
	    baseClass: 'bootstrap-switch',
	    wrapperClass: 'wrapper'
	});

	$("[name='marital_status']").on('switchChange.bootstrapSwitch', function(event, state) {
		if(state == true)
	    {
			$("[name='hidden_marital_status']").val(1);
			$(".attachment_marital_status_button").show();
		}
		else
		{
			$("[name='hidden_marital_status']").val(0);
			$(".attachment_marital_status_button").hide();
			$(".file_name_marital_status").html("");
	    	$(".hidden_attachment_marital_status").val("");
		}
	});

	// if(Admin == false && user_id != 79)
	if(!Admin && !Manager)
	{
		$("[name='staff_aws_given']").bootstrapSwitch({
		    state: <?php echo isset($staff[0]->aws_given)? $staff[0]->aws_given : 0 ?>,
		    size: 'normal',
		    onColor: 'purple',
		    onText: 'Yes',
		    offText: 'No',
		    // Text of the center handle of the switch
		    labelText: '&nbsp',
		    // Width of the left and right sides in pixels
		    handleWidth: '75px',
		    // Width of the center handle in pixels
		    labelWidth: 'auto',
		    baseClass: 'bootstrap-switch',
		    wrapperClass: 'wrapper',
		    disabled:true
		});
	}
	else
	{
		$("[name='staff_aws_given']").bootstrapSwitch({
		    state: <?php echo isset($staff[0]->aws_given)? $staff[0]->aws_given : 0 ?>,
		    size: 'normal',
		    onColor: 'purple',
		    onText: 'Yes',
		    offText: 'No',
		    // Text of the center handle of the switch
		    labelText: '&nbsp',
		    // Width of the left and right sides in pixels
		    handleWidth: '75px',
		    // Width of the center handle in pixels
		    labelWidth: 'auto',
		    baseClass: 'bootstrap-switch',
		    wrapperClass: 'wrapper'
		});
	}

	$("[name='staff_aws_given']").on('switchChange.bootstrapSwitch', function(event, state) {
		if(state == true)
	    {
			$("[name='hidden_staff_aws_given']").val(1);
		}
		else
		{
			$("[name='hidden_staff_aws_given']").val(0);
		}
	});

    $('form#employee').submit(function(e) {
    	e.preventDefault();

    	var submit_flag = true;

		if($("[name=staff_name]").val() == "")
    	{
    		submit_flag = false;

    		$('li[data-information="particulars"]').addClass("active");
	        $('#w2-particulars').addClass("active");
	        $('li[data-information="settings"]').removeClass("active");
	        $('#w2-settings').removeClass("active");

    		$("[name=staff_name]").focus();

    		toastr.error('Please fill in the NAME', 'Error');
    	}
    	else if($("[name=staff_nationality]").val() == "")
    	{
    		submit_flag = false;

    		$('li[data-information="particulars"]').addClass("active");
	        $('#w2-particulars').addClass("active");
	        $('li[data-information="settings"]').removeClass("active");
	        $('#w2-settings').removeClass("active");

    		$("[name=staff_nationality]").focus();

    		toastr.error('Please fill in the NATIONALITY', 'Error');
    	}
    	else if($("[name=staff_nric_finno]").val() == "")
    	{
    		submit_flag = false;

    		$('li[data-information="particulars"]').addClass("active");
	        $('#w2-particulars').addClass("active");
	        $('li[data-information="settings"]').removeClass("active");
	        $('#w2-settings').removeClass("active");

    		$("[name=staff_nric_finno]").focus();

    		toastr.error('Please fill in the NRIC/PASSPORT NO', 'Error');
    	}
    	else if($("[name=staff_address]").val() == "")
    	{
    		submit_flag = false;

    		$('li[data-information="particulars"]').addClass("active");
	        $('#w2-particulars').addClass("active");
	        $('li[data-information="settings"]').removeClass("active");
	        $('#w2-settings').removeClass("active");

    		$("[name=staff_address]").focus();

    		toastr.error('Please fill in the ADDRESS', 'Error');
    	}
    	else if($("#telephone").val() == "")
    	{
    		submit_flag = false;

    		$('li[data-information="particulars"]').addClass("active");
	        $('#w2-particulars').addClass("active");
	        $('li[data-information="settings"]').removeClass("active");
	        $('#w2-settings').removeClass("active");

    		$("#telephone").focus();

    		toastr.error('Please fill in the MOBILE PHONE', 'Error');
    	}
    	else if($("[name=staff_DOB]").val() == "")
    	{
    		submit_flag = false;

    		$('li[data-information="particulars"]').addClass("active");
	        $('#w2-particulars').addClass("active");
	        $('li[data-information="settings"]').removeClass("active");
	        $('#w2-settings').removeClass("active");

    		$("[name=staff_DOB]").focus();

    		toastr.error('Please fill in the DOB', 'Error');
    	}
    	else if($("[name=staff_status]").val() == "")
    	{
    		submit_flag = false;

    		$('li[data-information="settings"]').addClass("active");
	        $('#w2-settings').addClass("active");
	        $('li[data-information="particulars"]').removeClass("active");
	        $('#w2-particulars').removeClass("active");

    		$("[name=staff_status]").focus();

    		toastr.error('Please fill in the STATUS', 'Error');
    	}
    	else if($("[name=staff_workpass]").val() == "")
    	{
    		submit_flag = false;

    		$('li[data-information="settings"]').addClass("active");
	        $('#w2-settings').addClass("active");
	        $('li[data-information="particulars"]').removeClass("active");
	        $('#w2-particulars').removeClass("active");

    		$("[name=staff_workpass]").focus();

    		toastr.error('Please fill in the WORKPASS', 'Error');
    	}
    	else if($("[name=firm_id]").val() == "")
    	{
    		submit_flag = false;

    		$('li[data-information="settings"]').addClass("active");
	        $('#w2-settings').addClass("active");
	        $('li[data-information="particulars"]').removeClass("active");
	        $('#w2-particulars').removeClass("active");

    		$("[name=firm_id]").focus();

    		toastr.error('Please fill in the FIRM', 'Error');
    	}
    	else if($("[name=staff_joined]").val() == "")
    	{
    		submit_flag = false;

    		$('li[data-information="settings"]').addClass("active");
	        $('#w2-settings').addClass("active");
	        $('li[data-information="particulars"]').removeClass("active");
	        $('#w2-particulars').removeClass("active");

    		$("[name=staff_joined]").focus();

    		toastr.error('Please fill in the DATE JOINED', 'Error');
    	}
    	else if($("[name=staff_designation]").val() == "")
    	{
    		submit_flag = false;

    		$('li[data-information="settings"]').addClass("active");
	        $('#w2-settings').addClass("active");
	        $('li[data-information="particulars"]').removeClass("active");
	        $('#w2-particulars').removeClass("active");

    		$("[name=staff_designation]").focus();

    		toastr.error('Please fill in the DESIGNATION', 'Error');
    	}

    	// console.log('hidden_telephone');
    	// console.log($("#hidden_telephone").val());
    	// console.log('|');
    	// console.log('telephone');
    	// console.log($("#telephone").val());
    	
	    if(submit_flag)
	    {
			//$(".staff_workpass").attr("disabled", false);
			$("#staff_pass_expire").attr("disabled", false);
			$(".general").attr("disabled", false);
		    var formData = new FormData($('form')[0]);
		    $('#loadingmessage').show();
		    $.ajax({
		        type: "POST",
		        url: "<?php echo site_url('employee/create_employee'); ?>",
		        data: formData,
		        dataType: 'json',
	            // Tell jQuery not to process data or worry about content-type
	            // You *must* include these options!
	            // + '&vendor_name_text=' + $(".vendor_name option:selected").text()
	            cache: false,
	            contentType: false,
	            processData: false,
		        success: function(data){
		            // $('#feed-container').prepend(data);
		            // $('input[name=interview_code]').val(data);
		            //console.log(data);
		            //var data = JSON.parse(data);
		            $('#loadingmessage').hide();
		            //$(".staff_workpass").attr("disabled", true);
					$("#staff_pass_expire").attr("disabled", true);

					// if(Admin == false && user_id != 79)
					if(!Admin && !Manager)
					{
						$(".general").attr("disabled", true);
					}
					
					$(".previous_staff_status").val($(".employee_status").val());
					$(".previous_status_date").val($("#status_date").val());

					renewed_value = 1;
		            if(data[0]["status"] == "created"){
		            	//alert("Data is successfully " + data + ".");
		            	$("#staff_id").val(data[0]["employee_id"]);
		            	toastr.success('Information Updated', 'Updated');
		            	// window.location = '<?php echo base_url(); ?>' + "employee/index";
		            }else if(data[0]["status"] == "updated"){
		            	toastr.success('Information Updated', 'Updated');
		            }else{
		            	toastr.error('Something went wrong. Data is failed to create/save. Please try again later.', 'Error');
		            	//alert("Something went wrong. Data is failed to create/save. Please try again later.");
		            }
		        },
		        // error: function() { alert("Error posting feed."); }
		    });
		}
	});

	//Family Tab
	if(family_info)
	{
		$count_family_info = family_info.length - 1;
	}
	else
	{
		$count_family_info = 0;
	}
	$(document).on('click',"#family_Add",function() {

		//console.log($("#body_family_info form").index());
		$count_family_info++;
	 	$a=""; 
		$a += '<form class="tr family_editing sort_id" method="post" enctype="multipart/form-data" name="form'+$count_family_info+'" id="form'+$count_family_info+'">';
		$a += '<div class="hidden"><input type="text" class="form-control employee_id" name="employee_id[]" id="employee_id" value=""/></div>';
		$a += '<div class="hidden"><input type="text" class="form-control" name="family_info_id[]" id="family_info_id" value=""/></div>';
		$a += '<div class="td"><input type="text" name="family_name[]" id="family_name" class="form-control" value=""/><div id="form_family_name"></div></div>';
		$a += '<div class="td"><input type="text" name="nric[]" class="form-control" value="" id="nric"/><div id="form_nric"></div></div>';
		$a += '<div class="td"><div class="input-group date datepicker" data-provide="datepicker"><div class="input-group-addon"><span class="far fa-calendar-alt"></span></div><input type="text" class="form-control dob" id="dob" name="dob[]" value=""></div><div id="form_dob"></div></div>';
		$a += '<div class="td">';
		$a += '<select class="form-control nationality" style="width: 100%;" name="nationality[]" id="nationality"><option value="0">Select Nationality</option></select><div id="form_nationality"></div>';
		$a += '</div>';
		$a += '<div class="td">';
		$a += '<select class="form-control relationship" style="width: 100%;" name="relationship[]" id="relationship"><option value="0">Select Relationship</option></select><div id="form_relationship"></div>';
		$a += '</div>';
		$a += '<div class="td">';
		$a += '<input type="text" name="contact[]" id="contact" class="form-control" value=""/><div id="form_contact"></div>';
		$a += '</div>';
		$a += '<div class="td">';
		$a += "<div class='input-group'><input type='file' style='display:none' id='attachment_proof_of_document' multiple='' name='attachment_proof_of_document[]'><label for='attachment_proof_of_document' class='btn btn_purple attachment_proof_of_document'>Select Attachment</label><br/><span class='file_name_proof_of_document' id='file_name_proof_of_document'></span><input type='hidden' class='hidden_attachment_proof_of_document' name='hidden_attachment_proof_of_document' value=''/></div>";
		$a += '</div>';
		$a += '<div class="td family_action"><div style="display: inline-block; margin-right: 5px; margin-bottom: 5px;"><button type="button" class="btn btn_purple submit_family_info_button" onclick="edit_family(this);">Save</button></div><div style="display: inline-block;"><button type="button" class="btn btn_purple" onclick="delete_family_info(this);">Delete</button></div></div>';
		if(Admin || Manager)
		{
			$a += '<div class="td"><label class="verify_switch"><input name="verify_family_switch" class="verify_family_switch" type="checkbox"><span class="slider round"></span></label></div>';
		}
		else
		{
			$a += '<div class="td"></div>';
		}

		$a += '</form>';
		
		$("#body_family_info").prepend($a); 

		$('#form'+$count_family_info).find(".employee_id").val($("#staff_id").val());


		$("#loadingmessage").show();
		$.ajax({
	        type: "GET",
	        url: "<?php echo site_url('employee/get_nationality'); ?>",
	        async: false,
	        dataType: "json",
	        success: function(data){

	            $("#loadingmessage").hide();
	            $.each(data, function(key, val) {
	                var option = $('<option />');
	                option.attr('value', key).text(val);
	                $('#form'+$count_family_info).find(".nationality").append(option);
	            });

	            //$('#form'+$count_family_info).find(".nationality"+$count_family_info).select2();
	        }
	    });

	    $("#loadingmessage").show();
		$.ajax({
	        type: "GET",
	        url: "<?php echo site_url('employee/get_family_relationship'); ?>",
	        async: false,
	        dataType: "json",
	        success: function(data){

	            $("#loadingmessage").hide();
	            $.each(data, function(key, val) {
	                var option = $('<option />');
	                option.attr('value', key).text(val);
	                $('#form'+$count_family_info).find(".relationship").append(option);
	            });

	            //$('#form'+$count_family_info).find(".relationship"+$count_family_info).select2();
	        }
	    });

		$('#form'+$count_family_info).find('.datepicker').datepicker({
		    format: 'dd MM yyyy',
		});
	});

	if(family_info)
	{
		//console.log(family_info);
		for(var i = 0; i < family_info.length; i++)
		{
			$a=""; 
			$a += '<form class="tr sort_id" method="post" enctype="multipart/form-data" name="form'+i+'" id="form'+i+'">';
			$a += '<div class="hidden"><input type="text" class="form-control" name="employee_id[]" id="employee_id" value="'+family_info[i]["employee_id"]+'"/></div>';
			$a += '<div class="hidden"><input type="text" class="form-control" name="family_info_id[]" id="family_info_id" value="'+family_info[i]["id"]+'"/></div>';
			$a += '<div class="td"><input type="text" name="family_name[]" id="family_name" class="form-control" value="'+family_info[i]["family_name"]+'" disabled="disabled"/><div id="form_family_name"></div></div>';
			$a += '<div class="td"><input type="text" name="nric[]" class="form-control" value="'+family_info[i]["nric"]+'" id="nric" disabled="disabled"/><div id="form_nric"></div></div>';
			$a += '<div class="td"><div class="input-group date datepicker" data-provide="datepicker"><div class="input-group-addon"><span class="far fa-calendar-alt"></span></div><input type="text" class="form-control dob" id="dob" name="dob[]" value="" disabled="disabled"></div><div id="form_dob"></div></div>';
			$a += '<div class="td">';
			$a += '<select class="form-control nationality" style="width: 100%;" name="nationality[]" id="nationality" disabled="disabled"><option value="0">Select Nationality</option></select><div id="form_nationality"></div>';
			$a += '</div>';
			$a += '<div class="td">';
			$a += '<select class="form-control relationship" style="width: 100%;" name="relationship[]" id="relationship" disabled="disabled"><option value="0">Select Relationship</option></select><div id="form_relationship"></div>';
			$a += '</div>';
			$a += '<div class="td">';
			$a += '<input type="text" name="contact[]" id="contact" class="form-control" value="'+family_info[i]["contact"]+'" disabled="disabled"/><div id="form_contact"></div>';
			$a += '</div>';
			$a += '<div class="td">';
			$a += "<div class='input-group'><input type='file' style='display:none' id='attachment_proof_of_document' class='attachment_proof_of_document' multiple='' name='attachment_proof_of_document[]' disabled='disabled'><label for='attachment_proof_of_document' class='btn btn_purple attachment_proof_of_document' disabled='disabled'>Select Attachment</label><br/><span class='file_name_proof_of_document' id='file_name_proof_of_document'></span><input type='hidden' class='hidden_attachment_proof_of_document' name='hidden_attachment_proof_of_document' value=''/></div>";
			$a += '</div>';
			$a += '<div class="td family_action"><div style="display: inline-block; margin-right: 5px; margin-bottom: 5px;"><button type="button" class="btn btn_purple submit_family_info_button" onclick="edit_family(this);">Edit</button></div><div style="display: inline-block;"><button type="button" class="btn btn_purple" onclick="delete_family_info(this);">Delete</button></div></div>';
			if(Admin || Manager)
			{
				if(family_info[i]["user_id"] != user_id)
				{
					$a += '<div class="td"><label class="verify_switch"><input name="verify_family_switch" class="verify_family_switch" type="checkbox" '+((family_info[i]["verify"] == 1)?"checked":"")+'><span class="slider round"></span></label></div>';
				}
				else
				{
					$a += '<div class="td"></div>';
				}
			}
			else
			{
				$a += '<div class="td"></div>';
			}

			$a += '</form>';
			//console.log($a);
			$("#body_family_info").prepend($a); 

			$("#loadingmessage").show();
			$.ajax({
		        type: "GET",
		        url: "<?php echo site_url('employee/get_nationality'); ?>",
		        async: false,
		        dataType: "json",
		        success: function(data){

		            $("#loadingmessage").hide();
		            $.each(data, function(key, val) {
		                var option = $('<option />');
		                option.attr('value', key).text(val);
		                if(family_info[i]["nationality"] != undefined && key == family_info[i]["nationality"])
		                {
		                    option.attr('selected', 'selected');
		                }
		                $('#form'+i).find(".nationality").append(option);
		            });

		            //$('#form'+i).find(".nationality"+i).select2();
		        }
		    });

		    $("#loadingmessage").show();
			$.ajax({
		        type: "GET",
		        url: "<?php echo site_url('employee/get_family_relationship'); ?>",
		        async: false,
		        dataType: "json",
		        success: function(data){

		            $("#loadingmessage").hide();
		            $.each(data, function(key, val) {
		                var option = $('<option />');
		                option.attr('value', key).text(val);
		                if(family_info[i]["relationship"] != undefined && key == family_info[i]["relationship"])
		                {
		                    option.attr('selected', 'selected');
		                }
		                $('#form'+i).find(".relationship").append(option);
		            });
		        }
		    });

			$('#form'+i).find(".dob").val(moment(family_info[i]["dob"]).format('DD MMMM YYYY'));

			$('#form'+i).find('.datepicker').datepicker({
			    format: 'dd MM yyyy',
			});

			$('#form'+i).find(".hidden_attachment_proof_of_document").val(family_info[i]["proof_of_document"]);

			if(0 < JSON.parse(family_info[i]["proof_of_document"]).length)
			{
				var file_result_proof_of_document = JSON.parse(family_info[i]["proof_of_document"]);
		        var filename_proof_of_document = "";
		        //console.log(file_result_proof_of_document.length);
		        for(var p = 0; p < file_result_proof_of_document.length; p++)
		        {
			        if(i == 0)
			        {
			            filename_proof_of_document = '<a href="'+base_url+'uploads/proof_of_document/'+file_result_proof_of_document[p]+'" target="_blank">'+file_result_proof_of_document[p]+'</a>';
			        }
			        else
			        {
			            filename_proof_of_document = filename_proof_of_document + ", " + '<a href="'+base_url+'uploads/proof_of_document/'+file_result_proof_of_document[p]+'" target="_blank">'+file_result_proof_of_document[p]+'</a>';
			        }
		        }
		        $('#form'+i).find("#file_name_proof_of_document").html(filename_proof_of_document);
			}
		}
	}

	$(document).on('change','[id=attachment_proof_of_document]',function(){
	    var filename = "";
	    //console.log(this.files[0]);
	    for(var i = 0; i < this.files.length; i++)
	    {
		    if(i == 0)
		    {
		        filename = this.files[i].name;
		    }
		    else
		    {
		        filename = filename + ", " + this.files[i].name;
		    }
	    }
	    //console.log(filename);
	    $(this).parent().find(".file_name_proof_of_document").html(filename);
	    $(this).parent().find(".hidden_attachment_proof_of_document").val("");
	});


	$(document).on("change","[name='verify_family_switch']",function(element) {

		var checkbox = $(this);
		var checked = this.checked;
		var family_info_id = $(this).parent().parent().parent().find("#family_info_id").val();

		if(family_info_id == "")
		{
			checkbox.prop('checked', false);
			toastr.error("Please save the information before you verify family.", "Error");
		}
		else
		{
			bootbox.confirm({
			    message: "Do you wanna change the verification?",
			    closeButton: false,
			    buttons: {
			        confirm: {
			            label: 'Yes',
			            className: 'btn_purple'
			        },
			        cancel: {
			            label: 'No',
			            className: 'btn_cancel'
			        }
			    },
			    callback: function (result) {
			    	if (result) 
			        {
			        	$("#loadingmessage").show();
						$.ajax({
							type: "POST",
							url: "<?php echo site_url('employee/check_verify_family'); ?>",
							data: {"checked":checked, "staff_id": $("#staff_id").val(), "family_info_id": family_info_id}, // <--- THIS IS THE CHANGE
							dataType: "json",
							success: function(response){
								$("#loadingmessage").hide();
								if(response.Status == 1)
								{
									//$('input[name="verify_family_switch"]:checked').not(checkbox).prop('checked', false);
									toastr.success(response.message, response.title);
								}
							}
						});
					}
					else
					{
						if(checked)
						{
							//console.log(checkbox);
							checkbox.prop('checked', false);
						}
						else
						{
							checkbox.prop('checked', true);
						}
					}
			    }
			})
		}
	});

	function edit_family(element)
	{
		var tr = jQuery(element).parent().parent().parent();
		if(!tr.hasClass("family_editing")) 
		{
			tr.addClass("family_editing");
			tr.find("DIV.td").each(function()
			{
				if(!jQuery(this).hasClass("family_action"))
				{
					jQuery(this).find('input[name="family_name[]"]').attr('disabled', false);
					jQuery(this).find('input[name="nric[]"]').attr('disabled', false);
					jQuery(this).find('input[name="dob[]"]').attr('disabled', false);
					jQuery(this).find('input[name="contact[]"]').attr('disabled', false);
					jQuery(this).find('input[class="attachment_proof_of_document"]').attr('disabled', false);
					jQuery(this).find('.attachment_proof_of_document').attr('disabled', false);
					jQuery(this).find("select").attr('disabled', false);
				} 
				else 
				{
					jQuery(this).find(".submit_family_info_button").text("Save");
				}
			});
		} 
		else 
		{
			// var frm = $(element).closest('form');

			// var frm_serialized = frm.serialize();

			var formData = new FormData($(element).closest('form')[0]);

			family_info_submit(formData, tr);

		}
	}

	function family_info_submit(frm_serialized, tr)
	{
		//console.log(tr);
		$('#loadingmessage').show();
		$.ajax({ //Upload common input
	        url: "<?php echo site_url('employee/add_family_info'); ?>",
	        type: "POST",
	        data: frm_serialized,
	        dataType: 'json',
		    // Tell jQuery not to process data or worry about content-type
		    // You *must* include these options!
		    // + '&vendor_name_text=' + $(".vendor_name option:selected").text()
		    cache: false,
		    contentType: false,
		    processData: false,
	        success: function (response) {
	        	$('#loadingmessage').hide();
	        	//console.log(response.Status);
	            if (response.Status === 1) {
	            	//var errorsDateOfCessation = ' ';
	            	toastr.success(response.message, response.title);
	            	if(response.insert_family_info_id != null)
	            	{
	            		tr.find('input[name="family_info_id[]"]').attr('value', response.insert_family_info_id);
	            	}
	            	tr.removeClass("family_editing");

					tr.find("DIV.td").each(function(){
						if(!jQuery(this).hasClass("family_action"))
						{
							jQuery(this).find('input[name="family_id[]"]').attr('readonly', true);
							jQuery(this).find('input[name="family_name[]"]').attr('disabled', true);
							jQuery(this).find('input[name="nric[]"]').attr('disabled', true);
							jQuery(this).find('input[name="dob[]"]').attr('disabled', true);
							jQuery(this).find('input[name="contact[]"]').attr('disabled', true);
							jQuery(this).find('input[class="attachment_proof_of_document"]').attr('disabled', true);
							jQuery(this).find('.attachment_proof_of_document').attr('disabled', true);
							jQuery(this).find("select").attr('disabled', true);
							
						} 
						else 
						{
							jQuery(this).find(".submit_family_info_button").text("Edit");
						}
					});
				    
	            }
	        }
	    });
	}

	function delete_family_info(element)
	{
		var tr = jQuery(element).parent().parent().parent();

		var family_info_id = tr.find('input[name="family_info_id[]"]').val();

		bootbox.confirm({
		    message: "Do you wanna delete this selected info?",
		    closeButton: false,
		    buttons: {
		        confirm: {
		            label: 'Yes',
		            className: 'btn_purple'
		        },
		        cancel: {
		            label: 'No',
		            className: 'btn_cancel'
		        }
		    },
		    callback: function (result) {
		    	if (result) 
		        {
		        	$('#loadingmessage').show();
					if(family_info_id != undefined)
					{
						$.ajax({ //Upload common input
				            url: "<?php echo site_url('employee/delete_family_info'); ?>",
				            type: "POST",
				            data: {"family_info_id": family_info_id},
				            dataType: 'json',
				            success: function (response) {
				            	$('#loadingmessage').hide();
				            	if(response.Status == 1)
				            	{
				            		tr.remove();
				            		toastr.success("Updated Information.", "Updated");

				            	}
				            }
				        });
					}
		        }
		    }
		})
	}

	// Phone Plugin
	$('.hp').intlTelInput({
	    preferredCountries: [ "sg", "my"],
	    initialCountry: "auto",
	    formatOnDisplay: false,
	    nationalMode: true,
	    geoIpLookup: function(callback) {
	        jQuery.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
	            var countryCode = (resp && resp.country) ? resp.country : "";
	            callback(countryCode);
	        });
	    },
	    customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
	    	// console.log(selectedCountryData['dialCode']);
	    	$("#phone_code").val(selectedCountryData['dialCode']);
	      return "" ;
	    },
	    // utilsScript: "../application/js/utils.js"
	});

	// History Tab (Event table Add)
	// Event Tab
	if(event_info)
	{
		$count_event_info = event_info.length - 1;
	}
	else
	{
		$count_event_info = 0;
	}

	function edit_event(element)
	{
		$("#loadingmessage").show();

		var data = new FormData();
			data.append('employee_id', employee_id);
			data.append('attachment',$(".hidden_event_attachment").val());

		$.ajax({
	        type: "POST",
	        url: "<?php echo site_url('employee/add_event_info'); ?>",
			data: data,
			dataType: "json",
			cache: false,
	        contentType: false,
	        processData: false,
	        success: function(data)
	        {
	            $("#loadingmessage").hide();
	            
	            $(".event_action").hide();

	            location.reload();
	        }
	    });
	}

	function show_new_declaration(data)
	{
		$count_event_info++;

		$a=""; 
		$a += '<form class="tr sort_id declaration" method="post" enctype="multipart/form-data" name="Eventform'+$count_event_info+'" id="Eventform'+$count_event_info+'">';
		$a += '<div class="hidden"><input type="text" class="form-control employee_id " name="employee_id[]" id="employee_id" value=""/></div>';
		$a += '<div class="hidden"><input type="text" class="form-control" name="event_info_id[]" id="event_info_id" value=""/></div>';
		$a += '<div class="hidden"><input type="text" class="form-control" name="last_date" id="last_date" value="null"/></div>';
		$a += '<div class="td"><div class="input-group date datepicker" data-provide="datepicker"><div class="input-group-addon"><span class="far fa-calendar-alt"></span></div><input type="text" class="form-control eventDate" id="eventDate" name="eventDate[]" disabled="disabled"></div><div id="form_eventDate"></div></div>';
		$a += '<div class="td">';
		$a += '<select onchange=change_event('+$count_event_info+') class="form-control event" style="width: 100%;" name="event[]" id="event" disabled="disabled"><option value=" ">Select Event</option></select><div id="form_event"></div>';
		$a += '</div>';
		$a += '<div class="td">';
		$a += "<div class='input-group'><span class='file_name_attachment' id='file_name_attachment'></span><input type='hidden' class='hidden_event_attachment' name='hidden_event_attachment' value=''/></div>";
		$a += '</div>';

		$a += '<div class="td event_action"><button type="button" class="btn btn_purple submit_event_info_button" onclick="edit_event(this);">Read & Confirm</button></div>';

		$a += '</form>';
		$("#body_event_info").prepend($a); 


		$('#Eventform'+$count_event_info).find(".employee_id").val(employee_id);

		var date = new Date();
		var today = new Date(date.getFullYear()+'-'+(date.getMonth()+1)+'-'+date.getDate());
		$('#Eventform'+$count_event_info).find(".eventDate").val(moment(today).format('DD MMMM YYYY'));

	    $("#loadingmessage").show();
	    
		$.ajax({
	        type: "GET",
	        url: "<?php echo site_url('action/get_event_type'); ?>",
	        async: false,
	        dataType: "json",
	        success: function(data){

	            $("#loadingmessage").hide();
	            $.each(data, function(key, val) {
	                var option = $('<option />');
	                option.attr('value', key).text(val);

	                if(key == 10 && val == 'Declaration')
	                {
	                    option.attr('selected', 'selected');
	                }

	                $('#Eventform'+$count_event_info).find(".event").append(option);
	            });
	        }
	    });

		$('#Eventform'+$count_event_info).find('.datepicker').datepicker({
		    format: 'dd MM yyyy',
		});

		$('#Eventform'+$count_event_info).find(".hidden_event_attachment").val(data['filename']);
		filename_proof_of_document = '<a href="'+base_url+'pdf/document/'+data['filename']+'" target="_blank">'+data['filename']+'</a>';
		$('#Eventform'+$count_event_info).find("#file_name_attachment").html(filename_proof_of_document);
	}

	if(event_info)
	{
		for(var i = 0; i < event_info.length; i++)
		{
			$a=""; 
			$a += '<form class="tr sort_id" method="post" enctype="multipart/form-data" name="Eventform'+i+'" id="Eventform'+i+'">';
			
			$a += '<div class="hidden"><input type="text" class="form-control employee_id" name="employee_id[]" id="employee_id" value="'+event_info[i]["employee_id"]+'"/></div>';

			$a += '<div class="hidden"><input type="text" class="form-control" name="event_info_id[]" id="event_info_id" value="'+event_info[i]["id"]+'"/></div>';

			$a += '<div class="hidden"><input type="text" class="form-control" name="last_date" id="last_date" value="null"/></div>';

			$a += '<div class="td"><div class="input-group date datepicker" data-provide="datepicker"><div class="input-group-addon"><span class="far fa-calendar-alt"></span></div><input type="text" class="form-control eventDate" id="eventDate" name="eventDate[]" disabled="disabled"></div><div id="form_eventDate"></div></div>';

			$a += '<div class="td">';
			$a += '<select onchange=change_event('+i+') class="form-control event" style="width: 100%;" name="event[]" id="event" disabled="disabled"><option value=" ">Select Event</option></select><div id="form_event"></div>';
			$a += '</div>';

			$a += '<div class="td">';
			$a += "<div class='input-group'><span class='file_name_attachment' id='file_name_attachment'></span><input type='hidden' class='hidden_event_attachment' name='hidden_event_attachment' value=''/></div>";
			$a += '</div>';
			
			$a += '</form>';
			$("#body_event_info").prepend($a); 

		    $("#loadingmessage").show();
			$.ajax({
		        type: "GET",
		        url: "<?php echo site_url('action/get_event_type'); ?>",
		        async: false,
		        dataType: "json",
		        success: function(data){

		            $("#loadingmessage").hide();

		            $.each(data, function(key, val) {
		                var option = $('<option />');
		                option.attr('value', key).text(val);
		                if(event_info[i]["event"] != undefined && key == event_info[i]["event"])
		                {
		                    option.attr('selected', 'selected');
		                }
		                $('#Eventform'+i).find(".event").append(option);
		            });

		            $('#Eventform'+i).find(".relationship"+i).select2();
		        }
		    });

		    $('#Eventform'+i).find(".eventDate").val(moment(event_info[i]["date"]).format('DD MMMM YYYY'));

			$('#Eventform'+i).find('.datepicker').datepicker({
			    format: 'dd MM yyyy',
			});


			$('#Eventform'+i).find(".hidden_event_attachment").val(event_info[i]["attachment"]);

			filename_proof_of_document = '<a href="'+base_url+'pdf/document/'+event_info[i]["attachment"]+'" target="_blank">'+event_info[i]["attachment"]+'</a>';

			$('#Eventform'+i).find("#file_name_attachment").html(filename_proof_of_document);
		}
	}


	$('.show_telephone').click(function(e){
	    e.preventDefault();
	    $(this).parent().parent().find(".telephone_toggle").toggle();
	    // console.log($(this).parent().parent());
	    var icon = $(this).find(".fa");
	    if(icon.hasClass("fa-arrow-down"))
	    {
	        icon.addClass("fa-arrow-up").removeClass("fa-arrow-down");
	        $(this).find(".toggle_word").text('Show less');
	    }
	    else
	    {
	        icon.addClass("fa-arrow-down").removeClass("fa-arrow-up");
	        $(this).find(".toggle_word").text('Show more');
	    }
	});

	$(document).on('blur', '.check_empty_telephone', function(){
	    $(this).parent().parent().find(".hidden_telephone").attr("value", $(this).intlTelInput("getNumber", intlTelInputUtils.numberFormat.E164));
	    $(this).parent().parent().find(".telephone_primary").attr("value", $(this).intlTelInput("getNumber", intlTelInputUtils.numberFormat.E164));
	});

	$(document).ready(function() 
	{
	    $(document).on('click', '.telephone_primary', function(event){   
	        event.preventDefault();
	        var telephone_primary_radio_button = $(this);
	        bootbox.confirm("Are you comfirm set as primary for this Telephone?", function (result) {
	            if (result) {
	                telephone_primary_radio_button.prop( "checked", true );
	                $( '#form_telephone' ).html("");
	            }
	        });
	    });


	    $(".check_empty_telephone").on({
	      keydown: function(e) {
	        if (e.which === 32)
	          return false;
	      },
	      change: function() {
	        this.value = this.value.replace(/\s/g, "");
	      }
	    });

	    $(".addMore_telephone").click(function(){
	        var number = $(".main_telephone").intlTelInput("getNumber", intlTelInputUtils.numberFormat.E164);

	        var countryData = $(".main_telephone").intlTelInput("getSelectedCountryData");

	        $(".telephone_toggle").show();
	        $(".show_telephone").find(".fa").addClass("fa-arrow-up").removeClass("fa-arrow-down");
	        $(".show_telephone").find(".toggle_word").text('Show less');

	        $(".fieldGroupCopy_telephone").find('.second_telephone').attr("value", $(".main_telephone").val());
	        $(".fieldGroupCopy_telephone").find('.hidden_telephone').attr("value", number);
	        $(".fieldGroupCopy_telephone").find('.telephone_primary').attr("value", number);
	        //$(".fieldGroupCopy").find('.second_local_fix_line').intlTelInput("setNumber", number);
	        //$(".fieldGroupCopy_telephone").find('.second_telephone').intlTelInput("setCountry", countryData.iso2);

	        var fieldHTML = '<div class="input-group fieldGroup_telephone" style="margin-top:10px;">'+$(".fieldGroupCopy_telephone").html()+'</div>';

	        //$('body').find('.fieldGroup_telephone:first').after(fieldHTML);
	        $( fieldHTML).prependTo(".telephone_toggle");

	        $('.telephone_toggle .fieldGroup_telephone').eq(0).find('.second_hp').intlTelInput({
	            preferredCountries: [ "sg", "my"],
	            formatOnDisplay: false,
	            nationalMode: true,
	            initialCountry: countryData.iso2,
	            geoIpLookup: function(callback) {
	                jQuery.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
	                    var countryCode = (resp && resp.country) ? resp.country : "";
	                    callback(countryCode);
	                });
	            },
	            customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
	              return "" ;
	            },
	            utilsScript: "../themes/default/js/utils.js"
	        });

	        $('.telephone_toggle .fieldGroup_telephone').eq(0).find('.second_hp').on({
	          keydown: function(e) {
	            if (e.which === 32)
	              return false;
	          },
	          change: function() {
	            this.value = this.value.replace(/\s/g, "");
	          }
	        });

	        if ($(".main_telephone_primary").is(":checked")) 
	        {
	            $('.telephone_toggle .fieldGroup_telephone').eq(0).find('.telephone_primary').prop( "checked", true );
	        }


	        $(".button_increment_telephone").css({"visibility": "hidden"});

	        if ($(".telephone_toggle").find(".second_telephone").length > 0) 
	        {
	            $(".show_telephone").css({"visibility": "visible"});

	        }
	        else {
	            $(".show_telephone").css({"visibility": "hidden"});
	            
	        }
	       
	        $(".main_telephone").val("");
	        $(".main_telephone").parent().parent().find(".hidden_telephone").val("");
	        $(".main_telephone").parent().parent().find(".telephone_primary").val("");
	        $(".fieldGroupCopy_telephone").find('.second_telephone').attr("value", "");
	        $(".fieldGroupCopy_telephone").find('.hidden_telephone').attr("value", "");
	        $(".fieldGroupCopy_telephone").find('.telephone_primary').attr("value", "");

	    });

	    $("body").on("click",".remove_telephone",function(){ 
	        var remove_telephone_button = $(this);
	        bootbox.confirm("Are you comfirm delete this Telephone?", function (result) {
	            if (result) {

	                remove_telephone_button.parents(".fieldGroup_telephone").remove();

	                if (remove_telephone_button.parent().find(".telephone_primary").is(":checked")) 
	                {
	                    if ($(".telephone_toggle").find(".second_telephone").length > 0) 
	                    {
	                        $('.telephone_toggle .fieldGroup_telephone').eq(0).find('.telephone_primary').prop( "checked", true );
	                    }
	                    else
	                    {
	                        $(".main_telephone_primary").prop( "checked", true );
	                    }
	                    
	                }

	                if ($(".telephone_toggle").find(".second_telephone").length > 0) 
	                {
	                    $(".show_telephone").css({"visibility": "visible"});

	                }
	                else {
	                    $(".show_telephone").css({"visibility": "hidden"});
	                    
	                }
	            }
	        });
	    });

	    $('.main_telephone').keyup(function(){

	        if ($(this).val()) {
	            $(".button_increment_telephone").css({"visibility": "visible"});

	        }
	        else {
	            $(".button_increment_telephone").css({"visibility": "hidden"});
	        }
	    });

	    if ($(".telephone_toggle").find(".second_telephone").length > 0) 
	    {
	        $(".show_telephone").css({"visibility": "visible"});
	        $(".telephone_toggle").hide();

	    }
	    else {
	        $(".show_telephone").css({"visibility": "hidden"});
	        $(".telephone_toggle").hide();
	    }
	});

	if(telephone != null)
	{
	    for (var h = 0; h < telephone.length; h++) 
	    {
	        var firmTelephoneArray = telephone[h].split(',');

	        if(firmTelephoneArray[2] == 1)
	        {
	            $(".fieldGroup_telephone").find('.main_telephone').intlTelInput("setNumber", firmTelephoneArray[1]);
	            $(".fieldGroup_telephone").find('.main_hidden_telephone').attr("value", firmTelephoneArray[1]);
	            $(".fieldGroup_telephone").find('.main_telephone_primary').attr("value", firmTelephoneArray[1]);
	            $(".fieldGroup_telephone").find(".button_increment_telephone").css({"visibility": "visible"});
	        }
	        else
	        {
	            
	            $(".fieldGroupCopy_telephone").find('.hidden_telephone').attr("value", firmTelephoneArray[1]);
	            $(".fieldGroupCopy_telephone").find('.telephone_primary').attr("value", firmTelephoneArray[1]);


	            var fieldHTML = '<div class="input-group fieldGroup_telephone" style="margin-top:10px;">'+$(".fieldGroupCopy_telephone").html()+'</div>';

	            //$('body').find('.fieldGroup_telephone:first').after(fieldHTML);
	            $( fieldHTML).prependTo(".telephone_toggle");

	            $('.telephone_toggle .fieldGroup_telephone').eq(0).find('.second_hp').intlTelInput({
	                preferredCountries: [ "sg", "my"],
	                formatOnDisplay: false,
	                nationalMode: true,
	                geoIpLookup: function(callback) {
	                    jQuery.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
	                        var countryCode = (resp && resp.country) ? resp.country : "";
	                        callback(countryCode);
	                    });
	                },
	                customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
	                  return "" ;
	                },
	                utilsScript: "../themes/default/js/utils.js"
	            });

	            $('.telephone_toggle .fieldGroup_telephone').eq(0).find('.second_hp').intlTelInput("setNumber", firmTelephoneArray[1]);

	            $('.telephone_toggle .fieldGroup_telephone').eq(0).find('.second_hp').on({
	              keydown: function(e) {
	                if (e.which === 32)
	                  return false;
	              },
	              change: function() {
	                this.value = this.value.replace(/\s/g, "");
	              }
	            });

	            $(".fieldGroupCopy_telephone").find('.hidden_telephone').attr("value", "");
	            $(".fieldGroupCopy_telephone").find('.telephone_primary').attr("value", "");
	        }
	    }
	}
	else
	{
	    $(".fieldGroup_telephone").find('.main_telephone').intlTelInput("setNumber", "");
	}

	// validation for number only
	function validate(evt) {
  		var theEvent = evt || window.event;

  		// Handle paste
  		if (theEvent.type === 'paste') {
      		key = event.clipboardData.getData('text/plain');
  		} else {
  			// Handle key press
      		var key = theEvent.keyCode || theEvent.which;
      		key = String.fromCharCode(key);
  		}
  		var regex = /[0-9]/;
  		if( !regex.test(key) ) {
    		theEvent.returnValue = false;
    		if(theEvent.preventDefault) theEvent.preventDefault();
  		}
	}

	$(document).ready(function () {
    	$('.staff_department option[value=7]').remove();
    	$('.staff_office option[value=1]').remove();

    	var department = <?php echo json_encode(isset($staff[0]->department)?$staff[0]->department:"")?>;
    	var designation = <?php echo json_encode(isset($staff[0]->designation)?$staff[0]->designation:"")?>;

    	$.ajax({
           type: "POST",
           url: "<?php echo site_url('employee/get_designation'); ?>",
           data: "&department="+ department,
           success: function(data)
           {
           		var result = JSON.parse(data);

           		$(".charge_out_rate_designation_div").remove();
				
				var dropdown = '<div class="charge_out_rate_designation_div" style="width: 40%;"><select class="form-control charge_out_rate_designation general" id="staff_designation" name="staff_designation" style="width: 100% !important"><option value="" selected="selected">Please Select Designation</option>';

           		if(result != '')
           		{
				    for($i=0;$i<result.length;$i++)
				    {
				    	dropdown += '<option value="'+result[$i]['designation']+'">'+result[$i]['designation']+'</option>';
				    }
           		}
           		
           		dropdown += '</select></div>';
			    $(".charge_out_rate_designation_div_div").append(dropdown);

			    if(designation != ''){
		       		$(".charge_out_rate_designation").val(designation).trigger('change');
		       		// $(".designation").val("");

		   //     	if(Admin == false && user_id != 79)
					if(!Admin && !Manager)
					{
						$(".general").attr("disabled", true);
					}
		       	}
          	}
       	});
    });

    $(document).on('change', '.staff_department', function() {

	// $(".charge_out_rate_department_list").change(function(e) {

			var form = $(this);
        
	        var department = $(".staff_department").val();

	        if(department == ''){
	        	$(".charge_out_rate_designation_div").remove();

			    var dropdown = '<div class="col-sm-3 form-inline charge_out_rate_designation_div" style="width: 28.9%"><select class="form-control charge_out_rate_designation" id="staff_designation" name="staff_designation" style="width: 100% !important"><option value="" selected="selected">Please Select Designation</option></select></div>';

			    $(".charge_out_rate_designation_div_div").append(dropdown);
			    $(".int_select2").select2();
	        }
	        else{

	        	$.ajax({
		           type: "POST",
		           url: "<?php echo site_url('employee/get_designation'); ?>",
		           data: "&department="+ department,
		           success: function(data)
		           {	
		           		var result = JSON.parse(data);

		           		$(".charge_out_rate_designation_div").remove();
						
						var dropdown = '<div class="charge_out_rate_designation_div" style="width: 40%;"><select class="form-control charge_out_rate_designation" id="staff_designation" name="staff_designation" style="width: 100% !important"><option value="" selected="selected">Please Select Designation</option>';

		           		if(result != ''){

						    for($i=0;$i<result.length;$i++)
						    {
						    	dropdown += '<option value="'+result[$i]['designation']+'">'+result[$i]['designation']+'</option>';
						    }

		           		}
		           		
		           		dropdown += '</select></div>';
					    $(".charge_out_rate_designation_div_div").append(dropdown);
		           }
		       	});
	        }
    });


    if(staff != false)
	{	
		// if(Admin == false && user_id != 79)
		if(!Admin && !Manager)
		{
			$(".general").attr("disabled", true);
		}
	}

	if(<?php echo !empty($staff[0]->pic)?1:0 ?>){
        $('.showImage').show();
        $('.photo').hide();

        $('#editProfilePicBtn').hide(); // hide edit button.
        $('#removeProfilePicBtn').show(); // show remove button.
    }

    // function monthsDiff(d1, d2)
    // {
	// 	let date1 = new Date(d1);
	//   	let date2 = new Date(d2);
	//   	let years = yearsDiff(d1, d2);

	// 	console.log(date1, date2);
	//   	let months =(years * 12) + (date2.getMonth() - date1.getMonth()) ;
	//   	return months;
	// }

	function yearsDiff(d1, d2) 
	{
	    let date1 = new Date(d1);
	    let date2 = new Date(d2);
	    let yearsDiff =  date2.getFullYear() - date1.getFullYear();
	    return yearsDiff;
	}

	$(document).on('click','[id=EC_Proceed]',function(){

		var firm_id = '<?php echo isset($staff[0]->firm_id)?$staff[0]->firm_id:'' ?>';

		var data = [
			firm_id,
			$('[id=EC_name]').val(),
			$('[id=EC_nric_fin_no]').val(),
			$('[id=ol_job_title]').val(),
			$('[id=ol_effective_from]').val(),
			$('[id=ol_work_hour]').val(),
			$('[id=ol_vacation_leave]').val(),
			$('[id=EC_today_date]').val(),
			$('.salary_form_currency').val(),
			$('#new_salary').val(),
			$('#salary_effective_from').val(),
			$('[name=hidden_promotion]').val(),
			employee_id,
			$('#EC_designation').val(),

		];

		if($('[id=EC_staff_department]').val() == "")
		{
			toastr.error('Please Select The Department', 'Error');
		}
		else if($('[id=EC_designation]').val() == "")
		{
			toastr.error('Please Select The Designation', 'Error');
		}
		else if($('[id=ol_effective_from]').val() == "")
		{
			toastr.error('Please Enter The Date Of Commencement', 'Error');
		}
		else if($('[id=ol_work_hour]').val() == "")
		{
			toastr.error('Please Enter The Working Hours Per Week', 'Error');
		}
		else if($('[id=ol_vacation_leave]').val() == "")
		{
			toastr.error('Please Enter The Vacation Leave', 'Error');
		}
		else if($('.salary_form_currency').val() == "")
		{
			toastr.error('Please Select Currency', 'Error');
		}
		else if($('#new_salary').val() == "")
		{
			toastr.error('Please Enter The Salary', 'Error');
		}
		else if($('#salary_effective_from').val() == "")
		{
			toastr.error('Please Enter The Salary Effective Date', 'Error');
		}
		else
		{
			$('#loadingmessage').show();
			document.getElementById('EC_Proceed').disabled = "true";

			$.ajax({
				type: "POST",
				url: add_salary_link,
				data: {"data":data}, // <--- THIS IS THE CHANGE
				dataType: "json",
				'async':false,
				success: function(response)
				{
					// console.log(response);	
					$("#principal_statement").modal('hide');
					window.open(response.link,'_blank');
					if(response.link2)
					{
						window.open(response.link2,'_blank');
					}
					filename = response.filename;
					var key = response.data;

					$('#loadingmessage').hide();
					document.getElementById('EC_Proceed').removeAttribute('disabled');

					var content = jQuery('#clone_salary_model tr'),
					element = null,    
					element = content.clone();
					
					element.find("[name='effective_start_date[]']").attr("value",key.effective_date);
					var currency = element.find("[name='salary_currency[]']");
					element.find("[name='salary_currency[]']").val(key.currency);
					element.find("[name='staff_salary[]']").val(key.new_salary);

					element.find("[name='effective_start_date[]']").attr("disabled", true);
					currency.attr("disabled", true);
					element.find("[name='staff_salary[]']").attr("disabled", true);

					// info_id.val(key.id);
					// console.log(info_id[0]);
					// info_id.val(key.id);

					$('#body_add_salary').prepend(element);
					// element.prepend('#body_add_salary');

					currency.select2();


					$('.effective_date').datepicker({ 
						dateFormat:'dd/mm/yyyy',
						autoclose: true,
					});
				}               
			});

			// $('#form'+$count_event_info).find(".file_name_attachment").html('<a href="'+base_url+'/pdf/document/'+filename+'" target="_blank">'+filename+'</a>');

			// $('#form'+$count_event_info).find(".hidden_event_attachment").val(filename);

			// $("#employment_contract_modal").modal("hide");

			// $("#AL").val($('[id=ol_vacation_leave]').val());
			// $("#department").val($('[id=EC_staff_department]').val());
			// $("#designation").val($('[id=EC_designation]').val());
		}
	});

	$(document).on('click','[id=bond_Proceed]',function(){

		var firm_id = '<?php echo isset($staff[0]->firm_id)?$staff[0]->firm_id:'' ?>';

		var data = [
			firm_id,
			$('[id=employee_name]').val(),
			$('[id=employee_nric_fin_no]').val(),
			$('[id=readonly_job_title]').val(),
			$('#bond_period').val(),
			$('.bond_form_currency').val(),
			$('#bond_allowance').val(),
			$('#bond_effective_from').val(),
			employee_id,
		];

		if($('[id=bond_period]').val() == "")
		{
			toastr.error('Please Enter The Bond Period', 'Error');
		}
		else if($('.bond_form_currency').val() == "")
		{
			toastr.error('Please Select Currency', 'Error');
		}
		else if($('#bond_allowance').val() == "")
		{
			toastr.error('Please Enter The Allowance', 'Error');
		}
		else if($('#bond_effective_from').val() == "")
		{
			toastr.error('Please Enter The Bond Effective Date', 'Error');
		}
		else
		{
			$('#loadingmessage').show();
			document.getElementById('bond_Proceed').disabled = "true";

			$.ajax({
				type: "POST",
				url: add_bond_link,
				data: {"data":data}, // <--- THIS IS THE CHANGE
				dataType: "json",
				'async':false,
				success: function(response)
				{
					// console.log(response);	
					$("#bond_statement").modal('hide');
					window.open(response.link,'_blank');
					if(response.link2)
					{
						window.open(response.link2,'_blank');
					}
					var key = response.data;

					$('#loadingmessage').hide();
					document.getElementById('bond_Proceed').removeAttribute('disabled');

					var content = jQuery('#clone_bond_model tr'),
					element = null,    
					element = content.clone();
					
					element.find("[name='bond_start_date[]']").attr("value",key.bond_start_date);
					var currency = element.find("[name='bond_currency[]']");
					element.find("[name='bond_currency[]']").val(key.currency);
					element.find("[name='bond_period[]']").val(key.bond_period);
					element.find("[name='bond_allowance[]']").val(key.bond_allowance);
					var startDate = new Date(key.bond_start_date);
					var today 	  = new Date();
					var completed_month = monthDiff(startDate, today, key.bond_period);
					element.find("[name='bond_completed[]']").val(completed_month);
					element.find("[name='total_bond_allowance[]']").val(completed_month*key.bond_allowance);

					

					// element.find("[name='effective_start_date[]']").attr("disabled", true);
					// currency.attr("disabled", true);
					// element.find("[name='staff_salary[]']").attr("disabled", true);

					// info_id.val(key.id);
					// console.log(info_id[0]);
					// info_id.val(key.id);

					$('#body_add_bond').prepend(element);
					// element.prepend('#body_add_salary');

					currency.select2();


					// $('.effective_date').datepicker({ 
					// 	dateFormat:'dd/mm/yyyy',
					// 	autoclose: true,
					// });
				}               
			});

			// $('#form'+$count_event_info).find(".file_name_attachment").html('<a href="'+base_url+'/pdf/document/'+filename+'" target="_blank">'+filename+'</a>');

			// $('#form'+$count_event_info).find(".hidden_event_attachment").val(filename);

			// $("#employment_contract_modal").modal("hide");

			// $("#AL").val($('[id=ol_vacation_leave]').val());
			// $("#department").val($('[id=EC_staff_department]').val());
			// $("#designation").val($('[id=EC_designation]').val());
		}
	});

	function monthDiff(d1, d2, totalMonth) {
		var months;
		console.log(totalMonth);
		months = (d2.getFullYear() - d1.getFullYear()) * 12;
		months -= d1.getMonth();
		months += d2.getMonth();
		return months <= 0 ? 0 : months > totalMonth ? totalMonth : months;
	}


</script>  
<script src="<?= base_url()?>application/modules/employee/js/create.js" charset="utf-8"></script>
