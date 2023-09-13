if(get_kyc_corporate_info)
{
    $('#w2-screening #customer_id').val(get_kyc_corporate_info[0]['customer_id']);
}
if(get_kyc_individual_info)
{
    $('#w2-screening #customer_id').val(get_kyc_individual_info[0]['customer_id']);
}

$(".kycScreeningMainCSS-right .nav-tabs a[data-toggle=tab]").on("click", function(e) {
	if ($(this).hasClass("disabled")) {
		//console.log($(this).hasClass("disabled"));
		e.preventDefault();
		return false;
	}
});

$(document).on('click',".person_check_state",function() {
	person_tab_aktif = $(this).data("information");
	$(".submit_person_check_state").val(person_tab_aktif);

	if(person_tab_aktif == "kycScreeningInfo")
	{
		if(document.getElementById('individual_edit').checked) 
		{
			$('li[data-information="kycScreeningIndividual"]').addClass("active");
	        $('#w2-kycScreeningIndividual').addClass("active");
	        $('li[data-information="kycScreeningCorporate"]').removeClass("active");
	        $('#w2-kycScreeningCorporate').removeClass("active");
            runIndividualValidate();
	        $('#loadingmessage').show();
	        $.ajax({
                url: "personprofile/kycCheckIndividualInfo",
                type: "POST",
                data: {officer_id: $('#upload #officer_id').val()},
                dataType: 'json',
                success: function (response) {
                    $('#loadingmessage').hide();
                    if(response.Status == 1)
                    {
                    	//console.log(response.data);
                    	$('#kycScreeningIndividual-form .indi_save_as_draft_button').hide();
                    	$('#kycScreeningIndividual-form .indi_update_button').show();

                    	var officer_info = response.data;
                    	$('#kycScreeningIndividual-form #individual_officer_id').val(officer_info[0]['officer_id']);
                    	$('#kycScreeningIndividual-form #customer_id').val(officer_info[0]['customer_id']);
                    	$('#kycScreeningIndividual-form #record_id').val(officer_info[0]['record_id']);
                    	if(officer_info[0]['customer_active'] == 0)
                    	{
                    		$("#individual_customer_active").prop("checked", false);
                    	}
                    	if(officer_info[0]['salutation'] != "")
                    	{
                    		$('#kycScreeningIndividual-form #salutation').val(officer_info[0]['salutation']);
                    	}
                    	$('#kycScreeningIndividual-form #individual_name').val(officer_info[0]['name']);
                    	$('#kycScreeningIndividual-form #alias').val(officer_info[0]['alias']);
                    	$('#kycScreeningIndividual-form #gender').val(officer_info[0]['gender']);
                    	$('#kycScreeningIndividual-form #individual_country_of_residence').val(officer_info[0]['country_of_residence']);
                    	$('#kycScreeningIndividual-form #individual_nationality').val(officer_info[0]['nationality']);
                    	$('#kycScreeningIndividual-form #individual_identity_document_type').val(officer_info[0]['identity_document_type']);
                    	if(officer_info[0]['identity_document_type'] == "OTHERS")
                    	{
                    		$(".other_identity_document_type_div").show();
                    		$('#kycScreeningIndividual-form #individual_other_identity_document_type').val(officer_info[0]['other_identity_document_type']);
                    	}
                    	$('#kycScreeningIndividual-form #identity_number').val(officer_info[0]['identity_number']);
                    	$('#kycScreeningIndividual-form #individual_industry').val(officer_info[0]['industry']);
                    	$('#kycScreeningIndividual-form #individual_onboarding_mode').val(officer_info[0]['onboarding_mode']);
                    	$('#kycScreeningIndividual-form #individual_product_service_complexity').val(officer_info[0]['product_service_complexity']);
                    	$('#kycScreeningIndividual-form #individual_source_of_funds').val(officer_info[0]['source_of_funds']);
                    	if(officer_info[0]['source_of_funds'] == "OTHERS")
                    	{
                    		$(".other_source_of_funds_div").show();
                    		$('#kycScreeningIndividual-form #individual_other_source_of_funds').val(officer_info[0]['other_source_of_funds']);
                    	}
                    	$('#kycScreeningIndividual-form #individual_country_of_birth').val(officer_info[0]['country_of_birth']);
                    	$('#kycScreeningIndividual-form #individual_occupation').val(officer_info[0]['occupation']);
                    	var individual_pm = jQuery.parseJSON(officer_info[0]['payment_mode']);

                    	for(var t = 0; t < individual_pm.length; t++)
                    	{
                    		//console.log(individual_pm[t]);
                    		if(individual_pm[t] == "UNKNOWN" || individual_pm[t] == "NOT APPLICABLE")
                    		{
				                $("#individual_payment_mode").find('option[value="TELEGRAPHIC TRANSFER"]').prop('disabled',true);
				                $("#individual_payment_mode").find('option[value="CHEQUE (LOCAL)"]').prop('disabled',true);
				                $("#individual_payment_mode").find('option[value="CHEQUE (FOREIGN)"]').prop('disabled',true);
				                $("#individual_payment_mode").find('option[value="CREDIT CARD"]').prop('disabled',true);
				                $("#individual_payment_mode").find('option[value="VIRTUAL CURRENCY"]').prop('disabled',true);
				                $("#individual_payment_mode").find('option[value="CASH"]').prop('disabled',true);
				                $("#individual_payment_mode").find('option[value="DIRECT DEBIT / CREDIT"]').prop('disabled',true);
				                if(individual_pm[t] == "NOT APPLICABLE")
                        		{
					                $(".multiselect_div .multiselect-container input[value='UNKNOWN']").prop('disabled', true).prop("checked", false);
					                $(".multiselect_div .multiselect-container input[value='UNKNOWN").parent().parent().parent().removeClass("active");//["TELEGRAPHIC TRANSFER","CHEQUE (LOCAL)","CHEQUE (FOREIGN)","CREDIT CARD"]
					            }
					            else if(individual_pm[t] == "UNKNOWN")
					            {
					            	$(".multiselect_div .multiselect-container input[value='NOT APPLICABLE']").prop('disabled', true).prop("checked", false);
					                $(".multiselect_div .multiselect-container input[value='NOT APPLICABLE").parent().parent().parent().removeClass("active");
					            }
					            $("#individual_payment_mode").find('option[value="'+individual_pm[t]+'"]').prop('selected',true);
                        		$(".multiselect_div .multiselect-container input[value='"+individual_pm[t]+"']").prop("checked", true);
                				$(".multiselect_div .multiselect-container input[value='"+individual_pm[t]+"']").parent().parent().parent().addClass("active");
                				$('#kycScreeningIndividual-form').find('[name="individual_payment_mode[]"]').multiselect('refresh');
                    		}
                    		else
                    		{
                        		$("#individual_payment_mode").find('option[value="'+individual_pm[t]+'"]').prop('selected',true);
                        		$(".multiselect_div .multiselect-container input[value='"+individual_pm[t]+"']").prop("checked", true);
                				$(".multiselect_div .multiselect-container input[value='"+individual_pm[t]+"']").parent().parent().parent().addClass("active");
                				$('#kycScreeningIndividual-form').find('[name="individual_payment_mode[]"]').multiselect('refresh');
                			}
                    	}
                    	$('#kycScreeningIndividual-form #reference_number').val(officer_info[0]['reference_number']);
                    	$('#kycScreeningIndividual-form #individual_date_of_birth').val(officer_info[0]['date_of_birth']);
                    	$('#kycScreeningIndividual-form #individual_nature_of_business_relationship').val(officer_info[0]['nature_of_business_relationship']);
                    	individual_address(jQuery.parseJSON(officer_info[0]['address']));
                    	individual_mobile_no(jQuery.parseJSON(officer_info[0]['phone_number']));
						individual_email_address(jQuery.parseJSON(officer_info[0]['email_address']));
						individual_bank_account(jQuery.parseJSON(officer_info[0]['bank_account']));

                        if(officer_info[0]['status'] != "")
                        {
                            $("#show_individual_status").show();
                            $("#individual_status").text(officer_info[0]["status"]);
                        }
                    }
                    else
                    {
                    	$('#kycScreeningIndividual-form .indi_save_as_draft_button').show();
                    	$('#kycScreeningIndividual-form .indi_update_button').hide();

                    	$('#kycScreeningIndividual-form #individual_officer_id').val($('#upload #officer_id').val());
		            	$('#kycScreeningIndividual-form #individual_name').val($('#upload #name').val());
                        $('#kycScreeningIndividual-form #alias').val($('#upload #alias').val());
		            	$('#kycScreeningIndividual-form #identity_number').val($('#upload #identification_no').val());
                        $('#kycScreeningIndividual-form #individual_date_of_birth').val($('#upload #date_of_birth').val());
		            	if($('#upload #identification_type').val() == "Passport/ Others")
		            	{
		            		$('#kycScreeningIndividual-form #individual_identity_document_type').val("INTERNATIONAL PASSPORT");
		            	}
		            	else
		            	{
		            		$('#kycScreeningIndividual-form #individual_identity_document_type').val("NATIONAL ID");
		            	}

		            	$("#kycScreeningIndividual-form #individual_nationality > option").each(function() {
							var originalValue = this.value;
							var optionValue = $('#upload #nationalityId option:selected').text().slice(0, 5);
							var valueForMatch = '\\b'+optionValue;

							if(originalValue.match(new RegExp(valueForMatch, 'g')) == optionValue)
							{	
								$("#kycScreeningIndividual-form #individual_nationality").val(this.value);
								return;
							}

						});

						if(document.getElementById('local_edit').checked) {
							var indi_postal_code = $('#upload #postal_code1').val();
							var indi_street_name = $('#upload #street_name1').val();
							var indi_building_name = $('#upload #building_name1').val();
							var indi_unit_no1 = $('#upload #unit_no1').val();
							var indi_unit_no2 = $('#upload #unit_no2').val();

							var indi_address = changeLocalAddressFormat(indi_postal_code, indi_street_name, indi_building_name, indi_unit_no1, indi_unit_no2);

							$("#kycScreeningIndividual-form #individual_address").val(indi_address);
							$(".button_increment_individual_address").css({"visibility": "visible"});
						}
						else if(document.getElementById('foreign_edit').checked)
						{
							var indi_foreign_address1 = $('#upload #foreign_address1').val();
							var indi_foreign_address2 = $('#upload #foreign_address2').val();
							var indi_foreign_address3 = $('#upload #foreign_address3').val();

							var indi_address = changeForeignAddressFormat(indi_foreign_address1, indi_foreign_address2, indi_foreign_address3);

							$("#kycScreeningIndividual-form #individual_address").val(indi_address);
							$(".button_increment_individual_address").css({"visibility": "visible"});
						}

						var indi_contact_no_value = $("#upload input[name='local_mobile[]']").map(function(){return $(this).val();}).get();
						var filtered_indi_contact_no_value = indi_contact_no_value.filter(function (el) {
						  return el != "";
						});
						individual_mobile_no(filtered_indi_contact_no_value);

						var indi_email_address_value = $("#upload input[name='email[]']").map(function(){return $(this).val();}).get();
						var filtered_indi_email_address_value = indi_email_address_value.filter(function (el) {
						  return el != "";
						});
						individual_email_address(filtered_indi_email_address_value);

						//console.log(filtered_indi_email_address_value);
                    }
                    $('#gender').select2();
                    $('#individual_nationality').select2();
                    $('#individual_country_of_residence').select2();
                    $('#individual_country_of_birth').select2();
                    $('#individual_identity_document_type').select2();
                    $('#individual_industry').select2();
                    $('#individual_onboarding_mode').select2();
                    $('#individual_product_service_complexity').select2();
                    $('#individual_source_of_funds').select2();
                    $('#individual_occupation').select2();
                }
            });
		}
		else if(document.getElementById('company_edit').checked) 
		{
			$('li[data-information="kycScreeningIndividual"]').removeClass("active");
	        $('#w2-kycScreeningIndividual').removeClass("active");
	        $('li[data-information="kycScreeningCorporate"]').addClass("active");
	        $('#w2-kycScreeningCorporate').addClass("active");
            runCorporateValidate();
	        //officer_company_id

	        $.ajax({
                url: "personprofile/kycCheckCorporateInfo",
                type: "POST",
                data: {	officer_company_id: $('#submit_company #officer_company_id').val()},
                dataType: 'json',
                success: function (response) {
                    $('#loadingmessage').hide();
                    if(response.Status == 1)
                    {
                    	$('#kycScreeningCorporate-form .corp_save_as_draft_button').hide();
				    	$('#kycScreeningCorporate-form .corp_update_button').show();

				    	var corporate_info = response.data;
                    	$('#kycScreeningCorporate-form #corporate_officer_id').val(corporate_info[0]['officer_company_id']);
                    	$('#kycScreeningCorporate-form #customer_id').val(corporate_info[0]['customer_id']);
                    	$('#kycScreeningCorporate-form #record_id').val(corporate_info[0]['record_id']);
                    	if(corporate_info[0]['customer_active'] == 0)
                    	{
                    		$("#corporate_customer_active").prop("checked", false);
                    	}
                    	if(corporate_info[0]['company_incorporated'] == 0)
                    	{
                    		$("#corporate_company_incorp").prop("checked", false);
                    	}
                    	$('#kycScreeningCorporate-form #corporate_name').val(corporate_info[0]['corporate_name']);
                    	$('#kycScreeningCorporate-form #corporate_entity_type').val(corporate_info[0]['entity_type']);
                    	$('#kycScreeningCorporate-form #corporate_ownership_structure_layer').val(corporate_info[0]['ownership_structure_layer']);
                    	$('#kycScreeningCorporate-form #corporate_country_of_incorporation').val(corporate_info[0]['country_of_incorporation']);
                    	$('#kycScreeningCorporate-form #corporate_date_of_incorporation').val(corporate_info[0]['date_of_incorporation']);
                    	$('#kycScreeningCorporate-form #corporate_country_of_major_operation').val(corporate_info[0]['country_of_major_operation']);
                    	$('#kycScreeningCorporate-form #corporate_primary_business_activity').val(corporate_info[0]['primary_business_activity']);
                    	$('#kycScreeningCorporate-form #corporate_onboarding_mode').val(corporate_info[0]['onboarding_mode']);
                    	var corporate_pm = jQuery.parseJSON(corporate_info[0]['payment_mode']);

                    	for(var t = 0; t < corporate_pm.length; t++)
                    	{
                    		//console.log(individual_pm[t]);
                    		if(corporate_pm[t] == "UNKNOWN" || corporate_pm[t] == "NOT APPLICABLE")
                    		{
				                $("#corporate_payment_mode").find('option[value="TELEGRAPHIC TRANSFER"]').prop('disabled',true);
				                $("#corporate_payment_mode").find('option[value="CHEQUE (LOCAL)"]').prop('disabled',true);
				                $("#corporate_payment_mode").find('option[value="CHEQUE (FOREIGN)"]').prop('disabled',true);
				                $("#corporate_payment_mode").find('option[value="CREDIT CARD"]').prop('disabled',true);
				                $("#corporate_payment_mode").find('option[value="VIRTUAL CURRENCY"]').prop('disabled',true);
				                $("#corporate_payment_mode").find('option[value="CASH"]').prop('disabled',true);
				                $("#corporate_payment_mode").find('option[value="DIRECT DEBIT / CREDIT"]').prop('disabled',true);
				                if(corporate_pm[t] == "NOT APPLICABLE")
                        		{
					                $(".multiselect_div .multiselect-container input[value='UNKNOWN']").prop('disabled', true).prop("checked", false);
					                $(".multiselect_div .multiselect-container input[value='UNKNOWN").parent().parent().parent().removeClass("active");//["TELEGRAPHIC TRANSFER","CHEQUE (LOCAL)","CHEQUE (FOREIGN)","CREDIT CARD"]
					            }
					            else if(corporate_pm[t] == "UNKNOWN")
					            {
					            	$(".multiselect_div .multiselect-container input[value='NOT APPLICABLE']").prop('disabled', true).prop("checked", false);
					                $(".multiselect_div .multiselect-container input[value='NOT APPLICABLE").parent().parent().parent().removeClass("active");
					            }
					            $("#corporate_payment_mode").find('option[value="'+corporate_pm[t]+'"]').prop('selected',true);
                        		$(".multiselect_div .multiselect-container input[value='"+corporate_pm[t]+"']").prop("checked", true);
                				$(".multiselect_div .multiselect-container input[value='"+corporate_pm[t]+"']").parent().parent().parent().addClass("active");
                				$('#kycScreeningCorporate-form').find('[name="corporate_payment_mode[]"]').multiselect('refresh');
                    		}
                    		else
                    		{
                        		$("#corporate_payment_mode").find('option[value="'+corporate_pm[t]+'"]').prop('selected',true);
                        		$(".multiselect_div .multiselect-container input[value='"+corporate_pm[t]+"']").prop("checked", true);
                				$(".multiselect_div .multiselect-container input[value='"+corporate_pm[t]+"']").parent().parent().parent().addClass("active");
                				$('#kycScreeningCorporate-form').find('[name="corporate_payment_mode[]"]').multiselect('refresh');
                			}
                    	}
                    	$('#kycScreeningCorporate-form #corporate_product_service_complexity').val(corporate_info[0]['product_service_complexity']);
                    	$('#kycScreeningCorporate-form #corporate_source_of_funds').val(corporate_info[0]['source_of_funds']);
                    	if(corporate_info[0]['source_of_funds'] == "OTHERS")
                    	{
                    		$(".other_corp_identity_document_type_div").show();
                    		$('#kycScreeningCorporate-form #corporate_other_source_of_funds').val(corporate_info[0]['other_source_of_funds']);
                    	}
                    	
                    	$('#kycScreeningCorporate-form #reference_number').val(corporate_info[0]['reference_number']);
                    	$('#kycScreeningCorporate-form #incorporation_number').val(corporate_info[0]['incorporation_number']);

                    	corporate_address(jQuery.parseJSON(corporate_info[0]['address']));
                    	corporate_mobile_no(jQuery.parseJSON(corporate_info[0]['phone_number']));
						corporate_email_address(jQuery.parseJSON(corporate_info[0]['email_address']));
						corporate_bank_account(jQuery.parseJSON(corporate_info[0]['bank_account']));

                        if(corporate_info[0]['status'] != "")
                        {
                            $("#show_corp_status").show();
                            $("#corp_status").text(corporate_info[0]["status"]);
                        }

						$('#kycScreeningCorporate-form #corporate_nature_of_business_relationship').val(corporate_info[0]['nature_of_business_relationship']);
					}
                    else
                    {
                    	//new
				        $('#kycScreeningCorporate-form .corp_save_as_draft_button').show();
				    	$('#kycScreeningCorporate-form .corp_update_button').hide();
                        //console.log("$('#submit_company #officer_company_id').val() = "+$('#submit_company #officer_company_id').val());
                        $('#kycScreeningCorporate-form #corporate_officer_id').val($('#submit_company #officer_company_id').val());
				    	$('#kycScreeningCorporate-form #corporate_name').val($('#submit_company #company_name').val());
				    	$('#kycScreeningCorporate-form #corporate_date_of_incorporation').val($('#submit_company #date_of_incorporation').val());
				    	$('#kycScreeningCorporate-form #incorporation_number').val($('#submit_company #register_no').val());
				    	
				    	$("#kycScreeningCorporate-form #corporate_country_of_incorporation > option").each(function() {
							var originalValue = this.value;
							var optionValue = $('#submit_company #country_of_incorporation').val();
							var valueForMatch = '\\b'+optionValue;

							if(originalValue.match(new RegExp(valueForMatch, 'g')) == optionValue)
							{	
								$("#kycScreeningCorporate-form #corporate_country_of_incorporation").val(this.value);
								return;
							}

						});

						if(document.getElementById('company_local_edit').checked) {
							var indi_postal_code = $('#submit_company #company_postal_code').val();
							var indi_street_name = $('#submit_company #company_street_name').val();
							var indi_building_name = $('#submit_company #company_building_name').val();
							var indi_unit_no1 = $('#submit_company #company_unit_no1').val();
							var indi_unit_no2 = $('#submit_company #company_unit_no2').val();

							var indi_address = changeLocalAddressFormat(indi_postal_code, indi_street_name, indi_building_name, indi_unit_no1, indi_unit_no2);

							$("#kycScreeningCorporate-form #corporate_address").val(indi_address);
							$(".button_increment_corporate_address").css({"visibility": "visible"});
						}
						else if(document.getElementById('company_foreign_edit').checked)
						{
							var indi_foreign_address1 = $('#submit_company #company_foreign_address1').val();
							var indi_foreign_address2 = $('#submit_company #company_foreign_address2').val();
							var indi_foreign_address3 = $('#submit_company #company_foreign_address3').val();

							var indi_address = changeForeignAddressFormat(indi_foreign_address1, indi_foreign_address2, indi_foreign_address3);

							$("#kycScreeningCorporate-form #corporate_address").val(indi_address);
							$(".button_increment_corporate_address").css({"visibility": "visible"});
						}

						var corp_contact_no_value = $("#submit_company input[name='company_phone_number[]']").map(function(){return $(this).val();}).get();
						var filtered_corp_contact_no_value = corp_contact_no_value.filter(function (el) {
						  return el != "";
						});
						corporate_mobile_no(filtered_corp_contact_no_value);

						var corp_email_address_value = $("#submit_company input[name='company_email[]']").map(function(){return $(this).val();}).get();
						var filtered_corp_email_address_value = corp_email_address_value.filter(function (el) {
						  return el != "";
						});
						corporate_email_address(filtered_corp_email_address_value);
					}
                    $('#corporate_entity_type').select2();
                    $('#corporate_country_of_incorporation').select2();
                    $('#corporate_ownership_structure_layer').select2();
                    $('#corporate_country_of_major_operation').select2();
                    $('#corporate_onboarding_mode').select2();
                    $('#corporate_primary_business_activity').select2();
                    $('#corporate_product_service_complexity').select2();
                    $('#corporate_source_of_funds').select2();
                }
            });
		}
	}

	if(person_tab_aktif == "screening")
	{
        append_risk_info_to_table();
	}
});

function append_risk_info_to_table()
{
    if(document.getElementById('individual_edit').checked) 
    {
        $('#loadingmessage').show();
        $.ajax({
            url: "personprofile/kycIndividualRiskReportInfo",
            type: "POST",
            data: {officer_id: $('#upload #officer_id').val()},
            dataType: 'json',
            success: function (response) {
                $('#loadingmessage').hide();

                if(response.Status == 1)
                {
                    if ($.fn.DataTable.isDataTable("#datatable-screening")) {
                      $('#datatable-screening').DataTable().clear().destroy();
                    }

                    riskReport = response.data;
                    $("#w2-screening #customer_id").val(riskReport[0]["customer"]);
                    $(".for_each_risk_report").remove();
                    $("#w2-screening #refreshKYCInfo").val("false");
                    for(var i = 0; i < riskReport.length; i++)
                    {
                        if(riskReport[i]['latest_approval_status'] != "")
                        {
                            var str = riskReport[i]['created_at'];
                            var parts = str.slice(0, -1).split('+');
                            var dateComponent = parts[0];
                            var timeComponent = parts[1];
                            var updated_at_date = moment(dateComponent).format('DD/MM/YYYY');
                            var updated_at_time = moment(dateComponent).format('h:mm:ss a');
                            var updated_at_date_time = updated_at_date + ' ' + updated_at_time;
                            var risk_json = JSON.parse(riskReport[i]['risk_json']);
                            if(riskReport[i]['latest_approval_status'] != "")
                            {
                                var latest_approval_status = JSON.parse(riskReport[i]['latest_approval_status']);
                                var updated_by = latest_approval_status['updatedBy'];
                                var overrideRisk = ((latest_approval_status['overrideRisk'] != "")?latest_approval_status['overrideRisk']:"-");
                            }
                            else
                            {
                                var val_latest_approval_status = "-";
                            }
                            var riskScore = parseFloat(risk_json["riskScore"]);

                            $b=""; 
                            $b += '<tr class="for_each_risk_report">';
                            $b += '<td>'+updated_at_date_time+'</td>';
                            $b += '<td>'+updated_by["name"]+'</td>';
                            $b += '<td style="text-align:right">'+riskScore.toFixed(2)+'%</td>';
                            $b += '<td>'+risk_json["riskRating"]+'</td>';
                            $b += '<td style="text-align:center">'+overrideRisk+'</td>';
                            $b += '<td>'+latest_approval_status["approvalStatus"]+'</td>';
                            $b += '<td style="text-align:center"><span onclick="openRiskReport('+riskReport[i]['id']+')"><i class="fa fa-search" aria-hidden="true" style="font-size: 2.3rem;color: #FFBF00; cursor: pointer;"></i></span></td>';
                            $b += '</tr>';

                            $(".datatable-screening").append($b);
                        }
                    }
                    $.fn.dataTableExt.oSort['time-date-sort-pre'] = function(value) {      
                        return Date.parse(value);
                    };
                    $.fn.dataTableExt.oSort['time-date-sort-asc'] = function(a,b) {      
                        return a-b;
                    };
                    $.fn.dataTableExt.oSort['time-date-sort-desc'] = function(a,b) {
                        return b-a;
                    };

                    $('#datatable-screening').DataTable({
                        columnDefs : [
                            { 
                                type: 'time-date-sort', 
                                targets: [0],
                            }
                        ],
                        "order": [[0, 'desc']]
                    });
                }
                $('#datatable-screening').DataTable();
            }
        });
    }
    else if(document.getElementById('company_edit').checked) 
    {
        $('#loadingmessage').show();
        $.ajax({
            url: "personprofile/kycCorporateRiskReportInfo",
            type: "POST",
            data: {officer_company_id: $('#submit_company #officer_company_id').val()},
            dataType: 'json',
            success: function (response) {
                $('#loadingmessage').hide();

                if(response.Status == 1)
                {
                    if ($.fn.DataTable.isDataTable("#datatable-screening")) {
                      $('#datatable-screening').DataTable().clear().destroy();
                    }
                    
                    riskReport = response.data;
                    $("#w2-screening #customer_id").val(riskReport[0]["customer"]);
                    $(".for_each_risk_report").remove();
                    $("#w2-screening #refreshKYCInfo").val("false");
                    for(var i = 0; i < riskReport.length; i++)
                    {
                        if(riskReport[i]['latest_approval_status'] != "")
                        {
                            var str = riskReport[i]['updated_at'];
                            var parts = str.slice(0, -1).split('+');
                            var dateComponent = parts[0];
                            var timeComponent = parts[1];
                            var updated_at_date = moment(dateComponent).format('DD/MM/YYYY');
                            var updated_at_time = moment(dateComponent).format('h:mm:ss a');
                            var updated_at_date_time = updated_at_date + ' ' + updated_at_time;

                            //var updated_by = JSON.parse(riskReport[i]['updated_by']);
                            var risk_json = JSON.parse(riskReport[i]['risk_json']);
                            if(riskReport[i]['latest_approval_status'] != "")
                            {
                                var latest_approval_status = JSON.parse(riskReport[i]['latest_approval_status']);
                                //var val_latest_approval_status = latest_approval_status["overrideRisk"];
                                var updated_by = latest_approval_status['updatedBy'];
                                var overrideRisk = ((latest_approval_status['overrideRisk'] != "")?latest_approval_status['overrideRisk']:"-");
                            }
                            else
                            {
                                var val_latest_approval_status = "-";
                            }
                            var riskScore = parseFloat(risk_json["riskScore"]);
                            //console.log(latest_approval_status);
                            $b=""; 
                            $b += '<tr class="for_each_risk_report">';
                            //$b += '<td style="text-align: right">'+(i+1)+'</td>';
                            $b += '<td>'+updated_at_date_time+'</td>';
                            $b += '<td>'+updated_by["name"]+'</td>';
                            $b += '<td style="text-align:right">'+riskScore.toFixed(2)+'%</td>';
                            $b += '<td>'+risk_json["riskRating"]+'</td>';
                            $b += '<td style="text-align:center">'+overrideRisk+'</td>';
                            $b += '<td>'+latest_approval_status["approvalStatus"]+'</td>';
                            $b += '<td style="text-align:center"><span onclick="openRiskReport('+riskReport[i]['id']+')"><i class="fa fa-search" aria-hidden="true" style="font-size: 2.3rem;color: #FFBF00; cursor: pointer;"></i></span></td>';
                            //$b += '<td></td>';
                            $b += '</tr>';

                            $(".datatable-screening").append($b);
                        }
                    }
                    $.fn.dataTableExt.oSort['time-date-sort-pre'] = function(value) {      
                        return Date.parse(value);
                    };
                    $.fn.dataTableExt.oSort['time-date-sort-asc'] = function(a,b) {      
                        return a-b;
                    };
                    $.fn.dataTableExt.oSort['time-date-sort-desc'] = function(a,b) {
                        return b-a;
                    };

                    $('#datatable-screening').DataTable({
                        columnDefs : [
                            { 
                                type: 'time-date-sort', 
                                targets: [0],
                            }
                        ],
                        "order": [[0, 'desc']]
                    });
                }
                $('#datatable-screening').DataTable();
            }
        });
    }
	
}

$(document).on('click',".refreshRiskReport",function(e){
    $("#w2-screening #refreshKYCInfo").val("true");
    loginToKYC();
});

$(document).on('click',".downloadRiskReport",function(e){
    loginToKYC();
});

function loginToKYC()
{
    var user_previous_token = localStorage.getItem("refreshUserToken");

    if(user_previous_token == null)
    {
        bootbox
            .dialog({
                title: 'Artemis Login',
                message: $('#loginForm'),
                show: false, // We will show it manually later
                onEscape: function() {
        	        // you can do anything here you want when the user dismisses dialog
        	        $("#w2-screening #refreshKYCInfo").val("false");
        	    }
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
        var token = new AmazonCognitoIdentity.CognitoRefreshToken({ RefreshToken: user_previous_token });
        cognitoUser.refreshSession(token, function (err, session) {
            //console.log(!err);
            if(!err)
            {
                var idToken = session.getIdToken().getJwtToken();
                var refreshToken = session.getRefreshToken().getToken();
                var accessToken = session.getAccessToken().getJwtToken();

                localStorage.setItem("accessToken", accessToken);
                localStorage.setItem("refreshUserToken", refreshToken);

                screeningResult(accessToken);
            }
            else
            {
                localStorage.removeItem("refreshUserToken");
                loginToKYC();
            }
        });
        // cognitoUser.refreshSession(token, function (err, session) {
        //     console.log(session);
        //     var idToken = session.getIdToken().getJwtToken();
        //     var refreshToken = session.getRefreshToken().getToken();
        //     var accessToken = session.getAccessToken().getJwtToken();

        //     localStorage.setItem("accessToken", accessToken);
        //     localStorage.setItem("refreshUserToken", refreshToken);

        //     screeningResult(accessToken);
        // })
    }
}


function openRiskReport(id)
{
	for(var i = 0; i < riskReport.length; i++)
    {
    	if(riskReport[i]['id'] == id)
		{
			var risk_json = JSON.parse(riskReport[i]['risk_json']);
            if(document.getElementById('individual_edit').checked) 
            {
                var weight = risk_json['settings']['weight']['INDIVIDUAL'];
            }   
            else if(document.getElementById('company_edit').checked) 
            {
                var weight = risk_json['settings']['weight']['CORPORATE'];
            }
			var componentScore = risk_json['componentScore'];

			var latest_approval_status = JSON.parse(riskReport[i]['latest_approval_status']);
			var str = riskReport[i]['updated_at'];
			//console.log(risk_json);
			bootbox
                .dialog({
                    title: 'Risk Report',
                    message: $('.risk_report_table')
                })
                .on('shown.bs.modal', function() {
                    $(".for_each_risk_report_mark").remove();
                	$b=""; 
			        $b += '<tr class="for_each_risk_report_mark">';
			        $b += '<td>Country Risk</td>';
			        $b += '<td>CPI - Corruption Perception Index</td>';
			        $b += '<td style="text-align:right">'+parseFloat(weight['cpi']).toFixed(2)+'%</td>';
			        $b += '<td style="text-align:right">'+parseFloat(componentScore['cpi']).toFixed(2)+'%</td>';
					$b += '</tr>';
					$b += '<tr class="for_each_risk_report_mark">';
			        $b += '<td>Country Risk</td>';
			        $b += '<td>FATF - Financial Action Task Force</td>';
			        $b += '<td style="text-align:right">'+parseFloat(weight['fatf']).toFixed(2)+'%</td>';
			        $b += '<td style="text-align:right">'+parseFloat(componentScore['fatf']).toFixed(2)+'%</td>';
					$b += '</tr>';

					$b += '<tr class="for_each_risk_report_mark">';
			        $b += '<td>Tax Risk</td>';
			        $b += '<td>OECD - Organisation for Economic Co-operation and Development</td>';
			        $b += '<td style="text-align:right">'+parseFloat(weight['oecd']).toFixed(2)+'%</td>';
			        $b += '<td style="text-align:right">'+parseFloat(componentScore['oecd']).toFixed(2)+'%</td>';
					$b += '</tr>';

					$b += '<tr class="for_each_risk_report_mark">';
			        $b += '<td>Tax Risk</td>';
			        $b += '<td>FSI - Financial Secrecy Index</td>';
			        $b += '<td style="text-align:right">'+parseFloat(weight['fsi']).toFixed(2)+'%</td>';
			        $b += '<td style="text-align:right">'+parseFloat(componentScore['fsi']).toFixed(2)+'%</td>';
					$b += '</tr>';

					$b += '<tr class="for_each_risk_report_mark">';
			        $b += '<td>Tax Risk</td>';
			        $b += '<td>FATCA - Foreign Account Tax Compliance Act</td>';
			        $b += '<td style="text-align:right">'+parseFloat(weight['fatca']).toFixed(2)+'%</td>';
			        $b += '<td style="text-align:right">'+parseFloat(componentScore['fatca']).toFixed(2)+'%</td>';
					$b += '</tr>';

					$b += '<tr class="for_each_risk_report_mark">';
			        $b += '<td>Screening Risk</td>';
			        $b += '<td>PEP / Sanctions / Adverse News</td>';
			        $b += '<td style="text-align:right">'+parseFloat(weight['screening']).toFixed(2)+'%</td>';
			        $b += '<td style="text-align:right">'+parseFloat(componentScore['screening']).toFixed(2)+'%</td>';
					$b += '</tr>';

					$b += '<tr class="for_each_risk_report_mark">';
			        $b += '<td>Structural Risk</td>';
			        $b += '<td>Industry</td>';
			        $b += '<td style="text-align:right">'+parseFloat(weight['industry']).toFixed(2)+'%</td>';
			        $b += '<td style="text-align:right">'+parseFloat(componentScore['industry']).toFixed(2)+'%</td>';
					$b += '</tr>';

                    if(document.getElementById('individual_edit').checked) 
                    {
                        $b += '<tr class="for_each_risk_report_mark">';
                        $b += '<td>Structural Risk</td>';
                        $b += '<td>Occupation</td>';
                        $b += '<td style="text-align:right">'+parseFloat(weight['occupation']).toFixed(2)+'%</td>';
                        $b += '<td style="text-align:right">'+parseFloat(componentScore['occupation']).toFixed(2)+'%</td>';
                        $b += '</tr>';
                    }
                    else if(document.getElementById('company_edit').checked) 
                    {
                        $b += '<tr class="for_each_risk_report_mark">';
                        $b += '<td>Structural Risk</td>';
                        $b += '<td>Ownership Layers</td>';
                        $b += '<td style="text-align:right">'+parseFloat(weight['ownershipLayer']).toFixed(2)+'%</td>';
                        $b += '<td style="text-align:right">'+parseFloat(componentScore['ownershipLayer']).toFixed(2)+'%</td>';
                        $b += '</tr>';
                    }
					

					$b += '<tr class="for_each_risk_report_mark">';
			        $b += '<td>Operational Risk</td>';
			        $b += '<td>Onboarding Mode</td>';
			        $b += '<td style="text-align:right">'+parseFloat(weight['onboardingMode']).toFixed(2)+'%</td>';
			        $b += '<td style="text-align:right">'+parseFloat(componentScore['onboardingMode']).toFixed(2)+'%</td>';
					$b += '</tr>';

					$b += '<tr class="for_each_risk_report_mark">';
			        $b += '<td>Operational Risk</td>';
			        $b += '<td>Payment Modes</td>';
			        $b += '<td style="text-align:right">'+parseFloat(weight['paymentModes']).toFixed(2)+'%</td>';
			        $b += '<td style="text-align:right">'+parseFloat(componentScore['paymentModes']).toFixed(2)+'%</td>';
					$b += '</tr>';

					$b += '<tr class="for_each_risk_report_mark">';
			        $b += '<td>Operational Risk</td>';
			        $b += '<td>Complexity of Products and Services</td>';
			        $b += '<td style="text-align:right">'+parseFloat(weight['productComplexity']).toFixed(2)+'%</td>';
			        $b += '<td style="text-align:right">'+parseFloat(componentScore['productComplexity']).toFixed(2)+'%</td>';
					$b += '</tr>';

					$b += '<tr class="for_each_risk_report_mark footer_risk_report_table">';
			        $b += '<td colspan="2">Total</td>';
			        //$b += '<td></td>';
			        $b += '<td style="text-align:right">'+parseFloat(100).toFixed(2)+'%</td>';
			        $b += '<td style="text-align:right">'+parseFloat(risk_json['riskScore']).toFixed(2)+'%</td>';
					$b += '</tr>';

					$b += '<tr class="for_each_risk_report_mark footer_risk_report_table">';
			        $b += '<td colspan="3">COMPUTED RISK RATING</td>';
			        //$b += '<td></td>';
			        //$b += '<td>'+parseFloat(100).toFixed(2)+'%</td>';
			        $b += '<td style="text-align:right">'+risk_json['riskRating']+'</td>';
					$b += '</tr>';

					$b += '<tr class="for_each_risk_report_mark footer_risk_report_table">';
			        $b += '<td colspan="3">APPROVAL STATUS</td>';
			        //$b += '<td></td>';
			        //$b += '<td>'+parseFloat(100).toFixed(2)+'%</td>';
			        $b += '<td style="text-align:right">'+latest_approval_status['approvalStatus']+'</td>';
					$b += '</tr>';
					
					//var str = riskReport[i]['updated_at'];
					var parts = str.slice(0, -1).split('+');
					var dateComponent = parts[0];
					var timeComponent = parts[1];
					var updated_at_date = moment(dateComponent).format('DD/MM/YYYY');
					var updated_at_time = moment(dateComponent).format('h:mm:ss a');
					var updated_at_date_time = 'Report generated on ' + updated_at_date + ' at ' + updated_at_time;
					$(".generate_by").text(updated_at_date_time);

					$(".risk_report_table_body").append($b);

                    $('.risk_report_table')
                        .show();                                 
                })
                .on('hide.bs.modal', function(e) {
                    // Bootbox will remove the modal (including the body which contains the login form)
                    // after hiding the modal
                    // Therefor, we need to backup the form
                    
                    $('.risk_report_table').hide().appendTo('body');
                })
                .modal('show');
		}
	}
}

function changeLocalAddressFormat(indi_postal_code, indi_street_name, indi_building_name1, indi_unit_no1s, indi_unit_no2s)
{
	if(indi_unit_no1s != "" && indi_unit_no2s)
	{
		var indi_unit_no = indi_unit_no1s + '-' + indi_unit_no2s;
	}
	else if(indi_unit_no1s != "")
	{
		var indi_unit_no = indi_unit_no1s;
	}
	else
	{
		var indi_unit_no = "";
	}

	if(indi_building_name1 != "" && indi_unit_no != "")
	{
		var indi_building_name = indi_unit_no + " " + indi_building_name1 + ", ";
	}
	else if(indi_building_name1 == "" && indi_unit_no != "" || indi_building_name1 != "" && indi_unit_no == "")
	{
		var indi_building_name = indi_unit_no + indi_building_name1 + ", ";
	}
	else
	{
		var indi_building_name = "";
	}

	return(indi_street_name + ', ' + indi_building_name + 'Singapore ' + indi_postal_code);
} 

function changeForeignAddressFormat(indi_foreign_address1s, indi_foreign_address2s, indi_foreign_address3s)
{
	if(indi_foreign_address2s != "")
	{
		var indi_foreign_address2 = ', ' + indi_foreign_address2s;
	}
	else
	{
		var indi_foreign_address2 = indi_foreign_address2s;
	}

	if(indi_foreign_address3s != "")
	{
		var indi_foreign_address3 = ', ' + indi_foreign_address3s;
	}
	else
	{
		var indi_foreign_address3 = indi_foreign_address3s;
	}
	return(indi_foreign_address1s + indi_foreign_address2 + indi_foreign_address3);
}