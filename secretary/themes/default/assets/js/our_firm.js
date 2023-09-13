$('#form_template').formValidation({
    framework: 'bootstrap',
    icon: {
        /*valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'*/
    },
    fields: {
        'invoice_description[]' : {
            row: '.input-group',
            validators: {
                notEmpty: {
                    message: 'The Invoice Description field is required.'
                }
            }
        },
        'amount[]' : {
            row: '.input-group',
            validators: {
                notEmpty: {
                    message: 'The Amount field is required.'
                }
            }
        }
    }
});

toastr.options = {

  "positionClass": "toast-bottom-right"

}

for(var y = 0; y < template.length; y++)
{
	$v = y;
	$a=""; 
	/*<select class="input-sm" style="text-align:right;width: 150px;" id="position" name="position[]" onchange="optionCheck(this);"><option value="Director" >Director</option><option value="CEO" >CEO</option><option value="Manager" >Manager</option><option value="Secretary" >Secretary</option><option value="Auditor" >Auditor</option><option value="Managing Director" >Managing Director</option><option value="Alternate Director">Alternate Director</option></select>*/
	$a += '<div class="tr editing" method="post" name="form'+$v+'" id="form'+$v+'" num="'+$v+'">';
	$a += '<div class="hidden"><input type="text" class="form-control" name="id[]" value="'+template[y]["id"]+'"/></div>';
	// $a += '<div class="td" style="width:250px;"><div class="input-group"><input type="text" name="service[]" class="form-control service" value="'+template[y]["service_name"]+'" id="service" style="width:250px" readOnly/></div></div>';
	$a += '<div class="td"><div class="input-group"><input type="text" name="service_id[]" class="form-control service_id" value="'+template[y]["service_id"]+'" id="service_id" style="width:100%;"/><div id="form_service_id"></div></div></div>';
    $a += '<div class="td" style="width:250px;"><div class=""><select class="form-control" style="text-align:right;width: 100%;" name="service['+$v+']" id="service" onchange="checkService(this);"><option value="0">Select Service</option></select></div></div>';
	$a += '<div class="td"><div class="input-group"><input type="text" name="service_name[]" class="form-control service_name" value="'+template[y]["service_name"]+'" id="service_name" style="width:100%;text-align:right;"/><div id="form_service_name"></div></div></div>';
    $a += '<div class="td"><div class="input-group"><textarea class="form-control" name="invoice_description[]"  id="invoice_description" rows="5" style="width:250px">'+template[y]["invoice_description"]+'</textarea></div></div>';
	$a += '<div class="td"><div class="input-group"><input type="text" name="amount[]" pattern="[0-9,.]" class="numberdes form-control amount" value="'+addCommas(template[y]["amount"])+'" id="amount" style="width:100%;text-align:right;"/><div id="form_amount"></div></div></div>';
	$a += '<div class="td action"><button type="button" class="btn btn-primary" onclick="delete_billing_info(this);">Delete</button></div></div>';
	/*$a += '<div class="td action"><button type="button" class="btn btn-primary" onclick="edit_billing_info(this);">Save</button></div></div>';*/
	$a += '</div>';

	/*<input type="button" value="Save" id="save" name="save'+$count_officer+'" class="btn btn-primary" onclick="save(this);">*/
	$("#body_template_info").prepend($a); 
	//console.log(template);
	!function ($v) {

        $.ajax({
            type: "POST",
            url: "masterclient/get_billing_service",
            data: {"company_code": company_code, "service": template[y]["service"]},
            dataType: "json",
            success: function(data){
                //console.log(data);
                $("#form"+$v+" #service").find("option:eq(0)").html("Select Service");
                if(data.tp == 1){
                    var category_description = '';
                    var optgroup = '';
                    for(var t = 0; t < data.result.length; t++)
                    {
                        if(category_description != data.result[t]['category_description'])
                        {
                            if(optgroup != '')
                            {
                                $("#form"+$v+" #service").append(optgroup);
                            }
                            optgroup = $('<optgroup label="' + data.result[t]['category_description'] + '" />');
                        }

                        var option = $('<option />');
                        option.attr('value', data.result[t]['id']).text(data.result[t]['service']).appendTo(optgroup);

                        if(data.selected_service != null && data.result[t]['id'] == data.selected_service)
                        {
                            option.attr('selected', 'selected');
                        }

                        category_description = data.result[t]['category_description'];
                    }
                    $("#form"+$v+" #service").append(optgroup);
                    //console.log($v);
                    // $.each(data['result'], function(key, val) {
                    //     var option = $('<option />');
                    //     option.attr('value', key).text(val);
                    //     if(data.selected_service != null && key == data.selected_service)
                    //     {
                    //         option.attr('selected', 'selected');
                    //     }
                    //     $("#form"+i+" #service").append(option);
                    // });

                    $("#form"+$v+" #service option").filter(function()
                    {
                        return $.inArray($(this).val(),data.selected_query)>-1;
                    }).attr("disabled","disabled");  

                    $('select[name="service['+$v+']"] option').filter(function()
                    {
                        return $(this).val() === data.selected_service;
                    }).attr("disabled", false);
                }
                else{
                    alert(data.msg);
                }  
            }               
        });
    } ($v);
}

if(template)
{
    $count_template_info = template.length + 1;
}
else
{
    $count_template_info = 0;
}

$(document).on('click',"#billing_info_Add",function() {
	$a=""; 
	/*<select class="input-sm" style="text-align:right;width: 150px;" id="position" name="position[]" onchange="optionCheck(this);"><option value="Director" >Director</option><option value="CEO" >CEO</option><option value="Manager" >Manager</option><option value="Secretary" >Secretary</option><option value="Auditor" >Auditor</option><option value="Managing Director" >Managing Director</option><option value="Alternate Director">Alternate Director</option></select>*/
	$a += '<div class="tr editing" method="post" name="form'+$count_template_info+'" id="form'+$count_template_info+'" num="'+$count_template_info+'">';
	//$a += '<div class="hidden"><input type="text" class="form-control" name="id[]" value=""/></div>';
	// $a += '<div class="td" style="width:250px;"><div class="input-group"><input type="text" name="service[]" class="form-control service" value="'+template[y]["service_name"]+'" id="service" style="width:250px" readOnly/></div></div>';
	$a += '<div class="td"><div class="input-group"><input type="text" name="service_id['+$count_template_info+']" class="form-control service_id" value="" id="service_id" style="width:100%;"/><div id="form_service_id"></div></div></div>';
    $a += '<div class="td" style="width:250px;"><div class=""><select class="form-control" style="text-align:right;width: 100%;" name="service['+$count_template_info+']" id="service" onchange="checkService(this);"><option value="0" >Select Service</option></select></div></div>';
	$a += '<div class="td"><div class="input-group"><input type="text" name="service_name['+$count_template_info+']" class="form-control service_name" value="" id="service_name" style="width:100%;text-align:right;"/><div id="form_service_name"></div></div></div>';
    $a += '<div class="td"><div class="input-group"><textarea class="form-control" name="invoice_description['+$count_template_info+']"  id="invoice_description" rows="5" style="width:350px"></textarea></div></div>';
	$a += '<div class="td"><div class="input-group"><input type="text" name="amount['+$count_template_info+']" pattern="[0-9,.]" class="numberdes form-control amount" value="" id="amount" style="width:100%;text-align:right;"/><div id="form_amount"></div></div></div>';
	$a += '<div class="td action"><button type="button" class="btn btn-primary" onclick="delete_billing_info(this);">Delete</button></div></div>';
	/*$a += '<div class="td action"><button type="button" class="btn btn-primary" onclick="edit_billing_info(this);">Save</button></div></div>';*/
	$a += '</div>';

	/*<input type="button" value="Save" id="save" name="save'+$count_officer+'" class="btn btn-primary" onclick="save(this);">*/
	$("#body_template_info").prepend($a); 

	!function ($count_template_info) {

        $.ajax({
            type: "GET",
            url: "masterclient/get_billing_service",
            dataType: "json",
            success: function(data){
                //console.log(data);
                $("#form"+$count_template_info+" #service").find("option:eq(0)").html("Select Service");
                if(data.tp == 1){
                    var category_description = '';
                    var optgroup = '';
                    for(var t = 0; t < data.result.length; t++)
                    {
                        if(category_description != data.result[t]['category_description'])
                        {
                            if(optgroup != '')
                            {
                                $("#form"+$count_template_info+" #service").append(optgroup);
                            }
                            optgroup = $('<optgroup label="' + data.result[t]['category_description'] + '" />');
                        }

                        var option = $('<option />');
                        option.attr('value', data.result[t]['id']).text(data.result[t]['service']).appendTo(optgroup);

                        if(data.selected_service != null && data.result[t]['id'] == data.selected_service)
                        {
                            option.attr('selected', 'selected');
                        }

                        category_description = data.result[t]['category_description'];
                    }
                    $("#form"+$count_template_info+" #service").append(optgroup);

                    // $.each(data['result'], function(key, val) {
                    //     var option = $('<option />');
                    //     option.attr('value', key).text(val);
                    //     if(data.selected_service != null && key == data.selected_service)
                    //     {
                    //         option.attr('selected', 'selected');
                    //     }
                    //     $("#form"+i+" #service").append(option);
                    // });

                    var arr = $.map
                    (
                        $("select#service option:selected"), function(n)
                        {
                            return n.value;
                        }
                    );

                    $('select[name="service['+$count_template_info+']"] option').filter(function()
                    {
                        return $.inArray($(this).val(),arr)>-1;
                     }).attr("disabled","disabled"); 
                }
                else{
                    alert(data.msg);
                }  
            }               
        });
    } ($count_template_info);

    $('#form_template').formValidation('addField', 'invoice_description['+$count_template_info+']', invoice_description);
    $('#form_template').formValidation('addField', 'amount['+$count_template_info+']', amount);

    $count_template_info++;
});

    $(document).on("submit", "#form_template", function(e){
    e.preventDefault();
    var $form = $(e.target);
        
    // and the FormValidation instance
    var fv = $form.data('formValidation');
    console.log(fv);
    // Get the first invalid field
    var $invalidFields = fv.getInvalidFields().eq(0);
    // Get the tab that contains the first invalid field

    console.log($invalidFields);
    var $tabPane     = $invalidFields.parents();
    var valid_setup = fv.isValidContainer($tabPane);

    fv.disableSubmitButtons(false);

    if(valid_setup)
    {
        $('#loadingmessage').show();
        $.ajax({
            type: 'POST',
            url: "billings/save_template",
            data: $form.serialize(),
            dataType: 'json',
            success: function(response){
                $('#loadingmessage').hide();
                //console.log(response.error);
                if (response.Status === 1) 
                {
                    toastr.success("Information Updated", "Success");//contact, title
                    //console.log(response);
                    /*$('#modal_payment').modal('toggle');*/
                    //location.reload();
                }
            }
        });
    }
    else
    {
        toastr.error("Please complete all required field", "Error");
    }
});

$(document).on('click',"#save_template",function(e){
    $("#form_template").submit();
});

function delete_billing_info(element)
{
    var tr = jQuery(element).parent().parent();

    //var client_billing_info_id = tr.find('input[name="client_billing_info_id[]"]').val();

    toastr.success("Updated Information", "Success");
    tr.remove();

    $("select#service option").attr("disabled",false); //enable everything
         
     //collect the values from selected;
    var arr = $.map
    (
        $("select#service option:selected"), function(n)
        {
            return n.value;
        }
    );

    $("select#service").each(function() {

        var other_num = $(this).parent().parent().parent().attr("num");

        // console.log($(this).parent().parent().parent());
        console.log($('select[name="service['+other_num+']"]').val());
        var selected_dropdown_value = $('select[name="service['+other_num+']"]').val();

         $('select[name="service['+other_num+']"] option').filter(function()
        {
            return $.inArray($(this).val(),arr)>-1;
        }).attr("disabled","disabled"); 

        $('select[name="service['+other_num+']"] option').filter(function()
        {
            return $(this).val() === selected_dropdown_value;
        }).attr("disabled", false);

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

$tab_aktif ="ourFirm";

if($tab_aktif == "ourFirm" || $tab_aktif == "bankInfo")
{
    //console.log($("#billing_footer_button"));
    $("#billing_footer_button").hide();
}
else
{
    $("#billing_footer_button").show();
}

$(document).on('click',".our_firm_check_stat",function() {
        $tab_aktif = $(this).data("information");

        if($tab_aktif == "ourFirm" || $tab_aktif == "bankInfo")
        {
            //console.log($("#billing_footer_button"));
            $("#billing_footer_button").hide();
        }
        else
        {
            $("#billing_footer_button").show();
        }

});