<link rel="stylesheet" href="<?= base_url() ?>application/css/plugin/toastr.min.css" />
<script src="<?= base_url() ?>application/js/toastr.min.js"></script>
<script src="<?= base_url() ?>application/js/bootstrap-multiselect/bootstrap-multiselect.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>application/css/bootstrap-multiselect/bootstrap-multiselect.css" />
<script src="<?= base_url() ?>application/js/bootstrapValidator.min.js"></script>


<section role="main" class="content_section" style="margin-left:0;">
    <?php echo $breadcrumbs;?>
    <section class="panel" style="margin-top: 30px;">
        <div class="panel-body" style="background-color: white; border-radius: 10px;" >  
            <!-- <?php echo form_open("auth/register", 'class="login" '); ?> -->
            <?php $attrib = array('id' => 'create_user_form', 'class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
                echo form_open("auth/create_user", $attrib);
            ?>
            <fieldset class="col-sm-12"  style="margin-bottom: 20px;margin-top: 20px;">
            	<input type="hidden" name="employee_id" value="<?=$this->session->flashdata('employee_id')?$this->session->flashdata('employee_id'):'';?>">
            	
                <div class="form-group">
                    <div class="controls">
                    	<div class="col-sm-2">
                    		<label>First Name: </label>
                    	</div>
                        <div class="col-sm-5">
                            <input style="border-bottom-left-radius: 4px;border-top-left-radius: 4px;" type="text" name="first_name" class="form-control"
                               placeholder="<?= lang('first_name') ?>" value="<?php echo set_value('first_name'); ?>"/>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="controls">
                        <div class="col-sm-2">
                    		<label>Last Name: </label>
                    	</div>
                        <div class="col-sm-5">
                            <input style="border-bottom-left-radius: 4px;border-top-left-radius: 4px;" type="text" name="last_name" class="form-control"
                               placeholder="<?= lang('last_name') ?>" value="<?php echo set_value('last_name'); ?>"/>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="controls">
                        <div class="col-sm-2">
                    		<label>Email: </label>
                    	</div>
                        <div class="col-sm-5">
                            <input style="border-bottom-left-radius: 4px;border-top-left-radius: 4px;" type="email" name="email" class="form-control"
                               placeholder="<?= lang('email_address') ?>" value="<?php echo set_value('email'); ?>"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-2">
                        <label for="role" class="profile_label">Role :</label>
                    </div>
                    <div class="col-sm-5">
                        <?php echo form_dropdown('role', $users_group_dropdown, set_value('role'), 'class="form-control" id="role"'); ?>
                            <!-- <select class="form-control" style="text-align:right;width: 300px;" name="role" id="role">
                                <option value="0" >Select Role</option>
                            </select> -->
                    </div>
                </div>
                <div class="form_group has-success manager_in_charge_div">
                </div>
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
                    <div class="controls">
                        <div class="col-sm-2">
                    		<label>Password: </label>
                    	</div>
                        <div class="col-sm-5">
                            <input style="border-bottom-left-radius: 4px;border-top-left-radius: 4px;" type="password" name="password" class="form-control"
                               placeholder="<?= lang('Password') ?>" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"/>
                            <span style="color:#9349BD;display: block;font-size: 12px;margin-top: 5px;">At least 1 capital, 1 lowercase, 1 number and more than 8 characters long</span>
                        </div>
                        
                    </div>
                </div>

                <div class="form-group">
                    <div class="controls">
                        <div class="col-sm-2">
                    		<label>Confirm Password: </label>
                    	</div>
                        <div class="col-sm-5">
                            <input style="border-bottom-left-radius: 4px;border-top-left-radius: 4px;" type="password" name="password_confirm" class="form-control" placeholder="<?= lang('confirm_password') ?>" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" data-bv-identical="true" data-bv-identical-field="password" data-bv-identical-message="<?= lang('pw_not_same') ?>"/>
                            <label style="color:red;"><?php echo form_error('password_confirm'); ?></label>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="col-md-8" style="margin-left: 5px;">
                        <label class="checkbox" for="notify">
                            <input type="checkbox" name="notify" value="1" id="notify" checked="checked"/> Notify User by Email
                        </label>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="form-group">
                    <div class="col-md-8" style="margin-left: 20px;">
                        <label class="checkbox" for="notify">
                            <input type="checkbox" name="terms" id="" onchange="activateButton(this)" value = "1">  I/We have read and agreed with the <a href="<?= base_url();?>term_and_condition" target="_blank">Terms & Conditions</a>
                        </label>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <!-- <div class="form-group row">
                    <div class="controls">
                        <div class="col-sm-2">
                            <label>User Group: </label>
                        </div>
                        <div class="col-sm-5">
                            <?php echo form_dropdown('user_group', $users_group_dropdown, set_value('user_group'), 'class="form-control"'); ?>
                            <label style="color:red;"><?php echo form_error('user_group'); ?></label>
                        </div>
                    </div>
                </div> -->
        	</fieldset>
            <div class="col-md-12 text-right" style="margin-top: 10px;">
                <input type="submit" name="add_user" value="Add User" class="btn btn_purple create_user_submit" id="create_user_submit">
                <a href="<?= base_url();?>auth/users/" class="btn btn-default">Cancel</a>
            </div>
            <?php echo form_close(); ?>
        </div>
 	</section>
</section>

<script type="text/javascript">

    // if(<?php echo isset($result_status)? $result_status: 0 ?>){
    //     alert("Successfully created a new account!");

    //     window.location.href = '<?php echo base_url() ?>' + "employee/index";
    // }
    var base_url = '<?php echo base_url(); ?>';
    var error = <?php echo json_encode($error);?>;

    toastr.options = {
      "positionClass": "toast-bottom-right"
    }

    if(error != null)
    {
        toastr.error(error, "Error");
    }

    disableSubmit();

    function disableSubmit() {
      $("#create_user_submit").prop('disabled', true);
    }

    function activateButton(element) {
        
      if(element.checked) {
        
        var btn = $(".create_user_submit");
        btn.removeAttr('disabled');
        console.log(btn.removeAttr("disabled"));
       }
       else  {
        $("#create_user_submit").prop('disabled', true);
      }
    }

    $.ajax({
        type: "GET",
        url: base_url + "auth/get_firm",
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
        url: base_url + "auth/get_department",
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
                            }/*,
                            regexp: {
                              regexp: '^[^@\\s]+@([^@\\s]+\\.)+[^@\\s]+$',
                              message: 'The value is not a valid email'
                            }*/
                      }
                    }
                }
            });
    });

    $( '#create_user_form' ).on( 'status.field.bv', function( e, data ) {
        let $this = $( this );
        let formIsValid = true;
        
        $( '.form-group', $this ).each( function() {
            formIsValid = formIsValid && $( this ).hasClass( 'has-success' );
        });
        console.log(formIsValid);
        $( '#create_user_submit', $this ).attr( 'disabled', !formIsValid );
    });

    $(document).on('change',"#role",function() {
        $role = $("#role option:selected").text();
        $role_value = $("#role option:selected").val();
        $.ajax({
            type: "GET",
            url: base_url + "auth/get_manager_name",
            success: function(response){
                response = JSON.parse(response);
                console.log(response.result.length);
                if(response.result.length != 0)
                {
                    if($role_value == 3 || $role_value == 6)
                    {
                        $(".manager_in_charge_div_chill").remove(); 
                        $(".manager_in_charge_div").removeAttr( 'style' );

                        $a = "";
                        $a = '<div class="col-sm-2 manager_in_charge_div_chill" style="margin-left: -15px; margin-right: 5px;"><label for="manager_in_charge" class="profile_label">Manager In Charge:</label></div><div class="col-sm-10 manager_in_charge_div_chill"><select class="form-control" style="text-align:right;width: 300px;" name="manager_in_charge" id="manager_in_charge"></select></div>';

                        $(".manager_in_charge_div").append($a); 
                        $(".manager_in_charge_div").attr("style","margin-bottom: 65px;");

                        $.each(response.result, function(key, val) {
                            var option = $('<option />');
                            option.attr('value', key).text(val);

                            // if(claim_below_info[t]["type_id"] != null && key == claim_below_info[t]["type_id"])
                            // {
                            //     option.attr('selected', 'selected');
                            // }
                            
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

</script>