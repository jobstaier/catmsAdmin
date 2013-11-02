var ProjectDetailsView = Backbone.View.extend({
    el: 'body',

    events: {
        'click #projects .projects .part div': 'showDetails',
        'click #close': 'closeDetails',
        'click .p-right': 'showNextDetails',
        'click .p-left': 'showPrevDetails'
    },

    template: null,
    data: new Array(),
    activeDetail: 0,

    initialize: function() {
        //this.template =  _.template($("#projectDetails").html(), {});
        console.log('Init');

        var context = this;
        this.$('.part div').each(function() {
            var nodeValues = {
                'title': $(this).find('em.title').text(),
                'lead': $(this).find('i.lead').text(),
                'createdFor': $(this).find('i.createdFor').text(),
                'img': $(this).find('img').attr('alt')
            };

            context.data.push(nodeValues);
        });
    },

    showDetails: function(event) {
        window.baseView.moveOutAllPage();
        window.baseView.animateButtons('hide', 'right');
        window.baseView.animateButtons('hide', 'left');

        var node = $(event.currentTarget);

        this.activeDetail = (($(event.currentTarget).parent().index() + 1) * 3) - (3 - $(event.currentTarget).index());

        this.template =  _.template($("#projectDetails").html(), {
            'title': node.find('em.title').text(),
            'lead': node.find('i.lead').text(),
            'createdFor': node.find('i.createdFor').text(),
            'img': node.find('img').attr('alt')
        });

        var context = this;
        window.setTimeout(function() {
            context.$el.attr('id', 'project-detail');
            context.$('#projects-detail').html(context.template);
            context.$('#projects-detail').hide();
            context.$('.fixed').attr('id', 'top-top');
            this.$('#projects-detail').fadeIn(500);
        }, 500);
    },

    closeDetails: function(event) {
        this.$('.fixed').attr('id', '');
        this.$el.attr('id', '');

        window.baseView.animateButtons('show', 'right');
        window.baseView.animateButtons('show', 'left');

        this.$('#projects-detail').fadeOut(500);

        if (event) {
            window.setTimeout(function() {
                window.baseView.showProjects();
            }, 500);
        }
    },

    showActiveDetails: function() {
        var context = this;
        var dataToLoad = {
            'title': context.data[context.activeDetail].title,
            'lead': context.data[context.activeDetail].lead,
            'createdFor': context.data[context.activeDetail].createdFor,
            'img': context.data[context.activeDetail].img
        };

        context.$el.attr('id', 'project-detail');
        context.$('.fixed').attr('id', 'top-top');

        context.$('#projects-detail #project #title').animate({
            opacity: 0
        }, 750, 'swing');

        context.$('#projects-detail #project #image').animate({
            opacity: 0
        }, 750, 'swing');


        window.setTimeout(function() {
            context.template =  _.template($("#projectDetails").html(), dataToLoad);
            context.$('#projects-detail').html(context.template);

            context.$('#projects-detail #project #title').css('top', '-100px')
            context.$('#projects-detail #project #title').animate({
                top: '30px',
                opacity: 1
            }, 750, 'swing');

            context.$('#projects-detail #project #image').css('left', '-1000px')
            context.$('#projects-detail #project #image').animate({
                left: '40px',
                opacity: 1
            }, 450, 'swing');

            console.log(context.activeDetail);

        }, 750);
    },

    showNextDetails: function(event) {
        if (this.activeDetail + 1 < this.data.length) {
            this.activeDetail = this.activeDetail + 1;
        } else {
            this.activeDetail = 0;
        }
        this.showActiveDetails();
    },

    showPrevDetails: function(event) {
        if (this.activeDetail - 1 >= 0) {
            this.activeDetail = this.activeDetail - 1;
        } else {
            this.activeDetail = this.data.length - 1;
        }
        this.showActiveDetails();
    }



});

$(function() {
    window.projectDetails = new ProjectDetailsView();
});