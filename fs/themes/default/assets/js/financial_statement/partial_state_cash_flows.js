
function delete_row(element)
{
	var tr = $(element).parent().parent();
	var deleted_row_id = tr.find('.input_id').val();

	arr_deleted_row.push(deleted_row_id);
	tr.remove();

	// console.log(deleted_row_id);
}

$(document).on('click',"#save_state_cash_flows",function(e)
{
    $('#loadingmessage').show();
    // console.log(section_flag);

    $.ajax({ //Upload common input
      url: "fs_statements/save_category_value",
      type: "POST",
      data: $('#form_state_cash_flows').serialize() + '&fs_company_info_id=' + $("#fs_company_info_id").val() + '&arr_deleted_row=' + arr_deleted_row + '&section_flag=' + JSON.stringify(section_flag),
      dataType: 'json',
      success: function (response,data) 
      {
        $('#loadingmessage').hide();

        if(response['result'])
        {
          toastr.success("The data is saved to database.", "Successfully saved");
        }
        else
        {
          toastr.error("Something went wrong! Please try again later.", "Error");
        }

        $('#state_cash_flows_modal').modal('hide');
        
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
