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

function renderMimeTypeThumbnail(obj, dir) {
    if (obj.mimeType === 'image/jpeg' || obj.mimeType === 'image/png') {
        return  '<a href="' + dir + obj.path + '" class="thumbnail single-image">' +
                '<img style="max-width: 160px;" src="/' + obj.thumb + '" title="' + obj.title + '"/>' +
                '</a>';  
    } else
    
    if (obj.mimeType === 'application/pdf') {
        return '<a href="' + dir + obj.path + '" class="pdf-icon-48 media-icon"></a><i>' + obj.title + '</i>';
    } else
    
    if (obj.mimeType === 'application/x-rar') {
        return '<a href="' + dir + obj.path + '" class="rar-icon-48 media-icon">' + obj.title + '</a>';
    } else
    
    if (obj.mimeType === 'application/zip') {
        return '<a href="' + dir + obj.path + '" class="zip-icon-48 media-icon">' + obj.title + '</a>';
    } else
    
    return '<a href="' + dir + obj.path + '" class="thumbnail single-image">' + obj.title + '</a>';
}


$(function() {
    $('.copy-source').live('click', function() {
        copyToClipboard($(this).attr('href'));
        return false; 
    });
    
    $('body').tooltip({
        selector: '[data-toggle=tooltip]'
    });
    
    $('.selectpicker').selectpicker();
});
