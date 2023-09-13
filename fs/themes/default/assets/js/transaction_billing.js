var billing_info_coll;


$(document).on('click',"#billing_info_Add",function() {
    billing_info_coll = document.getElementsByClassName("row_billing");

    if(billing_info_coll.length > 0)
    {
        $count_billing_info = billing_info_coll.length + 1;
    }
    else
    {
        $count_billing_info = 1;
    }
    //console.log($count_billing_info);

    // $a=""; 
    // $a += '<tr num="'+$count_billing_info+'" class="row_billing">';
    // $a += '<td><div style="margin-bottom: 35px !important;"><select class="form-control billing_service" style="text-align:right;width: 100%;" name="service['+$count_billing_info+']" id="service'+$count_billing_info+'" onchange="optionCheckBilling(this);"><option value="0" >Select Service</option></select><div id="form_service"></div></div><div style="font-weight: bold;">Billing Period</div><div class="input-group"><select class="form-control frequency" style="text-align:right;width: 100%;" name="frequency['+$count_billing_info+']" id="frequency'+$count_billing_info+'"><option value="0" >Select Frequency</option></select><div id="form_frequency"></div></div></td>';
    // $a += '<td><div class="mb-md"><textarea class="form-control invoice_description" name="invoice_description['+$count_billing_info+']"  id="invoice_description" rows="3" style="width:290px"></textarea></div><div class="hidden"><input type="text" class="form-control" name="client_billing_info_id['+$count_billing_info+']" id="client_billing_info_id" value="'+$count_billing_info+'"/></div></td>';
    // $a += '<td><input type="text" name="amount['+$count_billing_info+']" class="numberdes form-control amount" value="" id="amount" style="width:100%;text-align:right;"/><div id="form_amount"></div></td>';
    // $a += '<td><div class="div_billing_cycle"><div>Start Date: </div><div class="from_billing_cycle_div mb-md"><div class="input-group" style="width: 100%" id="from_billing_cycle_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker from_billing_cycle_datepicker" id="from_billing_cycle" name="from_billing_cycle['+$count_billing_info+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div><div id="form_from_billing_cycle"></div></div><div class="mb-md"><div>End Date: </div><div class="to_billing_cycle_div mb-md"><div class="input-group" style="width: 100%" id="to_billing_cycle_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker to_billing_cycle_datepicker" id="to_billing_cycle" name="to_billing_cycle['+$count_billing_info+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div><div id="form_to_billing_cycle"></div></div></div></div></td>';
    // $a += '<td><div class="action"><button type="button" class="btn btn-primary" onclick="delete_billing_info(this);">Delete</button></div></td>';
    // $a += "</tr>";

    $a=""; 
    $a += '<tr num="'+$count_billing_info+'" class="row_billing">';
    $a += '<td><div style="margin-bottom: 35px !important;"><select class="form-control billing_service" style="width: 100%;" name="service['+$count_billing_info+']" id="service'+$count_billing_info+'" onchange="optionCheckBilling(this);"><option value="0" >Select Service</option></select><div id="form_service"></div></div></td>';
    $a += '<td><div class="mb-md"><textarea class="form-control invoice_description" name="invoice_description['+$count_billing_info+']"  id="invoice_description" rows="3" style="width:290px"></textarea></div><div class="hidden"><input type="text" class="form-control" name="client_billing_info_id['+$count_billing_info+']" id="client_billing_info_id" value="'+$count_billing_info+'"/></div></td>';
    $a += '<td><select class="form-control currency" style="text-align:right;width: 100%;" name="currency['+$count_billing_info+']" id="currency'+$count_billing_info+'"><option value="0" >Select Currency</option></select><div id="form_currency"></div></td>';
    $a += '<td><input type="text" name="amount['+$count_billing_info+']" class="numberdes form-control amount" value="" id="amount" style="width:100%;text-align:right;"/><div id="form_amount"></div></td>';
    $a += '<td><select class="form-control unit_pricing" style="width: 100%;" name="unit_pricing['+$count_billing_info+']" id="unit_pricing'+$count_billing_info+'"><option value="0" >Select Unit Pricing</option></select><div id="form_unit_pricing"></div></td>';
    $a += '<td><div class="action"><button type="button" class="btn btn-primary" onclick="delete_billing_info(this);">Delete</button></div></td>';
    $a += "</tr>";


    /*<input type="button" value="Save" id="save" name="save'+$count_officer+'" class="btn btn-primary" onclick="save(this);">*/
    $("#body_billing_info").prepend($a); 
    //$('.datepicker').datepicker({ dateFormat:'dd/mm/yyyy'});

    // $('.from_datepicker').datepicker({ 
    //     dateFormat:'dd/mm/yyyy',
    // }).datepicker('setStartDate', latest_incorporation_date);

    // $('.to_datepicker').datepicker({ 
    //     dateFormat:'dd/mm/yyyy',
    // }).datepicker('setStartDate', latest_incorporation_date);

    // $('.from_billing_cycle_datepicker').datepicker({ 
    //     dateFormat:'dd/mm/yyyy',
    // });

    // $('.to_billing_cycle_datepicker').datepicker({ 
    //     dateFormat:'dd/mm/yyyy',
    // });

    // $('.from_datepicker').datepicker({ 
    //     dateFormat:'dd/mm/yyyy',
    //     autoclose: true,
    // })
    // .on('changeDate', function (selected) {
    //     var startDate = new Date(selected.date.valueOf());
    //     $(this).parent().parent().parent().parent().find('.to_datepicker').datepicker('setStartDate', startDate);

    //     var num = $(this).parent().parent().parent().parent().attr("num");
    //     $('#billing_form').formValidation('revalidateField', 'from['+num+']');
    // }).on('clearDate', function (selected) {
    //     $(this).parent().parent().parent().parent().find('.to_datepicker').datepicker('setStartDate', null);
    // });

    // $('.to_datepicker').datepicker({ 
    //     dateFormat:'dd/mm/yyyy',
    //     autoclose: true,
    // }).on('changeDate', function (selected) {

    //     var endDate = new Date(selected.date.valueOf());
    //     $(this).parent().parent().parent().parent().find('.from_datepicker').datepicker('setEndDate', endDate);

    //     var num = $(this).parent().parent().parent().parent().parent().attr("num");
    //     //$('#billing_form').formValidation('revalidateField', 'to['+num+']');
    // }).on('clearDate', function (selected) {
    //    $(this).parent().parent().parent().parent().find('.from_datepicker').datepicker('setEndDate', null);
    // });

    // $('.from_billing_cycle_datepicker').datepicker({ 
    //     dateFormat:'dd/mm/yyyy',
    //     autoclose: true,
    // })
    // .on('changeDate', function (selected) {
    //     var startDate = new Date(selected.date.valueOf());
    //     $(this).parent().parent().parent().parent().find('.to_billing_cycle_datepicker').datepicker('setStartDate', startDate);

    //     var num = $(this).parent().parent().parent().parent().attr("num");
    //     $('#billing_form').formValidation('revalidateField', 'from['+num+']');
    // }).on('clearDate', function (selected) {
    //     $(this).parent().parent().parent().parent().find('.to_billing_cycle_datepicker').datepicker('setStartDate', null);
    // });

    // $('.to_billing_cycle_datepicker').datepicker({ 
    //     dateFormat:'dd/mm/yyyy',
    //     autoclose: true,
    // }).on('changeDate', function (selected) {

    //     var endDate = new Date(selected.date.valueOf());
    //     $(this).parent().parent().parent().parent().find('.from_billing_cycle_datepicker').datepicker('setEndDate', endDate);

    //     var num = $(this).parent().parent().parent().parent().parent().attr("num");
    //     //$('#setup_form').formValidation('revalidateField', 'to['+num+']');
    // }).on('clearDate', function (selected) {
    //    $(this).parent().parent().parent().parent().find('.from_billing_cycle_datepicker').datepicker('setEndDate', null);
    // });

    !function ($count_billing_info) {
        $.ajax({
            type: "POST",
            url: "transaction/get_billing_info_service",
            data: {"company_code": transaction_company_code},
            dataType: "json",
            success: function(data){
                //console.log(data);
                if(data.tp == 1){
                    var category_description = '';
                    var optgroup = '';
                    for(var t = 0; t < data.selected_billing_info_service_category.length; t++)
                    {
                        if(category_description != data.selected_billing_info_service_category[t]['category_description'])
                        {
                            if(optgroup != '')
                            {
                                $("#service"+$count_billing_info).append(optgroup);
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
                    $("#service"+$count_billing_info).append(optgroup);
                    $("#service"+$count_billing_info).select2({
                        formatNoMatches: function () {
                            return "No Result. <a href='our_firm/edit/"+data.firm_id+"' onclick='open_new_tab("+data.firm_id+")' target='_blank'>Click here to add Service</a>"
                        }
                    })
                    
                    var arr = $.map
                    (
                        $("select.billing_service option:selected"), function(n)
                        {
                            return n.value;
                        }
                    );

                    $('select[name="service['+$count_billing_info+']"] option').filter(function()
                    {
                        return $.inArray($(this).val(),arr)>-1;
                     }).attr("disabled","disabled"); 


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
                //console.log(data);
                if(data.tp == 1){
                    $.each(data['result'], function(key, val) {
                        var option = $('<option />');
                        option.attr('value', key).text(val);
                        
                        $("#frequency"+$count_billing_info).append(option);
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
            url: "masterclient/get_currency",
            dataType: "json",
            success: function(data){
                //console.log(data);
                if(data.tp == 1){
                    $.each(data['result'], function(key, val) {
                        var option = $('<option />');
                        option.attr('value', key).text(val);
                        
                        $("#currency"+$count_billing_info).append(option);
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
            url: "masterclient/get_unit_pricing",
            dataType: "json",
            success: function(data){
                //console.log(data);
                if(data.tp == 1){
                    $.each(data['result'], function(key, val) {
                        var option = $('<option />');
                        option.attr('value', key).text(val);
                        
                        $("#unit_pricing"+$count_billing_info).append(option);
                    });
                }
                else{
                    alert(data.msg);
                }  
            }               
        });
    }($count_billing_info);

    
    $count_billing_info++;
});

$(document).on('change','.billing_service',function(e){
    var descriptionValue = $(this).find(':selected').data('description');
    var amountValue = $(this).find(':selected').data('amount');
    var currencyValue = $(this).find(':selected').data('currency');
    var unit_pricingValue = $(this).find(':selected').data('unit_pricing');
    console.log(currencyValue);
    $(this).parent().parent().parent().find('#invoice_description').text(descriptionValue);
    $(this).parent().parent().parent().find('#amount').val(addCommas(amountValue));
    $(this).parent().parent().parent().find('.currency').val(currencyValue);
    $(this).parent().parent().parent().find('.unit_pricing').val(unit_pricingValue);
});

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

$(document).on('click',"#submitBillingInfo",function(e){
    console.log($('form#billing_form').serialize());
    $('#loadingmessage').show();
    $.ajax({ //Upload common input
      url: "transaction/add_client_billing_info",
      type: "POST",
      data: $('form#billing_form').serialize()+ '&transaction_code=' + $('#transaction_code').val() + '&transaction_task_id=' + $('#transaction_task').val(),
      dataType: 'json',
      success: function (response,data) {
        $('#loadingmessage').hide();
          if (response.Status === 1) 
          {
            toastr.success(response.message, response.title);
            $("#body_billing_info .row_billing").remove();
            billingInterface(response.transaction_client_billing);
          }
        }
    })

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
            data: {"client_billing_info_id": client_billing_info_id},
            dataType: 'json',
            success: function (response) {
                $('#loadingmessage').hide();
            }
        });
    }

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

        console.log($('select[name="service['+other_num+']"]').val());
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