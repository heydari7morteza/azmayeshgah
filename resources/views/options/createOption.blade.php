




@extends('layouts.main')

@section('header')
    @php $urls = array( array('name'=> 'خانه', 'url' => url('/')) , array('name'=> 'تنظیمات' , 'url' => route('showOptions')), array('name'=> 'ایجاد تنظیمات' , 'url' => null) ) @endphp
    @include('layouts.partials.header',['title' => 'ایجاد تنظیمات' , 'urls' => $urls ])
@endsection


@section('content')
    <!-- general form elements -->
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">ایجاد تنظیمات</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form role="form" action="{{route('checkCreateOption' , $user->id)}}" method="POST" >
            @csrf
            <div class="card-body">
                <div class="form-group has-error">
                    <input id="user_id" type="hidden" class="form-control @error('user_id') is-invalid @enderror" name="user_id" value="{{$user->id}}"aria-label="Small" aria-describedby="inputGroup-sizing-sm">
                    @error('user_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group has-error">
                    <input id="type" type="hidden" class="form-control @error('type') is-invalid @enderror" name="type" value="1" aria-label="Small" aria-describedby="inputGroup-sizing-sm" placeholder="نوع ">
                    @error('type')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group has-error">
                    <label for="name">کلید</label>
                    <input id="key" type="text" class="form-control @error('key') is-invalid @enderror" name="key" aria-label="Small" value="{{old('key')}}" aria-describedby="inputGroup-sizing-sm" placeholder="کلید">
                    @error('key')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group has-error">
                    <label for="name">مقدار</label>
                    <table class="table" id="value_table">
                        <tbody>
                            <tr>
                                <th>
                                    <i class="fa fa-plus-square" style="font-size:25px;" aria-hidden="true" id="add_value"></i>
                                </th>
                            </tr>
                          <tr>
                              <th>
                                <i class="fa fa-minus-square" style="font-size:25px;" aria-hidden="true" id="remove_value"></i>
                              </th>
                            <div id="container_value">
                                <td>
                                    <input name="key_value[]" type="text" class="form-control @error('key_value') is-invalid @enderror" aria-label="Small" value="{{old('key_value')}}" aria-describedby="inputGroup-sizing-sm" placeholder="کلید">
                                </td>
                                <td>
                                    <input  name="value[]" type="text" class="form-control @error('value') is-invalid @enderror" aria-label="Small" value="{{old('value')}}" aria-describedby="inputGroup-sizing-sm" placeholder="مقدار">
                                </td>
                            </div>
                          </tr>
                        </tbody>
                      </table>
                    @error('value')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>


            </div>
            <!-- /.card-body -->

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">ذخیره</button>
                <a class="btn btn-danger" href="{{route('showOptions')}}">انصراف</a>
            </div>
        </form>
    </div>
    <!-- /.card -->

   


@endsection







