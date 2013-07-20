$(function(){
    $('.archive-container').hide();
    
    $('.archive-trigger-container a').click(function(){
        console.log('Click');
        
        if ($(this).hasClass('active')){
            $(this).removeClass('active');
            $(this).children('i').removeClass('icon-chevron-right').addClass('icon-chevron-left');
            /*
            $('.archive-container').animate({
                right: '-592px'
            }, 1000, function(){});
            */
            $('.archive-container').fadeOut(500);
        } else {
            $(this).addClass('active');
            $(this).children('i').removeClass('icon-chevron-left').addClass('icon-chevron-right');
            /*
            $('.archive-container').css({
                right: '0px'
            }, 1000, function(){});
            */
            $('.archive-container').fadeIn(500);
        }

        return false;
    });
});