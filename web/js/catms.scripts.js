$(document).ready(function(){
    /* Modal form confirm */
    $('a.confirm').live('click', function(){
        $(this).parents('form').submit();
        console.log('Confirm');
        return false;
    });
    // End
    
    $('.popoverButton').popover();

    $("a.single-image").live('mouseenter', function(){
        $(this).fancybox();
    });
    
    $('.show-loader').button();

    $('.show-loader').click(function() {
        $(this).button('loading');
    });
    
    $('.history-trigger-container a').click(function(){
        if ($(this).hasClass('active')){
            $(this).removeClass('active');
            $(this).children('i').removeClass('icon-chevron-right').addClass('icon-chevron-left');
            $('.history-container').fadeOut(350);
        } else {
            $(this).addClass('active');
            $(this).children('i').removeClass('icon-chevron-left').addClass('icon-chevron-right');
            $('.history-container').fadeIn(350);
        }

        if (!$(this).hasClass('ready')){
            $(this).addClass('ready');
            getHistory();
        }

        return false;
    });
});

function getHistory(){
    $('.loader-history-gif').show();
    var URL = $('#getHistoryUrl').attr('href');
    $.ajax({
        type: 'POST',
        url: URL,
        dataType: 'json',
        data: null,
        success: function(data) {
            $('.loader-history-gif').hide();
            var html = '<ul>';
            $.each(data, function(i, val){
                var li = '<li>' + val + '</li>';
                html = html + li;
            });
            html = html + '<ul>';
            $('.append-history-here').html(html);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown){
            alert(errorThrown);
        }
    });
}