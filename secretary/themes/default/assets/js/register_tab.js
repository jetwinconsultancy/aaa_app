var register_client_info;
var pathArray = location.href.split( '/' );
var protocol = pathArray[0];
var host = pathArray[2];
var folder = pathArray[3];
var url = protocol + '//' + host + '/' + folder + '/';

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

function removeCommas(str) {
    while (str.search(",") >= 0) {
        str = (str + "").replace(',', '');
    }
    return str;
};

function register_change_date(latest_incorporation_date)
{
	//console.log(latest_incorporation_date);
	if(latest_incorporation_date == "Invalid Date")
	{
		$('#register_from').datepicker();

		$('#register_to').datepicker();
	}
	else
	{
		$('#register_from').datepicker('setStartDate', latest_incorporation_date).datepicker("setDate", latest_incorporation_date);

		$('#register_to').datepicker('setStartDate', latest_incorporation_date);
	}

}

toastr.options = {
  "positionClass": "toast-bottom-right"
}

$('#register_to').datepicker().on('changeDate', function (ev) {
    //console.log("in");
    var register_startDate = new Date($('#register_from').val());
	var register_endDate = new Date($('#register_to').val());

	if (register_startDate > register_endDate){
		$('#register_to').val("");
		toastr.error("Date range start date should not be after end date.", "Error");
	}
});

$('#register_from').datepicker().on('changeDate', function (ev) {
    //console.log("out");
    var register_startDates = new Date($('#register_from').val());
	var register_endDates = new Date($('#register_to').val());

	if (register_startDates > register_endDates){
		$('#register_to').val("");
		toastr.error("Date range start date should not be after end date.", "Error");
	}
});
/*$('#register_to').live("change", function(){
	console.log("in");
});*/
/*function formatDateFunc(date) {

	var array, tmp, date_2, date;
	date = date + '';
	array =  date.split("/");
	tmp = array[0];
	array[0] = array[1];
	array[1] = tmp;
	date_2 = array.join("/");
	date_2 = new Date(date_2);
	//console.log(date_2);

	var monthNames = [
		"01", "02", "03",
		"04", "05", "06", "07",
		"08", "09", "10",
		"11", "12"
	];

	var day = date_2.getDate();
	//console.log(day.length);
	if(day.toString().length==1)	
	{
		day="0"+day;
	}
		
	var monthIndex = date_2.getMonth();
	var year = date_2.getFullYear().toString().substr(-2);

	return day + '/' + monthNames[monthIndex] + '/' + year;
}*/

$('#printBtn').click(function(){
	//console.log("printBtn");
    
    $(".printable").print({
    	globalStyles: true,
    	mediaPrint: false,
    	title: $("#edit_company_name").val()
    });

	/*$( ".printable" ).printThis({
		importCSS: true,
		importStyle: false,         // import style tags
    	printContainer: true,
		loadCSS: "http://localhost/secretary/assets/stylesheets/theme.css",
	});*/
    // Cancel click event
    return(false);
});

$('#searchRegister').click(function(e){
	e.preventDefault();
	search_register_function();
});

function search_register_function()
{
	$('#loadingmessage').show();
	$.ajax({
        type: 'POST',
        url: "masterclient/search_register",
        data: $('#register_form').serialize(),
        dataType: 'json',
        success: function(response){
        	$('#loadingmessage').hide();
        	//console.log(response.register);
            //console.log(response[0]["filing_data"]);
            $(".register_collapsible").remove();
            $(".register_content").remove();
            $("#register_filing").remove();
            $("#register_officer").remove();
            $("#register_charges").remove();
            $("#register_member_header").remove();
            $("#register_guarantee_member").remove();
            $("#register_controller").remove();
            $("#register_profile").remove();
            $("#register_nominee_director").remove();
            $("#register_transfer").remove();

            if (response.register === "profile" || response.register === "all")
            {
            	if(response[0]["profile"].length > 0)
            	{
            		$a = "";
            		$a = '<button type="button" class="register_collapsible"><span style="font-size: 2.4rem;">PROFILE</span></button><div class="register_content"><div id="register_profile"><table class="table" style="border:none"><tr><td style="border:none;width: 200px;">Client Code: </td><td style="border:none"><div style="" id="register_client_code"></div></td></tr><tr><td style="border:none;width: 200px;">Registration No:</td><td style="border:none"><div style="" id="register_registration_no"></div></td></tr><tr><td style="border:none;width: 200px;">Company Name:</td><td style="border:none"><div style="" id="register_company_name"></div></td></tr><tr class="form-group former_name"><td style="border:none;width: 200px;">Former Name (if any):</td><td style="border:none"><div style="" id="register_former_name"></div></td></tr><tr class="form-group"><td style="border:none;width: 200px;">Incorporation Date:</td><td style="border:none"><div style="" id="register_incorporation_date"></div></td></tr><tr class="form-group"><td style="border:none;width: 200px;">Company Type:</td><td style="border:none"><div style="" id="register_company_type"></div></td></tr><tr class="form-group"><td style="border:none;width: 200px;">Status:</td><td style="border:none"><div style="" id="register_status"></div></td></tr><tr class="form-group"><td style="border:none;"><b>Principal Activities</b></td></tr><tr class="form-group"><td style="border:none;width: 200px;">Activity 1:</td><td style="border:none"><div style="" id="register_activity1"></div></td></tr><tr class="form-group activity2"><td style="border:none;width: 200px;">Activity 2:</td><td style="border:none"><div style="" id="register_activity2"></div></td></tr><tr class="form-group"><td style="border:none;"><b>Registered Office Address</b></td></tr><tr class="form-group"><td style="border:none;width: 200px;">Registered Office Address:</td><td style="border:none"><div style="width: 100%;"><div style="width: 25%;float:left;margin-right: 20px;"><label>Postal Code :</label></div><div style="width: 65%;float:left;margin-bottom:5px;"><div style="width: 20%;" id="register_postal_code"></div></div></div> </td></tr><tr class="form-group"><td style="border:none;width: 200px;"></td><td style="border:none"><div style="width: 100%;"><div style="width: 25%;float:left;margin-right: 20px;"><label>Street Name :</label></div><div style="width: 65%;float:left;margin-bottom:5px;"><div style="width: 100%;" id="register_street_name"></div></div></div> </td></tr><tr class="form-group"><td style="border:none;width: 200px;"></td><td style="border:none"><div style="width: 100%;"><div style="width: 25%;float:left;margin-right: 20px;"><label>Building Name :</label></div><div style="width: 65%;float:left;margin-bottom:5px;"><div style="width: 100%;" id="register_building_name"></div></div></div> </td></tr><tr class="form-group"><td style="border:none;width: 200px;"></td><td style="border:none"><div style="width: 100%;"><div style="width: 25%;float:left;margin-right: 20px;"><label>Unit No :</label></div><div style="width: 65%;float:left;margin-bottom:5px;"><div style="width: 5%; float: left;" id="register_unit_no1"></div><label style="float: left;margin-right: 10px;margin-left: 10px;" >-</label><div style="width: 8%;float: left;" id="register_unit_no2"></div></div></div></td></tr></table></div></div>';
            		$("#register_table").append($a);

            		$("#register_profile").show();
	            	//console.log(response[0]["profile"]);
	            	register_client_info = response[0]["profile"];
	            	$("#register_acquried_by").html(response[0]["profile"][0]["acquried_by_name"]);
	            	$("#register_client_code").html(response[0]["profile"][0]["client_code"]);
	            	$("#register_registration_no").html(response[0]["profile"][0]["registration_no"]);
	            	$("#register_company_name").html(response[0]["profile"][0]["company_name"]);
	            	if(response[0]["profile"][0]["former_name"] == "")
	            	{
	            		$("#register_profile .former_name").css("display", "none");
	            	}
	            	else
	            	{
	            		$("#register_profile .former_name").show();
	            		$("#register_former_name").html(response[0]["profile"][0]["former_name"]);
	            	}
	            	var parts =response[0]["profile"][0]["incorporation_date"].split('/');
					var date = parts[2]+"/"+parts[1]+"/"+parts[0];
					var mydate = $.datepicker.formatDate('dd M yy',new Date(date)); 
					//console.log(mydate.toDateString("d F Y"));

	            	$("#register_incorporation_date").html(mydate);
	            	$("#register_company_type").html(response[0]["profile"][0]["company_type_name"]);

	            	$("#register_status").html(response[0]["profile"][0]["status_name"]);
	            	$("#register_activity1").html(response[0]["profile"][0]["activity1"]);
	            	if(response[0]["profile"][0]["activity2"] == "")
	            	{
	            		$("#register_profile .activity2").css("display", "none");
	            	}
	            	else
	            	{
	            		$("#register_profile .activity2").show();
	            		$("#register_activity2").html(response[0]["profile"][0]["activity2"]);
	            	}
	            	/*$("#register_activity2").html(response[0]["profile"][0]["activity2"]);*/
	            	$("#register_postal_code").html(response[0]["profile"][0]["postal_code"]);

	            	$("#register_street_name").html(response[0]["profile"][0]["street_name"]);
	            	$("#register_building_name").html(response[0]["profile"][0]["building_name"]);
	            	$("#register_unit_no1").html(response[0]["profile"][0]["unit_no1"]);
	            	$("#register_unit_no2").html(response[0]["profile"][0]["unit_no2"]);
            	}
            	
            }
            if (response.register === "officer" || response.register === "all") 
            {
            	if(response.register === "all")
            	{
            		if(response[1] != undefined)
            		{
            			var client_officers = response[1]["client_officers"];
            		}
            		else
            		{
            			var client_officers = '';
            		}
            	}
            	else
            	{
            		if(response[0] != undefined)
            		{
            			var client_officers = response[0]["client_officers"];
            		}
            		else
            		{
            			var client_officers = '';
            		}
            	}

            	$a = "";
            	$a += '<button type="button" class="register_collapsible"><span style="font-size: 2.4rem;">REGISTER OF OFFICERS</span></button><div class="register_content"><div id="register_officer" style="margin-top:20px; margin-bottom:20px;">';
            	//$a += '<h4>REGISTER OF OFFICERS</h4>';
            	//$a += '<table class="table table-bordered table-striped mb-none" id="datatable-default register_officer_table">';
				$a += '<table class="table table-bordered table-striped mb-none register_officer_table" id="register_table_officer">';
				$a += '<thead><tr>'; 
				$a += '<th style="width:50px !important;text-align:center">No</th>';
				$a += '<th style="text-align: center">Position</th>'; 
				$a += '<th style="text-align: center">ID</th>'; 
				$a += '<th style="text-align: center; width:250px">Name</th>';
				$a += '<th style="text-align: center; width:250px">Address</th>';
				$a += '<th style="text-align: center">Date of Appointment</th>'; 
				$a += '<th style="text-align: center">Date Of Cessation</th>';
				$a += '</tr></thead>';
				$a += '</table>';
				$a += '</div></div>';
				$("#register_table").append($a);
				
            	if(client_officers.length > 0)
            	{
					for(var i = 0; i < client_officers.length; i++)
				    {
				    	var officer_address;
				    	var parts =client_officers[i]["date_of_appointment"].split('/');
						var date = parts[2]+"/"+parts[1]+"/"+parts[0];
						var mydate1 = $.datepicker.formatDate('dd M yy',new Date(date)); 

						if(client_officers[i]["date_of_cessation"] != "")
						{
							var parts =client_officers[i]["date_of_cessation"].split('/');
							var date = parts[2]+"/"+parts[1]+"/"+parts[0];
							var mydate2 = $.datepicker.formatDate('dd M yy',new Date(date)); 
						}
						else
						{
							var mydate2 = "";
						}

						if(client_officers[i]['officer_field_type'] == "individual" && client_officers[i]['officer_company_field_type'] == null)
						{
							if(client_officers[i]['officer_address_type'] == "Local")
							{
								if(client_officers[i]["unit_no1"] != "" || client_officers[i]["unit_no2"] != "")
								{
									var unit = ' #'+client_officers[i]["unit_no1"] +' - '+client_officers[i]["unit_no2"];
								}
								else
								{
									var unit = "";
								}
								officer_address = client_officers[i]["street_name1"]+ unit +' '+client_officers[i]["building_name1"]+' Singapore '+client_officers[i]["postal_code1"];
								//officer_frm.parent().parent().parent('form').find("DIV#form_controller_address").html( "" );
							}
							else if(client_officers[i]['officer_address_type'] == "Foreign")
							{
								officer_address = client_officers[i]["foreign_address1"] +'</br>'+ client_officers[i]["foreign_address2"] +'</br>'+ client_officers[i]["foreign_address3"];
								//officer_frm.parent().parent().parent('form').find("DIV#form_controller_address").html( "" );
							}
						}
						else(client_officers[i]['officer_company_field_type'] == "company" && client_officers[i]['officer_field_type'] == null)
						{
							if(client_officers[i]['officer_company_address_type'] == "Local")
							{
								if(client_officers[i]["company_unit_no1"] != "" || client_officers[i]["company_unit_no2"] != "")
								{
									var unit = ' #'+client_officers[i]["company_unit_no1"] +' - '+client_officers[i]["company_unit_no2"];
								}
								else
								{
									var unit = "";
								}
								officer_address = client_officers[i]["company_street_name"]+ unit +' '+client_officers[i]["company_building_name"]+' Singapore '+client_officers[i]["company_postal_code"];
								//officer_frm.parent().parent().parent('form').find("DIV#form_controller_address").html( "" );
							}
							else if(client_officers[i]['officer_company_address_type'] == "Foreign")
							{
								officer_address = client_officers[i]["company_foreign_address1"] +'</br>'+ client_officers[i]["company_foreign_address2"] +'</br>'+ client_officers[i]["company_foreign_address3"];
								//officer_frm.parent().parent().parent('form').find("DIV#form_controller_address").html( "" );
							}
						}
						

				        $b=""; 
				        $b += '<tr class="register_info_for_each_company">';
				        $b += '<td style="text-align: right">'+(i+1)+'</td>';
				        $b += '<td>'+client_officers[i]["position_name"]+'<div id="alternate_of'+i+'" hidden><span style="font-weight:bold;">Alternate of: </span><div id="register_alternate_of'+i+'"></div></div></td>';
				        
				        $b += '<td>'+((client_officers[i]["identification_no"] != null)?client_officers[i]["identification_no"] : client_officers[i]["register_no"])+'</td>';
				        $b += '<td>'+((client_officers[i]["name"] != null)?client_officers[i]["name"] : client_officers[i]["company_name"])+'</td>';
				        $b += '<td style="text-align:center">'+officer_address+'</td>';
				        $b += '<td style="text-align:center">'+mydate1+'</td>';
				        $b += '<td style="text-align:center">'+mydate2+'</td>';
				        $b += '</tr>';

				        $(".register_officer_table").append($b);

				        if(client_officers[i]["position"] == "7")
						{
							$("#alternate_of"+i+"").removeAttr('hidden');

							!function (i) {
								$.ajax({
									type: "POST",
									url: "masterclient/register_get_director",
									data: {"company_code": client_officers[i]["company_code"], "alternate_of": client_officers[i]["alternate_of"]}, // <--- THIS IS THE CHANGE
									dataType: "json",
									async: false,
									success: function(data){
							            if(data.tp == 1){
							                $.each(data['result'], function(key, val) {
							                    
							                    if(data.selected_director != null && key == data.selected_director)
							                    {
							                        $("#register_alternate_of"+i+"").html(val);

							                    }
							                    
							                });
							            }
							            else{
							                alert(data.msg);
							            }
									}				
								});
							} (i);
						}
				    }
				    $('#register_table_officer').DataTable({"paging": false,});
				    $('#register_officer .datatables-header').hide();
				    $('#register_officer .datatables-footer').hide();

            	}
            	else
            	{//console.log(client_officers.length);
            		$z=""; 
			        $z += '<tr class="filing_info_for_each_company">';
			        $z += '<td colspan="7" style="text-align:center;"><span style="font-weight:bold; font-size:20px;">N/A</span></td>';
			        $z += '</tr>';

			        $(".register_officer_table").append($z);
            	}

    //         	coll = document.getElementsByClassName("collapsible");

				// for (var g = 0; g < coll.length; g++) {
				//     coll[g].classList.toggle("incorp_active");
				//     coll[g].nextElementSibling.style.maxHeight = "100%";
				// }

				// for (var i = 0; i < coll.length; i++) {
				//   coll[i].addEventListener("click", function() {
				//     this.classList.toggle("incorp_active");
				//     var content = this.nextElementSibling;
				//     if (content.style.maxHeight){
				//       content.style.maxHeight = null;
				//     } else {
				//       content.style.maxHeight = "100%";
				//     } 
				//   });
				// }
            }

    //         if (response.register === "nominee_director" || response.register === "all") 
    //         {
    //         	if(response.register === "all")
    //         	{
    //         		if(response[6] != undefined)
    //         		{
    //         			var client_nominee_director = response[6]["client_nominee_director"];
    //         		}
    //         		else
    //         		{
    //         			var client_nominee_director = '';
    //         		}
    //         	}
    //         	else
    //         	{
    //         		if(response[0] != undefined)
    //         		{
    //         			var client_nominee_director = response[0]["client_nominee_director"];
    //         		}
    //         		else
    //         		{
    //         			var client_nominee_director = '';
    //         		}
    //         		//console.log(client_nominee_director);
    //         	}
    //         	//console.log(client_nominee_director);
    //         	$a = "";
    //         	$a += '<button type="button" class="register_collapsible"><span style="font-size: 2.4rem;">REGISTER OF NOMINEE DIRECTOR</span></button><div class="register_content"><div id="register_nominee_director" style="margin-top:20px; margin-bottom:20px;">';
    //         	//$a += '<h4>REGISTER OF OFFICERS</h4>';
    //         	//$a += '<table class="table table-bordered table-striped mb-none" id="datatable-default register_officer_table">';
				// $a += '<table class="table table-bordered table-striped mb-none register_nominee_director_table" id="register_table_nominee_director">';
				// $a += '<thead><tr>'; 
				// $a += '<th style="width:50px !important;text-align:center">No</th>';
				// $a += '<th style="text-align: center">Name of Nominee Director</th>'; 
				// $a += '<th style="text-align: center" colspan="2">Particular</th>'; 
				// $a += '</tr></thead>';
				// $a += '</table>';
				// $a += '</div></div>';
				// $("#register_table").append($a);
				
    //         	if(client_nominee_director.length > 0)
    //         	{
				// 	for(var i = 0; i < client_nominee_director.length; i++)
				//     {
				//     	var officer_address;
				//     	var parts =client_nominee_director[i]["date_of_appointment"].split('/');
				// 		var date = parts[2]+"/"+parts[1]+"/"+parts[0];
				// 		var mydate1 = $.datepicker.formatDate('dd M yy',new Date(date)); 

				// 		if(client_nominee_director[i]["date_of_cessation"] != "")
				// 		{
				// 			var parts =client_nominee_director[i]["date_of_cessation"].split('/');
				// 			var date = parts[2]+"/"+parts[1]+"/"+parts[0];
				// 			var mydate2 = $.datepicker.formatDate('dd M yy',new Date(date)); 
				// 		}
				// 		else
				// 		{
				// 			var mydate2 = "N/A";
				// 		}

				// 		if(client_nominee_director[i]["date_of_birth"] != null)
				// 		{
				// 			var date_of_birth = $.datepicker.formatDate('dd M yy',new Date(client_nominee_director[i]["date_of_birth"])); 
				// 		}
				// 		else
				// 		{
				// 			var date_of_birth = "N/A"; 
				// 		}

				// 		if(client_nominee_director[i]['officer_field_type'] == "individual" && client_nominee_director[i]['officer_company_field_type'] == null)
				// 		{
				// 			if(client_nominee_director[i]['officer_address_type'] == "Local")
				// 			{
				// 				if(client_nominee_director[i]["unit_no1"] != "" || client_nominee_director[i]["unit_no2"] != "")
				// 				{
				// 					var unit = ' #'+client_nominee_director[i]["unit_no1"] +' - '+client_nominee_director[i]["unit_no2"];
				// 				}
				// 				else
				// 				{
				// 					var unit = "";
				// 				}
				// 				officer_address = client_nominee_director[i]["street_name1"]+ unit +' '+client_nominee_director[i]["building_name1"]+' Singapore '+client_nominee_director[i]["postal_code1"];
				// 				//officer_frm.parent().parent().parent('form').find("DIV#form_controller_address").html( "" );
				// 			}
				// 			else if(client_nominee_director[i]['officer_address_type'] == "Foreign")
				// 			{
				// 				officer_address = client_nominee_director[i]["foreign_address1"] +'</br>'+ client_nominee_director[i]["foreign_address2"] +'</br>'+ client_nominee_director[i]["foreign_address3"];
				// 				//officer_frm.parent().parent().parent('form').find("DIV#form_controller_address").html( "" );
				// 			}
				// 		}
				// 		else(client_nominee_director[i]['officer_company_field_type'] == "company" && client_nominee_director[i]['officer_field_type'] == null)
				// 		{
				// 			if(client_nominee_director[i]['officer_company_address_type'] == "Local")
				// 			{
				// 				if(client_nominee_director[i]["company_unit_no1"] != "" || client_nominee_director[i]["company_unit_no2"] != "")
				// 				{
				// 					var unit = ' #'+client_nominee_director[i]["company_unit_no1"] +' - '+client_nominee_director[i]["company_unit_no2"];
				// 				}
				// 				else
				// 				{
				// 					var unit = "";
				// 				}
				// 				officer_address = client_nominee_director[i]["company_street_name"]+ unit +' '+client_nominee_director[i]["company_building_name"]+' Singapore '+client_nominee_director[i]["company_postal_code"];
				// 				//officer_frm.parent().parent().parent('form').find("DIV#form_controller_address").html( "" );
				// 			}
				// 			else if(client_nominee_director[i]['officer_company_address_type'] == "Foreign")
				// 			{
				// 				officer_address = client_nominee_director[i]["company_foreign_address1"] +'</br>'+ client_nominee_director[i]["company_foreign_address2"] +'</br>'+ client_officers[i]["company_foreign_address3"];
				// 				//officer_frm.parent().parent().parent('form').find("DIV#form_controller_address").html( "" );
				// 			}
				// 		}
						

				//         $b=""; 
				//         $b += '<tr class="register_info_for_each_company">';
				//         $b += '<td style="text-align: right" rowspan="8">'+(i+1)+'</td>';
				//         $b += '<td rowspan="8">'+((client_nominee_director[i]["name"] != null)?client_nominee_director[i]["name"] : client_nominee_director[i]["company_name"])+'</td>';
				//         $b += '<td>Full Name: </td>';
				//         $b += '<td>'+((client_nominee_director[i]["name"] != null)?client_nominee_director[i]["name"] : client_nominee_director[i]["company_name"])+'</td>';
				//         $b += '</tr>';
				//         $b += '<tr class="register_info_for_each_company">';
				//         $b += '<td>Alias: </td>';
				//         $b += '<td>N/A</td>';
				//         $b += '</tr>';
				//         $b += '<tr class="register_info_for_each_company">';
				//         $b += '<td>Residential Address: </td>';
				//         $b += '<td>'+officer_address+'</td>';
				//         $b += '</tr>';
				//         $b += '<tr class="register_info_for_each_company">';
				//         $b += '<td>Nationality: </td>';
				//         $b += '<td>'+((client_nominee_director[i]["officer_nationality"] != null)?client_nominee_director[i]["officer_nationality"] : client_nominee_director[i]["country_of_incorporation"])+'</td>';
				//         $b += '</tr>';
				//         $b += '<tr class="register_info_for_each_company">';
				//         $b += '<td>Identification No.: </td>';
				//         $b += '<td>'+((client_nominee_director[i]["identification_no"] != null)?client_nominee_director[i]["identification_no"] : client_nominee_director[i]["register_no"])+'</td>';
				//         $b += '</tr>';
				//         $b += '<tr class="register_info_for_each_company">';
				//         $b += '<td>Date of Birth: </td>';
				//         $b += '<td>'+date_of_birth+'</td>';
				//         $b += '</tr>';
				//         $b += '<tr class="register_info_for_each_company">';
				//         $b += '<td>Date of becoming a nominee director: </td>';
				//         $b += '<td>'+mydate1+'</td>';
				//         $b += '</tr>';
				//         $b += '<tr class="register_info_for_each_company">';
				//         $b += '<td>Date of cessation a nominee director: </td>';
				//         $b += '<td>'+mydate2+'</td>';
				//         $b += '</tr>';

				//         $(".register_nominee_director_table").append($b);

				//     }
				//     // $('#register_table_nominee_director').DataTable({"paging": false,});
				//     // $('#register_nominee_director .datatables-header').hide();
				//     // $('#register_nominee_director .datatables-footer').hide();

    //         	}
    //         	else
    //         	{//console.log(client_officers.length);
    //         		$z=""; 
			 //        $z += '<tr class="filing_info_for_each_company">';
			 //        $z += '<td colspan="3" style="text-align:center;"><span style="font-weight:bold; font-size:20px;">N/A</span></td>';
			 //        $z += '</tr>';

			 //        $(".register_nominee_director_table").append($z);
    //         	}
    //         }

            if (response.register === "member" || response.register === "all") 
            {
            	

            	if(response.register === "all")
            	{
            		if(response[2] != undefined)
            		{
            			if(response[2]["member"])
            			{
            				var member_info = response[2]["member"];

            				var guarantee_member_info = '';
            			}
            			else if(response[2]["guarantee_member"])
            			{
            				var guarantee_member_info = response[2]["guarantee_member"];
            				var member_info = '';
            			}
            			
            		}
            		else
            		{
            			var member_info = '';
            			var guarantee_member_info = '';
            		}
            		
            	}
            	else
            	{
            		if(response[0] != undefined)
            		{
            			//var member_info = response[0]["member"];
            			if(response[0]["member"])
            			{
            				var member_info = response[0]["member"];
            				var guarantee_member_info = '';
            			}
            			else if(response[0]["guarantee_member"])
            			{
            				var guarantee_member_info = response[0]["guarantee_member"];
            				var member_info = '';
            			}
            		}
            		else if(response[0] == undefined)
            		{
            			var member_info = '';
            			var guarantee_member_info = '';
            		}
            	}
            	
            	if(response.check_member_state == "non-guarantee")
            	{
					if(member_info.length > 0)
					{
						var id = null, balance = 0, test_id = 0, table_id = 0, row_id = 0, share_capital_id = 0;
						var officer_address, each_address;

						$g = "";
					    $g += '<button type="button" class="register_collapsible"><span style="font-size: 2.4rem;">REGISTER OF MEMBERS AND SHARE LEDGER</span></button><div class="register_content"><div id="register_member_header" style="margin-top:20px; margin-bottom: 20px;">';
						//$g += '<h4>REGISTER OF MEMBERS AND SHARE LEDGER</h4>';
		            	$g += '<table class="table table-bordered mb-none" id="" style="width:100%;">';
		            	$g += '<tr><td style="font-weight: bold;"> Name of Company : '+response.client_name+'';

		            	$g += '</td><td style="width: 200px; font-weight: bold;"> Folio No. : ';
		            	$g += '</td></tr>';
		            	$g += '</table>';
		            	$g += '</div></div>';

		            	$("#register_table").append($g);

						for(var i = 0; i < member_info.length; i++)
					    {
					    	if(member_info[i]["sharetype"] == "Others")
							{
								var sharetype = "(" +member_info[i]["other_class"]+ ")" ;
							}
							else
							{
								var sharetype = "";
							}

							
							if(member_info[i]["identification_no"] == test_id && member_info[i]["share_capital_id"] == share_capital_id)
							{
								balance += parseInt(member_info[i]["number_of_share"]);
								row_id++;
							}
							else if(member_info[i]["register_no"] == test_id && member_info[i]["share_capital_id"] == share_capital_id)
							{
								balance += parseInt(member_info[i]["number_of_share"]);
								row_id++;
							}
							else if(member_info[i]["registration_no"] == test_id && member_info[i]["share_capital_id"] == share_capital_id)
							{
								balance += parseInt(member_info[i]["number_of_share"]);
								row_id++;
							}
							else
							{
								if(member_info[i]["identification_no"] != null)
								{
									test_id = member_info[i]["identification_no"];
									share_capital_id = member_info[i]["share_capital_id"];
									balance = parseInt(member_info[i]["number_of_share"]);
									table_id++;
									row_id = 1;

									if(member_info[i]['officer_address_type'] == "Local")
									{
										if(member_info[i]["unit_no1"] != "" || member_info[i]["unit_no2"] != "")
										{
											var client_unit = ' #'+member_info[i]["unit_no1"] +' - '+member_info[i]["unit_no2"];
										}
										else
										{
											var client_unit = "";
										}
										each_address = member_info[i]["street_name1"]+ client_unit +' '+member_info[i]["building_name1"]+' Singapore '+member_info[i]["postal_code1"];
									}
									else if(member_info[i]['officer_address_type'] == "Foreign")
									{
										each_address = member_info[i]["foreign_address1"] +'</br>'+ member_info[i]["foreign_address2"] +'</br>'+ member_info[i]["foreign_address3"];
									}

								}
								else if(member_info[i]["register_no"] != null)
								{
									test_id = member_info[i]["register_no"];
									share_capital_id = member_info[i]["share_capital_id"];
									balance = parseInt(member_info[i]["number_of_share"]);
									table_id++;
									row_id = 1;

									if(member_info[i]['officer_company_address_type'] == "Local")
									{
										if(member_info[i]["company_unit_no1"] != "" || member_info[i]["company_unit_no2"] != "")
										{
											var client_unit = ' #'+member_info[i]["company_unit_no1"] +' - '+member_info[i]["company_unit_no2"];
										}
										else
										{
											var client_unit = "";
										}
										each_address = member_info[i]["company_street_name"]+ client_unit +' '+member_info[i]["company_building_name"]+' Singapore '+member_info[i]["company_postal_code"];
									}
									else if(member_info[i]['officer_company_address_type'] == "Foreign")
									{
										each_address = member_info[i]["company_foreign_address1"] +'</br>'+ member_info[i]["company_foreign_address2"] +'</br>'+ member_info[i]["company_foreign_address3"];
									}
								}
								else
								{
									test_id = member_info[i]["registration_no"];
									share_capital_id = member_info[i]["share_capital_id"];
									balance = parseInt(member_info[i]["number_of_share"]);
									table_id++;
									row_id = 1;

									if(member_info[i]["client_unit_no1"] != "" || member_info[i]["client_unit_no2"] != "")
									{
										var client_unit = ' #'+member_info[i]["client_unit_no1"] +' - '+member_info[i]["client_unit_no2"];
									}
									else
									{
										var client_unit = "";
									}
									each_address = member_info[i]["client_street_name"]+ client_unit +' '+member_info[i]["client_building_name"]+' Singapore '+member_info[i]["client_postal_code"];
								}
								

								$a = "";
								$a += '<div style="margin-top:20px;">';
								$a += '<table class="table table-bordered mb-none" id="" style="width:100%;">';
				            	$a += '<tr><td style="font-weight: bold;"> Name : '+((member_info[i]["name"] != null)?member_info[i]["name"] : (member_info[i]["company_name"] != null?member_info[i]["company_name"]:member_info[i]["client_company_name"]))+'';

				            	$a += '</td><td style="width: 200px; font-weight: bold;"> NRIC No./Passport No./Company No. : '+((member_info[i]["identification_no"] != null)?member_info[i]["identification_no"] : (member_info[i]["register_no"] != null?member_info[i]["register_no"]:member_info[i]["registration_no"]))+'';
				            	$a += '</td></tr><tr>';
				            	$a += '</td><td style="width: 200px; font-weight: bold;"> Address : '+each_address+'';
				            	$a += '</td><td style="width: 200px; font-weight: bold;"> Nationality/Country of Incorporation : '+((member_info[i]["nationality_name"] != null)?member_info[i]["nationality_name"] : ((member_info[i]["country_of_incorporation"] != null)?member_info[i]["country_of_incorporation"]:''))+'';
				            	$a += '</td></tr>';
				            	$a += '</table>';
				            	$a += '</div>';
				            	$a += '<div id="register_member" style="margin-top:20px;">';
				            	$a += '<table class="table table-bordered table-striped mb-none register_member_table'+table_id+'" id="register_table_member" style="width:100%;">';
								//$a += '<table style="border:1px solid black" class="allotment_table" id="register_filing_table">';
								$a += '<thead><tr>'; 
								$a += '<th style="text-align:center; width:10px !important;padding-right:2px !important;padding-left:2px !important;">No</th>';
								// $a += '<th style="word-break:break-all;text-align: center;width:50px !important;padding-right:2px !important;padding-left:2px !important;">ID</th>'; 
								// $a += '<th style="word-break:break-all;text-align: center; width:50px !important;padding-right:2px !important;padding-left:2px !important;">Name/ Address</th>'; 
								//$a += '<th style="text-align: center; width:10px !important;padding-right:2px !important;padding-left:2px !important;">Movement in Number of Shares</th>'; 
								$a += '<th style="text-align: center; width:50px !important;padding-right:2px !important;padding-left:2px !important;">Date of Acquisition or Transfer</th>';
								$a += '<th style="text-align: center; width:100px !important;padding-right:2px !important;padding-left:2px !important;">Transaction Type</th>';
								$a += '<th style="text-align: center; width:50px !important;padding-right:2px !important;padding-left:2px !important;">Class</th>';
								$a += '<th style="text-align: center; width:20px !important;padding-right:2px !important;padding-left:2px !important;">Old/New Share Certificate No.</th>';
								$a += '<th style="text-align: center; width:50px !important;padding-right:2px !important;padding-left:2px !important;">CCY</th>';
								$a += '<th style="text-align: center; width:10px !important;padding-right:2px !important;padding-left:2px !important;">No. of Shares Acquired</th>';
								$a += '<th style="text-align: center; width:10px !important;padding-right:2px !important;padding-left:2px !important;">No. of Shares Transferred</th>';
								$a += '<th style="text-align: center; width:10px !important;padding-right:2px !important;padding-left:2px !important;">Balance No of Shares</th>';
								
								$a += '</tr></thead>';
								$a += '</table>';
								$a += '</div>';
								$("#register_member_header").append($a);

								
							}

							if(test_id == member_info[i]["identification_no"] || test_id == member_info[i]["register_no"] || test_id == member_info[i]["registration_no"])
							{
								var parts = member_info[i]["transaction_date"].split('/');
								var date = parts[2]+"/"+parts[1]+"/"+parts[0];
								var mydate1 = $.datepicker.formatDate('dd M yy',new Date(date)); 

								if(member_info[i]['officer_field_type'] == "individual" && member_info[i]['officer_company_field_type'] == null)
								{
									if(member_info[i]['officer_address_type'] == "Local")
									{
										if(member_info[i]["unit_no1"] != "" || member_info[i]["unit_no2"] != "")
										{
											var unit = ' #'+member_info[i]["unit_no1"] +' - '+member_info[i]["unit_no2"];
										}
										else
										{
											var unit = "";
										}
										officer_address = member_info[i]["street_name1"]+ unit +' '+member_info[i]["building_name1"]+' Singapore '+member_info[i]["postal_code1"];
									}
									else if(member_info[i]['officer_address_type'] == "Foreign")
									{
										officer_address = member_info[i]["foreign_address1"] +'</br>'+ member_info[i]["foreign_address2"] +'</br>'+ member_info[i]["foreign_address3"];
									}
								}
								else if(member_info[i]['officer_company_field_type'] == "company" && member_info[i]['officer_field_type'] == null)
								{
									if(member_info[i]['officer_company_address_type'] == "Local")
									{
										if(member_info[i]["company_unit_no1"] != "" || member_info[i]["company_unit_no2"] != "")
										{
											var unit = ' #'+member_info[i]["company_unit_no1"] +' - '+member_info[i]["company_unit_no2"];
										}
										else
										{
											var unit = "";
										}
										officer_address = member_info[i]["company_street_name"]+ unit +' '+member_info[i]["company_building_name"]+' Singapore '+member_info[i]["company_postal_code"];
									}
									else if(member_info[i]['officer_company_address_type'] == "Foreign")
									{
										officer_address = member_info[i]["company_foreign_address1"] +'</br>'+ member_info[i]["company_foreign_address2"] +'</br>'+ member_info[i]["company_foreign_address3"];
									}
								}
								else if(member_info[i]['client_field_type'] == "client" && member_info[i]['officer_company_field_type'] == null && member_info[i]['officer_field_type'] == null)
								{

									if(member_info[i]["client_unit_no1"] != "" || member_info[i]["client_unit_no2"] != "")
									{
										var unit = ' #'+member_info[i]["client_unit_no1"] +' - '+member_info[i]["client_unit_no2"];
									}
									else
									{
										var unit = "";
									}
									officer_address = member_info[i]["client_street_name"]+ unit +' '+member_info[i]["client_building_name"]+' Singapore '+member_info[i]["client_postal_code"];

							
								}


								

						        $b=""; 
						        $b += '<tr class="member_info_for_each_company">';
						        $b += '<td style="text-align: right;width:10px">'+row_id+'</td>';

						        //$b += '<td>'+((member_info[i]["identification_no"] != null)?member_info[i]["identification_no"] : (member_info[i]["register_no"] != null?member_info[i]["register_no"]:member_info[i]["registration_no"]))+'</td>';
						        //$b += '<td><span style="font-weight:bold;">'+((member_info[i]["name"] != null)?member_info[i]["name"] : (member_info[i]["company_name"] != null?member_info[i]["company_name"]:member_info[i]["client_company_name"]))+'</span><div>'+officer_address+'</div></td>';
						       	$b += '<td style="text-align:center">'+mydate1+'</td>';
						       	$b += '<td>'+member_info[i]["transaction_type"]+'</td>';
						       	$b += '<td>'+member_info[i]["sharetype"] + " " + sharetype+'</td>';
						       	$b += '<td style="text-align:center">'+ ((member_info[i]["certificate_no"] != null)?member_info[i]["certificate_no"]:"") + ((member_info[i]["status"] != 1 && member_info[i]["certificate_no"] != null)?" (Cancelled) " : (member_info[i]["status"] != 1 && member_info[i]["certificate_no"] == null)? " N/A " : "")+'</td>';
						       	$b += '<td style="text-align:center">'+member_info[i]["currency"]+'</td>';
						       	if(parseInt(member_info[i]["number_of_share"]) >= 0)
						       	{
						       		$b += '<td style="text-align:right">'+addCommas(member_info[i]["number_of_share"])+'</td>';
						       	}
						       	else
						       	{
						       		$b += '<td style="text-align:right">-</td>';
						       	}

						       	if(0 > parseInt(member_info[i]["number_of_share"]))
						       	{
						       		$b += '<td style="text-align:right">'+addCommas(member_info[i]["number_of_share"])+'</td>';
						       	}
						       	else
						       	{
						       		$b += '<td style="text-align:right">-</td>';
						       	}

						       	$b += '<td style="text-align:right">'+addCommas(balance)+'</td>';
						       	


						        $b += '</tr>';

						        $(".register_member_table"+table_id).append($b);
						        //console.log(table_id);
						    }
							
					    }

		            	
					    $('#register_table_member').DataTable({"paging": false,});
					    $('#register_member .datatables-header').hide();
					    $('#register_member .datatables-footer').hide();
					}
					else
	            	{
	            		$b=""; 
				        $b += '<tr class="member_info_for_each_company">';
				        $b += '<td colspan="10" style="text-align:center;"><span style="font-weight:bold; font-size:20px;">N/A</span></td>';
				        $b += '</tr>';

				        $(".register_member_table"+table_id).append($b);
	            	}
	    //         	coll = document.getElementsByClassName("collapsible");

					// for (var g = 0; g < coll.length; g++) {
					//     coll[g].classList.toggle("incorp_active");
					//     coll[g].nextElementSibling.style.maxHeight = "100%";
					// }

					// for (var i = 0; i < coll.length; i++) {
					//   coll[i].addEventListener("click", function() {
					//     this.classList.toggle("incorp_active");
					//     var content = this.nextElementSibling;
					//     if (content.style.maxHeight){
					//       content.style.maxHeight = null;
					//     } else {
					//       content.style.maxHeight = "100%";
					//     } 
					//   });
					// }
            	}
            	else if (response.check_member_state == "guarantee")
            	{
            		//console.log(guarantee_member_info);
            		$a = "";
	            	$a += '<button type="button" class="register_collapsible"><span style="font-size: 2.4rem;">REGISTER OF MEMBERS AND SHARE LEDGER</span></button><div class="register_content"><div id="register_guarantee_member" style="margin-top:20px; margin-bottom: 20px;">';
	            	//$a += '<h4>MEMBER</h4>';
	            	$a += '<table class="table table-bordered table-striped mb-none register_guarantee_member_table" id="register_table_guarantee_member" style="width:100%;">';
					$a += '<thead><tr>'; 
					$a += '<th style="text-align:center; width:10px !important;padding-right:2px !important;padding-left:2px !important;">No</th>';
					$a += '<th style="word-break:break-all;text-align: center;width:50px !important;padding-right:2px !important;padding-left:2px !important;">ID</th>'; 
					$a += '<th style="word-break:break-all;text-align: center; width:50px !important;padding-right:2px !important;padding-left:2px !important;">Name</th>'; 
					$a += '<th style="text-align: center; width:100px !important;padding-right:2px !important;padding-left:2px !important;">Address</th>';
					$a += '<th style="text-align: center; width:50px !important;padding-right:2px !important;padding-left:2px !important;">CCY</th>';
					$a += '<th style="text-align: center; width:100px !important;padding-right:2px !important;padding-left:2px !important;">Amount of Guarantee</th>';
					$a += '<th style="text-align: center; width:50px !important;padding-right:2px !important;padding-left:2px !important;">Total Guarantee</th>';
					$a += '<th style="text-align: center; width:50px !important;padding-right:2px !important;padding-left:2px !important;">Transaction Date</th>';
					$a += '</tr></thead>';
					$a += '</table>';
					$a += '</div></div>';
					$("#register_table").append($a);

					if(guarantee_member_info.length > 0)
					{
						var id = null, balance = 0;
						var officer_address;

		            	for(var i = 0; i < guarantee_member_info.length; i++)
					    {

							if(id == null)
							{
								if(guarantee_member_info[i]["identification_no"] != null)
								{
									id = guarantee_member_info[i]["identification_no"];
									balance = parseFloat(guarantee_member_info[i]["guarantee"]);
								}
								else
								{
									id = guarantee_member_info[i]["register_no"];
									balance = parseFloat(guarantee_member_info[i]["guarantee"]);
								}
							}
							else
							{
								/*console.log(member_info[i]["identification_no"]);
								console.log(id);*/
								if(guarantee_member_info[i]["identification_no"] == id)
								{
									balance += parseFloat(guarantee_member_info[i]["guarantee"]);
								}
								else if(guarantee_member_info[i]["register_no"] == id)
								{
									balance += parseFloat(guarantee_member_info[i]["guarantee"]);
								}
								else
								{
									if(guarantee_member_info[i]["identification_no"] != null)
									{
										id = guarantee_member_info[i]["identification_no"];
										balance = parseFloat(guarantee_member_info[i]["guarantee"]);
									}
									else
									{
										id = guarantee_member_info[i]["register_no"];
										balance = parseFloat(guarantee_member_info[i]["guarantee"]);
									}
								}
							}

							var parts =guarantee_member_info[i]["guarantee_start_date"].split('/');
							var date = parts[2]+"/"+parts[1]+"/"+parts[0];
							var mydate1 = $.datepicker.formatDate('dd M yy',new Date(date)); 

							if(guarantee_member_info[i]['officer_field_type'] == "individual" && guarantee_member_info[i]['officer_company_field_type'] == null)
							{
								if(guarantee_member_info[i]['officer_address_type'] == "Local")
								{
									if(guarantee_member_info[i]["unit_no1"] != "" || guarantee_member_info[i]["unit_no2"] != "")
									{
										var unit = ' #'+guarantee_member_info[i]["unit_no1"] +' - '+guarantee_member_info[i]["unit_no2"];
									}
									else
									{
										var unit = "";
									}
									officer_address = guarantee_member_info[i]["street_name1"]+ unit +' '+guarantee_member_info[i]["building_name1"]+' Singapore '+guarantee_member_info[i]["postal_code1"];
									//officer_frm.parent().parent().parent('form').find("DIV#form_controller_address").html( "" );
								}
								else if(guarantee_member_info[i]['officer_address_type'] == "Foreign")
								{
									officer_address = guarantee_member_info[i]["foreign_address1"] +'</br>'+ guarantee_member_info[i]["foreign_address2"] +'</br>'+ guarantee_member_info[i]["foreign_address3"];
									//officer_frm.parent().parent().parent('form').find("DIV#form_controller_address").html( "" );
								}
							}
							else(guarantee_member_info[i]['officer_company_field_type'] == "company" && guarantee_member_info[i]['officer_field_type'] == null)
							{
								if(guarantee_member_info[i]['officer_company_address_type'] == "Local")
								{
									if(guarantee_member_info[i]["company_unit_no1"] != "" || guarantee_member_info[i]["company_unit_no2"] != "")
									{
										var unit = ' #'+guarantee_member_info[i]["company_unit_no1"] +' - '+guarantee_member_info[i]["company_unit_no2"];
									}
									else
									{
										var unit = "";
									}
									officer_address = guarantee_member_info[i]["company_street_name"]+ unit +' '+guarantee_member_info[i]["company_building_name"]+' Singapore '+guarantee_member_info[i]["company_postal_code"];
									//officer_frm.parent().parent().parent('form').find("DIV#form_controller_address").html( "" );
								}
								else if(guarantee_member_info[i]['officer_company_address_type'] == "Foreign")
								{
									officer_address = guarantee_member_info[i]["company_foreign_address1"] +'</br>'+ guarantee_member_info[i]["company_foreign_address2"] +'</br>'+ guarantee_member_info[i]["company_foreign_address3"];
									//officer_frm.parent().parent().parent('form').find("DIV#form_controller_address").html( "" );
								}
							}

					        $b=""; 
					        $b += '<tr class="guarantee_member_info_for_each_company">';
					        $b += '<td style="text-align: right;width:10px">'+(i+1)+'</td>';

					        $b += '<td>'+((guarantee_member_info[i]["identification_no"] != null)?guarantee_member_info[i]["identification_no"] : guarantee_member_info[i]["register_no"])+'</td>';
					        $b += '<td>'+((guarantee_member_info[i]["name"] != null)?guarantee_member_info[i]["name"] : guarantee_member_info[i]["company_name"])+'</td>';
					        $b += '<td style="text-align: center;">'+officer_address+'</td>';
					        $b += '<td style="text-align: center;">'+guarantee_member_info[i]["currency_name"]+'</td>';
					       	$b += '<td style="text-align:right">'+addCommas(guarantee_member_info[i]["guarantee"])+'</td>';
					       	$b += '<td style="text-align:right">'+addCommas(balance.toFixed(2))+'</td>';
					       	$b += '<td style="text-align: center;">'+mydate1+'</td>';

					        $b += '</tr>';

					        $(".register_guarantee_member_table").append($b);
					    }
					    $('#register_table_guarantee_member').DataTable({"paging": false,});
					    $('#register_guarantee_member .datatables-header').hide();
					    $('#register_guarantee_member .datatables-footer').hide();
					}
					else
	            	{
	            		$b=""; 
				        $b += '<tr class="guarantee_member_info_for_each_company">';
				        $b += '<td colspan="8" style="text-align:center;"><span style="font-weight:bold; font-size:20px;">N/A</span></td>';
				        $b += '</tr>';

				        $(".register_guarantee_member_table").append($b);
	            	}

	    //         	coll = document.getElementsByClassName("collapsible");

					// for (var g = 0; g < coll.length; g++) {
					//     coll[g].classList.toggle("incorp_active");
					//     coll[g].nextElementSibling.style.maxHeight = "100%";
					// }

					// for (var i = 0; i < coll.length; i++) {
					//   coll[i].addEventListener("click", function() {
					//     this.classList.toggle("incorp_active");
					//     var content = this.nextElementSibling;
					//     if (content.style.maxHeight){
					//       content.style.maxHeight = null;
					//     } else {
					//       content.style.maxHeight = "100%";
					//     } 
					//   });
					// }
            	}
            	
            	
            }

            if (response.register === "transfer" || response.register === "all") 
            {
            	if(response.register === "all")
            	{
            		if(response[7] != undefined)
            		{
            			var client_transfer = response[7]["client_transfer"];
            		}
            		else
            		{
            			var client_transfer = '';
            		}
            	}
            	else
            	{
            		if(response[0] != undefined)
            		{
            			var client_transfer = response[0]["client_transfer"];
            		}
            		else
            		{
            			var client_transfer = '';
            		}
            	}

            	//console.log(client_transfer);
            	var classOfShares = [];

            	$g = "";
            	$g += '<button type="button" class="register_collapsible"><span style="font-size: 2.4rem;">REGISTER OF TRANSFER</span></button><div class="register_content"><div id="register_transfer" style="margin-top:20px;">';
		        $("#register_table").append($g);

			    if(client_transfer.length > 0)
            	{
	            	for(var t = 0; t < client_transfer.length; t ++)
	            	{
	            		//console.log(classOfShares.indexOf(client_transfer[t]["sharetype"]));
	            		if(classOfShares.indexOf(client_transfer[t]["sharetype"]) == -1)
	            		{
	            			console.log(classOfShares.indexOf(client_transfer[t]["sharetype"]));
	            			var specificClassOfShares = [];
			            	$g = "";
						    $g += '<table class="table table-bordered mb-none" id="" style="width:100%;">';
			            	$g += '<tr><td style="font-weight: bold;"> Name of Company : '+response.client_name+' (UEN: '+response.uen+') <br/> Class of Shares: '+ client_transfer[t]["sharetype"];
			            	$g += '</td></tr>';
			            	$g += '</table>';
			            	//$g += '</div></div>';
			            	$("#register_transfer").append($g);

			            	var class_share_type = client_transfer[t]["sharetype"].replace(/\s/g,'');
			            	
			            	$a = '<div style="margin-top:20px;margin-bottom:20px;">';
			            	$a += '<table class="table table-bordered table-striped mb-none register_transfers_table '+class_share_type+'" id="register_table_transfers" style="width:100%;">';
							$a += '<thead>';
							$a += '<tr>'; 
							$a += '<th style="text-align:center;" rowspan="3">DATE</th>';
							$a += '<th style="text-align:center;" colspan="5">TRANSFEROR</th>';
							$a += '<th style="word-break:break-all;text-align:center;" colspan="3">TRANSFEREE</th>';
							$a += '</tr>';
							$a += '<tr>'; 
							$a += '<th style="text-align: center;" rowspan="2">Name & ADDRESS</th>'; 
							$a += '<th style="text-align: center;" colspan="2">OLD CERTIFICATE</th>';
							$a += '<th style="text-align: center;" colspan="2">BALANCE CERTIFICATE</th>';
							$a += '<th style="text-align: center;" rowspan="2">Name & ADDRESS</th>'; 
							$a += '<th style="text-align: center;" rowspan="2">No. of Shares<br />Transferred</th>'; 
							$a += '<th style="text-align: center;" rowspan="2">New Cert No.</th>'; 
							$a += '</tr>';
							$a += '<tr>'; 
							$a += '<th style="text-align: center;">No. of Shares</th>';
							$a += '<th style="text-align: center;">Cert No.</th>';
							$a += '<th style="text-align: center;">No. of Shares</th>';
							$a += '<th style="text-align: center;">Cert No.</th>';
							$a += '</tr>';
							$a += '</thead>';
							$a += '</table>';
							$a += '</div>';
							$("#register_transfer").append($a);

							classOfShares.push(client_transfer[t]["sharetype"]);
							specificClassOfShares.push(client_transfer[t]["sharetype"]);

							for(var g = 0; g < client_transfer.length; g ++)
	            			{
	            				if(specificClassOfShares.indexOf(client_transfer[g]["sharetype"]) > -1)
	            				{
	            					$b=""; 
							        $b += '<tr class="transfer_for_each_company">';
							        $b += '<td style="text-align: center;width:50px !important;">'+client_transfer[g]["date"]+'</td>';
							        $b += '<td style="text-align: center;">'+client_transfer[g]["transferor_name"]+'<br />'+client_transfer[g]["transferor_address"]+'</td>';
							        $b += '<td style="text-align: right;">'+addCommas(client_transfer[g]["old_number_share"])+'</td>';
							        $b += '<td style="text-align: center;">'+client_transfer[g]["old_cert"]+'</td>';
							        $b += '<td style="text-align: right;">'+addCommas(client_transfer[g]["balance_number_share"])+'</td>';
							        $b += '<td style="text-align: center;">'+client_transfer[g]["balance_cert"]+'</td>';
							        $b += '<td style="text-align: center;">'+client_transfer[g]["transferee_name"]+'<br />'+client_transfer[g]["transferee_address"]+'</td>';
							        $b += '<td style="text-align: right;">'+addCommas(client_transfer[g]["new_number_share"])+'</td>';
							        $b += '<td style="text-align: center">'+client_transfer[g]["new_cert"]+'</td>';
							        $b += '</tr>';

							        $("."+class_share_type+"").append($b);
	            				}
	            			}

						}
					}
					//console.log(classOfShares);
				}
				else
				{
					$z = "";
					$z += '<table class="table table-bordered table-striped register_transfers_table" id="register_table_transfers" style="width:100%; margin-bottom: 20px;">';
			        $z += '<tr>';
			        $z += '<td style="text-align:center; width: 100%;"><span style="font-weight:bold; font-size:20px;">N/A</span></td>';
			        $z += '</tr></table>';

			        $("#register_transfer").append($z);
				}
            }

            if (response.register === "controller" || response.register === "all") 
            {
            	if(response.register === "all")
            	{
            		if(response[5] != undefined)
            		{
            			var client_controller = response[5]["client_controller"];
            		}
            		else
            		{
            			var client_controller = '';
            		}
            	}
            	else
            	{
            		if(response[0] != undefined)
            		{
            			var client_controller = response[0]["client_controller"];
            		}
            		else
            		{
            			var client_controller = '';
            		}
            	}

    //         	$a = "";
    //         	$a += '<button type="button" class="register_collapsible"><span style="font-size: 2.4rem;">REGISTER OF CONTROLLER</span></button><div class="register_content"><div id="register_controller" style="margin-top:20px; margin-bottom: 20px;">';
    //         	$a += '<table class="table table-bordered table-striped mb-none register_controller_table" id="register_table_controller">';
				// $a += '<thead><tr>'; 
				// $a += '<th style="width:50px !important;text-align:center">No</th>';
				// $a += '<th style="text-align: center">ID/UEN</th>'; 
				// $a += '<th style="text-align: center; width:200px">Name</th>';
				// $a += '<th style="text-align: center">Date of Birth/Incorporation</th>';
				// $a += '<th style="text-align: center">Nationality/Country of Incorporation</th>';  
				// $a += '<th style="text-align: center">Address</th>'; 
				// $a += '<th style="text-align: center">Date of Registration</th>'; 
				// $a += '<th style="text-align: center">Date of notice</th>'; 
				// $a += '<th style="text-align: center">Confirmation received date</th>'; 
				// $a += '<th style="text-align: center">Date of entry</th>'; 
				// $a += '<th style="text-align: center">Date Of Cessation</th>';
				// $a += '</tr></thead>';
				// $a += '</table>';
				// $a += '</div></div>';
				// $("#register_table").append($a);

    //         	if(client_controller.length > 0)
    //         	{
				// 	for(var i = 0; i < client_controller.length; i++)
				//     {
				//     	var parts =client_controller[i]["date_of_registration"].split('/');
				// 		var date = parts[2]+"/"+parts[1]+"/"+parts[0];
				// 		var mydate1 = $.datepicker.formatDate('dd M yy',new Date(date)); 

				// 		if(client_controller[i]["date_of_cessation"] != "")
				// 		{
				// 			var parts =client_controller[i]["date_of_cessation"].split('/');
				// 			var date = parts[2]+"/"+parts[1]+"/"+parts[0];
				// 			var mydate2 = $.datepicker.formatDate('dd M yy',new Date(date)); 
				// 		}
				// 		else
				// 		{
				// 			var mydate2 = "";
				// 		}

				// 		if(client_controller[i]["date_of_notice"] != "")
				// 		{
				// 			var parts =client_controller[i]["date_of_notice"].split('/');
				// 			var date = parts[2]+"/"+parts[1]+"/"+parts[0];
				// 			var mydate4 = $.datepicker.formatDate('dd M yy',new Date(date)); 
				// 		}
				// 		else
				// 		{
				// 			var mydate4 = "";
				// 		}

				// 		if(client_controller[i]["confirmation_received_date"] != "")
				// 		{
				// 			var parts =client_controller[i]["confirmation_received_date"].split('/');
				// 			var date = parts[2]+"/"+parts[1]+"/"+parts[0];
				// 			var mydate5 = $.datepicker.formatDate('dd M yy',new Date(date)); 
				// 		}
				// 		else
				// 		{
				// 			var mydate5 = "";
				// 		}

				// 		if(client_controller[i]["date_of_entry"] != "")
				// 		{
				// 			var parts =client_controller[i]["date_of_entry"].split('/');
				// 			var date = parts[2]+"/"+parts[1]+"/"+parts[0];
				// 			var mydate6 = $.datepicker.formatDate('dd M yy',new Date(date)); 
				// 		}
				// 		else
				// 		{
				// 			var mydate6 = "";
				// 		}

				// 		if(client_controller[i]["date_of_birth"] != "")
				// 		{
				// 			var parts =client_controller[i]["date_of_birth"].split('/');
				// 			var date = parts[2]+"/"+parts[1]+"/"+parts[0];
				// 			var mydate3 = $.datepicker.formatDate('dd M yy',new Date(date)); 
				// 		}
				// 		else
				// 		{
				// 			var mydate3 = "";
				// 		}

				//         $b=""; 
				//         $b += '<tr class="filing_info_for_each_company">';
				//         $b += '<td style="text-align: right">'+(i+1)+'</td>';
				//         $b += '<td>'+((client_controller[i]["identification_no"] != null)?client_controller[i]["identification_no"] : (client_controller[i]["register_no"] != null)?client_controller[i]["register_no"]:client_controller[i]["registration_no"])+'</td>';
				//         $b += '<td>'+((client_controller[i]["name"] != null)?client_controller[i]["name"] : (client_controller[i]["company_name"] != null)?client_controller[i]["company_name"]:client_controller[i]["client_company_name"])+'</td>';
				//         $b += '<td style="text-align:center">'+mydate3+'</td>';
				//         if(client_controller[i]["nationality_name"] != null)
				//         {
				//         	$b += '<td style="text-align:center">'+client_controller[i]["nationality_name"]+'</td>';
				//         }
				//         else
				//         {
				//         	$b += '<td style="text-align:center"></td>';
				//         }
				//         $b += '<td style="text-align:center">'+client_controller[i]["address"]+'</td>';
				//         $b += '<td style="text-align:center">'+mydate1+'</td>';
				//         $b += '<td style="text-align:center">'+mydate4+'</td>';
				//         $b += '<td style="text-align:center">'+mydate5+'</td>';
				//         $b += '<td style="text-align:center">'+mydate6+'</td>';
				//         $b += '<td style="text-align:center">'+mydate2+'</td>';
				//         $b += '</tr>';

				//         $(".register_controller_table").append($b);

				        
				//     }
				//     $('#register_table_controller').DataTable({"paging": false,});
				//     $('#register_controller .datatables-header').hide();
				//     $('#register_controller .datatables-footer').hide();

    //         	}
    //         	else
    //         	{
    //         		$z=""; 
			 //        $z += '<tr class="filing_info_for_each_company">';
			 //        $z += '<td colspan="11" style="text-align:center;"><span style="font-weight:bold; font-size:20px;">N/A</span></td>';
			 //        $z += '</tr>';

			 //        $(".register_controller_table").append($z);
    //         	}

    			$a = "";
            	$a += '<button type="button" class="register_collapsible"><span style="font-size: 2.4rem;">REGISTER OF CONTROLLER</span></button><div class="register_content"><div id="register_controller" style="margin-top:20px; margin-bottom: 20px;">';
            	$a += '<table class="table table-bordered table-striped mb-none register_controller_table" id="register_table_controller">';
				$a += '<thead><tr>'; 
				$a += '<th style="width:50px !important;text-align:center">Notice Sent</th>';
				$a += '<th style="text-align: center">Confirmation Received</th>';
				$a += '<th style="text-align: center">Date of entry/update</th>';
				$a += '<th style="text-align: center">Controller Particular</th>';  
				$a += '<th style="text-align: center">Supporting Docs</th>';
				$a += '</tr></thead>';
				$a += '</table>';
				$a += '</div></div>';
				$("#register_table").append($a);


			    if(client_controller.length > 0)
			    {
			        for(var i = 0; i < client_controller.length; i++)
			        {
			            var selected_controller = client_controller[i];

			            if(selected_controller)
			            {
			                var full_address;

			                if(selected_controller["supporting_document"] != "")
			                {
			                    var file_result = JSON.parse(selected_controller["supporting_document"]);
			                }

			                if(selected_controller["client_controller_field_type"] == "individual")
			                {
			                    // if(selected_controller["alternate_address"] == 1)
			                    // {
			                    //     full_address = address_format (selected_controller["postal_code2"], selected_controller["street_name2"], selected_controller["building_name2"], selected_controller["unit_no3"], selected_controller["unit_no4"]);
			                    // }
			                    // else
			                    // {
			                        full_address = address_format (selected_controller["postal_code1"], selected_controller["street_name1"], selected_controller["building_name1"], selected_controller["officer_unit_no1"], selected_controller["officer_unit_no2"], selected_controller["foreign_address1"], selected_controller["foreign_address2"], selected_controller["foreign_address3"]);
			                    //}
			                }
			                else if(selected_controller["client_controller_field_type"] == "company")
			                {
			                    full_address = address_format (selected_controller["company_postal_code"], selected_controller["company_street_name"], selected_controller["company_building_name"], selected_controller["company_unit_no1"], selected_controller["company_unit_no2"], selected_controller["company_foreign_address1"], selected_controller["company_foreign_address2"], selected_controller["company_foreign_address3"]);
			                }
			                else if(selected_controller["client_controller_field_type"] == "client")
			                {
			                    full_address = address_format (selected_controller["postal_code"], selected_controller["street_name"], selected_controller["building_name"], selected_controller["client_unit_no1"], selected_controller["client_unit_no2"], selected_controller["foreign_add_1"], selected_controller["foreign_add_2"], selected_controller["foreign_add_3"]);
			                }

			                if(selected_controller["date_of_notice"] != "")
			                {
			                    var date_of_notice = formatDateROCFunc(new Date(changeROCDateFormat(selected_controller["date_of_notice"])));
			                }
			                else
			                {
			                    var date_of_notice = "";
			                }

			                if(selected_controller["confirmation_received_date"] != "")
			                {
			                    var confirmation_received_date = formatDateROCFunc(new Date(changeROCDateFormat(selected_controller["confirmation_received_date"])));
			                }
			                else
			                {
			                    var confirmation_received_date = "";
			                }

			                if(selected_controller["date_of_entry"] != "")
			                {
			                    var date_of_entry = formatDateROCFunc(new Date(changeROCDateFormat(selected_controller["date_of_entry"])));
			                }
			                else
			                {
			                    var date_of_entry = "";
                			}

			                $b = '';
			                $b += '<tr class="tr_controller_table">';
			                $b += '<td style="text-align: center">'+selected_controller["date_of_notice"]+'</td>';
			                $b += '<td style="text-align: center">'+selected_controller["confirmation_received_date"]+'</td>'; 
			                $b += '<td style="text-align: center">'+selected_controller["date_of_entry"]+'</td>';
			                // if(selected_controller["client_controller_field_type"] == "individual")
			                // {
			                //     $b += '<td><b>Full name:</b> '+ selected_controller["name"] +'<br/><b>Alias:</b> '+selected_controller["alias"]+'<br/><b>Residential address:</b> '+full_address+'<br/><b>Nationality:</b> '+selected_controller["officer_nationality_name"]+'<br/><b>Identification card number:</b> '+ selected_controller["identification_no"] +'<br/><b>Date of birth:</b> '+selected_controller["date_of_birth"] +'<br/><b>Date of becoming a controller:</b> '+selected_controller["date_of_registration"]+'<br/><b>Date of cessation:</b>'+selected_controller["date_of_cessation"]+'</td>';
			                // }
			                // else
			                // {
			                //     $b += '<td><b>Name:</b> '+ (selected_controller["officer_company_company_name"]!=null ? selected_controller["officer_company_company_name"] : selected_controller["client_company_name"]) + '<br/><b>Unique entity number issued by the Registrar:</b>'+ (selected_controller["entity_issued_by_registrar"]!=null?selected_controller["entity_issued_by_registrar"]:"")+'<br/><b>Address of registered office:</b> '+full_address+ '<br/><b>Legal form:</b>'+ (selected_controller["legal_form_entity"]!=null?selected_controller["legal_form_entity"]:"") +'<br/><b>Jurisdiction where and statute under which the registrable corporate controller is formed or incorporated:</b> '+(selected_controller["country_of_incorporation"]!=null?selected_controller["country_of_incorporation"]:"") + (selected_controller["statutes_of"] != null ? ', ' + selected_controller["statutes_of"] : '') + '<br/><b>Name of the corporate entity register of the jurisdiction where the registrable corporate controller is formed or incorporated:</b>' + (selected_controller["coporate_entity_name"]!=null?selected_controller["coporate_entity_name"]:"") +'<br/><b>Identification number or registration number on the corporate entity register of the jurisdiction where the registrable corporate controller is formed or incorporated:</b> '+ (selected_controller["register_no"] != null ? selected_controller["register_no"] : selected_controller["registration_no"]) +'<br/><b>Date of Incorpodation:</b> '+(selected_controller["date_of_incorporation"] != null ? selected_controller["date_of_incorporation"] : selected_controller["incorporation_date"]) +'<br/><b>Date of becoming a controller:</b> '+selected_controller["date_of_registration"]+'<br/><b>Date of cessation:</b>'+selected_controller["date_of_cessation"]+'</td>';
			                // }

			                if(selected_controller["client_controller_field_type"] == "individual")
			                {
			                    var date_of_birth = formatDateROCFunc(new Date(selected_controller["date_of_birth"]));
			                    var date_of_registration = formatDateROCFunc(new Date(changeROCDateFormat(selected_controller["date_of_registration"])));
			                    if(selected_controller["date_of_cessation"] != "")
			                    {
			                        var date_of_cessation = formatDateROCFunc(new Date(changeROCDateFormat(selected_controller["date_of_cessation"])));
			                    }
			                    else
			                    {
			                        var date_of_cessation = "";
			                    }

			                    $b += '<td><b>Full name:</b> '+ selected_controller["name"] +'<br/><b>Alias:</b> '+selected_controller["alias"]+'<br/><b>Residential address:</b> '+full_address+'<br/><b>Nationality: </b> '+selected_controller["officer_nationality_name"]+'<br/><b>Identification card number:</b> '+ selected_controller["identification_no"] +'<br/><b>Date of birth: </b> '+date_of_birth +'<br/><b>Date of becoming a controller: </b> '+date_of_registration+'<br/><b>Date of cessation:</b>'+date_of_cessation+'</td>';
			                }
			                else
			                {
			                    //var date_of_birth = formatDateFunc(new Date(selected_controller["date_of_birth"]));
			                    var date_of_incorporation = (selected_controller["date_of_incorporation"] != null ? selected_controller["date_of_incorporation"] : (selected_controller["incorporation_date"] != null)?changeROCDateFormat(selected_controller["incorporation_date"]) : "");
			                    if(date_of_incorporation != "")
			                    {
			                        var old_date_of_incorporation = new Date(date_of_incorporation);
			                        var date_of_incorporation = formatDateROCFunc(old_date_of_incorporation);
			                    }
			                    var date_of_registration = formatDateROCFunc(new Date(changeROCDateFormat(selected_controller["date_of_registration"])));
			                    if(selected_controller["date_of_cessation"] != "")
			                    {
			                        var date_of_cessation = formatDateROCFunc(new Date(changeROCDateFormat(selected_controller["date_of_cessation"])));
			                    }
			                    else
			                    {
			                        var date_of_cessation = "";
			                    }
			                    $b += '<td><b>Name:</b> '+ (selected_controller["officer_company_company_name"]!=null ? selected_controller["officer_company_company_name"] : selected_controller["client_company_name"]) + '<br/><b>Unique entity number issued by the Registrar: </b>'+ (selected_controller["entity_issued_by_registrar"]!=null?selected_controller["entity_issued_by_registrar"]:"")+'<br/><b>Address of registered office: </b> '+full_address+ '<br/><b>Legal form: </b>'+ (selected_controller["legal_form_entity"]!=null?selected_controller["legal_form_entity"]:selected_controller["client_company_type"]!=null?selected_controller["client_company_type"]:"") +'<br/><b>Jurisdiction where and statute under which the registrable corporate controller is formed or incorporated: </b> '+(selected_controller["country_of_incorporation"]!=null?selected_controller["country_of_incorporation"]:selected_controller["client_country_of_incorporation"]!=null?selected_controller["client_country_of_incorporation"]:"") + (selected_controller["statutes_of"] != null ? ', ' + selected_controller["statutes_of"] : selected_controller["client_statutes_of"] != null ? ', ' + selected_controller["client_statutes_of"] : '') + '<br/><b>Name of the corporate entity register of the jurisdiction where the registrable corporate controller is formed or incorporated: </b>' + (selected_controller["coporate_entity_name"]!=null?selected_controller["coporate_entity_name"]:selected_controller["client_coporate_entity_name"]!=null?selected_controller["client_coporate_entity_name"]:"") +'<br/><b>Identification number or registration number on the corporate entity register of the jurisdiction where the registrable corporate controller is formed or incorporated: </b> '+ (selected_controller["register_no"] != null ? selected_controller["register_no"] : selected_controller["registration_no"]) +'<br/><b>Date of Incorporation:</b> '+ date_of_incorporation +'<br/><b>Date of becoming a controller:</b> '+date_of_registration+'<br/><b>Date of cessation:</b>'+date_of_cessation+'</td>';
			                }
			                if(selected_controller["supporting_document"] != "" && selected_controller["supporting_document"] != "[]")
			                {
			                    $b += '<td><a href="'+url+'uploads/supporting_doc/'+file_result[0]+'" target="_blank">'+file_result[0]+'</a></td>';
			                }
			                else
			                {
			                    $b += '<td></td>';
			                }
			                //$b += '<td><input type="hidden" id="client_controller_id" value="'+selected_controller["client_controller_id"]+'" name="client_controller_id"/><input type="hidden" id="client_controller_name" value="'+(selected_controller["name"]!=null ? selected_controller["name"] : selected_controller["officer_company_company_name"]!=null ? selected_controller["officer_company_company_name"] : selected_controller["client_company_name"])+'" name="client_controller_name"/><input type="hidden" id="company_code" value="'+selected_controller["client_controller_company_code"]+'" name="company_code"/><button type="button" class="btn btn-primary" onclick="delete_controller(this);">Delete</button></td>';
			                $b += '</tr>';

			                $(".register_controller_table").append($b);
			            }
			        }
			        $('#register_table_controller').DataTable({"paging": false,});
				    $('#register_controller .datatables-header').hide();
				    $('#register_controller .datatables-footer').hide();
			    }
			    else
            	{
            		$z=""; 
			        $z += '<tr class="filing_info_for_each_company">';
			        $z += '<td colspan="5" style="text-align:center;"><span style="font-weight:bold; font-size:20px;">N/A</span></td>';
			        $z += '</tr>';

			        $(".register_controller_table").append($z);
            	}
            }

            if (response.register === "nominee_director" || response.register === "all") 
            {
            	if(response.register === "all")
            	{
            		if(response[6] != undefined)
            		{
            			var client_nominee_director = response[6]["client_nominee_director"];
            		}
            		else
            		{
            			var client_nominee_director = '';
            		}
            	}
            	else
            	{
            		if(response[0] != undefined)
            		{
            			var client_nominee_director = response[0]["client_nominee_director"];
            		}
            		else
            		{
            			var client_nominee_director = '';
            		}
            	}

            	$a = "";
            	$a += '<button type="button" class="register_collapsible"><span style="font-size: 2.4rem;">REGISTER OF NOMINEE DIRECTOR</span></button><div class="register_content"><div id="register_nominee_director" style="margin-top:20px; margin-bottom:20px;">';
            	$a += '<table class="table table-bordered table-striped mb-none register_nominee_director_table" id="register_table_nominee_director">';
				$a += '<thead><tr>'; 
				$a += '<th style="width:50px !important;text-align:center">Date of entry/update</th>';
				$a += '<th style="text-align: center">Name of Nominee Director</th>'; 
				$a += '<th style="text-align: center">Particulars of nominator</th>'; 
				$a += '<th style="text-align: center">Supporting Docs</th>';
				$a += '</tr></thead>';
				$a += '</table>';
				$a += '</div></div>';
				$("#register_table").append($a);
				
            	if(client_nominee_director.length > 0)
            	{
					for(var i = 0; i < client_nominee_director.length; i++)
				    {
				    	var selected_nominee_director = client_nominee_director[i];

			            if(selected_nominee_director)
			            {
			                var full_address;

			                if(selected_nominee_director["supporting_document"] != "")
			                {
			                    var file_result = JSON.parse(selected_nominee_director["supporting_document"]);
			                }

			                if(selected_nominee_director["nomi_officer_field_type"] == "individual")
			                {
			                    full_address = address_format (selected_nominee_director["postal_code1"], selected_nominee_director["street_name1"], selected_nominee_director["building_name1"], selected_nominee_director["nomi_officer_unit_no1"], selected_nominee_director["nomi_officer_unit_no1"], selected_nominee_director["foreign_address1"], selected_nominee_director["foreign_address2"], selected_nominee_director["foreign_address3"]);
			                }
			                else if(selected_nominee_director["nomi_officer_field_type"] == "company")
			                {
			                    full_address = address_format (selected_nominee_director["company_postal_code"], selected_nominee_director["company_street_name"], selected_nominee_director["company_building_name"], selected_nominee_director["company_unit_no1"], selected_nominee_director["company_unit_no2"], selected_nominee_director["company_foreign_address1"], selected_nominee_director["company_foreign_address2"], selected_nominee_director["company_foreign_address3"]);
			                }
			                else if(selected_nominee_director["nomi_officer_field_type"] == "client")
			                {
			                   full_address = address_format (selected_nominee_director["postal_code"], selected_nominee_director["street_name"], selected_nominee_director["building_name"], selected_nominee_director["client_unit_no1"], selected_nominee_director["client_unit_no2"], selected_nominee_director["foreign_add_1"], selected_nominee_director["foreign_add_2"], selected_nominee_director["foreign_add_3"]);
			                }

			                if(selected_nominee_director["nd_date_entry"] != "")
			                {
			                    var nd_date_entry = formatDateRONDFunc(new Date(changeRONDDateFormat(selected_nominee_director["nd_date_entry"])));
			                }
			                else
			                {
			                    var nd_date_entry = "";
			                }

			                $b = '';
			                $b += '<tr class="register_info_for_each_company">';
			                $b += '<td style="text-align: center">'+nd_date_entry+'</td>';
			                $b += '<td>'+selected_nominee_director["nd_officer_name"]+'</td>';
			                
			                if(selected_nominee_director["nomi_officer_field_type"] == "individual")
			                {
			                    var date_of_birth = formatDateRONDFunc(new Date(selected_nominee_director["date_of_birth"]));
			                    var date_become_nominator = formatDateRONDFunc(new Date(changeRONDDateFormat(selected_nominee_director["date_become_nominator"])));
			                    if(selected_nominee_director["date_of_cessation"] != "")
			                    {
			                        var date_of_cessation = formatDateRONDFunc(new Date(changeRONDDateFormat(selected_nominee_director["date_of_cessation"])));
			                    }
			                    else
			                    {
			                        var date_of_cessation = "";
			                    }
			                    $b += '<td><b>Full name:</b> '+ selected_nominee_director["name"] +'<br/><b>Alias:</b> '+selected_nominee_director["alias"]+'<br/><b>Residential address:</b> '+full_address+'<br/><b>Nationality:</b> '+selected_nominee_director["nomi_officer_nationality_name"]+'<br/><b>Identification card number:</b> '+ selected_nominee_director["identification_no"] +'<br/><b>Date of birth:</b> '+date_of_birth+'<br/><b>Date on which the person becomes a nominator:</b> '+date_become_nominator+'<br/><b>Date of cessation:</b>'+date_of_cessation+'</td>';
			                }
			                else
			                {
			                    var date_of_incorporation = (selected_nominee_director["date_of_incorporation"] != null ? selected_nominee_director["date_of_incorporation"] : (selected_nominee_director["incorporation_date"] != null)?changeRONDDateFormat(selected_nominee_director["incorporation_date"]) : "");
			                    if(date_of_incorporation != "")
			                    {
			                        var old_date_of_incorporation = new Date(date_of_incorporation);
			                        var date_of_incorporation = formatDateRONDFunc(old_date_of_incorporation);
			                    }
			                    var date_become_nominator = formatDateRONDFunc(new Date(changeRONDDateFormat(selected_nominee_director["date_become_nominator"])));
			                    if(selected_nominee_director["date_of_cessation"] != "")
			                    {
			                        var date_of_cessation = formatDateRONDFunc(new Date(changeRONDDateFormat(selected_nominee_director["date_of_cessation"])));
			                    }
			                    else
			                    {
			                        var date_of_cessation = "";
			                    }
			                    $b += '<td><b>Name:</b> '+ (selected_nominee_director["officer_company_company_name"]!=null ? selected_nominee_director["officer_company_company_name"] : selected_nominee_director["client_company_name"]) + '<br/><b>Unique entity number issued by the Registrar:</b>'+ (selected_nominee_director["entity_issued_by_registrar"]!=null?selected_nominee_director["entity_issued_by_registrar"]:"")+'<br/><b>Address of registered office:</b> '+full_address+ '<br/><b>Legal form:</b>'+ (selected_nominee_director["legal_form_entity"] != null?selected_nominee_director["legal_form_entity"]:selected_nominee_director["client_company_type"] != null?selected_nominee_director["client_company_type"]:"") +'<br/><b>Jurisdiction where and statute under which the registrable corporate controller is formed or incorporated:</b> '+(selected_nominee_director["country_of_incorporation"]!=null?selected_nominee_director["country_of_incorporation"]:selected_nominee_director["client_country_of_incorporation"]!=null?selected_nominee_director["client_country_of_incorporation"]:"") + (selected_nominee_director["statutes_of"] != null ? ', ' + selected_nominee_director["statutes_of"] : selected_nominee_director["client_statutes_of"] != null ? ', ' + selected_nominee_director["client_statutes_of"] : '') + '<br/><b>Name of the corporate entity register of the jurisdiction where the registrable corporate controller is formed or incorporated:</b>' + (selected_nominee_director["coporate_entity_name"]!=null?selected_nominee_director["coporate_entity_name"]:selected_nominee_director["client_coporate_entity_name"]!=null?selected_nominee_director["client_coporate_entity_name"]:"") +'<br/><b>Identification number or registration number on the corporate entity register of the jurisdiction where the registrable corporate controller is formed or incorporated:</b> '+ (selected_nominee_director["register_no"] != null ? selected_nominee_director["register_no"] : selected_nominee_director["registration_no"]) +'<br/><b>Date of Incorporation:</b> '+ date_of_incorporation +'<br/><b>Date of becoming a controller:</b> '+date_become_nominator+'<br/><b>Date of cessation:</b>'+date_of_cessation+'</td>';
			                }

			                if(selected_nominee_director["supporting_document"] != "" && selected_nominee_director["supporting_document"] != "[]")
			                {
			                    $b += '<td><a href="'+url+'uploads/supporting_doc/'+file_result[0]+'" target="_blank">'+file_result[0]+'</a></td>';
			                }
			                else
			                {
			                    $b += '<td></td>';
			                }
			                $b += '</tr>';

			                $(".register_nominee_director_table").append($b);
			            }
				    }
            	}
            	else
            	{
            		$z=""; 
			        $z += '<tr class="filing_info_for_each_company">';
			        $z += '<td colspan="4" style="text-align:center;"><span style="font-weight:bold; font-size:20px;">N/A</span></td>';
			        $z += '</tr>';

			        $(".register_nominee_director_table").append($z);
            	}
            }
            
            if (response.register === "charges" || response.register === "all") 
            {	
            	if(response.register === "all")
            	{
            		if(response[3] != undefined)
            		{
            			var charges_info = response[3]["charges"];
            		}
            		else
            		{
            			var charges_info = '';
            		}
            	}
            	else
            	{
            		if(response[0] != undefined)
            		{
            			var charges_info = response[0]["charges"];
            		}
            		else
            		{
            			var charges_info = '';
            		}
            	}

            	$a = "";
            	$a += '<button type="button" class="register_collapsible"><span style="font-size: 2.4rem;">REGISTER OF CHARGES</span></button><div class="register_content"><div id="register_charges" style="display:block;padding-top:20px;margin-bottom: 20px;">';
            	$a += '<table class="table table-bordered table-striped mb-none register_charges_table" id="register_table_charges" style="width:100%;">';
				$a += '<thead>';
				$a += '<tr style="">'; 
				$a += '<th style="text-align:center;width:10px !important;padding-right:2px !important;padding-left:2px !important;" rowspan="2">No</th>';
				$a += '<th style="text-align:center;width:100px !important;padding-right:0px !important;padding-left:0px !important;" colspan="2">Charge</th>';
				$a += '<th style="word-break:break-all;width:100px !important;text-align:center;" colspan="2">Registration of<br> Charge</th>';
				$a += '<th style="word-break:break-all;width:100px !important;text-align: center;" colspan="2">Satisfactory of<br> Charge</th>'; 
				$a += '<th style="text-align: center;width:50px !important;padding-right:0px !important;padding-left:0px !important; vertical-align: bottom !important;" rowspan="2">CCY</th>'; 
				$a += '<th style="text-align: center;width:100px !important;padding-right:0px !important;padding-left:0px !important; vertical-align: bottom !important;" rowspan="2">Amount</th>';
				$a += '<th style="text-align: center;width:150px !important; vertical-align: bottom !important;" rowspan="2">Secured by</th>';
				$a += '</tr>';
				$a += '<tr style="">'; 
				$a += '<th style="text-align: center;width:50px !important;padding-right:2px !important;padding-left:2px !important;">Name</th>'; 
				$a += '<th style="text-align: center;width:50px !important;padding-right:2px !important;padding-left:2px !important;">Nature</th>';
				$a += '<th style="text-align: center;width:50px !important;padding-right:0px !important;padding-left:0px !important;">Date</th>';
				$a += '<th style="text-align: center;width:50px !important;padding-right:0px !important;padding-left:0px !important;">No</th>';
				$a += '<th style="text-align: center;width:50px !important;padding-right:2px !important;padding-left:2px !important;">Date</th>';
				$a += '<th style="text-align: center;width:50px !important;padding-right:2px !important;padding-left:2px !important;">No</th>';
				$a += '</tr>';
				$a += '</thead>';
				$a += '</table>';
				$a += '</div></div>';
				$("#register_table").append($a);

				if(charges_info.length > 0)
				{
					for(var i = 0; i < charges_info.length; i++)
				    {
				    	if(charges_info[i]["date_registration"] != "")
						{
					    	var parts =charges_info[i]["date_registration"].split('/');
							var date = parts[2]+"/"+parts[1]+"/"+parts[0];
							var mydate1 = $.datepicker.formatDate('dd M yy',new Date(date)); 
						}
						else
						{
							var mydate1 = "";
						}

						if(charges_info[i]["date_satisfied"] != "")
						{
							var parts =charges_info[i]["date_satisfied"].split('/');
							var date = parts[2]+"/"+parts[1]+"/"+parts[0];
							var mydate2 = $.datepicker.formatDate('dd M yy',new Date(date)); 
						}
						else
						{
							var mydate2 = "";
						}

				        $b=""; 
				        $b += '<tr class="filing_info_for_each_company">';
				        $b += '<td style="text-align: right">'+(i+1)+'</td>';
				        $b += '<td style="word-break:break-all;text-align: center;padding-right:2px !important;padding-left:2px !important;">'+charges_info[i]["charge"]+'</td>';
				        $b += '<td style="word-break:break-all;text-align: center;padding-right:2px !important;padding-left:2px !important;">'+charges_info[i]["nature_of_charge"]+'</td>';
				        $b += '<td style="text-align: center;padding-right:2px !important;padding-left:2px !important;">'+mydate1+'</td>';
				        $b += '<td style="text-align: center;padding-right:2px !important;padding-left:2px !important;">'+charges_info[i]["charge_no"]+'</td>';
				        $b += '<td style="text-align: center;padding-right:2px !important;padding-left:2px !important;">'+mydate2+'</td>';
				        $b += '<td style="text-align: center;padding-right:2px !important;padding-left:2px !important;">'+charges_info[i]["satisfactory_no"]+'</td>';
				        $b += '<td style="text-align: center;padding-right:2px !important;padding-left:2px !important;">'+charges_info[i]["currency_name"]+'</td>';
				    	$b += '<td style="text-align: right;padding-left:2px !important;">'+addCommas(charges_info[i]["amount"])+'</td>';
				    	$b += '<td style="word-break:break-all;">'+charges_info[i]["secured_by"]+'</td>';
				        $b += '</tr>';

				        $(".register_charges_table").append($b);
				    }
				    $('#register_table_charges').DataTable({"paging": false,});
				    $('#register_charges .datatables-header').hide();
				    $('#register_charges .datatables-footer').hide();
				}
				else
            	{
            		$b=""; 
			        $b += '<tr class="filing_info_for_each_company">';
			        $b += '<td colspan="10" style="text-align:center;"><span style="font-weight:bold; font-size:20px;">N/A</span></td>';
			        $b += '</tr>';

			        $(".register_charges_table").append($b);
            	}
            }
            if (response.register === "filing" || response.register === "all") 
            {
            	if(response.register === "all")
            	{
            		if(response[4] != undefined)
            		{
            			var filing_info = response[4]["filing_data"];
            		}
            		else
            		{
            			var filing_info = '';
            		}
            	}
            	else
            	{
            		if(response[0] != undefined)
            		{
            			var filing_info = response[0]["filing_data"];
            		}
            		else
            		{
            			var filing_info = '';
            		}
            	}

        		$a = "";
            	$a += '<button type="button" class="register_collapsible"><span style="font-size: 2.4rem;">ACRA FILING RECORDS</span></button><div class="register_content"><div id="register_filing" style="margin-top:20px; margin-bottom: 20px;">';
            	$a += '<table class="table table-bordered table-striped mb-none register_filing_table" id="register_table_filing">';
				$a += '<thead><tr>'; 
				$a += '<th style="width:50px !important;text-align: center">No</th>';
				$a += '<th style="text-align: center">Year End</th>'; 
				$a += '<th style="text-align: center">AGM</th>';
				$a += '<th style="text-align: center">AR Filing Date</th>';
				$a += '</tr></thead>';
				$a += '</table>';
				$a += '</div></div>';
				$("#register_table").append($a);

				if(filing_info.length > 0)
				{
	            	for(var i = 0; i < filing_info.length; i++)
				    {
				        $b=""; 
				        $b += '<tr class="filing_info_for_each_company">';
				        $b += '<td style="text-align: right">'+(i+1)+'</td>';
				        $b += '<td style="text-align:center">'+filing_info[i]["year_end"]+'</td>';
				        $b += '<td style="text-align:center">'+filing_info[i]["agm"]+'</td>';
				        $b += '<td style="text-align:center">'+filing_info[i]["ar_filing_date"]+'</td>';
				        $b += '</tr>';

				        $(".register_filing_table").append($b);
				    }
				    $('#register_table_filing').DataTable({"paging": false,});
				    $('#register_filing .datatables-header').hide();
				    $('#register_filing .datatables-footer').hide();

				}
				else
            	{
            		$b=""; 
			        $b += '<tr class="filing_info_for_each_company">';
			        $b += '<td colspan="6" style="text-align:center;"><span style="font-weight:bold; font-size:20px;">N/A</span></td>';
			        $b += '</tr>';

			        $(".register_filing_table").append($b);
            	}
            }
            
            $('#register_table .dataTables_filter').hide();

            coll = document.getElementsByClassName("register_collapsible");

			for (var g = 0; g < coll.length; g++) {
			    coll[g].classList.toggle("register_active");
			    coll[g].nextElementSibling.style.maxHeight = "100%";
			}

			for (var i = 0; i < coll.length; i++) {
			  coll[i].addEventListener("click", function() {
			    this.classList.toggle("register_active");
			    var content = this.nextElementSibling;
			    if (content.style.maxHeight){
			      content.style.maxHeight = null;
			    } else {
			      content.style.maxHeight = "100%";
			    } 
			  });
			}
        }
	});
}  


function address_format (postal_code, street_name, building_name, unit_no1, unit_no2, foreign_add_1 = null, foreign_add_2 = null, foreign_add_3 = null)
{
    if(postal_code != "" && street_name != "")
    {
        var units = "", buildingName = "";

        if(unit_no1 != "" || unit_no2 != "")
        {
            units = ', #'+unit_no1 + " - " + unit_no2;
        }
        else if(unit_no1 != "")
        {
            units = unit_no1+' ';
        }
        else if(unit_no2 != "")
        {
            units = unit_no2+' ';
        }
        else
        {
            units = '';
        }
        if(building_name != "")
        {
            if(units == '')
            {
                buildingName = ', ' + building_name;
            }
            else
            {
                buildingName = ' ' + building_name;
            }
        }
        else
        {
            buildingName = '';
        }
        var address = street_name+units+buildingName+', SINGAPORE '+postal_code;
    }
    else if(foreign_add_1 != null )
    {
        var address = foreign_add_1;

        if(foreign_add_2 != "")
        {
            address = address + ', ' + foreign_add_2;
        }

        if(foreign_add_3 != "")
        {
            address = address + ', ' + foreign_add_3;
        }
    }

    return address;
}
