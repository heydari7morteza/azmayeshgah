<!DOCTYPE html>
<html>
<head>
    @include('layouts.partials.head')
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <p>ورود به سایت</p>
    </div>
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">

            <form action="{{ route('login') }}" method="post">
                @csrf
                <div class="input-group mb-3">
                    <input type="text" class="form-control @error('username') is-invalid @enderror" placeholder="نام کاربری" name="name" required value="{{old('name')}}">
                    <div class="input-group-append">
                        <span class="fa fa-envelope input-group-text"></span>
                    </div>
                    @error('username')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control @error('password') is-invalid @enderror" placeholder="رمز عبور" name="password" required autocomplete="current-password" >
                    <div class="input-group-append">
                        <span class="fa fa-lock input-group-text"></span>
                    </div>
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="checkbox icheck">
                            <label>
                                <input type="checkbox" name="remember"> یاد آوری من
                            </label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">ورود</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

            <p class="mb-1">
                    @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">رمز عبورم را فراموش کرده ام.</a>
                    @endif
            </p>
        </div>
        <!-- /.login-card-body -->
    </div>
</div>
<!-- /.login-box -->

@include('layouts.partials.footer-script')
<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass   : 'iradio_square-blue',
            increaseArea : '20%' // optional
        })
    })
</script>
</body>
</html>
