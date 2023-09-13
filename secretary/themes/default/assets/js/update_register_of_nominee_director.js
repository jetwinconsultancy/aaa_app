var nd_identification_no = {
    row: '.div_nd_id',
    validators: {
        notEmpty: {
            message: 'The Identification No. field is required.'
        }
    }
},nd_name = {
    row: '.div_nd_name',
    validators: {
        notEmpty: {
            message: 'The Name field is required.'
        }
    }
},
// nd_date_entry = {
//     row: '.div_nd_date_entry',
//     validators: {
//         notEmpty: {
//             message: 'The Date of entry/update field is required.'
//         }
//     }
// },
nomi_identification_no = {
    row: '.div_nomi_id',
    validators: {
        notEmpty: {
            message: 'The Identification No. field is required.'
        }
    }
},
nomi_name = {
    row: '.div_nomi_name',
    validators: {
        notEmpty: {
            message: 'The Name field is required.'
        }
    }
},
date_become_nominator = {
    row: '.div_date_become_nominator',
    validators: {
        notEmpty: {
            message: 'The Date on which the person becomes a nominator field is required.'
        }
    }
};

// $('#form_register_of_nominee_director').formValidation({
//     framework: 'bootstrap',
//     icon: {

//     },

//     fields: {
//         nd_identification_no: {
//             row: '.div_nd_id',
//             validators: {
//                 notEmpty: {
//                     message: 'The Identification No. field is required.'
//                 }
//             }
//         },
//         nd_name: {
//             row: '.div_nd_name',
//             validators: {
//                 notEmpty: {
//                     message: 'The Name field is required.'
//                 }
//             }
//         },
//         nd_date_entry: {
//             row: '.div_nd_date_entry',
//             validators: {
//                 notEmpty: {
//                     message: 'The Date of entry/update field is required.'
//                 }
//             }
//         },
//         nomi_identification_no: {
//             row: '.div_nomi_id',
//             validators: {
//                 notEmpty: {
//                     message: 'The Identification No. field is required.'
//                 }
//             }
//         },
//         nomi_name: {
//             row: '.div_nomi_name',
//             validators: {
//                 notEmpty: {
//                     message: 'The Name field is required.'
//                 }
//             }
//         },
//         date_become_nominator: {
//             row: '.div_date_become_nominator',
//             validators: {
//                 notEmpty: {
//                     message: 'The Date on which the person becomes a nominator field is required.'
//                 }
//             }
//         },
//     }
// });

$("#modal_nominee_director").on('hidden.bs.modal', function () {
    $('#form_register_of_nominee_director').formValidation('destroy');
    $('#form_register_of_nominee_director').formValidation('addField', 'nd_identification_no', nd_identification_no);
    $('#form_register_of_nominee_director').formValidation('addField', 'nd_name', nd_name);
    //$('#form_register_of_nominee_director').formValidation('addField', 'nd_date_entry', nd_date_entry);

    $('#form_register_of_nominee_director').formValidation('addField', 'nomi_identification_no', nomi_identification_no);
    $('#form_register_of_nominee_director').formValidation('addField', 'nomi_name', nomi_name);
    $('#form_register_of_nominee_director').formValidation('addField', 'date_become_nominator', date_become_nominator);
});

$( "#form_register_of_nominee_director" ).submit(function( e ) {
    e.preventDefault();

    $('#form_register_of_nominee_director').formValidation('revalidateField', 'nd_identification_no');
    $('#form_register_of_nominee_director').formValidation('revalidateField', 'nd_name');
    //$('#form_register_of_nominee_director').formValidation('revalidateField', 'nd_date_entry');

    $('#form_register_of_nominee_director').formValidation('revalidateField', 'nomi_identification_no');
    $('#form_register_of_nominee_director').formValidation('revalidateField', 'nomi_name');
    $('#form_register_of_nominee_director').formValidation('revalidateField', 'date_become_nominator');

    var $form = $(e.target);
    var fv = $form.data('formValidation');
    // Get the first invalid field
    var $invalidFields = fv.getInvalidFields().eq(0);
    // Get the tab that contains the first invalid field
    var $tabPane     = $invalidFields.parents();
    var valid_setup = fv.isValidContainer($tabPane);

    if(valid_setup)
    {
        $('#loadingControllerMessage').show();

        var formData = new FormData($('#form_register_of_nominee_director')[0]);
        formData.append('transaction_code', $('#transaction_code').val());
        formData.append('transaction_task_id', $('#transaction_task').val());
        formData.append('registration_no', $('#uen').val());
        formData.append('transaction_master_id', $('#transaction_trans #transaction_master_id').val());
        //formData.append('radio_individual_confirm_registrable_controller', $('input[name="individual_confirm_registrable_controller"]:checked').val());
        
        $.ajax({
            type: 'POST', //$form.serialize()
            url: "transaction/add_nominee_director",
            data: formData,
            dataType: 'json',
            // Tell jQuery not to process data or worry about content-type
            // You *must* include these options!
            // + '&user_name_text=' + $(".user_name option:selected").text()
            cache: false,
            contentType: false,
            processData: false,
            success: function(response){

                $('#loadingControllerMessage').hide();
                if (response.Status === 1) 
                {
                    var current_client_nominee_director_data = response.current_client_nominee_director_data;
                    var latest_client_nominee_director_data = response.latest_client_nominee_director_data;

                    $("#transaction_trans #transaction_master_id").val(response.transaction_master_id);
                    $("#transaction_trans #transaction_code").val(response.transaction_code);

                    $(".table_body_current_nominee_director").remove();
                    $('#current_nominee_director_table').DataTable().clear();
                    $('#current_nominee_director_table').DataTable().destroy();

                    registerNomineeDirectorInterface(current_client_nominee_director_data);

                    $(".table_body_latest_nominee_director").remove();
                    $('#latest_nominee_director_table').DataTable().clear();
                    $('#latest_nominee_director_table').DataTable().destroy();

                    if($('.transaction_task option:selected').val() == 33)
                    {
                        appointResignNomineeDirectorInterface(latest_client_nominee_director_data);
                        // if($('.transaction_task option:selected').val() == 33)
                        // {
                            add_service_engagment_row(102);
                            add_service_engagment_row(128);
                            resign_of_director_form_submit();
                        //}
                        
                    }
                    else
                    {
                        registerLatestNomineeDirectorInterface(latest_client_nominee_director_data);
                    }

                    $('.register_of_nominee_director input[name="nd_identification_no"]').prop("readonly", false);
                    $('.register_of_nominee_director input[name="nomi_identification_no"]').prop("readonly", false);
                    $('#modal_nominee_director').modal('hide');
                }
            }
        });
    }
});

$(document).on('click',"#saveRegNomineeDirector",function(e){
    $("#form_register_of_nominee_director").submit();
});