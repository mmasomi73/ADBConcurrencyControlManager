@extends('admin.layouts.app')
@section('title') صفحه مدیریت اسکجول‌ها @endsection

@section('css')
    <style>
        .code-view {
            direction: ltr !important;
            padding-right: 47px;
            font-family: monospace;
        }

        .aid-users {
            position: absolute;
            display: inline-block;
            height: 45px;
            width: auto;
            top: -20px;
            left: 30px;
            float: left;
            vertical-align: bottom;
        }

        .aid-users .avatar {
            position: absolute;
            left: 0;
            width: 45px;
            height: 45px;
            margin-left: 5px;
            background-image: linear-gradient(120deg, #84fab0 0%, #8fd3f4 100%);
            background-size: cover;
            border-radius: 50%;
            display: inline-block;
            cursor: pointer;
            -webkit-transition: all 500ms ease;
            -moz-transition: ball 500ms ease;
            -o-transition: all 500ms ease;
            transition: all 500ms ease;
        }

        .aid-users .avatar:nth-child(1) {
            left: 0;
        }

        .aid-users .avatar:nth-child(2) {
            left: 50px;
        }

        .aid-users .avatar:nth-child(3) {
            left: 100px;
        }

        .aid-users .avatar:hover {
            bottom: 8px;
        }

        .st-card span.header-icon {
            font-size: 14px;
        }

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

        .code-box {
            margin: 5px;
            border-radius: 5px;
            position: relative;
        }

        .usr-box{
            position: absolute;
            left: -60px;
            width: 45px;
            bottom: 0;
            height: 45px;
            margin-left: 5px;
            /*background-image: linear-gradient(120deg, #84fab0 0%, #8fd3f4 100%);*/
            background-size: cover;
            border-radius: 50%;
            display: inline-block;
            cursor: pointer;
            -webkit-transition: all 500ms ease;
            -moz-transition: ball 500ms ease;
            -o-transition: all 500ms ease;
            transition: all 500ms ease;
        }
        .usr-box .avatar{
            background-size: cover;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            border: 1px solid #e2e2e2;

        }
        .tab-pane{
            padding-left: 55px;
        }
        .sch-abt, .sch-id, .sch-time {
            display: inline-block;
            padding: 5px;
            font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        }

        .sch-id {
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

        .sch-time {
            margin-left: 48px;
        }
        .code-box::before{
            content: "";
            position: absolute;
            display: block;
            left: -1px;
            border-right: 7px solid #e9ecef;
            border-top: 7px solid transparent;
            border-bottom: 7px solid transparent;
            bottom: 10px;
            margin-left: -6px;
        }
        .code-box::after{
            content: "";
            position: absolute;
            display: block;
            left: 1px;
            border-right: 6px solid #fff;
            border-top: 6px solid transparent;
            border-bottom: 6px solid transparent;
            bottom: 11px;
            margin-left: -6px;
        }
    </style>
@endsection
@section('breadcrumb')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h4 class="m-b-10">صفحه مدیریت اسکجول‌ها</h4>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{route('index.panel')}}"><i class="feather icon-home"></i></a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="javascript:void(0);">مدیریت اسکجول‌ها</a>
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
                    @php
                        $i = 0;
                    @endphp
                    @forelse($schedules as $schedule)

                        @php
                            $users = $schedule->executions->pluck('user')->unique();
                        @endphp
                        <div class="pos-relative">
                            <div class="aid-users">
                                @forelse($users as $user)
                                    <div class="avatar" title="{{"{$user->name} {$user->family}"}}"
                                         data-toggle="tooltip" data-placement="top" data-trigger="hover"
                                         style="background-image: url('/{{getProfile($user->id)}}')"></div>

                                @empty
                                @endforelse
                            </div>
                            <div class="card rtl text-right isf st-card">

                                <div class="card-header">
                                    <span class="header-icon fnt-let">{{getRowNumber($schedules,$i)}}</span>
                                    <div class="d-inline-block code-view">
                                        {{$schedule->schedule}}
                                    </div>
                                    <span class="header-action">
                                    <i class="feather icon-chevron-down"></i>
                                </span>
                                </div>
                                <div class="card-block table-border-style" style="display: none;">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <ul class="nav nav-tabs md-tabs" role="tablist">

                                                @forelse($algorithms as $algorithm)
                                                    <li class="nav-item">
                                                        <a class="nav-link " data-toggle="tab"
                                                           href="#home-{{$algorithm->id}}-{{$schedule->id}}"
                                                           role="tab">{{$algorithm->name}}</a>
                                                        <div class="slide"></div>
                                                    </li>
                                                @empty
                                                @endforelse
                                            </ul>
                                            <div class="tab-content">
                                                @forelse($algorithms as $algorithm)
                                                    @php
                                                        $executions = $schedule->executions->where('algorithm_id',$algorithm->id);
                                                    @endphp
                                                    <div class="tab-pane" id="home-{{$algorithm->id}}-{{$schedule->id}}"
                                                         role="tabpanel">
                                                        @forelse($executions as $execution)
                                                            <div class="row ltr text-left border code-box">
                                                                <div class="usr-box">
                                                                    <div class="avatar"
                                                                         title="{{"{$execution->user->name} {$execution->user->family}"}}"
                                                                         data-toggle="tooltip" data-placement="top"
                                                                         data-trigger="hover"
                                                                         style="background-image: url('/{{getProfile($execution->user->id)}}')"></div>

                                                                </div>
                                                                <div class="col-lg-12 fnt-let">
                                                                    {{--                                                                    <div class="sch-id">--}}
                                                                    {{--                                                                        {{$execution->schedule->id}}--}}
                                                                    {{--                                                                    </div>--}}
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
                                                                            {{exFormatter($execution->executed)}}
                                                                        </code>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @empty
                                                        @endforelse
                                                    </div>
                                                @empty
                                                @endforelse
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                    @empty
                        <div>
                            <div class="text-center">هیچ اسکجولی یافت نشد!</div>
                        </div>
                    @endforelse
                    <div class="card rtl text-right isf st-card">

                        <div class="card-block table-border-style">
                            <div class="row">
                                <div class="col-sm-12">
                                    {{$schedules->links('vendor.pagination.explor-pagination')}}
                                </div>
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
@endsection
