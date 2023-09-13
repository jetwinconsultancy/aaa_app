var received = {
            row: '.input-group',
            validators: {
		        notEmpty: {
		            message: 'The Received field is required.'
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
    //excluded: [':disabled', ':hidden', ':not(:visible)'],
    fields: {
        receipt_date: {
        	row: '.receipt_date_div',
            validators: {
		        notEmpty: {
		            message: 'The Receipt Date field is required.'
		        }
		    }
        },
       /* receipt_no: {
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
		        notEmpty: {
		            message: 'The Reference No field is required.'
		        }
		    }
        },*/
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
                        console.log(options);
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
                        //console.log(options);
                        //console.log(value != "");
                        if(options == "1" && value == "")
                        {
                        	//console.log("false");
                        	return false;
                        }
                        else                        
                        {
                        	//console.log("true");
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
        credit_note_date: {
        	row: '.credit_note_date_div',
            validators: {
		        notEmpty: {
		            message: 'The Credit Note Date field is required.'
		        }
		    }
        },
        total_amount_discounted: {
        	row: '.input-group',
            validators: {
		        notEmpty: {
		            message: 'The Total Amount Discounted field is required.'
		        }
		    }
        },
        credit_note_no: {
        	row: '.input-group',
            validators: {
		        notEmpty: {
		            message: 'The Credit Note No field is required.'
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
add_total_outstanding();
function add_total_outstanding()
{	
	
	//console.log(billing_info);
	var total_statement = "";

	$.each(currency_info, function(currency_key, val) {
        var total_outstanding = 0;
        var currency_name = "";
        $.each(billing_info, function(key, val) {
        	if(billing_info[key]['currency_id'] == currency_info[currency_key]['id'])
        	{
        		currency_name = currency_info[currency_key]['currency'];
		        total_outstanding = total_outstanding + parseFloat(billing_info[key]['outstanding']);
		        console.log(billing_info[key]['outstanding']);
		    }
		    
	    });
	    if(currency_name != "")
	    {
	    	total_statement = total_statement + "(" + currency_name + ") " + addCommas(parseFloat(total_outstanding).toFixed(2)) + "<br/>";
	    	currency_name = "";
	    }
    });
    console.log(total_statement);
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
            console.log(data);
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

	function open_receipt(company_code) {
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
						console.log(response);
						var company_info = response.result;
						document.getElementById('receipt_company_name').innerHTML = company_info[0]['company_name'];

						payment_mode();

						var receipt_outstanding = 0.00;
						for(var b = 0; b < company_info.length; b++) 
						{
							receipt_outstanding = receipt_outstanding + parseFloat(company_info[b]['outstanding'],2);

				            var table_cell = '<tr><td style="text-align:right">'+(b+1)+'</td><td style="text-align:center">'+company_info[b]['invoice_date']+'</td><td>'+company_info[b]['invoice_no']+'</td><td align=right data-value="'+company_info[b]['amount']+'">'+addCommas(company_info[b]['amount'])+'</td><td align=right  class="outstanding_class" data-id="'+company_info[b]['id']+'" data-outstandingvalue="'+company_info[b]['outstanding']+'">'+addCommas(company_info[b]['outstanding'])+'</td><td class="td_amount_received"><div class="input-group"><input type="text" class="numberdes form-control applied_amount change_amount" style="width:100%;text-align:right" placeholder="Amount" value="" name="received['+b+']"/></div><input type="hidden" name="id[]" value="'+company_info[b]['id']+'"/><input type="hidden" name="outstanding[]" value="'+company_info[b]['outstanding']+'"/></td></tr>';

				            $(".table").find('#receipt_info').append( table_cell );

				            $('#form_receipt').formValidation('addField', 'received['+b+']', received);
				        }
				        console.log(receipt_outstanding);
				        var table_total = '<tr><td align=right colspan=4>Total</td><td align=right class="num_receipt_outstanding">'+addCommas(receipt_outstanding.toFixed(2))+'</td><td align=right id="received"></td></tr>';

				        $(".table").find('#receipt_total').append(table_total);
				        $("#total_amount_received").val("");
				        $('#form_receipt').formValidation('revalidateField', 'total_amount_received');

				        $(".receipt_no").val("");
				        $(".reference_no").val("");
				        $(".receipt_date").val("");

				        $array = company_info[0]["incorporation_date"].split("/");
						$tmp = $array[0];
						$array[0] = $array[1];
						$array[1] = $tmp;
						//unset($tmp);
						$date_2 = $array[0]+"/"+$array[1]+"/"+$array[2];
						console.log(new Date($date_2));

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

						$("#modal_payment").modal("show");
					}
						
				}				
			});
						
	}

	//$(function(){
	  	$(".open_reciept").click(function(){
		    var company_code = $(this).data('code');
		    console.log(company_code);
		    open_receipt(company_code);
	  	});

	  	function open_credit_note(company_code) {
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
							$(".table").find('#credit_note_info').html(""); 
							$(".table").find('#credit_note_total').html(""); 
							console.log(response);
							var company_info = response.result;
							document.getElementById('credit_note_company_name').innerHTML = company_info[0]['company_name'];

							var credit_note_outstanding = 0.00;
							for(var b = 0; b < company_info.length; b++) 
							{
								credit_note_outstanding = credit_note_outstanding + parseFloat(company_info[b]['outstanding'],2);

					            var table_cell = '<tr><td style="text-align:right">'+(b+1)+'</td><td style="text-align:center">'+company_info[b]['invoice_date']+'</td><td>'+company_info[b]['invoice_no']+'</td><td align=right data-value="'+company_info[b]['amount']+'">'+addCommas(company_info[b]['amount'])+'</td><td align=right  class="outstanding_class" data-id="'+company_info[b]['id']+'" data-outstandingvalue="'+company_info[b]['outstanding']+'">'+addCommas(company_info[b]['outstanding'])+'</td><td class="td_amount_received"><div class="input-group"><input type="text" class="numberdes form-control applied_amount change_credit_note_amount" style="width:100%;text-align:right" placeholder="Amount" value="" name="received['+b+']"/></div><input type="hidden" name="id[]" value="'+company_info[b]['id']+'"/><input type="hidden" name="outstanding[]" value="'+company_info[b]['outstanding']+'"/></td></tr>';

					            $(".table").find('#credit_note_info').append( table_cell );

					            $('#form_credit_note').formValidation('addField', 'received['+b+']', received);
					        }
					        console.log(credit_note_outstanding);
					        var table_total = '<tr><td align=right colspan=4>Total</td><td align=right class="num_credit_note_outstanding">'+addCommas(credit_note_outstanding.toFixed(2))+'</td><td align=right id="received"></td></tr>';

					        $(".table").find('#credit_note_total').append(table_total);
					        $("#total_amount_received").val("");
					        $('#form_credit_note').formValidation('revalidateField', 'total_amount_received');

					        $(".credit_note_no").val(response.credit_note_no);
					        $(".credit_note_date").val("");

					        $array = company_info[0]["incorporation_date"].split("/");
							$tmp = $array[0];
							$array[0] = $array[1];
							$array[1] = $tmp;
							//unset($tmp);
							$date_2 = $array[0]+"/"+$array[1]+"/"+$array[2];
							console.log(new Date($date_2));

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
								$(".change_credit_note_amount").attr("disabled", true);
							}

							$("#modal_credit_note").modal("show");
						}
							
					}				
				});
							
		}

	  	$(".open_credit_note").click(function(){
		    var company_code = $(this).data('code');
		    console.log(company_code);
		    open_credit_note(company_code);
	  	});
	//});
	function open_edit_receipt(receipt_id) {
		$.ajax({
			type: "POST",
			url: "billings/get_receipt_info",
			data: {"receipt_id":receipt_id}, // <--- THIS IS THE CHANGE

			dataType: "json",
			success: function(response){
				if(response.status == 1)
				{
					console.log(response);
					$(".table").find('#receipt_info').html(""); 
					$(".table").find('#receipt_total').html(""); 

					var company_info = response.result;
					document.getElementById('receipt_company_name').innerHTML = company_info[0]['company_name'];

					payment_mode(company_info[0]["payment_mode_id"]);

					var receipt_outstanding = 0.00;
					var receipt_received = 0.00;
					for(var b = 0; b < company_info.length; b++) 
					{
						receipt_outstanding = receipt_outstanding + parseFloat(company_info[b]['previous_outstanding'],2);

						receipt_received = receipt_received + parseFloat(company_info[b]['received'],2);

			            var table_cell = '<tr><td style="text-align:right">'+(b+1)+'</td><td style="text-align:center">'+company_info[b]['invoice_date']+'</td><td>'+company_info[b]['invoice_no']+'</td><td align=right data-value="'+company_info[b]['amount']+'">'+addCommas(company_info[b]['amount'])+'</td><td align=right  class="outstanding_class" data-id="'+company_info[b]['id']+'" data-outstandingvalue="'+company_info[b]['previous_outstanding']+'">'+addCommas(company_info[b]['previous_outstanding'])+'</td><td class="td_amount_received"><div class="input-group"><input type="text" class="numberdes form-control applied_amount change_amount" style="width:100%;text-align:right" placeholder="Amount" value="'+addCommas(company_info[b]['received'])+'" name="received['+b+']"/></div><input type="hidden" name="id[]" value="'+company_info[b]['id']+'"/><input type="hidden" name="outstanding[]" value="'+company_info[b]['previous_outstanding']+'"/></td></tr>';

			            $(".table").find('#receipt_info').append( table_cell );

			            $('#form_receipt').formValidation('addField', 'received['+b+']', received);
			        }
			        console.log(receipt_outstanding);
			        var table_total = '<tr><td align=right colspan=4>Total</td><td align=right >'+addCommas(receipt_outstanding.toFixed(2))+'</td><td align=right id="received"></td><input type="hidden" name="receipt_id" value="'+company_info[0]['receipt_id']+'"/></tr>';

			        $(".table").find('#receipt_total').append(table_total);
			        $("#total_amount_received").val(addCommas(receipt_received.toFixed(2)));
			        $("#received").html(addCommas(receipt_received.toFixed(2)));
			        $('#form_receipt').formValidation('revalidateField', 'total_amount_received');

			        $(".receipt_no").val(company_info[0]['receipt_no']);
			        $(".reference_no").val(company_info[0]['reference_no']);
			        $(".receipt_date").val(company_info[0]['receipt_date']);
			        //$("#total_amount_received").val(company_info[0]['received']);
			        //$(".receipt_date").val(company_info[0]['receipt_no']);


			        $array = company_info[0]["incorporation_date"].split("/");
					$tmp = $array[0];
					$array[0] = $array[1];
					$array[1] = $tmp;
					//unset($tmp);
					$date_2 = $array[0]+"/"+$array[1]+"/"+$array[2];
					console.log(new Date($date_2));

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

					$("#modal_payment").modal("show");
				}
			}

		});
	}
	$(".open_edit_reciept").click(function(){
		var receipt_id = $(this).data('id');
	    console.log(receipt_id);
	    open_edit_receipt(receipt_id);
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
					console.log(response);
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

			            var table_cell = '<tr><td style="text-align:right">'+(b+1)+'</td><td style="text-align:center">'+company_info[b]['invoice_date']+'</td><td>'+company_info[b]['invoice_no']+'</td><td align=right data-value="'+company_info[b]['amount']+'">'+addCommas(company_info[b]['amount'])+'</td><td align=right  class="outstanding_class" data-id="'+company_info[b]['id']+'" data-outstandingvalue="'+company_info[b]['previous_outstanding']+'">'+addCommas(company_info[b]['previous_outstanding'])+'</td><td class="td_amount_received"><div class="input-group"><input type="text" class="numberdes form-control applied_amount change_amount" style="width:100%;text-align:right" placeholder="Amount" value="'+addCommas(company_info[b]['received'])+'" name="received['+b+']"/></div><input type="hidden" name="id[]" value="'+company_info[b]['id']+'"/><input type="hidden" name="outstanding[]" value="'+company_info[b]['previous_outstanding']+'"/></td></tr>';

			            $(".table").find('#credit_note_info').append( table_cell );

			            $('#form_credit_note').formValidation('addField', 'received['+b+']', received);
			        }
			        console.log(credit_note_outstanding);
			        var table_total = '<tr><td align=right colspan=4>Total</td><td align=right >'+addCommas(credit_note_outstanding.toFixed(2))+'</td><td align=right id="received"></td><input type="hidden" name="credit_note_id" value="'+company_info[0]['credit_note_id']+'"/></tr>';

			        $(".table").find('#credit_note_total').append(table_total);
			        $("#total_amount_discounted").val(addCommas(credit_note_discounted.toFixed(2)));
			        $("#received").html(addCommas(credit_note_discounted.toFixed(2)));
			        $('#form_credit_note').formValidation('revalidateField', 'total_amount_discounted');

			        $(".credit_note_no").val(company_info[0]['credit_note_no']);
			        $(".credit_note_date").val(company_info[0]['credit_note_date']);
			        //$("#total_amount_received").val(company_info[0]['received']);
			        //$(".receipt_date").val(company_info[0]['receipt_no']);


			        $array = company_info[0]["incorporation_date"].split("/");
					$tmp = $array[0];
					$array[0] = $array[1];
					$array[1] = $tmp;
					//unset($tmp);
					$date_2 = $array[0]+"/"+$array[1]+"/"+$array[2];
					console.log(new Date($date_2));

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

					$("#modal_credit_note").modal("show");
				}
			}

		});
	}
	$(".open_edit_credit_note").click(function(){
		var credit_note_id = $(this).data('id');
	    console.log(credit_note_id);
	    open_edit_credit_note(credit_note_id);
	});

	console.log(bool_open_receipt);
	console.log(company_code);
	if(bool_open_receipt)
	{
		open_receipt(company_code);
	}

	/*$(function(){
	  $(".open_reciept").click(function(){
	     var company_code = $(this).data('code');
	     console.log(company_code);

	    $.ajax({
			type: "POST",
			url: "billings/get_billing_info",
			data: {"company_code":company_code}, // <--- THIS IS THE CHANGE
			dataType: "json",
			success: function(response){
					$(".table").find('#receipt_info').html(""); 
					$(".table").find('#receipt_total').html(""); 
					console.log(response);
					var company_info = response;
					document.getElementById('receipt_company_name').innerHTML = company_info[0]['company_name'];

					var receipt_outstanding = 0.00;
					for(var b = 0; b < company_info.length; b++) 
					{
						receipt_outstanding = receipt_outstanding + parseFloat(company_info[b]['outstanding'],2);

			            var table_cell = '<tr><td>'+(b+1)+'</td><td>'+company_info[b]['invoice_date']+'</td><td>'+company_info[b]['invoice_no']+'</td><td align=right data-value="'+company_info[b]['amount']+'">'+addCommas(company_info[b]['amount'])+'</td><td align=right  class="outstanding_class" data-id="'+company_info[b]['id']+'" data-outstandingvalue="'+company_info[b]['outstanding']+'">'+addCommas(company_info[b]['outstanding'])+'</td><td class="td_amount_received"><div class="input-group"><input type="text" class="numberdes form-control applied_amount change_amount" style="width:100%;text-align:right" placeholder="Amount" value="" name="received['+b+']"/></div><input type="hidden" name="id[]" value="'+company_info[b]['id']+'"/><input type="hidden" name="outstanding[]" value="'+company_info[b]['outstanding']+'"/></td></tr>';

			            $(".table").find('#receipt_info').append( table_cell );

			            $('#form_receipt').formValidation('addField', 'received['+b+']', received);
			        }
			        console.log(receipt_outstanding);
			        var table_total = '<tr><td align=right colspan=4>Total</td><td align=right >'+addCommas(receipt_outstanding)+'</td><td align=right id="received"></td></tr>';

			        $(".table").find('#receipt_total').append(table_total);
			        $("#total_amount_received").val("");
			        $('#form_receipt').formValidation('revalidateField', 'total_amount_received');

					$("#modal_payment").modal("show");
				}				
		});

	    
	  });
	});*/

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
	        "order": [[3, 'desc']]
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
	        },
	        { type: 'sort-numbers-ignore-text', targets: 3 } ],
	        "order": [[3, 'desc']]
		});
		table3.on( 'order.dt search.dt', function () {
            table3.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
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

$("#total_amount_received").live('change',function(){
	data_id = [];
	poidata = "";
/*poidata = "", poibalance = "";*/
/*var total = parseFloat(document.getElementById("total-amount").value);*/

	var total_amount_received = $(this).val();

	$('#total_amount_received').val(addCommas(parseFloat(total_amount_received.replace(/\,/g,'')).toFixed(2)));
	$(".table").find('#received').html(addCommas(parseFloat(total_amount_received.replace(/\,/g,'')).toFixed(2))); 

	total_amount_received = total_amount_received.replace(/\,/g,''); // 1125, but a string, so convert it to number
	total = parseFloat(total_amount_received,2);
	if(total>0)
	{
	    $('#unpaid_amount tbody tr td[data-outstandingvalue]').each(function(key, value) {
		    var dataValue =  this.getAttribute("data-outstandingvalue");
		    var dataId =  this.getAttribute("data-id");
		    var $row = $(this).closest("tr");
		    //console.log(key);
		    //var priceAmt = 0;
		    if(dataValue > 0)
		    {
		        if(total > dataValue || total == dataValue)
		        {
	                total = total - dataValue;
	                $row.find('.applied_amount').val(addCommas(parseFloat(dataValue).toFixed(2)));
	                /*$row.find('.balance').val('0.00');*/
	                data_id.push(dataId);
	           	}
	           	else
	           	{                            
		            //priceAmt = dataValue - total;
		            //$row.find('.applied_amount').val(total.toFixed(2));
		            $row.find('.applied_amount').val(addCommas(parseFloat(total).toFixed(2)));
		            /*$row.find('.balance').val(priceAmt.toFixed(2));*/
		            if(total>0)
		            {
		                //poibalance=priceAmt;
		                poidata=dataId;
		                data_id.push(dataId);
		            }
		            total=0;                                                        
		        }
	        }
	        $('#form_receipt').formValidation('revalidateField', 'received['+key+']');                 
	    });
		if(total>0)
		{
	        //alert('$' + total.toFixed(2) + ' remaining.');
	        var new_total_amount_receive = 0;
	        $('#unpaid_amount tbody tr td[data-outstandingvalue]').each(function() {
		    	new_total_amount_receive += parseFloat(this.getAttribute("data-outstandingvalue").replace(/\,/g,''));
		    });

		    $('#total_amount_received').val(addCommas(parseFloat(new_total_amount_receive).toFixed(2)));

	       	$(".table").find('#received').html(addCommas(parseFloat(new_total_amount_receive).toFixed(2)));
	    }

	    
	}

});

$("#total_amount_discounted").live('change',function(){
	data_id = [];
	poidata = "";
/*poidata = "", poibalance = "";*/
/*var total = parseFloat(document.getElementById("total-amount").value);*/

	var total_amount_discounted = $(this).val();

	$('#total_amount_discounted').val(addCommas(parseFloat(total_amount_discounted.replace(/\,/g,'')).toFixed(2)));
	$(".table").find('#received').html(addCommas(parseFloat(total_amount_discounted.replace(/\,/g,'')).toFixed(2))); 

	total_amount_discounted = total_amount_discounted.replace(/\,/g,''); // 1125, but a string, so convert it to number
	total = parseFloat(total_amount_discounted,2);
	if(total>0)
	{
	    $('#unpaid_amount tbody tr td[data-outstandingvalue]').each(function(key, value) {
		    var dataValue =  this.getAttribute("data-outstandingvalue");
		    var dataId =  this.getAttribute("data-id");
		    var $row = $(this).closest("tr");
		    //console.log(key);
		    //var priceAmt = 0;
		    if(dataValue > 0)
		    {
		        if(total > dataValue || total == dataValue)
		        {
	                total = total - dataValue;
	                $row.find('.applied_amount').val(addCommas(parseFloat(dataValue).toFixed(2)));
	                /*$row.find('.balance').val('0.00');*/
	                data_id.push(dataId);
	           	}
	           	else
	           	{                            
		            //priceAmt = dataValue - total;
		            //$row.find('.applied_amount').val(total.toFixed(2));
		            $row.find('.applied_amount').val(addCommas(parseFloat(total).toFixed(2)));
		            /*$row.find('.balance').val(priceAmt.toFixed(2));*/
		            if(total>0)
		            {
		                //poibalance=priceAmt;
		                poidata=dataId;
		                data_id.push(dataId);
		            }
		            total=0;                                                        
		        }
	        }
	        $('#form_receipt').formValidation('revalidateField', 'received['+key+']');                 
	    });
		if(total>0)
		{
	        //alert('$' + total.toFixed(2) + ' remaining.');
	        var new_total_amount_receive = 0;
	        $('#unpaid_amount tbody tr td[data-outstandingvalue]').each(function() {
		    	new_total_amount_receive += parseFloat(this.getAttribute("data-outstandingvalue").replace(/\,/g,''));
		    });

		    $('#total_amount_discounted').val(addCommas(parseFloat(new_total_amount_receive).toFixed(2)));

	       	$(".table").find('#received').html(addCommas(parseFloat(new_total_amount_receive).toFixed(2)));
	    }

	    
	}

});

/*$("input[id*='total-']").on('keyup', function() {       
    var ttl = //get Input Amount
    var gid = $(this).parents('tr').find('.ao_td_jn').attr('data-id');
    var Ajt = this.value;
    ttl = ttl - Ajt;
    if(ttl>0){
        $('#ao_amt_tbl tr td[data-value]').each(function() {
        var dv =  this.getAttribute("data-value");
        var dataId =  this.getAttribute("data-id");
        var $row = $(this).closest("tr");           
        if(gid == dataId){
        var balAmt = dv - Ajt;
        $row.find('.balance').val(balAmt.toFixed(2));
        }
        else{
        ...
        }
        ...
        }
    }
 });*/
$(".receipt_date").live('change',function(){
	var sum_total_outstanding = 0;
	var from = stringToDate($(".receipt_date").val());
	//console.log(from);
	$("#unpaid_amount tr").each(function() {
	    var row = $(this);
	    var date = stringToDate(row.find("td").eq(1).text());
	    
	    //show all rows by default
	    var show = true;

	    //if from date is valid and row date is less than from date, hide the row
	    if (from && date > from)
	      show = false;
	    
	    //if to date is valid and row date is greater than to date, hide the row
	    /*if (to && date > to)
	      show = false;*/

	    if (show)
	    {
	      row.show();
	      var num_receipt_outstanding = row.find('td.outstanding_class').text().replace(/\,/g,'');
	      //console.log(num_receipt_outstanding);
	      if(num_receipt_outstanding != "")
	      {
	      	sum_total_outstanding += parseFloat(num_receipt_outstanding);
	      }
	    }
	    else
	    {
	      row.hide();
	    }
	 });

	// $('#unpaid_amount tr td.outstanding_class').each(function() {
	// 	console.log($(this).text());
	// 	sum_total_outstanding += parseFloat($(this).text().replace(/\,/g,''));
		
	// });
	$(".num_receipt_outstanding").text(addCommas(sum_total_outstanding.toFixed(2)));

	if(sum_total_outstanding == 0)
	{
		$("#saveReceipt").attr("disabled", true);
	}
	else
	{
		$("#saveReceipt").attr("disabled", false);
	}
});

$(".credit_note_date").live('change',function(){
	var sum_total_outstanding = 0;
	var from = stringToDate($(".credit_note_date").val());
	//console.log(from);
	$("#unpaid_amount tr").each(function() {
	    var row = $(this);
	    var date = stringToDate(row.find("td").eq(1).text());
	    
	    //show all rows by default
	    var show = true;

	    //if from date is valid and row date is less than from date, hide the row
	    if (from && date > from)
	      show = false;
	    
	    //if to date is valid and row date is greater than to date, hide the row
	    /*if (to && date > to)
	      show = false;*/

	    if (show)
	    {
	      row.show();
	      var num_credit_note_outstanding = row.find('td.outstanding_class').text().replace(/\,/g,'');
	      //console.log(num_receipt_outstanding);
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

	// $('#unpaid_amount tr td.outstanding_class').each(function() {
	// 	console.log($(this).text());
	// 	sum_total_outstanding += parseFloat($(this).text().replace(/\,/g,''));
		
	// });
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
  // /console.log(date_parts);
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

$(".change_credit_note_amount").live('change',function(){

	data_id = [];
	poidata = "";

	var total_amount_discounted = $('#total_amount_discounted').val();

	total_amount_discounted = total_amount_discounted.replace(/\,/g,'');
	total = parseFloat(total_amount_discounted,2);

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

	//console.log("total===="+(parseFloat(sum_total)));

	$('#unpaid_amount tr td .change_credit_note_amount').each(function() {
		console.log($(this).val());
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

	console.log("total===="+(parseFloat(sum_total)));
	console.log("total===="+(parseFloat(total_amount_discounted)));
	/*if(parseFloat(sum_total,2) > parseFloat(total_amount_received,2))
	{*/
		//console.log(new_total_amount);
	    $('#total_amount_discounted').val(addCommas(parseFloat(sum_total).toFixed(2)));
	    $(".table").find('#received').html(addCommas(parseFloat(sum_total).toFixed(2)));
	//}

	if(total > 0){
        $('#unpaid_amount tr td[data-outstandingvalue]').each(function() {
        	var dataValue =  this.getAttribute("data-outstandingvalue");
		    var dataId =  this.getAttribute("data-id");
		    var $row = $(this).closest("tr");

		    if(row_data_id == dataId){
		        /*var balAmt = dv - Ajt;
		        $row.find('.balance').val(balAmt.toFixed(2));*/
		        console.log("amount_received===="+amount_received);
		        $row.find('.applied_amount').val(addCommas(parseFloat(amount_received).toFixed(2)));
	        }
	        else
	        {
	        	if(total > dataValue || total == dataValue)
		        {
	                total = total - dataValue;
	                //$row.find('.applied_amount').val(addCommas(dataValue));
	                /*$row.find('.balance').val('0.00');*/
	                data_id.push(dataId);
	           	}
	           	else
	           	{                            
		            //priceAmt = dataValue - total;
		            //$row.find('.applied_amount').val(total.toFixed(2));
		            //$row.find('.applied_amount').val(addCommas(total));
		            /*$row.find('.balance').val(priceAmt.toFixed(2));*/
		            if(total>0)
		            {
		                //poibalance=priceAmt;
		                poidata=dataId;
		                data_id.push(dataId);
		            }
		            total=0;                                                        
		        }
	        }
        });

        /*if(total > 0)
        {
	        // /alert('$' + total.toFixed(2) + ' remaining.');

	       	var new_total_amount = parseFloat($('#total_amount_received').val().replace(/\,/g,'')) - total;
	       	//console.log(new_total_amount);
	       	$('#total_amount_received').val(addCommas(new_total_amount));

	       	$(".table").find('#received').html(addCommas(new_total_amount)); 
        }*/
    }
    else
    {
    	//$('#total_amount_received').val(new_total_amount);
    	//console.log($(this).parent().parent().find(".outstanding_class").attr("data-outstandingvalue"));
    	var outstanding_value = parseFloat($(this).parent().parent().parent().find(".outstanding_class").attr("data-outstandingvalue").replace(/\,/g,''));
/*
    	if(outstanding_value > amount_received)
    	{
    		
    	}*/
    	if(amount_received > outstanding_value)
    	{
    		$(this).val(addCommas(parseFloat(outstanding_value).toFixed(2)));
    	}
    	var new_total = 0; 

    	$('#unpaid_amount tr td .change_credit_note_amount').each(function() {
			//console.log($(this).val());
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

		$('#total_amount_discounted').val(addCommas(parseFloat(new_total).toFixed(2)));

		$(".table").find('#received').html(addCommas(parseFloat(new_total).toFixed(2)));

    }

});

$(".change_amount").live('change',function(){

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

	//console.log("total===="+(parseFloat(sum_total)));

	$('#unpaid_amount tr td .change_amount').each(function() {
		console.log($(this).val());
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

	console.log("total===="+(parseFloat(sum_total)));
	console.log("total===="+(parseFloat(total_amount_received)));
	/*if(parseFloat(sum_total,2) > parseFloat(total_amount_received,2))
	{*/
		//console.log(new_total_amount);
	    $('#total_amount_received').val(addCommas(parseFloat(sum_total).toFixed(2)));
	    $(".table").find('#received').html(addCommas(parseFloat(sum_total).toFixed(2)));
	//}

	if(total > 0){
        $('#unpaid_amount tr td[data-outstandingvalue]').each(function() {
        	var dataValue =  this.getAttribute("data-outstandingvalue");
		    var dataId =  this.getAttribute("data-id");
		    var $row = $(this).closest("tr");

		    if(row_data_id == dataId){
		        /*var balAmt = dv - Ajt;
		        $row.find('.balance').val(balAmt.toFixed(2));*/
		        console.log("amount_received===="+amount_received);
		        $row.find('.applied_amount').val(addCommas(parseFloat(amount_received).toFixed(2)));
	        }
	        else
	        {
	        	if(total > dataValue || total == dataValue)
		        {
	                total = total - dataValue;
	                //$row.find('.applied_amount').val(addCommas(dataValue));
	                /*$row.find('.balance').val('0.00');*/
	                data_id.push(dataId);
	           	}
	           	else
	           	{                            
		            //priceAmt = dataValue - total;
		            //$row.find('.applied_amount').val(total.toFixed(2));
		            //$row.find('.applied_amount').val(addCommas(total));
		            /*$row.find('.balance').val(priceAmt.toFixed(2));*/
		            if(total>0)
		            {
		                //poibalance=priceAmt;
		                poidata=dataId;
		                data_id.push(dataId);
		            }
		            total=0;                                                        
		        }
	        }
        });

        /*if(total > 0)
        {
	        // /alert('$' + total.toFixed(2) + ' remaining.');

	       	var new_total_amount = parseFloat($('#total_amount_received').val().replace(/\,/g,'')) - total;
	       	//console.log(new_total_amount);
	       	$('#total_amount_received').val(addCommas(new_total_amount));

	       	$(".table").find('#received').html(addCommas(new_total_amount)); 
        }*/
    }
    else
    {
    	//$('#total_amount_received').val(new_total_amount);
    	//console.log($(this).parent().parent().find(".outstanding_class").attr("data-outstandingvalue"));
    	var outstanding_value = parseFloat($(this).parent().parent().parent().find(".outstanding_class").attr("data-outstandingvalue").replace(/\,/g,''));
/*
    	if(outstanding_value > amount_received)
    	{
    		
    	}*/
    	if(amount_received > outstanding_value)
    	{
    		$(this).val(addCommas(parseFloat(outstanding_value).toFixed(2)));
    	}
    	var new_total = 0; 

    	$('#unpaid_amount tr td .change_amount').each(function() {
			//console.log($(this).val());
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

		$('#total_amount_received').val(addCommas(parseFloat(new_total).toFixed(2)));

		$(".table").find('#received').html(addCommas(parseFloat(new_total).toFixed(2)));

    }

});

$(document).on('change','#form_receipt #payment_mode',function(e){
    $('#form_receipt').formValidation('revalidateField', 'payment_mode');
    $('#form_receipt').formValidation('revalidateField', 'reference_no');
});

$(document).on('change','#form_credit_note #credit_note_no',function(e){
    $('#form_credit_note').formValidation('revalidateField', 'credit_note_no');
});


/*$(document).on('change','#form_receipt #reference_no',function(e){
	console.log("reference_no");
    $('#form_receipt').formValidation('revalidateField', 'reference_no');
});*/

function deleteBilling($billing_id, $receipt_id = null, $credit_note_id = null){

	if($tab_aktif == "billing")
	{
		var billingCheckboxes = new Array();
		//$('input[name="billing_checkbox"]:checked').each(function() {
		   billingCheckboxes.push($billing_id);
		//});

		//console.log(billingCheckboxes);
		$.ajax({
			type: "POST",
			url: "billings/delete_billing",
			data: {"billing_id":billingCheckboxes, "tab": $tab_aktif}, // <--- THIS IS THE CHANGE
			dataType: "json",
			success: function(response){
				console.log(response);
				if(response.Status == 1)
				{
					toastr.success(response.message, response.title);
					location.reload();
				}
				/*console.log(window.URL);
				for(var b = 0; b < response.link.length; b++) 
				{
					//console.log(response);
					//window.location.href = 'http://localhost/dot/pdf/invoice/INV - 1521254993.pdf';
					window.open(
						  response.link[b],
						  '_blank' // <- This is what makes it open in a new window.
						);

				}*/

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

		//console.log(billingCheckboxes);
		$.ajax({
			type: "POST",
			url: "billings/delete_billing",
			data: {"receipt_id":receiptCheckboxes, "billing_id":billingCheckboxes, "tab": $tab_aktif}, // <--- THIS IS THE CHANGE
			dataType: "json",
			success: function(response){
				console.log(response);
				if(response.Status == 1)
				{
					toastr.success(response.message, response.title);
					location.reload();
				}
				/*console.log(window.URL);
				for(var b = 0; b < response.link.length; b++) 
				{
					//console.log(response);
					//window.location.href = 'http://localhost/dot/pdf/invoice/INV - 1521254993.pdf';
					window.open(
						  response.link[b],
						  '_blank' // <- This is what makes it open in a new window.
						);

				}*/

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
		//console.log(billingCheckboxes);
		$.ajax({
			type: "POST",
			url: "billings/delete_billing",
			data: {"receipt_id":receiptCheckboxes, "billing_id":billingCheckboxes, "credit_note_id":creditNoteCheckboxes, "tab": $tab_aktif}, // <--- THIS IS THE CHANGE
			dataType: "json",
			success: function(response){
				console.log(response);
				if(response.Status == 1)
				{
					toastr.success(response.message, response.title);
					location.reload();
				}
				/*console.log(window.URL);
				for(var b = 0; b < response.link.length; b++) 
				{
					//console.log(response);
					//window.location.href = 'http://localhost/dot/pdf/invoice/INV - 1521254993.pdf';
					window.open(
						  response.link[b],
						  '_blank' // <- This is what makes it open in a new window.
						);

				}*/

			}				
		});
	}
	else if($tab_aktif == "recurring")
	{
		var billingCheckboxes = new Array();
		//$('input[name="billing_checkbox"]:checked').each(function() {
		   billingCheckboxes.push($billing_id);
		//});

		//console.log(billingCheckboxes);
		$.ajax({
			type: "POST",
			url: "billings/delete_billing",
			data: {"recurring_billing_id":billingCheckboxes, "tab": $tab_aktif}, // <--- THIS IS THE CHANGE
			dataType: "json",
			success: function(response){
				console.log(response);
				if(response.Status == 1)
				{
					toastr.success(response.message, response.title);
					location.reload();
				}
				/*console.log(window.URL);
				for(var b = 0; b < response.link.length; b++) 
				{
					//console.log(response);
					//window.location.href = 'http://localhost/dot/pdf/invoice/INV - 1521254993.pdf';
					window.open(
						  response.link[b],
						  '_blank' // <- This is what makes it open in a new window.
						);

				}*/

			}				
		});
	}
}

function exportStatement(){
	var search_statement_data = $("#form_search_billing").serialize();

	console.log(search_statement_data);

	$('#loadingmessage').show();
	$.ajax({
		type: "POST",
		url: "createbillingpdf/create_statement_pdf",
		data: search_statement_data, // <--- THIS IS THE CHANGE
		dataType: "json",
		success: function(response){
			//console.log(response.link);
			//console.log(window.URL);
			if(response.status == 1)
			{
				for(var b = 0; b < response.link.length; b++) 
				{
					//console.log(response);
					//window.location.href = 'http://localhost/dot/pdf/invoice/INV - 1521254993.pdf';
					//$('#loadingmessage').hide();
					window.open(
						  response.link[b],
						  '_blank' // <- This is what makes it open in a new window.
						);

					
				}
				setTimeout(function(){ deleteInvoicePDF(); }, 5000);
			}
			else
			{
				
				toastr.error("Please fill in the search field and date before export statement.", "Error");
			}
			$('#loadingmessage').hide();
		}				
	});
}

function exportPDF($billing_id, $receipt_id = null, $credit_note_id = null){

	bootbox.confirm({
        message: "Do you want to print Pre-printed Letterhead document?",
        buttons: {
            confirm: {
                label: 'Yes'
                //className: 'btn-success'
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
				//console.log($tab_aktif);
				$('#loadingmessage').show();
				$.ajax({
					type: "POST",
					url: "createbillingpdf/create_billing_pdf",
					data: {"billing_id":billingCheckboxes, "tab": $tab_aktif, "pre-printed": result}, // <--- THIS IS THE CHANGE
					dataType: "json",
					success: function(response){
						console.log(response.link);
						console.log(window.URL);
						for(var b = 0; b < response.link.length; b++) 
						{
							//console.log(response);
							//window.location.href = 'http://localhost/dot/pdf/invoice/INV - 1521254993.pdf';
							$('#loadingmessage').hide();
							window.open(
								  response.link[b],
								  '_blank' // <- This is what makes it open in a new window.
								);

							
						}
						setTimeout(function(){ deleteInvoicePDF(); }, 5000);
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
				$.ajax({
					type: "POST",
					url: "createbillingpdf/create_billing_pdf",
					data: {"receipt_id":receiptCheckboxes, "tab": $tab_aktif, "pre-printed": result}, // <--- THIS IS THE CHANGE
					dataType: "json",
					success: function(response){
						console.log(response.link);
						console.log(window.URL);
						for(var b = 0; b < response.link.length; b++) 
						{
							//console.log(response);
							//window.location.href = 'http://localhost/dot/pdf/invoice/INV - 1521254993.pdf';
							window.open(
								  response.link[b],
								  '_blank' // <- This is what makes it open in a new window.
								);

						}
						setTimeout(function(){ deleteReceiptPDF(); }, 5000);
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
				$.ajax({
					type: "POST",
					url: "createbillingpdf/create_billing_pdf",
					data: {"credit_note_id":creditNoteCheckboxes, "tab": $tab_aktif, "pre-printed": result}, // <--- THIS IS THE CHANGE
					dataType: "json",
					success: function(response){
						console.log(response.link);
						console.log(window.URL);
						for(var b = 0; b < response.link.length; b++) 
						{
							//console.log(response);
							//window.location.href = 'http://localhost/dot/pdf/invoice/INV - 1521254993.pdf';
							window.open(
								  response.link[b],
								  '_blank' // <- This is what makes it open in a new window.
								);

						}
						setTimeout(function(){ deleteCreditNotePDF(); }, 5000);
					}				
				});
			}
        }
    });
	

}

function deleteInvoicePDF()
{
    $.ajax({ //Upload common input
      url: "createbillingpdf/delete_invoice",
      async: false,
      type: "POST",
      //data: {"path":link},
      dataType: 'json',
      success: function (response,data) {
        console.log(response);
      }
    })
}

function deleteReceiptPDF()
{
    $.ajax({ //Upload common input
      url: "createbillingpdf/delete_receipt",
      async: false,
      type: "POST",
      //data: {"path":link},
      dataType: 'json',
      success: function (response,data) {
        console.log(response);
      }
    })
}

function deleteCreditNotePDF()
{
	$.ajax({ //Upload common input
      url: "createbillingpdf/delete_credit_note",
      async: false,
      type: "POST",
      //data: {"path":link},
      dataType: 'json',
      success: function (response,data) {
        console.log(response);
      }
    })
}

$tab_aktif ="billing";

if($tab_aktif == "billing" || $tab_aktif == "payment1" || $tab_aktif == "credit_note" || $tab_aktif == "receipt")
{
	//console.log($("#billing_footer_button"));
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
			//console.log($("#billing_footer_button"));
			$(".edit_client_billing").show();
			$(".edit_client_recurring").hide();

			$(".billing_statement").show();
		}
		else if($tab_aktif == "recurring")
		{
			$(".edit_client_recurring").show();
			$(".edit_client_billing").hide();

			$(".billing_statement").hide();
		}
		else
		{
			$(".edit_client_recurring").hide();
			$(".edit_client_billing").hide();

			$(".billing_statement").hide();
		}

		if($tab_aktif == "billing" || $tab_aktif == "payment1" || $tab_aktif == "credit_note" || $tab_aktif == "receipt")
		{
			//console.log($("#billing_footer_button"));
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
    //console.log(selected_value);

    $("select#service").each(function() {

        var other_num = $(this).parent().parent().parent().attr("num");

        // console.log($(this).parent().parent().parent());
        //console.log($('select[name="service['+other_num+']"]').val());
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
	    //console.log(fv);
	    // Get the first invalid field
	    var $invalidFields = fv.getInvalidFields().eq(0);
	    // Get the tab that contains the first invalid field
	    var $tabPane     = $invalidFields.parents();
	    var valid_setup = fv.isValidContainer($tabPane);

	    fv.disableSubmitButtons(false);

	    //console.log(valid_setup);
	    if(valid_setup)
	    {
	    	//$("#form_receipt").formValidation('destroy');
	    	$.ajax({
		        type: 'POST',
		        url: "billings/save_credit_note",
		        data: $form.serialize(),
		        dataType: 'json',
		        success: function(response){
		            console.log(response.Status);

		            if (response.Status === 1) 
		            {

		            	//console.log(response);
		            	$('#modal_credit_note').modal('toggle');
		            	location.reload();
		            }
		        }
			});
	    }
	});

$(document).on('click',"#saveCreditNote",function(e){
    $("#form_credit_note").submit();
});

$(document).on("submit", "#form_receipt", function(e){
		
		e.preventDefault();
		var $form = $(e.target);
		
	    // and the FormValidation instance
	    var fv = $form.data('formValidation');
	    //console.log(fv);
	    // Get the first invalid field
	    var $invalidFields = fv.getInvalidFields().eq(0);
	    // Get the tab that contains the first invalid field
	    var $tabPane     = $invalidFields.parents();
	    var valid_setup = fv.isValidContainer($tabPane);

	    fv.disableSubmitButtons(false);

	    //console.log(valid_setup);
	    if(valid_setup)
	    {
	    	//$("#form_receipt").formValidation('destroy');
	    	$.ajax({
		        type: 'POST',
		        url: "billings/save_receipt",
		        data: $form.serialize(),
		        dataType: 'json',
		        success: function(response){
		            console.log(response.Status);

		            if (response.Status === 1) 
		            {

		            	//console.log(response);
		            	$('#modal_payment').modal('toggle');
		            	location.reload();
		            }
		        }
			});
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
	console.log(billing_check_state);

	if(billing_check_state != "billing")
	{
		$('li[data-information="'+billing_check_state+'"]').addClass("active");
		$('#w2-'+billing_check_state+'').addClass("active");
		$('li[data-information="billing"]').removeClass("active");
		$('#w2-billing').removeClass("active");
	}
	
}

if(access_right_billing_module == "read" || access_right_template_module == "read")
{
	$('textarea').attr("disabled", true);
	$('.amount').attr("disabled", true);
}
