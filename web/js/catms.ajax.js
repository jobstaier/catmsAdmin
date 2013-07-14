$(document).ready(function(){
    
    /* Image groups */
    var handler = $('.ajax-image-groups-preloder').parent();
    
    $('.ajax-get-groups').hover(function(){

        if (!$(this).hasClass('ready')) {
            $(this).addClass('ready');
            var url = $(this).attr('href');
            $.ajax({
                url: url,
                type: "post",
                data: null,
                success: function(groups){
                    
                    var tmpImageGroupUrl = $('.tmp-image-group-url').attr('href');
                    handler.children().remove();

                    if (groups.length != 0) {
                        for (slug in groups) {
                            handler.append('<li><a href="' + tmpImageGroupUrl + '/1/' + slug + '"><i class="icon-picture"></i> ' + groups[slug]  + '</a></li>')
                        }
                    } else {
                        handler.append('<li><a href="#"><i class="icon-remove-sign"></i> No groups defined</a></li>')
                    } 
                    handler.hide().fadeIn(150);  
                },
                error:function(){}   
            });
        } else {
            handler.fadeIn(150);
        }
    }, function(){});
    
    handler.parent().mouseleave(function() {
        handler.fadeOut(150);
    });
    
    $('.ajax-get-groups, .tmp-image-group-url').click(function() {
        return false;
    });
    /* End image groups */
    
    
    /* Content groups */
    var cHandler = $('.ajax-content-groups-preloder').parent();
    
    $('.ajax-get-content-groups').hover(function(){

        if (!$(this).hasClass('ready')) {
            $(this).addClass('ready');
            var url = $(this).attr('href');
            $.ajax({
                url: url,
                type: "post",
                data: null,
                success: function(groups){
                    var tmpImageGroupUrl = $('.tmp-content-group-url').attr('href');
                    cHandler.children().remove();
          
                    if (groups.length != 0) {
                        for (slug in groups) {
                            cHandler.append('<li><a href="' + tmpImageGroupUrl + '/1/' + slug + '"><i class="icon-book"></i> ' + groups[slug]  + '</a></li>')
                        }
                    } else {
                        cHandler.append('<li><a href="#"><i class="icon-remove-sign"></i> No groups defined</a></li>')
                    } 
                    cHandler.hide().fadeIn(150);  
                },
                error:function(){}   
            });
        } else {
            cHandler.fadeIn(150);
        }
    }, function(){});
    
    cHandler.parent().mouseleave(function() {
        cHandler.fadeOut(150);
    });
    
    $('.ajax-get-content-groups, .tmp-content-group-url').click(function() {
        return false;
    });
    /* End content groups */

});