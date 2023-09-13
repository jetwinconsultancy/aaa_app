toastr.options = {
  "positionClass": "toast-bottom-right"
}

$(document).on('click',"#submitChangeFYEInfo",function(e){
    $('#loadingmessage').show();

    $.ajax({ //Upload common input
      url: "transaction/add_new_fye",
      type: "POST",
      data: $('form#change_of_FYE_form').serialize()+ '&transaction_code=' + $('#transaction_code').val() + '&transaction_task_id=' + $('#transaction_task').val() + '&registration_no=' + $('#uen').val(),
      dataType: 'json',
      success: function (response,data) {
        $('#loadingmessage').hide();

          if (response.Status === 1) 
          {
            toastr.success(response.message, response.title);
            // $("#body_appoint_new_director .row_appoint_new_director").remove();
            //console.log($("#transaction_trans #transaction_master_id"));
            $(".transaction_change_FYE_id").val(response.transaction_change_FYE_id);
            $("#transaction_trans #transaction_code").val(response.transaction_code);
            $("#transaction_trans #transaction_master_id").val(response.transaction_master_id);
            //getChangeRegOfisInterface(response.transaction_change_regis_office_address);
          }
        }
    });
});