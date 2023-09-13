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
        }
    }
});
/*var invoice_description = {
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
*/	
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
			success: function(response){
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
				        var table_total = '<tr><td align=right colspan=4>Total</td><td align=right >'+addCommas(receipt_outstanding.toFixed(2))+'</td><td align=right id="received"></td></tr>';

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

	var datatableInit = function() {

		$('#datatable-paid').dataTable();
		$('#datatable-receipt').dataTable();

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
		    console.log(key);
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
});



function exportPDF(){
	var billingCheckboxes = new Array();
	$('input[name="billing_checkbox"]:checked').each(function() {
	   billingCheckboxes.push($(this).val());
	});

	//console.log(billingCheckboxes);
	$.ajax({
		type: "POST",
		url: "createbillingpdf/create_billing_pdf",
		data: {"billing_id":billingCheckboxes}, // <--- THIS IS THE CHANGE
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

		}				
	});
}
$tab_aktif ="billing";

if($tab_aktif == "billing" || $tab_aktif == "payment1" || $tab_aktif == "receipt")
{
	//console.log($("#billing_footer_button"));
	$("#billing_footer_button").hide();
}
else
{
	$("#billing_footer_button").show();
}

$(document).on('click',".check_state",function() {
		$tab_aktif = $(this).data("information");

		if($tab_aktif == "billing" || $tab_aktif == "payment1" || $tab_aktif == "receipt")
		{
			//console.log($("#billing_footer_button"));
			$("#billing_footer_button").hide();
		}
		else
		{
			$("#billing_footer_button").show();
		}

});

for(var y = 0; y < template.length; y++)
{
	$v = y;
	$a=""; 
	/*<select class="input-sm" style="text-align:right;width: 150px;" id="position" name="position[]" onchange="optionCheck(this);"><option value="Director" >Director</option><option value="CEO" >CEO</option><option value="Manager" >Manager</option><option value="Secretary" >Secretary</option><option value="Auditor" >Auditor</option><option value="Managing Director" >Managing Director</option><option value="Alternate Director">Alternate Director</option></select>*/
	$a += '<div class="tr editing" method="post" name="form'+$v+'" id="form'+$v+'" num="'+$v+'">';
	$a += '<div class="hidden"><input type="text" class="form-control" name="id[]" value="'+template[y]["id"]+'"/></div>';
	$a += '<div class="td" style="width:250px;"><div class="input-group"><input type="text" name="service[]" class="form-control service" value="'+template[y]["service_name"]+'" id="service" style="width:250px" readOnly/></div></div>';
	$a += '<div class="td"><div class="input-group"><textarea class="form-control" name="invoice_description[]"  id="invoice_description" rows="5" style="width:350px">'+template[y]["invoice_description"]+'</textarea></div></div>';
	$a += '<div class="td"><div class="input-group"><input type="text" name="amount[]" class="numberdes form-control amount" value="'+template[y]["amount"]+'" id="amount" style="width:100%;text-align:right;"/><div id="form_amount"></div></div></div>';

	/*$a += '<div class="td action"><button type="button" class="btn btn-primary" onclick="edit_billing_info(this);">Save</button></div></div>';*/
	$a += '</div>';

	/*<input type="button" value="Save" id="save" name="save'+$count_officer+'" class="btn btn-primary" onclick="save(this);">*/
	$("#body_template_info").prepend($a); 

	/*$('#body_template_info').formValidation('addField', 'invoice_description['+$v+']', invoice_description);
	$('#body_template_info').formValidation('addField', 'amount['+$v+']', amount);*/
}

$('#form_template').formValidation({
	framework: 'bootstrap',
    icon: {
        /*valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'*/
    },
    fields: {
    	'invoice_description[]' : {
	        row: '.input-group',
	        validators: {
	            notEmpty: {
	                message: 'The Invoice Description field is required.'
	            }
	        }
	    },
	    'amount[]' : {
	        row: '.input-group',
	        validators: {
	            notEmpty: {
	                message: 'The Amount field is required.'
	            }
	        }
	    }
    }
});

toastr.options = {

  "positionClass": "toast-bottom-right"

}


$(document).on("submit", "#form_template", function(e){
	e.preventDefault();
	var $form = $(e.target);
		
    // and the FormValidation instance
    var fv = $form.data('formValidation');
    console.log(fv);
    // Get the first invalid field
    var $invalidFields = fv.getInvalidFields().eq(0);
    // Get the tab that contains the first invalid field

    console.log($invalidFields);
    var $tabPane     = $invalidFields.parents();
    var valid_setup = fv.isValidContainer($tabPane);

    fv.disableSubmitButtons(false);

    if(valid_setup)
	{
		$('#loadingmessage').show();
		$.ajax({
	        type: 'POST',
	        url: "billings/save_template",
	        data: $form.serialize(),
	        dataType: 'json',
	        success: function(response){
	        	$('#loadingmessage').hide();
	            //console.log(response.error);
	            if (response.Status === 1) 
	            {
	            	toastr.success("Information Updated", "Success");//contact, title
	            	//console.log(response);
	            	/*$('#modal_payment').modal('toggle');
	            	location.reload();*/
	            }
	        }
		});
	}
	else
	{
		toastr.error("Please complete all required field", "Error");
	}
});

$(document).on('click',"#save_template",function(e){
    $("#form_template").submit();
});

$('.selectall').click(function() {
    if ($(this).is(':checked')) {
        $('input:checkbox').attr('checked', true);
    } else {
        $('input:checkbox').attr('checked', false);
    }
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



/*if(client_billing_info)
{
    $count_billing_info = client_billing_info.length + 1;
}
else
{*/
    $count_billing_info = 1;
//}
$(document).on('click',"#billing_info_Add",function() {
    
    $a=""; 
    /*<select class="input-sm" style="text-align:right;width: 150px;" id="position" name="position[]" onchange="optionCheck(this);"><option value="Director" >Director</option><option value="CEO" >CEO</option><option value="Manager" >Manager</option><option value="Secretary" >Secretary</option><option value="Auditor" >Auditor</option><option value="Managing Director" >Managing Director</option><option value="Alternate Director">Alternate Director</option></select>*/
    $a += '<div class="tr editing" method="post" name="form'+$count_billing_info+'" id="form'+$count_billing_info+'" num="'+$count_billing_info+'">';
    $a += '<div class="hidden"><input type="text" class="form-control" name="company_code" value="'+company_code+'"/></div>';
    $a += '<div class="hidden"><input type="text" class="form-control" name="client_billing_info_id['+$count_billing_info+']" id="client_billing_info_id" value="'+$count_billing_info+'"/></div>';
    $a += '<div class="td"><div class="input-group"><select class="input-sm form-control" style="text-align:right;width: 100%;" name="service['+$count_billing_info+']" id="service" onchange="optionCheckBilling(this);"><option value="0" >Select Service</option></select><div id="form_service"></div></div></div>';
    $a += '<div class="td"><div class="input-group"><textarea class="form-control" name="invoice_description['+$count_billing_info+']"  id="invoice_description" rows="8" style="width:250px"></textarea></div></div>';
    $a += '<div class="td"><div class="input-group"><input type="text" name="amount['+$count_billing_info+']" class="numberdes form-control amount" value="" id="amount" style="width:100%;text-align:right;"/><div id="form_amount"></div></div></div>';
    $a += '<div class="td"><div>From: </div><div class="from_div mb-md"><div class="input-group" style="width: 100%" id="from_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker from_datepicker" id="from" name="from['+$count_billing_info+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div><div id="form_from"></div></div><div class="mb-md"><div>To: </div><div class="to_div mb-md"><div class="input-group" style="width: 100%" id="to_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker to_datepicker" id="to" name="to['+$count_billing_info+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div><div id="form_to"></div></div></div></div>';
    $a += '<div class="td"><div class="input-group"><select class="input-sm form-control" style="text-align:right;width: 100%;" name="frequency['+$count_billing_info+']" id="frequency" onchange="optionCheckService(this);"><option value="0" >Select Frequency</option></select><div id="form_frequency"></div></div></div>';
    /*$a += '<div class="td action"><button type="button" class="btn btn-primary" onclick="edit_billing_info(this);">Save</button></div></div>';*/
    $a += '</div>';

    /*<input type="button" value="Save" id="save" name="save'+$count_officer+'" class="btn btn-primary" onclick="save(this);">*/
    $("#body_billing_info").prepend($a); 
    //$('.datepicker').datepicker({ dateFormat:'dd/mm/yyyy'});

    $('.from_datepicker').datepicker({ 
        dateFormat:'dd/mm/yyyy',
        autoclose: true,
    })
    .on('changeDate', function (selected) {
        var startDate = new Date(selected.date.valueOf());
        $(this).parent().parent().parent().parent().find('.to_datepicker').datepicker('setStartDate', startDate);

        var num = $(this).parent().parent().parent().parent().attr("num");
        $('#setup_form').formValidation('revalidateField', 'from['+num+']');
    }).on('clearDate', function (selected) {
        $(this).parent().parent().parent().parent().find('.to_datepicker').datepicker('setStartDate', null);
    });

    $('.to_datepicker').datepicker({ 
        dateFormat:'dd/mm/yyyy',
        autoclose: true,
    }).on('changeDate', function (selected) {

        var endDate = new Date(selected.date.valueOf());
        $(this).parent().parent().parent().parent().find('.from_datepicker').datepicker('setEndDate', endDate);

        var num = $(this).parent().parent().parent().parent().parent().attr("num");
        $('#setup_form').formValidation('revalidateField', 'to['+num+']');
    }).on('clearDate', function (selected) {
       $(this).parent().parent().parent().parent().find('.from_datepicker').datepicker('setEndDate', null);
    });


    !function ($count_billing_info) {
        $.ajax({
            type: "POST",
            url: "user_billings/get_billing_info_admin_service",
            data: {"company_code": company_code},
            dataType: "json",
            success: function(data){
                console.log(data);
                if(data.tp == 1){
                    $.each(data['result'], function(key, val) {
                        var option = $('<option />');
                        option.attr('value', key).text(val);
                        
                        /*$("#form"+$count_billing_info+" #service option").filter(function()
                        {
                            return $.inArray($(this).val(),data.selected_query)>-1;
                        }).attr("disabled","disabled");  */
                        $("#form"+$count_billing_info+" #service").append(option);

                        //$("select#service option").attr("disabled",false); //enable everything
     
     					
	                        //collect the values from selected;
	                        var arr = $.map
	                        (
	                            $("select#service option:selected"), function(n)
	                            {
	                                return n.value;
	                            }
	                        );
	                        //console.log(arr);

	                        $('select[name="service['+$count_billing_info+']"] option').filter(function()
	                        {
	                        	if($(this).val() != 6)
     							{
	                            	return $.inArray($(this).val(),arr)>-1;
	                            }
	                         }).attr("disabled","disabled"); 
	                    
                        
                    });
                }
                else{
                    alert(data.msg);
                }  
            }               
        });
    }($count_billing_info);

    !function ($count_billing_info) {
        $.ajax({
            type: "GET",
            url: "masterclient/get_billing_info_frequency",
            dataType: "json",
            success: function(data){
                console.log(data);
                if(data.tp == 1){
                    $.each(data['result'], function(key, val) {
                        var option = $('<option />');
                        option.attr('value', key).text(val);
                        
                        $("#form"+$count_billing_info+" #frequency").append(option);
                    });
                }
                else{
                    alert(data.msg);
                }  
            }               
        });
    }($count_billing_info);

    /*$('#setup_form').formValidation('addField', 'service['+$count_billing_info+']', serviceValidators);
    $('#setup_form').formValidation('addField', 'invoice_description['+$count_billing_info+']', invoiceDescriptionValidators);
    $('#setup_form').formValidation('addField', 'amount['+$count_billing_info+']', amountValidators);
    $('#setup_form').formValidation('addField', 'from['+$count_billing_info+']', fromValidators);

    $('#setup_form').formValidation('addField', 'frequency['+$count_billing_info+']', frequencyValidators);*/

    
    $count_billing_info++;
});


function optionCheckBilling(billing_element) {
    
    var tr = jQuery(billing_element).parent().parent();

    var input_num = tr.parent().attr("num");

    //console.log(input_num);
    //tr.find("DIV.td").each(function(){
        

        //Prevent Multiple Selections of Same Value
        var selected_value = tr.find('select[name="service['+input_num+']"]').val();

        //$("select#service option").attr("disabled",false); //enable everything
     
     	
         //collect the values from selected;
     	
	        var arr = $.map
	        (
	            $("select#service option:selected"), function(n)
	            {
	                return n.value;
	            }
	        );
	        //console.log(arr);

	        $('select[name="service['+input_num+']"] option').filter(function()
	        {
	        	if(selected_value != 6)
     			{
	            	return $.inArray($(this).val(),arr)>-1;
	            }
	         }).attr("disabled","disabled"); 

	        $("select#service option").filter(function()
	        {
	        	if(selected_value != 6)
     			{
	            	return $(this).val() === selected_value;
	            }
	         }).attr("disabled","disabled"); 

	        $('select[name="service['+input_num+']"] option').filter(function()
	        {
	        	if(selected_value != 6)
     			{
	            	return $(this).val() === selected_value;
	            }
	        }).attr("disabled", false);
	    

        //$('#setup_form').formValidation('revalidateField', 'frequency['+input_num+']');
        //jQuery(this).find("input").attr('disabled', false);
    //});

}