$count_share_capital = 0;
$(document).on('click',"#share_capital_Add",function() {
	$count_share_capital++;
 	$a=""; 

 	/*$a += '<form class="editing" method="post" name="form'+$count_charges+'" id="form'+$count_charges+'">';*/
	$a += '<form class="tr tr_share_capital editing" method="post" name="form'+$count_share_capital+'" id="form'+$count_share_capital+'">';
	$a += '<div class="hidden"><input type="text" class="form-control" name="company_code" value="'+company_code+'"/></div>';
	$a += '<div class="hidden"><input type="text" class="form-control" name="share_capital_id[]" id="share_capital_id" value=""/></div>';
	$a += '<div class="td"><select class="form-control" style="text-align:right;width: 100%;" name="class[]" id="class'+$count_share_capital+'" onchange="optionCheckClass(this);"><option value="0" >Select Class</option></select><div id="form_class"></div><div id="other_class" hidden><p style="font-weight:bold;">Others: </p><input type="text" name="other_class[]" class="form-control" value="" disabled="true"/><div id="form_other_class"></div></div></div>';
	$a += '<div class="td" style="text-align: right"><span>0</span><input type="hidden" style="text-align: right" name="number_of_shares[]" class="form-control number_of_shares" value="0" readonly/><div id="form_number_of_shares"></div></div>';
	$a += '<div class="td"><select class="form-control" style="text-align:right;width: 150px;" name="currency[]" id="currency'+$count_share_capital+'"><option value="0" >Select Currency</option></select><div id="form_currency"></div></div>';
	$a += '<div class="td" style="text-align: right"><span>0.00</span><input type="hidden" style="text-align: right" name="amount[]" class="form-control amount" value="0" readonly/><div id="form_amount"></div></div>';
	$a += '<div class="td" style="text-align: right"><span>0.00</span><input type="hidden" style="text-align: right" name="paid_up[]" class="form-control paid_up" value="0" readonly/><div id="form_paid_up"></div></div>';
	$a += '<div class="td action"><button type="button" class="btn btn-primary" onclick="edit_share_capital(this);">Save</button></div>';
	$a += '</form>';
	
	$("#body_share_capital").prepend($a); 

	$('.number_of_shares').tooltip({'trigger':'hover', 'title': 'Click the allotment button below to complete the number and amount of shares.'});
	$('.amount').tooltip({'trigger':'hover', 'title': 'Click the allotment button below to complete the number and amount of shares.'});
	$('.paid_up').tooltip({'trigger':'hover', 'title': 'Click the allotment button below to complete the number and amount of shares.'});

	!function ($count_share_capital) {
		$.ajax({
			type: "GET",
			url: "masterclient/get_sharetype",
			dataType: "json",
			success: function(data){
	            $("#form"+$count_share_capital+" #class"+$count_share_capital+"").find("option:eq(0)").html("Select Class");
	            if(data.tp == 1){
	                $.each(data['result'], function(key, val) {
	                    var option = $('<option />');
	                    option.attr('value', key).text(val);

	                    $("#form"+$count_share_capital+" #class"+$count_share_capital+"").append(option);
	                    /*console.log("#form"+$count_charges+" #currency"+$count_charges+"");*/
	                });
	            }
	            else{
	                alert(data.msg);
	            }
			}				
		});
	} ($count_share_capital);

	!function ($count_share_capital) {
		$.ajax({
			type: "GET",
			url: "masterclient/get_currency",
			dataType: "json",
			success: function(data){
	            $("#form"+$count_share_capital+" #currency"+$count_share_capital+"").find("option:eq(0)").html("Select Currency");
	            if(data.tp == 1){
	                $.each(data['result'], function(key, val) {
	                    var option = $('<option />');
	                    option.attr('value', key).text(val);
	                    if(key == 92)
	                    {
	                        option.attr('selected', 'selected');
	                    }

	                    $("#form"+$count_share_capital+" #currency"+$count_share_capital+"").append(option);
	                    /*console.log("#form"+$count_charges+" #currency"+$count_charges+"");*/
	                });
	            }
	            else{
	                alert(data.msg);
	            }
			}				
		});
	} ($count_share_capital);
		
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

$("#refresh").click(function(){
	//console.log("ininin");
	refresh();
});

function refresh()
{
	$(".member_tr_1").remove();
	$(".member_tr_2").remove();
	$(".member_certificate_tr_1").remove();
	//$(".share_capital_tr").remove();

	$.ajax({
		type: "POST",
		url: "masterclient/refresh_member",
		data: {"company_code": company_code},
		asycn: false,
		dataType: "json",
		success: function(response){
			console.log(response.info["member"]);
			console.log(response.info["member_certificate"]);

			for(var i = 0; i < response.info["member"].length; i++)
			{
			 	$v="";
			 	$v += '<tr class="member_tr_1">'; 
			 	$v += '<td rowspan=2>'+(i+1)+'</td>'; 
			 	$v += '<td>';
			 	$v += ''+((response.info["member"][i]["identification_no"] != null)?response.info["member"][i]["identification_no"]:((response.info["member"][i]["register_no"] != null)?response.info["member"][i]["register_no"]:response.info["member"][i]["registration_no"]))+'';
			 	$v += '</td>';
			 	$v += '<td>'+response.info["member"][i]["sharetype"]+ ((response.info["member"][i]["sharetype"] == "Others")?"( "+response.info["member"][i]["other_class"]+" )":" ")+'</td>';
			 	$v += '<td style="text-align: right">'+addCommas(response.info["member"][i]["number_of_share"])+'</td>';
			 	$v += '<td style="text-align: right">'+addCommas(response.info["member"][i]["no_of_share_paid"])+'</td>';
			 	$v += '</tr>';
			 	$v += '<tr class="member_tr_2">';
			 	$v += '<td>'+((response.info["member"][i]["name"] != null)?response.info["member"][i]["name"]:((response.info["member"][i]["company_name"] != null)?response.info["member"][i]["company_name"]:response.info["member"][i]["client_company_name"]))+'</td>';
			 	$v += '<td>'+response.info["member"][i]["currency"]+'</td>';
			 	$v += '<td style="text-align: right">'+addCommas(response.info["member"][i]["amount_share"])+'</td>';
			 	$v += '<td style="text-align: right">'+addCommas(response.info["member"][i]["amount_paid"])+'</td>';
			 	$v += '</tr>'

			 	$("#body_members_capital").append($v); 
			}

			for(var y = 0; y < response.info["member_certificate"].length; y++)
			{
			 	$j="";
			 	$j += '<tr class="member_certificate_tr_1">'; 
			 	$j += '<td>'+(y+1)+'</td>'; 
			 	$j += '<td>';
			 	$j += ''+((response.info["member_certificate"][y]["identification_no"] != null)?response.info["member_certificate"][y]["identification_no"]:((response.info["member_certificate"][y]["register_no"] != null)?response.info["member_certificate"][y]["register_no"]:response.info["member_certificate"][y]["registration_no"]))+'';
			 	$j += '</td>';
			 	$j += '<td>'+((response.info["member_certificate"][y]["name"] != null)?response.info["member_certificate"][y]["name"]:((response.info["member_certificate"][y]["company_name"] != null)?response.info["member_certificate"][y]["company_name"]:response.info["member_certificate"][y]["client_company_name"]))+'</td>';
			 	$j += '<td>'+response.info["member_certificate"][y]["sharetype"]+ ((response.info["member_certificate"][y]["sharetype"] == "Others")?"( "+response.info["member_certificate"][y]["other_class"]+" )":" ")+"( "+response.info["member_certificate"][y]["currency"]+" )"+'</td>';
			 	$j += '<td>'+((response.info["member_certificate"][y]["certificate_no"] != null)?response.info["member_certificate"][y]["certificate_no"]:response.info["member_certificate"][y]["new_certificate_no"])+'</td>';
			 	$j += '<td style="text-align: right">'+addCommas(response.info["member_certificate"][y]["number_of_share"])+'</td>';
			 	$j += '</tr>'

			 	$("#body_members_certificate").append($j); 
			}

			share_capital(response.info["client_share_capital"]);
		}				
	});
}

if(client_share_capital)
{
	share_capital(client_share_capital);
}

function share_capital(client_share_capital)
{
	//console.log("client_share_capital="+client_share_capital[0]);
	//console.log(client_officers[0]['name']);
	$(".tr_share_capital").remove();
	for(var i = 0; i < client_share_capital.length; i++)
	{
	 	$a=""; 

	 	/*$a += '<form class="editing" method="post" name="form'+$count_charges+'" id="form'+$count_charges+'">';*/
		$a += '<form class="tr tr_share_capital share_capital_tr" method="post" name="form'+i+'" id="form'+i+'">';
		$a += '<div class="hidden"><input type="text" class="form-control" name="company_code" value="'+company_code+'"/></div>';
		$a += '<div class="hidden"><input type="text" class="form-control" name="share_capital_id[]" id="share_capital_id" value="'+client_share_capital[i]["id"]+'"/></div>';
		$a += '<div class="td"><select class="form-control" style="text-align:right;width: 100%;" name="class[]" id="class'+i+'" onchange="optionCheckClass(this);" disabled="disabled"><option value="0" >Select Class</option></select><div id="form_class"></div><div id="other_class" hidden><p style="font-weight:bold;">Others: </p><input type="text" name="other_class[]" class="form-control" value="'+client_share_capital[i]["other_class"]+'" disabled="disabled"/><div id="form_other_class"></div></div></div>';
		$a += '<div class="td" style="text-align: right"><span>'+((client_share_capital[i]["number_of_shares"] != null)?addCommas(client_share_capital[i]["number_of_shares"]):0)+'</span><input type="hidden" style="text-align: right" name="number_of_shares[]" class="form-control number_of_shares" value="'+((client_share_capital[i]["number_of_shares"] != null)?addCommas(client_share_capital[i]["number_of_shares"]):0)+'" readonly/><div id="form_number_of_shares"></div></div>';
		$a += '<div class="td"><select class="form-control" style="text-align:right;width: 150px;" name="currency[]" id="currency'+i+'" disabled="disabled"><option value="0" >Select Currency</option></select><div id="form_currency"></div></div>';
		$a += '<div class="td" style="text-align: right"><span>'+((client_share_capital[i]["amount"] != null)?addCommas(client_share_capital[i]["amount"]):0.00)+'</span><input type="hidden" style="text-align: right" name="amount[]" class="form-control amount" value="'+((client_share_capital[i]["amount"] != null)?addCommas(client_share_capital[i]["amount"]):0)+'" readonly/><div id="form_amount"></div></div>';
		$a += '<div class="td" style="text-align: right"><span>'+((client_share_capital[i]["paid_up"] != null)?addCommas(client_share_capital[i]["paid_up"]):0.00)+'</span><input type="hidden" style="text-align: right" name="paid_up[]" class="form-control paid_up" value="'+((client_share_capital[i]["paid_up"] != null)?addCommas(client_share_capital[i]["paid_up"]):0)+'" readonly/><div id="form_paid_up"></div></div>';
		$a += '<div class="td action"><button type="button" class="btn btn-primary" onclick="edit_share_capital(this);">Edit</button></div>';
		$a += '</form>';
		
		$("#body_share_capital").prepend($a); 

		$(document).ready(function(){
			$('.number_of_shares').tooltip({'trigger':'hover', 'title': 'Click the allotment button below to complete the number and amount of shares.'});
			$('.amount').tooltip({'trigger':'hover', 'title': 'Click the allotment button below to complete the number and amount of shares.'});
			$('.paid_up').tooltip({'trigger':'hover', 'title': 'Click the allotment button below to complete the number and amount of shares.'});
		});
		if(client_share_capital[i]["class_id"] == "2")
		{
			//console.log("i==="+i);
			$("#form"+i+" #other_class").removeAttr('hidden');
		}

		!function (i) {
			$.ajax({
				type: "POST",
				url: "masterclient/get_sharetype",
				data: {"class": client_share_capital[i]["class_id"]},
				dataType: "json",
				success: function(data){
		            $("#form"+i+" #class"+i+"").find("option:eq(0)").html("Select Class");
		            if(data.tp == 1){
		                $.each(data['result'], function(key, val) {
		                    var option = $('<option />');
		                    option.attr('value', key).text(val);
		                    if(data.selected_class != null && key == data.selected_class)
		                    {
		                        option.attr('selected', 'selected');
		                        //$("#form"+i+" #alternate_of #select_alternate_of"+i+"").attr('disabled', 'disabled')
		                        /*if (data.selected_director == 166)
		                        {
		                            console.log("selected_director=166");
		                            document.getElementById("nationalityId").disabled = true;
		                        }*/
		                    }

		                    $("#form"+i+" #class"+i+"").append(option);
		                    /*console.log("#form"+$count_charges+" #currency"+$count_charges+"");*/
		                });
		            }
		            else{
		                alert(data.msg);
		            }
				}				
			});
		} (i);

		!function (i) {
			$.ajax({
				type: "POST",
				url: "masterclient/get_currency",
				data: {"currency": client_share_capital[i]["currency_id"]},
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

function optionCheckClass(share_capital_element) 
{	

	var tr = jQuery(share_capital_element).parent().parent();
	//console.log(tr.find('select[name="class[]"]').val());
	//tr.find("DIV.td").each(function(){
		tr.find('input[name="other_class[]"]').val('');
		tr.find('select[name="currency[]"] option').removeAttr('selected');
	//});
	//tr.find("DIV.td").each(function(){
		//jQuery(this).find("input").val('');
	if(tr.find('select[name="class[]"]').val() == "2")
	{
		
		tr.find("DIV#other_class").removeAttr('hidden');
		tr.find('input[name="other_class[]"]').removeAttr('disabled');

		/*$.ajax({
			type: "POST",
			url: "masterclient/get_director",
			data: {"company_code": tr.find('input[name="company_code"]').val()}, // <--- THIS IS THE CHANGE
			dataType: "json",
			success: function(data){
				console.log(data);
	            
	            console.log(data);
	            if(data.tp == 1){
	            	tr.find('select[name="alternate_of[]"]').html(""); 
	            	tr.find('select[name="alternate_of[]"]').append($('<option>', {
					    value: '0',
					    text: 'Select Director'
					}));
	            	
	            	//option.attr('value', '').text("Select Director");
	            	//tr.find('select[name="alternate_of[]"]').html("Select Director");
	            	//option.attr('value', '0').text("Select Director");
	                $.each(data['result'], function(key, val) {
	                    var option = $('<option />');
	                    option.attr('value', key).text(val);
	                    if(data.selected_director != null && key == data.selected_director)
	                    {
	                        option.attr('selected', 'selected');
	                    }
	                    
	                    tr.find('select[name="alternate_of[]"]').append(option);
	                });
	                
	                //$(".nationality").prop("disabled",false);
	            }
	            else{
	                alert(data.msg);
	            }

				
			}				
		});*/
	}
	else
	{
		tr.find("DIV#other_class").attr("hidden","true");
		tr.find('input[name="other_class[]"]').val("");
		tr.find('input[name="other_class[]"]').attr('disabled', 'true');
	}
}

function edit_share_capital(element)
{
	 //element.preventDefault();
	var tr = jQuery(element).parent().parent();
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

				/*jQuery(this).find('input[name="identification_register_no[]"]').attr('disabled', false);
				jQuery(this).find('input[name="date_of_appointment[]"]').attr('disabled', false);*/
				//console.log("hidden====="+jQuery(this).find("DIV#other_class").css('display'));
				if(jQuery(this).find("DIV#other_class").css('display') != "none")
				{
					jQuery(this).find('input[name="other_class[]"]').attr('disabled', false);
				}
				
				jQuery(this).find("select").attr('disabled', false);
				
				//jQuery(this).find(".datepicker").datepicker('disable');
				//jQuery(this).text("");
				//jQuery(this).append('<input type="text" value="'+value+'" />');
			} 
			else 
			{
				jQuery(this).find("BUTTON").text("Save");
			}
		});
	} 
	else 
	{
/*			var form_id = $(element).closest('form').attr('id');*/

		//console.log(tr.find('input[name="name[]"]').val()=="");

		if(tr.find('select[name="class[]"]').val()=="0" && tr.find('input[name="other_class[]"]').val()=="" && tr.find('input[name="number_of_shares[]"]').val()=="0" && tr.find('select[name="currency[]"]').val()=="0" && tr.find('input[name="amount[]"]').val()=="0" && tr.find('input[name="paid_up[]"]').val()=="0")
		{
			var share_capital_id = tr.find('input[name="share_capital_id[]"]').val();
			//console.log("share_capital_id==="+share_capital_id);
			if(share_capital_id != undefined)
			{
				$.ajax({ //Upload common input
	                url: "masterclient/delete_share_capital",
	                type: "POST",
	                data: {"share_capital_id": share_capital_id},
	                dataType: 'json',
	                success: function (response) {
	                	//console.log(response.Status);

	                	toastr.success(response.message, response.title);
	                }
	            });
			}
			tr.remove();
			refresh();
		}
		else
		{
			var frm = $(element).closest('form');

			var frm_serialized = frm.serialize();
			$('#loadingmessage').show();
			//console.log(frm_serialized);
			$.ajax({ //Upload common input
                url: "masterclient/add_share_capital",
                type: "POST",
                data: frm_serialized,
                dataType: 'json',
                success: function (response) {
                	$('#loadingmessage').hide();
                	//console.log(response.Status);
                    if (response.Status === 1) {
						toastr.success(response.message, response.title);
                    	var errors = ' ';
                    	tr.find("DIV#form_class").html( errors );
                    	tr.find("DIV#form_other_class").html( errors );
                    	tr.find("DIV#form_currency").html( errors );
                    	if(response.insert_share_capital_id != null)
                    	{
                    		tr.find('input[name="share_capital_id[]"]').attr('value', response.insert_share_capital_id);
                    	}
                    	tr.removeClass("editing");
						tr.find("DIV.td").each(function(){
							if(!jQuery(this).hasClass("action")){
								
								jQuery(this).find('input[name="other_class[]"]').attr('disabled', true);
								jQuery(this).find("select").attr('disabled', true);

								

								
							} else {
								jQuery(this).find("BUTTON").text("Edit");
							}
						});
					    
                    }
                    else
                    {
                    	//console.log(tr.find("DIV#form_date_of_cessation"));
						toastr.error(response.message, response.title);
                    	if (response.error["class"] != "")
                    	{
                    		var errorsClass = '<span class="help-block">*' + response.error["class"] + '</span>';
                    		tr.find("DIV#form_class").html( errorsClass );

                    	}
                    	else
                    	{
                    		var errorsClass = ' ';
                    		tr.find("DIV#form_class").html( errorsClass );
                    	}

                    	if (response.error["currency"] != "")
                    	{
                    		var errorsCurrency = '<span class="help-block">' + response.error["currency"] + '</span>';
                    		tr.find("DIV#form_currency").html( errorsCurrency );

                    	}
                    	else
                    	{
                    		var errorsCurrency = ' ';
                    		tr.find("DIV#form_currency").html( errorsCurrency );
                    	}

                    	if (response.error["other_class"] != "")
                    	{
                    		var errorsOtherClass = '<span class="help-block">*' + response.error["other_class"] + '</span>';
                    		tr.find("DIV#form_other_class").html( errorsOtherClass );

                    	}
                    	else
                    	{
                    		var errorsOtherClass = ' ';
                    		tr.find("DIV#form_other_class").html( errorsOtherClass );
                    	}
                    }
                }
            });
		}
	}
}