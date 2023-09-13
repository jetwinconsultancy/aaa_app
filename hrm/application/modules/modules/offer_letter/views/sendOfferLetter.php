<script src="<?= base_url() ?>node_modules/bootstrap-datepicker/dist/js/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css" />
<link rel="stylesheet" href="<?= base_url() ?>node_modules/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.css" />
<script src="<?= base_url() ?>node_modules/bootstrap-switch/dist/js/bootstrap-switch.js"></script>
<script src="<?= base_url() ?>plugin/jQuery-Mask-Plugin-master/dist/jquery.mask.min.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>application/css/plugin/toastr.min.css" />
<script src="<?= base_url() ?>application/js/toastr.min.js"></script>
<style>
	.toggle {
		margin:0px;
	}
</style>

<?php if(!empty($employee_data[0]->id)){ ?>
<div style="text-align: right; display:none" id="btn_action">
	<!-- <a class="btn btn_purple" style="cursor:pointer; margin-bottom: 10px;" onclick="preview_offer_letter('<?=isset($employee_data[0]->employee_applicant_id)?$employee_data[0]->employee_applicant_id:'' ?>')">Preview</a> -->

	<input type="button" style="cursor:pointer; margin-bottom: 10px;" class="btn btn_purple" value="Preview" onclick="preview_offer_letter('<?=isset($employee_data[0]->employee_applicant_id)?$employee_data[0]->employee_applicant_id:'' ?>','<?=isset($employee_data[0]->id)?$employee_data[0]->id: '' ?>')"/>
	<!-- <a class="btn btn-danger" style="cursor:pointer;">Send Now</a> -->
</div>
<?php } ?>

<div class="box">
	<div class="box-content">
	    <div class="row">
	        <div class="col-lg-12">
	            <!-- <?php echo form_open_multipart('Interview/create_applicant', array('id' => 'firm_form', 'enctype' => "multipart/form-data")); ?> -->
	            
	            	<input type="hidden" name="offer_letter_id" value="<?=isset($employee_data[0]->id)?$employee_data[0]->id:'' ?>">
	            	<input type="hidden" name="applicant_id" value="<?=isset($employee_data[0]->applicant_id)?$employee_data[0]->applicant_id:'' ?>">
	            	<input type="hidden" name="employee_id" value="<?=isset($employee_data[0]->employee_id)?$employee_data[0]->employee_id:'' ?>">

		            <div class="row">
		                <div class="col-md-12">
		                    <div class="col-md-12">
		                    	<div class="form-group">
	                                <div style="width: 100%;">
	                                    <div style="width: 30%;float:left;margin-right: 20px;">
	                                        <label>Employee name :</label>
	                                    </div>
	                                    <div style="width: 60%;float:left;margin-bottom:5px;">
	                                        <div class="input-group">
	                                        	<label style="width:100%;"><?php echo isset($employee_data[0]->name)?$employee_data[0]->name:'' ?></label>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>

	                            <div class="form-group">
	                                <div style="width: 100%;">
	                                    <div style="width: 30%;float:left;margin-right: 20px;">
	                                        <label>NRIC / Passport :</label>
	                                    </div>
	                                    <div style="width: 60%;float:left;margin-bottom:5px;">
	                                        <div class="input-group">
	                                        	<label style="width:100px;"><?php echo isset($employee_data[0]->ic_passport_no)?$employee_data[0]->ic_passport_no:'' ?></label>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>

	                            <div class="form-group">
			                    	<div style="width: 100%;">
				                    	<div style="width: 30%;float:left;margin-right: 20px;">
				                            <label>Company name :</label>
				                        </div>
				                    	<div style="width: 60%;float: left;">
				                    		<label>
				                    			<?php echo isset($employee_data[0]->company_name)?$employee_data[0]->company_name :'' ?>
				                    		</label>
                							<!-- <input type="text" class="form-control" name="ol_company"> -->
				                        </div>
				                    </div>
			                    </div>

			                    <!-- <?php 
									// if(!$new_employee){
									// 	echo 
									// 	'<div class="form-group">
									// 		<div style="width: 100%;">
									// 			<div style="width: 25%;float:left;margin-right: 20px;">
									// 				<label>Pass given :</label>
									// 			</div>
									// 			<div style="width: 65%;float: left;">'.
									// 				form_dropdown('ol_employee_pass', $employment_type, isset($employee_data[0]->workpass)?$employee_data[0]->workpass:'', 'class="form-control" disabled')
									// 			.'</div>
									// 		</div>
									// 	</div>';
									// } else{
									// 	echo
									// 	'<div class="form-group">
									// 		<div style="width: 100%;">
									// 			<div style="width: 25%;float:left;margin-right: 20px;">
									// 				<label>PR/Singaporean :</label>
									// 			</div>
									// 			<div style="width: 65%;float: left;">
									// 				<div>
									// 					<input type="checkbox" name="ol_is_pr_singaporean" />
									// 					<input type="hidden" name="hidden_ol_is_pr_singaporean" value="'. $is_pr_singaporean .'"/>
									// 				</div>
									// 			</div>
									// 		</div>
									// 	</div>';
									// }
	                            ?> -->

	                            <div class="form-group">
	                                <div style="width: 100%;">
	                                    <div style="width: 30%;float:left;margin-right: 20px;">
	                                        <label>Department :</label>
	                                    </div>
	                                    <div style="width: 60%;float:left;margin-bottom:5px;">
							                <?php 
                                        		echo form_dropdown('staff_department', $department_list, isset($employee_data[0]->department)?$employee_data[0]->department:'', 'class="form-control staff_department general"');
                                        	?>
	                                    </div>
	                                </div>
	                            </div>

	                            <div class="form-group">
	                                <div style="width: 100%;">
	                                    <div style="width: 30%;float:left;margin-right: 20px;">
	                                        <label>Designation :</label>
	                                    </div>
	                                    <div class="charge_out_rate_designation_div_div" style="width: 60%;float:left;margin-bottom:5px;">
	                                        <div class="charge_out_rate_designation_div">
	                                        	<!-- <input type="text" class="form-control" id="staff_designation" name="staff_designation" value="<?=isset($staff[0]->designation)?$staff[0]->designation:'' ?>" style="width: 400px;"/> -->
												<select class="form-control charge_out_rate_designation" id="staff_designation" name="staff_designation" style="width: 100% !important" required>
													<option value="" selected="selected">Please Select Designation</option>
												</select>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>

	                            <div class="form-group">
	                                <div style="width: 100%;">
	                                    <div style="width: 30%;float:left;margin-right: 20px;">
	                                        <label>Job title :</label>
	                                    </div>
	                                    <div style="width: 60%;float:left;margin-bottom:5px;">
							                <input type="text" class="form-control" id="ol_job_title" name="ol_job_title" data-date-format="dd-mm-yyyy" value="<?=isset($employee_data[0]->job_title)?$employee_data[0]->job_title:'' ?>" readonly>
	                                    </div>
	                                </div>
	                            </div>

	                            <div class="form-group">
	                                <div style="width: 100%;">
	                                    <div style="width: 30%;float:left;margin-right: 20px;">
	                                        <!-- <label>Effective from :</label> -->
	                                        <label>Date of commencement :</label>
	                                    </div>
	                                    <div style="width: 60%;float:left;margin-bottom:5px;">
							                <div class="input-group date datepicker">
							                	<div class="input-group-addon">
    										        <span class="far fa-calendar-alt"></span>
    										    </div>
							                	<input type="text" class="form-control" id="ol_effective_from" name="ol_effective_from" data-date-format="dd-mm-yyyy" value="<?=isset($employee_data[0]->effective_from)?date('d F Y', strtotime($employee_data[0]->effective_from)):'' ?>" required >
    										</div>
	                                    </div>
	                                </div>
	                            </div>

	                            <div class="form-group">
	                                <div style="width: 100%;">
	                                    <div style="width: 30%;float:left;margin-right: 20px;">
	                                        <label>Hours of work :</label>
	                                    </div>
	                                    <div style="width: 60%;float:left;margin-bottom:5px;">
							                <input type="text" class="form-control" id="ol_work_hour" name="ol_work_hour" value="<?=isset($employee_data[0]->effective_from)?$employee_data[0]->work_hour:'' ?>" style="width: 50px;float:left;"/>
							                <p style="float:left;padding: 8px;">hours a week</p>
	                                    </div>
	                                </div>
	                            </div>

	                            <div class="form-group">
	                                <div style="width: 100%;">
	                                    <div style="width: 30%;float:left;margin-right: 20px;">
	                                        <label>Vacation leave :</label>
	                                    </div>
	                                    <div style="width: 60%;float:left;margin-bottom:5px;">
							                <input type="text" class="form-control" id="ol_vacation_leave" name="ol_vacation_leave" value="<?=isset($employee_data[0]->effective_from)?$employee_data[0]->vacation_leave:'' ?>" style="width: 50px;float:left;"/>
							                <p style="float:left;padding: 8px;">days per annum</p>
	                                    </div>
	                                </div>
	                            </div>

								<div class="form-group salary-group">
									<div style="width: 100%;">
										<div style="width: 30%;float:left;margin-right: 20px;">
											<label>Salary :</label>
										</div>
										<div style="width: 60%;float:left;margin-bottom:5px;">
											<div style="width: 200px; margin-bottom:5px;">
												<?php
													echo form_dropdown('salary_form_currency', $currency_list, isset($employee_data[0]->salary_currency)?$employee_data[0]->salary_currency: '', 'class="salary_form_currency currency-select general" required');
												?>
											</div>
											<input type="text" class="form-control" id="new_salary" name="new_salary" value="<?=isset($employee_data[0]->salary)?$employee_data[0]->salary:'' ?>" style="width: 200px;float:left;" required/>
										</div>
									</div>
								</div>

								<div class="form-group">
									<div style="width: 100%;">
										<div style="width: 30%;float:left;margin-right: 20px;">
											<label>Bond :</label>
										</div>
										<div style="width: 250px;float:left;margin-bottom:10px;">
											<div>
												<input type="checkbox" id="bond" name="bond"/>
												<input type="hidden" name="hidden_bond" value="0"/>
											</div>
										</div>
									</div>
								</div>

								<div class="form-group bond_group" style="display:none">
									<div style="width: 100%;">
										<div style="width: 30%;float:left;margin-right: 20px;">
											<label>Bond Period :</label>
										</div>
										<div style="width: 60%;float:left;margin-bottom:5px;">
											<input type="text" class="form-control" id="bond_period" name="bond_period" value="<?=isset($employee_data[0]->bond_period)?$employee_data[0]->bond_period:'' ?>" style="width: 80px;float:left;"/>
											<p style="float:left;padding: 8px;">month(s)</p>
										</div>
									</div>
								</div>

								<div class="form-group bond_group" style="display:none">
									<div style="width: 100%;">
										<div style="width: 30%;float:left;margin-right: 20px;">
											<label>Bond Allowance :</label>
										</div>
										<div style="width: 60%;float:left;margin-bottom:5px;">
											<div style="width: 200px; margin-bottom:5px;">
												<?php
													echo form_dropdown('bond_form_currency', $currency_list, isset($employee_data[0]->bond_currency)?$employee_data[0]->bond_currency: '', 'class="bond_form_currency currency-select general" "');
												?>
											</div>
											<input type="text" class="form-control" id="bond_allowance" name="bond_allowance" value="<?=isset($employee_data[0]->bond_allowance)?$employee_data[0]->bond_allowance:'' ?>" style="width: 200px;float:left;"/>
										</div>
									</div>
								</div>

			                </div>
			            </div>
			        </div>
		    </div>
		</div>
	</div>
</div>

<script type="text/javascript" src="<?php echo base_url() . 'application/js/number_input_format.js' ?>"></script>

<script type="text/javascript">

	var offer_letter_id = <?php echo isset($employee_data[0]->id)?$employee_data[0]->id: 'null'; ?>;
	var bond = <?php echo isset($employee_data[0]->bond)?$employee_data[0]->bond: 0; ?>;

	$('.salary_form_currency').select2();

	$("[name='bond']").bootstrapSwitch({
		state: 0,
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
		disabled:false,
	});

	if(bond) {
		$("[name='bond']").bootstrapSwitch('state', true);
		$("[name='hidden_bond']").val(1);
		$(".bond_group").show();
		$("#bond_period").attr("required", "true");
		$(".bond_form_currency").attr("required", "true");
		$("#bond_allowance").attr("required", "true");
	}

	$("[name='bond']").on('switchChange.bootstrapSwitch', function(event, state) {
		if(state == true)
	    {
			$("[name='hidden_bond']").val(1);
			$(".bond_group").show();
			$("#bond_period").attr("required", "true");
			$(".bond_form_currency").attr("required", "true");
			$("#bond_allowance").attr("required", "true");
		}
		else
		{
			$("[name='hidden_bond']").val(0);
			$(".bond_group").hide();
			$('#bond_period').removeAttr('required');
			$('.bond_form_currency').removeAttr('required');
			$('#bond_allowance').removeAttr('required');
		}
	});

	$('.bond_form_currency').select2();


	if(offer_letter_id != null){
		$("#btn_action").show();
	}

	$('.datepicker').datepicker({
		format: 'dd MM yyyy',
	});

	toastr.options = {
	  "positionClass": "toast-bottom-right"
	}

	$("[name='ol_is_pr_singaporean']").bootstrapSwitch({
	    state: <?php echo isset($is_pr_singaporean)?$is_pr_singaporean: 0; ?>,
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

	$("[name='ol_is_pr_singaporean']").on('switchChange.bootstrapSwitch', function(event, state) {
		if(state == true)
	    {
			$("[name='hidden_ol_is_pr_singaporean']").val(1);
		}
		else
		{
			$("[name='hidden_ol_is_pr_singaporean']").val(0);
		}
	})

	// $('form#offer_letter').submit(function(e) {
	//     var form = $(this);
	//     e.preventDefault();
	//     $.ajax({
	//         type: "POST",
	//         url: "<?php echo site_url('offer_letter/save_offer_letter'); ?>",
	//         data: form.serialize(),
	//         dataType: "html",
	//         success: function(data){
	//             // $('#feed-container').prepend(data);
	//             $('input[name=offer_letter_id]').val(data);
	//             $('#offer_letter_modal').modal('hide');

	//             toastr.success('Information Updated', 'Updated');
	//         },
	//         // error: function() { alert("Error posting feed."); }
	//     });
	// });

	$(document).ready(function () {
    	$('.staff_department option[value=7]').remove();
    	$('.staff_office option[value=1]').remove();

    	var department = <?php echo json_encode(isset($employee_data[0]->department)?$employee_data[0]->department:"")?>;
    	var designation = <?php echo json_encode(isset($employee_data[0]->designation)?$employee_data[0]->designation:"")?>;

    	$.ajax({
           type: "POST",
           url: "<?php echo site_url('employee/get_designation'); ?>",
           data: "&department="+ department,
           success: function(data)
           {
           		var result = JSON.parse(data);

           		$(".charge_out_rate_designation_div").remove();
				
				var dropdown = '<div class="charge_out_rate_designation_div"><select class="form-control charge_out_rate_designation general" id="staff_designation" name="staff_designation" style="width: 100% !important" required><option value="" selected="selected">Please Select Designation</option>';

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
		       		$(".charge_out_rate_designation").val(designation);
		       		// $(".designation").val("");
		       	}
           }
       	});
    });

    $(document).on('change', '.staff_department', function() {

			var form = $(this);
        
	        var department = $(".staff_department").val();

	        if(department == ''){
	        	$(".charge_out_rate_designation_div").remove();

			    var dropdown = '<div class="charge_out_rate_designation_div"><select class="form-control charge_out_rate_designation" id="staff_designation" name="staff_designation" style="width: 100% !important" required><option value="" selected="selected">Please Select Designation</option></select></div>';

			    $(".charge_out_rate_designation_div_div").append(dropdown);

			    $("#ol_job_title").val('');
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
						
						var dropdown = '<div class="charge_out_rate_designation_div"><select class="form-control charge_out_rate_designation" id="staff_designation" name="staff_designation" style="width: 100% !important" required><option value="" selected="selected">Please Select Designation</option>';

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


    $(document).on('change', '.charge_out_rate_designation', function() {

    	var department = $(".staff_department").val();
    	var department_selected = '';
    	var designation_selected = $("#staff_designation").val();

    	$.ajax({
           type: "POST",
           url: "<?php echo site_url('offer_letter/get_departmentName'); ?>",
           data: "&department="+ department,
           'async':false,
           success: function(data)
           {	
           		if(data)
           		{
           			data = JSON.parse(data);
           			department_selected = data[0]['department_name'];
           		}
           }
       	});

    	if(designation_selected != '')
    	{	
    		$("#ol_job_title").val(department_selected+' '+designation_selected);
    	}
    });
</script>  
