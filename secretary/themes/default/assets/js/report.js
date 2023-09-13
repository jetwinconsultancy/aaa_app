$("#header_our_firm").removeClass("header_disabled");
$("#header_manage_user").removeClass("header_disabled");
$("#header_access_right").removeClass("header_disabled");
$("#header_user_profile").removeClass("header_disabled");
$("#header_setting").removeClass("header_disabled");
$("#header_dashboard").removeClass("header_disabled");
$("#header_client").removeClass("header_disabled");
$("#header_person").removeClass("header_disabled");
$("#header_document").removeClass("header_disabled");
$("#header_report").addClass("header_disabled");
$("#header_billings").removeClass("header_disabled");

$("#report_to_generate").live('change',function(){
	//console.log($("#report_to_generate").val());
	var search = $("#report_to_generate").val();

	$("#person_profile").remove();
    $("#client_list").remove();
    $("#due_date").remove();
    $("#list_of_invoice").remove();
    $("#list_of_credit_note").remove();
    $(".invoice_period").remove();
    $("#payment_list").remove();
    $("#bank_transaction_list").remove();
    $("#sales_report_list").remove();
    $("#gst_report").remove();

    $('.report_date_from').datepicker('setStartDate', null);
    $('.report_date_from').datepicker('setEndDate', null);
    $('.report_date_to').datepicker('setStartDate', null);
    $('.report_date_to').datepicker('setEndDate', null);
    $('.report_date_from').val("").datepicker("update");
    $('.report_date_to').val("").datepicker("update");

	if(search == "person_profile")
	{
		$("#searchRegister").hide();
		$("#hidden_section1_report").show();
		$("#hidden_section2_report").show();
        $(".hidden_section3_report").hide();
        $(".hidden_section4_report").hide();
        $(".hidden_section5_report").hide();
        $(".hidden_section6_report").hide();
        $(".hidden_section7_report").hide();
        $(".hidden_section8_report").hide();
        $(".hidden_section9_report").hide();
        $(".hidden_section10_report").hide();
        $(".hidden_section11_report").hide();
	}
	else if(search == "client_list")
	{
		$("#searchRegister").show();
		$("#hidden_section1_report").hide();
		$("#hidden_section2_report").hide();
        $(".hidden_section3_report").hide();
        $(".hidden_section4_report").hide();
        $(".hidden_section5_report").show();
        $(".hidden_section6_report").hide();
        $(".hidden_section7_report").hide();
        $(".hidden_section8_report").hide();
        $(".hidden_section9_report").hide();
        $(".hidden_section10_report").hide();
        $(".hidden_section11_report").hide();
	}
	else if(search == "due_date")
	{
		$("#searchRegister").show();
		$("#hidden_section1_report").hide();
		$("#hidden_section2_report").hide();
        $(".hidden_section3_report").hide();
        $(".hidden_section4_report").show();
        $(".hidden_section5_report").hide();
        $(".hidden_section6_report").hide();
        $(".hidden_section7_report").hide();
        $(".hidden_section8_report").hide();
        $(".hidden_section9_report").hide();
        $(".hidden_section10_report").hide();
        $(".hidden_section11_report").hide();
	}
	else if(search == "list_of_invoice")
	{
		$("#searchRegister").hide();
		$("#hidden_section1_report").hide();
		$("#hidden_section2_report").show();
        $(".hidden_section3_report").show();
        $(".hidden_section4_report").hide();
        $(".hidden_section5_report").hide();
        $(".hidden_section6_report").hide();
        $(".hidden_section7_report").hide();
        $(".hidden_section8_report").hide();
        $(".hidden_section9_report").hide();
        $(".hidden_section10_report").hide();
        $(".hidden_section11_report").hide();
	}
    else if(search == "list_of_credit_note")
    {
        $("#searchRegister").hide();
        $("#hidden_section1_report").hide();
        $("#hidden_section2_report").show();
        $(".hidden_section3_report").show();
        $(".hidden_section4_report").hide();
        $(".hidden_section5_report").hide();
        $(".hidden_section6_report").hide();
        $(".hidden_section7_report").hide();
        $(".hidden_section8_report").hide();
        $(".hidden_section9_report").hide();
        $(".hidden_section10_report").hide();
        $(".hidden_section11_report").hide();
    }
    else if(search == "invoice_period")
    {
        $("#searchRegister").show();
        $("#hidden_section1_report").hide();
        $("#hidden_section2_report").hide();
        $(".hidden_section3_report").hide();
        $(".hidden_section4_report").hide();
        $(".hidden_section5_report").hide();
        $(".hidden_section6_report").hide();
        $(".hidden_section7_report").hide();
        $(".hidden_section8_report").hide();
        $(".hidden_section9_report").hide();
        $(".hidden_section10_report").hide();
        $(".hidden_section11_report").hide();
    }
    else if(search == "payment")
    {
        $("#searchRegister").show();
        $("#hidden_section1_report").hide();
        $("#hidden_section2_report").hide();
        $(".hidden_section3_report").show();
        $(".hidden_section4_report").hide();
        $(".hidden_section5_report").hide();
        $(".hidden_section6_report").show();
        $(".hidden_section7_report").show();
        $(".hidden_section8_report").show();
        $(".hidden_section9_report").hide();
        $(".hidden_section10_report").hide();
        $(".hidden_section11_report").hide();
    }
    else if(search == "bank_transaction")
    {
        $("#searchRegister").hide();
        $("#hidden_section1_report").hide();
        $("#hidden_section2_report").show();
        $(".hidden_section3_report").show();
        $(".hidden_section4_report").hide();
        $(".hidden_section5_report").hide();
        $(".hidden_section6_report").hide();
        $(".hidden_section7_report").hide();
        $(".hidden_section8_report").hide();
        $(".hidden_section9_report").show();
        $(".hidden_section10_report").hide();
        $(".hidden_section11_report").hide();
    }
    else if(search == "sales_report")
    {
        $("#searchRegister").hide();
        $("#hidden_section1_report").hide();
        $("#hidden_section2_report").show();
        $(".hidden_section3_report").show();
        $(".hidden_section4_report").hide();
        $(".hidden_section5_report").hide();
        $(".hidden_section6_report").hide();
        $(".hidden_section7_report").hide();
        $(".hidden_section8_report").hide();
        $(".hidden_section9_report").hide();
        $(".hidden_section10_report").hide();
        $(".hidden_section11_report").hide();
    }
    else if(search == "register_contorller")
    {
        $("#searchRegister").show();
        $("#hidden_section1_report").hide();
        $("#hidden_section2_report").hide();
        $(".hidden_section3_report").show();
        $(".hidden_section4_report").hide();
        $(".hidden_section5_report").hide();
        $(".hidden_section6_report").hide();
        $(".hidden_section7_report").hide();
        $(".hidden_section8_report").hide();
        $(".hidden_section9_report").hide();
        $(".hidden_section10_report").hide();
        $(".hidden_section11_report").hide();
    }
    else if(search == "list_of_recurring")
    {
        $("#searchRegister").hide();
        $("#hidden_section1_report").hide();
        $("#hidden_section2_report").show();
        $(".hidden_section3_report").show();
        $(".hidden_section4_report").hide();
        $(".hidden_section5_report").hide();
        $(".hidden_section6_report").hide();
        $(".hidden_section7_report").hide();
        $(".hidden_section8_report").hide();
        $(".hidden_section9_report").hide();
        $(".hidden_section10_report").hide();
        $(".hidden_section11_report").hide();
    }
    else if(search == "list_of_receipt")
    {
        $("#searchRegister").hide();
        $("#hidden_section1_report").hide();
        $("#hidden_section2_report").show();
        $(".hidden_section3_report").show();
        $(".hidden_section4_report").hide();
        $(".hidden_section5_report").hide();
        $(".hidden_section6_report").hide();
        $(".hidden_section7_report").hide();
        $(".hidden_section8_report").hide();
        $(".hidden_section9_report").hide();
        $(".hidden_section10_report").hide();
        $(".hidden_section11_report").hide();
    }
    else if(search == "list_of_document")
    {
        $("#searchRegister").hide();
        $("#hidden_section1_report").hide();
        $("#hidden_section2_report").show();
        $(".hidden_section3_report").show();
        $(".hidden_section4_report").hide();
        $(".hidden_section5_report").hide();
        $(".hidden_section6_report").hide();
        $(".hidden_section7_report").hide();
        $(".hidden_section8_report").hide();
        $(".hidden_section9_report").hide();
        $(".hidden_section10_report").hide();
        $(".hidden_section11_report").hide();
    }
    else if(search == "gst_report")
    {
        $("#searchRegister").hide();
        $("#hidden_section1_report").hide();
        $("#hidden_section2_report").show();
        $(".hidden_section3_report").hide();
        $(".hidden_section4_report").hide();
        $(".hidden_section5_report").hide();
        $(".hidden_section6_report").hide();
        $(".hidden_section7_report").hide();
        $(".hidden_section8_report").hide();
        $(".hidden_section9_report").hide();
        $(".hidden_section10_report").show();
        $(".hidden_section11_report").hide();

        var gst_register_date = $(".gst_report_firm").find(':selected').data('gst_register_date');
        if(gst_register_date != null)
        {
            var array_register_date = gst_register_date.split("-");
            var format_gst_register_date = array_register_date[1]+"/"+array_register_date[2]+"/"+array_register_date[0];
            var latest_gst_register_date = new Date(format_gst_register_date);
            $('.report_date_from').datepicker('setStartDate', latest_gst_register_date).datepicker("setDate", latest_gst_register_date);
            $('.report_date_to').datepicker('setStartDate', latest_gst_register_date);
        }
        // var gst_deregister_date = $(".gst_report_firm").find(':selected').data('gst_deregister_date');
        // if(gst_deregister_date != null)
        // {
        //     var array_deregister_date = gst_deregister_date.split("-");
        //     var format_gst_deregister_date = array_deregister_date[1]+"/"+array_deregister_date[2]+"/"+array_deregister_date[0];
        //     var latest_gst_deregister_date = new Date(format_gst_deregister_date);
        //     $('.report_date_from').datepicker('setEndDate', latest_gst_deregister_date).datepicker("setDate", latest_gst_deregister_date);
        //     $('.report_date_to').datepicker('setEndDate', latest_gst_deregister_date);
        // }
    }
    else if(search == "csp_report")
    {
        $("#searchRegister").hide();
        $("#hidden_section1_report").hide();
        $("#hidden_section2_report").show();
        $(".hidden_section3_report").show();
        $(".hidden_section4_report").hide();
        $(".hidden_section5_report").hide();
        $(".hidden_section6_report").hide();
        $(".hidden_section7_report").hide();
        $(".hidden_section8_report").hide();
        $(".hidden_section9_report").hide();
        $(".hidden_section10_report").hide();
        $(".hidden_section11_report").show();
    }
    else if(search == "progress_bill_report")
    {
        $("#searchRegister").hide();
        $("#hidden_section1_report").hide();
        $("#hidden_section2_report").show();
        $(".hidden_section3_report").show();
        $(".hidden_section4_report").hide();
        $(".hidden_section5_report").hide();
        $(".hidden_section6_report").hide();
        $(".hidden_section7_report").hide();
        $(".hidden_section8_report").hide();
        $(".hidden_section9_report").hide();
        $(".hidden_section10_report").hide();
        $(".hidden_section11_report").hide();
    }
});

$.ajax({
    type: 'POST',
    url: "report/get_name",
    data: '&firm=' + encodeURIComponent($(".firm").val()) + '&type_of_payment=' + encodeURIComponent($(".type_of_payment").val()),
    dataType: 'json',
    success: function(response){
        $(".payment_username option").remove();
        var first_option = $('<option />');
            first_option.attr('value', "all").text("ALL");

        $('.payment_username').append(first_option);

        $.each(response.name, function(key, val) {
            var option = $('<option />');
            option.attr('value', key).text(val);

            $("#payment_username").append(option);
        });
    }
});

get_bank_info();
function get_bank_info()
{
    $('#loadingmessage').show();
    $.ajax({
        type: 'POST',
        url: "report/get_bank_info",
        data: '&firm=' + encodeURIComponent($(".firm").val()),
        dataType: 'json',
        success: function(response){
            $('#loadingmessage').hide();
            $(".bank_account option").remove();
            var first_option = $('<option />');
                first_option.attr('value', "0").text("Please Select");

            $('.bank_account').append(first_option);

            $.each(response.result, function(key, val) {
                var option = $('<option />');
                option.attr('value', key).text(val);

                $("#bank_account").append(option);
            });
        }
    });
}

$(document).on('change',"#firm",function() 
{
    if($('#report_to_generate').val() == "bank_transaction")
    {
        get_bank_info();
    }
});

$(document).on('change',"#gst_report_firm",function() 
{
    if($('#report_to_generate').val() == "gst_report")
    {
        var gst_register_date = $(".gst_report_firm").find(':selected').data('gst_register_date');
        if(gst_register_date != null)
        {
            var array_register_date = gst_register_date.split("-");
            var format_gst_register_date = array_register_date[1]+"/"+array_register_date[2]+"/"+array_register_date[0];
            var latest_gst_register_date = new Date(format_gst_register_date);
            $('.report_date_from').datepicker('setStartDate', latest_gst_register_date).datepicker("setDate", latest_gst_register_date);
            $('.report_date_to').datepicker('setStartDate', latest_gst_register_date);
        }
    }
});

$(document).on('change',".type_of_payment",function() 
{
    //console.log($(this).val());
    // if($(this).val() == "supplier")
    // {
        $.ajax({
            type: 'POST',
            url: "report/get_name",
            data: '&firm=' + encodeURIComponent($(".firm").val()) + '&type_of_payment=' + encodeURIComponent($(".type_of_payment").val()),
            dataType: 'json',
            success: function(response){
                //console.log(response.name);
                $(".payment_username option").remove();
                var first_option = $('<option />');
                    first_option.attr('value', "all").text("ALL");

                $('.payment_username').append(first_option);

                $.each(response.name, function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);

                    $("#payment_username").append(option);
                });
            }
        });
    // }
    // else if($(this).val() == "claim")
    // {
        // $.ajax({
        //     type: 'POST',
        //     url: "report/get_name",
        //     data: '&firm=' + encodeURIComponent($(".firm").val()) + '&type_of_payment=' + encodeURIComponent($(".type_of_payment").val()),
        //     dataType: 'json',
        //     success: function(response){
        //         console.log(response.name);
        //         $(".payment_username option").remove();
        //         var first_option = $('<option />');
        //             first_option.attr('value', "all").text("ALL");

        //         $('.payment_username').append(first_option);

        //         $.each(response.name, function(key, val) {
        //             var option = $('<option />');
        //             option.attr('value', key).text(val);

        //             $("#payment_username").append(option);
        //         });
        //     }
        // });
    //}
});

$('#printReportBtn').click(function(){
	//console.log("printReportBtn");
    
    $(".printablereport").print({
    	globalStyles: true,
    	mediaPrint: false
    });

    // Cancel click event
    return(false);
});

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

  return year + monthNames[monthIndex] + day;
}

function changeDateFormat(date)
{
    var change_date_parts = date.split('/');
    var change_date = change_date_parts[2]+"/"+change_date_parts[1]+"/"+change_date_parts[0];

    return change_date;
}

function changeDateFormatWithDash(date)
{
    var change_date_parts = date.split('-');
    var change_date = change_date_parts[2]+"/"+change_date_parts[1]+"/"+change_date_parts[0];

    return change_date;
}
                        
if(firm != null)
{
    $.each(firm, function(key, val) {
        var option = $('<option />');
        option.attr('value', key).text(val);
        // if(transaction_issue_dividend)
        // {
        //     if(transaction_issue_dividend[0]["currency"] != undefined && key == transaction_issue_dividend[0]["currency"])
        //     {
        //         option.attr('selected', 'selected');
        //     }
        // }
        $("#firm").append(option);
    });
}

if(gst_firm != null)
{
    $.each(gst_firm, function(key, val) {
        var option = $('<option />');
        option.attr('data-gst_deregister_date', val["gst_deregister_date"]).attr('data-gst_register_date', val["gst_register_date"]).attr('value', val["id"]).text(val["name"]);
        // if(transaction_issue_dividend)
        // {
        //     if(transaction_issue_dividend[0]["currency"] != undefined && key == transaction_issue_dividend[0]["currency"])
        //     {
        //         option.attr('selected', 'selected');
        //     }
        // }
        $("#gst_report_firm").append(option);
    });
}

$.each(service_category, function(key, val) {
    var option = $('<option />');
    option.attr('value', key).text(val);
    // if(transaction_issue_dividend)
    // {
    //     if(transaction_issue_dividend[0]["currency"] != undefined && key == transaction_issue_dividend[0]["currency"])
    //     {
    //         option.attr('selected', 'selected');
    //     }
    // }
    $("#service_category").append(option);
});

function generate_sales_report()
{
    //console.log("sales_report");
    $('#loadingmessage').show();
    $.ajax({
        type: "POST",
        url: "report/export_sales_report",
        data: $('#report_form').serialize(), // <--- THIS IS THE CHANGE
        dataType: "json",
        success: function(response){
            $('#loadingmessage').hide();
            if(response.status != "fail")
            {
                window.open(
                  response.link,
                  '_blank' // <- This is what makes it open in a new window.
                );
            }
            else
            {
                toastr.error("The excel cannot be generated.", "Unsuccessful");
            }
        }               
    });
}

function generate_register_of_controller()
{
    $('#loadingmessage').show();
    $.ajax({
        type: "POST",
        url: "report/export_register_of_controller",
        data: $('#report_form').serialize(), // <--- THIS IS THE CHANGE
        dataType: "json",
        success: function(response){
            $('#loadingmessage').hide();
            if(response.status != "fail")
            {
                window.open(
                  response.link,
                  '_blank' // <- This is what makes it open in a new window.
                );
            }
            else
            {
                toastr.error("The excel cannot be generated.", "Unsuccessful");
            }
        }               
    });
}

$('.search_report').click(function(e){
	e.preventDefault();
	$('#loadingmessage').show();
	$.ajax({
        type: 'POST',
        url: "report/search_report",
        data: $('#report_form').serialize(),
        dataType: 'json',
        success: function(response){
        	$('#loadingmessage').hide();
            $("#person_profile").remove();
            $("#client_list").remove();
            $("#due_date").remove();
            $("#list_of_invoice").remove();
            $("#list_of_credit_note").remove();
            $(".invoice_period").remove();
            $("#payment_list").remove();
            $("#bank_transaction_list").remove();
            $("#sales_report_list").remove();
            $("#register_controller_list").remove();
            $("#list_of_recurring").remove();
            $("#list_of_receipt").remove();
            $("#list_of_document").remove();
            $("#gst_report").remove();
            $("#progress_bill_report").remove();
            /*$("#register_charges").remove();
            $("#register_member").remove();
            $("#register_guarantee_member").remove();
            $("#register_controller").remove();
            $("#register_profile").hide();*/

            //console.log(response[0]["client_list"]);
            if (response.register === "person_profile" || response.register === "all") 
            {
            	if(response.register === "all")
            	{
            		if(response[0] != undefined)
            		{
            			var person_profile = response[0]["person_profile"];
            		}
            		else
            		{
            			var person_profile = '';
            		}
            	}
            	else
            	{
            		if(response[0] != undefined)
            		{
            			var person_profile = response[0]["person_profile"];
            		}
            		else
            		{
            			var person_profile = '';
            		}
            	}

            	$a = "";
            	$a += '<div id="person_profile" style="margin-top:20px;">';
            	$a += '<h3>Person Profile</h3>';
            	//$a += '<table class="table table-bordered table-striped mb-none" id="datatable-default register_officer_table">';
				$a += '<table class="table table-bordered table-striped mb-none person_profile_table" id="report_table_person_profile">';
				$a += '<thead><tr>'; 
				$a += '<th style="width:50px !important;text-align:center">No</th>';
				$a += '<th style="text-align: center">ID</th>'; 
				$a += '<th style="text-align: center">Name</th>'; 
				$a += '<th style="text-align: center">Company</th>'; 
				$a += '<th style="text-align: center">Designation</th>'; 
				$a += '<th style="text-align: center">From</th>'; 
				$a += '<th style="text-align: center">To</th>'; 
				$a += '</tr></thead>';
				$a += '</table>';
				$a += '</div>';
				$("#report_table").append($a);
                //console.log(person_profile);
                if(person_profile != null)
                {
                	if(person_profile.length > 0)
                	{
    					for(var i = 0; i < person_profile.length; i++)
    				    {
    				        $b=""; 
    				        $b += '<tr class="person_profile_for_each_company">';
    				        $b += '<td style="text-align: right">'+(i+1)+'</td>';
    				        $b += '<td>'+((person_profile[i]["identification_no"] != null)?person_profile[i]["identification_no"] : person_profile[i]["register_no"])+'</td>';
    				        $b += '<td>'+((person_profile[i]["name"] != null)?person_profile[i]["name"] : person_profile[i]["company_name"])+'</td>';
    				        $b += '<td>'+person_profile[i]["client_company_name"]+'</td>';
    				        $b += '<td>'+person_profile[i]["position_name"]+'</td>';
    				        $b += '<td style="text-align:center">'+person_profile[i]["date_of_appointment"]+'</td>';
    				        $b += '<td style="text-align:center">'+person_profile[i]["date_of_cessation"]+'</td>';
    				        $b += '</tr>';

    				        $(".person_profile_table").append($b);

    				    }
    				    $('#report_table_person_profile').DataTable({"paging": false,});
    				    $('#person_profile .datatables-header').hide();
    				    $('#person_profile .datatables-footer').hide();

                	}
                    else
                    {
                        $z=""; 
                        $z += '<tr class="person_profile_for_each_company">';
                        $z += '<td colspan="7" style="text-align:center;"><span style="font-weight:bold; font-size:20px;">N/A</span></td>';
                        $z += '</tr>';

                        $(".person_profile_table").append($z);
                    }
                }
            	else
            	{
            		$z=""; 
			        $z += '<tr class="person_profile_for_each_company">';
			        $z += '<td colspan="7" style="text-align:center;"><span style="font-weight:bold; font-size:20px;">N/A</span></td>';
			        $z += '</tr>';

			        $(".person_profile_table").append($z);
            	}
            }

            if (response.register === "client_list" || response.register === "all") 
            {
            	if(response.register === "all")
            	{
            		if(response[1] != undefined)
            		{
            			var client_list = response[1]["client_list"];
            		}
            		else
            		{
            			var client_list = '';
            		}
            	}
            	else
            	{
            		if(response[0] != undefined)
            		{
            			var client_list = response[0]["client_list"];
            		}
            		else
            		{
            			var client_list = '';
            		}
            	}

            	$a = "";
            	$a += '<div id="client_list" style="margin-top:20px;">';
            	$a += '<h3>Client List - '+ $("#report_form .service_category option:selected").text()+'</h3>';
            	//$a += '<table class="table table-bordered table-striped mb-none" id="datatable-default register_officer_table">';
				$a += '<table class="table table-bordered table-striped mb-none client_list_table" id="report_table_client_list">';
				$a += '<thead><tr>'; 
				$a += '<th style="width:50px !important;text-align:center">No</th>';
                $a += '<th style="text-align: center">Client Code</th>'; 
				$a += '<th style="text-align: center">Company Name</th>'; 
                $a += '<th style="text-align: center">Firm Name</th>'; 
				$a += '<th style="text-align: center">Year End</th>'; 
				$a += '</tr></thead>';
				$a += '</table>';
				$a += '</div>';
				$("#report_table").append($a);

            	if(client_list.length > 0)
            	{
					for(var i = 0; i < client_list.length; i++)
				    {
				        $b=""; 
				        $b += '<tr class="client_list_for_each_company">';
				        $b += '<td style="text-align: right"></td>';
                        $b += '<td>'+client_list[i]["client_code"]+'</td>';
                        $b += '<td>'+client_list[i]["company_name"]+'</td>';
                        if(client_list[i]["name"] != undefined)
                        {
                            if(client_list[i]["branch_name"] != "")
                            {
    				            $b += '<td>'+client_list[i]["name"]+' ('+client_list[i]["branch_name"]+')</td>';
                            }
                            else
                            {
                                $b += '<td>'+client_list[i]["name"]+'</td>';
                            }
                        }
                        else
                        {
                            $b += '<td></td>';
                        }
                        if(client_list[i]["year_end"] != undefined)
                        {
                             $b += '<td style="text-align:center"><span style="display:none">'+ formatDateFunc(new Date(client_list[i]["year_end"])) + '</span>' + client_list[i]["year_end"]+'</td>';
                        }
				        else
                        {
                             $b += '<td style="text-align:center"></td>';
                        }
				        $b += '</tr>';

				        $(".client_list_table").append($b);

				    }
				    

				    //$('#client_list .datatables-header').hide();
                    var t = $('#report_table_client_list').DataTable({
                        "columnDefs": [ {
                            "searchable": false,
                            "orderable": false,
                            'type': 'num', 
                            "targets": 0
                        }],
                        "paging": false
                    });

                    t.on( 'order.dt search.dt', function () {
                        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                            cell.innerHTML = i+1;
                        } );
                    } ).draw();

				    $('#client_list .datatables-footer').hide();

            	}
            	else
            	{
            		$z=""; 
			        $z += '<tr class="client_list_for_each_company">';
			        $z += '<td colspan="5" style="text-align:center;"><span style="font-weight:bold; font-size:20px;">N/A</span></td>';
			        $z += '</tr>';

			        $(".client_list_table").append($z);
            	}
            }

            if (response.register === "due_date" || response.register === "all") 
            {
            	if(response.register === "all")
            	{
            		if(response[2] != undefined)
            		{
            			var due_date = response[2]["due_date"];
            		}
            		else
            		{
            			var due_date = '';
            		}
            	}
            	else
            	{
            		if(response[0] != undefined)
            		{
            			var due_date = response[0]["due_date"];
            		}
            		else
            		{
            			var due_date = '';
            		}
            	}

            	$a = "";
            	$a += '<div id="due_date" style="margin-top:20px;">';
            	$a += '<h3>Due Date</h3>';
            	//$a += '<table class="table table-bordered table-striped mb-none" id="datatable-default register_officer_table">';
				$a += '<table class="table table-bordered table-striped mb-none due_date_table" id="report_table_due_date">';
				$a += '<thead><tr>'; 
				$a += '<th style="width:50px !important;text-align:center">No</th>';
				$a += '<th style="text-align: center">Company Name</th>'; 

                if($(".type_of_due_date").val() == "year_end" && $(".type_of_due_date").val() != "0")
                {
				    $a += '<th style="text-align: center">Year End</th>'; 
                }
                else if($(".type_of_due_date").val() == "due_date_agm" && $(".type_of_due_date").val() != "0")
                {
                    $a += '<th style="text-align: center">Due Date AGM (S.175)</th>'; 
                }
                else if($(".type_of_due_date").val() == "due_date_new_agm" && $(".type_of_due_date").val() != "0")
                {
                    $a += '<th style="text-align: center">Due Date New AGM (S.175)</th>'; 
                }
                else if($(".type_of_due_date").val() == "due_date_ar" && $(".type_of_due_date").val() != "0")
                {
                    $a += '<th style="text-align: center">Due Date AR (S.197)</th>'; 
                }
				//$a += '<th style="text-align: center">Due for Filing in (days)</th>'; 
				$a += '</tr></thead>';
				$a += '</table>';
				$a += '</div>';
				$("#report_table").append($a);

            	if(due_date.length > 0 && $(".type_of_due_date").val() != "0")
            	{
					for(var i = 0; i < due_date.length; i++)
				    {
                        if($(".type_of_due_date").val() == "year_end" && $(".type_of_due_date").val() != "0")
                        {
                                if(due_date[i]["year_end"] != undefined && due_date[i]["year_end"] != null)
                                {
                                    var dt = new Date(Date.parse(due_date[i]["year_end"]));

                                    var new_format_date = dt.getFullYear() + '' + ("0" + (dt.getMonth() + 1)).slice(-2) + '' + dt.getDate();

                                    $b=""; 
                                    $b += '<tr class="due_date_for_each_company">';
                                    $b += '<td style="text-align: right"></td>';
                                    $b += '<td>'+due_date[i]["company_name"]+'</td>';
                                    $b += '<td style="text-align:center"><span style="display:none">'+new_format_date+'</span>'+due_date[i]["year_end"]+'</td>';
                                    $b += '</tr>';

                                    $(".due_date_table").append($b);
                                }
                                // else
                                // {
                                //     $b += '<td style="text-align:center"></td>';
                                // }
                        }
                        else if($(".type_of_due_date").val() == "due_date_agm" && $(".type_of_due_date").val() != "0")
                        {
                            if(due_date[i]["175_extended_to"] != undefined && due_date[i]["175_extended_to"] != null && due_date[i]["175_extended_to"] != "" && due_date[i]["due_date_175"] != undefined && due_date[i]["due_date_175"] != null && due_date[i]["due_date_175"] != "")
                            {
                                $b=""; 
                                $b += '<tr class="due_date_for_each_company">';
                                $b += '<td style="text-align: right"></td>';
                                $b += '<td>'+due_date[i]["company_name"]+'</td>';
                                $b += '<td style="text-align:center"><span style="display:none">'+((due_date[i]["175_extended_to"] != 0)?new Date(Date.parse(due_date[i]["175_extended_to"])).getFullYear() + '' + ("0" + (new Date(Date.parse(due_date[i]["175_extended_to"])).getMonth() + 1)).slice(-2) + '' + new Date(Date.parse(due_date[i]["175_extended_to"])).getDate() : new Date(Date.parse(due_date[i]["due_date_175"])).getFullYear() + '' + ("0" + (new Date(Date.parse(due_date[i]["due_date_175"])).getMonth() + 1)).slice(-2) + '' + new Date(Date.parse(due_date[i]["due_date_175"])).getDate())+'</span>'+((due_date[i]["175_extended_to"] != 0)?due_date[i]["175_extended_to"] : due_date[i]["due_date_175"])+'</td>';
                                $b += '</tr>';

                                $(".due_date_table").append($b);
                            }
                        }
                        else if($(".type_of_due_date").val() == "due_date_new_agm" && $(".type_of_due_date").val() != "0")
                        {
                            if(due_date[i]["201_extended_to"] != undefined && due_date[i]["201_extended_to"] != null && due_date[i]["201_extended_to"] != "" && due_date[i]["due_date_201"] != undefined && due_date[i]["due_date_201"] != null && due_date[i]["due_date_201"] != "")
                            {
                                $b=""; 
                                $b += '<tr class="due_date_for_each_company">';
                                $b += '<td style="text-align: right"></td>';
                                $b += '<td>'+due_date[i]["company_name"]+'</td>';
                                // $b += '<td style="text-align: center">'+((due_date[i]["201_extended_to"] != 0)?due_date[i]["201_extended_to"] : due_date[i]["due_date_201"])+'<span style="display:none">'+((due_date[i]["201_extended_to"] != 0)?(new Date(Date.parse(due_date[i]["201_extended_to"])).getFullYear() + (new Date(Date.parse(due_date[i]["201_extended_to"])).getMonth() + 1) + new Date(Date.parse(due_date[i]["201_extended_to"])).getDate()) : (new Date(Date.parse(due_date[i]["due_date_201"])).getFullYear() + (new Date(Date.parse(due_date[i]["due_date_201"])).getMonth() + 1) + new Date(Date.parse(due_date[i]["due_date_201"])).getDate()))+'</span></td>'; 
                                //$b += '<td style="text-align: center">'+((due_date[i]["201_extended_to"] != 0)?due_date[i]["201_extended_to"] : due_date[i]["due_date_201"])+'</td>'; 
                                $b += '<td style="text-align:center"><span style="display:none">'+((due_date[i]["201_extended_to"] != 0)?new Date(Date.parse(due_date[i]["201_extended_to"])).getFullYear() + '' + ("0" + (new Date(Date.parse(due_date[i]["201_extended_to"])).getMonth() + 1)).slice(-2) + '' + new Date(Date.parse(due_date[i]["201_extended_to"])).getDate() : new Date(Date.parse(due_date[i]["due_date_201"])).getFullYear() + '' + ("0" + (new Date(Date.parse(due_date[i]["due_date_201"])).getMonth() + 1)).slice(-2) + '' + new Date(Date.parse(due_date[i]["due_date_201"])).getDate())+'</span>'+((due_date[i]["201_extended_to"] != 0)?due_date[i]["201_extended_to"] : due_date[i]["due_date_201"])+'</td>';
                                $b += '</tr>';

                                $(".due_date_table").append($b);
                            }
                        }
                        else if($(".type_of_due_date").val() == "due_date_ar" && $(".type_of_due_date").val() != "0")
                        {
                            if(due_date[i]["197_extended_to"] != undefined && due_date[i]["197_extended_to"] != null && due_date[i]["197_extended_to"] != "" && due_date[i]["due_date_197"] != undefined && due_date[i]["due_date_197"] != null && due_date[i]["due_date_197"] != "")
                            {
                                $b=""; 
                                $b += '<tr class="due_date_for_each_company">';
                                $b += '<td style="text-align: right"></td>';
                                $b += '<td>'+due_date[i]["company_name"]+'</td>';
                                //$b += '<td style="text-align: center">'+((due_date[i]["197_extended_to"] != 0)?due_date[i]["197_extended_to"] : due_date[i]["due_date_197"])+'<span style="display:none">'+((due_date[i]["197_extended_to"] != 0)?(new Date(Date.parse(due_date[i]["197_extended_to"])).getFullYear() + (new Date(Date.parse(due_date[i]["197_extended_to"])).getMonth() + 1) + new Date(Date.parse(due_date[i]["197_extended_to"])).getDate()) : (new Date(Date.parse(due_date[i]["due_date_197"])).getFullYear() + (new Date(Date.parse(due_date[i]["due_date_197"])).getMonth() + 1) + new Date(Date.parse(due_date[i]["due_date_197"])).getDate()))+'</span></td>'; 
                                //$b += '<td style="text-align: center">'+((due_date[i]["197_extended_to"] != 0)?due_date[i]["197_extended_to"] : due_date[i]["due_date_197"])+'</td>';
                                $b += '<td style="text-align:center"><span style="display:none">'+((due_date[i]["197_extended_to"] != 0)?new Date(Date.parse(due_date[i]["197_extended_to"])).getFullYear() + '' + ("0" + (new Date(Date.parse(due_date[i]["197_extended_to"])).getMonth() + 1)).slice(-2) + '' + new Date(Date.parse(due_date[i]["197_extended_to"])).getDate() : new Date(Date.parse(due_date[i]["due_date_197"])).getFullYear() + '' + ("0" + (new Date(Date.parse(due_date[i]["due_date_197"])).getMonth() + 1)).slice(-2) + '' + new Date(Date.parse(due_date[i]["due_date_197"])).getDate())+'</span>'+((due_date[i]["197_extended_to"] != 0)?due_date[i]["197_extended_to"] : due_date[i]["due_date_197"])+'</td>';
                                $b += '</tr>';

                                $(".due_date_table").append($b);
                            }
                        }
				    }

                    var t = $('#report_table_due_date').DataTable({
                        "columnDefs": [ {
                            "searchable": false,
                            "orderable": false,
                            'type': 'num', 
                            "targets": 0
                        }, { type: 'date-eu', targets: 2 }],
                        "order": [[2, 'desc']],
                        "paging": false
                    });

                    t.on( 'order.dt search.dt', function () {
                        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                            cell.innerHTML = i+1;
                        } );
                    } ).draw();

                    //$('#report_table_due_date').DataTable({"paging": false,});
                    $('#due_date .datatables-header').hide();
                    $('#due_date .datatables-footer').hide();
            	}
            	else
            	{
            		$z=""; 
			        $z += '<tr class="due_date_for_each_company">';
			        $z += '<td colspan="4" style="text-align:center;"><span style="font-weight:bold; font-size:20px;">N/A</span></td>';
			        $z += '</tr>';

			        $(".due_date_table").append($z);
            	}
            }

            if (response.register === "list_of_invoice" || response.register === "all") 
            {
            	if(response.register === "all")
            	{
            		if(response[3] != undefined)
            		{
            			var list_of_invoice = response[3]["list_of_invoice"];
            		}
            		else
            		{
            			var list_of_invoice = '';
            		}
            	}
            	else
            	{
            		if(response[0] != undefined)
            		{    //console.log(response[0]["list_of_invoice"]);
            			var list_of_invoice = response[0]["list_of_invoice"];
            		}
            		else
            		{
            			var list_of_invoice = '';
            		}
            	}
                //console.log(response[0]["list_of_invoice"]);
            	$a = "";
            	$a += '<div id="list_of_invoice" style="margin-top:20px; width:100%">';
            	$a += '<h3>List of Invoice</h3>';
            	//$a += '<table class="table table-bordered table-striped mb-none" id="datatable-default register_officer_table">';
				$a += '<table style="width:100%;table-layout:fixed" class="table table-bordered table-striped mb-none list_of_invoice_table display nowrap" id="report_table_list_of_invoice">';
				$a += '<thead><tr>'; 
				$a += '<th style="text-align: center; vertical-align: bottom !important;">No</th>';
				$a += '<th style="text-align: center; width: 7%; vertical-align: bottom !important; word-wrap:break-word">Invoice Date</th>'; 
				$a += '<th style="text-align: center; width: 7%; vertical-align: bottom !important; word-wrap:break-word">Invoice No.</th>'; 
				$a += '<th style="text-align: center; width: 8%; vertical-align: bottom !important; word-wrap:break-word">Company Name</th>'; 
				$a += '<th style="text-align: center; width: 5%; vertical-align: bottom !important; word-wrap:break-word">CCY</th>'; 
                $a += '<th style="text-align: center; vertical-align: bottom !important; word-wrap:break-word">Audit</th>';
                $a += '<th style="text-align: center; vertical-align: bottom !important; word-wrap:break-word">Account</th>';
                $a += '<th style="text-align: center; vertical-align: bottom !important; word-wrap:break-word">Tax</th>';
                $a += '<th style="text-align: center; vertical-align: bottom !important; word-wrap:break-word">Secretary</th>';
                $a += '<th style="text-align: center; vertical-align: bottom !important; word-wrap:break-word">Admin</th>';
                $a += '<th style="text-align: center; vertical-align: bottom !important; word-wrap:break-word">Human Resource</th>';
                $a += '<th style="text-align: center; vertical-align: bottom !important; word-wrap:break-word">Office Address</th>';
                $a += '<th style="text-align: center; vertical-align: bottom !important; word-wrap:break-word">Others</th>';
                $a += '<th style="text-align: center; vertical-align: bottom !important; word-wrap:break-word">I.T</th>';
				$a += '<th style="text-align: center; vertical-align: bottom !important; word-wrap:break-word">Other Assurance</th>';
                $a += '<th style="text-align: center; vertical-align: bottom !important; word-wrap:break-word">Amount</th>'; 
				$a += '<th style="text-align: center; vertical-align: bottom !important; word-wrap:break-word">Due</th>'; 
                $a += '<th style="text-align: center; vertical-align: bottom !important; word-wrap:break-word" class="needHide">Period Year End</th>'; 
				$a += '<th style="text-align: center; vertical-align: bottom !important; word-wrap:break-word" class="needHide">Firm</th>'; 
                $a += '<th style="text-align: center; vertical-align: bottom !important; word-wrap:break-word" class="needHide">Incorporation</th>'; 
                $a += '<th style="text-align: center; vertical-align: bottom !important; word-wrap:break-word" class="needHide">Discount</th>'; 
                $a += '<th style="text-align: center; vertical-align: bottom !important; word-wrap:break-word" class="needHide">Training</th>'; 
                $a += '<th style="text-align: center; vertical-align: bottom !important; word-wrap:break-word" class="needHide">Compilation</th>'; 
                $a += '<th style="text-align: center; vertical-align: bottom !important; word-wrap:break-word" class="needHide">Service Name</th>'; 
                $a += '</tr></thead>';
				$a += '</table>';
				$a += '</div>';
				$("#report_table").append($a);
                
            	if(list_of_invoice.length > 0)
            	{  
					for(var i = 0; i < list_of_invoice.length; i++)
				    {   
                        //console.log(list_of_invoice[i]["invoice_date"]);
                        var latest_date_format = changeDateFormat(list_of_invoice[i]["invoice_date"]);
				        $b=""; 
				        $b += '<tr class="list_of_invoice_for_each_company">';
				        $b += '<td style="text-align: right">'+(i+1)+'</td>';
				        $b += '<td style="text-align:center; width: 8%; word-wrap:break-word"><span style="display: none">'+ formatDateFunc(new Date(latest_date_format)) + '</span>'+list_of_invoice[i]["invoice_date"]+'</td>';
				        $b += '<td style="width: 8%; word-wrap:break-word">'+list_of_invoice[i]["invoice_no"]+'</td>';
				        $b += '<td style="width: 11%; word-wrap:break-word">'+list_of_invoice[i]["company_name"]+'</td>';
				        $b += '<td style="text-align:center; width: 50px; word-wrap:break-word">'+list_of_invoice[i]["currency_name"]+'</td>';
				        for(var m = 0; m < list_of_invoice[i]["category"].length; m++)
                        {   
                            $b += '<td style="text-align:right; word-wrap:break-word">'+addCommas(list_of_invoice[i]["category"][m])+'</td>';
                        }

                        $b += '<td style="text-align:right; word-wrap:break-word">'+addCommas(list_of_invoice[i]["amount"])+'</td>';
				        $b += '<td style="text-align:right; word-wrap:break-word">'+addCommas(list_of_invoice[i]["outstanding"])+'</td>';
                        var period_end_date = "";
				        for(var m = 0; m < list_of_invoice[i]["period_end_date"].length; m++)
                        {   
                            if(list_of_invoice[i]["period_end_date"][m] != "" && list_of_invoice[i]["period_end_date"][m] != null)
                            {
                                period_end_date = list_of_invoice[i]["period_end_date"][m];
                                break;
                            }
                        }
                        if(period_end_date != "")
                        {
                            var latest_period_end_date_format = changeDateFormat(period_end_date);
                            var sortPeriodEndDateFormat = formatDateFunc(new Date(latest_period_end_date_format));
                        }
                        else
                        {
                            var sortPeriodEndDateFormat = "";
                        }
                        $b += '<td style="text-align:right; word-wrap:break-word;"><span style="display: none">'+ sortPeriodEndDateFormat + '</span>'+period_end_date+'</td>';
                        if(list_of_invoice[i]["firm_name"] != undefined)
                        {
                            if(list_of_invoice[i]["branch_name"] != "")
                            {
                                $b += '<td style="word-wrap:break-word;">'+list_of_invoice[i]["firm_name"]+' ('+list_of_invoice[i]["branch_name"]+')</td>';
                            }
                            else
                            {
                                $b += '<td style="word-wrap:break-word;">'+list_of_invoice[i]["firm_name"]+'</td>';
                            }
                        }
                        $b += '<td style="text-align:right; word-wrap:break-word">'+addCommas(list_of_invoice[i]["incorp_amount"])+'</td>';
                        $b += '<td style="text-align:right; word-wrap:break-word">'+addCommas(list_of_invoice[i]["discount_amount"])+'</td>';
                        $b += '<td style="text-align:right; word-wrap:break-word">'+addCommas(list_of_invoice[i]["training_amount"])+'</td>';
                        $b += '<td style="text-align:right; word-wrap:break-word">'+addCommas(list_of_invoice[i]["compilation_amount"])+'</td>';
                        var our_service_name = "";
                        for(var m = 0; m < list_of_invoice[i]["service_name"].length; m++)
                        {   
                            if(m == 0)
                            {
                                our_service_name += list_of_invoice[i]["service_name"][m];
                            }
                            else
                            {
                                our_service_name += ", " + list_of_invoice[i]["service_name"][m];
                            }
                        }
                        $b += '<td>'+our_service_name+'</td>';
                        $b += '</tr>';

				        $(".list_of_invoice_table").append($b);
				    }
				    //$('#report_table_list_of_invoice').DataTable({"paging": false});

                    var t = $('#report_table_list_of_invoice').DataTable({
                        dom: "<'row'<'col-sm-5'B><'col-sm-7'f>>" + "<'row'<'col-sm-12't>>" +
            "<'row'<'col-sm-5'><'col-sm-7'p>>",
                        buttons: [
                            {
                                extend: 'excel',
                                exportOptions: {
                                    columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22,23],
                                    format: {
                                         body: function (data, row, column, node) {
                                           //console.log(row);
                                            if(column === 0){
                                                data = row + 1;

                                            }

                                            if(column === 1 || column === 17){
                                                data = data.replace(/<span(.+?)<\/span>/s, "");
                                            }

                                            if(column === 3){
                                                data = data.replace(/&amp;/g, "&");

                                            }

                                            return column === 2 ?
                                                  data.replace(/<.*?>/ig, ""): data;
                                       }
                                    }
                                }
                            },
                          ],
                        "columnDefs": [ {
                            "searchable": false,
                            "orderable": false,
                            'type': 'num', 
                            "targets": 0
                        },
                        {
                            "targets": [ 17, 18, 19, 20, 21, 22,23 ],
                            "visible": false
                        }],
                        "paging": false
                    });

                    t.on( 'order.dt search.dt', function () {
                        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                            cell.innerHTML = i+1;
                        } );
                    } ).draw();

                    $('.buttons-excel').each(function() {
                       $(this).removeClass('dt-button').addClass('btn btn-primary')
                    });

				    //$('#list_of_invoice .datatables-header').hide();
				    $('#list_of_invoice .datatables-footer').hide();

            	}
            	else
            	{
            		$z=""; 
			        $z += '<tr class="list_of_invoice_for_each_company">';
			        $z += '<td colspan="17" style="text-align:center;"><span style="font-weight:bold; font-size:20px;">N/A</span></td>';
			        $z += '</tr>';

                    // $(".needHide").attr("display", "none");
                    $('.needHide').attr("style", "display: none !important");
			        $(".list_of_invoice_table").append($z);
            	}
            }

            if (response.register === "list_of_credit_note" || response.register === "all") 
            {
                if(response[0] != undefined)
                {    
                    var list_of_credit_note = response[0]["list_of_credit_note"];
                }
                else
                {
                    var list_of_credit_note = '';
                }
                $a = "";
                $a += '<div id="list_of_credit_note" style="margin-top:20px;">';
                $a += '<h3>List of Credit Note</h3>';
                //$a += '<table class="table table-bordered table-striped mb-none" id="datatable-default register_officer_table">';
                $a += '<table style="width: 100%;" class="table table-bordered table-striped mb-none list_of_credit_note_table display nowrap" id="report_table_list_of_credit_note">';
                $a += '<thead><tr>'; 
                $a += '<th style="width:50px !important;text-align:center">No</th>';
                $a += '<th style="text-align: center">Company Name</th>'; 
                $a += '<th style="text-align: center">Credit Note Date</th>'; 
                $a += '<th style="text-align: center">Credit Note No</th>'; 
                $a += '<th style="text-align: center">Invoice No</th>'; 
                $a += '<th style="text-align: center">Currency</th>';
                $a += '<th style="text-align: center">Amount</th>'; 
                $a += '</tr></thead>';
                $a += '</table>';
                $a += '</div>';
                $("#report_table").append($a);

                if(list_of_credit_note.length > 0)
                {  
                    for(var i = 0; i < list_of_credit_note.length; i++)
                    {   
                        var latest_date_format = changeDateFormat(list_of_credit_note[i]["credit_note_date"]);
                        $b=""; 
                        $b += '<tr class="list_of_credit_note_for_each_company">';
                        $b += '<td style="text-align: right">'+(i+1)+'</td>';
                        $b += '<td>'+list_of_credit_note[i]["company_name"]+'</td>';
                        $b += '<td style="text-align:center"><span style="display: none">'+ formatDateFunc(new Date(latest_date_format)) + '</span>'+list_of_credit_note[i]["credit_note_date"]+'</td>';
                        $b += '<td>'+list_of_credit_note[i]["credit_note_no"]+'</td>';
                        $b += '<td>'+list_of_credit_note[i]["invoice_no"]+'</td>';
                        $b += '<td style="text-align:center">'+list_of_credit_note[i]["currency_name"]+'</td>';
                        $b += '<td style="text-align:right">'+addCommas(list_of_credit_note[i]["received"])+'</td>';
                        $b += '</tr>';

                        $(".list_of_credit_note_table").append($b);
                    }
                    //$('#report_table_list_of_credit_note').DataTable({"paging": false});

                    var t = $('#report_table_list_of_credit_note').DataTable({
                        dom: "<'row'<'col-sm-5'B><'col-sm-7'f>>" + "<'row'<'col-sm-12't>>" +
            "<'row'<'col-sm-5'><'col-sm-7'p>>",
                        buttons: [
                            {
                                extend: 'excel',
                                filename: 'List of Credit Note',
                                title: '',
                                exportOptions: {
                                    columns: [ 0, 1, 2, 3, 4, 5, 6],
                                    format: {
                                         body: function (data, row, column, node) {
                                           //console.log(row);
                                            if(column === 0){
                                                data = row + 1;

                                            }

                                            if(column === 2){
                                                data = data.replace(/<span(.+?)<\/span>/s, "");
                                            }

                                            if(column === 1){
                                                data = data.replace(/&amp;/g, "&");

                                            }

                                            return (column === 3 || column === 4)?
                                                  data.replace(/<.*?>/ig, ""): data;
                                       }
                                    }
                                }
                            },
                          ],
                          "bInfo": false,
                        fixedHeader: true,
                        "iDisplayLength": 100,
                        "columnDefs": [ {
                            "searchable": false,
                            "orderable": false,
                            'type': 'num', 
                            "targets": 0
                        }],
                        "paging": false
                    });

                    t.on( 'order.dt search.dt', function () {
                        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                            cell.innerHTML = i+1;
                        } );
                    } ).draw();

                    $('.buttons-excel').each(function() {
                       $(this).removeClass('dt-button').addClass('btn btn-primary')
                    });

                    //$('#list_of_credit_note .datatables-header').hide();
                    $('#list_of_credit_note .datatables-footer').hide();

                }
                else
                {
                    $z=""; 
                    $z += '<tr class="list_of_credit_note_for_each_company">';
                    $z += '<td colspan="7" style="text-align:center;"><span style="font-weight:bold; font-size:20px;">N/A</span></td>';
                    $z += '</tr>';

                    $(".list_of_credit_note_table").append($z);
                }
            }

            if (response.register === "invoice_period" || response.register === "all") 
            {
                if(response[0]["invoice_period"])
                {
                    if(response[0]["invoice_period"].length > 0)
                    {
                        var current_registration_no = "", current_service_type = 0, row_id = 0, table_id = 0;

                        for(var i = 0; i < response[0]["invoice_period"].length; i++)
                        {
                            if(response[0]["invoice_period"][i]["registration_no"] == current_registration_no)
                            {
                                row_id++;

                                if(response[0]["invoice_period"][i]["period_start_date"] != "" && response[0]["invoice_period"][i]["period_end_date"] != "")
                                {
                                    var a = '';
                                    a += '<tr class="invoice_period_for_each_company">';
                                    a += '<td style="text-align: right; width:50px !important;">'+row_id+'</td>';
                                    a += '<td style="width:30% !important;">'+response[0]["invoice_period"][i]["service_name"]+'</td>';
                                    a += '<td style="text-align:center;">'+response[0]["invoice_period"][i]["period_start_date"] + ' - '+ response[0]["invoice_period"][i]["period_end_date"] +'</td>';
                                    a += '<td style="width:200px !important;">'+response[0]["invoice_period"][i]["invoice_no"]+'</td>';
                                    a += '<td style="text-align:center; width:70px !important">'+((response[0]["invoice_period"][i]["outstanding"] != 0)?" Unpaid " : "Paid")+'</td>';
                                    a += '</tr>';

                                    $(".invoice_period_table"+table_id).append(a);
                                }
                            }
                            else
                            {
                                if(response[0]["invoice_period"][i]["period_start_date"] != "" && response[0]["invoice_period"][i]["period_end_date"] != "")
                                {
                                    current_registration_no = response[0]["invoice_period"][i]["registration_no"];
                                    current_service_type = response[0]["invoice_period"][i]["service_type"];
                                    table_id++;
                                    row_id = 1;

                                    var g = "";
                                    //$g += '<button type="button" class="register_collapsible"><span style="font-size: 2.4rem;">REGISTER OF MEMBERS AND SHARE LEDGER</span></button><div class="incorp_content"><div id="register_member_header" style="margin-top:20px; margin-bottom: 20px;">';
                                    //$g += '<h4>REGISTER OF MEMBERS AND SHARE LEDGER</h4>';
                                    g += '<div class="invoice_period">';
                                    g += '<table class="table table-bordered mb-none">';
                                    g += '<tr><td style="font-weight: bold;"> Name of Company : '+response[0]["invoice_period"][i]["company_name"]+'';

                                    g += '</td>';
                                    g += '</tr>';
                                    g += '</table>';
                                    g += '<div style="margin-bottom:20px;">';
                                    g += '<table class="table table-bordered table-striped mb-none invoice_period_table'+table_id+'">';
                                    //$a += '<table style="border:1px solid black" class="allotment_table" id="register_filing_table">';
                                    g += '<thead><tr>'; 
                                    g += '<th style="text-align:center; width:50px !important;padding-right:2px !important;padding-left:2px !important;">No</th>';
                                    // $a += '<th style="word-break:break-all;text-align: center;width:50px !important;padding-right:2px !important;padding-left:2px !important;">ID</th>'; 
                                    // $a += '<th style="word-break:break-all;text-align: center; width:50px !important;padding-right:2px !important;padding-left:2px !important;">Name/ Address</th>'; 
                                    //$a += '<th style="text-align: center; width:10px !important;padding-right:2px !important;padding-left:2px !important;">Movement in Number of Shares</th>'; 
                                    g += '<th style="text-align: center; padding-right:2px !important;padding-left:2px !important;">Service</th>';
                                    g += '<th style="text-align: center; padding-right:2px !important;padding-left:2px !important;">Invoice Period</th>';
                                    g += '<th style="text-align: center; padding-right:2px !important;padding-left:2px !important;">Inovice No</th>';
                                    g += '<th style="text-align: center; width:70px !important; padding-right:2px !important;padding-left:2px !important;">Status</th>';
                                    g += '</tr></thead>';
                                    if(response[0]["invoice_period"][i]["period_start_date"] != "" && response[0]["invoice_period"][i]["period_end_date"] != "")
                                    {
                                        g += '<tr class="invoice_period_for_each_company">';
                                        g += '<td style="text-align: right; width:50px !important;">'+row_id+'</td>';
                                        g += '<td style="width:30% !important;">'+response[0]["invoice_period"][i]["service_name"]+'</td>';
                                        g += '<td style="text-align:center;">'+response[0]["invoice_period"][i]["period_start_date"] + ' - '+ response[0]["invoice_period"][i]["period_end_date"] +'</td>';
                                        g += '<td style="width:200px !important;">'+response[0]["invoice_period"][i]["invoice_no"]+'</td>';
                                        g += '<td style="text-align:center; width:70px;">'+((response[0]["invoice_period"][i]["outstanding"] != 0)?" Unpaid " : "Paid")+'</td>';
                                        g += '</tr>';
                                    }
                                    g += '</table>';
                                    g += '</div>';
                                    g += '</div>';
                                    //$g += '</div></div>';

                                    $("#report_table").append(g);
                                }
                            }
                        }
                    }
                }
                else
                {
                    toastr.error("No Data.", "Error");
                }
            }

            if (response.register === "payment" || response.register === "all") 
            {
                $a = "";
                $a += '<div id="payment_list" style="margin-top:20px;">';
                $a += '<h3>Payment List</h3>';
                //$a += '<table class="table table-bordered table-striped mb-none" id="datatable-default register_officer_table">';
                $a += '<table class="table table-bordered table-striped mb-none payment_list_table" id="report_table_payment_list">';
                $a += '<thead><tr>'; 
                $a += '<th style="width:50px !important;text-align:center">No</th>';
                $a += '<th style="text-align: center">Firm Name</th>';
                $a += '<th style="text-align: center">Payment No</th>'; 
                //$a += '<th style="text-align: center">Client Code</th>'; 
                $a += '<th style="text-align: center">Name</th>'; 
                $a += '<th style="text-align: center">Currency</th>'; 
                $a += '<th style="text-align: center">Amount</th>'; 
                $a += '<th style="text-align: center">Payment Date</th>'; 
                $a += '<th style="text-align: center">Status</th>'; 
                $a += '</tr></thead>';
                $a += '</table>';
                $a += '</div>';
                $("#report_table").append($a);

                if(response[0] != null)
                {
                    var payment_list = response[0]["payment_list"];
                }
                else
                {
                    var payment_list = [];
                }

                if(payment_list.length > 0)
                {
                    for(var i = 0; i < payment_list.length; i++)
                    {
                        if($(".type_of_payment").val() == "supplier" || $(".type_of_payment").val() == "client")
                        {
                            $b=""; 
                            $b += '<tr class="payment_list_for_each_company">';
                            $b += '<td style="text-align: right"></td>';
                            if(payment_list[i]["branch_name"] != "")
                            {
                                $b += '<td>'+payment_list[i]["firm_name"]+' ('+payment_list[i]["branch_name"]+')</td>';
                            }
                            else
                            {
                                $b += '<td>'+payment_list[i]["firm_name"]+'</td>';
                            }
                            $b += '<td>'+payment_list[i]["payment_voucher_no"]+'</td>';
                            $b += '<td>'+payment_list[i]["vendor_name"]+'</td>';
                            $b += '<td>'+payment_list[i]["currency_name"]+'</td>';
                            $b += '<td style="text-align:right">'+addCommas(payment_list[i]["amount"])+'</td>';
                            $b += '<td style="text-align:center"><span style="display: none">'+ formatDateFunc(new Date(payment_list[i]["payment_voucher_date"])) + '</span>' + payment_list[i]["payment_voucher_date"]+'</td>';
                            if(payment_list[i]["status"] == 0)
                            {
                                $b += '<td>Pending</td>';
                            }
                            else if(payment_list[i]["status"] == 1)
                            {
                                $b += '<td>Deleted</td>';
                            }
                            else if(payment_list[i]["status"] == 2)
                            {
                                $b += '<td>Rejected</td>';
                            }
                            else if(payment_list[i]["status"] == 3)
                            {
                                $b += '<td>Approved - Unpaid</td>';
                            }
                            else if(payment_list[i]["status"] == 4)
                            {
                                $b += '<td>Approved & Paid</td>';
                            }
                            $b += '</tr>';

                            $(".payment_list_table").append($b);
                        }
                        else if($(".type_of_payment").val() == "claim")
                        {
                            $b=""; 
                            $b += '<tr class="payment_list_for_each_company">';
                            $b += '<td style="text-align: right"></td>';
                            if(payment_list[i]["branch_name"] != "")
                            {
                                $b += '<td>'+payment_list[i]["firm_name"]+' ('+payment_list[i]["branch_name"]+')</td>';
                            }
                            else
                            {
                                $b += '<td>'+payment_list[i]["firm_name"]+'</td>';
                            }
                            $b += '<td>'+payment_list[i]["claim_no"]+'</td>';
                            $b += '<td>'+payment_list[i]["user_name"]+'</td>';
                            $b += '<td>'+payment_list[i]["currency_name"]+'</td>';
                            $b += '<td style="text-align:right">'+addCommas(payment_list[i]["amount"])+'</td>';
                            $b += '<td style="text-align:center"><span style="display: none">'+ formatDateFunc(new Date(payment_list[i]["claim_date"])) + '</span>' + payment_list[i]["claim_date"]+'</td>';
                            if(payment_list[i]["status"] == 0)
                            {
                                $b += '<td>Pending</td>';
                            }
                            else if(payment_list[i]["status"] == 1)
                            {
                                $b += '<td>Deleted</td>';
                            }
                            else if(payment_list[i]["status"] == 2)
                            {
                                $b += '<td>Rejected</td>';
                            }
                            else if(payment_list[i]["status"] == 3)
                            {
                                $b += '<td>Approved - Unpaid</td>';
                            }
                            else if(payment_list[i]["status"] == 4)
                            {
                                $b += '<td>Approved & Paid</td>';
                            }
                            $b += '</tr>';

                            $(".payment_list_table").append($b);
                        }

                    }
                    

                    //$('#client_list .datatables-header').hide();
                    var t = $('#report_table_payment_list').DataTable({
                        "columnDefs": [ {
                            "searchable": false,
                            "orderable": false,
                            'type': 'num', 
                            "targets": 0
                        }],
                        "paging": false
                    });

                    t.on( 'order.dt search.dt', function () {
                        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                            cell.innerHTML = i+1;
                        } );
                    } ).draw();

                    $('#payment_list .datatables-footer').hide();

                }
                else
                {
                    $z=""; 
                    $z += '<tr class="payment_list_for_each_company">';
                    $z += '<td colspan="8" style="text-align:center;"><span style="font-weight:bold; font-size:20px;">N/A</span></td>';
                    $z += '</tr>';

                    $(".payment_list_table").append($z);
                }
            }

            if (response.register === "bank_transaction" || response.register === "all") 
            {
                $a = "";
                $a += '<div id="bank_transaction_list" style="margin-top:20px; width:100%">';
                $a += '<h3>Bank Transaction List</h3>';
                //$a += '<table class="table table-bordered table-striped mb-none" id="datatable-default register_officer_table">';
                $a += '<table class="table table-bordered table-striped mb-none bank_transaction_list_table" id="report_table_bank_transaction_list" style="width:100%">';
                $a += '<thead><tr>'; 
                $a += '<th style="width:50px !important;text-align:center">No</th>';
                $a += '<th style="text-align: center">Name</th>';
                $a += '<th style="text-align: center">Date</th>'; 
                //$a += '<th style="text-align: center">Client Code</th>'; 
                $a += '<th style="text-align: center">Reference No</th>'; 
                $a += '<th style="text-align: center">Type</th>'; 
                $a += '<th style="text-align: center">Description</th>'; 
                $a += '<th style="text-align: center">Currency</th>'; 
                $a += '<th style="text-align: center">Bank Account</th>'; 
                $a += '<th style="text-align: center">Amount Received</th>'; 
                $a += '<th style="text-align: center">Amount Paid</th>'; 
                $a += '</tr></thead>';
                $a += '</table>';
                $a += '</div>';
                $("#report_table").append($a);

                if(response[0]["payment_voucher_list"] != null)
                {
                    var payment_voucher_list = response[0]["payment_voucher_list"];

                    for(var i = 0; i < payment_voucher_list.length; i++)
                    {
                        var latest_date_format = changeDateFormat(payment_voucher_list[i]["payment_voucher_date"]);
                        $b=""; 
                        $b += '<tr class="bank_transaction_list_for_each_company">';
                        $b += '<td style="text-align: right"></td>';
                        $b += '<td>'+payment_voucher_list[i]["vendor_name"]+'</td>';
                        $b += '<td style="text-align:center"><span style="display: none">'+ formatDateFunc(new Date(latest_date_format)) + '</span>' + payment_voucher_list[i]["payment_voucher_date"]+'</td>';
                        $b += '<td>'+payment_voucher_list[i]["payment_voucher_no"]+'</td>';
                        $b += '<td>'+payment_voucher_list[i]["type_name"]+'</td>';
                        $b += '<td>'+payment_voucher_list[i]["payment_voucher_description"]+'</td>';
                        $b += '<td>'+payment_voucher_list[i]["currency_name"]+'</td>';
                        $b += '<td>'+payment_voucher_list[i]["banker"]+'</td>';
                        $b += '<td></td>';
                        $b += '<td style="text-align:right">'+addCommas(payment_voucher_list[i]["payment_voucher_service_amount"])+'</td>';
                        $b += '</tr>';

                        $(".bank_transaction_list_table").append($b);
                    }
                }
                else
                {
                    var payment_voucher_list = [];
                }

                if(response[0]["claim_list"] != null)
                {
                    var claim_list = response[0]["claim_list"];

                    for(var s = 0; s < claim_list.length; s++)
                    {
                        var latest_date_format = changeDateFormat(claim_list[s]["claim_date"]);
                        $b=""; 
                        $b += '<tr class="bank_transaction_list_for_each_company">';
                        $b += '<td style="text-align: right"></td>';
                        $b += '<td>'+claim_list[s]["user_name"]+'</td>';
                        $b += '<td style="text-align:center"><span style="display: none">'+ formatDateFunc(new Date(latest_date_format)) + '</span>' + claim_list[s]["claim_date"]+'</td>';
                        $b += '<td>'+claim_list[s]["claim_no"]+'</td>';
                        $b += '<td>'+claim_list[s]["type_name"]+'</td>';
                        $b += '<td>'+claim_list[s]["claim_description"]+'</td>';
                        $b += '<td>'+claim_list[s]["currency_name"]+'</td>';
                        $b += '<td>'+claim_list[s]["banker"]+'</td>';
                        $b += '<td></td>';
                        $b += '<td style="text-align:right">'+addCommas(claim_list[s]["claim_service_amount"])+'</td>';
                        $b += '</tr>';

                        $(".bank_transaction_list_table").append($b);
                    }
                }
                else
                {
                    var claim_list = [];
                }

                if(response[0]["payment_receipt_list"] != null)
                {
                    var payment_receipt_list = response[0]["payment_receipt_list"];

                    for(var s = 0; s < payment_receipt_list.length; s++)
                    {
                        var latest_date_format = changeDateFormat(payment_receipt_list[s]["receipt_date"]);
                        $b=""; 
                        $b += '<tr class="bank_transaction_list_for_each_company">';
                        $b += '<td style="text-align: right"></td>';
                        $b += '<td>'+payment_receipt_list[s]["client_name"]+'</td>';
                        $b += '<td style="text-align:center"><span style="display: none">'+ formatDateFunc(new Date(latest_date_format)) + '</span>' + payment_receipt_list[s]["receipt_date"]+'</td>';
                        $b += '<td>'+payment_receipt_list[s]["receipt_no"]+'</td>';
                        $b += '<td>'+payment_receipt_list[s]["type_name"]+'</td>';
                        $b += '<td>'+payment_receipt_list[s]["payment_receipt_description"]+'</td>';
                        $b += '<td>'+payment_receipt_list[s]["currency_name"]+'</td>';
                        $b += '<td>'+payment_receipt_list[s]["banker"]+'</td>';
                        $b += '<td style="text-align:right">'+addCommas(payment_receipt_list[s]["payment_receipt_service_amount"])+'</td>';
                        $b += '<td style="text-align:right"></td>';
                        $b += '</tr>';

                        $(".bank_transaction_list_table").append($b);
                    }
                }
                else
                {
                    var payment_receipt_list = [];
                }

                if(response[0]["receipt_list"] != null)
                {
                    var receipt_list = response[0]["receipt_list"];

                    for(var s = 0; s < receipt_list.length; s++)
                    {
                        var latest_date_format = changeDateFormat(receipt_list[s]["receipt_date"]);
                        $b=""; 
                        $b += '<tr class="bank_transaction_list_for_each_company">';
                        $b += '<td style="text-align: right"></td>';
                        $b += '<td>'+receipt_list[s]["company_name"]+'</td>';
                        $b += '<td style="text-align:center"><span style="display: none">'+ formatDateFunc(new Date(latest_date_format)) + '</span>' + receipt_list[s]["receipt_date"]+'</td>';
                        $b += '<td>'+receipt_list[s]["receipt_no"]+'</td>';
                        $b += '<td>ACCOUNT RECEIVABLES</td>';
                        $b += '<td>'+receipt_list[s]["invoice_no"]+'</td>';
                        $b += '<td>'+receipt_list[s]["currency_name"]+'</td>';
                        $b += '<td>'+receipt_list[s]["banker"]+'</td>';
                        $b += '<td style="text-align:right">'+addCommas(receipt_list[s]["received"])+'</td>';
                        $b += '<td style="text-align:right"></td>';
                        $b += '</tr>';

                        $(".bank_transaction_list_table").append($b);
                    }
                }
                else
                {
                    var receipt_list = [];
                }

                if(payment_voucher_list.length > 0 || claim_list.length > 0 || payment_receipt_list.length > 0 || receipt_list.length > 0)
                {
                    var t = $('#report_table_bank_transaction_list').DataTable({
                        dom: "<'row'<'col-sm-5'B><'col-sm-7'f>>" + "<'row'<'col-sm-12't>>" +
            "<'row'<'col-sm-5'><'col-sm-7'p>>",
                        buttons: [
                            {
                                extend: 'excel',
                                exportOptions: {
                                    columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
                                    format: {
                                         body: function (data, row, column, node) {
                                           //console.log(row);
                                            if(column === 0){
                                                data = row + 1;

                                            }

                                            if(column === 2){
                                                data = data.replace(/<span(.+?)<\/span>/s, "");

                                            }

                                            if(column === 4 || column === 1){
                                                data = data.replace(/&amp;/g, "&");

                                            }

                                            return column === 3 ?
                                                  data.replace(/<.*?>/ig, ""): data;

                                       }
                                    }
                                }
                            },
                          ],
                        "columnDefs": [ {
                            "searchable": false,
                            "orderable": false,
                            'type': 'num', 
                            "targets": 0
                        }],
                        "paging": false
                    });

                    t.on( 'order.dt search.dt', function () {
                        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                            cell.innerHTML = i+1;
                        } );
                    } ).draw();

                    $('.buttons-excel').each(function() {
                       $(this).removeClass('dt-button').addClass('btn btn-primary')
                    });

                    $('#bank_transaction_list .datatables-footer').hide();

                }
                else
                {
                    $z=""; 
                    $z += '<tr class="bank_transaction_list_for_each_company">';
                    $z += '<td colspan="10" style="text-align:center;"><span style="font-weight:bold; font-size:20px;">N/A</span></td>';
                    $z += '</tr>';

                    $(".bank_transaction_list_table").append($z);
                }
            }

            if (response.register === "sales_report" || response.register === "all") 
            {
                //console.log(response[0]["sales_report_list"]);
                $a = "";
                $a += '<div id="sales_report_list" style="margin-top:20px;">';
                $a += '<h3>Sales Report List</h3>';
                //$a += '<table class="table table-bordered table-striped mb-none" id="datatable-default register_officer_table">';
                $a += '<table class="table table-bordered table-striped mb-none sales_report_list_table" id="report_table_sales_report_list">';
                $a += '<thead><tr>'; 
                //$a += '<th style="width:50px !important;text-align:center">No</th>';
                $a += '<th style="text-align: center">Invoice Date</th>';
                $a += '<th style="text-align: center">Invoice No.</th>'; 
                //$a += '<th style="text-align: center">Client Code</th>'; 
                $a += '<th style="text-align: center">Description</th>'; 
                $a += '<th style="text-align: center">Amount</th>'; 
                $a += '<th style="text-align: center">Debit</th>'; 
                $a += '<th style="text-align: center">Credit</th>'; 
                $a += '<th style="text-align: center">Currency</th>'; 
                $a += '</tr></thead>';
                $a += '</table>';
                $a += '</div>';
                $("#report_table").append($a);

                if(response[0] != null)
                {
                    var sales_report_list = response[0]["sales_report_list"];
                }
                else
                {
                    var sales_report_list = [];
                }

                if(sales_report_list.length > 0)
                {
                    for(var i = 0; i < sales_report_list.length; i++)
                    {

                        //CASH
                        var latest_date_format = changeDateFormat(sales_report_list[i]["invoice_date"]);

                        $b=""; 
                        $b += '<tr class="sales_report_list_for_each_company">';
                        $b += '<td style="text-align:center"><span style="display: none">'+ formatDateFunc(new Date(latest_date_format)) + '</span>' + sales_report_list[i]["invoice_date"]+'</td>';
                        $b += '<td>'+sales_report_list[i]["invoice_no"]+'</td>';
                        $b += '<td> CASH - '+sales_report_list[i]["company_name"]+'</td>';
                        $b += '<td style="text-align:right">'+addCommas(sales_report_list[i]["amount"])+'</td>';
                        $b += '<td style="text-align:right">'+addCommas(sales_report_list[i]["amount"])+'</td>';
                        $b += '<td style="text-align:right"></td>';
                        $b += '<td>'+sales_report_list[i]["currency_name"]+'</td>';
                        $b += '</tr>';
                        //SALES
                        $b += '<tr class="sales_report_list_for_each_company">';
                        $b += '<td style="text-align:center"><span style="display: none">'+ formatDateFunc(new Date(latest_date_format)) + '</span>' + sales_report_list[i]["invoice_date"]+'</td>';
                        $b += '<td>'+sales_report_list[i]["invoice_no"]+'</td>';
                        $b += '<td> SALES </td>';
                        $b += '<td style="text-align:right">-'+addCommas(sales_report_list[i]["total_billing_service_amount"])+'</td>';
                        $b += '<td style="text-align:right"></td>';
                        $b += '<td style="text-align:right">'+addCommas(sales_report_list[i]["total_billing_service_amount"])+'</td>';
                        $b += '<td>'+sales_report_list[i]["currency_name"]+'</td>';
                        $b += '</tr>';
                        //GST OUTPUT TAX
                        if(sales_report_list[i]["gst_rate"] != 0)
                        {
                            var gst = 0;
                            var before_gst = ((sales_report_list[i]["gst_rate"] / 100) * parseFloat(sales_report_list[i]["total_billing_service_amount"].replace(/\,/g,''),2));
                            gst += parseFloat(before_gst.toFixed(2));

                            $b += '<tr class="sales_report_list_for_each_company">';
                            $b += '<td style="text-align:center"><span style="display: none">'+ formatDateFunc(new Date(latest_date_format)) + '</span>' + sales_report_list[i]["invoice_date"]+'</td>';
                            $b += '<td>'+sales_report_list[i]["invoice_no"]+'</td>';
                            $b += '<td> GST OUTPUT TAX </td>';
                            $b += '<td style="text-align:right">-'+addCommas(gst.toFixed(2))+'</td>';
                            $b += '<td style="text-align:right"></td>';
                            $b += '<td style="text-align:right">'+addCommas(gst.toFixed(2))+'</td>';
                            $b += '<td>'+sales_report_list[i]["currency_name"]+'</td>';
                            $b += '</tr>';
                        }

                        $(".sales_report_list_table").append($b);
                    }
                    
                    var t = $('#report_table_sales_report_list').DataTable({
                        dom: "<'row'<'col-sm-5'B><'col-sm-7'f>>" + "<'row'<'col-sm-12't>>" +
            "<'row'<'col-sm-5'><'col-sm-7'p>>",
                        buttons: [
                            {
                                text: 'Excel',
                                action: function ( e, dt, node, config ) {
                                    generate_sales_report();
                                }
                            }
                        ],
                        "paging": false
                    });

                    $('.dt-button').each(function() {
                       $(this).removeClass('dt-button').addClass('btn btn-primary')
                    });

                    $('#sales_report_list .datatables-footer').hide();

                }
                else
                {
                    $z=""; 
                    $z += '<tr class="sales_report_list_for_each_company">';
                    $z += '<td colspan="7" style="text-align:center;"><span style="font-weight:bold; font-size:20px;">N/A</span></td>';
                    $z += '</tr>';

                    $(".sales_report_list_table").append($z);
                }
            }

            if (response.register === "register_contorller" || response.register === "all") 
            {
                //console.log(response[0]["register_controller_list"]);

                $a = "";
                $a += '<div id="register_controller_list" style="margin-top:20px; width: 100%;">';
                $a += '<h3>Register Controller List</h3>';
                $a += '<div style="margin-bottom: 10px;"><span>Toggle column:  </span><select class="selectpicker" multiple>';
                $a += '<option class="toggle-vis" data-column="1">UEN</option>';
                $a += '<option class="toggle-vis" data-column="2">Category Type</option>';
                $a += '<option class="toggle-vis" data-column="3">Name</option>';
                //$a += '<option class="toggle-vis" data-column="4">Identification Type</option>';
                $a += '<option class="toggle-vis" data-column="5">ID/ Passport No.</option>';
                $a += '<option class="toggle-vis" data-column="6">Nationality</option>';
                $a += '<option class="toggle-vis" data-column="7">Date of Birth</option>';
                $a += '<option class="toggle-vis" data-column="8">Aliases (if any)</option>';
                $a += '<option class="toggle-vis" data-column="9">ACRA issued UEN (if any)</option>';
                $a += '<option class="toggle-vis" data-column="10">Non ACRA issued UEN (if any)</option>';
                $a += '<option class="toggle-vis" data-column="11">Legal form of the registrable corporate controllers</option>';
                $a += '<option class="toggle-vis" data-column="12">Jurisdiction which the registrable corporate controller is formed</option>';
                $a += '<option class="toggle-vis" data-column="13">Statute which the registrable corporate controller is formed or incorporate</option>';
                $a += '<option class="toggle-vis" data-column="14">Name of the corporate entity register of the jurisdiction in which the registrable corporate controller is formed or incorporated, (if any)</option>';
                $a += '<option class="toggle-vis" data-column="15">Address Type</option>';
                $a += '<option class="toggle-vis" data-column="16">Postal Code</option>';
                $a += '<option class="toggle-vis" data-column="17">Block</option>';
                $a += '<option class="toggle-vis" data-column="18">Level</option>';
                $a += '<option class="toggle-vis" data-column="19">Unit</option>';
                $a += '<option class="toggle-vis" data-column="20">Foreign Address Line 1</option>';
                $a += '<option class="toggle-vis" data-column="21">Foreign Address Line 2</option>';
                $a += '<option class="toggle-vis" data-column="22">Date appointed</option>';
                $a += '<option class="toggle-vis" data-column="23">Date ceased</option>';
                $a += '</select></div>';
                $a += '<div class="dataTables_scroll" style="width: 89vw;"><table class="table table-bordered table-striped mb-none register_controller_list_table" id="report_table_register_controller_list" style="width:100%;">';
                $a += '<thead><tr>'; 
                $a += '<th style="text-align:center">No</th>';
                $a += '<th>UEN</th>';
                $a += '<th>Category Type</th>'; 
                //$a += '<th>Client Code</th>'; 
                $a += '<th>Name</th>'; 
                //$a += '<th>Identification Type</th>'; 
                $a += '<th>ID/ Passport No.</th>'; 
                $a += '<th>Nationality</th>'; 
                $a += '<th>Date of Birth</th>'; 
                $a += '<th>Aliases (if any)</th>';
                $a += '<th>ACRA issued UEN (if any)</th>';
                $a += '<th>Non ACRA issued UEN (if any)</th>';
                $a += '<th>Legal form of the registrable corporate controllers</th>';
                $a += '<th>Jurisdiction which the registrable corporate controller is formed</th>';
                $a += '<th>Statute which the registrable corporate controller is formed or incorporate</th>';
                $a += '<th>Name of the corporate entity register of the jurisdiction in which the registrable corporate controller is formed or incorporated, (if any)</th>';
                $a += '<th>Address Type</th>';
                $a += '<th>Postal Code</th>';
                $a += '<th>Block</th>';
                $a += '<th>Level</th>';
                $a += '<th>Unit</th>';
                $a += '<th>Foreign Address Line 1</th>';
                $a += '<th>Foreign Address Line 2</th>';
                $a += '<th>Date appointed</th>';
                $a += '<th>Date ceased</th>';
                $a += '</tr></thead>';
                $a += '</table>';
                $a += '</div>';
                $a += '</div>';
                $("#report_table").append($a);

                if(response[0] != null)
                {
                    var register_controller_list = response[0]["register_controller_list"];
                }
                else
                {
                    var register_controller_list = [];
                }

                if(register_controller_list.length > 0)
                {
                    for(var i = 0; i < register_controller_list.length; i++)
                    {
                        //console.log(register_controller_list[i]);
                        if(register_controller_list[i]["client_controller_id"] != null)
                        {
                            var latest_date_appointed = changeDateFormat(register_controller_list[i]["date_appointed"]);
                            if(register_controller_list[i]["date_ceased"] != "")
                            {
                                var latest_date_ceased = changeDateFormat(register_controller_list[i]["date_ceased"]);
                            }
                            else
                            {
                                var latest_date_ceased = "";
                            }

                            if(register_controller_list[i]["officer_field_type"] == "individual")
                            {
                                var category_type = "I";
                                var name = register_controller_list[i]["name"];
                                // if(register_controller_list[i]["officer_identification_type"] == "NRIC (Singapore citizen)")
                                // {
                                //     var identification_type = "1";
                                // }
                                // else if(register_controller_list[i]["officer_identification_type"] == "NRIC (PR)")
                                // {
                                //     var identification_type = "2";
                                // }
                                // else if(register_controller_list[i]["officer_identification_type"] == "FIN Number")
                                // {
                                //     var identification_type = "3";
                                // }
                                // else if(register_controller_list[i]["officer_identification_type"] == "Passport/ Others")
                                // {
                                //     var identification_type = "4";
                                // }
                                var identification_no = register_controller_list[i]["identification_no"];
                                var uen = "";
                                var acra_uen = "";
                                var nation_code = register_controller_list[i]["code"];
                                var alias = register_controller_list[i]["alias"];
                                var date_of_birth = changeDateFormatWithDash(register_controller_list[i]["date_of_birth"]);
                                if(register_controller_list[i]["officer_address_type"] == "Local")
                                {
                                    var address_type = "S";
                                    var postal_code = register_controller_list[i]["officer_postal_code"];
                                    var firstChar = register_controller_list[i]["officer_street_name"].charAt(0);
                                    if( firstChar <='9' && firstChar >='0') {
                                        //do your stuff
                                        var firstWord = register_controller_list[i]["officer_street_name"].replace(/ .*/,'');
                                        var block = firstWord;
                                    }
                                    else
                                    {
                                        var block = "";
                                    }
                                    var level = register_controller_list[i]["officer_unit_no1"];
                                    var unit = register_controller_list[i]["officer_unit_no2"];
                                    var foreign_add1 = "";
                                    var foreign_add2 = "";
                                }
                                else
                                {
                                    var address_type = "F";
                                    var postal_code = "";
                                    var block = "";
                                    var level = "";
                                    var unit = "";
                                    var foreign_add1 = register_controller_list[i]["officer_foreign_address1"];
                                    var foreign_add2 = register_controller_list[i]["officer_foreign_address2"] + " " + register_controller_list[i]["officer_foreign_address3"];
                                }
                                var legal_form = "";
                                var juridiction = "";
                                var statutes_of = "";
                                var corporate_entity_name = "";
                            }
                            else
                            {
                                var category_type = "C";
                                //var identification_type = "";
                                var identification_no = "";

                                if(register_controller_list[i]["company_name"] != null)
                                {
                                    var name = register_controller_list[i]["company_name"];
                                }
                                else if(register_controller_list[i]["client_company_name"] != null)
                                {
                                    var name = register_controller_list[i]["client_company_name"];
                                }
                                
                                if(register_controller_list[i]["register_no"] != null && register_controller_list[i]["country_of_incorporation"] != "SINGAPORE") //office_company
                                {
                                    var uen = register_controller_list[i]["register_no"];
                                }
                                else if(register_controller_list[i]["entity_issued_by_registrar"] != null)
                                {
                                    var uen = register_controller_list[i]["entity_issued_by_registrar"];
                                }
                                else
                                {
                                    var uen = "";
                                }

                                if(register_controller_list[i]["register_no"] != null && register_controller_list[i]["country_of_incorporation"] == "SINGAPORE") //office_company
                                {
                                    var acra_uen = register_controller_list[i]["register_no"];
                                }
                                else if(register_controller_list[i]["registration_no"] != null) //client
                                {
                                    var acra_uen = register_controller_list[i]["registration_no"];
                                }
                                else if(register_controller_list[i]["entity_issued_by_registrar"] != null)
                                {
                                    var acra_uen = register_controller_list[i]["entity_issued_by_registrar"];
                                }

                                if(register_controller_list[i]["legal_form_entity"] != null)
                                {
                                    var legal_form = register_controller_list[i]["legal_form_entity"];
                                }
                                else if(register_controller_list[i]["client_company_type"] != null)
                                {
                                    var legal_form = register_controller_list[i]["client_company_type"];
                                }

                                if(register_controller_list[i]["company_nationality_code"] != null)
                                {
                                    var juridiction = register_controller_list[i]["company_nationality_code"];
                                }
                                else if(register_controller_list[i]["client_country_of_incorporation"] != null)
                                {
                                    var juridiction = "SG";
                                }

                                if(register_controller_list[i]["statutes_of"] != null)
                                {
                                    var statutes_of = register_controller_list[i]["statutes_of"];
                                }
                                else if(register_controller_list[i]["client_statutes_of"] != null)
                                {
                                    var statutes_of = register_controller_list[i]["client_statutes_of"];
                                }

                                if(register_controller_list[i]["coporate_entity_name"] != null)
                                {
                                    var corporate_entity_name = register_controller_list[i]["coporate_entity_name"];
                                }
                                else if(register_controller_list[i]["client_coporate_entity_name"] != null)
                                {
                                    var corporate_entity_name = register_controller_list[i]["client_coporate_entity_name"];
                                }

                                var nation_code = "";
                                var alias = "";
                                var date_of_birth = "";
                                var address_type = "";
                                var postal_code = "";
                                var block = "";
                                var level = "";
                                var unit = "";
                                var foreign_add1 = "";
                                var foreign_add2 = "";
                            }

                            $b=""; 
                            $b += '<tr class="register_controller_list_for_each_company">';
                            $b += '<td style="text-align: right"></td>';
                            $b += '<td>'+register_controller_list[i]["uen"]+'</td>';
                            $b += '<td>'+category_type+'</td>';
                            $b += '<td>'+name+'</td>';
                            //$b += '<td>'+identification_type+'</td>';
                            $b += '<td>'+identification_no+'</td>';
                            $b += '<td>'+nation_code+'</td>';
                            $b += '<td style="text-align:center"><span style="display: none">'+ formatDateFunc(new Date(register_controller_list[i]["date_of_birth"])) + '</span>' + date_of_birth+'</td>';
                            $b += '<td>'+alias+'</td>';
                            $b += '<td>'+acra_uen+'</td>';
                            $b += '<td>'+uen+'</td>';
                            $b += '<td>'+legal_form+'</td>';
                            $b += '<td>'+juridiction+'</td>';
                            $b += '<td>'+statutes_of+'</td>';
                            $b += '<td>'+corporate_entity_name+'</td>';
                            $b += '<td>'+address_type+'</td>';
                            $b += '<td>'+postal_code+'</td>';
                            $b += '<td>'+block+'</td>';
                            $b += '<td>'+level+'</td>';
                            $b += '<td>'+unit+'</td>';
                            $b += '<td>'+foreign_add1+'</td>';
                            $b += '<td>'+foreign_add2+'</td>';
                            $b += '<td style="text-align:center"><span style="display: none">'+ formatDateFunc(new Date(latest_date_appointed)) + '</span>' + register_controller_list[i]["date_appointed"] +'</td>';
                            $b += '<td style="text-align:center"><span style="display: none">'+ formatDateFunc(new Date(latest_date_ceased)) + '</span>' + register_controller_list[i]["date_ceased"] +'</td>';
                            $b += '</tr>';

                            $(".register_controller_list_table").append($b);
                        }
                    }

                    var t = $('#report_table_register_controller_list').DataTable({
                        dom: "<'row'<'col-sm-5'B><'col-sm-7'f>>" + "<'row'<'col-sm-12't>>" +
            "<'row'<'col-sm-5'><'col-sm-7'p>>",
                        buttons: [
                            {
                                text: 'Excel',
                                action: function ( e, dt, node, config ) {
                                    generate_register_of_controller();
                                }
                            }
                        ],
                        "columnDefs": [ {
                            "searchable": false,
                            "orderable": false,
                            'type': 'num', 
                            "targets": 0
                        }],
                        "paging": false,
                    });

                    $('.dt-button').each(function() {
                       $(this).removeClass('dt-button').addClass('btn btn-primary')
                    });

                    t.on( 'order.dt search.dt', function () {
                        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                            cell.innerHTML = i+1;
                        } );
                    } ).draw();

                    $('#register_controller_list .datatables-footer').hide();
                }
                else
                {
                    $z=""; 
                    $z += '<tr class="register_controller_list_for_each_company">';
                    $z += '<td colspan="18" style="text-align:center;"><span style="font-weight:bold; font-size:20px;">N/A</span></td>';
                    $z += '</tr>';

                    $(".register_controller_list_table").append($z);
                }

                $('.selectpicker').multiselect({
                    buttonWidth: '200px',
                    maxHeight: 400,
                    buttonText: function(options, select) {
                        if (options.length === 0) {
                            return 'Select the column';
                        }
                        else if (options.length > 1) {
                            return 'Multiple column selected!';
                        }
                        else {
                             var labels = [];
                             options.each(function() {
                                 if ($(this).attr('label') !== undefined) {
                                     labels.push($(this).attr('label'));
                                 }
                                 else {
                                     labels.push($(this).html());
                                 }
                             });
                             return labels.join(', ') + '';
                        }
                    },
                    onChange: function(element, checked) {
                        e.preventDefault();
                 
                        // Get the column API object
                        var column = t.column( element.data('column') );
                 
                        // Toggle the visibility
                        //Datatable Show / hide columns dynamically
                        column.visible( ! column.visible() );
                    }
                });
            }

            if (response.register === "list_of_recurring" || response.register === "all") 
            {
                if(response[0] != undefined)
                { 
                    var list_of_recurring = response[0]["list_of_recurring"];
                }
                else
                {
                    var list_of_recurring = '';
                }

                $a = "";
                $a += '<div id="list_of_recurring" style="margin-top:20px; width:100%">';
                $a += '<h3>List of Recurring</h3>';
                $a += '<table style="width:100%;table-layout:fixed" class="table table-bordered table-striped mb-none list_of_recurring_table display nowrap" id="report_table_list_of_recurring">';
                $a += '<thead><tr>'; 
                $a += '<th style="width:50px !important;text-align:center; vertical-align: bottom !important;">No</th>';
                $a += '<th style="text-align: center; vertical-align: bottom !important;">Firm Name</th>'; 
                $a += '<th style="text-align: center; vertical-align: bottom !important;">Recurring Date</th>'; 
                $a += '<th style="text-align: center; vertical-align: bottom !important;">Recurring No.</th>'; 
                $a += '<th style="text-align: center; vertical-align: bottom !important;">Company Name</th>'; 
                $a += '<th style="text-align: center; vertical-align: bottom !important;">CCY</th>'; 
                $a += '<th style="text-align: center; vertical-align: bottom !important;">Amount</th>'; 
                $a += '<th style="text-align: center; vertical-align: bottom !important;">Services</th>'; 
                $a += '<th style="text-align: center; vertical-align: bottom !important;">Recipient Name</th>'; 
                $a += '<th style="text-align: center; vertical-align: bottom !important;">Recipient Email</th>'; 
                $a += '</tr></thead>';
                $a += '</table>';
                $a += '</div>';
                $("#report_table").append($a);
                
                if(list_of_recurring.length > 0)
                {  
                    for(var i = 0; i < list_of_recurring.length; i++)
                    {   
                        var latest_date_format = changeDateFormat(list_of_recurring[i]["recu_invoice_issue_date"]);
                        $b=""; 
                        $b += '<tr class="list_of_recurring_for_each_company">';
                        $b += '<td style="text-align: right">'+(i+1)+'</td>';
                        
                        if(list_of_recurring[i]["firm_name"] != undefined)
                        {
                            if(list_of_recurring[i]["branch_name"] != "")
                            {
                                $b += '<td>'+list_of_recurring[i]["firm_name"]+' ('+list_of_recurring[i]["branch_name"]+')</td>';
                            }
                            else
                            {
                                $b += '<td style="width: 11%;">'+list_of_recurring[i]["firm_name"]+'</td>';
                            }
                        }
                        $b += '<td style="text-align:center; width: 8%;"><span style="display: none">'+ formatDateFunc(new Date(latest_date_format)) + '</span>'+list_of_recurring[i]["recu_invoice_issue_date"]+'</td>';
                        $b += '<td style="width: 8%;">'+list_of_recurring[i]["invoice_no"]+'</td>';
                        $b += '<td style="width: 11%;">'+list_of_recurring[i]["company_name"]+'</td>';
                        $b += '<td style="text-align:center; width: 50px;">'+list_of_recurring[i]["currency_name"]+'</td>';

                        $b += '<td style="text-align:right">'+addCommas(list_of_recurring[i]["amount"])+'</td>';
                        var our_service_name = "";
                        for(var m = 0; m < list_of_recurring[i]["our_service_name"].length; m++)
                        {   
                            if(m == 0)
                            {
                                our_service_name += list_of_recurring[i]["our_service_name"][m];
                            }
                            else
                            {
                                our_service_name += ", " + list_of_recurring[i]["our_service_name"][m];
                            }
                        }
                        $b += '<td>'+our_service_name+'</td>';
                        $b += '<td style="word-wrap:break-word">'+list_of_recurring[i]["contact_name"]+'</td>';
                        $b += '<td style="word-wrap:break-word">'+list_of_recurring[i]["contact_email"]+'</td>';
                        $b += '</tr>';

                        $(".list_of_recurring_table").append($b);
                    }

                    var t = $('#report_table_list_of_recurring').DataTable({
                        dom: "<'row'<'col-sm-5'B><'col-sm-7'f>>" + "<'row'<'col-sm-12't>>" +
            "<'row'<'col-sm-5'><'col-sm-7'p>>",
                        buttons: [
                            {
                                extend: 'excel',
                                exportOptions: {
                                    columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
                                    format: {
                                         body: function (data, row, column, node) {
                                           //console.log(row);
                                            if(column === 0){
                                                data = row + 1;

                                            }

                                            if(column === 2){
                                                data = data.replace(/<span(.+?)<\/span>/s, "");

                                            }

                                            if(column === 1 || column === 4){
                                                data = data.replace(/&amp;/g, "&");

                                            }

                                            return column === 4 ?
                                                  data.replace(/<.*?>/ig, ""): data;
                                       }
                                    }
                                }
                            },
                          ],
                        "columnDefs": [ {
                            "searchable": false,
                            "orderable": false,
                            'type': 'num', 
                            "targets": 0
                        }],
                        "paging": false
                    });

                    t.on( 'order.dt search.dt', function () {
                        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                            cell.innerHTML = i+1;
                        } );
                    } ).draw();

                    $('.buttons-excel').each(function() {
                       $(this).removeClass('dt-button').addClass('btn btn-primary')
                    });

                    $('#list_of_recurring .datatables-footer').hide();
                }
                else
                {
                    $z=""; 
                    $z += '<tr class="list_of_recurring_for_each_company">';
                    $z += '<td colspan="10" style="text-align:center;"><span style="font-weight:bold; font-size:20px;">N/A</span></td>';
                    $z += '</tr>';

                    $(".list_of_recurring_table").append($z);
                }
            }

            if(response.register === "list_of_receipt" || response.register === "all")
            {
                if(response[0] != undefined)
                { 
                    var list_of_receipt = response[0]["list_of_receipt"];
                }
                else
                {
                    var list_of_receipt = '';
                }
                //console.log(list_of_receipt);
                $a = "";
                $a += '<div id="list_of_receipt" style="margin-top:20px; width:100%">';
                $a += '<h3>List of Receipt</h3>';
                $a += '<table style="width:100%;table-layout:fixed" class="table table-bordered table-striped mb-none list_of_receipt_table display nowrap" id="report_table_list_of_receipt">';
                $a += '<thead><tr>'; 
                $a += '<th style="width:50px !important;text-align:center; vertical-align: bottom !important;">No</th>'; 
                $a += '<th style="text-align: center; vertical-align: bottom !important;">Company Name</th>'; 
                $a += '<th style="text-align: center; vertical-align: bottom !important;">Receipt No</th>'; 
                $a += '<th style="text-align: center; vertical-align: bottom !important;">Receipt Date</th>'; 
                $a += '<th style="text-align: center; vertical-align: bottom !important;">Receipt CCY</th>'; 
                $a += '<th style="text-align: center; vertical-align: bottom !important;">Receipt Amount</th>';
                $a += '<th style="text-align: center; vertical-align: bottom !important; word-wrap:break-word">Equivalent CCY</th>';
                $a += '<th style="text-align: center; vertical-align: bottom !important; word-wrap:break-word">Equivalent Amount</th>';
                $a += '<th style="text-align: center; vertical-align: bottom !important; word-wrap:break-word">Total Amount Received CCY</th>';
                $a += '<th style="text-align: center; vertical-align: bottom !important; word-wrap:break-word">Total Amount Received</th>';  
                $a += '<th style="text-align: center; vertical-align: bottom !important;">Out of Balance CCY</th>';
                $a += '<th style="text-align: center; vertical-align: bottom !important;">Out of Balance</th>'; 
                $a += '<th style="text-align: center; vertical-align: bottom !important; word-wrap:break-word">Reference No</th>'; 
                $a += '<th style="text-align: center; vertical-align: bottom !important;">Payment Mode</th>'; 
                $a += '<th style="text-align: center; vertical-align: bottom !important;">Invoice No</th>';
                $a += '<th style="text-align: center; vertical-align: bottom !important;">Bank Account</th>';  
                $a += '<th style="text-align: center; vertical-align: bottom !important;">Firm</th>';
                $a += '</tr></thead>';
                $a += '</table>';
                $a += '</div>';
                $("#report_table").append($a);

                if(list_of_receipt.length > 0)
                {  
                    for(var i = 0; i < list_of_receipt.length; i++)
                    {  
                        var latest_date_format = changeDateFormat(list_of_receipt[i]["receipt_date"]);
                        $b=""; 
                        $b += '<tr class="list_of_receipt_for_each_company">';
                        $b += '<td style="text-align: right">'+(i+1)+'</td>';
                        $b += '<td style="word-wrap:break-word;">'+list_of_receipt[i]["company_name"]+'</td>';
                        $b += '<td style="word-wrap:break-word;">'+list_of_receipt[i]["receipt_no"]+'</td>';
                        $b += '<td style="text-align:center; word-wrap:break-word"><span style="display: none">'+ formatDateFunc(new Date(latest_date_format)) + '</span>'+list_of_receipt[i]["receipt_date"]+'</td>';
                        $b += '<td style="word-wrap:break-word">'+list_of_receipt[i]["billing_currency_name"]+'</td>';
                        $b += '<td style="text-align:right; word-wrap:break-word">'+addCommas(list_of_receipt[i]["received"])+'</td>';
                        $b += '<td style="word-wrap:break-word">'+((list_of_receipt[i]["bank_currency_name"] != null)?list_of_receipt[i]["bank_currency_name"]:"")+'</td>';
                        $b += '<td style="text-align:right; word-wrap:break-word">'+addCommas(list_of_receipt[i]["equival_amount"])+'</td>';
                        $b += '<td style="word-wrap:break-word">'+((list_of_receipt[i]["bank_currency_name"] != null)?list_of_receipt[i]["bank_currency_name"]:"")+'</td>';
                        $b += '<td style="text-align:right; word-wrap:break-word">'+addCommas(list_of_receipt[i]["total_amount_received"])+'</td>';
                        $b += '<td style="word-wrap:break-word">'+((list_of_receipt[i]["bank_currency_name"] != null)?list_of_receipt[i]["bank_currency_name"]:"")+'</td>';
                        $b += '<td style="text-align:right; word-wrap:break-word">'+addCommas(list_of_receipt[i]["out_of_balance"])+'</td>';
                        $b += '<td style="text-align:center; word-wrap:break-word">'+list_of_receipt[i]["reference_no"]+'</td>';
                        $b += '<td style="text-align:center; word-wrap:break-word">'+list_of_receipt[i]["payment_mode"]+'</td>';
                        $b += '<td style="text-align:center; word-wrap:break-word">'+list_of_receipt[i]["invoice_no"]+'</td>';
                        if(list_of_receipt[i]["banker"] != null)
                        {
                            $b += '<td style="text-align:center; word-wrap:break-word">'+list_of_receipt[i]["banker"] + '(' + list_of_receipt[i]["bank_currency_name"] + ')' +'</td>';
                        }
                        else
                        {
                            $b += '<td style="text-align:center; word-wrap:break-word"></td>';
                        }

                        // $b += '<td style="text-align:center; width: 50px;">'+list_of_receipt[i]["currency_name"]+'</td>';
                        // for(var m = 0; m < list_of_receipt[i]["category"].length; m++)
                        // {   
                        //     $b += '<td style="text-align:right; word-wrap:break-word">'+addCommas(list_of_receipt[i]["category"][m])+'</td>';
                        // }

                        
                        // $b += '<td style="text-align:right; word-wrap:break-word">'+addCommas(list_of_receipt[i]["outstanding"])+'</td>';
                        // var period_end_date = "";
                        // for(var m = 0; m < list_of_receipt[i]["period_end_date"].length; m++)
                        // {   
                        //     if(list_of_receipt[i]["period_end_date"][m] != "")
                        //     {
                        //         period_end_date = list_of_receipt[i]["period_end_date"][m];
                        //         break;
                        //     }
                        // }
                        // if(period_end_date != "")
                        // {
                        //     var latest_period_end_date_format = changeDateFormat(period_end_date);
                        //     var sortPeriodEndDateFormat = formatDateFunc(new Date(latest_period_end_date_format));
                        // }
                        // else
                        // {
                        //     var sortPeriodEndDateFormat = "";
                        // }
                        // $b += '<td style="text-align:right; word-wrap:break-word;"><span style="display: none">'+ sortPeriodEndDateFormat + '</span>'+period_end_date+'</td>';
                        if(list_of_receipt[i]["firm_name"] != undefined)
                        {
                            if(list_of_receipt[i]["branch_name"] != "")
                            {
                                $b += '<td style="word-wrap:break-word;">'+list_of_receipt[i]["firm_name"]+' ('+list_of_receipt[i]["branch_name"]+')</td>';
                            }
                            else
                            {
                                $b += '<td style="word-wrap:break-word;">'+list_of_receipt[i]["firm_name"]+'</td>';
                            }
                        }
                        $b += '</tr>';

                        $(".list_of_receipt_table").append($b);
                    }

                    var t = $('#report_table_list_of_receipt').DataTable({
                        dom: "<'row'<'col-sm-5'B><'col-sm-7'f>>" + "<'row'<'col-sm-12't>>" +
            "<'row'<'col-sm-5'><'col-sm-7'p>>",
                        buttons: [
                            {
                                extend: 'excel',
                                exportOptions: {
                                    columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16],
                                    format: {
                                         body: function (data, row, column, node) {
                                           //console.log(row);
                                            if(column === 0){
                                                data = row + 1;

                                            }

                                            if(column === 3){
                                                data = data.replace(/<span(.+?)<\/span>/s, "");
                                            }

                                            if(column === 1){
                                                data = data.replace(/&amp;/g, "&");

                                            }

                                            return (column === 2 || column === 8) ?
                                                  data.replace(/<.*?>/ig, ""): data;
                                       }
                                    }
                                }
                            },
                          ],
                        "columnDefs": [ {
                            "searchable": false,
                            "orderable": false,
                            'type': 'num', 
                            "targets": 0
                        }
                        // ,
                        // {
                        //     "targets": [ 17, 18 ],
                        //     "visible": false
                        // }
                        ],
                        "order": [[ 1, "asc" ], [ 2, "desc" ]],
                        "paging": false
                    });

                    t.on( 'order.dt search.dt', function () {
                        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                            cell.innerHTML = i+1;
                        } );
                    } ).draw();

                    $('.buttons-excel').each(function() {
                       $(this).removeClass('dt-button').addClass('btn btn-primary')
                    });

                    $('#list_of_receipt .datatables-footer').hide();
                }
                else
                {
                    $z=""; 
                    $z += '<tr class="list_of_receipt_for_each_company">';
                    $z += '<td colspan="17" style="text-align:center;"><span style="font-weight:bold; font-size:20px;">N/A</span></td>';
                    $z += '</tr>';

                    $(".list_of_receipt_table").append($z);
                }
            }

            if (response.register === "list_of_document" || response.register === "all") 
            {
                if(response[0] != undefined)
                { 
                    var list_of_document = response[0]["list_of_document"];
                }
                else
                {
                    var list_of_document = '';
                }

                $a = "";
                $a += '<div id="list_of_document" style="margin-top:20px; width:100%">';
                $a += '<h3>List of Document</h3>';
                $a += '<table style="width:100%;table-layout:fixed" class="table table-bordered table-striped mb-none list_of_document_table display nowrap" id="report_table_list_of_document">';
                $a += '<thead><tr>'; 
                $a += '<th style="width:50px !important;text-align:center; vertical-align: bottom !important;">No</th>';
                $a += '<th style="text-align: center; vertical-align: bottom !important;">Client</th>'; 
                $a += '<th style="text-align: center; vertical-align: bottom !important;">Transaction ID</th>'; 
                $a += '<th style="text-align: center; vertical-align: bottom !important;">Type of Document</th>'; 
                $a += '<th style="text-align: center; vertical-align: bottom !important;">Created On</th>'; 
                $a += '<th style="text-align: center; vertical-align: bottom !important;">Created By</th>';  
                $a += '</tr></thead>';
                $a += '</table>';
                $a += '</div>';
                $("#report_table").append($a);

                if(list_of_document.length > 0)
                {  
                    for(var i = 0; i < list_of_document.length; i++)
                    {   
                        var created_at = moment(list_of_document[i]["created_at"]).format('YYYYMMDD');
                        
                        $b=""; 
                        $b += '<tr class="list_of_document_for_each_company">';
                        $b += '<td style="text-align: right">'+(i+1)+'</td>';
                        $b += '<td>'+list_of_document[i]["company_name"]+'</td>';
                        $b += '<td>'+list_of_document[i]["transaction_code"]+'</td>';
                        $b += '<td>'+list_of_document[i]["triggered_by_name"]+'</td>';
                        $b += '<td style="text-align:center;"><span style="display: none">'+ created_at + '</span>'+list_of_document[i]["created_at"]+'</td>';
                        $b += '<td>'+ list_of_document[i]["created_by_last_name"] + " " + list_of_document[i]["created_by_first_name"] +'</td>';
                        $b += '</tr>';

                        $(".list_of_document_table").append($b);
                    }

                    var t = $('#report_table_list_of_document').DataTable({
                        dom: "<'row'<'col-sm-5'B><'col-sm-7'f>>" + "<'row'<'col-sm-12't>>" +
            "<'row'<'col-sm-5'><'col-sm-7'p>>",
                        buttons: [
                            {
                                extend: 'excel',
                                exportOptions: {
                                    columns: [ 0, 1, 2, 3, 4, 5],
                                    format: {
                                         body: function (data, row, column, node) {
                                           //console.log(row);
                                            if(column === 0){
                                                data = row + 1;

                                            }

                                            if(column === 4){
                                                data = data.replace(/<span(.+?)<\/span>/s, "");

                                            }

                                            if(column === 1 || column === 3){
                                                data = data.replace(/&amp;/g, "&");

                                            }

                                            return column === 3 ?
                                                  data.replace(/<.*?>/ig, ""): data;
                                       }
                                    }
                                }
                            },
                          ],
                        "columnDefs": [ {
                            "searchable": false,
                            "orderable": false,
                            'type': 'num', 
                            "targets": 0
                        }],
                        "order": [ 4, "desc" ],
                        "paging": false
                    });

                    t.on( 'order.dt search.dt', function () {
                        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                            cell.innerHTML = i+1;
                        } );
                    } ).draw();

                    $('.buttons-excel').each(function() {
                       $(this).removeClass('dt-button').addClass('btn btn-primary')
                    });

                    $('#list_of_document .datatables-footer').hide();
                }
                else
                {
                    $z=""; 
                    $z += '<tr class="list_of_document_for_each_company">';
                    $z += '<td colspan="6" style="text-align:center;"><span style="font-weight:bold; font-size:20px;">N/A</span></td>';
                    $z += '</tr>';

                    $(".list_of_document_table").append($z);
                }
            }

            if (response.register === "gst_report" || response.register === "all")
            {
                console.log(response[0]);
                if(response[0] != undefined)
                {   
                    var gst_report = response[0]["gst_report"];
                    var cn_gst_report = response[0]["cn_gst_report"];
                    var list_of_category = response[0]["list_of_category"];
                    if(response[0]["list_of_category"][0]["currency_name"] == "MYR")
                    {
                        var gst_name = "SST";
                    }
                    else
                    {
                        var gst_name = "GST";
                    }
                }
                else
                {
                    var gst_report = '';
                    var cn_gst_report = '';
                    var list_of_category = '';
                    var gst_name = "GST";
                }
                
                $a = "";
                $a += '<div id="gst_report" style="margin-top:20px; width:100%">';
                $a += '<h3>'+gst_name+' Report</h3>';
                $a += '<table style="width:100%;table-layout:fixed" class="table table-bordered table-striped mb-none gst_report_table display nowrap" id="report_table_gst_report">';
                $a += '<thead><tr class="gst_report_table_header">'; 
                $a += '<th style="width: 50px; text-align: center; vertical-align: bottom !important;">No</th>';
                $a += '<th style="text-align: center; vertical-align: bottom !important; word-wrap:break-word">Invoice Date</th>'; 
                $a += '<th style="text-align: center; vertical-align: bottom !important; word-wrap:break-word">Invoice No.</th>'; 
                $a += '<th style="text-align: center; vertical-align: bottom !important; word-wrap:break-word">Company Name</th>'; 
                $a += '<th style="text-align: center; vertical-align: bottom !important; word-wrap:break-word">CCY</th>'; 
                $a += '<th style="text-align: center; vertical-align: bottom !important; word-wrap:break-word">Amount Before '+gst_name+'</th>'; 
                $a += '</tr></thead>';
                $a += '</table>';
                $a += '</div>';
                $("#report_table").append($a);

                for(x in list_of_category)
                {
                    $c = "";
                    $c += '<th style="text-align: center; vertical-align: bottom !important; word-wrap:break-word">'+list_of_category[x]["category"]+'</th>'; 
                    $(".gst_report_table_header").append($c);
                }
                
                $d = "";
                $d += '<th style="text-align: center; vertical-align: bottom !important; word-wrap:break-word">'+gst_name+'</th>';
                $d += '<th style="text-align: center; vertical-align: bottom !important; word-wrap:break-word">Total</th>'; 
                $(".gst_report_table_header").append($d);

                if(list_of_category != null)
                {
                    var num_of_header = list_of_category.length + 8;
                }
                else
                {
                    var num_of_header = 8;
                }

                if(gst_report.length > 0 || cn_gst_report.length > 0)
                {  
                    for(var i = 0; i < gst_report.length; i++)
                    {   
                        var latest_date_format = changeDateFormat(gst_report[i]["invoice_date"]);
                        $b=""; 
                        $b += '<tr class="gst_report_for_each_company">';
                        $b += '<td style="text-align: right">'+(i+1)+'</td>';
                        $b += '<td style="text-align:center;word-wrap:break-word"><span style="display: none">'+ formatDateFunc(new Date(latest_date_format)) + '</span>'+gst_report[i]["invoice_date"]+'</td>';
                        $b += '<td style="word-wrap:break-word">'+gst_report[i]["invoice_no"]+'</td>';
                        $b += '<td style="word-wrap:break-word">'+gst_report[i]["company_name"]+'</td>';
                        $b += '<td style="text-align:center; width: 50px; word-wrap:break-word">'+gst_report[i]["currency_name"]+'</td>';
                        $b += '<td style="text-align:right; word-wrap:break-word">'+addCommas(gst_report[i]["gst_category"][0])+'</td>';
                        // for(var m = 1; m < gst_report[i]["gst_category"].length; m++)
                        // {   
                        //     $b += '<td style="text-align:right; word-wrap:break-word">'+addCommas(gst_report[i]["gst_category"][m])+'</td>';
                        // }

                        for(let gstAmount in gst_report[i]["gst_category"])
                        {   
                            if(gstAmount != 0)
                            {
                                $b += '<td style="text-align:right; word-wrap:break-word">'+addCommas(gst_report[i]["gst_category"][gstAmount])+'</td>';
                            }
                        }

                        $b += '<td style="text-align:right; word-wrap:break-word">'+addCommas(gst_report[i]["total_gst"])+'</td>';
                        $b += '<td style="text-align:right; word-wrap:break-word">'+addCommas(gst_report[i]["total"])+'</td>';
                        $b += '</tr>';

                        $(".gst_report_table").append($b);
                    }

                    for(var i = 0; i < cn_gst_report.length; i++)
                    {   
                        var latest_date_format = changeDateFormat(cn_gst_report[i]["credit_note_date"]);
                        $b=""; 
                        $b += '<tr class="gst_report_for_each_company">';
                        $b += '<td style="text-align: right">'+(i+1)+'</td>';
                        $b += '<td style="text-align:center;word-wrap:break-word"><span style="display: none">'+ formatDateFunc(new Date(latest_date_format)) + '</span>'+cn_gst_report[i]["credit_note_date"]+'</td>';
                        $b += '<td style="word-wrap:break-word">'+cn_gst_report[i]["credit_note_no"]+'</td>';
                        $b += '<td style="word-wrap:break-word">'+cn_gst_report[i]["company_name"]+'</td>';
                        $b += '<td style="text-align:center; width: 50px; word-wrap:break-word">'+cn_gst_report[i]["currency_name"]+'</td>';
                        $b += '<td style="text-align:right; word-wrap:break-word">'+addCommas(cn_gst_report[i]["gst_category"][0])+'</td>';
                        // for(var m = 1; m < gst_report[i]["gst_category"].length; m++)
                        // {   
                        //     $b += '<td style="text-align:right; word-wrap:break-word">'+addCommas(gst_report[i]["gst_category"][m])+'</td>';
                        // }

                        for(let gstAmount in cn_gst_report[i]["gst_category"])
                        {   
                            if(gstAmount != 0)
                            {
                                $b += '<td style="text-align:right; word-wrap:break-word">'+addCommas(cn_gst_report[i]["gst_category"][gstAmount])+'</td>';
                            }
                        }

                        $b += '<td style="text-align:right; word-wrap:break-word">'+addCommas(cn_gst_report[i]["total_gst"])+'</td>';
                        $b += '<td style="text-align:right; word-wrap:break-word">'+addCommas(cn_gst_report[i]["total"])+'</td>';
                        $b += '</tr>';

                        $(".gst_report_table").append($b);
                    }

                    if(list_of_category.length > 0)
                    {
                        var arr = [];
                        for(var i = 0; i < num_of_header; i++)
                        {
                            arr.push(i);
                        }
                        var arr_header = arr;
                    }
                    else
                    {
                        var arr_header = "[0, 1, 2, 3, 4, 5, 6, 7, 8]";
                    }

                    var t = $('#report_table_gst_report').DataTable({
                        dom: "<'row'<'col-sm-5'B><'col-sm-7'f>>" + "<'row'<'col-sm-12't>>" +
            "<'row'<'col-sm-5'><'col-sm-7'p>>",
                        buttons: [
                            {
                                extend: 'excel',
                                exportOptions: {
                                    columns: arr_header,
                                    format: {
                                         body: function (data, row, column, node) {
                                           //console.log(row);
                                            if(column === 0){
                                                data = row + 1;

                                            }

                                            if(column === 1){
                                                data = data.replace(/<span(.+?)<\/span>/s, "");
                                            }

                                            if(column === 3){
                                                data = data.replace(/&amp;/g, "&");

                                            }

                                            return column === 2 ?
                                                  data.replace(/<.*?>/ig, ""): data;
                                       }
                                    }
                                }
                            },
                          ],
                        "columnDefs": [ {
                            "searchable": false,
                            "orderable": false,
                            'type': 'num', 
                            "targets": 0
                        }],
                        "order": [[ 1, "asc" ]],
                        "paging": false
                    });

                    t.on( 'order.dt search.dt', function () {
                        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                            cell.innerHTML = i+1;
                        } );
                    } ).draw();

                    $('.buttons-excel').each(function() {
                       $(this).removeClass('dt-button').addClass('btn btn-primary')
                    });

                    //$('#gst_report .datatables-header').hide();
                    $('#gst_report .datatables-footer').hide();

                }
                else
                {
                    $z=""; 
                    $z += '<tr class="gst_report_for_each_company">';
                    $z += '<td colspan="'+num_of_header+'" style="text-align:center;"><span style="font-weight:bold; font-size:20px;">N/A</span></td>';
                    $z += '</tr>';

                    $(".gst_report_table").append($z);
                }
            }

            if (response.register === "csp_report" || response.register === "all") 
            {
                if(response[0]["status"] != "unsucessful")
                {
                    window.open(
                      response[0]["link"],
                      '_blank' // <- This is what makes it open in a new window.
                    );
                }
                else
                {
                    toastr.error("You don't have any excel can be generated.", "Unsuccessful");
                }
            }

            if (response.register === "progress_bill_report" || response.register === "all") 
            {
                if(response[0] != undefined)
                {   
                    var progress_bill_report = response[0]["progress_bill_report"];
                }
                else
                {
                    var progress_bill_report = '';
                }

                $a = "";
                $a += '<div id="progress_bill_report" style="margin-top:20px; width:100%">';
                $a += '<h3>Progress Biiling Report</h3>';
                $a += '<table style="width:100%;table-layout:fixed" class="table table-bordered table-striped mb-none progress_bill_table display nowrap" id="report_table_progress_bill">';
                $a += '<thead><tr class="progress_bill_table_header">'; 
                $a += '<th style="width: 50px; text-align: center; vertical-align: bottom !important;">No</th>';
                $a += '<th style="text-align: center; vertical-align: bottom !important; word-wrap:break-word">Invoice Date</th>'; 
                $a += '<th style="text-align: center; vertical-align: bottom !important; word-wrap:break-word">Invoice No.</th>'; 
                $a += '<th style="text-align: center; vertical-align: bottom !important; word-wrap:break-word">Company Name</th>'; 
                $a += '<th style="text-align: center; vertical-align: bottom !important; word-wrap:break-word">Services</th>'; 
                $a += '<th style="text-align: center; vertical-align: bottom !important; word-wrap:break-word">POC</th>';
                $a += '<th style="text-align: center; vertical-align: bottom !important; word-wrap:break-word">Period Start Date</th>';  
                $a += '<th style="text-align: center; vertical-align: bottom !important; word-wrap:break-word">Period End Date</th>'; 
                $a += '<th style="text-align: center; vertical-align: bottom !important; word-wrap:break-word">CCY</th>';
                $a += '<th style="text-align: center; vertical-align: bottom !important; word-wrap:break-word">Amount</th>'; 
                $a += '<th style="text-align: center; vertical-align: bottom !important; word-wrap:break-word">Status</th>'; 
                $a += '</tr></thead>';
                $a += '</table>';
                $a += '</div>';
                $("#report_table").append($a);

                if(progress_bill_report.length > 0)
                {  
                    for(var i = 0; i < progress_bill_report.length; i++)
                    {   
                        var latest_invoice_date_format = changeDateFormat(progress_bill_report[i]["invoice_date"]);
                        
                        if(progress_bill_report[i]["transaction_master_with_billing_id"] != null && (progress_bill_report[i]["trans_billing_info_service_category_id"] != null || progress_bill_report[i]["our_service_billing_info_service_category_id"] != null))
                        {
                            if(progress_bill_report[i]["trans_billing_info_service_category_id"] != null)
                            {
                                var service_name = progress_bill_report[i]["trans_service_name"];
                                
                            }
                            else if(progress_bill_report[i]["our_service_billing_info_service_category_id"] != null)
                            {
                                var service_name = progress_bill_report[i]["our_service_service_name"];
                            }
                        }
                        else
                        {
                            var service_name = progress_bill_report[i]["service_name"];
                        }

                        var poc = progress_bill_report[i]["poc_percentage"] + "% of " + progress_bill_report[i]["number_of_percent_poc"];

                        if(progress_bill_report[i]["period_start_date"] != null)
                        {
                            var latest_period_start_date_format = changeDateFormat(progress_bill_report[i]["period_start_date"]);
                            var latest_period_start_date_format = formatDateFunc(new Date(latest_period_start_date_format));
                            var period_start_date = progress_bill_report[i]["period_start_date"];
                        }
                        else
                        {
                            var latest_period_start_date_format = "";
                            var period_start_date = "";
                        }

                        if(progress_bill_report[i]["period_end_date"] != null)
                        {
                            var latest_period_end_date_format = changeDateFormat(progress_bill_report[i]["period_end_date"]);
                            var latest_period_end_date_format = formatDateFunc(new Date(latest_period_end_date_format));
                            var period_end_date = progress_bill_report[i]["period_end_date"];
                        }
                        else
                        {
                            var latest_period_end_date_format = "";
                            var period_end_date = "";
                        }

                        if(parseFloat(progress_bill_report[i]["outstanding"]) > 0)
                        {
                            var status = "Unpaid";
                        }
                        else
                        {
                            var status = "Paid";
                        }

                        $b=""; 
                        $b += '<tr class="progress_bill_for_each_company">';
                        $b += '<td style="text-align: right"></td>';
                        $b += '<td style="text-align:center;"><span style="display: none">'+ formatDateFunc(new Date(latest_invoice_date_format)) + '</span>'+progress_bill_report[i]["invoice_date"]+'</td>';
                        $b += '<td>'+progress_bill_report[i]["invoice_no"]+'</td>';
                        $b += '<td>'+progress_bill_report[i]["company_name"]+'</td>';
                        $b += '<td>'+service_name+'</td>';
                        $b += '<td>'+poc+'</td>';
                        $b += '<td style="text-align:center;"><span style="display: none">'+ latest_period_start_date_format + '</span>'+period_start_date+'</td>';
                        $b += '<td style="text-align:center;"><span style="display: none">'+ latest_period_end_date_format + '</span>'+period_end_date+'</td>';
                        $b += '<td>'+progress_bill_report[i]["currency_name"]+'</td>';
                        $b += '<td style="text-align:right;">'+addCommas(progress_bill_report[i]["billing_service_amount"])+'</td>';
                        $b += '<td>'+status+'</td>';
                        $b += '</tr>';

                        $(".progress_bill_table").append($b);
                    }

                    var t = $('#report_table_progress_bill').DataTable({
                        dom: "<'row'<'col-sm-5'B><'col-sm-7'f>>" + "<'row'<'col-sm-12't>>" +
            "<'row'<'col-sm-5'><'col-sm-7'p>>",
                        buttons: [
                            {
                                extend: 'excel',
                                exportOptions: {
                                    columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                                    format: {
                                         body: function (data, row, column, node) {
                                           //console.log(row);
                                            if(column === 0){
                                                data = row + 1;

                                            }

                                            if(column === 1 || column === 7 || column === 8){
                                                data = data.replace(/<span(.+?)<\/span>/s, "");

                                            }

                                            if(column === 3 || column === 4){
                                                data = data.replace(/&amp;/g, "&");

                                            }

                                            return column === 4 ?
                                                  data.replace(/<.*?>/ig, ""): data;
                                       }
                                    }
                                }
                            },
                          ],
                        "columnDefs": [ {
                            "searchable": false,
                            "orderable": false,
                            'type': 'num', 
                            "targets": 0
                        }],
                        "order": [ 1, "asc" ], //desc
                        "paging": false
                    });

                    t.on( 'order.dt search.dt', function () {
                        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                            cell.innerHTML = i+1;
                        } );
                    } ).draw();

                    $('.buttons-excel').each(function() {
                       $(this).removeClass('dt-button').addClass('btn btn-primary')
                    });

                    $('#progress_bill .datatables-footer').hide();
                }
                else
                {
                    $z=""; 
                    $z += '<tr class="progress_bill_for_each_company">';
                    $z += '<td colspan="11" style="text-align:center;"><span style="font-weight:bold; font-size:20px;">N/A</span></td>';
                    $z += '</tr>';

                    $(".progress_bill_table").append($z);
                }
            }
        }
    });
})

