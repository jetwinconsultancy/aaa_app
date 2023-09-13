$(document).on('change','#banker_name',function(e){

    var email = $(this).find(':selected').data('email');
    var office_number = $(this).find(':selected').data('office_number');
    var mobile_number = $(this).find(':selected').data('mobile_number');
    var bank_name = $(this).find(':selected').data('bank_name');
    console.log(bank_name);
    if($(this).val() == 0)
    {
        $('#banker_email').text("");
        $('#office_number').text("");
        $('#mobile_number').text("");
        $('#banker_bank_name').text("");
    }
    else
    {
        $('#banker_email').text(email);
        $('#office_number').text(office_number);
        $('#mobile_number').text(mobile_number);
        $('#banker_bank_name').text(bank_name);
    }
    
});