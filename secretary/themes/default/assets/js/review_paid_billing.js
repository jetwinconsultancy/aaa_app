var latest_gst_rate = 0;
/*var invoice_description = {
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
            row: '.input-group',
            validators: {
                callback: {
                    message: 'The Service field is required.',
                    callback: function(value, validator, $field) {
                        var num = jQuery($field).parent().parent().parent().attr("num");
                        var options = validator.getFieldElements('service['+num+']').val();
                        console.log(options);
                        return (options != null && options != "0");
                    }
                }
            }
        };

function ajaxCall() {
    this.send = function(data, url, method, success, type) {
        type = type||'json';
        console.log(data);
        var successRes = function(data) {
            success(data);
        };

        var errorRes = function(e) {
          console.log(e);
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
        var url = base_url+"/secretary/"+'companytype/getCurrency';
        //console.log(url);
        var method = "get";
        var data = {};
        $('.currency').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            console.log(data);
            $('.currency').find("option:eq(0)").html("Select Currency");
            console.log(data);
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
        var url = base_url+"/secretary/"+'companytype/getClientName';
        //console.log(url);
        var method = "get";
        var data = {};
        $('.client_name').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            console.log(data);
            $('.client_name').find("option:eq(0)").html("Select Client Name");
            console.log(data);
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
                //$(".nationality").prop("disabled",false);
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
    //cm.getDirectorSignature1();
});*/

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
}
else
{
    //console.log(billing_top_info);
    if(billing_top_info[0]["postal_code"] != "" || billing_top_info[0]["street_name"] != "")
    {
        var units = " ";

        if(billing_top_info[0]["unit_no1"] != "" || billing_top_info[0]["unit_no2"] != "")
        {
            units = billing_top_info[0]["unit_no1"] + " - " + billing_top_info[0]["unit_no2"];
        }
        else if(billing_top_info[0]["unit_no1"] != "")
        {
            units = billing_top_info[0]["unit_no1"];
        }
        else if(billing_top_info[0]["unit_no2"] != "")
        {
            units = billing_top_info[0]["unit_no2"];
        }
        var nonedit_address = billing_top_info[0]["street_name"]+'\n#'+units+' '+billing_top_info[0]["building_name"]+'\nSingapore '+billing_top_info[0]["postal_code"];
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

    $('#invoice_no').text(billing_top_info[0]["invoice_no"]);
    $('[id="billing_date"]').text(billing_top_info[0]["invoice_date"]);
    $('[id="client_name"]').text(billing_top_info[0]["company_name"]);
    $('[id="currency_name"]').text(billing_top_info[0]["currency_name"]);
    $('[id="address"]').text(nonedit_address);
    $('[id="rate"]').text(billing_top_info[0]["rate"]);

    $('[name="invoice_no"]').attr('disabled', true);
    $('[name="billing_date"]').attr('disabled', true);
    $('[name="address"]').attr('disabled', true);

}


var service_array = "";



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
    //console.log(billing_below_info[0]["currency_id"]);
    $("#sub_total").text(addCommas(sum.toFixed(2)));
    //console.log($(".currency").val());

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
    //console.log(billing_below_info);
    for(var t = 0; t < billing_below_info.length; t++)
    {
        $count_billing_service_info = t;
        $a=""; 
        /*<select class="input-sm" style="text-align:right;width: 150px;" id="position" name="position[]" onchange="optionCheck(this);"><option value="Director" >Director</option><option value="CEO" >CEO</option><option value="Manager" >Manager</option><option value="Secretary" >Secretary</option><option value="Auditor" >Auditor</option><option value="Managing Director" >Managing Director</option><option value="Alternate Director">Alternate Director</option></select>*/
        $a += '<div class="tr editing tr_billing" method="post" name="form'+$count_billing_service_info+'" id="form'+$count_billing_service_info+'" num="'+$count_billing_service_info+'">';
        $a += '<div class="hidden"><input type="text" class="form-control" name="company_code" value=""/></div>';
        $a += '<div class="hidden"><input type="text" class="form-control" name="billing_service_id" value="'+billing_below_info[t]["billing_service_id"]+'"/></div>';
        $a += '<div class="hidden"><input type="text" class="form-control" name="client_billing_info_id['+$count_billing_service_info+']" id="client_billing_info_id" value="'+billing_below_info[t]["client_billing_info_id"]+'"/></div>';
        //$a += '<div class="td"><div class="input-group"><span>'+billing_below_info[t]["service_name"]+'</span></div></div>';
        $a += '<div class="td"><div class="input-group"><span>'+billing_below_info[t]["service_name"]+'</span></div><div class="input-group"><div style="margin-bottom: 5px;"><span style="font-weight: bold;">Progress Billing: </span><span>'+((billing_below_info[t]["progress_billing_yes_no"] == "yes")?"Yes":"No")+'</span></div></div><div class="poc_percent_div input-group mb-md" style="display:none"><div class="form-check"><label class="form-check-label" style="margin-top: 7px;" for="poc_percentage">'+((billing_below_info[t]["poc_percentage"] != null)?billing_below_info[t]["poc_percentage"]:"")+'% of <span class="number_of_percent_poc">'+billing_below_info[t]["number_of_percent_poc"]+'</span></label></div></div></div>';
        //$a += '<div class="td"><div class="input-group mb-md"><span>'+billing_below_info[t]["invoice_description"]+'</span></div><div style="width: 200px;display: inline-block; margin-right:10px;"><div style="font-weight: bold;">Period Start Date</div><span>'+billing_below_info[t]["period_start_date"]+'</span></div><div style="width: 200px;display: inline-block"><div style="font-weight: bold;">Period End Date</div><span>'+billing_below_info[t]["period_end_date"]+'</span></div></div>';
        $a += '<div class="td"><div class="input-group mb-md"><span>'+billing_below_info[t]["invoice_description"]+'</span></div><div class="period_class" style="width: 200px;display: inline-block; margin-right:10px;"><div style="font-weight: bold;">Period Start Date</div><span>'+billing_below_info[t]["period_start_date"]+'</span></div><div class="period_class" style="width: 200px;display: inline-block"><div style="font-weight: bold;">Period End Date</div><span>'+billing_below_info[t]["period_end_date"]+'</span></div><div class="input-group mb-md"><div class="type_reading_quantity_class" style="font-weight: bold; margin-bottom: 5px; display:none;">Type</div><div class="form-check form-check-inline rate_class" style="display:none"><span>'+((billing_below_info[t]["radio_quantity_reading"] == "reading")?"Reading":"Quantity")+'</span></div></div><div class="quantity" style="width: 200px;display: none; margin-right:10px;"><div style="font-weight: bold;">Quantity</div><span>'+billing_below_info[t]["quantity_value"]+'</span></div><div class="rate_class reading" style="width: 200px;display: none; margin-right:10px;"><div style="font-weight: bold;">Reading at beginning</div><span>'+billing_below_info[t]["reading_at_begin"]+'</span></div><div class="rate_class reading" style="width: 200px;display: none"><div style="font-weight: bold;">Reading at the end</div><span>'+billing_below_info[t]["reading_at_the_end"]+'</span></div><div class="rate_class" style="width: 200px;display: none; margin-right:10px;"><div style="font-weight: bold;">Rate</div><span>'+billing_below_info[t]["number_of_rate"]+'</span></div><div class="rate_class" style="width: 200px;display: none"><div style="font-weight: bold;">Measurement Unit</div><span>'+billing_below_info[t]["unit_for_rate"]+'</span></div></div>';
        $a += '<div class="td"><div class="input-group" style="float: right;"><span>'+addCommas(billing_below_info[t]["billing_service_amount"])+'</span><input type="hidden" name="amount['+$count_billing_service_info+']" class="numberdes form-control text-right amount" value="'+addCommas(billing_below_info[t]["billing_service_amount"])+'" id="amount" style="width:250px"/><div id="form_amount"></div></div></div>';
        /*$a += '<div class="td action"><button type="button" class="btn btn-primary delete_billing_button" onclick="delete_billing(this)" style="display: none;">Delete</button></div>';*/
        $a += '</div>';

        /*<input type="button" value="Save" id="save" name="save'+$count_officer+'" class="btn btn-primary" onclick="save(this);">*/
        $("#body_create_billing").prepend($a); 

        if($("#body_create_billing > div").length > 1)
        {
            $('.delete_billing_button').css('display','block');
        }

        if(billing_below_info[t]["progress_billing_yes_no"] == "yes")
        {
            $("#form"+$count_billing_service_info+" .poc_percent_div").show();
            //$("#form"+$count_billing_service_info+" #progress_billing_yes").prop("checked", true);
        }
        else if(billing_below_info[t]["progress_billing_yes_no"] == "no")
        {
            $("#form"+$count_billing_service_info+" .poc_percent_div").hide();
            //$("#form"+$count_billing_service_info+" #progress_billing_no").prop("checked", true);
        }

        for(var z = 0; z < service_dropdown.length; z++)
        {
            //res[service_array[i]['service']] = service_array[i]['service_name'];

            // var option = $('<option />');
            // option.attr('data-invoice_description', service_dropdown[z]['invoice_description']);
            // option.attr('data-amount', service_dropdown[z]['amount']);
            // option.attr('data-client_billing_info_id', service_dropdown[z]['client_billing_info_id']);
            // option.attr('value', service_dropdown[z]['service']).text(service_dropdown[z]['service_name']);
            // console.log(billing_below_info[t]["service"]);
            // console.log(service_dropdown[z]['service_name']);
            if(billing_below_info[t]["service"] != null && service_dropdown[z]['id'] == billing_below_info[t]["service"])
            {
                //option.attr('selected', 'selected');
                if(service_dropdown[z]['calculate_by_quantity_rate'] == "1")
                {
                    $("#form"+$count_billing_service_info+" .period_class").hide();
                    $("#form"+$count_billing_service_info+" .rate_class").css('display', 'inline-block');
                    $("#form"+$count_billing_service_info+" .type_reading_quantity_class").css('display', 'block');

                    if(billing_below_info[t]["radio_quantity_reading"] == "quantity")
                    {
                        $("#form"+$count_billing_service_info+" .quantity").css('display', 'inline-block');
                        $("#form"+$count_billing_service_info+" .reading").hide();
                        //$("#form"+$count_billing_service_info+" #radio_quantity").prop("checked", true);
                    }
                    else if(billing_below_info[t]["radio_quantity_reading"] == "reading")
                    {
                        $("#form"+$count_billing_service_info+" .quantity").hide();
                        $("#form"+$count_billing_service_info+" .reading").css('display', 'inline-block');
                        //$("#form"+$count_billing_service_info+" #radio_reading").prop("checked", true);
                    }
                }
                else if(service_dropdown[z]['calculate_by_quantity_rate'] == "2")
                {
                    $("#form"+$count_billing_service_info+" .period_class").css('display', 'inline-block');
                    $("#form"+$count_billing_service_info+" .rate_class").hide();
                    $("#form"+$count_billing_service_info+" .type_reading_quantity_class").hide();
                    //$("#form"+$count_billing_service_info+" #radio_reading").prop("checked", true);
                }

            }

            //$("#form"+t+" #service").append(option);
        }    
        service_array = service_dropdown;
       /* $('#create_billing_form').formValidation('addField', 'service['+$count_billing_service_info+']', service);
        $('#create_billing_form').formValidation('addField', 'invoice_description['+$count_billing_service_info+']', invoice_description);
        $('#create_billing_form').formValidation('addField', 'amount['+$count_billing_service_info+']', amount);*/
    }
    sum_first_total();
}

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
    $a += '<div class="td"><div class="input-group" style="width:70%;"><select class="input-sm form-control" name="service['+$count_billing_service_info+']" id="service" style="width:250px;"><option value="0" data-invoice_description="" data-amount="">Select Service</option></select><div id="form_service"></div></div></div>';
    $a += '<div class="td"><div class="input-group"><textarea class="form-control" name="invoice_description['+$count_billing_service_info+']"  id="invoice_description" rows="3" style="width:450px"></textarea></div></div>';
    $a += '<div class="td"><div class="input-group"><input type="text" name="amount['+$count_billing_service_info+']" class="numberdes form-control text-right amount" value="" id="amount" style="width:250px"/><div id="form_amount"></div></div></div>';
    /*$a += '<div class="td action"><button type="button" class="btn btn-primary" onclick="edit_billing_info(this);">Save</button></div></div>';*/
    /*$a += '<div class="td action"><button type="button" class="btn btn-primary delete_billing_button" onclick="delete_billing(this)" style="display: block;">Delete</button></div>';*/
    $a += '</div>';

    /*<input type="button" value="Save" id="save" name="save'+$count_officer+'" class="btn btn-primary" onclick="save(this);">*/
    $("#body_create_billing").prepend($a); 

    if($("#body_create_billing > div").length > 1)
    {
        $('.delete_billing_button').css('display','block');
    }

    for(var i = 0; i < service_array.length; i++)
    {
        //res[service_array[i]['service']] = service_array[i]['service_name'];

        var option = $('<option />');
        option.attr('data-invoice_description', service_array[i]['invoice_description']);
        option.attr('data-amount', service_array[i]['amount']);
        option.attr('data-client_billing_info_id', service_array[i]['client_billing_info_id']);
        option.attr('value', service_array[i]['service']).text(service_array[i]['service_name']);
        
        $("#form"+$count_billing_service_info+" #service").append(option);
    }    

    $('#create_billing_form').formValidation('addField', 'service['+$count_billing_service_info+']', service);
    $('#create_billing_form').formValidation('addField', 'invoice_description['+$count_billing_service_info+']', invoice_description);
    $('#create_billing_form').formValidation('addField', 'amount['+$count_billing_service_info+']', amount);

    $count_billing_service_info++;
});

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
                        ///console.log(options);
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

$(document).on('change','#create_billing_form #service',function(e){
    var num = $(this).parent().parent().parent().attr("num");

    var selected_invoice_description = $(this).find(':selected').data('invoice_description');
    var selected_amount = $(this).find(':selected').data('amount');
    var selected_client_billing_info_id = $(this).find(':selected').data('client_billing_info_id');
    //console.log(selected_invoice_description);
    $(this).parent().parent().parent().find('#invoice_description').val(selected_invoice_description);
    $(this).parent().parent().parent().find('#amount').val(addCommas(selected_amount));
    $(this).parent().parent().parent().find('#client_billing_info_id').val(addCommas(selected_client_billing_info_id));

    sum_total();

    $('#create_billing_form').formValidation('revalidateField', 'service['+num+']');
    $('#create_billing_form').formValidation('revalidateField', 'invoice_description['+num+']');
    $('#create_billing_form').formValidation('revalidateField', 'amount['+num+']');
});

$(document).on('change','#create_billing_form #client_name',function(e){
    var company_code = $('#client_name option:selected').val();

    //console.log(company_code);

    $.ajax({
        type: "POST",
        url: "billings/get_company_service",
        data: {"company_code":company_code}, // <--- THIS IS THE CHANGE
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
                $a0 += '<div class="td"><div class="input-group" style="width:70%;"><select class="input-sm form-control" name="service['+0+']" id="service" style="width:250px;"><option value="0" data-invoice_description="" data-amount="">Select Service</option></select><div id="form_service"></div></div></div>';
                $a0 += '<div class="td"><div class="input-group"><textarea class="form-control" name="invoice_description['+0+']"  id="invoice_description" rows="3" style="width:450px"></textarea></div></div>';
                $a0 += '<div class="td"><div class="input-group"><input type="text" name="amount['+0+']" class="numberdes form-control text-right amount" value="" id="amount" style="width:250px"/><div id="form_amount"></div></div></div>';
                /*$a += '<div class="td action"><button type="button" class="btn btn-primary" onclick="edit_billing_info(this);">Save</button></div></div>';*/
                // $a0 += '<div class="td action"><button type="button" class="btn btn-primary delete_billing_button" onclick="delete_billing(this)" style="display: none;">Delete</button></div>';
                $a0 += '</div>';

                $("#body_create_billing").prepend($a0); 

                if($("#body_create_billing > div").length > 1)
                {
                    $('.delete_billing_button').css('display','block');
                }

                for(var i = 0; i < service_array.length; i++)
                {
                    //res[service_array[i]['service']] = service_array[i]['service_name'];

                    var option = $('<option />');
                    option.attr('data-invoice_description', service_array[i]['invoice_description']);
                    option.attr('data-amount', service_array[i]['amount']);
                    option.attr('data-client_billing_info_id', service_array[i]['client_billing_info_id']);
                    option.attr('value', service_array[i]['service']).text(service_array[i]['service_name']);
                    
                    $("#form"+0+" #service").append(option);
                }

                $('#create_billing_form').formValidation('addField', 'service['+0+']', service);
                $('#create_billing_form').formValidation('addField', 'invoice_description['+0+']', invoice_description);
                $('#create_billing_form').formValidation('addField', 'amount['+0+']', amount);  
            }

        }               
    });
    $('#create_billing_form').formValidation('revalidateField', 'client_name');

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

    //console.log(valid_setup);
    if(valid_setup)
    {
        //$("#create_billing_form").formValidation('destroy');
        $('[name="invoice_no"]').attr('disabled', false);
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