@extends('layouts.main')
@section('main')
    <div class="box row">
        <div class="box-header">
            <div class="items">
                <div data-href="?a=basic2pl" class="item {{$algorithm == "Basic 2PL" ? "active":""}}">Basic 2PL</div>
                <div data-href="?a=conservative2pl" class="item {{$algorithm == "Conservative 2PL" ? "active":""}}">Conservative 2PL</div>
                <div data-href="?a=strict2pl" class="item {{$algorithm == "Strict 2PL" ? "active":""}}">Strict 2PL</div>
                <div data-href="?a=basicTO" class="item {{$algorithm == "Basic TO" ? "active":""}}">Basic TO</div>
            </div>
        </div>
        <div class="player-box">
            <div class="row m-0">
                <div class="col-lg-4">
                    <div class="ltr p-2 mr-4 fnt-let ">
                        Total Time:<code class="blue">{{$totalTime}}</code>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="ltr p-2 mr-4 fnt-let ">
                        Algorithm:<code class="blue">{{$algorithm}}</code>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="ltr p-2 mr-4 fnt-let ">
                        <button id="btn" data-href="{{route('index.excel')}}" class="btn btn-block modern-btn"> Download </button>
                    </div>
                </div>
                @foreach($schedules as $key => $schedule)
                    <div class="col-lg-12 fnt-let">
                        <div class="row m-0 schedule-box">
                            <div class="col-lg-4">schedule:<code class="blue">{{$key}}</code></div>
                            <div class="col-lg-4">Time: <code>{{$times[$key]}}</code></div>
                            <div class="col-lg-4"></div>
                            <div class="col-lg-12 scroll">schedule: <code>{{$schedule}}</code></div>
                            <div class="col-lg-12 scroll">execution:<code>{{$executions[$key]}}</code></div>
                            <div class="col-lg-12 scroll">Aborted:<code>{{key_exists($key,$aborts)? implode(',',$aborts[$key]) : "-"}}</code></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

@endsection
