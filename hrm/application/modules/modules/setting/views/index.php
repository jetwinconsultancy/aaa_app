<script src="<?= base_url() ?>node_modules/bootstrap-datepicker/dist/js/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker.css" />
<link rel="stylesheet" href="<?= base_url() ?>application/css/plugin/toastr.min.css" />
<script src="<?= base_url() ?>application/js/toastr.min.js"></script>

<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/dataTables.checkboxes.min.js"></script>
<link rel="stylesheet" href="<?=base_url()?>assets/vendor/jquery-datatables/media/css/dataTables.checkboxes.css" />
<script src="<?=base_url()?>assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/natural.js"></script>

<script src="<?= base_url() ?>node_modules/bootbox/bootbox.min.js"></script>
<script src="<?= base_url() ?>node_modules/moment/moment.js"></script>

<style type="text/css">
	#age_group_tbl th, #age_group_tbl td{
		text-align: center;
		padding-bottom: 5px;

	}

	#nationality_tbl th, #nationality_tbl td{
		text-align: center;
		padding-bottom: 5px;

	}

	tr.spaceUnder>td {
		padding-bottom: 0.5em !important;
	}
	
</style>

<section role="main" class="content_section" style="margin-left:0;">
	<section class="panel" style="margin-top: 30px;">
		<div class="panel-body">
			<div class="col-md-12">
				<div class="tabs">
					<ul class="nav nav-tabs nav-justify">	

						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown"><span class="badge hidden-xs">1</span> Leave <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li class="check_state " data-information="type_of_leave">
									<a href="#w2-type_of_leave" data-toggle="tab" class="text-center" style="text-align: left;">
										Type of Leave
									</a>
								</li>
								<li class="check_state  " data-information="approval_cap">
									<a href="#w2-approval_cap" data-toggle="tab" class="text-center" style="text-align: left;">
										Approval Cap
									</a>
								</li>
								<li class="check_state  " data-information="block_leave">
									<a href="#w2-block_leave" data-toggle="tab" class="text-center" style="text-align: left;">
										Block Leave
									</a>
								</li>
								<li class="check_state  " data-information="leave_cycle">
									<a href="#w2-leave_cycle" data-toggle="tab" class="text-center" style="text-align: left;">
										Leave Cycle
									</a>
								</li>
								<li class="check_state  " data-information="carry_forward_period">
									<a href="#w2-carry_forward_period" data-toggle="tab" class="text-center" style="text-align: left;">
										Carry Forward Period
									</a>
								</li>
							</ul>
						</li>

						<li class="check_state " data-information="block_holiday">
							<a href="#w2-block_holiday" data-toggle="tab" class="text-center">
								<span class="badge hidden-xs">2</span>
								Public Holiday
							</a>
						</li>
						<li class="check_state" data-information="partner_list">
							<a href="#w2-partner_list" data-toggle="tab" class="text-center">
								<span class="badge hidden-xs">3</span>
								Partner
							</a>
						</li>
						<!-- <li class="check_state" data-information="event">
							<a href="#w2-event" data-toggle="tab" class="text-center">
								<span class="badge hidden-xs">4</span>
								Event
							</a>
						</li> -->
						<li class="check_state" data-information="type_of_jobs">
							<a href="#w2-type_of_jobs" data-toggle="tab" class="text-center">
								<span class="badge hidden-xs">5</span>
								Type of Jobs
							</a>
						</li>
						<!-- <li class="check_state" data-information="offices">
							<a href="#w2-offices" data-toggle="tab" class="text-center">
								<span class="badge hidden-xs">6</span>
								Offices
							</a>
						</li>
						<li class="check_state" data-information="department">
							<a href="#w2-department" data-toggle="tab" class="text-center">
								<span class="badge hidden-xs">7</span>
								Department
							</a>
						</li> -->
						<li class="check_state" data-information="charge_out_rate">
							<a href="#w2-charge_out_rate" data-toggle="tab" class="text-center">
								<span class="badge hidden-xs">6</span>
								Charge-Out Rate
							</a>
						</li>

						<li class="check_state" data-information="team_shifts">
							<a href="#w2-team_shifts" data-toggle="tab" class="text-center">
								<span class="badge hidden-xs">7</span>
								Team Shifts
							</a>
						</li>

						<li class="check_state" data-information="institution">
							<a href="#w2-institution" data-toggle="tab" class="text-center">
								<span class="badge hidden-xs">8</span>
								Institution
							</a>
						</li>

						<li class="check_state" data-information="cpf">
							<a href="#w2-cpf" data-toggle="tab" class="text-center">
								<span class="badge hidden-xs">9</span>
								CPF
							</a>
						</li>
					</ul>
					<div class="tab-content clearfix">
<!-- LEAVE TYPES ----------------------------------------------------------------------------------------------------------------------------------------------- -->
						<div id="w2-type_of_leave" class="tab-pane active">
							<form id="type_of_leave_submit">
								<div class="input-container">
									<h3>Type of Leave</h3>
								</div>
								<input type="hidden" name="type_of_leave_id" class="type_of_leave_id" value="">
								<div class="col-md-12">
									<div class="form-group">
						                <div style="width: 100%;">
						                    <div style="width: 25%;float:left;margin-right: 20px;">
						                        <label>Leave Name: </label>
						                    </div>
						                    <div style="width: 30%;float: left;">
						                        <div style="width:100%">
						                        	<input type="text" id="leave_name" class="form-control" name="leave_name">
						                        </div>
						                    </div>
						                </div>
						            </div>
									<div class="form-group">
										<div style="width: 100%;">
						                	<div style="width: 25%;float:left;margin-right: 20px;">
						                        <label class="label_one">Days:</label>
						                        <label class="label_two" style="display: none;">Days (Per Subject):</label>
						                        <label class="label_three" style="display: none;">Days (Immediate Family):</label>
						                        <label class="label_four" style="display: none;">Days (Singapore Office - Singaporean):</label>
						                    </div>
						                	<div style="width: 15%;float: left;">
						                        <div style="width:100%">
						                        	<input type="number" id="leave_days" class="form-control" style="width:50%; display:inline-block;" name="leave_days" value="" onkeypress='validate(event)' required />
						                        </div>
						                    </div>
						                </div>
									</div>
									<div class="form-group second_condition" style="display: none;">
										<div style="width: 100%;">
						                	<div style="width: 25%;float:left;margin-right: 20px;">
						                        <label class="label_three">Days (Close Relatives):</label>
						                        <label class="label_four">Days (Singapore Office - Foreigner):</label>
						                    </div>
						                	<div style="width: 15%;float: left;">
						                        <div style="width:100%">
						                        	<input type="number" id="leave_days_second_condition" class="form-control" style="width:50%; display:inline-block;" name="leave_days_second_condition" value="" onkeypress='validate(event)' required />
						                        </div>
						                    </div>
						                </div>
									</div>
									<div class="form-group third_condition" style="display: none;">
										<div style="width: 100%;">
						                	<div style="width: 25%;float:left;margin-right: 20px;">
						                        <label class="label_four">Days (Malaysia Office - Malaysian):</label>
						                    </div>
						                	<div style="width: 15%;float: left;">
						                        <div style="width:100%">
						                        	<input type="number" id="leave_days_third_condition" class="form-control" style="width:50%; display:inline-block;" name="leave_days_third_condition" value="" onkeypress='validate(event)' required />
						                        </div>
						                    </div>
						                </div>
									</div>
									<div class="form-group fourth_condition" style="display: none;">
										<div style="width: 100%;">
						                	<div style="width: 25%;float:left;margin-right: 20px;">
						                        <label class="label_four">Days (Malaysia Office - Foreigner):</label>
						                    </div>
						                	<div style="width: 15%;float: left;">
						                        <div style="width:100%">
						                        	<input type="number" id="leave_days_fourth_condition" class="form-control" style="width:50%; display:inline-block;" name="leave_days_fourth_condition" value="" onkeypress='validate(event)' required />
						                        </div>
						                    </div>
						                </div>
									</div>
									<div class="form-group">
										<div style="width: 100%;">
						                	<div style="width: 25%;float:left;margin-right: 20px;">
						                        <label>Carry Forward on Next Year:</label>
						                    </div>
						                	<div style="width: 15%;float: left;">
						                        <div style="width:100%">
						                        	<?php 
			                                    		echo form_dropdown('choose_carry_forward_id', $choose_carry_forward_list, isset($choose_carry_forward_id_data['choose_carry_forward_id'])?$choose_carry_forward_id_data['choose_carry_forward_id']:'', 'class="form-control choose_carry_forward_id" required');
			                                    	?>
						                        </div>
						                    </div>
						                </div>
									</div>
									<div class="form-group">
						                <div style="width: 100%;">
						                    <div style="width: 25%;float:left;margin-right: 20px;">
						                        <label></label>
						                    </div>
						                    <div style="float:right;margin-bottom:5px;">
						                        <div class="input-group">
						                        	<button class="btn btn_purple" type="submit">Save</button>
						                        </div>
						                    </div>
						                </div>
						            </div>
						            <hr>
						            <table class="table table-bordered table-striped mb-none datatable-setting" style="width:100%">
										<thead>
											<tr style="background-color:white;">
												<th class="text-left" style="width:20%">Leave Name</th>
												<th class="text-left" style="width:15%">Days (First Condition)</th>
												<th class="text-left" style="width:15%">Days (Second Condition)</th>
												<th class="text-left" style="width:15%">Days (Third Condition)</th>
												<th class="text-left" style="width:15%">Days (Fourth Condition)</th>
												<th class="text-left" style="width:30%">Carry Forward on Next Year</th>
												<!-- <th class="text-left"></th> -->
											</tr>
										</thead>
										<tbody>
											<?php 
												foreach($type_of_leave_list as $leave)
									  			{
									  				echo '<tr>';
									  				echo '<td><a href="javascript:void(0)" class="edit_type_of_leave" data-id="'.$leave->id.'" id="edit_type_of_leave">'.$leave->leave_name.'</a></td>';
									  				echo '<td class="text-right">'.$leave->days.'</td>';
									  				echo '<td class="text-right">'.$leave->second_condition.'</td>';
									  				echo '<td class="text-right">'.$leave->third_condition.'</td>';
									  				echo '<td class="text-right">'.$leave->fourth_condition.'</td>';
									  				echo '<td class="text-right">'.$leave->choose_carry_forward_name.'</td>';
									  				// echo '<td style="text-align:center;"><input type="hidden" class="type_of_leave_id" value="'. $leave->id .'" /><button type="button" class="btn btn_purple" onclick=delete_type_of_leave(this)>Delete</button></td>';
									  				echo '</tr>';
									  			}
											?>
										</tbody>
									</table>
								</div>
							</form>
						</div>
<!-- APPROVAL CAP ---------------------------------------------------------------------------------------------------------------------------------------------- -->
						<div id="w2-approval_cap" class="tab-pane">
							<form id="approval_cap_submit" autocomplete="off">
								<input type="hidden" name="approval_cap_id" class="approval_cap_id">
								<div class="input-container">
									<h3>Approval Cap</h3>
									<div class="help-tip" role="tooltip" id="name-tooltip" data-toggle="tooltip" title="Maximum number of staff that can be approved in one working day.">
										<div class="help-tip_content">
										</div>
									</div>
								</div>

								<div class="form-group input-container approvalCap_office_div_div">
									<label class="col-xs-3" for="w2-show_all">Offices: </label>
									<div class="col-sm-3 form-inline approvalCap_office_div" style="width: 28.9%">
										<?php 
                                    		echo form_dropdown('approvalCap_office_list', $office_list, isset($office_list['office_name'])?$office_list['office_name']:'','class="form-control approvalCap_office_list int_select2" required');//jw
                                    	?>
									</div>
								</div>

				                <div class="form-group input-container approvalCap_department_div_div">
									<label class="col-xs-3" for="w2-show_all">Department: </label>
									<div class="col-sm-3 form-inline approvalCap_department_div" style="width: 28.9%">
										<?php 
	                                		echo form_dropdown('approvalCap_department_list', $department_list, isset($department_list_data['department_name'])?$department_list_data['department_name']:'','class="form-control approvalCap_department_list int_select2" required');//jw
	                                	?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-xs-3" for="w2-show_all">Date Range: </label>
									<div class="col-sm-6 form-inline">
										<div class="input-daterange input-group approval_cap_date" data-plugin-datepicker data-date-format="dd/mm/yyyy">
											<span class="input-group-addon">
												<i class="far fa-calendar-alt"></i>
											</span>
											<input type="text" class="form-control approval_cap_date_from" name="from" id="from" value="" placeholder="From" required>
											<span class="input-group-addon">to</span>
											<input type="text" class="form-control approval_cap_date_to" name="to" id="to" value="" placeholder="To" required>
										</div>
									</div>
								</div>
								<div class="form-group input-container">
									<label class="col-xs-3" for="w2-show_all">Approval Cap: </label>
									
									<div class="col-sm-3 form-inline">
										<input name="number_of_employee" type="number" class="form-control number_of_employee" value="0" style="width:70px;" onkeypress='validate(event)' required>
								    	<label style="display:inline-block;">employee(s)</label>
									</div>
								</div>
								<div class="form-group">
					                <div style="width: 100%;">
					                    <div style="width: 25%;float:left;margin-right: 20px;">
					                        <label></label>
					                    </div>
					                    <div style="float:right;margin-bottom:5px;">
					                        <div class="input-group">
					                        	<button class="btn btn_purple" type="submit">Save</button>
					                        	<a class="btn pull-right btn_cancel cancel_Cap" >Clear</a>
					                        </div>
					                    </div>
					                </div>
					            </div>
							</form>
							<hr>
							<div class="form-group">
				                <div style="width: 100%;">
				                    <div style="width: 10%;float:left;">
                                    	<label>Year :</label>
                                    	<div>
                                    		<?php 
                                    			echo form_dropdown('approvalCap_year_filter', $approvalCap_year_filter, isset($staff[0]->holiday_date_year)?$staff[0]->holiday_date_year:'', 'class="form-control int_select2 approvalCap_year_filter"');//jw
                                    		?>
                                    	</div>
                                	</div>
                                	<div style="width: 15%;float:left;padding-left: 10px">
                                    	<label>Offices :</label>
                                    	<div>
                                    		<?php 
                                    			echo form_dropdown('approvalCap_offices_filter', $approvalCap_offices_filter, isset($department_filter_data['id'])?$department_filter_data['department_name']:'','class="form-control int_select2 approvalCap_offices_filter"');//jw
                                    		?>
                                    	</div>
                                	</div>
                                	<div style="width: 15%;float:left;padding-left: 10px">
                                    	<label>Department :</label>
                                    	<div>
                                    		<?php 
                                    			echo form_dropdown('approvalCap_department_filter', $approvalCap_department_filter, isset($department_filter_data['id'])?$department_filter_data['department_name']:'','class="form-control int_select2 approvalCap_department_filter"');//jw
                                    		?>
                                    	</div>
                                	</div>
				                </div>
				            </div>
					        <table class="table table-bordered table-striped mb-none datatable-setting-cap" style="width:100%">
								<thead>
									<tr style="background-color:white;">
										<th class="text-left">From</th>
										<th class="text-left">To</th>
										<th class="text-left">Office</th>
										<th class="text-left">Department</th>
										<th class="text-left">Approval Cap</th>
										<th class="text-left"></th>
									</tr>
								</thead>
								<tbody class="cap_table">
									<!-- <?php 
										//foreach($approval_cap_list as $approval_cap)
							  			{
							  				//echo '<tr>';
							  				//echo '<td><span style="display: none">'.date('Ymd', strtotime($approval_cap->approval_cap_date_from)).'</span><a href="javascript:void(0)" class="edit_approval_cap" data-id="'.$approval_cap->id.'" id="edit_approval_cap">'.date('d F Y', strtotime($approval_cap->approval_cap_date_from)).'</a></td>';

							  				//echo '<td><span style="display: none">'.date('Ymd', strtotime($approval_cap->approval_cap_date_to)).'</span>'.date('d F Y', strtotime($approval_cap->approval_cap_date_to)).'</td>';

							  				//echo '<td>'.$approval_cap->office_name.'</td>';
							  				//echo '<td>'.$approval_cap->department_name.'</td>';
							  				//echo '<td>'.$approval_cap->number_of_employee.'</td>';

							  				//echo '<td style="text-align:center;"><input type="hidden" class="approval_cap_id" value="'. $approval_cap->id .'" /><button type="button" class="btn btn_purple" onclick=delete_approval_cap(this)>Delete</button></td>';
							  				//echo '</tr>';
							  			}
									?> -->
								</tbody>
							</table>
						</div>
<!-- BLOCK LEAVE ----------------------------------------------------------------------------------------------------------------------------------------------- -->
						<div id="w2-block_leave" class="tab-pane">
							<form id="block_leave_submit" autocomplete="off">
								<div class="input-container">
									<h3>Block Leave</h3>
								</div>
								<input type="hidden" name="block_leave_id" class="block_leave_id" value="">
								<div class="form-group">
									<label class="col-xs-3" for="w2-show_all">Offices: </label>
									<div class="col-sm-6 form-inline block_leave_office_div_div">
										<div class="block_leave_office_div" style="width: 56.2%">
	                                        <?php 
	                                        	echo form_dropdown('block_leave_office_list', $office_list, isset($department_list_data['department_name'])?$department_list_data['department_name']:'','class="form-control block_leave_office_list int_select2" required');
	                                        ?>
	                                    </div>
									</div>
								</div>
								<div class="form-group ">
									<label class="col-xs-3" for="w2-show_all">Department: </label>
									<div class="col-sm-6 form-inline block_leave_department_div_div">
										<div class="block_leave_department_div" style="width: 56.2%">
	                                        <?php 
	                                        	echo form_dropdown('block_leave_department_list', $department_list, isset($department_list_data['department_name'])?$department_list_data['department_name']:'','class="form-control block_leave_department_list int_select2" required');//jw
	                                        ?>
	                                    </div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-xs-3" for="w2-show_all">Date Range: </label>
									<div class="col-sm-6 form-inline">
										<div class="input-daterange input-group block_leave_date" data-plugin-datepicker data-date-format="dd/mm/yyyy">
											<span class="input-group-addon">
												<i class="far fa-calendar-alt"></i>
											</span>
											<input type="text" class="form-control block_leave_date_from" name="from" value="" placeholder="From">
											<span class="input-group-addon">to</span>
											<input type="text" class="form-control block_leave_date_to" name="to" value="" placeholder="To">
										</div>
									</div>
								</div>
								<div class="form-group">
					                <div style="width: 100%;">
					                    <div style="width: 25%;float:left;margin-right: 20px;">
					                        <label></label>
					                    </div>
					                    <div style="float:right;margin-bottom:5px;">
					                        <div class="input-group">
					                        	<button class="btn btn_purple" type="submit">Save</button>
					                        	<a class="btn pull-right btn_cancel cancel_block_leave">Clear</a>
					                        </div>
					                    </div>
					                </div>
					            </div>
					            <hr>
					            <div class="form-group">
					                <div style="width: 100%;">
					                    <div style="width: 10%;float:left;">
	                                    	<label>Year :</label>
	                                    	<div>
	                                    		<?php 
	                                    			echo form_dropdown('block_leave_year_filter', $block_leave_year_filter, isset($staff[0]->holiday_date_year)?$staff[0]->holiday_date_year:'', 'class="form-control int_select2 block_leave_year_filter"');//jw
	                                    		?>
	                                    	</div>
	                                	</div>
	                                	<div style="width: 15%;float:left;padding-left: 10px">
	                                    	<label>Offices :</label>
	                                    	<div>
	                                    		<?php 
	                                    			echo form_dropdown('block_leave_offices_filter', $block_leave_offices_filter, isset($department_filter_data['id'])?$department_filter_data['department_name']:'','class="form-control int_select2 block_leave_offices_filter"');//jw
	                                    		?>
	                                    	</div>
	                                	</div>
	                                	<div style="width: 15%;float:left;padding-left: 10px">
	                                    	<label>Department :</label>
	                                    	<div>
	                                    		<?php 
	                                    			echo form_dropdown('block_leave_department_filter', $block_leave_department_filter, isset($department_filter_data['id'])?$department_filter_data['department_name']:'','class="form-control int_select2 block_leave_department_filter"');//jw
	                                    		?>
	                                    	</div>
	                                	</div>
					                </div>
					            </div>
					            <table class="table table-bordered table-striped mb-none datatable-setting-block_leave" style="width:100%">
									<thead>
										<tr style="background-color:white;">
											<th class="text-left">From</th>
											<th class="text-left">To</th>
											<th class="text-left">Office</th>
											<th class="text-left">Department</th>
											<th class="text-left"></th>
										</tr>
									</thead>
									<tbody class="block_leave_table">
										<!-- <?php 
											//foreach($block_leave_list as $block_leave)
								  			{
								  				//echo '<tr>';
								  				//echo '<td><span style="display: none">'.date('Ymd', strtotime($block_leave->block_leave_date_from)).'</span><a href="javascript:void(0)" class="edit_block_leave" data-id="'.$block_leave->id.'" id="edit_block_leave">'.date('d F Y', strtotime($block_leave->block_leave_date_from)).'</a></td>';

								  				//echo '<td><span style="display: none">'.date('Ymd', strtotime($block_leave->block_leave_date_to)).'</span>'.date('d F Y', strtotime($block_leave->block_leave_date_to)).'</td>';

								  				//echo '<td>'.$block_leave->department_name.'</td>';

								  				//echo '<td style="text-align:center;"><input type="hidden" class="block_leave_id" value="'. $block_leave->id .'" /><button type="button" class="btn btn_purple" onclick=delete_block_leave(this)>Delete</button></td>';
								  				//echo '</tr>';
								  			}
										?> -->
									</tbody>
								</table>
					        </form>
						</div>
<!-- LEAVE CYCLE ----------------------------------------------------------------------------------------------------------------------------------------------- -->
						<div id="w2-leave_cycle" class="tab-pane">
							<form id="leave_cycle_submit">
								<input type="hidden" name="leave_cycle_id" value="<?=isset($leave_cycle_list[0]->id)?$leave_cycle_list[0]->id:'' ?>">
								<div class="input-container">
									<h3>Leave Cycle</h3>
								</div>
								<div class="form-group">
									<label class="col-xs-3" for="w2-show_all">Date Range: </label>
									<div class="col-sm-6 form-inline">
										<div class="input-daterange input-group leave_cycle_daterange" data-plugin-datepicker data-date-format="dd/mm/yyyy">
											<span class="input-group-addon">
												<i class="far fa-calendar-alt"></i>
											</span>
											<input type="text" class="form-control" name="from" value="<?=isset($leave_cycle_list[0]->leave_cycle_date_from)?date('d F', strtotime('2019-'.$leave_cycle_list[0]->leave_cycle_date_from)):'' ?>" placeholder="From">
											<span class="input-group-addon">to</span>
											<input type="text" class="form-control" name="to" value="<?=isset($leave_cycle_list[0]->leave_cycle_date_to)?date('d F', strtotime('2019-'.$leave_cycle_list[0]->leave_cycle_date_to)):'' ?>" placeholder="To">
										</div>
									</div>
								</div>
								<div class="form-group">
					                <div style="width: 100%;">
					                    <div style="width: 25%;float:left;margin-right: 20px;">
					                        <label></label>
					                    </div>
					                    <div style="float:right;margin-bottom:5px;">
					                        <div class="input-group">
					                        	<button class="btn btn_purple" type="submit">Save</button>
					                        </div>
					                    </div>
					                </div>
					            </div>
					        </form>
						</div>
<!-- BLOCK HOLIDAY --------------------------------------------------------------------------------------------------------------------------------------------- -->
						<div id="w2-block_holiday" class="tab-pane">
							<div class="input-container">
								<h3>Public Holiday</h3>
							</div>
							<form id="block_holiday_submit">
								<input type="hidden" name="block_holiday_id" class="block_holiday_id" value="">
								<div class="col-md-12">
									<div class="form-group">
						            	<div class="office_div_div" style="width: 100%;">
						                	<div style="width: 25%;float:left;margin-right: 20px;">
						                        <label>Offices:</label>
						                    </div>
						                	<div class="office_div" style="width: 30%;float: left;">
						                        <?php 
	                                        		echo form_dropdown('office_list', $office_list, isset($office_list['office_name'])?$office_list['office_name']:'','class="form-control office_list int_select2" required');//jw
	                                        	?>
						                    </div>
						                </div>
						            </div>
									<div class="form-group">
						            	<div class="department_div_div" style="width: 100%;">
						                	<div style="width: 25%;float:left;margin-right: 20px;">
						                        <label>Department:</label>
						                    </div>
						                	<div class="department_div" style="width: 30%;float: left;">
						                        <?php 
	                                        		echo form_dropdown('department_list', $department_list, isset($department_list_data['department_name'])?$department_list_data['department_name']:'','class="form-control department_list2 int_select2" required');//jw
	                                        	?>
						                    </div>
						                </div>
						            </div>
									<div class="form-group">
						            	<div style="width: 100%;">
						                	<div style="width: 25%;float:left;margin-right: 20px;">
						                        <label>Date:</label>
						                    </div>
						                	<div style="width: 30%;float: left;">
						                        <div class="input-group date datepicker" style="padding: 0px;">
						                        	<div class="input-group-addon">
												        <span class="far fa-calendar-alt"></span>
												    </div>
												    <input type="text" class="form-control block_holiday" id="block_holiday" name="block_holiday" required>
												</div>
						                    </div>
						                </div>
						            </div>
									<div class="form-group">
						                <div style="width: 100%;">
						                    <div style="width: 25%;float:left;margin-right: 20px;">
						                        <label>Holiday Description: </label>
						                    </div>
						                    <div style="width: 30%;float: left;">
						                        <div style="width:100%">
						                        	<input type="text" class="form-control holiday_description" name="holiday_description">
						                        </div>
						                    </div>
						                </div>
						            </div>
									<div class="form-group">
						                <div style="width: 100%;">
						                    <div style="width: 25%;float:left;margin-right: 20px;">
						                        <label></label>
						                    </div>
						                    <div style="float:right;margin-bottom:5px;">
						                        <div class="input-group">
						                        	<button class="btn btn_purple" type="submit">Save</button>
						                        	<a class="btn pull-right btn_cancel cancel_holiday" >Clear</a>
						                        </div>
						                    </div>
						                </div>
						            </div>
						            <hr>
						            <div class="form-group">
						                <div style="width: 100%;">
						                    <div style="width: 10%;float:left;">
	                                        	<label>Year :</label>
	                                        	<div>
	                                        		<?php 
	                                        			echo form_dropdown('year_list', $year_list, isset($staff[0]->holiday_date_year)?$staff[0]->holiday_date_year:'', 'class="form-control int_select2 year_list"');//jw
	                                        		?>
	                                        	</div>
	                                    	</div>
	                                    	<div style="width: 15%;float:left;padding-left: 10px">
	                                        	<label>Offices :</label>
	                                        	<div>
	                                        		<?php 
	                                        			echo form_dropdown('offices_filter', $offices_filter, isset($department_filter_data['id'])?$department_filter_data['department_name']:'','class="form-control int_select2 offices_filter"');//jw
	                                        		?>
	                                        	</div>
	                                    	</div>
	                                    	<div style="width: 15%;float:left;padding-left: 10px">
	                                        	<label>Department :</label>
	                                        	<div>
	                                        		<?php 
	                                        			echo form_dropdown('department_filter', $department_filter, isset($department_filter_data['id'])?$department_filter_data['department_name']:'','class="form-control int_select2 department_filter"');//jw
	                                        		?>
	                                        	</div>
	                                    	</div>
						                </div>
						            </div>
						            <div>
							            <table class="table table-bordered table-striped mb-none datatable-setting-holiday" >
											<thead>
												<tr style="background-color:white;">
													<th class="text-left">Date</th>
													<th class="text-left">Holiday Description</th>
													<th class="text-left">Office</th>
													<th class="text-left">Department</th>
													<th class="text-left"></th>
												</tr>
											</thead>
											<tbody class="holiday_table">
												<!-- <?php 
													// foreach($holiday_list as $holiday)
										  			{
										  				//echo '<tr class="holiday_data">';
										  				//echo '<td><span style="display: none">'.date('Ymd', strtotime($holiday->holiday_date)).'</span><a href="javascript:void(0)" class="edit_block_holiday" data-id="'.$holiday->id.'" id="edit_block_holiday">'.date('d F Y', strtotime($holiday->holiday_date)).'</a></td>';
										  				//echo '<td>'.$holiday->description.'</td>';
										  				//echo '<td>'.$holiday->office_name.'</td>';
										  				//echo '<td>'.$holiday->department_name.'</td>';
										  				//echo '<td style="text-align:center;"><input type="hidden" class="holiday_id" value="'. $holiday->id .'" /><button type="button" class="btn btn_purple" onclick=delete_holiday(this)>Delete</button></td>';
										  				//echo '</tr>';
										  			}
												?> -->
											</tbody>
										</table>
									</div>
								</div>
							</form>
						</div>
<!-- CARRY FORWARD --------------------------------------------------------------------------------------------------------------------------------------------- -->
						<div id="w2-carry_forward_period" class="tab-pane">
							<form id="carry_forward_period_submit">
								<div class="input-container">
									<h3>Carry Forward Period</h3>
								</div>
								<input type="hidden" name="carry_forward_period_id" value="<?=isset($carry_forward_period_list[0]->id)?$carry_forward_period_list[0]->id:'' ?>">
								<div class="col-md-12">
									<div class="form-group">
						            	<div style="width: 100%;">
						                	<div style="width: 25%;float:left;margin-right: 20px;">
						                        <label>Select Date:</label>
						                    </div>
						                	<div style="width: 30%;float: left;">
						                        <div class="input-group date carry_forward_period_datepicker" style="padding: 0px;">
						                        	<div class="input-group-addon">
												        <span class="far fa-calendar-alt"></span>
												    </div>
												    <input type="text" class="form-control" id="carry_forward_period" name="carry_forward_period_date" value="<?=isset($carry_forward_period_list[0]->carry_forward_period_date)?date('d F', strtotime('2019-'.$carry_forward_period_list[0]->carry_forward_period_date)):'' ?>" required>
												</div>
						                    </div>
						                </div>
						            </div>
						            <div class="form-group">
						                <div style="width: 100%;">
						                    <div style="width: 25%;float:left;margin-right: 20px;">
						                        <label></label>
						                    </div>
						                    <div style="float:right;margin-bottom:5px;">
						                        <div class="input-group">
						                        	<button class="btn btn_purple" type="submit">Save</button>
						                        </div>
						                    </div>
						                </div>
						            </div>
						        </div>
						    </form>
						</div>
<!-- PARTNER LIST ---------------------------------------------------------------------------------------------------------------------------------------------- -->
						<div id="w2-partner_list" class="tab-pane">
							<form id="partner_submit">
								<div class="input-container">
									<h3>Partner</h3>
								</div>
								<div class="col-md-12">
									<!-- <div class="form-group">
						            	<div style="width: 100%;">
						                	<div style="width: 25%;float:left;margin-right: 20px;">
						                        <label>Partner:</label>
						                    </div>
						                	<div style="width: 30%;float: left;">
						                		<input type="hidden" id="partner_id" name="partner_id" class="form-control partner_id" value="" />
						                        <input type="text" id="partner" name="partner" class="form-control partner" required/>
						                    </div>
						                    <div style="width: 30%;float: left;padding-left:10px;">
						                        <button type="submit" class="btn btn_purple">Save</button>
						                    </div>
						                </div>
						                <div style="width: 100%;">
						                	<div style="width: 25%;float:left;margin-right: 20px;">
						                        <label>Email:</label>
						                    </div>
						                	<div style="width: 30%;float: left;">
						                		<?php 
	                                        			echo form_dropdown('partner_email', $partner_email, '','class="form-control int_select2 partner_email"');
	                                        		?>
						                    </div>
						                </div>
						            </div> -->

						            <div class="form-group">
						                <div style="width: 100%;">
						                	<div style="width: 25%;float:left;margin-right: 20px;">
						                        <label>Partner:</label>
						                    </div>
						                	<div style="width: 30%;float: left;">
						                		<input type="hidden" id="partner_id" name="partner_id" class="form-control partner_id" value="" />
						                        <input type="text" id="partner" name="partner" class="form-control partner" required/>
						                    </div>
						                </div>
						            </div>
						             <div class="form-group">
						                <div style="width: 100%;">
						                	<div style="width: 25%;float:left;margin-right: 20px;">
						                        <label>Email:</label>
						                    </div>
						                	<div style="width: 30%;float: left;">
						                		<?php 
	                                        			echo form_dropdown('partner_email_id', $partner_email, '','class="form-control int_select2 partner_email_id"');
	                                        		?>
						                    </div>
						                </div>
						            </div>
									<div class="form-group">
						                <div style="width: 100%;">
						                    <div style="width: 25%;float:left;margin-right: 20px;">
						                        <label></label>
						                    </div>
						                    <div style="float:right;margin-bottom:5px;">
						                        <div class="input-group">
						                        	<button type="submit" class="btn btn_purple">Save</button>
						                        </div>
						                    </div>
						                </div>
						            </div>


						            <hr>
						            <div class="form-group">
						                <div style="width: 100%;">
						                    <table class="table table-bordered table-striped mb-none datatable-setting-partner" style="width:100%">
												<thead>
													<tr style="background-color:white;">
														<th class="text-left">ID</th>
														<th class="text-left">Partner Name</th>
														<!-- <th class="text-left"></th> -->
													</tr>
												</thead>
												<tbody>
													<?php 
														foreach($partner_list as $partner)
											  			{
											  				echo '<tr>';
											  				echo '<td>'.$partner->id.'</td>';
											  				echo '<td><a href="javascript:void(0)" class="edit_partner" data-id="'.$partner->id.'" id="edit_partner">'.$partner->partner_name.'</a></td>';
											  				// echo '<td style="text-align:center;"><input type="hidden" class="partner_id" value="'. $partner->id .'" /><button type="button" class="btn btn_purple" onclick=delete_partner(this)>Delete</button></td>';
											  				echo '</tr>';
											  			}
													?>
												</tbody>
											</table>
						                </div>
						            </div>
						        </div>
					        </form>
						</div>
<!-- EVENT LIST ------------------------------------------------------------------------------------------------------------------------------------------------ -->
						<div id="w2-event" class="tab-pane">
							<form id="event_submit">
								<div class="input-container">
									<h3>Event</h3>
								</div>
								<div class="col-md-12">
									<div class="form-group">
						            	<div style="width: 100%;">
						                	<div style="width: 25%;float:left;margin-right: 20px;">
						                        <label>Event:</label>
						                    </div>
						                	<div style="width: 30%;float: left;">
						                		<input type="hidden" id="event_id" name="event_id" class="form-control event_id" value="" />
						                        <input type="text" id="event" name="event" class="form-control event" required/>
						                    </div>
						                    <div style="width: 30%;float: left;padding-left:10px;">
						                        <button type="submit" class="btn btn_purple">Save</button>
						                    </div>
						                </div>
						            </div>
						            <hr>
						            <div class="form-group">
						                <div style="width: 100%;">
						                    <table class="table table-bordered table-striped mb-none datatable-setting-event" style="width:100%">
												<thead>
													<tr style="background-color:white;">
														<th class="text-left">ID</th>
														<th class="text-left">Event Name</th>
														<th class="text-left"></th>
													</tr>
												</thead>
												<tbody>
													<?php 
														foreach($event_list as $events)
											  			{
											  				echo '<tr>';
											  				echo '<td>'.$events->id.'</td>';
											  				echo '<td><a href="javascript:void(0)" class="edit_event" data-id="'.$events->id.'" id="edit_event">'.$events->event.'</a></td>';
											  				echo '<td style="text-align:center;"><input type="hidden" class="event_id" value="'. $events->id .'" /><button type="button" class="btn btn_purple" onclick=delete_event(this)>Delete</button></td>';
											  				echo '</tr>';
											  			}
													?>
												</tbody>
											</table>
						                </div>
						            </div>
						        </div>
					        </form>
						</div>
<!-- JOB LIST -------------------------------------------------------------------------------------------------------------------------------------------------- -->
						<div id="w2-type_of_jobs" class="tab-pane">
							<div class="input-container">
								<h3>Job</h3>
							</div>
							<form id="job_submit">
								<div class="col-md-12">
									<div class="form-group">
						            	<div style="width: 100%;">
						                	<div style="width: 25%;float:left;margin-right: 20px;">
						                        <label>Type of Jobs:</label>
						                    </div>
						                	<div style="width: 30%;float: left;">
						                		<input type="hidden" id="job_id" name="job_id" class="form-control job_id" value="" />
						                        <input type="text" id="job" name="job" class="form-control job" required/>
						                    </div>
						                    <div style="width: 30%;float: left;padding-left:10px;">
						                        <button type="submit" class="btn btn_purple">Save</button>
						                    </div>
						                </div>
						            </div>
						            <hr>
						            <div class="form-group">
						                <div style="width: 100%;">
						                    <table class="table table-bordered table-striped mb-none datatable-setting-job" style="width:100%">
												<thead>
													<tr style="background-color:white;">
														<th class="text-left">ID</th>
														<th class="text-left">Type Of Job</th>
														<!-- <th class="text-left"></th> -->
													</tr>
												</thead>
												<tbody>
													<?php 
														foreach($job_list as $job)
											  			{
											  				echo '<tr>';
											  				echo '<td>'.$job->id.'</td>';
											  				echo '<td><a href="javascript:void(0)" class="edit_job" data-id="'.$job->id.'" id="edit_job">'.$job->type_of_job.'</a></td>';
											  				// echo '<td style="text-align:center;"><input type="hidden" class="job_id" value="'. $job->id .'" /><button type="button" class="btn btn_purple" onclick=delete_job(this)>Delete</button></td>';
											  				echo '</tr>';
											  			}
													?>
												</tbody>
											</table>
						                </div>
						            </div>
						        </div>
					        </form>
						</div>
<!-- OFFICES --------------------------------------------------------------------------------------------------------------------------------------------------- -->
						<div id="w2-offices" class="tab-pane">
							<form id="offices_submit" autocomplete="off">
								<input type="hidden" class="form-control office_id">
								<div class="input-container">
									<h3>Offices</h3>
								</div>
								<div class="form-group input-container">
									<label class="col-xs-3" for="w2-show_all">Office Name: </label>
									<div class="col-sm-3 form-inline" style="width: 30%">
										<input style="width: 100%" type="text" class="form-control offices_office_name" required>
									</div>
								</div>
								<div class="form-group input-container">
									<label class="col-xs-3" for="w2-show_all">Country: </label>
									<div class="col-sm-3 form-inline" style="width: 30%">
										<?php 
                                			echo form_dropdown('offices_country', $country,'','class="form-control int_select2 offices_country" required');//jw
                                		?>
									</div>
								</div>
								<div class="form-group">
					                <div style="width: 100%;">
					                    <div style="float:right;margin-bottom:5px;">
					                        <div class="input-group">
					                        	<button class="btn btn_purple" type="submit">Save</button>
					                        	<a class="btn pull-right btn_cancel office_cancel">Clear</a>
					                        </div>
					                    </div>
					                </div>
					            </div>
								<hr>
								<table class="table table-bordered table-striped mb-none datatable-setting-offices" style="width:100%">
									<thead>
										<tr style="background-color:white;">
											<th class="text-left">Office Name</th>
											<th class="text-left">Country</th>
											<th class="text-left"></th>
										</tr>
									</thead>
									<tbody>
										<?php 
											foreach($offices as $office)
								  			{
								  				if($office->id != '1')
								  				{
								  					echo '<tr>';
									  				echo '<td><a href="javascript:void(0)" class="edit_office" data-id="'.$office->id.'">'.$office->office_name.'</a></td>';
									  				echo '<td>'.$office->office_country.'</td>';
									  				echo '<td style="text-align:center;"><input type="hidden" class="office_id" value="'. $office->id .'" /><button type="button" class="btn btn_purple" onclick=delete_office(this)>Delete</button></td>';
									  				echo '</tr>';
								  				}
								  			}
										?>
									</tbody>
								</table>
							</form>
						</div>
<!-- DEPARTMENT ------------------------------------------------------------------------------------------------------------------------------------------------ -->
						<div id="w2-department" class="tab-pane">
							<form id="department_submit" autocomplete="off">
								<div class="input-container">
									<h3>Department</h3>
								</div>
								<input type="hidden" class="form-control department_id">
								<div class="form-group input-container">
									<label class="col-xs-3" for="w2-show_all">Department Name: </label>
									<div class="col-sm-3 form-inline" style="width: 30%">
										<input style="width: 100%" type="text" class="form-control dpt_department_name" required>
									</div>
								</div>
								<div class="form-group">
					                <div style="width: 100%;">
					                    <div style="float:right;margin-bottom:5px;">
					                        <div class="input-group">
					                        	<button class="btn btn_purple" type="submit">Save</button>
					                        	<a class="btn pull-right btn_cancel department_cancel">Clear</a>
					                        </div>
					                    </div>
					                </div>
					            </div>
								<hr>
								<table class="table table-bordered table-striped mb-none datatable-setting-department" style="width:100%">
									<thead>
										<tr style="background-color:white;">
											<th class="text-left">ID</th>
											<th class="text-left">Department Name</th>
											<th class="text-left"></th>
										</tr>
									</thead>
									<tbody>
										<?php 
											foreach($departments as $department)
								  			{
								  				if($department->id != '1')
								  				{
								  					echo '<tr>';
								  					echo '<td>'.$department->id.'</td>';
									  				echo '<td><a href="javascript:void(0)" class="edit_department" data-id="'.$department->id.'">'.$department->department_name.'</a></td>';
									  				echo '<td style="text-align:center;"><input type="hidden" class="department_id" value="'. $department->id .'" /><button type="button" class="btn btn_purple" onclick=delete_department(this)>Delete</button></td>';
									  				echo '</tr>';
								  				}
								  			}
										?>
									</tbody>
								</table>
							</form>
						</div>
<!-- CHARGE-OUT RATE ------------------------------------------------------------------------------------------------------------------------------------------- -->
						<div id="w2-charge_out_rate" class="tab-pane">
							<form id="charge_out_rate_submit" autocomplete="off">
								<div class="input-container">
									<h3>Charge-out Rate</h3>
								</div>
								<input type="hidden" class="form-control charge_out_rate_id">
								<input type="hidden" class="form-control designation">
								<div class="form-group input-container charge_out_rate_office_div_div">
									<label class="col-xs-3" for="w2-show_all">Offices: </label>
									<div class="col-sm-3 form-inline charge_out_rate_office_div" style="width: 28.9%">
										<?php 
                                    		echo form_dropdown('charge_out_rate_office_list', $office_list, isset($office_list['office_name'])?$office_list['office_name']:'','class="form-control charge_out_rate_office_list int_select2" style="width: 100% !important" required');//jw
                                    	?>
									</div>
								</div>
				                <div class="form-group input-container charge_out_rate_department_div_div">
									<label class="col-xs-3" for="w2-show_all">Department: </label>
									<div class="col-sm-3 form-inline charge_out_rate_department_div" style="width: 28.9%">
										<?php 
	                                		echo form_dropdown('charge_out_rate_department_list', $department_list, isset($department_list_data['department_name'])?$department_list_data['department_name']:'','class="form-control charge_out_rate_department_list int_select2" style="width: 100% !important" required');//jw
	                                	?>
									</div>
								</div>
								<div class="form-group input-container charge_out_rate_designation_div_div">
									<label class="col-xs-3" for="w2-show_all">Designation: </label>
									<div class="col-sm-3 form-inline charge_out_rate_designation_div" style="width: 28.9%">
										<select class="form-control charge_out_rate_designation int_select2" style="width: 100% !important" required>
											<option value="" selected="selected">Please Select Designation</option>
										</select>
									</div>
								</div>
								<div class="form-group input-container">
									<label class="col-xs-3" for="w2-show_all">Rate: </label>
									<div class="col-sm-3 form-inline" style="width: 28.9%">
										<input type="number" min='0' class="form-control charge_out_rate" value="0" style="width:70px;" required>
									</div>
								</div>

								<div class="form-group">
					                <div style="width: 100%;">
					                    <div style="float:right;margin-bottom:5px;">
					                        <div class="input-group">
					                        	<button class="btn btn_purple" type="submit">Save</button>
					                        	<a class="btn pull-right btn_cancel charge_out_rate_cancel">Clear</a>
					                        </div>
					                    </div>
					                </div>
					            </div>
								<hr>
								<table class="table table-bordered table-striped mb-none datatable-setting-charge_out_rate" style="width:100%">
									<thead>
										<tr style="background-color:white;">
											<th class="text-left">ID</th>
											<th class="text-left">Designation</th>
											<th class="text-left">Office</th>
											<th class="text-left">Department</th>
											<th class="text-left">Rate</th>
											<th class="text-left"></th>
										</tr>
									</thead>
									<tbody>
										<?php 
											foreach($charge_out_rate as $result)
								  			{
								  				echo '<tr>';
							  					echo '<td>'.$result->id.'</td>';
								  				echo '<td><a href="javascript:void(0)" class="edit_charge_out_rate" data-id="'.$result->id.'">'.$result->designation.'</a></td>';
								  				echo '<td>'.$result->office_name.'</td>';
								  				echo '<td>'.$result->department_name .'</td>';
								  				echo '<td>'.$result->rate.'</td>';
								  				echo '<td style="text-align:center;"><input type="hidden" class="charge_out_rate_id" value="'. $result->id .'" /><button type="button" class="btn btn_purple" onclick=delete_charge_out_rate(this)>Delete</button></td>';
								  				echo '</tr>';
								  			}
										?>
									</tbody>
								</table>
							</form>
						</div>
<!-- TEAM SHIFT ------------------------------------------------------------------------------------------------------------------------------------------- -->
						<div id="w2-team_shifts" class="tab-pane">
							<table class="table table-bordered table-striped mb-none datatable-setting-team_shifts" style="width:100%">
								<div class="form-group">
					                <div style="width: 100%;">
					                    <div style="width: 10%;float:left;">
                                        	<label>Team :</label>
                                        	<div>
                                        		<select class="int_select2" id="team_id">
                                        			<option value="A">ALL</option>
												  	<option value="0">Not In Team</option>
												  	<option value="1">Team 1</option>
												  	<option value="2">Team 2</option>
												</select>
                                        	</div>
                                    	</div>
					                </div>
					            </div>
								<thead>
									<tr style="background-color:white;">
										<th class="text-left">Employee Name</th>
										<th class="text-center">Team</th>
									</tr>
								</thead>
								<tbody class="team_table" >
									<?php 
										foreach($team as $result)
							  			{
							  				echo '<tr class="team_data">';
						  					echo '<td>'.$result->name.'</td>';
						  					// echo '<td>'.$result->team_shift.'</td>';
						  					if($result->team_shift == 0)
						  					{
						  						echo '<td class="text-center"><input type="hidden" class="team_b4_change" value="'.$result->team_shift.'"><select data-id="'.$result->id.'" class="selected_team" onchange=change_team(this)><option value="0" selected>Not In Team</option><option value="1">Team 1</option><option value="2">Team 2</option></select></td>';
						  					}
						  					else if($result->team_shift == 1)
						  					{
						  						echo '<td class="text-center"><input type="hidden" class="team_b4_change" value="'.$result->team_shift.'"><select data-id="'.$result->id.'" class="selected_team" onchange=change_team(this)><option value="0">Not In Team</option><option value="1" selected>Team 1</option><option value="2">Team 2</option></select></td>';
						  					}
						  					else if($result->team_shift == 2)
						  					{
						  						echo '<td class="text-center"><input type="hidden" class="team_b4_change" value="'.$result->team_shift.'"><select data-id="'.$result->id.'" class="selected_team" onchange=change_team(this)><option value="0">Not In Team</option><option value="1">Team 1</option><option value="2" selected>Team 2</option></select></td>';
						  					}

							  				echo '</tr>';
							  			}
									?>
								</tbody>
							</table>
						</div>
<!-- INSTITUTION ------------------------------------------------------------------------------------------------------------------------------------------------- -->
						<div id="w2-institution" class="tab-pane">
							<div class="input-container">
								<h3>Institution</h3>
							</div>
							<form id="institution_submit">
								<div class="col-md-12">
									<div class="form-group">
						            	<div style="width: 100%;">
						                	<div style="width: 25%;float:left;margin-right: 20px;">
						                        <label>Institution:</label>
						                    </div>
						                	<div style="width: 30%;float: left;">
						                		<input type="hidden" id="institution_id" name="institution_id" class="form-control institution_id" value="" />
						                        <input type="text" id="institution" name="institution" class="form-control institution" required/>
						                    </div>
						                    <div style="width: 30%;float: left;padding-left:10px;">
						                        <button type="submit" class="btn btn_purple">Save</button>
						                    </div>
						                </div>
						            </div>
						            <hr>
						            <div class="form-group">
						                <div style="width: 100%;">
						                    <table class="table table-bordered table-striped mb-none datatable-setting-institution" style="width:100%">
												<thead>
													<tr style="background-color:white;">
														<th class="text-left">ID</th>
														<th class="text-left">Institution</th>
														<th class="text-left"></th>
													</tr>
												</thead>
												<tbody>
													<?php 
														foreach($institution_list as $institution)
											  			{
											  				echo '<tr>';
											  				echo '<td>'.$institution->id.'</td>';
											  				echo '<td><a href="javascript:void(0)" class="edit_institution" data-id="'.$institution->id.'" id="edit_institution">'.$institution->institution_name.'</a></td>';
											  				echo '<td style="text-align:center;"><input type="hidden" class="institution_id" value="'. $institution->id .'" /><button type="button" class="btn btn_purple" onclick=delete_institution(this)>Delete</button></td>';
											  				echo '</tr>';
											  			}
													?>
												</tbody>
											</table>
						                </div>
						            </div>
						        </div>
					        </form>
						</div>
<!-- CPF ------------------------------------------------------------------------------------------------------------------------------------------------- -->
						<div id="w2-cpf" class="tab-pane">
							<div class="input-container">
								<h3>CPF Setting</h3>
							</div>
							<form id="cpf_submit">
								<div class="col-md-12">
									<div class="form-group">
						            	<div style="width: 100%;">
						                	<div style="width: 25%;float:left;margin-right: 20px;">
						                        <label>Select jurisdiction:</label>
						                    </div>
						                	<div style="width: 30%;float: left;">
												<?php
													echo form_dropdown('jurisdiction_id', $jurisdiction_list, 1, 'class="form-control jurisdiction_id" "');
												?>
												<!-- <select class="form-control jurisdiction_id" id="jurisdiction_id" name="jurisdiction_id" style="width: 400px !important" required>
													<option value="" selected="selected">Select Jurisdiction</option>
													
												</select> -->
						                    </div>
						                    <!-- <div style="width: 30%;float: left;padding-left:10px;">
						                        <button type="submit" class="btn btn_purple">Save</button>
						                    </div> -->
						                </div>
						            </div>
						            <hr>
						            <div class="form-group salary_cap">
										<table class="salary_cap_group" style="width:90%;table-layout: fixed;" id="salary_cap_tbl">
											<thead style="width:100%;">
												<tr>
													<th style="height:35px;width:22%;" valign=middle>Salary cap</th>
													<th style="height:35px;width:5%;" valign=middle></th>
													<th style="height:35px;width:22%;" valign=middle></th>
													<th style="height:35px;width:17%;" valign=middle></th>
													<th style="height:35px;width:17%;text-align: center;" valign=middle>Monthly</th>
													<th style="height:35px;width:17%;text-align: center;" valign=middle>Annual</th>
												</tr>
											</thead>

											<tbody id="body_salary_cap">
												
											</tbody>
								
										</table>
						            </div>
									<hr>
						            <div class="form-group age_group_period">
										<table class="age_group_period" style="width:60%;table-layout: fixed;" id="age_group_period_tbl">
											<thead style="width: 100%;">
												<tr>
													<th style="height:35px;width:33%;" valign=middle>Age group</th>
													<th style="height:35px;width:7.5%;" valign=middle></th>
													<th style="height:35px;width:33%;" valign=middle></th>
													<th style="height:35px;width:26.5%;" valign=middle></th>
												</tr>
											</thead>

											<tbody id="body_age_group_period">
												
											</tbody>
								
										</table>
						            </div>
									<hr>
						            <div class="form-group nationality">
										<table class="nationality_period" style="width:60%;table-layout: fixed;" id="nationality_period_tbl">
											<thead style="width: 100%;">
												<tr>
													<th style="height:35px;width:33%;" valign=middle>Nationality</th>
													<th style="height:35px;width:7.5%;" valign=middle></th>
													<th style="height:35px;width:33%;" valign=middle></th>
													<th style="height:35px;width:26.5%;" valign=middle></th>
												</tr>
											</thead>

											<tbody id="body_nationality_period">
												
											</tbody>
								
										</table>
						            </div>
						        </div>
					        </form>
						</div>
<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------------- -->
					</div>
				</div>
			</div>
		</div>
		
<!--CLONES----------------------------------------------------------------------------------------------------------------------------------------------------------- -->

		<table id="clone_salary_cap_model" style="display: none;" >
			<tr class="spaceUnder" method="post" name="form" id="form" num="">
				<td>
					<input type="hidden" class="form-control general" name="salary_cap_id[]" value="">
					<div class="input-group date datepicker_cpf">
						<div class="input-group-addon">
							<span class="far fa-calendar-alt"></span>
						</div>
						<input type="text" class="form-control general" name="cap_start_date[]" data-date-format="d F Y" value="<?=isset($salary_cap[0]->cap_start_date)?date("d F Y",strtotime($salary_cap[0]->cap_start_date)):''?>">
					</div>
				</td>
				<td>
					<div style="width: 100%;text-align: center;">
						to
					</div>
				</td>
				<td>
					<div class="input-group date datepicker_cpf">
						<div class="input-group-addon">
							<span class="far fa-calendar-alt"></span>
						</div>
						<input type="text" class="form-control general" name="cap_end_date[]" data-date-format="d F Y" value="<?=isset($salary_cap[0]->cap_end_date)?date("d F Y",strtotime($salary_cap[0]->cap_end_date)):''?>" >
					</div>
				</td>
				<td>
					<div style="width: 100%;text-align: center;">
						<div style="width: 90%;display:inline-block !important;">
						<?php
							echo form_dropdown('cap_currency[]', $currency_list, isset($salary_cap[0]->currency)?$salary_cap[0]->currency: '', 'class="form-control currency-select general"');
						?>
						</div>
					</div>
				</td>
				<td>
					<div style="width: 100%;float:left;margin-bottom:5px;text-align: center;">
						<div>
							<input type='number' class="form-control general" name="monthly_cap_value[]" value="" style="width: 90%; display:inline-block !important;" /> 
							
						</div>
					</div>
				</td>

				<td>
					<div style="width: 100%;float:left;margin-bottom:5px;text-align: center;">
						<div>
							<input type='number' class="form-control general" name="annual_cap_value[]" value="" style="width: 90%; display:inline-block !important;" /> 
							
						</div>
					</div>
				</td>

				<td>
					<div style="width: 100%;margin-left:12px;">
						<div style="width: 90%;display:inline-block !important;">
							<button type="button" class="btn btn_purple save_salary">Save</button>
						</div>
					</div>
				</td>
				
			</tr>
		</table>

		<table id="clone_age_group_period_model" style="display: none;" >
			<tr class="spaceUnder" method="post" name="form" id="form" num="">
				<input type="hidden" class="form-control general" name="age_group_period_id[]" value="">

				<td >
					<div class="input-group date datepicker_cpf">
						<div class="input-group-addon">
							<span class="far fa-calendar-alt"></span>
						</div>
						<input type="text" class="form-control general" name="period_start_date[]" data-date-format="d F Y" value="<?=isset($age_period[0]->period_start_date)?date("d F Y",strtotime($age_period[0]->period_start_date)):''?>">
					</div>
				</td>
				<td>
					<div style="width: 100%;text-align: center;">
						to
					</div>
				</td>
				<td>
					<div class="input-group date datepicker_cpf">
						<div class="input-group-addon">
							<span class="far fa-calendar-alt"></span>
						</div>
						<input type="text" class="form-control general" name="period_end_date[]" data-date-format="d F Y" value="<?=isset($age_period[0]->period_end_date)?date("d F Y",strtotime($age_period[0]->period_end_date)):''?>" >
					</div>
				</td>
				<!-- <td>
					<div style="width: 100%;margin-left:12px;">
						<div style="width: 90%;display:inline-block !important;">
							<button type="button" class="btn btn_purple save_age_group_period">Save</button>
						</div>
					</div>
				</td> -->
				<td>
					<div style="width: 100%;margin-left:12px;">
						<div style="width: 100%;display:inline-block !important;">
							<button type="button" class="btn btn_purple save_age_group_period">Save</button>
							<button type="button" class="btn btn_purple edit_age_group_period">View / Edit</button>
						</div>
					</div>
				</td>
			</tr>
		</table>

		<table id="clone_nationality_period_model" style="display: none;" >
			<tr class="spaceUnder" method="post" name="form" id="form" num="">
				<input type="hidden" class="form-control general" name="nationality_period_id[]" value="">

				<td >
					<div class="input-group date datepicker_cpf">
						<div class="input-group-addon">
							<span class="far fa-calendar-alt"></span>
						</div>
						<input type="text" class="form-control general" name="period_start_date[]" data-date-format="d F Y" value="<?=isset($nationality_period[0]->period_start_date)?date("d F Y",strtotime($nationality_period[0]->period_start_date)):''?>">
					</div>
				</td>
				<td>
					<div style="width: 100%;text-align: center;">
						to
					</div>
				</td>
				<td>
					<div class="input-group date datepicker_cpf">
						<div class="input-group-addon">
							<span class="far fa-calendar-alt"></span>
						</div>
						<input type="text" class="form-control general" name="period_end_date[]" data-date-format="d F Y" value="<?=isset($nationality_period[0]->period_end_date)?date("d F Y",strtotime($nationality_period[0]->period_end_date)):''?>" >
					</div>
				</td>
				<td>
					<div style="width: 100%;margin-left:12px;">
						<div style="width: 90%;display:inline-block !important;">
							<button type="button" class="btn btn_purple save_nationality_period">Save</button>
							<button type="button" class="btn btn_purple edit_nationality_period">View / Edit</button>
						</div>
					</div>
				</td>
				
			</tr>
		</table>

<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------------- -->
	
	</section>
</section>

<div id="age_group_modal" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;" data-backdrop="static">
	<div class="modal-dialog" style="width: 1000px !important;">
		<div class="modal-content">
			<header class="panel-heading">
				<h2 class="panel-title"><strong>Age Group</strong></h2>
			</header>
			<div class="panel-body">
				<form method="post" id="age_group_form">
					<input type="hidden" class="form-control general" name="age_group_period_id" value="">
					<table class="" style="width:100%;table-layout: fixed;border-right:none;border-bottom:none;" id="age_group_tbl">
						<thead style="width:100%;">
							<tr>
								<th style="height:35px;width:8%;" valign=middle></th>
								<th style="height:35px;width:20%;" valign=middle></th>
								<th style="height:35px;width:20%;" valign=middle></th>
								<th style="height:35px;width:20%;" valign=middle>Employer</th>
								<th style="height:35px;width:20%;" valign=middle>Employee</th>
								<th style="height:35px;width:12%;" valign=middle>
									<!-- <a class="create_st_arrangement amber" href="<?= base_url();?>stocktake/add_stocktake_arrangement" data-toggle="tooltip" data-trigger="hover" style="font-weight:bold;" data-original-title="Create arragement" ><i class="fa fa-plus-circle amber" style="font-size:16px;height:45px;"></i> Add Line</a> 

									<a href="javascript: void(0);" class="th" style="color: #D9A200; outline: none !important;text-decoration: none;"><span id="payment_receipt_service_info_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Payment Voucher" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add Line</span></a>-->
									<a href="javascript: void(0);" data-toggle="tooltip" data-trigger="hover" style="font-weight:bold;" data-original-title="Add age group" id="age_group_add" ><i class="fa fa-plus-circle amber" style="font-size:16px;"></i> Add Line</a>
								</th>
							</tr>
						</thead>

						<tbody id="body_add_age_group">
							<!-- <tr>
								<td valign=middle>Up to</td>
								<td valign=middle>
									<input type='number' class="form-control general" name="age_years[]" value="" style="width: 50%; display:inline-block !important;" /> 
									years						  
								</td>
								<td valign=middle>
									<input type='number' class="form-control general" name="age_months[]" value="" style="width: 50%; display:inline-block !important;" /> 
									months
								</td>
								<td valign=middle>
									<input type='number' class="form-control general" name="employer_percent[]" value="" style="width: 26%; display:inline-block !important;" /> 
									%
								</td>
								<td valign=middle>
									<input type='number' class="form-control general" name="employee_percent[]" value="" style="width: 26%; display:inline-block !important;" /> 
									%
								</td>
								<td valign=middle>
									<button type="button" class="btn btn_purple delete_age_group">Delete line</button>
								</td>
							</tr> -->
						</tbody>
			
					</table>
				</form>
			</div>
			<div class="modal-footer">
				<button type="submit" id ="save_age_group" class="btn btn_purple" >Save</button>
				<input type="button" class="btn btn-default cancel" data-dismiss="modal" name="" value="Cancel">
			</div>
		
		</div>

		<table id="clone_age_group_model" style="display: none;" >
			<tr class="">
				<input type="hidden" class="form-control general" name="age_group_id[]" value="">
				<td valign=middle>Up to</td>
				<td valign=middle>
					<input type='number' class="form-control general" name="age_years[]" value="" style="width: 50%; display:inline-block !important;" required/> 
					years						  
				</td>
				<td valign=middle>
					<input type='number' class="form-control general" name="age_months[]" value="" style="width: 50%; display:inline-block !important;" required/> 
					months
				</td>
				<td valign=middle>
					<input type='number' class="form-control general" name="employer_percent[]" value="" style="width: 40%; display:inline-block !important;" required/> 
					%
				</td>
				<td valign=middle>
					<input type='number' class="form-control general" name="employee_percent[]" value="" style="width: 40%; display:inline-block !important;" required/> 
					%
				</td>
				<td valign=middle>
					<button type="button" class="btn btn_purple delete_age_group" onclick="delete_age_group(this)">Delete line</button>
				</td>
			</tr>
		</table>
	</div>
</div>
<!----AGE GROUP MODAL END---------------------------------------------------------------------------------------------------------------------------------------------------------------->
<div id="nationality_modal" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;" data-backdrop="static">
	<div class="modal-dialog" style="width: 1000px !important;">
		<div class="modal-content">
			<header class="panel-heading">
				<h2 class="panel-title"><strong>Nationality</strong></h2>
			</header>
			<div class="panel-body">
				<form method="post" id="nationality_form">
					<input type="hidden" class="form-control general" name="nationality_period_id" value="">
					<table class="" style="width:100%;table-layout: fixed;border-right:none;border-bottom:none;" id="nationality_tbl">
						<thead style="width:100%;">
							<tr>
								<th style="height:35px;width:45%;" valign=middle></th>
								<th style="height:35px;width:20%;" valign=middle>Employer</th>
								<th style="height:35px;width:20%;" valign=middle>Employee</th>
								<th style="height:35px;width:15%;" valign=middle></th>

								<!-- <th style="height:35px;width:12%;" valign=middle>
									<a class="create_st_arrangement amber" href="<?= base_url();?>stocktake/add_stocktake_arrangement" data-toggle="tooltip" data-trigger="hover" style="font-weight:bold;" data-original-title="Create arragement" ><i class="fa fa-plus-circle amber" style="font-size:16px;height:45px;"></i> Add Line</a> 

									<a href="javascript: void(0);" class="th" style="color: #D9A200; outline: none !important;text-decoration: none;"><span id="payment_receipt_service_info_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Payment Voucher" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add Line</span></a>
									<a href="javascript: void(0);" data-toggle="tooltip" data-trigger="hover" style="font-weight:bold;" data-original-title="Add nationality group" id="nationaility_add" ><i class="fa fa-plus-circle amber" style="font-size:16px;"></i> Add Line</a>
								</th> -->
							</tr>
						</thead>

						<tbody id="body_add_nationality">
							<!-- <tr>
								<td valign=middle>Up to</td>
								<td valign=middle>
									<input type='number' class="form-control general" name="age_years[]" value="" style="width: 50%; display:inline-block !important;" /> 
									years						  
								</td>
								<td valign=middle>
									<input type='number' class="form-control general" name="age_months[]" value="" style="width: 50%; display:inline-block !important;" /> 
									months
								</td>
								<td valign=middle>
									<input type='number' class="form-control general" name="employer_percent[]" value="" style="width: 26%; display:inline-block !important;" /> 
									%
								</td>
								<td valign=middle>
									<input type='number' class="form-control general" name="employee_percent[]" value="" style="width: 26%; display:inline-block !important;" /> 
									%
								</td>
								<td valign=middle>
									<button type="button" class="btn btn_purple delete_age_group">Delete line</button>
								</td>
							</tr> -->
						</tbody>
			
					</table>
				</form>
			</div>
			<div class="modal-footer">
				<button type="submit" id ="save_nationality" class="btn btn_purple" >Save</button>
				<input type="button" class="btn btn-default cancel" data-dismiss="modal" name="" value="Cancel">
			</div>
		
		</div>

		<table id="clone_nationality_model" style="display: none;" >
			<tr class="">
				<input type="hidden" class="form-control general" name="nationality_id[]" value="">>
				<td valign=middle>
					<?php
						echo form_dropdown('nationality_type[]', $nationality_type_list, '', 'class="general nationality_type_field form-control" disabled');
					?>
				</td>
				<td valign=middle>
					<input type='number' class="form-control disable general" name="employer_percent[]" value="" style="width: 80%; display:inline-block !important;" required/> 
					%
				</td>
				<td valign=middle>
					<input type='number' class="form-control disable general" name="employee_percent[]" value="" style="width: 80%; display:inline-block !important;" required/> 
					%
				</td>
				<td valign=middle>
					<span class="nationality_message" style="color: red;display: none;">*Follow age group</span>
				</td>
				
			</tr>
		</table>
	</div>
</div>
<!----NATIONALTY MODAL END---------------------------------------------------------------------------------------------------------------------------------------------------------------->

<script type="text/javascript">

	var active_tab = <?php echo json_encode(isset($active_tab)?$active_tab:"type_of_leave") ?>;
	var type_of_leave_list = <?php echo json_encode(isset($type_of_leave_list)?$type_of_leave_list:"") ?>;
	var block_leave_list = <?php echo json_encode(isset($block_leave_list)?$block_leave_list:"") ?>;
	var approval_cap_list = <?php echo json_encode(isset($approval_cap_list)?$approval_cap_list:"") ?>;
	var partner_list = <?php echo json_encode(isset($partner_list)?$partner_list:"") ?>;
	var event_list = <?php echo json_encode(isset($event_list)?$event_list:"") ?>;
	var job_list = <?php echo json_encode(isset($job_list)?$job_list:"") ?>;
	var database_department = <?php echo json_encode(isset($departments)?$departments:"") ?>;
	var database_payroll_offices = <?php echo json_encode(isset($offices)?$offices:"") ?>;
	var database_payroll_charge_out_rate = <?php echo json_encode(isset($charge_out_rate)?$charge_out_rate:"") ?>;
	var institution_list = <?php echo json_encode(isset($institution_list)?$institution_list:"") ?>;
	var pv_index_tab_aktif;
	var holiday_list;
	var salary_cap_list = <?php echo json_encode(isset($salary_cap_list)?$salary_cap_list:"") ?>;
	var add_salary_cap_link = "<?php echo site_url('setting/add_salary_cap') ?>";
	var age_group_period_list = <?php echo json_encode(isset($age_group_period_list)?$age_group_period_list:"") ?>;
	var get_age_group_link = "<?php echo site_url('setting/get_age_group') ?>";
	var add_age_group_period_link = "<?php echo site_url('setting/add_age_group_period') ?>";
	var save_age_group_link = "<?php echo site_url('setting/save_age_group') ?>";	
	var delete_age_group_link = "<?php echo site_url('setting/delete_age_group') ?>";									  
	var add_nationality_period_link = "<?php echo site_url('setting/add_nationality_period') ?>";
	var nationality_period_list = <?php echo json_encode(isset($nationality_period_list)?$nationality_period_list:"") ?>;
	var get_nationality_link = "<?php echo site_url('setting/get_nationality') ?>";
	var save_nationality_link = "<?php echo site_url('setting/save_nationality') ?>";	



	$(".int_select2").select2();

	$(document).on('click',".edit_type_of_leave",function(){
	    var edit_type_of_leave_id =  $(this).data("id");

	    for(var i = 0; i < type_of_leave_list.length; i++)
	    {
	    	if(type_of_leave_list[i]["id"] == edit_type_of_leave_id)
	    	{
				if(type_of_leave_list[i]["id"] == 8)
				{
					$(".label_one").css("display","none");
					$(".label_three").css("display","none");
					$(".label_four").css("display","none");
					$(".second_condition").css("display","none");
					$('.third_condition').css("display","none");
					$('.fourth_condition').css("display","none");

					$('.label_two').show();
					$(".label_two").css("display","inline-block !important");
				}
				else if(type_of_leave_list[i]["id"] == 4)
				{
					$(".label_one").css("display","none");
					$(".label_two").css("display","none");
					$(".label_four").css("display","none");
					$('.third_condition').css("display","none");
					$('.fourth_condition').css("display","none");

					$('.second_condition').show();
					$('.label_three').show();
					$(".label_three").css("display","inline-block !important");
				}
				else if(type_of_leave_list[i]["id"] == 5)
				{
					$(".label_one").css("display","none");
					$(".label_two").css("display","none");
					$(".label_three").css("display","none");

					$('.label_four').show();
					$(".label_four").css("display","inline-block !important");
					$('.second_condition').show();
					$('.third_condition').show();
					$('.fourth_condition').show();
				}
				else
				{
					$(".label_two").css("display","none");
					$(".label_three").css("display","none");
					$(".label_four").css("display","none");
					$(".second_condition").css("display","none");
					$('.third_condition').css("display","none");
					$('.fourth_condition').css("display","none");

					$('.label_one').show();
					$(".label_one").css("display","inline-block !important");
				}

	    		$(".type_of_leave_id").val(type_of_leave_list[i]["id"]);
    			$("#leave_name").val(type_of_leave_list[i]["leave_name"]);
    			$("#leave_days").val(type_of_leave_list[i]["days"]);
    			$("#leave_days_second_condition").val(type_of_leave_list[i]["second_condition"]);
    			$("#leave_days_third_condition").val(type_of_leave_list[i]["third_condition"]);
    			$("#leave_days_fourth_condition").val(type_of_leave_list[i]["fourth_condition"]);
    			$(".choose_carry_forward_id").val(type_of_leave_list[i]["choose_carry_forward_id"]);
	    	}
	    }
	});

	$(document).on('click',".edit_block_leave",function(){
	    var edit_block_leave_id =  $(this).data("id");
	    $('.block_leave_department_list option[value=7]').remove();
	    $('.block_leave_office_list option[value=1]').remove();
	    for(var i = 0; i < block_leave_list.length; i++)
	    {
	    	if(block_leave_list[i]["id"] == edit_block_leave_id)
	    	{
	    		$(".block_leave_id").val(block_leave_list[i]["id"]);
    			$(".block_leave_date_from").val(moment(block_leave_list[i]["block_leave_date_from"]).format('DD MMMM YYYY'));
    			$(".block_leave_date_to").val(moment(block_leave_list[i]["block_leave_date_to"]).format('DD MMMM YYYY'));
    			$(".block_leave_department_list").val(block_leave_list[i]["department_id"]).trigger('change');
    			$(".block_leave_office_list").val(block_leave_list[i]["offices_id"]).trigger('change');
	    	}
	    }
	});

	$(document).on('click',".edit_approval_cap",function(){
	    var edit_approval_cap_id =  $(this).data("id");
	  	$('.approvalCap_department_list option[value=7]').remove();
	    $('.approvalCap_office_list option[value=1]').remove();
	    for(var i = 0; i < approval_cap_list.length; i++)
	    {
	    	if(approval_cap_list[i]["id"] == edit_approval_cap_id)
	    	{
	    		$(".approval_cap_id").val(approval_cap_list[i]["id"]);
    			$(".approval_cap_date_from").val(moment(approval_cap_list[i]["approval_cap_date_from"]).format('DD MMMM YYYY'));
    			$(".approval_cap_date_to").val(moment(approval_cap_list[i]["approval_cap_date_to"]).format('DD MMMM YYYY'));
    			$(".approvalCap_department_list").val(approval_cap_list[i]["department_id"]).trigger('change');
    			$(".approvalCap_office_list").val(approval_cap_list[i]["offices_id"]).trigger('change');
    			$(".number_of_employee").val(approval_cap_list[i]["number_of_employee"]);

	    	}
	    }
	});

	$(document).on('click',".edit_partner",function(){
	    var edit_partner_id =  $(this).data("id");

	    for(var i = 0; i < partner_list.length; i++)
	    {
	    	if(partner_list[i]["id"] == edit_partner_id)
	    	{
	    		$(".partner_id").val(partner_list[i]["id"]);
    			$("#partner").val(partner_list[i]["partner_name"]);
    			$(".partner_email_id").val(partner_list[i]["user_id"]).trigger('change');
	    	}
	    }
	});

	$(document).on('click',".edit_event",function(){
	    var edit_event_id =  $(this).data("id");

	    for(var i = 0; i < event_list.length; i++)
	    {
	    	if(event_list[i]["id"] == edit_event_id)
	    	{
	    		$(".event_id").val(event_list[i]["id"]);
    			$("#event").val(event_list[i]["event"]);
	    	}
	    }
	});

	$(document).on('click',".edit_job",function(){
	    var edit_job_id =  $(this).data("id");

	    for(var i = 0; i < job_list.length; i++)
	    {
	    	if(job_list[i]["id"] == edit_job_id)
	    	{
	    		$(".job_id").val(job_list[i]["id"]);
    			$("#job").val(job_list[i]["type_of_job"]);
	    	}
	    }
	});

	$(document).on('click',".edit_office",function(){
	    var edit_id =  $(this).data("id");

	    for(var i = 0; i < database_payroll_offices.length; i++)
	    {
	    	if(database_payroll_offices[i]["id"] == edit_id)
	    	{
	    		$(".office_id").val(database_payroll_offices[i]["id"]);
	    		$(".offices_office_name").val(database_payroll_offices[i]["office_name"]);
    			$(".offices_country").val(database_payroll_offices[i]["office_country"]).trigger('change');
	    	}
	    }
	});

	$(document).on('click',".edit_department",function(){
	    var edit_id =  $(this).data("id");

	    for(var i = 0; i < database_department.length; i++)
	    {
	    	if(database_department[i]["id"] == edit_id)
	    	{
	    		$(".department_id").val(database_department[i]["id"]);
	    		$(".dpt_department_name").val(database_department[i]["department_name"]);
	    	}
	    }
	});

	$(document).on('click',".edit_institution",function(){
	    var edit_institution_id =  $(this).data("id");

	    for(var i = 0; i < institution_list.length; i++)
	    {
	    	if(institution_list[i]["id"] == edit_institution_id)
	    	{
	    		$(".institution_id").val(institution_list[i]["id"]);
    			$("#institution").val(institution_list[i]["institution_name"]);
	    	}
	    }
	});

	 $('[data-toggle="tooltip"]').tooltip();

	toastr.options = {
	  "positionClass": "toast-bottom-right"
	}

	$('.datepicker').datepicker({
		format: 'dd MM yyyy',
	});

	$(document).ready(function(){
		$('.datepicker_cpf').datepicker({
			format: 'dd/mm/yyyy',
		});
	});

	$('.carry_forward_period_datepicker').datepicker({
		format: 'dd MM',
	}).on('show', function() {
	    var dateText  = $(".datepicker-days .datepicker-switch").text();
	    var dateTitle = dateText.substr(0, dateText.length - 5);
	    $(".datepicker-days .datepicker-switch").text(dateTitle);
	});

	var userTarget1 = "";
	var exit1 = false;
	$('.leave_cycle_daterange').datepicker({
	  format: "dd MM",
	  language: "en",
	  orientation: "bottom auto",
	  autoclose: true,
	  showOnFocus: true,
	  keepEmptyValues: true,
	}).on('show', function() {
	    // remove the year from the date title before the datepicker show
	    var dateText  = $(".datepicker-days .datepicker-switch").text();
	    var dateTitle = dateText.substr(0, dateText.length - 5);
	    $(".datepicker-days .datepicker-switch").text(dateTitle);
	});

	$('.leave_cycle_daterange').focusin(function(e) {
	  userTarget1 = e.target.name;
	});
	$('.leave_cycle_daterange').on('changeDate', function(e) {
	  if (exit1) return;
	  if (e.target.name != userTarget1) {
	    exit1 = true;
	    $(e.target).datepicker('clearDates');
	  }
	  exit1 = false;
	});


	var userTarget = "";
	var exit = false;
	$('.input-daterange').datepicker({
	  format: "dd MM yyyy",
	  weekStart: 1,
	  language: "en",
	  startDate: "01/01/1957",
	  orientation: "bottom auto",
	  autoclose: true,
	  showOnFocus: true,
	  keepEmptyValues: true,
	});
	$('.input-daterange').focusin(function(e) {
	  userTarget = e.target.name;
	});
	$('.input-daterange').on('changeDate', function(e) {
	  if (exit) return;
	  if (e.target.name != userTarget) {
	    exit = true;
	    $(e.target).datepicker('clearDates');
	  }
	  exit = false;
	});

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
	  var regex = /[0-9]|\./;
	  if( !regex.test(key) ) {
	    theEvent.returnValue = false;
	    if(theEvent.preventDefault) theEvent.preventDefault();
	  }
	}

	if(active_tab != null)
	{  
		pv_index_tab_aktif = active_tab;

	    if(active_tab != "type_of_leave")
	    {
	        $('li[data-information="'+active_tab+'"]').addClass("active");
	        $('#w2-'+active_tab+'').addClass("active");
	        $('li[data-information="type_of_leave"]').removeClass("active");
	        $('#w2-type_of_leave').removeClass("active");
	    }
	}

	$(document).on('click',".check_state",function(){
		pv_index_tab_aktif = $(this).data("information");
	});

	(function( $ ) {
		'use strict';

		var datatableInit = function() {

			var table1 = $('.datatable-setting-cap').DataTable({
				
	            "order": [[ 0, 'asc' ]]
			});
		};

		$(function() {
			datatableInit();
		});

	}).apply( this, [ jQuery ]);

	(function( $ ) {
		'use strict';

		var datatableInit = function() {

			var table1 = $('.datatable-setting-charge_out_rate').DataTable({
				
	            "order": [[ 0, 'asc' ]]
			});
		};

		$(function() {
			datatableInit();
		});

	}).apply( this, [ jQuery ]);

	(function( $ ) {
		'use strict';

		var datatableInit = function() {

			var table1 = $('.datatable-setting-department').DataTable({
				
	            "order": [[ 0, 'asc' ]]
			});
		};

		$(function() {
			datatableInit();
		});

	}).apply( this, [ jQuery ]);

	(function( $ ) {
		'use strict';

		var datatableInit = function() {

			var table1 = $('.datatable-setting-block_leave').DataTable({
				
	            "order": [[ 0, 'asc' ]]
			});
		};

		$(function() {
			datatableInit();
		});

	}).apply( this, [ jQuery ]);

	(function( $ ) {
		'use strict';

		var datatableInit = function() {

			var table1 = $('.datatable-setting-partner').DataTable({
				
	            "order": [[ 0, 'asc' ]]
			});
		};

		$(function() {
			datatableInit();
		});

	}).apply( this, [ jQuery ]);

	(function( $ ) {
		'use strict';

		var datatableInit = function() {

			var table1 = $('.datatable-setting-event').DataTable({
				
	            "order": [[ 0, 'asc' ]]
			});
		};

		$(function() {
			datatableInit();
		});

	}).apply( this, [ jQuery ]);

	(function( $ ) {
		'use strict';

		var datatableInit = function() {

			var table1 = $('.datatable-setting').DataTable({
				
	            "order": [[ 0, 'asc' ]]
			});
		};

		$(function() {
			datatableInit();
		});

	}).apply( this, [ jQuery ]);

	(function( $ ) {
		'use strict';

		var datatableInit = function() {

			var table1 = $('.datatable-setting-holiday').DataTable({
				
	            "order": [[ 0, 'asc' ]]
			});
		};

		$(function() {
			datatableInit();
		});

	}).apply( this, [ jQuery ]);

	(function( $ ) {
		'use strict';

		var datatableInit = function() {

			var table1 = $('.datatable-setting-job').DataTable({
				
	            "order": [[ 0, 'asc' ]]
			});
		};

		$(function() {
			datatableInit();
		});

	}).apply( this, [ jQuery ]);

	(function( $ ) {
		'use strict';

		var datatableInit = function() {

			var table1 = $('.datatable-setting-offices').DataTable({
				
	            "order": [[ 0, 'asc' ]]
			});
		};

		$(function() {
			datatableInit();
		});

	}).apply( this, [ jQuery ]);

	(function( $ ) {
		'use strict';

		var datatableInit = function() {

			var table1 = $('.datatable-setting-team_shifts').DataTable({
				
	            "order": [[ 0, 'asc' ]]
			});
		};

		$(function() {
			datatableInit();
		});

	}).apply( this, [ jQuery ]);

	(function( $ ) {
		'use strict';

		var datatableInit = function() {

			var table1 = $('.datatable-setting-institution').DataTable({
				
	            "order": [[ 0, 'asc' ]]
			});
		};

		$(function() {
			datatableInit();
		});

	}).apply( this, [ jQuery ]);

// FORM SUBMIT
// LEAVE TYPES -----------------------------------------------------------------------------------------------------------------------------------------------------
	$("#type_of_leave_submit").submit(function(e) {
        var form = $(this);

        $.ajax({
           type: "POST",
           url: "setting/submit_type_of_leave",
           data: form.serialize(), // serializes the form's elements.
           success: function(data)
           {	
           		if(data){
           			toastr.success('Information Updated', 'Updated');
           			location.reload();
           		}
           }
       	});
    	e.preventDefault(); // avoid to execute the actual submit of the form.
    });
// APPROVAL CAP ----------------------------------------------------------------------------------------------------------------------------------------------------
    $("#approval_cap_submit").submit(function(e) {
        var form = $(this);

        $.ajax({
           type: "POST",
           url: "setting/submit_approval_cap",
           data: form.serialize(), // serializes the form's elements.
           success: function(data)
           {	
           		if(data){
           			toastr.success('Information Updated', 'Updated');
           			location.reload();
           		}
           		else
           		{
           			toastr.error('Approval Cap Already Exist', 'Error');
           		}
           }
       	});
    	e.preventDefault(); // avoid to execute the actual submit of the form.
    });
// BLOCK LEAVE -----------------------------------------------------------------------------------------------------------------------------------------------------
    $("#block_leave_submit").submit(function(e) {
        var form = $(this);

        $.ajax({
           type: "POST",
           url: "setting/submit_block_leave",
           data: form.serialize(), // serializes the form's elements.
           success: function(data)
           {	
           		if(data){
           			toastr.success('Information Updated', 'Updated');
           			// location.reload();
           		}
           		else
           		{
           			toastr.error('Block Leave Already Exist', 'Error');
           		}
           }
       	});
    	e.preventDefault(); // avoid to execute the actual submit of the form.
    });
// LEAVE CYCLE -----------------------------------------------------------------------------------------------------------------------------------------------------
    $("#leave_cycle_submit").submit(function(e) {
        var form = $(this);

        $.ajax({
           type: "POST",
           url: "setting/submit_leave_cycle",
           data: form.serialize(), // serializes the form's elements.
           success: function(data)
           {	
           		if(data){
           			toastr.success('Information Updated', 'Updated');
           			location.reload();
           		}
           }
       	});
    	e.preventDefault(); // avoid to execute the actual submit of the form.
    });
// LEAVE HOLIDAY ---------------------------------------------------------------------------------------------------------------------------------------------------
    $("#block_holiday_submit").submit(function(e) {
        var form = $(this);

        $.ajax({
           type: "POST",
           url: "setting/submit_holiday",
           data: form.serialize(), // serializes the form's elements.
           success: function(data)
           {	
           		if(data){
           			toastr.success('Information Updated', 'Updated');
           			location.reload();
           		}
           		else
           		{
           			toastr.error('Holiday Already Exist', 'Error');
           		}

           }
       	});
    	e.preventDefault(); // avoid to execute the actual submit of the form.
    });
// CARRY FORWARD ---------------------------------------------------------------------------------------------------------------------------------------------------
    $("#carry_forward_period_submit").submit(function(e) {
        var form = $(this);

        $.ajax({
           type: "POST",
           url: "setting/submit_carry_forward_period",
           data: form.serialize(), // serializes the form's elements.
           success: function(data)
           {	
           		if(data){
           			toastr.success('Information Updated', 'Updated');
           			location.reload();
           		}
           }
       	});
    	e.preventDefault(); // avoid to execute the actual submit of the form.
    });
// PARTYNER LIST ---------------------------------------------------------------------------------------------------------------------------------------------------
    $("#partner_submit").submit(function(e) {
        var form = $(this);

        $.ajax({
           type: "POST",
           url: "setting/submit_partner",
           data: form.serialize(), // serializes the form's elements.
           success: function(data)
           {	
           		if(data){
           			toastr.success('Information Updated', 'Updated');
           			location.reload();
           		}
           		else
           		{
           			toastr.error('Partner Already Exist', 'Error');
           		}
           }
       	});
    	e.preventDefault(); // avoid to execute the actual submit of the form.
    });
// EVENT LIST ------------------------------------------------------------------------------------------------------------------------------------------------------
    $("#event_submit").submit(function(e) {
        var form = $(this);

        $.ajax({
           type: "POST",
           url: "setting/submit_event",
           data: form.serialize(), // serializes the form's elements.
           success: function(data)
           {	
           		if(data){
           			toastr.success('Information Updated', 'Updated');
           			location.reload();
           		}
           		else
           		{
           			toastr.error('Event Already Exist', 'Error');
           		}
           }
       	});
    	e.preventDefault(); // avoid to execute the actual submit of the form.
    });
// JOB LIST --------------------------------------------------------------------------------------------------------------------------------------------------------
    $("#job_submit").submit(function(e) {
        var form = $(this);

        $.ajax({
           type: "POST",
           url: "setting/submit_job",
           data: form.serialize(), // serializes the form's elements.
           success: function(data)
           {	
           		if(data){
           			toastr.success('Information Updated', 'Updated');
           			location.reload();
           		}
           		else
           		{
           			toastr.error('Job Already Exist', 'Error');
           		}
           }
       	});
    	e.preventDefault(); // avoid to execute the actual submit of the form.
    });
// // OFFICES ---------------------------------------------------------------------------------------------------------------------------------------------------------
// 	$("#offices_submit").submit(function(e) {
//         var form = $(this);
//         console.log($('.office_id').val());
//         $.ajax({
//            type: "POST",
//            url: "setting/submit_offices",
//            data: '&offices_office_name=' + $('.offices_office_name').val() + '&offices_country=' + $('.offices_country').val() + '&office_id=' + $(".office_id").val(),
//            success: function(data)
//            {	
//            		if(data){
//            			toastr.success('Information Updated', 'Updated');
//            			location.reload();
//            		}
//            		else
//            		{
//            			toastr.error('Office Already Exist', 'Error');
//            		}
//            }
//        	});
//     	e.preventDefault(); // avoid to execute the actual submit of the form.
//     });
// // DEPARTMENT ------------------------------------------------------------------------------------------------------------------------------------------------------
// 	$("#department_submit").submit(function(e) {
//         var form = $(this);
//         console.log($('.dtp_department_name').val());
//         $.ajax({
//            type: "POST",
//            url: "setting/submit_department",
//            data: '&dpt_department_name=' + $('.dpt_department_name').val() + '&department_id=' + $(".department_id").val(),
//            success: function(data)
//            {	
//            		if(data){
//            			toastr.success('Information Updated', 'Updated');
//            			location.reload();
//            		}
//            		else
//            		{
//            			toastr.error('Department Already Exist', 'Error');
//            		}
//            }
//        	});
//     	e.preventDefault(); // avoid to execute the actual submit of the form.
//     });

// CHARGE-OUT RATE ---------------------------------------------------------------------------------------------------------------------------------------------------
	$("#charge_out_rate_submit").submit(function(e) {
        var form = $(this);

        $.ajax({
           type: "POST",
           url: "setting/submit_charge_out_rate",
           data: '&charge_out_rate_id=' + $('.charge_out_rate_id').val() +'&charge_out_rate_office_list=' + $('.charge_out_rate_office_list').val() + '&charge_out_rate_department_list=' + $('.charge_out_rate_department_list').val() +'&charge_out_rate_designation=' + $('.charge_out_rate_designation').val()+ '&charge_out_rate=' + $(".charge_out_rate").val(),
           success: function(data)
           {	
           		if(data){
           			toastr.success('Information Updated', 'Updated');
           			location.reload();
           		}
           		else
           		{
           			toastr.error('Charge-Out Rate Already Exist', 'Error');
           		}
           }
       	});
    	e.preventDefault(); // avoid to execute the actual submit of the form.
    });
// INSTITUTION -------------------------------------------------------------------------------------------------------------------------------------------------------
    $("#institution_submit").submit(function(e) {
        var form = $(this);

        $.ajax({
           type: "POST",
           url: "setting/submit_institution",
           data: form.serialize(), // serializes the form's elements.
           success: function(data)
           {	
           		if(data){
           			toastr.success('Information Updated', 'Updated');
           			location.reload();
           		}
           		else
           		{
           			toastr.error('Institution Already Exist', 'Error');
           		}
           }
       	});
    	e.preventDefault(); // avoid to execute the actual submit of the form.
    });
// -----------------------------------------------------------------------------------------------------------------------------------------------------------------

    function delete_holiday(element){
    	var div 		= $(element).parent();
    	var holiday_id 	= div.find('.holiday_id').val();

    	bootbox.confirm({
	        message: "Do you want to delete this selected info?",
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

	        	if(result == true)
	        	{
	        		$.post("setting/delete_holiday", { 'holiday_id': holiday_id }, function(data, status){
			    	 	if(data){
			    	 		location.reload();
			    	 	}
			    	});
	        	}
	        }
	    })
    }

    function delete_partner(element){
    	var div 		= $(element).parent();
    	var partner_id 	= div.find('.partner_id').val();

    	bootbox.confirm({
	        message: "Do you want to delete this selected info?",
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

	        	if(result == true)
	        	{
	        		$.post("setting/delete_partner", { 'partner_id': partner_id }, function(data, status){
			    	 	if(data){
			    	 		location.reload();
			    	 	}
			    	});
	        	}
	        }
	    })
    }

    function delete_event(element){
    	var div 		= $(element).parent();
    	var event_id 	= div.find('.event_id').val();

    	bootbox.confirm({
	        message: "Do you want to delete this selected info?",
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

	        	if(result == true)
	        	{
	        		$.post("setting/delete_event", { 'event_id': event_id }, function(data, status){
			    	 	if(data){
			    	 		location.reload();
			    	 	}
			    	});
	        	}
	        }
	    })
    }

    function delete_job(element){
    	var div 		= $(element).parent();
    	var job_id 	= div.find('.job_id').val();

    	bootbox.confirm({
	        message: "Do you want to delete this selected info?",
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

	        	if(result == true)
	        	{
	        		$.post("setting/delete_job", { 'job_id': job_id }, function(data, status){
			    	 	if(data){
			    	 		location.reload();
			    	 	}
			    	});
	        	}
	        }
	    })
    }

    function delete_office(element){
    	var div 		= $(element).parent();
    	var office_id 	= div.find('.office_id').val();

    	bootbox.confirm({
	        message: "Do you want to delete this selected info?",
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

	        	if(result == true)
	        	{
	        		$.post("setting/delete_office", { 'office_id': office_id }, function(data, status){
			    	 	if(data){
			    	 		location.reload();
			    	 	}
			    	});
	        	}
	        }
	    })
    }

    function delete_department(element){
    	var div 		= $(element).parent();
    	var department_id 	= div.find('.department_id').val();

    	bootbox.confirm({
	        message: "Do you want to delete this selected info?",
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

	        	if(result == true)
	        	{
	        		$.post("setting/delete_department", { 'department_id': department_id }, function(data, status){
			    	 	if(data){
			    	 		location.reload();
			    	 	}
			    	});
	        	}
	        }
	    })
    }

    function delete_type_of_leave(element){
    	var div 				= $(element).parent();
    	var type_of_leave_id 	= div.find('.type_of_leave_id').val();

    	bootbox.confirm({
	        message: "Do you want to delete this selected info?",
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

	        	if(result == true)
	        	{
	        		$.post("setting/delete_type_of_leave", { 'type_of_leave_id': type_of_leave_id }, function(data, status){
			    	 	if(data)
			    	 	{
			    	 		location.reload();
			    	 	}
			    	});
	        	}
	        }
	    })
    }

    function delete_block_leave(element){
    	var div 				= $(element).parent();
    	var block_leave_id 		= div.find('.block_leave_id').val();

    	bootbox.confirm({
	        message: "Do you want to delete this selected info?",
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

	        	if(result == true)
	        	{
	        		$.post("setting/delete_block_leave", { 'block_leave_id': block_leave_id }, function(data, status){
			    	 	if(data)
			    	 	{
			    	 		location.reload();
			    	 	}
			    	});
	        	}
	        }
	    })
    }

    function delete_approval_cap(element){
    	var div 			= $(element).parent();
    	var approval_cap_id = div.find('.approval_cap_id').val();

    	bootbox.confirm({
	        message: "Do you want to delete this selected info?",
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

	        	if(result == true)
	        	{
	        		$.post("setting/delete_approval_cap", { 'approval_cap_id': approval_cap_id }, function(data, status){
			    	 	if(data)
			    	 	{
			    	 		location.reload();
			    	 	}
			    	});
	        	}
	        }
	    })
    }

    function delete_charge_out_rate(element){
    	var div 			= $(element).parent();
    	var charge_out_rate_id = div.find('.charge_out_rate_id').val();

    	bootbox.confirm({
	        message: "Do you want to delete this selected info?",
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

	        	if(result == true)
	        	{
	        		$.post("setting/delete_charge_out_rate", { 'charge_out_rate_id': charge_out_rate_id }, function(data, status){
			    	 	if(data)
			    	 	{
			    	 		location.reload();
			    	 	}
			    	});
	        	}
	        }
	    })
    }

    function delete_institution(element){
    	var div 		= $(element).parent();
    	var institution_id 	= div.find('.institution_id').val();

    	bootbox.confirm({
	        message: "Do you want to delete this selected info?",
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

	        	if(result == true)
	        	{
	        		$.post("setting/delete_institution", { 'institution_id': institution_id }, function(data, status){
			    	 	if(data){
			    	 		location.reload();
			    	 	}
			    	});
	        	}
	        }
	    })
    }

    $(".year_list").change(function(e){
        var form = $(this);

        $.ajax({
           type: "POST",
           url: "setting/holiday_filter",
           data: '&offices_filter=' + $('.offices_filter').val() + '&department_filter=' + $('.department_filter').val() + '&year_list=' + $(".year_list").val(),
           success: function(data)
           {	
           		if(JSON.parse(data)==null || JSON.parse(data)==""){
           			$(".holiday_table").empty();
           		}

           		if(JSON.parse(data)!=null){

	           		$(".datatable-setting-holiday").DataTable().destroy();
	           		var holiday_object = (JSON.parse(data));
	           		$(".holiday_table").empty();

					for(var i = 0; i < holiday_object.length; i++){

						var rowHtml =  " <tr class='holiday_data'><td><span style='display: none'>"+holiday_object[i]["holiday_date"]+"</span><a href='javascript:void(0)' class='edit_block_holiday' data-id='"+holiday_object[i]["id"]+"' id='edit_block_holiday'>"+ moment(holiday_object[i]["holiday_date"]).format('DD MMMM YYYY') +"</a></td><td>"+holiday_object[i]["description"]+"</td><td>"+holiday_object[i]["office_name"]+"</td><td>"+holiday_object[i]["department_name"]+"</td><td style='text-align:center;'><input type='hidden' class='holiday_id' value='"+ holiday_object[i]["id"] +"' /><button type='button' class='btn btn_purple' onclick=delete_holiday(this)>Delete</button></td></tr> ";

						$(".holiday_table").append(rowHtml);

					}

					$('.datatable-setting-holiday').DataTable({
				    	"order": [],
				    	"bStateSave": true,
				    	"autoWidth" : false,
				        pageLength: 10
				    });
				}
				holiday_list = holiday_object;

           }
       	});
    	e.preventDefault();
    });

    $(".offices_filter").change(function(e) {
        var form = $(this);

        $.ajax({
           type: "POST",
           url: "setting/holiday_filter",
           data: '&offices_filter=' + $('.offices_filter').val() + '&department_filter=' + $('.department_filter').val() + '&year_list=' + $(".year_list").val(),

           success: function(data)
           {	
           		if(JSON.parse(data)==null || JSON.parse(data)=="")
           		{
           			$(".holiday_table").empty();
           		}

           		if(JSON.parse(data)!=null){

	           		$(".datatable-setting-holiday").DataTable().destroy();
	           		var holiday_object = (JSON.parse(data));
	           		$(".holiday_table").empty();

					for(var i = 0; i < holiday_object.length; i++){

						var rowHtml =  " <tr class='holiday_data'><td><span style='display: none'>"+holiday_object[i]["holiday_date"]+"</span><a href='javascript:void(0)' class='edit_block_holiday' data-id='"+holiday_object[i]["id"]+"' id='edit_block_holiday'>"+ moment(holiday_object[i]["holiday_date"]).format('DD MMMM YYYY') +"</a></td><td>"+holiday_object[i]["description"]+"</td><td>"+holiday_object[i]["office_name"]+"</td><td>"+holiday_object[i]["department_name"]+"</td><td style='text-align:center;'><input type='hidden' class='holiday_id' value='"+ holiday_object[i]["id"] +"' /><button type='button' class='btn btn_purple' onclick=delete_holiday(this)>Delete</button></td></tr> ";

						$(".holiday_table").append(rowHtml);

					}

					$('.datatable-setting-holiday').DataTable({
				    	"order": [],
				    	"bStateSave": true,
				    	"autoWidth" : false,
				        pageLength: 10
				    });
				}
				holiday_list = holiday_object;

           }
       	});
    	e.preventDefault();
    });//jw

	$(".department_filter").change(function(e) {
        var form = $(this);

        $.ajax({
           type: "POST",
           url: "setting/holiday_filter",
           data: '&offices_filter=' + $('.offices_filter').val() + '&department_filter=' + $('.department_filter').val() + '&year_list=' + $(".year_list").val(),

           success: function(data)
           {	
           		if(JSON.parse(data)==null || JSON.parse(data)=="")
           		{
           			$(".holiday_table").empty();
           		}

           		if(JSON.parse(data)!=null){

	           		$(".datatable-setting-holiday").DataTable().destroy();
	           		var holiday_object = (JSON.parse(data));
	           		$(".holiday_table").empty();

					for(var i = 0; i < holiday_object.length; i++){

						var rowHtml =  " <tr class='holiday_data'><td><span style='display: none'>"+holiday_object[i]["holiday_date"]+"</span><a href='javascript:void(0)' class='edit_block_holiday' data-id='"+holiday_object[i]["id"]+"' id='edit_block_holiday'>"+ moment(holiday_object[i]["holiday_date"]).format('DD MMMM YYYY') +"</a></td><td>"+holiday_object[i]["description"]+"</td><td>"+holiday_object[i]["office_name"]+"</td><td>"+holiday_object[i]["department_name"]+"</td><td style='text-align:center;'><input type='hidden' class='holiday_id' value='"+ holiday_object[i]["id"] +"' /><button type='button' class='btn btn_purple' onclick=delete_holiday(this)>Delete</button></td></tr> ";

						$(".holiday_table").append(rowHtml);

					}

					$('.datatable-setting-holiday').DataTable({
				    	"order": [],
				    	"bStateSave": true,
				    	"autoWidth" : false,
				        pageLength: 10
				    });
				}
				holiday_list = holiday_object;
           }
       	});
    	e.preventDefault();
    });//jw

    $(document).on('click',".edit_block_holiday",function(){
	    var edit_block_holiday_id =  $(this).data("id");
	    $('.department_list2 option[value=7]').remove();
	    $('.office_list option[value=1]').remove();
	    for(var i = 0; i < holiday_list.length; i++)
	    {
	    	if(holiday_list[i]["id"] == edit_block_holiday_id)
	    	{
	    		$(".block_holiday_id").val(holiday_list[i]["id"]);
    			$(".block_holiday").val(moment(holiday_list[i]["holiday_date"]).format('DD MMMM YYYY'));
    			$(".holiday_description").val(holiday_list[i]["description"]);
    			$(".department_list2").val(holiday_list[i]["department_id"]).trigger('change');
    			$(".office_list").val(holiday_list[i]["offices_id"]).trigger('change');
	    	}
	    }
	});//jw


// CLEAR BUTTON IN PUBLIC HOLIDAY TAB ------------------------------------------------------------------------------------------------------------------------------
	$(document).on('click',".cancel_holiday",function(){

		// REMOVE & REAPPEND OFFICES DROPDOWN ----------------------------------------------------------------------------------------------------------------------
	    $(".office_div").remove();

	    var office_dropdown = '<div class="office_div" style="width: 30%;float: left;"><select name="office_list" class="form-control office_list int_select2" required><option value="" selected="selected">Please Select Office</option>';

	    for($i=0;$i<database_payroll_offices.length;$i++)
	    {
	    	office_dropdown += '<option value="'+database_payroll_offices[$i]['id']+'">'+database_payroll_offices[$i]['office_name']+'</option>';
	    }

		office_dropdown += '</select></div>';

	    $(".office_div_div").append(office_dropdown);

	    // REMOVE & REAPPEND DEPARTMENT DROPDOWN -------------------------------------------------------------------------------------------------------------------
	    $(".department_div").remove();

	    var department_dropdown = '<div class="department_div" style="width: 30%;float: left;"><select name="department_list" class="form-control department_list2 int_select2" required><option value="" selected="selected">Please Select Department</option>';

	    for($i=0;$i<database_department.length;$i++)
	    {
	    	department_dropdown += '<option value="'+database_department[$i]['id']+'">'+database_department[$i]['department_name']+'</option>';
	    }

		department_dropdown += '</select></div>';

	    $(".department_div_div").append(department_dropdown);
	    
	    // CLEAR ALL INPUT -----------------------------------------------------------------------------------------------------------------------------------------
	    $(".block_holiday_id").val('');
		$(".block_holiday").val('');
		$(".holiday_description").val('');
		$(".department_list2").val('').trigger('change');
		$(".office_list").val('').trigger('change');

		//REINITIAL SELECT2 FOR DEPARTMENT AND OFFICES DROPDOWN ----------------------------------------------------------------------------------------------------
		$(".int_select2").select2();
	});//jw

// CLEAR BUTTON IN APPROVAL CAP TAB --------------------------------------------------------------------------------------------------------------------------------
	$(document).on('click',".cancel_Cap",function(){

		// REMOVE & REAPPEND OFFICES DROPDOWN ----------------------------------------------------------------------------------------------------------------------
	    $(".approvalCap_office_div").remove();

	    var office_dropdown = '<div class="col-sm-3 form-inline approvalCap_office_div" style="width: 28.9% !important"><select name="approvalCap_office_list" class="form-control approvalCap_office_list int_select2" style="width: 100% !important" required><option value="" selected="selected">Please Select Office</option>';

	    for($i=0;$i<database_payroll_offices.length;$i++)
	    {
	    	office_dropdown += '<option value="'+database_payroll_offices[$i]['id']+'">'+database_payroll_offices[$i]['office_name']+'</option>';
	    }

		office_dropdown += '</select></div>';

	    $(".approvalCap_office_div_div").append(office_dropdown);

	    // REMOVE & REAPPEND DEPARTMENT DROPDOWN -------------------------------------------------------------------------------------------------------------------
	    $(".approvalCap_department_div").remove();

	    var department_dropdown = '<div class="col-sm-3 form-inline approvalCap_department_div" style="width: 28.9% !important"><select name="approvalCap_department_list" class="form-control approvalCap_department_list int_select2" style="width: 100% !important" required><option value="" selected="selected">Please Select Department</option>';

	    for($i=0;$i<database_department.length;$i++)
	    {
	    	department_dropdown += '<option value="'+database_department[$i]['id']+'">'+database_department[$i]['department_name']+'</option>';
	    }

		department_dropdown += '</select></div>';

	    $(".approvalCap_department_div_div").append(department_dropdown);
	    
	    // CLEAR ALL INPUT -----------------------------------------------------------------------------------------------------------------------------------------
	    $(".approval_cap_id").val('');
		$(".approval_cap_date_from").val('');
		$(".approval_cap_date_id").val('');
		$(".number_of_employee").val('0');
		$(".approvalCap_office_list").val('').trigger('change');
		$(".approvalCap_department_list").val('').trigger('change');

		//REINITIAL SELECT2 FOR DEPARTMENT AND OFFICES DROPDOWN ----------------------------------------------------------------------------------------------------
		$(".int_select2").select2();
	});//jw

// CLEAR BUTTON IN BLOCK LEAVE TAB ---------------------------------------------------------------------------------------------------------------------------------
	$(document).on('click',".cancel_block_leave",function(){

		// REMOVE & REAPPEND OFFICES DROPDOWN ----------------------------------------------------------------------------------------------------------------------
	    $(".block_leave_office_div").remove();

	    var office_dropdown = '<div class="block_leave_office_div" style="width: 56.2%;float: left;"><select name="block_leave_office_list" class="form-control block_leave_office_list int_select2" style="width: 100% !important" required><option value="" selected="selected">Please Select Office</option>';

	    for($i=0;$i<database_payroll_offices.length;$i++)
	    {
	    	office_dropdown += '<option value="'+database_payroll_offices[$i]['id']+'">'+database_payroll_offices[$i]['office_name']+'</option>';
	    }

		office_dropdown += '</select></div>';

	    $(".block_leave_office_div_div").append(office_dropdown);

	    // REMOVE & REAPPEND DEPARTMENT DROPDOWN -------------------------------------------------------------------------------------------------------------------
	    $(".block_leave_department_div").remove();

	    var department_dropdown = '<div class="block_leave_department_div" style="width: 56.2%;float: left"><select name="block_leave_department_list" class="form-control block_leave_department_list int_select2" style="width: 100% !important" required><option value="" selected="selected">Please Select Department</option>';

	    for($i=0;$i<database_department.length;$i++)
	    {
	    	department_dropdown += '<option value="'+database_department[$i]['id']+'">'+database_department[$i]['department_name']+'</option>';
	    }

		department_dropdown += '</select></div>';

	    $(".block_leave_department_div_div").append(department_dropdown);
	    
	    // CLEAR ALL INPUT -----------------------------------------------------------------------------------------------------------------------------------------
	    $(".block_leave_id").val('');
		$(".block_leave_office_list").val('').trigger('change');
		$(".block_leave_department_list").val('').trigger('change');
		$(".block_leave_date_from").val('');
		$(".block_leave_date_to").val('');

		//REINITIAL SELECT2 FOR DEPARTMENT AND OFFICES DROPDOWN ----------------------------------------------------------------------------------------------------
		$(".int_select2").select2();
	});//jw

	$(document).on('click',".office_cancel",function()
	{
		$(".office_id").val('');
		$(".offices_office_name").val('');
		$(".offices_country").val('').trigger('change');
	});

	$(document).on('click',".department_cancel",function()
	{
		$(".department_id").val('');
		$(".dpt_department_name").val('');
	});

	$(".approvalCap_year_filter").change(function(e){
        var form = $(this);

        $.ajax({
           type: "POST",
           url: "setting/cap_filter",
           data: '&offices_filter=' + $('.approvalCap_offices_filter').val() + '&department_filter=' + $('.approvalCap_department_filter').val() + '&year_list=' + $(".approvalCap_year_filter").val(),
           success: function(data)
           {	
           		if(JSON.parse(data)==null || JSON.parse(data)==""){
           			$(".cap_table").empty();
           		}

           		if(JSON.parse(data)!=null){

	           		$(".datatable-setting-cap").DataTable().destroy();
	           		var object = (JSON.parse(data));
	           		$(".cap_table").empty();

					for(var i = 0; i < object.length; i++){

						var rowHtml =  "<tr><td><a href='javascript:void(0)' class='edit_approval_cap' data-id='"+object[i]["id"]+"' id='edit_approval_cap'>"+moment(object[i]["approval_cap_date_from"]).format('DD MMMM YYYY')+"</a></td><td>"+moment(object[i]["approval_cap_date_to"]).format('DD MMMM YYYY')+"</td><td>"+object[i]["office_name"]+"</td><td>"+object[i]["department_name"]+"</td><td>"+object[i]["number_of_employee"]+"</td><td style='text-align:center;''><input type='hidden' class='approval_cap_id' value='"+object[i]["id"]+"' /><button type='button' class='btn btn_purple' onclick=delete_approval_cap(this)>Delete</button></td></tr>";

						$(".cap_table").append(rowHtml);

					}

					$('.datatable-setting-cap').DataTable({
				    	"order": [],
				    	"bStateSave": true,
				    	"autoWidth" : false,
				        pageLength: 10
				    });
				}
           }
       	});
    	e.preventDefault();
    });

    $(".approvalCap_offices_filter").change(function(e) {
        var form = $(this);

        $.ajax({
           type: "POST",
           url: "setting/cap_filter",
           data: '&offices_filter=' + $('.approvalCap_offices_filter').val() + '&department_filter=' + $('.approvalCap_department_filter').val() + '&year_list=' + $(".approvalCap_year_filter").val(),
           success: function(data)
           {	
           		if(JSON.parse(data)==null || JSON.parse(data)=="")
           		{
           			$(".cap_table").empty();
           		}

           		if(JSON.parse(data)!=null){

	           		$(".datatable-setting-cap").DataTable().destroy();
	           		var object = (JSON.parse(data));
	           		$(".cap_table").empty();

					for(var i = 0; i < object.length; i++){

						var rowHtml =  "<tr><td><a href='javascript:void(0)' class='edit_approval_cap' data-id='"+object[i]["id"]+"' id='edit_approval_cap'>"+moment(object[i]["approval_cap_date_from"]).format('DD MMMM YYYY')+"</a></td><td>"+moment(object[i]["approval_cap_date_to"]).format('DD MMMM YYYY')+"</td><td>"+object[i]["office_name"]+"</td><td>"+object[i]["department_name"]+"</td><td>"+object[i]["number_of_employee"]+"</td><td style='text-align:center;''><input type='hidden' class='approval_cap_id' value='"+object[i]["id"]+"' /><button type='button' class='btn btn_purple' onclick=delete_approval_cap(this)>Delete</button></td></tr>";

						$(".cap_table").append(rowHtml);

					}

					$('.datatable-setting-cap').DataTable({
				    	"order": [],
				    	"bStateSave": true,
				    	"autoWidth" : false,
				        pageLength: 10
				    });
				}
           }
       	});
    	e.preventDefault();
    });//jw

	$(".approvalCap_department_filter").change(function(e) {
        var form = $(this);

        $.ajax({
           type: "POST",
           url: "setting/cap_filter",
           data: '&offices_filter=' + $('.approvalCap_offices_filter').val() + '&department_filter=' + $('.approvalCap_department_filter').val() + '&year_list=' + $(".approvalCap_year_filter").val(),
           success: function(data)
           {	
           		if(JSON.parse(data)==null || JSON.parse(data)=="")
           		{
           			$(".cap_table").empty();
           		}

           		if(JSON.parse(data)!=null){

	           		$(".datatable-setting-cap").DataTable().destroy();
	           		var object = (JSON.parse(data));
	           		$(".cap_table").empty();

					for(var i = 0; i < object.length; i++){

						var rowHtml =  "<tr><td><a href='javascript:void(0)' class='edit_approval_cap' data-id='"+object[i]["id"]+"' id='edit_approval_cap'>"+moment(object[i]["approval_cap_date_from"]).format('DD MMMM YYYY')+"</a></td><td>"+moment(object[i]["approval_cap_date_to"]).format('DD MMMM YYYY')+"</td><td>"+object[i]["office_name"]+"</td><td>"+object[i]["department_name"]+"</td><td>"+object[i]["number_of_employee"]+"</td><td style='text-align:center;''><input type='hidden' class='approval_cap_id' value='"+object[i]["id"]+"' /><button type='button' class='btn btn_purple' onclick=delete_approval_cap(this)>Delete</button></td></tr>";

						$(".cap_table").append(rowHtml);

					}

					$('.datatable-setting-cap').DataTable({
				    	"order": [],
				    	"bStateSave": true,
				    	"autoWidth" : false,
				        pageLength: 10
				    });
				}
           }
       	});
    	e.preventDefault();
    });//jw



    $(".block_leave_year_filter").change(function(e){
        var form = $(this);

        $.ajax({
           type: "POST",
           url: "setting/block_leave_filter",
           data: '&offices_filter=' + $('.block_leave_offices_filter').val() + '&department_filter=' + $('.block_leave_department_filter').val() + '&year_list=' + $(".block_leave_year_filter").val(),
           success: function(data)
           {	
           		if(JSON.parse(data)==null || JSON.parse(data)==""){
           			$(".block_leave_table").empty();
           		}

           		if(JSON.parse(data)!=null){

	           		$(".datatable-setting-block_leave").DataTable().destroy();
	           		var object = (JSON.parse(data));
	           		$(".block_leave_table").empty();

					for(var i = 0; i < object.length; i++){

						var rowHtml =  "<tr><td><a href='javascript:void(0)' class='edit_block_leave' data-id='"+object[i]["id"]+"' id='edit_block_leave'>"+moment(object[i]["block_leave_date_from"]).format('DD MMMM YYYY')+"</a></td><td>"+moment(object[i]["block_leave_date_to"]).format('DD MMMM YYYY')+"</td><td>"+object[i]["office_name"]+"</td><td>"+object[i]["department_name"]+"</td><td style='text-align:center;'><input type='hidden' class='block_leave_id' value='"+object[i]["id"]+"' /><button type='button' class='btn btn_purple' onclick=delete_block_leave(this)>Delete</button></td></tr>";

						$(".block_leave_table").append(rowHtml);

					}

					$('.datatable-setting-block_leave').DataTable({
				    	"order": [],
				    	"bStateSave": true,
				    	"autoWidth" : false,
				        pageLength: 10
				    });
				}
           }
       	});
    	e.preventDefault();
    });

    $(".block_leave_offices_filter").change(function(e) {
        var form = $(this);

        $.ajax({
           type: "POST",
           url: "setting/block_leave_filter",
           data: '&offices_filter=' + $('.block_leave_offices_filter').val() + '&department_filter=' + $('.block_leave_department_filter').val() + '&year_list=' + $(".block_leave_year_filter").val(),
           success: function(data)
           {	
           		if(JSON.parse(data)==null || JSON.parse(data)=="")
           		{
           			$(".block_leave_table").empty();
           		}

           		if(JSON.parse(data)!=null){

	           		$(".datatable-setting-block_leave").DataTable().destroy();
	           		var object = (JSON.parse(data));
	           		$(".block_leave_table").empty();

					for(var i = 0; i < object.length; i++){

						var rowHtml =  "<tr><td><a href='javascript:void(0)' class='edit_block_leave' data-id='"+object[i]["id"]+"' id='edit_block_leave'>"+moment(object[i]["block_leave_date_from"]).format('DD MMMM YYYY')+"</a></td><td>"+moment(object[i]["block_leave_date_to"]).format('DD MMMM YYYY')+"</td><td>"+object[i]["office_name"]+"</td><td>"+object[i]["department_name"]+"</td><td style='text-align:center;'><input type='hidden' class='block_leave_id' value='"+object[i]["id"]+"' /><button type='button' class='btn btn_purple' onclick=delete_block_leave(this)>Delete</button></td></tr>";

						$(".block_leave_table").append(rowHtml);

					}

					$('.datatable-setting-block_leave').DataTable({
				    	"order": [],
				    	"bStateSave": true,
				    	"autoWidth" : false,
				        pageLength: 10
				    });
				}
           }
       	});
    	e.preventDefault();
    });//jw

	$(".block_leave_department_filter").change(function(e) {
        var form = $(this);

        $.ajax({
           type: "POST",
           url: "setting/block_leave_filter",
           data: '&offices_filter=' + $('.block_leave_offices_filter').val() + '&department_filter=' + $('.block_leave_department_filter').val() + '&year_list=' + $(".block_leave_year_filter").val(),
           success: function(data)
           {	
           		if(JSON.parse(data)==null || JSON.parse(data)=="")
           		{
           			$(".block_leave_table").empty();
           		}

           		if(JSON.parse(data)!=null){

	           		$(".datatable-setting-block_leave").DataTable().destroy();
	           		var object = (JSON.parse(data));
	           		$(".block_leave_table").empty();

					for(var i = 0; i < object.length; i++){

						var rowHtml =  "<tr><td><a href='javascript:void(0)' class='edit_block_leave' data-id='"+object[i]["id"]+"' id='edit_block_leave'>"+moment(object[i]["block_leave_date_from"]).format('DD MMMM YYYY')+"</a></td><td>"+moment(object[i]["block_leave_date_to"]).format('DD MMMM YYYY')+"</td><td>"+object[i]["office_name"]+"</td><td>"+object[i]["department_name"]+"</td><td style='text-align:center;'><input type='hidden' class='block_leave_id' value='"+object[i]["id"]+"' /><button type='button' class='btn btn_purple' onclick=delete_block_leave(this)>Delete</button></td></tr>";

						$(".block_leave_table").append(rowHtml);

					}

					$('.datatable-setting-block_leave').DataTable({
				    	"order": [],
				    	"bStateSave": true,
				    	"autoWidth" : false,
				        pageLength: 10
				    });
				}
           }
       	});
    	e.preventDefault();
    });//jw

    $(document).on('click',".charge_out_rate_cancel",function()
	{
		$(".charge_out_rate_office_div").remove();

	    var office_dropdown = '<div class="col-sm-3 form-inline charge_out_rate_office_div" style="width: 28.9%"><select class="form-control charge_out_rate_office_list int_select2" style="width: 100% !important" required><option value="" selected="selected">Please Select Office</option>';

	    for($i=0;$i<database_payroll_offices.length;$i++)
	    {
	    	office_dropdown += '<option value="'+database_payroll_offices[$i]['id']+'">'+database_payroll_offices[$i]['office_name']+'</option>';
	    }

		office_dropdown += '</select></div>';

	    $(".charge_out_rate_office_div_div").append(office_dropdown);




	    $(".charge_out_rate_department_div").remove();

	    var department_dropdown = '<div class="col-sm-3 form-inline charge_out_rate_department_div" style="width: 28.9%"><select name="charge_out_rate_department_list" class="form-control charge_out_rate_department_list int_select2" style="width: 100% !important" required><option value="" selected="selected">Please Select Department</option>';

	    for($i=0;$i<database_department.length;$i++)
	    {
	    	department_dropdown += '<option value="'+database_department[$i]['id']+'">'+database_department[$i]['department_name']+'</option>';
	    }

		department_dropdown += '</select></div>';

	    $(".charge_out_rate_department_div_div").append(department_dropdown);
	    $('.charge_out_rate_department_list option[value=7]').remove();




		$(".charge_out_rate_designation_div").remove();

	    var dropdown = '<div class="col-sm-3 form-inline charge_out_rate_designation_div" style="width: 28.9%"><select class="form-control charge_out_rate_designation int_select2" style="width: 100% !important" required><option value="" selected="selected">Please Select Designation</option></select></div>';

	    $(".charge_out_rate_designation_div_div").append(dropdown);
	    $(".int_select2").select2();



		$(".charge_out_rate").val(0);
		$(".charge_out_rate_id").val('');
		$(".designation").val("");
	});

    $(document).on('change', '.charge_out_rate_department_list', function() {

	// $(".charge_out_rate_department_list").change(function(e) {

			var form = $(this);
        
	        var department = $(".charge_out_rate_department_list").val();

	        if(department == ''){
	        	$(".charge_out_rate_designation_div").remove();

			    var dropdown = '<div class="col-sm-3 form-inline charge_out_rate_designation_div" style="width: 28.9%"><select class="form-control charge_out_rate_designation int_select2" style="width: 100% !important" required><option value="" selected="selected">Please Select Designation</option></select></div>';

			    $(".charge_out_rate_designation_div_div").append(dropdown);
			    $(".int_select2").select2();
	        }
	        else{

	        	$.ajax({
		           type: "POST",
		           url:  "setting/get_designation",
		           data: "&department="+ department,
		           success: function(data)
		           {	
		           		var result = JSON.parse(data);

		           		$(".charge_out_rate_designation_div").remove();
						
						var dropdown = '<div class="col-sm-3 form-inline charge_out_rate_designation_div" style="width: 28.9%"><select class="form-control charge_out_rate_designation int_select2" style="width: 100% !important" required><option value="" selected="selected">Please Select Designation</option>';

		           		if(result != ''){

						    for($i=0;$i<result.length;$i++)
						    {
						    	dropdown += '<option value="'+result[$i]['id']+'">'+result[$i]['designation']+'</option>';
						    }

		           		}
		           		
		           		dropdown += '</select></div>';
					    $(".charge_out_rate_designation_div_div").append(dropdown);

					    if($(".designation").val() != ''){
				       		$(".charge_out_rate_designation").val($(".designation").val()).trigger('change');
				       		$(".designation").val("");
				       	}

					    $(".int_select2").select2();
		           }
		       	});
	        }
    });

    $(document).on('click', '.edit_charge_out_rate', function() {

    	var id =  $(this).data("id");

    	$('.charge_out_rate_department_list option[value=7]').remove();
	    $('.charge_out_rate_office_list option[value=1]').remove();

	    for(var i = 0; i < database_payroll_charge_out_rate.length; i++)
	    {
	    	if(database_payroll_charge_out_rate[i]["id"] == id)
	    	{
	    		$(".charge_out_rate_id").val(id);
	    		$(".charge_out_rate_office_list").val(database_payroll_charge_out_rate[i]["office_id"]).trigger('change');
	    		$(".designation").val(database_payroll_charge_out_rate[i]["designation_id"]);
	    		$(".charge_out_rate_department_list").val(database_payroll_charge_out_rate[i]["department_id"]).trigger('change');
	    		$(".charge_out_rate").val(database_payroll_charge_out_rate[i]["rate"]);
	    	}
	    }
    });

    $(document).ready(function () {
    	$('.charge_out_rate_department_list option[value=7]').remove();
    });

    $("#team_id").change(function(e){
        var form = $(this);

        $.ajax({
           type: "POST",
           url: "setting/team_filter",
           data: '&team_id=' + $('#team_id').val(),
           success: function(data)
           {	
           		if(JSON.parse(data)==null || JSON.parse(data)==""){
           			$(".team_table").empty();
           		}

           		if(JSON.parse(data)!=null){

	           		$(".datatable-setting-team_shifts").DataTable().destroy();
	           		var object = (JSON.parse(data));
	           		$(".team_table").empty();

					for(var i = 0; i < object.length; i++){

						if(object[i]["team_shift"] == 0)
	  					{
	  						var team_shift = '<td class="text-center"><input type="hidden" class="team_b4_change" value="'+object[i]["team_shift"]+'"><select data-id="'+object[i]["id"]+'" class="selected_team" onchange=change_team(this)><option value="0" selected>Not In Team</option><option value="1">Team 1</option><option value="2">Team 2</option></select></td>';
	  					}
	  					else if(object[i]["team_shift"] == 1)
	  					{
	  						var team_shift = '<td class="text-center"><input type="hidden" class="team_b4_change" value="'+object[i]["team_shift"]+'"><select data-id="'+object[i]["id"]+'" class="selected_team" onchange=change_team(this)><option value="0">Not In Team</option><option value="1" selected>Team 1</option><option value="2">Team 2</option></select></td>';
	  					}
	  					else if(object[i]["team_shift"] == 2)
	  					{
	  						var team_shift = '<td class="text-center"><input type="hidden" class="team_b4_change" value="'+object[i]["team_shift"]+'"><select data-id="'+object[i]["id"]+'" class="selected_team" onchange=change_team(this)><option value="0">Not In Team</option><option value="1">Team 1</option><option value="2" selected>Team 2</option></select></td>';
	  					}

						var rowHtml =  " <tr class='team_data'><td>"+object[i]["name"]+"</td>"+team_shift+"</tr> ";

						$(".team_table").append(rowHtml);

					}

					$('.datatable-setting-team_shifts').DataTable({
				    	"order": [],
				    	"bStateSave": true,
				    	"autoWidth" : false,
				        pageLength: 10
				    });
				}

           }
       	});
    	e.preventDefault();
    });

    function change_team(element){
    	var div  = $(element).parent();
    	var team = div.find('.selected_team').val();
    	var id = div.find('.selected_team').attr("data-id");
    	var team_b4_change = div.find('.team_b4_change').val();

    	bootbox.confirm({
	        message: "Do you want to change the employee team?",
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
	        	if(result == true)
	        	{
	        		$.ajax({
			           type: "POST",
			           url: "setting/update_team",
			           data: '&id=' + id + '&team_shift=' + team,
			           success: function(data)
			           {	
			           		location.reload();
			           }
			       	});

	        		// div.find('.team_b4_change').val(team);
	        	}
	        	else
	        	{
	        		div.find('.selected_team').val(team_b4_change);
	        	}
	        }
	    });
    }

</script>

<script src="<?= base_url()?>application/modules/setting/js/cpf_setting.js" charset="utf-8"></script>