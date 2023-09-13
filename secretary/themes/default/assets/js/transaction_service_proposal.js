$(document).on('click',"#submitServiceProposalInfo",function(e){
    if($('.dropdown_client_name').prop('disabled'))
    {
        var tran_company_name = $(".input_client_name").val();
    }
    else
    {
        var tran_company_name = $(".dropdown_client_name :selected").text();
    }

    var dataString = $('.form_div_service_proposal :input').serialize();
    $.ajax({ 
      url: "transaction/add_service_proposal",
      type: "POST",
      data: $('form#service_proposal_form').serialize() + '&transaction_code=' + $('#transaction_code').val() + '&transaction_task_id=' + $('#transaction_task').val() + '&client_type=' + $('.client_type').val() + '&client_name=' + encodeURIComponent(tran_company_name) + '&transaction_master_id=' + $("#transaction_trans #transaction_master_id").val(),
      dataType: 'json', 
      success: function (response,data) {
        $('#loadingmessage').hide();

          if (response.Status === 1) 
          {
            toastr.success(response.message, response.title);

            $("#transaction_trans #transaction_code").val(response.transaction_code);
            $("#transaction_trans #transaction_master_id").val(response.transaction_master_id);
          }
        }
    });
});

$(document).on('click',"#changeSPContent",function(e){
    e.preventDefault();

    service_name_elm.val($('.modify_service_proposal').val());
    $('#modal_service_proposal_description').modal('toggle');
});