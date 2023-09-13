function get_financial_year_period(financial_year_period_id = null)
{
	$.ajax({
		type: "GET",
		url: "masterclient/get_financial_year_period",
		dataType: "json",
		success: function(data){
            if(data.tp == 1){
            	$("#financial_year_period option").remove();
                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    if(financial_year_period_id != null && key == financial_year_period_id)
                    {
                        option.attr('selected', 'selected');
                    }

                    $("#financial_year_period").append(option);
                });
            }
            else{
                alert(data.msg);
            }

			
		}				
	});
}

$(document).on('click',"#submitFilingInfo",function(e){
    $('#loadingmessage').show();
    $.ajax({
      url: "transaction/save_filing_info",
      type: "POST",
      data: $('form#filing_form').serialize()+ '&transaction_code=' + $('#transaction_code').val() + '&transaction_task_id=' + $('#transaction_task').val() + '&transaction_master_id=' + $("#transaction_trans #transaction_master_id").val(),
      dataType: 'json',
      success: function (response,data) {
        $('#loadingmessage').hide();
          if (response.Status === 1) 
          {
            toastr.success(response.message, response.title);
          }
        }
    })
});