var FileExtensionView = Backbone.View.extend({
    el: 'body',
    
    renderMimeTypeThumbnail: function(obj, dir) {
        var title = (obj.title) ? obj.title : '- - -';
        
        if (obj.mimeType === 'image/jpeg' || obj.mimeType === 'image/png') {
            return  '<a href="' + dir + obj.path + '" class="thumbnail single-image">' +
                    '<img style="max-width: 160px;" src="/' + obj.thumb + '"/>' +
                    '</a>';  
        } else

        if (obj.mimeType === 'application/pdf') {
            return '<a href="' + dir + obj.path + '" class="pdf-icon-48 media-icon"></a><i>' + title + '</i>';
        } else

        if (obj.mimeType === 'application/x-rar') {
            return '<a href="' + dir + obj.path + '" class="rar-icon-48 media-icon"></a><i>' + title + '</i>';
        } else

        if (obj.mimeType === 'application/zip') {
            return '<a href="' + dir + obj.path + '" class="zip-icon-48 media-icon"></a><i>' + title + '</i>';
        } else

        return '<a href="' + dir + obj.path + '" class="thumbnail single-image"</a><i>' + title + '</i>';        
    }
});