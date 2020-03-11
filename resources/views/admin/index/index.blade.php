@extends('admin.layouts.app')
@section('title') صفحه اصلی @endsection

@section('css')
    <style>
        .card .img-radius {
            background: #fff;
            width: 100px;
            height: 100px;
            background-size: contain;
            object-fit: contain;
        }
    </style>
@endsection
@section('breadcrumb')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h4 class="m-b-10">صفحه اصلی</h4>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{route('index')}}"><i class="feather icon-home"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('content')
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">
                    <!-- [ page content ] start -->
                    <div class="row ltr">

                        <div class="col-xl-3 col-md-6 rtl">
                            <div class="card">
                                <div class="card-block">
                                    <div class="row align-items-center m-l-0">
                                        <div class="col-auto">
                                            <i class="icon feather icon-users f-30 text-c-red"></i>
                                        </div>
                                        <div class="col-auto">
                                            <h6 class="text-muted m-b-10">دانشجویان</h6>
                                            <h2 class="m-b-0 isf">{{count($users)}}</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 rtl">
                            <div class="card">
                                <div class="card-block">
                                    <div class="row align-items-center m-l-0">
                                        <div class="col-auto">
                                            <i class="icon feather icon-settings f-30 text-c-blue"></i>
                                        </div>
                                        <div class="col-auto">
                                            <h6 class="text-muted m-b-10">الگوریتم ها</h6>
                                            <h2 class="m-b-0 isf">{{$algorithms}}</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 rtl">
                            <div class="card">
                                <div class="card-block">
                                    <div class="row align-items-center m-l-0">
                                        <div class="col-auto">
                                            <i class="icon feather icon-activity f-30 text-c-yellow"></i>
                                        </div>
                                        <div class="col-auto">
                                            <h6 class="text-muted m-b-10">اسکجول ها</h6>
                                            <h2 class="m-b-0 isf">{{$schedules}}</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 rtl">
                            <div class="card">
                                <div class="card-block">
                                    <div class="row align-items-center m-l-0">
                                        <div class="col-auto">
                                            <i class="icon feather icon-shuffle f-30 text-c-green"></i>
                                        </div>
                                        <div class="col-auto">
                                            <h6 class="text-muted m-b-10">اجراها</h6>
                                            <h2 class="m-b-0 isf">{{$executions}}</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- site Analytics card start -->
                        @forelse($users as $user)
                            <div class="col-xl-6 col-md-12">
                                <div class="card user-card-full z-depth-0">
                                    <div class="row m-l-0 m-r-0">
                                        <div class="col-sm-4 bg-c-blue user-profile">
                                            <div class="card-block text-center text-white">
                                                <div class="m-b-25">
                                                    <img src="/{{getProfile($user->id)}}" class="img-radius"
                                                         alt="User-Profile-Image">
                                                </div>
                                                <h6 class="f-w-600">{{"{$user->name} {$user->family}"}}</h6>
                                                <a href="{{route('user.view',$user->id)}}" class="text-white"><i
                                                        class="feather icon-layers m-t-10 f-16"></i></a>
                                            </div>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="card-block">
                                                <h6 class="m-b-20 p-b-5 b-b-default f-w-600">Information</h6>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <p class="m-b-10 f-w-600">Algorithms</p>
                                                        <div class="text-muted f-w-400">
                                                            <ul class="basicTree" style="padding-left: 30px;font-size: 12px; list-style-type: disc;">
                                                                @forelse($user->algorithms as $algorithm)
                                                                    <li>
                                                                        {{$algorithm->algorithm->name}}
                                                                    </li>
                                                                @empty
                                                                    <li>
                                                                        -
                                                                    </li>
                                                                @endforelse
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    @empty
                    @endforelse
                    <!-- site Analytics card end -->
                    </div>
                    <!-- [ page content ] end -->
                </div>
            </div>
        </div>
    </div>

@endsection
