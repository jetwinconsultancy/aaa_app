// $count_officer = 0;
// $(document).on('click',"#resign_director_Add",function() {

// 	$count_officer++;
//  	$a = ""; 
// 	$a += '<tr class="row_resign_director">';
// 	$a += '<td><select class="form-control position" style="text-align:right;" name="position[]" id="position" onchange="optionCheck(this);" disabled="disabled"><option value="" >Select Position</option></select><div id="form_position"></div><div id="alternate_of" hidden><p style="font-weight:bold;">Alternate of: </p><select class="form-control select_alternate_of" style="text-align:right;width: 150px;" name="alternate_of[]"><option value="" >Select Director</option></select><div id="form_alternate_of"></div></div></td>';
// 	$a += '<td><input type="text" style="text-transform:uppercase;" name="identification_register_no[]" class="form-control identification_register_no" value="" id="gid_add_officer" maxlength="15" readonly/><div id="form_identification_register_no"></div><div style="height:16px;" ><a class="" href="'+ url + 'personprofile/add/1" style="cursor:pointer;" id="add_office_person_link" target="_blank" hidden onclick="add_appoint_new_director_person(this)"><div style="cursor:pointer;" id="click_add_person">Click here to <span style="font-weight:bold">Add Person</span></div></a></div></td>';
// 	$a += '<td><input type="text" style="text-transform:uppercase;" name="name[]" id="name" class="form-control" value="" readonly/><div id="form_name"></div><div class="hidden"><input type="text" class="form-control" name="client_officer_id[]" id="client_officer_id" value=""/></div><div class="hidden"><input type="text" class="form-control" name="officer_id[]" id="officer_id" value=""/></div><div class="hidden"><input type="text" class="form-control" name="officer_field_type[]" id="officer_field_type" value=""/></div></td>';
// 	$a += '<td><div class="input-group" style="width: 150px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="officer_date_of_appointment" name="date_of_appointment[]" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="DD/MM/YYYY" disabled="disabled"></div></td>';
// 	$a += '<td><div class="input-group" style="width: 150px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="officer_date_of_cessation" name="date_of_cessation[]" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="DD/MM/YYYY" disabled="disabled"></div></td>';
// 	$a += '<td class="action"><div style="display: inline-block;"><button type="button" class="btn btn-primary" onclick="delete_appoint_new_director(this);">Delete</button></div></td>';
// 	$a += '</tr>';
	
// 	$("#body_resign_director").prepend($a); 

// 	$.ajax({
// 		type: "GET",
// 		url: "transaction/get_client_officers_position",
// 		dataType: "json",
// 		async: false,
// 		success: function(data){
//             if(data.tp == 1){

//                 $.each(data['result'], function(key, val) {
//                     var option = $('<option />');
//                     option.attr('value', key).text(val);
//                     if(key == 1)
//                     {
//                         option.attr('selected', 'selected');
//                     }
//                     $("#position").append(option);
//                 });

//             }
//             else{
//                 alert(data.msg);
//             }
// 		}				
// 	});
// });
//var withdraw = true;
function withdraw_director(element)
{
	var tr = jQuery(element).parent().parent().parent();

	var client_officer_id = tr.find('input[name="client_officer_id[]"]').val();
	//console.log("client_officer_id==="+client_officer_id);
	
	if(jQuery(element).html() == 'Withdraw')
	{
		tr.find(".officer_date_of_cessation").prop("disabled", false);
		tr.find("#resign_director_reason").prop("disabled", false);
		tr.find("#is_director_withdraw").val("1");

		jQuery(element).html('Cancel');
		jQuery(element).toggleClass('cancel_withdraw_director');
		jQuery(element).removeClass('withdraw_director');
		//withdraw = false;
		//console.log($("#body_resign_director .withdraw_director").length);



	}
	else
	{
		tr.find(".officer_date_of_cessation").prop("disabled", true);
		tr.find("#resign_director_reason").prop("disabled", true);

		tr.find(".officer_date_of_cessation").val("");
		tr.find("#resign_director_reason").val("");

		tr.find("#hidden_date_of_cessation").val("");
		tr.find("#hidden_resign_director_reason").val("");

		tr.find("#is_director_withdraw").val("0");

		jQuery(element).html('Withdraw');
		jQuery(element).toggleClass('withdraw_director');
		jQuery(element).removeClass('cancel_withdraw_director');

		//withdraw = true;
	}
	$("#body_appoint_new_director .row_appoint_new_director").remove();
	if($("#body_resign_director .withdraw_director").length >= 1)
	{
		$('#appoint_new_director').hide();
	}
	else
	{
		$('#appoint_new_director').show();
	}
}

$(document).on('click',"#submitResignDirectorInfo",function(e){
    $('#loadingmessage').show();
    // $('.row_resign_director .position').prop("disabled", false);
    // $('.row_resign_director .officer_date_of_cessation').prop("disabled", false);
    $.ajax({ //Upload common input
      url: "transaction/add_resign_director",
      type: "POST",
      data: $('form#resign_of_director_form').serialize()+ '&transaction_code=' + $('#transaction_code').val() + '&transaction_task_id=' + $('#transaction_task').val() + '&registration_no=' + $('#uen').val(),
      dataType: 'json',
      success: function (response,data) {
        $('#loadingmessage').hide();

          if (response.Status === 1) 
          {
            toastr.success(response.message, response.title);
           	$("#body_resign_director .row_resign_director").remove();
           	$("#body_appoint_new_director .row_appoint_new_director").remove();
            $("#transaction_trans #transaction_master_id").val(response.transaction_master_id);
            $("#transaction_trans #transaction_code").val(response.transaction_code);
            resignDirectorInterface(response.transaction_client_officers);
          }
        }
    });
});



