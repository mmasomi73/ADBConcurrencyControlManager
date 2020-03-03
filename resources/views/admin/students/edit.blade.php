@extends('admin.layouts.app')
@section('title') صفحه ویرایش دانش آموز @endsection

@section('css')
    <link href="{{asset('assets/vendor/croppie/croppie.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/vendor/sweet-alert/sweetalert2.min.css')}}" rel="stylesheet" type="text/css">
@endsection

@section('breadcrumb')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h4 class="m-b-10">صفحه ویرایش سوال</h4>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{route('admin.index')}}"><i class="feather icon-home"></i></a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{route('admin.students.index')}}">مدیریت دانش آموزان </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="javascript:void(0);">ویرایش دانش آموز</a>
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
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card rtl text-right st-card">
                                <div class="card-header">
                                    <span class="header-icon"><i class="feather icon-save full-card"></i></span>
                                    <h5>ویرایش</h5>
                                    <span class="header-action"><i class="feather icon-chevron-down"></i></span>
                                </div>
                                <div class="card-block table-border-style">
                                    <form action="{{route('admin.students.update',$student->id)}}" method="post"
                                          class="form-material" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="photo col-lg-3 col-md-4 col-sm-12">
                                                <input type="file" name="thumbnail" class="hidden"
                                                       style="display: none;opacity: 0;z-index: -99999;">
                                                <input type="hidden" id="thumbnail-enc" name="thumbnail-enc"
                                                       class="hidden" style="display: none;opacity: 0;z-index: -99999;">
                                                <img id="profile-img"
                                                     @if(!empty($student->avatar)) src="/{{$student->avatar}}"
                                                     @endif alt="{{"{$student->name} {$student->family}"}}">
                                                <div class="pr"></div>
                                            </div>
                                            <div class="content isf col-lg-9 col-md-8 col-sm-12">
                                                <div class="row">
                                                    {{----------= Name =----------}}
                                                    <div class="col-lg-4">
                                                        <div class="form-group {{ $errors->has('name') ? 'form-danger' : 'form-primary' }}">
                                                            <input type="text" value="{{old('name',$student->name)}}"
                                                                   class="form-control text-right isf" name="name"
                                                                   id="name">
                                                            <span class="form-bar"></span>
                                                            @if ($errors->has('name'))
                                                                <span class="input-error">{{ $errors->first('name') }}</span>
                                                            @endif
                                                            <label for="name" class="float-label">نام</label>
                                                        </div>
                                                    </div>
                                                    {{----------= Family =----------}}
                                                    <div class="col-lg-4">
                                                        <div class="form-group {{ $errors->has('family') ? 'form-danger' : 'form-primary' }}">
                                                            <input type="text"
                                                                   value="{{old('family',$student->family)}}"
                                                                   class="form-control text-right isf" name="family"
                                                                   id="family">
                                                            <span class="form-bar"></span>
                                                            @if ($errors->has('family'))
                                                                <span class="input-error">{{ $errors->first('family') }}</span>
                                                            @endif
                                                            <label for="family" class="float-label">نام خانوادگی</label>
                                                        </div>
                                                    </div>
                                                    {{----------= Code =----------}}
                                                    <div class="col-lg-4">
                                                        <div class="form-group {{ $errors->has('code') ? 'form-danger' : 'form-primary' }}">
                                                            <input type="text" value="{{old('code',$student->code)}}"
                                                                   class="form-control text-left ltr" name="code"
                                                                   id="code">
                                                            <span class="form-bar"></span>
                                                            @if ($errors->has('code'))
                                                                <span class="input-error">{{ $errors->first('code') }}</span>
                                                            @endif
                                                            <label for="code" class="float-label">نام کاربری</label>
                                                        </div>
                                                    </div>
                                                    <!-- /.form-group -->
                                                    <div class="form-group col-sm-4 isf mr-auto">
                                                        <button type="submit" class="btn btn-primary w-100">ویرایش
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- List -->
                        <div class="col-lg-8 col-sm-12">
                            <div class="card rtl text-right isf st-card">
                                <div class="card-header">
                                    <span class="header-icon"><i class="feather icon-list full-card"></i></span>
                                    <h5>لیست کلاس ها</h5>
                                    <span class="header-action"><i class="feather icon-chevron-down"></i></span>
                                </div>
                                <div class="card-block table-border-style">
                                    <form action="{{route('admin.students.course.assign',$student->id)}}" method="post">
                                        @csrf
                                        <div class="row">
                                            {{-- Course --}}
                                            <div class="col-lg-8">
                                                <div class="form-group {{ $errors->has('course_id') ? 'form-danger' : 'form-primary' }}">
                                                    <select name="course_id" id="course_id" class="form-control isf">
                                                        @foreach($courses as $course)
                                                            <option @if(old('course_id') == $course->id) selected
                                                                    @endif value="{{$course->id}}">{{$course->title}}</option>
                                                        @endforeach
                                                    </select>
                                                    <span class="form-bar"></span>
                                                    @if ($errors->has('course_id'))
                                                        <span class="input-error">{{ $errors->first('course_id') }}</span>
                                                    @endif
                                                    <label for="course_id" class="float-label">کلاس</label>
                                                </div>
                                            </div>

                                            <div class="col-lg-4">
                                                <button type="submit"
                                                        class="btn btn-info waves-effect waves-light w-100 mt-1">افزودن
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="table-responsive">
                                        <table class="table rtl text-right dense">
                                            <thead>
                                            <tr class="rtl text-right">
                                                <th>#</th>
                                                <th>کلاس</th>
                                                <th class="text-center">پایه</th>
                                                <th class="text-center">عملیات</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php
                                                $i = 0;
                                            @endphp
                                            @forelse($student->courses as $course)
                                                <tr class="fnt-12">
                                                    <th scope="row">{{++$i}}</th>
                                                    <td>
                                                        {{$course->course->title}}
                                                    </td>
                                                    <td class="text-center">
                                                        {{$course->course->grade->name}}
                                                    </td>
                                                    <td class="text-center rtl">
                                                        <span class="table-action unassign"
                                                              data-href="{{route('admin.students.course.unassign',['id'=>$student->id,'course'=>$course->id])}}"
                                                              data-id="{{$course->id}}"
                                                              data-toggle="tooltip" title="لغو دسترسی">
                                                            <i class="feather icon-trash"></i></span>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <th colspan="5" class="text-center">برای این دانش آموز هیچ کلاسی ثبت
                                                        نشده
                                                        است!
                                                    </th>
                                                </tr>
                                            @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card rtl text-right isf st-card">
                                <div class="card-header">
                                    <span class="header-icon"><i class="feather icon-list full-card"></i></span>
                                    <h5>لیست معلمین</h5>
                                    <span class="header-action"><i class="feather icon-chevron-down"></i></span>
                                </div>
                                <div class="card-block table-border-style">
                                    <div class="row ul-list">
                                        @forelse($student->courses as $course)
                                            @foreach($course->course->teachers as $teacher)
                                                <div class="col-lg-12 fnt-12 ul-item">
                                                    <a class="table-action"
                                                       href="{{route('admin.teachers.edit',$teacher->teacher->id)}}">
                                                    <div class="avatar"
                                                         @if(!empty($teacher->teacher->avatar)) style="background-image: url('/{{$teacher->teacher->avatar}}')" @endif></div>
                                                    {{"{$teacher->teacher->name} {$teacher->teacher->family}"}}

                                                    {{"({$teacher->book->name})"}}

                                                        <i class="feather icon-eye"></i>
                                                    </a>
                                                </div>
                                            @endforeach
                                        @empty
                                            <div class="col-lg-12 text-center fnt-12">
                                                برای این دانش آموز معلمی ثبت نشده است.
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
    <script type="text/javascript" src="{{asset('assets/vendor/sweet-alert/sweetalert2.js')}}"></script>
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
                    }).then(function () {
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

        $('input[name="thumbnail"]').on('change', function () {
            readFile(this);
        });
        $('#crop-image-btn').on('click', function (ev) {

            $('.pr').text('');
            $uploadCrop.croppie('result', {
                type: 'rawcanvas',
                circle: true,
                size: {width: 150, height: 150},
                format: 'png'
            }).then(function (resp) {
                $('#profile-img').attr('src', resp.toDataURL());
                $('#thumbnail-enc').val(resp.toDataURL());
                $('#modal-image').modal('toggle');
                $('#personal-form').submit();
            });
        });

        $('body').ready(function () {
            //-------------------------------------------------= Unassign
            $('.unassign').click(function () {
                let href = $(this).data('href');
                let row = $(this).parents('tr');
                swal({
                    title: 'آیا مطمئن هستید؟',
                    text: "بعد از لغو دسترسی امکان بازیابی وجود ندارد",
                    type: 'warning',
                    animation: false,
                    customClass: 'animated tada',
                    showCancelButton: true,
                    confirmButtonColor: '#00bcd4',
                    cancelButtonColor: '#E91E63',
                    confirmButtonText: 'بله'
                }).then(function (result) {
                    if (result.value) {
                        $.ajax({
                            url: href,
                            method: 'POST',
                            dataType: 'json',
                            data: {
                                _token: "{{csrf_token ()}}",
                            }, beforeSend: function () {

                            }, success: function (data) {
                                if (data.id !== undefined) {
                                    swal(
                                        'دسترسی لغو شد!',
                                        'با موفقیت دسترسی لغو شد.',
                                        'success'
                                    ).then(function () {
                                        $(row).addClass('animated bounceOutRight');
                                        // alert(row);
                                        // console.log(row);
                                        window.location.reload();
                                    }).then(function () {
                                        // $(row).remove();
                                    });
                                } else {
                                    swal(
                                        'خطا در لغو دسترسی!',
                                        'سیستم قادر به لغو دسترسی نیست، مجدد سعی نمایید.',
                                        'danger'
                                    )
                                }
                            }, complete: function (xhr, status) {
                            }, error: function (xhr, status, error) {
                            }
                        });
                    }
                });
            });

            //-------------------------------------------------= Image Holder
            $('#profile-img').each(function () {
                if ($(this).attr('src') === undefined) $(this).attr('src', '/assets/images/img_blank.png');
            })
        });

    </script>
@endsection