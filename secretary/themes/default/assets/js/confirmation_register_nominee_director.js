$('.edit_current_nominee_director').on("click",function(e){
    e.preventDefault();
    $('[href="#transaction_data"]').tab('show');
    $('html, body').animate({
        scrollTop: $("#w2-nominee_director").offset().top-20
    }, 2000);
});

$('.edit_latest_nominee_director').on("click",function(e){
    e.preventDefault();
    $('[href="#transaction_data"]').tab('show');
    $('html, body').animate({
        scrollTop: $("#w2-nominee_director").offset().top+600
    }, 2000);
});