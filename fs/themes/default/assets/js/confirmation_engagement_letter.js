$('.edit_engagement_letter').on("click",function(e){
    e.preventDefault();
    $('[href="#transaction_data"]').tab('show');
    $('html, body').animate({
        scrollTop: $("#w2-engagement_letter").offset().top-180
    }, 2000);
});

//check_incorp_date();

$('.tran_status').on("click",function(e){
    e.preventDefault();
    if($(".tran_status").val() == "3")
    {
        $(".reason_cancellation_textfield").show();
    }
    else
    {
        $(".reason_cancellation_textfield").hide();
        $(".cancellation_reason").val("");
    }
});

$('.lodgement_date').datepicker({ 
    dateFormat:'dd/mm/yyyy',
}).datepicker('setStartDate', "01/01/1920");