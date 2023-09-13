var id = {
    row: '.transfer_group',   // The title is placed inside a <div class="col-xs-4"> element
    validators: {
        callback: {
            message: 'The ID field is required.',
            callback: function(value, validator, $field) {
                var num = jQuery($field).parent().parent().parent().attr("num");
                var options = validator.getFieldElements('id['+num+']').val();
                //console.log(options);
                return (options != null && options != "0");
            }
        }
    }
},
share_transfer = {
    row: '.transfer_group',   // The title is placed inside a <div class="col-xs-4"> element
    validators: {
        notEmpty: {
            message: 'The Number of Shares to Transfer field is required.'
        }/*,
        integer: {
            message: 'The value is not an integer'
        }*/
    }
},
consideration = {
    row: '.transfer_group',   // The title is placed inside a <div class="col-xs-4"> element
    validators: {
        notEmpty: {
            message: 'The Consideration field is required.'
        }/*,
        integer: {
            message: 'The value is not an integer'
        }*/
    }
},
id_to = {
    row: '.transfer_group',   // The title is placed inside a <div class="col-xs-4"> element
    validators: {
        notEmpty: {
            message: 'The ID field is required.'
            
        }
    }
},
// number_of_share_to = {
//     row: '.transfer_group',   // The title is placed inside a <div class="col-xs-4"> element
//     validators: {
//         notEmpty: {
//             message: 'The Number of Shares field is required.'
//         }/*,
//         integer: {
//             message: 'The value is not an integer'
//         }*/
//     }
// },
certificate = {
    row: '.transfer_group',   // The title is placed inside a <div class="col-xs-4"> element
    validators: {
        notEmpty: {
            message: 'The Certificate No field is required.'
        }
    }
};

var edit_cert = false;
var allotmentPeople;
var pathArray = location.href.split( '/' );
var protocol = pathArray[0];
var host = pathArray[2];
var folder = pathArray[3]
var url = protocol + '//' + host + '/' + folder + '/';

take_incorporation_date();

function take_incorporation_date()
{
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

            latest_incorporation_date = new Date($date_2);
            /*date.setDate(date.getDate()-1)
    */
            //console.log(new Date());
            $('#transaction_date').datepicker({ 
                dateFormat:'dd/mm/yyyy',
            }).datepicker('setStartDate', latest_incorporation_date);
        }
    });
}


toastr.options = {
  "positionClass": "toast-bottom-right"
}

$("#transaction_date").live('change',function(){
    if($(this).val() == "")
    {
        toastr.error("Transaction Date must be on or after the incorporation date.", "Error");
    }
});


function addFirstFrom()
{
    $a0=""; 
    $a0 += '<div class="tr editing transfer" method="post" name="form'+0+'" id="form'+0+'" num="'+0+'">';
    $a0 += '<div class="hidden"><input type="text" class="form-control" name="company_code" value="'+company_code+'"/></div>';
    $a0 += '<div class="hidden"><input type="text" class="form-control cert_id" name="cert_id[]" id="cert_id" value=""/></div>';
    $a0 += '<div class="hidden"><input type="text" class="form-control" name="transfer_id[]" id="transfer_id" value=""/></div>';
    $a0 += '<div class="hidden"><input type="text" class="form-control" name="officer_id['+0+']" id="officer_id" value=""/></div>';
    $a0 += '<div class="hidden"><input type="text" class="form-control" name="field_type['+0+']" id="field_type" value=""/></div>';
    /*$a += '<div class="td">'+$count_allotment+'</div>';*/
    $a0 += '<div class="td"><div class="transfer_group mb-md" style="width: 400px"><input type="hidden" class="form-control" name="certID['+0+']" value="" id="certID"/><input type="hidden" class="form-control" name="identification_no['+0+']" value="" id="identification_no"/><input type="hidden" class="form-control" name="person_name['+0+']" value="" id="person_name"/><select class="form-control person_id" style="text-align:right;width: 100%;" name="id['+0+']" id="person_id"><option value="0" >Select ID</option></select></div></div>';
    /*$a0 += '<div class="td"><div class="transfer_group mb-md"><input type="text" name="name['+0+']" class="form-control" value=""/></div></div>';
    $a0 += '<div class="td"><div class="transfer_group mb-md"><input type="text" name="number_of_share['+0+']" class="form-control" value=""/></div></div>';*/
    //$a0 += '<div class="td"><div class="transfer_group mb-md" id="name" style="width: 200px; text-align:left"></div><input type="hidden" class="form-control" name="person_name['+0+']" value="" id="person_name"/></div>';
    $a0 += '<div class="td"><div class="transfer_group mb-md" id="number_of_share" style="text-align:right;width: 200px"></div><input type="hidden" class="form-control" name="current_share['+0+']" value="" id="current_share"/><input type="hidden" class="form-control" name="amount_share['+0+']" value="" id="amount_share"/><input type="hidden" class="form-control" name="no_of_share_paid['+0+']" value="" id="no_of_share_paid"/><input type="hidden" class="form-control" name="amount_paid['+0+']" value="" id="amount_paid"/></div>';
    $a0 += '<div class="td"><div class="transfer_group mb-md" style="width: 200px"><input type="text" class="numberdes form-control share_transfer" name="share_transfer['+0+']" value="" id="share_transfer" style="text-align:right" pattern="^[0-9,]+$"/></div></div>';
    $a0 += '<div class="td"><div class="transfer_group mb-md" style="width: 200px"><input type="text" class="numberdes form-control consideration" name="consideration['+0+']" value="" id="consideration" style="text-align:right" pattern="^[0-9,.]+$"/></div></div>';
    //$a0 += '<div class="td action"><button type="button" class="btn btn-primary delete_transfer_button" onclick="delete_transfer(this)" style="display: none;">Delete</button></div>';
    $a0 += '</div>';

    $("#transfer_add").append($a0); 

    $('#transfer_form').formValidation('addField', 'id['+0+']', id);
    $('#transfer_form').formValidation('addField', 'share_transfer['+0+']', share_transfer);
    $('#transfer_form').formValidation('addField', 'consideration['+0+']', consideration);

    $ato=""; 
    $ato += '<div class="tr editing to" method="post" name="form_to'+0+'" id="form_to'+0+'" num_to="'+0+'">';
    $ato += '<div class="hidden"><input type="text" class="form-control" name="company_code" value="'+company_code+'"/></div>';
    $ato += '<div class="hidden"><input type="text" class="form-control to_cert_id" name="to_cert_id[]" id="to_cert_id" value=""/></div>';
    $ato += '<div class="hidden"><input type="text" class="form-control" name="to_id[]" id="to_id" value=""/></div>';
    $ato += '<div class="hidden"><input type="text" class="form-control" name="to_officer_id['+0+']" id="to_officer_id" value=""/></div>';
    $ato += '<div class="hidden"><input type="text" class="form-control" name="to_field_type['+0+']" id="to_field_type" value=""/></div>';
    $ato += '<div class="hidden"><input type="text" class="form-control" name="previous_new_cert['+0+']" id="previous_new_cert" value=""/></div>';
    $ato += '<div class="hidden"><input type="text" class="form-control" name="previous_cert['+0+']" id="previous_cert" value=""/></div>';
    /*$a += '<div class="td">'+$count_allotment+'</div>';*/
    $ato += '<div class="td"><div class="transfer_group"><input type="text" name="id_to['+0+']" class="form-control get_person_id" value="" id="get_person_id" style="text-transform:uppercase;"/></div><div style="height:16px;" ><a class="" href="'+ url + 'personprofile/add" style="cursor:pointer;" id="add_person_link" target="_blank" hidden><div style="cursor:pointer;">Click here to <span style="font-weight:bold">Add Entity</span></div></a></div></div>';
    /*$a0 += '<div class="td"><div class="transfer_group mb-md"><input type="text" name="name['+0+']" class="form-control" value=""/></div></div>';
    $a0 += '<div class="td"><div class="transfer_group mb-md"><input type="text" name="number_of_share['+0+']" class="form-control" value=""/></div></div>';*/
    $ato += '<div class="td"><div class="transfer_group mb-md" id="name_to" style="width: 200px; text-align:left"></div><input type="hidden" class="form-control" name="to_person_name['+0+']" value="" id="to_person_name"/></div>';
    $ato += '<div class="td"><div class="transfer_group mb-md" style="width: 200px"><input type="text" class="numberdes form-control number_of_share_to" name="number_of_share_to['+0+']" value="" id="number_of_share_to" style="text-align:right" pattern="^[0-9,]+$" readonly="true"/></div></div>';
    /*$ato += '<div class="td"><div class="transfer_group mb-md"><input type="text" class="form-control" name="certificate['+0+']" value="" id="certificate"/></div></div>';*/
    // $ato += '<div class="td action"><button type="button" class="btn btn-primary delete_to_button" onclick="delete_to(this)" style="display: none;">Delete</button></div>';
    $ato += '</div>';

    $("#transfer_to_add").append($ato); 

    $('#transfer_form').formValidation('addField', 'id_to['+0+']', id_to);
    //$('#transfer_form').formValidation('addField', 'number_of_share_to['+0+']', number_of_share_to);
    /*$('#transfer_form').formValidation('addField', 'certificate['+0+']', certificate); */
}

$('#transfer_form').formValidation({
    framework: 'bootstrap',
    icon: {
        /*valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'*/
    },
    // This option will not ignore invisible fields which belong to inactive panels
    //excluded: ':disabled',
    //excluded: [':disabled'],
    fields: {
        date: {
            validators: {
                notEmpty: {
                    message: 'The Transaction Date field is required'
                }
            }
        },
        class: {
            validators: {
                callback: {
                    message: 'The Class field is required',
                    callback: function(value, validator, $field) {
                        var options = validator.getFieldElements('class').val();
                        //console.log(options);
                        return (options != null && options != "0");
                    }
                }
            }
        }
        /*'id[0]': id,
        'share_transfer[0]': share_transfer,
        'id_to[0]': id_to,
        'number_of_share_to[0]': number_of_share_to,
        'certificate[0]': certificate*/
        
    }
});

if(company_class)
{
    //console.log(transfer);
    var shareClass;
    
    for(var i = 0; i < company_class.length; i++)
    {
        if(company_class[i]['sharetype'] == "Ordinary Share" || company_class[i]['sharetype'] == "Preferred Share")
        {
            shareClass = company_class[i]['sharetype'] + " ( " + company_class[i]['currency'] + " )";
        }
        else if(company_class[i]['sharetype'] == "Others")
        {
            shareClass = company_class[i]['other_class'] + " ( " + company_class[i]['currency'] + " )";
        }

        var option = $('<option />');
        /*console.log(currency_id);*/
        option.attr('data-otherclass', company_class[i]['other_class']);
        option.attr('data-currency', company_class[i]['currency']);
        option.attr('data-sharetype', company_class[i]['sharetype']);
        option.attr('value', company_class[i]['id']).text(shareClass);
        if(transfer)
        {
            if(transfer[0]["share_capital_id"] != null && company_class[i]['id'] == transfer[0]["share_capital_id"])
            {
                option.attr('selected', 'selected');
                /*if(transfer[0]["sharetype"] == "Others")
                {
                    $("#other_class").removeAttr('hidden');
                }*/
            }
        }

        $("#class").append(option);
    }
    //console.log(option);
}

$("#class").on('change', function() {
    //console.log($(this).find("option:selected").data('otherclass')==" ");
    //console.log($(this).find("option:selected").val());
    if($(this).find("option:selected").val() == 0)
    {
        //$("#other_class").attr("hidden","true");
        $(".others_field").attr("hidden","true");
        $("#currency").val($(this).find("option:selected").data('currency'));
    }
    else
    {
        if($(this).find("option:selected").data('otherclass')!=" ")
        {
            //$("#other_class").removeAttr('hidden');
            $(".others_field").removeAttr('hidden');
            /*$("#others").val($(this).find("option:selected").data('otherclass'));
            other_class = $(this).find("option:selected").data('otherclass');
            $(".member_others").val($(this).find("option:selected").data('otherclass'));*/
        }
        else
        {
            //$("#other_class").attr("hidden","true");
            $(".others_field").attr("hidden","true");

        }
        $("#others").val($(this).find("option:selected").data('otherclass'));
       /* other_class = $(this).find("option:selected").data('otherclass');
        $(".member_others").val($(this).find("option:selected").data('otherclass'));*/

        $("#client_member_share_capital_id").val($(this).find("option:selected").val());
        //console.log($(this).find("option:selected").val());
        $("#currency").val($(this).find("option:selected").data('currency'));

        if(transfer)
        {
            if($(this).find("option:selected").val() == transfer[0]["share_capital_id"])
            {
                /*$('.edit_certificate_no').attr('readonly', true);
                $('.edit_certificate_no').val(allotment[0]["certificate_no"]);*/
                //console.log("false");
                edit_cert = false;
            }
            else
            {
                /*$('.edit_certificate_no').attr('readonly', false);*/
                //console.log("true");
                edit_cert = true;
            }
        }

        /*currency = $(this).find("option:selected").data('currency');
        shareType = $(this).find("option:selected").data('sharetype');

        $(".member_class").val($(this).find("option:selected").data('sharetype'));
        $(".member_currency").val($(this).find("option:selected").data('currency'));*/
    }
    
    
});




//console.log(transfer);
/*console.log(allotmentPeople);*/

if(transfer)
{
    $count_transfer = transfer.length + 1;
}
else
{
    $count_transfer = 0;
}
$(document).on('click',"#transfer_member_Add",function() {

    $count_transfer++;
    $field_index = $count_transfer;
    $a="";

    $a += '<div class="tr editing transfer" method="post" name="form'+$count_transfer+'" id="form'+$count_transfer+'" num="'+$count_transfer+'">';
    $a += '<div class="hidden"><input type="text" class="form-control" name="company_code" value="'+company_code+'"/></div>';
    $a += '<div class="hidden"><input type="text" class="form-control cert_id" name="cert_id[]" id="cert_id" value=""/></div>';
    $a += '<div class="hidden"><input type="text" class="form-control" name="transfer_id[]" id="transfer_id" value=""/></div>';
    $a += '<div class="hidden"><input type="text" class="form-control" name="officer_id['+$field_index+']" id="officer_id" value=""/></div>';
    $a += '<div class="hidden"><input type="text" class="form-control" name="field_type['+$field_index+']" id="field_type" value=""/></div>';
    /*$a += '<div class="td">'+$count_allotment+'</div>';*/
    $a += '<div class="td"><div class="transfer_group mb-md" style="width: 200px"><select class="form-control person_choice'+$field_index+' person_id" style="text-align:right;width: 100%;" name="id['+$field_index+']" id="person_id"><option value="0" >Select ID</option></select></div></div>';
    /*$a += '<div class="td"><div class="transfer_group mb-md"><input type="text" name="name['+$field_index+']" class="form-control" value=""/></div></div>';
    $a += '<div class="td"><div class="transfer_group mb-md"><input type="text" name="number_of_share['+$field_index+']" class="form-control" value=""/></div></div>';*/
    $a += '<div class="td"><div class="transfer_group mb-md" id="name" style="width: 200px; text-align:left"></div><input type="hidden" class="form-control" name="identification_no['+field_index+']" value="" id="identification_no"/><input type="hidden" class="form-control" name="person_name['+$field_index+']" value="" id="person_name"/></div>';
    $a += '<div class="td"><div class="transfer_group mb-md" id="number_of_share" style="text-align:right;width: 200px"></div><input type="hidden" class="form-control" name="current_share['+$field_index+']" value="" id="current_share"/><input type="hidden" class="form-control" name="amount_share['+$field_index+']" value="" id="amount_share"/><input type="hidden" class="form-control" name="no_of_share_paid['+$field_index+']" value="" id="no_of_share_paid"/><input type="hidden" class="form-control" name="amount_paid['+$field_index+']" value="" id="amount_paid"/></div>';
    $a += '<div class="td"><div class="transfer_group mb-md" style="width: 200px"><input type="text" class="numberdes form-control share_transfer" name="share_transfer['+$field_index+']" value="" id="share_transfer" style="text-align:right" pattern="^[0-9,]+$"/></div></div>';
    $a += '<div class="td"><div class="transfer_group mb-md" style="width: 200px"><input type="text" class="numberdes form-control consideration" name="consideration['+$field_index+']" value="" id="consideration" style="text-align:right" pattern="^[0-9,.]+$"/></div></div>';
    //$a += '<div class="td action"><button type="button" class="btn btn-primary delete_transfer_button" onclick="delete_transfer(this)" style="display: block;">Delete</button></div>';
    $a += '</div>';

    $("#transfer_add").append($a); 

    if($("#transfer_add > div").length > 1)
    {
        $('.delete_transfer_button').css('display','block');
    }

    for(var i = 0; i < allotmentPeople.length; i++)
    {
        var option = $('<option />');
        /*console.log(currency_id);*/

        option.attr('data-name', (allotmentPeople[i]["company_name"]!=null ? allotmentPeople[i]["company_name"] : (allotmentPeople[i]["name"]!=null ? allotmentPeople[i]["name"] : allotmentPeople[i]["client_company_name"])));
        option.attr('data-numberofshare', allotmentPeople[i]['number_of_share']);
        option.attr('data-amountshare', allotmentPeople[i]['amount_share']);
        option.attr('data-noofsharepaid', allotmentPeople[i]['no_of_share_paid']);
        option.attr('data-amountpaid', allotmentPeople[i]['amount_paid']);
        option.attr('data-officerid', allotmentPeople[i]['officer_id']);
        option.attr('data-fieldtype', allotmentPeople[i]['field_type']);
        option.attr('value', allotmentPeople[i]['officer_id']).text((allotmentPeople[i]["identification_no"]!=null ? allotmentPeople[i]["identification_no"] : (allotmentPeople[i]["register_no"]!=null ? allotmentPeople[i]["register_no"] : allotmentPeople[i]["registration_no"])));

        //console.log($("#form"+$count_transfer+" .person_choice"+$field_index+""));
        $("#form"+$count_transfer+" .person_choice"+$field_index+"").append(option); 
    }

    //$("select option").attr("disabled",""); //enable everything
    DisableOptions(); //disable selected values
    

   /* var arr = $.map
    (
        $("select#person_id option:selected"), function(n)
        {
            return n.value;
        }
    );
    //console.log(arr);

    $('select[name="id['+$field_index+']"] option').filter(function()
    {
        return $.inArray($(this).val(),arr)>-1;
    }).attr("disabled","disabled");*/
    $('#transfer_form').formValidation('addField', 'id['+$field_index+']', id);
    $('#transfer_form').formValidation('addField', 'share_transfer['+$field_index+']', share_transfer);
    $('#transfer_form').formValidation('addField', 'consideration['+$field_index+']', consideration);
    
    
    //$('#wAllotment').formValidation('addField', 'name['+$field_index+']', nameValidators);

    /*if(other_class != " ")
    {
        $(".others_field").removeAttr('hidden');
    }
    else
    {
        $(".others_field").attr("hidden","true");
    }*/
        
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

if(transfer)
{
    $count_to = transfer.length;
}
else
{
    $count_to = 0;
}
$(document).on('click',"#transfer_to_member_Add",function() {

    $count_to++;
    $field_index = $count_to;

    $ato1=""; 
    $ato1 += '<div class="tr editing to" method="post" name="form_to'+$count_to+'" id="form_to'+$count_to+'" num_to="'+$count_to+'">';
    $ato1 += '<div class="hidden"><input type="text" class="form-control" name="company_code" value="'+company_code+'"/></div>';
    $ato1 += '<div class="hidden"><input type="text" class="form-control to_cert_id" name="to_cert_id[]" id="to_cert_id" value=""/></div>';
    $ato1 += '<div class="hidden"><input type="text" class="form-control" name="to_id[]" id="to_id" value=""/></div>';
    $ato1 += '<div class="hidden"><input type="text" class="form-control" name="to_officer_id['+$field_index+']" id="to_officer_id" value=""/></div>';
    $ato1 += '<div class="hidden"><input type="text" class="form-control" name="to_field_type['+$field_index+']" id="to_field_type" value=""/></div>';
    $ato1 += '<div class="hidden"><input type="text" class="form-control" name="previous_new_cert['+$field_index+']" id="previous_new_cert" value=""/></div>';
    $ato1 += '<div class="hidden"><input type="text" class="form-control" name="previous_cert['+$field_index+']" id="previous_cert" value=""/></div>';

    /*$a += '<div class="td">'+$count_allotment+'</div>';*/
    $ato1 += '<div class="td"><div class="transfer_group"><input type="text" name="id_to['+$field_index+']" class="form-control get_person_id" value="" id="get_person_id" style="text-transform:uppercase;"/></div><div style="height:16px;" ><a class="" href="'+ url + 'personprofile/add" style="cursor:pointer;" id="add_person_link" target="_blank" hidden><div style="cursor:pointer;">Click here to <span style="font-weight:bold">Add Entity</span></div></a></div></div>';
    /*$a0 += '<div class="td"><div class="transfer_group mb-md"><input type="text" name="name['+0+']" class="form-control" value=""/></div></div>';
    $a0 += '<div class="td"><div class="transfer_group mb-md"><input type="text" name="number_of_share['+0+']" class="form-control" value=""/></div></div>';*/
    $ato1 += '<div class="td"><div class="transfer_group mb-md" id="name_to" style="width: 200px; text-align:left"></div><input type="hidden" class="form-control" name="to_person_name['+$field_index+']" value="" id="to_person_name"/></div>';
    $ato1 += '<div class="td"><div class="transfer_group mb-md" style="width: 200px"><input type="text" class="numberdes form-control number_of_share_to" name="number_of_share_to['+$field_index+']" value="" id="number_of_share_to" style="text-align:right" pattern="^[0-9,]+$" readonly="true"/></div></div>';
    /*$ato1 += '<div class="td"><div class="transfer_group mb-md"><input type="text" class="form-control" name="certificate['+$field_index+']" value="" id="certificate"/></div></div>';*/
    //$ato1 += '<div class="td action"><button type="button" class="btn btn-primary delete_to_button" onclick="delete_to(this)" style="display: none;">Delete</button></div>';
    $ato1 += '</div>';

    $("#transfer_to_add").append($ato1); 

    if($("#transfer_to_add > div").length > 1)
    {
        $('.delete_to_button').css('display','block');
    }
    
    $('#transfer_form').formValidation('addField', 'id_to['+$field_index+']', id_to);
    //$('#transfer_form').formValidation('addField', 'number_of_share_to['+$field_index+']', number_of_share_to);
    /*$('#transfer_form').formValidation('addField', 'certificate['+$field_index+']', certificate);*/

   /* var arr = $.map
    (
        $("select#person_id option:selected"), function(n)
        {
            return n.value;
        }
    );
    //console.log(arr);

    $('select[name="id['+$field_index+']"] option').filter(function()
    {
        return $.inArray($(this).val(),arr)>-1;
    }).attr("disabled","disabled");*/
    /*$('#allotment_form').formValidation('addField', 'id['+$field_index+']', idValidators);
    $('#allotment_form').formValidation('addField', 'name['+$field_index+']', nameValidators);
    $('#allotment_form').formValidation('addField', 'number_of_share['+$field_index+']', numberOfShareValidators);
    $('#allotment_form').formValidation('addField', 'amount_share['+$field_index+']', amountShareValidators);
    $('#allotment_form').formValidation('addField', 'no_of_share_paid['+$field_index+']', noOfSharePaidValidators);
    $('#allotment_form').formValidation('addField', 'amount_paid['+$field_index+']', amountPaidValidators);
    $('#allotment_form').formValidation('addField', 'certificate_no['+$field_index+']', certificateNoValidators);*/
    //$('#wAllotment').formValidation('addField', 'name['+$field_index+']', nameValidators);

    /*if(other_class != " ")
    {
        $(".others_field").removeAttr('hidden');
    }
    else
    {
        $(".others_field").attr("hidden","true");
    }*/
        
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

function DisableOptions()
{
    $("select option").attr("disabled",false); //enable everything

    var arr=[];
    $("select#person_id").each(function(index)
    {
        //console.log($("select#person_id")[index].options[2]);
        //console.log($("select#person_id")[index].selectedIndex);
        arr.push($("select#person_id")[index].selectedIndex);
    });
    

    arr = jQuery.grep(arr, function(value) {
      return value != 0;
    });
    

    $("select#person_id").each(function(index)
    {
        for(var p = 0; p < arr.length; p++)
        {
            if(arr[p] != $("select#person_id")[index].selectedIndex)
            {
                $("select#person_id")[index].options[ arr[p] ].disabled = true;
            }
        }
    });

}


/*function DisableOptions()
{
    $("select option").attr("disabled",false); //enable everything

    var arr=[];
    $("select#person_id option:selected").each(function()
    {
      arr.push($(this).val());
    });
    

    arr = jQuery.grep(arr, function(value) {
      return value != 0;
    });
    // /console.log(arr);
    $('select#person_id option').filter(function()
        {
            //console.log($(this).val());
              return $.inArray($(this).val(),arr)>-1;
    }).attr("disabled","disabled");

}*/

function delete_transfer(element) {
    /*if(confirm("Delete This Record?"))
    {*/
        var tr = jQuery(element).parent().parent().parent(),
            shareTransferArrayId = tr.find('input[name="shareTransferArrayId"]').val();
        console.log(shareTransferArrayId);

        if(shareTransferArrayId != "")//shareTransferArrayId
        {
            var latestShareTransferInfoArray = JSON.parse(localStorage.getItem("shareTransferArray"));
            if(latestShareTransferInfoArray)
            {
                latestShareTransferInfoArray.splice(shareTransferArrayId, 1); 
                localStorage.shareTransferArray = JSON.stringify(latestShareTransferInfoArray);
                console.log(latestShareTransferInfoArray);
            }
            // $.ajax({
            //     type: "POST",
            //     url: "masterclient/delete_transfer",
            //     data: {"transfer_id":transfer_id}, // <--- THIS IS THE CHANGE
            //     dataType: "json",
            //     async: false,
            //     success: function(response){
            //         if(response.status == 1)
            //         {
            //             toastr.success("Delete Successfully.", "Success");
                        
            //             DisableOptions();
            //             sum_total();
            //         }
            //         else if(response.status == 2)
            //         {
            //             bootbox.alert("This action will result in negative balance of number of share for <a href='masterclient/check_share/"+response.company_code+"/"+response.client_member_share_capital_id+"/"+response.officer_id+"/"+response.field_type+"/"+response.certificate_no+"/"+response.transaction_type+"' target='_blank' class='click_some_member'>some members</a>.", function (result) {
                            
            //             });
            //         }
            //     }               
            // });
        }
        
        tr.closest("tr").remove();
        renderShareTransferInfo();
        //console.log($("#allotment_add > div").length);
        //tr.closest("DIV.tr").remove();

        // if($("#transfer_add > div").length == 1)
        // {
        //     if($('.delete_transfer_button').css('display') == 'block')
        //     {
        //         $('.delete_transfer_button').css('display','none');
        //     }
        // }
        
        
    //}
}

$(document).on('click', '.click_some_member', function (event) {
    bootbox.hideAll();
});

function delete_to(element) {
    /*if(confirm("Delete This Record?"))
    {*/
        var tr = jQuery(element).parent().parent(),
            to_id = tr.find('input[name="to_id[]"]').val();
        //console.log(to_id);

        if(transfer_id != "")
        {
            $.ajax({
                type: "POST",
                url: "masterclient/delete_to",
                data: {"to_id":to_id}, // <--- THIS IS THE CHANGE
                dataType: "json",
                async: false,
                success: function(response){
                    if(response.status == 1)
                    {
                        toastr.success("Delete Successfully.", "Success");
                        tr.closest("DIV.tr").remove();
                    }
                    else if(response.status == 2)
                    {
                        bootbox.alert("This action will result in negative balance of number of share for <a href='masterclient/check_share/"+response.company_code+"/"+response.client_member_share_capital_id+"/"+response.officer_id+"/"+response.field_type+"/"+response.certificate_no+"/"+response.transaction_type+"' target='_blank' class='click_some_member'>some members</a>.", function (result) {
                            
                        });
                    }
                }               
            });
        }
        
        //tr.closest("DIV.tr").remove();
        //console.log($("#allotment_add > div").length);
        if($("#transfer_to_add > div").length == 1)
        {
            if($('.delete_to_button').css('display') == 'block')
            {
                $('.delete_to_button').css('display','none');
            }
        }

        var sum_to = 0;

        $("#transfer_to_add .td #number_of_share_to").each(function(){
            //console.log($(this).val() == '');
            if($(this).val() == '')
            {
                sum_to += 0;
            }
            else
            {
                sum_to += +parseInt(removeCommas($(this).val()));
            }
        });
        $("#total_to").text(addCommas(sum_to));
        
    //}
}

$(document).on('change','#person_id',function(e){
    var num = $(this).parent().parent().parent().attr("num");

    var identification_no = $(this).find(':selected').data('identification_no');
    var name = $(this).find(':selected').data('name');
    var number_of_share = parseInt($(this).find(':selected').data('numberofshare'));
    var amount_share = $(this).find(':selected').data('amountshare');
    var no_of_share_paid = $(this).find(':selected').data('noofsharepaid');
    var amount_paid = $(this).find(':selected').data('amountpaid');
    var field_type = $(this).find(':selected').data('fieldtype');
    var officer_id = $(this).find(':selected').data('officerid');
    var certID = $(this).find(':selected').data('certid');
    //console.log(field_type);
    //console.log(officer_id);

    if($(this).val() == 0)
    {
        $(this).parent().parent().parent().find('#identification_no').val("");
        $(this).parent().parent().parent().find('#name').text("");
        $(this).parent().parent().parent().find('#person_name').val("");
        $(this).parent().parent().parent().find('#current_share').val("");
        $(this).parent().parent().parent().find('#amount_share').val("");
        $(this).parent().parent().parent().find('#no_of_share_paid').val("");
        $(this).parent().parent().parent().find('#amount_paid').val("");
        $(this).parent().parent().parent().find('#number_of_share').text("");
        $(this).parent().parent().parent().find('#share_transfer').val("");
        $(this).parent().parent().parent().find('#certID').val("");
        $(this).parent().parent().parent().find('input[name="officer_id['+num+']"]').val("");
        $(this).parent().parent().parent().find('input[name="field_type['+num+']"]').val("");

        sum_total();
    }
    else
    {
        $(this).parent().parent().parent().find('#identification_no').val(identification_no);
        $(this).parent().parent().parent().find('#name').text(name);
        $(this).parent().parent().parent().find('#person_name').val(name);
        $(this).parent().parent().parent().find('#current_share').val(number_of_share);
        $(this).parent().parent().parent().find('#amount_share').val(amount_share);
        $(this).parent().parent().parent().find('#no_of_share_paid').val(no_of_share_paid);
        $(this).parent().parent().parent().find('#amount_paid').val(amount_paid);
        //$(this).parent().parent().parent().find('#number_of_share').text(addCommas(number_of_share));
        $(this).parent().parent().parent().find('#certID').val(certID);
        $(this).parent().parent().parent().find('#share_transfer').val("");
        $(this).parent().parent().parent().find('input[name="officer_id['+num+']"]').val(officer_id);
        $(this).parent().parent().parent().find('input[name="field_type['+num+']"]').val(field_type);

        console.log(identification_no);

        $('#share_transfer_table td:nth-child(3)').each(function() 
        {
            var transferor_row = $(this);
            var check_transferor_id = $(this).text();
            
            if(check_transferor_id == identification_no)
            {
                var number_of_share_to_transfer = parseInt(transferor_row.parent().children().eq(8).text().replace(/\,/g,''));
                //console.log(number_of_share_to_transfer);

                // if(isNaN(number_of_share_to_transfer))
                // {
                //     number_of_share_to_transfer = 0;
                // }

                number_of_share = number_of_share - number_of_share_to_transfer;
                
                console.log(number_of_share_to_transfer);
            }
        });

        //$(this).parent().parent().parent().find('#current_share').val(number_of_share);
        $(this).parent().parent().parent().find('#number_of_share').text(addCommas(number_of_share));
        
        // var this_row = $(this);
        // $("#loadingmessage").show();
        // $.ajax({
        //     type: "POST",
        //     url: "masterclient/check_current_number_of_share_person",
        //     data: {"transaction_master_id":$("#transaction_trans #transaction_master_id").val(), "officer_id":officer_id, "field_type":field_type, "certID":certID},
        //     dataType: "json",
        //     success: function(responses){
        //         $("#loadingmessage").hide();

        //         if(responses[0]['total_number_of_share'] != null)
        //         {   
        //             var latest_number_share = parseInt(number_of_share) + parseInt(responses[0]['total_number_of_share']);
        //             this_row.parent().parent().parent().find('#number_of_share').text(addCommas(latest_number_share.toString()));
        //             //this_row.parent().parent().parent().find('#current_share').val(latest_number_share.toString());
        //         }
        //         else
        //         {
        //             this_row.parent().parent().parent().find('#number_of_share').text(addCommas(number_of_share));
                    
        //         }
        //     }
        // });
    }
    

    //sum_total();
    DisableOptions();
    /*$('#create_billing_form').formValidation('revalidateField', 'service['+num+']');
    $('#create_billing_form').formValidation('revalidateField', 'invoice_description['+num+']');
    $('#create_billing_form').formValidation('revalidateField', 'amount['+num+']');*/
});

function removeCommas(str) {
    while (str.search(",") >= 0) {
        str = (str + "").replace(',', '');
    }
    return str;
};

$(".merge_from_certificate_no").live('change',function(){
    var elem = $(this);
    elem.parent().parent().find( '.validate_merge_from_cert_no' ).html(" ");
    elem.parent().parent().find( '.validate_edit_allot_from_cert_live' ).html(" ");
});

$(".edit_certificate_no").live('change',function(){
    var elemt = $(this);
    elemt.parent().parent().find( '.validate_edit_from_cert' ).html(" ");
    elemt.parent().parent().find( '.validate_edit_allot_from_cert_live' ).html(" ");
});

$("#number_of_share_to").live('change',function(){
    //console.log($(this).parent().parent().parent().find("#number_of_share").html());
    //console.log($(this).val());
    //console.log($(this).parent().parent().parent().find("#number_of_share").html());
    var input_num = $(this).parent().parent().parent().attr("num_to");
    //console.log("inin");
    var sum_to = 0;

    $("#transfer_to_add .td #number_of_share_to").each(function(){
        //console.log($(this).val() == '');
        if($(this).val() == '')
        {
            sum_to += 0;
        }
        else
        {
            sum_to += +parseInt(removeCommas($(this).val()));
        }
    });
    
    //console.log($("#total_to").text());
    if(sum_to > removeCommas($("#total_from").text()))
    {
        $("#transfer_to_add .td #number_of_share_to").each(function(){
        //console.log($(this).val() == '');
            $(this).val("");
            $("#total_to").text("");
        });
    }
    else
    {
        $("#total_to").text(addCommas(sum_to));
    }

    $('#transfer_form').formValidation('revalidateField', 'number_of_share_to['+input_num+']');
    /*$("#total").text()*/
    /*if($(this).parent().parent().parent().find("#number_of_share_to").html() != "")
    {
        if(parseInt($(this).val()) > parseInt($(this).parent().parent().parent().find("#number_of_share").html()))
        {
            $(this).val($(this).parent().parent().parent().find("#number_of_share").html());
        }
        sum_total();
    }*/
    
});

$("#share_transfer").live('change',function(){
    //console.log($(this).parent().parent().parent().find("#number_of_share").html());
    //console.log($(this).val());
    //console.log($(this).parent().parent().parent().find("#number_of_share").html());
    if($(this).parent().parent().parent().find("#number_of_share").html() != "")
    {
        if(parseInt(removeCommas($(this).val())) > parseInt(removeCommas($(this).parent().parent().parent().find("#number_of_share").html())))
        {
            $(this).val($(this).parent().parent().parent().find("#number_of_share").html());
        }
        sum_total();
    }
    
});


function sum_total(){
    var sum = 0;
    $(".transfer .td #share_transfer").each(function(){
        //console.log($(this).val() == '');
        if($(this).val() == '')
        {
            sum += 0;
        }
        else
        {
            sum += +parseInt(removeCommas($(this).val()));
        }
    });
    //$(".total").val(sum);
    $("#total_from").text(addCommas(sum));
    $(".number_of_share_to").val(addCommas(sum));
    $("#total_to").text(addCommas(sum));
   /*console.log($("#total_from"));*/
    //console.log("total==="+(sum > 0));
    //console.log(!transfer);
    if(sum > 0)
    {
        // $('#table_transfer_to').show();
        // $('#total_share_transfer_to').show();
        // $('.to').show();
        if(!transfer)
        {
            //console.log($("#total_to").text());
            //console.log($("#total_from").text());
            if(parseInt($("#total_to").text()) > parseInt($("#total_from").text()))
            {
                $("#total_to").text("0");
                $("#transfer_to_add .td #number_of_share_to").each(function(){
                //console.log($(this).val() == '');
                    $(this).val("");
                });
            }
        }
        else
        {
            var sum_to = 0;

            if(parseInt($("#total_to").text()) > parseInt($("#total_from").text()))
            {
                $("#total_to").text("0");
                $("#transfer_to_add .td #number_of_share_to").each(function(){
                //console.log($(this).val() == '');
                    $(this).val("");
                });
            }

            $("#transfer_to_add .td #number_of_share_to").each(function(){
                if($(this).val() == '')
                {
                    sum_to += 0;
                }
                else
                {
                    sum_to += +parseInt(removeCommas($(this).val()));
                }
            });
            $("#total_to").text(addCommas(sum_to));
        }
        
    }
    // else
    // {
    //     $('#table_transfer_to').hide();
    //     $('.to').hide();
    //     $('#total_share_transfer_to').hide();
    // }
}

$("#get_person_id").live('change',function(){
    var allotment_frm = $(this);
    //console.log(allotment_frm.val());
    //console.log($(this).parent().parent().parent().attr("num"));
    var input_num = allotment_frm.parent().parent().parent().attr("num_to");
    $("#loadingTransfer").show();
    $.ajax({
        type: "POST",
        url: "masterclient/get_person",
        data: {"identification_register_no":allotment_frm.val()}, // <--- THIS IS THE CHANGE
        dataType: "json",
        success: function(responses){
            //console.log(allotment_frm.parent().parent().parent().find('input[name="officer_id['+input_num+']"]'));
            //console.log(allotment_frm.parent().parent('div').find('input[name="officer_id['+input_num+']"]'));
            //console.log(responses);
            $("#loadingTransfer").hide();
            if(responses.info != null)
            {
                var response = responses.info;
                var check_same_person = false;

                $('.person_id').each(function() {
                    //console.log($(this).find("option:selected").text() == response['identification_no']);
                    if($(this).find("option:selected").text() == response['identification_no'])
                    {
                        check_same_person = true;
                        return false;
                    }
                    else
                    {
                        check_same_person = false;
                    }

                });
                console.log(check_same_person);

                if(!check_same_person)
                {
                    if(responses.status == 1)
                    {
                        if(response)
                        {
                            //$(this).parent().parent().parent().find('#name').text(name);
                            allotment_frm.parent().parent().parent().find('#name_to').text(response['name']);
                            allotment_frm.parent().parent().parent().find('#to_person_name').val(response['name']);
                            allotment_frm.parent().parent().parent().find('input[name="to_officer_id['+input_num+']"]').val(response['id']);
                            allotment_frm.parent().parent().parent().find('input[name="to_field_type['+input_num+']"]').val(response['field_type']);
                            //console.log(allotment_frm.parent().parent().parent().find('input[name="to_field_type['+input_num+']"]'));
                            /*if(response['name'] != undefined)
                            {*/
                            allotment_frm.parent().parent('div').find('a#add_person_link').attr('hidden',"true");
            /*                allotment_frm.parent().parent('div').find('input[name="name['+input_num+']"]').attr('readonly', true);*/
                            //}
                            
                        }
                        else
                        {
                            allotment_frm.parent().parent('div').find('a#add_person_link').removeAttr('hidden');
                            allotment_frm.parent().parent().parent().find('#name_to').text("");
                            allotment_frm.parent().parent().parent().find('#to_person_name').val("");
                            allotment_frm.parent().parent().parent().find('input[name="to_officer_id['+input_num+']"]').val("");
                            allotment_frm.parent().parent().parent().find('input[name="to_field_type['+input_num+']"]').val("");
                        }
                    }
                    else
                    {
                        allotment_frm.parent().parent('div').find('input[name="name['+input_num+']"]').val("");
                        allotment_frm.parent().parent().parent().find(".name .help-block").remove();
                        
                        toastr.error("This person is an auditor for this company.", "Error");
                    }
                    $('#transfer_form').formValidation('revalidateField', 'id_to['+input_num+']');
                }
                else
                {
                    allotment_frm.parent().parent().parent().find('#get_person_id').val("");
                    allotment_frm.parent().parent().parent().find('#name_to').text("");
                    allotment_frm.parent().parent().parent().find('#to_person_name').val("");
                    allotment_frm.parent().parent().parent().find('input[name="to_officer_id['+input_num+']"]').val("");
                    allotment_frm.parent().parent().parent().find('input[name="to_field_type['+input_num+']"]').val("");
                    toastr.error("Cannot transfer to the same person.", "Error");
                    $('#transfer_form').formValidation('revalidateField', 'id_to['+input_num+']');

                }
                //$('#allotment_form').formValidation('revalidateField', 'name['+input_num+']');
            }
            else
            {
                allotment_frm.parent().parent('div').find('a#add_person_link').removeAttr('hidden');
                allotment_frm.parent().parent().parent().find('#name_to').text("");
                allotment_frm.parent().parent().parent().find('#to_person_name').val("");
                allotment_frm.parent().parent().parent().find('input[name="to_officer_id['+input_num+']"]').val("");
                allotment_frm.parent().parent().parent().find('input[name="to_field_type['+input_num+']"]').val("");
            }
        }               
    });
    // console.log($(this).val());
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
var shareTransferArray = [];
$(document).on('click',"#addShareTransferInfo",function(e){
    e.preventDefault();

    $('#transfer_form').formValidation('revalidateField', 'id[0]');
    $('#transfer_form').formValidation('revalidateField', 'share_transfer[0]');
    $('#transfer_form').formValidation('revalidateField', 'consideration[0]');
    $('#transfer_form').formValidation('revalidateField', 'id_to[0]');

    var fv   = $('#transfer_form').data('formValidation'), // FormValidation instance
    $transfer_tab = $('#transfer_form').find('.tab-pane').eq(1); // The current tab

    // Validate the container
    fv.validateContainer($transfer_tab);

    var isValidTransferStep = fv.isValidContainer($transfer_tab);
    //console.log($('#transfer_form').find('.tab-pane').eq(2));
    if (isValidTransferStep === false || isValidTransferStep === null) 
    {
        // Do not jump to the target tab
        //console.log("in");
        $('#transfer_form').find('ul.pager li.next').removeClass('disabled');
        return true;
    }
    else
    {
        //localStorage.removeItem("shareTransferArray");
        var shareTransferArrayId = $('#transfer_id').val();
        console.log(shareTransferArrayId);
        if(shareTransferArrayId != "")
        {
            var latestShareTransferInfoArray = JSON.parse(localStorage.getItem("shareTransferArray"));
            if(latestShareTransferInfoArray)
            {
                latestShareTransferInfoArray.splice(shareTransferArrayId, 1);
                localStorage.shareTransferArray = JSON.stringify(latestShareTransferInfoArray);
                console.log(latestShareTransferInfoArray);
            }
        }

        var $inputs = $('#transfer_member :input');
        var values = {};
        values["sharetype"] = $("#class").find(':selected').data('sharetype');
        values["otherclass"] = $("#class").find(':selected').data('otherclass');
        values["currency"] = $("#class").find(':selected').data('currency');
        $inputs.each(function() {
            values[this.name] = $(this).val();
        });
        //shareTransferArray.push(values);
        //console.log(JSON.stringify(localStorage.getItem("shareTransferArray")));
        if(localStorage.shareTransferArray)
        {
            var latestShareTransferArray = JSON.parse(localStorage.getItem("shareTransferArray"));
            //console.log(latestShareTransferArray);
            latestShareTransferArray.push(values);
            localStorage.shareTransferArray = JSON.stringify(latestShareTransferArray);
            //localStorage.shareTransferArray.push(values);
        }
        else
        {
            shareTransferArray.push(values);
            localStorage.shareTransferArray = JSON.stringify(shareTransferArray);
        }
        //console.log(JSON.parse(localStorage.getItem("shareTransferArray")));
        $('#person_id option[value="0"]').attr('selected', true);
        $('#transfer_id').val("");
        $("#consideration").val("");
        $("#get_person_id").val("");
        $("#share_transfer").val("");
        $("#number_of_share_to").val("");
        $("#number_of_share").text("");
        $("#name_to").text("");
        $("#total_from").text("0");
        $("#total_to").text("0");

        
    }

    renderShareTransferInfo();
});

renderShareTransferInfo();
function renderShareTransferInfo()
{
    var latestShareTransferInfoArray = JSON.parse(localStorage.getItem("shareTransferArray"));

    console.log(latestShareTransferInfoArray);
    $(".member_info_for_each_company").remove();
    if(latestShareTransferInfoArray)
    {
        var row_id = 0;
        for(var i = 0; i < latestShareTransferInfoArray.length; i++)
        {
            row_id++;
            // if(latestShareTransferInfoArray[i]["sharetype"] == "Others")
            // {
            //     var sharetype = "(" +transaction_share_transfer[i]["other_class"]+ ")" ;
            // }
            // else
            // {
            var sharetype = latestShareTransferInfoArray[i]["sharetype"];
            //}

            $b=""; 
            $b += '<tr class="member_info_for_each_company">';
            $b += '<td style="text-align: right;width:10px">'+row_id+'</td>';
            $b += '<td class="hidden"><input type="text" name="shareTransferArrayId" value="'+i+'"></td>';
            
            $b += '<td>'+latestShareTransferInfoArray[i]["identification_no[0]"]+'</td>';
            $b += '<td><a class="amber" href="javascript:void(0)" data-toggle="tooltip" data-trigger="hover" onclick="editMemberShareTransfer('+i+')">'+latestShareTransferInfoArray[i]["person_name[0]"]+'</a></td>';
            $b += '<td>'+latestShareTransferInfoArray[i]["id_to[0]"]+'</td>';
            $b += '<td>'+latestShareTransferInfoArray[i]["to_person_name[0]"]+'</td>';
            
            $b += '<td>'+sharetype+'</td>';
            // $b += '<td style="text-align:center">'+latestShareTransferInfoArray[i]["to_new_certificate_no"]+'</td>';
            $b += '<td style="text-align:center">'+latestShareTransferInfoArray[i]["currency"]+'</td>';
            $b += '<td style="text-align:right">'+latestShareTransferInfoArray[i]["number_of_share_to[0]"]+'</td>';
            $b += '<td><div class="action"><button type="button" class="btn btn-primary delete_transfer_button" onclick="delete_transfer(this)" style="display: block;">Delete</button></div></th>';
            $b += '</tr>';

            $("#share_transfer_table").append($b);
        }
    }
}

function editMemberShareTransfer(arrayID)
{
    console.log(arrayID);
    var latestShareTransferInfoArray = JSON.parse(localStorage.getItem("shareTransferArray"));

    $.each(latestShareTransferInfoArray, function(index, value) {
        console.log(index);
        if(index == arrayID)
        {
            var identification_no = value["identification_no[0]"];
            var number_of_share = parseInt(value["current_share[0]"]);
            //FROM
            $("#form0").find('#transfer_id').val(arrayID);
            $("#form0").find('#identification_no').val(value["identification_no[0]"]);
            $("#form0").find('#name').text(value["person_name[0]"]);
            $("#form0").find('#person_name').val(value["person_name[0]"]);
            $("#form0").find('#current_share').val(number_of_share);
            $("#form0").find('#amount_share').val(value["amount_share[0]"]);
            $("#form0").find('#no_of_share_paid').val(value["no_of_share_paid[0]"]);
            $("#form0").find('#amount_paid').val(value["amount_paid[0]"]);
            //$(this).parent().parent().parent().find('#number_of_share').text(addCommas(number_of_share));
            $("#form0").find('#certID').val(value["certID[0]"]);
            $("#form0").find('#share_transfer').val("");
            $("#form0").find('#officer_id').val(value["officer_id[0]"]);
            $("#form0").find('#field_type').val(value["field_type[0]"]);

            console.log($("#form0").find('#officer_id').val());
            console.log($("#form0").find('#field_type').val());
            console.log($("#form0").find('#transfer_id').val());

            $('#share_transfer_table td:nth-child(3)').each(function() 
            {
                var transferor_row = $(this);
                var check_transferor_id = $(this).text();
                var shareTransferArrayId = $(this).parent().find('input[name="shareTransferArrayId"]').val();
                console.log(shareTransferArrayId);
                console.log(arrayID);

                if(check_transferor_id == identification_no && shareTransferArrayId != arrayID)
                {
                    var number_of_share_to_transfer = parseInt(transferor_row.parent().children().eq(8).text().replace(/\,/g,''));

                    number_of_share = number_of_share - number_of_share_to_transfer;
                    
                    console.log(number_of_share_to_transfer);
                }
            });

            //$("#form0").find('#current_share').val(number_of_share);
            $("#form0").find('#number_of_share').text(addCommas(number_of_share));
            $('#person_id option[value="'+value["officer_id[0]"]+'"]').attr('selected', true);
            $("#form0").find('#share_transfer').val(value["number_of_share_to[0]"]);
            $("#form0").find('#consideration').val(value["consideration[0]"]);

            //TO
            $("#form_to0").find('#to_cert_id').val("");
            $("#form_to0").find('#to_id').val("");
            $("#form_to0").find('#to_officer_id').val(value["to_officer_id[0]"]);
            $("#form_to0").find('#to_field_type').val(value["to_field_type[0]"]);
            $("#form_to0").find('#previous_new_cert').val("");
            $("#form_to0").find('#previous_cert').val("");

            $("#form_to0").find('#get_person_id').val(value["id_to[0]"]);
            $("#form_to0").find('#name_to').text(value["to_person_name[0]"]);
            $("#form_to0").find('input[name="to_person_name[0]"]').val(value["to_person_name[0]"]);
            $("#form_to0").find('#to_person_name').text(value["to_person_name[0]"]);
            $("#form_to0").find('#number_of_share_to').val(value["number_of_share_to[0]"]);

            $('#transfer_form').formValidation('revalidateField', 'id[0]');
            $('#transfer_form').formValidation('revalidateField', 'share_transfer[0]');
            $('#transfer_form').formValidation('revalidateField', 'consideration[0]');
            $('#transfer_form').formValidation('revalidateField', 'id_to[0]');

            sum_total();
        }
    });
}

function assignAllomentPeople(alloment_people)
{
    //console.log(transfer);
    //console.log(alloment_people);
    allotmentPeople = alloment_people;
    if(!transfer)
    {
        $("DIV.transfer").remove();
        $("DIV.to").remove();
        addFirstFrom();

        // $('#table_transfer_to').hide();
        // $('.to').hide();
        // $('#total_share_transfer_to').hide();
        if(alloment_people != null)
        {
            for(var i = 0; i < alloment_people.length; i++)
            {
                var option = $('<option />');
                /*console.log(currency_id);*/
                
                option.attr('data-name', (alloment_people[i]["company_name"]!=null ? alloment_people[i]["company_name"] : (alloment_people[i]["name"]!=null ? alloment_people[i]["name"] : alloment_people[i]["client_company_name"])));
                option.attr('data-identification_no', (alloment_people[i]["identification_no"]!=null ? alloment_people[i]["identification_no"] : (alloment_people[i]["register_no"]!=null ? alloment_people[i]["register_no"] : alloment_people[i]["registration_no"])));
                option.attr('data-numberofshare', alloment_people[i]['number_of_share']);
                option.attr('data-amountshare', alloment_people[i]['amount_share']);
                option.attr('data-noofsharepaid', alloment_people[i]['no_of_share_paid']);
                option.attr('data-amountpaid', alloment_people[i]['amount_paid']);
                option.attr('data-officerid', alloment_people[i]['officer_id']);
                option.attr('data-fieldtype', alloment_people[i]['field_type']);
                option.attr('data-certID', alloment_people[i]['id']);
                option.attr('value', alloment_people[i]['officer_id']).text((alloment_people[i]["identification_no"]!=null ? (alloment_people[i]["identification_no"] + " - " + alloment_people[i]["name"]) : ((alloment_people[i]["register_no"]!=null) ? (alloment_people[i]["register_no"] + " - " + alloment_people[i]["company_name"]) : (alloment_people[i]["registration_no"] + " - " + alloment_people[i]["client_company_name"]))) + " (" +addCommas(alloment_people[i]['number_of_share'])+ ") ");

                $("#person_id").append(option); 
            }
        }
    }
    else
    {
        for(var i = 0; i < transfer.length; i++)
        {
            if(0 > transfer[i]["number_of_share"])
            {
                $a0=""; 
                $a0 += '<div class="tr editing transfer" method="post" name="form'+i+'" id="form'+i+'" num="'+i+'">';
                $a0 += '<div class="hidden"><input type="text" class="form-control" name="company_code" value="'+transfer[i]["company_code"]+'"/></div>';
                $a0 += '<div class="hidden"><input type="text" class="form-control cert_id" name="cert_id[]" id="cert_id" value="'+transfer[i]["cert_id"]+'"/></div>';
                $a0 += '<div class="hidden"><input type="text" class="form-control" name="transfer_id[]" id="transfer_id" value="'+transfer[i]["id"]+'"/></div>';
                $a0 += '<div class="hidden"><input type="text" class="form-control" name="officer_id['+i+']" id="officer_id" value="'+(transfer[i]["officer_id"]!=null ? transfer[i]["officer_id"] : (transfer[i]["officer_company_id"]!=null ? transfer[i]["officer_company_id"] : transfer[i]["client_company_id"]))+'"/></div>';
                $a0 += '<div class="hidden"><input type="text" class="form-control" name="field_type['+i+']" id="field_type" value="'+(transfer[i]["officer_field_type"]!=null ? transfer[i]["officer_field_type"] : (transfer[i]["officer_company_field_type"]!=null ? transfer[i]["officer_company_field_type"] : transfer[i]["client_company_field_type"]))+'"/></div>';
                /*$a += '<div class="td">'+$count_allotment+'</div>';*/
                $a0 += '<div class="td"><div class="transfer_group mb-md" style="width: 400px"><input type="hidden" class="form-control" name="certID['+i+']" value="" id="certID"/><input type="hidden" class="form-control" name="identification_no['+i+']" value="" id="identification_no"/><input type="hidden" class="form-control" name="person_name['+i+']" value="" id="person_name"/><select class="form-control person_id" style="text-align:right;width: 100%;" name="id['+i+']" id="person_id"><option value="0" >Select ID</option></select></div></div>';
                /*$a0 += '<div class="td"><div class="transfer_group mb-md"><input type="text" name="name['+0+']" class="form-control" value=""/></div></div>';
                $a0 += '<div class="td"><div class="transfer_group mb-md"><input type="text" name="number_of_share['+0+']" class="form-control" value=""/></div></div>';*/
                //$a0 += '<div class="td"><div class="transfer_group mb-md" id="name" style="width: 200px; text-align:left"></div><input type="hidden" class="form-control" name="person_name['+i+']" value="" id="person_name"/></div>';
                $a0 += '<div class="td"><div style="text-align:right;width: 200px" class="transfer_group mb-md" id="number_of_share"></div><input type="hidden" class="form-control" name="current_share['+i+']" value="" id="current_share"/><input type="hidden" class="form-control" name="amount_share['+i+']" value="" id="amount_share"/><input type="hidden" class="form-control" name="no_of_share_paid['+i+']" value="" id="no_of_share_paid"/><input type="hidden" class="form-control" name="amount_paid['+i+']" value="" id="amount_paid"/></div>';
                $a0 += '<div class="td"><div class="transfer_group mb-md" style="width: 200px"><input type="text" class="numberdes form-control share_transfer" style="text-align:right;" name="share_transfer['+i+']" value="'+addCommas(Math.abs(transfer[i]["number_of_share"]))+'" id="share_transfer" pattern="^[0-9,]+$"/></div></div>';
                $a0 += '<div class="td"><div class="transfer_group mb-md" style="width: 200px"><input type="text" class="numberdes form-control consideration" style="text-align:right;" name="consideration['+i+']" value="'+addCommas(Math.abs(transfer[i]["consideration"]))+'" id="consideration" pattern="^[0-9,.]+$"/></div></div>';
                //$a0 += '<div class="td action"><button type="button" class="btn btn-primary delete_transfer_button" onclick="delete_transfer(this)" style="display: none;">Delete</button></div>';
                $a0 += '</div>';

                $("#transfer_add").append($a0); 

                if($("#transfer_add > div").length > 1)
                {
                    $('.delete_transfer_button').css('display','block');
                }

                for(var a = 0; a < alloment_people.length; a++)
                {
                    var option = $('<option />');
                    /*console.log(currency_id);*/

                    option.attr('data-name', (alloment_people[a]["company_name"]!=null ? alloment_people[a]["company_name"] : (alloment_people[a]["name"]!=null ? alloment_people[a]["name"] : alloment_people[a]["client_company_name"])));
                    option.attr('data-numberofshare', alloment_people[a]['number_of_share']);
                    option.attr('data-amountshare', alloment_people[a]['amount_share']);
                    option.attr('data-noofsharepaid', alloment_people[a]['no_of_share_paid']);
                    option.attr('data-amountpaid', alloment_people[a]['amount_paid']);
                    option.attr('data-officerid', alloment_people[a]['officer_id']);
                    option.attr('data-fieldtype', alloment_people[a]['field_type']);
                    option.attr('data-certID', alloment_people[i]['id']);
                    option.attr('value', alloment_people[a]['officer_id']).text((alloment_people[a]["identification_no"]!=null ? (alloment_people[a]["identification_no"] + " - " + alloment_people[a]["name"]) : ((alloment_people[a]["register_no"]!=null) ? (alloment_people[a]["register_no"] + " - " + alloment_people[a]["company_name"]) : (alloment_people[a]["registration_no"] + " - " + alloment_people[a]["client_company_name"]))) + " (" +addCommas(alloment_people[a]['number_of_share'])+ ") ");

                    if(transfer)
                    {
                        if(transfer[i]["officer_id"] != null && alloment_people[a]['officer_id'] == transfer[i]["officer_id"])
                        {
                            $("#form"+i+" #name").text((alloment_people[a]["company_name"]!=null ? alloment_people[a]["company_name"] : (alloment_people[a]["name"]!=null ? alloment_people[a]["name"] : alloment_people[a]["client_company_name"])));
                            // $("#form"+i+" #number_of_share").text(parseInt(alloment_people[a]['number_of_share']) - parseInt(transfer[i]['number_of_share']));
                            $("#form"+i+" #number_of_share").text(addCommas(parseInt(alloment_people[a]['number_of_share'])));

                            $("#form"+i+" #person_name").val((alloment_people[a]["company_name"]!=null ? alloment_people[a]["company_name"] : (alloment_people[a]["name"]!=null ? alloment_people[a]["name"] : alloment_people[a]["client_company_name"])));
                            /*$("#form"+i+" #current_share").val(addCommas(parseInt(alloment_people[a]['number_of_share']) - parseInt(transfer[i]['number_of_share'])));
                            $("#form"+i+" #amount_share").val(parseFloat(alloment_people[a]['amount_share']) - parseFloat(transfer[i]['amount_share']));
                            $("#form"+i+" #no_of_share_paid").val(parseInt(alloment_people[a]['no_of_share_paid']) - parseInt(transfer[i]['no_of_share_paid']));
                            $("#form"+i+" #amount_paid").val(parseFloat(alloment_people[a]['amount_paid']) - parseFloat(transfer[i]['amount_paid']));*/

                            $("#form"+i+" #current_share").val(addCommas(parseInt(alloment_people[a]['number_of_share'])));
                            $("#form"+i+" #amount_share").val(parseFloat(alloment_people[a]['amount_share']));
                            $("#form"+i+" #no_of_share_paid").val(parseInt(alloment_people[a]['no_of_share_paid']));
                            $("#form"+i+" #amount_paid").val(parseFloat(alloment_people[a]['amount_paid']));
                            option.attr('selected', 'selected');
                        }
                    }

                    $("#form"+i+" #person_id").append(option); 
                }

                $('#transfer_form').formValidation('addField', 'id['+i+']', id);
                $('#transfer_form').formValidation('addField', 'share_transfer['+i+']', share_transfer);
                $('#transfer_form').formValidation('addField', 'consideration['+i+']', consideration);
            }
            else if(transfer[i]["number_of_share"] > 0)
            {
                //console.log(transfer);
                $atoe =""; 
                $atoe += '<div class="tr editing to" method="post" name="form_to'+i+'" id="form_to'+i+'" num_to="'+i+'">';
                $atoe += '<div class="hidden"><input type="text" class="form-control" name="company_code" value="'+transfer[i]["company_code"]+'"/></div>';
                $atoe += '<div class="hidden"><input type="text" class="form-control to_cert_id" name="to_cert_id[]" id="to_cert_id" value="'+transfer[i]["cert_id"]+'"/></div>';
                $atoe += '<div class="hidden"><input type="text" class="form-control" name="to_id[]" id="to_id" value="'+transfer[i]["id"]+'"/></div>';
                $atoe += '<div class="hidden"><input type="text" class="form-control" name="to_officer_id['+i+']" id="to_officer_id" value="'+(transfer[i]["officer_id"]!=null ? transfer[i]["officer_id"] : (transfer[i]["officer_company_id"]!=null ? transfer[i]["officer_company_id"] : transfer[i]["client_company_id"]))+'"/></div>';
                $atoe += '<div class="hidden"><input type="text" class="form-control" name="to_field_type['+i+']" id="to_field_type" value="'+(transfer[i]["officer_field_type"]!=null ? transfer[i]["officer_field_type"] : (transfer[i]["officer_company_field_type"]!=null ? transfer[i]["officer_company_field_type"] : transfer[i]["client_company_field_type"]))+'"/></div>';
                $atoe += '<div class="hidden"><input type="text" class="form-control" name="previous_new_cert['+i+']" id="previous_new_cert" value="'+transfer[i]["new_certificate_no"]+'"/></div>';
                $atoe += '<div class="hidden"><input type="text" class="form-control" name="previous_cert['+i+']" id="previous_cert" value="'+transfer[i]["certificate_no"]+'"/></div>';

                /*$a += '<div class="td">'+$count_allotment+'</div>';*/
                $atoe += '<div class="td"><div class="transfer_group"><input type="text" name="id_to['+i+']" class="form-control get_person_id" value="'+(transfer[i]["identification_no"]!=null ? transfer[i]["identification_no"] : (transfer[i]["register_no"]!=null ? transfer[i]["register_no"] : transfer[i]["registration_no"]))+'" id="get_person_id" style="text-transform:uppercase;"/></div><div style="height:16px;" ><a class="" href="'+ url + 'personprofile/add" style="cursor:pointer" id="add_person_link" target="_blank" hidden><div style="cursor:pointer;">Click here to <span style="font-weight:bold">Add Entity</span></div></a></div></div>';
                /*$a0 += '<div class="td"><div class="transfer_group mb-md"><input type="text" name="name['+0+']" class="form-control" value=""/></div></div>';
                $a0 += '<div class="td"><div class="transfer_group mb-md"><input type="text" name="number_of_share['+0+']" class="form-control" value=""/></div></div>';*/
                $atoe += '<div class="td"><div class="transfer_group mb-md" id="name_to" style="width: 200px; text-align:left">'+(transfer[i]["company_name"]!=null ? transfer[i]["company_name"] : (transfer[i]["name"]!=null ? transfer[i]["name"] : transfer[i]["client_company_name"]))+'</div><input type="hidden" class="form-control" name="to_person_name['+i+']" value="'+(transfer[i]["company_name"]!=null ? transfer[i]["company_name"] : (transfer[i]["name"]!=null ? transfer[i]["name"] : transfer[i]["client_company_name"]))+'" id="to_person_name"/></div>';
                $atoe += '<div class="td"><div class="transfer_group mb-md" style="width: 200px"><input type="text" style="text-align:right;" class="numberdes form-control number_of_share_to" name="number_of_share_to['+i+']" value="'+addCommas(transfer[i]["number_of_share"])+'" id="number_of_share_to" pattern="^[0-9,]+$" readonly="true"/></div></div>';
                /*$ato += '<div class="td"><div class="transfer_group mb-md"><input type="text" class="form-control" name="certificate['+0+']" value="" id="certificate"/></div></div>';*/
                //$atoe += '<div class="td action"><button type="button" class="btn btn-primary delete_to_button" onclick="delete_to(this)" style="display: none;">Delete</button></div>';
                /*if (transfer.length == 1)
                {
                    $atoe += '<div class="td action"><button type="button" class="btn btn-primary delete_allotment_button" onclick="delete_allotment(this)" style="display: none">Delete</button></div>';
                }
                else
                {
                    $atoe += '<div class="td action"><button type="button" class="btn btn-primary delete_allotment_button" onclick="delete_allotment(this)" style="display: block">Delete</button></div>';
                }   */
                $atoe += '</div>';

                $("#transfer_to_add").append($atoe); 

                if($("#transfer_to_add > div").length > 1)
                {
                    $('.delete_to_button').css('display','block');
                }

                $('#transfer_form').formValidation('addField', 'id_to['+i+']', id_to);
                //$('#transfer_form').formValidation('addField', 'number_of_share_to['+i+']', number_of_share_to);
            }

            if(access_right_member_module == "read" || client_status != "1")
            {
                $(".person_id").attr("disabled", true);
                $(".share_transfer").attr("disabled", true);
                $(".get_person_id").attr("disabled", true);
                $(".number_of_share_to").attr("disabled", true);
            }
            
        }
        //console.log("kkkk");
        sum_total();

        

        
    }
}

$("#cancel_transfer").on("click", function() {
    //console.log("inin");
    window.close();
});

function confirmTransfer(transfer_object)
{
    console.log(transfer_object);
    var total_existing = 0,
        total_transfer = 0,
        total_new = 0,
        new_share = 0,
        total_new_share_tranfer = 0,
        total_new_share_tranfer_to = 0,
        num_from_person = 0;

    //From
    transfer_object["field_type"] = transfer_object["field_type"].filter(function(val){return val});
    transfer_object["officer_id"] = transfer_object["officer_id"].filter(function(val){return val});
    transfer_object["share_transfer"] = transfer_object["share_transfer"].filter(function(val){return val});
    transfer_object["person_name"] = transfer_object["person_name"].filter(function(val){return val});
    transfer_object["current_share"] = transfer_object["current_share"].filter(function(val){return val});
    transfer_object["cert_id"] = transfer_object["certID"].filter(function(val){return val});
    //To
    transfer_object["to_officer_id"] = transfer_object["to_officer_id"].filter(function(val){return val});
    transfer_object["to_field_type"] = transfer_object["to_field_type"].filter(function(val){return val}); 
    transfer_object["to_person_name"] = transfer_object["to_person_name"].filter(function(val){return val});
    transfer_object["number_of_share_to"] = transfer_object["number_of_share_to"].filter(function(val){return val});
    //transfer_object["certificate"] = transfer_object["certificate"].filter(function(val){return val});   
    transfer_object["id_to"] = transfer_object["id_to"].filter(function(val){return val});
    
    $(".confirm_from_add_row").empty();
    $(".confirm_from_add_row2").empty();
    $(".confirm_from_add_row3").empty();
    $(".confirm_from_add_row4").empty();
    $(".confirm_from_add_row5").empty();

    $(".confirm_transfer_add_row").empty();
    $(".confirm_transfer_add_row2").empty();
    $(".confirm_transfer_add_row3").empty();
    $(".confirm_transfer_add_row4").empty();
    $(".confirm_transfer_add_row5").empty();
    $(".confirm_allotment_add_row5").empty();

    if(transfer_object["officer_id"] != undefined) 
    {   
        //console.log(transfer_object);
        num_from_person = transfer_object["officer_id"].length;
        for(var i = 0; i < transfer_object["officer_id"].length; i++)
        {
            total_existing +=  parseInt(removeCommas(transfer_object["current_share"][i]));
            new_share = parseInt(removeCommas(transfer_object["current_share"][i]))-parseInt(removeCommas(transfer_object["share_transfer"][i]));
            total_new_share_tranfer += new_share;
            var first_cert = true;
            // console.log(parseInt(transfer_object["current_share"][i]));
            // console.log(parseInt(removeCommas(transfer_object["share_transfer"][i])));
            // console.log(total_new_share_tranfer);
            if(!transfer)
            {
                !function (i) {
                    $.ajax({
                        type: "POST",
                        url: "masterclient/get_the_previous_certificate",
                        data: {"client_member_share_capital_id":transfer_object["client_member_share_capital_id"], "company_code":transfer_object["company_code"], "officer_id": transfer_object["officer_id"][i], "field_type": transfer_object["field_type"][i], "transaction_type": "Allotment"}, // <--- THIS IS THE CHANGE
                        dataType: "json",
                        async: false,
                        success: function(response){
                            var total_number_of_share = 0;
                            certificate_info = response;
                            //console.log(response);
                            $b=""; 
                            $b += '<tr class="confirm_from_add_row">';
                            $b += '<td style="width:50px !important;">'+(i+1)+'</td>';
                            $b += '<td>'+transfer_object["person_name"][i]+'</td>';
                            $b += '<td>'+addCommas(transfer_object["current_share"][i])+'<input type="hidden" name="previous_cert_id" value="'+transfer_object["cert_id"][i]+'"/></td>';
                            $b += '<td>('+addCommas(transfer_object["share_transfer"][i])+')</td>';
                            $b += '<td>'+((new_share != 0)?addCommas(new_share): "-")+'</td>';
                            /*$b += '<td></td>';*/
                            $b += '</tr>';

                            $b += '</tr>';
                            $b += '<tr class="confirm_from_add_row2">';
                            $b += '<th style="width:50px !important;"></th>';
                            $b += '<th colspan="2">Transfer Number of Shares</th>';
                            $b += "<th colspan='2'>Certificate No.</th>";
                            /*$b += '<th colspan="2">Certificate No.</th>';*/
                            /*<button type='button' class='btn btn-primary mergeFrom mergeFrom"+i+"' id='mergeFrom' style='float: right;' onclick='mergeBothFromAllotment("+i+")'>Merge</button><button type='button' class='btn btn-primary cancelMergeFrom"+i+"' id='cancelMergeFromAllotment' style='float: right;display:none' onclick='cancelMergeBothFromAllotment("+i+")'>Cancel</button></th>*/
                            $b += '</tr>';

                            $("#confirm_transfer_add").append($b);

                            // if(certificate_info != null)
                            // {
                            //     //console.log(certificate_info);
                            //     //console.log(certificate_info.length);
                            //     for(var p = 0; p < certificate_info.length; p++)
                            //     {
                            //         total_number_of_share += parseInt(certificate_info[p]["number_of_share"]);
                            //         $b1=""; 
                            //         $b1 += '<tr class="confirm_from_add_row3 merge_from_item'+i+'" style="display: none">';
                            //         $b1 += '<td style="width:50px !important;"></td>';
                            //         $b1 += '<td colspan="2">'+addCommas(certificate_info[p]["number_of_share"])+'</td>';
                            //         $b1 += '<td colspan="2">'+certificate_info[p]["certificate_no"]+'</div></td>';
                            //         $b1 += '</tr>';

                            //         $("#confirm_transfer_add").append($b1);
                            //     }
                            // }
                            total_number_of_share = parseInt(transfer_object["current_share"][i]);
                            $b2 ="";
                            $b2 += '<tr class="confirm_from_add_row4 merge_from_item'+i+'" style="display: none">';
                            $b2 += '<td style="width:50px !important;"></td>';
                            $b2 += '<td colspan="2">-'+addCommas(transfer_object["share_transfer"][i])+'</td>';
                            $b2 += '<td colspan="2"><div class="transfer_group"><div style="width:120px"><input type="text" class="hidden form-control cert_id" name="cert_id[]" id="cert_id" value="'+transfer_object["cert_id"][i]+'"/><input type="text" name="from_certificate_no['+i+']" class="form-control from_certificate_no'+i+' edit_certificate_no" value="" disabled/></div><div class="validate_merge_from_cert"></div></div></td>';

                            $b2 += '<tr class="confirm_from_add_row5 merge_from_total'+i+'">';
                            $b2 += '<td style="width:50px !important;"></td>';
                            $b2 += '<td colspan="2">'+addCommas((total_number_of_share + parseInt(-(removeCommas(transfer_object["share_transfer"][i])))))+'</td>';
                            if(new_share != 0)
                            {
                                $b2 += '<td colspan="2"><div class="transfer_group"><div style="width:120px"><input type="text" class="hidden form-control cert_id" name="cert_id[]" id="cert_id" value="'+transfer_object["cert_id"][i]+'"/><input type="text" name="merge_from_certificate_no['+i+']" class="form-control merge_from_certificate_no'+i+' merge_from_certificate_no check_cert_in_live" value=""/></div><div class="validate_merge_from_cert_no"></div><div class="validate_edit_allot_from_cert_live"></div></div></td>';
                            }
                            else
                            {
                                $b2 += '<td colspan="2"><div class="transfer_group"><div style="width:120px"><input type="text" class="hidden form-control cert_id" name="cert_id[]" id="cert_id" value="'+transfer_object["cert_id"][i]+'"/><input type="text" name="merge_from_certificate_no['+i+']" class="form-control merge_from_certificate_no'+i+' merge_from_certificate_no check_cert_in_live" value="NA" readonly/></div><div class="validate_merge_from_cert_no"></div><div class="validate_edit_allot_from_cert_live"></div></div></td>';
                            }
                            $b2 += '</tr>';

                            $("#confirm_transfer_add").append($b2);

                            $('#transfer_form').formValidation('addField', 'merge_from_certificate_no['+i+']', certificate);
                        }
                    });
                } (i);
            }
            else
            {
                !function (i) {
                    $.ajax({
                        type: "POST",
                        url: "masterclient/get_the_previous_certificate",
                        data: {"client_member_share_capital_id":transfer_object["client_member_share_capital_id"], "company_code":transfer_object["company_code"], "officer_id": transfer_object["officer_id"][i], "field_type": transfer_object["field_type"][i], "transaction_type": "Allotment"}, // <--- THIS IS THE CHANGE
                        dataType: "json",
                        async: false,
                        success: function(response){
                            //var total_number_of_share = 0;
                            certificate_info = response;

                            $b=""; 
                            $b += '<tr class="confirm_from_add_row">';
                            $b += '<td style="width:50px !important;">'+(i+1)+'</td>';
                            $b += '<td>'+transfer_object["person_name"][i]+'</td>';
                            $b += '<td>'+addCommas(transfer_object["current_share"][i])+'<input type="hidden" name="previous_cert_id" value="'+transfer_object["cert_id"][i]+'"/></td>';
                            $b += '<td>('+addCommas(transfer_object["share_transfer"][i])+')</td>';
                            $b += '<td>'+((new_share != 0)?addCommas(new_share): "-")+'</td>';
                            /*$b += '<td></td>';*/
                            $b += '</tr>';

                            $b += '</tr>';
                            $b += '<tr class="confirm_from_add_row2">';
                            $b += '<th style="width:50px !important;"></th>';
                            $b += '<th colspan="2">Transfer Number of Shares</th>';
                            //$b += "<th colspan='2'>Certificate No.<button type='button' class='btn btn-primary mergeFrom"+i+"' id='mergeFrom' style='float: right;' onclick='mergeBothFromAllotment("+i+")'>Merge</button><button type='button' class='btn btn-primary cancelMergeFrom"+i+"' id='cancelMergeFromAllotment' style='float: right;display:none' onclick='cancelMergeBothFromAllotment("+i+")'>Cancel</button></th>";
                            $b += '<th colspan="2">Certificate No.</th>';
                            $b += '</tr>';

                            $("#confirm_transfer_add").append($b);

                            /*if(certificate_info != null)
                            {
                                console.log(certificate_info);
                                console.log(certificate_info.length);
                                for(var p = 0; p < certificate_info.length; p++)
                                {*/
                                    /*total_number_of_share += parseInt(certificate_info[p]["number_of_share"]);
                                    $b1=""; 
                                    $b1 += '<tr class="confirm_from_add_row3 merge_from_item'+i+'">';
                                    $b1 += '<td style="width:50px !important;"></td>';
                                    $b1 += '<td colspan="2">'+certificate_info[p]["number_of_share"]+'</td>';
                                    $b1 += '<td colspan="2">'+certificate_info[p]["certificate_no"]+'</div></td>';
                                    $b1 += '</tr>';

                                    $("#confirm_transfer_add").append($b1);*/
                                /*}
                            /*}*/
                            //console.log(transfer);
                            
                            $b2 ="";
                            /*$b2 += '<tr class="confirm_from_add_row4 merge_from_item'+i+'">';
                            $b2 += '<td style="width:50px !important;"></td>';
                            $b2 += '<td colspan="2">-'+transfer_object["share_transfer"][i]+'</td>';
                            $b2 += '<td colspan="2"><div class="transfer_group"><div style="width:120px"><input type="text" name="from_certificate_no['+i+']" class="form-control from_certificate_no'+i+' edit_certificate_no" value="" disabled/></div></div></td>';
                            $b2 += '</tr>';*/

                            $b2 += '<tr class="confirm_from_add_row5 merge_from_total'+i+'">';
                            $b2 += '<td style="width:50px !important;"></td>';
                            $b2 += '<td colspan="2">'+addCommas((parseInt(removeCommas(transfer_object["current_share"][i])) - parseInt(removeCommas(transfer_object["share_transfer"][i]))))+'</td>';

                            for(var p = 0; p < transfer.length; p++)
                            {
                                if((transfer_object["person_name"][i] == transfer[p]["name"] && 0 > parseInt(transfer[p]["number_of_share"])) || (transfer_object["person_name"][i] == transfer[p]["company_name"] && 0 > parseInt(transfer[p]["number_of_share"]))  || (transfer_object["person_name"][i] == transfer[p]["client_company_name"] && 0 > parseInt(transfer[p]["number_of_share"])))
                                {
                                    console.log(new_share);
                                    first_cert = false;
                                    $b2 += '<td colspan="2"><div class="transfer_group"><div style="width:120px"><input type="text" class="hidden form-control cert_id" name="cert_id[]" id="cert_id" value="'+transfer_object["cert_id"][i]+'"/><input type="text" name="merge_from_certificate_no['+i+']" class="form-control merge_from_certificate_no'+i+' merge_from_certificate_no " value="'+((new_share == 0)? "NA" : (new_share != 0 && transfer[p]["certificate_no"] == "NA")?"": transfer[p]["certificate_no"])+'" '+((new_share != 0)?"": "readonly")+'/></div><div class="validate_merge_from_cert_no"></div><div class="validate_edit_allot_from_cert_live"></div></div></td>';
                                    //break;
                                }
                                //else if((transfer_object["person_name"][i] != transfer[p]["name"] && 0 > parseInt(transfer[p]["number_of_share"])) || (transfer_object["person_name"][i] != transfer[p]["company_name"] && 0 > parseInt(transfer[p]["number_of_share"])))
                                //{
                                    //$b2 += '<td colspan="2"><div class="transfer_group"><div style="width:120px"><input type="text" class="hidden form-control cert_id" name="cert_id[]" id="cert_id" value="'+transfer_object["cert_id"][i]+'"/><input type="text" name="merge_from_certificate_no['+i+']" class="form-control merge_from_certificate_no'+i+' merge_from_certificate_no check_cert_in_live" value=""/></div><div class="validate_merge_from_cert_no"></div><div class="validate_edit_allot_from_cert_live"></div></div></td>';
                                    //break;
                                //}
                            }

                            if(first_cert)
                            {
                                console.log(new_share);
                                if(new_share != 0)
                                {
                                    $b2 += '<td colspan="2" class="first_cert"><div class="transfer_group"><div style="width:120px"><input type="text" class="hidden form-control cert_id" name="cert_id[]" id="cert_id" value="'+transfer_object["cert_id"][i]+'"/><input type="text" name="merge_from_certificate_no['+i+']" class="form-control merge_from_certificate_no'+i+' merge_from_certificate_no check_cert_in_live" value=""/></div><div class="validate_merge_from_cert_no"></div><div class="validate_edit_allot_from_cert_live"></div></div></td>';
                                }
                                else
                                {
                                    $b2 += '<td colspan="2" class="first_cert"><div class="transfer_group"><div style="width:120px"><input type="text" class="hidden form-control cert_id" name="cert_id[]" id="cert_id" value="'+transfer_object["cert_id"][i]+'"/><input type="text" name="merge_from_certificate_no['+i+']" class="form-control merge_from_certificate_no'+i+' merge_from_certificate_no check_cert_in_live" value="NA" readonly/></div><div class="validate_merge_from_cert_no"></div><div class="validate_edit_allot_from_cert_live"></div></div></td>';
                                }
                            }

                            $b2 += '</tr>';

                            $("#confirm_transfer_add").append($b2);

                            $('#transfer_form').formValidation('addField', 'merge_from_certificate_no['+i+']', certificate);
            
                        }
                    });
                } (i);
            }
        }
    }

    if(transfer_object["to_officer_id"] != undefined) 
    {   
        for(var i = 0; i < transfer_object["to_officer_id"].length; i++)
        {
            num_from_person += 1;
            total_new_share_tranfer_to +=  parseInt(removeCommas(transfer_object["number_of_share_to"][i]));
            
            /*if(!transfer)
            {*/
                !function (i) {
                    $.ajax({
                        type: "POST",
                        url: "masterclient/get_the_previous_certificate_for_to",
                        data: {"client_member_share_capital_id":transfer_object["client_member_share_capital_id"], "company_code":transfer_object["company_code"], "officer_id": transfer_object["to_officer_id"][i], "field_type": transfer_object["to_field_type"][i], "transaction_type": "Allotment", "date": transfer_object["date"], "member_share_id": transfer_object["to_id"][i]}, // <--- THIS IS THE CHANGE
                        dataType: "json",
                        async: false,
                        success: function(response){
                            //console.log(response);
                            var total_number_of_share = 0;
                            //certificate_info = response;

                            if(response != null)
                            {
                                certificate_info = response.certificate_data;
                                merge_info = response.merge_status;
                            }
                            else
                            {
                                certificate_info = null;
                                merge_info = 0;
                            }

                            $b=""; 
                            $b += '<tr class="confirm_transfer_add_row">';
                            $b += '<td>'+num_from_person+'</td>';
                            $b += '<td>'+transfer_object["to_person_name"][i]+'</td>';
                            $b += '<td> - </td>';
                            $b += '<td>'+addCommas(transfer_object["number_of_share_to"][i])+'</td>';
                            $b += '<td>'+addCommas(transfer_object["number_of_share_to"][i])+'</td>';
                            /*$b += '<td>'+transfer_object["certificate"][i]+'</td>';*/
                            $b += '</tr>';

                            $b += '</tr>';
                            $b += '<tr class="confirm_transfer_add_row2">';
                            $b += '<th style="width:50px !important;"></th>';
                            $b += '<th colspan="2">Transfer Number of Shares</th>';
                            //$b += '<th colspan="2">Certificate No.'+ ((transfer_object["previous_cert"][i] != transfer_object["previous_new_cert"][i])?"<input type='text' class='hidden merge_status"+i+"' name='merge_status["+i+"]' value='1'/>":(certificate_info != null && transfer == null || certificate_info != null && transfer != null && certificate_info.length > 0 && merge_info == 0)?"<input type='text' class='merge_status"+i+" hidden' name='merge_status["+i+"]' value='0'/><button type='button' class='btn btn-primary mergeTransfer"+i+" mergeTransfer' id='mergeTransfer' style='float: right;' onclick='mergeBothAllotment("+i+")'>Merge</button><button type='button' class='btn btn-primary cancelMergeTransfer"+i+"' id='cancelMergeTransfer' style='float: right;display:none' onclick='cancelMergeBothAllotment("+i+")'>Cancel</button>":(certificate_info == null && transfer == null  || certificate_info == null && transfer != null)?"<input type='text' class='hidden merge_status"+i+"' name='merge_status["+i+"]' value='0'/>":"<input type='text' class='hidden merge_status"+i+"' name='merge_status["+i+"]' value='1'/><button type='button' class='btn btn-primary mergeTransfer"+i+" mergeTransfer' id='mergeTransfer' style='float: right;display:none' onclick='mergeBothAllotment("+i+")'>Merge</button><button type='button' class='btn btn-primary cancelMergeTransfer"+i+"' id='cancelMergeTransfer' style='float: right;' onclick='cancelMergeBothAllotment("+i+")'>Cancel</button>") +'</th>';
                            $b += '<th colspan="2">Certificate No.</th>';
                            $b += '</tr>';

                            $("#confirm_transfer_add").append($b);

                            /*if(certificate_info != null)
                            {
                                console.log(certificate_info);
                                console.log(certificate_info.length);
                                for(var p = 0; p < certificate_info.length; p++)
                                {
                                    total_number_of_share += parseInt(certificate_info[p]["number_of_share"]);
                                    $b1=""; 
                                    $b1 += '<tr class="confirm_transfer_add_row3 merge_transfer_item'+i+'">';
                                    $b1 += '<td style="width:50px !important;"></td>';
                                    $b1 += '<td colspan="2">'+addCommas(certificate_info[p]["number_of_share"])+'</td>';
                                    $b1 += '<td colspan="2">'+certificate_info[p]["certificate_no"]+'</div></td>';
                                    $b1 += '</tr>';

                                    $("#confirm_transfer_add").append($b1);
                                }
                            }*/
                           /* console.log(certificate_info);
                            console.log(transfer);*/
                            if((certificate_info != null && transfer == null) || (certificate_info != null && transfer != null && certificate_info.length > 0 && merge_info == 0))
                            {
                                // console.log(certificate_info);
                                // console.log(certificate_info.length);
                                for(var p = 0; p < certificate_info.length; p++)
                                {
                                    total_number_of_share += parseInt(certificate_info[p]["number_of_share"]);
                                    $b1=""; 
                                    $b1 += '<tr class="confirm_transfer_add_row3 merge_transfer_item'+i+'">';
                                    $b1 += '<td style="width:50px !important;"></td>';
                                    $b1 += '<td colspan="2">'+addCommas(certificate_info[p]["number_of_share"])+'</td>';
                                    $b1 += '<td colspan="2">'+certificate_info[p]["certificate_no"]+'</div></td>';
                                    $b1 += '</tr>';

                                    $("#confirm_transfer_add").append($b1);
                                }
                            }
                            else
                            {//console.log(certificate_info);
                                if(certificate_info != null)
                                {
                                    for(var p = 0; p < certificate_info.length; p++)
                                    {
                                        if(certificate_info[p]["cert_status"] == '2' && transfer_object["previous_cert"][i] ==  certificate_info[p]["new_certificate_no"])
                                        { /*styele="display:none;"*/
                                            total_number_of_share += parseInt(certificate_info[p]["number_of_share"]);
                                            $b1=""; 
                                            $b1 += '<tr class="confirm_transfer_add_row3 merge_transfer_item'+i+'" style="display:none">';
                                            $b1 += '<td style="width:50px !important;"></td>';
                                            $b1 += '<td colspan="2">'+addCommas(certificate_info[p]["number_of_share"])+'</td>';
                                            $b1 += '<td colspan="2">'+certificate_info[p]["certificate_no"]+'</div></td>';
                                            $b1 += '</tr>';

                                            $("#confirm_transfer_add").append($b1);
                                        }
                                        
                                    }
                                }
                            }

                            $b2="";
                            /*$b2 += '<tr class="confirm_transfer_add_row4 merge_transfer_item'+i+'">';
                            $b2 += '<td style="width:50px !important;"></td>';
                            $b2 += '<td colspan="2">'+addCommas(transfer_object["number_of_share_to"][i])+'</td>';
                            $b2 += '<td colspan="2"><div class="transfer_group"><div style="width:120px"><input type="text" class="hidden form-control cert_id" name="cert_id[]" id="cert_id" value="'+transfer_object["to_cert_id"][i]+'"/><input type="text" name="to_certificate_no['+i+']" class="form-control to_certificate_no'+i+' edit_certificate_no check_cert_in_live" value=""/></div><div class="validate_edit_from_cert"></div><div class="validate_edit_allot_from_cert_live"></div></div></td>';
                            $b2 += '</tr>';*/

                            if(certificate_info == null && transfer == null || certificate_info != null && transfer == null || certificate_info == null && transfer != null || certificate_info != null && transfer != null && certificate_info.length > 0 && merge_info == 0)
                            {
                                $b2 += '<tr class="confirm_transfer_add_row4 merge_transfer_item'+i+'">';
                                $b2 += '<td style="width:50px !important;"></td>';
                                $b2 += '<td colspan="2">'+addCommas(transfer_object["number_of_share_to"][i])+'</td>';
                            }
                            else
                            {
                                $b2 += '<tr class="confirm_transfer_add_row4 merge_transfer_item'+i+'" style="display:none">';
                                $b2 += '<td style="width:50px !important;"></td>';
                                $b2 += '<td colspan="2">'+addCommas(transfer_object["number_of_share_to"][i])+'</td>';
                            }

                            if(transfer)
                            {
                                //console.log(transfer);
                                //console.log(transfer_object);
                                if(!edit_cert && transfer_object["to_id"][i] != "")
                                {
                                    //console.log("outoutout");
                                        if(certificate_info == null && transfer == null || certificate_info != null && transfer == null || certificate_info == null && transfer != null || certificate_info == null && transfer == null || certificate_info != null && transfer != null && certificate_info.length > 0 && merge_info == 0)
                                        {
                                            for(var f = 0; f < transfer.length; f++)
                                            {
                                                if(transfer_object["to_id"][i] == transfer[f]["id"])
                                                {
                                                    $b2 += '<td colspan="2"><div class="transfer_group"><div style="width:120px"><input type="text" class="hidden form-control cert_id" name="cert_id[]" id="cert_id" value="'+transfer_object["to_cert_id"][i]+'"/><input type="text" name="to_certificate_no['+i+']" class="form-control to_certificate_no'+i+' edit_certificate_no check_cert_in_live" value="'+ ((transfer[f]["certificate_no"]!=null)?transfer[f]["certificate_no"] : "") +'"/></div><div class="validate_edit_from_cert"></div><div class="validate_edit_allot_from_cert_live"></div></div></td>';
                                                }
                                            }
                                        }
                                        else
                                        {
                                            if(certificate_info != null)
                                            {
                                                for(var b = 0; b < certificate_info.length; b++)
                                                {
                                                    if(transfer_object["to_cert_id"][i] == certificate_info[b]["id"])
                                                    {
                                                        //console.log(transfer_object["to_cert_id"][i]);
                                                        //console.log(certificate_info[b]["id"]);
                                                        $b2 += '<td colspan="2"><div class="transfer_group"><div style="width:120px"><input type="text" class="hidden form-control cert_id" name="cert_id[]" id="cert_id" value=""/><input type="text" name="to_certificate_no['+i+']" class="form-control to_certificate_no'+i+' edit_certificate_no check_cert_in_live" value=""/></div><div class="validate_edit_from_cert"></div><div class="validate_edit_allot_from_cert_live"></div></div></td>';
                                                    }
                                                }
                                            }
                                        }
                                    

                                    
                                }
                                else
                                {   
                                    //console.log("inininin");
                                    if(transfer_object["to_id"][i] == "")
                                    {
                                        $b2 += '<td colspan="2"><div class="transfer_group"><div style="width:120px"><input type="text" class="hidden form-control cert_id" name="cert_id[]" id="cert_id" value="'+transfer_object["to_cert_id"][i]+'"/><input type="text" name="to_certificate_no['+i+']" class="form-control to_certificate_no'+i+' edit_certificate_no check_cert_in_live" value=""/></div><div class="validate_edit_from_cert"></div><div class="validate_edit_allot_from_cert_live"></div></div></td>';
                                    }
                                    else
                                    {
                                        //console.log(allotment[i]["certificate_no"]);
                                        $b2 += '<td colspan="2"><div class="transfer_group"><div style="width:120px"><input type="text" class="hidden form-control cert_id" name="cert_id[]" id="cert_id" value="'+transfer_object["to_cert_id"][i]+'"/><input type="text" name="to_certificate_no['+i+']" class="form-control to_certificate_no'+i+' edit_certificate_no check_cert_in_live" value="'+ ((transfer[i]["certificate_no"]!=null)?transfer[i]["certificate_no"] : "") +'"/></div><div class="validate_edit_from_cert"></div><div class="validate_edit_allot_from_cert_live"></div></div></td>';
                                    }
                                }
                                
                            }
                            else
                            {
                                $b2 += '<td colspan="2"><div class="transfer_group"><div style="width:120px"><input type="text" class="hidden form-control cert_id" name="cert_id[]" id="cert_id" value="'+transfer_object["to_cert_id"][i]+'"/><input type="text" name="to_certificate_no['+i+']" class="form-control to_certificate_no'+i+' edit_certificate_no check_cert_in_live" value=""/></div><div class="validate_edit_from_cert"></div><div class="validate_edit_allot_from_cert_live"></div></div></td>';
                            }
                            $b2 += '</tr>';


                            /*$b2 += '<tr class="confirm_allotment_add_row5 merge_transfer_total'+i+'" style="display:none">';
                            $b2 += '<td style="width:50px !important;"></td>';
                            $b2 += '<td colspan="2">'+addCommas((total_number_of_share + parseInt(removeCommas(transfer_object["number_of_share_to"][i]))))+'</td>';
                            $b2 += '<td colspan="2"><div class="transfer_group"><div style="width:120px"><input type="hidden" name="merge_to_number_of_share['+i+']" class="form-control merge_to_number_of_share'+i+'" value="'+(total_number_of_share + parseInt(removeCommas(transfer_object["number_of_share_to"][i])))+'"/><input type="text" name="merge_to_certificate_no['+i+']" class="form-control merge_to_certificate_no'+i+'" value=""/></div></div></td>';
                            $b2 += '</tr>';*/


                            if(certificate_info != null && transfer == null || certificate_info == null && transfer == null  || certificate_info != null && transfer != null && certificate_info.length > 0 && merge_info == 0)
                            {
                                $b2 += '<tr class="confirm_transfer_add_row5 merge_transfer_total'+i+'" style="display:none">';
                                $b2 += '<td style="width:50px !important;"></td>';
                                $b2 += '<td colspan="2">'+addCommas((total_number_of_share + parseInt(removeCommas(transfer_object["number_of_share_to"][i]))))+'</td>';
                                $b2 += '<td colspan="2"><div class="transfer_group"><div style="width:120px"><input type="text" class="hidden form-control cert_id" name="cert_id[]" id="cert_id" value="'+transfer_object["to_cert_id"][i]+'"/><input type="hidden" name="merge_to_number_of_share['+i+']" class="form-control merge_to_number_of_share'+i+'" value="'+(total_number_of_share + parseInt(removeCommas(transfer_object["number_of_share_to"][i])))+'"/><input type="hidden" name="previous_merge_cert_num['+i+']" value=""/><input type="text" name="merge_to_certificate_no['+i+']" class="form-control merge_to_certificate_no'+i+' merge_to_certificate_no check_cert_in_live" value=""/><input type="hidden" name="latest_merge_cert_no['+i+']" class="form-control latest_merge_cert_no'+i+' latest_merge_cert_no check_cert_in_live" value=""/></div><div class="validate_edit_allot_from_cert"></div><div class="validate_edit_allot_from_cert_live"></div></div></td>';
                                $b2 += '</tr>';
                            }
                            else
                            {
                                if(certificate_info != null)
                                {
                                    var diff_num_of_share = 0, latest_cert, latest_num_of_share;

                                    if(transfer_object["previous_cert"][i] != transfer_object["previous_new_cert"][i])
                                    {
                                        for(var r = 0; r < certificate_info.length; r++)
                                        {
                                            if(transfer_object["previous_new_cert"][i] == certificate_info[r]["certificate_no"])
                                            {
                                                latest_cert = certificate_info[r]["certificate_no"];
                                                latest_num_of_share = parseInt(certificate_info[r]["number_of_share"]);
                                                latest_cert_id = certificate_info[r]["id"];
                                                //console.log(latest_cert);
                                                //console.log(latest_num_of_share);
                                            }
                                        }

                                        for(var g = 0; g < certificate_info.length; g++)
                                        {
                                            if(transfer_object["to_cert_id"][i] == certificate_info[g]["id"])
                                            {
                                                $b2 += '<tr class="confirm_transfer_add_row5 merge_transfer_total'+i+'">';
                                                $b2 += '<td style="width:50px !important;"></td>';
                                                /*if(allotment_object["cert_id"][i] == certificate_info[r]["id"])
                                                {*/
                                                    //console.log(transfer_object["number_of_share_to"][i]);
                                                    //console.log(certificate_info[g]["number_of_share"]);

                                                    if(parseInt(transfer_object["number_of_share_to"][i]) > parseInt(certificate_info[g]["number_of_share"]))
                                                    {
                                                        diff_num_of_share = parseInt(transfer_object["number_of_share_to"][i]) - parseInt(certificate_info[g]["number_of_share"]);
                                                        $b2 += '<td colspan="2">'+addCommas((diff_num_of_share + latest_num_of_share))+'</td>';
                                                        $b2 += '<td colspan="2"><div class="transfer_group"><div style="width:120px"><input type="text" class="hidden form-control cert_id" name="cert_id[]" id="cert_id" value="'+latest_cert_id+'"/><input type="hidden" name="merge_to_number_of_share['+i+']" class="form-control merge_to_number_of_share'+i+'" value="'+(diff_num_of_share + parseInt(transfer_object["number_of_share_to"][i]))+'"/><input type="hidden" name="previous_merge_cert_num['+i+']" value="'+ ((latest_cert!=null)?latest_cert : "") +'"/><input type="hidden" name="merge_to_certificate_no['+i+']" class="form-control merge_to_certificate_no'+i+' merge_to_certificate_no check_cert_in_live" value=""/><input type="text" name="latest_merge_cert_no['+i+']" class="form-control latest_merge_cert_no'+i+' latest_merge_cert_no check_cert_in_live" value="'+ ((latest_cert!=null)?latest_cert : "") +'"/></div><div class="validate_edit_allot_from_cert"></div><div class="validate_edit_allot_from_cert_live"></div></div></td>';
                                                    }
                                                    else if(parseInt(transfer_object["number_of_share_to"][i]) < parseInt(certificate_info[g]["number_of_share"]))
                                                    {
                                                        diff_num_of_share = parseInt(transfer_object["number_of_share_to"][i]) - parseInt(certificate_info[g]["number_of_share"]);
                                                        $b2 += '<td colspan="2">'+addCommas((diff_num_of_share + latest_num_of_share))+'</td>';
                                                        $b2 += '<td colspan="2"><div class="transfer_group"><div style="width:120px"><input type="text" class="hidden form-control cert_id" name="cert_id[]" id="cert_id" value="'+latest_cert_id+'"/><input type="hidden" name="merge_to_number_of_share['+i+']" class="form-control merge_to_number_of_share'+i+'" value="'+(diff_num_of_share + parseInt(transfer_object["number_of_share_to"][i]))+'"/><input type="hidden" name="previous_merge_cert_num['+i+']" value="'+ ((latest_cert!=null)?latest_cert : "") +'"/><input type="hidden" name="merge_to_certificate_no['+i+']" class="form-control merge_to_certificate_no'+i+' merge_to_certificate_no check_cert_in_live" value=""/><input type="text" name="latest_merge_cert_no['+i+']" class="form-control latest_merge_cert_no'+i+' latest_merge_cert_no check_cert_in_live" value="'+ ((latest_cert!=null)?latest_cert : "") +'"/></div><div class="validate_edit_allot_from_cert"></div><div class="validate_edit_allot_from_cert_live"></div></div></td>';
                                                    }
                                                    else if(parseInt(transfer_object["number_of_share_to"][i]) == parseInt(certificate_info[g]["number_of_share"]))
                                                    {
                                                        diff_num_of_share = 0;
                                                        $b2 += '<td colspan="2">'+addCommas((diff_num_of_share + latest_num_of_share))+'</td>';
                                                        $b2 += '<td colspan="2"><div class="transfer_group"><div style="width:120px"><input type="text" class="hidden form-control cert_id" name="cert_id[]" id="cert_id" value="'+latest_cert_id+'"/><input type="hidden" name="merge_to_number_of_share['+i+']" class="form-control merge_to_number_of_share'+i+'" value="'+(diff_num_of_share + parseInt(transfer_object["number_of_share_to"][i]))+'"/><input type="hidden" name="previous_merge_cert_num['+i+']" value="'+ ((latest_cert!=null)?latest_cert : "") +'"/><input type="hidden" name="merge_to_certificate_no['+i+']" class="form-control merge_to_certificate_no'+i+' merge_to_certificate_no check_cert_in_live" value=""/><input type="text" name="latest_merge_cert_no['+i+']" class="form-control latest_merge_cert_no'+i+' latest_merge_cert_no check_cert_in_live" value="'+ ((latest_cert!=null)?latest_cert : "") +'"/></div><div class="validate_edit_allot_from_cert"></div><div class="validate_edit_allot_from_cert_live"></div></div></td>';
                                                    }
                                                    
                                                //}
                                                
                                                $b2 += '</tr>';
                                            }
                                        }
                                    }
                                    else
                                    {
                                        for(var r = 0; r < certificate_info.length; r++)
                                        {
                                            if(transfer_object["to_cert_id"][i] == certificate_info[r]["id"])
                                            {
                                                $b2 += '<tr class="confirm_transfer_add_row5 merge_transfer_total'+i+'">';
                                                $b2 += '<td style="width:50px !important;"></td>';
                                                $b2 += '<td colspan="2">'+addCommas((total_number_of_share + parseInt(transfer_object["number_of_share_to"][i])))+'</td>';
                                                $b2 += '<td colspan="2"><div class="transfer_group"><div style="width:120px"><input type="text" class="hidden form-control cert_id" name="cert_id[]" id="cert_id" value="'+transfer_object["to_cert_id"][i]+'"/><input type="hidden" name="merge_to_number_of_share['+i+']" class="form-control merge_to_number_of_share'+i+'" value="'+(total_number_of_share + parseInt(transfer_object["number_of_share_to"][i]))+'"/><input type="hidden" name="previous_merge_cert_num['+i+']" value="'+ ((certificate_info[r]["certificate_no"]!=null)?certificate_info[r]["certificate_no"] : "") +'"/><input type="text" name="merge_to_certificate_no['+i+']" class="form-control merge_to_certificate_no'+i+' merge_to_certificate_no check_cert_in_live" value="'+ ((certificate_info[r]["certificate_no"]!=null)?certificate_info[r]["certificate_no"] : "") +'"/></div><div class="validate_edit_allot_from_cert"></div><div class="validate_edit_allot_from_cert_live"></div></div></td>';
                                                $b2 += '</tr>';
                                            }
                                        }
                                    }
                                }
                            }

                            $("#confirm_transfer_add").append($b2);

                            $('#transfer_form').formValidation('addField', 'to_certificate_no['+i+']', certificate);
                            $('#transfer_form').formValidation('addField', 'merge_to_certificate_no['+i+']', certificate);
                        }
                    });
                } (i);
            //}
            /*else
            {


                $b=""; 
                $b += '<tr class="confirm_transfer_add_row">';
                $b += '<td>'+num_from_person+'</td>';
                $b += '<td>'+transfer_object["to_person_name"][i]+'</td>';
                $b += '<td> - </td>';
                $b += '<td>'+addCommas(transfer_object["number_of_share_to"][i])+'</td>';
                $b += '<td>'+addCommas(transfer_object["number_of_share_to"][i])+'</td>';
                $b += '</tr>';

                $b += '</tr>';
                $b += '<tr class="confirm_transfer_add_row2">';
                $b += '<th style="width:50px !important;"></th>';
                $b += '<th colspan="2">Transfer Number of Shares</th>';
                //$b += '<th colspan="2">Certificate No.'+ ((certificate_info != null)?"<button type='button' class='btn btn-primary mergeTransfer"+i+"' id='mergeAllotment' style='float: right;' onclick='mergeBothAllotment("+i+")'>Merge</button><button type='button' class='btn btn-primary cancelMergeTransfer"+i+"' id='cancelMergeAllotment' style='float: right;display:none' onclick='cancelMergeBothAllotment("+i+")'>Cancel</button>":" ") +'</th>';
                $b += '<th colspan="2">Certificate No.</th>';
                $b += '</tr>';

                $("#confirm_transfer_add").append($b);


                $b2="";
                $b2 += '<tr class="confirm_transfer_add_row4 merge_transfer_item'+i+'">';
                $b2 += '<td style="width:50px !important;"></td>';
                $b2 += '<td colspan="2">'+addCommas(transfer_object["number_of_share_to"][i])+'</td>';
                for(var p = 0; p < transfer.length; p++)
                {
                    if((transfer_object["person_name"][i] = transfer[p]["name"] && parseInt(transfer[p]["number_of_share"]) > 0) || (transfer_object["person_name"][i] = transfer[p]["company_name"] && parseInt(transfer[p]["number_of_share"]) > 0))
                    {
                         $b2 += '<td colspan="2"><div class="transfer_group"><div style="width:120px"><input type="text" class="hidden form-control cert_id" name="cert_id[]" id="cert_id" value="'+transfer_object["to_cert_id"][i]+'"/><input type="text" name="to_certificate_no['+i+']" class="form-control to_certificate_no'+i+' edit_certificate_no" value="'+transfer[p]["certificate_no"]+'"/></div><div class="validate_edit_from_cert"></div><div class="validate_edit_allot_from_cert_live"></div></div></td>';
                    }
                }
                $b2 += '</tr>';

                $b2 += '<tr class="confirm_allotment_add_row5 merge_transfer_total'+i+'" style="display:none">';
                $b2 += '<td style="width:50px !important;"></td>';
                $b2 += '<td colspan="2">'+addCommas((parseInt(transfer_object["number_of_share_to"][i])))+'</td>';
                $b2 += '<td colspan="2"><div class="transfer_group"><div style="width:120px"><input type="hidden" name="merge_to_number_of_share['+i+']" class="form-control merge_to_number_of_share'+i+'" value="'+(parseInt(transfer_object["number_of_share_to"][i]))+'"/><input type="text" name="merge_to_certificate_no['+i+']" class="form-control merge_to_certificate_no'+i+'" value=""/></div></div></td>';
                $b2 += '</tr>';

                $("#confirm_transfer_add").append($b2);

                $('#transfer_form').formValidation('addField', 'to_certificate_no['+i+']', certificate);
                $('#transfer_form').formValidation('addField', 'merge_to_certificate_no['+i+']', certificate);

            }*/
        }
    }

    $b3=""; 
    $b3 += '<tr class="confirm_transfer_add_row">';
    $b3 += '<td></td>';
    $b3 += '<td style="font-weight: bold;">Total</td>';
    $b3 += '<td>'+addCommas(total_existing)+'</td>';
    $b3 += '<td> - </td>';
    $b3 += '<td>'+addCommas((parseInt(total_new_share_tranfer) + parseInt(total_new_share_tranfer_to)))+'</td>';
    //$b += '<td></td>';
    $b3 += '</tr>';

    if(access_right_member_module == "read" || client_status != "1")
    {
        $(".person_id").attr("disabled", true);
        $(".share_transfer").attr("disabled", true);
        $(".get_person_id").attr("disabled", true);
        $(".number_of_share_to").attr("disabled", true);

        $(".edit_certificate_no").attr("disabled", true);
        $(".from_certificate_no").attr("disabled", true);
        $(".merge_from_certificate_no").attr("disabled", true);
        $(".mergeFrom").attr("disabled", true);
        $(".mergeTransfer").attr("disabled", true);
    }

    $("#confirm_transfer_add").append($b3);
}

function mergeBothFromAllotment(table_index) {
    /*document.getElementsByClassName("merge_item"+table_index+"").style.display = 'none';
    document.getElementsByClassName("merge_item_total"+table_index+"").style.display = 'table-row';*/
    
    $('.merge_from_item'+table_index+'').css('display','none');
   
    $('.merge_from_total'+table_index+'').css('display','table-row');
    $('.mergeFrom'+table_index+'').css('display','none');
    $('.cancelMergeFrom'+table_index+'').css('display','block');
    $('.from_certificate_no'+table_index+'').val("");
    //$('.from_certificate_no'+table_index+'').removeAttr("disabled");
}

function cancelMergeBothFromAllotment(table_index) {
    $('.merge_from_item'+table_index+'').css('display','table-row');
   
    $('.merge_from_total'+table_index+'').css('display','none');
    $('.mergeFrom'+table_index+'').css('display','block');
    $('.cancelMergeFrom'+table_index+'').css('display','none');
    $('.merge_from_certificate_no'+table_index+'').val("");
    //$('.from_certificate_no'+table_index+'').prop("disabled", true);
}

function mergeBothAllotment(table_index) {
    /*document.getElementsByClassName("merge_item"+table_index+"").style.display = 'none';
    document.getElementsByClassName("merge_item_total"+table_index+"").style.display = 'table-row';*/
    
    $('.merge_transfer_item'+table_index+'').css('display','none');
   
    $('.merge_transfer_total'+table_index+'').css('display','table-row');
    $('.mergeTransfer'+table_index+'').css('display','none');
    $('.cancelMergeTransfer'+table_index+'').css('display','block');
    $('.merge_status'+table_index+'').val("1");
    $('.to_certificate_no'+table_index+'').val("");
    

}

function cancelMergeBothAllotment(table_index) {
    $('.merge_transfer_item'+table_index+'').css('display','table-row');
   
    $('.merge_transfer_total'+table_index+'').css('display','none');
    $('.mergeTransfer'+table_index+'').css('display','block');
    $('.cancelMergeTransfer'+table_index+'').css('display','none');
    $('.merge_status'+table_index+'').val("0");
    $('.merge_to_certificate_no'+table_index+'').val("");
}