<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Meisam Masomi">

    <title>یا اله العاصین</title>
    <!-- Bootstrap core CSS -->
    <link href="{{asset("/vendor/bootstrap/css/bootstrap.css")}}" rel="stylesheet">
    <link href="{{asset("/vendor/bootstrap/css/bootstrap-rtl.css")}}" rel="stylesheet">
    <link href="{{asset("/vendor/fontawesome-5.0.9/css/fontawesome-all.min.css")}}" rel="stylesheet">
    <link href="{{asset("/vendor/fontawesome-5.0.9/css/fontawesome.css")}}" rel="stylesheet">
    <link href="{{asset("/vendor/Waves/waves.min.css")}}" rel="stylesheet">
    <link href="{{asset("/vendor/volume/style.css")}}" rel="stylesheet">
    <link href="{{asset("/vendor/loader/style.css")}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset("/assets/css/style.css")}}">
    <link rel="stylesheet" href="{{asset("/assets/css/mobile.css")}}">
    <!-- Custom styles for this template -->
    <style>
        body {
            padding-top: 54px;
        }

        @media (min-width: 992px) {
            body {
                padding-top: 56px;
            }
        }

    </style>

</head>

<body>
<div class="background"></div>
<!-- Page Content -->
<div class="container">
    @yield('main')
</div>



<!-- Bootstrap core JavaScript -->
<script src="{{asset("/vendor/jquery/jquery.min.js")}}"></script>
<script src="{{asset("/vendor/jquery/jquery.min.js")}}"></script>
<script src="{{asset("/vendor/bootstrap/js/bootstrap.bundle.min.js")}}"></script>
<script src="{{asset("/vendor/particleground/jquery.particleground.min.js")}}"></script>
<script src="{{asset("/vendor/fontawesome-5.0.9/js/fontawesome-all.min.js")}}"></script>
<script src="{{asset("/vendor/Waves/waves.min.js")}}"></script>
<script src="{{asset("/vendor/nicescroll/jquery.nicescroll.min.js")}}"></script>
<script src="{{asset("/vendor/wavesurfer/wavesurfer.min.js")}}"></script>
<script src="{{asset("/vendor/wavesurfer/plugin/wavesurfer.cursor.min.js")}}"></script>

<script src="{{asset("/assets/js/mobile.js")}}"></script>
<script src="{{asset("/assets/js/script.js")}}"></script>
<script src="{{asset("/vendor/volume/index.js")}}"></script>
</body>

</html>
