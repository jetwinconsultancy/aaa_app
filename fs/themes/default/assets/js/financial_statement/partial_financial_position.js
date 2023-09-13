$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip(); 
});

$(document).on('click',"#save_state_financial_statement_group",function(e)
{
    $('#loadingmessage').show();

    var total_assets                 = $("#form_financial_position .total_assets").find('.total_assets').text();
    var total_assets_end             = $("#form_financial_position .total_assets").find('.total_assets_end').text();
    // var total_assets_beg = $("#form_financial_position .total_assets").find('.total_assets_beg').text();
    var total_equity_liabilities     = $("#form_financial_position .total_equity_liabilities").find('.total_equity_liabilities').text();
    var total_equity_liabilities_end = $("#form_financial_position .total_equity_liabilities").find('.total_equity_liabilities_end').text();
    // var total_equity_liabilities_beg = $("#form_financial_position .total_equity_liabilities").find('.total_equity_liabilities_beg').text();

    if(total_assets != total_equity_liabilities || total_assets_end != total_equity_liabilities_end)
    {
      bootbox.confirm({
          message: "Account is not balanced! Are you sure you want to save it?",
          buttons: {
              confirm: {
                  label: 'Yes',
                  className: 'btn-primary'
              },
              cancel: {
                  label: 'No',
                  // className: 'btn-danger'
              }
          },
          callback: function (result) 
          {
            if(result)
            {
              $.ajax({ //Upload common input
                url: "fs_statements/save_category_value",
                type: "POST",
                data: $('#form_financial_position').serialize() + '&fs_company_info_id=' + $("#fs_company_info_id").val() + '&statement_type=financial_position' + '&group_company=group' + '&fs_ntfs_layout_template_list=' + JSON.stringify(fs_ntfs_layout_template_list),
                dataType: 'json',
                success: function (response,data) 
                {
                  $('#loadingmessage').hide();

                  if(response['result'])
                  {
                    toastr.success("The data is saved to database.", "Successfully saved");

                    $('#state_financial_position_modal').modal('hide');
                  }
                  else
                  {
                    toastr.error("Something went wrong! Please try again later.", "Error");
                  }
                }
              });
            }
            else
            {
              $('#loadingmessage').hide();
            }
          }
      });
    }
    else
    {
      $.ajax({ //Upload common input
        url: "fs_statements/save_category_value",
        type: "POST",
        data: $('#form_financial_position').serialize() + '&fs_company_info_id=' + $("#fs_company_info_id").val() + '&statement_type=financial_position' + '&group_company=group' + '&fs_ntfs_layout_template_list=' + JSON.stringify(fs_ntfs_layout_template_list),
        dataType: 'json',
        success: function (response,data) 
        {
          $('#loadingmessage').hide();

          if(response['result'])
          {
            toastr.success("The data is saved to database.", "Successfully saved");

            $('#state_financial_position_modal').modal('hide');
          }
          else
          {
            toastr.error("Something went wrong! Please try again later.", "Error");
          }
        }
      });
    }
});

$(document).on('click',"#save_state_financial_statement_company",function(e)
{
    $('#loadingmessage').show();

    $.ajax({ //Upload common input
      url: "fs_statements/save_category_value",
      type: "POST",
      data: $('#form_financial_position').serialize() + '&fs_company_info_id=' + $("#fs_company_info_id").val() + '&group_company=company' + '&fs_ntfs_layout_template_list=' + JSON.stringify(fs_ntfs_layout_template_list),
      dataType: 'json',
      success: function (response,data) {

        console.log(response);

        if(response['result'])
        {
          // alert("Successfully updated!");
          toastr.success("The data is saved to database.", "Successfully saved");
        }
        else
        {
          // alert("Something went wrong! Please try again later.");
          toastr.error("Something went wrong! Please try again later.", "Error");
        }

        $('#state_financial_position_modal').modal('hide');

        $('#loadingmessage').hide();

        }
    });
});

function FP_calculation(parent_fs_categorized_account_id, type, description_type, group_company)
{
	var subtotal = find_sub_total_lye(parent_fs_categorized_account_id, type);

	// console.log(type);

	var total_assets = 0.00;
	var total_equity = 0.00;
	var total_liabilities = 0.00;
	var total_equity_and_liabilities = 0.00;

	// DISPLAY AND CALCULATE TOTAL ASSETS
	if(description_type == "Assets")
	{
		// total_assets = parseFloat(find_sub_total_by_classname(type + "_account_code_S0001")) + parseFloat(find_sub_total_by_classname(type + "_account_code_S0002"));
    total_assets = convert_back_bracket_to_negative(find_sub_total_by_classname(type + "_account_code_Assets"));

		$('#' + type + "_total_assets").text(total_assets);
	}
	// DISPLAY AND CALCULATE EQUITY AND LIABILITIES (INPUT FROM EQUITY)
	else if(description_type == "Equity")
	{
		// total_equity 	    = parseFloat(find_sub_total_by_classname(type + "_account_code_S0003"));
		// total_liabilities = parseFloat(find_sub_total_by_classname(type + "_account_code_S0004")) + parseFloat(find_sub_total_by_classname(type + "_account_code_S0005"));
    total_equity       = convert_back_bracket_to_negative(find_sub_total_by_classname(type + "_account_code_Equity"));
    total_liabilities = convert_back_bracket_to_negative(find_sub_total_by_classname(type + "_account_code_Liabilities"));

		$('#' + type + "_total_equity_liabilities").text(total_equity + total_liabilities);
	}
	// DISPLAY AND CALCULATE EQUITY AND LIABILITIES (INPUT FROM LIABILITIES)
	else if(description_type == "Liabilities")
	{	
		// total_equity 	  =   parseFloat(find_sub_total_by_classname(type + "_account_code_S0003"));
		// total_liabilities = parseFloat(find_sub_total_by_classname(type + "_account_code_S0004")) + parseFloat(find_sub_total_by_classname(type + "_account_code_S0005"));
    total_equity       = convert_back_bracket_to_negative(find_sub_total_by_classname(type + "_account_code_Equity"));
    total_liabilities = convert_back_bracket_to_negative(find_sub_total_by_classname(type + "_account_code_Liabilities"));

		$('#' + type + "_total_liabilities").text(total_liabilities);
	}

  $('#' + type + "_total_equity_liabilities").text(parseFloat(total_equity) + parseFloat(total_liabilities));

  show_FP_unbalanced_msg(false);
}

function show_FP_unbalanced_msg(is_initialise)
{
  var total_assets         = $("#form_financial_position .total_assets").find('.total_assets').text();
  var total_assets_end       = $("#form_financial_position .total_assets").find('.total_assets_end').text();
  // var total_assets_beg        = $("#form_financial_position .total_assets").find('.total_assets_beg').text();
  var total_equity_liabilities   = $("#form_financial_position .total_equity_liabilities").find('.total_equity_liabilities').text();
  var total_equity_liabilities_end = $("#form_financial_position .total_equity_liabilities").find('.total_equity_liabilities_end').text();
  // var total_equity_liabilities_beg = $("#form_financial_position .total_equity_liabilities").find('.total_equity_liabilities_beg').text();

  if(total_assets != total_equity_liabilities || total_assets_end != total_equity_liabilities_end)
  {
    if(is_group)
    {
      if(is_initialise)
      {
        bootbox.alert({
          message: "Account is not balance. Please check and change the account value.<br/><br/>"
        });
      }
      
      $('#form_financial_position #account_balance_msg').text("* Account is not balance. Please check and change the account value.");
    }
    else
    {
      bootbox.alert({
        message: "Account is not balance. Please check the account and do modification in 'Account Category'.<br/><br/>"
      });

      $('#form_financial_position #account_balance_msg').text("* Account is not balance. Please make changes in 'Account Category'.");
    }
  }
  else
  {
    $('#form_financial_position #account_balance_msg').text('');
  }
}