var array_for_incop_new_company, registered_address_info, transaction_client, transaction_task_id, 
    transaction_id, transaction_client_type_id, transaction_client_name, transaction_client_officers, transaction_filing, coll, transaction_client_signing_info, 
    transaction_contact_person_info, client_contact_info_email, client_contact_info_phone, client_selected_reminder, 
    transaction_document, document_table, array_for_appoint_director, transaction_appoint_new_director, client_unit,
    transaction_change_reg_ofis, array_for_change_company_name, transaction_change_company_name, company_class, allotmentPeople,
    previous_class_value = null, edit_transaction_share_transfer, activity_status, transaction_agm_ar, solvency_status,
    small_company, audited_financial_statement, filing_info, transaction_agm_ar_director_fee, transaction_agm_ar_dividend, transaction_agm_ar_amount_due, 
    transaction_agm_ar_director_retire, transaction_agm_ar_reappoint_auditor, total_number_of_share = 0, first_agm,
    agm_share_transfer, consent_for_shorter_notice, bool_dispense_agm = true, epc_status, bank_name, transaction_opening_bank_acc,
    manner_of_operation, previous_bank_value = null, transaction_incorporation_subsidiary, transaction_issue_director_fee, transaction_dividends,
    transaction_service_proposal, transaction_engagement_letter, company_code_selected;

var pathArray = location.href.split( '/' );
var protocol = pathArray[0];
var host = pathArray[2];
var folder = pathArray[3];
var url = protocol + '//' + host + '/' + folder + '/';

var tcm1 = new Chairman();
function ajaxCall() {
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

function Chairman() {
    var base_url = window.location.origin;  
    var call = new ajaxCall();

    this.getAllChairman = function(selected_edit_chairman) {
        var url = base_url+"/"+folder+"/"+'companytype/getAllChairman';
        //console.log(url);
        var method = "post";
        var data = {"company_code":  $("#agm_ar_form #company_code").val()};
        $('.transaction_agm_chairman').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            //console.log(data);
            $('.transaction_agm_chairman').find("option:eq(0)").html("Select Chairman");
            //console.log(data);
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
                //$(".nationality").prop("disabled",false);
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
    transaction_client_name = transaction_master[0]['client_name'];
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
            //console.log(data);
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
                        //$("#uen").prop("readonly", true);
                        $(".client_name_section").show();
                        if(transaction_client_type_id == 1)
                        {
                            $(".dropdown_client_name").show();
                            $(".input_client_name").hide();
                        }
                        else
                        {
                            $(".dropdown_client_name").hide();
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

$(document).on("click","#client_type",function(element) {
    get_client_name_interface($(this).val());
});

function get_client_name_interface(transaction_client_type_id = null)
{
    //console.log($(this).parent().parent().parent().parent('form').find(".sp_textarea"));
    if(transaction_client_type_id == 1)
    {
        $(".client_name_section").show();
        $(".dropdown_client_name").show();
        $(".input_client_name").hide();
        $(".dropdown_client_name").prop("disabled", false);
        $(".input_client_name").prop("disabled", true);
        $(".loadingWizardMessage").show();
        $.ajax({
            type: "GET",
            url: "transaction/get_all_client",
            dataType: "json",
            success: function(data){
                //console.log(data);
                $(".loadingWizardMessage").hide();
                if(data.tp == 1){
                    $("select#client_name option").remove();
                    var select_option = $('<option />');
                    select_option.attr('value', '0').text("Select Client Name");
                    $("select#client_name").append(select_option);

                    $.each(data['result'], function(key, val) {
                        var option = $('<option />');
                        option.attr('value', key).text(val);
                        if(transaction_client_name != null && val == transaction_client_name)
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
        console.log(transaction_client_name);
        $(".client_name_section").show();
        $(".dropdown_client_name").hide();
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
            //console.log(data);
            $(".loadingWizardMessage").hide();
            if(data.tp == 1){
                $("#client_name option").remove();
                var select_option = $('<option />');
                select_option.attr('value', '0').text("Select Client Name");
                $("#client_name").append(select_option);

                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    if(transaction_client_name != null && val.replace('(Potential Client)','') == transaction_client_name)
                    {
                        option.attr('selected', 'selected');
                        $("#client_name").prop("disabled", true);
                        //$("#uen").prop("readonly", true);
                        //$(".client_name_section").show();
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
		type: "GET",
		url: "transaction/get_transaction_task",
		dataType: "json",
		success: function(data){
			//console.log(data);
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
                        if(transaction_task_id == 1)
                        {
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
                            getIssueDividendInterface();
                        }
                        else if(transaction_task_id == 9)
                        {
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

                            $(".uen_section").hide();
                            $(".client_section").show();
                            //$(".client_type").prop("disabled", false);
                        }
                        else if(transaction_task_id == 30)
                        {
                            get_el_client_name_interface();
                            getEngagementLetterInterface();
                            $(".uen_section").hide();
                            $(".client_name_section").show();
                            //$(".dropdown_client_name").prop("disabled", false);
                            $(".dropdown_client_name").show();
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
    //console.log($(this).val());
    if($(this).val() == 0)
    {
        $("#transaction_data").children().remove();
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
        $(".uen_section").show();
        $(".client_section").hide();
        $(".client_name_section").hide();
        $(".client_type").prop("disabled", true);
        $(".dropdown_client_name").prop("disabled", true);
        $(".input_client_name").prop("disabled", true);
        //getAppointNewDirectorInterface();
        
    }
    else if($(this).val() == 3)
    {
        $(".uen_section").show();
        $(".client_section").hide();
        $(".client_name_section").hide();
        $(".client_type").prop("disabled", true);
        $(".dropdown_client_name").prop("disabled", true);
        $(".input_client_name").prop("disabled", true);
        //getResignDirectorInterface();
        
    }
    else if($(this).val() == 4)
    {
        $(".uen_section").show();
        $(".client_section").hide();
        $(".client_name_section").hide();
        $(".client_type").prop("disabled", true);
        $(".dropdown_client_name").prop("disabled", true);
        $(".input_client_name").prop("disabled", true);
        //getChangeRegOfisInterface();
        
    }
    else if($(this).val() == 5)
    {
        $(".uen_section").show();
        $(".client_section").hide();
        $(".client_name_section").hide();
        $(".client_type").prop("disabled", true);
        $(".dropdown_client_name").prop("disabled", true);
        $(".input_client_name").prop("disabled", true);
        //getChangeBizActivityInterface();
        
    }
    else if($(this).val() == 6)
    {
        $(".uen_section").show();
        $(".client_section").hide();
        $(".client_name_section").hide();
        $(".client_type").prop("disabled", true);
        $(".dropdown_client_name").prop("disabled", true);
        $(".input_client_name").prop("disabled", true);
        //getChangeFYEInterface();
        
    }
    else if($(this).val() == 7)
    {
        $(".uen_section").show();
        $(".client_section").hide();
        $(".client_name_section").hide();
        $(".client_type").prop("disabled", true);
        $(".dropdown_client_name").prop("disabled", true);
        $(".input_client_name").prop("disabled", true);
        //getAppointNewAuditorInterface();
    }
    else if($(this).val() == 8)
    {
        $(".uen_section").show();
        $(".client_section").hide();
        $(".client_name_section").hide();
        $(".client_type").prop("disabled", true);
        $(".dropdown_client_name").prop("disabled", true);
        $(".input_client_name").prop("disabled", true);
    }
    else if($(this).val() == 9)
    {
        $(".uen_section").show();
        $(".client_section").hide();
        $(".client_name_section").hide();
        $(".client_type").prop("disabled", true);
        $(".dropdown_client_name").prop("disabled", true);
        $(".input_client_name").prop("disabled", true);
    }
    else if($(this).val() == 10)
    {
        $(".uen_section").show();
        $(".client_section").hide();
        $(".client_name_section").hide();
        $(".client_type").prop("disabled", true);
        $(".dropdown_client_name").prop("disabled", true);
        $(".input_client_name").prop("disabled", true);
        //getShareTransferInterface();
        
    }
    else if($(this).val() == 11)
    {
        $(".uen_section").show();
        $(".client_section").hide();
        $(".client_name_section").hide();
        $(".client_type").prop("disabled", true);
        $(".dropdown_client_name").prop("disabled", true);
        $(".input_client_name").prop("disabled", true);
        //getShareAllotmentInterface();
        
    }
    else if($(this).val() == 12)
    {
        $(".uen_section").show();
        $(".client_section").hide();
        $(".client_name_section").hide();
        $(".client_type").prop("disabled", true);
        $(".dropdown_client_name").prop("disabled", true);
        $(".input_client_name").prop("disabled", true);
        //getChangeCompanyNameInterface();
        
    }
    else if($(this).val() == 15)
    {
        $(".uen_section").show();
        $(".client_section").hide();
        $(".client_name_section").hide();
        $(".client_type").prop("disabled", true);
        $(".dropdown_client_name").prop("disabled", true);
        $(".input_client_name").prop("disabled", true);
        //getChangeAgmArInterface();
        
    }
    else if($(this).val() == 16)
    {
        $(".uen_section").show();
        $(".client_section").hide();
        $(".client_name_section").hide();
        $(".client_type").prop("disabled", true);
        $(".dropdown_client_name").prop("disabled", true);
        $(".input_client_name").prop("disabled", true);
        
    }
    else if($(this).val() == 20)
    {
        $(".uen_section").show();
        $(".client_section").hide();
        $(".client_name_section").hide();
        $(".client_type").prop("disabled", true);
        $(".dropdown_client_name").prop("disabled", true);
        $(".input_client_name").prop("disabled", true);
        
    }
    else if($(this).val() == 24)
    {
        $(".uen_section").show();
        $(".client_section").hide();
        $(".client_name_section").hide();
        $(".client_type").prop("disabled", true);
        $(".dropdown_client_name").prop("disabled", true);
        $(".input_client_name").prop("disabled", true);
    }
    else if($(this).val() == 26)
    {
        $(".uen_section").show();
        $(".client_section").hide();
        $(".client_name_section").hide();
        $(".client_type").prop("disabled", true);
        $(".dropdown_client_name").prop("disabled", true);
        $(".input_client_name").prop("disabled", true);
        
    }
    else if($(this).val() == 28)
    {
        getTakeOverOfSecretarialInterface();
        
    }
    else if($(this).val() == 29)
    {
        get_client_name_interface(transaction_client_type_id);
        $(".uen_section").hide();
        $(".client_section").show();
        $(".client_type").prop("disabled", false);

        //$(".client_name_section").show();
        
    }
    else if($(this).val() == 30)
    {
        get_el_client_name_interface();
        $(".uen_section").hide();
        $(".client_section").hide();
        $(".client_name_section").show();
        $(".dropdown_client_name").prop("disabled", false);
        $(".dropdown_client_name").show();
        $(".input_client_name").prop("disabled", true);
        $(".input_client_name").hide();
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
});

$(".client_name_section .input_client_name").change(function(){

    if($("#transaction_task").val() == 29)
    {
        $('#loadingWizardMessage').show();
        getServiceProposalInterface();
    }
});

$(".uen_section #uen").change(function(){

    //console.log($("#transaction_task").val());
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
});

function editShareTransfer(transaction_share_member_id)
{
    $.post("transaction/edit_share_transfer_page", {id: $("#transaction_trans #transaction_master_id").val(), company_code: transaction_company_code, transaction_task_id: transaction_task_id, registration_no: $(".uen_section #uen").val(), transaction_share_member_id: transaction_share_member_id}, function(data){
        console.log(JSON.parse(data));
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
        console.log(JSON.parse(data));
        array_for_agm_ar = JSON.parse(data);

        if(array_for_agm_ar['error'] == null)
        {
            transaction_agm_ar = array_for_agm_ar[0]["transaction_agm_ar"];
            transaction_agm_ar_director_fee = array_for_agm_ar[0]["transaction_agm_ar_director_fee"];
            transaction_agm_ar_dividend = array_for_agm_ar[0]["transaction_agm_ar_dividend"];
            transaction_agm_ar_amount_due = array_for_agm_ar[0]["transaction_agm_ar_amount_due"];
            transaction_agm_ar_director_retire = array_for_agm_ar[0]["transaction_agm_ar_director_retire"];
            transaction_agm_ar_reappoint_auditor = array_for_agm_ar[0]["transaction_agm_ar_reappoint_auditor"];
            activity_status = array_for_agm_ar[0]["activity_status"];
            epc_status = array_for_agm_ar[0]["epc_status"];
            epc_status_value = array_for_agm_ar[0]["epc_status_value"];
            solvency_status = array_for_agm_ar[0]["solvency_status"];
            small_company = array_for_agm_ar[0]["small_company"];
            audited_financial_statement = array_for_agm_ar[0]["audited_financial_statement"];
            filing_info = array_for_agm_ar[0]["filing_info"];
            first_agm = array_for_agm_ar[0]["first_agm"];
            agm_share_transfer = array_for_agm_ar[0]["agm_share_transfer"];
            consent_for_shorter_notice = array_for_agm_ar[0]["consent_for_shorter_notice"];

            $("#transaction_data").append(array_for_agm_ar['interface']);

            $(".fye_date").prop("disabled", true);

            $('.agm_date_info').datepicker({ 
                dateFormat:'dd MM yyyy',
            });

            $('.reso_date').datepicker({ 
                dateFormat:'dd MM yyyy',
            });

            

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

            if(epc_status)
            {
                for(var i = 0; i < epc_status.length; i++)
                {

                    var option = $('<option />');
                    option.attr('value', epc_status[i]['id']).text(epc_status[i]['is_epc_status']);
                    if(transaction_agm_ar)
                    {
                        if(transaction_agm_ar[0]["epc_status_id"] != null && epc_status[i]['id'] == transaction_agm_ar[0]["epc_status_id"])
                        {
                            option.attr('selected', 'selected');
                        }
                    }
                    else
                    {
                        if(epc_status_value != null && epc_status[i]['id'] == epc_status_value)
                        {
                            option.attr('selected', 'selected');
                        }
                    }

                    $("#epc_status").append(option);
                }
                
            }

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

                $(".reso_date").val(transaction_agm_ar[0]['reso_date']);

                $("#activity_status").val(transaction_agm_ar[0]['activity_status']);

                $("#solvency_status").val(transaction_agm_ar[0]['solvency_status']);

                $("#small_company").val(transaction_agm_ar[0]['small_company']);

                if(transaction_agm_ar[0]['small_company'] == 2) //No
                {
                    //$("#audited_fs").val("1");
                    $("#audited_fs").prop("disabled", true);
                    //$('#reappointment_auditor').prop('checked', true);
                }

                $("#audited_fs").val(transaction_agm_ar[0]['audited_fs']);
                //shareAgmArInterface(transaction_agm_ar);
            }
            else
            {   
                if(filing_info)
                {
                    $('.fye_date').datepicker({ 
                        dateFormat:'dd MM yyyy',
                    }).datepicker('setDate', filing_info[0]['year_end']);
                }

                if(new Date("8/31/2018") >= new Date($('.fye_date').val()))
                {
                    $(".dispense_agm_button").text("Dispense AGM");

                    $(".agm_date_info").val("");
                    $(".agm_date_info").prop('disabled', false);
                    bool_dispense_agm = true;
                    $("#bottom_interface").show();
                }
                else
                {
                    // if($("#upload_company_info .company_type").val() == 1)
                    // {
                        $(".dispense_agm_button").text("Hold AGM");
                        $(".agm_date_info").val("dispensed");
                        $(".agm_date_info").prop('disabled', true);
                        bool_dispense_agm = false;
                        $("#bottom_interface").hide();
                    // }
                    // else
                    // {

                    //     $("#agm_date").prop('disabled', false);
                    //     bool_dispense_agm = true;
                    // }
                }
            }

            

            if(transaction_agm_ar_director_fee)
            {
                if(transaction_agm_ar_director_fee[0]["id"] != null)
                {
                    $('#director_fee').prop('checked', true);
                    transactionAgmArDirectorFeeInterface(transaction_agm_ar_director_fee);
                }
            }

            if(transaction_agm_ar_dividend)
            {
                if(transaction_agm_ar_dividend[0]["id"] != null)
                {
                    $('#dividend').prop('checked', true);
                    transactionAgmArDividendInterface(transaction_agm_ar_dividend);
                }
            }

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
                            console.log($(".trans_company_code"));
                            $(".trans_company_code").val(response[0]["company_code"]);
                            $("#agm_ar_form #company_code").val(response[0]["company_code"]);
                            transaction_company_code = response[0]["company_code"];

                            $("#company_name").append(response[0]['company_name']);
                            $(".hidden_company_name").val(response[0]['company_name']);

                            if(transaction_agm_ar)
                            {
                                tcm1.getAllChairman(transaction_agm_ar[0]["chairman"]);
                            }
                            else
                            {   
                                tcm1.getAllChairman(null);
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

function transactionAgmArDirectorFeeInterface(transaction_agm_ar_director_fee)
{
    for(var i = 0; i < transaction_agm_ar_director_fee.length; i++)
    {
        $a="";
        $a += '<tr class="row_director_fee">';
        $a += '<td><input type="text" style="text-transform:uppercase;" name="director_fee_name[]" id="name" class="form-control" value="'+ transaction_agm_ar_director_fee[i]["director_fee_name"] +'" readonly/><input type="hidden" class="form-control" name="director_fee_identification_register_no[]" id="director_fee_identification_register_no" value="'+ transaction_agm_ar_director_fee[i]["director_fee_identification_no"] +'"/><div class="hidden"><input type="text" class="form-control" name="director_fee_client_officer_id[]" id="client_officer_id" value=""/></div><div class="hidden"><input type="text" class="form-control" name="director_fee_officer_id[]" id="officer_id" value="'+transaction_agm_ar_director_fee[i]["director_fee_officer_id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="director_fee_officer_field_type[]" id="officer_field_type" value="'+transaction_agm_ar_director_fee[i]["director_fee_officer_field_type"]+'"/></div></td>';
        $a += '<td><input type="text" style="text-transform:uppercase;" name="director_fee[]" id="director_fee numberdes" class="form-control" value="'+addCommas(transaction_agm_ar_director_fee[i]["director_fee"])+'"/></td>'
        $a += '</tr>';

        $("#director_fee_add").append($a);

    }

    $(".director_fee_div").show();
}

function transactionAgmArDividendInterface(transaction_agm_ar_dividend)
{
    for(var i = 0; i < transaction_agm_ar_dividend.length; i++)
    {
        $a="";
        $a += '<tr class="row_dividend" data-numberOfShare="'+transaction_agm_ar_dividend[i]["number_of_share"]+'">';
        $a += '<td><input type="text" style="text-transform:uppercase;" name="dividend_name[]" id="name" class="form-control" value="'+transaction_agm_ar_dividend[i]["dividend_name"] +'" readonly/><input type="hidden" class="form-control" name="dividend_identification_register_no[]" id="dividend_identification_register_no" value="'+ transaction_agm_ar_dividend[i]["dividend_identification_no"]+'"/><div class="hidden"><input type="text" class="form-control" name="dividend_client_officer_id[]" id="client_officer_id" value=""/></div><div class="hidden"><input type="text" class="form-control" name="dividend_officer_id[]" id="officer_id" value="'+transaction_agm_ar_dividend[i]["dividend_officer_id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="dividend_officer_field_type[]" id="officer_field_type" value="'+transaction_agm_ar_dividend[i]["dividend_officer_field_type"]+'"/></div></td>';
        $a += '<td><input type="text" style="text-transform:uppercase;" name="dividend[]" id="dividend_fee" class="form-control" value="'+addCommas(transaction_agm_ar_dividend[i]["dividend_fee"])+'" readonly/><input type="hidden" name="number_of_share[]" id="number_of_share" class="form-control" value="'+transaction_agm_ar_dividend[i]["number_of_share"]+'"/></td>'
        $a += '</tr>';

        $("#dividend_add").append($a);

        total_number_of_share = total_number_of_share + parseInt(transaction_agm_ar_dividend[i]["number_of_share"]);
    }
    $("#total_dividend").val(addCommas(transaction_agm_ar_dividend[0]["total_dividend_declared"]));
    $(".dividend_div").show();
}

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
            console.log($(event.target));
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
    for(var i = 0; i < transaction_agm_ar_reappoint_auditor.length; i++)
    {
        $a="";
        $a += '<tr class="row_reappointment_auditor">';
        $a += '<td><input type="text" style="text-transform:uppercase;" name="reappointment_auditor_name[]" id="name" class="form-control" value="'+ transaction_agm_ar_reappoint_auditor[i]["reappoint_auditor_name"] +'" readonly/><input type="hidden" class="form-control" name="reappointment_auditor_identification_register_no[]" id="reappointment_auditor_identification_register_no" value="'+ transaction_agm_ar_reappoint_auditor[i]["reappoint_auditor_identification_no"] +'"/><div class="hidden"><input type="text" class="form-control" name="reappointment_auditor_client_officer_id[]" id="client_officer_id" value=""/></div><div class="hidden"><input type="text" class="form-control" name="reappointment_auditor_officer_id[]" id="officer_id" value="'+transaction_agm_ar_reappoint_auditor[i]["reappoint_auditor_officer_id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="reappointment_auditor_officer_field_type[]" id="officer_field_type" value="'+transaction_agm_ar_reappoint_auditor[i]["reappoint_field_type"]+'"/></div></td>';
        $a += '</tr>';

        $("#reappointment_auditor_add").append($a);
    }
    $(".reappointment_auditor_div").show();
}

function getShareTransferInterface()
{
    $("#transaction_data .panel").remove();
    $.post("transaction/get_share_transfer_page", {id: transaction_id, company_code: transaction_company_code, transaction_task_id: transaction_task_id, registration_no: $(".uen_section #uen").val()}, function(data){
        console.log(JSON.parse(data));
        array_for_share_transfer = JSON.parse(data);

        if(array_for_share_transfer['error'] == null)
        {

            transaction_share_transfer = array_for_share_transfer[0]["transaction_share_transfer"];
            company_class = array_for_share_transfer[0]["company_class"];

            $("#transaction_data").append(array_for_share_transfer['interface']);

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
                //console.log(transaction_share_transfer);
                //shareTransferInterface(transaction_share_transfer);
            }
            // else
            // {
                $("DIV.transfer").remove();
                $("DIV.to").remove();
                $(document).ready(function() {
                    addFirstFrom();
                    $('#table_transfer_to').hide();
                    $('.to').hide();
                    $('#total_share_transfer_to').hide();
                });

                

            //}

            $("#class").change(function(){
                $('#loadingWizardMessage').show();
                if($('select[name="class"]').val() != previous_class_value && previous_class_value != null)
                {
                    $("DIV.transfer").remove();
                    $("DIV.to").remove();
                    addFirstFrom();

                    $('#table_transfer_to').hide();
                    $('.to').hide();
                    $('#total_share_transfer_to').hide();
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
                            console.log($(".trans_company_code"));
                            $(".trans_company_code").val(response[0]["company_code"]);
                            $("#share_transfer_form #company_code").val(response[0]["company_code"]);
                            transaction_company_code = response[0]["company_code"];

                            $("#company_name").append(response[0]['company_name']);
                            $(".hidden_company_name").val(response[0]['company_name']);

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
{   console.log(transaction_share_transfer);
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
            $b += '<td style="text-align:center">'+transaction_share_transfer[i]["to_new_certificate_no"]+'</td>';
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
                    option.attr('value', allotmentPeople[i]['officer_id']).text((allotmentPeople[i]["identification_no"]!=null ? allotmentPeople[i]["identification_no"] : (allotmentPeople[i]["register_no"]!=null ? allotmentPeople[i]["register_no"] : allotmentPeople[i]["registration_no"])));

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
        console.log(JSON.parse(data));
        array_for_share_allot = JSON.parse(data);

        if(array_for_share_allot['error'] == null)
        {
            transaction_share_allot = array_for_share_allot[0]["transaction_share_allotment"];
            company_class = array_for_share_allot[0]["company_class"];

            $("#transaction_data").append(array_for_share_allot['interface']);

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
                        //console.log(data);
                        //var field = $("textarea");
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
                            

                            /*$("#street_name1").attr("readonly", true);
                            $("#building_name1").attr("readonly", true);*/

                       
                          }
                          $( '#form_postal_code1' ).html('');
                          $( '#form_street_name1' ).html('');
                          //field.val(myString);
                        } else if (status.code == 603) {
                            $( '#form_postal_code1' ).html('<span class="help-block">*No Record Found</span>');
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
                else
                {
                    $("#street_name1").val("");
                    $("#building_name1").val("");

                    /*$("#street_name1").attr("readonly", false);
                    $("#building_name1").attr("readonly", false);*/
                }
            });

            // if(company_class)
            // {
            //     var shareClass;

            //     for(var i = 0; i < company_class.length; i++)
            //     {
            //         if(company_class[i]['sharetype'] == "Ordinary Share")
            //         {
            //             shareClass = company_class[i]['sharetype'] + " ( " + company_class[i]['currency'] + " )";
            //         }
            //         else if(company_class[i]['sharetype'] == "Others")
            //         {
            //             shareClass = company_class[i]['other_class'] + " ( " + company_class[i]['currency'] + " )";
            //         }
            //         var option = $('<option />');
            //         option.attr('data-otherclass', company_class[i]['other_class']);
            //         option.attr('data-currency', company_class[i]['currency']);
            //         option.attr('data-sharetype', company_class[i]['sharetype']);
            //         option.attr('value', company_class[i]['id']).text(shareClass);
            //         if(transaction_share_allot)
            //         {
            //             if(transaction_share_allot[0]["share_capital_id"] != null && company_class[i]['id'] == transaction_share_allot[0]["share_capital_id"])
            //             {
            //                 option.attr('selected', 'selected');

            //             }
            //         }

            //         $("#class").append(option);
            //     }
            //     $("#currency").val($("#class").find("option:selected").data('currency'));
            //     $("#client_member_share_capital_id").val($("#class").find("option:selected").val());
            // }

            // $("#class").on('change', function() {
            //     if($(this).find("option:selected").val() == 0)
            //     {
            //         $("#currency").val($(this).find("option:selected").data('currency'));
            //     }
            //     else
            //     {
            //         $("#client_member_share_capital_id").val($(this).find("option:selected").val());
            //         $("#currency").val($(this).find("option:selected").data('currency'));
            //     }
                
            // });

            if(transaction_share_allot)
            {
                if(array_for_share_allot[0]["transaction_share_allotment_date"])
                {
                    $("#tr_registered_edit input").attr('disabled', 'true');
                    $('input[name="registered_postal_code1"]').val(array_for_share_allot[0]["postal_code"]);
                    $('input[name="registered_street_name1"]').val(array_for_share_allot[0]["street_name"]);
                    $('input[name="registered_building_name1"]').val(array_for_share_allot[0]["building_name"]);
                    $('input[name="registered_unit_no1"]').val(array_for_share_allot[0]["unit_no1"]);
                    $('input[name="registered_unit_no2"]').val(array_for_share_allot[0]["unit_no2"]);

                    $(".director_meeting_date").val(array_for_share_allot[0]["transaction_share_allotment_date"][0]['director_meeting_date']);
                    $(".director_meeting_time").val(array_for_share_allot[0]["transaction_share_allotment_date"][0]['director_meeting_time']);
                    $(".member_meeting_date").val(array_for_share_allot[0]["transaction_share_allotment_date"][0]['member_meeting_date']);
                    $(".member_meeting_time").val(array_for_share_allot[0]["transaction_share_allotment_date"][0]['member_meeting_time'])
                    if(array_for_share_allot[0]["transaction_share_allotment_date"][0]['address_type'] == "Registered Office Address")
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
                    if(array_for_share_allot[0]["transaction_share_allotment_date"][0]['address_type'] == "Local")
                    {
                        $('input[name="postal_code1"]').val(array_for_share_allot[0]["transaction_share_allotment_date"][0]['postal_code1']);
                        $('input[name="street_name1"]').val(array_for_share_allot[0]["transaction_share_allotment_date"][0]['street_name1']);
                        $('input[name="building_name1"]').val(array_for_share_allot[0]["transaction_share_allotment_date"][0]['building_name1']);
                        $('input[name="unit_no1"]').val(array_for_share_allot[0]["transaction_share_allotment_date"][0]['unit_no1']);
                        $('input[name="unit_no2"]').val(array_for_share_allot[0]["transaction_share_allotment_date"][0]['unit_no2']);

                        $("#local_edit").prop("checked", true);
                        $("#tr_local_edit").show();
                        $("#tr_registered_edit").hide();
                        $("#tr_foreign_edit").hide();

                        $('input[name="foreign_address1"]').attr('disabled', 'true');
                        $('input[name="foreign_address2"]').attr('disabled', 'true');
                        $('input[name="foreign_address3"]').attr('disabled', 'true');
                    }
                    else if(array_for_share_allot[0]["transaction_share_allotment_date"][0]['address_type'] == "Foreign")
                    {
                        $('input[name="foreign_address1"]').val(array_for_share_allot[0]["transaction_share_allotment_date"][0]['foreign_address1']);
                        $('input[name="foreign_address2"]').val(array_for_share_allot[0]["transaction_share_allotment_date"][0]['foreign_address2']);
                        $('input[name="foreign_address3"]').val(array_for_share_allot[0]["transaction_share_allotment_date"][0]['foreign_address3']);
                        
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

                shareAllotmentInterface(transaction_share_allot);
                
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
            //console.log($('input[name="address_type"]').val());
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
                            console.log($(".trans_company_code"));
                            $(".trans_company_code").val(response[0]["company_code"]);
                            $("#share_allotment_form #company_code").val(response[0]["company_code"]);
                            transaction_company_code = response[0]["company_code"];

                            $("#company_name").append(response[0]['company_name']);
                            $(".hidden_company_name").val(response[0]['company_name']);

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
        console.log(JSON.parse(data));
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

            $('#director_signing').val(array_for_engagement_letter[0]["director_result_1"]);

            
        }
        //console.log($(".dropdown_client_name :selected").val());
        $(".trans_company_code").val($(".dropdown_client_name :selected").val());
        $("#engagement_letter_form .company_code").val($(".dropdown_client_name :selected").val());
        transaction_company_code = $(".dropdown_client_name :selected").val();

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
    });

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
    //console.log(tran_company_name);
    $.post("transaction/get_service_proposal_page", {id: transaction_id, company_code: transaction_company_code, transaction_task_id: transaction_task_id, company_name: tran_company_name}, function(data){
        console.log(JSON.parse(data));
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
                    //console.log($("#service_proposal_form .company_code"));
                    transaction_company_code = array_for_service_proposal[0]["company_code"];
                    //console.log(array_for_service_proposal[0]["client_detail"]);
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
                //console.log(array_for_service_proposal[0]["transaction_our_service_list"]);
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
                $b += '<td>'+array_for_service_proposal[0]["transaction_our_service_list"][$i]["service_name"]+'<input type="hidden" name="engagement_letter_list_id[]" value="'+array_for_service_proposal[0]["transaction_our_service_list"][$i]["engagement_letter_list_id"]+'"></td>';
                $b += '<td><select class="form-control" style="text-align:right;width: 100%;" name="currency[]" id="currency'+$i+'"><option value="0" >Select Currency</option></select></td>';
                $b += '<td><input class="form-control numberdes" type="text" name="fee[]" value="" id="fee'+$i+'" style="text-align:right;"></td>';
                $b += '<td><select class="form-control" style="text-align:right;width: 100%;" name="unit_pricing[]" id="unit_pricing'+$i+'"><option value="0" >Select Unit Pricing</option></select></td>';
                // $b += '<td><input class="form-control" type="text" name="unit_pricing[]" id="unit_pricing'+$i+'" value=""></td>';
                $b += '<td><select class="form-control" style="text-align:right;width: 100%;" name="servicing_firm[]" id="servicing_firm'+$i+'"><option value="0" >Select Servicing Firm</option></select></td>';
                $b += '</tr>';

                $("#body_service_proposal").append($b);

                $.each(currency, function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    // if(transaction_issue_dividend)
                    // {
                    //     if(transaction_issue_dividend[0]["currency"] != undefined && key == transaction_issue_dividend[0]["currency"])
                    //     {
                    //         option.attr('selected', 'selected');
                    //     }
                    // }
                    $("#currency"+$i).append(option);
                });

                $.each(unit_pricing_name, function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    // if(transaction_issue_dividend)
                    // {
                    //     if(transaction_issue_dividend[0]["currency"] != undefined && key == transaction_issue_dividend[0]["currency"])
                    //     {
                    //         option.attr('selected', 'selected');
                    //     }
                    // }
                    $("#unit_pricing"+$i).append(option);
                });

                $.each(get_all_firm_info, function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);

                    $("#servicing_firm"+$i).append(option);
                });

                if(array_for_service_proposal[0]["transaction_service_proposal_service_info"])
                {
                    for($b = 0; $b < array_for_service_proposal[0]["transaction_service_proposal_service_info"].length; $b++)
                    {
                        if(array_for_service_proposal[0]["transaction_service_proposal_service_info"][$b]["our_service_id"] == array_for_service_proposal[0]["transaction_our_service_list"][$i]["id"])
                        {
                            $("#selected_service_id"+$i).prop('checked', true);
                            $("#hidden_selected_service_id"+$i).val(array_for_service_proposal[0]["transaction_service_proposal_service_info"][$b]["our_service_id"]);
                            $("#currency"+$i).val(array_for_service_proposal[0]["transaction_service_proposal_service_info"][$b]["currency_id"]);
                            $("#fee"+$i).val(addCommas(array_for_service_proposal[0]["transaction_service_proposal_service_info"][$b]["fee"]));
                            $("#unit_pricing"+$i).val(array_for_service_proposal[0]["transaction_service_proposal_service_info"][$b]["unit_pricing"]);
                            $("#servicing_firm"+$i).val(array_for_service_proposal[0]["transaction_service_proposal_service_info"][$b]["servicing_firm"]);
                        }
                    }
                }
                else
                {
                    $("#currency"+$i).val(array_for_service_proposal[0]["transaction_our_service_list"][$i]["currency"]);
                    $("#fee"+$i).val(addCommas(array_for_service_proposal[0]["transaction_our_service_list"][$i]["amount"]));
                    $("#unit_pricing"+$i).val(array_for_service_proposal[0]["transaction_our_service_list"][$i]["unit_pricing"]);
                }
            }
        }

        $('.selected_service_id').change(function(e) {
            e.preventDefault();
            if($(this).is(":checked")) {
                $(this).parent().find(".hidden_selected_service_id").val($(this).val());
            }
            else
            {
                $(this).parent().find(".hidden_selected_service_id").val("");
            }
        });
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
                    //console.log(data);
                    //var field = $("textarea");
                    var myString = "";
                    
                    var status = data.Status;
                    /*myString += "Status.code: " + status.code + "\n";
                    myString += "Status.request: " + status.request + "\n";
                    myString += "Status.name: " + status.name + "\n";*/
                    
                    if (status.code == 200) {         
                      for (var i = 0; i < data.Placemark.length; i++) {
                        var placemark = data.Placemark[i];
                        var status = data.Status[i];
                        //console.log(placemark.AddressDetails.Country.Thoroughfare.ThoroughfareName);
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
            console.log($(this).parent().parent());
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
    });
}

function getChangeFYEInterface()
{
    $("#transaction_data .panel").remove();
    $.post("transaction/get_change_of_FYE_page", {id: transaction_id, company_code: transaction_company_code, transaction_task_id: transaction_task_id, registration_no: $(".uen_section #uen").val()}, function(data){
        console.log(JSON.parse(data));
        array_for_change_FYE = JSON.parse(data);

        if(array_for_change_FYE['error'] == null)
        {
            transaction_change_FYE = array_for_change_FYE[0]["transaction_change_FYE"];

            $("#transaction_data").append(array_for_change_FYE['interface']);

            $('.new_FYE').datepicker({ 
                dateFormat:'dd MM yyyy',
            });

            if(transaction_change_FYE)
            {
                changeFYEInterface(transaction_change_FYE);
            }
            else
            {
                if($(".uen_section #uen").val() != "")
                {
                    $.ajax({ //Upload common input
                      url: "transaction/check_filing_info",
                      type: "POST",
                      data: {"registration_no": $(".uen_section #uen").val()},
                      dataType: 'json',
                      async: false,
                      success: function (response,data) {
                        $('#loadingWizardMessage').hide();
                        console.log(response);
                            if(response)
                            {   
                                console.log($(".trans_company_code"));
                                $(".trans_company_code").val(response[0]["company_code"]);
                                $("#change_of_FYE_form #company_code").val(response[0]["company_code"]);
                                transaction_company_code = response[0]["company_code"];

                                $("#company_name").append(response[0]['company_name']);
                                $(".hidden_company_name").val(response[0]['company_name']);

                                $("#FYE").append(response[0]['year_end']);
                                $(".hidden_old_FYE").val(response[0]['year_end']);

                                $("#old_financial_year_period").append(response[0]['period']);
                                $(".hidden_old_period").val(response[0]['period']);

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
                    //console.log(financial_year_period_id);
                    
                    //console.log(data);
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
    }
    else
    {
        document.getElementById("new_activity2").value = $(".hidden_old_activity2").val();
        document.getElementById("new_activity2").readOnly = false;
    }
}
function getChangeBizActivityInterface()
{
    $("#transaction_data .panel").remove();
    $.post("transaction/get_change_of_biz_activity_page", {id: transaction_id, company_code: transaction_company_code, transaction_task_id: transaction_task_id, registration_no: $(".uen_section #uen").val()}, function(data){
        console.log(JSON.parse(data));
        array_for_change_biz_activity = JSON.parse(data);

        if(array_for_change_biz_activity['error'] == null)
        {
            transaction_change_biz_activity = array_for_change_biz_activity[0]["transaction_change_biz_activity"];

            $("#transaction_data").append(array_for_change_biz_activity['interface']);

            if(transaction_change_biz_activity)
            {
                changeBizActivityInterface(transaction_change_biz_activity);
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
                                console.log($(".trans_company_code"));
                                $(".trans_company_code").val(response[0]["company_code"]);
                                $("#change_of_biz_activity_form #company_code").val(response[0]["company_code"]);
                                transaction_company_code = response[0]["company_code"];

                                $("#company_name").append(response[0]['company_name']);
                                $(".hidden_company_name").val(response[0]['company_name']);

                                $("#activity_1").append(response[0]['activity1']);
                                $(".hidden_old_activity1").val(response[0]['activity1']);
                                $("#new_activity1").val(response[0]['activity1']);

                                $("#activity_2").append(response[0]['activity2']);
                                $(".hidden_old_activity2").val(response[0]['activity2']);
                                $("#new_activity2").val(response[0]['activity2']);

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

    if($(".hidden_total_balance").val() != undefined && $(".total_dividend_amount").val() != "")
    {
        var rowCount = $('#latest_director_table tr.row_member_section').length;
        $(".devidend_per_share").val((parseInt(total_devidend_amount) / parseInt($(".hidden_total_balance").val())).toFixed(4));
        
        for($y = 0; $y < rowCount; $y++)
        {
            // console.log($(".row_member_section").eq($y).find(".hidden_balance").val());
            // each_devidend_paid = parseInt(total_devidend_amount) / parseInt($(".hidden_total_balance").val()) * parseInt($(".row_member_section").eq($y).find(".hidden_balance").val());
            each_devidend_paid = total_devidend_amount / $(".hidden_total_balance").val() * $(".row_member_section").eq($y).find(".hidden_balance").val();
            
            // $(".row_member_section").eq($y).find(".devidend_paid").html(addCommas(each_devidend_paid.toFixed(2)));
            // $(".row_member_section").eq($y).find(".hidden_devidend_paid").val(each_devidend_paid.toFixed(2));

            total_devidend_paid += parseFloat(each_devidend_paid.toFixed(2));
            // console.log(total_devidend_paid.toFixed(2));

            arr_each_dividend_paid.push(each_devidend_paid);
        }

        // $(".total_devidend_paid").html(addCommas(total_devidend_paid.toFixed(2)));
        // $(".hidden_total_devidend_paid").val(total_devidend_paid.toFixed(2));

        var total_dividend_paid = parseFloat(total_devidend_paid.toFixed(2));
        var recheck_total = 0;

        console.log(total_devidend_amount, total_dividend_paid);
        // console.log(arr_each_dividend_paid);

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
    console.log(arr, diff_amount);

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
    // var temp_highest_lowest = 0.00;

    // console.log(arr, isPositive, point_five_scale);

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
        console.log(response);
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
                                console.log(total_balance);
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
        console.log(JSON.parse(data));
        array_for_issue_dividend = JSON.parse(data);

        if(array_for_issue_dividend['error'] == null)
        {
            transaction_issue_dividend = array_for_issue_dividend[0]["transaction_issue_dividend"];

            $("#transaction_data").append(array_for_issue_dividend['interface']);

            

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
                //console.log(transaction_issue_dividend);
                
            }
            else
            {
                $('.declare_of_fye').datepicker({ 
                    dateFormat:'dd/mm/yyyy',
                }).datepicker('setStartDate', "01/01/1920").datepicker("setDate", new Date(array_for_issue_dividend[0]["year_end"]));

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
                                console.log($(".trans_company_code"));
                                $(".trans_company_code").val(response[0]["company_code"]);
                                $("#issue_dividend_form #company_code").val(response[0]["company_code"]);
                                transaction_company_code = response[0]["company_code"];

                                $("#company_name").append(response[0]['company_name']);
                                $(".hidden_company_name").val(response[0]['company_name']);

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

            $('.devidend_of_cut_off_date').datepicker({ 
                 dateFormat:'dd/mm/yyyy',
             }).datepicker('setStartDate', "01/01/1920").on('changeDate', function (selected) {
                //console.log($('.billing_date').val());
                var devidend_of_cut_off_date = $('.devidend_of_cut_off_date').val();
                //console.log(devidend_of_cut_off_date);
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
        console.log(JSON.parse(data));
        array_for_issue_director_fee = JSON.parse(data);

        if(array_for_issue_director_fee['error'] == null)
        {
            transaction_issue_director_fee = array_for_issue_director_fee[0]["transaction_issue_director_fee"];

            $("#transaction_data").append(array_for_issue_director_fee['interface']);

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
                        $b += '<td><select class="form-control currency" style="width: 200px;" name="currency[]" id="currency""><option value="0">Select Currency</option></select></td>';
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
                                console.log($(".trans_company_code"));
                                $(".trans_company_code").val(response[0]["company_code"]);
                                $("#issue_director_fee_form #company_code").val(response[0]["company_code"]);
                                transaction_company_code = response[0]["company_code"];

                                $("#company_name").append(response[0]['company_name']);
                                $(".hidden_company_name").val(response[0]['company_name']);

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
        console.log(JSON.parse(data));
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
                    // console.log(data['result']);
                    //console.log(tr.find("#service_type"));
                    //console.log(dropdown_data.unit_pricing);
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
                                console.log($(".trans_company_code"));
                                $(".trans_company_code").val(response[0]["company_code"]);
                                $("#incorp_subsidiary_form #company_code").val(response[0]["company_code"]);
                                transaction_company_code = response[0]["company_code"];

                                $("#company_name").append(response[0]['company_name']);
                                $(".hidden_company_name").val(response[0]['company_name']);

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
        console.log(JSON.parse(data));
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

                    $.ajax({ //Upload common input
                          url: "transaction/select_banker_info",
                          type: "POST",
                          data: {"bank_id": $("#bank").val()},
                          dataType: 'json',
                          async: false,
                          success: function (response,data) {
                            console.log(response);
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
                                console.log($(".trans_company_code"));
                                $(".trans_company_code").val(response[0]["company_code"]);
                                $("#change_of_company_name_form #company_code").val(response[0]["company_code"]);
                                transaction_company_code = response[0]["company_code"];

                                $("#company_name").append(response[0]['company_name']);
                                $(".hidden_company_name").val(response[0]['company_name']);

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
    console.log($(this).val());
    if($(this).val() == 1)
    {
        $(".ceased_date_row").hide();
        $(".ceased_date").prop("disabled", true);
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
        console.log(JSON.parse(data));
        array_for_strike_off = JSON.parse(data);

        if(array_for_strike_off['error'] == null)
        {
            transaction_strike_off = array_for_strike_off[0]["transaction_strike_off"];

            $("#transaction_data").append(array_for_strike_off['interface']);

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
                                //console.log($(".trans_company_code"));
                                $(".trans_company_code").val(response[0]["company_code"]);
                                $("#strike_off_form #company_code").val(response[0]["company_code"]);
                                transaction_company_code = response[0]["company_code"];

                                // $("#company_name").append(response[0]['company_name']);
                                // $(".hidden_company_name").val(response[0]['company_name']);

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
        console.log(JSON.parse(data));
        array_for_change_company_name = JSON.parse(data);

        if(array_for_change_company_name['error'] == null)
        {
            transaction_change_company_name = array_for_change_company_name[0]["transaction_change_company_name"];

            $("#transaction_data").append(array_for_change_company_name['interface']);

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
                                console.log($(".trans_company_code"));
                                $(".trans_company_code").val(response[0]["company_code"]);
                                $("#change_of_company_name_form #company_code").val(response[0]["company_code"]);
                                transaction_company_code = response[0]["company_code"];

                                $("#company_name").append(response[0]['company_name']);
                                $(".hidden_company_name").val(response[0]['company_name']);

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
            console.log(transaction_appoint_new_auditor);
            $("#transaction_data").append(array_for_appoint_auditor['interface']);

            $('.meeting_date').datepicker({ 
                dateFormat:'dd/mm/yyyy',
            }).datepicker('setStartDate', new Date());

            $('.notice_date').datepicker({ 
                dateFormat:'dd/mm/yyyy',
            }).datepicker('setStartDate', new Date());

            //Officers
            if(transaction_appoint_new_auditor)
            {
                $(".meeting_date").val(transaction_appoint_new_auditor[0]["meeting_date"]);
                $(".notice_date").val(transaction_appoint_new_auditor[0]["notice_date"]);
                resignAuditorInterface(transaction_appoint_new_auditor);
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
                        console.log(response);
                            if(response)
                            {   
                                //$("#transaction_trans #company_code").val(response[0]["company_code"]);
                                resignAuditorInterface(response);
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
        console.log(JSON.parse(data));
        array_for_change_reg_ofis_address = JSON.parse(data);

        if(array_for_change_reg_ofis_address['error'] == null)
        {
            registered_address_info = array_for_change_reg_ofis_address[0]["registered_address_info"];
            transaction_change_reg_ofis = array_for_change_reg_ofis_address[0]["transaction_change_reg_ofis"];

            $("#transaction_data").append(array_for_change_reg_ofis_address['interface']);

            if(registered_address_info != undefined)
            {
              $.each(registered_address_info, function(key, val) {
                  var option = $('<option />');
                  option.attr('data-postal_code', val['postal_code']).attr('data-street_name', val['street_name']).attr('data-building_name', val['building_name']).attr('data-unit_no1', val['unit_no1']).attr('data-unit_no2', val['unit_no2']).attr('value', val['id']).text(val['service_name']);
                  if(transaction_change_reg_ofis != null)
                  {
                    if(transaction_change_reg_ofis[0]['our_service_regis_address_id'] != undefined && val['id'] == transaction_change_reg_ofis[0]['our_service_regis_address_id'])
                    {
                        option.attr('selected', 'selected');
                    }
                }
                  $("#service_reg_off").append(option);
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
                                console.log($(".trans_company_code"));
                                $(".trans_company_code").val(response[0]["company_code"]);
                                $("#change_of_reg_ofis_form #company_code").val(response[0]["company_code"]);
                                $("#transaction_trans #company_code").val(response[0]["company_code"]);
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

                                $("#old_registration_address").append(response[0]["street_name"]+'<br/>'+client_unit+' '+response[0]["building_name"]+'<br/> Singapore '+response[0]["postal_code"]);
                                $(".hidden_old_registration_address").val(response[0]["street_name"]+'<br/>'+client_unit+' '+response[0]["building_name"]+'<br/> Singapore '+response[0]["postal_code"]);
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
            toastr.error(array_for_change_reg_ofis_address['error'], "Error");
        }
    });
}

function getResignDirectorInterface()
{
    $("#transaction_data .panel").remove();
    $.post("transaction/get_resign_of_director_page", {id: transaction_id, company_code: transaction_company_code, transaction_task_id: transaction_task_id, registration_no: $(".uen_section #uen").val()}, function(data){
        console.log(JSON.parse(data));
        array_for_resign_director = JSON.parse(data);

        if(array_for_resign_director['error'] == null)
        {
            transaction_resign_director = array_for_resign_director[0]["transaction_resign_director"];

            $("#transaction_data").append(array_for_resign_director['interface']);

            //Officers
            if(transaction_resign_director)
            {
                resignDirectorInterface(transaction_resign_director);
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
                        console.log(response);
                            if(response)
                            {   
                                //console.log($(".trans_company_code"));
                                // $(".trans_company_code").val(response[0]["company_code"]);
                                // $("#resign_of_director_form #company_code").val(response[0]["company_code"]);
                                // transaction_company_code = response[0]["company_code"];

                                resignDirectorInterface(response);
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
                            transaction_company_code = response[0]["company_code"];
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
            toastr.error(array_for_resign_director['error'], "Error");
        }
    });
}

function getAppointmentSecretarialInterface()
{
    $("#transaction_data .panel").remove();
    $.post("transaction/get_appointment_of_secretarial_page", {id: transaction_id, company_code: transaction_company_code, transaction_task_id: transaction_task_id, registration_no: $(".uen_section #uen").val()}, function(data){
        console.log(JSON.parse(data));
        array_for_appoint_secretarial = JSON.parse(data);

        if(array_for_appoint_secretarial['error'] == null)
        {
            transaction_appoint_new_secretarial = array_for_appoint_secretarial[0]["transaction_appoint_new_secretarial"];

            $("#transaction_data").append(array_for_appoint_secretarial['interface']);

            //Officers
            if(transaction_appoint_new_secretarial)
            {
                appointNewSecretarialInterface(transaction_appoint_new_secretarial);
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
                            transaction_company_code = response[0]["company_code"];
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
            toastr.error(array_for_appoint_secretarial['error'], "Error");
        }
    });
}

function getAppointNewDirectorInterface()
{
    $("#transaction_data .panel").remove();
    $.post("transaction/get_appointment_of_director_page", {id: transaction_id, company_code: transaction_company_code, transaction_task_id: transaction_task_id, registration_no: $(".uen_section #uen").val()}, function(data){
        console.log(JSON.parse(data));
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
        console.log(JSON.parse(data));
        array_for_incop_new_company = JSON.parse(data);
        //console.log(array_for_incop_new_company[0]["registered_address_info"]);
        registered_address_info = array_for_incop_new_company[0]["registered_address_info"];
        transaction_client = array_for_incop_new_company[0]["transaction_client"];
        transaction_client_officers = array_for_incop_new_company[0]["transaction_client_officers"];
        // transaction_client_controller = array_for_incop_new_company[0]["transaction_client_controller"];
        transaction_filing = array_for_incop_new_company[0]["transaction_filing"];
        transaction_billing = array_for_incop_new_company[0]["transaction_billing"];
        transaction_previous_secretarial = array_for_incop_new_company[0]["transaction_previous_secretarial"];
        // transaction_member = array_for_incop_new_company[0]["transaction_member"];
        // transaction_client_signing_info = array_for_incop_new_company[0]["transaction_client_signing_info"];
        // transaction_contact_person_info = array_for_incop_new_company[0]["transaction_contact_person_info"];
        // if(transaction_contact_person_info != null)
        // {
        //     client_contact_info_email = transaction_contact_person_info[0]['transaction_client_contact_info_email'];
        //     client_contact_info_phone = transaction_contact_person_info[0]['transaction_client_contact_info_phone'];
        // }
        // client_selected_reminder = array_for_incop_new_company[0]["transaction_client_selected_reminder"];
        transaction_document = array_for_incop_new_company[0]["document"];

        documentInterface(transaction_document);

        $("#transaction_data").append(array_for_incop_new_company['interface']);

        // if(transaction_contact_person_info != null)
        // {
        //     $("#setup_form #contact_name").val(transaction_contact_person_info[0]["name"]);
        // }

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

            //console.log($("#upload_company_info #company_type"));
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
            //console.log(transaction_filing[0]["financial_year_period"]);
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
        console.log(JSON.parse(data));
        array_for_incop_new_company = JSON.parse(data);
        //console.log(array_for_incop_new_company[0]["registered_address_info"]);
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

            //console.log($("#upload_company_info #company_type"));
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
            //console.log(transaction_filing[0]["financial_year_period"]);
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
        // var lastTab = localStorage.getItem('lastTab');
        // if (lastTab) {
        //     $('[href="' + lastTab + '"]').tab('show');
        // }
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

    if(transaction_strike_off[0]["reason_for_application_id"] == 1)
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
    $("#activity_2").append(transaction_change_biz_activity[0]['old_activity2']);

    $(".hidden_old_activity1").val(transaction_change_biz_activity[0]['old_activity1']);
    $(".hidden_old_activity2").val(transaction_change_biz_activity[0]['old_activity2']);
    
    if(transaction_change_biz_activity[0]['remove_activity_2'] == 1)
    {
        $('.remove_activity_2').prop('checked', true);
        $('#new_activity2').prop('readOnly', true);
    }
    else
    {   
        $('.remove_activity_2').prop('checked', false);
        $('#new_activity2').prop('readOnly', false);
    }

    $("#new_activity1").val(transaction_change_biz_activity[0]['activity1']);
    $("#new_activity2").val(transaction_change_biz_activity[0]['activity2']);
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
}

function changeRegOfisInterface(transaction_change_reg_ofis)
{
    $(".transaction_change_regis_ofis_address_id").val(transaction_change_reg_ofis[0]["id"]);
    $(".trans_company_code").val(transaction_change_reg_ofis[0]["company_code"]);
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
}

function resignAuditorInterface(transaction_resign_new_auditor)
{
    console.log(transaction_resign_new_auditor);
    var appoint_auditor = [];
    for(var i = 0; i < transaction_resign_new_auditor.length; i++)
    {
        if(transaction_resign_new_auditor[i]["date_of_appointment"] != "")
        {
            $a="";
            $a += '<tr class="row_resign_auditor">';
            $a += '<td><select class="form-control position" style="text-align:right;" name="resign_position[]" id="resign_position'+i+'" disabled="disabled"><option value="0" >Select Position</option></select><input type="hidden" class="form-control hidden_position" name="hidden_position[]" id="hidden_position" value="'+transaction_resign_new_auditor[i]["position"]+'"/><div id="alternate_of" hidden><p style="font-weight:bold;">Alternate of: </p><select class="form-control select_alternate_of" id="select_alternate_of'+i+'" style="text-align:right;width: 150px;" name="resign_alternate_of[]"><option value="" >Select Director</option></select><div id="form_alternate_of"></div></div></td>';
            $a += '<td><input type="text" style="text-transform:uppercase;" name="resign_identification_register_no[]" class="form-control" value="'+ (transaction_resign_new_auditor[i]["identification_no"]!=null ? transaction_resign_new_auditor[i]["identification_no"] : transaction_resign_new_auditor[i]["register_no"]) +'" id="gid_add_officer" maxlength="15" disabled="disabled"/><input type="hidden" class="form-control" name="hidden_resign_identification_register_no[]" id="hidden_resign_identification_register_no" value="'+ (transaction_resign_new_auditor[i]["identification_no"]!=null ? transaction_resign_new_auditor[i]["identification_no"] : transaction_resign_new_auditor[i]["register_no"]) +'"/><div style="height:16px;" ><a class="" href="'+ url + 'personprofile/add" style="cursor:pointer;" id="add_office_person_link" target="_blank" hidden onclick="add_officers_person(this)"><div style="cursor:pointer;" id="click_add_person">Click here to <span style="font-weight:bold">Add Person</span></div></a></div></td>';
            $a += '<td><input type="text" style="text-transform:uppercase;" name="resign_name[]" id="name" class="form-control" value="'+ (transaction_resign_new_auditor[i]["company_name"]!=null ? transaction_resign_new_auditor[i]["company_name"] : transaction_resign_new_auditor[i]["name"]) +'" readonly/><div id="form_name"></div><div class="hidden"><input type="text" class="form-control" name="resign_client_officer_id[]" id="client_officer_id" value="'+transaction_resign_new_auditor[i]["id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="resign_officer_id[]" id="officer_id" value="'+transaction_resign_new_auditor[i]["officer_id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="resign_officer_field_type[]" id="officer_field_type" value="'+transaction_resign_new_auditor[i]["field_type"]+'"/></div></td>';
            $a += '<td><div class="input-group" style="width: 150px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="officer_date_of_appointment" name="resign_date_of_appointment[]" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="'+transaction_resign_new_auditor[i]["date_of_appointment"]+'" placeholder="DD/MM/YYYY" disabled="disabled"></div><input type="hidden" class="form-control" name="hidden_date_of_appointment[]" id="hidden_date_of_appointment" value="'+transaction_resign_new_auditor[i]["date_of_appointment"]+'"/></td>';
            $a += '<td><div class="input-group" style="width: 150px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker officer_date_of_cessation" id="officer_date_of_cessation'+i+'" name="resign_date_of_cessation[]" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="'+transaction_resign_new_auditor[i]["date_of_cessation"]+'" placeholder="DD/MM/YYYY" disabled="disabled"></div><input type="hidden" class="form-control" name="hidden_date_of_cessation[]" id="hidden_date_of_cessation" value="'+transaction_resign_new_auditor[i]["date_of_cessation"]+'"/></td>';
            $a += '<td><textarea class="form-control resign_auditor_reason" name="resign_auditor_reason[]" id="resign_auditor_reason" style="width:100%;height:70px;text-transform:uppercase;" disabled="disabled">'+(transaction_resign_new_auditor[i]["reason"]!=undefined ? transaction_resign_new_auditor[i]["reason"] : "")+'</textarea><input type="hidden" class="form-control" name="hidden_resign_auditor_reason[]" id="hidden_resign_auditor_reason" value="'+transaction_resign_new_auditor[i]["reason"]+'"/></td>';
            $a += '<td class="action"><div style="display: inline-block;"><button type="button" id="withdraw_auditor'+i+'" class="btn btn-primary withdraw_auditor" onclick="withdraw_auditor(this);">Withdraw</button></div></td>';
            $a += '</tr>';

            $("#body_resign_auditor").append($a);

            $('#officer_date_of_cessation'+i).datepicker({ 
                dateFormat:'dd/mm/yyyy',
            }).datepicker('setStartDate', transaction_resign_new_auditor[i]["date_of_appointment"]).on('changeDate', function(ev) {
                $(this).parent().parent().find("#hidden_date_of_cessation").val($(this).val());
            });
            // /console.log($('#officer_date_of_cessation'+i));
            console.log($('#officer_date_of_cessation'+i).val() != "");
            if($('#officer_date_of_cessation'+i).val() != "")
            {
                $('#officer_date_of_cessation'+i).parent().parent().parent().find(".officer_date_of_cessation").prop("disabled", false);
                $('#officer_date_of_cessation'+i).parent().parent().parent().find("#resign_auditor_reason").prop("disabled", false);

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
    //console.log($("#body_resign_auditor .withdraw_auditor").length);
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
        console.log($(this).val());
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
        $a += '<td class="action"><div style="display: inline-block;"><button type="button" class="btn btn-primary" onclick="delete_appoint_new_director(this);">Delete</button></div></td>';
        $a += '</tr>';

        $("#body_appoint_new_auditor").prepend($a);

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

function resignDirectorInterface(transaction_resign_new_director)
{
    //console.log(transaction_resign_new_director);
    var appoint_director = [];
    for(var i = 0; i < transaction_resign_new_director.length; i++)
    {
        if(transaction_resign_new_director[i]["date_of_appointment"] != "")
        {
            $a="";
            $a += '<tr class="row_resign_director">';
            $a += '<td><select class="form-control position" style="text-align:right;" name="resign_position[]" id="resign_position'+i+'" disabled="disabled"><option value="0" >Select Position</option></select><input type="hidden" class="form-control hidden_position" name="hidden_position[]" id="hidden_position" value="'+transaction_resign_new_director[i]["position"]+'"/><div id="alternate_of" hidden><p style="font-weight:bold;">Alternate of: </p><select class="form-control select_alternate_of" id="select_alternate_of'+i+'" style="text-align:right;width: 150px;" name="resign_alternate_of[]"><option value="" >Select Director</option></select><div id="form_alternate_of"></div></div></td>';
            $a += '<td><input type="text" style="text-transform:uppercase;" name="resign_identification_register_no[]" class="form-control" value="'+ (transaction_resign_new_director[i]["identification_no"]!=null ? transaction_resign_new_director[i]["identification_no"] : transaction_resign_new_director[i]["register_no"]) +'" id="gid_add_director_officer" maxlength="15" disabled="disabled"/><input type="hidden" class="form-control" name="hidden_resign_identification_register_no[]" id="hidden_resign_identification_register_no" value="'+ (transaction_resign_new_director[i]["identification_no"]!=null ? transaction_resign_new_director[i]["identification_no"] : transaction_resign_new_director[i]["register_no"]) +'"/><div style="height:16px;" ><a class="" href="'+ url + 'personprofile/add" style="cursor:pointer;" id="add_office_person_link" target="_blank" hidden onclick="add_officers_person(this)"><div style="cursor:pointer;" id="click_add_person">Click here to <span style="font-weight:bold">Add Person</span></div></a></div></td>';
            $a += '<td><input type="text" style="text-transform:uppercase;" name="resign_name[]" id="name" class="form-control" value="'+ (transaction_resign_new_director[i]["company_name"]!=null ? transaction_resign_new_director[i]["company_name"] : transaction_resign_new_director[i]["name"]) +'" readonly/><div id="form_name"></div><div class="hidden"><input type="text" class="form-control" name="resign_client_officer_id[]" id="client_officer_id" value="'+transaction_resign_new_director[i]["id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="resign_officer_id[]" id="officer_id" value="'+transaction_resign_new_director[i]["officer_id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="resign_officer_field_type[]" id="officer_field_type" value="'+transaction_resign_new_director[i]["field_type"]+'"/></div></td>';
            $a += '<td><div class="input-group" style="width: 150px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="officer_date_of_appointment" name="resign_date_of_appointment[]" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="'+transaction_resign_new_director[i]["date_of_appointment"]+'" placeholder="DD/MM/YYYY" disabled="disabled"></div><input type="hidden" class="form-control" name="hidden_date_of_appointment[]" id="hidden_date_of_appointment" value="'+transaction_resign_new_director[i]["date_of_appointment"]+'"/></td>';
            $a += '<td><div class="input-group" style="width: 150px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker officer_date_of_cessation" id="officer_date_of_cessation'+i+'" name="resign_date_of_cessation[]" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="'+transaction_resign_new_director[i]["date_of_cessation"]+'" placeholder="DD/MM/YYYY" disabled="disabled"></div><input type="hidden" class="form-control" name="hidden_date_of_cessation[]" id="hidden_date_of_cessation" value="'+transaction_resign_new_director[i]["date_of_cessation"]+'"/></td>';
            $a += '<td><textarea class="form-control resign_director_reason" name="resign_director_reason[]" id="resign_director_reason" style="width:100%;height:70px;text-transform:uppercase;" disabled="disabled">'+(transaction_resign_new_director[i]["reason"]!=undefined ? transaction_resign_new_director[i]["reason"] : "")+'</textarea><input type="hidden" class="form-control" name="hidden_resign_director_reason[]" id="hidden_resign_director_reason" value="'+transaction_resign_new_director[i]["reason"]+'"/></td>';
            $a += '<td class="action"><div style="display: inline-block;"><button type="button" id="withdraw_director'+i+'" class="btn btn-primary withdraw_director" onclick="withdraw_director(this);">Withdraw</button><input type="hidden" name="is_director_withdraw[]" id="is_director_withdraw" class="is_director_withdraw'+i+'" value="'+(transaction_resign_new_director[i]["is_resign"]!=undefined ? transaction_resign_new_director[i]["is_resign"] : "0")+'"></div></td>';
            $a += '</tr>';

            $("#body_resign_director").append($a);

            $('#officer_date_of_cessation'+i).datepicker({ 
                dateFormat:'dd/mm/yyyy',
            }).datepicker('setStartDate', transaction_resign_new_director[i]["date_of_appointment"]).on('changeDate', function(ev) {
                $(this).parent().parent().find("#hidden_date_of_cessation").val($(this).val());
            }).on('clearDate', function(ev) {
                $(this).parent().parent().find("#hidden_date_of_cessation").val($(this).val());
            });
            // /console.log($('#officer_date_of_cessation'+i));
            //console.log($('#officer_date_of_cessation'+i).val() != "");
            if($('#officer_date_of_cessation'+i).val() != "" || $('.is_director_withdraw'+i).val() != "0")
            {
                $('#officer_date_of_cessation'+i).parent().parent().parent().find(".officer_date_of_cessation").prop("disabled", false);
                $('#officer_date_of_cessation'+i).parent().parent().parent().find("#resign_director_reason").prop("disabled", false);

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
        console.log($(this).val());
        $(this).parent().find("#hidden_resign_director_reason").val($(this).val());
    });

    if($("#body_resign_director .withdraw_director").length >= 1)
    {
        $('#appoint_new_director').hide();
    }
    else
    {
        $('#appoint_new_director').show();
    }
}

function appointNewSecretarialInterface(transaction_appoint_new_secretarial)
{
    for(var i = 0; i < transaction_appoint_new_secretarial.length; i++)
    {
        $a="";
        $a += '<tr class="row_appoint_new_director">';
        $a += '<td><select class="form-control position" style="text-align:right;" name="position[]" id="position'+i+'" disabled="disabled"><option value="0" >Select Position</option></select><div id="form_position"></div></td>';
        $a += '<td><input type="text" style="text-transform:uppercase;" name="identification_register_no[]" class="form-control" value="'+ (transaction_appoint_new_secretarial[i]["identification_no"]!=null ? transaction_appoint_new_secretarial[i]["identification_no"] : transaction_appoint_new_secretarial[i]["register_no"]) +'" id="gid_add_director_officer" maxlength="15"/><div id="form_identification_register_no"></div><div style="height:16px;" ><a class="" href="'+ url + 'personprofile/add" style="cursor:pointer;" id="add_office_person_link" target="_blank" hidden onclick="add_officers_person(this)"><div style="cursor:pointer;" id="click_add_person">Click here to <span style="font-weight:bold">Add Person</span></div></a></div></td>';
        $a += '<td><input type="text" style="text-transform:uppercase;" name="name[]" id="name" class="form-control" value="'+ (transaction_appoint_new_secretarial[i]["company_name"]!=null ? transaction_appoint_new_secretarial[i]["company_name"] : transaction_appoint_new_secretarial[i]["name"]) +'" readonly/><div id="form_name"></div><div class="hidden"><input type="text" class="form-control" name="client_officer_id[]" id="client_officer_id" value="'+transaction_appoint_new_secretarial[i]["id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="officer_id[]" id="officer_id" value="'+transaction_appoint_new_secretarial[i]["officer_id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="officer_field_type[]" id="officer_field_type" value="'+transaction_appoint_new_secretarial[i]["field_type"]+'"/></div></td>';
        // $a += '<td><div class="input-group" style="width: 150px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="officer_date_of_appointment" name="date_of_appointment[]" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="'+transaction_appoint_new_director[i]["date_of_appointment"]+'" placeholder="DD/MM/YYYY"></div></td>';
        $a += '<td class="action"><div style="display: inline-block;"><button type="button" class="btn btn-primary" onclick="delete_appoint_new_director(this);">Delete</button></div></td>';
        $a += '</tr>';

        $("#body_appoint_new_secretarial").prepend($a);

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
        // $a += '<td><div class="input-group" style="width: 150px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="officer_date_of_appointment" name="date_of_appointment[]" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="'+transaction_appoint_new_director[i]["date_of_appointment"]+'" placeholder="DD/MM/YYYY"></div></td>';
        $a += '<td class="action"><div style="display: inline-block;"><button type="button" class="btn btn-primary" onclick="delete_appoint_new_director(this);">Delete</button></div></td>';
        $a += '</tr>';

        $("#body_appoint_new_director").prepend($a);

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

        !function (i) {
            $.ajax({
                type: "POST",
                url: "transaction/get_client_officers_position",
                data: {"position": transaction_client_officers[i]["position"]},
                dataType: "json",
                success: function(data){
                    //console.log(data);
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
                        
                        //$(".nationality").prop("disabled",false);
                    }
                    else{
                        alert(data.msg);
                    }

                    
                }               
            });
        } (i);

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
                                        console.log("selected_director=166");
                                        document.getElementById("nationalityId").disabled = true;
                                    }*/
                                }
                                $("#form"+i+" #alternate_of #select_alternate_of"+i+"").append(option);
                                //console.log("#form"+i+" #alternate_of #select_alternate_of"+i+"");
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
    console.log("in");
    for(var i = 0; i < transaction_client_controller.length; i++)
    {
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
        $a += '<td><div class="mb-md"><input type="text" style="text-transform:uppercase;" name="identification_register_no[]" class="form-control" value="'+ (transaction_client_controller[i]["identification_no"]!=null ? transaction_client_controller[i]["identification_no"] : transaction_client_controller[i]["register_no"] != null ? transaction_client_controller[i]["register_no"] : transaction_client_controller[i]["registration_no"]) +'" id="gid_add_controller_officer"/><div id="form_identification_register_no"></div><div style=""><a class="" href="'+ url + 'personprofile/add/1" style="cursor:pointer;" id="add_office_person_link" target="_blank" hidden onclick="add_controller_person(this)"><div style="cursor:pointer;">Click here to <span style="font-weight:bold">Add Person</span></div></a></div></div><div class="mb-md"><input type="text" style="text-transform:uppercase;" name="name[]" id="name" class="form-control" value="'+ (transaction_client_controller[i]["company_name"]!=null ? transaction_client_controller[i]["company_name"] : transaction_client_controller[i]["name"] != null ? transaction_client_controller[i]["name"] : transaction_client_controller[i]["client_company_name"]) +'" readonly/><div id="form_name"></div></div></td>';
        $a += '<td><div class="mb-md"><input type="text" name="date_of_birth[]" id="date_of_birth" class="form-control" value="'+transaction_client_controller[i]["date_of_birth"]+'" readonly/></div><div class="mb-md"><input type="text" style="text-transform:uppercase;" name="nationality[]" id="nationality" class="form-control nationality" value="'+transaction_client_controller[i]["nationality_name"]+'" readonly/></div></td>';
        $a += '<td><textarea class="form-control" name="address[]" id="controller_address" style="width:100%;height:70px;text-transform:uppercase;" readonly>'+transaction_client_controller[i]["address"]+'</textarea></div><div id="form_controller_address"></div><div class="hidden"><input type="text" class="form-control" name="client_controller_id[]" id="client_controller_id" value="'+transaction_client_controller[i]["id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="officer_id[]" id="officer_id" value="'+transaction_client_controller[i]["officer_id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="officer_field_type[]" id="officer_field_type" value="'+transaction_client_controller[i]["field_type"]+'"/></td>';
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
        $a="";
        $a += '<tr num="'+i+'" class="row_billing">';
        $a += '<td><div style="margin-bottom: 35px !important;"><select class="form-control billing_service" style="width: 100%;" name="service['+i+']" id="service'+i+'" onchange="optionCheckBilling(this);"><option value="0" >Select Service</option></select><div id="form_service"></div></div></td>';
        $a += '<td><div class="mb-md"><textarea class="form-control invoice_description" name="invoice_description['+i+']"  id="invoice_description" rows="3" style="width:290px">'+transaction_billing[h]["invoice_description"]+'</textarea></div><div class="hidden"><input type="text" class="form-control" name="client_billing_info_id['+i+']" id="client_billing_info_id" value="'+transaction_billing[h]["client_billing_info_id"]+'"/></div></td>';
        $a += '<td><select class="form-control currency" style="text-align:right;width: 100%;" name="currency['+i+']" id="service_currency'+i+'"><option value="0" >Select Currency</option></select><div id="form_currency"></div></td>';
        $a += '<td><input type="text" name="amount['+i+']" class="numberdes form-control amount" value="'+ addCommas(transaction_billing[h]["amount"])+'" id="amount" style="width:100%;text-align:right;"/><div id="form_amount"></div></td>';
        $a += '<td><select class="form-control" style="width: 100%;" name="unit_pricing['+i+']" id="unit_pricing'+i+'"><option value="0" >Select Unit Pricing</option></select><div id="form_unit_pricing"></div></td>';
        $a += '<td><div class="action"><button type="button" class="btn btn-primary" onclick="delete_billing_info(this);">Delete</button></div></td>';
        $a += "</tr>";

        $("#body_billing_info").append($a);

        !function (i) {

            $.ajax({
                type: "POST",
                url: "transaction/get_billing_info_service",
                data: {"company_code": transaction_company_code, "service": transaction_billing[h]["service"]},
                async: false,
                dataType: "json",
                success: function(data){
                    //console.log(data);
                    $("#service"+i).find("option:eq(0)").html("Select Service");
                    if(data.tp == 1){
                        var category_description = '';
                        var optgroup = '';

                        for(var t = 0; t < data.selected_billing_info_service_category.length; t++)
                        {
                            if(category_description != data.selected_billing_info_service_category[t]['category_description'])
                            {
                                if(optgroup != '')
                                {
                                    $("#service"+i).append(optgroup);
                                }
                                optgroup = $('<optgroup label="' + data.selected_billing_info_service_category[t]['category_description'] + '" />');
                            }

                            category_description = data.selected_billing_info_service_category[t]['category_description'];

                            for(var h = 0; h < data.result.length; h++)
                            {
                                if(category_description == data.result[h]['category_description'])
                                {
                                    var option = $('<option />');
                                    option.attr('data-description', data.result[h]['invoice_description']).attr('data-currency', data.result[h]['currency']).attr('data-unit_pricing', data.result[h]['unit_pricing']).attr('data-amount', data.result[h]['amount']).attr('value', data.result[h]['id']).text(data.result[h]['service_name']).appendTo(optgroup);

                                    if(data.selected_service != null && data.result[h]['id'] == data.selected_service)
                                    {
                                        option.attr('selected', 'selected');
                                    }
                                }
                            }
                            
                        }
                        $("#service"+i).append(optgroup);
                        $("#service"+i).select2({
                            formatNoMatches: function () {
                                return "No Result. <a href='our_firm/edit/"+data.firm_id+"' onclick='open_new_tab("+data.firm_id+")' target='_blank'>Click here to add Service</a>"
                            }
                        })
                        

                        $("#service"+i+" option").filter(function()
                        {
                            return $.inArray($(this).val(),data.selected_query)>-1;
                        }).attr("disabled","disabled");  

                        $('select[name="service['+i+']"] option').filter(function()
                        {
                            return $(this).val() === data.selected_service;
                        }).attr("disabled", false);


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
                    //console.log(data);
                    if(data.tp == 1){
                        $.each(data['result'], function(key, val) {
                            var option = $('<option />');
                            option.attr('value', key).text(val);
                            if(transaction_billing[h]["currency"] != null && key == transaction_billing[h]["currency"])
                            {
                                option.attr('selected', 'selected');
                            }
                            $("#service_currency"+i).append(option);

                            console.log($("#service_currency"+i));
                        });
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
                    //console.log(data);
                    if(data.tp == 1){
                        $.each(data['result'], function(key, val) {
                            var option = $('<option />');
                            option.attr('value', key).text(val);
                           
                            if(transaction_billing[h]["unit_pricing"] != null && key == transaction_billing[h]["unit_pricing"])
                            {
                                option.attr('selected', 'selected');
                            }
                            $("#unit_pricing"+i).append(option);

                        });
                    }
                    else{
                        alert(data.msg);
                    }  
                }               
            });
        }(i);
    }
}

function shareTransferInterface(transaction_member)
{
    for(var i = 0; i < transaction_member.length; i++)
    {
        if(0 > transaction_member[i]["number_of_share"])
        {   //console.log(transaction_member[i]["number_of_share"]);
            $a0=""; 
            $a0 += '<div class="tr editing transfer transfer_coll" method="post" name="form'+i+'" id="form'+i+'" num="'+i+'">';
            $a0 += '<div class="hidden"><input type="text" class="form-control" name="company_code" value="'+transaction_member[i]["company_code"]+'"/></div>';
            $a0 += '<div class="hidden"><input type="text" class="form-control cert_id" name="cert_id[]" id="cert_id" value="'+transaction_member[i]["cert_id"]+'"/></div>';
            $a0 += '<div class="hidden"><input type="text" class="form-control" name="transfer_id[]" id="transfer_id" value="'+transaction_member[i]["id"]+'"/></div>';
            $a0 += '<div class="hidden"><input type="text" class="form-control" name="officer_id['+i+']" id="officer_id" value="'+(transaction_member[i]["officer_id"]!=null ? transaction_member[i]["officer_id"] : (transaction_member[i]["officer_company_id"]!=null ? transaction_member[i]["officer_company_id"] : transaction_member[i]["client_company_id"]))+'"/></div>';
            $a0 += '<div class="hidden"><input type="text" class="form-control" name="field_type['+i+']" id="field_type" value="'+(transaction_member[i]["officer_field_type"]!=null ? transaction_member[i]["officer_field_type"] : (transaction_member[i]["officer_company_field_type"]!=null ? transaction_member[i]["officer_company_field_type"] : transaction_member[i]["client_company_field_type"]))+'"/></div>';
            /*$a += '<div class="td">'+$count_allotment+'</div>';*/
            $a0 += '<div class="td"><div class="transfer_group mb-md" style="width: 200px"><select class="form-control person_id" style="text-align:right;width: 100%;" name="id['+i+']" id="person_id"><option value="0" >Select ID</option></select></div></div>';
            /*$a0 += '<div class="td"><div class="transfer_group mb-md"><input type="text" name="name['+0+']" class="form-control" value=""/></div></div>';
            $a0 += '<div class="td"><div class="transfer_group mb-md"><input type="text" name="number_of_share['+0+']" class="form-control" value=""/></div></div>';*/
            $a0 += '<div class="td"><div class="transfer_group mb-md" id="name" style="width: 200px; text-align:left"></div><input type="hidden" class="form-control" name="person_name['+i+']" value="" id="person_name"/></div>';
            $a0 += '<div class="td"><div style="text-align:right;width: 180px" class="transfer_group mb-md" id="number_of_share"></div><input type="hidden" class="form-control" name="current_share['+i+']" value="" id="current_share"/><input type="hidden" class="form-control" name="amount_share['+i+']" value="" id="amount_share"/><input type="hidden" class="form-control" name="no_of_share_paid['+i+']" value="" id="no_of_share_paid"/><input type="hidden" class="form-control" name="amount_paid['+i+']" value="" id="amount_paid"/></div>';
            $a0 += '<div class="td"><div class="transfer_group mb-md" style="width: 200px"><input type="text" class="numberdes form-control share_transfer" style="text-align:right;" name="share_transfer['+i+']" value="'+addCommas(Math.abs(transaction_member[i]["number_of_share"]))+'" id="share_transfer" pattern="^[0-9,]+$"/></div></div>';
            $a0 += '<div class="td"><div class="transfer_group mb-md" style="width: 100px"><input type="text" class="numberdes form-control consideration" style="text-align:right;" name="consideration['+i+']" value="'+addCommas(Math.abs(transaction_member[i]["consideration"]))+'" id="consideration" pattern="^[0-9,]+$"/></div></div>';
            $a0 += '<div class="td"><div class="transfer_group mb-md" style="width: 100px"><input type="text" class="form-control from_certificate" name="from_certificate['+i+']" value="'+transaction_member[i]["certificate_no"]+'" id="to_certificate"/></div></div>';
            $a0 += '<div class="td action"><button type="button" class="btn btn-primary delete_transfer_button" onclick="delete_transfer(this)" style="display: none;">Delete</button></div>';
            $a0 += '</div>';

            $("#transfer_add").append($a0); 

            if($("#transfer_add > div").length > 1)
            {
                $('.delete_transfer_button').css('display','block');
            }

            for(var a = 0; a < allotmentPeople.length; a++)
            {
                var option = $('<option />');
                /*console.log(currency_id);*/

                option.attr('data-name', (allotmentPeople[a]["company_name"]!=null ? allotmentPeople[a]["company_name"] : (allotmentPeople[a]["name"]!=null ? allotmentPeople[a]["name"] : allotmentPeople[a]["client_company_name"])));
                option.attr('data-numberofshare', allotmentPeople[a]['number_of_share']);
                option.attr('data-amountshare', allotmentPeople[a]['amount_share']);
                option.attr('data-noofsharepaid', allotmentPeople[a]['no_of_share_paid']);
                option.attr('data-amountpaid', allotmentPeople[a]['amount_paid']);
                option.attr('data-officerid', allotmentPeople[a]['officer_id']);
                option.attr('data-fieldtype', allotmentPeople[a]['field_type']);
                option.attr('value', allotmentPeople[a]['officer_id']).text((allotmentPeople[a]["identification_no"]!=null ? allotmentPeople[a]["identification_no"] : (allotmentPeople[a]["register_no"]!=null ? allotmentPeople[a]["register_no"] : allotmentPeople[a]["registration_no"])));

                if(transaction_member)
                {
                    if(transaction_member[i]["officer_id"] != null && allotmentPeople[a]['officer_id'] == transaction_member[i]["officer_id"])
                    {
                        $("#form"+i+" #name").text((allotmentPeople[a]["company_name"]!=null ? allotmentPeople[a]["company_name"] : (allotmentPeople[a]["name"]!=null ? allotmentPeople[a]["name"] : allotmentPeople[a]["client_company_name"])));
                        // $("#form"+i+" #number_of_share").text(parseInt(allotmentPeople[a]['number_of_share']) - parseInt(transfer[i]['number_of_share']));
                        $("#form"+i+" #number_of_share").text(addCommas(parseInt(allotmentPeople[a]['number_of_share'])));

                        $("#form"+i+" #person_name").val((allotmentPeople[a]["company_name"]!=null ? allotmentPeople[a]["company_name"] : (allotmentPeople[a]["name"]!=null ? allotmentPeople[a]["name"] : allotmentPeople[a]["client_company_name"])));
                        /*$("#form"+i+" #current_share").val(addCommas(parseInt(allotmentPeople[a]['number_of_share']) - parseInt(transfer[i]['number_of_share'])));
                        $("#form"+i+" #amount_share").val(parseFloat(alloment_people[a]['amount_share']) - parseFloat(transfer[i]['amount_share']));
                        $("#form"+i+" #no_of_share_paid").val(parseInt(alloment_people[a]['no_of_share_paid']) - parseInt(transfer[i]['no_of_share_paid']));
                        $("#form"+i+" #amount_paid").val(parseFloat(alloment_people[a]['amount_paid']) - parseFloat(transfer[i]['amount_paid']));*/

                        $("#form"+i+" #current_share").val(addCommas(parseInt(allotmentPeople[a]['number_of_share'])));
                        $("#form"+i+" #amount_share").val(parseFloat(allotmentPeople[a]['amount_share']));
                        $("#form"+i+" #no_of_share_paid").val(parseInt(allotmentPeople[a]['no_of_share_paid']));
                        $("#form"+i+" #amount_paid").val(parseFloat(allotmentPeople[a]['amount_paid']));
                        option.attr('selected', 'selected');
                    }
                }

                $("#form"+i+" #person_id").append(option); 
            }

        }
        else if(transaction_member[i]["number_of_share"] > 0)
        {
            //console.log(transfer);
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
            $atoe += '<div class="td"><div class="transfer_group mb-md" style="width: 200px"><input type="text" style="text-align:right;" class="numberdes form-control number_of_share_to" name="number_of_share_to['+i+']" value="'+addCommas(transaction_member[i]["number_of_share"])+'" id="number_of_share_to" pattern="^[0-9,]+$"/></div></div>';
            /*$ato += '<div class="td"><div class="transfer_group mb-md"><input type="text" class="form-control" name="certificate['+0+']" value="" id="certificate"/></div></div>';*/
            $atoe += '<div class="td"><div class="transfer_group mb-md" style="width: 100px"><input type="text" class="form-control to_certificate" name="to_certificate['+i+']" value="'+transaction_member[i]["certificate_no"]+'" id="to_certificate"/></div></div>';
            $atoe += '<div class="td action"><button type="button" class="btn btn-primary delete_to_button" onclick="delete_to(this)" style="display: none;">Delete</button></div>';

            $atoe += '</div>';

            $("#transfer_to_add").append($atoe); 

            if($("#transfer_to_add > div").length > 1)
            {
                $('.delete_to_button').css('display','block');
            }
        }
        
    }
    //console.log("kkkk");
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
        $a += '<td><div class="input-group mb-md"><input type="text" name="number_of_share['+v+']" class="numberdes form-control number_of_share" value="'+addCommas(transaction_member[z]["number_of_share"])+'" id="number_of_share" style="text-align:right;" pattern="^[0-9,]+$"/><div id="form_number_of_share"></div></div><div class="input-group mb-md"><input type="text" name="amount_share['+v+']" id="amount_share" class="numberdes form-control amount_share" value="'+addCommas(transaction_member[z]["amount_share"])+'" style="text-align:right;" pattern="[0-9.,]"/><div id="form_amount_share"></div></div></td>';
        $a += '<td><div class="input-group mb-md"><input type="text" tabindex="-1" name="no_of_share_paid['+v+']" class="numberdes form-control no_of_share_paid" value="'+addCommas(transaction_member[z]["no_of_share_paid"])+'" id="no_of_share_paid" style="text-align:right;" readonly/><div id="form_no_of_share_paid"></div></div><div class="input-group mb-md"><input type="text" name="amount_paid['+v+']" id="amount_paid" class="numberdes form-control amount_paid" value="'+addCommas(transaction_member[z]["amount_paid"])+'" style="text-align:right;" pattern="[0-9.,]"/><div id="form_amount_paid"></div></div></td>';
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
                            console.log(v);
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
                            /*console.log("#form"+$count_charges+" #currency"+$count_charges+"");*/
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
                            console.log(v);
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
                            /*console.log("#form"+$count_charges+" #currency"+$count_charges+"");*/
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

function documentInterface(transaction_document)
{
    $(".transaction_document_table").remove(); 
    //console.log("transaction_document");
    if(transaction_document)
    {
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

            $a = "";
            $a += "<tr class='transaction_document_table'>";
            $a += '<td>'+q+'<input type="hidden" name="document_master_id[]" value="'+transaction_document[f]['document_master_id']+'"></td>';
            $a += '<td><span href="" style="height:45px;font-weight:bold;" class="edit_currency  pointer amber">'+transaction_document[f]['document_name'] + ' ' + firm_name +'</span></td>';
            $a += "</tr>";

            $("#document_body").append($a); 
        }

        $(document).ready(function() {
            document_table = $('#datatable-document').DataTable();
            //$("#datatable-document .dataTables_length").hide();
            // var table = $('#datatable-document').DataTable({     
            //   'columnDefs': [
            //      {
            //         'targets': 0,
            //         'checkboxes': {
            //            'selectRow': true
            //         }
            //      }
            //   ],
            //   'select': {
            //      'style': 'multi'
            //   },
            //   'order': [[1, 'asc']]
            // });
        });
    }
}

$(document).on('click',"#submitGenerateDocument",function(e){
    
    //console.log($('#loadingmessage'));
    console.log($('.transaction_task option:selected').val());
    if($('.transaction_task option:selected').val() == 30)
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
    $.ajax({ //Upload common input
      url: "transaction_document/generate_document",
      type: "POST",
      data: document_table.$('input, select').serialize()+ '&company_code=' + transaction_company_code + '&transaction_task_name=' + $('.transaction_task option:selected').text() + '&transaction_master_id=' + $("#transaction_trans #transaction_master_id").val() + '&pre-printed_letterhead=' + result,
      dataType: 'json',
      success: function (response,data) {
        $('#loadingWizardMessage').hide();
        //console.log(response.zip_link);
            window.open(
              response.zip_link,
              '_blank' // <- This is what makes it open in a new window.
            );

            setTimeout(function(){ deletePDF(); }, 5000);
        }
    })
}

function deletePDF()
{
    $.ajax({ //Upload common input
      url: "transaction_document/delete_document",
      async: false,
      type: "POST",
      //data: {"path":link},
      dataType: 'json',
      success: function (response,data) {
        console.log(response);
      }
    })
}

$(function() { 
    // for bootstrap 3 use 'shown.bs.tab', for bootstrap 2 use 'shown' in the next line
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        // save the latest tab; use cookies if you like 'em better:
        localStorage.setItem('lastTab', $(this).attr('href'));
        //console.log(localStorage.getItem('lastTab'));
    });    
});

function formatDateFunc(date) {
    //console.log(date);
  var monthNames = [
    "01", "02", "03",
    "04", "05", "06", "07",
    "08", "09", "10",
    "11", "12"
  ];

  var day = date.getDate();
  //console.log(day.length);
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
                console.log(result);
                if(result == "")
                {
                    toastr.error("Please enter the cancellation transaction reason.", "Error");
                    return false; 
                }
                else
                {
                    $('#loadingWizardMessage').show();
                    $.ajax({ //Upload common input
                        url: "transaction/cancel_transaction_by_user",
                        type: "POST",
                        data: {"cancel_reason": result, "transaction_code": $('#transaction_code').val(), "transaction_task_id": $('#transaction_task').val(), "registration_no": $('#uen').val()},
                        dataType: 'json',
                        success: function (response) {
                            $('#loadingWizardMessage').hide();
                            //console.log(response);
                            toastr.success(response.message, response.title);
                            window.location.href = "transaction/";
                            //location.reload();
                        }
                    });
                }
            }
            
        }
    });
})

