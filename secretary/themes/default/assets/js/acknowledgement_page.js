var common_seal_and_stamp_array = new Array();

$.each(transaction_common_seal_vendor_list, function(key, val) {
    var option = $('<option />');
    option.attr('value', val["id"]).text(val["vendor_name"] + " (" + val["vendor_email"] + ")");
    $('.common_seal_vendor').append(option);
});


$(document).on("click", ".send_common_seal_email", function() {
	common_seal_and_stamp_array = new Array();
	$('input[name="common_seal_and_stamp"]:checked').each(function() {
	   common_seal_and_stamp_array.push($(this).val());
	});

	$.ajax({
        type: "POST", 
        url: "transaction/send_common_seal_email",
        data: {"common_seal_vendor_id": $("#common_seal_vendor").val(), "transaction_master_id_for_acknowledgement": $('#transaction_master_id_for_acknowledgement').val(), "common_seal_and_stamp_array": common_seal_and_stamp_array},
        dataType: "json",
        success: function(result){
        	if(result.status == 1)
        	{
        		toastr.success("Successful send the email.", "Success");
        		$(".send_common_seal_email").prop('disabled', true);
        	}
        	else if(result.status == 2 || result.status == 4)
        	{
        		toastr.error("Unsuccessful send the email.", "Error");
        	}
        	else if(result.status == 3)
        	{
        		toastr.error("Please purchase at least one item.", "Error");
        	}
		}
    });
});
