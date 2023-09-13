var edit_gst_filing_id, first_gst_filing_id = "";

check_latest_fye_for_gst();
gst_filing_history_table(gst_filing_data);

function check_latest_fye_for_gst()
{
	$(".gst_year_end").val("");

	$.ajax({
		type: "POST",
		url: "masterclient/check_latest_fye_for_gst",
		data: {"company_code": company_code}, // <--- THIS IS THE CHANGE
		dataType: "json",
		async: false,
		success: function(response)
		{
			//console.log(response);
			$(".gst_year_end").val(response['latest_fye']);
		}
	});
}

$(".gst_year_end").blur(function() {			    
	$( '#validate_gst_year_end' ).html( "" );
});

$('#update_gst').click(function(e){
	e.preventDefault();
	save_gst();
});

$('#gst_year_end').datepicker({
    format: 'dd MM yyyy'
});

$('#gst_filing_period1').datepicker({
    format: 'dd MM yyyy'
});

$('#gst_filing_period2').datepicker({
    format: 'dd MM yyyy'
});

$('#gst_filing_due_date').datepicker({
    format: 'dd MM yyyy'
});

$('#gst_filing_date').datepicker({
    format: 'dd MM yyyy'
});

$('#gst_de_registration_date').datepicker({
    format: 'dd MM yyyy'
});

function save_gst(){
	$('#loadingmessage').show();
	$.ajax({ //Upload common input
        url: "masterclient/add_gst_filing_info",
        type: "POST",
        data: $('#gst_form').serialize(),
        dataType: 'json',
        success: function (response) {
        	$('#loadingmessage').hide();
        	
            if (response.Status === 1) {
            	//console.log(response.tax_filing_data['tax_filing_data']);
            	toastr.success(response.message, response.title);
            	gst_filing_history_table(response.gst_filing_data['gst_filing_data']);

            	gst_filing_data = response.gst_filing_data['gst_filing_data'];

	    		var num_gst_filing = parseInt(gst_filing_data.length) - 1;
	    		edit_gst_filing_id = gst_filing_data[num_gst_filing]["id"];

	    		if(gst_filing_data[num_gst_filing]["gst_year_end"] != "")
				{
					$("#gst_year_end").prop('disabled', false);
					$("#gst_filing_cycle").prop('disabled', false);
					$("#gst_filing_period1").prop('disabled', false);
					$("#gst_filing_period2").prop('disabled', false);
					$("#gst_filing_due_date").prop('disabled', false);
					$("#gst_filing_date").prop('disabled', false);

					$(".de_register_date_section").hide();

					bool_de_register = true;

		    		$(".gst_id").val(gst_filing_data[num_gst_filing]["id"]);
		    		$("#gst_year_end").val(gst_filing_data[num_gst_filing]["gst_year_end"]);
		    		$("#gst_filing_cycle").val(gst_filing_data[num_gst_filing]["gst_filing_cycle"]);
		    		// $("#gst_filing_period1").val(gst_filing_data[num_gst_filing]["gst_filing_period1"]);
		    		// $("#gst_filing_period2").val(gst_filing_data[num_gst_filing]["gst_filing_period2"]);
		    		if(gst_filing_data[num_gst_filing]["gst_filing_period1"] != "")
		    		{
		    			$("#gst_filing_period1").datepicker("setDate", new Date(gst_filing_data[num_gst_filing]["gst_filing_period1"]));
					}
					if(gst_filing_data[num_gst_filing]["gst_filing_period2"] != "")
		    		{
						$('#gst_filing_period2').datepicker("setDate", new Date(gst_filing_data[num_gst_filing]["gst_filing_period2"]));
		    		}
		    		$("#gst_filing_due_date").val(gst_filing_data[num_gst_filing]["gst_filing_due_date"]);
		    		$("#gst_filing_date").val(gst_filing_data[num_gst_filing]["gst_filing_date"]);
		    		$("#gst_de_registration_date").val(gst_filing_data[num_gst_filing]["gst_de_registration_date"]);
		    	}
		    	else if(gst_filing_data[num_gst_filing]["gst_de_registration_date"] != "")
				{
					$("#gst_year_end").prop('disabled', true);
					$("#gst_filing_cycle").prop('disabled', true);
					$("#gst_filing_period1").prop('disabled', true);
					$("#gst_filing_period2").prop('disabled', true);
					$("#gst_filing_due_date").prop('disabled', true);
					$("#gst_filing_date").prop('disabled', true);

					$(".gst_id").val(gst_filing_data[num_gst_filing]["id"]);
					$("#gst_year_end").val('');
					$("#gst_filing_cycle").val('0');
					$("#gst_filing_period1").val('');
					$("#gst_filing_period2").val('');
					$("#gst_filing_due_date").val('');
					$("#gst_filing_date").val('');
					$("#gst_de_registration_date").val(gst_filing_data[num_gst_filing]["gst_de_registration_date"]);

					$(".de_register_date_section").show();

					bool_de_register = false;
				}
            }
            else
            {
            	toastr.error(response.message, response.title);
            	//console.log(response.error["year_end"]);
            	/*$("#validate_year_end_date").text(response[0].error);*/
            	if (response.error["gst_year_end"] != "" && response.error["gst_year_end"] != null)
            	{
            		var errorsGstYearEnd = '<span class="help-block">*' + response.error["gst_year_end"] + '</span>';
            		$( '#validate_gst_year_end' ).html( errorsGstYearEnd );

            	}
            	else
            	{
            		var errorsGstYearEnd = '';
            		$( '#validate_gst_year_end' ).html( errorsGstYearEnd );
            	}
            	
            	if (response.error["gst_de_registration_date"] != "" && response.error["gst_de_registration_date"] != null)
            	{
            		var errorsDeRegistrationDate = '<span class="help-block">*' + response.error["gst_de_registration_date"] + '</span>';
            		$( '#validate_gst_de_registration_date' ).html( errorsDeRegistrationDate );

            	}
            	else
            	{
            		var errorsDeRegistrationDate = '';
            		$( '#validate_gst_de_registration_date' ).html( errorsDeRegistrationDate );
            	}
            }
        }
    });
}

function gst_filing_history_table(gst_filing)
{	
	if(gst_filing)
	{
		console.log(gst_filing);
		$(".gst_filing_info_for_each_company").remove();
		var number_of_length = parseInt(gst_filing.length) - 1;
		edit_gst_filing_id = gst_filing[number_of_length]["id"];

		if(gst_filing[number_of_length]["gst_year_end"] != "")
		{
			$("#gst_year_end").prop('disabled', false);
			$("#gst_filing_cycle").prop('disabled', false);
			$("#gst_filing_period1").prop('disabled', false);
			$("#gst_filing_period2").prop('disabled', false);
			$("#gst_filing_due_date").prop('disabled', false);
			$("#gst_filing_date").prop('disabled', false);

			$(".de_register_date_section").hide();

			bool_de_register = true;

			$(".gst_id").val(gst_filing[number_of_length]["id"]);
			$("#gst_year_end").val(gst_filing[number_of_length]["gst_year_end"]);
			$("#gst_filing_cycle").val(gst_filing[number_of_length]["gst_filing_cycle"]);
			// $("#gst_filing_period1").val(gst_filing[number_of_length]["gst_filing_period1"]);
			// $("#gst_filing_period2").val(gst_filing[number_of_length]["gst_filing_period2"]);
			if(gst_filing[number_of_length]["gst_filing_period1"] != "")
		    {
				$("#gst_filing_period1").datepicker("setDate", new Date(gst_filing[number_of_length]["gst_filing_period1"]));
			}
			if(gst_filing[number_of_length]["gst_filing_period2"] != "")
		    {
				$('#gst_filing_period2').datepicker("setDate", new Date(gst_filing[number_of_length]["gst_filing_period2"]));
			}
			$("#gst_filing_due_date").val(gst_filing[number_of_length]["gst_filing_due_date"]);
			$("#gst_filing_date").val(gst_filing[number_of_length]["gst_filing_date"]);
			$("#gst_de_registration_date").val(gst_filing[number_of_length]["gst_de_registration_date"]);
		}
		else if(gst_filing[number_of_length]["gst_de_registration_date"] != "")
		{
			$("#gst_year_end").prop('disabled', true);
			$("#gst_filing_cycle").prop('disabled', true);
			$("#gst_filing_period1").prop('disabled', true);
			$("#gst_filing_period2").prop('disabled', true);
			$("#gst_filing_due_date").prop('disabled', true);
			$("#gst_filing_date").prop('disabled', true);

			$(".gst_id").val(gst_filing[number_of_length]["id"]);
			$("#gst_year_end").val('');
			$("#gst_filing_cycle").val('0');
			$("#gst_filing_period1").val('');
			$("#gst_filing_period2").val('');
			$("#gst_filing_due_date").val('');
			$("#gst_filing_date").val('');
			$("#gst_de_registration_date").val(gst_filing[number_of_length]["gst_de_registration_date"]);

			$(".de_register_date_section").show();

			bool_de_register = false;
		}

		get_gst_filing_cycle(gst_filing[number_of_length]["gst_filing_cycle"]);

		for(var i = 0; i < gst_filing.length; i++)
	    {
	    	if(i == 0)
	    	{
	    		first_gst_filing_id = gst_filing[i]["id"];
	    	}

	        $b=""; 
	        $b += '<tr class="gst_filing_info_for_each_company">';
	        $b += '<td class="hidden"><input type="text" class="form-control" name="each_gst_filing_id" id="each_gst_filing_id" value="'+gst_filing[i]["id"]+'"/></td>';
	        $b += '<td style="width: 20px !important;">'+(i+1)+'</td>';
	        if(access_right_client_module == "read" || access_right_filing_module == "read")
			{
	        	$b += '<td style="text-align: center">'+gst_filing[i]["gst_year_end"]+'</td>';
	        }
	        else
	        {
	        	$b += '<td style="text-align: center"><a href="javascript:void(0)" class="edit_gst_filing" data-id="'+gst_filing[i]["id"]+'" id="edit_gst_filing">'+gst_filing[i]["gst_year_end"]+'</a></td>';
	        }
	        
	        $b += '<td style="text-align: center">'+((gst_filing[i]["gst_filing_cycle_name"] != null)?gst_filing[i]["gst_filing_cycle_name"]:"")+'</td>';
	        if(gst_filing[i]["gst_filing_period1"] != "" && gst_filing[i]["gst_filing_period2"] != "")
	        {
	        	$b += '<td style="text-align: center">'+(gst_filing[i]["gst_filing_period1"] + ' - ' + gst_filing[i]["gst_filing_period2"])+'</td>';
	        }
	        else if (gst_filing[i]["gst_filing_period1"] != "" && gst_filing[i]["gst_filing_period2"] == "")
	        {
	        	$b += '<td style="text-align: center">'+(gst_filing[i]["gst_filing_period1"])+'</td>';
	        }
	        else if (gst_filing[i]["gst_filing_period1"] == "" && gst_filing[i]["gst_filing_period2"] != "")
	        {
	        	$b += '<td style="text-align: center">'+(gst_filing[i]["gst_filing_period2"])+'</td>';
	        }
	        else
	        {
	        	$b += '<td style="text-align: center"></td>';
	        }
	        $b += '<td style="text-align: center">'+(gst_filing[i]["gst_filing_due_date"])+'</td>';
	        $b += '<td style="text-align: center">'+(gst_filing[i]["gst_filing_date"])+'</td>';
	        // $b += '<td style="text-align: center">'+(gst_filing[i]["gst_de_registration_date"])+'</td>';

        	if(access_right_client_module == "read" || access_right_filing_module == "read")
			{
	        	$b += '<td style="text-align: center">'+gst_filing[i]["gst_de_registration_date"]+'</td>';
	        }
	        else
	        {	
	        	if(gst_filing[i]["gst_de_registration_date"] != "")
        		{
	        		$b += '<td style="text-align: center"><a href="javascript:void(0)" class="edit_gst_filing" data-id="'+gst_filing[i]["id"]+'" id="edit_gst_filing">'+gst_filing[i]["gst_de_registration_date"]+'</a></td>';
	        	}
	        	else
	        	{
	        		$b += '<td style="text-align: center">'+gst_filing[i]["gst_de_registration_date"]+'</td>';
	        	}
	        }
	        
	        $b += '<td><button type="button" class="btn btn-primary delete_gst_filing_button" onclick="delete_gst_filing(this)">Delete</button></td>';
	        $b += '</tr>';

	        $("#gst_filing_table").append($b);
	    }
	}
}

function delete_gst_filing(element){
	var tr = jQuery(element).parent().parent();
	var each_gst_filing_id = tr.find('input[name="each_gst_filing_id"]').val();
	if(each_gst_filing_id != undefined)
	{
		$('#loadingmessage').show();
		$.ajax({ //Upload common input
	        url: "masterclient/delete_gst_filing",
	        type: "POST",
	        data: {"gst_filing_id": each_gst_filing_id, "company_code": company_code},
	        dataType: 'json',
	        success: function (response) {
	        	//console.log(response);
	        	$('#loadingmessage').hide();
	        	if(response.gst_filing_data['gst_filing_data'] == false)
	        	{
	        		$(".gst_filing_id").val("");
	        		first_gst_filing_id = "";
					$("#gst_year_end").val("");
					$("#gst_filing_cycle").val("0");
					$("#gst_filing_period1").val("");
					$("#gst_filing_period2").val("");
					$("#gst_filing_due_date").val("");
					$("#gst_filing_date").val("");
					$("#gst_de_registration_date").val("");
					check_latest_fye_for_gst();
	        	}
	        	else
	        	{
	        		gst_filing_data = response.gst_filing_data['gst_filing_data'];

		        	var number_of_lgh = parseInt(gst_filing_data.length) - 1;

					edit_tax_filing_id = gst_filing_data[number_of_lgh]["id"];

					if(gst_filing_data[number_of_lgh]["gst_year_end"] != "")
					{
						$("#gst_year_end").prop('disabled', false);
						$("#gst_filing_cycle").prop('disabled', false);
						$("#gst_filing_period1").prop('disabled', false);
						$("#gst_filing_period2").prop('disabled', false);
						$("#gst_filing_due_date").prop('disabled', false);
						$("#gst_filing_date").prop('disabled', false);

						$(".de_register_date_section").hide();

						bool_de_register = true;

						$(".gst_id").val(gst_filing_data[number_of_lgh]["id"]);
						$("#gst_year_end").val(gst_filing_data[number_of_lgh]["gst_year_end"]);
						$("#gst_filing_cycle").val(gst_filing_data[number_of_lgh]["gst_filing_cycle"]);
						// $("#gst_filing_period1").val(gst_filing_data[number_of_lgh]["gst_filing_period1"]);
						// $("#gst_filing_period2").val(gst_filing_data[number_of_lgh]["gst_filing_period2"]);
						if(gst_filing_data[number_of_lgh]["gst_filing_period1"] != "")
		    			{
							$("#gst_filing_period1").datepicker("setDate", new Date(gst_filing_data[number_of_lgh]["gst_filing_period1"]));
						}
						if(gst_filing_data[number_of_lgh]["gst_filing_period2"] != "")
		    			{
							$('#gst_filing_period2').datepicker("setDate", new Date(gst_filing_data[number_of_lgh]["gst_filing_period2"]));
						}
						$("#gst_filing_due_date").val(gst_filing_data[number_of_lgh]["gst_filing_due_date"]);
						$("#gst_filing_date").val(gst_filing_data[number_of_lgh]["gst_filing_date"]);
						$("#gst_de_registration_date").val(gst_filing_data[number_of_lgh]["gst_de_registration_date"]);
					}
					else if(gst_filing_data[number_of_lgh]["gst_de_registration_date"] != "")
					{
						$("#gst_year_end").prop('disabled', true);
						$("#gst_filing_cycle").prop('disabled', true);
						$("#gst_filing_period1").prop('disabled', true);
						$("#gst_filing_period2").prop('disabled', true);
						$("#gst_filing_due_date").prop('disabled', true);
						$("#gst_filing_date").prop('disabled', true);

						$(".gst_id").val(gst_filing_data[number_of_lgh]["id"]);
						$("#gst_year_end").val('');
						$("#gst_filing_cycle").val('0');
						$("#gst_filing_period1").val('');
						$("#gst_filing_period2").val('');
						$("#gst_filing_due_date").val('');
						$("#gst_filing_date").val('');
						$("#gst_de_registration_date").val(gst_filing_data[number_of_lgh]["gst_de_registration_date"]);

						$(".de_register_date_section").show();

						bool_de_register = false;
					}

	        	}
	        	
	        }
	    });
	}
	tr.remove();
}

$('.edit_gst_filing').live("click",function(){
	
    edit_gst_filing_id =  $(this).data("id");

    for(var i = 0; i < gst_filing_data.length; i++)
    {
    	if(gst_filing_data[i]["id"] == edit_gst_filing_id)
    	{
    		if(gst_filing_data[i]["gst_year_end"] != "")
			{
				$("#gst_year_end").prop('disabled', false);
				$("#gst_filing_cycle").prop('disabled', false);
				$("#gst_filing_period1").prop('disabled', false);
				$("#gst_filing_period2").prop('disabled', false);
				$("#gst_filing_due_date").prop('disabled', false);
				$("#gst_filing_date").prop('disabled', false);

				$(".de_register_date_section").hide();

				bool_de_register = true;

	    		$(".gst_id").val(gst_filing_data[i]["id"]);
				$("#gst_year_end").val(gst_filing_data[i]["gst_year_end"]);
				$("#gst_filing_cycle").val(gst_filing_data[i]["gst_filing_cycle"]);
				// $("#gst_filing_period1").val(gst_filing_data[i]["gst_filing_period1"]);
				// $("#gst_filing_period2").val(gst_filing_data[i]["gst_filing_period2"]);
				if(gst_filing_data[i]["gst_filing_period1"] != "")
		    	{
					$("#gst_filing_period1").datepicker("setDate", new Date(gst_filing_data[i]["gst_filing_period1"]));
				}
				if(gst_filing_data[i]["gst_filing_period1"] != "")
		    	{
					$('#gst_filing_period2').datepicker("setDate", new Date(gst_filing_data[i]["gst_filing_period2"]));
				}
				$("#gst_filing_due_date").val(gst_filing_data[i]["gst_filing_due_date"]);
				$("#gst_filing_date").val(gst_filing_data[i]["gst_filing_date"]);
				$("#gst_de_registration_date").val(gst_filing_data[i]["gst_de_registration_date"]);
			}
			else if(gst_filing_data[i]["gst_de_registration_date"] != "")
			{
				$("#gst_year_end").prop('disabled', true);
				$("#gst_filing_cycle").prop('disabled', true);
				$("#gst_filing_period1").prop('disabled', true);
				$("#gst_filing_period2").prop('disabled', true);
				$("#gst_filing_due_date").prop('disabled', true);
				$("#gst_filing_date").prop('disabled', true);

				$(".gst_id").val(gst_filing_data[i]["id"]);
				$("#gst_year_end").val('');
				$("#gst_filing_cycle").val('0');
				$("#gst_filing_period1").val('');
				$("#gst_filing_period2").val('');
				$("#gst_filing_due_date").val('');
				$("#gst_filing_date").val('');
				$("#gst_de_registration_date").val(gst_filing_data[i]["gst_de_registration_date"]);

				$(".de_register_date_section").show();

				bool_de_register = false;
			}
		}
    }

});