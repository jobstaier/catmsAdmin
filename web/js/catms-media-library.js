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
                '<a href="" class="btn btn-primary btn-mini remove-all-marked-confirm">Confirm</a>&nbsp;&nbsp;&nbsp;<a href="#" class="btn btn-inverse btn-mini dismiss">Dismiss</a></div>',
        placement: 'top',
        html: true
    });
    
    $('.dismiss').live('click', function(){
        $('a.remove-all-marked').popover('hide');
    });
    
     $('.remove-all-marked').click(function(){
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
            $.ajax({
                type: 'POST',
                url: URL,
                dataType: 'json',
                data: data,
                success: function(data) {
                    $.each(data.images, function(i, val){
                        var tr = $('.row_'+val);
                        tr.fadeOut(650);
                        window.setTimeout(function(){
                            tr.remove();  

                            showAlert('Remove success!', ' Asset has been removed successfuly.', 'info');
                            
                            if ($('table tbody img').length === 0) {
                                window.location.href = $('.list-home-url').attr('href');
                            }

                        }, 650);  
                    });
                    
                    removeTrigger.html(defText);
                    removeTrigger.removeAttr('disabled');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown){
                    removeTrigger.html(defText);
                    removeTrigger.removeAttr('disabled');
                    showAlert('Delete failure!', errorThrown, 'error');
                }
            });
        }
        
        return false;
    });
});

function showAlert(header, message, type)
{
var html = '<div class="alert alert-'+ type +'">' + 
       '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
       '<strong>'+ header +'</strong>' + message +
       '</div>';

$('.notice-container').append(html);
}