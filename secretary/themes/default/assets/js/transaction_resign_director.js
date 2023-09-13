//-----------------open modal -------------------------
function open_nominee_director()
{
    $('.register_of_nominee_director input[name="nd_identification_no"]').prop("readonly", false);
    $('.register_of_nominee_director input[name="nomi_identification_no"]').prop("readonly", false);
    $('.register_of_nominee_director input').val("");
    $(".register_of_nominee_director .nd_file_name").html("");
    $(".register_of_nominee_director .nd_hidden_supporting_document").val("");

    $('.register_of_nominee_director .nd_add_office_person_link').hide();
    $('.register_of_nominee_director .nd_a_refresh_controller').hide();
    $('.register_of_nominee_director .nd_refresh_controller').hide();
    $('.register_of_nominee_director .nd_view_edit_person').hide();

    $('.register_of_nominee_director .nomi_add_office_person_link').hide();
    $('.register_of_nominee_director .nomi_a_refresh_controller').hide();
    $('.register_of_nominee_director .nomi_refresh_controller').hide();
    $('.register_of_nominee_director .nomi_view_edit_person').hide();

    $('#form_register_of_nominee_director').formValidation('addField', 'nd_identification_no', nd_identification_no);
    $('#form_register_of_nominee_director').formValidation('addField', 'nd_name', nd_name);

    $('#form_register_of_nominee_director').formValidation('addField', 'nomi_identification_no', nomi_identification_no);
    $('#form_register_of_nominee_director').formValidation('addField', 'nomi_name', nomi_name);
    $('#form_register_of_nominee_director').formValidation('addField', 'date_become_nominator', date_become_nominator);

    revalidateDatepicker();
    $("#modal_nominee_director").modal("show");
}

$(document).on('click','#create_nominee_director',function(){
    open_nominee_director();
});
//-----------------------------------------------------

function addNomineeDirector(element){

	var flag = $(element).is(':checked');

	if(flag)
	{
		$('.nominee_director').show();
	}
	else
	{
		$('.nominee_director').hide();
	}
}

function change_director_reason_selection(element){

	var tr = jQuery(element).parent().parent();

	var selected = tr.find(".resign_director_reason_selection").val();
	tr.find("#hidden_resign_director_reason_selected").val(selected);
	
	if(selected == 'OTHERS')
	{
		tr.find(".resign_director_reason").show();
	}
	else
	{
		tr.find(".resign_director_reason").hide();
		tr.find(".resign_director_reason").val("");
		tr.find(".hidden_resign_director_reason").val("");
	}
}

function withdraw_director(element)
{
	var tr = jQuery(element).parent().parent().parent();
	var client_officer_id = tr.find('input[name="client_officer_id[]"]').val();
	
	if(jQuery(element).html() == 'Withdraw')
	{
		tr.find(".officer_date_of_cessation").prop("disabled", false);
		tr.find("#resign_director_reason").prop("disabled", false);
		tr.find("#is_director_withdraw").val("1");
		tr.find(".resign_director_reason_selection").prop("disabled", false);

		jQuery(element).html('Cancel');
		jQuery(element).toggleClass('cancel_withdraw_director');
		jQuery(element).removeClass('withdraw_director');
	}
	else
	{
		tr.find(".officer_date_of_cessation").prop("disabled", true);
		tr.find("#resign_director_reason").hide();
		tr.find(".resign_director_reason_selection").prop("disabled", true);

		tr.find(".officer_date_of_cessation").val("");
		tr.find(".resign_director_reason_selection").val("");
		tr.find("#hidden_resign_director_reason_selected").val("null");

		tr.find("#hidden_date_of_cessation").val("");
		tr.find("#hidden_resign_director_reason").val("");

		tr.find("#is_director_withdraw").val("0");

		jQuery(element).html('Withdraw');
		jQuery(element).toggleClass('withdraw_director');
		jQuery(element).removeClass('cancel_withdraw_director');
	}
}

$(document).on('click',"#submitResignDirectorInfo",function(e){
    resign_of_director_form_submit();
    submit_billing_data();
});


function resign_of_director_form_submit() {
    $('#loadingmessage').show();
    $.ajax({ 
      url: "transaction/add_resign_director",
      type: "POST",
      data: $('form#resign_of_director_form').serialize()+ '&transaction_code=' + $('#transaction_code').val() + '&transaction_task_id=' + $('#transaction_task').val() + '&registration_no=' + $('#uen').val() + '&transaction_master_id=' + $("#transaction_trans #transaction_master_id").val(),
      dataType: 'json',
      success: function (response,data) {
        $('#loadingmessage').hide();
          if (response.Status === 1) 
          {
			toastr.success(response.message, response.title);
			$("#body_resign_director .row_resign_director").remove();
			$("#body_appoint_new_director .row_appoint_new_director").remove();
			$("#transaction_trans #transaction_master_id").val(response.transaction_master_id);
			$("#transaction_trans #transaction_code").val(response.transaction_code);
			resignDirectorInterface(response.transaction_client_officers);
          }
        }
    });
};



