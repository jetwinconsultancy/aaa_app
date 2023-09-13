toastr.options = {
  "positionClass": "toast-bottom-right"
}

$(document).on('click',"#submitChangeCompanyNameInfo",function(e){
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
      url: "transaction/add_new_company_name",
      type: "POST",
      data: $('form#change_of_company_name_form').serialize()+ '&transaction_code=' + $('#transaction_code').val() + '&transaction_task_id=' + $('#transaction_task').val() + '&registration_no=' + $('#uen').val() + '&transaction_master_id=' + $("#transaction_trans #transaction_master_id").val(),
      dataType: 'json',
      success: function (response,data) {
        $('#loadingmessage').hide();

          if (response.Status === 1) 
          {
            if($('input[name="address_type"]:checked').val() == "Registered Office Address")
            {
              $("#tr_registered_edit input").attr('disabled', 'true');
            }
            toastr.success(response.message, response.title);
            $(".transaction_change_company_name_id").val(response.transaction_change_company_name_id);
            $("#transaction_trans #transaction_master_id").val(response.transaction_master_id);
            $("#transaction_trans #transaction_code").val(response.transaction_code);
          }
        }
    });
});