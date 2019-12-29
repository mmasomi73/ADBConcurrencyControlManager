@extends('layouts.main')
@section('main')
    <div class="box row">
        <div class="box-header">
            <div class="items">
                <div class="item">Basic 2PL</div>
                <div class="item">Conservative 2PL</div>
                <div class="item">Strict 2PL</div>
                <div class="item">Basic TO</div>
            </div>
        </div>
        <div class="player-box">
            <div class="row m-0">
                <div class="col-lg-6">
                    <div class="ltr p-2 mr-4 fnt-let ">
                        Total Time:<code class="blue">{{$totalTime}}</code>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="ltr p-2 mr-4 fnt-let ">
                        Algorithm:<code class="blue">{{$algorithm}}</code>
                    </div>
                </div>
                @foreach($schedules as $key => $schedule)
                    <div class="col-lg-12 fnt-let border-top">
                        <div class="row m-0 schedule-box">
                            <div class="col-lg-4">schedule:<code class="blue">{{$key}}</code></div>
                            <div class="col-lg-4">Time: <code>{{$times[$key]}}</code></div>
                            <div class="col-lg-4"></div>
                            <div class="col-lg-12 scroll">schedule: <code>{{$schedule}}</code></div>
                            <div class="col-lg-12 scroll">execution:<code>{{$executions[$key]}}</code></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

@endsection
