$(document).on('click',"#submitPreviousSecretarialInfo",function(e){
    $('#loadingmessage').show();
    $.ajax({ //Upload common input
      url: "transaction/save_previous_secretarial_info",
      type: "POST",
      data: $('form#previous_secretarial_form').serialize()+ '&transaction_code=' + $('#transaction_code').val() + '&transaction_task_id=' + $('#transaction_task').val(),
      dataType: 'json',
      success: function (response,data) {
        $('#loadingmessage').hide();
          if (response.Status === 1) 
          {
            toastr.success(response.message, response.title);
            // $("#body_controller .row_controller").remove();
            // controllerInterface(response.transaction_client_controller);
          }
        }
    })

});