<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge" />
    <!-- กำหนด Cache-Control เพื่อป้องกันการแคช -->
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
    {{-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> --}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title> สำนักงานอัยการสูงสุด </title>
    @include('layouts._tag_head')
    
    {{-- <link rel="shortcut icon" href="/images/title.png" type="image/png" /> --}}
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
    @yield('custom-css')

    @vite('resources/sass/app.css')
</head>

<body class="app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show pb-5">
    <div id="app" class="light-grey-bgx" style="height:100%;">
        <div id="wrapper">
            <div class="wrapper wrapper-content">  
                @yield('content')
            </div>
        </div>
    </div>

@section('scripts')
@show

</body>
</html>
