<!DOCTYPE html>
<html lang="fa-IR" dir="rtl">

<head>
    <title>@yield('title')</title>
    @include('admin.layouts.meta')
    @include('admin.layouts.back-css')
    @yield('css')
</head>

<body>
<!-- [ Pre-loader ] start -->
@include('admin.partials.loader')
<!-- [ Pre-loader ] end -->
<div id="pcoded" class="pcoded">
    <div class="pcoded-overlay-box"></div>
    <div class="pcoded-container navbar-wrapper">

        <div class="pcoded-main-container">
            <div class="pcoded-wrapper">

                <div class="pcoded-content">

                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</div>
@include('admin.layouts.back-js')
@yield('js')
</body>

</html>
