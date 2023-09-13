var pathArray = location.href.split( '/' );
var protocol = pathArray[0];
var host = pathArray[2];
var folder = pathArray[3];

var latest_gst_rate = 0, count_billing_service_info_num = 0, tmp = [], total_claim_amount = 0, arr_for_check_no_assignment = [];
var state_own_letterhead_checkbox = true;

$('#create_billing_form').formValidation({
    framework: 'bootstrap',
    icon: {
        /*valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'*/
    },
    // This option will not ignore invisible fields which belong to inactive panels
    //excluded: ':disabled',
    excluded: [':disabled', ':hidden', ':not(:visible)'],
    //ignore: [':disabled', ':hidden', ':not(:visible)'],
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
        client_name: {
            row: '.input-group',
            validators: {
                callback: {
                    message: 'The Client Name field is required.',
                    callback: function(value, validator, $field) {
                        var options = validator.getFieldElements('client_name').val();
                        //console.log(options);
                        return (options != null && options != "0");
                    }
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
                        //console.log(options);
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
        excluded: [':disabled', ':hidden', ':not(:visible)'],
        row: '.select-input-group',
        validators: {
            callback: {
                message: 'The Service field is required.',
                callback: function(value, validator, $field) {
                    var num = jQuery($field).parent().parent().parent().attr("num");
                    var options = validator.getFieldElements('service['+num+']').val();
                    //console.log(options);
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
                    //console.log(options);
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
                    else if(key == firm_info[0]["firm_currency"] && data.selected_currency == null)
                    {
                        option.attr('selected', 'selected');
                    }
                    $('.currency').append(option);
                });
            }
            else{
                alert(data.msg);
            }
        }); 
    };

    this.getClientName = function() {
        var url = base_url+"/"+folder+"/"+'companytype/getClientName';
        var method = "get";
        var data = {};
        $('.client_name').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            $('.client_name').find("option:eq(0)").html("Select Client Name");
            if(data.tp == 1){
                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    if(data.selected_client_name != null && key == data.selected_client_name)
                    {
                        option.attr('selected', 'selected');
                        $('.client_name').attr('disabled', true);
                    }
                    $('.client_name').append(option);
                });
                $('#client_name').select2();
            }
            else{
                alert(data.msg);
            }
        }); 
    };
}

$(function() {
    var cn = new Client();
    cn.getClientName();
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

  return day + '/' + monthNames[monthIndex] + '/' + year;
}

$(document).ready(function() {
    $('#loadingBilling').hide();
});

$('.billing_date').datepicker({ 
    dateFormat:'dd/mm/yyyy',
    autoclose: true,
}).datepicker("setDate", "0")
.datepicker('setStartDate', "01/01/1920")
.on('changeDate', function (selected) {
    //console.log($('.billing_date').val());
    var billing_date = $('.billing_date').val();
    get_gst_rate(billing_date);
    
    $('#create_billing_form').formValidation('revalidateField', 'billing_date');
});

if(billing_top_info == undefined)
{
    $.ajax({
        type: "GET",
        url: "billings/get_invoice_no",
        asycn: false,
        //data: {"company_code":company_code}, // <--- THIS IS THE CHANGE
        dataType: "json",
        success: function(response){
            //$(".tr_billing").remove();
            //console.log(response);
            $('[name="invoice_no"]').val(response.invoice_no);
        }
    });

     $('#rate').val("1.0000");

    var d = new Date();

    var today_date = formatDateFunc(d);
    get_gst_rate(today_date);

    $("[name='hidden_own_letterhead_checkbox']").val(1);
    $(".dropdown_client_name").show();
    $('.text_client_name').attr('disabled', true);
}
else
{
    // if(billing_top_info[0]["use_foreign_add_as_billing_add"] == 1)
    // {
    //     if(billing_top_info[0]["foreign_add_1"] != "")
    //     {
    //         var comma1 = billing_top_info[0]["foreign_add_1"] + '\n';
    //     }
    //     else
    //     {
    //         var comma1 = '';
    //     }

    //     if(billing_top_info[0]["foreign_add_2"] != "")
    //     {
    //         var comma2 = comma1 + billing_top_info[0]["foreign_add_2"] + '\n';
    //     }
    //     else
    //     {
    //         var comma2 = comma1 + '';
    //     }
    //     var nonedit_address = comma2 + billing_top_info[0]["foreign_add_3"];
    // }
    // else
    // {
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
            if(billing_top_info[0]["foreign_address2"] != "")
            {
                var value_foreign_address2 = '\n' + billing_top_info[0]["foreign_address2"];
            }
            else
            {
                var value_foreign_address2 = '';
            }
            var foreign_address = billing_top_info[0]["foreign_address1"] + value_foreign_address2 + '\n' + billing_top_info[0]["foreign_address3"];
            var nonedit_address = foreign_address;
        }
    //}
    //$('[name="client_name"]').val(billing_top_info[0]["company_code"]);
    $('[name="text_client_name"]').val(billing_top_info[0]["company_name"]);
    $('[name="invoice_no"]').val(billing_top_info[0]["invoice_no"]);
    $('[name="previous_invoice_no"]').val(billing_top_info[0]["invoice_no"]);
    $('[name="billing_date"]').val(billing_top_info[0]["invoice_date"]);
    $('[name="address"]').val(nonedit_address);
    $('[name="rate"]').val(billing_top_info[0]["rate"]);

    //$('[name="invoice_no"]').attr('disabled', true);
    $('[name="billing_date"]').attr('disabled', true);
    $('[name="address"]').attr('disabled', true);
    $(".input_client_name").show();
    $('.text_client_name').attr('disabled', true);

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

$("[name='own_letterhead_checkbox']").bootstrapSwitch({
    state: state_own_letterhead_checkbox,
    size: 'normal',
    onColor: 'primary',
    onText: 'YES',
    offText: 'NO',
    // Text of the center handle of the switch
    labelText: '&nbsp',
    // Width of the left and right sides in pixels
    handleWidth: '75px',
    // Width of the center handle in pixels
    labelWidth: 'auto',
    baseClass: 'bootstrap-switch',
    wrapperClass: 'wrapper'


});

// Triggered on switch state change.
$("[name='own_letterhead_checkbox']").on('switchChange.bootstrapSwitch', function(event, state) {
    //console.log(state); // true | false

    if(state == true)
    {
        $("[name='hidden_own_letterhead_checkbox']").val(1);
    }
    else
    {
        $("[name='hidden_own_letterhead_checkbox']").val(0);
    }
});


var service_array = "";
var service_category_array = "";
var unit_pricing = "";



/*$('#create_billing_form #service').on('change',function(e){

    var selected_invoice_description = $(this).find(':selected').data('invoice_description');
    var selected_amount = $(this).find(':selected').data('amount');
    $(this).parent().parent().parent().find('#invoice_description').val(selected_invoice_description);
    $(this).parent().parent().parent().find('#amount').val(addCommas(selected_amount));

    sum_total();

    //$('#form_receipt').formValidation('revalidateField', 'received['+key+']');  
});*/

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

$(".amount").live('change',function(){
    $(this).parent().parent().parent().parent().find('.number_of_percent_poc').text($(this).val());
    $(this).parent().parent().parent().parent().find('.hidden_number_of_percent_poc').val($(this).val());

    var amountValue = $(this).val();
    var poc_percentage_val = $(this).parent().parent().parent().parent().find('.poc_percentage').val();
    if(poc_percentage_val != "")
    {
        var after_poc_amount = (parseFloat(poc_percentage_val)/100)*parseFloat(amountValue.replace(/\,/g,''),2);
        $(this).parent().parent().parent().parent().find('#amount').val(addCommas(after_poc_amount.toFixed(2)));
    }
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
        $(this).parent().parent().parent().parent().find('#amount').val(addCommas(after_poc_amount.toFixed(2)));

        sum_total();
    }
    else
    {
        toastr.error("The percentage cannot be null.", "Error");
    }
    
});

function sum_total(){
    var sum = 0;
    var before_gst = 0, gst = 0, gst_rate = 0, grand_total = 0, gst_with_rate = 0;
    $(".amount").each(function(){
        //console.log($(this).val() == '');
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
                if($("#old_gst_rate").val() != "false")
                {
                    gst_rate = $("#old_gst_rate").val();
                }
                else
                {
                    //gst_rate = $(this).parent().parent().parent().find(':selected').data('rate');
                    gst_rate = $(this).parent().parent().parent().find('.gst_rate').val();
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
            //console.log("total==="+before_gst);
            gst += parseFloat(before_gst.toFixed(2));
        }
    });
    //$(".total").val(sum);
   // console.log(billing_below_info[0]["currency_id"]);
    $("#sub_total").text(addCommas(sum.toFixed(2)));
    //console.log($(".currency").val());

    
    if($(".currency").val() == "0" || $(".currency").val() == firm_info[0]["firm_currency"] || gst == "0.00")
    {
        gst_with_rate = " ";
        $("#gst_with_rate").text(gst_with_rate);
    }
    else if($(".currency").val() != firm_info[0]["firm_currency"])
    {
        gst_with_rate = gst * parseFloat($(".rate").val());
        $("#gst_with_rate").text("( "+ firm_info[0]["currency_name"] +addCommas(gst_with_rate.toFixed(2))+" )");
    }

    /*if(billing_below_info)
    {
        gst_rate = billing_below_info[0]['gst_rate'];

        before_gst = ((gst_rate / 100) * billing_below_info[t]["billing_service_amount"]);
        //console.log("total==="+before_gst);
        gst += parseFloat(before_gst.toFixed(2));
    }*/
    

    /*for(var t = 0; t < billing_below_info.length; t++)
    {
        before_gst = ((billing_below_info[t]['gst_rate'] / 100) * billing_below_info[t]["billing_service_amount"]);
        //console.log("total==="+before_gst);
        gst += parseFloat(before_gst.toFixed(2));
    }*/

    $("#gst").text(addCommas(gst.toFixed(2)));
    grand_total = sum + gst;
    $("#grand_total").text(addCommas(grand_total.toFixed(2)));
    //number_format((sum + round(gst, 2)),2)
    $("input[id=hidden_grand_total]").val(addCommas(grand_total.toFixed(2)));
    
}

function sum_first_total(){
    var sum = 0;
    var before_gst = 0, gst = 0, gst_rate = 0, grand_total = 0, gst_with_rate = 0;
    $(".amount").each(function(){
        //console.log($(this).val() == '');
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
                // gst_rate = billing_below_info[0]['gst_rate'];
                if($("#old_gst_rate").val() != "false")
                {
                    gst_rate = $("#old_gst_rate").val();
                }
                else
                {
                    gst_rate = $(this).parent().parent().parent().find('.gst_rate').val();
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
            //console.log(gst_rate);
            gst += parseFloat(before_gst.toFixed(2));
        }
    });
    //$(".total").val(sum);
    //console.log(gst);
    $("#sub_total").text(addCommas(sum.toFixed(2)));
    //console.log($(".currency").val());

    // if(billing_below_info[0]["currency_id"] == "1")
    // {
    //     gst_with_rate = " ";
    //     $("#gst_with_rate").text(gst_with_rate);
    // }
    // else if(billing_below_info[0]["currency_id"] != "1")
    // {
    //     gst_with_rate = gst * parseFloat(billing_below_info[0]["rate"]);
    //     $("#gst_with_rate").text("( SGD"+addCommas(gst_with_rate.toFixed(2))+" )");
    // }

    if(billing_below_info)
    {
        if(billing_below_info[0]["currency_id"] == firm_info[0]["firm_currency"])
        {
            gst_with_rate = " ";
            $("#gst_with_rate").text(gst_with_rate);
        }
        else if(billing_below_info[0]["currency_id"] != firm_info[0]["firm_currency"])
        {
            gst_with_rate = gst * parseFloat(billing_below_info[0]["rate"]);
            $("#gst_with_rate").text("( "+ firm_info[0]["currency_name"] +addCommas(gst_with_rate.toFixed(2))+" )");
        }
    }
    else
    {
        if($("#currency").val() == firm_info[0]["firm_currency"])//firm_info[0]["firm_currency"]
        {
            gst_with_rate = " ";
            $("#gst_with_rate").text(gst_with_rate);
        }
        else if($("#currency").val() != firm_info[0]["firm_currency"])
        {
            gst_with_rate = gst * parseFloat($("#rate").val());
            $("#gst_with_rate").text("( "+ firm_info[0]["currency_name"] +addCommas(gst_with_rate.toFixed(2))+" )");
        }
    }
    //console.log(gst);
    $("#gst").text(addCommas(gst.toFixed(2)));
    grand_total = sum + gst;
    $("#grand_total").text(addCommas(grand_total.toFixed(2)));
    //number_format((sum + round(gst, 2)),2)
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
    /*if(confirm("Delete This Record?"))
    {*/
        var tr = jQuery(element).parent().parent(),
            billing_service_id = tr.find('input[name="billing_service_id[]"]').val();
        //console.log(billing_service_id);

        /*$.ajax({
            type: "POST",
            url: "masterclient/delete_billing_service",
            data: {"billing_service_id":billing_service_id}, // <--- THIS IS THE CHANGE
            dataType: "json",
            success: function(response){
                console.log(response);
            }               
        });*/
        tr.closest("DIV.tr").remove();
        //console.log($("#allotment_add > div").length);
        if($("#body_create_billing > div").length == 1)
        {
            if($('.delete_billing_button').css('display') == 'block')
            {
                $('.delete_billing_button').css('display','none');
            }
        }
        sum_total();
    //}
}

if(billing_below_info != undefined)
{
    $('#create_billing_service').show();
    $('#body_create_billing').show();
    $('#sub_total_create_billing').show();
    $('#gst_create_billing').show();
    $('#grand_total_create_billing').show();

    //assign gsts
    if(billing_below_info[0]["gst_new_way"] == 0)
    {
        $('#old_gst_rate').val(billing_below_info[0]["gst_rate"]);
    }
    else
    {
        $('#old_gst_rate').val("false");
    }
    $('#services_company_code').val(billing_below_info[0]["company_code"]);
    count_billing_service_info_num = billing_below_info.length;
    for(var t = 0; t < billing_below_info.length; t++)
    {
        $count_billing_service_info = t;
        $a=""; 
        //console.log(billing_below_info[t]["claim_service_id"]);
        //var claim_service_id_result = billing_below_info[t]["claim_service_id"];
        /*<select class="input-sm" style="text-align:right;width: 150px;" id="position" name="position[]" onchange="optionCheck(this);"><option value="Director" >Director</option><option value="CEO" >CEO</option><option value="Manager" >Manager</option><option value="Secretary" >Secretary</option><option value="Auditor" >Auditor</option><option value="Managing Director" >Managing Director</option><option value="Alternate Director">Alternate Director</option></select>*/
        $a += '<div class="tr editing tr_billing" method="post" name="form'+$count_billing_service_info+'" id="form'+$count_billing_service_info+'" num="'+$count_billing_service_info+'">';
        $a += '<div class="hidden"><input type="text" class="form-control" name="company_code" value=""/></div>';
        $a += '<div class="hidden"><input type="text" class="form-control" name="billing_service_id" value="'+billing_below_info[t]["billing_service_id"]+'"/></div>';
        $a += '<div class="hidden"><input type="text" class="form-control" name="client_billing_info_id['+$count_billing_service_info+']" id="client_billing_info_id" value="'+billing_below_info[t]["client_billing_info_id"]+'"/></div>';
        $a += '<div class="hidden"><input type="text" class="form-control" name="claim_service_id['+$count_billing_service_info+']" id="claim_service_id" value=""/></div>';
        //$a += '<div class="td" style="width: 150px;"><div class="select-input-group"><select class="input-sm form-control service" name="service['+$count_billing_service_info+']" id="service'+$count_billing_service_info+'" style="width:200px !important;"><option value="0" data-invoice_description="" data-amount="">Select Service</option></select><input type="hidden" class="form-control" name="payment_voucher_type['+$count_billing_service_info+']" id="payment_voucher_type" value=""/><div id="form_service"></div></div></div>';
        //$a += '<div class="td"><div class="select-input-group mb-md"><select class="input-sm form-control service" name="service['+$count_billing_service_info+']" id="service'+$count_billing_service_info+'" style="width:200px;"><option value="0" data-invoice_description="" data-amount="">Select Service</option></select><input type="hidden" class="form-control" name="payment_voucher_type['+$count_billing_service_info+']" id="payment_voucher_type" value=""/></div><div class="input-group mb-md"><div style="font-weight: bold; margin-bottom: 5px;">Progress Billing</div><div class="form-check form-check-inline"><input class="form-check-input progress_billing_yes" type="radio" name="progress_billing_yes_no['+$count_billing_service_info+']" id="progress_billing_yes" value="yes"><label class="form-check-label" for="progress_billing_yes">Yes</label></div><div class="form-check form-check-inline"><input class="form-check-input progress_billing_no" type="radio" name="progress_billing_yes_no['+$count_billing_service_info+']" id="progress_billing_no" value="no"><label class="form-check-label" for="progress_billing_no">No</label></div></div><div class="poc_percent_div input-group mb-md" style="display:none"><div class="form-check"><input class="form-control form-check-input poc_percentage" style="width:50px; margin-right:1px;" type="text" name="poc_percentage['+$count_billing_service_info+']" id="poc_percentage" value="'+((billing_below_info[t]["poc_percentage"] != null)?billing_below_info[t]["poc_percentage"]:"")+'"><label class="form-check-label" style="margin-top: 7px;" for="poc_percentage">% of <span class="number_of_percent_poc">'+billing_below_info[t]["number_of_percent_poc"]+'</span></label><input type="hidden" class="hidden_number_of_percent_poc" name="hidden_number_of_percent_poc['+$count_billing_service_info+']" value="'+billing_below_info[t]["number_of_percent_poc"]+'"></div></div></div>';
        //$a += '<div class="td"><div class="input-group mb-md"><textarea class="form-control" name="invoice_description['+$count_billing_service_info+']"  id="invoice_description" rows="3" style="width:420px">'+billing_below_info[t]["invoice_description"]+'</textarea></div><div style="width: 200px;display: inline-block; margin-right:10px;"><div style="font-weight: bold;">Period Start Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker period_start_date" id="period_start_date" name="period_start_date['+$count_billing_service_info+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value="'+billing_below_info[t]["period_start_date"]+'"></div></div><div style="width: 200px;display: inline-block"><div style="font-weight: bold;">Period End Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker period_end_date" id="period_end_date" name="period_end_date['+$count_billing_service_info+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value="'+billing_below_info[t]["period_end_date"]+'"></div></div></div>';
        $a += '<div class="td"><div class="select-input-group mb-md"><select class="input-sm form-control service" name="service['+$count_billing_service_info+']" id="service'+$count_billing_service_info+'" style="width:200px;"><option value="0" data-invoice_description="" data-amount="">Select Service</option></select><input type="hidden" class="form-control" name="payment_voucher_type['+$count_billing_service_info+']" id="payment_voucher_type" value=""/></div><div class="input-group mb-md"><div style="font-weight: bold; margin-bottom: 5px;">Progress Billing</div><div class="form-check form-check-inline"><input class="form-check-input progress_billing_yes" type="radio" name="progress_billing_yes_no['+$count_billing_service_info+']" id="progress_billing_yes" value="yes"><label class="form-check-label" for="progress_billing_yes">Yes</label></div><div class="form-check form-check-inline"><input class="form-check-input progress_billing_no" type="radio" name="progress_billing_yes_no['+$count_billing_service_info+']" id="progress_billing_no" value="no" checked><label class="form-check-label" for="progress_billing_no">No</label></div></div><div class="poc_percent_div input-group mb-md" style="display:none"><div class="form-check"><input class="form-control form-check-input poc_percentage" style="width:50px; margin-right:1px;" type="text" name="poc_percentage['+$count_billing_service_info+']" id="poc_percentage" value="'+((billing_below_info[t]["poc_percentage"] != null)?billing_below_info[t]["poc_percentage"]:"")+'"><label class="form-check-label" style="margin-top: 7px;" for="poc_percentage">% of <span class="number_of_percent_poc">'+billing_below_info[t]["number_of_percent_poc"]+'</span></label><input type="hidden" class="hidden_number_of_percent_poc" name="hidden_number_of_percent_poc['+$count_billing_service_info+']" value="'+billing_below_info[t]["number_of_percent_poc"]+'"></div></div><div class="input-group mb-md"><div style="font-weight: bold; margin-bottom: 5px;">Assignment</div><div class="form-check form-check-inline"><input class="form-check-input assignment_yes" type="radio" name="assignment_yes_no['+$count_billing_service_info+']" id="assignment_yes" value="yes"><label class="form-check-label" for="assignment_yes">Yes</label></div><div class="form-check form-check-inline"><input class="form-check-input assignment_no" type="radio" name="assignment_yes_no['+$count_billing_service_info+']" id="assignment_no" value="no" checked><label class="form-check-label" for="assignment_no">No</label></div></div><div class="assignment_div mb-md" style="display:none"><div class="form-check"><select class="input-sm form-control assignment" name="assignment['+$count_billing_service_info+']" id="assignment'+$count_billing_service_info+'" style="width:200px;"><option value="0">Select Assignment</option></select></div></div></div>';
        $a += '<div class="td"><div class="input-group mb-md"><textarea class="form-control" name="invoice_description['+$count_billing_service_info+']"  id="invoice_description" rows="3" style="width:420px">'+billing_below_info[t]["invoice_description"]+'</textarea></div><div class="period_class" style="width: 200px;display: inline-block; margin-right:10px;"><div style="font-weight: bold;">Period Start Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"/></span><input type="text" class="form-control datepicker period_start_date" id="period_start_date" name="period_start_date['+$count_billing_service_info+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value="'+billing_below_info[t]["period_start_date"]+'"></div></div><div class="period_class" style="width: 200px;display: inline-block"><div style="font-weight: bold;">Period End Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"/></span><input type="text" class="form-control datepicker period_end_date" id="period_end_date" name="period_end_date['+$count_billing_service_info+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value="'+billing_below_info[t]["period_end_date"]+'"></div></div><div class="input-group mb-md"><div class="type_reading_quantity_class" style="font-weight: bold; margin-bottom: 5px; display:none;">Type</div><div class="form-check form-check-inline rate_class" style="display:none"><input class="form-check-input radio_reading" type="radio" name="radio_quantity_reading['+$count_billing_service_info+']" id="radio_reading" value="reading"><label class="form-check-label" for="radio_reading">Reading</label></div><div class="form-check form-check-inline rate_class" style="display:none"><input class="form-check-input radio_quantity" type="radio" name="radio_quantity_reading['+$count_billing_service_info+']" id="radio_quantity" value="quantity"><label class="form-check-label" for="radio_quantity">Quantity</label></div></div><div class="quantity" style="width: 200px;display: none; margin-right:10px;"><div style="font-weight: bold;">Quantity</div><div class="input-group" style="width: 100%"><input type="text" class="form-control border_radius quantity_value" id="quantity_value" name="quantity_value['+$count_billing_service_info+']" value="'+((billing_below_info[t]["quantity_value"] != null)?billing_below_info[t]["quantity_value"]:"")+'"/></div></div><div class="rate_class reading" style="width: 200px;display: none; margin-right:10px;"><div style="font-weight: bold;">Reading at beginning</div><div class="input-group" style="width: 100%"><input type="text" class="form-control border_radius reading_at_begin" id="reading_at_begin" name="reading_at_begin['+$count_billing_service_info+']" value="'+((billing_below_info[t]["reading_at_begin"] != null)?billing_below_info[t]["reading_at_begin"]:"")+'"/></div></div><div class="rate_class reading" style="width: 200px;display: none"><div style="font-weight: bold;">Reading at the end</div><div class="input-group" style="width: 100%"><input type="text" class="form-control border_radius reading_at_the_end" id="reading_at_the_end" name="reading_at_the_end['+$count_billing_service_info+']" value="'+((billing_below_info[t]["reading_at_the_end"] != null)?billing_below_info[t]["reading_at_the_end"]:"")+'"/></div></div><div class="rate_class" style="width: 200px;display: none; margin-right:10px;"><div style="font-weight: bold;">Rate</div><div class="input-group" style="width: 100%"><input type="text" class="form-control border_radius number_of_rate" id="number_of_rate" name="number_of_rate['+$count_billing_service_info+']" value="'+((billing_below_info[t]["number_of_rate"] != null)?billing_below_info[t]["number_of_rate"]:"")+'"/></div></div><div class="rate_class" style="width: 200px;display: none"><div style="font-weight: bold;">Measurement Unit</div><div class="input-group" style="width: 100%"><input type="text" class="form-control border_radius unit_for_rate" id="unit_for_rate" name="unit_for_rate['+$count_billing_service_info+']" value="'+((billing_below_info[t]["unit_for_rate"] != null)?billing_below_info[t]["unit_for_rate"]:"")+'"/></div></div></div>';
        $a += '<div class="td" style="width:150px"><input type="hidden" name="gst_category_id[]" class="form-control gst_category_id" id="gst_category_id" value="'+billing_below_info[t]["gst_category_id"]+'"/><input type="hidden" name="gst_new_way[]" class="form-control gst_new_way" id="gst_new_way" value="'+billing_below_info[t]["gst_new_way"]+'"/><input type="hidden" name="gst_rate[]" class="form-control gst_rate" id="gst_rate" value="'+billing_below_info[t]["gst_rate"]+'"/><div class="input-group"><input type="text" name="amount['+$count_billing_service_info+']" class="numberdes form-control text-right amount" value="'+addCommas(billing_below_info[t]["billing_service_amount"])+'" id="amount" style="width:150px"/><div id="form_amount"></div></div></div>'; 
        $a += '<div class="td" style="width:150px"><div class="select-input-group"><select class="form-control" style="text-align:right;width: 165px;" name="unit_pricing['+$count_billing_service_info+']" id="unit_pricing"><option value="0" >Select Unit Pricing</option></select></div></div>';
        $a += '<div class="td action"><button type="button" class="btn btn-primary delete_billing_button" onclick="delete_billing(this)" style="display: none;">Delete</button></div>';
        $a += '</div>';

        /*<input type="button" value="Save" id="save" name="save'+$count_officer+'" class="btn btn-primary" onclick="save(this);">*/
        $("#body_create_billing").append($a); 

        $("#form"+$count_billing_service_info+" #claim_service_id").attr('value', billing_below_info[t]["claim_service_id"]);

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

        $.each(get_assignment_result, function(key, val) {
            //console.log(val['unit_pricing_name']);
            var option = $('<option />');
            option.attr('value', val['assignment_id']).text(val['type_of_job']+" ("+val['FYE']+")");
            if(billing_below_info[t]["assignment_id"] != null && val['assignment_id'] == billing_below_info[t]["assignment_id"])
            {
                option.attr('selected', 'selected');
                $("#form"+$count_billing_service_info+" #assignment_yes").prop("checked", true);
                $("#form"+$count_billing_service_info+" .assignment_div").show();
            }
            $("#form"+$count_billing_service_info+" #assignment"+$count_billing_service_info).append(option);
        });

        $("#form"+$count_billing_service_info+" #assignment"+$count_billing_service_info).select2();

        $.each(get_unit_pricing, function(key, val) {
            //console.log(billing_below_info[t]["unit_pricing"]);
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

        // for(var z = 0; z < service_dropdown.length; z++)
        // {
        //     //res[service_array[i]['service']] = service_array[i]['service_name'];

        //     var option = $('<option />');
        //     option.attr('data-invoice_description', service_dropdown[z]['invoice_description']);
        //     option.attr('data-amount', service_dropdown[z]['amount']);
        //     option.attr('data-client_billing_info_id', service_dropdown[z]['client_billing_info_id']);
        //     option.attr('value', service_dropdown[z]['service']).text(service_dropdown[z]['service_name']);

        //     if(billing_below_info[t]["service"] != null && service_dropdown[z]['service'] == billing_below_info[t]["service"])
        //     {
        //         option.attr('selected', 'selected');
        //     }

        //     $("#form"+t+" #service").append(option);
        // }    

        var category_description = '';
        var optgroup = '';
        // for(var z = 0; z < service_dropdown.length; z++)
        // {
        //     if(category_description != service_dropdown[z]['category_description'])
        //     {
        //         if(optgroup != '')
        //         {
        //             $("#form"+t+" #service").append(optgroup);
        //         }
        //         optgroup = $('<optgroup label="' + service_dropdown[z]['category_description'] + '" />');
        //     }

        //     var option = $('<option />');
        //     option.attr('value', service_dropdown[z]['id']).text(service_dropdown[z]['service']).appendTo(optgroup);

        //     category_description = service_dropdown[z]['category_description'];
        //     console.log(billing_below_info[t]["service"]);
        //     if(billing_below_info[t]["service"] != null && service_dropdown[z]['id'] == billing_below_info[t]["service"])
        //     {
        //         option.attr('selected', 'selected');
        //     }

            
        // }
        for(var j = 0; j < service_category.length; j++)
        {
            if(category_description != service_category[j]['category_description'])
            {
                if(optgroup != '')
                {
                    $("#form"+$count_billing_service_info+" #service"+$count_billing_service_info).append(optgroup);
                }
                optgroup = $('<optgroup label="' + service_category[j]['category_description'] + '" />');
            }

            category_description = service_category[j]['category_description'];
            
            for(var h = 0; h < service_dropdown.length; h++)
            {
                if(category_description == service_dropdown[h]['category_description'])
                {
                    var option = $('<option />');
                    option.attr('data-gst_category_id', service_dropdown[h]['gst_category_id']).attr('data-calculate_by_quantity_rate', service_dropdown[h]['calculate_by_quantity_rate']).attr('data-gst_new_way', service_dropdown[h]['gst_new_way']).attr('data-rate', service_dropdown[h]['rate']).attr('data-our_service_id', service_dropdown[h]['service']).attr('data-description', service_dropdown[h]['invoice_description']).attr('data-currency', service_dropdown[h]['currency']).attr('data-unit_pricing', service_dropdown[h]['unit_pricing']).attr('data-amount', service_dropdown[h]['amount']).attr('value', service_dropdown[h]['id']).text(service_dropdown[h]['service_name']);
                    
                    if(service_dropdown[h]['deleted'] == 0)
                    {
                        option.appendTo(optgroup);
                    }

                    if(billing_below_info[t]["service"] != null && service_dropdown[h]['id'] == billing_below_info[t]["service"])
                    {
                        if(service_dropdown[h]['deleted'] == 1)
                        {
                            option.appendTo(optgroup);
                        }
                        option.attr('selected', true);

                        if(service_dropdown[h]['calculate_by_quantity_rate'] == "1")
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
                        else if(service_dropdown[h]['calculate_by_quantity_rate'] == "2")
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

        for(var h = 0; h < service_dropdown.length; h++)
        {
            if(service_dropdown[h]['deactive'] == 1)
            {
                $("#form"+t+" #service"+t+" option[value='"+service_dropdown[h]['id']+"']").attr("disabled", true);
            }
        }

        service_array = service_dropdown;
        service_category_array = service_category;
        unit_pricing = get_unit_pricing;
        assignment_result = get_assignment_result;

        $('#create_billing_form').formValidation('addField', 'service['+$count_billing_service_info+']', service);
        $('#create_billing_form').formValidation('addField', 'invoice_description['+$count_billing_service_info+']', invoice_description);
        $('#create_billing_form').formValidation('addField', 'amount['+$count_billing_service_info+']', amount);
        $('#create_billing_form').formValidation('addField', 'unit_pricing['+$count_billing_service_info+']', validate_unit_pricing);
    }
    sum_first_total();
}

$(document).on('change','.service',function(e){
    var num = $(this).parent().parent().parent().attr("num");
   
    var calculate_by_quantity_rate = $(this).find(':selected').data('calculate_by_quantity_rate');
    var descriptionValue = $(this).find(':selected').data('description');
    var amountValue = $(this).find(':selected').data('amount');
    // var currencyValue = $(this).find(':selected').data('currency');
    var unit_pricingValue = $(this).find(':selected').data('unit_pricing');
    var rate = $(this).find(':selected').data('rate');
    var gst_new_way = $(this).find(':selected').data('gst_new_way');
    var gst_category_id = $(this).find(':selected').data('gst_category_id');
    // console.log(calculate_by_quantity_rate);
    // console.log($(this).parent().parent().parent());
    if(calculate_by_quantity_rate == "1")
    {
        $(this).parent().parent().parent().find('.period_class').hide();
        $(this).parent().parent().parent().find('.rate_class').css('display', 'inline-block');
        $(this).parent().parent().parent().find('.type_reading_quantity_class').css('display', 'block');
        $(this).parent().parent().parent().find('.period_start_date').val("");
        $(this).parent().parent().parent().find('.period_end_date').val("");

    }
    else if(calculate_by_quantity_rate == "2")
    {
        $(this).parent().parent().parent().find('.period_class').css('display', 'inline-block');
        $(this).parent().parent().parent().find('.rate_class').hide();
        $(this).parent().parent().parent().find('.type_reading_quantity_class').hide();
        $(this).parent().parent().parent().find('#quantity_value').val("");
        $(this).parent().parent().parent().find('#reading_at_begin').val("");
        $(this).parent().parent().parent().find('#reading_at_the_end').val("");
        $(this).parent().parent().parent().find('#number_of_rate').val("");
        $(this).parent().parent().parent().find('#unit_for_rate').val("");
    }

    $(this).parent().parent().parent().find('#invoice_description').text(descriptionValue);
    var poc_percentage_val = $(this).parent().parent().parent().find('.poc_percentage').val();
    if(poc_percentage_val != "")
    {
        var after_poc_amount = (parseFloat(poc_percentage_val)/100)*parseFloat(amountValue);
        $(this).parent().parent().parent().find('#amount').val(addCommas(after_poc_amount.toFixed(2)));
    }
    else
    {
        $(this).parent().parent().parent().find('#amount').val(addCommas(amountValue));
    }

    $(this).parent().parent().parent().find('.number_of_percent_poc').text(addCommas(amountValue));
    $(this).parent().parent().parent().find('.hidden_number_of_percent_poc').val(addCommas(amountValue));
    
    //$(this).parent().parent().parent().find('#currency').val(currencyValue);
    $(this).parent().parent().parent().find('#unit_pricing').val(addCommas(unit_pricingValue));
    $(this).parent().parent().parent().find('#gst_rate').val(rate);
    $(this).parent().parent().parent().find('#gst_new_way').val(gst_new_way);
    $(this).parent().parent().parent().find('#gst_category_id').val(gst_category_id);

    showProgressBillingInfo($(this).parent().parent().parent());

    $('#create_billing_form').formValidation('revalidateField', 'service['+num+']');
    $('#create_billing_form').formValidation('revalidateField', 'invoice_description['+num+']');
    $('#create_billing_form').formValidation('revalidateField', 'amount['+num+']');
    $('#create_billing_form').formValidation('revalidateField', 'unit_pricing['+num+']');
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
    var amountValue = $(this).parent().parent().parent().parent().find('#amount').val();
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
        $(this).parent().parent().parent().parent().find('#amount').val(addCommas(txt_number_of_poc.toFixed(2)));
    }
    else
    {
        $(this).parent().parent().parent().parent().find('#amount').val("0.00");
    }

    $(this).parent().parent().parent().find('.number_of_percent_poc').text("0.00");
    $(this).parent().parent().parent().find('.hidden_number_of_percent_poc').val("0.00");
});

$(document).on('change','.assignment_yes',function(e){
    $(this).parent().parent().parent().find('.assignment_div').show();
    $(this).parent().parent().parent().find('.assignment').select2("val", "0");
});

$(document).on('change','.assignment_no',function(e){
    
    arr_for_check_no_assignment.push($(this).parent().parent().parent().find('.assignment :selected').val());
    $(this).parent().parent().parent().find('.assignment').select2("val", "0");
    $(this).parent().parent().parent().find('.assignment_div').hide();
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
        console.log($row.find("#amount"));
        var format_total_amount_for_reading = addCommas(total_amount_for_reading.toFixed(2));
        $row.find("#amount").val(format_total_amount_for_reading);
        $row.find('.number_of_percent_poc').text(format_total_amount_for_reading);
        $row.find('.hidden_number_of_percent_poc').val(format_total_amount_for_reading);
    }
    else
    {
        console.log("in");
        $row.find("#amount").val("0.00");
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
        $row.find("#amount").val(addCommas(total_amount_for_quantity.toFixed(2)));
        $row.find('.number_of_percent_poc').text(addCommas(total_amount_for_quantity.toFixed(2)));
        $row.find('.hidden_number_of_percent_poc').val(addCommas(total_amount_for_quantity.toFixed(2)));
    }
    else
    {
        $row.find("#amount").val("0.00");
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
    //console.log($row);
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
    $count_billing_service_info = billing_below_info.length;
}
else
{
    $count_billing_service_info = count_billing_service_info_num + 1;
}

//$count_billing_service_info = 1;
$(document).on('click',"#billing_service_info_Add",function() {
    
    $a=""; 
    /*<select class="input-sm" style="text-align:right;width: 150px;" id="position" name="position[]" onchange="optionCheck(this);"><option value="Director" >Director</option><option value="CEO" >CEO</option><option value="Manager" >Manager</option><option value="Secretary" >Secretary</option><option value="Auditor" >Auditor</option><option value="Managing Director" >Managing Director</option><option value="Alternate Director">Alternate Director</option></select>*/
    $a += '<div class="tr editing tr_billing" method="post" name="form'+$count_billing_service_info+'" id="form'+$count_billing_service_info+'" num="'+$count_billing_service_info+'">';
    $a += '<div class="hidden"><input type="text" class="form-control" name="company_code" value=""/></div>';
    $a += '<div class="hidden"><input type="text" class="form-control" name="billing_service_id" value=""/></div>';
    $a += '<div class="hidden"><input type="text" class="form-control" name="client_billing_info_id['+$count_billing_service_info+']" id="client_billing_info_id" value=""/></div>';
    $a += '<div class="hidden"><input type="text" class="form-control" name="claim_service_id['+$count_billing_service_info+']" id="claim_service_id" value=""/></div>';
    //$a += '<div class="td"><div class="select-input-group"><select class="input-sm form-control service" name="service['+$count_billing_service_info+']" id="service'+$count_billing_service_info+'" style="width:200px;"><option value="0" data-invoice_description="" data-amount="">Select Service</option></select><input type="hidden" class="form-control" name="payment_voucher_type['+$count_billing_service_info+']" id="payment_voucher_type" value=""/><div id="form_service"></div></div></div>';
    //$a += '<div class="td"><div class="select-input-group mb-md"><select class="input-sm form-control service" name="service['+$count_billing_service_info+']" id="service'+$count_billing_service_info+'" style="width:200px;"><option value="0" data-invoice_description="" data-amount="">Select Service</option></select><input type="hidden" class="form-control" name="payment_voucher_type['+$count_billing_service_info+']" id="payment_voucher_type" value=""/></div><div class="input-group mb-md"><div style="font-weight: bold; margin-bottom: 5px;">Progress Billing</div><div class="form-check form-check-inline"><input class="form-check-input progress_billing_yes" type="radio" name="progress_billing_yes_no['+$count_billing_service_info+']" id="progress_billing_yes" value="yes"><label class="form-check-label" for="progress_billing_yes">Yes</label></div><div class="form-check form-check-inline"><input class="form-check-input progress_billing_no" type="radio" name="progress_billing_yes_no['+$count_billing_service_info+']" id="progress_billing_no" value="no" checked><label class="form-check-label" for="progress_billing_no">No</label></div></div><div class="poc_percent_div input-group mb-md" style="display:none"><div class="form-check"><input class="form-control form-check-input poc_percentage" style="width:50px; margin-right:1px;" type="text" name="poc_percentage['+$count_billing_service_info+']" id="poc_percentage" value=""><label class="form-check-label" style="margin-top: 7px;" for="poc_percentage">% of <span class="number_of_percent_poc">0.00</span></label><input type="hidden" class="hidden_number_of_percent_poc" name="hidden_number_of_percent_poc['+$count_billing_service_info+']" value=""></div></div></div>';
    //$a += '<div class="td"><div class="input-group mb-md"><textarea class="form-control" name="invoice_description['+$count_billing_service_info+']"  id="invoice_description" rows="3" style="width:420px"></textarea></div><div style="width: 200px;display: inline-block; margin-right:10px;"><div style="font-weight: bold;">Period Start Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker period_start_date" id="period_start_date" name="period_start_date['+$count_billing_service_info+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div></div><div style="width: 200px;display: inline-block"><div style="font-weight: bold;">Period End Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker period_end_date" id="period_end_date" name="period_end_date['+$count_billing_service_info+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div></div></div>';
    $a += '<div class="td"><div class="select-input-group mb-md"><select class="input-sm form-control service" name="service['+$count_billing_service_info+']" id="service'+$count_billing_service_info+'" style="width:200px;"><option value="0" data-invoice_description="" data-amount="">Select Service</option></select><input type="hidden" class="form-control" name="payment_voucher_type['+$count_billing_service_info+']" id="payment_voucher_type" value=""/></div><div class="input-group mb-md"><div style="font-weight: bold; margin-bottom: 5px;">Progress Billing</div><div class="form-check form-check-inline"><input class="form-check-input progress_billing_yes" type="radio" name="progress_billing_yes_no['+$count_billing_service_info+']" id="progress_billing_yes" value="yes"><label class="form-check-label" for="progress_billing_yes">Yes</label></div><div class="form-check form-check-inline"><input class="form-check-input progress_billing_no" type="radio" name="progress_billing_yes_no['+$count_billing_service_info+']" id="progress_billing_no" value="no" checked><label class="form-check-label" for="progress_billing_no">No</label></div></div><div class="poc_percent_div input-group mb-md" style="display:none"><div class="form-check"><input class="form-control form-check-input poc_percentage" style="width:50px; margin-right:1px;" type="text" name="poc_percentage['+$count_billing_service_info+']" id="poc_percentage" value=""><label class="form-check-label" style="margin-top: 7px;" for="poc_percentage">% of <span class="number_of_percent_poc">0.00</span></label><input type="hidden" class="hidden_number_of_percent_poc" name="hidden_number_of_percent_poc['+$count_billing_service_info+']" value=""></div></div><div class="input-group mb-md"><div style="font-weight: bold; margin-bottom: 5px;">Assignment</div><div class="form-check form-check-inline"><input class="form-check-input assignment_yes" type="radio" name="assignment_yes_no['+$count_billing_service_info+']" id="assignment_yes" value="yes"><label class="form-check-label" for="assignment_yes">Yes</label></div><div class="form-check form-check-inline"><input class="form-check-input assignment_no" type="radio" name="assignment_yes_no['+$count_billing_service_info+']" id="assignment_no" value="no" checked><label class="form-check-label" for="assignment_no">No</label></div></div><div class="assignment_div mb-md" style="display:none"><div class="form-check"><select class="input-sm form-control assignment" name="assignment['+$count_billing_service_info+']" id="assignment'+$count_billing_service_info+'" style="width:200px;"><option value="0">Select Assignment</option></select></div></div></div>';
    $a += '<div class="td"><div class="input-group mb-md"><textarea class="form-control" name="invoice_description['+$count_billing_service_info+']"  id="invoice_description" rows="3" style="width:420px"></textarea></div><div class="period_class" style="width: 200px;display: inline-block; margin-right:10px;"><div style="font-weight: bold;">Period Start Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"/></span><input type="text" class="form-control datepicker period_start_date" id="period_start_date" name="period_start_date['+$count_billing_service_info+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div></div><div class="period_class" style="width: 200px;display: inline-block"><div style="font-weight: bold;">Period End Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"/></span><input type="text" class="form-control datepicker period_end_date" id="period_end_date" name="period_end_date['+$count_billing_service_info+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div></div><div class="input-group mb-md"><div class="type_reading_quantity_class" style="font-weight: bold; margin-bottom: 5px; display:none;">Type</div><div class="form-check form-check-inline rate_class" style="display:none"><input class="form-check-input radio_reading" type="radio" name="radio_quantity_reading['+$count_billing_service_info+']" id="radio_reading" value="reading" checked><label class="form-check-label" for="radio_reading">Reading</label></div><div class="form-check form-check-inline rate_class" style="display:none"><input class="form-check-input radio_quantity" type="radio" name="radio_quantity_reading['+$count_billing_service_info+']" id="radio_quantity" value="quantity"><label class="form-check-label" for="radio_quantity">Quantity</label></div></div><div class="quantity" style="width: 200px;display: none; margin-right:10px;"><div style="font-weight: bold;">Quantity</div><div class="input-group" style="width: 100%"><input type="text" class="form-control border_radius quantity_value" id="quantity_value" name="quantity_value['+$count_billing_service_info+']" value=""/></div></div><div class="rate_class reading" style="width: 200px;display: none; margin-right:10px;"><div style="font-weight: bold;">Reading at beginning</div><div class="input-group" style="width: 100%"><input type="text" class="form-control border_radius reading_at_begin" id="reading_at_begin" name="reading_at_begin['+$count_billing_service_info+']" value=""/></div></div><div class="rate_class reading" style="width: 200px;display: none"><div style="font-weight: bold;">Reading at the end</div><div class="input-group" style="width: 100%"><input type="text" class="form-control border_radius reading_at_the_end" id="reading_at_the_end" name="reading_at_the_end['+$count_billing_service_info+']" value=""/></div></div><div class="rate_class" style="width: 200px;display: none; margin-right:10px;"><div style="font-weight: bold;">Rate</div><div class="input-group" style="width: 100%"><input type="text" class="form-control border_radius number_of_rate" id="number_of_rate" name="number_of_rate['+$count_billing_service_info+']" value=""/></div></div><div class="rate_class" style="width: 200px;display: none"><div style="font-weight: bold;">Measurement Unit</div><div class="input-group" style="width: 100%"><input type="text" class="form-control border_radius unit_for_rate" id="unit_for_rate" name="unit_for_rate['+$count_billing_service_info+']" value=""/></div></div></div>';   
    $a += '<div class="td" style="width:150px"><input type="hidden" name="gst_category_id[]" class="form-control gst_category_id" id="gst_category_id" value=""/><input type="hidden" name="gst_new_way[]" class="form-control gst_new_way" id="gst_new_way" value=""/><input type="hidden" name="gst_rate[]" class="form-control gst_rate" id="gst_rate" value=""/><div class="input-group"><input type="text" name="amount['+$count_billing_service_info+']" class="numberdes form-control text-right amount" value="" id="amount" style="width:150px"/><div id="form_amount"></div></div></div>';
    $a += '<div class="td" style="width:150px"><div class="select-input-group"><select class="form-control" style="text-align:right;width: 165px;" name="unit_pricing['+$count_billing_service_info+']" id="unit_pricing"><option value="0" >Select Unit Pricing</option></select><div id="form_unit_pricing"></div></div></div>';
    /*$a += '<div class="td action"><button type="button" class="btn btn-primary" onclick="edit_billing_info(this);">Save</button></div></div>';*/
    $a += '<div class="td action"><button type="button" class="btn btn-primary delete_billing_button" onclick="delete_billing(this)" style="display: block;">Delete</button></div>';
    $a += '</div>';

    /*<input type="button" value="Save" id="save" name="save'+$count_officer+'" class="btn btn-primary" onclick="save(this);">*/
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

    $.each(assignment_result, function(key, val) {
        //console.log(val['unit_pricing_name']);
        var option = $('<option />');
        option.attr('value', val['assignment_id']).text(val['type_of_job']+" ("+val['FYE']+")");
        $("#form"+$count_billing_service_info+" #assignment"+$count_billing_service_info).append(option);
    });

    $("#form"+$count_billing_service_info+" #assignment"+$count_billing_service_info).select2();

    $.each(unit_pricing, function(key, val) {
        //console.log(val['unit_pricing_name']);
        var option = $('<option />');
        option.attr('value', val['id']).text(val['unit_pricing_name']);
        
        $("#form"+$count_billing_service_info+" #unit_pricing").append(option);
    });

    // for(var i = 0; i < service_array.length; i++)
    // {
    //     //res[service_array[i]['service']] = service_array[i]['service_name'];

    //     var option = $('<option />');
    //     option.attr('data-invoice_description', service_array[i]['invoice_description']);
    //     option.attr('data-amount', service_array[i]['amount']);
    //     option.attr('data-client_billing_info_id', service_array[i]['client_billing_info_id']);
    //     option.attr('value', service_array[i]['service']).text(service_array[i]['service_name']);
        
    //     $("#form"+$count_billing_service_info+" #service").append(option);
    // } 

    var category_description = '';
    var optgroup = '';
    // for(var t = 0; t < service_array.length; t++)
    // {
    //     if(category_description != service_array[t]['category_description'])
    //     {
    //         if(optgroup != '')
    //         {
    //             $("#form"+$count_billing_service_info+" #service").append(optgroup);
    //         }
    //         optgroup = $('<optgroup label="' + service_array[t]['category_description'] + '" />');
    //     }

    //     var option = $('<option />');
    //     option.attr('value', service_array[t]['id']).text(service_array[t]['service']).appendTo(optgroup);

    //     category_description = service_array[t]['category_description'];
    // }
    for(var t = 0; t < service_category_array.length; t++)
    {
        // if(category_description != service_category_array[t]['category_description'])
        // {
            if(category_description != service_category_array[t]['category_description'])
            {
                if(optgroup != '')
                {
                    $("#form"+$count_billing_service_info+" #service"+$count_billing_service_info).append(optgroup);
                }
                optgroup = $('<optgroup label="' + service_category_array[t]['category_description'] + '" />');
                //console.log(service_category_array[t]['category_description']);
            }

            category_description = service_category_array[t]['category_description'];
            
            for(var h = 0; h < service_array.length; h++)
            {
                if(category_description == service_array[h]['category_description'])
                {
                    //console.log(service_array[h]);
                    var option = $('<option />');
                    option.attr('data-gst_category_id', service_array[h]['gst_category_id']).attr('data-calculate_by_quantity_rate', service_array[h]['calculate_by_quantity_rate']).attr('data-gst_new_way', service_array[h]['gst_new_way']).attr('data-rate', service_array[h]['rate']).attr('data-our_service_id', service_array[h]['service']).attr('data-description', service_array[h]['invoice_description']).attr('data-currency', service_array[h]['currency']).attr('data-unit_pricing', service_array[h]['unit_pricing']).attr('data-amount', service_array[h]['amount']).attr('value', service_array[h]['id']).text(service_array[h]['service_name']);
                    if(service_array[h]['deleted'] == 0)
                    {
                        option.appendTo(optgroup);
                    }
                }
            }
            //}
        

        
    }
    $("#form"+$count_billing_service_info+" #service"+$count_billing_service_info).append(optgroup);   

    $("#form"+$count_billing_service_info+" #service"+$count_billing_service_info).select2();

    for(var h = 0; h < service_array.length; h++)
    {
        if(service_array[h]['deactive'] == 1)
        {
            $("#form"+$count_billing_service_info+" #service"+$count_billing_service_info+" option[value='"+service_array[h]['id']+"']").attr("disabled", true);
        }
    }

    // for(var h = 0; h < service_array.length; h++)
    // {
    //     if(service_array[h]['deactive'] == 1)
    //     {
    //         $("#form"+$count_billing_service_info+" #service option[value='"+service_array[h]['id']+"']").attr("disabled", true);
    //     }
    // }

    // $.validator.setDefaults({ ignore: ":hidden:not(select)" })
    // $("#form"+$count_billing_service_info).validate({
    //     rules: {chosen:"required"},
    //     message: {chosen:"Select a Country"}
    // });

    // $.validator.addMethod(     //adding a method to validate select box//
    //         "chosen",
    //         function(value, element) {
    //             console.log(value.length);
    //             return (value == null ? false : (value.length == 0 ? false : true))
    //         },
    //         "please select an option"//custom message
    //         );

    // $("#form"+$count_billing_service_info).validate({
    //     rules: {
    //         "#service": {
    //             chosen: true
    //         }
    //     }
    // });

    $('#create_billing_form').formValidation('addField', 'service['+$count_billing_service_info+']', service);
    $('#create_billing_form').formValidation('addField', 'invoice_description['+$count_billing_service_info+']', invoice_description);
    $('#create_billing_form').formValidation('addField', 'amount['+$count_billing_service_info+']', amount);
    $('#create_billing_form').formValidation('addField', 'unit_pricing['+$count_billing_service_info+']', validate_unit_pricing);

    $count_billing_service_info++;
});

// $(document).on('change','#create_billing_form #service',function(e){
//     var num = $(this).parent().parent().parent().attr("num");

//     var selected_invoice_description = $(this).find(':selected').data('invoice_description');
//     var selected_amount = $(this).find(':selected').data('amount');
//     var selected_client_billing_info_id = $(this).find(':selected').data('client_billing_info_id');
//     //console.log(selected_invoice_description);
//     //$(this).parent().parent().parent().find('#invoice_description').val(selected_invoice_description);
//     //$(this).parent().parent().parent().find('#amount').val(addCommas(selected_amount));
//     //$(this).parent().parent().parent().find('#client_billing_info_id').val(addCommas(selected_client_billing_info_id));

//     //sum_total();

//     $('#create_billing_form').formValidation('revalidateField', 'service['+num+']');
//     $('#create_billing_form').formValidation('revalidateField', 'invoice_description['+num+']');
//     $('#create_billing_form').formValidation('revalidateField', 'amount['+num+']');
//     $('#create_billing_form').formValidation('revalidateField', 'unit_pricing['+num+']');
// });

$(document).on('change','#create_billing_form #client_name',function(e){
    showRow();
});

$(document).on('change','#create_billing_form #currency',function(e){
    showRow();
});

function showRow(){
    var company_code = $('#client_name option:selected').val();

    //console.log(company_code);
    if($("#currency option:selected").val() == 0)
    {
        toastr.error("Please select a currency.", "Error");
    }
    else
    {
        $.ajax({
            type: "POST",
            url: "billings/get_company_service",
            data: {"company_code":company_code, "currency": $("#currency option:selected").val()}, // <--- THIS IS THE CHANGE
            dataType: "json",
            success: function(response){
                $(".tr_billing").remove();
                //console.log(response);
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
                        $('[name="hidden_foreign_address1"]').val("");
                        $('[name="hidden_foreign_address2"]').val("");
                        $('[name="hidden_foreign_address3"]').val("");
                        $('#create_billing_form').formValidation('revalidateField', 'address');
                        $("#address").attr('readOnly', false);
                    }
                    else
                    {
                        $('[name="address"]').val(response.address);
                        $('[name="hidden_postal_code"]').val(response.postal_code);
                        $('[name="hidden_street_name"]').val(response.street_name);
                        $('[name="hidden_building_name"]').val(response.building_name);
                        $('[name="hidden_unit_no1"]').val(response.unit_no1);
                        $('[name="hidden_unit_no2"]').val(response.unit_no2);
                        $('[name="hidden_foreign_address1"]').val(response.foreign_add_1);
                        $('[name="hidden_foreign_address2"]').val(response.foreign_add_2);
                        $('[name="hidden_foreign_address3"]').val(response.foreign_add_3);
                        $('#create_billing_form').formValidation('revalidateField', 'address');
                        $("#address").attr('readOnly', true);
                    }
                    

                    service_array = response.service;
                    service_category_array = response.selected_billing_info_service_category;
                    unit_pricing = response.unit_pricing;
                    claim_result = response.claim_result;
                    assignment_result = response.assignment_result;

                    if(response.service.length != 0)
                    {
                        $('#create_billing_service').show();
                        $('#body_create_billing').show();
                        $('#sub_total_create_billing').show();
                        $('#gst_create_billing').show();
                        $('#grand_total_create_billing').show();
                    }
                    else
                    {
                        $('#create_billing_service').hide();
                        $('#body_create_billing').hide();
                        $('#sub_total_create_billing').hide();
                        $('#gst_create_billing').hide();
                        $('#grand_total_create_billing').hide();
                    }
                    //console.log(claim_result.length);
                    // if(claim_result.length == 0)
                    // {
                        count_billing_service_info_num = 0;

                        $a0=""; 

                        $a0 += '<div class="tr editing tr_billing" method="post" name="form'+0+'" id="form'+0+'" num="'+0+'">';
                        $a0 += '<div class="hidden"><input type="text" class="form-control" name="company_code" value=""/></div>';
                        $a0 += '<div class="hidden"><input type="text" class="form-control" name="billing_service_id" value=""/></div>';
                        $a0 += '<div class="hidden"><input type="text" class="form-control" name="client_billing_info_id['+0+']" id="client_billing_info_id" value=""/></div>';
                        $a0 += '<div class="hidden"><input type="text" class="form-control" name="claim_service_id['+0+']" id="claim_service_id" value=""/></div>';
                        $a0 += '<div class="td"><div class="select-input-group mb-md"><select class="input-sm form-control service" name="service['+0+']" id="service'+0+'" style="width:200px;"><option value="0" data-invoice_description="" data-amount="">Select Service</option></select><input type="hidden" class="form-control" name="payment_voucher_type['+0+']" id="payment_voucher_type" value=""/></div><div class="input-group mb-md"><div style="font-weight: bold; margin-bottom: 5px;">Progress Billing</div><div class="form-check form-check-inline"><input class="form-check-input progress_billing_yes" type="radio" name="progress_billing_yes_no['+0+']" id="progress_billing_yes" value="yes"><label class="form-check-label" for="progress_billing_yes">Yes</label></div><div class="form-check form-check-inline"><input class="form-check-input progress_billing_no" type="radio" name="progress_billing_yes_no['+0+']" id="progress_billing_no" value="no" checked><label class="form-check-label" for="progress_billing_no">No</label></div></div><div class="poc_percent_div input-group mb-md" style="display:none"><div class="form-check"><input class="form-control form-check-input poc_percentage" style="width:50px; margin-right:1px;" type="text" name="poc_percentage['+0+']" id="poc_percentage" value=""><label class="form-check-label" style="margin-top: 7px;" for="poc_percentage">% of <span class="number_of_percent_poc">0.00</span></label><input type="hidden" class="hidden_number_of_percent_poc" name="hidden_number_of_percent_poc['+0+']" value=""></div></div><div class="input-group mb-md"><div style="font-weight: bold; margin-bottom: 5px;">Assignment</div><div class="form-check form-check-inline"><input class="form-check-input assignment_yes" type="radio" name="assignment_yes_no['+0+']" id="assignment_yes" value="yes"><label class="form-check-label" for="assignment_yes">Yes</label></div><div class="form-check form-check-inline"><input class="form-check-input assignment_no" type="radio" name="assignment_yes_no['+0+']" id="assignment_no" value="no" checked><label class="form-check-label" for="assignment_no">No</label></div></div><div class="assignment_div mb-md" style="display:none"><div class="form-check"><select class="input-sm form-control assignment" name="assignment['+0+']" id="assignment'+0+'" style="width:200px;"><option value="0">Select Assignment</option></select></div></div></div>';
                        $a0 += '<div class="td"><div class="input-group mb-md"><textarea class="form-control" name="invoice_description['+0+']"  id="invoice_description" rows="3" style="width:420px"></textarea></div><div class="period_class" style="width: 200px;display: inline-block; margin-right:10px;"><div style="font-weight: bold;">Period Start Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"/></span><input type="text" class="form-control datepicker period_start_date" id="period_start_date" name="period_start_date['+0+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div></div><div class="period_class" style="width: 200px;display: inline-block"><div style="font-weight: bold;">Period End Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"/></span><input type="text" class="form-control datepicker period_end_date" id="period_end_date" name="period_end_date['+0+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div></div><div class="input-group mb-md"><div class="type_reading_quantity_class" style="font-weight: bold; margin-bottom: 5px; display:none;">Type</div><div class="form-check form-check-inline rate_class" style="display:none"><input class="form-check-input radio_reading" type="radio" name="radio_quantity_reading['+0+']" id="radio_reading" value="reading" checked><label class="form-check-label" for="radio_reading">Reading</label></div><div class="form-check form-check-inline rate_class" style="display:none"><input class="form-check-input radio_quantity" type="radio" name="radio_quantity_reading['+0+']" id="radio_quantity" value="quantity"><label class="form-check-label" for="radio_quantity">Quantity</label></div></div><div class="quantity" style="width: 200px;display: none; margin-right:10px;"><div style="font-weight: bold;">Quantity</div><div class="input-group" style="width: 100%"><input type="text" class="form-control border_radius quantity_value" id="quantity_value" name="quantity_value['+0+']" value=""/></div></div><div class="rate_class reading" style="width: 200px;display: none; margin-right:10px;"><div style="font-weight: bold;">Reading at beginning</div><div class="input-group" style="width: 100%"><input type="text" class="form-control border_radius reading_at_begin" id="reading_at_begin" name="reading_at_begin['+0+']" value=""/></div></div><div class="rate_class reading" style="width: 200px;display: none"><div style="font-weight: bold;">Reading at the end</div><div class="input-group" style="width: 100%"><input type="text" class="form-control border_radius reading_at_the_end" id="reading_at_the_end" name="reading_at_the_end['+0+']" value=""/></div></div><div class="rate_class" style="width: 200px;display: none; margin-right:10px;"><div style="font-weight: bold;">Rate</div><div class="input-group" style="width: 100%"><input type="text" class="form-control border_radius number_of_rate" id="number_of_rate" name="number_of_rate['+0+']" value=""/></div></div><div class="rate_class" style="width: 200px;display: none"><div style="font-weight: bold;">Measurement Unit</div><div class="input-group" style="width: 100%"><input type="text" class="form-control border_radius unit_for_rate" id="unit_for_rate" name="unit_for_rate['+0+']" value=""/></div></div></div>';               
                        $a0 += '<div class="td" style="width:150px"><input type="hidden" name="gst_category_id[]" class="form-control gst_category_id" id="gst_category_id" value=""/><input type="hidden" name="gst_new_way[]" class="form-control gst_new_way" id="gst_new_way" value=""/><input type="hidden" name="gst_rate[]" class="form-control gst_rate" id="gst_rate" value=""/><div class="input-group"><input type="text" name="amount['+0+']" class="numberdes form-control text-right amount" value="" id="amount" style="width:150px"/><div id="form_amount"></div></div></div>';
                        /*$a += '<div class="td action"><button type="button" class="btn btn-primary" onclick="edit_billing_info(this);">Save</button></div></div>';*/
                        $a0 += '<div class="td" style="width:150px"><div class="select-input-group"><select class="form-control" style="text-align:right;width: 165px;" name="unit_pricing['+0+']" id="unit_pricing"><option value="0" >Select Unit Pricing</option></select></div></div>';
                        $a0 += '<div class="td action"><button type="button" class="btn btn-primary delete_billing_button" onclick="delete_billing(this)" style="display: none;">Delete</button></div>';
                        $a0 += '</div>';

                        $("#body_create_billing").append($a0); 

                        $.each(assignment_result, function(key, val) {
                            //console.log(val['unit_pricing_name']);
                            var option = $('<option />');
                            option.attr('value', val['assignment_id']).text(val['type_of_job']+" ("+val['FYE']+")");
                            $("#form"+0+" #assignment"+0).append(option);
                        });

                        $("#form"+0+" #assignment"+0).select2();

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
                            //console.log(val['unit_pricing_name']);
                            var option = $('<option />');
                            option.attr('value', val['id']).text(val['unit_pricing_name']);
                            
                            $("#form"+0+" #unit_pricing").append(option);
                        });

                        var category_description = '';
                        var optgroup = '';

                        for(var t = 0; t < service_category_array.length; t++)
                        {
                            if(category_description != service_category_array[t]['category_description'])
                            {
                                if(optgroup != '')
                                {
                                    $("#form"+0+" #service"+0).append(optgroup);
                                }
                                optgroup = $('<optgroup label="' + service_category_array[t]['category_description'] + '" />');
                            }

                            category_description = service_category_array[t]['category_description'];
                            
                            for(var h = 0; h < service_array.length; h++)
                            {
                                if(category_description == service_array[h]['category_description'])
                                {
                                    var option = $('<option />'); //calculate_by_quantity_rate
                                    option.attr('data-gst_category_id', service_array[h]['gst_category_id']).attr('data-calculate_by_quantity_rate', service_array[h]['calculate_by_quantity_rate']).attr('data-gst_new_way', service_array[h]['gst_new_way']).attr('data-rate', service_array[h]['rate']).attr('data-our_service_id', service_array[h]['service']).attr('data-description', service_array[h]['invoice_description']).attr('data-currency', service_array[h]['currency']).attr('data-unit_pricing', service_array[h]['unit_pricing']).attr('data-amount', service_array[h]['amount']).attr('value', service_array[h]['id']).text(service_array[h]['service_name']).appendTo(optgroup);
                                }
                            }
                        }
                        $("#form"+0+" #service"+0).append(optgroup);

                        $("#form"+0+" #service"+0).select2();

                        for(var h = 0; h < service_array.length; h++)
                        {
                            if(service_array[h]['deactive'] == 1)
                            {
                                $("#form"+0+" #service"+0+" option[value='"+service_array[h]['id']+"']").attr("disabled", true);
                            }
                        }

                        $('#create_billing_form').formValidation('addField', 'service['+0+']', service);
                        $('#create_billing_form').formValidation('addField', 'invoice_description['+0+']', invoice_description);
                        $('#create_billing_form').formValidation('addField', 'amount['+0+']', amount);  
                        $('#create_billing_form').formValidation('addField', 'unit_pricing['+0+']', validate_unit_pricing);  
                    //}
                    if(claim_result.length > 0)
                    { 
                        $("#modal_claim_list").modal("show");
                        //toastr.error("This Client have "+claim_result.length+" not paying claim.", "Claim Info");
                        $(".claim_list_tr").remove();
                        for(var f = 0; f < claim_result.length; f++)
                        {
                            $b = "";
                            $b = '<tr class="claim_list_tr"><td style="text-align: center; vertical-align: middle;"><input type="checkbox" class="claim_id" id="claim_id" value="'+claim_result[f]["id"]+'" data-amount="'+claim_result[f]["amount"]+'"></td><td><div class="type">'+claim_result[f]["claim_date"]+'</div></td><td><div class="type">'+claim_result[f]["type_name"]+'</div></td><td><div class="desciption">'+claim_result[f]["claim_description"]+'</div></td><td><div class="desciption">'+claim_result[f]["currency_name"]+'</div></td><td style="text-align: right"><div class="amount">'+addCommas(claim_result[f]["amount"])+'</div></td></tr>';
                            $("#claim_info").append($b); 
                        }               
                    }
                }

            }               
        });
        $('#create_billing_form').formValidation('revalidateField', 'client_name');
    }
}

$(document).on('change','.claim_id',function(e){
    var checked = $(this).val();

    if ($(this).is(':checked')) {
        tmp.push(checked);
        total_claim_amount = total_claim_amount + parseFloat($(this).data('amount'));
    } else {
        tmp.splice($.inArray(checked, tmp),1);
        total_claim_amount = total_claim_amount - parseFloat($(this).data('amount'));
    }

    $(".total_selected").html(addCommas(total_claim_amount.toFixed(2)));
});

$(document).on('click',"#selectClaimList",function(e){
    $("#modal_claim_list").modal("hide");
    //console.log($("#service").includes("DISBURSEMENT - Printing"));
    var opt = 'DISBURSEMENTS';
    if ($('#service'+0+' option:contains('+ opt +')').length) {
        $("select[name='service[0]']").select2("val", $("#service"+0+" option:contains('DISBURSEMENTS')").val()).trigger('change');
        $("#amount").val(addCommas(total_claim_amount.toFixed(2)));
        //console.log($("#claim_service_id"));
        $("#claim_service_id").attr('value', JSON.stringify(tmp));
    }
    else
    {
        toastr.error("Please set the DISBURSEMENTS in Service Engagement inside Client module.", "Error");
    }
    
    //$("select[name='service[0]'] option:contains(DISBURSEMENT - Printing)").attr('selected', 'selected');
});

$(document).on('change','#create_billing_form #currency',function(e){
    //console.log($(this).val());
    if($(this).val() == "1")
    {
        $("#rate").val("1.0000");
    }
    $('#create_billing_form').formValidation('revalidateField', 'currency');
});

function get_gst_rate(billing_date)
{
    //console.log(billing_date);
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
            //console.log(response.get_gst_rate);

        }
    });

    //$('#create_billing_form').formValidation('revalidateField', 'billing_date');
}


$(document).on("submit",function(e){
    e.preventDefault();
    var $form = $(e.target);
    // and the FormValidation instance
    var fv = $form.data('formValidation');
    // Get the first invalid field
    var $invalidFields = fv.getInvalidFields().eq(0);
    // Get the tab that contains the first invalid field
    var $tabPane     = $invalidFields.parents();
    var valid_setup = fv.isValidContainer($tabPane);

    if(valid_setup)
    {
        if($('#create_billing_service').css('display') != 'none')
        {
            $('[name="billing_date"]').attr('disabled', false);
            $('[name="address"]').attr('disabled', false);
            $('.currency').attr('disabled', false);
            $('.client_name').attr('disabled', false);
            $("#saveBilling").attr("disabled", true);
            var selected_company_name = $(".client_name option:selected").text();
            $('#loadingBilling').show();
            $.ajax({
                type: 'POST',
                url: "billings/save_billing",
                data: $form.serialize() + '&company_name=' + encodeURIComponent(selected_company_name) + '&arr_for_check_no_assignment=' + JSON.stringify(arr_for_check_no_assignment),
                dataType: 'json',
                success: function(response){
                    $('#loadingBilling').hide();
                    if (response.Status === 1)
                    {
                        //$("#saveBilling").attr("disabled", false);
                        toastr.success(response.message, response.title);
                        var getUrl = window.location;
                        var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1] + "/billings";
                        window.location.href = baseUrl;
                    }
                    else if(response.Status === 2)
                    {
                        toastr.warning(response.message, response.title);
                        setTimeout(function() {
                            var getUrl = window.location;
                            var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1] + "/billings";
                            window.location.href = baseUrl;
                        }, 1000);
                    }
                    else if(response.Status === 3)
                    {
                        toastr.error(response.message, response.title);
                        setTimeout(function() {
                            var getUrl = window.location;
                            var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1] + "/billings";
                            window.location.href = baseUrl;
                        }, 1000);
                    }
                }
            });
        }
        else
        {
            toastr.error("Please set service engagement in this client.", "Error");
        }
    }
    else
    {
        for(var h = 0; h < service_array.length; h++)
        {
            if(service_array[h]['deactive'] == 1)
            {
                $(".service option[value='"+service_array[h]['id']+"']").attr("disabled", true);
            }
        }
    }

});

$(document).on('click',"#saveBilling",function(e){
    $('select.service').select2('destroy');
    for(var h = 0; h < service_array.length; h++)
    {
        if(service_array[h]['deactive'] == 1)
        {
            $(".service option[value='"+service_array[h]['id']+"']").removeAttr("disabled");
        }
    }
    $("select.service").select2();
    $("#create_billing_form").submit();
});

if(access_right_billing_module == "read" || access_right_unpaid_module == "read")
{
    $('input').attr("disabled", true);
    $('button').attr("disabled", true);
    $('select').attr("disabled", true);
    $('textarea').attr("disabled", true);
    $("#billing_service_info_Add").hide();
}