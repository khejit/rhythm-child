

jQuery(document).ready(function($) {

    $('ul#special-nav a').each( function(){

        $(this).on('click', function (e) {
            var selectorHeight = $('.main-nav').height();
            var id = $(this).attr('href');
            var scrollSpeed = 700;

            e.preventDefault();

            goTo = $(id).offset().top - selectorHeight - 30;
            $("html, body").animate({scrollTop: goTo}, scrollSpeed);
        });

    });

    var nav = $('#special-nav');
    var navPosition = nav.offset().top;

    $(window).scroll(function(){

        var scroll = $(window).scrollTop()+110;

        if( scroll > navPosition ){
            nav.addClass('sticky');
            if ( scroll > navPosition + $('article').height() - 220 ){
                nav.addClass('bottom').removeClass('sticky');
            }
            else
            {
                nav.removeClass('bottom').addClass('sticky')
            }
        }
        else
        {
            nav.removeClass('sticky')
        }

    });

});