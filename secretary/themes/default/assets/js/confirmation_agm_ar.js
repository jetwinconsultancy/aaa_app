$('.edit_company_info_and_status').on("click",function(e){
    e.preventDefault();
    $('[href="#transaction_data"]').tab('show');
    $('html, body').animate({
        scrollTop: $("#w2-agm_ar_form").offset().top-180
    }, 2000);
});

$('.edit_notice').on("click",function(e){
    e.preventDefault();
    $('[href="#transaction_data"]').tab('show');
    $('html, body').animate({
        scrollTop: $("#w2-agm_ar_form").offset().top+350
    }, 2000);
});

$('.edit_agenda').on("click",function(e){
    e.preventDefault();
    $('[href="#transaction_data"]').tab('show');
    $('html, body').animate({
        scrollTop: $("#w2-agm_ar_form").offset().top+1100
    }, 2000);
});

$('.edit_ar_declaration').on("click",function(e){
    e.preventDefault();
    $('[href="#transaction_data"]').tab('show');
    $('html, body').animate({
        scrollTop: $("#w2-agm_ar_form").offset().top+1950
    }, 2000);
});

check_incorp_date();
