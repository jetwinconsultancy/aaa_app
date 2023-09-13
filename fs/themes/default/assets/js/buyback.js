var share_buyback = {
    row: '.transfer_group',   // The title is placed inside a <div class="col-xs-4"> element
    validators: {
        notEmpty: {
            message: 'The Share Buyback field is required.'
        }
    }
},
certificate_no = {
    row: '.transfer_group',   // The title is placed inside a <div class="col-xs-4"> element
    validators: {
        notEmpty: {
            message: 'The Certificate No field is required.'
            
        }
        /*callback: {
            message: 'The Certificate No field is required.',
            callback: function(value, validator, $field) {
                //console.log($field.parent().parent().parent());
                var num = $field.parent().parent().parent().attr("num");

                var share_buyback_value = $field.parent().parent().parent().find("#share_buyback").val();
                //var framework = $('#surveyForm').find('[name="framework"]:checked').val();
                return (share_buyback_value == 0) ? true : (value !== '');
            }
        }*/
    }
};

$('#buyback_form').formValidation({
    framework: 'bootstrap',
    icon: {
        /*valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'*/
    },
    // This option will not ignore invisible fields which belong to inactive panels
    //excluded: ':disabled',
    //excluded: [':disabled', ':hidden', ':not(:visible)'],
    fields: {
        date: {
            validators: {
                notEmpty: {
                    message: 'The Transaction Date field is required.'
                }
            }
        },
        buyback_class: {
            validators: {
                callback: {
                    message: 'The Class field is required.',
                    callback: function(value, validator, $field) {
                        var options = validator.getFieldElements('buyback_class').val();
                        console.log(options);
                        return (options != null && options != "0");
                    }
                }
            }
        },
        buyback_share: {
            validators: {
                notEmpty: {
                    message: 'The Buyback Share field is required.'
                },
                between: {
                    min: 1,
                    max: 100,
                    message: 'The number of buyback share must be between 1 and 100.'
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

/*$('.buyback_share').on('keypress', function(e){
  return //e.metaKey || // cmd/ctrl
    e.which <= 0 || // arrow keys
    e.which == 8 || // delete key
    e.which == 46 || // dot key
    /[0-9]/.test(String.fromCharCode(e.which)); // numbers
})*/

$('.buyback_share').keypress(function(event) {
  if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
    event.preventDefault();
  }
});

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
            console.log("incorporation_date==="+response[0]["incorporation_date"]);
            $array = response[0]["incorporation_date"].split("/");
            $tmp = $array[0];
            $array[0] = $array[1];
            $array[1] = $tmp;
            //unset($tmp);
            $date_2 = $array[0]+"/"+$array[1]+"/"+$array[2];
            console.log(new Date($date_2));

            latest_incorporation_date = new Date($date_2);
            /*date.setDate(date.getDate()-1)
    */
            console.log(new Date());
            $('#transaction_date').datepicker({ 
                dateFormat:'dd/mm/yyyy',
            }).datepicker('setStartDate', latest_incorporation_date);
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

console.log(buyback);

if(buyback)
{
    var previous_total_number_of_share = 0;
    var previous_share_buyback = 0;
    var buyback_share = 0;

    var total_number_of_share = 0;
    var total_share_buyback = 0;
    var total_new_number_of_share = 0;
    var new_share_buyback = 0;
    var per_share = 0;
    var current_amount_share = 0;
    $("tr.transfer").remove();

    for(var t = 0; t < buyback.length; t++)
    {
        previous_total_number_of_share += parseInt(buyback[t]["certificate_number_of_share"]); 

        previous_share_buyback += (-(parseInt(buyback[t]["number_of_share"]))); 

        total_number_of_share += parseInt(buyback[t]["certificate_number_of_share"]);

        total_share_buyback += (-(parseInt(buyback[t]["number_of_share"])));

        total_new_number_of_share += (parseInt(buyback[t]["certificate_number_of_share"]) - (-(parseInt(buyback[t]["number_of_share"]))));
        console.log(buyback[t]["certificate_number_of_share"]);
        console.log(buyback[t]["number_of_share"]);
        console.log(total_new_number_of_share);
        if(buyback[t]["amount_share"] != 0.00 && buyback[t]["number_of_share"] != 0)
        {
            per_share = (parseFloat(buyback[t]["amount_share"])/parseInt(buyback[t]["number_of_share"]));
        }
        else
        {
            per_share = 0;
        }
        
        //console.log(per_share);
       // console.log(buyback[t]["number_of_share"]);
        //console.log(buyback[t]["number_of_share"]);

        if(per_share == 0)
        {
            $.ajax({
                type: "POST",
                url: "masterclient/get_amount_share",
                data: {"id":buyback[t]["id"], "officer_id":buyback[t]["officer_id"], "field_type":buyback[t]["field_type"]}, // <--- THIS IS THE CHANGE
                dataType: "json",
                async: false,
                success: function(response){
                    console.log(response[0]["amount_share"]);
                    current_amount_share = response[0]["amount_share"];

                    $('input[name="current_amount_share['+t+']"]').val(current_amount_share);

                    //buyback_share = ((-(buyback[t]["number_of_share"])/current_amount_share) * 100).toFixed(0);

                }               
            });
        }
        else
        {
            current_amount_share = (per_share * parseInt(buyback[t]["certificate_number_of_share"]));
            console.log(current_amount_share);
        }
        

        $a0=""; 
        $a0 += '<tr class="editing transfer" method="post" name="form'+t+'" id="form'+t+'" num="'+t+'">';
        $a0 += '<input type="hidden" class="form-control" name="company_code" value="'+company_code+'"/>';
        $a0 += '<input type="hidden" class="form-control cert_id" name="cert_id[]" id="cert_id" value="'+buyback[t]["cert_id"]+'"/>';
        $a0 += '<input type="hidden" class="form-control" name="buyback_id[]" id="buyback_id" value="'+buyback[t]["id"]+'"/>';
        $a0 += '<input type="hidden" class="form-control" name="officer_id['+t+']" id="officer_id" value="'+(buyback[t]["officer_id"]!=null ? buyback[t]["officer_id"] : (buyback[t]["officer_company_id"]!=null ? buyback[t]["officer_company_id"] : buyback[t]["client_company_id"]))+'"/>';
        $a0 += '<input type="hidden" class="form-control" name="field_type['+t+']" id="field_type" value="'+(buyback[t]["officer_field_type"]!=null ? buyback[t]["officer_field_type"] : (buyback[t]["officer_company_field_type"]!=null ? buyback[t]["officer_company_field_type"] : buyback[t]["client_company_field_type"]))+'"/>';
        /*$a += '<div class="td">'+$count_allotment+'</div>';*/
        $a0 += '<td>'+(t+1)+'</td>';
        $a0 += '<td><div class="member_name">'+(buyback[t]["company_name"]!=null ? buyback[t]["company_name"] : (buyback[t]["name"]!=null ? buyback[t]["name"] : buyback[t]["client_company_name"]))+'</div><input type="hidden" class="form-control" name="member_name['+t+']" value="'+(buyback[t]["company_name"]!=null ? buyback[t]["company_name"] : (buyback[t]["name"]!=null ? buyback[t]["name"] : buyback[t]["client_company_name"]))+'" id="member_name"/></td>';
        /*$a0 += '<div class="td"><div class="transfer_group mb-md"><input type="text" name="name['+0+']" class="form-control" value=""/></div></div>';
        $a0 += '<div class="td"><div class="transfer_group mb-md"><input type="text" name="number_of_share['+0+']" class="form-control" value=""/></div></div>';*/
        $a0 += '<td><div class="number_of_share" style="text-align:right;">'+addCommas(buyback[t]["certificate_number_of_share"])+'</div><input type="hidden" class="form-control" name="current_number_of_share['+t+']" value="'+buyback[t]["certificate_number_of_share"]+'" id="current_number_of_share"/><input type="hidden" class="form-control" name="current_amount_share['+t+']" value="'+current_amount_share+'" id="current_amount_share"/></td>';
        $a0 += '<td><div class="transfer_group mb-md"><input type="text" class="numberdes form-control share_buyback" name="share_buyback['+t+']" value="'+addCommas((-parseInt(buyback[t]["number_of_share"])))+'" id="share_buyback" style="text-align:right;" pattern="^[0-9,]+$"/></div></td>';
        $a0 += '<td><div id="show_new_number_of_share" style="text-align:right;">'+addCommas(parseInt(buyback[t]["certificate_number_of_share"]) - (-(parseInt(buyback[t]["number_of_share"]))))+'</div><input type="hidden" class="form-control new_number_of_share" name="new_number_of_share['+t+']" value="'+(parseInt(buyback[t]["certificate_number_of_share"]) - (-(parseInt(buyback[t]["number_of_share"]))))+'" id="new_number_of_share"/></td>';
        /*$a0 += '<td><div class="transfer_group mb-md"><input type="text" class="form-control edit_certificate_no check_cert_in_live" name="certificate_no['+t+']" value="'+buyback[t]["certificate_no"]+'" id="certificate_no"/><input type="hidden" class="form-control" name="hidden_certificate_no['+t+']" value="'+buyback[t]["certificate_no"]+'" id="hidden_certificate_no"/><div class="validate_edit_from_cert"></div><div class="validate_edit_from_cert_live"></div></div></td>';*/
        $a0 += '</tr>';

        $("#tbody_buyback").append($a0); 
        //console.log(buyback[t]["certificate_no"].substring(0,9));
        if(buyback[t]["certificate_no"].substring(0,9) == "TEMPORARY")
        {
            $('input[name="certificate_no['+t+']"]').val("");
            $('input[name="certificate_no['+t+']"]').attr('disabled', true);

            $('input[name="hidden_certificate_no['+t+']"]').val(" ");
        }

        if(access_right_member_module == "read" || client_status != "1")
        {
            $(".share_buyback").attr("disabled", true);
            $(".check_cert_in_live").attr("disabled", true);
        }

        $('#buyback_form').formValidation('addField', 'share_buyback['+t+']', share_buyback);
        /*$('#buyback_form').formValidation('addField', 'certificate_no['+t+']', certificate_no);*/

        /*$('input[name="share_buyback['+t+']"]').val(parseInt(alloment_people[t]["number_of_share"]) * parseFloat(buyback_share) / 100);
        $('input[name="new_number_of_share['+t+']"]').val(show_new_number_of_share);*/
    }

    $("#total_number_of_share").text(addCommas((total_number_of_share).toFixed(0)));
    $("#total_share_buyback").text(addCommas((total_share_buyback).toFixed(0)));
    $("#total_new_number_of_share").text(addCommas((total_new_number_of_share).toFixed(0)));

    
    console.log(previous_share_buyback);
    console.log(previous_total_number_of_share);
    buyback_share = ((previous_share_buyback/previous_total_number_of_share) * 100).toFixed(2);
    console.log(buyback_share);
    $("#buyback_share").val(buyback_share);
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

if(company_class)
{
	//console.log("company_class="+company_class[0]);
    var shareClass;
    for(var i = 0; i < company_class.length; i++)
	{
        if(company_class[i]['sharetype'] == "Ordinary Share")
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
        if(buyback)
        {
        	if(buyback[0]["share_capital_id"] != null && company_class[i]['id'] == buyback[0]["share_capital_id"])
        	{
        		option.attr('selected', 'selected');
        		/*if(buyback[0]["sharetype"] == "Others")
        		{
        			$("#buyback_other_class").removeAttr('hidden');
        		}*/
        	}
        }

        $("#buyback_class").append(option);
	}
	console.log(option);
}

$("#buyback_class").on('change', function() {
    //console.log($(this).find("option:selected").data('otherclass')==" ");
    //console.log($(this).find("option:selected").val());
    if($(this).find("option:selected").val() == 0)
    {
        //$("#buyback_other_class").attr("hidden","true");
        $(".others_field").attr("hidden","true");
        $("#currency").val($(this).find("option:selected").data('currency'));
    }
    else
    {
        if($(this).find("option:selected").data('otherclass')!=" ")
        {
            //$("#buyback_other_class").removeAttr('hidden');
            $(".others_field").removeAttr('hidden');
            /*$("#others").val($(this).find("option:selected").data('otherclass'));
            other_class = $(this).find("option:selected").data('otherclass');
            $(".member_others").val($(this).find("option:selected").data('otherclass'));*/
        }
        else
        {
            //$("#buyback_other_class").attr("hidden","true");
            $(".others_field").attr("hidden","true");

        }
        $("#buyback_others").val($(this).find("option:selected").data('otherclass'));
       /* other_class = $(this).find("option:selected").data('otherclass');
        $(".member_others").val($(this).find("option:selected").data('otherclass'));*/

        $("#client_member_share_capital_id").val($(this).find("option:selected").val());
        //console.log($(this).find("option:selected").val());
        $("#buyback_currency").val($(this).find("option:selected").data('currency'));

        /*currency = $(this).find("option:selected").data('currency');
        shareType = $(this).find("option:selected").data('sharetype');

        $(".member_class").val($(this).find("option:selected").data('sharetype'));
        $(".member_currency").val($(this).find("option:selected").data('currency'));*/
    }
    
    
});

function changeShareNumber(buyback, buyback_share)
{
    var new_shares_buyback = 0;
    var show_new_number_of_shares = 0;
    var total_new_share_buyback = 0;
    var total_new_number_of_shares = 0;

    for(var t = 0; t < buyback.length; t++)
    {

        new_shares_buyback = (parseFloat(buyback[t]["certificate_number_of_share"]) * parseFloat(buyback_share) / 100);

        show_new_number_of_shares = parseFloat(buyback[t]["certificate_number_of_share"]) - parseFloat(new_shares_buyback);

        console.log(show_new_number_of_shares);
        total_new_share_buyback += parseFloat(new_shares_buyback);

        total_new_number_of_shares += parseInt(show_new_number_of_shares.toFixed(0));
        
        $('#form'+t+' #show_new_number_of_share').text(addCommas(parseInt(show_new_number_of_shares.toFixed(0))));
        $('input[name="new_number_of_share['+t+']"]').val(parseInt(show_new_number_of_shares.toFixed(0)));
        $('input[name="share_buyback['+t+']"]').val(addCommas((parseInt(buyback[t]["certificate_number_of_share"]) * parseFloat(buyback_share) / 100).toFixed(0)));
    }

    $("#total_share_buyback").text(addCommas(parseFloat(total_new_share_buyback.toFixed(0))));
    $("#total_new_number_of_share").text(addCommas(parseFloat(total_new_number_of_shares.toFixed(0))));

    
}

function assignAllomentPeople(alloment_people, buyback_share)
{
    //allotmentPeople = alloment_people;
    console.log(alloment_people);
    console.log(buyback_share);
    var total_number_of_share = 0;
    var total_share_buyback = 0;
    var total_new_number_of_share = 0;
    var new_share_buyback = 0;
    $("tr.transfer").remove();

    for(var t = 0; t < alloment_people.length; t++)
    {   
        if(alloment_people[t]["number_of_share"] != 0)
        {
            total_number_of_share += parseInt(alloment_people[t]["number_of_share"]);

            total_share_buyback += (parseInt(alloment_people[t]["number_of_share"]) * parseFloat(buyback_share) / 100);

            //console.log(total_share_buyback);

            new_share_buyback = (parseInt(alloment_people[t]["number_of_share"]) * parseFloat(buyback_share) / 100);

            show_new_number_of_share = parseFloat(alloment_people[t]["number_of_share"]) - parseInt(new_share_buyback.toFixed(0));
            console.log(show_new_number_of_share);

            total_new_number_of_share += (parseFloat(alloment_people[t]["number_of_share"]) - parseFloat(new_share_buyback));

            $a0=""; 
            $a0 += '<tr class="editing transfer" method="post" name="form'+t+'" id="form'+t+'" num="'+t+'">';
            $a0 += '<input type="hidden" class="form-control" name="company_code" value="'+company_code+'"/>';
            $a0 += '<input type="hidden" class="form-control cert_id" name="cert_id[]" id="cert_id" value=""/>';
            $a0 += '<input type="hidden" class="form-control" name="buyback_id[]" id="buyback_id" value=""/>';
            $a0 += '<input type="hidden" class="form-control" name="officer_id['+t+']" id="officer_id" value="'+alloment_people[t]["officer_id"]+'"/>';
            $a0 += '<input type="hidden" class="form-control" name="field_type['+t+']" id="field_type" value="'+alloment_people[t]["field_type"]+'"/>';
            /*$a += '<div class="td">'+$count_allotment+'</div>';*/
            $a0 += '<td>'+(t+1)+'</td>';
            $a0 += '<td><div class="member_name">'+(alloment_people[t]["company_name"]!=null ? alloment_people[t]["company_name"] : (alloment_people[t]["name"]!=null ? alloment_people[t]["name"] :  alloment_people[t]["client_company_name"]))+'</div><input type="hidden" class="form-control" name="member_name['+t+']" value="'+(alloment_people[t]["company_name"]!=null ? alloment_people[t]["company_name"] : (alloment_people[t]["name"]!=null ? alloment_people[t]["name"] : alloment_people[t]["client_company_name"]))+'" id="member_name"/></td>';
            /*$a0 += '<div class="td"><div class="transfer_group mb-md"><input type="text" name="name['+0+']" class="form-control" value=""/></div></div>';
            $a0 += '<div class="td"><div class="transfer_group mb-md"><input type="text" name="number_of_share['+0+']" class="form-control" value=""/></div></div>';*/
            $a0 += '<td><div class="number_of_share" style="text-align:right;">'+addCommas(parseFloat(alloment_people[t]["number_of_share"]).toFixed(0))+'</div><input type="hidden" class="form-control" name="current_number_of_share['+t+']" value="'+parseFloat(alloment_people[t]["number_of_share"]).toFixed(0)+'" id="current_number_of_share"/><input type="hidden" class="form-control" name="current_amount_share['+t+']" value="'+alloment_people[t]["amount_share"]+'" id="current_amount_share"/></td>';
            $a0 += '<td><div class="transfer_group mb-md"><input type="text" class="numberdes form-control share_buyback" name="share_buyback['+t+']" value="" id="share_buyback" style="text-align:right;" pattern="^[0-9,]+$"/></div></td>';
            $a0 += '<td><div id="show_new_number_of_share" style="text-align:right;">'+addCommas(parseInt(show_new_number_of_share).toFixed(0))+'</div><input type="hidden" class="form-control new_number_of_share" name="new_number_of_share['+t+']" value="" id="new_number_of_share"/></td>';
            //$a0 += '<td><div class="transfer_group mb-md"><input type="text" class="form-control edit_certificate_no check_cert_in_live" name="certificate_no['+t+']" value="" id="certificate_no"/><input type="hidden" class="form-control" name="hidden_certificate_no['+t+']" value="" id="hidden_certificate_no"/><div class="validate_edit_from_cert"></div><div class="validate_edit_from_cert_live"></div></div></td>';
            $a0 += '</tr>';

            $("#tbody_buyback").append($a0); 

            $('#buyback_form').formValidation('addField', 'share_buyback['+t+']', share_buyback);
            //$('#buyback_form').formValidation('addField', 'certificate_no['+t+']', certificate_no);

            $('input[name="share_buyback['+t+']"]').val(addCommas((parseInt(alloment_people[t]["number_of_share"]) * parseFloat(buyback_share) / 100).toFixed(0)));
            $('input[name="new_number_of_share['+t+']"]').val(parseFloat(show_new_number_of_share).toFixed(0));

        }
        /*console.log("share_buyback==="+share_buyback);
        console.log("number_of_share==="+(parseFloat(alloment_people[t]["number_of_share"]) - parseFloat(share_buyback)));*/
        
    }
    
    $("#total_number_of_share").text(addCommas((total_number_of_share).toFixed(0)));
    $("#total_share_buyback").text(addCommas(parseFloat(total_share_buyback).toFixed(0)));
    $("#total_new_number_of_share").text(addCommas(parseFloat(total_new_number_of_share).toFixed(0)));
    /*$("DIV.transfer").remove();
    $("DIV.to").remove();*/
    //addFirstFrom();

    /*for(var i = 0; i < alloment_people.length; i++)
    {
        var option = $('<option />');
       

        option.attr('data-name', (alloment_people[i]["company_name"]!=null ? alloment_people[i]["company_name"] : alloment_people[i]["name"]));
        option.attr('data-numberofshare', alloment_people[i]['number_of_share']);
        option.attr('data-officerid', alloment_people[i]['officer_id']);
        option.attr('data-fieldtype', alloment_people[i]['field_type']);
        option.attr('value', alloment_people[i]['officer_id']).text((alloment_people[i]["identification_no"]!=null ? alloment_people[i]["identification_no"] : alloment_people[i]["register_no"]));

        $("#person_id").append(option); 
    }*/
}

$("#certificate_no").live('change',function(){

    $(this).parent().find("#hidden_certificate_no").val($(this).val());
    $(this).parent().find( '.validate_edit_from_cert_live' ).html(" ");
});

$("#share_buyback").live('change',function(){
    /*console.log($(this).val());
    console.log($(this).parent().parent().parent().find("#current_number_of_share").val());*/
    var sum_total = 0, new_sum_number_of_share = 0;

    console.log(parseInt($(this).parent().parent().parent().find('#current_number_of_share').val()));
    if(parseInt($(this).val().replace(/\,/g,'')) > parseInt($(this).parent().parent().parent().find('#current_number_of_share').val().replace(/\,/g,'')))
    {
        $(this).val(0);
    }
    

    var input_num = $(this).parent().parent().parent().attr("num");
    $new_number_of_share = parseInt($(this).parent().parent().parent().find("#current_number_of_share").val().replace(/\,/g,'')) - parseInt($(this).val().replace(/\,/g,''));
    $(this).parent().parent().parent().find("#show_new_number_of_share").text(addCommas($new_number_of_share));

    $('input[name="new_number_of_share['+input_num+']"]').val(addCommas($new_number_of_share));

    $('.share_buyback').each(function() {
        console.log($(this).val());
        if($(this).val() == "")
        {
            sum_total += 0;
            $(this).val(0);
        }
        else
        {
            sum_total += parseFloat($(this).val().replace(/\,/g,''));
        }
        
    });
    
    $("#total_share_buyback").text(addCommas(sum_total));

    $('.new_number_of_share').each(function() {
        console.log($(this).val());
        if($(this).val() == "")
        {
            new_sum_number_of_share += 0;
            $(this).val(0);
        }
        else
        {
            new_sum_number_of_share += parseFloat($(this).val().replace(/\,/g,''));
        }
        
    });

    $("#total_new_number_of_share").text(addCommas(new_sum_number_of_share));

    //console.log((parseFloat($("#total_number_of_share").text().replace(/\,/g,'')) / sum_total).toFixed(2));
    $("#buyback_share").val((sum_total / (parseFloat($("#total_number_of_share").text().replace(/\,/g,''))) * 100 ).toFixed(1));

    if(parseInt($(this).val()) == 0 || parseInt($new_number_of_share) == 0)
    {
        $(this).parent().parent().parent().find("#certificate_no").attr('disabled', true);
        $(this).parent().parent().parent().find("#certificate_no").val("");
        $(this).parent().parent().parent().find("#hidden_certificate_no").val(" ");
        
        //$('#buyback_form').formValidation('enableFieldValidators', 'certificate_no['+input_num+']', false).formValidation('resetField', 'certificate_no['+input_num+']');
        //$('#buyback_form').formValidation('removeField', 'certificate_no['+input_num+']');
    }
    else
    {
        $(this).parent().parent().parent().find("#certificate_no").attr('disabled', false);
        //$('#buyback_form').formValidation('addField', 'certificate_no['+input_num+']', certificate_no);
        //$('#buyback_form').formValidation('enableFieldValidators', 'certificate_no['+input_num+']', true).formValidation('resetField', 'certificate_no['+input_num+']');
    }
    

    //$('#buyback_form').formValidation('revalidateField', 'certificate_no['+input_num+']');

});

/*$("#cancel_buyback").on("click", function() {
    //console.log("inin");
    window.close();
});*/

function confirmBuyback(buyback_object)
{
    console.log(buyback_object);
    console.log(buyback);

    //buyback_object["hidden_certificate_no"] = buyback_object["hidden_certificate_no"].filter(function(val){return val});
    buyback_object["current_number_of_share"] = buyback_object["current_number_of_share"].filter(function(val){return val});
    buyback_object["field_type"] = buyback_object["field_type"].filter(function(val){return val});
    buyback_object["member_name"] = buyback_object["member_name"].filter(function(val){return val});
    buyback_object["new_number_of_share"] = buyback_object["new_number_of_share"].filter(function(val){return val});
    buyback_object["officer_id"] = buyback_object["officer_id"].filter(function(val){return val});
    buyback_object["share_buyback"] = buyback_object["share_buyback"].filter(function(val){return val});
    //buyback_object["hidden_certificate_no"] = buyback_object["hidden_certificate_no"].filter(function(val){return val});

    $(".confirm_buyback_add_row").empty();

    if(buyback_object["officer_id"] != undefined) 
    {   
        //num_from_person = buyback_object["officer_id"].length;
        for(var i = 0; i < buyback_object["officer_id"].length; i++)
        {
           /* total_existing +=  parseInt(transfer_object["current_share"][i]);
            new_share = parseInt(transfer_object["current_share"][i])-parseInt(transfer_object["share_transfer"][i]);
            total_new_share_tranfer += new_share;*/

            $b=""; 
            $b += '<tr class="confirm_buyback_add_row">';
            $b += '<td>'+(i+1)+'</td>';
            $b += '<td>'+buyback_object["member_name"][i]+'</td>';
            $b += '<td style="text-align:right;">'+addCommas(buyback_object["current_number_of_share"][i])+'</td>';
            $b += '<td style="text-align:right;">'+addCommas(buyback_object["share_buyback"][i])+'</td>';
            $b += '<td style="text-align:right;">'+addCommas(buyback_object["new_number_of_share"][i])+'</td>';
            if(buyback != null)
            {
                for(var t = 0; t < buyback.length; t++)
                {
                    if((buyback_object["member_name"][i] == buyback[t]["name"] && 0 > parseInt(buyback[t]["number_of_share"])) || (buyback_object["member_name"][i] == buyback[t]["company_name"] && 0 > parseInt(buyback[t]["number_of_share"])) || (buyback_object["member_name"][i] == buyback[t]["client_company_name"] && 0 > parseInt(buyback[t]["number_of_share"])))
                    {
                        $b += '<td><div class="transfer_group"><input type="hidden" class="form-control cert_id" name="cert_id[]" id="cert_id" value="'+buyback[t]["cert_id"]+'"/><input type="text" class="form-control edit_certificate_no check_cert_in_live" name="certificate_no['+i+']" value="'+buyback[t]["certificate_no"]+'" id="certificate_no"/><input type="hidden" class="form-control" name="hidden_certificate_no['+i+']" value="'+buyback[t]["certificate_no"]+'" id="hidden_certificate_no"/><div class="validate_edit_from_cert"></div><div class="validate_edit_from_cert_live"></div></div></td>';
                    }
                }
            }
            else
            {
                $b += '<td><div class="transfer_group"><input type="hidden" class="form-control cert_id" name="cert_id[]" id="cert_id" value=""/><input type="text" class="form-control edit_certificate_no check_cert_in_live" name="certificate_no['+i+']" value="" id="certificate_no"/><input type="hidden" class="form-control" name="hidden_certificate_no['+i+']" value="" id="hidden_certificate_no"/><div class="validate_edit_from_cert"></div><div class="validate_edit_from_cert_live"></div></div></td>';
            }
            
            //$b += '<td style="text-align: center">'+((buyback_object["hidden_certificate_no"][i] != undefined)?buyback_object["hidden_certificate_no"][i]:" ")+'</td>';
            $b += '</tr>';

            $("#confirm_buyback_add").append($b);

            $('#buyback_form').formValidation('addField', 'certificate_no['+i+']', certificate_no);
        }
    }

    if(access_right_member_module == "read" || client_status != "1")
    {
        $(".share_buyback").attr("disabled", true);
        $(".check_cert_in_live").attr("disabled", true);
    }
}
    
    