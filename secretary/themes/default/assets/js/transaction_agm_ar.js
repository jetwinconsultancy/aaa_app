function directorFee(cbox) {
	if (cbox.checked) 
	{
		$("#loadingmessage").show();
		$.post("transaction/get_resign_director_info", {id: transaction_id, company_code: transaction_company_code, transaction_task_id: transaction_task_id, registration_no: $(".uen_section #uen").val()}, function(data){
	        $("#loadingmessage").hide();
	        if(data != "")
	        {
		        data = JSON.parse(data);
		        array_for_director_info = data["director"];
		        array_currency_info = data["currency"];
		        if(array_for_director_info.length > 0)
		        {
			        for(var i = 0; i < array_for_director_info.length; i++)
				    {
				        $a = "";
			            $a += '<tr class="row_director_fee director_fee'+i+'">';
			            $a += '<td><input type="text" style="text-transform:uppercase;" name="director_fee_name[]" id="name" class="form-control" value="'+ (array_for_director_info[i]["company_name"]!=null ? array_for_director_info[i]["company_name"] : array_for_director_info[i]["name"]) +'" readonly/><input type="hidden" class="form-control" name="director_fee_identification_register_no[]" id="director_fee_identification_register_no" value="'+ (array_for_director_info[i]["identification_no"]!=null ? array_for_director_info[i]["identification_no"] : array_for_director_info[i]["register_no"]) +'"/><div class="hidden"><input type="text" class="form-control" name="director_fee_client_officer_id[]" id="client_officer_id" value="'+array_for_director_info[i]["id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="director_fee_officer_id[]" id="officer_id" value="'+array_for_director_info[i]["officer_id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="director_fee_officer_field_type[]" id="officer_field_type" value="'+array_for_director_info[i]["field_type"]+'"/></div></td>';
			            $a += '<td><select class="form-control currency" name="currency[]" id="currency'+i+'"><option value="0">Select Currency</option></select></td>';
			            $a += '<td><input type="text" style="text-align:right;" name="salary[]" id="salary'+i+'" class="numberdes form-control salary" value=""/></td>';
			            $a += '<td><input type="text" style="text-align:right;" name="cpf[]" id="cpf'+i+'" class="numberdes form-control cpf" value=""/></td>';
			            $a += '<td><input type="text" style="text-align:right;" name="director_fee[]" id="director_fee'+i+'" class="numberdes form-control director_fee" value=""/></td>';
			            $a += '<td><input type="text" style="text-align:right;" name="total_director_fee[]" id="total_director_fee'+i+'" class="numberdes form-control total_director_fee" value="" readonly="true"/></td>';
			            $a += '</tr>';

			            $("#director_fee_add").append($a);

			            $.each(array_currency_info, function(key, val) {
		                    var option = $('<option />');
		                    option.attr('value', key).text(val);
		                    $(".director_fee"+i).find(".currency").append(option);
		                });
			        }
			    }
			}
		    else
		    {
		    	$z = ""; 
                $z += '<tr class="row_director_fee">';
                $z += '<td colspan="6" style="text-align:center;"><span style="font-weight:bold; font-size:20px;">N/A</span></td>';
                $z += '</tr>';

                $("#director_fee_add").append($z);
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

$(document).on('change','.row_director_fee .currency',function(e){
	$(".row_director_fee .currency").val($(this).val());
});

$(document).on('change','.salary',function(e){
	var total = $(this).parent().parent().find(".total_director_fee").val();
	var salary = $(this).val();
	var cpf = $(this).parent().parent().find(".cpf").val();
	var director_fee = $(this).parent().parent().find(".director_fee").val();

	if(salary == "")
	{
		salary = "0";
	}
	else if(total == "")
	{
		total = "0";
	}

	if(cpf != "")
	{
		total = parseFloat(salary.replace(/\,/g,''), 2) + parseFloat(cpf.replace(/\,/g,''), 2);
	}
	else
	{
		total = parseFloat(salary.replace(/\,/g,''), 2) + 0;
	}

	if(director_fee != "")
	{
		total = total + parseFloat(director_fee.replace(/\,/g,''), 2);
	}
	else
	{
		total = total + 0;
	}

	$(this).parent().parent().find(".total_director_fee").val(addCommas(total));
});

$(document).on('change','.cpf',function(e){
	var total = $(this).parent().parent().find(".total_director_fee").val();
	var salary = $(this).parent().parent().find(".salary").val();
	var cpf = $(this).val();
	var director_fee = $(this).parent().parent().find(".director_fee").val();

	if(cpf == "")
	{
		cpf = "0";
	}
	else if(total == "")
	{
		total = "0";
	}

	if(salary != "")
	{
		total = parseFloat(cpf.replace(/\,/g,''), 2) + parseFloat(salary.replace(/\,/g,''), 2);
	}
	else
	{
		total = parseFloat(cpf.replace(/\,/g,''), 2) + 0;
	}

	if(director_fee != "")
	{
		total = total + parseFloat(director_fee.replace(/\,/g,''), 2);
	}
	else
	{
		total = total + 0;
	}

	$(this).parent().parent().find(".total_director_fee").val(addCommas(total));
});

$(document).on('change','.director_fee',function(e){
	var total = $(this).parent().parent().find(".total_director_fee").val();
	var salary = $(this).parent().parent().find(".salary").val();
	var cpf = $(this).parent().parent().find(".cpf").val();
	var director_fee = $(this).val();

	if(director_fee == "")
	{
		director_fee = "0";
	}
	else if(total == "")
	{
		total = "0";
	}

	if(salary != "" && salary != undefined)
	{
		total = parseFloat(director_fee.replace(/\,/g,''), 2) + parseFloat(salary.replace(/\,/g,''), 2);
	}
	else
	{
		total = parseFloat(director_fee.replace(/\,/g,''), 2) + 0;
	}

	if(cpf != ""  && cpf != undefined)
	{
		total = total + parseFloat(cpf.replace(/\,/g,''), 2);
	}
	else
	{
		total = total + 0;
	}

	$(this).parent().parent().find(".total_director_fee").val(addCommas(total));
});

$(document).on('change','#require_hold_agm_list',function(e){
	$(".agm_date_info").val("");
    $(".date_fs_sent_to_member").val("");
	if($(this).val() == 1)
	{
		$(".shorter_notice_section").show();
		$(".notice_date_section").show();
		$(".agm_time_section").show();
		$(".agm_date_section").show();
		$(".date_fs_section").hide();
	}
	else if($(this).val() == 2 || $(this).val() == 4)
	{
		$(".shorter_notice_section").hide();
		$(".notice_date_section").hide();
		$(".agm_time_section").hide();
		$(".agm_date_section").hide();
		$(".date_fs_section").show();
	}
	else if($(this).val() == 3)
	{
		$(".shorter_notice_section").hide();
		$(".notice_date_section").hide();
		$(".agm_time_section").hide();
		$(".agm_date_section").hide();
		$(".date_fs_section").hide();
	}
	else
	{
		$(".shorter_notice_section").show();
		$(".notice_date_section").show();
		$(".agm_time_section").show();
		$(".agm_date_section").hide();
		$(".date_fs_section").hide();
	}
});

function amountDueFromDirector(cbox) {
	if (cbox.checked) 
	{
		$("#loadingmessage").show();
		$.post("transaction/get_resign_director_info", {id: transaction_id, company_code: transaction_company_code, transaction_task_id: transaction_task_id, registration_no: $(".uen_section #uen").val()}, function(data){
	        $("#loadingmessage").hide();
	        if(data != "")
	        {
		        data = JSON.parse(data);
		        array_for_director_info = data["director"];

		        for(var i = 0; i < array_for_director_info.length; i++)
			    {
			        $a="";
		            $a += '<tr class="row_amount_due">';
		            $a += '<td><input type="text" style="text-transform:uppercase;" name="amount_due_name[]" id="name" class="form-control" value="'+ (array_for_director_info[i]["company_name"]!=null ? array_for_director_info[i]["company_name"] : array_for_director_info[i]["name"]) +'" readonly/><input type="hidden" class="form-control" name="amount_due_identification_register_no[]" id="amount_due_identification_register_no" value="'+ (array_for_director_info[i]["identification_no"]!=null ? array_for_director_info[i]["identification_no"] : array_for_director_info[i]["register_no"]) +'"/><div class="hidden"><input type="text" class="form-control" name="amount_due_client_officer_id[]" id="client_officer_id" value="'+array_for_director_info[i]["id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="amount_due_officer_id[]" id="officer_id" value="'+array_for_director_info[i]["officer_id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="amount_due_officer_field_type[]" id="officer_field_type" value="'+array_for_director_info[i]["field_type"]+'"/></div></td>';
		            $a += '<td><input type="text" style="text-transform:uppercase;text-align:right;" name="amount_due[]" id="amount_due" class="form-control numberdes" value=""/></td>'
		            $a += '</tr>';

		            $("#amount_due_from_director_add").append($a);
		        }
		    }
		    else
		    {
		    	$z = ""; 
                $z += '<tr class="row_director_fee">';
                $z += '<td colspan="2" style="text-align:center;"><span style="font-weight:bold; font-size:20px;">N/A</span></td>';
                $z += '</tr>';

                $("#amount_due_from_director_add").append($z);
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

$(document).on('change','#reappointment_auditor_name',function(e){
	var client_officer_id = $(this).find(':selected').data("client_officer_id");
	var officer_id = $(this).find(':selected').data("officer_id");
	var officer_field_type = $(this).find(':selected').data("officer_field_type");
	var identification_register_no = $(this).find(':selected').data("identification_register_no");

	$(".row_reappointment_auditor #client_officer_id").val(client_officer_id);
	$(".row_reappointment_auditor #officer_id").val(officer_id);
	$(".row_reappointment_auditor #officer_field_type").val(officer_field_type);
	$(".row_reappointment_auditor #reappointment_auditor_identification_register_no").val(identification_register_no);
});

function create_reappoint_auditor_interface(data)
{
	$a="";
    $a += '<tr class="row_reappointment_auditor">';
    $a += '<input type="hidden" class="form-control" name="reappointment_auditor_client_officer_id[]" id="client_officer_id" value=""/><input type="hidden" class="form-control" name="reappointment_auditor_officer_id[]" id="officer_id" value=""/><input type="hidden" class="form-control" name="reappointment_auditor_officer_field_type[]" id="officer_field_type" value=""/><input type="hidden" class="form-control" name="reappointment_auditor_identification_register_no[]" id="reappointment_auditor_identification_register_no" value=""/>';
    $a += '<td><select class="form-control" style="text-align:right;" name="reappointment_auditor_name[]" id="reappointment_auditor_name"><option value="0" data-client_officer_id="" data-officer_id="" data-officer_field_type="" data-identification_register_no=""></option></select></td>';
    $a += '</tr>';

    $("#reappointment_auditor_add").append($a);

    if(data)
    {
        array_for_auditor_info = JSON.parse(data);
        if(array_for_auditor_info.length > 0)
		{
            $.each(array_for_auditor_info, function(key, val) {
                var option = $('<option />');
                var auditor_name = (val["company_name"]!=null ? val["company_name"] : val["name"]);
                var identification_no = (val["identification_no"]!=null ? val["identification_no"] : val["register_no"]);
                option.attr('value', auditor_name).text(auditor_name);
                option.attr('data-client_officer_id', val["id"]);
                option.attr('data-officer_id', val["officer_id"]);
                option.attr('data-officer_field_type', val["field_type"]);
                option.attr('data-identification_register_no',identification_no);

                $("#reappointment_auditor_name").append(option);
            });
        }
    }
}

function reappointmentAuditor(cbox) {
	if (cbox.checked) 
	{
		$("#loadingmessage").show();
		$.post("transaction/get_resign_auditor_info", {id: transaction_id, company_code: transaction_company_code, transaction_task_id: transaction_task_id, registration_no: $(".uen_section #uen").val()}, function(data){
	        $("#loadingmessage").hide();
	        $(".row_reappointment_auditor").remove();
	        create_reappoint_auditor_interface(data);
	    });
		$(".reappointment_auditor_div").show();
	}
	else
	{
		$(".reappointment_auditor_div").hide();
		$(".row_reappointment_auditor").remove();
	}
}

$(document).on('click',"#submitCompanyInfoAndStatus",function(e){
	$('#loadingmessage').show();
	$(".xbrl_list").prop("disabled", false);
	$.ajax({ 
		url: "transaction/save_company_info_and_status",
		type: "POST",
		data: $('form#company_info_and_status_form').serialize()+ '&transaction_code=' + $('#transaction_code').val() + '&transaction_task_id=' + $('#transaction_task').val() + '&registration_no=' + $('#uen').val() + '&company_code=' + $('.company_code').val() + '&transaction_agm_ar_id=' + $('.transaction_agm_ar_id').val() + '&transaction_master_id=' + $("#transaction_trans #transaction_master_id").val(),
		dataType: 'json',
		success: function (response,data) {
			$('#loadingmessage').hide();
			if($("#company_type").val() == "2")
            {
				$(".xbrl_list").prop("disabled", true);
			}
			if (response.Status === 1) 
			{
				toastr.success(response.message, response.title);
				$(".transaction_agm_ar_id").val(response.transaction_agm_ar_id);
				$("#transaction_trans #transaction_master_id").val(response.transaction_master_id);
            	$("#transaction_trans #transaction_code").val(response.transaction_code);
			}
		}
    });
});

$(document).on('click',"#submitNoticeInfo",function(e){
	$('#loadingmessage').show();
	$(".fye_date").prop("disabled", false);
	if($('input[name="address_type"]:checked').val() == "Registered Office Address")
    {
        $("#tr_registered_edit input").removeAttr('disabled');
    }
    else
    {
        $("#tr_registered_edit input").attr('disabled', 'true');
    }
	$.ajax({ 
		url: "transaction/save_notice",
		type: "POST",
		data: $('form#notice_form').serialize()+ '&transaction_code=' + $('#transaction_code').val() + '&transaction_task_id=' + $('#transaction_task').val() + '&registration_no=' + $('#uen').val() + '&company_code=' + $('.company_code').val() + '&transaction_agm_ar_id=' + $('.transaction_agm_ar_id').val() + '&transaction_master_id=' + $("#transaction_trans #transaction_master_id").val(),
		dataType: 'json',
		success: function (response,data) {
			$('#loadingmessage').hide();
			if (response.Status === 1) 
			{
				toastr.success(response.message, response.title);
				$(".fye_date").prop("disabled", true);
				if($('input[name="address_type"]:checked').val() == "Registered Office Address")
	            {
	                $("#tr_registered_edit input").attr('disabled', 'true');
	            }
				$(".transaction_agm_ar_id").val(response.transaction_agm_ar_id);
				$("#transaction_trans #transaction_master_id").val(response.transaction_master_id);
            	$("#transaction_trans #transaction_code").val(response.transaction_code);
			}
		}
    });
});

$(document).on('click',"#submitAgendaInfo",function(e){
	$('#loadingmessage').show();
	var check_is_disabled_audited_fs = $('#audited_fs').prop('disabled');
    if(check_is_disabled_audited_fs)
    {
    	$('#audited_fs').prop('disabled', false);
    }
    $.ajax({ 
		url: "transaction/save_agenda",
		type: "POST",
		data: $('form#agenda_form').serialize()+ '&transaction_code=' + $('#transaction_code').val() + '&transaction_task_id=' + $('#transaction_task').val() + '&registration_no=' + $('#uen').val() + '&company_code=' + $('.company_code').val() + '&transaction_agm_ar_id=' + $('.transaction_agm_ar_id').val() + '&transaction_master_id=' + $("#transaction_trans #transaction_master_id").val(),
		dataType: 'json',
		success: function (response,data) {
			$('#loadingmessage').hide();
			if (response.Status === 1) 
			{
				toastr.success(response.message, response.title);
				if(check_is_disabled_audited_fs)
			    {
			    	$('#audited_fs').prop('disabled', true);
			    }
			    $(".transaction_agm_ar_id").val(response.transaction_agm_ar_id);
				$("#transaction_trans #transaction_master_id").val(response.transaction_master_id);
            	$("#transaction_trans #transaction_code").val(response.transaction_code);
			}
		}
	});
});

$(document).on('click',"#submitArDeclarationInfo",function(e){
	$('#loadingmessage').show();
	$.ajax({ 
		url: "transaction/save_ar_declaration",
		type: "POST",
		data: $('form#ar_declaration_form').serialize()+ '&transaction_code=' + $('#transaction_code').val() + '&transaction_task_id=' + $('#transaction_task').val() + '&registration_no=' + $('#uen').val() + '&company_code=' + $('.company_code').val() + '&transaction_agm_ar_id=' + $('.transaction_agm_ar_id').val() + '&transaction_master_id=' + $("#transaction_trans #transaction_master_id").val(),
		dataType: 'json',
		success: function (response,data) {
			$('#loadingmessage').hide();
			if (response.Status === 1) 
			{
				toastr.success(response.message, response.title);
			    $(".transaction_agm_ar_id").val(response.transaction_agm_ar_id);
				$("#transaction_trans #transaction_master_id").val(response.transaction_master_id);
            	$("#transaction_trans #transaction_code").val(response.transaction_code);
			}
		}
	});
});

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
    if($('input[name="address_type"]:checked').val() == "Registered Office Address")
    {
        $("#tr_registered_edit input").removeAttr('disabled');
    }
    else
    {
        $("#tr_registered_edit input").attr('disabled', 'true');
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
		    if($('input[name="address_type"]:checked').val() == "Registered Office Address")
            {
                $("#tr_registered_edit input").attr('disabled', 'true');
            }
			if (response.Status === 1) 
			{
				toastr.success(response.message, response.title);
				$(".transaction_agm_ar_id").val(response.transaction_agm_ar_id);
			}
        }
    })
});

