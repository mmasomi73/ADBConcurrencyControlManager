@extends('admin.layouts.app')
@section('title') صفحه مدیریت کاربران @endsection

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
                            <a href="javascript:void(0);">مدیریت کاربران</a>
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
                            <h5> کاربران</h5>
                            <span class="header-action"><i class="feather icon-chevron-down"></i></span>
                        </div>
                        <div class="card-block table-border-style">
                            <div class="table-responsive">
                                <table class="table rtl text-right">
                                    <thead>
                                    <tr class="rtl text-right">
                                        <th>#</th>
                                        <th>نام</th>
                                        <th>الگوریتم</th>
                                        <th class="text-center">عملیات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $i = 0;
                                    @endphp
                                    @forelse($users as $user)
                                        <tr class="fnt-12">
                                            <th scope="row">{{++$i}}</th>
                                            <td class="has-avatar" style="position: relative;">
                                                <div class="avatar"
                                                     style="background-image: url('/{{getProfile($user->id)}}')"></div>
                                                {{"{$user->name} {$user->family}"}}
                                            </td>
                                            <td class="text-center">
                                                @forelse($user->algorithms as $algorithm)
                                                    <label class="label label-inverse-info ltr"
                                                           data-toggle="tooltip" data-placement="top" data-trigger="hover" title="{{$algorithm->algorithm->name}}">
                                                        {{$algorithm->algorithm->name}}
                                                    </label>
                                                @empty
                                                    -
                                                @endforelse
                                            </td>
                                            <td class="text-center rtl">
                                                <a class="table-action" data-toggle="tooltip" title="مشاهده"
                                                   href="{{route('user.view',$user->id)}}"><i
                                                        class="feather icon-eye"></i></a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <th colspan="5" class="text-center">هیچ کاربری یافت نشد!</th>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                                <div class="col-sm-12">
                                    {{$users->links('vendor.pagination.explor-pagination')}}
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
