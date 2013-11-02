var SliderView = Backbone.View.extend({
    el: '',
    navigation: '',

    slides: new Array(),
    count: 0,
    currentSlide: 0,
    autoPlayTime: 7000,
    pauseTime: 5000,
    initSlide: 0,

    timer: null,

    events: {
        'click .navigation li': 'animate'
    },

    initialize: function(options) {
        this.setElement(options.el);
        this.navigation = this.$(options.navigation);

        var context = this;
        this.$('.quotes div.quote').each(function() {
            context.slides.push(this);
            context.count =  context.count + 1;
        });

        this.initialState();

        this.autoPlay();
    },

    initialState: function() {
        this.$('.quotes div.quote').hide();
        this.$('.quotes div.quote:eq(' + this.initSlide + ')').show();

        this.setActiveNavigation();
    },

    setActiveNavigation: function(eq) {
        this.navigation.find('li').each(function() {
            $(this).removeClass('active');
        });
        this.navigation.find('li:eq(' + this.currentSlide + ')').addClass('active');
    },

    animate: function(event) {
        event.preventDefault();
        window.clearInterval(this.timer);

        this.currentSlide = $(event.currentTarget).index();
        this.setActiveNavigation();

        $(this.slides).each(function() {
            $(this).fadeOut(500);
        });

        var context = this;
        window.setTimeout(function() {
            $(context.slides[context.currentSlide]).fadeIn(500);
        }, 600);

        window.setTimeout(function() {
            context.autoPlay(true, context);
        }, this.pauseTime);
    },

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
    }
});

$(function() {
    new SliderView({
        'el': '#referencje',
        'navigation': '.navigation'
    });
});