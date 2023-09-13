

function get_financial_year_period(financial_year_period_id = null)
{
	//console.log("in");
	$.ajax({
		type: "GET",
		url: "masterclient/get_financial_year_period",
		dataType: "json",
		success: function(data){
			//console.log(financial_year_period_id);
            
            //console.log(data);
            if(data.tp == 1){
            	/*tr.find('select[name="alternate_of[]"]').html(""); 
            	tr.find('select[name="alternate_of[]"]').append($('<option>', {
				    value: '0',
				    text: 'Select Director'
				}));*/
            	
            	//option.attr('value', '').text("Select Director");
            	//tr.find('select[name="alternate_of[]"]').html("Select Director");
            	//option.attr('value', '0').text("Select Director");
            	$("#financial_year_period option").remove();
                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    if(financial_year_period_id != null && key == financial_year_period_id)
                    {
                        option.attr('selected', 'selected');
                    }
                    //console.log($("#financial_year_period"));
                    $("#financial_year_period").append(option);
                });
                
                //$(".nationality").prop("disabled",false);
            }
            else{
                alert(data.msg);
            }

			
		}				
	});
}

$(document).on('click',"#submitFilingInfo",function(e){
    $('#loadingmessage').show();
    $.ajax({ //Upload common input
      url: "transaction/save_filing_info",
      type: "POST",
      data: $('form#filing_form').serialize()+ '&transaction_code=' + $('#transaction_code').val() + '&transaction_task_id=' + $('#transaction_task').val(),
      dataType: 'json',
      success: function (response,data) {
        $('#loadingmessage').hide();
          if (response.Status === 1) 
          {
            toastr.success(response.message, response.title);
            // $("#body_controller .row_controller").remove();
            // controllerInterface(response.transaction_client_controller);
          }
        }
    })

});