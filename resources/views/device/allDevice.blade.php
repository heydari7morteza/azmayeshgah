



@extends('layouts.main')

@section('header')
    @php $urls = array( array('name'=> 'خانه', 'url' => url('/')) , array('name'=> 'دستگاه ها' , 'url' => null)) @endphp
    @include('layouts.partials.header',['title' => 'دستگاه ها' , 'urls' => $urls ])
@endsection


@section('content')
    @if(session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @endif
    {{-- <a href="{{route('createDevice' , $id)}}"><button class="btn btn-primary mb-4" type="submit">ایجاد دستگاه</button></a> --}}
    <table id="table" class="table table-bordered table-hover mt-4">
        <thead>
        <tr>
            <th>نام کاربری</th>
            <th>نوع دستگاه</th>
            <th>استاندارد</th>
            <th>عملیات</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($devices as $device)
            <tr>
                <td>{{$device->user->name}}</td>
                <td>
                    @foreach ($device_option as $key => $value)
                        @if ($key == $device->type)
                        {{$value[$key]}}
                        @endif
                    @endforeach
                </td>
                <td>{{$device->standard}}</td>
                <td>
                    <a href="{{route('devicePosition' , $device->id)}}"><button class="btn btn-primary" type="submit">جایگاه ها</button></a>
                    <a href="{{route('showProject' , $device->id)}}"><button class="btn btn-primary" type="submit">پروژه ها</button></a>
                    <a href="{{route('editDevice' , $device->id)}}"><button class="btn btn-primary" type="submit">ویرایش</button></a>

                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmation{{$device->id}}">
                        حذف
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="confirmation{{$device->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">حذف دستگاه </h5>
                                </div>
                                <div class="modal-body">
                                    آیا از حذف دستگاه اطمینان دارید؟
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">انصراف</button>
                                    <a  href="{{route('deleteDevice' , $device->id)}}"><button class="action-btn btn btn-danger" type="submit">حذف</button></a>
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
            <th>نوع دستگاه</th>
            <th>استاندارد</th>
            <th>عملیات</th>
        </tr>
        </tfoot>
    </table>

@endsection
