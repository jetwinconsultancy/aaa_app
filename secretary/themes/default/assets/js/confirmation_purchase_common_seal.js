$('.edit_purchase_common_seal').on("click",function(e){
    e.preventDefault();
    $('[href="#transaction_data"]').tab('show');
    $('html, body').animate({
        scrollTop: $("#w2-purchase_common_seal_form").offset().top-180
    }, 2000);
});


$('.sendEmailForCommonSeal').on("click",function(e){

	for (var i = 0; i < $('#purchase_common_seal_form select[name="product[]"]').length; i++) 
	{
		if ($('#purchase_common_seal_form select[name="product[]"]')[i].value == 0) 
		{
			$('[href="#transaction_data"]').tab('show');
			$('#purchase_common_seal_form select[name="product[]"]')[i].focus();
			toastr.error("Please complete all required field", "Error");
		 	return false;
		 }
	}

	bootbox.confirm({
	    message: "Do you want to send Order of Self-inking round stamp or Common Seal email?",
	    closeButton: false,
	    buttons: {
	        confirm: {
	            label: 'Yes'
	        },
	        cancel: {
	            label: 'No',
	            className: 'btn-danger'
	        }
	    },
	    callback: function (result) {
	    	if(result)
	    	{
	    		$.ajax({
			        type: "POST", 
			        url: "transaction/send_common_seal_email_under_services",
			        data: {"transaction_master_id": $("#transaction_trans #transaction_master_id").val()},
			        dataType: "json",
			        success: function(result){
			        	if(result.status == 1)
			        	{
			        		toastr.success("Successful send the email.", "Success");
			        		$(".submitPurchaseCommonSealInfo").prop('disabled', true);
							$(".sendEmailForCommonSeal").prop('disabled', true);
						}
						else if(result.status == 2)
			        	{
			        		toastr.error("Unsuccessful send the email.", "Error");
			        	}
			        }
				});
			}
		}
	});
});
