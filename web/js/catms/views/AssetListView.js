var AssetListView = Backbone.View.extend({
    el: '#assetList',
    
    events: {
        'click .mark-all': 'markAll',
        'click .unmark-all': 'unmarkAll',
        'change .mark-it': 'markIt',
        'click .remove-all-marked-confirm': 'removeAllMarked',
        'click .remove-all-marked': 'preventClick',
        'click .dismiss': 'hidePopoverRemoveAll'
    },
    
    initialize: function() {
        this.$el.find('.unmark-all').hide();
        this.$el.find('.remove-all-marked').hide();
        this.popoverRemoveAllMarked();
        this.popoverRemoveThis();
    },
            
    markAll: function() {
        this.$el.find('.mark-it').each(function() {
            $(this).prop('checked', true);;
        });
        this.$el.find('.unmark-all').show();
        this.$el.find('.mark-all').hide();
        this.$el.find('.remove-all-marked').show();
        
        return false;
    },
            
    unmarkAll: function() {
        this.$el.find('.mark-it').each(function() {
            $(this).prop('checked', false);
        });
        this.$el.find('.mark-all').show();
        this.$el.find('.unmark-all').hide();
        this.$el.find('.remove-all-marked').hide();

        return false;
    },
            
    markIt: function() {
        var $ = this.$el;
        $.prop('checked', true);
        
        if ($.find('.mark-it:checked').length === 0) {
            $.find('.unmark-all').hide();
            $.find('.remove-all-marked').hide();
        } else {
            $.find('.remove-all-marked').show();
            $.find('.unmark-all').show();
        }
        $.find('.mark-all').show();
    },
            
    removeAllMarked: function() {
        if (this.$el.find('.mark-it:checked').length > 0) {
            
            var removeTrigger = this.$el.find('a.remove-all-marked');
            var defText = removeTrigger.html();
            
            removeTrigger.html('<i class="icon-trash"></i> ' + Translator.get('global.loading'));
            removeTrigger.attr('disabled', 'disabled');
            this.$el.find('a.remove-all-marked').popover('hide');
            
            var data = {'images': []};
            $('.mark-it:checked').each(function(){
                data.images.push($(this).val()); 
            });

            var URL = removeTrigger.attr('href');
            window.modalLoader.showLoader();
            
            var self = this;
            
            $.ajax({
                type: 'POST',
                url: URL,
                dataType: 'json',
                context: this,
                data: data,
                success: function(data) {

                    window.modalLoader.hideLoader();
                    $.each(data.images, function(i, val){
                        
                        var tr = $('.row_'+val);
                        tr.fadeOut(650);
                        window.setTimeout(function() {
                            tr.remove();  

                            window.baseView.pinesNotify(null, Translator.get('asset.assetsHasBeenRemovedSuccessfuly'), 'success');
                            
                            if ($('table tbody img').length === 0) {
                                window.location.href = $('.list-home-url').attr('href');
                            }
                            
                            self.checkButtons(self);
                            
                        }, 650);  
                    });
                    
                    removeTrigger.html(defText);
                    removeTrigger.removeAttr('disabled');
                    
                },
                error: function(XMLHttpRequest, textStatus, errorThrown){
                    window.modalLoader.hideLoader();
                    removeTrigger.html(defText);
                    removeTrigger.removeAttr('disabled');
                    window.baseView.pinesNotify(Translator.get('global.errorOccured'), errorThrown, 'error');
                }
            }); 
            
        }
        
        return false;
    },
    
    preventClick: function() {
        return false;
    },
            
    hidePopoverRemoveAll: function(event) {
        this.$el.find('.remove-all-marked').popover('hide');
    },
            
    checkButtons: function(context) {
        if (context.$el.find('.mark-it:checked').length === 0) {
            context.$el.find('.remove-all-marked').hide();
            context.$el.find('.unmark-all').hide();            
        }
    },
            
    popoverRemoveAllMarked: function() {
        variables = {
            'deleteConfirmAll': Translator.get('global.deleteConfirmAll'),
            'confirm': Translator.get('global.confirm'),
            'dismiss': Translator.get('global.dissmiss')
        };
        
        popoverTemplate = _.template( $("#popoverRemoveAllTemplate").html(), variables );
        
        this.$el.find('.remove-all-marked').popover({
            content: popoverTemplate,
            placement: 'top',
            html: true
        });
    },
            
    popoverRemoveThis: function() {
        variables = {
            'deleteConfirm': Translator.get('global.deleteConfirm'),
            'confirm': Translator.get('global.confirm'),
            'dismiss': Translator.get('global.dissmiss')
        };
        
        popoverTemplate = _.template( $("#popoverRemoveThisTemplate").html(), variables );
        
        this.$el.find('.remove-all-marked').popover({
            content: popoverTemplate,
            placement: 'left',
            html: true
        });
    }            
});