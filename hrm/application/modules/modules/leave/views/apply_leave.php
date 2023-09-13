<link rel="stylesheet" href="<?= base_url() ?>node_modules/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" />
<script src="<?= base_url() ?>node_modules/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>

<script type="text/javascript" src="<?= base_url()?>application/js/custom/time_format.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>application/css/plugin/toastr.min.css" />
<script src="<?= base_url() ?>application/js/toastr.min.js"></script>

<section class="body">
	<div class="inner-wrapper">
		<section role="main" class="content_section" style="margin-left:0;">
			<?php echo $breadcrumbs;?>
			<div class="box" style="margin-bottom: 30px; margin-top: 30px;">
				<div class="box-content">
				    <div class="row">
				        <div class="col-lg-12">
				            <!-- <form id="appy_leave" method="POST" autocomplete="off"> -->
				            	<input id="employee_id" name="employee_id" class="form-control" type="hidden" value="<?=isset($leave_data['employee_id'])?$leave_data['employee_id']:''?>">
				            	<input id="leave_id" name="leave_id" class="form-control" type="hidden" value="<?=isset($leave_data['leave_id'])?$leave_data['leave_id']:''?>">

	                            <div class="form-group row">
								    <label for="active_type_of_leave" class="col-sm-3 col-form-label">Type of leave: </label>
							    	<div class="col-sm-5" style="width: 25% !important">
							    		<?php 
							    			//echo json_encode($block_leave_list);
                                    		echo form_dropdown('type_of_leave_id', $active_type_of_leave, isset($leave_data['type_of_leave_id'])?$leave_data['type_of_leave_id']:'', 'class="form-control int_select2 type_of_leave" id="type_of_leave" required');
                                    	?>
						            </div>
								</div>

								<div class="form-group row Sick_Leave" style="display: none;">
								    <label class="col-sm-3 col-form-label">Medical Certificate: </label>
							    	<div class="col-sm-5" style="width: 25% !important">
							    		<div class='input-group'>
		                                	<input type='file' style='display:none' id='attachment_medical_cert' multiple='' name='attachment_medical_cert'>
		                                	<label for='attachment_medical_cert' class='btn btn_purple btn_attachment_medical_cert' style="width: 250px;">Select Attachment</label><br/>
		                                	<span class='file_name_medical_cert' id='file_name_medical_cert' value=''></span>
		                                	<input type='hidden' class='hidden_attachment_medical_cert' name='hidden_attachment_medical_cert' value=''/>
		                                </div>
						            </div>
								</div>

								<div class="form-group row Compassionate_Leave" style="display: none;">
								    <label for="relationship" class="col-sm-3 col-form-label">Relationship: </label>
							    	<div class="col-sm-5 relationship_div_div" style="width: 25% !important">
							    		<!-- <?php 
                                    		echo form_dropdown('relationship', $relationship, '', 'class="form-control int_select2 relationship" id="relationship" ');
                                    	?> -->

                                        <div class="relationship_div">
											<select class="form-control relationship" id="relationship" name="relationship" style="width: 100% !important" required>
												<option value="" selected="selected">Please Select the Relationship</option>
											</select>
                                        </div>

						            </div>
								</div>

								<div class="form-group row Compassionate_Leave" style="display: none;">
								    <label class="col-sm-3 col-form-label">Death Certificate: </label>
							    	<div class="col-sm-5" style="width: 25% !important">
							    		<div class='input-group'>
		                                	<input type='file' style='display:none' id='attachment_death_certificate' multiple='' name='attachment_death_certificate'>
		                                	<label for='attachment_death_certificate' class='btn btn_purple btn_attachment_death_certificate' style="width: 250px;">Select Attachment</label><br/>
		                                	<span class='file_name_death_certificate' id='file_name_death_certificate' value=''></span>
		                                	<input type='hidden' class='hidden_attachment_death_certificate' name='hidden_attachment_death_certificate' value=''/>
		                                </div>
						            </div>
								</div>

								<div class="form-group row Study_Leave" style="display: none;">
								    <label class="col-sm-3 col-form-label">Institution: </label>
							    	<div class="col-sm-5" style="width: 25% !important">
							    		<?php 
                                    		echo form_dropdown('institution', $institution, '', 'class="form-control int_select2 institution" id="institution" ');
                                    	?>
						            </div>
								</div>

								<div class="form-group row Study_Leave" style="display: none;">
								    <label class="col-sm-3 col-form-label">Number of Subject: </label>
							    	<div class="col-sm-5" style="width: 25% !important">
							    		<input type='number' class="form-control" name="num_of_subject" id='num_of_subject' min="1" value="0" onchange="calculateBalance()" style="width: 35%; text-align: center; display:inline-block !important;" />
						            </div>
								</div>

								<div class="form-group row Study_Leave" style="display: none;">
								    <label class="col-sm-3 col-form-label">Exam Schedule: </label>
							    	<div class="col-sm-5" style="width: 25% !important">
							    		<div class='input-group'>
		                                	<input type='file' style='display:none' id='attachment_exam_schedule' multiple='' name='attachment_exam_schedule'>
		                                	<label for='attachment_exam_schedule' class='btn btn_purple btn_attachment_exam_schedule' style="width: 250px;">Select Attachment</label><br/>
		                                	<span class='file_name_exam_schedule' id='file_name_exam_schedule' value=''></span>
		                                	<input type='hidden' class='hidden_attachment_exam_schedule' name='hidden_attachment_exam_schedule' value=''/>
		                                </div>
						            </div>
								</div>

								<div class="form-group row Childcare_Leave" style="display: none;">
								    <label class="col-sm-3 col-form-label">Child's Date Of Birth / Estimated Delivery Date: </label>
							    	<div class="col-sm-5" style="width: 25% !important">
							    		<div class='input-group date'>
								    		<span class="input-group-addon">
												<i class="far fa-calendar-alt"></i>
											</span>
						                    <input type='text' class="form-control leave_child_dob" name="leave_child_dob" id='leave_child_dob' required/>
						                </div>
						            </div>
								</div>

								<div class="form-group row Childcare_Leave" style="display: none;">
								    <label class="col-sm-3 col-form-label">Your Child Is: </label>
							    	<div class="col-sm-5" style="width: 25% !important">
							    		<select class="form-control int_select2" id="child_is" name="child_is" style="width: 100% !important" required>
											<option value="singaporean" selected="selected">Singaporean</option>
											<option value="malaysian">Malaysian</option>
											<option value="others">Others</option>
										</select>
						            </div>
								</div>

								<div class="form-group row Childcare_Leave" style="display: none;">
								    <label class="col-sm-3 col-form-label">Birth Certificate: </label>
							    	<div class="col-sm-5" style="width: 25% !important">
							    		<div class='input-group'>
		                                	<input type='file' style='display:none' id='attachment_birth_cert' multiple='' name='attachment_birth_cert'>
		                                	<label for='attachment_birth_cert' class='btn btn_purple btn_attachment_birth_cert' style="width: 250px;">Select Attachment</label><br/>
		                                	<span class='file_name_birth_cert' id='file_name_birth_cert' value=''></span>
		                                	<input type='hidden' class='hidden_attachment_birth_cert' name='hidden_attachment_birth_cert' value=''/>
		                                </div>
						            </div>
								</div>

								<div class="form-group row Wedding_Leave" style="display: none;">
								    <label class="col-sm-3 col-form-label">Married Certificate: </label>
							    	<div class="col-sm-5" style="width: 25% !important">
							    		<div class='input-group'>
		                                	<input type='file' style='display:none' id='attachment_married_cert' multiple='' name='attachment_married_cert'>
		                                	<label for='attachment_married_cert' class='btn btn_purple btn_attachment_married_cert' style="width: 250px;">Select Attachment</label><br/>
		                                	<span class='file_name_married_cert' id='file_name_married_cert' value=''></span>
		                                	<input type='hidden' class='hidden_attachment_married_cert' name='hidden_attachment_married_cert' value=''/>
		                                </div>
						            </div>
								</div>

								<div class="form-group row">
								    <label for="balance" class="col-sm-3 col-form-label">Balance (Before Approve): </label>
							    	<div class="col-sm-1">
							    		<input type='text' class="form-control" name="balance" id='balance' readonly="true" value="<?php echo (isset($leave_data['balance_before_approve'])?$leave_data['balance_before_approve']:'')?>"/>
							    		
						            </div>
						            <div class="col-sm-1" style="padding-top: 6px; padding-left: 0px;"><span>day(s)</span></div>
								</div>

				            	<div class="form-group row">
								    <label for="leave_start_date" class="col-sm-3 col-form-label">Start Date: </label>
								    <div class="col-sm-3">
								    	<div class='input-group date'>
								    		<span class="input-group-addon">
												<i class="far fa-calendar-alt"></i>
											</span>
						                    <input type='text' class="form-control leave_start_date" name="leave_start_date" id='start_date' required/>
						                </div>
								    </div>
								    <div class="col-sm-2">
								    	<!-- <div class="col-sm-6 addBtn">
								    		<div style="padding:5%">
									    		<a style="cursor: pointer;" onclick="hide_addBtn(this)"><span class="glyphicon glyphicon-plus"></span> Add Time</a>
									    	</div>
								    	</div> -->
								    	<div class="col-sm-10">
								    		<?php
                                                echo form_dropdown('leave_start_time', $start_time_list, isset($leave_data['start_time'])?$leave_data['start_time']:'', 'onchange="remove_time_option(1)" style="height: 34px; padding: 6px 12px;" class="form-control leave_start_time" id="leave_start_time" ');
                                            ?>
									    	<!-- <div class='input-group date' id='start_date_time' style="display:none;">
							                    <input type='text' class="form-control" name="leave_start_time" />
							                    <span class="input-group-addon">
							                        <span class="glyphicon glyphicon-time"></span>
							                    </span>
							                </div> -->
							            </div>
						               <!--  <div class="col-sm-1 removeBtn" style="display: none; padding: 3%;">
							                <a style="cursor: pointer;" onclick="remove_time(this)"><span class="glyphicon glyphicon-remove"></span></a>
							            </div> -->
								    </div>
								</div>
								<div class="form-group row">
								    <label for="leave_end_date" class="col-sm-3 col-form-label">End Date: </label>
								    <div class="col-sm-3">
								    	<div class='input-group date'>
								    		<span class="input-group-addon">
												<i class="far fa-calendar-alt"></i>
											</span>
						                    <input type='text' class="form-control leave_end_date" name="leave_end_date" id='end_date' required/>
						                </div>
								    </div>
								    <div class="col-sm-2">
								    	<!-- <div class="col-sm-6 addBtn">
								    		<div style="padding:5%">
										    	<a style="cursor: pointer" onclick="hide_addBtn(this)"><span class="glyphicon glyphicon-plus"></span> Add Time</a>
										    </div>
									    </div> -->
								    	<div class="col-sm-10">
								    		<?php
                                                echo form_dropdown('leave_end_time', $end_time_list, isset($leave_data['end_time'])?$leave_data['end_time']?$leave_data['end_time']:end($end_time_list):end($end_time_list), 'onchange="remove_time_option(0)" style="height: 34px; padding: 6px 12px;" class="form-control leave_end_time" id="leave_end_time" ');
                                            ?>
									    	<!-- <div class='input-group date' id='end_date_time' style="display:none;">
							                    <input type='text' class="form-control" name="leave_end_time"/>
							                    <span class="input-group-addon">
							                        <span class="glyphicon glyphicon-time"></span>
							                    </span>
							                </div> -->
							            </div>
							            <!-- <div class="col-sm-1 removeBtn" style="display: none; padding: 3%;">
							                <a style="cursor: pointer;" onclick="remove_time(this)"><span class="glyphicon glyphicon-remove"></span></a>
							            </div> -->
								    </div>
								    <!-- <div class="col-sm-5">
								      	<div id="leave_end_date" class="input-group date form_datetime"  data-date-format="dd MM yyyy - HH:ii:ss p" data-link-field="dtp_input1">
						                    <input name="leave_end_date" class="form-control" size="16" type="text" value="<?=isset($mc_data[0]->end_date)?$mc_data[0]->end_date:''?>" onchange="check_date('end_date')">
						                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
											<span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
						                </div>
								    </div>
								    <div class="col-sm-3">
								    	<div class='input-group date' id='end_date_time'>
						                    <input type='text' class="form-control" />
						                    <span class="input-group-addon">
						                        <span class="glyphicon glyphicon-time"></span>
						                    </span>
						                </div>
								    </div> -->
								</div>
								<div class="form-group row">
								    <label for="mc_reason" class="col-sm-3 col-form-label">Total Leave Apply: </label>
								    <div class="col-sm-5">
								    	<label id="total_leave_apply" style="display:inline-block;">
								    		<?php echo isset($leave_data['total_leave_apply'])?$leave_data['total_leave_apply']:0 ?>
								    	</label>
								    	<input id="leave_total_days" name="leave_total_days" type="hidden" value="<?=isset($leave_data['total_leave_apply'])?$leave_data['total_leave_apply']:0?>">
								    	<label style="display:inline-block;">day(s)</label>
								    </div>
								</div>
								<hr>
								<!-- <div class="form-group row">
								    <label for="mc_reason" class="col-sm-2 col-form-label">Remaining leave: </label>
								    <div class="col-sm-5">
								    	<input name="days_left_before" type="hidden" value="<?=isset($leave_data['days_left_before'])?$leave_data['days_left_before']:0?>">
								    	<label id="total_remaining_al" style="display:inline-block;">
								    		<?php echo isset($leave_data['total_remaining_al'])?$leave_data['total_remaining_al']:0 ?>
								    	</label>
								    	<input name="total_remaining_al" type="hidden" value="<?=isset($leave_data['total_remaining_al'])?$leave_data['total_remaining_al']:0?>">	
								    	<label style="display:inline-block;">day(s)</label>
								    </div>
								</div> -->
								<!-- <div class="row">
							        <div class='col-sm-6'>
							            <div class="form-group">
							                
							            </div>
							        </div>
							    </div> -->
								<div class="form-group row">
									<div class="col-sm-12">
								    	<?php 
								    		//$status = isset($status)?$status:'';
								    		echo '<a href="'.base_url().'leave" class="btn pull-right btn_cancel" style="margin:0.5%; cursor: pointer;">Cancel</a>';
								    		echo '<button class="btn btn_purple pull-right update_leave" style="margin:0.5%;display: none;">Update</button>';

								    		if($status == NULL)
								    		{
								    			if(!$Admin && !$Manager && $leave_data['user_id'] == $this->user_id)
								    			{
								    				// if($leave_data['status'] == 1 || $leave_data['status'] == '')
								    				if($leave_data['status'] == '')
								    				{
								    					echo '<button class="btn btn_purple pull-right appy_leave" style="margin:0.5%">Apply</button>';
								    				}
								    			}
								    			else
								    			{
								    				echo '<button class="btn btn_purple pull-right appy_leave" style="margin:0.5%">Apply</button>';
								    			}
								    		}
								    	?>
								    </div>
			                    </div>
						    <!-- </form> -->
					    </div>
					</div>
				</div>
			</div>
		</section>
	</div>
</section>
<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>

<!-- <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  	<div class="modal-dialog" role="document">
    	<div class="modal-content">
	    	<div class="modal-header">
	      		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		        	<span aria-hidden="true">&times;</span>
		  	    </button>
		  	    <?php 
		  	    	if(is_null(isset($mc_data[0]->id)?$mc_data[0]->id:null)){
		  	    		echo '<h4 class="modal-title">Success apply</h4>';
		  	    	} else {
		  	    		echo '<h4 class="modal-title">Success update</h4>';
		  	    	}
		  	    ?>
	      		
	      	</div>
	      	<div class="modal-body">
	      		<?php 
	      			if(is_null(isset($mc_data[0]->id)?$mc_data[0]->id:null)){
		  	    		echo '<p>Your MC has been successfully applied.</p>';
		  	    	} else {
		  	    		echo '<p>MC has been successfully updated.</p>';
		  	    	}
	      		?>
	      	</div>
	      	<div class="modal-footer">
	        	<button type="button" class="btn btn-default cancelBtn" data-dismiss="modal">Close</button>
	      	</div>
    	</div>
  	</div>
</div> -->
<!-- <script src="<?= base_url() ?>application/views/modal/modal_template.php"></script> -->

<script type="text/javascript">

	toastr.options = {
	  "positionClass": "toast-bottom-right"
	}
	//console.log(<?php echo json_encode($block_leave_list) ?>);
	var page_status = <?php echo json_encode($status) ?>;
	// var leave_data = JSON.parse('<?php echo json_encode($leave_data) ?>');
	var leave_data = <?php echo json_encode(isset($leave_data)?$leave_data:FALSE) ?>;
	var initial_start_date = '<?php echo isset($leave_data['start_date'])?$leave_data['start_date']:''; ?>';
	var initial_end_date   = '<?php echo isset($leave_data['end_date'])?$leave_data['end_date']:''; ?>';
	var employee_id = '<?php echo isset($leave_data['employee_id'])?$leave_data['employee_id']:''; ?>';
	var disabledArr = JSON.parse('<?php echo json_encode($block_leave_list) ?>');
	var other_type_of_leave_list =  JSON.parse('<?php echo isset($other_type_of_leave_list)?$other_type_of_leave_list:''; ?>');
	var staff = <?php echo json_encode(isset($staff)?$staff:FALSE) ?>;
	var base_url = '<?php echo base_url() ?>';
	var Admin = <?php echo json_encode($Admin) ?>;
	var Manager = <?php echo json_encode($Manager) ?>;
	var user_id = <?php echo json_encode($this->user_id) ?>;
	var daysOfYear = [];
	var relationship = <?php echo json_encode(isset($relationship)? $relationship:'') ?>;

	var temp_dp_value = '';

	if(leave_data['child_dob'] != 'undefined')
	{
		var perious_child_dob = leave_data['child_dob'];
	}
	else
	{
		var perious_child_dob = '';
	}

	var setting_ss = 0;
	var setting_sf = 0;
	var setting_mm = 0;
	var setting_mf = 0;

	$.ajax({
		'async':false,
        type: "POST",
        url: "<?php echo site_url('leave/get_leave_day'); ?>",
        data: {'id':'5'},
        success: function(data){
        	var object = JSON.parse(data);

        	setting_ss = object[0]['days'];
			setting_sf = object[0]['second_condition'];
			setting_mm = object[0]['third_condition'];
			setting_mf = object[0]['fourth_condition'];
        }
   	});

	var ss = setting_ss;
	var sf = setting_sf;
	var mm = setting_mm;
	var mf = setting_mf;

	$(".int_select2").select2();

	if(page_status == "view")
	{
		$(".leave_start_date").val(moment(initial_start_date).format('DD/MM/YYYY'));
		$(".leave_end_date").val(moment(initial_end_date).format('DD/MM/YYYY'));
		$(".type_of_leave").attr("disabled", true);
		$(".leave_start_date").attr("disabled", true);
		$(".leave_end_date").attr("disabled", true);
		$(".leave_start_time").attr("disabled", true);
		$(".leave_end_time").attr("disabled", true);
		$("#num_of_subject").attr("disabled", true);
	}
	// console.log(leave_data['status']);
	if(!Admin && !Manager || leave_data['user_id'] == user_id)
	{
		// if(leave_data['status'] != 1 && leave_data['status'] != '')
		if(leave_data['status'] != '')
		{
			// moment(initial_end_date).format('DD MMMM YYYY')
			$(".leave_start_date").val(moment(initial_start_date).format('DD/MM/YYYY'));
			$(".leave_end_date").val(moment(initial_end_date).format('DD/MM/YYYY'));
			$(".type_of_leave").attr("disabled", true);
			$(".leave_start_date").attr("disabled", true);
			$(".leave_end_date").attr("disabled", true);
			$(".leave_start_time").attr("disabled", true);
			$(".leave_end_time").attr("disabled", true);
		}
	}

	var initial = false;

	$(document).on('change',".type_of_leave",function() {

		$("#balance").val('0');
		$('.Sick_Leave').hide();
		$('.Compassionate_Leave').hide();
		$('.Childcare_Leave').hide();
		$('.Study_Leave').hide();
		$('.Wedding_Leave').hide();

		if($('.type_of_leave').val() == '1'){
			for(i=0;i<disabledArr.length;i++){
				var From = new Date(disabledArr[i].block_leave_date_from);
			    var To = new Date(disabledArr[i].block_leave_date_to);
			    
			    var loop = new Date(disabledArr[i].block_leave_date_from);

			    for (var d = From; d <= To; d.setDate(d.getDate() + 1)) {
				    daysOfYear.push(new Date(d));
				}
			}

			if(initial){
				$('#start_date').data("DateTimePicker").destroy();
				$('#end_date').data("DateTimePicker").destroy();
			}

			$('#start_date').datetimepicker({
		        format: 'DD/MM/YYYY',
		        date: new Date(initial_start_date),
		        useCurrent: false,
		        widgetPositioning: {
		        	vertical: 'bottom',
		        },
		        disabledDates: daysOfYear
		    });

			$('#end_date').datetimepicker({
		        format: 'DD/MM/YYYY',
		        date: new Date(initial_end_date),
		        useCurrent: false,
		        widgetPositioning: {
		        	vertical: 'bottom',
		        },
		        disabledDates: daysOfYear
		    });

		    initial = true;
		}
		else
		{
			if(initial){
				$('#start_date').data("DateTimePicker").destroy();
				$('#end_date').data("DateTimePicker").destroy();
			}

			$('#start_date').datetimepicker({
		        format: 'DD/MM/YYYY',
		        date: new Date(initial_start_date),
		        useCurrent: false,
		        widgetPositioning: {
		        	vertical: 'bottom',
		        },
		        disabledDates: null
		    });

			$('#end_date').datetimepicker({
		        format: 'DD/MM/YYYY',
		        date: new Date(initial_end_date),
		        useCurrent: false,
		        widgetPositioning: {
		        	vertical: 'bottom',
		        },
		        disabledDates: null
		    });

		    $('#leave_child_dob').datetimepicker({
		        format: 'DD/MM/YYYY',
		        // date: new Date(initial_start_date),
		        useCurrent: false,
		        widgetPositioning: {
		        	vertical: 'bottom',
		        },
		        disabledDates: null
		    });

		    initial = true;
		}

		if($('.type_of_leave').val() == '2')
		{
			$('.Sick_Leave').show();
		}

		if($('.type_of_leave').val() == '4')
		{
			$('.Compassionate_Leave').show();

			$.ajax({
	           type: "POST",
	           url: "<?php echo site_url('leave/get_relationship'); ?>",
	           data: "&id="+ leave_data['employee_id'],
	           success: function(data)
	           {
	           		var result = JSON.parse(data);

	           		$(".relationship_div").remove();
					
					var dropdown = '<div class="relationship_div"><select class="form-control relationship" id="relationship" name="relationship" style="width: 100% !important" required><option value="" selected="selected">Please Select the Relationship</option>';

	           		if(result != '')
	           		{
					    for($i=0;$i<result.length;$i++)
					    {
					    	if(result[$i]['funeral_flag'] != 1)
					    	{
					    		dropdown += '<option value="'+result[$i]['id']+'">'+result[$i]['relationship_name']+' ('+result[$i]['family_name']+')</option>';
					    	}
					    }
	           		}
	           		
	           		dropdown += '</select></div>';
				    $(".relationship_div_div").append(dropdown);

				    $(".relationship").select2();
	           	}
	       	});
		}

		if($('.type_of_leave').val() == '5')
		{
			$('.Childcare_Leave').show();
			// $('.Wedding_Leave').show();

			if(staff[0]['office_country'] == "SINGAPORE")
			{
				if($('#child_is').val() == 'singaporean')
				{
					$("#balance").val(ss);
				}
				else
				{
					$("#balance").val(sf);
				}
			}
			else if(staff[0]['office_country'] == "MALAYSIA")
			{
				$("#child_is").val('malaysian').trigger('change');

				if($('#child_is').val() == 'malaysian')
				{
					$("#balance").val(mm);
				}
				else
				{
					$("#balance").val(mf);
				}
			}
		}

		if($('.type_of_leave').val() == '6')
		{
			$('.Childcare_Leave').show();
			$('.Wedding_Leave').show();
		}

		if($('.type_of_leave').val() == '7')
		{
			$('.Childcare_Leave').show();
		}

		if($('.type_of_leave').val() == '8')
		{
			$('.Study_Leave').show();
		}

		if($('.type_of_leave').val() == '9')
		{
			$('.Wedding_Leave').show();

			var balance = 0;

			$.ajax({
	    		'async':false,
		        type: "POST",
		        url: "<?php echo site_url('leave/get_leave_day'); ?>",
		        data: {'id':'9'},
		        success: function(data){
		        	var object = JSON.parse(data);

		        	balance = parseFloat(object[0]['days']);
		        }
		   	});

		   	$("#balance").val(balance);
		}
	});

/* --------------------------------------------------------------------------------------------------------- */
	$(".type_of_leave").change(function (){
		$('#loadingmessage').show();
	    $.post("<?php echo site_url('leave/get_the_balance'); ?>", {employee_id: employee_id ,type_of_leave_id: $(this).val()}, function(data){
	    	if(data != false)
	    	{
	    		$("#balance").val(JSON.parse(data)[0]["annual_leave_days"]);
	    	}
	    	// else
	    	// {
	    	// 	$("#balance").val('0');
	    	// }

	    	$('#loadingmessage').hide();
		});
	});

	$(document).on('change','[id=child_is]',function(){

		if(staff[0]['office_country'] == "SINGAPORE")
		{
			if($('#child_is').val() == 'singaporean')
			{
				$("#balance").val(ss);
			}
			else
			{
				$("#balance").val(sf);
			}
		}
		else if(staff[0]['office_country'] == "MALAYSIA")
		{
			if($('#child_is').val() == 'malaysian')
			{
				$("#balance").val(mm);
			}
			else
			{
				$("#balance").val(mf);
			}
		}
	});

	$(document).on('change','[id=relationship]',function(){

		var relation = $(".relationship").val();

		var immediate_family = 0;
		var close_relative = 0;

		$.ajax({
    		'async':false,
	        type: "POST",
	        url: "<?php echo site_url('leave/get_leave_day'); ?>",
	        data: {'id':'4'},
	        success: function(data){
	        	var object = JSON.parse(data);

	        	immediate_family = parseFloat(object[0]['days']);
	        	close_relative = parseFloat(object[0]['second_condition']);
	        }
	   	});

		if(relation != '')
		{
			for(var a = 0 ; a < relationship.length ; a ++)
			{
				if(relationship[a]['id'] == relation)
				{
					if(relationship[a]['relatives'] == '1')
					{
						$("#balance").val(immediate_family);
					}
					else
					{
						$("#balance").val(close_relative);
					}
				}
			}
		}
		else
		{
			$("#balance").val('0');
		}
		
	});

	$(document).on('change','[id=attachment_medical_cert]',function(){
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
	    $(this).parent().find(".file_name_medical_cert").html(filename);
	    $(this).parent().find(".hidden_attachment_medical_cert").val("");
	});

	$(document).on('change','[id=attachment_death_certificate]',function(){
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
	    $(this).parent().find(".file_name_death_certificate").html(filename);
	    $(this).parent().find(".hidden_attachment_death_certificate").val("");
	});

	$(document).on('change','[id=attachment_birth_cert]',function(){
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
	    $(this).parent().find(".file_name_birth_cert").html(filename);
	    $(this).parent().find(".hidden_attachment_birth_cert").val("");
	});

	$(document).on('change','[id=attachment_exam_schedule]',function(){
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
	    $(this).parent().find(".file_name_exam_schedule").html(filename);
	    $(this).parent().find(".hidden_attachment_exam_schedule").val("");
	});

	$(document).on('change','[id=attachment_married_cert]',function(){
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
	    $(this).parent().find(".file_name_married_cert").html(filename);
	    $(this).parent().find(".hidden_attachment_married_cert").val("");
	});

	// disable selection on date before and after.
 	$("#start_date").on("dp.change", function (e) {

        $('#end_date').data("DateTimePicker").minDate(e.date);

        var end_date = $('#end_date').data('DateTimePicker').date();

        if(end_date != null){
        	if(!(e.date.format('YYYY-MM-DD') == end_date.format('YYYY-MM-DD'))){
	        	$('select[name=leave_start_time] option').last().show();
		    	$('select[name=leave_end_time] option').first().show();
	        }
        	calculate_totalDays();	// excluding weekend.
        }
    });

    $("#end_date").on("dp.change", function (e) {
        $('#start_date').data("DateTimePicker").maxDate(e.date);

        var start_date = $('#start_date').data('DateTimePicker').date();

        if(start_date != null){
        	if(!(e.date.format('YYYY-MM-DD') == start_date.format('YYYY-MM-DD'))){
	        	$('select[name=leave_start_time] option').last().show();
		    	$('select[name=leave_end_time] option').first().show();
	        }
        	calculate_totalDays();	// excluding weekend.
        }
    });

$("#leave_child_dob").on("dp.change" , function (e)
{
	if(e.date.format('YYYY-MM-DD') != temp_dp_value)
	{
		if(!Admin && !Manager || leave_data['user_id'] == user_id)
		{
			if(leave_data['status'] == '')
			{
		        $.ajax({
		        	'async':false,
		       		type: "POST",
		           	url: "<?php echo site_url('leave/search_for_matenity'); ?>",
		           	data: {'employee_id':leave_data['employee_id'],'date':$('#leave_child_dob').val()},
		           	success: function(data)
		           	{
		           		var result = JSON.parse(data);

		           		var today = new Date();

		           		var selected = new Date($("#leave_child_dob").val());
		           		selected     = new Date((selected.getFullYear()+1)+'-'+(selected.getMonth()+1)+'-'+selected.getDate());

		           		if(result[0] != 0)
		           		{
		           			if(today <= selected)
		           			{
		           				$("#child_is").val(result[1]).trigger('change');

			           			if(staff[0]['office_country'] == "SINGAPORE")
								{
									if(result[1] == 'singaporean')
									{
										ss = setting_ss - parseFloat(result[0]);
										$("#balance").val(ss);
									}
									else
									{
										sf = setting_sf - parseFloat(result[0]);
										$("#balance").val(sf);
									}
								}
								else if(staff[0]['office_country'] == "MALAYSIA")
								{
									if(result[1] == 'malaysian')
									{
										mm = setting_mm - parseFloat(result[0]);
										$("#balance").val(mm);
									}
									else
									{
										mf = setting_mf - parseFloat(result[0]);
										$("#balance").val(mf);
									}
								}
		           			}
		           			else
		           			{
		           				$("#child_is").val(result[1]).trigger('change');
		           				
		           				if(staff[0]['office_country'] == "SINGAPORE")
								{
									if(result[1] == 'singaporean')
									{
										ss = 0;
										$("#balance").val(ss);
									}
									else
									{
										sf = 0;
										$("#balance").val(sf);
									}
								}
								else if(staff[0]['office_country'] == "MALAYSIA")
								{
									if(result[1] == 'malaysian')
									{
										mm = 0;
										$("#balance").val(mm);
									}
									else
									{
										mf = 0;
										$("#balance").val(mf);
									}
								}
		           			}

		           		}
		           		else
		           		{
							if(staff[0]['office_country'] == "SINGAPORE")
							{
								if($('#child_is').val() == 'singaporean')
								{
									ss = setting_ss;
								}
								else
								{
									sf = setting_sf;
								}
							}
							else if(staff[0]['office_country'] == "MALAYSIA")
							{
								if($('#child_is').val() == 'malaysian')
								{
									mm = setting_mm;
								}
								else
								{
									mf = setting_mf;
								}
							}

							$("#child_is").val($("#child_is").val()).trigger('change');
		           		}
		           	}
		       	});
			}
		}
	}

	temp_dp_value = e.date.format('YYYY-MM-DD');
});

    // start and end (TIME)
	$('#start_date_time').datetimepicker({
        format: 'LT'
    });

    $('#end_date_time').datetimepicker({
        format: 'LT'
    });

    // $('form#appy_leave').submit(function(e) {
    $(".appy_leave").click(function(){
	    var form = $(this);
	    var flag = true;
	    // var flag = false;
	    $(".leave_start_time").attr("disabled", false);
		$(".leave_end_time").attr("disabled", false);

		var leave_type = $("#type_of_leave").val();

		var remain_leave_day = parseFloat($('#balance').val());
		var apply_leave_day = parseFloat($('#total_leave_apply').text());
		var result = parseFloat(remain_leave_day)-parseFloat(apply_leave_day);

		var relationship_input = $('#relationship').val();
		var institution_input  = $('#institution').val();
		var num_subject_input  = $('#num_of_subject').val();

		var leave_child_dob  = $('#leave_child_dob').val();
		var child_is  = $('#child_is').val();

		var leave_child_dob_dateFormat = leave_child_dob.split("/");
		leave_child_dob_dateFormat = new Date(
			parseInt(leave_child_dob_dateFormat[2]) + 1, leave_child_dob_dateFormat[1] - 1, leave_child_dob_dateFormat[0]
		);
		leave_child_dob_timeFormat = leave_child_dob_dateFormat.getTime();

		// var start_date_dateFormat = $("#start_date").val().split("/");
		// start_date_dateFormat = new Date(start_date_dateFormat[2] , start_date_dateFormat[1] - 1, start_date_dateFormat[0]);
		// start_date_timeFormat = start_date_dateFormat.getTime();
		today_dateFormat = new Date();
		today_timeFormat = today_dateFormat.getTime();

		var appy_leave_form = new FormData();
			appy_leave_form.append('employee_id', $("#employee_id").val());
			appy_leave_form.append('leave_id', $("#leave_id").val());
			appy_leave_form.append('type_of_leave_id', $("#type_of_leave").val());
			appy_leave_form.append('balance', $("#balance").val());
			appy_leave_form.append('leave_start_date', $("#start_date").val());
			appy_leave_form.append('leave_start_time', $("#leave_start_time").val());
			appy_leave_form.append('leave_end_date', $("#end_date").val());
			appy_leave_form.append('leave_end_time', $("#leave_end_time").val());
			appy_leave_form.append('leave_total_days', $("#leave_total_days").val());

		if(leave_type == '')
		{
			toastr.error('Please Select the Type of Leave', 'Reminder');
			flag = false;
		}

		if($("#start_date").val() == '')
		{
			toastr.error('Please Enter the Leave Start Date', 'Reminder');
			flag = false;
		}

		if($("#end_date").val() == '')
		{
			toastr.error('Please Enter the Leave End Date', 'Reminder');
			flag = false;
		}

		if(leave_type == '2')
		{
			if($("#attachment_medical_cert")[0]['files'][0] == undefined)
			{
				appy_leave_form.append('attachment_medical_cert', '');
			}
			else
			{
				appy_leave_form.append('attachment_medical_cert', $("#attachment_medical_cert")[0]['files'][0]);
			}

			if(apply_leave_day>remain_leave_day)
			{
				toastr.error('Your Leave Balance Not Enough', 'Reminder');
				flag = false;
			}
		}

		if(leave_type == '4')
		{
			if(relationship_input == '')
			{
				toastr.error('Please Select the Relationship', 'Reminder');
				flag = false;
			}
			else
			{
				appy_leave_form.append('relation', relationship_input);
			}

			if($("#attachment_death_certificate")[0]['files'][0] == undefined)
			{
				appy_leave_form.append('attachment_death_certificate', '');
			}
			else
			{
				appy_leave_form.append('attachment_death_certificate', $("#attachment_death_certificate")[0]['files'][0]);
			}

			if(apply_leave_day>remain_leave_day)
			{
				toastr.error('Your Leave Balance Not Enough', 'Reminder');
				flag = false;
			}
		}

		if(leave_type == '5')
		{
			appy_leave_form.append('child_is', child_is);

			if($("#attachment_birth_cert")[0]['files'][0] == undefined)
			{
				appy_leave_form.append('attachment_birth_cert', '');
			}
			else
			{
				appy_leave_form.append('attachment_birth_cert', $("#attachment_birth_cert")[0]['files'][0]);
			}

			if(apply_leave_day>remain_leave_day)
			{
				toastr.error('Your Leave Balance Not Enough', 'Reminder');
				flag = false;
			}

			if(leave_child_dob == '')
			{
				toastr.error('Please Enter Child Date Of Birth', 'Reminder');
				flag = false;
			}
			else if(today_timeFormat > leave_child_dob_timeFormat)
			{
				toastr.error('This Maternity Leave Is Expired', 'Reminder');
				flag = false;
			}
			else
			{
				appy_leave_form.append('leave_child_dob', leave_child_dob);
			}
		}

		if(leave_type == '6')
		{
			if($("#attachment_birth_cert")[0]['files'][0] == undefined)
			{
				appy_leave_form.append('attachment_birth_cert', '');
			}
			else
			{
				appy_leave_form.append('attachment_birth_cert', $("#attachment_birth_cert")[0]['files'][0]);
			}

			if($("#attachment_married_cert")[0]['files'][0] == undefined)
			{
				appy_leave_form.append('attachment_married_cert', '');
			}
			else
			{
				appy_leave_form.append('attachment_married_cert', $("#attachment_married_cert")[0]['files'][0]);
			}
		}

		if(leave_type == '7')
		{
			if($("#attachment_birth_cert")[0]['files'][0] == undefined)
			{
				toastr.error('Please Attach Your Children Birth Certificate', 'Reminder');
				flag = false;
			}
			else
			{
				appy_leave_form.append('attachment_birth_cert', $("#attachment_birth_cert")[0]['files'][0]);
			}
		}

		if(leave_type == '8')
		{
			if(institution_input == '')
			{
				toastr.error('Please Select the Institution', 'Reminder');
				flag = false;
			}
			else
			{
				appy_leave_form.append('institution', institution_input);
			}

			if(num_subject_input == '')
			{
				toastr.error('Please Enter the Number of Subject', 'Reminder');
				flag = false;
			}
			else
			{
				appy_leave_form.append('num_of_subject', num_subject_input);
			}

			if($("#attachment_exam_schedule")[0]['files'][0] == undefined)
			{
				toastr.error('Please Attach Your Exam Schedule', 'Reminder');
				flag = false;
			}
			else
			{
				appy_leave_form.append('attachment_exam_schedule', $("#attachment_exam_schedule")[0]['files'][0]);
			}

			if(remain_leave_day < apply_leave_day)
			{
				toastr.error('Balances Over The Limit', 'Reminder');
				flag = false;
			}
		}

		if(leave_type == '9')
		{
			if($("#attachment_married_cert")[0]['files'][0] == undefined)
			{
				appy_leave_form.append('attachment_married_cert', '');
			}
			else
			{
				appy_leave_form.append('attachment_married_cert', $("#attachment_married_cert")[0]['files'][0]);
			}

			if(apply_leave_day>remain_leave_day)
			{
				toastr.error('Your Leave Balance Not Enough', 'Reminder');
				flag = false;
			}
		}

		if(flag)
		{
			if(leave_type != '5')
			{
				if(!(result <= -20))
				{
					$('#loadingmessage').show();

				    $.ajax({
				        type: "POST",
				        url: "<?php echo site_url('leave/submit_leave'); ?>",
				        data: appy_leave_form,
				        dataType: "json",
				        cache: false,
				        contentType: false,
				        processData: false,
				        success: function(data){
				        	$('#loadingmessage').hide();

				        	if(data['result']){
				        		$('input[name=leave_id]').val(data['data']['id']);
				        		toastr.success('Information Updated', 'Updated');
				        	}else{
				        		toastr.error('Something went wrong. Please try again later.', 'Error');
				        	}

				        	window.location = base_url + "leave";
				        }
				   	});
				}
				else
				{
					toastr.error('Annual Leave Over The Limit (-20)', 'Warning');
				}
			}
			else
			{
				$('#loadingmessage').show();

			    $.ajax({
			        type: "POST",
			        url: "<?php echo site_url('leave/submit_leave'); ?>",
			        data: appy_leave_form,
			        dataType: "json",
			        cache: false,
			        contentType: false,
			        processData: false,
			        success: function(data){
			        	$('#loadingmessage').hide();

			        	if(data['result']){
			        		$('input[name=leave_id]').val(data['data']['id']);
			        		toastr.success('Information Updated', 'Updated');
			        	}else{
			        		toastr.error('Something went wrong. Please try again later.', 'Error');
			        	}

			        	window.location = base_url + "leave";
			        }
			   	});
			}
		}

	   // e.preventDefault();
	});


    $(".update_leave").click(function(){

    	var flag = true;

    	var appy_leave_form = new FormData();
			appy_leave_form.append('employee_id', $("#employee_id").val());
			appy_leave_form.append('leave_id', $("#leave_id").val());
			appy_leave_form.append('type_of_leave_id', $("#type_of_leave").val());
			appy_leave_form.append('balance', $("#balance").val());
			appy_leave_form.append('leave_start_date', $("#start_date").val());
			appy_leave_form.append('leave_start_time', $("#leave_start_time").val());
			appy_leave_form.append('leave_end_date', $("#end_date").val());
			appy_leave_form.append('leave_end_time', $("#leave_end_time").val());
			appy_leave_form.append('leave_total_days', $("#leave_total_days").val());
			appy_leave_form.append('status', leave_data['status']);


    	var leave_type = $("#type_of_leave").val();

    	if(leave_type == '2')
		{
			if($("#attachment_medical_cert")[0]['files'][0] == undefined)
			{
				toastr.error('Please Select the Attachment', 'Reminder');
				flag = false;
			}
			else
			{
				appy_leave_form.append('attachment_medical_cert', $("#attachment_medical_cert")[0]['files'][0]);
			}
		}

    	if(leave_type == '4')
		{
			appy_leave_form.append('relation', $('#relationship').val());

			if($("#attachment_death_certificate")[0]['files'][0] == undefined)
			{
				toastr.error('Please Select the Attachment', 'Reminder');
				flag = false;
			}
			else
			{
				appy_leave_form.append('attachment_death_certificate', $("#attachment_death_certificate")[0]['files'][0]);
			}
		}

		if(leave_type == '5')
		{
			appy_leave_form.append('child_is', $('#child_is').val());
			appy_leave_form.append('perious_child_dob', perious_child_dob);

			if($("#attachment_birth_cert")[0]['files'][0] == undefined)
			{
				toastr.error('Please Attach Your Children Birth Certificate', 'Reminder');
				flag = false;
			}
			else
			{
				appy_leave_form.append('attachment_birth_cert', $("#attachment_birth_cert")[0]['files'][0]);
			}

			if($("#leave_child_dob").val() == "")
			{
				toastr.error('Please Enter Child Date Of Birth', 'Reminder');
				flag = false;
			}
			else
			{
				appy_leave_form.append('leave_child_dob', $("#leave_child_dob").val());
			}
		}

		if(leave_type == '6')
		{
			if($("#attachment_birth_cert")[0]['files'][0] == undefined)
			{
				toastr.error('Please Attach Your Children Birth Certificate', 'Reminder');
				flag = false;
			}
			else
			{
				appy_leave_form.append('attachment_birth_cert', $("#attachment_birth_cert")[0]['files'][0]);
			}

			if($("#attachment_married_cert")[0]['files'][0] == undefined)
			{
				toastr.error('Please Attach Your Married Certificate', 'Reminder');
				flag = false;
			}
			else
			{
				appy_leave_form.append('attachment_married_cert', $("#attachment_married_cert")[0]['files'][0]);
			}
		}

		if(leave_type == '7')
		{
			if($("#attachment_birth_cert")[0]['files'][0] == undefined)
			{
				toastr.error('Please Select the Attachment', 'Reminder');
				flag = false;
			}
			else
			{
				appy_leave_form.append('attachment_birth_cert', $("#attachment_birth_cert")[0]['files'][0]);
			}
		}

		if(leave_type == '8')
		{
			appy_leave_form.append('institution', $('#institution').val());
			appy_leave_form.append('num_of_subject', $('#num_of_subject').val());

			if($("#attachment_exam_schedule")[0]['files'][0] == undefined)
			{
				toastr.error('Please Select the Attachment', 'Reminder');
				flag = false;
			}
			else
			{
				appy_leave_form.append('attachment_exam_schedule', $("#attachment_exam_schedule")[0]['files'][0]);
			}
		}

		if(leave_type == '9')
		{
			if($("#attachment_married_cert")[0]['files'][0] == undefined)
			{
				toastr.error('Please Select the Attachment', 'Reminder');
				flag = false;
			}
			else
			{
				appy_leave_form.append('attachment_married_cert', $("#attachment_married_cert")[0]['files'][0]);
			}
		}

		if(flag)
		{
			$('#loadingmessage').show();

		    $.ajax({
		        type: "POST",
		        url: "<?php echo site_url('leave/submit_leave'); ?>",
		        data: appy_leave_form,
		        dataType: "json",
		        cache: false,
		        contentType: false,
		        processData: false,
		        success: function(data){
		        	$('#loadingmessage').hide();

		        	if(data['result']){
		        		$('input[name=leave_id]').val(data['data']['id']);
		        		toastr.success('Information Updated', 'Updated');
		        	}else{
		        		toastr.error('Something went wrong. Please try again later.', 'Error');
		        	}

		        	window.location = base_url + "leave";
		        }
		   	});
		}

	});


    // function check_date(startOrEnd){
    	// var start_date 	= $("#leave_start_date").data("datetimepicker").getDate();
    	// var end_date 	= $("#leave_end_date").data("datetimepicker").getDate();

    	// // formatted = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate() + " " + date.getHours + ":" + date.getMinutes() + ":" + date.getSeconds();
    	// var year 	= end_date.getFullYear() - start_date.getFullYear();
    	// var day  	= end_date.getDate()     - start_date.getDate();
    	// var hour  	= end_date.getHours()    - start_date.getHours();
    	// var minute  = end_date.getMinutes()  - start_date.getMinutes();

    // }

    function calculateBalance()
    {
    	var num_of_subject = $("#num_of_subject").val();

    	var day_per_subject = 0;

    	$.ajax({
    		'async':false,
	        type: "POST",
	        url: "<?php echo site_url('leave/get_leave_day'); ?>",
	        data: {'id':'8'},
	        success: function(data){
	        	var object = JSON.parse(data);

	        	day_per_subject = parseFloat(object[0]['days']);
	        }
	   	});

		if(num_of_subject != '')
		{
			result = parseFloat(num_of_subject) * day_per_subject;
			$("#balance").val(result);
		}
		else
		{
			$("#balance").val('0');
		}
    }

    function remove_time_option(isStart){
    	var start_date 	= $('input[name=leave_start_date]');
    	var end_date 	= $('input[name=leave_end_date]');
    	var start_time 	= $('select[name=leave_start_time] option');
    	var end_time 	= $('select[name=leave_end_time] option');

    	var start_time_val 	= $('select[name=leave_start_time]').val();
    	var end_time_val 	= $('select[name=leave_end_time]').val();

    	if(start_date.val() == end_date.val()){
	    	if(isStart){
	    		if(start_time_val == start_time.last().val()){
	    			end_time.first().hide();
	    		}else{
	    			end_time.first().show();
	    		}
	    	}else{
	    		if(end_time_val == end_time.first().val()){
	    			start_time.last().hide();
	    		}else{
	    			start_time.last().show();
	    		}
	    	}
	    }else{
	    	start_time.last().show();
	    	end_time.first().show();
	    }

	    calculate_totalDays();
    }

    function calculate_totalDays(){
    	if($('#start_date').data('DateTimePicker').date() != null && 
    		$('#end_date').data('DateTimePicker').date() != null &&
    		$("select[name=leave_start_time]").val() != null &&
    		$("select[name=leave_end_time]").val() != null){
    		
    	$('#loadingmessage').show();
		var dates = new Object();

		dates.employee_id = employee_id;
        dates.start_date = $('#start_date').data('DateTimePicker').date().format('YYYY-MM-DD');
        dates.end_date 	 = $('#end_date').data('DateTimePicker').date().format('YYYY-MM-DD');

        // dates.start_time 	 = ConvertTimeformat("24", $("select[name=start_time]").val());
        // dates.end_time 		 = ConvertTimeformat("24", $("select[name=end_time]").val());
        dates.start_time 	 = moment($("select[name=leave_start_time]").val(), "h:mm A").format("HH:mm");
        dates.end_time 		 = moment($("select[name=leave_end_time]").val(), "h:mm A").format("HH:mm");

        $.ajax({
	        type: "POST",
	        url: "<?php echo site_url('leave/calculate_working_days'); ?>",
	        data: dates,
	        dataType: "json",
	        success: function(data){
	        	$("#total_leave_apply").text(data.total_working_days);
	        	$("input[name=leave_total_days]").val(data.total_working_days);
	        	$('#loadingmessage').hide();
	        	// $('#total_remaining_al').text(data.total_remaining_al);
	        	// $("input[name=total_remaining_al]").val(data.total_working_days);

	    //     	if(data.department_id == "5" && data.start_date == "6" && data.end_date == "6"){
	    //     		$(".leave_start_time").attr("disabled", true);
					// $(".leave_end_time").attr("disabled", true);
					// $(".leave_start_time").val("10:00 AM");
					// $(".leave_end_time").val("1:00 PM");
	    //     	}
	    //     	else
	    //     	{
	    //     		$(".leave_start_time").attr("disabled", false);
					// $(".leave_end_time").attr("disabled", false);
	    //     	}
	        },
	        // error: function() { alert("Error posting feed."); }

	   	});
	   	}
    }

    // function hide_addBtn(element){
    // 	$(element).parent().parent().hide();
    // 	var date_element = $(element).parent().parent().parent().find('.date');
    // 	var remove_btn	 = $(element).parent().parent().parent().find('.removeBtn');

    // 	date_element.show();
    // 	remove_btn.show();
    // }

    // function remove_time(element){
    // 	var remove_btn = $(element).parent().hide();
    // 	var addBtn 	   = $(element).parent().parent().find('.addBtn');
    // 	var timeInput  = $(element).parent().parent().find('.date');

    // 	remove_btn.hide();
    // 	addBtn.show();
    // 	timeInput.hide();

    // 	timeInput.find("input").val('');
    // }

    var today = new Date();
    today     = new Date(today.getFullYear()+'-'+(today.getMonth()-2)+'-'+today.getDate());
    var date  = new Date(staff[0]['date_joined']);
    date      = new Date(date.getFullYear()+'-'+(date.getMonth()+1)+'-'+date.getDate());

    if((staff[0]['employee_status_id'] == 1 && date<=today) || staff[0]['office_country'] == "MALAYSIA")
    {
    	for(var i = 0 ; i < other_type_of_leave_list.length ; i++)
		{
			var x = document.getElementById("type_of_leave");
			var option = document.createElement("option");
	
			if(other_type_of_leave_list[i]['leave_name'] == 'Maternity Leave')
			{
				if(staff[0]['gender'] == 0)
				{
					option.text = other_type_of_leave_list[i]['leave_name'];
					option.value  = other_type_of_leave_list[i]['id'];
					x.add(option);
				}
			}
		}

		if(leave_data['type_of_leave_id'] != "")
		{
			$(".type_of_leave").val(leave_data['type_of_leave_id']);

			// MATERNITY LEAVE
			if(leave_data['type_of_leave_id'] == 5)
			{
				$('.Childcare_Leave').show();

				$('#leave_child_dob').datetimepicker({
			        format: 'DD/MM/YYYY',
			        date: new Date(leave_data['child_dob']),
			        useCurrent: false,
			        widgetPositioning: {
			        	vertical: 'bottom',
			        },
			        disabledDates: null
			    });

				$('#child_is').val(leave_data['child_is']).trigger('change');
				$("#child_is").attr("disabled", true);

				if(leave_data['birth_cert'] != '')
				{
					var file_result_nric = JSON.parse(leave_data['birth_cert']);
			        var filename_nric = "";

			        for(var i = 0; i < file_result_nric.length; i++)
			        {
				        if(i == 0)
				        {
				            filename_nric = '<a href="'+base_url+'uploads/leave_attachment/'+file_result_nric[i]+'" target="_blank">'+file_result_nric[i]+'</a>';
				        }
				        else
				        {
				            filename_nric = filename_nric + ", " + '<a href="'+base_url+'uploads/leave_attachment/'+file_result_nric[i]+'" target="_blank">'+file_result_nric[i]+'</a>';
				        }
			        }
			        $("#file_name_birth_cert").html(filename_nric);
			        $("#attachment_birth_cert").attr("disabled", true);
			        $(".btn_attachment_birth_cert").attr("disabled", true);
				}

				if(leave_data['birth_cert'] == '')
				{
					$('.update_leave').show();
				}
			}
		}
    }

	if(staff[0]['employee_status_id'] != 1)
	{
		for(var i = 0 ; i < other_type_of_leave_list.length ; i++)
		{
			var x = document.getElementById("type_of_leave");
			var option = document.createElement("option");

			if(other_type_of_leave_list[i]['leave_name'] == 'Maternity Leave')
			{
				if(staff[0]['gender'] == 0)
				{
					option.text = other_type_of_leave_list[i]['leave_name'];
					option.value  = other_type_of_leave_list[i]['id'];
					x.add(option);
				}
			}
			else if(other_type_of_leave_list[i]['leave_name'] == 'Paternity Leave')
			{
				if(staff[0]['marital_status'] == 1 && staff[0]['gender'] == 1)
				{
					option.text = other_type_of_leave_list[i]['leave_name'];
					option.value  = other_type_of_leave_list[i]['id'];
					x.add(option);
				}
			}
			else if(other_type_of_leave_list[i]['leave_name'] == 'Childcare Leave')
			{
				// if(staff_details[0]['nationality_id'] == 165 && staff_details[0]['gender'] == 0)
				if(staff[0]['marital_status'] == 1)
				{
					option.text = other_type_of_leave_list[i]['leave_name'];
					option.value  = other_type_of_leave_list[i]['id'];
					x.add(option);
				}
			}
			else if(other_type_of_leave_list[i]['leave_name'] == 'Wedding Leave')
			{
				if(staff[0]['marital_status'] != 1)
				{
					option.text = other_type_of_leave_list[i]['leave_name'];
					option.value  = other_type_of_leave_list[i]['id'];
					x.add(option);
				}
			}
			else
			{
				option.text = other_type_of_leave_list[i]['leave_name'];
				option.value  = other_type_of_leave_list[i]['id'];
				x.add(option);
			}
		}

		if(leave_data['type_of_leave_id'] != "")
		{
			$(".type_of_leave").val(leave_data['type_of_leave_id']);

			// SICK LEAVE
			if(leave_data['type_of_leave_id'] == 2)
			{
				$('.Sick_Leave').show();

				if(leave_data['medical_cert'] != '')
				{
					var file_result_nric = JSON.parse(leave_data['medical_cert']);
			        var filename_nric = "";

			        for(var i = 0; i < file_result_nric.length; i++)
			        {
				        if(i == 0)
				        {
				            filename_nric = '<a href="'+base_url+'uploads/leave_attachment/'+file_result_nric[i]+'" target="_blank">'+file_result_nric[i]+'</a>';
				        }
				        else
				        {
				            filename_nric = filename_nric + ", " + '<a href="'+base_url+'uploads/leave_attachment/'+file_result_nric[i]+'" target="_blank">'+file_result_nric[i]+'</a>';
				        }
			        }
			        $("#file_name_medical_cert").html(filename_nric);
			        $("#attachment_medical_cert").attr("disabled", true);
			        $(".btn_attachment_medical_cert").attr("disabled", true);
				}
				else
				{
					$('.update_leave').show();
				}
			}

			// COMPASSIONATE LEAVE
			if(leave_data['type_of_leave_id'] == 4)
			{
				$('.Compassionate_Leave').show();

				$('#relationship').val(leave_data['relation']).trigger('change');
				$(".relationship").attr("disabled", true);

				$.ajax({
		           type: "POST",
		           url: "<?php echo site_url('leave/get_relationship'); ?>",
		           data: "&id="+ leave_data['employee_id'],
		           success: function(data)
		           {
		           		var result = JSON.parse(data);

		           		$(".relationship_div").remove();
						
						var dropdown = '<div class="relationship_div"><select class="form-control relationship" id="relationship" name="relationship" style="width: 100% !important" required><option value="" selected="selected">Please Select the Relationship</option>';

		           		if(result != '')
		           		{
						    for($i=0;$i<result.length;$i++)
						    {
						    	dropdown += '<option value="'+result[$i]['id']+'">'+result[$i]['relationship_name']+' ('+result[$i]['family_name']+')</option>';
						    }
		           		}
		           		
		           		dropdown += '</select></div>';
					    $(".relationship_div_div").append(dropdown);

					    $(".relationship").select2();

					    $('#relationship').val(leave_data['relation']).trigger('change');
					    $(".relationship").attr("disabled", true);
		           	}
		       	});

				if(leave_data['death_cert'] != '')
				{
					var file_result_nric = JSON.parse(leave_data['death_cert']);
			        var filename_nric = "";

			        for(var i = 0; i < file_result_nric.length; i++)
			        {
				        if(i == 0)
				        {
				            filename_nric = '<a href="'+base_url+'uploads/leave_attachment/'+file_result_nric[i]+'" target="_blank">'+file_result_nric[i]+'</a>';
				        }
				        else
				        {
				            filename_nric = filename_nric + ", " + '<a href="'+base_url+'uploads/leave_attachment/'+file_result_nric[i]+'" target="_blank">'+file_result_nric[i]+'</a>';
				        }
			        }
			        $("#file_name_death_certificate").html(filename_nric);
			        $("#attachment_death_certificate").attr("disabled", true);
			        $(".btn_attachment_death_certificate").attr("disabled", true);
				}
				else
				{
					$('.update_leave').show();
				}
			}

			// MATERNITY LEAVE
			if(leave_data['type_of_leave_id'] == 5)
			{
				$('.Childcare_Leave').show();

				$('#leave_child_dob').datetimepicker({
			        format: 'DD/MM/YYYY',
			        date: new Date(leave_data['child_dob']),
			        useCurrent: false,
			        widgetPositioning: {
			        	vertical: 'bottom',
			        },
			        disabledDates: null
			    });

				$('#child_is').val(leave_data['child_is']).trigger('change');
				$("#child_is").attr("disabled", true);

				$("#balance").val(leave_data['balance_before_approve']);

				if(leave_data['birth_cert'] != '')
				{
					var file_result_nric = JSON.parse(leave_data['birth_cert']);
			        var filename_nric = "";

			        for(var i = 0; i < file_result_nric.length; i++)
			        {
				        if(i == 0)
				        {
				            filename_nric = '<a href="'+base_url+'uploads/leave_attachment/'+file_result_nric[i]+'" target="_blank">'+file_result_nric[i]+'</a>';
				        }
				        else
				        {
				            filename_nric = filename_nric + ", " + '<a href="'+base_url+'uploads/leave_attachment/'+file_result_nric[i]+'" target="_blank">'+file_result_nric[i]+'</a>';
				        }
			        }
			        $("#file_name_birth_cert").html(filename_nric);
			        $("#attachment_birth_cert").attr("disabled", true);
			        $(".btn_attachment_birth_cert").attr("disabled", true);
				}

				if(leave_data['birth_cert'] == '')
				{
					$('.update_leave').show();
				}
				else
				{
					$("#leave_child_dob").attr("disabled", true);
				}
			}

			// PATERNITY LEAVE
			if(leave_data['type_of_leave_id'] == 6)
			{
				$('.Childcare_Leave').show();
				$('.Wedding_Leave').show();

				if(leave_data['birth_cert'] != '')
				{
					var file_result_nric = JSON.parse(leave_data['birth_cert']);
			        var filename_nric = "";

			        for(var i = 0; i < file_result_nric.length; i++)
			        {
				        if(i == 0)
				        {
				            filename_nric = '<a href="'+base_url+'uploads/leave_attachment/'+file_result_nric[i]+'" target="_blank">'+file_result_nric[i]+'</a>';
				        }
				        else
				        {
				            filename_nric = filename_nric + ", " + '<a href="'+base_url+'uploads/leave_attachment/'+file_result_nric[i]+'" target="_blank">'+file_result_nric[i]+'</a>';
				        }
			        }
			        $("#file_name_birth_cert").html(filename_nric);
			        $("#attachment_birth_cert").attr("disabled", true);
			        $(".btn_attachment_birth_cert").attr("disabled", true);
				}

				if(leave_data['married_cert'] != '')
				{
					var file_result_nric = JSON.parse(leave_data['married_cert']);
			        var filename_nric = "";

			        for(var i = 0; i < file_result_nric.length; i++)
			        {
				        if(i == 0)
				        {
				            filename_nric = '<a href="'+base_url+'uploads/leave_attachment/'+file_result_nric[i]+'" target="_blank">'+file_result_nric[i]+'</a>';
				        }
				        else
				        {
				            filename_nric = filename_nric + ", " + '<a href="'+base_url+'uploads/leave_attachment/'+file_result_nric[i]+'" target="_blank">'+file_result_nric[i]+'</a>';
				        }
			        }
			        $("#file_name_married_cert").html(filename_nric);
			        $("#attachment_married_cert").attr("disabled", true);
			        $(".btn_attachment_married_cert").attr("disabled", true);
				}

				if(leave_data['married_cert'] == '' || leave_data['married_cert'] == '')
				{
					$('.update_leave').show();
				}
			}

			// CHILDCARE LEAVE
			if(leave_data['type_of_leave_id'] == 7)
			{
				$('.Childcare_Leave').show();

				if(leave_data['birth_cert'] != '')
				{
					var file_result_nric = JSON.parse(leave_data['birth_cert']);
			        var filename_nric = "";

			        for(var i = 0; i < file_result_nric.length; i++)
			        {
				        if(i == 0)
				        {
				            filename_nric = '<a href="'+base_url+'uploads/leave_attachment/'+file_result_nric[i]+'" target="_blank">'+file_result_nric[i]+'</a>';
				        }
				        else
				        {
				            filename_nric = filename_nric + ", " + '<a href="'+base_url+'uploads/leave_attachment/'+file_result_nric[i]+'" target="_blank">'+file_result_nric[i]+'</a>';
				        }
			        }
			        $("#file_name_birth_cert").html(filename_nric);
			        $("#attachment_birth_cert").attr("disabled", true);
			        $(".btn_attachment_birth_cert").attr("disabled", true);
				}
				else
				{
					$('.update_leave').show();
				}
			}

			// STUDY LEAVE
			if(leave_data['type_of_leave_id'] == 8)
			{
				$('.Study_Leave').show();

				$('#institution').val(leave_data['institution']).trigger('change');
				$(".institution").attr("disabled", true);

				$('#num_of_subject').val(leave_data['num_of_subject']).trigger('change');
				$(".num_of_subject").attr("disabled", true);

				if(leave_data['exam_schedule'] != '')
				{
					var file_result_nric = JSON.parse(leave_data['exam_schedule']);
			        var filename_nric = "";

			        for(var i = 0; i < file_result_nric.length; i++)
			        {
				        if(i == 0)
				        {
				            filename_nric = '<a href="'+base_url+'uploads/leave_attachment/'+file_result_nric[i]+'" target="_blank">'+file_result_nric[i]+'</a>';
				        }
				        else
				        {
				            filename_nric = filename_nric + ", " + '<a href="'+base_url+'uploads/leave_attachment/'+file_result_nric[i]+'" target="_blank">'+file_result_nric[i]+'</a>';
				        }
			        }
			        $("#file_name_exam_schedule").html(filename_nric);
			        $("#attachment_exam_schedule").attr("disabled", true);
			        $(".btn_attachment_exam_schedule").attr("disabled", true);
				}
				else
				{
					$('.update_leave').show();
				}
			}

			// MARRIED LEAVE
			if(leave_data['type_of_leave_id'] == 9)
			{
				$('.Wedding_Leave').show();

				if(leave_data['married_cert'] != '')
				{
					var file_result_nric = JSON.parse(leave_data['married_cert']);
			        var filename_nric = "";

			        for(var i = 0; i < file_result_nric.length; i++)
			        {
				        if(i == 0)
				        {
				            filename_nric = '<a href="'+base_url+'uploads/leave_attachment/'+file_result_nric[i]+'" target="_blank">'+file_result_nric[i]+'</a>';
				        }
				        else
				        {
				            filename_nric = filename_nric + ", " + '<a href="'+base_url+'uploads/leave_attachment/'+file_result_nric[i]+'" target="_blank">'+file_result_nric[i]+'</a>';
				        }
			        }
			        $("#file_name_married_cert").html(filename_nric);
			        $("#attachment_married_cert").attr("disabled", true);
			        $(".btn_attachment_married_cert").attr("disabled", true);
				}
				else
				{
					$('.update_leave').show();
				}
			}
		}

	}

</script>  