var RelatedImagesView = Backbone.View.extend({
    el: 'body',

    events: {
        'click .related-images-trigger-container a': 'getRelatedImages',
        'click .single-image-dynamic': 'showDynamicColorbox'
    },

    initialize: function() {

    },

    getRelatedImages: function(event) {
        event.preventDefault();

        var clicked = $(event.currentTarget);

        if (clicked.hasClass('active')){
            clicked.removeClass('active');
            clicked.children('i').removeClass('icon-chevron-right').addClass('icon-chevron-left');
            $('.related-images-container').fadeOut(350);
        } else {
            clicked.addClass('active');
            clicked.children('i').removeClass('icon-chevron-left').addClass('icon-chevron-right');
            $('.related-images-container').fadeIn(350);
        }

        if (!clicked.hasClass('ready')){
            clicked.addClass('ready');
            this.getRelatedImageGroup();
        }

    },

    getRelatedImageGroup: function() {
        $('.loader-gif').show();

        var URL = $('#getRelatedImageGroupUrl').attr('href');
        var context = this;

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
                    context.renderThumbs(data, container);
                } else {
                    context.renderNullResultNotice(container);
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
                window.baseView.pinesNotify(null, errorThrown, 'error');
            }
        });
    },

    renderThumbs: function(data, container) {
        var html = '<ul>';
        $.each(data, function(i, val){
            var imgDirectory = $('#getImagesDirectroyUrl').attr('href') + 'system-thumbs/sThmb_';
            var imgDirectoryFull = $('#getImagesDirectroyUrl').attr('href') + 'media-library/';

            var editUrl = $('#editImageUrl').attr('href');
            $.each(val, function(id, properties){
                var title = (properties.title !== null) ? properties.title : properties.path;
                html = html + '<li><div class="image-wrapper"><a href="' + imgDirectoryFull + properties.path + '" class="single-image-dynamic"><img class="img-polaroid" src="' + imgDirectory +
                    properties.path + '" title="' + properties.title + '"/></a></div>' +
                    '<div class="caption">'+ title +'</div>' +
                    '<a href="' + imgDirectory + properties.path + '" class="btn btn-mini btn-success copy-source" data-original-title="' + Translator.get('global.copySource') +  '">' + Translator.get('global.copySource') + '</a>' +
                    '<a href="' + editUrl + '/' + id + '/' + properties.group + '" class="btn edit btn-mini btn-primary">' + Translator.get('global.edit') + '</a>' +
                    '</li>';
            });
        });
        html = html + '</ul>';
        container.html(html);
    },

    renderNullResultNotice: function(container) {
        container.html('<div class="alert" style="margin-top: 10px;">' +
            '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
            '<strong>' + Translator.get('relatedImages.noRelationsDefined') + '</strong> ' + Translator.get('relatedImages.checkOptions') +
            '</div>');
    },

    showDynamicColorbox: function(event) {
        event.preventDefault();

        $(event.currentTarget).colorbox({
            'close': Translator.get('global.close')
        });
    }

});
