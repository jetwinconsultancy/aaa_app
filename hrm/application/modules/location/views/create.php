<link rel="stylesheet" href="<?=base_url()?>node_modules/fullcalendar/dist/fullcalendar.css" />
<script src="<?= base_url() ?>node_modules/fullcalendar/dist/fullcalendar.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>plugin/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css" />
<script src="<?= base_url() ?>plugin/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>application/css/plugin/toastr.min.css" />
<script src="<?= base_url() ?>application/js/toastr.min.js"></script>

<section role="main" class="content_section" style="margin-left:0;">
	<section class="panel" style="margin-top: 30px;">
		<div class="panel-body">

			<input class="form-control" id="arrangement_id" name="arrangement_id" type="hidden" value="<?=isset($arrangement_details['id'])?$arrangement_details['id']:''?>">

			<div class="form-group">
            	<div style="width: 100%;">
                	<div style="width: 25%;float:left;margin-right: 20px;">
                        <label>Employee Name :</label>
                    </div>
                    <div style="width: 30%;float: left;">
		                <!-- <?php echo form_dropdown('employee_list', $employee_list, isset($employee_list['id'])?$employee_list['id']:'', 'class="form-control select2 employee_list" required');?> -->
		                <?php 
		                	echo form_dropdown('employee', $employee_list, isset($arrangement_details['employee_id'])?$arrangement_details['employee_id']:'', 'class="form-control select2 employee" style="width:290px;"');
		                ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
            	<div style="width: 100%;">
                	<div style="width: 25%;float:left;margin-right: 20px;">
                        <label>Start Date and Time :</label>
                    </div>
                    <div style="width: 30%;float: left;">
		                <div class="input-group date form_datetime col-md-5" data-date-format="dd MM yyyy - HH:ii p" data-link-field="dtp_input1">
		                	<span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
		                	<input autocomplete="off" name="start_datetime" class="form-control start_datetime" type="text" value="<?=isset($arrangement_details['form_datetime'])?$arrangement_details['form_datetime']: '' ?>" style="width:250px;">
		                </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
            	<div style="width: 100%;">
                	<div style="width: 25%;float:left;margin-right: 20px;">
                        <label>End Date and Time :</label>
                    </div>
                    <div style="width: 30%;float: left;">
		                <div class="input-group date to_datetime col-md-5" data-date-format="dd MM yyyy - HH:ii p" data-link-field="dtp_input1">
		                	<span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
		                	<input autocomplete="off" name="end_datetime" class="form-control end_datetime" type="text" value="<?=isset($arrangement_details['to_datetime'])?$arrangement_details['to_datetime']: '' ?>" style="width:250px;">
		                </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
            	<div style="width: 100%;">
                	<div style="width: 25%;float:left;margin-right: 20px;">
                        <label>Location :</label>
                    </div>
                    <div style="width: 30%;float: left;">
                    	<div class="tab-pane" style="width: 100%;height: 30px">
							<label class="radio-inline"><input type="radio" checked name="optradio" id="optradio_OO" value="OO">Our Office</label>
							<label class="radio-inline"><input type="radio" name="optradio" id="optradio_CO" value="CO">Client Office</label>
							<label class="radio-inline"><input type="radio" name="optradio" id="optradio_WFH" value="WFH">Work From Home</label>
							<label class="radio-inline"><input type="radio" name="optradio" id="optradio_OT" value="OT">Others</label>

				        </div>
				        <div id="our_office_div">
					        <?php 
			                	echo form_dropdown('our_office', $our_office, isset($arrangement_details['arrangement_location'])?$arrangement_details['arrangement_location']:'', 'class="form-control select2 our_office" id="our_office" style="width:500px;"');
			                ?>
			            </div>
			            <div id="client_office_div">
			                <?php 
			                	echo form_dropdown('client_office', $client_office, isset($arrangement_details['arrangement_location'])?$arrangement_details['arrangement_location']:'', 'class="form-control select2 client_office" id="client_office" style="width:500px;"');
			                ?>
			            </div>
                    </div>
                </div>
            </div>

			<div class="form-group">
				<div style="width: 100%;">
					<div style="width: 25%;float:left;margin-right: 20px;">
                        <label>Location Address :</label>
                    </div>
                    <div style="float:left;margin-bottom:5px;">
                        <textarea class="form-control" id="location_address" name="location_address" style="width: 500px;height: 100px;float:left;"><?php echo (isset($arrangement_details['location_address'])?$arrangement_details['location_address']: '') ?></textarea>
                    </div>
                </div>
            </div>

            <hr>

            <div class="form-group">
                <div style="width: 100%;float:left;margin-bottom:5px;">
                	<?php 
                		echo '<a href="'.base_url().'location" class="btn pull-right btn_cancel" style="margin:0.5%; cursor: pointer;">Cancel</a>';

                		if($status != 'view')
						{
	                		if($status == 'update'){
	                			echo '<button type="submit" class="btn btn_purple pull-right" id="arrangement_update" style="margin:0.5%">Update</button>';
	                		}else{
	                			echo '<button type="submit" class="btn btn_purple pull-right" id="arrangement_create" style="margin:0.5%">Create</button>';
	                		}
	                	}
                	?>
                </div>
            </div>
		</div>
	</section>
</section>

<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>

<script>
	var status = <?php echo json_encode(isset($status)?$status:'create') ?>;
	var which_office = <?php echo json_encode(isset($arrangement_details['is_client_office'])?$arrangement_details['is_client_office']:'') ?>;

	$(document).ready( function () {

		$('#our_office_div').show();
    	$("#our_office_div").css("display","inline-block !important");
    	$("#client_office_div").css("display","none");

		$('.select2').select2();

	    $('.form_datetime').datetimepicker({
	        weekStart: 1,
	        todayBtn:  1,
			autoclose: 1,
			todayHighlight: 1,
			startView: 2,
			forceParse: 0,
	        showMeridian: 1
	    });

	    $('.to_datetime').datetimepicker({
	        weekStart: 1,
	        todayBtn:  1,
			autoclose: 1,
			todayHighlight: 1,
			startView: 2,
			forceParse: 0,
	        showMeridian: 1
	    });

	    if(which_office == '')
	    {
	    	$('#optradio_OO').attr('checked', 'checked');
	    	$('#our_office_div').show();
	    	$("#our_office_div").css("display","inline-block !important");
	    	$("#client_office_div").css("display","none");
	    }
	    else if(which_office == '1')
	    {
	    	$('#optradio_CO').attr('checked', 'checked');
	    	$('#client_office_div').show();
	    	$("#client_office_div").css("display","inline-block !important");
	    	$("#our_office_div").css("display","none");
	    	$()
	    }
		else if(which_office == '2' || which_office == '3')
	    {
			if(which_office == '2')
			{
				$('#optradio_WFH').attr('checked', 'checked');
			}
			if(which_office == '3')
			{
				$('#optradio_OT').attr('checked', 'checked');
			}
	    	$('#client_office_div').hide();
	    	$("#our_office_div").hide();

	    }
	});

	$('#arrangement_create').click(function(){
		document.getElementById('arrangement_create').disabled = "true";
		arrangement_submittion();
	});

	$('#arrangement_update').click(function(){
		document.getElementById('arrangement_update').disabled = "true";
		arrangement_submittion();
	});

	function arrangement_submittion(){
		var flag = true;

        // EMPLOYEE VALIDATION
        var employee = $(".employee").val();

		if(employee == "")
		{
			toastr.error('Please Select Employee', 'Reminder');
			flag = false;
		}
		// END EMPLOYEE VALIDATION

		// START & END DATE VALIDATION
		var start    = $(".start_datetime").val();
		var end      = $(".end_datetime").val(); 

		if(start == "" || end == "")
		{
			toastr.error('Please Provide The Date & Time', 'Reminder');
			flag = false;
		}
		else if(start == end)
		{
			toastr.error('Cannot Enter The Same Date & Time', 'Reminder');
			flag = false;
		}
		else
		{
			var time1 = start.split('-');
			var time2 = end.split('-');
			start = time1[0]+time1[1];
			end   = time2[0]+time2[1];
			start = new Date(start);
			end   = new Date(end);

			if(end < start)
			{
				toastr.error('End Date & Time > Start Date & Time', 'Reminder');
				flag = false;
			}
		}
		// END START & END DATE VALIDATION

		// LOCATION VALIDATION
		var is_client_office
		var location
		var optradio = $("[name='optradio']"); 
              
        for(i = 0; i < optradio.length; i++) 
        {       
            if(optradio[i].type="radio") 
            { 
                if(optradio[i].checked) 
                {
                    if(optradio[i].value == 'OO')
                    {
                    	is_client_office = '0';
                    }
                    else if(optradio[i].value == 'CO')
                    {
                    	is_client_office = '1';
                    }
					else if(optradio[i].value == 'WFH')
                    {
                    	is_client_office = '2';
                    }
					else if(optradio[i].value == 'OT')
                    {
                    	is_client_office = '3';
                    }
                }
            } 
        }

        if(is_client_office ==  '1')
        {
        	location = $('.client_office').val();
        }
        else if(is_client_office ==  '0')
        {
        	location = $('.our_office').val();
        }
		else if(is_client_office ==  '2' || is_client_office ==  '3')
        {
        	location = '';
        }

        if(location == "" && !(is_client_office ==  '2'|| is_client_office ==  '3'))
        {
        	toastr.error('Please Select The Location', 'Reminder');
			flag = false;
        }
        // END LOCATION VALIDATION

        // ADDRESS VALIDATION
        var address = $('#location_address').val();

        if(address == "")
        {
        	toastr.error('Please Provide The Address', 'Reminder');
			flag = false;
        }
        // END ADDRESS VALIDATION

		var arrangement_location_form = new FormData();
		arrangement_location_form.append('id', $('#arrangement_id').val());
		arrangement_location_form.append('arrangement_employee_id', employee);
		arrangement_location_form.append('arrangement_start', $(".start_datetime").val());
		arrangement_location_form.append('arrangement_end', $(".end_datetime").val());
		arrangement_location_form.append('is_client_office', is_client_office);
		arrangement_location_form.append('arrangement_location', location);
		arrangement_location_form.append('location_address', address);

		
		if(flag)
		{
			$('#loadingmessage').show();

			$.ajax({
				type: "POST",
				url:"<?php echo site_url('location/submit_arrangement'); ?>",
				data: arrangement_location_form,
				dataType: "json",
		        cache: false,
		        contentType: false,
		        processData: false,
				success: function(data)
				{	
					$('#loadingmessage').hide();
					toastr.success('Information Updated', 'Updated');
					setTimeout(function(){window.location.href= '<?php base_url() ?>/hrm/location/index';}, 500);
				}
			});
		}
	}

	$('input[type=radio][name=optradio]').change(function(){

	    if(this.value == 'OO')
	    {
	    	$('#our_office_div').show();
	    	$("#our_office_div").css("display","inline-block !important");
	    	$("#client_office_div").css("display","none");
	    }
	    else if(this.value == 'CO')
	    {
	    	$('#client_office_div').show();
	    	$("#client_office_div").css("display","inline-block !important");
	    	$("#our_office_div").css("display","none");
	    }
		else if(this.value == 'WFH' || this.value == 'OT')
	    {
	    	$('#optradio_WFH').attr('checked', 'checked');
	    	$('#client_office_div').hide();
			$('#our_office_div').hide();

			if(this.value == 'WFH')
			{
				//get employee address
				get_employee_address();
			}
			else
			{
				$('#location_address').text('');

			}
	    }
	});

	$('.employee').change(function(){

		var selectedLocation = $('input[name=optradio]:checked').val();
		if(selectedLocation == "WFH")
		{
			//get employee address
			get_employee_address();
		}
		
	});

	$('.our_office').change(function(){

	    if(this.value == "")
	    {
	    	$('#location_address').text('');
	    }
	    else
	    {
	    	$.ajax({
				type: "POST",
				// url: "location/get_firm_address",
				url:"<?php echo site_url('location/get_firm_address'); ?>",
				data: {id:this.value},
				success: function(data)
				{	
					result = JSON.parse(data);

					if(result[0]['address_type'] == "Local")
					{
						$.ajax({
							type: "POST",
							// url: "location/get_firm_address",
							url:"<?php echo site_url('location/write_address'); ?>",
							data: {
								street_name:result[0]['street_name'], 
								unit_no1:result[0]['unit_no1'], 
								unit_no2:result[0]['unit_no2'], 
								building_name:result[0]['building_name'], 
								postal_code:result[0]['postal_code'], 
								type:"comma"
							},
							success: function(data)
							{	
								$('#location_address').text(data);
							}
						});
					}
					else
					{
						$('#location_address').text(result[0]['foreign_address1']+" "+result[0]['foreign_address2']+" "+result[0]['foreign_address3']);
					}
				}
			});
	    }
	});

	$('.client_office').change(function(){

		if(this.value == "")
	    {
	    	$('#location_address').text('');
	    }
	    else
	    {
	    	$.ajax({
				type: "POST",
				// url: "location/get_firm_address",
				url:"<?php echo site_url('location/get_client_address'); ?>",
				data: {id:this.value},
				success: function(data)
				{	
					result = JSON.parse(data);

					if(result[0]['use_foreign_add_as_billing_add'] == "0")
					{
						$.ajax({
							type: "POST",
							// url: "location/get_firm_address",
							url:"<?php echo site_url('location/write_address'); ?>",
							data: {
								street_name:result[0]['street_name'], 
								unit_no1:result[0]['unit_no1'], 
								unit_no2:result[0]['unit_no2'], 
								building_name:result[0]['building_name'], 
								postal_code:result[0]['postal_code'], 
								type:"comma"
							},
							success: function(data)
							{	
								$('#location_address').text(data);
							}
						});
					}
					else
					{
						$('#location_address').text(result[0]['foreign_add_1']+" "+result[0]['foreign_add_2']+" "+result[0]['foreign_add_3']);
					}
				}
			});
	    }
	});

	function get_employee_address()
	{
		if($('.employee').val())
		{
			$.ajax({
				type: "POST",
				// url: "location/get_firm_address",
				url:"<?php echo site_url('location/get_employee_address'); ?>",
				data: {employeeId:$('.employee').val()},
				success: function(data)
				{
					$('#location_address').text(data);
				}
			});
		}
		
	}
	
</script>

<!-- <script src="<?= base_url() ?>/application/modules/location/js/postalcode.js" /></script> -->
