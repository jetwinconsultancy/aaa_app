// $("[name='hidden_group_checkbox']").bootstrapSwitch({
//     // state: state_checkbox,
//     size: 'small',
//     onColor: 'primary',
//     onText: 'YES',
//     offText: 'NO',
//     // Text of the center handle of the switch
//     labelText: '&nbsp',
//     // Width of the left and right sides in pixels
//     handleWidth: '45px',
//     // Width of the center handle in pixels
//     labelWidth: 'auto',
//     baseClass: 'bootstrap-switch',
//     wrapperClass: 'wrapper'
// });

// console.log(client_signing_info);

var pathArray = location.href.split( '/' );
var protocol = pathArray[0];
var host = pathArray[2];
var folder = pathArray[3];

var company_code = $('#company_code').val();

// console.log(pathArray);

// $(document).on('click',"#submit_company_particular",function(e){
//     $('#loadingmessage').show();

//     var client_id          = $('#client_id').val();
//     var company_code       = $("#company_code").val();
//     var fs_company_info_id = $('input[name=fs_company_info_id]').val();

//     var reason_of_changing_fc = $('#reason_of_changing_fc').val();

//     $.ajax({ //Upload common input
//       url: "financial_statement/submit_company_particular",
//       type: "POST",
//       data: $('#form_fs_company_info').serialize() + '&fs_company_info_id=' + fs_company_info_id + '&company_code=' + company_code + '&firm_id=' + firm_id + '&reason_of_changing_fc=' + reason_of_changing_fc,
//       dataType: 'json',
//       success: function (response,data) {

//         console.log(response);
//         $('input[name=fs_company_info_id]').val(response['id']);

//         if(!(response['id'] === '') && !(response['id'] === 'undefined'))
//         {
//             toastr.success("The data will be saved to database.", "Success saved!");
//             window.location.href = "financial_statement/create/" + client_id + "/" + response['id'];
//         }
//         else
//         {
//             toastr.error("Something went wrong. Please tray again later.", "");
//         }

//         $('#loadingmessage').hide();
//         }
//     });
// });



// $('.director_list').change(function() {
//     var element = this; // selected element
//     var dropdownval = $(this).val();
//     var dropdown2_val = $('.director_list').not(element).val();

//     var fs_company_info_id = $('input[name=fs_company_info_id]').val();
//     var company_code       = $("#company_code").val();

//     $.ajax({ //Upload common input
//         url: "financial_statement/get_this_directors_list",
//         type: "POST",
//         data: {fs_company_info_id: fs_company_info_id, company_code:company_code},
//         dataType: 'json',
//         success: function (response,data) {

//         $('.director_list').not(element).empty();

//         for(index in response) {
//             if(index === dropdown2_val)
//             {
//                 $('.director_list').not(element).prepend('<option value="' + index + '" selected="selected">' + response[index] + '</option>');
//             }
//             else
//             {
//                 $('.director_list').not(element).prepend('<option value="' + index + '">' + response[index] + '</option>');
//             }
//         }

//         $('.director_list').not(element).find('option[value="' + dropdownval + '"]').remove();
//         }
//     });

// });

function change_value_prior_year()
{
   var last_fye_begin = $('#last_fye_begin').val();
   var last_fye_end = $('#last_fye_end').val();

   var selected = $('#effect_of_restatement_since').val();
   // console.log($('#effect_of_restatement_since').val());

   $('#effect_of_restatement_since').empty();

   $('#effect_of_restatement_since').append(new Option(last_fye_begin, last_fye_begin));
   $('#effect_of_restatement_since').append(new Option(last_fye_end, last_fye_end));

   $('#effect_of_restatement_since').val(selected);
}

$('#is_prior_year_amount_restated').on('change', function() 
{
    if(this.value == 1)
    {
        $('.show_effect_of_restatement_since').show();
    }
    else
    {
        $('.show_effect_of_restatement_since').hide();
    }
});

function show_hide_reason_of_changing()
{
    // var first_set = $('#form_fs_company_info.reason_changing_fc').val();
    var first_set = $('#form_fs_company_info input[name=first_set_checkbox]').val();

    if($('#last_year_fc_currency_id').val() == $('#current_year_fc_currency_id').val())
    {
        $('.reason_changing_fc').hide();
    }
    else
    {
        if(first_set == 0)
        {
            $('.reason_changing_fc').show();
        }
    }

    // if($('#current_year_fc_currency_id').val() == $('#current_year_pc_currency_id').val())
    // {
    //     $('.reason_changing_fc_pc').hide();
    // }
    // else
    // {
    //     $('.reason_changing_fc_pc').show();
    // }
}

function show_is_group_consolidated()
{
    if($('#group_type').val() == "2" || $('#group_type').val() == "3")
    {
        $('.group_consolidated').show();
    }
    else
    {
        $('.group_consolidated').hide();
    }
}

/* ----------------------------------------- */

function ajaxCall() 
{
    this.send = function(data, url, method, success, type) {
        type = type||'json';
        //console.log(data);
        var successRes = function(data) {
            success(data);
        };

        var errorRes = function(e) {
          //console.log(e);
          alert("Error found \nError Code: "+e.status+" \nError Message: "+e.statusText);
        };
        $.ajax({
            url: url,
            type: method,
            data: data,
            success: successRes,
            error: errorRes,
            dataType: type,
            timeout: 60000
        });

    }

}

var cm1 = new Chairman();

function Chairman() {
    var base_url = window.location.origin;  
    var call = new ajaxCall();

    this.getDirectorSignature2 = function(director_signature_1_id) 
    {
        var url = base_url+"/"+folder+"/"+'companytype/getDirectorSignature2';
        // console.log(url);
        var method = "post";
        var data = {"company_code": company_code, "director_signature_1_id": director_signature_1_id};
        $('.director_signature_2').find("option:eq(0)").html("Please wait..");

        call.send(data, url, method, function(data) {
            //console.log(data);
            //$('.director_signature_2').find("option:eq(0)").html("Select Director Signature 2");
            //console.log(data);
            $(".director_signature_2 option").remove();

            var option = $('<option />');
            option.attr('value', '0').text("Select Director Signature 2");
            $('.director_signature_2').append(option);

            if(data.tp == 1){
                if(data['result'].length == 0)
                {
                    $(".director_signature_2").attr("disabled", "disabled");
                    
                    $('.director_signature_2_group').removeClass("has-error");
                    $('.director_signature_2_group').removeClass("has-success");
                    $('.director_signature_2_group .help-block').hide();
                }
                else
                {
                    //$(".director_signature_2 option").remove(); 
                    
                    $.each(data['result'], function(key, val) {
                        var option = $('<option />');
                        option.attr('value', key).text(val);
                        // if(data.selected_all_director2 != null && key == data.selected_all_director2)
                        // {
                        //     option.attr('selected', 'selected');
                        // }

                        if(fs_report_details[0]['director_signature_id_2'] != null && key == fs_report_details[0]['director_signature_id_2'])
                        {
                            option.attr('selected', 'selected');
                        }
                        else if(client_signing_info !== false)
                        {
                            if(client_signing_info[0]['director_signature_2'] != null)
                            {
                                option.attr('selected', 'selected');
                            }
                        }

                        $('.director_signature_2').append(option);
                    });
                }
                
                $("#form_fs_company_info .director_signature_2").select2('destroy'); 
                $("#form_fs_company_info .director_signature_2").select2({ minimumResultsForSearch: -1 });
                //$(".nationality").prop("disabled",false);
            }
            else{
                alert(data.msg);
            }
        }); 
    };

    this.getTodayDirectorSignature2 = function(director_signature_1_id) {

        
        var url = base_url+"/"+folder+"/"+'companytype/getTodayDirectorSignature2';
        //console.log(url);
        var method = "post";
        var data = {"company_code": company_code, "director_signature_1_id": director_signature_1_id};
        $('.director_signature_2').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            //console.log(data);
            //$('.director_signature_2').find("option:eq(0)").html("Select Director Signature 2");
            $(".director_signature_2 option").remove(); 

            var option = $('<option />');
            option.attr('value', '0').text("Select Director Signature 2");
            $('.director_signature_2').append(option);
            //console.log(data);
            if(data.tp == 1){
                if(data['result'].length == 0)
                {
                    $(".director_signature_2").attr("disabled", "disabled");
                    //$(".director_signature_2 option").remove();
                    $('.director_signature_2_group').removeClass("has-error");
                    $('.director_signature_2_group').removeClass("has-success");
                    $('.director_signature_2_group .help-block').hide();
                }
                else
                {
                    //$(".director_signature_2 option").remove(); 
                    $(".director_signature_2").attr("disabled", false);
                    $.each(data['result'], function(key, val) {
                        var option = $('<option />');
                        option.attr('value', key).text(val);
                        if(data.selected_all_director2 != null && key == data.selected_all_director2)
                        {
                            option.attr('selected', 'selected');
                        }
                        $('.director_signature_2').append(option);
                    });
                }
                
                $("#form_fs_company_info .director_signature_2").select2('destroy'); 
                $("#form_fs_company_info .director_signature_2").select2({ minimumResultsForSearch: -1 });
                //$(".nationality").prop("disabled",false);
            }
            else{
                alert(data.msg);
            }
        }); 

        // $("#form_fs_company_info .director_signature_2").select2({ minimumResultsForSearch: -1 });
    };

    this.getDirectorSignature1 = function() 
    {
        var url = base_url+"/"+folder+"/"+'companytype/getDirectorSignature1';
        // console.log(url);
        var method = "post";
        var data = {"company_code": company_code};
        $('.director_signature_1').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            //$('.director_signature_1').find("option:eq(0)").html("Select Director Signature 1");
            $(".director_signature_1 option").remove(); 

            var option = $('<option />');
            option.attr('value', '0').text("Select Director Signature 1");
            $('.director_signature_1').append(option);
            //console.log(data);
            if(data.tp == 1){
                
                $.each(data['result'], function(key, val) {

                    // console.log(val);
                    var option = $('<option />');
                    option.attr('value', key).text(val);

                    // if(data.selected_all_director1 != null && key == data.selected_all_director1)
                    // {
                    //     option.attr('selected', 'selected');
                    // }

                    if(fs_report_details[0]['director_signature_id_1'] != null && key == fs_report_details[0]['director_signature_id_1'])
                    {
                        option.attr('selected', 'selected');
                    }
                    else if(client_signing_info !== false)
                    {
                        if(client_signing_info[0]['director_signature_1'] != null)
                        {
                            option.attr('selected', 'selected');
                        }
                    }

                    $('.director_signature_1').append(option);
                });
                directorSignature2($('#director_signature_1').val());

                $("#form_fs_company_info .director_signature_1").select2('destroy'); 
                $("#form_fs_company_info .director_signature_1").select2({ minimumResultsForSearch: -1 });
                //$(".nationality").prop("disabled",false);
            }
            else{
                alert(data.msg);
            }
        }); 
    };

    this.getTodayDirectorSignature1 = function() {
        var url = base_url+"/"+folder+"/"+'companytype/getTodayDirectorSignature1';
        //console.log(url);
        var method = "post";
        var data = {"company_code": company_code};
        $('.director_signature_1').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            //console.log(data);
            //$('.director_signature_1').find("option:eq(0)").html("Select Director Signature 1");
            $(".director_signature_1 option").remove(); 
            
            var option = $('<option />');
            option.attr('value', '0').text("Select Director Signature 1");
            $('.director_signature_1').append(option);
            //console.log(data);
            console.log($('.director_signature_1')); 

            if(data.tp == 1){
                //$(".director_signature_1 option").remove(); 
                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    if(data.selected_all_director1 != null && key == data.selected_all_director1)
                    {
                        option.attr('selected', 'selected');
                    }
                    $('.director_signature_1').append(option);
                });
                console.log($('.director_signature_1').val());
                directorSignature2($('#director_signature_1').val()); 
                // directorSignature2(1); 

                $("#form_fs_company_info .director_signature_1").select2('destroy'); 
                $("#form_fs_company_info .director_signature_1").select2();
                //$(".nationality").prop("disabled",false);
            }
            else{
                alert(data.msg);
            }
        }); 

        // $("#form_fs_company_info .director_signature_1").select2({ minimumResultsForSearch: -1 });
    };

    this.getChairman = function() {
        var url = base_url+"/"+folder+"/"+'companytype/getChairman';
        //console.log(url);
        var method = "post";
        var data = {"company_code": company_code};
        $('.chairman').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            //console.log(data);
            //$('.chairman').find("option:eq(0)").html("Select Chairman");
            $(".chairman option").remove(); 

            var option = $('<option />');
            option.attr('value', '0').text("Select Chairman");
            $('.chairman').append(option);
            //console.log(data);
            if(data.tp == 1){
                
                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    if(data.selected_chairman != null && key == data.selected_chairman)
                    {
                        option.attr('selected', 'selected');
                    }
                    $('.chairman').append(option);
                });
                //$(".nationality").prop("disabled",false);
            }
            else{
                alert(data.msg);
            }
        }); 
    };

    this.getAllChairman = function() {
        var url = base_url+"/"+folder+"/"+'companytype/getAllChairman';
        //console.log(url);
        var method = "post";
        var data = {"company_code": company_code};
        $('.chairman').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            //console.log(data);
            //$('.chairman').find("option:eq(0)").html("Select Chairman");
            $(".chairman option").remove(); 
            
            var option = $('<option />');
            option.attr('value', '0').text("Select Chairman");
            $('.chairman').append(option);
            //console.log(data);
            if(data.tp == 1){
                //$(".chairman option").remove(); 
                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    if(data.selected_all_chairman != null && key == data.selected_all_chairman)
                    {
                        option.attr('selected', 'selected');
                    }
                    $('.chairman').append(option);
                });
                //$(".nationality").prop("disabled",false);
            }
            else{
                alert(data.msg);
            }
        }); 
    };
}

if(client_signing_info !== false)
{
    if(client_signing_info[0]['show_all'])
    {
        cm1.getAllChairman();
    }

    if(client_signing_info[0]['director_signature_id_2'])
    {
        cm1.getDirectorSignature2(client_signing_info[0]['director_signature_id_1']);
        $(".director_signature_2").removeAttr("disabled");
    }
}

function directorSignature2(directorSignature2) 
{
    var director_signature_1_id = directorSignature2;

    if(director_signature_1_id != '' && director_signature_1_id != 0)
    {
        cm1.getDirectorSignature2(director_signature_1_id);
        showDS2 = false;
        $(".btnShowAllDirectorSig2").prop('value', 'Show Today');
        $(".director_signature_2").removeAttr("disabled");

        if(director_signature_1_id == '' && director_signature_1_id == 0)
        {
            $('.director_signature_2_group').addClass("has-error");
            $('.director_signature_2_group').removeClass("has-success");
            $('.director_signature_2_group .help-block').show();
        }
        else
        {
            $('.director_signature_2_group').removeClass("has-error");
            $('.director_signature_2_group').removeClass("has-success");
            $('.director_signature_2_group .help-block').hide();
        }
    }
    else
    {
        $(".director_signature_2").attr("disabled", "disabled");
        $(".director_signature_2 option:gt(0)").remove();

        $(".director_signature_2 .select2-chosen").text('Select Director Signature 2');
        
        //$('#setup_form').formValidation('revalidateField', 'director_signature_2');
        $('.director_signature_2_group').removeClass("has-error");
        $('.director_signature_2_group').removeClass("has-success");
        $('.director_signature_2_group .help-block').hide();
    }
};

var showCM, showDS1, showDS2;
showDS1 = true;
showDS2 = true;

function showAllDirectorSig1(directorsig1box) 
{
    console.log("hello");

    var tr = jQuery(directorsig1box).parent().parent();
    //chairmanbox.checked
    if (showDS1) 
    {
        console.log("Case 1");

        tr.find('select[name="director_signature_1"]').html(""); 
        tr.find('select[name="director_signature_1"]').append($('<option>', {
            value: '0',
            text: 'Select Director Signature 1'
        }));
        cm1.getDirectorSignature1();

        showDS1 = false;
        $(".btnShowAllDirectorSig1").prop('value', 'Show Today');
    }
    else
    {
        console.log("Case 2");

        tr.find('select[name="director_signature_1"]').html(""); 
        tr.find('select[name="director_signature_1"]').append($('<option>', {
            value: '0',
            text: 'Select Director Signature 1'
        }));
        cm1.getTodayDirectorSignature1();

        showDS1 = true;
        $(".btnShowAllDirectorSig1").prop('value', 'Show All');
    }
}

$('#form_fs_company_info').formValidation({
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

        chairman: {
            row: '.chairman_group', 
            validators: {
                callback: {
                    message: 'The Chairman field is required',
                    callback: function(value, validator, $field) {
                        var options = validator.getFieldElements('chairman').val();
                        //console.log(options);
                        return (options != null && options != "0");
                    }
                }
            }
        },
        director_signature_1: {
            row: '.director_signature_1_group', 
            validators: {
                callback: {
                    message: 'The Director Signature 1 field is required',
                    callback: function(value, validator, $field) {
                        var options = validator.getFieldElements('director_signature_1').val();
                        //console.log(options);
                        return (options != null && options != "0");
                    }
                }
            }
        },
        director_signature_2: {
            row: '.director_signature_2_group', 
            validators: {
                callback: {
                    message: 'The Director Signature 2 field is required',
                    callback: function(value, validator, $field) {
                        var options = validator.getFieldElements('director_signature_2').val();
                        //console.log(options);
                        return (options != null && options != "0");
                    }
                }
            }
        }
    }
});

function showAllDirectorSig2(directorsig2box) 
{
    var tr = jQuery(directorsig2box).parent().parent();
    var ds_1_id = $('select[name="director_signature_1"]').val();
    $(".director_signature_2").attr("disabled", false);
    //chairmanbox.checked
    if (showDS2) 
    {
        tr.find('select[name="director_signature_2"]').html(""); 
        tr.find('select[name="director_signature_2"]').append($('<option>', {
            value: '0',
            text: 'Select Director Signature 2'
        }));
        cm1.getDirectorSignature2(ds_1_id);

        showDS2 = false;
        $(".btnShowAllDirectorSig2").prop('value', 'Show Today');
    }
    else
    {
        tr.find('select[name="director_signature_2"]').html(""); 
        tr.find('select[name="director_signature_2"]').append($('<option>', {
            value: '0',
            text: 'Select Director Signature 2'
        }));
        cm1.getTodayDirectorSignature2(ds_1_id);

        showDS2 = true;
        $(".btnShowAllDirectorSig2").prop('value', 'Show All');
    }
}

// $(function() {
//     refreshSigningInfo();
// });

function refreshSigningInfo() 
{
    var cm = new Chairman();
    // if(!client_signing_info)
    // {
    //     cm.getChairman();
    // }
    // else
    // {
    //     cm.getAllChairman();
    // }

    
    cm.getDirectorSignature1();
};

$(document).on('change','#setup_form #director_signature_1',function(e){
    $('#setup_form').formValidation('revalidateField', 'director_signature_1');
});

$(document).on('change','#setup_form #director_signature_2',function(e){
    $('#setup_form').formValidation('revalidateField', 'director_signature_2');
});


