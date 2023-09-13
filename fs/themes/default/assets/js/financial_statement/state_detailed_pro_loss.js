$(document).on('click',"#save_state_detailed_pro_loss",function(e){
    $('#loadingmessage').show();

    $.ajax({ //Upload common input
      url: "fs_statements/save_category_value",
      type: "POST",
      data: $('#form_state_detailed_profit_loss').serialize() + '&fs_company_info_id=' + $("#fs_company_info_id").val() + '&statement_type=state_detailed_pro_loss',
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

function calculation(parent_fs_categorized_account_id)
{
  find_sub_total_lye(parent_fs_categorized_account_id); 
  // get_overall_total();

  var revenue_id = $('#revenue_id').val();
  var cost_of_sale_id = $('#cost_of_sale_id').val();
  var other_income_id = $('#other_income_id').val();

  // console.log(revenue_id);

  var total_revenue_ly = 0.00;
  var total_cost_of_sale_ly = 0.00;
  var total_other_income_ly = 0.00;
  var total_operating_expenses_ly = $('.total_operating_expenses_ly').text();

  // console.log(total_operating_expenses_ly);

  if(revenue_id != '')
  {
    total_revenue_ly = find_sub_total_lye(revenue_id, 'SDPL');
  }

  if(cost_of_sale_id != '')
  {
    total_cost_of_sale_ly = find_sub_total_lye(cost_of_sale_id, 'SDPL');
  }

  if(other_income_id != '')
  {
    total_other_income_ly = find_sub_total_lye(other_income_id, 'SDPL');
  }

  var gross_profit = total_revenue_ly - total_cost_of_sale_ly;
  var profit_of_the_year_ly = gross_profit + total_other_income_ly - total_operating_expenses_ly;

  $('.gross_profit_ly').text(gross_profit);
  $('.profit_of_the_year_ly').text(profit_of_the_year_ly);

  $('input[name="profit_of_the_year_ly"]').val(profit_of_the_year_ly);
}