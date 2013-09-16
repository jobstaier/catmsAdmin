function showAlert(header, message, type){
var html = '<div class="alert alert-'+ type +'">' + 
       '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
       '<strong>'+ header +'</strong> ' + message +
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
    var modalStr =
        '<div id="promptModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">' +
            '<div class="modal-header">' +
                '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>' +
                '<h3 id="myModalLabel"><i class="icon-large icon-scissors"></i> Copy Source</h3>' +
            '</div>' +
            '<div class="modal-body" style="text-align: center;">' +
                '<p>Copy to clipboard: Ctrl+A, Ctrl+C</p>' +
                '<input class="span6" style="text-align:center" type="text" value="' + text + '">' +
            '</div>' +
            '<div class="modal-footer">' +
                '<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>' +
            '</div>' +
        '</div>';
        
    $(modalStr).modal();

    //window.prompt ("Copy to clipboard: Ctrl+C, Enter", text);
}

function renderMimeTypeThumbnail(obj, dir) {
    if (obj.mimeType === 'image/jpeg' || obj.mimeType === 'image/png') {
        return  '<a href="' + dir + obj.path + '" class="thumbnail single-image">' +
                '<img style="max-width: 160px;" src="/' + obj.thumb + '"/>' +
                '</a>';  
    } else
    
    if (obj.mimeType === 'application/pdf') {
        return '<a href="' + dir + obj.path + '" class="pdf-icon-48 media-icon"></a><i>' + obj.title + '</i>';
    } else
    
    if (obj.mimeType === 'application/x-rar') {
        return '<a href="' + dir + obj.path + '" class="rar-icon-48 media-icon"></a><i>' + obj.title + '</i>';
    } else
    
    if (obj.mimeType === 'application/zip') {
        return '<a href="' + dir + obj.path + '" class="zip-icon-48 media-icon"></a><i>' + obj.title + '</i>';
    } else
    
    return '<a href="' + dir + obj.path + '" class="thumbnail single-image"</a><i>' + obj.title + '</i>';
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

function pinesNotify(title, text, type) {
    $.pnotify({
        title: title,
        text: text,
        type: type
    });
}

function showModalLoader() {
    $('.modal-body').html('<div class="modal-loader" style="text-align: center"><img src="' + loaderGif + '" /></div>');
}
