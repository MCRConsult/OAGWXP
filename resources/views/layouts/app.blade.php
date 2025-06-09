<!DOCTYPE html>
<html lang="en">

<head>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> สำนักงานอัยการสูงสุด </title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
    <link rel="icon" type="image/png" href="{{ asset('favicon-32x32.png') }}" sizes="32x32" />
    <link rel="icon" type="image/png" href="{{ asset('favicon-16x16.png') }}" sizes="16x16" />

    @include('layouts._tag_head')
    @vite('resources/sass/app.css')
</head>

<style type="text/css">
    [v-cloak] > * { display:none }
</style>

<body class="app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show pb-5">
    @include('layouts._header')
    <div class="app-body" id="app" v-cloak>
        <!-- Sidebar -->
        @include('layouts._sidebar')
        <main class="main">
            @include('layouts._breadcrumb')
            @if (session('db_name') != 'PROD')
                <div class="row">
                    <div class="col-12 p-2 p-xs b-r-sm" style="background-color: #e3302f;">
                        <h5 class="no-margins text-center mb-0" style="color: #fff;">
                            *** สำหรับทดสอบระบบเท่านั้น-(DB : {{ session('db_name') }}) ***
                        </h5>
                    </div>
                </div>
            @endif
            <div class="container-fluid">
                @include('shared._errors')
                @include('shared._success')
                <div class="animated fadeIn">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>
    
    {{-- <script src="{{ asset('js/app.js') }}"></script> --}}
    <script src="{{ asset('/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('js/assets/font-awesome-all-5.6.3.js') }}"></script>
    <script src="{{ asset('ckeditor5-build-classic/ckeditor.js') }}"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>

    @vite('resources/js/app.js')
    <script>
        // show file name on selected file
        // $('.custom-file-input').on('change', function() {
        //     let fileName = $(this).val().split('\\').pop();
        //     $(this).next('.custom-file-label').addClass("selected").html(fileName);
        // });

        // document.querySelectorAll('.nav-dropdown-toggle').forEach(function (toggle) {
        //     toggle.addEventListener('click', function (e) {
        //         e.preventDefault();
        //         const parentLi = this.closest('.nav-dropdown');
        //         parentLi.classList.toggle('open');
        //     });
        // });
    </script>
    @yield('footer-js')
</body>

</html>
