<div class="header_between_all_section">
<div>
    <?php echo $breadcrumbs;?>
</div>
<div class="box" style="margin-top: 20px;margin-bottom: 50px">

    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <?php $attrib = array('id' => 'create_user_form', 'class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
                echo form_open("auth/create_access_client_user", $attrib);
                ?>
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

                            <div class="form-group">
                                <div class="col-sm-2">
                                    <label for="email" class="profile_label"><?php echo lang('email', 'email'); ?> :</label>
                                </div>
                                <div class="col-sm-5">
                                    <input type="email" id="email" name="email" class="form-control" required="required" style="width:300px;"/>
                                </div>

                            </div>
                           
                            <div class="form-group">
                                <div class="col-sm-2">
                                    <label for="client" class="profile_label">Client :</label>
                                </div>
                                <div class="col-sm-5">
                                    <select class="form-control" id="selected_client" multiple="multiple" name="selected_client[]">
                                    </select>
                                </div>
                                
                            </div>
                            <input type="hidden" id="role" name="role" class="form-control"value="4" />
                            <!-- <div class="form-group">
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
                            </div> -->

                            <div class="row">
                                <div class="col-md-8" style="margin-left: 20px;">
                                    <label class="checkbox" for="notify">
                                        <input type="checkbox"  name="notify" value="1" id="notify" checked="checked"/> <?= lang('notify_user_by_email') ?>
                                    </label>
                                </div>
                                <div class="clearfix"></div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-8" style="margin-left: 20px;">
                                    <label class="checkbox" for="notify">
                                        <input type="checkbox" name="terms" id="" onchange="activateButton(this)" value = "1">  I/We have read and agreed with the <a href="<?= base_url();?>term_and_condition" target="_blank">Terms & Conditions</a>
                                    </label>
                                </div>
                                <div class="clearfix"></div>
                            </div>

                        </div>
                        
                    </div>
                </div>
                <div class="col-md-12 text-right" style="margin-top: 10px;">
                    <?php echo form_submit('add_user', lang('add_user'), 'class="btn btn-primary"  id="create_access_client_user_submit"'); ?>
                    <a href="<?= base_url();?>auth/client/" class="btn btn-default">Cancel</a>
                </div>

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
</div>
<script type="text/javascript" charset="utf-8">
    $("#header_our_firm").removeClass("header_disabled");
    $("#header_manage_user").removeClass("header_disabled");
    $("#header_access_right").removeClass("header_disabled");
    $("#header_client_access").addClass("header_disabled");
    $("#header_user_profile").removeClass("header_disabled");
    $("#header_setting").removeClass("header_disabled");
    $("#header_dashboard").removeClass("header_disabled");
    $("#header_client").removeClass("header_disabled");
    $("#header_person").removeClass("header_disabled");
    $("#header_document").removeClass("header_disabled");
    $("#header_report").removeClass("header_disabled");
    $("#header_billings").removeClass("header_disabled");

    var error = <?php echo json_encode($error);?>;
    //console.log(error);

    toastr.options = {

      "positionClass": "toast-bottom-right"

    }

    if(error != null)
    {
        toastr.error(error, "Error");
    }

    disableSubmit();

    function disableSubmit() {
      document.getElementById("create_access_client_user_submit").disabled = true;
    }

    function activateButton(element) {
        console.log(element.checked);
      if(element.checked) {
        document.getElementById("create_access_client_user_submit").disabled = false;
       }
       else  {
        document.getElementById("create_access_client_user_submit").disabled = true;
      }

    }

    $.ajax({
        type: "GET",
        url: "auth/get_client",
        dataType: "json",
        async: false,
        success: function(data){
            if(data.tp == 1){
                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    $('#selected_client').append(option);
                });
            }
            else{
                alert(data.msg);
            }
        }               
    });



    $(document).ready(function () {
        $('#create_user_form')
            .find('[name="selected_client[]"]')
                .multiselect({
                    buttonWidth: '300px',
                    maxHeight: 200,
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    filterPlaceholder: 'Search ...',
                    nonSelectedText: 'Check an option!',
                    numberDisplayed: 1,
                    buttonText: function(options, select) {
                        if (options.length === 0) {
                            return 'Select the client';
                        }
                        else if (options.length > 1) {
                            return 'More than 1 client selected!';
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
                        //console.log($('#create_user_form').bootstrapValidator('revalidateField', 'selected_client'));
                        $('#create_user_form').bootstrapValidator('revalidateField', 'selected_client[]');
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
                    'selected_client[]': {
                        validators: {
                            callback: {
                                message: 'Please choose at least one client.',
                                callback: function(value, validator, $field) {
                                    // Get the selected options
                                    var options = validator.getFieldElements('selected_client[]').val();
                                    console.log(validator);
                                    return (options != null
                                        && options.length >= 1);
                                }
                            }
                        }
                    },
                    // password: {
                    //     validators: {
                    //         notEmpty: {
                    //           message: 'The password is required'
                    //         },
                    //         identical: {
                    //             field: 'confirm_password',
                    //             message: 'The password and its confirm are not the same'
                    //         }
                    //     }
                    // },
                    // confirm_password: {
                    //     validators: {
                    //         notEmpty: {
                    //           message: 'The confirm password is required'
                    //         },
                    //         identical: {
                    //             field: 'password',
                    //             message: 'The password and its confirm are not the same'
                    //         }
                    //     }
                    // },
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

        $( '#create_access_client_user_submit', $this ).attr( 'disabled', !formIsValid );
    });


    
</script>