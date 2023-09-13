var edit_eci_filing_id, first_eci_filing_id = "";

check_latest_fye();
eci_filing_history_table(eci_filing_data);

function check_latest_fye()
{
	$(".eci_tax_period").val("");
	$("#next_eci_filing_due_date").val("");

	$.ajax({
		type: "POST",
		url: "masterclient/check_latest_fye",
		data: {"company_code": company_code}, // <--- THIS IS THE CHANGE
		dataType: "json",
		async: false,
		success: function(response)
		{
			//console.log(response);
			$(".eci_tax_period").val(response['latest_fye']);
			$("#next_eci_filing_due_date").val(response['next_eci_filing_due_date']);
		}
	});
}

$('.eci_tax_period').datepicker({
    format: 'dd MM yyyy'
});

$('#eci_filing_date').datepicker({
    format: 'dd MM yyyy'
});

$(".eci_tax_period").blur(function() {			    

    if($('.eci_tax_period').val() != "")
    {
    	$.ajax({
			type: "POST",
			url: "masterclient/get_next_eci_filing_due_date",
			data: {"company_code": company_code, "eci_tax_period": $('.eci_tax_period').val()}, // <--- THIS IS THE CHANGE
			dataType: "json",
			async: false,
			success: function(response)
			{
				$("#next_eci_filing_due_date").val(response['next_eci_filing_due_date']);
			}
		});

    }
    else
	{
		 $("#next_eci_filing_due_date").val("");
	}
	$( '#validate_eci_tax_period' ).html( "" );
});

function eci_filing_history_table(eci_filing)
{	
	if(eci_filing)
	{
		console.log(eci_filing);
		$(".eci_filing_info_for_each_company").remove();
		var number_of_length = parseInt(eci_filing.length) - 1;
		edit_eci_filing_id = eci_filing[number_of_length]["id"];

		$(".eci_id").val(eci_filing[number_of_length]["id"]);
		$("#eci_tax_period").val(eci_filing[number_of_length]["eci_tax_period"]);
		$("#eci_filing_date").val(eci_filing[number_of_length]["eci_filing_date"]);
		$("#next_eci_filing_due_date").val(eci_filing[number_of_length]["next_eci_filing_due_date"]);

		for(var i = 0; i < eci_filing.length; i++)
	    {
	    	if(i == 0)
	    	{
	    		first_eci_filing_id = eci_filing[i]["id"];
	    	}

	        $b=""; 
	        $b += '<tr class="eci_filing_info_for_each_company">';
	        $b += '<td class="hidden"><input type="text" class="form-control" name="each_eci_filing_id" id="each_eci_filing_id" value="'+eci_filing[i]["id"]+'"/></td>';
	        $b += '<td style="width: 20px !important;">'+(i+1)+'</td>';
	        if(access_right_client_module == "read" || access_right_filing_module == "read")
			{
	        	$b += '<td style="text-align: center">'+eci_filing[i]["eci_tax_period"]+'</td>';
	        }
	        else
	        {
	        	$b += '<td style="text-align: center"><a href="javascript:void(0)" class="edit_eci_filing" data-id="'+eci_filing[i]["id"]+'" id="edit_eci_filing">'+eci_filing[i]["eci_tax_period"]+'</a></td>';
	        }
	        
	        $b += '<td style="text-align: center">'+eci_filing[i]["eci_filing_date"]+'</td>';
	        $b += '<td style="text-align: center">'+(eci_filing[i]["next_eci_filing_due_date"])+'</td>';
	        $b += '<td><button type="button" class="btn btn-primary delete_eci_filing_button" onclick="delete_eci_filing(this)">Delete</button></td>';
	        $b += '</tr>';

	        $("#eci_filing_table").append($b);
	    }
	}
}

$('#update_eci').click(function(e){
	e.preventDefault();
	save_eci();
});


function save_eci(){
	$('#loadingmessage').show();
	$.ajax({ //Upload common input
        url: "masterclient/add_eci_filing_info",
        type: "POST",
        data: $('#eci_form').serialize(),
        dataType: 'json',
        success: function (response) {
        	$('#loadingmessage').hide();
        	
            if (response.Status === 1) {
            	console.log(response.eci_filing_data['eci_filing_data']);
            	toastr.success(response.message, response.title);
            	eci_filing_history_table(response.eci_filing_data['eci_filing_data']);

            	eci_filing_data = response.eci_filing_data['eci_filing_data'];

	    		var num_eci_filing = parseInt(eci_filing_data.length) - 1;
	    		edit_eci_filing_id = eci_filing_data[num_eci_filing]["id"];
	    		$(".eci_id").val(eci_filing_data[num_eci_filing]["id"]);
	    		$("#eci_tax_period").val(eci_filing_data[num_eci_filing]["eci_tax_period"]);
	    		$("#eci_filing_date").val(eci_filing_data[num_eci_filing]["eci_filing_date"]);
	    		$("#next_eci_filing_due_date").val(eci_filing_data[num_eci_filing]["next_eci_filing_due_date"]);
            }
            else
            {
            	toastr.error(response.message, response.title);
            	//console.log(response.error["year_end"]);
            	/*$("#validate_year_end_date").text(response[0].error);*/
            	if (response.error["eci_tax_period"] != "")
            	{
            		var errorsYearEnd = '<span class="help-block">*' + response.error["eci_tax_period"] + '</span>';
            		$( '#validate_eci_tax_period' ).html( errorsYearEnd );

            	}
            	else
            	{
            		var errorsYearEnd = '';
            		$( '#validate_eci_tax_period' ).html( errorsYearEnd );
            	}
            }
        }
    });
}

function delete_eci_filing(element){
	var tr = jQuery(element).parent().parent();
	var each_eci_filing_id = tr.find('input[name="each_eci_filing_id"]').val();
	if(each_eci_filing_id != undefined)
	{
		$('#loadingmessage').show();
		$.ajax({ //Upload common input
	        url: "masterclient/delete_eci_filing",
	        type: "POST",
	        data: {"eci_filing_id": each_eci_filing_id, "company_code": company_code},
	        dataType: 'json',
	        success: function (response) {
	        	//console.log(response);
	        	$('#loadingmessage').hide();
	        	if(response.eci_filing_data['eci_filing_data'] == false)
	        	{
	        		$(".eci_id").val("");
	        		first_eci_filing_id = "";
					$("#eci_tax_period").val("");
					$("#eci_filing_date").val("");
					$("#next_eci_filing_due_date").val("");
					check_latest_fye();

	        	}
	        	else
	        	{
	        		eci_filing_data = response.eci_filing_data['eci_filing_data'];

		        	var number_of_lgh = parseInt(eci_filing_data.length) - 1;

					edit_eci_filing_id = eci_filing_data[number_of_lgh]["id"];

					$(".eci_id").val(eci_filing_data[number_of_lgh]["id"]);
					$("#eci_tax_period").val(eci_filing_data[number_of_lgh]["eci_tax_period"]);
					$("#eci_filing_date").val(eci_filing_data[number_of_lgh]["eci_filing_date"]);
					$("#next_eci_filing_due_date").val(eci_filing_data[number_of_lgh]["next_eci_filing_due_date"]);

	        	}
	        	
	        }
	    });
	}
	tr.remove();
}

$('.edit_eci_filing').live("click",function(){
	
    edit_eci_filing_id =  $(this).data("id");

    for(var i = 0; i < eci_filing_data.length; i++)
    {
    	if(eci_filing_data[i]["id"] == edit_eci_filing_id)
    	{
    		$(".eci_id").val(eci_filing_data[i]["id"]);
    		$("#eci_tax_period").val(eci_filing_data[i]["eci_tax_period"]);
    		$("#eci_filing_date").val(eci_filing_data[i]["eci_filing_date"]);
    		$("#next_eci_filing_due_date").val(eci_filing_data[i]["next_eci_filing_due_date"]);
    	}
    }

});
