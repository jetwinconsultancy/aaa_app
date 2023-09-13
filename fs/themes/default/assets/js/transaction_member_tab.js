var allotment_info_coll;
// $count_share_capital = 0;
// $(document).on('click',"#share_capital_Add",function() {
// 	$count_share_capital++;
//  	$a=""; 

//  	/*$a += '<form class="editing" method="post" name="form'+$count_charges+'" id="form'+$count_charges+'">';*/
// 	$a += '<form class="tr tr_share_capital editing" method="post" name="form'+$count_share_capital+'" id="form'+$count_share_capital+'">';
// 	$a += '<div class="hidden"><input type="text" class="form-control" name="company_code" value="'+transaction_company_code+'"/></div>';
// 	$a += '<div class="hidden"><input type="text" class="form-control" name="share_capital_id[]" id="share_capital_id" value=""/></div>';
// 	$a += '<div class="td"><select class="form-control" style="text-align:right;width: 100%;" name="class[]" id="class'+$count_share_capital+'" onchange="optionCheckClass(this);"><option value="0" >Select Class</option></select><div id="form_class"></div><div id="other_class" hidden><p style="font-weight:bold;">Others: </p><input type="text" name="other_class[]" class="form-control" value="" disabled="true"/><div id="form_other_class"></div></div></div>';
// 	$a += '<div class="td" style="text-align: right"><span>0</span><input type="hidden" style="text-align: right" name="number_of_shares[]" class="form-control number_of_shares" value="0" readonly/><div id="form_number_of_shares"></div></div>';
// 	$a += '<div class="td"><select class="form-control" style="text-align:right;width: 150px;" name="currency[]" id="currency'+$count_share_capital+'"><option value="0" >Select Currency</option></select><div id="form_currency"></div></div>';
// 	$a += '<div class="td" style="text-align: right"><span>0.00</span><input type="hidden" style="text-align: right" name="amount[]" class="form-control amount" value="0" readonly/><div id="form_amount"></div></div>';
// 	$a += '<div class="td" style="text-align: right"><span>0.00</span><input type="hidden" style="text-align: right" name="paid_up[]" class="form-control paid_up" value="0" readonly/><div id="form_paid_up"></div></div>';
// 	$a += '<div class="td action"><button type="button" class="btn btn-primary" onclick="edit_share_capital(this);">Save</button></div>';
// 	$a += '</form>';
	
// 	$("#body_share_capital").prepend($a); 

// 	!function ($count_share_capital) {
// 		$.ajax({
// 			type: "GET",
// 			url: "masterclient/get_sharetype",
// 			dataType: "json",
// 			success: function(data){
// 	            $("#form"+$count_share_capital+" #class"+$count_share_capital+"").find("option:eq(0)").html("Select Class");
// 	            if(data.tp == 1){
// 	                $.each(data['result'], function(key, val) {
// 	                    var option = $('<option />');
// 	                    option.attr('value', key).text(val);

// 	                    $("#form"+$count_share_capital+" #class"+$count_share_capital+"").append(option);
// 	                    /*console.log("#form"+$count_charges+" #currency"+$count_charges+"");*/
// 	                });
// 	            }
// 	            else{
// 	                alert(data.msg);
// 	            }
// 			}				
// 		});
// 	} ($count_share_capital);

// 	!function ($count_share_capital) {
// 		$.ajax({
// 			type: "GET",
// 			url: "masterclient/get_currency",
// 			dataType: "json",
// 			success: function(data){
// 	            $("#form"+$count_share_capital+" #currency"+$count_share_capital+"").find("option:eq(0)").html("Select Currency");
// 	            if(data.tp == 1){
// 	                $.each(data['result'], function(key, val) {
// 	                    var option = $('<option />');
// 	                    option.attr('value', key).text(val);
// 	                    if(key == 92)
// 	                    {
// 	                        option.attr('selected', 'selected');
// 	                    }

// 	                    $("#form"+$count_share_capital+" #currency"+$count_share_capital+"").append(option);
// 	                    /*console.log("#form"+$count_charges+" #currency"+$count_charges+"");*/
// 	                });
// 	            }
// 	            else{
// 	                alert(data.msg);
// 	            }
// 			}				
// 		});
// 	} ($count_share_capital);
// });

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

// function edit_share_capital(element)
// {
// 	 //element.preventDefault();
// 	var tr = jQuery(element).parent().parent();
// 	if(!tr.hasClass("editing")) 
// 	{
// 		tr.addClass("editing");
// 		tr.find("DIV.td").each(function()
// 		{
// 			if(!jQuery(this).hasClass("action"))
// 			{

// 				if(jQuery(this).find("DIV#other_class").css('display') != "none")
// 				{
// 					jQuery(this).find('input[name="other_class[]"]').attr('disabled', false);
// 				}
				
// 				jQuery(this).find("select").attr('disabled', false);
				
// 			} 
// 			else 
// 			{
// 				jQuery(this).find("BUTTON").text("Save");
// 			}
// 		});
// 	} 
// 	else 
// 	{

// 		if(tr.find('select[name="class[]"]').val()=="0" && tr.find('input[name="other_class[]"]').val()=="" && tr.find('input[name="number_of_shares[]"]').val()=="0" && tr.find('select[name="currency[]"]').val()=="0" && tr.find('input[name="amount[]"]').val()=="0" && tr.find('input[name="paid_up[]"]').val()=="0")
// 		{
// 			var share_capital_id = tr.find('input[name="share_capital_id[]"]').val();
// 			//console.log("share_capital_id==="+share_capital_id);
// 			if(share_capital_id != undefined)
// 			{
// 				$.ajax({ //Upload common input
// 	                url: "masterclient/delete_share_capital",
// 	                type: "POST",
// 	                data: {"share_capital_id": share_capital_id},
// 	                dataType: 'json',
// 	                success: function (response) {
// 	                	//console.log(response.Status);

// 	                	toastr.success(response.message, response.title);
// 	                }
// 	            });
// 			}
// 			tr.remove();
// 			refresh();
// 		}
// 		else
// 		{
// 			var frm = $(element).closest('form');

// 			var frm_serialized = frm.serialize();
// 			$('#loadingmessage').show();
// 			//console.log(frm_serialized);
// 			$.ajax({ //Upload common input
//                 url: "transaction/add_share_capital",
//                 type: "POST",
//                 data: frm_serialized,
//                 dataType: 'json',
//                 success: function (response) {
//                 	$('#loadingmessage').hide();
//                 	//console.log(response.Status);
//                     if (response.Status === 1) {
// 						toastr.success(response.message, response.title);
//                     	var errors = ' ';
//                     	tr.find("DIV#form_class").html( errors );
//                     	tr.find("DIV#form_other_class").html( errors );
//                     	tr.find("DIV#form_currency").html( errors );
//                     	if(response.insert_share_capital_id != null)
//                     	{
//                     		tr.find('input[name="share_capital_id[]"]').attr('value', response.insert_share_capital_id);
//                     	}
//                     	tr.removeClass("editing");
// 						tr.find("DIV.td").each(function(){
// 							if(!jQuery(this).hasClass("action")){
								
// 								jQuery(this).find('input[name="other_class[]"]').attr('disabled', true);
// 								jQuery(this).find("select").attr('disabled', true);

								

								
// 							} else {
// 								jQuery(this).find("BUTTON").text("Edit");
// 							}
// 						});
					    
//                     }
//                     else
//                     {
//                     	//console.log(tr.find("DIV#form_date_of_cessation"));
// 						toastr.error(response.message, response.title);
//                     	if (response.error["class"] != "")
//                     	{
//                     		var errorsClass = '<span class="help-block">*' + response.error["class"] + '</span>';
//                     		tr.find("DIV#form_class").html( errorsClass );

//                     	}
//                     	else
//                     	{
//                     		var errorsClass = ' ';
//                     		tr.find("DIV#form_class").html( errorsClass );
//                     	}

//                     	if (response.error["currency"] != "")
//                     	{
//                     		var errorsCurrency = '<span class="help-block">' + response.error["currency"] + '</span>';
//                     		tr.find("DIV#form_currency").html( errorsCurrency );

//                     	}
//                     	else
//                     	{
//                     		var errorsCurrency = ' ';
//                     		tr.find("DIV#form_currency").html( errorsCurrency );
//                     	}

//                     	if (response.error["other_class"] != "")
//                     	{
//                     		var errorsOtherClass = '<span class="help-block">*' + response.error["other_class"] + '</span>';
//                     		tr.find("DIV#form_other_class").html( errorsOtherClass );

//                     	}
//                     	else
//                     	{
//                     		var errorsOtherClass = ' ';
//                     		tr.find("DIV#form_other_class").html( errorsOtherClass );
//                     	}
//                     }
//                 }
//             });
// 		}
// 	}
// }

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

 
	// $a += '<div class="tr editing" method="post" name="form'+$count_allotment+'" id="form'+$count_allotment+'" num="'+$field_index+'">';
	// $a += '<div class="hidden"><input type="text" class="form-control" name="company_code" value="'+transaction_company_code+'"/></div>';
	// $a += '<div class="hidden"><input type="text" class="form-control cert_id" name="cert_id[]" id="cert_id" value=""/></div>';
	// $a += '<div class="hidden"><input type="text" class="form-control" name="member_share_id[]" id="member_share_id" value=""/></div>';
	// $a += '<div class="hidden"><input type="text" class="form-control" name="officer_id['+$field_index+']" id="officer_id" value=""/></div>';
	// $a += '<div class="hidden"><input type="text" class="form-control" name="field_type['+$field_index+']" id="field_type" value=""/></div>';
	// $a += '<div class="hidden"><input type="text" class="form-control" name="previous_new_cert['+$field_index+']" id="previous_new_cert" value=""/></div>';
	// $a += '<div class="hidden"><input type="text" class="form-control" name="previous_cert['+$field_index+']" id="previous_cert" value=""/></div>';

	// $a += '<div class="td"><div><input type="text" name="id['+$field_index+']" class="form-control id" value="" id="get_person_name" style="text-transform:uppercase;"/></div><div style="height:16px;" ><a class="" href="'+ url + 'personprofile/add" style="cursor:pointer;" id="add_person_link" target="_blank" hidden><div style="cursor:pointer;">Click here to <span style="font-weight:bold">Add Entity</span></div></a></div><div class="input-group mb-md name"><input type="text" name="name['+$field_index+']" class="form-control" value="" tabindex="-1" readonly/><div id="form_name"></div></div></div>';
	// $a += '<div class="td"><div class="mb-md"><select class="form-control" style="text-align:right;width: 100%;" name="class[]" id="class'+$field_index+'" onchange="optionCheckClass(this);"><option value="0" >Select Class</option></select><div id="form_class"></div><div id="other_class" hidden><p style="font-weight:bold;">Others: </p><input type="text" name="other_class[]" class="form-control" value="" disabled="true"/><div id="form_other_class"></div></div></div><div class="input-group mb-md"><select class="form-control" style="text-align:right;width: 200px;" name="currency[]" id="currency'+$field_index+'"><option value="0" >Select Currency</option></select><div id="form_currency"></div></div></div>';
	// $a += '<div class="td"><div class="input-group mb-md"><input type="text" name="number_of_share['+$field_index+']" class="numberdes form-control number_of_share" value="" id="number_of_share" style="text-align:right;" pattern="^[0-9,]+$"/><div id="form_number_of_share"></div></div><div class="input-group mb-md"><input type="text" name="amount_share['+$field_index+']" id="amount_share" class="numberdes form-control amount_share" value="" style="text-align:right;" pattern="[0-9.,]"/><div id="form_amount_share"></div></div></div>';
	// $a += '<div class="td"><div class="input-group mb-md"><input type="text" tabindex="-1" name="no_of_share_paid['+$field_index+']" class="numberdes form-control no_of_share_paid" value="" id="no_of_share_paid" style="text-align:right;" readonly/><div id="form_no_of_share_paid"></div></div><div class="input-group mb-md"><input type="text" name="amount_paid['+$field_index+']" id="amount_paid" class="numberdes form-control amount_paid" value="" style="text-align:right;" pattern="[0-9.,]"/><div id="form_amount_paid"></div></div></div>';
	// $a += '<div class="td action"><button type="button" class="btn btn-primary delete_allotment_button" onclick="delete_allotment(this)" style="display: block;">Delete</button></div>';
	// $a += '</div>';

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

$(document).on('click',"#submitMemberInfo",function(e){
    $('#loadingmessage').show();
    $.ajax({ //Upload common input
      url: "transaction/save_allotment",
      type: "POST",
      data: $('form#member_form').serialize()+ '&transaction_code=' + $('#transaction_code').val() + '&transaction_task_id=' + $('#transaction_task').val(),
      dataType: 'json',
      success: function (response,data) {
        $('#loadingmessage').hide();

          if (response.Status === 1) 
          {
            toastr.success(response.message, response.title);
            $("#allotment_add .row_allotment").remove();
            memberInterface(response.transaction_member);
            //console.log(showCM);
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
	console.log(allotment_frm.val());
	//console.log($(this).parent().parent().parent().attr("num"));
	var input_num = allotment_frm.parent().parent().parent().attr("num");
	$("#loadingmessage").show();
	$.ajax({
		type: "POST",
		url: "transaction/get_transaction_person",
		data: {"identification_register_no":allotment_frm.val(), "company_code": $(".transaction_company_code").val()}, // <--- THIS IS THE CHANGE
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