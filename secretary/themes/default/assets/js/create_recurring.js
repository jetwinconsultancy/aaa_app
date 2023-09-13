var pathArray = location.href.split( '/' );
var protocol = pathArray[0];
var host = pathArray[2];
var folder = pathArray[3];

var latest_gst_rate = 0;
var state_own_letterhead_checkbox = false;

$('#create_recurring_form').formValidation({
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
        },
        frequency: {
            row: '.period-input-group',
            validators: {
                callback: {
                    message: 'The Billing Period field is required.',
                    callback: function(value, validator, $field) {
                        var options = validator.getFieldElements('frequency').val();
                        //console.log(options);
                        return (options != null && options != "0");
                    }
                }
            }
        },
        recurring_issue_date: {
            row: '.period-input-group',
            validators: {
                notEmpty: {
                    message: 'The Recurring Invoice Issues Date field is required.'
                }
            }
        },
        recurring_cancel_date: {
            row: '.period-input-group',
            validators: {
                notEmpty: {
                    message: 'The Recurring Cancel Date field is required.'
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
                    console.log(options);
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
                    else if(key == firm_info[0]["firm_currency"] && data.selected_currency == null) //key==1
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

    this.frequency = function() {
        $.ajax({
            type: "GET",
            url: "masterclient/get_billing_info_frequency",
            asycn:false,
            dataType: "json",
            success: function(data){
                $('#loadingmessage').hide();
                $("#frequency").find("option:eq(0)").html("Select Frequency");
                if(data.tp == 1){
                    $.each(data['result'], function(key, val) {
                        var option = $('<option />');
                        option.attr('value', key).text(val);
                        if(data.selected_frequency != null && key == data.selected_frequency)
                        {
                            option.attr('selected', 'selected');
                        }
                        $("#frequency").append(option);
                    });
                }
                else{
                    alert(data.msg);
                } 
            }               
        }); 
    };

    this.type_of_day = function() {
        $.ajax({
            type: "GET",
            url: "masterclient/get_type_of_day",
            dataType: "json",
            success: function(data){
                console.log(data);
                if(data.tp == 1){
                    $.each(data['result'], function(key, val) {
                        var option = $('<option />');
                        option.attr('value', key).text(val);
                        if(data.selected_type_of_day != null && key == data.selected_type_of_day)
                        {
                            option.attr('selected', 'selected');
                        }
                        $("#type_of_day").append(option);
                    });
                }
                else{
                    alert(data.msg);
                }  
            }               
        });
    }
}

$(function() {
    var cn = new Client();
    cn.getClientName();
    cn.getCurrency();
    cn.frequency();
    cn.type_of_day();
});

function optionCheckService(service_element) 
{
    var tr = jQuery(service_element).parent().parent();
    console.log(jQuery(service_element).val());
    if(jQuery(service_element).val() == "1" || jQuery(service_element).val() == "0")
    {
        $(".recurring_part").hide();
        $("#recurring_issue_date").prop("disabled", true);
    }
    else
    {
        $(".recurring_part").show();
        $("#recurring_issue_date").prop("disabled", false);
    }
}

var state_recuring_checkbox = true;
$("[name='hidden_recurring_checkbox']").val(1);

if(recurring_below_info)
{
    if(recurring_below_info[0]['recurring_status'] == 0)
    {
        state_recuring_checkbox = false;
        $("[name='hidden_recurring_checkbox']").val(recurring_below_info[0]['recurring_status']);
        $("#frequency").prop("disabled", true);
        $(".recurring_part").hide();
        $("#recurring_issue_date").prop("disabled", true);
        $(".recurring_cancel_date_part").show();
        $("#recurring_cancel_date").prop("disabled", false);
    }
    else
    {
        state_recuring_checkbox = true;
        $("[name='hidden_recurring_checkbox']").val(recurring_below_info[0]['recurring_status']);
        $("#frequency").prop("disabled", false);
        $(".recurring_part").show();
        $("#recurring_issue_date").prop("disabled", false);
        $(".recurring_cancel_date_part").hide();
        $("#recurring_cancel_date").prop("disabled", true);
    }
}

$("[name='recuring_checkbox']").bootstrapSwitch({
    state: state_recuring_checkbox,
    size: 'normal',
    onColor: 'primary',
    onText: 'Active',
    offText: 'Non-Active',
    // Text of the center handle of the switch
    labelText: '&nbsp',
    // Width of the left and right sides in pixels
    handleWidth: '100px',
    // Width of the center handle in pixels
    labelWidth: 'auto',
    baseClass: 'bootstrap-switch',
    wrapperClass: 'wrapper'


});

// Triggered on switch state change.
$("[name='recuring_checkbox']").on('switchChange.bootstrapSwitch', function(event, state) {
    //console.log(this); // DOM element
    //console.log(event); // jQuery event
    //console.log(state); // true | false
    if(state == true)
    {
        //$("#gst_value").val("");
        $("[name='hidden_recurring_checkbox']").val(1);
        $("#frequency").val(0);
        $("#frequency").prop("disabled", false);
        $(".recurring_part").show();
        $("#recurring_issue_date").val("");
        $("#recurring_issue_date").prop("disabled", false);
        $('#create_recurring_form').formValidation('revalidateField', 'frequency');
        $(".recurring_cancel_date_part").hide();
        $("#recurring_cancel_date").val("");
        $("#recurring_cancel_date").prop("disabled", true);
        //$("[name='gst_value']").attr("value", "");
    }
    else
    {
        //$('#gst_value').attr("value", "");
        //$("#gst_date").val("");
        $("[name='hidden_recurring_checkbox']").val(0);
        $("#frequency").val(1);
        $('#create_recurring_form').formValidation('revalidateField', 'frequency');
        $("#frequency").prop("disabled", true);
        $(".recurring_part").hide();
        $("#recurring_issue_date").val("");
        $("#recurring_issue_date").prop("disabled", true);
        $(".recurring_cancel_date_part").show();
        $("#recurring_cancel_date").val("");
        $("#recurring_cancel_date").prop("disabled", false);
    }   
});

$('#recurring_issue_date').datepicker({ 
    dateFormat:'dd/mm/yyyy',
}).datepicker('setStartDate', "01/01/1920")
.on('changeDate', function (selected) {
    $('#create_recurring_form').formValidation('revalidateField', 'recurring_issue_date');
});

$('#recurring_cancel_date').datepicker({ 
    dateFormat:'dd/mm/yyyy',
}).datepicker('setStartDate', "01/01/1920")
.on('changeDate', function (selected) {
    $('#create_recurring_form').formValidation('revalidateField', 'recurring_cancel_date');
});

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

$('.billing_date').datepicker({ 
    dateFormat:'dd/mm/yyyy',
    autoclose: true,
}).datepicker("setDate", "0")
.on('changeDate', function (selected) {
    //console.log($('.recurring_date').val());
    var billing_date = $('.billing_date').val();
    get_gst_rate(billing_date);
    
    $('#create_recurring_form').formValidation('revalidateField', 'billing_date');
});

//console.log(recurring_top_info);
if(recurring_top_info == undefined)
{

    $.ajax({
        type: "GET",
        url: "billings/get_recurring_invoice_no",
        asycn: false,
        //data: {"company_code":company_code}, // <--- THIS IS THE CHANGE
        dataType: "json",
        success: function(response){
            //$(".tr_recurring").remove();
            //console.log(response);
            $('[name="invoice_no"]').val(response.invoice_no);
        }
    });

    $('#rate').val("1.0000");

    var d = new Date();

    var today_date = formatDateFunc(d);
    get_gst_rate(today_date);

    $("[name='hidden_own_letterhead_checkbox']").val(0);
}
else
{
    if(recurring_top_info[0]["use_foreign_add_as_billing_add"] == 1)
    {
        if(recurring_top_info[0]["foreign_add_1"] != "")
        {
            var comma1 = recurring_top_info[0]["foreign_add_1"] + '\n';
        }
        else
        {
            var comma1 = '';
        }

        if(recurring_top_info[0]["foreign_add_2"] != "")
        {
            var comma2 = comma1 + recurring_top_info[0]["foreign_add_2"] + '\n';
        }
        else
        {
            var comma2 = comma1 + '';
        }
        var nonedit_address = comma2 + recurring_top_info[0]["foreign_add_3"];
    }
    else
    {
        if(recurring_top_info[0]["postal_code"] != "" || recurring_top_info[0]["street_name"] != "")
        {
            var units = " ";

            if(recurring_top_info[0]["unit_no1"] != "" || recurring_top_info[0]["unit_no2"] != "")
            {
                units = '\n#'+recurring_top_info[0]["unit_no1"] + " - " + recurring_top_info[0]["unit_no2"];
            }
            else if(recurring_top_info[0]["unit_no1"] != "")
            {
                units = recurring_top_info[0]["unit_no1"];
            }
            else if(recurring_top_info[0]["unit_no2"] != "")
            {
                units = recurring_top_info[0]["unit_no2"];
            }
            var nonedit_address = recurring_top_info[0]["street_name"]+units+' '+recurring_top_info[0]["building_name"]+'\nSingapore '+recurring_top_info[0]["postal_code"];
        }
        else
        {
            var nonedit_address = recurring_top_info[0]["foreign_address"];
        }
    }

    $('[name="invoice_no"]').val(recurring_top_info[0]["invoice_no"]);
    $('[name="billing_date"]').val(recurring_top_info[0]["invoice_date"]);
    $('[name="address"]').val(nonedit_address);
    $('[name="rate"]').val(recurring_top_info[0]["rate"]);
    $('[name="hidden_recurring_checkbox"]').val(recurring_top_info[0]["recurring_status"]);
    $('[name="recurring_cancel_date"]').val(recurring_top_info[0]["recurring_cancel_date"]);
    $('[name="recurring_issue_date"]').val(recurring_top_info[0]["recu_invoice_issue_date"]);
    $('[name="invoice_no"]').attr('disabled', true);
    $('[name="billing_date"]').attr('disabled', true);
    $('[name="address"]').attr('disabled', true);

    if(recurring_top_info[0]['own_letterhead_checkbox'] == 0)
    {
        state_own_letterhead_checkbox = false;
        $("[name='hidden_own_letterhead_checkbox']").val(recurring_top_info[0]['own_letterhead_checkbox']);
    }
    else
    {
        state_own_letterhead_checkbox = true;
        $("[name='hidden_own_letterhead_checkbox']").val(recurring_top_info[0]['own_letterhead_checkbox']);
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

$(".amount").live('change',function(){
    sum_total();
});
$(".rate").live('change',function(){
    sum_total();
});
$(".currency").live('change',function(){
    sum_total();
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
    $(".amount").each(function(){
        //console.log($(this).val() == '');
        if($(this).val() == '')
        {
            sum += 0;
        }
        else
        {
            sum += +parseFloat($(this).val().replace(/\,/g,''),2);

            if(recurring_below_info)
            {
                //assign gst
                //gst_rate = recurring_below_info[0]['gst_rate'];
                if($("#old_gst_rate").val() != "false")
                {
                    gst_rate = $("#old_gst_rate").val();
                }
                else
                {
                    gst_rate = $(this).parent().parent().parent().find('.gst_rate').val();
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
            //console.log("total==="+before_gst);
            gst += parseFloat(before_gst.toFixed(2));
        }
    });
    //$(".total").val(sum);
   // console.log(recurring_below_info[0]["currency_id"]);
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

            if(recurring_below_info)
            {
                //assign gst
                //gst_rate = recurring_below_info[0]['gst_rate'];
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
            //console.log("total==="+before_gst);
            gst += parseFloat(before_gst.toFixed(2));
        }
    });
    //$(".total").val(sum);
    //console.log(gst);
    $("#sub_total").text(addCommas(sum.toFixed(2)));

    if(recurring_below_info)
    {
        if(recurring_below_info[0]["currency_id"] == "1")
        {
            gst_with_rate = " ";
            $("#gst_with_rate").text(gst_with_rate);
        }
        else if(recurring_below_info[0]["currency_id"] != "1")
        {
            gst_with_rate = gst * parseFloat(recurring_below_info[0]["rate"]);
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



function delete_recurring(element) {

    var tr = jQuery(element).parent().parent(),
        recurring_service_id = tr.find('input[name="recurring_service_id[]"]').val();

    tr.closest("DIV.tr").remove();
    //console.log($("#allotment_add > div").length);
    if($("#body_create_recurring > div").length == 1)
    {
        if($('.delete_recurring_button').css('display') == 'block')
        {
            $('.delete_recurring_button').css('display','none');
        }
    }
    sum_total();
    
}

if(recurring_below_info != undefined)
{
    console.log(service_dropdown);
    $('#create_recurring_service').show();
    $('#body_create_recurring').show();
    $('#sub_total_create_recurring').show();
    $('#gst_create_recurring').show();
    $('#grand_total_create_recurring').show();

    //assign gst
    //$('#gst_rate').val(recurring_below_info[0]["gst_rate"]);
    if(recurring_below_info[0]["gst_new_way"] == 0)
    {
        $('#old_gst_rate').val(recurring_below_info[0]["gst_rate"]);
    }
    else
    {
        $('#old_gst_rate').val("false");
    }

    for(var t = 0; t < recurring_below_info.length; t++)
    {
        $count_recurring_service_info = t;
        $a=""; 
        /*<select class="input-sm" style="text-align:right;width: 150px;" id="position" name="position[]" onchange="optionCheck(this);"><option value="Director" >Director</option><option value="CEO" >CEO</option><option value="Manager" >Manager</option><option value="Secretary" >Secretary</option><option value="Auditor" >Auditor</option><option value="Managing Director" >Managing Director</option><option value="Alternate Director">Alternate Director</option></select>*/
        $a += '<div class="tr editing tr_recurring" method="post" name="form'+$count_recurring_service_info+'" id="form'+$count_recurring_service_info+'" num="'+$count_recurring_service_info+'">';
        $a += '<div class="hidden"><input type="text" class="form-control" name="company_code" value=""/></div>';
        $a += '<div class="hidden"><input type="text" class="form-control" name="recurring_service_id" value="'+recurring_below_info[t]["recurring_service_id"]+'"/></div>';
        $a += '<div class="hidden"><input type="text" class="form-control" name="client_recurring_info_id['+$count_recurring_service_info+']" id="client_recurring_info_id" value="'+recurring_below_info[t]["client_recurring_info_id"]+'"/></div>';
        $a += '<div class="td" style="width: 150px;"><div class="select-input-group"><select class="input-sm form-control service" name="service['+$count_recurring_service_info+']" id="service'+$count_recurring_service_info+'" style="width:200px !important;"><option value="0" data-invoice_description="" data-amount="">Select Service</option></select><div id="form_service"></div></div></div>';
        $a += '<div class="td"><div class="input-group mb-md"><textarea class="form-control" name="invoice_description['+$count_recurring_service_info+']"  id="invoice_description" rows="3" style="width:420px">'+recurring_below_info[t]["invoice_description"]+'</textarea></div><div style="width: 200px;display: inline-block; margin-right:10px;"><div style="font-weight: bold;">Period Start Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker period_start_date" id="period_start_date" name="period_start_date['+$count_recurring_service_info+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value="'+recurring_below_info[t]["period_start_date"]+'"></div></div><div style="width: 200px;display: inline-block"><div style="font-weight: bold;">Period End Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker period_end_date" id="period_end_date" name="period_end_date['+$count_recurring_service_info+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value="'+recurring_below_info[t]["period_end_date"]+'"></div></div><div class="help-block remark"></div></div>';
        $a += '<div class="td" style="width:150px"><input type="hidden" name="gst_category_id[]" class="form-control gst_category_id" id="gst_category_id" value="'+recurring_below_info[t]["gst_category_id"]+'"/><input type="hidden" name="gst_new_way[]" class="form-control gst_new_way" id="gst_new_way" value="'+recurring_below_info[t]["gst_new_way"]+'"/><input type="hidden" name="gst_rate[]" class="form-control gst_rate" id="gst_rate" value="'+recurring_below_info[t]["gst_rate"]+'"/><div class="input-group"><input type="text" name="amount['+$count_recurring_service_info+']" class="numberdes form-control text-right amount" value="'+addCommas(recurring_below_info[t]["recurring_service_amount"])+'" id="amount" style="width:150px"/><div id="form_amount"></div></div></div>';
        $a += '<div class="td" style="width:150px"><div class="select-input-group"><select class="form-control" style="text-align:right;width: 165px;" name="unit_pricing['+$count_recurring_service_info+']" id="unit_pricing"><option value="0" >Select Unit Pricing</option></select></div></div>';
        $a += '<div class="td action"><button type="button" class="btn btn-primary delete_recurring_button" onclick="delete_recurring(this)" style="display: none;">Delete</button></div>';
        $a += '</div>';

        /*<input type="button" value="Save" id="save" name="save'+$count_officer+'" class="btn btn-primary" onclick="save(this);">*/
        $("#body_create_recurring").append($a); 

        if($("#body_create_recurring > div").length > 1)
        {
            $('.delete_recurring_button').css('display','block');
        }

        $('.period_start_date').datepicker({ 
            dateFormat:'dd/mm/yyyy',
        }).datepicker('setStartDate', "01/01/1920");

        $('.period_end_date').datepicker({ 
            dateFormat:'dd/mm/yyyy',
        }).datepicker('setStartDate', "01/01/1920");

        $.each(get_unit_pricing, function(key, val) {
            console.log(recurring_below_info[t]["unit_pricing"]);

            var option = $('<option />');
            option.attr('value', val['id']).text(val['unit_pricing_name']);
            
            if(recurring_below_info[t]["unit_pricing"] != null && val['id'] == recurring_below_info[t]["unit_pricing"])
            {
                option.attr('selected', 'selected');
            }

            $("#form"+$count_recurring_service_info+" #unit_pricing").append(option);
        });

        var category_description = '';
        var optgroup = '';

        for(var j = 0; j < service_category.length; j++)
        {

            if(category_description != service_category[j]['category_description'])
            {
                if(optgroup != '')
                {
                    $("#form"+$count_recurring_service_info+" #service"+$count_recurring_service_info).append(optgroup);
                }
                optgroup = $('<optgroup label="' + service_category[j]['category_description'] + '" />');
                //console.log(service_category_array[t]['category_description']);
            }

            category_description = service_category[j]['category_description'];
            
            for(var h = 0; h < service_dropdown.length; h++)
            {
                if(category_description == service_dropdown[h]['category_description'])
                {
                    var option = $('<option />');
                    option.attr('data-gst_category_id', service_dropdown[h]['gst_category_id']).attr('data-gst_new_way', service_dropdown[h]['gst_new_way']).attr('data-rate', service_dropdown[h]['rate']).attr('data-our_service_id', service_dropdown[h]['service']).attr('data-description', service_dropdown[h]['invoice_description']).attr('data-currency', service_dropdown[h]['currency']).attr('data-unit_pricing', service_dropdown[h]['unit_pricing']).attr('data-amount', service_dropdown[h]['amount']).attr('value', service_dropdown[h]['id']).text(service_dropdown[h]['service_name']).appendTo(optgroup);
                    
                    if(recurring_below_info[t]["service"] != null && service_dropdown[h]['id'] == recurring_below_info[t]["service"])
                    {
                        option.attr('selected', 'selected');
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

        $('#create_recurring_form').formValidation('addField', 'service['+$count_recurring_service_info+']', service);
        $('#create_recurring_form').formValidation('addField', 'invoice_description['+$count_recurring_service_info+']', invoice_description);
        $('#create_recurring_form').formValidation('addField', 'amount['+$count_recurring_service_info+']', amount);
        $('#create_recurring_form').formValidation('addField', 'unit_pricing['+$count_recurring_service_info+']', validate_unit_pricing);
    }
    sum_first_total();
}

$(document).on('change','.service',function(e){
    var num = $(this).parent().parent().parent().attr("num");

    var descriptionValue = $(this).find(':selected').data('description');
    var amountValue = $(this).find(':selected').data('amount');
    var unit_pricingValue = $(this).find(':selected').data('unit_pricing');
    var rate = $(this).find(':selected').data('rate');
    var gst_new_way = $(this).find(':selected').data('gst_new_way');
    var gst_category_id = $(this).find(':selected').data('gst_category_id');
    console.log(rate);
    $(this).parent().parent().parent().find('#invoice_description').text(descriptionValue);
    $(this).parent().parent().parent().find('#amount').val(addCommas(amountValue));
    $(this).parent().parent().parent().find('#unit_pricing').val(addCommas(unit_pricingValue));
    $(this).parent().parent().parent().find('#gst_rate').val(rate);
    $(this).parent().parent().parent().find('#gst_new_way').val(gst_new_way);
    $(this).parent().parent().parent().find('#gst_category_id').val(gst_category_id);
    
    $('#create_recurring_form').formValidation('revalidateField', 'service['+num+']');
    $('#create_recurring_form').formValidation('revalidateField', 'invoice_description['+num+']');
    $('#create_recurring_form').formValidation('revalidateField', 'amount['+num+']');
    $('#create_recurring_form').formValidation('revalidateField', 'unit_pricing['+num+']');

    sum_total();
});

if(recurring_below_info)
{
    $count_recurring_service_info = recurring_below_info.length;
}
else
{
    $count_recurring_service_info = 1;
}

//$count_recurring_service_info = 1;
$(document).on('click',"#recurring_service_info_Add",function() {
    
    $a=""; 
    /*<select class="input-sm" style="text-align:right;width: 150px;" id="position" name="position[]" onchange="optionCheck(this);"><option value="Director" >Director</option><option value="CEO" >CEO</option><option value="Manager" >Manager</option><option value="Secretary" >Secretary</option><option value="Auditor" >Auditor</option><option value="Managing Director" >Managing Director</option><option value="Alternate Director">Alternate Director</option></select>*/
    $a += '<div class="tr editing tr_recurring" method="post" name="form'+$count_recurring_service_info+'" id="form'+$count_recurring_service_info+'" num="'+$count_recurring_service_info+'">';
    $a += '<div class="hidden"><input type="text" class="form-control" name="company_code" value=""/></div>';
    $a += '<div class="hidden"><input type="text" class="form-control" name="recurring_service_id" value=""/></div>';
    $a += '<div class="hidden"><input type="text" class="form-control" name="client_recurring_info_id['+$count_recurring_service_info+']" id="client_recurring_info_id" value=""/></div>';
    $a += '<div class="td"><div class="select-input-group"><select class="input-sm form-control service" name="service['+$count_recurring_service_info+']" id="service'+$count_recurring_service_info+'" style="width:200px;"><option value="0" data-invoice_description="" data-amount="">Select Service</option></select><div id="form_service"></div></div></div>';
    $a += '<div class="td"><div class="input-group mb-md"><textarea class="form-control" name="invoice_description['+$count_recurring_service_info+']"  id="invoice_description" rows="3" style="width:420px"></textarea></div><div style="width: 200px;display: inline-block; margin-right:10px;"><div style="font-weight: bold;">Period Start Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker period_start_date" id="period_start_date" name="period_start_date['+$count_recurring_service_info+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div></div><div style="width: 200px;display: inline-block"><div style="font-weight: bold;">Period End Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker period_end_date" id="period_end_date" name="period_end_date['+$count_recurring_service_info+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div></div><div class="help-block remark"></div></div>';
    $a += '<div class="td" style="width:150px"><input type="hidden" name="gst_category_id[]" class="form-control gst_category_id" id="gst_category_id" value=""/><input type="hidden" name="gst_new_way[]" class="form-control gst_new_way" id="gst_new_way" value=""/><input type="hidden" name="gst_rate[]" class="form-control gst_rate" id="gst_rate" value=""/><div class="input-group"><input type="text" name="amount['+$count_recurring_service_info+']" class="numberdes form-control text-right amount" value="" id="amount" style="width:150px"/><div id="form_amount"></div></div></div>';
    $a += '<div class="td" style="width:150px"><div class="select-input-group"><select class="form-control" style="text-align:right;width: 165px;" name="unit_pricing['+$count_recurring_service_info+']" id="unit_pricing"><option value="0" >Select Unit Pricing</option></select></div></div>';
    /*$a += '<div class="td action"><button type="button" class="btn btn-primary" onclick="edit_recurring_info(this);">Save</button></div></div>';*/
    $a += '<div class="td action"><button type="button" class="btn btn-primary delete_recurring_button" onclick="delete_recurring(this)" style="display: block;">Delete</button></div>';
    $a += '</div>';

    /*<input type="button" value="Save" id="save" name="save'+$count_officer+'" class="btn btn-primary" onclick="save(this);">*/
    $("#body_create_recurring").append($a); 

    if($("#body_create_recurring > div").length > 1)
    {
        $('.delete_recurring_button').css('display','block');
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
        
        $("#form"+$count_recurring_service_info+" #unit_pricing").append(option);
    });

    var category_description = '';
    var optgroup = '';

    for(var t = 0; t < service_category_array.length; t++)
    {
        if(category_description != service_category_array[t]['category_description'])
        {
            if(optgroup != '')
            {
                $("#form"+$count_recurring_service_info+" #service"+$count_recurring_service_info).append(optgroup);
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
                option.attr('data-gst_category_id', service_array[h]['gst_category_id']).attr('data-gst_new_way', service_array[h]['gst_new_way']).attr('data-rate', service_array[h]['rate']).attr('data-our_service_id', service_array[h]['service']).attr('data-description', service_array[h]['invoice_description']).attr('data-currency', service_array[h]['currency']).attr('data-unit_pricing', service_array[h]['unit_pricing']).attr('data-amount', service_array[h]['amount']).attr('value', service_array[h]['id']).text(service_array[h]['service_name']).appendTo(optgroup);
            }
        }
    }
    $("#form"+$count_recurring_service_info+" #service"+$count_recurring_service_info).append(optgroup);   

    $("#form"+$count_recurring_service_info+" #service"+$count_recurring_service_info).select2();

    for(var h = 0; h < service_array.length; h++)
    {
        if(service_array[h]['deactive'] == 1)
        {
            $("#form"+$count_recurring_service_info+" #service"+$count_recurring_service_info+" option[value='"+service_array[h]['id']+"']").attr("disabled", true);
        }
    }

    $('#create_recurring_form').formValidation('addField', 'service['+$count_recurring_service_info+']', service);
    $('#create_recurring_form').formValidation('addField', 'invoice_description['+$count_recurring_service_info+']', invoice_description);
    $('#create_recurring_form').formValidation('addField', 'amount['+$count_recurring_service_info+']', amount);
    $('#create_recurring_form').formValidation('addField', 'unit_pricing['+$count_recurring_service_info+']', validate_unit_pricing);

    $count_recurring_service_info++;
});

// $(document).on('change','#create_recurring_form #service',function(e){
//     var num = $(this).parent().parent().parent().attr("num");

//     var selected_invoice_description = $(this).find(':selected').data('invoice_description');
//     var selected_amount = $(this).find(':selected').data('amount');
//     var selected_client_recurring_info_id = $(this).find(':selected').data('client_recurring_info_id');

//     $('#create_recurring_form').formValidation('revalidateField', 'service['+num+']');
//     $('#create_recurring_form').formValidation('revalidateField', 'invoice_description['+num+']');
//     $('#create_recurring_form').formValidation('revalidateField', 'amount['+num+']');
//     $('#create_recurring_form').formValidation('revalidateField', 'unit_pricing['+num+']');
// });

$(document).on('change','#create_recurring_form #client_name',function(e){
    showRow();
});

$(document).on('change','#create_recurring_form #currency',function(e){
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
                $(".tr_recurring").remove();
                //console.log(response);
                if(response.Status == 1)
                {
                    if(company_code == 0)
                    {
                        $('[name="address"]').val("");
                        $('#create_recurring_form').formValidation('revalidateField', 'address');
                        $("#address").attr('readOnly', false);
                    }
                    else
                    {
                        $('[name="address"]').val(response.address);
                        $('#create_recurring_form').formValidation('revalidateField', 'address');
                        $("#address").attr('readOnly', true);
                    }
                    

                    service_array = response.service;
                    service_category_array = response.selected_billing_info_service_category;
                    unit_pricing = response.unit_pricing;

                    if(response.service.length != 0)
                    {
                        $('#create_recurring_service').show();
                        $('#body_create_recurring').show();
                        $('#sub_total_create_recurring').show();
                        $('#gst_create_recurring').show();
                        $('#grand_total_create_recurring').show();
                    }
                    else
                    {
                        $('#create_recurring_service').hide();
                        $('#body_create_recurring').hide();
                        $('#sub_total_create_recurring').hide();
                        $('#gst_create_recurring').hide();
                        $('#grand_total_create_recurring').hide();
                    }

                    $a0=""; 

                    $a0 += '<div class="tr editing tr_recurring" method="post" name="form'+0+'" id="form'+0+'" num="'+0+'">';
                    $a0 += '<div class="hidden"><input type="text" class="form-control" name="company_code" value=""/></div>';
                    $a0 += '<div class="hidden"><input type="text" class="form-control" name="recurring_service_id" value=""/></div>';
                    $a0 += '<div class="hidden"><input type="text" class="form-control" name="client_recurring_info_id['+0+']" id="client_recurring_info_id" value=""/></div>';
                    $a0 += '<div class="td"><div class="select-input-group"><select class="input-sm form-control service" name="service['+0+']" id="service'+0+'" style="width:200px;"><option value="0" data-invoice_description="" data-amount="">Select Service</option></select><div id="form_service"></div></div></div>';
                    $a0 += '<div class="td"><div class="input-group mb-md"><textarea class="form-control" name="invoice_description['+0+']"  id="invoice_description" rows="3" style="width:420px"></textarea></div><div style="width: 200px;display: inline-block; margin-right:10px;"><div style="font-weight: bold;">Period Start Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker period_start_date" id="period_start_date" name="period_start_date['+0+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div></div><div style="width: 200px;display: inline-block"><div style="font-weight: bold;">Period End Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker period_end_date" id="period_end_date" name="period_end_date['+0+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div></div><div class="help-block remark"></div></div>';
                    $a0 += '<div class="td" style="width:150px"><input type="hidden" name="gst_category_id[]" class="form-control gst_category_id" id="gst_category_id" value=""/><input type="hidden" name="gst_new_way[]" class="form-control gst_new_way" id="gst_new_way" value=""/><input type="hidden" name="gst_rate[]" class="form-control gst_rate" id="gst_rate" value=""/><div class="input-group"><input type="text" name="amount['+0+']" class="numberdes form-control text-right amount" value="" id="amount" style="width:150px"/><div id="form_amount"></div></div></div>';
                    /*$a += '<div class="td action"><button type="button" class="btn btn-primary" onclick="edit_recurring_info(this);">Save</button></div></div>';*/
                    $a0 += '<div class="td" style="width:150px"><div class="select-input-group"><select class="form-control" style="text-align:right;width: 165px;" name="unit_pricing['+0+']" id="unit_pricing"><option value="0" >Select Unit Pricing</option></select></div></div>';
                    $a0 += '<div class="td action"><button type="button" class="btn btn-primary delete_recurring_button" onclick="delete_recurring(this)" style="display: none;">Delete</button></div>';
                    $a0 += '</div>';

                    $("#body_create_recurring").append($a0); 

                    if($("#body_create_recurring > div").length > 1)
                    {
                        $('.delete_recurring_button').css('display','block');
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
                    //     option.attr('data-client_recurring_info_id', service_array[i]['client_recurring_info_id']);
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
                                    $("#form"+0+" #service"+0).append(optgroup);
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
                                    option.attr('data-gst_category_id', service_array[h]['gst_category_id']).attr('data-gst_new_way', service_array[h]['gst_new_way']).attr('data-rate', service_array[h]['rate']).attr('data-our_service_id', service_array[h]['service']).attr('data-description', service_array[h]['invoice_description']).attr('data-currency', service_array[h]['currency']).attr('data-unit_pricing', service_array[h]['unit_pricing']).attr('data-amount', service_array[h]['amount']).attr('value', service_array[h]['id']).text(service_array[h]['service_name']).appendTo(optgroup);
                                }
                            }
                            //}
                        

                        
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

                    $('#create_recurring_form').formValidation('addField', 'service['+0+']', service);
                    $('#create_recurring_form').formValidation('addField', 'invoice_description['+0+']', invoice_description);
                    $('#create_recurring_form').formValidation('addField', 'amount['+0+']', amount);  
                    $('#create_recurring_form').formValidation('addField', 'unit_pricing['+0+']', validate_unit_pricing);
                }

            }               
        });
        $('#create_recurring_form').formValidation('revalidateField', 'client_name');
    }
}

$(document).on('change','#create_recurring_form #currency',function(e){
    //console.log($(this).val());
    if($(this).val() == "1")
    {
        $("#rate").val("1.0000");
    }
    $('#create_recurring_form').formValidation('revalidateField', 'currency');
});



function get_gst_rate(billing_date)
{
    //console.log(recurring_date);
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

    //$('#create_recurring_form').formValidation('revalidateField', 'recurring_date');
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
    if(valid_setup)
    {
        $('[name="invoice_no"]').attr('disabled', false);
        $('[name="billing_date"]').attr('disabled', false);
        $('[name="address"]').attr('disabled', false);
        $('.currency').attr('disabled', false);
        $('.client_name').attr('disabled', false);
        $('#frequency').attr('disabled', false);
        $.ajax({
            type: 'POST',
            url: "billings/save_recurring",
            data: $form.serialize(),
            dataType: 'json',
            success: function(response){
                if (response.Status === 1) 
                {
                    toastr.success(response.message, response.title);

                    var getUrl = window.location;
                    var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1] + "/billings";
                    window.location.href = baseUrl;
                }
            }
        });
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

$(document).on('click',"#saveRecurring",function(e){
    $('select.service').select2('destroy');
    for(var h = 0; h < service_array.length; h++)
    {
        if(service_array[h]['deactive'] == 1)
        {
            $(".service option[value='"+service_array[h]['id']+"']").removeAttr("disabled");
        }
    }
    $("select.service").select2();
    $("#create_recurring_form").submit();
});

$(document).on('change','#frequency',function(e){
    $('#body_create_recurring').find('.tr_recurring').each(function(){
        console.log($(this).find(".period_start_date"));
        var period_start_date = $(this).find(".period_start_date").val();
        var period_end_date = $(this).find(".period_end_date").val();

        console.log(period_start_date);
        if($('#frequency').val() != 0 && $('#frequency').val() != 1 && $('#recurring_issue_date').val() != "" && period_start_date != "" && period_end_date != "")
        {
            changeRemark($(this), $('#frequency').val(), $('#recurring_issue_date').val(), period_start_date, period_end_date);
        }
    });
});

$(document).on('change','#recurring_issue_date',function(e){
    $('#body_create_recurring').find('.tr_recurring').each(function(){
        console.log($(this).find(".period_start_date"));
        var period_start_date = $(this).find(".period_start_date").val();
        var period_end_date = $(this).find(".period_end_date").val();

        console.log(period_start_date);
        if($('#frequency').val() != 0 && $('#frequency').val() != 1 && $('#recurring_issue_date').val() != "" && period_start_date != "" && period_end_date != "")
        {
            changeRemark($(this), $('#frequency').val(), $('#recurring_issue_date').val(), period_start_date, period_end_date);
        }
    });
});

$(document).on('change','.period_start_date',function(e){
    console.log($(this).parent().parent().parent().parent().parent().parent());
    var this_row = $(this).parent().parent().parent().parent();
    if($('#frequency').val() != 0 && $('#frequency').val() != 1 && $('#recurring_issue_date').val() != "" && this_row.find('.period_end_date').val() != "")
    {
        changeRemark(this_row, $('#frequency').val(), $('#recurring_issue_date').val(), this_row.find('.period_start_date').val(), this_row.find('.period_end_date').val());
    }
    
});

$(document).on('change','.period_end_date',function(e){
    console.log($(this).parent().parent().parent().parent().parent().parent());
    var this_row = $(this).parent().parent().parent().parent();
    if($('#frequency').val() != 0 && $('#frequency').val() != 1 && $('#recurring_issue_date').val() != "" && this_row.find('.period_start_date').val() != "")
    {
        changeRemark(this_row, $('#frequency').val(), $('#recurring_issue_date').val(), this_row.find('.period_start_date').val(), this_row.find('.period_end_date').val());
    }
    
});

function changeRemark(this_row, frequency, recurring_issue_date, period_start_date, period_end_date)
{
    $('#loadingmessage').show();
    $.ajax({
        type: "POST",
        url: "masterclient/check_next_recurring_date",
        data: {"frequency": frequency, "recurring_issue_date": recurring_issue_date, "period_end_date": period_end_date},
        dataType: "json",
        asycn: false,
        success: function(data){
            console.log(data);
            $('#loadingmessage').hide();
            this_row.find(".remark").text("");
            if(data.status == 1)
            {
                this_row.find(".remark").html("Remarks: <br>1. Your next recurring bill will issue on "+recurring_issue_date+" for your billing cycle "+period_start_date+" to "+period_end_date+" <br> 2. Your subsequent recurring bill will issue on "+data.issue_date+" for your billing cycle "+data.next_from_billing_cycle+" to "+data.next_to_billing_cycle+"");
            }
        }               
    });
}

$(document).ready(function() {
    $('#loadingBilling').hide();
});
// if(access_right_recurring_module == "read" || access_right_unpaid_module == "read")
// {
//     $('input').attr("disabled", true);
//     $('button').attr("disabled", true);
//     $('select').attr("disabled", true);
//     $('textarea').attr("disabled", true);
//     $("#recurring_service_info_Add").hide();
// }