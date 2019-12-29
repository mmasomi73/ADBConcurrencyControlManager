
function resp() {
    var width = $(window).width();
    //-----= Responsive
        //-----= Audio volume
        // console.log();
        // $('.vlCtrl svg').attr('viewBox','0 0 '+(width - (width * 0.1) - 120)+' 30');
        // $('.vlCtrl svg #ctrlLineB').attr('x2',(width - (width * 0.1) - 128)).attr('y2',15).attr('y1',15);
        // $('.vlCtrl svg #ctrlLineF').attr('x2',(width - (width * 0.1) - 128)).attr('y2',15).attr('y1',15);
        // $('.vlCtrl svg #circle').attr('cx',(width - (width * 0.1) - 128)).attr('cy',15);
    if (width <= 900){
        $('.plist').click(function () {
            $('.playlist').toggleClass('shower');
            $('.player').toggleClass('hider');
        });
        $('.playlist  .close').click(function () {
            $('.playlist').toggleClass('shower');
            $('.player').toggleClass('hider');
        });
    }
}

$(window).ready(function() {
    resp();
});