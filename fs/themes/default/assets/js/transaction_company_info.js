$tab_aktif = "companyInfo";
var base_url = '<?php echo base_url() ?>';

$(document).on('change','#service_reg_off',function(e){

    var postal_codeValue = $(this).find(':selected').data('postal_code');
    var street_nameValue = $(this).find(':selected').data('street_name');
    var building_nameValue = $(this).find(':selected').data('building_name');
    var unit_no1Value = $(this).find(':selected').data('unit_no1');
    var unit_no2Value = $(this).find(':selected').data('unit_no2');

    document.getElementById("postal_code").value = postal_codeValue;
    document.getElementById("street_name").value = street_nameValue;
    document.getElementById("building_name").value = building_nameValue;
    document.getElementById("unit_no1").value = unit_no1Value;
    document.getElementById("unit_no2").value = unit_no2Value;

});

function fillRegisteredAddressInput(cbox) {
  //console.log(cbox);
  if (cbox.checked) {
    console.log(cbox);
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
  }
}

// function fillRegisteredAddressInput(cbox) {
//     //console.log(cbox);
//   if (cbox.checked) {
//     //console.log(cbox);
//     document.getElementById("postal_code").value = registered_address_info[0].postal_code;
//     document.getElementById("street_name").value = registered_address_info[0].street_name;
//     document.getElementById("building_name").value = registered_address_info[0].building_name;
//     document.getElementById("unit_no1").value = registered_address_info[0].unit_no1;
//     document.getElementById("unit_no2").value = registered_address_info[0].unit_no2;

//     document.getElementById("postal_code").readOnly = true;
//     document.getElementById("street_name").readOnly = true;
//     document.getElementById("building_name").readOnly = true;
//     document.getElementById("unit_no1").readOnly = true;
//     document.getElementById("unit_no2").readOnly = true;

//     $( '#form_postal_code' ).html("");
//     $( '#form_street_name' ).html("");
//   }
//   else
//   {
//     document.getElementById("postal_code").value = "";
//     document.getElementById("street_name").value = "";
//     document.getElementById("building_name").value = "";
//     document.getElementById("unit_no1").value = "";
//     document.getElementById("unit_no2").value = "";

//     document.getElementById("postal_code").readOnly = false;
//     document.getElementById("street_name").readOnly = false;
//     document.getElementById("building_name").readOnly = false;
//     document.getElementById("unit_no1").readOnly = false;
//     document.getElementById("unit_no2").readOnly = false;
//   }
// }

toastr.options = {
  "positionClass": "toast-bottom-right"
}

$(document).on('click',"#submitCompanyInfo",function(e){
  console.log("in");
    $('#loadingmessage').show();
    submitCompanyInfo();

});

function submitCompanyInfo ()
{
  $.ajax({ //Upload common input
    url: "transaction/save_company_info",
    type: "POST",
    data: $('form#upload_company_info').serialize()+ '&transaction_code=' + $('#transaction_code').val() + '&transaction_task_id=' + $('#transaction_task').val(),
    dataType: 'json',
    success: function (response,data) {
      $('#loadingmessage').hide();
        if (response.Status === 1) 
        {
          toastr.success(response.message, response.title);
          $("#transaction_trans #transaction_master_id").val(response.transaction_master_id);
          $("#transaction_trans #transaction_code").val(response.transaction_code);
          documentInterface(response.document);
        }
      }
  })
}

// $(document).on('click', "#company_type", function(e){
//   console.log($(this).val());
//   if($(this).val() == "4" || $(this).val() == "5" || $(this).val() == "6")
//   {
//       $("#guarantee").show();
//       $("#non-guarantee").hide();
//   }
//   else if($(this).val() == "1" || $(this).val() == "2" || $(this).val() == "3")
//   {
//       $("#non-guarantee").show();
//       $("#guarantee").hide();
//   }
// });

