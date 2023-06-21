

@extends('layouts.main')

@section('header')
    @php $urls = array( array('name'=> 'خانه', 'url' => url('/')) , array('name'=> 'تنظیمات' , 'url' => null)) @endphp
    @include('layouts.partials.header',['title' => 'تنظیمات' , 'urls' => $urls ])
@endsection


@section('content')
    @if(session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @endif
    <a href="{{route('createOption' ,$user->id)}}"><button class="btn btn-primary mb-4" type="submit">ایجاد تنظیمات</button></a>
    <table id="table" class="table table-bordered table-hover mt-4">
        <thead>
        <tr>
          <th>کلید</th>
          <th>مقدار</th>
          <th>عملیات</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($options as $option)
            <tr>
              <td>{{$option->key}}</td>
              <td>
                @foreach ($option->value as $item)
                    @foreach ($item as $k => $val)
                        {{$k}} : {{$val}} {{","}}
                    @endforeach
                 @endforeach
              </td> 
              
                    <td>
                    <a href="{{route('editOption' ,$option->id)}}"><button class="btn btn-primary" type="submit">ویرایش</button></a>

                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmation{{$option->id}}">
                        حذف
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="confirmation{{$option->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">حذف تنظیمات </h5>
                                </div>
                                <div class="modal-body">
                                    آیا از حذف تنظیمات اطمینان دارید؟
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">انصراف</button>
                                    <a  href="{{route('deleteOption' , $option->id)}}"><button class="action-btn btn btn-danger" type="submit">حذف</button></a>
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
          <th>کلید</th>
          <th>مقدار</th>
          <th>عملیات</th>
        </tr>
        </tfoot>
    </table>

@endsection
