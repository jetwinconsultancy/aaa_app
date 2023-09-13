$('.edit_ml_quarterly_statements').on("click",function(e){
    e.preventDefault();
    $('[href="#transaction_data"]').tab('show');
    $('html, body').animate({
        scrollTop: $("#w2-ml_quarterly_statements").offset().top-180
    }, 2000);
});