$('.edit_current_controller').on("click",function(e){
    e.preventDefault();
    $('[href="#transaction_data"]').tab('show');
    $('html, body').animate({
        scrollTop: $("#w2-controller").offset().top-20
    }, 2000);
});

$('.edit_latest_controller').on("click",function(e){
    e.preventDefault();
    $('[href="#transaction_data"]').tab('show');
    $('html, body').animate({
        scrollTop: $("#w2-controller").offset().top+600
    }, 2000);
});