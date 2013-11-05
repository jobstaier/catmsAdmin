var AssetGroupGridView = AssetGridView.extend({
    
    initialize: function() {
        window.modalLoader.showLoader();
        
        this.url = this.$el.find('#getGroupImagesList').attr('href'); 
        this.page = parseInt(this.$el.find('.grid-list').attr('data-view') + 1);
        
        $('.more-btn-container').hide();
        
        this.notice = _.template(
            $("#emptyDatabaseNoticeTemplate").html(), 
            {emptyDatabase: Translator.get('global.emptyDatabase')} 
        );
       
        this.getGridAssets();
    },
    
    renderList: function(data, container) {
        var list = '';
        var dir = $('#dirPath').attr('href');
        var editPath = $('#editPath').attr('href');
        $.each(data.images, function(i, obj){
            list = list + '<li>' + window.fileExtension.renderMimeTypeThumbnail(obj, dir) +
                '<div class="image-grid-btns">' + 
                    '<a class="hide image-id" rel="' + obj.id + '"></a>' +
                    '<div class="edit-form-prototype hide"></div>' +
                    '<a data-placement="top" data-toggle="popover" title="' + Translator.get('global.delete') + '" class="remove-image"  href="' + obj.deletePath + '"><i class="icon-trash"></i></a>' +
                    '<a data-placement="top" data-toggle="tooltip" title="' + Translator.get('global.copySource') + '" href="' + dir + obj.path + '" class="copy-source"><i class="icon-screenshot"></i></a>' +
                    '<a data-placement="top" data-toggle="tooltip" title="' + Translator.get('asset.quickEdit') + '" class="modal-trigger"   href="' + editPath + '/' + obj.id + '"><i class="icon-pencil"></i></a>' +
                    '<a data-placement="top" data-toggle="tooltip" title="' + Translator.get('global.edit') + '" href="' + editPath + '/' + obj.id + '/' + obj.imageGroup +'"><i class="icon-edit"></i><a/>' +
                '</div></li>';
        });
        container.append(list);
        
        container.find('a.single-image').map(function() {
            window.baseView.showLightbox(this);
        });

        if (data.hasMore) {
            $('.more-btn-container').show();
        } else {
            $('.more-btn-container').hide();
        }
    }   
});
