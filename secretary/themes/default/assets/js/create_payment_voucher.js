var pathArray = location.href.split( '/' );
var protocol = pathArray[0];
var host = pathArray[2];
var folder = pathArray[3];

var latest_gst_rate = 0;
var state_own_letterhead_checkbox = true;

$('#create_payment_voucher_form').formValidation({
    framework: 'bootstrap',
    icon: {

    },

    fields: {
        payment_voucher_date: {
            row: '.payment_voucher_date_div',
            validators: {
                notEmpty: {
                    message: 'The Date field is required.'
                }
            }
        },
        payment_voucher_no: {
            row: '.validate',
            validators: {
                notEmpty: {
                    message: 'The Payment Voucher No field is required.'
                }
            }
        },
        client_type: {
            row: '.client_type_group',
            validators: {
                callback: {
                    message: 'The Client Type field is required.',
                    callback: function(value, validator, $field) {
                        var options = validator.getFieldElements('client_type').val();
                        //console.log(options);
                        return (options != null && options != "0");
                    }
                }
            }
        },
        vendor_name: {
            row: '.input-group',
            validators: {
                callback: {
                    message: 'The Vendor Name field is required.',
                    callback: function(value, validator, $field) {
                        var options = validator.getFieldElements('vendor_name').val();
                        //console.log(options);
                        return (options != null && options != "0");
                    }
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

var payment_voucher_description = {
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

function Pv() {
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
                    else if(key == firm_info[0]["firm_currency"] && data.selected_currency == null) //key == 1
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

    this.getVendorName = function() {
        var url = base_url+"/"+folder+"/"+'companytype/getVendorName';
        //console.log(url);
        var method = "get";
        var data = {};
        $('.vendor_name').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            //console.log(data);
            $('.vendor_name').find("option:eq(0)").html("Select Vendor Name");
            //console.log(data);
            if(data.tp == 1){
                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    if(data.selected_vendor_name != null && key == data.selected_vendor_name)
                    {
                        option.attr('selected', 'selected');
                        $('.vendor_name').attr('disabled', true);
                    }
                    $('.vendor_name').append(option);
                });
                $('#vendor_name').select2();
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
                    if(payment_voucher_top_info != undefined)
                    {
                        if(payment_voucher_top_info[0]["supplier_code"] != null && key == payment_voucher_top_info[0]["supplier_code"])
                        {
                            option.attr('selected', 'selected');
                            $('.client_name').attr('disabled', true);
                        }
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
}

$(function() {
    var cn = new Pv();
    cn.getClientName();
    cn.getVendorName();
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
    $('#loadingPaymentVoucher').hide();
});

$('.payment_voucher_date').datepicker({ 
    dateFormat:'dd/mm/yyyy',
    autoclose: true,
}).datepicker("setDate", "0")
.on('changeDate', function (selected) {
    var payment_voucher_date = $('.payment_voucher_date').val();
    //get_gst_rate(payment_voucher_date);
    
    $('#create_payment_voucher_form').formValidation('revalidateField', 'payment_voucher_date');
});

if(payment_voucher_top_info == undefined)
{
    $.ajax({
        type: "GET",
        url: "payment_voucher/get_payment_voucher_no",
        asycn: false,
        dataType: "json",
        success: function(response){
            $('[name="payment_voucher_no"]').val(response.payment_voucher_no);
        }
    });

     $('#rate').val("1.0000");

    var d = new Date();

    var today_date = formatDateFunc(d);
    //get_gst_rate(today_date);

    //$("[name='hidden_own_letterhead_checkbox']").val(1);
    $(".dropdown_vendor_name").show();
    $('.text_vendor_name').attr('disabled', true);
    $('#payment_voucher_no').attr('disabled', true);

    $(".dropdown_client_name").show();
    $('.text_vendor_name').attr('disabled', true);
}
else
{

    if(payment_voucher_top_info[0]["postal_code"] != "" || payment_voucher_top_info[0]["street_name"] != "")
    {
        var units = " ";

        if(payment_voucher_top_info[0]["unit_no1"] != "" || payment_voucher_top_info[0]["unit_no2"] != "")
        {
            units = '\n#'+payment_voucher_top_info[0]["unit_no1"] + " - " + payment_voucher_top_info[0]["unit_no2"];
        }
        else if(payment_voucher_top_info[0]["unit_no1"] != "")
        {
            units = payment_voucher_top_info[0]["unit_no1"];
        }
        else if(payment_voucher_top_info[0]["unit_no2"] != "")
        {
            units = payment_voucher_top_info[0]["unit_no2"];
        }
        var nonedit_address = payment_voucher_top_info[0]["street_name"]+units+' '+payment_voucher_top_info[0]["building_name"]+'\nSingapore '+payment_voucher_top_info[0]["postal_code"];
    }
    else
    {
        if(payment_voucher_top_info[0]["foreign_address2"] != "")
        {
            var value_foreign_address2 = '\n' + payment_voucher_top_info[0]["foreign_address2"];
        }
        else
        {
            var value_foreign_address2 = '';
        }
        var foreign_address = payment_voucher_top_info[0]["foreign_address1"] + value_foreign_address2 + '\n' + payment_voucher_top_info[0]["foreign_address3"];
        var nonedit_address = foreign_address;
    }
    //console.log(payment_voucher_top_info[0]["supplier_code"]);
    if(payment_voucher_top_info[0]["client_type"] == 2)
    {
        $(".tr_client_name").show();
        $(".tr_vendor_name").hide();
        $('[name="client_name"]').val(payment_voucher_top_info[0]["supplier_code"]);
        $('[name="text_client_name"]').val(payment_voucher_top_info[0]["vendor_name"]);
        $('[name="unassign_ccy"]').val(payment_voucher_top_info[0]["previous_cn_currency"]);
        $('[name="unassign_amt"]').val(payment_voucher_top_info[0]["previous_total_cn_out_of_balance"]);
        $(".input_client_name").show();
        $('.text_client_name').attr('disabled', true);
    }
    else
    {
        $(".tr_client_name").hide();
        $(".tr_vendor_name").show();
        $('[name="vendor_name"]').val(payment_voucher_top_info[0]["supplier_code"]);
        $('[name="text_vendor_name"]').val(payment_voucher_top_info[0]["vendor_name"]);
        $('[name="unassign_ccy"]').val("");
        $('[name="unassign_amt"]').val(0);
        $(".input_vendor_name").show();
        $('.text_vendor_name').attr('disabled', true);
    }
    $('[name="client_type"]').attr('disabled', true);
    $('[name="client_type"]').val(payment_voucher_top_info[0]["client_type"]);
    $('[name="payment_voucher_no"]').val(payment_voucher_top_info[0]["payment_voucher_no"]);
    $('[name="previous_payment_voucher_no"]').val(payment_voucher_top_info[0]["payment_voucher_no"]);
    $('[name="payment_voucher_date"]').val(payment_voucher_top_info[0]["payment_voucher_date"]);
    $('[name="address"]').val(nonedit_address);
    $('[name="rate"]').val(payment_voucher_top_info[0]["rate"]);
    $('[name="cheque_number"]').val(payment_voucher_top_info[0]["cheque_number"]);

    //$('[name="payment_voucher_no"]').attr('disabled', true);
    $('[name="payment_voucher_date"]').attr('disabled', true);
    $('[name="address"]').attr('disabled', true);
    $('#payment_voucher_no').attr('disabled', true);

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
$(".client_type").live('change',function(){
    //console.log($(this).val());
    if($(this).val() == 1)
    {
        $(".tr_vendor_name").show();
        $(".tr_client_name").hide();
    }
    else if($(this).val() == 2)
    {
        $(".tr_vendor_name").hide();
        $(".tr_client_name").show();
    }
    else
    {
        $(".tr_vendor_name").hide();
        $(".tr_client_name").hide();
    }
    $(".vendor_name").select2("val", "0");
    $(".client_name").select2("val", "0");
    $(".unassign_ccy").val("");
    $(".unassign_amt").val(0);
});


function sum_total(){
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

            if(payment_voucher_below_info)
            {
                //assign gst
                //gst_rate = payment_voucher_below_info[0]['gst_rate'];
                gst_rate = 0;
            }
            else
            {
                gst_rate = latest_gst_rate;
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

    if($(".client_type").val() == "2")
    {
        check_unassign_amt_val();
    }
}

function check_unassign_amt_val()
{
    var unassign_amt = parseFloat($(".unassign_amt").val().replace(/\,/g,''));
    var grand_total_amt = parseFloat($("#grand_total").text().replace(/\,/g,''));
    console.log(unassign_amt);
    console.log(grand_total_amt);
    if(grand_total_amt > unassign_amt)
    {
        $(".amount").each(function(){
            $(this).val("");
        });
        sum_total();
        toastr.error("The Grant Total amount is greater than Unassign Amount.", "Error");
    }
}

function sum_first_total(){
    var sum = 0;
    var before_gst = 0, gst = 0, gst_rate = 0, grand_total = 0, gst_with_rate = 0;
    $(".amount").each(function(){
        console.log($(this).val());
        if($(this).val() == '')
        {
            sum += 0;
        }
        else
        {
            sum += +parseFloat($(this).val().replace(/\,/g,''),2);

            if(payment_voucher_below_info)
            {
                //assign gst
                //gst_rate = payment_voucher_below_info[0]['gst_rate'];
                gst_rate = 0;
            }
            else
            {
                gst_rate = latest_gst_rate;
            }

            before_gst = ((gst_rate / 100) * parseFloat($(this).val().replace(/\,/g,''),2));
            gst += parseFloat(before_gst.toFixed(2));
        }
    });

    $("#sub_total").text(addCommas(sum.toFixed(2)));

    if(payment_voucher_below_info)
    {
        if(payment_voucher_below_info[0]["currency_id"] == "1")
        {
            gst_with_rate = " ";
            $("#gst_with_rate").text(gst_with_rate);
        }
        else if(payment_voucher_below_info[0]["currency_id"] != "1")
        {
            gst_with_rate = gst * parseFloat(payment_voucher_below_info[0]["rate"]);
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

function delete_payment_voucher(element) 
{
    var tr = jQuery(element).parent().parent(),
        payment_voucher_service_id = tr.find('input[name="payment_voucher_service_id[]"]').val();

    tr.closest("DIV.tr").remove();
    if($("#body_create_payment_voucher > div").length == 1)
    {
        if($('.delete_payment_voucher_button').css('display') == 'block')
        {
            $('.delete_payment_voucher_button').css('display','none');
        }
    }
    sum_total();
}

if(payment_voucher_below_info != undefined)
{
    $('#create_payment_voucher_service').show();
    $('#body_create_payment_voucher').show();
    $('#sub_total_create_payment_voucher').show();
    $('#gst_create_payment_voucher').show();
    $('#grand_total_create_payment_voucher').show();

    //assign gst
    $('#gst_rate').val(payment_voucher_below_info[0]["gst_rate"]);

    for(var t = 0; t < payment_voucher_below_info.length; t++)
    {
        $count_payment_voucher_service_info = t;
        $a=""; 
        /*<select class="input-sm" style="text-align:right;width: 150px;" id="position" name="position[]" onchange="optionCheck(this);"><option value="Director" >Director</option><option value="CEO" >CEO</option><option value="Manager" >Manager</option><option value="Secretary" >Secretary</option><option value="Auditor" >Auditor</option><option value="Managing Director" >Managing Director</option><option value="Alternate Director">Alternate Director</option></select>*/
        $a += '<div class="tr editing tr_payment_voucher" method="post" name="form'+$count_payment_voucher_service_info+'" id="form'+$count_payment_voucher_service_info+'" num="'+$count_payment_voucher_service_info+'">';
        $a += '<div class="hidden"><input type="text" class="form-control" name="supplier_code" value=""/></div>';
        $a += '<div class="hidden"><input type="text" class="form-control" name="payment_voucher_service_id" value="'+payment_voucher_below_info[t]["payment_voucher_service_id"]+'"/></div>';
        $a += '<div class="hidden"><input type="text" class="form-control" name="vendor_payment_voucher_info_id['+$count_payment_voucher_service_info+']" id="vendor_payment_voucher_info_id" value="'+payment_voucher_below_info[t]["vendor_payment_voucher_info_id"]+'"/></div>';
        $a += '<div class="td" style="width: 150px;"><div class="select-input-group"><select class="input-sm form-control" name="type['+$count_payment_voucher_service_info+']" id="type'+$count_payment_voucher_service_info+'" style="width:200px;"><option value="0" data-invoice_description="" data-amount="">Select Type</option></select><div id="form_service"></div></div></div>';
        $a += '<div class="td"><div class="input-group mb-md"><textarea class="form-control" name="payment_voucher_description['+$count_payment_voucher_service_info+']"  id="payment_voucher_description" rows="3" style="width:420px">'+payment_voucher_below_info[t]["payment_voucher_description"]+'</textarea></div></div>';
        //<div style="width: 200px;display: inline-block; margin-right:10px;"><div style="font-weight: bold;">Period Start Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker period_start_date" id="period_start_date" name="period_start_date['+$count_payment_voucher_service_info+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value="'+payment_voucher_below_info[t]["period_start_date"]+'"></div></div><div style="width: 200px;display: inline-block"><div style="font-weight: bold;">Period End Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker period_end_date" id="period_end_date" name="period_end_date['+$count_payment_voucher_service_info+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value="'+payment_voucher_below_info[t]["period_end_date"]+'"></div></div>
        $a += '<div class="td" style="width:150px"><div class="input-group"><input type="text" name="amount['+$count_payment_voucher_service_info+']" class="numberdes form-control text-right amount" value="'+addCommas(payment_voucher_below_info[t]["payment_voucher_service_amount"])+'" id="amount" style="width:150px"/><div id="form_amount"></div></div></div>';
        $a += "<div class='td' style='width:100px'><div class='input-group'><input type='file' style='display:none' id='attachment"+$count_payment_voucher_service_info+"' multiple='' name='attachment["+$count_payment_voucher_service_info+"][]'><label for='attachment"+$count_payment_voucher_service_info+"' class='btn btn-primary attachment"+$count_payment_voucher_service_info+"'>Select Attachment</label><br/><span class='file_name' id='file_name"+$count_payment_voucher_service_info+"'></span><input type='hidden' class='hidden_attachment' name='hidden_attachment["+$count_payment_voucher_service_info+"]' value='"+payment_voucher_below_info[t]["attachment"]+"'/></div></div>";
        // $a += '<div class="td" style="width:150px"><div class="select-input-group"><select class="form-control" style="text-align:right;width: 165px;" name="unit_pricing['+$count_payment_voucher_service_info+']" id="unit_pricing"><option value="0" >Select Unit Pricing</option></select></div></div>';
        $a += '<div class="td action"><button type="button" class="btn btn-primary delete_payment_voucher_button" onclick="delete_payment_voucher(this)" style="display: none;">Delete</button></div>';
        $a += '</div>';

        /*<input type="button" value="Save" id="save" name="save'+$count_officer+'" class="btn btn-primary" onclick="save(this);">*/
        $("#body_create_payment_voucher").append($a); 

        if($("#body_create_payment_voucher > div").length > 1)
        {
            $('.delete_payment_voucher_button').css('display','block');
        }
        //console.log(type_array);
        var file_result = JSON.parse(payment_voucher_below_info[t]["attachment"]);
        var filename = "";
        //console.log(this.files);
        for(var i = 0; i < file_result.length; i++)
        {
        if(i == 0)
        {
            filename = '<a href="'+base_url+'uploads/pv_receipt/'+file_result[i]+'" target="_blank">'+file_result[i]+'</a>';
        }
        else
        {
            filename = filename + ", " + '<a href="'+base_url+'uploads/pv_receipt/'+file_result[i]+'" target="_blank">'+file_result[i]+'</a>';
        }
        }
        $("#file_name"+t).html(filename);

        $.each(type_array, function(key, val) {
            var option = $('<option />');
            option.attr('value', val["id"]).text(val["type_name"]);

            if(payment_voucher_below_info[t]["type_id"] != null && val["id"] == payment_voucher_below_info[t]["type_id"])
            {
                option.attr('selected', 'selected');
            }
            
            $("#form"+$count_payment_voucher_service_info+" #type"+$count_payment_voucher_service_info).append(option);
        });

        $("#form"+$count_payment_voucher_service_info+" #type"+$count_payment_voucher_service_info).select2();

        if(payment_voucher_top_info[0]['status'] == 2 || payment_voucher_top_info[0]['status'] == 3 || payment_voucher_top_info[0]['status'] == 4)
        {
            $("#create_payment_voucher_form :input").prop('readonly', true);
            $('#create_payment_voucher_form select').attr('disabled', true);
            $('.attachment').hide();
            $('.attachment'+$count_payment_voucher_service_info+'').hide();
            $('.delete_payment_voucher_button').hide();
        }

        $('#create_payment_voucher_form').formValidation('addField', 'type['+$count_payment_voucher_service_info+']', type);
        $('#create_payment_voucher_form').formValidation('addField', 'payment_voucher_description['+$count_payment_voucher_service_info+']', payment_voucher_description);
        $('#create_payment_voucher_form').formValidation('addField', 'amount['+$count_payment_voucher_service_info+']', amount);
        //$('#create_payment_voucher_form').formValidation('addField', 'unit_pricing['+$count_payment_voucher_service_info+']', validate_unit_pricing);
    }
    sum_first_total();
}
else
{
    showRow();
}

if(payment_voucher_below_info)
{
    $count_payment_voucher_service_info = payment_voucher_below_info.length;
}
else
{
    $count_payment_voucher_service_info = 1;
}

$(document).on('click',"#payment_voucher_service_info_Add",function() {
    
    $a=""; 
    /*<select class="input-sm" style="text-align:right;width: 150px;" id="position" name="position[]" onchange="optionCheck(this);"><option value="Director" >Director</option><option value="CEO" >CEO</option><option value="Manager" >Manager</option><option value="Secretary" >Secretary</option><option value="Auditor" >Auditor</option><option value="Managing Director" >Managing Director</option><option value="Alternate Director">Alternate Director</option></select>*/
    $a += '<div class="tr editing tr_payment_voucher" method="post" name="form'+$count_payment_voucher_service_info+'" id="form'+$count_payment_voucher_service_info+'" num="'+$count_payment_voucher_service_info+'">';
    $a += '<div class="hidden"><input type="text" class="form-control" name="supplier_code" value=""/></div>';
    $a += '<div class="hidden"><input type="text" class="form-control" name="payment_voucher_service_id" value=""/></div>';
    $a += '<div class="hidden"><input type="text" class="form-control" name="vendor_payment_voucher_info_id['+$count_payment_voucher_service_info+']" id="vendor_payment_voucher_info_id" value=""/></div>';
    $a += '<div class="td"><div class="select-input-group"><select class="input-sm form-control" name="type['+$count_payment_voucher_service_info+']" id="type'+$count_payment_voucher_service_info+'" style="width:200px;"><option value="0" data-invoice_description="" data-amount="">Select Type</option></select><div id="form_service"></div></div></div>';
    $a += '<div class="td"><div class="input-group mb-md"><textarea class="form-control" name="payment_voucher_description['+$count_payment_voucher_service_info+']"  id="payment_voucher_description" rows="3" style="width:420px"></textarea></div></div>';
    //<div style="width: 200px;display: inline-block; margin-right:10px;"><div style="font-weight: bold;">Period Start Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker period_start_date" id="period_start_date" name="period_start_date['+$count_payment_voucher_service_info+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div></div><div style="width: 200px;display: inline-block"><div style="font-weight: bold;">Period End Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker period_end_date" id="period_end_date" name="period_end_date['+$count_payment_voucher_service_info+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div></div>
    $a += '<div class="td" style="width:150px"><div class="input-group"><input type="text" name="amount['+$count_payment_voucher_service_info+']" class="numberdes form-control text-right amount" value="" id="amount" style="width:150px"/><div id="form_amount"></div></div></div>';
    $a += '<div class="td" style="width:100px"><div class="input-group"><input type="file" style="display:none" id="attachment'+$count_payment_voucher_service_info+'" multiple="" name="attachment['+$count_payment_voucher_service_info+'][]"><label for="attachment'+$count_payment_voucher_service_info+'" class="btn btn-primary" class="attachment">Select Attachment</label><br/><span class="file_name"></span><input type="hidden" class="hidden_attachment" name="hidden_attachment['+$count_payment_voucher_service_info+']" value=""/></div></div>';
    //$a += '<div class="td" style="width:150px"><div class="select-input-group"><select class="form-control" style="text-align:right;width: 165px;" name="unit_pricing['+$count_payment_voucher_service_info+']" id="unit_pricing"><option value="0" >Select Unit Pricing</option></select><div id="form_unit_pricing"></div></div></div>';
    /*$a += '<div class="td action"><button type="button" class="btn btn-primary" onclick="edit_payment_voucher_info(this);">Save</button></div></div>';*/
    $a += '<div class="td action"><button type="button" class="btn btn-primary delete_payment_voucher_button" onclick="delete_payment_voucher(this)" style="display: block;">Delete</button></div>';
    $a += '</div>';

    /*<input type="button" value="Save" id="save" name="save'+$count_officer+'" class="btn btn-primary" onclick="save(this);">*/
    $("#body_create_payment_voucher").append($a); 

    if($("#body_create_payment_voucher > div").length > 1)
    {
        $('.delete_payment_voucher_button').css('display','block');
    }

    $.each(type_array, function(key, val) {
        if(val["deleted"] == 0)
        {
            var option = $('<option />');
            option.attr('value', val["id"]).text(val["type_name"]);
            
            $("#form"+$count_payment_voucher_service_info+" #type"+$count_payment_voucher_service_info).append(option);
        }
    });

    $("#form"+$count_payment_voucher_service_info+" #type"+$count_payment_voucher_service_info).select2();
    $('#create_payment_voucher_form').formValidation('addField', 'type['+$count_payment_voucher_service_info+']', type);
    $('#create_payment_voucher_form').formValidation('addField', 'payment_voucher_description['+$count_payment_voucher_service_info+']', payment_voucher_description);
    $('#create_payment_voucher_form').formValidation('addField', 'amount['+$count_payment_voucher_service_info+']', amount);
    //$('#create_payment_voucher_form').formValidation('addField', 'unit_pricing['+$count_payment_voucher_service_info+']', validate_unit_pricing);

    $count_payment_voucher_service_info++;
});

$(document).on('change','#create_payment_voucher_form #vendor_name',function(e){
    $('#loadingPaymentVoucher').show();
    var supplier_code = $('#vendor_name option:selected').val();
    $.ajax({
        type: "POST",
        url: "payment_voucher/get_vendor_address",
        data: {"supplier_code":supplier_code}, // <--- THIS IS THE CHANGE
        dataType: "json",
        success: function(response){
            //$(".tr_payment_voucher").remove();
            $('#loadingPaymentVoucher').hide();
            if(response.Status == 1)
            {
                if(supplier_code == 0)
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
                    $('#create_payment_voucher_form').formValidation('revalidateField', 'address');
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
                    $('#create_payment_voucher_form').formValidation('revalidateField', 'address');
                    $("#address").attr('readOnly', true);
                }
            }
        }
    });
});

$(document).on('change','#create_payment_voucher_form #client_name',function(e){
    $('#loadingPaymentVoucher').show();
    var company_code = $('#client_name option:selected').val();
    $.ajax({
        type: "POST",
        url: "payment_voucher/get_client_address",
        data: {"company_code":company_code}, // <--- THIS IS THE CHANGE
        dataType: "json",
        success: function(response){
            $('#loadingPaymentVoucher').hide();
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
                    $('#create_payment_voucher_form').formValidation('revalidateField', 'address');
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
                    $('#create_payment_voucher_form').formValidation('revalidateField', 'address');
                    $("#address").attr('readOnly', true);

                    if(response.unassign_amount != false)
                    {
                        $(".unassign_ccy").val(response.unassign_amount[0]["currency_name"]);
                        $(".unassign_amt").val(addCommas(parseFloat(response.unassign_amount[0]["total_cn_out_of_balance"]).toFixed(2)));
                    }
                    else
                    {
                        $(".unassign_ccy").val("");
                        $(".unassign_amt").val(0);
                    }
                }
            }
        }
    });
});

function showRow(){
    var supplier_code = $('#vendor_name option:selected').val();

    $.ajax({
        type: "GET",
        url: "companytype/get_payment_voucher_type",
        //data: {"supplier_code":supplier_code, "currency": $("#currency option:selected").val()}, // <--- THIS IS THE CHANGE
        dataType: "json",
        success: function(response){
            if(response.tp == 1)
            {

                $a0=""; 

                $a0 += '<div class="tr editing tr_payment_voucher" method="post" name="form'+0+'" id="form'+0+'" num="'+0+'">';
                $a0 += '<div class="hidden"><input type="text" class="form-control" name="supplier_code" value=""/></div>';
                $a0 += '<div class="hidden"><input type="text" class="form-control" name="payment_voucher_service_id" value=""/></div>';
                $a0 += '<div class="hidden"><input type="text" class="form-control" name="vendor_payment_voucher_info_id['+0+']" id="vendor_payment_voucher_info_id" value=""/></div>';
                $a0 += '<div class="td"><div class="select-input-group"><select class="input-sm form-control" name="type['+0+']" id="type'+0+'" style="width:200px;"><option value="0" data-invoice_description="" data-amount="">Select Type</option></select><div id="form_service"></div></div></div>';
                $a0 += '<div class="td"><div class="input-group mb-md"><textarea class="form-control" name="payment_voucher_description['+0+']"  id="payment_voucher_description" rows="3" style="width:420px"></textarea></div></div>';
                //<div style="width: 200px;display: inline-block; margin-right:10px;"><div style="font-weight: bold;">Period Start Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker period_start_date" id="period_start_date" name="period_start_date['+0+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div></div><div style="width: 200px;display: inline-block"><div style="font-weight: bold;">Period End Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker period_end_date" id="period_end_date" name="period_end_date['+0+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div></div>
                $a0 += '<div class="td" style="width:150px"><div class="input-group"><input type="text" name="amount['+0+']" class="numberdes form-control text-right amount" value="" id="amount" style="width:150px"/><div id="form_amount"></div></div></div>';
                $a0 += '<div class="td" style="width:100px"><div class="input-group"><input type="file" style="display:none" id="attachment'+0+'" multiple="" name="attachment['+0+'][]"><label for="attachment'+0+'" class="btn btn-primary" class="attachment">Select Attachment</label><br/><span class="file_name"></span><input type="hidden" class="hidden_attachment" name="hidden_attachment['+0+']" value=""/></div></div>';
                /*$a += '<div class="td action"><button type="button" class="btn btn-primary" onclick="edit_payment_voucher_info(this);">Save</button></div></div>';*/
                // $a0 += '<div class="td" style="width:150px"><div class="select-input-group"><select class="form-control" style="text-align:right;width: 165px;" name="unit_pricing['+0+']" id="unit_pricing"><option value="0" >Select Unit Pricing</option></select></div></div>';
                $a0 += '<div class="td action"><button type="button" class="btn btn-primary delete_payment_voucher_button" onclick="delete_payment_voucher(this)" style="display: none;">Delete</button></div>';
                $a0 += '</div>';

                
                $("#body_create_payment_voucher").append($a0); 

                if($("#body_create_payment_voucher > div").length > 1)
                {
                    $('.delete_payment_voucher_button').css('display','block');
                }

                type_array = response.result;
                $.each(response.result, function(key, val) {
                    if(val["deleted"] == 0)
                    {
                        var option = $('<option />');
                        option.attr('value', val["id"]).text(val["type_name"]);
                        
                        $("#form"+0+" #type"+0).append(option);
                    }
                });

                $("#form"+0+" #type"+0).select2();

                $('#create_payment_voucher_form').formValidation('addField', 'type['+0+']', type);
                $('#create_payment_voucher_form').formValidation('addField', 'payment_voucher_description['+0+']', payment_voucher_description);
                $('#create_payment_voucher_form').formValidation('addField', 'amount['+0+']', amount);  
                $('#create_payment_voucher_form').formValidation('addField', 'unit_pricing['+0+']', validate_unit_pricing);  
            }
        }               
    });
}

$(document).on('change','#create_payment_voucher_form #currency',function(e){
    if($(this).val() == "1")
    {
        $("#rate").val("1.0000");
    }
    $('#create_payment_voucher_form').formValidation('revalidateField', 'currency');
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
        $('[name="client_type"]').attr('disabled', false);
        $('[name="payment_voucher_date"]').attr('disabled', false);
        $('[name="address"]').attr('disabled', false);
        $('.currency').attr('disabled', false);
        $('.vendor_name').attr('disabled', false);
        $('.client_name').attr('disabled', false);
        $('#payment_voucher_no').attr('disabled', false);
        $('.bank_account').attr('disabled', false);

        var formData = new FormData($('form')[0]);
        formData.append('vendor_name_text', $(".vendor_name option:selected").text());
        formData.append('client_name_text', $(".client_name option:selected").text());
        $('#loadingPaymentVoucher').show();
        $.ajax({
            type: 'POST', //$form.serialize()
            url: "payment_voucher/save_payment_voucher",
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
                $('#loadingPaymentVoucher').hide();
                if (response.Status === 1) 
                {
                    toastr.success(response.message, response.title);
                    var getUrl = window.location;
                    var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1] + "/payment_voucher";
                    window.location.href = baseUrl;
                }
                else
                {
                    toastr.error(response.message, response.title);
                }
            }
        });
    }
});

$(document).on('click',"#savePaymentVoucher",function(e){
    $("#create_payment_voucher_form").submit();
});

// if(access_right_billing_module == "read" || access_right_unpaid_module == "read")
// {
//     $('input').attr("disabled", true);
//     $('button').attr("disabled", true);
//     $('select').attr("disabled", true);
//     $('textarea').attr("disabled", true);
//     $("#billing_service_info_Add").hide();
// }