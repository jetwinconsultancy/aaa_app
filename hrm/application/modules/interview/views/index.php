<script src="<?= base_url() ?>assets/vendor/jquery-datatables/media/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
<script src="<?= base_url() ?>node_modules/bootbox/bootbox.min.js"></script>
<script src="<?= base_url() ?>node_modules/bootbox/bootbox.all.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>application/css/plugin/toastr.min.css" />
<script src="<?= base_url() ?>application/js/toastr.min.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>node_modules/bootstrap-multiselect/dist/css/bootstrap-multiselect.css" />
<script src="<?= base_url() ?>node_modules/bootstrap-multiselect/dist/js/bootstrap-multiselect.js"></script>
<script src="<?= base_url()?>application/js/bootstrapValidator.min.js" /></script>

<style>
	a { color: var(--main-theme-color); }
	a:hover, a:focus { color: var(--main-theme-color); }
	a:active { color: var(--main-theme-color); }

	.modal-header .close { display:none; }
</style>
<!-- 
<link rel="stylesheet" href="<?= base_url() ?>node_modules/bootstrap-switch-master/dist/css/bootstrap3/bootstrap-switch.css" />
<script src="<?= base_url() ?>node_modules/bootstrap-switch-master/dist/js/bootstrap-switch.js"></script> -->


<section class="panel" style="margin-top: 30px;">
	<header class="panel-heading">
		<div class="panel-actions">
			<a class="create_client themeColor_purple" href="interview/create" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Create Interview" ><i class="fa fa-plus-circle themeColor_purple" style="font-size:16px;height:45px;"></i> Create Interview</a>
		</div>
		<h2></h2>
	</header>
	<div class="panel-body">
		<div class="col-md-12">
			<div class="row datatables-header form-inline">
				
				<div id="buttonclick" style="display:block;padding-top:10px;table-layout: fixed;width:100%">
				<table class="table table-bordered table-striped mb-none" id="datatable-default" style="width:100%">
					<thead>
						<tr style="background-color:white;">
							<th class="text-left">Interview no</th>
							<th class="text-left">Applicant Name</th>
							<th class="text-left">Date & Time</th>
							<th class="text-center">Interview Status</th>
							<th class="text-center">Interview Result</th>
							<th class="text-center">Offer Letter</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							foreach($interview_list as $key=>$row)
				  			{
				  				echo '<tr>';
				  				echo '<td><a href="interview/edit_interview/'.$row->interview_id.'">'.$row->interview_no.'</a></td>';
				  				// echo '<td><a href="applicant/form/'.$row->applicant_id.'" target="_blank">'.$row->name.'</a></td>';
				  				echo '<td><a href="applicant/applicant_profile/'.$row->applicant_id.'" target="_blank">'.$row->name.'</a></td>';
				  				echo '<td>'.$row->interview_time.'</td>';

				  			// 	if($row->status != 2){
				  			// 		echo '<td>'.
				  			// 			form_dropdown('interview_status', $interview_status, $row->status, 'onchange="change_interview_status(this,'. $row->interview_id .', '.$key.')" style="width:100%;" class="select2" ')
				  			// 		.'</td>';

				  			// 		echo '<td align="center">-</td>';
				  			// 	}else{
				  			// 		echo '<td>'.
				  			// 			form_dropdown('interview_status', $interview_status, $row->status, 'style="width:100%;" class="select2" disabled')
				  			// 		.'</td>';

									// echo '<td>'.
				  			// 			form_dropdown('interview_result', $interview_result, $row->result, 'onchange="change_interview_result(this,'. $row->interview_id .', '. $row->result .')" style="width:100%;" class="select2"')
				  			// 		.'</td>';
				  			// 	}

				  				if($row->status == 1){
				  					echo '<td>'.
				  						form_dropdown('interview_status', $interview_status, $row->status, 'onchange="change_interview_status(this,'. $row->interview_id .', '.$key.')" style="width:100%;" class="select2" ')
				  					.'</td>';

				  					echo '<td align="center">-</td>';
				  				}
				  				else if($row->status == 2)
				  				{
				  					echo '<td>'.
				  						form_dropdown('interview_status', $interview_status, $row->status, 'style="width:100%;" class="select2" disabled')
				  					.'</td>';

									echo '<td>'.
				  						form_dropdown('interview_result', $interview_result, $row->result, 'onchange="change_interview_result(this,'. $row->interview_id .', '. $row->result .')" style="width:100%;" class="select2"')
				  					.'</td>';
				  				}
				  				else if($row->status == 3)
				  				{
				  					echo '<td>'.
				  						form_dropdown('interview_status', $interview_status, $row->status, 'style="width:100%;" class="select2" disabled')
				  					.'</td>';

				  					echo '<td align="center">-</td>';
				  				}

				  				
				  				if($row->result == 2){
				  					echo '<td style="text-align:center;"><button type="button" class="btn btn_purple" onclick=confirmationOL("'.$row->applicant_id.'")>Offer Letter</button></td>';
				  				}else{
				  					echo '<td style="text-align:center;"> - </td>';
				  				}
				  				echo '</tr>';
				  			}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</section>

<!-- Modal -->
<div class="modal fade" id="offer_letter_modal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    	<form id="offer_letter" method="POST">
	    	<div class="modal-header">
	          	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          	<span aria-hidden="true">&times;</span>
		    	</button>
	          	<h4 class="modal-title">Offer Letter Details</h4>
	        </div>
	      <!-- <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">Send Offer Letter</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div> -->
	      	<div class="modal-body"></div>
	      	<div class="modal-footer">
	        	<button type="button" class="btn btn-secondary cancel" data-dismiss="modal">Close</button>
	        	<button type="submit" class="btn btn_purple">Save</button>
	     	</div>
		</form>
    </div>
  </div>
</div>

<div id="create_user" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;" data-backdrop="static">
	<div class="modal-dialog" style="width: 900px !important;">
		<div class="modal-content">

			<div class="modal-header">
	          	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          	<span aria-hidden="true">&times;</span>
		    	</button>
	          	<h4 class="modal-title">Create User</h4>
	        </div>

	        <div class="modal-body">
	        	<form id="create_user_form" data-toggle="validator" autocomplete="off">
	        		<input type="hidden" id="interview_id">
		        	<div class="form-group">
	                    <div class="col-sm-2">
	                        <label for="first_name" class="profile_label">First Name :</label>
	                    </div>
	                    <div class="col-sm-5">
	                        <input type="text" class="form-control" id="first_name" name="first_name" required="required" style="width: 300px;"/>
	                    </div>
	                </div>

	                <div class="form-group">
	                    <div class="col-sm-2">
	                        <label for="last_name" class="profile_label">Last Name :</label>
	                    </div>
	                    <div class="col-sm-5">
	                        <input type="text" class="form-control" id="last_name" name="last_name" required="required" style="width: 300px;"/>
	                    </div>
	                </div>

	                <div class="form-group">
	                    <div class="col-sm-2">
	                        <label for="last_name" class="profile_label">Email :</label>
	                    </div>
	                    <div class="col-sm-5">
	                        <input type="text" class="form-control" id="email" name="email" required="required" style="width: 300px;"/>
	                    </div>
	                </div>

	                <div class="form-group">
		                <div class="col-sm-2">
		                    <label for="role" class="profile_label">Role :</label>
		                </div>
		                <div class="col-sm-5">
		                        <select class="form-control" style="text-align:right;width: 300px;" name="role" id="role">
		                            <option value="0" >Select Role</option>
		                        </select>
		                </div>
		            </div>

		            <div class="form_group has-success manager_in_charge_div"></div>

	                <div class="form-group">
	                    <div class="col-sm-2">
	                        <label for="department" class="profile_label">Department:</label>
	                    </div>
	                    <div class="col-sm-5">
	                            <select class="form-control" style="text-align:right;width: 300px;" name="department" id="department">
	                                <option value="0" >Select Department</option>
	                            </select>
	                        
	                    </div>
	                </div>

	                <div class="form-group">
	                    <div class="col-sm-2">
	                        <label for="firm" class="profile_label">Firm :</label>
	                    </div>
	                    <div class="col-sm-5">
	                        <select class="form-control" id="selected_firm" multiple="multiple" name="selected_firm[]">
	                        </select>
	                    </div> 
	                </div>

	                <div class="form-group">
	                    <div class="col-sm-2">
	                        <label for="password" class="profile_label">Password :</label>
	                    </div>
	                    <div class="col-sm-5">
	                        <input type="password" class="form-control tip" id="password" name="password" required="required" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" style="width: 300px;"/>
	                        <span class="help-block"></span>
	                    </div>
	                </div>

					<div class="form-group">
	                    <div class="col-sm-2">
	                        <label for="confirm_password" class="profile_label">Confirm Password :</label>
	                    </div>
	                    <div class="col-sm-5">

	                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required="required" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" style="width: 300px;"/>
	                    </div>
	                </div>

	                <div>
	                    <div class="col-md-8" style="margin-left: 20px;">
	                        <label class="checkbox" for="notify">
	                            <input type="checkbox"  name="notify" value="1" id="notify" checked="checked"/> Notify User by Email
	                        </label>
	                    </div>
	                    <div class="clearfix"></div>
	                </div>

		        	<div class="form-group">
	                    <div class="col-md-8" style="margin-left: 20px;">
	                        <label class="checkbox" for="notify">
	                            <input type="checkbox" name="terms" id="" onchange="activateButton(this)" value = "1">  I/We have read and agreed with the <a href="<?php echo base_url('../secretary/term_and_condition');?>" target="_blank">Terms & Conditions</a>
	                        </label>
	                    </div>
	                    <div class="clearfix"></div>
	                </div>
	            </form>
	        </div>

	        <div class="modal-footer">
	        	<button type="submit" id="create_user_submit" class="btn btn_purple" disabled>Submit</button>
				<input type="button" id="" class="btn btn-default cancel" data-dismiss="modal" name="" value="Cancel">
	        </div>

		</div>
	</div>
</div>

<div class="loading" id='loadingmessage' style='display:none;z-index: 9999 !important'>Loading&#8230;</div>

<script>

	// $('form#create_user_form').submit(function(e) {
	$(document).on('click',"#create_user_submit",function()
	{
		document.getElementById("create_user_submit").disabled = true;

	    var form = $('#create_user_form');

	   	if ($('#create_user_form').bootstrapValidator('validate').has('.has-error').length)
	   	{
        	// document.getElementById("create_user_submit").disabled = true;
      	}
      	else
      	{
      		$.ajax({
		        type: "POST",
		        url: "<?php echo site_url('interview/create_user'); ?>",
		        data: form.serialize(),
		        dataType: "html",
		        'async':false,
		        success: function(data)
		        {
					$.ajax({
						type: "POST",
				        url: "<?php echo site_url('interview/create_employee_details'); ?>",
				        data: {'email': $("#email").val(), 'interview_id': $("#interview_id").val(), 'result': '3'},
				        dataType: "html",
				        'async':false,
				        success: function(data)
				        {
							if(data[0]["status"] == "created"){
								toastr.success('Information Updated', 'Updated');
							}else{
								toastr.error('Something went wrong. Data is failed to create/save. Please try again later.', 'Error');
							}

							setTimeout(function(){location.reload();}, 500);
				        }
					});

		        	// var user = '';
		        	// var applicant = '';

					// // GET DATA FROM USERS TABLE
		        	// $.ajax({
				    //     type: "POST",
				    //     url: "<?php echo site_url('interview/get_user_data'); ?>",
				    //     data: {'email': $("#email").val()},
				    //     dataType: "html",
				    //     'async':false,
				    //     success: function(data)
				    //     {
				    //     	user = JSON.parse(data);
				    //     }
				    // });

		        	// // GET DATA FROM APPLICANT & OFFER_LETTER TABLE
		        	// $.ajax({
				    //     type: "POST",
				    //     url: "<?php echo site_url('interview/get_applicant_data'); ?>",
				    //     data: {'interview_id': $("#interview_id").val()},
				    //     dataType: "html",
				    //     'async':false,
				    //     success: function(data)
				    //     {
				    //     	applicant = JSON.parse(data);
				    //     }
				    // });

		        	// var data = new FormData();
			     	// data.append('user_id', user[0]['id']);
					// // data.append('hidden_telephone', '');
					// data.append('staff_id','');
					// data.append('staff_name', applicant[0]['name']);
					// data.append('staff_nric_finno', applicant[0]['ic_passport_no']);
					// data.append('hidden_singapore_pr', applicant[0]['is_pr_singaporean']);
					// data.append('staff_address', applicant[0]['address']);
					// data.append('staff_nationality', applicant[0]['nationality_id']);
					// data.append('staff_DOB', applicant[0]['dob']);
					// data.append('applicant_preview_pic', applicant[0]['pic']);

					// if(applicant[0]['gender'] == 'Male')
					// {
					// 	data.append('hidden_gender', 1);
					// }
					// else
					// {
					// 	data.append('hidden_gender', 0);
					// }

					// data.append('hidden_marital_status', '');
					// data.append('firm_id', '');
					// data.append('staff_joined', applicant[0]['effective_from']);
					// data.append('staff_cessation', '');
					// data.append('staff_designation', applicant[0]['designation']);
					// data.append('staff_department', applicant[0]['department']);
					// data.append('staff_office', '');
					// data.append('staff_workpass', '');
					// data.append('staff_pass_expire', '');
					// data.append('hidden_staff_aws_given', '');
					// data.append('staff_cpf_employee', '');
					// data.append('staff_cpf_employer', '');
					// data.append('staff_cdac', '');
					// data.append('staff_remark', '');
					// data.append('staff_supervisor', '');
					// data.append('date_of_letter', applicant[0]['date_offer']);
					// data.append('status_date', '');

					// var active = new Array("1", "3", "2");
					// var leave_days = new Array(applicant[0]['vacation_leave'], "60.0", "14.0");

					// data.append('active', JSON.stringify(active));
					// data.append('leave_days', JSON.stringify(leave_days));
					// data.append('previous_staff_status', '');
					// data.append('staff_status', '1');
					// data.append('previous_status_date', '');

			     	// $.ajax({
				    //     type: "POST",
				    //     url: "<?php echo site_url('employee/create_employee'); ?>",
				    //     data: data,
				    //     dataType: 'json',
			        //     cache: false,
			        //     contentType: false,
			        //     processData: false,
				    //     success: function(data)
				    //     {
				        	
				    //     }
				   	// });

				   	// $.post(base_url + "interview/change_interview_result", {'interview_id': $("#interview_id").val(), 'result': '3' }, function(data, status){
					// 	if(data){}
					// });

			     	// location.reload();
		        }
		   	});
      	}

	});

	$(document).ready(function () {
        $('#create_user_form')
            .find('[name="selected_firm[]"]')
                .multiselect({
                    buttonWidth: '300px',
                    maxHeight: 200,
                    buttonText: function(options, select) {
                        if (options.length === 0) {
                            return 'Select the Firm';
                        }
                        else if (options.length > 1) {
                            return 'More than 1 firm selected!';
                        }
                        else {
                             var labels = [];
                             options.each(function() {
                                 if ($(this).attr('label') !== undefined) {
                                     labels.push($(this).attr('label'));
                                 }
                                 else {
                                     labels.push($(this).html());
                                 }
                             });
                             return labels.join(', ') + '';
                        }
                    },
                    // Re-validate the multiselect field when it is changed
                    onChange: function(element, checked) {
                        $('#create_user_form').bootstrapValidator('revalidateField', 'selected_firm[]');
                    }
                })
                .end()
            .bootstrapValidator({
                framework: 'bootstrap',
                excluded: ':disabled',
                submitButtons: 'input[type="submit"]',
                fields: {
                    first_name: {
                        validators: {
                            notEmpty: {
                                message: 'The first name is required'
                            }
                        }
                    },
                    last_name: {
                        validators: {
                            notEmpty: {
                                message: 'The last name is required'
                            }
                        }
                    },
                    email: {
                        validators: {
                            notEmpty: {
                              message: 'The email is required'
                            },
                            regexp: {
                              regexp: '^[^@\\s]+@([^@\\s]+\\.)+[^@\\s]+$',
                              message: 'The value is not a valid email address'
                            },
                            remote: {
		                        message: 'This email already exists',
		                        url: 'interview/email_duplicate_validation',
		                    }
                      	}
                    },
                    role: {
                        validators: {
                            callback: {
                                message: 'The role is required',
                                callback: function(value, validator, $field) {
                                    //var num = jQuery($field).parent().parent().parent().attr("num");
                                    var options = validator.getFieldElements('role').val();
                                    //console.log("options====="+options);
                                    return (options != null && options != "0");
                                }
                            }
                        }
                    },
                    department: {
                        validators: {
                            callback: {
                                message: 'The department is required',
                                callback: function(value, validator, $field) {
                                    //var num = jQuery($field).parent().parent().parent().attr("num");
                                    var options = validator.getFieldElements('department').val();
                                    //console.log("options====="+options);
                                    return (options != null && options != "0");
                                }
                            }
                        }
                    },
                    'selected_firm[]': {
                        validators: {
                            callback: {
                                message: 'Please choose at least one firm.',
                                callback: function(value, validator, $field) {
                                    // Get the selected options
                                    var options = validator.getFieldElements('selected_firm[]').val();
                                    // console.log(validator);
                                    return (options != null
                                        && options.length >= 1);
                                }
                            }
                        }
                    },
                    password: {
                        validators: {
                            notEmpty: {
                              message: 'The password is required'
                            },
                            identical: {
                                field: 'confirm_password',
                                message: 'The password and its confirm are not the same'
                            }
                        }
                    },
                    confirm_password: {
                        validators: {
                            notEmpty: {
                              message: 'The confirm password is required'
                            },
                            identical: {
                                field: 'password',
                                message: 'The password and its confirm are not the same'
                            }
                        }
                    },
                    terms: {
                          validators: {
                            notEmpty: {
                              message: 'The Term & Condition is required'
                            }
                      }
                    }
                }
            });
    });

	function activateButton(element) 
	{
		if(element.checked) 
		{
			document.getElementById("create_user_submit").disabled = false;
		}
		else 
		{
			document.getElementById("create_user_submit").disabled = true;
		}
    }



    // $( '#create_user_form' ).on( 'status.field.bv', function( e, data ) {
    //     let $this = $( this );
    //     let formIsValid = true;
        
    //     $( '.form-group', $this ).each( function() {
    //         formIsValid = formIsValid && $( this ).hasClass( 'has-success' );
    //     });

    //     $( '#create_user_submit', $this ).attr( 'disabled', !formIsValid );
    // });

    $.ajax({
        type: "GET",
        url: "interview/get_firm",
        dataType: "json",
        async: false,
        success: function(data){
            if(data.tp == 1){
                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    $('#selected_firm').append(option);
                });
            }
            else{
                alert(data.msg);
            }
        }               
    });

    $.ajax({
        type: "GET",
        url: "interview/get_department",
        dataType: "json",
        async: false,
        success: function(data){
            if(data.tp == 1){
                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    $('#department').append(option);
                });
            }
            else{
                alert(data.msg);
            }
        }               
    });

    $.ajax({
        type: "GET",
        url: "interview/get_group",
        dataType: "json",
        async: false,
        success: function(data){
            if(data.tp == 1){
                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    $('#role').append(option);
                });
            }
            else{
                alert(data.msg);
            }
        }               
    });

    $(document).on('change',"#role",function() {
        $role = $("#role option:selected").text();
        $role_value = $("#role option:selected").val();
        $.ajax({
            type: "GET",
            url: "interview/get_manager_name",
            success: function(response){
                response = JSON.parse(response);
                // console.log(response.result.length);
                if(response.result.length != 0)
                {
                    if($role_value == 3 || $role_value == 6)
                    {
                        $(".manager_in_charge_div_chill").remove(); 
                        $(".manager_in_charge_div").removeAttr( 'style' );

                        $a = "";
                        $a = '<div class="col-sm-2 manager_in_charge_div_chill"><label for="manager_in_charge" class="profile_label">Manager In Charge:</label></div><div class="col-sm-10 manager_in_charge_div_chill"><select class="form-control" style="text-align:right;width: 300px;" name="manager_in_charge" id="manager_in_charge"></select></div>';

                        $(".manager_in_charge_div").append($a); 
                        $(".manager_in_charge_div").attr("style","margin-bottom: 65px;");

                        $.each(response.result, function(key, val) {
                            var option = $('<option />');
                            option.attr('value', key).text(val);
                            
                            $("#manager_in_charge").append(option);
                        });
                    }
                    else
                    {
                        $(".manager_in_charge_div_chill").remove(); 
                        $(".manager_in_charge_div").removeAttr( 'style' );
                    }
                }
            }
        });
    });

    $(".cancel").click(function(){
		location.reload();
	});

	$(document).ready( function (){

		$(".select2").select2();

	    var table = $('#datatable-default').DataTable( {
	    	"dom": "<'row'<'col-sm-6'l><'col-sm-6'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>"
	    } );
		
	});

	var base_url ='<?php echo base_url() ?>';

	$(document).ready(function() {
		$('.dataTables_filter input').addClass('form-control');
		$('.dataTables_filter input').attr('placeholder', 'Search');
	});

	function confirmationOL(id){
		$.post("./offer_letter/sendOL_NewEmployee", { 'id': id }, function(data, status){
			$('.modal-body').empty();
            $('.modal-body').prepend(data);
            $('#offer_letter_modal').modal('show');
        });
	}

	function change_interview_status(element, interview_id, row_index)
	{
		var choice = $(element).val();

		bootbox.confirm({
	        message: "<b>Confirm want to change the interview status ??</b>",
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
	        callback: function (result){
	        	if(result == true)
	        	{
	        		$.post(base_url + "interview/change_interview_status", {'interview_id': interview_id, 'status' : choice }, function(data, status)
	        		{
						if(data)
						{
							location.reload();
						}
					});
	        	}
	        	else
	        	{
	        		$(element).select2('destroy'); 
	        		$(element).val(1);
	        		$(element).select2(); 
	        	}
	        }
	    });
	}

	function change_interview_result(element, interview_id, ori_value){
		var choice = $(element).val();

		if(choice != 3)
		{
			bootbox.confirm({
		        message: "<b>Confirm the interview result ??</b>",
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
		        callback: function (result){
		        	if(result == true)
		        	{
		        		$.post(base_url + "interview/change_interview_result", {'interview_id': interview_id, 'result': choice }, function(data, status){
							if(data){
								location.reload();
							}
						});
		        	}
		        	else
		        	{
		        		$(element).select2('destroy'); 
		        		$(element).val(ori_value);
		        		$(element).select2(); 
		        	}
		        }
		    });

		}
		else
		{
			var check_result = '';

			// GET DATA FROM APPLICANT & OFFER_LETTER TABLE
        	$.ajax({
		        type: "POST",
		        url: "<?php echo site_url('interview/get_applicant_data'); ?>",
		        data: {'interview_id': interview_id},
		        dataType: "html",
		        'async':false,
		        success: function(data)
		        {
		        	check_result = JSON.parse(data);
		        }
		    });

		    if(check_result.length>0)
		    {
		    	$("#create_user").modal("show");
				$("#interview_id").val(interview_id);
		    }
		    else
		    {
		    	toastr.error('Please complete offer letter before you accept the applicant.', 'Incomplete offer letter');

		    	$(element).select2('destroy'); 
        		$(element).val(ori_value);
        		$(element).select2(); 
		    }

		}
	}

	function preview_offer_letter(applicant_id,offer_letter_id){

		$("#loadingmessage").show();

        $.post(base_url + "interview/view_offer_letter", { 'applicant_id': applicant_id }, function(data, status){

            var response = JSON.parse(data);

			$.post(base_url + "interview/save_offer_letter_attachment", { 'offer_letter_id': offer_letter_id, 'attachment': response.filename }, function(data, status){});

            window.open(
                response.link,
                '_blank'
            );

            $("#loadingmessage").hide();
        });
    }

    $('form#offer_letter').submit(function(e) {
	    var form = $(this);

	    e.preventDefault();

	    $.ajax({
	        type: "POST",
	        url: "<?php echo site_url('offer_letter/save_offer_letter'); ?>",
	        data: form.serialize(),
	        dataType: "html",
	        success: function(data){
	            // $('#feed-container').prepend(data);
	            $('input[name=offer_letter_id]').val(data);
	            $('#offer_letter_modal').modal('hide');

	            toastr.success('Information Updated', 'Updated');
				location.reload();
	        },
	        // error: function() { alert("Error posting feed."); }
	    });
	});
</script>