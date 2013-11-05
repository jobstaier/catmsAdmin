var ClipboardView = BaseView.extend({

    events: {
        'click .copy-source': 'copyToClipboard'
    },
            
    copyToClipboard: function(event) {
        event.preventDefault();
        text = $(event.currentTarget).attr('href');

        clipboardTemplate = _.template( $("#clipboardModalTemplate").html(), { text: text } );
        $(clipboardTemplate).modal();
        
        return false;
    }
});
