var pathArray = location.href.split( '/' );
var protocol = pathArray[0];
var host = pathArray[2];
var folder = pathArray[3];
var url = protocol + '//' + host + '/' + folder + '/';
var asc = true, name_asc = true, date_of_birth_asc = true, nationality_asc = true, address_asc = true, date_of_registration_asc = true, date_of_cessation_asc = true;

$("#gid_add_guarantee_officer").live('change',function(){
	$(this).parent().parent('form').find("DIV#form_identification_register_no").html( "" );
});
$("#name").live('change',function(){
	$(this).parent().parent('form').find("DIV#form_name").html( "" );
});
$("#guarantee").live('change',function(){
	$(this).parent().parent('form').find("DIV#form_guarantee").html( "" );
});
$("#guarantee_start_date").live('change',function(){
	$(this).parent().parent().parent('form').find("DIV#form_guarantee_start_date").html( "" );
});
$("#guarantee_end_date").live('change',function(){
	$(this).parent().parent().parent('form').find("DIV#form_guarantee_end_date").html( "" );
});

function delete_guarantee(element)
{
    var tr = jQuery(element).parent().parent().parent();

    var client_guarantee_id = tr.find('input[name="client_guarantee_id[]"]').val();
    //console.log("client_officer_id==="+client_officer_id);
    if(client_guarantee_id != undefined)
    {
        $.ajax({ //Upload common input
            url: "masterclient/delete_guarantee",
            type: "POST",
            data: {"client_guarantee_id": client_guarantee_id},
            dataType: 'json',
            success: function (response) {
                //console.log(response.Status);
            }
        });
    }
    tr.remove();
}

function edit_guarantee(element)
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

				jQuery(this).find('input[name="guarantee[]"]').attr('disabled', false);
				jQuery(this).find('input[name="guarantee_start_date[]"]').attr('disabled', false);
				jQuery(this).find('input[name="guarantee_end_date[]"]').attr('disabled', false);
				jQuery(this).find("select").attr('disabled', false);
				
				//jQuery(this).find(".datepicker").datepicker('disable');
				//jQuery(this).text("");
				//jQuery(this).append('<input type="text" value="'+value+'" />');
			} 
			else 
			{
				jQuery(this).find(".submit_guarantee_button").text("Save");
			}
		});
	} 
	else 
	{
/*			var form_id = $(element).closest('form').attr('id');*/

		//console.log(tr.find('input[name="name[]"]').val()=="");

		// if(tr.find('input[name="identification_register_no[]"]').val()=="" && tr.find('input[name="name[]"]').val()=="" && tr.find('select[name="currency[]"]').val()=="0" && tr.find('input[name="guarantee[]"]').val()=="" && tr.find('input[name="guarantee_start_date[]"]').val()=="")
		// {
		// 	//console.log(jQuery(this).find('input[name="client_officer_id[]"]'));
		// 	var client_guarantee_id = tr.find('input[name="client_guarantee_id[]"]').val();
		// 	//console.log("client_officer_id==="+client_officer_id);
		// 	if(client_guarantee_id != undefined)
		// 	{
		// 		$.ajax({ //Upload common input
	 //                url: "masterclient/delete_guarantee",
	 //                type: "POST",
	 //                data: {"client_guarantee_id": client_guarantee_id},
	 //                dataType: 'json',
	 //                success: function (response) {
	 //                	//console.log(response.Status);
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
                url: "masterclient/check_guarantee_data",
                data: frm_serialized, // <--- THIS IS THE CHANGE
                dataType: "json",
                async: false,
                success: function(response)
                {
                    if(response)
                    {
                        if (confirm('Do you want to submit?')) 
                        {
                           guarantee_submit(frm_serialized, tr);
                        } 
                        else 
                        {
                           return false;
                        }
                    }
                    else
                    {
                        guarantee_submit(frm_serialized, tr);
                    }
                }
            });

			//console.log(frm_serialized);
			
		//}
	}
}

function guarantee_submit(frm_serialized, tr)
{
    $('#loadingmessage').show();
    $.ajax({ //Upload common input
        url: "masterclient/add_guarantee",
        type: "POST",
        data: frm_serialized,
        dataType: 'json',
        async: false,
        success: function (response) {
            $('#loadingmessage').hide();
            //console.log(response.Status);
            if (response.Status === 1) {
                //var errorsDateOfCessation = ' ';
                toastr.success(response.message, response.title);
                //tr.find("DIV#form_date_of_cessation").html(" ");
                if(response.insert_client_guarantee_id != null)
                {
                   //console.log(tr.find('.client_guarantee_id'));
                    var guarantee_id = response.insert_client_guarantee_id;
                    //console.log(response.insert_client_guarantee_id);
                    tr.find('input[name="client_guarantee_id[]"]').val(response.insert_client_guarantee_id);
                }
                tr.removeClass("editing");

                tr.attr("data-currency",tr.find('.currency option:selected').text());
                tr.attr("data-registe_no",tr.find('input[name="identification_register_no[]"]').val());
                tr.attr("data-name",tr.find('input[name="name[]"]').val());
                tr.attr("data-guarantee",tr.find('input[name="guarantee[]"]').val());
                tr.attr("data-guarantee_start_date",tr.find('input[name="guarantee_start_date[]"]').val());
                
                /*tr.data('registe_no',tr.find('input[name="identification_register_no[]"]').val());
                tr.data('name',tr.find('input[name="identification_register_no[]"]').val());*/
                tr.find("DIV.td").each(function(){
                    if(!jQuery(this).hasClass("action")){

                        jQuery(this).find('input[name="identification_register_no[]"]').attr('disabled', true);
                        jQuery(this).find('input[name="guarantee[]"]').attr('disabled', true);
                        jQuery(this).find('input[name="guarantee_start_date[]"]').attr('disabled', true);
                        jQuery(this).find('input[name="guarantee_end_date[]"]').attr('disabled', true);
                        jQuery(this).find("select").attr('disabled', true);

                        

                        
                    } else {
                        jQuery(this).find(".submit_guarantee_button").text("Edit");
                    }
                });
                
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
                if (response.error["guarantee_start_date"] != "")
                {
                    var errorsGuaranteeStartDate = '<span class="help-block">*' + response.error["guarantee_start_date"] + '</span>';
                    tr.find("DIV#form_guarantee_start_date").html( errorsGuaranteeStartDate );

                }
                else
                {
                    var errorsGuaranteeStartDate = ' ';
                    tr.find("DIV#form_guarantee_start_date").html( errorsGuaranteeStartDate );
                }

                if (response.error["guarantee"] != "")
                {
                    var errorsGuarantee = '<span class="help-block">' + response.error["guarantee"] + '</span>';
                    tr.find("DIV#form_guarantee").html( errorsGuarantee );

                }
                else
                {
                    var errorsGuarantee = ' ';
                    tr.find("DIV#form_guarantee").html( errorsGuarantee );
                }

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

                
            }
        }
    });
}

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

$(document).ready(function(){
    $('#id_guarantee_header')
    .wrapInner('<span title="sort this column"/>')
    .each(function(){
        
        var th = $(this),
            thIndex = th.index(),
            inverse = false;
        
        th.click(function(){
            //console.log($("#body_officer").find('.sort_id'));
            if(asc)
            {
            	$("#body_guarantee").each(function(){
				    $(this).html($(this).find('.guarantee_sort_id').sort(function(a, b){
				        return ($(b).data('registe_no').toString().toLowerCase()) < ($(a).data('registe_no').toString().toLowerCase()) ? 1 : -1;
				    }));
				});

				asc = false;
            }
            else
            {
	            $("#body_guarantee").each(function(){
				    $(this).html($(this).find('.guarantee_sort_id').sort(function(a, b){
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

    $('#id_guarantee_name')
    .wrapInner('<span title="sort this column"/>')
    .each(function(){
        
        var th = $(this),
            thIndex = th.index(),
            inverse = false;
        
        th.click(function(){
            //console.log($("#body_officer").find('.sort_id'));
            if(name_asc)
            {
            	$("#body_guarantee").each(function(){
				    $(this).html($(this).find('.guarantee_sort_id').sort(function(a, b){
				        return ($(b).data('name').toString().toLowerCase()) < ($(a).data('name').toString().toLowerCase()) ? 1 : -1;
				    }));
				});

				name_asc = false;
            }
            else
            {
	            $("#body_guarantee").each(function(){
				    $(this).html($(this).find('.guarantee_sort_id').sort(function(a, b){
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

    $('#id_currency')
    .wrapInner('<span title="sort this column"/>')
    .each(function(){
        
        var th = $(this),
            thIndex = th.index(),
            inverse = false;
        
        th.click(function(){
            //console.log($("#body_officer").find('.sort_id'));
            if(currency_asc)
            {
            	$("#body_guarantee").each(function(){
				    $(this).html($(this).find('.guarantee_sort_id').sort(function(a, b){
				        return ($(b).data('currency').toLowerCase()) < ($(a).data('currency').toLowerCase()) ? 1 : -1;
				    }));
				});

				currency_asc = false;
            }
            else
            {
	            $("#body_guarantee").each(function(){
				    $(this).html($(this).find('.guarantee_sort_id').sort(function(a, b){
				        return ($(b).data('currency').toLowerCase()) < ($(a).data('currency').toLowerCase()) ? -1 : 1;
				    }));
				});

				currency_asc = true;
            }
            $('#guarantee_start_date').datepicker({ 
			    dateFormat:'dd/mm/yyyy',
			}).datepicker('setStartDate', latest_incorporation_date); 
        });
            
    });

    $('#id_guarantee')
    .wrapInner('<span title="sort this column"/>')
    .each(function(){
        
        var th = $(this),
            thIndex = th.index(),
            inverse = false;
        
        th.click(function(){
            //console.log($("#body_officer").find('.sort_id'));
            if(guarantee_asc)
            {
            	$("#body_guarantee").each(function(){
				    $(this).html($(this).find('.guarantee_sort_id').sort(function(a, b){
				        return ($(b).data('guarantee')) < ($(a).data('guarantee')) ? 1 : -1;
				    }));
				});

				guarantee_asc = false;
            }
            else
            {
	            $("#body_guarantee").each(function(){
				    $(this).html($(this).find('.guarantee_sort_id').sort(function(a, b){
				        return ($(b).data('guarantee')) < ($(a).data('guarantee')) ? -1 : 1;
				    }));
				});

				guarantee_asc = true;
            }
            $('#guarantee_start_date').datepicker({ 
			    dateFormat:'dd/mm/yyyy',
			}).datepicker('setStartDate', latest_incorporation_date); 
        });
            
    });

    $('#id_guarantee_start_date')
    .wrapInner('<span title="sort this column"/>')
    .each(function(){
        
        var th = $(this),
            thIndex = th.index(),
            inverse = false;
        
        th.click(function(){
            //console.log($("#body_officer").find('.sort_id'));
            if(guarantee_start_date_asc)
            {
            	$("#body_guarantee").each(function(){
				    $(this).html($(this).find('.guarantee_sort_id').sort(function(a, b){
				        return ($(b).data('guarantee_start_date').toLowerCase()) < ($(a).data('guarantee_start_date').toLowerCase()) ? 1 : -1;
				    }));
				});

				guarantee_start_date_asc = false;
            }
            else
            {
	            $("#body_guarantee").each(function(){
				    $(this).html($(this).find('.guarantee_sort_id').sort(function(a, b){
				        return ($(b).data('guarantee_start_date').toLowerCase()) < ($(a).data('guarantee_start_date').toLowerCase()) ? -1 : 1;
				    }));
				});

				guarantee_start_date_asc = true;
            }
            $('#guarantee_start_date').datepicker({ 
			    dateFormat:'dd/mm/yyyy',
			}).datepicker('setStartDate', latest_incorporation_date); 
        });
            
    });


});

get_client_guarantee();

function get_client_guarantee()
{
	if(client_guarantee)
	{
		//console.log(client_guarantee);
		//console.log(client_officers[0]['name']);
		for(var i = 0; i < client_guarantee.length; i++)
		{
			$a="";
			$a += '<form class="tr guarantee_sort_id" method="post" name="form'+i+'" id="form'+i+'" data-guarantee_start_date="'+client_guarantee[i]["guarantee_start_date"]+'" data-guarantee="'+client_guarantee[i]["guarantee"]+'" data-currency="'+client_guarantee[i]["currency_name"]+'" data-registe_no="'+ (client_guarantee[i]["identification_no"]!=null ? client_guarantee[i]["identification_no"] : client_guarantee[i]["register_no"]) +'" data-name="'+ (client_guarantee[i]["company_name"]!=null ? client_guarantee[i]["company_name"] : client_guarantee[i]["name"]) +'">';
			$a += '<div class="hidden"><input type="text" class="form-control" name="company_code" id="company_code" value="'+client_guarantee[i]["company_code"]+'"/></div>';
			$a += '<div class="hidden"><input type="text" class="form-control" name="client_guarantee_id[]" id="client_guarantee_id" value="'+client_guarantee[i]["id"]+'"/></div>';
			$a += '<div class="hidden"><input type="text" class="form-control" name="officer_id" id="officer_id" value="'+client_guarantee[i]["officer_id"]+'"/></div>';
			$a += '<div class="hidden"><input type="text" class="form-control" name="officer_field_type" id="officer_field_type" value="'+client_guarantee[i]["field_type"]+'"/></div>';
			$a += '<div class="td"><input type="text" name="identification_register_no[]" class="form-control" value="'+ (client_guarantee[i]["identification_no"]!=null ? client_guarantee[i]["identification_no"] : client_guarantee[i]["register_no"]) +'" id="gid_add_guarantee_officer" disabled="disabled"/><div id="form_identification_register_no"></div><div style="height:16px;" ><a class="" href="'+ url + 'personprofile/add" style="cursor:pointer;" id="add_office_person_link" target="_blank" hidden><div style="cursor:pointer;">Click here to <span style="font-weight:bold">Add Person</span></div></a></div></div>';
			$a += '<div class="td"><input type="text" name="name[]" id="name" class="form-control" value="'+ (client_guarantee[i]["company_name"]!=null ? client_guarantee[i]["company_name"] : client_guarantee[i]["name"]) +'" readonly/><div id="form_name"></div></div>';
			$a += '<div class="td"><select class="form-control currency" style="text-align:right;width:  100%;" name="currency[]" id="currency'+i+'" disabled="disabled"><option value="0" >Select Currency</option></select><div id="form_currency"></div></div>';
			$a += '<div class="td"><input type="text" name="guarantee[]" id="guarantee" class="numberdes form-control" value="'+addCommas(client_guarantee[i]["guarantee"])+'" style="text-align:right" disabled="disabled"/><div id="form_guarantee"></div></div>';
			$a += '<div class="td"><div class="input-group mb-md" style="width: 150px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="guarantee_start_date" name="guarantee_start_date[]" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="'+client_guarantee[i]["guarantee_start_date"]+'" disabled="disabled"></div><div id="form_guarantee_start_date"></div></div>';
			/*$a += '<div class="td">';
			$a += '<div class="input-group mb-md" style="width: 150px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="guarantee_end_date" name="guarantee_end_date[]" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="'+client_guarantee[i]["guarantee_end_date"]+'" disabled="disabled"></div><div id="form_guarantee_end_date"></div>';
			$a += '</div>';*/
			//$a += '<div class="td action"><button type="button" class="btn btn-primary" onclick="edit_guarantee(this);">Edit</button></div></div>';
            $a += '<div class="td action"><div style="display: inline-block; margin-right: 5px;"><button type="button" class="btn btn-primary submit_guarantee_button" onclick="edit_guarantee(this);">Edit</button></div><div style="display: inline-block;"><button type="button" class="btn btn-primary" onclick="delete_guarantee(this);">Delete</button></div></div>';
			$a += '</form>';
				
				/*<input type="button" value="Save" id="save" name="save'+$count_officer+'" class="btn btn-primary" onclick="save(this);">*/
			$("#body_guarantee").prepend($a);
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
						$('#guarantee_start_date').datepicker({ 
						    dateFormat:'dd/mm/yyyy',
						}).datepicker('setStartDate', $latest_incorporation_date);
					}
				});
			} (i);

			!function (i) {
				$.ajax({
					type: "POST",
					url: "masterclient/get_currency",
					data: {"currency": client_guarantee[i]["currency_id"]},
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
}

if(client_guarantee)
{
	$count_guarantee = client_guarantee.length;
}
else
{
	$count_guarantee = 0;
}

$(document).on('click',"#guarantee_Add",function() {

	$count_guarantee++;
 	$a=""; 
 	/*<select class="input-sm" style="text-align:right;width: 150px;" id="position" name="position[]" onchange="optionCheck(this);"><option value="Director" >Director</option><option value="CEO" >CEO</option><option value="Manager" >Manager</option><option value="Secretary" >Secretary</option><option value="Auditor" >Auditor</option><option value="Managing Director" >Managing Director</option><option value="Alternate Director">Alternate Director</option></select>*/
	$a += '<form class="tr editing guarantee_sort_id" method="post" name="form'+$count_guarantee+'" id="form'+$count_guarantee+'">';
	$a += '<div class="hidden"><input type="text" class="form-control" name="company_code" value="'+company_code+'"/></div>';
	$a += '<div class="hidden"><input type="text" class="form-control client_guarantee_id" name="client_guarantee_id[]" id="client_guarantee_id" value=""/></div>';
	$a += '<div class="hidden"><input type="text" class="form-control" name="officer_id" id="officer_id" value=""/></div>';
	$a += '<div class="hidden"><input type="text" class="form-control" name="officer_field_type" id="officer_field_type" value=""/></div>';
	$a += '<div class="td"><input type="text" name="identification_register_no[]" class="form-control" value="" id="gid_add_guarantee_officer"/><div id="form_identification_register_no"></div><div style="height:16px;" ><a class="" href="'+ url + 'personprofile/add/1" style="cursor:pointer;" id="add_office_person_link" target="_blank" hidden><div style="cursor:pointer;">Click here to <span style="font-weight:bold">Add Person</span></div></a></div></div>';
	$a += '<div class="td"><input type="text" name="name[]" id="name" class="form-control" value="" readonly/><div id="form_name"></div></div>';
	$a += '<div class="td"><select class="form-control currency" style="text-align:right;width:  100%;" name="currency[]" id="currency'+$count_guarantee+'"><option value="0" >Select Currency</option></select><div id="form_currency"></div></div>';
	$a += '<div class="td"><input type="text" name="guarantee[]" id="guarantee" class="numberdes form-control" value="" style="text-align:right"/><div id="form_guarantee"></div></div>';
	$a += '<div class="td"><div class="input-group mb-md" style="width: 150px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="guarantee_start_date" name="guarantee_start_date[]" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value=""></div><div id="form_guarantee_start_date"></div></div>';
	/*$a += '<div class="td">';
	$a += '<div class="input-group mb-md" style="width: 150px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="guarantee_end_date" name="guarantee_end_date[]" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value=""></div><div id="form_guarantee_end_date"></div>';
	$a += '</div>';*/
	//$a += '<div class="td action"><button type="button" class="btn btn-primary" onclick="edit_guarantee(this);">Save</button></div></div>';
	$a += '<div class="td action"><div style="display: inline-block; margin-right: 5px;"><button type="button" class="btn btn-primary submit_guarantee_button" onclick="edit_guarantee(this);">Save</button></div><div style="display: inline-block;"><button type="button" class="btn btn-primary" onclick="delete_guarantee(this);">Delete</button></div></div>';
    $a += '</form>';
	
	/*<input type="button" value="Save" id="save" name="save'+$count_officer+'" class="btn btn-primary" onclick="save(this);">*/
	$("#body_guarantee").prepend($a); 
	$('.datepicker').datepicker({ dateFormat:'yyyy-mm=dd'});

	//console.log(latest_incorporation_date);
	$('#guarantee_start_date').datepicker({ 
	    dateFormat:'dd/mm/yyyy',
	}).datepicker('setStartDate', latest_incorporation_date);

	!function ($count_guarantee) {
		$.ajax({
			type: "GET",
			url: "masterclient/get_currency",
			dataType: "json",
			success: function(data){
	            $("#form"+$count_guarantee+" #currency"+$count_guarantee+"").find("option:eq(0)").html("Select Currency");
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
	                    $("#form"+$count_guarantee+" #currency"+$count_guarantee+"").append(option);
	                    /*console.log("#form"+$count_charges+" #currency"+$count_charges+"");*/
	                });
	            }
	            else{
	                alert(data.msg);
	            }
			}				
		});
	} ($count_guarantee);

});

//Search Officer
$("#gid_add_guarantee_officer").live('change',function(){
	var officer_frm = $(this);
	//console.log(officer_frm.val());
	//console.log($(this).parent().parent('form').find('input[name="name[]"]').val("aaa"));
	$.ajax({
		type: "POST",
		url: "masterclient/get_guarantee_officer",
		data: {"identification_register_no":officer_frm.val()}, // <--- THIS IS THE CHANGE
		dataType: "json",
		success: function(response){

			//console.log(response['name']);
			if(response)
			{
				if(response['field_type'] == "company")
				{
					//console.log(data['field_type'] == "company");
					officer_frm.parent().parent('form').find('input[name="name[]"]').val(response['company_name']);
					officer_frm.parent().parent('form').find('input[name="officer_id"]').val(response['id']);
					officer_frm.parent().parent('form').find('input[name="officer_field_type"]').val(response['field_type']);
					if(response['company_name'] != undefined)
					{
						//officer_frm.parent().parent('form').find('input[name="name[]"]').attr('readOnly', true);
						officer_frm.parent().parent('form').find("DIV#form_name").html( "" );
						//officer_frm.parent().parent('form').find('input[name="name[]"]').val(response['company_name']);
					}
				}
				else
				{
					officer_frm.parent().parent('form').find('input[name="name[]"]').val(response['name']);
					officer_frm.parent().parent('form').find('input[name="officer_id"]').val(response['id']);
					officer_frm.parent().parent('form').find('input[name="officer_field_type"]').val(response['field_type']);
					if(response['name'] != undefined)
					{
						//officer_frm.parent().parent('form').find('input[name="name[]"]').attr('readOnly', true);
						officer_frm.parent().parent('form').find("DIV#form_name").html( "" );
					}
					
				}
				officer_frm.parent().parent('form').find('a#add_office_person_link').attr('hidden',"true");
			}
			else
			{
				officer_frm.parent().parent('form').find('input[name="name[]"]').val("");
				//officer_frm.parent().parent('form').find('input[name="name[]"]').attr('readOnly', false);
				officer_frm.parent().parent('form').find('input[name="officer_id"]').val("");
				officer_frm.parent().parent('form').find('input[name="officer_field_type"]').val("");
				officer_frm.parent().parent('form').find('a#add_office_person_link').removeAttr('hidden');
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
