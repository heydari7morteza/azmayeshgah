






@extends('layouts.main')

@section('header')
    @php $urls = array( array('name'=> 'خانه', 'url' => url('/')) , array('name'=> 'مایع' , 'url' => route('all_liquids')), array('name'=> 'ویرایش مایع' , 'url' => null) ) @endphp
    @include('layouts.partials.header',['title' => 'ویرایش مایع' , 'urls' => $urls ])
@endsection


@section('content')
    <!-- general form elements -->
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">ویرایش مایع</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form role="form" action="{{route('checkEditLiquidProject' , $liquid->id)}}" method="POST" >
            @csrf
            <div class="card-body">
                <div class="form-group has-error">
                    <input id="project_id" type="hidden" class="form-control @error('project_id') is-invalid @enderror" name="project_id" value="<?php if($liquid->project_id) $liquid->project_id?>"  aria-label="Small" aria-describedby="inputGroup-sizing-sm">
                    @error('project_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group has-error">
                    <input  type="hidden" class="form-control @error('user_id') is-invalid @enderror" name="user_id" value="{{Auth::user()->id}}"  aria-label="Small" aria-describedby="inputGroup-sizing-sm">
                    @error('user_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group has-error">
                    <label for="name">نام</label>
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" aria-label="Small" value="{{ $liquid->name }}" aria-describedby="inputGroup-sizing-sm" placeholder="نام ">
                    @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                

                <div class="form-group has-error">
                    <label for="volume">مقدار</label>
                    <input id="volume" type="text" class="form-control @error('volume') is-invalid @enderror" name="volume" aria-label="Small" value="{{ $liquid->volume }}" aria-describedby="inputGroup-sizing-sm" placeholder="مقدار ">
                    @error('volume')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group has-error">
                    <label for="density">تراکم</label>
                    <input id="density" type="text" class="form-control @error('density') is-invalid @enderror" name="density" aria-label="Small" value="{{ $liquid->density }}" aria-describedby="inputGroup-sizing-sm" placeholder="تراکم ">
                    @error('density')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>توضیحات</label>
                    <textarea name="description" class="form-control"placeholder="توضیحات" rows="3">{{ $liquid->description }}</textarea>
                </div>
            </div>
            <!-- /.card-body -->

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">ذخیره</button>
                @if ($liquid->project_id)
                    <a class="btn btn-danger" href="{{route('liquidProject' , $liquid->project_id)}}">انصراف</a>
                @else
                    <a class="btn btn-danger" href="{{route('userShowLiquids' , $liquid->user_id)}}">انصراف</a>
                @endif
                
            </div>
        </form>
    </div>
    <!-- /.card -->

@endsection


















