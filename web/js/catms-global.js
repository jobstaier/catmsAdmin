var loaderGif = '/img/loading.gif';

function showAlert(header, message, type){
var html = '<div class="alert alert-'+ type +'">' + 
       '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
       '<strong>'+ header +'</strong>' + message +
       '</div>';

$('.notice-container').html(html);
}

function showLoader(){
    $.fancybox(
        '<div style="text-align: center"><img src="' + loaderGif + '" /></div>',
        {
            'autoDimensions'	: false,
            'width'         	: 350,
            'height'        	: 70,
            'transitionIn'	: 'none',
            'transitionOut'	: 'none',
            'padding'           : '20px',
            'hideOnOverlayClick': false,
            'showCloseButton'   : false,
            'centerOnScroll'    : true   
        }
    );
}

function closeLoader(){
    $.fancybox.close();
}

function copyToClipboard(text) {
    window.prompt ("Copy to clipboard: Ctrl+C, Enter", text);
}

$(function() {
    $('.copy-source').live('click', function() {
        copyToClipboard($(this).attr('href'));
        return false; 
    });
});
