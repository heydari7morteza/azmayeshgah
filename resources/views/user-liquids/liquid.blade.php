




@extends('layouts.main')

@section('header')
    @php $urls = array( array('name'=> 'خانه', 'url' => url('/')) , array('name'=> 'مایع' , 'url' => null)) @endphp
    @include('layouts.partials.header',['title' => 'مایعات' , 'urls' => $urls ])
@endsection


@section('content')
    @if(session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @endif
    <a href="{{route('userCreateLiquid' , $id)}}"><button class="btn btn-primary mb-4" type="submit">ایجاد مایع</button></a>
    <table id="table" class="table table-bordered table-hover mt-4">
        <thead>
        <tr>
            <th>نام</th>
            <th>توضیحات</th>
            <th>مقدار</th>
            <th>چگالی</th>
            <th>عملیات</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($liquids as $liquid)
            <tr>
              <td>{{$liquid->name;}}</td>
              <td>{{$liquid->description;}}</td>
              <td>{{$liquid->volume;}}</td>
              <td>{{$liquid->density;}}</td>
                <td>
                    <a href="{{route('userEditLiquid' , $liquid->id)}}"><button class="btn btn-primary" type="submit">ویرایش</button></a>

                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmation{{$liquid->id}}">
                        حذف
                    </button>
                    <!-- Modal -->
                    <div class="modal fade" id="confirmation{{$liquid->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">حذف مایع </h5>
                                </div>
                                <div class="modal-body">
                                    آیا از حذف مایع اطمینان دارید؟
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">انصراف</button>
                                    <a  href="{{route('userDeleteLiquid' , $liquid->id)}}"><button class="action-btn btn btn-danger" type="submit">حذف</button></a>
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
            <th>توضیحات</th>
            <th>مقدار</th>
            <th>چگالی</th>
            <th>عملیات</th>
        </tr>
        </tfoot>
    </table>

@endsection
