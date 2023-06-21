@extends('layouts.main')

@section('header')
    @php $urls = array( array('name'=> 'خانه', 'url' => url('/')) , array('name'=> 'کاربر' , 'url' => null)) @endphp
    @include('layouts.partials.header',['title' => 'کاربران' , 'urls' => $urls ])
@endsection


@section('content')
    @if(session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @endif
    <a href="{{route('addUserForm')}}"><button class="btn btn-primary mb-4" type="submit">اضافه کردن کاربر</button></a>
    <table id="table" class="table table-bordered table-hover mt-4">
        <thead>
        <tr>
            <th>نام کاربری</th>
            <th>ایمیل</th>
            <th>نوع</th>
            <th>عملیات</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($users as $user)
            <tr>
                <td>{{$user->name}}</td>
                <td>{{$user->email}}</td>
                <td>
                    @foreach ($user_option as $key => $value)
                        @if ($key == $user->type)
                            {{$value[$key]}}
                        @endif
                    @endforeach
                </td>
                <td>
                    <a href="{{route('showDevice' , $user->id)}}"><button class="action-btn btn btn-primary" type="submit">دستگاه ها</button></a>
                    <a href="{{route('editUser' , $user->id)}}"><button class="action-btn btn btn-primary" type="submit">ویرایش</button></a>



                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmation{{$user->id}}">
                        حذف
                    </button>
                    <div class="modal fade" id="confirmation{{$user->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">حذف کاربر {{$user->name}}</h5>
                                </div>
                                <div class="modal-body">
                                    آیا از حذف کاربر {{$user->name}} اطمینان دارید؟
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">انصراف</button>
                                    <a  href="{{route('deleteUser' , $user->id)}}"><button class="action-btn btn btn-danger" type="submit">حذف</button></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <th>نام کاربری</th>
            <th>ایمیل</th>
            <th>نوع</th>
            <th>عملیات</th>
        </tr>
        </tfoot>
    </table>

@endsection
