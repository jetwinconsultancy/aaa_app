// $('#toggle-one').bootstrapToggle();

// $('body').on('dblclick','.add_note_td', function() 
// {
// 	// console.log($(this));
// 	$('#insert_note_num').modal("show");
// });  

// $('body').on('dblclick','.add_note', function() 
// {
// 	return false;
// });  

function show_hide_total_comprehensive_income()
{
	var sci_other_description = $('#form_state_comp_income .sci_other_description');
	var sci_other_g_ye   	  = $('#form_state_comp_income .sci_other_g_ye');
	var sci_other_g_lye   	  = $('#form_state_comp_income .sci_other_g_lye');
	var sci_other_c_ye   	  = $('#form_state_comp_income .sci_other_c_ye');
	var sci_other_c_lye   	  = $('#form_state_comp_income .sci_other_c_lye');

	var show_last_row = false;

	if(sci_other_description.length > 1)	// if more than 1 row, show last row.
	{
		show_last_row = true;
	}
	else 	// check every input, if one of the input have value, show last row.
	{
		// check description
		if(sci_other_description.length > 0)
		{
			if($(sci_other_description[0]).val())
			{
				show_last_row = true;
			}
		}

		// check group year end value (current)
		if(sci_other_g_ye.length > 0)
		{
			if($(sci_other_g_ye[0]).val())
			{
				show_last_row = true;
			}
		}

		// check group year end value (previous)
		if(sci_other_g_lye.length > 0)
		{
			if($(sci_other_g_lye[0]).val())
			{
				show_last_row = true;
			}
		}

		// check company year end value (current)
		if(sci_other_c_ye.length > 0)
		{
			if($(sci_other_c_ye[0]).val())
			{
				show_last_row = true;
			}
		}

		// check company year end value (previous)
		if(sci_other_c_lye.length > 0)
		{
			if($(sci_other_c_lye[0]).val())
			{
				show_last_row = true;
			}
		}
	}

	// var profit_loss_display_after_tax = $('#form_state_comp_income .profit_loss_display_after_tax_input');
	var profit_loss_display_b4_tax 	  = $('#form_state_comp_income .profit_loss_display_b4_tax').text();
	var profit_loss_display_after_tax = $('#form_state_comp_income .profit_loss_display_after_tax').text();

	// profit_loss_display_b4_tax_input
	// profit_loss_display_b4_tax

	// if(sci_additional_list[0][0]['child_array'].length == 0 && sci_soa_pl_list.length == 0)
	// {
	// 	// $('.pl_be4_tax_double_line').css("border-top", "1px solid black");
	// 	$('#form_state_comp_income .pl_be4_tax_double_line').css("border-bottom-style", "double");
	// 	$('#form_state_comp_income .pl_be4_tax_double_line').css("border-bottom-width", "5px");
	// }
	// else
	// {
	// 	// $('.pl_after_tax_double_line').css("border-top", "1px solid black");
	// 	$('#form_state_comp_income .pl_after_tax_double_line').css("border-bottom-style", "double");
	// 	$('#form_state_comp_income .pl_after_tax_double_line').css("border-bottom-width", "5px");
	// }

	// hide/show last row
	if(show_last_row)	// show last row
	{
		$('#form_state_comp_income .sci_total_comprehensive_income_tr').show();

		// remove ", Total comprehensive income for the year"
		if(sci_additional_list[0][0]['child_array'].length == 0 && sci_soa_pl_list.length == 0)
		{
			$('#form_state_comp_income .profit_loss_display_b4_tax').text(profit_loss_display_b4_tax.replace(", Total comprehensive income for the year", ""));
			$('#form_state_comp_income .profit_loss_display_b4_tax_input').val(profit_loss_display_b4_tax.replace(", Total comprehensive income for the year", ""));

			// remove double line
			$('#form_state_comp_income .pl_be4_tax_double_line').css("border-bottom-style", "");
			$('#form_state_comp_income .pl_be4_tax_double_line').css("border-bottom-width", "");
		}
		else
		{
			$('#form_state_comp_income .profit_loss_display_after_tax').text(profit_loss_display_after_tax.replace(", Total comprehensive income for the year", ""));
			$('#form_state_comp_income .profit_loss_display_after_tax_input').val(profit_loss_display_after_tax.replace(", Total comprehensive income for the year", ""));

			// remove double line
			$('#form_state_comp_income .pl_after_tax_double_line').css("border-bottom-style", "");
			$('#form_state_comp_income .pl_after_tax_double_line').css("border-bottom-width", "");
		}
	}
	else // hide and draw double lines
	{
		$('#form_state_comp_income .sci_total_comprehensive_income_tr').hide();

		$('#form_state_comp_income .pl_after_tax_double_line').css("border-bottom-style", "double");
		$('#form_state_comp_income .pl_after_tax_double_line').css("border-bottom-width", "5px");

		if(sci_additional_list[0][0]['child_array'].length == 0 && sci_soa_pl_list.length == 0)
		{
			$('#form_state_comp_income .profit_loss_display_b4_tax').text(profit_loss_display_b4_tax + ", Total comprehensive income for the year");
			$('#form_state_comp_income .profit_loss_display_b4_tax_input').val(profit_loss_display_b4_tax + ", Total comprehensive income for the year");

			// Add in double line
			$('#form_state_comp_income .pl_be4_tax_double_line').css("border-bottom-style", "double");
			$('#form_state_comp_income .pl_be4_tax_double_line').css("border-bottom-width", "5px");
		}
		else
		{
			$('#form_state_comp_income .profit_loss_display_after_tax').text(profit_loss_display_after_tax + ", Total comprehensive income for the year", "");
			$('#form_state_comp_income .profit_loss_display_after_tax_input').val(profit_loss_display_after_tax + ", Total comprehensive income for the year", "");

			// Add in double line
			$('#form_state_comp_income .pl_after_tax_double_line').css("border-bottom-style", "double");
			$('#form_state_comp_income .pl_after_tax_double_line').css("border-bottom-width", "5px");
		}
	}
}

function add_state_comp_row(element, data)
{
	var tr = $(element).parent().parent();
	var clone = tr.clone();

	clone.find('.sci_new_row').parent().html('<a data-toggle="tooltip" data-trigger="hover" style="color: lightgrey; font-weight:bold; cursor: pointer;" onclick="delete_state_comp_row(this)"><i class="fa fa-minus-circle" style="font-size:12px;"></i></a>');

	if(data)
	{
		/* ------ insert values ------ */
		clone.find('.sci_other_id').val(data['id']);
		clone.find('.sci_other_description').val(data['description']);
		clone.find('.sci_other_c_ye').val(data['value_company_ye']);
		clone.find('.sci_other_c_lye').val(data['value_company_lye_end']);
		clone.find('.sci_other_g_ye').val(data['value_group_ye']);
		clone.find('.sci_other_g_lye').val(data['value_group_lye_end']);
		/* ------ END OF insert values ------ */
	}

	tr.before(clone);

	/* ------ reset default value tr ------ */
	tr.find('.sci_other_description').val('');
	tr.find('.sci_other_c_ye').val('');
	tr.find('.sci_other_c_lye').val('');
	tr.find('.sci_other_g_ye').val('');
	tr.find('.sci_other_g_lye').val('');
	/* ------ END OF reset default value tr ------ */
}

function delete_state_comp_row(element)
{
	var tr = $(element).parent().parent();
	var this_id = tr.find('.sci_other_id').val();

	deleted_dynamic_ids.push(this_id);

	tr.remove();

	calculate_total_comprehensive('group');
	show_hide_total_comprehensive_income();
}

$(document).on('click',"#save_state_comp_income", function(e)
{
    $('#loadingStateCompIncome').show();

    $.ajax({ //Upload common input
      url: "fs_statements/save_category_value",
      type: "POST",
      data: $('#form_state_comp_income').serialize() + '&client_id=' + $('#client_id').val() + '&fs_company_info_id=' + 
      		$("#fs_company_info_id").val() + '&statement_type=state_comp_income' + '&is_group=' + is_group + '&fs_ntfs_layout_template_list=' + 
      		JSON.stringify(fs_ntfs_layout_template_list) + '&deleted_dynamic_ids=' + deleted_dynamic_ids,
      dataType: 'json',
      success: function (response,data) 
      {
        // console.log(response);

        if(response['result'])
        {
        	$('#SCI_cii_id').val(response['SCI_cii_id']);
        	$('#SCI_pl_be4_tax_id').val(response['SCI_pl_be4_tax_id']);

          	toastr.success("Successfully updated!", "Success saved!");

          	window.location.href = "financial_statement/create/" + response['client_id'] + "/" + response['fs_company_info_id'];
        }
        else
        {
          	toastr.error("Something went wrong. Please try again later.", "");
        }

        // toastr.success(response.message, response.title);

        $('#loadingStateCompIncome').hide();

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

function SCI_calculation(category_type, group_company)
{
	var subtotal = find_sub_total_by_classname(category_type);

	$('#' + category_type + '_subtotal').text(subtotal);

	// Let this be 'A' for COMPANY
	var main_category_company_ye_subtotal  = parseFloat(convert_back_bracket_to_negative($('#main_category_company_ye_subtotal').text()));
	var main_category_company_lye_subtotal = parseFloat(convert_back_bracket_to_negative($('#main_category_company_lye_subtotal').text()));

	// Let this be 'B' for COMPANY
	var expenses_company_ye_subtotal  = parseFloat(convert_back_bracket_to_negative($('#expenses_company_ye_subtotal').text()));
	var expenses_company_lye_subtotal = parseFloat(convert_back_bracket_to_negative($('#expenses_company_lye_subtotal').text()));

	if(group_company == "group")
	{
		// Let this be 'A' for GROUP
		var main_category_group_ye_subtotal  = parseFloat(convert_back_bracket_to_negative($('#main_category_group_ye_subtotal').text()));
		var main_category_group_lye_subtotal = parseFloat(convert_back_bracket_to_negative($('#main_category_group_lye_subtotal').text()));

		// Let this be 'B' for GROUP
		var expenses_group_ye_subtotal  = parseFloat(convert_back_bracket_to_negative($('#expenses_group_ye_subtotal').text()));
		var expenses_group_lye_subtotal = parseFloat(convert_back_bracket_to_negative($('#expenses_group_lye_subtotal').text()));

		// display title "Profit before tax, Loss before tax, ..."
		// if(sci_additional_list.length > 0) 
		// {
			main_group_ye 	 = compare_value_sci(main_category_group_ye_subtotal, expenses_group_ye_subtotal);
			main_group_lye 	 = compare_value_sci(main_category_group_lye_subtotal, expenses_group_lye_subtotal);
			main_company_ye  = compare_value_sci(main_category_company_ye_subtotal, expenses_company_ye_subtotal);
			main_company_lye = compare_value_sci(main_category_company_lye_subtotal, expenses_company_lye_subtotal);

			// console.log(main_category_company_ye_subtotal);
			// console.log($('#main_category_company_lye_subtotal').text());
			// console.log($('#expenses_company_ye_subtotal').text());
			// console.log($('#expenses_company_lye_subtotal').text());

			var display_text_b4_tax = '';
			var display_text_after_tax = '';

			if(main_group_ye && main_group_lye && main_company_ye && main_company_lye)
			{
				display_text_b4_tax 	= 'Profit before tax';
				display_text_after_tax  = 'Profit after tax';
			}
			else if(!main_group_ye && !main_group_lye && !main_company_ye && !main_company_lye)
			{
				display_text_b4_tax 	= 'Loss before tax';
				display_text_after_tax  = 'Loss after tax';
			}
			else if(main_group_ye)
			{
				display_text_b4_tax 	= 'Profit/Loss before tax';
				display_text_after_tax 	= 'Profit/Loss after tax';
			}
			else if(!main_group_ye)
			{
				display_text_b4_tax 	= 'Loss/Profit before tax';
				display_text_after_tax 	= 'Loss/Profit after tax';
			}

			$('.profit_loss_display_b4_tax').text(display_text_b4_tax);
			$('.profit_loss_display_after_tax').text(display_text_after_tax);

			$('.profit_loss_display_b4_tax_input').val(display_text_b4_tax);
			$('.profit_loss_display_after_tax_input').val(display_text_after_tax);
		// }

		show_hide_total_comprehensive_income(); // change profit/loss content with/without include total comprehensive income.

		// // IF all A > B
		// if(((main_category_group_ye_subtotal 	 > expenses_group_ye_subtotal) 	  && ((main_category_group_ye_subtotal    - expenses_group_ye_subtotal)    > 0)) &&
		// 	((main_category_group_lye_subtotal 	 > expenses_group_lye_subtotal)	  && ((main_category_group_lye_subtotal   - expenses_group_lye_subtotal)   > 0)) &&
		// 	((main_category_company_ye_subtotal  > expenses_company_ye_subtotal)  && ((main_category_company_ye_subtotal  - expenses_company_ye_subtotal)  > 0)) &&
		// 	((main_category_company_lye_subtotal > expenses_company_lye_subtotal) && ((main_category_company_lye_subtotal - expenses_company_lye_subtotal) > 0)))
		// {
		// 	// console.log('Profit before tax');

		// 	$('.profit_loss_display_b4_tax').text('Profit before tax');
		// 	$('.profit_loss_display_after_tax').text('Profit after tax');

		// 	$('.profit_loss_display_b4_tax_input').val('Profit before tax');
		// 	$('.profit_loss_display_after_tax_input').val('Profit after tax');
		// }
		// // IF all A < B
		// else if(((main_category_group_ye_subtotal 	 < expenses_group_ye_subtotal) 	  && ((main_category_group_ye_subtotal 	  - expenses_group_ye_subtotal)    < 0)) &&
		// 		((main_category_group_lye_subtotal 	 < expenses_group_lye_subtotal)   && ((main_category_group_lye_subtotal   - expenses_group_lye_subtotal)   < 0)) &&
		// 		((main_category_company_ye_subtotal  < expenses_company_ye_subtotal)  && ((main_category_company_ye_subtotal  - expenses_company_ye_subtotal)  < 0)) &&
		// 		((main_category_company_lye_subtotal < expenses_company_lye_subtotal) && ((main_category_company_lye_subtotal - expenses_company_lye_subtotal) < 0)))
		// {
		// 	// console.log('Loss before tax');

		// 	$('.profit_loss_display_b4_tax').text('Loss before tax');
		// 	$('.profit_loss_display_after_tax').text('Loss after tax');

		// 	$('.profit_loss_display_b4_tax_input').val('Loss before tax');
		// 	$('.profit_loss_display_after_tax_input').val('Loss after tax');
		// }
		// // IF mixture
		// else
		// {
		// 	if(main_category_group_ye_subtotal === '')	// if no group
		// 	{
		// 		if((main_category_company_ye_subtotal  > expenses_company_ye_subtotal))
		// 		{
		// 			// console.log("Profit/Loss");

		// 			$('.profit_loss_display_b4_tax').text('Profit/Loss before tax');
		// 			$('.profit_loss_display_after_tax').text('Profit/Loss after tax');

		// 			$('.profit_loss_display_b4_tax_input').val('Profit/Loss before tax');
		// 			$('.profit_loss_display_after_tax_input').val('Profit/Loss after tax');
		// 		}
		// 		else
		// 		{
		// 			// console.log('Loss/Profit');

		// 			$('.profit_loss_display_b4_tax').text('Loss/Profit before tax');
		// 			$('.profit_loss_display_after_tax').text('Loss/Profit after tax');

		// 			$('.profit_loss_display_b4_tax_input').val('Loss/Profit before tax');
		// 			$('.profit_loss_display_after_tax_input').val('Loss/Profit after tax');
		// 		}
		// 	}
		// 	else // if got group
		// 	{
		// 		if((main_category_group_ye_subtotal > expenses_group_ye_subtotal))
		// 		{
		// 			// console.log('Profit/Loss');

		// 			$('.profit_loss_display_b4_tax').text('Profit/Loss before tax');
		// 			$('.profit_loss_display_after_tax').text('Profit/Loss after tax');

		// 			$('.profit_loss_display_b4_tax_input').val('Profit/Loss before tax');
		// 			$('.profit_loss_display_after_tax_input').val('Profit/Loss after tax');
		// 		}
		// 		else
		// 		{
		// 			// console.log('Loss/Profit');

		// 			$('.profit_loss_display_b4_tax').text('Loss/Profit before tax');
		// 			$('.profit_loss_display_after_tax').text('Loss/Profit after tax');

		// 			$('.profit_loss_display_b4_tax_input').val('Loss/Profit before tax');
		// 			$('.profit_loss_display_after_tax_input').val('Loss/Profit after tax');
		// 		}
		// 	}
		// }

		// Display profit/loss before tax 
		$('#profit_loss_be4_group_ye').text(negative_bracket_thousand_separator(main_category_group_ye_subtotal - expenses_group_ye_subtotal));
		$('#profit_loss_be4_group_ye_input').val((main_category_group_ye_subtotal - expenses_group_ye_subtotal));

		$('#profit_loss_be4_group_lye').text(negative_bracket_thousand_separator(main_category_group_lye_subtotal - expenses_group_lye_subtotal));
		$('#profit_loss_be4_group_lye_input').val((main_category_group_lye_subtotal - expenses_group_lye_subtotal));

		calculate_profit_after_tax(group_company);
	}
}

function compare_value_sci(a, b)
{
	if((a - b) >= 0)
	{
		return 1;
	}
	else
	{
		return 0;
	}
}


function calculate_profit_after_tax(group_company)
{	
	// var taxation_company_ye  = convert_back_bracket_to_negative($('.taxation_company_ye').text());
	// var taxation_company_lye = convert_back_bracket_to_negative($('.taxation_company_lye').text());
	
	// var profit_loss_be4_company_ye  = convert_back_bracket_to_negative($('#profit_loss_be4_company_ye').text());
	// var profit_loss_be4_company_lye = convert_back_bracket_to_negative($('#profit_loss_be4_company_lye').text());

	// console.log(profit_loss_be4_company_ye);

	// var pl_after_tax_g_ye 	= 0,
	//  	pl_after_tax_g_lye 	= 0,
	//  	pl_after_tax_c_ye 	= 0,
	//  	pl_after_tax_c_lye 	= 0;

	if(group_company == "group")
	{
		var taxation_group_ye  = convert_back_bracket_to_negative($('.taxation_group_ye').val());
		var taxation_group_lye = convert_back_bracket_to_negative($('.taxation_group_lye').val());

		var soa_pl_group_ye  = convert_back_bracket_to_negative($('.soa_pl_group_ye').val());
		var soa_pl_group_lye = convert_back_bracket_to_negative($('.soa_pl_group_lye').val());

		var profit_loss_be4_group_ye  = convert_back_bracket_to_negative($('#profit_loss_be4_group_ye').text());
		var profit_loss_be4_group_lye = convert_back_bracket_to_negative($('#profit_loss_be4_group_lye').text());

		// console.log(profit_loss_be4_group_ye - taxation_group_ye);
		// console.log(profit_loss_be4_group_lye - taxation_group_lye);

		$('#pl_after_tax_g_ye').text(negative_bracket_thousand_separator(profit_loss_be4_group_ye - taxation_group_ye - soa_pl_group_ye));
		$('#pl_after_tax_g_ye_input').val((profit_loss_be4_group_ye - taxation_group_ye - soa_pl_group_ye));
		$('#pl_after_tax_g_lye').text(negative_bracket_thousand_separator(profit_loss_be4_group_lye - taxation_group_lye - soa_pl_group_lye));
		$('#pl_after_tax_g_lye_input').val((profit_loss_be4_group_lye - taxation_group_lye - soa_pl_group_lye));

		// calculate_total_comprehensive(group_company);
	}
	// else if(group_company == "company")
	// {
	// 	var taxation_company_lye = convert_back_bracket_to_negative($('.taxation_company_lye').val());

	// 	var profit_loss_be4_company_lye = convert_back_bracket_to_negative($('#profit_loss_be4_company_lye').text());

	// 	$('#pl_after_tax_g_lye').text((profit_loss_be4_group_lye - taxation_group_lye));
	// 	$('#pl_after_tax_g_lye_input').val((profit_loss_be4_group_lye - taxation_group_lye));
	// }
	
	// $('#pl_after_tax_c_ye').text(negative_bracket_thousand_separator(profit_loss_be4_company_ye - taxation_company_ye));
	// $('#pl_after_tax_c_ye_input').val((profit_loss_be4_company_ye - taxation_company_ye));
	// $('#pl_after_tax_c_lye').text(negative_bracket_thousand_separator(profit_loss_be4_company_lye - taxation_company_lye));
	// $('#pl_after_tax_c_lye_input').val((profit_loss_be4_company_lye - taxation_company_lye));

	calculate_total_comprehensive(group_company);
}

function calculate_total_comprehensive(group_company)
{
	if(sci_additional_list[0][0]['child_array'].length == 0 && sci_soa_pl_list.length == 0) // if no tax and share of associates, take profit/loss before tax value
	{
		var pl_after_tax_c_ye  = parseFloat(convert_back_bracket_to_negative($('#profit_loss_be4_company_ye').text()));
		var pl_after_tax_c_lye = parseFloat(convert_back_bracket_to_negative($('#profit_loss_be4_company_lye').text()));
	}	
	else
	{
		var pl_after_tax_c_ye  = parseFloat(convert_back_bracket_to_negative($('#pl_after_tax_c_ye').text()));
		var pl_after_tax_c_lye = parseFloat(convert_back_bracket_to_negative($('#pl_after_tax_c_lye').text()));
	}

	// console.log($('#pl_after_tax_c_ye').text(), convert_back_bracket_to_negative($('#pl_after_tax_c_ye').text()), pl_after_tax_c_ye);

	var sci_other_c_ye 	= parseFloat(convert_back_bracket_to_negative(find_sub_total_by_classname("sci_other_c_ye")));
	var sci_other_c_lye = parseFloat(convert_back_bracket_to_negative(find_sub_total_by_classname("sci_other_c_lye")));

	$('#total_comprehensive_company_ye').text(negative_bracket_thousand_separator(pl_after_tax_c_ye + sci_other_c_ye));
	$('#total_comprehensive_company_lye').text(negative_bracket_thousand_separator(pl_after_tax_c_lye + sci_other_c_lye));

	if(group_company == "group")
	{
		if(sci_additional_list[0][0]['child_array'].length == 0 && sci_soa_pl_list.length == 0) // if no tax and share of associates, take profit/loss before tax value
		{
			var pl_after_tax_c_ye  = parseFloat(convert_back_bracket_to_negative($('#profit_loss_be4_group_ye').text()));
			var pl_after_tax_c_lye = parseFloat(convert_back_bracket_to_negative($('#profit_loss_be4_group_lye').text()));
		}	
		else
		{
			var pl_after_tax_g_ye  = parseFloat(convert_back_bracket_to_negative($('#pl_after_tax_g_ye').text()));
			var pl_after_tax_g_lye = parseFloat(convert_back_bracket_to_negative($('#pl_after_tax_g_lye').text()));
		}

		var subtotal_others_group_ye  = parseFloat(convert_back_bracket_to_negative(find_sub_total_by_classname("sci_other_g_ye")));
		var subtotal_others_group_lye = parseFloat(convert_back_bracket_to_negative(find_sub_total_by_classname("sci_other_g_lye")));

		$('#total_comprehensive_group_ye').text(negative_bracket_thousand_separator(pl_after_tax_g_ye + subtotal_others_group_ye));
		$('#total_comprehensive_group_lye').text(negative_bracket_thousand_separator(pl_after_tax_g_lye + subtotal_others_group_lye));
	}
}

function process_total_comprehensive_income(group_company)
{
	show_hide_total_comprehensive_income();
	calculate_total_comprehensive(group_company);
}