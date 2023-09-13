$('.edit_officer_info').on("click",function(e){
    e.preventDefault();
    $('[href="#transaction_data"]').tab('show');
    $('html, body').animate({
        scrollTop: $("#transaction_confirmation_officer_table").offset().top-180
    }, 2000);
});

check_incorp_date();