var MenuView = Backbone.View.extend({
    
    el: 'body',
    handler: null,
    cHandler: null,
    
    events: {
        'mouseenter .ajax-get-groups': 'getImageGroups',
        'mouseenter .ajax-get-content-groups': 'getContentGroups',
        'click .ajax-get-groups, .tmp-image-group-url, .ajax-get-content-groups, .tmp-content-group-url': 'preventDefault'
    },
    
    initialize: function() {
        this.handler = $('.ajax-image-groups-preloder').parent();
        this.cHandler = $('.ajax-content-groups-preloder').parent();

        var context = this;
        this.handler.parent().mouseleave(function() {
            context.handler.fadeOut(150);
        });
        
        this.cHandler.parent().mouseleave(function() {
            context.cHandler.fadeOut(150);
        });
        
    },
    
    getImageGroups: function(event) {
        var hovered = $(event.target);
        
        if (!hovered.hasClass('ready')) {
            hovered.addClass('ready');
            var url = hovered.attr('href');
            var self = this;
            
            $.ajax({
                url: url,
                type: 'post',
                data: null,
                context: this,
                success: function(groups){
                    
                    var tmpImageGroupUrl = $('.tmp-image-group-url').attr('href');
                    self.handler.children().remove();

                    if (groups.length !== 0) {
                        $.each(groups, function(i, obj){
                            self.handler.append('<li><a href="' + tmpImageGroupUrl + '/' + obj.id + '"><i class="icon-picture"></i> ' + obj.description  + '</a></li>');
                        });
                        
                        $('#image-groups-submenu').css({
                            'max-height': parseInt($(window).height() - 150) + 'px',
                            'overflow-y': 'scroll'
                        });
                        
                    } else {
                        self.handler.append('<li><a href="#"><i class="icon-remove-sign"></i> No groups defined</a></li>');
                    }
                },
                error:function(XMLHttpRequest, textStatus, errorThrown) {
                    //window.baseView.pinesNotify(Translator.get('global.errorOccured'), errorThrown, 'error');
                }   
            });
        } else {
            this.handler.fadeIn(150);
        }        
    },
    
    getContentGroups: function(event) {
        var hovered = $(event.target);
        
        if (!hovered.hasClass('ready')) {
            hovered.addClass('ready');
            var url = hovered.attr('href');
            var self = this;
            
            $.ajax({
                url: url,
                type: "post",
                data: null,
                context: this,
                success: function(groups){
                    var tmpImageGroupUrl = $('.tmp-content-group-url').attr('href');
                    self.cHandler.children().remove();
          
                    if (groups.length !== 0) {
                        for (slug in groups) {
                            self.cHandler.append('<li><a href="' + tmpImageGroupUrl + '/1/' + slug + '"><i class="icon-tag"></i> ' + groups[slug]  + '</a></li>')
                        }

                        $('#content-groups-submenu').css({
                            'max-height': parseInt($(window).height() - 150) + 'px',
                            'overflow-y': 'scroll'
                        });
                        
                    } else {
                        self.cHandler.append('<li><a href="#"><i class="icon-remove-sign"></i> No groups defined</a></li>')
                    } 
                    //cHandler.hide().fadeIn(150);  
                },
                error:function(XMLHttpRequest, textStatus, errorThrown) {
                     //window.baseView.pinesNotify(Translator.get('global.errorOccured'), errorThrown, 'error');
                }  
            });
        } else {
            this.cHandler.fadeIn(150);
        }        
    },
    
    preventDefault: function() {
        return false;
    }
});

