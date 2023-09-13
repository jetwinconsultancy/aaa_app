$(window).on('load', function() {
	var countries_string = localStorage.getItem("kyc_countries");
    var kycCountriesArray = countries_string.split(',');

	var entity_types_string = localStorage.getItem("kyc_entity_types");
    var kycEntityTypeArray = entity_types_string.split(',');

    var ownership_layers_string = localStorage.getItem("kyc_ownership_layers");
    var kycOwnershipLayersArray = ownership_layers_string.split(',');

    var onboarding_mode_string = localStorage.getItem("kyc_onboarding_mode");
    var kycOnboardingModeArray = onboarding_mode_string.split(',');

    var ssic_string = localStorage.getItem("kyc_ssic");
    var kycSSICArray = JSON.parse(ssic_string);

    var payment_modes_string = localStorage.getItem("kyc_payment_modes");
    var kycPaymentModeArray = payment_modes_string.split(',');

    var product_service_complexities_string = localStorage.getItem("kyc_product_service_complexities");
    var kycProductServiceComplexitiesArray = product_service_complexities_string.split(',');

    var source_of_funds_string = localStorage.getItem("kyc_source_of_funds");
    var kycSourceOfFundsArray = source_of_funds_string.split(',');

    $.each(kycEntityTypeArray, function(key, val) {
        var option = $('<option />');
        option.attr('value', val).text(val);
        $('#corporate_entity_type').append(option);
    });

    $.each(kycCountriesArray, function(key, val) {
        var option = $('<option />');
        option.attr('value', val).text(val);
        $('#corporate_country_of_incorporation').append(option);
    });

    $.each(kycOwnershipLayersArray, function(key, val) {
        var option = $('<option />');
        option.attr('value', val).text(val);
        $('#corporate_ownership_structure_layer').append(option);
    });

    $.each(kycCountriesArray, function(key, val) {
        var option = $('<option />');
        option.attr('value', val).text(val);
        $('#corporate_country_of_major_operation').append(option);
    });

    $.each(kycOnboardingModeArray, function(key, val) {
        var option = $('<option />');
        option.attr('value', val).text(val);
        $('#corporate_onboarding_mode').append(option);
    });

    $.each(kycSSICArray, function(key, val) {
        var option = $('<option />');
        option.attr('value', val).text(val);
        $('#corporate_primary_business_activity').append(option);
    });

    $.each(kycPaymentModeArray, function(key, val) {
        var option = $('<option />');
        option.attr({'value':val, 'id':val}).text(val);
        $('#corporate_payment_mode').append(option);
    });

    $.each(kycProductServiceComplexitiesArray, function(key, val) {
        var option = $('<option />');
        option.attr('value', val).text(val);
        $('#corporate_product_service_complexity').append(option);
    });

    $.each(kycSourceOfFundsArray, function(key, val) {
        var option = $('<option />');
        option.attr('value', val).text(val);
        $('#corporate_source_of_funds').append(option);
    });
    
    if(document.getElementById('company_edit').checked) 
    {
        runCorporateValidate();
    }

    // $('#loginForm').bootstrapValidator({
    //     //feedbackIcons: {
    //         // valid: 'glyphicon glyphicon-ok',
    //         // invalid: 'glyphicon glyphicon-remove',
    //         // validating: 'glyphicon glyphicon-refresh'
    //     //},
    //     fields: {
    //         username: {
    //             validators: {
    //                 notEmpty: {
    //                     message: 'The username is required'
    //                 }
    //             }
    //         },
    //         password: {
    //             validators: {
    //                 notEmpty: {
    //                     message: 'The password is required'
    //                 }
    //             }
    //         }
    //     }
    // }).on('success.form.bv', function(e) {
    //     // Prevent form submission
    //     e.preventDefault();

    //     var $form = $(e.target),                        // The form instance
    //         bv    = $form.data('bootstrapValidator');   // BootstrapValidator instance

    //     var kycUsername = $('#loginForm input[name="username"]').val();
    //     var kycPassword = $('#loginForm input[name="password"]').val();
    //     console.log("loginForm");

    //     getKYCUserToken (kycUsername, kycPassword, $form);
    // });

    $(document).on('click',".corporate_kyc_submit_screening",function(e){
        var $link = $(e.target);
        e.preventDefault();
        if(!$link.data('lockedAt') || +new Date() - $link.data('lockedAt') > 600) {
            $('#loginForm input[name="screening"]').val(true);
            submit_kyc_corp_info();
        }
        $link.data('lockedAt', +new Date());
    });

    $(document).on('click',".corporate_kyc_submit",function(e){
        var $link = $(e.target);
        e.preventDefault();
        if(!$link.data('lockedAt') || +new Date() - $link.data('lockedAt') > 600) {
            $('#loginForm input[name="screening"]').val(false);
            submit_kyc_corp_info();
        }
        $link.data('lockedAt', +new Date());
    });

    function submit_kyc_corp_info()
    {
        var bootstrapValidators = $("#kycScreeningCorporate-form").data('bootstrapValidator');
        bootstrapValidators.validate();
        if(bootstrapValidators.isValid())
        {
            var user_previous_token = localStorage.getItem("refreshUserToken");

            if(user_previous_token == null)
            {
                bootbox
                    .dialog({
                        title: 'Artemis Login',
                        message: $('#loginForm'),
                        show: false // We will show it manually later
                    })
                    .on('shown.bs.modal', function() {
                        $('#loginForm')
                            .show()                                 // Show the login form
                            .bootstrapValidator('resetForm', true); // Reset form
                    })
                    .on('hide.bs.modal', function(e) {
                        // Bootbox will remove the modal (including the body which contains the login form)
                        // after hiding the modal
                        // Therefor, we need to backup the form
                        $('#loginForm').hide().appendTo('body');
                    })
                    .modal('show');
            }
            else
            {
                //---------------For Refresh Token------------------------------
                refreshAmazonToken(user_previous_token);
            }
        }
        else 
        {
            return;
        }
    }

    function refreshAmazonToken(user_previous_token)
    {
        var token = new AmazonCognitoIdentity.CognitoRefreshToken({ RefreshToken: user_previous_token });
        cognitoUser.refreshSession(token, function (err, session) {
            console.log(!err);
            if(!err)
            {
                var idToken = session.getIdToken().getJwtToken();
                var refreshToken = session.getRefreshToken().getToken();
                var accessToken = session.getAccessToken().getJwtToken();

                localStorage.setItem("accessToken", accessToken);
                localStorage.setItem("refreshUserToken", refreshToken);

                saveKYCInfo(accessToken, session['idToken']['payload']['sub']);
            }
            else
            {
                localStorage.removeItem("refreshUserToken");
                submit_kyc_corp_info();
            }
        });
    }
});

function runCorporateValidate()
{
    var bootstrapValidator = $("#kycScreeningIndividual-form").data('bootstrapValidator');
    //console.log(bootstrapValidator);
    if (bootstrapValidator != undefined)
        bootstrapValidator.destroy();

    $('#kycScreeningCorporate-form')
    .find('[name="corporate_payment_mode[]"]')
    .multiselect({
        buttonWidth: '100%',
        buttonClass: 'form-control corporate_payment_mode_button',
        maxHeight: 200,
        buttonText: function(options, select) {
            if (options.length === 0) {
                return 'Select the Payment Mode';
            }
            else if (options.length > 1) {
                return 'More than 1 Payment Mode selected!';
            }
            else {
                 var labels = [];
                 options.each(function() {
                     if ($(this).attr('label') !== undefined) {
                         labels.push($(this).attr('label'));
                     }
                     else {
                         labels.push($(this).html());
                     }
                 });
                 return labels.join(', ') + '';
            }
        },
        // Re-validate the multiselect field when it is changed
        onChange: function(element, checked) {
            if($(".multiselect_div .multiselect-container input[value='UNKNOWN']").is(":checked"))
            {
                $(".multiselect_div .multiselect-container input[value='TELEGRAPHIC TRANSFER']").prop('disabled', true).prop("checked", false);
                $(".multiselect_div .multiselect-container input[value='TELEGRAPHIC TRANSFER']").parent().parent().parent().removeClass("active");
                $(".multiselect_div .multiselect-container input[value='CHEQUE (LOCAL)']").prop('disabled', true).prop("checked", false);
                $(".multiselect_div .multiselect-container input[value='CHEQUE (LOCAL)']").parent().parent().parent().removeClass("active");
                $(".multiselect_div .multiselect-container input[value='CHEQUE (FOREIGN)']").prop('disabled', true).prop("checked", false);
                $(".multiselect_div .multiselect-container input[value='CHEQUE (FOREIGN)']").parent().parent().parent().removeClass("active");
                $(".multiselect_div .multiselect-container input[value='CREDIT CARD']").prop('disabled', true).prop("checked", false);
                $(".multiselect_div .multiselect-container input[value='CREDIT CARD']").parent().parent().parent().removeClass("active");
                $(".multiselect_div .multiselect-container input[value='VIRTUAL CURRENCY']").prop('disabled', true).prop("checked", false);
                $(".multiselect_div .multiselect-container input[value='VIRTUAL CURRENCY']").parent().parent().parent().removeClass("active");
                $(".multiselect_div .multiselect-container input[value='CASH']").prop('disabled', true).prop("checked", false);
                $(".multiselect_div .multiselect-container input[value='CASH']").parent().parent().parent().removeClass("active");
                $(".multiselect_div .multiselect-container input[value='DIRECT DEBIT / CREDIT']").prop('disabled', true).prop("checked", false);
                $(".multiselect_div .multiselect-container input[value='DIRECT DEBIT / CREDIT']").parent().parent().parent().removeClass("active");
                $(".multiselect_div .multiselect-container input[value='NOT APPLICABLE']").prop('disabled', true).prop("checked", false);
                $(".multiselect_div .multiselect-container input[value='NOT APPLICABLE']").parent().parent().parent().removeClass("active");

                $("#corporate_payment_mode option[id='TELEGRAPHIC TRANSFER']").removeAttr('selected').prop('selected', false);
                $("#corporate_payment_mode option[id='CHEQUE (LOCAL)']").removeAttr('selected').prop('selected', false);
                $("#corporate_payment_mode option[id='CHEQUE (FOREIGN)']").removeAttr('selected').prop('selected', false);
                $("#corporate_payment_mode option[id='CREDIT CARD']").removeAttr('selected').prop('selected', false);
                $("#corporate_payment_mode option[id='VIRTUAL CURRENCY']").removeAttr('selected').prop('selected', false);
                $("#corporate_payment_mode option[id='CASH']").removeAttr('selected').prop('selected', false);
                $("#corporate_payment_mode option[id='DIRECT DEBIT / CREDIT']").removeAttr('selected').prop('selected', false);
                $("#corporate_payment_mode option[id='NOT APPLICABLE']").removeAttr('selected').prop('selected', false);
            }
            else if($(".multiselect_div .multiselect-container input[value='NOT APPLICABLE']").is(":checked"))
            {
                $(".multiselect_div .multiselect-container input[value='TELEGRAPHIC TRANSFER']").prop('disabled', true).prop("checked", false);
                $(".multiselect_div .multiselect-container input[value='TELEGRAPHIC TRANSFER']").parent().parent().parent().removeClass("active");
                $(".multiselect_div .multiselect-container input[value='CHEQUE (LOCAL)']").prop('disabled', true).prop("checked", false);
                $(".multiselect_div .multiselect-container input[value='CHEQUE (LOCAL)']").parent().parent().parent().removeClass("active");
                $(".multiselect_div .multiselect-container input[value='CHEQUE (FOREIGN)']").prop('disabled', true).prop("checked", false);
                $(".multiselect_div .multiselect-container input[value='CHEQUE (FOREIGN)']").parent().parent().parent().removeClass("active");
                $(".multiselect_div .multiselect-container input[value='CREDIT CARD']").prop('disabled', true).prop("checked", false);
                $(".multiselect_div .multiselect-container input[value='CREDIT CARD']").parent().parent().parent().removeClass("active");
                $(".multiselect_div .multiselect-container input[value='VIRTUAL CURRENCY']").prop('disabled', true).prop("checked", false);
                $(".multiselect_div .multiselect-container input[value='VIRTUAL CURRENCY']").parent().parent().parent().removeClass("active");
                $(".multiselect_div .multiselect-container input[value='CASH']").prop('disabled', true).prop("checked", false);
                $(".multiselect_div .multiselect-container input[value='CASH']").parent().parent().parent().removeClass("active");
                $(".multiselect_div .multiselect-container input[value='DIRECT DEBIT / CREDIT']").prop('disabled', true).prop("checked", false);
                $(".multiselect_div .multiselect-container input[value='DIRECT DEBIT / CREDIT']").parent().parent().parent().removeClass("active");
                $(".multiselect_div .multiselect-container input[value='UNKNOWN']").prop('disabled', true).prop("checked", false);
                $(".multiselect_div .multiselect-container input[value='UNKNOWN']").parent().parent().parent().removeClass("active");
                
                $("#corporate_payment_mode option[id='TELEGRAPHIC TRANSFER']").removeAttr('selected').prop('selected', false);
                $("#corporate_payment_mode option[id='CHEQUE (LOCAL)']").removeAttr('selected').prop('selected', false);
                $("#corporate_payment_mode option[id='CHEQUE (FOREIGN)']").removeAttr('selected').prop('selected', false);
                $("#corporate_payment_mode option[id='CREDIT CARD']").removeAttr('selected').prop('selected', false);
                $("#corporate_payment_mode option[id='VIRTUAL CURRENCY']").removeAttr('selected').prop('selected', false);
                $("#corporate_payment_mode option[id='CASH']").removeAttr('selected').prop('selected', false);
                $("#corporate_payment_mode option[id='DIRECT DEBIT / CREDIT']").removeAttr('selected').prop('selected', false);
                $("#corporate_payment_mode option[id='UNKNOWN']").removeAttr('selected').prop('selected', false);
            }
            else
            {
                $(".multiselect_div .multiselect-container input[value='TELEGRAPHIC TRANSFER']").prop('disabled', false);
                $(".multiselect_div .multiselect-container input[value='CHEQUE (LOCAL)']").prop('disabled', false);
                $(".multiselect_div .multiselect-container input[value='CHEQUE (FOREIGN)']").prop('disabled', false);
                $(".multiselect_div .multiselect-container input[value='CREDIT CARD']").prop('disabled', false);
                $(".multiselect_div .multiselect-container input[value='VIRTUAL CURRENCY']").prop('disabled', false);
                $(".multiselect_div .multiselect-container input[value='CASH']").prop('disabled', false);
                $(".multiselect_div .multiselect-container input[value='DIRECT DEBIT / CREDIT']").prop('disabled', false);
                $(".multiselect_div .multiselect-container input[value='NOT APPLICABLE']").prop('disabled', false);
                $(".multiselect_div .multiselect-container input[value='UNKNOWN']").prop('disabled', false);

                $("#corporate_payment_mode").find('option[value="TELEGRAPHIC TRANSFER"]').prop('disabled',false);
                $("#corporate_payment_mode").find('option[value="CHEQUE (LOCAL)"]').prop('disabled',false);
                $("#corporate_payment_mode").find('option[value="CHEQUE (FOREIGN)"]').prop('disabled',false);
                $("#corporate_payment_mode").find('option[value="CREDIT CARD"]').prop('disabled',false);
                $("#corporate_payment_mode").find('option[value="VIRTUAL CURRENCY"]').prop('disabled',false);
                $("#corporate_payment_mode").find('option[value="CASH"]').prop('disabled',false);
                $("#corporate_payment_mode").find('option[value="DIRECT DEBIT / CREDIT"]').prop('disabled',false);
            }   
            $('#kycScreeningCorporate-form').bootstrapValidator('revalidateField', 'corporate_payment_mode[]');
        }
    }).end()
    .bootstrapValidator({
        excluded: [':disabled'],
        submitButtons: 'input[class="corporate_kyc_submit"]',
        fields: {
            corporate_name: {
                validators: {
                    notEmpty: {
                        message: 'The Company Name is required'
                    }
                }
            },
            corporate_entity_type: {
                validators: {
                    callback: {
                        message: 'The Entity Type is required',
                        callback: function(value, validator, $field) {
                            var options = validator.getFieldElements('corporate_entity_type').val();
                            return (options != null);
                        }
                    }
                }
            },
            corporate_ownership_structure_layer: {
                validators: {
                    callback: {
                        message: 'The Ownership Structure Layer is required',
                        callback: function(value, validator, $field) {
                            var options = validator.getFieldElements('corporate_ownership_structure_layer').val();
                            return (options != null);
                        }
                    }
                }
            },
            corporate_country_of_incorporation: {
                validators: {
                    callback: {
                        message: 'The Country of Incorporation is required',
                        callback: function(value, validator, $field) {
                            var options = validator.getFieldElements('corporate_country_of_incorporation').val();
                            return (options != null);
                        }
                    }
                }
            },
            corporate_country_of_major_operation: {
                validators: {
                    callback: {
                        message: 'The Country of Major Operation is required',
                        callback: function(value, validator, $field) {
                            var options = validator.getFieldElements('corporate_country_of_major_operation').val();
                            return (options != null);
                        }
                    }
                }
            },
            corporate_primary_business_activity: {
                validators: {
                    callback: {
                        message: 'The Primary Business Activity is required',
                        callback: function(value, validator, $field) {
                            var options = validator.getFieldElements('corporate_primary_business_activity').val();
                            return (options != null);
                        }
                    }
                }
            },
            corporate_onboarding_mode: {
                validators: {
                    callback: {
                        message: 'The Onboarding Mode is required',
                        callback: function(value, validator, $field) {
                            var options = validator.getFieldElements('corporate_onboarding_mode').val();
                            return (options != null);
                        }
                    }
                }
            },
            corporate_product_service_complexity: {
                validators: {
                    callback: {
                        message: 'The Product Service Complexity is required',
                        callback: function(value, validator, $field) {
                            var options = validator.getFieldElements('corporate_product_service_complexity').val();
                            return (options != null);
                        }
                    }
                }
            },
            'corporate_payment_mode[]': {
                validators: {
                    callback: {
                        message: 'Please choose at least one Payment Mode.',
                        callback: function(value, validator, $field) {
                            var options = validator.getFieldElements('corporate_payment_mode[]').val();
                            return (options != null);
                        }
                    }
                }
            }
        }
    });
}

$("#corporate_source_of_funds").change(function (){
    if($(this).val() == "OTHERS")
    {
        $(".other_corp_identity_document_type_div").show();
    }
    else
    {
        $(".other_corp_identity_document_type_div").hide();
    }
    $("#corporate_other_source_of_funds").val("");
});

//----------------------------corporate_address-----------------------------------------------------
$('.show_corporate_address').click(function(e){
    e.preventDefault();
    $(this).parent().parent().find(".local_corporate_address_toggle").toggle();
    //console.log($(this).parent().parent().find(".local_corporate_address_toggle"));
    var icon = $(this).find(".fa");
    if(icon.hasClass("fa-arrow-down"))
    {
        icon.addClass("fa-arrow-up").removeClass("fa-arrow-down");
        //$(this).find(".toggle_word").text('Show less');
    }
    else
    {
        icon.addClass("fa-arrow-down").removeClass("fa-arrow-up");
        //$(this).find(".toggle_word").text('Show more');
    }
});

//edit
function corporate_address(officer_corporate_address)
{
    if(officer_corporate_address != null)
    {
        for (var h = 0; h < officer_corporate_address.length; h++) 
        {
            var officercorporate_addressArray = officer_corporate_address;//officer_corporate_address[h].split(',')

            if(h == 0)
            {
                $(".fieldGroup_corporate_address").find('.main_corporate_address').attr("value", officercorporate_addressArray[0]);
                $(".fieldGroup_corporate_address").find('.main_corporate_address_primary').attr("value", officercorporate_addressArray[0]);

                $(".fieldGroup_corporate_address").find(".button_increment_corporate_address").css({"visibility": "visible"});
            }
            else
            {
                $(".fieldGroupCopy_corporate_address").find('.second_corporate_address').attr("value", officercorporate_addressArray[0]);

                $(".fieldGroupCopy_corporate_address").find('.corporate_address_primary').attr("value", officercorporate_addressArray[0]);

                var fieldHTML = '<div class="input-group fieldGroup_corporate_address" style="margin-top:10px; display: block;">'+$(".fieldGroupCopy_corporate_address").html()+'</div>';

                //$('body').find('.fieldGroup_corporate_address:first').after(fieldHTML);
                $( fieldHTML).prependTo(".local_corporate_address_toggle");

                $(".fieldGroupCopy_corporate_address").find('.second_corporate_address').attr("value", "");
                $(".fieldGroupCopy_corporate_address").find('.corporate_address_primary').attr("value", "");

                $(".show_corporate_address").css({"visibility": "visible"});
                $(".local_corporate_address_toggle").hide();
            }
        }
    }
}

//put to hidden and radio button value when finish typing
$(document).on('blur', '.check_empty_corporate_address', function(){

    $(this).parent().find(".corporate_address_primary").attr("value", $(this).val());
});


$(document).ready(function() {
    $(document).on('click', '.corporate_address_primary', function(event){  
        event.preventDefault();
        var corporate_address_primary_radio_button = $(this);
        bootbox.confirm("Are you comfirm set as primary for this address?", function (result) {
            if (result) {
                corporate_address_primary_radio_button.prop( "checked", true );
            }
        });
    });

    //corporate_corporate_address
    $(".addMore_corporate_address").click(function(){

        $(".local_corporate_address_toggle").show();
        $(".show_corporate_address").find(".fa").addClass("fa-arrow-up").removeClass("fa-arrow-down");
        $(".show_corporate_address").find(".toggle_word").text('Show less');

        $(".fieldGroupCopy_corporate_address").find('.second_corporate_address').attr("value", $(".main_corporate_address").val());

        $(".fieldGroupCopy_corporate_address").find('.corporate_address_primary').attr("value", $(".main_corporate_address").val());

        var fieldHTML = '<div class="input-group fieldGroup_corporate_address" style="margin-top:10px; display: block;">'+$(".fieldGroupCopy_corporate_address").html()+'</div>';

        $( fieldHTML).prependTo(".local_corporate_address_toggle");

        if ($(".main_corporate_address_primary").is(":checked")) 
        {
            $(".local_corporate_address_toggle .fieldGroup_corporate_address").eq(0).find('.corporate_address_primary').prop( "checked", true );
        }

        $(".button_increment_corporate_address").css({"visibility": "hidden"});

        if ($(".local_corporate_address_toggle").find(".second_corporate_address").length > 0) 
        {
            $(".show_corporate_address").css({"visibility": "visible"});

        }
        else {
            $(".show_corporate_address").css({"visibility": "hidden"});
            
        }
       
        $(".main_corporate_address").val("");
        $(".main_corporate_address").parent().find(".corporate_address_primary").val("");
        $(".fieldGroupCopy_corporate_address").find('.second_corporate_address').attr("value", "");
        $(".fieldGroupCopy_corporate_address").find('.corporate_address_primary').attr("value", "");

    });

    $("body").on("click",".remove_corporate_address",function(){ 
        var remove_corporate_address = $(this);
        bootbox.confirm("Are you comfirm delete this address?", function (result) {
            if (result) {

                remove_corporate_address.parents(".fieldGroup_corporate_address").remove();

                if (remove_corporate_address.parent().find(".corporate_address_primary").is(":checked")) 
                {
                    if ($(".local_corporate_address_toggle").find(".second_corporate_address").length > 0) 
                    {
                        $(".local_corporate_address_toggle .fieldGroup_corporate_address").eq(0).find('.corporate_address_primary').prop( "checked", true );
                    }
                    else
                    {
                        $(".main_corporate_address_primary").prop( "checked", true );
                    }
                }

                if ($(".local_corporate_address_toggle").find(".second_corporate_address").length > 0) 
                {
                    $(".show_corporate_address").css({"visibility": "visible"});

                }
                else {
                    $(".show_corporate_address").css({"visibility": "hidden"});
                    
                }
                
                $( '#form_corporate_address' ).html("");
            }
        });
    });

    $('.main_corporate_address').keyup(function(){

        if ($(this).val()) {
            $(".button_increment_corporate_address").css({"visibility": "visible"});

        }
        else {
            $(".button_increment_corporate_address").css({"visibility": "hidden"});
        }
    });
    if ($(".local_corporate_address_toggle").find(".second_corporate_address").length > 0) 
    {
        $(".show_corporate_address").css({"visibility": "visible"});
        $(".local_corporate_address_toggle").hide();

    }
    else {
        $(".show_corporate_address").css({"visibility": "hidden"});
        $(".local_corporate_address_toggle").hide();
    }
});
//--------------------------------------end_corporate_address---------------------------------------

//--------------------------------------corporate_phone_number---------------------------------------
$('.show_corporate_phone_number').click(function(e){
    e.preventDefault();
    $(this).parent().parent().find(".corporate_phone_number_toggle").toggle();
    var icon = $(this).find(".fa");
    if(icon.hasClass("fa-arrow-down"))
    {
        icon.addClass("fa-arrow-up").removeClass("fa-arrow-down");
        //$(this).find(".toggle_word").text('Show less');
    }
    else
    {
        icon.addClass("fa-arrow-down").removeClass("fa-arrow-up");
        //$(this).find(".toggle_word").text('Show more');
    }
});

$('.fieldGroup_corporate_phone_number .hp').intlTelInput({
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
function corporate_mobile_no(officer_mobile_no)
{
    if(officer_mobile_no != null)
    {
        for (var h = 0; h < officer_mobile_no.length; h++) 
        {
            var officerMobileNoArray = officer_mobile_no[h].split(',');

            if(h == 0)
            {
                $(".fieldGroup_corporate_phone_number").find('.main_corporate_phone_number').intlTelInput("setNumber", officerMobileNoArray[0]);
                $(".fieldGroup_corporate_phone_number").find('.main_hidden_corporate_phone_number').attr("value", officerMobileNoArray[0]);
                $(".fieldGroup_corporate_phone_number").find('.main_corporate_phone_number_primary').attr("value", officerMobileNoArray[0]);
                $(".fieldGroup_corporate_phone_number").find(".button_increment_corporate_phone_number").css({"visibility": "visible"});
            }
            else
            {
                
                $(".fieldGroupCopy_corporate_phone_number").find('.hidden_corporate_phone_number').attr("value", officerMobileNoArray[0]);
                $(".fieldGroupCopy_corporate_phone_number").find('.corporate_phone_number_primary').attr("value", officerMobileNoArray[0]);

                var fieldHTML = '<div class="input-group fieldGroup_corporate_phone_number" style="margin-top:10px; width: 100%;">'+$(".fieldGroupCopy_corporate_phone_number").html()+'</div>';

                //$('body').find('.fieldGroup_corporate_phone_number:first').after(fieldHTML);
                $( fieldHTML).prependTo(".corporate_phone_number_toggle");

                $('.corporate_phone_number_toggle .fieldGroup_corporate_phone_number').eq(0).find('.second_hp').intlTelInput({
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

                $('.corporate_phone_number_toggle .fieldGroup_corporate_phone_number').eq(0).find('.second_hp').intlTelInput("setNumber", officerMobileNoArray[0]);

                $('.corporate_phone_number_toggle .fieldGroup_corporate_phone_number').eq(0).find('.second_hp').on({
                  keydown: function(e) {
                    if (e.which === 32)
                      return false;
                  },
                  change: function() {
                    this.value = this.value.replace(/\s/g, "");
                  }
                });

                $(".fieldGroupCopy_corporate_phone_number").find('.hidden_corporate_phone_number').attr("value", "");
                $(".fieldGroupCopy_corporate_phone_number").find('.corporate_phone_number_primary').attr("value", "");

                $(".show_corporate_phone_number").css({"visibility": "visible"});
                $(".corporate_phone_number_toggle").hide();
            }
        }
    }
}

//put to hidden and radio button value when finish typing
$(document).on('blur', '.check_empty_corporate_phone_number', function(){
    //console.log($(this).val());
    $(this).parent().parent().find(".hidden_corporate_phone_number").attr("value", $(this).intlTelInput("getNumber", intlTelInputUtils.numberFormat.E164));
    $(this).parent().parent().find(".corporate_phone_number_primary").attr("value", $(this).intlTelInput("getNumber", intlTelInputUtils.numberFormat.E164));
});

$(document).ready(function() {
    $(document).on('click', '.corporate_phone_number_primary', function(event){
        event.preventDefault();
        var corporate_phone_number_primary_radio_button = $(this);
        bootbox.confirm("Are you comfirm set as primary for this Mobile No?", function (result) {
            if (result) {
                corporate_phone_number_primary_radio_button.prop( "checked", true );
            }
        });
        
    });

    $(".check_empty_corporate_phone_number").on({
      keydown: function(e) {
        if (e.which === 32)
          return false;
      },
      change: function() {
        this.value = this.value.replace(/\s/g, "");
      }
    });

    $(".addMore_corporate_phone_number").click(function(){
        var number = $(".main_corporate_phone_number").intlTelInput("getNumber", intlTelInputUtils.numberFormat.E164);

        var countryData = $(".main_corporate_phone_number").intlTelInput("getSelectedCountryData");

        $(".corporate_phone_number_toggle").show();
        $(".show_corporate_phone_number").find(".fa").addClass("fa-arrow-up").removeClass("fa-arrow-down");
        $(".show_corporate_phone_number").find(".toggle_word").text('Show less');

        $(".fieldGroupCopy_corporate_phone_number").find('.second_corporate_phone_number').attr("value", $(".main_corporate_phone_number").val());
        $(".fieldGroupCopy_corporate_phone_number").find('.hidden_corporate_phone_number').attr("value", number);
        $(".fieldGroupCopy_corporate_phone_number").find('.corporate_phone_number_primary').attr("value", number);

        var fieldHTML = '<div class="input-group fieldGroup_corporate_phone_number" style="margin-top:10px; width: 100%;">'+$(".fieldGroupCopy_corporate_phone_number").html()+'</div>';

        $( fieldHTML).prependTo(".corporate_phone_number_toggle");

        $('.corporate_phone_number_toggle .fieldGroup_corporate_phone_number').eq(0).find('.second_hp').intlTelInput({
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

        $('.corporate_phone_number_toggle .fieldGroup_corporate_phone_number').eq(0).find('.second_hp').on({
          keydown: function(e) {
            if (e.which === 32)
              return false;
          },
          change: function() {
            this.value = this.value.replace(/\s/g, "");
          }
        });

        if ($(".main_corporate_phone_number_primary").is(":checked")) 
        {
            $('.corporate_phone_number_toggle .fieldGroup_corporate_phone_number').eq(0).find('.corporate_phone_number_primary').prop( "checked", true );
        }

        $(".button_increment_corporate_phone_number").css({"visibility": "hidden"});

        if ($(".corporate_phone_number_toggle").find(".second_corporate_phone_number").length > 0) 
        {
            $(".show_corporate_phone_number").css({"visibility": "visible"});

        }
        else {
            $(".show_corporate_phone_number").css({"visibility": "hidden"});
            
        }

        $(".main_corporate_phone_number").val("");
        $(".main_corporate_phone_number").parent().parent().find(".hidden_corporate_phone_number").val("");
        $(".main_corporate_phone_number").parent().parent().find(".corporate_phone_number_primary").val("");
        $(".fieldGroupCopy_corporate_phone_number").find('.second_corporate_phone_number').attr("value", "");
        $(".fieldGroupCopy_corporate_phone_number").find('.hidden_corporate_phone_number').attr("value", "");
        $(".fieldGroupCopy_corporate_phone_number").find('.corporate_phone_number_primary').attr("value", "");
        $(".fieldGroupCopy_corporate_phone_number").find('.corporate_phone_number_primary').prop( "checked", false );

    });

    $("body").on("click",".remove_corporate_phone_number",function(){ 
        var remove_corporate_phone_number_button = $(this);
        bootbox.confirm("Are you comfirm delete this Mobile No?", function (result) {
            if (result) {
                remove_corporate_phone_number_button.parents(".fieldGroup_corporate_phone_number").remove();

                if (remove_corporate_phone_number_button.parent().find(".corporate_phone_number_primary").is(":checked")) 
                {
                    if ($(".corporate_phone_number_toggle").find(".second_corporate_phone_number").length > 0) 
                    {
                        $('.corporate_phone_number_toggle .fieldGroup_corporate_phone_number').eq(0).find('.corporate_phone_number_primary').prop( "checked", true );
                    }
                    else
                    {
                        $(".main_corporate_phone_number_primary").prop( "checked", true );
                    }
                }

                if ($(".corporate_phone_number_toggle").find(".second_corporate_phone_number").length > 0) 
                {
                    $(".show_corporate_phone_number").css({"visibility": "visible"});

                }
                else {
                    $(".show_corporate_phone_number").css({"visibility": "hidden"});
                    
                }
                $( '#form_corporate_phone_number' ).html("");
            }
        });
    });

    $('.main_corporate_phone_number').keyup(function(){

        if ($(this).val()) {
            $(".button_increment_corporate_phone_number").css({"visibility": "visible"});

        }
        else {
            $(".button_increment_corporate_phone_number").css({"visibility": "hidden"});
        }
    });

    if ($(".corporate_phone_number_toggle").find(".second_corporate_phone_number").length > 0) 
    {
        $(".show_corporate_phone_number").css({"visibility": "visible"});
        $(".corporate_phone_number_toggle").hide();

    }
    else {
        $(".show_corporate_phone_number").css({"visibility": "hidden"});
        $(".corporate_phone_number_toggle").hide();
    }
});

//--------------------------------------end_corporate_phone_number---------------------------------------

//----------------------------corporate_email_address-----------------------------------------------------
$('.show_corporate_email_address').click(function(e){
    e.preventDefault();
    $(this).parent().parent().find(".local_corporate_email_address_toggle").toggle();
    //console.log($(this).parent().parent().find(".local_corporate_email_address_toggle"));
    var icon = $(this).find(".fa");
    if(icon.hasClass("fa-arrow-down"))
    {
        icon.addClass("fa-arrow-up").removeClass("fa-arrow-down");
        //$(this).find(".toggle_word").text('Show less');
    }
    else
    {
        icon.addClass("fa-arrow-down").removeClass("fa-arrow-up");
        //$(this).find(".toggle_word").text('Show more');
    }
});

//edit
function corporate_email_address(officer_corporate_email_address)
{
    if(officer_corporate_email_address != null)
    {
        for (var h = 0; h < officer_corporate_email_address.length; h++) 
        {
            var officercorporate_email_addressArray = officer_corporate_email_address[h].split(',');

            if(h == 0)
            {
                $(".fieldGroup_corporate_email_address").find('.main_corporate_email_address').attr("value", officercorporate_email_addressArray[0]);
                $(".fieldGroup_corporate_email_address").find('.main_corporate_email_address_primary').attr("value", officercorporate_email_addressArray[0]);

                $(".fieldGroup_corporate_email_address").find(".button_increment_corporate_email_address").css({"visibility": "visible"});
            }
            else
            {
                $(".fieldGroupCopy_corporate_email_address").find('.second_corporate_email_address').attr("value", officercorporate_email_addressArray[0]);

                $(".fieldGroupCopy_corporate_email_address").find('.corporate_email_address_primary').attr("value", officercorporate_email_addressArray[0]);

                var fieldHTML = '<div class="input-group fieldGroup_corporate_email_address" style="margin-top:10px; display: block;">'+$(".fieldGroupCopy_corporate_email_address").html()+'</div>';

                //$('body').find('.fieldGroup_corporate_email_address:first').after(fieldHTML);
                $( fieldHTML).prependTo(".local_corporate_email_address_toggle");

                $(".fieldGroupCopy_corporate_email_address").find('.second_corporate_email_address').attr("value", "");
                $(".fieldGroupCopy_corporate_email_address").find('.corporate_email_address_primary').attr("value", "");

                $(".show_corporate_email_address").css({"visibility": "visible"});
                $(".local_corporate_email_address_toggle").hide();
            }
        }
    }
}

//put to hidden and radio button value when finish typing
$(document).on('blur', '.check_empty_corporate_email_address', function(){

    $(this).parent().find(".corporate_email_address_primary").attr("value", $(this).val());
});


$(document).ready(function() {
    $(document).on('click', '.corporate_email_address_primary', function(event){  
        event.preventDefault();
        var corporate_email_address_primary_radio_button = $(this);
        bootbox.confirm("Are you comfirm set as primary for this email address?", function (result) {
            if (result) {
                corporate_email_address_primary_radio_button.prop( "checked", true );
            }
        });
    });

    //corporate_corporate_email_address
    $(".addMore_corporate_email_address").click(function(){

        $(".local_corporate_email_address_toggle").show();
        $(".show_corporate_email_address").find(".fa").addClass("fa-arrow-up").removeClass("fa-arrow-down");
        $(".show_corporate_email_address").find(".toggle_word").text('Show less');

        $(".fieldGroupCopy_corporate_email_address").find('.second_corporate_email_address').attr("value", $(".main_corporate_email_address").val());

        $(".fieldGroupCopy_corporate_email_address").find('.corporate_email_address_primary').attr("value", $(".main_corporate_email_address").val());

        var fieldHTML = '<div class="input-group fieldGroup_corporate_email_address" style="margin-top:10px; display: block;">'+$(".fieldGroupCopy_corporate_email_address").html()+'</div>';

        $( fieldHTML).prependTo(".local_corporate_email_address_toggle");

        if ($(".main_corporate_email_address_primary").is(":checked")) 
        {
            $(".local_corporate_email_address_toggle .fieldGroup_corporate_email_address").eq(0).find('.corporate_email_address_primary').prop( "checked", true );
        }

        $(".button_increment_corporate_email_address").css({"visibility": "hidden"});

        if ($(".local_corporate_email_address_toggle").find(".second_corporate_email_address").length > 0) 
        {
            $(".show_corporate_email_address").css({"visibility": "visible"});

        }
        else {
            $(".show_corporate_email_address").css({"visibility": "hidden"});
            
        }
       
        $(".main_corporate_email_address").val("");
        $(".main_corporate_email_address").parent().find(".corporate_email_address_primary").val("");
        $(".fieldGroupCopy_corporate_email_address").find('.second_corporate_email_address').attr("value", "");
        $(".fieldGroupCopy_corporate_email_address").find('.corporate_email_address_primary').attr("value", "");

    });

    $("body").on("click",".remove_corporate_email_address",function(){ 
        var remove_corporate_email_address = $(this);
        bootbox.confirm("Are you comfirm delete this address?", function (result) {
            if (result) {

                remove_corporate_email_address.parents(".fieldGroup_corporate_email_address").remove();

                if (remove_corporate_email_address.parent().find(".corporate_email_address_primary").is(":checked")) 
                {
                    if ($(".local_corporate_email_address_toggle").find(".second_corporate_email_address").length > 0) 
                    {
                        $(".local_corporate_email_address_toggle .fieldGroup_corporate_email_address").eq(0).find('.corporate_email_address_primary').prop( "checked", true );
                    }
                    else
                    {
                        $(".main_corporate_email_address_primary").prop( "checked", true );
                    }
                }

                if ($(".local_corporate_email_address_toggle").find(".second_corporate_email_address").length > 0) 
                {
                    $(".show_corporate_email_address").css({"visibility": "visible"});

                }
                else {
                    $(".show_corporate_email_address").css({"visibility": "hidden"});
                    
                }
                
                $( '#form_corporate_email_address' ).html("");
            }
        });
    });

    $('.main_corporate_email_address').keyup(function(){

        if ($(this).val()) {
            $(".button_increment_corporate_email_address").css({"visibility": "visible"});

        }
        else {
            $(".button_increment_corporate_email_address").css({"visibility": "hidden"});
        }
    });
    if ($(".local_corporate_email_address_toggle").find(".second_corporate_email_address").length > 0) 
    {
        $(".show_corporate_email_address").css({"visibility": "visible"});
        $(".local_corporate_email_address_toggle").hide();

    }
    else {
        $(".show_corporate_email_address").css({"visibility": "hidden"});
        $(".local_corporate_email_address_toggle").hide();
    }
});
//--------------------------------------end_corporate_email_address---------------------------------------

//----------------------------corporate_bank_account-----------------------------------------------------
$('.show_corporate_bank_account').click(function(e){
    e.preventDefault();
    $(this).parent().parent().find(".local_corporate_bank_account_toggle").toggle();
    //console.log($(this).parent().parent().find(".local_corporate_bank_account_toggle"));
    var icon = $(this).find(".fa");
    if(icon.hasClass("fa-arrow-down"))
    {
        icon.addClass("fa-arrow-up").removeClass("fa-arrow-down");
        //$(this).find(".toggle_word").text('Show less');
    }
    else
    {
        icon.addClass("fa-arrow-down").removeClass("fa-arrow-up");
        //$(this).find(".toggle_word").text('Show more');
    }
});

//edit
function corporate_bank_account(officer_corporate_bank_account)
{
    if(officer_corporate_bank_account != null)
    {
        for (var h = 0; h < officer_corporate_bank_account.length; h++) 
        {
            var officercorporate_bank_accountArray = officer_corporate_bank_account[h].split(',');

            if(h == 0)
            {
                $(".fieldGroup_corporate_bank_account").find('.main_corporate_bank_account').attr("value", officercorporate_bank_accountArray[0]);
                $(".fieldGroup_corporate_bank_account").find('.main_corporate_bank_account_primary').attr("value", officercorporate_bank_accountArray[0]);

                $(".fieldGroup_corporate_bank_account").find(".button_increment_corporate_bank_account").css({"visibility": "visible"});
            }
            else
            {
                $(".fieldGroupCopy_corporate_bank_account").find('.second_corporate_bank_account').attr("value", officercorporate_bank_accountArray[0]);

                $(".fieldGroupCopy_corporate_bank_account").find('.corporate_bank_account_primary').attr("value", officercorporate_bank_accountArray[0]);

                var fieldHTML = '<div class="input-group fieldGroup_corporate_bank_account" style="margin-top:10px; display: block;">'+$(".fieldGroupCopy_corporate_bank_account").html()+'</div>';

                //$('body').find('.fieldGroup_corporate_bank_account:first').after(fieldHTML);
                $( fieldHTML).prependTo(".local_corporate_bank_account_toggle");

                $(".fieldGroupCopy_corporate_bank_account").find('.second_corporate_bank_account').attr("value", "");
                $(".fieldGroupCopy_corporate_bank_account").find('.corporate_bank_account_primary').attr("value", "");
                
                $(".show_corporate_bank_account").css({"visibility": "visible"});
                $(".local_corporate_bank_account_toggle").hide();
            }
        }
    }
}

//put to hidden and radio button value when finish typing
$(document).on('blur', '.check_empty_corporate_bank_account', function(){

    $(this).parent().find(".corporate_bank_account_primary").attr("value", $(this).val());
});


$(document).ready(function() {
    $(document).on('click', '.corporate_bank_account_primary', function(event){  
        event.preventDefault();
        var corporate_bank_account_primary_radio_button = $(this);
        bootbox.confirm("Are you comfirm set as primary for this bank account?", function (result) {
            if (result) {
                corporate_bank_account_primary_radio_button.prop( "checked", true );
            }
        });
    });

    //corporate_corporate_bank_account
    $(".addMore_corporate_bank_account").click(function(){

        $(".local_corporate_bank_account_toggle").show();
        $(".show_corporate_bank_account").find(".fa").addClass("fa-arrow-up").removeClass("fa-arrow-down");
        $(".show_corporate_bank_account").find(".toggle_word").text('Show less');

        $(".fieldGroupCopy_corporate_bank_account").find('.second_corporate_bank_account').attr("value", $(".main_corporate_bank_account").val());

        $(".fieldGroupCopy_corporate_bank_account").find('.corporate_bank_account_primary').attr("value", $(".main_corporate_bank_account").val());

        var fieldHTML = '<div class="input-group fieldGroup_corporate_bank_account" style="margin-top:10px; display: block;">'+$(".fieldGroupCopy_corporate_bank_account").html()+'</div>';

        $( fieldHTML).prependTo(".local_corporate_bank_account_toggle");

        if ($(".main_corporate_bank_account_primary").is(":checked")) 
        {
            $(".local_corporate_bank_account_toggle .fieldGroup_corporate_bank_account").eq(0).find('.corporate_bank_account_primary').prop( "checked", true );
        }

        $(".button_increment_corporate_bank_account").css({"visibility": "hidden"});

        if ($(".local_corporate_bank_account_toggle").find(".second_corporate_bank_account").length > 0) 
        {
            $(".show_corporate_bank_account").css({"visibility": "visible"});

        }
        else {
            $(".show_corporate_bank_account").css({"visibility": "hidden"});
            
        }
       
        $(".main_corporate_bank_account").val("");
        $(".main_corporate_bank_account").parent().find(".corporate_bank_account_primary").val("");
        $(".fieldGroupCopy_corporate_bank_account").find('.second_corporate_bank_account').attr("value", "");
        $(".fieldGroupCopy_corporate_bank_account").find('.corporate_bank_account_primary').attr("value", "");

    });

    $("body").on("click",".remove_corporate_bank_account",function(){ 
        var remove_corporate_bank_account = $(this);
        bootbox.confirm("Are you comfirm delete this address?", function (result) {
            if (result) {

                remove_corporate_bank_account.parents(".fieldGroup_corporate_bank_account").remove();

                if (remove_corporate_bank_account.parent().find(".corporate_bank_account_primary").is(":checked")) 
                {
                    if ($(".local_corporate_bank_account_toggle").find(".second_corporate_bank_account").length > 0) 
                    {
                        $(".local_corporate_bank_account_toggle .fieldGroup_corporate_bank_account").eq(0).find('.corporate_bank_account_primary').prop( "checked", true );
                    }
                    else
                    {
                        $(".main_corporate_bank_account_primary").prop( "checked", true );
                    }
                }

                if ($(".local_corporate_bank_account_toggle").find(".second_corporate_bank_account").length > 0) 
                {
                    $(".show_corporate_bank_account").css({"visibility": "visible"});

                }
                else {
                    $(".show_corporate_bank_account").css({"visibility": "hidden"});
                    
                }
                
                $( '#form_corporate_bank_account' ).html("");
            }
        });
    });

    $('.main_corporate_bank_account').keyup(function(){

        if ($(this).val()) {
            $(".button_increment_corporate_bank_account").css({"visibility": "visible"});

        }
        else {
            $(".button_increment_corporate_bank_account").css({"visibility": "hidden"});
        }
    });
    if ($(".local_corporate_bank_account_toggle").find(".second_corporate_bank_account").length > 0) 
    {
        $(".show_corporate_bank_account").css({"visibility": "visible"});
        $(".local_corporate_bank_account_toggle").hide();
    }
    else {
        $(".show_corporate_bank_account").css({"visibility": "hidden"});
        $(".local_corporate_bank_account_toggle").hide();
    }
});
//--------------------------------------end_corporate_bank_account---------------------------------------