

@extends('layouts.main')

@section('header')
    @php $urls = array( array('name'=> 'خانه', 'url' => url('/')) , array('name'=> 'موجودیت' , 'url' => route('entityInfo')), array('name'=> 'ایجاد موجودیت' , 'url' => null) ) @endphp
    @include('layouts.partials.header',['title' => 'اضافه کردن موجودیت' , 'urls' => $urls ])
@endsection


@section('content')
    <!-- general form elements -->
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">ایجاد موجودیت</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->

        <form role="form" action="{{route('addEntity')}}" method="POST" >
            @csrf
            <div class="card-body">
                <div class="form-group has-error">
                    <label for="name">نام</label>
                    <input id="type" type="text" class="form-control @error('name') is-invalid @enderror" name="name" aria-label="Small" value="{{old('name')}}" 
                    required
                    oninvalid="this.setCustomValidity('لطفا فیلد را پر کنید')"
                    oninput="this.setCustomValidity('')"
                     aria-describedby="inputGroup-sizing-sm" placeholder="نام">
                    @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                
                <div class="form-group has-error">
                    <label for="name">نوع</label>
                    <select class="form-control" name="type" id="key_option"  aria-label="Default select example">
                        @foreach ($entity_option as $key => $value)
                            <option  value="{{$key}}">
                                {{$value[$key]}}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div  style="display:none" id="module_type">
                    <div class="form-group has-error" >
                        <label for="rest_position">تعداد سطر</label>
                        <input type="text" class="form-control @error('row_module') is-invalid @enderror" name="row_module" aria-label="Small" value="{{old('row_module')}}" 
                        oninvalid="this.setCustomValidity('لطفا فیلد را پر کنید')"
                        oninput="this.setCustomValidity('')"
                        aria-describedby="inputGroup-sizing-sm" placeholder="تعداد سطر">
                        @error('row_module')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group has-error" >
                        <label for="falcon_height">تعداد ستون</label>
                        <input  type="text" class="form-control @error('col_module') is-invalid @enderror" name="col_module" aria-label="Small" value="{{old('col_module')}}" 
                        oninvalid="this.setCustomValidity('لطفا فیلد را پر کنید')"
                        oninput="this.setCustomValidity('')"
                        aria-describedby="inputGroup-sizing-sm" placeholder="تعداد ستون">
                        @error('col_module')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div  style="display:none" id="sampler_type">

                    <div class="form-group has-error" >
                        <label for="volume">حجم</label>
                        <input  type="text" class="form-control @error('volume') is-invalid @enderror" name="volume" aria-label="Small" value="{{old('volume')}}"
                        oninvalid="this.setCustomValidity('لطفا فیلد را پر کنید')"
                        oninput="this.setCustomValidity('')"
                        aria-describedby="inputGroup-sizing-sm" placeholder="حجم را وارد کنید">
                        @error('volume')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group has-error" >
                        <label for="rest_position">rest position</label>
                        <input id="rest_position" type="text" class="form-control @error('rest_position') is-invalid @enderror" name="rest_position" aria-label="Small" value="{{old('rest_position')}}" 
                        oninvalid="this.setCustomValidity('لطفا فیلد را پر کنید')"
                        oninput="this.setCustomValidity('')"
                        aria-describedby="inputGroup-sizing-sm" >
                        @error('rest_position')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group has-error" >
                        <label for="falcon_height">first position</label>
                        <input id="first_position" type="text" class="form-control @error('first_position') is-invalid @enderror" name="first_position" aria-label="Small" value="{{old('first_position')}}"
                        oninvalid="this.setCustomValidity('لطفا فیلد را پر کنید')"
                        oninput="this.setCustomValidity('')"
                        aria-describedby="inputGroup-sizing-sm" >
                        @error('first_position')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group has-error" >
                        <label for="falcon_height">second position</label>
                        <input id="second_position" type="text" class="form-control @error('second_position') is-invalid @enderror" name="second_position" aria-label="Small" value="{{old('second_position')}}" 
                        oninvalid="this.setCustomValidity('لطفا فیلد را پر کنید')"
                        oninput="this.setCustomValidity('')"
                        aria-describedby="inputGroup-sizing-sm" >
                        @error('second_position')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group has-error" >
                        <label for="falcon_height">height</label>
                        <input id="sampler_height" type="text" class="form-control @error('sampler_height') is-invalid @enderror" name="sampler_height" aria-label="Small" value="{{old('sampler_height')}}" 
                        oninvalid="this.setCustomValidity('لطفا فیلد را پر کنید')"
                        oninput="this.setCustomValidity('')"
                        aria-describedby="inputGroup-sizing-sm" placeholder="ارتفاع سمپلر">
                        @error('sampler_height')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group has-error" style="display:none" id="falcon_input">
                    <label for="falcon_height">ارتفاع فالکون</label>

                    <input id="falcon_height" type="text" class="form-control @error('falcon_height') is-invalid @enderror" name="falcon_height" aria-label="Small" value="{{old('falcon_height')}}"  
                    oninvalid="this.setCustomValidity('لطفا فیلد را پر کنید')"
                    oninput="this.setCustomValidity('')"
                    aria-describedby="inputGroup-sizing-sm" placeholder="ارتفاع فالکون">
                    @error('falcon_height')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div  id="tip_falcon" style="display:none">

                    <div class="form-group has-error">
                        <label for="tip_volume">حجم تیپ</label>
                        <input  type="text" class="form-control @error('tip_volume') is-invalid @enderror" name="tip_volume" aria-label="Small" value="{{old('tip_volume')}}" 
                        oninvalid="this.setCustomValidity('لطفا فیلد را پر کنید')"
                        oninput="this.setCustomValidity('')"
                        aria-describedby="inputGroup-sizing-sm" placeholder="حجم تیپ">
                        @error('tip_volume')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group has-error">
                        <label for="height_tip">ارتفاع تیپ</label>
                        <input  type="text" class="form-control @error('height_tip') is-invalid @enderror" name="height_tip" aria-label="Small" value="{{old('height_tip')}}" 
                        oninvalid="this.setCustomValidity('لطفا فیلد را پر کنید')"
                        oninput="this.setCustomValidity('')"
                        aria-describedby="inputGroup-sizing-sm" placeholder="ارتفاع تیپ">
                        @error('height_tip')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group has-error">
                        <label for="name">نقطه a</label>
                       <div class="row">
                        <div class="col-4">
                            <input id="t_a_x" type="text" class="form-control @error('t_a_x') is-invalid @enderror" name="t_a_x" aria-label="Small" value="{{old('t_a_x')}}" 
                            oninvalid="this.setCustomValidity('لطفا فیلد را پر کنید')"
                            oninput="this.setCustomValidity('')"
                            aria-describedby="inputGroup-sizing-sm" placeholder="x">
                            @error('t_a_x')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="col-4">
                            <input id="t_a_y" type="text" class="form-control @error('t_a_y') is-invalid @enderror" name="t_a_y" aria-label="Small" value="{{old('t_a_y')}}" 
                            oninvalid="this.setCustomValidity('لطفا فیلد را پر کنید')"
                            oninput="this.setCustomValidity('')"
                            aria-describedby="inputGroup-sizing-sm" placeholder="y">
                            @error('t_a_y')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="col-4">
                            <input id="t_a_z" type="text" class="form-control @error('t_a_z') is-invalid @enderror" name="t_a_z" aria-label="Small" value="{{old('t_a_z')}}" 
                            oninvalid="this.setCustomValidity('لطفا فیلد را پر کنید')"
                            oninput="this.setCustomValidity('')"
                            aria-describedby="inputGroup-sizing-sm" placeholder="z">
                            @error('t_a_z')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                       </div>

                    </div>

                    <div class="form-group has-error">
                        <label for="name">نقطه b</label>

                       <div class="row">
                        <div class="col-4">
                            <input  type="text" class="form-control @error('t_b_x') is-invalid @enderror" name="t_b_x" aria-label="Small" value="{{old('t_b_x')}}" 
                            oninvalid="this.setCustomValidity('لطفا فیلد را پر کنید')"
                            oninput="this.setCustomValidity('')"
                            aria-describedby="inputGroup-sizing-sm" placeholder="x">
                            @error('t_b_x')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="col-4">
                            <input type="text" class="form-control @error('t_b_y') is-invalid @enderror" name="t_b_y" aria-label="Small" value="{{old('t_b_y')}}" 
                            oninvalid="this.setCustomValidity('لطفا فیلد را پر کنید')"
                            oninput="this.setCustomValidity('')"
                            aria-describedby="inputGroup-sizing-sm" placeholder="y">
                            @error('t_b_y')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="col-4">
                            <input  type="text" class="form-control @error('t_b_z') is-invalid @enderror" name="t_b_z" aria-label="Small" value="{{old('t_b_z')}}" 
                            oninvalid="this.setCustomValidity('لطفا فیلد را پر کنید')"
                            oninput="this.setCustomValidity('')"
                            aria-describedby="inputGroup-sizing-sm" placeholder="z">
                            @error('t_b_z')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                       </div>
                    </div>

                    <div class="form-group has-error">
                        <label for="name">نقطه c</label>

                       <div class="row">
                        <div class="col-4">
                            <input  type="text" class="form-control @error('t_c_x') is-invalid @enderror" name="t_c_x" aria-label="Small" value="{{old('t_c_x')}}"
                            oninvalid="this.setCustomValidity('لطفا فیلد را پر کنید')"
                            oninput="this.setCustomValidity('')"
                            aria-describedby="inputGroup-sizing-sm" placeholder="x">
                            @error('t_c_x')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="col-4">
                            <input  type="text" class="form-control @error('t_c_y') is-invalid @enderror" name="t_c_y" aria-label="Small" value="{{old('t_c_y')}}"
                            oninvalid="this.setCustomValidity('لطفا فیلد را پر کنید')"
                            oninput="this.setCustomValidity('')"
                            aria-describedby="inputGroup-sizing-sm" placeholder="y">
                            @error('t_c_y')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="col-4">
                            <input  type="text" class="form-control @error('t_c_z') is-invalid @enderror" name="t_c_z" aria-label="Small" value="{{old('t_c_z')}}"
                            oninvalid="this.setCustomValidity('لطفا فیلد را پر کنید')"
                            oninput="this.setCustomValidity('')"
                            aria-describedby="inputGroup-sizing-sm" placeholder="z">
                            @error('t_c_z')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                       </div>
                    </div>

                    <div class="form-group has-error">
                        <label for="name">فاصله هر ستون از هم</label>

                        <input  type="text" class="form-control @error('t_col_interval') is-invalid @enderror" name="t_col_interval" aria-label="Small" value="{{old('t_col_interval')}}" aria-describedby="inputGroup-sizing-sm" placeholder="فاصله هر ستون">
                        @error('t_col_interval')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group has-error">
                        <label for="name">فاصله هر ردیف</label>

                        <input  type="text" class="form-control @error('t_row_interval') is-invalid @enderror" name="t_row_interval" aria-label="Small" value="{{old('t_row_interval')}}" aria-describedby="inputGroup-sizing-sm" placeholder="فاصله هر ردیف">
                        @error('t_row_interval')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group has-error">
                        <label for="height">ارتفاع ظرف</label>

                        <input  type="text" class="form-control @error('t_height') is-invalid @enderror" name="t_height" aria-label="Small" value="{{old('t_height')}}" aria-describedby="inputGroup-sizing-sm" placeholder="ارتفاع ظرف">
                        @error('t_height')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group has-error">
                        <label for="name">مقدار ارتفاع لازم برای ورود به تیپ</label>

                        <input id="t_offset" type="text" class="form-control @error('t_offset') is-invalid @enderror" name="t_offset" aria-label="Small" value="{{old('t_offset')}}"
                        oninvalid="this.setCustomValidity('لطفا فیلد را پر کنید')"
                        oninput="this.setCustomValidity('')"
                        aria-describedby="inputGroup-sizing-sm" placeholder="مقدار ارتفاع لازم برای ورود به تیپ">
                        @error('t_offset')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group has-error">
                        <label for="t_row">تعداد ردیف</label>

                        <input  type="text" class="form-control @error('t_row') is-invalid @enderror" name="t_row" aria-label="Small" value="{{old('t_row')}}"
                        oninvalid="this.setCustomValidity('لطفا فیلد را پر کنید')"
                        oninput="this.setCustomValidity('')"
                        aria-describedby="inputGroup-sizing-sm" placeholder="تعداد ردیف">
                        @error('t_row')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group has-error">
                        <label for="t_col">تعداد ستون</label>
                        <input  type="text" class="form-control @error('t_col') is-invalid @enderror" name="t_col" aria-label="Small" value="{{old('t_col')}}"
                        oninvalid="this.setCustomValidity('لطفا فیلد را پر کنید')"
                        oninput="this.setCustomValidity('')"
                        aria-describedby="inputGroup-sizing-sm" placeholder="تعداد ستون">
                        @error('t_col')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>



                <div class="container-all-input" id="container_all_input">

                    <div class="form-group has-error">
                        <label for="tube_volume">حجم تیوپ</label>
                        <input  type="text" class="form-control @error('tube_volume') is-invalid @enderror" name="tube_volume" aria-label="Small" value="{{old('tube_volume')}}"
                        oninvalid="this.setCustomValidity('لطفا فیلد را پر کنید')"
                        oninput="this.setCustomValidity('')"
                        aria-describedby="inputGroup-sizing-sm" placeholder="حجم تیوب">
                        @error('tube_volume')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group has-error">
                        <label for="height_tube">ارتفاع تیوپ</label>
                        <input  type="text" class="form-control @error('height_tube') is-invalid @enderror" name="height_tube" aria-label="Small" value="{{old('height_tube')}}" 
                        oninvalid="this.setCustomValidity('لطفا فیلد را پر کنید')"
                        oninput="this.setCustomValidity('')"
                         aria-describedby="inputGroup-sizing-sm" placeholder="ارتفاع تیوب">
                        @error('height_tube')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group has-error">
                        <label for="name">نقطه a</label>

                       <div class="row">
                        <div class="col-4">
                            <input id="point_a_x" type="text" class="form-control @error('point_a_x') is-invalid @enderror" name="point_a_x" aria-label="Small" value="{{old('point_a_x')}}"
                            oninvalid="this.setCustomValidity('لطفا فیلد را پر کنید')"
                            oninput="this.setCustomValidity('')"
                            aria-describedby="inputGroup-sizing-sm" placeholder="x">
                            @error('point_a_x')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="col-4">
                            <input id="point_a_y" type="text" class="form-control @error('point_a_y') is-invalid @enderror" name="point_a_y" aria-label="Small" value="{{old('point_a_y')}}" 
                            oninvalid="this.setCustomValidity('لطفا فیلد را پر کنید')"
                            oninput="this.setCustomValidity('')"
                             aria-describedby="inputGroup-sizing-sm" placeholder="y">
                            @error('point_a_y')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="col-4">
                            <input id="point_a_z" type="text" class="form-control @error('point_a_z') is-invalid @enderror" name="point_a_z" aria-label="Small" value="{{old('point_a_z')}}" 
                            oninvalid="this.setCustomValidity('لطفا فیلد را پر کنید')"
                            oninput="this.setCustomValidity('')"
                            aria-describedby="inputGroup-sizing-sm" placeholder="z">
                            @error('point_a_z')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                       </div>

                    </div>

                    <div class="form-group has-error">
                        <label for="name">نقطه b</label>

                       <div class="row">
                        <div class="col-4">
                            <input id="point_b_x" type="text" class="form-control @error('point_b_x') is-invalid @enderror" name="point_b_x" aria-label="Small" value="{{old('point_b_x')}}" 
                            oninvalid="this.setCustomValidity('لطفا فیلد را پر کنید')"
                            oninput="this.setCustomValidity('')"
                             aria-describedby="inputGroup-sizing-sm" placeholder="x">
                            @error('point_b_x')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="col-4">
                            <input id="point_b_y" type="text" class="form-control @error('point_b_y') is-invalid @enderror" name="point_b_y" aria-label="Small" value="{{old('point_b_y')}}" 
                            oninvalid="this.setCustomValidity('لطفا فیلد را پر کنید')"
                            oninput="this.setCustomValidity('')"
                            aria-describedby="inputGroup-sizing-sm" placeholder="y">
                            @error('point_b_y')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="col-4">
                            <input id="point_b_z" type="text" class="form-control @error('point_b_z') is-invalid @enderror" name="point_b_z" aria-label="Small" value="{{old('point_b_z')}}" 
                            oninvalid="this.setCustomValidity('لطفا فیلد را پر کنید')"
                            oninput="this.setCustomValidity('')"
                             aria-describedby="inputGroup-sizing-sm" placeholder="z">
                            @error('point_b_z')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                       </div>
                    </div>

                    <div class="form-group has-error">
                        <label for="name">نقطه c</label>

                       <div class="row">
                        <div class="col-4">
                            <input  type="text" class="form-control @error('point_c_x') is-invalid @enderror" name="point_c_x" aria-label="Small" value="{{old('point_c_x')}}"
                            oninvalid="this.setCustomValidity('لطفا فیلد را پر کنید')"
                            oninput="this.setCustomValidity('')"
                            aria-describedby="inputGroup-sizing-sm" placeholder="x">
                            @error('point_c_x')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="col-4">
                            <input id="point_c_y" type="text" class="form-control @error('point_c_y') is-invalid @enderror" name="point_c_y" aria-label="Small" value="{{old('point_c_y')}}" 
                            oninvalid="this.setCustomValidity('لطفا فیلد را پر کنید')"
                            oninput="this.setCustomValidity('')"
                            aria-describedby="inputGroup-sizing-sm" placeholder="y">
                            @error('point_c_y')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="col-4">
                            <input  type="text" class="form-control @error('point_c_z') is-invalid @enderror" name="point_c_z" aria-label="Small" value="{{old('point_c_z')}}" 
                            oninvalid="this.setCustomValidity('لطفا فیلد را پر کنید')"
                            oninput="this.setCustomValidity('')"
                            aria-describedby="inputGroup-sizing-sm" placeholder="z">
                            @error('point_c_z')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                       </div>
                    </div>

                    <div class="form-group has-error">
                        <label for="name">فاصله هر ستون از هم</label>

                        <input id="col_interval" type="text" class="form-control @error('col_interval') is-invalid @enderror" name="col_interval" aria-label="Small" value="{{old('col_interval')}}" aria-describedby="inputGroup-sizing-sm" placeholder="فاصله هر ستون">
                        @error('col_interval')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group has-error">
                        <label for="name">فاصله هر ردیف</label>

                        <input id="row_interval" type="text" class="form-control @error('row_interval') is-invalid @enderror" name="row_interval" aria-label="Small" value="{{old('row_interval')}}" aria-describedby="inputGroup-sizing-sm" placeholder="فاصله هر ردیف">
                        @error('row_interval')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group has-error">
                        <label for="height">ارتفاع ظرف</label>

                        <input id="height" type="text" class="form-control @error('height') is-invalid @enderror" name="height" aria-label="Small" value="{{old('height')}}" aria-describedby="inputGroup-sizing-sm" placeholder="ارتفاع ظرف">
                        @error('height')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group has-error">
                        <label for="row">تعداد ردیف</label>

                        <input id="row" type="text" class="form-control @error('row') is-invalid @enderror" name="row" aria-label="Small" value="{{old('row')}}" 
                        oninvalid="this.setCustomValidity('لطفا فیلد را پر کنید')"
                        oninput="this.setCustomValidity('')"
                        aria-describedby="inputGroup-sizing-sm" placeholder="تعداد ردیف">
                        @error('row')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group has-error">
                        <label for="col">تعداد ستون</label>
                        <input id="col" type="text" class="form-control @error('col') is-invalid @enderror" name="col" aria-label="Small" value="{{old('col')}}" 
                        oninvalid="this.setCustomValidity('لطفا فیلد را پر کنید')"
                        oninput="this.setCustomValidity('')"
                        aria-describedby="inputGroup-sizing-sm" placeholder="تعداد ستون">
                        @error('col')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>


                <div class="form-group has-error" id="position_type_match" >
                    <label for="device_type">نوع جایگاه</label>
                    <select class="form-control" name="position_type_match" aria-label="Default select example">
                        @foreach ($position_option as $key => $value)
                            <option  value="{{$key}}">
                                {{$value[$key]}}
                            </option>
                        @endforeach
                    </select>

                </div>

                <div class="form-group has-error" style="display:none" id="key_option_protocol">
                    <label for="device_type">نوع پروتکل</label>
                    <select class="form-control" name="module_type_match"  aria-label="Default select example">
                        @foreach ($module_option as $key => $value)
                            <option  value="{{$key}}">
                                {{$value[$key]}}
                            </option>
                        @endforeach
                    </select>
                </div>


                <div class="form-group">
                    <label>توضیحات</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="توضیحات ...">{{old('description')}}</textarea>
                </div>
            </div>
            <!-- /.card-body -->

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">ذخیره</button>
                <a class="btn btn-danger" href="{{route('entityInfo')}}">انصراف</a>
            </div>
        </form>
    </div>
    <!-- /.card -->

@endsection
