$('document').ready(function() {						
    var windowWidth = $(window).width() - 981;		
    var sidePadding = windowWidth / 2;	
    $('.scrollup').css('right', (sidePadding - 50) + 'px');	
    $('.scrollup-text').css('right', (sidePadding - 181) + 'px');        
    $(window).scroll(function(){            
            if ($(this).scrollTop() > 100) {                
                    $('.scrollup').fadeIn();            
            } else {                
                    $('.scrollup').fadeOut();            
            }        
    });          
    $('.scrollup, .scrollup-text').click(function(){            
            $("html, body").animate({ scrollTop: 0 }, 600);            
            return false;        
    });	                
    $('.scrollup').hover(function() {				
            $('.scrollup-text').fadeIn();        	
    }, function() {        		
            $('.scrollup-text').fadeOut();        	
    });
        
           
    $(window).resize(function() {
        var windowWidth = $(window).width() - 981;		
        var sidePadding = windowWidth / 2;	
        $('.scrollup').css('right', (sidePadding - 50) + 'px');	
        $('.scrollup-text').css('right', (sidePadding - 181) + 'px'); 
    });
        
});