$count_bank_info = 0;
$(document).on('click',"#bank_Add",function() {
	// console.log($("#body_bank_info form").index());
	$count_bank_info++;
 	$a=""; 
	$a += '<form class="tr bank_editing sort_id" method="post" name="form'+$count_bank_info+'" id="form'+$count_bank_info+'">';
	$a += '<div class="hidden"><input type="text" class="form-control" name="firm_id[]" id="firm_id" value="'+firm_id+'"/></div>';
	$a += '<div class="hidden"><input type="text" class="form-control" name="bank_info_id[]" id="bank_info_id" value=""/></div>';
	$a += '<div class="td"><input type="text" name="bank_id[]" id="bank_id" class="form-control" value=""/><div id="form_bank_id"></div></div>';
	$a += '<div class="td"><input type="text" name="banker[]" class="form-control" value="" id="banker"/><div id="form_banker"></div></div>';
	$a += '<div class="td"><input type="text" name="account_number[]" id="account_number" class="form-control" value=""/><div id="form_account_number"></div></div>';
	$a += '<div class="td"><input type="text" name="bank_code[]" id="bank_code" class="form-control" value=""/><div id="form_bank_code"></div></div>';
	$a += '<div class="td">';
	$a += '<input type="text" name="swift_code[]" id="swift_code" class="form-control" value=""/><div id="form_swift_code"></div>';
	$a += '</div>';
	$a += '<div class="td">';
	$a += '<select class="form-control currency" style="width: 100%;" name="currency[]" id="currency"><option value="0">Select Currency</option></select><div id="form_currency"></div>';;
	$a += '</div>';
	$a += '<div class="td">';
	$a += '<input type="file" style="display:none" id="attachment'+$count_bank_info+'" name="attachment[]"><label for="attachment'+$count_bank_info+'" class="btn btn-primary attachment">Attachment</label><br/><span class="file_name"></span><input type="hidden" class="hidden_attachment" name="hidden_attachment[]" value=""/><div id="form_attachment"></div>';
    $a += '</div>';
	$a += '<div class="td bank_action"><div style="display: inline-block; margin-right: 5px; margin-bottom: 5px;"><button type="button" class="btn btn-primary submit_bank_info_button" onclick="edit_bank(this);">Save</button></div><div style="display: inline-block;"><button type="button" class="btn btn-primary" onclick="delete_bank_info(this);">Delete</button></div></div>';
	$a += '<div class="td"><label class="switch"><input name="in_use_bank_switch" class="in_use_bank_switch" type="checkbox"><span class="slider round"></span></label></div>';
	$a += '</form>';
	
	$("#body_bank_info").prepend($a); 

	$("#loadingmessage").show();
	$.ajax({
        type: "GET",
        url: "masterclient/get_currency",
        async: false,
        dataType: "json",
        success: function(data){
            // console.log(data['result']);
            //console.log(tr.find("#service_type"));
            //console.log(dropdown_data.unit_pricing);
            $("#loadingmessage").hide();
            $.each(data['result'], function(key, val) {
                var option = $('<option />');
                option.attr('value', key).text(val);
                // if(dropdown_data.currency != undefined && key == dropdown_data.currency)
                // {
                //     option.attr('selected', 'selected');
                // }
                $('#form'+$count_bank_info).find("#currency").append(option);
            });
        }
    });
});

$(document).on('change','[type=file]',function(){
    var filename = "";
    //console.log(this.files[0]);
    for(var i = 0; i < this.files.length; i++)
    {
	    if(i == 0)
	    {
	        filename = this.files[i].name;
	    }
	    else
	    {
	        filename = filename + ", " + this.files[i].name;
	    }
    }
    //console.log(filename);
    $(this).parent().find(".file_name").html(filename);
    $(this).parent().find(".hidden_attachment").val("");
    $(this).parent().find("DIV#form_attachment").html("");
});

//$("[name='in_use_bank_switch']").change(function() {
$(document).on("change","[name='in_use_bank_switch']",function(element) {
	/*console.log(this.checked);
	console.log($(this).parent().parent().parent().find("#firm_id").val());*/
	var checkbox = $(this);
	//var checkbox_checked = $('input[name="firm_switch"]:checked');
	var checked = this.checked;
	var firm_id = $(this).parent().parent().parent().find("#firm_id").val();
	var bank_info_id = $(this).parent().parent().parent().find("#bank_info_id").val();
	// console.log(checkbox);
	// console.log(checked);
	// console.log(firm_id);
	// console.log(bank_info_id);
	//var user_id = $(this).parent().parent().parent().find("#user_id").val();

	if(bank_info_id == "")
	{
		checkbox.prop('checked', false);
		toastr.error("Please save the information before you set in use bank.", "Error");
	}
	else
	{
		$.ajax({
			type: "POST",
			url: "our_firm/check_in_use_bank",
			data: {"checked":checked, "firm_id": firm_id, "bank_info_id": bank_info_id}, // <--- THIS IS THE CHANGE
			dataType: "json",
			success: function(response){
				if(response.Status == 1)
				{
					checkbox.prop('checked', true);
					toastr.success(response.message, response.title);
				}
				else
				{
					//if(confirm('Do you wanna change the in use company?')) {
					bootbox.confirm("Do you wanna change the in use bank?", function (result) {
			            if (result) 
			            {
							$.ajax({
								type: "POST",
								url: "our_firm/change_in_use_bank",
								data: {"checked":checked, "firm_id": firm_id, "bank_info_id": bank_info_id}, // <--- THIS IS THE CHANGE
								dataType: "json",
								success: function(response){
									if(response.Status == 1)
									{
										/*if($('input[name="firm_switch"]:checked'))
										{*/
										//if(this.checked){
											 $('input[name="in_use_bank_switch"]:checked').not(checkbox).prop('checked', false);
										//}
										//console.log($('input[name="in_use_switch"]:checked').not(this));
										//}

										toastr.success(response.message, response.title);
										//location.reload();
									}
								}
							});
						}
						else
						{
							if(checked)
							{
								//console.log(checkbox);
								checkbox.prop('checked', false);
							}
							else
							{
								checkbox.prop('checked', true);
							}
							//return false;
						}
					});
					// else
					// {
					// 	if(checked)
					// 	{
					// 		//console.log(checkbox);
					// 		checkbox.prop('checked', false);
					// 	}
					// 	else
					// 	{
					// 		checkbox.prop('checked', true);
					// 	}
					// 	return false;
					// }

				}

			}
		});
	}
});

function edit_bank(element)
{
	 //element.preventDefault();
	var tr = jQuery(element).parent().parent().parent();
	if(!tr.hasClass("bank_editing")) 
	{
		tr.addClass("bank_editing");
		tr.find("DIV.td").each(function()
		{
			if(!jQuery(this).hasClass("bank_action"))
			{
				//jQuery(this).find('input[name="bank_id[]"]').attr('disabled', false);
				jQuery(this).find('input[name="banker[]"]').attr('disabled', false);
				jQuery(this).find('input[name="account_number[]"]').attr('disabled', false);
				jQuery(this).find('input[name="bank_code[]"]').attr('disabled', false);
				jQuery(this).find('input[name="swift_code[]"]').attr('disabled', false);
				jQuery(this).find('label.attachment').attr('disabled', false);
				jQuery(this).find("select").attr('disabled', false);
			} 
			else 
			{
				jQuery(this).find(".submit_bank_info_button").text("Save");
			}
		});
	} 
	else 
	{
		var frm = $(element).closest('form');

		//var frm_serialized = frm.serialize();

		var frm_serialized = frm.attr('id');
		//console.log(frm_serialized);
		// $.ajax({
		// 	type: "POST",
		// 	url: "masterclient/check_officer_data",
		// 	data: frm_serialized, // <--- THIS IS THE CHANGE
		// 	dataType: "json",
		// 	async: false,
		// 	success: function(response)
		// 	{
		// 		if(response)
		// 		{
		// 			bootbox.confirm("Do you want to submit? Do you want to overwrite previous document?", function (result) {
		// 	            if (result) {
		// 	            	officer_submit(frm_serialized, tr);
		// 	            }
		// 	            else
		// 	            {
		// 	            	return false;
		// 	            }
		// 	        });
		// 		}
		// 		else
		// 		{

					bank_info_submit(frm_serialized, tr);
		// 		}
		// 	}
		// });
	}
}

$("#bank_id").live('change',function(){
	$(this).parent().parent('form').find("DIV#form_bank_id").html( "" );
});

$("#banker").live('change',function(){
	$(this).parent().parent('form').find("DIV#form_banker").html( "" );
});

$("#account_number").live('change',function(){
	$(this).parent().parent('form').find("DIV#form_account_number").html( "" );
});

$("#bank_code").live('change',function(){
	$(this).parent().parent('form').find("DIV#form_bank_code").html( "" );
});

$("#swift_code").live('change',function(){
	$(this).parent().parent('form').find("DIV#form_swift_code").html( "" );
});

$("#currency").live('change',function(){
	$(this).parent().parent('form').find("DIV#form_currency").html( "" );
});

function bank_info_submit(frm_serialized, tr)
{
	//console.log(frm_serialized);
	var formData = new FormData($('#'+frm_serialized)[0]);
	//console.log(formData);
	$('#loadingmessage').show();
	$.ajax({ //Upload common input
        url: "our_firm/add_bank_info",
        type: "POST",
        data: formData,
        dataType: 'json',
        // Tell jQuery not to process data or worry about content-type
        // You *must* include these options!
        // + '&user_name_text=' + $(".user_name option:selected").text()
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
        	$('#loadingmessage').hide();
        	//console.log(response.Status);
            if (response.Status === 1) {
            	//var errorsDateOfCessation = ' ';
            	toastr.success(response.message, response.title);
            	if(response.insert_bank_info_id != null)
            	{
            		tr.find('input[name="bank_info_id[]"]').attr('value', response.insert_bank_info_id);
            	}
            	tr.removeClass("bank_editing");

				tr.find("DIV.td").each(function(){
					if(!jQuery(this).hasClass("bank_action"))
					{

						jQuery(this).find('input[name="bank_id[]"]').attr('readonly', true);
						jQuery(this).find('input[name="banker[]"]').attr('disabled', true);
						jQuery(this).find('input[name="account_number[]"]').attr('disabled', true);
						jQuery(this).find('input[name="bank_code[]"]').attr('disabled', true);
						jQuery(this).find('input[name="swift_code[]"]').attr('disabled', true);
						jQuery(this).find('label.attachment').attr('disabled', true);
						jQuery(this).find("select").attr('disabled', true);
						
					} 
					else 
					{
						jQuery(this).find(".submit_bank_info_button").text("Edit");
					}
				});
			    
            }
            else if (response.Status === 2)
            {
            	toastr.error(response.message, response.title);
            }
            else
            {
				toastr.error(response.message, response.title);
            	if (response.error["bank_id"] != "")
            	{
            		var errorsBankId = '<span class="help-block">*' + response.error["bank_id"] + '</span>';
            		tr.find("DIV#form_bank_id").html( errorsBankId );
            		tr.find("#bank_id").val("");

            	}
            	else
            	{
            		var errorsBankId = ' ';
            		tr.find("DIV#form_bank_id").html( errorsBankId );
            	}

            	if (response.error["banker"] != "")
            	{
            		var errorsBanker = '<span class="help-block">*' + response.error["banker"] + '</span>';
            		tr.find("DIV#form_banker").html( errorsBanker );

            	}
            	else
            	{
            		var errorsBanker = ' ';
            		tr.find("DIV#form_banker").html( errorsBanker );
            	}

            	if (response.error["account_number"] != "")
            	{
            		var errorsAccountNumber = '<span class="help-block">*' + response.error["account_number"] + '</span>';
            		tr.find("DIV#form_account_number").html( errorsAccountNumber );

            	}
            	else
            	{
            		var errorsAccountNumber = ' ';
            		tr.find("DIV#form_account_number").html( errorsAccountNumber );
            	}

            	if (response.error["bank_code"] != "")
            	{
            		var errorsBankCode = '<span class="help-block">*' + response.error["bank_code"] + '</span>';
            		tr.find("DIV#form_bank_code").html( errorsBankCode );

            	}
            	else
            	{
            		var errorsBankCode = ' ';
            		tr.find("DIV#form_bank_code").html( errorsBankCode );
            	}

            	if (response.error["swift_code"] != "")
            	{
            		var errorsSwiftCode = '<span class="help-block">*' + response.error["swift_code"] + '</span>';
            		tr.find("DIV#form_swift_code").html( errorsSwiftCode );

            	}
            	else
            	{
            		var errorsSwiftCode = ' ';
            		tr.find("DIV#form_swift_code").html( errorsSwiftCode );
            	}

            	if (response.error["currency"] != "")
            	{
            		var errorsCurrency = '<span class="help-block">*' + response.error["currency"] + '</span>';
            		tr.find("DIV#form_currency").html( errorsCurrency );

            	}
            	else
            	{
            		var errorsCurrency = ' ';
            		tr.find("DIV#form_currency").html( errorsCurrency );
            	}

            	if (response.error["attachment"] != "")
            	{
            		var errorsAttachment = '<span class="help-block">*' + response.error["attachment"] + '</span>';
            		tr.find("DIV#form_attachment").html( errorsAttachment );

            	}
            	else
            	{
            		var errorsAttachment = ' ';
            		tr.find("DIV#form_attachment").html( errorsAttachment );
            	}
            }
        }
    });
}

if(bank_info)
{
	$count_bank_info = bank_info.length;

	for(var i = 0; i < bank_info.length; i++)
	{
		$a=""; 
		$a += '<form class="tr sort_id" method="post" name="form'+i+'" id="form'+i+'">';
		$a += '<div class="hidden"><input type="text" class="form-control" name="firm_id[]" id="firm_id" value="'+bank_info[i]["firm_id"]+'"/></div>';
		$a += '<div class="hidden"><input type="text" class="form-control" name="bank_info_id[]" id="bank_info_id" value="'+bank_info[i]["id"]+'"/></div>';
		$a += '<div class="td"><input type="text" name="bank_id[]" id="bank_id" class="form-control" value="'+bank_info[i]["bank_id"]+'" readonly/><div id="form_bank_id"></div></div>';
		$a += '<div class="td"><input type="text" name="banker[]" class="form-control" value="'+bank_info[i]["banker"]+'" id="banker" disabled="disabled"/><div id="form_banker"></div></div>';
		$a += '<div class="td"><input type="text" name="account_number[]" id="account_number" class="form-control" value="'+bank_info[i]["account_number"]+'" disabled="disabled"/><div id="form_account_number"></div></div>';
		$a += '<div class="td"><input type="text" name="bank_code[]" id="bank_code" class="form-control" value="'+bank_info[i]["bank_code"]+'" disabled="disabled"/><div id="form_bank_code"></div></div>';
		$a += '<div class="td">';
		$a += '<input type="text" name="swift_code[]" id="swift_code" class="form-control" value="'+bank_info[i]["swift_code"]+'" disabled="disabled"/><div id="form_swift_code"></div>';
		$a += '</div>';
		$a += '<div class="td">';
		$a += '<select class="form-control currency" style="width: 100%;" name="currency[]" id="currency"" disabled="disabled"><option value="0">Select Currency</option></select><div id="form_currency"></div>';;
		$a += '</div>';
		$a += '<div class="td">'
		$a += "<input type='file' style='display:none' id='attachment"+i+"' name='attachment[]'><label for='attachment"+i+"' class='btn btn-primary attachment' disabled='disabled'>Attachment</label><br/><span class='file_name'></span><input type='hidden' class='hidden_attachment' name='hidden_attachment[]'' value='"+bank_info[i]["qr_code"]+"'/><div id='form_attachment'></div>";
	    $a += '</div>';
		$a += '<div class="td bank_action"><div style="display: inline-block; margin-right: 5px; margin-bottom: 5px;"><button type="button" class="btn btn-primary submit_bank_info_button" onclick="edit_bank(this);">Edit</button></div><div style="display: inline-block;"><button type="button" class="btn btn-primary" onclick="delete_bank_info(this);">Delete</button></div></div>';
		$a += '<div class="td"><label class="switch"><input name="in_use_bank_switch" class="in_use_bank_switch" type="checkbox" '+((bank_info[i]["in_use"] == 1)?"checked":"")+'><span class="slider round"></span></label></div>';
		$a += '</form>';
			
		$("#body_bank_info").prepend($a);

		if(bank_info[i]["qr_code"] != "")
		{
			var file_result = JSON.parse(bank_info[i]["qr_code"]);
	        var filename = "";
	        //console.log(this.files);
	        for(var k = 0; k < file_result.length; k++)
	        {
	            if(k == 0)
	            {
	                filename = '<a href="'+base_url+'uploads/billing_qr_code/'+file_result[k]+'" target="_blank">'+file_result[k]+'</a>';
	            }
	            else
	            {
	                filename = filename + ", " + '<a href="'+base_url+'uploads/billing_qr_code/'+file_result[k]+'" target="_blank">'+file_result[k]+'</a>';
	            }
	        }
	        $('#form'+i+' .file_name').html(filename);
	    }

		$("#loadingmessage").show();
		$.ajax({
	        type: "GET",
	        url: "masterclient/get_currency",
	        async: false,
	        dataType: "json",
	        success: function(data){
	            // console.log(data['result']);
	            //console.log(tr.find("#service_type"));
	            //console.log(dropdown_data.unit_pricing);
	            $("#loadingmessage").hide();
	            $.each(data['result'], function(key, val) {
	                var option = $('<option />');
	                option.attr('value', key).text(val);
	                if(bank_info[i]["currency"] != undefined && key == bank_info[i]["currency"])
	                {
	                    option.attr('selected', 'selected');
	                }
	                $('#form'+i).find("#currency").append(option);
	            });
	        }
	    });
	}
}

function delete_bank_info(element)
{
	var tr = jQuery(element).parent().parent().parent();
	var bank_info_id = tr.find('input[name="bank_info_id[]"]').val();
	$('#loadingmessage').show();
	if(bank_info_id != undefined)
	{
		$.ajax({ //Upload common input
            url: "our_firm/delete_bank_info",
            type: "POST",
            data: {"bank_info_id": bank_info_id},
            dataType: 'json',
            success: function (response) {
            	//console.log(response.Status);
            	$('#loadingmessage').hide();
            	if(response.Status == 1)
            	{
            		tr.remove();
            		toastr.success("Updated Information.", "Updated");

            	}
            }
        });
	}
}