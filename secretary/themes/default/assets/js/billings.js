var check_is_receipt_or_unassign = true;
var received = {
    row: '.input-group',
    validators: {
        notEmpty: {
            message: 'The Received field is required.'
        }
    }
},
cn_received = {
    row: '.cn_group',
    validators: {
        notEmpty: {
            message: 'The Received field is required.'
        }
    }
},
equival_amount = {
    row: '.equival_amount_group',
    validators: {
        notEmpty: {
            message: 'The Equivalent Amount field is required.'
        }
    }
};
$('#form_receipt').formValidation({
    framework: 'bootstrap',
    icon: {
        /*valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'*/
    },
    // This option will not ignore invisible fields which belong to inactive panels
    //excluded: ':disabled',
    excluded: [':disabled', ':hidden', ':not(:visible)'],
    fields: {
        receipt_date: {
        	row: '.receipt_date_div',
            validators: {
		        notEmpty: {
		            message: 'The Receipt Date field is required.'
		        }
		    }
        },
        total_amount_received: {
        	row: '.input-group',
            validators: {
		        notEmpty: {
		            message: 'The Total Amount Received field is required.'
		        }
		    }
        },
        payment_mode: {
        	row: '.input-group',
            validators: {
                callback: {
                    message: 'The Payment Mode field is required',
                    callback: function(value, validator, $field) {
                        var options = validator.getFieldElements('payment_mode').val();
                        return (options != null && options != "0");
                    }
                }
            }
        },
        bank_account: {
        	row: '.input-group',
            validators: {
                callback: {
                    message: 'The Bank Account field is required',
                    callback: function(value, validator, $field) {
                        var options = validator.getFieldElements('bank_account').val();
                        return (options != null && options != "0");
                    }
                }
            }
        },
        receipt_no: {
        	row: '.input-group',
            validators: {
		        notEmpty: {
		            message: 'The Receipt No field is required.'
		        }
		    }
        },
        reference_no: {
        	row: '.input-group',
            validators: {
                callback: {
                    message: 'The Reference No field is required',
                    callback: function(value, validator, $field) {
                        var options = validator.getFieldElements('payment_mode').val();
                        if(options == "1" && value == "")
                        {
                        	return false;
                        }
                        else                        
                        {
                        	return true;
                        }
                    }
                }
            }
        },
    }
});

$('#form_credit_note').formValidation({
    framework: 'bootstrap',
    icon: {
        /*valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'*/
    },

    fields: {
        client_company_code: {
            row: '.client_company_code_group',
            validators: {
                callback: {
                    message: 'The Company Name field is required',
                    callback: function(value, validator, $field) {
                        var options = validator.getFieldElements('client_company_code').val();
                        return (options != null && options != "0");
                    }
                }
            }
        },
        latest_invoice_no_for_cn_id: {
            row: '.cn_group',
            validators: {
                callback: {
                    message: 'The Invoice Number field is required',
                    callback: function(value, validator, $field) {
                        var options = validator.getFieldElements('latest_invoice_no_for_cn_id').val();
                        return (options != null && options != "0");
                    }
                }
            }
        },
        latest_credit_note_date: {
        	row: '.credit_note_date_div',
            validators: {
		        notEmpty: {
		            message: 'The Credit Note Date field is required.'
		        }
		    }
        },
        latest_total_amount_discounted: {
        	row: '.cn_group',
            validators: {
		        notEmpty: {
		            message: 'The Total Amount Discounted field is required.'
		        }
		    }
        },
        latest_credit_note_no: {
        	row: '.cn_group',
            validators: {
		        notEmpty: {
		            message: 'The Credit Note No field is required.'
		        }
		    }
        },
        cn_rate: {
            row: '.cn_group',
            validators: {
                notEmpty: {
                    message: 'The Rate field is required.'
                }
            }
        },
    }
});


var invoice_description = {
        row: '.input-group',
        validators: {
            notEmpty: {
                message: 'The Invoice Description field is required.'
            }
        }
    },
    amount = {
        row: '.input-group',
        validators: {
            notEmpty: {
                message: 'The Amount field is required.'
            }
        }
    };

// function addCommas(nStr) {
//     nStr += '';
//     var x = nStr.split('.');
//     var x1 = x[0];
//     var x2 = x.length > 1 ? '.' + x[1] : '';
//     var rgx = /(\d+)(\d{3})/;
//     while (rgx.test(x1)) {
//         x1 = x1.replace(rgx, '$1' + ',' + '$2');
//     }
//     return x1 + x2;
// }

$(document).keypress(function(e) {
    if(e.which == 13 )
    {
      return false;
    }
});

function formatDateFunc(date) {
    //console.log(date);
  var monthNames = [
    "01", "02", "03",
    "04", "05", "06", "07",
    "08", "09", "10",
    "11", "12"
  ];

  var day = date.getDate();
  //console.log(day.length);
  if(day.toString().length==1)  
  {
    day="0"+day;
  }
    
  var monthIndex = date.getMonth();
  var year = date.getFullYear();

  return year + monthNames[monthIndex] + day;
}

function changeDateFormat(date)
{
    var change_date_parts = date.split('/');
    var change_date = change_date_parts[2]+"/"+change_date_parts[1]+"/"+change_date_parts[0];

    return change_date;
}

add_total_outstanding();
function add_total_outstanding()
{	
	var total_statement = "";

	$.each(currency_info, function(currency_key, val) {
        var total_outstanding = 0;
        var currency_name = "";
        $.each(billing_info, function(key, val) {
        	if(billing_info[key]['currency_id'] == currency_info[currency_key]['id'])
        	{
        		currency_name = currency_info[currency_key]['currency'];
		        total_outstanding = total_outstanding + parseFloat(billing_info[key]['outstanding']);
		    }
		    
	    });
	    if(currency_name != "")
	    {
	    	total_statement = total_statement + "(" + currency_name + ") " + addCommas(parseFloat(total_outstanding).toFixed(2)) + "<br/>";
	    	currency_name = "";
	    }
    });
    if(total_statement == "")
    {
    	total_statement = 0;
    }
    $(".statement_amount").append(total_statement);
}

payment_mode();
function payment_mode(selected_mode = null)
{
	$.ajax({
        type: "GET",
        url: "billings/get_payment_mode",
        dataType: "json",
        success: function(data){
            if(data.tp == 1){
            	$(".payment_mode option").remove();
            	$('.payment_mode').append($('<option>', {value:0, text:'Select Payment Mode'}));
                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    
                    if(selected_mode != null && selected_mode == key)
                    {
                    	option.attr('selected', 'selected');
                    }
                    $("#payment_mode").append(option);
                });
            }
            else{
                alert(data.msg);
            }  
        }               
    });
}

bank_acc_info();
function bank_acc_info(selected_mode = null)
{
	$.ajax({
        type: "GET",
        url: "billings/get_bank_account",
        dataType: "json",
        async: false,
        success: function(data){
            if(data.tp == 1){
            	$(".bank_account option").remove();
            	$('.bank_account').append($('<option>', {value:0, text:'Select Bank Account'}));
                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    
                    if(selected_mode != null && selected_mode == key)
                    {
                    	option.attr('selected', 'selected');
                    }
                    $("#bank_account").append(option);
                });
            }
            else{
                alert(data.msg);
            }  
        }               
    });
}

//Change bank account will detect the currency
$(".bank_account").live('change',function()
{
	detect_currency($(".bank_account"), "billing");
    if($("#total_amount_received").val() != "" && $("#total_amount_received").val() != 0)
    {
        detect_out_of_balance();
    }
});

function detect_currency(dropdown_bank_account, tab, receipt_equival_amount = 0, company_info = null)
{
	var bank_account_text = dropdown_bank_account.find('option:selected').text();
	var bank_account_value = dropdown_bank_account.find('option:selected').val();
	var currency_text;
	//console.log(bank_account_text);
	if(bank_account_value != 0)
	{
		$('.currency_total_amount_received option').each(function(i,el){
            //console.log(this.text);
			if (bank_account_text.indexOf(this.text) >= 0)
			{
		   		currency_text = this.text;
		   	}
		});
        //console.log(currency_text);
        //$('#test').find('option[text="B"]').val();
        $(".currency_total_amount_received option:contains("+currency_text+")").prop("selected","selected");//bank_account_text

        if(tab == "billing")
        {
            tr = $(".receipt_info tr:visible");
        }
        else if(tab == "receipt")
        {
            tr = $(".receipt_info tr");
        }
        $(".out_of_balance_currency").text(currency_text);

		tr.each(function() {
		    var td_cells = $(this).find("td").eq(3);
		    var td_cell_text = td_cells.text();
		    if(td_cells.text() != currency_text)
		    {
		    	$(".td_equival_amount").remove();
		    	$(".td_equival_amount_total").remove();
                $(".td_out_of_balance_equival_amount").remove();
		    	
                //Row
		    	$("#unpaid_amount .receipt_currency_rate").show();
		    	tr.each(function(index) {
			    	var td = '<td class="td_equival_amount"><div class="equival_amount_group"><input type="text" class="numberdes form-control equival_amount" style="width:100%;text-align:right" placeholder="Equivalent Amount" value="" name="equival_amount['+index+']"/></div></td>';
			    	$(this).append(td);
                    if(company_info != null)
                    {
                        if(company_info[index] != undefined)
                        {
                            $('input[name="equival_amount['+index+']"]').val(addCommas(parseFloat(company_info[index]['equival_amount'],2).toFixed(2)));
                        }
                    }
			    	$('#form_receipt').formValidation('addField', 'equival_amount['+index+']', equival_amount);  
			    });

                //equival_amount_total
			    var receipt_total_td = '<td align=right class="td_equival_amount_total"></td>';
			    $('#receipt_total tr').append(receipt_total_td);
                //out_of_balance_equival_amount
                $(".out_of_balance_original_amount").val(0);
                $(".out_of_balance_original_amount").hide();
                var table_out_of_balance_total = '<td class="td_out_of_balance_equival_amount"><div class="out_of_balance_eq_group"><input type="text" class="numberdes form-control out_of_balance_equival_amount" style="width:100%;text-align:right" placeholder="Amount" value="0" name="out_of_balance_equival_amount" readonly="true"/></div></td>';
                $('#receipt_out_of_balance_total tr').append(table_out_of_balance_total);

                if(receipt_equival_amount != 0)
                {
                    $(".td_equival_amount_total").text(addCommas(parseFloat(receipt_equival_amount,2).toFixed(2)));
                }
			    return false;
		    }
		    else
		    {
		    	$("#unpaid_amount .receipt_currency_rate").hide();
		    	$(".td_equival_amount").remove();
		    	$(".td_equival_amount_total").remove();
                $(".td_out_of_balance_equival_amount").remove();
                $(".out_of_balance_original_amount").show();
		    }
		});
	}
}

function showEquivalentAmount(currency_text, receipt_equival_amount, company_info = null)
{
    tr = $(".receipt_info tr");
    $(".out_of_balance_currency").text(currency_text);
    tr.each(function() {
            var td_cells = $(this).find("td").eq(3);
            var td_cell_text = td_cells.text();
            if(td_cells.text() != currency_text)
            {
                $(".td_equival_amount").remove();
                $(".td_equival_amount_total").remove();
                $(".td_out_of_balance_equival_amount").remove();
                
                //Row
                $("#unpaid_amount .receipt_currency_rate").show();
                tr.each(function(index) {
                    var td = '<td class="td_equival_amount"><div class="equival_amount_group"><input type="text" class="numberdes form-control equival_amount" style="width:100%;text-align:right" placeholder="Equivalent Amount" value="" name="equival_amount['+index+']"/></div></td>';
                    $(this).append(td);
                    if(company_info != null)
                    {
                        if(company_info[index] != undefined)
                        {
                            $('input[name="equival_amount['+index+']"]').val(addCommas(parseFloat(company_info[index]['equival_amount'],2).toFixed(2)));
                        }
                    }
                    $('#form_receipt').formValidation('addField', 'equival_amount['+index+']', equival_amount);  
                });

                //equival_amount_total
                var receipt_total_td = '<td align=right class="td_equival_amount_total"></td>';
                $('#receipt_total tr').append(receipt_total_td);
                //out_of_balance_equival_amount
                $(".out_of_balance_original_amount").val(0);
                $(".out_of_balance_original_amount").hide();
                var table_out_of_balance_total = '<td class="td_out_of_balance_equival_amount"><div class="out_of_balance_eq_group"><input type="text" class="numberdes form-control out_of_balance_equival_amount" style="width:100%;text-align:right" placeholder="Amount" value="0" name="out_of_balance_equival_amount" readonly="true"/></div></td>';
                $('#receipt_out_of_balance_total tr').append(table_out_of_balance_total);

                if(receipt_equival_amount != 0)
                {
                    $(".td_equival_amount_total").text(addCommas(parseFloat(receipt_equival_amount,2).toFixed(2)));
                }
                return false;
            }
            else
            {
                $("#unpaid_amount .receipt_currency_rate").hide();
                $(".td_equival_amount").remove();
                $(".td_equival_amount_total").remove();
                $(".td_out_of_balance_equival_amount").remove();
                $(".out_of_balance_original_amount").show();
            }
        });
}

//Open receipt pop up and retrieve all the billing info
function open_receipt(company_code, checkpart, unassign_amount_val, unassign_currency_name, group_credit_note_no)
{
	$("#unpaid_amount .receipt_currency_rate").hide();
	$.ajax({
		type: "POST",
		url: "billings/get_billing_info",
		data: {"company_code":company_code}, // <--- THIS IS THE CHANGE
		async: false,
		dataType: "json",
		success: function(response)
		{
			if(response.status == 1)
			{
				$(".table").find('#receipt_info').html(""); 
				$(".table").find('#receipt_total').html(""); 
                $(".table").find('#receipt_out_of_balance_total').html(""); 

				var company_info = response.result;
				var billing_currency = response.billing_currency;
				var currency_result = response.currency_result;

				if(company_info[0]['company_name'] != null)
				{
					document.getElementById('receipt_company_name').innerHTML = company_info[0]['company_name'];
				}
				else
				{
					document.getElementById('receipt_company_name').innerHTML = company_info[0]['transaction_client_company_name'];
				}
				
				//total_amount_received_currency
                $("#currency_total_amount_received option").remove();

				$.each(currency_result, function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                   	$("#currency_total_amount_received").append(option);

                });

				payment_mode();
				bank_acc_info();

				var receipt_outstanding = 0.00;
				for(var b = 0; b < company_info.length; b++) 
				{
                    if(checkpart == "unassign_amount")
                    {
                        if(unassign_currency_name == company_info[b]['currency_name'])
                        {
                            receipt_outstanding = receipt_outstanding + parseFloat(company_info[b]['outstanding'],2);

                            var table_cell = '<tr><td style="text-align:right">'+(b+1)+'</td><td style="text-align:center">'+company_info[b]['invoice_date']+'</td><td>'+company_info[b]['invoice_no']+'</td><td>'+company_info[b]['currency_name']+'</td><td align=right data-value="'+company_info[b]['amount']+'">'+addCommas(company_info[b]['amount'])+'</td><td align=right  class="outstanding_class" data-id="'+company_info[b]['id']+'" data-outstandingvalue="'+company_info[b]['outstanding']+'">'+addCommas(company_info[b]['outstanding'])+'</td><td class="td_amount_received"><div class="input-group" style="display:block !important;"><input type="text" class="numberdes form-control applied_amount change_amount" style="width:100%;text-align:right" placeholder="Amount" value="" name="received['+b+']"/></div><input type="hidden" name="id[]" value="'+company_info[b]['id']+'"/><input type="hidden" name="outstanding[]" value="'+company_info[b]['outstanding']+'"/></td></tr>';

                            $(".table").find('#receipt_info').append( table_cell );

                            $('#form_receipt').formValidation('addField', 'received['+b+']', received);
                        }
                    }
                    else
                    {
                        receipt_outstanding = receipt_outstanding + parseFloat(company_info[b]['outstanding'],2);

                        var table_cell = '<tr><td style="text-align:right">'+(b+1)+'</td><td style="text-align:center">'+company_info[b]['invoice_date']+'</td><td>'+company_info[b]['invoice_no']+'</td><td>'+company_info[b]['currency_name']+'</td><td align=right data-value="'+company_info[b]['amount']+'">'+addCommas(company_info[b]['amount'])+'</td><td align=right  class="outstanding_class" data-id="'+company_info[b]['id']+'" data-outstandingvalue="'+company_info[b]['outstanding']+'">'+addCommas(company_info[b]['outstanding'])+'</td><td class="td_amount_received"><div class="input-group" style="display:block !important;"><input type="text" class="numberdes form-control applied_amount change_amount" style="width:100%;text-align:right" placeholder="Amount" value="" name="received['+b+']"/></div><input type="hidden" name="id[]" value="'+company_info[b]['id']+'"/><input type="hidden" name="outstanding[]" value="'+company_info[b]['outstanding']+'"/></td></tr>';

                        $(".table").find('#receipt_info').append( table_cell );

                        $('#form_receipt').formValidation('addField', 'received['+b+']', received);
                    }
		            
		        }
		        var table_total = '<tr><td align=right colspan=5>Total</td><td align=right class="num_receipt_outstanding">'+addCommas(receipt_outstanding.toFixed(2))+'</td><td align=right id="received"></td></tr>';
		        
                var table_out_of_balance_total = '<tr><td align=right colspan=5>Out of Balance</td><td class="out_of_balance_currency"></td><td id="td_out_of_balance_original_amount"><div class="out_of_balance_group"><input type="text" class="numberdes form-control out_of_balance_original_amount" style="width:100%;text-align:right" placeholder="Amount" value="0" name="out_of_balance_original_amount" readonly="true"/></div></td></tr>';

                $(".table").find('#receipt_total').append(table_total);
                $(".table").find('#receipt_out_of_balance_total').append(table_out_of_balance_total);

		        $("#total_amount_received").val("");
		        $('#form_receipt').formValidation('revalidateField', 'total_amount_received');

		        $(".receipt_no").val("");
		        $(".reference_no").val("");
                $(".hidden_reference_no").val("");
		        $(".receipt_date").val("");

		        if(company_info[0]["incorporation_date"] != null)
		        {
		        	$array = company_info[0]["incorporation_date"].split("/");
		        }
		        else
		        {
		        	$array = company_info[0]["transaction_client_incorporation_date"].split("/");
		        }

				$tmp = $array[0];
				$array[0] = $array[1];
				$array[1] = $tmp;
				$date_2 = $array[0]+"/"+$array[1]+"/"+$array[2];

				$(".receipt_no").val(response.receipt_no);
				$(".receipt_no").attr("readonly", true);
				var date2 = new Date($date_2);

				$('.receipt_date').datepicker({ 
				    dateFormat:'dd/mm/yyyy',
				    autoclose: true,
				}).datepicker('setStartDate', date2)
				.on('changeDate', function (selected) {
				    $('#form_receipt').formValidation('revalidateField', 'receipt_date');
				});

				if(access_right_billing_module == "read" || access_right_template_module == "read")
				{
					$(".change_amount").attr("disabled", true);
				}

                if(checkpart == "unassign_amount")
                {
                    check_is_receipt_or_unassign = false;
                    $(".tr_unassign_amount_receipt").show();
                    $(".tr_unassign_amount_receipt .unassign_amt").val(addCommas(unassign_amount_val));
                    $(".tr_unassign_amount_receipt .unassign_ccy").val(unassign_currency_name);
                    $(".tr_unassign_amount_receipt .unassign_company_code").val(company_info[0]['company_code']);
                    $(".reference_no").val(group_credit_note_no);
                    $(".reference_no").attr('disabled', true);
                    $(".hidden_reference_no").val(group_credit_note_no);
                    $(".tr_bank_account").hide();
                    $(".tr_payment_mode").hide();
                    

                    // $("#payment_mode").attr("disabled", true);
                    // $("#bank_account").attr("disabled", true);

                    $( document ).ready(function() {
                        $('#form_receipt').formValidation('enableFieldValidators', 'bank_account', false);
                        $('#form_receipt').formValidation('enableFieldValidators', 'payment_mode', false);
                    });
                    //$('#form_receipt').formValidation('revalidateField', 'bank_account');

                    // if($("#currency_total_amount_received option:selected").text() != unassign_currency_name)
                    // {
                    showEquivalentAmount($("#currency_total_amount_received option:selected").text(), 0);
                    //}

                    //console.log(group_credit_note_no);
                }
                else if(checkpart == "billing")
                {
                    check_is_receipt_or_unassign = true;
                    $(".tr_unassign_amount_receipt").hide();
                    $(".tr_unassign_amount_receipt .unassign_amt").val(0);
                    $(".tr_unassign_amount_receipt .unassign_company_code").val("");
                    $(".reference_no").val("");
                    $(".reference_no").attr('disabled', false);
                    $(".hidden_reference_no").val("");
                    // $("#payment_mode").attr("disabled", false);
                    // $("#bank_account").attr("disabled", false);
                    $(".tr_bank_account").show();
                    $(".tr_payment_mode").show();

                    $( document ).ready(function() {
                        $('#form_receipt').formValidation('enableFieldValidators', 'bank_account', true);
                        $('#form_receipt').formValidation('enableFieldValidators', 'payment_mode', true);
                    });
                }

                $("#saveReceipt").attr("disabled", false);
				$("#modal_payment").modal("show");
			}
            else
            {
                toastr.error("Not invoice can be assigned.", "Error");
            }
		}				
	});		
}

//Open Receipt
$(".open_reciept").click(function()
{
	var company_code = $(this).data('code');
    var checkpart = $(this).data('checkpart');
    var unassign_amount = $(this).data('unassign_amount');
    var currency_name = $(this).data('currency_name');
    var group_credit_note_no = $(this).data('group_credit_note_no');
	open_receipt(company_code, checkpart, unassign_amount, currency_name, group_credit_note_no);
});

//Open unassign amount pop up and retrieve all the billing info
// function open_assign_amount(company_code)
// {
//     $("#unpaid_amount .receipt_currency_rate").hide();
//     $.ajax({
//         type: "POST",
//         url: "billings/get_billing_info",
//         data: {"company_code":company_code}, // <--- THIS IS THE CHANGE
//         async: false,
//         dataType: "json",
//         success: function(response)
//         {
//             if(response.status == 1)
//             {
//                 $(".table").find('#receipt_info').html(""); //#receipt_info
//                 $(".table").find('#receipt_total').html(""); //#receipt_total
//                 $(".table").find('#receipt_out_of_balance_total').html(""); //#receipt_out_of_balance_total

//                 var company_info = response.result;
//                 var billing_currency = response.billing_currency;
//                 var currency_result = response.currency_result;

//                 if(company_info[0]['company_name'] != null)
//                 {
//                     document.getElementById('receipt_company_name').innerHTML = company_info[0]['company_name'];
//                 }
//                 else
//                 {
//                     document.getElementById('receipt_company_name').innerHTML = company_info[0]['transaction_client_company_name'];
//                 }
                
//                 //total_amount_received_currency
//                 $("#currency_total_amount_received option").remove();

//                 $.each(currency_result, function(key, val) {
//                     var option = $('<option />');
//                     option.attr('value', key).text(val);
//                     $("#currency_total_amount_received").append(option);

//                 });

//                 payment_mode();
//                 bank_acc_info();

//                 var receipt_outstanding = 0.00;
//                 for(var b = 0; b < company_info.length; b++) 
//                 {
//                     receipt_outstanding = receipt_outstanding + parseFloat(company_info[b]['outstanding'],2);

//                     //var table_cell = '<tr><td style="text-align:right">'+(b+1)+'</td><td style="text-align:center">'+company_info[b]['invoice_date']+'</td><td>'+company_info[b]['invoice_no']+'</td><td>'+company_info[b]['currency_name']+'</td><td align=right data-value="'+company_info[b]['amount']+'">'+addCommas(company_info[b]['amount'])+'</td><td align=right  class="outstanding_class" data-id="'+company_info[b]['id']+'" data-outstandingvalue="'+company_info[b]['outstanding']+'">'+addCommas(company_info[b]['outstanding'])+'</td><td class="td_amount_received"><div class="input-group"><input type="text" class="numberdes form-control applied_amount change_amount" style="width:100%;text-align:right" placeholder="Amount" value="" name="received['+b+']"/></div><input type="hidden" name="id[]" value="'+company_info[b]['id']+'"/><input type="hidden" name="outstanding[]" value="'+company_info[b]['outstanding']+'"/></td><td class="td_other_amount_received"><div class="input-group"><input type="text" class="numberdes form-control applied_other_amount change_other_amount" style="width:100%;text-align:right" placeholder="Amount" value="" name="other_currency_received['+b+']"/></div><input type="hidden" name="other_currency_amount[]" value="'+company_info[b]['other_currency_amount']+'"/></td></tr>';
//                     var table_cell = '<tr><td style="text-align:right">'+(b+1)+'</td><td style="text-align:center">'+company_info[b]['invoice_date']+'</td><td>'+company_info[b]['invoice_no']+'</td><td>'+company_info[b]['currency_name']+'</td><td align=right data-value="'+company_info[b]['amount']+'">'+addCommas(company_info[b]['amount'])+'</td><td align=right  class="outstanding_class" data-id="'+company_info[b]['id']+'" data-outstandingvalue="'+company_info[b]['outstanding']+'">'+addCommas(company_info[b]['outstanding'])+'</td><td class="td_amount_received"><div class="input-group" style="display:block !important;"><input type="text" class="numberdes form-control applied_amount change_amount" style="width:100%;text-align:right" placeholder="Amount" value="" name="received['+b+']"/></div><input type="hidden" name="id[]" value="'+company_info[b]['id']+'"/><input type="hidden" name="outstanding[]" value="'+company_info[b]['outstanding']+'"/></td></tr>';

//                     $(".table").find('#receipt_info').append( table_cell );

//                     $('#form_receipt').formValidation('addField', 'received['+b+']', received);
//                 }
//                 var table_total = '<tr><td align=right colspan=5>Total</td><td align=right class="num_receipt_outstanding">'+addCommas(receipt_outstanding.toFixed(2))+'</td><td align=right id="received"></td></tr>';
//                 $(".table").find('#receipt_total').append(table_total);
//                 var table_out_of_balance_total = '<tr><td align=right colspan=5>Out of Balance</td><td class="out_of_balance_currency"></td><td id="td_out_of_balance_original_amount"><div class="out_of_balance_group"><input type="text" class="numberdes form-control out_of_balance_original_amount" style="width:100%;text-align:right" placeholder="Amount" value="0" name="out_of_balance_original_amount" readonly="true"/></div></td></tr>';
//                 $(".table").find('#receipt_out_of_balance_total').append(table_out_of_balance_total);

//                 $("#total_amount_received").val("");
//                 $('#form_receipt').formValidation('revalidateField', 'total_amount_received');

//                 $(".receipt_no").val("");
//                 $(".reference_no").val("");
//                 $(".receipt_date").val("");

//                 if(company_info[0]["incorporation_date"] != null)
//                 {
//                     $array = company_info[0]["incorporation_date"].split("/");
//                 }
//                 else
//                 {
//                     $array = company_info[0]["transaction_client_incorporation_date"].split("/");
//                 }

//                 $tmp = $array[0];
//                 $array[0] = $array[1];
//                 $array[1] = $tmp;
//                 $date_2 = $array[0]+"/"+$array[1]+"/"+$array[2];

//                 $(".receipt_no").val(response.receipt_no);
//                 $(".receipt_no").attr("readonly", true);
//                 var date2 = new Date($date_2);

//                 $('.receipt_date').datepicker({ 
//                     dateFormat:'dd/mm/yyyy',
//                     autoclose: true,
//                 }).datepicker('setStartDate', date2)
//                 .on('changeDate', function (selected) {
//                     $('#form_receipt').formValidation('revalidateField', 'receipt_date');
//                 });

//                 if(access_right_billing_module == "read" || access_right_template_module == "read")
//                 {
//                     $(".change_amount").attr("disabled", true);
//                 }

//                 $("#modal_aa_receipt").modal("show");
//             }
//             else
//             {
//                 toastr.error("Not any invoice can be assign.", "Error");
//             }
//         }
//     });
// }

//Open unassign amount
// $(".open_assign_amount").click(function()
// {
//     var company_code = $(this).data('code');
//     open_assign_amount(company_code);
// });

//Open credit note pop up and retrieve all the billing info
//3 latest credit note
function open_credit_note(billing_id) 
{
    $('#loadingmessage').show();
    $.ajax({
        type: "POST",
        url: "billings/get_a_billing_info",
        data: {"billing_id":billing_id}, // <--- THIS IS THE CHANGE
        dataType: "json",
        success: function(response)
        {
            //console.log(response);
            $('#loadingmessage').hide();
            $(".remove_cn_service_and_total").remove();
            //$("#credit_note_id").val("");
            var bill = response.edit_bill;
            var bill_services = response.edit_bill_service;
            var get_billing_credit_note_gst_record = response.get_billing_credit_note_gst_record;
            if(bill != false && bill_services != false)
            {
                
                //$("#latest_invoice_no_for_cn").text(bill[0]["invoice_no"]);
                $("#latest_credit_note_company_name").text(bill[0]["company_name"]);
                $("#billing_outstanding").val(bill[0]["outstanding"]);
                $("#billing_no").val(bill[0]["invoice_no"]);
                $("#currency").val(bill[0]["currency_id"]);
                $('#currency').attr('disabled', true);

                for(x in bill_services)
                {
                    for(y in get_billing_credit_note_gst_record)
                    {
                        if(bill_services[x]["billing_service_id"] == get_billing_credit_note_gst_record[y]["billing_service_id"] && bill_services[x]["billing_service_amount"] == get_billing_credit_note_gst_record[y]["total_cn_amount"])
                        {
                            //total CN amount is same as invoice amount
                            var table_cell = '<tr class="remove_cn_service_and_total"><td>'+bill_services[x]["service_name"]+'</td><td style="text-align:right;" class="invoice_amount" data-numRow="'+x+'" data-invoiceamount="'+bill_services[x]["billing_service_amount"]+'">'+addCommas(bill_services[x]["billing_service_amount"])+'</td><td class="td_amount_received" style="width: 200px;"><div class="cn_group"><input type="text" class="numberdes form-control applied_amount change_credit_note_amount" style="width:100%;text-align:right" placeholder="Amount" value="" name="received['+x+']" readonly="true"/><input type="hidden" name="billing_service_id['+x+']" value="'+bill_services[x]["billing_service_id"]+'"/><input type="hidden" name="invoice_amount['+x+']" value="'+bill_services[x]["billing_service_amount"]+'"/><input type="hidden" class="gst_rate" name="gst_rate['+x+']" value="'+bill_services[x]["gst_rate"]+'"/></div></td></tr>';
                            break;
                        }
                        else
                        {
                            var table_cell = '<tr class="remove_cn_service_and_total"><td>'+bill_services[x]["service_name"]+'</td><td style="text-align:right;" class="invoice_amount" data-numRow="'+x+'" data-invoiceamount="'+bill_services[x]["billing_service_amount"]+'">'+addCommas(bill_services[x]["billing_service_amount"])+'</td><td class="td_amount_received" style="width: 200px;"><div class="cn_group"><input type="text" class="numberdes form-control applied_amount change_credit_note_amount" style="width:100%;text-align:right" placeholder="Amount" value="" name="received['+x+']"/><input type="hidden" name="billing_service_id['+x+']" value="'+bill_services[x]["billing_service_id"]+'"/><input type="hidden" name="invoice_amount['+x+']" value="'+bill_services[x]["billing_service_amount"]+'"/><input type="hidden" class="gst_rate" name="gst_rate['+x+']" value="'+bill_services[x]["gst_rate"]+'"/></div></td></tr>';
                        }
                    }
                    $(".table").find('#latest_credit_note_info').append(table_cell);

                    $('#form_credit_note').formValidation('addField', 'received['+x+']', cn_received);
                }

                var table_total = '<tr class="remove_cn_service_and_total"><input type="hidden" name="latest_invoice_outstanding" value="'+bill[0]["outstanding"]+'"/><input type="hidden" id="latest_total_cn_amount" name="latest_total_cn_amount" value=""/><input type="hidden" class="latest_cn_out_of_balance" name="latest_cn_out_of_balance" value=""/><td align=right colspan=2>Invoice Outstanding</td><td align=right id="latest_invoice_outstanding"></td></tr><tr class="remove_cn_service_and_total"><td align=right colspan=2>Total CN Amount</td><td align=right id="latest_received"></td></tr><tr class="remove_cn_service_and_total"><td align=right colspan=2>Out of Balance</td><td align=right id="latest_cn_out_of_balance"></td></tr>';

                $(".table").find('#latest_credit_note_total').append(table_total);
                $("#total_amount_received").val("");
                //$('#form_credit_note').formValidation('revalidateField', 'total_amount_received');

                //$(".latest_credit_note_no").val(response.credit_note_no);
                $(".latest_credit_note_date").val("");
                $("#latest_invoice_outstanding").text(addCommas(bill[0]["outstanding"]));

                // $array = bill[0]["incorporation_date"].split("/");
                // $tmp = $array[0];
                // $array[0] = $array[1];
                // $array[1] = $tmp;
                // $date_2 = $array[0]+"/"+$array[1]+"/"+$array[2];

                // var date2 = new Date($date_2);

                $('.latest_credit_note_date').datepicker({ 
                    dateFormat:'dd/mm/yyyy',
                    autoclose: true,
                })
                .on('changeDate', function (selected) {
                    $('#form_credit_note').formValidation('revalidateField', 'latest_credit_note_date');
                });//.datepicker('setStartDate', date2)
            }
            else
            {
                //$("#latest_credit_note_company_name").text("");
                //$("#client_id").val(0);
                $("#client_id").select2("val", "0");
                //$("#latest_invoice_no_for_cn").select2("val", "0");
                //console.log("in");
            }
            //$("#modal_credit_note").modal("show");
        }
    });
	// $.ajax({
	// 	type: "POST",
	// 	url: "billings/get_billing_info",
	// 	data: {"company_code":company_code}, // <--- THIS IS THE CHANGE
	// 	async: false,
	// 	dataType: "json",
	// 	success: function(response)
	// 	{
	// 		if(response.status == 1)
	// 		{
	// 			$(".table").find('#credit_note_info').html(""); 
	// 			$(".table").find('#credit_note_total').html(""); 
	// 			var company_info = response.result;
	// 			document.getElementById('credit_note_company_name').innerHTML = company_info[0]['company_name'];

	// 			var credit_note_outstanding = 0.00;
	// 			for(var b = 0; b < company_info.length; b++) 
	// 			{
	// 				credit_note_outstanding = credit_note_outstanding + parseFloat(company_info[b]['outstanding'],2);

	// 	            var table_cell = '<tr><td style="text-align:right">'+(b+1)+'</td><td style="text-align:center">'+company_info[b]['invoice_date']+'</td><td>'+company_info[b]['invoice_no']+'</td><td align=right data-value="'+company_info[b]['amount']+'">'+addCommas(company_info[b]['amount'])+'</td><td align=right  class="outstanding_class" data-id="'+company_info[b]['id']+'" data-outstandingvalue="'+company_info[b]['outstanding']+'">'+addCommas(company_info[b]['outstanding'])+'</td><td class="td_amount_received"><div class="input-group"><input type="text" class="numberdes form-control applied_amount change_credit_note_amount" style="width:100%;text-align:right" placeholder="Amount" value="" name="received['+b+']"/></div><input type="hidden" name="id[]" value="'+company_info[b]['id']+'"/><input type="hidden" name="outstanding[]" value="'+company_info[b]['outstanding']+'"/></td></tr>';

	// 	            $(".table").find('#credit_note_info').append( table_cell );

	// 	            $('#form_credit_note').formValidation('addField', 'received['+b+']', received);
	// 	        }
	// 	        var table_total = '<tr><td align=right colspan=4>Total</td><td align=right class="num_credit_note_outstanding">'+addCommas(credit_note_outstanding.toFixed(2))+'</td><td align=right id="received"></td></tr>';

	// 	        $(".table").find('#credit_note_total').append(table_total);
	// 	        $("#total_amount_received").val("");
	// 	        $('#form_credit_note').formValidation('revalidateField', 'total_amount_received');

	// 	        $(".credit_note_no").val(response.credit_note_no);
	// 	        $(".credit_note_date").val("");

	// 	        $array = company_info[0]["incorporation_date"].split("/");
	// 			$tmp = $array[0];
	// 			$array[0] = $array[1];
	// 			$array[1] = $tmp;
	// 			$date_2 = $array[0]+"/"+$array[1]+"/"+$array[2];

	// 			var date2 = new Date($date_2);

	// 			$('.credit_note_date').datepicker({ 
	// 			    dateFormat:'dd/mm/yyyy',
	// 			    autoclose: true,
	// 			}).datepicker('setStartDate', date2)
	// 			.on('changeDate', function (selected) {
	// 			    $('#form_credit_note').formValidation('revalidateField', 'credit_note_date');
	// 			});

	// 			if(access_right_billing_module == "read" || access_right_template_module == "read")
	// 			{
	// 				$(".change_credit_note_amount").attr("disabled", true);
	// 			}

	// 			$("#modal_credit_note").modal("show");
	// 		}
				
	// 	}				
	// });		
}

$(document).on('change','#latest_invoice_no_for_cn',function(e){
    //console.log($("#latest_invoice_no_for_cn").val());
    var billing_id = $("#latest_invoice_no_for_cn").val();
    if(billing_id != null && billing_id != 0)
    {
        open_credit_note(billing_id);
    }
    else
    {
        $('#currency').attr('disabled', false);
    }
    // else
    // {
    //     $("#latest_invoice_no_for_cn").select2("val", "0");
    // }
});

//1 latest credit note
$(".open_credit_note").click(function(){
    $('#loadingmessage').show();
    $(".remove_option").remove();
    $(".remove_client_option").remove();
    
    $.ajax({
        type: "GET",
        url: "billings/get_credit_note_no",
        dataType: "json",
        success: function(response)
        {
            $('#loadingmessage').hide();
            $(".remove_cn_service_and_total").remove();
            $("#latest_credit_note_company_name").text("");
            $("#latest_total_amount_discounted").val("");
            $('#latest_credit_note_no').attr('readonly', false);
            $("#currency").attr("disabled", false);
            $('#client_id').attr('disabled', false);
            $('#latest_invoice_no_for_cn').attr('disabled', false);

            var client_list = response.client_list;
            var currency = response.currency;
            var firm_currency = response.firm_currency;
            // console.log(currency);
            // console.log(firm_currency);
            
            $(".latest_credit_note_no").val(response.credit_note_no);
            $(".latest_credit_note_date").val("");

            $.each(client_list, function(key, val) {
                var option = $('<option />');
                option.attr('class', "remove_client_option").attr('value', key).text(val);
                // if(data.selected_client_name != null && key == data.selected_client_name)
                // {
                //     option.attr('selected', 'selected');
                //     $('.client_name').attr('disabled', true);
                // }
                $('#client_id').append(option);
            });
            $("#client_id").select2();
            
            $("#latest_invoice_no_for_cn").select2();
            $("#latest_invoice_no_for_cn").select2("val", "0");

            $.each(currency, function(key, val) {
                var option = $('<option />');
                option.attr('class', "remove_client_option").attr('value', val["id"]).text(val["currency"]);
                if(firm_currency[0]["firm_currency"] != null &&  val["id"] == firm_currency[0]["firm_currency"])
                {
                    option.attr('selected', 'selected');
                }
                //$('#currency').attr('disabled', true);
                $('#currency').append(option);
            });

            $('.latest_credit_note_date').datepicker({ 
                dateFormat:'dd/mm/yyyy',
                autoclose: true,
            }).on('changeDate', function (selected) {
                $('#form_credit_note').formValidation('revalidateField', 'latest_credit_note_date');
            });

            $("#cn_rate").val("1.0000");
            $("#saveCreditNote").attr("disabled", false);
            $("#modal_credit_note").modal("show");
        }
    });
    //var billing_id = $(this).data('code');
    //open_credit_note(billing_id);
});

//2 latest credit note
$(document).on('change','.client_id',function(e){
    $('#loadingmessage').show();
    $(".remove_option").remove();
    var company_code = $(".client_id option:selected").val();
    var company_name = $(".client_id option:selected").text();
    $.ajax({
        type: "POST",
        url: "billings/get_client_invoice",
        dataType: "json",
        data: {"company_code":company_code},
        success: function(response)
        {
            $('#loadingmessage').hide();
            $(".remove_cn_service_and_total").remove();
            //$("#latest_credit_note_company_name").text("");
            //$("#latest_total_amount_discounted").val("");

            var billings_invoice_no = response.billings_invoice_no;

            if(billings_invoice_no.length > 0)
            {
                $.each(billings_invoice_no, function(key, val) {
                    var option = $('<option />');
                    if(parseFloat(val['outstanding']) > 0)
                    {
                        var billing_status = "Unpaid";
                    }
                    else
                    {
                        var billing_status = "Paid";
                    }
                    option.attr('class', "remove_option").attr('value', val['id']).text(val['invoice_no'] + " (" +billing_status+ ")");

                    $("#latest_invoice_no_for_cn").append(option);
                });
            }
            $("#latest_invoice_no_for_cn").select2();

            if(company_code == 0)
            {
                $('[name="company_name"]').val("");
                $('[name="hidden_postal_code"]').val("");
                $('[name="hidden_street_name"]').val("");
                $('[name="hidden_building_name"]').val("");
                $('[name="hidden_unit_no1"]').val("");
                $('[name="hidden_unit_no2"]').val("");
                $('[name="hidden_foreign_address1"]').val("");
                $('[name="hidden_foreign_address2"]').val("");
                $('[name="hidden_foreign_address3"]').val("");
            }
            else
            {
                $('[name="company_name"]').val(company_name);
                $('[name="hidden_postal_code"]').val(response.postal_code);
                $('[name="hidden_street_name"]').val(response.street_name);
                $('[name="hidden_building_name"]').val(response.building_name);
                $('[name="hidden_unit_no1"]').val(response.unit_no1);
                $('[name="hidden_unit_no2"]').val(response.unit_no2);
                if(response.foreign_add_1 != null)
                {
                    $('[name="hidden_foreign_address1"]').val(response.foreign_add_1);
                }
                else
                {
                    $('[name="hidden_foreign_address1"]').val("");
                }
                if(response.foreign_add_2 != null)
                {
                    $('[name="hidden_foreign_address2"]').val(response.foreign_add_2);
                }
                else
                {
                    $('[name="hidden_foreign_address2"]').val("");
                }
                if(response.foreign_add_3 != null)
                {
                    $('[name="hidden_foreign_address3"]').val(response.foreign_add_3);
                }
                else
                {
                    $('[name="hidden_foreign_address3"]').val("");
                }
            }

            

        }
    });
});

$(".open_previous_credit_note").click(function()
{
    // var company_code = $(this).data('code');
    // open_previous_credit_note(company_code);
    $('#loadingmessage').show();
    $('#datatable-previous_credit_note').DataTable().destroy();
    $('.tr_previous_credit_note').remove();
    $.ajax({
        type: "GET",
        url: "billings/get_previous_credit_note",
        //data: {"company_code":company_code}, // <--- THIS IS THE CHANGE
        //async: false,
        dataType: "json",
        success: function(response)
        {
            $('#loadingmessage').hide();
            //console.log(response.credit_note);
            var previous_credit_note = response.credit_note;
            for(x in previous_credit_note)
            {
                var latest_date_format = changeDateFormat(previous_credit_note[x]["credit_note_date"]);

                $a = '';
                $a += '<tr class="tr_previous_credit_note"><td style="text-align: right"></td>';
                $a += '<td>'+previous_credit_note[x]["company_name"]+'</td>';
                $a += '<td><a data-toggle="modal" data-id="'+previous_credit_note[x]["credit_note_id"]+'" class="open_edit_credit_note pointer mb-sm mt-sm mr-sm">'+previous_credit_note[x]["credit_note_no"]+'</a></td>';
                $a += '<td style="text-align: center"><span style="display:none">'+ formatDateFunc(new Date(latest_date_format)) + '</span>'+previous_credit_note[x]["credit_note_date"]+'</td>';
                $a += '<td style="text-align: right">'+addCommas(previous_credit_note[x]["received"])+'</td>';
                $a += '<td>'+previous_credit_note[x]["invoice_no"]+'</td>';
                $a += '<td><a data-toggle="modal" data-code="'+previous_credit_note[x]["company_code"]+'" onclick="exportOldCreditNotePDF(null, null, '+previous_credit_note[x]["credit_note_id"]+')" class="btn btn-primary pointer mb-sm mr-sm">PDF</a></td>';
                $a +=  '</tr>';

                $("#previous_credit_note_body").append($a);

            }

            var table5 = $('#datatable-previous_credit_note').DataTable({
                "columnDefs": [ {
                    "searchable": false,
                    "orderable": false,
                    'type': 'num', 
                    "targets": 0
                }],
                "order": [[3, 'desc']]
            });
            table5.on( 'order.dt search.dt', function () {
                table5.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
            } ).draw();

            $("#modal_previous_credit_note").modal("show");
        }
    });
});

//Open credit note pop up and retrieve all the billing info
// function open_previous_credit_note(company_code) 
// {
//     //console.log(company_code);
//     $.ajax({
//         type: "POST",
//         url: "billings/get_previous_credit_note",
//         data: {"company_code":company_code}, // <--- THIS IS THE CHANGE
//         async: false,
//         dataType: "json",
//         success: function(response)
//         {
//             console.log(response);
//         }
//     });
// }



function open_edit_receipt(receipt_id) {
	$.ajax({
		type: "POST",
		url: "billings/get_receipt_info",
		data: {"receipt_id":receipt_id}, // <--- THIS IS THE CHANGE
		dataType: "json",
		success: function(response){
            $("#unpaid_amount .receipt_currency_rate").hide();
			if(response.status == 1)
			{
				$(".table").find('#receipt_info').html(""); 
				$(".table").find('#receipt_total').html("");
                $(".table").find('#receipt_out_of_balance_total').html(""); 

				var company_info = response.result;
                var currency_result = response.currency_result;
                var billing_credit_note_gst_with_receipt = response.billing_credit_note_gst_with_receipt;
				document.getElementById('receipt_company_name').innerHTML = company_info[0]['company_name'];

				payment_mode(company_info[0]["payment_mode_id"]);
				bank_acc_info(company_info[0]["bank_account_id"]);
				$(".receipt_no").attr("readonly", true);
				var receipt_outstanding = 0.00;
				var receipt_received = 0.00;
                var receipt_equival_amount = 0.00;
				for(var b = 0; b < company_info.length; b++) 
				{
					receipt_outstanding = receipt_outstanding + parseFloat(company_info[b]['previous_outstanding'],2);

					receipt_received = receipt_received + parseFloat(company_info[b]['received'],2);

                    if(company_info[b]['equival_amount'] != null)
                    {
                        receipt_equival_amount = receipt_equival_amount + parseFloat(company_info[b]['equival_amount'],2);
                    }

		            var table_cell = '<tr><td style="text-align:right">'+(b+1)+'</td><td style="text-align:center">'+company_info[b]['invoice_date']+'</td><td>'+company_info[b]['invoice_no']+'</td><td>'+company_info[b]['currency_name']+'</td><td align=right data-value="'+company_info[b]['amount']+'">'+addCommas(company_info[b]['amount'])+'</td><td align=right  class="outstanding_class" data-id="'+company_info[b]['id']+'" data-outstandingvalue="'+company_info[b]['previous_outstanding']+'">'+addCommas(company_info[b]['outstanding'])+'</td><td class="td_amount_received"><div class="input-group" style="display: block !important;"><input type="text" class="numberdes form-control applied_amount change_amount" style="width:100%;text-align:right" placeholder="Amount" value="'+addCommas(company_info[b]['received'])+'" name="received['+b+']"/></div><input type="hidden" name="id[]" value="'+company_info[b]['id']+'"/><input type="hidden" name="outstanding[]" value="'+company_info[b]['previous_outstanding']+'"/></td></tr>';

		            $(".table").find('#receipt_info').append( table_cell );

		            $('#form_receipt').formValidation('addField', 'received['+b+']', received);
		        }
		        var table_total = '<tr><td align=right colspan=5>Total</td><td align=right>'+addCommas(receipt_outstanding.toFixed(2))+'</td><td align=right id="received"></td><input type="hidden" name="receipt_id" value="'+company_info[0]['receipt_id']+'"/></tr>';
                $(".table").find('#receipt_total').append(table_total);

                var table_out_of_balance_total = '<tr><td align=right colspan=5>Out of Balance</td><td class="out_of_balance_currency">'+company_info[0]['currency_name']+'<input type="hidden" class="hidden_out_of_balance" value="'+company_info[0]['out_of_balance']+'"/></td><td id="td_out_of_balance_original_amount"><div class="out_of_balance_group"><input type="text" class="numberdes form-control out_of_balance_original_amount" style="width:100%;text-align:right" placeholder="Amount" value="0" name="out_of_balance_original_amount" readonly="true"/></div></td></tr>';
                $(".table").find('#receipt_out_of_balance_total').append(table_out_of_balance_total);
                
		        $("#received").html(addCommas(receipt_received.toFixed(2)));
		        $('#form_receipt').formValidation('revalidateField', 'total_amount_received');

		        $(".receipt_no").val(company_info[0]['receipt_no']);
		        $(".reference_no").val(company_info[0]['reference_no']);
                $(".hidden_reference_no").val(company_info[0]['reference_no']);
		        $(".receipt_date").val(company_info[0]['receipt_date']);
                //total_amount_received_currency
                $("#currency_total_amount_received option").remove();
                $.each(currency_result, function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    if(company_info[0]["currency_total_amount_received"] != null && key == company_info[0]["currency_total_amount_received"])
                    {
                        option.attr('selected', 'selected');
                    }
                    $("#currency_total_amount_received").append(option);
                });
                
                if(company_info[0]["incorporation_date"])
                {
    		        $array = company_info[0]["incorporation_date"].split("/");
    				$tmp = $array[0];
    				$array[0] = $array[1];
    				$array[1] = $tmp;
    				$date_2 = $array[0]+"/"+$array[1]+"/"+$array[2];
                }
                else
                {
                    $date_2 = "1990/11/03";
                }

				var date2 = new Date($date_2);

				$('.receipt_date').datepicker({ 
				    dateFormat:'dd/mm/yyyy',
				    autoclose: true,
				}).datepicker('setStartDate', date2)
				.on('changeDate', function (selected) {
				    $('#form_receipt').formValidation('revalidateField', 'receipt_date');
				});
				if(access_right_billing_module == "read" || access_right_template_module == "read")
				{
					$(".change_amount").attr("disabled", true);
				}
                var total_amount_received_value = parseFloat(company_info[0]['total_amount_received'],2);
                $("#total_amount_received").val(addCommas(total_amount_received_value.toFixed(2)));
                if(receipt_equival_amount > 0)
                {
                    detect_currency($(".bank_account"), "receipt", receipt_equival_amount, company_info);
                }

                if(company_info[0]['is_from_cn'] == 1)
                {
                    check_is_receipt_or_unassign = false;
                    $(".tr_unassign_amount_receipt").show();
                    $(".tr_unassign_amount_receipt .unassign_amt").val(addCommas(billing_credit_note_gst_with_receipt[0]["previous_total_cn_out_of_balance"]));
                    $(".tr_unassign_amount_receipt .unassign_ccy").val(billing_credit_note_gst_with_receipt[0]["previous_cn_currency"]);
                    $(".tr_unassign_amount_receipt .unassign_company_code").val(company_info[0]['company_code']);
                    $(".tr_bank_account").hide();
                    $(".tr_payment_mode").hide();
                    $(".reference_no").attr('disabled', true);

                    showEquivalentAmount($("#currency_total_amount_received option:selected").text(), receipt_equival_amount, company_info)
                }
                else
                {
                    check_is_receipt_or_unassign = true;
                    $(".tr_unassign_amount_receipt").hide();
                    $(".tr_unassign_amount_receipt .unassign_amt").val(0);
                    $(".tr_unassign_amount_receipt .unassign_company_code").val("");
                    $(".tr_bank_account").show();
                    $(".tr_payment_mode").show();
                    $(".reference_no").attr('disabled', false);
                }

                detect_out_of_balance();
                $("#saveReceipt").attr("disabled", false);
				$("#modal_payment").modal("show");
			}
		}

	});
}

$(".open_edit_reciept").click(function(){
	var receipt_id = $(this).data('id');
    open_edit_receipt(receipt_id);
});

//open out of balance in receipt tab
function open_out_of_balance_reciept(receipt_id) {
    $.ajax({
        type: "POST",
        url: "billings/get_out_of_balance_receipt_info",
        data: {"receipt_id":receipt_id}, // <--- THIS IS THE CHANGE
        dataType: "json",
        success: function(response){
            $("#unpaid_amount .receipt_currency_rate").hide();
            if(response.status == 1)
            {
                $(".table").find('#receipt_info').html(""); 
                $(".table").find('#receipt_total').html("");
                $(".table").find('#receipt_out_of_balance_total').html(""); 

                var company_info = response.result;
                var currency_result = response.currency_result;
                var billing_result_info = response.billing_result;
                document.getElementById('receipt_company_name').innerHTML = company_info[0]['company_name'];

                payment_mode(company_info[0]["payment_mode_id"]);
                bank_acc_info(company_info[0]["bank_account_id"]);
                $(".receipt_no").attr("readonly", true);
                var receipt_outstanding = 0.00;
                var receipt_received = 0.00;
                var receipt_equival_amount = 0.00;
                var no_list = 0;
                var receipt_billing_id = [];
                //var index_number = 0;
                for(var b = 0; b < company_info.length; b++) 
                {
                    receipt_billing_id.push(company_info[b]['billing_id']);
                    no_list = b;

                    receipt_outstanding = receipt_outstanding + parseFloat(company_info[b]['previous_outstanding'],2);

                    receipt_received = receipt_received + parseFloat(company_info[b]['received'],2);

                    if(company_info[b]['equival_amount'] != null)
                    {
                        receipt_equival_amount = receipt_equival_amount + parseFloat(company_info[b]['equival_amount'],2);
                    }

                    var table_cell = '<tr><td style="text-align:right">'+(b+1)+'</td><td style="text-align:center">'+company_info[b]['invoice_date']+'</td><td>'+company_info[b]['invoice_no']+'</td><td>'+company_info[b]['currency_name']+'</td><td align=right data-value="'+company_info[b]['amount']+'">'+addCommas(company_info[b]['amount'])+'</td><td align=right  class="outstanding_class" data-id="'+company_info[b]['id']+'" data-outstandingvalue="'+company_info[b]['previous_outstanding']+'">'+addCommas(company_info[b]['outstanding'])+'</td><td class="td_amount_received"><div class="input-group" style="display: block !important;"><input type="text" class="numberdes form-control applied_amount change_amount" style="width:100%;text-align:right" placeholder="Amount" value="'+addCommas(company_info[b]['received'])+'" name="received['+b+']"/></div><input type="hidden" name="id[]" value="'+company_info[b]['id']+'"/><input type="hidden" name="outstanding[]" value="'+company_info[b]['previous_outstanding']+'"/></td></tr>';

                    $(".table").find('#receipt_info').append( table_cell );

                    $('#form_receipt').formValidation('addField', 'received['+b+']', received);
                }

                if(billing_result_info.length > 0)
                {
                    for(var b = 0; b < billing_result_info.length; b++) 
                    {
                        if(!receipt_billing_id.includes(billing_result_info[b]['id']))
                        {
                            no_list = no_list + 1;
                            
                            receipt_outstanding = receipt_outstanding + parseFloat(billing_result_info[b]['outstanding'],2);

                            var table_cell = '<tr><td style="text-align:right">'+(no_list + 1)+'</td><td style="text-align:center">'+billing_result_info[b]['invoice_date']+'</td><td>'+billing_result_info[b]['invoice_no']+'</td><td>'+billing_result_info[b]['currency_name']+'</td><td align=right data-value="'+billing_result_info[b]['amount']+'">'+addCommas(billing_result_info[b]['amount'])+'</td><td align=right  class="outstanding_class" data-id="'+billing_result_info[b]['id']+'" data-outstandingvalue="'+billing_result_info[b]['outstanding']+'">'+addCommas(billing_result_info[b]['outstanding'])+'</td><td class="td_amount_received"><div class="input-group" style="display:block !important;"><input type="text" class="numberdes form-control applied_amount change_amount" style="width:100%;text-align:right" placeholder="Amount" value="" name="received['+(no_list)+']"/></div><input type="hidden" name="id[]" value="'+billing_result_info[b]['id']+'"/><input type="hidden" name="outstanding[]" value="'+billing_result_info[b]['outstanding']+'"/></td></tr>';

                            $(".table").find('#receipt_info').append( table_cell );

                            $('#form_receipt').formValidation('addField', 'received['+(no_list)+']', received);
                        }
                    }
                }

                var table_total = '<tr><td align=right colspan=5>Total</td><td align=right>'+addCommas(receipt_outstanding.toFixed(2))+'</td><td align=right id="received"></td><input type="hidden" name="receipt_id" value="'+company_info[0]['receipt_id']+'"/></tr>';
                $(".table").find('#receipt_total').append(table_total);

                var table_out_of_balance_total = '<tr><td align=right colspan=5>Out of Balance</td><td class="out_of_balance_currency">'+company_info[0]['currency_name']+'<input type="hidden" class="hidden_out_of_balance" value="'+company_info[0]['out_of_balance']+'"/></td><td id="td_out_of_balance_original_amount"><div class="out_of_balance_group"><input type="text" class="numberdes form-control out_of_balance_original_amount" style="width:100%;text-align:right" placeholder="Amount" value="0" name="out_of_balance_original_amount" readonly="true"/></div></td></tr>';
                $(".table").find('#receipt_out_of_balance_total').append(table_out_of_balance_total);
                
                $("#received").html(addCommas(receipt_received.toFixed(2)));
                $('#form_receipt').formValidation('revalidateField', 'total_amount_received');

                $(".receipt_no").val(company_info[0]['receipt_no']);
                $(".reference_no").val(company_info[0]['reference_no']);
                $(".hidden_reference_no").val(company_info[0]['reference_no']);
                $(".receipt_date").val(company_info[0]['receipt_date']);
                //total_amount_received_currency
                $("#currency_total_amount_received option").remove();
                $.each(currency_result, function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    if(company_info[0]["currency_total_amount_received"] != null && key == company_info[0]["currency_total_amount_received"])
                    {
                        option.attr('selected', 'selected');
                    }
                    $("#currency_total_amount_received").append(option);
                });
                
                if(company_info[0]["incorporation_date"])
                {
                    $array = company_info[0]["incorporation_date"].split("/");
                    $tmp = $array[0];
                    $array[0] = $array[1];
                    $array[1] = $tmp;
                    $date_2 = $array[0]+"/"+$array[1]+"/"+$array[2];
                }
                else
                {
                    $date_2 = "1990/11/03";
                }
                
                var date2 = new Date($date_2);

                $('.receipt_date').datepicker({ 
                    dateFormat:'dd/mm/yyyy',
                    autoclose: true,
                }).datepicker('setStartDate', date2)
                .on('changeDate', function (selected) {
                    $('#form_receipt').formValidation('revalidateField', 'receipt_date');
                });
                if(access_right_billing_module == "read" || access_right_template_module == "read")
                {
                    $(".change_amount").attr("disabled", true);
                }
                var total_amount_received_value = parseFloat(company_info[0]['total_amount_received'],2);
                $("#total_amount_received").val(addCommas(total_amount_received_value.toFixed(2)));
                if(receipt_equival_amount > 0)
                {
                    detect_currency($(".bank_account"), "receipt", receipt_equival_amount, company_info);
                }
                detect_out_of_balance();
                $("#saveReceipt").attr("disabled", false);
                $("#modal_payment").modal("show");
            }
        }
    });
}

$(".open_out_of_balance_reciept").click(function(){
    var receipt_id = $(this).data('id');
    open_out_of_balance_reciept(receipt_id);
});

function open_edit_credit_note(credit_note_id) {
	$.ajax({
		type: "POST",
		url: "billings/get_credit_note_info",
		data: {"credit_note_id":credit_note_id}, // <--- THIS IS THE CHANGE

		dataType: "json",
		success: function(response){
			if(response.status == 1)
			{
				$(".table").find('#credit_note_info').html(""); 
				$(".table").find('#credit_note_total').html(""); 

				var company_info = response.result;
				document.getElementById('credit_note_company_name').innerHTML = company_info[0]['company_name'];

				payment_mode(company_info[0]["payment_mode_id"]);

				var credit_note_outstanding = 0.00;
				var credit_note_discounted = 0.00;
				for(var b = 0; b < company_info.length; b++) 
				{
					credit_note_outstanding = credit_note_outstanding + parseFloat(company_info[b]['previous_outstanding'],2);

					credit_note_discounted = credit_note_discounted + parseFloat(company_info[b]['received'],2);

		            var table_cell = '<tr><td style="text-align:right">'+(b+1)+'</td><td style="text-align:center">'+company_info[b]['invoice_date']+'</td><td>'+company_info[b]['invoice_no']+'</td><td align=right data-value="'+company_info[b]['amount']+'">'+addCommas(company_info[b]['amount'])+'</td><td align=right  class="outstanding_class" data-id="'+company_info[b]['id']+'" data-outstandingvalue="'+company_info[b]['previous_outstanding']+'">'+addCommas(company_info[b]['previous_outstanding'])+'</td><td class="td_amount_received"><div class="input-group"><input type="text" class="numberdes form-control applied_amount change_amount" style="width:100%;text-align:right" placeholder="Amount" value="'+addCommas(company_info[b]['received'])+'" name="received['+b+']" disabled="true"/></div><input type="hidden" name="id[]" value="'+company_info[b]['id']+'"/><input type="hidden" name="outstanding[]" value="'+company_info[b]['previous_outstanding']+'"/></td></tr>';

		            $(".table").find('#credit_note_info').append( table_cell );

		            $('#form_credit_note').formValidation('addField', 'received['+b+']', received);
		        }

		        var table_total = '<tr><td align=right colspan=4>Total</td><td align=right >'+addCommas(credit_note_outstanding.toFixed(2))+'</td><td align=right id="received"></td><input type="hidden" name="credit_note_id" value="'+company_info[0]['credit_note_id']+'"/></tr>';

		        $(".table").find('#credit_note_total').append(table_total);
		        $("#total_amount_discounted").val(addCommas(credit_note_discounted.toFixed(2)));
		        $("#received").html(addCommas(credit_note_discounted.toFixed(2)));
		        $('#form_credit_note').formValidation('revalidateField', 'total_amount_discounted');

		        $(".credit_note_no").val(company_info[0]['credit_note_no']);
		        $(".credit_note_date").val(company_info[0]['credit_note_date']);

		        if(company_info[0]["incorporation_date"] != null)
		        {
			        $array = company_info[0]["incorporation_date"].split("/");
					$tmp = $array[0];
					$array[0] = $array[1];
					$array[1] = $tmp;
					$date_2 = $array[0]+"/"+$array[1]+"/"+$array[2];
				}
				else
				{
					$date_2 = "1/1/1990";
				}

				var date2 = new Date($date_2);

				$('.credit_note_date').datepicker({ 
				    dateFormat:'dd/mm/yyyy',
				    autoclose: true,
				}).datepicker('setStartDate', date2)
				.on('changeDate', function (selected) {
				    $('#form_credit_note').formValidation('revalidateField', 'credit_note_date');
				});
				if(access_right_billing_module == "read" || access_right_template_module == "read")
				{
					$(".change_amount").attr("disabled", true);
				}

				$("#modal_edit_previous_credit_note").modal("show");
			}
		}

	});
}
//$(".open_edit_credit_note").click(function(){
$(document).on("click",".open_edit_credit_note",function() {
	var credit_note_id = $(this).data('id');
    open_edit_credit_note(credit_note_id);
});

//4 latest credit note
function open_edit_latest_credit_note(credit_note_id, company_code) {
    $('#loadingmessage').show();
    $.ajax({
        type: "POST",
        url: "billings/get_latest_credit_note_info",
        data: {"credit_note_id":credit_note_id, "company_code": company_code}, // <--- THIS IS THE CHANGE
        dataType: "json",
        success: function(response){
            if(response.status == 1)
            {
                $('#loadingmessage').hide();
                $(".remove_cn_service_and_total").remove();
                $('#latest_credit_note_no').attr('readonly', true);

                var billing_credit_note_gst_record = response.result;
                var client_list = response.client_list;
                var billings_invoice_no = response.billings_invoice_no;
                var currency = response.currency;

                $.each(client_list, function(key, val) {
                    var option = $('<option />');
                    option.attr('class', "remove_client_option").attr('value', key).text(val);
                    if(billing_credit_note_gst_record[0]["company_code"] != null && key == billing_credit_note_gst_record[0]["company_code"])
                    {
                        option.attr('selected', 'selected');
                        $('#client_id').attr('disabled', true);
                    }
                    $('#client_id').append(option);
                });

                $("#client_id").select2();

                if(billings_invoice_no.length > 0)
                {
                    $.each(billings_invoice_no, function(key, val) {
                        var option = $('<option />');
                        if(parseFloat(val['outstanding']) > 0)
                        {
                            var billing_status = "Unpaid";
                        }
                        else
                        {
                            var billing_status = "Paid";
                        }
                        option.attr('class', "remove_option").attr('value', val['id']).text(val['invoice_no'] + " (" +billing_status+ ")");
                        
                        if(billing_credit_note_gst_record[0]["billing_id"] != null && val['id'] == billing_credit_note_gst_record[0]["billing_id"])
                        {
                            option.attr('selected', 'selected');
                            $('#latest_invoice_no_for_cn').attr('disabled', true);
                        }

                        $("#latest_invoice_no_for_cn").append(option);
                    });
                }
                else
                {
                    $("#latest_invoice_no_for_cn").val(0);
                }

                $("#latest_invoice_no_for_cn").select2();

                $("#credit_note_id").val(billing_credit_note_gst_record[0]["billing_credit_note_gst_id"]);

                $.each(currency, function(key, val) {
                    var option = $('<option />');
                    option.attr('class', "remove_client_option").attr('value', val["id"]).text(val["currency"]);
                    if(billing_credit_note_gst_record[0]["currency_id"] != null &&  val["id"] == billing_credit_note_gst_record[0]["currency_id"])
                    {
                        option.attr('selected', 'selected');
                    }
                    $('#currency').attr('disabled', true);
                    $('#currency').append(option);
                });

                if(billing_credit_note_gst_record[0]["billing_id"] != "0")
                {
                    $("#latest_credit_note_company_name").text(billing_credit_note_gst_record[0]["company_name"]);
                    $("#billing_outstanding").val(billing_credit_note_gst_record[0]["billing_outstanding"]);
                    $("#billing_no").val(billing_credit_note_gst_record[0]["invoice_no"]);

                    for(x in billing_credit_note_gst_record)
                    {
                        var table_cell = '<tr class="remove_cn_service_and_total"><td>'+billing_credit_note_gst_record[x]["service_name"]+'</td><td style="text-align:right;" class="invoice_amount" data-numRow="'+x+'" data-invoiceamount="'+billing_credit_note_gst_record[x]["previous_invoice_amount"]+'">'+addCommas(billing_credit_note_gst_record[x]["previous_invoice_amount"])+'</td><td class="td_amount_received" style="width: 200px;"><div class="cn_group"><input type="text" class="numberdes form-control applied_amount change_credit_note_amount" style="width:100%;text-align:right" placeholder="Amount" value="'+addCommas(billing_credit_note_gst_record[x]["cn_amount"])+'" name="received['+x+']"/><input type="hidden" name="billing_service_id['+x+']" value="'+billing_credit_note_gst_record[x]["billing_service_id"]+'"/><input type="hidden" name="invoice_amount['+x+']" value="'+billing_credit_note_gst_record[x]["previous_invoice_amount"]+'"/><input type="hidden" class="gst_rate" name="gst_rate['+x+']" value="'+billing_credit_note_gst_record[x]["gst_rate"]+'"/><input type="hidden" class="billing_credit_note_gst_record_id" name="billing_credit_note_gst_record_id['+x+']" value="'+billing_credit_note_gst_record[x]["billing_credit_note_gst_record_id"]+'"/></div></td></tr>';
                        $(".table").find('#latest_credit_note_info').append(table_cell);

                        $('#form_credit_note').formValidation('addField', 'received['+x+']', cn_received);
                    }

                    var table_total = '<tr class="remove_cn_service_and_total"><input type="hidden" name="latest_invoice_outstanding" value="'+billing_credit_note_gst_record[0]["billing_outstanding"]+'"/><input type="hidden" id="latest_total_cn_amount" name="latest_total_cn_amount" value="'+billing_credit_note_gst_record[0]["total_cn_amount"]+'"/><input type="hidden" class="latest_cn_out_of_balance" name="latest_cn_out_of_balance" value="'+billing_credit_note_gst_record[0]["cn_out_of_balance"]+'"/><input type="hidden" id="previous_total_cn_amount" name="previous_total_cn_amount" value="'+billing_credit_note_gst_record[0]["total_cn_amount"]+'"/><td align=right colspan=2>Invoice Outstanding</td><td align=right id="latest_invoice_outstanding">'+billing_credit_note_gst_record[0]["billing_outstanding"]+'</td></tr><tr class="remove_cn_service_and_total"><td align=right colspan=2>Total CN Amount</td><td align=right id="latest_received">'+addCommas(billing_credit_note_gst_record[0]["total_cn_amount"])+'</td></tr><tr class="remove_cn_service_and_total"><td align=right colspan=2>Out of Balance</td><td align=right id="latest_cn_out_of_balance">'+billing_credit_note_gst_record[0]["cn_out_of_balance"]+'</td></tr>';

                    $(".table").find('#latest_credit_note_total').append(table_total);
                    $("#latest_total_amount_discounted").val(addCommas(billing_credit_note_gst_record[0]["total_amount_discounted"]));

                    $("#latest_invoice_outstanding").text(addCommas(billing_credit_note_gst_record[0]["billing_outstanding"]));

                    $(".latest_credit_note_no").val(billing_credit_note_gst_record[0]['credit_note_no']);
                    $(".latest_credit_note_date").val(billing_credit_note_gst_record[0]['credit_note_date']);
                    $("#cn_rate").val(billing_credit_note_gst_record[0]['cn_rate']);

                    if(billing_credit_note_gst_record[0]["incorporation_date"] != null)
                    {
                        $array = billing_credit_note_gst_record[0]["incorporation_date"].split("/");
                        $tmp = $array[0];
                        $array[0] = $array[1];
                        $array[1] = $tmp;
                        $date_2 = $array[0]+"/"+$array[1]+"/"+$array[2];
                    }
                    else
                    {
                        $date_2 = "1/1/1990";
                    }

                    var date2 = new Date($date_2);

                    $('.latest_credit_note_date').datepicker({ 
                        dateFormat:'dd/mm/yyyy',
                        autoclose: true,
                    }).datepicker('setStartDate', date2)
                    .on('changeDate', function (selected) {
                        $('#form_credit_note').formValidation('revalidateField', 'latest_credit_note_date');
                    });

                    $('#form_credit_note').formValidation('revalidateField', 'latest_total_amount_discounted');
                }
                else
                {
                    $("#latest_credit_note_company_name").text("");
                    $("#credit_note_id").val(billing_credit_note_gst_record[0]["billing_credit_note_gst_id"]);
                    $("#latest_total_amount_discounted").val(addCommas(billing_credit_note_gst_record[0]["total_amount_discounted"]));
                    $(".latest_credit_note_no").val(billing_credit_note_gst_record[0]['credit_note_no']);
                    $(".latest_credit_note_date").val(billing_credit_note_gst_record[0]['credit_note_date']);
                    $("#latest_invoice_no_for_cn").select2("val", 0);
                }
                $("#saveCreditNote").attr("disabled", false);
                $("#modal_credit_note").modal("show");
            }
            // if(response.status == 1)
            // {
            //     $(".table").find('#credit_note_info').html(""); 
            //     $(".table").find('#credit_note_total').html(""); 

            //     var company_info = response.result;
            //     document.getElementById('credit_note_company_name').innerHTML = company_info[0]['company_name'];

            //     payment_mode(company_info[0]["payment_mode_id"]);

            //     var credit_note_outstanding = 0.00;
            //     var credit_note_discounted = 0.00;
            //     for(var b = 0; b < company_info.length; b++) 
            //     {
            //         credit_note_outstanding = credit_note_outstanding + parseFloat(company_info[b]['previous_outstanding'],2);

            //         credit_note_discounted = credit_note_discounted + parseFloat(company_info[b]['received'],2);

            //         var table_cell = '<tr><td style="text-align:right">'+(b+1)+'</td><td style="text-align:center">'+company_info[b]['invoice_date']+'</td><td>'+company_info[b]['invoice_no']+'</td><td align=right data-value="'+company_info[b]['amount']+'">'+addCommas(company_info[b]['amount'])+'</td><td align=right  class="outstanding_class" data-id="'+company_info[b]['id']+'" data-outstandingvalue="'+company_info[b]['previous_outstanding']+'">'+addCommas(company_info[b]['previous_outstanding'])+'</td><td class="td_amount_received"><div class="input-group"><input type="text" class="numberdes form-control applied_amount change_amount" style="width:100%;text-align:right" placeholder="Amount" value="'+addCommas(company_info[b]['received'])+'" name="received['+b+']" disabled="true"/></div><input type="hidden" name="id[]" value="'+company_info[b]['id']+'"/><input type="hidden" name="outstanding[]" value="'+company_info[b]['previous_outstanding']+'"/></td></tr>';

            //         $(".table").find('#credit_note_info').append( table_cell );

            //         $('#form_credit_note').formValidation('addField', 'received['+b+']', received);
            //     }

            //     var table_total = '<tr><td align=right colspan=4>Total</td><td align=right >'+addCommas(credit_note_outstanding.toFixed(2))+'</td><td align=right id="received"></td><input type="hidden" name="credit_note_id" value="'+company_info[0]['credit_note_id']+'"/></tr>';

            //     $(".table").find('#credit_note_total').append(table_total);
            //     $("#total_amount_discounted").val(addCommas(credit_note_discounted.toFixed(2)));
            //     $("#received").html(addCommas(credit_note_discounted.toFixed(2)));
            //     $('#form_credit_note').formValidation('revalidateField', 'total_amount_discounted');

            //     $(".credit_note_no").val(company_info[0]['credit_note_no']);
            //     $(".credit_note_date").val(company_info[0]['credit_note_date']);

            //     if(company_info[0]["incorporation_date"] != null)
            //     {
            //         $array = company_info[0]["incorporation_date"].split("/");
            //         $tmp = $array[0];
            //         $array[0] = $array[1];
            //         $array[1] = $tmp;
            //         $date_2 = $array[0]+"/"+$array[1]+"/"+$array[2];
            //     }
            //     else
            //     {
            //         $date_2 = "1/1/1990";
            //     }

            //     var date2 = new Date($date_2);

            //     $('.credit_note_date').datepicker({ 
            //         dateFormat:'dd/mm/yyyy',
            //         autoclose: true,
            //     }).datepicker('setStartDate', date2)
            //     .on('changeDate', function (selected) {
            //         $('#form_credit_note').formValidation('revalidateField', 'credit_note_date');
            //     });
            //     if(access_right_billing_module == "read" || access_right_template_module == "read")
            //     {
            //         $(".change_amount").attr("disabled", true);
            //     }

            //     $("#modal_edit_previous_credit_note").modal("show");
            // }
        }

    });
}

$(document).on("click",".open_edit_latest_credit_note",function() {
    var credit_note_id = $(this).data('id');
    var company_code = $(this).data('company_code');
    
    open_edit_latest_credit_note(credit_note_id, company_code);
});

if(bool_open_receipt)
{
	open_receipt(company_code);
}

function addCommas(nStr) {
    nStr += '';
    var x = nStr.split('.');
    var x1 = x[0];
    var x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}

/*	$("#total_amount_received").live('change',function(){
		var total_amount_received = $(this).val();

		total_amount_received = total_amount_received.replace(/\,/g,''); // 1125, but a string, so convert it to number
		total_amount_received = parseInt(total_amount_received,10);
		console.log(total_amount_received);
	});*/

(function( $ ) {
	'use strict';
	function sortNumbersIgnoreText(a, b, high) {

	    var reg = /[+-]?((\d+(\.\d*)?)|\.\d+)([eE][+-]?[0-9]+)?/;    
	    a = a.replace(/(<a[^>]+>|<a>|<\/a>)/g, '');
	    //a = a.match(reg);    
	    a = a.substr(-4);  
	    a = a !== null ? parseInt(a) : high;
	    b = b.replace(/(<a[^>]+>|<a>|<\/a>)/g, '');
	    b = b.substr(-4);    
	    b = b !== null ? parseInt(b) : high;

	    return ((a < b) ? -1 : ((a > b) ? 1 : 0));    
	}

	jQuery.extend( jQuery.fn.dataTableExt.oSort, {
	    "sort-numbers-ignore-text-asc": function (a, b) {
	        return sortNumbersIgnoreText(a, b, Number.POSITIVE_INFINITY);
	    },
	    "sort-numbers-ignore-text-desc": function (a, b) {
	        return sortNumbersIgnoreText(a, b, Number.NEGATIVE_INFINITY) * -1;
	    }
	});

	var datatableInit = function() {

		var table1 = $('#datatable-paid').DataTable({
			"columnDefs": [ {
	            "searchable": false,
	            "orderable": false,
	            'type': 'num', 
	            'targets': 0
	        },
	        { type: 'sort-numbers-ignore-text', targets: 3 } ],
	        "order": [[ 2, "desc" ], [3, 'desc']]
		});
		table1.on( 'order.dt search.dt', function () {
            table1.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();
		var table2 = $('#datatable-receipt').DataTable({
			"columnDefs": [ {
	            "searchable": false,
	            "orderable": false,
	            'type': 'num', 
	            "targets": 0
	        } ],
	        "order": [[ 3, 'desc' ]]
		});
		table2.on( 'order.dt search.dt', function () {
            table2.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();
		var table3 = $('#datatable-credit_note').DataTable({
			"columnDefs": [ {
	            "searchable": false,
	            "orderable": false,
	            'type': 'num', 
	            "targets": 0
	        }],
	        "order": [[3, 'desc']]
		});
		table3.on( 'order.dt search.dt', function () {
            table3.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();
        var table5 = $('#datatable-unassign_amount').DataTable({
            "columnDefs": [ {
                "searchable": false,
                "orderable": false,
                'type': 'num', 
                "targets": 0
            }],
            "order": [[1, 'desc']]
        });
        table5.on( 'order.dt search.dt', function () {
            table5.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();
		var table4 = $('#datatable-recurring').DataTable({
			"columnDefs": [ {
	            "searchable": false,
	            "orderable": false,
	            'type': 'num', 
	            "targets": 0
	        } ],
	        "order": [[ 2, 'desc' ]]
		});
		table4.on( 'order.dt search.dt', function () {
            table4.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();
	};

	$(function() {
		datatableInit();
	});

}).apply( this, [ jQuery ]);

//Using total to assign others value
$("#total_amount_received").live('change',function(){
    detect_out_of_balance();
});
// $("#total_amount_received").live('change',function(){
// 	data_id = [];
// 	poidata = "";

// 	var total_amount_received = $(this).val();

// 	$('#total_amount_received').val(addCommas(parseFloat(total_amount_received.replace(/\,/g,'')).toFixed(2)));
// 	$(".table").find('#received').html(addCommas(parseFloat(total_amount_received.replace(/\,/g,'')).toFixed(2))); 

// 	total_amount_received = total_amount_received.replace(/\,/g,''); // 1125, but a string, so convert it to number
// 	total = parseFloat(total_amount_received,2);

// 	if(total > 0)
// 	{
// 	    $('#unpaid_amount tbody tr td[data-outstandingvalue]').each(function(key, value) {
// 		    var dataValue =  this.getAttribute("data-outstandingvalue");
// 		    var dataId =  this.getAttribute("data-id");
// 		    var $row = $(this).closest("tr");

// 		    if(dataValue > 0)
// 		    {
// 		        if(total > dataValue || total == dataValue)
// 		        {
// 	                total = total - dataValue;
// 	                $row.find('.applied_amount').val(addCommas(parseFloat(dataValue).toFixed(2)));
// 	                data_id.push(dataId);
// 	           	}
// 	           	else
// 	           	{                            
// 		            $row.find('.applied_amount').val(addCommas(parseFloat(total).toFixed(2)));
// 		            if(total > 0)
// 		            {
// 		                poidata = dataId;
// 		                data_id.push(dataId);
// 		            }
// 		            total = 0;                                                        
// 		        }
// 	        }
// 	        $('#form_receipt').formValidation('revalidateField', 'received['+key+']');                 
// 	    });

// 		if(total > 0)
// 		{
// 	        var new_total_amount_receive = 0;
// 	        $('#unpaid_amount tbody tr td[data-outstandingvalue]').each(function() {
// 		    	new_total_amount_receive += parseFloat(this.getAttribute("data-outstandingvalue").replace(/\,/g,''));
// 		    });

// 		    $('#total_amount_received').val(addCommas(parseFloat(new_total_amount_receive).toFixed(2)));

// 	       	$(".table").find('#received').html(addCommas(parseFloat(new_total_amount_receive).toFixed(2)));
// 	    }
// 	}
// });

//detect the total_amount_discounted field in credit note
$("#latest_total_amount_discounted").live('change',function(){
    //data_id = [];
    detect_out_of_balance_cn();
    // poidata = "";

    // var total_amount_discounted = $(this).val();

    // $('#latest_total_amount_discounted').val(addCommas(parseFloat(total_amount_discounted.replace(/\,/g,'')).toFixed(2)));
    // $(".table").find('#latest_received').html(addCommas(parseFloat(total_amount_discounted.replace(/\,/g,'')).toFixed(2))); 

    // total_amount_discounted = total_amount_discounted.replace(/\,/g,''); // 1125, but a string, so convert it to number
    // total = parseFloat(total_amount_discounted,2);

    // if(total>0)
    // {
    //     $('#unpaid_amount tbody tr td[data-invoiceamount]').each(function(key, value) {
    //         var dataValue =  this.getAttribute("data-invoiceamount");
    //         var dataId =  this.getAttribute("data-numRow");
    //         var $row = $(this).closest("tr");

    //         if(dataValue > 0)
    //         {
    //             if(total > dataValue || total == dataValue)
    //             {
    //                 total = total - dataValue;
    //                 $row.find('.applied_amount').val(addCommas(parseFloat(dataValue).toFixed(2)));

    //                 //data_id.push(dataId);
    //             }
    //             else
    //             {                            
    //                 $row.find('.applied_amount').val(addCommas(parseFloat(total).toFixed(2)));

    //                 // if(total>0)
    //                 // {
    //                     //poidata=dataId;
    //                     //data_id.push(dataId);
    //                 //}
    //                 total=0;                                                        
    //             }
    //         }
    //         $('#form_receipt').formValidation('revalidateField', 'received['+dataId+']');                 
    //     });
    //     if(total>0)
    //     {
    //         var new_total_amount_receive = 0;
    //         $('#unpaid_amount tbody tr td[data-invoiceamount]').each(function() {
    //             new_total_amount_receive += parseFloat(this.getAttribute("data-invoiceamount").replace(/\,/g,''));
    //         });
    //         if(isNaN(new_total_amount_receive))
    //         {
    //             new_total_amount_receive = 0;
    //         }
    //         $('#latest_total_amount_discounted').val(addCommas(parseFloat(new_total_amount_receive).toFixed(2)));

    //         $(".table").find('#latest_received').html(addCommas(parseFloat(new_total_amount_receive).toFixed(2)));
    //     }
    // }
    // else
    // {
    //     if(isNaN(parseFloat(total_amount_discounted.replace(/\,/g,''))))
    //     {
    //         total_amount_discounted = 0;
    //         $('#latest_total_amount_discounted').val(addCommas(parseFloat(total_amount_discounted).toFixed(2)));
    //         $(".table").find('#latest_received').html(addCommas(parseFloat(total_amount_discounted).toFixed(2)));
    //     }

    //     $('#unpaid_amount tbody tr td[data-invoiceamount]').each(function(key, value) {
    //         var $row = $(this).closest("tr");
    //         var dataId =  this.getAttribute("data-numRow");
    //         $row.find('.applied_amount').val(addCommas(parseFloat(0).toFixed(2)));
    //         $('#form_receipt').formValidation('revalidateField', 'received['+dataId+']');   
    //     });
    // }
});
// $("#total_amount_discounted").live('change',function(){
//     data_id = [];
//     poidata = "";

//     var total_amount_discounted = $(this).val();

//     $('#total_amount_discounted').val(addCommas(parseFloat(total_amount_discounted.replace(/\,/g,'')).toFixed(2)));
//     $(".table").find('#received').html(addCommas(parseFloat(total_amount_discounted.replace(/\,/g,'')).toFixed(2))); 

//     total_amount_discounted = total_amount_discounted.replace(/\,/g,''); // 1125, but a string, so convert it to number
//     total = parseFloat(total_amount_discounted,2);
//     if(total>0)
//     {
//         $('#unpaid_amount tbody tr td[data-outstandingvalue]').each(function(key, value) {
//             var dataValue =  this.getAttribute("data-outstandingvalue");
//             var dataId =  this.getAttribute("data-id");
//             var $row = $(this).closest("tr");

//             if(dataValue > 0)
//             {
//                 if(total > dataValue || total == dataValue)
//                 {
//                     total = total - dataValue;
//                     $row.find('.applied_amount').val(addCommas(parseFloat(dataValue).toFixed(2)));

//                     data_id.push(dataId);
//                 }
//                 else
//                 {                            
//                     $row.find('.applied_amount').val(addCommas(parseFloat(total).toFixed(2)));

//                     if(total>0)
//                     {
//                         poidata=dataId;
//                         data_id.push(dataId);
//                     }
//                     total=0;                                                        
//                 }
//             }
//             $('#form_receipt').formValidation('revalidateField', 'received['+key+']');                 
//         });
//         if(total>0)
//         {
//             var new_total_amount_receive = 0;
//             $('#unpaid_amount tbody tr td[data-outstandingvalue]').each(function() {
//                 new_total_amount_receive += parseFloat(this.getAttribute("data-outstandingvalue").replace(/\,/g,''));
//             });
//             if(isNaN(new_total_amount_receive))
//             {
//                 new_total_amount_receive = 0;
//             }
//             $('#total_amount_discounted').val(addCommas(parseFloat(new_total_amount_receive).toFixed(2)));

//             $(".table").find('#received').html(addCommas(parseFloat(new_total_amount_receive).toFixed(2)));
//         }
//     }
//     else
//     {
//         if(isNaN(parseFloat(total_amount_discounted.replace(/\,/g,''))))
//         {
//             total_amount_discounted = 0;
//             $('#total_amount_discounted').val(addCommas(parseFloat(total_amount_discounted).toFixed(2)));
//             $(".table").find('#received').html(addCommas(parseFloat(total_amount_discounted).toFixed(2)));
//         }

//         $('#unpaid_amount tbody tr td[data-outstandingvalue]').each(function(key, value) {
//             var $row = $(this).closest("tr");
//             $row.find('.applied_amount').val(addCommas(parseFloat(0).toFixed(2)));
//             $('#form_receipt').formValidation('revalidateField', 'received['+key+']');   
//         });
//     }
// });

//When receipt date change the date
// $(".receipt_date").live('change',function(){
// 	var sum_total_outstanding = 0;
//     var sum_original_amount = 0;
//     var sum_equivalent_amount = 0;
// 	var from = stringToDate($(".receipt_date").val());

// 	$("#receipt_info tr").each(function() {
//         var receipt_row = $(this)
// 	    var row = $("#unpaid_amount tr");
// 	    var date = stringToDate(receipt_row.find("td").eq(1).text());
	    
// 	    //show all rows by default
// 	    var show = true;

// 	    //if from date is valid and row date is less than from date, hide the row
// 	    if (from && date > from)
// 	      show = false;

// 	    if (show)
// 	    {
//             receipt_row.show();
//             var num_receipt_outstanding = receipt_row.find('td.outstanding_class').text().replace(/\,/g,'');
//             var num_original_amount = receipt_row.find('.td_amount_received .applied_amount').val().replace(/\,/g,'');
            
//             if(num_receipt_outstanding != "")
//             {
//             	sum_total_outstanding += parseFloat(num_receipt_outstanding);
//             }
//             if(num_original_amount != "")
//             {
//                 sum_original_amount += parseFloat(num_original_amount);
//             }
            
//             if(receipt_row.find('.td_equival_amount .equival_amount').val() != undefined)
//             {
//                 var num_equival_amount = receipt_row.find('.td_equival_amount .equival_amount').val().replace(/\,/g,'');
//                 if(num_equival_amount != "")
//                 {
//                     sum_equivalent_amount += parseFloat(num_equival_amount);
//                 }
//             }
// 	    }
// 	    else
// 	    {
//             receipt_row.hide();
// 	    }
// 	 });

// 	$(".num_receipt_outstanding").text(addCommas(sum_total_outstanding.toFixed(2)));
//     $("#received").text(addCommas(sum_original_amount.toFixed(2)));
//     $("#td_equival_amount_total").text(addCommas(sum_equivalent_amount.toFixed(2)));

// 	if(sum_total_outstanding == 0)
// 	{
// 		$("#saveReceipt").attr("disabled", true);
//         $("#total_amount_received").val("");
//         $(".change_amount").val("");
//         $("#received").text("");
//         $(".equival_amount").val("");
//         $(".td_equival_amount_total").val("");
// 	}
// 	else
// 	{
// 		$("#saveReceipt").attr("disabled", false);
// 	}

// 	detect_currency($(".bank_account"), "billing");
//     detect_out_of_balance();
// });

//When credit note date change the date
$(".credit_note_date").live('change',function(){
	var sum_total_outstanding = 0;
	var from = stringToDate($(".credit_note_date").val());

	$("#unpaid_amount tr").each(function() {
	    var row = $(this);
	    var date = stringToDate(row.find("td").eq(1).text());
	    
	    //show all rows by default
	    var show = true;

	    //if from date is valid and row date is less than from date, hide the row
	    if (from && date > from)
	      show = false;

	    if (show)
	    {
	      row.show();
	      var num_credit_note_outstanding = row.find('td.outstanding_class').text().replace(/\,/g,'');
	      if(num_credit_note_outstanding != "")
	      {
	      	sum_total_outstanding += parseFloat(num_credit_note_outstanding);
	      }
	    }
	    else
	    {
	      row.hide();
	    }
	 });

	$(".num_credit_note_outstanding").text(addCommas(sum_total_outstanding.toFixed(2)));

	if(sum_total_outstanding == 0)
	{
		$("#saveCreditNote").attr("disabled", true);
	}
	else
	{
		$("#saveCreditNote").attr("disabled", false);
	}
});

//parse entered date. return NaN if invalid
function stringToDate(s) {
  var ret = NaN;
  var date_parts = s.split("/");
  date = new Date(date_parts[2], date_parts[1]-1, date_parts[0]);

  if (!isNaN(date.getTime())) {
    ret = date;
  }
  return ret;
}

function changeDateFormat(date) {
	var change_date_parts = date.split('/');
	var change_date = change_date_parts[2]+"/"+change_date_parts[1]+"/"+change_date_parts[0];
	return($.datepicker.formatDate('dd M yy',new Date(change_date))); 
}

//detect change the received field in credit note
$(".change_credit_note_amount").live('change',function(){
    data_id = [];
    poidata = "";

    var total_amount_discounted = $('#latest_total_amount_discounted').val();

    total_amount_discounted = total_amount_discounted.replace(/\,/g,'');
    total = parseFloat(total_amount_discounted,2);

    var row_data_id = $(this).parent().parent().parent().find(".invoice_amount").attr("data-numRow");

    var amount_received = $(this).val();
    
    if(amount_received == '')
    {
        amount_received = 0;
    }
    else
    {
        amount_received = amount_received.replace(/\,/g,''); // 1125, but a string, so convert it to number
        amount_received = parseFloat(amount_received);
    }
    
    var sum_total = 0;

    total = total - amount_received;

    $('#unpaid_amount tr td .change_credit_note_amount').each(function() {
        var gst_rate = $(this).parent().find(".gst_rate").val();
        
        if($(this).val() == "")
        {
            sum_total += 0;
            $(this).val(0);
        }
        else
        {
            sum_total += parseFloat($(this).val().replace(/\,/g,'')) * (1 + (parseInt(gst_rate)/100));
        }
        
    });
    //console.log(amount_received);
    //$('#latest_total_amount_discounted').val(addCommas(parseFloat(sum_total).toFixed(2)));
    $(".table").find('#latest_received').html(addCommas(parseFloat(sum_total).toFixed(2)));
    $("#latest_total_cn_amount").val(parseFloat(sum_total).toFixed(2));
    $(this).val(addCommas(parseFloat(amount_received).toFixed(2)));

    if(total > 0){
        //var new_total = 0; 
        $('#unpaid_amount tr td[data-invoiceamount]').each(function() {
            var dataValue =  this.getAttribute("data-invoiceamount");
            var dataId =  this.getAttribute("data-numRow");
            var $row = $(this).closest("tr");

            if(row_data_id == dataId)
            {
                if(amount_received > dataValue)
                {
                    $row.find('.applied_amount').val(addCommas(parseFloat(dataValue).toFixed(2)));
                }
                else
                {   
                    $row.find('.applied_amount').val(addCommas(parseFloat(amount_received).toFixed(2)));
                }
            }
            else
            {
                if(total > dataValue || total == dataValue)
                {
                    total = total - dataValue;
                    data_id.push(dataId);
                }
                else
                {                            
                    if(total>0)
                    {
                        poidata=dataId;
                        data_id.push(dataId);
                    }
                    total=0;                                                        
                }
            }
        });

        // $('#unpaid_amount tr td .change_credit_note_amount').each(function() {
        //     if($(this).val() == "")
        //     {
        //         new_total += 0;
        //         $(this).val(0);
        //     }
        //     else
        //     {
        //         new_total += parseFloat($(this).val().replace(/\,/g,''));
        //     }
            
        // });
        // $(".table").find('#latest_received').html(addCommas(parseFloat(new_total).toFixed(2)));
        detect_out_of_balance_cn($(this));
    }
    else
    {
        
        var invoice_amount = parseFloat($(this).parent().parent().parent().find(".invoice_amount").attr("data-invoiceamount").replace(/\,/g,''));

        if(amount_received > invoice_amount)
        {
            $(this).val(addCommas(parseFloat(invoice_amount).toFixed(2)));
        }
        else
        {   
            $(this).val(addCommas(parseFloat(amount_received).toFixed(2)));
        }
        //var new_total = 0; 

        

        //$('#latest_total_amount_discounted').val(addCommas(parseFloat(new_total).toFixed(2)));

        detect_out_of_balance_cn($(this));
    }
    $('#form_credit_note').formValidation('revalidateField', 'received['+row_data_id+']');
});

function detect_out_of_balance_cn(change_of_field = null)
{
    // if($(".out_of_balance_original_amount").css('display') === 'none')//$(".td_equival_amount_total").text() != ""
    // {
    //     //console.log("equival");
    //     if($(".td_equival_amount_total").text() != "")
    //     {
    //         if($(".hidden_out_of_balance").val() != undefined)
    //         {
    //             var out_of_balance_equival_amount = $(".hidden_out_of_balance").val().replace(/\,/g,'');
    //         }
    //         else
    //         {
    //             var out_of_balance_equival_amount = 0;
    //         }
    //         if($(".td_equival_amount_total").text() != "")
    //         {
    //             var td_equival_amount_total = $(".td_equival_amount_total").text().replace(/\,/g,'');
    //         }
    //         else
    //         {
    //             var td_equival_amount_total = 0;
    //         }
    //         var latest_total_amount_received = $("#total_amount_received").val().replace(/\,/g,'');

    //         var total_out_of_balance = parseFloat(latest_total_amount_received) - parseFloat(td_equival_amount_total);
    //         //console.log(total_out_of_balance);
    //         if(!isNaN(total_out_of_balance))
    //         {
    //             if(0 > total_out_of_balance)
    //             {
    //                 $('.out_of_balance_equival_amount').val(addCommas(parseFloat(0).toFixed(2)));
    //                 if(change_of_field != null)
    //                 {
    //                     change_of_field.val(addCommas(parseFloat(out_of_balance_equival_amount).toFixed(2)));
    //                 }
    //             }
    //             else
    //             {
    //                 $('.out_of_balance_equival_amount').val(addCommas(parseFloat(total_out_of_balance).toFixed(2)));
    //             }
    //         }
    //         else
    //         {
    //             $('.out_of_balance_equival_amount').val(addCommas(parseFloat(0).toFixed(2)));
    //         }
    //     }
    // }
    // else
    // {
        //console.log("original");
        var new_total = 0; 
        var usr_latest_total_amount_discounted = parseFloat($('#latest_total_amount_discounted').val().replace(/\,/g,''));
        var latest_invoice_outstanding = parseFloat($("#latest_invoice_outstanding").text().replace(/\,/g,''));
        //console.log(usr_latest_total_amount_discounted);
        //console.log(latest_invoice_outstanding);
        if(usr_latest_total_amount_discounted > latest_invoice_outstanding && latest_invoice_outstanding > 0)
        {
            $('#latest_total_amount_discounted').val("");
            toastr.error("Total Amount Discounted cannot greater than Invoice Outstanding amount.", "Error");
        }

        $('#unpaid_amount tr td .change_credit_note_amount').each(function() {
            var gst_rate = $(this).parent().find(".gst_rate").val();
            if(usr_latest_total_amount_discounted == 0)
            {
                new_total += 0; 
                $(this).val(0);
            }
            else
            {
                if($(this).val() == "")
                {
                    new_total += 0;
                    $(this).val(0);
                }
                else
                {
                    new_total += parseFloat($(this).val().replace(/\,/g,'')) * (1 + (parseInt(gst_rate)/100));
                }
            }
        });

        $(".table").find('#latest_received').html(addCommas(parseFloat(new_total).toFixed(2)));
        $("#latest_total_cn_amount").val(parseFloat(new_total).toFixed(2));

        if($(".hidden_out_of_balance_cn").val() != undefined)
        {
            var out_of_balance_original_amount = $(".hidden_out_of_balance_cn").val().replace(/\,/g,'');
        }
        else
        {
            var out_of_balance_original_amount = 0;
        }
        if($("#latest_received").text() != "")
        {
            var td_received_total = $("#latest_received").text().replace(/\,/g,'');
        }
        else
        {
            var td_received_total = 0;
        }
        //var latest_total_amount_discounted = $("#latest_total_amount_discounted").val().replace(/\,/g,'');
        
        var total_out_of_balance = latest_invoice_outstanding - parseFloat(td_received_total);
       // console.log(total_out_of_balance);
        if(!isNaN(total_out_of_balance))
        {
            if(0 > total_out_of_balance)
            {
                //var total_out_of_balance = parseFloat(latest_total_amount_discounted) - parseFloat(td_received_total);
                
                if(parseFloat(latest_invoice_outstanding) > 0)
                {
                    $('#latest_cn_out_of_balance').text(addCommas(parseFloat(0).toFixed(2)));
                    $('.latest_cn_out_of_balance').val(parseFloat(0).toFixed(2));
                    $('#latest_total_amount_discounted').val(parseFloat(td_received_total).toFixed(2));
                }
                else
                {
                    $('#latest_cn_out_of_balance').text(addCommas(parseFloat(-(total_out_of_balance)).toFixed(2)));
                    $('.latest_cn_out_of_balance').val(parseFloat(-(total_out_of_balance)).toFixed(2));
                    if(usr_latest_total_amount_discounted > parseFloat(new_total))
                    {
                        $('#latest_total_amount_discounted').val(parseFloat(new_total).toFixed(2));
                    }
                    else
                    {
                        $('#latest_total_amount_discounted').val(parseFloat(-(total_out_of_balance)).toFixed(2));
                    }
                }
                // if(change_of_field != null)
                // {
                //     change_of_field.val(addCommas(parseFloat(out_of_balance_original_amount).toFixed(2)));
                // }
            }
            else
            {
                
                $('#latest_cn_out_of_balance').text(addCommas(parseFloat(0).toFixed(2)));
                $('.latest_cn_out_of_balance').val(parseFloat(0).toFixed(2));
                if(parseFloat(td_received_total) > 0)
                {
                    $('#latest_total_amount_discounted').val(parseFloat(td_received_total).toFixed(2));
                }
            }
        }
        else
        {
            $('#latest_cn_out_of_balance').text(addCommas(parseFloat(0).toFixed(2)));
            $('.latest_cn_out_of_balance').val(parseFloat(0).toFixed(2));
            if(parseFloat(td_received_total) > 0)
            {
                $('#latest_total_amount_discounted').val(parseFloat(td_received_total).toFixed(2));
            }
        }
        $('#form_credit_note').formValidation('revalidateField', 'latest_total_amount_discounted');
    //}
}
// $(".change_credit_note_amount").live('change',function(){

// 	data_id = [];
// 	poidata = "";

// 	var total_amount_discounted = $('#total_amount_discounted').val();

// 	total_amount_discounted = total_amount_discounted.replace(/\,/g,'');
// 	total = parseFloat(total_amount_discounted,2);

// 	var row_data_id = $(this).parent().parent().parent().find(".outstanding_class").attr("data-id");

// 	var amount_received = $(this).val();
	
// 	if(amount_received == '')
// 	{
// 		amount_received = 0;
// 	}
// 	else
// 	{
// 		amount_received = amount_received.replace(/\,/g,''); // 1125, but a string, so convert it to number
// 		amount_received = parseFloat(amount_received);
// 	}
	
// 	var sum_total = 0;

// 	total = total - amount_received;

// 	$('#unpaid_amount tr td .change_credit_note_amount').each(function() {
// 		if($(this).val() == "")
// 		{
// 			sum_total += 0;
// 			$(this).val(0);
// 		}
// 		else
// 		{
// 			sum_total += parseFloat($(this).val().replace(/\,/g,''));
// 		}
		
// 	});

// 	$('#total_amount_discounted').val(addCommas(parseFloat(sum_total).toFixed(2)));
// 	$(".table").find('#received').html(addCommas(parseFloat(sum_total).toFixed(2)));

// 	if(total > 0){
//         $('#unpaid_amount tr td[data-outstandingvalue]').each(function() {
//         	var dataValue =  this.getAttribute("data-outstandingvalue");
// 		    var dataId =  this.getAttribute("data-id");
// 		    var $row = $(this).closest("tr");

// 		    if(row_data_id == dataId)
//             {
//                 if(amount_received > dataValue)
//                 {
//                     $row.find('.applied_amount').val(addCommas(parseFloat(dataValue).toFixed(2)));
//                 }
//                 else
//                 {   
//                     $row.find('.applied_amount').val(addCommas(parseFloat(amount_received).toFixed(2)));
//                 }
// 	        }
// 	        else
// 	        {
// 	        	if(total > dataValue || total == dataValue)
// 		        {
// 	                total = total - dataValue;
// 	                data_id.push(dataId);
// 	           	}
// 	           	else
// 	           	{                            
// 		            if(total>0)
// 		            {
// 		                poidata=dataId;
// 		                data_id.push(dataId);
// 		            }
// 		            total=0;                                                        
// 		        }
// 	        }
//         });
//     }
//     else
//     {
//     	var outstanding_value = parseFloat($(this).parent().parent().parent().find(".outstanding_class").attr("data-outstandingvalue").replace(/\,/g,''));

//     	if(amount_received > outstanding_value)
//     	{
//     		$(this).val(addCommas(parseFloat(outstanding_value).toFixed(2)));
//     	}
//     	var new_total = 0; 

//     	$('#unpaid_amount tr td .change_credit_note_amount').each(function() {
// 			if($(this).val() == "")
// 			{
// 				new_total += 0;
// 				$(this).val(0);
// 			}
// 			else
// 			{
// 				new_total += parseFloat($(this).val().replace(/\,/g,''));
// 			}
			
// 		});

// 		$('#total_amount_discounted').val(addCommas(parseFloat(new_total).toFixed(2)));

// 		$(".table").find('#received').html(addCommas(parseFloat(new_total).toFixed(2)));
//     }
// });

//detect equival_amount field in receipt
$(".equival_amount").live('change',function()
{
	var sum_equival_amount_total = 0;
    var equival_amount = $(this).val().replace(/\,/g,'');

	$('#unpaid_amount tr td .equival_amount').each(function() {
		if($(this).val() == "")
		{
			sum_equival_amount_total += 0;
			$(this).val(0);
		}
		else
		{
			sum_equival_amount_total += parseFloat($(this).val().replace(/\,/g,''));
		}
		
	});
    $(".table").find('.td_equival_amount_total').html(addCommas(parseFloat(sum_equival_amount_total).toFixed(2)));
    $(this).val(addCommas(parseFloat(equival_amount).toFixed(2)));
    //detect_total_amount_received_value();
    detect_out_of_balance($(this));
});

//detect change_amount field in receipt
$(".change_amount").live('change',function()
{
	data_id = [];
	poidata = "";

	var total_amount_received = $('#total_amount_received').val();

	total_amount_received = total_amount_received.replace(/\,/g,'');
	total = parseFloat(total_amount_received,2);

	var row_data_id = $(this).parent().parent().parent().find(".outstanding_class").attr("data-id");

	var amount_received = $(this).val();
	
	if(amount_received == '')
	{
		amount_received = 0;
	}
	else
	{
		amount_received = amount_received.replace(/\,/g,''); // 1125, but a string, so convert it to number
		amount_received = parseFloat(amount_received);
	}
	
	var sum_total = 0;
	total = total - amount_received;

	$('#unpaid_amount tr td .change_amount').each(function() {
		if($(this).val() == "")
		{
			sum_total += 0;
			$(this).val(0);
		}
		else
		{
			sum_total += parseFloat($(this).val().replace(/\,/g,''));
		}
	});

    $(".table").find('#received').html(addCommas(parseFloat(sum_total).toFixed(2)));
    $(this).val(addCommas(parseFloat(amount_received).toFixed(2)));
    //detect_total_amount_received_value();
    // console.log(amount_received);
    // console.log(total);
	if(total > 0)
	{
        var new_total = 0; 
        $('#unpaid_amount tr td[data-outstandingvalue]').each(function() 
        {
        	var dataValue =  parseFloat(this.getAttribute("data-outstandingvalue").replace(/\,/g,''));
		    var dataId =  this.getAttribute("data-id");
		    var $row = $(this).closest("tr");

		    if(row_data_id == dataId)
		    {
                if(amount_received > dataValue)
                {
                    $row.find('.applied_amount').val(addCommas(parseFloat(dataValue).toFixed(2)));
                }
                else
                {   
                    $row.find('.applied_amount').val(addCommas(parseFloat(amount_received).toFixed(2)));
                }
	        }
	        else
	        {
	        	if(total > dataValue || total == dataValue)
		        {
	                total = total - dataValue;
	                data_id.push(dataId);
	           	}
	           	else
	           	{                            
		            if(total > 0)
		            {
		                poidata = dataId;
		                data_id.push(dataId);
		            }
		            total = 0;                                                        
		        }
	        }
        });

        $('#unpaid_amount tr td .change_amount').each(function() {
            if($(this).val() == "")
            {
                new_total += 0;
                $(this).val(0);
            }
            else
            {
                new_total += parseFloat($(this).val().replace(/\,/g,''));
            }
            
        });

        $(".table").find('#received').html(addCommas(parseFloat(new_total).toFixed(2)));
        detect_out_of_balance($(this));
    }
    else
    {
    	var new_total = 0; 
        var outstanding_value = parseFloat($(this).parent().parent().parent().find(".outstanding_class").attr("data-outstandingvalue").replace(/\,/g,''));
        if(amount_received > outstanding_value)
        {
            $(this).val(addCommas(parseFloat(outstanding_value).toFixed(2)));
        }
        else
        {   
            $(this).val(addCommas(parseFloat(amount_received).toFixed(2)));
        }

    	$('#unpaid_amount tr td .change_amount').each(function() {
			if($(this).val() == "")
			{
				new_total += 0;
				$(this).val(0);
			}
			else
			{
				new_total += parseFloat($(this).val().replace(/\,/g,''));
			}
			
		});

		$(".table").find('#received').html(addCommas(parseFloat(new_total).toFixed(2)));
		//detect_total_amount_received_value();
        detect_out_of_balance($(this));
    }
});

//calculate the out of balance and put in the field
function detect_out_of_balance(change_of_field = null)
{
    if($(".out_of_balance_original_amount").css('display') === 'none')//$(".td_equival_amount_total").text() != ""
    {
        //console.log("equival");
        if($(".td_equival_amount_total").text() != "")
        {
            if($(".hidden_out_of_balance").val() != undefined)
            {
                var out_of_balance_equival_amount = $(".hidden_out_of_balance").val().replace(/\,/g,'');
            }
            else
            {
                var out_of_balance_equival_amount = 0;
            }
            if($(".td_equival_amount_total").text() != "")
            {
                var td_equival_amount_total = $(".td_equival_amount_total").text().replace(/\,/g,'');
            }
            else
            {
                var td_equival_amount_total = 0;
            }
            var latest_total_amount_received = $("#total_amount_received").val().replace(/\,/g,'');

            var total_out_of_balance = parseFloat(latest_total_amount_received) - parseFloat(td_equival_amount_total);
            //console.log(total_out_of_balance);
            if(!isNaN(total_out_of_balance))
            {
                if(0 > total_out_of_balance)
                {
                    $('.out_of_balance_equival_amount').val(addCommas(parseFloat(0).toFixed(2)));
                    if(change_of_field != null)
                    {
                        change_of_field.val(addCommas(parseFloat(out_of_balance_equival_amount).toFixed(2)));
                    }
                }
                else
                {
                    $('.out_of_balance_equival_amount').val(addCommas(parseFloat(total_out_of_balance).toFixed(2)));
                }
            }
            else
            {
                $('.out_of_balance_equival_amount').val(addCommas(parseFloat(0).toFixed(2)));
            }
        }
    }
    else
    {
        //console.log("original");
        

        if($(".tr_unassign_amount_receipt").is(":visible"))
        {
            var unassign_amt = $(".unassign_amt").val().replace(/\,/g,'');
            if(parseFloat($("#total_amount_received").val().replace(/\,/g,'')) > parseFloat(unassign_amt))
            {
                $("#total_amount_received").val(addCommas(parseFloat(unassign_amt).toFixed(2)));
            }
            //console.log(unassign_amt);
        }

        if($(".hidden_out_of_balance").val() != undefined)
        {
            var out_of_balance_original_amount = $(".hidden_out_of_balance").val().replace(/\,/g,'');
        }
        else
        {
            var out_of_balance_original_amount = 0;
        }
        if($("#received").text() != "")
        {
            var td_received_total = $("#received").text().replace(/\,/g,'');
        }
        else
        {
            var td_received_total = 0;
        }
        var latest_total_amount_received = $("#total_amount_received").val().replace(/\,/g,'');
        var total_out_of_balance = parseFloat(latest_total_amount_received) - parseFloat(td_received_total);
        //console.log(total_out_of_balance);
        if(!isNaN(total_out_of_balance))
        {
            if(0 > total_out_of_balance)
            {
                $('.out_of_balance_original_amount').val(addCommas(parseFloat(0).toFixed(2)));
                if(change_of_field != null)
                {
                    change_of_field.val(addCommas(parseFloat(out_of_balance_original_amount).toFixed(2)));
                }
            }
            else
            {
                $('.out_of_balance_original_amount').val(addCommas(parseFloat(total_out_of_balance).toFixed(2)));
            }
        }
        else
        {
            $('.out_of_balance_original_amount').val(addCommas(parseFloat(0).toFixed(2)));
        }


    }
}
// function detect_total_amount_received_value()
// {
// 	// console.log($("#received").text());
// 	// console.log($(".td_equival_amount_total").text());
// 	if($(".td_equival_amount_total").text() != "")
// 	{
// 		$('#total_amount_received').val($(".td_equival_amount_total").text());
// 	}
// 	else
// 	{
// 		$('#total_amount_received').val($("#received").text());
// 	}
// }

$(document).on('change','#form_receipt #payment_mode',function(e){
    $('#form_receipt').formValidation('revalidateField', 'payment_mode');
    $('#form_receipt').formValidation('revalidateField', 'reference_no');
});

$(document).on('change','#form_credit_note #latest_credit_note_no',function(e){
    $('#form_credit_note').formValidation('revalidateField', 'latest_credit_note_no');
});

function deleteBilling($billing_id, $receipt_id = null, $credit_note_id = null){
	bootbox.confirm({
        message: "Do you want to delete this selected info?",
        closeButton: false,
        buttons: {
            confirm: {
                label: 'Yes'
            },
            cancel: {
                label: 'No',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
        	if(result == true)
        	{
        		if($tab_aktif == "billing")
				{
					var billingCheckboxes = new Array();
					//$('input[name="billing_checkbox"]:checked').each(function() {
					billingCheckboxes.push($billing_id);
					//});
                    $('#loadingmessage').show();
					$.ajax({
						type: "POST",
						url: "billings/delete_billing",
						data: {"billing_id":billingCheckboxes, "tab": $tab_aktif}, // <--- THIS IS THE CHANGE
						dataType: "json",
						success: function(response){
                            $('#loadingmessage').hide();
							if(response.Status == 1)
							{
								toastr.success(response.message, response.title);
								location.reload();
							}
                            else
                            {
                                toastr.error(response.message, response.title);
                            }
						}				
					});
				}
				else if($tab_aktif == "receipt")
				{
					var receiptCheckboxes = new Array();
					var billingCheckboxes = new Array();
					//$('input[name="receipt_checkbox"]:checked').each(function() {
					receiptCheckboxes.push($receipt_id);
					//});

					// $('input[name="billing_id_chkbox"]').each(function() {
					billingCheckboxes.push($billing_id);
					// });
                    $('#loadingmessage').show();
					$.ajax({
						type: "POST",
						url: "billings/delete_billing",
						data: {"receipt_id":receiptCheckboxes, "billing_id":billingCheckboxes, "tab": $tab_aktif}, // <--- THIS IS THE CHANGE
						dataType: "json",
						success: function(response){
                            $('#loadingmessage').hide();
							if(response.Status == 1)
							{
								toastr.success(response.message, response.title);
								location.reload();
							}
                            else
                            {
                                toastr.error(response.message, response.title);
                            }
						}				
					});
				}
				else if($tab_aktif == "credit_note")
				{
					var receiptCheckboxes = new Array();
					var billingCheckboxes = new Array();
					var creditNoteCheckboxes = new Array();
					//$('input[name="receipt_checkbox"]:checked').each(function() {
					receiptCheckboxes.push($receipt_id);
					//});

					// $('input[name="billing_id_chkbox"]').each(function() {
					billingCheckboxes.push($billing_id);
					// });
					creditNoteCheckboxes.push($credit_note_id);
                    $('#loadingmessage').show();
					$.ajax({
						type: "POST",
						url: "billings/delete_billing",
						data: {"receipt_id":receiptCheckboxes, "billing_id":billingCheckboxes, "credit_note_id":creditNoteCheckboxes, "tab": $tab_aktif}, // <--- THIS IS THE CHANGE
						dataType: "json",
						success: function(response){
                            $('#loadingmessage').hide();
							if(response.Status == 1)
							{
								toastr.success(response.message, response.title);
								location.reload();
							}
                            else
                            {
                                toastr.error(response.message, response.title);
                            }
						}				
					});
				}
				else if($tab_aktif == "recurring")
				{
					var billingCheckboxes = new Array();
					//$('input[name="billing_checkbox"]:checked').each(function() {
					billingCheckboxes.push($billing_id);
					//});
                    $('#loadingmessage').show();
					$.ajax({
						type: "POST",
						url: "billings/delete_billing",
						data: {"recurring_billing_id":billingCheckboxes, "tab": $tab_aktif}, // <--- THIS IS THE CHANGE
						dataType: "json",
						success: function(response){
                            $('#loadingmessage').hide();
							if(response.Status == 1)
							{
								toastr.success(response.message, response.title);
								location.reload();
							}
                            else
                            {
                                toastr.error(response.message, response.title);
                            }
						}				
					});
				}
        	}

        }
    });
}

function pushInvoiceToQB($billing_id){
    bootbox.confirm({
        message: "Do you want to import this selected info to Quickbook Online?",
        closeButton: false,
        buttons: {
            confirm: {
                label: 'Yes'
            },
            cancel: {
                label: 'No',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            if(result == true)
            {
                if($tab_aktif == "billing")
                {
                    var billingCheckboxes = new Array();
                    billingCheckboxes.push($billing_id);
                    $('#loadingmessage').show();
                    $.ajax({
                        type: "POST",
                        url: "billings/create_invoice_in_qb",
                        data: {"billing_id":billingCheckboxes, "tab": $tab_aktif}, // <--- THIS IS THE CHANGE
                        dataType: "json",
                        success: function(response){
                            $('#loadingmessage').hide();
                            if(response.Status == 1)
                            {
                                toastr.success(response.message, response.title);
                            }
                            else
                            {
                                toastr.error(response.message, response.title);
                            }
                        }               
                    });
                }
            }
        }
    });
}

function pushReceiptToQB($receipt_id){
    bootbox.confirm({
        message: "Do you want to import this selected info to Quickbook Online?",
        closeButton: false,
        buttons: {
            confirm: {
                label: 'Yes'
            },
            cancel: {
                label: 'No',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            if(result == true)
            {
                if($tab_aktif == "receipt")
                {
                    var billingCheckboxes = new Array();
                    billingCheckboxes.push($receipt_id);
                    $('#loadingmessage').show();
                    $.ajax({
                        type: "POST",
                        url: "billings/create_receipt_in_qb",
                        data: {"receipt_id":billingCheckboxes, "tab": $tab_aktif},
                        dataType: "json",
                        success: function(response){
                            $('#loadingmessage').hide();
                            if(response.Status == 1)
                            {
                                toastr.success(response.message, response.title);
                            }
                            else
                            {
                                toastr.error(response.message, response.title);
                            }
                        }               
                    });
                }
            }
        }
    });
}

function pushCreditNoteToQB($credit_note_id)
{
    bootbox.confirm({
        message: "Do you want to import this selected info to Quickbook Online?",
        closeButton: false,
        buttons: {
            confirm: {
                label: 'Yes'
            },
            cancel: {
                label: 'No',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            if(result == true)
            {
                if($tab_aktif == "credit_note")
                {
                    var billingCheckboxes = new Array();
                    billingCheckboxes.push($credit_note_id);
                    $('#loadingmessage').show();
                    $.ajax({
                        type: "POST",
                        url: "billings/create_credit_note_in_qb",
                        data: {"credit_note_id":billingCheckboxes, "tab": $tab_aktif},
                        dataType: "json",
                        success: function(response){
                            $('#loadingmessage').hide();
                            if(response.Status == 1)
                            {
                                toastr.success(response.message, response.title);
                            }
                            else
                            {
                                toastr.error(response.message, response.title);
                            }
                        }               
                    });
                }
            }
        }
    });
}

//-----------connect quickbook---------------------------
check_connection_button();
function check_connection_button(){
    if(check_qb_token)
    {
        $(".connect_quickbook").hide();
        $(".disconnect_quickbook").show();
    }
    else
    {
        $(".connect_quickbook").show();
        $(".disconnect_quickbook").hide();
    }
}

function popupWindow(url, windowName, win, w, h) {
    const y = win.top.outerHeight / 2 + win.top.screenY - ( h / 2);
    const x = win.top.outerWidth / 2 + win.top.screenX - ( w / 2);
    return win.open(url, windowName, `toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=${w}, height=${h}, top=${y}, left=${x}`);
}

function connect_quickbook(){
    // const popupWinWidth = "1200";
    // const popupWinHeight = "750";
    // var left = (screen.width - popupWinWidth) / 2;
    // var top = (screen.height - popupWinHeight) / 4;

    const w = "900";
    const h = "500";

    // Fixes dual-screen position                             Most browsers      Firefox
    const dualScreenLeft = window.screenLeft !==  undefined ? window.screenLeft : window.screenX;
    const dualScreenTop = window.screenTop !==  undefined   ? window.screenTop  : window.screenY;

    const width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
    const height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

    const systemZoom = width / window.screen.availWidth;
    const left = (screen.width - w) / 2;
    const top =  (screen.height - h) / 4; // for 25% - devide by 4  |  for 33% - devide by 3
      
    var myWindow = window.open('./quickbook_auth/auth_request_accounting', "Connect to Quickbook Online", 
            'resizable=yes, width=' + (w / systemZoom)
            + ', height=' + (h) + ', top='
            + top + ', left=' + left);

    $.ajax({
        type: 'POST',
        url: '/echo/json/',
        success: function (data) {
            myWindow.location;
        }
    });

    var loop = setInterval(function() {   
    if(myWindow.closed) {  
            clearInterval(loop); 
            $('#loadingmessage').show(); 
            $.ajax({ 
                url: "billings/check_qb_token_after_login",
                type: "GET",
                success: function (response,data) {
                    $('#loadingmessage').hide(); 
                    check_qb_token = response;
                    check_connection_button();
                }
            });
        }  
    }, 1000); 
}

function disconnect_quickbook(){
    $('#loadingmessage').show(); 
    $.ajax({
        url: "quickbook_auth/revoke_token_accounting",
        type: "GET",
        success: function (response,data) {
            $('#loadingmessage').hide(); 
            check_qb_token = response;
            check_connection_button();
        }
    });
}

if(qb_company_id == "")
{
    $(".import_billing_to_quickbook").hide();
}
else
{
    $(".import_billing_to_quickbook").show();
    check_connection_button();
}
//-------------------------------------------------------

//Export Statement
function exportStatement(){
	var search_statement_data = $("#form_search_billing").serialize();
    var search_text = $("#form_search_billing").find(".billing_search").val().trim();
    if(search_text != "")
    {
        var url = "createbillingpdf/create_statement_pdf";
    }
    else
    {
        var url = "createbillingpdf/create_all_statement_pdf";
    }
	$('#loadingmessage').show();
	$.ajax({
		type: "POST",
		url: url,
		data: search_statement_data, // <--- THIS IS THE CHANGE
		dataType: "json",
		success: function(response){
			if(response.status == 1)
			{
				for(var b = 0; b < response.link.length; b++) 
				{
					window.open(
						  response.link[b],
						  '_blank' // <- This is what makes it open in a new window.
						);
				}
			}
			else
			{
				toastr.error("No Data can be export.", "Error");
			}
			$('#loadingmessage').hide();
		}				
	});
}

//Export PDF
function exportPDF($billing_id, $receipt_id = null, $credit_note_id = null){
	bootbox.confirm({
        message: "Do you want to print Pre-printed Letterhead document?",
        closeButton: false,
        buttons: {
            confirm: {
                label: 'Yes'
            },
            cancel: {
                label: 'No',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            if($tab_aktif == "billing")
			{
				// var billingCheckboxes = new Array();
				// $('input[name="billing_checkbox"]:checked').each(function() {
				//    billingCheckboxes.push($(this).val());
				// });
				var billingCheckboxes = new Array();
				billingCheckboxes.push($billing_id);

				$('#loadingmessage').show();
				$.ajax({
					type: "POST",
					url: "createbillingpdf/create_billing_pdf",
					data: {"billing_id":billingCheckboxes, "tab": $tab_aktif, "pre-printed": result}, // <--- THIS IS THE CHANGE
					dataType: "json",
					success: function(response){
						for(var b = 0; b < response.link.length; b++) 
						{
							$('#loadingmessage').hide();
							window.open(
								  response.link[b],
								  '_blank' // <- This is what makes it open in a new window.
								);
						}
					}				
				});
			}
			else if($tab_aktif == "receipt")
			{
				var receiptCheckboxes = new Array();
				// $('input[name="receipt_checkbox"]:checked').each(function() {
				//    receiptCheckboxes.push($(this).val());
				// });
				receiptCheckboxes.push($receipt_id);
                $('#loadingmessage').show();
				$.ajax({
					type: "POST",
					url: "createbillingpdf/create_billing_pdf",
					data: {"receipt_id":receiptCheckboxes, "tab": $tab_aktif, "pre-printed": result}, // <--- THIS IS THE CHANGE
					dataType: "json",
					success: function(response){
						for(var b = 0; b < response.link.length; b++) 
						{
                            $('#loadingmessage').hide();
							window.open(
								  response.link[b],
								  '_blank' // <- This is what makes it open in a new window.
								);

						}
					}				
				});
			}
			else if($tab_aktif == "credit_note")
			{
				var creditNoteCheckboxes = new Array();
				// $('input[name="receipt_checkbox"]:checked').each(function() {
				//    receiptCheckboxes.push($(this).val());
				// });
				creditNoteCheckboxes.push($credit_note_id);
                $('#loadingmessage').show();
				$.ajax({
					type: "POST",
					url: "createbillingpdf/create_billing_pdf",
					data: {"credit_note_id":creditNoteCheckboxes, "tab": $tab_aktif, "pre-printed": result}, // <--- THIS IS THE CHANGE
					dataType: "json",
					success: function(response){
						for(var b = 0; b < response.link.length; b++) 
						{
                            $('#loadingmessage').hide();
							window.open(
								  response.link[b],
								  '_blank' // <- This is what makes it open in a new window.
								);

						}
					}				
				});
			}
        }
    });
}

function exportOldCreditNotePDF($billing_id, $receipt_id = null, $credit_note_id = null){
    bootbox.confirm({
        message: "Do you want to print Pre-printed Letterhead document?",
        closeButton: false,
        buttons: {
            confirm: {
                label: 'Yes'
            },
            cancel: {
                label: 'No',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            var creditNoteCheckboxes = new Array();

            creditNoteCheckboxes.push($credit_note_id);
            $('#loadingmessage').show();
            $.ajax({
                type: "POST",
                url: "createbillingpdf/create_old_credit_note_pdf",
                data: {"credit_note_id":creditNoteCheckboxes, "tab": $tab_aktif, "pre-printed": result}, // <--- THIS IS THE CHANGE
                dataType: "json",
                success: function(response){
                    for(var b = 0; b < response.link.length; b++) 
                    {
                        $('#loadingmessage').hide();
                        window.open(
                              response.link[b],
                              '_blank' // <- This is what makes it open in a new window.
                            );

                    }
                }               
            });
        }
    });
}
// function deleteInvoicePDF()
// {
//     $.ajax({ //Upload common input
//       url: "createbillingpdf/delete_invoice",
//       async: false,
//       type: "POST",
//       //data: {"path":link},
//       dataType: 'json',
//       success: function (response,data) {
//         //console.log(response);
//       }
//     })
// }

// function deleteReceiptPDF()
// {
//     $.ajax({ //Upload common input
//       url: "createbillingpdf/delete_receipt",
//       async: false,
//       type: "POST",
//       //data: {"path":link},
//       dataType: 'json',
//       success: function (response,data) {
//         //console.log(response);
//       }
//     })
// }

// function deleteCreditNotePDF()
// {
// 	$.ajax({ //Upload common input
//       url: "createbillingpdf/delete_credit_note",
//       async: false,
//       type: "POST",
//       //data: {"path":link},
//       dataType: 'json',
//       success: function (response,data) {
//         //console.log(response);
//       }
//     })
// }

if($tab_aktif == "billing" || $tab_aktif == "payment1" || $tab_aktif == "credit_note" || $tab_aktif == "receipt" || $tab_aktif == "unassign_amount" || $tab_aktif == "recurring")
{
	$("#billing_footer_button").hide();
}
else
{
	$("#billing_footer_button").show();
}

$(document).on('click',".billing_check_state",function() {
		$tab_aktif = $(this).data("information");

		if($tab_aktif == "billing")
		{
			$(".edit_client_billing").show();
			$(".edit_client_recurring").hide();
            $(".open_credit_note").hide();

			$(".billing_statement").show();
		}
		else if($tab_aktif == "recurring")
		{
			$(".edit_client_recurring").show();
			$(".edit_client_billing").hide();
            $(".open_credit_note").hide();

			$(".billing_statement").hide();
		}
        else if($tab_aktif == "credit_note")
        {
            $(".edit_client_recurring").hide();
            $(".edit_client_billing").hide();
            $(".open_credit_note").show();

            $(".billing_statement").hide();
        }
		else
		{
			$(".edit_client_recurring").hide();
			$(".edit_client_billing").hide();
            $(".open_credit_note").hide();

			$(".billing_statement").hide();
		}

		if($tab_aktif == "billing" || $tab_aktif == "payment1" || $tab_aktif == "credit_note" || $tab_aktif == "receipt" || $tab_aktif == "unassign_amount" || $tab_aktif == "recurring")
		{
			$("#billing_footer_button").hide();
		}
		else
		{
			$("#billing_footer_button").show();
		}

});

function checkService(billing_element)
{
	var tr = jQuery(billing_element).parent().parent();

    var input_num = tr.parent().attr("num");

	$("select#service option").attr("disabled",false); //enable everything
         
     //collect the values from selected;
    var arr = $.map
    (
        $("select#service option:selected"), function(n)
        {
            return n.value;
        }
    );

    $("select#service").each(function() {

        var other_num = $(this).parent().parent().parent().attr("num");

        var selected_dropdown_value = $('select[name="service['+other_num+']"]').val();

         $('select[name="service['+other_num+']"] option').filter(function()
        {
            return $.inArray($(this).val(),arr)>-1;
        }).attr("disabled","disabled"); 

        $('select[name="service['+other_num+']"] option').filter(function()
        {
            return $(this).val() === selected_dropdown_value;
        }).attr("disabled", false);

        //$('select[name="service['+other_num+']"] option').val().attr("disabled", false);
        //return $(this).val();
    });
}

toastr.options = {
  "positionClass": "toast-bottom-right"
}

$('.selectall').click(function() {
    if ($(this).is(':checked')) {
        $(':checkbox.billing_checkbox').attr('checked', true);
    } else {
        $(':checkbox.billing_checkbox').attr('checked', false);
    }
});

$('.selectallreceipt').click(function() {
    if ($(this).is(':checked')) {
        $(':checkbox.receipt_checkbox').prop('checked', true);
    } else {
        $(':checkbox.receipt_checkbox').prop('checked', false);
    }
});

$(document).on("submit", "#form_credit_note", function(e){
	e.preventDefault();
	var $form = $(e.target);
	
    // and the FormValidation instance
    var fv = $form.data('formValidation');
    // Get the first invalid field
    var $invalidFields = fv.getInvalidFields().eq(0);
    // Get the tab that contains the first invalid field
    var $tabPane     = $invalidFields.parents();
    var valid_setup = fv.isValidContainer($tabPane);

    fv.disableSubmitButtons(false);

    if(valid_setup)
    {
        $("#saveCreditNote").attr("disabled", true);
        $("#currency").attr("disabled", false);
        $('#client_id').attr('disabled', false);
        $('#latest_invoice_no_for_cn').attr('disabled', false);
        $('#loadingmessage').show();
    	$.ajax({
	        type: 'POST',
	        url: "billings/save_credit_note",
	        data: $form.serialize(),
	        dataType: 'json',
	        success: function(response){
                $('#loadingmessage').hide();
                $("#currency").attr("disabled", true);
	            if (response.Status === 1) 
	            {
	            	$('#modal_credit_note').modal('toggle');
                    $("#saveCreditNote").attr("disabled", false);
                    toastr.success(response.message, response.title);
	            	location.reload();
	            }
                else if (response.Status === 2) 
                {
                    toastr.warning(response.message, response.title);
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
                else if (response.Status === 3) 
                {
                    toastr.error(response.message, response.title);
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
	        }
		});
    }
});

$(document).on('click',"#saveCreditNote",function(e){
    e.preventDefault();
    $("#form_credit_note").submit();
});

$(document).on("submit", "#form_receipt", function(e){
	e.preventDefault();
	var $form = $(e.target);
	
    // and the FormValidation instance
    var fv = $form.data('formValidation');
    // Get the first invalid field
    var $invalidFields = fv.getInvalidFields().eq(0);
    // Get the tab that contains the first invalid field
    var $tabPane     = $invalidFields.parents();
    var valid_setup = fv.isValidContainer($tabPane);
    fv.disableSubmitButtons(false);
    var check_total_received_amt = true;
    if(valid_setup)
    {
    	//$("#form_receipt").formValidation('destroy');
        if(!check_is_receipt_or_unassign)
        {
            if(parseFloat($(".out_of_balance_original_amount").val().replace(/\,/g,'')) > 0)
            {
                toastr.error("Total Amount Received need to be fully assigned in invoice amount.", "Error");
                check_total_received_amt = false;
            }
        }
        else
        {
            check_total_received_amt = true;
        }
        if(check_total_received_amt)
        {
        	$(".currency_total_amount_received").prop("disabled", false);
            $("#saveReceipt").attr("disabled", true);
            $(".reference_no").attr("disabled", false);
            $('#loadingmessage').show();
        	$.ajax({
    	        type: 'POST',
    	        url: "billings/save_receipt",
    	        data: $form.serialize(),
    	        dataType: 'json',
    	        success: function(response){
                    $('#loadingmessage').hide();
    	            if (response.Status === 1) 
    	            {
                        $("#saveReceipt").attr("disabled", false);
    	            	$('#modal_payment').modal('toggle');
                        toastr.success(response.message, response.title);
                        location.reload();
    	            }
                    else if (response.Status === 2) 
                    {
                        $(".reference_no").attr("disabled", true);
                        toastr.warning(response.message, response.title);
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    }
                    else if (response.Status === 3) 
                    {
                        $(".reference_no").attr("disabled", true);
                        $("#saveReceipt").attr("disabled", false);
                        toastr.error(response.message, response.title);
                        // setTimeout(function() {
                        //     location.reload();
                        // }, 1000);
                    }
    	        }
    		});
        }
    }
});

$(document).on('click',"#saveReceipt",function(e){
    $("#form_receipt").submit();
});

$(document).on('click',"#searchResult",function(e){
    $("#form_search_billing").submit();
});

$(document).on('click',".billing_check_state",function() {
	$tab_aktif = $(this).data("information");
	$(".submit_billing_check_state").val($tab_aktif);
});

if(billing_check_state)
{
	if(billing_check_state != "billing")
	{
		$('li[data-information="'+billing_check_state+'"]').addClass("active");
		$('#w2-'+billing_check_state+'').addClass("active");
		$('li[data-information="billing"]').removeClass("active");
		$('#w2-billing').removeClass("active");

		if(billing_check_state == "billing")
		{
			$(".edit_client_billing").show();
			$(".edit_client_recurring").hide();
		}
		else if(billing_check_state == "recurring")
		{
			$(".edit_client_recurring").show();
			$(".edit_client_billing").hide();
		}
	}
	
}

if(access_right_billing_module == "read" || access_right_template_module == "read")
{
	$('textarea').attr("disabled", true);
	$('.amount').attr("disabled", true);
}

function importToQB(){
    if($tab_aktif == "billing" || $tab_aktif == "receipt" || $tab_aktif == "credit_note")
    {
        $('.import_start_date').datepicker().datepicker('setStartDate', '01/01/1960');
        $('.import_end_date').datepicker().datepicker('setStartDate', '01/01/1960');

        if($tab_aktif == "billing")
        {
            var module_name = "Invoice";
        }
        else if($tab_aktif == "receipt")
        {
            var module_name = "Receipt";
        }
        else if($tab_aktif == "credit_note")
        {
            var module_name = "Credit Note";
        }
        $('.module_name').html(module_name);
        $('.import_start_date').val("").datepicker("update");
        $('.import_end_date').val("").datepicker("update");
        $('#modal_import_billings_to_qb').modal('toggle');
    }
    else
    {
        toastr.error("This cannot be import to Quickbook Online.", "Error");
    }
}

$(document).on('click',"#saveImportBillingsToQB",function(e){ //create_receipt_in_qb
    var import_start_date = $(".import_start_date").val();
    var import_end_date = $(".import_end_date").val();

    if($tab_aktif == "billing" || $tab_aktif == "receipt" || $tab_aktif == "credit_note")
    {
        if($tab_aktif == "billing")
        {
            var import_url = "billings/import_all_invoice_to_qb";
        }
        else if($tab_aktif == "receipt")
        {
            var import_url = "billings/import_all_receipt_to_qb";
        }
        else if($tab_aktif == "credit_note")
        {
            var import_url = "billings/import_all_cn_to_qb";
        }

        $('#loadingmessage').show();
        $.ajax({
            type: "POST",
            url: import_url,
            data: {"import_start_date": import_start_date, "import_end_date": import_end_date}, // <--- THIS IS THE CHANGE
            dataType: "json",
            success: function(response){
                $('#loadingmessage').hide();
                if(response.Status == 1)
                {
                    toastr.success(response.message, response.title);
                }
                else if(response.Status == 2)
                {
                    toastr.warning(response.message, response.title);
                }
                else if(response.Status == 3)
                {
                    toastr.error(response.message, response.title);
                }
            }               
        });
    }
    else
    {
        toastr.error("This cannot be import to Quickbook Online.", "Error");
    }
});

