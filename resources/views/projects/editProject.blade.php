








@extends('layouts.main')

@section('header')
    @php $urls = array( array('name'=> 'خانه', 'url' => url('/')) , array('name'=> 'پروژه' , 'url' => route('allProjects')), array('name'=> 'ویرایش پروژه' , 'url' => null) ) @endphp
    @include('layouts.partials.header',['title' => 'ویرایش پروژه' , 'urls' => $urls ])
@endsection


@section('content')
    <!-- general form elements -->
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">ویرایش پروژه</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form role="form" action="{{route('checkEditProject' , $project->id)}}" method="POST" >
            @csrf
            <div class="card-body">
                <div class="form-group has-error">
                    <input id="device_id" type="hidden" class="form-control @error('device_id') is-invalid @enderror" name="device_id"  value="{{$project->device_id}}" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
                    @error('device_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group has-error">
                    <label for="device_type">نام</label>
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{$project->name}}" aria-label="Small" aria-describedby="inputGroup-sizing-sm" placeholder="نام">
                    @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group has-error">
                    <label for="speed_x">سرعت در راستای محور x</label>
                    <input  type="text" class="form-control @error('speed_x') is-invalid @enderror" name="speed_x" aria-label="Small" value="{{$project->getMeta('speed_x')}}" aria-describedby="inputGroup-sizing-sm" >
                    @error('speed_x')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group has-error">
                    <label for="speed_y">سرعت در راستای محور y</label>
                    <input  type="text" class="form-control @error('speed_y') is-invalid @enderror" name="speed_y" aria-label="Small" value="{{$project->getMeta('speed_y')}}"  aria-describedby="inputGroup-sizing-sm">
                    @error('speed_y')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group has-error">
                    <label for="speed_z">سرعت در راستای محور z</label>
                    <input  type="text" class="form-control @error('speed_z') is-invalid @enderror" name="speed_z" aria-label="Small" value="{{$project->getMeta('speed_z')}}" aria-describedby="inputGroup-sizing-sm" >
                    @error('speed_z')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group has-error">
                    <label for="safe_zone">ارتفاع ایمن</label>
                    <input  type="text" class="form-control @error('safe_zone') is-invalid @enderror" name="safe_zone" aria-label="Small" value="{{$project->getMeta('safe_zone')}}" aria-describedby="inputGroup-sizing-sm" >
                    @error('safe_zone')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group has-error">
                    <label for="name">توضیحات</label>
                    <textarea id="description" type="text" class="form-control @error('description') is-invalid @enderror" name="description"  aria-label="Small" aria-describedby="inputGroup-sizing-sm" >{{$project->description}}</textarea>
                    @error('description')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <!-- /.card-body -->

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">ذخیره</button>
                @if (Auth::user()->type == 1)
                    <a class="btn btn-danger" href="{{route('showProject' , $project->device_id)}}">انصراف</a>
                @else
                    <a class="btn btn-danger" href="{{route('allProjects')}}">انصراف</a>
                @endif
            </div>
        </form>
    </div>
    <!-- /.card -->

@endsection







