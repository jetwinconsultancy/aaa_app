var pathArray = location.href.split( '/' );
var protocol = pathArray[0];
var host = pathArray[2];
var folder = pathArray[3];

var latest_gst_rate = 0;
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
        }/*,
        'invoice_description[0]': invoice_description,
        'amount[0]': amount,
        'service[0]': service*/
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
        //console.log(data);
        var successRes = function(data) {
            success(data);
        };

        var errorRes = function(e) {
          //console.log(e);
          alert("Error found \nError Code: "+e.status+" \nError Message: "+e.statusText);
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
        //console.log(url);
        var method = "get";
        var data = {};
        $('.currency').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            //console.log(data);
            $('.currency').find("option:eq(0)").html("Select Currency");
            //console.log(data);
            if(data.tp == 1){
                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    if(data.selected_currency != null && key == data.selected_currency)
                    {
                        option.attr('selected', 'selected');
                        //$('.currency').attr('disabled', true);
                    }
                    else if(key == 1)
                    {
                        option.attr('selected', 'selected');
                    }
                    $('.currency').append(option);
                });
                //$(".nationality").prop("disabled",false);
            }
            else{
                alert(data.msg);
            }
        }); 
    };

    this.getClientName = function() {
        var url = base_url+"/"+folder+"/"+'companytype/getClientName';
        //console.log(url);
        var method = "get";
        var data = {};
        $('.client_name').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            //console.log(data);
            $('.client_name').find("option:eq(0)").html("Select Client Name");
            //console.log(data);
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
                //$(".nationality").prop("disabled",false);
            }
            else{
                alert(data.msg);
            }
        }); 
    };

    // this.frequency = function() {
    //     $.ajax({
    //         type: "GET",
    //         url: "masterclient/get_billing_info_frequency",
    //         //data: {"frequency": client_info[i]["frequency"]},
    //         dataType: "json",
    //         success: function(data){
    //             //console.log(data);
    //             $('#loadingmessage').hide();
    //             $("#frequency").find("option:eq(0)").html("Select Frequency");
    //             if(data.tp == 1){
    //                 $.each(data['result'], function(key, val) {
    //                     var option = $('<option />');
    //                     option.attr('value', key).text(val);
    //                     if(data.selected_frequency != null && key == data.selected_frequency)
    //                     {
    //                         option.attr('selected', 'selected');
    //                     }
    //                     $("#frequency").append(option);
    //                 });
    //             }
    //             else{
    //                 alert(data.msg);
    //             } 
    //         }               
    //     }); 
    // };

    // this.type_of_day = function() {
    //     $.ajax({
    //         type: "GET",
    //         url: "masterclient/get_type_of_day",
    //         //data: {"type_of_day": client_info[i]["type_of_day"]},
    //         dataType: "json",
    //         success: function(data){
    //             console.log(data);
    //             if(data.tp == 1){
    //                 $.each(data['result'], function(key, val) {
    //                     var option = $('<option />');
    //                     option.attr('value', key).text(val);
    //                     if(data.selected_type_of_day != null && key == data.selected_type_of_day)
    //                     {
    //                         option.attr('selected', 'selected');
    //                     }
    //                     $("#type_of_day").append(option);
    //                 });
    //             }
    //             else{
    //                 alert(data.msg);
    //             }  
    //         }               
    //     });
    // }
}

$(function() {
    var cn = new Client();
    cn.getClientName();
    cn.getCurrency();
    //cn.frequency();
    //cn.type_of_day();
    //cm.getDirectorSignature1();
});

toastr.options = {
    "positionClass": "toast-bottom-right"
}

function optionCheckService(service_element) 
{
    var tr = jQuery(service_element).parent().parent();
    console.log(jQuery(service_element).val());
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
.on('changeDate', function (selected) {
    //console.log($('.billing_date').val());
    var billing_date = $('.billing_date').val();
    get_gst_rate(billing_date);
    
    $('#create_billing_form').formValidation('revalidateField', 'billing_date');
});

//console.log(billing_top_info);
if(billing_top_info == undefined)
{
    /*var d = new Date();
    function f(n) { return n < 10 ? '0' + n : n; }  
    var random_num = Math.floor(Math.random() * (999999 -  100000)) + 100000;
    random_num = d.getFullYear() + f(d.getMonth()+1) + f(d.getDate()) + random_num; 
    var invoice_number = "INV - " + random_num;*/
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
}
else
{
    if(billing_top_info[0]["postal_code"] != "" || billing_top_info[0]["street_name"] != "")
    {
        var units = " ";

        if(billing_top_info[0]["unit_no1"] != "" || billing_top_info[0]["unit_no2"] != "")
        {
            units = '\n#'+billing_top_info[0]["unit_no1"] + " - " + billing_top_info[0]["unit_no2"];
        }
        else if(billing_top_info[0]["unit_no1"] != "")
        {
            units = billing_top_info[0]["unit_no1"];
        }
        else if(billing_top_info[0]["unit_no2"] != "")
        {
            units = billing_top_info[0]["unit_no2"];
        }
        var nonedit_address = billing_top_info[0]["street_name"]+units+' '+billing_top_info[0]["building_name"]+'\nSingapore '+billing_top_info[0]["postal_code"];
    }
    else
    {
        var nonedit_address = billing_top_info[0]["foreign_address"];
    }

    $('[name="invoice_no"]').val(billing_top_info[0]["invoice_no"]);
    $('[name="previous_invoice_no"]').val(billing_top_info[0]["invoice_no"]);
    $('[name="billing_date"]').val(billing_top_info[0]["invoice_date"]);
    $('[name="address"]').val(nonedit_address);
    $('[name="rate"]').val(billing_top_info[0]["rate"]);

    //$('[name="invoice_no"]').attr('disabled', true);
    $('[name="billing_date"]').attr('disabled', true);
    $('[name="address"]').attr('disabled', true);

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
    console.log(state); // true | false

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

$(".amount").live('change',function(){
    sum_total();
});
$(".rate").live('change',function(){
    sum_total();
});
$(".currency").live('change',function(){
    sum_total();
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
                gst_rate = billing_below_info[0]['gst_rate'];
            }
            else
            {
                gst_rate = latest_gst_rate;
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
                gst_rate = billing_below_info[0]['gst_rate'];
            }
            else
            {
                gst_rate = latest_gst_rate;
            }

            before_gst = ((gst_rate / 100) * parseFloat($(this).val().replace(/\,/g,''),2));
            //console.log("total==="+before_gst);
            gst += parseFloat(before_gst.toFixed(2));
        }
    });
    //$(".total").val(sum);
    console.log(gst);
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

    //assign gst
    $('#gst_rate').val(billing_below_info[0]["gst_rate"]);

    for(var t = 0; t < billing_below_info.length; t++)
    {
        $count_billing_service_info = t;
        $a=""; 
        /*<select class="input-sm" style="text-align:right;width: 150px;" id="position" name="position[]" onchange="optionCheck(this);"><option value="Director" >Director</option><option value="CEO" >CEO</option><option value="Manager" >Manager</option><option value="Secretary" >Secretary</option><option value="Auditor" >Auditor</option><option value="Managing Director" >Managing Director</option><option value="Alternate Director">Alternate Director</option></select>*/
        $a += '<div class="tr editing tr_billing" method="post" name="form'+$count_billing_service_info+'" id="form'+$count_billing_service_info+'" num="'+$count_billing_service_info+'">';
        $a += '<div class="hidden"><input type="text" class="form-control" name="company_code" value=""/></div>';
        $a += '<div class="hidden"><input type="text" class="form-control" name="billing_service_id" value="'+billing_below_info[t]["billing_service_id"]+'"/></div>';
        $a += '<div class="hidden"><input type="text" class="form-control" name="client_billing_info_id['+$count_billing_service_info+']" id="client_billing_info_id" value="'+billing_below_info[t]["client_billing_info_id"]+'"/></div>';
        $a += '<div class="td" style="width: 150px;"><div class="select-input-group"><select class="input-sm form-control" name="service['+$count_billing_service_info+']" id="service" style="width:200px !important;"><option value="0" data-invoice_description="" data-amount="">Select Service</option></select><div id="form_service"></div></div></div>';
        $a += '<div class="td"><div class="input-group mb-md"><textarea class="form-control" name="invoice_description['+$count_billing_service_info+']"  id="invoice_description" rows="3" style="width:420px">'+billing_below_info[t]["invoice_description"]+'</textarea></div><div style="width: 200px;display: inline-block; margin-right:10px;"><div style="font-weight: bold;">Period Start Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker period_start_date" id="period_start_date" name="period_start_date['+$count_billing_service_info+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value="'+billing_below_info[t]["period_start_date"]+'"></div></div><div style="width: 200px;display: inline-block"><div style="font-weight: bold;">Period End Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker period_end_date" id="period_end_date" name="period_end_date['+$count_billing_service_info+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value="'+billing_below_info[t]["period_end_date"]+'"></div></div></div>';
        $a += '<div class="td" style="width:150px"><div class="input-group"><input type="text" name="amount['+$count_billing_service_info+']" class="numberdes form-control text-right amount" value="'+addCommas(billing_below_info[t]["billing_service_amount"])+'" id="amount" style="width:150px"/><div id="form_amount"></div></div></div>';
        $a += '<div class="td" style="width:150px"><div class="select-input-group"><select class="form-control" style="text-align:right;width: 165px;" name="unit_pricing['+$count_billing_service_info+']" id="unit_pricing"><option value="0" >Select Unit Pricing</option></select></div></div>';
        $a += '<div class="td action"><button type="button" class="btn btn-primary delete_billing_button" onclick="delete_billing(this)" style="display: none;">Delete</button></div>';
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

        $.each(get_unit_pricing, function(key, val) {
            console.log(billing_below_info[t]["unit_pricing"]);

            var option = $('<option />');
            option.attr('value', val['id']).text(val['unit_pricing_name']);
            
            if(billing_below_info[t]["unit_pricing"] != null && val['id'] == billing_below_info[t]["unit_pricing"])
            {
                option.attr('selected', 'selected');
            }

            $("#form"+$count_billing_service_info+" #unit_pricing").append(option);
        });

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
        // if(category_description != service_category_array[t]['category_description'])
        // {
            if(category_description != service_category[j]['category_description'])
            {
                if(optgroup != '')
                {
                    $("#form"+$count_billing_service_info+" #service").append(optgroup);
                }
                optgroup = $('<optgroup label="' + service_category[j]['category_description'] + '" />');
                //console.log(service_category_array[t]['category_description']);
            }

            category_description = service_category[j]['category_description'];
            
            for(var h = 0; h < service_dropdown.length; h++)
            {
                if(category_description == service_dropdown[h]['category_description'])
                {
                    //console.log(service_array[h]['service_name']);
                    var option = $('<option />');
                    option.attr('data-description', service_dropdown[h]['invoice_description']).attr('data-currency', service_dropdown[h]['currency']).attr('data-unit_pricing', service_dropdown[h]['unit_pricing']).attr('data-amount', service_dropdown[h]['amount']).attr('value', service_dropdown[h]['id']).text(service_dropdown[h]['service_name']).appendTo(optgroup);
                    
                    if(billing_below_info[t]["service"] != null && service_dropdown[h]['id'] == billing_below_info[t]["service"])
                    {
                        option.attr('selected', 'selected');
                    }
                }
            }
        }
        $("#form"+t+" #service").append(optgroup);
        $("#form"+t+" #service").select2();

        service_array = service_dropdown;
        service_category_array = service_category;
        unit_pricing = get_unit_pricing;

        $('#create_billing_form').formValidation('addField', 'service['+$count_billing_service_info+']', service);
        $('#create_billing_form').formValidation('addField', 'invoice_description['+$count_billing_service_info+']', invoice_description);
        $('#create_billing_form').formValidation('addField', 'amount['+$count_billing_service_info+']', amount);
        $('#create_billing_form').formValidation('addField', 'unit_pricing['+$count_billing_service_info+']', validate_unit_pricing);
    }
    sum_first_total();
}

$(document).on('change','#service',function(e){
    var num = $(this).parent().parent().parent().attr("num");
    
    var descriptionValue = $(this).find(':selected').data('description');
    var amountValue = $(this).find(':selected').data('amount');
    // var currencyValue = $(this).find(':selected').data('currency');
    var unit_pricingValue = $(this).find(':selected').data('unit_pricing');

    $(this).parent().parent().parent().find('#invoice_description').text(descriptionValue);
    $(this).parent().parent().parent().find('#amount').val(addCommas(amountValue));
    //$(this).parent().parent().parent().find('#currency').val(currencyValue);
    $(this).parent().parent().parent().find('#unit_pricing').val(addCommas(unit_pricingValue));

    $('#create_billing_form').formValidation('revalidateField', 'service['+num+']');
    $('#create_billing_form').formValidation('revalidateField', 'invoice_description['+num+']');
    $('#create_billing_form').formValidation('revalidateField', 'amount['+num+']');
    $('#create_billing_form').formValidation('revalidateField', 'unit_pricing['+num+']');
    sum_total();
});

if(billing_below_info)
{
    $count_billing_service_info = billing_below_info.length;
}
else
{
    $count_billing_service_info = 1;
}

//$count_billing_service_info = 1;
$(document).on('click',"#billing_service_info_Add",function() {
    
    $a=""; 
    /*<select class="input-sm" style="text-align:right;width: 150px;" id="position" name="position[]" onchange="optionCheck(this);"><option value="Director" >Director</option><option value="CEO" >CEO</option><option value="Manager" >Manager</option><option value="Secretary" >Secretary</option><option value="Auditor" >Auditor</option><option value="Managing Director" >Managing Director</option><option value="Alternate Director">Alternate Director</option></select>*/
    $a += '<div class="tr editing tr_billing" method="post" name="form'+$count_billing_service_info+'" id="form'+$count_billing_service_info+'" num="'+$count_billing_service_info+'">';
    $a += '<div class="hidden"><input type="text" class="form-control" name="company_code" value=""/></div>';
    $a += '<div class="hidden"><input type="text" class="form-control" name="billing_service_id" value=""/></div>';
    $a += '<div class="hidden"><input type="text" class="form-control" name="client_billing_info_id['+$count_billing_service_info+']" id="client_billing_info_id" value=""/></div>';
    $a += '<div class="td"><div class="select-input-group"><select class="input-sm form-control" name="service['+$count_billing_service_info+']" id="service" style="width:200px;"><option value="0" data-invoice_description="" data-amount="">Select Service</option></select><div id="form_service"></div></div></div>';
    $a += '<div class="td"><div class="input-group mb-md"><textarea class="form-control" name="invoice_description['+$count_billing_service_info+']"  id="invoice_description" rows="3" style="width:420px"></textarea></div><div style="width: 200px;display: inline-block; margin-right:10px;"><div style="font-weight: bold;">Period Start Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker period_start_date" id="period_start_date" name="period_start_date['+$count_billing_service_info+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div></div><div style="width: 200px;display: inline-block"><div style="font-weight: bold;">Period End Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker period_end_date" id="period_end_date" name="period_end_date['+$count_billing_service_info+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div></div></div>';
    $a += '<div class="td" style="width:150px"><div class="input-group"><input type="text" name="amount['+$count_billing_service_info+']" class="numberdes form-control text-right amount" value="" id="amount" style="width:150px"/><div id="form_amount"></div></div></div>';
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
                    $("#form"+$count_billing_service_info+" #service").append(optgroup);
                }
                optgroup = $('<optgroup label="' + service_category_array[t]['category_description'] + '" />');
                //console.log(service_category_array[t]['category_description']);
            }

            category_description = service_category_array[t]['category_description'];
            
            for(var h = 0; h < service_array.length; h++)
            {
                if(category_description == service_array[h]['category_description'])
                {
                    //console.log(service_array[h]['service_name']);
                    var option = $('<option />');
                    option.attr('data-description', service_array[h]['invoice_description']).attr('data-currency', service_array[h]['currency']).attr('data-unit_pricing', service_array[h]['unit_pricing']).attr('data-amount', service_array[h]['amount']).attr('value', service_array[h]['id']).text(service_array[h]['service_name']).appendTo(optgroup);
                }
            }
            //}
        

        
    }
    $("#form"+$count_billing_service_info+" #service").append(optgroup);   

    $("#form"+$count_billing_service_info+" #service").select2();

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
                        $('#create_billing_form').formValidation('revalidateField', 'address');
                        $("#address").attr('readOnly', false);
                    }
                    else
                    {
                        $('[name="address"]').val(response.address);
                        $('#create_billing_form').formValidation('revalidateField', 'address');
                        $("#address").attr('readOnly', true);
                    }
                    

                    service_array = response.service;
                    service_category_array = response.selected_billing_info_service_category;
                    unit_pricing = response.unit_pricing;

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

                    $a0=""; 

                    $a0 += '<div class="tr editing tr_billing" method="post" name="form'+0+'" id="form'+0+'" num="'+0+'">';
                    $a0 += '<div class="hidden"><input type="text" class="form-control" name="company_code" value=""/></div>';
                    $a0 += '<div class="hidden"><input type="text" class="form-control" name="billing_service_id" value=""/></div>';
                    $a0 += '<div class="hidden"><input type="text" class="form-control" name="client_billing_info_id['+0+']" id="client_billing_info_id" value=""/></div>';
                    $a0 += '<div class="td"><div class="select-input-group"><select class="input-sm form-control" name="service['+0+']" id="service" style="width:200px;"><option value="0" data-invoice_description="" data-amount="">Select Service</option></select><div id="form_service"></div></div></div>';
                    $a0 += '<div class="td"><div class="input-group mb-md"><textarea class="form-control" name="invoice_description['+0+']"  id="invoice_description" rows="3" style="width:420px"></textarea></div><div style="width: 200px;display: inline-block; margin-right:10px;"><div style="font-weight: bold;">Period Start Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker period_start_date" id="period_start_date" name="period_start_date['+0+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div></div><div style="width: 200px;display: inline-block"><div style="font-weight: bold;">Period End Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker period_end_date" id="period_end_date" name="period_end_date['+0+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div></div></div>';
                    $a0 += '<div class="td" style="width:150px"><div class="input-group"><input type="text" name="amount['+0+']" class="numberdes form-control text-right amount" value="" id="amount" style="width:150px"/><div id="form_amount"></div></div></div>';
                    /*$a += '<div class="td action"><button type="button" class="btn btn-primary" onclick="edit_billing_info(this);">Save</button></div></div>';*/
                    $a0 += '<div class="td" style="width:150px"><div class="select-input-group"><select class="form-control" style="text-align:right;width: 165px;" name="unit_pricing['+0+']" id="unit_pricing"><option value="0" >Select Unit Pricing</option></select></div></div>';
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
                        //console.log(val['unit_pricing_name']);
                        var option = $('<option />');
                        option.attr('value', val['id']).text(val['unit_pricing_name']);
                        
                        $("#form"+0+" #unit_pricing").append(option);
                    });

                    // for(var i = 0; i < service_array.length; i++)
                    // {
                    //     //res[service_array[i]['service']] = service_array[i]['service_name'];

                    //     var option = $('<option />');
                    //     option.attr('data-invoice_description', service_array[i]['invoice_description']);
                    //     option.attr('data-amount', service_array[i]['amount']);
                    //     option.attr('data-client_billing_info_id', service_array[i]['client_billing_info_id']);
                    //     option.attr('value', service_array[i]['service']).text(service_array[i]['service_name']);

                        
                        
                    //     $("#form"+0+" #service").append(option);
                    // }

                    var category_description = '';
                    var optgroup = '';
                    // for(var t = 0; t < service_array.length; t++)
                    // {
                    //     if(category_description != service_array[t]['category_description'])
                    //     {
                    //         if(optgroup != '')
                    //         {
                    //             $("#form"+0+" #service").append(optgroup);
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
                                    $("#form"+0+" #service").append(optgroup);
                                }
                                optgroup = $('<optgroup label="' + service_category_array[t]['category_description'] + '" />');
                                //console.log(service_category_array[t]['category_description']);
                            }

                            category_description = service_category_array[t]['category_description'];
                            
                            for(var h = 0; h < service_array.length; h++)
                            {
                                if(category_description == service_array[h]['category_description'])
                                {
                                    //console.log(service_array[h]['service_name']);
                                    var option = $('<option />');
                                    option.attr('data-description', service_array[h]['invoice_description']).attr('data-currency', service_array[h]['currency']).attr('data-unit_pricing', service_array[h]['unit_pricing']).attr('data-amount', service_array[h]['amount']).attr('value', service_array[h]['id']).text(service_array[h]['service_name']).appendTo(optgroup);
                                }
                            }
                            //}
                        

                        
                    }
                    $("#form"+0+" #service").append(optgroup);

                    $("#form"+0+" #service").select2();

                    $('#create_billing_form').formValidation('addField', 'service['+0+']', service);
                    $('#create_billing_form').formValidation('addField', 'invoice_description['+0+']', invoice_description);
                    $('#create_billing_form').formValidation('addField', 'amount['+0+']', amount);  
                    $('#create_billing_form').formValidation('addField', 'unit_pricing['+0+']', validate_unit_pricing);  
                }

            }               
        });
        $('#create_billing_form').formValidation('revalidateField', 'client_name');
    }
}

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
                $('#gst_rate').val(response.get_gst_rate);
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
        //console.log(fv);
        // Get the first invalid field
        var $invalidFields = fv.getInvalidFields().eq(0);
        // Get the tab that contains the first invalid field
        var $tabPane     = $invalidFields.parents();
        var valid_setup = fv.isValidContainer($tabPane);

        //fv.disableSubmitButtons(false);
        //console.log($form.valid());
        //console.log(valid_setup);
        if(valid_setup)
        {
            if($('#create_billing_service').css('display') != 'none')
            {
                //$("#create_billing_form").formValidation('destroy');
                //$('[name="invoice_no"]').attr('disabled', false);
                $('[name="billing_date"]').attr('disabled', false);
                $('[name="address"]').attr('disabled', false);
                $('.currency').attr('disabled', false);
                $('.client_name').attr('disabled', false);
                $.ajax({
                    type: 'POST',
                    url: "billings/save_billing",
                    data: $form.serialize(),
                    dataType: 'json',
                    success: function(response){
                        //console.log(response.Status);

                        if (response.Status === 1) 
                        {
                            toastr.success(response.message, response.title);
                            //console.log(response);
                            var getUrl = window.location;
                            var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1] + "/billings";
                            //console.log(baseUrl);
                            window.location.href = baseUrl;
                            //$('#modal_payment').modal('toggle');
                            //location.reload();
                        }
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