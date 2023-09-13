$count_officer = 0;
$(document).on('click',"#appoint_new_director_Add",function() {

	$count_officer++;
 	$a = ""; 
	$a += '<tr class="row_appoint_new_director">';
	$a += '<td><select class="form-control position" style="text-align:right;" name="position[]" id="position" onchange="optionCheck(this);" disabled="disabled"><option value="" >Select Position</option></select><div id="form_position"></div><div id="alternate_of" hidden><p style="font-weight:bold;">Alternate of: </p><select class="form-control select_alternate_of" style="text-align:right;width: 150px;" name="alternate_of[]"><option value="" >Select Director</option></select><div id="form_alternate_of"></div></div></td>';
	$a += '<td><input type="text" style="text-transform:uppercase;" name="identification_register_no[]" class="form-control identification_register_no" value="" id="gid_add_director_officer" maxlength="15"/><div id="form_identification_register_no"></div><div style="height:16px;" ><a class="" href="'+ url + 'personprofile/add/1" style="cursor:pointer;" id="add_office_person_link" target="_blank" hidden onclick="add_appoint_new_director_person(this)"><div style="cursor:pointer;" id="click_add_person">Click here to <span style="font-weight:bold">Add Person</span></div></a></div></td>';
	$a += '<td><input type="text" style="text-transform:uppercase;" name="name[]" id="name" class="form-control" value="" readonly/><div id="form_name"></div><div class="hidden"><input type="text" class="form-control" name="client_officer_id[]" id="client_officer_id" value=""/></div><div class="hidden"><input type="text" class="form-control" name="officer_id[]" id="officer_id" value=""/></div><div class="hidden"><input type="text" class="form-control" name="officer_field_type[]" id="officer_field_type" value=""/></div></td>';
	$a += '<td><div class="input-group" style="width: 150px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="officer_date_of_appointment" name="date_of_appointment[]" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="DD/MM/YYYY"></div></td>';
	// $a += '<td><div class="input-group" style="width: 150px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="officer_date_of_appointment" name="date_of_appointment[]" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="DD/MM/YYYY"></div></td>';
	$a += '<td class="action"><div style="display: inline-block;"><button type="button" class="btn btn-primary" onclick="delete_appoint_new_director(this);">Delete</button></div></td>';
	$a += '</tr>';
	
	$("#body_appoint_new_director").prepend($a); 

	// ADDED BY JW
    $('#officer_date_of_appointment').datepicker({ 
        dateFormat:'dd MM yyyy',
    });

	$.ajax({
		type: "GET",
		url: "transaction/get_client_officers_position",
		dataType: "json",
		async: false,
		success: function(data){
            if(data.tp == 1){

                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    if(key == 1)
                    {
                        option.attr('selected', 'selected');
                    }
                    $("#position").append(option);
                });

            }
            else{
                alert(data.msg);
            }
		}				
	});
	//check_incorp_date();
});

//Search Officer
$("#gid_add_director_officer").live('change',function(){
	var officer_frm = $(this);
	var officer_id;

	if(officer_frm.parent().parent('form').find('select[name="position[]"]').val() == "7")
	{
		officer_id = officer_frm.parent().parent('form').find('.select_alternate_of').val();
	}
	else
	{
		officer_id = null;
	}

	$("#loadingmessage").show();
	$.ajax({
		type: "POST",
		url: "transaction/get_officer",
		data: {"officer_id":officer_id, "identification_register_no":officer_frm.val(), "position": officer_frm.parent().parent().parent().parent().find('select[name="position[]"]').val(), "company_code": $("#company_code").val()}, // <--- THIS IS THE CHANGE
		dataType: "json",
		success: function(data){
			$("#loadingmessage").hide();
			if(data.status == 1)
			{
				var response = data.info;
				if(response['field_type'] == "company")
				{
					officer_frm.parent().parent().find('input[name="name[]"]').val(response['company_name']);
					officer_frm.parent().parent().find('input[name="officer_id"]').val(response['id']);
					officer_frm.parent().parent().find('input[name="officer_field_type"]').val(response['field_type']);
					if(response['company_name'] != undefined)
					{
						officer_frm.parent().parent().find("DIV#form_name").html( "" );
					}
				}
				else
				{
					officer_frm.parent().parent().find('input[name="name[]"]').val(response['name']);
					officer_frm.parent().parent().find('input[name="officer_id[]"]').val(response['id']);
					officer_frm.parent().parent().find('input[name="officer_field_type[]"]').val(response['field_type']);
					if(response['name'] != undefined)
					{
						officer_frm.parent().parent().find("DIV#form_name").html( "" );
					}
					
				}
				officer_frm.parent().parent().find('a#add_office_person_link').attr('hidden',"true");
			}
			else if(data.status == 2)
			{
				toastr.error(data.message, data.title);
				officer_frm.parent().parent().find('a#add_office_person_link').attr('hidden',"true");
			}
			else if(data.status == 3)
			{
				officer_frm.parent().parent().find('input[name="name[]"]').val("");
				officer_frm.parent().parent().find('input[name="officer_id"]').val("");
				officer_frm.parent().parent().find('input[name="officer_field_type"]').val("");
				officer_frm.parent().parent().find('a#add_office_person_link').removeAttr('hidden');
			}
			else if(data.status == 4)
			{
				toastr.error(data.message, data.title);
				officer_frm.parent().parent().find('a#add_office_person_link').attr('hidden',"true");
				officer_frm.parent().parent().find('input[name="name[]"]').val("");
			}
			else if(data.status == 5)
			{
				toastr.error(data.message, data.title);
				officer_frm.parent().parent().find('input[name="name[]"]').val("");
			}
			
		}				
	});
});

$(document).on('click',"#submitDirectorInfo",function(e){
    $('#loadingmessage').show();
    $('.row_appoint_new_director .position').prop("disabled", false);
    $.ajax({ 
      url: "transaction/add_appoint_new_director",
      type: "POST",
      data: $('form#apointment_of_director_form').serialize()+ '&transaction_code=' + $('#transaction_code').val() + '&transaction_task_id=' + $('#transaction_task').val() + '&registration_no=' + $('#uen').val() + '&transaction_master_id=' + $("#transaction_trans #transaction_master_id").val(),
      dataType: 'json',
      success: function (response,data) {
        $('#loadingmessage').hide();

          if (response.Status === 1) 
          {
            toastr.success(response.message, response.title);
            $("#body_appoint_new_director .row_appoint_new_director").remove();
            $("#transaction_trans #transaction_master_id").val(response.transaction_master_id);
            $("#transaction_trans #transaction_code").val(response.transaction_code);
            $('.row_appoint_new_director .position').prop("disabled", true);
            appointNewDirectorInterface(response.transaction_client_officers);
          }
        }
    });
});

function add_appoint_new_director_person(elem)
{
	jQuery(elem).parent().parent().find('input[name="identification_register_no[]"]').val("");
    jQuery(elem).attr('hidden',"true");
}

function delete_appoint_new_director(element)
{
	var tr = jQuery(element).parent().parent().parent();
	var client_officer_id = tr.find('input[name="client_officer_id[]"]').val();
	$('#loadingmessage').show();
	if(client_officer_id != undefined)
	{
		$.ajax({
            url: "transaction/delete_transaction_officer",
            type: "POST",
            data: {"client_officer_id": client_officer_id},
            dataType: 'json',
            success: function (response) {
            	$('#loadingmessage').hide();
            	if(response.Status == 1)
            	{
            		tr.remove();
            		toastr.success("Updated Information.", "Updated");

            	}
            	else if(response.Status == 2)
            	{
            		location.reload();
            		toastr.error("Cannot delete this director because he is alternated by an alternate director.", "Error");
            	}
            }
        });
	}
}

function check_incorp_date()
{
	$.ajax({
		type: "POST",
		url: "transaction/check_client_info",
		data: {"registration_no": $('.uen_section #uen').val()},
		dataType: "json",
		async: false,
		success: function(response)
		{
			$array = response[0]["incorporation_date"].split("/");
			$tmp = $array[0];
			$array[0] = $array[1];
			$array[1] = $tmp;
			$date_2 = $array[0]+"/"+$array[1]+"/"+$array[2];

			$latest_incorporation_date = new Date($date_2);
			$('.lodgement_date').datepicker({ 
			    dateFormat:'dd/mm/yyyy',
			}).datepicker('setStartDate', $latest_incorporation_date);
		}
	});
}
