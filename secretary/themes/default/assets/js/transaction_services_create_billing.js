$(document).on('click',"#transaction_billing_service_info_Add",function() {
    $count_billing_service_info = count_billing_service_info_num;
    $a=""; 
    $a += '<div class="tr editing tr_billing" method="post" name="form'+$count_billing_service_info+'" id="form'+$count_billing_service_info+'" num="'+$count_billing_service_info+'">';
    $a += '<div class="hidden"><input type="text" class="form-control" name="company_code" value="'+$('.trans_company_code').val()+'"/></div>';
    $a += '<div class="hidden"><input type="text" class="form-control" name="billing_service_id" value=""/></div>';
    $a += '<div class="hidden"><input type="text" class="form-control" name="client_billing_info_id['+$count_billing_service_info+']" id="client_billing_info_id" value=""/></div>';
    $a += '<div class="hidden"><input type="text" class="form-control" name="claim_service_id['+$count_billing_service_info+']" id="claim_service_id" value=""/></div>';
    //$a += '<div class="td"><div class="select-input-group"><select class="input-sm form-control service" name="service['+$count_billing_service_info+']" id="service'+$count_billing_service_info+'" style="width:200px;"><option value="0" data-invoice_description="" data-amount="">Select Service</option></select><input type="hidden" class="form-control" name="payment_voucher_type['+$count_billing_service_info+']" id="payment_voucher_type" value=""/><div id="form_service"></div></div></div>';
    $a += '<div class="td"><div class="select-input-group mb-md"><select class="input-sm form-control service" name="service['+$count_billing_service_info+']" id="service'+$count_billing_service_info+'" style="width:200px;"><option value="0" data-invoice_description="" data-amount="">Select Service</option></select><input type="hidden" class="form-control" name="payment_voucher_type['+$count_billing_service_info+']" id="payment_voucher_type" value=""/></div><div class="input-group mb-md"><div style="font-weight: bold; margin-bottom: 5px;">Progress Billing</div><div class="form-check form-check-inline"><input class="form-check-input progress_billing_yes" type="radio" name="progress_billing_yes_no['+$count_billing_service_info+']" id="progress_billing_yes" value="yes"><label class="form-check-label" for="progress_billing_yes">Yes</label></div><div class="form-check form-check-inline"><input class="form-check-input progress_billing_no" type="radio" name="progress_billing_yes_no['+$count_billing_service_info+']" id="progress_billing_no" value="no" checked><label class="form-check-label" for="progress_billing_no">No</label></div></div><div class="poc_percent_div input-group mb-md" style="display:none"><div class="form-check"><input class="form-control form-check-input poc_percentage" style="width:50px; margin-right:1px;" type="text" name="poc_percentage['+$count_billing_service_info+']" id="poc_percentage" value=""><label class="form-check-label" style="margin-top: 7px;" for="poc_percentage">% of <span class="number_of_percent_poc">0.00</span></label><input type="hidden" class="hidden_number_of_percent_poc" name="hidden_number_of_percent_poc['+$count_billing_service_info+']" value=""></div></div></div>';
    //$a += '<div class="td"><div class="input-group mb-md"><textarea class="form-control" name="invoice_description['+$count_billing_service_info+']"  id="invoice_description" rows="3" style="width:420px"></textarea></div><div style="width: 200px;display: inline-block; margin-right:10px;"><div style="font-weight: bold;">Period Start Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker period_start_date" id="period_start_date" name="period_start_date['+$count_billing_service_info+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div></div><div style="width: 200px;display: inline-block"><div style="font-weight: bold;">Period End Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker period_end_date" id="period_end_date" name="period_end_date['+$count_billing_service_info+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div></div></div>';
    $a += '<div class="td"><div class="input-group mb-md"><textarea class="form-control" name="invoice_description['+$count_billing_service_info+']"  id="invoice_description" rows="3" style="width:420px"></textarea></div><div class="period_class" style="width: 200px;display: inline-block; margin-right:10px;"><div style="font-weight: bold;">Period Start Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"/></span><input type="text" class="form-control datepicker period_start_date" id="period_start_date" name="period_start_date['+$count_billing_service_info+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div></div><div class="period_class" style="width: 200px;display: inline-block"><div style="font-weight: bold;">Period End Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"/></span><input type="text" class="form-control datepicker period_end_date" id="period_end_date" name="period_end_date['+$count_billing_service_info+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div></div><div class="input-group mb-md"><div class="type_reading_quantity_class" style="font-weight: bold; margin-bottom: 5px; display:none;">Type</div><div class="form-check form-check-inline rate_class" style="display:none"><input class="form-check-input radio_reading" type="radio" name="radio_quantity_reading['+$count_billing_service_info+']" id="radio_reading" value="reading" checked><label class="form-check-label" for="radio_reading">Reading</label></div><div class="form-check form-check-inline rate_class" style="display:none"><input class="form-check-input radio_quantity" type="radio" name="radio_quantity_reading['+$count_billing_service_info+']" id="radio_quantity" value="quantity"><label class="form-check-label" for="radio_quantity">Quantity</label></div></div><div class="quantity" style="width: 200px;display: none; margin-right:10px;"><div style="font-weight: bold;">Quantity</div><div class="input-group" style="width: 100%"><input type="text" class="form-control border_radius quantity_value" id="quantity_value" name="quantity_value['+$count_billing_service_info+']" value=""/></div></div><div class="rate_class reading" style="width: 200px;display: none; margin-right:10px;"><div style="font-weight: bold;">Reading at beginning</div><div class="input-group" style="width: 100%"><input type="text" class="form-control border_radius reading_at_begin" id="reading_at_begin" name="reading_at_begin['+$count_billing_service_info+']" value=""/></div></div><div class="rate_class reading" style="width: 200px;display: none"><div style="font-weight: bold;">Reading at the end</div><div class="input-group" style="width: 100%"><input type="text" class="form-control border_radius reading_at_the_end" id="reading_at_the_end" name="reading_at_the_end['+$count_billing_service_info+']" value=""/></div></div><div class="rate_class" style="width: 200px;display: none; margin-right:10px;"><div style="font-weight: bold;">Rate</div><div class="input-group" style="width: 100%"><input type="text" class="form-control border_radius number_of_rate" id="number_of_rate" name="number_of_rate['+$count_billing_service_info+']" value=""/></div></div><div class="rate_class" style="width: 200px;display: none"><div style="font-weight: bold;">Measurement Unit</div><div class="input-group" style="width: 100%"><input type="text" class="form-control border_radius unit_for_rate" id="unit_for_rate" name="unit_for_rate['+$count_billing_service_info+']" value=""/></div></div></div>';   
    $a += '<div class="td" style="width:150px"><input type="hidden" name="gst_category_id[]" class="form-control gst_category_id" id="gst_category_id" value=""/><input type="hidden" name="gst_new_way[]" class="form-control gst_new_way" id="gst_new_way" value=""/><input type="hidden" name="gst_rate[]" class="form-control gst_rate" id="gst_rate" value=""/><div class="input-group"><input type="text" name="amount['+$count_billing_service_info+']" class="numberdes form-control text-right transaction_create_billing_amount" value="" id="transaction_create_billing_amount" style="width:150px"/><div id="form_amount"></div></div></div>';
    $a += '<div class="td" style="width:150px"><div class="select-input-group"><select class="form-control" style="text-align:right;width: 165px;" name="unit_pricing['+$count_billing_service_info+']" id="unit_pricing"><option value="0" >Select Unit Pricing</option></select><div id="form_unit_pricing"></div></div></div>';
    $a += '<div class="td action"><button type="button" class="btn btn-primary delete_billing_button" onclick="delete_billing(this)" style="display: block;">Delete</button></div>';
    $a += '</div>';

    $("#body_create_billing").append($a); 

    if($("#body_create_billing > div").length > 1)
    {
        $('.delete_billing_button').css('display','block');
    }

    $('.period_start_date').datepicker({ 
        dateFormat:'dd/mm/yyyy',
    }).datepicker('setStartDate', "01/01/1920");

    $('.period_end_date').datepicker({ 
        dateFormat:'dd/mm/yyyy',
    }).datepicker('setStartDate', "01/01/1920");

    $.each(unit_pricing, function(key, val) {
        var option = $('<option />');
        option.attr('value', val['id']).text(val['unit_pricing_name']);
        
        $("#form"+$count_billing_service_info+" #unit_pricing").append(option);
    });

    var category_description = '';
    var optgroup = '';

    for(var t = 0; t < service_category_array.length; t++)
    {
        if(category_description != service_category_array[t]['category_description'])
        {
            if(optgroup != '')
            {
                $("#form"+$count_billing_service_info+" #service"+$count_billing_service_info).append(optgroup);
            }
            optgroup = $('<optgroup label="' + service_category_array[t]['category_description'] + '" />');
        }

        category_description = service_category_array[t]['category_description'];
        
        for(var h = 0; h < service_array.length; h++)
        {
            if(category_description == service_array[h]['category_description'])
            {
                var option = $('<option />');
                option.attr('data-gst_category_id', service_array[h]['gst_category_id']).attr('data-calculate_by_quantity_rate', service_array[h]['calculate_by_quantity_rate']).attr('data-gst_new_way', service_array[h]['gst_new_way']).attr('data-rate', service_array[h]['rate']).attr('data-our_service_id', service_array[h]['service']).attr('data-description', service_array[h]['invoice_description']).attr('data-currency', service_array[h]['currency']).attr('data-unit_pricing', service_array[h]['unit_pricing']).attr('data-amount', service_array[h]['amount']).attr('value', service_array[h]['id']).text(service_array[h]['service_name']);
                if(service_array[h]['deleted'] == 0)
                {
                    option.appendTo(optgroup);
                }
            }
        }
    }
    
    $("#form"+$count_billing_service_info+" #service"+$count_billing_service_info).append(optgroup);   

    $("#form"+$count_billing_service_info+" #service"+$count_billing_service_info).select2();

    $('#transaction_create_billing_form').formValidation('addField', 'service['+$count_billing_service_info+']', service);
    $('#transaction_create_billing_form').formValidation('addField', 'invoice_description['+$count_billing_service_info+']', invoice_description);
    $('#transaction_create_billing_form').formValidation('addField', 'amount['+$count_billing_service_info+']', amount);
    $('#transaction_create_billing_form').formValidation('addField', 'unit_pricing['+$count_billing_service_info+']', validate_unit_pricing);

    count_billing_service_info_num++;

    $("#modal_billing").data("bs.modal").handleUpdate();
});

$(document).on("submit","#transaction_create_billing_form",function(e){
    e.preventDefault();
    var $form = $(e.target);
    // and the FormValidation instance
    var fv = $form.data('formValidation');
    // Get the first invalid field
    var $invalidFields = fv.getInvalidFields().eq(0);
    // Get the tab that contains the first invalid field
    var $tabPane     = $invalidFields.parents();
    var valid_setup = fv.isValidContainer($tabPane);
    transaction_task_id = $("#transaction_trans #transaction_task").val();
    if(valid_setup)
    {
        if($('#create_billing_service').css('display') != 'none')
        {   
            $('#loadingBilling').show();
            $('[name="billing_date"]').attr('disabled', false);
            $('[name="address"]').attr('disabled', false);
            $('.currency').attr('disabled', false);
            $("#saveBilling").attr("disabled", true);
            if(transaction_task_id == 36)
            {
                var company_name = $('select.transaction_drop_client_name option:selected').text();
            }
            else
            {
                var company_name = $(".transaction_client_name").val();
            }
            $('#loadingBilling').show();
            $.ajax({
                type: 'POST',
                url: "billings/save_transaction_create_billing",
                data: $form.serialize() + '&company_name=' + encodeURIComponent(company_name) + '&transaction_master_id=' + $("#transaction_master_id").val() + '&transaction_task_id=' + transaction_task_id, 
                dataType: 'json',
                success: function(response){
                    $('#loadingBilling').hide();
                    $("#saveBilling").attr("disabled", false);
                    if (response.Status === 1) 
                    {
                        toastr.success(response.message, response.title);
                    }
                    else if (response.Status === 2) 
                    {
                        toastr.warning(response.message, response.title);
                    }
                    else
                    {
                        toastr.error(response.message, response.title);
                    }

                    $("#previous_invoice_no").val(response.previous_invoice_no);
                    $('#modal_billing').modal('toggle');

                    billings = response[0]["billings"];
                    paid_billings = response[0]["paid_billings"];
                    insert_billing_table_row(billings, paid_billings);
                }
            });
        }
        else
        {
            toastr.error("Please set service engagement in this client.", "error");
        }
    }
});

$(document).on('click',"#saveBilling",function(e){
    e.preventDefault();
    // var $forms = $($("#transaction_create_billing_form")[0]);
    // console.log($forms.data('formValidation'));
    $("#transaction_create_billing_form").submit();
});

function exportPDF($billing_id){

    bootbox.confirm({
        message: "Do you want to print Pre-printed Letterhead document?",
        closeButton: false,
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

            var billingCheckboxes = new Array();
            billingCheckboxes.push($billing_id);
            //console.log($tab_aktif);
            $('#loadingmessage').show();
            $.ajax({
                type: "POST",
                url: "createbillingpdf/create_billing_pdf",
                data: {"billing_id":billingCheckboxes, "tab": "billing", "pre-printed": result}, // <--- THIS IS THE CHANGE
                dataType: "json",
                success: function(response){
                    //console.log(response.link);
                    //console.log(window.URL);
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
                    //setTimeout(function(){ deleteInvoicePDF(); }, 5000);
                }               
            });
        }
    })
}

function deleteBilling($billing_id){
    bootbox.confirm({
        message: "Do you want to delete this selected info?",
        closeButton: false,
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
            //console.log(result);
            if(result == true)
            {
                var billingCheckboxes = new Array();

                billingCheckboxes.push($billing_id);

                $.ajax({
                    type: "POST",
                    url: "billings/delete_billing",
                    data: {"billing_id":billingCheckboxes, "tab": "billing"}, // <--- THIS IS THE CHANGE
                    dataType: "json",
                    success: function(response){
                        if(response.Status == 1)
                        {
                            toastr.success(response.message, response.title);
                            location.reload();
                        }
                    }               
                });
            }
        }
    })
}
                
            