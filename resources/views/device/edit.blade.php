@extends('layouts.main')

@section('header')
    @php $urls = array( array('name'=> 'خانه', 'url' => url('/')) , array('name'=> 'دستگاه' , 'url' => route('showDevice' , $device->user->id)), array('name'=> 'ویرایش دستگاه' , 'url' => null) ) @endphp
    @include('layouts.partials.header',['title' => 'ویرایش دستگاه' , 'urls' => $urls ])
@endsection


@section('content')
    <!-- general form elements -->
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">ویرایش دستگاه</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form role="form" action="{{route('editDeviceForm' ,$device->id)}}" method="POST" >
            @csrf
            <div class="card-body">
                <div class="form-group has-error">
                    <label for="type">نوع</label>
                    <select class="form-control" name="type" aria-label="Default select example">
                        @foreach ($device_option as $key => $value)
                            <option  value="{{$key}}" @if ($key == $device->type) {{'selected'}} @endif>
                                {{$value[$key]}}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>توضیحات</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="توضیحات ...">{{$device->description}}</textarea>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox"  name="standard" @php if($device->standard == 1) echo 'checked'; @endphp  id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
                        دستگاه معیار
                    </label>
                </div>
            </div>
            <!-- /.card-body -->

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">ذخیره</button>

                @if (Auth::user()->type == 1)
                    <a class="btn btn-danger" href="{{route('showDevice' , $device->user->id)}}">انصراف</a>
                @else
                    <a class="btn btn-danger" href="{{route('allDevice')}}">انصراف</a>
                @endif
            </div>
        </form>
    </div>
    <!-- /.card -->

@endsection
