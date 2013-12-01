var BaseView = Backbone.View.extend({
    
    el: 'body',
    
    events: {
        'click .set-locale': 'setLocale',
        'click .show-btn-loader': 'showButtonLoader',
        'click .history-trigger-container a': 'showHistory',
        'click .show-submit-loader' : 'showSubmitLoader'
    },
    
    initialize: function() {
        this.$el.tooltip({
            selector: '[data-toggle=tooltip]'
        });
        
        this.$el.find('.selectpicker').selectpicker();
        this.$el.find('popoverButton').popover();
        
        this.cleanErrorFields();
        
        var context = this;
        this.$el.find('a.single-image').map(function() {
            context.showLightbox(this);
        });

        this.$('input[type="checkbox"]').checkbox();
    },

    setLocale: function(event) {

        var loader = new ModalLoaderView();
        loader.showLoader();

        var URL = $(event.target).attr('href');
        var self = this;

        $.ajax({
            type: 'GET',
            url: URL,
            dataType: 'json',
            data: null,
            context: this,
            success: function(data) {
                window.location.reload(true);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
                self.pinesNotify(Translator.get('global.errorOccured'), errorThrown, 'error');
            }
        });

        return false;
    },
    
    showLightbox: function(el) {
        $(el).colorbox({});            
    },
            
    pinesNotify: function(title, text, type) {
        $.pnotify({
            title: title,
            text: text,
            type: type
        });
    },
            
    showAlert: function(header, message, type) {

        var html = '<div class="alert alert-'+ type +'">' + 
               '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
               '<strong>'+ header +'</strong> ' + message +
               '</div>';

        this.$el.find('.notice-container').html(html);

    },
            
    showButtonLoader: function(event) {
        $(event.target).button('loading');
    },
            
    showSubmitLoader: function(event) {
        $(event.target).parents('form').submit(function() {
            window.modalLoader.showLoader();
        });
    },
            
    showHistory: function(event) {
        var clicked = $(event.target);
        if (clicked.hasClass('active')){
            clicked.removeClass('active');
            clicked.children('i').removeClass('icon-chevron-right').addClass('icon-chevron-left');
            $('.history-container').fadeOut(350);
        } else {
            clicked.addClass('active');
            clicked.children('i').removeClass('icon-chevron-left').addClass('icon-chevron-right');
            $('.history-container').fadeIn(350);
        }

        if (!clicked.hasClass('ready')){
            clicked.addClass('ready');
            this.getHistory();
        }

        return false;
    },
                  
    getHistory: function() {
        $('.loader-history-gif').show();
        var URL = $('#getHistoryUrl').attr('href');
        var context = this;
        
        $.ajax({
            type: 'POST',
            url: URL,
            dataType: 'json',
            data: null,
            success: function(data) {
                $('.loader-history-gif').hide();
                var html = '<ul>';
                $.each(data, function(i, val){
                    var li = '<li>' + val + '</li>';
                    html = html + li;
                });
                html = html + '<ul>';
                $('.append-history-here').html(html);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
                context.pinesNotify(Translator.get('global.errorOccured'), errorThrown, 'error');
            }
        });
    },
    
    cleanErrorFields: function() {
        var container = $('.error-field ul');
        var msg = container.text();
        container.remove();
        $('.error-field').append(msg);
    }
    
});

var ModalLoaderView = BaseView.extend({
    
    loaderGif: '/img/catms/loading.gif',
    modalLoader: null,
    
    events: {
        'click .showLoader': 'showLoader'
    },
     
    initialize: function() {
        BaseView.prototype.initialize.apply(this);

        var variables = { loaderGif: this.loaderGif };

        this.$el.append( _.template( $("#loaderTemplate").html(), variables ) );
        this.modalLoader = this.$el.find( '#modalLoader' );
    },
    
    showLoader: function(modalAction) {
        var modalAction = (modalAction) ? modalAction : false;

        if (modalAction) {
            $('.modal-body').html(
                '<div class="modal-loader">' + 
                    '<img src="' + this.loaderGif + '" />' + 
                '</div>'
            );            
        } else {
            this.modalLoader.modal({
                backdrop: 'static',
                keyboard: false   
            });
        }
    },
    
    hideLoader: function() {
        this.modalLoader.modal( 'hide' );
    }
});