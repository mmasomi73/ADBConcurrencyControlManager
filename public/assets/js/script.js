$('input[type="text"]').focus(function () {
    $("<div class='input-border'></div>")
});
$(document).ready(function () {
    Waves.attach('.waves-float', ['waves-float']);
    Waves.attach('.waves-circle', ['waves-circle']);
    Waves.init();
    $('.background').particleground({
        dotColor: '#636363',
        lineColor: '#636363',
        particleRadius: 3
    });
    $('.intro').css({
        'margin-top': -($('.intro').height() / 2)
    });

    $(".schedule-box .scroll").niceScroll({
        cursorcolor: "#bfc2c5",
        cursorborder: "1px solid #bfc2c5",
        cursorwidth: "5px",
        autohidemode: false,
        touchbehavior: true,
        emulatetouch: true
    });

    $('#btn').click(function () {
        let search = $(location).attr('search');
        let link = $(this).data('href');
        window.location.replace(link + search);
    });

    //------------------------------Loader
    function Ticker(elem) {
        elem.lettering();
        this.done = false;
        this.cycleCount = 5;
        this.cycleCurrent = 0;
        this.chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%^&*()-_=+{}|[]\\;\':"<>?,./`~'.split('');
        this.charsCount = this.chars.length;
        this.letters = elem.find('span');
        this.letterCount = this.letters.length;
        this.letterCurrent = 0;

        this.letters.each(function () {
            var $this = $(this);
            $this.attr('data-orig', $this.text());
            $this.text('-');
        });
    }

    Ticker.prototype.getChar = function () {
        return this.chars[Math.floor(Math.random() * this.charsCount)];
    };

    Ticker.prototype.reset = function () {
        this.done = false;
        this.cycleCurrent = 0;
        this.letterCurrent = 0;
        this.letters.each(function () {
            var $this = $(this);
            $this.text($this.attr('data-orig'));
            $this.removeClass('done');
        });
        this.loop();
    };

    Ticker.prototype.loop = function () {
        var self = this;

        this.letters.each(function (index, elem) {
            var $elem = $(elem);
            if (index >= self.letterCurrent) {
                if ($elem.text() !== ' ') {
                    $elem.text(self.getChar());
                    $elem.css('opacity', Math.random());
                }
            }
        });

        if (this.cycleCurrent < this.cycleCount) {
            this.cycleCurrent++;
        } else if (this.letterCurrent < this.letterCount) {
            var currLetter = this.letters.eq(this.letterCurrent);
            this.cycleCurrent = 0;
            currLetter.text(currLetter.attr('data-orig')).css('opacity', 1).addClass('done');
            this.letterCurrent++;
        } else {
            this.done = true;
        }

        if (!this.done) {
            requestAnimationFrame(function () {
                self.loop();
            });
        } else {
            setTimeout(function () {
                self.reset();
            }, 750);
        }
    };

    $words = $('.word');

    $words.each(function () {
        var $this = $(this),
            ticker = new Ticker($this).reset();
        $this.data('ticker', ticker);
    });

    //------------------------------ Authorize
    $('.info-box').removeClass('bounceInLeft').addClass('animated bounceOutLeft');
    $('.cmd, .close').click(function () {
        if ($('.info-box').hasClass('animated bounceInLeft')) {
            $('.info-box').removeClass('hide animated bounceInLeft bounceOutLeft').addClass('animated bounceOutLeft');
        } else
            $('.info-box').removeClass('hide animated bounceInLeft bounceOutLeft').addClass('animated bounceInLeft');
    });

});

//---------------Menu
$(function () {
    var top = $('.box-header').offset().top - parseFloat($('.box-header').css('marginTop').replace(/auto/, 0));
    var footTop = $('#footer').offset().top - parseFloat($('#footer').css('marginTop').replace(/auto/, 0));

    var maxY = footTop - $('.box-header').outerHeight();

    $(window).scroll(function (evt) {
        var y = $(this).scrollTop();
        if (y > top + 60) {
            $('.box-header').addClass('fixed');
        } else {
            $('.box-header').removeClass('fixed');
        }
    });
});

// Back to top button
(function () {
    $(document).ready(function () {
        return $(window).scroll(function () {
            return $(window).scrollTop() > 200 ? $("#back-to-top").addClass("show") : $("#back-to-top").removeClass("show")
        }), $("#back-to-top").click(function () {
            return $("html,body").animate({
                scrollTop: "0"
            })
        })
    })
}).call(this);

