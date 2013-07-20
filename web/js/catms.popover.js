$(function(){
    $('.remove-this').click(function(){
        return false;
    });        

    $('.remove-this').popover({
        content: 
                '<div style="text-align: center;">Are you sure you want to delete this item?<br /><br />' + 
                '<a href="" class="btn btn-primary btn-mini remove-this-confirm">Confirm</a>&nbsp;&nbsp;&nbsp;<a class="btn btn-inverse btn-mini dismiss">Dismiss</a></div>',
        placement: 'right',
        html: true
    });

    $('.dismiss').live('click', function(){
        $('.remove-this').popover('hide');
        return false;
    });

    $('.remove-this-confirm').live('click', function(){
        showLoader();
        $(this).parents('form').submit();
        return false;
    });   
});