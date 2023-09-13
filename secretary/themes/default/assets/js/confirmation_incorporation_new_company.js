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

$('.edit_member_info').on("click",function(e){
    e.preventDefault();
    $('[href="#transaction_data"]').tab('show');
    $('html, body').animate({
        scrollTop: $("#w2-capital").offset().top-180
    }, 2000);
});

$('.edit_controller_info').on("click",function(e){
    e.preventDefault();
    $('[href="#transaction_data"]').tab('show');
    $('html, body').animate({
        scrollTop: $("#w2-controller").offset().top-180
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

$('.edit_setup_info').on("click",function(e){
    e.preventDefault();
    $('[href="#transaction_data"]').tab('show');
    $('html, body').animate({
        scrollTop: $("#w2-setup").offset().top-180
    }, 2000);
});

$('.lodgement_date').datepicker({ 
    dateFormat:'dd/mm/yyyy',
}).datepicker('setStartDate', "01/01/1920");

