// if(transaction_purchase_common_seal_data.length > 0)
// {
// 	var purchase_cs_index = transaction_purchase_common_seal_data.length - 1;
// }
// else
// {
// 	var purchase_cs_index = 0;
// }

function firstRowCS()
{
	$a = ""; 
	$a += '<tr class="row_purchase_common_seal">';
	$a += '<td><select class="form-control company_name" style="width:100%;" name="company_name[]" id="company_name0"><option value="0">Select Company Name</option></select></td>';
	$a += '<td><input type="text" style="text-transform:uppercase;" name="register_no[]" class="form-control register_no" value="" id="register_no" readonly="true"/></td>';
	$a += '<td><select class="form-control product" style="text-align:right;" name="product[]" id="product"><option value="0">Select Product</option><option value="Common Seal">Common Seal</option><option value="Self-inking round stamp">Self-inking round stamp</option></select></td>';
	$a += '<td class="action"><div style="display: inline-block;"><button type="button" class="btn btn-primary" onclick="delete_purchase_common_seal(this);">Delete</button></div></td>';
	$a += '</tr>';
	
	$("#purchase_common_seal_table").prepend($a); 

    $.each(client_list, function(key, val) {
        var option = $('<option />');
        option.attr('value', val["company_code"]).attr('data-register_no', val['registration_no']).text(val["company_name"]);

        $("#company_name0").append(option);
    });

    $("#company_name0").select2();
    purchase_cs_index++;
}

$(document).on('click',"#purchase_common_seal_Add",function() {
 	$a = ""; 
	$a += '<tr class="row_purchase_common_seal">';
	$a += '<td><select class="form-control company_name" style="width:100%;" name="company_name[]" id="company_name'+purchase_cs_index+'"><option value="0">Select Company Name</option></select></td>';
	$a += '<td><input type="text" style="text-transform:uppercase;" name="register_no[]" class="form-control register_no" value="" id="register_no" readonly="true"/></td>';
	$a += '<td><select class="form-control product" style="text-align:right;" name="product[]" id="product"><option value="0">Select Product</option><option value="Common Seal">Common Seal</option><option value="Self-inking round stamp">Self-inking round stamp</option></select></td>';
	$a += '<td class="action"><div style="display: inline-block;"><button type="button" class="btn btn-primary" onclick="delete_purchase_common_seal(this);">Delete</button></div></td>';
	$a += '</tr>';
	
	$("#purchase_common_seal_table").prepend($a); 

    $.each(client_list, function(key, val) {
        var option = $('<option />');
        option.attr('value', val["company_code"]).attr('data-register_no', val['registration_no']).text(val["company_name"]);

        $("#company_name"+purchase_cs_index).append(option);
    });

    $("#company_name"+purchase_cs_index).select2();
    purchase_cs_index++;
});

$(document).on('change','.company_name',function(e){
	var register_no = $(this).find(':selected').data('register_no');
	$(this).parent().parent().find(".register_no").val(register_no);
});

$(document).on('click',"#submitPurchaseCommonSealInfo",function(e){
    purchase_common_seal_form_submit();
});

function purchase_common_seal_form_submit() {
    $('#loadingmessage').show();

    $.ajax({
      url: "transaction/add_purchase_common_seal",
      type: "POST",
      data: $('form#purchase_common_seal_form').serialize()+ '&transaction_code=' + $('#transaction_code').val() + '&transaction_task_id=' + $('#transaction_task').val() + '&transaction_master_id=' + $("#transaction_trans #transaction_master_id").val(),
      dataType: 'json',
      success: function (response,data) {
        $('#loadingmessage').hide();

          if (response.Status === 1) 
          {
          	//$('.row_resign_director .resign_director_reason_selection').prop("disabled", true);
			toastr.success(response.message, response.title);
			$("#transaction_trans #transaction_master_id").val(response.transaction_master_id);
			$("#transaction_trans #transaction_code").val(response.transaction_code);
          }
        }
    });
};

function delete_purchase_common_seal(element)
{
    var tr = jQuery(element).parent().parent();
    tr.closest("tr").remove();
}