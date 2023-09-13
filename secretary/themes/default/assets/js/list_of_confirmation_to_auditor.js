$('#letter_conf_auditor_date_to').datepicker("setDate", moment(new Date()).format("DD/MM/YYYY"));
$('#letter_conf_auditor_date_from').datepicker();

function changeDateFormat(date)
{
    var change_date_parts = date.split('/');
    var change_date = change_date_parts[2]+"/"+change_date_parts[1]+"/"+change_date_parts[0];

    return change_date;
}

toastr.options = {
    "positionClass": "toast-bottom-right"
}

function formatDateFunc(date) {
    //console.log(date);
  var monthNames = [
    "01", "02", "03",
    "04", "05", "06", "07",
    "08", "09", "10",
    "11", "12"
  ];

  var day = date.getDate();
  //console.log(day.length);
  if(day.toString().length==1)  
  {
    day="0"+day;
  }
    
  var monthIndex = date.getMonth();
  var year = date.getFullYear();

  return year + monthNames[monthIndex] + day;
}

$('#searchLetterConfAuditor').click(function(e){
	e.preventDefault();
	search_letter_of_conf_auditor_function();
});

function search_letter_of_conf_auditor_function()
{
	$('#loadingmessage').show();
	$.ajax({
        type: 'POST',
        url: "masterclient/search_letter_of_conf_auditor_function",
        data: $('#letter_confirmation_to_auditor_form').serialize(),
        dataType: 'json',
        success: function(response){
        	$('#loadingmessage').hide();
        	//console.log(response["list_of_confirmation_auditor"]);
            var list_of_confirmation_auditor = response["list_of_confirmation_auditor"];
            $(".tr_letter_confirmation_to_auditor").remove();
            $('#datatable-letter_confirmation_to_auditor').DataTable().clear();
			$('#datatable-letter_confirmation_to_auditor').DataTable().destroy();
            if(list_of_confirmation_auditor.length > 0)
        	{
				for(var i = 0; i < list_of_confirmation_auditor.length; i++)
			    {
			    	var latest_effective_date_format = changeDateFormat(list_of_confirmation_auditor[i]["effective_date"]);

					$b=""; 
			        $b += '<tr class="tr_letter_confirmation_to_auditor">';
			        $b += '<td style="text-align: right">'+(i+1)+'</td>';
			        $b += '<td><span style="display: none">'+ formatDateFunc(new Date(latest_effective_date_format)) + '</span>'+list_of_confirmation_auditor[i]["effective_date"]+'</td>';
			        $b += '<td>'+list_of_confirmation_auditor[i]["transaction_task"]+'</td>';
			        $b += '</tr>';

			        $("#letter_confirmation_to_auditor_body").append($b);
				}
			}
			else
			{
				
			}
			$('#datatable-letter_confirmation_to_auditor').DataTable();
        }
    });
}

$('#generate_letter_conf_pdf').click(function(e){
	e.preventDefault();
	$('#loadingmessage').show();
	$.ajax({
		type: "POST",
		url: "CreateListOfConfAuditor/create_pdf",
		data: $('#letter_confirmation_to_auditor_form').serialize(),
		dataType: "json",
		success: function(response){
			if(response.generate == 1)
			{
				for(var b = 0; b < response.link.length; b++) 
				{
					$('#loadingmessage').hide();
					window.open(
						  response.link[b],
						  '_blank' // <- This is what makes it open in a new window.
						);
				}
			}
			else if(response.generate == 2)
			{
				$('#loadingmessage').hide();
				toastr.error("Please fill in the Effective Date in Services.", "Error");
			}
			else if(response.generate == 3)
			{
				$('#loadingmessage').hide();
				toastr.error("No services can be generated.", "Error");
			}
		}				
	});

	// bootbox.prompt("Recipient Name", function(result){
	// 	if(result)
	// 	{
	//     	$('#loadingmessage').show();
	// 		$.ajax({
	// 			type: "POST",
	// 			url: "createlistofconfauditor/create_pdf",
	// 			data: $('#letter_confirmation_to_auditor_form').serialize() + '&recipient_name=' + result,
	// 			dataType: "json",
	// 			success: function(response){
	// 				for(var b = 0; b < response.link.length; b++) 
	// 				{
	// 					$('#loadingmessage').hide();
	// 					window.open(
	// 						  response.link[b],
	// 						  '_blank' // <- This is what makes it open in a new window.
	// 						);
	// 				}
	// 			}				
	// 		});
	// 	}
	// 	else
	// 	{
	// 		toastr.error("Please insert the Recipient Name.", "Error");
	// 	}
	// })

	// bootbox.confirm({
 //        message: "Do you want to print Pre-printed Letterhead document?",
 //        closeButton: false,
 //        buttons: {
 //            confirm: {
 //                label: 'Yes'
 //            },
 //            cancel: {
 //                label: 'No',
 //                className: 'btn-danger'
 //            }
 //        },
 //        callback: function (result) {
	// 		$('#loadingmessage').show();
	// 		$.ajax({
	// 			type: "POST",
	// 			url: "createlistofconfauditor/create_pdf",
	// 			data: $('#letter_confirmation_to_auditor_form').serialize() + '&pre-printed=' + result,
	// 			dataType: "json",
	// 			success: function(response){
	// 				for(var b = 0; b < response.link.length; b++) 
	// 				{
	// 					$('#loadingmessage').hide();
	// 					window.open(
	// 						  response.link[b],
	// 						  '_blank' // <- This is what makes it open in a new window.
	// 						);
	// 				}
	// 			}				
	// 		});
	// 	}
	// });
});
