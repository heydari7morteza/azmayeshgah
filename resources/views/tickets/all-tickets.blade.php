

@extends('layouts.main')

@section('header')
    @php $urls = array( array('name'=> 'خانه', 'url' => url('/')) , array('name'=> 'تیکت ها' , 'url' => null)) @endphp
    @include('layouts.partials.header',['title' => 'تیکت ها' , 'urls' => $urls ])
@endsection


@section('content')
    @if(session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @endif

    
    <table id="table" class="table table-bordered table-hover mt-4">
        <thead>
        <tr>
          <th>نوع</th>
          <th>وضعیت</th>
          <th>نام</th>
          <th>عنوان</th>
          <th>توضیحات</th>
          <th>عملیات</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($tickets as $ticket)
            <tr>
              <td>{{$ticket->type}}</td>
              <td>
                @foreach ($ticket_option as $key => $value)
                    @if ($key == $ticket->status)
                        {{$value[$key]}}
                    @endif
                @endforeach
              </td>
              <td>{{$ticket->user->name}}</td>
              <td>{{$ticket->name}}</td>
              <td>{{$ticket->description}}</td>
                <td>
                    <a href="{{route('editTicket' , $ticket->id)}}"><button class="btn btn-primary" type="submit">ویرایش</button></a>
                    <a href="{{route('showMessage' , $ticket->id)}}"><button class="btn btn-primary" type="submit">پیام </button></a>
                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmation{{$ticket->id}}">
                        حذف
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="confirmation{{$ticket->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">حذف تیکت </h5>
                                </div>
                                <div class="modal-body">
                                    آیا از حذف تیکت اطمینان دارید؟
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">انصراف</button>
                                    <a  href="{{route('deleteTicket' , $ticket->id)}}"><button class="action-btn btn btn-danger" type="submit">حذف</button></a>
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
            <th>نوع</th>
            <th>وضعیت</th>
            <th>عنوان</th>
            <th>توضیحات</th>
        </tr>
        </tfoot>
    </table>

@endsection
