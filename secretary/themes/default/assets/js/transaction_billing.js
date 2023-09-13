var billing_info_coll, currency_info, array_client_billing_info_id = [], selected_descriptionValue, 
selected_amountValue, selected_currencyValue, selected_unit_pricingValue, selected, selected_row, $count_billing_info = 1, numberForRetrieve = 1;

$(document).on('click',"#billing_info_Add",function() {
    add_service_engagment_row();
});

function add_service_engagment_row(service_engagement_id = null)
{
    billing_info_coll = document.getElementsByClassName("row_billing");

    if(service_engagement_id != null)
    {
        $('.billing_service :selected').each(function(){
            selected = $(this).val();
            selected_row = $(this);
            if($('.transaction_task option:selected').val() == 1 || $('.transaction_task option:selected').val() == 4)
            {
                $.each(registered_address_info, function(key, val) {
                    if(val['our_service_info_id'] == selected)
                    {
                        selected_row.parent().parent().parent().parent().parent().remove();
                    }
                });
            }
            else if($('.transaction_task option:selected').val() == 33)
            {
                if((selected == "102" && service_engagement_id == selected) || (selected == "128" && service_engagement_id == selected))
                {
                    selected_row.parent().parent().parent().parent().parent().remove();
                    array_client_billing_info_id.push(selected_row.parent().parent().parent().parent().parent().find('#client_billing_info_id').val());
                }
            }
        });
    }

    if(billing_info_coll.length > 0 && latest_client_billing_info_id != 0)
    {
        $count_billing_info = parseInt(latest_client_billing_info_id) + 1; //billing_info_coll.length + 1;
        latest_client_billing_info_id = $count_billing_info;
    }
    else
    {
        $count_billing_info = 1;
        latest_client_billing_info_id = 1;
    }

    $a=""; 
    $a += '<tr num="'+$count_billing_info+'" class="row_billing">';
    $a += '<td><div style="margin-bottom: 35px !important;"><select class="form-control billing_service" style="width: 100%;" name="service[]" id="service'+$count_billing_info+'" onchange="optionCheckBilling(this);"><option value="0" >Select Service</option></select><div id="form_service"></div></div></td>';
    $a += '<td><div class="mb-md"><textarea class="form-control invoice_description" name="invoice_description['+$count_billing_info+']"  id="invoice_description" rows="3" style="width:250px"></textarea></div><div class="hidden"><input type="text" class="form-control" name="client_billing_info_id['+$count_billing_info+']" id="client_billing_info_id" value="'+$count_billing_info+'"/></div></td>';
    $a += '<td><select class="form-control currency" style="text-align:right;width: 100%;" name="currency['+$count_billing_info+']" id="service_currency'+$count_billing_info+'"><option value="0" >Select Currency</option></select><div id="form_currency"></div><br/><input type="text" name="amount['+$count_billing_info+']" class="numberdes form-control amount" value="" id="amount" style="width:100%;text-align:right;"/><div id="form_amount"></div></td>';
    $a += '<td><select class="form-control unit_pricing" style="width: 100%;" name="unit_pricing['+$count_billing_info+']" id="unit_pricing'+$count_billing_info+'"><option value="0" >Select Unit Pricing</option></select><div id="form_unit_pricing"></div></td>';
    $a += '<td><select class="form-control servicing_firm" style="width: 100%;" name="servicing_firm['+$count_billing_info+']" id="servicing_firm'+$count_billing_info+'"><option value="0" >Select Servicing Firm</option></select><div id="form_servicing_firm"></div></td>';
    $a += '<td><div class="action"><button type="button" class="btn btn-primary" onclick="delete_billing_info(this);">Delete</button></div></td>';
    $a += "</tr>";

    $("#body_billing_info").prepend($a); 
    console.log(numberForRetrieve);
    if(numberForRetrieve == 1)
    {
        !function ($count_billing_info) {
            $.ajax({
                type: "POST",
                url: "transaction/get_billing_info_service",
                data: {"company_code": transaction_company_code},
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
        }($count_billing_info);

        console.log("0");
        !function ($count_billing_info) {
            console.log("1");
            $.ajax({
                type: "GET",
                url: "masterclient/get_billing_info_frequency",
                dataType: "json",
                async: false,
                success: function(data){
                    console.log(data);
                    if(data.tp == 1){
                        console.log(data['result']);
                        localStorage.setItem("billing_info_frequency", JSON.stringify(data['result']));
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
        }($count_billing_info);

        !function ($count_billing_info) {
            $.ajax({
                type: "GET",
                url: "masterclient/get_servicing_firm",
                dataType: "json",
                async: false,
                success: function(data){
                    if(data.tp == 1){
                        localStorage.setItem("billing_servicing_firm", JSON.stringify(data['result']));
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
                url: "masterclient/get_unit_pricing",
                dataType: "json",
                async: false,
                success: function(data){
                    if(data.tp == 1){
                        localStorage.setItem("billing_unit_pricing", JSON.stringify(data['result']));
                    }
                    else{
                        alert(data.msg);
                    }  
                }               
            });
        }($count_billing_info);

        numberForRetrieve = numberForRetrieve + 1;
    }

    var info_list = JSON.parse(localStorage.getItem("billing_info_service"));
    var category_description = '';
    var optgroup = '';
    for(var t = 0; t < info_list.selected_billing_info_service_category.length; t++)
    {
        if(category_description != info_list.selected_billing_info_service_category[t]['category_description'])
        {
            if(optgroup != '')
            {
                $("#service"+$count_billing_info).append(optgroup);
            }
            optgroup = $('<optgroup label="' + info_list.selected_billing_info_service_category[t]['category_description'] + '" />');
        }

        category_description = info_list.selected_billing_info_service_category[t]['category_description'];

        for(var h = 0; h < info_list.result.length; h++)
        {
            if(category_description == info_list.result[h]['category_description'])
            {
                var option = $('<option />');
                option.attr('data-description', info_list.result[h]['invoice_description']).attr('data-currency', info_list.result[h]['currency']).attr('data-unit_pricing', info_list.result[h]['unit_pricing']).attr('data-amount', info_list.result[h]['amount']).attr('value', info_list.result[h]['id']).text(info_list.result[h]['service_name']);
                if(info_list.result[h]['deleted'] == 0)
                {
                    option.appendTo(optgroup);
                }
                if(service_engagement_id != null && info_list.result[h]['id'] == service_engagement_id)
                {
                    if(info_list.result[h]['deleted'] == 1)
                    {
                        option.appendTo(optgroup);
                    }
                    option.attr('selected', 'selected');

                    selected_descriptionValue = info_list.result[h]['invoice_description'];
                    selected_amountValue = info_list.result[h]['amount'];
                    selected_currencyValue = info_list.result[h]['currency'];
                    selected_unit_pricingValue = info_list.result[h]['unit_pricing'];

                    $("#service"+$count_billing_info).parent().parent().parent().find('#invoice_description').text(selected_descriptionValue);
                    $("#service"+$count_billing_info).parent().parent().parent().find('#amount').val(addCommas(selected_amountValue));
                }
            }
        }
    }
    $("#service"+$count_billing_info).append(optgroup);
    $("#service"+$count_billing_info).select2({
        formatNoMatches: function () {
            return "No Result. <a href='our_firm/edit/"+info_list.firm_id+"' onclick='open_new_tab("+info_list.firm_id+")' target='_blank'>Click here to add Service</a>"
        },
        width: '250px'
    });

    $.each(JSON.parse(localStorage.getItem("billing_unit_pricing")), function(key, val) {
        var option = $('<option />');
        option.attr('value', key).text(val);
        if(service_engagement_id != null)
        {
            if(selected_unit_pricingValue != null && key == selected_unit_pricingValue)
            {
                option.attr('selected', 'selected');
            }
        }
        $("#unit_pricing"+$count_billing_info).append(option);
    });

    $.each(JSON.parse(localStorage.getItem("billing_currency")), function(key, val) {
        var option = $('<option />');
        option.attr('value', key).text(val);
        
        if(service_engagement_id != null)
        {
            if(selected_currencyValue != null && key == selected_currencyValue)
            {
                option.attr('selected', 'selected');
            }
        }

        $("#service_currency"+$count_billing_info).append(option);
    });

    $.each(JSON.parse(localStorage.getItem("billing_servicing_firm")), function(key, val) {
        var option = $('<option />');
        option.attr('value', key).text(val);
        
        $("#servicing_firm"+$count_billing_info).append(option);
    });

    // $.each(JSON.parse(localStorage.getItem("billing_info_frequency")), function(key, val) {
    //     var option = $('<option />');
    //     option.attr('value', key).text(val);
        
    //     $("#frequency"+$count_billing_info).append(option);
    // });

    $count_billing_info++;
}

$(document).on('change','.billing_service',function(e){
    var descriptionValue = $(this).find(':selected').data('description');
    var amountValue = $(this).find(':selected').data('amount');
    var currencyValue = $(this).find(':selected').data('currency');
    var unit_pricingValue = $(this).find(':selected').data('unit_pricing');

    $(this).parent().parent().parent().find('#invoice_description').text(descriptionValue);
    $(this).parent().parent().parent().find('#amount').val(addCommas(amountValue));
    $(this).parent().parent().parent().find('.currency').val(currencyValue);
    $(this).parent().parent().parent().find('.unit_pricing').val(unit_pricingValue);
});

function open_new_tab(firm_id)
{
    window.open ('our_services','_blank');
}

function optionCheckBilling(billing_element) {
    
    var tr = jQuery(billing_element).parent().parent();

    var input_num = tr.parent().attr("num");

    jQuery(this).find("input").val('');

    if(tr.find('select[name="service['+input_num+']"]').val() == "1")
    {
        tr.parent().find('select[name="frequency['+input_num+']"]').val("4");
        $(".div_recurring").show();
        tr.parent().find("input").attr('disabled', false);

    }
    else if(tr.find('select[name="service['+input_num+']"]').val() == "2")
    {
        tr.parent().find('select[name="frequency['+input_num+']"]').val("5");
        $(".div_recurring").show();
        tr.parent().find("input").attr('disabled', false);

    }
    else if(tr.find('select[name="service['+input_num+']"]').val() == "0")
    {
        tr.parent().find("input").attr('disabled', false);
        $(".div_recurring").show();
        tr.parent().find("select").val('0');
    }
    else
    {
        tr.parent().find('select[name="frequency['+input_num+']"]').val("1");

        $(".div_recurring").hide();

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

    $("select.billing_service option").attr("disabled",false); //enable everything
 
     //collect the values from selected;
    var arr = $.map
    (
        $("select.billing_service option:selected"), function(n)
        {
            return n.value;
        }
    );

    $("select.billing_service").each(function() {

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


    });
}

function submit_billing_data()
{
    var formBillingData = new FormData($('form#billing_form')[0]);

    formBillingData.append('transaction_code' , $('#transaction_code').val());
    formBillingData.append('transaction_task_id' , $('#transaction_task').val());
    formBillingData.append('array_client_billing_info_id' , JSON.stringify(array_client_billing_info_id));
    formBillingData.append('transaction_master_id' , $("#transaction_trans #transaction_master_id").val());

    $('#loadingmessage').show();
    $.ajax({ //Upload common input
      url: "transaction/add_client_billing_info",
      type: "POST",
      //data: $('form#billing_form').serialize()+ '&transaction_code=' + $('#transaction_code').val() + '&transaction_task_id=' + $('#transaction_task').val() + '&array_client_billing_info_id=' + JSON.stringify(array_client_billing_info_id),
      data: formBillingData,
      dataType: 'json',
      contentType: false,
      processData: false, 
      success: function (response,data) {
            $('#loadingmessage').hide();
            if (response.Status === 1) 
            {
                toastr.success(response.message, response.title);
                $("#body_billing_info .row_billing").remove();
                billingInterface(response.transaction_client_billing);
            }
        }
    });
}

$(document).on('click',"#submitBillingInfo",function(e){
    submit_billing_data();
});

function delete_billing_info(element)
{
    var tr = jQuery(element).parent().parent().parent();

    var client_billing_info_id = tr.find('#client_billing_info_id').val();

    
    if(client_billing_info_id != undefined)
    {
        $('#loadingmessage').show();
        $.ajax({ //Upload common input
            url: "transaction/delete_billing",
            type: "POST",
            data: {"client_billing_info_id": client_billing_info_id, "transaction_company_code": transaction_company_code, "transaction_master_id": $("#transaction_trans #transaction_master_id").val()},
            dataType: 'json',
            success: function (response) {
                $('#loadingmessage').hide();
                if(response.Status == 1)
                {
                    array_client_billing_info_id.push(client_billing_info_id);
                    tr.remove();
                    toastr.success("Updated Information", "Success");
                    
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

                    });
                }
                else
                {
                    toastr.error("Cannot be delete. This service is use in billing.", "Error");
                }
            }
        });
    }
}