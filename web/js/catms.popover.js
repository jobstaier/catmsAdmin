$(function(){
    $('.remove-this, .change-this, .save-this').click(function(){
        return false;
    });        

    $('.remove-this').each(function(){
        $(this).popover({
            content: 
                '<div style="text-align: center;">Are you sure you want to delete this item?<br /><br />' + 
                '<a href="" class="btn btn-primary btn-mini remove-this-confirm">Confirm</a>&nbsp;&nbsp;&nbsp;<a class="btn btn-inverse btn-mini dismiss">Dismiss</a></div>',
            placement: ($(this).attr('data-view')) ? $(this).attr('data-view') : 'right',
            html: true
        });
    });
    
    $('.change-this').each(function(){
        $(this).popover({
            content: 
                '<div style="text-align: center;">Are you sure you want to change this value?<br /><br />' + 
                '<a href="" class="btn btn-primary btn-mini remove-this-confirm">Confirm</a>&nbsp;&nbsp;&nbsp;<a class="btn btn-inverse btn-mini dismiss">Dismiss</a></div>',
            placement: ($(this).attr('data-view')) ? $(this).attr('data-view') : 'right',
            html: true
        });
    }); 
    
    $('.save-this').each(function(){
        $(this).popover({
            content: 
                '<div style="text-align: center;">Are you sure you want to save it?<br /><br />' + 
                '<a href="" class="btn btn-primary btn-mini remove-this-confirm">Confirm</a>&nbsp;&nbsp;&nbsp;<a class="btn btn-inverse btn-mini dismiss">Dismiss</a></div>',
            placement: ($(this).attr('data-view')) ? $(this).attr('data-view') : 'right',
            html: true
        });
    }); 

    $('.dismiss').live('click', function(){
        $('.remove-this').popover('hide');
        $('.change-this').popover('hide');
        $('.save-this').popover('hide');
        return false;
    });

    $('.remove-this-confirm').live('click', function(){
        showLoader();
        $(this).parents('form').submit();
        return false;
    });   
    
/*  
    $('.change-this').click(function(){
        return false;
    });        
*/


/*
    $('.dismiss').live('click', function(){
        $('.change-this').popover('hide');       
        return false;
    });
*/
/*
    $('.remove-this-confirm').live('click', function(){
        showLoader();
        $(this).parents('form').submit();
        return false;
    });
*/
});