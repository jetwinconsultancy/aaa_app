var cm1 = new Chairman();
var pathArray = location.href.split( '/' );
var protocol = pathArray[0];
var host = pathArray[2];
var folder = pathArray[3];
var array_client_billing_info_id = [];

function ajaxCall() {
    this.send = function(data, url, method, success, type) {
        type = type||'json';
        //console.log(data);
        var successRes = function(data) {
            success(data);
        };

        var errorRes = function(e) {
            //console.log(e);
            if(e.status != 200)
            {
                alert("Error found \nError Code: "+e.status+" \nError Message: "+e.statusText);
            }
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

function Chairman() {
    var base_url = window.location.origin;  
    var call = new ajaxCall();

    this.getDirectorSignature2 = function(director_signature_1_id) {

        
        var url = base_url+"/"+folder+"/"+'companytype/getDirectorSignature2';
        //console.log(url);
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
                        if(data.selected_all_director2 != null && key == data.selected_all_director2)
                        {
                            option.attr('selected', 'selected');
                        }
                        $('.director_signature_2').append(option);
                    });
                }
                
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
                
                //$(".nationality").prop("disabled",false);
            }
            else{
                alert(data.msg);
            }
        }); 
    };

    this.getDirectorSignature1 = function() {
        var url = base_url+"/"+folder+"/"+'companytype/getDirectorSignature1';
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
            if(data.tp == 1){
                
                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    if(data.selected_all_director1 != null && key == data.selected_all_director1)
                    {
                        option.attr('selected', 'selected');
                    }
                    $('.director_signature_1').append(option);
                });
                directorSignature2($('.director_signature_1').val());
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
                directorSignature2($('.director_signature_1').val());
                //$(".nationality").prop("disabled",false);
            }
            else{
                alert(data.msg);
            }
        }); 
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

$(function() {
    refreshSigningInfo();
});

function refreshSigningInfo() {
    var cm = new Chairman();
    if(!client_signing_info)
    {
        cm.getChairman();
    }
    else
    {
        cm.getAllChairman();
    }

    cm.getDirectorSignature1();
    
};

// var serviceValidators = {
//             row: '.input-dropdown',
//             validators: {
//                 callback: {
//                     message: 'The Service field is required',
//                     callback: function(value, validator, $field) {
//                         var num = jQuery($field).parent().parent().parent().attr("num");
//                         var options = validator.getFieldElements('service['+num+']').val();
//                         //console.log("options====="+options);
//                         return (options != null && options != "0");
//                     }
//                 }
//             }
//         },
// invoiceDescriptionValidators = {
//     row: '.input-group', 
//     validators: {
//         notEmpty: {
//             message: 'The Invoice Description field is required.'
//         }
//     }
// },
// amountValidators = {
//     row: '.input-group', 
//     validators: {
//         notEmpty: {
//             message: 'The Amount field is required.'
//         },
//         integer: {
//             message: 'The value is not an integer',
//             // The default separators
//             thousandsSeparator: ',',
//             decimalSeparator: '.'
//         }
//     }
// },
// currencyValidators = {
//     row: '.input-dropdown',
//     validators: {
//         callback: {
//             message: 'The Currency field is required',
//             callback: function(value, validator, $field) {
//                 var num = jQuery($field).parent().parent().parent().attr("num");
//                 var options = validator.getFieldElements('currency['+num+']').val();
//                 //console.log("options====="+options);
//                 return (options != null && options != "0");
//             }
//         }
//     }
// },
// unitPricingValidators = {
//     row: '.input-dropdown',
//     validators: {
//         callback: {
//             message: 'The Unit Pricing field is required',
//             callback: function(value, validator, $field) {
//                 var num = jQuery($field).parent().parent().parent().attr("num");
//                 var options = validator.getFieldElements('unit_pricing['+num+']').val();
//                 //console.log("options====="+options);
//                 return (options != null && options != "0");
//             }
//         }
//     }
// };

// servicingFirmValidators = {
//     row: '.input-dropdown',
//     validators: {
//         callback: {
//             message: 'The Servicing Firm field is required',
//             callback: function(value, validator, $field) {
//                 var num = jQuery($field).parent().parent().parent().attr("num");
//                 var options = validator.getFieldElements('servicing_firm['+num+']').val();
//                 //console.log("options====="+options);
//                 return (options != null && options != "0");
//             }
//         }
//     }
// };

$.ajax({
    type: "GET",
    url: "masterclient/get_reminder",
    dataType: "json",
    async: false,
    success: function(data){
        if(data.tp == 1){
            $.each(data['result'], function(key, val) {
                var option = $('<option />');
                option.attr('value', key).text(val);

                var str = client_selected_reminder;

                if(str)
                {
                    for($k = 0; $k < str.length; $k++)
                    {
                        if(key == str[$k]['selected_reminder'])
                        {
                            option.attr('selected', 'selected');
                        }
                    }
                }

                $('#select_reminder').append(option);
            });
        }
        else{
            alert(data.msg);
        }
    }               
});

function search_related_group_function()
{
    $('#loadingmessage').show();
    $.ajax({
        type: "GET",
        url: "masterclient/get_client_list",
        dataType: "json",
        //async: false,
        success: function(data){
            $('#loadingmessage').hide();
            if(data.tp == 1)
            {
                $.each(data['result'], function(key, val) {
                    if(key != $(".related_group_client_id").val())
                    {
                        var option = $('<option />');
                        option.attr('value', key).text(val);

                        var str = client_selected_setup_group;

                        if(str)
                        {
                            for($k = 0; $k < str.length; $k++)
                            {
                                if(key == str[$k]['selected_child_client_id'])
                                {
                                    option.attr('selected', 'selected');
                                }
                            }
                        }

                        $(".setup_select_group").append(option);
                    }
                });

                $.each(data['result'], function(key, val) {
                    if(key != $(".related_group_client_id").val())
                    {
                        var option = $('<option />');
                        option.attr('value', key).text(val);
                        
                        var str = client_selected_related_party;

                        if(str)
                        {
                            for($k = 0; $k < str.length; $k++)
                            {
                                if(key == str[$k]['selected_child_client_id'])
                                {
                                    option.attr('selected', 'selected');
                                }
                            }
                        }

                        $(".setup_select_related_party").append(option);
                    }
                });

                $('#related_group_form').find('[name="select_group[]"]')
                    .multiselect({
                        buttonWidth: '300px',
                        maxHeight: 300,
                        enableFiltering: true,
                        enableCaseInsensitiveFiltering: true,
                        buttonText: function(options, select) 
                        {
                            if (options.length === 0) 
                            {
                                return 'Select the Group';
                            }
                            else if (options.length > 1) 
                            {
                                return 'More than 1 Group selected!';
                            }
                            else {
                                return options.length+' selected!';
                            }
                        },
                        onChange: function(option, checked, select) {
                            var opselected = $('#related_group_form').find('[name="select_group[]"]').val();
                            // Set the value
                            $('#related_group_form').find('[name="select_related_party[]"]').multiselect('select',opselected);
                        }
                    });

                $('#related_group_form').find('[name="select_related_party[]"]')
                    .multiselect({
                        buttonWidth: '300px',
                        maxHeight: 300,
                        enableFiltering: true,
                        enableCaseInsensitiveFiltering: true,
                        buttonText: function(options, select) 
                        {
                            if (options.length === 0) 
                            {
                                return 'Select the Related Party';
                            }
                            else if (options.length > 1) 
                            {
                                return 'More than 1 Related Party selected!';
                            }
                            else {
                                return options.length+' selected!';
                            }
                        }
                    });
            }
            else{
                alert(data.msg);
            }
        }               
    });
}

$(document).ready(function () {
    $('#reminder_form').find('[name="select_reminder[]"]')
        .multiselect({
        buttonWidth: '300px',
        maxHeight: 300,
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        buttonText: function(options, select) 
        {
            if (options.length === 0) 
            {
                return 'Select the Reminder';
            }
            else if (options.length > 1) 
            {
                return 'More than 1 reminder selected!';
            }
            else {
                return options.length+' selected!';
                 // var labels = [];
                 // options.each(function() {
                 //     if ($(this).attr('label') !== undefined) {
                 //         labels.push($(this).attr('label'));
                 //     }
                 //     else {
                 //         labels.push($(this).html());
                 //     }
                 // });
                 // return labels.join(', ') + '';
            }
        }
    });
});

$('#signing_information_form').formValidation({
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

        // chairman: {
        //     row: '.chairman_group', 
        //     validators: {
        //         callback: {
        //             message: 'The Chairman field is required',
        //             callback: function(value, validator, $field) {
        //                 var options = validator.getFieldElements('chairman').val();
        //                 //console.log(options);
        //                 return (options != null && options != "0");
        //             }
        //         }
        //     }
        // },
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
        }/*,
        contact_name: {  
            validators: {
                notEmpty: {
                    message: 'The Name field is required.'
                }
            }
        },
        contact_phone: {  
            validators: {
                notEmpty: {
                    message: 'The Phone field is required.'
                }
            }
        },
        contact_email: {  
            validators: {
                notEmpty: {
                    message: 'The Email field is required.'
                }
            }
        }*/
    }
});

$('.show_contact_phone').click(function(e){
    e.preventDefault();
    $(this).parent().parent().find(".contact_phone_toggle").toggle();
    //console.log($(this).parent().parent());
    var icon = $(this).find(".fa");
    if(icon.hasClass("fa-arrow-down"))
    {
        icon.addClass("fa-arrow-up").removeClass("fa-arrow-down");
        $(this).find(".toggle_word").text('Show less');
    }
    else
    {
        icon.addClass("fa-arrow-down").removeClass("fa-arrow-up");
        $(this).find(".toggle_word").text('Show more');
    }
});

$('.show_contact_email').click(function(e){
    e.preventDefault();
    $(this).parent().parent().find(".contact_email_toggle").toggle();
    var icon = $(this).find(".fa");
    if(icon.hasClass("fa-arrow-down"))
    {
        icon.addClass("fa-arrow-up").removeClass("fa-arrow-down");
        $(this).find(".toggle_word").text('Show less');
    }
    else
    {
        icon.addClass("fa-arrow-down").removeClass("fa-arrow-up");
        $(this).find(".toggle_word").text('Show more');
    }
});

$('.hp').intlTelInput({
    preferredCountries: [ "sg", "my"],
    initialCountry: "auto",
    formatOnDisplay: false,
    nationalMode: true,
    geoIpLookup: function(callback) {
        jQuery.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
            var countryCode = (resp && resp.country) ? resp.country : "";
            callback(countryCode);
        });
    },
    customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
      return "" ;
    },
    utilsScript: "../themes/default/js/utils.js"
});

//edit
if(client_contact_info_phone != null)
{
    for (var h = 0; h < client_contact_info_phone.length; h++) 
    {
        var clientContactInfoPhoneArray = client_contact_info_phone[h].split(',');

        if(clientContactInfoPhoneArray[2] == 1)
        {
            $(".fieldGroup_contact_phone").find('.main_contact_phone').intlTelInput("setNumber", clientContactInfoPhoneArray[1]);
            $(".fieldGroup_contact_phone").find('.main_hidden_contact_phone').attr("value", clientContactInfoPhoneArray[1]);
            $(".fieldGroup_contact_phone").find('.main_contact_phone_primary').attr("value", clientContactInfoPhoneArray[1]);
            $(".fieldGroup_contact_phone").find(".button_increment_contact_phone").css({"visibility": "visible"});
        }
        else
        {
            
            $(".fieldGroupCopy_contact_phone").find('.hidden_contact_phone').attr("value", clientContactInfoPhoneArray[1]);
            $(".fieldGroupCopy_contact_phone").find('.contact_phone_primary').attr("value", clientContactInfoPhoneArray[1]);


            var fieldHTML = '<div class="input-group fieldGroup_contact_phone" style="margin-top:10px;">'+$(".fieldGroupCopy_contact_phone").html()+'</div>';

            //$('body').find('.fieldGroup_contact_phone:first').after(fieldHTML);
            $( fieldHTML).prependTo(".contact_phone_toggle");

            $('.contact_phone_toggle .fieldGroup_contact_phone').eq(0).find('.second_hp').intlTelInput({
                preferredCountries: [ "sg", "my"],
                formatOnDisplay: false,
                nationalMode: true,
                geoIpLookup: function(callback) {
                    jQuery.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
                        var countryCode = (resp && resp.country) ? resp.country : "";
                        callback(countryCode);
                    });
                },
                customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
                  return "" ;
                },
                utilsScript: "../themes/default/js/utils.js"
            });

            $('.contact_phone_toggle .fieldGroup_contact_phone').eq(0).find('.second_hp').intlTelInput("setNumber", clientContactInfoPhoneArray[1]);

            $('.contact_phone_toggle .fieldGroup_contact_phone').eq(0).find('.second_hp').on({
              keydown: function(e) {
                if (e.which === 32)
                  return false;
              },
              change: function() {
                this.value = this.value.replace(/\s/g, "");
              }
            });

            $(".fieldGroupCopy_contact_phone").find('.hidden_contact_phone').attr("value", "");
            $(".fieldGroupCopy_contact_phone").find('.contact_phone_primary').attr("value", "");
        }
    }
}
else
{
    $(".fieldGroup_contact_phone").find('.main_contact_phone').intlTelInput("setNumber", "");
}

if(client_contact_info_email != null)
{
    for (var h = 0; h < client_contact_info_email.length; h++) 
    {
        var clientContactInfoEmailArray = client_contact_info_email[h].split(',');

        if(clientContactInfoEmailArray[2] == 1)
        {
            $(".fieldGroup_contact_email").find('.main_contact_email').attr("value", clientContactInfoEmailArray[1]);
            $(".fieldGroup_contact_email").find('.main_contact_email_primary').attr("value", clientContactInfoEmailArray[1]);

            $(".fieldGroup_contact_email").find(".button_increment_contact_email").css({"visibility": "visible"});
        }
        else
        {
            $(".fieldGroupCopy_contact_email").find('.second_contact_email').attr("value", clientContactInfoEmailArray[1]);

            $(".fieldGroupCopy_contact_email").find('.contact_email_primary').attr("value", clientContactInfoEmailArray[1]);

            var fieldHTML = '<div class="input-group fieldGroup_contact_email" style="margin-top:10px; display: block !important;">'+$(".fieldGroupCopy_contact_email").html()+'</div>';

            //$('body').find('.fieldGroup_contact_email:first').after(fieldHTML);
            $( fieldHTML).prependTo(".contact_email_toggle");

            $(".fieldGroupCopy_contact_email").find('.second_contact_email').attr("value", "");
            $(".fieldGroupCopy_contact_email").find('.contact_email_primary').attr("value", "");
        }
    }
}
//

$(document).on('blur', '.check_empty_contact_phone', function(){
    $(this).parent().parent().find(".hidden_contact_phone").attr("value", $(this).intlTelInput("getNumber", intlTelInputUtils.numberFormat.E164));
    $(this).parent().parent().find(".contact_phone_primary").attr("value", $(this).intlTelInput("getNumber", intlTelInputUtils.numberFormat.E164));
});

$(document).on('blur', '.check_empty_contact_email', function(){
    $(this).parent().find(".contact_email_primary").attr("value", $(this).val());
});

$(document).ready(function() {

    $(document).on('click', '.contact_phone_primary', function(event){   
        event.preventDefault();
        var contact_phone_primary_radio_button = $(this);
        bootbox.confirm("Are you comfirm set as primary for this Phone Number?", function (result) {
            if (result) {
                contact_phone_primary_radio_button.prop( "checked", true );
                $( '#form_contact_phone' ).html("");
            }
        });
    });

    $(document).on('click', '.contact_email_primary', function(event){  
        event.preventDefault();
        var contact_email_primary_radio_button = $(this);
        bootbox.confirm("Are you comfirm set as primary for this Email?", function (result) {
            if (result) {
                contact_email_primary_radio_button.prop( "checked", true );
                $( '#form_contact_email' ).html("");
            }
        });
    });

    $(".check_empty_contact_phone").on({
      keydown: function(e) {
        if (e.which === 32)
          return false;
      },
      change: function() {
        this.value = this.value.replace(/\s/g, "");
      }
    });

    $(".addMore_contact_phone").click(function(){
        var number = $(".main_contact_phone").intlTelInput("getNumber", intlTelInputUtils.numberFormat.E164);

        var countryData = $(".main_contact_phone").intlTelInput("getSelectedCountryData");

        $(".contact_phone_toggle").show();
        $(".show_contact_phone").find(".fa").addClass("fa-arrow-up").removeClass("fa-arrow-down");
        $(".show_contact_phone").find(".toggle_word").text('Show less');

        $(".fieldGroupCopy_contact_phone").find('.second_contact_phone').attr("value", $(".main_contact_phone").val());
        $(".fieldGroupCopy_contact_phone").find('.hidden_contact_phone').attr("value", number);
        $(".fieldGroupCopy_contact_phone").find('.contact_phone_primary').attr("value", number);
        //$(".fieldGroupCopy").find('.second_local_fix_line').intlTelInput("setNumber", number);
        //$(".fieldGroupCopy_contact_phone").find('.second_contact_phone').intlTelInput("setCountry", countryData.iso2);

        var fieldHTML = '<div class="input-group fieldGroup_contact_phone" style="margin-top:10px;">'+$(".fieldGroupCopy_contact_phone").html()+'</div>';

        //$('body').find('.fieldGroup_contact_phone:first').after(fieldHTML);
        $( fieldHTML).prependTo(".contact_phone_toggle");

        $('.contact_phone_toggle .fieldGroup_contact_phone').eq(0).find('.second_hp').intlTelInput({
            preferredCountries: [ "sg", "my"],
            formatOnDisplay: false,
            nationalMode: true,
            initialCountry: countryData.iso2,
            geoIpLookup: function(callback) {
                jQuery.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
                    var countryCode = (resp && resp.country) ? resp.country : "";
                    callback(countryCode);
                });
            },
            customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
              return "" ;
            },
            utilsScript: "../themes/default/js/utils.js"
        });

        $('.contact_phone_toggle .fieldGroup_contact_phone').eq(0).find('.second_hp').on({
          keydown: function(e) {
            if (e.which === 32)
              return false;
          },
          change: function() {
            this.value = this.value.replace(/\s/g, "");
          }
        });

        if ($(".main_contact_phone_primary").is(":checked")) 
        {
            $('.contact_phone_toggle .fieldGroup_contact_phone').eq(0).find('.contact_phone_primary').prop( "checked", true );
        }


        $(".button_increment_contact_phone").css({"visibility": "hidden"});

        if ($(".contact_phone_toggle").find(".second_contact_phone").length > 0) 
        {
            $(".show_contact_phone").css({"visibility": "visible"});

        }
        else {
            $(".show_contact_phone").css({"visibility": "hidden"});
            
        }
       
        $(".main_contact_phone").val("");
        $(".main_contact_phone").parent().parent().find(".hidden_contact_phone").val("");
        $(".main_contact_phone").parent().parent().find(".contact_phone_primary").val("");
        $(".fieldGroupCopy_contact_phone").find('.second_contact_phone').attr("value", "");
        $(".fieldGroupCopy_contact_phone").find('.hidden_contact_phone').attr("value", "");
        $(".fieldGroupCopy_contact_phone").find('.contact_phone_primary').attr("value", "");

    });

    $("body").on("click",".remove_contact_phone",function(){ 
        var remove_contact_phone_button = $(this);
        bootbox.confirm("Are you comfirm delete this Phone Number?", function (result) {
            if (result) {

                remove_contact_phone_button.parents(".fieldGroup_contact_phone").remove();

                if (remove_contact_phone_button.parent().find(".contact_phone_primary").is(":checked")) 
                {
                    if ($(".contact_phone_toggle").find(".second_contact_phone").length > 0) 
                    {
                        $('.contact_phone_toggle .fieldGroup_contact_phone').eq(0).find('.contact_phone_primary').prop( "checked", true );
                    }
                    else
                    {
                        $(".main_contact_phone_primary").prop( "checked", true );
                    }
                    
                }

                if ($(".contact_phone_toggle").find(".second_contact_phone").length > 0) 
                {
                    $(".show_contact_phone").css({"visibility": "visible"});

                }
                else {
                    $(".show_contact_phone").css({"visibility": "hidden"});
                    
                }
            }
        });
    });

    $('.main_contact_phone').keyup(function(){

        if ($(this).val()) {
            $(".button_increment_contact_phone").css({"visibility": "visible"});

        }
        else {
            $(".button_increment_contact_phone").css({"visibility": "hidden"});
        }
    });

    $(".addMore_contact_email").click(function(){
        $(".contact_email_toggle").show();
        $(".show_contact_email").find(".fa").addClass("fa-arrow-up").removeClass("fa-arrow-down");
        $(".show_contact_email").find(".toggle_word").text('Show less');

        $(".fieldGroupCopy_contact_email").find('.second_contact_email').attr("value", $(".main_contact_email").val());
        //$(".fieldGroupCopy").find('.second_local_fix_line').intlTelInput("setNumber", number);
        //$(".fieldGroupCopy_email").find('.second_email').intlTelInput("setCountry", countryData.iso2);
        $(".fieldGroupCopy_contact_email").find('.contact_email_primary').attr("value", $(".main_contact_email").val());

        var fieldHTML = '<div class="input-group fieldGroup_contact_email" style="margin-top:10px; display: block !important;">'+$(".fieldGroupCopy_contact_email").html()+'</div>';

        //$('body').find('.fieldGroup_contact_email:first').after(fieldHTML);
        $( fieldHTML).prependTo(".contact_email_toggle");

        if ($(".main_contact_email_primary").is(":checked")) 
        {
            $(".contact_email_toggle .fieldGroup_contact_email").eq(0).find('.contact_email_primary').prop( "checked", true );
        }
        
        $(".button_increment_contact_email").css({"visibility": "hidden"});
       
       if ($(".contact_email_toggle").find(".second_contact_email").length > 0) 
        {
            $(".show_contact_email").css({"visibility": "visible"});

        }
        else {
            $(".show_contact_email").css({"visibility": "hidden"});
            
        }

        $(".main_contact_email").val("");
        $(".main_contact_email").parent().find(".main_contact_email_primary").val("");
        $(".fieldGroupCopy_contact_email").find('.second_contact_email').attr("value", "");
        $(".fieldGroupCopy_contact_email").find('.contact_email_primary').attr("value", "");

    });

    $("body").on("click",".remove_contact_email",function(){ 
        var remove_contact_email_button = $(this);
        bootbox.confirm("Are you comfirm delete this Email?", function (result) {
            if (result) {

                remove_contact_email_button.parents(".fieldGroup_contact_email").remove();

                if (remove_contact_email_button.parent().find(".contact_email_primary").is(":checked")) 
                {
                    if ($(".contact_email_toggle").find(".second_contact_email").length > 0) 
                    {
                        $(".contact_email_toggle .fieldGroup_contact_email").eq(0).find('.contact_email_primary').prop( "checked", true );
                    }
                    else
                    {
                        $(".main_contact_email_primary").prop( "checked", true );
                    }
                }

                if ($(".contact_email_toggle").find(".second_contact_email").length > 0) 
                {
                    $(".show_contact_email").css({"visibility": "visible"});

                }
                else {
                    $(".show_contact_email").css({"visibility": "hidden"});
                    
                }
            }
        });
    });

    $('.main_contact_email').keyup(function(){

        if ($(this).val()) {
            $(".button_increment_contact_email").css({"visibility": "visible"});

        }
        else {
            $(".button_increment_contact_email").css({"visibility": "hidden"});
        }
    });

    if ($(".contact_phone_toggle").find(".second_contact_phone").length > 0) 
    {
        $(".show_contact_phone").css({"visibility": "visible"});
        $(".contact_phone_toggle").hide();

    }
    else {
        $(".show_contact_phone").css({"visibility": "hidden"});
        $(".contact_phone_toggle").hide();
    }

    if ($(".contact_email_toggle").find(".second_contact_email").length > 0) 
    {
        $(".show_contact_email").css({"visibility": "visible"});
        $(".contact_email_toggle").hide();

    }
    else {
        $(".show_contact_email").css({"visibility": "hidden"});
        $(".contact_email_toggle").hide();
    }

});


$(document).on('change','#setup_form #chairman',function(e){
    $('#setup_form').formValidation('revalidateField', 'chairman');
});

$(document).on('change','#setup_form #director_signature_1',function(e){
    $('#setup_form').formValidation('revalidateField', 'director_signature_1');
});

$(document).on('change','#setup_form #director_signature_2',function(e){
    $('#setup_form').formValidation('revalidateField', 'director_signature_2');
});

$(document).on('change','#billing_form #service',function(e){
    var num = $(this).parent().parent().parent().attr("num");
    $('#billing_form').formValidation('revalidateField', 'service['+num+']');
});

$(document).on('change','#billing_form #frequency',function(e){
    var num = $(this).parent().parent().parent().attr("num");
    $('#billing_form').formValidation('revalidateField', 'frequency['+num+']');
});

$(document).on('change','#billing_form .from_datepicker',function(e){
// $(".from_datepicker").datepicker().on('changeDate', function (e) {
    var num = $(this).parent().parent().parent().parent().parent().attr("num");
    //console.log($(this).parent().parent().parent().parent().parent());
    $('#billing_form').formValidation('revalidateField', 'from['+num+']');
});

if(corp_rep_data)
{
    $count_corp_rep = corp_rep_data.length;
}
else
{
    $count_corp_rep = 0;
}

$(document).on('click',"#corp_rep_Add",function() {

    $count_corp_rep++;
    $a = ""; 
    $a += '<tr class="row_corp_rep">';
    $a += '<td><input type="text" style="text-transform:uppercase;" name="subsidiary_name[]" id="subsidiary_name_'+$count_corp_rep+'" class="form-control subsidiary_name" value=""/></td>';
    $a += '<td><input type="text" style="text-transform:uppercase;" name="corp_rep_name[]" id="corp_rep_name" class="form-control" value=""/></td>';
    $a += '<td><input type="text" style="text-transform:uppercase;" name="corp_rep_identity_number[]" class="form-control corp_rep_identity_number" value="" id="corp_rep_identity_number" maxlength="15"/></td>';
    $a += '<td><div class="input-group" style="width: 150px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="corp_rep_date_of_appointment" name="date_of_appointment[]" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="DD/MM/YYYY"></div></td>';
    $a += '<td><div class="input-group" style="width: 150px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="corp_rep_date_of_cessation" name="date_of_cessation[]" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="DD/MM/YYYY"></div></td>';
    $a += '<td class="action"><div style="display: inline-block;"><button type="button" class="btn btn-primary" onclick="delete_officer(this);">Delete</button></div></td>';
    $a += '</tr>';
    $("#body_corp_rep").prepend($a); 

    $('#corp_rep_date_of_appointment').datepicker({ 
        dateFormat:'dd/mm/yyyy',
    });

    $('#corp_rep_date_of_cessation').datepicker({ 
        dateFormat:'dd/mm/yyyy',
    });

});

if(corp_rep_data)
{
    for(var i = 0; i < corp_rep_data.length; i++)
    {
        $a = ""; 
        $a += '<tr class="row_corp_rep">';
        $a += '<td><input type="text" style="text-transform:uppercase;" name="subsidiary_name[]" id="subsidiary_name_'+i+'" class="form-control subsidiary_name" value="'+corp_rep_data[i]["subsidiary_name"]+'"/></td>';
        $a += '<td><input type="text" style="text-transform:uppercase;" name="corp_rep_name[]" id="corp_rep_name" class="form-control" value="'+corp_rep_data[i]["name_of_corp_rep"]+'"/></td>';
        $a += '<td><input type="text" style="text-transform:uppercase;" name="corp_rep_identity_number[]" class="form-control corp_rep_identity_number" value="'+corp_rep_data[i]["identity_number"]+'" id="corp_rep_identity_number" maxlength="15"/></td>';
        $a += '<td><div class="input-group" style="width: 150px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="corp_rep_date_of_appointment" name="date_of_appointment[]" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="'+corp_rep_data[i]["effective_date"]+'" placeholder="DD/MM/YYYY"></div></td>';
        $a += '<td><div class="input-group" style="width: 150px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="corp_rep_date_of_cessation" name="date_of_cessation[]" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="'+corp_rep_data[i]["cessation_date"]+'" placeholder="DD/MM/YYYY"></div></td>';
        $a += '<td class="action"><div style="display: inline-block;"><button type="button" class="btn btn-primary" onclick="delete_officer(this);">Delete</button></div></td>';
        $a += '</tr>';
        //<input type="hidden" name="subsidiary_id[]" class="form-control subsidiary_id" id="subsidiary_id_'+i+'" value="'+transaction_incorporation_subsidiary[i]["client_id"]+'"/>
        $("#body_corp_rep").prepend($a); 

        $('#corp_rep_date_of_appointment').datepicker({ 
            dateFormat:'dd/mm/yyyy',
        });

        $('#corp_rep_date_of_cessation').datepicker({ 
            dateFormat:'dd/mm/yyyy',
        });
    }
}

function delete_officer(element)
{
    var tr = jQuery(element).parent().parent().parent();
    tr.remove();
}
// $(document).on('change','#type_of_day',function(e){
//     console.log($(this).parent().parent().parent().parent());
//     var this_row = $(this).parent().parent().parent().parent();
//     if(this_row.find('#frequency').val() != 0 && this_row.find('#frequency').val() != 1 && this_row.find('#from_billing_cycle').val() != "" && this_row.find('#to_billing_cycle').val() != "" && this_row.find('#type_of_day').val() != 0 && this_row.find('#days').val() != "")
//     {
//         changeRemark(this_row, this_row.find('#type_of_day').val(), this_row.find('#days').val(), this_row.find('#frequency').val(), this_row.find('#to_billing_cycle').val(), this_row.find('#from').val(), this_row.find('#to').val());
//     }
// });

// $(document).on('change','#days ',function(e){
//     console.log($(this).parent().parent().parent().parent());
//     var this_row = $(this).parent().parent().parent().parent();
//     if(this_row.find('#frequency').val() != 0 && this_row.find('#frequency').val() != 1 && this_row.find('#from_billing_cycle').val() != "" && this_row.find('#to_billing_cycle').val() != "" && this_row.find('#type_of_day').val() != 0 && this_row.find('#days').val() != "")
//     {
//         changeRemark(this_row, this_row.find('#type_of_day').val(), this_row.find('#days').val(), this_row.find('#frequency').val(), this_row.find('#to_billing_cycle').val(), this_row.find('#from').val(), this_row.find('#to').val());
//     }
// });

// $(document).on('change','#from_billing_cycle',function(e){
//     console.log($(this).parent().parent().parent().parent().parent().parent());
//     var this_row = $(this).parent().parent().parent().parent().parent();
//     if(this_row.find('#frequency').val() != 0 && this_row.find('#frequency').val() != 1 && this_row.find('#to_billing_cycle').val() != "")
//     {
//         changeRemark(this_row, this_row.find('#type_of_day').val(), this_row.find('#days').val(), this_row.find('#frequency').val(), this_row.find('#to_billing_cycle').val(), this_row.find('#from').val(), this_row.find('#to').val());
//     }
    
// });

// $(document).on('change','#to_billing_cycle',function(e){
//     console.log($(this).parent().parent().parent().parent().parent().parent());
//     var this_row = $(this).parent().parent().parent().parent().parent().parent();
//     if(this_row.find('#frequency').val() != 0 && this_row.find('#frequency').val() != 1 && this_row.find('#from_billing_cycle').val() != "")
//     {
//         changeRemark(this_row, this_row.find('#type_of_day').val(), this_row.find('#days').val(), this_row.find('#frequency').val(), this_row.find('#to_billing_cycle').val(), this_row.find('#from').val(), this_row.find('#to').val());
//     }
    
// });

function changeRemark(this_row, type_of_day, days, frequency, to_billing_cycle, from, to)
{
    $('#loadingmessage').show();
    $.ajax({
        type: "POST",
        url: "masterclient/check_next_recurring_date",
        data: {"type_of_day": type_of_day, "days": days, "frequency": frequency, "to_billing_cycle": to_billing_cycle, "from": from, "to": to},
        dataType: "json",
        success: function(data){
            //console.log(data);
            $('#loadingmessage').hide();
            this_row.find(".remark").text("");
            if(data.status == 1)
            {
                this_row.find(".remark").text("Remarks: Your next recurring bill will issue on "+data.issue_date+" for your billing cycle "+data.next_from_billing_cycle+" to "+data.next_to_billing_cycle+"");
            }
        }               
    });
}

$(".director_signature_1").change(function(ev) {
    directorSignature2($(this).val());
});


function directorSignature2(directorSignature2) {
    var director_signature_1_id = directorSignature2;
    //console.log(director_signature_1_id);
    if(director_signature_1_id != '' && director_signature_1_id != 0){
        cm1.getDirectorSignature2(director_signature_1_id);
        showDS2 = false;
        $(".btnShowAllDirectorSig2").prop('value', 'Show Today');
        $(".director_signature_2").removeAttr("disabled");

        if(director_signature_1_id == '' && director_signature_1_id == 0){
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
    else{
        $(".director_signature_2").attr("disabled", "disabled");
        $(".director_signature_2 option:gt(0)").remove();
        //$('#setup_form').formValidation('revalidateField', 'director_signature_2');
        $('.director_signature_2_group').removeClass("has-error");
        $('.director_signature_2_group').removeClass("has-success");
        $('.director_signature_2_group .help-block').hide();
    }
};

$(document).on('click',"#save_signing_information",function(e){
    e.preventDefault();
    setup_section = "signing_information_form";
    $("#w2-setup #signing_information_form").submit();
});

$(document).on('click',"#save_contact_information",function(e){
    e.preventDefault();
    setup_section = "contact_information_form";
    $("#w2-setup #contact_information_form").submit();
});

$(document).on('click',"#save_reminder",function(e){
    e.preventDefault();
    setup_section = "reminder_form";
    $("#w2-setup #reminder_form").submit();
});

$(document).on('click',"#save_corporate_representative",function(e){
    e.preventDefault();
    setup_section = "corporate_representative_form";
    $("#w2-setup #corporate_representative_form").submit();
});

$(document).on('click',"#save_related_party",function(e){
    e.preventDefault();
    setup_section = "related_group_form";
    $("#w2-setup #related_group_form").submit();
});

// $(document).on('submit', function (e) {
//     e.preventDefault();

//     if(setup_section == "signing_information_form")
//     {
//         var $form = $(e.target);

//         // and the FormValidation instance
//         var fv = $form.data('formValidation');
//         // Get the first invalid field
//         var $invalidFields = fv.getInvalidFields().eq(0);
//         // Get the tab that contains the first invalid field
//         var $tabPane     = $invalidFields.parents('.tab-pane');
//         var valid_setup = fv.isValidContainer($tabPane);

//         if(valid_setup == false)
//         {
//             toastr.error("Please complete all required field", "Error");
//         }

//         submit_setup_link = "masterclient/submit_signing_information";
//         submit_setup_data = $("#signing_information_form").serialize();
//     }

//     $('#loadingmessage').show();
//     $.ajax({ //Upload common input
//         url: submit_setup_link,
//         type: "POST",
//         data: submit_setup_data,
//         dataType: 'json',
//         success: function (response,data) {
//             $('#loadingmessage').hide();
            
//         }
//     });
// });


// if(client_billing_info)
// {   
//     var array_length = client_billing_info.length - 1;
//     $count_billing_info = parseInt(client_billing_info[array_length]["client_billing_info_id"]) + 1;
// }
// else
// {
//     $count_billing_info = 1;
// }
// $(document).on('click',"#billing_info_Add",function() {
    
//     $a=""; 
//     $a += '<div class="tr editing" style="border-bottom: 2px solid #dddddd;" method="post" name="form'+$count_billing_info+'" id="form'+$count_billing_info+'" num="'+$count_billing_info+'">';
//     $a += '<div class="hidden"><input type="text" class="form-control company_code" name="company_code" value="'+company_code+'"/></div>';
//     $a += '<div class="hidden"><input type="text" class="form-control client_billing_info_id" name="client_billing_info_id['+$count_billing_info+']" id="client_billing_info_id" value="'+$count_billing_info+'"/></div>';
//     $a += '<div class="td"><div class="input-dropdown" style="margin-bottom: 55px !important;"><select class="form-control service" style="width: 100%;" name="service['+$count_billing_info+']" id="service" onchange="optionCheckBilling(this);"><option value="0" >Select Service</option></select><div id="form_service"></div></div></div>';
//     $a += '<div class="td"><div class="input-group mb-md"><textarea class="form-control" name="invoice_description['+$count_billing_info+']"  id="invoice_description" rows="5" style="width:250px"></textarea></div></div>';
//     $a += '<div class="td"><div class="input-dropdown" style="margin-bottom: 55px !important;"><select class="form-control currency" style="text-align:right;width: 100%;" name="currency['+$count_billing_info+']" id="currency"><option value="0" >Select Currency</option></select><div id="form_currency"></div></div></div>';
//     $a += '<div class="td"><div class="input-group"><input type="text" name="amount['+$count_billing_info+']" class="numberdes form-control amount" value="" id="amount" style="width:100%;text-align:right;"/><div id="form_amount"></div></div></div>';
//     $a += '<div class="td"><div class="input-dropdown" style="margin-bottom: 55px !important;"><select class="form-control" style="width: 100%;" name="unit_pricing['+$count_billing_info+']" id="unit_pricing"><option value="0" >Select Unit Pricing</option></select><div id="form_unit_pricing"></div></div></div>';
//     //$a += '<div class="td"><div class="div_billing_cycle"><div>Start Date: </div><div class="from_billing_cycle_div mb-md"><div class="input-group" style="width: 100%" id="from_billing_cycle_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker from_billing_cycle_datepicker" id="from_billing_cycle" name="from_billing_cycle['+$count_billing_info+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div><div id="form_from_billing_cycle"></div></div><div class="mb-md"><div>End Date: </div><div class="to_billing_cycle_div mb-md"><div class="input-group" style="width: 100%" id="to_billing_cycle_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker to_billing_cycle_datepicker" id="to_billing_cycle" name="to_billing_cycle['+$count_billing_info+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div><div id="form_to_billing_cycle"></div></div></div></div></div>';
//     $a += '<div class="td action"><button type="button" class="btn btn-primary" onclick="delete_billing_info(this);">Delete</button></div></div>';
//     $a += '</div>';

//     $("#body_billing_info").prepend($a); 

//     !function ($count_billing_info) {
//         $.ajax({
//             type: "POST",
//             url: "masterclient/get_billing_info_service",
//             data: {"company_code": company_code},
//             dataType: "json",
//             success: function(data){
//                 //console.log(data.selected_billing_info_service_category);
//                 if(data.tp == 1){
//                     var category_description = '';
//                     var optgroup = '';
//                     for(var t = 0; t < data.selected_billing_info_service_category.length; t++)
//                     {
//                         if(category_description != data.selected_billing_info_service_category[t]['category_description'])
//                         {
//                             if(optgroup != '')
//                             {
//                                 $("#form"+$count_billing_info+" #service").append(optgroup);
//                             }
//                             optgroup = $('<optgroup label="' + data.selected_billing_info_service_category[t]['category_description'] + '" />');
//                         }

//                         category_description = data.selected_billing_info_service_category[t]['category_description'];

//                         for(var h = 0; h < data.result.length; h++)
//                         {
//                             if(category_description == data.result[h]['category_description'])
//                             {
//                                 var option = $('<option />');
//                                 option.attr('data-description', data.result[h]['invoice_description']).attr('data-currency', data.result[h]['currency']).attr('data-unit_pricing', data.result[h]['unit_pricing']).attr('data-amount', data.result[h]['amount']).attr('value', data.result[h]['id']).text(data.result[h]['service_name']).appendTo(optgroup);
//                             }
//                         }
                        

                        
//                     }
//                     $("#form"+$count_billing_info+" #service").append(optgroup);
//                     $("#form"+$count_billing_info+" #service").select2({
//                         formatNoMatches: function () {
//                             return "No Result. <a href='our_firm/edit/"+data.firm_id+"' onclick='open_new_tab("+data.firm_id+")' target='_blank'>Click here to add Service</a>"
//                          },
//                          width: '350px'
//                     });

//                     var arr = $.map
//                     (
//                         $("select#service option:selected"), function(n)
//                         {
//                             return n.value;
//                         }
//                     );

//                     $('select[name="service['+$count_billing_info+']"] option').filter(function()
//                     {
//                         return $.inArray($(this).val(),arr)>-1;
//                      }).attr("disabled","disabled"); 
                    
//                 }
//                 else{
//                     alert(data.msg);
//                 }  
//             }               
//         });
//     }($count_billing_info);

//     !function ($count_billing_info) {
//         $.ajax({
//             type: "GET",
//             url: "masterclient/get_currency",
//             dataType: "json",
//             success: function(data){
//                 //console.log(data);
//                 if(data.tp == 1){
//                     $.each(data['result'], function(key, val) {
//                         var option = $('<option />');
//                         option.attr('value', key).text(val);
                        
//                         $("#form"+$count_billing_info+" #currency").append(option);
//                     });
//                 }
//                 else{
//                     alert(data.msg);
//                 }  
//             }               
//         });
//     }($count_billing_info);

//     !function ($count_billing_info) {
//         $.ajax({
//             type: "GET",
//             url: "masterclient/get_unit_pricing",
//             dataType: "json",
//             success: function(data){
//                 //console.log(data);
//                 if(data.tp == 1){
//                     $.each(data['result'], function(key, val) {
//                         var option = $('<option />');
//                         option.attr('value', key).text(val);
                        
//                         $("#form"+$count_billing_info+" #unit_pricing").append(option);
//                     });
//                 }
//                 else{
//                     alert(data.msg);
//                 }  
//             }               
//         });
//     }($count_billing_info);


//     !function ($count_billing_info) {
//         $.ajax({
//             type: "GET",
//             url: "masterclient/get_billing_info_frequency",
//             dataType: "json",
//             success: function(data){
//                 //console.log(data);
//                 if(data.tp == 1){
//                     $.each(data['result'], function(key, val) {
//                         var option = $('<option />');
//                         option.attr('value', key).text(val);
                        
//                         $("#form"+$count_billing_info+" #frequency").append(option);
//                     });
//                 }
//                 else{
//                     alert(data.msg);
//                 }  
//             }               
//         });
//     }($count_billing_info);

//     !function ($count_billing_info) {
//         $.ajax({
//             type: "GET",
//             url: "masterclient/get_type_of_day",
//             dataType: "json",
//             success: function(data){
//                 //console.log(data);
//                 if(data.tp == 1){
//                     $.each(data['result'], function(key, val) {
//                         var option = $('<option />');
//                         option.attr('value', key).text(val);
                        
//                         $("#form"+$count_billing_info+" #type_of_day").append(option);
//                     });
//                 }
//                 else{
//                     alert(data.msg);
//                 }  
//             }               
//         });
//     }($count_billing_info);

//     $('#billing_form').formValidation('addField', 'service['+$count_billing_info+']', serviceValidators);
//     $('#billing_form').formValidation('addField', 'invoice_description['+$count_billing_info+']', invoiceDescriptionValidators);
//     $('#billing_form').formValidation('addField', 'amount['+$count_billing_info+']', amountValidators);
//     $('#billing_form').formValidation('addField', 'currency['+$count_billing_info+']', currencyValidators);
//     $('#billing_form').formValidation('addField', 'unit_pricing['+$count_billing_info+']', unitPricingValidators);
    
//     $count_billing_info++;
// });

function open_new_tab(firm_id)
{
    window.open ('our_services','_blank');
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

// function optionCheckService(service_element) 
// {
//     var tr = jQuery(service_element).parent().parent();

//     var input_num = tr.parent().attr("num");

//     if(tr.find('select[name="frequency['+input_num+']"]').val() == "1")
//     {
//         tr.parent().find('input[name="from['+input_num+']"]').attr('disabled', 'disabled');
//         tr.parent().find('input[name="to['+input_num+']"]').attr('disabled', 'disabled');

//         tr.parent().find('input[name="from['+input_num+']"]').val("");
//         tr.parent().find('input[name="to['+input_num+']"]').val("");

//         tr.parent().find('.div_recurring').hide();

//         tr.parent().find('input[name="type_of_day['+input_num+']"]').attr('disabled', 'disabled');
//         tr.parent().find('input[name="days['+input_num+']"]').attr('disabled', 'disabled');

//         tr.parent().find('select[name="type_of_day['+input_num+']"]').val("0");
//         tr.parent().find('input[name="days['+input_num+']"]').val("");

//         tr.parent().find('.div_interval').hide();

//         tr.parent().find(".remark").text("");
//     }
//     else
//     {
//         tr.parent().find('input[name="from['+input_num+']"]').removeAttr('disabled');
//         tr.parent().find('input[name="to['+input_num+']"]').removeAttr('disabled');

//         tr.parent().find('.div_recurring').show();

//         tr.parent().find('select[name="type_of_day['+input_num+']"]').removeAttr('disabled');
//         tr.parent().find('input[name="days['+input_num+']"]').removeAttr('disabled');

//         tr.parent().find('.div_interval').show();

//         if(tr.parent().find('#frequency').val() != 0 && tr.parent().find('#frequency').val() != 1 && tr.parent().find('#from_billing_cycle').val() != "" && tr.parent().find('#to_billing_cycle').val() != "")
//         {
//             changeRemark(tr.parent(), tr.parent().find('#type_of_day').val(), tr.parent().find('#days').val(), tr.parent().find('#frequency').val(), tr.parent().find('#to_billing_cycle').val(), tr.parent().find('#from').val(), tr.parent().find('#to').val());
//         }
//     }
// }

toastr.options = {

  "positionClass": "toast-bottom-right"

}

// function delete_billing_info(element)
// {
//     var tr = jQuery(element).parent().parent();

//     var client_billing_info_id = tr.find('.client_billing_info_id').val();
//     var company_code = tr.find('.company_code').val();

//     if(client_billing_info_id != "")
//     {
//         $.ajax({ //Upload common input
//             url: "masterclient/delete_client_billing_info",
//             type: "POST",
//             data: {"client_billing_info_id": client_billing_info_id, "company_code": company_code},
//             dataType: 'json',
//             success: function (response) {
//                 if(response.Status == 1)
//                 {
//                     array_client_billing_info_id.push(client_billing_info_id);
//                     toastr.success("Updated Information", "Success");
//                     tr.remove();

//                     $("select#service option").attr("disabled",false); //enable everything
         
//                      //collect the values from selected;
//                     var arr = $.map
//                     (
//                         $("select#service option:selected"), function(n)
//                         {
//                             return n.value;
//                         }
//                     );

//                     $("select#service").each(function() {

//                         var other_num = $(this).parent().parent().parent().attr("num");

//                         var selected_dropdown_value = $('select[name="service['+other_num+']"]').val();

//                         $('select[name="service['+other_num+']"] option').filter(function()
//                         {
//                             return $.inArray($(this).val(),arr)>-1;
//                         }).attr("disabled","disabled"); 

//                         $('select[name="service['+other_num+']"] option').filter(function()
//                         {
//                             return $(this).val() === selected_dropdown_value;
//                         }).attr("disabled", false);

//                     });
//                 }
//                 else
//                 {
//                     toastr.error("Cannot be delete. This service is use in billing.", "Error");
//                 }
//             }
//         });
//     }
// }

// var change_template = true;
// $("#update_template").on('click',function() {
//     $('#loadingmessage').show();
//     if(change_template == true)
//     {
//         billing(template);
//         change_template = false;
//     }
//     else
//     {
//         billing(client_billing_info);
//         change_template = true;
//     }
// });

function setup_change_date(latest_incorporation_date)
{
    $('.from_datepicker').datepicker({ 
        dateFormat:'dd/mm/yyyy',
    }).datepicker('setStartDate', latest_incorporation_date);

    $('.to_datepicker').datepicker({ 
        dateFormat:'dd/mm/yyyy',
    }).datepicker('setStartDate', latest_incorporation_date);

}

// if(client_billing_info)
// {
//     billing(client_billing_info);
// }

// function billing(client_info)
// {
//     $("#body_billing_info .tr").remove();
//     if(client_info.length > 0)
//     {
//         for(var i = 0; i < client_info.length; i++)
//         {

//             $a=""; 
//             $a += '<div class="tr editing" method="post" name="form'+i+'" id="form'+i+'" num="'+i+'">';
//             $a += '<div class="hidden"><input type="text" class="form-control company_code" name="company_code" value="'+company_code+'"/></div>';
//             $a += '<div class="hidden"><input type="text" class="form-control client_billing_info_id" name="client_billing_info_id['+i+']" id="client_billing_info_id" value="'+client_info[i]["client_billing_info_id"]+'"/></div>';
//             $a += '<div class="td"><div class="input-dropdown" style="margin-bottom: 55px !important;"><select class="form-control" style="width: 100%;" name="service['+i+']" id="service" onchange="optionCheckBilling(this);"><option value="0" >Select Service</option></select><div id="form_service"></div></div></div>';
//             $a += '<div class="td"><div class="input-group mb-md"><textarea class="form-control" name="invoice_description['+i+']"  id="invoice_description" rows="3" style="width:250px">'+client_info[i]["invoice_description"]+'</textarea></div></div>';
//             $a += '<div class="td"><div class="input-dropdown" style="margin-bottom: 55px !important;"><select class="form-control" style="text-align:right;width: 100%;" name="currency['+i+']" id="currency"><option value="0" >Select Currency</option></select><div id="form_currency"></div></div></div>';
//             $a += '<div class="td"><div class="input-group"><input type="text" name="amount['+i+']" class="numberdes form-control amount" value="'+ addCommas(client_info[i]["amount"])+'" id="amount" style="width:100%;text-align:right;"/><div id="form_amount"></div></div></div>';
//             $a += '<div class="td"><div class="input-dropdown" style="margin-bottom: 55px !important;"><select class="form-control" style="width: 100%;" name="unit_pricing['+i+']" id="unit_pricing"><option value="0" >Select Unit Pricing</option></select><div id="form_unit_pricing"></div></div></div>';
//             //$a += '<div class="td"><div class="div_billing_cycle"><div>Start Date: </div><div class="from_billing_cycle_div mb-md"><div class="input-group" style="width: 100%" id="from_billing_cycle_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker from_billing_cycle_datepicker" id="from_billing_cycle" name="from_billing_cycle['+i+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value="'+client_info[i]["from_billing_cycle"]+'"></div><div id="form_from_billing_cycle"></div></div><div class="mb-md"><div>End Date: </div><div class="to_billing_cycle_div mb-md"><div class="input-group" style="width: 100%" id="to_billing_cycle_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker to_billing_cycle_datepicker" id="to_billing_cycle" name="to_billing_cycle['+i+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value="'+client_info[i]["to_billing_cycle"]+'"></div><div id="form_to_billing_cycle"></div></div></div></div></div>';
//             $a += '<div class="td action"><button type="button" class="btn btn-primary" onclick="delete_billing_info(this);">Delete</button></div></div>';
//             $a += '</div>';

//             if(client_info[i]["frequency_name"] == "Non-recurring")
//             {
//                 $("#form"+i+" .div_recurring").hide();
//                 $("#form"+i+" #to").attr('disabled', 'disabled');
//                 $("#form"+i+" #from").attr('disabled', 'disabled');
//             }
//             else
//             {
//                 $("#form"+i+" .div_recurring").show();
//                 $("#form"+i+" #to").attr('disabled', false);
//                 $("#form"+i+" #from").attr('disabled', false);
//             }

            
//             $('.from_datepicker').datepicker({ 
//                 dateFormat:'dd/mm/yyyy',
//                 autoclose: true,
//             })
//             .on('changeDate', function (selected) {
//                 var startDate = new Date(selected.date.valueOf());
//                 $(this).parent().parent().parent().parent().find('.to_datepicker').datepicker('setStartDate', startDate);

//                 var num = $(this).parent().parent().parent().parent().parent().attr("num");
//                 $('#billing_form').formValidation('revalidateField', 'from['+num+']');
//             }).on('clearDate', function (selected) {
//                 $(this).parent().parent().parent().parent().find('.to_datepicker').datepicker('setStartDate', null);
//             });

//             $('.to_datepicker').datepicker({ 
//                 dateFormat:'dd/mm/yyyy',
//                 autoclose: true,
//             }).on('changeDate', function (selected) {

//                 var endDate = new Date(selected.date.valueOf());
//                 $(this).parent().parent().parent().parent().find('.from_datepicker').datepicker('setEndDate', endDate);

//                 var num = $(this).parent().parent().parent().parent().parent().attr("num");
//                 //$('#setup_form').formValidation('revalidateField', 'to['+num+']');
//             }).on('clearDate', function (selected) {
//                $(this).parent().parent().parent().parent().find('.from_datepicker').datepicker('setEndDate', null);
//             });

//             $('.from_billing_cycle_datepicker').datepicker({ 
//                 dateFormat:'dd/mm/yyyy',
//                 autoclose: true,
//             })
//             .on('changeDate', function (selected) {
//                 var startDate = new Date(selected.date.valueOf());
//                 $(this).parent().parent().parent().parent().find('.to_billing_cycle_datepicker').datepicker('setStartDate', startDate);

//                 var num = $(this).parent().parent().parent().parent().attr("num");
//                 $('#billing_form').formValidation('revalidateField', 'from['+num+']');
//             }).on('clearDate', function (selected) {
//                 $(this).parent().parent().parent().parent().find('.to_billing_cycle_datepicker').datepicker('setStartDate', null);
//             });

//             $('.to_billing_cycle_datepicker').datepicker({ 
//                 dateFormat:'dd/mm/yyyy',
//                 autoclose: true,
//             }).on('changeDate', function (selected) {

//                 var endDate = new Date(selected.date.valueOf());
//                 $(this).parent().parent().parent().parent().find('.from_billing_cycle_datepicker').datepicker('setEndDate', endDate);

//                 var num = $(this).parent().parent().parent().parent().parent().attr("num");
//                 //$('#setup_form').formValidation('revalidateField', 'to['+num+']');
//             }).on('clearDate', function (selected) {
//                $(this).parent().parent().parent().parent().find('.from_billing_cycle_datepicker').datepicker('setEndDate', null);
//             });
            

//             !function (i) {

//                 $.ajax({
//                     type: "POST",
//                     url: "masterclient/get_billing_info_service",
//                     data: {"company_code": company_code, "service": client_info[i]["service"], 'is_template': change_template},
//                     dataType: "json",
//                     success: function(data){
//                         //console.log(data);
//                         $("#form"+i+" #service").find("option:eq(0)").html("Select Service");
//                         if(data.tp == 1){
//                             var category_description = '';
//                             var optgroup = '';

//                             for(var t = 0; t < data.selected_billing_info_service_category.length; t++)
//                             {
//                                 if(category_description != data.selected_billing_info_service_category[t]['category_description'])
//                                 {
//                                     if(optgroup != '')
//                                     {
//                                         $("#form"+i+" #service").append(optgroup);
//                                     }
//                                     optgroup = $('<optgroup label="' + data.selected_billing_info_service_category[t]['category_description'] + '" />');
//                                 }

//                                 category_description = data.selected_billing_info_service_category[t]['category_description'];

//                                 for(var h = 0; h < data.result.length; h++)
//                                 {
//                                     if(category_description == data.result[h]['category_description'])
//                                     {
//                                         var option = $('<option />');
//                                         option.attr('data-description', data.result[h]['invoice_description']).attr('data-currency', data.result[h]['currency']).attr('data-unit_pricing', data.result[h]['unit_pricing']).attr('data-amount', data.result[h]['amount']).attr('value', data.result[h]['id']).text(data.result[h]['service_name']).appendTo(optgroup);

//                                         if(data.selected_service != null && data.result[h]['id'] == data.selected_service)
//                                         {
//                                             option.attr('selected', 'selected');
//                                         }
//                                     }
//                                 }
//                             }


//                             $("#form"+i+" #service").append(optgroup);

//                             $("#form"+i+" #service").select2({
//                                 formatNoMatches: function () {
//                                     return "No Result. <a href='our_firm/edit/"+data.firm_id+"' onclick='open_new_tab("+data.firm_id+")' target='_blank'>Click here to add Service</a>"
//                                 },
//                                 width: '350px'
//                             });

//                             $("#form"+i+" #service option").filter(function()
//                             {
//                                 return $.inArray($(this).val(),data.selected_query)>-1;
//                             }).attr("disabled","disabled");  

//                             $('select[name="service['+i+']"] option').filter(function()
//                             {
//                                 return $(this).val() === data.selected_service;
//                             }).attr("disabled", false);
//                         }
//                         else{
//                             alert(data.msg);
//                         }  
//                     }               
//                 });
//             } (i);

//             !function (i) {
//                 $.ajax({
//                     type: "GET",
//                     url: "masterclient/get_currency",
//                     dataType: "json",
//                     async: false,
//                     success: function(data){
//                         //console.log(data);
//                         if(data.tp == 1){
//                             $.each(data['result'], function(key, val) {
//                                 var option = $('<option />');
//                                 option.attr('value', key).text(val);
//                                 if(client_info[i]["currency"] != null && key == client_info[i]["currency"])
//                                 {
//                                     option.attr('selected', 'selected');
//                                 }
//                                 $("#form"+i+" #currency").append(option);
//                             });
//                         }
//                         else{
//                             alert(data.msg);
//                         }  
//                     }               
//                 });
//             }(i);

//             !function (i) {
//                 $.ajax({
//                     type: "GET",
//                     url: "masterclient/get_unit_pricing",
//                     async:false,
//                     dataType: "json",
//                     success: function(data){
//                         //console.log(data);
//                         if(data.tp == 1){
//                             $.each(data['result'], function(key, val) {
//                                 var option = $('<option />');
//                                 option.attr('value', key).text(val);
//                                 if(client_info[i]["unit_pricing"] != null && key == client_info[i]["unit_pricing"])
//                                 {
//                                     option.attr('selected', 'selected');
//                                 }
                                
//                                 $("#form"+i+" #unit_pricing").append(option);
//                             });
//                         }
//                         else{
//                             alert(data.msg);
//                         }  
//                     }               
//                 });
//             }(i);

//             !function (i) {
//                 $.ajax({
//                     type: "POST",
//                     url: "masterclient/get_billing_info_frequency",
//                     data: {"frequency": client_info[i]["frequency"]},
//                     dataType: "json",
//                     success: function(data){
//                         //console.log(data);
//                         $('#loadingmessage').hide();
//                         $("#form"+i+" #frequency").find("option:eq(0)").html("Select Frequency");
//                         if(data.tp == 1){
//                             $.each(data['result'], function(key, val) {
//                                 var option = $('<option />');
//                                 option.attr('value', key).text(val);
//                                 if(data.selected_frequency != null && key == data.selected_frequency)
//                                 {
//                                     option.attr('selected', 'selected');
//                                 }
//                                 $("#form"+i+" #frequency").append(option);
//                             });
//                         }
//                         else{
//                             alert(data.msg);
//                         } 
//                     }               
//                 });
//             } (i);

//             !function (i) {
//                 $.ajax({
//                     type: "POST",
//                     url: "masterclient/get_type_of_day",
//                     data: {"type_of_day": client_info[i]["type_of_day"]},
//                     dataType: "json",
//                     success: function(data){
//                         //console.log(data);
//                         if(data.tp == 1){
//                             $.each(data['result'], function(key, val) {
//                                 var option = $('<option />');
//                                 option.attr('value', key).text(val);
//                                 if(data.selected_type_of_day != null && key == data.selected_type_of_day)
//                                 {
//                                     option.attr('selected', 'selected');
//                                 }
//                                 $("#form"+i+" #type_of_day").append(option);
//                             });
//                         }
//                         else{
//                             alert(data.msg);
//                         }  
//                     }               
//                 });
//             }(i);

//             if(client_info[i]["frequency"] != 1 && client_info[i]["from_billing_cycle"] != '' && client_info[i]["to_billing_cycle"] != '')
//             {
//                 !function (i) {
//                     $.ajax({
//                         type: "POST",
//                         url: "masterclient/check_next_recurring_date",
//                         data: {"type_of_day": client_info[i]["type_of_day"], "days": client_info[i]["days"], "frequency": client_info[i]["frequency"], "to_billing_cycle": client_info[i]["to_billing_cycle"], "from": client_info[i]["from"], "to": client_info[i]["to"]},
//                         dataType: "json",
//                         success: function(data){
//                             //console.log(data);
//                             $("#form"+i+" .remark").text("");
//                             if(data.status == 1)
//                             {
//                                 $("#form"+i+" .remark").text("Remarks: Your next recurring bill will issue on "+data.issue_date+" for your billing cycle "+data.next_from_billing_cycle+" to "+data.next_to_billing_cycle+"");
//                             }

                            
//                         }               
//                     });
//                 }(i);
//             }
            
//             $('#billing_form').formValidation('addField', 'service['+i+']', serviceValidators);
//             $('#billing_form').formValidation('addField', 'invoice_description['+i+']', invoiceDescriptionValidators);
//             $('#billing_form').formValidation('addField', 'amount['+i+']', amountValidators);
//             $('#billing_form').formValidation('addField', 'currency['+i+']', currencyValidators);
//             $('#billing_form').formValidation('addField', 'unit_pricing['+i+']', unitPricingValidators);
//         }
//     }
//     else
//     {
//         $('#loadingmessage').hide();
//     }
    
    
// }

// function optionCheckBilling(billing_element) {
    
//     var tr = jQuery(billing_element).parent().parent();

//     var input_num = tr.parent().attr("num");

//     jQuery(this).find("input").val('');

//     if(tr.find('select[name="service['+input_num+']"]').val() == "1")
//     {
//         tr.parent().find('select[name="frequency['+input_num+']"]').val("4");
//         $("#form"+input_num+" .div_recurring").show();
//         tr.parent().find("input").attr('disabled', false);
//     }
//     else if(tr.find('select[name="service['+input_num+']"]').val() == "2")
//     {
//         tr.parent().find('select[name="frequency['+input_num+']"]').val("5");
//         $("#form"+input_num+" .div_recurring").show();
//         tr.parent().find("input").attr('disabled', false);
//     }
//     else if(tr.find('select[name="service['+input_num+']"]').val() == "0")
//     {
//         tr.parent().find("input").attr('disabled', false);
//         $("#form"+input_num+" .div_recurring").show();
//         tr.parent().find("select").val('0');
//     }
//     else
//     {
//         tr.parent().find('select[name="frequency['+input_num+']"]').val("1");

//         $("#form"+input_num+" .div_recurring").hide();

//         tr.parent().find('input[name="from['+input_num+']"]').attr('disabled', 'disabled');
//         tr.parent().find('input[name="to['+input_num+']"]').attr('disabled', 'disabled');

//         tr.parent().find('input[name="from['+input_num+']"]').val("");
//         tr.parent().find('input[name="to['+input_num+']"]').val("");

//         tr.parent().find('.from_div').removeClass("has-error");
//         tr.parent().find('.from_div').removeClass("has-success");
//         tr.parent().find('.from_div .help-block').hide();
//     }

//     //Prevent Multiple Selections of Same Value
//     var selected_value = tr.find('select[name="service['+input_num+']"]').val();

//     $("select#service option").attr("disabled",false); //enable everything
 
//      //collect the values from selected;
//     var arr = $.map
//     (
//         $("select#service option:selected"), function(n)
//         {
//             return n.value;
//         }
//     );

//     $("select#service").each(function() {

//         var other_num = $(this).parent().parent().parent().attr("num");

//         var selected_dropdown_value = $('select[name="service['+other_num+']"]').val();

//          $('select[name="service['+other_num+']"] option').filter(function()
//         {
//             return $.inArray($(this).val(),arr)>-1;
//         }).attr("disabled","disabled"); 

//         $('select[name="service['+other_num+']"] option').filter(function()
//         {
//             return $(this).val() === selected_dropdown_value;
//         }).attr("disabled", false);

//     });
//}
