$('.edit_strike_off_notice').on("click",function(e){
    e.preventDefault();
    $('[href="#transaction_data"]').tab('show');
    $('html, body').animate({
        scrollTop: $("#w2-strike_off_form").offset().top-180
    }, 2000);
});

$('.edit_strike_off').on("click",function(e){
    e.preventDefault();
    $('[href="#transaction_data"]').tab('show');
    $('html, body').animate({
        scrollTop: $("#w2-strike_off_form").offset().top+180
    }, 2000);
});

check_incorp_date();