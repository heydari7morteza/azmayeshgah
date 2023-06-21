








@extends('layouts.main')

@section('header')
    @php $urls = array( array('name'=> 'خانه', 'url' => url('/')) , array('name'=> 'جایگاه دستگاه' , 'url' => route('devicePosition',$id)), array('name'=> 'ایجاد جایگاه دستگاه' , 'url' => null) ) @endphp
    @include('layouts.partials.header',['title' => 'ایجاد جایگاه دستگاه' , 'urls' => $urls ])
@endsection


@section('content')
    <!-- general form elements -->
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">ایجاد جایگاه دستگاه</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form role="form" action="{{route('checkCreateDevicePosition' , $id)}}" method="POST" >
            @csrf
            <div class="card-body">
                <div class="form-group has-error">
                    <input id="device_id" type="hidden" class="form-control @error('device_id') is-invalid @enderror" name="device_id" value="{{$id}}" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
                    @error('device_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group has-error">
                    <label for="griper_value">نام جایگاه</label>
                    <select class="form-control" id="position_item_selected" name="position_id" aria-label="Default select example" >
                        @foreach ($positions as $position)
                        <option  value="{{$position->id}}" type="{{$position->type}}">
                            {{$position->name}}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div  id="girpper_value_container" class="form-group has-error" style="display:none" >
                    <div class="form-group has-error">
                        <label for="gripper_value">مقدار بسته شدن گریپر</label>
                        <input type="text" class="form-control @error('gripper_value') is-invalid @enderror" name="gripper_value" aria-label="Small" value="{{old('gripper_value')}}" aria-describedby="inputGroup-sizing-sm" placeholder="مقدار بسته شدن گریپر">
                        @error('gripper_value')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group has-error">
                        <label for="name">point a</label>

                        <input  type="text" class="form-control @error('g_a_x') is-invalid @enderror" name="g_a_x" aria-label="Small" value="{{old('g_a_x')}}" aria-describedby="inputGroup-sizing-sm" placeholder="x">
                        @error('g_a_x')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror

                        <input  type="text" class="form-control @error('g_a_y') is-invalid @enderror" name="g_a_y" aria-label="Small" value="{{old('g_a_y')}}" aria-describedby="inputGroup-sizing-sm" placeholder="y">
                        @error('g_a_y')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror

                        <input  type="text" class="form-control @error('g_a_z') is-invalid @enderror" name="g_a_z" aria-label="Small" value="{{old('g_a_z')}}" aria-describedby="inputGroup-sizing-sm" placeholder="z">
                        @error('g_a_z')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div  id="sampler_value_container" class="form-group has-error" style="display:none" >
                    <div class="form-group has-error">
                        <label for="gripper_value">حجم سمپلر</label>
                        <input type="text" class="form-control @error('sampler_volume') is-invalid @enderror" name="sampler_volume" aria-label="Small" value="{{old('sampler_volume')}}" aria-describedby="inputGroup-sizing-sm" placeholder="حجم سمپلر">
                        @error('sampler_volume')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group has-error">
                        <label for="name">point a</label>

                        <input  type="text" class="form-control @error('f_a_x') is-invalid @enderror" name="f_a_x" aria-label="Small" value="{{old('f_a_x')}}" aria-describedby="inputGroup-sizing-sm" placeholder="x">
                        @error('f_a_x')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror

                        <input  type="text" class="form-control @error('f_a_y') is-invalid @enderror" name="f_a_y" aria-label="Small" value="{{old('f_a_y')}}" aria-describedby="inputGroup-sizing-sm" placeholder="y">
                        @error('f_a_y')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror

                        <input  type="text" class="form-control @error('f_a_z') is-invalid @enderror" name="f_a_z" aria-label="Small" value="{{old('f_a_z')}}" aria-describedby="inputGroup-sizing-sm" placeholder="z">
                        @error('f_a_z')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div  id="folcons_container" style="display:none">
                    <label for="name">فالکون قد کوتاه</label>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group has-error">
                                <input type="text" class="form-control @error('p_a_x') is-invalid @enderror" name="p_a_x" aria-label="Small" value="{{old('p_a_x')}}" aria-describedby="inputGroup-sizing-sm" placeholder="x">
                                @error('p_a_x')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group has-error">
                                <input id="p_a_y" type="text" class="form-control @error('p_a_y') is-invalid @enderror" name="p_a_y" aria-label="Small" value="{{old('p_a_y')}}" aria-describedby="inputGroup-sizing-sm" placeholder="y">
                                @error('p_a_y')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group has-error">
                                <input id="p_a_z" type="text" class="form-control @error('p_a_z') is-invalid @enderror" name="p_a_z" aria-label="Small" value="{{old('p_a_z')}}" aria-describedby="inputGroup-sizing-sm" placeholder="z">
                                @error('p_a_z')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group has-error">
                                <input id="p_b_x" type="text" class="form-control @error('p_b_x') is-invalid @enderror" name="p_b_x" aria-label="Small" value="{{old('p_b_x')}}" aria-describedby="inputGroup-sizing-sm" placeholder="x">
                                @error('p_b_x')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group has-error">
                                <input id="p_b_y" type="text" class="form-control @error('p_b_y') is-invalid @enderror" name="p_b_y" aria-label="Small" value="{{old('p_b_y')}}" aria-describedby="inputGroup-sizing-sm" placeholder="y">
                                @error('p_b_y')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group has-error">
                                <input id="p_b_z" type="text" class="form-control @error('p_b_z') is-invalid @enderror" name="p_b_z" aria-label="Small" value="{{old('p_b_z')}}" aria-describedby="inputGroup-sizing-sm" placeholder="z">
                                @error('p_b_z')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group has-error">
                                <input id="p_c_x" type="text" class="form-control @error('p_c_x') is-invalid @enderror" name="p_c_x" aria-label="Small" value="{{old('p_c_x')}}" aria-describedby="inputGroup-sizing-sm" placeholder="x">
                                @error('p_c_x')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group has-error">
                                <input id="p_c_y" type="text" class="form-control @error('p_c_y') is-invalid @enderror" name="p_c_y" aria-label="Small" value="{{old('p_c_y')}}" aria-describedby="inputGroup-sizing-sm" placeholder="y">
                                @error('p_c_y')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group has-error">
                                <input id="p_c_z" type="text" class="form-control @error('p_c_z') is-invalid @enderror" name="p_c_z" aria-label="Small" value="{{old('p_c_z')}}" aria-describedby="inputGroup-sizing-sm" placeholder="z">
                                @error('p_c_z')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group has-error">
                                <input id="p_d_x" type="text" class="form-control @error('p_d_x') is-invalid @enderror" name="p_d_x" aria-label="Small" value="{{old('p_d_x')}}" aria-describedby="inputGroup-sizing-sm" placeholder="x">
                                @error('p_d_x')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group has-error">
                                <input id="p_d_y" type="text" class="form-control @error('p_d_y') is-invalid @enderror" name="p_d_y" aria-label="Small" value="{{old('p_d_y')}}" aria-describedby="inputGroup-sizing-sm" placeholder="y">
                                @error('p_d_y')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group has-error">
                                <input id="p_d_z" type="text" class="form-control @error('p_d_z') is-invalid @enderror" name="p_d_z" aria-label="Small" value="{{old('p_d_z')}}" aria-describedby="inputGroup-sizing-sm" placeholder="x">
                                @error('p_d_z')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group has-error">
                                <input id="p_e_x" type="text" class="form-control @error('p_e_x') is-invalid @enderror" name="p_e_x" aria-label="Small" value="{{old('p_e_x')}}" aria-describedby="inputGroup-sizing-sm" placeholder="x">
                                @error('p_e_x')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group has-error">
                                <input id="p_e_y" type="text" class="form-control @error('p_e_y') is-invalid @enderror" name="p_e_y" aria-label="Small" value="{{old('p_e_y')}}" aria-describedby="inputGroup-sizing-sm" placeholder="y">
                                @error('p_e_y')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group has-error">
                                <input id="p_e_z" type="text" class="form-control @error('p_e_z') is-invalid @enderror" name="p_e_z" aria-label="Small" value="{{old('p_e_z')}}" aria-describedby="inputGroup-sizing-sm" placeholder="z">
                                @error('p_e_z')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group has-error">
                                <input id="point_a_x" type="text" class="form-control @error('point_a_x') is-invalid @enderror" name="p_f_x" aria-label="Small" value="{{old('p_f_x')}}" aria-describedby="inputGroup-sizing-sm" placeholder="x">
                                @error('point_a_x')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group has-error">
                                <input id="p_f_y" type="text" class="form-control @error('p_f_y') is-invalid @enderror" name="p_f_y" aria-label="Small" value="{{old('p_f_y')}}" aria-describedby="inputGroup-sizing-sm" placeholder="y">
                                @error('p_f_y')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group has-error">
                                <input id="p_f_z" type="text" class="form-control @error('p_f_z') is-invalid @enderror" name="p_f_z" aria-label="Small" value="{{old('p_f_z')}}" aria-describedby="inputGroup-sizing-sm" placeholder="z">
                                @error('p_f_z')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group has-error">
                                <input id="p_g_x" type="text" class="form-control @error('p_g_x') is-invalid @enderror" name="p_g_x" aria-label="Small" value="{{old('p_g_x')}}" aria-describedby="inputGroup-sizing-sm" placeholder="x">
                                @error('p_g_x')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group has-error">
                                <input id="p_g_y" type="text" class="form-control @error('p_g_y') is-invalid @enderror" name="p_g_y" aria-label="Small" value="{{old('p_g_y')}}" aria-describedby="inputGroup-sizing-sm" placeholder="y">
                                @error('p_g_y')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group has-error">
                                <input id="p_g_z" type="text" class="form-control @error('p_g_z') is-invalid @enderror" name="p_g_z" aria-label="Small" value="{{old('p_g_z')}}" aria-describedby="inputGroup-sizing-sm" placeholder="z">
                                @error('p_g_z')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group has-error">
                                <input id="p_h_x" type="text" class="form-control @error('p_h_x') is-invalid @enderror" name="p_h_x" aria-label="Small" value="{{old('p_h_x')}}" aria-describedby="inputGroup-sizing-sm" placeholder="x">
                                @error('p_h_x')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group has-error">
                                <input id="p_h_y" type="text" class="form-control @error('p_h_y') is-invalid @enderror" name="p_h_y" aria-label="Small" value="{{old('p_h_y')}}" aria-describedby="inputGroup-sizing-sm" placeholder="y">
                                @error('p_h_y')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group has-error">
                                <input id="p_h_z" type="text" class="form-control @error('p_h_z') is-invalid @enderror" name="p_h_z" aria-label="Small" value="{{old('p_h_z')}}" aria-describedby="inputGroup-sizing-sm" placeholder="z">
                                @error('p_h_z')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <label for="name">فالکون قد بلند</label>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group has-error">
                                <input id="p_i_x" type="text" class="form-control @error('p_i_x') is-invalid @enderror" name="p_i_x" aria-label="Small" value="{{old('p_i_x')}}" aria-describedby="inputGroup-sizing-sm" placeholder="x">
                                @error('p_i_x')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group has-error">
                                <input id="p_i_y" type="text" class="form-control @error('p_i_y') is-invalid @enderror" name="p_i_y" aria-label="Small" value="{{old('p_i_y')}}" aria-describedby="inputGroup-sizing-sm" placeholder="y">
                                @error('p_i_y')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group has-error">
                                <input id="p_i_z" type="text" class="form-control @error('p_i_z') is-invalid @enderror" name="p_i_z" aria-label="Small" value="{{old('p_i_z')}}" aria-describedby="inputGroup-sizing-sm" placeholder="z">
                                @error('p_i_z')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group has-error">
                                <input id="p_j_x" type="text" class="form-control @error('p_j_x') is-invalid @enderror" name="p_j_x" aria-label="Small" value="{{old('p_j_x')}}" aria-describedby="inputGroup-sizing-sm" placeholder="x">
                                @error('p_j_x')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group has-error">
                                <input id="p_j_y" type="text" class="form-control @error('p_j_y') is-invalid @enderror" name="p_j_y" aria-label="Small" value="{{old('p_j_y')}}" aria-describedby="inputGroup-sizing-sm" placeholder="y">
                                @error('p_j_y')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group has-error">
                                <input id="p_j_z" type="text" class="form-control @error('p_j_z') is-invalid @enderror" name="p_j_z" aria-label="Small" value="{{old('p_j_z')}}" aria-describedby="inputGroup-sizing-sm" placeholder="z">
                                @error('p_j_z')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group has-error">
                                <input id="p_k_x" type="text" class="form-control @error('p_k_x') is-invalid @enderror" name="p_k_x" aria-label="Small" value="{{old('p_k_x')}}" aria-describedby="inputGroup-sizing-sm" placeholder="x">
                                @error('p_k_x')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group has-error">
                                <input id="p_k_y" type="text" class="form-control @error('p_k_y') is-invalid @enderror" name="p_k_y" aria-label="Small" value="{{old('p_k_y')}}" aria-describedby="inputGroup-sizing-sm" placeholder="y">
                                @error('p_k_y')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group has-error">
                                <input id="point_a_x" type="text" class="form-control @error('p_k_z') is-invalid @enderror" name="p_k_z" aria-label="Small" value="{{old('p_k_z')}}" aria-describedby="inputGroup-sizing-sm" placeholder="z">
                                @error('p_k_z')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group has-error">
                                <input id="p_l_x" type="text" class="form-control @error('p_l_x') is-invalid @enderror" name="p_l_x" aria-label="Small" value="{{old('p_l_x')}}" aria-describedby="inputGroup-sizing-sm" placeholder="x">
                                @error('p_l_x')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group has-error">
                                <input id="p_l_y" type="text" class="form-control @error('p_l_y') is-invalid @enderror" name="p_l_y" aria-label="Small" value="{{old('p_l_y')}}" aria-describedby="inputGroup-sizing-sm" placeholder="y">
                                @error('p_l_y')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group has-error">
                                <input id="p_l_z" type="text" class="form-control @error('p_l_z') is-invalid @enderror" name="p_l_z" aria-label="Small" value="{{old('p_l_z')}}" aria-describedby="inputGroup-sizing-sm" placeholder="z">
                                @error('p_l_z')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group has-error">
                                <input id="p_m_x" type="text" class="form-control @error('p_m_x') is-invalid @enderror" name="p_m_x" aria-label="Small" value="{{old('p_m_x')}}" aria-describedby="inputGroup-sizing-sm" placeholder="x">
                                @error('p_m_x')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group has-error">
                                <input id="p_m_y" type="text" class="form-control @error('p_m_y') is-invalid @enderror" name="p_m_y" aria-label="Small" value="{{old('p_m_y')}}" aria-describedby="inputGroup-sizing-sm" placeholder="y">
                                @error('p_m_y')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group has-error">
                                <input id="p_m_z" type="text" class="form-control @error('p_m_z') is-invalid @enderror" name="p_m_z" aria-label="Small" value="{{old('p_m_z')}}" aria-describedby="inputGroup-sizing-sm" placeholder="z">
                                @error('p_m_z')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                </div>
                <div id="device_position_items">
                    <div id="trash_container" style="display:none">
                        <div class="form-group has-error">
                            <label for="name">offset</label>
                            <input  type="text" class="form-control @error('offset_tip_10') is-invalid @enderror" name="offset_tip_10" aria-label="Small" value="{{old('offset_tip_10')}}" aria-describedby="inputGroup-sizing-sm" placeholder="offset_tip_10">
                            @error('offset_tip_10')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
    
                            <input type="text" class="form-control @error('offset_tip_100') is-invalid @enderror" name="offset_tip_100" aria-label="Small" value="{{old('offset_tip_100')}}" aria-describedby="inputGroup-sizing-sm" placeholder="offset_tip_100">
                            @error('offset_tip_100')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
    
                            <input  type="text" class="form-control @error('offset_tip_1000') is-invalid @enderror" name="offset_tip_1000" aria-label="Small" value="{{old('offset_tip_1000')}}" aria-describedby="inputGroup-sizing-sm" placeholder="offset_tip_1000">
                            @error('offset_tip_1000')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group has-error">
                        <label for="name">point a</label>

                        <input id="point_a_x" type="text" class="form-control @error('point_a_x') is-invalid @enderror" name="point_a_x" aria-label="Small" value="{{old('point_a_x')}}" aria-describedby="inputGroup-sizing-sm" placeholder="x">
                        @error('point_a_x')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror

                        <input id="point_a_y" type="text" class="form-control @error('point_a_y') is-invalid @enderror" name="point_a_y" aria-label="Small" value="{{old('point_a_y')}}" aria-describedby="inputGroup-sizing-sm" placeholder="y">
                        @error('point_a_y')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror

                        <input id="point_a_z" type="text" class="form-control @error('point_a_z') is-invalid @enderror" name="point_a_z" aria-label="Small" value="{{old('point_a_z')}}" aria-describedby="inputGroup-sizing-sm" placeholder="z">
                        @error('point_a_z')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>


                    <div class="form-group has-error">
                        <label for="name">point b</label>

                        <input id="point_b_x" type="text" class="form-control @error('point_b_x') is-invalid @enderror" name="point_b_x" aria-label="Small" value="{{old('point_b_x')}}" aria-describedby="inputGroup-sizing-sm" placeholder="x">
                        @error('point_b_x')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror

                        <input id="point_b_y" type="text" class="form-control @error('point_b_y') is-invalid @enderror" name="point_b_y" aria-label="Small" value="{{old('point_b_y')}}" aria-describedby="inputGroup-sizing-sm" placeholder="y">
                        @error('point_a_y')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror

                        <input id="point_b_z" type="text" class="form-control @error('point_b_z') is-invalid @enderror" name="point_b_z" aria-label="Small" value="{{old('point_b_z')}}" aria-describedby="inputGroup-sizing-sm" placeholder="z">
                        @error('point_b_z')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>


                    <div class="form-group has-error">
                        <label for="name">عرض</label>

                        <input type="text" class="form-control @error('width') is-invalid @enderror" name="width" aria-label="Small" value="{{old('width')}}" aria-describedby="inputGroup-sizing-sm"placeholder="عرض را وارد کنید" >
                        @error('width')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group has-error">
                        <label for="name">طول</label>

                        <input  type="text" class="form-control @error('length') is-invalid @enderror" name="length" aria-label="Small" value="{{old('length')}}" aria-describedby="inputGroup-sizing-sm" placeholder="طول را وارد کنید">
                        @error('length')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-check" id="tips_type" style="display:none">
                    <input class="form-check-input" type="checkbox"  name="standard"  id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
                        جایگاه معیار
                    </label>
                </div>



            </div>
            <!-- /.card-body -->



            <div class="card-footer">
                <button type="submit" class="btn btn-primary">ذخیره</button>
                <a class="btn btn-danger" href="{{route('devicePosition', $id)}}">انصراف</a>
            </div>
        </form>
    </div>
    <!-- /.card -->

@endsection







