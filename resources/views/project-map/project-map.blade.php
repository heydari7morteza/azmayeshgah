<!DOCTYPE html>

<html lang="fa">
<head>
    @include('layouts.partials.head')
</head>
<body class="hold-transition sidebar-mini control-sidebar-slide-open">
<main class="wrapper">
    <nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{url('/')}}" class="nav-link">نقشه</a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{route('liquidProject' , $id)}}" class="nav-link">مایعات</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#">پروسه ها</a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{url('/project/gcode/decoder/'.$id)}}" id="gcode-decoder" project-id="{{$id}}" class="nav-link">تولید جی کد</a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{url('/project/gcode/download/'.$id)}}" id="gcode-download" project-id="{{$id}}" class="nav-link">دانلود جی کد</a>
            </li>
        </ul>
        <ul class="estimate">

        </ul>
    </nav>



    @include('layouts.partials.sidebar')


    <div class="content-wrapper" >
        <div  role="alert" id="gcode_error" >
            <div class="alert alert-danger" role="alert" style="display: none">

            </div>
            <div class="alert alert-success" role="alert" style="display: none">

            </div>
        </div>
        @php $urls = array( array('name'=> 'خانه', 'url' => url('/')) , array('name'=> 'پروژه' , 'url' => route('showProject', $id)), array('name'=> 'نقشه پروژه' , 'url' => null) ) @endphp
        @include('layouts.partials.header',['title' => 'نقشه پروژه' , 'urls' => $urls ])

        <div class="content">
            <div class="container-fluid project-map">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            @foreach($devicePositions as $devicePosition)
                        @if($devicePosition->position->type == 6)
                                    @php
                                        $project_map = \App\Models\ProjectMap::where('device_position_id','=',$devicePosition->id)->where('project_id','=',$id)->first();
                                    @endphp
                                    <div class="col-4 mt-3">
                                        <div class="col-wrapper @if($project_map) two-col @endif d-flex align-items-center justify-content-center" id="wrapper{{$devicePosition->id}}">
                                            <p class="entity">{{$devicePosition->position->name}}</p>
                                            <div class="add-liquids">
                                                @if($project_map)
                                                    <p>{{$project_map->entity->name}} </p>
                                                    <div class="btn-wrapper">
                                                        <a  href="#" class="btn btn-danger btn-sm delete-project-map" project-map-id="{{$project_map->id}}" > حذف </a>
                                                        <a  href="#" class="btn btn-success btn-sm calibrate-project-map" data-toggle="modal" data-target="#calibrate{{$project_map->id}}" project-map-id="{{$project_map->id}}"  > کالیبراسیون </a>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="overlay align-items-center justify-content-center" @if($project_map) style="display: none !important;" @endif>
                                                <a class="entity" data-toggle="modal" data-target="#entity{{$devicePosition->id}}" href="#"  device-position-id="{{$devicePosition->id}}" project-id="{{$id}}"><i class="fa fa-plus-square-o"></i></a>
                                            </div>
                                            <!-- Modal -->
                                            <div class="modal fade" id="entity{{$devicePosition->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content" style="direction:rtl">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">انتخاب ظرف برای {{$devicePosition->position->name}}</h5>
                                                        </div>
                                                        <div class="modal-body">
                                                            @php
                                                                $entities = \App\Models\Entity::where('type','=',7)->where('position_type_match','=',$devicePosition->position->type)->get();
                                                            @endphp
                                                            <form class="" >
                                                                <div class="form-group">
                                                                    <label>انتخاب ظرف</label>
                                                                    <select class="form-control select2 entity-select" >
                                                                        @foreach($entities as $key => $entity)
                                                                            <option value="{{$entity->id}}">{{$entity->name}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-success add-entity"  device-position-id="{{$devicePosition->id}}" project-id="{{$id}}" >ثبت</button>
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">انصراف</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal fade calibrate" @if($project_map) id="calibrate{{$project_map->id}}" @endif tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content" style="direction:rtl">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">کالیبراسیون {{$devicePosition->position->name}}</h5>
                                                        </div>
                                                        <div class="modal-body">
                                                            @if($project_map)
                                                                <form class="" >
                                                                    <div class="form-group">
                                                                        <label>جا به جایی در راستای محور x</label>
                                                                        <input class="form-control" type="number" name="x" placeholder="مقادیر مثبت و منفی قابل قبول است." />
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>جا به جایی در راستای محور y</label>
                                                                        <input class="form-control" type="number" name="y" placeholder="مقادیر مثبت و منفی قابل قبول است." />
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>جا به جایی در راستای محور z</label>
                                                                        <input class="form-control" type="number" name="z" placeholder="مقادیر مثبت و منفی قابل قبول است." />
                                                                    </div>
                                                                </form>
                                                            @endif
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-success add-calibrate"  @if($project_map) project-map-id="{{$project_map->id}}" @endif >ثبت</button>
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">انصراف</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            @endif
                            @endforeach
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-8" style="direction:ltr">
                            <div class="row">
                                @foreach($devicePositions as $devicePosition)
                                    @if($devicePosition->position->type == 0)
                                        @php
                                            $project_map = \App\Models\ProjectMap::where('device_position_id','=',$devicePosition->id)->first();
                                        @endphp
                                        <div class="col-3 mt-3">
                                            <div class="col-wrapper @if($project_map) two-col @endif d-flex align-items-center justify-content-center" id="wrapper{{$devicePosition->id}}">
                                                <p class="entity">{{$devicePosition->position->name}}</p>
                                                <div class="add-liquids">
                                                    @if($project_map)
                                                        <p>{{$project_map->entity->name}} </p>
                                                        <div class="btn-wrapper">
                                                            <a  href="#" class="btn btn-danger btn-sm delete-project-map" project-map-id="{{$project_map->id}}" > حذف </a>
                                                            <a  href="#" class="btn btn-success btn-sm calibrate-project-map" data-toggle="modal" data-target="#calibrate{{$project_map->id}}" project-map-id="{{$project_map->id}}"  > کالیبراسیون </a>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="overlay align-items-center justify-content-center" @if($project_map) style="display: none !important;" @endif>
                                                    <a class="entity" data-toggle="modal" data-target="#entity{{$devicePosition->id}}" href="#"  device-position-id="{{$devicePosition->id}}" project-id="{{$id}}"><i class="fa fa-plus-square-o"></i></a>
                                                </div>
                                                <!-- Modal -->
                                                <div class="modal fade" id="entity{{$devicePosition->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content" style="direction:rtl">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">انتخاب ظرف برای {{$devicePosition->position->name}}</h5>
                                                            </div>
                                                            <div class="modal-body">
                                                                @php
                                                                    $entities = \App\Models\Entity::where('type','=',6)->where('position_type_match','=',$devicePosition->position->type)->get();

                                                                @endphp
                                                                <form class="" >
                                                                    <div class="form-group">
                                                                        <label>انتخاب ظرف</label>
                                                                        <select class="form-control select2 entity-select" >
                                                                            @foreach($entities as $key => $entity)
                                                                                <option value="{{$entity->id}}">{{$entity->name}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-success add-entity"  device-position-id="{{$devicePosition->id}}" project-id="{{$id}}" >ثبت</button>
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">انصراف</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                    <div class="modal fade calibrate" @if($project_map) id="calibrate{{$project_map->id}}" @endif tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content" style="direction:rtl">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">کالیبراسیون {{$devicePosition->position->name}}</h5>
                                                                </div>
                                                                <div class="modal-body">
                                                                    @if($project_map)
                                                                    <form class="" >
                                                                        <div class="form-group">
                                                                            <label>جا به جایی در راستای محور x</label>
                                                                            <input class="form-control" type="number" name="x" placeholder="مقادیر مثبت و منفی قابل قبول است." />
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>جا به جایی در راستای محور y</label>
                                                                            <input class="form-control" type="number" name="y" placeholder="مقادیر مثبت و منفی قابل قبول است." />
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>جا به جایی در راستای محور z</label>
                                                                            <input class="form-control" type="number" name="z" placeholder="مقادیر مثبت و منفی قابل قبول است." />
                                                                        </div>
                                                                    </form>
                                                                    @endif
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-success add-calibrate"  @if($project_map) project-map-id="{{$project_map->id}}" @endif >ثبت</button>
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">انصراف</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                            </div>
                                        </div>
                                    {{-- @elseif(($devicePosition->position->type != 7) && ($devicePosition->position->type != 6)  && ($devicePosition->position->type != 0)  && ($devicePosition->position->type != 8)) --}} {{--old code--}}
                                    @elseif(($devicePosition->position->type == 1) ||($devicePosition->position->type == 3))  <!--this is my code-->
                                        @php
                                            $project_map = \App\Models\ProjectMap::where('device_position_id','=',$devicePosition->id)->first();
                                            $liquids = \App\Models\Liquid::where('project_id' , null)->orWhere('project_id' , '=', $id)->get();
                                        @endphp
                                        <div class="col-6 mt-3">
                                            <div class="col-wrapper @if($project_map) two-col @endif d-flex align-items-center justify-content-center" id="wrapper{{$devicePosition->id}}">
                                                <p class="entity">{{$devicePosition->position->name}}</p>
                                                <div class="add-liquids">
                                                    @if($project_map)
                                                        <p>{{$project_map->entity->name}}</p>
                                                        <div class="btn-wrapper">
                                                            <a  href="#" class="btn btn-danger btn-sm delete-project-map" project-map-id="{{$project_map->id}}" > حذف </a>
                                                            <a  href="#" class="btn btn-success btn-sm calibrate-project-map" data-toggle="modal" data-target="#calibrate{{$project_map->id}}" project-map-id="{{$project_map->id}}"  > کالیبراسیون </a>
                                                        </div><div class="btn-wrapper">
                                                            <a class="add_liquids btn btn-primary btn-sm" data-toggle="modal" data-target="#add_liquids{{$project_map->id}}" href="#" >اضافه کردن نمونه ها</a>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="overlay align-items-center justify-content-center" @if($project_map) style="display: none !important;" @endif>
                                                    <a class="entity" data-toggle="modal" data-target="#entity{{$devicePosition->id}}" href="#"  device-position-id="{{$devicePosition->id}}" project-id="{{$id}}"><i class="fa fa-plus-square-o"></i></a>
                                                </div>
                                                <!-- Modal -->
                                                <div class="modal fade" id="entity{{$devicePosition->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content" style="direction:rtl">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">انتخاب ظرف برای {{$devicePosition->position->name}}</h5>
                                                            </div>
                                                            <div class="modal-body">
                                                                @php
                                                                if($devicePosition->position->type == 5 || $devicePosition->position->type == 3){
                                                                    $entities = \App\Models\Entity::where('position_type_match','=',$devicePosition->position->type)->where(function ($query) {$query->where('type','=',0)->orWhere('type','=',2);})->get();
                                                                }else{
                                                                    $entities = \App\Models\Entity::where('type','=',0)->where('position_type_match','=',$devicePosition->position->type)->get();
                                                                }
                                                                @endphp
                                                                <form class="" >
                                                                    <div class="form-group">
                                                                        <label>انتخاب ظرف</label>
                                                                        <select class="form-control select2 entity-select" >
                                                                            @foreach($entities as $key => $entity)
                                                                            <option value="{{$entity->id}}">{{$entity->name}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-success add-entity"  device-position-id="{{$devicePosition->id}}" project-id="{{$id}}" >ثبت</button>
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">انصراف</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal fade calibrate" @if($project_map) id="calibrate{{$project_map->id}}" @endif tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content" style="direction:rtl">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">کالیبراسیون {{$devicePosition->position->name}}</h5>
                                                            </div>
                                                            <div class="modal-body">
                                                                @if($project_map)
                                                                    <form class="" >
                                                                        <div class="form-group">
                                                                            <label>جا به جایی در راستای محور x</label>
                                                                            <input class="form-control" type="number" name="x" placeholder="مقادیر مثبت و منفی قابل قبول است." />
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>جا به جایی در راستای محور y</label>
                                                                            <input class="form-control" type="number" name="y" placeholder="مقادیر مثبت و منفی قابل قبول است." />
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>جا به جایی در راستای محور z</label>
                                                                            <input class="form-control" type="number" name="z" placeholder="مقادیر مثبت و منفی قابل قبول است." />
                                                                        </div>
                                                                    </form>
                                                                @endif
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-success add-calibrate"  @if($project_map) project-map-id="{{$project_map->id}}" @endif >ثبت</button>
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">انصراف</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal fade add-liquid-modal" @if($project_map) id="add_liquids{{$project_map->id}}" @endif tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content" style="direction:rtl">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">انتخاب نمونه برای {{$devicePosition->position->name}}</h5>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="liquids-wrapper" >
                                                                    <form >
                                                                        <div class="alert alert-success mt-3 d-none">نمونه با موفقیت ثبت شد.</div>
                                                                        <div class="form-group has-error">
                                                                            <label for="liquids">مایع</label>
                                                                            <select class="form-control" name="liquid">
                                                                                @foreach($liquids as $key => $liquid)
                                                                                    <option value="{{$liquid->id}}">{{$liquid->name}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="form-group has-error">
                                                                            <label for="volume">حجم (میکرولیتر)</label>
                                                                            <input id="volume" type="number" class="form-control" name="volume" min="0" aria-label="Small" aria-describedby="inputGroup-sizing-sm" placeholder="حجم" />
                                                                            <span class="invalid-feedback d-none" role="alert">
                                                                                <strong>حجم درخواستی بیشتر از حجم موجود مایع می باشد.</strong>
                                                                            </span>
                                                                        </div>
                                                                        <input type="hidden" name="project-map" value="@if($project_map){{$project_map->id}}@endif" />
{{--                                                                        <input type="hidden" name="row" value="" />--}}
{{--                                                                        <input type="hidden" name="col" value="" />--}}
                                                                        <input type="hidden" name="selected" value="" />
                                                                        <button type="button" class="btn btn-success add-liquid">ثبت</button>
                                                                        <button type="button" class="btn btn-success edit-liquid d-none">تغییر</button>
                                                                        <button type="button" class="btn btn-danger remove-liquid d-none">حذف</button>
                                                                        <button type="button" class="btn btn-secondary cancel-liquid" >انصراف</button>
                                                                    </form>
                                                                </div>
                                                                @if($project_map)
                                                                @php
                                                                if($project_map->entity->col){
                                                                    $k = 12/($project_map->entity->col);
                                                                    $k = floor($k);
                                                                    $rows = array('A','B','C','D','E','F','G','H','I','J','K','L','M','O');
                                                                }
                                                                @endphp
                                                                <form class="form-wrapper" >
                                                                    <div class="form-group">
                                                                        {{--                                                                    <label>انتخاب نمونه</label>--}}
                                                                        {{--                                                                    <select class="form-control select2 entity-select" >--}}
                                                                        {{--                                                                        @foreach($liquids as $key => $liquid)--}}
                                                                        {{--                                                                            <option value="{{$liquid->id}}">{{$liquid    ->name}}</option>--}}
                                                                        {{--                                                                        @endforeach--}}
                                                                        {{--                                                                    </select>--}}
                                                                        <div class="row">
                                                                            <div class="col-11">
                                                                                <div class="row">
                                                                                    @for($n=0;$n < $project_map->entity->col;$n++)
                                                                                        <div class="col-{{$k}} mt-2 d-flex align-items-center justify-content-center">
                                                                                            <p>{{$project_map->entity->col - $n}}</p>
                                                                                        </div>
                                                                                    @endfor
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        @for($i=0;$i < $project_map->entity->row;$i++)
                                                                            <div class="row">
                                                                                <div class="col-11">
                                                                                    <div class="row">
                                                                                    @for($j=0;$j < $project_map->entity->col;$j++)
                                                                                        @php
                                                                                            $flag = 0;
                                                                                            $liq_id = 0;
                                                                                            $volume  = 0;
                                                                                            $map_liquids = json_decode($project_map->liquids);
                                                                                            if($map_liquids){
                                                                                                foreach ($map_liquids as $map_liquid){
                                                                                                    if(($map_liquid->row == ($i+1)) && ($map_liquid->col == ($project_map->entity->col - $j))){
                                                                                                        $flag = 1;
                                                                                                        $liq_id = $map_liquid->id;
                                                                                                        $volume = $map_liquid->volume;
                                                                                                        break;
                                                                                                    }
                                                                                                }
                                                                                            }
                                                                                        @endphp
                                                                                        <div class="col-{{$k}} mt-2">
                                                                                            <div class="circle @if($flag) selected @endif" @if($flag) liquid-id="{{$liq_id}}"  liquid-vol="{{$volume}}" @endif row="{{$i+1}}" col="{{$project_map->entity->col - $j}}">
                                                                                            </div>
                                                                                        </div>
                                                                                    @endfor
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-1 d-flex align-items-center justify-content-center">
                                                                                    <div class="row mt-2">
                                                                                        <p>{{$rows[$i]}}</p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endfor

                                                                    </div>
                                                                </form>
                                                                @endif
                                                            </div>

                                                            <div class="modal-footer">
                                                                <p class="invalid-feedback"><strong>به طور همزمان نمیتوانید مکان های دارای مقدار و بدون مقدار را انتخاب کنید.</strong></p>
                                                                <button type="button" class="btn btn-success add-selecting-liquid " >ثبت</button>
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">انصراف</button>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        @endforeach


                                        @foreach ($devicePositions as $devicePosition)
                                        @if(($devicePosition->position->type == 2) ||($devicePosition->position->type == 4) ||($devicePosition->position->type == 5))
                                        @php
                                            $project_map = \App\Models\ProjectMap::where('device_position_id','=',$devicePosition->id)->first();
                                            // $liquids = \App\Models\Liquid::where('project_id','=',$id)->get();
                                            $liquids = \App\Models\Liquid::where('project_id' , null)->orWhere('project_id' , '=', $id)->get();
                                        @endphp
                                            @if($devicePosition->position->type == 2)
                                            <div class="col-6 mt-3">
                                                <div class="col-wrapper @if($project_map) two-col @endif d-flex align-items-center justify-content-center" id="wrapper{{$devicePosition->id}}">
                                                    <p class="entity">{{$devicePosition->position->name}}</p>
                                                    <div class="add-liquids">
                                                        @if($project_map)
                                                            <p>{{$project_map->entity->name}} </p>
                                                            <div class="btn-wrapper">
                                                                <a  href="#" class="btn btn-danger btn-sm delete-project-map" project-map-id="{{$project_map->id}}" > حذف </a>
                                                                <a  href="#" class="btn btn-success btn-sm calibrate-project-map" data-toggle="modal" data-target="#calibrate{{$project_map->id}}" project-map-id="{{$project_map->id}}"  > کالیبراسیون </a>
                                                            </div><div class="btn-wrapper">
                                                                <a class="add_liquids btn btn-primary btn-sm" data-toggle="modal" data-target="#add_liquids{{$project_map->id}}" href="#" >اضافه کردن نمونه ها</a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="overlay align-items-center justify-content-center" @if($project_map) style="display: none !important;" @endif>
                                                        <a class="entity" data-toggle="modal" data-target="#entity{{$devicePosition->id}}" href="#"  device-position-id="{{$devicePosition->id}}" project-id="{{$id}}"><i class="fa fa-plus-square-o"></i></a>
                                                    </div>
                                                    <!-- Modal -->
                                                    <div class="modal fade" id="entity{{$devicePosition->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content" style="direction:rtl">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">انتخاب ظرف برای {{$devicePosition->position->name}}</h5>
                                                                </div>
                                                                <div class="modal-body">
                                                                    @php
                                                                    if($devicePosition->position->type == 5 || $devicePosition->position->type == 3){
                                                                        $entities = \App\Models\Entity::where('position_type_match','=',$devicePosition->position->type)->where(function ($query) {$query->where('type','=',0)->orWhere('type','=',2);})->get();
                                                                    }else{
                                                                        $entities = \App\Models\Entity::where('type','=',0)->where('position_type_match','=',$devicePosition->position->type)->get();
                                                                    }
                                                                    @endphp
                                                                    <form class="" >
                                                                        <div class="form-group">
                                                                            <label>انتخاب ظرف</label>
                                                                            <select class="form-control select2 entity-select" >
                                                                                @foreach($entities as $key => $entity)
                                                                                <option value="{{$entity->id}}">{{$entity->name}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-success add-entity"  device-position-id="{{$devicePosition->id}}" project-id="{{$id}}" >ثبت</button>
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">انصراف</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal fade calibrate" @if($project_map) id="calibrate{{$project_map->id}}" @endif tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content" style="direction:rtl">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">کالیبراسیون {{$devicePosition->position->name}}</h5>
                                                                </div>
                                                                <div class="modal-body">
                                                                    @if($project_map)
                                                                        <form class="" >
                                                                            <div class="form-group">
                                                                                <label>جا به جایی در راستای محور x</label>
                                                                                <input class="form-control" type="number" name="x" placeholder="مقادیر مثبت و منفی قابل قبول است." />
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>جا به جایی در راستای محور y</label>
                                                                                <input class="form-control" type="number" name="y" placeholder="مقادیر مثبت و منفی قابل قبول است." />
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>جا به جایی در راستای محور z</label>
                                                                                <input class="form-control" type="number" name="z" placeholder="مقادیر مثبت و منفی قابل قبول است." />
                                                                            </div>
                                                                        </form>
                                                                    @endif
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-success add-calibrate"  @if($project_map) project-map-id="{{$project_map->id}}" @endif >ثبت</button>
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">انصراف</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal fade add-liquid-modal" @if($project_map) id="add_liquids{{$project_map->id}}" @endif tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content" style="direction:rtl">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">انتخاب نمونه برای {{$devicePosition->position->name}}</h5>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="liquids-wrapper" >
                                                                        <form >
                                                                            <div class="alert alert-success mt-3 d-none">نمونه با موفقیت ثبت شد.</div>
                                                                            <div class="form-group has-error">
                                                                                <label for="liquids">مایع</label>
                                                                                <select class="form-control" name="liquid">
                                                                                    @foreach($liquids as $key => $liquid)
                                                                                        <option value="{{$liquid->id}}">{{$liquid->name}}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                            <div class="form-group has-error">
                                                                                <label for="volume">حجم (میکرولیتر)</label>
                                                                                <input id="volume" type="number" class="form-control" name="volume" min="0" aria-label="Small" aria-describedby="inputGroup-sizing-sm" placeholder="حجم" />
                                                                                <span class="invalid-feedback d-none" role="alert">
                                                                                    <strong>حجم درخواستی بیشتر از حجم موجود مایع می باشد.</strong>
                                                                                </span>
                                                                            </div>
                                                                            <input type="hidden" name="project-map" value="@if($project_map){{$project_map->id}}@endif" />
                                                                            {{--                                                                        <input type="hidden" name="row" value="" />--}}
                                                                            {{--                                                                        <input type="hidden" name="col" value="" />--}}
                                                                            <input type="hidden" name="selected" value="" />
                                                                            <button type="button" class="btn btn-success add-liquid">ثبت</button>
                                                                            <button type="button" class="btn btn-success edit-liquid d-none">تغییر</button>
                                                                            <button type="button" class="btn btn-danger remove-liquid d-none">حذف</button>
                                                                            <button type="button" class="btn btn-secondary cancel-liquid" >انصراف</button>
                                                                        </form>
                                                                    </div>
                                                                    @if($project_map)
                                                                    @php
                                                                    if($project_map->entity->col){
                                                                        $k = 12/($project_map->entity->col);
                                                                        $k = floor($k);
                                                                        $rows = array('A','B','C','D','E','F','G','H','I','J','K','L','M','O');
                                                                    }
                                                                    @endphp
                                                                    <form class="form-wrapper" >
                                                                        <div class="form-group">
                                                                            {{--                                                                    <label>انتخاب نمونه</label>--}}
                                                                            {{--                                                                    <select class="form-control select2 entity-select" >--}}
                                                                            {{--                                                                        @foreach($liquids as $key => $liquid)--}}
                                                                            {{--                                                                            <option value="{{$liquid->id}}">{{$liquid    ->name}}</option>--}}
                                                                            {{--                                                                        @endforeach--}}
                                                                            {{--                                                                    </select>--}}
                                                                            <div class="row">
                                                                                <div class="col-11">
                                                                                    <div class="row">
                                                                                        @for($n=0;$n < $project_map->entity->col;$n++)
                                                                                            <div class="col-{{$k}} mt-2 d-flex align-items-center justify-content-center">
                                                                                                <p>{{$project_map->entity->col - $n}}</p>
                                                                                            </div>
                                                                                        @endfor
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            @for($i=0;$i < $project_map->entity->row;$i++)
                                                                                <div class="row">
                                                                                    <div class="col-11">
                                                                                        <div class="row">
                                                                                        @for($j=0;$j < $project_map->entity->col;$j++)
                                                                                            @php
                                                                                                $flag = 0;
                                                                                                $liq_id = 0;
                                                                                                $volume  = 0;
                                                                                                $map_liquids = json_decode($project_map->liquids);
                                                                                                if($map_liquids){
                                                                                                    foreach ($map_liquids as $map_liquid){
                                                                                                        if(($map_liquid->row == ($i+1)) && ($map_liquid->col == ($project_map->entity->col - $j))){
                                                                                                            $flag = 1;
                                                                                                            $liq_id = $map_liquid->id;
                                                                                                            $volume = $map_liquid->volume;
                                                                                                            break;
                                                                                                        }
                                                                                                    }
                                                                                                }
                                                                                            @endphp
                                                                                            <div class="col-{{$k}} mt-2">
                                                                                                <div class="circle @if($flag) selected @endif" @if($flag) liquid-id="{{$liq_id}}"  liquid-vol="{{$volume}}" @endif row="{{$i+1}}" col="{{$project_map->entity->col - $j}}">

                                                                                                </div>
                                                                                            </div>
                                                                                        @endfor
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-1 d-flex align-items-center justify-content-center">
                                                                                        <div class="row mt-2">
                                                                                            <p>{{$rows[$i]}}</p>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            @endfor

                                                                        </div>
                                                                    </form>
                                                                    @endif
                                                                </div>

                                                                <div class="modal-footer">
                                                                    <p class="invalid-feedback  "><strong>به طور همزمان نمیتوانید مکان های دارای مقدار و بدون مقدار را انتخاب کنید.</strong></p>
                                                                    <button type="button" class="btn btn-success add-selecting-liquid " >ثبت</button>
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">انصراف</button>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @elseif(($devicePosition->position->type == 4) ||($devicePosition->position->type == 5))
                                            <div class="col-3 mt-3">
                                                <div class="col-wrapper @if($project_map) two-col @endif d-flex align-items-center justify-content-center" id="wrapper{{$devicePosition->id}}">
                                                    <p class="entity">{{$devicePosition->position->name}}</p>
                                                    <div class="add-liquids">
                                                        @if($project_map)
                                                            <p>{{$project_map->entity->name}} </p>
                                                            <div class="btn-wrapper">
                                                                <a  href="#" class="btn btn-danger btn-sm delete-project-map" project-map-id="{{$project_map->id}}" > حذف </a>
                                                                <a  href="#" class="btn btn-success btn-sm calibrate-project-map" data-toggle="modal" data-target="#calibrate{{$project_map->id}}" project-map-id="{{$project_map->id}}"  > کالیبراسیون </a>
                                                            </div><div class="btn-wrapper">
                                                                <a class="add_liquids btn btn-primary btn-sm" data-toggle="modal" data-target="#add_liquids{{$project_map->id}}" href="#" >اضافه کردن نمونه ها</a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="overlay align-items-center justify-content-center" @if($project_map) style="display: none !important;" @endif>
                                                        <a class="entity" data-toggle="modal" data-target="#entity{{$devicePosition->id}}" href="#"  device-position-id="{{$devicePosition->id}}" project-id="{{$id}}"><i class="fa fa-plus-square-o"></i></a>
                                                    </div>
                                                    <!-- Modal -->
                                                    <div class="modal fade" id="entity{{$devicePosition->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content" style="direction:rtl">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">انتخاب ظرف برای {{$devicePosition->position->name}}</h5>
                                                                </div>
                                                                <div class="modal-body">
                                                                    @php
                                                                    if($devicePosition->position->type == 5 || $devicePosition->position->type == 3){
                                                                        $entities = \App\Models\Entity::where('position_type_match','=',$devicePosition->position->type)->where(function ($query) {$query->where('type','=',0)->orWhere('type','=',2);})->get();
                                                                    }else{
                                                                        $entities = \App\Models\Entity::where('type','=',0)->where('position_type_match','=',$devicePosition->position->type)->get();
                                                                    }
                                                                    @endphp
                                                                    <form class="" >
                                                                        <div class="form-group">
                                                                            <label>انتخاب ظرف</label>
                                                                            <select class="form-control select2 entity-select" >
                                                                                @foreach($entities as $key => $entity)
                                                                                <option value="{{$entity->id}}">{{$entity->name}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-success add-entity"  device-position-id="{{$devicePosition->id}}" project-id="{{$id}}" >ثبت</button>
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">انصراف</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal fade calibrate" @if($project_map) id="calibrate{{$project_map->id}}" @endif tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content" style="direction:rtl">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">کالیبراسیون {{$devicePosition->position->name}}</h5>
                                                                </div>
                                                                <div class="modal-body">
                                                                    @if($project_map)
                                                                        <form class="" >
                                                                            <div class="form-group">
                                                                                <label>جا به جایی در راستای محور x</label>
                                                                                <input class="form-control" type="number" name="x" placeholder="مقادیر مثبت و منفی قابل قبول است." />
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>جا به جایی در راستای محور y</label>
                                                                                <input class="form-control" type="number" name="y" placeholder="مقادیر مثبت و منفی قابل قبول است." />
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>جا به جایی در راستای محور z</label>
                                                                                <input class="form-control" type="number" name="z" placeholder="مقادیر مثبت و منفی قابل قبول است." />
                                                                            </div>
                                                                        </form>
                                                                    @endif
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-success add-calibrate"  @if($project_map) project-map-id="{{$project_map->id}}" @endif >ثبت</button>
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">انصراف</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal fade add-liquid-modal" @if($project_map) id="add_liquids{{$project_map->id}}" @endif tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content" style="direction:rtl">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">انتخاب نمونه برای {{$devicePosition->position->name}}</h5>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="liquids-wrapper" >
                                                                        <form >
                                                                            <div class="alert alert-success mt-3 d-none">نمونه با موفقیت ثبت شد.</div>
                                                                            <div class="form-group has-error">
                                                                                <label for="liquids">مایع</label>
                                                                                <select class="form-control" name="liquid">
                                                                                    @foreach($liquids as $key => $liquid)
                                                                                        <option value="{{$liquid->id}}">{{$liquid->name}}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                            <div class="form-group has-error">
                                                                                <label for="volume">حجم (میکرولیتر)</label>
                                                                                <input id="volume" type="number" class="form-control" name="volume" min="0" aria-label="Small" aria-describedby="inputGroup-sizing-sm" placeholder="حجم" />
                                                                                <span class="invalid-feedback d-none" role="alert">
                                                                                    <strong>حجم درخواستی بیشتر از حجم موجود مایع می باشد.</strong>
                                                                                </span>
                                                                            </div>
                                                                            <input type="hidden" name="project-map" value="@if($project_map){{$project_map->id}}@endif" />
                                                                            {{--                                                                        <input type="hidden" name="row" value="" />--}}
                                                                            {{--                                                                        <input type="hidden" name="col" value="" />--}}
                                                                            <input type="hidden" name="selected" value="" />
                                                                            <button type="button" class="btn btn-success add-liquid">ثبت</button>
                                                                            <button type="button" class="btn btn-success edit-liquid d-none">تغییر</button>
                                                                            <button type="button" class="btn btn-danger remove-liquid d-none">حذف</button>
                                                                            <button type="button" class="btn btn-secondary cancel-liquid" >انصراف</button>
                                                                        </form>
                                                                    </div>
                                                                    @if($project_map)
                                                                    @php
                                                                    if($project_map->entity->col){
                                                                        $k = 12/($project_map->entity->col);
                                                                        $k = floor($k);
                                                                        $rows = array('A','B','C','D','E','F','G','H','I','J','K','L','M','O');
                                                                    }
                                                                    @endphp
                                                                    <form class="form-wrapper" >
                                                                        <div class="form-group">
                                                                            {{--                                                                    <label>انتخاب نمونه</label>--}}
                                                                            {{--                                                                    <select class="form-control select2 entity-select" >--}}
                                                                            {{--                                                                        @foreach($liquids as $key => $liquid)--}}
                                                                            {{--                                                                            <option value="{{$liquid->id}}">{{$liquid    ->name}}</option>--}}
                                                                            {{--                                                                        @endforeach--}}
                                                                            {{--                                                                    </select>--}}
                                                                            <div class="row">
                                                                                <div class="col-11">
                                                                                    <div class="row">
                                                                                        @for($n=0;$n < $project_map->entity->col;$n++)
                                                                                            <div class="col-{{$k}} mt-2 d-flex align-items-center justify-content-center">
                                                                                                <p>{{$project_map->entity->col - $n}}</p>
                                                                                            </div>
                                                                                        @endfor
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            @for($i=0;$i < $project_map->entity->row;$i++)
                                                                                <div class="row">
                                                                                    <div class="col-11">
                                                                                        <div class="row">
                                                                                        @for($j=0;$j < $project_map->entity->col;$j++)
                                                                                            @php
                                                                                                $flag = 0;
                                                                                                $liq_id = 0;
                                                                                                $volume  = 0;
                                                                                                $map_liquids = json_decode($project_map->liquids);
                                                                                                if($map_liquids){
                                                                                                    foreach ($map_liquids as $map_liquid){
                                                                                                        if(($map_liquid->row == ($i+1)) && ($map_liquid->col == ($project_map->entity->col - $j))){
                                                                                                            $flag = 1;
                                                                                                            $liq_id = $map_liquid->id;
                                                                                                            $volume = $map_liquid->volume;
                                                                                                            break;
                                                                                                        }
                                                                                                    }
                                                                                                }
                                                                                            @endphp
                                                                                            <div class="col-{{$k}} mt-2 circle-wrapper">
                                                                                                <div class="circle @if($flag) selected @endif" @if($flag) liquid-id="{{$liq_id}}"  liquid-vol="{{$volume}}" @endif row="{{$i+1}}" col="{{$project_map->entity->col - $j}}">

                                                                                                </div>
                                                                                            </div>
                                                                                        @endfor
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-1 d-flex align-items-center justify-content-center">
                                                                                        <div class="row mt-2">
                                                                                            <p>{{$rows[$i]}}</p>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            @endfor

                                                                        </div>
                                                                    </form>
                                                                    @endif
                                                                </div>

                                                                <div class="modal-footer">
                                                                    <p class="invalid-feedback"><strong>به طور همزمان نمیتوانید مکان های دارای مقدار و بدون مقدار را انتخاب کنید.</strong></p>
                                                                    <button type="button" class="btn btn-success add-selecting-liquid " >ثبت</button>
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">انصراف</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        @endif
                               @endforeach
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="row">
                                    @foreach($devicePositions as $devicePosition)
                                    @if($devicePosition->position->type == 7)
                                        @php
                                            $project_map = \App\Models\ProjectMap::where('device_position_id','=',$devicePosition->id)->first();
                                        @endphp
                                        <div class="col-12 mt-3">
                                            <div class="col-wrapper @if($project_map) two-col @endif d-flex align-items-center justify-content-center" id="wrapper{{$devicePosition->id}}">
                                                <p class="entity">{{$devicePosition->position->name}}</p>
                                                <div class="add-liquids">
                                                    @if($project_map)
                                                        <p>{{$project_map->entity->name}} </p>
                                                        <div class="btn-wrapper">
                                                            <a  href="#" class="btn btn-danger btn-sm delete-project-map" project-map-id="{{$project_map->id}}" > حذف </a>
                                                            <a  href="#" class="btn btn-success btn-sm calibrate-project-map" data-toggle="modal" data-target="#calibrate{{$project_map->id}}" project-map-id="{{$project_map->id}}"  > کالیبراسیون </a>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="overlay align-items-center justify-content-center" @if($project_map) style="display: none !important;" @endif>
                                                    <a class="entity" data-toggle="modal" data-target="#entity{{$devicePosition->id}}" href="#"  device-position-id="{{$devicePosition->id}}" project-id="{{$id}}"><i class="fa fa-plus-square-o"></i></a>
                                                </div>
                                                <!-- Modal -->
                                                <div class="modal fade" id="entity{{$devicePosition->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content" style="direction:rtl">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">انتخاب ظرف برای {{$devicePosition->position->name}}</h5>
                                                            </div>
                                                            <div class="modal-body">
                                                                @php
                                                                    $entities = \App\Models\Entity::where('type','=',1)->where('position_type_match','=',$devicePosition->position->type)->get();

                                                                @endphp
                                                                <form class="" >
                                                                    <div class="form-group">
                                                                        <label>انتخاب ظرف</label>
                                                                        <select class="form-control select2 entity-select" >
                                                                            @foreach($entities as $key => $entity)
                                                                                <option value="{{$entity->id}}">{{$entity->name}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-success add-entity"  device-position-id="{{$devicePosition->id}}" project-id="{{$id}}" >ثبت</button>
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">انصراف</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal fade calibrate" @if($project_map) id="calibrate{{$project_map->id}}" @endif tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content" style="direction:rtl">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">کالیبراسیون {{$devicePosition->position->name}}</h5>
                                                            </div>
                                                            <div class="modal-body">
                                                                @if($project_map)
                                                                    <form class="" >
                                                                        <div class="form-group">
                                                                            <label>جا به جایی در راستای محور x</label>
                                                                            <input class="form-control" type="number" name="x" placeholder="مقادیر مثبت و منفی قابل قبول است." value="{{$project_map->x}}"/>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>جا به جایی در راستای محور y</label>
                                                                            <input class="form-control" type="number" name="y" placeholder="مقادیر مثبت و منفی قابل قبول است." value="{{$project_map->y}}"/>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>جا به جایی در راستای محور z</label>
                                                                            <input class="form-control" type="number" name="z" placeholder="مقادیر مثبت و منفی قابل قبول است." value="{{$project_map->z}}"/>
                                                                        </div>
                                                                    </form>
                                                                @endif
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-success add-calibrate"  @if($project_map) project-map-id="{{$project_map->id}}" @endif >ثبت</button>
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">انصراف</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <aside class="control-sidebar control-sidebar-dark text-center" style="top:57px;">
            <h5 class="mt-3">پروسه ها</h5>
            <button class="btn btn-sm btn-success mt-2" id="add-protocol">افزودن پروسه</button>
            <ul class="connected-sortable droppable-area mt-4">
                @php
                    $project_protocols = \App\Models\ProjectProtocol::where('project_id','=',$id)->orderBy('sequence')->get();
                @endphp
                @foreach($project_protocols as $project_protocol)
                    <li class="draggable-item" id="protocol{{$project_protocol->id}}"><p>{{$project_protocol->entity->name}}</p><button class="btn btn-sm btn-success edit-protocol" protocol-id="{{$project_protocol->id}}" >تغییر</button> <button class="btn btn-sm btn-danger delete-protocol" protocol-id="{{$project_protocol->id}}" > حذف</button> </li>
                @endforeach
            </ul>
        </aside>
        <div class="add-protocol" >
            <div class="header">
                <h5 class="modal-title" id="exampleModalLabel">افزودن پروتکل</h5>
            </div>
            <div class="row mt-3 content">
                <div class="col-12">
                    @php
                        $entities = \App\Models\Entity::where('type','=',4)->get();
                    @endphp
                    <div class="modal-wrapper">
                    @foreach($devicePositions as $key => $devicePosition)
                        @if(($devicePosition->position->type != 7) && ($devicePosition->position->type != 6)  && ($devicePosition->position->type != 0))
                            @php
                                $project_map = \App\Models\ProjectMap::where('device_position_id','=',$devicePosition->id)->where('project_id','=',$id)->first();
                            @endphp
                                <div class="modal fade add-modal"  @if($project_map) id="add_target{{$project_map->id}}" @endif tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" device-position-id="{{$devicePosition->id}}">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content" style="direction:rtl">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">انتخاب نمونه</h5>
                                            </div>
                                            <div class="modal-body">
                                                @if($project_map)
                                                @php
                                                    if($project_map->entity->col){
                                                        $k = 12/($project_map->entity->col);
                                                        $k = floor($k);
                                                        $rows = array('A','B','C','D','E','F','G','H','I','J','K','L','M','O');
                                                    }
                                                @endphp
                                                <form class="form-wrapper" >
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-11">
                                                                <div class="row">
                                                                    @for($n=0;$n < $project_map->entity->col;$n++)
                                                                        <div class="col-{{$k}} mt-2 d-flex align-items-center justify-content-center">
                                                                            <p>{{$project_map->entity->col - $n}}</p>
                                                                        </div>
                                                                    @endfor
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @for($i=0;$i < $project_map->entity->row;$i++)
                                                            <div class="row">
                                                                <div class="col-11">
                                                                    <div class="row">
                                                                        @for($j=0;$j < $project_map->entity->col;$j++)
                                                                            @php
                                                                                $flag = 0;
                                                                                $liq_id = 0;
                                                                                $volume  = 0;
                                                                                $map_liquids = json_decode($project_map->liquids);
                                                                                if($map_liquids){
                                                                                    foreach ($map_liquids as $map_liquid){
                                                                                         if(($map_liquid->row == ($i+1)) && ($map_liquid->col == ($project_map->entity->col - $j))){
                                                                                             $flag = 1;
                                                                                             $liq_id = $map_liquid->id;
                                                                                             $volume = $map_liquid->volume;
                                                                                             break;
                                                                                         }
                                                                                    }
                                                                                }
                                                                            @endphp
                                                                            <div class="col-{{$k}} mt-2">
                                                                                <div class="circle @if($flag) selected @endif" @if($flag) liquid-id="{{$liq_id}}"  liquid-vol="{{$volume}}" @endif row="{{$i+1}}" col="{{$project_map->entity->col - $j}}">
                                                                                </div>
                                                                            </div>
                                                                        @endfor
                                                                    </div>
                                                                </div>
                                                                <div class="col-1 d-flex align-items-center justify-content-center">
                                                                    <div class="row mt-2">
                                                                        <p>{{$rows[$i]}}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endfor

                                                    </div>
                                                </form>
                                                @endif
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-success add-target-liquid" project-map-id="@if($project_map){{$project_map->id}}@endif">ثبت</button>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">انصراف</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                    @endforeach
                    </div>
                    <form class="" >
                        <div class="form-group">
                            <label>انتخاب پروتکل</label>
                            <select class="form-control entity-select" >
                                @foreach($entities as $key => $entity)
                                    <option value="{{$entity->id}}" type="{{$entity->position_type_match}}">{{$entity->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        @foreach($entities as $key => $entity)
                            @if($entity->position_type_match == 0)
                                <div class="extra-input display-extra" id="protocol-type-{{$entity->position_type_match}}">
                                    <div class="form-group sampler">
                                        <label>نوع سمپلر</label>
                                        <select class="form-control project-map-select" name="sampler">
                                            @foreach($devicePositions as $key => $devicePosition)
                                                @if(($devicePosition->position->type == 7) )
                                                    @php
                                                        $project_map = \App\Models\ProjectMap::where('device_position_id','=',$devicePosition->id)->where('project_id','=',$id)->first();
                                                    @endphp
                                                    @if($project_map)
                                                        <option value="{{$project_map->id}}"  >{{$project_map->entity->name}} - {{$project_map->devicePosition->position->name}}</option>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group source">
                                        <label>مبدا</label>
                                        <select class="form-control project-map-select" name="source">
                                            @foreach($devicePositions as $key => $devicePosition)
                                                @if(($devicePosition->position->type != 7)  && ($devicePosition->position->type != 0) && ($devicePosition->position->type != 8))
                                                    @php
                                                        $project_map = \App\Models\ProjectMap::where('device_position_id','=',$devicePosition->id)->where('project_id','=',$id)->first();
                                                    @endphp
                                                    @if($project_map)
                                                        <option value="{{$project_map->id}}"  >{{$project_map->entity->name}}</option>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </select>
                                        <button class="btn btn-sm btn-success btn-add-entity " data-toggle="modal" data-target="">افزودن نمونه</button>
                                        <input type="hidden" class="selected" name="source-selected" value="" />
                                    </div>
                                    <div class="form-group">
                                        <label>حجم نمونه در مبدا</label>
                                        <input type="text" class="form-control" name="source-volume" value="" aria-label="Small" aria-describedby="inputGroup-sizing-sm" placeholder="حجم نمونه در مبدا برای هر محل انتخاب شده">
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                    <div class="form-group target">
                                        <label>مقصد</label>
                                        <select class="form-control project-map-select" name="target">
                                            @foreach($devicePositions as $key => $devicePosition)
                                                @if(($devicePosition->position->type != 7)  && ($devicePosition->position->type != 0) && ($devicePosition->position->type != 8))
                                                    @php

                                                        $project_map = \App\Models\ProjectMap::where('device_position_id','=',$devicePosition->id)->where('project_id','=',$id)->first();
                                                    @endphp
                                                    @if($project_map)
                                                        <option value="{{$project_map->id}}" >{{$project_map->entity->name}}</option>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </select>
                                        <button class="btn btn-sm btn-success btn-add-entity" data-toggle="modal" data-target="">افزودن نمونه</button>
                                        <input type="hidden" class="selected"  name="target-selected" value="" />
                                    </div>
                                    <div class="form-group">
                                        <label>حجم نمونه در مقصد</label>
                                        <input type="text" class="form-control" name="target-volume" value="" aria-label="Small" aria-describedby="inputGroup-sizing-sm" placeholder="حجم نمونه در مقصد برای هر محل انتخاب شده">
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="tip-change" value="1">
                                            <label class="form-check-label">تیپ ها در هر جابه جایی تعویض شود.</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input class="form-check-input" type="checkbox" name="loop" value="1">
                                        <label class="form-check-label">نوع تکرار فرآیند</label>
                                    </div>
                                </div>
                            @elseif($entity->position_type_match == 1)
                                <div class="extra-input" id="protocol-type-{{$entity->position_type_match}}">
                                    <div class="form-group sampler">
                                        <label>نوع سمپلر</label>
                                        <select class="form-control project-map-select" name="sampler">
                                            @foreach($devicePositions as $key => $devicePosition)
                                                @if(($devicePosition->position->type == 7) )
                                                    @php
                                                        $project_map = \App\Models\ProjectMap::where('device_position_id','=',$devicePosition->id)->where('project_id','=',$id)->first();
                                                    @endphp
                                                    @if($project_map)
                                                        <option value="{{$project_map->id}}"  >{{$project_map->entity->name}} - {{$project_map->devicePosition->position->name}}</option>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group target">
                                        <label>مقصد</label>
                                        <select class="form-control project-map-select" name="target">
                                            @foreach($devicePositions as $key => $devicePosition)
                                                @if(($devicePosition->position->type != 7)  && ($devicePosition->position->type != 0) && ($devicePosition->position->type != 8))
                                                    @php

                                                        $project_map = \App\Models\ProjectMap::where('device_position_id','=',$devicePosition->id)->where('project_id','=',$id)->first();
                                                    @endphp
                                                    @if($project_map)
                                                        <option value="{{$project_map->id}}" >{{$project_map->entity->name}}</option>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </select>
                                        <button class="btn btn-sm btn-success btn-add-entity" data-toggle="modal" data-target="">افزودن نمونه</button>
                                        <input type="hidden" class="selected"  name="target-selected" value="" />
                                    </div>
                                    <div class="form-group">
                                        <label>تعداد پیپت</label>
                                        <input type="number" class="form-control" name="pipet-num" value="" aria-label="Small" aria-describedby="inputGroup-sizing-sm" placeholder="تعداد پیپت">
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                    <div class="form-group">
                                        <label>حجم پیپت</label>
                                        <input type="text" class="form-control" name="pipet-volume" value="" aria-label="Small" aria-describedby="inputGroup-sizing-sm" placeholder="حجم پیپت">
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                            @elseif($entity->position_type_match == 2)
                                <div class="extra-input" id="protocol-type-{{$entity->position_type_match}}">
                                    <div class="form-group">
                                        <label>زمان توقف(ثانیه)</label>
                                        <input type="text" class="form-control" name="second" value="" aria-label="Small" aria-describedby="inputGroup-sizing-sm" placeholder="زمان توقف بر حسب ثانیه">
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                            @elseif($entity->position_type_match == 3)
                                <div class="extra-input" id="protocol-type-{{$entity->position_type_match}}">
                                    <div class="form-group">
                                        <label>حجم تیوب</label>
                                        <input type="text" class="form-control" name="tube-volume" value="" aria-label="Small" aria-describedby="inputGroup-sizing-sm" placeholder="حجم تیوب برحسب میلی متر">
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                    <div class="form-group">
                                        <label>زمان اجرا ماژول مگنت</label>
                                        <input type="text" class="form-control" name="second" value="" aria-label="Small" aria-describedby="inputGroup-sizing-sm" placeholder="حجم تیوب برحسب میلی متر">
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="magnet-engage" value="1">
                                            <label class="form-check-label">درگیری آهنربا با تیوب</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>ارتفاع درگیری آهنربا با تیوب ها</label>
                                        <input type="text" class="form-control" name="magnet-height" value="" aria-label="Small" aria-describedby="inputGroup-sizing-sm" placeholder="ارتفاع  برحسب میلی متر">
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>

                                </div>
                            @elseif($entity->position_type_match == 4)
                                <div class="extra-input" id="protocol-type-{{$entity->position_type_match}}">
                                    <div class="form-group">
                                        <label>نوع</label>
                                        <select class="form-control warmer-type" name="warmer-type">
                                            <option value="0" >فقط گرمکن</option>
                                            <option value="1" >فقط همزمن</option>
                                            <option value="2" >گرمکن و همزن با زمان مشابه</option>
                                            <option value="3" >گرمکن و همزن با زمان متفاوت</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>زمان گرمکن</label>
                                        <input type="text" class="form-control" name="warmer-time" value="" aria-label="Small" aria-describedby="inputGroup-sizing-sm" placeholder="زمان بر حسب ثانیه">
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                    <div class="form-group">
                                        <label>دما گرمکن</label>
                                        <input type="text" class="form-control" name="warmer-temperature" value="" aria-label="Small" aria-describedby="inputGroup-sizing-sm" placeholder="دما بر حسب سانتیگراد">
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                    <div class="form-group">
                                        <label>زمان همزن در هر تکرار</label>
                                        <input type="text" class="form-control" name="mixer-time" value="" aria-label="Small" aria-describedby="inputGroup-sizing-sm" placeholder="زمان بر حسب ثانیه">
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                    <div class="form-group">
                                        <label>تعداد تکرار همزن</label>
                                        <input type="number" class="form-control" name="mixer-repeat" value="" aria-label="Small" aria-describedby="inputGroup-sizing-sm" placeholder="تعداد تکرار همزن">
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                            @elseif($entity->position_type_match == 5)
                                <div class="extra-input" id="protocol-type-{{$entity->position_type_match}}">
                                    <div class="form-group">
                                        <label>زمان</label>
                                        <input type="number" class="form-control" name="time" value="" aria-label="Small" aria-describedby="inputGroup-sizing-sm" placeholder="زمان بر حسب ثانیه">
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                            @elseif($entity->position_type_match == 6)
                                <div class="extra-input" id="protocol-type-{{$entity->position_type_match}}">
                                    <div class="form-group">
                                        <label>زمان</label>
                                        <input type="number" class="form-control" name="time" value="" aria-label="Small" aria-describedby="inputGroup-sizing-sm" placeholder="زمان بر حسب ثانیه">
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <span class="invalid-feedback" role="alert">

                 </span>
                <button type="button" class="btn btn-success btn-add-protocol" project-id="{{$id}}" protocol-type="0" method="0" >ثبت</button>
                <button type="button" class="btn btn-secondary"  id="close-protocol">انصراف</button>
            </div>
        </div>
    </div>
    <!-- Main Footer -->
    <footer class="main-footer">
        @include('layouts.partials.footer' , ['text'=> 'امیر صولتی'])
    </footer>

    @include('layouts.partials.footer-script')
</main>
