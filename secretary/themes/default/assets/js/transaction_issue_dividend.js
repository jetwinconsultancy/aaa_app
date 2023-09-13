$(document).on('click',"#submitIssueDividendInfo",function(e){
    $('#loadingmessage').show();
    if($('input[name="address_type"]:checked').val() == "Registered Office Address")
    {
      $("#tr_registered_edit input").removeAttr('disabled');
    }
    else
    {
      $("#tr_registered_edit input").attr('disabled', 'true');
    }
    $.ajax({ 
      url: "transaction/add_issue_dividend",
      type: "POST",
      data: $('form#issue_dividend_form').serialize()+ '&transaction_code=' + $('#transaction_code').val() + '&transaction_task_id=' + $('#transaction_task').val() + '&registration_no=' + $('#uen').val(),
      dataType: 'json',
      success: function (response,data) {
          $('#loadingmessage').hide();
          if (response.Status === 1) 
          {
            toastr.success(response.message, response.title);
            if($('input[name="address_type"]:checked').val() == "Registered Office Address")
            {
              $("#tr_registered_edit input").attr('disabled', 'true');
            }
            $("#transaction_trans #transaction_code").val(response.transaction_code);
            $("#transaction_trans #transaction_master_id").val(response.transaction_master_id);
            $("#issue_dividend_form #transaction_issue_dividend_id").val(response.transaction_issue_dividend_id);
          }
        }
    });
});