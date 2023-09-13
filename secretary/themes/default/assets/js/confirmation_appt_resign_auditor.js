$('.edit_resign_officer_info').on("click",function(e){
    e.preventDefault();
    $('[href="#transaction_data"]').tab('show');
    $('html, body').animate({
        scrollTop: $(".resign_auditor").offset().top-180
    }, 2000);
});

$('.edit_appoint_officer_info').on("click",function(e){
    e.preventDefault();
    $('[href="#transaction_data"]').tab('show');
    $('html, body').animate({
        scrollTop: $(".appoint_auditor").offset().top-180
    }, 2000);
});


check_incorp_date();