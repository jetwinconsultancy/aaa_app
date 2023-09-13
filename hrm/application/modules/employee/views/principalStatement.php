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

<!-- <?php if(!empty($employee_data[0]->id)){ ?>
<div style="text-align: right; display:none" id="btn_action">
	<input type="button" style="cursor:pointer; margin-bottom: 10px;" class="btn btn_purple" value="Preview" onclick="preview_offer_letter('<?=isset($employee_data[0]->employee_applicant_id)?$employee_data[0]->employee_applicant_id:'' ?>')"/>
</div>
<?php } ?> -->

<div class="box">
	<div class="box-content">
	    <div class="row">
	        <div class="col-lg-12">
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
                                        	<input type="hidden" id="EC_name" value="<?php echo isset($employee_data[0]->name)?$employee_data[0]->name:'' ?>">
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
                                        	<label style="width:100px;"><?php echo isset($employee_data[0]->nric_fin_no)?$employee_data[0]->nric_fin_no:'' ?></label>
                                        	<input type="hidden" id="EC_nric_fin_no" value="<?php echo isset($employee_data[0]->nric_fin_no)?$employee_data[0]->nric_fin_no:'' ?>">
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
			                    		<input type="hidden" id="EC_company_name" value="<?php echo isset($employee_data[0]->company_name)?$employee_data[0]->company_name :'' ?>">
            							<!-- <input type="text" class="form-control" name="ol_company"> -->
			                        </div>
			                    </div>
		                    </div>

							<div class="form-group">
                                <div style="width: 100%;">
                                    <div style="width: 30%;float:left;margin-right: 20px;">
                                        <label>Promotion/Progression :</label>
                                    </div>
                                    <div style="width: 250px;float:left;margin-bottom:10px;">
										<div>
											<input type="checkbox" id="promotion" name="promotion" />
											<input type="hidden" name="hidden_promotion" value="0"/>
										</div>
									</div>
                                </div>
                            </div>

                            <div class="form-group promotion_group" style="display:none">
                                <div style="width: 100%;">
                                    <div style="width: 30%;float:left;margin-right: 20px;">
                                        <label>Department :</label>
                                    </div>
                                    <div style="width: 60%;float:left;margin-bottom:5px;">
						                <?php 
                                    		echo form_dropdown('EC_staff_department', $department_list, isset($employee_data[0]->department)?$employee_data[0]->department:'', 'class="form-control EC_staff_department general" id="EC_staff_department" ');
                                    	?>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group promotion_group" style="display:none">
                                <div style="width: 100%;">
                                    <div style="width: 30%;float:left;margin-right: 20px;">
                                        <label>Designation :</label>
                                    </div>
                                    <div class="EC_designation_div_div" style="width: 60%;float:left;margin-bottom:5px;">
                                        <div class="EC_designation_div">
											<select class="form-control EC_designation" id="EC_designation" name="EC_designation" style="width: 100% !important" required>
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
										<!-- <label>
			                    			<?php echo isset($employee_data[0]->effective_from)?date('d F Y', strtotime($employee_data[0]->effective_from)) :'' ?>
			                    		</label> -->
						                <div class="input-group date datepicker">
						                	<div class="input-group-addon">
										        <span class="far fa-calendar-alt"></span>
										    </div>
						                	<input type="text" class="form-control" id="ol_effective_from" name="ol_effective_from" data-date-format="dd-mm-yyyy" value="<?=isset($employee_data[0]->date_joined)?date('d F Y', strtotime($employee_data[0]->date_joined)):'' ?>" readOnly >
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
						                <input type="text" class="form-control" id="ol_work_hour" name="ol_work_hour" value="40" style="width: 80px;float:left;"/>
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
						                <input type="text" class="form-control" id="ol_vacation_leave" name="ol_vacation_leave" value="<?=isset($employee_data[0]->days)?$employee_data[0]->days:'' ?>" style="width: 80px;float:left;"/>
						                <p style="float:left;padding: 8px;">days per annum</p>
                                    </div>
                                </div>
                            </div>

							<div class="form-group salary-group">
                                <div style="width: 100%;">
                                    <div style="width: 30%;float:left;margin-right: 20px;">
                                        <label>New salary :</label>
                                    </div>
                                    <div style="width: 60%;float:left;margin-bottom:5px;">
										<div style="width: 200px; margin-bottom:5px;">
											<?php
												echo form_dropdown('salary_form_currency', $currency_list, isset($salary[0]->currency)?$salary[0]->currency: '', 'class="salary_form_currency currency-select general" "');
											?>
										</div>
						                <input type="text" class="form-control" id="new_salary" name="new_salary" value="" style="width: 200px;float:left;" required/>
                                    </div>
                                </div>
                            </div>

							<div class="form-group salary-group">
                                <div style="width: 100%;">
                                    <div style="width: 30%;float:left;margin-right: 20px;">
                                        <label>Effective date :</label>
                                    </div>
                                    <div style="width: 60%;float:left;margin-bottom:5px;">
									<div class="input-group date datepicker">
						                	<div class="input-group-addon">
										        <span class="far fa-calendar-alt"></span>
										    </div>
						                	<input type="text" class="form-control" id="salary_effective_from" name="salary_effective_from" data-date-format="dd-mm-yyyy" value="" required>
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
</div>

<script type="text/javascript" src="<?php echo base_url() . 'application/js/number_input_format.js' ?>"></script>

<script type="text/javascript">

	var offer_letter_id = <?php echo isset($employee_data[0]->id)?$employee_data[0]->id: 'null'; ?>;
	var date_joined = <?php echo isset($employee_data[0]->date_joined)?json_encode($employee_data[0]->date_joined): 'null'; ?>;

	if(offer_letter_id != null){
		$("#btn_action").show();
	}

	$('.datepicker').datepicker({
		format: 'dd MM yyyy',
		startDate: new Date(date_joined)
	});

	toastr.options = {
	  "positionClass": "toast-bottom-right"
	}

	// $("[name='ol_is_pr_singaporean']").bootstrapSwitch({
	//     state: <?php echo isset($is_pr_singaporean)?$is_pr_singaporean: 0; ?>,
	//     size: 'normal',
	//     onColor: 'purple',
	//     onText: 'Yes',
	//     offText: 'No',
	//     // Text of the center handle of the switch
	//     labelText: '&nbsp',
	//     // Width of the left and right sides in pixels
	//     handleWidth: '75px',
	//     // Width of the center handle in pixels
	//     labelWidth: 'auto',
	//     baseClass: 'bootstrap-switch',
	//     wrapperClass: 'wrapper'
	// });

	// $("[name='ol_is_pr_singaporean']").on('switchChange.bootstrapSwitch', function(event, state) {
	// 	if(state == true)
	//     {
	// 		$("[name='hidden_ol_is_pr_singaporean']").val(1);
	// 	}
	// 	else
	// 	{
	// 		$("[name='hidden_ol_is_pr_singaporean']").val(0);
	// 	}
	// })

	$(document).ready(function () {
    	$('.EC_staff_department option[value=7]').remove();
    	$('.staff_office option[value=1]').remove();

    	var department  = <?php echo json_encode(isset($employee_data[0]->department)?$employee_data[0]->department:"")?>;
    	var designation = <?php echo json_encode(isset($employee_data[0]->designation)?$employee_data[0]->designation:"")?>;

    	$.ajax({
           type: "POST",
           url: "<?php echo site_url('employee/get_designation'); ?>",
           data: "&department="+ department,
           success: function(data)
           {
           		var result = JSON.parse(data);

           		$(".EC_designation_div").remove();
				
				var dropdown = '<div class="EC_designation_div"><select class="form-control EC_designation general" id="EC_designation" name="EC_designation" style="width: 100% !important" required><option value="" selected="selected">Please Select Designation</option>';

           		if(result != '')
           		{
				    for($i=0;$i<result.length;$i++)
				    {
				    	dropdown += '<option value="'+result[$i]['designation']+'">'+result[$i]['designation']+'</option>';
				    }
           		}
           		
           		dropdown += '</select></div>';
			    $(".EC_designation_div_div").append(dropdown);

			    if(designation != ''){
		       		$(".EC_designation").val(designation);
		       	}
           }
       	});

       	var department_selected = '';
    	var designation_selected = designation;

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

    	if(department_selected != '' && designation_selected != '')
    	{	
    		$("#ol_job_title").val(department_selected+' '+designation_selected);
    	}
    });

    $(document).on('change', '.EC_staff_department', function() {

			var form = $(this);
        
	        var department = $(".EC_staff_department").val();

	        if(department == ''){
	        	$(".EC_designation_div").remove();

			    var dropdown = '<div class="EC_designation_div"><select class="form-control EC_designation" id="EC_designation" name="EC_designation" style="width: 100% !important" required><option value="" selected="selected">Please Select Designation</option></select></div>';

			    $(".EC_designation_div_div").append(dropdown);

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

		           		$(".EC_designation_div").remove();
						
						var dropdown = '<div class="EC_designation_div"><select class="form-control EC_designation" id="EC_designation" name="EC_designation" style="width: 100% !important" required><option value="" selected="selected">Please Select Designation</option>';

		           		if(result != ''){

						    for($i=0;$i<result.length;$i++)
						    {
						    	dropdown += '<option value="'+result[$i]['designation']+'">'+result[$i]['designation']+'</option>';
						    }

		           		}
		           		
		           		dropdown += '</select></div>';
					    $(".EC_designation_div_div").append(dropdown);
		           }
		       	});
	        }
    });


    $(document).on('change', '.EC_designation', function() {

    	var department = $(".EC_staff_department").val();
    	var department_selected = '';
    	var designation_selected = $("#EC_designation").val();

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

	$("[name='promotion']").bootstrapSwitch({
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

	$("[name='promotion']").on('switchChange.bootstrapSwitch', function(event, state) {
		if(state == true)
	    {
			$("[name='hidden_promotion']").val(1);
			$(".promotion_group").show();
		}
		else
		{
			$("[name='hidden_promotion']").val(0);
			$(".promotion_group").hide();
		}
	});
</script>  
