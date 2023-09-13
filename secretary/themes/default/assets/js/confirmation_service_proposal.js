$('.edit_service_proposal').on("click",function(e){
    e.preventDefault();
    $('[href="#transaction_data"]').tab('show');
    $('html, body').animate({
        scrollTop: $("#w2-service_proposal").offset().top-180
    }, 2000);
});

//check_incorp_date();

// $('.tran_status').on("click",function(e){
//     e.preventDefault();
//     if($(".tran_status").val() == "4")
//     {
//         $(".reason_cancellation_textfield").show();
//     }
//     else
//     {
//         $(".reason_cancellation_textfield").hide();
//         $(".cancellation_reason").val("");
//     }
// });

// $('.lodgement_date').datepicker({ 
//     dateFormat:'dd/mm/yyyy',
// }).datepicker('setStartDate', "01/01/1920");