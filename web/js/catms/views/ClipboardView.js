var ClipboardView = BaseView.extend({

    events: {
        'click .copy-source': 'copyToClipboard'
    },

    initialize: function() {

    },
            
    copyToClipboard: function(event) {
        text = $(event.currentTarget).attr('href');

        clipboardTemplate = _.template( $("#clipboardModalTemplate").html(), { text: text } );
        $(clipboardTemplate).modal();
        
        return false;
    }
});
