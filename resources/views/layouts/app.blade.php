<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    
    <title>laravel</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesdesign" name="author" />
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <!-- icon  -->
    <link rel="stylesheet" href="node_modules/@mdi/font/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="node_modules/@mdi/font/css/materialdesignicons.css">
    <link href="{{ asset('assets/css/materialdesignicons.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('assets/css/custom_admin.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('assets/css/datatable.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('assets/css/fontawesome-free/css/all.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <!-- boostrap icon  -->
    <link href="{{ asset('assets/libs/bootstrap-icons/bootstrap-icons.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <!-- aos style css  -->
    <link href="{{ asset('assets/libs/aos/aos.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <!-- swiper style css  -->
    <link href="{{ asset('assets/libs/swiper/swiper-bundle.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <!-- style.css  -->
    <!-- <link href="{{ asset('assets/css/style.css') }}" type="text/css" rel="stylesheet" /> -->
    <!-- <link href="{{ asset('assets/css/authentication.css') }}" type="text/css" rel="stylesheet" /> -->
    <!-- <link href="{{ asset('assets/css/custom.min.css') }}" id="app-style" rel="stylesheet" type="text/css" /> -->
  
    <!-- user-css  -->
    <link href="{{ asset('assets/css/user_css/calendar.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/user_css/color-pink.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/user_css/color.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/user_css/eventCalendar.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/user_css/jquery.cookieBar.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/user_css/main.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/user_css/nprogress.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/user_css/page-tour.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/user_css/responsive.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/user_css/toast-notification.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/user_css/video-player.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/user_css/weather-icon.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/user_css/weather-icons.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/user_css/style.css') }}" id="app-style" rel="stylesheet" type="text/css" />

<body>
    <div id="app">
    </div>
    <!--thư viện jquery -->
    <script src="{{asset('assets/libs/jquery.min.js')}}"></script>
    <!-- Thư viện typer.min.js -->
    <script src="{{asset('assets/libs/typer.min.js')}}"></script>

    <!-- aos -->
    <script src="{{asset('assets/libs/aos/aos.js')}}"></script>
    <!-- swiper  -->
    <script src="{{asset('assets/libs/swiper/swiper-bundle.min.js')}}"></script>

    <script src="{{ mix('assets/js/app.js') }}" type="text/javascript"></script>
    <!-- <script src="{{ asset('assets/js/app2.js') }}" type="text/javascript"></script> -->
    <!-- <script src="{{ asset('assets/js/datatable.js') }}" type="text/javascript"></script> -->

</body>

</html>