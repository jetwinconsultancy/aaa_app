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

	if(search == "person_profile")
	{
		$("#searchRegister").hide();
		$("#hidden_section1_report").show();
		$("#hidden_section2_report").show();
        $(".hidden_section3_report").hide();
	}
	else if(search == "client_list")
	{
		$("#searchRegister").show();
		$("#hidden_section1_report").hide();
		$("#hidden_section2_report").hide();
        $(".hidden_section3_report").hide();
	}
	else if(search == "due_date")
	{
		$("#searchRegister").show();
		$("#hidden_section1_report").hide();
		$("#hidden_section2_report").hide();
        $(".hidden_section3_report").hide();
	}
	else if(search == "list_of_invoice")
	{
		$("#searchRegister").hide();
		$("#hidden_section1_report").hide();
		$("#hidden_section2_report").show();
        $(".hidden_section3_report").show();
	}
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
        	//console.log(response.register);
            //console.log(response[0]["filing_data"]);
            //$("#register_filing").remove();
            $("#person_profile").remove();
            $("#client_list").remove();
            $("#due_date").remove();
            $("#list_of_invoice").remove();
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
            	$a += '<h3>Client List</h3>';
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
				        $b += '<td>'+client_list[i]["name"]+'</td>';
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
				    var t = $('#report_table_client_list').DataTable({
                        "columnDefs": [ {
                            "searchable": false,
                            "orderable": false,
                            "targets": 0
                        } ],
                        "paging": false
                    });
                    
                    t.on( 'order.dt search.dt', function () {
                        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                            cell.innerHTML = i+1;
                        } );
                    } ).draw();

				    //$('#client_list .datatables-header').hide();
				    $('#client_list .datatables-footer').hide();

            	}
            	else
            	{
            		$z=""; 
			        $z += '<tr class="client_list_for_each_company">';
			        $z += '<td colspan="3" style="text-align:center;"><span style="font-weight:bold; font-size:20px;">N/A</span></td>';
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
				$a += '<th style="text-align: center">Year End</th>'; 
				$a += '<th style="text-align: center">Due for Filing in (days)</th>'; 
				$a += '</tr></thead>';
				$a += '</table>';
				$a += '</div>';
				$("#report_table").append($a);

            	if(due_date.length > 0)
            	{
					for(var i = 0; i < due_date.length; i++)
				    {
				        $b=""; 
				        $b += '<tr class="due_date_for_each_company">';
				        $b += '<td style="text-align: right">'+(i+1)+'</td>';
				        $b += '<td>'+due_date[i]["company_name"]+'</td>';
                        if(due_date[i]["year_end"] != undefined)
                        {
                            $b += '<td style="text-align:center">'+due_date[i]["year_end"]+'</td>';
                        }
                        else
                        {
                            $b += '<td style="text-align:center"></td>';
                        }
				        
                        if(due_date[i]["days"] != undefined)
                        {
                            $b += '<td style="text-align:center">'+due_date[i]["days"]+'</td>';
                        }
				        else
                        {
                            $b += '<td style="text-align:center"></td>';
                        }
				        $b += '</tr>';

				        $(".due_date_table").append($b);

				    }
				    $('#report_table_due_date').DataTable({"paging": false,});
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
            		{
            			var list_of_invoice = response[0]["list_of_invoice"];
            		}
            		else
            		{
            			var list_of_invoice = '';
            		}
            	}

            	$a = "";
            	$a += '<div id="list_of_invoice" style="margin-top:20px;">';
            	$a += '<h3>List of Invoice</h3>';
            	//$a += '<table class="table table-bordered table-striped mb-none" id="datatable-default register_officer_table">';
				$a += '<table class="table table-bordered table-striped mb-none list_of_invoice_table" id="report_table_list_of_invoice">';
				$a += '<thead><tr>'; 
				$a += '<th style="width:50px !important;text-align:center">No</th>';
				$a += '<th style="text-align: center">Invoice Date</th>'; 
				$a += '<th style="text-align: center">Invoice No.</th>'; 
				$a += '<th style="text-align: center">Company Name</th>'; 
				$a += '<th style="text-align: center">CCY</th>'; 
				$a += '<th style="text-align: center">Amount</th>'; 
				$a += '<th style="text-align: center">Outstanding</th>'; 
				$a += '</tr></thead>';
				$a += '</table>';
				$a += '</div>';
				$("#report_table").append($a);

            	if(list_of_invoice.length > 0)
            	{
					for(var i = 0; i < list_of_invoice.length; i++)
				    {
				        $b=""; 
				        $b += '<tr class="list_of_invoice_for_each_company">';
				        $b += '<td style="text-align: right">'+(i+1)+'</td>';
				        $b += '<td style="text-align:center">'+list_of_invoice[i]["invoice_date"]+'</td>';
				        $b += '<td>'+list_of_invoice[i]["invoice_no"]+'</td>';
				        $b += '<td>'+list_of_invoice[i]["company_name"]+'</td>';
				        $b += '<td style="text-align:center">'+list_of_invoice[i]["currency_name"]+'</td>';
				        $b += '<td style="text-align:right">'+addCommas(list_of_invoice[i]["amount"])+'</td>';
				        $b += '<td style="text-align:right">'+addCommas(list_of_invoice[i]["outstanding"])+'</td>';
				        $b += '</tr>';

				        $(".list_of_invoice_table").append($b);

				    }
				    $('#report_table_list_of_invoice').DataTable({"paging": false,});
				    $('#list_of_invoice .datatables-header').hide();
				    $('#list_of_invoice .datatables-footer').hide();

            	}
            	else
            	{
            		$z=""; 
			        $z += '<tr class="due_date_for_each_company">';
			        $z += '<td colspan="7" style="text-align:center;"><span style="font-weight:bold; font-size:20px;">N/A</span></td>';
			        $z += '</tr>';

			        $(".list_of_invoice_table").append($z);
            	}
            }
        }
    });
})

