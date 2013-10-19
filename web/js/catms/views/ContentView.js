var ContentView = Backbone.View.extend({
    el: 'body',
    
    initialize: function() {
        this.$el.find('.archive-container').hide();
    },
            
    events: {
        'click a.sample-trigger': 'getSampleContent',
        'click .archive-trigger-container a': 'catchArchiveButtonState',
        'click .import-archive': 'importArchive'
    },
    
    getSampleContent: function() {
        eq = Math.floor((Math.random()*6));

        tinymce.editors[0].execCommand(
            'mceSetContent', 
            false, 
            $('.sample-content-container div:eq(' + eq + ')').html()
        );
    },
            
    catchArchiveButtonState: function(event) {
        var btn = $(event.target);

        if (btn.hasClass('active')){
            btn.removeClass('active');
            btn.children('i').removeClass('icon-chevron-right').addClass('icon-chevron-left');
            $('.archive-container').fadeOut(500);
        } else {
            btn.addClass('active');
            btn.children('i').removeClass('icon-chevron-left').addClass('icon-chevron-right');
            $('.archive-container').fadeIn(500);
        }

        return false;
    },
    
    importArchive: function(event) {
        event.preventDefault();
        var trigger = $(event.currentTarget);
        var URL = trigger.attr('href');
        var context = this;
        window.modalLoader.showLoader();
        
        $.ajax({
            type: 'GET',
            url: URL,
            dataType: 'json',
            data: null,
            success: function(data) {
                context.importContent(data);
                window.modalLoader.hideLoader();
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
                window.baseView.pinesNotify(Translator.get('global.errorOccured'), errorThrown, 'error');
                window.modalLoader.hideLoader();
            }
        });
    },
    
    importContent: function(data) {
        $('#catms_adminbundle_contentmanagertype_slug').val(data.slug);
        $('#catms_adminbundle_contentmanagertype_description').val(data.description);
        $('#catms_adminbundle_contentmanagertype_priority').val(data.priority);

        tinymce.editors[0].execCommand('mceSetContent', false, data.fullText);

        try {
            content = (data.shortText !== null) ? data.shortText : '';
            tinymce.editors[1].execCommand('mceSetContent', false, content);
        } catch(err) {
            console.log(err);
        } 
    }
});
