$(document).on('click',"#submitTakeOverSecretarialInfo",function(e){
    $('#loadingmessage').show();
    console.log("transaction_take_over_secretarial.js");

    // $.ajax({ //Upload common input
    //   url: "transaction/add_strike_off",
    //   type: "POST",
    //   data: $('form#strike_off_form').serialize()+ '&transaction_code=' + $('#transaction_code').val() + '&transaction_task_id=' + $('#transaction_task').val() + '&registration_no=' + $('#uen').val(),
    //   dataType: 'json',
    //   success: function (response,data) {
    //     $('#loadingmessage').hide();

    //       if (response.Status === 1) 
    //       {
    //         toastr.success(response.message, response.title);
    //         // $("#body_appoint_new_director .row_appoint_new_director").remove();
    //         //console.log($("#transaction_trans #transaction_master_id"));
    //         //$(".transaction_change_regis_ofis_address_id").val(response.transaction_change_regis_ofis_address_id);
    //         $("#transaction_trans #transaction_code").val(response.transaction_code);
    //         $("#transaction_trans #transaction_master_id").val(response.transaction_master_id);
    //         //$("#strike_off_form #transaction_strike_off_id").val(response.transaction_strike_off_id);
    //         //getChangeRegOfisInterface(response.transaction_change_regis_office_address);
    //       }
    //     }
    // });
});