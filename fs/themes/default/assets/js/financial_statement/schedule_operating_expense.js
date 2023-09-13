$(document).on('click',"#save_schedule_operating_expenses",function(e){
    $('#loadingmessage').show();

    $.ajax({ //Upload common input
      // url: "fs_statements/save_total_by_category",
      url: "fs_statements/save_category_value",
      type: "POST",
      data: $('#form_schedule_operating_expense').serialize() + '&fs_company_info_id=' + $("#fs_company_info_id").val() + '&statement_type=schedule_operating_expense',
      dataType: 'json',
      success: function (response,data) {

        console.log(response);

        if(response['result'])
        {
          alert("Successfully updated!");
        }
        else
        {
          alert("Something went wrong! Please try again later.");
        }

        // toastr.success(response.message, response.title);

        $('#loadingmessage').hide();

          // if (response.Status === 1) 
          // {
          //   toastr.success(response.message, response.title);
          //   // $("#body_appoint_new_director .row_appoint_new_director").remove();
          //   //console.log($("#transaction_trans #transaction_master_id"));
          //   //$(".transaction_change_regis_ofis_address_id").val(response.transaction_change_regis_ofis_address_id);
          //   $("#transaction_trans #transaction_code").val(response.transaction_code);
          //   $("#transaction_trans #transaction_master_id").val(response.transaction_master_id);
          //   //$("#strike_off_form #transaction_strike_off_id").val(response.transaction_strike_off_id);
          //   //getChangeRegOfisInterface(response.transaction_change_regis_office_address);
          // }
        }
    });
});

function calculation_SOE(parent_fs_categorized_account_id)
{
  find_sub_total_lye(parent_fs_categorized_account_id, 'SOE');
  $('input[name="overall_operating_expenses_ly"]').val(get_overall_total('SOE'));
}

