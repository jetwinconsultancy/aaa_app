$(document).on('click',"#share_allotment_Add",function() {
	allotment_info_coll = document.getElementsByClassName("row_share_allotment");

    if(allotment_info_coll.length > 0)
    {
        $count_allotment = allotment_info_coll.length + 1;
    }
    else
    {
        $count_allotment = 1;
    }
	
	$field_index = $count_allotment;
 	$a=""; 

	$a += '<tr class="member_tr_'+$field_index+' row_share_allotment" num="'+$field_index+'">';
	$a += '<td><div><input type="text" name="id['+$field_index+']" class="form-control id" value="" id="get_person_name" style="text-transform:uppercase;"/></div><div style="height:16px;" ><a class="" href="'+ url + 'personprofile/add/1" style="cursor:pointer;" id="add_person_link" target="_blank" onclick="add_member(this)" hidden><div style="cursor:pointer;">Click here to <span style="font-weight:bold">Add Entity</span></div></a></div><div class="input-group mb-md name" style="width: 100%;"><input type="text" name="name['+$field_index+']" class="form-control" style="width: 100%;" value="" tabindex="-1" readonly/><div id="form_name"></div></div></td>';
	$a += '<td><div class="mb-md"><select class="form-control" style="text-align:right;width: 100%;" name="class[]" id="class'+$field_index+'" onchange="optionCheckClass(this);"><option value="0" >Select Class</option></select><div id="form_class"></div><div id="other_class" hidden><p style="font-weight:bold;">Others: </p><input type="text" name="other_class['+$field_index+']" class="form-control" value="" disabled="true"/><div id="form_other_class"></div></div></div><div class="input-group mb-md" style="width: 100%;"><select class="form-control" style="text-align:right;width: 100%;" name="currency[]" id="currency'+$field_index+'"><option value="0" >Select Currency</option></select><div id="form_currency"></div></div></td>';
	$a += '<td><div class="input-group mb-md" style="width: 100%;"><input type="text" name="number_of_share['+$field_index+']" class="numberdes form-control number_of_share" value="" id="number_of_share" style="text-align:right; width:100%;" pattern="^[0-9,]+$"/><div id="form_number_of_share"></div></div><div class="input-group mb-md" style="width: 100%;"><input type="text" name="amount_share['+$field_index+']" id="amount_share" class="numberdes form-control amount_share" value="" style="text-align:right; width:100%;" pattern="[0-9.,]"/><div id="form_amount_share"></div></div></td>';
	$a += '<td><div class="input-group mb-md" style="width: 100%;"><input type="text" tabindex="-1" name="no_of_share_paid['+$field_index+']" class="numberdes form-control no_of_share_paid" value="" id="no_of_share_paid" style="text-align:right; width:100%;" readonly/><div id="form_no_of_share_paid"></div></div><div class="input-group mb-md" style="width: 100%;"><input type="text" name="amount_paid['+$field_index+']" id="amount_paid" class="numberdes form-control amount_paid" value="" style="text-align:right; width:100%;" pattern="[0-9.,]"/><div id="form_amount_paid"></div></div></td>';
	$a += '<td><div class="mb-md"><input type="text" name="certificate['+$field_index+']" class="form-control certificate" value="" id="certificate"/></div></td>';
	$a += '<td><div class="action"><button type="button" class="btn btn-primary delete_allotment_button" onclick="delete_share_allotment(this)" style="display: block;">Delete</button></div><div class="hidden"><input type="text" class="form-control cert_id" name="cert_id[]" id="cert_id" value=""/></div><div class="hidden"><input type="text" class="form-control" name="member_share_id[]" id="member_share_id" value=""/></div><div class="hidden"><input type="text" class="form-control" name="officer_id['+$field_index+']" id="officer_id" value=""/></div><div class="hidden"><input type="text" class="form-control" name="field_type['+$field_index+']" id="field_type" value=""/></div></td>';
	$a += '</tr>';

	$("#allotment_add").prepend($a); 

	!function ($field_index) {
		$.ajax({
			type: "GET",
			url: "masterclient/get_sharetype",
			dataType: "json",
			success: function(data){
	            $(".member_tr_"+$field_index+" #class"+$field_index+"").find("option:eq(0)").html("Select Class");
	            if(data.tp == 1){
	                $.each(data['result'], function(key, val) {
	                    var option = $('<option />');
	                    option.attr('value', key).text(val);

	                    $(".member_tr_"+$field_index+" #class"+$field_index+"").append(option);
	                    /*console.log("#form"+$count_charges+" #currency"+$count_charges+"");*/
	                });
	            }
	            else{
	                alert(data.msg);
	            }
			}				
		});
	} ($field_index);

	!function ($field_index) {
		$.ajax({
			type: "GET",
			url: "masterclient/get_currency",
			dataType: "json",
			success: function(data){
	            $(".member_tr_"+$field_index+" #currency"+$field_index+"").find("option:eq(0)").html("Select Currency");
	            if(data.tp == 1){
	                $.each(data['result'], function(key, val) {
	                    var option = $('<option />');
	                    option.attr('value', key).text(val);
	                    if(key == 92)
	                    {
	                        option.attr('selected', 'selected');
	                    }

	                    $(".member_tr_"+$field_index+" #currency"+$field_index+"").append(option);
	                    /*console.log("#form"+$count_charges+" #currency"+$count_charges+"");*/
	                });
	            }
	            else{
	                alert(data.msg);
	            }
			}				
		});
	} ($field_index);

	$count_allotment++;
});

function add_member(elem)
{
    jQuery(elem).parent().parent().find('#get_person_name').val("");
    jQuery(elem).attr('hidden',"true");
}

$("#get_person_name").live('change',function(){
	var allotment_frm = $(this);
	console.log(allotment_frm.val());
	//console.log($(this).parent().parent().parent().attr("num"));
	var input_num = allotment_frm.parent().parent().parent().attr("num");
	$("#loadingmessage").show();
	$.ajax({
		type: "POST",
		url: "transaction/get_transaction_person",
		data: {"identification_register_no":allotment_frm.val()}, // <--- THIS IS THE CHANGE
		dataType: "json",
		success: function(responses){
			//console.log(allotment_frm.parent().parent().parent().find('input[name="officer_id['+input_num+']"]'));
			//console.log(allotment_frm.parent().parent('div').find('input[name="officer_id['+input_num+']"]'));
			$("#loadingmessage").hide();
			//console.log(responses);
			if(responses.status == 1)
			{
				var response = responses.info;
				if(response)
				{
					allotment_frm.parent().parent().find('input[name="name['+input_num+']"]').val(response['name']);
					allotment_frm.parent().parent().parent().find('input[name="officer_id['+input_num+']"]').val(response['id']);
					allotment_frm.parent().parent().parent().find('input[name="field_type['+input_num+']"]').val(response['field_type']);
					/*if(response['name'] != undefined)
					{*/
					allotment_frm.parent().parent().find('a#add_person_link').attr('hidden',"true");
					//allotment_frm.parent().parent('div').find('input[name="name['+input_num+']"]').attr('readonly', true);
					//}
					
				}
				else
				{
					allotment_frm.parent().parent().find('a#add_person_link').removeAttr('hidden');
					allotment_frm.parent().parent().find('input[name="name['+input_num+']"]').val("");
					//allotment_frm.parent().parent('div').find('input[name="name['+input_num+']"]').attr('readonly', false);
					allotment_frm.parent().parent().parent().find('input[name="officer_id['+input_num+']"]').val("");
					allotment_frm.parent().parent().parent().find('input[name="field_type['+input_num+']"]').val("");
					allotment_frm.parent().parent().parent().find(".name .help-block").remove();
					//console.log(allotment_frm.parent().parent().parent().find(".name .help-block"));
				}
				
			}
			else
			{
				allotment_frm.parent().parent().find('input[name="name['+input_num+']"]').val("");
				allotment_frm.parent().parent().parent().find(".name .help-block").remove();
				
				toastr.error("This person is an auditor for this company.", "Error");
			}
			$('#allotment_form').formValidation('revalidateField', 'name['+input_num+']');
		}				
	});
	// console.log($(this).val());
});

$("#number_of_share").live('change',function(){
	var no_of_share_paid = 0;
	var allotment_frm = $(this);

	var input_number = allotment_frm.parent().parent().parent().attr("num");

	if($('input[name="number_of_share['+input_number+']"]').val() != "" && $('input[name="amount_share['+input_number+']"]').val() != "" && $('input[name="amount_paid['+input_number+']"]').val() != "")
	{
		no_of_share_paid = (parseFloat(removeCommas($('input[name="amount_paid['+input_number+']"]').val())) * parseInt(removeCommas($('input[name="number_of_share['+input_number+']"]').val()))) / parseFloat(removeCommas($('input[name="amount_share['+input_number+']"]').val()));
	}
	$('input[name="no_of_share_paid['+input_number+']"]').val(addCommas(parseInt(no_of_share_paid)));

});

$("#amount_share").live('change',function(){
	var no_of_share_paid = 0;
	var allotment_frm = $(this);

	var input_number = allotment_frm.parent().parent().parent().attr("num");

	if($('input[name="number_of_share['+input_number+']"]').val() != "" && $('input[name="amount_share['+input_number+']"]').val() != "" && $('input[name="amount_paid['+input_number+']"]').val() != "")
	{
		no_of_share_paid = (parseFloat(removeCommas($('input[name="amount_paid['+input_number+']"]').val())) * parseInt(removeCommas($('input[name="number_of_share['+input_number+']"]').val()))) / parseFloat(removeCommas($('input[name="amount_share['+input_number+']"]').val()));
	}
	$('input[name="no_of_share_paid['+input_number+']"]').val(addCommas(parseInt(no_of_share_paid)));

});

$("#amount_paid").live('change',function(){
	var no_of_share_paid = 0;
	var allotment_frm = $(this);

	var input_number = allotment_frm.parent().parent().parent().attr("num");

	if($('input[name="number_of_share['+input_number+']"]').val() != "" && $('input[name="amount_share['+input_number+']"]').val() != "" && $('input[name="amount_paid['+input_number+']"]').val() != "")
	{
		no_of_share_paid = (parseFloat(removeCommas($('input[name="amount_paid['+input_number+']"]').val())) * parseInt(removeCommas($('input[name="number_of_share['+input_number+']"]').val()))) / parseFloat(removeCommas($('input[name="amount_share['+input_number+']"]').val()));
	}
	$('input[name="no_of_share_paid['+input_number+']"]').val(addCommas(parseInt(no_of_share_paid)));

});

function removeCommas(str) {
    while (str.search(",") >= 0) {
        str = (str + "").replace(',', '');
    }
    return str;
};

function delete_share_allotment(element)
{
    var tr = jQuery(element).parent().parent().parent();

    var member_share_id = tr.find('input[name="member_share_id[]"]').val();
    var cert_id = tr.find('input[name="cert_id[]"]').val();
    //console.log("client_officer_id==="+client_officer_id);
    
    if(member_share_id != undefined && cert_id != undefined)
    {
    	$('#loadingmessage').show();
        $.ajax({ //Upload common input
            url: "transaction/delete_member",
            type: "POST",
            data: {"member_share_id": member_share_id, "cert_id": cert_id},
            dataType: 'json',
            success: function (response) {
                $('#loadingmessage').hide();
                tr.remove();
    			toastr.success("Updated Information.", "Updated");
            }
        });
    }
    
}

$(document).on('click',"#submitShareAllotmentInfo",function(e){
    $('#loadingmessage').show();
    console.log($('input[name="address_type"]').val());
    console.log($('input[name="address_type"]').val() == "Registered Office Address");
    if($('input[name="address_type"]').val() == "Registered Office Address")
    {
    	$("#tr_registered_edit input").removeAttr('disabled');
    }
    else
    {
    	$("#tr_registered_edit input").attr('disabled', 'true');
    }
    $.ajax({ //Upload common input
      url: "transaction/save_share_allotment",
      type: "POST",
      data: $('form#share_allotment_form').serialize()+ '&transaction_code=' + $('#transaction_code').val() + '&transaction_task_id=' + $('#transaction_task').val() + '&registration_no=' + $('#uen').val(),
      dataType: 'json',
      success: function (response,data) {
        $('#loadingmessage').hide();

          if (response.Status === 1) 
          {
          	if($('input[name="address_type"]').val() == "Registered Office Address")
		    {
		    	$("#tr_registered_edit input").attr('disabled', 'true');
		    }
            toastr.success(response.message, response.title);
            $("#allotment_add .row_share_allotment").remove();
            $("#transaction_trans #transaction_master_id").val(response.transaction_master_id);
            $("#transaction_trans #transaction_code").val(response.transaction_code);
            shareAllotmentInterface(response.transaction_member);

          }
        }
    })
});