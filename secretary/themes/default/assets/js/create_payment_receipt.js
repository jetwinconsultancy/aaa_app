var pathArray = location.href.split( '/' );
var protocol = pathArray[0];
var host = pathArray[2];
var folder = pathArray[3];

var latest_gst_rate = 0;
var state_own_letterhead_checkbox = true;

$('#create_payment_receipt_form').formValidation({
    framework: 'bootstrap',
    icon: {

    },

    fields: {
        payment_receipt_date: {
            row: '.payment_receipt_date_div',
            validators: {
                notEmpty: {
                    message: 'The Date field is required.'
                }
            }
        },
        payment_receipt_no: {
            row: '.validate',
            validators: {
                notEmpty: {
                    message: 'The Receipt No field is required.'
                }
            }
        },
        client_name: {
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
                        //console.log(options);
                        return (options != null && options != "0");
                    }
                }
            }
        }
    }
});

var payment_receipt_description = {
        row: '.input-group',
        validators: {
            notEmpty: {
                message: 'The Payment Voucher Description field is required.'
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
    type = {
        //excluded: [':disabled', ':hidden', ':not(:visible)'],
        row: '.select-input-group',
        validators: {
            callback: {
                message: 'The Type field is required.',
                callback: function(value, validator, $field) {
                    var num = jQuery($field).parent().parent().parent().attr("num");
                    var options = validator.getFieldElements('type['+num+']').val();
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

function Receipt() {
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
                    else if(key == firm_info[0]["firm_currency"] && data.selected_currency == null)
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

    this.getBankAcc = function() {
        var url = base_url+"/"+folder+"/"+'companytype/getBankAcc';
        //console.log(url);
        var method = "get";
        var data = {};
        $('.bank_account').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            //console.log(data);
            $('.bank_account').find("option:eq(0)").html("Select Bank Account");
            //console.log(data);
            if(data.tp == 1){
                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    if(data.selected_bank_acc != null && key == data.selected_bank_acc)
                    {
                        option.attr('selected', 'selected');
                        //$('.currency').attr('disabled', true);
                    }
                    
                    $('.bank_account').append(option);
                });
                //$(".nationality").prop("disabled",false);
            }
            else{
                alert(data.msg);
            }
        }); 
    };
}

$(function() {
    var cn = new Receipt();
    cn.getCurrency();
    cn.getBankAcc();
});

toastr.options = {
    "positionClass": "toast-bottom-right"
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
    $('#loadingPaymentReceipt').hide();
});

$('.payment_receipt_date').datepicker({ 
    dateFormat:'dd/mm/yyyy',
    autoclose: true,
}).datepicker("setDate", "0")
.on('changeDate', function (selected) {
    var payment_receipt_date = $('.payment_receipt_date').val();
    
    $('#create_payment_receipt_form').formValidation('revalidateField', 'payment_receipt_date');
});

if(payment_receipt_top_info == undefined)
{
    $.ajax({
        type: "GET",
        url: "payment_voucher/get_payment_receipt_no",
        asycn: false,
        dataType: "json",
        success: function(response){
            $('[name="payment_receipt_no"]').val(response.receipt_no);
        }
    });

     $('#rate').val("1.0000");

    var d = new Date();

    var today_date = formatDateFunc(d);
    //get_gst_rate(today_date);

    //$("[name='hidden_own_letterhead_checkbox']").val(1);
    //$(".dropdown_vendor_name").show();
    $('.class_name').attr('disabled', true);
    $('#payment_receipt_no').attr('disabled', true);
}
else
{
    // if(payment_receipt_top_info[0]["postal_code"] != "" || payment_receipt_top_info[0]["street_name"] != "")
    // {
    //     var units = " ";

    //     if(payment_receipt_top_info[0]["unit_no1"] != "" || payment_receipt_top_info[0]["unit_no2"] != "")
    //     {
    //         units = '\n#'+payment_receipt_top_info[0]["unit_no1"] + " - " + payment_receipt_top_info[0]["unit_no2"];
    //     }
    //     else if(payment_receipt_top_info[0]["unit_no1"] != "")
    //     {
    //         units = payment_receipt_top_info[0]["unit_no1"];
    //     }
    //     else if(payment_receipt_top_info[0]["unit_no2"] != "")
    //     {
    //         units = payment_receipt_top_info[0]["unit_no2"];
    //     }
    //     var nonedit_address = payment_receipt_top_info[0]["street_name"]+units+' '+payment_receipt_top_info[0]["building_name"]+'\nSingapore '+payment_receipt_top_info[0]["postal_code"];
    // }
    // else
    // {
    //     var nonedit_address = payment_receipt_top_info[0]["foreign_address"];
    // }

    //$('[name="vendor_name"]').val(payment_receipt_top_info[0]["supplier_code"]);
    $('[name="client_name"]').val(payment_receipt_top_info[0]["client_name"]);
    $('[name="payment_receipt_no"]').val(payment_receipt_top_info[0]["receipt_no"]);
    $('[name="previous_payment_receipt_no"]').val(payment_receipt_top_info[0]["receipt_no"]);
    $('[name="payment_receipt_date"]').val(payment_receipt_top_info[0]["receipt_date"]);
    $('[name="address"]').val(payment_receipt_top_info[0]["address"]);
    $('[name="rate"]').val(payment_receipt_top_info[0]["rate"]);
    $('[name="cheque_number"]').val(payment_receipt_top_info[0]["cheque_number"]);

    $('[name="payment_receipt_date"]').attr('disabled', true);
    $('[name="address"]').attr('disabled', true);
    $('.client_name').attr('disabled', true);
    $('#payment_receipt_no').attr('disabled', true);

}

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

            if(payment_receipt_below_info)
            {
                gst_rate = 0;
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
    $(".amount").each(function(){
        if($(this).val() == '')
        {
            sum += 0;
        }
        else
        {
            sum += +parseFloat($(this).val().replace(/\,/g,''),2);

            if(payment_receipt_below_info)
            {
                gst_rate = 0;
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

    $("#sub_total").text(addCommas(sum.toFixed(2)));

    if(payment_receipt_below_info)
    {
        if(payment_receipt_below_info[0]["currency_id"] == "1")
        {
            gst_with_rate = " ";
            $("#gst_with_rate").text(gst_with_rate);
        }
        else if(payment_receipt_below_info[0]["currency_id"] != "1")
        {
            gst_with_rate = gst * parseFloat(payment_receipt_below_info[0]["rate"]);
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

function delete_payment_receipt(element) 
{
    var tr = jQuery(element).parent().parent(),
        payment_receipt_service_id = tr.find('input[name="payment_receipt_service_id[]"]').val();

    tr.closest("DIV.tr").remove();

    if($("#body_create_payment_receipt > div").length == 1)
    {
        if($('.delete_payment_receipt_button').css('display') == 'block')
        {
            $('.delete_payment_receipt_button').css('display','none');
        }
    }
    sum_total();
}

if(payment_receipt_below_info != undefined)
{
    $('#create_payment_receipt_service').show();
    $('#body_create_payment_receipt').show();
    $('#sub_total_create_payment_receipt').show();
    $('#gst_create_payment_receipt').show();
    $('#grand_total_create_payment_receipt').show();

    //assign gst
    $('#gst_rate').val(payment_receipt_below_info[0]["gst_rate"]);

    for(var t = 0; t < payment_receipt_below_info.length; t++)
    {
        $count_payment_receipt_service_info = t;
        $a=""; 
        /*<select class="input-sm" style="text-align:right;width: 150px;" id="position" name="position[]" onchange="optionCheck(this);"><option value="Director" >Director</option><option value="CEO" >CEO</option><option value="Manager" >Manager</option><option value="Secretary" >Secretary</option><option value="Auditor" >Auditor</option><option value="Managing Director" >Managing Director</option><option value="Alternate Director">Alternate Director</option></select>*/
        $a += '<div class="tr editing tr_payment_receipt" method="post" name="form'+$count_payment_receipt_service_info+'" id="form'+$count_payment_receipt_service_info+'" num="'+$count_payment_receipt_service_info+'">';
        //$a += '<div class="hidden"><input type="text" class="form-control" name="supplier_code" value=""/></div>';
        $a += '<div class="hidden"><input type="text" class="form-control" name="payment_receipt_service_id" value="'+payment_receipt_below_info[t]["payment_receipt_service_id"]+'"/></div>';
        $a += '<div class="hidden"><input type="text" class="form-control" name="vendor_payment_receipt_info_id['+$count_payment_receipt_service_info+']" id="vendor_payment_receipt_info_id" value="'+payment_receipt_below_info[t]["vendor_payment_receipt_info_id"]+'"/></div>';
        $a += '<div class="td" style="width: 150px;"><div class="select-input-group"><select class="input-sm form-control" name="type['+$count_payment_receipt_service_info+']" id="type'+$count_payment_receipt_service_info+'" style="width:200px;"><option value="0" data-invoice_description="" data-amount="">Select Type</option></select><div id="form_service"></div></div></div>';
        $a += '<div class="td"><div class="input-group mb-md"><textarea class="form-control" name="payment_receipt_description['+$count_payment_receipt_service_info+']"  id="payment_receipt_description" rows="3" style="width:420px">'+payment_receipt_below_info[t]["payment_receipt_description"]+'</textarea></div></div>';
        //<div style="width: 200px;display: inline-block; margin-right:10px;"><div style="font-weight: bold;">Period Start Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker period_start_date" id="period_start_date" name="period_start_date['+$count_payment_receipt_service_info+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value="'+payment_receipt_below_info[t]["period_start_date"]+'"></div></div><div style="width: 200px;display: inline-block"><div style="font-weight: bold;">Period End Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker period_end_date" id="period_end_date" name="period_end_date['+$count_payment_receipt_service_info+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value="'+payment_receipt_below_info[t]["period_end_date"]+'"></div></div>
        $a += '<div class="td" style="width:150px"><div class="input-group"><input type="text" name="amount['+$count_payment_receipt_service_info+']" class="numberdes form-control text-right amount" value="'+addCommas(payment_receipt_below_info[t]["payment_receipt_service_amount"])+'" id="amount" style="width:150px"/><div id="form_amount"></div></div></div>';
        $a += "<div class='td' style='width:100px'><div class='input-group'><input type='file' style='display:none' id='attachment"+$count_payment_receipt_service_info+"' multiple='' name='attachment["+$count_payment_receipt_service_info+"][]'><label for='attachment"+$count_payment_receipt_service_info+"' class='btn btn-primary attachment"+$count_payment_receipt_service_info+"'>Select Attachment</label><br/><span class='file_name' id='file_name"+$count_payment_receipt_service_info+"'></span><input type='hidden' class='hidden_attachment' name='hidden_attachment["+$count_payment_receipt_service_info+"]' value='"+payment_receipt_below_info[t]["attachment"]+"'/></div></div>";
        // $a += '<div class="td" style="width:150px"><div class="select-input-group"><select class="form-control" style="text-align:right;width: 165px;" name="unit_pricing['+$count_payment_receipt_service_info+']" id="unit_pricing"><option value="0" >Select Unit Pricing</option></select></div></div>';
        $a += '<div class="td action"><button type="button" class="btn btn-primary delete_payment_receipt_button" onclick="delete_payment_receipt(this)" style="display: none;">Delete</button></div>';
        $a += '</div>';

        /*<input type="button" value="Save" id="save" name="save'+$count_officer+'" class="btn btn-primary" onclick="save(this);">*/
        $("#body_create_payment_receipt").append($a); 

        if($("#body_create_payment_receipt > div").length > 1)
        {
            $('.delete_payment_receipt_button').css('display','block');
        }
        //console.log(type_array);
        var file_result = JSON.parse(payment_receipt_below_info[t]["attachment"]);
        var filename = "";
        //console.log(this.files);
        for(var i = 0; i < file_result.length; i++)
        {
        if(i == 0)
        {
            filename = '<a href="'+base_url+'uploads/pv_receipt_receipt/'+file_result[i]+'" target="_blank">'+file_result[i]+'</a>';
        }
        else
        {
            filename = filename + ", " + '<a href="'+base_url+'uploads/pv_receipt_receipt/'+file_result[i]+'" target="_blank">'+file_result[i]+'</a>';
        }
        }
        $("#file_name"+t).html(filename);

        $.each(type_array, function(key, val) {
            var option = $('<option />');
            option.attr('value', key).text(val);

            if(payment_receipt_below_info[t]["type_id"] != null && key == payment_receipt_below_info[t]["type_id"])
            {
                option.attr('selected', 'selected');
            }
            
            $("#form"+$count_payment_receipt_service_info+" #type"+$count_payment_receipt_service_info).append(option);
        });

        $("#form"+$count_payment_receipt_service_info+" #type"+$count_payment_receipt_service_info).select2();

        if(payment_receipt_top_info[0]['status'] == 2 || payment_receipt_top_info[0]['status'] == 3 || payment_receipt_top_info[0]['status'] == 4)
        {
            $("#create_payment_receipt_form :input").prop('readonly', true);
            $('#create_payment_receipt_form select').attr('disabled', true);
            $('.attachment').hide();
            $('.attachment'+$count_payment_receipt_service_info+'').hide();
            $('.delete_payment_receipt_button').hide();
        }

        $('#create_payment_receipt_form').formValidation('addField', 'type['+$count_payment_receipt_service_info+']', type);
        $('#create_payment_receipt_form').formValidation('addField', 'payment_receipt_description['+$count_payment_receipt_service_info+']', payment_receipt_description);
        $('#create_payment_receipt_form').formValidation('addField', 'amount['+$count_payment_receipt_service_info+']', amount);
        //$('#create_payment_receipt_form').formValidation('addField', 'unit_pricing['+$count_payment_receipt_service_info+']', validate_unit_pricing);
    }
    sum_first_total();
}
else
{
    showRow();
}

if(payment_receipt_below_info)
{
    $count_payment_receipt_service_info = payment_receipt_below_info.length;
}
else
{
    $count_payment_receipt_service_info = 1;
}

$(document).on('click',"#payment_receipt_service_info_Add",function() {
    
    $a=""; 
    /*<select class="input-sm" style="text-align:right;width: 150px;" id="position" name="position[]" onchange="optionCheck(this);"><option value="Director" >Director</option><option value="CEO" >CEO</option><option value="Manager" >Manager</option><option value="Secretary" >Secretary</option><option value="Auditor" >Auditor</option><option value="Managing Director" >Managing Director</option><option value="Alternate Director">Alternate Director</option></select>*/
    $a += '<div class="tr editing tr_payment_receipt" method="post" name="form'+$count_payment_receipt_service_info+'" id="form'+$count_payment_receipt_service_info+'" num="'+$count_payment_receipt_service_info+'">';
    //$a += '<div class="hidden"><input type="text" class="form-control" name="supplier_code" value=""/></div>';
    $a += '<div class="hidden"><input type="text" class="form-control" name="payment_receipt_service_id" value=""/></div>';
    $a += '<div class="hidden"><input type="text" class="form-control" name="vendor_payment_receipt_info_id['+$count_payment_receipt_service_info+']" id="vendor_payment_receipt_info_id" value=""/></div>';
    $a += '<div class="td"><div class="select-input-group"><select class="input-sm form-control" name="type['+$count_payment_receipt_service_info+']" id="type'+$count_payment_receipt_service_info+'" style="width:200px;"><option value="0" data-invoice_description="" data-amount="">Select Type</option></select><div id="form_service"></div></div></div>';
    $a += '<div class="td"><div class="input-group mb-md"><textarea class="form-control" name="payment_receipt_description['+$count_payment_receipt_service_info+']"  id="payment_receipt_description" rows="3" style="width:420px"></textarea></div></div>';
    //<div style="width: 200px;display: inline-block; margin-right:10px;"><div style="font-weight: bold;">Period Start Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker period_start_date" id="period_start_date" name="period_start_date['+$count_payment_receipt_service_info+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div></div><div style="width: 200px;display: inline-block"><div style="font-weight: bold;">Period End Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker period_end_date" id="period_end_date" name="period_end_date['+$count_payment_receipt_service_info+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div></div>
    $a += '<div class="td" style="width:150px"><div class="input-group"><input type="text" name="amount['+$count_payment_receipt_service_info+']" class="numberdes form-control text-right amount" value="" id="amount" style="width:150px"/><div id="form_amount"></div></div></div>';
    $a += '<div class="td" style="width:100px"><div class="input-group"><input type="file" style="display:none" id="attachment'+$count_payment_receipt_service_info+'" multiple="" name="attachment['+$count_payment_receipt_service_info+'][]"><label for="attachment'+$count_payment_receipt_service_info+'" class="btn btn-primary" class="attachment">Select Attachment</label><br/><span class="file_name"></span><input type="hidden" class="hidden_attachment" name="hidden_attachment['+$count_payment_receipt_service_info+']" value=""/></div></div>';
    //$a += '<div class="td" style="width:150px"><div class="select-input-group"><select class="form-control" style="text-align:right;width: 165px;" name="unit_pricing['+$count_payment_receipt_service_info+']" id="unit_pricing"><option value="0" >Select Unit Pricing</option></select><div id="form_unit_pricing"></div></div></div>';
    /*$a += '<div class="td action"><button type="button" class="btn btn-primary" onclick="edit_payment_receipt_info(this);">Save</button></div></div>';*/
    $a += '<div class="td action"><button type="button" class="btn btn-primary delete_payment_receipt_button" onclick="delete_payment_receipt(this)" style="display: block;">Delete</button></div>';
    $a += '</div>';

    /*<input type="button" value="Save" id="save" name="save'+$count_officer+'" class="btn btn-primary" onclick="save(this);">*/
    $("#body_create_payment_receipt").append($a); 

    if($("#body_create_payment_receipt > div").length > 1)
    {
        $('.delete_payment_receipt_button').css('display','block');
    }

    $.each(type_array, function(key, val) {
        var option = $('<option />');
        option.attr('value', key).text(val);
        
        $("#form"+$count_payment_receipt_service_info+" #type"+$count_payment_receipt_service_info).append(option);
    });

    //$("#form"+0+" #service").append(optgroup);

    $("#form"+$count_payment_receipt_service_info+" #type"+$count_payment_receipt_service_info).select2();

    $('#create_payment_receipt_form').formValidation('addField', 'type['+$count_payment_receipt_service_info+']', type);
    $('#create_payment_receipt_form').formValidation('addField', 'payment_receipt_description['+$count_payment_receipt_service_info+']', payment_receipt_description);
    $('#create_payment_receipt_form').formValidation('addField', 'amount['+$count_payment_receipt_service_info+']', amount);
    //$('#create_payment_receipt_form').formValidation('addField', 'unit_pricing['+$count_payment_receipt_service_info+']', validate_unit_pricing);

    $count_payment_receipt_service_info++;
});

function showRow(){
    //var supplier_code = $('#vendor_name option:selected').val();

    // if($("#currency option:selected").val() == 0)
    // {
    //     toastr.error("Please select a currency.", "Error");
    // }
    // else
    // {
        $.ajax({
            type: "GET",
            url: "companytype/get_payment_receipt_type",
            //data: {"supplier_code":supplier_code, "currency": $("#currency option:selected").val()}, // <--- THIS IS THE CHANGE
            dataType: "json",
            success: function(response){
                if(response.tp == 1)
                {

                    $a0=""; 

                    $a0 += '<div class="tr editing tr_payment_receipt" method="post" name="form'+0+'" id="form'+0+'" num="'+0+'">';
                    //$a0 += '<div class="hidden"><input type="text" class="form-control" name="supplier_code" value=""/></div>';
                    $a0 += '<div class="hidden"><input type="text" class="form-control" name="payment_receipt_service_id" value=""/></div>';
                    $a0 += '<div class="hidden"><input type="text" class="form-control" name="vendor_payment_receipt_info_id['+0+']" id="vendor_payment_receipt_info_id" value=""/></div>';
                    $a0 += '<div class="td"><div class="select-input-group"><select class="input-sm form-control" name="type['+0+']" id="type'+0+'" style="width:200px;"><option value="0" data-invoice_description="" data-amount="">Select Type</option></select><div id="form_service"></div></div></div>';
                    $a0 += '<div class="td"><div class="input-group mb-md"><textarea class="form-control" name="payment_receipt_description['+0+']"  id="payment_receipt_description" rows="3" style="width:420px"></textarea></div></div>';
                    //<div style="width: 200px;display: inline-block; margin-right:10px;"><div style="font-weight: bold;">Period Start Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker period_start_date" id="period_start_date" name="period_start_date['+0+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div></div><div style="width: 200px;display: inline-block"><div style="font-weight: bold;">Period End Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker period_end_date" id="period_end_date" name="period_end_date['+0+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div></div>
                    $a0 += '<div class="td" style="width:150px"><div class="input-group"><input type="text" name="amount['+0+']" class="numberdes form-control text-right amount" value="" id="amount" style="width:150px"/><div id="form_amount"></div></div></div>';
                    $a0 += '<div class="td" style="width:100px"><div class="input-group"><input type="file" style="display:none" id="attachment'+0+'" multiple="" name="attachment['+0+'][]"><label for="attachment'+0+'" class="btn btn-primary" class="attachment">Select Attachment</label><br/><span class="file_name"></span><input type="hidden" class="hidden_attachment" name="hidden_attachment['+0+']" value=""/></div></div>';
                    /*$a += '<div class="td action"><button type="button" class="btn btn-primary" onclick="edit_payment_receipt_info(this);">Save</button></div></div>';*/
                    // $a0 += '<div class="td" style="width:150px"><div class="select-input-group"><select class="form-control" style="text-align:right;width: 165px;" name="unit_pricing['+0+']" id="unit_pricing"><option value="0" >Select Unit Pricing</option></select></div></div>';
                    $a0 += '<div class="td action"><button type="button" class="btn btn-primary delete_payment_receipt_button" onclick="delete_payment_receipt(this)" style="display: none;">Delete</button></div>';
                    $a0 += '</div>';

                    
                    $("#body_create_payment_receipt").append($a0); 

                    if($("#body_create_payment_receipt > div").length > 1)
                    {
                        $('.delete_payment_receipt_button').css('display','block');
                    }

                    type_array = response.result;
                    $.each(response.result, function(key, val) {
                        var option = $('<option />');
                        option.attr('value', key).text(val);
                        
                        $("#form"+0+" #type"+0).append(option);
                    });

                    //$("#form"+0+" #service").append(optgroup);

                    $("#form"+0+" #type"+0).select2();

                    $('#create_payment_receipt_form').formValidation('addField', 'type['+0+']', type);
                    $('#create_payment_receipt_form').formValidation('addField', 'payment_receipt_description['+0+']', payment_receipt_description);
                    $('#create_payment_receipt_form').formValidation('addField', 'amount['+0+']', amount);  
                    $('#create_payment_receipt_form').formValidation('addField', 'unit_pricing['+0+']', validate_unit_pricing);  
                }

            }               
        });
        //$('#create_payment_voucher_form').formValidation('revalidateField', 'vendor_name');
    //}
}

$(document).on('change','#create_payment_receipt_form #currency',function(e){
    if($(this).val() == "1")
    {
        $("#rate").val("1.0000");
    }
    $('#create_payment_receipt_form').formValidation('revalidateField', 'currency');
});

$(document).on('change','[type=file]',function(){
    var filename = "";
    console.log(this.files[0]);
    for(var i = 0; i < this.files.length; i++)
    {
    if(i == 0)
    {
        filename = this.files[i].name;
    }
    else
    {
        filename = filename + ", " + this.files[i].name;
    }
    }
    $(this).parent().find(".file_name").html(filename);
    $(this).parent().find(".hidden_attachment").val("");
});

$(document).on("submit",function(e){
    
        e.preventDefault();
        var $form = $(e.target);

        var fv = $form.data('formValidation');
        // Get the first invalid field
        var $invalidFields = fv.getInvalidFields().eq(0);
        // Get the tab that contains the first invalid field
        var $tabPane     = $invalidFields.parents();
        var valid_setup = fv.isValidContainer($tabPane);

        if(valid_setup)
        {
            // if($('#create_payment_receipt_service').css('display') != 'none')
            // {
                $('[name="payment_receipt_date"]').attr('disabled', false);
                $('[name="address"]').attr('disabled', false);
                $('.currency').attr('disabled', false);
                //$('.vendor_name').attr('disabled', false);
                $('#payment_receipt_no').attr('disabled', false);
                $('.bank_account').attr('disabled', false);

                var formData = new FormData($('form')[0]);
                //formData.append('vendor_name_text', $(".vendor_name option:selected").text());
                $('#loadingPaymentReceipt').show();
                $.ajax({
                    type: 'POST', //$form.serialize()
                    url: "payment_voucher/save_payment_receipt",
                    data: formData,
                    dataType: 'json',
                    // Tell jQuery not to process data or worry about content-type
                    // You *must* include these options!
                    // + '&vendor_name_text=' + $(".vendor_name option:selected").text()
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(response){
                        //console.log(response.Status);
                        $('#loadingPaymentReceipt').hide();
                        if (response.Status === 1) 
                        {
                            toastr.success(response.message, response.title);
                            var getUrl = window.location;
                            var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1] + "/payment_voucher";
                            window.location.href = baseUrl;
                        }
                    }
                });
            // }
            // else
            // {
            //     toastr.error("Please set service engagement in this Vendor.", "error");
            // }
        }

});

$(document).on('click',"#savePaymentReceipt",function(e){
    $("#create_payment_receipt_form").submit();
});