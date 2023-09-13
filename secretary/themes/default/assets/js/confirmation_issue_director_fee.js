$('.edit_issue_director_fee').on("click",function(e){
    e.preventDefault();
    $('[href="#transaction_data"]').tab('show');
    $('html, body').animate({
        scrollTop: $("#w2-issue_director_fee_form").offset().top-180
    }, 2000);
});

check_incorp_date();