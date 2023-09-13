$('.edit_company_info').on("click",function(e){
    e.preventDefault();
    $('[href="#transaction_data"]').tab('show');
    $('html, body').animate({
        scrollTop: $("#w2-companyInfo").offset().top-180
    }, 2000);
});

$('.edit_officer_info').on("click",function(e){
    e.preventDefault();
    $('[href="#transaction_data"]').tab('show');
    $('html, body').animate({
        scrollTop: $("#w2-officer").offset().top-180
    }, 2000);
});

$('.edit_filing_info').on("click",function(e){
    e.preventDefault();
    $('[href="#transaction_data"]').tab('show');
    $('html, body').animate({
        scrollTop: $("#w2-filing").offset().top-180
    }, 2000);
});

$('.edit_billing_info').on("click",function(e){
    e.preventDefault();
    $('[href="#transaction_data"]').tab('show');
    $('html, body').animate({
        scrollTop: $("#w2-billing").offset().top-180
    }, 2000);
});

$('.edit_previous_secretarial_info').on("click",function(e){
    e.preventDefault();
    $('[href="#transaction_data"]').tab('show');
    $('html, body').animate({
        scrollTop: $("#w2-previous_secretarial_info").offset().top-180
    }, 2000);
});

$('.lodgement_date').datepicker({ 
    dateFormat:'dd/mm/yyyy',
});