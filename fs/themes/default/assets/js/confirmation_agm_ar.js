$('.edit_agm_ar').on("click",function(e){
    e.preventDefault();
    $('[href="#transaction_data"]').tab('show');
    $('html, body').animate({
        scrollTop: $("#agm_ar_form").offset().top-180
    }, 2000);
});

check_incorp_date();