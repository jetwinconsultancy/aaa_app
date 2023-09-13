$('.edit_change_company_name').on("click",function(e){
    e.preventDefault();
    $('[href="#transaction_data"]').tab('show');
    $('html, body').animate({
        scrollTop: $("#w2-change_company_name_form").offset().top-180
    }, 2000);
});

check_incorp_date();