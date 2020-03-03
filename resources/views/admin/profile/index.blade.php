@extends('admin.layouts.app')
@section('title') صفحه مدیریت پروفایل @endsection

@section('css')
    <link href="{{asset('assets/vendor/croppie/croppie.css')}}" rel="stylesheet" type="text/css">
@endsection

@section('breadcrumb')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h4 class="m-b-10">صفحه مدیریت پروفایل</h4>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{route('admin.index')}}"><i class="feather icon-home"></i></a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="javascript:void(0);">مدیریت پرفایل</a>
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
                    <div class="row">
                        <!-- Profile -->
                        <div class="col-lg-8 col-sm-12">
                            <div class="card rtl text-right st-card">
                                <div class="card-header">
                                    <span class="header-icon"><i class="feather icon-user full-card"></i></span>
                                    <h5>ویرایش پروفایل</h5>
                                    <span class="header-action"><i class="feather icon-chevron-down"></i></span>
                                </div>
                                <div class="card-block table-border-style">
                                    <form action="{{route('admin.profile.update')}}" method="post" class="form-material rtl row" enctype="multipart/form-data">
                                        @csrf
                                        <div class="photo col-lg-4 col-md-6 col-sm-12">
                                            <input type="file" name="thumbnail" class="hidden" style="display: none;opacity: 0;z-index: -99999;">
                                            <input type="hidden" id="thumbnail-enc" name="thumbnail-enc" class="hidden" style="display: none;opacity: 0;z-index: -99999;">
                                            <img id="profile-img"  @if(!empty($user->avatar)) src="{{$user->avatar}}" @endif alt=""><div class="pr"></div>
                                        </div>
                                        <div class="content isf col-lg-8 col-md-6 col-sm-12 d-block">
                                            {{----------= Name =----------}}
                                            <div class="col-lg-12">
                                                <div class="form-group {{ $errors->has('name') ? 'form-danger' : 'form-primary' }}">
                                                    <input type="text" value="{{old('name',$user->name)}}" class="form-control text-right isf" name="name" id="name">
                                                    <span class="form-bar"></span>
                                                    @if ($errors->has('name'))
                                                        <span class="input-error">{{ $errors->first('name') }}</span>
                                                    @endif
                                                    <label for="name" class="float-label">نام</label>
                                                </div>
                                            </div>
                                            {{----------= Email =----------}}
                                            <div class="col-lg-12">
                                                <div class="form-group {{ $errors->has('email') ? 'form-danger' : 'form-primary' }}">
                                                    <input type="email" value="{{old('email',$user->email)}}" class="form-control text-left ltr" name="email" id="email">
                                                    <span class="form-bar"></span>
                                                    @if ($errors->has('email'))
                                                        <span class="input-error">{{ $errors->first('email') }}</span>
                                                    @endif
                                                    <label for="email" class="float-label">ایمیل</label>
                                                </div>
                                            </div>
                                            <!-- /.form-group -->
                                            <div class="form-group col-sm-12 isf">
                                                <button type="submit" class="btn btn-primary w-100">ثبت</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- Password -->
                        <div class="col-lg-4 col-sm-12">
                            <div class="card rtl text-right st-card">
                                <div class="card-header">
                                    <span class="header-icon"><i class="feather icon-lock full-card"></i></span>
                                    <h5>تغییر رمز عبور</h5>
                                    <span class="header-action"><i class="feather icon-chevron-down"></i></span>
                                </div>
                                <div class="card-block table-border-style">
                                    <form action="{{route('admin.profile.password')}}" method="post" class="form-material">
                                        @csrf
                                        <div class="row">

                                            {{-- Old Password --}}
                                            <div class="col-lg-12">
                                                <div class="form-group {{ $errors->has('old_password') ? 'form-danger' : 'form-primary' }}">
                                                    <input type="password" class="form-control ltr text-left isf" name="old_password" id="old_password">
                                                    <span class="form-bar"></span>
                                                    @if ($errors->has('old_password'))
                                                        <span class="input-error">{{ $errors->first('old_password') }}</span>
                                                    @endif
                                                    <label for="old_password" class="float-label">رمز عبور قبلی</label>
                                                </div>
                                            </div>

                                            {{-- New Password --}}
                                            <div class="col-lg-12">
                                                <div class="form-group {{ $errors->has('new_password') ? 'form-danger' : 'form-primary' }}">
                                                    <input type="password" class="form-control ltr text-left isf" name="new_password" id="new_password">
                                                    <span class="form-bar"></span>
                                                    @if ($errors->has('new_password'))
                                                        <span class="input-error">{{ $errors->first('new_password') }}</span>
                                                    @endif
                                                    <label for="new_password" class="float-label">رمز عبور جدید</label>
                                                </div>
                                            </div>

                                            {{-- Confirm Password --}}
                                            <div class="col-lg-12">
                                                <div class="form-group {{ $errors->has('new_password_confirmation') ? 'form-danger' : 'form-primary' }}">
                                                    <input type="password" class="form-control ltr text-left isf" name="new_password_confirmation" id="new_password_confirmation">
                                                    <span class="form-bar"></span>
                                                    @if ($errors->has('new_password_confirmation'))
                                                        <span class="input-error">{{ $errors->first('new_password_confirmation') }}</span>
                                                    @endif
                                                    <label for="new_password_confirmation" class="float-label"> تایید رمز عبور</label>
                                                </div>
                                            </div>

                                            <div class="col-lg-12">
                                                <button type="submit" class="btn btn-info waves-effect waves-light w-100">ویــرایش</button>
                                            </div>
                                        </div>
                                    </form>
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
    <div id="modal-image" class="modal rtl">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">برش عکس</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="feather icon-x"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="image-crop"></div>
                </div>
                <div class="modal-footer">
                    <button id="crop-image-btn" type="button" class="btn btn-primary">برش</button>
                </div>
            </div>
        </div>
    </div>
    @include('admin.partials.notify')
    <script type="text/javascript" src="{{asset ('assets/vendor/croppie/croppie.js')}}"></script>
    <script>
        //TODO: Use Croppia To Crop Image
        $('.pr').click(function () {
            $('input[name="thumbnail"]').click();
        });

        //-------------------------------------------------= Croppie
        let $uploadCrop;

        function readFile(input) {
            $('#modal-image').modal('toggle').removeClass('fade');
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('.image-crop').addClass('ready');
                    $uploadCrop.croppie('bind', {
                        url: e.target.result
                    }).then(function(){
                    });

                };

                reader.readAsDataURL(input.files[0]);
            }
            else {
            }
        }

        $uploadCrop = $('.image-crop').croppie({
            viewport: {
                width: 150,
                height: 150,
                type: 'circle'
            },
            enableExif: true
        });

        $('input[name="thumbnail"]').on('change', function () { readFile(this); });
        $('#crop-image-btn').on('click', function (ev) {

            $('.pr').text('');
            $uploadCrop.croppie('result', {
                type: 'rawcanvas',
                circle: true,
                size: { width: 150, height: 150 },
                format: 'png'
            }).then(function (resp) {
                $('#profile-img').attr('src',resp.toDataURL());
                $('#thumbnail-enc').val(resp.toDataURL());
                $('#modal-image').modal('toggle');
                $('#personal-form').submit();
            });
        });

    </script>
@endsection