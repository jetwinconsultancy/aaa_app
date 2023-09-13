var idValidators = {
    row: '.input-group',   // The title is placed inside a <div class="col-xs-4"> element
    validators: {
        notEmpty: {
            message: 'The ID field is required.'
        }
    }
},
nameValidators = {
	row: '.input-group',   // The title is placed inside a <div class="col-xs-4"> element
    validators: {
        notEmpty: {
            message: 'The Name field is required.'
        }
    }
},
amountShareValidators = {
	row: '.input-group',   // The title is placed inside a <div class="col-xs-4"> element
    validators: {
        notEmpty: {
            message: 'The Amount Share field is required.'
            
        }/*,
        integer: {
            message: 'The value is not an integer',
            // The default separators
            thousandsSeparator: ',',
            decimalSeparator: '.'
        }*/
    }
},
noOfSharePaidValidators = {
	row: '.input-group',   // The title is placed inside a <div class="col-xs-4"> element
    validators: {
        notEmpty: {
            message: 'The No of Share Paid field is required.'
        }/*,
        integer: {
            message: 'The value is not an integer'
        }*/
    }
},
amountPaidValidators = {
	row: '.input-group',   // The title is placed inside a <div class="col-xs-4"> element
    validators: {
        notEmpty: {
            message: 'The Amount Paid field is required.'
        }/*,
        integer: {
            message: 'The value is not an integer'
        }*/
    }
},
certificateNoValidators = {
	row: '.alloment_group',   // The title is placed inside a <div class="col-xs-4"> element
    validators: {
        notEmpty: {
            message: 'The Certificate No field is required.'
        }
    }
},
numberOfShareValidators = {
	row: '.input-group',   // The title is placed inside a <div class="col-xs-4"> element
    validators: {
        notEmpty: {
            message: 'The Number of Share field is required.'
        }/*,
        integer: {
            message: 'The value is not an integer'
        }*/
    }
};

var edit_cert = false;
var pathArray = location.href.split( '/' );
var protocol = pathArray[0];
var host = pathArray[2];
var folder = pathArray[3]
var url = protocol + '//' + host + '/' + folder + '/';

var shareType = " ", other_class = " ", currency = " "; 

$a0=""; 
if(allotment == undefined)
{
	$a0 += '<div class="tr editing" method="post" name="form'+0+'" id="form'+0+'" num="'+0+'">';
	$a0 += '<div class="hidden"><input type="text" class="form-control" name="company_code" value="'+company_code+'"/></div>';
	$a0 += '<div class="hidden"><input type="text" class="form-control cert_id" name="cert_id[]" id="cert_id" value=""/></div>';
	$a0 += '<div class="hidden"><input type="text" class="form-control" name="member_share_id[]" id="member_share_id" value=""/></div>';
	$a0 += '<div class="hidden"><input type="text" class="form-control" name="officer_id['+0+']" id="officer_id" value=""/></div>';
	$a0 += '<div class="hidden"><input type="text" class="form-control" name="field_type['+0+']" id="field_type" value=""/></div>';
	$a0 += '<div class="hidden"><input type="text" class="form-control" name="previous_new_cert['+0+']" id="previous_new_cert" value=""/></div>';
	$a0 += '<div class="hidden"><input type="text" class="form-control" name="previous_cert['+0+']" id="previous_cert" value=""/></div>';
	/*$a += '<div class="td">'+$count_allotment+'</div>';*/
	$a0 += '<div class="td"><div class="input-group"><input type="text" name="id['+0+']" class="form-control id" value="" id="get_person_name" style="text-transform:uppercase;"/></div><div style="height:16px;" ><a class="" href="'+ url + 'personprofile/add" style="cursor:pointer;" id="add_person_link" target="_blank" hidden><div style="cursor:pointer;">Click here to <span style="font-weight:bold">Add Entity</span></div></a></div><div class="input-group mb-md name"><input type="text" tabindex="-1" name="name['+0+']" class="form-control" value="" readonly/></div></div>';
	$a0 += '<div class="td"><div class="input-group mb-md"><div class="member_classes"><input type="text" tabindex="-1" name="class['+0+']" class="form-control member_class" id="member_class" value="" readonly/></div><div id="others_field" class="mb-md others_field" hidden><div style="font-weight:bold;"></div><input type="text" tabindex="-1" name="others['+0+']" id="member_others" class="form-control member_others" value="" readonly/></div></div><div class="input-group mb-md"><input type="text" tabindex="-1" name="currency['+0+']" class="form-control member_currency" id="member_currency" value="" readonly/></div></div>';
	$a0 += '<div class="td"><div class="input-group mb-md"><input type="text" name="number_of_share['+0+']" class="numberdes form-control number_of_share" value="" id="number_of_share" style="text-align:right;" pattern="^[0-9,]+$"/></div><div class="input-group mb-md"><input type="text" name="amount_share['+0+']" id="amount_share" class="numberdes form-control amount_share" value="" style="text-align:right;" pattern="[0-9.,]"/></div></div>';
	$a0 += '<div class="td"><div class="input-group mb-md"><input type="text" tabindex="-1" name="no_of_share_paid['+0+']" class="numberdes form-control no_of_share_paid" value="" id="no_of_share_paid" style="text-align:right;" readonly/></div><div class="input-group mb-md"><input type="text" name="amount_paid['+0+']" id="amount_paid" class="numberdes form-control amount_paid" value="" style="text-align:right;" pattern="[0-9.,]"/></div></div>';
	/*$a0 += '<div class="td"><div class="input-group mb-md"><input type="text" class="form-control" name="certificate_no['+0+']" value=""/></div></div>';*/
	$a0 += '<div class="td action"><button type="button" class="btn btn-primary delete_allotment_button" onclick="delete_allotment(this)" style="display: none;">Delete</button></div>';
	$a0 += '</div>';
}
$("#allotment_add").append($a0); 

$('#allotment_form').formValidation({
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
    	date: {
            validators: {
                notEmpty: {
                    message: 'The Transaction Date field is required'
                }
            }
        },
		class: {
	        validators: {
	            callback: {
	                message: 'The Class field is required',
	                callback: function(value, validator, $field) {
	                    var options = validator.getFieldElements('class').val();
	                    console.log(options);
	                    return (options != null && options != "0");
	                }
	            }
	        }
	    },
        'id[0]': idValidators,
        'name[0]': nameValidators,
        'number_of_share[0]': numberOfShareValidators,
        'amount_share[0]': amountShareValidators,
        'no_of_share_paid[0]': noOfSharePaidValidators,
        'amount_paid[0]': amountPaidValidators/*,
        'certificate_no[0]': certificateNoValidators*/
        
    }
});

take_incorporation_date();

function take_incorporation_date()
{
	$.ajax({
		type: "POST",
		url: "masterclient/check_incorporation_date",
		data: {"company_code": company_code}, // <--- THIS IS THE CHANGE
		dataType: "json",
		async: false,
		success: function(response)
		{
			console.log("incorporation_date==="+response[0]["incorporation_date"]);
			$array = response[0]["incorporation_date"].split("/");
			$tmp = $array[0];
			$array[0] = $array[1];
			$array[1] = $tmp;
			//unset($tmp);
			$date_2 = $array[0]+"/"+$array[1]+"/"+$array[2];
			console.log(new Date($date_2));

			latest_incorporation_date = new Date($date_2);
			/*date.setDate(date.getDate()-1)
	*/
			console.log(new Date());
			$('#transaction_date').datepicker({ 
			    dateFormat:'dd/mm/yyyy',
			}).datepicker('setStartDate', latest_incorporation_date);
		}
	});
}

if(company_class)
{
	console.log(company_class);
	var shareClass;

    for(var i = 0; i < company_class.length; i++)
	{
		if(company_class[i]['sharetype'] == "Ordinary Share")
		{
			shareClass = company_class[i]['sharetype'] + " ( " + company_class[i]['currency'] + " )";
		}
		else if(company_class[i]['sharetype'] == "Others")
		{
			shareClass = company_class[i]['other_class'] + " ( " + company_class[i]['currency'] + " )";
		}
		var option = $('<option />');
        /*console.log(currency_id);*/
        option.attr('data-otherclass', company_class[i]['other_class']);
        option.attr('data-currency', company_class[i]['currency']);
        option.attr('data-sharetype', company_class[i]['sharetype']);
        option.attr('value', company_class[i]['id']).text(shareClass);
        if(allotment)
        {
        	if(allotment[0]["share_capital_id"] != null && company_class[i]['id'] == allotment[0]["share_capital_id"])
        	{
        		option.attr('selected', 'selected');
        		/*if(allotment[0]["sharetype"] == "Others")
        		{
        			$("#other_class").removeAttr('hidden');
        		}*/
        	}
        }

        $("#class").append(option);
	}
	console.log(option);
}

$("#class").on('change', function() {
	//console.log($(this).find("option:selected").data('otherclass')==" ");
	if($(this).find("option:selected").val() == 0)
    {
        /*$("#other_class").attr("hidden","true");*/
        $(".others_field").attr("hidden","true");
        $("#currency").val($(this).find("option:selected").data('currency'));
    }
    else
    {
    	//console.log($(this).find("option:selected").data('otherclass')!="");
		if($(this).find("option:selected").data('otherclass')!="")
		{
			// $("#other_class").removeAttr('hidden');
			$(".others_field").removeAttr('hidden');
			$(".member_classes").attr("hidden","true");
			/*$("#others").val($(this).find("option:selected").data('otherclass'));
			other_class = $(this).find("option:selected").data('otherclass');
			$(".member_others").val($(this).find("option:selected").data('otherclass'));*/
		}
		else
		{
			/*$("#other_class").attr("hidden","true");*/
			$(".others_field").attr("hidden","true");
			$(".member_classes").removeAttr('hidden');

		}
		$("#others").val($(this).find("option:selected").data('otherclass'));
		other_class = $(this).find("option:selected").data('otherclass');
		$(".member_others").val($(this).find("option:selected").data('otherclass'));

		$("#client_member_share_capital_id").val($(this).find("option:selected").val());
		//console.log($(this).find("option:selected").val());
	  	$("#currency").val($(this).find("option:selected").data('currency'));

	  	currency = $(this).find("option:selected").data('currency');
	  	shareType = $(this).find("option:selected").data('sharetype');

	  	$(".member_class").val($(this).find("option:selected").data('sharetype'));
	  	$(".member_currency").val($(this).find("option:selected").data('currency'));

	  	if(allotment)
	  	{
		  	if($(this).find("option:selected").val() == allotment[0]["share_capital_id"])
		  	{
		  		/*$('.edit_certificate_no').attr('readonly', true);
		  		$('.edit_certificate_no').val(allotment[0]["certificate_no"]);*/
		  		//console.log("false");
		  		edit_cert = false;
		  	}
		  	else
		  	{
		  		/*$('.edit_certificate_no').attr('readonly', false);*/
		  		//console.log("true");
		  		edit_cert = true;
		  	}
		}
	  	
	}
  	
});

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
//console.log(allotment);
if(allotment) 
{
	console.log(allotment[0]["sharetype"]);
	$(".member_class").val(allotment[0]["sharetype"]);
	$(".member_others").val(allotment[0]["other_class"]);
  	$(".member_currency").val(allotment[0]["currency"]);

  	other_class = allotment[0]["other_class"];
	currency = allotment[0]["currency"];
  	shareType = allotment[0]["sharetype"];
  	console.log(other_class);
	for(var t = 0; t < allotment.length; t++)
	{
		$count_edit_allotment = t;
		$edit_field_index = t;
	 	$a=""; 


	 	/*$a += '<form class="editing" method="post" name="form'+$count_charges+'" id="form'+$count_charges+'">';*/
		$a += '<div class="tr editing" method="post" name="form'+$count_edit_allotment+'" id="form'+$count_edit_allotment+'" num="'+$edit_field_index+'">';
		$a += '<div class="hidden"><input type="text" class="form-control" name="company_code" value="'+company_code+'"/></div>';
		$a += '<div class="hidden"><input type="text" class="form-control cert_id" name="cert_id[]" id="cert_id" value="'+allotment[t]["cert_id"]+'"/></div>';
		$a += '<div class="hidden"><input type="text" class="form-control member_share_id" name="member_share_id[]" id="member_share_id" value="'+allotment[t]["id"]+'"/></div>';
		$a += '<div class="hidden"><input type="text" class="form-control" name="officer_id['+$edit_field_index+']" id="officer_id" value="'+(allotment[t]["officer_id"]!=null ? allotment[t]["officer_id"] : (allotment[t]["officer_company_id"] != null ? allotment[t]["officer_company_id"] : allotment[t]["client_company_id"]))+'"/></div>';
		$a += '<div class="hidden"><input type="text" class="form-control" name="field_type['+$edit_field_index+']" id="field_type" value="'+(allotment[t]["officer_field_type"]!=null ? allotment[t]["officer_field_type"] : (allotment[t]["officer_company_field_type"] != null ? allotment[t]["officer_company_field_type"] : allotment[t]["client_company_field_type"]))+'"/></div>';
		$a += '<div class="hidden"><input type="text" class="form-control" name="previous_new_cert['+$edit_field_index+']" id="previous_new_cert" value="'+allotment[t]["new_certificate_no"]+'"/></div>';
		$a += '<div class="hidden"><input type="text" class="form-control" name="previous_cert['+$edit_field_index+']" id="previous_cert" value="'+allotment[t]["certificate_no"]+'"/></div>';

		$a += '<div class="td"><div class="input-group"><input type="text" name="id['+$edit_field_index+']" class="form-control id" value="'+ (allotment[t]["identification_no"]!=null ? allotment[t]["identification_no"] : (allotment[t]["register_no"]!=null ? allotment[t]["register_no"] : allotment[t]["registration_no"])) +'" id="get_person_name" style="text-transform:uppercase;"/></div><div style="height:16px;" ><a class="" href="'+ url + 'personprofile/add" style="cursor:pointer;" id="add_person_link" target="_blank" hidden><div style="cursor:pointer;">Click here to <span style="font-weight:bold">Add Entity</span></div></a></div><div class="input-group mb-md name"><input type="text" tabindex="-1" name="name['+$edit_field_index+']" class="form-control" value="'+ (allotment[t]["company_name"]!=null ? allotment[t]["company_name"] : (allotment[t]["name"] != null ? allotment[t]["name"] : allotment[t]["client_company_name"])) +'" readonly/></div></div>';
		$a += '<div class="td"><div class="input-group mb-md"><div class="member_classes"><input type="text" tabindex="-1" name="class[]" class="form-control member_class" id="member_class" value="'+(allotment[t]["sharetype"]!=null ? allotment[t]["sharetype"] : shareType)+'" readonly/></div><div id="others_field" class="mb-md others_field" hidden><div style="font-weight:bold;"></div><input type="text" tabindex="-1" name="others[]" id="member_others" class="form-control member_others" value="'+(allotment[t]["other_class"]!=null ? allotment[t]["other_class"] : other_class)+'" readonly/></div></div><div class="input-group mb-md"><input type="text" tabindex="-1" name="currency[]" class="form-control member_currency" id="member_currency" value="'+(allotment[t]["currency"]!=null ? allotment[t]["currency"] : allotment[t]["currency"])+'" readonly/></div></div>';
		$a += '<div class="td"><div class="input-group mb-md"><input type="text" name="number_of_share['+$edit_field_index+']" class="numberdes form-control number_of_share" value="'+addCommas(allotment[t]["number_of_share"])+'" id="number_of_share" style="text-align:right;" pattern="^[0-9,]+$"/></div><div class="input-group mb-md"><input type="text" name="amount_share['+$edit_field_index+']" id="amount_share" class="numberdes form-control amount_share" value="'+addCommas(allotment[t]["amount_share"])+'" style="text-align:right;" pattern="[0-9.,]"/></div></div>';
		$a += '<div class="td"><div class="input-group mb-md"><input type="text" tabindex="-1" name="no_of_share_paid['+$edit_field_index+']" class="numberdes form-control no_of_share_paid" value="'+addCommas(allotment[t]["no_of_share_paid"])+'" id="no_of_share_paid" style="text-align:right;" readonly/></div><div class="input-group mb-md"><input type="text" name="amount_paid['+$edit_field_index+']" id="amount_paid" class="numberdes form-control amount_paid" value="'+addCommas(allotment[t]["amount_paid"])+'" style="text-align:right;" pattern="[0-9.,]"/></div></div>';
		/*$a += '<div class="td"><div class="input-group mb-md"><input type="text" class="form-control" name="certificate_no['+$edit_field_index+']" value="'+allotment[t]["certificate_no"]+'"/></div></div>';*/
		if (allotment.length == 1)
		{
			$a += '<div class="td action"><button type="button" class="btn btn-primary delete_allotment_button" onclick="delete_allotment(this)" style="display: none">Delete</button></div>';
		}
		else
		{
			$a += '<div class="td action"><button type="button" class="btn btn-primary delete_allotment_button" onclick="delete_allotment(this)" style="display: block">Delete</button></div>';
		}		
		$a += '</div>';

		$("#allotment_add").append($a); 

		$('#allotment_form').formValidation('addField', 'id['+$edit_field_index+']', idValidators);
		$('#allotment_form').formValidation('addField', 'name['+$edit_field_index+']', nameValidators);
		$('#allotment_form').formValidation('addField', 'number_of_share['+$edit_field_index+']', numberOfShareValidators);
		$('#allotment_form').formValidation('addField', 'amount_share['+$edit_field_index+']', amountShareValidators);
		$('#allotment_form').formValidation('addField', 'no_of_share_paid['+$edit_field_index+']', noOfSharePaidValidators);
		$('#allotment_form').formValidation('addField', 'amount_paid['+$edit_field_index+']', amountPaidValidators);
		/*$('#allotment_form').formValidation('addField', 'certificate_no['+$edit_field_index+']', certificateNoValidators);*/

		/*if(allotment[t]["company_name"] != null || allotment[t]["name"] != null)
		{
			$('input[name="name['+$edit_field_index+']"]').attr('readonly', true);
		}
		else
		{
			$('input[name="name['+$edit_field_index+']"]').attr('readonly', false);
		}*/

		if(other_class != "")
		{
			$(".others_field").removeAttr('hidden');
			$(".member_classes").attr("hidden","true");
		}
		else
		{
			$(".others_field").attr("hidden","true");
			$(".member_classes").removeAttr('hidden');
		}

		/*if($edit_field_index == 0)
		{
			$('.delete_allotment_button').css('display','none');
		}	*/
		//$count_edit_allotment++;
	}
}
if(allotment)
{
	$count_allotment = allotment.length + 1;
}
else
{
	$count_allotment = 0;
}
$(document).on('click',"#allotment_member_Add",function() {

	$count_allotment++;
	$field_index = $count_allotment;
 	$a=""; 

 	/*$a += '<form class="editing" method="post" name="form'+$count_charges+'" id="form'+$count_charges+'">';*/
	$a += '<div class="tr editing" method="post" name="form'+$count_allotment+'" id="form'+$count_allotment+'" num="'+$field_index+'">';
	$a += '<div class="hidden"><input type="text" class="form-control" name="company_code" value="'+company_code+'"/></div>';
	$a += '<div class="hidden"><input type="text" class="form-control cert_id" name="cert_id[]" id="cert_id" value=""/></div>';
	$a += '<div class="hidden"><input type="text" class="form-control" name="member_share_id[]" id="member_share_id" value=""/></div>';
	$a += '<div class="hidden"><input type="text" class="form-control" name="officer_id['+$field_index+']" id="officer_id" value=""/></div>';
	$a += '<div class="hidden"><input type="text" class="form-control" name="field_type['+$field_index+']" id="field_type" value=""/></div>';
	$a += '<div class="hidden"><input type="text" class="form-control" name="previous_new_cert['+$field_index+']" id="previous_new_cert" value=""/></div>';
	$a += '<div class="hidden"><input type="text" class="form-control" name="previous_cert['+$field_index+']" id="previous_cert" value=""/></div>';

	/*$a += '<div class="td">'+$count_allotment+'</div>';*/
	$a += '<div class="td"><div class="input-group"><input type="text" name="id['+$field_index+']" class="form-control id" value="" id="get_person_name" style="text-transform:uppercase;"/></div><div style="height:16px;" ><a class="" href="'+ url + 'personprofile/add" style="cursor:pointer;" id="add_person_link" target="_blank" hidden><div style="cursor:pointer;">Click here to <span style="font-weight:bold">Add Entity</span></div></a></div><div class="input-group mb-md name"><input type="text" name="name['+$field_index+']" class="form-control" value="" tabindex="-1" readonly/><div id="form_name"></div></div></div>';
	$a += '<div class="td"><div class="input-group mb-md"><div class="member_classes"><input type="text" tabindex="-1" name="class['+$field_index+']" class="form-control member_class" id="member_class" value="'+shareType+'" readonly/></div><div id="others_field" class="mb-md others_field" hidden><div style="font-weight:bold;"></div><input type="text" tabindex="-1" name="others['+$field_index+']" id="member_others" class="form-control member_others" value="'+other_class+'" readonly/></div></div><div class="input-group mb-md"><input type="text" tabindex="-1" name="currency['+$field_index+']" class="form-control member_currency" id="member_currency" value="'+currency+'" readonly/><div id="form_currency"></div></div></div>';
	$a += '<div class="td"><div class="input-group mb-md"><input type="text" name="number_of_share['+$field_index+']" class="numberdes form-control number_of_share" value="" id="number_of_share" style="text-align:right;" pattern="^[0-9,]+$"/><div id="form_number_of_share"></div></div><div class="input-group mb-md"><input type="text" name="amount_share['+$field_index+']" id="amount_share" class="numberdes form-control amount_share" value="" style="text-align:right;" pattern="[0-9.,]"/><div id="form_amount_share"></div></div></div>';
	$a += '<div class="td"><div class="input-group mb-md"><input type="text" tabindex="-1" name="no_of_share_paid['+$field_index+']" class="numberdes form-control no_of_share_paid" value="" id="no_of_share_paid" style="text-align:right;" readonly/><div id="form_no_of_share_paid"></div></div><div class="input-group mb-md"><input type="text" name="amount_paid['+$field_index+']" id="amount_paid" class="numberdes form-control amount_paid" value="" style="text-align:right;" pattern="[0-9.,]"/><div id="form_amount_paid"></div></div></div>';
	/*$a += '<div class="td"><div class="input-group mb-md"><input type="text" class="form-control" name="certificate_no['+$field_index+']" value=""/><div id="form_certificate_no"></div></div></div>';*/
	$a += '<div class="td action"><button type="button" class="btn btn-primary delete_allotment_button" onclick="delete_allotment(this)" style="display: block;">Delete</button></div>';
	$a += '</div>';

	$("#allotment_add").append($a); 

	if($("#allotment_add > div").length > 1)
	{
		$('.delete_allotment_button').css('display','block');
	}

	$('#allotment_form').formValidation('addField', 'id['+$field_index+']', idValidators);
	$('#allotment_form').formValidation('addField', 'name['+$field_index+']', nameValidators);
	$('#allotment_form').formValidation('addField', 'number_of_share['+$field_index+']', numberOfShareValidators);
	$('#allotment_form').formValidation('addField', 'amount_share['+$field_index+']', amountShareValidators);
	$('#allotment_form').formValidation('addField', 'no_of_share_paid['+$field_index+']', noOfSharePaidValidators);
	$('#allotment_form').formValidation('addField', 'amount_paid['+$field_index+']', amountPaidValidators);
	/*$('#allotment_form').formValidation('addField', 'certificate_no['+$field_index+']', certificateNoValidators);*/
	//$('#wAllotment').formValidation('addField', 'name['+$field_index+']', nameValidators);

	if(other_class != "")
	{
		$(".others_field").removeAttr('hidden');
		$(".member_classes").attr("hidden","true");
	}
	else
	{
		$(".others_field").attr("hidden","true");
		$(".member_classes").removeAttr('hidden');
	}
		
	$("input.number").bind({
		keydown: function(e) {
			if (e.shiftKey === true ) {
				if (e.which == 9) {
					return true;
				}
				return false;
			}
			if (e.which > 57) {
				return false;
			}
			if (e.which==32) {
				return false;
			}
			return true;
		}
	});
});

toastr.options = {
  "positionClass": "toast-bottom-right"
}

$("#transaction_date").live('change',function(){
	if($(this).val() == "")
	{
		toastr.error("Transaction Date must be on or after the incorporation date.", "Error");
	}
});

$("#number_of_share").live('change',function(){
	var no_of_share_paid = 0;
	var allotment_frm = $(this);
	console.log(allotment_frm.val());
	//console.log($(this).parent().parent().parent().attr("num"));
	var input_number = allotment_frm.parent().parent().parent().attr("num");

	if($('input[name="number_of_share['+input_number+']"]').val() != "" && $('input[name="amount_share['+input_number+']"]').val() != "" && $('input[name="amount_paid['+input_number+']"]').val() != "")
	{
		no_of_share_paid = (parseFloat(removeCommas($('input[name="amount_paid['+input_number+']"]').val())) * parseInt(removeCommas($('input[name="number_of_share['+input_number+']"]').val()))) / parseFloat(removeCommas($('input[name="amount_share['+input_number+']"]').val()));
	}
	$('input[name="no_of_share_paid['+input_number+']"]').val(addCommas(parseInt(no_of_share_paid)));
	// console.log($(this).val());
});

$("#amount_share").live('change',function(){
	var no_of_share_paid = 0;
	var allotment_frm = $(this);
	console.log(allotment_frm.val());
	//console.log($(this).parent().parent().parent().attr("num"));
	var input_number = allotment_frm.parent().parent().parent().attr("num");

	if($('input[name="number_of_share['+input_number+']"]').val() != "" && $('input[name="amount_share['+input_number+']"]').val() != "" && $('input[name="amount_paid['+input_number+']"]').val() != "")
	{
		no_of_share_paid = (parseFloat(removeCommas($('input[name="amount_paid['+input_number+']"]').val())) * parseInt(removeCommas($('input[name="number_of_share['+input_number+']"]').val()))) / parseFloat(removeCommas($('input[name="amount_share['+input_number+']"]').val()));
	}
	$('input[name="no_of_share_paid['+input_number+']"]').val(addCommas(parseInt(no_of_share_paid)));
	// console.log($(this).val());
});

$("#amount_paid").live('change',function(){
	var no_of_share_paid = 0;
	var allotment_frm = $(this);
	console.log(allotment_frm.val());
	//console.log($(this).parent().parent().parent().attr("num"));
	var input_number = allotment_frm.parent().parent().parent().attr("num");

	if($('input[name="number_of_share['+input_number+']"]').val() != "" && $('input[name="amount_share['+input_number+']"]').val() != "" && $('input[name="amount_paid['+input_number+']"]').val() != "")
	{
		no_of_share_paid = (parseFloat(removeCommas($('input[name="amount_paid['+input_number+']"]').val())) * parseInt(removeCommas($('input[name="number_of_share['+input_number+']"]').val()))) / parseFloat(removeCommas($('input[name="amount_share['+input_number+']"]').val()));
	}
	$('input[name="no_of_share_paid['+input_number+']"]').val(addCommas(parseInt(no_of_share_paid)));
	// console.log($(this).val());
});

function removeCommas(str) {
    while (str.search(",") >= 0) {
        str = (str + "").replace(',', '');
    }
    return str;
};

$("#get_person_name").live('change',function(){
	var allotment_frm = $(this);
	//console.log(allotment_frm.val());
	//console.log($(this).parent().parent().parent().attr("num"));
	var input_num = allotment_frm.parent().parent().parent().attr("num");
	$("#loadingAllotment").show();
	$.ajax({
		type: "POST",
		url: "masterclient/get_person",
		data: {"identification_register_no":allotment_frm.val()}, // <--- THIS IS THE CHANGE
		dataType: "json",
		success: function(responses){
			//console.log(allotment_frm.parent().parent().parent().find('input[name="officer_id['+input_num+']"]'));
			//console.log(allotment_frm.parent().parent('div').find('input[name="officer_id['+input_num+']"]'));
			$("#loadingAllotment").hide();
			//console.log(responses);
			if(responses.status == 1)
			{
				var response = responses.info;
				if(response)
				{
					allotment_frm.parent().parent('div').find('input[name="name['+input_num+']"]').val(response['name']);
					allotment_frm.parent().parent().parent().find('input[name="officer_id['+input_num+']"]').val(response['id']);
					allotment_frm.parent().parent().parent().find('input[name="field_type['+input_num+']"]').val(response['field_type']);
					/*if(response['name'] != undefined)
					{*/
					allotment_frm.parent().parent('div').find('a#add_person_link').attr('hidden',"true");
					//allotment_frm.parent().parent('div').find('input[name="name['+input_num+']"]').attr('readonly', true);
					//}
					
				}
				else
				{
					allotment_frm.parent().parent('div').find('a#add_person_link').removeAttr('hidden');
					allotment_frm.parent().parent('div').find('input[name="name['+input_num+']"]').val("");
					//allotment_frm.parent().parent('div').find('input[name="name['+input_num+']"]').attr('readonly', false);
					allotment_frm.parent().parent().parent().find('input[name="officer_id['+input_num+']"]').val("");
					allotment_frm.parent().parent().parent().find('input[name="field_type['+input_num+']"]').val("");
					allotment_frm.parent().parent().parent().find(".name .help-block").remove();
					//console.log(allotment_frm.parent().parent().parent().find(".name .help-block"));
				}
				
			}
			else
			{
				allotment_frm.parent().parent('div').find('input[name="name['+input_num+']"]').val("");
				allotment_frm.parent().parent().parent().find(".name .help-block").remove();
				
				toastr.error("This person is an auditor for this company.", "Error");
			}
			$('#allotment_form').formValidation('revalidateField', 'name['+input_num+']');
		}				
	});
	// console.log($(this).val());
});

function delete_allotment(element) {
	/*if(confirm("Delete This Record?"))
	{*/
		var tr = jQuery(element).parent().parent(),
			allotment_id = tr.find('input[name="member_share_id[]"]').val();
		console.log(allotment_id);

		$.ajax({
			type: "POST",
			url: "masterclient/delete_allotment",
			data: {"allotment_id":allotment_id}, // <--- THIS IS THE CHANGE
			dataType: "json",
			success: function(response){
				console.log(response);
				if(response.status == 1)
				{
					toastr.success("Delete Successfully.", "Success");
					tr.closest("DIV.tr").remove();
				}
				else if(response.status == 2)
				{
					bootbox.alert("This action will result in negative balance of number of share for <a href='masterclient/check_share/"+response.company_code+"/"+response.client_member_share_capital_id+"/"+response.officer_id+"/"+response.field_type+"/"+response.certificate_no+"/"+response.transaction_type+"' target='_blank' class='click_some_member'>some members</a>.", function (result) {
			            
			        });
				}
			}				
		});
		//tr.closest("DIV.tr").remove();
		//console.log($("#allotment_add > div").length);
		if($("#allotment_add > div").length == 1)
		{
			if($('.delete_allotment_button').css('display') == 'block')
			{
				$('.delete_allotment_button').css('display','none');
			}
		}
		
	//}
}

$(document).on('click', '.click_some_member', function (event) {
    bootbox.hideAll();
});

/*$("#cancel").on("click", function() {
	//console.log("inin");
	window.close();
});*/

function formatDateFunc(date) {
	//console.log(date);
  var monthNames = [
    "January", "February", "March",
    "April", "May", "June", "July",
    "August", "September", "October",
    "November", "December"
  ];

  var day = date.getDate();
  //console.log(day.length);
  if(day.toString().length==1)	
  {
  	day="0"+day;
  }
  	
  var monthIndex = date.getMonth();
  var year = date.getFullYear();

  return day + ' ' + monthNames[monthIndex] + ' ' + year;
}

$(".merge_from_certificate_no").live('change',function(){
    var elem = $(this);
    elemt.parent().parent().find( '.validate_edit_from_cert' ).html(" ");
    elemt.parent().parent().find( '.validate_edit_allot_from_cert_live' ).html(" ");
    elemt.parent().parent().find( '.validate_edit_allot_from_cert' ).html(" ");
});

$(".edit_certificate_no").live('change',function(){
    var elemt = $(this);
    elemt.parent().parent().find( '.validate_edit_from_cert' ).html(" ");
    elemt.parent().parent().find( '.validate_edit_allot_from_cert_live' ).html(" ");
    elemt.parent().parent().find( '.validate_edit_allot_from_cert' ).html(" ");
    
});

function confirmFunction(allotment_object)
{
	//console.log(allotment_object);
	var class_with_other = "",
		certificate_info = "",
		merge_info = "", id_member_share = "",
		confirm_number_of_share = 0,
		confirm_amount_share = 0,
		confirm_no_of_share_paid = 0,
		confirm_amount_paid = 0;

	allotment_object["amount_paid"] = allotment_object["amount_paid"].filter(function(val){return val});
	allotment_object["amount_share"] = allotment_object["amount_share"].filter(function(val){return val});
	//allotment_object["certificate_no"] = allotment_object["certificate_no"].filter(function(val){return val});
	allotment_object["class"] = allotment_object["class"].filter(function(val){return val});
	allotment_object["currency"] = allotment_object["currency"].filter(function(val){return val});
	allotment_object["field_type"] = allotment_object["field_type"].filter(function(val){return val});
	allotment_object["id"] = allotment_object["id"].filter(function(val){return val});
	allotment_object["name"] = allotment_object["name"].filter(function(val){return val});
	allotment_object["no_of_share_paid"] = allotment_object["no_of_share_paid"].filter(function(val){return val});
	allotment_object["number_of_share"] = allotment_object["number_of_share"].filter(function(val){return val});
	allotment_object["officer_id"] = allotment_object["officer_id"].filter(function(val){return val});
	allotment_object["others"] = allotment_object["others"].filter(function(val){return val});
	allotment_object["member_share_id"] = allotment_object["member_share_id"].filter(function(val){return val});

	//console.log(allotment_object);
	$(".confirm_allotment_add_row").empty();
	$(".confirm_allotment_add_row2").empty();
	$(".confirm_allotment_add_row3").empty();
	$(".confirm_allotment_add_row4").empty();
	$(".confirm_allotment_add_row5").empty();

	
	$array = allotment_object["date"].split("/");
	$tmp = $array[0];
	$array[0] = $array[1];
	$array[1] = $tmp;
	//unset($tmp);
	$date_2 = $array[0]+"/"+$array[1]+"/"+$array[2];
	//console.log(new Date($date_2));

	$('div#confirm_date').text(formatDateFunc(new Date($date_2)));
	
	
	if(allotment_object["class"] == "Others")
	{
		class_with_other += allotment_object["others"][0];
	}
	else
	{
		class_with_other += allotment_object["class"][0];
	}

	$('div#confirm_class').text(class_with_other);
	$('div#confirm_currency').text(allotment_object["currency"][0]);
	if(allotment_object["id"] != undefined) 
	{	
		
		for(var i = 0; i < allotment_object["id"].length; i++)
		{
			
			confirm_number_of_share +=  parseInt(removeCommas(allotment_object["number_of_share"][i]));
			confirm_amount_share +=  parseFloat(removeCommas(allotment_object["amount_share"][i]));
			confirm_no_of_share_paid +=  parseInt(removeCommas(allotment_object["no_of_share_paid"][i]));
			confirm_amount_paid +=  parseFloat(removeCommas(allotment_object["amount_paid"][i]));

			/*if(allotment_object["member_share_id"][i] != undefined)
			{
				id_member_share = allotment_object["member_share_id"][i];
				console.log(id_member_share);
			}
			else
			{
				id_member_share = null;
				console.log(id_member_share);
			}*/

			!function (i) {
				$.ajax({
			        type: "POST",
			        url: "masterclient/get_allotment_certificate",
			        data: {"client_member_share_capital_id":allotment_object["client_member_share_capital_id"], "company_code":allotment_object["company_code"], "officer_id": allotment_object["officer_id"][i], "field_type": allotment_object["field_type"][i], "transaction_type": allotment_object["transaction_type"], "date": allotment_object["date"], "member_share_id": allotment_object["member_share_id"][i]}, // <--- THIS IS THE CHANGE
			        dataType: "json",
			        async: false,
			        success: function(response){
			            console.log(response);
			            var total_number_of_share = 0, total_amount_paid = 0, total_no_of_share_paid = 0, total_amount_share = 0;

			            if(response != null)
			            {
			            	certificate_info = response.certificate_data;
			            	merge_info = response.merge_status;
			            }
			            else
			            {
			            	certificate_info = null;
			            	merge_info = 0;
			            }
			            
			            //'+ (allotment[t]["identification_no"]!=null ? allotment[t]["identification_no"] : allotment[t]["register_no"]) +'
			            //$b += '<td>'+buyback_object["certificate_no"][i]+'</td>';
			            $b=""; 
						$b2=""; 
			            $b += '<tr class="confirm_allotment_add_row">';
			            $b += '<td style="width:50px !important;">'+(i+1)+'</td>';
			            $b += '<td><div class="input-group mb-md"><input type="text" name="id" class="form-control" value="'+allotment_object["id"][i]+'" id="id" disabled/></div><div class="input-group mb-md"><input type="text" name="name" class="form-control" value="'+allotment_object["name"][i]+'" disabled/></div></td>';
			            $b += '<td><div class="input-group mb-md"><div class="member_classes"><input type="text" name="class" class="form-control member_class" id="member_class" value="'+allotment_object["class"][i]+'" disabled/></div><div id="others_field" class="mb-md others_field" hidden><div style="font-weight:bold;"></div><input type="text" name="others" id="member_others" class="form-control member_others" value="'+allotment_object["others"][i]+'" disabled/></div></div><div class="input-group mb-md"><input type="text" name="currency" class="form-control member_currency" id="member_currency" value="'+allotment_object["currency"][i]+'" disabled/></div></td>';
			            $b += '<td><div class="input-group mb-md"><input type="text" name="number_of_share" class="form-control" value="'+addCommas(allotment_object["number_of_share"][i])+'" style="text-align:right;" disabled/></div><div class="input-group mb-md"><input type="text" name="amount_share" class="form-control" value="'+addCommas(allotment_object["amount_share"][i])+'" style="text-align:right;" disabled/></div></td>';
			            $b += '<td><div class="input-group mb-md"><input type="text" name="no_of_share_paid" class="form-control" value="'+addCommas(allotment_object["no_of_share_paid"][i])+'" style="text-align:right;" disabled/></div><div class="input-group mb-md"><input type="text" name="amount_paid" class="form-control" value="'+addCommas(allotment_object["amount_paid"][i])+'" style="text-align:right;" disabled/></div></td>';
			            
			            $b += '</tr>';
			            $b += '<tr class="confirm_allotment_add_row2">';
			            $b += '<th style="width:50px !important;"></th>';
			           	$b += '<th colspan="2">Number of Shares</th>';
			           	$b += '<th colspan="2">Certificate No.'+ ((allotment_object["previous_cert"][i] != allotment_object["previous_new_cert"][i])?"<input type='text' class='hidden merge_status"+i+"' name='merge_status["+i+"]' value='1'/>":(certificate_info != null && allotment == null || certificate_info != null && allotment != null && certificate_info.length > 0 && merge_info == 0)?"<input type='text' class='merge_status"+i+" hidden' name='merge_status["+i+"]' value='0'/><button type='button' class='btn btn-primary mergeAllotment"+i+" mergeAllotment' id='mergeAllotment' style='float: right;' onclick='mergeBothAllotment("+i+")'>Merge</button><button type='button' class='btn btn-primary cancelMergeAllotment"+i+"' id='cancelMergeAllotment' style='float: right;display:none' onclick='cancelMergeBothAllotment("+i+")'>Cancel</button>":(certificate_info == null && allotment == null  || certificate_info == null && allotment != null)?"<input type='text' class='hidden merge_status"+i+"' name='merge_status["+i+"]' value='0'/>":"<input type='text' class='hidden merge_status"+i+"' name='merge_status["+i+"]' value='1'/><button type='button' class='btn btn-primary mergeAllotment"+i+" mergeAllotment' id='mergeAllotment' style='float: right;display:none' onclick='mergeBothAllotment("+i+")'>Merge</button><button type='button' class='btn btn-primary cancelMergeAllotment"+i+"' id='cancelMergeAllotment' style='float: right;' onclick='cancelMergeBothAllotment("+i+")'>Cancel</button>") +'</th>';
			            $b += '</tr>';

			            $("#confirm_allotment_add").append($b);

			            if(allotment_object["others"][i] != null)
						{
							$(".others_field").removeAttr('hidden');
							$(".member_classes").attr("hidden","true");
						}
						else
						{
							$(".others_field").attr("hidden","true");
							$(".member_classes").removeAttr('hidden');
						}

			            //console.log(certificate_info);
			            if(certificate_info != null && allotment == null || certificate_info != null && allotment != null && certificate_info.length > 0 && merge_info == 0)
			            {
			            	//console.log(certificate_info);
			            	//console.log(certificate_info.length);
			            	for(var p = 0; p < certificate_info.length; p++)
			            	{
			            		total_number_of_share += parseInt(certificate_info[p]["number_of_share"]);
			            		total_amount_share += parseFloat(certificate_info[p]["amount_share"]);
			            		total_no_of_share_paid += parseInt(certificate_info[p]["no_of_share_paid"]);
			            		total_amount_paid += parseFloat(certificate_info[p]["amount_paid"]);
			            		$b1=""; 
			            		$b1 += '<tr class="confirm_allotment_add_row3 merge_item'+i+'">';
					            $b1 += '<td style="width:50px !important;"></td>';
					           	$b1 += '<td colspan="2">'+addCommas(certificate_info[p]["number_of_share"])+'</td>';
					           	$b1 += '<td colspan="2">'+certificate_info[p]["certificate_no"]+'</div></td>';
					            $b1 += '</tr>';

					            $("#confirm_allotment_add").append($b1);
			            	}
			            }
			            else
			            {//console.log(certificate_info);
			            	if(certificate_info != null)
			            	{
				            	for(var p = 0; p < certificate_info.length; p++)
				            	{
				            		if(certificate_info[p]["cert_status"] == '2' && allotment_object["previous_cert"][i] ==  certificate_info[p]["new_certificate_no"])
				            		{ /*styele="display:none;"*/
				            			total_number_of_share += parseInt(certificate_info[p]["number_of_share"]);
					            		total_amount_share += parseFloat(certificate_info[p]["amount_share"]);
					            		total_no_of_share_paid += parseInt(certificate_info[p]["no_of_share_paid"]);
					            		total_amount_paid += parseFloat(certificate_info[p]["amount_paid"]);
					            		$b1=""; 
					            		$b1 += '<tr class="confirm_allotment_add_row3 merge_item'+i+'" style="display:none">';
							            $b1 += '<td style="width:50px !important;"></td>';
							           	$b1 += '<td colspan="2">'+addCommas(certificate_info[p]["number_of_share"])+'</td>';
							           	$b1 += '<td colspan="2">'+certificate_info[p]["certificate_no"]+'</div></td>';
							            $b1 += '</tr>';

							            $("#confirm_allotment_add").append($b1);
				            		}
				            		
				            	}
				            }
			            }
			            
			            if(certificate_info == null && allotment == null || certificate_info != null && allotment == null || certificate_info == null && allotment != null || certificate_info != null && allotment != null && certificate_info.length > 0 && merge_info == 0)
			            {
				            $b2 += '<tr class="confirm_allotment_add_row4 merge_item'+i+'">';
				            $b2 += '<td style="width:50px !important;"></td>';
				           	$b2 += '<td colspan="2">'+addCommas(allotment_object["number_of_share"][i])+'</td>';
				        }
				        else
				        {
				        	$b2 += '<tr class="confirm_allotment_add_row4 merge_item'+i+'" style="display:none">';
				            $b2 += '<td style="width:50px !important;"></td>';
				           	$b2 += '<td colspan="2">'+addCommas(allotment_object["number_of_share"][i])+'</td>';
				        }

			           	//console.log("member_share_id======"+allotment_object["member_share_id"][i] != null);
			           	if(allotment){
			           		//console.log(allotment);
			           		if(!edit_cert && allotment_object["member_share_id"][i] != null)
			           		{
			           			for(var f = 0; f < allotment.length; f++)
			           			{
			           				if(certificate_info == null && allotment == null || certificate_info != null && allotment == null || certificate_info == null && allotment != null || certificate_info == null && allotment == null || certificate_info != null && allotment != null && certificate_info.length > 0 && merge_info == 0)
				            		{
				           				if(allotment_object["member_share_id"][i] == allotment[f]["id"])
				           				{
				           					$b2 += '<td colspan="2"><div class="alloment_group"><div style="width:120px"><input type="text" class="hidden form-control cert_id" name="cert_id[]" id="cert_id" value="'+allotment_object["cert_id"][i]+'"/><input type="text" name="new_certificate_no['+i+']" class="form-control new_certificate_no'+i+' edit_certificate_no check_cert_in_live" value="'+ ((allotment[f]["certificate_no"]!=null)?allotment[f]["certificate_no"] : "") +'"/></div><div class="validate_edit_allot_from_cert"></div><div class="validate_edit_allot_from_cert_live"></div></div></td>';
				           				}
				           			}
				           			else
				           			{
				           				if(certificate_info != null)
				            			{
					           				for(var b = 0; b < certificate_info.length; b++)
					            			{
						           				if(allotment_object["cert_id"][i] == certificate_info[b]["id"])
						           				{
						           					$b2 += '<td colspan="2"><div class="alloment_group"><div style="width:120px"><input type="text" class="hidden form-control cert_id" name="cert_id[]" id="cert_id" value=""/><input type="text" name="new_certificate_no['+i+']" class="form-control new_certificate_no'+i+' edit_certificate_no check_cert_in_live" value=""/></div><div class="validate_edit_allot_from_cert"></div><div class="validate_edit_allot_from_cert_live"></div></div></td>';
						           				}
						           			}
						           		}
				           			}
			           			}

			           			
			           		}
			           		else
			           		{	
			           			if(allotment_object["member_share_id"][i] == null){
			           				$b2 += '<td colspan="2"><div class="alloment_group"><div style="width:120px"><input type="text" class="hidden form-control cert_id" name="cert_id[]" id="cert_id" value="'+allotment_object["cert_id"][i]+'"/><input type="text" name="new_certificate_no['+i+']" class="form-control new_certificate_no'+i+' edit_certificate_no check_cert_in_live" value=""/></div><div class="validate_edit_allot_from_cert"></div><div class="validate_edit_allot_from_cert_live"></div></div></td>';
			           			}
			           			else
			           			{
			           				//console.log(allotment[i]["certificate_no"]);
			           				$b2 += '<td colspan="2"><div class="alloment_group"><div style="width:120px"><input type="text" class="hidden form-control cert_id" name="cert_id[]" id="cert_id" value="'+allotment_object["cert_id"][i]+'"/><input type="text" name="new_certificate_no['+i+']" class="form-control new_certificate_no'+i+' edit_certificate_no check_cert_in_live" value="'+ ((allotment[i]["certificate_no"]!=null)?allotment[i]["certificate_no"] : "") +'"/></div><div class="validate_edit_allot_from_cert"></div><div class="validate_edit_allot_from_cert_live"></div></div></td>';
			           			}
			           		}
			           		
			           	}
			           	else
			           	{
			           		$b2 += '<td colspan="2"><div class="alloment_group"><div style="width:120px"><input type="text" class="hidden form-control cert_id" name="cert_id[]" id="cert_id" value="'+allotment_object["cert_id"][i]+'"/><input type="text" name="new_certificate_no['+i+']" class="form-control new_certificate_no'+i+' edit_certificate_no check_cert_in_live" value=""/></div><div class="validate_edit_allot_from_cert"></div><div class="validate_edit_allot_from_cert_live"></div></div></td>';
			           	}
			            $b2 += '</tr>';

			            if(certificate_info != null && allotment == null || certificate_info == null && allotment == null  || certificate_info != null && allotment != null && certificate_info.length > 0 && merge_info == 0)
			            {
				            $b2 += '<tr class="confirm_allotment_add_row5 merge_item_total'+i+'" style="display:none">';
				            $b2 += '<td style="width:50px !important;"></td>';
				           	$b2 += '<td colspan="2">'+addCommas((total_number_of_share + parseInt(allotment_object["number_of_share"][i])))+'</td>';
				           	$b2 += '<td colspan="2"><div class="alloment_group"><div style="width:120px"><input type="text" class="hidden form-control cert_id" name="cert_id[]" id="cert_id" value="'+allotment_object["cert_id"][i]+'"/><input type="hidden" name="merge_number_of_share['+i+']" class="form-control merge_number_of_share'+i+'" value="'+(total_number_of_share + parseInt(allotment_object["number_of_share"][i]))+'"/><input type="hidden" name="merge_amount_share['+i+']" class="form-control merge_amount_share'+i+'" value="'+(total_amount_share + parseFloat(allotment_object["amount_share"][i]))+'"/><input type="hidden" name="merge_no_of_share_paid['+i+']" class="form-control merge_no_of_share_paid'+i+'" value="'+(total_no_of_share_paid + parseInt(allotment_object["no_of_share_paid"][i]))+'"/><input type="hidden" name="merge_amount_paid['+i+']" class="form-control merge_amount_paid'+i+'" value="'+(total_amount_paid + parseFloat(allotment_object["amount_paid"][i]))+'"/><input type="hidden" name="previous_merge_cert_num['+i+']" value=""/><input type="text" name="merge_certificate_no['+i+']" class="form-control merge_certificate_no'+i+' merge_certificate_no check_cert_in_live" value=""/><input type="hidden" name="latest_merge_cert_no['+i+']" class="form-control latest_merge_cert_no'+i+' latest_merge_cert_no check_cert_in_live" value=""/></div><div class="validate_edit_allot_from_cert"></div><div class="validate_edit_allot_from_cert_live"></div></div></td>';
				            $b2 += '</tr>';
				        }
				        else
				        {
				        	if(certificate_info != null)
			            	{
			            		var diff_num_of_share = 0, latest_cert, latest_num_of_share;

			            		if(allotment_object["previous_cert"][i] != allotment_object["previous_new_cert"][i])
			            		{
						        	for(var r = 0; r < certificate_info.length; r++)
			            			{
				           				if(allotment_object["previous_new_cert"][i] == certificate_info[r]["certificate_no"])
				           				{
				           					latest_cert = certificate_info[r]["certificate_no"];
				           					latest_num_of_share = parseInt(certificate_info[r]["number_of_share"]);
				           					latest_cert_id = certificate_info[r]["id"];
				           					// console.log(latest_cert);
				           					// console.log(latest_num_of_share);
				           				}
				           			}

				           			for(var g = 0; g < certificate_info.length; g++)
				           			{
				           				if(allotment_object["cert_id"][i] == certificate_info[g]["id"])
								        {
								        	$b2 += '<tr class="confirm_allotment_add_row5 merge_item_total'+i+'">';
								            $b2 += '<td style="width:50px !important;"></td>';
								            /*if(allotment_object["cert_id"][i] == certificate_info[r]["id"])
								            {*/
								            	// console.log(allotment_object["number_of_share"][i]);
								            	// console.log(certificate_info[g]["number_of_share"]);

								            	if(parseInt(allotment_object["number_of_share"][i]) > parseInt(certificate_info[g]["number_of_share"]))
								            	{
								            		diff_num_of_share = parseInt(allotment_object["number_of_share"][i]) - parseInt(certificate_info[g]["number_of_share"]);
								            		$b2 += '<td colspan="2">'+addCommas((diff_num_of_share + latest_num_of_share))+'</td>';
								            		$b2 += '<td colspan="2"><div class="alloment_group"><div style="width:120px"><input type="text" class="hidden form-control cert_id" name="cert_id[]" id="cert_id" value="'+latest_cert_id+'"/><input type="hidden" name="merge_number_of_share['+i+']" class="form-control merge_number_of_share'+i+'" value="'+(diff_num_of_share + parseInt(allotment_object["number_of_share"][i]))+'"/><input type="hidden" name="merge_amount_share['+i+']" class="form-control merge_amount_share'+i+'" value="'+(diff_num_of_share + parseFloat(allotment_object["amount_share"][i]))+'"/><input type="hidden" name="merge_no_of_share_paid['+i+']" class="form-control merge_no_of_share_paid'+i+'" value="'+(diff_num_of_share + parseInt(allotment_object["no_of_share_paid"][i]))+'"/><input type="hidden" name="merge_amount_paid['+i+']" class="form-control merge_amount_paid'+i+'" value="'+(diff_num_of_share + parseInt(allotment_object["amount_paid"][i]))+'"/><input type="hidden" name="previous_merge_cert_num['+i+']" value="'+ ((latest_cert!=null)?latest_cert : "") +'"/><input type="hidden" name="merge_certificate_no['+i+']" class="form-control merge_certificate_no'+i+' merge_certificate_no check_cert_in_live" value=""/><input type="text" name="latest_merge_cert_no['+i+']" class="form-control latest_merge_cert_no'+i+' latest_merge_cert_no check_cert_in_live" value="'+ ((latest_cert!=null)?latest_cert : "") +'"/></div><div class="validate_edit_allot_from_cert"></div><div class="validate_edit_allot_from_cert_live"></div></div></td>';
								            	}
								            	else if(parseInt(allotment_object["number_of_share"][i]) < parseInt(certificate_info[g]["number_of_share"]))
								            	{
								            		diff_num_of_share = parseInt(allotment_object["number_of_share"][i]) - parseInt(certificate_info[g]["number_of_share"]);
								            		$b2 += '<td colspan="2">'+addCommas((diff_num_of_share + latest_num_of_share))+'</td>';
								            		$b2 += '<td colspan="2"><div class="alloment_group"><div style="width:120px"><input type="text" class="hidden form-control cert_id" name="cert_id[]" id="cert_id" value="'+latest_cert_id+'"/><input type="hidden" name="merge_number_of_share['+i+']" class="form-control merge_number_of_share'+i+'" value="'+(diff_num_of_share + parseInt(allotment_object["number_of_share"][i]))+'"/><input type="hidden" name="merge_amount_share['+i+']" class="form-control merge_amount_share'+i+'" value="'+(diff_num_of_share + parseFloat(allotment_object["amount_share"][i]))+'"/><input type="hidden" name="merge_no_of_share_paid['+i+']" class="form-control merge_no_of_share_paid'+i+'" value="'+(diff_num_of_share + parseInt(allotment_object["no_of_share_paid"][i]))+'"/><input type="hidden" name="merge_amount_paid['+i+']" class="form-control merge_amount_paid'+i+'" value="'+(diff_num_of_share + parseInt(allotment_object["amount_paid"][i]))+'"/><input type="hidden" name="previous_merge_cert_num['+i+']" value="'+ ((latest_cert!=null)?latest_cert : "") +'"/><input type="hidden" name="merge_certificate_no['+i+']" class="form-control merge_certificate_no'+i+' merge_certificate_no check_cert_in_live" value=""/><input type="text" name="latest_merge_cert_no['+i+']" class="form-control latest_merge_cert_no'+i+' latest_merge_cert_no check_cert_in_live" value="'+ ((latest_cert!=null)?latest_cert : "") +'"/></div><div class="validate_edit_allot_from_cert"></div><div class="validate_edit_allot_from_cert_live"></div></div></td>';
								            	}
								            	else if(parseInt(allotment_object["number_of_share"][i]) == parseInt(certificate_info[g]["number_of_share"]))
								            	{
								            		diff_num_of_share = 0;
								            		$b2 += '<td colspan="2">'+addCommas((diff_num_of_share + latest_num_of_share))+'</td>';
								            		$b2 += '<td colspan="2"><div class="alloment_group"><div style="width:120px"><input type="text" class="hidden form-control cert_id" name="cert_id[]" id="cert_id" value="'+latest_cert_id+'"/><input type="hidden" name="merge_number_of_share['+i+']" class="form-control merge_number_of_share'+i+'" value="'+(diff_num_of_share + parseInt(allotment_object["number_of_share"][i]))+'"/><input type="hidden" name="merge_amount_share['+i+']" class="form-control merge_amount_share'+i+'" value="'+(diff_num_of_share + parseFloat(allotment_object["amount_share"][i]))+'"/><input type="hidden" name="merge_no_of_share_paid['+i+']" class="form-control merge_no_of_share_paid'+i+'" value="'+(diff_num_of_share + parseInt(allotment_object["no_of_share_paid"][i]))+'"/><input type="hidden" name="merge_amount_paid['+i+']" class="form-control merge_amount_paid'+i+'" value="'+(diff_num_of_share + parseInt(allotment_object["amount_paid"][i]))+'"/><input type="hidden" name="previous_merge_cert_num['+i+']" value="'+ ((latest_cert!=null)?latest_cert : "") +'"/><input type="hidden" name="merge_certificate_no['+i+']" class="form-control merge_certificate_no'+i+' merge_certificate_no check_cert_in_live" value=""/><input type="text" name="latest_merge_cert_no['+i+']" class="form-control latest_merge_cert_no'+i+' latest_merge_cert_no check_cert_in_live" value="'+ ((latest_cert!=null)?latest_cert : "") +'"/></div><div class="validate_edit_allot_from_cert"></div><div class="validate_edit_allot_from_cert_live"></div></div></td>';
								            	}
								           		
								            //}
								           	
								            $b2 += '</tr>';
								        }
								    }
								}
								else
								{
									for(var r = 0; r < certificate_info.length; r++)
			            			{
				           				if(allotment_object["cert_id"][i] == certificate_info[r]["id"])
				           				{
								        	$b2 += '<tr class="confirm_allotment_add_row5 merge_item_total'+i+'">';
								            $b2 += '<td style="width:50px !important;"></td>';
								           	$b2 += '<td colspan="2">'+addCommas((total_number_of_share + parseInt(allotment_object["number_of_share"][i])))+'</td>';
								           	$b2 += '<td colspan="2"><div class="alloment_group"><div style="width:120px"><input type="text" class="hidden form-control cert_id" name="cert_id[]" id="cert_id" value="'+allotment_object["cert_id"][i]+'"/><input type="hidden" name="merge_number_of_share['+i+']" class="form-control merge_number_of_share'+i+'" value="'+(total_number_of_share + parseInt(allotment_object["number_of_share"][i]))+'"/><input type="hidden" name="merge_amount_share['+i+']" class="form-control merge_amount_share'+i+'" value="'+(total_amount_share + parseFloat(allotment_object["amount_share"][i]))+'"/><input type="hidden" name="merge_no_of_share_paid['+i+']" class="form-control merge_no_of_share_paid'+i+'" value="'+(total_no_of_share_paid + parseInt(allotment_object["no_of_share_paid"][i]))+'"/><input type="hidden" name="merge_amount_paid['+i+']" class="form-control merge_amount_paid'+i+'" value="'+(total_amount_paid + parseFloat(allotment_object["amount_paid"][i]))+'"/><input type="hidden" name="previous_merge_cert_num['+i+']" value="'+ ((certificate_info[r]["certificate_no"]!=null)?certificate_info[r]["certificate_no"] : "") +'"/><input type="text" name="merge_certificate_no['+i+']" class="form-control merge_certificate_no'+i+' merge_certificate_no check_cert_in_live" value="'+ ((certificate_info[r]["certificate_no"]!=null)?certificate_info[r]["certificate_no"] : "") +'"/></div><div class="validate_edit_allot_from_cert"></div><div class="validate_edit_allot_from_cert_live"></div></div></td>';
								            $b2 += '</tr>';
								        }
								    }
								}
							}
				        }

						$("#confirm_allotment_add").append($b2);

						$('#allotment_form').formValidation('addField', 'new_certificate_no['+i+']', certificateNoValidators);
						$('#allotment_form').formValidation('addField', 'merge_certificate_no['+i+']', certificateNoValidators);
			        }               
			    });
			} (i);

			/*$b=""; 
			$b += '<div class="tr editing" method="post" name="form'+i+'" id="form'+i+'">';
			$b += '<div class="td"><div class="input-group mb-md"><input type="text" name="id" class="form-control" value="'+allotment_object["id"][i]+'" id="id" disabled/></div><div class="input-group mb-md"><input type="text" name="name" class="form-control" value="'+allotment_object["name"][i]+'" disabled/></div></div>';
			$b += '<div class="td"><div class="input-group mb-md"><input type="text" name="class" class="form-control member_class" id="member_class" value="'+allotment_object["class"][i]+'" disabled/><div id="others_field" class="mb-md others_field" hidden><div style="font-weight:bold;">Others: </div><input type="text" name="others" id="member_others" class="form-control member_others" value="'+allotment_object["others"][i]+'" disabled/></div></div><div class="input-group mb-md"><input type="text" name="currency" class="form-control member_currency" id="member_currency" value="'+allotment_object["currency"][i]+'" disabled/></div></div>';
			$b += '<div class="td"><div class="input-group mb-md"><input type="text" name="number_of_share" class="form-control" value="'+allotment_object["number_of_share"][i]+'" disabled/></div><div class="input-group mb-md"><input type="text" name="amount_share" class="form-control" value="'+allotment_object["amount_share"][i]+'" disabled/></div></div>';
			$b += '<div class="td"><div class="input-group mb-md"><input type="text" name="no_of_share_paid" class="form-control" value="'+allotment_object["no_of_share_paid"][i]+'" disabled/></div><div class="input-group mb-md"><input type="text" name="amount_paid" class="form-control" value="'+allotment_object["amount_paid"][i]+'" disabled/></div></div>';
			$b += '<div class="td"><input type="text" class="form-control" name="certificate_no" value="'+allotment_object["certificate_no"][i]+'" disabled/></div>';
			//$a += '<div class="td action"><button type="button" class="btn btn-primary" onclick="edit_charge(this);">Save</button></div>';
			$b += '</div>';*/

			

			

			if(allotment_object["others"] != "")
			{
				if(allotment_object["others"][i] != "")
				{
					//console.log("i==="+i);
					$("#form"+i+" #others_field").removeAttr('hidden');
				}
				else
				{
					$("#form"+i+" #others_field").attr('hidden',"true");
				}
			}
		}
		$('div#confirm_total_number_of_shares').text(addCommas(confirm_number_of_share));
		$('div#confirm_total_amount_shares').text(addCommas(confirm_amount_share.toFixed(2)));
		$('div#confirm_total_no_of_share_paid').text(addCommas(confirm_no_of_share_paid));
		$('div#confirm_total_amount_paid').text(addCommas(confirm_amount_paid.toFixed(2)));
			
	}
	if(access_right_member_module == "read" || client_status != "1")
	{
		$("select#class").attr("disabled", true);
		$("#transaction_date").attr("disabled", true);
		$(".id").attr("disabled", true);
		$(".number_of_share").attr("disabled", true);
		$(".amount_share").attr("disabled", true);
		$(".amount_paid").attr("disabled", true);
		$(".edit_certificate_no ").attr("disabled", true);
		$(".mergeAllotment").attr("disabled", true);
	}
}

function mergeBothAllotment(table_index) {
    /*document.getElementsByClassName("merge_item"+table_index+"").style.display = 'none';
    document.getElementsByClassName("merge_item_total"+table_index+"").style.display = 'table-row';*/
    
    $('.merge_item'+table_index+'').css('display','none');
   
    $('.merge_item_total'+table_index+'').css('display','table-row');
    $('.mergeAllotment'+table_index+'').css('display','none');
    $('.cancelMergeAllotment'+table_index+'').css('display','block');
    $('.merge_status'+table_index+'').val("1");
    $('.new_certificate_no'+table_index+'').val("");
    

}

function cancelMergeBothAllotment(table_index) {
	$('.merge_item'+table_index+'').css('display','table-row');
   
    $('.merge_item_total'+table_index+'').css('display','none');
    $('.mergeAllotment'+table_index+'').css('display','block');
    $('.cancelMergeAllotment'+table_index+'').css('display','none');
    $('.merge_status'+table_index+'').val("0");
    //console.log($('.merge_status'+table_index+''));
    $('.merge_certificate_no'+table_index+'').val("");
}

/*class: {
        validators: {
            callback: {
                message: 'The Class field is required',
                callback: function(value, validator, $field) {
                    var options = validator.getFieldElements('class').val();
                    console.log(options);
                    return (options != null && options != "0");
                }
            }
        }
    },*/
//$("#allotment_confirm").onfocus = function () {
    //console.log("testtest===="+document.getElementById("allotment_confirm").getElementsByClassName("active"));
 //};
/*console.log($('.tab-pane.active').attr('id'));
if ( $('.tab-pane.active').attr('id')== "allotment_confirm") {

//Do stuff
console.log("inininni");
}*/
/*(function () {
$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
var target = $(e.target).attr("href")
alert(target);
});
})*/



