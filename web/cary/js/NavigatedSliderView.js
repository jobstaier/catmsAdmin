var NavigatedSliderView = Backbone.View.extend({
    el: 'body',
    navigation: '',

    slides: new Array(),
    count: 0,
    currentSlide: 0,
    //autoPlayTime: 7000,
    //pauseTime: 5000,
    initSlide: 0,

    timer: null,

    events: {
        'click #nav-bottom ul li': 'animate',
        'click .prev': 'navigatePrev',
        'click .next': 'navigateNext',
        'mouseenter .part div': 'slideImageIn',
        'mouseleave .part div': 'slideImageOut'
    },

    initialize: function(options) {
        this.setElement(options.el);
        this.navigation = this.$(options.navigation);

        var context = this;
        this.$('#middle div.part').each(function() {
            context.slides.push(this);
            context.count =  context.count + 1;
        });

        this.initialState();
        //this.autoPlay();
    },

    slideImageIn: function(event) {
        var hovered = $(event.currentTarget);

        hovered.find('img').stop(true, false).animate({
            top: '0px'
        }, 350, 'swing');
    },

    slideImageOut: function(event) {
        var hovered = $(event.currentTarget);

        hovered.find('img').stop(true, false).animate({
            top: '-80px'
        }, 350, 'swing');
    },

    initialState: function() {
        this.$('#middle div.part').hide();
        this.$('#middle div.part:eq(' + this.initSlide + ')').show();

        this.setActiveNavigation();
    },

    setActiveNavigation: function(eq) {
        this.navigation.find('li').each(function() {
            $(this).removeClass('active');
        });
        this.navigation.find('li:eq(' + this.currentSlide + ')').addClass('active');
    },

    animate: function(event) {

        window.clearInterval(this.timer);
        if (event) {
            event.preventDefault();
            this.currentSlide = $(event.currentTarget).index();
        }

        this.setActiveNavigation();

        $(this.slides).each(function() {
            $(this).fadeOut(500);
        });

        var context = this;
        window.setTimeout(function() {
            $(context.slides[context.currentSlide]).fadeIn(500);
        }, 600);

        /*
        window.setTimeout(function() {
            context.autoPlay(true, context);
        }, this.pauseTime);
        */
    },

    /*
    autoPlay: function(restart, context) {
        var restart = (restart) ? false : restart;

        if (!restart) {
            var context = this;
        }

        window.clearInterval(context.timer);
        context.timer = window.setInterval(function() {
            context.autoAnimate();
        }, context.autoPlayTime);
    },
    */

    autoAnimate: function() {
        if (this.currentSlide < (this.count - 1)) {
            this.currentSlide = this.currentSlide + 1;
        } else {
            this.currentSlide = 0;
        }

        $(this.slides).each(function() {
            $(this).fadeOut(500);
        });

        this.setActiveNavigation();

        var context = this;
        window.setTimeout(function() {
            $(context.slides[context.currentSlide]).fadeIn(500);
        }, 600);
    },

    navigatePrev: function(event) {
        event.preventDefault();
        window.clearInterval(this.timer);

        if (this.currentSlide - 1 >= 0) {
            this.currentSlide = this.currentSlide - 1;
        } else {
            this.currentSlide = this.count - 1;
        }
        console.log(this.currentSlide);
        this.animate();
    },

    navigateNext: function() {
        event.preventDefault();
        window.clearInterval(this.timer);

        if (this.currentSlide < (this.count - 1)) {
            this.currentSlide = this.currentSlide + 1;
        } else {
            this.currentSlide = 0;
        }
        console.log(this.currentSlide);
        this.animate();
    }
});

$(function() {
    new NavigatedSliderView({
        'el': '#projects',
        'navigation': '#nav-bottom ul'
    });
});