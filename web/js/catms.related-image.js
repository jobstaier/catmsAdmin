$(function(){
    $('.related-images-trigger-container a').click(function(){
        if ($(this).hasClass('active')){
            $(this).removeClass('active');
            $(this).children('i').removeClass('icon-chevron-right').addClass('icon-chevron-left');
            $('.related-images-container').fadeOut(350);
        } else {
            $(this).addClass('active');
            $(this).children('i').removeClass('icon-chevron-left').addClass('icon-chevron-right');
            $('.related-images-container').fadeIn(350);
        }

        if (!$(this).hasClass('ready')){
            $(this).addClass('ready');
            getRelatedImageGroup();
            getRelatedImageInjected();
        }

        return false;
    });
});
        
/**
 * Function to get images from related ImageGroups
 *
 * @method getRelatedImageGroup
 */
function getRelatedImageGroup(){
    $('.loader-gif').show();
    var URL = $('#getRelatedImageGroupUrl').attr('href');
    $.ajax({
        type: 'POST',
        url: URL,
        dataType: 'json',
        data: null,
        success: function(data) {
            $('.loader-gif').hide();

            var count = Object.keys(data).length;
            var container = $('.related-from-group .append-here');

            if (count > 0 ) {
                renderThumbs(data, container);
            } else {
                renderNullResultNotice(container);
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown){
            pinesNotify(Translator.get('global.errorOccured'), errorThrown, 'error');
        }
    });
}

function renderThumbs(data, container){
    var html = '<ul>';
    $.each(data, function(i, val){
        var imgDirectory = $('#getImagesDirectroyUrl').attr('href');
        var editUrl = $('#editImageUrl').attr('href');
        $.each(val, function(id, properties){
            var title = (properties.title !== null) ? properties.title : '';
            html = html + '<li><div class="image-wrapper"><a href="' + imgDirectory + properties.path + '" class="single-image"><img class="img-polaroid" src="' + imgDirectory + 
                    properties.path + '" title="' + properties.title + '"/></a></div>' +
                    '<div class="caption">'+ title +'</div>' +
                    '<a href="' + imgDirectory + properties.path + '" class="btn btn-mini btn-success copy-source">' + Translator.get('global.copySource') + '</a>' + 
                    '<a href="' + editUrl+ '/' + id + '" class="btn edit btn-mini btn-primary">' + Translator.get('global.edit') + '</a>' + 
                    '</li>';
        });
    });
    html = html + '</ul>';
    container.html(html);
}

function renderNullResultNotice(container){
    container.html('<div class="alert" style="margin-top: 10px;">' +
        '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
        '<strong>No relations defined!</strong> Check Content Group properties if you want display apropriate images here.' +
        '</div>');
}
