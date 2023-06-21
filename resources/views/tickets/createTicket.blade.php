



@extends('layouts.main')

@section('header')
    @php $urls = array( array('name'=> 'خانه', 'url' => url('/')) , array('name'=> 'تیکت' , 'url' => route('all_tickets')), array('name'=> 'ایجاد تیکت' , 'url' => null) ) @endphp
    @include('layouts.partials.header',['title' => 'ایجاد تیکت' , 'urls' => $urls ])
@endsection

@section('content')

    <!-- general form elements -->
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">ایجاد تیکت</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form role="form" action="{{route('checkCreateTicket' , $user->id)}}" method="POST" >
            @csrf
            <div class="card-body">

                <div class="form-group has-error">
                    <label for="type">آیدی دستگاه</label>
                    <select class="form-control" name="device_id" aria-label="Default select example">
                        @if ($device->isNotEmpty())
                            @foreach ($device as $item)
                                <option  value="{{$item->id}}" selected>
                                    {{$item->id}}
                                </option>
                            @endforeach
                        @else
                        <option   value= "0" selected>
                            دستگاهی وجود ندارد - لطفا دستگاه را ایجاد کنید
                        </option>
                        @endif

                    </select>
                </div>

               

                <div class="form-group has-error">
                    <input id="user_id" type="hidden" class="form-control @error('user_id') is-invalid @enderror" name="user_id" value="{{$user->id}}" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
                    @error('user_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                

                @if ($isAdmin == 0)
                    <div class="form-group has-error">
                        <input id="admin_id" type="hidden" class="form-control @error('admin_id') is-invalid @enderror"  name="admin_id" value="{{$user->id}}"  aria-label="Small" aria-describedby="inputGroup-sizing-sm">
                        @error('admin_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                @else 

                    <div class="form-group has-error">
                        <input id="admin_id" type="hidden" class="form-control @error('admin_id') is-invalid @enderror" name="admin_id"  aria-label="Small" aria-describedby="inputGroup-sizing-sm">
                        @error('admin_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                @endif
                

                <div class="form-group has-error">
                    <label for="type">نوع</label>
                    <select class="form-control" name="type" aria-label="Default select example">
                        <option   value="0" selected>
                            0
                        </option>
                        <option  value="1">
                            1
                        </option>
                    </select>
                </div>

                <div class="form-group has-error">
                    <label for="type">وضعیت</label>
                    <select class="form-control" name="status" aria-label="Default select example">
                        @foreach ($ticket_option as $key => $value)
                            <option  value="{{$key}}">
                                {{$value[$key]}}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group has-error">
                    <label for="name">نام</label>
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{old('name')}}" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
                    @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>توضیحات</label>
                    <textarea name="description" class="form-control" rows="3" >{{old('description')}}</textarea>
                </div>
                

            </div>
            <!-- /.card-body -->

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">ذخیره</button>
                @if (Auth::user()->type == 1)
                    <a class="btn btn-danger" href="{{route('showTicket' ,$user->id)}}">انصراف</a>
                @else
                    <a class="btn btn-danger" href="{{route('all_tickets')}}">انصراف</a>
                @endif
                
            </div>
        </form>
    </div>
    <!-- /.card -->

@endsection










