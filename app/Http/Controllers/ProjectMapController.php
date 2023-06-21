<?php

namespace App\Http\Controllers;

use App\Models\DevicePosition;
use App\Models\Entity;
use App\Models\Liquid;
use App\Models\Position;
use App\Models\Project;
use App\Models\ProjectMap;
use Illuminate\Http\Request;

class ProjectMapController extends Controller
{

    public function showMap($id)
    {
        $project = Project::find($id);
        if ($project) {
            $device = $project->device;
            $devicePositions = DevicePosition::where('device_id', '=', $device->id)->get();
            return view('project-map.project-map', compact('devicePositions', 'id'));
        }
    }

    public function setEntity(Request $request){
        $map = new ProjectMap();
        $devicePosition = DevicePosition::find($request->device_position_id);
        $devicePosition->position;
        $project = Project::find($request->project_id);
        $liquids = $project->liquids;
        $entity = Entity::find($request->entity_id);
        if( $devicePosition && $project && $entity){
            $map->project()->associate($project);
            $map->devicePosition()->associate($devicePosition);
            $map->entity()->associate($entity);
            $map->save();
        }

        return ['map'=>$map,'liquids'=>$liquids];
    }


    public function deleteEntity($id){
        $map = ProjectMap::find($id);
        if($map){
            $devicePosition = $map->devicePosition->id;
            $map->delete();
            return ['devicePosition' => $devicePosition,'map'=>$map->id];
        }
        else{
            return 0;
        }

    }


    public function addCalibrate(Request $request){
        $project_map = ProjectMap::find($request->project_map_id);
        if($project_map){
            $project_map->x = $request->x;
            $project_map->y = $request->y;
            $project_map->z = $request->z;
            $project_map->save();
            return array('status' => 1 );
        }
        else{
            return array('status' => 0 );
        }
    }

    public function addLiquid(Request $request){
        $project_map = ProjectMap::find($request->project_map_id);
        if($project_map){
            $liquid = Liquid::find($request->liquid);
            if($liquid){

                if( $liquid->volume < $request->volume ) {

                    return array('status' => 0 );
                }
                else{
                    $liquids = $project_map->liquids;
                    $liquids = json_decode($liquids);
                    $circles = json_decode($request->selected);
                    if($liquids){
                        foreach ($circles as $circle){
                            $liq = new \stdClass();
                            $liq->id = $request->liquid;
                            $liq->volume = $request->volume;
                            $liq->row = $circle->row;
                            $liq->col = $circle->col;
                            array_push($liquids,$liq);
                        }
                    }
                    else{
                        $liquids = array();
                        foreach ($circles as $circle){
                            $liq = new \stdClass();
                            $liq->id = $request->liquid;
                            $liq->volume = $request->volume;
                            $liq->row = $circle->row;
                            $liq->col = $circle->col;
                            array_push($liquids,$liq);
                        }
                    }
                    $liquids = json_encode($liquids);
                    $project_map->liquids = $liquids;
                    $project_map->save();
                    $liquid->volume -= $request->volume;
                    $liquid->save();
                    return array('status' => 1 );
                }
            }
        }
        else{
            return array('status' => 0 );
        }
    }



    public function editLiquid(Request $request){
        $project_map = ProjectMap::find($request->project_map_id);
        // مپ وجود داشته باشد.
        if($project_map){
            $liquid = Liquid::find($request->liquid);
            // مایع وجود داشته باشد
            if($liquid){
                $liquids = $project_map->liquids;
                $liquids = json_decode($liquids);
                $circles = json_decode($request->selected);
                // در آن ظرف مایع وجود داشته باشد.
                if($liquids){
                    foreach ($liquids as $liq){
                        foreach ($circles as $circle){
                            // ردیف و ستون آن نقطه با ردیف و ستون ذخیره شده برابر باشد.
                            if( $liq->row == $circle->row && $liq->col == $circle->col ){
                                // نوع مایع تغییر نکرده باشد.
                                if($liq->id == $request->liquid){
                                    // حجم مایع درخواستی از حجم کل مایع بیشتر نشود.
                                    if( $liquid->volume + $liq->volume < $request->volume ) {
                                        return array('status' => 0 );
                                    }
                                    else{
                                        $diff = $request->volume - $liq->volume;
                                        $liq->volume += $diff;
                                        $liquid->volume -= $diff;
                                    }
                                }
                                // نوع مایع تغییر کرده باشد.
                                else{
                                    $oldLiquid = Liquid::find($liq->id);
                                    $oldLiquid->volume += $liq->volume;
                                    $oldLiquid->save();
                                    $liq->id = $request->liquid;
                                    // حجم مایع درخواستی از حجم کل مایع بیشتر نشود.
                                    if($liquid->volume < $request->volume ){
                                        return array('status' => 0 );
                                    }
                                    else{
                                        $liq->volume = $request->volume;
                                        $liquid->volume -= $request->volume;
                                    }
                                }
                            }
                        }
                    }
                    $liquids = json_encode($liquids);
                    $project_map->liquids = $liquids;
                    $project_map->save();
                    $liquid->save();
                    return array('status' => 1 );
                }
                else {
                    return array('status' => 0 );
                }
            }
        }
    }


    public function removeLiquid(Request $request) {
        $project_map = ProjectMap::find($request->project_map_id);
        // مپ وجود داشته باشد.
        if($project_map){
            $liquid = Liquid::find($request->liquid);
            // مایع وجود داشته باشد
            if($liquid){
                $liquids = $project_map->liquids;
                $liquids = json_decode($liquids);
                $circles = json_decode($request->selected);
                // در آن ظرف مایع وجود داشته باشد.
                if($liquids){
                    foreach ($liquids as $key => $liq){
                        foreach ($circles as $circle){
                            // ردیف و ستون آن نقطه با ردیف و ستون ذخیره شده برابر باشد.
                            if( $liq->row == $circle->row && $liq->col == $circle->col ){
                                $liquid->volume += $liq->volume;
                                unset($liquids[$key]);
                                $liquids = array_values($liquids);
                            }
                        }
                    }
                    $liquids = json_encode($liquids);
                    $project_map->liquids = $liquids;
                    $project_map->save();
                    $liquid->save();
                    return array('status' => 1 );
                }
                else {
                    return array('status' => 0 );
                }
            }
        }
    }

}
