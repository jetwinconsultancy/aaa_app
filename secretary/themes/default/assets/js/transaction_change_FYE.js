toastr.options = {
  "positionClass": "toast-bottom-right"
}

$(document).on('click',"#submitChangeFYEInfo",function(e){
    $('#loadingmessage').show();
    $.ajax({
      url: "transaction/add_new_fye",
      type: "POST",
      data: $('form#change_of_FYE_form').serialize()+ '&transaction_code=' + $('#transaction_code').val() + '&transaction_task_id=' + $('#transaction_task').val() + '&registration_no=' + $('#uen').val() + '&transaction_master_id=' + $("#transaction_trans #transaction_master_id").val(),
      dataType: 'json',
      success: function (response,data) {
        $('#loadingmessage').hide();

          if (response.Status === 1) 
          {
            toastr.success(response.message, response.title);
            $(".transaction_change_FYE_id").val(response.transaction_change_FYE_id);
            $("#transaction_trans #transaction_code").val(response.transaction_code);
            $("#transaction_trans #transaction_master_id").val(response.transaction_master_id);
          }
        }
    });
});