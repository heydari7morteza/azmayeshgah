




@extends('layouts.main')

@section('header')
    @php $urls = array( array('name'=> 'خانه', 'url' => url('/')) , array('name'=> 'جایگاه' , 'url' => null)) @endphp
    @include('layouts.partials.header',['title' => 'جایگاه ها' , 'urls' => $urls ])
@endsection


@section('content')
    @if(session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @endif
    <a href="{{route('createPosition')}}"><button class="btn btn-primary mb-4" type="submit">ایجاد جایگاه</button></a>
    <table id="table" class="table table-bordered table-hover mt-4">
        <thead>
        <tr>
          <th>نوع دستگاه</th>
          <th>نام</th>
          <th>توضیحات</th>
          <th>نوع</th>
          <th>عملیات</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($positions as $position)
            <tr>
              <td>
                @foreach ($device_option as $key => $value)
                    @if ($key == $position->device_type)
                        {{$value[$key]}}
                    @endif
                @endforeach
              </td>
              <td>{{$position->name}}</td>
              <td>{{$position->description}}</td>
              <td>
                    @foreach ($position_option as $key => $value)
                        @if ($key == $position->type)
                            {{$value[$key]}}
                        @endif
                    @endforeach
              </td>
                <td>
                    <a href="{{route('editPosition' , $position->id)}}"><button class="btn btn-primary" type="submit">ویرایش</button></a>

                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmation{{$position->id}}">
                        حذف
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="confirmation{{$position->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">حذف جایگاه </h5>
                                </div>
                                <div class="modal-body">
                                    آیا از حذف جایگاه اطمینان دارید؟
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">انصراف</button>
                                    <a  href="{{route('deletePosition' , $position->id)}}"><button class="action-btn btn btn-danger" type="submit">حذف</button></a>
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
          <th>نوع دستگاه</th>
          <th>نام</th>
          <th>توضیحات</th>
          <th>نوع</th>
          <th>عملیات</th>
        </tr>
        </tfoot>
    </table>

@endsection
