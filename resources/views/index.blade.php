@extends('layouts.main')
@section('main')
    <div class="box row">
        <div class="box-header">
            <div class="items">
                <div data-href="basic2pl" class="item">Basic 2PL</div>
                <div data-href="conservative2pl" class="item">
                    Conservative 2PL
                </div>
                <div data-href="strict2pl" class="item">Strict 2PL</div>
                <div data-href="basicTO" class="item">Basic TO</div>
            </div>
        </div>
        <div class="waiting hide">
            <div class="word">IN_PROGRESS...</div>
            <div class="overlay"></div>
        </div>
        <div class="player-box">
            <div class="row m-0 schls">
                <div class="col-lg-4">
                    <div class="ltr p-2 mr-4 fnt-let ">
                        Total Time:<code id="total_time" class="blue">0.0</code>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="ltr p-2 mr-4 fnt-let ">
                        Algorithm:<code id="algorithm" class="blue">-</code>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="ltr p-2 mr-4 fnt-let ">
                        <button id="btn" data-href="{{route('index.excel')}}" class="btn btn-block modern-btn">
                            Download
                        </button>
                    </div>
                </div>
                <div class="col-lg-12 fnt-let">
                    <div class="row m-0 schedule-box">
                        <div class="border-min-tl"></div>
                        <div class="border-min-tr"></div>
                        <div class="border-min-bl"></div>
                        <div class="border-min-br"></div>
                        <div class="col-lg-12 text-center">
                            <div class="init fa-2x">Choose One Of Algorithms ...</div>
                            <div class="init">Alorithm executions may be take long time, please wait ...</div>
                            <div class="text-center mt-2 rtl arabic">
                                حضرت موسى (علیه السلام ) در مناجات خود در كوه طور عرض كرد: یا اله العالمین (اى معبود جهانیان ).
                                جواب شنید: لبیك (یعنى نداى تورا پذیرفتم ).
                                سپس عرض كرد: یا اله المحسنین (اى خداى نیكوكاران ) همان جواب را شنید سپس عرض كرد: یا اله المطیعین : (اى خداى اطاعت كنندگان ) باز همان پاسخ را شنید.
                                سپس عرض كرد: <code class="arabic">یا اله العاصین</code> (اى خداى گنهكاران ).
                                سه بار در پاسخ شنید: لبیك ، لبیك لبیك .
                                موسى (علیه السلام ) عرض كرد: خدایا چرا، در دفعه چهارم ، سه بار پاسخم دادى ؟!
                                خداوند به او خطاب كرد: عارفان به معرفت خود، و نیكوكاران و اطاعت كنندگان به نیكى و اطاعت خود، اعتماد دارند، ولى گنهكاران جز به فضل من ، پناهى ندارند، اگر از درگاه من ناامید گردند، به درگاه چه كسى پناهنده شوند؟!.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="footer"></div>
    <div id="back-to-top">
    </div>

    <div class="cmd"></div>

    <div class="info-box bounceOutLeft">
        <div class="border-min-tl"></div>
        <div class="border-min-tr"></div>
        <div class="border-min-bl"></div>
        <div class="border-min-br"></div>
        <div class="info">
            <div class="close"></div>
            <div class="uni">Bu'Ali Sina University</div>
            <div class="logo"></div>
            <div class="data">
                <div class="name">SP: DR.Morteza Yousef Sanati</div>
                <div class="name">ST: Meysam Masomi</div>
                <div class="num">SN:9813161005</div>
                <div class="sn">fall-2019</div>
            </div>
        </div>
    </div>

    <div class="notification animated fadeOutDown">
        <div class="icon"></div>
        <div class="msg">Copy to Clipboard...</div>
    </div>
@endsection

@section('js')
    <script>
        $('body').ready(function () {
            $('div.item').click(function () {
                $('div.item').removeClass('active');
                $(this).addClass('active');
                $('#total_time').text('0.0');
                $('#algorithm').text($(this).text());
                let link = $(this).data('href');
                $('.waiting').removeClass('hide');
                $($('.schedule-box').parent().get().reverse()).each(function (idx, item) {
                    setTimeout(function () {
                        $(item).addClass('animated bounceOutLeft');
                        setTimeout(function () {
                            $(item).remove();
                        }, (idx + 1) * 100);
                    }, (idx + 1) * 100);
                });
                //-------------------------------= Ajax
                $.ajax({
                    url: "{{route('index.ajax')}}",
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        link: link,
                        _token: "{{csrf_token()}}"
                    }, success: function (data) {
                        if(_.has(data,'totalTime')){
                            $('#total_time').text(_.get(data,'totalTime'));
                            _.forEach(_.get(data,'schedules',[]), function(value, key) {
                                setTimeout(function () {
                                    $('.player-box .schls').append("<div class=\"col-lg-12 fnt-let animated bounceInLeft \">\n" +
                                        "                        <div class=\"row m-0 schedule-box\">\n" +
                                        "                            <div class=\"border-min-tl\"></div>\n" +
                                        "                            <div class=\"border-min-tr\"></div>\n" +
                                        "                            <div class=\"border-min-bl\"></div>\n" +
                                        "                            <div class=\"border-min-br\"></div>\n" +
                                        "                            <div class=\"col-lg-4\">schedule:<code class=\"blue\">"+ key +"</code></div>\n" +
                                        "                            <div class=\"col-lg-4\">Time: <code>"+ _.get(value,'time','0.0') +"</code></div>\n" +
                                        "                            <div class=\"col-lg-4\"></div>\n" +
                                        "                            <div class=\"col-lg-12 truncate scroll\">schedule: <code>"+ _.get(value,'schedule','-') +"</code></div>\n" +
                                        "                            <div class=\"col-lg-12 truncate scroll\">execution:<code>"+ _.get(value,'execution','-') +"</code></div>\n" +
                                        "                            <div class=\"col-lg-12 truncate scroll\">\n" +
                                        "                                Aborted:<code>"+ _.get(value,'aborted','-') +"</code>\n" +
                                        "                            </div>\n" +
                                        "                        </div>\n" +
                                        "                    </div>");
                                }, (key + 1) * 50);


                            });
                        }
                    }, complete: function (XHR, status) {
                        $('.waiting').addClass('hide');
                    },
                    timeout: 1000 * 60 * 5 // 5min
                });
            });

            //-------------------------------= Copy Clipboard
            $('body').on('click','code',function () {
                let $temp = $("<input>");
                $("body").append($temp);
                $temp.val($(this).text().trim()).select();
                document.execCommand("copy");
                $temp.remove();
                $('.notification').addClass('animated fadeInUp').removeClass('fadeOutDown');
                $('.notification .msg').toggleClass('anim');
                setTimeout(function () {
                    $('.notification').addClass(' animated fadeOutDown');
                    $('.notification .msg').toggleClass('anim');

                }, 5000);
            });
        });
    </script>
@endsection

