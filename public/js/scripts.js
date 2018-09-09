$(document).ready(function(e) {

    $('#slidedown').click(function() {
        $('#down').slideToggle();
    });


    $('.mobileMenu').on('click', function(e) {


        $('.menu').slideToggle();

    });

    miniatures = $('.miniatures a');
    minImgs = $('.miniatures img');

    if($('body').outerWidth() <= 800) {
        miniatures.each(function(){
            miniatures.removeAttr('href');
        });
    } else {
        $(miniatures).lightbox();

    }




});
