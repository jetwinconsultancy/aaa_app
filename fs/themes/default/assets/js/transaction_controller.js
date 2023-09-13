$count_controller = 0;
$(document).on('click',"#controller_Add",function() {

	$count_controller++;
 	$a=""; 
	// $a += '<form class="tr editing controller_sort_id" method="post" name="form'+$count_controller+'" id="form'+$count_controller+'">';
	// $a += '<div class="hidden"><input type="text" class="form-control" name="company_code" value="'+transaction_company_code+'"/></div>';
	// $a += '<div class="hidden"><input type="text" class="form-control" name="client_controller_id[]" id="client_controller_id" value=""/></div>';
	// $a += '<div class="hidden"><input type="text" class="form-control" name="officer_id" id="officer_id" value=""/></div>';
	// $a += '<div class="hidden"><input type="text" class="form-control" name="officer_field_type" id="officer_field_type" value=""/></div>';
	// $a += '<div class="td"><div class="input-group mb-md" style="width: 140px;"><input type="text" style="text-transform:uppercase;" name="identification_register_no[]" class="form-control" value="" id="gid_add_controller_officer"/><div id="form_identification_register_no"></div><div style=""><a class="" href="'+ url + 'personprofile/add/1" style="cursor:pointer;" id="add_office_person_link" target="_blank" hidden onclick="add_controller_person(this)"><div style="cursor:pointer;height:62px;">Click here to <span style="font-weight:bold">Add Person</span></div></a></div></div><div class="input-group mb-md" style="width: 140px;"><input type="text" style="text-transform:uppercase;" name="name[]" id="name" class="form-control" value="" readonly/><div id="form_name"></div></div></div>';
	// $a += '<div class="td"><div class="mb-md" style="width: 140px;"><input type="text" name="date_of_birth[]" id="date_of_birth" class="form-control" value="" readonly/></div><div class="mb-md" style="width: 140px;"><input type="text" style="text-transform:uppercase;" name="nationality[]" id="nationality" class="form-control nationality" value="" readonly/></div></div>';
	// $a += '<div class="td"><div class="input-group" style="width: 170px;"><textarea class="form-control" name="address[]" id="controller_address" style="width:100%;height:70px;text-transform:uppercase;" readonly></textarea></div><div id="form_controller_address"></div></div>';
 //    $a += '<div class="td action"><div style="display: inline-block; margin-right: 5px; margin-bottom: 5px;"><button type="button" class="btn btn-primary submit_controller" onclick="edit_controller(this);">Save</button></div><div style="display: inline-block;"><button type="button" class="btn btn-primary" onclick="delete_controller(this);">Delete</button></div></div>';
	// $a += '</form>';

	$a += '<tr class="row_controller">';
	$a += '<td><div class="mb-md"><input type="text" style="text-transform:uppercase;" name="identification_register_no[]" class="form-control" value="" id="gid_add_controller_officer"/><div id="form_identification_register_no"></div><div style=""><a class="" href="'+ url + 'personprofile/add/1" style="cursor:pointer;" id="add_office_person_link" target="_blank" hidden onclick="add_controller_person(this)"><div style="cursor:pointer;">Click here to <span style="font-weight:bold">Add Person</span></div></a></div></div><div class="mb-md"><input type="text" style="text-transform:uppercase;" name="name[]" id="name" class="form-control" value="" readonly/><div id="form_name"></div></div></td>';
	$a += '<td><div class="mb-md"><input type="text" name="date_of_birth[]" id="date_of_birth" class="form-control" value="" readonly/></div><div class="mb-md"><input type="text" style="text-transform:uppercase;" name="nationality[]" id="nationality" class="form-control nationality" value="" readonly/></div></td>';
	$a += '<td><textarea class="form-control" name="address[]" id="controller_address" style="width:100%;height:70px;text-transform:uppercase;" readonly></textarea><div id="form_controller_address"></div><div class="hidden"><input type="text" class="form-control" name="client_controller_id[]" id="client_controller_id" value=""/></div><div class="hidden"><input type="text" class="form-control" name="officer_id[]" id="officer_id" value=""/></div><div class="hidden"><input type="text" class="form-control" name="officer_field_type[]" id="officer_field_type" value=""/></td>';
	$a += '<td class="action"><div style="display: inline-block;"><button type="button" class="btn btn-primary" onclick="delete_controller(this);">Delete</button></div></td>';
	$a += '</tr>';
	
	$("#body_controller").prepend($a); 
});

//Search Officer
$("#gid_add_controller_officer").live('change',function(){
	var officer_frm = $(this);

    $('#loadingmessage').show();
	$.ajax({
		type: "POST",
		url: "masterclient/get_guarantee_officer",
		data: {"identification_register_no":officer_frm.val()}, // <--- THIS IS THE CHANGE
		dataType: "json",
		success: function(response){
            $('#loadingmessage').hide();
			//console.log(response);
			if(response)
			{
				if(response['field_type'] == "company")
				{
					//console.log(data['field_type'] == "company");
					officer_frm.parent().parent().parent().find('input[name="name[]"]').val(response['company_name']);
					officer_frm.parent().parent().parent().find('input[name="officer_id[]"]').val(response['id']);
					officer_frm.parent().parent().parent().find('input[name="officer_field_type[]"]').val(response['field_type']);

                    if(response['date_of_incorporation'] != null)
                    {
                        var date_of_incorporation = (response['date_of_incorporation']).split('-');
                        response['date_of_incorporation'] = date_of_incorporation[2] + '/' + date_of_incorporation[1] + '/' + date_of_incorporation[0];
                    }
                    else
                    {
                        response['date_of_incorporation'] = "";
                    }

					officer_frm.parent().parent().parent().find('input[name="date_of_birth[]"]').val(response['date_of_incorporation']);
					officer_frm.parent().parent().parent().find('.nationality').val(response['country_of_incorporation']);

					if(response["company_postal_code"] != "" && response["company_street_name"] != "")
					{
						if(response["company_unit_no1"] != "" || response["company_unit_no2"] != "")
						{
							var unit = ' #'+response["company_unit_no1"] +' - '+response["company_unit_no2"];
						}
						else
						{
							var unit = "";
						}
						var address = response["company_street_name"]+ unit +' '+response["company_building_name"]+' Singapore '+response["company_postal_code"];
						officer_frm.parent().parent().parent().find("DIV#form_controller_address").html( "" );
					}
					else if(response["company_foreign_address1"] != null )
					{
						var address = response["company_foreign_address1"];
						officer_frm.parent().parent().parent().find("DIV#form_controller_address").html( "" );
					}
					officer_frm.parent().parent().parent().find('#controller_address').val(address);

					if(response['company_name'] != undefined)
					{
						officer_frm.parent().parent().parent().find("DIV#form_name").html( "" );
					}
				}
				else if(response['field_type'] == "individual")
				{
					officer_frm.parent().parent().parent().find('input[name="name[]"]').val(response['name']);
					officer_frm.parent().parent().parent().find('input[name="officer_id[]"]').val(response['id']);
					officer_frm.parent().parent().parent().find('input[name="officer_field_type[]"]').val(response['field_type']);

            		var date_birth = (response['date_of_birth']).split('-');
            		response['date_of_birth'] = date_birth[2] + '/' + date_birth[1] + '/' + date_birth[0];

					officer_frm.parent().parent().parent().find('input[name="date_of_birth[]"]').val(response['date_of_birth']);
					officer_frm.parent().parent().parent().find('.nationality').val(response['nationality_name']);

					if(response["postal_code1"] != "" && response["street_name1"] != "")
					{
						if(response["unit_no1"] != "" || response["unit_no2"] != "")
						{
							var unit = ' #'+response["unit_no1"] +' - '+response["unit_no2"];
						}
						else
						{
							var unit = "";
						}
						var address = response["street_name1"]+ unit +' '+response["building_name1"]+' Singapore '+response["postal_code1"];
						officer_frm.parent().parent().parent().find("DIV#form_controller_address").html( "" );
					}
					else if(response["foreign_address1"] != null )
					{
						var address = response["foreign_address1"];
						officer_frm.parent().parent().parent().find("DIV#form_controller_address").html( "" );
					}
					officer_frm.parent().parent().parent().find('#controller_address').val(address);

					if(response['name'] != undefined)
					{
						//officer_frm.parent().parent().find('input[name="name[]"]').attr('readOnly', true);
						officer_frm.parent().parent().parent().find("DIV#form_name").html( "" );
					}
					
				}
				else
				{
					officer_frm.parent().parent().parent().find('input[name="name[]"]').val(response['company_name']);
					officer_frm.parent().parent().parent().find('input[name="officer_id[]"]').val(response['id']);
					officer_frm.parent().parent().parent().find('input[name="officer_field_type[]"]').val("client");

					// if(response['incorporation_date'] != null)
     //                {
     //                    var incorporation_date = (response['incorporation_date']).split('-');
     //                    response['incorporation_date'] = incorporation_date[2] + '/' + incorporation_date[1] + '/' + incorporation_date[0];
     //                }
     //                else
     //                {
     //                    response['incorporation_date'] = "";
     //                }

					officer_frm.parent().parent().parent().find('input[name="date_of_birth[]"]').val(response['incorporation_date']);
					officer_frm.parent().parent().parent().find('.nationality').val("");

					if(response["postal_code"] != "" && response["street_name"] != "")
					{
						if(response["unit_no1"] != "" || response["unit_no2"] != "")
						{
							var unit = ' #'+response["unit_no1"] +' - '+response["unit_no2"];
						}
						else
						{
							var unit = "";
						}
						var address = response["street_name"]+ unit +' '+response["building_name"]+' Singapore '+response["postal_code"];
						officer_frm.parent().parent().parent().find("DIV#form_controller_address").html( "" );
					}
					officer_frm.parent().parent().parent().find('#controller_address').val(address);

					if(response['company_name'] != undefined)
					{
						//officer_frm.parent().parent().find('input[name="name[]"]').attr('readOnly', true);
						officer_frm.parent().parent().parent().find("DIV#form_name").html( "" );
					}
				}
				officer_frm.parent().parent().parent().find('a#add_office_person_link').attr('hidden',"true");
			}
			else
			{
				officer_frm.parent().parent().parent().find('input[name="name[]"]').val("");
				officer_frm.parent().parent().parent().find('input[name="officer_id"]').val("");
				officer_frm.parent().parent().parent().find('input[name="officer_field_type"]').val("");
				officer_frm.parent().parent().parent().find('a#add_office_person_link').removeAttr('hidden');

                officer_frm.parent().parent().parent().find('input[name="date_of_birth[]"]').val("");
                officer_frm.parent().parent().parent().find('.nationality').val("");
                officer_frm.parent().parent().parent().find('#controller_address').val("");
			}
		}				
	});
});

$(document).on('click',"#submitControllerInfo",function(e){
  console.log("in");
    $('#loadingmessage').show();
    $.ajax({ //Upload common input
      url: "transaction/add_controller",
      type: "POST",
      data: $('form#controller_form').serialize()+ '&transaction_code=' + $('#transaction_code').val() + '&transaction_task_id=' + $('#transaction_task').val(),
      dataType: 'json',
      success: function (response,data) {
        $('#loadingmessage').hide();
          if (response.Status === 1) 
          {
            toastr.success(response.message, response.title);
            $("#body_controller .row_controller").remove();
            controllerInterface(response.transaction_client_controller);
          }
        }
    })

});

function add_controller_person(elem)
{
    jQuery(elem).parent().parent().find('input[name="identification_register_no[]"]').val("");
    jQuery(elem).attr('hidden',"true");
}

// function edit_controller(element)
// {
// 	var tr = jQuery(element).parent().parent().parent();
// 	if(!tr.hasClass("editing")) 
// 	{
// 		tr.addClass("editing");
// 		tr.find("DIV.td").each(function()
// 		{
// 			if(!jQuery(this).hasClass("action"))
// 			{
// 				jQuery(this).find('input[name="identification_register_no[]"]').attr('disabled', false);
// 			} 
// 			else 
// 			{
// 				jQuery(this).find(".submit_controller").text("Save");
// 			}
// 		});
// 	} 
// 	else 
// 	{
// 		tr.find('#controller_address').removeAttr("disabled");
// 		var frm = $(element).closest('form');

// 		var frm_serialized = frm.serialize();

//         $('#loadingmessage').show();
//         $.ajax({ //Upload common input
//             url: "transaction/add_controller",
//             type: "POST",
//             data: frm_serialized,
//             dataType: 'json',
//             success: function (response) {
//                 $('#loadingmessage').hide();
//                 if (response.Status === 1) {
//                     toastr.success(response.message, response.title);
//                     if(response.insert_client_controller_id != null)
//                     {
//                         tr.find('input[name="client_controller_id[]"]').attr('value', response.insert_client_controller_id);
//                     }
//                     tr.removeClass("editing");

                    
//                     tr.attr("data-registe_no",tr.find('input[name="identification_register_no[]"]').val());
//                     tr.attr("data-name",tr.find('input[name="position[]"]').val());
//                     tr.attr("data-date_of_birth",tr.find('input[name="date_of_birth[]"]').text());
//                     tr.attr("data-nationality",tr.find('.nationality option:selected').text());
//                     tr.attr("data-address",tr.find('textarea[name="address[]"]').val());
//                     tr.attr("data-date_of_registration",tr.find('input[name="date_of_registration[]"]').val());
//                     tr.attr("data-date_of_cessation",tr.find('input[name="date_of_cessation[]"]').val());

//                     tr.find("DIV.td").each(function(){
//                         if(!jQuery(this).hasClass("action")){

//                             jQuery(this).find('input[name="identification_register_no[]"]').attr('disabled', true);
//                             //jQuery(this).find('input[name="date_of_birth[]"]').attr('disabled', true);
                            
//                             jQuery(this).find("textarea").attr('disabled', true);
//                             //jQuery(this).find("select").attr('disabled', true);

                            

                            
//                         } else {
//                             jQuery(this).find(".submit_controller").text("Edit");
//                         }
//                     });
//                     tr.find("DIV#form_date_of_cessation").html("");
                    
//                 }
//                 else if (response.Status === 2)
//                 {
//                     //console.log(response.data);
//                     toastr.error(response.message, response.title);
//                 }
//                 else
//                 {

//                     toastr.error(response.message, response.title);

//                     if (response.error["identification_register_no"] != "")
//                     {
//                         var errorsIdentificationRegisterNo = '<span class="help-block">*' + response.error["identification_register_no"] + '</span>';
//                         tr.find("DIV#form_identification_register_no").html( errorsIdentificationRegisterNo );

//                     }
//                     else
//                     {
//                         var errorsIdentificationRegisterNo = ' ';
//                         tr.find("DIV#form_identification_register_no").html( errorsIdentificationRegisterNo );
//                     }

//                     if (response.error["name"] != "")
//                     {
//                         var errorsName = '<span class="help-block">*' + response.error["name"] + '</span>';
//                         tr.find("DIV#form_name").html( errorsName );

//                     }
//                     else
//                     {
//                         var errorsName = ' ';
//                         tr.find("DIV#form_name").html( errorsName );
//                     }

//                     if (response.error["address"] != "")
//                     {
//                         var errorsAddress = '<span class="help-block">*' + response.error["address"] + '</span>';
//                         tr.find("DIV#form_controller_address").html( errorsAddress );

//                     }
//                     else
//                     {
//                         var errorsAddress = ' ';
//                         tr.find("DIV#form_controller_address").html( errorsAddress );
//                     }

//                     if (response.error["date_of_registration"] != "")
//                     {
//                         var errorsDateOfRegistration = '<span class="help-block" style="margin-top: -15px !important;">*' + response.error["date_of_registration"] + '</span>';
//                         tr.find("DIV#form_date_of_registration").html( errorsDateOfRegistration );

//                     }
//                     else
//                     {
//                         var errorsDateOfRegistration = ' ';
//                         tr.find("DIV#form_date_of_registration").html( errorsDateOfRegistration );
//                     }

//                     if (response.error["date_of_notice"] != "")
//                     {
//                         var errorsDateOfNotice = '<span class="help-block" style="margin-top: -15px !important;">*' + response.error["date_of_notice"] + '</span>';
//                         tr.find("DIV#form_date_of_notice").html( errorsDateOfNotice );

//                     }
//                     else
//                     {
//                         var errorsDateOfNotice = ' ';
//                         tr.find("DIV#form_date_of_notice").html( errorsDateOfNotice );
//                     }

//                     if (response.error["confirmation_received_date"] != "")
//                     {
//                         var errorsConfirmationReceivedDate = '<span class="help-block" style="margin-top: -15px !important;">*' + response.error["confirmation_received_date"] + '</span>';
//                         tr.find("DIV#form_confirmation_received_date").html( errorsConfirmationReceivedDate );

//                     }
//                     else
//                     {
//                         var errorsConfirmationReceivedDate = ' ';
//                         tr.find("DIV#form_confirmation_received_date").html( errorsConfirmationReceivedDate );
//                     }

//                     if (response.error["date_of_entry"] != "")
//                     {
//                         var errorsDateOfEntry = '<span class="help-block" style="margin-top: -15px !important;">*' + response.error["date_of_entry"] + '</span>';
//                         tr.find("DIV#form_date_of_entry").html( errorsDateOfEntry );

//                     }
//                     else
//                     {
//                         var errorsDateOfEntry = ' ';
//                         tr.find("DIV#form_date_of_entry").html( errorsDateOfEntry );
//                     }

//                     if (response.error["date_of_cessation"] != "")
//                     {
//                         var errorsDateOfCessation = '<span class="help-block">*' + response.error["date_of_cessation"] + '</span>';
//                         tr.find("DIV#form_date_of_cessation").html( errorsDateOfCessation );
//                         tr.find("#date_of_cessation").val("");
//                     }
//                     else
//                     {
//                         var errorsDateOfCessation = ' ';
//                         tr.find("DIV#form_date_of_cessation").html( errorsDateOfCessation );
//                     }
                    
//                 }
//             }
//         });
// 	}
// }

function delete_controller(element)
{
    var tr = jQuery(element).parent().parent().parent();

    var client_controller_id = tr.find('input[name="client_controller_id[]"]').val();
    //console.log("client_officer_id==="+client_officer_id);
    
    if(client_controller_id != undefined)
    {
    	$('#loadingmessage').show();
        $.ajax({ //Upload common input
            url: "transaction/delete_controller",
            type: "POST",
            data: {"client_controller_id": client_controller_id},
            dataType: 'json',
            success: function (response) {
                $('#loadingmessage').hide();
            }
        });
    }
    tr.remove();
    toastr.success("Updated Information.", "Updated");

}