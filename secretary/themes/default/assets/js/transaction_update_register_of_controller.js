var pathArray = location.href.split( '/' );
var protocol = pathArray[0];
var host = pathArray[2];
var folder = pathArray[3];
var url = protocol + '//' + host + '/' + folder + '/';

$(".add_office_person_link").attr("href", url + 'personprofile/add/1');

$('#form_individual_register_of_controller').formValidation({
    framework: 'bootstrap',
    icon: {

    },

    fields: {
        identification_no: {
            row: '.div_identification_no',
            validators: {
                notEmpty: {
                    message: 'The Identification No. field is required.'
                }
            }
        },
        individual_controller_name: {
            row: '.div_controller_name',
            validators: {
                notEmpty: {
                    message: 'The Name field is required.'
                }
            }
        },
        date_appointed: {
            row: '.div_date_appoint',
            validators: {
                notEmpty: {
                    message: 'The Date Appointed field is required.'
                }
            }
        },
    }
});

$('#form_company_register_of_controller').formValidation({
    framework: 'bootstrap',
    icon: {

    },

    fields: {
        controller_uen: {
            row: '.div_controller_uen',
            validators: {
                notEmpty: {
                    message: 'The UEN field is required.'
                }
            }
        },
        entity_name: {
            row: '.div_entity_name',
            validators: {
                notEmpty: {
                    message: 'The Entity Name field is required.'
                }
            }
        },
        date_appointed: {
            row: '.div_date_appoint',
            validators: {
                notEmpty: {
                    message: 'The Date Appointed field is required.'
                }
            }
        },
    }
});

$('#form_individual_register_of_controller #date_appointed').datepicker({ 
    dateFormat:'dd/mm/yyyy',
    autoclose: true,
}).on('changeDate', function (selected) {
    $('#form_individual_register_of_controller').formValidation('revalidateField', 'date_appointed');
});

$('#form_individual_register_of_controller #date_ceased').datepicker({ 
    dateFormat:'dd/mm/yyyy',
    autoclose: true,
});

$('#form_individual_register_of_controller #date_of_notice').datepicker({ 
    dateFormat:'dd/mm/yyyy',
    autoclose: true,
});

$('#form_individual_register_of_controller #date_of_entry').datepicker({ 
    dateFormat:'dd/mm/yyyy',
    autoclose: true,
});

$('#form_individual_register_of_controller #date_confirmation').datepicker({ 
    dateFormat:'dd/mm/yyyy',
    autoclose: true,
});

$('#form_company_register_of_controller #date_appointed').datepicker({ 
    dateFormat:'dd/mm/yyyy',
    autoclose: true,
}).on('changeDate', function (selected) {
    $('#form_company_register_of_controller').formValidation('revalidateField', 'date_appointed');
});

$('#form_company_register_of_controller #date_ceased').datepicker({ 
    dateFormat:'dd/mm/yyyy',
    autoclose: true,
});

$('#form_company_register_of_controller #date_of_notice').datepicker({ 
    dateFormat:'dd/mm/yyyy',
    autoclose: true,
});

$('#form_company_register_of_controller #date_of_entry').datepicker({ 
    dateFormat:'dd/mm/yyyy',
    autoclose: true,
});

$('#form_company_register_of_controller #date_confirmation').datepicker({ 
    dateFormat:'dd/mm/yyyy',
    autoclose: true,
});

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
function open_controller()
{
    $(".individual_register_controller").show();
    $(".company_register_controller").hide();
    $('input[name="field_type"]').prop("disabled", false);
    $("#reg_cont_individual_edit").prop("checked", true);
    $('.individual_register_controller input[name="identification_no"]').prop("readonly", false);
    $('.individual_register_controller input').val("");
    $(".individual_register_controller .file_name").html("");
    $(".individual_register_controller .hidden_supporting_document").val("");
    $('.individual_register_controller .individual_div_date_confirmation').hide();

    $('.individual_register_controller .add_office_person_link').hide();
    $('.individual_register_controller .refresh_controller').hide();
    $('.individual_register_controller .refresh_a_controller').hide();
    $('.individual_register_controller .indi_view_edit_person').hide();

    $('.company_register_controller input').val('');
    $(".company_register_controller .file_name").html("");
    $(".company_register_controller .hidden_supporting_document").val("");
    $('.company_register_controller .corp_div_date_confirmation').hide();

    $('.company_register_controller .add_office_person_link').hide();
    $('.company_register_controller .refresh_controller').hide();
    $('.company_register_controller .refresh_a_controller').hide();
    $('.company_register_controller .corp_view_edit_person').hide();

    $("#modal_controller").modal("show");
    $('#form_individual_register_of_controller').formValidation('revalidateField', 'individual_controller_name');
}

$("#create_controller").click(function()
{
    open_controller();
});

//category
$("#reg_cont_individual_edit").click(function() {
    $(".individual_register_controller").show();
    $(".company_register_controller").hide();
    $('.company_register_controller input[name="controller_uen"]').prop("readonly", false);
    $('.company_register_controller input').val('');
    $(".company_register_controller .corp_file_name").html("");
    $(".company_register_controller .corp_hidden_supporting_document").val("");
    $('input[name="corp_confirm_registrable_controller"]').attr('checked', false);
    $('.company_register_controller .corp_div_date_confirmation').hide();
});

$("#reg_cont_company_edit").click(function() {
    $(".company_register_controller").show();
    $(".individual_register_controller").hide();
    $('.individual_register_controller input[name="identification_no"]').prop("readonly", false);
    $('.individual_register_controller input').val('');
    $(".individual_register_controller .file_name").html("");
    $(".individual_register_controller .hidden_supporting_document").val("");
    $('input[name="individual_confirm_registrable_controller"]').attr('checked', false);
    $('.individual_register_controller .individual_div_date_confirmation').hide();
});

$( "#form_individual_register_of_controller, #form_company_register_of_controller" ).submit(function( e ) {
    e.preventDefault();
    var radioButtonValue = $('input[name="field_type"]:checked').val();
    var $form = $(e.target);
    var form_id = $form.attr('id');
    if(form_id == "form_individual_register_of_controller" || form_id == "form_company_register_of_controller")
    {
        if(form_id == "form_individual_register_of_controller")
        {
            $('#form_individual_register_of_controller').formValidation('revalidateField', 'identification_no');
            $('#form_individual_register_of_controller').formValidation('revalidateField', 'individual_controller_name');
            $('#form_individual_register_of_controller').formValidation('revalidateField', 'date_appointed');
        }
        else if(form_id == "form_company_register_of_controller")
        {
            $('#form_company_register_of_controller').formValidation('revalidateField', 'controller_uen');
            $('#form_company_register_of_controller').formValidation('revalidateField', 'entity_name');
            $('#form_company_register_of_controller').formValidation('revalidateField', 'date_appointed');
        }
        var fv = $form.data('formValidation');
        // Get the first invalid field
        var $invalidFields = fv.getInvalidFields().eq(0);
        // Get the tab that contains the first invalid field
        var $tabPane     = $invalidFields.parents();
        var valid_setup = fv.isValidContainer($tabPane);

        if(valid_setup)
        {
            $('#loadingControllerMessage').show();
            if(radioButtonValue == "Individual")
            {
                $('.individual_register_controller #identification_type').attr('disabled', false);

                var formData = new FormData($('#form_individual_register_of_controller')[0]);
            }
            else
            {
                var formData = new FormData($('#form_company_register_of_controller')[0]);
            }
            formData.append('transaction_code', $('#transaction_code').val());
            formData.append('transaction_task_id', $('#transaction_task').val());
            formData.append('registration_no', $('#uen').val());
            formData.append('transaction_master_id', $('#transaction_trans #transaction_master_id').val());

            $.ajax({
                type: 'POST',
                url: "transaction/add_register_controller",
                data: formData,
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                success: function(response){
                    $('.individual_register_controller #identification_type').attr('disabled', true);
                    $('#loadingControllerMessage').hide();
                    if (response.Status === 1) 
                    {
                        var get_current_client_controller_data = response.current_client_controller_data;
                        var get_latest_client_controller_data = response.latest_client_controller_data;
                        $(".tr_controller_table").remove();
                        $('#current_controller_table').DataTable().clear();
                        $('#current_controller_table').DataTable().destroy();
                        registerControllerInterface(get_current_client_controller_data);

                        $(".tr_latest_controller_table").remove();
                        $('#latest_controller_table').DataTable().clear();
                        $('#latest_controller_table').DataTable().destroy();
                        registerLatestControllerInterface(get_latest_client_controller_data);

                        $("#transaction_trans #transaction_master_id").val(response.transaction_master_id);
                        $("#transaction_trans #transaction_code").val(response.transaction_code);
                        
                        $('.individual_register_controller input[name="identification_no"]').prop("readonly", false);
                        $('.company_register_controller input[name="controller_uen"]').prop("readonly", false);
                        $('#modal_controller').modal('toggle');
                    }
                }
            });
        }
    }
});

$(document).on('click',"#saveRegController",function(e){
    var radioButtonValue = $('input[name="field_type"]:checked').val();
    if(radioButtonValue == "Individual")
    {
        $("#form_individual_register_of_controller").submit();
    }
    else
    {
        $("#form_company_register_of_controller").submit();
    }
});

$(document).on('change','#supporting_document',function(){
    var filename = "";
    for(var i = 0; i < this.files.length; i++)
    {
        if(i == 0)
        {
            filename = this.files[i].name;
        }
        else
        {
            filename = filename + ", " + this.files[i].name;
        }
    }
    $(this).parent().find(".file_name").html(filename);
    $(this).parent().find(".hidden_supporting_document").val("");
});

$(document).on('change','#corp_supporting_document',function(){
    var filename = "";
    for(var i = 0; i < this.files.length; i++)
    {
        if(i == 0)
        {
            filename = this.files[i].name;
        }
        else
        {
            filename = filename + ", " + this.files[i].name;
        }
    }
    $(this).parent().find(".corp_file_name").html(filename);
    $(this).parent().find(".corp_hidden_supporting_document").val("");
});

//Confirmation by Registrable Controller
$("#individual_is_confirm_registrable_controller").click(function() {
    $(".individual_div_date_confirmation").show();
    $('.individual_register_controller input[name="date_confirmation"]').val("");
    $("#radio_individual_confirm_registrable_controller").val($(this).data('information'));
});

$("#individual_not_confirm_registrable_controller").click(function() {
    $(".individual_div_date_confirmation").hide();
    $('.individual_register_controller input[name="date_confirmation"]').val("");
    $("#radio_individual_confirm_registrable_controller").val($(this).data('information'));
});

$("#corp_is_confirm_registrable_controller").click(function() {
    $(".corp_div_date_confirmation").show();
    $('.company_register_controller input[name="date_confirmation"]').val("");
    $("#radio_corp_confirm_registrable_controller").val($(this).data('information'));
});

$("#corp_not_confirm_registrable_controller").click(function() {
    $(".corp_div_date_confirmation").hide();
    $('.company_register_controller input[name="date_confirmation"]').val("");
    $("#radio_corp_confirm_registrable_controller").val($(this).data('information'));
});

function refresh_person()
{
    var officer_frm = $("#gid_add_controller_officer");
    get_info(officer_frm);
}

//Search Officer
$("#gid_add_controller_officer").live('change',function(){
	var officer_frm = $(this);
    get_info(officer_frm);
});

function get_info(officer_frm)
{
    $('#loadingControllerMessage').show();
    $.ajax({
        type: "POST",
        url: "masterclient/get_guarantee_officer",
        data: {"identification_register_no":officer_frm.val()},
        dataType: "json",
        success: function(response){
            $('#loadingControllerMessage').hide();
            $(".refresh_controller").hide();
            $(".refresh_a_controller").hide();
            var controllerCategory = $('input[name="field_type"]:checked').val();
            if(response)
            {
                //--------------------new-----------------------
                if(response['field_type'] == "company")
                {
                    if(controllerCategory == "company")
                    {
                        $('.company_register_controller #company_code').val(transaction_company_code);
                        $('.company_register_controller input[name="entity_name"]').val(response['company_name']);
                        $('.company_register_controller input[name="officer_id"]').val(response['id']);
                        $('.company_register_controller input[name="officer_field_type"]').val(response['field_type']);
                        $('.company_register_controller .corp_view_edit_person').show();
                        $('.company_register_controller .corp_view_edit_person').attr("href", "personprofile/editCompany/"+encodeURIComponent(encodeURIComponent(response['encrypt_register_no'])));
                    }
                    else
                    {
                        $('.company_register_controller input[name="entity_name"]').val("");
                        toastr.error("Please put the correct UEN.", "Error");
                    }
                    $('#form_company_register_of_controller').formValidation('revalidateField', 'entity_name');
                }
                else if(response['field_type'] == "individual")
                {
                    if(controllerCategory == "Individual")
                    {
                        $('.individual_register_controller #company_code').val(transaction_company_code);
                        $('.individual_register_controller #individual_controller_name').val(response['name']);
                        $('.individual_register_controller input[name="officer_id"]').val(response['id']);
                        $('.individual_register_controller input[name="officer_field_type"]').val(response['field_type']);
                        $('.individual_register_controller .indi_view_edit_person').show();
                        $('.individual_register_controller .indi_view_edit_person').attr("href", "personprofile/edit/"+encodeURIComponent(encodeURIComponent(response['encrypt_identification_no'])));
                    }
                    else
                    {
                        $('.individual_register_controller #individual_controller_name').val("");
                        toastr.error("Please put the correct Identification No.", "Error");
                    }
                    $('#form_individual_register_of_controller').formValidation('revalidateField', 'individual_controller_name');
                }
                else
                {
                    if(controllerCategory == "company")
                    {
                        $('.company_register_controller #company_code').val(transaction_company_code);
                        $('.company_register_controller input[name="entity_name"]').val(response['company_name']);
                        $('.company_register_controller input[name="officer_id"]').val(response['id']);
                        $('.company_register_controller input[name="officer_field_type"]').val("client");
                        $('.company_register_controller .corp_view_edit_person').show();
                        $('.company_register_controller .corp_view_edit_person').attr("href", "masterclient/edit/"+response['client_id']);
                    }
                    else
                    {
                        $('.company_register_controller input[name="entity_name"]').val("");
                        toastr.error("Please put the correct UEN.", "Error");
                    }
                    $('#form_company_register_of_controller').formValidation('revalidateField', 'entity_name');
                }
                //------------------------------------------
                //---------------------new---------------------
                $('.add_office_person_link').attr('hidden',"true");
                //---------------------------------------------
            }
            else
            {
                //---------------------new---------------------
                if(controllerCategory == "company")
                {
                    $('.company_register_controller .add_office_person_link').show();
                    $('.company_register_controller input[name="entity_name"]').val("");
                    $('#form_company_register_of_controller').formValidation('revalidateField', 'entity_name');
                    $('.company_register_controller .corp_view_edit_person').hide();
                }
                else if(controllerCategory == "Individual")
                {
                    $('.individual_register_controller .add_office_person_link').show();
                    $('.individual_register_controller #individual_controller_name').val("");
                    $('#form_individual_register_of_controller').formValidation('revalidateField', 'individual_controller_name');
                    $('.individual_register_controller .indi_view_edit_person').hide();
                }
                //---------------------------------------------
            }
        }               
    });
}

function open_edit_controller(controller_id, transaction_id, office_id, field_type) {
    $('#loadingControllerMessage').show();
    $.ajax({
        type: "POST",
        url: "transaction/get_controller_info",
        data: {"controller_id":controller_id, "transaction_id":transaction_id, "office_id":office_id, "field_type":field_type}, // <--- THIS IS THE CHANGE
        dataType: "json",
        success: function(response){
            $('#loadingControllerMessage').hide();
            if(response.status == 1)
            {
                $("#modal_controller input[name='field_type']").prop("disabled", true);
                gid_add_controller_officer
                var controller_info = response.list_of_controller;
                if(controller_info[0]['client_controller_field_type'] == "company")
                {
                    $(".company_register_controller").show();
                    $(".individual_register_controller").hide();
                    $("#reg_cont_company_edit").prop("checked", true);
                    $('.company_register_controller #company_code').val(controller_info[0]['client_controller_company_code']);
                    $('.company_register_controller #transaction_client_controller_id').val(controller_info[0]['client_controller_id']);
                    $('.company_register_controller input[name="controller_uen"]').val(controller_info[0]['register_no']);
                    $('.company_register_controller input[name="controller_uen"]').prop("readonly", true);
                    $('.company_register_controller input[name="entity_name"]').val(controller_info[0]['officer_company_company_name']);
                    $('.company_register_controller input[name="officer_id"]').val(controller_info[0]['client_controller_officer_id']);
                    $('.company_register_controller input[name="officer_field_type"]').val(controller_info[0]['client_controller_field_type']);
                    $('.company_register_controller .corp_view_edit_person').show();
                    $('.company_register_controller .corp_view_edit_person').attr("href", "personprofile/editCompany/"+encodeURIComponent(encodeURIComponent(controller_info[0]['encrypt_register_no'])));

                    $('.company_register_controller input[name="date_appointed"]').val(controller_info[0]['date_of_registration']);
                    $('.company_register_controller input[name="date_ceased"]').val(controller_info[0]['date_of_cessation']);
                    $('.company_register_controller input[name="date_of_notice"]').val(controller_info[0]['date_of_notice']);
                    $('.company_register_controller input[name="date_of_entry"]').val(controller_info[0]['date_of_entry']);
                    if(controller_info[0]['is_confirm_by_reg_controller'] == "yes")
                    {
                        $("#corp_is_confirm_registrable_controller").prop("checked", true);
                        $('.company_register_controller .corp_div_date_confirmation').show();
                        $("#radio_corp_confirm_registrable_controller").val('yes');
                    }
                    else
                    {
                        $("#corp_not_confirm_registrable_controller").prop("checked", true);
                        $('.company_register_controller .corp_div_date_confirmation').hide();
                        $("#radio_corp_confirm_registrable_controller").val('no');
                    }
                    $('.company_register_controller input[name="date_confirmation"]').val(controller_info[0]['confirmation_received_date']);
                    if(controller_info[0]["supporting_document"] != "" && controller_info[0]["supporting_document"] != "[]")
                    {
                        var edit_file_result = JSON.parse(controller_info[0]["supporting_document"]);
                        var editfilename = "";

                        editfilename = '<a href="'+url+'uploads/supporting_doc/'+edit_file_result[0]+'" target="_blank">'+edit_file_result[0]+'</a>';
                        $(".company_register_controller .corp_file_name").html(editfilename);
                        $(".company_register_controller .corp_hidden_supporting_document").val(controller_info[0]["supporting_document"]);
                    }
                    else
                    {
                        $(".company_register_controller .corp_file_name").html("");
                        $(".company_register_controller .corp_hidden_supporting_document").val("");
                    }
                }
                else if(controller_info[0]['client_controller_field_type'] == "individual")
                {
                    $(".individual_register_controller").show();
                    $(".company_register_controller").hide();
                    $("#reg_cont_individual_edit").prop("checked", true);
                    $('.individual_register_controller #company_code').val(controller_info[0]['client_controller_company_code']);
                    $('.individual_register_controller #transaction_client_controller_id').val(controller_info[0]['client_controller_id']);
                    $('.individual_register_controller input[name="identification_no"]').val(controller_info[0]['identification_no']);
                    $('.individual_register_controller input[name="identification_no"]').prop("readonly", true);
                    $('.individual_register_controller #individual_controller_name').val(controller_info[0]['name']);
                    $('.individual_register_controller input[name="officer_id"]').val(controller_info[0]['client_controller_officer_id']);
                    $('.individual_register_controller input[name="officer_field_type"]').val(controller_info[0]['client_controller_field_type']);
                    $('.individual_register_controller .indi_view_edit_person').show();
                    $('.individual_register_controller .indi_view_edit_person').attr("href", "personprofile/edit/"+encodeURIComponent(encodeURIComponent(controller_info[0]['encrypt_identification_no'])));

                    $('.individual_register_controller input[name="date_appointed"]').val(controller_info[0]['date_of_registration']);
                    $('.individual_register_controller input[name="date_ceased"]').val(controller_info[0]['date_of_cessation']);
                    $('.individual_register_controller input[name="date_of_notice"]').val(controller_info[0]['date_of_notice']);
                    $('.individual_register_controller input[name="date_of_entry"]').val(controller_info[0]['date_of_entry']);
                    if(controller_info[0]['is_confirm_by_reg_controller'] == "yes")
                    {
                        $("#individual_is_confirm_registrable_controller").prop("checked", true);
                        $('.individual_register_controller .individual_div_date_confirmation').show();
                        $("#radio_individual_confirm_registrable_controller").val("yes");
                    }
                    else
                    {
                        $("#individual_not_confirm_registrable_controller").prop("checked", true);
                        $('.individual_register_controller .individual_div_date_confirmation').hide();
                        $("#radio_individual_confirm_registrable_controller").val("no");
                    }
                    $('.individual_register_controller input[name="date_confirmation"]').val(controller_info[0]['confirmation_received_date']);

                    if(controller_info[0]["supporting_document"] != "" && controller_info[0]["supporting_document"] != "[]")
                    {
                        var edit_file_result = JSON.parse(controller_info[0]["supporting_document"]);
                        var editfilename = "";
                        editfilename = '<a href="'+url+'uploads/supporting_doc/'+edit_file_result[0]+'" target="_blank">'+edit_file_result[0]+'</a>';
                        $(".individual_register_controller .file_name").html(editfilename);
                        $(".individual_register_controller .hidden_supporting_document").val(controller_info[0]["supporting_document"]);
                    }
                    else
                    {
                        $(".individual_register_controller .file_name").html("");
                        $(".individual_register_controller .hidden_supporting_document").val("");
                    }
                }
                else
                {
                    $(".company_register_controller").show();
                    $(".individual_register_controller").hide();
                    $("#reg_cont_company_edit").prop("checked", true);
                    $('.company_register_controller #company_code').val(controller_info[0]['client_controller_company_code']);
                    $('.company_register_controller #transaction_client_controller_id').val(controller_info[0]['client_controller_id']);
                    $('.company_register_controller input[name="controller_uen"]').val(controller_info[0]['registration_no']);
                    $('.company_register_controller input[name="controller_uen"]').prop("readonly", true);
                    $('.company_register_controller input[name="entity_name"]').val(controller_info[0]['client_company_name']);
                    $('.company_register_controller input[name="officer_id"]').val(controller_info[0]['client_controller_officer_id']);
                    $('.company_register_controller input[name="officer_field_type"]').val("client");
                    $('.company_register_controller .corp_view_edit_person').show();
                    $('.company_register_controller .corp_view_edit_person').attr("href", "masterclient/edit/"+controller_info[0]['client_id']);

                    $('.company_register_controller input[name="date_appointed"]').val(controller_info[0]['date_of_registration']);
                    $('.company_register_controller input[name="date_ceased"]').val(controller_info[0]['date_of_cessation']);
                    $('.company_register_controller input[name="date_of_notice"]').val(controller_info[0]['date_of_notice']);
                    $('.company_register_controller input[name="date_of_entry"]').val(controller_info[0]['date_of_entry']);
                    if(controller_info[0]['is_confirm_by_reg_controller'] == "yes")
                    {
                        $("#corp_is_confirm_registrable_controller").prop("checked", true);
                        $('.company_register_controller .corp_div_date_confirmation').show();
                        $("#radio_corp_confirm_registrable_controller").val('yes');
                    }
                    else
                    {
                        $("#corp_not_confirm_registrable_controller").prop("checked", true);
                        $('.company_register_controller .corp_div_date_confirmation').hide();
                        $("#radio_corp_confirm_registrable_controller").val('no');
                    }
                    $('.company_register_controller input[name="date_confirmation"]').val(controller_info[0]['confirmation_received_date']);
                    if(controller_info[0]["supporting_document"] != "" && controller_info[0]["supporting_document"] != "[]")
                    {
                        var edit_file_result = JSON.parse(controller_info[0]["supporting_document"]);
                        var editfilename = "";
                        editfilename = '<a href="'+url+'uploads/supporting_doc/'+edit_file_result[0]+'" target="_blank">'+edit_file_result[0]+'</a>';
                        $(".company_register_controller .corp_file_name").html(editfilename);
                        $(".company_register_controller .corp_hidden_supporting_document").val(controller_info[0]["supporting_document"]);
                    }
                    else
                    {
                        $(".company_register_controller .corp_file_name").html("");
                        $(".company_register_controller .corp_hidden_supporting_document").val("");
                    }
                }

                $("#modal_controller").modal("show");
            }
        }

    });
}

$(document).on("click", ".open_edit_controller", function () {
    var controller_id = $(this).data('id');
    var transaction_id = $(this).data('transaction_id');
    var office_id = $(this).data('officeid');
    var field_type = $(this).data('fieldtype');
    open_edit_controller(controller_id, transaction_id, office_id, field_type);
});

function add_controller_person(elem)
{
    //------------new--------------------
    $(".refresh_controller").show();
    $(".refresh_a_controller").show();
    
    //--------------------------------
    jQuery(elem).attr('hidden',"true");
}

function delete_register_controller(element)
{
    var tr = jQuery(element).parent().parent();

    var client_controller_id = tr.find('#client_controller_id').val();
    var client_controller_officer_id = tr.find('#client_controller_officer_id').val();
    var client_controller_field_type = tr.find('#client_controller_field_type').val();
    var delete_company_code = tr.find('#company_code').val();
    var client_controller_name = tr.find('#client_controller_name').val();

    bootbox.confirm("Are you confirm delete this record?", function (result) {
        if (result) 
        {
            $('#loadingmessage').show();
            if(client_controller_id != undefined)
            {
                $.ajax({
                    url: "transaction/delete_register_controller",
                    type: "POST",
                    data: {"client_controller_id": client_controller_id, "client_controller_officer_id": client_controller_officer_id, "client_controller_field_type": client_controller_field_type, "delete_company_code": delete_company_code, "client_controller_name": client_controller_name},
                    dataType: 'json',
                    success: function (response) {
                        $('#loadingmessage').hide();
                        var get_current_client_controller_data = response.current_client_controller_data;
                        var get_latest_client_controller_data = response.latest_client_controller_data;
                        $(".tr_controller_table").remove();
                        $('#current_controller_table').DataTable().clear();
                        $('#current_controller_table').DataTable().destroy();
                        registerControllerInterface(get_current_client_controller_data);

                        $( document ).ready(function() {
                            $('#current_controller_table').DataTable();
                        });

                        $(".tr_latest_controller_table").remove();
                        $('#latest_controller_table').DataTable().clear();
                        $('#latest_controller_table').DataTable().destroy();
                        registerLatestControllerInterface(get_latest_client_controller_data);

                        $( document ).ready(function() {
                            $('#latest_controller_table').DataTable();
                        });
                    }
                });

                toastr.success("Updated Information.", "Updated");
            }
        }
    });
}