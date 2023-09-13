$(document).on('click',"#submitStrikeOffNoticeInfo",function(e){
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
      url: "transaction/save_strike_off_notice",
      type: "POST",
      data: $('form#notice_form').serialize()+ '&transaction_code=' + $('#transaction_code').val() + '&transaction_task_id=' + $('#transaction_task').val() + '&registration_no=' + $('#uen').val() + '&company_code=' + $("#w2-strike_off_form #company_code").val() + '&transaction_strike_off_id=' + $('.transaction_strike_off_id').val() + '&transaction_master_id=' + $("#transaction_trans #transaction_master_id").val(),
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
          $(".transaction_strike_off_id").val(response.transaction_strike_off_id);
          $("#transaction_trans #transaction_master_id").val(response.transaction_master_id);
          $("#transaction_trans #transaction_code").val(response.transaction_code);
        }
    }
    });
});

$(document).on('click',"#submitStrikeOffInfo",function(e){
    $('#loadingmessage').show();
    $.ajax({
      url: "transaction/add_strike_off",
      type: "POST",
      data: $('form#strike_off_form').serialize()+ '&transaction_code=' + $('#transaction_code').val() + '&transaction_task_id=' + $('#transaction_task').val() + '&registration_no=' + $('#uen').val() + '&company_code=' + $("#w2-strike_off_form #company_code").val() + '&transaction_strike_off_id=' + $('.transaction_strike_off_id').val() + '&transaction_master_id=' + $("#transaction_trans #transaction_master_id").val(),
      dataType: 'json',
      success: function (response,data) {
        $('#loadingmessage').hide();

          if (response.Status === 1) 
          {
            toastr.success(response.message, response.title);
            $(".transaction_strike_off_id").val(response.transaction_strike_off_id);
            $("#transaction_trans #transaction_code").val(response.transaction_code);
            $("#transaction_trans #transaction_master_id").val(response.transaction_master_id);
          }
        }
    });
});