



@extends('layouts.main')

@section('header')
    @php $urls = array( array('name'=> 'خانه', 'url' => url('/')) , array('name'=> 'تیکت' , 'url' => route('all_tickets')), array('name'=> 'ویرایش تیکت' , 'url' => null) ) @endphp
    @include('layouts.partials.header',['title' => 'ویرایش تیکت' , 'urls' => $urls ])
@endsection

@section('content')

    <!-- general form elements -->
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">ویرایش تیکت</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form role="form" action="{{route('checkEditTicket' , $ticket->id)}}" method="POST">
            @csrf
            <div class="card-body">
                <div class="form-group has-error">
                    <input id="device_id" type="hidden" class="form-control @error('device_id') is-invalid @enderror" name="device_id" value="{{$ticket->device_id}}" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
                    @error('device_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group has-error">
                    <input id="user_id" type="hidden" class="form-control @error('user_id') is-invalid @enderror" name="user_id" value="{{$ticket->user_id}}" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
                    @error('user_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group has-error">
                    <input id="admin_id" type="hidden" class="form-control @error('admin_id') is-invalid @enderror" name="admin_id" value="{{$ticket->admin_id}}" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
                    @error('admin_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group has-error">
                    <label for="type">نوع</label>
                    <select class="form-control" name="type" aria-label="Default select example">
                        <option  value="{{$ticket->type}}" selected >
                            {{$ticket->type}}
                        </option>
                        <option  value="0">
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
                            <option  value="{{$key}}"@if ($key == $ticket->status) selected @endif>
                                {{$value[$key]}}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group has-error">
                    <label for="name">نام</label>
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{$ticket->name}}" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
                    @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>توضیحات</label>
                    <textarea name="description" class="form-control" rows="3" >{{$ticket->description}}</textarea>
                </div>
                

            </div>
            <!-- /.card-body -->

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">ذخیره</button>
                @if (Auth::user()->type == 0)
                    <a class="btn btn-danger" href="{{route('all_tickets')}}">انصراف</a>
                @else
                    <a class="btn btn-danger" href="{{route('showTicket' , $ticket->user_id)}}">انصراف</a>
                @endif
                
            </div>
        </form>
    </div>
    <!-- /.card -->

@endsection










