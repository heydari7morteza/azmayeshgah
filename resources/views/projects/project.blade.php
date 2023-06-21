





@extends('layouts.main')

@section('header')
    @php $urls = array( array('name'=> 'خانه', 'url' => url('/')) , array('name'=> 'پروژه' , 'url' => null)) @endphp
    @include('layouts.partials.header',['title' => 'پروژه ها' , 'urls' => $urls ])
@endsection


@section('content')
    @if(session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @endif
    <a href="{{route('createProject' , $device->id)}}"><button class="btn btn-primary mb-4" type="submit">ایجاد پروژه</button></a>
    <table id="table" class="table table-bordered table-hover mt-4">
        <thead>
        <tr>
          <th>نام</th>
          <th>تخمین تیپ</th>
          <th>تخمین زمان</th>
          <th>سرعت(x)</th>
          <th>سرعت(y)</th>
          <th>سرعت(z)</th>
          <th>ارتفاع ایمن</th>
          <th>عملیات</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($projects as $project)
            <tr>
              <td>{{$project->name}}</td>
              <td>{{$project->estimate_tips}}</td>
              <td>{{$project->estimate_time}}</td>
              <td>{{$project->getMeta('speed_x')}}</td>
              <td>{{$project->getMeta('speed_y')}}</td>
              <td>{{$project->getMeta('speed_z')}}</td>
              <td>{{$project->getMeta('safe_zone')}}</td>
                <td>
                    <a href="{{route('editProject' ,$project->id)}}"><button class="btn btn-primary" type="submit">ویرایش</button></a>
                    <a href="{{route('showMap' ,$project->id)}}"><button class="btn btn-primary" type="submit">نقشه پروژه</button></a>

                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmation{{$project->id}}">
                        حذف
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="confirmation{{$project->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">حذف پروژه </h5>
                                </div>
                                <div class="modal-body">
                                    آیا از حذف پروژه اطمینان دارید؟
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">انصراف</button>
                                    <a  href="{{route('deleteProject', $project->id)}}"><button class="action-btn btn btn-danger" type="submit">حذف</button></a>
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
          <th>تخمین تیپ</th>
          <th>تخمین زمان</th>
          <th>سرعت(x)</th>
          <th>سرعت(y)</th>
          <th>سرعت(z)</th>
          <th>ارتفاع ایمن</th>
          <th>عملیات</th>
        </tr>
        </tfoot>
    </table>

@endsection
