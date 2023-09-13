$tab_aktif = "companyInfo";
var base_url = '<?php echo base_url() ?>';

$(document).on('change','#service_reg_off',function(e){

    var postal_codeValue = $(this).find(':selected').data('postal_code');
    var street_nameValue = $(this).find(':selected').data('street_name');
    var building_nameValue = $(this).find(':selected').data('building_name');
    var unit_no1Value = $(this).find(':selected').data('unit_no1');
    var unit_no2Value = $(this).find(':selected').data('unit_no2');
    var our_service_info_idValue = $(this).find(':selected').data('our_service_info_id');

    document.getElementById("postal_code").value = postal_codeValue;
    document.getElementById("street_name").value = street_nameValue;
    document.getElementById("building_name").value = building_nameValue;
    document.getElementById("unit_no1").value = unit_no1Value;
    document.getElementById("unit_no2").value = unit_no2Value;

    add_service_engagment_row(our_service_info_idValue);
});

function fillIncorpRegisteredAddressInput(cbox) {
  if (cbox.checked) {
    $(".service_reg_off_area").show();

    document.getElementById("postal_code").readOnly = true;
    document.getElementById("street_name").readOnly = true;
    document.getElementById("building_name").readOnly = true;
    document.getElementById("unit_no1").readOnly = true;
    document.getElementById("unit_no2").readOnly = true;

    $( '#form_postal_code' ).html("");
    $( '#form_street_name' ).html("");
  }
  else
  {
    $(".service_reg_off_area").hide();
    $("#service_reg_off").val(0);

    document.getElementById("postal_code").value = "";
    document.getElementById("street_name").value = "";
    document.getElementById("building_name").value = "";
    document.getElementById("unit_no1").value = "";
    document.getElementById("unit_no2").value = "";

    document.getElementById("postal_code").readOnly = false;
    document.getElementById("street_name").readOnly = false;
    document.getElementById("building_name").readOnly = false;
    document.getElementById("unit_no1").readOnly = false;
    document.getElementById("unit_no2").readOnly = false;

    $('.billing_service :selected').each(function(){
        selected = $(this).val();
        selected_row = $(this);

        $.each(registered_address_info, function(key, val) {
            if(val['our_service_info_id'] == selected)
            {
                client_billing_info_id = selected_row.parent().parent().parent().parent().parent().find('#client_billing_info_id').val();
                array_client_billing_info_id.push(client_billing_info_id);
                selected_row.parent().parent().parent().parent().parent().remove();
            }
        });
    });
  }
}

toastr.options = {
  "positionClass": "toast-bottom-right"
}

$(document).on('click',"#submitCompanyInfo",function(e){
    $('#loadingmessage').show();
    submitCompanyInfo();

});

function submitCompanyInfo ()
{
  $.ajax({ //Upload common input
    url: "transaction/save_company_info",
    type: "POST",
    data: $('form#upload_company_info').serialize() + '&transaction_code=' + $('#transaction_code').val() + '&transaction_task_id=' + $('#transaction_task').val() + '&transaction_master_id=' + $("#transaction_trans #transaction_master_id").val(),
    dataType: 'json',
    success: function (response,data) {
      $('#loadingmessage').hide();
        if (response.Status === 1) 
        {
          submit_billing_data();

          $("#transaction_trans #transaction_master_id").val(response.transaction_master_id);
          $("#transaction_trans #transaction_code").val(response.transaction_code);
          documentInterface(response.document);

          $(".currency_class").remove();
          $.each(response.currency, function(key, val) {
              var option = $('<option />');
              option.attr('class', 'currency_class').attr('value', key).text(val);
              $('.client_qb_currency').append(option);
          });
            
          $(".client_id_to_qb").val(response.client_id);

          let client_qb_data_arr = response.check_client_qb_info;
          if(client_qb_data_arr.length > 0)
          {
            $(".client_qbs").show();
            $('.list_qb_cus_name').remove();
            $.each(client_qb_data_arr, function(key, val) {
                var option = "<li class='list_qb_cus_name'>"+client_qb_data_arr[key]["company_name"] +" ("+ client_qb_data_arr[key]["currency_name"] +")"+"</li>";
                $('.client_qb_data').append(option);
            });
          }
          else
          {
            $(".client_qbs").hide();
          }

          if(response.qb_company_id != "")
          {
              $('#modal_import_client_to_qb').modal('toggle');
          }
        }
      }
  })
}

$(document).on("click","#saveImportClientToQB",function(element) {
    var client_id = $(this).parent().parent().find('.client_id_to_qb').val(); 
    var client_qb_currency = $(this).parent().parent().find('.client_qb_currency').val(); 

    if(client_qb_currency != 0)
    {
        $('#loadingmessage').show();
        $.ajax({
            type: 'POST',
            url: "transaction/import_qb_client_to_quickbook",
            data: {"client_id": client_id, "client_qb_currency": client_qb_currency},
            dataType: 'json',
            success: function(response){
                $('#loadingmessage').hide();
                if(response.Status == 1)
                {
                    //$('#modal_import_services_to_qb').modal('toggle');
                    toastr.success(response.message, response.title);
                } 
                else if(response.Status == 2)
                {
                    toastr.warning(response.message, response.title);
                }
                else if(response.Status == 3)
                {
                    toastr.error(response.message, response.title);
                }
            }
        });
    }
    else
    {
        toastr.error("Please select one Income Account before you save.", "Error");
    }
});

