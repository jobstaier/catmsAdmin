var GlobalView = Backbone.View.extend({
    
    el: 'body',
    
    initialize: function() {
        this.$el.append('<a href="javascript:void(0);" class="showLoader">Show Loader</a>');
    }
});

var ModalLoaderView = GlobalView.extend({
    
    loaderGif: '/img/loading.gif',
    modalLoader: null,
    
    events: {
        'click .showLoader': 'showLoader'
    },
     
    initialize: function() {
        GlobalView.prototype.initialize.apply( this );

        var variables = { loaderGif: this.loaderGif };

        this.$el.append( _.template( $("#loaderTemplate").html(), variables ) );
        this.modalLoader = this.$el.find( '#modalLoader' );
    },
    
    showLoader: function() {
        this.modalLoader.modal({
            backdrop: 'static',
            keyboard: false   
        });
    },
    
    hideLoader: function() {
        this.modalLoader.modal( 'hide' );
    }
});