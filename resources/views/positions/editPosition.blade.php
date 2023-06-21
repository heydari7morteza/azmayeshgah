



@extends('layouts.main')

@section('header')
    @php $urls = array( array('name'=> 'خانه', 'url' => url('/')) , array('name'=> 'جایگاه' , 'url' => route('showPosition')), array('name'=> 'ویرایش جایگاه' , 'url' => null) ) @endphp
    @include('layouts.partials.header',['title' => 'ویرایش جایگاه' , 'urls' => $urls ])
@endsection


@section('content')
    <!-- general form elements -->
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">ویرایش جایگاه</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form role="form" action="{{route('checkEditPosition' , $position->id)}}" method="POST" >
            @csrf
            <div class="card-body">
                <div class="form-group has-error">
                    <label for="type">نوع</label>
                    <select class="form-control" name="type" aria-label="Default select example">
                        @foreach ($position_option as $key => $value)
                            <option  value="{{$key}}" @if ($position->type == $key) selected @endif>
                                {{$value[$key]}}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group has-error">
                    <label for="type">نوع دستگاه</label>
                    <select class="form-control" name="device_type" aria-label="Default select example">
                        @foreach ($device_option as $key => $value)
                            <option  value="{{$key}}" @if ($key == $position->device_type) selected @endif>
                                {{$value[$key]}}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group has-error">
                    <label for="name">نام</label>
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{$position->name}}" aria-label="Small" aria-describedby="inputGroup-sizing-sm" placeholder="نام">
                    @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>توضیحات</label>
                    <textarea name="description" class="form-control" rows="3"  placeholder="توضیحات ...">{{$position->description}}</textarea>
                </div>
            </div>
            <!-- /.card-body -->

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">ذخیره</button>
                <a class="btn btn-danger" href="{{route('showPosition')}}">انصراف</a>
            </div>
        </form>
    </div>
    <!-- /.card -->

@endsection