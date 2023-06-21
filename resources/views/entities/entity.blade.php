







@extends('layouts.main')

@section('header')
    @php $urls = array( array('name'=> 'خانه', 'url' => url('/')) , array('name'=> 'موجودیت' , 'url' => null)) @endphp
    @include('layouts.partials.header',['title' => 'موجودیت ها' , 'urls' => $urls ])
@endsection


@section('content')
    @if(session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @endif
    <a href="{{route('addEntityForm')}}"><button class="btn btn-primary mb-4" type="submit">ایجاد موجودیت</button></a>
    <table id="table" class="table table-bordered table-hover mt-4">
        <thead>
        <tr>
          <th>نام</th>
          <th>نوع</th>
          <th>نوع جایگاه</th>
          <th>توضیحات</th>
          <th>عملیات</th>
        </tr>
        </thead>
        <tbody>
            

          @foreach ($entities as $entity)
         
          <tr>
            <td>{{$entity->name}}</td>
            <td>
                @foreach ($entity_option as $key => $value)
                    @if ($key == $entity->type)
                        {{$value[$key]}}
                    @endif
                @endforeach
            </td>
            <td>
                @if ($entity->type == 4)
                    @foreach ($module_option as $key => $value)
                        @if ($key == $entity->position_type_match)
                            {{$value[$key]}}
                        @endif
                    @endforeach
                @else
                    @foreach ($position_option as $key => $value)
                        @if ($key == $entity->position_type_match)
                            {{$value[$key]}}
                        @endif
                    @endforeach
                @endif
                
            </td>
            <td>{{$entity->description}}</td>
                <td>
                    <a href="{{route('editEntity' , $entity->id)}}"><button class="btn btn-primary" type="submit">ویرایش</button></a>

                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmation{{$entity->id}}">
                        حذف
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="confirmation{{$entity->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                    <a  href="{{route('deleteEntity' , $entity->id)}}"><button class="action-btn btn btn-danger" type="submit">حذف</button></a>
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
            <th>نام</th>
            <th>نوع</th>
            <th>نوع جایگاه</th>
            <th>توضیحات</th>
            <th>عملیات</th>
        </tr>
        </tfoot>
    </table>

@endsection