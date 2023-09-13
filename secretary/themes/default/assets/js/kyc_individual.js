$(window).on('load', function() {
    var countries_string = localStorage.getItem("kyc_countries");
    var kycCountriesArray = countries_string.split(',');

    var identity_document_type_string = localStorage.getItem("kyc_identity_document_type");
    var kycIdentityDocumentTypeArray = identity_document_type_string.split(',');

    var ssic_string = localStorage.getItem("kyc_ssic");
    var kycSSICArray = JSON.parse(ssic_string);
    //console.log(kycSSICArray);

    var onboarding_mode_string = localStorage.getItem("kyc_onboarding_mode");
    var kycOnboardingModeArray = onboarding_mode_string.split(',');

    var payment_modes_string = localStorage.getItem("kyc_payment_modes");
    var kycPaymentModeArray = payment_modes_string.split(',');

    var product_service_complexities_string = localStorage.getItem("kyc_product_service_complexities");
    var kycProductServiceComplexitiesArray = product_service_complexities_string.split(',');

    var source_of_funds_string = localStorage.getItem("kyc_source_of_funds");
    var kycSourceOfFundsArray = source_of_funds_string.split(',');

    var occupation_string = localStorage.getItem("kyc_occupation");
    var kycOccupationArray = JSON.parse(occupation_string);

	$.each(kycCountriesArray, function(key, val) {
        var option = $('<option />');
        option.attr('value', val).text(val);
        $('#individual_nationality').append(option);
    });

    $.each(kycCountriesArray, function(key, val) {
        var option = $('<option />');
        option.attr('value', val).text(val);
        $('#individual_country_of_residence').append(option);
    });

    $.each(kycCountriesArray, function(key, val) {
        var option = $('<option />');
        option.attr('value', val).text(val);
        $('#individual_country_of_birth').append(option);
    });

    $.each(kycIdentityDocumentTypeArray, function(key, val) {
        var option = $('<option />');
        option.attr('value', val).text(val);
        $('#individual_identity_document_type').append(option);
    });

    $.each(kycSSICArray, function(key, val) {
        var option = $('<option />');
        option.attr('value', val).text(val);
        $('#individual_industry').append(option);
    });

    $.each(kycOnboardingModeArray, function(key, val) {
        var option = $('<option />');
        option.attr('value', val).text(val);
        $('#individual_onboarding_mode').append(option);
    });

    $.each(kycPaymentModeArray, function(key, val) {
        var option = $('<option />');
        option.attr({'value':val, 'id':val}).text(val);
        $('#individual_payment_mode').append(option);
    });

    $.each(kycProductServiceComplexitiesArray, function(key, val) {
        var option = $('<option />');
        option.attr('value', val).text(val);
        $('#individual_product_service_complexity').append(option);
    });

    $.each(kycSourceOfFundsArray, function(key, val) {
        var option = $('<option />');
        option.attr('value', val).text(val);
        $('#individual_source_of_funds').append(option);
    });

    $.each(kycOccupationArray, function(key, val) {
        var option = $('<option />');
        option.attr('value', val).text(val);
        $('#individual_occupation').append(option);
    });
    
    if(document.getElementById('individual_edit').checked) 
    {
        runIndividualValidate();
    }

    $('#loginForm').bootstrapValidator({
        fields: {
            username: {
                validators: {
                    notEmpty: {
                        message: 'The username is required'
                    }
                }
            },
            password: {
                validators: {
                    notEmpty: {
                        message: 'The password is required'
                    }
                }
            }
        }
    }).on('success.form.bv', function(e) {
        // Prevent form submission
        e.preventDefault();

        var $form = $(e.target),                        // The form instance
            bv    = $form.data('bootstrapValidator');   // BootstrapValidator instance

        var kycUsername = $('#loginForm input[name="username"]').val();
        var kycPassword = $('#loginForm input[name="password"]').val();

        getKYCUserToken (kycUsername, kycPassword, $form);

    });

    $(document).on('click',".individual_kyc_submit_screening",function(e){
        var $link = $(e.target);
        e.preventDefault();
        if(!$link.data('lockedAt') || +new Date() - $link.data('lockedAt') > 600) {
            $('#loginForm input[name="screening"]').val(true);
            submit_kyc_info();
        }
        $link.data('lockedAt', +new Date());
    });

    $(document).on('click',".individual_kyc_submit",function(e){
        var $link = $(e.target);
        e.preventDefault();
        if(!$link.data('lockedAt') || +new Date() - $link.data('lockedAt') > 600) {
            $('#loginForm input[name="screening"]').val(false);
            submit_kyc_info();
        }
        $link.data('lockedAt', +new Date());
    });

    function submit_kyc_info()
    {
        var bootstrapValidator = $("#kycScreeningIndividual-form").data('bootstrapValidator');
        bootstrapValidator.validate();
        if(bootstrapValidator.isValid())
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
                submit_kyc_info();
            }
        });
    }
});

function runIndividualValidate()
{
    var bootstrapValidator = $("#kycScreeningCorporate-form").data('bootstrapValidator');
    //console.log(bootstrapValidator);
    if (bootstrapValidator != undefined)
        bootstrapValidator.destroy();

    $('#kycScreeningIndividual-form')
    .find('[name="individual_payment_mode[]"]')
    .multiselect({
        buttonWidth: '100%',
        buttonClass: 'form-control individual_payment_mode_button',
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

                $("#individual_payment_mode option[id='TELEGRAPHIC TRANSFER']").removeAttr('selected').prop('selected', false);
                $("#individual_payment_mode option[id='CHEQUE (LOCAL)']").removeAttr('selected').prop('selected', false);
                $("#individual_payment_mode option[id='CHEQUE (FOREIGN)']").removeAttr('selected').prop('selected', false);
                $("#individual_payment_mode option[id='CREDIT CARD']").removeAttr('selected').prop('selected', false);
                $("#individual_payment_mode option[id='VIRTUAL CURRENCY']").removeAttr('selected').prop('selected', false);
                $("#individual_payment_mode option[id='CASH']").removeAttr('selected').prop('selected', false);
                $("#individual_payment_mode option[id='DIRECT DEBIT / CREDIT']").removeAttr('selected').prop('selected', false);
                $("#individual_payment_mode option[id='NOT APPLICABLE']").removeAttr('selected').prop('selected', false);
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
                
                $("#individual_payment_mode option[id='TELEGRAPHIC TRANSFER']").removeAttr('selected').prop('selected', false);
                $("#individual_payment_mode option[id='CHEQUE (LOCAL)']").removeAttr('selected').prop('selected', false);
                $("#individual_payment_mode option[id='CHEQUE (FOREIGN)']").removeAttr('selected').prop('selected', false);
                $("#individual_payment_mode option[id='CREDIT CARD']").removeAttr('selected').prop('selected', false);
                $("#individual_payment_mode option[id='VIRTUAL CURRENCY']").removeAttr('selected').prop('selected', false);
                $("#individual_payment_mode option[id='CASH']").removeAttr('selected').prop('selected', false);
                $("#individual_payment_mode option[id='DIRECT DEBIT / CREDIT']").removeAttr('selected').prop('selected', false);
                $("#individual_payment_mode option[id='UNKNOWN']").removeAttr('selected').prop('selected', false);
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

                $("#individual_payment_mode").find('option[value="TELEGRAPHIC TRANSFER"]').prop('disabled',false);
                $("#individual_payment_mode").find('option[value="CHEQUE (LOCAL)"]').prop('disabled',false);
                $("#individual_payment_mode").find('option[value="CHEQUE (FOREIGN)"]').prop('disabled',false);
                $("#individual_payment_mode").find('option[value="CREDIT CARD"]').prop('disabled',false);
                $("#individual_payment_mode").find('option[value="VIRTUAL CURRENCY"]').prop('disabled',false);
                $("#individual_payment_mode").find('option[value="CASH"]').prop('disabled',false);
                $("#individual_payment_mode").find('option[value="DIRECT DEBIT / CREDIT"]').prop('disabled',false);
            }   
            $('#kycScreeningIndividual-form').bootstrapValidator('revalidateField', 'individual_payment_mode[]');
        }
    }).end()
    .bootstrapValidator({
        excluded: ':disabled',
        submitButtons: 'input[class="individual_kyc_submit"]',
        fields: {
            individual_name: {
                validators: {
                    notEmpty: {
                        message: 'The Name is required'
                    }
                }
            },
            gender: {
                validators: {
                    callback: {
                        message: 'The Gender is required',
                        callback: function(value, validator, $field) {
                            var options = validator.getFieldElements('gender').val();
                            return (options != null);
                        }
                    }
                }
            },
            individual_country_of_residence: {
                validators: {
                    callback: {
                        message: 'The Country of Residence is required',
                        callback: function(value, validator, $field) {
                            var options = validator.getFieldElements('individual_country_of_residence').val();
                            return (options != null);
                        }
                    }
                }
            },
            individual_industry: {
                validators: {
                    callback: {
                        message: 'The Industry is required',
                        callback: function(value, validator, $field) {
                            var options = validator.getFieldElements('individual_industry').val();
                            return (options != null);
                        }
                    }
                }
            },
            individual_onboarding_mode: {
                validators: {
                    callback: {
                        message: 'The Onboarding Mode is required',
                        callback: function(value, validator, $field) {
                            var options = validator.getFieldElements('individual_onboarding_mode').val();
                            return (options != null);
                        }
                    }
                }
            },
            individual_product_service_complexity: {
                validators: {
                    callback: {
                        message: 'The Product Service Complexity is required',
                        callback: function(value, validator, $field) {
                            var options = validator.getFieldElements('individual_product_service_complexity').val();
                            return (options != null);
                        }
                    }
                }
            },
            individual_nationality: {
                validators: {
                    callback: {
                        message: 'The Nationality is required',
                        callback: function(value, validator, $field) {
                            var options = validator.getFieldElements('individual_nationality').val();
                            return (options != null);
                        }
                    }
                }
            },
            individual_occupation: {
                validators: {
                    callback: {
                        message: 'The Occupation is required',
                        callback: function(value, validator, $field) {
                            var options = validator.getFieldElements('individual_occupation').val();
                            return (options != null);
                        }
                    }
                }
            },
            'individual_payment_mode[]': {
                validators: {
                    callback: {
                        message: 'Please choose at least one Payment Mode.',
                        callback: function(value, validator, $field) {
                            var options = validator.getFieldElements('individual_payment_mode[]').val();
                            return (options != null
                                && options.length >= 1);
                        }
                    }
                }
            }
        }
    });
}
    
function getKYCUserToken (kycUsername, kycPassword, $form)
{
    var username = kycUsername;
    var password = kycPassword;

    var poolData = {
        UserPoolId: user_pool_id,
        ClientId: client_id
    };

    var userPool = new AmazonCognitoIdentity.CognitoUserPool(poolData);
    var authData = {
        Username: username,
        Password: password
    };

    var authDetails = new AmazonCognitoIdentity.AuthenticationDetails(authData);
    var userData = {
        Username: username,
        Pool: userPool
    };

    var cognitoUsers = new AmazonCognitoIdentity.CognitoUser(userData);
    cognitoUsers.authenticateUser(authDetails, {
        onSuccess: function(result)
        {
            var refreshToken = result.getRefreshToken().getToken();
            var accessToken = result.getAccessToken().getJwtToken();

            localStorage.setItem("accessToken", accessToken);
            localStorage.setItem("refreshUserToken", refreshToken);
            if(person_tab_aktif == "screening")
            {
                screeningResult(accessToken);                         
            }
            else if($("#checkbox_scan1").is(":checked"))
            {
                var scan_list = $('input[name="checkbox_scan[]"]:checked').map(function(_, el) {
                    return $(el).val();
                }).get();
                var accessToken = $("#ScanForm input[name='accessToken']").val();
                var customer_id = $("#ScanForm input[name='customer_id']").val();
                var record_id = $("#ScanForm input[name='record_id']").val();

                artemisCheck(scan_list, accessToken, customer_id, record_id);
            }
            else
            {
                saveKYCInfo(accessToken, result['idToken']['payload']['sub']);
                $form.parents('.bootbox').modal('hide');
            }
        },
        onFailure: function(err)
        {
            toastr.error((err.message || JSON.stringify(err)), "Error");
        }
    });
}

function screeningResult(accessToken)
{
    var customer_id = $('#w2-screening #customer_id').val();
    var refreshKYCInfo = $("#w2-screening #refreshKYCInfo").val();
    if(refreshKYCInfo == "true")
    {
        $.ajax({
            url: 'https://a2-acumenalphaadvisory-prod-be.cynopsis.co/client/customers/'+customer_id+'/risk_reports/?limit=999',
            headers: {
                'Content-Type': "application/json",
                'X-ARTEMIS-DOMAIN': "1",
                'Authorization': 'Bearer '+ accessToken,
            },
            method: 'GET',
            success: function(data){
                //console.log(data);
                var string_risk_report = JSON.stringify(data['results']);
                if(document.getElementById('individual_edit').checked) 
                {
                    var risk_data = JSON.parse('{"officer_id": '+$('#upload #officer_id').val()+', "riskInfo" : '+string_risk_report+'}');
                }
                else if(document.getElementById('company_edit').checked) 
                {
                    var risk_data = JSON.parse('{"corporate_officer_id": '+$('#submit_company #officer_company_id').val()+', "riskInfo" : '+string_risk_report+'}');
                }
                //var risk_data = '{"riskJson" : [{"id":50,"createdBy":{"id":"4a1d45db-d44a-44f9-97f1-bf548437a466","name":"Justin","email":"justin@aaa-global.com","oauthId":"4a1d45db-d44a-44f9-97f1-bf548437a466","isActive":true,"mfaEnabled":false},"updatedBy":{"id":"4a1d45db-d44a-44f9-97f1-bf548437a466","name":"Justin","email":"justin@aaa-global.com","oauthId":"4a1d45db-d44a-44f9-97f1-bf548437a466","isActive":true,"mfaEnabled":false},"riskJson":{"componentScore":{"cpi":12.75,"fatf":13.375,"fsi":0.8333333333333334,"fatca":2.5,"oecd":10,"industry":5,"occupation":5,"onboardingMode":5,"paymentModes":1.6666666666666667,"productComplexity":5,"screening":30},"riskRating":"LOW","riskScore":91.125,"comments":{"23":[]},"settings":{"weight":{"CORPORATE":{"cpi":15,"fsi":2.5,"fatf":15,"oecd":10,"fatca":2.5,"industry":5,"screening":30,"paymentModes":5,"primaryWeight":50,"onboardingMode":5,"ownershipLayer":5,"productComplexity":5},"INDIVIDUAL":{"cpi":15,"fsi":2.5,"fatf":15,"oecd":10,"fatca":2.5,"industry":5,"screening":30,"occupation":5,"paymentModes":5,"primaryWeight":50,"onboardingMode":5,"productComplexity":5}},"highRiskThreshold":40,"mediumRiskThreshold":70}},"isOutdated":true,"latestApprovalStatus":null,"createdAt":"2019-12-18T16:39:01.607424 08:00","updatedAt":"2019-12-18T16:39:01.692038 08:00","riskRating":"","outdated":true,"customer":27}],"officer_id": 53}';
                $.ajax({ 
                    url: "personprofile/saveRiskReport",
                    type: "POST",
                    data: risk_data,
                    //data: string_risk_report + '&officer_id=' + $('#kycScreeningIndividual-form #individual_officer_id').val(),
                    dataType: 'json',
                    success: function (response) {
                        $('#loadingmessage').hide();
                        append_risk_info_to_table();
                        $('.bootbox').modal('hide');
                        toastr.success("Information Updated", "Success");
                    }
                });
            }
        });

        updateApprovalStatus(customer_id, accessToken);
    }
    else
    {
        var acceptance_form_req = new XMLHttpRequest();
        acceptance_form_req.open("GET", 'https://a2-acumenalphaadvisory-prod-be.cynopsis.co/api/customers/'+customer_id+'/acceptance_form/', true);
        acceptance_form_req.setRequestHeader('Content-Type', 'application/json');
        acceptance_form_req.setRequestHeader('X-ARTEMIS-DOMAIN', '1');
        acceptance_form_req.setRequestHeader('Authorization', 'Bearer '+ accessToken);
        acceptance_form_req.responseType = "blob";
        acceptance_form_req.onreadystatechange = function () {
            if (acceptance_form_req.readyState === 4 && acceptance_form_req.status === 200) {
                var filename = "Customer Acceptance Form.pdf";
                if (typeof window.chrome !== 'undefined') {
                    // Chrome version
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(acceptance_form_req.response);
                    link.download = "Customer Acceptance Form.pdf";
                    link.click();
                } else if (typeof window.navigator.msSaveBlob !== 'undefined') {
                    // IE version
                    var blob = new Blob([acceptance_form_req.response], { type: 'application/pdf' });
                    window.navigator.msSaveBlob(blob, filename);
                } else {
                    // Firefox version
                    var file = new File([acceptance_form_req.response], filename, { type: 'application/force-download' });
                    window.open(URL.createObjectURL(file));
                }
            }
        };
        acceptance_form_req.send();

        var req = new XMLHttpRequest();
        req.open("GET", 'https://a2-acumenalphaadvisory-prod-be.cynopsis.co/api/customers/'+customer_id+'/pdf_report/', true);
        req.setRequestHeader('Content-Type', 'application/json');
        req.setRequestHeader('X-ARTEMIS-DOMAIN', '1');
        req.setRequestHeader('Authorization', 'Bearer '+ accessToken);
        req.responseType = "blob";
        req.onreadystatechange = function () {
            if (req.readyState === 4 && req.status === 200) {
                var filename = "Detailed Report.pdf";
                if (typeof window.chrome !== 'undefined') {
                    // Chrome version
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(req.response);
                    link.download = "Detailed Report.pdf";
                    link.click();
                } else if (typeof window.navigator.msSaveBlob !== 'undefined') {
                    // IE version
                    var blob = new Blob([req.response], { type: 'application/pdf' });
                    window.navigator.msSaveBlob(blob, filename);
                } else {
                    // Firefox version
                    var file = new File([req.response], filename, { type: 'application/force-download' });
                    window.open(URL.createObjectURL(file));
                }
            }
        };
        req.send();

        // window.open(
        //   'https://a2-acumenalphaadvisory-prod-be.cynopsis.co/api/customers/'+customer_id+'/acceptance_form/?authorization='+accessToken,
        //   '_blank' // <- This is what makes it open in a new window.
        // );

        // window.open(
        //   'https://a2-acumenalphaadvisory-prod-be.cynopsis.co/api/customers/'+customer_id+'/pdf_report/?authorization='+accessToken,
        //   '_blank' // <- This is what makes it open in a new window.
        // );
    }

    $('.bootbox').modal('hide');
}

function updateApprovalStatus(customer_id, accessToken)
{
    $.ajax({
        url: 'https://a2-acumenalphaadvisory-prod-be.cynopsis.co/client/customers/'+customer_id+'/',
        headers: {
            'Content-Type': "application/json",
            'X-ARTEMIS-DOMAIN': "1",
            'Authorization': 'Bearer '+ accessToken,
        },
        method: 'GET',
        success: function(data){
            var individual_officer_id = $('#officer_company_id').val();
            if(document.getElementById('individual_edit').checked) 
            {
                var type = "individual";
            }
            else if(document.getElementById('company_edit').checked) 
            {
                var type = "company";
            }
                
            $.ajax({ //Upload common input
                url: "personprofile/updateApprovalStatus",
                type: "POST",
                data: '&individual_officer_id=' + individual_officer_id + '&status=' + data["status"] + '&type=' + type,
                dataType: 'json',
                success: function (response) {
                    if(data["status"] != "")
                    {
                        if(document.getElementById('individual_edit').checked) 
                        {
                            $("#show_individual_status").show();
                            $("#individual_status").text(data["status"]);
                        }
                        else if(document.getElementById('company_edit').checked) 
                        {
                            $("#show_corp_status").show();
                            $("#corp_status").text(data["status"]);
                        }
                    }
                }
            });
        }
    });
}

function saveKYCInfo(accessToken, user_kyc_id)
{
    if(document.getElementById('individual_edit').checked) 
    {
        if(document.getElementById('individual_customer_active').checked) {
            var sub_individual_active = true;
        }
        else
        {
            var sub_individual_active = false;
        }
        var sub_salutation = (($("#kycScreeningIndividual-form #salutation").val() != null)?$("#kycScreeningIndividual-form #salutation").val():"");
        var sub_individual_name = $("#kycScreeningIndividual-form #individual_name").val();
        var sub_alias = (($("#kycScreeningIndividual-form #alias").val() != "")? ('"'+ $("#kycScreeningIndividual-form #alias").val()) + '"' : "");
        var sub_gender = $("#kycScreeningIndividual-form #gender").val();
        var sub_individual_country_of_residence = $("#kycScreeningIndividual-form #individual_country_of_residence").val();
        var sub_individual_nationality = $("#kycScreeningIndividual-form #individual_nationality").val();
        var sub_individual_identity_document_type = (($("#kycScreeningIndividual-form #individual_identity_document_type").val() != null)?$("#kycScreeningIndividual-form #individual_identity_document_type").val():"");
        var sub_identity_number = (($("#kycScreeningIndividual-form #identity_number").val() != null)?$("#kycScreeningIndividual-form #identity_number").val():"");
        var sub_individual_industry = $("#kycScreeningIndividual-form #individual_industry").val();
        var sub_individual_onboarding_mode = $("#kycScreeningIndividual-form #individual_onboarding_mode").val();
        var sub_individual_product_service_complexity = $("#kycScreeningIndividual-form #individual_product_service_complexity").val();
        var sub_individual_source_of_funds = (($("#kycScreeningIndividual-form #individual_source_of_funds").val() != null)?$("#kycScreeningIndividual-form #individual_source_of_funds").val():"");
        var sub_individual_country_of_birth = (($("#kycScreeningIndividual-form #individual_country_of_birth").val() != null)?$("#kycScreeningIndividual-form #individual_country_of_birth").val():"");
        var sub_individual_occupation = $("#kycScreeningIndividual-form #individual_occupation").val();
        var sub_individual_payment_mode = JSON.stringify($("#kycScreeningIndividual-form #individual_payment_mode").val());
        var sub_reference_number = (($("#kycScreeningIndividual-form #reference_number").val() != null)?$("#kycScreeningIndividual-form #reference_number").val():"");

        if($("#kycScreeningIndividual-form #individual_date_of_birth").val() != "")
        {
            var sub_individual_date_of_birth = $("#kycScreeningIndividual-form #individual_date_of_birth").val();
            var sub_new_individual_date_of_birth = '"'+ (sub_individual_date_of_birth.split("/").reverse().join("-")) + "T00:00:00.000Z" + '"';
        }
        else
        {
            var sub_new_individual_date_of_birth = null;
        }
        var sub_address_value = $("#kycScreeningIndividual-form input[name='individual_address[]']").map(function(){return $(this).val();}).get();
        var filtered_sub_address_value = sub_address_value.filter(function (el) {
                                              return el != "";
                                            });
        var string_filtered_sub_address_value = JSON.stringify(filtered_sub_address_value);

        var sub_contact_no_value = $("#kycScreeningIndividual-form input[name='individual_phone_number[]']").map(function(){return $(this).val();}).get();
        var filtered_sub_contact_no_value = sub_contact_no_value.filter(function (el) {
                                              return el != "";
                                            });
        var string_filtered_sub_contact_no_value = JSON.stringify(filtered_sub_contact_no_value);

        var sub_email_address_value = $("#kycScreeningIndividual-form input[name='individual_email_address[]']").map(function(){return $(this).val();}).get();
        var filtered_sub_email_address_value = sub_email_address_value.filter(function (el) {
                                                  return el != "";
                                                });
        var string_filtered_sub_email_address_value = JSON.stringify(filtered_sub_email_address_value);

        var sub_bank_account_value = $("#kycScreeningIndividual-form input[name='individual_bank_account[]']").map(function(){return $(this).val();}).get();
        var filtered_sub_bank_account_value = sub_bank_account_value.filter(function (el) {
                                                  return el != "";
                                                });
        var string_filtered_sub_bank_account_value = JSON.stringify(filtered_sub_bank_account_value);

        var sub_individual_nature_of_business_relationship = $("#kycScreeningIndividual-form #individual_nature_of_business_relationship").val();

        //var test = '{"individualRecords":[{"aliasNames":['+sub_salutation+'],"title":"'+sub_salutation+'","name":"'+sub_individual_name+'","nationality":"'+sub_individual_nationality+'","countryOfResidence":"'+sub_individual_country_of_residence+'","gender":"'+sub_gender+'","dateOfBirth":"'+sub_new_individual_date_of_birth+'","industry":"[2018] 01119 - GROWING OF FOOD CROPS (NON-HYDROPONICS) N.E.C.","occupation":"DRIVER","addresses":[],"phoneNumbers":[],"referenceId":"","sourceOfFunds":"LOTTERY/WINDFALL","emailAddresses":[],"bankAccounts":[],"countryOfBirth":"SINGAPORE","idType":"NATIONAL ID","idNumber":"4918405717","primary":true}],"users":["4a1d45db-d44a-44f9-97f1-bf548437a466"],"domains":[1],"paymentModes":["VIRTUAL CURRENCY"],"onboardingMode":"NON FACE-TO-FACE","productServiceComplexity":"SIMPLE","natureOfBusinessRelationship":"Nature of business relationship","referenceId":"","isActiveCustomer":true}';
        //console.log(kycIndividualData);
        //console.log(test);
        $('#loadingmessage').show();
        if($('#kycScreeningIndividual-form #customer_id').val() != "" && $('#kycScreeningIndividual-form #record_id').val() != "")
        {
            var customer_id = $('#kycScreeningIndividual-form #customer_id').val();
            var record_id   = $('#kycScreeningIndividual-form #record_id').val();

            $("#ScanForm input[name='customer_id']").val(customer_id);
            $("#ScanForm input[name='record_id']").val(record_id);
            $('#w2-screening #customer_id').val(customer_id);
            var kycIndividualData = '{"individualRecords":[{"id":'+record_id+',"aliasNames":['+sub_alias+'],"title":"'+sub_salutation+'","name":"'+sub_individual_name+'","nationality":"'+sub_individual_nationality+'","countryOfResidence":"'+sub_individual_country_of_residence+'","gender":"'+sub_gender+'","dateOfBirth":'+sub_new_individual_date_of_birth+',"industry":"'+sub_individual_industry+'","occupation":"'+sub_individual_occupation+'","addresses":'+string_filtered_sub_address_value+',"phoneNumbers":'+string_filtered_sub_contact_no_value+',"referenceId":"'+sub_reference_number+'","sourceOfFunds":"'+sub_individual_source_of_funds+'","emailAddresses":'+string_filtered_sub_email_address_value+',"bankAccounts":'+string_filtered_sub_bank_account_value+',"countryOfBirth":"'+sub_individual_country_of_birth+'","idType":"'+sub_individual_identity_document_type+'","idNumber":"'+sub_identity_number+'","primary":true}],"users":["'+user_kyc_id+'"],"domains":[1],"paymentModes":'+sub_individual_payment_mode+',"onboardingMode":"'+sub_individual_onboarding_mode+'","productServiceComplexity":"'+sub_individual_product_service_complexity+'","natureOfBusinessRelationship":"'+sub_individual_nature_of_business_relationship+'","referenceId":"'+sub_reference_number+'","isActiveCustomer":'+sub_individual_active+'}';
        
            $.ajax({
                url: 'https://a2-acumenalphaadvisory-prod-be.cynopsis.co/client/customers/'+customer_id+'/',
                headers: {
                    'Content-Type': "application/json",
                    'X-ARTEMIS-DOMAIN': "1",
                    'Authorization': 'Bearer '+ accessToken,
                },
                method: 'PATCH',
                dataType: 'json',
                data: kycIndividualData,
                success: function(data){
                    var form = $('#kycScreeningIndividual-form');
                    $.ajax({ //Upload common input
                        url: "personprofile/kycIndividualUpdate",
                        type: "POST",
                        data: form.serialize() + '&customer_id=' + customer_id + '&record_id=' + record_id + '&status=' + data["status"],
                        dataType: 'json',
                        success: function (response) {
                            if(data["status"] != "undefined")
                            {
                                $("#show_individual_status").show();
                                $("#individual_status").text(data["status"]);
                            }
                            $('#loadingmessage').hide();
                            toastr.success("Information Updated", "Success");
                        }
                    });
                }
            });
        }
        else
        {
            var kycIndividualData = '{"individualRecords":[{"aliasNames":['+sub_alias+'],"title":"'+sub_salutation+'","name":"'+sub_individual_name+'","nationality":"'+sub_individual_nationality+'","countryOfResidence":"'+sub_individual_country_of_residence+'","gender":"'+sub_gender+'","dateOfBirth":'+sub_new_individual_date_of_birth+',"industry":"'+sub_individual_industry+'","occupation":"'+sub_individual_occupation+'","addresses":'+string_filtered_sub_address_value+',"phoneNumbers":'+string_filtered_sub_contact_no_value+',"referenceId":"'+sub_reference_number+'","sourceOfFunds":"'+sub_individual_source_of_funds+'","emailAddresses":'+string_filtered_sub_email_address_value+',"bankAccounts":'+string_filtered_sub_bank_account_value+',"countryOfBirth":"'+sub_individual_country_of_birth+'","idType":"'+sub_individual_identity_document_type+'","idNumber":"'+sub_identity_number+'","primary":true}],"users":["'+user_kyc_id+'"],"domains":[1],"paymentModes":'+sub_individual_payment_mode+',"onboardingMode":"'+sub_individual_onboarding_mode+'","productServiceComplexity":"'+sub_individual_product_service_complexity+'","natureOfBusinessRelationship":"'+sub_individual_nature_of_business_relationship+'","referenceId":"'+sub_reference_number+'","isActiveCustomer":'+sub_individual_active+'}';
        
            $.ajax({
                url: 'https://a2-acumenalphaadvisory-prod-be.cynopsis.co/client/customers/',
                contentType: "application/json; charset=utf-8",
                headers: {
                    'X-ARTEMIS-DOMAIN': "1",
                    'Authorization': 'Bearer '+ accessToken,
                },
                dataType: 'json',
                method: 'POST',
                data: kycIndividualData,
                success: function(data){
                    //$('#loadingmessage').hide();
                    //console.log(data);
                    var approval_status = data["status"];
                    var customer_id = data['id'];
                    $.ajax({
                        url: 'https://a2-acumenalphaadvisory-prod-be.cynopsis.co/client/customers/'+customer_id+'/crps/',
                        contentType: "application/json; charset=utf-8",
                        headers: {
                            'X-ARTEMIS-DOMAIN': "1",
                            'Authorization': 'Bearer '+ accessToken,
                        },
                        method: 'GET',
                        success: function(data){
                            //$('#loadingmessage').hide();
                            //console.log(data);
                            var form = $('#kycScreeningIndividual-form');
                            var record_id = data['results'][0]['record'];
                            $("#ScanForm input[name='record_id']").val(record_id);
                            $("#ScanForm input[name='customer_id']").val(customer_id);
                            $('#w2-screening #customer_id').val(customer_id);
                            $.ajax({ 
                                url: "personprofile/kycIndividualUpdate",
                                type: "POST",
                                data: form.serialize() + '&customer_id=' + customer_id + '&record_id=' + record_id + '&status=' + approval_status,
                                dataType: 'json',
                                success: function (response) {
                                    $('#loadingmessage').hide();
                                    $('#kycScreeningIndividual-form #customer_id').val(customer_id);
                                    $('#kycScreeningIndividual-form #record_id').val(record_id);

                                    if(approval_status != "undefined")
                                    {
                                        $("#show_individual_status").show();
                                        $("#individual_status").text(approval_status);
                                    }
                                    toastr.success("Information Updated", "Success");
                                }
                            });
                        }
                    });
                }
            });
        }

        if($('#loginForm input[name="screening"]').val() == "true")
        {
            $("#ScanForm input[name='accessToken']").val(accessToken);
            
            bootbox
                .dialog({
                    title: 'Artemis Scan',
                    message: $('#ScanForm')
                })
                .on('shown.bs.modal', function() {
                    $('#ScanForm')
                        .show();                                 // Show the login form
                })
                .on('hide.bs.modal', function(e) {
                    // Bootbox will remove the modal (including the body which contains the login form)
                    // after hiding the modal
                    // Therefor, we need to backup the form
                    $('#ScanForm').hide().appendTo('body');
                })
                .modal('show');

            $("#start_to_scan").prop('disabled', false);
        }
    }
    else if(document.getElementById('company_edit').checked) 
    {
        if(document.getElementById('corporate_customer_active').checked) {
            var sub_corporate_active = true;
        }
        else
        {
            var sub_corporate_active = false;
        }
        if(document.getElementById('corporate_company_incorp').checked) {
            var sub_corporate_company_incorp = true;
        }
        else
        {
            var sub_corporate_company_incorp = false;
        }
        var sub_corporate_name = $("#kycScreeningCorporate-form #corporate_name").val();
        var sub_corporate_entity_type = (($("#kycScreeningCorporate-form #corporate_entity_type").val() != null)?$("#kycScreeningCorporate-form #corporate_entity_type").val():"");
        var sub_corporate_ownership_structure_layer = (($("#kycScreeningCorporate-form #corporate_ownership_structure_layer").val() != null)?$("#kycScreeningCorporate-form #corporate_ownership_structure_layer").val():"");
        var sub_corporate_country_of_incorporation = (($("#kycScreeningCorporate-form #corporate_country_of_incorporation").val() != null)?$("#kycScreeningCorporate-form #corporate_country_of_incorporation").val():"");
        var sub_corporate_country_of_major_operation = (($("#kycScreeningCorporate-form #corporate_country_of_major_operation").val() != null)?$("#kycScreeningCorporate-form #corporate_country_of_major_operation").val():"");
        var sub_corporate_primary_business_activity = (($("#kycScreeningCorporate-form #corporate_primary_business_activity").val() != null)?$("#kycScreeningCorporate-form #corporate_primary_business_activity").val():"");
        var sub_corporate_onboarding_mode = (($("#kycScreeningCorporate-form #corporate_onboarding_mode").val() != null)?$("#kycScreeningCorporate-form #corporate_onboarding_mode").val():"");
        var sub_corporate_payment_mode = JSON.stringify($("#kycScreeningCorporate-form #corporate_payment_mode").val());
        var sub_corporate_product_service_complexity = (($("#kycScreeningCorporate-form #corporate_product_service_complexity").val() != null)?$("#kycScreeningCorporate-form #corporate_product_service_complexity").val():"");
        var sub_corporate_source_of_funds = (($("#kycScreeningCorporate-form #corporate_source_of_funds").val() != null)?$("#kycScreeningCorporate-form #corporate_source_of_funds").val():"");
        var sub_reference_number = (($("#kycScreeningCorporate-form #reference_number").val() != null)?$("#kycScreeningCorporate-form #reference_number").val():"");
        var sub_incorporation_number = (($("#kycScreeningCorporate-form #incorporation_number").val() != null)?$("#kycScreeningCorporate-form #incorporation_number").val():"");

        if($("#kycScreeningCorporate-form #corporate_date_of_incorporation").val() != "")
        {
            var sub_corporate_date_of_incorporation = $("#kycScreeningCorporate-form #corporate_date_of_incorporation").val();
            var sub_new_corporate_date_of_incorporation = '"'+ (sub_corporate_date_of_incorporation.split("/").reverse().join("-")) + "T00:00:00.000Z" + '"';
        }
        else
        {
            var sub_new_corporate_date_of_incorporation = null;
        }

        var sub_address_value = $("#kycScreeningCorporate-form input[name='corporate_address[]']").map(function(){return $(this).val();}).get();
        var filtered_sub_address_value = sub_address_value.filter(function (el) {
                                              return el != "";
                                            });
        var string_filtered_sub_address_value = JSON.stringify(filtered_sub_address_value);

        var sub_contact_no_value = $("#kycScreeningCorporate-form input[name='corporate_phone_number[]']").map(function(){return $(this).val();}).get();
        var filtered_sub_contact_no_value = sub_contact_no_value.filter(function (el) {
                                              return el != "";
                                            });
        var string_filtered_sub_contact_no_value = JSON.stringify(filtered_sub_contact_no_value);

        var sub_email_address_value = $("#kycScreeningCorporate-form input[name='corporate_email_address[]']").map(function(){return $(this).val();}).get();
        var filtered_sub_email_address_value = sub_email_address_value.filter(function (el) {
                                                  return el != "";
                                                });
        var string_filtered_sub_email_address_value = JSON.stringify(filtered_sub_email_address_value);

        var sub_bank_account_value = $("#kycScreeningCorporate-form input[name='corporate_bank_account[]']").map(function(){return $(this).val();}).get();
        var filtered_sub_bank_account_value = sub_bank_account_value.filter(function (el) {
                                                  return el != "";
                                                });
        var string_filtered_sub_bank_account_value = JSON.stringify(filtered_sub_bank_account_value);

        var sub_corporate_nature_of_business_relationship = $("#kycScreeningCorporate-form #corporate_nature_of_business_relationship").val();
        
        $('#loadingmessage').show();
        if($('#kycScreeningCorporate-form #customer_id').val() != "" && $('#kycScreeningCorporate-form #record_id').val() != "")
        {
            var customer_id = $('#kycScreeningCorporate-form #customer_id').val();
            var record_id   = $('#kycScreeningCorporate-form #record_id').val();
            $("#ScanForm input[name='customer_id']").val(customer_id);
            $("#ScanForm input[name='record_id']").val(record_id);
            
            var kycCorporateData = '{"corporateRecords": [{"id":'+record_id+',"isIncorporated": '+sub_corporate_company_incorp+',"name": "'+sub_corporate_name+'","entityType": "'+sub_corporate_entity_type+'","ownershipStructureLayers": "'+sub_corporate_ownership_structure_layer+'","countryOfIncorporation": "'+sub_corporate_country_of_incorporation+'","countryOfOperations": "'+sub_corporate_country_of_major_operation+'","businessActivity": "'+sub_corporate_primary_business_activity+'","addresses": '+string_filtered_sub_address_value+',"phoneNumbers": '+string_filtered_sub_contact_no_value+',"sourceOfFunds": "'+sub_corporate_source_of_funds+'","emailAddresses": '+string_filtered_sub_email_address_value+',"bankAccounts": '+string_filtered_sub_bank_account_value+',"incorporationNumber": "'+sub_incorporation_number+'","incorporationDate": '+sub_new_corporate_date_of_incorporation+',"primary": true}],"isActiveCustomer": '+sub_corporate_active+',"referenceId": "'+sub_reference_number+'","paymentModes": '+sub_corporate_payment_mode+',"onboardingMode": "'+sub_corporate_onboarding_mode+'","productServiceComplexity": "'+sub_corporate_product_service_complexity+'","natureOfBusinessRelationship": "'+sub_corporate_nature_of_business_relationship+'","users": ["'+user_kyc_id+'"],"domains": [1]}';
        
            $.ajax({
                url: 'https://a2-acumenalphaadvisory-prod-be.cynopsis.co/client/customers/'+customer_id+'/',
                headers: {
                    'Content-Type': "application/json",
                    'X-ARTEMIS-DOMAIN': "1",
                    'Authorization': 'Bearer '+ accessToken,
                },
                method: 'PATCH',
                dataType: 'json',
                data: kycCorporateData,
                success: function(data){
                    var form = $('#kycScreeningCorporate-form');
                    $.ajax({ //Upload common input
                        url: "personprofile/kycCorporateUpdate",
                        type: "POST",
                        data: form.serialize() + '&customer_id=' + customer_id + '&record_id=' + record_id + '&status=' + data["status"],
                        dataType: 'json',
                        success: function (response) {
                            if(data["status"] != "undefined")
                            {
                                if(document.getElementById('individual_edit').checked) 
                                {
                                    $("#show_individual_status").show();
                                    $("#individual_status").text(data["status"]);
                                }
                                else if(document.getElementById('company_edit').checked) 
                                {
                                    $("#show_corp_status").show();
                                    $("#corp_status").text(data["status"]);
                                }
                            }
                            $('#loadingmessage').hide();
                            toastr.success("Information Updated", "Success");
                        }
                    });
                }
            });
        }
        else
        {
            var kycCorporateData = '{"corporateRecords": [{"isIncorporated": '+sub_corporate_company_incorp+',"name": "'+sub_corporate_name+'","entityType": "'+sub_corporate_entity_type+'","ownershipStructureLayers": "'+sub_corporate_ownership_structure_layer+'","countryOfIncorporation": "'+sub_corporate_country_of_incorporation+'","countryOfOperations": "'+sub_corporate_country_of_major_operation+'","businessActivity": "'+sub_corporate_primary_business_activity+'","addresses": '+string_filtered_sub_address_value+',"phoneNumbers": '+string_filtered_sub_contact_no_value+',"sourceOfFunds": "'+sub_corporate_source_of_funds+'","emailAddresses": '+string_filtered_sub_email_address_value+',"bankAccounts": '+string_filtered_sub_bank_account_value+',"incorporationNumber": "'+sub_incorporation_number+'","incorporationDate": '+sub_new_corporate_date_of_incorporation+',"primary": true}],"isActiveCustomer": '+sub_corporate_active+',"referenceId": "'+sub_reference_number+'","paymentModes": '+sub_corporate_payment_mode+',"onboardingMode": "'+sub_corporate_onboarding_mode+'","productServiceComplexity": "'+sub_corporate_product_service_complexity+'","natureOfBusinessRelationship": "'+sub_corporate_nature_of_business_relationship+'","users": ["'+user_kyc_id+'"],"domains": [1]}';
        
            $.ajax({
                url: 'https://a2-acumenalphaadvisory-prod-be.cynopsis.co/client/customers/', //1. Create Corporate Customer
                contentType: "application/json; charset=utf-8",
                headers: {
                    'X-ARTEMIS-DOMAIN': "1",
                    'Authorization': 'Bearer '+ accessToken,
                },
                dataType: 'json',
                method: 'POST',
                data: kycCorporateData,
                success: function(data){
                    var approval_status = data["status"];
                    var customer_id = data['id'];
                    $.ajax({
                        url: 'https://a2-acumenalphaadvisory-prod-be.cynopsis.co/client/customers/'+customer_id+'/crps/',
                        contentType: "application/json; charset=utf-8",
                        headers: {
                            'X-ARTEMIS-DOMAIN': "1",
                            'Authorization': 'Bearer '+ accessToken,
                        },
                        method: 'GET',
                        success: function(data){
                            //$('#loadingmessage').hide();
                            //console.log(data);
                            var form = $('#kycScreeningCorporate-form');
                            var record_id = data['results'][0]['record'];
                            $("#ScanForm input[name='record_id']").val(record_id);
                            $("#ScanForm input[name='customer_id']").val(customer_id);
                            $.ajax({ 
                                url: "personprofile/kycCorporateUpdate",
                                type: "POST",
                                data: form.serialize() + '&customer_id=' + customer_id + '&record_id=' + record_id + '&status=' + approval_status,
                                dataType: 'json',
                                success: function (response) {
                                    $('#loadingmessage').hide();
                                    $('#kycScreeningCorporate-form #customer_id').val(customer_id);
                                    $('#kycScreeningCorporate-form #record_id').val(record_id);

                                    if(approval_status != "undefined")
                                    {
                                        if(document.getElementById('individual_edit').checked) 
                                        {
                                            $("#show_individual_status").show();
                                            $("#individual_status").text(approval_status);
                                        }
                                        else if(document.getElementById('company_edit').checked) 
                                        {
                                            $("#show_corp_status").show();
                                            $("#corp_status").text(approval_status);
                                        }
                                    }
                                    toastr.success("Information Updated", "Success");
                                }
                            });
                        }
                    });
                }
            });
        }

        if($('#loginForm input[name="screening"]').val() == "true")
        {
            $("#ScanForm input[name='accessToken']").val(accessToken);
            
            bootbox
                .dialog({
                    title: 'Artemis Scan',
                    message: $('#ScanForm')
                })
                .on('shown.bs.modal', function() {
                    $('#ScanForm')
                        .show();                                 // Show the login form
                })
                .on('hide.bs.modal', function(e) {
                    // Bootbox will remove the modal (including the body which contains the login form)
                    // after hiding the modal
                    // Therefor, we need to backup the form
                    $('#ScanForm').hide().appendTo('body');
                })
                .modal('show');
                $("#start_to_scan").prop('disabled', false);
        }
    }
}

$(document).on('click',"#start_to_scan",function(e){
    var $link = $(e.target);
    e.preventDefault();
    if(!$link.data('lockedAt') || +new Date() - $link.data('lockedAt') > 1000) {
        console.log('clicked');
        var scan_list = $('input[name="checkbox_scan[]"]:checked').map(function(_, el) {
            return $(el).val();
        }).get();

        var accessToken = $("#ScanForm input[name='accessToken']").val();
        var customer_id = $("#ScanForm input[name='customer_id']").val();
        var record_id = $("#ScanForm input[name='record_id']").val();

        if(scan_list.includes("WORLD-CHECK"))
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
            artemisCheck(scan_list, accessToken, customer_id, record_id);
            $("#start_to_scan").prop('disabled', true);
        }
    }
    $link.data('lockedAt', +new Date());
});

function artemisCheck(scan_list, accessToken, customer_id, record_id)
{
    var artemiScanSearchFinishValue;
    $('#loadingmessage').show();
    for(var i = 0; i < scan_list.length; i++)
    {
        if(scan_list[i] == "WORLD-CHECK")
        {
            $.ajax({
                url: 'https://a2-acumenalphaadvisory-prod-be.cynopsis.co/client/records/'+record_id+'/searches/',
                headers: {
                    'Content-Type': "application/json",
                    'X-ARTEMIS-DOMAIN': "1",
                    'Authorization': 'Bearer '+ accessToken,
                },
                dataType: 'json',
                method: 'POST',
                //async: false,
                data: '{"engine_type": "TR"}',
                success: function(data){
                    //console.log(data);
                },
                fail: function(xhr, textStatus, errorThrown){
                    toastr.error("WORLD-CHECK request failed", "Error");
                }
            });

        }
        else if(scan_list[i] == "INTERNET SEARCH")
        {
            $.ajax({
                url: 'https://a2-acumenalphaadvisory-prod-be.cynopsis.co/client/records/'+record_id+'/internet_searches/',
                headers: {
                    'Content-Type': "application/json",
                    'X-ARTEMIS-DOMAIN': "1",
                    'Authorization': 'Bearer '+ accessToken,
                },
                dataType: 'json',
                method: 'POST',
                //async: false,
                success: function(data){
                    //console.log(data);
                },
                fail: function(xhr, textStatus, errorThrown){
                    toastr.error("INTERNET SEARCH request failed", "Error");
                }
            });
        }
        else if(scan_list[i] == "OWN RESTRICTED LIST")
        {
            $.ajax({
                url: 'https://a2-acumenalphaadvisory-prod-be.cynopsis.co/client/records/'+record_id+'/own_name_searches/',
                headers: {
                    'Content-Type': "application/json",
                    'X-ARTEMIS-DOMAIN': "1",
                    'Authorization': 'Bearer '+ accessToken,
                },
                dataType: 'json',
                method: 'POST',
                //async: false,
                success: function(data){
                    //console.log(data);
                },
                fail: function(xhr, textStatus, errorThrown){
                    toastr.error("OWN RESTRICTED LIST request failed", "Error");
                }
            });
        }
        else if(scan_list[i] == "ARTEMISCAN")
        {
            $.ajax({
                url: 'https://a2-acumenalphaadvisory-prod-be.cynopsis.co/client/records/'+record_id+'/searches/',
                headers: {
                    'Content-Type': "application/json",
                    'X-ARTEMIS-DOMAIN': "1",
                    'Authorization': 'Bearer '+ accessToken,
                },
                dataType: 'json',
                method: 'POST',
                //async: false,
                data: '{"engine_type": "ArtemiScan"}',
                success: function(data){
                    //console.log(data);
                    //callArtemiScanSearchFinishAgain(record_id, accessToken);
                },
                fail: function(xhr, textStatus, errorThrown){
                    toastr.error("ARTEMISCAN request failed", "Error");
                }
            });
        }
    }

    setTimeout(function(){ startCheckTheSearch(customer_id, record_id, accessToken) }, 15000);

    $('input[id="checkbox_scan1"]').prop("checked", false);
}

function startCheckTheSearch(customer_id, record_id, accessToken) {
    $.when( callTRSearchFinishAgain(record_id, accessToken), callArtemiScanSearchFinishAgain(record_id, accessToken) ).done(function() {
        $.ajax({
            url: 'https://a2-acumenalphaadvisory-prod-be.cynopsis.co/client/customers/'+customer_id+'/risk_reports/',
            headers: {
                'Content-Type': "application/json",
                'X-ARTEMIS-DOMAIN': "1",
                'Authorization': 'Bearer '+ accessToken,
            },
            dataType: 'json',
            method: 'POST',
            //async: false,
            success: function(data){
                //console.log(data);

                $.ajax({
                    url: 'https://a2-acumenalphaadvisory-prod-be.cynopsis.co/client/customers/'+customer_id+'/risk_reports/?limit=999',
                    headers: {
                        'Content-Type': "application/json",
                        'X-ARTEMIS-DOMAIN': "1",
                        'Authorization': 'Bearer '+ accessToken,
                    },
                    method: 'GET',
                    success: function(data){
                        //console.log(data);
                        var string_risk_report = JSON.stringify(data['results']);

                        if(document.getElementById('individual_edit').checked) 
                        {
                            var risk_data = JSON.parse('{"officer_id": '+$('#kycScreeningIndividual-form #individual_officer_id').val()+', "riskInfo" : '+string_risk_report+'}');
                        }
                        else if(document.getElementById('company_edit').checked) 
                        {
                            var risk_data = JSON.parse('{"corporate_officer_id": '+$('#kycScreeningCorporate-form #corporate_officer_id').val()+', "riskInfo" : '+string_risk_report+'}');
                        }
                        $.ajax({ 
                            url: "personprofile/saveRiskReport",
                            type: "POST",
                            data: risk_data,
                            //data: string_risk_report + '&officer_id=' + $('#kycScreeningIndividual-form #individual_officer_id').val(),
                            dataType: 'json',
                            success: function (response) {
                                $('#loadingmessage').hide();
                                $('.bootbox').modal('hide');
                                toastr.success("Information Updated", "Success");
                                updateApprovalStatus(customer_id, accessToken);
                            }
                        });
                    }
                })
            }
        });
    });
}

var callTRSearchFinishAgain = function(record_id, accessToken){
    $.ajax({
        url: 'https://a2-acumenalphaadvisory-prod-be.cynopsis.co/client/records/'+record_id+'/searches/?engine_type=TR&limit=20',
        headers: {
            'Content-Type': "application/json",
            'X-ARTEMIS-DOMAIN': "1",
            'Authorization': 'Bearer '+ accessToken,
        },
        method: 'GET',
        async: false,
        success: function(data){
            //console.log(data['results'][0]["isSearchFinished"]);
            if(data['results'].length > 0)
            {
                if(data['results'][0]["isSearchFinished"] == false)
                {
                    callTRSearchFinishAgain(record_id, accessToken);
                }
                else
                {
                    return true;
                }
            }
            else
            {
                return true;
            }
        }
    });
};

var callArtemiScanSearchFinishAgain = function(record_id, accessToken){
    $.ajax({
        url: 'https://a2-acumenalphaadvisory-prod-be.cynopsis.co/client/records/'+record_id+'/searches/?engine_type=ArtemiScan&limit=20',
        headers: {
            'Content-Type': "application/json",
            'X-ARTEMIS-DOMAIN': "1",
            'Authorization': 'Bearer '+ accessToken,
        },
        method: 'GET',
        //timeout: 5000, // sets timeout to 3 seconds
        async: false,
        success: function(data){
            //console.log(data['results'][0]["isSearchFinished"]);
            if(data['results'].length > 0)
            {
                if(data['results'][0]["isSearchFinished"] == false)
                {
                    callArtemiScanSearchFinishAgain(record_id, accessToken);
                }
                else
                {
                    return true;
                }
            }
            else
            {
                return true;
            }
        }
    });
};

//----------------------------individual_address-----------------------------------------------------
$('.show_individual_address').click(function(e){
    e.preventDefault();
    $(this).parent().parent().find(".local_individual_address_toggle").toggle();
    //console.log($(this).parent().parent().find(".local_individual_address_toggle"));
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
function individual_address(officer_individual_address)
{
    if(officer_individual_address != null)
    {
        for (var h = 0; h < officer_individual_address.length; h++) 
        {
            var officerindividual_addressArray = officer_individual_address; //officer_individual_address[h].split(',');

            if(h == 0)
            {
                $(".fieldGroup_individual_address").find('.main_individual_address').attr("value", officerindividual_addressArray[0]);
                $(".fieldGroup_individual_address").find('.main_individual_address_primary').attr("value", officerindividual_addressArray[0]);

                $(".fieldGroup_individual_address").find(".button_increment_individual_address").css({"visibility": "visible"});
            }
            else
            {
                $(".fieldGroupCopy_individual_address").find('.second_individual_address').attr("value", officerindividual_addressArray[0]);

                $(".fieldGroupCopy_individual_address").find('.individual_address_primary').attr("value", officerindividual_addressArray[0]);

                var fieldHTML = '<div class="input-group fieldGroup_individual_address" style="margin-top:10px; display: block;">'+$(".fieldGroupCopy_individual_address").html()+'</div>';

                //$('body').find('.fieldGroup_individual_address:first').after(fieldHTML);
                $( fieldHTML).prependTo(".local_individual_address_toggle");

                $(".fieldGroupCopy_individual_address").find('.second_individual_address').attr("value", "");
                $(".fieldGroupCopy_individual_address").find('.individual_address_primary').attr("value", "");

                $(".show_individual_address").css({"visibility": "visible"});
                $(".local_individual_address_toggle").hide();
            }
        }
    }
}

//put to hidden and radio button value when finish typing
$(document).on('blur', '.check_empty_individual_address', function(){

    $(this).parent().find(".individual_address_primary").attr("value", $(this).val());
});


$(document).ready(function() {
    $(document).on('click', '.individual_address_primary', function(event){  
        event.preventDefault();
        var individual_address_primary_radio_button = $(this);
        bootbox.confirm("Are you comfirm set as primary for this address?", function (result) {
            if (result) {
                individual_address_primary_radio_button.prop( "checked", true );
            }
        });
    });

    //individual_individual_address
    $(".addMore_individual_address").click(function(){

        $(".local_individual_address_toggle").show();
        $(".show_individual_address").find(".fa").addClass("fa-arrow-up").removeClass("fa-arrow-down");
        $(".show_individual_address").find(".toggle_word").text('Show less');

        $(".fieldGroupCopy_individual_address").find('.second_individual_address').attr("value", $(".main_individual_address").val());

        $(".fieldGroupCopy_individual_address").find('.individual_address_primary').attr("value", $(".main_individual_address").val());

        var fieldHTML = '<div class="input-group fieldGroup_individual_address" style="margin-top:10px; display: block;">'+$(".fieldGroupCopy_individual_address").html()+'</div>';

        $( fieldHTML).prependTo(".local_individual_address_toggle");

        if ($(".main_individual_address_primary").is(":checked")) 
        {
            $(".local_individual_address_toggle .fieldGroup_individual_address").eq(0).find('.individual_address_primary').prop( "checked", true );
        }

        $(".button_increment_individual_address").css({"visibility": "hidden"});

        if ($(".local_individual_address_toggle").find(".second_individual_address").length > 0) 
        {
            $(".show_individual_address").css({"visibility": "visible"});

        }
        else {
            $(".show_individual_address").css({"visibility": "hidden"});
            
        }
       
        $(".main_individual_address").val("");
        $(".main_individual_address").parent().find(".individual_address_primary").val("");
        $(".fieldGroupCopy_individual_address").find('.second_individual_address').attr("value", "");
        $(".fieldGroupCopy_individual_address").find('.individual_address_primary').attr("value", "");

    });

    $("body").on("click",".remove_individual_address",function(){ 
        var remove_individual_address = $(this);
        bootbox.confirm("Are you comfirm delete this address?", function (result) {
            if (result) {

                remove_individual_address.parents(".fieldGroup_individual_address").remove();

                if (remove_individual_address.parent().find(".individual_address_primary").is(":checked")) 
                {
                    if ($(".local_individual_address_toggle").find(".second_individual_address").length > 0) 
                    {
                        $(".local_individual_address_toggle .fieldGroup_individual_address").eq(0).find('.individual_address_primary').prop( "checked", true );
                    }
                    else
                    {
                        $(".main_individual_address_primary").prop( "checked", true );
                    }
                }

                if ($(".local_individual_address_toggle").find(".second_individual_address").length > 0) 
                {
                    $(".show_individual_address").css({"visibility": "visible"});

                }
                else {
                    $(".show_individual_address").css({"visibility": "hidden"});
                    
                }
                
                $( '#form_individual_address' ).html("");
            }
        });
    });

    $('.main_individual_address').keyup(function(){

        if ($(this).val()) {
            $(".button_increment_individual_address").css({"visibility": "visible"});

        }
        else {
            $(".button_increment_individual_address").css({"visibility": "hidden"});
        }
    });
    if ($(".local_individual_address_toggle").find(".second_individual_address").length > 0) 
    {
        $(".show_individual_address").css({"visibility": "visible"});
        $(".local_individual_address_toggle").hide();

    }
    else {
        $(".show_individual_address").css({"visibility": "hidden"});
        $(".local_individual_address_toggle").hide();
    }
});
//--------------------------------------end_individual_address---------------------------------------

//--------------------------------------individual_phone_number---------------------------------------
$('.show_individual_phone_number').click(function(e){
    e.preventDefault();
    $(this).parent().parent().find(".individual_phone_number_toggle").toggle();
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

$('.fieldGroup_individual_phone_number .hp').intlTelInput({
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
function individual_mobile_no(officer_mobile_no)
{
    if(officer_mobile_no != null)
    {
        for (var h = 0; h < officer_mobile_no.length; h++) 
        {
            var officerMobileNoArray = officer_mobile_no[h].split(',');

            if(h == 0)
            {
                $(".fieldGroup_individual_phone_number").find('.main_individual_phone_number').intlTelInput("setNumber", officerMobileNoArray[0]);
                $(".fieldGroup_individual_phone_number").find('.main_hidden_individual_phone_number').attr("value", officerMobileNoArray[0]);
                $(".fieldGroup_individual_phone_number").find('.main_individual_phone_number_primary').attr("value", officerMobileNoArray[0]);
                $(".fieldGroup_individual_phone_number").find(".button_increment_individual_phone_number").css({"visibility": "visible"});
            }
            else
            {
                
                $(".fieldGroupCopy_individual_phone_number").find('.hidden_individual_phone_number').attr("value", officerMobileNoArray[0]);
                $(".fieldGroupCopy_individual_phone_number").find('.individual_phone_number_primary').attr("value", officerMobileNoArray[0]);

                var fieldHTML = '<div class="input-group fieldGroup_individual_phone_number" style="margin-top:10px; width: 100%;">'+$(".fieldGroupCopy_individual_phone_number").html()+'</div>';

                //$('body').find('.fieldGroup_individual_phone_number:first').after(fieldHTML);
                $( fieldHTML).prependTo(".individual_phone_number_toggle");

                $('.individual_phone_number_toggle .fieldGroup_individual_phone_number').eq(0).find('.second_hp').intlTelInput({
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

                $('.individual_phone_number_toggle .fieldGroup_individual_phone_number').eq(0).find('.second_hp').intlTelInput("setNumber", officerMobileNoArray[0]);

                $('.individual_phone_number_toggle .fieldGroup_individual_phone_number').eq(0).find('.second_hp').on({
                  keydown: function(e) {
                    if (e.which === 32)
                      return false;
                  },
                  change: function() {
                    this.value = this.value.replace(/\s/g, "");
                  }
                });

                $(".fieldGroupCopy_individual_phone_number").find('.hidden_individual_phone_number').attr("value", "");
                $(".fieldGroupCopy_individual_phone_number").find('.individual_phone_number_primary').attr("value", "");

                $(".show_individual_phone_number").css({"visibility": "visible"});
                $(".individual_phone_number_toggle").hide();
            }
        }
    }
}

//put to hidden and radio button value when finish typing
$(document).on('blur', '.check_empty_individual_phone_number', function(){
    //console.log($(this).val());
    $(this).parent().parent().find(".hidden_individual_phone_number").attr("value", $(this).intlTelInput("getNumber", intlTelInputUtils.numberFormat.E164));
    $(this).parent().parent().find(".individual_phone_number_primary").attr("value", $(this).intlTelInput("getNumber", intlTelInputUtils.numberFormat.E164));
});

$(document).ready(function() {
    $(document).on('click', '.individual_phone_number_primary', function(event){
        event.preventDefault();
        var individual_phone_number_primary_radio_button = $(this);
        bootbox.confirm("Are you comfirm set as primary for this Mobile No?", function (result) {
            if (result) {
                individual_phone_number_primary_radio_button.prop( "checked", true );
            }
        });
        
    });

    $(".check_empty_individual_phone_number").on({
      keydown: function(e) {
        if (e.which === 32)
          return false;
      },
      change: function() {
        this.value = this.value.replace(/\s/g, "");
      }
    });

    $(".addMore_individual_phone_number").click(function(){
        var number = $(".main_individual_phone_number").intlTelInput("getNumber", intlTelInputUtils.numberFormat.E164);

        var countryData = $(".main_individual_phone_number").intlTelInput("getSelectedCountryData");

        $(".individual_phone_number_toggle").show();
        $(".show_individual_phone_number").find(".fa").addClass("fa-arrow-up").removeClass("fa-arrow-down");
        $(".show_individual_phone_number").find(".toggle_word").text('Show less');

        $(".fieldGroupCopy_individual_phone_number").find('.second_individual_phone_number').attr("value", $(".main_individual_phone_number").val());
        $(".fieldGroupCopy_individual_phone_number").find('.hidden_individual_phone_number').attr("value", number);
        $(".fieldGroupCopy_individual_phone_number").find('.individual_phone_number_primary').attr("value", number);

        var fieldHTML = '<div class="input-group fieldGroup_individual_phone_number" style="margin-top:10px; width: 100%;">'+$(".fieldGroupCopy_individual_phone_number").html()+'</div>';

        $( fieldHTML).prependTo(".individual_phone_number_toggle");

        $('.individual_phone_number_toggle .fieldGroup_individual_phone_number').eq(0).find('.second_hp').intlTelInput({
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

        $('.individual_phone_number_toggle .fieldGroup_individual_phone_number').eq(0).find('.second_hp').on({
          keydown: function(e) {
            if (e.which === 32)
              return false;
          },
          change: function() {
            this.value = this.value.replace(/\s/g, "");
          }
        });

        if ($(".main_individual_phone_number_primary").is(":checked")) 
        {
            $('.individual_phone_number_toggle .fieldGroup_individual_phone_number').eq(0).find('.individual_phone_number_primary').prop( "checked", true );
        }

        $(".button_increment_individual_phone_number").css({"visibility": "hidden"});

        if ($(".individual_phone_number_toggle").find(".second_individual_phone_number").length > 0) 
        {
            $(".show_individual_phone_number").css({"visibility": "visible"});

        }
        else {
            $(".show_individual_phone_number").css({"visibility": "hidden"});
            
        }

        $(".main_individual_phone_number").val("");
        $(".main_individual_phone_number").parent().parent().find(".hidden_individual_phone_number").val("");
        $(".main_individual_phone_number").parent().parent().find(".individual_phone_number_primary").val("");
        $(".fieldGroupCopy_individual_phone_number").find('.second_individual_phone_number').attr("value", "");
        $(".fieldGroupCopy_individual_phone_number").find('.hidden_individual_phone_number').attr("value", "");
        $(".fieldGroupCopy_individual_phone_number").find('.individual_phone_number_primary').attr("value", "");
        $(".fieldGroupCopy_individual_phone_number").find('.individual_phone_number_primary').prop( "checked", false );

    });

    $("body").on("click",".remove_individual_phone_number",function(){ 
        var remove_individual_phone_number_button = $(this);
        bootbox.confirm("Are you comfirm delete this Mobile No?", function (result) {
            if (result) {
                remove_individual_phone_number_button.parents(".fieldGroup_individual_phone_number").remove();

                if (remove_individual_phone_number_button.parent().find(".individual_phone_number_primary").is(":checked")) 
                {
                    if ($(".individual_phone_number_toggle").find(".second_individual_phone_number").length > 0) 
                    {
                        $('.individual_phone_number_toggle .fieldGroup_individual_phone_number').eq(0).find('.individual_phone_number_primary').prop( "checked", true );
                    }
                    else
                    {
                        $(".main_individual_phone_number_primary").prop( "checked", true );
                    }
                }

                if ($(".individual_phone_number_toggle").find(".second_individual_phone_number").length > 0) 
                {
                    $(".show_individual_phone_number").css({"visibility": "visible"});

                }
                else {
                    $(".show_individual_phone_number").css({"visibility": "hidden"});
                    
                }
                $( '#form_individual_phone_number' ).html("");
            }
        });
    });

    $('.main_individual_phone_number').keyup(function(){

        if ($(this).val()) {
            $(".button_increment_individual_phone_number").css({"visibility": "visible"});

        }
        else {
            $(".button_increment_individual_phone_number").css({"visibility": "hidden"});
        }
    });

    if ($(".individual_phone_number_toggle").find(".second_individual_phone_number").length > 0) 
    {
        $(".show_individual_phone_number").css({"visibility": "visible"});
        $(".individual_phone_number_toggle").hide();

    }
    else {
        $(".show_individual_phone_number").css({"visibility": "hidden"});
        $(".individual_phone_number_toggle").hide();
    }
});

//--------------------------------------end_individual_phone_number---------------------------------------

//----------------------------individual_email_address-----------------------------------------------------
$('.show_individual_email_address').click(function(e){
    e.preventDefault();
    $(this).parent().parent().find(".local_individual_email_address_toggle").toggle();
    //console.log($(this).parent().parent().find(".local_individual_email_address_toggle"));
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
function individual_email_address(officer_individual_email_address)
{
    if(officer_individual_email_address != null)
    {
        for (var h = 0; h < officer_individual_email_address.length; h++) 
        {
            var officerindividual_email_addressArray = officer_individual_email_address[h].split(',');

            if(h == 0)
            {
                $(".fieldGroup_individual_email_address").find('.main_individual_email_address').attr("value", officerindividual_email_addressArray[0]);
                $(".fieldGroup_individual_email_address").find('.main_individual_email_address_primary').attr("value", officerindividual_email_addressArray[0]);

                $(".fieldGroup_individual_email_address").find(".button_increment_individual_email_address").css({"visibility": "visible"});
            }
            else
            {
                $(".fieldGroupCopy_individual_email_address").find('.second_individual_email_address').attr("value", officerindividual_email_addressArray[0]);

                $(".fieldGroupCopy_individual_email_address").find('.individual_email_address_primary').attr("value", officerindividual_email_addressArray[0]);

                var fieldHTML = '<div class="input-group fieldGroup_individual_email_address" style="margin-top:10px; display: block;">'+$(".fieldGroupCopy_individual_email_address").html()+'</div>';

                //$('body').find('.fieldGroup_individual_email_address:first').after(fieldHTML);
                $( fieldHTML).prependTo(".local_individual_email_address_toggle");

                $(".fieldGroupCopy_individual_email_address").find('.second_individual_email_address').attr("value", "");
                $(".fieldGroupCopy_individual_email_address").find('.individual_email_address_primary').attr("value", "");

                $(".show_individual_email_address").css({"visibility": "visible"});
                $(".local_individual_email_address_toggle").hide();
            }
        }
    }
}

//put to hidden and radio button value when finish typing
$(document).on('blur', '.check_empty_individual_email_address', function(){

    $(this).parent().find(".individual_email_address_primary").attr("value", $(this).val());
});


$(document).ready(function() {
    $(document).on('click', '.individual_email_address_primary', function(event){  
        event.preventDefault();
        var individual_email_address_primary_radio_button = $(this);
        bootbox.confirm("Are you comfirm set as primary for this email address?", function (result) {
            if (result) {
                individual_email_address_primary_radio_button.prop( "checked", true );
            }
        });
    });

    //individual_individual_email_address
    $(".addMore_individual_email_address").click(function(){

        $(".local_individual_email_address_toggle").show();
        $(".show_individual_email_address").find(".fa").addClass("fa-arrow-up").removeClass("fa-arrow-down");
        $(".show_individual_email_address").find(".toggle_word").text('Show less');

        $(".fieldGroupCopy_individual_email_address").find('.second_individual_email_address').attr("value", $(".main_individual_email_address").val());

        $(".fieldGroupCopy_individual_email_address").find('.individual_email_address_primary').attr("value", $(".main_individual_email_address").val());

        var fieldHTML = '<div class="input-group fieldGroup_individual_email_address" style="margin-top:10px; display: block;">'+$(".fieldGroupCopy_individual_email_address").html()+'</div>';

        $( fieldHTML).prependTo(".local_individual_email_address_toggle");

        if ($(".main_individual_email_address_primary").is(":checked")) 
        {
            $(".local_individual_email_address_toggle .fieldGroup_individual_email_address").eq(0).find('.individual_email_address_primary').prop( "checked", true );
        }

        $(".button_increment_individual_email_address").css({"visibility": "hidden"});

        if ($(".local_individual_email_address_toggle").find(".second_individual_email_address").length > 0) 
        {
            $(".show_individual_email_address").css({"visibility": "visible"});

        }
        else {
            $(".show_individual_email_address").css({"visibility": "hidden"});
            
        }
       
        $(".main_individual_email_address").val("");
        $(".main_individual_email_address").parent().find(".individual_email_address_primary").val("");
        $(".fieldGroupCopy_individual_email_address").find('.second_individual_email_address').attr("value", "");
        $(".fieldGroupCopy_individual_email_address").find('.individual_email_address_primary').attr("value", "");

    });

    $("body").on("click",".remove_individual_email_address",function(){ 
        var remove_individual_email_address = $(this);
        bootbox.confirm("Are you comfirm delete this address?", function (result) {
            if (result) {

                remove_individual_email_address.parents(".fieldGroup_individual_email_address").remove();

                if (remove_individual_email_address.parent().find(".individual_email_address_primary").is(":checked")) 
                {
                    if ($(".local_individual_email_address_toggle").find(".second_individual_email_address").length > 0) 
                    {
                        $(".local_individual_email_address_toggle .fieldGroup_individual_email_address").eq(0).find('.individual_email_address_primary').prop( "checked", true );
                    }
                    else
                    {
                        $(".main_individual_email_address_primary").prop( "checked", true );
                    }
                }

                if ($(".local_individual_email_address_toggle").find(".second_individual_email_address").length > 0) 
                {
                    $(".show_individual_email_address").css({"visibility": "visible"});

                }
                else {
                    $(".show_individual_email_address").css({"visibility": "hidden"});
                    
                }
                
                $( '#form_individual_email_address' ).html("");
            }
        });
    });

    $('.main_individual_email_address').keyup(function(){

        if ($(this).val()) {
            $(".button_increment_individual_email_address").css({"visibility": "visible"});

        }
        else {
            $(".button_increment_individual_email_address").css({"visibility": "hidden"});
        }
    });
    if ($(".local_individual_email_address_toggle").find(".second_individual_email_address").length > 0) 
    {
        $(".show_individual_email_address").css({"visibility": "visible"});
        $(".local_individual_email_address_toggle").hide();

    }
    else {
        $(".show_individual_email_address").css({"visibility": "hidden"});
        $(".local_individual_email_address_toggle").hide();
    }
});
//--------------------------------------end_individual_email_address---------------------------------------

//----------------------------individual_bank_account-----------------------------------------------------
$('.show_individual_bank_account').click(function(e){
    e.preventDefault();
    $(this).parent().parent().find(".local_individual_bank_account_toggle").toggle();
    //console.log($(this).parent().parent().find(".local_individual_bank_account_toggle"));
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
function individual_bank_account(officer_individual_bank_account)
{
    if(officer_individual_bank_account != null)
    {
        for (var h = 0; h < officer_individual_bank_account.length; h++) 
        {
            var officerindividual_bank_accountArray = officer_individual_bank_account[h].split(',');

            if(h == 0)
            {
                $(".fieldGroup_individual_bank_account").find('.main_individual_bank_account').attr("value", officerindividual_bank_accountArray[0]);
                $(".fieldGroup_individual_bank_account").find('.main_individual_bank_account_primary').attr("value", officerindividual_bank_accountArray[0]);

                $(".fieldGroup_individual_bank_account").find(".button_increment_individual_bank_account").css({"visibility": "visible"});
            }
            else
            {
                $(".fieldGroupCopy_individual_bank_account").find('.second_individual_bank_account').attr("value", officerindividual_bank_accountArray[0]);

                $(".fieldGroupCopy_individual_bank_account").find('.individual_bank_account_primary').attr("value", officerindividual_bank_accountArray[0]);

                var fieldHTML = '<div class="input-group fieldGroup_individual_bank_account" style="margin-top:10px; display: block;">'+$(".fieldGroupCopy_individual_bank_account").html()+'</div>';

                //$('body').find('.fieldGroup_individual_bank_account:first').after(fieldHTML);
                $( fieldHTML).prependTo(".local_individual_bank_account_toggle");

                $(".fieldGroupCopy_individual_bank_account").find('.second_individual_bank_account').attr("value", "");
                $(".fieldGroupCopy_individual_bank_account").find('.individual_bank_account_primary').attr("value", "");
                
                $(".show_individual_bank_account").css({"visibility": "visible"});
                $(".local_individual_bank_account_toggle").hide();
            }
        }
    }
}

//put to hidden and radio button value when finish typing
$(document).on('blur', '.check_empty_individual_bank_account', function(){

    $(this).parent().find(".individual_bank_account_primary").attr("value", $(this).val());
});


$(document).ready(function() {
    $(document).on('click', '.individual_bank_account_primary', function(event){  
        event.preventDefault();
        var individual_bank_account_primary_radio_button = $(this);
        bootbox.confirm("Are you comfirm set as primary for this bank account?", function (result) {
            if (result) {
                individual_bank_account_primary_radio_button.prop( "checked", true );
            }
        });
    });

    //individual_individual_bank_account
    $(".addMore_individual_bank_account").click(function(){

        $(".local_individual_bank_account_toggle").show();
        $(".show_individual_bank_account").find(".fa").addClass("fa-arrow-up").removeClass("fa-arrow-down");
        $(".show_individual_bank_account").find(".toggle_word").text('Show less');

        $(".fieldGroupCopy_individual_bank_account").find('.second_individual_bank_account').attr("value", $(".main_individual_bank_account").val());

        $(".fieldGroupCopy_individual_bank_account").find('.individual_bank_account_primary').attr("value", $(".main_individual_bank_account").val());

        var fieldHTML = '<div class="input-group fieldGroup_individual_bank_account" style="margin-top:10px; display: block;">'+$(".fieldGroupCopy_individual_bank_account").html()+'</div>';

        $( fieldHTML).prependTo(".local_individual_bank_account_toggle");

        if ($(".main_individual_bank_account_primary").is(":checked")) 
        {
            $(".local_individual_bank_account_toggle .fieldGroup_individual_bank_account").eq(0).find('.individual_bank_account_primary').prop( "checked", true );
        }

        $(".button_increment_individual_bank_account").css({"visibility": "hidden"});

        if ($(".local_individual_bank_account_toggle").find(".second_individual_bank_account").length > 0) 
        {
            $(".show_individual_bank_account").css({"visibility": "visible"});

        }
        else {
            $(".show_individual_bank_account").css({"visibility": "hidden"});
            
        }
       
        $(".main_individual_bank_account").val("");
        $(".main_individual_bank_account").parent().find(".individual_bank_account_primary").val("");
        $(".fieldGroupCopy_individual_bank_account").find('.second_individual_bank_account').attr("value", "");
        $(".fieldGroupCopy_individual_bank_account").find('.individual_bank_account_primary').attr("value", "");

    });

    $("body").on("click",".remove_individual_bank_account",function(){ 
        var remove_individual_bank_account = $(this);
        bootbox.confirm("Are you comfirm delete this address?", function (result) {
            if (result) {

                remove_individual_bank_account.parents(".fieldGroup_individual_bank_account").remove();

                if (remove_individual_bank_account.parent().find(".individual_bank_account_primary").is(":checked")) 
                {
                    if ($(".local_individual_bank_account_toggle").find(".second_individual_bank_account").length > 0) 
                    {
                        $(".local_individual_bank_account_toggle .fieldGroup_individual_bank_account").eq(0).find('.individual_bank_account_primary').prop( "checked", true );
                    }
                    else
                    {
                        $(".main_individual_bank_account_primary").prop( "checked", true );
                    }
                }

                if ($(".local_individual_bank_account_toggle").find(".second_individual_bank_account").length > 0) 
                {
                    $(".show_individual_bank_account").css({"visibility": "visible"});

                }
                else {
                    $(".show_individual_bank_account").css({"visibility": "hidden"});
                    
                }
                
                $( '#form_individual_bank_account' ).html("");
            }
        });
    });

    $('.main_individual_bank_account').keyup(function(){

        if ($(this).val()) {
            $(".button_increment_individual_bank_account").css({"visibility": "visible"});

        }
        else {
            $(".button_increment_individual_bank_account").css({"visibility": "hidden"});
        }
    });
    if ($(".local_individual_bank_account_toggle").find(".second_individual_bank_account").length > 0) 
    {
        $(".show_individual_bank_account").css({"visibility": "visible"});
        $(".local_individual_bank_account_toggle").hide();
    }
    else {
        $(".show_individual_bank_account").css({"visibility": "hidden"});
        $(".local_individual_bank_account_toggle").hide();
    }
});
//--------------------------------------end_individual_bank_account---------------------------------------

$("#individual_source_of_funds").change(function (){
    if($(this).val() == "OTHERS")
    {
        $(".other_source_of_funds_div").show();
    }
    else
    {
        $(".other_source_of_funds_div").hide();
    }
    $("#individual_other_source_of_funds").val("");
});

$("#individual_identity_document_type").change(function (){
    if($(this).val() == "OTHERS")
    {
        $(".other_identity_document_type_div").show();
    }
    else
    {
        $(".other_identity_document_type_div").hide();
    }
    $("#individual_other_identity_document_type").val("");
});
//".multiselect_div .multiselect-container input[value='UNKNOWN']"
// $('.multiselect_div .multiselect-container input[type="checkbox"]').change(function(){
//     console.log("in");
//     if($(this).is(':checked')){
//         alert('It has been checked!');
//     } else {
//         alert('Our checkbox is not checked!');
//     }
// });

// $("#individual_payment_mode").change(function(){
//     //console.log($(this).find("option").attr("id"));
//     //console.log("inin");
//     if($(".multiselect_div .multiselect-container input[value='UNKNOWN']").is(":checked"))
//     {
//         $(".multiselect_div .multiselect-container input[value='TELEGRAPHIC TRANSFER']").prop('disabled', true).prop("checked", false);
//         $(".multiselect_div .multiselect-container input[value='TELEGRAPHIC TRANSFER']").parent().parent().parent().removeClass("active");
//         $(".multiselect_div .multiselect-container input[value='CHEQUE (LOCAL)']").prop('disabled', true).prop("checked", false);
//         $(".multiselect_div .multiselect-container input[value='CHEQUE (LOCAL)']").parent().parent().parent().removeClass("active");
//         $(".multiselect_div .multiselect-container input[value='CHEQUE (FOREIGN)']").prop('disabled', true).prop("checked", false);
//         $(".multiselect_div .multiselect-container input[value='CHEQUE (FOREIGN)']").parent().parent().parent().removeClass("active");
//         $(".multiselect_div .multiselect-container input[value='CREDIT CARD']").prop('disabled', true).prop("checked", false);
//         $(".multiselect_div .multiselect-container input[value='CREDIT CARD']").parent().parent().parent().removeClass("active");
//         $(".multiselect_div .multiselect-container input[value='VIRTUAL CURRENCY']").prop('disabled', true).prop("checked", false);
//         $(".multiselect_div .multiselect-container input[value='VIRTUAL CURRENCY']").parent().parent().parent().removeClass("active");
//         $(".multiselect_div .multiselect-container input[value='CASH']").prop('disabled', true).prop("checked", false);
//         $(".multiselect_div .multiselect-container input[value='CASH']").parent().parent().parent().removeClass("active");
//         $(".multiselect_div .multiselect-container input[value='DIRECT DEBIT / CREDIT']").prop('disabled', true).prop("checked", false);
//         $(".multiselect_div .multiselect-container input[value='DIRECT DEBIT / CREDIT']").parent().parent().parent().removeClass("active");
//         $(".multiselect_div .multiselect-container input[value='NOT APPLICABLE']").prop('disabled', true).prop("checked", false);
//         $(".multiselect_div .multiselect-container input[value='NOT APPLICABLE']").parent().parent().parent().removeClass("active");
//         //$('#kycScreeningIndividual-form').find('[name="individual_payment_mode[]"]').multiselect('refresh');
//     }
//     else if($(".multiselect_div .multiselect-container input[value='NOT APPLICABLE']").is(":checked"))
//     {
//         $(".multiselect_div .multiselect-container input[value='TELEGRAPHIC TRANSFER']").prop('disabled', true).prop("checked", false);
//         $(".multiselect_div .multiselect-container input[value='TELEGRAPHIC TRANSFER']").parent().parent().parent().removeClass("active");
//         $(".multiselect_div .multiselect-container input[value='CHEQUE (LOCAL)']").prop('disabled', true).prop("checked", false);
//         $(".multiselect_div .multiselect-container input[value='CHEQUE (LOCAL)']").parent().parent().parent().removeClass("active");
//         $(".multiselect_div .multiselect-container input[value='CHEQUE (FOREIGN)']").prop('disabled', true).prop("checked", false);
//         $(".multiselect_div .multiselect-container input[value='CHEQUE (FOREIGN)']").parent().parent().parent().removeClass("active");
//         $(".multiselect_div .multiselect-container input[value='CREDIT CARD']").prop('disabled', true).prop("checked", false);
//         $(".multiselect_div .multiselect-container input[value='CREDIT CARD']").parent().parent().parent().removeClass("active");
//         $(".multiselect_div .multiselect-container input[value='VIRTUAL CURRENCY']").prop('disabled', true).prop("checked", false);
//         $(".multiselect_div .multiselect-container input[value='VIRTUAL CURRENCY']").parent().parent().parent().removeClass("active");
//         $(".multiselect_div .multiselect-container input[value='CASH']").prop('disabled', true).prop("checked", false);
//         $(".multiselect_div .multiselect-container input[value='CASH']").parent().parent().parent().removeClass("active");
//         $(".multiselect_div .multiselect-container input[value='DIRECT DEBIT / CREDIT']").prop('disabled', true).prop("checked", false);
//         $(".multiselect_div .multiselect-container input[value='DIRECT DEBIT / CREDIT']").parent().parent().parent().removeClass("active");
//         $(".multiselect_div .multiselect-container input[value='UNKNOWN']").prop('disabled', true).prop("checked", false);
//         $(".multiselect_div .multiselect-container input[value='UNKNOWN']").parent().parent().parent().removeClass("active");
//         //$('#kycScreeningIndividual-form').find('[name="individual_payment_mode[]"]').multiselect('refresh');
//     }
//     else
//     {
//         $(".multiselect_div .multiselect-container input[value='TELEGRAPHIC TRANSFER']").prop('disabled', false);
//         $(".multiselect_div .multiselect-container input[value='CHEQUE (LOCAL)']").prop('disabled', false);
//         $(".multiselect_div .multiselect-container input[value='CHEQUE (FOREIGN)']").prop('disabled', false);
//         $(".multiselect_div .multiselect-container input[value='CREDIT CARD']").prop('disabled', false);
//         $(".multiselect_div .multiselect-container input[value='VIRTUAL CURRENCY']").prop('disabled', false);
//         $(".multiselect_div .multiselect-container input[value='CASH']").prop('disabled', false);
//         $(".multiselect_div .multiselect-container input[value='DIRECT DEBIT / CREDIT']").prop('disabled', false);
//         $(".multiselect_div .multiselect-container input[value='NOT APPLICABLE']").prop('disabled', false);
//         $(".multiselect_div .multiselect-container input[value='UNKNOWN']").prop('disabled', false);
//         //$('#kycScreeningIndividual-form').find('[name="individual_payment_mode[]"]').multiselect('refresh');
//     }   


// });

