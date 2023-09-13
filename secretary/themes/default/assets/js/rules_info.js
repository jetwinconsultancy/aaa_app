$count_rules_info = 0;
$(document).on('click',"#rules_Add",function() {
	$count_rules_info++;
 	$a=""; 
	$a += '<form class="tr rules_editing sort_id" method="post" name="form'+$count_rules_info+'" id="form'+$count_rules_info+'">';
	$a += '<div class="hidden"><input type="text" class="form-control" name="rules_info_id[]" id="rules_info_id" value=""/></div>';
	$a += '<div class="td">';
	$a += '<select class="form-control department" multiple="multiple" style="width: 100%;" name="department[]" id="department"></select><input type="checkbox" id="select_all_department_checkbox'+$count_rules_info+'" name="select_all_department_checkbox">Select All<div id="form_department"></div>';;
	$a += '</div>';
	$a += '<div class="td"><input type="text" name="type[]" id="type" class="form-control" value=""/><div id="form_type"></div></div>';
	$a += '<div class="td"><textarea type="text" name="description[]" class="form-control" value="" id="description"></textarea><div id="form_description"></div></div>';
	$a += '<div class="td rules_action"><div style="display: inline-block; margin-right: 5px; margin-bottom: 5px;"><button type="button" class="btn btn-primary submit_rules_info_button" onclick="edit_rules(this);">Save</button></div><div style="display: inline-block;"><button type="button" class="btn btn-primary" onclick="delete_rules_info(this);">Delete</button></div></div>';
	$a += '</form>';
	
	$("#body_rules_info").prepend($a); 

	$("#loadingmessage").show();
	$.ajax({
        type: "GET",
        url: "masterclient/get_department",
        async: false,
        dataType: "json",
        success: function(data){
            $("#loadingmessage").hide();
            $.each(data['result'], function(key, val) {
                var option = $('<option />');
                option.attr('value', key).text(val);

                $('#form'+$count_rules_info).find("#department").append(option);
            });

            $("#form"+$count_rules_info+" #department").select2();
        }
    });

    $(document).on('click',"#select_all_department_checkbox"+$count_rules_info,function() {
	    if($(this).is(':checked') ){
	        $(this).parent().find("#department > option").prop("selected","selected");
	        $(this).parent().find("#department").trigger("change");
	    }else{
	        $(this).parent().find("#department > option").removeAttr("selected");
	        $(this).parent().find("#department").trigger("change");
	     }
	});
});


function edit_rules(element)
{
	var tr = jQuery(element).parent().parent().parent();
	if(!tr.hasClass("rules_editing")) 
	{
		tr.addClass("rules_editing");
		tr.find("DIV.td").each(function()
		{
			if(!jQuery(this).hasClass("rules_action"))
			{
				jQuery(this).find('input[name="department[]"]').attr('disabled', false);
				jQuery(this).find('input[name="type[]"]').attr('disabled', false);
				jQuery(this).find('textarea[name="description[]"]').attr('disabled', false);
                jQuery(this).find('input[name="select_all_department_checkbox"]').attr('disabled', false);
				jQuery(this).find("select").attr('disabled', false);
			} 
			else 
			{
				jQuery(this).find(".submit_rules_info_button").text("Save");
			}
		});
	} 
	else 
	{
		var frm = $(element).closest('form');
		var frm_serialized = frm.serialize();
		rules_info_submit(frm_serialized, tr);
	}
}

$("#department").live('change',function(){
	$(this).parent().parent('form').find("DIV#form_department").html( "" );
});

$("#type").live('change',function(){
	$(this).parent().parent('form').find("DIV#form_type").html( "" );
});

$("#description").live('change',function(){
	$(this).parent().parent('form').find("DIV#form_description").html( "" );
});

toastr.options = {
  "positionClass": "toast-bottom-right"
}

function rules_info_submit(frm_serialized, tr)
{
	$('#loadingmessage').show();
	$.ajax({ //Upload common input
        url: "auth/add_rules_info",
        type: "POST",
        data: frm_serialized,
        dataType: 'json',
        success: function (response) {
        	$('#loadingmessage').hide();
            if (response.Status === 1) 
            {
            	toastr.success(response.message, response.title);
            	if(response.insert_rules_info_id != null)
            	{
            		tr.find('input[name="rules_info_id[]"]').attr('value', response.insert_rules_info_id);
            	}
            	tr.removeClass("rules_editing");

				tr.find("DIV.td").each(function(){
					if(!jQuery(this).hasClass("rules_action"))
					{
						jQuery(this).find('input[name="department[]"]').attr('disabled', true);
						jQuery(this).find('input[name="type[]"]').attr('disabled', true);
                        jQuery(this).find('input[name="select_all_department_checkbox"]').attr('disabled', true);
						jQuery(this).find('textarea[name="description[]"]').attr('disabled', true);
						jQuery(this).find("select").attr('disabled', true);
					} 
					else 
					{
						jQuery(this).find(".submit_rules_info_button").text("Edit");
					}
				});
			    
            }
            else
            {
				toastr.error(response.message, response.title);
            	if (response.error["department"] != "")
            	{
            		var errorsDepartment = '<span class="help-block">*' + response.error["department"] + '</span>';
            		tr.find("DIV#form_department").html( errorsDepartment );
            		tr.find("#department").val("");

            	}
            	else
            	{
            		var errorsDepartment = '';
            		tr.find("DIV#form_department").html( errorsDepartment );
            	}

            	if (response.error["type"] != "")
            	{
            		var errorsType = '<span class="help-block">*' + response.error["type"] + '</span>';
            		tr.find("DIV#form_type").html( errorsType );

            	}
            	else
            	{
            		var errorsType = '';
            		tr.find("DIV#form_type").html( errorsType );
            	}

            	if (response.error["description"] != "")
            	{
            		var errorsDescription = '<span class="help-block">*' + response.error["description"] + '</span>';
            		tr.find("DIV#form_description").html( errorsDescription );

            	}
            	else
            	{
            		var errorsDescription = '';
            		tr.find("DIV#form_description").html( errorsDescription );
            	}
            }
        }
    });
}

//$(document).on('click',".users_check_state",function() {
$(document).ready(function(){
    $( '.users_check_state' ).click(function() {
        $tab_aktif = $(this).data("information");
        
        //console.log($("#loadingmessage").show());
        if($tab_aktif == "rulesList")
        {
            //if(!$( '.loading' ).is(":visible")){
            $(".loading").show();
            //}
            $.ajax({
                type: "GET",
                url: "auth/get_rules_data",
                //async: false,
                dataType: "json",
                success: function(rules_info){
                    $(".loading").hide();
                    $("#body_rules_info").empty();

                    if(rules_info)
                    {
                        for(var i = 0; i < rules_info.length; i++)
                        {
                            $a=""; 
                            $a += '<form class="tr sort_id" method="post" name="form'+i+'" id="form'+i+'">';
                            $a += '<div class="hidden"><input type="text" class="form-control" name="rules_info_id[]" id="rules_info_id" value="'+rules_info[i]["id"]+'"/></div>';
                            $a += '<div class="td">';
                            $a += '<select class="form-control department" multiple="multiple" style="width: 100%;" name="department[]" id="department"></select><input type="checkbox" id="select_all_department_checkbox'+i+'" name="select_all_department_checkbox">Select All<div id="form_department"></div>';;
                            $a += '</div>';
                            $a += '<div class="td"><input type="text" name="type[]" id="type" class="form-control" value="'+rules_info[i]["type"]+'"/><div id="form_type"></div></div>';
                            $a += '<div class="td"><textarea type="text" name="description[]" class="form-control" value="" id="description">'+rules_info[i]["description"]+'</textarea><div id="form_description"></div></div>';
                            $a += '<div class="td rules_action"><div style="display: inline-block; margin-right: 5px; margin-bottom: 5px;"><button type="button" class="btn btn-primary submit_rules_info_button" onclick="edit_rules(this);">Edit</button></div><div style="display: inline-block;"><button type="button" class="btn btn-primary" onclick="delete_rules_info(this);">Delete</button></div></div>';
                            $a += '</form>';
                            
                            $("#body_rules_info").append($a); 

                            if(rules_info[i]["select_all_department_checkbox"] == 0)
                            {
                                $('#select_all_department_checkbox'+i).prop('checked', false);
                            }
                            else if(rules_info[i]["select_all_department_checkbox"] == 1)
                            {
                                $('#select_all_department_checkbox'+i).prop('checked', true);
                            }

                            $.each(department, function(key, val) {
                                var option = $('<option />');
                                option.attr('value', key).text(val);

                                $('#form'+i).find("#department").append(option);
                            });

                            var s2 = $("#form"+i+" #department").select2();

                            var vals = JSON.parse(rules_info[i]["department"]);

                            s2.val(vals).trigger("change");

                            $(document).on('click',"#select_all_department_checkbox"+i,function() {
                                if($(this).is(':checked') ){
                                    $(this).parent().find("#department > option").prop("selected","selected");
                                    $(this).parent().find("#department").trigger("change");
                                }else{
                                    $(this).parent().find("#department > option").removeAttr("selected");
                                    $(this).parent().find("#department").trigger("change");
                                 }
                            });

                            $('input[name="department[]"]').attr('disabled', true);
                            $('input[name="type[]"]').attr('disabled', true);
                            $('input[name="select_all_department_checkbox"]').attr('disabled', true);
                            $('textarea[name="description[]"]').attr('disabled', true);
                            $("select").attr('disabled', true);
                        }
                    }
                }
            });
        }
        else
        {
            $(".loading").hide();
        }
        
    });
});

function delete_rules_info(element)
{
    var tr = jQuery(element).parent().parent().parent();

    var rules_info_id = tr.find('input[name="rules_info_id[]"]').val();
    $('#loadingmessage').show();
    if(rules_info_id != undefined)
    {
        $.ajax({ //Upload common input
            url: "auth/delete_rules_info",
            type: "POST",
            data: {"rules_info_id": rules_info_id},
            dataType: 'json',
            success: function (response) {
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

