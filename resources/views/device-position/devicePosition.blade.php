





@extends('layouts.main')

@section('header')
    @php $urls = array( array('name'=> 'خانه', 'url' => url('/')) , array('name'=> 'جایگاه دستگاه' , 'url' => null)) @endphp
    @include('layouts.partials.header',['title' => ' جایگاه دستگاه ها' , 'urls' => $urls ])
@endsection


@section('content')
    @if(session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @endif
    <a href="{{route('createDevicePosition' , $device->id)}}"><button class="btn btn-primary mb-4" type="submit">ایجاد جایگاه دستگاه</button></a>
    <table id="table" class="table table-bordered table-hover mt-4">
        <thead>
        <tr>
          <th>نوع دستگاه</th>
          <th>نام جایگاه</th>
          <th>point a</th>
          <th>point b</th>
          <th>طول</th>
          <th>عرض</th>
          <th>عملیات</th>
        </tr>
        </thead>
        <tbody>

            @foreach ($devicePositions as $devicePosition)
            <tr>
              <td>
                @foreach ($device_option as $key => $value)
                    @if ($key == $device->type)
                    {{$value[$key]}}
                    @endif
                @endforeach
              </td>
              <td>{{$devicePosition->position->name}}</td>
              @if ($devicePosition->position->type == 7 || $devicePosition->position->type == 8)
                <td>x:{{$devicePosition->point_a['x']}} , y:{{$devicePosition->point_a['y']}} , z:{{$devicePosition->point_a['z']}}</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
              @else
                <td>x:{{$devicePosition->point_a['x']}} , y:{{$devicePosition->point_a['y']}} , z:{{$devicePosition->point_a['z']}}</td>
                <td>x:{{$devicePosition->point_b['x']}} , y:{{$devicePosition->point_b['y']}} , z:{{$devicePosition->point_b['z']}}</td>
                <td>{{$devicePosition->length}}</td>
                <td>{{$devicePosition->width}}</td>
              @endif

                <td>
                    <a href="{{route('editDevicePosition' , $devicePosition->id)}}"><button class="btn btn-primary" type="submit">ویرایش</button></a>

                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmation{{$devicePosition->id}}">
                        حذف
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="confirmation{{$devicePosition->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">حذف جایگاه دستگاه</h5>
                                </div>
                                <div class="modal-body">
                                    آیا از حذف جایگاه دستگاه اطمینان دارید؟
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">انصراف</button>
                                    <a  href="{{route('deleteDevicePosition' , $devicePosition->id)}}"><button class="action-btn btn btn-danger" type="submit">حذف</button></a>
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
          <th>نام جایگاه</th>
          <th>point a</th>
          <th>point b</th>
          <th>طول</th>
          <th>عرض</th>
          <th>عملیات</th>
        </tr>
        </tfoot>
    </table>

@endsection
