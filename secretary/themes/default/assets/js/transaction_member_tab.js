var allotment_info_coll;

function optionCheckClass(share_capital_element) 
{	

	var tr = jQuery(share_capital_element).parent().parent();

	var num = tr.parent().attr("num");

	tr.find('input[name="other_class['+num+']"]').val('');
	tr.find('select[name="currency[]"] option').removeAttr('selected');

	if(tr.find('select[name="class[]"]').val() == "2")
	{
		
		tr.find("DIV#other_class").removeAttr('hidden');
		tr.find('input[name="other_class['+num+']"]').removeAttr('disabled');

	}
	else
	{
		tr.find("DIV#other_class").attr("hidden","true");
		tr.find('input[name="other_class['+num+']"]').val("");
		tr.find('input[name="other_class['+num+']"]').attr('disabled', 'true');
	}
}

//$count_allotment = 0;
$(document).on('click',"#allotment_member_Add",function() {
	allotment_info_coll = document.getElementsByClassName("row_allotment");

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

	$a += '<tr class="member_tr_'+$field_index+' row_allotment" num="'+$field_index+'">';
	$a += '<td><div><input type="text" name="id['+$field_index+']" class="form-control id" value="" id="get_allot_person_name" style="text-transform:uppercase;"/></div><div style="height:16px;" ><a class="" href="'+ url + 'personprofile/add/1" style="cursor:pointer;" id="add_person_link" target="_blank" onclick="add_member(this)" hidden><div style="cursor:pointer;">Click here to <span style="font-weight:bold">Add Entity</span></div></a></div><div class="input-group mb-md name"><input type="text" name="name['+$field_index+']" class="form-control" value="" tabindex="-1" readonly/><div id="form_name"></div></div></td>';
	$a += '<td><div class="mb-md"><select class="form-control" style="text-align:right;width: 100%;" name="class[]" id="class'+$field_index+'" onchange="optionCheckClass(this);"><option value="0" >Select Class</option></select><div id="form_class"></div><div id="other_class" hidden><p style="font-weight:bold;">Others: </p><input type="text" name="other_class['+$field_index+']" class="form-control" value="" disabled="true"/><div id="form_other_class"></div></div></div><div class="input-group mb-md"><select class="form-control" style="text-align:right;width: 170px;" name="currency[]" id="currency'+$field_index+'"><option value="0" >Select Currency</option></select><div id="form_currency"></div></div></td>';
	$a += '<td><div class="input-group mb-md"><input type="text" name="number_of_share['+$field_index+']" class="numberdes form-control number_of_share" value="" id="number_of_share" style="text-align:right;" pattern="^[0-9,]+$"/><div id="form_number_of_share"></div></div><div class="input-group mb-md"><input type="text" name="amount_share['+$field_index+']" id="amount_share" class="numberdes form-control amount_share" value="" style="text-align:right;" pattern="[0-9.,]"/><div id="form_amount_share"></div></div></td>';
	$a += '<td><div class="input-group mb-md"><input type="text" tabindex="-1" name="no_of_share_paid['+$field_index+']" class="numberdes form-control no_of_share_paid" value="" id="no_of_share_paid" style="text-align:right;" readonly/><div id="form_no_of_share_paid"></div></div><div class="input-group mb-md"><input type="text" name="amount_paid['+$field_index+']" id="amount_paid" class="numberdes form-control amount_paid" value="" style="text-align:right;" pattern="[0-9.,]"/><div id="form_amount_paid"></div></div></td>';
	$a += '<td><div class="mb-md"><input type="text" name="certificate['+$field_index+']" class="form-control certificate" value="" id="certificate"/></div></td>';
	$a += '<td><div class="action"><button type="button" class="btn btn-primary delete_allotment_button" onclick="delete_allotment(this)" style="display: block;">Delete</button></div><div class="hidden"><input type="text" class="form-control cert_id" name="cert_id[]" id="cert_id" value=""/></div><div class="hidden"><input type="text" class="form-control" name="member_share_id[]" id="member_share_id" value=""/></div><div class="hidden"><input type="text" class="form-control" name="officer_id['+$field_index+']" id="officer_id" value=""/></div><div class="hidden"><input type="text" class="form-control" name="field_type['+$field_index+']" id="field_type" value=""/></div></td>';
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

$(document).on('click',"#submitMemberInfo",function(e){
    $('#loadingmessage').show();
    $.ajax({ //Upload common input
      url: "transaction/save_allotment",
      type: "POST",
      data: $('form#member_form').serialize()+ '&transaction_code=' + $('#transaction_code').val() + '&transaction_task_id=' + $('#transaction_task').val() + '&transaction_master_id=' + $("#transaction_trans #transaction_master_id").val(),
      dataType: 'json',
      success: function (response,data) {
        $('#loadingmessage').hide();

          if (response.Status === 1) 
          {
            toastr.success(response.message, response.title);
            $("#allotment_add .row_allotment").remove();
            memberInterface(response.transaction_member);

            $('.chairman').find("option:eq(0)").html("Select Chairman");
			$(".chairman option:gt(0)").remove();
            if (!showCM) 
		    {
		        cm1.getAllChairman();
		    }
		    else
		    {
		        cm1.getChairman();
		    }
          }
        }
    })
});

$("#get_allot_person_name").live('change',function(){
	var allotment_frm = $(this);
	var input_num = allotment_frm.parent().parent().parent().attr("num");
	$("#loadingmessage").show();
	$.ajax({
		type: "POST",
		url: "transaction/get_transaction_person",
		data: {"identification_register_no":allotment_frm.val(), "company_code": $(".transaction_company_code").val()}, // <--- THIS IS THE CHANGE
		dataType: "json",
		success: function(responses){
			$("#loadingmessage").hide();
			if(responses.status == 1)
			{
				var response = responses.info;
				if(response)
				{
					allotment_frm.parent().parent().find('input[name="name['+input_num+']"]').val(response['name']);
					allotment_frm.parent().parent().parent().find('input[name="officer_id['+input_num+']"]').val(response['id']);
					allotment_frm.parent().parent().parent().find('input[name="field_type['+input_num+']"]').val(response['field_type']);
					allotment_frm.parent().parent().find('a#add_person_link').attr('hidden',"true");
				}
				else
				{
					allotment_frm.parent().parent().find('a#add_person_link').removeAttr('hidden');
					allotment_frm.parent().parent().find('input[name="name['+input_num+']"]').val("");
					allotment_frm.parent().parent().parent().find('input[name="officer_id['+input_num+']"]').val("");
					allotment_frm.parent().parent().parent().find('input[name="field_type['+input_num+']"]').val("");
					allotment_frm.parent().parent().parent().find(".name .help-block").remove();
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
});

function add_member(elem)
{
    jQuery(elem).parent().parent().find('#get_allot_person_name').val("");
    jQuery(elem).attr('hidden',"true");
}

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

function delete_allotment(element)
{
    var tr = jQuery(element).parent().parent().parent();
    var member_share_id = tr.find('input[name="member_share_id[]"]').val();
    var cert_id = tr.find('input[name="cert_id[]"]').val();
    
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
                $('.chairman').find("option:eq(0)").html("Select Chairman");
				$(".chairman option:gt(0)").remove();
	            if (!showCM) 
			    {
			        cm1.getAllChairman();
			    }
			    else
			    {
			        cm1.getChairman();
			    }
            }
        });
    }
    tr.remove();
    toastr.success("Updated Information.", "Updated");

}