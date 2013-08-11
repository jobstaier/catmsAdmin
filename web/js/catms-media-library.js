$(function(){
    $('.overlay-loader').hide();
    
    $('.unmark-all').hide();

    $('.mark-all').click(function(){
        $('.mark-it').attr('checked', 'checked');
        $('.unmark-all').show();
        $('.mark-all').hide();
        return false;
    });
    
    $('.unmark-all').click(function(){
        $('.mark-it').removeAttr('checked');
        $('.mark-all').show();
        $('.unmark-all').hide();
        return false;
    });
    
    $('.mark-it').change(function(){
        if ($('.mark-it:checked').length === 0) {
            $('.unmark-all').hide();
        } else {
            $('.unmark-all').show();
        }
        $('.mark-all').show();
    });
    
    
    $('.remove-all-marked').popover({
        content: 
                '<div style="text-align: center;">Are you sure you want to delete all selected items?<br /><br />' + 
                '<a href="" class="btn btn-primary btn-mini remove-all-marked-confirm">Confirm</a>&nbsp;&nbsp;&nbsp;<a class="btn btn-inverse btn-mini dismiss">Dismiss</a></div>',
        placement: 'top',
        html: true
    });
    
    $('.remove-this').popover({
        content: 
                '<div style="text-align: center;">Are you sure you want to delete this item?<br /><br />' + 
                '<a href="" class="btn btn-primary btn-mini remove-this-confirm">Confirm</a>&nbsp;&nbsp;&nbsp;<a class="btn btn-inverse btn-mini dismiss">Dismiss</a></div>',
        placement: 'left',
        html: true
    });
    
    $('.dismiss').live('click', function(){
        $('a.remove-all-marked, .remove-this').popover('hide');
    });
    
    $('.remove-all-marked, .remove-this').click(function(){
        return false;
    });
     
    $('.remove-this-confirm').live('click', function(){
        $(this).parents('form').submit();
        return false;
    });
    
    $('.remove-all-marked-confirm').live('click', function(){
        if ($('.mark-it:checked').length === 0) {
            
        } else {
            var removeTrigger = $('a.remove-all-marked');
            var defText = removeTrigger.html();
            
            removeTrigger.html('<i class="icon-trash"></i> Loading . . .');
            removeTrigger.attr('disabled', 'disabled');
            $('a.remove-all-marked').popover('hide');
            
            var data = {'images': []};
            $('.mark-it:checked').each(function(){
                data.images.push($(this).val()); 
            });

            var URL = removeTrigger.attr('href');
            showLoader();
            
            $.ajax({
                type: 'POST',
                url: URL,
                dataType: 'json',
                data: data,
                success: function(data) {

                    closeLoader();
                    $.each(data.images, function(i, val){
                        
                        var tr = $('.row_'+val);
                        tr.fadeOut(650);
                        window.setTimeout(function(){
                            tr.remove();  

                            pinesNotify('Remove success!', 'Assets has been removed successfuly', 'success');
                            
                            if ($('table tbody img').length === 0) {
                                console.log($('.list-home-url').attr('href'));
                                window.location.href = $('.list-home-url').attr('href');
                            }

                        }, 650);  
                    });
                    
                    removeTrigger.html(defText);
                    removeTrigger.removeAttr('disabled');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown){
                    closeLoader();
                    removeTrigger.html(defText);
                    removeTrigger.removeAttr('disabled');
                    pinesNotify('Delete failure!', errorThrown, 'error');
                }
            });
        }
        
        return false;
    });
});