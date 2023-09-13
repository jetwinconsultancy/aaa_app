var pathArray = location.href.split( '/' );
var protocol = pathArray[0];
var host = pathArray[2];
var folder = pathArray[3];

var latest_gst_rate = 0, count_billing_service_info_num = 0, tmp = [], total_claim_amount = 0;
var state_own_letterhead_checkbox = true;


$('#transaction_create_billing_form').formValidation({
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
        billing_date: {
            row: '.billing_date_div',
            validators: {
                notEmpty: {
                    message: 'The Date field is required.'
                }
            }
        },
        invoice_no: {
            row: '.validate',
            validators: {
                notEmpty: {
                    message: 'The Invoice No field is required.'
                }
            }
        },
        transaction_client_name: {
            row: '.input_client_name',
            validators: {
                notEmpty: {
                    message: 'The Client Name field is required.'
                }
            }
        },
        rate: {
            row: '.rate-input-group',
            validators: {
                notEmpty: {
                    message: 'The Rate field is required.'
                }
            }
        },
        address: {
            row: '.input-group',
            validators: {
                notEmpty: {
                    message: 'The Address field is required.'
                }
            }
        },
        currency: {
            row: '.input-group',
            validators: {
                callback: {
                    message: 'The Currency field is required.',
                    callback: function(value, validator, $field) {
                        var options = validator.getFieldElements('currency').val();
                        return (options != null && options != "0");
                    }
                }
            }
        },
        transaction_drop_client_name: {
            row: '.input-group',
            validators: {
                callback: {
                    message: 'The Client Name field is required.',
                    callback: function(value, validator, $field) {
                        var options = validator.getFieldElements('transaction_drop_client_name').val();
                        return (options != null && options != "0");
                    }
                }
            }
        }
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
    },
    service = {
        //excluded: [':disabled', ':hidden', ':not(:visible)'],
        row: '.select-input-group',
        validators: {
            callback: {
                message: 'The Service field is required.',
                callback: function(value, validator, $field) {
                    var num = jQuery($field).parent().parent().parent().attr("num");
                    var options = validator.getFieldElements('service['+num+']').val();
                    return (options != null && options != "0");
                }
            }
        }
    },
    validate_unit_pricing = {
        //excluded: [':disabled', ':hidden', ':not(:visible)'],
        row: '.select-input-group',
        validators: {
            callback: {
                message: 'The Unit Pricing field is required.',
                callback: function(value, validator, $field) {
                    var num = jQuery($field).parent().parent().parent().attr("num");
                    var options = validator.getFieldElements('unit_pricing['+num+']').val();
                    return (options != null && options != "0");
                }
            }
        }
    };

function ajaxCall() {
    this.send = function(data, url, method, success, type) {
        type = type||'json';
        var successRes = function(data) {
            success(data);
        };

        var errorRes = function(e) {
            if(e.status != 200)
            {
                alert("Error found \nError Code: "+e.status+" \nError Message: "+e.statusText);
            }
        };
        $.ajax({
            url: url,
            type: method,
            data: data,
            success: successRes,
            error: errorRes,
            dataType: type,
            timeout: 60000
        });

    }

}

function Client() {
    var base_url = window.location.origin;  
    var call = new ajaxCall();

    this.getCurrency = function() {
        var url = base_url+"/"+folder+"/"+'companytype/getCurrency';
        var method = "get";
        var data = {};
        $('.currency').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            $('.currency').find("option:eq(0)").html("Select Currency");
            if(data.tp == 1){
                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    if(data.selected_currency != null && key == data.selected_currency)
                    {
                        option.attr('selected', 'selected');
                    }
                    else if(key == 1)
                    {
                        option.attr('selected', 'selected');
                    }
                    $('.currency').append(option);
                });

                if(!billing_below_info)
                {
                    showRow();
                }
                
            }
            else{
                alert(data.msg);
            }
        }); 
    };
}

$(function() {
    var cn = new Client();
    cn.getCurrency();
});

toastr.options = {
    "positionClass": "toast-bottom-right"
}

function optionCheckService(service_element) 
{
    var tr = jQuery(service_element).parent().parent();
    if(jQuery(service_element).val() == "1" || jQuery(service_element).val() == "0")
    {
        $(".recurring_part").hide();
        $("#type_of_day").prop("disabled", true);
        $("#days").prop("disabled", true);
        $("#from").prop("disabled", true);
        $("#to").prop("disabled", true);
    }
    else
    {
        $(".recurring_part").show();
        $("#type_of_day").prop("disabled", false);
        $("#days").prop("disabled", false);
        $("#from").prop("disabled", false);
        $("#to").prop("disabled", false);
    }
}

function formatDateFunc(date) {
  var monthNames = [
    "01", "02", "03",
    "04", "05", "06", "07",
    "08", "09", "10",
    "11", "12"
  ];

  var day = date.getDate();
  if(day.toString().length==1)  
  {
    day="0"+day;
  }
    
  var monthIndex = date.getMonth();
  var year = date.getFullYear();

  return day + '/' + monthNames[monthIndex] + '/' + year;
}

$(document).ready(function() {
    $('#loadingBilling').hide();
});

$('.billing_date').datepicker({ 
    dateFormat:'dd/mm/yyyy',
    autoclose: true,
}).datepicker("setDate", "0")
.on('changeDate', function (selected) {
    var billing_date = $('.billing_date').val();
    get_gst_rate(billing_date);
    
    $('#transaction_create_billing_form').formValidation('revalidateField', 'billing_date');
});

if(billing_top_info == undefined)
{
    $.ajax({
        type: "GET",
        url: "billings/get_invoice_no",
        asycn: false,
        dataType: "json",
        success: function(response){
            $('[name="invoice_no"]').val(response.invoice_no);
        }
    });

     $('#rate').val("1.0000");

    var d = new Date();

    var today_date = formatDateFunc(d);
    get_gst_rate(today_date);

    $("#invoice_no").attr('readOnly', true);
    $("#transaction_client_name").attr('readOnly', true);
    $("#address").attr('readOnly', true);
}
else
{
    if(billing_top_info[0]["postal_code"] != "" || billing_top_info[0]["street_name"] != "")
    {
        var units = " ";

        if(billing_top_info[0]["unit_no1"] != "" || billing_top_info[0]["unit_no2"] != "")
        {
            units = '\n#'+billing_top_info[0]["unit_no1"] + " - " + billing_top_info[0]["unit_no2"]+' ';
        }
        else if(billing_top_info[0]["unit_no1"] != "")
        {
            units = billing_top_info[0]["unit_no1"]+' ';
        }
        else if(billing_top_info[0]["unit_no2"] != "")
        {
            units = billing_top_info[0]["unit_no2"]+' ';
        }
        else
        {
            units = '\n';
        }
        var nonedit_address = billing_top_info[0]["street_name"]+units+billing_top_info[0]["building_name"]+'\nSingapore '+billing_top_info[0]["postal_code"];
    }
    else
    {
        var nonedit_address = billing_top_info[0]["foreign_address"];
    }

    $('[name="transaction_client_name"]').val(billing_top_info[0]["company_name"]);
    $('[name="invoice_no"]').val(billing_top_info[0]["invoice_no"]);
    $('[name="previous_invoice_no"]').val(billing_top_info[0]["invoice_no"]);
    $('[name="billing_date"]').val(billing_top_info[0]["invoice_date"]);
    $('[name="address"]').val(nonedit_address);
    $('[name="rate"]').val(billing_top_info[0]["rate"]);

    $("#invoice_no").attr('readOnly', true);
    $("#transaction_client_name").attr('readOnly', true);
    $('[name="billing_date"]').attr('disabled', true);
    $('[name="address"]').attr('disabled', true);

}

var service_array = "";
var service_category_array = "";
var unit_pricing = "";

$(".transaction_create_billing_amount").live('change',function(){
    sum_total();
});
$(".rate").live('change',function(){
    sum_total();
});
$(".currency").live('change',function(){
    sum_total();
});
$(".poc_percentage").live('change',function(){

    var num = $(this).parent().parent().parent().parent().attr("num");
    var amountValue = $(this).parent().parent().parent().parent().find('.number_of_percent_poc').text();
    var poc_percentage_val = $(this).parent().parent().parent().parent().find('.poc_percentage').val();
    if(poc_percentage_val != "")
    {
        if(poc_percentage_val > 100)
        {
            $(this).parent().parent().parent().parent().find('.poc_percentage').val("100");
            poc_percentage_val = 100;
            toastr.error("The percentage cannot bigger than 100.", "Error");
        }
        var after_poc_amount = (parseFloat(poc_percentage_val)/100)*parseFloat(amountValue.replace(/\,/g,''),2);
        $(this).parent().parent().parent().parent().find('#transaction_create_billing_amount').val(addCommas(after_poc_amount.toFixed(2)));

        sum_total();
    }
    else
    {
        toastr.error("The percentage cannot be null.", "Error");
    }
    
});

$(".period_start_date").live('change',function(){
    let period_start_date = $(this).val();
    let period_end_date = $(this).parent().parent().parent().parent().find('.period_end_date').val();

    let pedAr = period_end_date.split('/');
    let newPed = new Date(pedAr[2] + '-' + pedAr[1] + '-' + pedAr[0]);

    let psdAr = period_start_date.split('/');
    let newPsd = new Date(psdAr[2] + '-' + psdAr[1] + '-' + psdAr[0]);

    if(newPsd > newPed)
    {
        toastr.error("Period End Date must bigger than Period Start Date.", "Error", {
            preventDuplicates: true
            , preventOpenDuplicates: true
        });
    }
});

$(".period_end_date").live('change',function(){
    let period_end_date = $(this).val();
    let period_start_date = $(this).parent().parent().parent().parent().find('.period_start_date').val();

    let pedAr = period_end_date.split('/');
    let newPed = new Date(pedAr[2] + '-' + pedAr[1] + '-' + pedAr[0]);

    let psdAr = period_start_date.split('/');
    let newPsd = new Date(psdAr[2] + '-' + psdAr[1] + '-' + psdAr[0]);

    if(newPsd > newPed)
    {
        toastr.error("Period End Date must bigger than Period Start Date.", "Error", {
            preventDuplicates: true
            , preventOpenDuplicates: true
        });
    }
});

function sum_total(){
    var sum = 0;
    var before_gst = 0, gst = 0, gst_rate = 0, grand_total = 0, gst_with_rate = 0;
    $(".transaction_create_billing_amount").each(function(){
        if($(this).val() == '')
        {
            sum += 0;
        }
        else
        {
            sum += +parseFloat($(this).val().replace(/\,/g,''),2);

            if(billing_below_info)
            {
                //assign gst
                //gst_rate = billing_below_info[0]['gst_rate'];
                if($("#old_gst_rate").val() != "false")
                {
                    gst_rate = $("#old_gst_rate").val();
                }
                else
                {
                    gst_rate = $(this).parent().parent().parent().find(':selected').data('rate');
                }
            }
            else
            {
                if(latest_gst_rate)
                {
                    gst_rate = latest_gst_rate;
                }
                else
                {
                    gst_rate = $(this).parent().parent().parent().find(':selected').data('rate');
                }
            }

            before_gst = ((gst_rate / 100) * parseFloat($(this).val().replace(/\,/g,''),2));
            gst += parseFloat(before_gst.toFixed(2));
        }
    });

    $("#sub_total").text(addCommas(sum.toFixed(2)));
    
    if($(".currency").val() == "0" || $(".currency").val() == "1" || gst == "0.00")
    {
        gst_with_rate = " ";
        $("#gst_with_rate").text(gst_with_rate);
    }
    else if($(".currency").val() != "1")
    {
        gst_with_rate = gst * parseFloat($(".rate").val());
        $("#gst_with_rate").text("( SGD"+addCommas(gst_with_rate.toFixed(2))+" )");
    }

    $("#gst").text(addCommas(gst.toFixed(2)));
    grand_total = sum + gst;
    $("#grand_total").text(addCommas(grand_total.toFixed(2)));
    $("input[id=hidden_grand_total]").val(addCommas(grand_total.toFixed(2)));
    
}

function sum_first_total(){
    var sum = 0;
    var before_gst = 0, gst = 0, gst_rate = 0, grand_total = 0, gst_with_rate = 0;
    $(".transaction_create_billing_amount").each(function(){
        if($(this).val() == '')
        {
            sum += 0;
        }
        else
        {
            sum += +parseFloat($(this).val().replace(/\,/g,''),2);

            if(billing_below_info)
            {
                //assign gst
                //gst_rate = billing_below_info[0]['gst_rate'];
                console.log(billing_below_info);
                if($("#old_gst_rate").val() != "false")
                {
                    gst_rate = $("#old_gst_rate").val();
                }
                else
                {
                    gst_rate = $(this).parent().parent().parent().find(':selected').data('rate');
                }
                console.log(gst_rate);
            }
            else
            {
                //gst_rate = latest_gst_rate;
                if(latest_gst_rate)
                {
                    gst_rate = latest_gst_rate;
                }
                else
                {
                    gst_rate = $(this).parent().parent().parent().find(':selected').data('rate');
                }
            }

            before_gst = ((gst_rate / 100) * parseFloat($(this).val().replace(/\,/g,''),2));
            gst += parseFloat(before_gst.toFixed(2));
        }
    });

    $("#sub_total").text(addCommas(sum.toFixed(2)));

    if(billing_below_info)
    {
        if(billing_below_info[0]["currency_id"] == "1")
        {
            gst_with_rate = " ";
            $("#gst_with_rate").text(gst_with_rate);
        }
        else if(billing_below_info[0]["currency_id"] != "1")
        {
            gst_with_rate = gst * parseFloat(billing_below_info[0]["rate"]);
            $("#gst_with_rate").text("( SGD"+addCommas(gst_with_rate.toFixed(2))+" )");
        }
    }
    else
    {
        if($("#currency").val() == "1")
        {
            gst_with_rate = " ";
            $("#gst_with_rate").text(gst_with_rate);
        }
        else if($("#currency").val() != "1")
        {
            gst_with_rate = gst * parseFloat($("#rate").val());
            $("#gst_with_rate").text("( SGD"+addCommas(gst_with_rate.toFixed(2))+" )");
        }
    }

    $("#gst").text(addCommas(gst.toFixed(2)));
    grand_total = sum + gst;
    $("#grand_total").text(addCommas(grand_total.toFixed(2)));
    $("input[id=hidden_grand_total]").val(addCommas(grand_total.toFixed(2)));
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

function delete_billing(element) {
    var tr = jQuery(element).parent().parent(),
        billing_service_id = tr.find('input[name="billing_service_id[]"]').val();

    tr.closest("DIV.tr").remove();

    if($("#body_create_billing > div").length == 1)
    {
        if($('.delete_billing_button').css('display') == 'block')
        {
            $('.delete_billing_button').css('display','none');
        }
    }
    sum_total();
}

// if(billing_below_info != undefined)
// {
//     $('#create_billing_service').show();
//     $('#body_create_billing').show();
//     $('#sub_total_create_billing').show();
//     $('#gst_create_billing').show();
//     $('#grand_total_create_billing').show();

//     //assign gst
//     $('#gst_rate').val(billing_below_info[0]["gst_rate"]);
//     count_billing_service_info_num = billing_below_info.length;
//     for(var t = 0; t < billing_below_info.length; t++)
//     {
//         $count_billing_service_info = t;
//         $a=""; 
//         $a += '<div class="tr editing tr_billing" method="post" name="form'+$count_billing_service_info+'" id="form'+$count_billing_service_info+'" num="'+$count_billing_service_info+'">';
//         $a += '<div class="hidden"><input type="text" class="form-control" name="company_code" value="'+billing_below_info[t]["company_code"]+'"/></div>';
//         $a += '<div class="hidden"><input type="text" class="form-control" name="billing_service_id" value="'+billing_below_info[t]["billing_service_id"]+'"/></div>';
//         $a += '<div class="hidden"><input type="text" class="form-control" name="client_billing_info_id['+$count_billing_service_info+']" id="client_billing_info_id" value=""/></div>';
//         $a += '<div class="hidden"><input type="text" class="form-control" name="claim_service_id['+$count_billing_service_info+']" id="claim_service_id" value=""/></div>';
//         $a += '<div class="td" style="width: 150px;"><div class="select-input-group"><select class="input-sm form-control" name="service['+$count_billing_service_info+']" id="service" style="width:200px !important;"><option value="0" data-invoice_description="" data-amount="">Select Service</option></select><input type="hidden" class="form-control" name="payment_voucher_type['+$count_billing_service_info+']" id="payment_voucher_type" value=""/><div id="form_service"></div></div></div>';
//         $a += '<div class="td"><div class="input-group mb-md"><textarea class="form-control" name="invoice_description['+$count_billing_service_info+']"  id="invoice_description" rows="3" style="width:420px">'+billing_below_info[t]["invoice_description"]+'</textarea></div><div style="width: 200px;display: inline-block; margin-right:10px;"><div style="font-weight: bold;">Period Start Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker period_start_date" id="period_start_date" name="period_start_date['+$count_billing_service_info+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value="'+billing_below_info[t]["period_start_date"]+'"></div></div><div style="width: 200px;display: inline-block"><div style="font-weight: bold;">Period End Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker period_end_date" id="period_end_date" name="period_end_date['+$count_billing_service_info+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value="'+billing_below_info[t]["period_end_date"]+'"></div></div></div>';
//         $a += '<div class="td" style="width:150px"><div class="input-group"><input type="text" name="amount['+$count_billing_service_info+']" class="numberdes form-control text-right transaction_create_billing_amount" value="'+addCommas(billing_below_info[t]["billing_service_amount"])+'" id="transaction_create_billing_amount" style="width:150px"/><div id="form_amount"></div></div></div>';
//         $a += '<div class="td" style="width:150px"><div class="select-input-group"><select class="form-control" style="text-align:right;width: 165px;" name="unit_pricing['+$count_billing_service_info+']" id="unit_pricing"><option value="0" >Select Unit Pricing</option></select></div></div>';
//         $a += '<div class="td action"><button type="button" class="btn btn-primary delete_billing_button" onclick="delete_billing(this)" style="display: none;">Delete</button></div>';
//         $a += '</div>';
//         $("#body_create_billing").append($a); 

//         $("#form"+$count_billing_service_info+" #claim_service_id").attr('value', billing_below_info[t]["claim_service_id"]);

//         if($("#body_create_billing > div").length > 1)
//         {
//             $('.delete_billing_button').css('display','block');
//         }

//         $('.period_start_date').datepicker({ 
//             dateFormat:'dd/mm/yyyy',
//         }).datepicker('setStartDate', "01/01/1920");

//         $('.period_end_date').datepicker({ 
//             dateFormat:'dd/mm/yyyy',
//         }).datepicker('setStartDate', "01/01/1920");

//         $.each(get_unit_pricing, function(key, val) {
//             var option = $('<option />');
//             option.attr('value', val['id']).text(val['unit_pricing_name']);
            
//             if(billing_below_info[t]["unit_pricing"] != null && val['id'] == billing_below_info[t]["unit_pricing"])
//             {
//                 option.attr('selected', 'selected');
//             }

//             $("#form"+$count_billing_service_info+" #unit_pricing").append(option);
//         });

//         var category_description = '';
//         var optgroup = '';

//         for(var j = 0; j < service_category.length; j++)
//         {
//             if(category_description != service_category[j]['category_description'])
//             {
//                 if(optgroup != '')
//                 {
//                     $("#form"+$count_billing_service_info+" #service").append(optgroup);
//                 }
//                 optgroup = $('<optgroup label="' + service_category[j]['category_description'] + '" />');
//             }

//             category_description = service_category[j]['category_description'];
            
//             for(var h = 0; h < service_dropdown.length; h++)
//             {
//                 if(category_description == service_dropdown[h]['category_description'])
//                 {
//                     var option = $('<option />');
//                     option.attr('data-description', service_dropdown[h]['invoice_description']).attr('data-currency', service_dropdown[h]['currency']).attr('data-unit_pricing', service_dropdown[h]['unit_pricing']).attr('data-amount', service_dropdown[h]['amount']).attr('value', service_dropdown[h]['id']).text(service_dropdown[h]['service_name']).appendTo(optgroup);
                    
//                     if(billing_below_info[t]["service"] != null && service_dropdown[h]['id'] == billing_below_info[t]["service"])
//                     {
//                         option.attr('selected', 'selected');
//                     }
//                 }
//             }
//         }
//         $("#form"+t+" #service").append(optgroup);
//         $("#form"+t+" #service").select2();

//         service_array = service_dropdown;
//         service_category_array = service_category;
//         unit_pricing = get_unit_pricing;

//         $('#transaction_create_billing_form').formValidation('addField', 'service['+$count_billing_service_info+']', service);
//         $('#transaction_create_billing_form').formValidation('addField', 'invoice_description['+$count_billing_service_info+']', invoice_description);
//         $('#transaction_create_billing_form').formValidation('addField', 'amount['+$count_billing_service_info+']', amount);
//         $('#transaction_create_billing_form').formValidation('addField', 'unit_pricing['+$count_billing_service_info+']', validate_unit_pricing);
//     }
//     sum_first_total();
// }

$(document).on('change','.service',function(e){
    var num = $(this).parent().parent().parent().attr("num");

    var calculate_by_quantity_rate = $(this).find(':selected').data('calculate_by_quantity_rate');
    var descriptionValue = $(this).find(':selected').data('description');
    var amountValue = $(this).find(':selected').data('amount');
    var unit_pricingValue = $(this).find(':selected').data('unit_pricing');
    var rate = $(this).find(':selected').data('rate');
    var gst_new_way = $(this).find(':selected').data('gst_new_way');
    var gst_category_id = $(this).find(':selected').data('gst_category_id');

    if(calculate_by_quantity_rate == "1")
    {
        $(this).parent().parent().parent().find('.period_class').hide();
        $(this).parent().parent().parent().find('.rate_class').css('display', 'inline-block');
        $(this).parent().parent().parent().find('.type_reading_quantity_class').css('display', 'block');
    }
    else if(calculate_by_quantity_rate == "2")
    {
        $(this).parent().parent().parent().find('.period_class').css('display', 'inline-block');
        $(this).parent().parent().parent().find('.rate_class').hide();
        $(this).parent().parent().parent().find('.type_reading_quantity_class').hide();
    }

    $(this).parent().parent().parent().find('#invoice_description').text(descriptionValue);
    var poc_percentage_val = $(this).parent().parent().parent().find('.poc_percentage').val();
    if(poc_percentage_val != "")
    {
        var after_poc_amount = (parseFloat(poc_percentage_val)/100)*parseFloat(amountValue);
        $(this).parent().parent().parent().find('#transaction_create_billing_amount').val(addCommas(after_poc_amount.toFixed(2)));
    }
    else
    {
        $(this).parent().parent().parent().find('#transaction_create_billing_amount').val(addCommas(amountValue));
    }
    $(this).parent().parent().parent().find('.number_of_percent_poc').text(addCommas(amountValue));
    $(this).parent().parent().parent().find('.hidden_number_of_percent_poc').val(addCommas(amountValue));
    //$(this).parent().parent().parent().find('#transaction_create_billing_amount').val(addCommas(amountValue));
    $(this).parent().parent().parent().find('#unit_pricing').val(addCommas(unit_pricingValue));
    $(this).parent().parent().parent().find('#gst_rate').val(rate);
    $(this).parent().parent().parent().find('#gst_new_way').val(gst_new_way);
    $(this).parent().parent().parent().find('#gst_category_id').val(gst_category_id);

    showProgressBillingInfo($(this).parent().parent().parent());

    $('#transaction_create_billing_form').formValidation('revalidateField', 'service['+num+']');
    $('#transaction_create_billing_form').formValidation('revalidateField', 'invoice_description['+num+']');
    $('#transaction_create_billing_form').formValidation('revalidateField', 'amount['+num+']');
    $('#transaction_create_billing_form').formValidation('revalidateField', 'unit_pricing['+num+']');
    sum_total();
});

$(document).on('change','.radio_reading',function(e){
    var num = $(this).parent().parent().parent().attr("num");
    $(this).parent().parent().parent().find('.quantity').hide();
    $(this).parent().parent().parent().find('.reading').css('display', 'inline-block');
    $(this).parent().parent().parent().find('#quantity_value').val("");
    $(this).parent().parent().parent().find('#reading_at_begin').val("");
    $(this).parent().parent().parent().find('#reading_at_the_end').val("");
    $(this).parent().parent().parent().find('#number_of_rate').val("");
    $(this).parent().parent().parent().find('#unit_for_rate').val("");
});

$(document).on('change','.radio_quantity',function(e){
    var num = $(this).parent().parent().parent().attr("num");
    $(this).parent().parent().parent().find('.quantity').css('display', 'inline-block');
    $(this).parent().parent().parent().find('.reading').hide();
    $(this).parent().parent().parent().find('#quantity_value').val("");
    $(this).parent().parent().parent().find('#reading_at_begin').val("");
    $(this).parent().parent().parent().find('#reading_at_the_end').val("");
    $(this).parent().parent().parent().find('#number_of_rate').val("");
    $(this).parent().parent().parent().find('#unit_for_rate').val("");
});

$(document).on('change','.progress_billing_yes',function(e){
    var amountValue = $(this).parent().parent().parent().parent().find('#transaction_create_billing_amount').val();
    //console.log(amountValue);
    $(this).parent().parent().parent().find('.poc_percent_div').show();
    $(this).parent().parent().parent().find('#poc_percentage').val("");

    $(this).parent().parent().parent().find('.number_of_percent_poc').text(addCommas(amountValue));
    $(this).parent().parent().parent().find('.hidden_number_of_percent_poc').val(addCommas(amountValue));
    
    showProgressBillingInfo($(this).parent().parent().parent());
});

$(document).on('change','.progress_billing_no',function(e){
    $(this).parent().parent().parent().find('.poc_percent_div').hide();
    $(this).parent().parent().parent().find('#poc_percentage').val("");
    //parseFloat($(this).val().replace(/\,/g,''),2))
    var txt_number_of_poc = parseFloat($(this).parent().parent().parent().find('.number_of_percent_poc').text().replace(/\,/g,''),2);
    //console.log(txt_number_of_poc);
    if(txt_number_of_poc >= 0)
    {
        $(this).parent().parent().parent().parent().find('#transaction_create_billing_amount').val(addCommas(txt_number_of_poc.toFixed(2)));
    }
    else
    {
        $(this).parent().parent().parent().parent().find('#transaction_create_billing_amount').val("0.00");
    }

    $(this).parent().parent().parent().find('.number_of_percent_poc').text("0.00");
    $(this).parent().parent().parent().find('.hidden_number_of_percent_poc').val("0.00");
});

$(document).on('change','.number_of_rate',function(e){

    if ($(this).parent().parent().parent().parent().find("#radio_reading").prop("checked")) 
    {
        calculate_total_reading($(this).parent().parent().parent().parent());
    }
    else if ($(this).parent().parent().parent().parent().find("#radio_quantity").prop("checked"))
    {
        calculate_total_quantity($(this).parent().parent().parent().parent());
    }
});

$(document).on('change','.reading_at_begin, .reading_at_the_end',function(e){
    calculate_total_reading($(this).parent().parent().parent().parent());
});

function calculate_total_reading($row)
{
    var reading_beginning = $row.find(".reading_at_begin").val();
    var reading_end = $row.find(".reading_at_the_end").val();
    var reading_rate = $row.find(".number_of_rate").val();

    if(isNumeric(reading_beginning) && isNumeric(reading_end) && isNumeric(reading_rate))
    {
        var total_amount_for_reading = (parseFloat(reading_end) - parseFloat(reading_beginning)) * parseFloat(reading_rate);
        console.log(addCommas(total_amount_for_reading.toFixed(2)));
        console.log($row.find("#transaction_create_billing_amount"));
        var format_total_amount_for_reading = addCommas(total_amount_for_reading.toFixed(2));
        $row.find("#transaction_create_billing_amount").val(format_total_amount_for_reading);
        $row.find('.number_of_percent_poc').text(format_total_amount_for_reading);
        $row.find('.hidden_number_of_percent_poc').val(format_total_amount_for_reading);
    }
    else
    {
        console.log("in");
        $row.find("#transaction_create_billing_amount").val("0.00");
    }

    sum_total();
}

$(document).on('change','.quantity_value',function(e){
    calculate_total_quantity($(this).parent().parent().parent().parent());
});

function calculate_total_quantity($row)
{
    var quantity_val = $row.find(".quantity_value").val();
    var reading_rate = $row.find(".number_of_rate").val();

    if(isNumeric(quantity_val) && isNumeric(reading_rate))
    {
        var total_amount_for_quantity = parseFloat(quantity_val)*parseFloat(reading_rate);
        $row.find("#transaction_create_billing_amount").val(addCommas(total_amount_for_quantity.toFixed(2)));
        $row.find('.number_of_percent_poc').text(addCommas(total_amount_for_quantity.toFixed(2)));
        $row.find('.hidden_number_of_percent_poc').val(addCommas(total_amount_for_quantity.toFixed(2)));
    }
    else
    {
        $row.find("#transaction_create_billing_amount").val("0.00");
    }

    sum_total();
}

function isNumeric(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}

function showProgressBillingInfo($row)
{
    var company_code = $('#client_name option:selected').val();
    var serviceValue = $row.find('.service option:selected').val();
    console.log($row);
    if ($row.find("#progress_billing_no").prop("checked")) 
    {
        var progress_billing_status = false;
    }
    else if ($row.find("#progress_billing_yes").prop("checked"))
    {
        var progress_billing_status = true;
    }

    if(serviceValue != 0 && progress_billing_status)
    {
        $.ajax({
            type: "POST",
            url: "billings/check_progress_billing_data",
            data: {"company_code":company_code, "serviceValue": serviceValue}, // <--- THIS IS THE CHANGE
            dataType: "json",
            success: function(response){
                $(".tr_progress_billing_info").remove();
                //console.log(response);
                if(response.progress_billing_data)
                {
                    var progress_billing_data = response.progress_billing_data;
                    for(x in progress_billing_data)
                    {
                        if(progress_billing_data[x]["transaction_master_with_billing_id"] != null && (progress_billing_data[x]["trans_billing_info_service_category_id"] != null || progress_billing_data[x]["our_service_billing_info_service_category_id"] != null))
                        {
                            if(progress_billing_data[x]["trans_billing_info_service_category_id"] != null)
                            {
                                var service_name = progress_billing_data[x]["trans_service_name"];
                                
                            }
                            else if(progress_billing_data[x]["our_service_billing_info_service_category_id"] != null)
                            {
                                var service_name = progress_billing_data[x]["our_service_service_name"];
                            }
                        }
                        else
                        {
                            var service_name = progress_billing_data[x]["service_name"];
                        }

                        var invoice_number = progress_billing_data[x]["invoice_no"];
                        var poc = progress_billing_data[x]["poc_percentage"] + "% of " + progress_billing_data[x]["number_of_percent_poc"];
                        var period_start_date = (progress_billing_data[x]["period_start_date"] != null)?progress_billing_data[x]["period_start_date"]:"";
                        var period_end_date = (progress_billing_data[x]["period_end_date"] != null)?progress_billing_data[x]["period_end_date"]:"";
                        var amount = progress_billing_data[x]["currency_name"] + progress_billing_data[x]["billing_service_amount"];

                        $a = '';
                        $a += '<tr class="tr_progress_billing_info"><td style="text-align: right">'+(parseInt(x)+1)+'</td>';
                        $a += '<td>'+invoice_number+'</td>';
                        $a += '<td>'+service_name+'</td>';
                        $a += '<td>'+poc+'</td>';
                        $a += '<td>'+period_start_date+'</td>';
                        $a += '<td>'+period_end_date+'</td>';
                        $a += '<td>'+amount+'</td>';
                        $a +=  '</tr>';

                        $("#tbody_poc_info").append($a);
                    }

                    $("#progressBillingModalScrollable").modal("show");
                }
            }
        });
    }
}

if(billing_below_info)
{
    count_billing_service_info_num = billing_below_info.length;
}

$(document).on('change','select.transaction_drop_client_name',function(e){
    var company_code = $('select.transaction_drop_client_name option:selected').val();
    $.ajax({
        type: "POST",
        url: "billings/get_client_address",
        data: {"company_code":company_code}, // <--- THIS IS THE CHANGE
        dataType: "json",
        success: function(response){
            if(response.Status == 1)
            {
                if(company_code == 0)
                {
                    $('[name="address"]').val("");
                    $('[name="hidden_postal_code"]').val("");
                    $('[name="hidden_street_name"]').val("");
                    $('[name="hidden_building_name"]').val("");
                    $('[name="hidden_unit_no1"]').val("");
                    $('[name="hidden_unit_no2"]').val("");
                }
                else
                {
                    $('[name="address"]').val(response.address);
                    $('[name="hidden_postal_code"]').val(response.postal_code);
                    $('[name="hidden_street_name"]').val(response.street_name);
                    $('[name="hidden_building_name"]').val(response.building_name);
                    $('[name="hidden_unit_no1"]').val(response.unit_no1);
                    $('[name="hidden_unit_no2"]').val(response.unit_no2);
                }
            }
        }
    });
});

$(document).on('change','#transaction_create_billing_form #currency',function(e){
    showRow();
});

function showRow(){
    var company_code = $('.trans_company_code').val();
    var transaction_master_id = $("#transaction_trans #transaction_master_id").val();
    var transaction_task_id = $("#transaction_trans #transaction_task").val();
    if($("#currency option:selected").val() == 0)
    {
        toastr.error("Please select a currency.", "Error");
    }
    else
    {
        if(transaction_task_id == 1)
        {
            var row_url = "billings/get_transaction_company_service";
        }
        else if(transaction_task_id == 4 || transaction_task_id == 33 || transaction_task_id == 34)
        {
            var row_url = "billings/get_client_transaction_company_service";
        }
        else
        {
            var row_url = "billings/get_our_service_info_for_transaction";
        }

        $('#loadingBilling').show();
        $.ajax({
            type: "POST",
            url: row_url,
            data: {"company_code":company_code, "currency": $("#currency option:selected").val(), "transaction_master_id": transaction_master_id, "transaction_task_id": transaction_task_id}, // <--- THIS IS THE CHANGE
            dataType: "json",
            success: function(response){
                $('#loadingBilling').hide();
                $(".row_of_invoice_no").hide();
                $('[name="previous_invoice_no"]').val("");
                $(".tr_billing").remove();
                if(response.Status == 1)
                {
                    if(transaction_task_id == 36)
                    {
                        $(".dropdown_client_name").show();
                        $(".text_field_client_name").hide();
                        $('select.transaction_drop_client_name .client_option').remove();
                        $.each(response.client_list, function(key, val) {
                            var option = $('<option />');
                            option.attr('value', val["company_code"]).attr('class', 'client_option').text(val["company_name"]);
                            // if(data.selected_client_name != null && key == data.selected_client_name)
                            // {
                            //     option.attr('selected', 'selected');
                            //     $('.client_name').attr('disabled', true);
                            // }
                            $('select.transaction_drop_client_name').append(option);
                        });
                        $('select.transaction_drop_client_name').select2();
                    }
                    else
                    {
                        $(".dropdown_client_name").hide();
                        $(".text_field_client_name").show();
                        $('[name="transaction_client_name"]').val(response.company_name);
                        $('[name="address"]').val(response.address);
                        $('[name="hidden_postal_code"]').val(response.postal_code);
                        $('[name="hidden_street_name"]').val(response.street_name);
                        $('[name="hidden_building_name"]').val(response.building_name);
                        $('[name="hidden_unit_no1"]').val(response.unit_no1);
                        $('[name="hidden_unit_no2"]').val(response.unit_no2);
                    }
                    //$('#transaction_create_billing_form').formValidation('revalidateField', 'address');
                    $("#address").attr('readOnly', true);
                    
                    service_array = response.service;
                    service_category_array = response.selected_billing_info_service_category;
                    unit_pricing = response.unit_pricing;
                    count_billing_service_info_num = 0;

                    if(response.service.length != 0)
                    {
                        $('#create_billing_service').show();
                        $('#body_create_billing').show();
                        $('#sub_total_create_billing').show();
                        $('#gst_create_billing').show();
                        $('#grand_total_create_billing').show();

                        if(transaction_task_id == 1 || transaction_task_id == 4 || transaction_task_id == 33 || transaction_task_id == 34)
                        {
                            for(var k = 0; k < response.service.length; k++)
                            {
                                $a0="";

                                $a0 += '<div class="tr editing tr_billing" method="post" name="form'+count_billing_service_info_num+'" id="form'+count_billing_service_info_num+'" num="'+count_billing_service_info_num+'">';
                                $a0 += '<div class="hidden"><input type="text" class="form-control" name="company_code" value="'+$('.trans_company_code').val()+'"/></div>';
                                $a0 += '<div class="hidden"><input type="text" class="form-control" name="billing_service_id" value=""/></div>';
                                $a0 += '<div class="hidden"><input type="text" class="form-control" name="client_billing_info_id['+count_billing_service_info_num+']" id="client_billing_info_id" value=""/></div>';
                                //$a0 += '<div class="td"><div class="select-input-group"><select class="input-sm form-control service" name="service['+count_billing_service_info_num+']" id="service'+count_billing_service_info_num+'" style="width:200px;"><option value="0" data-invoice_description="" data-amount="">Select Service</option></select><input type="hidden" class="form-control" name="payment_voucher_type['+count_billing_service_info_num+']" id="payment_voucher_type" value=""/><div id="form_service"></div></div></div>';
                                $a0 += '<div class="td"><div class="select-input-group mb-md"><select class="input-sm form-control service" name="service['+count_billing_service_info_num+']" id="service'+count_billing_service_info_num+'" style="width:200px;"><option value="0" data-invoice_description="" data-amount="">Select Service</option></select><input type="hidden" class="form-control" name="payment_voucher_type['+count_billing_service_info_num+']" id="payment_voucher_type" value=""/></div><div class="input-group mb-md"><div style="font-weight: bold; margin-bottom: 5px;">Progress Billing</div><div class="form-check form-check-inline"><input class="form-check-input progress_billing_yes" type="radio" name="progress_billing_yes_no['+count_billing_service_info_num+']" id="progress_billing_yes" value="yes"><label class="form-check-label" for="progress_billing_yes">Yes</label></div><div class="form-check form-check-inline"><input class="form-check-input progress_billing_no" type="radio" name="progress_billing_yes_no['+count_billing_service_info_num+']" id="progress_billing_no" value="no" checked><label class="form-check-label" for="progress_billing_no">No</label></div></div><div class="poc_percent_div input-group mb-md" style="display:none"><div class="form-check"><input class="form-control form-check-input poc_percentage" style="width:50px; margin-right:1px;" type="text" name="poc_percentage['+count_billing_service_info_num+']" id="poc_percentage" value=""><label class="form-check-label" style="margin-top: 7px;" for="poc_percentage">% of <span class="number_of_percent_poc">0.00</span></label><input type="hidden" class="hidden_number_of_percent_poc" name="hidden_number_of_percent_poc['+count_billing_service_info_num+']" value=""></div></div></div>';
                                //$a0 += '<div class="td"><div class="input-group mb-md"><textarea class="form-control" name="invoice_description['+count_billing_service_info_num+']"  id="invoice_description" rows="3" style="width:420px"></textarea></div><div style="width: 200px;display: inline-block; margin-right:10px;"><div style="font-weight: bold;">Period Start Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker period_start_date" id="period_start_date" name="period_start_date['+count_billing_service_info_num+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div></div><div style="width: 200px;display: inline-block"><div style="font-weight: bold;">Period End Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker period_end_date" id="period_end_date" name="period_end_date['+count_billing_service_info_num+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div></div></div>';
                                $a0 += '<div class="td"><div class="input-group mb-md"><textarea class="form-control" name="invoice_description['+count_billing_service_info_num+']"  id="invoice_description" rows="3" style="width:420px"/></div><div class="period_class" style="width: 200px;display: inline-block; margin-right:10px;"><div style="font-weight: bold;">Period Start Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"/></span><input type="text" class="form-control datepicker period_start_date" id="period_start_date" name="period_start_date['+count_billing_service_info_num+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div></div><div class="period_class" style="width: 200px;display: inline-block"><div style="font-weight: bold;">Period End Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"/></span><input type="text" class="form-control datepicker period_end_date" id="period_end_date" name="period_end_date['+count_billing_service_info_num+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div></div><div class="input-group mb-md"><div class="type_reading_quantity_class" style="font-weight: bold; margin-bottom: 5px; display:none;">Type</div><div class="form-check form-check-inline rate_class" style="display:none"><input class="form-check-input radio_reading" type="radio" name="radio_quantity_reading['+count_billing_service_info_num+']" id="radio_reading" value="reading" checked><label class="form-check-label" for="radio_reading">Reading</label></div><div class="form-check form-check-inline rate_class" style="display:none"><input class="form-check-input radio_quantity" type="radio" name="radio_quantity_reading['+count_billing_service_info_num+']" id="radio_quantity" value="quantity"><label class="form-check-label" for="radio_quantity">Quantity</label></div></div><div class="quantity" style="width: 200px;display: none; margin-right:10px;"><div style="font-weight: bold;">Quantity</div><div class="input-group" style="width: 100%"><input type="text" class="form-control border_radius quantity_value" id="quantity_value" name="quantity_value['+count_billing_service_info_num+']" value=""/></div></div><div class="rate_class reading" style="width: 200px;display: none; margin-right:10px;"><div style="font-weight: bold;">Reading at beginning</div><div class="input-group" style="width: 100%"><input type="text" class="form-control border_radius reading_at_begin" id="reading_at_begin" name="reading_at_begin['+count_billing_service_info_num+']" value=""/></div></div><div class="rate_class reading" style="width: 200px;display: none"><div style="font-weight: bold;">Reading at the end</div><div class="input-group" style="width: 100%"><input type="text" class="form-control border_radius reading_at_the_end" id="reading_at_the_end" name="reading_at_the_end['+count_billing_service_info_num+']" value=""/></div></div><div class="rate_class" style="width: 200px;display: none; margin-right:10px;"><div style="font-weight: bold;">Rate</div><div class="input-group" style="width: 100%"><input type="text" class="form-control border_radius number_of_rate" id="number_of_rate" name="number_of_rate['+count_billing_service_info_num+']" value=""/></div></div><div class="rate_class" style="width: 200px;display: none"><div style="font-weight: bold;">Measurement Unit</div><div class="input-group" style="width: 100%"><input type="text" class="form-control border_radius unit_for_rate" id="unit_for_rate" name="unit_for_rate['+count_billing_service_info_num+']" value=""/></div></div></div>';
                                $a0 += '<div class="td" style="width:150px"><input type="hidden" name="gst_category_id[]" class="form-control gst_category_id" id="gst_category_id" value=""/><input type="hidden" name="gst_new_way[]" class="form-control gst_new_way" id="gst_new_way" value=""/><input type="hidden" name="gst_rate[]" class="form-control gst_rate" id="gst_rate" value=""/><div class="input-group"><input type="text" name="amount['+count_billing_service_info_num+']" class="numberdes form-control text-right transaction_create_billing_amount" value="" id="transaction_create_billing_amount" style="width:150px"/><div id="form_amount"></div></div></div>';
                                $a0 += '<div class="td" style="width:150px"><div class="select-input-group"><select class="form-control" style="text-align:right;width: 165px;" name="unit_pricing['+count_billing_service_info_num+']" id="unit_pricing"><option value="0" >Select Unit Pricing</option></select></div></div>';
                                $a0 += '<div class="td action"><button type="button" class="btn btn-primary delete_billing_button" onclick="delete_billing(this)" style="display: none;">Delete</button></div>';
                                $a0 += '</div>';

                                $("#body_create_billing").append($a0); 

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
                                    if(response.service[k]["unit_pricing"] != null && val['id'] == response.service[k]["unit_pricing"])
                                    {
                                        option.attr('selected', 'selected');
                                    }
                                    $("#form"+count_billing_service_info_num+" #unit_pricing").append(option);
                                });

                                var category_description = '';
                                var optgroup = '';

                                for(var t = 0; t < service_category_array.length; t++)
                                {
                                    if(category_description != service_category_array[t]['category_description'])
                                    {
                                        if(optgroup != '')
                                        {
                                            $("#form"+count_billing_service_info_num+" #service"+count_billing_service_info_num).append(optgroup);
                                        }
                                        optgroup = $('<optgroup label="' + service_category_array[t]['category_description'] + '" />');
                                    }

                                    category_description = service_category_array[t]['category_description'];
                                    
                                    for(var h = 0; h < service_array.length; h++)
                                    {
                                        if(category_description == service_array[h]['category_description'])
                                        {
                                            var option = $('<option />');
                                            option.attr('data-gst_category_id', service_array[h]['gst_category_id']).attr('data-calculate_by_quantity_rate', service_array[h]['calculate_by_quantity_rate']).attr('data-gst_new_way', service_array[h]['gst_new_way']).attr('data-rate', service_array[h]['rate']).attr('data-our_service_id', service_array[h]['service']).attr('data-description', service_array[h]['invoice_description']).attr('data-currency', service_array[h]['currency']).attr('data-unit_pricing', service_array[h]['unit_pricing']).attr('data-amount', service_array[h]['amount']).attr('value', service_array[h]['id']).text(service_array[h]['service_name']).appendTo(optgroup);
                                        
                                            if(response.service[k]["id"] != null && service_array[h]['id'] == response.service[k]["id"])
                                            {
                                                option.attr('selected', 'selected');
                                            }
                                        }
                                    }
                                }
                                $("#form"+count_billing_service_info_num+" #service"+count_billing_service_info_num).append(optgroup);
                                $("#form"+count_billing_service_info_num+" #service"+count_billing_service_info_num).select2();

                                $("#form"+count_billing_service_info_num+" #invoice_description").text(response.service[k]["invoice_description"]);
                                $("#form"+count_billing_service_info_num+" #transaction_create_billing_amount").val(addCommas(response.service[k]["amount"]));
                                $("#form"+count_billing_service_info_num+" #gst_new_way").val(addCommas(response.service[k]["gst_new_way"]));
                                $("#form"+count_billing_service_info_num+" #gst_rate").val(addCommas(response.service[k]["rate"]));
                                $("#form"+count_billing_service_info_num+" #gst_category_id").val(addCommas(response.service[k]["gst_category_id"]));
                               
                                $('#transaction_create_billing_form').formValidation('addField', 'service['+count_billing_service_info_num+']', service);
                                $('#transaction_create_billing_form').formValidation('addField', 'invoice_description['+count_billing_service_info_num+']', invoice_description);
                                $('#transaction_create_billing_form').formValidation('addField', 'amount['+count_billing_service_info_num+']', amount);  
                                $('#transaction_create_billing_form').formValidation('addField', 'unit_pricing['+count_billing_service_info_num+']', validate_unit_pricing);  
                                
                                count_billing_service_info_num++;
                            }
                            sum_first_total();
                        }
                        else
                        {
                            $a0="";

                            $a0 += '<div class="tr editing tr_billing" method="post" name="form'+count_billing_service_info_num+'" id="form'+count_billing_service_info_num+'" num="'+count_billing_service_info_num+'">';
                            $a0 += '<div class="hidden"><input type="text" class="form-control" name="company_code" value="'+$('.trans_company_code').val()+'"/></div>';
                            $a0 += '<div class="hidden"><input type="text" class="form-control" name="billing_service_id" value=""/></div>';
                            $a0 += '<div class="hidden"><input type="text" class="form-control" name="client_billing_info_id['+count_billing_service_info_num+']" id="client_billing_info_id" value=""/></div>';
                            //$a0 += '<div class="td"><div class="select-input-group"><select class="input-sm form-control service" name="service['+count_billing_service_info_num+']" id="service'+count_billing_service_info_num+'" style="width:200px;"><option value="0" data-invoice_description="" data-amount="">Select Service</option></select><input type="hidden" class="form-control" name="payment_voucher_type['+count_billing_service_info_num+']" id="payment_voucher_type" value=""/><div id="form_service"></div></div></div>';
                            $a0 += '<div class="td"><div class="select-input-group mb-md"><select class="input-sm form-control service" name="service['+count_billing_service_info_num+']" id="service'+count_billing_service_info_num+'" style="width:200px;"><option value="0" data-invoice_description="" data-amount="">Select Service</option></select><input type="hidden" class="form-control" name="payment_voucher_type['+count_billing_service_info_num+']" id="payment_voucher_type" value=""/></div><div class="input-group mb-md"><div style="font-weight: bold; margin-bottom: 5px;">Progress Billing</div><div class="form-check form-check-inline"><input class="form-check-input progress_billing_yes" type="radio" name="progress_billing_yes_no['+count_billing_service_info_num+']" id="progress_billing_yes" value="yes"><label class="form-check-label" for="progress_billing_yes">Yes</label></div><div class="form-check form-check-inline"><input class="form-check-input progress_billing_no" type="radio" name="progress_billing_yes_no['+count_billing_service_info_num+']" id="progress_billing_no" value="no" checked><label class="form-check-label" for="progress_billing_no">No</label></div></div><div class="poc_percent_div input-group mb-md" style="display:none"><div class="form-check"><input class="form-control form-check-input poc_percentage" style="width:50px; margin-right:1px;" type="text" name="poc_percentage['+count_billing_service_info_num+']" id="poc_percentage" value=""><label class="form-check-label" style="margin-top: 7px;" for="poc_percentage">% of <span class="number_of_percent_poc">0.00</span></label><input type="hidden" class="hidden_number_of_percent_poc" name="hidden_number_of_percent_poc['+count_billing_service_info_num+']" value=""></div></div></div>';
                            //$a0 += '<div class="td"><div class="input-group mb-md"><textarea class="form-control" name="invoice_description['+count_billing_service_info_num+']"  id="invoice_description" rows="3" style="width:420px"></textarea></div><div style="width: 200px;display: inline-block; margin-right:10px;"><div style="font-weight: bold;">Period Start Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker period_start_date" id="period_start_date" name="period_start_date['+count_billing_service_info_num+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div></div><div style="width: 200px;display: inline-block"><div style="font-weight: bold;">Period End Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker period_end_date" id="period_end_date" name="period_end_date['+count_billing_service_info_num+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div></div></div>';
                            $a0 += '<div class="td"><div class="input-group mb-md"><textarea class="form-control" name="invoice_description['+count_billing_service_info_num+']"  id="invoice_description" rows="3" style="width:420px"/></div><div class="period_class" style="width: 200px;display: inline-block; margin-right:10px;"><div style="font-weight: bold;">Period Start Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"/></span><input type="text" class="form-control datepicker period_start_date" id="period_start_date" name="period_start_date['+count_billing_service_info_num+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div></div><div class="period_class" style="width: 200px;display: inline-block"><div style="font-weight: bold;">Period End Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"/></span><input type="text" class="form-control datepicker period_end_date" id="period_end_date" name="period_end_date['+count_billing_service_info_num+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div></div><div class="input-group mb-md"><div class="type_reading_quantity_class" style="font-weight: bold; margin-bottom: 5px; display:none;">Type</div><div class="form-check form-check-inline rate_class" style="display:none"><input class="form-check-input radio_reading" type="radio" name="radio_quantity_reading['+count_billing_service_info_num+']" id="radio_reading" value="reading" checked><label class="form-check-label" for="radio_reading">Reading</label></div><div class="form-check form-check-inline rate_class" style="display:none"><input class="form-check-input radio_quantity" type="radio" name="radio_quantity_reading['+count_billing_service_info_num+']" id="radio_quantity" value="quantity"><label class="form-check-label" for="radio_quantity">Quantity</label></div></div><div class="quantity" style="width: 200px;display: none; margin-right:10px;"><div style="font-weight: bold;">Quantity</div><div class="input-group" style="width: 100%"><input type="text" class="form-control border_radius quantity_value" id="quantity_value" name="quantity_value['+count_billing_service_info_num+']" value=""/></div></div><div class="rate_class reading" style="width: 200px;display: none; margin-right:10px;"><div style="font-weight: bold;">Reading at beginning</div><div class="input-group" style="width: 100%"><input type="text" class="form-control border_radius reading_at_begin" id="reading_at_begin" name="reading_at_begin['+count_billing_service_info_num+']" value=""/></div></div><div class="rate_class reading" style="width: 200px;display: none"><div style="font-weight: bold;">Reading at the end</div><div class="input-group" style="width: 100%"><input type="text" class="form-control border_radius reading_at_the_end" id="reading_at_the_end" name="reading_at_the_end['+count_billing_service_info_num+']" value=""/></div></div><div class="rate_class" style="width: 200px;display: none; margin-right:10px;"><div style="font-weight: bold;">Rate</div><div class="input-group" style="width: 100%"><input type="text" class="form-control border_radius number_of_rate" id="number_of_rate" name="number_of_rate['+count_billing_service_info_num+']" value=""/></div></div><div class="rate_class" style="width: 200px;display: none"><div style="font-weight: bold;">Measurement Unit</div><div class="input-group" style="width: 100%"><input type="text" class="form-control border_radius unit_for_rate" id="unit_for_rate" name="unit_for_rate['+count_billing_service_info_num+']" value=""/></div></div></div>';
                            $a0 += '<div class="td" style="width:150px"><input type="hidden" name="gst_category_id[]" class="form-control gst_category_id" id="gst_category_id" value=""/><input type="hidden" name="gst_new_way[]" class="form-control gst_new_way" id="gst_new_way" value=""/><input type="hidden" name="gst_rate[]" class="form-control gst_rate" id="gst_rate" value=""/><div class="input-group"><input type="text" name="amount['+count_billing_service_info_num+']" class="numberdes form-control text-right transaction_create_billing_amount" value="" id="transaction_create_billing_amount" style="width:150px"/><div id="form_amount"></div></div></div>';
                            $a0 += '<div class="td" style="width:150px"><div class="select-input-group"><select class="form-control" style="text-align:right;width: 165px;" name="unit_pricing['+count_billing_service_info_num+']" id="unit_pricing"><option value="0" >Select Unit Pricing</option></select></div></div>';
                            $a0 += '<div class="td action"><button type="button" class="btn btn-primary delete_billing_button" onclick="delete_billing(this)" style="display: none;">Delete</button></div>';
                            $a0 += '</div>';

                            $("#body_create_billing").append($a0); 

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
                                // if(response.service[k]["unit_pricing"] != null && val['id'] == response.service[k]["unit_pricing"])
                                // {
                                //     option.attr('selected', 'selected');
                                // }
                                $("#form"+count_billing_service_info_num+" #unit_pricing").append(option);
                            });

                            var category_description = '';
                            var optgroup = '';

                            for(var t = 0; t < service_category_array.length; t++)
                            {
                                if(category_description != service_category_array[t]['category_description'])
                                {
                                    if(optgroup != '')
                                    {
                                        $("#form"+count_billing_service_info_num+" #service"+count_billing_service_info_num).append(optgroup);
                                    }
                                    optgroup = $('<optgroup label="' + service_category_array[t]['category_description'] + '" />');
                                }

                                category_description = service_category_array[t]['category_description'];
                                
                                for(var h = 0; h < service_array.length; h++)
                                {
                                    if(category_description == service_array[h]['category_description'])
                                    {
                                        var option = $('<option />');
                                        option.attr('data-gst_category_id', service_array[h]['gst_category_id']).attr('data-calculate_by_quantity_rate', service_array[h]['calculate_by_quantity_rate']).attr('data-gst_new_way', service_array[h]['gst_new_way']).attr('data-rate', service_array[h]['rate']).attr('data-our_service_id', service_array[h]['service']).attr('data-description', service_array[h]['invoice_description']).attr('data-currency', service_array[h]['currency']).attr('data-unit_pricing', service_array[h]['unit_pricing']).attr('data-amount', service_array[h]['amount']).attr('value', service_array[h]['id']).text(service_array[h]['service_name']).appendTo(optgroup);
                                    
                                        // if(response.service[k]["id"] != null && service_array[h]['id'] == response.service[k]["id"])
                                        // {
                                        //     option.attr('selected', 'selected');
                                        // }
                                    }
                                }
                            }
                            $("#form"+count_billing_service_info_num+" #service"+count_billing_service_info_num).append(optgroup);
                            $("#form"+count_billing_service_info_num+" #service"+count_billing_service_info_num).select2();

                            // $("#form"+count_billing_service_info_num+" #invoice_description").text(response.service[k]["invoice_description"]);
                            // $("#form"+count_billing_service_info_num+" #transaction_create_billing_amount").val(addCommas(response.service[k]["amount"]));
                            // $("#form"+count_billing_service_info_num+" #gst_new_way").val(addCommas(response.service[k]["gst_new_way"]));
                            // $("#form"+count_billing_service_info_num+" #gst_rate").val(addCommas(response.service[k]["rate"]));

                           
                            $('#transaction_create_billing_form').formValidation('addField', 'service['+count_billing_service_info_num+']', service);
                            $('#transaction_create_billing_form').formValidation('addField', 'invoice_description['+count_billing_service_info_num+']', invoice_description);
                            $('#transaction_create_billing_form').formValidation('addField', 'amount['+count_billing_service_info_num+']', amount);  
                            $('#transaction_create_billing_form').formValidation('addField', 'unit_pricing['+count_billing_service_info_num+']', validate_unit_pricing);  
                            count_billing_service_info_num++;

                            sum_first_total();
                        }
                    }
                    else
                    {
                        $('#create_billing_service').hide();
                        $('#body_create_billing').hide();
                        $('#sub_total_create_billing').hide();
                        $('#gst_create_billing').hide();
                        $('#grand_total_create_billing').hide();
                    }
                }

            }               
        });
        $('#transaction_create_billing_form').formValidation('revalidateField', 'client_name');
    }
}

$(document).on('change','#transaction_create_billing_form #currency',function(e){
    if($(this).val() == "1")
    {
        $("#rate").val("1.0000");
    }
    $('#transaction_create_billing_form').formValidation('revalidateField', 'currency');
});

function get_gst_rate(billing_date)
{
    $.ajax({
        type: 'POST',
        url: "billings/get_gst_rate",
        data: {"billing_date" : billing_date},
        dataType: 'json',
        asycn: false,
        success: function(response){
            if(response.Status == 1)
            {
                //assign gst
                $('#old_gst_rate').val(response.get_gst_rate);
                latest_gst_rate = response.get_gst_rate;

                sum_total();
            }
        }
    });
}

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

    var datatableIncorpBillingInit = function() {

        var table1 = $('#datatable-paid').DataTable({
            destroy: true,
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
    };

    $(function() {
        datatableIncorpBillingInit();
    });

}).apply( this, [ jQuery ]);

$(".open_client_billing").click(function(){
    //var company_code = $(this).data('code');
    //console.log(company_code);
    open_client_billing();
});

function open_client_billing() {
    showRow();
    $("#modal_billing").modal("show");                
}

function editBilling($billing_id){
    $('#loadingBilling').show();
    $.ajax({
        type: 'POST',
        url: "billings/get_edit_billing_info",
        data: {"billing_id":$billing_id, "transaction_task_id": transaction_task_id, "transaction_master_id": $("#transaction_trans #transaction_master_id").val()},
        dataType: 'json',
        success: function(response){
            if (response.Status === 1) 
            {
                $('#loadingBilling').hide();
                
                billing_top_info = response[0]['edit_bill'];
                billing_below_info = response[0]['edit_bill_service'];

                if(billing_top_info != undefined)
                {
                    $(".row_of_invoice_no").show();
                    $(".text_field_client_name").show();
                    $(".dropdown_client_name").hide();

                    if(billing_top_info[0]["postal_code"] != "" || billing_top_info[0]["street_name"] != "")
                    {
                        var units = " ";

                        if(billing_top_info[0]["unit_no1"] != "" || billing_top_info[0]["unit_no2"] != "")
                        {
                            units = '\n#'+billing_top_info[0]["unit_no1"] + " - " + billing_top_info[0]["unit_no2"]+' ';
                        }
                        else if(billing_top_info[0]["unit_no1"] != "")
                        {
                            units = billing_top_info[0]["unit_no1"]+' ';
                        }
                        else if(billing_top_info[0]["unit_no2"] != "")
                        {
                            units = billing_top_info[0]["unit_no2"]+' ';
                        }
                        else
                        {
                            units = '\n';
                        }
                        var nonedit_address = billing_top_info[0]["street_name"]+units+billing_top_info[0]["building_name"]+'\nSingapore '+billing_top_info[0]["postal_code"];
                    }
                    else
                    {
                        var nonedit_address = billing_top_info[0]["foreign_address"];
                    }

                    //$('[name="text_client_name"]').val(billing_top_info[0]["company_name"]);
                    if(transaction_task_id == "36")
                    {
                        $('[name="transaction_client_name"]').val(billing_top_info[0]["company_name"]);
                    }
                    $('[name="invoice_no"]').val(billing_top_info[0]["invoice_no"]);
                    $('[name="previous_invoice_no"]').val(billing_top_info[0]["invoice_no"]);
                    $('[name="billing_date"]').val(billing_top_info[0]["invoice_date"]);
                    $('[name="address"]').val(nonedit_address);
                    $('[name="rate"]').val(billing_top_info[0]["rate"]);

                    $('[name="billing_date"]').attr('disabled', true);
                    $('[name="address"]').attr('disabled', true);
                    $(".input_client_name").show();
                    //$('.text_client_name').attr('disabled', true);

                    if(billing_top_info[0]['own_letterhead_checkbox'] == 0)
                    {
                        state_own_letterhead_checkbox = false;
                        $("[name='hidden_own_letterhead_checkbox']").val(billing_top_info[0]['own_letterhead_checkbox']);
                    }
                    else
                    {
                        state_own_letterhead_checkbox = true;
                        $("[name='hidden_own_letterhead_checkbox']").val(billing_top_info[0]['own_letterhead_checkbox']);
                    }

                }
                //console.log(billing_below_info.length);
                if(billing_below_info != undefined)
                {
                    if(billing_below_info[0]["gst_new_way"] == 0)
                    {
                        $('#old_gst_rate').val(billing_below_info[0]["gst_rate"]);
                    }
                    else
                    {
                        $('#old_gst_rate').val("false");
                    }
                    
                    $("#body_create_billing").empty();
                    $('#create_billing_service').show();
                    $('#body_create_billing').show();
                    $('#sub_total_create_billing').show();
                    $('#gst_create_billing').show();
                    $('#grand_total_create_billing').show();

                    //assign gst
                    $('#gst_rate').val(billing_below_info[0]["gst_rate"]);
                    //$('#services_company_code').val(billing_below_info[0]["company_code"]);

                    count_billing_service_info_num = billing_below_info.length;
                    service_category_array = response[0]['get_service_category'];
                    service_array = response[0]['get_client_billing_info'];
                    unit_pricing = response[0]["get_unit_pricing"];
                    
                    for(var t = 0; t < billing_below_info.length; t++)
                    {
                        $count_billing_service_info = t;
                        $a=""; 
                        $a += '<div class="tr editing tr_billing" method="post" name="form'+$count_billing_service_info+'" id="form'+$count_billing_service_info+'" num="'+$count_billing_service_info+'">';
                        $a += '<div class="hidden"><input type="text" class="form-control" name="company_code" value="'+billing_below_info[t]["company_code"]+'"/></div>';
                        $a += '<div class="hidden"><input type="text" class="form-control" name="billing_service_id" value="'+billing_below_info[t]["billing_service_id"]+'"/></div>';
                        $a += '<div class="hidden"><input type="text" class="form-control" name="client_billing_info_id['+$count_billing_service_info+']" id="client_billing_info_id" value="'+billing_below_info[t]["client_billing_info_id"]+'"/></div>';
                        $a += '<div class="hidden"><input type="text" class="form-control" name="claim_service_id['+$count_billing_service_info+']" id="claim_service_id" value=""/></div>';
                        //$a += '<div class="td" style="width: 150px;"><div class="select-input-group"><select class="input-sm form-control service" name="service['+$count_billing_service_info+']" id="service'+$count_billing_service_info+'" style="width:200px !important;"><option value="0" data-invoice_description="" data-amount="">Select Service</option></select><input type="hidden" class="form-control" name="payment_voucher_type['+$count_billing_service_info+']" id="payment_voucher_type" value=""/><div id="form_service"></div></div></div>';
                        $a += '<div class="td"><div class="select-input-group mb-md"><select class="input-sm form-control service" name="service['+$count_billing_service_info+']" id="service'+$count_billing_service_info+'" style="width:200px;"><option value="0" data-invoice_description="" data-amount="">Select Service</option></select><input type="hidden" class="form-control" name="payment_voucher_type['+$count_billing_service_info+']" id="payment_voucher_type" value=""/></div><div class="input-group mb-md"><div style="font-weight: bold; margin-bottom: 5px;">Progress Billing</div><div class="form-check form-check-inline"><input class="form-check-input progress_billing_yes" type="radio" name="progress_billing_yes_no['+$count_billing_service_info+']" id="progress_billing_yes" value="yes"><label class="form-check-label" for="progress_billing_yes">Yes</label></div><div class="form-check form-check-inline"><input class="form-check-input progress_billing_no" type="radio" name="progress_billing_yes_no['+$count_billing_service_info+']" id="progress_billing_no" value="no"><label class="form-check-label" for="progress_billing_no">No</label></div></div><div class="poc_percent_div input-group mb-md" style="display:none"><div class="form-check"><input class="form-control form-check-input poc_percentage" style="width:50px; margin-right:1px;" type="text" name="poc_percentage['+$count_billing_service_info+']" id="poc_percentage" value="'+((billing_below_info[t]["poc_percentage"] != null)?billing_below_info[t]["poc_percentage"]:"")+'"><label class="form-check-label" style="margin-top: 7px;" for="poc_percentage">% of <span class="number_of_percent_poc">'+billing_below_info[t]["number_of_percent_poc"]+'</span></label><input type="hidden" class="hidden_number_of_percent_poc" name="hidden_number_of_percent_poc['+$count_billing_service_info+']" value="'+billing_below_info[t]["number_of_percent_poc"]+'"></div></div></div>';
                        //$a += '<div class="td"><div class="input-group mb-md"><textarea class="form-control" name="invoice_description['+$count_billing_service_info+']"  id="invoice_description" rows="3" style="width:420px">'+billing_below_info[t]["invoice_description"]+'</textarea></div><div style="width: 200px;display: inline-block; margin-right:10px;"><div style="font-weight: bold;">Period Start Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker period_start_date" id="period_start_date" name="period_start_date['+$count_billing_service_info+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value="'+billing_below_info[t]["period_start_date"]+'"></div></div><div style="width: 200px;display: inline-block"><div style="font-weight: bold;">Period End Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker period_end_date" id="period_end_date" name="period_end_date['+$count_billing_service_info+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value="'+billing_below_info[t]["period_end_date"]+'"></div></div></div>';
                        $a += '<div class="td"><div class="input-group mb-md"><textarea class="form-control" name="invoice_description['+$count_billing_service_info+']"  id="invoice_description" rows="3" style="width:420px">'+billing_below_info[t]["invoice_description"]+'</textarea></div><div class="period_class" style="width: 200px;display: inline-block; margin-right:10px;"><div style="font-weight: bold;">Period Start Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"/></span><input type="text" class="form-control datepicker period_start_date" id="period_start_date" name="period_start_date['+$count_billing_service_info+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value="'+billing_below_info[t]["period_start_date"]+'"></div></div><div class="period_class" style="width: 200px;display: inline-block"><div style="font-weight: bold;">Period End Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"/></span><input type="text" class="form-control datepicker period_end_date" id="period_end_date" name="period_end_date['+$count_billing_service_info+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value="'+billing_below_info[t]["period_end_date"]+'"></div></div><div class="input-group mb-md"><div class="type_reading_quantity_class" style="font-weight: bold; margin-bottom: 5px; display:none;">Type</div><div class="form-check form-check-inline rate_class" style="display:none"><input class="form-check-input radio_reading" type="radio" name="radio_quantity_reading['+$count_billing_service_info+']" id="radio_reading" value="reading"><label class="form-check-label" for="radio_reading">Reading</label></div><div class="form-check form-check-inline rate_class" style="display:none"><input class="form-check-input radio_quantity" type="radio" name="radio_quantity_reading['+$count_billing_service_info+']" id="radio_quantity" value="quantity"><label class="form-check-label" for="radio_quantity">Quantity</label></div></div><div class="quantity" style="width: 200px;display: none; margin-right:10px;"><div style="font-weight: bold;">Quantity</div><div class="input-group" style="width: 100%"><input type="text" class="form-control border_radius quantity_value" id="quantity_value" name="quantity_value['+$count_billing_service_info+']" value="'+((billing_below_info[t]["quantity_value"] != null)?billing_below_info[t]["quantity_value"]:"")+'"/></div></div><div class="rate_class reading" style="width: 200px;display: none; margin-right:10px;"><div style="font-weight: bold;">Reading at beginning</div><div class="input-group" style="width: 100%"><input type="text" class="form-control border_radius reading_at_begin" id="reading_at_begin" name="reading_at_begin['+$count_billing_service_info+']" value="'+((billing_below_info[t]["reading_at_begin"] != null)?billing_below_info[t]["reading_at_begin"]:"")+'"/></div></div><div class="rate_class reading" style="width: 200px;display: none"><div style="font-weight: bold;">Reading at the end</div><div class="input-group" style="width: 100%"><input type="text" class="form-control border_radius reading_at_the_end" id="reading_at_the_end" name="reading_at_the_end['+$count_billing_service_info+']" value="'+((billing_below_info[t]["reading_at_the_end"] != null)?billing_below_info[t]["reading_at_the_end"]:"")+'"/></div></div><div class="rate_class" style="width: 200px;display: none; margin-right:10px;"><div style="font-weight: bold;">Rate</div><div class="input-group" style="width: 100%"><input type="text" class="form-control border_radius number_of_rate" id="number_of_rate" name="number_of_rate['+$count_billing_service_info+']" value="'+((billing_below_info[t]["number_of_rate"] != null)?billing_below_info[t]["number_of_rate"]:"")+'"/></div></div><div class="rate_class" style="width: 200px;display: none"><div style="font-weight: bold;">Measurement Unit</div><div class="input-group" style="width: 100%"><input type="text" class="form-control border_radius unit_for_rate" id="unit_for_rate" name="unit_for_rate['+$count_billing_service_info+']" value="'+((billing_below_info[t]["unit_for_rate"] != null)?billing_below_info[t]["unit_for_rate"]:"")+'"/></div></div></div>';
                        $a += '<div class="td" style="width:150px"><input type="hidden" name="gst_category_id[]" class="form-control gst_category_id" id="gst_category_id" value="'+billing_below_info[t]["gst_category_id"]+'"/><input type="hidden" name="gst_new_way[]" class="form-control gst_new_way" id="gst_new_way" value="'+billing_below_info[t]["gst_new_way"]+'"/><input type="hidden" name="gst_rate[]" class="form-control gst_rate" id="gst_rate" value="'+billing_below_info[t]["gst_rate"]+'"/><div class="input-group"><input type="text" name="amount['+$count_billing_service_info+']" class="numberdes form-control text-right transaction_create_billing_amount" value="'+addCommas(billing_below_info[t]["billing_service_amount"])+'" id="transaction_create_billing_amount" style="width:150px"/><div id="form_amount"></div></div></div>';
                        $a += '<div class="td" style="width:150px"><div class="select-input-group"><select class="form-control" style="text-align:right;width: 165px;" name="unit_pricing['+$count_billing_service_info+']" id="unit_pricing"><option value="0" >Select Unit Pricing</option></select></div></div>';
                        $a += '<div class="td action"><button type="button" class="btn btn-primary delete_billing_button" onclick="delete_billing(this)" style="display: none;">Delete</button></div>';
                        $a += '</div>';

                        /*<input type="button" value="Save" id="save" name="save'+$count_officer+'" class="btn btn-primary" onclick="save(this);">*/
                        $("#body_create_billing").append($a); 

                        $("#form"+$count_billing_service_info+" #claim_service_id").attr('value', billing_below_info[t]["claim_service_id"]);
                        //console.log($("#body_create_billing > div").length);
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
                            console.log(billing_below_info[t]["unit_pricing"]);

                            var option = $('<option />');
                            option.attr('value', val['id']).text(val['unit_pricing_name']);
                            
                            if(billing_below_info[t]["unit_pricing"] != null && val['id'] == billing_below_info[t]["unit_pricing"])
                            {
                                option.attr('selected', 'selected');
                            }

                            $("#form"+$count_billing_service_info+" #unit_pricing").append(option);
                        });
                        
                        if(billing_below_info[t]["progress_billing_yes_no"] == "yes")
                        {
                            $("#form"+$count_billing_service_info+" .poc_percent_div").show();
                            $("#form"+$count_billing_service_info+" #progress_billing_yes").prop("checked", true);
                        }
                        else if(billing_below_info[t]["progress_billing_yes_no"] == "no")
                        {
                            $("#form"+$count_billing_service_info+" .poc_percent_div").hide();
                            $("#form"+$count_billing_service_info+" #progress_billing_no").prop("checked", true);
                        }

                        var category_description = '';
                        var optgroup = '';

                        for(var j = 0; j < service_category_array.length; j++)
                        {
                            if(category_description != service_category_array[j]['category_description'])
                            {
                                if(optgroup != '')
                                {
                                    $("#form"+$count_billing_service_info+" #service"+$count_billing_service_info).append(optgroup);
                                }
                                optgroup = $('<optgroup label="' + service_category_array[j]['category_description'] + '" />');
                            }

                            category_description = service_category_array[j]['category_description'];
                            
                            for(var h = 0; h < service_array.length; h++)
                            {
                                if(category_description == service_array[h]['category_description'])
                                {
                                    //console.log(service_array[h]['service_name']);
                                    var option = $('<option />');
                                    option.attr('data-gst_category_id', service_array[h]['gst_category_id']).attr('data-calculate_by_quantity_rate', service_array[h]['calculate_by_quantity_rate']).attr('data-gst_new_way', service_array[h]['gst_new_way']).attr('data-rate', service_array[h]['rate']).attr('data-our_service_id', service_array[h]['service']).attr('data-description', service_array[h]['invoice_description']).attr('data-currency', service_array[h]['currency']).attr('data-unit_pricing', service_array[h]['unit_pricing']).attr('data-amount', service_array[h]['amount']).attr('value', service_array[h]['id']).text(service_array[h]['service_name']);
                                    if(service_array[h]['deleted'] == 0)
                                    {
                                        option.appendTo(optgroup);
                                    }
                                    if(billing_below_info[t]["service"] != null && service_array[h]['id'] == billing_below_info[t]["service"])
                                    {
                                        if(service_array[h]['deleted'] == 1)
                                        {
                                            option.appendTo(optgroup);
                                        }
                                        option.attr('selected', 'selected');
                                        if(service_array[h]['calculate_by_quantity_rate'] == "1")
                                        {
                                            $("#form"+$count_billing_service_info+" .period_class").hide();
                                            $("#form"+$count_billing_service_info+" .rate_class").css('display', 'inline-block');
                                            $("#form"+$count_billing_service_info+" .type_reading_quantity_class").css('display', 'block');

                                            if(billing_below_info[t]["radio_quantity_reading"] == "quantity")
                                            {
                                                $("#form"+$count_billing_service_info+" .quantity").css('display', 'inline-block');
                                                $("#form"+$count_billing_service_info+" .reading").hide();
                                                $("#form"+$count_billing_service_info+" #radio_quantity").prop("checked", true);
                                            }
                                            else if(billing_below_info[t]["radio_quantity_reading"] == "reading")
                                            {
                                                $("#form"+$count_billing_service_info+" .quantity").hide();
                                                $("#form"+$count_billing_service_info+" .reading").css('display', 'inline-block');
                                                $("#form"+$count_billing_service_info+" #radio_reading").prop("checked", true);
                                            }
                                        }
                                        else if(service_array[h]['calculate_by_quantity_rate'] == "2")
                                        {
                                            $("#form"+$count_billing_service_info+" .period_class").css('display', 'inline-block');
                                            $("#form"+$count_billing_service_info+" .rate_class").hide();
                                            $("#form"+$count_billing_service_info+" .type_reading_quantity_class").hide();
                                            $("#form"+$count_billing_service_info+" #radio_reading").prop("checked", true);
                                        }
                                    }
                                }
                            }
                        }
                        $("#form"+t+" #service"+t).append(optgroup);
                        $("#form"+t+" #service"+t).select2();

                        // service_array = service_dropdown;
                        // service_category_array = service_category;
                        // unit_pricing = unit_pricing;

                        $('#create_billing_form').formValidation('addField', 'service['+$count_billing_service_info+']', service);
                        $('#create_billing_form').formValidation('addField', 'invoice_description['+$count_billing_service_info+']', invoice_description);
                        $('#create_billing_form').formValidation('addField', 'amount['+$count_billing_service_info+']', amount);
                        $('#create_billing_form').formValidation('addField', 'unit_pricing['+$count_billing_service_info+']', validate_unit_pricing);
                    }
                    sum_first_total();
                }
                $("#modal_billing").modal("show"); 
            }
        }
    });
}



