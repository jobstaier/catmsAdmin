var AssetGridView = Backbone.View.extend({
    el: 'body',
    url: null,
    page: null,
    notice: null,
    noticeSuccessText: Translator.get('crud.asset.updateSuccess'),
    noticeErrorValidationTitle: Translator.get('validation.error'),
    noticeErrorTitle: Translator.get('global.errorOccured'),
    noticeErrorText: Translator.get('crud.asset.updateFailure'),
    noticeSuccessDeleteText: Translator.get('crud.asset.deleteSuccess'),
    
    initialize: function() {
        window.modalLoader.showLoader();
        
        this.url = this.$el.find('#getImagesList').attr('href'); 
        this.page = parseInt(this.$el.find('.grid-list').attr('data-view') + 1);
        
        $('.more-btn-container').hide();
        
        this.notice = _.template(
            $("#emptyDatabaseNoticeTemplate").html(), 
            {emptyDatabase: Translator.get('global.emptyDatabase')} 
        );
       
        this.getGridAssets();
    },
            
    events: {
        'click .modal-trigger': 'showEditModal',
        'click .save-trigger': 'saveModalChanges',
        'click .remove-image-confirm': 'removeImageConfirm',
        'mousedown .remove-image': 'showRemoveImageConfirm',
        'click .remove-image': 'preventDefault',
        'click .dismiss': 'hidePopover',
        'click .more-btn-container a.btn': 'showMore'
    },
            
    getGridAssets: function() {
        var self = this;
        $.ajax({
            type: 'GET',
            url: self.url,
            dataType: 'json',
            data: {'page': self.page},
            context: this,
            success: function(data) {
                if (data.images) {
                    self.renderList(data, $('.grid-list'));
                    $('.grid-list').attr('data-view', self.page);
                } else {
                    self.$el.find('.grid').html(self.notice);
                }
                window.modalLoader.hideLoader();
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
                window.baseView.pinesNotify(self.noticeErrorTitle, errorThrown, 'error');
                window.modalLoader.hideLoader();
            }
        });
    },

    showEditModal: function(event) {
        event.preventDefault();
        this.regenerateForm($(event.target).parents('li').find('.image-id').attr('rel'));
        $('#modalQuickEdit').modal();
    },
            
    saveModalChanges: function(event) {
        event.preventDefault();
        
        var el = $(event.target);
        
        var form = el.parents('#modalQuickEdit').find($('form.inline-edit-form'));
        var data = {
            'asset_form[id]'            : form.find($('input[name="asset_form[id]"]')).val(),
            'asset_form[title]'         : form.find($('input[name="asset_form[title]"]')).val(),
            'asset_form[priority]'      : form.find($('input[name="asset_form[priority]"]')).val(),
            'asset_form[redirect]'      : form.find($('input[name="asset_form[redirect]"]')).val(),
            'asset_form[slug]'          : form.find($('input[name="asset_form[slug]"]')).val()
        };

        var URL = $('#editInlinePath').attr('href');

        var self = this;

        $.ajax({
            type: 'POST',
            url: URL,
            dataType: 'json',
            context: this,
            data: data,
            success: function(data) {
                if(data.result === 'success') {
                    window.baseView.pinesNotify(null, self.noticeSuccessText, 'success');
                    $('#modalQuickEdit').modal('hide');
                } else if(data.result === 'error') {
                    window.baseView.pinesNotify(self.noticeErrorValidationTitle, self.noticeErrorText, 'error');
                    $.each(data.errors, function(key, error) {
                        $.each(error, function(i, message) {
                        window.baseView.pinesNotify(self.noticeErrorTitle, message, 'error');
                        });
                    });
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
                window.baseView.pinesNotify(self.noticeErrorTitle, errorThrown, 'error');
                $('#modalQuickEdit').modal('hide');
            }
        });        
    },
    
    regenerateForm: function(assetId) {
        window.modalLoader.showLoader(true);
        var URL = this.$el.find('#editInlineRegeneratePath').attr('href');
        var self = this;
        
        $.ajax({
            type: 'GET',
            url: URL,
            dataType: 'json',
            context: this,
            data: {'id': assetId},
            success: function(data) {
                $('.modal-body').html(data.editFormPrototype);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
                window.baseView.pinesNotify(self.noticeErrorTitle, errorThrown, 'error');
                $('#modalQuickEdit').modal('hide');
            }
        });    
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
                    '<a data-placement="top" data-toggle="tooltip" title="' + Translator.get('global.edit') + '" href="' + editPath + '/' + obj.id + '"><i class="icon-edit"></i></a>' +
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
    },
            
    removeImageConfirm: function(event) {
        event.preventDefault();
        
        var clicked = $(event.target);
        
        clicked.parents('div.popover').prev('a.remove-image').popover('hide');
        clicked.parents('li').fadeOut(500);
        window.setTimeout(function() {
            clicked.parents('li').remove();
        }, 500);
        
        window.modalLoader.showLoader();
        
        var URL = clicked.data('path');
        var self = this;

        $.ajax({
            type: 'POST',
            url: URL,
            dataType: 'json',
            data: null,
            context: this,
            success: function(data) {
                if(data.result === 'success') {
                    window.baseView.pinesNotify(null, self.noticeSuccessDeleteText, 'success');
                    window.modalLoader.hideLoader();
                } else if(data.result === 'error') {
                    window.baseView.pinesNotify(self.noticeErrorTitle, self.noticeErrorText, 'error');
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
                window.baseView.pinesNotify(self.noticeErrorTitle, errorThrown, 'error');
                window.modalLoader.hideLoader();
            }
        });        
    },
    
    showRemoveImageConfirm: function(event) {
        event.preventDefault();
        var clicked = $(event.currentTarget);
            
        var placement = (clicked.data('placement') !== 'undefined') ? clicked.data('placement') : 'left';
        var url = clicked.attr('href');

        clicked.popover({
            content: 
                    '<div class="delete-popvoer">' + Translator.get('global.deleteConfirm') + '<br />' + 
                    '<a data-path="' + url + '" href="" class="btn btn-primary btn-mini remove-image-confirm">' + Translator.get('global.confirm') + '</a>&nbsp;&nbsp;&nbsp;<a class="btn btn-inverse btn-mini dismiss">' + Translator.get('global.dissmiss') + '</a></div>',
            placement: placement,
            html: true           
        });   
    },
    
    preventDefault: function() {
        return false;
    },
            
    hidePopover: function(event) {
        window.confirmView.dismissRemove(event, '.remove-image');
    },
            
    showMore: function() {
        window.modalLoader.showLoader();
        var page = parseInt($('.grid-list').attr('data-view')) + 1;
        var self = this;

        $.ajax({
            type: 'GET',
            url: self.url,
            dataType: 'json',
            context: this,
            data: {'page': page},
            success: function(data) {
                self.renderList(data, $('.grid-list'));
                window.modalLoader.hideLoader();
                $('.grid-list').attr('data-view', page);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
                window.baseView.pinesNotify(self.noticeErrorTitle, errorThrown, 'error');
                window.modalLoader.hideLoader();                
            }
        });
        return false;
    }
});
