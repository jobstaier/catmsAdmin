jQuery(document).ready(function($) {                                                       
    $("ul#topnav li").hover(function() { 
        $(this).find("span").show();    
        $(this).addClass("activeMenu");                                                                                   
    } , function() {
        $(this).css({'background': 'none'});
        $(this).find("span").hide();
        $(this).removeClass("activeMenu"); 
    });                             
});