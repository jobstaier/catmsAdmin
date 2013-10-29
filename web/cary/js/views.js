var BaseView = Backbone.View.extend({
    el: 'body',

    moveLeftBtn: null,
    moveRightBtn: null,

    moveBgStep: 40,
    moveBgSpeed: 500,
    showNavButtonsSpeed: 400,
    backgroundPosition: null,

    currentPage: 0,
    prevPage: null,
    homepage: '',

    pages: [
        'homepage',
        'projects',
        'reference',
        'contact'
    ],

    events: {
        'click .right': 'moveRight',
        'click .left': 'moveLeft',
        'keydown': 'logKey'
    },

    initialize: function() {
        this.moveRightBtn = this.$el.find('.right');
        this.moveLeftBtn = this.$el.find('.left');

        this.homepage = this.$el.find('#homepage').hide();
        this.projects = this.$el.find('#projects').hide();
        this.reference = this.$el.find('#referencje').hide();
        this.contact = this.$el.find('#kontakt').hide();

        this.initialState();
    },

    logKey: function(event) {
        if (event.keyCode == 39 && this.currentPage <= 3) {
            this.moveRight();
        } else if (event.keyCode == 37 && this.currentPage > 0) {
            this.moveLeft();
        }
    },

    initialState: function() {

        this.moveLeftBtn.css({
            'left': '-90px',
            'opacity': 0
        });

        this.moveRightBtn.css({
            'right': '-90px',
            'opacity': 0
        });

        this.moveRightBtn.animate({
            'right': '0px',
            'opacity': 1
        }, this.showNavButtonsSpeed);

        this.animateButtons('show', 'right');

        this.$el.css({
            'background-position-x': '250px'
        });

        this.$el.animate({
            backgroundPositionX: '0px'
        }, 750);
        this.backgroundPosition = 0;

        this.showHomepage();
    },

    moveRight: function() {
        this.prevPage = this.currentPage;
        var nextPage = this.currentPage + 1;

        if (nextPage <= 3) {
            this.currentPage = nextPage;
            this.showPage(nextPage);
            if (nextPage == 3) {
                this.animateButtons('hide', 'right');
            }

            this.backgroundPosition = this.backgroundPosition - this.moveBgStep;
            this.$el.animate({
                backgroundPositionX: this.backgroundPosition + 'px'
            }, this.moveBgSpeed);

        } else {
            this.animateButtons('hide', 'right');
        }

        this.animateButtons('show', 'left');
    },

    moveLeft: function() {
        this.prevPage = this.currentPage;
        var prevPage = this.currentPage - 1;

        if (prevPage >= 0) {
            this.currentPage = prevPage;
            this.showPage(prevPage);
            if (prevPage == 0) {
                this.animateButtons('hide', 'left');
            }

            this.backgroundPosition = this.backgroundPosition - this.moveBgStep;
            this.$el.animate({
                backgroundPositionX: this.backgroundPosition
            }, this.moveBgSpeed);
        } else {
            this.animateButtons('hide', 'left');
        }

        this.animateButtons('show', 'right');
    },

    showPage: function(dataPage) {
        String.prototype.capitalize = function() {
            return this.charAt(0).toUpperCase() + this.slice(1);
        }

        var page = this.pages[dataPage].capitalize();

        var funcCall = 'this.show' + page + '()';

        this.moveOutPage();
        var ret = eval(funcCall);
    },

    moveOutPage: function() {
        if (this.currentPage == 0)  {
            eval('this.hide' + this.pages[1].capitalize() + '()');
        } else if (this.currentPage > 0 && this.currentPage < 3) {
            if (this.currentPage > this.prevPage) {
                eval('this.hide' + this.pages[this.currentPage - 1].capitalize() + '()');
            } else {
                eval('this.hide' + this.pages[this.currentPage + 1].capitalize() + '()');
            }
        } else if (this.currentPage == 3) {
            eval('this.hide' + this.pages[this.currentPage - 1].capitalize() + '()');
        }
    },

    showHomepage: function() {
        this.homepage.show();
        this.homepage.css({
            'position': 'absolute',
            'top': '0px',
            'opacity': 0
        });

        var context = this;
        window.setTimeout(function() {
            context.homepage.animate({
                'top': '70px',
                opacity: 1
            }, 750);
        }, 750);
    },

    hideHomepage: function() {
        var context = this;
        window.setTimeout(function() {
            context.homepage.hide();
        }, 750);

        this.homepage.animate({
            'top': '0px',
            opacity: 0
        }, 750);
    },

    showProjects: function() {
        this.projects.css({
            bottom: '0px',
            position: 'absolute',
            'opacity': 0
        });

        var context = this;
        window.setTimeout(function() {
            context.projects.show();
            context.projects.animate({
                'top': '70px',
                opacity: 1
            }, 750);
        }, 750);

    },

    hideProjects: function() {
        var context = this;
        window.setTimeout(function() {
            context.projects.hide();
        }, 750);

        this.projects.animate({
            'top': '0px',
            opacity: 0
        }, 750);
    },

    showReference: function() {
        this.reference.css({
            left: '0px',
            position: 'absolute',
            'opacity': 0
        });

        var context = this;
        window.setTimeout(function() {
            context.reference.show();
            context.reference.animate({
                'left': '400px',
                opacity: 1
            }, {
                duration: 1500,
                easing: 'easeOutElastic'
            });
        }, 750);
    },

    hideReference: function() {
        var context = this;
        window.setTimeout(function() {
            context.reference.hide();
        }, 750);

        this.reference.animate({
            left: '0px',
            'opacity': 0
        }, {
            duration: 750,
            easing: 'easeOutCirc'
        });
    },

    showContact: function() {
        var context = this;
        window.setTimeout(function() {
            context.contact.fadeIn(750);
        }, 750);
    },

    hideContact: function() {
        this.contact.fadeOut(750);
    },

    animateButtons: function(action, direction) {
        if (direction == 'left') {
            if (action == 'hide') {
                this.moveLeftBtn.animate({
                    'left': '-90px',
                    'opacity': 0
                }, this.showNavButtonsSpeed);
            } else {
                this.moveLeftBtn.animate({
                    'left': '0px',
                    'opacity': 1
                }, this.showNavButtonsSpeed);
            }
        } else if (direction == 'right') {
            if (action == 'hide') {
                this.moveRightBtn.animate({
                    'right': '-90px',
                    'opacity': 0
                }, this.showNavButtonsSpeed);
            } else {
                this.moveRightBtn.animate({
                    'right': '0px',
                    'opacity': 1
                }, this.showNavButtonsSpeed);
            }
        }
    }
});

$(function() {
    new BaseView();
});
