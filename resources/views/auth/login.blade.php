@extends('layouts.blank')
<head>
    <title> สำนักงานอัยการสูงสุด </title>
</head>
@section('custom-css')
<style >
    .loginColumns {
        max-width: 800px;
        margin: 0 auto;
        padding: 150px 20px 20px 20px;
    }

    body {
        background-color: #ef6c00 !important;
    }

</style>
@stop

@section('content')
    <div class="container" style="width: auto;">
        <div class="loginColumns animated fadeInDown">
            @include('shared._success')
            @include('shared._errors')
            <div class="card">
                <div class="card-body">
                    <div class="row co-12">
                        <div class="col-md-6 b-r">
                            <div class="clearfix" style="text-align: center;">
                                <p class="logo-name-mini hidden-xs">
                                    <img src="{{ asset('images/oag-login.png') }}" style="height: 180px; margin-top: 18px;">
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="ibox-content">
                                @if (Session::has('err_login') && Session::get('err_login'))
                                    <ul class="list-unstyled alert alert-warning alert-dismissible" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <li>{!! Session::get('err_login') !!}</li>
                                    </ul>
                                @endif
                                <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <div class="col-md-12">
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                                        <div class="col-md-12">
                                            {{-- <input id="username" placeholder="ชื่อผู้ใช้งาน" type="text" class="form-control" name="username" value="{{ old('username') }}" required autofocus> --}}
                                            <input id="username" placeholder="ชื่อผู้ใช้งาน" type="text" class="form-control" name="username"
                                                value="{{ old('username', \Cookie::get('remember_username')) }}" required autofocus>
                                            @if ($errors->has('username'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('username') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                        <div class="col-md-12">
                                            <input id="password" placeholder="รหัสผ่านผู้ใช้งาน" type="password" class="form-control" name="password" autocomplete="off" required value="{{ old('password', Cookie::get('remember_password') ? decrypt(Cookie::get('remember_password')) : '') }}">
                                            {{-- value="{{ old('password', Cookie::get('remember_password') ? decrypt(Cookie::get('remember_password')) : '') }}" --}}
                                            <i class="bi bi-eye-slash bi-xl"
                                                id="togglePassword"
                                                style="
                                                    font-size: 1.8rem;
                                                    float: right;
                                                    margin-right: 15px;
                                                    margin-top: -30px;
                                                    position: relative;
                                                    z-index: 2;
                                                    color: black;"
                                                ></i>

                                            @if ($errors->has('password'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('password') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <input type="checkbox" name="remember"> จำฉันไว้ในระบบ
                                        </div>
                                    </div>

                                    <div class="form-group" style="margin-bottom: 0px;">
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary btn-block full-width m-b">
                                                เข้าสู่ระบบ
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        const togglePassword = document.querySelector("#togglePassword");
        const password = document.querySelector("#password");

        togglePassword.addEventListener("click", function () {
            // toggle the type attribute
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);

            // toggle the icon
            this.classList.toggle("bi-eye");
        });
    </script>
@stop
