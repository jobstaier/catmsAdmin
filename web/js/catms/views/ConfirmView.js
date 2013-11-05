var ConfirmView = BaseView.extend({

    events: {
        'click .remove-this-form': 'preventDefault',
        'click .remove-this-confirm': 'confirmRemove',
        'click .remove-this-form-confirm': 'confirmFormRemove',
        'click a.dismiss': 'dismissRemove',
        'click .dismiss-form': 'dismissFormRemove'
    },

    initialize: function() {
        BaseView.prototype.initialize.apply(this);
        $('.remove-this').each(function(){
            $(this).popover({
                content: 
                    '<div style="text-align: center;">' + Translator.get('global.deleteConfirm') + '<br /><br />' + 
                    '<a href="" class="btn btn-primary btn-mini remove-this-confirm">' + Translator.get('global.confirm') + '</a>&nbsp;&nbsp;&nbsp;<a class="btn btn-inverse btn-mini dismiss">' + Translator.get('global.dissmiss') + '</a></div>',
                placement: ($(this).attr('data-view')) ? $(this).attr('data-view') : 'right',
                html: true
            });
        });

        $('.remove-this-form').each(function(){
            $(this).popover({
                content:
                    '<div style="text-align: center;">' + Translator.get('global.deleteConfirm') + '<br /><br />' +
                        '<a href="" class="btn btn-primary btn-mini remove-this-form-confirm">' + Translator.get('global.confirm') + '</a>&nbsp;&nbsp;&nbsp;<a class="btn btn-inverse btn-mini dismiss-form">' + Translator.get('global.dissmiss') + '</a></div>',
                placement: ($(this).attr('data-view')) ? $(this).attr('data-view') : 'right',
                html: true
            });
        });

        $('.change-this').each(function(){
            $(this).popover({
                content: 
                    '<div style="text-align: center;">Are you sure you want to change this value?<br /><br />' + 
                    '<a href="" class="btn btn-primary btn-mini remove-this-confirm">Confirm</a>&nbsp;&nbsp;&nbsp;<a class="btn btn-inverse btn-mini dismiss">Dismiss</a></div>',
                placement: ($(this).attr('data-view')) ? $(this).attr('data-view') : 'right',
                html: true
            });
        }); 

        $('.save-this').each(function(){
            $(this).popover({
                content: 
                    '<div style="text-align: center;">Are you sure you want to save it?<br /><br />' + 
                    '<a href="" class="btn btn-primary btn-mini remove-this-confirm">Confirm</a>&nbsp;&nbsp;&nbsp;<a class="btn btn-inverse btn-mini dismiss">Dismiss</a></div>',
                placement: ($(this).attr('data-view')) ? $(this).attr('data-view') : 'right',
                html: true
            });
        }); 

        $('body').on('click', 'a.confirm', function(){
            $('.remove-this').popover('hide');
            $('.remove-this-form').popover('hide');
            $('.change-this').popover('hide');
            $('.save-this').popover('hide');
            return false;
        });
        
    },

    preventDefault: function(event) {
        event.preventDefault();
    },

    confirmFormRemove: function(event) {
        event.preventDefault();
        $(event.currentTarget).parents('form').submit();
        window.modalLoader.showLoader();
    },

    confirmRemove: function(event) {
        $(event.target).parents('form').submit();
        window.modalLoader.showLoader();
        return false;
    },

    dismissRemove: function(event, triggerClass) {
        var triggerClass = (triggerClass) ? triggerClass : '.remove-this';

        $(event.target).parents('.popover').prev(triggerClass).popover('hide');
    },

    dismissFormRemove: function(event) {
        $(event.target).parents('.popover').prev('.remove-this-form').popover('hide');
    }
});