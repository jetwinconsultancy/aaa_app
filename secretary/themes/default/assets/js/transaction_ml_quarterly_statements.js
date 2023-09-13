$(document).on('click',"#submitMlQuarterlyStatementsInfo",function(e){
    $('#loadingmessage').show();
    if($('.dropdown_client_name').prop('disabled'))
    {
        var tran_company_name = $(".input_client_name").val();
    }
    else
    {
        var tran_company_name = $(".dropdown_client_name :selected").text();
    }
    $.ajax({ //Upload common input
      url: "transaction/add_ml_quarterly_statements_info",
      type: "POST",
      data: $('form#ml_quarterly_statements_form').serialize()+ '&transaction_code=' + $('#transaction_code').val() + '&transaction_task_id=' + $('#transaction_task').val() + '&client_type=' + $('.client_type').val() + '&client_name=' + tran_company_name,
      dataType: 'json',
      success: function (response,data) {
        $('#loadingmessage').hide();

          if (response.Status === 1) 
          {
            toastr.success(response.message, response.title);
            // $("#body_appoint_new_director .row_appoint_new_director").remove();
            //console.log($("#transaction_trans #transaction_master_id"));
            //$(".transaction_change_FYE_id").val(response.transaction_change_FYE_id);
            $("#transaction_trans #transaction_code").val(response.transaction_code);
            $("#transaction_trans #transaction_master_id").val(response.transaction_master_id);
            //getChangeRegOfisInterface(response.transaction_change_regis_office_address);
          }
        }
    });
});