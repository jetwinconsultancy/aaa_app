$count_charges = 0;
$(document).on('click',"#charges_Add",function() {
	$count_charges++;
 	$a=""; 

 	/*$a += '<form class="editing" method="post" name="form'+$count_charges+'" id="form'+$count_charges+'">';*/
	$a += '<form class="tr editing" method="post" name="form'+$count_charges+'" id="form'+$count_charges+'">';
	$a += '<div class="hidden"><input type="text" class="form-control" name="company_code" value="'+company_code+'"/></div>';
	$a += '<div class="hidden"><input type="text" class="form-control" name="client_charge_id[]" id="client_charge_id" value=""/></div>';
	$a += '<div class="td"><input type="text" name="charge[]" class="form-control" value="" id="charge"/><div id="form_charge"></div></div>';
	$a += '<div class="td"><textarea class="form-control" name="nature_of_charge[]" id="nature_of_charge" style="width:100%;height:40px;"></textarea><div id="form_nature_of_charge"></div></div>';
	$a += '<div class="td"><div class="input-group mb-md" style="width: 100%;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="date_registration" name="date_registration[]" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value=""></div><div id="form_date_registration"></div><div class="input-group mb-md" style="width: 100%;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="date_satisfied" name="date_satisfied[]" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value=""></div><div id="form_date_satisfied"></div></div>';
	$a += '<div class="td"><div class="input-group mb-md"><input type="text" name="charge_no[]" class="form-control" id="charge_no" value=""/><div id="form_charge_no"></div></div><div class="input-group mb-md"><input type="text" name="satisfactory_no[]" class="form-control" id="satisfactory_no" value=""/><div id="form_satisfactory_no"></div></div></div>';
	$a += '<div class="td"><div class="input-group mb-md"><select class="form-control currency" style="text-align:right;width: 184px;" name="currency[]" id="currency'+$count_charges+'"><option value="0" >Select Currency</option></select><div id="form_currency"></div></div><div class="input-group mb-md"><input type="text" style="text-align: right" name="amount[]" class="numberdes form-control" id="amount" value=""/><div id="form_amount"></div></div></div>';
	$a += '<div class="td"><div class="input-group mb-md"><textarea class="form-control" name="secured_by[]" id="secured_by" style="width:100%;height:40px;"></textarea><div id="form_secured_by"></div></div></div>';
	$a += '<div class="td action"><div style="display: inline-block; margin-right: 5px; margin-bottom: 5px;"><button type="button" class="btn btn-primary submit_charge_button" onclick="edit_charge(this);">Save</button></div><div style="display: inline-block;"><button type="button" class="btn btn-primary" onclick="delete_charge(this);">Delete</button></div></div>';
	//$a += '<div class="td action"><button type="button" class="btn btn-primary" onclick="edit_charge(this);">Save</button></div>';
	$a += '</form>';
	
	/*$a += '<div class="tr">';
	$a += '<div class="td"></div>';
	$a += '<div class="td"></div>';
	$a += '<div class="td"><div class="input-group mb-md" style="width: 150px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="date_satisfied" name="date_satisfied[]" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value=""></div><div id="form_date_satisfied"></div></div>';
	$a += '<div class="td"><input type="text" name="satisfactory_no[]" class="form-control" value=""/><div id="form_satisfactory_no"></div></div>';
	$a += '<div class="td"></div>';
	$a += '<div class="td"><input type="text" name="secured_by[]" class="form-control" value=""/><div id="form_secured_by"></div></div>';
	$a += '<div class="td"></div>';
	$a += '</div>';*/
	/*$a += '</form>'*/
	
	/*<input type="button" value="Save" id="save" name="save'+$count_officer+'" class="btn btn-primary" onclick="save(this);">*/
	$("#body_charges").prepend($a); 
	$('.datepicker').datepicker({ dateFormat:'yyyy-mm=dd'});

	$('#date_registration').datepicker({ 
	    dateFormat:'dd/mm/yyyy',
	}).datepicker('setStartDate', latest_incorporation_date);

	!function ($count_charges) {
		$.ajax({
			type: "GET",
			url: "masterclient/get_currency",
			dataType: "json",
			success: function(data){
	            $("#form"+$count_charges+" #currency"+$count_charges+"").find("option:eq(0)").html("Select Currency");
	            if(data.tp == 1){
	                $.each(data['result'], function(key, val) {
	                    var option = $('<option />');
	                    option.attr('value', key).text(val);
	                    if(key == 1)
	                    {
	                        option.attr('selected', 'selected');
	                        //$("#form"+i+" #alternate_of #select_alternate_of"+i+"").attr('disabled', 'disabled')
	                        /*if (data.selected_director == 166)
	                        {
	                            console.log("selected_director=166");
	                            document.getElementById("nationalityId").disabled = true;
	                        }*/
	                    }
	                    $("#form"+$count_charges+" #currency"+$count_charges+"").append(option);
	                    /*console.log("#form"+$count_charges+" #currency"+$count_charges+"");*/
	                });
	            }
	            else{
	                alert(data.msg);
	            }
			}				
		});
	} ($count_charges);
		
	$("input.number").bind({
		keydown: function(e) {
			if (e.shiftKey === true ) {
				if (e.which == 9) {
					return true;
				}
				return false;
			}
			if (e.which > 57) {
				return false;
			}
			if (e.which==32) {
				return false;
			}
			return true;
		}
	});
});

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

function charge_change_date(latest_incorporation_date)
{
	$('#date_registration').datepicker({ 
	    dateFormat:'dd/mm/yyyy',
	}).datepicker('setStartDate', latest_incorporation_date);

}

if(client_charges)
{
	//console.log("client_charges="+client_charges[0]);
	//console.log(client_officers[0]['name']);
	for(var i = 0; i < client_charges.length; i++)
	{
		$a="";
		$a += '<form class="tr" method="post" name="form'+i+'" id="form'+i+'">';
		$a += '<div class="hidden"><input type="text" class="form-control" name="company_code" value="'+company_code+'"/></div>';
		$a += '<div class="hidden"><input type="text" class="form-control" name="client_charge_id[]" id="client_charge_id" value="'+client_charges[i]["id"]+'"/></div>';
		$a += '<div class="td"><input type="text" name="charge[]" class="form-control" value="'+client_charges[i]["charge"]+'" id="charge" disabled="disabled"/><div id="form_charge"></div></div>';
		$a += '<div class="td"><textarea class="form-control" name="nature_of_charge[]" id="nature_of_charge" style="width:100%;height:40px;" disabled="disabled">'+client_charges[i]["nature_of_charge"]+'</textarea><div id="form_nature_of_charge"></div></div>';
		$a += '<div class="td"><div class="input-group mb-md" style="width: 100%;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="date_registration" name="date_registration[]" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="'+client_charges[i]["date_registration"]+'" disabled="disabled"></div><div id="form_date_registration"></div><div class="input-group mb-md" style="width: 100%;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="date_satisfied" name="date_satisfied[]" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="'+client_charges[i]["date_satisfied"]+'" disabled="disabled"></div><div id="form_date_satisfied"></div></div>';
		$a += '<div class="td"><div class="input-group mb-md"><input type="text" name="charge_no[]" class="form-control" value="'+client_charges[i]["charge_no"]+'" id="charge_no" disabled="disabled"/><div id="form_charge_no"></div></div><div class="input-group mb-md"><input type="text" name="satisfactory_no[]" class="form-control" value="'+client_charges[i]["satisfactory_no"]+'" id="satisfactory_no" disabled="disabled"/><div id="form_satisfactory_no"></div></div></div>';
		$a += '<div class="td"><div class="input-group mb-md"><select class="form-control currency" style="text-align:right;width: 184px;" name="currency[]" id="currency'+i+'" disabled="disabled"><option value="0" >Select Currency</option></select><div id="form_currency"></div></div><div class="input-group mb-md"><input type="text" style="text-align: right" name="amount[]" class="numberdes form-control" value="'+addCommas(client_charges[i]["amount"])+'" id="amount" disabled="disabled"/><div id="form_amount"></div></div></div>';
		$a += '<div class="td"><div class="input-group mb-md"><textarea class="form-control" name="secured_by[]" id="secured_by" style="width:100%;height:40px;" disabled="disabled">'+client_charges[i]["secured_by"]+'</textarea><div id="form_secured_by"></div></div></div>';
		$a += '<div class="td action"><div style="display: inline-block; margin-right: 5px; margin-bottom: 5px;"><button type="button" class="btn btn-primary submit_charge_button" onclick="edit_charge(this);">Edit</button></div><div style="display: inline-block;"><button type="button" class="btn btn-primary" onclick="delete_charge(this);">Delete</button></div></div>';
		//$a += '<div class="td action"><button type="button" class="btn btn-primary" onclick="edit_charge(this);">Edit</button></div>';
		$a += '</form>';
			
			/*<input type="button" value="Save" id="save" name="save'+$count_officer+'" class="btn btn-primary" onclick="save(this);">*/
		$("#body_charges").prepend($a); 

		/*$('#date_registration').datepicker({ 
		    dateFormat:'dd/mm/yyyy',
		}).datepicker('setStartDate', latest_incorporation_date);*/

		!function (i) {
				$.ajax({
					type: "POST",
					url: "masterclient/get_currency",
					data: {"currency": client_charges[i]["currency"]},
					dataType: "json",
					success: function(data){
			            $("#form"+i+" #currency"+i+"").find("option:eq(0)").html("Select Currency");
			            if(data.tp == 1){
			                $.each(data['result'], function(key, val) {
			                    var option = $('<option />');
			                    option.attr('value', key).text(val);
			                    if(data.selected_currency != null && key == data.selected_currency)
			                    {
			                        option.attr('selected', 'selected');
			                        //$("#form"+i+" #alternate_of #select_alternate_of"+i+"").attr('disabled', 'disabled')
			                        /*if (data.selected_director == 166)
			                        {
			                            console.log("selected_director=166");
			                            document.getElementById("nationalityId").disabled = true;
			                        }*/
			                    }

			                    $("#form"+i+" #currency"+i+"").append(option);
			                    /*console.log("#form"+$count_charges+" #currency"+$count_charges+"");*/
			                });
			            }
			            else{
			                alert(data.msg);
			            }
					}				
				});
			} (i);
		}
	}

$("#charge").live('change',function(){
	var charge_frm = $(this);
	//console.log(charge_frm.val());

	if(charge_frm.val() == "")
	{
		charge_frm.parent().parent('form').find('input[name="charge[]"]').val("");
		charge_frm.parent().parent('form').find('textarea[name="nature_of_charge[]"]').val("");
		charge_frm.parent().parent('form').find('input[name="date_registration[]"]').val("");
		charge_frm.parent().parent('form').find('input[name="date_satisfied[]"]').val("");
		charge_frm.parent().parent('form').find('input[name="charge_no[]"]').val("");
		charge_frm.parent().parent('form').find('input[name="satisfactory_no[]"]').val("");
		charge_frm.parent().parent('form').find('input[name="amount[]"]').val("");
		charge_frm.parent().parent('form').find('textarea[name="secured_by[]"]').val("");
		charge_frm.parent().parent('form').find('select[name="currency[]"] option').removeAttr('selected');

	}
	charge_frm.parent().parent('form').find("DIV#form_charge").html( "" );

});

$("#nature_of_charge").live('change',function(){
	var change_nature_of_charge = $(this);
	change_nature_of_charge.parent().parent('form').find("DIV#form_nature_of_charge").html( "" );
});

$("#date_registration").live('change',function(){

	var change_date_registration = $(this);
	change_date_registration.parent().parent().parent('form').find("DIV#form_date_registration").html( "" );

	if($(this).parent().parent().parent('form').find('input[name="charge[]"]').val()!="")
    {
        if($(this).val() == "")
        {
            toastr.error("Date of registration must bigger or equal than date of inforporation.", "Error");
        }
    }
});

$("#date_satisfied").live('change',function(){
	var change_date_satisfied = $(this);
	change_date_satisfied.parent().parent().parent('form').find("DIV#form_date_satisfied").html( "" );
	change_date_satisfied.parent().parent().parent('form').find("DIV#form_satisfactory_no").html( "" );
});

$("#charge_no").live('change',function(){

	var change_charge_no = $(this);
	//console.log(change_charge_no.parent().parent('form').find("DIV#form_charge_no"));
	change_charge_no.parent().parent().parent('form').find("DIV#form_charge_no").html( "" );
});

$("#satisfactory_no").live('change',function(){
	var change_satisfactory_no= $(this);
	change_satisfactory_no.parent().parent().parent('form').find("DIV#form_satisfactory_no").html( "" );
	change_satisfactory_no.parent().parent().parent('form').find("DIV#form_date_satisfied").html( "" );
});


$(".currency").live('change',function(){
	var change_currency = $(this);
	change_currency.parent().parent('form').find("DIV#form_currency").html( "" );
});

$("#amount").live('change',function(){
	var change_amount = $(this);
	change_amount.parent().parent().parent('form').find("DIV#form_amount").html( "" );
});

$("#secured_by").live('change',function(){
	var change_secured_by= $(this);
	change_secured_by.parent().parent().parent('form').find("DIV#form_secured_by").html( "" );
});

function delete_charge(element)
{
	var tr = jQuery(element).parent().parent().parent();

	var client_charge_id = tr.find('input[name="client_charge_id[]"]').val();
	//console.log("client_officer_id==="+client_officer_id);
	if(client_charge_id != undefined)
	{
		$.ajax({ //Upload common input
            url: "masterclient/delete_charge",
            type: "POST",
            data: {"client_charge_id": client_charge_id},
            dataType: 'json',
            success: function (response) {
            	//console.log(response.Status);
            	toastr.success("Information Updated", "Updated");
            }
        });
	}
	tr.remove();
}


function edit_charge(element)
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
				jQuery(this).find("input").attr('disabled', false);
				jQuery(this).find("select").attr('disabled', false);
				jQuery(this).find("textarea").attr('disabled', false);
				
				//jQuery(this).find(".datepicker").datepicker('disable');
				//jQuery(this).text("");
				//jQuery(this).append('<input type="text" value="'+value+'" />');
			} 
			else 
			{
				jQuery(this).find(".submit_charge_button").text("Save");
			}
		});
	} 
	else 
	{
/*			var form_id = $(element).closest('form').attr('id');*/

		//console.log();

		// if(tr.find('input[name="charge[]"]').val()=="" && tr.find('textarea[name="nature_of_charge[]"]').val()=="" && tr.find('input[name="date_registration[]"]').val()=="" && tr.find('input[name="date_satisfied[]"]').val()=="" && tr.find('input[name="charge_no[]"]').val()=="" && tr.find('input[name="satisfactory_no[]"]').val()=="" && tr.find('input[name="amount[]"]').val()=="" && tr.find('textarea[name="secured_by[]"]').val()=="" && tr.find('select[name="currency[]"]').val()=="0")
		// {
		// 	var client_charge_id = tr.find('input[name="client_charge_id[]"]').val();
		// 	//console.log("client_officer_id==="+client_officer_id);
		// 	if(client_charge_id != undefined)
		// 	{
		// 		$.ajax({ //Upload common input
	 //                url: "masterclient/delete_charge",
	 //                type: "POST",
	 //                data: {"client_charge_id": client_charge_id},
	 //                dataType: 'json',
	 //                success: function (response) {
	 //                	//console.log(response.Status);
	 //                	toastr.success("Information Updated", "Updated");
	 //                }
	 //            });
		// 	}
		// 	tr.remove();
		// }
		// else
		// {
			var frm = $(element).closest('form');

			var frm_serialized = frm.serialize();

			$.ajax({
                type: "POST",
                url: "masterclient/check_charge_data",
                data: frm_serialized, // <--- THIS IS THE CHANGE
                dataType: "json",
                async: false,
                success: function(response)
                {
                    if(response)
                    {
                        if (confirm('Do you want to submit?')) 
                        {
                           charge_submit(frm_serialized, tr);
                        } 
                        else 
                        {
                           return false;
                        }
                    }
                    else
                    {
                        charge_submit(frm_serialized, tr);
                    }
                }
            });

			
		//}
	}
}

function charge_submit(frm_serialized, tr)
{
	$('#loadingmessage').show();
	//console.log(frm_serialized);
	$.ajax({ //Upload common input
        url: "masterclient/add_charge",
        type: "POST",
        data: frm_serialized,
        dataType: 'json',
        success: function (response) {
        	$('#loadingmessage').hide();
        	//console.log(response.insert_client_charge_id);
            if (response.Status === 1) {
            	toastr.success(response.message, response.title);
            	/*var errorsDateOfCessation = ' ';
            	tr.find("DIV#form_date_of_cessation").html( errorsDateOfCessation );*/
            	//console.log(tr.find('input[name="client_charge_id"]').val(response.insert_client_charge_id));
            	if(response.insert_client_charge_id != null)
            	{
            		tr.find('input[name="client_charge_id[]"]').attr('value', response.insert_client_charge_id);
            	}
            	tr.removeClass("editing");
				tr.find("DIV.td").each(function(){
					if(!jQuery(this).hasClass("action")){
						
						jQuery(this).find("input").attr('disabled', true);
						jQuery(this).find("select").attr('disabled', true);
						jQuery(this).find("textarea").attr('disabled', true);
						

						
					} else {
						jQuery(this).find(".submit_charge_button").text("Edit");
					}
				});
			    
            }
            else
            {
            	//console.log(tr.find("DIV#form_date_of_cessation"));
				toastr.error(response.message, response.title);
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

            	if (response.error["charge"] != "")
            	{
            		var errorsCharge = '<span class="help-block">' + response.error["charge"] + '</span>';
            		tr.find("DIV#form_charge").html( errorsCharge );

            	}
            	else
            	{
            		var errorsCharge = ' ';
            		tr.find("DIV#form_charge").html( errorsCharge );
            	}

            	if (response.error["nature_of_charge"] != "")
            	{
            		var errorsNatureOfCharge = '<span class="help-block">*' + response.error["nature_of_charge"] + '</span>';
            		tr.find("DIV#form_nature_of_charge").html( errorsNatureOfCharge );

            	}
            	else
            	{
            		var errorsNatureOfCharge = ' ';
            		tr.find("DIV#form_nature_of_charge").html( errorsNatureOfCharge );
            	}

            	if (response.error["date_registration"] != "")
            	{
            		var errorsDateRegistration = '<span class="help-block" style="margin-top: -15px !important">*' + response.error["date_registration"] + '</span>';
            		tr.find("DIV#form_date_registration").html( errorsDateRegistration );

            	}
            	else
            	{
            		var errorsDateRegistration = ' ';
            		tr.find("DIV#form_date_registration").html( errorsDateRegistration );
            	}

            	if (response.error["date_satisfied"] != "")
            	{
            		var errorsDateSatisfied = '<span class="help-block">*' + response.error["date_satisfied"] + '</span>';
            		tr.find("DIV#form_date_satisfied").html( errorsDateSatisfied );

            	}
            	else
            	{
            		var errorsDateSatisfied = ' ';
            		tr.find("DIV#form_date_satisfied").html( errorsDateSatisfied );
            	}

            	if (response.error["charge_no"] != "")
            	{
            		var errorsChargeNo = '<span class="help-block">*' + response.error["charge_no"] + '</span>';
            		tr.find("DIV#form_charge_no").html( errorsChargeNo );

            	}
            	else
            	{
            		var errorsChargeNo = ' ';
            		tr.find("DIV#form_charge_no").html( errorsChargeNo );
            	}

            	if (response.error["satisfactory_no"] != "")
            	{
            		var errorsSatisfactoryNo = '<span class="help-block">*' + response.error["satisfactory_no"] + '</span>';
            		tr.find("DIV#form_satisfactory_no").html( errorsSatisfactoryNo );

            	}
            	else
            	{
            		var errorsSatisfactoryNo = ' ';
            		tr.find("DIV#form_satisfactory_no").html( errorsSatisfactoryNo );
            	}

            	if (response.error["amount"] != "")
            	{
            		var errorsAmount = '<span class="help-block">*' + response.error["amount"] + '</span>';
            		tr.find("DIV#form_amount").html( errorsAmount );

            	}
            	else
            	{
            		var errorsAmount = ' ';
            		tr.find("DIV#form_amount").html( errorsAmount );
            	}

            	/*if (response.error["secured_by"] != "")
            	{
            		var errorsSecuredBy = '<span class="help-block">*' + response.error["secured_by"] + '</span>';
            		tr.find("DIV#form_secured_by").html( errorsSecuredBy );

            	}
            	else
            	{
            		var errorsSecuredBy = ' ';
            		tr.find("DIV#form_secured_by").html( errorsSecuredBy );
            	}*/
            }
        }
    });
}