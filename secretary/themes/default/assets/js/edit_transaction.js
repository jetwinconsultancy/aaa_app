var array_for_incop_new_company, registered_address_info, transaction_client, transaction_task_id, 
    transaction_id, transaction_client_type_id, transaction_client_name, transaction_client_officers, transaction_filing, coll, transaction_client_signing_info, 
    transaction_contact_person_info, client_contact_info_email, client_contact_info_phone, client_selected_reminder, 
    transaction_document, document_table, array_for_appoint_director, transaction_appoint_new_director, client_unit,
    transaction_change_reg_ofis, array_for_change_company_name, transaction_change_company_name, company_class, allotmentPeople,
    previous_class_value = null, edit_transaction_share_transfer, activity_status, transaction_agm_ar, solvency_status,
    small_company, audited_financial_statement, filing_info, transaction_agm_ar_director_fee, transaction_agm_ar_dividend, transaction_agm_ar_amount_due, 
    transaction_agm_ar_director_retire, transaction_agm_ar_reappoint_auditor, total_number_of_share = 0, company_type, first_agm, xbrl_list, require_hold_agm_list,
    agm_share_transfer, consent_for_shorter_notice, bool_dispense_agm = false, epc_status, bank_name, transaction_opening_bank_acc,
    manner_of_operation, previous_bank_value = null, transaction_incorporation_subsidiary, transaction_issue_director_fee, transaction_dividends,
    transaction_service_proposal, transaction_engagement_letter, company_code_selected, exemption, regis_controller_is_kept, regis_nominee_dir_is_kept, check_is_first_agm, check_have_share_transfer,
    previous_transaction_agm_ar, billing_top_info, billing_below_info, service_dropdown, service_category, get_unit_pricing,
    follow_up_history_data, transaction_ml_quarterly_statements, billings, paid_billings, latest_client_billing_info_id = 0,
    datatable_service_proposal, array_for_director_info, array_currency_info, currency, get_current_client_controller_data, get_latest_client_controller_data,
    get_current_client_nominee_director_data, array_for_update_register_of_nominee_director, get_latest_client_nominee_director_data, 
    transaction_omp_grant, array_for_omp_grant, service_proposal_datatable, transaction_purchase_common_seal_data, client_list,
    transaction_common_seal_vendor_list, purchase_cs_index, service_name_elm, numberForRetrieve = 1;

var pathArray = location.href.split( '/' );
var protocol = pathArray[0];
var host = pathArray[2];
var folder = pathArray[3];
var url = protocol + '//' + host + '/' + folder + '/';

var tcm1 = new Chairman();
function ajaxCall() {
    this.send = function(data, url, method, success, type) {
        type = type||'json';
        var successRes = function(data) {
            success(data);
        };

        var errorRes = function(e) {
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

    this.getAllChairman = function(selected_edit_chairman) {
        var url = base_url+"/"+folder+"/"+'companytype/getAllChairman';
        var method = "post";
        var data = {"company_code":  $("#w2-agm_ar_form #company_code").val()};
        $('.transaction_agm_chairman').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            $('.transaction_agm_chairman').find("option:eq(0)").html("Select Chairman");
            if(data.tp == 1){
                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    if(selected_edit_chairman == null)
                    {
                        if(data.selected_all_chairman != null && key == data.selected_all_chairman)
                        {
                            option.attr('selected', 'selected');
                        }
                    }
                    else
                    {
                        if(key == selected_edit_chairman)
                        {
                            option.attr('selected', 'selected');
                        }
                    }
                    $('#transaction_agm_chairman').append(option);
                });
            }
            else{
                alert(data.msg);
            }
        }); 
    };

}

if(transaction_master != null)
{
    transaction_id = transaction_master[0]['id'];
    transaction_task_id = transaction_master[0]['transaction_task_id'];
    transaction_client_type_id = transaction_master[0]['client_type_id'];
    transaction_client_name = $('<textarea />').html(transaction_master[0]['client_name']).text();
}
else
{
    transaction_id = null;
    transaction_task_id = null;
    transaction_client_type_id = null;
    transaction_client_name = null;
}

get_client_type(transaction_client_type_id);
function get_client_type(transaction_client_type_id = null)
{
    $.ajax({
        type: "GET",
        url: "transaction/get_client_type",
        dataType: "json",
        success: function(data){
            if(data.tp == 1){
                $("#client_type option").remove();
                var select_option = $('<option />');
                select_option.attr('value', '0').text("Select Client");
                $("#client_type").append(select_option);
                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    if(transaction_client_type_id != null && key == transaction_client_type_id)
                    {
                        option.attr('selected', 'selected');
                        $("#client_type").prop("disabled", true);
                        $(".client_name_section").show();
                        if(transaction_client_type_id == 1)
                        {
                            $(".dropdown_client_name").show();
                            $('.dropdown_client_name').next(".select2-container").show();
                            $(".input_client_name").hide();
                        }
                        else
                        {
                            $(".dropdown_client_name").hide();
                            $('.dropdown_client_name').next(".select2-container").hide();
                            $(".input_client_name").show();
                        }
                    }
                    else
                    {
                        loadFirstTab();
                    }
                    $("#client_type").append(option);
                })
            }
        }
    })
}

$(".client_type").change(function(){
    get_client_name_interface($(this).val());
});

function get_client_name_interface(transaction_client_type_id = null)
{
    if(transaction_client_type_id == 1)
    {
        $(".client_name_section").show();
        $(".dropdown_client_name").show();
        $('.dropdown_client_name').next(".select2-container").show();
        $(".input_client_name").hide();
        $(".dropdown_client_name").prop("disabled", false);
        $(".input_client_name").prop("disabled", true);
        $(".loadingWizardMessage").show();
        $.ajax({
            type: "GET",
            url: "transaction/get_all_client",
            dataType: "json",
            success: function(data){
                $(".loadingWizardMessage").hide();
                if(data.tp == 1){
                    $("select#client_name option").remove();
                    var select_option = $('<option />');
                    select_option.attr('value', '0').text("Select Client Name");
                    $("select#client_name").append(select_option);

                    $.each(data['result'], function(key, val) {
                        var option = $('<option />');
                        option.attr('value', key).text(val);
                        if(transaction_client_name != null && val.toUpperCase().trim() == transaction_client_name.toUpperCase().trim())
                        {
                            option.attr('selected', 'selected');
                            $("select#client_name").prop("disabled", true);
                            //$("#uen").prop("readonly", true);
                            //$(".client_name_section").show();
                        }
                        else
                        {
                            loadFirstTab();
                        }
                        $("select#client_name").append(option);
                    })
                }
                $("select#client_name").select2();
            }
        })
    }
    else if(transaction_client_type_id == 2)
    {
        $(".client_name_section").show();
        $(".dropdown_client_name").hide();
        $('.dropdown_client_name').next(".select2-container").hide();
        $(".input_client_name").show();
        $(".dropdown_client_name").prop("disabled", true);
        $("input#client_name").val(transaction_client_name);
        if(transaction_client_name != null)
        {
            $(".input_client_name").prop("disabled", true);
        }
        else
        {
            $(".input_client_name").prop("disabled", false);
        }
    }
};

function get_el_client_name_interface()
{
    $(".loadingWizardMessage").show();
    $.ajax({
        type: "GET",
        url: "transaction/get_all_el_client",
        dataType: "json",
        success: function(data){
            $(".loadingWizardMessage").hide();
            if(data.tp == 1){
                $("#client_name option").remove();
                var select_option = $('<option />');
                select_option.attr('value', '0').text("Select Client Name");
                $("#client_name").append(select_option);

                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    var delete_string = val.replace(' (Potential Client)','');
                    if(transaction_client_name != null && delete_string.replace(/\s+/g,' ').trim() == transaction_client_name.replace(/\s+/g,' ').trim())
                    {
                        option.attr('selected', 'selected');
                        $("#client_name").prop("disabled", true);
                    }
                    else
                    {
                        loadFirstTab();
                    }
                    $("#client_name").append(option);
                })
            }
            $("#client_name").select2();
        }
    })
}

get_transaction_task(transaction_task_id);
function get_transaction_task(transaction_task_id = null)
{
	$.ajax({
		type: "POST",
		url: "transaction/get_transaction_task",
		dataType: "json",
        data: {"transaction_task_id":transaction_task_id},
		success: function(data){
            if(data.tp == 1){
            	$("#transaction_task option").remove();
                var select_option = $('<option />');
                select_option.attr('value', '0').text("Select Service");
                $("#transaction_task").append(select_option);
                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    if(transaction_task_id != null && key == transaction_task_id)
                    {
                        option.attr('selected', 'selected');
                        $("#transaction_task").prop("disabled", true);
                        $("#uen").prop("readonly", true);
                        $(".effec_date").show();
                        $(".effective_date").prop("disabled", false);
                        $("#change_condition").hide();
                        if(transaction_task_id == 1 || transaction_task_id == 4 || transaction_task_id == 5 || transaction_task_id == 6 || transaction_task_id == 7 || transaction_task_id == 12 || transaction_task_id == 15 || transaction_task_id == 26 || transaction_task_id == 31 || transaction_task_id == 32 || transaction_task_id == 33 || transaction_task_id == 34)
                        {
                            if(transaction_task_id == 1)
                            {
                                $(".for_incorp").css('display', '');
                                $(".date_of_update_cont").hide();
                                $(".date_of_update_entry").css('display', 'none');
                                $(".date_of_the_conf_received").prop("disabled", true);
                                $(".date_of_entry_or_update").prop("disabled", true);
                            }
                            else if(transaction_task_id == 31 || transaction_task_id == 32)
                            {
                                $(".effec_date").hide();
                                $(".effective_date").prop("disabled", true);
                                $(".for_incorp").hide();
                                $(".for_other_services").css('display', '');
                                $(".date_of_update_entry").css('display', '');
                                if(transaction_task_id == 31)
                                {
                                    $(".date_of_update_cont").css('display', '');
                                }
                                else if(transaction_task_id == 32)
                                {
                                    $(".date_of_update_cont").css('display', 'none');
                                }
                                $(".date_of_the_conf_received").prop("disabled", false);
                                $(".date_of_entry_or_update").prop("disabled", false);
                            }
                            else if(transaction_task_id == 4 || transaction_task_id == 5 || transaction_task_id == 6 || transaction_task_id == 7 || transaction_task_id == 12 || transaction_task_id == 15 || transaction_task_id == 26 || transaction_task_id == 33 || transaction_task_id == 34)
                            {
                                $(".for_incorp").hide();
                                $(".for_other_services").css('display', '');
                            }
                            $(".hide_completion_tab").css('display', '');
                            $("#transaction_completion").css("display", "");
                            $(".confirmation_number").text("4");
                            $(".compilation_number").text("5");
                            $(".completion_number").text("6");
                        }
                        else if(transaction_task_id == 36)
                        {
                            $(".effec_date").hide();
                            $(".effective_date").prop("disabled", true);
                            $(".accept_date").show();
                            $(".accepted_date").prop("disabled", false);
                            $(".uen_section").hide();
                            $(".client_section").hide();
                            $(".client_type").prop("disabled", false);
                            $(".for_other_services").css('display', '');
                            $(".transaction_document_tab").css('display', 'none');
                            $(".hide_completion_tab").css('display', 'none');
                            $("#transaction_completion").css("display", "none");
                            getPurchaseCommonSealInterface();

                            $(".confirmation_number").text("4");
                            $(".completion_number").text("5");
                        }
                        else
                        {
                            $(".for_incorp").hide();
                            $(".hide_completion_tab").css('display', '');
                            $("#transaction_completion").css("display", "");
                            $(".confirmation_number").text("3");
                            $(".compilation_number").text("4");
                            $(".completion_number").text("5");
                        }
                        if(transaction_task_id == 1)
                        {
                            $("#change_condition").show();
                            getIncorporationInterface();
                        }
                        else if(transaction_task_id == 2)
                        {
                            getAppointNewDirectorInterface();
                        }
                        else if(transaction_task_id == 3)
                        {
                            getResignDirectorInterface();
                        }
                        else if(transaction_task_id == 4)
                        {
                            getChangeRegOfisInterface();
                        }
                        else if(transaction_task_id == 5)
                        {
                            getChangeBizActivityInterface();
                        }
                        else if(transaction_task_id == 6)
                        {
                            getChangeFYEInterface();
                        }
                        else if(transaction_task_id == 7)
                        {
                            getAppointNewAuditorInterface();
                        }
                        else if(transaction_task_id == 8)
                        {
                            $(".effec_date").hide();
                            $(".effective_date").prop("disabled", false);
                            getIssueDividendInterface();
                        }
                        else if(transaction_task_id == 9)
                        {
                            $(".effec_date").hide();
                            $(".effective_date").prop("disabled", false);
                            getIssueDirectorFeeInterface();
                        }
                        else if(transaction_task_id == 10)
                        {
                            getShareTransferInterface();
                        }
                        else if(transaction_task_id == 11)
                        {
                            getShareAllotmentInterface();
                        }
                        else if(transaction_task_id == 12)
                        {
                            getChangeCompanyNameInterface();
                        }
                        else if(transaction_task_id == 15)
                        {
                            getChangeAgmArInterface();
                        }
                        else if(transaction_task_id == 16)
                        {
                            getOpeningBankAccountInterface();
                        }
                        else if(transaction_task_id == 20)
                        {
                            getIncorporationSubsidiaryInterface();
                        }
                        else if(transaction_task_id == 24)
                        {
                            getAppointmentSecretarialInterface();
                        }
                        else if(transaction_task_id == 26)
                        {
                            getStrikeOffInterface();
                        }
                        else if(transaction_task_id == 28)
                        {
                            getTakeOverOfSecretarialInterface();
                        }
                        else if(transaction_task_id == 29)
                        {
                            get_client_name_interface(transaction_client_type_id);
                            getServiceProposalInterface();
                            $(".effec_date").hide();
                            $(".effective_date").prop("disabled", false);
                            $(".uen_section").hide();
                            $(".client_section").show();
                            $(".accept_date").show();
                            $(".accepted_date").prop("disabled", false);
                        }
                        else if(transaction_task_id == 30)
                        {
                            get_el_client_name_interface();
                            getEngagementLetterInterface();
                            $(".effec_date").hide();
                            $(".effective_date").prop("disabled", false);
                            $(".uen_section").hide();
                            $(".client_name_section").show();
                            //$(".dropdown_client_name").prop("disabled", false);
                            $(".dropdown_client_name").show();
                            $(".accept_date").show();
                            $(".accepted_date").prop("disabled", false);
                        }
                        else if(transaction_task_id == 31)
                        {
                            getUpdateRegisterofController();
                        }
                        else if(transaction_task_id == 32)
                        {
                            getUpdateRegisterofNomineeDirector();
                        }
                        else if(transaction_task_id == 33)
                        {
                            getResignDirectorInterface();
                            // getUpdateRegisterofNomineeDirector();
                        }
                        else if(transaction_task_id == 34)
                        {
                            getAppointmentSecretarialInterface();
                        }
                        else if(transaction_task_id == 35)
                        {
                            get_client_name_interface(transaction_client_type_id);
                            getOMPGrantInterface();
                            $(".effec_date").hide();
                            $(".effective_date").prop("disabled", false);
                            $(".uen_section").hide();
                            $(".client_section").show();
                            $(".accept_date").show();
                            $(".accepted_date").prop("disabled", false);
                        }
                    }
                    else
                    {
                        loadFirstTab();
                    }
                    $("#transaction_task").append(option);
                });
                
                //$(".nationality").prop("disabled",false);
            }
            else{
                alert(data.msg);
            }

			$(".transaction_task").select2();
		}				
	});
}

$(".transaction_task").change(function(){
    $("#change_condition").hide();
    if($(this).val() == 1 || $(this).val() == 31 || $(this).val() == 32)
    {
        if($(this).val() == 1)
        {
            $("#change_condition").show();
            $(".for_incorp").css('display', '');
            $(".effec_date").show();
            $(".effective_date").prop("disabled", false);
            $(".date_of_update_entry").css('display', 'none');
            $(".date_of_update_cont").css('display', 'none');
            $(".date_of_the_conf_received").prop("disabled", true);
            $(".date_of_entry_or_update").prop("disabled", true);
        }
        else if($(this).val() == 31)
        {
            $(".for_incorp").hide();
            $(".for_other_services").css('display', '');
            $(".effec_date").hide();
            $(".effective_date").prop("disabled", true);
            $(".date_of_update_entry").css('display', '');
            $(".date_of_update_cont").css('display', '');
            $(".date_of_the_conf_received").prop("disabled", false);
            $(".date_of_entry_or_update").prop("disabled", false);
        }
        else if($(this).val() == 32)
        {
            $(".for_incorp").hide();
            $(".for_other_services").css('display', '');
            $(".effec_date").hide();
            $(".effective_date").prop("disabled", true);
            $(".date_of_update_entry").css('display', '');
            $(".date_of_update_cont").css('display', 'none');
            $(".date_of_the_conf_received").prop("disabled", true);
            $(".date_of_entry_or_update").prop("disabled", false);
        }
        $(".hide_completion_tab").css('display', '');
        $("#transaction_completion").css("display", "");
        $(".transaction_document_tab").css('display', '');
        $(".accept_date").hide();
        $(".accepted_date").prop("disabled", true);
        $(".confirmation_number").text("4");
        $(".compilation_number").text("5");
    }
    else if($(this).val() == 36)
    {
        $(".for_other_services").css('display', '');
        $(".transaction_document_tab").css('display', 'none');
        $(".confirmation_number").text("4");
        $(".completion_number").text("5");
        $(".hide_completion_tab").css('display', 'none');
        $("#transaction_completion").css("display", "none");
    }
    else
    {
        $(".for_incorp").hide();
        $(".accept_date").hide();
        $(".accepted_date").prop("disabled", true);
        $(".effec_date").show();
        $(".effective_date").prop("disabled", false);
        $(".confirmation_number").text("3");
        $(".compilation_number").text("4");

        $(".for_other_services").css('display', 'none');
        $(".date_of_update_entry").css('display', 'none');
        $(".date_of_update_cont").css('display', 'none');
        $(".date_of_the_conf_received").prop("disabled", true);
        $(".date_of_entry_or_update").prop("disabled", true);
        $(".transaction_document_tab").css('display', '');
        $(".hide_completion_tab").css('display', '');
        $("#transaction_completion").css("display", "");
    }

    if($(this).val() == 0)
    {
        $("#transaction_data").children().remove();
        $(".effec_date").show();
        $(".effective_date").prop("disabled", false);
        $(".accept_date").hide();
        $(".accepted_date").prop("disabled", true);
        $(".uen_section").show();
        $(".client_section").hide();
        $(".client_name_section").hide();
        $(".client_type").prop("disabled", true);
        $(".dropdown_client_name").prop("disabled", true);
        $(".input_client_name").prop("disabled", true);

    }
    else if($(this).val() == 1)
    {
        getIncorporationInterface();

    }
    else if($(this).val() == 2)
    {
        $(".effec_date").show();
        $(".effective_date").prop("disabled", false);
        $(".accept_date").hide();
        $(".accepted_date").prop("disabled", true);
        $(".uen_section").show();
        $(".client_section").hide();
        $(".client_name_section").hide();
        $(".client_type").prop("disabled", true);
        $(".dropdown_client_name").prop("disabled", true);
        $(".input_client_name").prop("disabled", true);
        //getAppointNewDirectorInterface();
        if($(".uen_section #uen").val() != "")
        {
            $('#loadingWizardMessage').show();
            getAppointNewDirectorInterface();
        }
    }
    else if($(this).val() == 3 || $(this).val() == 33)
    {
        $(".effec_date").show();
        $(".effective_date").prop("disabled", false);
        $(".accept_date").hide();
        $(".accepted_date").prop("disabled", true);
        $(".uen_section").show();
        $(".client_section").hide();
        $(".client_name_section").hide();
        $(".client_type").prop("disabled", true);
        $(".dropdown_client_name").prop("disabled", true);
        $(".input_client_name").prop("disabled", true);
        $(".for_other_services").css('display', '');

        if($(".uen_section #uen").val() != "")
        {
            $('#loadingWizardMessage').show();
            getResignDirectorInterface();
        }
    }
    else if($(this).val() == 4)
    {
        $(".effec_date").show();
        $(".effective_date").prop("disabled", false);
        $(".accept_date").hide();
        $(".accepted_date").prop("disabled", true);
        $(".uen_section").show();
        $(".client_section").hide();
        $(".client_name_section").hide();
        $(".client_type").prop("disabled", true);
        $(".dropdown_client_name").prop("disabled", true);
        $(".input_client_name").prop("disabled", true);
        $(".for_other_services").css('display', '');
        //getChangeRegOfisInterface();
        if($(".uen_section #uen").val() != "")
        {
            $('#loadingWizardMessage').show();
            getChangeRegOfisInterface();
        }
    }
    else if($(this).val() == 5)
    {
        $(".effec_date").show();
        $(".effective_date").prop("disabled", false);
        $(".accept_date").hide();
        $(".accepted_date").prop("disabled", true);
        $(".uen_section").show();
        $(".client_section").hide();
        $(".client_name_section").hide();
        $(".client_type").prop("disabled", true);
        $(".dropdown_client_name").prop("disabled", true);
        $(".input_client_name").prop("disabled", true);
        $(".for_other_services").css('display', '');
        //getChangeBizActivityInterface();
        if($(".uen_section #uen").val() != "")
        {
            $('#loadingWizardMessage').show();
            getChangeBizActivityInterface();
        }
        
    }
    else if($(this).val() == 6)
    {
        $(".effec_date").show();
        $(".effective_date").prop("disabled", false);
        $(".accept_date").hide();
        $(".accepted_date").prop("disabled", true);
        $(".uen_section").show();
        $(".client_section").hide();
        $(".client_name_section").hide();
        $(".client_type").prop("disabled", true);
        $(".dropdown_client_name").prop("disabled", true);
        $(".input_client_name").prop("disabled", true);
        $(".for_other_services").css('display', '');
        //getChangeFYEInterface();
        if($(".uen_section #uen").val() != "")
        {
            $('#loadingWizardMessage').show();
            getChangeFYEInterface();
        }
    }
    else if($(this).val() == 7)
    {
        $(".effec_date").show();
        $(".effective_date").prop("disabled", false);
        $(".accept_date").hide();
        $(".accepted_date").prop("disabled", true);
        $(".uen_section").show();
        $(".client_section").hide();
        $(".client_name_section").hide();
        $(".client_type").prop("disabled", true);
        $(".dropdown_client_name").prop("disabled", true);
        $(".input_client_name").prop("disabled", true);
        $(".for_other_services").css('display', '');
        //getAppointNewAuditorInterface();
        if($(".uen_section #uen").val() != "")
        {
            $('#loadingWizardMessage').show();
            getAppointNewAuditorInterface();
        }
    }
    else if($(this).val() == 8)
    {
        $(".effec_date").hide();
        $(".effective_date").prop("disabled", true);
        $(".accept_date").hide();
        $(".accepted_date").prop("disabled", true);
        $(".uen_section").show();
        $(".client_section").hide();
        $(".client_name_section").hide();
        $(".client_type").prop("disabled", true);
        $(".dropdown_client_name").prop("disabled", true);
        $(".input_client_name").prop("disabled", true);
        if($(".uen_section #uen").val() != "")
        {
            $('#loadingWizardMessage').show();
            getIssueDividendInterface();
        }
    }
    else if($(this).val() == 9)
    {
        $(".effec_date").hide();
        $(".effective_date").prop("disabled", true);
        $(".accept_date").hide();
        $(".accepted_date").prop("disabled", true);
        $(".uen_section").show();
        $(".client_section").hide();
        $(".client_name_section").hide();
        $(".client_type").prop("disabled", true);
        $(".dropdown_client_name").prop("disabled", true);
        $(".input_client_name").prop("disabled", true);
        if($(".uen_section #uen").val() != "")
        {
            $('#loadingWizardMessage').show();
            getIssueDirectorFeeInterface();
        }
    }
    else if($(this).val() == 10)
    {
        $(".effec_date").show();
        $(".effective_date").prop("disabled", false);
        $(".accept_date").hide();
        $(".accepted_date").prop("disabled", true);
        $(".uen_section").show();
        $(".client_section").hide();
        $(".client_name_section").hide();
        $(".client_type").prop("disabled", true);
        $(".dropdown_client_name").prop("disabled", true);
        $(".input_client_name").prop("disabled", true);
        //getShareTransferInterface();
        if($(".uen_section #uen").val() != "")
        {
            $('#loadingWizardMessage').show();
            getShareTransferInterface();
        }
    }
    else if($(this).val() == 11)
    {
        $(".effec_date").show();
        $(".effective_date").prop("disabled", false);
        $(".accept_date").hide();
        $(".accepted_date").prop("disabled", true);
        $(".uen_section").show();
        $(".client_section").hide();
        $(".client_name_section").hide();
        $(".client_type").prop("disabled", true);
        $(".dropdown_client_name").prop("disabled", true);
        $(".input_client_name").prop("disabled", true);
        //getShareAllotmentInterface();
        if($(".uen_section #uen").val() != "")
        {
            $('#loadingWizardMessage').show();
            getShareAllotmentInterface();
        }
    }
    else if($(this).val() == 12)
    {
        $(".effec_date").show();
        $(".effective_date").prop("disabled", false);
        $(".accept_date").hide();
        $(".accepted_date").prop("disabled", true);
        $(".uen_section").show();
        $(".client_section").hide();
        $(".client_name_section").hide();
        $(".client_type").prop("disabled", true);
        $(".dropdown_client_name").prop("disabled", true);
        $(".input_client_name").prop("disabled", true);
        $(".for_other_services").css('display', '');
        //getChangeCompanyNameInterface();
        if($(".uen_section #uen").val() != "")
        {
            $('#loadingWizardMessage').show();
            getChangeCompanyNameInterface();
        }
    }
    else if($(this).val() == 15)
    {
        $(".effec_date").show();
        $(".effective_date").prop("disabled", false);
        $(".accept_date").hide();
        $(".accepted_date").prop("disabled", true);
        $(".uen_section").show();
        $(".client_section").hide();
        $(".client_name_section").hide();
        $(".client_type").prop("disabled", true);
        $(".dropdown_client_name").prop("disabled", true);
        $(".input_client_name").prop("disabled", true);
        $(".for_other_services").css('display', '');
        //getChangeAgmArInterface();
        if($(".uen_section #uen").val() != "")
        {
            $('#loadingWizardMessage').show();
            getChangeAgmArInterface();
        }
    }
    else if($(this).val() == 16)
    {
        $(".effec_date").show();
        $(".effective_date").prop("disabled", false);
        $(".accept_date").hide();
        $(".accepted_date").prop("disabled", true);
        $(".uen_section").show();
        $(".client_section").hide();
        $(".client_name_section").hide();
        $(".client_type").prop("disabled", true);
        $(".dropdown_client_name").prop("disabled", true);
        $(".input_client_name").prop("disabled", true);
        if($(".uen_section #uen").val() != "")
        {
            $('#loadingWizardMessage').show();
            getOpeningBankAccountInterface();
        }
    }
    else if($(this).val() == 20)
    {
        $(".effec_date").show();
        $(".effective_date").prop("disabled", false);
        $(".accept_date").hide();
        $(".accepted_date").prop("disabled", true);
        $(".uen_section").show();
        $(".client_section").hide();
        $(".client_name_section").hide();
        $(".client_type").prop("disabled", true);
        $(".dropdown_client_name").prop("disabled", true);
        $(".input_client_name").prop("disabled", true);
        if($(".uen_section #uen").val() != "")
        {
            $('#loadingWizardMessage').show();
            getIncorporationSubsidiaryInterface();
        }
    }
    else if($(this).val() == 24 || $(this).val() == 34)
    {
        $(".effec_date").show();
        $(".effective_date").prop("disabled", false);
        $(".accept_date").hide();
        $(".accepted_date").prop("disabled", true);
        $(".uen_section").show();
        $(".client_section").hide();
        $(".client_name_section").hide();
        $(".client_type").prop("disabled", true);
        $(".dropdown_client_name").prop("disabled", true);
        $(".input_client_name").prop("disabled", true);
        $(".for_other_services").css('display', '');
        if($(".uen_section #uen").val() != "")
        {
            $('#loadingWizardMessage').show();
            getAppointmentSecretarialInterface();
        }
    }
    else if($(this).val() == 26)
    {
        $(".effec_date").show();
        $(".effective_date").prop("disabled", false);
        $(".accept_date").hide();
        $(".accepted_date").prop("disabled", true);
        $(".uen_section").show();
        $(".client_section").hide();
        $(".client_name_section").hide();
        $(".client_type").prop("disabled", true);
        $(".dropdown_client_name").prop("disabled", true);
        $(".input_client_name").prop("disabled", true);
        $(".for_other_services").css('display', '');

        if($(".uen_section #uen").val() != "")
        {
            $('#loadingWizardMessage').show();
            getStrikeOffInterface();
        }
    }
    else if($(this).val() == 28)
    {
        $(".effec_date").show();
        $(".effective_date").prop("disabled", false);
        $(".accept_date").hide();
        $(".accepted_date").prop("disabled", true);
        //getTakeOverOfSecretarialInterface();
        
    }
    else if($(this).val() == 29)
    {
        get_client_name_interface(transaction_client_type_id);
        $(".effec_date").hide();
        $(".effective_date").prop("disabled", true);
        $(".accept_date").show();
        $(".accepted_date").prop("disabled", false);
        $(".uen_section").hide();
        $(".client_section").show();
        $(".client_type").prop("disabled", false);
    }
    else if($(this).val() == 30)
    {
        get_el_client_name_interface();
        $(".effec_date").hide();
        $(".effective_date").prop("disabled", true);
        $(".accept_date").show();
        $(".accepted_date").prop("disabled", false);
        $(".uen_section").hide();
        $(".client_section").hide();
        $(".client_name_section").show();
        $(".dropdown_client_name").prop("disabled", false);
        $(".dropdown_client_name").show();
        $(".input_client_name").prop("disabled", true);
        $(".input_client_name").hide();
    }
    else if($(this).val() == 31 || $(this).val() == 32)
    {
        //$(".effec_date").show();
        //$(".effective_date").prop("disabled", false);
        $(".for_other_services").css('display', '');
        $(".date_of_update_entry").css('display', '');
        if($(this).val() == 31)
        {
            $(".date_of_update_cont").css('display', '');
        }
        else
        {
            $(".date_of_update_cont").css('display', 'none');
        }
        $(".date_of_the_conf_received").prop("disabled", false);
        $(".date_of_entry_or_update").prop("disabled", false);
        $(".accept_date").hide();
        $(".accepted_date").prop("disabled", true);
        $(".uen_section").show();
        $(".client_section").hide();
        $(".client_name_section").hide();
        $(".client_type").prop("disabled", true);
        $(".dropdown_client_name").prop("disabled", true);
        $(".input_client_name").prop("disabled", true);
        if($(".uen_section #uen").val() != "" && $(this).val() == 31)
        {
            $('#loadingWizardMessage').show();
            getUpdateRegisterofController();
        }
        else if($(".uen_section #uen").val() != "" && $(this).val() == 32)
        {
            $('#loadingWizardMessage').show();
            getUpdateRegisterofNomineeDirector();
        }
    }
    else if($(this).val() == 35)
    {
        get_client_name_interface(transaction_client_type_id);
        $(".effec_date").hide();
        $(".effective_date").prop("disabled", true);
        $(".accept_date").show();
        $(".accepted_date").prop("disabled", false);
        $(".uen_section").hide();
        $(".client_section").show();
        $(".client_type").prop("disabled", false);
    }
    else if($(this).val() == 36)
    {
        //get_client_name_interface(transaction_client_type_id);
        $(".effec_date").hide();
        $(".effective_date").prop("disabled", true);
        $(".accept_date").show();
        $(".accepted_date").prop("disabled", false);
        $(".uen_section").hide();
        $(".client_section").hide();
        $(".client_type").prop("disabled", false);
        $(".for_other_services").css('display', '');
        getPurchaseCommonSealInterface();
    }
    else
    {
        $("#transaction_data").children().remove();
        $(".uen_section").show();
    }
});

$(".client_name_section .dropdown_client_name").change(function(){

    if($("#transaction_task").val() == 29)
    {
        $('#loadingWizardMessage').show();
        getServiceProposalInterface();
    }
    else if($("#transaction_task").val() == 30)
    {
        $('#loadingWizardMessage').show();
        getEngagementLetterInterface();
    }
    else if($("#transaction_task").val() == 35)
    {
        $('#loadingWizardMessage').show();
        getOMPGrantInterface();
    }
});

$(".client_name_section .input_client_name").change(function(){

    if($("#transaction_task").val() == 29)
    {
        $('#loadingWizardMessage').show();
        getServiceProposalInterface();
    }
    else if($("#transaction_task").val() == 35)
    {
        $('#loadingWizardMessage').show();
        getOMPGrantInterface();
    }
});

$(".uen_section #uen").change(function(){
    if($("#transaction_task").val() == 2)
    {
        $('#loadingWizardMessage').show();
        getAppointNewDirectorInterface();
    }
    else if($("#transaction_task").val() == 3)
    {
        $('#loadingWizardMessage').show();
        getResignDirectorInterface();
    }
    else if($("#transaction_task").val() == 4)
    {
        $('#loadingWizardMessage').show();
        getChangeRegOfisInterface();
    }
    else if($("#transaction_task").val() == 5)
    {
        $('#loadingWizardMessage').show();
        getChangeBizActivityInterface();
    }
    else if($("#transaction_task").val() == 6)
    {
        $('#loadingWizardMessage').show();
        getChangeFYEInterface();
    }
    else if($("#transaction_task").val() == 7)
    {
        $('#loadingWizardMessage').show();
        getAppointNewAuditorInterface();
    }
    else if($("#transaction_task").val() == 8)
    {
        $('#loadingWizardMessage').show();
        getIssueDividendInterface();
    }
    else if($("#transaction_task").val() == 9)
    {
        $('#loadingWizardMessage').show();
        getIssueDirectorFeeInterface();
    }
    else if($("#transaction_task").val() == 10)
    {
        $('#loadingWizardMessage').show();
        getShareTransferInterface();
    }
    else if($("#transaction_task").val() == 11)
    {
        $('#loadingWizardMessage').show();
        getShareAllotmentInterface();
    }
    else if($("#transaction_task").val() == 12)
    {
        $('#loadingWizardMessage').show();
        getChangeCompanyNameInterface();
    }
    else if($("#transaction_task").val() == 15)
    {
        $('#loadingWizardMessage').show();
        getChangeAgmArInterface();
    }
    else if($("#transaction_task").val() == 16)
    {
        $('#loadingWizardMessage').show();
        getOpeningBankAccountInterface();
    }
    else if($("#transaction_task").val() == 20)
    {
        $('#loadingWizardMessage').show();
        getIncorporationSubsidiaryInterface();
    }
    else if($("#transaction_task").val() == 24)
    {
        $('#loadingWizardMessage').show();
        getAppointmentSecretarialInterface();
    }
    else if($("#transaction_task").val() == 26)
    {
        $('#loadingWizardMessage').show();
        getStrikeOffInterface();
    }
    else if($("#transaction_task").val() == 31)
    {
        $('#loadingWizardMessage').show();
        getUpdateRegisterofController();
    }
    else if($("#transaction_task").val() == 32)
    {
        $('#loadingWizardMessage').show();
        getUpdateRegisterofNomineeDirector();
    }
    else if($("#transaction_task").val() == 33)
    {
        $('#loadingWizardMessage').show();
        getResignDirectorInterface();
    }
    else if($("#transaction_task").val() == 34)
    {
        $('#loadingWizardMessage').show();
        getAppointmentSecretarialInterface();
    }
});

function checkPreviousServices( jQuery ) {
    // Code to run when the document is ready.
    $.post("transaction/check_previous_transaction", {id: transaction_id, company_code: transaction_company_code, transaction_task_id: $(".transaction_task_section #transaction_task").val(), registration_no: $(".uen_section #uen").val()}, function(data){
        var previous_status_json = JSON.parse(data);
        if(previous_status_json["status"] == 1)
        {
            bootbox.dialog({
                "message": "Service Created. To view <a href='transaction/edit/"+previous_status_json["transaction_master_id"]+"'>link</a> or continue to create?",
                "closeButton": false,
                "buttons": {
                    "success": {
                       label: "Continue",
                       className: "btn-primary",
                       callback: function () {}
                    }
                }  
            });
        }
    });
}

function editShareTransfer(transaction_share_member_id)
{
    $.post("transaction/edit_share_transfer_page", {id: $("#transaction_trans #transaction_master_id").val(), company_code: transaction_company_code, transaction_task_id: transaction_task_id, registration_no: $(".uen_section #uen").val(), transaction_share_member_id: transaction_share_member_id}, function(data){
        array_for_share_transfer = JSON.parse(data);

        edit_transaction_share_transfer = array_for_share_transfer[0]["transaction_share_transfer"];

        $('#class option[value="'+edit_transaction_share_transfer[0]["share_capital_id"]+'"]').attr('selected', true)
        $("#currency").val($("#class").find("option:selected").data('currency'));
        $("#client_member_share_capital_id").val($("#class").find("option:selected").val());

        get_transfer_from_people();

        $("#total_from").text("0");
        $("#total_to").text("0");

        $("DIV.transfer").remove();
        $("DIV.to").remove();

        $('#table_transfer_to').show();
        $('.to').show();
        $('#total_share_transfer_to').show();

        shareTransferInterface(edit_transaction_share_transfer);
    });

} 

function dispense_agm()
{
    if(bool_dispense_agm)
    {
        $(".agm_date_info").prop('disabled', true);
        $(".agm_date_info").val("dispensed");
        bool_dispense_agm = false;

        $("#bottom_interface").hide();
    }
    else
    {
        if(new Date("8/31/2018") >= new Date($('#year_end_date').val()))
        {
            $(".dispense_agm_button").text("Dispense AGM");
        }

        $(".agm_date_info").prop('disabled', false);
        $(".agm_date_info").val("");
        bool_dispense_agm = true;

        $("#bottom_interface").show();
    }
}

function getChangeAgmArInterface()
{
    $("#transaction_data .panel").remove();
    $.post("transaction/get_agm_ar_page", {id: transaction_id, company_code: transaction_company_code, transaction_task_id: transaction_task_id, registration_no: $(".uen_section #uen").val()}, function(data){
        array_for_agm_ar = JSON.parse(data);

        if(array_for_agm_ar['error'] == null)
        {
            transaction_agm_ar = array_for_agm_ar[0]["transaction_agm_ar"];
            previous_transaction_agm_ar = array_for_agm_ar[0]["previous_transaction_agm_ar"];
            transaction_agm_ar_director_fee = array_for_agm_ar[0]["transaction_agm_ar_director_fee"];
            currency = array_for_agm_ar[0]["currency"];
            //transaction_agm_ar_dividend = array_for_agm_ar[0]["transaction_agm_ar_dividend"];
            transaction_agm_ar_amount_due = array_for_agm_ar[0]["transaction_agm_ar_amount_due"];
            transaction_agm_ar_director_retire = array_for_agm_ar[0]["transaction_agm_ar_director_retire"];
            transaction_agm_ar_reappoint_auditor = array_for_agm_ar[0]["transaction_agm_ar_reappoint_auditor"];
            check_is_first_agm = array_for_agm_ar[0]["check_is_first_agm"];
            exemption = array_for_agm_ar[0]["exemption"];
            regis_controller_is_kept = array_for_agm_ar[0]["regis_controller_is_kept"];
            regis_nominee_dir_is_kept = array_for_agm_ar[0]["regis_nominee_dir_is_kept"];
            check_have_share_transfer = array_for_agm_ar[0]["check_have_share_transfer"];
            activity_status = array_for_agm_ar[0]["activity_status"];
            epc_status = array_for_agm_ar[0]["epc_status"];
            epc_status_value = array_for_agm_ar[0]["epc_status_value"];
            solvency_status = array_for_agm_ar[0]["solvency_status"];
            small_company = array_for_agm_ar[0]["small_company"];
            audited_financial_statement = array_for_agm_ar[0]["audited_financial_statement"];
            filing_info = array_for_agm_ar[0]["filing_info"];
            company_type = array_for_agm_ar[0]["company_type"];
            first_agm = array_for_agm_ar[0]["first_agm"];
            xbrl_list = array_for_agm_ar[0]["xbrl_list"];
            require_hold_agm_list = array_for_agm_ar[0]["require_hold_agm_list"];
            agm_share_transfer = array_for_agm_ar[0]["agm_share_transfer"];
            consent_for_shorter_notice = array_for_agm_ar[0]["consent_for_shorter_notice"];

            $("#transaction_data").append(array_for_agm_ar['interface']);

            $(".fye_date").prop("disabled", true);

            $('.agm_date_info').datepicker({ 
                dateFormat:'dd MM yyyy',
            });

            $('.notice_date').datepicker({ 
                dateFormat:'dd MM yyyy',
            });

            $('.date_fs_sent_to_member').datepicker({ 
                dateFormat:'dd MM yyyy',
            });

            $('#agm_time').datetimepicker({
                format: 'LT'
            });

            $("#agm_time").val("10:00 AM");

            $('#resolution_time').datetimepicker({
                format: 'LT'
            });

            $("#resolution_time").val("10:00 AM");
            agm_ar_meeting_table_info(array_for_agm_ar, transaction_agm_ar);

            if(exemption)
            {
                for(var i = 0; i < exemption.length; i++)
                {
                    var cont_option = $('<option />');
                    cont_option.attr('value', exemption[i]['id']).text(exemption[i]['exemption']);
                    if(transaction_agm_ar)
                    {
                        if(transaction_agm_ar[0]["cont_exemption_id"] != null && exemption[i]['id'] == transaction_agm_ar[0]["cont_exemption_id"])
                        {
                            cont_option.attr('selected', 'selected');

                        }
                    }
                    $("#controller_exempt").append(cont_option);

                    var dir_option = $('<option />');
                    dir_option.attr('value', exemption[i]['id']).text(exemption[i]['exemption']);
                    if(transaction_agm_ar)
                    {
                        if(transaction_agm_ar[0]["dir_exemption_id"] != null && exemption[i]['id'] == transaction_agm_ar[0]["dir_exemption_id"])
                        {
                            dir_option.attr('selected', 'selected');

                        }
                    }
                    $("#director_exempt").append(dir_option);
                }
                
            }

            if(regis_controller_is_kept)
            {
                for(var i = 0; i < regis_controller_is_kept.length; i++)
                {

                    var option = $('<option />');
                    option.attr('value', regis_controller_is_kept[i]['id']).text(regis_controller_is_kept[i]['cont_is_kept_at']);
                    if(transaction_agm_ar)
                    {
                        if(transaction_agm_ar[0]["regis_controller_is_kept_id"] != null && regis_controller_is_kept[i]['id'] == transaction_agm_ar[0]["regis_controller_is_kept_id"])
                        {
                            option.attr('selected', 'selected');

                        }
                    }

                    $("#controller_kept").append(option);
                }
                
            }

            if(regis_nominee_dir_is_kept)
            {
                for(var i = 0; i < regis_nominee_dir_is_kept.length; i++)
                {

                    var option = $('<option />');
                    option.attr('value', regis_nominee_dir_is_kept[i]['id']).text(regis_nominee_dir_is_kept[i]['dir_is_kept_at']);
                    if(transaction_agm_ar)
                    {
                        if(transaction_agm_ar[0]["regis_nominee_dir_is_kept_id"] != null && regis_nominee_dir_is_kept[i]['id'] == transaction_agm_ar[0]["regis_nominee_dir_is_kept_id"])
                        {
                            option.attr('selected', 'selected');

                        }
                    }

                    $("#director_kept").append(option);
                }
                
            }

            if(company_type)
            {
                for(var i = 0; i < company_type.length; i++)
                {
                    var option = $('<option />');
                    option.attr('value', company_type[i]['id']).text(company_type[i]['company_type']);
                    if(transaction_agm_ar)
                    {
                        if(transaction_agm_ar[0]["company_type"] != null && company_type[i]['id'] == transaction_agm_ar[0]["company_type"])
                        {
                            option.attr('selected', 'selected');
                        }
                    }
                    $("#company_type").append(option);
                }
            }

            if(xbrl_list)
            {
                for(var i = 0; i < xbrl_list.length; i++)
                {
                    var option = $('<option />');
                    option.attr('value', xbrl_list[i]['id']).text(xbrl_list[i]['xbrl_list_name']);
                    if(transaction_agm_ar)
                    {
                        if(transaction_agm_ar[0]["xbrl"] != null && xbrl_list[i]['id'] == transaction_agm_ar[0]["xbrl"])
                        {
                            option.attr('selected', 'selected');
                        }
                    }
                    $("#xbrl_list").append(option);
                }
            }

            if(require_hold_agm_list)
            {
                for(var i = 0; i < require_hold_agm_list.length; i++)
                {
                    var option = $('<option />');
                    option.attr('value', require_hold_agm_list[i]['id']).text(require_hold_agm_list[i]['require_hold_agm_list_name']);
                    if(transaction_agm_ar)
                    {
                        if(transaction_agm_ar[0]["require_hold_agm_list"] != null && require_hold_agm_list[i]['id'] == transaction_agm_ar[0]["require_hold_agm_list"])
                        {
                            option.attr('selected', 'selected');

                            // if(transaction_agm_ar[0]["require_hold_agm_list"] == 1)
                            // {
                            //     $(".agm_date_section").show();
                            // }
                            // else if(transaction_agm_ar[0]["require_hold_agm_list"] == 2)
                            // {
                            //     $(".date_fs_section").show();
                            // }

                            if(transaction_agm_ar[0]["require_hold_agm_list"] == 1)
                            {
                                $(".shorter_notice_section").show();
                                $(".notice_date_section").show();
                                $(".agm_time_section").show();
                                $(".agm_date_section").show();
                                $(".date_fs_section").hide();
                            }
                            else if(transaction_agm_ar[0]["require_hold_agm_list"] == 2 || transaction_agm_ar[0]["require_hold_agm_list"] == 4)
                            {
                                $(".shorter_notice_section").hide();
                                $(".notice_date_section").hide();
                                $(".agm_time_section").hide();
                                $(".agm_date_section").hide();
                                $(".date_fs_section").show();
                            }
                            else if(transaction_agm_ar[0]["require_hold_agm_list"] == 3)
                            {
                                $(".shorter_notice_section").hide();
                                $(".notice_date_section").hide();
                                $(".agm_time_section").hide();
                                $(".agm_date_section").hide();
                                $(".date_fs_section").hide();
                            }
                            else
                            {
                                $(".shorter_notice_section").show();
                                $(".notice_date_section").show();
                                $(".agm_time_section").show();
                                $(".agm_date_section").hide();
                                $(".date_fs_section").hide();
                            }
                        }
                    }
                    $("#require_hold_agm_list").append(option);
                }
            }
            if(first_agm)
            {
                for(var i = 0; i < first_agm.length; i++)
                {

                    var option = $('<option />');
                    option.attr('value', first_agm[i]['id']).text(first_agm[i]['is_first_agm']);
                    if(transaction_agm_ar)
                    {
                        if(transaction_agm_ar[0]["is_first_agm_id"] != null && first_agm[i]['id'] == transaction_agm_ar[0]["is_first_agm_id"])
                        {
                            option.attr('selected', 'selected');

                        }
                    }
                    else
                    {
                        if(check_is_first_agm.length == 0)
                        {
                            if(first_agm[i]['id'] == 1)
                            {
                                option.attr('selected', 'selected');

                            }
                        }
                        else
                        {
                            if(first_agm[i]['id'] == 2)
                            {
                                option.attr('selected', 'selected');

                            }
                        }
                        // else if(check_is_first_agm.length == 1)
                        // {
                        //     if(check_is_first_agm[0]["agm"] != "")
                        //     {
                        //         if(first_agm[i]['id'] == 2)
                        //         {
                        //             option.attr('selected', 'selected');

                        //         }
                        //     }
                        //     else
                        //     {
                        //         if(first_agm[i]['id'] == 1)
                        //         {
                        //             option.attr('selected', 'selected');

                        //         }
                        //     }
                        // }
                        
                    }

                    $("#first_agm").append(option);
                }
                
            }

            if(agm_share_transfer)
            {
                for(var i = 0; i < agm_share_transfer.length; i++)
                {

                    var option = $('<option />');
                    option.attr('value', agm_share_transfer[i]['id']).text(agm_share_transfer[i]['share_transfer_name']);
                    if(transaction_agm_ar)
                    {
                        if(transaction_agm_ar[0]["agm_share_transfer_id"] != null && agm_share_transfer[i]['id'] == transaction_agm_ar[0]["agm_share_transfer_id"])
                        {
                            option.attr('selected', 'selected');

                        }
                    }
                    else
                    {
                        if(check_have_share_transfer.length > 0)
                        {
                            if(agm_share_transfer[i]['id'] == 1)
                            {
                                option.attr('selected', 'selected');

                            }
                        }
                        else
                        {
                            if(agm_share_transfer[i]['id'] == 3)
                            {
                                option.attr('selected', 'selected');

                            }
                        }
                    }

                    $("#agm_share_transfer").append(option);
                }
            }

            if(consent_for_shorter_notice)
            {
                for(var i = 0; i < consent_for_shorter_notice.length; i++)
                {

                    var option = $('<option />');
                    option.attr('value', consent_for_shorter_notice[i]['id']).text(consent_for_shorter_notice[i]['is_shorter_notice']);
                    if(transaction_agm_ar)
                    {
                        if(transaction_agm_ar[0]["shorter_notice"] != null && consent_for_shorter_notice[i]['id'] == transaction_agm_ar[0]["shorter_notice"])
                        {
                            option.attr('selected', 'selected');

                        }
                    }
                    else
                    {
                        if(consent_for_shorter_notice[i]['id'] == 2)
                        {
                            option.attr('selected', 'selected');

                        }
                    }

                    $("#shorter_notice").append(option);
                }
            }

            if(activity_status)
            {
                for(var i = 0; i < activity_status.length; i++)
                {

                    var option = $('<option />');
                    option.attr('value', activity_status[i]['id']).text(activity_status[i]['activity_status_name']);
                    if(transaction_agm_ar)
                    {
                        if(transaction_agm_ar[0]["activity_status_id"] != null && activity_status[i]['id'] == transaction_agm_ar[0]["activity_status_id"])
                        {
                            option.attr('selected', 'selected');

                        }
                    }

                    $("#activity_status").append(option);
                }
                
            }

            if(solvency_status)
            {
                for(var i = 0; i < solvency_status.length; i++)
                {

                    var option = $('<option />');
                    option.attr('value', solvency_status[i]['id']).text(solvency_status[i]['solvency_status_name']);
                    if(transaction_agm_ar)
                    {
                        if(transaction_agm_ar[0]["solvency_status_id"] != null && solvency_status[i]['id'] == transaction_agm_ar[0]["solvency_status_id"])
                        {
                            option.attr('selected', 'selected');
                        }
                    }

                    $("#solvency_status").append(option);
                }
                
            }

            // if(epc_status)
            // {
            //     for(var i = 0; i < epc_status.length; i++)
            //     {

            //         var option = $('<option />');
            //         option.attr('value', epc_status[i]['id']).text(epc_status[i]['is_epc_status']);
            //         if(transaction_agm_ar)
            //         {
            //             if(transaction_agm_ar[0]["epc_status_id"] != null && epc_status[i]['id'] == transaction_agm_ar[0]["epc_status_id"])
            //             {
            //                 option.attr('selected', 'selected');
            //             }
            //         }
            //         else
            //         {
            //             if(epc_status_value != null && epc_status[i]['id'] == epc_status_value)
            //             {
            //                 option.attr('selected', 'selected');
            //             }
            //         }

            //         $("#epc_status").append(option);
            //     }
                
            // }

            if(small_company)
            {
                for(var i = 0; i < small_company.length; i++)
                {

                    var option = $('<option />');
                    option.attr('value', small_company[i]['id']).text(small_company[i]['small_company_decision']);
                    if(transaction_agm_ar)
                    {
                        if(transaction_agm_ar[0]["small_company_id"] != null && small_company[i]['id'] == transaction_agm_ar[0]["small_company_id"])
                        {
                            option.attr('selected', 'selected');
                        }
                    }

                    $("#small_company").append(option);
                }
                
            }

            if(audited_financial_statement)
            {
                for(var i = 0; i < audited_financial_statement.length; i++)
                {

                    var option = $('<option />');
                    option.attr('value', audited_financial_statement[i]['id']).text(audited_financial_statement[i]['audited_fs_decision']);
                    if(transaction_agm_ar)
                    {
                        if(transaction_agm_ar[0]["audited_fs_id"] != null && audited_financial_statement[i]['id'] == transaction_agm_ar[0]["audited_fs_id"])
                        {
                            option.attr('selected', 'selected');
                        }
                    }

                    $("#audited_fs").append(option);
                }
                
            }

            if(transaction_agm_ar)
            {   
                $(".transaction_agm_ar_id").val(transaction_agm_ar[0]['id']);

                $('.fye_date').datepicker({ 
                    dateFormat:'dd MM yyyy',
                }).datepicker('setDate', transaction_agm_ar[0]['year_end_date']);

                if(new Date("8/31/2018") >= new Date($('.fye_date').val()))
                {
                    $(".dispense_agm_button").text("Dispense AGM");
                }
                 else
                {
                    $(".dispense_agm_button").text("Hold AGM");
                }

                if(transaction_agm_ar[0]['agm_date'] == "dispensed")
                {
                    $(".agm_date_info").prop('disabled', true);
                    $("#bottom_interface").hide();
                }
                else
                {
                    $(".agm_date_info").prop('disabled', false);
                    $("#bottom_interface").show();
                }

                $(".agm_date_info").val(transaction_agm_ar[0]['agm_date']);

                $(".date_fs_sent_to_member").val(transaction_agm_ar[0]['date_fs_sent_to_member']);

                $("#agm_time").val(transaction_agm_ar[0]['agm_time']);

                $(".notice_date").val(transaction_agm_ar[0]['notice_date']);

                $("#resolution_time").val(transaction_agm_ar[0]['reso_time']);

                $("#activity_status").val(transaction_agm_ar[0]['activity_status']);

                $("#solvency_status").val(transaction_agm_ar[0]['solvency_status']);

                $("#small_company").val(transaction_agm_ar[0]['small_company']);

                if(transaction_agm_ar[0]['small_company'] == 2) //No
                {
                    //$("#audited_fs").val("1");
                    $("#audited_fs").prop("disabled", true);
                    //$('#reappointment_auditor').prop('checked', true);
                }

                if($("#company_type").val() == "2")
                {
                    $(".xbrl_list").attr("disabled", true);
                }

                $("#audited_fs").val(transaction_agm_ar[0]['audited_fs']);

                $("input[name='register_of_controller'][value='"+transaction_agm_ar[0]['register_of_controller']+"']").prop("checked",true);
                $("input[name='register_of_nominee_director'][value='"+transaction_agm_ar[0]['register_of_nominee_director']+"']").prop("checked",true);
                //shareAgmArInterface(transaction_agm_ar);
            }
            else
            {   
                if(filing_info)
                {
                    $('.fye_date').datepicker({ 
                        dateFormat:'dd MM yyyy',
                    }).datepicker('setDate', filing_info[0]['year_end']);

                    $("#company_type").val(filing_info[0]['company_type']);

                    if(filing_info[0]['company_type'] == "2")
                    {
                        $(".xbrl_list").val("1");
                        $(".xbrl_list").attr("disabled", true);
                    }
                }

                // if(new Date("8/31/2018") >= new Date($('.fye_date').val()))
                // {
                    $(".dispense_agm_button").text("Dispense AGM");

                    $(".agm_date_info").val("");
                    $(".agm_date_info").prop('disabled', false);
                    bool_dispense_agm = true;
                    $("#bottom_interface").show();
                // }
                // else
                // {
                //     $(".dispense_agm_button").text("Hold AGM");
                //     $(".agm_date_info").val("dispensed");
                //     $(".agm_date_info").prop('disabled', true);
                //     bool_dispense_agm = false;
                //     $("#bottom_interface").hide();
                // }

                if(previous_transaction_agm_ar)
                {
                    $("#controller_exempt").val(previous_transaction_agm_ar[0]['cont_exemption_id']);
                    $("#director_exempt").val(previous_transaction_agm_ar[0]['dir_exemption_id']);
                    $("#controller_kept").val(previous_transaction_agm_ar[0]['regis_controller_is_kept_id']);
                    $("#director_kept").val(previous_transaction_agm_ar[0]['regis_nominee_dir_is_kept_id']);
                    $("#activity_status").val(previous_transaction_agm_ar[0]['activity_status']);
                    $("#solvency_status").val(previous_transaction_agm_ar[0]['solvency_status']);
                    $("#small_company").val(previous_transaction_agm_ar[0]['small_company']);
                    $("#audited_fs").val(previous_transaction_agm_ar[0]['audited_fs']);
                }
            }

            

            if(transaction_agm_ar_director_fee)
            {
                if(transaction_agm_ar_director_fee[0]["id"] != null)
                {
                    $('#director_fee').prop('checked', true);
                    transactionAgmArDirectorFeeInterface(transaction_agm_ar_director_fee, currency);
                }
            }

            // if(transaction_agm_ar_dividend)
            // {
            //     if(transaction_agm_ar_dividend[0]["id"] != null)
            //     {
            //         $('#dividend').prop('checked', true);
            //         transactionAgmArDividendInterface(transaction_agm_ar_dividend);
            //     }
            // }

            if(transaction_agm_ar_amount_due)
            {
                if(transaction_agm_ar_amount_due[0]["id"] != null)
                {
                    $('#amount_due_from_director').prop('checked', true);
                    transactionAgmArAmountDueInterface(transaction_agm_ar_amount_due);
                }

            }

            if(transaction_agm_ar_director_retire)
            {
                if(transaction_agm_ar_director_retire[0]["id"] != null)
                {
                    $('#director_retirement').prop('checked', true);
                    transactionAgmArDirectorRetireInterface(transaction_agm_ar_director_retire);
                }

            }

            if(transaction_agm_ar_reappoint_auditor)
            {
                if(transaction_agm_ar_reappoint_auditor[0]["id"] != null)
                {
                    $('#reappointment_auditor').prop('checked', true);
                    transactionAgmArReappointAuditorInterface(transaction_agm_ar_reappoint_auditor);
                }

            }
            
            if($(".uen_section #uen").val() != "")
            {
                $.ajax({ //Upload common input
                  url: "transaction/check_client_info",
                  type: "POST",
                  data: {"registration_no": $(".uen_section #uen").val()},
                  dataType: 'json',
                  async: false,
                  success: function (response,data) {
                    $('#loadingWizardMessage').hide();
                        if(response)
                        {   
                            $(".trans_company_code").val(response[0]["company_code"]);
                            $("#w2-agm_ar_form #company_code").val(response[0]["company_code"]);
                            transaction_company_code = response[0]["company_code"];

                            $("#company_name").append(response[0]['company_name']);
                            $(".hidden_company_name").val(response[0]['company_name']);

                            if(transaction_agm_ar)
                            {
                                tcm1.getAllChairman(transaction_agm_ar[0]["chairman"]);
                            }
                            else
                            {   
                                if(previous_transaction_agm_ar)
                                {
                                    tcm1.getAllChairman(previous_transaction_agm_ar[0]['chairman']);
                                }
                                else
                                {
                                    tcm1.getAllChairman(null);
                                }
                            }
                            if(transaction_id == undefined)
                            {
                                $( document ).ready( checkPreviousServices );
                            }
                        }
                        else
                        {
                            toastr.error("Please enter the correct Registration No", "Error");
                        }
                    }
                });
            }
            else
            {
                $('#loadingWizardMessage').hide();
                
            }
            
            


            loadLastTab();
        }
        else
        {
            $('#loadingWizardMessage').hide();
            toastr.error(array_for_agm_ar['error'], "Error");
        }
    });
    
}

function transactionAgmArDirectorFeeInterface(transaction_agm_ar_director_fee, currency)
{
    if(transaction_agm_ar_director_fee.length > 0)
    {
        for(var i = 0; i < transaction_agm_ar_director_fee.length; i++)
        {
            // $a="";
            // $a += '<tr class="row_director_fee">';
            // $a += '<td><input type="text" style="text-transform:uppercase;" name="director_fee_name[]" id="name" class="form-control" value="'+ transaction_agm_ar_director_fee[i]["director_fee_name"] +'" readonly/><input type="hidden" class="form-control" name="director_fee_identification_register_no[]" id="director_fee_identification_register_no" value="'+ transaction_agm_ar_director_fee[i]["director_fee_identification_no"] +'"/><div class="hidden"><input type="text" class="form-control" name="director_fee_client_officer_id[]" id="client_officer_id" value=""/></div><div class="hidden"><input type="text" class="form-control" name="director_fee_officer_id[]" id="officer_id" value="'+transaction_agm_ar_director_fee[i]["director_fee_officer_id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="director_fee_officer_field_type[]" id="officer_field_type" value="'+transaction_agm_ar_director_fee[i]["director_fee_officer_field_type"]+'"/></div></td>';
            // $a += '<td><input type="text" style="text-transform:uppercase;" name="director_fee[]" id="director_fee numberdes" class="form-control" value="'+addCommas(transaction_agm_ar_director_fee[i]["director_fee"])+'"/></td>'
            // $a += '</tr>';

            // $("#director_fee_add").append($a);

            $a = "";
            $a += '<tr class="row_director_fee director_fee'+i+'">';
            $a += '<td><input type="text" style="text-transform:uppercase;" name="director_fee_name[]" id="name" class="form-control" value="'+ transaction_agm_ar_director_fee[i]["director_fee_name"] +'" readonly/><input type="hidden" class="form-control" name="director_fee_identification_register_no[]" id="director_fee_identification_register_no" value="'+ transaction_agm_ar_director_fee[i]["director_fee_identification_no"] +'"/><div class="hidden"><input type="text" class="form-control" name="director_fee_client_officer_id[]" id="client_officer_id" value=""/></div><div class="hidden"><input type="text" class="form-control" name="director_fee_officer_id[]" id="officer_id" value="'+transaction_agm_ar_director_fee[i]["director_fee_officer_id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="director_fee_officer_field_type[]" id="officer_field_type" value="'+transaction_agm_ar_director_fee[i]["director_fee_officer_field_type"]+'"/></div></td>';
            $a += '<td><select class="form-control currency" name="currency[]" id="currency'+i+'"><option value="0">Select Currency</option></select></td>';
            $a += '<td><input type="text" style="text-align:right;" name="salary[]" id="salary'+i+'" class="numberdes form-control salary" value="'+addCommas(transaction_agm_ar_director_fee[i]["salary"])+'"/></td>';
            $a += '<td><input type="text" style="text-align:right;" name="cpf[]" id="cpf'+i+'" class="numberdes form-control cpf" value="'+addCommas(transaction_agm_ar_director_fee[i]["cpf"])+'"/></td>';
            $a += '<td><input type="text" style="text-align:right;" name="director_fee[]" id="director_fee'+i+'" class="numberdes form-control director_fee" value="'+addCommas(transaction_agm_ar_director_fee[i]["director_fee"])+'"/></td>';
            $a += '<td><input type="text" style="text-align:right;" name="total_director_fee[]" id="total_director_fee'+i+'" class="numberdes form-control total_director_fee" value="'+addCommas(transaction_agm_ar_director_fee[i]["total_director_fee"])+'" readonly="true"/></td>';
            $a += '</tr>';

            $("#director_fee_add").append($a);

            $.each(currency, function(key, val) {
                var option = $('<option />');
                option.attr('value', key).text(val);
                if(transaction_agm_ar_director_fee[i]["currency_id"] == key)
                {
                    option.attr('selected', 'selected');
                }
                $(".director_fee"+i).find(".currency").append(option);
            });
        }
    }
    else
    {
        $z = ""; 
        $z += '<tr class="row_director_fee">';
        $z += '<td colspan="6" style="text-align:center;"><span style="font-weight:bold; font-size:20px;">N/A</span></td>';
        $z += '</tr>';

        $("#director_fee_add").append($z);
    }

    $(".director_fee_div").show();
}

// function transactionAgmArDividendInterface(transaction_agm_ar_dividend)
// {
//     for(var i = 0; i < transaction_agm_ar_dividend.length; i++)
//     {
//         $a="";
//         $a += '<tr class="row_dividend" data-numberOfShare="'+transaction_agm_ar_dividend[i]["number_of_share"]+'">';
//         $a += '<td><input type="text" style="text-transform:uppercase;" name="dividend_name[]" id="name" class="form-control" value="'+transaction_agm_ar_dividend[i]["dividend_name"] +'" readonly/><input type="hidden" class="form-control" name="dividend_identification_register_no[]" id="dividend_identification_register_no" value="'+ transaction_agm_ar_dividend[i]["dividend_identification_no"]+'"/><div class="hidden"><input type="text" class="form-control" name="dividend_client_officer_id[]" id="client_officer_id" value=""/></div><div class="hidden"><input type="text" class="form-control" name="dividend_officer_id[]" id="officer_id" value="'+transaction_agm_ar_dividend[i]["dividend_officer_id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="dividend_officer_field_type[]" id="officer_field_type" value="'+transaction_agm_ar_dividend[i]["dividend_officer_field_type"]+'"/></div></td>';
//         $a += '<td><input type="text" style="text-transform:uppercase;" name="dividend[]" id="dividend_fee" class="form-control" value="'+addCommas(transaction_agm_ar_dividend[i]["dividend_fee"])+'" readonly/><input type="hidden" name="number_of_share[]" id="number_of_share" class="form-control" value="'+transaction_agm_ar_dividend[i]["number_of_share"]+'"/></td>'
//         $a += '</tr>';

//         $("#dividend_add").append($a);

//         total_number_of_share = total_number_of_share + parseInt(transaction_agm_ar_dividend[i]["number_of_share"]);
//     }
//     $("#total_dividend").val(addCommas(transaction_agm_ar_dividend[0]["total_dividend_declared"]));
//     $(".dividend_div").show();
// }

function transactionAgmArAmountDueInterface(transaction_agm_ar_amount_due)
{
    for(var i = 0; i < transaction_agm_ar_amount_due.length; i++)
    {
        $a="";
        $a += '<tr class="row_amount_due">';
        $a += '<td><input type="text" style="text-transform:uppercase;" name="amount_due_name[]" id="name" class="form-control" value="'+ transaction_agm_ar_amount_due[i]["amount_due_from_director_name"] +'" readonly/><input type="hidden" class="form-control" name="amount_due_identification_register_no[]" id="amount_due_identification_register_no" value="'+ transaction_agm_ar_amount_due[i]["amount_due_from_director_identification_no"] +'"/><div class="hidden"><input type="text" class="form-control" name="amount_due_client_officer_id[]" id="client_officer_id" value=""/></div><div class="hidden"><input type="text" class="form-control" name="amount_due_officer_id[]" id="officer_id" value="'+transaction_agm_ar_amount_due[i]["amount_due_from_director_officer_id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="amount_due_officer_field_type[]" id="officer_field_type" value="'+transaction_agm_ar_amount_due[i]["amount_due_from_director_officer_field_type"]+'"/></div></td>';
        $a += '<td><input type="text" style="text-transform:uppercase;" name="amount_due[]" id="amount_due" class="form-control numberdes" value="'+addCommas(transaction_agm_ar_amount_due[i]["amount_due_from_director_fee"])+'"/></td>';
        $a += '</tr>';

        $("#amount_due_from_director_add").append($a);

    }
    $(".amount_due_from_director_div").show();
}

function transactionAgmArDirectorRetireInterface(transaction_agm_ar_director_retire)
{
    for(var i = 0; i < transaction_agm_ar_director_retire.length; i++)
    {
        $a="";
        $a += '<tr class="row_director_retirement">';
        $a += '<td>'+(i + 1)+'<input type="hidden" class="form-control" name="director_retiring_client_officer_id[]" value="'+transaction_agm_ar_director_retire[i]['director_retire_officer_id']+'"/></td>';
        $a += '<td>'+transaction_agm_ar_director_retire[i]['director_retire_identification_no']+'<input type="hidden" class="form-control" name="director_retiring_identification_no[]" value="'+transaction_agm_ar_director_retire[i]['director_retire_identification_no']+'"/></td>';
        $a += '<td>'+transaction_agm_ar_director_retire[i]['director_retire_name']+'<input type="hidden" class="form-control" name="director_retiring_name[]" value="'+transaction_agm_ar_director_retire[i]['director_retire_name']+'"/></td>';
        $a += '<td><input type="checkbox" name="director_retiring_checkbox" '+((transaction_agm_ar_director_retire[i]["director_retiring_checkbox"] == "1")?'checked':'')+'/><input type="hidden" name="hidden_director_retiring_checkbox[]" value="'+transaction_agm_ar_director_retire[i]['director_retiring_checkbox']+'"/><input type="hidden" class="form-control" name="director_retiring_officer_id[]" value="'+transaction_agm_ar_director_retire[i]['director_retire_officer_id']+'"/><input type="hidden" class="form-control" name="director_retiring_field_type[]" value="'+transaction_agm_ar_director_retire[i]['director_retire_field_type']+'"/></td>';
        $a += '</tr>';

        $("#director_retirement_add").append($a);

        $("[name='director_retiring_checkbox']").bootstrapSwitch({
            //state: state_checkbox,
            size: 'small',
            onColor: 'primary',
            onText: 'YES',
            offText: 'NO',
            // Text of the center handle of the switch
            labelText: '&nbsp',
            // Width of the left and right sides in pixels
            handleWidth: '20px',
            // Width of the center handle in pixels
            labelWidth: 'auto',
            baseClass: 'bootstrap-switch',
            wrapperClass: 'wrapper'


        });

        // Triggered on switch state change.
        $("[name='director_retiring_checkbox']").on('switchChange.bootstrapSwitch', function(event, state) {
            if(state == true)
            {
                $(event.target).parent().parent().parent().find("[name='hidden_director_retiring_checkbox[]']").val(1);

            }
            else
            {
               $(event.target).parent().parent().parent().find("[name='hidden_director_retiring_checkbox[]']").val(0);
            }
        });
    }
    $(".director_retirement_div").show();
}

function transactionAgmArReappointAuditorInterface(transaction_agm_ar_reappoint_auditor)
{
    // for(var i = 0; i < transaction_agm_ar_reappoint_auditor.length; i++)
    // {
    //     $a="";
    //     $a += '<tr class="row_reappointment_auditor">';
    //     $a += '<td><input type="text" style="text-transform:uppercase;" name="reappointment_auditor_name[]" id="name" class="form-control" value="'+ transaction_agm_ar_reappoint_auditor[i]["reappoint_auditor_name"] +'" readonly/><input type="hidden" class="form-control" name="reappointment_auditor_identification_register_no[]" id="reappointment_auditor_identification_register_no" value="'+ transaction_agm_ar_reappoint_auditor[i]["reappoint_auditor_identification_no"] +'"/><div class="hidden"><input type="text" class="form-control" name="reappointment_auditor_client_officer_id[]" id="client_officer_id" value=""/></div><div class="hidden"><input type="text" class="form-control" name="reappointment_auditor_officer_id[]" id="officer_id" value="'+transaction_agm_ar_reappoint_auditor[i]["reappoint_auditor_officer_id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="reappointment_auditor_officer_field_type[]" id="officer_field_type" value="'+transaction_agm_ar_reappoint_auditor[i]["reappoint_field_type"]+'"/></div></td>';
    //     $a += '</tr>';

    //     $("#reappointment_auditor_add").append($a);
    // }
    $.post("transaction/get_resign_auditor_info", {id: transaction_id, company_code: transaction_company_code, transaction_task_id: transaction_task_id, registration_no: $(".uen_section #uen").val()}, function(data){
        
        $a="";
        $a += '<tr class="row_reappointment_auditor">';
        $a += '<input type="hidden" class="form-control" name="reappointment_auditor_client_officer_id[]" id="client_officer_id" value=""/><input type="hidden" class="form-control" name="reappointment_auditor_officer_id[]" id="officer_id" value=""/><input type="hidden" class="form-control" name="reappointment_auditor_officer_field_type[]" id="officer_field_type" value=""/><input type="hidden" class="form-control" name="reappointment_auditor_identification_register_no[]" id="reappointment_auditor_identification_register_no" value=""/>';
        $a += '<td><select class="form-control" style="text-align:right;" name="reappointment_auditor_name[]" id="reappointment_auditor_name"><option value="0" data-client_officer_id="" data-officer_id="" data-officer_field_type="" data-identification_register_no=""></option></select></td>';
        $a += '</tr>';
        $("#reappointment_auditor_add").append($a);

        if(data)
        {
            var array_for_auditor_info = JSON.parse(data);

            $.each(array_for_auditor_info, function(key, val) {
                var option = $('<option />');
                var auditor_name = (val["company_name"]!=null ? val["company_name"] : val["name"]);
                var identification_no = (val["identification_no"]!=null ? val["identification_no"] : val["register_no"]);
                option.attr('value', auditor_name).text(auditor_name);
                option.attr('data-client_officer_id', val["id"]);
                option.attr('data-officer_id', val["officer_id"]);
                option.attr('data-officer_field_type', val["field_type"]);
                option.attr('data-identification_register_no',identification_no);

                if(auditor_name == transaction_agm_ar_reappoint_auditor[0]["reappoint_auditor_name"])
                {
                    option.attr('selected', 'selected');
                }
                $("#reappointment_auditor_name").append(option);
            });
        }
        $(".reappointment_auditor_div").show();
    });
}

function getShareTransferInterface()
{
    $("#transaction_data .panel").remove();
    $.post("transaction/get_share_transfer_page", {id: transaction_id, company_code: transaction_company_code, transaction_task_id: transaction_task_id, registration_no: $(".uen_section #uen").val()}, function(data){
        array_for_share_transfer = JSON.parse(data);

        if(array_for_share_transfer['error'] == null)
        {

            transaction_share_transfer = array_for_share_transfer[0]["transaction_share_transfer"];
            company_class = array_for_share_transfer[0]["company_class"];

            $("#transaction_data").append(array_for_share_transfer['interface']);
            //meeting_table_info(transaction_share_transfer, array_for_share_transfer);
            if(company_class)
            {
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
                    else if(company_class[i]['sharetype'] == "Preferred Share")
                    {
                        shareClass = company_class[i]['sharetype'] + " ( " + company_class[i]['currency'] + " )";
                    }
                    var option = $('<option />');
                    option.attr('data-otherclass', company_class[i]['other_class']);
                    option.attr('data-currency', company_class[i]['currency']);
                    option.attr('data-sharetype', company_class[i]['sharetype']);
                    option.attr('value', company_class[i]['id']).text(shareClass);
                    // if(transaction_share_transfer)
                    // {
                    //     if(transaction_share_transfer[0]["share_capital_id"] != null && company_class[i]['id'] == transaction_share_transfer[0]["share_capital_id"])
                    //     {
                    //         option.attr('selected', 'selected');

                    //     }
                    // }

                    $("#class").append(option);
                }
                
            }

            $("#class").on('change', function() {
                if($(this).find("option:selected").val() == 0)
                {
                    $("#currency").val($(this).find("option:selected").data('currency'));
                }
                else
                {
                    $("#client_member_share_capital_id").val($(this).find("option:selected").val());
                    $("#currency").val($(this).find("option:selected").data('currency'));
                }
                
            });

            if(transaction_share_transfer)
            {
                get_transfer_from_people();
                added_transfer_info(transaction_share_transfer);
                //shareTransferInterface(transaction_share_transfer);
            }
            // else
            // {
            $("DIV.transfer").remove();
            $("DIV.to").remove();
            $(document).ready(function() {
                addFirstFrom();
                // $('#table_transfer_to').hide();
                // $('.to').hide();
                // $('#total_share_transfer_to').hide();
            });
            //}

            $("#class").change(function(){
                $('#loadingWizardMessage').show();
                if($('select[name="class"]').val() != previous_class_value && previous_class_value != null)
                {
                    $("DIV.transfer").remove();
                    $("DIV.to").remove();
                    addFirstFrom();

                    // $('#table_transfer_to').hide();
                    // $('.to').hide();
                    // $('#total_share_transfer_to').hide();
                    $('#total_from').text("0");
                    $('#total_to').text("0");

                    previous_class_value = $('select[name="class"]').val();
                }
                else
                {
                    previous_class_value = $('select[name="class"]').val();
                }
                get_transfer_from_people();
            });
            
            if($(".uen_section #uen").val() != "")
            {
                $.ajax({
                  url: "transaction/check_client_info",
                  type: "POST",
                  data: {"registration_no": $(".uen_section #uen").val()},
                  dataType: 'json',
                  async: false,
                  success: function (response,data) {
                    $('#loadingWizardMessage').hide();
                        if(response)
                        {   
                            $(".trans_company_code").val(response[0]["company_code"]);
                            $("#share_transfer_form #company_code").val(response[0]["company_code"]);
                            transaction_company_code = response[0]["company_code"];

                            $("#company_name").append(response[0]['company_name']);
                            $(".hidden_company_name").val(response[0]['company_name']);

                            if(transaction_id == undefined)
                            {
                                $( document ).ready( checkPreviousServices );
                            }
                        }
                        else
                        {
                            toastr.error("Please enter the correct Registration No", "Error");
                        }
                    }
                });


            }
            else
            {
                $('#loadingWizardMessage').hide();
                
            }
            

            loadLastTab();
        }
        else
        {
            $('#loadingWizardMessage').hide();
            toastr.error(array_for_share_transfer['error'], "Error");
        }
    });
}

function added_transfer_info(transaction_share_transfer)
{  
    $(".member_info_for_each_company").remove();
    if(transaction_share_transfer)
    {
        var row_id = 0;
        for(var i = 0; i < transaction_share_transfer.length; i++)
        {

            row_id++;
            if(transaction_share_transfer[i]["sharetype"] == "Others")
            {
                var sharetype = "(" +transaction_share_transfer[i]["other_class"]+ ")" ;
            }
            else
            {
                var sharetype = "";
            }

            $b=""; 
            $b += '<tr class="member_info_for_each_company">';
            $b += '<td style="text-align: right;width:10px">'+row_id+'</td>';
            $b += '<td class="hidden"><input type="text" name="transaction_transfer_member_id" value="'+transaction_share_transfer[i]["transaction_transfer_member_id"]+'"></td>';
            $b += '<td class="hidden"><input type="text" name="transaction_id" value="'+transaction_share_transfer[i]["transaction_id"]+'"></td>';
            $b += '<td class="hidden"><input type="text" name="from_transfer_member_id" value="'+transaction_share_transfer[i]["from_transfer_member_id"]+'"></td>';
            $b += '<td class="hidden"><input type="text" name="to_transfer_member_id" value="'+transaction_share_transfer[i]["to_transfer_member_id"]+'"></td>';
            $b += '<td class="hidden"><input type="text" name="from_cert_id" value="'+transaction_share_transfer[i]["from_cert_id"]+'"></td>';
            $b += '<td class="hidden"><input type="text" name="to_cert_id" value="'+transaction_share_transfer[i]["to_cert_id"]+'"></td>';
            $b += '<td>'+((transaction_share_transfer[i]["from_officer_identification_no"] != null)?transaction_share_transfer[i]["from_officer_identification_no"] : (transaction_share_transfer[i]["from_officer_company_register_no"] != null?transaction_share_transfer[i]["from_officer_company_register_no"]:transaction_share_transfer[i]["from_client_regis_no"]))+'</td>';
            $b += '<td><a class="amber" href="javascript:void(0)" data-toggle="tooltip" data-trigger="hover" onclick="editShareTransfer('+transaction_share_transfer[i]["from_transfer_member_id"]+')">'+((transaction_share_transfer[i]["from_officer_name"] != null)?transaction_share_transfer[i]["from_officer_name"] : (transaction_share_transfer[i]["from_officer_company_name"] != null?transaction_share_transfer[i]["from_officer_company_name"]:transaction_share_transfer[i]["from_client_company_name"]))+'</a></td>';
            $b += '<td>'+((transaction_share_transfer[i]["to_officer_identification_no"] != null)?transaction_share_transfer[i]["to_officer_identification_no"] : (transaction_share_transfer[i]["to_officer_company_register_no"] != null?transaction_share_transfer[i]["to_officer_company_register_no"]:transaction_share_transfer[i]["to_client_regis_no"]))+'</td>';
            $b += '<td>'+((transaction_share_transfer[i]["to_officer_name"] != null)?transaction_share_transfer[i]["to_officer_name"] : (transaction_share_transfer[i]["to_officer_company_name"] != null?transaction_share_transfer[i]["to_officer_company_name"]:transaction_share_transfer[i]["to_client_company_name"]))+'</td>';
            
            $b += '<td>'+transaction_share_transfer[i]["sharetype"] + " " + sharetype+'</td>';
            // $b += '<td style="text-align:center">'+transaction_share_transfer[i]["to_new_certificate_no"]+'</td>';
            $b += '<td style="text-align:center">'+transaction_share_transfer[i]["currency"]+'</td>';
            $b += '<td style="text-align:right">'+addCommas(transaction_share_transfer[i]["to_number_of_share"])+'</td>';
            $b += '<td><div class="action"><button type="button" class="btn btn-primary delete_transfer_button" onclick="delete_member_transfer(this)" style="display: block;">Delete</button></div></th>';
            $b += '</tr>';

            $("#transfer_info_add").append($b);
        }
    }
}

function get_transfer_from_people()
{
    $.ajax({
        type: "POST",
        url: "transaction/get_transfer_people",
        data: {"client_member_share_capital_id":$('select[name="class"]').val(), "company_code":$("#company_code").val(), "transaction_id":transaction_id}, // <--- THIS IS THE CHANGE
        dataType: "json",
        async: false,
        success: function(response){
            $('#loadingWizardMessage').hide();
            allotmentPeople = response;
            
            if(allotmentPeople != null)
            {   
                $(".person_id option").remove();
                var option = $('<option />');
                option.attr('value', 0).text("Select ID");
                $(".person_id").append(option); 

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
                    option.attr('data-certID', allotmentPeople[i]['id']);
                    option.attr('value', allotmentPeople[i]['officer_id']).text((allotmentPeople[i]["identification_no"]!=null ? (allotmentPeople[i]["identification_no"] + " - " + allotmentPeople[i]["name"]) : ((allotmentPeople[i]["register_no"]!=null) ? (allotmentPeople[i]["register_no"] + " - " + allotmentPeople[i]["company_name"]) : (allotmentPeople[i]["registration_no"] + " - " + allotmentPeople[i]["client_company_name"]))) + " (" +addCommas(allotmentPeople[i]['number_of_share'])+ ") ");

                    $(".person_id").append(option); 
                }

            }
        }               
    });
}
function getShareAllotmentInterface()
{
    $("#transaction_data .panel").remove();
    $.post("transaction/get_share_allot_page", {id: transaction_id, company_code: transaction_company_code, transaction_task_id: transaction_task_id, registration_no: $(".uen_section #uen").val()}, function(data){

        array_for_share_allot = JSON.parse(data);

        if(array_for_share_allot['error'] == null)
        {
            transaction_share_allot = array_for_share_allot[0]["transaction_share_allotment"];
            company_class = array_for_share_allot[0]["company_class"];

            $("#transaction_data").append(array_for_share_allot['interface']);
            $("#registration_no").append($(".uen_section #uen").val());
            
            meeting_table_info(transaction_share_allot, array_for_share_allot);
            if(transaction_share_allot)
            {
                shareAllotmentInterface(transaction_share_allot);
            }

            if($(".uen_section #uen").val() != "")
            {
                $.ajax({
                  url: "transaction/check_client_info",
                  type: "POST",
                  data: {"registration_no": $(".uen_section #uen").val()},
                  dataType: 'json',
                  async: false,
                  success: function (response,data) {
                    $('#loadingWizardMessage').hide();
                        if(response)
                        {   
                            $(".trans_company_code").val(response[0]["company_code"]);
                            $("#share_allotment_form #company_code").val(response[0]["company_code"]);
                            transaction_company_code = response[0]["company_code"];

                            $("#company_name").append(response[0]['company_name']);
                            $(".hidden_company_name").val(response[0]['company_name']);

                            if(transaction_id == undefined)
                            {
                                $( document ).ready( checkPreviousServices );
                            }
                        }
                        else
                        {
                            toastr.error("Please enter the correct Registration No", "Error");
                        }
                    }
                });
            }
            else
            {
                $('#loadingWizardMessage').hide();
                
            }
            
            loadLastTab();
        }
        else
        {
            $('#loadingWizardMessage').hide();
            toastr.error(array_for_share_allot['error'], "Error");
        }
    });
}

function agm_address_table()
{
    $("#register_address_edit").click(function() {
        $("#tr_foreign_edit").hide();
        $("#tr_registered_edit").show();
        $("#tr_local_edit").hide();

        var foreign_address1 = document.getElementById('foreign_address1');
        var foreign_address2 = document.getElementById('foreign_address2');
        var foreign_address3 = document.getElementById('foreign_address3');

        $('input[name="postal_code1"]').attr('disabled', 'true');
        $('input[name="street_name1"]').attr('disabled', 'true');

        $('input[name="foreign_address1"]').attr('disabled', 'true');
        $('input[name="foreign_address2"]').attr('disabled', 'true');
        $('input[name="foreign_address3"]').attr('disabled', 'true');

        switch (foreign_address1.type) {
            case 'text':
                foreign_address1.value = '';
                break;
        }

        switch (foreign_address2.type) {
            case 'hidden':
            case 'text':
                foreign_address2.value = '';
                break;
            case 'radio':
            case 'checkbox': 
        }

        switch (foreign_address3.type) {
            case 'hidden':
            case 'text':
                foreign_address3.value = '';
                break;
            case 'radio':
            case 'checkbox': 
        }

        for (var i = 1; i < 2; i++) {
            window['postal_code'+i] = document.getElementById('postal_code'+i);
            window['street_name'+i] = document.getElementById('street_name'+i);
            window['building_name'+i] = document.getElementById('building_name'+i);

            switch (window['postal_code'+i].type) {
                case 'text':
                    window['postal_code'+i].value = '';
                    break;
            }
            switch (window['street_name'+i].type) {
                case 'text':
                    window['street_name'+i].value = '';
                    break;
            }
            switch (window['building_name'+i].type) {
                case 'text':
                    window['building_name'+i].value = '';
                    break;
            }
        }
        for (var i = 1; i < 3; i++) {
            window['unit_no'+i] = document.getElementById('unit_no'+i);
            switch (window['unit_no'+i].type) {
                case 'text':
                    window['unit_no'+i].value = '';
                    break;
            }
        }
    });

    $("#local_edit").click(function() {
        $("#tr_foreign_edit").hide();
        $("#tr_registered_edit").hide();
        $("#tr_local_edit").show();

        var foreign_address1 = document.getElementById('foreign_address1');
        var foreign_address2 = document.getElementById('foreign_address2');
        var foreign_address3 = document.getElementById('foreign_address3');

        $('input[name="postal_code1"]').removeAttr('disabled');
        $('input[name="street_name1"]').removeAttr('disabled');

        $('input[name="foreign_address1"]').attr('disabled', 'true');
        $('input[name="foreign_address2"]').attr('disabled', 'true');
        $('input[name="foreign_address3"]').attr('disabled', 'true');

        switch (foreign_address1.type) {
            case 'text':
                foreign_address1.value = '';
                break;
        }

        switch (foreign_address2.type) {
            case 'hidden':
            case 'text':
                foreign_address2.value = '';
                break;
            case 'radio':
            case 'checkbox': 
        }

        switch (foreign_address3.type) {
            case 'hidden':
            case 'text':
                foreign_address3.value = '';
                break;
            case 'radio':
            case 'checkbox': 
        }
    });
    
    $("#foreign_edit").click(function() {
        $("#tr_foreign_edit").show();
        $("#tr_local_edit").hide();
        $("#tr_registered_edit").hide();


        $('input[name="postal_code1"]').attr('disabled', 'true');
        $('input[name="street_name1"]').attr('disabled', 'true');
        
        $('input[name="foreign_address1"]').removeAttr('disabled');
        $('input[name="foreign_address2"]').removeAttr('disabled');
        $('input[name="foreign_address3"]').removeAttr('disabled');

        for (var i = 1; i < 2; i++) {
            window['postal_code'+i] = document.getElementById('postal_code'+i);
            window['street_name'+i] = document.getElementById('street_name'+i);
            window['building_name'+i] = document.getElementById('building_name'+i);

            switch (window['postal_code'+i].type) {
                case 'text':
                    window['postal_code'+i].value = '';
                    break;
            }
            switch (window['street_name'+i].type) {
                case 'text':
                    window['street_name'+i].value = '';
                    break;
            }
            switch (window['building_name'+i].type) {
                case 'text':
                    window['building_name'+i].value = '';
                    break;
            }
        }
        for (var i = 1; i < 3; i++) {
            window['unit_no'+i] = document.getElementById('unit_no'+i);
            switch (window['unit_no'+i].type) {
                case 'text':
                    window['unit_no'+i].value = '';
                    break;
            }
        }

    });

    $('#postal_code1').keyup(function(){
        if($(this).val().length == 6){
            var zip = $(this).val();
            $.ajax({
              url:    'https://gothere.sg/maps/geo',
              dataType: 'jsonp',
              data:   {
                'output'  : 'json',
                'q'     : zip,
                'client'  : '',
                'sensor'  : false
              },
              type: 'GET',
              success: function(data) {
                var myString = "";
                
                var status = data.Status;
                
                if (status.code == 200) {         
                  for (var i = 0; i < data.Placemark.length; i++) {
                    var placemark = data.Placemark[i];
                    var status = data.Status[i];

                    $("#street_name1").val(placemark.AddressDetails.Country.Thoroughfare.ThoroughfareName);

                    if(placemark.AddressDetails.Country.AddressLine == "undefined")
                    {
                        $("#building_name1").val("");
                    }
                    else
                    {
                        $("#building_name1").val(placemark.AddressDetails.Country.AddressLine);
                    }
               
                  }
                  $( '#form_postal_code1' ).html('');
                  $( '#form_street_name1' ).html('');
                } else if (status.code == 603) {
                    $( '#form_postal_code1' ).html('<span class="help-block">*No Record Found</span>');
                }

              },
              statusCode: {
                404: function() {
                  alert('Page not found');
                }
              },
            });
        }
        else
        {
            $("#street_name1").val("");
            $("#building_name1").val("");
        }
    });
}

function agm_ar_meeting_table_info(array_for_agm_ar = null, transaction_agm_ar = null)
{
    agm_address_table();
    
    if(transaction_agm_ar)
    {
        if(transaction_agm_ar[0]["agm_date"])
        {
            $("#tr_registered_edit input").attr('disabled', 'true');
            $('input[name="registered_postal_code1"]').val(array_for_agm_ar[0]["client_postal_code"]);
            $('input[name="registered_street_name1"]').val(array_for_agm_ar[0]["client_street_name"]);
            $('input[name="registered_building_name1"]').val(array_for_agm_ar[0]["client_building_name"]);
            $('input[name="registered_unit_no1"]').val(array_for_agm_ar[0]["client_unit_no1"]);
            $('input[name="registered_unit_no2"]').val(array_for_agm_ar[0]["client_unit_no2"]);

            if(transaction_agm_ar[0]['address_type'] == "Registered Office Address")
            {
                $("#register_address_edit").prop("checked", true);
                $("#tr_registered_edit").show();
                $("#tr_local_edit").hide();
                $("#tr_foreign_edit").hide();

                $('input[name="postal_code1"]').attr('disabled', 'true');
                $('input[name="street_name1"]').attr('disabled', 'true');

                $('input[name="foreign_address1"]').attr('disabled', 'true');
                $('input[name="foreign_address2"]').attr('disabled', 'true');
                $('input[name="foreign_address3"]').attr('disabled', 'true');
            }
            if(transaction_agm_ar[0]['address_type'] == "Local")
            {
                $('input[name="postal_code1"]').val(transaction_agm_ar[0]['postal_code1']);
                $('input[name="street_name1"]').val(transaction_agm_ar[0]['street_name1']);
                $('input[name="building_name1"]').val(transaction_agm_ar[0]['building_name1']);
                $('input[name="unit_no1"]').val(transaction_agm_ar[0]['unit_no1']);
                $('input[name="unit_no2"]').val(transaction_agm_ar[0]['unit_no2']);

                $("#local_edit").prop("checked", true);
                $("#tr_local_edit").show();
                $("#tr_registered_edit").hide();
                $("#tr_foreign_edit").hide();

                $('input[name="foreign_address1"]').attr('disabled', 'true');
                $('input[name="foreign_address2"]').attr('disabled', 'true');
                $('input[name="foreign_address3"]').attr('disabled', 'true');
            }
            else if(transaction_agm_ar[0]['address_type'] == "Foreign")
            {
                $('input[name="foreign_address1"]').val(transaction_agm_ar[0]['foreign_address1']);
                $('input[name="foreign_address2"]').val(transaction_agm_ar[0]['foreign_address2']);
                $('input[name="foreign_address3"]').val(transaction_agm_ar[0]['foreign_address3']);
                
                $("#foreign_edit").prop("checked", true);
                $("#tr_foreign_edit").show();
                $("#tr_local_edit").hide();
                $("#tr_registered_edit").hide();

                $('input[name="postal_code1"]').attr('disabled', 'true');
                $('input[name="street_name1"]').attr('disabled', 'true');
            }

        }
        else
        {
            $("#tr_registered_edit input").attr('disabled', 'true');
            $('input[name="registered_postal_code1"]').val(array_for_agm_ar[0]["client_postal_code"]);
            $('input[name="registered_street_name1"]').val(array_for_agm_ar[0]["client_street_name"]);
            $('input[name="registered_building_name1"]').val(array_for_agm_ar[0]["client_building_name"]);
            $('input[name="registered_unit_no1"]').val(array_for_agm_ar[0]["client_unit_no1"]);
            $('input[name="registered_unit_no2"]').val(array_for_agm_ar[0]["client_unit_no2"]);

            $("#register_address_edit").prop("checked", true);
            $("#tr_registered_edit").show();
            $("#tr_local_edit").hide();
            $("#tr_foreign_edit").hide();

            $('input[name="postal_code1"]').attr('disabled', 'true');
            $('input[name="street_name1"]').attr('disabled', 'true');

            $('input[name="foreign_address1"]').attr('disabled', 'true');
            $('input[name="foreign_address2"]').attr('disabled', 'true');
            $('input[name="foreign_address3"]').attr('disabled', 'true');
        }
    }
    else
    {   
        $('input[name="registered_postal_code1"]').val(array_for_agm_ar[0]["client_postal_code"]);
        $('input[name="registered_street_name1"]').val(array_for_agm_ar[0]["client_street_name"]);
        $('input[name="registered_building_name1"]').val(array_for_agm_ar[0]["client_building_name"]);
        $('input[name="registered_unit_no1"]').val(array_for_agm_ar[0]["client_unit_no1"]);
        $('input[name="registered_unit_no2"]').val(array_for_agm_ar[0]["client_unit_no2"]);
        $("#tr_registered_edit input").attr('disabled', 'true');
        $("#register_address_edit").prop("checked", true);
        $("#tr_registered_edit").show();
        $("#tr_local_edit").hide();
        $("#tr_foreign_edit").hide();
    }
}

function meeting_table_info(transaction_share_allot = null, array_for_share_allot = null)
{
    $('.director_meeting_date').datepicker({ 
         dateFormat:'dd/mm/yyyy',
     }).datepicker('setStartDate', "01/01/1920");


    $('#datetimepicker3').datetimepicker({
        format: 'LT'
    });

    $("#datetimepicker3").val("10:00 AM");

    $('.member_meeting_date').datepicker({ 
         dateFormat:'dd/mm/yyyy',
     }).datepicker('setStartDate', "01/01/1920");


    $('#datetimepicker4').datetimepicker({
        format: 'LT'
    });

    $("#datetimepicker4").val("10:00 AM");

    agm_address_table();

    if(transaction_share_allot)
    {
        if(array_for_share_allot[0]["transaction_meeting_date"])
        {
            $("#tr_registered_edit input").attr('disabled', 'true');
            $('input[name="registered_postal_code1"]').val(array_for_share_allot[0]["postal_code"]);
            $('input[name="registered_street_name1"]').val(array_for_share_allot[0]["street_name"]);
            $('input[name="registered_building_name1"]').val(array_for_share_allot[0]["building_name"]);
            $('input[name="registered_unit_no1"]').val(array_for_share_allot[0]["unit_no1"]);
            $('input[name="registered_unit_no2"]').val(array_for_share_allot[0]["unit_no2"]);

            $(".director_meeting_date").val(array_for_share_allot[0]["transaction_meeting_date"][0]['director_meeting_date']);
            $(".director_meeting_time").val(array_for_share_allot[0]["transaction_meeting_date"][0]['director_meeting_time']);
            $(".member_meeting_date").val(array_for_share_allot[0]["transaction_meeting_date"][0]['member_meeting_date']);
            $(".member_meeting_time").val(array_for_share_allot[0]["transaction_meeting_date"][0]['member_meeting_time']);
            if(array_for_share_allot[0]["transaction_meeting_date"][0]['address_type'] == "Registered Office Address" || array_for_share_allot[0]["transaction_meeting_date"][0]['address_type'] == "")
            {
                $("#register_address_edit").prop("checked", true);
                $("#tr_registered_edit").show();
                $("#tr_local_edit").hide();
                $("#tr_foreign_edit").hide();

                $('input[name="postal_code1"]').attr('disabled', 'true');
                $('input[name="street_name1"]').attr('disabled', 'true');

                $('input[name="foreign_address1"]').attr('disabled', 'true');
                $('input[name="foreign_address2"]').attr('disabled', 'true');
                $('input[name="foreign_address3"]').attr('disabled', 'true');
            }
            if(array_for_share_allot[0]["transaction_meeting_date"][0]['address_type'] == "Local")
            {
                $('input[name="postal_code1"]').val(array_for_share_allot[0]["transaction_meeting_date"][0]['postal_code1']);
                $('input[name="street_name1"]').val(array_for_share_allot[0]["transaction_meeting_date"][0]['street_name1']);
                $('input[name="building_name1"]').val(array_for_share_allot[0]["transaction_meeting_date"][0]['building_name1']);
                $('input[name="unit_no1"]').val(array_for_share_allot[0]["transaction_meeting_date"][0]['unit_no1']);
                $('input[name="unit_no2"]').val(array_for_share_allot[0]["transaction_meeting_date"][0]['unit_no2']);

                $("#local_edit").prop("checked", true);
                $("#tr_local_edit").show();
                $("#tr_registered_edit").hide();
                $("#tr_foreign_edit").hide();

                $('input[name="foreign_address1"]').attr('disabled', 'true');
                $('input[name="foreign_address2"]').attr('disabled', 'true');
                $('input[name="foreign_address3"]').attr('disabled', 'true');
            }
            else if(array_for_share_allot[0]["transaction_meeting_date"][0]['address_type'] == "Foreign")
            {
                $('input[name="foreign_address1"]').val(array_for_share_allot[0]["transaction_meeting_date"][0]['foreign_address1']);
                $('input[name="foreign_address2"]').val(array_for_share_allot[0]["transaction_meeting_date"][0]['foreign_address2']);
                $('input[name="foreign_address3"]').val(array_for_share_allot[0]["transaction_meeting_date"][0]['foreign_address3']);
                
                $("#foreign_edit").prop("checked", true);
                $("#tr_foreign_edit").show();
                $("#tr_local_edit").hide();
                $("#tr_registered_edit").hide();

                $('input[name="postal_code1"]').attr('disabled', 'true');
                $('input[name="street_name1"]').attr('disabled', 'true');
            }

        }
        else
        {
            $("#tr_registered_edit input").attr('disabled', 'true');
            $('input[name="registered_postal_code1"]').val(array_for_share_allot[0]["postal_code"]);
            $('input[name="registered_street_name1"]').val(array_for_share_allot[0]["street_name"]);
            $('input[name="registered_building_name1"]').val(array_for_share_allot[0]["building_name"]);
            $('input[name="registered_unit_no1"]').val(array_for_share_allot[0]["unit_no1"]);
            $('input[name="registered_unit_no2"]').val(array_for_share_allot[0]["unit_no2"]);

            $("#register_address_edit").prop("checked", true);
            $("#tr_registered_edit").show();
            $("#tr_local_edit").hide();
            $("#tr_foreign_edit").hide();

            $('input[name="postal_code1"]').attr('disabled', 'true');
            $('input[name="street_name1"]').attr('disabled', 'true');

            $('input[name="foreign_address1"]').attr('disabled', 'true');
            $('input[name="foreign_address2"]').attr('disabled', 'true');
            $('input[name="foreign_address3"]').attr('disabled', 'true');
        }
    }
    else
    {   
        $('input[name="registered_postal_code1"]').val(array_for_share_allot[0]["postal_code"]);
        $('input[name="registered_street_name1"]').val(array_for_share_allot[0]["street_name"]);
        $('input[name="registered_building_name1"]').val(array_for_share_allot[0]["building_name"]);
        $('input[name="registered_unit_no1"]').val(array_for_share_allot[0]["unit_no1"]);
        $('input[name="registered_unit_no2"]').val(array_for_share_allot[0]["unit_no2"]);

        $("#tr_registered_edit input").attr('disabled', 'true');
        $("#register_address_edit").prop("checked", true);
        $("#tr_registered_edit").show();
        $("#tr_local_edit").hide();
        $("#tr_foreign_edit").hide();
    }
}

function getEngagementLetterInterface()
{
    $("#transaction_data .panel").remove();
    if($(".dropdown_client_name :selected").val() != 0)
    {
        company_code_selected = $(".dropdown_client_name :selected").val();
    }
    else
    {
        company_code_selected = transaction_company_code;
    }
    $.post("transaction/get_engagement_letter_page", {id: transaction_id, company_code: company_code_selected, transaction_task_id: transaction_task_id}, function(data){
        array_for_engagement_letter = JSON.parse(data);

        transaction_engagement_letter_additional_info = array_for_engagement_letter[0]["transaction_engagement_letter_additional_info"];
        transaction_engagement_letter = array_for_engagement_letter[0]["transaction_engagement_letter"];
        
        var currency = array_for_engagement_letter[0]["currency"];
        var unit_pricing_name = array_for_engagement_letter[0]["unit_pricing_name"];
        var get_all_firm_info = array_for_engagement_letter[0]["get_all_firm_info"];

        $("#transaction_data").append("");

        $("#transaction_data").append(array_for_engagement_letter['interface']);

        if(transaction_engagement_letter_additional_info)
        {
            $('#loadingWizardMessage').hide();
            $('.engagement_letter_date').datepicker({ 
                    format: 'dd MM yyyy',
                }).datepicker('setStartDate', "01/01/1920").datepicker('setDate', transaction_engagement_letter_additional_info[0]["engagement_letter_date"]);
            $('#el_uen').val(transaction_engagement_letter_additional_info[0]["uen"]);
            $('.fye_date').datepicker({ 
                format: 'dd MM yyyy',
            }).datepicker('setStartDate', "01/01/1920").datepicker('setDate', transaction_engagement_letter_additional_info[0]["fye_date"]);
            $('#director_signing').val(transaction_engagement_letter_additional_info[0]["director_signing"]);

            //transaction_company_code = $(".dropdown_client_name :selected").val();
        }
        else
        {
            $('#loadingWizardMessage').hide();
            //$("#transaction_data").append(array_for_engagement_letter['interface']);
            if(array_for_engagement_letter[0]["client_info"] != false)
            {
                $('#el_uen').val(array_for_engagement_letter[0]["client_info"][0]["registration_no"]);

                $('.fye_date').datepicker({ 
                    format: 'dd MM yyyy',
                }).datepicker('setStartDate', "01/01/1920").datepicker('setDate', array_for_engagement_letter[0]["client_info"][0]["year_end"]);
            }
            else
            {
                $('.fye_date').datepicker({ 
                    format: 'dd MM yyyy',
                }).datepicker('setStartDate', "01/01/1920");
            }

            $('.engagement_letter_date').datepicker({ 
                    format: 'dd MM yyyy',
                }).datepicker('setStartDate', "01/01/1920");

            if(array_for_engagement_letter[0]["director_result_1"] != false)
            {
                $('#director_signing').val(array_for_engagement_letter[0]["director_result_1"]);
            }
            else
            {
                $('#director_signing').val();
            }

            
        }
        $(".trans_company_code").val(company_code_selected);
        $("#engagement_letter_form .company_code").val(company_code_selected);
        transaction_company_code = company_code_selected;

        if(array_for_engagement_letter[0]["transaction_engagement_letter_list"])
        {
            for($i = 0; $i < array_for_engagement_letter[0]["transaction_engagement_letter_list"].length; $i++)
            {
                $b =""; 
                $b += '<tr class="service_section">';
                $b += '<td style="text-align:center;"><input type="checkbox" class="selected_el_id" id="selected_el_id'+$i+'" name="selected_el_id[]" value="'+array_for_engagement_letter[0]["transaction_engagement_letter_list"][$i]["id"]+'"><input class="form-control hidden_selected_el_id" id="hidden_selected_el_id'+$i+'" type="hidden" name="hidden_selected_el_id[]" value=""></td>';
                $b += '<td>'+array_for_engagement_letter[0]["transaction_engagement_letter_list"][$i]["engagement_letter_list_name"]+'</td>';
                $b += '<td><select class="form-control" style="text-align:right;width: 100%;" name="currency[]" id="currency'+$i+'"><option value="0" >Select Currency</option></select></td>';
                $b += '<td><input class="form-control numberdes" type="text" name="fee[]" value="" id="fee'+$i+'" style="text-align:right;"></td>';
                $b += '<td><select class="form-control" style="text-align:right;width: 100%;" name="unit_pricing[]" id="unit_pricing'+$i+'"><option value="0" >Select Unit Pricing</option></select></td>';
                // $b += '<td><input class="form-control" type="text" name="unit_pricing[]" id="unit_pricing'+$i+'" value=""></td>';
                $b += '<td><select class="form-control" style="text-align:right;width: 100%;" name="servicing_firm[]" id="servicing_firm'+$i+'"><option value="0" >Select Servicing Firm</option></select></td>';
                $b += '</tr>';

                $("#body_engagement_letter").append($b);

                $.each(currency, function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);

                    $("#currency"+$i).append(option);
                });

                $.each(unit_pricing_name, function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);

                    $("#unit_pricing"+$i).append(option);
                });

                $.each(get_all_firm_info, function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);

                    $("#servicing_firm"+$i).append(option);
                });

                if(array_for_engagement_letter[0]["get_service_proposal_service_info"])
                {
                    for($b = 0; $b < array_for_engagement_letter[0]["get_service_proposal_service_info"].length; $b++)
                    {
                        if(array_for_engagement_letter[0]["get_service_proposal_service_info"][$b]["engagement_letter_list_id"] == array_for_engagement_letter[0]["transaction_engagement_letter_list"][$i]["id"])
                        {
                            $("#selected_el_id"+$i).prop('checked', true);
                            //$("#trans_master_service_proposal_id").val(array_for_engagement_letter[0]["get_service_proposal_service_info"][$b]["id"]);
                            $("#hidden_selected_el_id"+$i).val(array_for_engagement_letter[0]["get_service_proposal_service_info"][$b]["engagement_letter_list_id"]);
                            $("#currency"+$i).val(array_for_engagement_letter[0]["get_service_proposal_service_info"][$b]["currency_id"]);
                            $("#fee"+$i).val(addCommas(array_for_engagement_letter[0]["get_service_proposal_service_info"][$b]["fee"]));
                            $("#unit_pricing"+$i).val(array_for_engagement_letter[0]["get_service_proposal_service_info"][$b]["unit_pricing"]);
                            $("#servicing_firm"+$i).val(array_for_engagement_letter[0]["get_service_proposal_service_info"][$b]["servicing_firm"]);
                        }
                    }
                }


                if(array_for_engagement_letter[0]["get_service_proposal_service_info_id"])
                {
                    $("#trans_master_service_proposal_id").val(array_for_engagement_letter[0]["get_service_proposal_service_info_id"][0]["id"]);
                }
                else
                {
                    $("#trans_master_service_proposal_id").val("");
                }

                if(transaction_engagement_letter)
                {
                    for($b = 0; $b < transaction_engagement_letter.length; $b++)
                    {
                        if(transaction_engagement_letter[$b]["engagement_letter_list_id"] == array_for_engagement_letter[0]["transaction_engagement_letter_list"][$i]["id"])
                        {
                            $("#selected_el_id"+$i).prop('checked', true);
                            $("#hidden_selected_el_id"+$i).val(transaction_engagement_letter[$b]["engagement_letter_list_id"]);
                            $("#currency"+$i).val(transaction_engagement_letter[$b]["currency_id"]);
                            $("#fee"+$i).val(addCommas(transaction_engagement_letter[$b]["fee"]));
                            $("#unit_pricing"+$i).val(transaction_engagement_letter[$b]["unit_pricing"]);
                            $("#servicing_firm"+$i).val(transaction_engagement_letter[$b]["servicing_firm"]);
                        }
                    }
                }
            }
        }

        $('.selected_el_id').change(function(e) {
            e.preventDefault();
            if($(this).is(":checked")) {
                $(this).parent().find(".hidden_selected_el_id").val($(this).val());
            }
            else
            {
                $(this).parent().find(".hidden_selected_el_id").val("");
            }
        });

        if(transaction_id == undefined)
        {
            $( document ).ready( checkPreviousServices );
        }
    });

}

function getMLQuarterlyStatementsInterface()
{
    $("#transaction_data .panel").remove();
    if($('.dropdown_client_name').prop('disabled'))
    {
        var tran_company_name = $(".input_client_name").val();
    }
    else
    {
        var tran_company_name = $(".dropdown_client_name :selected").text();
    }

    $k = 0;
    $.post("transaction/get_ml_quarterly_statements_page", {id: transaction_id, company_code: transaction_company_code, transaction_task_id: transaction_task_id, company_name: tran_company_name}, function(data){
        array_for_ml_quarterly_statements = JSON.parse(data);

        transaction_ml_quarterly_statements = array_for_ml_quarterly_statements[0]["transaction_ml_quarterly_statements"];
        
        $("#transaction_data").append("");
        if(transaction_ml_quarterly_statements)
        {
            $("#transaction_data").append(array_for_ml_quarterly_statements['interface']);

            $("#transaction_trans #transaction_code").val(transaction_ml_quarterly_statements[0]['transaction_code']);
            $("#transaction_trans #transaction_master_id").val(transaction_ml_quarterly_statements[0]['transaction_id']);
            $(".trans_company_code").val(transaction_ml_quarterly_statements[0]["company_code"]);
            $("#ml_quarterly_statements_form .company_code").val(transaction_ml_quarterly_statements[0]["company_code"]);
            $("#company_name").text(transaction_ml_quarterly_statements[0]['client_name']);
            $("#postal_code").val(transaction_ml_quarterly_statements[0]['postal_code']);
            $("#street_name").val(transaction_ml_quarterly_statements[0]['street_name']);
            $("#building_name").val(transaction_ml_quarterly_statements[0]['building_name']);
            $("#unit_no1").val(transaction_ml_quarterly_statements[0]['unit_no1']);
            $("#unit_no2").val(transaction_ml_quarterly_statements[0]['unit_no2']);

            if(transaction_client_type_id == 1)
            {
                document.getElementById("postal_code").readOnly = true;
                document.getElementById("street_name").readOnly = true;
                document.getElementById("building_name").readOnly = true;
                document.getElementById("unit_no1").readOnly = true;
                document.getElementById("unit_no2").readOnly = true;
            }
        }
        else
        {   
            $('#loadingWizardMessage').hide();
            $("#transaction_data").append(array_for_ml_quarterly_statements['interface']);

            if(array_for_ml_quarterly_statements[0]["company_code"])
            {
                $(".trans_company_code").val(array_for_ml_quarterly_statements[0]["company_code"]);
                $("#ml_quarterly_statements_form .company_code").val(array_for_ml_quarterly_statements[0]["company_code"]);
                transaction_company_code = array_for_ml_quarterly_statements[0]["company_code"];
                $("#company_name").text(array_for_ml_quarterly_statements[0]["client_detail"][0]['company_name']);
                $("#postal_code").val(array_for_ml_quarterly_statements[0]["client_detail"][0]['postal_code']);
                $("#street_name").val(array_for_ml_quarterly_statements[0]["client_detail"][0]['street_name']);
                $("#building_name").val(array_for_ml_quarterly_statements[0]["client_detail"][0]['building_name']);
                $("#unit_no1").val(array_for_ml_quarterly_statements[0]["client_detail"][0]['unit_no1']);
                $("#unit_no2").val(array_for_ml_quarterly_statements[0]["client_detail"][0]['unit_no2']);

                document.getElementById("postal_code").readOnly = true;
                document.getElementById("street_name").readOnly = true;
                document.getElementById("building_name").readOnly = true;
                document.getElementById("unit_no1").readOnly = true;
                document.getElementById("unit_no2").readOnly = true;
            }
            else
            {
                $(".trans_company_code").val(transaction_company_code);
                $("#ml_quarterly_statements_form .company_code").val(transaction_company_code);
                $("#company_name").text($(".input_client_name").val());
                document.getElementById("postal_code").readOnly = false;
                document.getElementById("street_name").readOnly = false;
                document.getElementById("building_name").readOnly = false;
                document.getElementById("unit_no1").readOnly = false;
                document.getElementById("unit_no2").readOnly = false;
            }
        }

        $('#postal_code').keyup(function(){
            if($(this).val().length == 6)
            {
                var zip = $(this).val();
                $.ajax({
                  url:    'https://gothere.sg/maps/geo',
                  dataType: 'jsonp',
                  data:   {
                    'output'  : 'json',
                    'q'     : zip,
                    'client'  : '',
                    'sensor'  : false
                  },
                  type: 'GET',
                  success: function(data) {
                    var myString = "";
                    var status = data.Status;

                    if (status.code == 200) {         
                      for (var i = 0; i < data.Placemark.length; i++) {
                        var placemark = data.Placemark[i];
                        var status = data.Status[i];
                        $("#street_name").val(placemark.AddressDetails.Country.Thoroughfare.ThoroughfareName);

                        if(placemark.AddressDetails.Country.AddressLine == "undefined")
                        {
                            $("#building_name").val("");
                        }
                        else
                        {
                            $("#building_name").val(placemark.AddressDetails.Country.AddressLine);
                        }
                      }
                      $( '#form_postal_code' ).html('');
                      $( '#form_street_name' ).html('');
                    } else if (status.code == 603) {
                        $( '#form_postal_code' ).html('<span class="help-block">*No Record Found</span>');
                    }

                  },
                  statusCode: {
                    404: function() {
                      alert('Page not found');
                    }
                  },
                });
            }
        });
    });
}

function getOMPGrantInterface()
{
    $("#transaction_data .panel").remove();
    if($('.dropdown_client_name').prop('disabled'))
    {
        var tran_company_name = $(".input_client_name").val();
    }
    else
    {
        var tran_company_name = $(".dropdown_client_name :selected").text();
    }

    $.post("transaction/get_omp_grant_page", {id: transaction_id, company_code: transaction_company_code, transaction_task_id: transaction_task_id, company_name: tran_company_name}, function(data){
        array_for_omp_grant = JSON.parse(data);

        transaction_omp_grant = array_for_omp_grant[0]["transaction_omp_grant"];
        $("#transaction_data").append("");
        if(transaction_omp_grant)
        {
            $("#transaction_data").append(array_for_omp_grant['interface']);

            $('.date_of_quotation').datepicker({ 
                format: 'dd MM yyyy',
            }).datepicker('setStartDate', "01/01/1920").datepicker('setDate', transaction_omp_grant[0]["date_of_quotation"]);

            $('.grant_date').datepicker({ 
                format: 'dd MM yyyy',
            }).datepicker('setStartDate', "01/01/1920").datepicker('setDate', transaction_omp_grant[0]["grant_date"]);

            // client_contact_info_name = array_for_service_proposal[0]["transaction_service_proposal_contact_person"][0]["name"];
            // client_contact_info_phone = array_for_service_proposal[0]["transaction_service_proposal_contact_person"][0]["transaction_client_contact_info_phone"];
            // client_contact_info_email = array_for_service_proposal[0]["transaction_service_proposal_contact_person"][0]["transaction_client_contact_info_email"];
            //changeServiceProposalInterface(transaction_service_proposal);
            $("#transaction_trans #transaction_code").val(transaction_omp_grant[0]['transaction_code']);
            $("#transaction_trans #transaction_master_id").val(transaction_omp_grant[0]['transaction_id']);
            $(".trans_company_code").val(transaction_omp_grant[0]["company_code"]);
            $("#omp_grant_form .company_code").val(transaction_omp_grant[0]["company_code"]);
            // $("#activity1").val(transaction_service_proposal[0]['activity1']);
            // $("#activity2").val(transaction_service_proposal[0]['activity2']);
            $("#postal_code").val(transaction_omp_grant[0]['postal_code']);
            $("#street_name").val(transaction_omp_grant[0]['street_name']);
            $("#building_name").val(transaction_omp_grant[0]['building_name']);
            $("#unit_no1").val(transaction_omp_grant[0]['unit_no1']);
            $("#unit_no2").val(transaction_omp_grant[0]['unit_no2']);

            $("#attention_name").val(transaction_omp_grant[0]['attention_name']);
            $("#attention_title").val(transaction_omp_grant[0]['attention_title']);
            //$("#grant_date").val(transaction_omp_grant[0]['grant_date']);
            $("#quotation_ref").val(transaction_omp_grant[0]['quotation_ref']);
            $("#cash_deposit").val(addCommas(transaction_omp_grant[0]['cash_deposit']));
            $("#success_fees").val(transaction_omp_grant[0]['success_fees']);

            // if(transaction_omp_grant[0]['less_the_cash_deposit'] > 0)
            // {
                $("#less_the_cash_deposit").val(addCommas(transaction_omp_grant[0]['less_the_cash_deposit']));
            //}
            //$("#contact_name").val(client_contact_info_name);

            if(transaction_client_type_id == 1)
            {
                // document.getElementById("activity1").readOnly = true;
                // document.getElementById("activity2").readOnly = true;
                document.getElementById("postal_code").readOnly = true;
                document.getElementById("street_name").readOnly = true;
                document.getElementById("building_name").readOnly = true;
                document.getElementById("unit_no1").readOnly = true;
                document.getElementById("unit_no2").readOnly = true;
            }
        }
        else
        {
            $('#loadingWizardMessage').hide();
            $("#transaction_data").append(array_for_omp_grant['interface']);

            $('.date_of_quotation').datepicker({ 
                format: 'dd MM yyyy',
            }).datepicker("setDate", formatDateFunc(new Date()));

            $('.grant_date').datepicker({ 
                format: 'dd MM yyyy',
            }).datepicker("setDate", formatDateFunc(new Date()));

            if(array_for_omp_grant[0]["company_code"])
            {
                $(".trans_company_code").val(array_for_omp_grant[0]["company_code"]);
                $("#omp_grant_form .company_code").val(array_for_omp_grant[0]["company_code"]);
                transaction_company_code = array_for_omp_grant[0]["company_code"];

                $("#postal_code").val(array_for_omp_grant[0]["client_detail"][0]['postal_code']);
                $("#street_name").val(array_for_omp_grant[0]["client_detail"][0]['street_name']);
                $("#building_name").val(array_for_omp_grant[0]["client_detail"][0]['building_name']);
                $("#unit_no1").val(array_for_omp_grant[0]["client_detail"][0]['unit_no1']);
                $("#unit_no2").val(array_for_omp_grant[0]["client_detail"][0]['unit_no2']);

                document.getElementById("postal_code").readOnly = true;
                document.getElementById("street_name").readOnly = true;
                document.getElementById("building_name").readOnly = true;
                document.getElementById("unit_no1").readOnly = true;
                document.getElementById("unit_no2").readOnly = true;
            }
            else
            {
                $(".trans_company_code").val(transaction_company_code);
                $("#omp_grant_form .company_code").val(transaction_company_code);

                document.getElementById("postal_code").readOnly = false;
                document.getElementById("street_name").readOnly = false;
                document.getElementById("building_name").readOnly = false;
                document.getElementById("unit_no1").readOnly = false;
                document.getElementById("unit_no2").readOnly = false;
            }
        }

        $('.cash_deposit').change(function(e) {
            e.preventDefault();
            $(".less_the_cash_deposit").val($('.cash_deposit').val());
        });

        $('.less_the_cash_deposit').change(function(e) {
            e.preventDefault();
            $(".cash_deposit").val($('.less_the_cash_deposit').val());
        });

        // $('.success_fees').change(function(e) {
        //     e.preventDefault();
        //     $(".less_the_cash_deposit").val(calculate_less_the_cash_deposit());
        // });

        $('#postal_code').keyup(function(){
            if($(this).val().length == 6)
            {
                var zip = $(this).val();
                //var address = "068914";
                $.ajax({
                  url:    'https://gothere.sg/maps/geo',
                  dataType: 'jsonp',
                  data:   {
                    'output'  : 'json',
                    'q'     : zip,
                    'client'  : '',
                    'sensor'  : false
                  },
                  type: 'GET',
                  success: function(data) {
                    var myString = "";
                    var status = data.Status;
                    /*myString += "Status.code: " + status.code + "\n";
                    myString += "Status.request: " + status.request + "\n";
                    myString += "Status.name: " + status.name + "\n";*/
                    
                    if (status.code == 200) {         
                      for (var i = 0; i < data.Placemark.length; i++) {
                        var placemark = data.Placemark[i];
                        var status = data.Status[i];
                        $("#street_name").val(placemark.AddressDetails.Country.Thoroughfare.ThoroughfareName);

                        if(placemark.AddressDetails.Country.AddressLine == "undefined")
                        {
                            $("#building_name").val("");
                        }
                        else
                        {
                            $("#building_name").val(placemark.AddressDetails.Country.AddressLine);
                        }
                      }
                      $( '#form_postal_code' ).html('');
                      $( '#form_street_name' ).html('');
                      //field.val(myString);
                    } else if (status.code == 603) {
                        $( '#form_postal_code' ).html('<span class="help-block">*No Record Found</span>');
                      //field.val("No Record Found");
                    }

                  },
                  statusCode: {
                    404: function() {
                      alert('Page not found');
                    }
                  },
                });
            }
        });

        if(transaction_id == undefined)
        {
            $( document ).ready( checkPreviousServices );
        }
    });
}

function calculate_less_the_cash_deposit()
{
    if($('.cash_deposit').val() != "" && $('.success_fees').val() != "")
    {
        var less_the_cash_deposit = parseFloat($('.cash_deposit').val().replace(/\,/g,'')) * ((100 - parseFloat($('.success_fees').val().replace(/\,/g,'')))/100);
    }
    else
    {
        var less_the_cash_deposit = 0.00;
    }

    return addCommas(less_the_cash_deposit.toFixed(2));
}

function getServiceProposalInterface()
{
    $("#transaction_data .panel").remove();
    if($('.dropdown_client_name').prop('disabled'))
    {
        var tran_company_name = $(".input_client_name").val();
    }
    else
    {
        var tran_company_name = $(".dropdown_client_name :selected").text();
    }

    $k = 0;
    $.post("transaction/get_service_proposal_page", {id: transaction_id, company_code: transaction_company_code, transaction_task_id: transaction_task_id, company_name: tran_company_name}, function(data){
        array_for_service_proposal = JSON.parse(data);

        transaction_service_proposal = array_for_service_proposal[0]["transaction_service_proposal"];
        var currency = array_for_service_proposal[0]["currency"];
        var unit_pricing_name = array_for_service_proposal[0]["unit_pricing_name"];
        var get_all_firm_info = array_for_service_proposal[0]["get_all_firm_info"];
        
        $("#transaction_data").append("");
        if(transaction_service_proposal)
        {
            $("#transaction_data").append(array_for_service_proposal['interface']);

            $('.proposal_date').datepicker({ 
                format: 'dd MM yyyy',
            }).datepicker('setStartDate', "01/01/1920").datepicker('setDate', transaction_service_proposal[0]["proposal_date"]);

            client_contact_info_name = array_for_service_proposal[0]["transaction_service_proposal_contact_person"][0]["name"];
            client_contact_info_phone = array_for_service_proposal[0]["transaction_service_proposal_contact_person"][0]["transaction_client_contact_info_phone"];
            client_contact_info_email = array_for_service_proposal[0]["transaction_service_proposal_contact_person"][0]["transaction_client_contact_info_email"];
            //changeServiceProposalInterface(transaction_service_proposal);
            $("#transaction_trans #transaction_code").val(transaction_service_proposal[0]['transaction_code']);
            $("#transaction_trans #transaction_master_id").val(transaction_service_proposal[0]['transaction_id']);
            $(".trans_company_code").val(transaction_service_proposal[0]["company_code"]);
            $("#service_proposal_form .company_code").val(transaction_service_proposal[0]["company_code"]);
            $("#activity1").val(transaction_service_proposal[0]['activity1']);
            $("#activity2").val(transaction_service_proposal[0]['activity2']);
            $("#postal_code").val(transaction_service_proposal[0]['postal_code']);
            $("#street_name").val(transaction_service_proposal[0]['street_name']);
            $("#building_name").val(transaction_service_proposal[0]['building_name']);
            $("#unit_no1").val(transaction_service_proposal[0]['unit_no1']);
            $("#unit_no2").val(transaction_service_proposal[0]['unit_no2']);
            $("#contact_name").val(client_contact_info_name);

            if(transaction_client_type_id == 1)
            {
                document.getElementById("activity1").readOnly = true;
                document.getElementById("activity2").readOnly = true;
                document.getElementById("postal_code").readOnly = true;
                document.getElementById("street_name").readOnly = true;
                document.getElementById("building_name").readOnly = true;
                document.getElementById("unit_no1").readOnly = true;
                document.getElementById("unit_no2").readOnly = true;
            }
        }
        else
        {
            if(array_for_service_proposal[0]["transaction_our_service_list"])
            {
                $('#loadingWizardMessage').hide();
                $("#transaction_data").append(array_for_service_proposal['interface']);

                //$(".proposal_date").val(new Date()); 

                $('.proposal_date').datepicker({ 
                    format: 'dd MM yyyy',
                }).datepicker("setDate", formatDateFunc(new Date()));

                if(array_for_service_proposal[0]["company_code"])
                {
                    $(".trans_company_code").val(array_for_service_proposal[0]["company_code"]);
                    $("#service_proposal_form .company_code").val(array_for_service_proposal[0]["company_code"]);
                    transaction_company_code = array_for_service_proposal[0]["company_code"];
                    client_contact_info_name = array_for_service_proposal[0]["client_contact_person"][0]["name"];
                    client_contact_info_phone = array_for_service_proposal[0]["client_contact_person"][0]["client_contact_info_phone"];
                    client_contact_info_email = array_for_service_proposal[0]["client_contact_person"][0]["client_contact_info_email"];
                    
                    $("#activity1").val(array_for_service_proposal[0]["client_detail"][0]['activity1']);
                    $("#activity2").val(array_for_service_proposal[0]["client_detail"][0]['activity2']);
                    $("#postal_code").val(array_for_service_proposal[0]["client_detail"][0]['postal_code']);
                    $("#street_name").val(array_for_service_proposal[0]["client_detail"][0]['street_name']);
                    $("#building_name").val(array_for_service_proposal[0]["client_detail"][0]['building_name']);
                    $("#unit_no1").val(array_for_service_proposal[0]["client_detail"][0]['unit_no1']);
                    $("#unit_no2").val(array_for_service_proposal[0]["client_detail"][0]['unit_no2']);

                    document.getElementById("activity1").readOnly = true;
                    document.getElementById("activity2").readOnly = true;
                    document.getElementById("postal_code").readOnly = true;
                    document.getElementById("street_name").readOnly = true;
                    document.getElementById("building_name").readOnly = true;
                    document.getElementById("unit_no1").readOnly = true;
                    document.getElementById("unit_no2").readOnly = true;

                    $("#contact_name").val(client_contact_info_name);
                }
                else
                {
                    $(".trans_company_code").val(transaction_company_code);
                    $("#service_proposal_form .company_code").val(transaction_company_code);

                    document.getElementById("activity1").readOnly = false;
                    document.getElementById("activity2").readOnly = false;
                    document.getElementById("postal_code").readOnly = false;
                    document.getElementById("street_name").readOnly = false;
                    document.getElementById("building_name").readOnly = false;
                    document.getElementById("unit_no1").readOnly = false;
                    document.getElementById("unit_no2").readOnly = false;

                    client_contact_info_phone = null;
                    client_contact_info_email = null;
                }
                // for($i = 0; $i < array_for_service_proposal[0]["transaction_our_service_list"].length; $i++)
                // {
                //     $b =""; 
                //     $b += '<tr class="service_section">';
                //     $b += '<td style="text-align:center;"><input type="checkbox" class="selected_service_id" name="selected_service_id[]" value="'+array_for_service_proposal[0]["transaction_our_service_list"][$i]["id"]+'"><input class="form-control hidden_selected_service_id" type="hidden" name="hidden_selected_service_id[]" value=""></td>';
                //     $b += '<td>'+array_for_service_proposal[0]["transaction_our_service_list"][$i]["service_name"]+'</td>';
                //     $b += '<td><select class="form-control" style="text-align:right;width: 100%;" name="currency[]" id="currency'+$i+'"><option value="0" >Select Currency</option></select></td>';
                //     $b += '<td><input class="form-control numberdes" type="text" name="fee[]" value="" style="text-align:right;"></td>';
                //     $b += '<td><input class="form-control" type="text" name="unit_pricing[]" value=""></td>';
                //     $b += '</tr>';

                //     $("#body_service_proposal").append($b);

                //     $.each(currency, function(key, val) {
                //         var option = $('<option />');
                //         option.attr('value', key).text(val);
                //         // if(transaction_issue_dividend)
                //         // {
                //         //     if(transaction_issue_dividend[0]["currency"] != undefined && key == transaction_issue_dividend[0]["currency"])
                //         //     {
                //         //         option.attr('selected', 'selected');
                //         //     }
                //         // }
                //         $("#currency"+$i).append(option);
                //     });
                // }
            }
            else
            {
                $('#loadingWizardMessage').hide();
                $("#transaction_data").append("");
                $("#transaction_data").append("<span class='help-block'>* Please setup the info in Our Service Module.</span>");
            }
        }

        if(array_for_service_proposal[0]["transaction_our_service_list"])
        {
            for($i = 0; $i < array_for_service_proposal[0]["transaction_our_service_list"].length; $i++)
            {
                $b =""; 
                $b += '<tr class="service_section">';
                $b += '<td style="text-align:center;"><input type="checkbox" class="selected_service_id" id="selected_service_id'+$i+'" name="selected_service_id[]" value="'+array_for_service_proposal[0]["transaction_our_service_list"][$i]["id"]+'"><input class="form-control hidden_selected_service_id" id="hidden_selected_service_id'+$i+'" type="hidden" name="hidden_selected_service_id[]" value=""></td>';
                $b += '<td><span><a href="javascript:void(0)" onclick="editServiceProposalDescription(this)" class="mb-sm mt-sm mr-sm span_service_name" style="cursor:pointer" data-toggle="tooltip" data-trigger="hover" data-original-title="Change Service Proposal Content">'+array_for_service_proposal[0]["transaction_our_service_list"][$i]["service_name"]+'</a></span><textarea class="service_proposal_description" id="service_proposal_description'+$i+'" hidden="hidden" name="service_proposal_description[]">'+array_for_service_proposal[0]["transaction_our_service_list"][$i]["service_proposal_description"]+'</textarea><input type="hidden" name="engagement_letter_list_id[]" value="'+array_for_service_proposal[0]["transaction_our_service_list"][$i]["engagement_letter_list_id"]+'"></td>';
                $b += '<td><select class="form-control currency" style="text-align:right;width: 100%;" name="currency[]" id="currency'+$i+'"><option value="0" >Select Currency</option></select></td>';
                $b += '<td><input class="form-control numberdes fee" type="text" name="fee[]" value="" id="fee'+$i+'" style="text-align:right;"></td>';
                $b += '<td><select class="form-control unit_pricing" style="text-align:right;width: 100%;" name="unit_pricing[]" id="unit_pricing'+$i+'"><option value="0" >Select Unit Pricing</option></select></td>';
                // $b += '<td><input class="form-control" type="text" name="unit_pricing[]" id="unit_pricing'+$i+'" value=""></td>';
                $b += '<td><select class="form-control" style="text-align:right;width: 100%;" name="servicing_firm[]" id="servicing_firm'+$i+'"><option value="0">Select Servicing Firm</option></select></td>';
                $b += '<td><input class="form-control" name="sequence[]" id="sequence'+$i+'"/></td>';
                $b += '<td class="row_to_add_sub_service" style="display:none;width:10px"><a class="add_sub_service amber" href="javascript:void(0)" id="add_sub_service" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Create Sub Service" ><i class="fa fa-plus-circle amber" style="font-size:16px;height:15px;"></i></a></td>'
                $b += '</tr>';
               
                $("#body_service_proposal").append($b);

                $.each(currency, function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    $("#currency"+$i).append(option);
                });

                $.each(unit_pricing_name, function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    $("#unit_pricing"+$i).append(option);
                });

                $.each(get_all_firm_info, function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);

                    $("#servicing_firm"+$i).append(option);
                });

                $("#currency"+$i).val(array_for_service_proposal[0]["transaction_our_service_list"][$i]["currency"]);
                $("#fee"+$i).val(addCommas(array_for_service_proposal[0]["transaction_our_service_list"][$i]["amount"]));
                $("#unit_pricing"+$i).val(array_for_service_proposal[0]["transaction_our_service_list"][$i]["unit_pricing"]);

                if(array_for_service_proposal[0]["transaction_service_proposal_service_info"])
                {
                    for($b = 0; $b < array_for_service_proposal[0]["transaction_service_proposal_service_info"].length; $b++)
                    {
                        if(array_for_service_proposal[0]["transaction_service_proposal_service_info"][$b]["our_service_id"] == array_for_service_proposal[0]["transaction_our_service_list"][$i]["id"])
                        {
                            $("#selected_service_id"+$i).prop('checked', true);
                            $("#selected_service_id"+$i).parent().parent().find(".row_to_add_sub_service").show();
                            $("#service_proposal_description"+$i).val(array_for_service_proposal[0]["transaction_service_proposal_service_info"][$b]["service_proposal_description"]);
                            $("#hidden_selected_service_id"+$i).val(array_for_service_proposal[0]["transaction_service_proposal_service_info"][$b]["our_service_id"]);
                            $("#currency"+$i).val(array_for_service_proposal[0]["transaction_service_proposal_service_info"][$b]["currency_id"]);
                            $("#fee"+$i).val(addCommas(array_for_service_proposal[0]["transaction_service_proposal_service_info"][$b]["fee"]));
                            $("#unit_pricing"+$i).val(array_for_service_proposal[0]["transaction_service_proposal_service_info"][$b]["unit_pricing"]);
                            $("#servicing_firm"+$i).val(array_for_service_proposal[0]["transaction_service_proposal_service_info"][$b]["servicing_firm"]);
                            $("#sequence"+$i).val(array_for_service_proposal[0]["transaction_service_proposal_service_info"][$b]["sequence"]);
                            
                            for($m = 0; $m < array_for_service_proposal[0]["transaction_service_proposal_sub_service_info"].length; $m++)
                            {
                                if(array_for_service_proposal[0]["transaction_service_proposal_service_info"][$b]["id"] == array_for_service_proposal[0]["transaction_service_proposal_sub_service_info"][$m]["service_info_id"])
                                {
                                    var checkbox_value = array_for_service_proposal[0]["transaction_service_proposal_service_info"][$b]["our_service_id"];
                                    $j =""; 
                                    $j += '<tr class="service_section sub_row_'+checkbox_value+'">';
                                    $j += '<td style="text-align:center;"><input type="checkbox" class="sub_selected_service_id" id="sub_selected_service_id'+$i+'" name="sub_selected_service_id[]" value="" checked="true" disabled="true"><input type="hidden" name="hidden_selected_service_id_for_sub[]" value="'+checkbox_value+'"></td>';
                                    $j += '<td><textarea class="form-control" id="sub_service'+$k+'" name="sub_service[]" rows="4" cols="30">'+array_for_service_proposal[0]["transaction_service_proposal_sub_service_info"][$m]["our_service_name"]+'</textarea></td>'; //<input class="form-control" type="text" name="sub_service[]" value="'+array_for_service_proposal[0]["transaction_service_proposal_sub_service_info"][$m]["our_service_name"]+'" id="sub_service'+$k+'">
                                    $j += '<td><select class="form-control" style="text-align:right;width: 100%;" name="sub_currency[]" id="sub_currency'+$k+'"><option value="0" >Select Currency</option></select></td>';
                                    $j += '<td><input class="form-control numberdes" type="text" name="sub_fee[]" value="'+addCommas(array_for_service_proposal[0]["transaction_service_proposal_sub_service_info"][$m]["sub_fee"])+'" id="sub_fee'+$k+'" style="text-align:right;"></td>';
                                    $j += '<td><select class="form-control" style="text-align:right;width: 100%;" name="sub_unit_pricing[]" id="sub_unit_pricing'+$k+'"><option value="0" >Select Unit Pricing</option></select></td>';
                                    // $b += '<td><input class="form-control" type="text" name="unit_pricing[]" id="unit_pricing'+$i+'" value=""></td>';
                                    $j += '<td></td>';
                                    $j += '<td></td>';
                                    $j += '<td style="width:10px"><a class="remove_sub_service amber" href="javascript:void(0)" id="remove_sub_service" onclick="remove_sub_service(this)" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Delete Sub Service" ><i class="fa fa-minus-circle amber" style="font-size:16px;height:15px;"></i></a>';
                                    $j += '</tr>';
                                    $("#selected_service_id"+$i).parent().parent().after($j);

                                    $.each(currency, function(key, val) {
                                        var option = $('<option />');
                                        option.attr('value', key).text(val);

                                        $("#sub_currency"+$k).append(option);
                                    });

                                    $.each(unit_pricing_name, function(key, val) {
                                        var option = $('<option />');
                                        option.attr('value', key).text(val);

                                        $("#sub_unit_pricing"+$k).append(option);
                                    });

                                    $("#sub_currency"+$k).val(array_for_service_proposal[0]["transaction_service_proposal_sub_service_info"][$m]["sub_currency_id"]);
                                    $("#sub_unit_pricing"+$k).val(array_for_service_proposal[0]["transaction_service_proposal_sub_service_info"][$m]["sub_unit_pricing"]);
                                    $k++;
                                }
                            }
                        }
                        // else
                        // {
                        //     $("#currency"+$i).val(array_for_service_proposal[0]["transaction_our_service_list"][$i]["currency"]);
                        //     $("#fee"+$i).val(addCommas(array_for_service_proposal[0]["transaction_our_service_list"][$i]["amount"]));
                        //     $("#unit_pricing"+$i).val(array_for_service_proposal[0]["transaction_our_service_list"][$i]["unit_pricing"]);
                        // }
                    }
                }
                // else
                // {
                //     $("#currency"+$i).val(array_for_service_proposal[0]["transaction_our_service_list"][$i]["currency"]);
                //     $("#fee"+$i).val(addCommas(array_for_service_proposal[0]["transaction_our_service_list"][$i]["amount"]));
                //     $("#unit_pricing"+$i).val(array_for_service_proposal[0]["transaction_our_service_list"][$i]["unit_pricing"]);
                // }
            }
            //service_proposal_datatable = $('#service_proposal_table').DataTable();
            $(document).ready(function(){
                $('[data-toggle="tooltip"]').tooltip();
            });
        }
        
        $('.selected_service_id').change(function(e) {
            e.preventDefault();
            if($(this).is(":checked")) {
                $(this).parent().find(".hidden_selected_service_id").val($(this).val());
                $(this).parent().parent().find(".row_to_add_sub_service").show();
            }
            else
            {
                $(this).parent().find(".hidden_selected_service_id").val("");
                $(this).parent().parent().find(".row_to_add_sub_service").hide();  
                $('.sub_row_'+$(this).val()).remove();
            }
        });

        $(document).on('click', '.add_sub_service', function(e){
            e.preventDefault();
            var checkbox_value = $(this).parent().parent().find(".selected_service_id").val();
            $b =""; 
            $b += '<tr class="service_section sub_row_'+checkbox_value+'">';
            $b += '<td style="text-align:center;"><input type="checkbox" class="sub_selected_service_id" id="sub_selected_service_id'+$i+'" name="sub_selected_service_id[]" value="" checked="true" disabled="true"><input type="hidden" name="hidden_selected_service_id_for_sub[]" value="'+checkbox_value+'"></td>';
            $b += '<td><textarea class="form-control" id="sub_service'+$k+'" name="sub_service[]" rows="4" cols="30"></textarea></td>'; //<input class="form-control" type="text" name="sub_service[]" value="" id="sub_service'+$k+'">
            $b += '<td><select class="form-control" style="text-align:right;width: 100%;" name="sub_currency[]" id="sub_currency'+$k+'"><option value="0" >Select Currency</option></select></td>';
            $b += '<td><input class="form-control numberdes" type="text" name="sub_fee[]" value="" id="sub_fee'+$k+'" style="text-align:right;"></td>';
            $b += '<td><select class="form-control" style="text-align:right;width: 100%;" name="sub_unit_pricing[]" id="sub_unit_pricing'+$k+'"><option value="0" >Select Unit Pricing</option></select></td>';
            // $b += '<td><input class="form-control" type="text" name="unit_pricing[]" id="unit_pricing'+$i+'" value=""></td>';
            $b += '<td></td>';
            $b += '<td></td>';
            $b += '<td style="width:10px"><a class="remove_sub_service amber" href="javascript:void(0)" id="remove_sub_service" onclick="remove_sub_service(this)" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Delete Sub Service" ><i class="fa fa-minus-circle amber" style="font-size:16px;height:15px;"></i></a>'
            $b += '</tr>';

            $("#service_proposal_table").find($(this).parent().parent()).after($b);

            $.each(currency, function(key, val) {
                var option = $('<option />');
                option.attr('value', key).text(val);

                $("#sub_currency"+$k).append(option);
            });

            $.each(unit_pricing_name, function(key, val) {
                var option = $('<option />');
                option.attr('value', key).text(val);

                $("#sub_unit_pricing"+$k).append(option);
            });

            $("#sub_service"+$k).val($(this).parent().parent().find(".span_service_name").html());
            $("#sub_currency"+$k).val($(this).parent().parent().find(".currency").val());
            $("#sub_fee"+$k).val($(this).parent().parent().find(".fee").val());
            $("#sub_unit_pricing"+$k).val($(this).parent().parent().find(".unit_pricing").val());
            $k++;

            //service_proposal_datatable = $('#service_proposal_table').DataTable();
            // if ($.fn.DataTable.isDataTable('#service_proposal_table')) {
            //     //$('#').DataTable().destroy();
            //     $('#service_proposal_table').DataTable().destroy();
            //     //$('#service_proposal_table').empty();
            // }
            

            // $(document).ready(function() {
            //     if ($.fn.DataTable.isDataTable('#service_proposal_table')) {
            //         $('#service_proposal_table').DataTable().destroy();
            //     }
            //     var t = $('#service_proposal_table').DataTable({
            //                 "columnDefs": [
            //                    {
            //                       "targets": 0, 
            //                       "orderDataType": "dom-checkbox"
            //                    }
            //                 ],
            //                 order: [
            //                   [0, 'desc']
            //                 ]
            //             });
            // });
        });

        // $(document).ready(function() {
        //     datatable_service_proposal = $('#service_proposal_table').DataTable({
        //                 // "columnDefs": [
        //                 //    {
        //                 //       "targets": 0, 
        //                 //       "orderDataType": "dom-checkbox"
        //                 //    }
        //                 // ],
        //                 order: [
        //                   [0, 'desc']
        //                 ]
        //             });
        // });
        

        $('#postal_code').keyup(function(){
            if($(this).val().length == 6)
            {
                var zip = $(this).val();
                $.ajax({
                  url:    'https://gothere.sg/maps/geo',
                  dataType: 'jsonp',
                  data:   {
                    'output'  : 'json',
                    'q'     : zip,
                    'client'  : '',
                    'sensor'  : false
                  },
                  type: 'GET',
                  success: function(data) {
                    var myString = "";
                    
                    var status = data.Status;
                    /*myString += "Status.code: " + status.code + "\n";
                    myString += "Status.request: " + status.request + "\n";
                    myString += "Status.name: " + status.name + "\n";*/
                    
                    if (status.code == 200) {         
                      for (var i = 0; i < data.Placemark.length; i++) {
                        var placemark = data.Placemark[i];
                        var status = data.Status[i];
                        $("#street_name").val(placemark.AddressDetails.Country.Thoroughfare.ThoroughfareName);

                        if(placemark.AddressDetails.Country.AddressLine == "undefined")
                        {
                            $("#building_name").val("");
                        }
                        else
                        {
                            $("#building_name").val(placemark.AddressDetails.Country.AddressLine);
                        }
                      }
                      $( '#form_postal_code' ).html('');
                      $( '#form_street_name' ).html('');
                      //field.val(myString);
                    } else if (status.code == 603) {
                        $( '#form_postal_code' ).html('<span class="help-block">*No Record Found</span>');
                      //field.val("No Record Found");
                    }

                  },
                  statusCode: {
                    404: function() {
                      alert('Page not found');
                    }
                  },
                });
            }
        });

        $('.show_contact_phone').click(function(e){
            e.preventDefault();
            $(this).parent().parent().find(".contact_phone_toggle").toggle();
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

        if(transaction_id == undefined)
        {
            $( document ).ready( checkPreviousServices );
        }
    });
    });
}

function editServiceProposalDescription(elm)
{
    service_name_elm = $(elm).parent().parent().find(".service_proposal_description");
    var service_proposal_description = $(elm).parent().parent().find(".service_proposal_description").val();
    $('.modify_service_proposal').val(service_proposal_description);
    $('#modal_service_proposal_description').modal('toggle');
}

function remove_sub_service(row) {
    row.parentNode.parentNode.remove();
};

function getChangeFYEInterface()
{
    $("#transaction_data .panel").remove();
    $.post("transaction/get_change_of_FYE_page", {id: transaction_id, company_code: transaction_company_code, transaction_task_id: transaction_task_id, registration_no: $(".uen_section #uen").val()}, function(data){
        array_for_change_FYE = JSON.parse(data);

        if(array_for_change_FYE['error'] == null)
        {
            transaction_change_FYE = array_for_change_FYE[0]["transaction_change_FYE"];

            $("#transaction_data").append(array_for_change_FYE['interface']);

            $("#registration_no").append($(".uen_section #uen").val());

            $('.new_FYE').datepicker({ 
                dateFormat:'dd MM yyyy',
            }).datepicker('setStartDate', '01/01/1960');

            $('.tab_2_effective_date').datepicker().datepicker('setStartDate', '01/01/1960');

            if(transaction_change_FYE)
            {
                changeFYEInterface(transaction_change_FYE);
            }
            else
            {
                if($(".uen_section #uen").val() != "")
                {
                    $.ajax({ 
                      url: "transaction/check_filing_info",
                      type: "POST",
                      data: {"registration_no": $(".uen_section #uen").val()},
                      dataType: 'json',
                      async: false,
                      success: function (response,data) {
                        $('#loadingWizardMessage').hide();
                            if(response)
                            {   
                                $(".trans_company_code").val(response[0]["company_code"]);
                                $("#change_of_FYE_form #company_code").val(response[0]["company_code"]);
                                transaction_company_code = response[0]["company_code"];

                                $("#company_name").append(response[0]['company_name']);
                                $(".hidden_company_name").val(response[0]['company_name']);

                                $("#FYE").append(response[0]['year_end']);
                                $(".hidden_old_FYE").val(response[0]['year_end']);

                                $("#old_financial_year_period").append(response[0]['period']);
                                $(".hidden_old_period").val(response[0]['period']);

                                if(transaction_id == undefined)
                                {
                                    $( document ).ready( checkPreviousServices );
                                }
                            }
                            else
                            {
                                toastr.error("Please enter the correct Registration No", "Error");
                            }
                        }
                    });
                }
                else
                {
                    $('#loadingWizardMessage').hide();
                    
                }
            }

            $.ajax({
                type: "GET",
                url: "masterclient/get_financial_year_period",
                dataType: "json",
                async: false,
                success: function(data){
                    if(data.tp == 1){
                        $("#financial_year_period option").remove();
                        $.each(data['result'], function(key, val) {
                            var option = $('<option />');
                            option.attr('value', key).text(val);
                            if(transaction_change_FYE)
                            {
                                if(transaction_change_FYE[0]["financial_year_period"] != null && key == transaction_change_FYE[0]["financial_year_period"])
                                {
                                    option.attr('selected', 'selected');
                                }
                            }
                            $("#financial_year_period").append(option);
                        });
                        
                    }
                    else{
                        alert(data.msg);
                    }

                    
                }               
            });

            loadLastTab();
        }
        else
        {
            $('#loadingWizardMessage').hide();
            toastr.error(array_for_change_FYE['error'], "Error");
        }
    });
}

function removeActivity2(cbox) {

    if (cbox.checked) 
    {
        document.getElementById("new_activity2").value = "";
        document.getElementById("new_activity2").readOnly = true;

        document.getElementById("new_description2").value = "";
        document.getElementById("new_description2").disabled = true;
    }
    else
    {
        document.getElementById("new_activity2").value = $(".hidden_old_activity2").val();
        document.getElementById("new_activity2").readOnly = false;

        document.getElementById("new_description2").value = $(".hidden_old_description2").val();
        document.getElementById("new_description2").disabled = false;
    }
}
function getChangeBizActivityInterface()
{
    $("#transaction_data .panel").remove();
    $.post("transaction/get_change_of_biz_activity_page", {id: transaction_id, company_code: transaction_company_code, transaction_task_id: transaction_task_id, registration_no: $(".uen_section #uen").val()}, function(data){
        array_for_change_biz_activity = JSON.parse(data);

        if(array_for_change_biz_activity['error'] == null)
        {
            transaction_change_biz_activity = array_for_change_biz_activity[0]["transaction_change_biz_activity"];

            $("#transaction_data").append(array_for_change_biz_activity['interface']);
            $("#registration_no").append($(".uen_section #uen").val());
            $('.tab_2_effective_date').datepicker().datepicker('setStartDate', '01/01/1960');

            if(transaction_change_biz_activity)
            {
                changeBizActivityInterface(transaction_change_biz_activity);
            }
            else
            {
                if($(".uen_section #uen").val() != "")
                {
                    $.ajax({
                      url: "transaction/check_client_info",
                      type: "POST",
                      data: {"registration_no": $(".uen_section #uen").val()},
                      dataType: 'json',
                      async: false,
                      success: function (response,data) {
                        $('#loadingWizardMessage').hide();
                            if(response)
                            {   
                                $(".trans_company_code").val(response[0]["company_code"]);
                                $("#change_of_biz_activity_form #company_code").val(response[0]["company_code"]);
                                transaction_company_code = response[0]["company_code"];

                                $("#company_name").append(response[0]['company_name']);
                                $(".hidden_company_name").val(response[0]['company_name']);

                                $("#activity_1").append(response[0]['activity1']);
                                $(".hidden_old_activity1").val(response[0]['activity1']);
                                $("#new_activity1").val(response[0]['activity1']);

                                $("#description_1").append(response[0]['description1']);
                                $(".hidden_old_description1").val(response[0]['description1']);
                                $("#new_description1").val(response[0]['description1']);

                                $("#activity_2").append(response[0]['activity2']);
                                $(".hidden_old_activity2").val(response[0]['activity2']);
                                $("#new_activity2").val(response[0]['activity2']);

                                $("#description_2").append(response[0]['description2']);
                                $(".hidden_old_description2").val(response[0]['description2']);
                                $("#new_description2").val(response[0]['description2']);

                                if(transaction_id == undefined)
                                {
                                    $( document ).ready( checkPreviousServices );
                                }
                            }
                            else
                            {
                                toastr.error("Please enter the correct Registration No", "Error");
                            }
                        }
                    });
                }
                else
                {
                    $('#loadingWizardMessage').hide();
                }
            }

            loadLastTab();
        }
        else
        {
            $('#loadingWizardMessage').hide();
            toastr.error(array_for_change_biz_activity['error'], "Error");
        }
    });
}

function each_devidend_paid_calculation(total_devidend_amount)
{
    var each_devidend_paid = 0, total_devidend_paid = 0;
    var arr_each_dividend_paid = [];

    if(0 > total_devidend_amount.indexOf('.'))
    {
        total_devidend_amount = total_devidend_amount + ".00";
    }

    if($(".hidden_total_balance").val() != undefined && $(".total_dividend_amount").val() != "")
    {
        var rowCount = $('#latest_director_table tr.row_member_section').length;
        $(".devidend_per_share").val((parseInt(total_devidend_amount) / parseInt($(".hidden_total_balance").val())).toFixed(4));
        
        for($y = 0; $y < rowCount; $y++)
        {
            each_devidend_paid = total_devidend_amount / $(".hidden_total_balance").val() * $(".row_member_section").eq($y).find(".hidden_balance").val();
            total_devidend_paid += parseFloat(each_devidend_paid.toFixed(2));
            arr_each_dividend_paid.push(each_devidend_paid);
        }

        var total_dividend_paid = parseFloat(total_devidend_paid.toFixed(2));
        var recheck_total = 0;

        if(total_devidend_amount != total_dividend_paid)
        {
            do {
                var diff_amount = parseFloat(total_devidend_amount) - parseFloat(total_devidend_paid.toFixed(2));

                var obj = round_off_adjustment(arr_each_dividend_paid, diff_amount.toFixed(2));

                if(obj["addOrMinus"] === 'minus'){
                    var new_value = arr_each_dividend_paid[obj["index"]].toFixed(2) - parseFloat(obj["value_to_AddOrMinus"]);

                    arr_each_dividend_paid[obj["index"]] = new_value;
                    each_devidend_paid[obj["index"]] = new_value;
                }
                else if(obj["addOrMinus"] === 'add')
                {
                    var new_value = parseFloat(arr_each_dividend_paid[obj["index"]].toFixed(2)) + parseFloat(obj["value_to_AddOrMinus"]);

                    arr_each_dividend_paid[obj["index"]] = new_value;

                    each_devidend_paid[obj["index"]] = new_value;
                }

                total_dividend_paid = 0;

                for($y = 0; $y < rowCount; $y++)
                {
                    total_dividend_paid += parseFloat(arr_each_dividend_paid[$y].toFixed(2));
                }

            } while(total_devidend_amount != total_dividend_paid.toFixed(2))
        }
        
        for($y = 0; $y < rowCount; $y++)
        {
            $(".row_member_section").eq($y).find(".devidend_paid").html(addCommas(arr_each_dividend_paid[$y].toFixed(2)));
            $(".row_member_section").eq($y).find(".hidden_devidend_paid").val(arr_each_dividend_paid[$y].toFixed(2));

            recheck_total += parseFloat(arr_each_dividend_paid[$y].toFixed(2));
        }

        $(".total_devidend_paid").html(addCommas(recheck_total.toFixed(2)));
        $(".hidden_total_devidend_paid").val(recheck_total.toFixed(2));
    }
}

function round_off_adjustment(arr, diff_amount)
{
    var arr_split_diff_amount = diff_amount.split(".");
    var decimal_length = arr_split_diff_amount[1].length;

    var sample_decimal = '0.';

    for($i = 0; $i < decimal_length; $i++)
    {
        sample_decimal+= '0';
    }

    var point_five_scale = sample_decimal + '5';
    var new_arr = [];

    for($i = 0; $i < arr.length; $i++)
    {
        var temp_arr = (arr[$i].toString()).split(".");

        new_arr.push(sample_decimal + temp_arr[1].substring(decimal_length));
    }

    if(diff_amount > 0) // + value (positive)
    {
        var index = select_value_round_off(new_arr, true, point_five_scale);
        var obj = {'index': index, 'value_to_AddOrMinus': sample_decimal.substring(0, sample_decimal.length - 1) + '1', 'addOrMinus': 'add' }

        return obj;
    }
    else // - value (positive)
    {
        var index = select_value_round_off(new_arr, false, point_five_scale);
        var obj = {'index': index, 'value_to_AddOrMinus': sample_decimal.substring(0, sample_decimal.length - 1) + '1', 'addOrMinus': 'minus' }

        return obj;
    }
}

function select_value_round_off(arr, isPositive, point_five_scale)
{   
    if(isPositive)
    {
        // if adjustment need to be + value
        var obj = {};

        for($i = 0; $i < arr.length; $i++)  // eliminate value higher than 5.
        {
            if(arr[$i] < point_five_scale)
            {
                obj[$i] = arr[$i];
            }
        }

        var temp_highest = obj[Object.keys(obj)[0]];
        var temp_key_index = 0;

        for(var key in obj) {   // get first value as default so that can compare.
            temp_highest = obj[key];
            temp_key_index = key;
            break;
        }

        for(var key in obj) {   // compare and get the lowest value.
            if(obj[key] > temp_highest)
            {
                temp_highest = obj[key];
                temp_key_index = key;
            }
        }
        return temp_key_index;
    }
    else
    {   
        // if adjustment need to be - value
        var obj = {};

        for($i = 0; $i < arr.length; $i++)  // eliminate value lower than 5.
        {
            if(arr[$i] > point_five_scale)
            {
                obj[$i] = arr[$i];
            }
        }

        var temp_lowest = obj[Object.keys(obj)[0]];
        var temp_key_index = 0;

        for(var key in obj) {   // get first value as default so that can compare.
            temp_lowest = obj[key];
            temp_key_index = key;

            break;
        }

        for(var key in obj) {   // compare and get the lowest value.
            if(obj[key] < temp_lowest)
            {
                temp_lowest = obj[key];
                temp_key_index = key;
            }
        }
        return temp_key_index;
    }
}

$(document).on('change',".total_dividend_amount",function(e){
    each_devidend_paid_calculation(removeCommas($(this).val()));
});

function get_issue_dividend_member(devidend_of_cut_off_date, company_code)
{
    $('#loadingWizardMessage').show();
    $.ajax({
      url: "transaction/get_before_cut_off_date_member",
      type: "POST",
      data: {"devidend_of_cut_off_date": devidend_of_cut_off_date, "company_code": company_code},
      dataType: 'json',
      async: false,
      success: function (response,data) {
        $('#loadingWizardMessage').hide();
        $(".remove_member_info_class").remove();
            if(response)
            {   
                var member_info = response;
                var balance = 0, test_id = 0, table_id = 0, row_id = 0, total_balance = 0, each_devidend_paid = 0, total_devidend_paid = 0;
                for(var i = 0; i < member_info.length; i++)
                {
                    if(member_info[i]["identification_no"] == test_id)
                    {
                        balance += parseInt(member_info[i]["number_of_share"]);
                        row_id++;
                    }
                    else if(member_info[i]["register_no"] == test_id)
                    {
                        balance += parseInt(member_info[i]["number_of_share"]);
                        row_id++;
                    }
                    else if(member_info[i]["registration_no"] == test_id)
                    {
                        balance += parseInt(member_info[i]["number_of_share"]);
                        row_id++;
                    }
                    else
                    {
                        if(test_id != 0)
                        {
                            if(balance != 0)
                            {
                                $('.member_info_'+table_id).find(".balance").html(addCommas(balance));
                                $('.member_info_'+table_id).find(".hidden_balance").val(balance);
                                total_balance += balance;
                            }
                            else
                            {
                                $('.member_info_'+table_id).remove();
                            }
                        }
                        
                        if(member_info[i]["identification_no"] != null)
                        {
                            test_id = member_info[i]["identification_no"];
                            balance = parseInt(member_info[i]["number_of_share"]);
                            table_id++;
                            row_id = 1;
                        }
                        else if(member_info[i]["register_no"] != null)
                        {
                            test_id = member_info[i]["register_no"];
                            balance = parseInt(member_info[i]["number_of_share"]);
                            table_id++;
                            row_id = 1;
                        }
                        else
                        {
                            test_id = member_info[i]["registration_no"];
                            balance = parseInt(member_info[i]["number_of_share"]);
                            table_id++;
                            row_id = 1;
                        }

                        $b =""; 
                        $b += '<tr class="member_info_'+table_id+' remove_member_info_class row_member_section">';
                        $b += '<td>'+((member_info[i]["name"] != null)?member_info[i]["name"] : (member_info[i]["company_name"] != null?member_info[i]["company_name"]:member_info[i]["client_company_name"]))+'<input type="hidden" name="shareholder_name[]" value="'+((member_info[i]["name"] != null)?member_info[i]["name"] : (member_info[i]["company_name"] != null?member_info[i]["company_name"]:member_info[i]["client_company_name"]))+'"></td>';
                        $b += '<td style="text-align:right"><span class="balance"></span><input type="hidden" name="balance[]" class="hidden_balance" value=""><input type="hidden" class="form-control" name="officer_id[]" id="officer_id" value="'+(member_info[i]["officer_id"]!=null ? member_info[i]["officer_id"] : (member_info[i]["officer_company_id"] != null ? member_info[i]["officer_company_id"] : member_info[i]["client_company_id"]))+'"/></td>';
                        $b += '<td style="text-align:right"><span class="devidend_paid"></span><input type="hidden" name="devidend_paid[]" class="hidden_devidend_paid" value=""><input type="hidden" class="form-control" name="field_type[]" id="field_type" value="'+(member_info[i]["officer_field_type"]!=null ? member_info[i]["officer_field_type"] : (member_info[i]["officer_company_field_type"] != null ? member_info[i]["officer_company_field_type"] : member_info[i]["client_field_type"]))+'"/></td>';
                        $b += '</tr>';
                        
                        $("#body_issue_dividend").append($b);
                    }

                    $('.member_info_'+table_id).find(".balance").html(addCommas(balance));
                    $('.member_info_'+table_id).find(".hidden_balance").val(balance);
                    
                }

                total_balance += balance;

                $b =""; 
                $b += '<tr class="total_amount remove_member_info_class">';
                $b += '<td style="font-weight: bold;">Total</td>';
                $b += '<td style="text-align:right"><span class="total_balance"></span><input type="hidden" name="total_balance" class="hidden_total_balance" value=""></td>';
                $b += '<td style="text-align:right"><span class="total_devidend_paid"></span><input type="hidden" name="total_devidend_paid" class="hidden_total_devidend_paid" value=""></td>';
                $b += '</tr>';

                $("#body_issue_dividend").append($b);

                $(".total_balance").html(addCommas(total_balance));
                $(".hidden_total_balance").val(total_balance);

                if($(".total_dividend_amount").val() != "")
                {
                    each_devidend_paid_calculation(removeCommas($(".total_dividend_amount").val()));
                }

            }
            else
            {
                toastr.error("Please choose the correct devidend of cut off date to show the members.", "Error");
            }
        }
    });
}

function getIssueDividendInterface()
{
    $("#transaction_data .panel").remove();

    $.post("transaction/get_issue_dividend", {id: transaction_id, company_code: transaction_company_code, transaction_task_id: transaction_task_id, registration_no: $(".uen_section #uen").val()}, function(data){
        array_for_issue_dividend = JSON.parse(data);

        if(array_for_issue_dividend['error'] == null)
        {
            transaction_issue_dividend = array_for_issue_dividend[0]["transaction_issue_dividend"];

            $("#transaction_data").append(array_for_issue_dividend['interface']);

            meeting_table_info(transaction_issue_dividend, array_for_issue_dividend);
            
            var currency = array_for_issue_dividend[0]["currency"];

            $.each(currency, function(key, val) {
                var option = $('<option />');
                option.attr('value', key).text(val);
                if(transaction_issue_dividend)
                {
                    if(transaction_issue_dividend[0]["currency"] != undefined && key == transaction_issue_dividend[0]["currency"])
                    {
                        option.attr('selected', 'selected');
                    }
                }
                $(".currency").append(option);
            });

            var nature = array_for_issue_dividend[0]["nature"];

            $.each(nature, function(key, val) {
                var option = $('<option />');
                option.attr('value', key).text(val);
                if(transaction_issue_dividend)
                {
                    if(transaction_issue_dividend[0]["nature"] != undefined && key == transaction_issue_dividend[0]["nature"])
                    {
                        option.attr('selected', 'selected');
                    }
                }
                $(".nature").append(option);
            });

            if(transaction_issue_dividend)
            {
                issueDividendInterface(transaction_issue_dividend);
                $('.declare_of_fye').datepicker({ 
                    dateFormat:'dd/mm/yyyy',
                }).datepicker('setStartDate', "01/01/1920");
            }
            else
            {
                $('.declare_of_fye').datepicker({ 
                    dateFormat:'dd/mm/yyyy',
                }).datepicker('setStartDate', "01/01/1920").datepicker("setDate", new Date(array_for_issue_dividend[0]["year_end"]));
            }

            if($(".uen_section #uen").val() != "")
            {
                $.ajax({
                  url: "transaction/check_client_info",
                  type: "POST",
                  data: {"registration_no": $(".uen_section #uen").val()},
                  dataType: 'json',
                  async: false,
                  success: function (response,data) {
                    $('#loadingWizardMessage').hide();
                        if(response)
                        {   
                            $(".trans_company_code").val(response[0]["company_code"]);
                            $("#issue_dividend_form #company_code").val(response[0]["company_code"]);
                            transaction_company_code = response[0]["company_code"];

                            $("#company_name").append(response[0]['company_name']);
                            $(".hidden_company_name").val(response[0]['company_name']);

                            if(transaction_id == undefined)
                            {
                                $( document ).ready( checkPreviousServices );
                            }
                        }
                        else
                        {
                            toastr.error("Please enter the correct Registration No", "Error");
                        }
                    }
                });
            }
            else
            {
                $('#loadingWizardMessage').hide();
                
            }

            $('.devidend_of_cut_off_date').datepicker({ 
                 dateFormat:'dd/mm/yyyy',
             }).datepicker('setStartDate', "01/01/1920").on('changeDate', function (selected) {
                var devidend_of_cut_off_date = $('.devidend_of_cut_off_date').val();
                get_issue_dividend_member(devidend_of_cut_off_date, $("#issue_dividend_form #company_code").val());
            });


            $('.devidend_payment_date').datepicker({ 
                 dateFormat:'dd/mm/yyyy',
             }).datepicker('setStartDate', "01/01/1920");

            loadLastTab();
        }
        else
        {
            $('#loadingWizardMessage').hide();
            toastr.error(array_for_issue_dividend['error'], "Error");
        }
    });
}

function getIssueDirectorFeeInterface()
{
    $("#transaction_data .panel").remove();

    $.post("transaction/get_issue_director_fee", {id: transaction_id, company_code: transaction_company_code, transaction_task_id: transaction_task_id, registration_no: $(".uen_section #uen").val()}, function(data){
        array_for_issue_director_fee = JSON.parse(data);

        if(array_for_issue_director_fee['error'] == null)
        {
            transaction_issue_director_fee = array_for_issue_director_fee[0]["transaction_issue_director_fee"];

            $("#transaction_data").append(array_for_issue_director_fee['interface']);

            meeting_table_info(transaction_issue_director_fee, array_for_issue_director_fee);

            if(transaction_issue_director_fee)
            {
                issueDirectorFeeInterface(transaction_issue_director_fee, array_for_issue_director_fee[0]["currency"]);

                $('.declare_of_fye').datepicker({ 
                    dateFormat:'dd/mm/yyyy',
                }).datepicker('setStartDate', "01/01/1920");

                
            }
            else
            {
                $('.declare_of_fye').datepicker({ 
                    dateFormat:'dd/mm/yyyy',
                }).datepicker('setStartDate', "01/01/1920").datepicker("setDate", new Date(array_for_issue_director_fee[0]["year_end"]));

                if(array_for_issue_director_fee[0]["officer_info"] != "")
                {   
                    var director_list = array_for_issue_director_fee[0]["officer_info"];
                    for($r = 0; $r < director_list.length; $r++)
                    {
                        $b=""; 
                        $b += '<tr class="director_fee_table'+$r+'">';
                        $b += '<td style="text-align: left;">'+(director_list[$r]["identification_no"] != ""?director_list[$r]["identification_no"]:director_list[$r]["register_no"])+'<input type="hidden" name="identification_register_no[]" value="'+(director_list[$r]["identification_no"] != ""?director_list[$r]["identification_no"]:director_list[$r]["register_no"])+'"></td>';
                        $b += '<td style="text-align: left;">'+(director_list[$r]["name"] != ""?director_list[$r]["name"]:director_list[$r]["company_name"])+'<input type="hidden" name="director_name[]" value="'+(director_list[$r]["name"] != ""?director_list[$r]["name"]:director_list[$r]["company_name"])+'"></td>';
                        $b += '<td style="text-align: center;">'+director_list[$r]["date_of_appointment"]+'<input type="hidden" name="date_of_appointment[]" value="'+director_list[$r]["date_of_appointment"]+'"><input type="hidden" name="officer_id[]" value="'+director_list[$r]["officer_id"]+'"><input type="hidden" name="officer_field_type[]" value="'+director_list[$r]["officer_field_type"]+'"></td>';
                        $b += '<td><select class="form-control currency" style="width: 200px;" name="currency[]" id="currency"><option value="0">Select Currency</option></select></td>';
                        $b += '<td style="text-align: center;"><input class="form-control numberdes" style="text-align: right; width: 200px;" name="director_fee[]" id="director_fee" pattern="^[0-9,]+$"></td>';
                        $b += '</tr>';

                        $("#body_issue_director_fee").append($b);

                        var currency = array_for_issue_director_fee[0]["currency"];

                        $.each(currency, function(key, val) {
                            var option = $('<option />');
                            option.attr('value', key).text(val);
                            $(".director_fee_table"+$r).find(".currency").append(option);
                        });
                    }
                }

               


                if($(".uen_section #uen").val() != "")
                {
                    $.ajax({
                      url: "transaction/check_client_info",
                      type: "POST",
                      data: {"registration_no": $(".uen_section #uen").val()},
                      dataType: 'json',
                      async: false,
                      success: function (response,data) {
                        $('#loadingWizardMessage').hide();
                            if(response)
                            {   
                                $(".trans_company_code").val(response[0]["company_code"]);
                                $("#issue_director_fee_form #company_code").val(response[0]["company_code"]);
                                transaction_company_code = response[0]["company_code"];

                                $("#company_name").append(response[0]['company_name']);
                                $(".hidden_company_name").val(response[0]['company_name']);

                                if(transaction_id == undefined)
                                {
                                    $( document ).ready( checkPreviousServices );
                                }
                            }
                            else
                            {
                                toastr.error("Please enter the correct Registration No", "Error");
                            }
                        }
                    });
                }
                else
                {
                    $('#loadingWizardMessage').hide();
                    
                }
            }

            $('.resolution_date').datepicker({ 
                 dateFormat:'dd/mm/yyyy',
             }).datepicker('setStartDate', "01/01/1920");

            $('.meeting_date').datepicker({ 
                 dateFormat:'dd/mm/yyyy',
             }).datepicker('setStartDate', "01/01/1920");

            $('.notice_date').datepicker({ 
                 dateFormat:'dd/mm/yyyy',
             }).datepicker('setStartDate', "01/01/1920");

            loadLastTab();
        }
        else
        {
            $('#loadingWizardMessage').hide();
            toastr.error(array_for_issue_director_fee['error'], "Error");
        }
    });
}

function getIncorporationSubsidiaryInterface()
{
    $("#transaction_data .panel").remove();
    $.post("transaction/get_incorporation_subsidiary_page", {id: transaction_id, company_code: transaction_company_code, transaction_task_id: transaction_task_id, registration_no: $(".uen_section #uen").val()}, function(data){
        array_for_incorporation_subsidiary = JSON.parse(data);

        if(array_for_incorporation_subsidiary['error'] == null)
        {
            transaction_incorporation_subsidiary = array_for_incorporation_subsidiary[0]["transaction_incorporation_subsidiary"];

            $("#transaction_data").append(array_for_incorporation_subsidiary['interface']);

            $("#loadingmessage").show();
            $.ajax({
                type: "GET",
                url: "masterclient/get_currency",
                async: false,
                dataType: "json",
                success: function(data){
                    $("#loadingmessage").hide();
                    $.each(data['result'], function(key, val) {
                        var option = $('<option />');
                        option.attr('value', key).text(val);
                        if(transaction_incorporation_subsidiary)
                        {
                            if(transaction_incorporation_subsidiary[0]["currency"] != undefined && key == transaction_incorporation_subsidiary[0]["currency"])
                            {
                                option.attr('selected', 'selected');
                            }
                        }
                        $("#currency").append(option);
                    });
                }
            });

            $('.propose_effective_date').datepicker({ 
                 dateFormat:'dd/mm/yyyy',
             }).datepicker('setStartDate', "01/01/1920");

            if(transaction_incorporation_subsidiary)
            {
                incorporationSubsidiaryInterface(transaction_incorporation_subsidiary);
            }
            else
            {
                if($(".uen_section #uen").val() != "")
                {
                    $.ajax({ //Upload common input
                      url: "transaction/check_client_info",
                      type: "POST",
                      data: {"registration_no": $(".uen_section #uen").val()},
                      dataType: 'json',
                      async: false,
                      success: function (response,data) {
                        $('#loadingWizardMessage').hide();
                            if(response)
                            {   
                                $(".trans_company_code").val(response[0]["company_code"]);
                                $("#incorp_subsidiary_form #company_code").val(response[0]["company_code"]);
                                transaction_company_code = response[0]["company_code"];

                                $("#company_name").append(response[0]['company_name']);
                                $(".hidden_company_name").val(response[0]['company_name']);

                                if(transaction_id == undefined)
                                {
                                    $( document ).ready( checkPreviousServices );
                                }
                            }
                            else
                            {
                                toastr.error("Please enter the correct Registration No", "Error");
                            }
                        }
                    });
                }
                else
                {
                    $('#loadingWizardMessage').hide();
                    
                }
            }

            loadLastTab();
        }
        else
        {
            $('#loadingWizardMessage').hide();
            toastr.error(array_for_incorporation_subsidiary['error'], "Error");
        }
    });
}

function getOpeningBankAccountInterface()
{
    $("#transaction_data .panel").remove();
    $.post("transaction/get_opening_bank_account_page", {id: transaction_id, company_code: transaction_company_code, transaction_task_id: transaction_task_id, registration_no: $(".uen_section #uen").val()}, function(data){
        array_for_opening_bank_acc = JSON.parse(data);

        if(array_for_opening_bank_acc['error'] == null)
        {
            transaction_opening_bank_acc = array_for_opening_bank_acc[0]["transaction_opening_bank_acc"];
            bank_name = array_for_opening_bank_acc[0]["bank_name"];
            manner_of_operation = array_for_opening_bank_acc[0]["manner_of_operation"];

            $("#transaction_data").append(array_for_opening_bank_acc['interface']);

            if(bank_name)
            {
                for(var i = 0; i < bank_name.length; i++)
                {
                    var option = $('<option />');
                    option.attr('value', bank_name[i]['id']).text(bank_name[i]['bank_name']);

                    $("#bank").append(option);
                }
                
            }

            if(manner_of_operation)
            {
                for(var i = 0; i < manner_of_operation.length; i++)
                {
                    var option = $('<option />');
                    option.attr('value', manner_of_operation[i]['id']).text(manner_of_operation[i]['operation_name']);

                    $("#manner_of_operation").append(option);
                }
            }

            $("#bank").change(function(){
                $('#loadingWizardMessage').show();
                if($('select[name="bank"]').val() != previous_bank_value)
                {
                    $("#banker_name option").remove();
                    var option = $('<option />');
                    option.attr('value', 0).text("Select Name");
                    $("#banker_name").append(option);

                    $('#banker_email').text("");
                    $('#office_number').text("");
                    $('#mobile_number').text("");
                    $('#banker_bank_name').text("");

                    $.ajax({
                          url: "transaction/select_banker_info",
                          type: "POST",
                          data: {"bank_id": $("#bank").val()},
                          dataType: 'json',
                          async: false,
                          success: function (response,data) {
                            if(response)
                            { 
                                for(var i = 0; i < response.length; i++)
                                {
                                    var option = $('<option />');
                                    option.attr('data-email', response[i]['email']);
                                    option.attr('data-office_number', response[i]['office_number']);
                                    option.attr('data-mobile_number', response[i]['mobile_number']);
                                    option.attr('data-bank_name', response[i]['bank_name']);
                                    option.attr('value', response[i]['id']).text(response[i]['banker_name']);

                                    $("#banker_name").append(option);
                                    
                                }
                            }
                            $('#loadingWizardMessage').hide();

                          }
                    });

                    // $("DIV.transfer").remove();
                    // $("DIV.to").remove();
                    // addFirstFrom();

                    // $('#table_transfer_to').hide();
                    // $('.to').hide();
                    // $('#total_share_transfer_to').hide();
                    // $('#total_from').text("0");
                    // $('#total_to').text("0");

                    previous_bank_value = $('select[name="bank"]').val();
                }
                // else
                // {
                //     previous_bank_value = $('select[name="bank"]').val();
                // }
                
            });

            if(transaction_opening_bank_acc)
            {
                changeOpeningBankAcc(transaction_opening_bank_acc);
            }
            else
            {
                if($(".uen_section #uen").val() != "")
                {
                    $.ajax({ //Upload common input
                      url: "transaction/check_client_info",
                      type: "POST",
                      data: {"registration_no": $(".uen_section #uen").val()},
                      dataType: 'json',
                      async: false,
                      success: function (response,data) {
                        $('#loadingWizardMessage').hide();
                            if(response)
                            {  
                                $(".trans_company_code").val(response[0]["company_code"]);
                                $("#change_of_company_name_form #company_code").val(response[0]["company_code"]);
                                transaction_company_code = response[0]["company_code"];

                                $("#company_name").append(response[0]['company_name']);
                                $(".hidden_company_name").val(response[0]['company_name']);

                                if(transaction_id == undefined)
                                {
                                    $( document ).ready( checkPreviousServices );
                                }
                            }
                            else
                            {
                                toastr.error("Please enter the correct Registration No", "Error");
                            }
                        }
                    });
                }
                else
                {
                    $('#loadingWizardMessage').hide();
                    
                }
            }

            loadLastTab();
        }
        else
        {
            $('#loadingWizardMessage').hide();
            toastr.error(array_for_opening_bank_acc['error'], "Error");
        }
    });
}

$(document).on('change',".reason_for_application",function(e){
    if($(this).val() == 0 || $(this).val() == 1)
    {
        $(".ceased_date_row").hide();
        $(".ceased_date").prop("disabled", true);
        $(".ceased_date").val("");
    }
    else
    {
        $(".ceased_date_row").show();
        $(".ceased_date").prop("disabled", false);
    }
});

function getStrikeOffInterface()
{
    $("#transaction_data .panel").remove();
    $.post("transaction/get_strike_off_page", {id: transaction_id, company_code: transaction_company_code, transaction_task_id: transaction_task_id, registration_no: $(".uen_section #uen").val()}, function(data){
        array_for_strike_off = JSON.parse(data);

        if(array_for_strike_off['error'] == null)
        {
            transaction_strike_off = array_for_strike_off[0]["transaction_strike_off"];
            consent_for_shorter_notice = array_for_strike_off[0]["consent_for_shorter_notice"];

            $("#transaction_data").append(array_for_strike_off['interface']);

            $('.agm_date_info').datepicker({ 
                dateFormat:'dd MM yyyy',
            });

            $('.notice_date').datepicker({ 
                dateFormat:'dd MM yyyy',
            });

            $('.date_fs_sent_to_member').datepicker({ 
                dateFormat:'dd MM yyyy',
            });

            $('#agm_time').datetimepicker({
                format: 'LT'
            });
            $("#agm_time").val("10:00 AM");

            agm_ar_meeting_table_info(array_for_strike_off, transaction_strike_off);

            if(consent_for_shorter_notice)
            {
                for(var i = 0; i < consent_for_shorter_notice.length; i++)
                {
                    var option = $('<option />');
                    option.attr('value', consent_for_shorter_notice[i]['id']).text(consent_for_shorter_notice[i]['is_shorter_notice']);
                    if(transaction_strike_off)
                    {
                        if(transaction_strike_off[0]["shorter_notice"] != null && consent_for_shorter_notice[i]['id'] == transaction_strike_off[0]["shorter_notice"])
                        {
                            option.attr('selected', 'selected');
                        }
                    }

                    $("#shorter_notice").append(option);
                }
            }

            if(transaction_strike_off)
            {
                changeStrikeOffInterface(transaction_strike_off, array_for_strike_off[0]["reason_for_application"]);
            }
            else
            {
                var reason_for_application = array_for_strike_off[0]["reason_for_application"];

                $.each(reason_for_application, function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    $(".reason_for_application").append(option);
                });

                if($(".uen_section #uen").val() != "")
                {
                    $.ajax({ //Upload common input
                      url: "transaction/check_client_info",
                      type: "POST",
                      data: {"registration_no": $(".uen_section #uen").val()},
                      dataType: 'json',
                      async: false,
                      success: function (response,data) {
                        $('#loadingWizardMessage').hide();
                            if(response)
                            {   
                                $(".trans_company_code").val(response[0]["company_code"]);
                                $("#w2-strike_off_form #company_code").val(response[0]["company_code"]);
                                transaction_company_code = response[0]["company_code"];

                                $("#company_name").append(response[0]['company_name']);
                                if(transaction_id == undefined)
                                {
                                    $( document ).ready( checkPreviousServices );
                                }
                            }
                            else
                            {
                                toastr.error("Please enter the correct Registration No", "Error");
                            }
                        }
                    });
                }
                else
                {
                    $('#loadingWizardMessage').hide();
                    
                }
            }

            $('.ceased_date').datepicker({ 
                 dateFormat:'dd/mm/yyyy',
             }).datepicker('setStartDate', "01/01/1920");

            loadLastTab();
        }
        else
        {
            $('#loadingWizardMessage').hide();
            toastr.error(array_for_strike_off['error'], "Error");
        }
    });
}

function getChangeCompanyNameInterface()
{
    $("#transaction_data .panel").remove();
    $.post("transaction/get_change_of_company_name_page", {id: transaction_id, company_code: transaction_company_code, transaction_task_id: transaction_task_id, registration_no: $(".uen_section #uen").val()}, function(data){
        array_for_change_company_name = JSON.parse(data);

        if(array_for_change_company_name['error'] == null)
        {
            transaction_change_company_name = array_for_change_company_name[0]["transaction_change_company_name"];

            $("#transaction_data").append(array_for_change_company_name['interface']);
            $("#registration_no").append($(".uen_section #uen").val());

            $('.tab_2_effective_date').datepicker().datepicker('setStartDate', '01/01/1960');

            meeting_table_info(transaction_change_company_name, array_for_change_company_name);

            if(transaction_change_company_name)
            {
                changeCompanyNameInterface(transaction_change_company_name);
            }
            else
            {
                if($(".uen_section #uen").val() != "")
                {
                    $.ajax({ //Upload common input
                      url: "transaction/check_client_info",
                      type: "POST",
                      data: {"registration_no": $(".uen_section #uen").val()},
                      dataType: 'json',
                      async: false,
                      success: function (response,data) {
                        $('#loadingWizardMessage').hide();
                            if(response)
                            {   
                                $(".trans_company_code").val(response[0]["company_code"]);
                                $("#change_of_company_name_form #company_code").val(response[0]["company_code"]);
                                transaction_company_code = response[0]["company_code"];

                                $("#company_name").append(response[0]['company_name']);
                                $(".hidden_company_name").val(response[0]['company_name']);

                                if(transaction_id == undefined)
                                {
                                    $( document ).ready( checkPreviousServices );
                                }
                            }
                            else
                            {
                                toastr.error("Please enter the correct Registration No", "Error");
                            }
                        }
                    });
                }
                else
                {
                    $('#loadingWizardMessage').hide();
                    
                }
            }

            loadLastTab();
        }
        else
        {
            $('#loadingWizardMessage').hide();
            toastr.error(array_for_change_company_name['error'], "Error");
        }
    });
}

function getAppointNewAuditorInterface()
{
    $("#transaction_data .panel").remove();
    $.post("transaction/get_appointment_of_auditor_page", {id: transaction_id, company_code: transaction_company_code, transaction_task_id: transaction_task_id, registration_no: $(".uen_section #uen").val()}, function(data){
        array_for_appoint_auditor = JSON.parse(data);

        if(array_for_appoint_auditor['error'] == null)
        {
            transaction_appoint_new_auditor = array_for_appoint_auditor[0]["transaction_appoint_new_auditor"];
            $("#transaction_data").append(array_for_appoint_auditor['interface']);

            // $('.meeting_date').datepicker({ 
            //     dateFormat:'dd/mm/yyyy',
            // }).datepicker('setStartDate', new Date());

            $('.notice_date').datepicker({ 
                dateFormat:'dd/mm/yyyy',
            }).datepicker('setStartDate', "01/01/1920");

            meeting_table_info(transaction_appoint_new_auditor, array_for_appoint_auditor);

            //Officers

            if(transaction_appoint_new_auditor)
            {
                //$(".meeting_date").val(transaction_appoint_new_auditor[0]["meeting_date"]);
                $(".notice_date").val(array_for_appoint_auditor[0]["transaction_meeting_date"][0]["notice_date"]);
                resignAuditorInterface(transaction_appoint_new_auditor,false);
            }
            else
            {
                if($(".uen_section #uen").val() != "")
                {
                    $.ajax({ //Upload common input
                      url: "transaction/get_resign_auditor_info",
                      type: "POST",
                      data: {"registration_no": $(".uen_section #uen").val()},
                      dataType: 'json',
                      async: false,
                      success: function (response,data) {
                        $('#loadingWizardMessage').hide();
                            if(response)
                            {   
                                resignAuditorInterface(response,true);
                                if(transaction_id == undefined)
                                {
                                    $( document ).ready( checkPreviousServices );
                                }
                            }
                            else
                            {
                                if($("#body_resign_auditor .withdraw_auditor").length >= 1 || $("#body_resign_auditor .cancel_withdraw_auditor").length >= 1)
                                {
                                    $('#resign_auditor_table').show();
                                    $('#no_auditor_resign').hide();
                                }
                                else
                                {
                                    $('#resign_auditor_table').hide();
                                    $('#no_auditor_resign').show();
                                }
                            }

                        }
                    });
                }
            }

            if($(".uen_section #uen").val() != "")
            {
                $.ajax({ //Upload common input
                  url: "transaction/check_client_info",
                  type: "POST",
                  data: {"registration_no": $(".uen_section #uen").val()},
                  dataType: 'json',
                  async: false,
                  success: function (response,data) {
                    $('#loadingWizardMessage').hide();
                        if(response)
                        {   
                            $("#apointment_of_auditor_form #company_code").val(response[0]["company_code"]);
                            transaction_company_code = response[0]["company_code"];
                            $("#transaction_trans #company_code").val(response[0]["company_code"]);

                            $("#company_name").append(response[0]['company_name']);
                            $(".hidden_company_name").val(response[0]['company_name']);
                            $("#registration_no").append($(".uen_section #uen").val());
                        }
                        else
                        {
                            toastr.error("Please enter the correct Registration No", "Error");
                        }
                    }
                });
            }
            else
            {
                $('#loadingWizardMessage').hide();
                
            }

            loadLastTab();
        }
        else
        {
            $('#loadingWizardMessage').hide();
            toastr.error(array_for_appoint_auditor['error'], "Error");
        }
    });
}

function getChangeRegOfisInterface()
{
    $("#transaction_data .panel").remove();
    $.post("transaction/get_change_of_reg_ofis_page", {id: transaction_id, company_code: transaction_company_code, transaction_task_id: transaction_task_id, registration_no: $(".uen_section #uen").val()}, function(data){
        array_for_change_reg_ofis_address = JSON.parse(data);

        if(array_for_change_reg_ofis_address['error'] == null)
        {
            registered_address_info = array_for_change_reg_ofis_address[0]["registered_address_info"];
            transaction_change_reg_ofis = array_for_change_reg_ofis_address[0]["transaction_change_reg_ofis"];
            transaction_billing = array_for_change_reg_ofis_address[0]["transaction_billing"];
            
            $("#transaction_data").append(array_for_change_reg_ofis_address['interface']);

            $("#registration_no").append($(".uen_section #uen").val());

            $('.tab_2_effective_date').datepicker().datepicker('setStartDate', '01/01/1960');

            if(registered_address_info != undefined)
            {
              $.each(registered_address_info, function(key, val) {
                  var option = $('<option />');
                  option.attr('data-postal_code', val['postal_code']).attr('data-street_name', val['street_name']).attr('data-building_name', val['building_name']).attr('data-unit_no1', val['unit_no1']).attr('data-unit_no2', val['unit_no2']).attr('data-our_service_info_id', val['our_service_info_id']).attr('value', val['id']).text(val['service_name']);
                  if(transaction_change_reg_ofis != null)
                  {
                    if(transaction_change_reg_ofis[0]['our_service_regis_address_id'] != undefined && val['id'] == transaction_change_reg_ofis[0]['our_service_regis_address_id'])
                    {
                        option.attr('selected', 'selected');
                    }
                }
                  $("#trans_service_reg_off").append(option);
              });
            }

            if(transaction_change_reg_ofis)
            {
                changeRegOfisInterface(transaction_change_reg_ofis);
            }
            else
            {
                if($(".uen_section #uen").val() != "")
                {
                    $.ajax({ //Upload common input
                      url: "transaction/check_client_info",
                      type: "POST",
                      data: {"registration_no": $(".uen_section #uen").val()},
                      dataType: 'json',
                      async: false,
                      success: function (response,data) {
                        $('#loadingWizardMessage').hide();
                            if(response)
                            {   
                                $(".trans_company_code").val(response[0]["company_code"]);
                                $("#change_of_reg_ofis_form #company_code").val(response[0]["company_code"]);
                                $("#transaction_trans #company_code").val(response[0]["company_code"]);
                                $("#billing_form #company_code").val(response[0]["company_code"]);
                                transaction_company_code = response[0]["company_code"];

                                if(response[0]["unit_no1"] != "" || response[0]["unit_no2"] != "")
                                {
                                    client_unit = ' #'+response[0]["unit_no1"] +' - '+response[0]["unit_no2"];
                                }
                                else
                                {
                                    client_unit = "";
                                }
                                $("#company_name").append(response[0]['company_name']);
                                $(".hidden_company_name").val(response[0]['company_name']);

                                $("#old_registration_address").append(response[0]["street_name"]+'<br/>'+client_unit+' '+response[0]["building_name"]+'<br/> SINGAPORE '+response[0]["postal_code"]);
                                $(".hidden_old_registration_address").val(response[0]["street_name"]+'<br/>'+client_unit+' '+response[0]["building_name"]+'<br/> SINGAPORE '+response[0]["postal_code"]);
                                if(transaction_id == undefined)
                                {
                                    $( document ).ready( checkPreviousServices );
                                }
                            }
                            else
                            {
                                toastr.error("Please enter the correct Registration No", "Error");
                            }
                        }
                    });
                }
                else
                {
                    $('#loadingWizardMessage').hide();
                    
                }
            }

            //billing
            if(transaction_billing)
            {
                billingInterface(transaction_billing);
            }
            
            loadLastTab();
        }
        else
        {
            $('#loadingWizardMessage').hide();
            toastr.error(array_for_change_reg_ofis_address['error'], "Error");
        }
    });
}

function getResignDirectorInterface()
{
    $("#transaction_data .panel").remove();
    $.post("transaction/get_resign_of_director_page", {id: transaction_id, company_code: transaction_company_code, transaction_task_id: transaction_task_id, registration_no: $(".uen_section #uen").val()}, function(data){
        array_for_resign_director = JSON.parse(data);

        if(array_for_resign_director['error'] == null)
        {
            transaction_resign_director = array_for_resign_director[0]["transaction_resign_director"];
            get_latest_client_nominee_director_data = array_for_resign_director[0]["get_latest_client_nominee_director_data"];
            transaction_billing = array_for_resign_director[0]["transaction_billing"];

            $("#transaction_data").append(array_for_resign_director['interface']);

            $("#company_name").append(array_for_resign_director[0]["transaction_client"][0]["company_name"]);
            $("#registration_no").append($(".uen_section #uen").val());

            $('.notice_date').datepicker({ 
                dateFormat:'dd/mm/yyyy',
            }).datepicker('setStartDate', "01/01/1920");

            meeting_table_info(transaction_resign_director, array_for_resign_director);

            //Officers
            if(transaction_resign_director|| get_latest_client_nominee_director_data)
            {
                resignDirectorInterface(transaction_resign_director,false);
                appointResignNomineeDirectorInterface(get_latest_client_nominee_director_data);
            }
            else
            {
                if($(".uen_section #uen").val() != "")
                {
                    $.ajax({ //Upload common input
                      url: "transaction/get_resign_director_info",
                      type: "POST",
                      data: {"registration_no": $(".uen_section #uen").val()},
                      dataType: 'json',
                      async: false,
                      success: function (response,data) {
                        $('#loadingWizardMessage').hide();
                            if(response)
                            {   
                                resignDirectorInterface(response["director"],true);
                            }
                            else
                            {
                                $('#resign_director_table .resign_director').hide();
                            }
                        }
                    });
                }
            }

            if($(".uen_section #uen").val() != "")
            {
                $.ajax({ //Upload common input
                  url: "transaction/check_client_info",
                  type: "POST",
                  data: {"registration_no": $(".uen_section #uen").val()},
                  dataType: 'json',
                  async: false,
                  success: function (response,data) {
                    $('#loadingWizardMessage').hide();
                        if(response)
                        {   
                            $(".trans_company_code").val(response[0]["company_code"]);
                            $("#resign_of_director_form #company_code").val(response[0]["company_code"]);
                            $("#transaction_trans #company_code").val(response[0]["company_code"]);
                            $("#billing_form #company_code").val(response[0]["company_code"]);
                            transaction_company_code = response[0]["company_code"];

                            if(transaction_id == undefined)
                            {
                                $( document ).ready( checkPreviousServices );
                            }
                        }
                        else
                        {
                            toastr.error("Please enter the correct Registration No", "Error");
                        }
                    }
                });
            }
            else
            {
                $('#loadingWizardMessage').hide();
                
            }

            //billing
            if(transaction_billing)
            {
                billingInterface(transaction_billing);
            }

            loadLastTab();
        }
        else
        {
            $('#loadingWizardMessage').hide();
            toastr.error(array_for_resign_director['error'], "Error");
        }
    });
}

function getUpdateRegisterofController()
{
    $("#transaction_data .panel").remove();
    $.post("transaction/get_update_register_of_controller", {id: transaction_id, company_code: transaction_company_code, transaction_task_id: transaction_task_id, registration_no: $(".uen_section #uen").val()}, function(data){
        array_for_update_register_of_controller = JSON.parse(data);

        if(array_for_update_register_of_controller['error'] == null)
        {
            get_current_client_controller_data = array_for_update_register_of_controller[0]["get_current_client_controller_data"];
            get_latest_client_controller_data = array_for_update_register_of_controller[0]["get_latest_client_controller_data"];

            $("#transaction_data").append(array_for_update_register_of_controller['interface']);

            $("#company_name").append(array_for_update_register_of_controller[0]["transaction_client"][0]["company_name"]);
            $("#registration_no").append($(".uen_section #uen").val());

            if(get_current_client_controller_data || get_latest_client_controller_data)
            {
                registerControllerInterface(get_current_client_controller_data);
                registerLatestControllerInterface(get_latest_client_controller_data);
            }
            else
            {
                if($(".uen_section #uen").val() != "")
                {
                    $.ajax({ //Upload common input
                      url: "transaction/get_register_controller_info",
                      type: "POST",
                      data: {"registration_no": $(".uen_section #uen").val()},
                      dataType: 'json',
                      async: false,
                      success: function (response,data) {
                            $('#loadingWizardMessage').hide();
                            if(response)
                            {   
                                registerControllerInterface(response["controller"]);
                                registerLatestControllerInterface(false);
                            }
                            else
                            {
                                $( document ).ready(function() {
                                    $('#current_controller_table').DataTable();
                                });
                                $( document ).ready(function() {
                                    $('#latest_controller_table').DataTable();
                                });
                            }
                        }
                    });
                }
            }

            if($(".uen_section #uen").val() != "")
            {
                $.ajax({ //Upload common input
                  url: "transaction/check_client_info",
                  type: "POST",
                  data: {"registration_no": $(".uen_section #uen").val()},
                  dataType: 'json',
                  async: false,
                  success: function (response,data) {
                    $('#loadingWizardMessage').hide();
                        if(response)
                        {   
                            $(".trans_company_code").val(response[0]["company_code"]);
                            //$("#resign_of_director_form #company_code").val(response[0]["company_code"]);
                            $("#transaction_trans #company_code").val(response[0]["company_code"]);
                            transaction_company_code = response[0]["company_code"];

                            if(transaction_id == undefined)
                            {
                                $( document ).ready( checkPreviousServices );
                            }
                        }
                        else
                        {
                            toastr.error("Please enter the correct Registration No", "Error");
                        }
                    }
                });
            }
            else
            {
                $('#loadingWizardMessage').hide();
            }

            loadLastTab();
        }
        else
        {
            $('#loadingWizardMessage').hide();
            toastr.error(array_for_update_register_of_controller['error'], "Error");
        }
    });
}

function getUpdateRegisterofNomineeDirector()
{
    $("#transaction_data .panel").remove();
    $.post("transaction/get_update_register_of_nominee_director", {id: transaction_id, company_code: transaction_company_code, transaction_task_id: transaction_task_id, registration_no: $(".uen_section #uen").val()}, function(data){
        array_for_update_register_of_nominee_director = JSON.parse(data);

        if(array_for_update_register_of_nominee_director['error'] == null)
        {
            get_current_client_nominee_director_data = array_for_update_register_of_nominee_director[0]["get_current_client_nominee_director_data"];
            get_latest_client_nominee_director_data = array_for_update_register_of_nominee_director[0]["get_latest_client_nominee_director_data"];

            $("#transaction_data").append(array_for_update_register_of_nominee_director['interface']);

            //--------------------------tab----------------------------------------------------
            var controller_coll = document.getElementsByClassName("controller-collapsible");

            for (var g = 0; g < controller_coll.length; g++) {
                controller_coll[g].classList.toggle("controller-active");
                controller_coll[g].nextElementSibling.style.maxHeight = "100%";
            }

            for (var i = 0; i < controller_coll.length; i++) {
                controller_coll[i].addEventListener("click", function() {
                    this.classList.toggle("controller-active");
                    var content = this.nextElementSibling;
                    if (content.style.maxHeight){
                      content.style.maxHeight = null;
                    } else {
                      content.style.maxHeight = "100%";
                    } 
                });
            }
            //---------------------------------------------------------------------------------

            $("#company_name").append(array_for_update_register_of_nominee_director[0]["transaction_client"][0]["company_name"]);
            $("#registration_no").append($(".uen_section #uen").val());

            if(get_current_client_nominee_director_data || get_latest_client_nominee_director_data)
            {
                registerNomineeDirectorInterface(get_current_client_nominee_director_data);
                registerLatestNomineeDirectorInterface(get_latest_client_nominee_director_data);
            }
            else
            {
                if($(".uen_section #uen").val() != "")
                {
                    $.ajax({
                      url: "transaction/get_register_nominee_director_info",
                      type: "POST",
                      data: {"registration_no": $(".uen_section #uen").val()},
                      dataType: 'json',
                      async: false,
                      success: function (response,data) {
                            $('#loadingWizardMessage').hide();
                            if(response)
                            {   
                                registerNomineeDirectorInterface(response["nominee_director"]);
                                registerLatestNomineeDirectorInterface(false);
                            }
                            else
                            {
                                $( document ).ready(function() {
                                    $('#current_nominee_director_table').DataTable();
                                });
                                $( document ).ready(function() {
                                    $('#latest_nominee_director_table').DataTable();
                                });
                            }
                        }
                    });
                }
            }

            if($(".uen_section #uen").val() != "")
            {
                $.ajax({ //Upload common input
                  url: "transaction/check_client_info",
                  type: "POST",
                  data: {"registration_no": $(".uen_section #uen").val()},
                  dataType: 'json',
                  async: false,
                  success: function (response,data) {
                        $('#loadingWizardMessage').hide();
                        if(response)
                        {   
                            $(".trans_company_code").val(response[0]["company_code"]);
                            //$("#resign_of_director_form #company_code").val(response[0]["company_code"]);
                            $("#transaction_trans #company_code").val(response[0]["company_code"]);
                            transaction_company_code = response[0]["company_code"];
                            if(transaction_id == undefined)
                            {
                                $( document ).ready( checkPreviousServices );
                            }
                        }
                        else
                        {
                            toastr.error("Please enter the correct Registration No", "Error");
                        }
                    }
                });
            }
            else
            {
                $('#loadingWizardMessage').hide();
            }

            loadLastTab();
        }
        else
        {
            $('#loadingWizardMessage').hide();
            toastr.error(array_for_update_register_of_nominee_director['error'], "Error");
        }
    });
}

function getAppointmentSecretarialInterface()
{
    $("#transaction_data .panel").remove();
    $.post("transaction/get_appointment_of_secretarial_page", {id: transaction_id, company_code: transaction_company_code, transaction_task_id: transaction_task_id, registration_no: $(".uen_section #uen").val()}, function(data){
        var array_for_appoint_secretarial = JSON.parse(data);

        if(array_for_appoint_secretarial['error'] == null)
        {
            var transaction_appoint_new_secretarial = array_for_appoint_secretarial[0]["transaction_appoint_new_secretarial"];
            var transaction_resignation_of_company_secretary = array_for_appoint_secretarial[0]["transaction_resignation_of_company_secretary"];
            transaction_billing = array_for_appoint_secretarial[0]["transaction_billing"];
            
            $("#transaction_data").append(array_for_appoint_secretarial['interface']);

            $("#company_name").append(array_for_appoint_secretarial[0]["transaction_client"][0]["company_name"]);
            $(".hidden_company_name").val(array_for_appoint_secretarial[0]["transaction_client"][0]["company_name"]);
            $("#registration_no").append($(".uen_section #uen").val());

            if(transaction_resignation_of_company_secretary)
            {
                $("#resignation_of_company_secretary").val(transaction_resignation_of_company_secretary[0]["resignation_of_company_secretary"]);
                $("#resignation_of_corporate_secretarial_agent").val(transaction_resignation_of_company_secretary[0]["resignation_of_corporate_secretarial_agent"]);
                $("#resignation_of_corporate_secretarial_agent_address").val(transaction_resignation_of_company_secretary[0]["resignation_of_corporate_secretarial_agent_address"]);
            }
            //Officers
            if(transaction_appoint_new_secretarial)
            {
                //appointNewSecretarialInterface(transaction_appoint_new_secretarial);
                resignSecretarialInterface(transaction_appoint_new_secretarial);
            }
            else
            {
                if($(".uen_section #uen").val() != "")
                {
                    $.ajax({
                      url: "transaction/get_resign_secretarial_info",
                      type: "POST",
                      data: {"registration_no": $(".uen_section #uen").val()},
                      dataType: 'json',
                      async: false,
                      success: function (response,data) {
                        $('#loadingWizardMessage').hide();
                            if(response)
                            {   
                                resignSecretarialInterface(response,true);
                            }
                            else
                            {
                                if($("#body_resign_secretarial .withdraw_secretarial").length >= 1 || $("#body_resign_secretarial .cancel_withdraw_secretarial").length >= 1)
                                {
                                    $('#resign_secretarial_table').show();
                                    $('#no_secretarial_resign').hide();
                                }
                                else
                                {
                                    $('#resign_secretarial_table').hide();
                                    $('#no_secretarialr_resign').show();
                                }
                            }

                        }
                    });

                    add_service_engagment_row(3);
                }
            }

            if($(".uen_section #uen").val() != "")
            {
                $.ajax({ //Upload common input
                  url: "transaction/check_client_info",
                  type: "POST",
                  data: {"registration_no": $(".uen_section #uen").val()},
                  dataType: 'json',
                  async: false,
                  success: function (response,data) {
                    $('#loadingWizardMessage').hide();
                        if(response)
                        {   
                            $("#apointment_of_secretarial_form #company_code").val(response[0]["company_code"]);
                            $("#transaction_trans #company_code").val(response[0]["company_code"]);
                            $("#billing_form #company_code").val(response[0]["company_code"]);
                            transaction_company_code = response[0]["company_code"];

                            if(transaction_id == undefined)
                            {
                                $( document ).ready( checkPreviousServices );
                            }
                        }
                        else
                        {
                            toastr.error("Please enter the correct Registration No", "Error");
                        }
                    }
                });
            }
            else
            {
                $('#loadingWizardMessage').hide();
                
            }

            //billing
            if(transaction_billing)
            {
                billingInterface(transaction_billing);
            }
            
            loadLastTab();
        }
        else
        {
            $('#loadingWizardMessage').hide();
            toastr.error(array_for_appoint_secretarial['error'], "Error");
        }
    });
}

function getAppointNewDirectorInterface()
{
    $("#transaction_data .panel").remove();
    $.post("transaction/get_appointment_of_director_page", {id: transaction_id, company_code: transaction_company_code, transaction_task_id: transaction_task_id, registration_no: $(".uen_section #uen").val()}, function(data){
        array_for_appoint_director = JSON.parse(data);

        if(array_for_appoint_director['error'] == null)
        {
            transaction_appoint_new_director = array_for_appoint_director[0]["transaction_appoint_new_director"];

            $("#transaction_data").append(array_for_appoint_director['interface']);

            //Officers
            if(transaction_appoint_new_director)
            {
                appointNewDirectorInterface(transaction_appoint_new_director);
            }

            if($(".uen_section #uen").val() != "")
            {
                $.ajax({ //Upload common input
                  url: "transaction/check_client_info",
                  type: "POST",
                  data: {"registration_no": $(".uen_section #uen").val()},
                  dataType: 'json',
                  async: false,
                  success: function (response,data) {
                    $('#loadingWizardMessage').hide();
                        if(response)
                        {   
                            $("#apointment_of_director_form #company_code").val(response[0]["company_code"]);
                            $("#transaction_trans #company_code").val(response[0]["company_code"]);
                            transaction_company_code = response[0]["company_code"];

                            $("#company_name").append(response[0]['company_name']);
                            $(".hidden_company_name").val(response[0]['company_name']);
                            $("#registration_no").append($(".uen_section #uen").val());

                            if(transaction_id == undefined)
                            {
                                $( document ).ready( checkPreviousServices );
                            }
                        }
                        else
                        {
                            toastr.error("Please enter the correct Registration No", "Error");
                        }
                    }
                });
            }
            else
            {
                $('#loadingWizardMessage').hide();
                
            }
            loadLastTab();
        }
        else
        {
            $('#loadingWizardMessage').hide();
            toastr.error(array_for_appoint_director['error'], "Error");
        }
    });
}

function getTakeOverOfSecretarialInterface()
{
    $("#transaction_data .panel").remove();
    $.post("transaction/get_take_over_of_secretarial_page", {id: transaction_id, company_code: transaction_company_code, transaction_task_id: transaction_task_id}, function(data){
        array_for_incop_new_company = JSON.parse(data);
        registered_address_info = array_for_incop_new_company[0]["registered_address_info"];
        transaction_client = array_for_incop_new_company[0]["transaction_client"];
        transaction_client_officers = array_for_incop_new_company[0]["transaction_client_officers"];
        transaction_filing = array_for_incop_new_company[0]["transaction_filing"];
        transaction_billing = array_for_incop_new_company[0]["transaction_billing"];
        transaction_previous_secretarial = array_for_incop_new_company[0]["transaction_previous_secretarial"];
        transaction_document = array_for_incop_new_company[0]["document"];

        documentInterface(transaction_document);

        $("#transaction_data").append(array_for_incop_new_company['interface']);

        coll = document.getElementsByClassName("collapsible");

        if(transaction_client)
        {
            for (var g = 0; g < coll.length; g++) {
                coll[g].classList.toggle("incorp_active");
                coll[g].nextElementSibling.style.maxHeight = "100%";
            }
        }
        else
        {
            coll[0].classList.toggle("incorp_active");
            coll[0].nextElementSibling.style.maxHeight = "100%";
        }

        for (var i = 0; i < coll.length; i++) {
          coll[i].addEventListener("click", function() {
            this.classList.toggle("incorp_active");
            var content = this.nextElementSibling;
            if (content.style.maxHeight){
              content.style.maxHeight = null;
            } else {
              content.style.maxHeight = "100%";
            } 
          });
        }

        //Company_info
        if(transaction_client)
        {
            $('#upload_company_info #client_code').val(transaction_client[0]['client_code']);
            $('#upload_company_info #edit_company_name').val(transaction_client[0]['company_name']);
            $('#upload_company_info #activity1').val(transaction_client[0]['activity1']);
            $('#upload_company_info #activity2').val(transaction_client[0]['activity2']);

            if(transaction_client[0]["registered_address"] == 1)
            {
                $("#upload_company_info #use_registered_address").prop("checked", true);
                $(".service_reg_off_area").show();
            }
            else
            {
                $("#upload_company_info #use_registered_address").prop("checked", false);
                $(".service_reg_off_area").hide();
            }
            

            $('#upload_company_info #postal_code').val(transaction_client[0]['postal_code']);
            $('#upload_company_info #street_name').val(transaction_client[0]['street_name']);
            $('#upload_company_info #building_name').val(transaction_client[0]['building_name']);
            $('#upload_company_info #unit_no1').val(transaction_client[0]['unit_no1']);
            $('#upload_company_info #unit_no2').val(transaction_client[0]['unit_no2']);
            
            if(transaction_client[0]["registered_address"] == 1)
            {
                document.getElementById("postal_code").readOnly = true;
                document.getElementById("street_name").readOnly = true;
                document.getElementById("building_name").readOnly = true;
                document.getElementById("unit_no1").readOnly = true;
                document.getElementById("unit_no2").readOnly = true;
            }

            if(transaction_client[0]['company_type'] == "4" || transaction_client[0]['company_type'] == "5" || transaction_client[0]['company_type'] == "6")
            {
                $("#guarantee").show();
                $("#non-guarantee").hide();
            }
            else if(transaction_client[0]['company_type'] == "1" || transaction_client[0]['company_type'] == "2" || transaction_client[0]['company_type'] == "3")
            {
                $("#non-guarantee").show();
                $("#guarantee").hide();
            }
        }

        if(registered_address_info != undefined)
        {
          $.each(registered_address_info, function(key, val) {
              var option = $('<option />');
              option.attr('data-postal_code', val['postal_code']).attr('data-street_name', val['street_name']).attr('data-building_name', val['building_name']).attr('data-unit_no1', val['unit_no1']).attr('data-unit_no2', val['unit_no2']).attr('value', val['id']).text(val['service_name']);
              if(transaction_client != null)
              {
                if(transaction_client[0]['our_service_regis_address_id'] != undefined && val['id'] == transaction_client[0]['our_service_regis_address_id'])
                {
                    option.attr('selected', 'selected');
                }
            }
              $("#service_reg_off").append(option);
          });
        }

        //Officers
        if(transaction_client_officers)
        {
            officerInterface(transaction_client_officers);
        }

        //Controller
        // if(transaction_client_controller)
        // {
        //     controllerInterface(transaction_client_controller);
            
        // }

        //filing
        if(transaction_filing)
        {

            $('#year_end_date').datepicker({
                format: 'dd MM yyyy'
            });
            $(".filing_id").val(transaction_filing[0]["id"]);
            $("#year_end_date").val(transaction_filing[0]["year_end"]);
            get_financial_year_period(transaction_filing[0]["financial_year_period"]);
        }
        else
        {
            $('#year_end_date').datepicker({
                format: 'dd MM yyyy'
            });
            get_financial_year_period();
        }

        //billing
        if(transaction_billing)
        {
            billingInterface(transaction_billing);
        }

        if(transaction_previous_secretarial)
        {
            $(".previous_secretarial_info_id").val(transaction_previous_secretarial[0]["id"]);
            $("#previous_secretarial_company_name").val(transaction_previous_secretarial[0]["company_name"]);
            $("#previous_secretarial_postal_code").val(transaction_previous_secretarial[0]["postal_code"]);
            $("#previous_secretarial_street_name").val(transaction_previous_secretarial[0]["street_name"]);
            $("#previous_secretarial_building_name").val(transaction_previous_secretarial[0]["building_name"]);
            $("#previous_secretarial_unit_no1").val(transaction_previous_secretarial[0]["unit_no1"]);
            $("#previous_secretarial_unit_no2").val(transaction_previous_secretarial[0]["unit_no2"]);
        }

        //share_capital
        // if(transaction_member)
        // {
        //     memberInterface(transaction_member);
        // }   

        loadLastTab();
    });

    $(".uen_section").hide();
    $(".uen_section .uen").val("");
    $(".client_section").hide();
    $(".client_name_section").hide();
    $(".client_type").prop("disabled", true);
    $(".dropdown_client_name").prop("disabled", true);
    $(".input_client_name").prop("disabled", true);
}

function getIncorporationInterface()
{
    $("#transaction_data .panel").remove();
    $.post("transaction/get_incorporation_new_company_page", {id: transaction_id, company_code: transaction_company_code, transaction_task_id: transaction_task_id}, function(data){
        array_for_incop_new_company = JSON.parse(data);
        registered_address_info = array_for_incop_new_company[0]["registered_address_info"];
        transaction_client = array_for_incop_new_company[0]["transaction_client"];
        transaction_client_officers = array_for_incop_new_company[0]["transaction_client_officers"];
        transaction_client_controller = array_for_incop_new_company[0]["transaction_client_controller"];
        transaction_filing = array_for_incop_new_company[0]["transaction_filing"];
        transaction_billing = array_for_incop_new_company[0]["transaction_billing"];
        transaction_member = array_for_incop_new_company[0]["transaction_member"];
        transaction_client_signing_info = array_for_incop_new_company[0]["transaction_client_signing_info"];
        transaction_contact_person_info = array_for_incop_new_company[0]["transaction_contact_person_info"];
        if(transaction_contact_person_info != null)
        {
            client_contact_info_email = transaction_contact_person_info[0]['transaction_client_contact_info_email'];
            client_contact_info_phone = transaction_contact_person_info[0]['transaction_client_contact_info_phone'];
        }
        client_selected_reminder = array_for_incop_new_company[0]["transaction_client_selected_reminder"];
        transaction_document = array_for_incop_new_company[0]["document"];

        documentInterface(transaction_document);

        $("#transaction_data").append(array_for_incop_new_company['interface']);

        if(transaction_contact_person_info != null)
        {
            $("#setup_form #contact_name").val(transaction_contact_person_info[0]["name"]);
        }

        coll = document.getElementsByClassName("collapsible");

        if(transaction_client)
        {
            for (var g = 0; g < coll.length; g++) {
                coll[g].classList.toggle("incorp_active");
                coll[g].nextElementSibling.style.maxHeight = "100%";
            }
        }
        else
        {
            coll[0].classList.toggle("incorp_active");
            coll[0].nextElementSibling.style.maxHeight = "100%";
        }

        for (var i = 0; i < coll.length; i++) {
          coll[i].addEventListener("click", function() {
            this.classList.toggle("incorp_active");
            var content = this.nextElementSibling;
            if (content.style.maxHeight){
              content.style.maxHeight = null;
            } else {
              content.style.maxHeight = "100%";
            } 
          });
        }

        //Company_info
        if(transaction_client)
        {
            $('#upload_company_info #client_code').val(transaction_client[0]['client_code']);
            $('#upload_company_info #edit_company_name').val(transaction_client[0]['company_name']);
            $('#upload_company_info #activity1').val(transaction_client[0]['activity1']);
            $('#upload_company_info #description1').text(transaction_client[0]['description1']);
            $('#upload_company_info #activity2').val(transaction_client[0]['activity2']);
            $('#upload_company_info #description2').text(transaction_client[0]['description2']);

            if(transaction_client[0]["registered_address"] == 1)
            {
                $("#upload_company_info #use_registered_address").prop("checked", true);
                $(".service_reg_off_area").show();
            }
            else
            {
                $("#upload_company_info #use_registered_address").prop("checked", false);
                $(".service_reg_off_area").hide();
            }
            

            $('#upload_company_info #postal_code').val(transaction_client[0]['postal_code']);
            $('#upload_company_info #street_name').val(transaction_client[0]['street_name']);
            $('#upload_company_info #building_name').val(transaction_client[0]['building_name']);
            $('#upload_company_info #unit_no1').val(transaction_client[0]['unit_no1']);
            $('#upload_company_info #unit_no2').val(transaction_client[0]['unit_no2']);
            
            if(transaction_client[0]["registered_address"] == 1)
            {
                document.getElementById("postal_code").readOnly = true;
                document.getElementById("street_name").readOnly = true;
                document.getElementById("building_name").readOnly = true;
                document.getElementById("unit_no1").readOnly = true;
                document.getElementById("unit_no2").readOnly = true;
            }

            if(transaction_client[0]['company_type'] == "4" || transaction_client[0]['company_type'] == "5" || transaction_client[0]['company_type'] == "6")
            {
                $("#guarantee").show();
                $("#non-guarantee").hide();
            }
            else if(transaction_client[0]['company_type'] == "1" || transaction_client[0]['company_type'] == "2" || transaction_client[0]['company_type'] == "3")
            {
                $("#non-guarantee").show();
                $("#guarantee").hide();
            }
        }

        if(registered_address_info != undefined)
        {
            $.each(registered_address_info, function(key, val) {
                var option = $('<option />');
                option.attr('data-postal_code', val['postal_code']).attr('data-street_name', val['street_name']).attr('data-building_name', val['building_name']).attr('data-unit_no1', val['unit_no1']).attr('data-unit_no2', val['unit_no2']).attr('data-our_service_info_id', val['our_service_info_id']).attr('value', val['id']).text(val['service_name']);
                if(transaction_client != null)
                {
                    if(transaction_client[0]['our_service_regis_address_id'] != undefined && val['id'] == transaction_client[0]['our_service_regis_address_id'])
                    {
                        option.attr('selected', 'selected');
                    }
                }
                $("#service_reg_off").append(option);
            });
        }

        //Officers
        if(transaction_client_officers)
        {
            officerInterface(transaction_client_officers);
        }

        //Controller
        if(transaction_client_controller)
        {
            controllerInterface(transaction_client_controller);
        }

        //filing
        if(transaction_filing)
        {

            $('#year_end_date').datepicker({
                format: 'dd MM yyyy'
            });
            $(".filing_id").val(transaction_filing[0]["id"]);
            $("#year_end_date").val(transaction_filing[0]["year_end"]);
            get_financial_year_period(transaction_filing[0]["financial_year_period"]);
        }
        else
        {
            $('#year_end_date').datepicker({
                format: 'dd MM yyyy'
            });
            get_financial_year_period();
        }

        //billing
        if(transaction_billing)
        {
            billingInterface(transaction_billing);
        }

        //share_capital
        if(transaction_member)
        {
            memberInterface(transaction_member);
        }   

        loadLastTab();
    });

    $(".uen_section").hide();
    $(".uen_section .uen").val("");
    $(".client_section").hide();
    $(".client_name_section").hide();
    $(".client_type").prop("disabled", true);
    $(".dropdown_client_name").prop("disabled", true);
    $(".input_client_name").prop("disabled", true);
}

function loadLastTab()
{
    $(document).ready(function() {
        // go to the latest tab, if it exists:
        var lastTab = localStorage.getItem('lastTab');
        if (lastTab) {
            $('[href="' + lastTab + '"]').tab('show');
        }
        //localStorage.clear();
        $('#loadingTransaction').hide();
    });
}

function loadFirstTab()
{
    $(document).ready(function() {
        $('#loadingTransaction').hide();
    });
}

function changeFYEInterface(transaction_change_FYE)
{
    $(".transaction_change_FYE_id").val(transaction_change_FYE[0]["id"]);
    $(".trans_company_code").val(transaction_change_FYE[0]["company_code"]);
    $("#change_of_FYE_form #company_code").val(transaction_change_FYE[0]["company_code"]);
    transaction_company_code = transaction_change_FYE[0]["company_code"];

    $("#company_name").append(transaction_change_FYE[0]['company_name']);
    $(".hidden_company_name").val(transaction_change_FYE[0]['company_name']);

    $("#FYE").append(transaction_change_FYE[0]['old_year_end']);
    $(".hidden_old_FYE").val(transaction_change_FYE[0]['old_year_end']);

    $("#old_financial_year_period").append(transaction_change_FYE[0]['old_financial_year_period']);
    $(".hidden_old_period").val(transaction_change_FYE[0]['old_financial_year_period']);

    $(".tab_2_effective_date").val(transaction_change_FYE[0]['effective_date']);

    $(".new_FYE").val(transaction_change_FYE[0]['new_year_end']);
}

function incorporationSubsidiaryInterface(transaction_incorporation_subsidiary)
{

    // for(var i = 0; i < transaction_incorporation_subsidiary.length; i++)
    // {
    //     $a = ""; 
    //     $a += '<tr class="row_corp_rep">';
    //     $a += '<td><input type="text" style="text-transform:uppercase;" name="subsidiary_name[]" id="subsidiary_name_'+i+'" class="form-control subsidiary_name" value="'+transaction_incorporation_subsidiary[i]["subsidiary_name"]+'"/></td>';
    //     $a += '<td><input type="text" style="text-transform:uppercase;" name="corp_rep_name[]" id="corp_rep_name" class="form-control" value="'+transaction_incorporation_subsidiary[i]["name_of_corp_rep"]+'"/></td>';
    //     $a += '<td><input type="text" style="text-transform:uppercase;" name="corp_rep_identity_number[]" class="form-control corp_rep_identity_number" value="'+transaction_incorporation_subsidiary[i]["identity_number"]+'" id="corp_rep_identity_number" maxlength="15"/></td>';
    //     $a += '<td><div class="input-group" style="width: 150px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="corp_rep_date_of_appointment" name="date_of_appointment[]" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="'+transaction_incorporation_subsidiary[i]["corp_rep_effective_date"]+'" placeholder="DD/MM/YYYY"></div></td>';
    //     $a += '<td><div class="input-group" style="width: 150px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="corp_rep_date_of_cessation" name="date_of_cessation[]" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="'+transaction_incorporation_subsidiary[i]["cessation_date"]+'" placeholder="DD/MM/YYYY"></div></td>';
    //     $a += '<td class="action"><div style="display: inline-block;"><button type="button" class="btn btn-primary" onclick="delete_officer(this);">Delete</button></div></td>';
    //     $a += '</tr>';
    //     //<input type="hidden" name="subsidiary_id[]" class="form-control subsidiary_id" id="subsidiary_id_'+i+'" value="'+transaction_incorporation_subsidiary[i]["client_id"]+'"/>
    //     $("#body_corp_rep").prepend($a); 

    //     $('#corp_rep_date_of_appointment').datepicker({ 
    //         dateFormat:'dd/mm/yyyy',
    //     });

    //     $('#corp_rep_date_of_cessation').datepicker({ 
    //         dateFormat:'dd/mm/yyyy',
    //     });
    // }

    $(".transaction_incorp_subsidiary_id").val(transaction_incorporation_subsidiary[0]["id"]);
    $(".trans_company_code").val(transaction_incorporation_subsidiary[0]["company_code"]);
    $("#incorp_subsidiary_form #company_code").val(transaction_incorporation_subsidiary[0]["company_code"]);
    transaction_company_code = transaction_incorporation_subsidiary[0]["company_code"];

    $("#subsidiary_name").val(transaction_incorporation_subsidiary[0]['subsidiary_name']);
    $("#country_of_incorporation").val(transaction_incorporation_subsidiary[0]['country_of_incorporation']);
    $("#total_investment_amount").val(addCommas(transaction_incorporation_subsidiary[0]['total_investment_amount']));
    $("#corp_rep_name").val(transaction_incorporation_subsidiary[0]['name_of_corp_rep']);
    $("#corp_rep_identity_number").val(transaction_incorporation_subsidiary[0]['identity_number']);
    $(".propose_effective_date").val(transaction_incorporation_subsidiary[0]['propose_effective_date']);
}

function issueDividendInterface(transaction_issue_dividend)
{
    $("#issue_dividend_form #company_code").val(transaction_issue_dividend[0]["company_code"]);
    $(".transaction_issue_dividend_id").val(transaction_issue_dividend[0]["id"]);
    $(".devidend_per_share").val(transaction_issue_dividend[0]["devidend_per_share"]);
    $(".total_dividend_amount").val(addCommas(transaction_issue_dividend[0]["total_dividend_amount"]));
    $(".declare_of_fye").val(transaction_issue_dividend[0]["declare_of_fye"]);
    $(".devidend_of_cut_off_date").val(transaction_issue_dividend[0]["devidend_of_cut_off_date"]);
    $(".devidend_payment_date").val(transaction_issue_dividend[0]["devidend_payment_date"]);

    for($t = 0; $t < transaction_issue_dividend.length; $t++)
    {
        var table_id = $t + 1;
        $b =""; 
        $b += '<tr class="member_info_'+table_id+' remove_member_info_class row_member_section">';
        $b += '<td>'+transaction_issue_dividend[$t]["shareholder_name"]+'<input type="hidden" name="shareholder_name[]" value="'+transaction_issue_dividend[$t]["shareholder_name"]+'"></td>';
        $b += '<td style="text-align:right"><span class="balance">'+addCommas(transaction_issue_dividend[$t]["number_of_share"])+'</span><input type="hidden" name="balance[]" class="hidden_balance" value="'+transaction_issue_dividend[$t]["number_of_share"]+'"><input type="hidden" class="form-control" name="officer_id[]" id="officer_id" value="'+(transaction_issue_dividend[$t]["officer_id"]!=null ? transaction_issue_dividend[$t]["officer_id"] : (transaction_issue_dividend[$t]["officer_company_id"] != null ? transaction_issue_dividend[$t]["officer_company_id"] : transaction_issue_dividend[$t]["client_company_id"]))+'"/></td>';
        $b += '<td style="text-align:right"><span class="devidend_paid">'+addCommas(transaction_issue_dividend[$t]["devidend_paid"])+'</span><input type="hidden" name="devidend_paid[]" class="hidden_devidend_paid" value="'+transaction_issue_dividend[$t]["devidend_paid"]+'"><input type="hidden" class="form-control" name="field_type[]" id="field_type" value="'+(transaction_issue_dividend[$t]["officer_field_type"]!=null ? transaction_issue_dividend[$t]["officer_field_type"] : (transaction_issue_dividend[$t]["officer_company_field_type"] != null ? transaction_issue_dividend[$t]["officer_company_field_type"] : transaction_issue_dividend[$t]["client_field_type"]))+'"/></td>';
        $b += '</tr>';
        
        $("#body_issue_dividend").append($b);   
    }

    $b =""; 
    $b += '<tr class="total_amount remove_member_info_class">';
    $b += '<td style="font-weight: bold;">Total</td>';
    $b += '<td style="text-align:right"><span class="total_balance">'+addCommas(transaction_issue_dividend[0]["total_number_of_share"])+'</span><input type="hidden" name="total_balance" class="hidden_total_balance" value="'+transaction_issue_dividend[0]["total_number_of_share"]+'"></td>';
    $b += '<td style="text-align:right"><span class="total_devidend_paid">'+addCommas(transaction_issue_dividend[0]["total_devidend_paid"])+'</span><input type="hidden" name="total_devidend_paid" class="hidden_total_devidend_paid" value="'+transaction_issue_dividend[0]["total_devidend_paid"]+'"></td>';
    $b += '</tr>';

    $("#body_issue_dividend").append($b);
}

function changeStrikeOffInterface(transaction_strike_off, reason_for_application)
{   
    $("#w2-strike_off_form .company_code").val(transaction_strike_off[0]['company_code']);
    $("#w2-strike_off_form .transaction_strike_off_id").val(transaction_strike_off[0]['id']);

    $("#w2-strike_off_form #company_name").text(transaction_strike_off[0]['company_name']);

    $(".agm_date_info").val(transaction_strike_off[0]['agm_date']);

    $(".date_fs_sent_to_member").val(transaction_strike_off[0]['date_fs_sent_to_member']);

    $("#agm_time").val(transaction_strike_off[0]['agm_time']);

    $(".notice_date").val(transaction_strike_off[0]['notice_date']);

    $(".ceased_date").val(transaction_strike_off[0]["ceased_date"]);

    $.each(reason_for_application, function(key, val) {
        var option = $('<option />');
        option.attr('value', key).text(val);
        if(transaction_strike_off[0]["reason_for_application_id"] != undefined && key == transaction_strike_off[0]["reason_for_application_id"])
        {
            option.attr('selected', 'selected');
        }
        $(".reason_for_application").append(option);
    });

    if(transaction_strike_off[0]["reason_for_application_id"] == 0 || transaction_strike_off[0]["reason_for_application_id"] == 1)
    {
        $(".ceased_date_row").hide();
        $(".ceased_date").prop("disabled", true);
    }
    else
    {
        $(".ceased_date_row").show();
        $(".ceased_date").prop("disabled", false);
    }
    
}

function issueDirectorFeeInterface(transaction_issue_director_fee, currency)
{
    $(".transaction_issue_director_fee_id").val(transaction_issue_director_fee[0]["id"]);
    $(".declare_of_fye").val(transaction_issue_director_fee[0]["declare_of_fye"]);
    $(".resolution_date").val(transaction_issue_director_fee[0]["resolution_date"]);
    $(".meeting_date").val(transaction_issue_director_fee[0]["meeting_date"]);
    $(".notice_date").val(transaction_issue_director_fee[0]["notice_date"]);
    if(transaction_issue_director_fee != "")
    {   
        var director_list = transaction_issue_director_fee;
        for($r = 0; $r < director_list.length; $r++)
        {
            $b=""; 
            $b += '<tr class="director_fee_table'+$r+'">';
            $b += '<td style="text-align: left;">'+director_list[$r]["identification_register_no"]+'<input type="hidden" name="identification_register_no[]" value="'+director_list[$r]["identification_register_no"]+'"></td>';
            $b += '<td style="text-align: left;">'+director_list[$r]["director_name"]+'<input type="hidden" name="director_name[]" value="'+director_list[$r]["director_name"] +'"></td>';
            $b += '<td style="text-align: center;">'+director_list[$r]["date_of_appointment"]+'<input type="hidden" name="date_of_appointment[]" value="'+director_list[$r]["date_of_appointment"]+'"><input type="hidden" name="officer_id[]" value="'+director_list[$r]["officer_id"]+'"><input type="hidden" name="officer_field_type[]" value="'+director_list[$r]["officer_field_type"]+'"></td>';
            $b += '<td><select class="form-control currency" style="width: 200px;" name="currency[]" id="currency""><option value="0">Select Currency</option></select></td>';
            $b += '<td style="text-align: center;"><input class="form-control numberdes" style="text-align: right; width: 200px;" name="director_fee[]" id="director_fee" pattern="^[0-9,]+$" value="'+addCommas(director_list[$r]["director_fee"])+'"></td>';
            $b += '</tr>';

            $("#body_issue_director_fee").append($b);

            //var currency = array_for_issue_director_fee[0]["currency"];

            $.each(currency, function(key, val) {
                var option = $('<option />');
                option.attr('value', key).text(val);

                if(director_list)
                {
                    if(director_list[$r]["currency"] != undefined && key == director_list[$r]["currency"])
                    {
                        option.attr('selected', 'selected');
                    }
                }
                $(".director_fee_table"+$r).find(".currency").append(option);
            });
        }
    }
}

function changeBizActivityInterface(transaction_change_biz_activity)
{
    $(".transaction_change_biz_activity_id").val(transaction_change_biz_activity[0]["id"]);
    $(".trans_company_code").val(transaction_change_biz_activity[0]["company_code"]);
    $("#change_of_biz_activity_form #company_code").val(transaction_change_biz_activity[0]["company_code"]);
    transaction_company_code = transaction_change_biz_activity[0]["company_code"];

    $("#company_name").append(transaction_change_biz_activity[0]['company_name']);
    $(".hidden_company_name").val(transaction_change_biz_activity[0]['company_name']);

    $("#activity_1").append(transaction_change_biz_activity[0]['old_activity1']);
    $("#description_1").append(transaction_change_biz_activity[0]['old_description1']);
    $("#activity_2").append(transaction_change_biz_activity[0]['old_activity2']);
    $("#description_2").append(transaction_change_biz_activity[0]['old_description2']);

    $(".hidden_old_activity1").val(transaction_change_biz_activity[0]['old_activity1']);
    $(".hidden_old_description1").val(transaction_change_biz_activity[0]['old_description1']);
    $(".hidden_old_activity2").val(transaction_change_biz_activity[0]['old_activity2']);
    $(".hidden_old_description2").val(transaction_change_biz_activity[0]['old_description2']);
    
    if(transaction_change_biz_activity[0]['remove_activity_2'] == 1)
    {
        $('.remove_activity_2').prop('checked', true);
        $('#new_activity2').prop('readOnly', true);
        $('#new_description2').prop('disabled', true);
    }
    else
    {   
        $('.remove_activity_2').prop('checked', false);
        $('#new_activity2').prop('readOnly', false);
        $('#new_description2').prop('disabled', false);
    }

    $("#new_activity1").val(transaction_change_biz_activity[0]['activity1']);
    $("#new_description1").val(transaction_change_biz_activity[0]['description1']);
    $("#new_activity2").val(transaction_change_biz_activity[0]['activity2']);
    $("#new_description2").val(transaction_change_biz_activity[0]['description2']);
    $(".tab_2_effective_date").val(transaction_change_biz_activity[0]['effective_date']);
}

function changeCompanyNameInterface(transaction_change_company_name)
{
    $(".transaction_change_company_name_id").val(transaction_change_company_name[0]["id"]);
    $(".trans_company_code").val(transaction_change_company_name[0]["company_code"]);
    $("#change_of_company_name_form #company_code").val(transaction_change_company_name[0]["company_code"]);
    transaction_company_code = transaction_change_company_name[0]["company_code"];

    $("#company_name").append(transaction_change_company_name[0]['company_name']);
    $(".hidden_company_name").val(transaction_change_company_name[0]['company_name']);

    $("#new_company_name").val(transaction_change_company_name[0]['new_company_name']);
    $(".tab_2_effective_date").val(transaction_change_company_name[0]['effective_date']);
}

function changeRegOfisInterface(transaction_change_reg_ofis)
{
    $(".transaction_change_regis_ofis_address_id").val(transaction_change_reg_ofis[0]["id"]);
    $(".trans_company_code").val(transaction_change_reg_ofis[0]["company_code"]);
    $("#billing_form #company_code").val(transaction_change_reg_ofis[0]["company_code"]);
    $("#change_of_reg_ofis_form #company_code").val(transaction_change_reg_ofis[0]["company_code"]);
    transaction_company_code = transaction_change_reg_ofis[0]["company_code"];

    $("#company_name").append(transaction_change_reg_ofis[0]['company_name']);
    $(".hidden_company_name").val(transaction_change_reg_ofis[0]['company_name']);

    $("#old_registration_address").append(transaction_change_reg_ofis[0]['old_registration_address']);
    $(".hidden_old_registration_address").val(transaction_change_reg_ofis[0]['old_registration_address']);

    if(transaction_change_reg_ofis[0]["registered_address"] == 1)
    {
        $("#use_registered_address").prop("checked", true);
        $(".service_reg_off_area").show();
        document.getElementById("postal_code").readOnly = true;
        document.getElementById("street_name").readOnly = true;
        document.getElementById("building_name").readOnly = true;
        document.getElementById("unit_no1").readOnly = true;
        document.getElementById("unit_no2").readOnly = true;
    }
    else
    {
        $("#use_registered_address").prop("checked", false);
        $(".service_reg_off_area").hide();
        document.getElementById("postal_code").readOnly = false;
        document.getElementById("street_name").readOnly = false;
        document.getElementById("building_name").readOnly = false;
        document.getElementById("unit_no1").readOnly = false;
        document.getElementById("unit_no2").readOnly = false;
    }

    $("#postal_code").val(transaction_change_reg_ofis[0]['postal_code']);
    $("#street_name").val(transaction_change_reg_ofis[0]['street_name']);
    $("#building_name").val(transaction_change_reg_ofis[0]['building_name']);
    $("#unit_no1").val(transaction_change_reg_ofis[0]['unit_no1']);
    $("#unit_no2").val(transaction_change_reg_ofis[0]['unit_no2']);

    $(".tab_2_effective_date").val(transaction_change_reg_ofis[0]['effective_date']);
}

function resignAuditorInterface(transaction_resign_new_auditor,is_create = null)
{
    var appoint_auditor = [];
    for(var i = 0; i < transaction_resign_new_auditor.length; i++)
    {
        if(transaction_resign_new_auditor[i]["date_of_appointment"] != "" && (transaction_resign_new_auditor[i]["appoint_resign_flag"] ==  "resign" || is_create) )
        {
            $a="";
            $a += '<tr class="row_resign_auditor">';
            $a += '<td><select class="form-control position" style="text-align:right;" name="resign_position[]" id="resign_position'+i+'" disabled="disabled"><option value="0" >Select Position</option></select><input type="hidden" class="form-control hidden_position" name="hidden_position[]" id="hidden_position" value="'+transaction_resign_new_auditor[i]["position"]+'"/><div id="alternate_of" hidden><p style="font-weight:bold;">Alternate of: </p><select class="form-control select_alternate_of" id="select_alternate_of'+i+'" style="text-align:right;width: 150px;" name="resign_alternate_of[]"><option value="" >Select Director</option></select><div id="form_alternate_of"></div></div></td>';
            $a += '<td><input type="text" style="text-transform:uppercase;" name="resign_identification_register_no[]" class="form-control" value="'+ (transaction_resign_new_auditor[i]["identification_no"]!=null ? transaction_resign_new_auditor[i]["identification_no"] : transaction_resign_new_auditor[i]["register_no"]) +'" id="gid_add_officer" maxlength="15" disabled="disabled"/><input type="hidden" class="form-control" name="hidden_resign_identification_register_no[]" id="hidden_resign_identification_register_no" value="'+ (transaction_resign_new_auditor[i]["identification_no"]!=null ? transaction_resign_new_auditor[i]["identification_no"] : transaction_resign_new_auditor[i]["register_no"]) +'"/><div style="height:16px;" ><a class="" href="'+ url + 'personprofile/add" style="cursor:pointer;" id="add_office_person_link" target="_blank" hidden onclick="add_officers_person(this)"><div style="cursor:pointer;" id="click_add_person">Click here to <span style="font-weight:bold">Add Person</span></div></a></div></td>';
            $a += '<td><input type="text" style="text-transform:uppercase;" name="resign_name[]" id="name" class="form-control" value="'+ (transaction_resign_new_auditor[i]["company_name"]!=null ? transaction_resign_new_auditor[i]["company_name"] : transaction_resign_new_auditor[i]["name"]) +'" readonly/><div id="form_name"></div><div class="hidden"><input type="text" class="form-control" name="resign_client_officer_id[]" id="client_officer_id" value="'+transaction_resign_new_auditor[i]["id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="resign_officer_id[]" id="officer_id" value="'+transaction_resign_new_auditor[i]["officer_id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="resign_officer_field_type[]" id="officer_field_type" value="'+transaction_resign_new_auditor[i]["field_type"]+'"/></div></td>';
            $a += '<td><div class="input-group" style="width: 150px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="officer_date_of_appointment" name="resign_date_of_appointment[]" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="'+transaction_resign_new_auditor[i]["date_of_appointment"]+'" placeholder="DD/MM/YYYY" disabled="disabled"></div><input type="hidden" class="form-control" name="hidden_date_of_appointment[]" id="hidden_date_of_appointment" value="'+transaction_resign_new_auditor[i]["date_of_appointment"]+'"/></td>';
            $a += '<td><div class="input-group" style="width: 150px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker officer_date_of_cessation" id="officer_date_of_cessation'+i+'" name="resign_date_of_cessation[]" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="'+transaction_resign_new_auditor[i]["date_of_cessation"]+'" placeholder="DD/MM/YYYY" disabled="disabled"></div><input type="hidden" class="form-control" name="hidden_date_of_cessation[]" id="hidden_date_of_cessation" value="'+transaction_resign_new_auditor[i]["date_of_cessation"]+'"/></td>';
            
            $a += '<td><select onclick="change_auditor_reason_selection(this);" class="form-control resign_auditor_reason_selection" name="resign_auditor_reason_selection[]" id="resign_auditor_reason_selection'+i+'" style="width:100%;" disabled="disabled"><option value="DECEASED">DECEASED</option><option value="DISQUALIFIED">DISQUALIFIED</option><option value="RESIGNED">RESIGNED</option><option value="OTHERS">OTHERS</option></select><input type="hidden" class="form-control" name="hidden_resign_auditor_reason_selection[]" id="hidden_resign_auditor_reason_selection" value="'+transaction_resign_new_auditor[i]["reason_selected"]+'"/><br>';
            $a += '<textarea class="form-control resign_auditor_reason" name="resign_auditor_reason[]" id="resign_auditor_reason" style="width:100%;height:70px;text-transform:uppercase;display: none;">'+(transaction_resign_new_auditor[i]["reason"]!=undefined ? transaction_resign_new_auditor[i]["reason"] : "")+'</textarea><input type="hidden" class="form-control" name="hidden_resign_auditor_reason[]" id="hidden_resign_auditor_reason" value="'+transaction_resign_new_auditor[i]["reason"]+'"/></td>';
    
            $a += '<td class="action"><div style="display: inline-block;"><button type="button" id="withdraw_auditor'+i+'" class="btn btn-primary withdraw_auditor" onclick="withdraw_auditor(this);">Withdraw</button></div></td>';
            $a += '</tr>';

            $("#body_resign_auditor").append($a);

            $('#officer_date_of_cessation'+i).datepicker({ 
                dateFormat:'dd/mm/yyyy',
            }).datepicker('setStartDate', transaction_resign_new_auditor[i]["date_of_appointment"]).on('changeDate', function(ev) {
                $(this).parent().parent().find("#hidden_date_of_cessation").val($(this).val());
            });
            if(transaction_resign_new_auditor[i]['reason_selected'] != '')
            {
                $('#officer_date_of_cessation'+i).parent().parent().parent().find("#resign_auditor_reason_selection"+i).val(transaction_resign_new_auditor[i]['reason_selected']);
            }

            if(transaction_resign_new_auditor[i]['reason_selected'] == 'OTHERS' || (transaction_resign_new_auditor[i]['reason_selected'] == "" && transaction_resign_new_auditor[i]["reason"]!="") )
            {
                $('#officer_date_of_cessation'+i).parent().parent().parent().find("#resign_auditor_reason_selection"+i).val('OTHERS');
                $('#officer_date_of_cessation'+i).parent().parent().parent().find("#resign_auditor_reason").show();
                //$('#officer_date_of_cessation'+i).parent().parent().parent().find("#resign_auditor_reason").prop("disabled", true);
            }

            if($('#officer_date_of_cessation'+i).val() != "" || $('#resign_auditor_reason_selection'+i+' :selected').val() != undefined)
            {
                $('#officer_date_of_cessation'+i).prop("disabled", false);
                // $('#officer_date_of_cessation'+i).parent().parent().parent().find("#resign_auditor_reason").prop("disabled", false);
                $('#officer_date_of_cessation'+i).parent().parent().parent().find("#resign_auditor_reason_selection"+i).prop("disabled", false);

                $('#officer_date_of_cessation'+i).parent().parent().parent().find("#withdraw_auditor"+i).html('Cancel');
                $('#officer_date_of_cessation'+i).parent().parent().parent().find("#withdraw_auditor"+i).toggleClass('cancel_withdraw_auditor');
                $('#officer_date_of_cessation'+i).parent().parent().parent().find("#withdraw_auditor"+i).removeClass('withdraw_auditor');
            }

            !function (i) {
                $.ajax({
                    type: "POST",
                    url: "transaction/get_client_officers_position",
                    data: {"position": transaction_resign_new_auditor[i]["position"]},
                    dataType: "json",
                    success: function(data){
                        $("#form"+i+" #resign_position"+i+"").find("option:eq(0)").html("Select Position");
                        if(data.tp == 1){
                            $.each(data['result'], function(key, val) {
                                var option = $('<option />');
                                option.attr('value', key).text(val);
                                if(data.selected_client_officers_position != null && key == data.selected_client_officers_position)
                                {
                                    option.attr('selected', 'selected');
                                }
                                $("#resign_position"+i+"").append(option);
                            });
                            
                        }
                        else{
                            alert(data.msg);
                        }

                        
                    }               
                });
            } (i);
        }
        else
        {
            appoint_auditor.push(transaction_resign_new_auditor[i]);
            
        }
        //check_incorp_date();
    }

    if(appoint_auditor.length > 0)
    {
        appointNewAuditorInterface(appoint_auditor);
    }
    
    if($("#body_resign_auditor .withdraw_auditor").length >= 1 || $("#body_resign_auditor .cancel_withdraw_auditor").length >= 1)
    {
        $('#resign_auditor_table').show();
        $('#no_auditor_resign').hide();
    }
    else
    {
        $('#resign_auditor_table').hide();
        $('#no_auditor_resign').show();
    }

    $(".resign_auditor_reason").on("change keyup paste", function() {
        $(this).parent().find("#hidden_resign_auditor_reason").val($(this).val());
    });
}

function appointNewAuditorInterface(transaction_appoint_new_auditor)
{
    for(var i = 0; i < transaction_appoint_new_auditor.length; i++)
    {
        $a="";
        $a += '<tr class="row_appoint_resign_auditor">';
        $a += '<td><select class="form-control position" style="text-align:right;" name="position[]" id="position'+i+'" disabled="disabled"><option value="0" >Select Position</option></select><div id="form_position"></div><div id="alternate_of" hidden><p style="font-weight:bold;">Alternate of: </p><select class="form-control select_alternate_of" id="select_alternate_of'+i+'" style="text-align:right;width: 150px;" name="alternate_of[]"><option value="" >Select Director</option></select><div id="form_alternate_of"></div></div></td>';
        $a += '<td><input type="text" style="text-transform:uppercase;" name="identification_register_no[]" class="form-control" value="'+ (transaction_appoint_new_auditor[i]["identification_no"]!=null ? transaction_appoint_new_auditor[i]["identification_no"] : transaction_appoint_new_auditor[i]["register_no"]) +'" id="gid_add_auditor_officer" maxlength="15"/><div id="form_identification_register_no"></div><div style="height:16px;" ><a class="" href="'+ url + 'personprofile/add" style="cursor:pointer;" id="add_office_person_link" target="_blank" hidden onclick="add_officers_person(this)"><div style="cursor:pointer;" id="click_add_person">Click here to <span style="font-weight:bold">Add Person</span></div></a></div></td>';
        $a += '<td><input type="text" style="text-transform:uppercase;" name="name[]" id="name" class="form-control" value="'+ (transaction_appoint_new_auditor[i]["company_name"]!=null ? transaction_appoint_new_auditor[i]["company_name"] : transaction_appoint_new_auditor[i]["name"]) +'" readonly/><div id="form_name"></div><div class="hidden"><input type="text" class="form-control" name="client_officer_id[]" id="client_officer_id" value="'+transaction_appoint_new_auditor[i]["id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="officer_id[]" id="officer_id" value="'+transaction_appoint_new_auditor[i]["officer_id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="officer_field_type[]" id="officer_field_type" value="'+transaction_appoint_new_auditor[i]["field_type"]+'"/></div></td>';
        $a += '<td><div class="input-group" style="width: 150px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="officer_date_of_appointment" name="date_of_appointment[]" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="'+transaction_appoint_new_auditor[i]["date_of_appointment"]+'" placeholder="DD/MM/YYYY"></div></td>';
        $a += '<td class="action"><div style="display: inline-block;"><button type="button" class="btn btn-primary" onclick="delete_appoint_new_director(this);">Delete</button></div></td>';
        $a += '</tr>';

        $("#body_appoint_new_auditor").prepend($a);

        // // ADDED BY JW
        $('#officer_date_of_appointment').datepicker({ 
            dateFormat:'dd MM yyyy',
        });

        !function (i) {
            $.ajax({
                type: "POST",
                url: "transaction/get_client_officers_position",
                data: {"position": transaction_appoint_new_auditor[i]["position"]},
                dataType: "json",
                success: function(data){
                    $("#form"+i+" #position"+i+"").find("option:eq(0)").html("Select Position");
                    if(data.tp == 1){
                        $.each(data['result'], function(key, val) {
                            var option = $('<option />');
                            option.attr('value', key).text(val);
                            if(data.selected_client_officers_position != null && key == data.selected_client_officers_position)
                            {
                                option.attr('selected', 'selected');
                            }
                            $("#position"+i+"").append(option);
                        });
                        
                    }
                    else{
                        alert(data.msg);
                    }

                    
                }               
            });
        } (i);
    }
}

function formatDateRONDCFunc(date) {
  var monthNames = [
    "January", "February", "March",
    "April", "May", "June", "July",
    "August", "September", "October",
    "November", "December"
  ];

  var day = date.getDate();
  if(day.toString().length==1)  
  {
    day="0"+day;
  }
    
  var monthIndex = date.getMonth();
  var year = date.getFullYear();

  return day + ' ' + monthNames[monthIndex] + ' ' + year;
}

function changeRONDCDateFormat(date)
{
    $array = date.split("/");
    $tmp = $array[0];
    $array[0] = $array[1];
    $array[1] = $tmp;
    $latest_date_format = $array[0]+"/"+$array[1]+"/"+$array[2];

    return $latest_date_format;
}

function registerNomineeDirectorInterface(client_nominee_director)
{
    if(client_nominee_director)
    {
        for(var i = 0; i < client_nominee_director.length; i++)
        {
            var selected_nominee_director = client_nominee_director[i];

            if(selected_nominee_director)
            {
                var full_address;

                if(selected_nominee_director["supporting_document"] != "")
                {
                    var file_result = JSON.parse(selected_nominee_director["supporting_document"]);
                }

                if(selected_nominee_director["nomi_officer_field_type"] == "individual")
                {
                    // if(selected_nominee_director["alternate_address"] == 1)
                    // {
                    //     full_address = address_format (selected_nominee_director["postal_code2"], selected_nominee_director["street_name2"], selected_nominee_director["building_name2"], selected_nominee_director["unit_no3"], selected_nominee_director["unit_no4"]);
                    // }
                    // else
                    // {
                        full_address = address_format (selected_nominee_director["postal_code1"], selected_nominee_director["street_name1"], selected_nominee_director["building_name1"], selected_nominee_director["nomi_officer_unit_no1"], selected_nominee_director["nomi_officer_unit_no1"], selected_nominee_director["foreign_address1"], selected_nominee_director["foreign_address2"], selected_nominee_director["foreign_address3"]);
                    //}
                }
                else if(selected_nominee_director["nomi_officer_field_type"] == "company")
                {
                    full_address = address_format (selected_nominee_director["company_postal_code"], selected_nominee_director["company_street_name"], selected_nominee_director["company_building_name"], selected_nominee_director["company_unit_no1"], selected_nominee_director["company_unit_no2"], selected_nominee_director["company_foreign_address1"], selected_nominee_director["company_foreign_address2"], selected_nominee_director["company_foreign_address3"]);
                }
                else if(selected_nominee_director["nomi_officer_field_type"] == "client")
                {
                   full_address = address_format (selected_nominee_director["postal_code"], selected_nominee_director["street_name"], selected_nominee_director["building_name"], selected_nominee_director["client_unit_no1"], selected_nominee_director["client_unit_no2"], selected_nominee_director["foreign_add_1"], selected_nominee_director["foreign_add_2"], selected_nominee_director["foreign_add_3"]);
                }

                if(selected_nominee_director["nd_date_entry"] != "")
                {
                    var nd_date_entry = formatDateRONDCFunc(new Date(changeRONDCDateFormat(selected_nominee_director["nd_date_entry"])));
                }
                else
                {
                    var nd_date_entry = "";
                }

                $b = '';
                $b += '<tr class="tr_nominee_director_table">';
                $b += '<td style="text-align: center">'+nd_date_entry+'</td>';
                $b += '<td>'+selected_nominee_director["nd_officer_name"]+'</td>';
                //$b += '<td><b>Full name:</b> <a data-toggle="modal" data-id="'+selected_nominee_director["client_nominee_director_id"]+'" data-nomiofficeid="'+selected_nominee_director["nomi_officer_id"]+'" data-nomifieldtype="'+selected_nominee_director["nomi_officer_field_type"]+'" class="open_edit_nominee_director pointer">'+ selected_nominee_director["name"] +'</a><br/><b>Alias:</b> '+selected_nominee_director["alias"]+'<br/><b>Residential address:</b> '+full_address+'<br/><b>Nationality:</b> '+selected_nominee_director["nomi_officer_nationality_name"]+'<br/><b>Identification card number:</b> '+ selected_nominee_director["identification_no"] +'<br/><b>Date of birth:</b> '+selected_nominee_director["date_of_birth"] +'<br/><b>Date on which the person becomes a nominator:</b> '+selected_nominee_director["date_become_nominator"]+'<br/><b>Date of cessation:</b>'+selected_nominee_director["date_of_cessation"]+'</td>';
                if(selected_nominee_director["nomi_officer_field_type"] == "individual")
                {
                    var date_of_birth = formatDateRONDCFunc(new Date(selected_nominee_director["date_of_birth"]));
                    var date_become_nominator = formatDateRONDCFunc(new Date(changeRONDCDateFormat(selected_nominee_director["date_become_nominator"])));
                    if(selected_nominee_director["date_of_cessation"] != "")
                    {
                        var date_of_cessation = formatDateRONDCFunc(new Date(changeRONDCDateFormat(selected_nominee_director["date_of_cessation"])));
                    }
                    else
                    {
                        var date_of_cessation = "";
                    }
                    $b += '<td><b>Full name:</b> <a data-toggle="modal" data-id="'+selected_nominee_director["client_nominee_director_id"]+'" data-transaction_id="'+selected_nominee_director["transaction_id"]+'" data-nomiofficeid="'+selected_nominee_director["nomi_officer_id"]+'" data-nomifieldtype="'+selected_nominee_director["nomi_officer_field_type"]+'" class="open_edit_nominee_director pointer">'+ selected_nominee_director["name"] +'</a><br/><b>Alias:</b> '+selected_nominee_director["alias"]+'<br/><b>Residential address:</b> '+full_address+'<br/><b>Nationality:</b> '+selected_nominee_director["nomi_officer_nationality_name"]+'<br/><b>Identification card number:</b> '+ selected_nominee_director["identification_no"] +'<br/><b>Date of birth:</b> '+date_of_birth+'<br/><b>Date on which the person becomes a nominator:</b> '+date_become_nominator+'<br/><b>Date of cessation:</b>'+date_of_cessation+'</td>';
                }
                else
                {
                    var date_of_incorporation = (selected_nominee_director["date_of_incorporation"] != null ? selected_nominee_director["date_of_incorporation"] : (selected_nominee_director["incorporation_date"] != null)?changeRONDCDateFormat(selected_nominee_director["incorporation_date"]) : "");
                    if(date_of_incorporation != "")
                    {
                        var old_date_of_incorporation = new Date(date_of_incorporation);
                        var date_of_incorporation = formatDateRONDCFunc(old_date_of_incorporation);
                    }
                    var date_become_nominator = formatDateRONDCFunc(new Date(changeRONDCDateFormat(selected_nominee_director["date_become_nominator"])));
                    if(selected_nominee_director["date_of_cessation"] != "")
                    {
                        var date_of_cessation = formatDateRONDCFunc(new Date(changeRONDCDateFormat(selected_nominee_director["date_of_cessation"])));
                    }
                    else
                    {
                        var date_of_cessation = "";
                    }
                    $b += '<td><b>Name:</b> <a data-toggle="modal" data-id="'+selected_nominee_director["client_nominee_director_id"]+'" data-transaction_id="'+selected_nominee_director["transaction_id"]+'" data-nomiofficeid="'+selected_nominee_director["nomi_officer_id"]+'" data-nomifieldtype="'+selected_nominee_director["nomi_officer_field_type"]+'" class="open_edit_nominee_director pointer">'+ (selected_nominee_director["officer_company_company_name"]!=null ? selected_nominee_director["officer_company_company_name"] : selected_nominee_director["client_company_name"]) + '</a><br/><b>Unique entity number issued by the Registrar:</b> '+ (selected_nominee_director["entity_issued_by_registrar"]!=null?selected_nominee_director["entity_issued_by_registrar"]:"")+'<br/><b>Address of registered office:</b> '+full_address+ '<br/><b>Legal form:</b> '+ (selected_nominee_director["legal_form_entity"] != null?selected_nominee_director["legal_form_entity"]:selected_nominee_director["client_company_type"] != null?selected_nominee_director["client_company_type"]:"") +'<br/><b>Jurisdiction where and statute under which the registrable corporate controller is formed or incorporated:</b> '+(selected_nominee_director["country_of_incorporation"]!=null?selected_nominee_director["country_of_incorporation"]:selected_nominee_director["client_country_of_incorporation"]!=null?selected_nominee_director["client_country_of_incorporation"]:"") + (selected_nominee_director["statutes_of"] != null ? ', ' + selected_nominee_director["statutes_of"] : selected_nominee_director["client_statutes_of"] != null ? ', ' + selected_nominee_director["client_statutes_of"] : '') + '<br/><b>Name of the corporate entity register of the jurisdiction where the registrable corporate controller is formed or incorporated:</b>' + (selected_nominee_director["coporate_entity_name"]!=null?selected_nominee_director["coporate_entity_name"]:selected_nominee_director["client_coporate_entity_name"]!=null?selected_nominee_director["client_coporate_entity_name"]:"") +'<br/><b>Identification number or registration number on the corporate entity register of the jurisdiction where the registrable corporate controller is formed or incorporated:</b> '+ (selected_nominee_director["register_no"] != null ? selected_nominee_director["register_no"] : selected_nominee_director["registration_no"]) +'<br/><b>Date of Incorporation:</b> '+ date_of_incorporation +'<br/><b>Date of becoming a controller:</b> '+date_become_nominator+'<br/><b>Date of cessation:</b>'+date_of_cessation+'</td>';
                }

                if(selected_nominee_director["supporting_document"] != "" && selected_nominee_director["supporting_document"] != "[]")
                {
                    $b += '<td><a href="'+url+'uploads/supporting_doc/'+file_result[0]+'" target="_blank">'+file_result[0]+'</a></td>';
                }
                else
                {
                    $b += '<td></td>';
                }
                $b += '<td><input type="hidden" id="client_nominee_director_id" value="'+selected_nominee_director["client_nominee_director_id"]+'" name="client_nominee_director_id"/><input type="hidden" id="delete_nomi_officer_id" value="'+selected_nominee_director["nomi_officer_id"]+'" name="delete_nomi_officer_id"/><input type="hidden" id="delete_nomi_officer_field_type" value="'+selected_nominee_director["nomi_officer_field_type"]+'" name="delete_nomi_officer_field_type"/><input type="hidden" id="client_nominee_director_name" value="'+selected_nominee_director["nd_officer_name"]+'" name="client_nominee_director_name"/><input type="hidden" id="company_code" value="'+selected_nominee_director["client_nominee_director_company_code"]+'" name="company_code"/><button type="button" class="btn btn-primary" onclick="delete_nominee_director(this);">Delete</button></td>';
                $b += '</tr>';

                $("#table_body_current_nominee_director").append($b);
            }
        }
    }
    $( document ).ready(function() {
        $('#current_nominee_director_table').DataTable();
    });
}

function registerLatestNomineeDirectorInterface(client_nominee_director)
{
    if(client_nominee_director)
    {
        for(var i = 0; i < client_nominee_director.length; i++)
        {
            var selected_nominee_director = client_nominee_director[i];

            if(selected_nominee_director)
            {
                var full_address;

                if(selected_nominee_director["supporting_document"] != "")
                {
                    var file_result = JSON.parse(selected_nominee_director["supporting_document"]);
                }

                if(selected_nominee_director["nomi_officer_field_type"] == "individual")
                {
                    // if(selected_nominee_director["alternate_address"] == 1)
                    // {
                    //     full_address = address_format (selected_nominee_director["postal_code2"], selected_nominee_director["street_name2"], selected_nominee_director["building_name2"], selected_nominee_director["unit_no3"], selected_nominee_director["unit_no4"]);
                    // }
                    // else
                    // {
                        full_address = address_format (selected_nominee_director["postal_code1"], selected_nominee_director["street_name1"], selected_nominee_director["building_name1"], selected_nominee_director["nomi_officer_unit_no1"], selected_nominee_director["nomi_officer_unit_no1"], selected_nominee_director["foreign_address1"], selected_nominee_director["foreign_address2"], selected_nominee_director["foreign_address3"]);
                    //}
                }
                else if(selected_nominee_director["nomi_officer_field_type"] == "company")
                {
                    full_address = address_format (selected_nominee_director["company_postal_code"], selected_nominee_director["company_street_name"], selected_nominee_director["company_building_name"], selected_nominee_director["company_unit_no1"], selected_nominee_director["company_unit_no2"], selected_nominee_director["company_foreign_address1"], selected_nominee_director["company_foreign_address2"], selected_nominee_director["company_foreign_address3"]);
                }
                else if(selected_nominee_director["nomi_officer_field_type"] == "client")
                {
                   full_address = address_format (selected_nominee_director["postal_code"], selected_nominee_director["street_name"], selected_nominee_director["building_name"], selected_nominee_director["client_unit_no1"], selected_nominee_director["client_unit_no2"], selected_nominee_director["foreign_add_1"], selected_nominee_director["foreign_add_2"], selected_nominee_director["foreign_add_3"]);
                }

                $b = '';
                $b += '<tr class="tr_nominee_director_table">';
                //$b += '<td style="text-align: center">'+nd_date_entry+'</td>';
                $b += '<td>'+selected_nominee_director["nd_officer_name"]+'</td>';
                //$b += '<td><b>Full name:</b> <a data-toggle="modal" data-id="'+selected_nominee_director["client_nominee_director_id"]+'" data-nomiofficeid="'+selected_nominee_director["nomi_officer_id"]+'" data-nomifieldtype="'+selected_nominee_director["nomi_officer_field_type"]+'" class="open_edit_nominee_director pointer">'+ selected_nominee_director["name"] +'</a><br/><b>Alias:</b> '+selected_nominee_director["alias"]+'<br/><b>Residential address:</b> '+full_address+'<br/><b>Nationality:</b> '+selected_nominee_director["nomi_officer_nationality_name"]+'<br/><b>Identification card number:</b> '+ selected_nominee_director["identification_no"] +'<br/><b>Date of birth:</b> '+selected_nominee_director["date_of_birth"] +'<br/><b>Date on which the person becomes a nominator:</b> '+selected_nominee_director["date_become_nominator"]+'<br/><b>Date of cessation:</b>'+selected_nominee_director["date_of_cessation"]+'</td>';
                if(selected_nominee_director["nomi_officer_field_type"] == "individual")
                {
                    var date_of_birth = formatDateRONDCFunc(new Date(selected_nominee_director["date_of_birth"]));
                    var date_become_nominator = formatDateRONDCFunc(new Date(changeRONDCDateFormat(selected_nominee_director["date_become_nominator"])));
                    if(selected_nominee_director["date_of_cessation"] != "")
                    {
                        var date_of_cessation = formatDateRONDCFunc(new Date(changeRONDCDateFormat(selected_nominee_director["date_of_cessation"])));
                    }
                    else
                    {
                        var date_of_cessation = "";
                    }
                    $b += '<td><b>Full name:</b> <a data-toggle="modal" data-id="'+selected_nominee_director["client_nominee_director_id"]+'" data-transaction_id="'+selected_nominee_director["transaction_id"]+'" data-nomiofficeid="'+selected_nominee_director["nomi_officer_id"]+'" data-nomifieldtype="'+selected_nominee_director["nomi_officer_field_type"]+'" class="open_edit_nominee_director pointer">'+ selected_nominee_director["name"] +'</a><br/><b>Alias:</b> '+selected_nominee_director["alias"]+'<br/><b>Residential address:</b> '+full_address+'<br/><b>Nationality:</b> '+selected_nominee_director["nomi_officer_nationality_name"]+'<br/><b>Identification card number:</b> '+ selected_nominee_director["identification_no"] +'<br/><b>Date of birth:</b> '+date_of_birth+'<br/><b>Date on which the person becomes a nominator:</b> '+date_become_nominator+'<br/><b>Date of cessation:</b>'+date_of_cessation+'</td>';
                }
                else
                {
                    var date_of_incorporation = (selected_nominee_director["date_of_incorporation"] != null ? selected_nominee_director["date_of_incorporation"] : (selected_nominee_director["incorporation_date"] != null)?changeRONDCDateFormat(selected_nominee_director["incorporation_date"]) : "");
                    if(date_of_incorporation != "")
                    {
                        var old_date_of_incorporation = new Date(date_of_incorporation);
                        var date_of_incorporation = formatDateRONDCFunc(old_date_of_incorporation);
                    }
                    var date_become_nominator = formatDateRONDCFunc(new Date(changeRONDCDateFormat(selected_nominee_director["date_become_nominator"])));
                    if(selected_nominee_director["date_of_cessation"] != "")
                    {
                        var date_of_cessation = formatDateRONDCFunc(new Date(changeRONDCDateFormat(selected_nominee_director["date_of_cessation"])));
                    }
                    else
                    {
                        var date_of_cessation = "";
                    }
                    $b += '<td><b>Name:</b> <a data-toggle="modal" data-id="'+selected_nominee_director["client_nominee_director_id"]+'" data-transaction_id="'+selected_nominee_director["transaction_id"]+'" data-nomiofficeid="'+selected_nominee_director["nomi_officer_id"]+'" data-nomifieldtype="'+selected_nominee_director["nomi_officer_field_type"]+'" class="open_edit_nominee_director pointer">'+ (selected_nominee_director["officer_company_company_name"]!=null ? selected_nominee_director["officer_company_company_name"] : selected_nominee_director["client_company_name"]) + '</a><br/><b>Unique entity number issued by the Registrar:</b>'+ (selected_nominee_director["entity_issued_by_registrar"]!=null?selected_nominee_director["entity_issued_by_registrar"]:"")+'<br/><b>Address of registered office:</b> '+full_address+ '<br/><b>Legal form:</b>'+ (selected_nominee_director["legal_form_entity"] != null?selected_nominee_director["legal_form_entity"]:selected_nominee_director["client_company_type"] != null?selected_nominee_director["client_company_type"]:"") +'<br/><b>Jurisdiction where and statute under which the registrable corporate controller is formed or incorporated:</b> '+(selected_nominee_director["country_of_incorporation"]!=null?selected_nominee_director["country_of_incorporation"]:selected_nominee_director["client_country_of_incorporation"]!=null?selected_nominee_director["client_country_of_incorporation"]:"") + (selected_nominee_director["statutes_of"] != null ? ', ' + selected_nominee_director["statutes_of"] : selected_nominee_director["client_statutes_of"] != null ? ', ' + selected_nominee_director["client_statutes_of"] : '') + '<br/><b>Name of the corporate entity register of the jurisdiction where the registrable corporate controller is formed or incorporated:</b>' + (selected_nominee_director["coporate_entity_name"]!=null?selected_nominee_director["coporate_entity_name"]:selected_nominee_director["client_coporate_entity_name"]!=null?selected_nominee_director["client_coporate_entity_name"]:"") +'<br/><b>Identification number or registration number on the corporate entity register of the jurisdiction where the registrable corporate controller is formed or incorporated:</b> '+ (selected_nominee_director["register_no"] != null ? selected_nominee_director["register_no"] : selected_nominee_director["registration_no"]) +'<br/><b>Date of Incorporation:</b> '+ date_of_incorporation +'<br/><b>Date of becoming a controller:</b> '+date_become_nominator+'<br/><b>Date of cessation:</b>'+date_of_cessation+'</td>';
                }
                if(selected_nominee_director["supporting_document"] != "" && selected_nominee_director["supporting_document"] != "[]")
                {
                    $b += '<td><a href="'+url+'uploads/supporting_doc/'+file_result[0]+'" target="_blank">'+file_result[0]+'</a></td>';
                }
                else
                {
                    $b += '<td></td>';
                }
                $b += '<td><input type="hidden" id="client_nominee_director_id" value="'+selected_nominee_director["client_nominee_director_id"]+'" name="client_nominee_director_id"/><input type="hidden" id="delete_nomi_officer_id" value="'+selected_nominee_director["nomi_officer_id"]+'" name="delete_nomi_officer_id"/><input type="hidden" id="delete_nomi_officer_field_type" value="'+selected_nominee_director["nomi_officer_field_type"]+'" name="delete_nomi_officer_field_type"/><input type="hidden" id="client_nominee_director_name" value="'+selected_nominee_director["nd_officer_name"]+'" name="client_nominee_director_name"/><input type="hidden" id="company_code" value="'+selected_nominee_director["client_nominee_director_company_code"]+'" name="company_code"/><button type="button" class="btn btn-primary" onclick="delete_nominee_director(this);">Delete</button></td>';
                $b += '</tr>';

                $("#table_body_latest_nominee_director").append($b);
            }
        }
    }
    $( document ).ready(function() {
        $('#latest_nominee_director_table').DataTable();
    });
}

function appointResignNomineeDirectorInterface(client_nominee_director)
{
    if(client_nominee_director)
    {
        $('#add_nominee_director').prop('checked', true);
        addNomineeDirector($('#add_nominee_director'));

        for(var i = 0; i < client_nominee_director.length; i++)
        {
            var selected_nominee_director = client_nominee_director[i];

            if(selected_nominee_director)
            {
                var full_address;

                if(selected_nominee_director["supporting_document"] != "")
                {
                    var file_result = JSON.parse(selected_nominee_director["supporting_document"]);
                }

                if(selected_nominee_director["nomi_officer_field_type"] == "individual")
                {
                    // if(selected_nominee_director["alternate_address"] == 1)
                    // {
                    //     full_address = address_format (selected_nominee_director["postal_code2"], selected_nominee_director["street_name2"], selected_nominee_director["building_name2"], selected_nominee_director["unit_no3"], selected_nominee_director["unit_no4"]);
                    // }
                    // else
                    // {
                        full_address = address_format (selected_nominee_director["postal_code1"], selected_nominee_director["street_name1"], selected_nominee_director["building_name1"], selected_nominee_director["nomi_officer_unit_no1"], selected_nominee_director["nomi_officer_unit_no1"], selected_nominee_director["foreign_address1"], selected_nominee_director["foreign_address2"], selected_nominee_director["foreign_address3"]);
                    //}
                }
                else if(selected_nominee_director["nomi_officer_field_type"] == "company")
                {
                    full_address = address_format (selected_nominee_director["company_postal_code"], selected_nominee_director["company_street_name"], selected_nominee_director["company_building_name"], selected_nominee_director["company_unit_no1"], selected_nominee_director["company_unit_no2"], selected_nominee_director["company_foreign_address1"], selected_nominee_director["company_foreign_address2"], selected_nominee_director["company_foreign_address3"]);
                }
                else if(selected_nominee_director["nomi_officer_field_type"] == "client")
                {
                   full_address = address_format (selected_nominee_director["postal_code"], selected_nominee_director["street_name"], selected_nominee_director["building_name"], selected_nominee_director["client_unit_no1"], selected_nominee_director["client_unit_no2"], selected_nominee_director["foreign_add_1"], selected_nominee_director["foreign_add_2"], selected_nominee_director["foreign_add_3"]);
                }

                $b = '';
                $b += '<tr class="tr_nominee_director_table">';
                //$b += '<td style="text-align: center">'+nd_date_entry+'</td>';
                $b += '<td>'+selected_nominee_director["nd_officer_name"]+'</td>';
                //$b += '<td><b>Full name:</b> <a data-toggle="modal" data-id="'+selected_nominee_director["client_nominee_director_id"]+'" data-nomiofficeid="'+selected_nominee_director["nomi_officer_id"]+'" data-nomifieldtype="'+selected_nominee_director["nomi_officer_field_type"]+'" class="open_edit_nominee_director pointer">'+ selected_nominee_director["name"] +'</a><br/><b>Alias:</b> '+selected_nominee_director["alias"]+'<br/><b>Residential address:</b> '+full_address+'<br/><b>Nationality:</b> '+selected_nominee_director["nomi_officer_nationality_name"]+'<br/><b>Identification card number:</b> '+ selected_nominee_director["identification_no"] +'<br/><b>Date of birth:</b> '+selected_nominee_director["date_of_birth"] +'<br/><b>Date on which the person becomes a nominator:</b> '+selected_nominee_director["date_become_nominator"]+'<br/><b>Date of cessation:</b>'+selected_nominee_director["date_of_cessation"]+'</td>';
                
                if(selected_nominee_director["nomi_officer_field_type"] == "individual")
                {
                    var date_of_birth = formatDateRONDCFunc(new Date(selected_nominee_director["date_of_birth"]));
                    var date_become_nominator = formatDateRONDCFunc(new Date(changeRONDCDateFormat(selected_nominee_director["date_become_nominator"])));
                    if(selected_nominee_director["date_of_cessation"] != "")
                    {
                        var date_of_cessation = formatDateRONDCFunc(new Date(changeRONDCDateFormat(selected_nominee_director["date_of_cessation"])));
                    }
                    else
                    {
                        var date_of_cessation = "";
                    }
                    // $b += '<td><b>Full name:</b> <a data-toggle="modal" data-id="'+selected_nominee_director["client_nominee_director_id"]+'" data-transaction_id="'+selected_nominee_director["transaction_id"]+'" data-nomiofficeid="'+selected_nominee_director["nomi_officer_id"]+'" data-nomifieldtype="'+selected_nominee_director["nomi_officer_field_type"]+'" class="open_edit_nominee_director pointer">'+ selected_nominee_director["name"] +'</a><br/><b>Alias:</b> '+selected_nominee_director["alias"]+'<br/><b>Residential address:</b> '+full_address+'<br/><b>Nationality:</b> '+selected_nominee_director["nomi_officer_nationality_name"]+'<br/><b>Identification card number:</b> '+ selected_nominee_director["identification_no"] +'<br/><b>Date of birth:</b> '+date_of_birth+'<br/><b>Date on which the person becomes a nominator:</b> '+date_become_nominator+'<br/><b>Date of cessation:</b>'+date_of_cessation+'</td>';
                    $b += '<td><b>Full name:</b> <a data-toggle="modal" data-id="'+selected_nominee_director["client_nominee_director_id"]+'" data-transaction_id="'+selected_nominee_director["transaction_id"]+'" data-nomiofficeid="'+selected_nominee_director["nomi_officer_id"]+'" data-nomifieldtype="'+selected_nominee_director["nomi_officer_field_type"]+'" class="open_edit_nominee_director pointer">'+ selected_nominee_director["name"] +'</a></td>';
                }
                else
                {
                    var date_of_incorporation = (selected_nominee_director["date_of_incorporation"] != null ? selected_nominee_director["date_of_incorporation"] : (selected_nominee_director["incorporation_date"] != null)?changeRONDCDateFormat(selected_nominee_director["incorporation_date"]) : "");
                    if(date_of_incorporation != "")
                    {
                        var old_date_of_incorporation = new Date(date_of_incorporation);
                        var date_of_incorporation = formatDateRONDCFunc(old_date_of_incorporation);
                    }
                    var date_become_nominator = formatDateRONDCFunc(new Date(changeRONDCDateFormat(selected_nominee_director["date_become_nominator"])));
                    if(selected_nominee_director["date_of_cessation"] != "")
                    {
                        var date_of_cessation = formatDateRONDCFunc(new Date(changeRONDCDateFormat(selected_nominee_director["date_of_cessation"])));
                    }
                    else
                    {
                        var date_of_cessation = "";
                    }
                    $b += '<td><b>Name:</b> <a data-toggle="modal" data-id="'+selected_nominee_director["client_nominee_director_id"]+'" data-transaction_id="'+selected_nominee_director["transaction_id"]+'" data-nomiofficeid="'+selected_nominee_director["nomi_officer_id"]+'" data-nomifieldtype="'+selected_nominee_director["nomi_officer_field_type"]+'" class="open_edit_nominee_director pointer">'+ (selected_nominee_director["officer_company_company_name"]!=null ? selected_nominee_director["officer_company_company_name"] : selected_nominee_director["client_company_name"]) + '</a><br/><b>Unique entity number issued by the Registrar:</b>'+ (selected_nominee_director["entity_issued_by_registrar"]!=null?selected_nominee_director["entity_issued_by_registrar"]:"")+'<br/><b>Address of registered office:</b> '+full_address+ '<br/><b>Legal form:</b>'+ (selected_nominee_director["legal_form_entity"] != null?selected_nominee_director["legal_form_entity"]:selected_nominee_director["client_company_type"] != null?selected_nominee_director["client_company_type"]:"") +'<br/><b>Jurisdiction where and statute under which the registrable corporate controller is formed or incorporated:</b> '+(selected_nominee_director["country_of_incorporation"]!=null?selected_nominee_director["country_of_incorporation"]:selected_nominee_director["client_country_of_incorporation"]!=null?selected_nominee_director["client_country_of_incorporation"]:"") + (selected_nominee_director["statutes_of"] != null ? ', ' + selected_nominee_director["statutes_of"] : selected_nominee_director["client_statutes_of"] != null ? ', ' + selected_nominee_director["client_statutes_of"] : '') + '<br/><b>Name of the corporate entity register of the jurisdiction where the registrable corporate controller is formed or incorporated:</b>' + (selected_nominee_director["coporate_entity_name"]!=null?selected_nominee_director["coporate_entity_name"]:selected_nominee_director["client_coporate_entity_name"]!=null?selected_nominee_director["client_coporate_entity_name"]:"") +'<br/><b>Identification number or registration number on the corporate entity register of the jurisdiction where the registrable corporate controller is formed or incorporated:</b> '+ (selected_nominee_director["register_no"] != null ? selected_nominee_director["register_no"] : selected_nominee_director["registration_no"]) +'<br/><b>Date of Incorporation:</b> '+ date_of_incorporation +'<br/><b>Date of becoming a controller:</b> '+date_become_nominator+'<br/><b>Date of cessation:</b>'+date_of_cessation+'</td>';
                }

                if(selected_nominee_director["supporting_document"] != "" && selected_nominee_director["supporting_document"] != "[]")
                {
                    $b += '<td><a href="'+url+'uploads/supporting_doc/'+file_result[0]+'" target="_blank">'+file_result[0]+'</a></td>';
                }
                else
                {
                    $b += '<td></td>';
                }
                $b += '<td><input type="hidden" id="client_nominee_director_id" value="'+selected_nominee_director["client_nominee_director_id"]+'" name="client_nominee_director_id"/><input type="hidden" id="delete_nomi_officer_id" value="'+selected_nominee_director["nomi_officer_id"]+'" name="delete_nomi_officer_id"/><input type="hidden" id="delete_nomi_officer_field_type" value="'+selected_nominee_director["nomi_officer_field_type"]+'" name="delete_nomi_officer_field_type"/><input type="hidden" id="client_nominee_director_name" value="'+selected_nominee_director["nd_officer_name"]+'" name="client_nominee_director_name"/><input type="hidden" id="company_code" value="'+selected_nominee_director["client_nominee_director_company_code"]+'" name="company_code"/><button type="button" class="btn btn-primary" onclick="delete_nominee_director(this);">Delete</button></td>';
                $b += '</tr>';

                $("#table_body_latest_nominee_director").append($b);
            }
        }
    }
    $( document ).ready(function() {
        $('#latest_nominee_director_table').DataTable();
    });
}

$("#is_confirm_registrable_controller").click(function() {
    $(".div_date_of_conf_received").show();
    $('.date_of_the_conf_received').val("");
    $("#radio_confirm_registrable_controller").val($(this).data('information'));
});

$("#not_confirm_registrable_controller").click(function() {
    $(".div_date_of_conf_received").hide();
    $('.date_of_the_conf_received').val("");
    $("#radio_confirm_registrable_controller").val($(this).data('information'));
});

function registerControllerInterface(client_controller)
{
    if(client_controller)
    {
        for(var i = 0; i < client_controller.length; i++)
        {
            var search_filter_categoryposition = $("#search_filter_categoryposition").val();

            if(client_controller[i]["client_controller_field_type"] == "individual" && search_filter_categoryposition == "individual")
            {
                var selected_controller = client_controller[i];
            }
            else if((client_controller[i]["client_controller_field_type"] == "client" || client_controller[i]["client_controller_field_type"] == "company") && search_filter_categoryposition == "corporate")
            {
                var selected_controller = client_controller[i];
            }
            else
            {
                var selected_controller = client_controller[i];
            }
            // else
            // {
            //     var selected_controller = false;
            // }

            if(selected_controller)
            {
                var full_address;

                if(selected_controller["supporting_document"] != "")
                {
                    var file_result = JSON.parse(selected_controller["supporting_document"]);
                }

                if(selected_controller["client_controller_field_type"] == "individual")
                {
                    // if(selected_controller["alternate_address"] == 1)
                    // {
                    //     full_address = address_format (selected_controller["postal_code2"], selected_controller["street_name2"], selected_controller["building_name2"], selected_controller["unit_no3"], selected_controller["unit_no4"]);
                    // }
                    // else
                    // {
                        full_address = address_format (selected_controller["postal_code1"], selected_controller["street_name1"], selected_controller["building_name1"], selected_controller["officer_unit_no1"], selected_controller["officer_unit_no2"], selected_controller["foreign_address1"], selected_controller["foreign_address2"], selected_controller["foreign_address3"]);
                    //}
                }
                else if(selected_controller["client_controller_field_type"] == "company")
                {
                    full_address = address_format (selected_controller["company_postal_code"], selected_controller["company_street_name"], selected_controller["company_building_name"], selected_controller["company_unit_no1"], selected_controller["company_unit_no2"], selected_controller["company_foreign_address1"], selected_controller["company_foreign_address2"], selected_controller["company_foreign_address3"]);
                }
                else if(selected_controller["client_controller_field_type"] == "client")
                {
                    full_address = address_format (selected_controller["postal_code"], selected_controller["street_name"], selected_controller["building_name"], selected_controller["client_unit_no1"], selected_controller["client_unit_no2"], selected_controller["foreign_add_1"], selected_controller["foreign_add_2"], selected_controller["foreign_add_3"]);
                }

                if(selected_controller["client_controller_field_type"] == "individual")
                {
                    $link_for_name = '<a data-toggle="modal" data-id="'+selected_controller["client_controller_id"]+'" data-transaction_id="'+selected_controller["transaction_id"]+'" data-officeid="'+selected_controller["officer_id"]+'" data-fieldtype="'+selected_controller["client_controller_field_type"]+'" class="open_edit_controller pointer">'+ selected_controller["name"] + '</a>';
                }
                else
                {
                    $link_for_name = '<a data-toggle="modal" data-id="'+selected_controller["client_controller_id"]+'" data-transaction_id="'+selected_controller["transaction_id"]+'" data-officeid="'+selected_controller["officer_id"]+'" data-fieldtype="'+selected_controller["client_controller_field_type"]+'" class="open_edit_controller pointer">'+ (selected_controller["officer_company_company_name"]!=null ? selected_controller["officer_company_company_name"] : selected_controller["client_company_name"]) + '</a>';
                }

                if(selected_controller["date_of_notice"] != "")
                {
                    var date_of_notice = formatDateRONDCFunc(new Date(changeRONDCDateFormat(selected_controller["date_of_notice"])));
                }
                else
                {
                    var date_of_notice = "";
                }

                if(selected_controller["confirmation_received_date"] != "")
                {
                    var confirmation_received_date = formatDateRONDCFunc(new Date(changeRONDCDateFormat(selected_controller["confirmation_received_date"])));
                }
                else
                {
                    var confirmation_received_date = "";
                }

                if(selected_controller["date_of_entry"] != "")
                {
                    var date_of_entry = formatDateRONDCFunc(new Date(changeRONDCDateFormat(selected_controller["date_of_entry"])));
                }
                else
                {
                    var date_of_entry = "";
                }

                $b = '';
                $b += '<tr class="tr_controller_table">';
                $b += '<td style="text-align: center">'+date_of_notice+'</td>';
                $b += '<td style="text-align: center">'+confirmation_received_date+'</td>'; 
                $b += '<td style="text-align: center">'+date_of_entry+'</td>';
                if(selected_controller["client_controller_field_type"] == "individual")
                {
                    var date_of_birth = formatDateRONDCFunc(new Date(selected_controller["date_of_birth"]));
                    var date_of_registration = formatDateRONDCFunc(new Date(changeRONDCDateFormat(selected_controller["date_of_registration"])));
                    if(selected_controller["date_of_cessation"] != "")
                    {
                        var date_of_cessation = formatDateRONDCFunc(new Date(changeRONDCDateFormat(selected_controller["date_of_cessation"])));
                    }
                    else
                    {
                        var date_of_cessation = "";
                    }

                    $b += '<td><b>Full name:</b> '+$link_for_name+'</a><br/><b>Alias:</b> '+selected_controller["alias"]+'<br/><b>Residential address:</b> '+full_address+'<br/><b>Nationality:</b> '+selected_controller["officer_nationality_name"]+'<br/><b>Identification card number:</b> '+ selected_controller["identification_no"] +'<br/><b>Date of birth:</b> '+ date_of_birth +'<br/><b>Date of becoming a controller:</b> '+date_of_registration+'<br/><b>Date of cessation:</b>'+date_of_cessation+'</td>';
                }
                else
                {
                    var date_of_incorporation = (selected_controller["date_of_incorporation"] != null ? selected_controller["date_of_incorporation"] : (selected_controller["incorporation_date"] != null)?changeRONDCDateFormat(selected_controller["incorporation_date"]) : "");
                    if(date_of_incorporation != "")
                    {
                        var old_date_of_incorporation = new Date(date_of_incorporation);
                        var date_of_incorporation = formatDateRONDCFunc(old_date_of_incorporation);
                    }
                    var date_of_registration = formatDateRONDCFunc(new Date(changeRONDCDateFormat(selected_controller["date_of_registration"])));
                    if(selected_controller["date_of_cessation"] != "")
                    {
                        var date_of_cessation = formatDateRONDCFunc(new Date(changeRONDCDateFormat(selected_controller["date_of_cessation"])));
                    }
                    else
                    {
                        var date_of_cessation = "";
                    }

                    $b += '<td><b>Name:</b> '+$link_for_name+'<br/><b>Unique entity number issued by the Registrar:</b>'+ (selected_controller["entity_issued_by_registrar"]!=null?selected_controller["entity_issued_by_registrar"]:"")+'<br/><b>Address of registered office:</b> '+full_address+ '<br/><b>Legal form:</b> '+ (selected_controller["legal_form_entity"]!=null?selected_controller["legal_form_entity"]:selected_controller["client_company_type"]!=null?selected_controller["client_company_type"]:"") +'<br/><b>Jurisdiction where and statute under which the registrable corporate controller is formed or incorporated:</b> '+(selected_controller["country_of_incorporation"]!=null?selected_controller["country_of_incorporation"]:selected_controller["client_country_of_incorporation"]!=null?selected_controller["client_country_of_incorporation"]:"") + (selected_controller["statutes_of"] != null ? ', ' + selected_controller["statutes_of"] : selected_controller["client_statutes_of"] != null ? ', ' + selected_controller["client_statutes_of"] : '') + '<br/><b>Name of the corporate entity register of the jurisdiction where the registrable corporate controller is formed or incorporated:</b> ' + (selected_controller["coporate_entity_name"]!=null?selected_controller["coporate_entity_name"]:selected_controller["client_coporate_entity_name"]!=null?selected_controller["client_coporate_entity_name"]:"") +'<br/><b>Identification number or registration number on the corporate entity register of the jurisdiction where the registrable corporate controller is formed or incorporated:</b> '+ (selected_controller["register_no"] != null ? selected_controller["register_no"] : selected_controller["registration_no"]) +'<br/><b>Date of Incorporation:</b> '+ date_of_incorporation +'<br/><b>Date of becoming a controller:</b> '+date_of_registration+'<br/><b>Date of cessation:</b>'+date_of_cessation+'</td>';
                }
                if(selected_controller["supporting_document"] != "" && selected_controller["supporting_document"] != "[]")
                {
                    $b += '<td><a href="'+url+'uploads/supporting_doc/'+file_result[0]+'" target="_blank">'+file_result[0]+'</a></td>';
                }
                else
                {
                    $b += '<td></td>';
                }
                $b += '<td><input type="hidden" id="client_controller_id" value="'+selected_controller["client_controller_id"]+'" name="client_controller_id"/><input type="hidden" id="client_controller_officer_id" value="'+selected_controller["officer_id"]+'" name="client_controller_officer_id"/><input type="hidden" id="client_controller_field_type" value="'+selected_controller["client_controller_field_type"]+'" name="client_controller_field_type"/><input type="hidden" id="client_controller_name" value="'+(selected_controller["name"]!=null ? selected_controller["name"] : selected_controller["officer_company_company_name"]!=null ? selected_controller["officer_company_company_name"] : selected_controller["client_company_name"])+'" name="client_controller_name"/><input type="hidden" id="company_code" value="'+selected_controller["client_controller_company_code"]+'" name="company_code"/><button type="button" class="btn btn-primary" onclick="delete_register_controller(this);">Delete</button></td>';
                $b += '</tr>';

                $("#table_body_current_controller").append($b);
            }
        }
    }
    $( document ).ready(function() {
        $('#current_controller_table').DataTable();
    });
}

function registerLatestControllerInterface(client_controller)
{
    if(client_controller)
    {
        for(var i = 0; i < client_controller.length; i++)
        {
            var search_filter_categoryposition = $("#search_filter_categoryposition").val();

            if(client_controller[i]["client_controller_field_type"] == "individual" && search_filter_categoryposition == "individual")
            {
                var selected_controller = client_controller[i];
            }
            else if((client_controller[i]["client_controller_field_type"] == "client" || client_controller[i]["client_controller_field_type"] == "company") && search_filter_categoryposition == "corporate")
            {
                var selected_controller = client_controller[i];
            }
            else
            {
                var selected_controller = client_controller[i];
            }
            // else
            // {
            //     var selected_controller = false;
            // }

            if(selected_controller)
            {
                var full_address;

                if(selected_controller["supporting_document"] != "")
                {
                    var file_result = JSON.parse(selected_controller["supporting_document"]);
                }

                if(selected_controller["client_controller_field_type"] == "individual")
                {
                    // if(selected_controller["alternate_address"] == 1)
                    // {
                    //     full_address = address_format (selected_controller["postal_code2"], selected_controller["street_name2"], selected_controller["building_name2"], selected_controller["unit_no3"], selected_controller["unit_no4"]);
                    // }
                    // else
                    // {
                        full_address = address_format (selected_controller["postal_code1"], selected_controller["street_name1"], selected_controller["building_name1"], selected_controller["officer_unit_no1"], selected_controller["officer_unit_no2"], selected_controller["foreign_address1"], selected_controller["foreign_address2"], selected_controller["foreign_address3"]);
                    //}
                }
                else if(selected_controller["client_controller_field_type"] == "company")
                {
                    full_address = address_format (selected_controller["company_postal_code"], selected_controller["company_street_name"], selected_controller["company_building_name"], selected_controller["company_unit_no1"], selected_controller["company_unit_no2"], selected_controller["company_foreign_address1"], selected_controller["company_foreign_address2"], selected_controller["company_foreign_address3"]);
                }
                else if(selected_controller["client_controller_field_type"] == "client")
                {
                    full_address = address_format (selected_controller["postal_code"], selected_controller["street_name"], selected_controller["building_name"], selected_controller["client_unit_no1"], selected_controller["client_unit_no2"], selected_controller["foreign_add_1"], selected_controller["foreign_add_2"], selected_controller["foreign_add_3"]);
                }

                if(selected_controller["date_of_notice"] != "")
                {
                    var date_of_notice = formatDateRONDCFunc(new Date(changeRONDCDateFormat(selected_controller["date_of_notice"])));
                }
                else
                {
                    var date_of_notice = "";
                }

                $b = '';
                $b += '<tr class="tr_latest_controller_table">';
                $b += '<td style="text-align: center">'+date_of_notice+'</td>';
                // $b += '<td style="text-align: center">'+selected_controller["confirmation_received_date"]+'</td>'; 
                // $b += '<td style="text-align: center">'+selected_controller["date_of_entry"]+'</td>';
                if(selected_controller["client_controller_field_type"] == "individual")
                {
                    var date_of_birth = formatDateRONDCFunc(new Date(selected_controller["date_of_birth"]));
                    var date_of_registration = formatDateRONDCFunc(new Date(changeRONDCDateFormat(selected_controller["date_of_registration"])));
                    if(selected_controller["date_of_cessation"] != "")
                    {
                        var date_of_cessation = formatDateRONDCFunc(new Date(changeRONDCDateFormat(selected_controller["date_of_cessation"])));
                    }
                    else
                    {
                        var date_of_cessation = "";
                    }

                    $b += '<td><b>Full name:</b> <a data-toggle="modal" data-id="'+selected_controller["client_controller_id"]+'" data-transaction_id="'+selected_controller["transaction_id"]+'" data-officeid="'+selected_controller["officer_id"]+'" data-fieldtype="'+selected_controller["client_controller_field_type"]+'" class="open_edit_controller pointer">'+ selected_controller["name"] +'</a><br/><b>Alias:</b> '+selected_controller["alias"]+'<br/><b>Residential address:</b> '+full_address+'<br/><b>Nationality:</b> '+selected_controller["officer_nationality_name"]+'<br/><b>Identification card number:</b> '+ selected_controller["identification_no"] +'<br/><b>Date of birth:</b> '+date_of_birth+'<br/><b>Date of becoming a controller:</b> '+date_of_registration+'<br/><b>Date of cessation:</b>'+date_of_cessation+'</td>';
                }
                else
                {
                    var date_of_incorporation = (selected_controller["date_of_incorporation"] != null ? selected_controller["date_of_incorporation"] : (selected_controller["incorporation_date"] != null)?changeRONDCDateFormat(selected_controller["incorporation_date"]) : "");
                    if(date_of_incorporation != "")
                    {
                        var old_date_of_incorporation = new Date(date_of_incorporation);
                        var date_of_incorporation = formatDateRONDCFunc(old_date_of_incorporation);
                    }
                    var date_of_registration = formatDateRONDCFunc(new Date(changeRONDCDateFormat(selected_controller["date_of_registration"])));
                    if(selected_controller["date_of_cessation"] != "")
                    {
                        var date_of_cessation = formatDateRONDCFunc(new Date(changeRONDCDateFormat(selected_controller["date_of_cessation"])));
                    }
                    else
                    {
                        var date_of_cessation = "";
                    }
                    $b += '<td><b>Name:</b> <a data-toggle="modal" data-id="'+selected_controller["client_controller_id"]+'" data-transaction_id="'+selected_controller["transaction_id"]+'" data-officeid="'+selected_controller["officer_id"]+'" data-fieldtype="'+selected_controller["client_controller_field_type"]+'" class="open_edit_controller pointer">'+ (selected_controller["officer_company_company_name"]!=null ? selected_controller["officer_company_company_name"] : selected_controller["client_company_name"]) + '</a><br/><b>Unique entity number issued by the Registrar:</b>'+ (selected_controller["entity_issued_by_registrar"]!=null?selected_controller["entity_issued_by_registrar"]:"")+'<br/><b>Address of registered office:</b> '+full_address+ '<br/><b>Legal form:</b> '+ (selected_controller["legal_form_entity"]!=null?selected_controller["legal_form_entity"]:selected_controller["client_company_type"]!=null?selected_controller["client_company_type"]:"") +'<br/><b>Jurisdiction where and statute under which the registrable corporate controller is formed or incorporated:</b> '+(selected_controller["country_of_incorporation"]!=null?selected_controller["country_of_incorporation"]:selected_controller["client_country_of_incorporation"]!=null?selected_controller["client_country_of_incorporation"]:"") + (selected_controller["statutes_of"] != null ? ', ' + selected_controller["statutes_of"] : selected_controller["client_statutes_of"] != null ? ', ' + selected_controller["client_statutes_of"] : '') + '<br/><b>Name of the corporate entity register of the jurisdiction where the registrable corporate controller is formed or incorporated:</b> ' + (selected_controller["coporate_entity_name"]!=null?selected_controller["coporate_entity_name"]:selected_controller["client_coporate_entity_name"]!=null?selected_controller["client_coporate_entity_name"]:"") +'<br/><b>Identification number or registration number on the corporate entity register of the jurisdiction where the registrable corporate controller is formed or incorporated:</b> '+ (selected_controller["register_no"] != null ? selected_controller["register_no"] : selected_controller["registration_no"]) +'<br/><b>Date of Incorporation:</b> '+ date_of_incorporation +'<br/><b>Date of becoming a controller:</b> '+date_of_registration+'<br/><b>Date of cessation:</b>'+date_of_cessation+'</td>';
                }
                if(selected_controller["supporting_document"] != "" && selected_controller["supporting_document"] != "[]")
                {
                    $b += '<td><a href="'+url+'uploads/supporting_doc/'+file_result[0]+'" target="_blank">'+file_result[0]+'</a></td>';
                }
                else
                {
                    $b += '<td></td>';
                }
                $b += '<td><input type="hidden" id="client_controller_id" value="'+selected_controller["client_controller_id"]+'" name="client_controller_id"/><input type="hidden" id="client_controller_officer_id" value="'+selected_controller["officer_id"]+'" name="client_controller_officer_id"/><input type="hidden" id="client_controller_field_type" value="'+selected_controller["client_controller_field_type"]+'" name="client_controller_field_type"/><input type="hidden" id="client_controller_name" value="'+(selected_controller["name"]!=null ? selected_controller["name"] : selected_controller["officer_company_company_name"]!=null ? selected_controller["officer_company_company_name"] : selected_controller["client_company_name"])+'" name="client_controller_name"/><input type="hidden" id="company_code" value="'+selected_controller["client_controller_company_code"]+'" name="company_code"/><button type="button" class="btn btn-primary" onclick="delete_register_controller(this);">Delete</button></td>';
                $b += '</tr>';

                $("#table_body_latest_controller").append($b);
            }
        }
    }

    $( document ).ready(function() {
        $('#latest_controller_table').DataTable();
    });
}

function resignDirectorInterface(transaction_resign_new_director,is_create = null)
{
    var appoint_director = [];
    for(var i = 0; i < transaction_resign_new_director.length; i++)
    {
        if(transaction_resign_new_director[i]["date_of_appointment"] != ""  && (transaction_resign_new_director[i]["appoint_resign_flag"] ==  "resign" || is_create) )
        {
            $a="";
            $a += '<tr class="row_resign_director">';
            $a += '<td><select class="form-control position" style="text-align:right;" name="resign_position[]" id="resign_position'+i+'" disabled="disabled"><option value="0" >Select Position</option></select><input type="hidden" class="form-control hidden_position" name="hidden_position[]" id="hidden_position" value="'+transaction_resign_new_director[i]["position"]+'"/><div id="alternate_of" hidden><p style="font-weight:bold;">Alternate of: </p><select class="form-control select_alternate_of" id="select_alternate_of'+i+'" style="text-align:right;width: 150px;" name="resign_alternate_of[]"><option value="" >Select Director</option></select><div id="form_alternate_of"></div></div></td>';
            $a += '<td><input type="text" style="text-transform:uppercase;" name="resign_identification_register_no[]" class="form-control" value="'+ (transaction_resign_new_director[i]["identification_no"]!=null ? transaction_resign_new_director[i]["identification_no"] : transaction_resign_new_director[i]["register_no"]) +'" id="gid_add_director_officer" maxlength="15" disabled="disabled"/><input type="hidden" class="form-control" name="hidden_resign_identification_register_no[]" id="hidden_resign_identification_register_no" value="'+ (transaction_resign_new_director[i]["identification_no"]!=null ? transaction_resign_new_director[i]["identification_no"] : transaction_resign_new_director[i]["register_no"]) +'"/><div style="height:16px;" ><a class="" href="'+ url + 'personprofile/add" style="cursor:pointer;" id="add_office_person_link" target="_blank" hidden onclick="add_officers_person(this)"><div style="cursor:pointer;" id="click_add_person">Click here to <span style="font-weight:bold">Add Person</span></div></a></div></td>';
            $a += '<td><input type="text" style="text-transform:uppercase;" name="resign_name[]" id="name" class="form-control" value="'+ (transaction_resign_new_director[i]["company_name"]!=null ? transaction_resign_new_director[i]["company_name"] : transaction_resign_new_director[i]["name"]) +'" readonly/><div id="form_name"></div><div class="hidden"><input type="text" class="form-control" name="resign_client_officer_id[]" id="client_officer_id" value="'+transaction_resign_new_director[i]["id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="resign_officer_id[]" id="officer_id" value="'+transaction_resign_new_director[i]["officer_id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="resign_officer_field_type[]" id="officer_field_type" value="'+transaction_resign_new_director[i]["field_type"]+'"/></div></td>';
            $a += '<td><div class="input-group" style="width: 150px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="officer_date_of_appointment" name="resign_date_of_appointment[]" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="'+transaction_resign_new_director[i]["date_of_appointment"]+'" placeholder="DD/MM/YYYY" disabled="disabled"></div><input type="hidden" class="form-control" name="hidden_date_of_appointment[]" id="hidden_date_of_appointment" value="'+transaction_resign_new_director[i]["date_of_appointment"]+'"/></td>';
            $a += '<td><div class="input-group" style="width: 150px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker officer_date_of_cessation" id="officer_date_of_cessation'+i+'" name="resign_date_of_cessation[]" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="'+transaction_resign_new_director[i]["date_of_cessation"]+'" placeholder="DD/MM/YYYY" disabled="disabled"></div><input type="hidden" class="form-control" name="hidden_date_of_cessation[]" id="hidden_date_of_cessation" value="'+transaction_resign_new_director[i]["date_of_cessation"]+'"/></td>';
            
            $a += '<td><select onclick="change_director_reason_selection(this);" class="form-control resign_director_reason_selection" name="resign_director_reason_selection[]" id="resign_director_reason_selection'+i+'" style="width:100%;" disabled="disabled"><option value="DECEASED">DECEASED</option><option value="DISQUALIFIED">DISQUALIFIED</option><option value="RESIGNED">RESIGNED</option><option value="OTHERS">OTHERS</option></select><input type="hidden" class="form-control" name="hidden_resign_director_reason_selected[]" id="hidden_resign_director_reason_selected" value="'+transaction_resign_new_director[i]["reason_selected"]+'"/><br>';
            $a += '<textarea class="form-control resign_director_reason" name="resign_director_reason[]" id="resign_director_reason" style="width:100%;height:70px;text-transform:uppercase;display: none;">'+(transaction_resign_new_director[i]["reason"]!=undefined ? transaction_resign_new_director[i]["reason"] : "")+'</textarea><input type="hidden" class="form-control" name="hidden_resign_director_reason[]" id="hidden_resign_director_reason" value="'+transaction_resign_new_director[i]["reason"]+'"/></td>';

            $a += '<td class="action"><div style="display: inline-block;"><button type="button" id="withdraw_director'+i+'" class="btn btn-primary withdraw_director" onclick="withdraw_director(this);">Withdraw</button><input type="hidden" name="is_director_withdraw[]" id="is_director_withdraw" class="is_director_withdraw'+i+'" value="'+(transaction_resign_new_director[i]["is_resign"]!=undefined ? transaction_resign_new_director[i]["is_resign"] : "0")+'"></div></td>';
            // $a += '<td class="action"><div style="display: inline-block;"><button type="button" id="withdraw_director'+i+'" class="btn btn-primary withdraw_director" onclick="withdraw_director(this);">Withdraw</button></div></td>';
            $a += '</tr>';

            $("#body_resign_director").append($a);

            $('#officer_date_of_cessation'+i).datepicker({ 
                dateFormat:'dd/mm/yyyy',
            }).datepicker('setStartDate', transaction_resign_new_director[i]["date_of_appointment"]).on('changeDate', function(ev) {
                $(this).parent().parent().find("#hidden_date_of_cessation").val($(this).val());
            }).on('clearDate', function(ev) {
                $(this).parent().parent().find("#hidden_date_of_cessation").val($(this).val());
            });

            if(transaction_resign_new_director[i]['reason_selected'] != '')
            {
                $('#officer_date_of_cessation'+i).parent().parent().parent().find("#resign_director_reason_selection"+i).val(transaction_resign_new_director[i]['reason_selected']);
            }

            if(transaction_resign_new_director[i]['reason_selected'] == 'OTHERS' || (transaction_resign_new_director[i]['reason_selected'] == "" && transaction_resign_new_director[i]["reason"]!="") )
            {
                $('#officer_date_of_cessation'+i).parent().parent().parent().find("#resign_director_reason_selection"+i).val('OTHERS');
                $('#officer_date_of_cessation'+i).parent().parent().parent().find("#resign_director_reason").show();
                //$('#officer_date_of_cessation'+i).parent().parent().parent().find("#resign_director_reason").prop("disabled", true);
            }

            // if($('#officer_date_of_cessation'+i).val() != "" || $('#resign_director_reason_selection'+i+' :selected').val() != undefined)
            // {
            //     $('#officer_date_of_cessation'+i).prop("disabled", false);
            //     // $('#officer_date_of_cessation'+i).parent().parent().parent().find("#resign_director_reason").prop("disabled", false);
            //     $('#officer_date_of_cessation'+i).parent().parent().parent().find("#resign_director_reason_selection"+i).prop("disabled", false);

            //     $('#officer_date_of_cessation'+i).parent().parent().parent().find("#withdraw_director"+i).html('Cancel');
            //     $('#officer_date_of_cessation'+i).parent().parent().parent().find("#withdraw_director"+i).toggleClass('cancel_withdraw_director');
            //     $('#officer_date_of_cessation'+i).parent().parent().parent().find("#withdraw_director"+i).removeClass('withdraw_director');
            // }


            if($('#officer_date_of_cessation'+i).val() != "" || $('.is_director_withdraw'+i).val() != "0" || $('#resign_director_reason_selection'+i+' :selected').val() != undefined)
            {
                $('#officer_date_of_cessation'+i).parent().parent().parent().find(".officer_date_of_cessation").prop("disabled", false);
                $('#officer_date_of_cessation'+i).parent().parent().parent().find("#resign_director_reason").prop("disabled", false);
                $('#officer_date_of_cessation'+i).parent().parent().parent().find("#resign_director_reason_selection"+i).prop("disabled", false);

                $('#officer_date_of_cessation'+i).parent().parent().parent().find("#withdraw_director"+i).html('Cancel');
                $('#officer_date_of_cessation'+i).parent().parent().parent().find("#withdraw_director"+i).toggleClass('cancel_withdraw_director');
                $('#officer_date_of_cessation'+i).parent().parent().parent().find("#withdraw_director"+i).removeClass('withdraw_director');
            }

            !function (i) {
                $.ajax({
                    type: "POST",
                    url: "transaction/get_client_officers_position",
                    data: {"position": transaction_resign_new_director[i]["position"]},
                    dataType: "json",
                    success: function(data){
                        $("#form"+i+" #resign_position"+i+"").find("option:eq(0)").html("Select Position");
                        if(data.tp == 1){
                            $.each(data['result'], function(key, val) {
                                var option = $('<option />');
                                option.attr('value', key).text(val);
                                if(data.selected_client_officers_position != null && key == data.selected_client_officers_position)
                                {
                                    option.attr('selected', 'selected');
                                }
                                $("#resign_position"+i+"").append(option);
                            });
                            
                        }
                        else{
                            alert(data.msg);
                        }

                        
                    }               
                });
            } (i);
        }
        else
        {
            appoint_director.push(transaction_resign_new_director[i]);
            
        }
        //check_incorp_date();
    }
    if(appoint_director.length > 0)
    {
        appointNewDirectorInterface(appoint_director);
    }
    $(".resign_director_reason").on("change keyup paste", function() {
        $(this).parent().find("#hidden_resign_director_reason").val($(this).val());
    });

    // if($("#body_resign_director .withdraw_director").length >= 1)
    // {
    //     $('#appoint_new_director').hide();
    // }
    // else
    // {
    //     $('#appoint_new_director').show();
    // }

    if($("#body_resign_director .withdraw_director").length >= 1 || $("#body_resign_director .cancel_withdraw_director").length >= 1)
    {
        //$('#resign_director_table').show();
        $('#resign_director_table .resign_director').show();
        $('#no_director_resign').hide();
    }
    else
    {
        //$('#resign_director_table').hide();
        $('#resign_director_table .resign_director').hide();
        $('#no_director_resign').show();
    }
}

function resignSecretarialInterface(transaction_resign_new_secretarial,is_create = null)
{
    var appoint_secretarial = [];
    for(var i = 0; i < transaction_resign_new_secretarial.length; i++)
    {
        // appoint_auditor.push(transaction_resign_new_auditor[i]);
        if(transaction_resign_new_secretarial[i]["date_of_appointment"] != "" && (transaction_resign_new_secretarial[i]["appoint_resign_flag"] ==  "resign" || is_create) )
        {
            $a="";
            $a += '<tr class="row_resign_secretarial">';
            $a += '<td><select class="form-control position" style="text-align:right;" name="resign_position[]" id="resign_position'+i+'" disabled="disabled"><option value="0" >Select Position</option></select><input type="hidden" class="form-control hidden_position" name="hidden_position[]" id="hidden_position" value="'+transaction_resign_new_secretarial[i]["position"]+'"/><div id="alternate_of" hidden><p style="font-weight:bold;">Alternate of: </p><select class="form-control select_alternate_of" id="select_alternate_of'+i+'" style="text-align:right;width: 150px;" name="resign_alternate_of[]"><option value="" >Select Director</option></select><div id="form_alternate_of"></div></div></td>';
            $a += '<td><input type="text" style="text-transform:uppercase;" name="resign_identification_register_no[]" class="form-control" value="'+ (transaction_resign_new_secretarial[i]["identification_no"]!=null ? transaction_resign_new_secretarial[i]["identification_no"] : transaction_resign_new_secretarial[i]["register_no"]) +'" id="gid_add_officer" maxlength="15" disabled="disabled"/><input type="hidden" class="form-control" name="hidden_resign_identification_register_no[]" id="hidden_resign_identification_register_no" value="'+ (transaction_resign_new_secretarial[i]["identification_no"]!=null ? transaction_resign_new_secretarial[i]["identification_no"] : transaction_resign_new_secretarial[i]["register_no"]) +'"/><div style="height:16px;" ><a class="" href="'+ url + 'personprofile/add" style="cursor:pointer;" id="add_office_person_link" target="_blank" hidden onclick="add_officers_person(this)"><div style="cursor:pointer;" id="click_add_person">Click here to <span style="font-weight:bold">Add Person</span></div></a></div></td>';
            $a += '<td><input type="text" style="text-transform:uppercase;" name="resign_name[]" id="name" class="form-control" value="'+ (transaction_resign_new_secretarial[i]["company_name"]!=null ? transaction_resign_new_secretarial[i]["company_name"] : transaction_resign_new_secretarial[i]["name"]) +'" readonly/><div id="form_name"></div><div class="hidden"><input type="text" class="form-control" name="resign_client_officer_id[]" id="client_officer_id" value="'+transaction_resign_new_secretarial[i]["id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="resign_officer_id[]" id="officer_id" value="'+transaction_resign_new_secretarial[i]["officer_id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="resign_officer_field_type[]" id="officer_field_type" value="'+transaction_resign_new_secretarial[i]["field_type"]+'"/></div></td>';
            $a += '<td><div class="input-group" style="width: 150px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="officer_date_of_appointment" name="resign_date_of_appointment[]" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="'+transaction_resign_new_secretarial[i]["date_of_appointment"]+'" placeholder="DD/MM/YYYY" disabled="disabled"></div><input type="hidden" class="form-control" name="hidden_date_of_appointment[]" id="hidden_date_of_appointment" value="'+transaction_resign_new_secretarial[i]["date_of_appointment"]+'"/></td>';
            $a += '<td><div class="input-group" style="width: 150px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker officer_date_of_cessation" id="officer_date_of_cessation'+i+'" name="resign_date_of_cessation[]" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="'+transaction_resign_new_secretarial[i]["date_of_cessation"]+'" placeholder="DD/MM/YYYY" disabled="disabled"></div><input type="hidden" class="form-control" name="hidden_date_of_cessation[]" id="hidden_date_of_cessation" value="'+transaction_resign_new_secretarial[i]["date_of_cessation"]+'"/></td>';
            
            $a += '<td><select onclick="change_reason_selection(this);" class="form-control resign_secretarial_reason_selection" name="resign_secretarial_reason_selection[]" id="resign_secretarial_reason_selection'+i+'" style="width:100%;" disabled="disabled"><option value="DECEASED">DECEASED</option><option value="DISQUALIFIED">DISQUALIFIED</option><option value="RESIGNED">RESIGNED</option><option value="OTHERS">OTHERS</option></select><input type="hidden" class="form-control" name="hidden_resign_secretarial_reason_selection[]" id="hidden_resign_secretarial_reason_selection" value="'+transaction_resign_new_secretarial[i]["reason_selected"]+'"/><br>';
            $a += '<textarea class="form-control resign_secretarial_reason" name="resign_secretarial_reason[]" id="resign_secretarial_reason" style="width:100%;height:70px;text-transform:uppercase;display: none;">'+(transaction_resign_new_secretarial[i]["reason"]!=undefined ? transaction_resign_new_secretarial[i]["reason"] : "")+'</textarea><input type="hidden" class="form-control" name="hidden_resign_secretarial_reason[]" id="hidden_resign_secretarial_reason" value="'+transaction_resign_new_secretarial[i]["reason"]+'"/></td>';
    
            $a += '<td class="action"><div style="display: inline-block;"><button type="button" id="withdraw_secretarial'+i+'" class="btn btn-primary withdraw_secretarial" onclick="withdraw_secretarial(this);">Withdraw</button></div></td>';
            $a += '</tr>';

            $("#body_resign_secretarial").append($a);

            $('#officer_date_of_cessation'+i).datepicker({ 
                dateFormat:'dd/mm/yyyy',
            }).datepicker('setStartDate', transaction_resign_new_secretarial[i]["date_of_appointment"]).on('changeDate', function(ev) {
                $(this).parent().parent().find("#hidden_date_of_cessation").val($(this).val());
            });

            if(transaction_resign_new_secretarial[i]['reason_selected'] != '')
            {
                $('#officer_date_of_cessation'+i).parent().parent().parent().find("#resign_secretarial_reason_selection"+i).val(transaction_resign_new_secretarial[i]['reason_selected']);
            }

            if(transaction_resign_new_secretarial[i]['reason_selected'] == 'OTHERS' || (transaction_resign_new_secretarial[i]['reason_selected'] == "" && transaction_resign_new_secretarial[i]["reason"]!="") )
            {
                $('#officer_date_of_cessation'+i).parent().parent().parent().find("#resign_secretarial_reason_selection"+i).val('OTHERS');
                $('#officer_date_of_cessation'+i).parent().parent().parent().find("#resign_secretarial_reason").show();
                //$('#officer_date_of_cessation'+i).parent().parent().parent().find("#resign_secretarial_reason").prop("disabled", true);
            }

            if($('#officer_date_of_cessation'+i).val() != "" || $('#resign_secretarial_reason_selection'+i+' :selected').val() != undefined)
            {
                $('#officer_date_of_cessation'+i).prop("disabled", false);
                // $('#officer_date_of_cessation'+i).parent().parent().parent().find("#resign_secretarial_reason").prop("disabled", false);
                $('#officer_date_of_cessation'+i).parent().parent().parent().find("#resign_secretarial_reason_selection"+i).prop("disabled", false);

                $('#officer_date_of_cessation'+i).parent().parent().parent().find("#withdraw_secretarial"+i).html('Cancel');
                $('#officer_date_of_cessation'+i).parent().parent().parent().find("#withdraw_secretarial"+i).toggleClass('cancel_withdraw_secretarial');
                $('#officer_date_of_cessation'+i).parent().parent().parent().find("#withdraw_secretarial"+i).removeClass('withdraw_secretarial');
            }

            !function (i) {
                $.ajax({
                    type: "POST",
                    url: "transaction/get_client_officers_position",
                    data: {"position": transaction_resign_new_secretarial[i]["position"]},
                    dataType: "json",
                    success: function(data){
                        $("#form"+i+" #resign_position"+i+"").find("option:eq(0)").html("Select Position");
                        if(data.tp == 1){
                            $.each(data['result'], function(key, val) {
                                var option = $('<option />');
                                option.attr('value', key).text(val);
                                if(data.selected_client_officers_position != null && key == data.selected_client_officers_position)
                                {
                                    option.attr('selected', 'selected');
                                }
                                $("#resign_position"+i+"").append(option);
                            });
                            
                        }
                        else{
                            alert(data.msg);
                        }

                        
                    }               
                });
            } (i);
        }
        else
        {
            appoint_secretarial.push(transaction_resign_new_secretarial[i]);
            
        }
        //check_incorp_date();
    }
    if(appoint_secretarial.length > 0)
    {
        appointNewSecretarialInterface(appoint_secretarial);
    }
    
    if($("#body_resign_secretarial .withdraw_secretarial").length >= 1 || $("#body_resign_secretarial .cancel_withdraw_secretarial").length >= 1)
    {
        $('#resign_secretarial_table').show();
        $('#no_secretarial_resign').hide();
    }
    else
    {
        $('#resign_secretarial_table').hide();
        $('#no_secretarial_resign').show();
    }

    $(".resign_secretarial_reason").on("change keyup paste", function() {
        $(this).parent().find("#hidden_resign_secretarial_reason").val($(this).val());
    });
}

function appointNewSecretarialInterface(transaction_appoint_new_secretarial)
{   
    for(var i = 0; i < transaction_appoint_new_secretarial.length; i++)
    {
        $a="";
        $a += '<tr class="row_appoint_new_secretarial">';
        $a += '<td><select class="form-control position" style="text-align:right;" name="position[]" id="position'+i+'" disabled="disabled"><option value="0" >Select Position</option></select><div id="form_position"></div></td>';
        $a += '<td><input type="text" style="text-transform:uppercase;" name="identification_register_no[]" class="form-control" value="'+ (transaction_appoint_new_secretarial[i]["identification_no"]!=null ? transaction_appoint_new_secretarial[i]["identification_no"] : transaction_appoint_new_secretarial[i]["register_no"]) +'" id="gid_add_director_officer" maxlength="15"/><div id="form_identification_register_no"></div><div style="height:16px;" ><a class="" href="'+ url + 'personprofile/add" style="cursor:pointer;" id="add_office_person_link" target="_blank" hidden onclick="add_officers_person(this)"><div style="cursor:pointer;" id="click_add_person">Click here to <span style="font-weight:bold">Add Person</span></div></a></div></td>';
        $a += '<td><input type="text" style="text-transform:uppercase;" name="name[]" id="name" class="form-control" value="'+ (transaction_appoint_new_secretarial[i]["company_name"]!=null ? transaction_appoint_new_secretarial[i]["company_name"] : transaction_appoint_new_secretarial[i]["name"]) +'" readonly/><div id="form_name"></div><div class="hidden"><input type="text" class="form-control" name="client_officer_id[]" id="client_officer_id" value="'+transaction_appoint_new_secretarial[i]["id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="officer_id[]" id="officer_id" value="'+transaction_appoint_new_secretarial[i]["officer_id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="officer_field_type[]" id="officer_field_type" value="'+transaction_appoint_new_secretarial[i]["field_type"]+'"/></div></td>';
        $a += '<td><div class="input-group" style="width: 150px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="officer_date_of_appointment" name="date_of_appointment[]" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="'+transaction_appoint_new_secretarial[i]["date_of_appointment"]+'" placeholder="DD/MM/YYYY"></div></td>';
        $a += '<td class="action"><div style="display: inline-block;"><button type="button" class="btn btn-primary" onclick="delete_appoint_new_director(this);">Delete</button></div></td>';
        $a += '</tr>';

        $("#body_appoint_new_secretarial").prepend($a);

        $('#officer_date_of_appointment').datepicker({ 
            dateFormat:'dd MM yyyy',
        });

        !function (i) {
            $.ajax({
                type: "POST",
                url: "transaction/get_client_officers_position",
                data: {"position": transaction_appoint_new_secretarial[i]["position"]},
                dataType: "json",
                success: function(data){
                    $("#form"+i+" #position"+i+"").find("option:eq(0)").html("Select Position");
                    if(data.tp == 1){
                        $.each(data['result'], function(key, val) {
                            var option = $('<option />');
                            option.attr('value', key).text(val);
                            if(data.selected_client_officers_position != null && key == data.selected_client_officers_position)
                            {
                                option.attr('selected', 'selected');
                            }
                            $("#position"+i+"").append(option);
                        });
                        
                    }
                    else{
                        alert(data.msg);
                    }

                    
                }               
            });
        } (i);

        //check_incorp_date();
    }
}

function appointNewDirectorInterface(transaction_appoint_new_director)
{
    for(var i = 0; i < transaction_appoint_new_director.length; i++)
    {
        $a="";
        $a += '<tr class="row_appoint_new_director">';
        $a += '<td><select class="form-control position" style="text-align:right;" name="position[]" id="position'+i+'" disabled="disabled"><option value="0" >Select Position</option></select><div id="form_position"></div><div id="alternate_of" hidden><p style="font-weight:bold;">Alternate of: </p><select class="form-control select_alternate_of" id="select_alternate_of'+i+'" style="text-align:right;width: 150px;" name="alternate_of[]"><option value="" >Select Director</option></select><div id="form_alternate_of"></div></div></td>';
        $a += '<td><input type="text" style="text-transform:uppercase;" name="identification_register_no[]" class="form-control" value="'+ (transaction_appoint_new_director[i]["identification_no"]!=null ? transaction_appoint_new_director[i]["identification_no"] : transaction_appoint_new_director[i]["register_no"]) +'" id="gid_add_director_officer" maxlength="15"/><div id="form_identification_register_no"></div><div style="height:16px;" ><a class="" href="'+ url + 'personprofile/add" style="cursor:pointer;" id="add_office_person_link" target="_blank" hidden onclick="add_officers_person(this)"><div style="cursor:pointer;" id="click_add_person">Click here to <span style="font-weight:bold">Add Person</span></div></a></div></td>';
        $a += '<td><input type="text" style="text-transform:uppercase;" name="name[]" id="name" class="form-control" value="'+ (transaction_appoint_new_director[i]["company_name"]!=null ? transaction_appoint_new_director[i]["company_name"] : transaction_appoint_new_director[i]["name"]) +'" readonly/><div id="form_name"></div><div class="hidden"><input type="text" class="form-control" name="client_officer_id[]" id="client_officer_id" value="'+transaction_appoint_new_director[i]["id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="officer_id[]" id="officer_id" value="'+transaction_appoint_new_director[i]["officer_id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="officer_field_type[]" id="officer_field_type" value="'+transaction_appoint_new_director[i]["field_type"]+'"/></div></td>';
        $a += '<td><div class="input-group" style="width: 150px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="officer_date_of_appointment" name="date_of_appointment[]" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="'+transaction_appoint_new_director[i]["date_of_appointment"]+'" placeholder="DD/MM/YYYY"></div></td>';
        $a += '<td class="action"><div style="display: inline-block;"><button type="button" class="btn btn-primary" onclick="delete_appoint_new_director(this);">Delete</button></div></td>';
        $a += '</tr>';

        $("#body_appoint_new_director").prepend($a);

        // // ADDED BY JW
        $('#officer_date_of_appointment').datepicker({ 
            dateFormat:'dd MM yyyy',
        });

        !function (i) {
            $.ajax({
                type: "POST",
                url: "transaction/get_client_officers_position",
                data: {"position": transaction_appoint_new_director[i]["position"]},
                dataType: "json",
                success: function(data){
                    $("#form"+i+" #position"+i+"").find("option:eq(0)").html("Select Position");
                    if(data.tp == 1){
                        $.each(data['result'], function(key, val) {
                            var option = $('<option />');
                            option.attr('value', key).text(val);
                            if(data.selected_client_officers_position != null && key == data.selected_client_officers_position)
                            {
                                option.attr('selected', 'selected');
                            }
                            $("#position"+i+"").append(option);
                        });
                        
                    }
                    else{
                        alert(data.msg);
                    }

                    
                }               
            });
        } (i);

        //check_incorp_date();
    }
}

function officerInterface(transaction_client_officers)
{
    var numberForOfficerRetrieve = 1;

    for(var i = 0; i < transaction_client_officers.length; i++)
    {
        $a="";
        $a += '<tr class="row_officer">';
        $a += '<td><select class="form-control" style="text-align:right;" name="position[]" id="position'+i+'"><option value="0" >Select Position</option></select><div id="form_position"></div><div id="alternate_of" hidden><p style="font-weight:bold;">Alternate of: </p><select class="form-control select_alternate_of" id="select_alternate_of'+i+'" style="text-align:right;width: 150px;" name="alternate_of[]"><option value="" >Select Director</option></select><div id="form_alternate_of"></div></div></td>';
        $a += '<td><input type="text" style="text-transform:uppercase;" name="identification_register_no[]" class="form-control" value="'+ (transaction_client_officers[i]["identification_no"]!=null ? transaction_client_officers[i]["identification_no"] : transaction_client_officers[i]["register_no"]) +'" id="gid_add_officer" maxlength="15"/><div id="form_identification_register_no"></div><div style="height:16px;" ><a class="" href="'+ url + 'personprofile/add" style="cursor:pointer;" id="add_office_person_link" target="_blank" hidden onclick="add_officers_person(this)"><div style="cursor:pointer;" id="click_add_person">Click here to <span style="font-weight:bold">Add Person</span></div></a></div></td>';
        $a += '<td><input type="text" style="text-transform:uppercase;" name="name[]" id="name" class="form-control" value="'+ (transaction_client_officers[i]["company_name"]!=null ? transaction_client_officers[i]["company_name"] : transaction_client_officers[i]["name"]) +'" readonly/><div id="form_name"></div><div class="hidden"><input type="text" class="form-control" name="client_officer_id[]" id="client_officer_id" value="'+transaction_client_officers[i]["id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="officer_id[]" id="officer_id" value="'+transaction_client_officers[i]["officer_id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="officer_field_type[]" id="officer_field_type" value="'+transaction_client_officers[i]["field_type"]+'"/></div></td>';
        $a += '<td class="action"><div style="display: inline-block;"><button type="button" class="btn btn-primary" onclick="delete_transaction_officer(this);">Delete</button></div></td>';
        $a += '</tr>';
            
        $("#body_officer").prepend($a);

        if(numberForOfficerRetrieve == 1)
        {
            !function (i) {
                $.ajax({
                    type: "POST",
                    url: "transaction/get_client_officers_position",
                    data: {"position": transaction_client_officers[i]["position"]},
                    dataType: "json",
                    success: function(data){
                        $("#form"+i+" #position"+i+"").find("option:eq(0)").html("Select Position");
                        if(data.tp == 1){
                            localStorage.setItem("officersPosition", JSON.stringify(data));
                        }
                        else{
                            alert(data.msg);
                        }
                    }               
                });
            } (i);

            numberForOfficerRetrieve = numberForOfficerRetrieve + 1;
        }

        var office_position = JSON.parse(localStorage.getItem("officersPosition"));
        $.each(office_position['result'], function(key, val) {
            var option = $('<option />');
            option.attr('value', key).text(val);
            if(transaction_client_officers[i]["position"] != null && key == transaction_client_officers[i]["position"])
            {
                option.attr('selected', 'selected');
            }
            $("#position"+i+"").append(option);
        });

        if(transaction_client_officers[i]["position"] == "7")
        {
            $("#form"+i+" #alternate_of").removeAttr('hidden');

            !function (i) {
                $.ajax({
                    type: "POST",
                    url: "trnasaction/get_director",
                    data: {"company_code": transaction_client_officers[i]["company_code"], "alternate_of": transaction_client_officers[i]["alternate_of"]}, // <--- THIS IS THE CHANGE
                    dataType: "json",
                    async: false,
                    success: function(data){
                        $("#form"+i+" #alternate_of #select_alternate_of"+i+"").find("option:eq(0)").html("Select Director");
                        if(data.tp == 1){
                            $.each(data['result'], function(key, val) {
                                var option = $('<option />');
                                option.attr('value', key).text(val);
                                if(data.selected_director != null && key == data.selected_director)
                                {
                                    option.attr('selected', 'selected');
                                    $("#form"+i+" #alternate_of #select_alternate_of"+i+"").attr('disabled', 'disabled')
                                    /*if (data.selected_director == 166)
                                    {
                                        document.getElementById("nationalityId").disabled = true;
                                    }*/
                                }
                                $("#form"+i+" #alternate_of #select_alternate_of"+i+"").append(option);
                            });
                            //$(".nationality").prop("disabled",false);
                        }
                        else{
                            alert(data.msg);
                        }
                    }               
                });
            } (i);
        }
    }
}

function controllerInterface(transaction_client_controller)
{
    var full_address, contRegisNo, contName, contNational;
    for(var i = 0; i < transaction_client_controller.length; i++)
    {
        if(transaction_client_controller[i]["client_controller_field_type"] == "individual")
        {
            // if(transaction_client_controller[i]["alternate_address"] == 1)
            // {
            //     full_address = address_format (transaction_client_controller[i]["postal_code2"], transaction_client_controller[i]["street_name2"], transaction_client_controller[i]["building_name2"], transaction_client_controller[i]["unit_no3"], transaction_client_controller[i]["unit_no4"]);
            // }
            // else
            // {
                full_address = address_format (transaction_client_controller[i]["postal_code1"], transaction_client_controller[i]["street_name1"], transaction_client_controller[i]["building_name1"], transaction_client_controller[i]["officer_unit_no1"], transaction_client_controller[i]["officer_unit_no2"], transaction_client_controller[i]["foreign_address1"], transaction_client_controller[i]["foreign_address2"], transaction_client_controller[i]["foreign_address3"]);
            //}
            contRegisNo = transaction_client_controller[i]["identification_no"];
            contName = transaction_client_controller[i]["name"];
            contNational = transaction_client_controller[i]["officer_nationality_name"];
            
        }
        else if(transaction_client_controller[i]["client_controller_field_type"] == "company")
        {
            full_address = address_format (transaction_client_controller[i]["company_postal_code"], transaction_client_controller[i]["company_street_name"], transaction_client_controller[i]["company_building_name"], transaction_client_controller[i]["company_unit_no1"], transaction_client_controller[i]["company_unit_no2"], transaction_client_controller[i]["company_foreign_address1"], transaction_client_controller[i]["company_foreign_address2"], transaction_client_controller[i]["company_foreign_address3"]);
            contRegisNo = transaction_client_controller[i]["register_no"];
            contName = transaction_client_controller[i]["company_name"];
            contNational = transaction_client_controller[i]["country_of_incorporation"];
        }
        else if(transaction_client_controller[i]["client_controller_field_type"] == "client")
        {
            full_address = address_format (transaction_client_controller[i]["postal_code"], transaction_client_controller[i]["street_name"], transaction_client_controller[i]["building_name"], transaction_client_controller[i]["client_unit_no1"], transaction_client_controller[i]["client_unit_no2"], transaction_client_controller[i]["foreign_add_1"], transaction_client_controller[i]["foreign_add_2"], transaction_client_controller[i]["foreign_add_3"]);
            contRegisNo = transaction_client_controller[i]["registration_no"];
            contName = transaction_client_controller[i]["client_company_name"];
            contNational = transaction_client_controller[i]["client_country_of_incorporation"];
        }

        //(transaction_client_controller[i]["identification_no"]!=null ? transaction_client_controller[i]["identification_no"] : transaction_client_controller[i]["register_no"] != null ? transaction_client_controller[i]["register_no"] : transaction_client_controller[i]["registration_no"])
        //(transaction_client_controller[i]["company_name"]!=null ? transaction_client_controller[i]["company_name"] : transaction_client_controller[i]["name"] != null ? transaction_client_controller[i]["name"] : transaction_client_controller[i]["client_company_name"])
        //(transaction_client_controller[i]["officer_nationality_name"] != null ? transaction_client_controller[i]["officer_nationality_name"]:transaction_client_controller[i]["country_of_incorporation"])
        $a="";
        // $a += '<form class="tr controller_sort_id" method="post" name="form'+i+'" id="form'+i+'" data-date_of_cessation="'+transaction_client_controller[i]["date_of_cessation"]+'" data-date_of_registration="'+transaction_client_controller[i]["date_of_registration"]+'" data-nationality="'+transaction_client_controller[i]["nationality_name"]+'" data-date_of_birth="'+transaction_client_controller[i]["date_of_birth"]+'" data-address="'+transaction_client_controller[i]["address"]+'" data-registe_no="'+ (transaction_client_controller[i]["identification_no"]!=null ? transaction_client_controller[i]["identification_no"] : transaction_client_controller[i]["register_no"]) +'" data-name="'+ (transaction_client_controller[i]["company_name"]!=null ? transaction_client_controller[i]["company_name"] : transaction_client_controller[i]["name"]) +'">';
        // $a += '<div class="hidden"><input type="text" class="form-control" name="company_code" value="'+transaction_client_controller[i]["company_code"]+'"/></div>';
        // $a += '<div class="hidden"><input type="text" class="form-control" name="client_controller_id[]" id="client_controller_id" value="'+transaction_client_controller[i]["id"]+'"/></div>';
        // $a += '<div class="hidden"><input type="text" class="form-control" name="officer_id" id="officer_id" value="'+transaction_client_controller[i]["officer_id"]+'"/></div>';
        // $a += '<div class="hidden"><input type="text" class="form-control" name="officer_field_type" id="officer_field_type" value="'+transaction_client_controller[i]["field_type"]+'"/></div>';
        // $a += '<div class="td"><div class="input-group mb-md" style="width: 140px;"><input type="text" style="text-transform:uppercase;" name="identification_register_no[]" class="form-control" value="'+ (transaction_client_controller[i]["identification_no"]!=null ? transaction_client_controller[i]["identification_no"] : transaction_client_controller[i]["register_no"]) +'" id="gid_add_controller_officer" disabled="disabled"/><div id="form_identification_register_no"></div><div style=""><a class="" href="'+ url + 'personprofile/add/1" style="cursor:pointer;" id="add_office_person_link" target="_blank" hidden onclick="add_controller_person(this"><div style="cursor:pointer;height:62px;">Click here to <span style="font-weight:bold">Add Person</span></div></a></div></div><div class="input-group mb-md" style="width: 140px;"><input type="text" style="text-transform:uppercase;" name="name[]" id="name" class="form-control" value="'+ (transaction_client_controller[i]["company_name"]!=null ? transaction_client_controller[i]["company_name"] : transaction_client_controller[i]["name"]) +'" readonly/><div id="form_name"></div></div></div>';
        // $a += '<div class="td"><div class="mb-md" style="width: 140px;"><input type="text" name="date_of_birth[]" id="date_of_birth" class="form-control"  value="'+transaction_client_controller[i]["date_of_birth"]+'" readonly/></div><div class="mb-md" style="width: 140px;"><input type="text" style="text-transform:uppercase;" name="nationality[]" id="nationality" class="form-control nationality" value="'+transaction_client_controller[i]["nationality_name"]+'" readonly/></div></div>';
        // $a += '<div class="td"><div class="input-group" style="width: 170px;"><textarea class="form-control" name="address[]" id="controller_address" style="width:100%;height:70px;text-transform:uppercase;" disabled="disabled">'+transaction_client_controller[i]["address"]+'</textarea></div><div id="form_controller_address"></div></div>';
        // $a += '<div class="td action"><div style="display: inline-block; margin-right: 5px; margin-bottom: 5px;"><button type="button" class="btn btn-primary submit_controller" onclick="edit_controller(this);">Edit</button></div><div style="display: inline-block;"><button type="button" class="btn btn-primary" onclick="delete_controller(this);">Delete</button></div></div>';
        // $a += '</form>';

        $a += '<tr class="row_controller">';
        $a += '<td><div class="mb-md"><input type="text" style="text-transform:uppercase;" name="identification_register_no[]" class="form-control" value="'+ contRegisNo +'" id="gid_add_controller_officer"/><div id="form_identification_register_no"></div><div style=""><a class="" href="'+ url + 'personprofile/add/1" style="cursor:pointer;" id="add_office_person_link" target="_blank" hidden onclick="add_controller_person(this)"><div style="cursor:pointer;">Click here to <span style="font-weight:bold">Add Person</span></div></a></div></div><div class="mb-md"><input type="text" style="text-transform:uppercase;" name="name[]" id="name" class="form-control" value="'+ contName +'" readonly/><div id="form_name"></div></div></td>';
        $a += '<td><div class="mb-md"><input type="text" name="date_of_birth[]" id="date_of_birth" class="form-control" value="'+(transaction_client_controller[i]["date_of_birth"] != null ? transaction_client_controller[i]["date_of_birth"] : transaction_client_controller[i]["date_of_incorporation"] != null ? transaction_client_controller[i]["date_of_incorporation"] : transaction_client_controller[i]["incorporation_date"])+'" readonly/></div><div class="mb-md"><input type="text" style="text-transform:uppercase;" name="nationality[]" id="nationality" class="form-control nationality" value="'+ contNational +'" readonly/></div></td>';
        $a += '<td><textarea class="form-control" name="address[]" id="controller_address" style="width:100%;height:70px;text-transform:uppercase;" readonly>'+full_address+'</textarea></div><div id="form_controller_address"></div><div class="hidden"><input type="text" class="form-control" name="client_controller_id[]" id="client_controller_id" value="'+transaction_client_controller[i]["client_controller_id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="officer_id[]" id="officer_id" value="'+transaction_client_controller[i]["officer_id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="officer_field_type[]" id="officer_field_type" value="'+transaction_client_controller[i]["client_controller_field_type"]+'"/></td>';
        $a += '<td class="action"><div style="display: inline-block;"><button type="button" class="btn btn-primary" onclick="delete_controller(this);">Delete</button></div></td>';
        $a += '</tr>';
            
        $("#body_controller").prepend($a);
    }
}

function billingInterface(transaction_billing)
{
    for(var h = 0; h < transaction_billing.length; h++)
    {
        var i = h + 1;
        latest_client_billing_info_id = transaction_billing[h]["client_billing_info_id"];
        $a="";
        $a += '<tr num="'+i+'" class="row_billing">';
        $a += '<td><div style="margin-bottom: 35px !important;"><select class="form-control billing_service" style="width: 100%;" name="service[]" id="service'+i+'" onchange="optionCheckBilling(this);"><option value="0" >Select Service</option></select><div id="form_service"></div></div></td>';
        $a += '<td><div class="mb-md"><textarea class="form-control invoice_description" name="invoice_description['+i+']"  id="invoice_description" rows="3" style="width:250px">'+transaction_billing[h]["invoice_description"]+'</textarea></div><div class="hidden"><input type="text" class="form-control" name="client_billing_info_id['+i+']" id="client_billing_info_id" value="'+transaction_billing[h]["client_billing_info_id"]+'"/></div></td>';
        $a += '<td><select class="form-control currency" style="text-align:right;width: 100%;" name="currency['+i+']" id="service_currency'+i+'"><option value="0" >Select Currency</option></select><div id="form_currency"></div><br/><input type="text" name="amount['+i+']" class="numberdes form-control amount" value="'+ addCommas(transaction_billing[h]["amount"])+'" id="amount" style="width:100%;text-align:right;"/><div id="form_amount"></div></td>';
        $a += '<td><select class="form-control" style="width: 100%;" name="unit_pricing['+i+']" id="unit_pricing'+i+'"><option value="0" >Select Unit Pricing</option></select><div id="form_unit_pricing"></div></td>';
        $a += '<td><select class="form-control" style="width: 100%;" name="servicing_firm['+i+']" id="servicing_firm'+i+'"><option value="0" >Select Servicing Firm</option></select><div id="form_servicing_firm"></div></td>';
        $a += '<td><div class="action"><button type="button" class="btn btn-primary" onclick="delete_billing_info(this);">Delete</button></div></td>';
        $a += "</tr>";

        $("#body_billing_info").append($a);

        if(numberForRetrieve == 1)
        {
            !function (i) {
                $.ajax({
                    type: "POST",
                    url: "transaction/get_billing_info_service",
                    data: {"company_code": transaction_company_code, "service": transaction_billing[h]["service"]},
                    dataType: "json",
                    success: function(data){
                        if(data.tp == 1){
                            localStorage.setItem("billing_info_service", JSON.stringify(data));
                        }
                        else{
                            alert(data.msg);
                        }  
                    }               
                });
            } (i);

            !function (i) {
                $.ajax({
                    type: "GET",
                    url: "masterclient/get_currency",
                    async: false,
                    dataType: "json",
                    success: function(data){
                        if(data.tp == 1){
                            localStorage.setItem("billing_currency", JSON.stringify(data['result']));
                        }
                        else{
                            alert(data.msg);
                        }  
                    }               
                });
            }(i);

            !function (i) {
                $.ajax({
                    type: "GET",
                    url: "masterclient/get_unit_pricing",
                    dataType: "json",
                    async: false,
                    success: function(data){
                        if(data.tp == 1){
                            localStorage.setItem("billing_unit_pricing", JSON.stringify(data['result']));
                        }
                        else{
                            alert(data.msg);
                        }  
                    }               
                });
            }(i);

            !function (i) {
                $.ajax({
                    type: "GET",
                    url: "masterclient/get_servicing_firm",
                    async:false,
                    dataType: "json",
                    success: function(data){
                        if(data.tp == 1){
                            localStorage.setItem("billing_servicing_firm", JSON.stringify(data['result']));
                        }
                        else{
                            alert(data.msg);
                        }  
                    }               
                });
            }(i);

            numberForRetrieve = numberForRetrieve + 1;
        }

        $("#service"+i).find("option:eq(0)").html("Select Service");
        var info_list = JSON.parse(localStorage.getItem("billing_info_service"));
        var category_description = '';
        var optgroup = '';

        for(var t = 0; t < info_list.selected_billing_info_service_category.length; t++)
        {
            if(category_description != info_list.selected_billing_info_service_category[t]['category_description'])
            {
                if(optgroup != '')
                {
                    $("#service"+i).append(optgroup);
                }
                optgroup = $('<optgroup label="' + info_list.selected_billing_info_service_category[t]['category_description'] + '" />');
            }

            category_description = info_list.selected_billing_info_service_category[t]['category_description'];

            for(var b = 0; b < info_list.result.length; b++)
            {
                if(category_description == info_list.result[b]['category_description'])
                {
                    var option = $('<option />');
                    option.attr('data-description', info_list.result[b]['invoice_description']).attr('data-currency', info_list.result[b]['currency']).attr('data-unit_pricing', info_list.result[b]['unit_pricing']).attr('data-amount', info_list.result[b]['amount']).attr('value', info_list.result[b]['id']).text(info_list.result[b]['service_name']).appendTo(optgroup);

                    if(info_list.selected_service != null && info_list.result[b]['id'] == transaction_billing[h]["service"])
                    {
                        option.attr('selected', 'selected');
                    }
                }
            }
        }
        $("#service"+i).append(optgroup);
        $("#service"+i).select2({
            formatNoMatches: function () {
                return "No Result. <a href='our_firm/edit/"+info_list.firm_id+"' onclick='open_new_tab("+info_list.firm_id+")' target='_blank'>Click here to add Service</a>"
            },
            width: '250px'
        })

        $.each(JSON.parse(localStorage.getItem("billing_currency")), function(key, val) {
            var option = $('<option />');
            option.attr('value', key).text(val);
            if(transaction_billing[h]["currency"] != null && key == transaction_billing[h]["currency"])
            {
                option.attr('selected', 'selected');
            }
            $("#service_currency"+i).append(option);
        });

        $.each(JSON.parse(localStorage.getItem("billing_unit_pricing")), function(key, val) {
            var option = $('<option />');
            option.attr('value', key).text(val);
           
            if(transaction_billing[h]["unit_pricing"] != null && key == transaction_billing[h]["unit_pricing"])
            {
                option.attr('selected', 'selected');
            }
            $("#unit_pricing"+i).append(option);

        });

        $.each(JSON.parse(localStorage.getItem("billing_servicing_firm")), function(key, val) {
            var option = $('<option />');
            option.attr('value', key).text(val);
            if(transaction_billing[h]["servicing_firm"] != null && key == transaction_billing[h]["servicing_firm"])
            {
                option.attr('selected', 'selected');
            }
            
            $("#servicing_firm"+i).append(option);
        });
    }
}

function shareTransferInterface(transaction_member)
{
    for(var i = 0; i < transaction_member.length; i++)
    {   
        if(0 > transaction_member[i]["number_of_share"])
        {   
            var officer_id = transaction_member[i]["officer_id"]!=null ? transaction_member[i]["officer_id"] : (transaction_member[i]["officer_company_id"]!=null ? transaction_member[i]["officer_company_id"] : transaction_member[i]["client_company_id"]);
            var field_type = transaction_member[i]["officer_field_type"]!=null ? transaction_member[i]["officer_field_type"] : (transaction_member[i]["officer_company_field_type"]!=null ? transaction_member[i]["officer_company_field_type"] : transaction_member[i]["client_company_field_type"]);
            
            $a0=""; 
            $a0 += '<div class="tr editing transfer transfer_coll" method="post" name="form'+i+'" id="form'+i+'" num="'+i+'">';
            $a0 += '<div class="hidden"><input type="text" class="form-control" name="company_code" value="'+transaction_member[i]["company_code"]+'"/></div>';
            $a0 += '<div class="hidden"><input type="text" class="form-control cert_id" name="cert_id[]" id="cert_id" value="'+transaction_member[i]["cert_id"]+'"/></div>';
            $a0 += '<div class="hidden"><input type="text" class="form-control" name="transfer_id[]" id="transfer_id" value="'+transaction_member[i]["id"]+'"/></div>';
            $a0 += '<div class="hidden"><input type="text" class="form-control" name="officer_id['+i+']" id="officer_id" value="'+(officer_id)+'"/></div>';
            $a0 += '<div class="hidden"><input type="text" class="form-control" name="field_type['+i+']" id="field_type" value="'+(field_type)+'"/></div>';
            /*$a += '<div class="td">'+$count_allotment+'</div>';*/
            $a0 += '<div class="td"><div class="transfer_group mb-md" style="width: 350px"><input type="hidden" class="form-control" name="certID['+i+']" value="" id="certID"/><select class="form-control person_id" style="text-align:right;width: 100%;" name="id['+i+']" id="person_id"><option value="0" >Select ID</option></select></div></div>';
            /*$a0 += '<div class="td"><div class="transfer_group mb-md"><input type="text" name="name['+0+']" class="form-control" value=""/></div></div>';
            $a0 += '<div class="td"><div class="transfer_group mb-md"><input type="text" name="number_of_share['+0+']" class="form-control" value=""/></div></div>';*/
            $a0 += '<div class="hidden"><div class="transfer_group mb-md" id="name" style="width: 200px; text-align:left"></div><input type="hidden" class="form-control" name="person_name['+i+']" value="" id="person_name"/></div>';
            $a0 += '<div class="td"><div style="text-align:right;width: 180px" class="transfer_group mb-md" id="number_of_share"></div><input type="hidden" class="form-control" name="current_share['+i+']" value="" id="current_share"/><input type="hidden" class="form-control" name="amount_share['+i+']" value="" id="amount_share"/><input type="hidden" class="form-control" name="no_of_share_paid['+i+']" value="" id="no_of_share_paid"/><input type="hidden" class="form-control" name="amount_paid['+i+']" value="" id="amount_paid"/></div>';
            $a0 += '<div class="td"><div class="transfer_group mb-md" style="width: 200px"><input type="text" class="numberdes form-control share_transfer" style="text-align:right;" name="share_transfer['+i+']" value="'+addCommas(Math.abs(transaction_member[i]["number_of_share"]))+'" id="share_transfer" pattern="^[0-9,]+$"/></div></div>';
            $a0 += '<div class="td"><div class="transfer_group mb-md" style="width: 100px"><input type="text" class="numberdes form-control consideration" style="text-align:right;" name="consideration['+i+']" value="'+addCommas(Math.abs(transaction_member[i]["consideration"]))+'" id="consideration" pattern="^[0-9,]+$"/></div></div>';
            $a0 += '<div class="hidden"><div class="transfer_group mb-md" style="width: 100px"><input type="text" class="form-control from_certificate" name="from_certificate['+i+']" value="'+transaction_member[i]["certificate_no"]+'" id="to_certificate"/></div></div>';
            //$a0 += '<div class="td action"><button type="button" class="btn btn-primary delete_transfer_button" onclick="delete_transfer(this)" style="display: none;">Delete</button></div>';
            $a0 += '</div>';

            $("#transfer_add").append($a0); 

            if($("#transfer_add > div").length > 1)
            {
                $('.delete_transfer_button').css('display','block');
            }
            for(var a = 0; a < allotmentPeople.length; a++)
            {
                var option = $('<option />');
                option.attr('data-name', (allotmentPeople[a]["company_name"]!=null ? allotmentPeople[a]["company_name"] : (allotmentPeople[a]["name"]!=null ? allotmentPeople[a]["name"] : allotmentPeople[a]["client_company_name"])));
                option.attr('data-numberofshare', allotmentPeople[a]['number_of_share']);
                option.attr('data-amountshare', allotmentPeople[a]['amount_share']);
                option.attr('data-noofsharepaid', allotmentPeople[a]['no_of_share_paid']);
                option.attr('data-amountpaid', allotmentPeople[a]['amount_paid']);
                option.attr('data-officerid', allotmentPeople[a]['officer_id']);
                option.attr('data-fieldtype', allotmentPeople[a]['field_type']);
                option.attr('data-certID', allotmentPeople[a]['id']);
                option.attr('value', allotmentPeople[a]['officer_id']).text((allotmentPeople[a]["identification_no"]!=null ? (allotmentPeople[a]["identification_no"] + " - " + allotmentPeople[a]["name"]) : ((allotmentPeople[a]["register_no"]!=null) ? (allotmentPeople[a]["register_no"] + " - " + allotmentPeople[a]["company_name"]) : (allotmentPeople[a]["registration_no"] + " - " + allotmentPeople[a]["client_company_name"]))) + " (" +addCommas(allotmentPeople[a]['number_of_share'])+ ") ");

                if(transaction_member)
                {
                    if(transaction_member[i]["officer_id"] != null && allotmentPeople[a]['officer_id'] == transaction_member[i]["officer_id"] && allotmentPeople[a]['id'] == transaction_member[i]["previous_certificate_id"])
                    {
                        $("#form"+i+" #name").text((allotmentPeople[a]["company_name"]!=null ? allotmentPeople[a]["company_name"] : (allotmentPeople[a]["name"]!=null ? allotmentPeople[a]["name"] : allotmentPeople[a]["client_company_name"])));
                        // $("#form"+i+" #number_of_share").text(parseInt(allotmentPeople[a]['number_of_share']) - parseInt(transfer[i]['number_of_share']));
                        
                        $("#loadingmessage").show();
                        $.ajax({
                            type: "POST",
                            url: "transaction/check_edit_number_of_share_person",
                            data: {"transaction_master_id":$("#transaction_trans #transaction_master_id").val(), "officer_id":officer_id, "field_type":field_type, "transaction_certificate_id":transaction_member[i]["cert_id"], "certID":transaction_member[i]["previous_certificate_id"]},
                            dataType: "json",
                            async: false,
                            success: function(responses){
                                $("#loadingmessage").hide();

                                if(responses[0]['total_number_of_share'] != null)
                                {   
                                    var latest_number_share = parseInt(allotmentPeople[a]['number_of_share']) + parseInt(responses[0]['total_number_of_share']);
                                    $("#form"+i+" #number_of_share").text(addCommas(latest_number_share.toString()));
                                    //$("#form"+i+" #current_share").val(number_of_share + responses[0]['total_number_of_share']);
                                }
                                else
                                {
                                    $("#form"+i+" #number_of_share").text(addCommas(parseInt(allotmentPeople[a]['number_of_share'])));
                                    
                                }
                            }
                        });
                        $("#form"+i+" #current_share").val(addCommas(parseInt(allotmentPeople[a]['number_of_share'])));
                        $("#form"+i+" #person_name").val((allotmentPeople[a]["company_name"]!=null ? allotmentPeople[a]["company_name"] : (allotmentPeople[a]["name"]!=null ? allotmentPeople[a]["name"] : allotmentPeople[a]["client_company_name"])));
                        /*$("#form"+i+" #current_share").val(addCommas(parseInt(allotmentPeople[a]['number_of_share']) - parseInt(transfer[i]['number_of_share'])));
                        $("#form"+i+" #amount_share").val(parseFloat(alloment_people[a]['amount_share']) - parseFloat(transfer[i]['amount_share']));
                        $("#form"+i+" #no_of_share_paid").val(parseInt(alloment_people[a]['no_of_share_paid']) - parseInt(transfer[i]['no_of_share_paid']));
                        $("#form"+i+" #amount_paid").val(parseFloat(alloment_people[a]['amount_paid']) - parseFloat(transfer[i]['amount_paid']));*/

                        
                        $("#form"+i+" #amount_share").val(parseFloat(allotmentPeople[a]['amount_share']));
                        $("#form"+i+" #no_of_share_paid").val(parseInt(allotmentPeople[a]['no_of_share_paid']));
                        $("#form"+i+" #amount_paid").val(parseFloat(allotmentPeople[a]['amount_paid']));
                        $("#form"+i+" #certID").val(parseFloat(allotmentPeople[a]['id']));
                        option.attr('selected', 'selected');
                    }
                }

                $("#form"+i+" #person_id").append(option); 
            }

        }
        else if(transaction_member[i]["number_of_share"] > 0)
        {
            $atoe =""; 
            $atoe += '<div class="tr editing to to_coll" method="post" name="form_to'+i+'" id="form_to'+i+'" num_to="'+i+'">';
            $atoe += '<div class="hidden"><input type="text" class="form-control" name="company_code" value="'+transaction_member[i]["company_code"]+'"/></div>';
            $atoe += '<div class="hidden"><input type="text" class="form-control to_cert_id" name="to_cert_id[]" id="to_cert_id" value="'+transaction_member[i]["cert_id"]+'"/></div>';
            $atoe += '<div class="hidden"><input type="text" class="form-control" name="to_id[]" id="to_id" value="'+transaction_member[i]["id"]+'"/></div>';
            $atoe += '<div class="hidden"><input type="text" class="form-control" name="to_officer_id['+i+']" id="to_officer_id" value="'+(transaction_member[i]["officer_id"]!=null ? transaction_member[i]["officer_id"] : (transaction_member[i]["officer_company_id"]!=null ? transaction_member[i]["officer_company_id"] : transaction_member[i]["client_company_id"]))+'"/></div>';
            $atoe += '<div class="hidden"><input type="text" class="form-control" name="to_field_type['+i+']" id="to_field_type" value="'+(transaction_member[i]["officer_field_type"]!=null ? transaction_member[i]["officer_field_type"] : (transaction_member[i]["officer_company_field_type"]!=null ? transaction_member[i]["officer_company_field_type"] : transaction_member[i]["client_company_field_type"]))+'"/></div>';
            $atoe += '<div class="hidden"><input type="text" class="form-control" name="previous_new_cert['+i+']" id="previous_new_cert" value="'+transaction_member[i]["new_certificate_no"]+'"/></div>';
            $atoe += '<div class="hidden"><input type="text" class="form-control" name="previous_cert['+i+']" id="previous_cert" value="'+transaction_member[i]["certificate_no"]+'"/></div>';

            /*$a += '<div class="td">'+$count_allotment+'</div>';*/
            $atoe += '<div class="td"><div class="transfer_group"><input type="text" name="id_to['+i+']" class="form-control get_person_id" value="'+(transaction_member[i]["identification_no"]!=null ? transaction_member[i]["identification_no"] : (transaction_member[i]["register_no"]!=null ? transaction_member[i]["register_no"] : transaction_member[i]["registration_no"]))+'" id="get_person_id" style="text-transform:uppercase;"/></div><div style="height:16px;" ><a class="" href="'+ url + 'personprofile/add/1" style="cursor:pointer;" id="add_person_link" target="_blank" onclick="add_transfer_member(this)" hidden><div style="cursor:pointer;">Click here to <span style="font-weight:bold">Add Entity</span></div></a></div></div>';
            /*$a0 += '<div class="td"><div class="transfer_group mb-md"><input type="text" name="name['+0+']" class="form-control" value=""/></div></div>';
            $a0 += '<div class="td"><div class="transfer_group mb-md"><input type="text" name="number_of_share['+0+']" class="form-control" value=""/></div></div>';*/
            $atoe += '<div class="td"><div class="transfer_group mb-md" id="name_to" style="width: 200px; text-align:left">'+(transaction_member[i]["company_name"]!=null ? transaction_member[i]["company_name"] : (transaction_member[i]["name"]!=null ? transaction_member[i]["name"] : transaction_member[i]["client_company_name"]))+'</div><input type="hidden" class="form-control" name="to_person_name['+i+']" value="'+(transaction_member[i]["company_name"]!=null ? transaction_member[i]["company_name"] : (transaction_member[i]["name"]!=null ? transaction_member[i]["name"] : transaction_member[i]["client_company_name"]))+'" id="to_person_name"/></div>';
            $atoe += '<div class="td"><div class="transfer_group mb-md" style="width: 100%"><input type="text" style="text-align:right;" class="numberdes form-control number_of_share_to" name="number_of_share_to['+i+']" value="'+addCommas(transaction_member[i]["number_of_share"])+'" id="number_of_share_to" pattern="^[0-9,]+$" readonly="true"/></div></div>';
            /*$ato += '<div class="td"><div class="transfer_group mb-md"><input type="text" class="form-control" name="certificate['+0+']" value="" id="certificate"/></div></div>';*/
            $atoe += '<div class="hidden"><div class="transfer_group mb-md" style="width: 100px"><input type="text" class="form-control to_certificate" name="to_certificate['+i+']" value="'+transaction_member[i]["certificate_no"]+'" id="to_certificate"/></div></div>';
            //$atoe += '<div class="td action"><button type="button" class="btn btn-primary delete_to_button" onclick="delete_to(this)" style="display: none;">Delete</button></div>';

            $atoe += '</div>';

            $("#transfer_to_add").append($atoe); 

            if($("#transfer_to_add > div").length > 1)
            {
                $('.delete_to_button').css('display','block');
            }
        }
        
    }
    sum_total();
}

function add_member(elem)
{
    jQuery(elem).parent().parent().find('#get_allot_person_name').val("");
    jQuery(elem).attr('hidden',"true");
}

function shareAllotmentInterface(transaction_member)
{
    for(var z = 0; z < transaction_member.length; z++)
    {
        var v = z + 1;
        $a = ""; 
        $a += '<tr class="member_tr_'+v+' row_share_allotment" num="'+v+'">';
        $a += '<td><div><input type="text" name="id['+v+']" class="form-control id" value="'+(transaction_member[z]["identification_no"]!=null ? transaction_member[z]["identification_no"] : (transaction_member[z]["register_no"]!=null ? transaction_member[z]["register_no"] : transaction_member[z]["registration_no"]))+'" id="get_allot_person_name" style="text-transform:uppercase;"/></div><div style="height:16px;" ><a class="" href="'+ url + 'personprofile/add/1" style="cursor:pointer;" id="add_person_link" target="_blank" onclick="add_member(this)" hidden><div style="cursor:pointer;">Click here to <span style="font-weight:bold">Add Entity</span></div></a></div><div class="input-group mb-md name"><input type="text" name="name['+v+']" class="form-control" value="'+(transaction_member[z]["company_name"]!=null ? transaction_member[z]["company_name"] : (transaction_member[z]["name"] != null ? transaction_member[z]["name"] : transaction_member[z]["client_company_name"]))+'" tabindex="-1" readonly/><div id="form_name"></div></div></td>';
        $a += '<td><div class="mb-md"><select class="form-control" style="text-align:right;width: 100%;" name="class[]" id="class'+v+'" onchange="optionCheckClass(this);"><option value="0" >Select Class</option></select><div id="form_class"></div><div id="other_class" hidden><p style="font-weight:bold;">Others: </p><input type="text" name="other_class['+v+']" class="form-control input_other_class" value="'+transaction_member[z]["other_class"]+'" disabled="true"/><div id="form_other_class"></div></div></div><div class="input-group mb-md"><select class="form-control" style="text-align:right;width: 170px;" name="currency[]" id="currency'+v+'"><option value="0" >Select Currency</option></select><div id="form_currency"></div></div></td>';
        $a += '<td><div class="input-group mb-md" style="width:100%;"><input type="text" name="number_of_share['+v+']" class="numberdes form-control number_of_share" value="'+addCommas(transaction_member[z]["number_of_share"])+'" id="number_of_share" style="text-align:right; width:100%;" pattern="^[0-9,]+$"/><div id="form_number_of_share"></div></div><div class="input-group mb-md" style="width:100%;"><input type="text" name="amount_share['+v+']" id="amount_share" class="numberdes form-control amount_share" value="'+addCommas(transaction_member[z]["amount_share"])+'" style="text-align:right; width:100%;" pattern="[0-9.,]"/><div id="form_amount_share"></div></div></td>';
        $a += '<td><div class="input-group mb-md" style="width:100%;"><input type="text" tabindex="-1" name="no_of_share_paid['+v+']" class="numberdes form-control no_of_share_paid" value="'+addCommas(transaction_member[z]["no_of_share_paid"])+'" id="no_of_share_paid" style="text-align:right; width:100%;" readonly/><div id="form_no_of_share_paid"></div></div><div class="input-group mb-md" style="width:100%;"><input type="text" name="amount_paid['+v+']" id="amount_paid" class="numberdes form-control amount_paid" value="'+addCommas(transaction_member[z]["amount_paid"])+'" style="text-align:right; width:100%;" pattern="[0-9.,]"/><div id="form_amount_paid"></div></div></td>';
        $a += '<td><div class="mb-md"><input type="text" name="certificate['+v+']" class="form-control certificate" value="'+transaction_member[z]["certificate_no"]+'" id="certificate"/></div></td>';
        $a += '<td><div class="action"><button type="button" class="btn btn-primary delete_allotment_button" onclick="delete_share_allotment(this)" style="display: block;">Delete</button></div><div class="hidden"><input type="text" class="form-control cert_id" name="cert_id[]" id="cert_id" value="'+transaction_member[z]["cert_id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="member_share_id[]" id="member_share_id" value="'+transaction_member[z]["id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="officer_id['+v+']" id="officer_id" value="'+(transaction_member[z]["officer_id"]!=null ? transaction_member[z]["officer_id"] : (transaction_member[z]["officer_company_id"] != null ? transaction_member[z]["officer_company_id"] : transaction_member[z]["client_company_id"]))+'"/></div><div class="hidden"><input type="text" class="form-control" name="field_type['+v+']" id="field_type" value="'+(transaction_member[z]["officer_field_type"]!=null ? transaction_member[z]["officer_field_type"] : (transaction_member[z]["officer_company_field_type"] != null ? transaction_member[z]["officer_company_field_type"] : transaction_member[z]["client_company_field_type"]))+'"/></div></td>';
        $a += '</tr>';

        $("#allotment_add").append($a); 

        !function (v) {
            $.ajax({
                type: "GET",
                url: "masterclient/get_sharetype",
                dataType: "json",
                success: function(data){
                    $(".member_tr_"+v+" #class"+v+"").find("option:eq(0)").html("Select Class");
                    if(data.tp == 1){
                        $.each(data['result'], function(key, val) {
                            var option = $('<option />');
                            option.attr('value', key).text(val);
                            if(transaction_member[v-1]["class_id"] != null && key == transaction_member[v-1]["class_id"])
                            {
                                option.attr('selected', 'selected');
                            }
                            $(".member_tr_"+v+" #class"+v+"").append(option);

                            if(transaction_member[v-1]["class_id"] == 2)
                            {
                                $(".member_tr_"+v+" #other_class").show();
                                $(".member_tr_"+v+" .input_other_class").attr('disabled', false);
                                
                            }
                        });
                    }
                    else{
                        alert(data.msg);
                    }
                }               
            });
        } (v);

        !function (v) {
            $.ajax({
                type: "GET",
                url: "masterclient/get_currency",
                dataType: "json",
                success: function(data){
                    $(".member_tr_"+v+" #currency"+v+"").find("option:eq(0)").html("Select Currency");
                    if(data.tp == 1){
                        $.each(data['result'], function(key, val) {
                            var option = $('<option />');
                            option.attr('value', key).text(val);
                            if(transaction_member[v-1]["currency_id"] != null && key == transaction_member[v-1]["currency_id"])
                            {
                                option.attr('selected', 'selected');
                            }

                            $(".member_tr_"+v+" #currency"+v+"").append(option);
                        });
                    }
                    else{
                        alert(data.msg);
                    }
                }               
            });
        } (v);
    }
}

function memberInterface(transaction_member){
    for(var z = 0; z < transaction_member.length; z++)
    {
        var v = z + 1;
        $a = ""; 
        $a += '<tr class="member_tr_'+v+' row_allotment" num="'+v+'">';
        $a += '<td><div><input type="text" name="id['+v+']" class="form-control id" value="'+(transaction_member[z]["identification_no"]!=null ? transaction_member[z]["identification_no"] : (transaction_member[z]["register_no"]!=null ? transaction_member[z]["register_no"] : transaction_member[z]["registration_no"]))+'" id="get_allot_person_name" style="text-transform:uppercase;"/></div><div style="height:16px;" ><a class="" href="'+ url + 'personprofile/add/1" style="cursor:pointer;" id="add_person_link" target="_blank" onclick="add_member(this)" hidden><div style="cursor:pointer;">Click here to <span style="font-weight:bold">Add Entity</span></div></a></div><div class="input-group mb-md name"><input type="text" name="name['+v+']" class="form-control" value="'+(transaction_member[z]["company_name"]!=null ? transaction_member[z]["company_name"] : (transaction_member[z]["name"] != null ? transaction_member[z]["name"] : transaction_member[z]["client_company_name"]))+'" tabindex="-1" readonly/><div id="form_name"></div></div></td>';
        $a += '<td><div class="mb-md"><select class="form-control" style="text-align:right;width: 100%;" name="class[]" id="class'+v+'" onchange="optionCheckClass(this);"><option value="0" >Select Class</option></select><div id="form_class"></div><div id="other_class" hidden><p style="font-weight:bold;">Others: </p><input type="text" name="other_class['+z+']" class="form-control input_other_class" value="'+transaction_member[z]["other_class"]+'" disabled="true"/><div id="form_other_class"></div></div></div><div class="input-group mb-md"><select class="form-control" style="text-align:right;width: 170px;" name="currency[]" id="currency'+v+'"><option value="0" >Select Currency</option></select><div id="form_currency"></div></div></td>';
        $a += '<td><div class="input-group mb-md"><input type="text" name="number_of_share['+v+']" class="numberdes form-control number_of_share" value="'+addCommas(transaction_member[z]["number_of_share"])+'" id="number_of_share" style="text-align:right;" pattern="^[0-9,]+$"/><div id="form_number_of_share"></div></div><div class="input-group mb-md"><input type="text" name="amount_share['+v+']" id="amount_share" class="numberdes form-control amount_share" value="'+addCommas(transaction_member[z]["amount_share"])+'" style="text-align:right;" pattern="[0-9.,]"/><div id="form_amount_share"></div></div></td>';
        $a += '<td><div class="input-group mb-md"><input type="text" tabindex="-1" name="no_of_share_paid['+v+']" class="numberdes form-control no_of_share_paid" value="'+addCommas(transaction_member[z]["no_of_share_paid"])+'" id="no_of_share_paid" style="text-align:right;" readonly/><div id="form_no_of_share_paid"></div></div><div class="input-group mb-md"><input type="text" name="amount_paid['+v+']" id="amount_paid" class="numberdes form-control amount_paid" value="'+addCommas(transaction_member[z]["amount_paid"])+'" style="text-align:right;" pattern="[0-9.,]"/><div id="form_amount_paid"></div></div></td>';
        $a += '<td><div class="mb-md"><input type="text" name="certificate['+v+']" class="form-control certificate" value="'+transaction_member[z]["certificate_no"]+'" id="certificate"/></div></td>';
        $a += '<td><div class="action"><button type="button" class="btn btn-primary delete_allotment_button" onclick="delete_allotment(this)" style="display: block;">Delete</button></div><div class="hidden"><input type="text" class="form-control cert_id" name="cert_id[]" id="cert_id" value="'+transaction_member[z]["cert_id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="member_share_id[]" id="member_share_id" value="'+transaction_member[z]["id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="officer_id['+v+']" id="officer_id" value="'+(transaction_member[z]["officer_id"]!=null ? transaction_member[z]["officer_id"] : (transaction_member[z]["officer_company_id"] != null ? transaction_member[z]["officer_company_id"] : transaction_member[z]["client_company_id"]))+'"/></div><div class="hidden"><input type="text" class="form-control" name="field_type['+v+']" id="field_type" value="'+(transaction_member[z]["officer_field_type"]!=null ? transaction_member[z]["officer_field_type"] : (transaction_member[z]["officer_company_field_type"] != null ? transaction_member[z]["officer_company_field_type"] : transaction_member[z]["client_company_field_type"]))+'"/></div></td>';
        $a += '</tr>';

        $("#allotment_add").append($a); 


        !function (v) {
            $.ajax({
                type: "GET",
                url: "masterclient/get_sharetype",
                dataType: "json",
                success: function(data){
                    $(".member_tr_"+v+" #class"+v+"").find("option:eq(0)").html("Select Class");
                    if(data.tp == 1){
                        $.each(data['result'], function(key, val) {
                            var option = $('<option />');
                            option.attr('value', key).text(val);
                            if(transaction_member[v-1]["class_id"] != null && key == transaction_member[v-1]["class_id"])
                            {
                                option.attr('selected', 'selected');
                            }
                            $(".member_tr_"+v+" #class"+v+"").append(option);

                            if(transaction_member[v-1]["class_id"] == 2)
                            {
                                $(".member_tr_"+v+" #other_class").show();
                                $(".member_tr_"+v+" .input_other_class").attr('disabled', false);
                                
                            }
                        });
                    }
                    else{
                        alert(data.msg);
                    }
                }               
            });
        } (v);

        !function (v) {
            $.ajax({
                type: "GET",
                url: "masterclient/get_currency",
                dataType: "json",
                success: function(data){
                    $(".member_tr_"+v+" #currency"+v+"").find("option:eq(0)").html("Select Currency");
                    if(data.tp == 1){
                        $.each(data['result'], function(key, val) {
                            var option = $('<option />');
                            option.attr('value', key).text(val);
                            if(transaction_member[v-1]["currency_id"] != null && key == transaction_member[v-1]["currency_id"])
                            {
                                option.attr('selected', 'selected');
                            }

                            $(".member_tr_"+v+" #currency"+v+"").append(option);
                        });
                    }
                    else{
                        alert(data.msg);
                    }
                }               
            });
        } (v);
    }
}

function getPurchaseCommonSealInterface()
{
    $("#transaction_data .panel").remove();
    $.post("transaction/get_purchase_common_seal_page", {id: transaction_id, transaction_task_id: transaction_task_id}, function(data){
        array_for_purchase_common_seal = JSON.parse(data);

        if(array_for_purchase_common_seal['error'] == null)
        {
            transaction_purchase_common_seal_data = array_for_purchase_common_seal[0]["transaction_purchase_common_seal_data"];
            client_list = array_for_purchase_common_seal[0]["client_list"];
            transaction_common_seal_vendor_list = array_for_purchase_common_seal[0]["transaction_common_seal_vendor_list"];
            $("#transaction_data").append(array_for_purchase_common_seal['interface']);

            $('.tab_2_purchase_date').datepicker().datepicker('setStartDate', '01/01/1960').datepicker("setDate", new Date());

            $.each(transaction_common_seal_vendor_list, function(key, val) {
                var option = $('<option />');
                option.attr('value', val["id"]).text(val["vendor_name"] + " (" + val["vendor_email"] + ")");
                $('.common_seal_vendor').append(option);
            });

            if(transaction_purchase_common_seal_data)
            {
                purchase_cs_index = transaction_purchase_common_seal_data.length;
                purchaseCommonSealInterface(transaction_purchase_common_seal_data, client_list);

                if(transaction_purchase_common_seal_data[0]["service_status"] == 2 && transaction_purchase_common_seal_data[0]["status"] == 2)
                {
                    $(".submitPurchaseCommonSealInfo").prop('disabled', true);
                }
            }
            else
            {
                $('#loadingWizardMessage').hide();

                purchase_cs_index = 0;
                $( document ).ready(function() {
                    firstRowCS();
                });
            }

            loadLastTab();
        }
        else
        {
            $('#loadingWizardMessage').hide();
            toastr.error(array_for_purchase_common_seal['error'], "Error");
        }
    });
}

function purchaseCommonSealInterface(transaction_purchase_common_seal_data, client_list)
{
    $(".tab_2_purchase_date").val(transaction_purchase_common_seal_data[0]["date"]);
    $(".common_seal_vendor").val(transaction_purchase_common_seal_data[0]["vendor"]);

    for(var t = 0; t < transaction_purchase_common_seal_data.length; t++)
    {
        $a = ""; 
        $a += '<tr class="row_purchase_common_seal">';
        $a += '<td><select class="form-control company_name" style="width:100%;" name="company_name[]" id="company_name'+t+'"><option value="0">Select Company Name</option></select></td>';
        $a += '<td><input type="text" style="text-transform:uppercase;" name="register_no[]" class="form-control register_no" value="'+transaction_purchase_common_seal_data[t]["registration_no"]+'" id="register_no" readonly="true"/></td>';
        $a += '<td><select class="form-control product" style="text-align:right;" name="product[]" id="product'+t+'"><option value="0">Select Product</option><option value="Common Seal">Common Seal</option><option value="Self-inking round stamp">Self-inking round stamp</option></select></td>';
        $a += '<td class="action"><div style="display: inline-block;"><button type="button" class="btn btn-primary" onclick="delete_purchase_common_seal(this);">Delete</button></div></td>';
        $a += '</tr>';
        
        $("#purchase_common_seal_table").prepend($a); 

        $.each(client_list, function(key, val) {
            var option = $('<option />');
            option.attr('value', val["company_code"]).attr('data-register_no', val['registration_no']).text(val["company_name"]);
            if(transaction_purchase_common_seal_data[t]["company_code"] != null && val["company_code"] == transaction_purchase_common_seal_data[t]["company_code"])
            {
                option.attr('selected', 'selected');
            }
            $("#company_name"+t).append(option);
        });

        $("#company_name"+t).select2();
        $("#product"+t).val(transaction_purchase_common_seal_data[t]["order_for"]);
    }
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

function removeCommas(str) {
    while (str.search(",") >= 0) {
        str = (str + "").replace(',', '');
    }
    return str;
};

function changeDateFormat(date)
{
    var change_date_parts = date.split('/');
    var change_date = change_date_parts[2]+"/"+change_date_parts[1]+"/"+change_date_parts[0];

    return change_date;
}

function createBillingInterface(billing_interface, billings, paid_billings)
{
    $("#transaction_create_billing").append(billing_interface);
    insert_billing_table_row(billings, paid_billings);
}

function insert_billing_table_row(billings, paid_billings)
{
    if(billings || paid_billings)
    {
        if ($.fn.DataTable.isDataTable('#datatable-paid')) {
            $('#datatable-paid').DataTable().destroy();
        }

        $("#billing_body").empty();
    }
    if(billings)
    {
        for(var h = 0; h < billings.length; h++)
        {//billings/edit_bill/'+billings[h]["id"]+'
            var latest_date_format = changeDateFormat(billings[h]["invoice_date"]);
            $b="";
            $b += '<tr>';
            $b += '<td style="text-align: right"></td>';
            $b += '<td>'+billings[h]["company_name"]+'</td>';
            $b += '<td style="text-align: center"><span style="display:none">'+ formatDateFunc(new Date(latest_date_format)) + '</span>'+billings[h]["invoice_date"]+'</td>';
            $b += '<td><a href="javascript:void(0)" onclick="editBilling('+billings[h]["id"]+')" class="pointer mb-sm mt-sm mr-sm  ">'+billings[h]["invoice_no"]+'</a></td>';
            $b += '<td>'+billings[h]["currency_name"]+'</td>';
            $b += '<td class="text-right">'+addCommas(billings[h]["amount"])+'</td>';
            $b += '<td class="text-right">'+addCommas(billings[h]["outstanding"])+'</td>';
            $b += '<td></td>';
            $b += '<td>Unpaid</td>';
            $b += '<td><a data-toggle="modal" data-code="'+billings[h]["company_code"]+'" onclick="exportPDF('+billings[h]["id"]+')" class="btn btn-primary p_code?>ointer mb-sm mr-sm">PDF</a><a style="" data-toggle="modal" data-code="'+billings[h]["company_code"]+'" onclick="deleteBilling('+billings[h]["id"]+')" class="btn btn-primary pointer mb-sm">Delete</a></td>';
            $b += '</tr>';

            $("#billing_body").append($b);
        }
    }

    if(paid_billings)
    {
        for(var g = 0; g < paid_billings.length; g++)
        {
            var latest_date_format = changeDateFormat(paid_billings[g]["invoice_date"]);
            $c="";
            $c += '<tr>';
            $c += '<td style="text-align: right"></td>';
            $c += '<td>'+paid_billings[g]["company_name"]+'</td>';
            $c += '<td style="text-align: center"><span style="display:none">'+ formatDateFunc(new Date(latest_date_format)) + '</span>'+paid_billings[g]["invoice_date"]+'</td>';
            $c += '<td><a href="billings/review_paid_bill/'+paid_billings[g]["id"]+'" class="pointer mb-sm mt-sm mr-sm  ">'+paid_billings[g]["invoice_no"]+'</a></td>';
            $c += '<td>'+paid_billings[g]["currency_name"]+'</td>';
            $c += '<td style="text-align: right">'+addCommas(paid_billings[g]["amount"])+'</td>';
            $c += '<td style="text-align: right">'+addCommas(paid_billings[g]["outstanding"])+'</td>';
            $c += '<td>'+((paid_billings[g]["receipt_no"] != null)?paid_billings[g]["receipt_no"]:"")+'</td>';
            $c += '<td>Paid</td>';
            $c += '<td><a data-toggle="modal" data-code="'+paid_billings[g]["company_code"]+'" onclick="exportPDF('+paid_billings[g]["id"]+')" class="btn btn-primary pointer mb-sm mr-sm">PDF</a></td>';
            $c += '</tr>';

            $("#billing_body").append($c);
        }
    }

    $(document).ready(function() {
        var table1 = $('#datatable-paid').DataTable({
            destroy: true,
            "columnDefs": [ {
                "searchable": false,
                "orderable": false,
                'type': 'num', 
                'targets': 0
            },
            { type: 'sort-numbers-ignore-text', targets: 3 } ],
            "order": [[3, 'desc']]
        });
        table1.on( 'order.dt search.dt', function () {
            table1.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();
    });
    // $('#datatable-paid').DataTable({
    //     destroy: true
    // });
}

$(document).on("change", "#change_condition", function() {
    var condition_name = $(this).find(":selected").text();
    document_table.$(".transaction_document_row").filter(function() {
        if(condition_name == "Pre-Incorporation")
        {
            if($.trim($(this).text()) == "Shares allotment form" 
                || $.trim($(this).text()) == "F24 - Return of allotment of shares" 
                || $.trim($(this).text()) == "Form 44" 
                || $.trim($(this).text()) == "Form 45" 
                || $.trim($(this).text()) == "Form 45B" 
                || $.trim($(this).text()) == "Form 49" 
                || $.trim($(this).text()) == "Information and verification of clients")
            {
                $(this).parent().parent().find(".document_master_id option:contains('V2')").prop("selected", true);
            }
            else
            {
                $(this).parent().parent().find(".document_master_id option:contains('Not Generated')").prop("selected", true);
            }
        }
        else if(condition_name == "Post-Incorporation")
        {
            if($.trim($(this).text()) == "First Director Resolutions (Many)" 
                || $.trim($(this).text()) == "First Director Resolutions (One)" 
                || $.trim($(this).text()) == "Allotment-Share Cert" 
                || $.trim($(this).text()) == "CONSTITUTION" 
                || $.trim($(this).text()) == "Online Filing to ACRA" 
                || $.trim($(this).text()) == "Notice of Incorporation")
            {
                if($.trim($(this).text()) == "Allotment-Share Cert")
                {
                    $(this).parent().parent().find(".document_master_id option:contains('V1')").prop("selected", true);
                }
                else
                {
                    $(this).parent().parent().find(".document_master_id option:contains('V2')").prop("selected", true);
                }

            }
            else
            {
                $(this).parent().parent().find(".document_master_id option:contains('Not Generated')").prop("selected", true);
            }
        }
        else
        {
            if($.trim($(this).text()) == "Online Filing to ACRA" 
                || $.trim($(this).text()) == "Notice of Incorporation")
            {
                $(this).parent().parent().find(".document_master_id option:contains('Not Generated')").prop("selected", true);
            }
            else
            {
                $(this).parent().parent().find(".document_master_id option:contains('V1')").prop("selected", true);
            }
        }

    });
});

$(document).on("change", "#change_version", function() {
    var version_name = $(this).find(":selected").text();
    document_table.$(".document_master_id option").filter(function() {
        //may want to use $.trim in here
        if($(this).text() == version_name)
        {
            return true;
        }
        else if($(this).text() == "Not Generated")
        {
            return true;
        }
    }).prop('selected', true);
});

function documentCategoryList(document_category_list)
{
    $("#change_condition").val(0);
    $("#change_version .master_ver_dropdown_list").remove();
    if(document_category_list != undefined)
    {
        for(var f = 0; f < document_category_list.length; f++)
        {
            if(document_category_list[f]['document_category_name'] == "V1")
            {
                var selected_document_category_value = true;
            }
            else
            {
                var selected_document_category_value = false;
            }

            $("#change_version").append($('<option>', {
                value: document_category_list[f]['id'],
                text: document_category_list[f]['document_category_name'],
                class: "master_ver_dropdown_list",
                selected: selected_document_category_value
            }));
        }
    }
}

function documentInterface(transaction_document)
{
    $('#datatable-document').DataTable().destroy();
    $(".transaction_document_table").remove();
    var got_v1 = false;
    if(transaction_document)
    {
        var document_name = "";
        for(var f = 0; f < transaction_document.length; f++)
        {
            var q = f + 1;
            if(transaction_document[f]['transaction_task_id'] == 30)
            {
                if(transaction_document[f]['firm_name'] != null)
                {
                    var firm_name = "(" + transaction_document[f]['firm_name'] + ")";
                }
                else
                {
                    var firm_name = "";
                }
            }
            else
            {
                var firm_name = "";
            }

            if(document_name != transaction_document[f]['document_name'])
            {
                var a = "";
                a += "<tr class='transaction_document_table'>";
                //a += '<td>'+q+'<input type="hidden" name="document_master_id[]" value="'+transaction_document[f]['document_master_id']+'"></td>';
                a += '<td style="width: 50px !important;"></td>';
                a += '<td><span style="height:45px;font-weight:bold;" class="edit_currency amber transaction_document_row">'+transaction_document[f]['document_name'] + ' ' + firm_name +'</span></td>';
                a += '<td style="width: 200px !important;"><select class="form-control document_master_id" name="document_master_id[]" id="document_master_id'+transaction_document[f]['id']+'"><option value="null">Not Generated</option></select></td>';
                a += "</tr>";

                $("#document_body").append(a);
                if(transaction_document[f]['document_category_name'] == "V1")
                {
                    if(transaction_document[f]['transaction_task_id'] == 1)
                    {
                        if(transaction_document[f]['document_name'] != "F24 - Return of allotment of shares")
                        {
                            var selected_value = true;
                            got_v1 = true;
                        }
                    }
                    else
                    {
                        var selected_value = true;
                        got_v1 = true;
                    }
                }
                else
                {
                    var selected_value = false;
                    got_v1 = false;
                }

                $("#document_master_id"+transaction_document[f]['id']+"").append($('<option>', {
                    value: transaction_document[f]['document_master_id'],
                    text: transaction_document[f]['document_category_name'],
                    selected: selected_value
                }));

                document_name = transaction_document[f]['document_name'];
            }
            else
            {
                if(!got_v1 && transaction_document[f]['document_category_name'] == "V2")
                {
                    var selected_value = true;
                }
                else
                {
                    var selected_value = false;
                }

                $("#document_master_id"+transaction_document[f]['id']+"").append($('<option>', {
                    value: transaction_document[f]['document_master_id'],
                    text: transaction_document[f]['document_category_name'],
                    selected: selected_value
                }));
            }
        }
        document_table = $('#datatable-document').DataTable({
            columns: [
                { width: '5%' },
                { width: '80%' },
                { width: '15%' }
            ]
        });

        document_table.on( 'order.dt search.dt', function () {
            document_table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();
    }
}

$(document).on('click',"#submitGenerateDocument",function(e){
    if($('.transaction_task option:selected').val() == 29 || $('.transaction_task option:selected').val() == 30)
    {
        bootbox.confirm({
            message: "Do you want to print Pre-printed Letterhead document?",
            buttons: {
                confirm: {
                    label: 'Yes'
                    //className: 'btn-success'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                $('#loadingWizardMessage').show();
                generateDocument(result);
            }
        });
    }
    else
    {
        $('#loadingWizardMessage').show();
        generateDocument(null);
    }

});

function generateDocument(result)
{
    $.ajax({
      url: "transaction_document/generate_document",
      type: "POST",
      data: document_table.$('input, select').serialize()+ '&company_code=' + transaction_company_code + '&transaction_task_name=' + encodeURIComponent($('.transaction_task option:selected').text()) + '&transaction_master_id=' + $("#transaction_trans #transaction_master_id").val() + '&pre-printed_letterhead=' + result,
      dataType: 'json',//document_table.$('input, select').serialize() //$('#transaction_document select').serialize()
      success: function (response,data) {
        $('#loadingWizardMessage').hide();
            window.open(
              response.zip_link,
              '_blank' // <- This is what makes it open in a new window.
            );
            //setTimeout(function(){ deletePDF(); }, 20000);
        }
    })
}

$("#refresh_document_list").click(function(){
    $.ajax({
        url: "transaction/get_upload_document_list",
        async: false,
        type: "POST",
        data: {"transaction_master_id":$("#transaction_trans #transaction_master_id").val()},
        dataType: 'json',
        success: function (response) {
            create_upload_document_list(response[0]["all_documents"]);     
        }
    });
});

function create_upload_document_list(all_documents){
    $(".each_all_documents").remove();
    if(0 < all_documents.length)
    {
        for(var i = 0; i < all_documents.length; i++)
        {
            if(all_documents[i]["type"] != null)
            {
                var document_link = all_documents[i]["id"]+'/'+all_documents[i]["type"];
            }
            else
            {
                var document_link = all_documents[i]["id"];
            }

            var created_at_date = new Date(all_documents[i]["created_at"]);

            var monthNames = [
                "January", "February", "March",
                "April", "May", "June", "July",
                "August", "September", "October",
                "November", "December"
            ];

            var day = created_at_date.getDate();
            if(day.toString().length==1)    
            {
                day="0"+day;
            }
                
            var monthIndex = created_at_date.getMonth();
            var year = created_at_date.getFullYear();

            var c = ""; 
            c += '<tr class="each_all_documents">';
            c += '<td style="text-align: center">'+all_documents[i]["company_name"]+'<input class="hidden" name="each_document_id" value="'+all_documents[i]["id"]+'"></td>';
            c += '<td style="text-align: center">'+all_documents[i]["transaction_code"]+'</td>';
            c += '<td style="text-align: center">'+all_documents[i]["triggered_by_name"]+'</td>';
            c += '<td style="text-align: center">'+day + ' ' + monthNames[monthIndex] + ' ' + year+'</td>';
            c += '<td style="text-align: center">'+all_documents[i]["created_by_first_name"] +" "+ all_documents[i]["created_by_last_name"]+'</td>';

            if(all_documents[i]["received_on"] == "")
            {
                c += '<td><a id="add_all_document_file" href="documents/add_pending_document_file/'+document_link+'" class="btn btn-primary add_all_document_file" target="_blank">Update</a></td>';
            }
            else
            {
                c += '<td><a href="documents/edit_pending_document_file/'+document_link+'" class="pointer mb-sm mt-sm mr-sm" target="_blank">'+all_documents[i]["received_on"]+'</td>';
            }
            c += '<td style="text-align: center">'+((all_documents[i]["received_by_first_name"] != null)?all_documents[i]["received_by_first_name"]:"") +" "+ ((all_documents[i]["received_by_last_name"] != null)?all_documents[i]["received_by_last_name"]:"")+'</td>';
            c += '</tr>';
            
            $("#all_document_body").append(c);
        }
    }
}

function deletePDF()
{
    $.ajax({
      url: "transaction_document/delete_document",
      async: false,
      type: "POST",
      //data: {"path":link},
      dataType: 'json',
      success: function (response,data) {
      }
    });
}

$(function() { 
    // for bootstrap 3 use 'shown.bs.tab', for bootstrap 2 use 'shown' in the next line
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        // save the latest tab; use cookies if you like 'em better:
        localStorage.setItem('lastTab', $(this).attr('href'));
    });    
});

function formatDateFunc(date) {
  var monthNames = [
    "01", "02", "03",
    "04", "05", "06", "07",
    "08", "09", "10",
    "11", "12"
  ];

  var day = date.getDate();
  if(day.toString().length==1)  
  {
    day="0"+day;
  }
    
  var monthIndex = date.getMonth();
  var year = date.getFullYear();

  return day + '/' + monthNames[monthIndex] + '/' + year;
}

$('.cancel_by_user').on("click",function(e){
    e.preventDefault();
    bootbox.prompt({
        title: "Reason Cancellation Transaction",
        inputType: 'textarea',
        callback: function (result) {
            if(result != null)
            {
                if(result == "")
                {
                    toastr.error("Please enter the cancellation transaction reason.", "Error");
                    return false; 
                }
                else
                {
                    $('#loadingWizardMessage').show();
                    $.ajax({
                        url: "transaction/cancel_transaction_by_user",
                        type: "POST",
                        data: {"cancel_reason": result, "transaction_code": $('#transaction_code').val(), "transaction_task_id": $('#transaction_task').val(), "registration_no": $('#uen').val()},
                        dataType: 'json',
                        success: function (response) {
                            $('#loadingWizardMessage').hide();
                            toastr.success(response.message, response.title);
                            window.location.href = "transaction/";
                        }
                    });
                }
            }
            
        }
    });
})

$('.tran_status').on("click",function(e){
    e.preventDefault();
    if(transaction_task_id == null)
    {
        var task_id = $('.transaction_task :selected').val();
    }
    else
    {
        var task_id = transaction_task_id;
    }
    if(task_id == 29)
    {
        if($(".tran_status").val() == "4")
        {
            $(".reason_cancellation_textfield").show();
        }
        else
        {
            $(".reason_cancellation_textfield").hide();
            $(".cancellation_reason").val("");
        }
    }
    else
    {
        if($(".tran_status").val() == "3")
        {
            $(".reason_cancellation_textfield").show();
        }
        else
        {
            $(".reason_cancellation_textfield").hide();
            $(".cancellation_reason").val("");
        }
    }
});

$('.date_of_follow_up').datepicker({ 
     dateFormat:'dd/mm/yyyy',
 }).datepicker('setStartDate', "01/01/1920")
.datepicker("setDate", new Date());


$('#datetimepicker5').datetimepicker({
    format: 'LT'
});

$('.next_follow_up_date').datepicker({ 
     dateFormat:'dd/mm/yyyy',
 }).datepicker('setStartDate', "01/01/1920")
.datepicker("setDate", new Date());


$('#datetimepicker6').datetimepicker({
    format: 'LT'
});
$('.create_follow_up').on("click",function(e){
    e.preventDefault();
    $(".follow_up_form_section").show();
    $(".create_follow_up_form_section").hide();
});

$('.follow_up_outcome').on("click",function(e){
    e.preventDefault();
    if($(this).val() == "1")
    {
        $(".action_part").hide();
        $(".follow_up_action").val("0");
        $(".next_follow_up_date").datepicker("setDate", new Date());
        $(".next_follow_up_time").val("");
        $(".follow_up_action").prop("disabled", true);
        $(".next_follow_up_date").prop("disabled", true);
        $(".next_follow_up_time").prop("disabled", true);
    }
    else if($(this).val() == "2")
    {
        $(".action_part").show();
        $(".follow_up_action").prop("disabled", false);
        $(".next_follow_up_date").prop("disabled", false);
        $(".next_follow_up_time").prop("disabled", false);
    }
    
});

$('#save_lodgement_info').click(function(e){
    e.preventDefault();
    $('#loadingWizardMessage').show();
    $.ajax({
        url: "transaction/add_lodgement_info",
        type: "POST",
        data: $('#lodgement_info_form input, #lodgement_info_form select, #lodgement_info_form textarea').serialize() + '&company_code=' + $('#company_code').val() + '&transaction_master_id=' + $("#transaction_trans #transaction_master_id").val() + '&transaction_task=' + $(".transaction_task_section #transaction_task").val() + '&transaction_task_name=' + $('.transaction_task option:selected').text(),
        dataType: 'json',
        success: function (response) {
            $('#loadingWizardMessage').hide();
            if (response.Status === 1) {
                toastr.success(response.message, response.title);
            }
        }
    })
});

$('#save_follow_up').click(function(e){
    e.preventDefault();
    $('#loadingWizardMessage').show();
    $.ajax({
        url: "transaction/add_follow_up_info",
        type: "POST",
        data: $('#follow_up_form_section input, #follow_up_form_section select, #follow_up_form_section textarea').serialize() + '&company_code=' + $('#company_code').val() + '&transaction_master_id=' + $("#transaction_trans #transaction_master_id").val() + '&transaction_task_name=' + $('.transaction_task option:selected').text(),
        dataType: 'json',
        success: function (response) {
            $('#loadingWizardMessage').hide();
            if (response.Status === 1) {
                toastr.success(response.message, response.title);
                follow_up_history_data = response.follow_up_history;
                follow_up_history_table(response.follow_up_history);
                $('.follow_up_history_id').val("");
                $('.date_of_follow_up').datepicker("setDate", new Date());
                $('.time_of_follow_up').val("");
                $('.follow_up_remark').val("");
                $('.follow_up_outcome').val("0");

                $(".action_part").hide();
                $(".follow_up_action").val("0");
                $(".next_follow_up_date").datepicker("setDate", new Date());
                $(".next_follow_up_time").val("");
                $(".follow_up_action").prop("disabled", true);
                $(".next_follow_up_date").prop("disabled", true);
                $(".next_follow_up_time").prop("disabled", true);

                $(".follow_up_form_section").hide();
                $(".create_follow_up_form_section").show();
            }
        }
    })
});

function follow_up_history_table(follow_up_history)
{   
    if(follow_up_history)
    {
        $(".each_follow_up_history").remove();

        for(var i = 0; i < follow_up_history.length; i++)
        {
            $b=""; 
            $b += '<tr class="each_follow_up_history">';
            $b += '<td class="hidden"><input type="text" class="form-control" name="each_follow_up_history_id" id="each_follow_up_history_id" value="'+follow_up_history[i]["id"]+'"/></td>';
            $b += '<td style="text-align: center"><a href="javascript:void(0)" class="edit_follow_up_history" data-id="'+follow_up_history[i]["id"]+'" data-follow_up_info="'+follow_up_history[i]+'" id="edit_follow_up_history">'+follow_up_history[i]["follow_up_id"]+'</a></td>';
            $b += '<td style="text-align: center">' + follow_up_history[i]["date_of_follow_up"] +" "+ follow_up_history[i]["time_of_follow_up"] +'</td>';
            $b += '<td style="text-align: center">' + follow_up_history[i]["next_follow_up_date"] +" "+ follow_up_history[i]["next_follow_up_time"] +'</td>';
            $b += '<td>'+follow_up_history[i]["first_name"]+'</td>';
            $b += '<td><button type="button" class="btn btn-primary delete_follow_up_history_button" onclick="delete_follow_up_history(this)">Delete</button></td>';
            $b += '</tr>';

            $("#follow_up_table").append($b);
        }
    }
}

$('.edit_follow_up_history').live("click",function(){
    
    var follow_up_history_id =  $(this).data("id");follow_up_history_data

    for(var i = 0; i < follow_up_history_data.length; i++)
    {
        if(follow_up_history_data[i]["id"] == follow_up_history_id)
        {
            $(".follow_up_form_section").show();
            $(".create_follow_up_form_section").hide();

            $(".follow_up_history_id").val(follow_up_history_data[i]["id"]);
            $(".date_of_follow_up").val(follow_up_history_data[i]["date_of_follow_up"]);
            $(".time_of_follow_up").val(follow_up_history_data[i]["time_of_follow_up"]);
            $(".follow_up_remark").val(follow_up_history_data[i]["follow_up_remark"]);
            $(".follow_up_outcome").val(follow_up_history_data[i]["follow_up_outcome_id"]);
            if(follow_up_history_data[i]["follow_up_outcome_id"] == "2")
            {
                $(".action_part").show();
                $(".follow_up_action").prop("disabled", false);
                $(".next_follow_up_date").prop("disabled", false);
                $(".next_follow_up_time").prop("disabled", false);

                $(".follow_up_action").val(follow_up_history_data[i]["follow_up_action_id"]);
                $(".next_follow_up_date").val(follow_up_history_data[i]["next_follow_up_date"]);
                $(".next_follow_up_time").val(follow_up_history_data[i]["next_follow_up_time"]);
            }
            else
            {
                $(".action_part").hide();
                $(".follow_up_action").val("0");
                $(".next_follow_up_date").datepicker("setDate", new Date());
                $(".next_follow_up_time").val("");
                $(".follow_up_action").prop("disabled", true);
                $(".next_follow_up_date").prop("disabled", true);
                $(".next_follow_up_time").prop("disabled", true);
            }
            
        }
    }
});

function delete_follow_up_history(element){
    var tr = jQuery(element).parent().parent();
    var each_follow_up_history_id = tr.find('input[name="each_follow_up_history_id"]').val();
    if(each_follow_up_history_id != undefined)
    {
        $('#loadingWizardMessage').show();
        $.ajax({
            url: "transaction/delete_follow_up_history",
            type: "POST",
            data: {"follow_up_history_id": each_follow_up_history_id, "company_code": $('#company_code').val(), 'transaction_master_id': $("#transaction_trans #transaction_master_id").val(), 'transaction_task_name': $('.transaction_task option:selected').text()},
            dataType: 'json',
            success: function (response) {
                $('#loadingWizardMessage').hide();
                toastr.success(response.message, response.title);
                follow_up_history_data = response.follow_up_history;
                follow_up_history_table(response.follow_up_history);
            }
        });
    }
    tr.remove();
}

function address_format (postal_code, street_name, building_name, unit_no1, unit_no2, foreign_add_1 = null, foreign_add_2 = null, foreign_add_3 = null)
{
    if(postal_code != "" && street_name != "")
    {
        var units = "", buildingName = "";

        if(unit_no1 != "" || unit_no2 != "")
        {
            units = ', #'+unit_no1 + " - " + unit_no2;
        }
        else if(unit_no1 != "")
        {
            units = unit_no1+' ';
        }
        else if(unit_no2 != "")
        {
            units = unit_no2+' ';
        }
        else
        {
            units = '';
        }
        if(building_name != "")
        {
            if(units == '')
            {
                buildingName = ', ' + building_name;
            }
            else
            {
                buildingName = ' ' + building_name;
            }
        }
        else
        {
            buildingName = '';
        }
        var address = street_name+units+buildingName+', SINGAPORE '+postal_code;
    }
    else if(foreign_add_1 != null )
    {
        var address = foreign_add_1;

        if(foreign_add_2 != "")
        {
            address = address + ', ' + foreign_add_2;
        }

        if(foreign_add_3 != "")
        {
            address = address + ', ' + foreign_add_3;
        }
    }

    return address;
}

