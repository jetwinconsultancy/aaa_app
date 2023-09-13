var serviceValidators = {
            row: '.input-dropdown',
            validators: {
                callback: {
                    message: 'The Service field is required',
                    callback: function(value, validator, $field) {
                        var num = jQuery($field).parent().parent().parent().attr("num");
                        var options = validator.getFieldElements('service['+num+']').val();
                        //console.log("options====="+options);
                        return (options != null && options != "0");
                    }
                }
            }
        },
invoiceDescriptionValidators = {
    row: '.input-group', 
    validators: {
        notEmpty: {
            message: 'The Invoice Description field is required.'
        }
    }
},
amountValidators = {
    row: '.input-group', 
    validators: {
        notEmpty: {
            message: 'The Amount field is required.'
        }/*,
        integer: {
            message: 'The value is not an integer',
            // The default separators
            thousandsSeparator: ',',
            decimalSeparator: '.'
        }*/
    }
},
currencyValidators = {
    row: '.input-dropdown',
    validators: {
        callback: {
            message: 'The Currency field is required',
            callback: function(value, validator, $field) {
                var num = jQuery($field).parent().parent().parent().attr("num");
                var options = validator.getFieldElements('currency['+num+']').val();
                //console.log("options====="+options);
                return (options != null && options != "0");
            }
        }
    }
},
unitPricingValidators = {
    row: '.input-dropdown',
    validators: {
        callback: {
            message: 'The Unit Pricing field is required',
            callback: function(value, validator, $field) {
                var num = jQuery($field).parent().parent().parent().attr("num");
                var options = validator.getFieldElements('unit_pricing['+num+']').val();
                return (options != null && options != "0");
            }
        }
    }
};

if(localStorage.getItem("billing_currency") == null)
{
	$.ajax({
	    type: "GET",
	    url: "masterclient/get_currency",
	    dataType: "json",
	    async: false,
	    success: function(data){
	        if(data.tp == 1){
	        	localStorage.setItem("billing_currency", JSON.stringify(data['result']));
	        }
	        else{
	            alert(data.msg);
	        }  
	    }               
	});
}

if(localStorage.getItem("billing_unit_pricing") == null)
{
	$.ajax({
	    type: "GET",
	    url: "masterclient/get_unit_pricing",
	    async:false,
	    dataType: "json",
	    success: function(data){
	        //console.log(data);
	        if(data.tp == 1){
	        	localStorage.setItem("billing_unit_pricing", JSON.stringify(data['result']));
	        }
	        else{
	            alert(data.msg);
	        }  
	    }               
	});
}


function search_billing_function()
{
	$('#loadingmessage').show();
	$.ajax({
        type: 'POST',
        url: "masterclient/search_client_billing",
        data: {"company_code": $("#w2-billing .company_code").val()},
        dataType: 'json',
        success: function(response){
        	$('#loadingmessage').hide();
        	$(".billing_collapsible").remove();
        	$(".billing_content").remove();

        	service_category_list = response["service_category"];
        	client_billing_info = response["client_billing_info"];

        	var service_category = Object.keys(response["service_category"]);

        	for(var a = 1; a <= service_category.length; a++)
        	{
        		var service_category_id = a;
        		var service_category_name = response["service_category"][a];
				var tbl_service_category_name = response["service_category"][a].replace(/[\. ,:-]+/g, '');
        		var client_info = response["client_billing_info"];

        		$a = '';
        		$a += '<button type="button" class="billing_collapsible" style="margin-top: 10px;">';
        		$a += '<span style="font-size: 2.4rem;" class="service_engage_title">'+service_category_name+'</span>';
        		$a += '</button>';
        		$a += '<div class="billing_content" id="div_'+tbl_service_category_name+'_content">';
				$a += '</div>';

	        	$("#billing_form").append($a);
        	}
			coll = document.getElementsByClassName("billing_collapsible");

			for (var i = 0; i < coll.length; i++) {
			  coll[i].addEventListener("click", function() {
			  	console.log(this.firstElementChild.innerHTML);
			    this.classList.toggle("billing_active");
			    var content = this.nextElementSibling;
			    if (content.style.maxHeight){
			      content.style.maxHeight = null;
			    } else {
			    	get_each_service_engagement_info(this.firstElementChild.innerHTML);
			      	content.style.maxHeight = "100%";
			    } 
			  });
			}
        }
    })
}

function get_each_service_engagement_info(title) 
{
	$('#loadingmessage').show();
	$.ajax({
        type: 'POST',
        url: "masterclient/search_client_billing",
        data: {"company_code": $("#w2-billing .company_code").val()},
        dataType: 'json',
        success: function(response){
        	$('#loadingmessage').hide();
        	service_category_list = response["service_category"];
        	client_billing_info = response["client_billing_info"];
        	var numberForRetrieve = 1;
        	var service_category = Object.keys(response["service_category"]);

        	for(var a = 1; a <= service_category.length; a++)
        	{
        		var service_category_id = a;
        		var service_category_name = response["service_category"][a];
				var tbl_service_category_name = response["service_category"][a].replace(/[\. ,:-]+/g, '');
        		var client_info = response["client_billing_info"];

        		if(service_category_name == title)
        		{
        			$("#table_"+tbl_service_category_name+"_info").remove();

        			$a = '';
	        		
	        		$a += '<div class="row" id="table_'+tbl_service_category_name+'_info"><div class="col-lg-12 col-xl-6">';
	        		$a += '<div style="display: table; border-collapse: collapse; margin-top: 20px; margin-bottom: 20px; width: 100%"><thead><div class="tr"> ';
	        		$a += '<div class="th" valign=middle style="width:200px;text-align: center">Service</div>';
	        		$a += '<div class="th" valign=middle style="width:250px;text-align: center">Invoice Description</div>';
	        		$a += '<div class="th" style="width:87px;text-align: center">Fee</div>';
	        		$a += '<div class="th" style="width:100px;text-align: center">Unit Pricing</div>';
	        		$a += '<div class="th" style="width:100px;text-align: center">Servicing Firm</div>';
	        		$a += '<div class="th" style="width:70px;text-align: center">Deactivate</div>';
	        		$a += '<a href="javascript: void(0);" class="th" rowspan =2 style="color: #D9A200;width:50px; outline: none !important;text-decoration: none;"><span class="billing_info_Add" id="billing_info_Add" data-service_category_id="'+service_category_id+'" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Service Engagement Information" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add</span></a>';
	        		$a += '</div></thead><div class="tbody" id="body_'+tbl_service_category_name+'_info">';
	        		$a += '<div class="tr '+tbl_service_category_name+'_empty_row">';
	        		$a += '<div class="td" style="padding-bottom:30px; border-right: none !important;">';
	        		$a += '<div style="width:200px;"></div>';
	        		$a += '</div>';
	        		$a += '<div class="td" style="border-right: none !important; border-left: none !important;">';
	        		$a += '<div style="width:245px;"></div>';
	        		$a += '</div>';
	        		$a += '<div class="td" style="border-right: none !important; border-left: none !important;">';
	        		$a += '<div style="width:87px;"><span style="font-weight:bold; font-size:20px;">N/A</span></div>';
	        		$a += '</div>';
	        		$a += '<div class="td" style="border-right: none !important; border-left: none !important;">';
	        		$a += '<div style="width:100px;"></div>';
	        		$a += '</div>';
	        		$a += '<div class="td" style="border-right: none !important; border-left: none !important;">';
	        		$a += '<div style="width:100px;"></div>';
	        		$a += '</div>';
	        		$a += '<div class="td" style="border-right: none !important; border-left: none !important;">';
	        		$a += '<div style="width:70px;"></div>';
	        		$a += '</div>';
	        		$a += '<div class="td" style="border-left: none !important;">';
	        		$a += '<div style="width:50px;"></div>';
	        		$a += '</div>';
	        		$a += '</div>';
	        		$a += '</div></div>';
	        		$a += '</div></div>';

		        	$("#div_"+tbl_service_category_name+"_content").append($a);

        			for(var i = 0; i < client_info.length; i++)
		    		{	
		    			if(client_info[i]['service_type'] == service_category_id)
		    			{
		    				$("." + client_info[i]['category_description'].replace(/[\. ,:-]+/g, '') + "_empty_row").remove();

			    			$b = '';
			    			$b += '<div class="tr editing" method="post" name="form'+i+'" id="form'+i+'" num="'+i+'">';
				            $b += '<div class="hidden"><input type="text" class="form-control company_code" name="company_code" value="'+company_code+'"/></div>';
				            $b += '<div class="hidden"><input type="text" class="form-control client_billing_info_id" name="client_billing_info_id['+i+']" id="client_billing_info_id" value="'+client_info[i]["client_billing_info_id"]+'"/></div>';
				            $b += '<div class="td"><div class="input-dropdown" style="width:200px; margin-bottom: 55px !important;"><select class="form-control service" style="width: 100%;" name="service['+i+']" id="service'+i+'" onchange="optionCheckBilling(this);"><option value="0" >Select Service</option></select><div id="form_service"></div></div></div>';
				            $b += '<div class="td"><div class="input-group mb-md" style="width:100%"><textarea class="form-control" name="invoice_description['+i+']"  id="invoice_description" rows="3" style="width:100%">'+client_info[i]["invoice_description"]+'</textarea></div></div>';
				            $b += '<div class="td"><div class="input-dropdown"><select class="form-control" style="text-align:right;width: 100%;" name="currency['+i+']" id="currency"><option value="0" >Select Currency</option></select></div><br/><div class="input-group" style="width:100%;"><input type="text" name="amount['+i+']" class="numberdes form-control amount" value="'+ addCommas(client_info[i]["amount"])+'" id="amount" style="width:100%;text-align:right;"/></div></div>';
				            $b += '<div class="td"><div class="input-dropdown"><select class="form-control" style="width: 100%;" name="unit_pricing['+i+']" id="unit_pricing"><option value="0" >Select Unit Pricing</option></select></div></div>';
				            $b += '<div class="td"><div class="input-dropdown"><select class="form-control" style="width: 100%;" name="servicing_firm['+i+']" id="servicing_firm"><option value="0" >Select Servicing Firm</option></select></div></div>';
				            //$a += '<div class="td"><div class="div_billing_cycle"><div>Start Date: </div><div class="from_billing_cycle_div mb-md"><div class="input-group" style="width: 100%" id="from_billing_cycle_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker from_billing_cycle_datepicker" id="from_billing_cycle" name="from_billing_cycle['+i+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value="'+client_info[i]["from_billing_cycle"]+'"></div><div id="form_from_billing_cycle"></div></div><div class="mb-md"><div>End Date: </div><div class="to_billing_cycle_div mb-md"><div class="input-group" style="width: 100%" id="to_billing_cycle_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker to_billing_cycle_datepicker" id="to_billing_cycle" name="to_billing_cycle['+i+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value="'+client_info[i]["to_billing_cycle"]+'"></div><div id="form_to_billing_cycle"></div></div></div></div></div>';
				            $b += '<div class="td action"><label class="switch"><input name="deactive_switch" class="deactive_switch" type="checkbox" '+((client_info[i]["deactive"] == 1)?"checked":"")+'><span class="slider round"></span></label><input type="hidden" class="hidden_deactive_switch" name="hidden_deactive_switch['+i+']" value="'+client_info[i]["deactive"]+'"/></div>';
				            $b += '<div class="td action"><button type="button" class="btn btn-primary" onclick="delete_billing_info(this);">Delete</button></div></div>';
				            $b += '</div>';

				            $("#body_"+client_info[i]['category_description'].replace(/[\. ,:-]+/g, '')+"_info").append($b);

				            if(client_info[i]["frequency_name"] == "Non-recurring")
				            {
				                $("#form"+i+" .div_recurring").hide();
				                $("#form"+i+" #to").attr('disabled', 'disabled');
				                $("#form"+i+" #from").attr('disabled', 'disabled');
				            }
				            else
				            {
				                $("#form"+i+" .div_recurring").show();
				                $("#form"+i+" #to").attr('disabled', false);
				                $("#form"+i+" #from").attr('disabled', false);
				            }

				            $('.from_datepicker').datepicker({ 
				                dateFormat:'dd/mm/yyyy',
				                autoclose: true,
				            })
				            .on('changeDate', function (selected) {
				                var startDate = new Date(selected.date.valueOf());
				                $(this).parent().parent().parent().parent().find('.to_datepicker').datepicker('setStartDate', startDate);
				                var num = $(this).parent().parent().parent().parent().parent().attr("num");
				                $('#billing_form').formValidation('revalidateField', 'from['+num+']');
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
				                //$('#setup_form').formValidation('revalidateField', 'to['+num+']');
				            }).on('clearDate', function (selected) {
				               $(this).parent().parent().parent().parent().find('.from_datepicker').datepicker('setEndDate', null);
				            });

				            $('.from_billing_cycle_datepicker').datepicker({ 
				                dateFormat:'dd/mm/yyyy',
				                autoclose: true,
				            })
				            .on('changeDate', function (selected) {
				                var startDate = new Date(selected.date.valueOf());
				                $(this).parent().parent().parent().parent().find('.to_billing_cycle_datepicker').datepicker('setStartDate', startDate);

				                var num = $(this).parent().parent().parent().parent().attr("num");
				                $('#billing_form').formValidation('revalidateField', 'from['+num+']');
				            }).on('clearDate', function (selected) {
				                $(this).parent().parent().parent().parent().find('.to_billing_cycle_datepicker').datepicker('setStartDate', null);
				            });

				            $('.to_billing_cycle_datepicker').datepicker({ 
				                dateFormat:'dd/mm/yyyy',
				                autoclose: true,
				            }).on('changeDate', function (selected) {

				                var endDate = new Date(selected.date.valueOf());
				                $(this).parent().parent().parent().parent().find('.from_billing_cycle_datepicker').datepicker('setEndDate', endDate);

				                var num = $(this).parent().parent().parent().parent().parent().attr("num");
				                //$('#setup_form').formValidation('revalidateField', 'to['+num+']');
				            }).on('clearDate', function (selected) {
				               $(this).parent().parent().parent().parent().find('.from_billing_cycle_datepicker').datepicker('setEndDate', null);
				            });
				            
				            if(numberForRetrieve == 1)
				            {
				            	!function (i) {
					                $.ajax({
					                    type: "POST",
					                    url: "masterclient/get_billing_info_service",
					                    data: {"company_code": company_code, "service": client_info[i]["service"]},//, 'is_template': change_template
					                    dataType: "json",
					                    async: false,
					                    success: function(data){
					                        if(data.tp == 1){
					                        	localStorage.setItem("billing_info_service", JSON.stringify(data));
					                        }
					                        else{
					                            alert(data.msg);
					                        }  
					                    }               
					                });
					            } (i);

					            !function (i) {
					                $.ajax({
					                    type: "GET",
					                    url: "masterclient/get_servicing_firm",
					                    async:false,
					                    dataType: "json",
					                    success: function(data){
					                        if(data.tp == 1){
					                            localStorage.setItem("billing_servicing_firm", JSON.stringify(data['result']));
					                        }
					                        else{
					                            alert(data.msg);
					                        }  
					                    }               
					                });
					            }(i);

					            numberForRetrieve = numberForRetrieve + 1;
				            }

				            $("#form"+i+" #service"+i).find("option:eq(0)").html("Select Service");
				            var info_list = JSON.parse(localStorage.getItem("billing_info_service"));
				            var category_description = '';
                            var optgroup = '';

                            for(var t = 0; t < info_list.selected_billing_info_service_category.length; t++)
                            {
                            	if(parseInt(info_list.selected_billing_info_service_category[t]['id']) == service_category_id)
                            	{
	                                if(category_description != info_list.selected_billing_info_service_category[t]['category_description'])
	                                {
	                                    if(optgroup != '')
	                                    {
	                                        $("#form"+i+" #service"+i).append(optgroup);
	                                    }
	                                    optgroup = $('<optgroup label="' + info_list.selected_billing_info_service_category[t]['category_description'] + '" />');
	                                }

	                                category_description = info_list.selected_billing_info_service_category[t]['category_description'];

	                                for(var h = 0; h < info_list.result.length; h++)
	                                {
	                                    if(category_description == info_list.result[h]['category_description'])
	                                    {
	                                        var option = $('<option />');
	                                        option.attr('data-description', info_list.result[h]['invoice_description']).attr('data-currency', info_list.result[h]['currency']).attr('data-unit_pricing', info_list.result[h]['unit_pricing']).attr('data-amount', info_list.result[h]['amount']).attr('value', info_list.result[h]['id']).text(info_list.result[h]['service_name']).appendTo(optgroup);

	                                        if(info_list.selected_service != null && info_list.result[h]['id'] == client_info[i]["service"])
	                                        {
	                                            option.attr('selected', 'selected');
	                                        }
	                                    }
	                                }
	                            }
                            }

                            $("#form"+i+" #service"+i).append(optgroup);

                            $("#form"+i+" #service"+i).select2({
                                formatNoMatches: function () {
                                    return "No Result. <a href='our_firm/edit/"+info_list.firm_id+"' onclick='open_new_tab("+info_list.firm_id+")' target='_blank'>Click here to add Service</a>"
                                },
                                width: '200px'
                            });

				            var billing_currency_list = JSON.parse(localStorage.getItem("billing_currency"));

				            $.each(billing_currency_list, function(key, val) {
                                var option = $('<option />');
                                option.attr('value', key).text(val);
                                if(client_info[i]["currency"] != null && key == client_info[i]["currency"])
                                {
                                    option.attr('selected', 'selected');
                                }
                                $("#form"+i+" #currency").append(option);
                            });

				            $.each(JSON.parse(localStorage.getItem("billing_unit_pricing")), function(key, val) {
                                var option = $('<option />');
                                option.attr('value', key).text(val);
                                if(client_info[i]["unit_pricing"] != null && key == client_info[i]["unit_pricing"])
                                {
                                    option.attr('selected', 'selected');
                                }
                                
                                $("#form"+i+" #unit_pricing").append(option);
                            });

				            $.each(JSON.parse(localStorage.getItem("billing_servicing_firm")), function(key, val) {
                                var option = $('<option />');
                                option.attr('value', key).text(val);
                                if(client_info[i]["servicing_firm"] != null && key == client_info[i]["servicing_firm"])
                                {
                                    option.attr('selected', 'selected');
                                }
                                
                                $("#form"+i+" #servicing_firm").append(option);
                            });
				            
				            $('#billing_form').formValidation('addField', 'service['+i+']', serviceValidators);
				            $('#billing_form').formValidation('addField', 'invoice_description['+i+']', invoiceDescriptionValidators);
				            $('#billing_form').formValidation('addField', 'amount['+i+']', amountValidators);
				            $('#billing_form').formValidation('addField', 'currency['+i+']', currencyValidators);
				            $('#billing_form').formValidation('addField', 'unit_pricing['+i+']', unitPricingValidators);
				        }
			        }
        		}
        	}
        }
    });
}

$(document).on('change',"[name='deactive_switch']",function() {
	var checkbox = $(this);
	var checked = this.checked;
	var client_billing_info_id = $(this).parent().parent().parent().find(".client_billing_info_id").val();
	var company_code = $(this).parent().parent().parent().find(".company_code").val();
	var hidden_deactive_switch = $(this).parent().parent().find(".hidden_deactive_switch");

	if(checked == false)
	{
		var confirmDeactivate = "Do you wanna Activate this service engagement?"
	}
	else
	{
		var confirmDeactivate = "Do you wanna Deactivate this service engagement?"
	}
	bootbox.confirm(confirmDeactivate, function (result) {
        if (result) 
        {
        	if(checked == false)
        	{
        		hidden_deactive_switch.val(0);
        	}
        	else
        	{
        		hidden_deactive_switch.val(1);
        	}
		}
		else
		{
			if(checked)
			{
				checkbox.prop('checked', false);
			}
			else
			{
				checkbox.prop('checked', true);
			}
		}
	});
});

$(document).on('change','.service',function(e){
    var num = $(this).parent().parent().parent().attr("num");
    var descriptionValue = $(this).find(':selected').data('description');
    var amountValue = $(this).find(':selected').data('amount');
    var currencyValue = $(this).find(':selected').data('currency');
    var unit_pricingValue = $(this).find(':selected').data('unit_pricing');
    console.log(descriptionValue);
    $(this).parent().parent().parent().find('#invoice_description').val(descriptionValue);
    if(amountValue != undefined)
    {
    	$(this).parent().parent().parent().find('#amount').val(addCommas(amountValue));
    }
    else
    {
    	$(this).parent().parent().parent().find('#amount').val(addCommas("0.00"));
    }
    $(this).parent().parent().parent().find('#currency').val(currencyValue);
    $(this).parent().parent().parent().find('#unit_pricing').val(unit_pricingValue);

    $('#billing_form').formValidation('revalidateField', 'invoice_description['+num+']');
    $('#billing_form').formValidation('revalidateField', 'amount['+num+']');
    $('#billing_form').formValidation('revalidateField', 'currency['+num+']');
    $('#billing_form').formValidation('revalidateField', 'unit_pricing['+num+']');
});

function optionCheckBilling(billing_element) {
    
    var tr = jQuery(billing_element).parent().parent();

    var input_num = tr.parent().attr("num");

    jQuery(this).find("input").val('');

    if(tr.find('select[name="service['+input_num+']"]').val() == "1")
    {
        tr.parent().find('select[name="frequency['+input_num+']"]').val("4");
        $("#form"+input_num+" .div_recurring").show();
        tr.parent().find("input").attr('disabled', false);
    }
    else if(tr.find('select[name="service['+input_num+']"]').val() == "2")
    {
        tr.parent().find('select[name="frequency['+input_num+']"]').val("5");
        $("#form"+input_num+" .div_recurring").show();
        tr.parent().find("input").attr('disabled', false);
    }
    else if(tr.find('select[name="service['+input_num+']"]').val() == "0")
    {
        tr.parent().find("input").attr('disabled', false);
        $("#form"+input_num+" .div_recurring").show();
        tr.parent().find("select").val('0');

    }
    else
    {
        tr.parent().find('select[name="frequency['+input_num+']"]').val("1");

        $("#form"+input_num+" .div_recurring").hide();

        tr.parent().find('input[name="from['+input_num+']"]').attr('disabled', 'disabled');
        tr.parent().find('input[name="to['+input_num+']"]').attr('disabled', 'disabled');

        tr.parent().find('input[name="from['+input_num+']"]').val("");
        tr.parent().find('input[name="to['+input_num+']"]').val("");

        tr.parent().find('.from_div').removeClass("has-error");
        tr.parent().find('.from_div').removeClass("has-success");
        tr.parent().find('.from_div .help-block').hide();
    }

    //Prevent Multiple Selections of Same Value
    var selected_value = tr.find('select[name="service['+input_num+']"]').val();

    $("select.service option").attr("disabled",false); //enable everything
}

if(client_billing_info)
{   
    var array_length = client_billing_info.length - 1;
    if(client_billing_info[array_length]["client_billing_info_id"] != undefined)
    {
    	$count_billing_info = parseInt(client_billing_info[array_length]["client_billing_info_id"]) + 1;
    }
    else
    {
    	$count_billing_info = 1;
    }
}
else
{
    $count_billing_info = 1;
}
$(document).on('click',".billing_info_Add",function() {
    
    var check_service_category_id = this.getAttribute("data-service_category_id");

	var service_category_name = service_category_list[check_service_category_id].replace(/[\. ,:-]+/g, '');

	$("." + service_category_name + "_empty_row").remove();

    $a=""; 
    $a += '<div class="tr editing" style="border-bottom: 2px solid #dddddd;" method="post" name="form'+$count_billing_info+'" id="form'+$count_billing_info+'" num="'+$count_billing_info+'">';
    $a += '<div class="hidden"><input type="text" class="form-control company_code" name="company_code" value="'+company_code+'"/></div>';
    $a += '<div class="hidden"><input type="text" class="form-control client_billing_info_id" name="client_billing_info_id['+$count_billing_info+']" id="client_billing_info_id" value="'+$count_billing_info+'"/></div>';
    $a += '<div class="td"><div class="input-dropdown" style="width:200px; margin-bottom: 55px !important;"><select class="form-control service" style="width: 100%;" name="service['+$count_billing_info+']" id="service'+$count_billing_info+'" onchange="optionCheckBilling(this);"><option value="0" >Select Service</option></select></div></div>';
    $a += '<div class="td"><div class="input-group mb-md" style="width:100%"><textarea class="form-control" name="invoice_description['+$count_billing_info+']"  id="invoice_description" rows="5" style="width:100%"></textarea></div></div>';
    $a += '<div class="td"><div class="input-dropdown"><select class="form-control currency" style="text-align:right;width: 100%;" name="currency['+$count_billing_info+']" id="currency"><option value="0" >Select Currency</option></select></div><br/><div class="input-group" style="width:100%;"><input type="text" name="amount['+$count_billing_info+']" class="numberdes form-control amount" value="" id="amount" style="width:100%;text-align:right;"/></div></div>';
    $a += '<div class="td"><div class="input-dropdown" style="width:100%"><select class="form-control" style="width: 100%;" name="unit_pricing['+$count_billing_info+']" id="unit_pricing"><option value="0" >Select Unit Pricing</option></select></div></div>';
    $a += '<div class="td"><div class="input-dropdown"><select class="form-control" style="width: 100%;" name="servicing_firm['+$count_billing_info+']" id="servicing_firm"><option value="0" >Select Servicing Firm</option></select></div></div>';
    //$a += '<div class="td"><div class="div_billing_cycle"><div>Start Date: </div><div class="from_billing_cycle_div mb-md"><div class="input-group" style="width: 100%" id="from_billing_cycle_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker from_billing_cycle_datepicker" id="from_billing_cycle" name="from_billing_cycle['+$count_billing_info+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div><div id="form_from_billing_cycle"></div></div><div class="mb-md"><div>End Date: </div><div class="to_billing_cycle_div mb-md"><div class="input-group" style="width: 100%" id="to_billing_cycle_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker to_billing_cycle_datepicker" id="to_billing_cycle" name="to_billing_cycle['+$count_billing_info+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div><div id="form_to_billing_cycle"></div></div></div></div></div>';
    $a += '<div class="td action"><label class="switch"><input name="deactive_switch" class="deactive_switch" type="checkbox"><span class="slider round"></span></label><input type="hidden" class="hidden_deactive_switch" name="hidden_deactive_switch['+$count_billing_info+']" value="0"/></div>';
    $a += '<div class="td action"><button type="button" class="btn btn-primary" onclick="delete_billing_info(this);">Delete</button></div></div>';
    $a += '</div>';

    $("#body_"+service_category_name+"_info").prepend($a); 

    !function ($count_billing_info) {
    	$('#loadingmessage').show();
        $.ajax({
            type: "POST",
            url: "masterclient/get_billing_info_service",
            data: {"company_code": $("#w2-billing .company_code").val()},
            dataType: "json",
            //async: false,
            success: function(data){
                if(data.tp == 1){
                    var category_description = '';
                    var optgroup = '';
                    for(var t = 0; t < data.selected_billing_info_service_category.length; t++)
                    {
                    	if(parseInt(data.selected_billing_info_service_category[t]['id']) == check_service_category_id)
	                    {
	                        if(category_description != data.selected_billing_info_service_category[t]['category_description'])
	                        {
	                            if(optgroup != '')
	                            {
	                                $("#form"+$count_billing_info+" #service"+$count_billing_info).append(optgroup);
	                            }
	                            optgroup = $('<optgroup label="' + data.selected_billing_info_service_category[t]['category_description'] + '" />');
	                        }

	                        category_description = data.selected_billing_info_service_category[t]['category_description'];

	                        for(var h = 0; h < data.result.length; h++)
	                        {
	                            if(category_description == data.result[h]['category_description'])
	                            {
	                                var option = $('<option />');
	                                option.attr('data-description', data.result[h]['invoice_description']).attr('data-currency', data.result[h]['currency']).attr('data-unit_pricing', data.result[h]['unit_pricing']).attr('data-amount', data.result[h]['amount']).attr('value', data.result[h]['id']).text(data.result[h]['service_name']).appendTo(optgroup);
	                            }
	                        }
	                    }
                    }
                    $("#form"+$count_billing_info+" #service"+$count_billing_info).append(optgroup);
                    $("#form"+$count_billing_info+" #service"+$count_billing_info).select2({
                        formatNoMatches: function () {
                            return "No Result. <a href='our_firm/edit/"+data.firm_id+"' onclick='open_new_tab("+data.firm_id+")' target='_blank'>Click here to add Service</a>"
                         },
                         width: '200px'
                    });

                    $('#loadingmessage').hide();
                }
                else{
                    alert(data.msg);
                }  
            }               
        });
    }($count_billing_info);

    var billing_currency_list = JSON.parse(localStorage.getItem("billing_currency"));

    $.each(billing_currency_list, function(key, val) {
        var option = $('<option />');
        option.attr('value', key).text(val);
                        
        $("#form"+$count_billing_info+" #currency").append(option);
    });

    $.each(JSON.parse(localStorage.getItem("billing_unit_pricing")), function(key, val) {
        var option = $('<option />');
        option.attr('value', key).text(val);
                        
        $("#form"+$count_billing_info+" #unit_pricing").append(option);
    });

    // !function ($count_billing_info) {
    //     $.ajax({
    //         type: "GET",
    //         url: "masterclient/get_currency",
    //         dataType: "json",
    //         success: function(data){
    //             if(data.tp == 1){
    //                 $.each(data['result'], function(key, val) {
    //                     var option = $('<option />');
    //                     option.attr('value', key).text(val);
                        
    //                     $("#form"+$count_billing_info+" #currency").append(option);
    //                 });
    //             }
    //             else{
    //                 alert(data.msg);
    //             }  
    //         }               
    //     });
    // }($count_billing_info);

    !function ($count_billing_info) {
        $.ajax({
            type: "GET",
            url: "masterclient/get_servicing_firm",
            dataType: "json",
            success: function(data){
                if(data.tp == 1){
                    $.each(data['result'], function(key, val) {
                        var option = $('<option />');
                        option.attr('value', key).text(val);
                        
                        $("#form"+$count_billing_info+" #servicing_firm").append(option);
                    });
                }
                else{
                    alert(data.msg);
                }  
            }               
        });
    }($count_billing_info);

    // !function ($count_billing_info) {
    //     $.ajax({
    //         type: "GET",
    //         url: "masterclient/get_unit_pricing",
    //         dataType: "json",
    //         success: function(data){
    //             if(data.tp == 1){
    //                 $.each(data['result'], function(key, val) {
    //                     var option = $('<option />');
    //                     option.attr('value', key).text(val);
                        
    //                     $("#form"+$count_billing_info+" #unit_pricing").append(option);
    //                 });
    //                 $('#loadingmessage').hide();
    //             }
    //             else{
    //                 alert(data.msg);
    //             }  
    //         }               
    //     });
    // }($count_billing_info);

    $('#billing_form').formValidation('addField', 'service['+$count_billing_info+']', serviceValidators);
    $('#billing_form').formValidation('addField', 'invoice_description['+$count_billing_info+']', invoiceDescriptionValidators);
    $('#billing_form').formValidation('addField', 'amount['+$count_billing_info+']', amountValidators);
    $('#billing_form').formValidation('addField', 'currency['+$count_billing_info+']', currencyValidators);
    $('#billing_form').formValidation('addField', 'unit_pricing['+$count_billing_info+']', unitPricingValidators);

    $count_billing_info++;
});

function delete_billing_info(element)
{
    var tr = jQuery(element).parent().parent();

    var client_billing_info_id = tr.find('.client_billing_info_id').val();
    var company_code = tr.find('.company_code').val();

    if(client_billing_info_id != "")
    {
        $.ajax({ //Upload common input
            url: "masterclient/delete_client_billing_info",
            type: "POST",
            data: {"client_billing_info_id": client_billing_info_id, "company_code": company_code},
            dataType: 'json',
            success: function (response) {
                if(response.Status == 1)
                {
                    array_client_billing_info_id.push(client_billing_info_id);
                    //toastr.success("Updated Information", "Success");
                    tr.remove();

                    $("select.service option").attr("disabled",false); //enable everything
                }
                else
                {
                    toastr.error("Cannot be delete. This service is use in billing.", "Error");
                }
            }
        });
    }
}