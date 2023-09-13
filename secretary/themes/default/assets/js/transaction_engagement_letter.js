$(document).on('click',"#submitEngagementLetterInfo",function(e){
    $('#loadingmessage').show();

    $.ajax({ 
      url: "transaction/add_engagement_letter",
      type: "POST",
      data: $('form#engagement_letter_form').serialize() + '&transaction_code=' + $('#transaction_code').val() + '&transaction_task_id=' + $('#transaction_task').val() + '&client_name=' + encodeURIComponent($(".dropdown_client_name :selected").text()) + '&transaction_master_id=' + $("#transaction_trans #transaction_master_id").val(),
      dataType: 'json',
      success: function (response,data) {
        $('#loadingmessage').hide();

          if (response.Status === 1) 
          {
            toastr.success(response.message, response.title);

            $("#transaction_trans #transaction_code").val(response.transaction_code);
            $("#transaction_trans #transaction_master_id").val(response.transaction_master_id);
            $(".dropdown_client_name").prop("disabled", true);
          }
        }
    });
});