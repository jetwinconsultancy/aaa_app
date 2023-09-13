$('.edit_resign_officer_info').on("click",function(e){
    e.preventDefault();
    $('[href="#transaction_data"]').tab('show');
    $('html, body').animate({
        // scrollTop: $("#resign_of_director_form").offset().top-180
        scrollTop: $(".resign_director").offset().top-180
    }, 2000);
});

$('.edit_appoint_officer_info').on("click",function(e){
    e.preventDefault();
    $('[href="#transaction_data"]').tab('show');
    $('html, body').animate({
        // scrollTop: $("#appoint_new_director").offset().top-180
        scrollTop: $(".appoint_auditor").offset().top-180
    }, 2000);
});

$('.edit_latest_nominee_director').on("click",function(e){
    e.preventDefault();
    $('[href="#transaction_data"]').tab('show');
    $('html, body').animate({
        scrollTop: $(".nominee_director").offset().top-180
    }, 2000);
});

$('.edit_billing_info').on("click",function(e){
    e.preventDefault();
    $('[href="#transaction_data"]').tab('show');
    $('html, body').animate({
        scrollTop: $("#w2-billing").offset().top-180
    }, 2000);
});


check_incorp_date();