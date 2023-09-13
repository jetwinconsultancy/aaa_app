$('.edit_issue_dividend').on("click",function(e){
    e.preventDefault();
    $('[href="#transaction_data"]').tab('show');
    $('html, body').animate({
        scrollTop: $("#w2-issue_dividend_form").offset().top-180
    }, 2000);
});

check_incorp_date();