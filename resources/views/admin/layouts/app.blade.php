<!DOCTYPE html>
<html lang="fa-IR" dir="rtl">

<head>
    <title>@yield('title')</title>
    @include('admin.layouts.meta')
    @include('admin.layouts.css')
    @yield('css')
</head>

<body>
<!-- [ Pre-loader ] start -->
@include('admin.partials.loader')
<!-- [ Pre-loader ] end -->
<div id="pcoded" class="pcoded">
    <div class="pcoded-overlay-box"></div>
    <div class="pcoded-container navbar-wrapper">
        <!-- [ Header ] start -->
        @include('admin.partials.top')
        <!-- [ Header ] end -->

        <div class="pcoded-main-container">
            <div class="pcoded-wrapper">
                <!-- [ navigation menu ] start -->
                @include('admin.partials.right-side')
                <!-- [ navigation menu ] end -->
                <div class="pcoded-content">
                    <!-- [ breadcrumb ] start -->
                    @yield('breadcrumb')
                    <!-- [ breadcrumb ] end -->
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</div>
@include('admin.layouts.js')
@yield('js')
</body>

</html>
