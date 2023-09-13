$('.edit_share_transfer_info').on("click",function(e){
    e.preventDefault();
    $('[href="#transaction_data"]').tab('show');
    $('html, body').animate({
        scrollTop: $("#w2-share_transfer_form").offset().top-180
    }, 2000);
});

check_incorp_date();

