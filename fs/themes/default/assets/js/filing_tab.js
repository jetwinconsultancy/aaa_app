var pathArray = location.href.split( '/' );
var protocol = pathArray[0];
var host = pathArray[2];
var folder = pathArray[3]
var url = protocol + '//' + host + '/' + folder + '/';
var edit_filing_id, bool_dispense_agm = true;
var first_filing_id = "";

filing_history_table(filing_data);
// if(!filing_data)
// {
// 	check_due_date_175();
// }


function filing_history_table(filing)
{	
	if(filing)
	{
		console.log(filing);
		$(".filing_info_for_each_company").remove();
		var number_of_length = parseInt(filing.length) - 1;
		edit_filing_id = filing[number_of_length]["id"];
		$(".filing_id").val(filing[number_of_length]["id"]);
		$("#year_end_date").val(filing[number_of_length]["year_end"]);
		$("#year_end_date").prop('disabled', true);
		$("#agm_date").val(filing[number_of_length]["agm"]);
		$("#ar_filing_date").val(filing[number_of_length]["ar_filing_date"]);
		get_financial_year_period(filing[number_of_length]["financial_year_period_id"]);
		if(new Date("8/31/2018") > new Date(filing[number_of_length]["year_end"]))
    	{
		    $(".dispense_agm_button").text("Dispense AGM");
		    $("#agm_date").prop('disabled', false);
			//$("#agm_date").val("");
			bool_dispense_agm = true;
		}
		else
		{
			if($(".company_type").val() == 1)
			{
				$(".dispense_agm_button").text("Hold AGM");
				$("#agm_date").prop('disabled', true);
				//$("#agm_date").val("dispensed");
				bool_dispense_agm = false;
			}
			else
			{
				if(filing[number_of_length]["agm"] == "dispensed")
				{
					$("#agm_date").prop('disabled', true);
					bool_dispense_agm = false;
				}
				else
				{
					$("#agm_date").prop('disabled', false);
					bool_dispense_agm = true;
				}
				
			}
		}
		//console.log(filing[number_of_length]["due_date_175"]);
		$("#due_date_175").val(filing[number_of_length]["due_date_175"]);
		$("#due_date_201").val(filing[number_of_length]["due_date_201"]);
		$("#due_date_197").val(filing[number_of_length]["due_date_197"]);
		$(".change_year_end_button").show();
		due_date_201_dropdown();
		due_date_197_dropdown();
		/*if(filing[number_of_length]["due_date_175"] != "")
		{
			console.log(formatDateFunc(new Date(filing[number_of_length]["due_date_175"])));
			due_date_175_dropdown(new Date(filing[number_of_length]["due_date_175"]));
		}*/

		if(filing[number_of_length]["due_date_175"] != "" && filing[number_of_length]["due_date_175"] != "Not Applicable")
		{
			due_date_175_dropdown(new Date(filing[number_of_length]["due_date_175"]));
			$(".extended_to_175").prop('disabled', false);
		}
		else if (filing[number_of_length]["due_date_175"] != "" && filing[number_of_length]["due_date_175"] == "Not Applicable")
		{
			//$("#due_date_201").val(filing_data[number_of_lgh]["due_date_175"]);
			$("#due_date_175").val(filing[number_of_length]["due_date_175"]);
			$(".extended_to_175").prop('disabled', true);
		}

		if(filing[number_of_length]["due_date_197"] != "" && filing[number_of_length]["due_date_197"] != "Not Applicable")
		{
			due_date_197_dropdown(new Date(filing[number_of_length]["due_date_197"]));
			//$("#due_date_197").val(filing[number_of_length]["due_date_197"]);
			$(".extended_to_197").prop('disabled', false);
		}
		else if (filing[number_of_length]["due_date_197"] != "" && filing[number_of_length]["due_date_197"] == "Not Applicable")
		{
			//$("#due_date_201").val(filing_data[number_of_lgh]["due_date_175"]);
			$("#due_date_197").val(filing[number_of_length]["due_date_197"]);
			$(".extended_to_197").prop('disabled', true);
		}

		for(var i = 0; i < filing.length; i++)
	    {
	       /* total_existing +=  parseInt(transfer_object["current_share"][i]);
	        new_share = parseInt(transfer_object["current_share"][i])-parseInt(transfer_object["share_transfer"][i]);
	        total_new_share_tranfer += new_share;*/
	        /*if(filing[i]["agm"] == "")
	    	{*/

	    		

	    		//console.log(filing_data[i]["due_date_175"] == "");
	    	//}
	    	if(i == 0)
	    	{
	    		first_filing_id = filing[i]["id"];
	    	}

	        $b=""; 
	        $b += '<tr class="filing_info_for_each_company">';
	        $b += '<td class="hidden"><input type="text" class="form-control" name="each_filing_id" id="each_filing_id" value="'+filing[i]["id"]+'"/></td>';
	        $b += '<td>'+(i+1)+'</td>';
	        if(access_right_client_module == "read" || access_right_filing_module == "read")
			{
	        	$b += '<td style="text-align: center">'+filing[i]["year_end"]+'</td>';
	        }
	        else
	        {
	        	$b += '<td style="text-align: center"><a href="javascript:void(0)" class="edit_filing" data-id="'+filing[i]["id"]+'" id="edit_filing">'+filing[i]["year_end"]+'</a></td>';
	        }
	        
	        $b += '<td style="text-align: center">'+filing[i]["period"]+'</td>';
	        $b += '<td style="text-align: center">'+((filing[i]["175_extended_to"] != 0)?filing[i]["175_extended_to"] : filing[i]["due_date_175"])+'</td>';
	        $b += '<td style="text-align: center">'+((filing[i]["201_extended_to"] != 0)?filing[i]["201_extended_to"] : filing[i]["due_date_201"])+'</td>';
	        $b += '<td style="text-align: center">'+((filing[i]["197_extended_to"] != 0)?filing[i]["197_extended_to"] : filing[i]["due_date_197"])+'</td>';
	        $b += '<td style="text-align: center">'+filing[i]["agm"]+'</td>';
	        $b += '<td style="text-align: center">'+filing[i]["ar_filing_date"]+'</td>';
	        $b += '<td><button type="button" class="btn btn-primary delete_filing_button" onclick="delete_filing(this)">Delete</button></td>';
	        $b += '</tr>';

	        $("#filing_table").append($b);
	    }
	}
}

$('.edit_filing').live("click",function(){
	
    edit_filing_id =  $(this).data("id");

    

    for(var i = 0; i < filing_data.length; i++)
    {
    	if(filing_data[i]["id"] == edit_filing_id)
    	{
    		$(".filing_id").val(filing_data[i]["id"]);
    		$("#year_end_date").val(filing_data[i]["year_end"]);
    		$("#year_end_date").prop('disabled', true);
    		$("#agm_date").val(filing_data[i]["agm"]);
    		$("#ar_filing_date").val(filing_data[i]["ar_filing_date"]);
			get_financial_year_period(filing_data[i]["financial_year_period_id"]);
    		if(filing_data[i]["agm"] == "dispensed")
    		{
    			$("#agm_date").prop('disabled', true);
    			bool_dispense_agm = false;
    		}
    		else
    		{
    			$("#agm_date").prop('disabled', false);
    			bool_dispense_agm = true;
    		}
    		$("#due_date_175").val(filing_data[i]["due_date_175"]);
    		$("#due_date_201").val(filing_data[i]["due_date_201"]);
    		$("#due_date_197").val(filing_data[i]["due_date_197"]);
    		$(".change_year_end_button").show();
    		due_date_201_dropdown();
    		/*if(filing_data[i]["due_date_175"] != "")
    		{
    			due_date_175_dropdown(new Date(filing_data[i]["due_date_175"]));
    		}*/

    		if(filing_data[i]["due_date_175"] != "" && filing_data[i]["due_date_175"] != "Not Applicable")
			{
				due_date_175_dropdown(new Date(filing_data[i]["due_date_175"]));
				$(".extended_to_175").prop('disabled', false);
			}
			else if (filing_data[i]["due_date_175"] != "" && filing_data[i]["due_date_175"] == "Not Applicable")
    		{
    			//$("#due_date_201").val(filing_data[number_of_lgh]["due_date_175"]);
    			$("#due_date_175").val(filing_data[i]["due_date_175"]);
    			$(".extended_to_175").prop('disabled', true);
    		}

    		if(filing_data[i]["due_date_197"] != "" && filing_data[i]["due_date_197"] != "Not Applicable")
			{
				due_date_197_dropdown(new Date(filing_data[i]["due_date_197"]));
				$(".extended_to_197").prop('disabled', false);
			}
			else if (filing_data[i]["due_date_197"] != "" && filing_data[i]["due_date_197"] == "Not Applicable")
    		{
    			//$("#due_date_201").val(filing_data[number_of_lgh]["due_date_175"]);
    			$("#due_date_197").val(filing_data[i]["due_date_197"]);
    			$(".extended_to_197").prop('disabled', true);
    		}
    		

    		//console.log(filing_data[i]["due_date_175"] == "");
    	}
    }

})

$('#agm_date').datepicker({
    format: 'dd MM yyyy'
});

$('#ar_filing_date').datepicker({
    format: 'dd MM yyyy'
});

$('#year_end_date').datepicker({
    format: 'dd MM yyyy'
});
/*var date = new Date();
	//date.setDate(date.getDate()-1);

	$('#date_of_birth').datepicker({ 
	    endDate: date
	});*/
get_financial_year_period();
check_incorporation_date();
function check_incorporation_date()
{
	$.ajax({
		type: "POST",
		url: "masterclient/check_incorporation_date",
		data: {"company_code": company_code}, // <--- THIS IS THE CHANGE
		dataType: "json",
		async: false,
		success: function(response)
		{
			//console.log("incorporation_date==="+response[0]["incorporation_date"]);
			if(response != null)
			{
				$array = response[0]["incorporation_date"].split("/");
				$tmp = $array[0];
				$array[0] = $array[1];
				$array[1] = $tmp;
				//unset($tmp);
				$date_2 = $array[0]+"/"+$array[1]+"/"+$array[2];
				//console.log(new Date($date_2));

				var date = new Date($date_2);
				//date.setDate(date.getDate()-1)

				//console.log(new Date());

				$('#year_end_date').datepicker({
				    //format: 'dd MM yyyy',
				    startDate: date
				}).on('changeDate', function (ev) {
				    //var due_date_201, after_180_days;
				    
				    //console.log($('#year_end_date').val());

				    //due_date_201.setDate(due_date_201.getDate() + 180); 
				    //due_date_201.setMonth(due_date_201.getMonth() + 6);
				    //console.log(due_date_201);
				    /*if($('#year_end_date').val() != "")
				    {
				    	due_date_201 = new Date($('#year_end_date').val());

					    due_date_201 = new Date(due_date_201.getFullYear(), due_date_201.getMonth()+7, 0);

					    console.log(due_date_201);

					    $("#due_date_201").val(formatDateFunc(due_date_201));

					    $( '#validate_year_end_date' ).html("");
					}*/


				});
			}
			
		}
	});
}

function get_financial_year_period(financial_year_period_id = null)
{
	$.ajax({
		type: "GET",
		url: "masterclient/get_financial_year_period",
		dataType: "json",
		success: function(data){
			//console.log(data);
            
            //console.log(data);
            if(data.tp == 1){
            	/*tr.find('select[name="alternate_of[]"]').html(""); 
            	tr.find('select[name="alternate_of[]"]').append($('<option>', {
				    value: '0',
				    text: 'Select Director'
				}));*/
            	
            	//option.attr('value', '').text("Select Director");
            	//tr.find('select[name="alternate_of[]"]').html("Select Director");
            	//option.attr('value', '0').text("Select Director");
            	$("#financial_year_period option").remove();
                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    if(financial_year_period_id != null && key == financial_year_period_id)
                    {
                        option.attr('selected', 'selected');
                    }
                    $("#financial_year_period").append(option);
                });
                
                //$(".nationality").prop("disabled",false);
            }
            else{
                alert(data.msg);
            }

			
		}				
	});
}

// $(document).ready(function(){
// 	$("#year_end_date").keydown(myfunction); // use keydown

// 	function myfunction(e) {
// 	    if(e.keyCode === 13) {
// 	    	console.log("inin");
// 	        e.stopPropagation();
// 	        e.preventDefault();

// 	        return false;
// 	    }
// 	}
// })

// $('#year_end_date').keypress(function (event) {
//     if (event.keyCode === 10 || event.keyCode === 13) {
//         event.preventDefault();
//     }
// });

$("#year_end_date").blur(function() {
    //console.log('in');

    //console.log('out');
    var due_date_201, after_180_days, due_date_197, latest_year_end;				    
    
    if($('#year_end_date').val() != "")
    {
    	console.log(first_filing_id);
    	due_date_201 = new Date($('#year_end_date').val());
    	

    	// console.log(new Date("8/31/2018") >= due_date_201);
    	// console.log(new Date("8/31/2018"));

    	if(new Date("8/31/2018") > new Date($('#year_end_date').val()))
    	{
    		// console.log(new Date("8/31/2018") >= new Date($('#year_end_date').val()));
    		// console.log(new Date("8/31/2018"));
    		// console.log(new Date($('#year_end_date').val()));
    		
    		if(first_filing_id != "" && first_filing_id != $(".filing_id").val())
    		{
    			check_due_date_175();
    		}
    		

		    due_date_201 = new Date(due_date_201.getFullYear(), due_date_201.getMonth()+7, 0);

		    $("#due_date_201").val(formatDateFunc(due_date_201));
		    $("#due_date_197").val("");
			$("#due_date_175").val("");


		    $(".dispense_agm_button").text("Dispense AGM");
		    $("#agm_date").prop('disabled', false);
			$("#agm_date").val("");
			bool_dispense_agm = true;
		}
		else
		{
			//latest_year_end = new Date($('#year_end_date').val());

			$.ajax({
				type: "POST",
				url: "masterclient/calculate_new_filing_date",
				data: {"latest_year_end": $('#year_end_date').val()}, // <--- THIS IS THE CHANGE
				dataType: "json",
				async: false,
				success: function(data){
		            if(data.tp == 1){
		            	//console.log(data['result']['due_date_201']);
		            	//$("#due_date_201").val((data['result']['due_date_201']));
		            	
		       //      	if(first_filing_id != "" && first_filing_id != $(".filing_id").val() || new Date($('#year_end_date').val()) >= new Date("12/31/2018"))
    					// {
			            	$("#due_date_197").val(data['result']['due_date_197']);
			            	check_due_date_175(data['result']['due_date_175']);
			            // }
			            // else
			            // {
			            	// $("#due_date_197").val("");
			            	// $("#due_date_175").val("");
			            	$("#extended_to_175 option").remove();
	
							$("#extended_to_175").append($('<option>', {
							    value: '0',
							    text: 'Please Select'
							}));
			            //}
			            $("#due_date_201").val(data['result']['due_date_201']);
		            }
		            else{
		                alert(data.msg);
		            }

					
				}				
			});

			// due_date_197 = new Date($('#year_end_date').val());

			if($("#upload_company_info .company_type").val() == 1)
			{
				$(".dispense_agm_button").text("Hold AGM");
				$("#agm_date").prop('disabled', false);
				$("#agm_date").val("");
				bool_dispense_agm = true;
			}
			else
			{

				$("#agm_date").prop('disabled', false);
				$("#agm_date").val("");
				bool_dispense_agm = true;
			}

			// due_date_201 = new Date(due_date_201.getFullYear(), due_date_201.getMonth()+6, 0);

	  //   	$("#due_date_201").val(formatDateFunc(due_date_201));

			// due_date_197 = new Date(due_date_197.getFullYear(), due_date_197.getMonth()+7, 0);

		 //    $("#due_date_197").val(formatDateFunc(due_date_197));
		}

	    $( '#validate_year_end_date' ).html("");
	}
	else
	{
		 $("#due_date_201").val("");
		 $("#due_date_197").val("");
	}
});

function change_incorporation_date(latest_incorporation_date)
{
	$('#year_end_date').datepicker({ 
	    dateFormat:'dd MM yyyy',
	}).datepicker('setStartDate', latest_incorporation_date);

	$('#agm_date').datepicker({ 
	    dateFormat:'dd MM yyyy',
	}).datepicker('setStartDate', latest_incorporation_date);

	$('#ar_filing_date').datepicker({ 
	    dateFormat:'dd MM yyyy',
	}).datepicker('setStartDate', latest_incorporation_date);


	$('.eci_tax_period').datepicker({ 
	    dateFormat:'dd MM yyyy',
	}).datepicker('setStartDate', latest_incorporation_date);

	$('#eci_filing_date').datepicker({ 
	    dateFormat:'dd MM yyyy',
	}).datepicker('setStartDate', latest_incorporation_date);

	$('.coporate_tax_period').datepicker({ 
	    dateFormat:'dd MM yyyy',
	}).datepicker('setStartDate', latest_incorporation_date);

	$('#tax_filing_date').datepicker({ 
	    dateFormat:'dd MM yyyy',
	}).datepicker('setStartDate', latest_incorporation_date);
}




$.fn.allchange = function (callback) {
    var me = this;
    var last = "";
    var infunc = function () {
        var text = $(me).val();
        if (text != last) {
            last = text;
            callback();
        }
        setTimeout(infunc, 100);
    }
    setTimeout(infunc, 100);
}

function due_date_197_dropdown()
{
	var due_date_197_ext_one_month, due_date_197_ext_two_month, due_date_197_ext_array = [];

	$("#extended_to_197 option").remove();

	// due_date_197_ext_one_month = new Date($('#due_date_197').val());
	// due_date_197_ext_one_month.setDate( due_date_197_ext_one_month.getDate( ) + 30 );

 //    due_date_197_ext_array.push(formatDateFunc(due_date_197_ext_one_month));

    due_date_197_ext_two_month = new Date($('#due_date_197').val());
    due_date_197_ext_two_month.setDate( due_date_197_ext_two_month.getDate( ) + 60 );

    due_date_197_ext_array.push(formatDateFunc(due_date_197_ext_two_month));

	$("#extended_to_197").append($('<option>', {
	    value: '0',
	    text: 'Please Select'
	}));

    for(var i = 0; i < due_date_197_ext_array.length; i++)
	{
		var option = $('<option />');
        option.attr('value', due_date_197_ext_array[i]).text(due_date_197_ext_array[i]);
        if(filing_data)
        {
        	 for(var j = 0; j < filing_data.length; j++)
		    {
		    	if(filing_data[j]["id"] == edit_filing_id)
		    	{
		        	if(filing_data[j]["197_extended_to"] != 0 && filing_data[j]['197_extended_to'] == due_date_197_ext_array[i])
		        	{
		        		option.attr('selected', 'selected');
		        	}
		        }
		    }
        }

        $("#extended_to_197").append(option);
	}
}

function due_date_201_dropdown()
{
	var due_date_201_ext_one_month, due_date_201_ext_two_month, due_date_201_ext_array = [];

	$("#extended_to_201 option").remove();

	$("#extended_to_201").append($('<option>', {
	    value: '0',
	    text: 'Please Select'
	}));

	if($('#due_date_201').val() != "")
	{
		due_date_201_ext_one_month = new Date($('#due_date_201').val());
		due_date_201_ext_one_month.setDate( due_date_201_ext_one_month.getDate( ) + 30 );
		/*due_date_201_ext_one_month = new Date(due_date_201_ext_one_month.getFullYear(), due_date_201_ext_one_month.getMonth()+1, 30);
		//console.log(new Date($('#due_date_201').val()).getMonth() + 1);
	    due_date_201_ext_one_month.setMonth(due_date_201_ext_one_month.getMonth() + 1);
	    due_date_201_ext_one_month = new Date(due_date_201_ext_one_month.getFullYear(), due_date_201_ext_one_month.getMonth(), 0);*/

	    //console.log(due_date_201_ext_one_month);
	    

	    //due_date_201_ext_one_month = new Date(due_date_201_ext_one_month.getFullYear(), due_date_201_ext_one_month.getMonth(), 31)

	    due_date_201_ext_array.push(formatDateFunc(due_date_201_ext_one_month));

	    due_date_201_ext_two_month = new Date($('#due_date_201').val());
	    due_date_201_ext_two_month.setDate( due_date_201_ext_two_month.getDate( ) + 60 );
	/*    due_date_201_ext_one_month = new Date(due_date_201_ext_one_month.getFullYear(), due_date_201_ext_one_month.getMonth()+1, 30);
	    due_date_201_ext_two_month.setMonth(due_date_201_ext_two_month.getMonth() + 2);
	    due_date_201_ext_two_month = new Date(due_date_201_ext_two_month.getFullYear(), due_date_201_ext_two_month.getMonth()+1, 0);*/

	    //console.log(due_date_201_ext_two_month);

	    /*due_date_201_ext_two_month = new Date(due_date_201_ext_two_month.getFullYear(), due_date_201_ext_two_month.getMonth(), 31)*/

	    due_date_201_ext_array.push(formatDateFunc(due_date_201_ext_two_month));

		

	    for(var i = 0; i < due_date_201_ext_array.length; i++)
		{
			var option = $('<option />');
	        option.attr('value', due_date_201_ext_array[i]).text(due_date_201_ext_array[i]);
	        if(filing_data)
	        {
	        	 for(var j = 0; j < filing_data.length; j++)
			    {
			    	if(filing_data[j]["id"] == edit_filing_id)
			    	{
			        	if(filing_data[j]["201_extended_to"] != 0 && filing_data[j]['201_extended_to'] == due_date_201_ext_array[i])
			        	{
			        		option.attr('selected', 'selected');
			        	}
			        }
			    }
	        }

	        $("#extended_to_201").append(option);
		}
	}
}

function due_date_175_dropdown(due_date_175){
	var due_date_175_ext_one_month, due_date_175_ext_two_month, due_date_175_ext_array = [];

	$("#extended_to_175 option").remove();
	
	$("#extended_to_175").append($('<option>', {
	    value: '0',
	    text: 'Please Select'
	}));

	//console.log(due_date_175 != "");

	if(due_date_175 != "")
	{
		
		$("#due_date_175").val(formatDateFunc(due_date_175));
		//get 197 value

		if(new Date("8/31/2018") > new Date($('#year_end_date').val()))
	    {
			due_date_197 = new Date($('#due_date_175').val());

		    due_date_197.setDate( due_date_197.getDate( ) + 30 );

		    $("#due_date_197").val(formatDateFunc(due_date_197));
		}

		due_date_175_ext_one_month= new Date( due_date_175 );
		due_date_175_ext_one_month.setDate( due_date_175_ext_one_month.getDate( ) + 30 );
		//due_date_175_ext_one_month = due_date_175_ext_one_month.getDate( ) + ' ' + ( due_date_175_ext_one_month.getMonth( ) + 1 ) + ' ' + due_date_175_ext_one_month.getFullYear( );
		//console.log(due_date_175_ext_one_month);
		/*due_date_175_ext_one_month = new Date(due_date_175);

		due_date_175_ext_one_month.setMonth(due_date_175_ext_one_month.getMonth() + 1);*/

		due_date_175_ext_array.push(formatDateFunc(due_date_175_ext_one_month));

		due_date_175_ext_two_month = new Date(due_date_175);
		due_date_175_ext_two_month.setDate(due_date_175_ext_two_month.getDate() + 60 );

		due_date_175_ext_array.push(formatDateFunc(due_date_175_ext_two_month));

		

		for(var i = 0; i < due_date_175_ext_array.length; i++)
		{
			var option = $('<option />');
	        option.attr('value', due_date_175_ext_array[i]).text(due_date_175_ext_array[i]);
	        if(filing_data)
	        {
	        	 for(var j = 0; j < filing_data.length; j++)
			    {
			    	if(filing_data[j]["id"] == edit_filing_id)
			    	{
			        	if(filing_data[j]["175_extended_to"] != null && filing_data[j]['175_extended_to'] == due_date_175_ext_array[i])
			        	{
			        		option.attr('selected', 'selected');
			        	}
			        }
			    }
	        }

	        $("#extended_to_175").append(option);
		}
	}
}

$('#due_date_201').allchange(function(){
	//console.log("in");
	due_date_201_dropdown();
});

$('#due_date_197').allchange(function(){
	due_date_197_dropdown();
});

function check_due_date_175($date = null)
{
	if(new Date("8/31/2018") > new Date($('#year_end_date').val()))
    {
		$.ajax({
		    type: "POST",
		    url: "masterclient/check_first_due_date_175",
		    data: {"company_code": company_code, "filing_id": $(".filing_id").val()}, // <--- THIS IS THE CHANGE
		    dataType: "json",
		    success: function(response){
		    	//console.log(response);
		    	if(response.way == 1 || response.way == 3)
		    	{
		    		var due_date_175;
		    		due_date_175 = new Date(response.date);
		    		//console.log(response.date);

					// due_date_175.setMonth(due_date_175.getMonth() + 18);
					// due_date_175.setDate(due_date_175.getDate() - 1);
			    	due_date_175_dropdown(due_date_175);	
			    }
				else if(response.way == 2)
				{
					var date_after_fifteen_month, previous_due_date_175, last_day_of_year, twoDigitsPreviousDate, twoDigitsNewDate, due_date_175, due_date_175_ext_one_month, due_date_175_ext_two_month, due_date_175_ext_array = [];

			    	$("#extended_to_175 option").remove();
			    	previous_due_date_175 = new Date(response.date);
					due_date_175 = new Date(response.date);
					//console.log(due_date_175);
					//console.log("old_year==="+due_date_175.getFullYear());
					twoDigitsPreviousDate = parseInt(due_date_175.getFullYear().toString().substr(2,2));


			    	//due_date_175.setMonth(due_date_175.getMonth() + 15);

			    	date_after_fifteen_month = new Date(response.date_after_fifteen_month);
			    	
		            //console.log("new_year==="+due_date_175.getFullYear());
		            twoDigitsNewDate = parseInt(due_date_175.getFullYear().toString().substr(2,2));
		            // console.log(twoDigitsNewDate);
		            // console.log(twoDigitsPreviousDate);
		            if(twoDigitsNewDate - twoDigitsPreviousDate > 1)
		            {
		            	last_day_of_year = new Date(previous_due_date_175.getFullYear(), 12, 31)
		            	$("#due_date_175").val(formatDateFunc(last_day_of_year));
		            }
		            else
		            {
		            	$("#due_date_175").val(formatDateFunc(date_after_fifteen_month));
		            }
			    	
					due_date_175_ext_one_month = new Date(date_after_fifteen_month);
			    	due_date_175_ext_one_month.setMonth(due_date_175_ext_one_month.getMonth() + 1);

			    	due_date_175_ext_array.push(formatDateFunc(due_date_175_ext_one_month));

			    	due_date_175_ext_two_month = new Date(date_after_fifteen_month);
			    	due_date_175_ext_two_month.setMonth(due_date_175_ext_two_month.getMonth() + 2);

			    	due_date_175_ext_array.push(formatDateFunc(due_date_175_ext_two_month));

			    	$("#extended_to_175").append($('<option>', {
					    value: '0',
					    text: 'Please Select'
					}));

			    	for(var i = 0; i < due_date_175_ext_array.length; i++)
					{
						var option = $('<option />');
				        option.attr('value', due_date_175_ext_array[i]).text(due_date_175_ext_array[i]);
				        /*if(allotment)
				        {
				        	if(allotment[0]["share_capital_id"] != null && company_class[i]['id'] == allotment[0]["share_capital_id"])
				        	{
				        		option.attr('selected', 'selected');
				        		if(allotment[0]["sharetype"] == "Others")
				        		{
				        			$("#other_class").removeAttr('hidden');
				        		}
				        	}
				        }*/

				        $("#extended_to_175").append(option);
					}
				} 
				// else if(response.way == 3)
				// {
				// 	$("#due_date_175").val("");
				// 	$("#extended_to_175 option").remove();
				// 	$("#extended_to_175").append($('<option>', {
				// 	    value: '0',
				// 	    text: 'Please Select'
				// 	}));
				// }
				else if(response.way == 4)
				{
					$("#due_date_175").val("Not Applicable");
					$("#extended_to_175").prop('disabled', true);
				}

				due_date_197 = new Date($('#due_date_175').val());

			    due_date_197.setDate( due_date_197.getDate( ) + 30 );

			    $("#due_date_197").val(formatDateFunc(due_date_197));
				
			}
		});
	}
	else
	{
		// var due_date_175;

		if($date == null)
		{
			due_date_175 = "";
		}
		else
		{
			due_date_175 = new Date($date);
		}

		// due_date_175.setMonth(due_date_175.getMonth() + 6);
		// due_date_175.setDate(due_date_175.getDate() - 1);

		// due_date_175_dropdown(due_date_175);
		due_date_175_dropdown(due_date_175);
	}
}

function formatDateFunc(date) {
	//console.log(date);
  var monthNames = [
    "January", "February", "March",
    "April", "May", "June", "July",
    "August", "September", "October",
    "November", "December"
  ];

  var day = date.getDate();
  //console.log(day.length);
  if(day.toString().length==1)	
  {
  	day="0"+day;
  }
  	
  var monthIndex = date.getMonth();
  var year = date.getFullYear();

  return day + ' ' + monthNames[monthIndex] + ' ' + year;
}
/*
$('#update_filling').click(function(event){
	//console.log("inin");
	$('#filing_form').submit();  
});*/

function change_year_end()
{
	$("#year_end_date").prop('disabled', false);
}

function dispense_agm()
{
	if(bool_dispense_agm)
	{
		$("#agm_date").prop('disabled', true);
		$("#agm_date").val("dispensed");
		bool_dispense_agm = false;
	}
	else
	{
		if(new Date("8/31/2018") > new Date($('#year_end_date').val()))
	    {
			$(".dispense_agm_button").text("Dispense AGM");
		}

		$("#agm_date").prop('disabled', false);
		$("#agm_date").val("");
		bool_dispense_agm = true;
	}
}

function dispense_agm_button(status)
{
	if(status == "public")
	{
		$(".dispense_agm_button").hide();
	}
	else if(status == "private")
	{
		$(".dispense_agm_button").show();
	}
}

$('#update_filling').click(function(e){
	e.preventDefault();
	$("#year_end_date").prop('disabled', false);
	$("#agm_date").prop('disabled', false);

	$.ajax({
        type: "POST",
        url: "masterclient/check_filing_data",
        data: $('#filing_form').serialize(), // <--- THIS IS THE CHANGE
        dataType: "json",
        async: false,
        success: function(response)
        {
            if(response)
            {
                if (confirm('Do you want to submit?')) 
                {
                   charge_filing();
                } 
                else 
                {
                	$("#year_end_date").prop('disabled', true);
					$("#agm_date").prop('disabled', true);
                   	return false;
                }
            }
            else
            {
                charge_filing();
            }
        }
    });
	
});

function charge_filing(){
	$("#year_end_date").prop('disabled', false);
	$("#agm_date").prop('disabled', false);
	$('#loadingmessage').show();
	$.ajax({ //Upload common input
        url: "masterclient/add_filing_info",
        type: "POST",
        data: $('#filing_form').serialize(),
        dataType: 'json',
        success: function (response) {
        	$('#loadingmessage').hide();
        	//console.log(response.Status);
            if (response.Status === 1) {
            	toastr.success(response.message, response.title);
            	filing_history_table(response.filing_data);
            	//check_due_date_175();
            	//$(".change_year_end_button").hide();
            	filing_data = response.filing_data;
            	bool_dispense_agm = true;
            	//$(".filing_id").val("");

            	//for(var i = 0; i < filing_data.length; i++)
			    //{
			    	//console.log(filing_data[i]["agm"] == "");
			    	//if(filing_data[i]["agm"] == "")
			    	//{
			    		var num_filing = parseInt(filing_data.length) - 1;
			    		edit_filing_id = filing_data[num_filing]["id"];
			    		$(".filing_id").val(filing_data[num_filing]["id"]);
			    		$("#year_end_date").val(filing_data[num_filing]["year_end"]);
			    		$("#year_end_date").prop('disabled', true);

			    		if(new Date("8/31/2018") > new Date(filing_data[num_filing]["year_end"]))
				    	{
						    $(".dispense_agm_button").text("Dispense AGM");
						    $("#agm_date").prop('disabled', false);
							//$("#agm_date").val("");
							bool_dispense_agm = true;
						}
						else
						{
							if($("#upload_company_info .company_type").val() == 1 && filing_data[num_filing]["agm"] != "")
							{
								$(".dispense_agm_button").text("Hold AGM");
								$("#agm_date").prop('disabled', true);
								//$("#agm_date").val("dispensed");
								bool_dispense_agm = false;
							}
							else
							{

								$("#agm_date").prop('disabled', false);
								bool_dispense_agm = true;
							}
						}

			    		$("#agm_date").val(filing_data[num_filing]["agm"]);
			    		$("#ar_filing_date").val(filing_data[num_filing]["ar_filing_date"]);
			    		get_financial_year_period(filing_data[num_filing]["financial_year_period_id"]);
			    		$("#due_date_175").val(filing_data[num_filing]["due_date_175"]);
			    		$("#due_date_201").val(filing_data[num_filing]["due_date_201"]);
			    		$("#due_date_197").val(filing_data[num_filing]["due_date_197"]);
			    		$(".change_year_end_button").show();
			    		due_date_201_dropdown();
			    		due_date_197_dropdown();
			    		if(filing_data[num_filing]["due_date_175"] != "" && filing_data[num_filing]["due_date_175"] != "Not Applicable")
			    		{

			    			due_date_175_dropdown(new Date(filing_data[num_filing]["due_date_175"]));
			    			$(".extended_to_175").prop('disabled', false);
			    		}
			    		else if (filing_data[num_filing]["due_date_175"] != "" && filing_data[num_filing]["due_date_175"] == "Not Applicable")
			    		{
			    			$(".extended_to_175").prop('disabled', true);
			    		}
			    		
			    		if(response.have_eci)
			    		{
			    			check_latest_fye();
			    		}
			    		if(response.have_tax)
			    		{
			    			check_latest_fye_for_tax();
			    		}
			    		//console.log(bool_dispense_agm);
			    		//console.log(filing_data[i]["due_date_175"] == "");
			    	//}
			    //}


            }
            else
            {
            	toastr.error(response.message, response.title);
            	//console.log(response.error["year_end"]);
            	/*$("#validate_year_end_date").text(response[0].error);*/
            	if (response.error["year_end"] != "")
            	{
            		var errorsYearEnd = '<span class="help-block">*' + response.error["year_end"] + '</span>';
            		$( '#validate_year_end_date' ).html( errorsYearEnd );

            	}
            	else
            	{
            		var errorsYearEnd = '';
            		$( '#validate_year_end_date' ).html( errorsYearEnd );
            	}
            }
        }
    });
}


function delete_filing(element){
	var tr = jQuery(element).parent().parent();
	var each_filing_id = tr.find('input[name="each_filing_id"]').val();
	if(each_filing_id != undefined)
	{
		$('#loadingmessage').show();
		$.ajax({ //Upload common input
	        url: "masterclient/delete_filing",
	        type: "POST",
	        data: {"filing_id": each_filing_id, "company_code": company_code},
	        dataType: 'json',
	        success: function (response) {
	        	//console.log(response);
	        	$('#loadingmessage').hide();
	        	if(response.filing_data == null)
	        	{
	        		$(".filing_id").val("");
	        		first_filing_id = "";
					$("#year_end_date").val("");
					$("#year_end_date").prop('disabled', false);
					$("#agm_date").prop('disabled', false);
					bool_dispense_agm = false;
					$(".extended_to_175").prop('disabled', false);
					$("#agm_date").val("");
					$("#ar_filing_date").val("");
					$("#due_date_175").val("");
					check_due_date_175();
					$("#due_date_201").val("");
					$(".change_year_end_button").hide();
					due_date_201_dropdown();
					$("#due_date_197").val("");
					due_date_197_dropdown();
	        	}
	        	else
	        	{
	        		filing_data = response.filing_data;

		        	var number_of_lgh = parseInt(filing_data.length) - 1;

					edit_filing_id = filing_data[number_of_lgh]["id"];

					$(".filing_id").val(filing_data[number_of_lgh]["id"]);
					$("#year_end_date").val(filing_data[number_of_lgh]["year_end"]);
					$("#year_end_date").prop('disabled', true);
					if(new Date("8/31/2018") > new Date(filing_data[number_of_lgh]["year_end"]))
			    	{
					    $(".dispense_agm_button").text("Dispense AGM");
					    $("#agm_date").prop('disabled', false);
						//$("#agm_date").val("");
						bool_dispense_agm = true;
					}
					else
					{
						if($("#upload_company_info .company_type").val() == 1)
						{
							$(".dispense_agm_button").text("Hold AGM");
							$("#agm_date").prop('disabled', true);
							//$("#agm_date").val("dispensed");
							bool_dispense_agm = false;
						}
						else
						{

							$("#agm_date").prop('disabled', false);
							bool_dispense_agm = true;
						}
					}

					if(filing_data[number_of_lgh]["agm"] == "dispensed")
					{
						$("#agm_date").prop('disabled', true);
						bool_dispense_agm = true;
					}
					else
					{
						$("#agm_date").prop('disabled', false);
						bool_dispense_agm = false;
					}
					
					//$(".extended_to_175").prop('disabled', false);
					$("#agm_date").val(filing_data[number_of_lgh]["agm"]);
					$("#ar_filing_date").val(filing_data[number_of_lgh]["ar_filing_date"]);
			    	get_financial_year_period(filing_data[number_of_lgh]["financial_year_period_id"]);
					//$("#due_date_175").val(filing[i]["due_date_175"]);
					$("#due_date_201").val(filing_data[number_of_lgh]["due_date_201"]);
					$(".change_year_end_button").show();
					due_date_201_dropdown();
					console.log(filing_data[number_of_lgh]["due_date_197"]);
					if(filing_data[number_of_lgh]["due_date_175"] != "" && filing_data[number_of_lgh]["due_date_175"] != "Not Applicable")
					{
						//console.log(formatDateFunc(new Date(filing_data[number_of_lgh]["due_date_175"])));
						due_date_175_dropdown(new Date(filing_data[number_of_lgh]["due_date_175"]));
						$(".extended_to_175").prop('disabled', false);
					}
					else if (filing_data[number_of_lgh]["due_date_175"] != "" && filing_data[number_of_lgh]["due_date_175"] == "Not Applicable")
		    		{
		    			//$("#due_date_201").val(filing_data[number_of_lgh]["due_date_175"]);
		    			$("#due_date_175").val(filing_data[number_of_lgh]["due_date_175"]);
		    			$(".extended_to_175").prop('disabled', true);
		    		}
		    		if(filing_data[number_of_lgh]["due_date_175"] == "")
		    		{
		    			$("#due_date_175").val(filing_data[number_of_lgh]["due_date_175"]);
		    			due_date_175_dropdown(filing_data[number_of_lgh]["due_date_175"]);
		    		}
		    		if(filing_data[number_of_lgh]["due_date_197"] == "")
		    		{	

		    			$("#due_date_197").val(filing_data[number_of_lgh]["due_date_197"]);
		    		}

	        	}
	        	if(response.have_eci)
	    		{
	    			check_latest_fye();
	    		}
	    		if(response.have_tax)
	    		{
	    			check_latest_fye_for_tax();
	    		}
	        }
	    });
	}
	tr.remove();

	
	//check_due_date_175();
	//$("#year_end_date").prop('disabled', false);
	//$(".change_year_end_button").hide();
}