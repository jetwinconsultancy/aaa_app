$(document).on('click',"#save_state_changes_in_equity_group",function(e)
{
    $('#loadingmessage').show();
    // console.log(section_flag);

    $.ajax({ //Upload common input
      url: "fs_statements/save_category_value",
      type: "POST",
      data: $('#form_state_changes_in_equity').serialize() + '&fs_company_info_id=' + $("#fs_company_info_id").val() + '&group_company=group' +'&arr_deleted_row=' + arr_deleted_row,
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

        $('#state_changes_in_equity_modal').modal('hide');
      }
    });
});

$(document).on('click',"#save_state_changes_in_equity_company",function(e)
{
    $('#loadingmessage').show();
    // console.log(section_flag);

    $.ajax({ //Upload common input
      url: "fs_statements/save_category_value",
      type: "POST",
      data: $('#form_state_changes_in_equity').serialize() + '&fs_company_info_id=' + $("#fs_company_info_id").val() + '&group_company=company' + '&arr_deleted_row=' + arr_deleted_row,
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

        $('#state_changes_in_equity_modal').modal('hide');
      }
    });
});