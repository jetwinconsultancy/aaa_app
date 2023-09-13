$('.edit_change_biz_activity').on("click",function(e){
    e.preventDefault();
    $('[href="#transaction_data"]').tab('show');
    $('html, body').animate({
        scrollTop: $("#w2-change_biz_activity_form").offset().top-180
    }, 2000);
});

check_incorp_date();