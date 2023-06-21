@extends('layouts.main')

@section('header')
    @php $urls = array( array('name'=> 'خانه', 'url' => url('/')) , array('name'=> 'کاربر' , 'url' => url('/user/userInfo')), array('name'=> 'افزودن کاربر' , 'url' => null) ) @endphp
    @include('layouts.partials.header',['title' => 'افزودن کاربر' , 'urls' => $urls ])
@endsection


@section('content')
    <!-- general form elements -->
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">افزودن کاربر</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form role="form" action="{{route('addUser')}}" method="POST" >
            @csrf
            <div class="card-body">
                <div class="form-group has-error">
                    <label for="name">نام کاربری</label>
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" aria-label="Small" aria-describedby="inputGroup-sizing-sm" placeholder="نام کاربری">
                    @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group has-error">
                    <label for="name">نوع</label>
                    <select class="form-control" name="type" aria-label="Default select example">
                        @foreach ($user_option as $key => $value)
                            <option  value="{{$key}}"  @if ($user->type == $key) selected @endif>
                                {{$value[$key]}}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group has-error">
                    <label for="email">ایمیل</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" aria-label="Small" aria-describedby="inputGroup-sizing-sm" placeholder="ایمیل">
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group has-error">
                    <label for="password">رمز عبور</label>
                    <input  type="password" class="form-control @error('password') is-invalid @enderror" name="password" aria-label="Small" aria-describedby="inputGroup-sizing-sm" placeholder="رمز عبور">
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group has-error">
                    <label for="password_confirmation">تاییدیه رمز عبور</label>
                    <input id="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" aria-label="Small" aria-describedby="inputGroup-sizing-sm" placeholder="تاییدیه رمز عبور">
                    @error('password_confirmation')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <!-- /.card-body -->

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">ذخیره</button>
                <a class="btn btn-danger" href="./userInfo">انصراف</a>
            </div>
        </form>
    </div>
    <!-- /.card -->

@endsection
