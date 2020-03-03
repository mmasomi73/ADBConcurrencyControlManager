@extends('admin.layouts.app')
@section('title') صفحه مدیریت دانش آموزان @endsection

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
                        <h4 class="m-b-10">صفحه مدیریت دانش آموزان</h4>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{route('admin.index')}}"><i class="feather icon-home"></i></a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="javascript:void(0);">مدیریت دانش آموزان</a>
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
                    <!-- Store -->
                    <div class="card rtl text-right st-card">
                        <div class="card-header">
                            <span class="header-icon"><i class="feather icon-save full-card"></i></span>
                            <h5>افزودن دانش آموز</h5>
                            <span class="header-action"><i class="feather icon-chevron-down"></i></span>
                        </div>
                        <div class="card-block table-border-style">
                            <form action="{{route('admin.students.store')}}" method="post" class="form-material" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="photo col-lg-3 col-md-4 col-sm-12">
                                        <input type="file" name="thumbnail" class="hidden" style="display: none;opacity: 0;z-index: -99999;">
                                        <input type="hidden" id="thumbnail-enc" name="thumbnail-enc" class="hidden" style="display: none;opacity: 0;z-index: -99999;">
                                        <img id="profile-img" alt=""><div class="pr"></div>
                                    </div>
                                    <div class="content isf col-lg-9 col-md-8 col-sm-12">
                                        <div class="row">
                                            {{----------= Name =----------}}
                                            <div class="col-lg-4">
                                                <div class="form-group {{ $errors->has('name') ? 'form-danger' : 'form-primary' }}">
                                                    <input type="text" value="{{old('name')}}" class="form-control text-right isf" name="name" id="name">
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
                                                    <input type="text" value="{{old('family')}}" class="form-control text-right isf" name="family" id="family">
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
                                                    <input type="text" value="{{old('code')}}" class="form-control text-left ltr" name="code" id="code">
                                                    <span class="form-bar"></span>
                                                    @if ($errors->has('code'))
                                                        <span class="input-error">{{ $errors->first('code') }}</span>
                                                    @endif
                                                    <label for="code" class="float-label">نام کاربری</label>
                                                </div>
                                            </div>
                                            <!-- /.form-group -->
                                            <div class="form-group col-sm-4 isf mr-auto">
                                                <button type="submit" class="btn btn-primary w-100">ثبت</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- List -->
                    <div class="card rtl text-right isf st-card">
                        <div class="card-header">
                            <span class="header-icon"><i class="feather icon-list full-card"></i></span>
                            <h5>دانش آموزان</h5>
                            <span class="header-action"><i class="feather icon-chevron-down"></i></span>
                        </div>
                        <div class="card-block table-border-style">
                            <div class="table-responsive">
                                <table class="table rtl text-right dense">
                                    <thead>
                                    <tr class="rtl text-right">
                                        <th>#</th>
                                        <th>نام</th>
                                        <th class="text-center">نام کاربری</th>
                                        <th class="text-center">کلاس</th>
                                        <th class="text-center">پایه</th>
                                        <th class="text-center">عملیات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $i = 0;
                                    @endphp
                                    @forelse($students as $student)
                                        <tr class="fnt-12">
                                            <th scope="row">{{++$i}}</th>
                                            <td class="has-avatar">
                                                <div class="avatar" @if(!empty($student->avatar)) style="background-image: url('/{{$student->avatar}}')" @endif></div>
                                                {{"{$student->name} {$student->family}"}}
                                            </td>
                                            <td class="text-center">
                                                {{$student->code}}
                                            </td>
                                            @php
                                                $courses = new \Illuminate\Support\Collection();
                                                $grades = new \Illuminate\Support\Collection();
                                                foreach ($student->courses as $course) {
                                                    $courses->push($course->course->title);
                                                    $grades->push($course->course->grade->name);
                                                }
                                            $courses = $courses->unique();
                                            $grades = $grades->unique();
                                            @endphp
                                            <td class="text-center">
                                                {{$courses->implode('product', ' - ')}}
                                            </td>
                                            <td class="text-center">
                                                {{$grades->implode('product', ' - ')}}
                                            </td>
                                            <td  class="text-center rtl">
                                                <span class="table-action destroy"
                                                   data-toggle="tooltip" title="حذف"
                                                   data-href="{{route('admin.students.destroy',$student->id)}}"><i class="feather icon-trash"></i></span>
                                                <a class="table-action" data-toggle="tooltip" title="ویرایش" href="{{route('admin.students.edit',$student->id)}}"><i class="feather icon-edit"></i></a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <th colspan="5" class="text-center">هیچ دانش آموزی یافت نشد!</th>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                                <div class="col-sm-12">
                                    {{$students->links('vendor.pagination.explor-pagination')}}
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
                let reader = new FileReader();

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

        $('body').ready(function () {
            //-------------------------------------------------= Unassign
            $('.destroy').click(function () {
                let href = $(this).data('href');
                let row = $(this).parents('tr');
                swal({
                    title: 'آیا مطمئن هستید؟',
                    text: "بعد از حذف امکان بازیابی وجود ندارد",
                    type: 'warning',
                    animation: false,
                    customClass: 'animated tada',
                    showCancelButton: true,
                    confirmButtonColor: '#00bcd4',
                    cancelButtonColor: '#E91E63',
                    confirmButtonText: 'بله'
                }).then(function (result){
                    if (result.value) {
                        $.ajax({
                            url: href,
                            method: 'POST',
                            dataType: 'json',
                            data: {
                                _token: "{{csrf_token ()}}",
                            }, beforeSend: function () {

                            }, success: function (data) {
                                if(data.id !== undefined){
                                    swal(
                                        'حذف شد!',
                                        'با موفقیت حذف شد.',
                                        'success'
                                    ).then(function () {
                                        $(row).addClass('animated bounceOutRight');
                                        // alert(row);
                                        // console.log(row);
                                        window.location.reload();
                                    }).then(function () {
                                        // $(row).remove();
                                    });
                                }else{
                                    swal(
                                        'خطا در حذف!',
                                        'سیستم قادر به حذف نیست، مجدد سعی نمایید.',
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
        });

    </script>
@endsection