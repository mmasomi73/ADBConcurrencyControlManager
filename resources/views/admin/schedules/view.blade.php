@extends('admin.layouts.app')
@section('title') صفحه مشاهده الگوریتم @endsection

@section('css')
    <style>
        .code-style {
            background: #f4f4f4;
            border: 1px solid #ddd;
            border-left: 3px solid #f36d33;
            color: #666;
            page-break-inside: avoid;
            font-family: monospace;
            font-size: 15px;
            line-height: 1.6;

            max-width: 100%;
            overflow-y: hidden;
            padding: 0;
            display: block;
            word-wrap: inherit;
            overflow-x: auto;
            margin: 5px;
            border-radius: 5px;
        }

        .code-style code {
            color: #666;
            font-family: monospace;
            white-space: nowrap;
        }
        .code-box{
            margin: 5px;
            border-radius: 5px;
        }
        .sch-abt, .sch-id, .sch-time{
            display: inline-block;
            padding: 5px;
            font-family: SFMono-Regular,Menlo,Monaco,Consolas,"Liberation Mono","Courier New",monospace;
        }
        .sch-id{
            background: #00BCD4;
            color: #fff;
            margin: 0;
            width: 32px;
            text-align: center;
            border-radius: 5px 0 50% 0;
            position: absolute;
            top: 0;
            left: 0;
        }
        .sch-time{
            margin-left: 48px;
        }
    </style>
@endsection
@section('breadcrumb')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h4 class="m-b-10">صفحه مدیریت کاربران</h4>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{route('index.panel')}}"><i class="feather icon-home"></i></a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{route('user.index')}}">مدیریت کاربران</a>
                        </li>
                        <li class="breadcrumb-item">
{{--                            <a href="javascript:void(0);">{{$user->getFullName()}}</a>--}}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('content')
    <div class="pcoded-inner-content rtl text-right">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">
                    <!-- [ page content ] start -->
                    <!-- List -->
                    <div class="card rtl text-right isf st-card">
                        <div class="card-header">
                            <span class="header-icon"><i class="feather icon-list full-card"></i></span>
                            <h5>خروجی کاربر</h5>
                            <span class="header-action"><i class="feather icon-chevron-down"></i></span>
                        </div>
                        <div class="card-block table-border-style rtl">
                            <div class="form-radio  m-b-30">
                                <form action="{{route('user.view',$user->id)}}" method="get">

                                    <div class="row rtl text-right">
                                        <div class="col-lg-3">
                                            <div class="radio radiofill radio-primary radio-inline rtl w-100">
                                                <label>
                                                    <input type="radio" name="algorithm" value="basic2PL"
                                                           @if($algorithm == 1)checked="checked" @endif>
                                                    <i class="helper"></i> Basic 2PL
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-lg-3">
                                            <div class="radio radiofill radio-primary radio-inline rtl w-100">
                                                <label>
                                                    <input type="radio" name="algorithm" value="conservative2PL"
                                                           @if($algorithm == 2)checked="checked" @endif>
                                                    <i class="helper"></i> Conservative 2PL
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="radio radiofill radio-primary radio-inline rtl w-100">
                                                <label>
                                                    <input type="radio" name="algorithm" value="strict2PL"
                                                           @if($algorithm == 3)checked="checked" @endif>
                                                    <i class="helper"></i> Strict 2PL
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="radio radiofill radio-primary radio-inline rtl w-100">
                                                <label>
                                                    <input type="radio" name="algorithm" value="basicTO"
                                                           @if($algorithm == 4)checked="checked" @endif>
                                                    <i class="helper"></i> Basic TO
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-9"></div>
                                        <div class="col-lg-3">
                                            <button type="submit" class="btn btn-info waves-effect waves-light w-100">
                                                جستجو
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            @forelse($executions as $execution)
                                <div class="row ltr text-left border code-box">


                                    <div class="col-lg-12 fnt-let">
                                        <div class="sch-id">
                                            {{$execution->schedule->id}}
                                        </div>
                                        <div class="sch-time">
                                            Time : {{$execution->time}}
                                        </div>
                                        <div class="sch-abt">
                                           Aborted Numbers : {{$execution->aborted}}
                                        </div>
                                        <div class="code-style w-100">
                                            <code>
                                                {{$execution->aborts}}
                                            </code>
                                        </div>

                                        <div class="code-style w-100">
                                            <code>
                                                {{$execution->schedule->schedule}}
                                            </code>
                                            <code>
                                                {{$execution->executed}}
                                            </code>
                                        </div>
                                    </div>
                                </div>
                            @empty
                            @endforelse
                            <div class="col-sm-12">
                                {{$executions->links('vendor.pagination.explor-pagination')}}
                            </div>
                        </div>
                    </div>
                    <!-- [ page content ] end -->
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    @include('admin.partials.notify')
    <script src="{{asset('cms/assets/js/pcoded.js')}}"></script>
@endsection
