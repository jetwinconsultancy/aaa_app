function export_front_page_pdf(pre_printed){
	var fs_company_info_id = $('input[name=fs_company_info_id]').val();
	// company_code = $('#company_code').val();

	$('#loadingmessage').show();

	$.ajax({
		type: "POST",
		url: "create_fs_documents_pdf/fs_report",
		data: {'fs_company_info_id': fs_company_info_id, 'pre_printed': pre_printed}, // <--- THIS IS THE CHANGE
		dataType: "json",
		success: function(response){
			$('#myModal').modal('hide');

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
			setTimeout(function(){ deleteInvoicePDF(); }, 5000);
		}				
	});

	function deleteInvoicePDF()
	{
	    $.ajax({ //Upload common input
	      url: "create_fs_documents_pdf/delete_document",
	      async: false,
	      type: "POST",
	      dataType: 'json',
	      success: function (response,data) {
	        console.log(response);
	      }
	    })
	}
}

function export_doc_in_word(draft_report)
{
	var fs_company_info_id = $('input[name=fs_company_info_id]').val();
	var first_generate_report = $('#FS_generate_report .first_generate_report').val();

	$('#loadingmessage').show();

	$.ajax({
		type: "POST",
		url: "fs_generate_doc_word/fs_report",
		data: {'fs_company_info_id': fs_company_info_id, 'draft_report': draft_report, 'first_generate_report': first_generate_report}, // <--- THIS IS THE CHANGE
		dataType: "json",
		success: function(response){
			$('#myModal_word').modal('hide');

			for(var b = 0; b < response.link.length; b++) 
			{
				// console.log(response);
				
				// window.location.href = 'http://localhost/dot/pdf/invoice/INV - 1521254993.pdf';
				$('#loadingmessage').hide();
				window.open(
				  	response.link[b],
				  	'_blank' // <- This is what makes it open in a new window.
				);

				if(response['first_generate_report'] == "1")
				{
					location.reload();
				}
			}
			// setTimeout(function(){ deleteInvoicePDF(); }, 5000);
		}				
	});

	// function deleteInvoicePDF()
	// {
	//     $.ajax({ //Upload common input
	//       url: "create_fs_documents_pdf/delete_document",
	//       async: false,
	//       type: "POST",
	//       dataType: 'json',
	//       success: function (response,data) {
	//         console.log(response);
	//       }
	//     })
	// }
}