var pathArray = location.href.split( '/' );
var protocol = pathArray[0];
var host = pathArray[2];
var folder = pathArray[3];
var url = protocol + '//' + host + '/' + folder + '/';

$("#gid_add_controller_officer").live('change',function(){
	$(this).parent().parent().parent('form').find("DIV#form_identification_register_no").html( "" );
});
$("#name").live('change',function(){
	$(this).parent().parent().parent('form').find("DIV#form_name").html( "" );
});
$("#date_of_birth").live('change',function(){
	$(this).parent().parent().parent('form').find("DIV#form_date_of_birth").html( "" );
});
$("#nationality").live('change',function(){
	$(this).parent().parent().parent('form').find("DIV#form_nationality").html( "" );
});
$("#controller_address").live('change',function(){
	$(this).parent().parent().parent('form').find("DIV#form_controller_address").html( "" );
});
$("#date_of_registration").live('change',function(){
	$(this).parent().parent().parent('form').find("DIV#form_date_of_registration").html( "" );

    if($(this).parent().parent().parent('form').find('input[name="identification_register_no[]"]').val()!="")
    {
        if($(this).val() == "")
        {
            toastr.error("Date of registration must bigger or equal than date of inforporation.", "Error");
        }
    }
});
$("#date_of_notice").live('change',function(){
    $(this).parent().parent().parent('form').find("DIV#form_date_of_notice").html( "" );
});
$("#confirmation_received_date").live('change',function(){
    $(this).parent().parent().parent('form').find("DIV#form_confirmation_received_date").html( "" );
});
$("#date_of_entry").live('change',function(){
    $(this).parent().parent().parent('form').find("DIV#form_date_of_entry").html( "" );
});
$("#date_of_cessation").live('change',function(){
	$(this).parent().parent().parent('form').find("DIV#form_date_of_cessation").html( "" );
	$(this).parent().parent().parent('form').find("DIV#form_compare_date_of_cessation").html( "" );
});
// $("#add_office_person_link").click(function(){
//     console.log("in");
// });

function add_controller_person(elem)
{
    //console.log(jQuery(elem).parent().parent().find('input[name="identification_register_no[]"]').val());
    jQuery(elem).parent().parent().find('input[name="identification_register_no[]"]').val("");
    jQuery(elem).attr('hidden',"true");
}

function delete_controller(element)
{
    var tr = jQuery(element).parent().parent().parent();

    var client_controller_id = tr.find('input[name="client_controller_id[]"]').val();
    //console.log("client_officer_id==="+client_officer_id);
    $('#loadingmessage').show();
    if(client_controller_id != undefined)
    {
        $.ajax({ //Upload common input
            url: "masterclient/delete_controller",
            type: "POST",
            data: {"client_controller_id": client_controller_id},
            dataType: 'json',
            success: function (response) {
                console.log(response.Status);
                $('#loadingmessage').hide();
            }
        });
    }
    tr.remove();
    toastr.success("Updated Information.", "Updated");

}

function edit_controller(element)
{
	 //element.preventDefault();
	var tr = jQuery(element).parent().parent().parent();
	if(!tr.hasClass("editing")) 
	{
		tr.addClass("editing");
		tr.find("DIV.td").each(function()
		{
			if(!jQuery(this).hasClass("action"))
			{
				/*if(jQuery(this).find('input[name="name[]"]').val()=="")
				{
					jQuery(this).find("input").attr('disabled', false);
				}
				else
				{
					jQuery(this).find("input").attr('disabled', false);
				}*/
				//console.log(jQuery(this).find('input[name="guarantee[]"]'));
				jQuery(this).find('input[name="identification_register_no[]"]').attr('disabled', false);

				jQuery(this).find('input[name="date_of_birth[]"]').attr('disabled', false);
				
				
				jQuery(this).find('input[name="date_of_registration[]"]').attr('disabled', false);
                jQuery(this).find('input[name="date_of_notice[]"]').attr('disabled', false);
                jQuery(this).find('input[name="confirmation_received_date[]"]').attr('disabled', false);
                jQuery(this).find('input[name="date_of_entry[]"]').attr('disabled', false);
				jQuery(this).find('input[name="date_of_cessation[]"]').attr('disabled', false);
				jQuery(this).find("select").attr('disabled', false);
				
				//jQuery(this).find(".datepicker").datepicker('disable');
				//jQuery(this).text("");
				//jQuery(this).append('<input type="text" value="'+value+'" />');
			} 
			else 
			{
				jQuery(this).find(".submit_controller").text("Save");
			}
		});
	} 
	else 
	{
/*			var form_id = $(element).closest('form').attr('id');*/

		//console.log(tr.find('input[name="name[]"]').val()=="");

		// if(tr.find('textarea[name="address[]"]').val()=="" && tr.find('input[name="identification_register_no[]"]').val()=="" && tr.find('input[name="name[]"]').val()=="" && tr.find('input[name="nationality[]"]').val()=="" && tr.find('input[name="date_of_birth[]"]').val()=="" && tr.find('input[name="date_of_registration[]"]').val()=="" && tr.find('input[name="date_of_cessation[]"]').val()=="")
		// {
		// 	//console.log(jQuery(this).find('input[name="client_officer_id[]"]'));
		// 	var client_controller_id = tr.find('input[name="client_controller_id[]"]').val();
		// 	//console.log("client_officer_id==="+client_officer_id);
		// 	if(client_controller_id != undefined)
		// 	{
		// 		$.ajax({ //Upload common input
	 //                url: "masterclient/delete_controller",
	 //                type: "POST",
	 //                data: {"client_controller_id": client_controller_id},
	 //                dataType: 'json',
	 //                success: function (response) {
	 //                	console.log(response.Status);
	 //                }
	 //            });
		// 	}
		// 	tr.remove();
		// }
		// else
		// {
            tr.find('#controller_address').removeAttr("disabled");
			var frm = $(element).closest('form');

			var frm_serialized = frm.serialize();

            $.ajax({
                type: "POST",
                url: "masterclient/check_controller_data",
                data: frm_serialized, // <--- THIS IS THE CHANGE
                dataType: "json",
                async: false,
                success: function(response)
                {
                    if(response)
                    {
                        if (confirm('Do you want to submit?')) 
                        {
                            controller_submit(frm_serialized, tr);
                        } 
                        else 
                        {
                           return false;
                        }
                    }
                    else
                    {
                        controller_submit(frm_serialized, tr);
                    }
                }
            });

			//console.log(frm_serialized);
			
		//}
	}
}

function controller_submit(frm_serialized, tr)
{
    $('#loadingmessage').show();

        $.ajax({ //Upload common input
            url: "masterclient/add_controller",
            type: "POST",
            data: frm_serialized,
            dataType: 'json',
            success: function (response) {
                $('#loadingmessage').hide();
                //console.log(response.Status);
                if (response.Status === 1) {
                    //var errorsDateOfCessation = ' ';
                    toastr.success(response.message, response.title);
                    //tr.find("DIV#form_date_of_cessation").html(" ");
                    if(response.insert_client_controller_id != null)
                    {
                        tr.find('input[name="client_controller_id[]"]').attr('value', response.insert_client_controller_id);
                    }
                    tr.removeClass("editing");

                    
                    tr.attr("data-registe_no",tr.find('input[name="identification_register_no[]"]').val());
                    tr.attr("data-name",tr.find('input[name="position[]"]').val());
                    tr.attr("data-date_of_birth",tr.find('input[name="date_of_birth[]"]').text());
                    tr.attr("data-nationality",tr.find('.nationality option:selected').text());
                    tr.attr("data-address",tr.find('textarea[name="address[]"]').val());
                    tr.attr("data-date_of_registration",tr.find('input[name="date_of_registration[]"]').val());
                    tr.attr("data-date_of_cessation",tr.find('input[name="date_of_cessation[]"]').val());
                    
                    /*tr.data('registe_no',tr.find('input[name="identification_register_no[]"]').val());
                    tr.data('name',tr.find('input[name="identification_register_no[]"]').val());*/
                    tr.find("DIV.td").each(function(){
                        if(!jQuery(this).hasClass("action")){

                            jQuery(this).find('input[name="identification_register_no[]"]').attr('disabled', true);
                            jQuery(this).find('input[name="date_of_birth[]"]').attr('disabled', true);
                            jQuery(this).find('input[name="date_of_registration[]"]').attr('disabled', true);
                            jQuery(this).find('input[name="date_of_notice[]"]').attr('disabled', true);
                            jQuery(this).find('input[name="confirmation_received_date[]"]').attr('disabled', true);
                            jQuery(this).find('input[name="date_of_entry[]"]').attr('disabled', true);
                            jQuery(this).find('input[name="date_of_cessation[]"]').attr('disabled', true);
                            jQuery(this).find("textarea").attr('disabled', true);
                            jQuery(this).find("select").attr('disabled', true);

                            

                            
                        } else {
                            jQuery(this).find(".submit_controller").text("Edit");
                        }
                    });
                    tr.find("DIV#form_date_of_cessation").html("");
                    
                }
                else if (response.Status === 2)
                {
                    //console.log(response.data);
                    toastr.error(response.message, response.title);
                }
                else
                {
                    //console.log(tr.find("DIV#form_date_of_cessation"));
                    toastr.error(response.message, response.title);

                    if (response.error["identification_register_no"] != "")
                    {
                        var errorsIdentificationRegisterNo = '<span class="help-block">*' + response.error["identification_register_no"] + '</span>';
                        tr.find("DIV#form_identification_register_no").html( errorsIdentificationRegisterNo );

                    }
                    else
                    {
                        var errorsIdentificationRegisterNo = ' ';
                        tr.find("DIV#form_identification_register_no").html( errorsIdentificationRegisterNo );
                    }

                    if (response.error["name"] != "")
                    {
                        var errorsName = '<span class="help-block">*' + response.error["name"] + '</span>';
                        tr.find("DIV#form_name").html( errorsName );

                    }
                    else
                    {
                        var errorsName = ' ';
                        tr.find("DIV#form_name").html( errorsName );
                    }

                    /*if (response.error["date_of_birth"] != "")
                    {
                        var errorsDateOfBirth = '<span class="help-block">*' + response.error["date_of_birth"] + '</span>';
                        tr.find("DIV#form_date_of_birth").html( errorsDateOfBirth );

                    }
                    else
                    {
                        var errorsDateOfBirth = ' ';
                        tr.find("DIV#form_date_of_birth").html( errorsDateOfBirth );
                    }*/

                    /*if (response.error["nationality"] != "")
                    {
                        var errorsNationality = '<span class="help-block">' + response.error["nationality"] + '</span>';
                        tr.find("DIV#form_nationality").html( errorsNationality );

                    }
                    else
                    {
                        var errorsNationality = ' ';
                        tr.find("DIV#form_nationality").html( errorsNationality );
                    }*/

                    if (response.error["address"] != "")
                    {
                        var errorsAddress = '<span class="help-block">*' + response.error["address"] + '</span>';
                        tr.find("DIV#form_controller_address").html( errorsAddress );

                    }
                    else
                    {
                        var errorsAddress = ' ';
                        tr.find("DIV#form_controller_address").html( errorsAddress );
                    }

                    if (response.error["date_of_registration"] != "")
                    {
                        var errorsDateOfRegistration = '<span class="help-block" style="margin-top: -15px !important;">*' + response.error["date_of_registration"] + '</span>';
                        tr.find("DIV#form_date_of_registration").html( errorsDateOfRegistration );

                    }
                    else
                    {
                        var errorsDateOfRegistration = ' ';
                        tr.find("DIV#form_date_of_registration").html( errorsDateOfRegistration );
                    }

                    if (response.error["date_of_notice"] != "")
                    {
                        var errorsDateOfNotice = '<span class="help-block" style="margin-top: -15px !important;">*' + response.error["date_of_notice"] + '</span>';
                        tr.find("DIV#form_date_of_notice").html( errorsDateOfNotice );

                    }
                    else
                    {
                        var errorsDateOfNotice = ' ';
                        tr.find("DIV#form_date_of_notice").html( errorsDateOfNotice );
                    }

                    if (response.error["confirmation_received_date"] != "")
                    {
                        var errorsConfirmationReceivedDate = '<span class="help-block" style="margin-top: -15px !important;">*' + response.error["confirmation_received_date"] + '</span>';
                        tr.find("DIV#form_confirmation_received_date").html( errorsConfirmationReceivedDate );

                    }
                    else
                    {
                        var errorsConfirmationReceivedDate = ' ';
                        tr.find("DIV#form_confirmation_received_date").html( errorsConfirmationReceivedDate );
                    }

                    if (response.error["date_of_entry"] != "")
                    {
                        var errorsDateOfEntry = '<span class="help-block" style="margin-top: -15px !important;">*' + response.error["date_of_entry"] + '</span>';
                        tr.find("DIV#form_date_of_entry").html( errorsDateOfEntry );

                    }
                    else
                    {
                        var errorsDateOfEntry = ' ';
                        tr.find("DIV#form_date_of_entry").html( errorsDateOfEntry );
                    }

                    if (response.error["date_of_cessation"] != "")
                    {
                        var errorsDateOfCessation = '<span class="help-block">*' + response.error["date_of_cessation"] + '</span>';
                        tr.find("DIV#form_date_of_cessation").html( errorsDateOfCessation );
                        tr.find("#date_of_cessation").val("");
                    }
                    else
                    {
                        var errorsDateOfCessation = ' ';
                        tr.find("DIV#form_date_of_cessation").html( errorsDateOfCessation );
                    }

                    /*if (response.error["compare_date_of_cessation"] != "")
                    {
                        var errorsCompareDateOfCessation = '<span class="help-block">*' + response.error["compare_date_of_cessation"] + '</span>';
                        tr.find("DIV#form_compare_date_of_cessation").html( errorsCompareDateOfCessation );

                    }
                    else
                    {
                        var errorsCompareDateOfCessation = ' ';
                        tr.find("DIV#form_compare_date_of_cessation").html( errorsCompareDateOfCessation );
                    }*/

                    
                }
            }
        });
}
$(document).ready(function(){
    $('#id_controller_header')
    .wrapInner('<span title="sort this column"/>')
    .each(function(){
        
        var th = $(this),
            thIndex = th.index(),
            inverse = false;
        
        th.click(function(){
            //console.log($("#body_officer").find('.sort_id'));
            if(asc)
            {
            	$("#body_controller").each(function(){
				    $(this).html($(this).find('.controller_sort_id').sort(function(a, b){
				        return ($(b).data('registe_no').toString().toLowerCase()) < ($(a).data('registe_no').toString().toLowerCase()) ? 1 : -1;
				    }));
				});

				asc = false;
            }
            else
            {
	            $("#body_controller").each(function(){
				    $(this).html($(this).find('.controller_sort_id').sort(function(a, b){
				        return ($(b).data('registe_no').toString().toLowerCase()) < ($(a).data('registe_no').toString().toLowerCase()) ? -1 : 1;
				    }));
				});

				asc = true;
            }
            $('#guarantee_start_date').datepicker({ 
			    dateFormat:'dd/mm/yyyy',
			}).datepicker('setStartDate', latest_incorporation_date); 
        });
            
    });

    $('#id_controller_name')
    .wrapInner('<span title="sort this column"/>')
    .each(function(){
        
        var th = $(this),
            thIndex = th.index(),
            inverse = false;
        
        th.click(function(){
            //console.log($("#body_officer").find('.sort_id'));
            if(name_asc)
            {
            	$("#body_controller").each(function(){
				    $(this).html($(this).find('.controller_sort_id').sort(function(a, b){
				        return ($(b).data('name').toString().toLowerCase()) < ($(a).data('name').toString().toLowerCase()) ? 1 : -1;
				    }));
				});

				name_asc = false;
            }
            else
            {
	            $("#body_controller").each(function(){
				    $(this).html($(this).find('.controller_sort_id').sort(function(a, b){
				        return ($(b).data('name').toString().toLowerCase()) < ($(a).data('name').toString().toLowerCase()) ? -1 : 1;
				    }));
				});

				name_asc = true;
            }
            $('#guarantee_start_date').datepicker({ 
			    dateFormat:'dd/mm/yyyy',
			}).datepicker('setStartDate', latest_incorporation_date); 
        });
            
    });

    $('#id_address')
    .wrapInner('<span title="sort this column"/>')
    .each(function(){
        
        var th = $(this),
            thIndex = th.index(),
            inverse = false;
        
        th.click(function(){
            //console.log($("#body_officer").find('.sort_id'));
            if(address_asc)
            {
            	$("#body_controller").each(function(){
				    $(this).html($(this).find('.controller_sort_id').sort(function(a, b){
				        return ($(b).data('address').toLowerCase()) < ($(a).data('address').toLowerCase()) ? 1 : -1;
				    }));
				});

				address_asc = false;
            }
            else
            {
	            $("#body_controller").each(function(){
				    $(this).html($(this).find('.controller_sort_id').sort(function(a, b){
				        return ($(b).data('address').toLowerCase()) < ($(a).data('address').toLowerCase()) ? -1 : 1;
				    }));
				});

				address_asc = true;
            }
            $('#date_of_registration').datepicker({ 
			    dateFormat:'dd/mm/yyyy',
			}).datepicker('setStartDate', latest_incorporation_date); 
        });
            
    });

    $('#id_date_of_birth')
    .wrapInner('<span title="sort this column"/>')
    .each(function(){
        
        var th = $(this),
            thIndex = th.index(),
            inverse = false;
        
        th.click(function(){
            //console.log($("#body_officer").find('.sort_id'));
            if(date_of_birth_asc)
            {
            	$("#body_controller").each(function(){
				    $(this).html($(this).find('.controller_sort_id').sort(function(a, b){
				        return ($(b).data('date_of_birth')) < ($(a).data('date_of_birth')) ? 1 : -1;
				    }));
				});

				date_of_birth_asc = false;
            }
            else
            {
	            $("#body_controller").each(function(){
				    $(this).html($(this).find('.controller_sort_id').sort(function(a, b){
				        return ($(b).data('date_of_birth')) < ($(a).data('date_of_birth')) ? -1 : 1;
				    }));
				});

				date_of_birth_asc = true;
            }
            $('#date_of_registration').datepicker({ 
			    dateFormat:'dd/mm/yyyy',
			}).datepicker('setStartDate', latest_incorporation_date); 
        });
            
    });

    $('#id_nationality')
    .wrapInner('<span title="sort this column"/>')
    .each(function(){
        
        var th = $(this),
            thIndex = th.index(),
            inverse = false;
        
        th.click(function(){
            //console.log($("#body_officer").find('.sort_id'));
            if(nationality_asc)
            {
            	$("#body_controller").each(function(){
				    $(this).html($(this).find('.controller_sort_id').sort(function(a, b){
				        return ($(b).data('nationality').toLowerCase()) < ($(a).data('nationality').toLowerCase()) ? 1 : -1;
				    }));
				});

				nationality_asc = false;
            }
            else
            {
	            $("#body_controller").each(function(){
				    $(this).html($(this).find('.controller_sort_id').sort(function(a, b){
				        return ($(b).data('nationality').toLowerCase()) < ($(a).data('nationality').toLowerCase()) ? -1 : 1;
				    }));
				});

				nationality_asc = true;
            }
            $('#date_of_registration').datepicker({ 
			    dateFormat:'dd/mm/yyyy',
			}).datepicker('setStartDate', latest_incorporation_date); 
        });
            
    });

    $('#id_date_of_registration')
    .wrapInner('<span title="sort this column"/>')
    .each(function(){
        
        var th = $(this),
            thIndex = th.index(),
            inverse = false;
        
        th.click(function(){
            //console.log($("#body_officer").find('.sort_id'));
            if(date_of_registration_asc)
            {
            	$("#body_controller").each(function(){
				    $(this).html($(this).find('.controller_sort_id').sort(function(a, b){
				        return ($(b).data('date_of_registration').toLowerCase()) < ($(a).data('date_of_registration').toLowerCase()) ? 1 : -1;
				    }));
				});

				date_of_registration_asc = false;
            }
            else
            {
	            $("#body_controller").each(function(){
				    $(this).html($(this).find('.controller_sort_id').sort(function(a, b){
				        return ($(b).data('date_of_registration').toLowerCase()) < ($(a).data('date_of_registration').toLowerCase()) ? -1 : 1;
				    }));
				});

				date_of_registration_asc = true;
            }
            $('#date_of_registration').datepicker({ 
			    dateFormat:'dd/mm/yyyy',
			}).datepicker('setStartDate', latest_incorporation_date); 
        });
            
    });

    $('#id_date_of_cessation')
    .wrapInner('<span title="sort this column"/>')
    .each(function(){
        
        var th = $(this),
            thIndex = th.index(),
            inverse = false;
        
        th.click(function(){
            //console.log($("#body_officer").find('.sort_id'));
            if(date_of_cessation_asc)
            {
            	$("#body_controller").each(function(){
				    $(this).html($(this).find('.controller_sort_id').sort(function(a, b){
				        return ($(b).data('date_of_cessation').toLowerCase()) < ($(a).data('date_of_cessation').toLowerCase()) ? 1 : -1;
				    }));
				});

				date_of_cessation_asc = false;
            }
            else
            {
	            $("#body_controller").each(function(){
				    $(this).html($(this).find('.controller_sort_id').sort(function(a, b){
				        return ($(b).data('date_of_cessation').toLowerCase()) < ($(a).data('date_of_cessation').toLowerCase()) ? -1 : 1;
				    }));
				});

				date_of_cessation_asc = true;
            }
            $('#date_of_registration').datepicker({ 
			    dateFormat:'dd/mm/yyyy',
			}).datepicker('setStartDate', latest_incorporation_date); 
        });
            
    });

});

function controller_change_date(latest_incorporation_date)
{
    $('#date_of_registration').datepicker({ 
        dateFormat:'dd/mm/yyyy',
    }).datepicker('setStartDate', latest_incorporation_date);

}

get_client_controller();

function get_client_controller()
{
	if(client_controller)
	{
		//console.log(client_controller);
		//console.log(client_officers[0]['name']);
		for(var i = 0; i < client_controller.length; i++)
		{
			$a="";
			$a += '<form class="tr controller_sort_id" method="post" name="form'+i+'" id="form'+i+'" data-date_of_cessation="'+client_controller[i]["date_of_cessation"]+'" data-date_of_registration="'+client_controller[i]["date_of_registration"]+'" data-nationality="'+client_controller[i]["nationality_name"]+'" data-date_of_birth="'+client_controller[i]["date_of_birth"]+'" data-address="'+client_controller[i]["address"]+'" data-registe_no="'+ (client_controller[i]["identification_no"]!=null ? client_controller[i]["identification_no"] : client_controller[i]["register_no"]) +'" data-name="'+ (client_controller[i]["company_name"]!=null ? client_controller[i]["company_name"] : client_controller[i]["name"]) +'">';
			$a += '<div class="hidden"><input type="text" class="form-control" name="company_code" value="'+client_controller[i]["company_code"]+'"/></div>';
			$a += '<div class="hidden"><input type="text" class="form-control" name="client_controller_id[]" id="client_controller_id" value="'+client_controller[i]["id"]+'"/></div>';
			$a += '<div class="hidden"><input type="text" class="form-control" name="officer_id" id="officer_id" value="'+client_controller[i]["officer_id"]+'"/></div>';
			$a += '<div class="hidden"><input type="text" class="form-control" name="officer_field_type" id="officer_field_type" value="'+client_controller[i]["field_type"]+'"/></div>';
			$a += '<div class="td"><div class="input-group mb-md" style="width: 140px;"><input type="text" style="text-transform:uppercase;" name="identification_register_no[]" class="form-control" value="'+ (client_controller[i]["identification_no"]!=null ? client_controller[i]["identification_no"] : client_controller[i]["register_no"] != null ? client_controller[i]["register_no"] : client_controller[i]["registration_no"]) +'" id="gid_add_controller_officer" disabled="disabled"/><div id="form_identification_register_no"></div><div style=""><a class="" href="'+ url + 'personprofile/add/1" style="cursor:pointer;" id="add_office_person_link" target="_blank" hidden onclick="add_controller_person(this"><div style="cursor:pointer;height:62px;">Click here to <span style="font-weight:bold">Add Person</span></div></a></div></div><div class="input-group mb-md" style="width: 140px;"><input type="text" style="text-transform:uppercase;" name="name[]" id="name" class="form-control" value="'+ (client_controller[i]["company_name"]!=null ? client_controller[i]["company_name"] : client_controller[i]["name"] != null ? client_controller[i]["name"] : client_controller[i]["client_company_name"]) +'" readonly/><div id="form_name"></div></div></div>';
			$a += '<div class="td"><div class="mb-md" style="width: 140px;"><input type="text" name="date_of_birth[]" id="date_of_birth" class="form-control"  value="'+client_controller[i]["date_of_birth"]+'" readonly/></div><div class="mb-md" style="width: 140px;"><input type="text" style="text-transform:uppercase;" name="nationality[]" id="nationality" class="form-control nationality" value="'+client_controller[i]["nationality_name"]+'" readonly/></div></div>';
			$a += '<div class="td"><div class="input-group" style="width: 170px;"><textarea class="form-control" name="address[]" id="controller_address" style="width:100%;height:70px;text-transform:uppercase;" disabled="disabled">'+client_controller[i]["address"]+'</textarea></div><div id="form_controller_address"></div></div>';
            $a += '<div class="td"><div class="input-group mb-md" style="width: 140px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="date_of_registration" name="date_of_registration[]" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" data-plugin-datepicker="" value="'+client_controller[i]["date_of_registration"]+'" disabled="disabled"></div><div id="form_date_of_registration"></div><div class="input-group mb-md" style="width: 140px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="date_of_notice" name="date_of_notice[]" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" data-plugin-datepicker="" value="'+client_controller[i]["date_of_notice"]+'" disabled="disabled"></div><div id="form_date_of_notice"></div></div>';
            $a += '<div class="td"><div class="input-group mb-md" style="width: 140px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="confirmation_received_date" name="confirmation_received_date[]" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" data-plugin-datepicker="" value="'+client_controller[i]["confirmation_received_date"]+'" disabled="disabled"></div><div id="form_confirmation_received_date"></div><div class="input-group mb-md" style="width: 140px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="date_of_entry" name="date_of_entry[]" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" data-plugin-datepicker="" value="'+client_controller[i]["date_of_entry"]+'" disabled="disabled"></div><div id="form_date_of_entry"></div></div>';

			/*$a += '<div class="td"><div class="input-group mb-md" style="width: 130px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="date_of_registration" name="date_of_registration[]" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" data-plugin-datepicker="" value="'+client_controller[i]["date_of_registration"]+'" disabled="disabled"></div><div id="form_date_of_registration"></div></div>';
            $a += '<div class="td"><div class="input-group mb-md" style="width: 130px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="date_of_notice" name="date_of_notice[]" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" data-plugin-datepicker="" value="'+client_controller[i]["form_date_of_notice"]+'" disabled="disabled"></div><div id="form_date_of_notice"></div></div>';
            $a += '<div class="td"><div class="input-group mb-md" style="width: 130px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="confirmation_received_date" name="confirmation_received_date[]" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" data-plugin-datepicker="" value="'+client_controller[i]["confirmation_received_date"]+'" disabled="disabled"></div><div id="form_confirmation_received_date"></div></div>';
            $a += '<div class="td"><div class="input-group mb-md" style="width: 130px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="date_of_entry" name="date_of_entry[]" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" data-plugin-datepicker="" value="'+client_controller[i]["date_of_entry"]+'" disabled="disabled"></div><div id="form_date_of_entry"></div></div>';*/
			$a += '<div class="td"><div class="input-group mb-md" style="width: 140px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="date_of_cessation" name="date_of_cessation[]" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" data-plugin-datepicker="" value="'+client_controller[i]["date_of_cessation"]+'" disabled="disabled"></div><div id="form_date_of_cessation"></div><div id="form_compare_date_of_cessation"></div></div>';
			/*$a += '<div class="td">';
			$a += '<div class="input-group mb-md" style="width: 150px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="guarantee_end_date" name="guarantee_end_date[]" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value=""></div><div id="form_guarantee_end_date"></div>';
			$a += '</div>';*/
            $a += '<div class="td action"><div style="display: inline-block; margin-right: 5px; margin-bottom: 5px;"><button type="button" class="btn btn-primary submit_controller" onclick="edit_controller(this);">Edit</button></div><div style="display: inline-block;"><button type="button" class="btn btn-primary" onclick="delete_controller(this);">Delete</button></div></div>';
			//$a += '<div class="td action" style="width: 140px;"><button type="button" class="btn btn-primary" onclick="edit_controller(this);">Edit</button><button type="button" class="btn btn-primary" onclick="delete_controller(this);">Delete</button></div>';
			$a += '</form>';
				
				/*<input type="button" value="Save" id="save" name="save'+$count_officer+'" class="btn btn-primary" onclick="save(this);">*/
			$("#body_controller").prepend($a);
			$('.datepicker').datepicker({ dateFormat:'yyyy-mm=dd'});
			!function (i) {
				$.ajax({
					type: "POST",
					url: "masterclient/check_incorporation_date",
					data: {"company_code": company_code}, // <--- THIS IS THE CHANGE
					dataType: "json",
					async: false,
					success: function(response)
					{
						//console.log("incorporation_date==="+response[0]["incorporation_date"]);
						$array = response[0]["incorporation_date"].split("/");
						$tmp = $array[0];
						$array[0] = $array[1];
						$array[1] = $tmp;
						//unset($tmp);
						$date_2 = $array[0]+"/"+$array[1]+"/"+$array[2];
						//console.log(new Date($date_2));

						$latest_incorporation_date = new Date($date_2);
						$('#date_of_registration').datepicker({ 
						    dateFormat:'dd/mm/yyyy',
						}).datepicker('setStartDate', $latest_incorporation_date);
					}
				});
			} (i);

			/*!function (i) {
				$.ajax({
					type: "POST",
					url: "masterclient/get_nationality",
					data: {"nationality": client_controller[i]["nationality_id"]},
					dataType: "json",
					success: function(data){
			            $("#form"+i+" #nationality"+i+"").find("option:eq(0)").html("Select Nationality");
			            if(data.tp == 1){
			                $.each(data['result'], function(key, val) {
			                    var option = $('<option />');
			                    option.attr('value', key).text(val);
			                    if(data.selected_nationality != null && key == data.selected_nationality)
			                    {
			                        option.attr('selected', 'selected');
			                        
			                    }

			                    $("#form"+i+" #nationality"+i+"").append(option);
			                    
			                });
			            }
			            else{
			                alert(data.msg);
			            }
					}				
				});
			} (i);*/

			
		}
	}
}

if(client_controller)
{
	$count_controller = client_controller.length;
}
else
{
	$count_controller = 0;
}
$(document).on('click',"#controller_Add",function() {

	$count_controller++;
 	$a=""; 
 	/*<select class="input-sm" style="text-align:right;width: 150px;" id="position" name="position[]" onchange="optionCheck(this);"><option value="Director" >Director</option><option value="CEO" >CEO</option><option value="Manager" >Manager</option><option value="Secretary" >Secretary</option><option value="Auditor" >Auditor</option><option value="Managing Director" >Managing Director</option><option value="Alternate Director">Alternate Director</option></select>*/
	$a += '<form class="tr editing controller_sort_id" method="post" name="form'+$count_controller+'" id="form'+$count_controller+'">';
	$a += '<div class="hidden"><input type="text" class="form-control" name="company_code" value="'+company_code+'"/></div>';
	$a += '<div class="hidden"><input type="text" class="form-control" name="client_controller_id[]" id="client_controller_id" value=""/></div>';
	$a += '<div class="hidden"><input type="text" class="form-control" name="officer_id" id="officer_id" value=""/></div>';
	$a += '<div class="hidden"><input type="text" class="form-control" name="officer_field_type" id="officer_field_type" value=""/></div>';
	$a += '<div class="td"><div class="input-group mb-md" style="width: 140px;"><input type="text" style="text-transform:uppercase;" name="identification_register_no[]" class="form-control" value="" id="gid_add_controller_officer"/><div id="form_identification_register_no"></div><div style=""><a class="" href="'+ url + 'personprofile/add/1" style="cursor:pointer;" id="add_office_person_link" target="_blank" hidden onclick="add_controller_person(this)"><div style="cursor:pointer;height:62px;">Click here to <span style="font-weight:bold">Add Person</span></div></a></div></div><div class="input-group mb-md" style="width: 140px;"><input type="text" style="text-transform:uppercase;" name="name[]" id="name" class="form-control" value="" readonly/><div id="form_name"></div></div></div>';
	$a += '<div class="td"><div class="mb-md" style="width: 140px;"><input type="text" name="date_of_birth[]" id="date_of_birth" class="form-control" value="" readonly/></div><div class="mb-md" style="width: 140px;"><input type="text" style="text-transform:uppercase;" name="nationality[]" id="nationality" class="form-control nationality" value="" readonly/></div></div>';
	$a += '<div class="td"><div class="input-group" style="width: 170px;"><textarea class="form-control" name="address[]" id="controller_address" style="width:100%;height:70px;text-transform:uppercase;" readonly></textarea></div><div id="form_controller_address"></div></div>';
	$a += '<div class="td"><div class="input-group mb-md" style="width: 140px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="date_of_registration" name="date_of_registration[]" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" data-plugin-datepicker="" value=""></div><div id="form_date_of_registration"></div><div class="input-group mb-md" style="width: 140px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="date_of_notice" name="date_of_notice[]" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" data-plugin-datepicker="" value=""></div><div id="form_date_of_notice"></div></div>';
    //$a += '<div class="td"><div class="input-group mb-md" style="width: 150px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="date_of_notice" name="date_of_notice[]" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" data-plugin-datepicker="" value=""></div><div id="form_date_of_notice"></div></div>';
    $a += '<div class="td"><div class="input-group mb-md" style="width: 140px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="confirmation_received_date" name="confirmation_received_date[]" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" data-plugin-datepicker="" value=""></div><div id="form_confirmation_received_date"></div><div class="input-group mb-md" style="width: 140px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="date_of_entry" name="date_of_entry[]" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" data-plugin-datepicker="" value=""></div><div id="form_date_of_entry"></div></div>';
    //$a += '<div class="td"><div class="input-group mb-md" style="width: 150px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="date_of_entry" name="date_of_entry[]" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" data-plugin-datepicker="" value=""></div><div id="form_date_of_entry"></div></div>';
	$a += '<div class="td"><div class="input-group mb-md" style="width: 140px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="date_of_cessation" name="date_of_cessation[]" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" data-plugin-datepicker="" value=""></div><div id="form_date_of_cessation"></div><div id="form_compare_date_of_cessation"></div></div>';
	/*$a += '<div class="td">';
	$a += '<div class="input-group mb-md" style="width: 150px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="guarantee_end_date" name="guarantee_end_date[]" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value=""></div><div id="form_guarantee_end_date"></div>';
	$a += '</div>';*/
    $a += '<div class="td action"><div style="display: inline-block; margin-right: 5px; margin-bottom: 5px;"><button type="button" class="btn btn-primary submit_controller" onclick="edit_controller(this);">Save</button></div><div style="display: inline-block;"><button type="button" class="btn btn-primary" onclick="delete_controller(this);">Delete</button></div></div>';
	//$a += '<div class="td action"><button type="button" class="btn btn-primary" onclick="edit_controller(this);">Save</button><button type="button" class="btn btn-primary" onclick="delete_controller(this);">Delete</button></div>';
	$a += '</form>';
	
	/*<input type="button" value="Save" id="save" name="save'+$count_officer+'" class="btn btn-primary" onclick="save(this);">*/
	$("#body_controller").prepend($a); 
	
	//console.log(latest_incorporation_date);
    $('.datepicker').datepicker({ dateFormat:'dd/mm/yyyy'});

	$('#date_of_registration').datepicker({ 
	    dateFormat:'dd/mm/yyyy',
	}).datepicker('setStartDate', latest_incorporation_date);

	/*!function ($count_controller) {
		$.ajax({
			type: "GET",
			url: "masterclient/get_nationality",
			dataType: "json",
			success: function(data){
	            $("#form"+$count_controller+" #nationality"+$count_controller+"").find("option:eq(0)").html("Select Nationality");
	            if(data.tp == 1){
	                $.each(data['result'], function(key, val) {
	                    var option = $('<option />');
	                    option.attr('value', key).text(val);
	                    
	                    $("#form"+$count_controller+" #nationality"+$count_controller+"").append(option);
	                    
	                });
	            }
	            else{
	                alert(data.msg);
	            }
			}				
		});
	} ($count_controller);*/

});


//Search Officer
$("#gid_add_controller_officer").live('change',function(){
	var officer_frm = $(this);
	//console.log(officer_frm.val());
	//console.log($(this).parent().parent('form').find('input[name="name[]"]').val("aaa"));
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
					officer_frm.parent().parent().parent('form').find('input[name="name[]"]').val(response['company_name']);
					officer_frm.parent().parent().parent('form').find('input[name="officer_id"]').val(response['id']);
					officer_frm.parent().parent().parent('form').find('input[name="officer_field_type"]').val(response['field_type']);

                    if(response['date_of_incorporation'] != null)
                    {
                        var date_of_incorporation = (response['date_of_incorporation']).split('-');
                        response['date_of_incorporation'] = date_of_incorporation[2] + '/' + date_of_incorporation[1] + '/' + date_of_incorporation[0];
                    }
                    else
                    {
                        response['date_of_incorporation'] = "";
                    }

					officer_frm.parent().parent().parent('form').find('input[name="date_of_birth[]"]').val(response['date_of_incorporation']);
					officer_frm.parent().parent().parent('form').find('.nationality').val(response['country_of_incorporation']);

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
						officer_frm.parent().parent().parent('form').find("DIV#form_controller_address").html( "" );
					}
					else if(response["company_foreign_address1"] != null )
					{
						var address = response["company_foreign_address1"];
						officer_frm.parent().parent().parent('form').find("DIV#form_controller_address").html( "" );
					}
					officer_frm.parent().parent().parent('form').find('#controller_address').val(address);

					if(response['company_name'] != undefined)
					{
						//officer_frm.parent().parent('form').find('input[name="name[]"]').attr('readOnly', true);
						officer_frm.parent().parent().parent('form').find("DIV#form_name").html( "" );
						//officer_frm.parent().parent('form').find('input[name="name[]"]').val(response['company_name']);
					}
				}
				else if(response['field_type'] == "individual")
				{
					//console.log(officer_frm.parent().parent().parent('form'));
					officer_frm.parent().parent().parent('form').find('input[name="name[]"]').val(response['name']);
					officer_frm.parent().parent().parent('form').find('input[name="officer_id"]').val(response['id']);
					officer_frm.parent().parent().parent('form').find('input[name="officer_field_type"]').val(response['field_type']);

            		var date_birth = (response['date_of_birth']).split('-');
            		response['date_of_birth'] = date_birth[2] + '/' + date_birth[1] + '/' + date_birth[0];

					officer_frm.parent().parent().parent('form').find('input[name="date_of_birth[]"]').val(response['date_of_birth']);
					officer_frm.parent().parent().parent('form').find('.nationality').val(response['nationality_name']);

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
						officer_frm.parent().parent().parent('form').find("DIV#form_controller_address").html( "" );
					}
					else if(response["foreign_address1"] != null )
					{
						var address = response["foreign_address1"];
						officer_frm.parent().parent().parent('form').find("DIV#form_controller_address").html( "" );
					}
					officer_frm.parent().parent().parent('form').find('#controller_address').val(address);

					if(response['name'] != undefined)
					{
						//officer_frm.parent().parent('form').find('input[name="name[]"]').attr('readOnly', true);
						officer_frm.parent().parent().parent('form').find("DIV#form_name").html( "" );
					}
					
				}
                else
                {
                    officer_frm.parent().parent().parent('form').find('input[name="name[]"]').val(response['company_name']);
                    officer_frm.parent().parent().parent('form').find('input[name="officer_id[]"]').val(response['id']);
                    officer_frm.parent().parent().parent('form').find('input[name="officer_field_type[]"]').val("client");

                    officer_frm.parent().parent().parent('form').find('input[name="date_of_birth[]"]').val(response['incorporation_date']);
                    officer_frm.parent().parent().parent('form').find('.nationality').val("");

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
                        officer_frm.parent().parent().parent('form').find("DIV#form_controller_address").html( "" );
                    }
                    officer_frm.parent().parent().parent('form').find('#controller_address').val(address);

                    if(response['company_name'] != undefined)
                    {
                        //officer_frm.parent().parent().find('input[name="name[]"]').attr('readOnly', true);
                        officer_frm.parent().parent().parent('form').find("DIV#form_name").html( "" );
                    }
                }
				officer_frm.parent().parent().parent('form').find('a#add_office_person_link').attr('hidden',"true");
			}
			else
			{
				officer_frm.parent().parent().parent('form').find('input[name="name[]"]').val("");
				//officer_frm.parent().parent('form').find('input[name="name[]"]').attr('readOnly', false);
				officer_frm.parent().parent().parent('form').find('input[name="officer_id"]').val("");
				officer_frm.parent().parent().parent('form').find('input[name="officer_field_type"]').val("");
				officer_frm.parent().parent().parent('form').find('a#add_office_person_link').removeAttr('hidden');

                officer_frm.parent().parent().parent('form').find('input[name="date_of_birth[]"]').val("");
                officer_frm.parent().parent().parent('form').find('.nationality').val("");
                officer_frm.parent().parent().parent('form').find('#controller_address').val("");
			}
			
			


			
			
			//$b = JSON.parse(data);
			/*$("#add_officer_nama").val($b['name']);
			$("#position").val($b['position']);
			$("#position").val($b['position']);
			$("#date_of_appointment").val($b['date_of_appointment']);
			$("#date_of_cessation").val($b['date_of_cessation']);
			$("#add_officer_postal_code").val($b['zipcode']);
			$("#add_officer_street").val($b['street']);
			$("#add_officer_buildingname").val($b['buildingname']);
			$("#unit_no1").val($b['unit_no1']);
			$("#unit_no2").val($b['unit_no2']);
			$("#alternate_address").val($b['alternate_address']);
			$("#nationality").val($b['nationality']);
			$("#date_of_birth").val($b['date_of_birth']);*/
			// console.log($b['id']);
			// console.log($b['id']);
		}				
	});
	// console.log($(this).val());
});


