$count_officer = 0;
$(document).on('click',"#officers_Add",function() {

	$count_officer++;
 	$a = ""; 
	$a += '<tr class="row_officer">';
	$a += '<td><select class="form-control position" style="text-align:right;" name="position[]" id="position"><option value="" >Select Position</option></select><div id="form_position"></div><div id="alternate_of" hidden><p style="font-weight:bold;">Alternate of: </p><select class="form-control select_alternate_of" style="text-align:right;width: 150px;" name="alternate_of[]"><option value="" >Select Director</option></select><div id="form_alternate_of"></div></div></td>';
	$a += '<td><input type="text" style="text-transform:uppercase;" name="identification_register_no[]" class="form-control identification_register_no" value="" id="gid_add_officer" maxlength="15"/><div id="form_identification_register_no"></div><div style="height:16px;" ><a class="" href="'+ url + 'personprofile/add/1" style="cursor:pointer;" id="add_office_person_link" target="_blank" hidden onclick="add_officers_person(this)"><div style="cursor:pointer;" id="click_add_person">Click here to <span style="font-weight:bold">Add Person</span></div></a></div></td>';
	$a += '<td><input type="text" style="text-transform:uppercase;" name="name[]" id="name" class="form-control" value="" readonly/><div id="form_name"></div><div class="hidden"><input type="text" class="form-control" name="client_officer_id[]" id="client_officer_id" value=""/></div><div class="hidden"><input type="text" class="form-control" name="officer_id[]" id="officer_id" value=""/></div><div class="hidden"><input type="text" class="form-control" name="officer_field_type[]" id="officer_field_type" value=""/></div></td>';
	$a += '<td class="action"><div style="display: inline-block;"><button type="button" class="btn btn-primary" onclick="delete_transaction_officer(this);">Delete</button></div></td>';
	$a += '</tr>';
	
	$("#body_officer").prepend($a); 

	$.ajax({
		type: "GET",
		url: "transaction/get_client_officers_position",
		dataType: "json",
		success: function(data){

            if(data.tp == 1){

                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    if(data.selected_client_officers_position != null && key == data.client_officers_position)
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
});

//Search Officer
$("#gid_add_officer").live('change',function(){
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
		data: {"officer_id":officer_id, "identification_register_no":officer_frm.val(), "position": officer_frm.parent().parent().find('select[name="position[]"]').val(), 'company_code': $(".transaction_company_code").val()}, // <--- THIS IS THE CHANGE
		dataType: "json",
		success: function(data){
			$("#loadingmessage").hide();
			if(data.status == 1)
			{
				var response = data.info;
				if(response['field_type'] == "company")
				{
					officer_frm.parent().parent().find('input[name="name[]"]').val(response['company_name']);
					officer_frm.parent().parent().find('input[name="officer_id[]"]').val(response['id']);
					officer_frm.parent().parent().find('input[name="officer_field_type[]"]').val(response['field_type']);
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

$(document).on('click',"#submitOfficerInfo",function(e){
    $('#loadingmessage').show();
    $.ajax({ //Upload common input
      url: "transaction/add_officer",
      type: "POST",
      data: $('form#officer_form').serialize()+ '&transaction_code=' + $('#transaction_code').val() + '&transaction_task_id=' + $('#transaction_task').val() + '&transaction_master_id=' + $("#transaction_trans #transaction_master_id").val(),
      dataType: 'json',
      success: function (response,data) {
        $('#loadingmessage').hide();

          if (response.Status === 1) 
          {
            toastr.success(response.message, response.title);
            $("#body_officer .row_officer").remove();
            officerInterface(response.transaction_client_officers);

            $('.director_signature_1').find("option:eq(0)").html("Select Director Signature 2");
			$(".director_signature_1 option:gt(0)").remove();
			
            if (!showDS1) 
            {
                cm1.getDirectorSignature1();
            }
            else
            {
                cm1.getTodayDirectorSignature1();
            }
            var director_signature_1_id = $(".director_signature_1").val();
            $('.director_signature_2').find("option:eq(0)").html("Select Director Signature 2");
			$(".director_signature_2 option:gt(0)").remove();
            if(director_signature_1_id != '' && director_signature_1_id != 0){
                cm1.getDirectorSignature2(director_signature_1_id);
                showDS2 = false;
                $(".btnShowAllDirectorSig2").prop('value', 'Show Today');
                $(".director_signature_2").removeAttr("disabled");

            }
            else{
                $(".director_signature_2").attr("disabled", "disabled");
                $(".director_signature_2 option:gt(0)").remove();
                //$('#setup_form').formValidation('revalidateField', 'director_signature_2');

            }
          }
        }
    })

});

function add_officers_person(elem)
{
    jQuery(elem).parent().parent().find('input[name="identification_register_no[]"]').val("");
    jQuery(elem).attr('hidden',"true");
}

function delete_transaction_officer(element)
{
	var tr = jQuery(element).parent().parent().parent();
	//console.log(tr);


	var client_officer_id = tr.find('input[name="client_officer_id[]"]').val();
	//console.log("client_officer_id==="+client_officer_id);
	$('#loadingmessage').show();
	if(client_officer_id != undefined)
	{
		$.ajax({ //Upload common input
            url: "transaction/delete_transaction_officer",
            type: "POST",
            data: {"client_officer_id": client_officer_id},
            dataType: 'json',
            success: function (response) {
            	//console.log(response.Status);
            	$('#loadingmessage').hide();
            	if(response.Status == 1)
            	{
            		tr.remove();
            		toastr.success("Updated Information.", "Updated");
            		$(".director_signature_1 option").remove();
            		if (!showDS1) 
		            {
		                cm1.getDirectorSignature1();
		            }
		            else
		            {
		                cm1.getTodayDirectorSignature1();
		            }

		            var director_signature_1_id = $(".director_signature_1").val();
		            $(".director_signature_2 option").remove();
		            if(director_signature_1_id != '' && director_signature_1_id != 0){
		                cm1.getDirectorSignature2(director_signature_1_id);
		                showDS2 = false;
		                $(".btnShowAllDirectorSig2").prop('value', 'Show Today');
		                $(".director_signature_2").removeAttr("disabled");

		            }
		            else{
		                $(".director_signature_2").attr("disabled", "disabled");
		                $(".director_signature_2 option:gt(0)").remove();
		                //$('#setup_form').formValidation('revalidateField', 'director_signature_2');

		            }

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

// function optionCheck(officer_element) {

// 	var tr = jQuery(officer_element).parent().parent();

// 	if(tr.find('select[name="position[]"]').val() == "7")
// 	{
// 		//console.log("ininini");
// 		tr.find("DIV#alternate_of").removeAttr('hidden');
// 		tr.find('select[name="alternate_of[]"]').removeAttr('disabled');
// 		$("#loadingmessage").show();
// 		$.ajax({
// 			type: "POST",
// 			url: "transaction/get_director",
// 			data: {"company_code": tr.find('input[name="company_code"]').val()}, // <--- THIS IS THE CHANGE
// 			dataType: "json",
// 			success: function(data){
// 				$("#loadingmessage").hide();
// 	            if(data.tp == 1){
// 	            	tr.find('select[name="alternate_of[]"]').html(""); 
// 	            	tr.find('select[name="alternate_of[]"]').append($('<option>', {
// 					    value: '0',
// 					    text: 'Select Director'
// 					}));
	            	
// 	            	//option.attr('value', '').text("Select Director");
// 	            	//tr.find('select[name="alternate_of[]"]').html("Select Director");
// 	            	//option.attr('value', '0').text("Select Director");
// 	                $.each(data['result'], function(key, val) {
// 	                    var option = $('<option />');
// 	                    option.attr('value', key).text(val);
// 	                    if(data.selected_director != null && key == data.selected_director)
// 	                    {
// 	                        option.attr('selected', 'selected');
// 	                    }
	                    
// 	                    tr.find('select[name="alternate_of[]"]').append(option);
// 	                });
	                
// 	                //$(".nationality").prop("disabled",false);
// 	            }
// 	            else{
// 	                alert(data.msg);
// 	            }

				
// 			}				
// 		});
// 	}
// 	else
// 	{
// 		tr.find("DIV#alternate_of").attr("hidden","true");
// 		tr.find('select[name="alternate_of[]"]').attr('disabled', 'disabled');
// 	}

// }

// function edit(element)
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
// 				jQuery(this).find('input[name="date_of_appointment[]"]').attr('disabled', false);
// 				jQuery(this).find('input[name="date_of_cessation[]"]').attr('disabled', false);
// 				jQuery(this).find("select").attr('disabled', false);
				
// 			} 
// 			else 
// 			{
// 				jQuery(this).find(".submit_officer_button").text("Save");
// 			}
// 		});
// 	} 
// 	else 
// 	{
// 		var frm = $(element).closest('form');

// 		var frm_serialized = frm.serialize();

// 		$('#loadingmessage').show();
// 		$.ajax({ //Upload common input
// 	        url: "transaction/add_officer",
// 	        type: "POST",
// 	        data: frm_serialized,
// 	        dataType: 'json',
// 	        success: function (response) {
// 	        	$('#loadingmessage').hide();
// 	        	//console.log(response.Status);
// 	            if (response.Status === 1) {
// 	            	//var errorsDateOfCessation = ' ';
// 	            	toastr.success(response.message, response.title);
// 	            	tr.find("DIV#form_date_of_cessation").html(" ");
// 	            	if(response.insert_client_officers_id != null)
// 	            	{
// 	            		tr.find('input[name="client_officer_id[]"]').attr('value', response.insert_client_officers_id);
// 	            	}
// 	            	tr.removeClass("editing");

// 	            	tr.attr("data-position",tr.find('#position option:selected').text());
// 	            	tr.attr("data-registe_no",tr.find('input[name="identification_register_no[]"]').val());
// 	            	tr.attr("data-name",tr.find('input[name="name[]"]').val());
// 	            	tr.attr("data-dateofappointment",tr.find('input[name="date_of_appointment[]"]').val());
// 	            	tr.attr("data-dateofcessation",tr.find('input[name="date_of_cessation[]"]').val());
	            	
// 	            	/*tr.data('registe_no',tr.find('input[name="identification_register_no[]"]').val());
// 	            	tr.data('name',tr.find('input[name="identification_register_no[]"]').val());*/
// 					tr.find("DIV.td").each(function(){
// 						if(!jQuery(this).hasClass("action")){
							
// 							//jQuery(this).find("input[name='name[]']").attr('readonly', true);
// 							jQuery(this).find("input[name='identification_register_no[]']").attr('disabled', true);
// 							jQuery(this).find("input[name='date_of_appointment[]']").attr('disabled', true);
// 							jQuery(this).find("input[name='date_of_cessation[]']").attr('disabled', true);
// 							jQuery(this).find("select").attr('disabled', true);

							

							
// 						} else {
// 							jQuery(this).find(".submit_officer_button").text("Edit");
// 						}
// 					});
				    
// 	            }
// 	            else if (response.Status === 2)
// 	            {
// 	            	//console.log(response.data);
// 	            	toastr.error(response.message, response.title);
// 	            }
// 	            else
// 	            {
// 	            	//console.log(tr.find("DIV#form_date_of_cessation"));
// 					toastr.error(response.message, response.title);
// 	            	if (response.error["date_of_cessation"] != "")
// 	            	{
// 	            		var errorsDateOfCessation = '<span class="help-block">*' + response.error["date_of_cessation"] + '</span>';
// 	            		tr.find("DIV#form_date_of_cessation").html( errorsDateOfCessation );
// 	            		tr.find("#date_of_cessation").val("");

// 	            	}
// 	            	else
// 	            	{
// 	            		var errorsDateOfCessation = ' ';
// 	            		tr.find("DIV#form_date_of_cessation").html( errorsDateOfCessation );
// 	            	}

// 	            	if (response.error["alternate_of"] != "")
// 	            	{
// 	            		var errorsAlternateOf = '<span class="help-block">' + response.error["alternate_of"] + '</span>';
// 	            		tr.find("DIV#form_alternate_of").html( errorsAlternateOf );

// 	            	}
// 	            	else
// 	            	{
// 	            		var errorsAlternateOf = ' ';
// 	            		tr.find("DIV#form_alternate_of").html( errorsAlternateOf );
// 	            	}

// 	            	if (response.error["identification_register_no"] != "")
// 	            	{
// 	            		var errorsIdentificationRegisterNo = '<span class="help-block">*' + response.error["identification_register_no"] + '</span>';
// 	            		tr.find("DIV#form_identification_register_no").html( errorsIdentificationRegisterNo );

// 	            	}
// 	            	else
// 	            	{
// 	            		var errorsIdentificationRegisterNo = ' ';
// 	            		tr.find("DIV#form_identification_register_no").html( errorsIdentificationRegisterNo );
// 	            	}

// 	            	if (response.error["name"] != "")
// 	            	{
// 	            		var errorsName = '<span class="help-block">*' + response.error["name"] + '</span>';
// 	            		tr.find("DIV#form_name").html( errorsName );

// 	            	}
// 	            	else
// 	            	{
// 	            		var errorsName = ' ';
// 	            		tr.find("DIV#form_name").html( errorsName );
// 	            	}

// 	            	if (response.error["date_of_appointment"] != "")
// 	            	{
// 	            		var errorsDateOfAppointment = '<span class="help-block">*' + response.error["date_of_appointment"] + '</span>';
// 	            		tr.find("DIV#form_date_of_appointment").html( errorsDateOfAppointment );

// 	            	}
// 	            	else
// 	            	{
// 	            		var errorsDateOfAppointment = ' ';
// 	            		tr.find("DIV#form_date_of_appointment").html( errorsDateOfAppointment );
// 	            	}

// 	            	if (response.error["position"] != "")
// 	            	{
// 	            		var errorsPosition = '<span class="help-block">*' + response.error["position"] + '</span>';
// 	            		tr.find("DIV#form_position").html( errorsPosition );

// 	            	}
// 	            	else
// 	            	{
// 	            		var errorsPosition = ' ';
// 	            		tr.find("DIV#form_position").html( errorsPosition );
// 	            	}
// 	            }
// 	        }
// 	    });
// 	}
// }


