(function( $ ) {

	'use strict';

	var datatableInit = function() {

		var t = $('#datatable-pending').DataTable({
	        "order": [[ 4, "asc" ]]
	    });

	    t.on( 'order.dt search.dt', function () {
            t.column(1, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();

		var s = $('#datatable-all_doc').DataTable({
	        "order": [[ 3, "asc" ]]
	    });

	    s.on( 'order.dt search.dt', function () {
            s.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();

		$('#datatable-master').dataTable();
		$('#datatable-reminder').dataTable();
	};

	$(function() {
		datatableInit();
	});

}).apply( this, [ jQuery ]);

$(document).on('click','.tonggle_readmore',function (){
	$id = $(this).data('id');
	$("#"+$id).toggle();
});

toastr.options = {
  "positionClass": "toast-bottom-right"
}

$('.selectallpendingdocument').click(function() {
    if ($(this).is(':checked')) {
        $('input:checkbox').attr('checked', true);
    } else {
        $('input:checkbox').attr('checked', false);
    }
});

function exportDocumentPDF(){
	var documentCheckboxes = new Array();
	$('input[name="pending_document_checkbox"]:checked').each(function() {
	   documentCheckboxes.push($(this).val());
	});

	//console.log(billingCheckboxes);
	$('#loadingmessage').show();
	$.ajax({
		type: "POST",
		url: "createDocumentPdf/create_document_pdf",
		data: {"document_id":documentCheckboxes}, // <--- THIS IS THE CHANGE
		dataType: "json",
		success: function(response){
			console.log(response.link);
			console.log(window.URL);
			for(var b = 0; b < response.link.length; b++) 
			{
				//console.log(response);
				//window.location.href = 'http://localhost/dot/pdf/invoice/INV - 1521254993.pdf';
				$('#loadingmessage').hide();
				window.open(
					  response.link[b],
					  '_blank' // <- This is what makes it open in a new window.
					);

			}

		}				
	});
}

$(document).on('click',"#searchResult",function(e){
    $("#form_search_document").submit();
});

$(document).on('click',".document_check_state",function() {
	$tab_aktif = $(this).data("information");
	$(".submit_document_check_state").val($tab_aktif);
	
});

if(document_check_state)
{
	//console.log(document_check_state);

	if(document_check_state != "pending")
	{
		$('li[data-information="'+document_check_state+'"]').addClass("active");
		$('#w2-'+document_check_state+'').addClass("active");
		$('li[data-information="pending"]').removeClass("active");
		$('#w2-pending').removeClass("active");
	}
	
}

function delete_transaction_pending_document(element){
	var tr = jQuery(element).parent().parent();
	var each_document_id = tr.find('input[name="each_document_id"]').val();
	var each_company_name = tr.find('input[name="each_company_name"]').val();

	if(each_document_id != undefined)
	{
		$('#loadingmessage').show();
		$.ajax({ //Upload common input
	        url: "documents/delete_transaction_document",
	        type: "POST",
	        data: {"document_id": each_document_id, "company_name": each_company_name},
	        dataType: 'json',
	        success: function (response) {

	        	$('#loadingmessage').hide();
	        	toastr.success(response.message, response.title);
	        	//location.reload();
	        	
	        }
	    });
	}
	tr.remove();
}

function delete_pending_document(element){
	var tr = jQuery(element).parent().parent();
	var each_document_id = tr.find('input[name="each_document_id"]').val();
	var each_company_name = tr.find('input[name="each_company_name"]').val();
	if(each_document_id != undefined)
	{
		$('#loadingmessage').show();
		$.ajax({ //Upload common input
	        url: "documents/delete_document",
	        type: "POST",
	        data: {"document_id": each_document_id, "company_name": each_company_name},
	        dataType: 'json',
	        success: function (response) {
	        	$('#loadingmessage').hide();
	        	toastr.success(response.message, response.title);
	        }
	    });
	}
	tr.remove();
}

function delete_master_document(element){
	var tr = jQuery(element).parent().parent();
	var each_master_id = tr.find('input[name="each_master_id"]').val();
	var each_master_name = tr.find('input[name="each_master_name"]').val();
	if(each_master_id != undefined)
	{
		$('#loadingmessage').show();
		$.ajax({ //Upload common input
	        url: "documents/delete_master_document",
	        type: "POST",
	        data: {"master_id": each_master_id, "each_master_name": each_master_name},
	        dataType: 'json',
	        success: function (response) {

	        	$('#loadingmessage').hide();
	        	toastr.success(response.message, response.title);
	        	
	        }
	    });
	}
	tr.remove();
}

function delete_reminder_document(element){
	var tr = jQuery(element).parent().parent();
	var each_reminder_id = tr.find('input[name="each_reminder_id"]').val();
	var each_reminder_name = tr.find('input[name="each_reminder_name"]').val();
	if(each_reminder_id != undefined)
	{
		$('#loadingmessage').show();
		$.ajax({ //Upload common input
	        url: "documents/delete_reminder_document",
	        type: "POST",
	        data: {"reminder_id": each_reminder_id, "each_reminder_name": each_reminder_name},
	        dataType: 'json',
	        success: function (response) {

	        	$('#loadingmessage').hide();
	        	toastr.success(response.message, response.title);
	        	
	        }
	    });
	}
	tr.remove();
}

$(".open_update").click(function(){
	var company_code = $(this).data('code');
	console.log(company_code);
	open_update(company_code);
});

function open_update(company_code) {

	$("#modal_update").modal("show");
	/*$.ajax({
		type: "POST",
		url: "billings/get_billing_info",
		data: {"company_code":company_code}, // <--- THIS IS THE CHANGE

		dataType: "json",
		success: function(response){
			if(response.status == 1)
			{
				$(".table").find('#receipt_info').html(""); 
				$(".table").find('#receipt_total').html(""); 
				console.log(response);
				var company_info = response.result;
				document.getElementById('receipt_company_name').innerHTML = company_info[0]['company_name'];

				payment_mode();

				var receipt_outstanding = 0.00;
				for(var b = 0; b < company_info.length; b++) 
				{
					receipt_outstanding = receipt_outstanding + parseFloat(company_info[b]['outstanding'],2);

		            var table_cell = '<tr><td style="text-align:right">'+(b+1)+'</td><td style="text-align:center">'+company_info[b]['invoice_date']+'</td><td>'+company_info[b]['invoice_no']+'</td><td align=right data-value="'+company_info[b]['amount']+'">'+addCommas(company_info[b]['amount'])+'</td><td align=right  class="outstanding_class" data-id="'+company_info[b]['id']+'" data-outstandingvalue="'+company_info[b]['outstanding']+'">'+addCommas(company_info[b]['outstanding'])+'</td><td class="td_amount_received"><div class="input-group"><input type="text" class="numberdes form-control applied_amount change_amount" style="width:100%;text-align:right" placeholder="Amount" value="" name="received['+b+']"/></div><input type="hidden" name="id[]" value="'+company_info[b]['id']+'"/><input type="hidden" name="outstanding[]" value="'+company_info[b]['outstanding']+'"/></td></tr>';

		            $(".table").find('#receipt_info').append( table_cell );

		            $('#form_receipt').formValidation('addField', 'received['+b+']', received);
		        }
		        console.log(receipt_outstanding);
		        var table_total = '<tr><td align=right colspan=4>Total</td><td align=right >'+addCommas(receipt_outstanding.toFixed(2))+'</td><td align=right id="received"></td></tr>';

		        $(".table").find('#receipt_total').append(table_total);
		        $("#total_amount_received").val("");
		        $('#form_receipt').formValidation('revalidateField', 'total_amount_received');

		        $(".receipt_no").val("");
		        $(".reference_no").val("");
		        $(".receipt_date").val("");

		        $array = company_info[0]["incorporation_date"].split("/");
				$tmp = $array[0];
				$array[0] = $array[1];
				$array[1] = $tmp;
				//unset($tmp);
				$date_2 = $array[0]+"/"+$array[1]+"/"+$array[2];
				console.log(new Date($date_2));

				var date2 = new Date($date_2);

				$('.receipt_date').datepicker({ 
				    dateFormat:'dd/mm/yyyy',
				    autoclose: true,
				}).datepicker('setStartDate', date2)
				.on('changeDate', function (selected) {
				    $('#form_receipt').formValidation('revalidateField', 'receipt_date');
				});

				$("#modal_payment").modal("show");
			}
				
		}				
	});*/
}