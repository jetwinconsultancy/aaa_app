$('.edit_omp_grant').on("click",function(e){
    e.preventDefault();
    $('[href="#transaction_data"]').tab('show');
    $('html, body').animate({
        scrollTop: $("#w2-omp_grant").offset().top-180
    }, 2000);
});