var ConfirmView = BaseView.extend({

    initialize: function() {
        BaseView.prototype.initialize.apply(this);
    },

    events: {
       'click .remove-this-confirm': 'confirmRemove',
       'click a.dismiss': 'dismissRemove'
    },

    confirmRemove: function(event) {
        $(event.target).parents('form').submit();
        window.modalLoader.showLoader();
        return false;
    },
            
    dismissRemove: function(event) {
        console.log('Im hrere');
        $(event.target).parents('.popover').prev('.remove-this').popover('hide');
    }
});