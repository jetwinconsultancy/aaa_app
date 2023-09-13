var edit_tax_filing_id, first_tax_filing_id = "";

check_latest_fye_for_tax();
tax_filing_history_table(tax_filing_data);

function check_latest_fye_for_tax()
{
	$(".coporate_tax_period").val("");
	$("#tax_filing_period").val("");
	$("#tax_filing_due_date").val("");

	$.ajax({
		type: "POST",
		url: "masterclient/check_latest_fye_for_tax",
		data: {"company_code": company_code}, // <--- THIS IS THE CHANGE
		dataType: "json",
		async: false,
		success: function(response)
		{
			//console.log(response);
			$(".coporate_tax_period").val(response['latest_fye']);
			$("#tax_filing_period").val(response['tax_filing_period']);
			$("#tax_filing_due_date").val(response['tax_filing_due_date']);
		}
	});
}

$('.coporate_tax_period').datepicker({
    format: 'dd MM yyyy'
});

$('#tax_filing_date').datepicker({
    format: 'dd MM yyyy'
});

$(".coporate_tax_period").blur(function() {			    

    if($('.coporate_tax_period').val() != "")
    {
    	$.ajax({
			type: "POST",
			url: "masterclient/get_tax_period_due_date",
			data: {"company_code": company_code, "coporate_tax_period": $('.coporate_tax_period').val()}, // <--- THIS IS THE CHANGE
			dataType: "json",
			async: false,
			success: function(response)
			{
				$("#tax_filing_period").val(response['tax_filing_period']);
				$("#tax_filing_due_date").val(response['tax_filing_due_date']);
			}
		});

    }
    else
	{
		$("#tax_filing_period").val("");
		$("#tax_filing_due_date").val("");
	}
	$( '#validate_coporate_tax_period' ).html( "" );
});

function tax_filing_history_table(tax_filing)
{	
	if(tax_filing)
	{
		console.log(tax_filing);
		$(".tax_filing_info_for_each_company").remove();
		var number_of_length = parseInt(tax_filing.length) - 1;
		edit_tax_filing_id = tax_filing[number_of_length]["id"];

		$(".tax_id").val(tax_filing[number_of_length]["id"]);
		$("#coporate_tax_period").val(tax_filing[number_of_length]["coporate_tax_period"]);
		$("#tax_filing_period").val(tax_filing[number_of_length]["tax_filing_period"]);
		$("#tax_filing_date").val(tax_filing[number_of_length]["filing_date"]);
		$("#tax_filing_due_date").val(tax_filing[number_of_length]["tax_filing_due_date"]);

		for(var i = 0; i < tax_filing.length; i++)
	    {
	    	if(i == 0)
	    	{
	    		first_tax_filing_id = tax_filing[i]["id"];
	    	}

	        $b=""; 
	        $b += '<tr class="tax_filing_info_for_each_company">';
	        $b += '<td class="hidden"><input type="text" class="form-control" name="each_tax_filing_id" id="each_tax_filing_id" value="'+tax_filing[i]["id"]+'"/></td>';
	        $b += '<td style="width: 20px !important;">'+(i+1)+'</td>';
	        if(access_right_client_module == "read" || access_right_filing_module == "read")
			{
	        	$b += '<td style="text-align: center">'+tax_filing[i]["coporate_tax_period"]+'</td>';
	        }
	        else
	        {
	        	$b += '<td style="text-align: center"><a href="javascript:void(0)" class="edit_tax_filing" data-id="'+tax_filing[i]["id"]+'" id="edit_tax_filing">'+tax_filing[i]["coporate_tax_period"]+'</a></td>';
	        }
	        
	        $b += '<td style="text-align: center">'+tax_filing[i]["tax_filing_period"]+'</td>';
	        $b += '<td style="text-align: center">'+(tax_filing[i]["tax_filing_due_date"])+'</td>';
	        $b += '<td style="text-align: center">'+(tax_filing[i]["filing_date"])+'</td>';
	        $b += '<td><button type="button" class="btn btn-primary delete_tax_filing_button" onclick="delete_tax_filing(this)">Delete</button></td>';
	        $b += '</tr>';

	        $("#tax_filing_table").append($b);
	    }
	}
}

$('#update_tax').click(function(e){
	e.preventDefault();
	save_tax();
});

function save_tax(){
	$('#loadingmessage').show();
	$.ajax({ //Upload common input
        url: "masterclient/add_tax_filing_info",
        type: "POST",
        data: $('#tax_form').serialize(),
        dataType: 'json',
        success: function (response) {
        	$('#loadingmessage').hide();
        	
            if (response.Status === 1) {
            	console.log(response.tax_filing_data['tax_filing_data']);
            	toastr.success(response.message, response.title);
            	tax_filing_history_table(response.tax_filing_data['tax_filing_data']);

            	tax_filing_data = response.tax_filing_data['tax_filing_data'];

	    		var num_tax_filing = parseInt(tax_filing_data.length) - 1;
	    		edit_tax_filing_id = tax_filing_data[num_tax_filing]["id"];

	    		$(".tax_id").val(tax_filing_data[num_tax_filing]["id"]);
	    		$("#coporate_tax_period").val(tax_filing_data[num_tax_filing]["coporate_tax_period"]);
	    		$("#tax_filing_period").val(tax_filing_data[num_tax_filing]["tax_filing_period"]);
	    		$("#tax_filing_date").val(tax_filing_data[num_tax_filing]["filing_date"]);
	    		$("#tax_filing_due_date").val(tax_filing_data[num_tax_filing]["tax_filing_due_date"]);
            }
            else
            {
            	toastr.error(response.message, response.title);
            	//console.log(response.error["year_end"]);
            	/*$("#validate_year_end_date").text(response[0].error);*/
            	if (response.error["coporate_tax_period"] != "")
            	{
            		var errorsYearEnd = '<span class="help-block">*' + response.error["coporate_tax_period"] + '</span>';
            		$( '#validate_coporate_tax_period' ).html( errorsYearEnd );

            	}
            	else
            	{
            		var errorsYearEnd = '';
            		$( '#validate_coporate_tax_period' ).html( errorsYearEnd );
            	}
            }
        }
    });
}

function delete_tax_filing(element){
	var tr = jQuery(element).parent().parent();
	var each_tax_filing_id = tr.find('input[name="each_tax_filing_id"]').val();
	if(each_tax_filing_id != undefined)
	{
		$('#loadingmessage').show();
		$.ajax({ //Upload common input
	        url: "masterclient/delete_tax_filing",
	        type: "POST",
	        data: {"tax_filing_id": each_tax_filing_id, "company_code": company_code},
	        dataType: 'json',
	        success: function (response) {
	        	//console.log(response);
	        	$('#loadingmessage').hide();
	        	if(response.tax_filing_data['tax_filing_data'] == false)
	        	{
	        		$(".tax_id").val("");
	        		first_tax_filing_id = "";
					$("#coporate_tax_period").val("");
					$("#tax_filing_period").val("");
					$("#tax_filing_date").val("");
					$("#tax_filing_due_date").val("");
					check_latest_fye_for_tax();
	        	}
	        	else
	        	{
	        		tax_filing_data = response.tax_filing_data['tax_filing_data'];

		        	var number_of_lgh = parseInt(tax_filing_data.length) - 1;

					edit_tax_filing_id = tax_filing_data[number_of_lgh]["id"];

					$(".tax_id").val(tax_filing_data[number_of_lgh]["id"]);
					$("#coporate_tax_period").val(tax_filing_data[number_of_lgh]["coporate_tax_period"]);
					$("#tax_filing_period").val(tax_filing_data[number_of_lgh]["tax_filing_period"]);
					$("#tax_filing_date").val(tax_filing_data[number_of_lgh]["filing_date"]);
					$("#tax_filing_due_date").val(tax_filing_data[number_of_lgh]["tax_filing_due_date"]);

	        	}
	        	
	        }
	    });
	}
	tr.remove();
}

$('.edit_tax_filing').live("click",function(){
	
    edit_tax_filing_id =  $(this).data("id");

    for(var i = 0; i < tax_filing_data.length; i++)
    {
    	if(tax_filing_data[i]["id"] == edit_tax_filing_id)
    	{
    		$(".tax_id").val(tax_filing_data[i]["id"]);
    		$("#coporate_tax_period").val(tax_filing_data[i]["coporate_tax_period"]);
    		$("#tax_filing_period").val(tax_filing_data[i]["tax_filing_period"]);
    		$("#tax_filing_date").val(tax_filing_data[i]["filing_date"]);
    		$("#tax_filing_due_date").val(tax_filing_data[i]["tax_filing_due_date"]);
    	}
    }

});