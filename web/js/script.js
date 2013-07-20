Cufon.replace('#brand, ul.nav li, h2.ca-main, h1.lead-title, .page-header, .middle-menu a');
$(function(){
    $('.content-container').hide();
    $('.content-container').fadeIn(600);
    $('.navbar').hide();
    setTimeout(function(){
        $('.navbar, ul.ca-menu').fadeIn(500);
    }, 200);


    $('.middle-menu .right').click(function(){
        if ($(this).hasClass('active')){
            return false;
        }
        $('.middle-menu .left').removeClass('active');
        $(this).addClass('active');

        $('.jumbotron .lead-title').fadeOut(1000);
        $('#ca-menu-1').animate({
            left: '-1020px',
            opacity: 0
        }, 2000, function(){});
        setTimeout(function(){
            $('#ca-menu-2').animate({
                right: 0,
                opacity: 1
            }, 1000, function(){});
            $('.jumbotron .lead-title').text($('.right-title').text()).fadeIn(1000);
            Cufon.refresh();
        }, 1000);
        return false;
    }); 


    $('.middle-menu .left').click(function(){
        if ($(this).hasClass('active')){
            return false;
        }
        $('.middle-menu .right').removeClass('active');
        $(this).addClass('active');

        $('.jumbotron .lead-title').fadeOut(1000);
        $('#ca-menu-2').animate({
            right: '-1020px',
            opacity: 0
        }, 2000, function(){});
        setTimeout(function(){
            $('#ca-menu-1').animate({
                left: 0,
                opacity: 1
            }, 1000, function(){});
            $('.jumbotron .lead-title').text($('.left-title').text()).fadeIn(1000);
            Cufon.refresh();
        }, 1000);
        return false;
    }); 
    
    var active;
    var side;

    $('.middle-menu div#ca-menu-2 li').each(function(){
        if ($(this).hasClass('slider2-active')) {
            active = $('#ca-menu-2');
            side = '.right';  
        }
    });
    
    $('.middle-menu div#ca-menu-1 li').each(function(){
        if ($(this).hasClass('slider1-active')) {
            active = $('#ca-menu-1');
            side = '.left';  
        }
    });


    
    //Dodanie active dla left- right
    $('.middle-menu ' + side).addClass('active');
    
    console.log(active + ' ' + side);
    
    if (side === '.right') {
        $('#ca-menu-1').animate({
            left: '-1020px',
            opacity: 0
        }, 2000, function(){});
        setTimeout(function(){
            $('#ca-menu-2').animate({
                right: 0,
                opacity: 1
            }, 1000, function(){});
            $('.jumbotron .lead-title').text($('.right-title').text()).fadeIn(1000);
            Cufon.refresh();
        }, 1000);
    } 
    
});


