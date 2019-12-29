$('input[type="text"]').focus(function () {
    $( "<div class='input-border'></div>" )
});
$(document).ready(function() {
    Waves.attach('.waves-float', ['waves-float']);
    Waves.attach('.waves-circle', ['waves-circle']);
    Waves.init();
    $('.background').particleground({
        dotColor: '#cacaca',
        lineColor: '#cacaca',
        particleRadius: 3
    });
    $('.intro').css({
        'margin-top': -($('.intro').height() / 2)
    });

    $(".schedule-box .scroll").niceScroll({
        cursorcolor:"#bfc2c5",
        cursorborder: "1px solid #bfc2c5",
        cursorwidth: "5px",
        autohidemode: false,
        touchbehavior: true,
        emulatetouch: true
    });

    $('div.item').click(function () {
        let link = $(this).data('href');
        window.location.replace(window.location.origin + link );
    });

    $('#btn').click(function () {
        let search = $(location).attr('search');
        let link = $(this).data('href');
        window.location.replace(link + search );
    });
});

