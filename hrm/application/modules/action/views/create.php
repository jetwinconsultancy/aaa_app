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

<section role="main" class="content_section" style="margin-left:0;">
	<?php echo $breadcrumbs;?>
	<section class="panel" style="margin-top: 30px;">
		<form id="action" method="POST" enctype="multipart/form-data"> 
		<div class="panel-body">
			<div>
                <table class="table table-bordered table-striped table-condensed mb-none" id="event_info_table" style="width: 100%">
                    <thead>
                        <div class="tr">
                            <div class="th" id="date" style="text-align: center;width:250px">Date</div>
                            <div class="th" id="event" style="text-align: center;width:250px">Event</div>
                            <div class="th" id="document" style="text-align: center;width:500px">Attachment</div>
                            <?php if($Admin || $this->data['Manager'] || $this->user_id == 79 || $this->user_id == 62 || $this->user_id == 91 || $this->user_id == 107) { ?>
                            <a href="javascript: void(0);" class="th" rowspan=2 style="color: #D9A200;width:150px; outline: none !important;text-decoration: none; text-align: center;"><span id="event_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Family Info" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add </span></a>
                            <?php } ?>
                        </div>
                    </thead>
                    <div class="tbody" id="body_event_info"></div>
                </table>
            </div>
        </div>


		<footer class="panel-footer">
			<div class="row">
				<?php 
            		echo '<a href="'.base_url().'action" class="btn pull-right btn_cancel" style="margin:0.5%; cursor: pointer;">Cancel</a>';
            	?>
			</div>
		</footer>
		</form>
	</section>
</section>

<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>

<div id="employment_contract" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;" data-backdrop="static">
	<div class="modal-dialog" style="width: 1000px !important;">
		<div class="modal-content">
			<header class="panel-heading">
				<h2 class="panel-title"><strong>Employment Contract</strong></h2>
			</header>
			<form id="employment_contract">
				<div class="panel-body">
					<div class="col-md-12">
						<div class="form-group">
							<div style="width: 100%;">
								<div style="width: 25%;float:left;margin-right: 20px;">
		                            <label>Full Name :</label>
		                        </div>
		                        <div style="width: 65%;float:left;margin-bottom:5px;">
		                            <div style="width: 20%;">
		                            	<input type="text" class="form-control" id="EC_staff_name" name="staff_name" value="<?=isset($staff[0]->name)? $staff[0]->name:'' ?>" style="width: 400px;" disabled/>
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
		                            	<input type="text" class="form-control" id="EC_staff_nric_finno" name="staff_nric_finno" value="<?=isset($staff[0]->nric_fin_no)?$staff[0]->nric_fin_no:'' ?>" style="width: 400px;" disabled/>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		                <div class="form-group">
	                        <div style="width: 100%;">
		                        <div style="width: 25%;float:left;margin-right: 20px;">
		                            <label>Job title :</label>
		                        </div>
		                        <div style="width: 65%;float:left;margin-bottom:5px;">
		                            <div style="width: 20%;">
		                            	
		                            </div>
		                        </div>
		                    </div>
		                </div>
		                <div class="form-group">
	                        <div style="width: 100%;">
		                        <div style="width: 25%;float:left;margin-right: 20px;">
		                            <label>Date of commencement :</label>
		                        </div>
		                        <div style="width: 65%;float:left;margin-bottom:5px;">
		                            <div style="width: 20%;">
		                            	
		                            </div>
		                        </div>
		                    </div>
		                </div>
		                <div class="form-group">
	                        <div style="width: 100%;">
		                        <div style="width: 25%;float:left;margin-right: 20px;">
		                            <label>Hours of Work :</label>
		                        </div>
		                        <div style="width: 65%;float:left;margin-bottom:5px;">
		                            <div style="width: 20%;">
		                            	
		                            </div>
		                        </div>
		                    </div>
		                </div>
		                <div class="form-group">
	                        <div style="width: 100%;">
		                        <div style="width: 25%;float:left;margin-right: 20px;">
		                            <label>Vacation leave :</label>
		                        </div>
		                        <div style="width: 65%;float:left;margin-bottom:5px;">
		                            <div style="width: 20%;">
		                            	
		                            </div>
		                        </div>
		                    </div>
		                </div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" id ="" class="btn btn_purple" >Proceed</button>
					<input type="button" class="btn btn-default cancel" data-dismiss="modal" name="" value="Cancel">
				</div>
			</form>
		</div>
	</div>
</div>

<div id="probation_pass" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;" data-backdrop="static">
	<div class="modal-dialog" style="width: 1000px !important;">
		<div class="modal-content">
			<header class="panel-heading">
				<h2 class="panel-title"><strong>Pass Probation Letter</strong></h2>
			</header>
				<div class="panel-body">
					<div class="col-md-12">
						<input type="text" class="hidden" id="this" />
						<div class="form-group">
							<div style="width: 100%;">
								<div style="width: 25%;float:left;margin-right: 20px;">
		                            <label>Date :</label>
		                        </div>
		                        <div style="width: 65%;float:left;margin-bottom:5px;">
		                            <div style="width: 20%;">
		                            	<input type="text" class="form-control" id="PPL_today_date" name="PPL_today_date" value="<?php echo date('d F Y');?>" style="width: 400px;" disabled/>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		                <div class="form-group">
							<div style="width: 100%;">
								<div style="width: 25%;float:left;margin-right: 20px;">
		                            <label>Full Name :</label>
		                        </div>
		                        <div style="width: 65%;float:left;margin-bottom:5px;">
		                            <div style="width: 20%;">
		                            	<input type="text" class="form-control" id="PPL_staff_name" name="PPL_staff_name" value="<?=isset($staff[0]->name)? $staff[0]->name:'' ?>" style="width: 400px;" disabled/>
		                            </div>
		                        </div>
		                    </div>
		                </div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn_purple" id="PPL_Proceed">Proceed</button>
					<input type="button" class="btn btn-default cancel" data-dismiss="modal" name="" value="Cancel">
				</div>
		</div>
	</div>
</div>

<div id="probation_extension" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;" data-backdrop="static">
	<div class="modal-dialog" style="width: 1000px !important;">
		<div class="modal-content">
			<header class="panel-heading">
				<h2 class="panel-title"><strong>Extend Probation Letter</strong></h2>
			</header>
				<div class="panel-body">
					<div class="col-md-12">
						<input type="text" class="hidden" id="this" />
						<div class="form-group">
							<div style="width: 100%;">
								<div style="width: 25%;float:left;margin-right: 20px;">
		                            <label>Date :</label>
		                        </div>
		                        <div style="width: 65%;float:left;margin-bottom:5px;">
		                            <div style="width: 20%;">
		                            	<input type="text" class="form-control" id="EPL_today_date" name="EPL_today_date" value="<?php echo date('d F Y');?>" style="width: 400px;" disabled/>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		                <div class="form-group">
							<div style="width: 100%;">
								<div style="width: 25%;float:left;margin-right: 20px;">
		                            <label>Full Name :</label>
		                        </div>
		                        <div style="width: 65%;float:left;margin-bottom:5px;">
		                            <div style="width: 20%;">
		                            	<input type="text" class="form-control" id="EPL_staff_name" name="EPL_staff_name" value="<?=isset($staff[0]->name)? $staff[0]->name:'' ?>" style="width: 400px;" disabled/>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		                <div class="form-group">
							<div style="width: 100%;">
								<div style="width: 25%;float:left;margin-right: 20px;">
		                            <label>Probation Start :</label>
		                        </div>
		                        <div style="width: 65%;float:left;margin-bottom:5px;">
		                            <div style="width: 20%;">
		                            	<input type="text" class="form-control" id="EPL_probation_start" name="EPL_probation_start" value="<?=isset($staff[0]->date_joined)? date('d F Y', strtotime($staff[0]->date_joined)):'' ?>" style="width: 400px;" disabled/>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		                <div class="form-group">
							<div style="width: 100%;">
								<div style="width: 25%;float:left;margin-right: 20px;">
		                            <label>Extend Period (Months) :</label>
		                        </div>
		                        <div style="width: 65%;float:left;margin-bottom:5px;">
		                            <div style="width: 100%;">
		                            	<input  type='number' class="form-control" name="EPL_extend_period" id='EPL_extend_period' value="0" min="1" style="width: 25%; text-align: center; display:inline-block !important;"  />
		                            	<label style="display:inline-block !important ; padding-left: 10px">Month(s)</label>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		                <div class="form-group">
							<div style="width: 100%;">
								<div style="width: 25%;float:left;margin-right: 20px;">
		                            <label>Extend To :</label>
		                        </div>
		                        <div style="width: 65%;float:left;margin-bottom:5px;">
		                            <div style="width: 20%;">
		                            	<input type="text" class="form-control" id="EPL_extend_to" name="EPL_extend_to" style="width: 400px;" disabled/>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		                <div class="form-group">
							<div style="width: 100%;">
								<div style="width: 25%;float:left;margin-right: 20px;">
		                            <label>Reason For Extension :</label>
		                        </div>
		                        <div style="width: 65%;float:left;margin-bottom:5px;">
		                            <div style="width: 66%;">
		                            	<textarea class="form-control" id="EPL_reason" name="EPL_reason" style="width: 100%;height: 100px" required="true" placeholder="1. Example Reason A&#10;2. Example Reason B&#10;3. Example Reason C" ></textarea>
		                            </div>
		                        </div>
		                    </div>
		                </div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn_purple" id="EPL_Proceed">Proceed</button>
					<input type="button" class="btn btn-default cancel" data-dismiss="modal" name="" value="Cancel">
				</div>
		</div>
	</div>
</div>

<div id="probation_failed" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;" data-backdrop="static">
	<div class="modal-dialog" style="width: 1000px !important;">
		<div class="modal-content">
			<header class="panel-heading">
				<h2 class="panel-title"><strong>Fail Probation Letter</strong></h2>
			</header>
				<div class="panel-body">
					<div class="col-md-12">
						<input type="text" class="hidden" id="this" />
						<div class="form-group">
							<div style="width: 100%;">
								<div style="width: 25%;float:left;margin-right: 20px;">
		                            <label>Date :</label>
		                        </div>
		                        <div style="width: 65%;float:left;margin-bottom:5px;">
		                            <div style="width: 20%;">
		                            	<input type="text" class="form-control" id="FPL_today_date" name="FPL_today_date" value="<?php echo date('d F Y');?>" style="width: 400px;" disabled/>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		                <div class="form-group">
							<div style="width: 100%;">
								<div style="width: 25%;float:left;margin-right: 20px;">
		                            <label>Full Name :</label>
		                        </div>
		                        <div style="width: 65%;float:left;margin-bottom:5px;">
		                            <div style="width: 20%;">
		                            	<input type="text" class="form-control" id="FPL_staff_name" name="FPL_staff_name" value="<?=isset($staff[0]->name)? $staff[0]->name:'' ?>" style="width: 400px;" disabled/>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		                <div class="form-group">
							<div style="width: 100%;">
								<div style="width: 25%;float:left;margin-right: 20px;">
		                            <label>Probation End On :</label>
		                        </div>
		                        <div style="width: 65%;float:left;margin-bottom:5px;">
		                            <div style="width: 20%;">
		                            	<input type="text" class="form-control datepicker" id="FPL_end_on" name="FPL_end_on" style="width: 400px;"/>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		                <div class="form-group">
							<div style="width: 100%;">
								<div style="width: 25%;float:left;margin-right: 20px;">
		                            <label>Reason For Failure :</label>
		                        </div>
		                        <div style="width: 65%;float:left;margin-bottom:5px;">
		                            <div style="width: 66%;">
		                            	<textarea class="form-control" id="FPL_reason" name="FPL_reason" style="width: 100%;height: 100px" required="true" placeholder="1. Example Reason A&#10;2. Example Reason B&#10;3. Example Reason C" ></textarea>
		                            </div>
		                        </div>
		                    </div>
		                </div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn_purple" id="FPL_Proceed">Proceed</button>
					<input type="button" class="btn btn-default cancel" data-dismiss="modal" name="" value="Cancel">
				</div>
		</div>
	</div>
</div>

<div id="employment_termination" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;" data-backdrop="static">
	<div class="modal-dialog" style="width: 1000px !important;">
		<div class="modal-content">
			<header class="panel-heading">
				<h2 class="panel-title"><strong>Termination of Employment</strong></h2>
			</header>
				<div class="panel-body">
					<div class="col-md-12">
						<input type="text" class="hidden" id="this" />
						<div class="form-group">
							<div style="width: 100%;">
								<div style="width: 25%;float:left;margin-right: 20px;">
		                            <label>Date :</label>
		                        </div>
		                        <div style="width: 65%;float:left;margin-bottom:5px;">
		                            <div style="width: 20%;">
		                            	<input type="text" class="form-control" id="ET_today_date" name="ET_today_date" value="<?php echo date('d F Y');?>" style="width: 400px;" disabled/>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		                <div class="form-group">
							<div style="width: 100%;">
								<div style="width: 25%;float:left;margin-right: 20px;">
		                            <label>Full Name :</label>
		                        </div>
		                        <div style="width: 65%;float:left;margin-bottom:5px;">
		                            <div style="width: 20%;">
		                            	<input type="text" class="form-control" id="ET_staff_name" name="ET_staff_name" value="<?=isset($staff[0]->name)? $staff[0]->name:'' ?>" style="width: 400px;" disabled/>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		                <div class="form-group">
							<div style="width: 100%;">
								<div style="width: 25%;float:left;margin-right: 20px;">
		                            <label>Terminate On :</label>
		                        </div>
		                        <div style="width: 65%;float:left;margin-bottom:5px;">
		                            <div style="width: 20%;">
		                            	<input type="text" class="form-control datepicker" id="ET_end_on" name="ET_end_on" style="width: 400px;"/>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		                <div class="form-group">
							<div style="width: 100%;">
								<div style="width: 25%;float:left;margin-right: 20px;">
		                            <label>Reason For Termination :</label>
		                        </div>
		                        <div style="width: 65%;float:left;margin-bottom:5px;">
		                            <div style="width: 66%;">
		                            	<textarea class="form-control" id="ET_reason" name="ET_reason" style="width: 100%;height: 100px" required="true" placeholder="1. Example Reason A&#10;2. Example Reason B&#10;3. Example Reason C" ></textarea>
		                            </div>
		                        </div>
		                    </div>
		                </div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn_purple" id="ET_Proceed">Proceed</button>
					<input type="button" class="btn btn-default cancel" data-dismiss="modal" name="" value="Cancel">
				</div>
		</div>
	</div>
</div>

<div id="recommendation_letter" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;" data-backdrop="static">
	<div class="modal-dialog" style="width: 1000px !important;">
		<div class="modal-content">
			<header class="panel-heading">
				<h2 class="panel-title"><strong>Letter of Recommendation</strong></h2>
			</header>
				<div class="panel-body">
					<div class="col-md-12">
						<input type="text" class="hidden" id="this" />
						<div class="form-group">
							<div style="width: 100%;">
								<div style="width: 25%;float:left;margin-right: 20px;">
		                            <label>Date :</label>
		                        </div>
		                        <div style="width: 65%;float:left;margin-bottom:5px;">
		                            <div style="width: 20%;">
		                            	<input type="text" class="form-control" id="RL_today_date" name="RL_today_date" value="<?php echo date('d F Y');?>" style="width: 400px;" disabled/>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		                <div class="form-group">
							<div style="width: 100%;">
								<div style="width: 25%;float:left;margin-right: 20px;">
		                            <label>Full Name :</label>
		                        </div>
		                        <div style="width: 65%;float:left;margin-bottom:5px;">
		                            <div style="width: 20%;">
		                            	<input type="text" class="form-control" id="RL_staff_name" name="RL_staff_name" value="<?=isset($staff[0]->name)? $staff[0]->name:'' ?>" style="width: 400px;" disabled/>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		                <div class="form-group">
							<div style="width: 100%;">
								<div style="width: 25%;float:left;margin-right: 20px;">
		                            <label>Designation  :</label>
		                        </div>
		                        <div style="width: 65%;float:left;margin-bottom:5px;">
		                            <div style="width: 20%;">
		                            	<input type="text" class="form-control" id="RL_designation" name="RL_designation" style="width: 400px;" disabled/>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		                <div class="form-group">
							<div style="width: 100%;">
								<div style="width: 25%;float:left;margin-right: 20px;">
		                            <label>Work Over :</label>
		                        </div>
		                        <div style="width: 65%;float:left;margin-bottom:5px;">
		                            <div style="width: 20%;">
		                            	<input type="text" class="form-control" id="RL_work_over" name="RL_work_over" style="width: 400px;" disabled />
		                            </div>
		                        </div>
		                    </div>
		                </div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn_purple" id="RL_Proceed">Proceed</button>
					<input type="button" class="btn btn-default cancel" data-dismiss="modal" name="" value="Cancel">
				</div>
		</div>
	</div>
</div>

<div id="PR_recommendation_letter" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;" data-backdrop="static">
	<div class="modal-dialog" style="width: 1000px !important;">
		<div class="modal-content">
			<header class="panel-heading">
				<h2 class="panel-title"><strong>Letter of PR Recommendation</strong></h2>
			</header>
				<div class="panel-body">
					<div class="col-md-12">
						<input type="text" class="hidden" id="this" />
						<div class="form-group">
							<div style="width: 100%;">
								<div style="width: 25%;float:left;margin-right: 20px;">
		                            <label>Date :</label>
		                        </div>
		                        <div style="width: 65%;float:left;margin-bottom:5px;">
		                            <div style="width: 20%;">
		                            	<input type="text" class="form-control" id="PRRL_today_date" name="PRRL_today_date" value="<?php echo date('d F Y');?>" style="width: 400px;" disabled/>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		                <div class="form-group">
							<div style="width: 100%;">
								<div style="width: 25%;float:left;margin-right: 20px;">
		                            <label>Full Name :</label>
		                        </div>
		                        <div style="width: 65%;float:left;margin-bottom:5px;">
		                            <div style="width: 20%;">
		                            	<input type="text" class="form-control" id="PRRL_staff_name" name="PRRL_staff_name" value="<?=isset($staff[0]->name)? $staff[0]->name:'' ?>" style="width: 400px;" disabled/>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		                <div class="form-group">
							<div style="width: 100%;">
								<div style="width: 25%;float:left;margin-right: 20px;">
		                            <label>FIN :</label>
		                        </div>
		                        <div style="width: 65%;float:left;margin-bottom:5px;">
		                            <div style="width: 20%;">
		                            	<input type="text" class="form-control" id="PRRL_FIN" name="PRRL_FIN" style="width: 400px;"/>
		                            </div>
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
		                            	<input type="text" class="form-control" id="PRRL_firm" name="PRRL_firm" style="width: 400px;" disabled />
		                            </div>
		                        </div>
		                    </div>
		                </div>
		                <div class="form-group">
							<div style="width: 100%;">
								<div style="width: 25%;float:left;margin-right: 20px;">
		                            <label>Work Over :</label>
		                        </div>
		                        <div style="width: 65%;float:left;margin-bottom:5px;">
		                            <div style="width: 20%;">
		                            	<input type="text" class="form-control" id="PRRL_work_over" name="PRRL_work_over" style="width: 400px;" disabled />
		                            </div>
		                        </div>
		                    </div>
		                </div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn_purple" id="PRRL_Proceed">Proceed</button>
					<input type="button" class="btn btn-default cancel" data-dismiss="modal" name="" value="Cancel">
				</div>
		</div>
	</div>
</div>

<div id="reprimand_letter" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;" data-backdrop="static">
	<div class="modal-dialog" style="width: 1000px !important;">
		<div class="modal-content">
			<header class="panel-heading">
				<h2 class="panel-title"><strong>Reprimand Letter</strong></h2>
			</header>
				<div class="panel-body">
					<div class="col-md-12">
						<input type="text" class="hidden" id="this" />
						<div class="form-group">
							<div style="width: 100%;">
								<div style="width: 25%;float:left;margin-right: 20px;">
		                            <label>Date :</label>
		                        </div>
		                        <div style="width: 65%;float:left;margin-bottom:5px;">
		                            <div style="width: 20%;">
		                            	<input type="text" class="form-control" id="WL_today_date" name="WL_today_date" value="<?php echo date('d F Y');?>" style="width: 400px;" disabled/>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		                <div class="form-group">
							<div style="width: 100%;">
								<div style="width: 25%;float:left;margin-right: 20px;">
		                            <label>Full Name :</label>
		                        </div>
		                        <div style="width: 65%;float:left;margin-bottom:5px;">
		                            <div style="width: 20%;">
		                            	<input type="text" class="form-control" id="WL_staff_name" name="WL_staff_name" value="<?=isset($staff[0]->name)? $staff[0]->name:'' ?>" style="width: 400px;" disabled/>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		                <div class="form-group">
							<div style="width: 100%;">
								<div style="width: 25%;float:left;margin-right: 20px;">
		                            <label>Reason :</label>
		                        </div>
		                        <div style="width: 65%;float:left;margin-bottom:5px;">
		                            <div style="width: 100%;">
		                            	<textarea class="form-control" id="WL_reason" name="WL_reason" style="width: 100%;height: 500px" required="true" placeholder="1. Reason Title A&#10;Reason A Details ...&#10;&#10;2. Reason Title B&#10;Reason B Details ...&#10;&#10;3. Reason Title C&#10;Reason C Details ..." ></textarea>
		                            </div>
		                        </div>
		                    </div>
		                </div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn_purple" id="WL_Proceed">Proceed</button>
					<input type="button" class="btn btn-default cancel" data-dismiss="modal" name="" value="Cancel">
				</div>
		</div>
	</div>
</div>

<div id="open_bank_letter" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;" data-backdrop="static">
	<div class="modal-dialog" style="width: 1000px !important;">
		<div class="modal-content">
			<header class="panel-heading">
				<h2 class="panel-title"><strong>Open Bank Letter</strong></h2>
			</header>
				<div class="panel-body">
					<div class="col-md-12">
						<input type="text" class="hidden" id="this" />
						<div class="form-group">
							<div style="width: 100%;">
								<div style="width: 25%;float:left;margin-right: 20px;">
		                            <label>Date :</label>
		                        </div>
		                        <div style="width: 65%;float:left;margin-bottom:5px;">
		                            <div style="width: 20%;">
		                            	<input type="text" class="form-control" id="OBL_today_date" name="OBL_today_date" value="<?php echo date('d F Y');?>" style="width: 400px;" disabled/>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		                <div class="form-group">
							<div style="width: 100%;">
								<div style="width: 25%;float:left;margin-right: 20px;">
		                            <label>Full Name :</label>
		                        </div>
		                        <div style="width: 65%;float:left;margin-bottom:5px;">
		                            <div style="width: 20%;">
		                            	<input type="text" class="form-control" id="OBL_staff_name" name="OBL_staff_name" value="<?=isset($staff[0]->name)? $staff[0]->name:'' ?>" style="width: 400px;"/>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		                <div class="form-group">
							<div style="width: 100%;">
								<div style="width: 25%;float:left;margin-right: 20px;">
		                            <label>FIN :</label>
		                        </div>
		                        <div style="width: 65%;float:left;margin-bottom:5px;">
		                            <div style="width: 20%;">
		                            	<input type="text" class="form-control" id="OBL_FIN" name="OBL_FIN" style="width: 400px;"/>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		                <div class="form-group">
							<div style="width: 100%;">
								<div style="width: 25%;float:left;margin-right: 20px;">
		                            <label>Residing Address :</label>
		                        </div>
		                        <div style="width: 65%;float:left;margin-bottom:5px;">
		                            <div style="width: 20%;">
		                            	<textarea class="form-control" id="OBL_residing_address" name="OBL_residing_address" style="width: 400px;" disabled><?php echo (isset($staff[0]->address))?$staff[0]->address:'';?></textarea>
		                            </div>
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
											echo form_dropdown('OBL_firm', $firm_list, isset($staff[0]->firm_id)?$staff[0]->firm_id: '', 'id="OBL_firm" class="form-control" style="width: 400px;"');
										?>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		                <div class="form-group">
							<div style="width: 100%;">
								<div style="width: 25%;float:left;margin-right: 20px;">
		                            <label>Date Join :</label>
		                        </div>
		                        <div style="width: 65%;float:left;margin-bottom:5px;">
		                            <div style="width: 20%;">
		                            	<input type="text" class="form-control" id="OBL_date_join" name="OBL_date_join" value="<?=isset($staff[0]->date_joined)? date('d F Y', strtotime($staff[0]->date_joined)):'' ?>" style="width: 400px;" disabled/>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		                <div class="form-group">
							<div style="width: 100%;">
								<div style="width: 25%;float:left;margin-right: 20px;">
		                            <label>Bank :</label>
		                        </div>
		                        <div style="width: 65%;float:left;margin-bottom:5px;">
		                            <div style="width: 20%;">
		                            	<?php echo form_dropdown('bank_info', $bank_info[0],'', 'id="OBL_bank_info" style="width: 400px;"');?>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		                <div class="form-group">
							<div style="width: 100%;">
								<div style="width: 25%;float:left;margin-right: 20px;">
		                            <label>Bank Address :</label>
		                        </div>
		                        <div style="float:left;margin-bottom:5px;">
		                            <div style="width: 20%;">
		                            	<textarea class="form-control" id="OBL_bank_address" name="OBL_bank_address" style="width: 400px;height: 100px;float:left;" disabled></textarea>
		                            </div>
		                        </div>
		                        <div style="float:left;padding-left: 5px;">
                            		<button type="submit" class="btn btn_purple" id="OBL_Edit">Edit</button>
                            		<button type="submit" class="btn btn_purple" id="OBL_Save" style='display:none'>Save</button>
                            	</div>
		                    </div>
		                </div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn_purple" id="OBL_Proceed">Proceed</button>
					<input type="button" class="btn btn-default cancel" data-dismiss="modal" name="" value="Cancel">
				</div>
		</div>
	</div>
</div>

<div id="promotion_progression" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;" data-backdrop="static">
	<div class="modal-dialog" style="width: 1000px !important;">
		<div class="modal-content">
			<header class="panel-heading">
				<h2 class="panel-title"><strong>Promotion & Progression</strong></h2>
			</header>
				<div class="panel-body">
					<div class="col-md-12">
						<input type="text" class="hidden" id="this" />
						<div class="form-group">
							<div style="width: 100%;">
								<div style="width: 25%;float:left;margin-right: 20px;">
		                            <label>Date :</label>
		                        </div>
		                        <div style="width: 65%;float:left;margin-bottom:5px;">
		                            <div style="width: 20%;">
		                            	<input type="text" class="form-control" id="PP_today_date" name="PP_today_date" value="<?php echo date('d F Y');?>" style="width: 400px;" disabled/>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		                <div class="form-group">
							<div style="width: 100%;">
								<div style="width: 25%;float:left;margin-right: 20px;">
		                            <label>Full Name :</label>
		                        </div>
		                        <div style="width: 65%;float:left;margin-bottom:5px;">
		                            <div style="width: 20%;">
		                            	<input type="text" class="form-control" id="PP_staff_name" name="PP_staff_name" value="<?=isset($staff[0]->name)? $staff[0]->name:'' ?>" style="width: 400px;" disabled/>
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
		                            <div style="width: 20%;">
		                            	<?php echo form_dropdown('staff_department', $department_list, isset($staff[0]->department)?$staff[0]->department:'', 'class="form-control staff_department" style="width: 400px;" disabled'); ?>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		                <div class="form-group">
							<div style="width: 100%;">
								<div style="width: 25%;float:left;margin-right: 20px;">
		                            <label>Promoted To (Designation) :</label>
		                        </div>
		                        <div style="width: 65%;float:left;margin-bottom:5px;">
		                            <div style="width: 20%;">
		                            	<div class="charge_out_rate_designation_div_div" style="width: 400px;">
	                                        <div class="charge_out_rate_designation_div" style="width: 400px;">
												<select class="form-control charge_out_rate_designation" id="PP_staff_designation" name="PP_staff_designation" style="width: 400px !important" required>
													<option value="" selected="selected">Please Select Designation</option>
												</select>
	                                        </div>
	                                    </div>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		                <div class="form-group">
							<div style="width: 100%;">
								<div style="width: 25%;float:left;margin-right: 20px;">
		                            <label>Effect Date :</label>
		                        </div>
		                        <div style="width: 65%;float:left;margin-bottom:5px;">
		                            <div style="width: 20%;">
		                            	<input type="text" class="form-control datepicker" id="PP_effect_date" name="PP_effect_date" style="width: 400px;" />
		                            </div>
		                        </div>
		                    </div>
		                </div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn_purple" id="PP_Proceed">Proceed</button>
					<input type="button" class="btn btn-default cancel" data-dismiss="modal" name="" value="Cancel">
				</div>
		</div>
	</div>
</div>

<div id="employment_contract_modal" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;" data-backdrop="static">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    	<div class="modal-header">
          	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          	<span aria-hidden="true">&times;</span>
	    	</button>
          	<h4 class="modal-title">Employment Contract</h4>
          	<input type="hidden" id="EC_today_date" name="EC_today_date" value="<?php echo date('d F Y');?>"/>
        </div>
      	<div class="modal-body"></div>
      	<div class="modal-footer">
        	<button type="submit" class="btn btn_purple" id="EC_Proceed">Proceed</button>
     	</div>
    </div>
  </div>
</div>

<div id="Annex_A_letter" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;" data-backdrop="static">
	<div class="modal-dialog" style="width: 1000px !important;">
		<div class="modal-content">
			<header class="panel-heading">
				<h2 class="panel-title"><strong>Annex A</strong></h2>
			</header>
				<div class="panel-body">
					<div class="col-md-12">
						<input type="text" class="hidden" id="this" />
						<input type="text" class="hidden" id="AA_year" />
						<input type="text" class="hidden" id="AA_month" />
		                <div class="form-group">
							<div style="width: 100%;">
								<div style="width: 25%;float:left;margin-right: 20px;">
		                            <label>Full Name :</label>
		                        </div>
		                        <div style="width: 65%;float:left;margin-bottom:5px;">
		                            <div style="width: 20%;">
		                            	<input type="text" class="form-control" id="AA_staff_name" name="AA_staff_name" value="<?=isset($staff[0]->name)? $staff[0]->name:'' ?>" style="width: 400px;" disabled/>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		                <div class="form-group">
							<div style="width: 100%;">
								<div style="width: 25%;float:left;margin-right: 20px;">
		                            <label>FIN :</label>
		                        </div>
		                        <div style="width: 65%;float:left;margin-bottom:5px;">
		                            <div style="width: 20%;">
		                            	<input type="text" class="form-control" id="AA_FIN" name="AA_FIN" style="width: 400px;"/>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		                <div class="form-group">
							<div style="width: 100%;">
								<div style="width: 25%;float:left;margin-right: 20px;">
		                            <label>Work Over :</label>
		                        </div>
		                        <div style="width: 65%;float:left;margin-bottom:5px;">
		                            <div style="width: 20%;">
		                            	<input type="text" class="form-control" id="AA_work_over" name="AA_work_over" style="width: 400px;" disabled />
		                            </div>
		                        </div>
		                    </div>
		                </div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn_purple" id="AA_Proceed">Proceed</button>
					<input type="button" class="btn btn-default cancel" data-dismiss="modal" name="" value="Cancel">
				</div>
		</div>
	</div>
</div>

<script type="text/javascript">
var base_url = '<?php echo base_url(); ?>';
var event_info = <?php echo json_encode(isset($event_info)?$event_info:FALSE) ?>;
var Admin = <?php echo json_encode(isset($this->data['Admin'])?true:false) ?>;
var Manager = <?php echo json_encode(isset($this->data['Manager'])?true:false) ?>;
var user_id = <?php echo json_encode($this->user_id) ?>;
var employee_id = <?php echo json_encode(isset($staff[0]->id)? $staff[0]->id:'') ?>;

$('.datepicker').datepicker({ format: 'dd MM yyyy' });

	if(event_info)
	{
		$count_event_info = event_info.length - 1;
	}
	else
	{
		$count_event_info = 0;
	}

	if(event_info)
	{
		for(var i = 0; i < event_info.length; i++)
		{
			$a=""; 
			$a += '<form class="tr sort_id" method="post" enctype="multipart/form-data" name="form'+i+'" id="form'+i+'">';
			
			$a += '<div class="hidden"><input type="text" class="form-control employee_id" name="employee_id[]" id="employee_id" value="'+event_info[i]["employee_id"]+'"/></div>';

			$a += '<div class="hidden"><input type="text" class="form-control" name="event_info_id[]" id="event_info_id" value="'+event_info[i]["id"]+'"/></div>';

			$a += '<div class="hidden"><input type="text" class="form-control" name="last_date" id="last_date" value="null"/></div>';
			$a += '<div class="hidden"><input type="text" class="form-control" name="AL" id="AL" value="null"/></div>';
			$a += '<div class="hidden"><input type="text" class="form-control" name="department" id="department" value="null"/></div>';
			$a += '<div class="hidden"><input type="text" class="form-control" name="designation" id="designation" value="null"/></div>';
			$a += '<div class="hidden"><input type="text" class="form-control" name="wp_fin_no" id="wp_fin_no" value="null"/></div>';

			$a += '<div class="td"><div class="input-group date datepicker" data-provide="datepicker"><div class="input-group-addon"><span class="far fa-calendar-alt"></span></div><input type="text" class="form-control eventDate" id="eventDate" name="eventDate[]" disabled="disabled"></div><div id="form_eventDate"></div></div>';

			$a += '<div class="td">';
			$a += '<select onchange=change_event('+i+') class="form-control event" style="width: 100%;" name="event[]" id="event" disabled="disabled"><option value=" ">Select Event</option></select><div id="form_event"></div>';
			$a += '</div>';

			$a += '<div class="td">';
			$a += "<div class='input-group'><span class='file_name_attachment' id='file_name_attachment'></span><input type='hidden' class='hidden_event_attachment' name='hidden_event_attachment' value=''/></div>";
			// $a += "<div class='input-group'><input type='file' style='display:none' id='event_attachment' class='event_attachment' multiple='' name='event_attachment[]' disabled='disabled'><label for='event_attachment' class='btn btn_purple event_attachment' disabled='disabled'>Select Attachment</label><br/><span class='file_name_attachment' id='file_name_attachment'></span><input type='hidden' class='hidden_event_attachment' name='hidden_event_attachment'/></div>";
			$a += '</div>';

			if(Admin || Manager || user_id == 79 || user_id == 62 || user_id == 91 || user_id == 107)
			{
				$a += '<div class="td event_action"><div style="display: inline-block; margin-right: 5px; margin-bottom: 5px;"><button type="button" class="btn btn_purple submit_event_info_button" onclick="edit_event(this);">Edit</button></div><div style="display: inline-block;"><button type="button" class="btn btn_purple" onclick="delete_event_info(this);">Delete</button></div></div>';
			}

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
		                $('#form'+i).find(".event").append(option);
		            });

		            //$('#form'+i).find(".relationship"+i).select2();
		        }
		    });

		    $('#form'+i).find(".eventDate").val(moment(event_info[i]["date"]).format('DD MMMM YYYY'));

			$('#form'+i).find('.datepicker').datepicker({
			    format: 'dd MM yyyy',
			});


			$('#form'+i).find(".hidden_event_attachment").val(event_info[i]["attachment"]);

			filename_proof_of_document = '<a href="'+base_url+'pdf/document/'+event_info[i]["attachment"]+'" target="_blank">'+event_info[i]["attachment"]+'</a>';

			$('#form'+i).find("#file_name_attachment").html(filename_proof_of_document);
		}
	}
	
	
	$(document).on('click',"#event_Add",function() 
	{
		$count_event_info++;
	 	$a=""; 
		$a += '<form class="tr event_editing sort_id" method="post" enctype="multipart/form-data" name="form'+$count_event_info+'" id="form'+$count_event_info+'">';

		$a += '<div class="hidden"><input type="text" class="form-control employee_id" name="employee_id[]" id="employee_id" value=""/></div>';
		$a += '<div class="hidden"><input type="text" class="form-control" name="event_info_id[]" id="event_info_id" value=""/></div>';
		$a += '<div class="hidden"><input type="text" class="form-control" name="last_date" id="last_date" value="null"/></div>';
		$a += '<div class="hidden"><input type="text" class="form-control" name="AL" id="AL" value="null"/></div>';
		$a += '<div class="hidden"><input type="text" class="form-control" name="department" id="department" value="null"/></div>';
		$a += '<div class="hidden"><input type="text" class="form-control" name="designation" id="designation" value="null"/></div>';
		$a += '<div class="hidden"><input type="text" class="form-control" name="wp_fin_no" id="wp_fin_no" value="null"/></div>';

		$a += '<div class="td"><div class="input-group date datepicker" data-provide="datepicker"><div class="input-group-addon"><span class="far fa-calendar-alt"></span></div><input type="text" class="form-control eventDate" id="eventDate" name="eventDate[]" value=""></div><div id="form_eventDate"></div></div>';

		$a += '<div class="td">';
		$a += '<select onchange=change_event('+$count_event_info+') class="form-control event" style="width: 100%;" name="event[]" id="event"><option value=" ">Select Event</option></select><div id="form_event"></div>';
		$a += '</div>';

		$a += '<div class="td">';
		$a += "<div class='input-group'><span class='file_name_attachment' id='file_name_attachment'></span><input type='hidden' class='hidden_event_attachment' name='hidden_event_attachment' value=''/></div>";
		// $a += "<div class='input-group'><input type='file' style='display:none' id='event_attachment' multiple='' name='event_attachment[]'><label for='event_attachment' class='btn btn_purple event_attachment'>Select Attachment</label><br/><span class='file_name_attachment' id='file_name_attachment'></span><input type='hidden' class='hidden_event_attachment' name='hidden_event_attachment' value=''/></div>";
		$a += '</div>';

		if(Admin || Manager || user_id == 79 || user_id == 62 || user_id == 91 || user_id == 107)
		{
			$a += '<div class="td event_action"><div style="display: inline-block; margin-right: 5px; margin-bottom: 5px;"><button type="button" class="btn btn_purple submit_event_info_button" onclick="edit_event(this);">Save</button></div><div style="display: inline-block;"><button type="button" class="btn btn_purple" onclick="delete_event_info(this);">Delete</button></div></div>';
		} 

		$a += '</form>';
		
		$("#body_event_info").prepend($a); 

		$('#form'+$count_event_info).find(".employee_id").val(employee_id);

		var date = new Date();
		var today = new Date(date.getFullYear()+'-'+(date.getMonth()+1)+'-'+date.getDate());
		$('#form'+$count_event_info).find(".eventDate").val(moment(today).format('DD MMMM YYYY'));


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
	                $('#form'+$count_event_info).find(".event").append(option);
	            });

	            //$('#form'+$count_family_info).find(".relationship"+$count_family_info).select2();
	        }
	    });

		$('#form'+$count_event_info).find('.datepicker').datepicker({
		    format: 'dd MM yyyy',
		});
	});

	function change_event(element){
		
		var filename;
		var event_id = $('#form'+element).find("#event").val();

		$("#last_date").val('null');
		$("#designation").val('null');

	    if(event_id == '1')
	    {
	    	$.ajax({
	            type: "POST",
	            url: "<?php echo site_url('action/employmentContract'); ?>",
	            data: {"id":employee_id}, // <--- THIS IS THE CHANGE
	            'async':false,
	            success: function(data)
	            {
	            	if(data)
	            	{
	            		$('.modal-body').empty();
			            $('.modal-body').prepend(data);
			            $('#employment_contract_modal').modal('show');
			            $("#EC_today_date").val($('#form'+element).find(".eventDate").val());
	            	}
	            }               
	        });
	    }
	    else if(event_id == '2')
	    {
	    	$("#probation_pass").modal("show");
	    	$("#this").val(element);
	    	$("#PPL_today_date").val($('#form'+element).find(".eventDate").val());
	    }
	    else if(event_id == '3')
	    {
	    	$("#probation_extension").modal("show");
	    	$("#this").val(element);
	    	$("#EPL_today_date").val($('#form'+element).find(".eventDate").val());
	    }
	    else if(event_id == '4')
	    {
	    	$("#probation_failed").modal("show");
	    	$("#this").val(element);
	    	$("#FPL_today_date").val($('#form'+element).find(".eventDate").val());
	    }
	    else if(event_id == '5')
	    {
	    	$("#reprimand_letter").modal("show");
	    	$("#this").val(element);
	    	$("#WL_today_date").val($('#form'+element).find(".eventDate").val());
	    }
	    else if(event_id == '6')
	    {
	    	$("#OBL_firm").select2();
	    	$("#OBL_bank_info").select2();

	    	$("#open_bank_letter").modal("show");
	    	$("#this").val(element);
	    	$("#OBL_today_date").val($('#form'+element).find(".eventDate").val());

	    	var firm  = '<?php echo isset($staff[0]->firm_id)?$staff[0]->firm_id: ''; ?>';

	    	$.ajax({
	            type: "POST",
	            url: "<?php echo site_url('action/get_wp_fin_no'); ?>",
	            data: {"emp_id":employee_id}, // <--- THIS IS THE CHANGE
	            'async':false,
	            success: function(data)
	            {
	            	if(data)
	            	{
	            		var result = JSON.parse(data);
	            		$('#OBL_FIN').val(result[0]['wp_fin_no']);
	            	}
	            }               
	        });

			$.ajax({
	            type: "POST",
	            url: "<?php echo site_url('action/get_firm_name'); ?>",
	            data: {"firm":firm}, // <--- THIS IS THE CHANGE
	            'async':false,
	            success: function(data)
	            {
	            	if(data)
	            	{
	            		var result = JSON.parse(data);
	            		$('#OBL_firm').val(result[0]['id']).trigger('change');
	            	}
	            }               
	        });
	    }
	    else if(event_id == '7')
	    {
	    	$("#PR_recommendation_letter").modal("show");
	    	$("#this").val(element);
	    	$("#PRRL_today_date").val($('#form'+element).find(".eventDate").val());

	    	var date1 = '<?php echo isset($staff[0]->date_joined)?$staff[0]->date_joined:''; ?>';
	    	var firm  = '<?php echo isset($staff[0]->firm_id)?$staff[0]->firm_id: ''; ?>';

	    	$.ajax({
	            type: "POST",
	            url: "<?php echo site_url('action/get_wp_fin_no'); ?>",
	            data: {"emp_id":employee_id}, // <--- THIS IS THE CHANGE
	            'async':false,
	            success: function(data)
	            {
	            	if(data)
	            	{
	            		var result = JSON.parse(data);
	            		$('#PRRL_FIN').val(result[0]['wp_fin_no']);
	            	}
	            }               
	        });

	    	$.ajax({
	            type: "POST",
	            url: "<?php echo site_url('action/calculate_year_month'); ?>",
	            data: {"date1":date1}, // <--- THIS IS THE CHANGE
	            'async':false,
	            success: function(data)
	            {
	            	$('#PRRL_work_over').val(data);
	            }               
	        });

	        $.ajax({
	            type: "POST",
	            url: "<?php echo site_url('action/get_firm_name'); ?>",
	            data: {"firm":firm}, // <--- THIS IS THE CHANGE
	            'async':false,
	            success: function(data)
	            {
	            	if(data)
	            	{
	            		var result = JSON.parse(data);
	            		$('#PRRL_firm').val(result[0]['name']);
	            	}
	            }               
	        });

	    }
	    else if(event_id == '8')
	    {
	    	$("#employment_termination").modal("show");
	    	$("#this").val(element);
	    	$("#ET_today_date").val($('#form'+element).find(".eventDate").val());
	    }
	    else if(event_id == '9')
	    {
	    	var designation = '<?php echo isset($staff[0]->designation)?$staff[0]->designation:""?>';
	    	var date1       = '<?php echo isset($staff[0]->date_joined)?$staff[0]->date_joined:''; ?>';

	    	$("#recommendation_letter").modal("show");
	    	$("#RL_designation").val(designation);
	    	$("#this").val(element);
	    	$("#RL_today_date").val($('#form'+element).find(".eventDate").val());

	    	$.ajax({
	            type: "POST",
	            url: "<?php echo site_url('action/calculate_year_month'); ?>",
	            data: {"date1":date1}, // <--- THIS IS THE CHANGE
	            'async':false,
	            success: function(data)
	            {
	            	$('#RL_work_over').val(data);
	            }               
	        });
	    }
	  //   else if(event_id == '10')
	  //   {
	  //   	var firm_id = '<?php echo isset($staff[0]->firm_id)?$staff[0]->firm_id:'' ?>';
			// var emp_id = '<?php echo isset($staff[0]->id)?$staff[0]->id:'' ?>';

			// var data = [$('[id=PPL_today_date]').val(), $('[id=PPL_staff_name]').val(), firm_id];

	  //       $.ajax({
	  //           type: "POST",
	  //           url: "<?php echo site_url('employee/declaration_letter'); ?>",
	  //           data: {"data":data}, // <--- THIS IS THE CHANGE
	  //           dataType: "json",
	  //           'async':false,
	  //           success: function(response)
	  //           {
	  //           	window.open(response.link,'_blank');
	  //               filename = response.filename;
	  //           }               
	  //       });
	  //   }
	  	else if(event_id == '11')
	    {
	    	$("#promotion_progression").modal("show");
	    	$("#PP_today_date").val($('#form'+element).find(".eventDate").val());

	    	var department = <?php echo json_encode(isset($staff[0]->department)?$staff[0]->department:"")?>;
    		var designation = <?php echo json_encode(isset($staff[0]->designation)?$staff[0]->designation:"")?>;

	    	$.ajax({
	           	type: "POST",
	           	url: "<?php echo site_url('action/get_designation'); ?>",
	           	data: "&department="+ department,
	           	success: function(data)
	           	{
	           		var result = JSON.parse(data);

	           		$(".charge_out_rate_designation_div").remove();
					
					var dropdown = '<div class="charge_out_rate_designation_div" style="width: 40%;"><select class="form-control charge_out_rate_designation" id="PP_staff_designation" name="PP_staff_designation" style="width: 400px !important" required><option value="" selected="selected">Please Select Designation</option>';

	           		if(result != '')
	           		{
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
	    else if(event_id == '12')
	    {
	    	$("#Annex_A_letter").modal("show");
	    	$("#this").val(element);

	    	var date1 = '<?php echo isset($staff[0]->date_joined)?$staff[0]->date_joined:''; ?>';

	    	$.ajax({
	            type: "POST",
	            url: "<?php echo site_url('action/get_wp_fin_no'); ?>",
	            data: {"emp_id":employee_id}, // <--- THIS IS THE CHANGE
	            'async':false,
	            success: function(data)
	            {
	            	if(data)
	            	{
	            		var result = JSON.parse(data);
	            		$('#AA_FIN').val(result[0]['wp_fin_no']);
	            	}
	            }               
	        });

	    	$.ajax({
	            type: "POST",
	            url: "<?php echo site_url('action/calculate_year_month'); ?>",
	            data: {"date1":date1}, // <--- THIS IS THE CHANGE
	            'async':false,
	            success: function(data)
	            {
	            	$('#AA_work_over').val(data);
	            }               
	        });

	        $.ajax({
	            type: "POST",
	            url: "<?php echo site_url('action/calculate_year_month_InNumber'); ?>",
	            data: {"date1":date1}, // <--- THIS IS THE CHANGE
	            'async':false,
	            success: function(data)
	            {
	            	var result = JSON.parse(data);

	            	$('#AA_year').val(result[0]);
	            	$('#AA_month').val(result[1]);
	            }               
	        });
	    }
	}

	function delete_event_info(element)
	{
		var tr = jQuery(element).parent().parent().parent();

		var event_info_id = tr.find('input[name="event_info_id[]"]').val();

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
					if(event_info_id != undefined)
					{
						$.ajax({ //Upload common input
				            url: "<?php echo site_url('action/delete_event_info'); ?>",
				            type: "POST",
				            data: {"event_info_id": event_info_id},
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

	function edit_event(element)
	{
		var tr = jQuery(element).parent().parent().parent();
		if(!tr.hasClass("event_editing")) 
		{
			tr.addClass("event_editing");
			tr.find("DIV.td").each(function()
			{
				if(!jQuery(this).hasClass("event_action"))
				{
					jQuery(this).find('input[name="eventDate[]"]').attr('disabled', false);
					jQuery(this).find('input[name="event[]"]').attr('disabled', false);
					jQuery(this).find('.event_attachment').attr('disabled', false);
					jQuery(this).find("select").attr('disabled', false);
				} 
				else 
				{
					jQuery(this).find(".submit_event_info_button").text("Save");
				}
			});
		} 
		else 
		{
			var formData = new FormData($(element).closest('form')[0]);
			event_info_submit(formData, tr);
		}
	}

	function event_info_submit(frm_serialized, tr)
	{
		$('#loadingmessage').show();
		$.ajax({ //Upload common input
	        url: "<?php echo site_url('action/add_event_info'); ?>",
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
	            	if(response.insert_event_info_id != null)
	            	{
	            		tr.find('input[name="event_info_id[]"]').attr('value', response.insert_event_info_id);
	            	}
	            	tr.removeClass("event_editing");

					tr.find("DIV.td").each(function(){
						if(!jQuery(this).hasClass("event_action"))
						{
							jQuery(this).find('input[name="eventDate[]"]').attr('disabled', true);
							jQuery(this).find('input[name="event[]"]').attr('disabled', true);
							jQuery(this).find('.event_attachment').attr('disabled', true);
							jQuery(this).find("select").attr('disabled', true);
							
						} 
						else 
						{
							jQuery(this).find(".submit_event_info_button").text("Edit");
						}
					});
				    
	            }
	        }
	    });
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
		else
		{
			$('#loadingmessage').show();
			document.getElementById('EC_Proceed').disabled = "true";

			$.ajax({
	            type: "POST",
	            url: "<?php echo site_url('action/employment_contract_letter'); ?>",
	            data: {"data":data}, // <--- THIS IS THE CHANGE
	            dataType: "json",
	            'async':false,
	            success: function(response)
	            {
	            	window.open(response.link,'_blank');
	                filename = response.filename;

	                $('#loadingmessage').hide();
	                document.getElementById('EC_Proceed').removeAttribute('disabled');
	            }               
	        });

	    	$('#form'+$count_event_info).find(".file_name_attachment").html('<a href="'+base_url+'/pdf/document/'+filename+'" target="_blank">'+filename+'</a>');

	    	$('#form'+$count_event_info).find(".hidden_event_attachment").val(filename);

	    	$("#employment_contract_modal").modal("hide");

	    	$("#AL").val($('[id=ol_vacation_leave]').val());
	    	$("#department").val($('[id=EC_staff_department]').val());
	    	$("#designation").val($('[id=EC_designation]').val());
		}

	});

	$(document).on('click','[id=PPL_Proceed]',function(){

		var firm_id = '<?php echo isset($staff[0]->firm_id)?$staff[0]->firm_id:'' ?>';

		var data = [$('[id=PPL_today_date]').val(),
					$('[id=PPL_staff_name]').val(),
					firm_id];

		$.ajax({
            type: "POST",
            url: "<?php echo site_url('action/probation_passed_letter'); ?>",
            data: {"data":data}, // <--- THIS IS THE CHANGE
            dataType: "json",
            'async':false,
            success: function(response)
            {
            	window.open(response.link,'_blank');
                filename = response.filename;
            }               
        });

    	$('#form'+$count_event_info).find(".file_name_attachment").html('<a href="'+base_url+'/pdf/document/'+filename+'" target="_blank">'+filename+'</a>');

    	$('#form'+$count_event_info).find(".hidden_event_attachment").val(filename);

    	$("#probation_pass").modal("hide");

    	$("#last_date").val($('[id=PPL_today_date]').val());

	});

	var bank_details = '<?php echo json_encode(isset($bank_info[1])?$bank_info[1]:"") ?>';
	bank_details = bank_details.split("\n").join('<nl>');
	bank_details = JSON.parse(bank_details);

	$(document).on('change','[id=OBL_bank_info]',function(){

		var bank_selected = $('[id=OBL_bank_info]').val();

		if(bank_details[bank_selected] == undefined)
		{
			$('[id=OBL_bank_address]').val('');
			document.getElementById('OBL_bank_address').disabled = "true";
			$("#OBL_Save").hide();
		}
		else if (bank_details[bank_selected] == '')
		{
			$('[id=OBL_bank_address]').val('');
			document.getElementById('OBL_bank_address').disabled = "true";
			$("#OBL_Save").hide();
		}
		else
		{
			bank_details[bank_selected] = bank_details[bank_selected].split("<nl>").join('\n');
			$('[id=OBL_bank_address]').val(bank_details[bank_selected]);
			document.getElementById('OBL_bank_address').disabled = "true";
			$("#OBL_Save").hide();
		}

	});

	$(document).on('click','[id=OBL_Edit]',function()
	{
		document.getElementById('OBL_bank_address').removeAttribute('disabled');
		$("#OBL_Save").show();
	});

	$(document).on('click','[id=OBL_Save]',function()
	{
		var address = $('[id=OBL_bank_address]').val();
		var id = $('[id=OBL_bank_info]').val();

		$.ajax({
            type: "POST",
            url: "<?php echo site_url('action/save_open_bank_address'); ?>",
            data: {"id":id,"address":address}, // <--- THIS IS THE CHANGE
            'async':false,
            success: function(data)
            {
            	document.getElementById('OBL_bank_address').disabled = "true";
            	$("#OBL_Save").hide();
            	bank_details[id] = address;
            	toastr.success('Information Updated', 'Success');
            }               
        });
	});

	$(document).on('click','[id=OBL_Proceed]',function(){

		var firm_id = '<?php echo isset($staff[0]->firm_id)?$staff[0]->firm_id:'' ?>';
		var gender  = '<?php echo isset($staff[0]->gender)?$staff[0]->gender:""?>';

		var data = [$('[id=OBL_today_date]').val(),
					$('[id=OBL_staff_name]').val(),
					$('[id=OBL_FIN]').val(),
					$('[id=OBL_residing_address]').val(),
					$("[id=OBL_firm] option:selected").text(),
					$('[id=OBL_date_join]').val(),
					$('[id=OBL_bank_info]').val(),
					$('[id=OBL_bank_address]').val(),
					$("[id=OBL_firm] option:selected").val(),
					gender];

		if($('[id=OBL_FIN]').val() == '')
		{
			toastr.error('Please Fill In The FIN', 'Error');
		}
		else if($('[id=OBL_bank_address]').val() == '')
		{
			toastr.error('Please Provide The Bank Address', 'Error');
		}
		else
		{
			$.ajax({
	            type: "POST",
	            url: "<?php echo site_url('action/open_bank_letter'); ?>",
	            data: {"data":data}, // <--- THIS IS THE CHANGE
	            dataType: "json",
	            'async':false,
	            success: function(response)
	            {
	            	window.open(response.link,'_blank');
	                filename = response.filename;
	            }               
	        });

	    	$('#form'+$count_event_info).find(".file_name_attachment").html('<a href="'+base_url+'/pdf/document/'+filename+'" target="_blank">'+filename+'</a>');

	    	$('#form'+$count_event_info).find(".hidden_event_attachment").val(filename);

	    	$("#open_bank_letter").modal("hide");

	    	$("#wp_fin_no").val($('[id=OBL_FIN]').val());
		}

	});

	$(document).on('change','[id=EPL_extend_period]',function(){

		var join_date = '<?php echo isset($staff[0]->date_joined)?$staff[0]->date_joined:''; ?>';

		var extension = $('[id=EPL_extend_period]').val();

		$.ajax({
            type: "POST",
            url: "<?php echo site_url('action/calculate_extend_date'); ?>",
            data: {"join_date":join_date, "extension":extension}, // <--- THIS IS THE CHANGE
            'async':false,
            success: function(data)
            {
            	$('[id=EPL_extend_to]').val(data);
            }               
        });

	});

	$(document).on('click','[id=EPL_Proceed]',function(){

		var firm_id = '<?php echo isset($staff[0]->firm_id)?$staff[0]->firm_id:'' ?>';

		var data = [$('[id=EPL_today_date]').val(),
					$('[id=EPL_staff_name]').val(),
					$('[id=EPL_probation_start]').val(),
					$('[id=EPL_extend_period]').val(),
					$('[id=EPL_extend_to]').val(),
					$('[id=EPL_reason]').val(),
					firm_id];

		if($('[id=EPL_extend_period]').val() == '0')
		{
			toastr.error('Please Fill In The Extend Period', 'Error');
		}
		else if($('[id=EPL_reason]').val() == '')
		{
			toastr.error('Please Provide The Reason', 'Error');
		}
		else
		{
			$.ajax({
	            type: "POST",
	            url: "<?php echo site_url('action/probation_extended_letter'); ?>",
	            data: {"data":data}, // <--- THIS IS THE CHANGE
	            dataType: "json",
	            'async':false,
	            success: function(response)
	            {
	            	window.open(response.link,'_blank');
	                filename = response.filename;
	            }               
	        });

	    	$('#form'+$count_event_info).find(".file_name_attachment").html('<a href="'+base_url+'/pdf/document/'+filename+'" target="_blank">'+filename+'</a>');

	    	$('#form'+$count_event_info).find(".hidden_event_attachment").val(filename);

	    	$("#probation_extension").modal("hide");
		}

	});

	$(document).on('click','[id=FPL_Proceed]',function(){

		var firm_id = '<?php echo isset($staff[0]->firm_id)?$staff[0]->firm_id:'' ?>';

		var data = [$('[id=FPL_today_date]').val(),
					$('[id=FPL_staff_name]').val(),
					$('[id=FPL_end_on]').val(),
					$('[id=FPL_reason]').val(),
					firm_id];

		if($('[id=FPL_end_on]').val() == '')
		{
			toastr.error('Please Fill In The End Date', 'Error');
		}
		else if($('[id=FPL_reason]').val() == '')
		{
			toastr.error('Please Provide The Reason', 'Error');
		}
		else
		{
			$.ajax({
	            type: "POST",
	            url: "<?php echo site_url('action/probation_failed_letter'); ?>",
	            data: {"data":data}, // <--- THIS IS THE CHANGE
	            dataType: "json",
	            'async':false,
	            success: function(response)
	            {
	            	window.open(response.link,'_blank');
	                filename = response.filename;
	            }               
	        });

	    	$('#form'+$count_event_info).find(".file_name_attachment").html('<a href="'+base_url+'/pdf/document/'+filename+'" target="_blank">'+filename+'</a>');

	    	$('#form'+$count_event_info).find(".hidden_event_attachment").val(filename);

	    	$("#probation_failed").modal("hide");

	    	$("#last_date").val($('[id=FPL_end_on]').val());
		}

	});

	$(document).on('click','[id=ET_Proceed]',function(){

		var firm_id = '<?php echo isset($staff[0]->firm_id)?$staff[0]->firm_id:'' ?>';

		var data = [$('[id=ET_today_date]').val(),
					$('[id=ET_staff_name]').val(),
					$('[id=ET_end_on]').val(),
					$('[id=ET_reason]').val(),
					firm_id];

		if($('[id=ET_end_on]').val() == '')
		{
			toastr.error('Please Fill In The End Date', 'Error');
		}
		else if($('[id=ET_reason]').val() == '')
		{
			toastr.error('Please Provide The Reason', 'Error');
		}
		else
		{
			$.ajax({
	            type: "POST",
	            url: "<?php echo site_url('action/employment_termination_letter'); ?>",
	            data: {"data":data}, // <--- THIS IS THE CHANGE
	            dataType: "json",
	            'async':false,
	            success: function(response)
	            {
	            	window.open(response.link,'_blank');
	                filename = response.filename;
	            }               
	        });

	    	$('#form'+$count_event_info).find(".file_name_attachment").html('<a href="'+base_url+'/pdf/document/'+filename+'" target="_blank">'+filename+'</a>');

	    	$('#form'+$count_event_info).find(".hidden_event_attachment").val(filename);

	    	$("#employment_termination").modal("hide");

	    	$("#last_date").val($('[id=ET_end_on]').val());
		}

	});

	$(document).on('click','[id=RL_Proceed]',function(){

		var firm_id = '<?php echo isset($staff[0]->firm_id)?$staff[0]->firm_id:'' ?>';
		var gender  = '<?php echo isset($staff[0]->gender)?$staff[0]->gender:""?>';

		var data = [$('[id=RL_today_date]').val(),
					$('[id=RL_staff_name]').val(),
					$('[id=RL_designation]').val(),
					$('[id=RL_work_over]').val(),
					firm_id,
					gender];

		$.ajax({
            type: "POST",
            url: "<?php echo site_url('action/recommendation_letter'); ?>",
            data: {"data":data}, // <--- THIS IS THE CHANGE
            dataType: "json",
            'async':false,
            success: function(response)
            {
            	window.open(response.link,'_blank');
                filename = response.filename;
            }               
        });

    	$('#form'+$count_event_info).find(".file_name_attachment").html('<a href="'+base_url+'/pdf/document/'+filename+'" target="_blank">'+filename+'</a>');

    	$('#form'+$count_event_info).find(".hidden_event_attachment").val(filename);

    	$("#recommendation_letter").modal("hide");

	});

	$(document).on('click','[id=PRRL_Proceed]',function(){

		var firm_id = '<?php echo isset($staff[0]->firm_id)?$staff[0]->firm_id:'' ?>';

		var data = [$('[id=PRRL_today_date]').val(),
					$('[id=PRRL_staff_name]').val(),
					$('[id=PRRL_FIN]').val(),
					$('[id=PRRL_firm]').val(),
					$('[id=PRRL_work_over]').val(),
					firm_id];

		if($('[id=PRRL_FIN]').val() == '')
		{
			toastr.error('Please Fill In The FIN', 'Error');
		}
		else
		{
			$.ajax({
	            type: "POST",
	            url: "<?php echo site_url('action/PR_recommendation_letter'); ?>",
	            data: {"data":data}, // <--- THIS IS THE CHANGE
	            dataType: "json",
	            'async':false,
	            success: function(response)
	            {
	            	window.open(response.link,'_blank');
	                filename = response.filename;
	            }               
	        });

	    	$('#form'+$count_event_info).find(".file_name_attachment").html('<a href="'+base_url+'/pdf/document/'+filename+'" target="_blank">'+filename+'</a>');

	    	$('#form'+$count_event_info).find(".hidden_event_attachment").val(filename);

	    	$("#PR_recommendation_letter").modal("hide");

	    	$("#wp_fin_no").val($('[id=PRRL_FIN]').val());
	    }

	});

	$(document).on('click','[id=WL_Proceed]',function(){

		var firm_id = '<?php echo isset($staff[0]->firm_id)?$staff[0]->firm_id:'' ?>';
		var emp_id = '<?php echo isset($staff[0]->id)?$staff[0]->id:'' ?>';

		var data = [$('[id=WL_today_date]').val(),
					$('[id=WL_staff_name]').val(),
					$('[id=WL_reason]').val(),
					firm_id,
					emp_id];

		if($('[id=WL_reason]').val() == '')
		{
			toastr.error('Please Fill In The Reason', 'Error');
		}
		else
		{
			$.ajax({
	            type: "POST",
	            url: "<?php echo site_url('action/reprimand_letter'); ?>",
	            data: {"data":data}, // <--- THIS IS THE CHANGE
	            dataType: "json",
	            'async':false,
	            success: function(response)
	            {
	            	window.open(response.link,'_blank');
	                filename = response.filename;
	            }               
	        });

	    	$('#form'+$count_event_info).find(".file_name_attachment").html('<a href="'+base_url+'/pdf/document/'+filename+'" target="_blank">'+filename+'</a>');

	    	$('#form'+$count_event_info).find(".hidden_event_attachment").val(filename);

	    	$("#reprimand_letter").modal("hide");
	    }

	});

	$(document).on('click','[id=PP_Proceed]',function(){

		// var firm_id = '<?php echo isset($staff[0]->firm_id)?$staff[0]->firm_id:'' ?>';
		// var emp_id = '<?php echo isset($staff[0]->id)?$staff[0]->id:'' ?>';

		// var data = [$('[id=WL_today_date]').val(),
		// 			$('[id=WL_staff_name]').val(),
		// 			$('[id=WL_reason]').val(),
		// 			firm_id,
		// 			emp_id];

		// if($('[id=WL_reason]').val() == '')
		// {
		// 	toastr.error('Please Fill In The Reason', 'Error');
		// }
		// else
		// {
		// 	$.ajax({
	 //            type: "POST",
	 //            url: "<?php echo site_url('action/reprimand_letter'); ?>",
	 //            data: {"data":data}, // <--- THIS IS THE CHANGE
	 //            dataType: "json",
	 //            'async':false,
	 //            success: function(response)
	 //            {
	 //            	window.open(response.link,'_blank');
	 //                filename = response.filename;
	 //            }               
	 //        });

	 //    	$('#form'+$count_event_info).find(".file_name_attachment").html('<a href="'+base_url+'/pdf/document/'+filename+'" target="_blank">'+filename+'</a>');

	 //    	$('#form'+$count_event_info).find(".hidden_event_attachment").val(filename);

	 //    	$("#reprimand_letter").modal("hide");
	 //    }

	 	var firm_id = '<?php echo isset($staff[0]->firm_id)?$staff[0]->firm_id:'' ?>';
	 	var emp_id   = '<?php echo isset($staff[0]->id)?$staff[0]->id:'' ?>';
	 	var emp_name = '<?=isset($staff[0]->name)? $staff[0]->name:'' ?>';

	 	var data = [$('[id=PP_today_date]').val(),
	 				$('[id=PP_staff_designation]').val(),
					$('[id=PP_effect_date]').val(),
					emp_name,
					firm_id];

		if($('[id=PP_staff_designation]').val() == '')
		{
			toastr.error('Please Select The Designation', 'Error');
		}
		else if($('[id=PP_effect_date]').val() == '')
		{
			toastr.error('Please Fill In The Effect Date', 'Error');
		}
		else
		{
			// console.log($('[id=PP_staff_designation]').val());
			// console.log($('[id=PP_effect_date]').val());
			$.ajax({
	            type: "POST",
	            url: "<?php echo site_url('action/promotion_progression'); ?>",
	            data: {"data":data}, // <--- THIS IS THE CHANGE
	            dataType: "json",
	            'async':false,
	            success: function(response)
	            {
	            	window.open(response.link,'_blank');
	                filename = response.filename;
	            }               
	        });

	    	$('#form'+$count_event_info).find(".file_name_attachment").html('<a href="'+base_url+'/pdf/document/'+filename+'" target="_blank">'+filename+'</a>');

	    	$('#form'+$count_event_info).find(".hidden_event_attachment").val(filename);

	    	$("#promotion_progression").modal("hide");
		}

		$("#designation").val($('[id=PP_staff_designation]').val());

	});

	$(document).on('click','[id=AA_Proceed]',function(){

		var data = [$('[id=AA_staff_name]').val(),
					$('[id=AA_FIN]').val(),
					$('#AA_year').val(),
	        		$('#AA_month').val()];

		if($('[id=AA_FIN]').val() == '')
		{
			toastr.error('Please Fill In The FIN', 'Error');
		}
		else
		{
			$.ajax({
	            type: "POST",
	            url: "<?php echo site_url('action/Annex_A_Letter'); ?>",
	            data: {"data":data}, // <--- THIS IS THE CHANGE
	            dataType: "json",
	            'async':false,
	            success: function(response)
	            {
	            	window.open(response.link,'_blank');
	                filename = response.filename;
	            }               
	        });

	    	$('#form'+$count_event_info).find(".file_name_attachment").html('<a href="'+base_url+'/pdf/document/'+filename+'" target="_blank">'+filename+'</a>');

	    	$('#form'+$count_event_info).find(".hidden_event_attachment").val(filename);

	    	$("#Annex_A_letter").modal("hide");

	    	$("#wp_fin_no").val($('[id=AA_FIN]').val());
	    }
	});

</script>  