var transfer_info_coll;

$(document).on('click',"#transfer_member_Add",function() {
    transfer_info_coll = document.getElementsByClassName("transfer_coll");

    if(transfer_info_coll.length > 0)
    {
        $count_transfer = transfer_info_coll.length + 1;
    }
    else
    {
        $count_transfer = 0;
    }

    $count_transfer++;
    $field_index = $count_transfer;
    $a="";

    $a += '<div class="tr editing transfer transfer_coll" method="post" name="form'+$count_transfer+'" id="form'+$count_transfer+'" num="'+$count_transfer+'">';
    //$a += '<div class="hidden"><input type="text" class="form-control" name="company_code" value="'+company_code+'"/></div>';
    $a += '<div class="hidden"><input type="text" class="form-control cert_id" name="cert_id[]" id="cert_id" value=""/></div>';
    $a += '<div class="hidden"><input type="text" class="form-control" name="transfer_id[]" id="transfer_id" value=""/></div>';
    $a += '<div class="hidden"><input type="text" class="form-control" name="officer_id['+$field_index+']" id="officer_id" value=""/></div>';
    $a += '<div class="hidden"><input type="text" class="form-control" name="field_type['+$field_index+']" id="field_type" value=""/></div>';
    $a += '<div class="td"><div class="transfer_group mb-md" style="width: 350px"><select class="form-control person_choice'+$field_index+' person_id" style="text-align:right;width: 100%;" name="id['+$field_index+']" id="person_id"><option value="0" >Select ID</option></select></div></div>';
    $a += '<div class="hidden"><div class="transfer_group mb-md" id="name" style="width: 200px; text-align:left"></div><input type="hidden" class="form-control" name="person_name['+$field_index+']" value="" id="person_name"/></div>';
    $a += '<div class="td"><div class="transfer_group mb-md" id="number_of_share" style="text-align:right;width: 180px"></div><input type="hidden" class="form-control" name="current_share['+$field_index+']" value="" id="current_share"/><input type="hidden" class="form-control" name="amount_share['+$field_index+']" value="" id="amount_share"/><input type="hidden" class="form-control" name="no_of_share_paid['+$field_index+']" value="" id="no_of_share_paid"/><input type="hidden" class="form-control" name="amount_paid['+$field_index+']" value="" id="amount_paid"/></div>';
    $a += '<div class="td"><div class="transfer_group mb-md" style="width: 200px"><input type="text" class="numberdes form-control share_transfer" name="share_transfer['+$field_index+']" value="" id="share_transfer" style="text-align:right" pattern="^[0-9,]+$"/></div></div>';
    $a += '<div class="td"><div class="transfer_group mb-md" style="width: 100px"><input type="text" class="numberdes form-control consideration" name="consideration['+$field_index+']" value="" id="consideration" style="text-align:right" pattern="^[0-9,]+$"/></div></div>';
    $a += '<div class="hidden"><div class="transfer_group mb-md" style="width: 100px"><input type="text" class="form-control from_certificate" name="from_certificate['+$field_index+']" value="" id="from_certificate"/></div></div>';
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

        option.attr('data-name', (allotmentPeople[i]["company_name"]!=null ? allotmentPeople[i]["company_name"] : (allotmentPeople[i]["name"]!=null ? allotmentPeople[i]["name"] : allotmentPeople[i]["client_company_name"])));
        option.attr('data-numberofshare', allotmentPeople[i]['number_of_share']);
        option.attr('data-amountshare', allotmentPeople[i]['amount_share']);
        option.attr('data-noofsharepaid', allotmentPeople[i]['no_of_share_paid']);
        option.attr('data-amountpaid', allotmentPeople[i]['amount_paid']);
        option.attr('data-officerid', allotmentPeople[i]['officer_id']);
        option.attr('data-fieldtype', allotmentPeople[i]['field_type']);
        option.attr('data-certID', allomentPeople[i]['id']);
        option.attr('value', allotmentPeople[i]['officer_id']).text((allotmentPeople[i]["identification_no"]!=null ? (allotmentPeople[i]["identification_no"] + " - " + allotmentPeople[i]["name"]) : ((allotmentPeople[i]["register_no"]!=null) ? (allotmentPeople[i]["register_no"] + " - " + allotmentPeople[i]["company_name"]) : (allotmentPeople[i]["registration_no"] + " - " + allotmentPeople[i]["client_company_name"]))) + " (" +addCommas(allotmentPeople[i]['number_of_share'])+ ") ");

        $("#form"+$count_transfer+" .person_choice"+$field_index+"").append(option); 
    }

    DisableOptions(); //disable selected values
});



$(document).on('change','#person_id',function(e){
    var num = $(this).parent().parent().parent().attr("num");

    var name = $(this).find(':selected').data('name');
    var number_of_share = $(this).find(':selected').data('numberofshare');
    var amount_share = $(this).find(':selected').data('amountshare');
    var no_of_share_paid = $(this).find(':selected').data('noofsharepaid');
    var amount_paid = $(this).find(':selected').data('amountpaid');
    var field_type = $(this).find(':selected').data('fieldtype');
    var officer_id = $(this).find(':selected').data('officerid');
    var certID = $(this).find(':selected').data('certid');

    if($(this).val() == 0)
    {
        $(this).parent().parent().parent().find('#name').text("");
        $(this).parent().parent().parent().find('#person_name').val("");
        $(this).parent().parent().parent().find('#current_share').val("");
        $(this).parent().parent().parent().find('#amount_share').val("");
        $(this).parent().parent().parent().find('#no_of_share_paid').val("");
        $(this).parent().parent().parent().find('#amount_paid').val("");
        $(this).parent().parent().parent().find('#number_of_share').text("");
        $(this).parent().parent().parent().find('#share_transfer').val("");
        $(this).parent().parent().parent().find('input[name="officer_id['+num+']"]').val("");
        $(this).parent().parent().parent().find('input[name="field_type['+num+']"]').val("");
        //$(this).parent().parent().parent().find('#certID').val("");

        sum_total();
    }
    else
    {
        $(this).parent().parent().parent().find('#name').text(name);
        $(this).parent().parent().parent().find('#person_name').val(name);
        $(this).parent().parent().parent().find('#amount_share').val(amount_share);
        $(this).parent().parent().parent().find('#no_of_share_paid').val(no_of_share_paid);
        $(this).parent().parent().parent().find('#amount_paid').val(amount_paid);
        $(this).parent().parent().parent().find('#share_transfer').val("");
        $(this).parent().parent().parent().find('input[name="officer_id['+num+']"]').val(officer_id);
        $(this).parent().parent().parent().find('input[name="field_type['+num+']"]').val(field_type);
        $(this).parent().parent().parent().find('#current_share').val(number_of_share);
        $(this).parent().parent().parent().find('#certID').val(certID);

        var this_row = $(this);
        $("#loadingmessage").show();
        $.ajax({
            type: "POST",
            url: "transaction/check_number_of_share_person",
            data: {"transaction_master_id":$("#transaction_trans #transaction_master_id").val(), "officer_id":officer_id, "field_type":field_type, "certID":certID},
            dataType: "json",
            success: function(responses){
                $("#loadingmessage").hide();

                if(responses[0]['total_number_of_share'] != null)
                {   
                    var latest_number_share = parseInt(number_of_share) + parseInt(responses[0]['total_number_of_share']);
                    this_row.parent().parent().parent().find('#number_of_share').text(addCommas(latest_number_share.toString()));
                    //this_row.parent().parent().parent().find('#current_share').val(latest_number_share.toString());
                }
                else
                {
                    this_row.parent().parent().parent().find('#number_of_share').text(addCommas(number_of_share));
                    
                }
            }
        });
    }
    
    DisableOptions();
});

function DisableOptions()
{
    $("select option").attr("disabled",false); //enable everything

    var arr=[];
    $("select#person_id").each(function(index)
    {
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

function addFirstFrom()
{
    $a0=""; 
    $a0 += '<div class="tr editing transfer transfer_coll" method="post" name="form'+0+'" id="form'+0+'" num="'+0+'">';
    //$a0 += '<div class="hidden"><input type="text" class="form-control" name="company_code" value="'+company_code+'"/></div>';
    $a0 += '<div class="hidden"><input type="text" class="form-control cert_id" name="cert_id[]" id="cert_id" value=""/></div>';
    $a0 += '<div class="hidden"><input type="text" class="form-control" name="transfer_id[]" id="transfer_id" value=""/></div>';
    $a0 += '<div class="hidden"><input type="text" class="form-control" name="officer_id['+0+']" id="officer_id" value=""/></div>';
    $a0 += '<div class="hidden"><input type="text" class="form-control" name="field_type['+0+']" id="field_type" value=""/></div>';
    /*$a += '<div class="td">'+$count_allotment+'</div>';*/
    $a0 += '<div class="td"><div class="transfer_group mb-md" style="width: 350px"><input type="hidden" class="form-control" name="certID['+0+']" value="" id="certID"/><select class="form-control person_id" style="text-align:right;width: 100%;" name="id['+0+']" id="person_id"><option value="0" >Select ID</option></select></div></div>';
    /*$a0 += '<div class="td"><div class="transfer_group mb-md"><input type="text" name="name['+0+']" class="form-control" value=""/></div></div>';
    $a0 += '<div class="td"><div class="transfer_group mb-md"><input type="text" name="number_of_share['+0+']" class="form-control" value=""/></div></div>';*/
    $a0 += '<div class="hidden"><div class="transfer_group mb-md" id="name" style="width: 200px; text-align:left"></div><input type="hidden" class="form-control" name="person_name['+0+']" value="" id="person_name"/></div>';
    $a0 += '<div class="td"><div class="transfer_group mb-md" id="number_of_share" style="text-align:right;width: 180px"></div><input type="hidden" class="form-control" name="current_share['+0+']" value="" id="current_share"/><input type="hidden" class="form-control" name="amount_share['+0+']" value="" id="amount_share"/><input type="hidden" class="form-control" name="no_of_share_paid['+0+']" value="" id="no_of_share_paid"/><input type="hidden" class="form-control" name="amount_paid['+0+']" value="" id="amount_paid"/></div>';
    $a0 += '<div class="td"><div class="transfer_group mb-md" style="width: 200px"><input type="text" class="numberdes form-control share_transfer" name="share_transfer['+0+']" value="" id="share_transfer" style="text-align:right" pattern="^[0-9,]+$"/></div></div>';
    $a0 += '<div class="td"><div class="transfer_group mb-md" style="width: 100px"><input type="text" class="numberdes form-control consideration" name="consideration['+0+']" value="" id="consideration" style="text-align:right" pattern="^[0-9,]+$"/></div></div>';
    $a0 += '<div class="hidden"><div class="transfer_group mb-md" style="width: 100px"><input type="text" class="form-control from_certificate" name="from_certificate['+0+']" value="" id="from_certificate"/></div></div>';
    //$a0 += '<div class="td action"><button type="button" class="btn btn-primary delete_transfer_button" onclick="delete_transfer(this)" style="display: none;">Delete</button></div>';
    $a0 += '</div>';

    $("#transfer_add").append($a0); 

    $ato=""; 
    $ato += '<div class="tr editing to to_coll" method="post" name="form_to'+0+'" id="form_to'+0+'" num_to="'+0+'">';
    //$ato += '<div class="hidden"><input type="text" class="form-control" name="company_code" value="'+company_code+'"/></div>';
    $ato += '<div class="hidden"><input type="text" class="form-control to_cert_id" name="to_cert_id[]" id="to_cert_id" value=""/></div>';
    $ato += '<div class="hidden"><input type="text" class="form-control" name="to_id[]" id="to_id" value=""/></div>';
    $ato += '<div class="hidden"><input type="text" class="form-control" name="to_officer_id['+0+']" id="to_officer_id" value=""/></div>';
    $ato += '<div class="hidden"><input type="text" class="form-control" name="to_field_type['+0+']" id="to_field_type" value=""/></div>';
    $ato += '<div class="hidden"><input type="text" class="form-control" name="previous_new_cert['+0+']" id="previous_new_cert" value=""/></div>';
    $ato += '<div class="hidden"><input type="text" class="form-control" name="previous_cert['+0+']" id="previous_cert" value=""/></div>';
    /*$a += '<div class="td">'+$count_allotment+'</div>';*/
    $ato += '<div class="td"><div class="transfer_group"><input type="text" name="id_to['+0+']" class="form-control get_person_id" value="" id="get_person_id" style="text-transform:uppercase;"/></div><div style="height:16px;" ><a class="" href="'+ url + 'personprofile/add/1" style="cursor:pointer;" id="add_person_link" target="_blank" onclick="add_transfer_member(this)" hidden><div style="cursor:pointer;">Click here to <span style="font-weight:bold">Add Entity</span></div></a></div></div>';
    /*$a0 += '<div class="td"><div class="transfer_group mb-md"><input type="text" name="name['+0+']" class="form-control" value=""/></div></div>';
    $a0 += '<div class="td"><div class="transfer_group mb-md"><input type="text" name="number_of_share['+0+']" class="form-control" value=""/></div></div>';*/
    $ato += '<div class="td"><div class="transfer_group mb-md" id="name_to" style="width: 200px; text-align:left"></div><input type="hidden" class="form-control" name="to_person_name['+0+']" value="" id="to_person_name"/></div>';
    $ato += '<div class="td"><div class="transfer_group mb-md" style="width: 100%"><input type="text" class="numberdes form-control number_of_share_to" name="number_of_share_to['+0+']" value="" id="number_of_share_to" style="text-align:right" pattern="^[0-9,]+$" readonly = "true"/></div></div>';
    $ato += '<div class="hidden"><div class="transfer_group mb-md" style="width: 100px"><input type="text" class="form-control from_certificate" name="to_certificate['+0+']" value="" id="to_certificate"/></div></div>';
    //$ato += '<div class="td action"><button type="button" class="btn btn-primary delete_to_button" onclick="delete_to(this)" style="display: none;">Delete</button></div>';
    $ato += '</div>';

    $("#transfer_to_add").append($ato); 
}

function add_transfer_member(elem)
{
    jQuery(elem).parent().parent().find('#get_person_id').val("");
    jQuery(elem).attr('hidden',"true");
}

$("#number_of_share_to").live('change',function(){
    var input_num = $(this).parent().parent().parent().attr("num_to");
    var sum_to = 0;

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
    
    //console.log(sum_to);
    if(sum_to > removeCommas($("#total_from").text()))
    {
        $("#transfer_to_add .td #number_of_share_to").each(function(){
        //console.log($(this).val() == '');
            $(this).val("");
            $("#total_to").text("");
        });

        toastr.error("Number of Shares cannot be more than number of shares to transfer.", "Error");
    }
    else
    {
        $("#total_to").text(addCommas(sum_to));
    }
});

$("#share_transfer").live('change',function(){
    if($(this).parent().parent().parent().find("#number_of_share").html() != "")
    {
        if(parseInt(removeCommas($(this).val())) > parseInt(removeCommas($(this).parent().parent().parent().find("#number_of_share").html())))
        {
            $(this).val($(this).parent().parent().parent().find("#number_of_share").html());
        }
        sum_total();
    }
    
});

function removeCommas(str) {
    while (str.search(",") >= 0) {
        str = (str + "").replace(/,/g, "");
    }
    return str;
};

function sum_total(){
    var sum = 0;
    $(".transfer .td #share_transfer").each(function(){

        if($(this).val() == '')
        {
            sum += 0;
        }
        else
        {
            sum += +parseInt(removeCommas($(this).val()));
        }
    });

    $("#total_from").text(addCommas(sum));
    $(".number_of_share_to").val(addCommas(sum));
    $("#total_to").text(addCommas(sum));

    if(sum > 0)
    {
        $('#table_transfer_to').show();
        $('#total_share_transfer_to').show();
        $('.to').show();
        if(!transaction_share_transfer)
        {

            if(parseInt($("#total_to").text()) > parseInt($("#total_from").text()))
            {
                $("#total_to").text("0");
                $("#transfer_to_add .td #number_of_share_to").each(function(){
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
                console.log($(this).val());
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
}

$(document).on('click',"#transfer_to_member_Add",function() {

    transfer_to_info_coll = document.getElementsByClassName("to_coll");
    if(transfer_to_info_coll.length > 0)
    {
        $count_to = transfer_to_info_coll.length + 1;
    }
    else
    {
        $count_to = 0;
    }

    $count_to++;
    $field_index = $count_to;

    $ato1=""; 
    $ato1 += '<div class="tr editing to to_coll" method="post" name="form_to'+$count_to+'" id="form_to'+$count_to+'" num_to="'+$count_to+'">';
    //$ato1 += '<div class="hidden"><input type="text" class="form-control" name="company_code" value="'+company_code+'"/></div>';
    $ato1 += '<div class="hidden"><input type="text" class="form-control to_cert_id" name="to_cert_id[]" id="to_cert_id" value=""/></div>';
    $ato1 += '<div class="hidden"><input type="text" class="form-control" name="to_id[]" id="to_id" value=""/></div>';
    $ato1 += '<div class="hidden"><input type="text" class="form-control" name="to_officer_id['+$field_index+']" id="to_officer_id" value=""/></div>';
    $ato1 += '<div class="hidden"><input type="text" class="form-control" name="to_field_type['+$field_index+']" id="to_field_type" value=""/></div>';
    $ato1 += '<div class="hidden"><input type="text" class="form-control" name="previous_new_cert['+$field_index+']" id="previous_new_cert" value=""/></div>';
    $ato1 += '<div class="hidden"><input type="text" class="form-control" name="previous_cert['+$field_index+']" id="previous_cert" value=""/></div>';
    $ato1 += '<div class="td"><div class="transfer_group"><input type="text" name="id_to['+$field_index+']" class="form-control get_person_id" value="" id="get_person_id" style="text-transform:uppercase;"/></div><div style="height:16px;" ><a class="" href="'+ url + 'personprofile/add/1" style="cursor:pointer;" id="add_person_link" target="_blank" onclick="add_transfer_member(this)" hidden><div style="cursor:pointer;">Click here to <span style="font-weight:bold">Add Entity</span></div></a></div></div>';
    $ato1 += '<div class="td"><div class="transfer_group mb-md" id="name_to" style="width: 200px; text-align:left"></div><input type="hidden" class="form-control" name="to_person_name['+$field_index+']" value="" id="to_person_name"/></div>';
    $ato1 += '<div class="td"><div class="transfer_group mb-md" style="width: 200px"><input type="text" class="numberdes form-control number_of_share_to" name="number_of_share_to['+$field_index+']" value="" id="number_of_share_to" style="text-align:right" pattern="^[0-9,]+$"/></div></div>';
    $ato1 += '<div class="hidden"><div class="transfer_group mb-md" style="width: 100px"><input type="text" class="form-control from_certificate" name="to_certificate['+$field_index+']" value="" id="to_certificate"/></div></div>';
    //$ato1 += '<div class="td action"><button type="button" class="btn btn-primary delete_to_button" onclick="delete_to(this)" style="display: none;">Delete</button></div>';
    $ato1 += '</div>';

    $("#transfer_to_add").append($ato1); 

    if($("#transfer_to_add > div").length > 1)
    {
        $('.delete_to_button').css('display','block');
    }
});

$("#get_person_id").live('change',function(){
    var allotment_frm = $(this);
    var input_num = allotment_frm.parent().parent().parent().attr("num_to");
    $("#loadingmessage").show();
    $.ajax({
        type: "POST",
        url: "transaction/get_transaction_person",
        data: {"identification_register_no":allotment_frm.val()}, // <--- THIS IS THE CHANGE
        dataType: "json",
        success: function(responses){
            $("#loadingmessage").hide();
            if(responses.info != null)
            {
                var response = responses.info;
                var check_same_person = false;

                $('.person_id').each(function() {
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

                if(!check_same_person)
                {
                    if(responses.status == 1)
                    {
                        if(response)
                        {
                            allotment_frm.parent().parent().parent().find('#name_to').text(response['name']);
                            allotment_frm.parent().parent().parent().find('#to_person_name').val(response['name']);
                            allotment_frm.parent().parent().parent().find('input[name="to_officer_id['+input_num+']"]').val(response['id']);
                            allotment_frm.parent().parent().parent().find('input[name="to_field_type['+input_num+']"]').val(response['field_type']);
                            allotment_frm.parent().parent('div').find('a#add_person_link').attr('hidden',"true");
                            
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
});

function delete_member_transfer(element)
{
    var tr = jQuery(element).parent().parent().parent();

    var transaction_transfer_member_id = tr.find('input[name="transaction_transfer_member_id"]').val();
    var transaction_id = tr.find('input[name="transaction_id"]').val();
    var from_transfer_member_id = tr.find('input[name="from_transfer_member_id"]').val();
    var to_transfer_member_id = tr.find('input[name="to_transfer_member_id"]').val();
    var from_cert_id = tr.find('input[name="from_cert_id"]').val();
    var to_cert_id = tr.find('input[name="to_cert_id"]').val();

    if(transaction_transfer_member_id != undefined && transaction_id != undefined)
    {
        $('#loadingmessage').show();
        $.ajax({ 
            url: "transaction/delete_member_transfer",
            type: "POST",
            data: {"transaction_transfer_member_id": transaction_transfer_member_id, "transaction_id": transaction_id, "from_transfer_member_id": from_transfer_member_id, "to_transfer_member_id": to_transfer_member_id, "from_cert_id": from_cert_id, "to_cert_id": to_cert_id},
            dataType: 'json',
            success: function (response) {
                $('#loadingmessage').hide();
                $(".member_info_for_each_company").remove();
                added_transfer_info(response.transaction_member);
                toastr.success("Updated Information.", "Updated");
            }
        });
    }
    
}

function delete_to(element) {

    var tr = jQuery(element).parent().parent(),
        to_id = tr.find('input[name="to_id[]"]').val(),
        cert_id = tr.find('input[name="to_cert_id[]"]').val();

    tr.closest("DIV.tr").remove();
    if($("#transfer_to_add > div").length == 1)
    {
        if($('.delete_to_button').css('display') == 'block')
        {
            $('.delete_to_button').css('display','none');
        }
    }

    var sum_to = 0;

    $("#transfer_to_add .td #number_of_share_to").each(function(){
        if($(this).val() == '')
        {
            sum_to += 0;
        }
        else
        {
            sum_to += +parseInt($(this).val());
        }
    });
    $("#total_to").text(sum_to);
}

function delete_transfer(element) {

    var tr = jQuery(element).parent().parent(),
        transfer_id = tr.find('input[name="transfer_id[]"]').val(),
        cert_id = tr.find('input[name="cert_id[]"]').val();

    tr.closest("DIV.tr").remove();

    DisableOptions();
    sum_total();

    if($("#transfer_add > div").length == 1)
    {
        if($('.delete_transfer_button').css('display') == 'block')
        {
            $('.delete_transfer_button').css('display','none');
        }
    }
}

$(document).on('click',"#submitShareTransferInfo",function(e){
    $('#loadingmessage').show();
    if($('input[name="address_type"]').val() == "Registered Office Address")
    {
        $("#tr_registered_edit input").removeAttr('disabled');
    }
    else
    {
        $("#tr_registered_edit input").attr('disabled', 'true');
    }
    $.ajax({ 
        url: "transaction/save_share_transfer",
        type: "POST",
        data: $('form#share_transfer_form').serialize()+ '&transaction_code=' + $('#transaction_code').val() + '&transaction_task_id=' + $('#transaction_task').val() + '&registration_no=' + $('#uen').val() + '&transaction_master_id=' + $("#transaction_trans #transaction_master_id").val(),
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
                $("#transaction_trans #transaction_master_id").val(response.transaction_master_id);
                $("#transaction_trans #transaction_code").val(response.transaction_code);
                
                get_transfer_from_people();
                added_transfer_info(response.transaction_member);

                $('#class option[value="0"]').attr('selected', true);
                $("#currency").val($("#class").find("option:selected").data('currency'));
                $("#client_member_share_capital_id").val($("#class").find("option:selected").val());

                $("#total_from").text("0");
                $("#total_to").text("0");

                $("DIV.transfer").remove();
                $("DIV.to").remove();
                addFirstFrom();
            }
        }
    })
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

$(document).on('click',"#submitShareTransferCertInfo",function(e){
    e.preventDefault();
    var notEmpty = true;

    $('.transferor_table_add td:nth-child(1):visible').each(function(){
        var transferor_row = $(this);
        var transferor_name = $(this).text();
        var currency_class = $(this).parent().find("td:nth-child(2)").text();
        var new_number_of_share_transfer = 0, difference_share_transfer = 0;
        var total_number_of_share_to_transfer = parseInt($(this).parent().find(".transfered_share").text().replace(/\,/g,''));
        $('.transferor_table_add td:nth-child(1)').each(function(){
            var transferor_row = $(this);
            var check_transferor_name = $(this).text();
            var check_currency_class = $(this).parent().find("td:nth-child(2)").text();
            if(check_transferor_name == transferor_name && check_currency_class == currency_class)
            {
                var number_of_share_to_transfer = parseInt(transferor_row.parent().find(".assign_number_of_share").val().replace(/\,/g,''));

                if(isNaN(number_of_share_to_transfer))
                {
                    number_of_share_to_transfer = 0;
                }

                new_number_of_share_transfer = new_number_of_share_transfer + number_of_share_to_transfer;
            }
        });
        difference_share_transfer = (-(total_number_of_share_to_transfer)) - new_number_of_share_transfer;

        if(difference_share_transfer == -0)
        {
            difference_share_transfer = 0;
        }

        if(difference_share_transfer != 0)
        {
            notEmpty = false;
            return false; 
        }
    });

    $('.transferor_table_add td:nth-child(6)').each(function() 
    {
        var transferor_row = $(this);
        var number_of_share_transfer = transferor_row.find(".assign_number_of_share").val();
        var new_number_of_shares = transferor_row.parent().find(".new_number_of_share").val();
        var latest_certificate = transferor_row.parent().find(".latest_certificate").val();
        if(number_of_share_transfer != "")
        {
            if(new_number_of_shares == "" || latest_certificate == "")
            {
                notEmpty = false;
                return false;
            }
        }
    });

    $('.certificate_table_add td:nth-child(4)').each(function() 
    {
        var transferee_row = $(this);
        var new_certificate = transferee_row.find('input[name="certificate[]"]').val();
        if(new_certificate == "")
        {
            notEmpty = false;
            return false;
        }
    });

    if(notEmpty)
    {
        $.ajax({
            url: "transaction/save_share_transfer_latest_cert_number",
            type: "POST",
            data: $('form#confirm_transfer_cert_form').serialize()+ '&transaction_code=' + $('#transaction_code').val() + '&transaction_task_id=' + $('#transaction_task').val() + '&registration_no=' + $('#uen').val() + '&company_code=' + transaction_company_code + '&transaction_master_id=' + $('#transaction_master_id').val(),
            dataType: 'json',
            success: function (response,data) {
            $('#loadingmessage').hide();

                if (response.Status === 1) 
                {
                    toastr.success(response.message, response.title);
                }
            }
        })
    }
    else
    {
        toastr.error("Please complete all the input field and make sure all the value is correct.", "Error");
    }
});