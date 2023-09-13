<div class="header_between_all_section">
<div>
    <?php echo $breadcrumbs;?>
</div>
<div class="box" style="margin-top: 20px;margin-bottom: 30px">
    <!-- <div class="box-header" style="height:54px;">
        <h2 class="blue"><i class="fa-fw fa fa-users"></i><?= lang('create_user'); ?></h2>
    </div> -->
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <?php $attrib = array('id' => 'create_user_form', 'class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
                echo form_open("auth/create_user", $attrib);
                ?>
                <!-- <?php echo json_encode($user_id) ?> -->
                <!-- <input type="hidden" id="admin_id" name="admin_id" value="<?= $user_id ?>"/> -->
                <div class="row">
                    <div class="col-md-12">
                        <div>
                            <div class="form-group">
                                <div class="col-sm-2">
                                    <label for="first_name" class="profile_label"><?php echo lang('first_name', 'first_name'); ?> :</label>
                                </div>
                                <div class="col-sm-5">
                                    <?php echo form_input('first_name', '', '', 'class="form-control" id="first_name" required="required" style="width:300px;" '); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-2">
                                    <label for="last_name" class="profile_label"><?php echo lang('last_name', 'last_name'); ?> :</label>
                                </div>
                                <div class="col-sm-5">
                                    <?php echo form_input('last_name', '', '', 'class="form-control" id="last_name" required="required"  style="width:300px;"'); ?>
                                </div>
                            </div>
                            <!-- <div class="form-group">
                                <?= lang('gender', 'gender'); ?>
                                <?php
                                $ge[''] = array('male' => lang('male'), 'female' => lang('female'));
                                echo form_dropdown('gender', $ge, (isset($_POST['gender']) ? $_POST['gender'] : ''), 'class="tip form-control" id="gender" data-placeholder="' . lang("select") . ' ' . lang("gender") . '" required="required"');
                                ?>
                            </div> -->

                            <!-- <div class="form-group">
                                <?php echo lang('company', 'company'); ?>
                                <div class="controls">
                                    <?php echo form_input('company', '', '', 'class="form-control" id="company" required="required"'); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <?php echo lang('phone', 'phone'); ?>
                                <div class="controls">
                                    <?php echo form_input('phone', '', '', 'class="form-control" id="phone" required="required"'); ?>
                                </div>
                            </div> -->

                            <div class="form-group">
                                <div class="col-sm-2">
                                    <label for="email" class="profile_label"><?php echo lang('email', 'email'); ?> :</label>
                                </div>
                                <div class="col-sm-5">
                                    <input type="email" id="email" name="email" class="form-control" required="required" style="width:300px;"/>
                                </div>
                                    <?php /* echo form_input('email', '', 'class="form-control" id="email" required="required"'); */ ?>

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
                            
                            <!-- <div class="form-group">
                                <?php echo lang('username', 'username'); ?>
                                <div class="controls">
                                    <input type="text" id="username" name="username" class="form-control"
                                           required="required" pattern=".{4,20}"/>
                                </div>
                            </div> -->
                            <!-- <div class="form-group">
                                <?= lang('status', 'status'); ?>
                                <?php
                                $opt = array('' => '', 1 => lang('active'), 0 => lang('inactive'));
                                echo form_dropdown('status', $opt, (isset($_POST['status']) ? $_POST['status'] : ''), 'id="status" data-placeholder="' . lang("select") . ' ' . lang("status") . '" required="required" class="form-control input-tip select" style="width:100%;"');
                                ?>
                            </div> -->
                            <div class="form-group">
                                <div class="col-sm-2">
                                    <label for="firm" class="profile_label">Firm :</label>
                                </div>
                                <div class="col-sm-5">
                                    <select class="form-control" id="selected_firm" multiple="multiple" name="selected_firm[]">
                                        <!-- <option value="cheese">Cheese</option>
                                        <option value="tomatoes">Tomatoes</option>
                                        <option value="mozarella">Mozzarella</option>
                                        <option value="mushrooms">Mushrooms</option>
                                        <option value="pepperoni">Pepperoni</option>
                                        <option value="onions">Onions</option> -->
                                    </select>
                                </div>
                                
                            </div>

                            <div class="form-group">
                                <div class="col-sm-2">
                                    <label for="password" class="profile_label"><?php echo lang('password', 'password'); ?> :</label>
                                </div>
                                <div class="col-sm-8">
                                    <?php echo form_password('password', '', '', 'class="form-control tip" id="password" required="required" style="width:300px;" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"'); ?>
                                    <span class="help-block"><?= lang('pasword_hint') ?></span>
                                </div>
                                
                            </div>

                            <div class="form-group">
                                <div class="col-sm-2">
                                    <label for="confirm_password" class="profile_label"><?php echo lang('confirm_password', 'confirm_password'); ?> :</label>
                                </div>
                                <div class="col-sm-5">
                                    <?php echo form_password('confirm_password', '', '', 'class="form-control" id="confirm_password" required="required" style="width:300px;" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"'); ?>
                                </div>
                            </div>

                             <!-- data-bv-identical="true" data-bv-identical-field="password" data-bv-identical-message="' . lang('pw_not_came') . '" -->

                            <div style="margin-left: -15px;">
                                <div class="col-md-8" style="margin-left: 20px;">
                                    <label class="checkbox" for="notify">
                                        <input type="checkbox"  name="notify" value="1" id="notify" checked="checked"/> <?= lang('notify_user_by_email') ?>
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

                        </div>
                        <!-- <div class="col-md-5 col-md-offset-1">

                            <div class="form-group">
                                <?= lang('status', 'status'); ?>
                                <?php
                                $opt = array('' => '', 1 => lang('active'), 0 => lang('inactive'));
                                echo form_dropdown('status', $opt, (isset($_POST['status']) ? $_POST['status'] : ''), 'id="status" data-placeholder="' . lang("select") . ' ' . lang("status") . '" required="required" class="form-control input-tip select" style="width:100%;"');
                                ?>
                            </div> -->
                            <!-- <div class="form-group hidden">
                                <?= lang("group", "group"); ?>
                                <?php
                                $gp[""] = "";
                                foreach ($groups as $group) {
                                    if ($group['name'] != 'customer' && $group['name'] != 'supplier') {
                                        $gp[$group['id']] = $group['name'];
                                    }
                                }
                                echo form_dropdown('group', $gp, (isset($_POST['group']) ? $_POST['group'] : ''), 'id="group" data-placeholder="' . lang("select") . ' ' . lang("group") . '" required="required" class="form-control input-tip select" style="width:100%;"');
                                ?>
                            </div> -->

                            <!-- <div class="clearfix"></div>
                            <div class="no">
                                <div class="form-group hidden">
                                    <?= lang("biller", "biller"); ?>
                                    <?php
                                    $bl[""] = "";
                                    foreach ($billers as $biller) {
                                        $bl[$biller->id] = $biller->company != '-' ? $biller->company : $biller->name;
                                    }
                                    echo form_dropdown('biller', $bl, (isset($_POST['biller']) ? $_POST['biller'] : $Settings->default_biller), 'id="biller" data-placeholder="' . lang("select") . ' ' . lang("biller") . '" required="required" class="form-control input-tip select" style="width:100%;"');
                                    ?>
                                </div>

                                <div class="form-group hidden">
                                    <?= lang("warehouse", "warehouse"); ?>
                                    <?php
                                    // $wh[''] = '';
                                    // foreach ($warehouses as $warehouse) {
                                        // $wh[$warehouse->id] = $warehouse->name;
                                    // }
                                    echo form_input('warehouse', 1, 'id="warehouse" class="form-control input-tip select" data-placeholder="' . lang("select") . ' ' . lang("warehouse") . '" required="required" style="width:100%;" ');
                                    ?>
                                </div>
                            </div> -->

                            <!-- <div class="row">
                                <div class="col-md-8">
                                    <label class="checkbox" for="notify">
                                        <input type="checkbox"  name="notify" value="1" id="notify" checked="checked"/> <?= lang('notify_user_by_email') ?>
                                    </label>
                                </div>
                                <div class="clearfix"></div>
                            </div>

                        </div> -->
                    </div>
                </div>

                <!-- <p style="margin-top: 10px;"><?php echo form_submit('add_user', lang('add_user'), 'class="btn btn-primary"  id="create_user_submit"'); ?></p> -->
                <div class="col-md-12 text-right" style="margin-top: 10px;">
                    <?php echo form_submit('add_user', lang('add_user'), 'class="btn btn-primary"  id="create_user_submit"'); ?>
                    <a href="<?= base_url();?>auth/users/" class="btn btn-default">Cancel</a>
                </div>

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
</div>
<script type="text/javascript" charset="utf-8">
    $("#header_our_firm").removeClass("header_disabled");
    $("#header_manage_user").addClass("header_disabled");
    $("#header_access_right").removeClass("header_disabled");
    $("#header_user_profile").removeClass("header_disabled");
    $("#header_setting").removeClass("header_disabled");
    $("#header_dashboard").removeClass("header_disabled");
    $("#header_client").removeClass("header_disabled");
    $("#header_person").removeClass("header_disabled");
    $("#header_document").removeClass("header_disabled");
    $("#header_report").removeClass("header_disabled");
    $("#header_billings").removeClass("header_disabled");

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
      document.getElementById("create_user_submit").disabled = true;
    }

    function activateButton(element) {
        console.log(element.checked);
      if(element.checked) {
        document.getElementById("create_user_submit").disabled = false;
       }
       else  {
        document.getElementById("create_user_submit").disabled = true;
      }

    }

    $.ajax({
        type: "GET",
        url: "auth/get_firm",
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
        url: "masterclient/get_department",
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
        url: "auth/get_group",
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
                    // manager_in_charge: {
                    //     validators: {
                    //         callback: {
                    //             message: 'The Manager In Charge is required',
                    //             callback: function(value, validator, $field) {
                    //                 //var num = jQuery($field).parent().parent().parent().attr("num");
                    //                 var options = validator.getFieldElements('manager_in_charge').val();
                    //                 //console.log("options====="+options);
                    //                 return (options != null && options != "0");
                    //             }
                    //         }
                    //     }
                    // },
                    'selected_firm[]': {
                        validators: {
                            callback: {
                                message: 'Please choose at least one firm.',
                                callback: function(value, validator, $field) {
                                    // Get the selected options
                                    var options = validator.getFieldElements('selected_firm[]').val();
                                    console.log(validator);
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

        $( '#create_user_submit', $this ).attr( 'disabled', !formIsValid );
    });

    $(document).on('change',"#role",function() {
        $role = $("#role option:selected").text();
        $role_value = $("#role option:selected").val();
        $.ajax({
            type: "GET",
            url: "auth/get_manager_name",
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

    $(document).ready(function () {
        $('#group').change(function (event) {
            var group = $(this).val();
            if (group == 1 || group == 2) {
                $('.no').slideUp();
                $('form[data-toggle="validator"]').bootstrapValidator('removeField', 'biller');
                $('form[data-toggle="validator"]').bootstrapValidator('removeField', 'warehouse');
            } else {
                $('.no').slideDown();
                $('form[data-toggle="validator"]').bootstrapValidator('addField', 'biller');
                $('form[data-toggle="validator"]').bootstrapValidator('addField', 'warehouse');
            }
        });
    });

    
</script>