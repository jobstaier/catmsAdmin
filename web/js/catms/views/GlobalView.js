var GlobalView = Backbone.View.extend({
    
    el: 'body',
    
    initialize: function() {
        console.log('GlobalView ready!');
        this.$el.append('<a href="javascript:void(0);" class="showLoader">Show Loader</a>');
    }
});

var ModalLoaderView = GlobalView.extend({
    
    loaderGif: '/img/loading.gif',
    modalLoaderHtml: '',
    modalLoader: null,
    
    events: {
        'click .showLoader': 'showLoader'
    },
     
    initialize: function() {
        console.log('ModalLoader view ready!');
        
        GlobalView.prototype.initialize.apply(this);
        
        this.modalLoaderHtml = 
            '<div id="modalLoader" class="modal fade">' + '\
                <div class="modal-body">' + 
                    '<legend>Proszę czekać . . .</legend>' +
                    '<img src="' + this.loaderGif + '" />' + 
                '</div>' + 
            '</div>';
    },
    
    showLoader: function() {
        this.$el.append(this.modalLoaderHtml);
        this.modalLoader = this.$el.find('#modalLoader');
        this.modalLoader.modal({
            backdrop: 'static',
            keyboard: false   
        });
    },
    
    hideLoader: function() {
        this.modalLoader.modal('hide');
    }
});