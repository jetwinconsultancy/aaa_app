//var total_number_of_share = 0;


function directorFee(cbox) {
	if (cbox.checked) 
	{
		$("#loadingmessage").show();
		$.post("transaction/get_resign_director_info", {id: transaction_id, company_code: transaction_company_code, transaction_task_id: transaction_task_id, registration_no: $(".uen_section #uen").val()}, function(data){
	        $("#loadingmessage").hide();
	        console.log(JSON.parse(data));
	        array_for_director_info = JSON.parse(data);

	        for(var i = 0; i < array_for_director_info.length; i++)
		    {
		        $a="";
	            $a += '<tr class="row_director_fee">';
	            $a += '<td><input type="text" style="text-transform:uppercase;" name="director_fee_name[]" id="name" class="form-control" value="'+ (array_for_director_info[i]["company_name"]!=null ? array_for_director_info[i]["company_name"] : array_for_director_info[i]["name"]) +'" readonly/><input type="hidden" class="form-control" name="director_fee_identification_register_no[]" id="director_fee_identification_register_no" value="'+ (array_for_director_info[i]["identification_no"]!=null ? array_for_director_info[i]["identification_no"] : array_for_director_info[i]["register_no"]) +'"/><div class="hidden"><input type="text" class="form-control" name="director_fee_client_officer_id[]" id="client_officer_id" value="'+array_for_director_info[i]["id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="director_fee_officer_id[]" id="officer_id" value="'+array_for_director_info[i]["officer_id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="director_fee_officer_field_type[]" id="officer_field_type" value="'+array_for_director_info[i]["field_type"]+'"/></div></td>';
	            $a += '<td><input type="text" style="text-transform:uppercase;" name="director_fee[]" id="director_fee" class="numberdes form-control" value=""/></td>'
	            $a += '</tr>';

	            $("#director_fee_add").append($a);
	        }
	    });
		$(".director_fee_div").show();
	}
	else
	{
		$(".director_fee_div").hide();
		$(".row_director_fee").remove();
	}
}

function dividendCheckBox(cbox) {
	if (cbox.checked) 
	{
		$("#loadingmessage").show();
		$.post("transaction/get_all_member", {id: transaction_id, company_code: transaction_company_code, transaction_task_id: transaction_task_id, registration_no: $(".uen_section #uen").val()}, function(data){
	        $("#loadingmessage").hide();
	        console.log(JSON.parse(data));
	        array_for_member_info = JSON.parse(data);

	        for(var i = 0; i < array_for_member_info.length; i++)
		    {
		    	// if (array_for_member_info[i + 1]['officer_id'] != array_for_member_info[i]['officer_id'] && array_for_member_info[i + 1]['field_type'] != array_for_member_info[i]['field_type']) 
		    	// {
			        $a="";
		            $a += '<tr class="row_dividend" data-numberOfShare="'+array_for_member_info[i]["number_of_share"]+'">';
		            $a += '<td><input type="text" style="text-transform:uppercase;" name="dividend_name[]" id="name" class="form-control" value="'+ (array_for_member_info[i]["company_name"]!=null ? array_for_member_info[i]["company_name"] : array_for_member_info[i]["name"] != null ? array_for_member_info[i]["name"] : array_for_member_info[i]["client_company_name"]) +'" readonly/><input type="hidden" class="form-control" name="dividend_identification_register_no[]" id="dividend_identification_register_no" value="'+ (array_for_member_info[i]["identification_no"]!=null ? array_for_member_info[i]["identification_no"] : array_for_member_info[i]["register_no"]) +'"/><div class="hidden"><input type="text" class="form-control" name="dividend_client_officer_id[]" id="client_officer_id" value="'+array_for_member_info[i]["id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="dividend_officer_id[]" id="officer_id" value="'+array_for_member_info[i]["officer_id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="dividend_officer_field_type[]" id="officer_field_type" value="'+array_for_member_info[i]["field_type"]+'"/></div></td>';
		            $a += '<td><input type="text" style="text-transform:uppercase;" name="dividend[]" id="dividend_fee" class="form-control" value="" readonly/><input type="hidden" name="number_of_share[]" id="number_of_share" class="form-control" value="'+array_for_member_info[i]["number_of_share"]+'"/></td>'
		            $a += '</tr>';

		            $("#dividend_add").append($a);

		            total_number_of_share = total_number_of_share + parseInt(array_for_member_info[i]["number_of_share"]);
		        //}
	        }
	    });
		$(".dividend_div").show();
	}
	else
	{
		$(".dividend_div").hide();
		$(".row_dividend").remove();
	}
}

function amountDueFromDirector(cbox) {
	if (cbox.checked) 
	{
		$("#loadingmessage").show();
		$.post("transaction/get_resign_director_info", {id: transaction_id, company_code: transaction_company_code, transaction_task_id: transaction_task_id, registration_no: $(".uen_section #uen").val()}, function(data){
	        $("#loadingmessage").hide();
	        console.log(JSON.parse(data));
	        array_for_director_info = JSON.parse(data);

	        for(var i = 0; i < array_for_director_info.length; i++)
		    {
		        $a="";
	            $a += '<tr class="row_amount_due">';
	            $a += '<td><input type="text" style="text-transform:uppercase;" name="amount_due_name[]" id="name" class="form-control" value="'+ (array_for_director_info[i]["company_name"]!=null ? array_for_director_info[i]["company_name"] : array_for_director_info[i]["name"]) +'" readonly/><input type="hidden" class="form-control" name="amount_due_identification_register_no[]" id="amount_due_identification_register_no" value="'+ (array_for_director_info[i]["identification_no"]!=null ? array_for_director_info[i]["identification_no"] : array_for_director_info[i]["register_no"]) +'"/><div class="hidden"><input type="text" class="form-control" name="amount_due_client_officer_id[]" id="client_officer_id" value="'+array_for_director_info[i]["id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="amount_due_officer_id[]" id="officer_id" value="'+array_for_director_info[i]["officer_id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="amount_due_officer_field_type[]" id="officer_field_type" value="'+array_for_director_info[i]["field_type"]+'"/></div></td>';
	            $a += '<td><input type="text" style="text-transform:uppercase;" name="amount_due[]" id="amount_due" class="form-control numberdes" value=""/></td>'
	            $a += '</tr>';

	            $("#amount_due_from_director_add").append($a);
	        }
	    });
		$(".amount_due_from_director_div").show();
	}
	else
	{
		$(".amount_due_from_director_div").hide();
		$(".row_amount_due").remove();
	}
}

function removeCommas(str) {
    while (str.search(",") >= 0) {
        str = (str + "").replace(',', '');
    }
    return str;
};

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

function directorRetirement(cbox) {
	if (cbox.checked) 
	{
		$("#loadingmessage").show();
		$.post("transaction/get_all_director_retiring", {id: transaction_id, company_code: transaction_company_code, transaction_task_id: transaction_task_id, registration_no: $(".uen_section #uen").val()}, function(data){
	        $("#loadingmessage").hide();
	        console.log(JSON.parse(data));
	        array_for_director_retirement_info = JSON.parse(data);

	        for(var i = 0; i < array_for_director_retirement_info.length; i++)
		    {
		        $a="";
	            $a += '<tr class="row_director_retirement">';
	            $a += '<td>'+(i + 1)+'<input type="hidden" class="form-control" name="director_retiring_client_officer_id[]" value="'+array_for_director_retirement_info[i]['id']+'"/></td>';
	            $a += '<td>'+array_for_director_retirement_info[i]['identification_no']+'<input type="hidden" class="form-control" name="director_retiring_identification_no[]" value="'+array_for_director_retirement_info[i]['identification_no']+'"/></td>';
	            $a += '<td>'+array_for_director_retirement_info[i]['name']+'<input type="hidden" class="form-control" name="director_retiring_name[]" value="'+array_for_director_retirement_info[i]['name']+'"/></td>';
	            $a += '<td><input type="checkbox" name="director_retiring_checkbox"/><input type="hidden" name="hidden_director_retiring_checkbox[]" value="'+array_for_director_retirement_info[i]['retiring']+'"/><input type="hidden" class="form-control" name="director_retiring_officer_id[]" value="'+array_for_director_retirement_info[i]['officer_id']+'"/><input type="hidden" class="form-control" name="director_retiring_field_type[]" value="'+array_for_director_retirement_info[i]['field_type']+'"/></td>';
	            $a += '</tr>';

	            $("#director_retirement_add").append($a);

	            $("[name='director_retiring_checkbox']").bootstrapSwitch({
				    //state: state_checkbox,
				    size: 'small',
				    onColor: 'primary',
				    onText: 'YES',
				    offText: 'NO',
				    // Text of the center handle of the switch
				    labelText: '&nbsp',
				    // Width of the left and right sides in pixels
				    handleWidth: '20px',
				    // Width of the center handle in pixels
				    labelWidth: 'auto',
				    baseClass: 'bootstrap-switch',
				    wrapperClass: 'wrapper'


				});

				// Triggered on switch state change.
				$("[name='director_retiring_checkbox']").on('switchChange.bootstrapSwitch', function(event, state) {
					console.log($(event.target));
				    if(state == true)
				    {
				        $(event.target).parent().parent().parent().find("[name='hidden_director_retiring_checkbox[]']").val(1);

				    }
				    else
				    {
				       $(event.target).parent().parent().parent().find("[name='hidden_director_retiring_checkbox[]']").val(0);
				    }
				});
	        }
	    });

		$(".director_retirement_div").show();
	}
	else
	{
		$(".director_retirement_div").hide();
		$(".row_director_retirement").remove();
	}
}

function reappointmentAuditor(cbox) {
	if (cbox.checked) 
	{
		$("#loadingmessage").show();
		$.post("transaction/get_resign_auditor_info", {id: transaction_id, company_code: transaction_company_code, transaction_task_id: transaction_task_id, registration_no: $(".uen_section #uen").val()}, function(data){
	        $("#loadingmessage").hide();
	        console.log(JSON.parse(data));
	        array_for_auditor_info = JSON.parse(data);

	        for(var i = 0; i < array_for_auditor_info.length; i++)
		    {
		        $a="";
	            $a += '<tr class="row_reappointment_auditor">';
	            $a += '<td><input type="text" style="text-transform:uppercase;" name="reappointment_auditor_name[]" id="name" class="form-control" value="'+ (array_for_auditor_info[i]["company_name"]!=null ? array_for_auditor_info[i]["company_name"] : array_for_auditor_info[i]["name"]) +'" readonly/><input type="hidden" class="form-control" name="reappointment_auditor_identification_register_no[]" id="reappointment_auditor_identification_register_no" value="'+ (array_for_auditor_info[i]["identification_no"]!=null ? array_for_auditor_info[i]["identification_no"] : array_for_auditor_info[i]["register_no"]) +'"/><div class="hidden"><input type="text" class="form-control" name="reappointment_auditor_client_officer_id[]" id="client_officer_id" value="'+array_for_auditor_info[i]["id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="reappointment_auditor_officer_id[]" id="officer_id" value="'+array_for_auditor_info[i]["officer_id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="reappointment_auditor_officer_field_type[]" id="officer_field_type" value="'+array_for_auditor_info[i]["field_type"]+'"/></div></td>';
	            $a += '</tr>';

	            $("#reappointment_auditor_add").append($a);
	        }
	    });
		$(".reappointment_auditor_div").show();
	}
	else
	{
		$(".reappointment_auditor_div").hide();
		$(".row_reappointment_auditor").remove();
	}
}



$(document).on('click',"#submitAgmArInfo",function(e){
    $('#loadingmessage').show();
    $(".fye_date").prop("disabled", false);
    var isDisabled = $('.agm_date_info').prop('disabled');
    if(isDisabled)
    {
    	$(".agm_date_info").prop("disabled", false);
    }
    var check_is_disabled_audited_fs = $('#audited_fs').prop('disabled');
    if(check_is_disabled_audited_fs)
    {
    	$('#audited_fs').prop('disabled', false);
    }
    $.ajax({ //Upload common input
      url: "transaction/save_agm_ar",
      type: "POST",
      data: $('form#agm_ar_form').serialize()+ '&transaction_code=' + $('#transaction_code').val() + '&transaction_task_id=' + $('#transaction_task').val() + '&registration_no=' + $('#uen').val(),
      dataType: 'json',
      success: function (response,data) {
        $('#loadingmessage').hide();
        //console.log(response);
        	$(".fye_date").prop("disabled", true);
        	$("#transaction_trans #transaction_master_id").val(response.transaction_master_id);
            $("#transaction_trans #transaction_code").val(response.transaction_code);
        	if(isDisabled)
        	{
        		$(".agm_date_info").prop("disabled", true);
        	}
        	if(check_is_disabled_audited_fs)
		    {
		    	$('#audited_fs').prop('disabled', true);
		    }
			if (response.Status === 1) 
			{
				toastr.success(response.message, response.title);
				$(".transaction_agm_ar_id").val(response.transaction_agm_ar_id);
				
			}
        }
    })
});

