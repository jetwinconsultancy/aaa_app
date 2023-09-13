

$(document).on('click',"#submit_company_particular",function(e){
    $('#loadingmessage').show();

    $company_code = $("#company_code").val();

    // var emphasis_of_matter_checkbox = $('[name="emphasis_of_matter_checkbox"]').val();
    // var other_matter_checkbox       = $('[name="other_matter_checkbox"]').val();
    // var disclaimer_checkbox         = $('[name="disclaimer_checkbox"]').val();

    // var opinion_detail_input     = $('.fs_aud_report_opinion').val() === '-' ? $('.lbl_opinion_details').text() : $('.opinion_detail_input').val(); // dropdown value
    // var emphasis_input           = emphasis_of_matter_checkbox == 1 ? $('.emphasis_input').text() : '';    
    // var other_matter             = other_matter_checkbox == 1 ? $('.lbl_other_matter').text() : '';
    // var key_audit_matter         = $('.lbl_key_aud_matter').text();
    // var given_disclaimer_opinion = disclaimer_checkbox == 1 ? $('.disclaimer_input').text() : '';

    $.ajax({ //Upload common input
      url: "financial_statement/submit_company_particular",
      type: "POST",
      data: $('#form_fs_company_info').serialize() + '&company_code=' + $company_code,
      dataType: 'json',
      success: function (response,data) {

        console.log(response);

        toastr.success(response.message, response.title);

        $('#loadingmessage').hide();

          // if (response.Status === 1) 
          // {
          //   toastr.success(response.message, response.title);
          //   // $("#body_appoint_new_director .row_appoint_new_director").remove();
          //   //console.log($("#transaction_trans #transaction_master_id"));
          //   //$(".transaction_change_regis_ofis_address_id").val(response.transaction_change_regis_ofis_address_id);
          //   $("#transaction_trans #transaction_code").val(response.transaction_code);
          //   $("#transaction_trans #transaction_master_id").val(response.transaction_master_id);
          //   //$("#strike_off_form #transaction_strike_off_id").val(response.transaction_strike_off_id);
          //   //getChangeRegOfisInterface(response.transaction_change_regis_office_address);
          // }
        }
    });
});