<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\DevicePosition;
use App\Models\Entity;
use App\Models\Option;
use App\Models\Project;
use App\Models\ProjectMap;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class gcodeDecoderController extends Controller
{

    public $current_direction = ['x' => 0 , 'y' => 0 , 'z' => 0 , 'u' => 0 , 'v' => 0];
    public $speed = array();
    public $estimate_time = 0;

    public function decoder($id){
        $gcode = '';
        $project = Project::find($id);
        if($project){


            // get project map
            $maps = $project->projectMaps;


            //get protocol by sequence order
            $protocols = $project->projectProtocols()->orderBy('sequence')->get();
            

            //  initial tip attribute for three type sampler
            $current_tip_Dimensions_10 = $this->getRackDimensions($project,10);
            $current_tip_index_type_10 = 0;
            $current_tip_row_type_10 = 1;
            $current_tip_col_type_10 = 1;
            $estimate_tip_10 = 0;

            $current_tip_Dimensions_100 = $this->getRackDimensions($project,100);
            $current_tip_index_type_100 = 0;
            $current_tip_row_type_100 = 1;
            $current_tip_col_type_100 = 1;
            $estimate_tip_100 = 0;

            $current_tip_Dimensions_1000 = $this->getRackDimensions($project,1000);
            $current_tip_index_type_1000 = 0;
            $current_tip_row_type_1000 = 1;
            $current_tip_col_type_1000 = 1;
            $estimate_tip_1000 = 0;


            // current tip attribute
            $current_tip_index = 0;
            $current_tip_row = 1;
            $current_tip_col = 1;


            // initial current sampler attribute
            $current_sampler = 0;
            $x = 0;
            $y = 0;
            $z = 0;
            $gripper = 0;
            $rest_position = 0;
            $first_position = 0;
            $second_position = 0;
            $sampler_volume = 0;
            $sampler_height = 0;
            $sampler_offset_x =0;
            $sampler_offset_y =0;
            $sampler_offset_z =0;
            $trash_flag = 0;


            // get standard sampler for calibration
            
            $standard_sampler_offset = DevicePosition::where('device_id','=',$project->device->id)->meta()->where(function($query) {
                $query->where('device_positions_meta.key', '=', 'sampler_volume')->where('device_positions_meta.value', '=', 10);
            })->first();
            if(!$standard_sampler_offset){
                return ['status'=>'0' , 'message'=>'جایگاه نوک سمپلر 10 تعریف نشده است.'];
            }
            $standard_sampler_offset_x = $standard_sampler_offset->point_a['x'];
            $standard_sampler_offset_y = $standard_sampler_offset->point_a['y'];
            $standard_sampler_offset_z = $standard_sampler_offset->point_a['z'];

            //get standard tip position in tip racks
            $tip_calibrate = DevicePosition::where('device_id','=',$project->device->id)->meta()->where(function($query) {
                $query->where('device_positions_meta.key', '=', 'standard')->where('device_positions_meta.value', '=', 1);

            })->whereHas('position', function ($query)  {
                $query->where('type', '=', 0)->orderBy('id');
            })->first();
            if(!$tip_calibrate){
                return ['status'=>'0' , 'message'=>'جایگاه رک معیار تعریف نشده است.'];
            }
            $calibrate_a = $tip_calibrate->point_a;
            $calibrate_b = $tip_calibrate->point_b;

            //get standard device for calibration
            $device_calibrate = Device::meta()->where(function($query) {
                $query->where('devices_meta.key', '=', 'standard')->where('devices_meta.value', '=', 1);
            })->first();
            if(!$device_calibrate){
                return ['status'=>'0' , 'message'=>'دستگاه معیار تعریف نشده است.'];
            }


            if($maps && $protocols){

                if(!$protocols->isNotEmpty()){
                return ( ['status'=> '0' , "message" =>"پروتکل خالیست"] );
            }
                // gcode initial
                $gcode = "G28 Z Y X U V \n G90 \n M150 B255 \n ";

                // calibration speed and safe zone
                $speed_x = $this->speed['x'] = $project->speed_x;
                $speed_y = $this->speed['y'] = $project->speed_y;
                $speed_z = $this->speed['z'] = $project->speed_z;

                $safe_zone = $project->safe_zone;

                // get current sampler value
                if($protocols->isNotEmpty()){
                    foreach ($protocols as $protocol){
                        if($protocol->entity->position_type_match == 0){
                        $sampler = ProjectMap::find($protocol->sampler_id);
                        $sampler_volume = $sampler->entity->volume;
                        }
                    }
                }else{
                    return ['status'=>'0' , 'message'=>'پروتکل وجود ندارد.'];
                }
                
                // get trash position
                $trash = $project->projectMaps()->with('devicePosition')->whereHas('devicePosition.position', function ($query)  {
                    $query->where('type', '=', 6)->orderBy('id');
                })->first();
                if($trash){
                    $trash_point = $trash->devicePosition->point_a;
                    $offset = json_decode($trash->devicePosition->getMeta('offset') , true );
                    // $trash = " ; Trash Code \n G01 U0  F".$speed_z." \n G01 X". $trash_point['x'] ." F".$speed_x." \n G04 P100 \n G01 Y".$trash_point['y']." F".$speed_y." \n G04 P100 \n G01 Z".($trash_point['z'])." F".$speed_z." \n G04 P100 \n M42 P53 S255 \n M42 P51 S0 \n M42 P49 S130 \n G04 P150 \n M42 P49 S0 \n M42 P53 S0 \n M42 P51 S255 \n M42 P49 S130 \n G04 P150 \n M42 P49 S0 \n G01 Z".($safe_zone)." F".$speed_z." \n G04 P100 \n";
                    if($sampler_volume == 10){
                        $trash = " ; Trash Code \n G01 U0  F".$speed_z." \n G01 X". $trash_point['x'] ." F".$speed_x." \n G04 P100 \n G01 Y".$trash_point['y']." F".$speed_y." \n G04 P100 \n G01 Z".($trash_point['z'])." F".$speed_z.
                        " \n G01 Y". $offset['offset_tip_10'] ." F".$speed_y
                        ." \n G04 P100 \n G01 Y".($safe_zone)." F".$speed_z." \n G04 P100 \n "; 
                    }elseif($sampler_volume == 100){
                        $trash = " ; Trash Code \n G01 U0  F".$speed_z." \n G01 X". $trash_point['x'] ." F".$speed_x." \n G04 P100 \n G01 Y".$trash_point['y']." F".$speed_y." \n G04 P100 \n G01 Z".($trash_point['z'])." F".$speed_z.
                        " \n G04 P100 \n G01 Y".$offset['offset_tip_100']." F".$speed_y
                        ." \n G04 P100 \n G01 Y".($safe_zone)." F".$speed_z." \n G04 P100 \n ";
                    }elseif($sampler_volume == 1000){
                        $trash = " ; Trash Code \n G01 U0  F".$speed_z." \n G01 X". $trash_point['x'] ." F".$speed_x." \n G04 P100 \n G01 Y".$trash_point['y']." F".$speed_y." \n G04 P100 \n G01 Z".($trash_point['z'])." F".$speed_z.
                        " \n G04 P100 \n G01 Y".$offset['offset_tip_1000']." F".$speed_y
                        ." \n G04 P100 \n G01 Y".($safe_zone)." F".$speed_z." \n G04 P100 \n ";
                    }
                }else{
                    return ['status'=>'0' , 'message'=>'جایگاه سطل زباله تعریف نشده است.'];
                }

                // gcode of protocols
                foreach ($protocols as $protocol){
                    // a flag when we need change tip is been 1;
                    $tip_change_flag = 0;

                    // gcode of transfer protocol
                    if($protocol->entity->position_type_match == 0){

                        $transfer = " ";
                        //get sampler of protocol
                        $sampler = ProjectMap::find($protocol->sampler_id);

                        
                        

                        // check sampler is in gripper
                        if(!$current_sampler){
                            $current_sampler = $sampler->id;
                            if($sampler->x){
                                $x = $sampler->x;
                            }
                            else{
                                $x = $sampler->devicePosition->point_a['x'];
                            }
                            if($sampler->y){
                                $y = $sampler->y;
                            }
                            else{
                                $y = $sampler->devicePosition->point_a['y'];
                            }
                            if($sampler->z){
                                $z = $sampler->z;
                            }
                            else{
                                $z = $sampler->devicePosition->point_a['z'];
                            }
                            $gripper = $sampler->devicePosition->gripper;
                            $rest_position = $sampler->entity->rest_position;
                            $first_position = $sampler->entity->first_position;
                            $second_position = $sampler->entity->second_position;
                            $sampler_volume = $sampler->entity->volume;
                            // return $sampler_volume;
                            $sampler_offset = DevicePosition::where('device_id','=',$project->device->id)->meta()->where(function($query) use ($sampler_volume) {
                                $query->where('device_positions_meta.key', '=', 'sampler_volume')->where('device_positions_meta.value', '=', $sampler_volume);
                            })->first();
                            if(!$sampler_offset){
                                return ( ['status'=> '0' , "message" =>"جایگاه نوک سمپلر ".$sampler_volume." تعریف نشده است"] );
                            }
                            $sampler_offset_x = $sampler_offset->point_a['x'];
                            $sampler_offset_y = $sampler_offset->point_a['y'];
                            $sampler_offset_z = $sampler_offset->point_a['z'];
                            $sampler_height = $sampler->entity->sampler_height;
                            $sampler_1microliter = ($first_position - $rest_position) / $sampler_volume;
                            $transfer .= " ; Get Sampler \n G01 X60 F".$speed_x." \n G04 P100 \n G01 Y".$y." F".$speed_y." \n G04 P100 \n G01 Z".$z." F".$speed_z." \n G04 P100 \n G01 X". $x ." F".$speed_x." \n G04 P100 \n";
                            $transfer .= "G01 V".$gripper." F".$speed_z." \n G04 P100 \n G01 Z". ($z-10) ." F".$speed_z." \n G04 P100 \n G01 U".$rest_position."  F".$speed_z."\n G04 P100 \n G01 X60 F".$speed_x." \n G04 P100 \n";
                            $this->getTime(60,$y,$z,-1 ,-1,300);
                            $this->getTime($x,-1,($z-10),$gripper ,$rest_position,400);
                            $this->getTime(60,-1,-1,-1 ,-1,100);
                            if($sampler_volume == 10){
                                $current_tip_index = $current_tip_index_type_10;
                                $current_tip_row = $current_tip_row_type_10;
                                $current_tip_col = $current_tip_col_type_10;
                            }
                            elseif($sampler_volume == 100){
                                $current_tip_index = $current_tip_index_type_100;
                                $current_tip_row = $current_tip_row_type_100;
                                $current_tip_col = $current_tip_col_type_100;
                            }
                            elseif ($sampler_volume == 1000){
                                $current_tip_index = $current_tip_index_type_1000;
                                $current_tip_row = $current_tip_row_type_1000;
                                $current_tip_col = $current_tip_col_type_1000;
                            }
                        }


                        // check sampler in gripper diffrence from selected sampler
                        elseif($current_sampler != $sampler->id){
                            $current_sampler_obj = ProjectMap::find($current_sampler);
                            if($current_sampler_obj->x){
                                $x = $current_sampler_obj->x;
                            }
                            else{
                                $x = $current_sampler_obj->devicePosition->point_a['x'];
                            }
                            if($current_sampler_obj->y){
                                $y = $current_sampler_obj->y;
                            }
                            else{
                                $y = $current_sampler_obj->devicePosition->point_a['y'];
                            }
                            if($current_sampler_obj->z){
                                $z = $current_sampler_obj->z;
                            }
                            else{
                                $z = $current_sampler_obj->devicePosition->point_a['z'];
                            }
                            $current_sampler_volume = $current_sampler_obj->entity->volume;
                            if($current_sampler_volume == 10){
                                $current_tip_index_type_10 = $current_tip_index;
                                $current_tip_row_type_10 = $current_tip_row;
                                $current_tip_col_type_10 = $current_tip_col;
                            }
                            elseif($current_sampler_volume == 100){
                                $current_tip_index_type_100 = $current_tip_index;
                                $current_tip_row_type_100 = $current_tip_row;
                                $current_tip_col_type_100 = $current_tip_col;
                            }
                            elseif ($current_sampler_volume == 1000){
                                $current_tip_index_type_1000 = $current_tip_index;
                                $current_tip_row_type_1000 = $current_tip_row;
                                $current_tip_col_type_1000 = $current_tip_col;
                            }
                            $transfer .= $trash;
                            $this->getTime($trash_point['x'],$trash_point['y'],$trash_point['z'],-1 ,0,400);
                            $this->getTime(-1,-1,$safe_zone,-1 ,-1);
                            $transfer .= " ; put sampler \n G01 X60 F".$speed_x." \n G04 P100 \n G01 Y".$y." F".$speed_y." \n G04 P100 \n G01 Z".($z-10)." F".$speed_z." \n G04 P100 \n G01 X". $x ." F".$speed_x." \n G04 P100 \n";
                            $transfer .= "G01 Z". ($z) ." F".$speed_z." \n G04 P100 \n G01 U0  F".$speed_z."\n G04 P100 \n G01 V0 F".$speed_z." \n G04 P100 \n G01 X60 F".$speed_x." \n G04 P100 \n";
                            $this->getTime(60,$y,($z-10),-1 ,-1,300);
                            $this->getTime($x,-1,$z,0 ,0,400);
                            $this->getTime(60,-1,-1,-1 ,-1,100);
                            $current_sampler = $sampler->id;
                            if($sampler->x){
                                $x = $sampler->x;
                            }
                            else{
                                $x = $sampler->devicePosition->point_a['x'];
                            }
                            if($sampler->y){
                                $y = $sampler->y;
                            }
                            else{
                                $y = $sampler->devicePosition->point_a['y'];
                            }
                            if($sampler->z){
                                $z = $sampler->z;
                            }
                            else{
                                $z = $sampler->devicePosition->point_a['z'];
                            }
                            $gripper = $sampler->devicePosition->gripper;
                            $rest_position = $sampler->entity->rest_position;
                            $first_position = $sampler->entity->first_position;
                            $second_position = $sampler->entity->second_position;
                            $sampler_volume = $sampler->entity->volume;
                            $sampler_height = $sampler->entity->sampler_height;
                            $sampler_offset = DevicePosition::where('device_id','=',$project->device->id)->meta()->where(function($query) use ($sampler_volume) {
                                $query->where('device_positions_meta.key', '=', 'sampler_volume')->where('device_positions_meta.value', '=', $sampler_volume);
                            })->first();
                            if(!$sampler_offset){
                                return ( ['status'=> '0' , "message" =>"جایگاه نوک سمپلر ".$sampler_volume." تعریف نشده است"] );
                            }
                            $sampler_offset_x = $sampler_offset->point_a['x'];
                            $sampler_offset_y = $sampler_offset->point_a['y'];
                            $sampler_offset_z = $sampler_offset->point_a['z'];
                            $sampler_1microliter = ($first_position - $rest_position) / $sampler_volume;
                            $transfer .= " ; get another sampler \n G01 X60 F".$speed_x." \n G04 P100 \n G01 Y".$y." F".$speed_y." \n G04 P100 \n G01 Z".$z." F".$speed_z." \n G04 P100 \n G01 X". $x ." F".$speed_x." \n G04 P100 \n";
                            $transfer .= "G01 V".$gripper." F".$speed_z." \n G04 P100 \n G01 Z". ($z-10) ." F".$speed_z." \n G04 P100 \n G01 U".$rest_position."  F".$speed_z."\n G04 P100 \n G01 X60 F".$speed_x." \n G04 P100 \n";
                            $this->getTime(60,$y,$z,-1 ,-1,300);
                            $this->getTime($x,-1,($z-10),$gripper ,$rest_position,400);
                            $this->getTime(60,-1,-1,-1 ,-1,100);
                            $trash_flag = 0;
                            if($sampler_volume == 10){
                                $current_tip_index = $current_tip_index_type_10;
                                $current_tip_row = $current_tip_row_type_10;
                                $current_tip_col = $current_tip_col_type_10;
                            }
                            elseif($sampler_volume == 100){
                                $current_tip_index = $current_tip_index_type_100;
                                $current_tip_row = $current_tip_row_type_100;
                                $current_tip_col = $current_tip_col_type_100;
                            }
                            elseif ($sampler_volume == 1000){
                                $current_tip_index = $current_tip_index_type_1000;
                                $current_tip_row = $current_tip_row_type_1000;
                                $current_tip_col = $current_tip_col_type_1000;
                            }
                        }
                        $map = $project->projectMaps()->with('devicePosition')->whereHas('devicePosition.position', function ($query)  {
                            $query->where('type', '=', 0)->orderBy('id');
                        })->with('entity')->whereHas('entity', function ($query) use ($sampler_volume) {
                            $query->meta()->where(function($query) use ($sampler_volume) {
                                $query->where('entities_meta.key', '=', 'tip_volume')->where('entities_meta.value', '=', $sampler_volume);
                            });
                        })->get();

                       if($map){
                           //get attributes of tip rack
                        $rack_a = json_decode($map[$current_tip_index]->entity->point_a);
                        $rack_b = json_decode($map[$current_tip_index]->entity->point_b);
                        $rack_c = json_decode($map[$current_tip_index]->entity->point_c);
                        $height = $map[$current_tip_index]->entity->height;
                        $row_interval = $map[$current_tip_index]->entity->row_interval;
                        $col_interval = $map[$current_tip_index]->entity->col_interval;
                        $row = $map[$current_tip_index]->entity->row;
                        $col = $map[$current_tip_index]->entity->col;
                        $height_tip = $map[$current_tip_index]->entity->height_tip;
                        $tip_offset = $map[$current_tip_index]->entity->offset;
                        $point_a = $map[$current_tip_index]->devicePosition->point_a;
                        $point_b = $map[$current_tip_index]->devicePosition->point_b;
                        $position_type = $map[$current_tip_index]->devicePosition->position->type;
                        $calibrate = $this->calibration($project->device,$device_calibrate,$position_type,$point_a,$point_b,$calibrate_a,$calibrate_b,$rack_a,$rack_b,$rack_c,$row,$col );
                        $tip_offset = $map[$current_tip_index]->entity->offset;
                        $offset_x = $sampler_offset_x - $standard_sampler_offset_x;
                        $offset_y = $sampler_offset_y - $standard_sampler_offset_y;
                        $offset_z = $sampler_offset_z - $standard_sampler_offset_z;
                        $final_desc = $this->final_destination($map[$current_tip_index],$calibrate[0],$current_tip_row,$calibrate[4],$current_tip_col,$calibrate[3],0,0,0 ,$offset_x,$offset_y,$offset_z,$tip_offset);
                        // check if tips in sampler
                        if(!$trash_flag){
                            $safe = 0;
                            // prevent to produce minus z
                            if($safe_zone-$height_tip > 0){
                                $safe = $safe_zone-$height_tip;
                            }
                            $transfer .= " ; get tip \n G01 Y".$final_desc[1]." F".$speed_y." \n G04 P100 \n G01 X". $final_desc[0] ." F".$speed_x." \n G04 P100 G01 \n Z".($final_desc[2] - $tip_offset)." F".$speed_z." \n G04 P300 \n G01 Z".($final_desc[2])." F".$speed_z." \n G01 Z".($safe)." F".$speed_z." \n G04 P100 \n";
                            $this->getTime($final_desc[0],$final_desc[1],$final_desc[2],-1 ,-1,600);
                            $this->getTime(-1,-1,$safe,-1 ,-1,200);
                            $trash_flag = 1;
                        }
                        else{
                            $safe = 0;
                            // prevent to produce minus z
                            if($safe_zone-$height_tip > 0){
                                $safe = $safe_zone-$height_tip;
                            }
                            $transfer .= $trash;
                            $this->getTime($trash_point['x'],$trash_point['y'],$trash_point['z'],-1 ,0,400);
                            $this->getTime(-1,-1,$safe_zone,-1 ,-1);
                            $transfer .= " ; get tip \n G01 Y".$final_desc[1]." F".$speed_y." \n G04 P100 \n G01 X". $final_desc[0] ." F".$speed_x." \n G04 P100 \n G01 Z".($final_desc[2] - $tip_offset)." F".$speed_z." \n G04 P300 \n G01 Z".($final_desc[2])." F".$speed_z." \n G01 Z".($safe)." F".$speed_z." \n G04 P100 \n";
                            $this->getTime($final_desc[0],$final_desc[1],$final_desc[2],-1 ,-1,600);
                            $this->getTime(-1,-1,$safe,-1 ,-1,200);
                        }

                        // change current tip location for next round
                        $current_tip_col++;
                        if($current_tip_col > $col ){
                            $current_tip_row++;
                            $current_tip_col = 1;
                            if($current_tip_row > $row ){
                                $current_tip_index++;
                                $current_tip_row = 1;
                            }
                        }

                        // get source attributes
                        $source_a = json_decode($protocol->source->entity->point_a);
                        $source_b = json_decode($protocol->source->entity->point_b);
                        $source_c = json_decode($protocol->source->entity->point_c);
                        $source_row = $protocol->source->entity->row;
                        $source_col = $protocol->source->entity->col;
                        $source_tube_volume = $protocol->source->entity->tube_volume;
                        $source_height_tube = $protocol->source->entity->height_tube;
                        $point_a = $protocol->source->devicePosition->point_a;
                        $point_b = $protocol->source->devicePosition->point_b;
                        $position_type = $protocol->source->devicePosition->position->type;
                        $source_calibrate = $this->calibration($project->device,$device_calibrate,$position_type,$point_a,$point_b,$point_a,$point_b,$source_a,$source_b,$source_c,$source_row,$source_col);

                        // get target attributes
                        $target_a = json_decode($protocol->target->entity->point_a);
                        $target_b = json_decode($protocol->target->entity->point_b);
                        $target_c = json_decode($protocol->target->entity->point_c);
                        $target_row = $protocol->target->entity->row;
                        $target_col = $protocol->target->entity->col;
                        $target_tube_volume = $protocol->target->entity->tube_volume;
                        $target_height_tube = $protocol->target->entity->height_tube;
                        $point_a = $protocol->target->devicePosition->point_a;
                        $point_b = $protocol->target->devicePosition->point_b;
                        $position_type = $protocol->target->devicePosition->position->type;
                        $target_calibrate = $this->calibration($project->device,$device_calibrate,$position_type,$point_a,$point_b,$point_a,$point_b,$target_a,$target_b,$target_c,$target_row,$target_col);
                        $source_selecteds = json_decode($protocol->source_selected);
                        $target_selecteds = json_decode($protocol->target_selected);
                        $target_count = count($target_selecteds);

                        // check transfer type one to one with interval or many to many
                        if($protocol->loop){
                            foreach( $source_selecteds as $source_selected ){
                                for($j=0; $j < $target_count; $j++){

                                    // check protocol set tip change for evert turn or not
                                    if($protocol->tip_change && $tip_change_flag){
                                        $transfer .= $trash;
                                        $this->getTime($trash_point['x'],$trash_point['y'],$trash_point['z'],-1 ,0,400);
                                        $this->getTime(-1,-1,$safe_zone,-1 ,-1);
                                       // get next tip attributes
                                        $row = $map[$current_tip_index]->entity->row;
                                        $col = $map[$current_tip_index]->entity->col;
                                        $point_a = $map[$current_tip_index]->devicePosition->point_a;
                                        $point_b = $map[$current_tip_index]->devicePosition->point_b;
                                        $position_type = $map[$current_tip_index]->devicePosition->position->type;
                                        $calibrate = $this->calibration($project->device,$device_calibrate,$position_type,$point_a,$point_b,$calibrate_a,$calibrate_b,$rack_a,$rack_b,$rack_c,$row,$col );
                                        $tip_offset = $map[$current_tip_index]->entity->offset;
                                        $offset_x = $sampler_offset_x - $standard_sampler_offset_x;
                                        $offset_y = $sampler_offset_y - $standard_sampler_offset_y;
                                        $offset_z = $sampler_offset_z - $standard_sampler_offset_z;
                                        $final_desc = $this->final_destination($map[$current_tip_index],$calibrate[0],$current_tip_row,$calibrate[4],$current_tip_col,$calibrate[3],0,0,0,$offset_x,$offset_y,$offset_z,$tip_offset);

                                        $transfer .= " ; get another tip \n G01 X". $final_desc[0] ." F".$speed_x." \n G04 P100 \n G01 Y".$final_desc[1]." F".$speed_y." \n G04 P100 \n G01 Z".($final_desc[2] - $tip_offset)." F".$speed_z." \n G04 P300 \n G01 Z".($final_desc[2])." F".$speed_z." \n G01 Z".($safe_zone)." F".$speed_z." \n G04 P100 \n";
                                        $this->getTime($final_desc[0],$final_desc[1],$final_desc[2],-1 ,-1,600);
                                        $this->getTime(-1,-1,$safe,-1 ,-1,200);
                                        // change current tip location for next round
                                        $current_tip_col++;
                                        if($current_tip_col > $col ){
                                            $current_tip_row++;
                                            $current_tip_col = 1;
                                            if($current_tip_row > $row ){
                                                $current_tip_index++;
                                                $current_tip_row = 1;
                                            }
                                        }
                                    }
                                    $offset_x = $sampler_offset_x - $standard_sampler_offset_x;
                                    $offset_y = $sampler_offset_y - $standard_sampler_offset_y;
                                    $offset_z = $sampler_offset_z - $standard_sampler_offset_z;
                                    $source_final_desc = $this->final_destination($protocol->source,$source_calibrate[0],$source_selected->row,$source_calibrate[4],$source_selected->col,$source_calibrate[3],$source_height_tube,$source_tube_volume,$protocol->source_volume,$offset_x,$offset_y,$offset_z,0);
                                    $transfer .= " ; get liquid from source \n G01 X". $source_final_desc[0] ." F".$speed_x." \n G04 P100 \n G01 Y".$source_final_desc[1]." F".$speed_y." \n G04 P100 \n G01 U".$first_position."  F".$speed_z." \n G01 Z".($source_final_desc[2]-$height_tip)." F".$speed_z." \n G04 P100 \n ";

                                    $safe = 0;
                                    if($safe_zone-$height_tip > 0){
                                        $safe = $safe_zone-$height_tip;
                                    }
                                    $transfer .= "G01 U".($first_position-($sampler_1microliter * $protocol->source_volume))."  F".$speed_z." \n G04 P100 \n G01 Z".($safe)." F".$speed_z." \n G04 P100 \n ";
                                    $this->getTime($source_final_desc[0],$source_final_desc[1],($source_final_desc[2]-$height_tip),-1 ,$first_position,300);
                                    $this->getTime(-1,-1,$safe,-1 ,($first_position-($sampler_1microliter * $protocol->source_volume)),200);
                                    $target_final_desc = $this->final_destination($protocol->target,$target_calibrate[0],$target_selecteds[$j]->row,$target_calibrate[4],$target_selecteds[$j]->col,$target_calibrate[3],$target_height_tube,$target_tube_volume,$protocol->target_volume,$offset_x,$offset_y,$offset_z,0);
                                    $transfer .= "; put liquids to target \n G01 X". $target_final_desc[0] ." F".$speed_x." \n G04 P100 \n G01 Y".$target_final_desc[1]." F".$speed_y." \n G04 P100 \n G01 Z".($target_final_desc[2]-$height_tip)." F".$speed_z." \n G04 P100 \n ";
                                    $transfer .= " G01 U".$second_position."  F".($speed_z+2000)." \n G04 P100 \n  G01 Z".($safe_zone)." F".$speed_z." \n G04 P100 \n ";
                                    $this->getTime($target_final_desc[0],$target_final_desc[1],($target_final_desc[2]-$height_tip),-1 ,-1,300);
                                    $this->getTime(-1,-1,$safe,-1 ,$second_position,200);
                                    $tip_change_flag = 1;

                                }
                            }
                        }
                        else{
                            foreach( $source_selecteds as $source_selected ){

                                // check protocol set tip change for evert turn or not
                                if($protocol->tip_change && $tip_change_flag){
                                    $transfer .= $trash;
                                    $this->getTime($trash_point['x'],$trash_point['y'],$trash_point['z'],-1 ,0,400);
                                    $this->getTime(-1,-1,$safe_zone,-1 ,-1);
                                    // get next tip attributes
                                    $row = $map[$current_tip_index]->entity->row;
                                    $col = $map[$current_tip_index]->entity->col;
                                    $point_a = $map[$current_tip_index]->devicePosition->point_a;
                                    $point_b = $map[$current_tip_index]->devicePosition->point_b;
                                    $position_type = $map[$current_tip_index]->devicePosition->position->type;
                                    $calibrate = $this->calibration($project->device,$device_calibrate,$position_type,$point_a,$point_b,$calibrate_a,$calibrate_b,$rack_a,$rack_b,$rack_c,$row,$col );
                                    $tip_offset = $map[$current_tip_index]->entity->offset;
                                    $offset_x = $sampler_offset_x - $standard_sampler_offset_x;
                                    $offset_y = $sampler_offset_y - $standard_sampler_offset_y;
                                    $offset_z = $sampler_offset_z - $standard_sampler_offset_z;
                                    $final_desc = $this->final_destination($map[$current_tip_index],$calibrate[0],$current_tip_row,$calibrate[4],$current_tip_col,$calibrate[3],0,0,0,$offset_x,$offset_y,$offset_z,$tip_offset);

                                    $transfer .= " ; get another tip \n G01 X". $final_desc[0] ." F".$speed_x." \n G04 P100 \n G01 Y".$final_desc[1]." F".$speed_y." \n G04 P100 \n G01 Z".($final_desc[2] - $tip_offset)." F".$speed_z." \n G04 P300 \n G01 Z".($final_desc[2])." F".$speed_z." \n G01 Z".($safe_zone)." F".$speed_z." \n G04 P100 \n";
                                    $this->getTime($final_desc[0],$final_desc[1],$final_desc[2],-1 ,-1,600);
                                    $this->getTime(-1,-1,$safe,-1 ,-1,200);
                                    // change current tip location for next round
                                    $current_tip_col++;
                                    if($current_tip_col > $col ){
                                        $current_tip_row++;
                                        $current_tip_col = 1;
                                        if($current_tip_row > $row ){
                                            $current_tip_index++;
                                            $current_tip_row = 1;
                                        }
                                    }
                                }
                                $offset_x = $sampler_offset_x - $standard_sampler_offset_x;
                                $offset_y = $sampler_offset_y - $standard_sampler_offset_y;
                                $offset_z = $sampler_offset_z - $standard_sampler_offset_z;
                                $source_final_desc = $this->final_destination($protocol->source,$source_calibrate[0],$source_selected->row,$source_calibrate[4],$source_selected->col,$source_calibrate[3],$source_height_tube,$source_tube_volume,$protocol->source_volume,$offset_x,$offset_y,$offset_z,0);
                                $transfer .= " ; get liquid from source \n G01 X". $source_final_desc[0] ." F".$speed_x." \n G04 P100 \n G01 Y".$source_final_desc[1]." F".$speed_y." \n G04 P100 \n G01 U".$first_position."  F".$speed_z." \n G01 Z".($source_final_desc[2]-$height_tip)." F".$speed_z." \n G04 P100 \n ";
                                $safe = 0;
                                if($safe_zone-$height_tip > 0){
                                    $safe = $safe_zone-$height_tip;
                                }
                                $transfer .= "G01 U".($first_position-($sampler_1microliter * $protocol->source_volume))."  F".$speed_z." \n G04 P100 \n G01 Z".($safe)." F".$speed_z." \n G04 P100 \n ";
                                $this->getTime($source_final_desc[0],$source_final_desc[1],($source_final_desc[2]-$height_tip),-1 ,$first_position,300);
                                $this->getTime(-1,-1,$safe,-1 ,($first_position-($sampler_1microliter * $protocol->source_volume)),200);
                                $target_count = count($target_selecteds);
                                foreach ( $target_selecteds as $key => $target_selected ){
                                    if($key+1 == $target_count){
                                        $liquid_change = $second_position;
                                    }
                                    else{
                                        $liquid_change = (($first_position-($sampler_1microliter * $protocol->source_volume))+(($key + 1 )*($sampler_1microliter * $protocol->target_volume)));
                                    }
                                    $offset_x = $sampler_offset_x - $standard_sampler_offset_x;
                                    $offset_y = $sampler_offset_y - $standard_sampler_offset_y;
                                    $offset_z = $sampler_offset_z - $standard_sampler_offset_z;
                                    $target_final_desc = $this->final_destination($protocol->target,$target_calibrate[0],$target_selected->row,$target_calibrate[4],$target_selected->col,$target_calibrate[3],$target_height_tube,$target_tube_volume,$protocol->target_volume,$offset_x,$offset_y,$offset_z,0);
                                    $transfer .= "; put liquids to target \n G01 X". $target_final_desc[0] ." F".$speed_x." \n G04 P100 \n G01 Y".$target_final_desc[1]." F".$speed_y." \n G04 P100 \n G01 Z".($target_final_desc[2]-$height_tip)." F".$speed_z." \n G04 P100 \n ";
                                    $safe = 0;
                                    if($safe_zone-$height_tip > 0){
                                        $safe = $safe_zone-$height_tip;
                                    }
                                    $transfer .= " G01 U".$liquid_change."  F".($speed_z+2000)." \n G04 P100 \n  G01 Z".($safe)." F".$speed_z." \n G04 P100 \n ";
                                    $this->getTime($target_final_desc[0],$target_final_desc[1],($target_final_desc[2]-$height_tip),-1 ,-1,300);
                                    $this->getTime(-1,-1,$safe,-1 ,$second_position,200);
                                    $tip_change_flag = 1;
                                }
                            }
                        }
                       }
                       else{
                        return ( ['status'=> '0' , "message" =>"رک تیپ با حجم سمپلر وجود ندارد"] );
                       }
                        $gcode .= $transfer;
                    }

                    // gcode of pippet protocol
                    elseif($protocol->entity->position_type_match == 1){
                        $pippet = " ";

                        //get sampler of protocol
                        $sampler = ProjectMap::find($protocol->sampler_id);
                        // check sampler is in gripper
                        if(!$current_sampler){
                            $current_sampler = $sampler->id;
                            if($sampler->x){
                                $x = $sampler->x;
                            }
                            else{
                                $x = $sampler->devicePosition->point_a['x'];
                            }
                            if($sampler->y){
                                $y = $sampler->y;
                            }
                            else{
                                $y = $sampler->devicePosition->point_a['y'];
                            }
                            if($sampler->z){
                                $z = $sampler->z;
                            }
                            else{
                                $z = $sampler->devicePosition->point_a['z'];
                            }
                            $gripper = $sampler->devicePosition->gripper;
                            $rest_position = $sampler->entity->rest_position;
                            $first_position = $sampler->entity->first_position;
                            $second_position = $sampler->entity->second_position;
                            $sampler_volume = $sampler->entity->volume;
                            $sampler_height = $sampler->entity->sampler_height;
                            $sampler_1microliter = ($first_position - $rest_position) / $sampler_volume;
                            $sampler_offset = DevicePosition::where('device_id','=',$project->device->id)->meta()->where(function($query) use ($sampler_volume) {
                                $query->where('device_positions_meta.key', '=', 'sampler_volume')->where('device_positions_meta.value', '=', $sampler_volume);
                            })->first();
                            if(!$sampler_offset){
                                return ( ['status'=> '0' , "message" =>"جایگاه نوک سمپلر ".$sampler_volume." تعریف نشده است"] );
                            }
                            $sampler_offset_x = $sampler_offset->point_a['x'];
                            $sampler_offset_y = $sampler_offset->point_a['y'];
                            $sampler_offset_z = $sampler_offset->point_a['z'];
                            $pippet .= " ; Get Sampler \n G01 X60 F".$speed_x." \n G04 P100 \n G01 Y".$y." F".$speed_y." \n G04 P100 \n G01 Z".$z." F".$speed_z." \n G04 P100 \n G01 X". $x ." F".$speed_x." \n G04 P100 \n";
                            $pippet .= "G01 V".$gripper." F".$speed_z." \n G04 P100 \n G01 Z". ($z-10) ." F".$speed_z." \n G04 P100 \n G01 U".$rest_position."  F".$speed_z."\n G04 P100 \n G01 X60 F".$speed_x." \n G04 P100 \n";
                            $this->getTime(60,$y,$z,-1 ,-1,300);
                            $this->getTime($x,-1,($z-10),$gripper ,$rest_position,400);
                            $this->getTime(60,-1,-1,-1 ,-1,100);
                            if($sampler_volume == 10){
                                $current_tip_index = $current_tip_index_type_10;
                                $current_tip_row = $current_tip_row_type_10;
                                $current_tip_col = $current_tip_col_type_10;
                            }
                            elseif($sampler_volume == 100){
                                $current_tip_index = $current_tip_index_type_100;
                                $current_tip_row = $current_tip_row_type_100;
                                $current_tip_col = $current_tip_col_type_100;
                            }
                            elseif ($sampler_volume == 1000){
                                $current_tip_index = $current_tip_index_type_1000;
                                $current_tip_row = $current_tip_row_type_1000;
                                $current_tip_col = $current_tip_col_type_1000;
                            }
                        }

                        // check sampler in gripper diffrence from selected sampler
                        elseif($current_sampler != $sampler->id){
                            $current_sampler_obj = ProjectMap::find($current_sampler);
                            if($current_sampler_obj->x){
                                $x = $current_sampler_obj->x;
                            }
                            else{
                                $x = $current_sampler_obj->devicePosition->point_a['x'];
                            }
                            if($current_sampler_obj->y){
                                $y = $current_sampler_obj->y;
                            }
                            else{
                                $y = $current_sampler_obj->devicePosition->point_a['y'];
                            }
                            if($current_sampler_obj->z){
                                $z = $current_sampler_obj->z;
                            }
                            else{
                                $z = $current_sampler_obj->devicePosition->point_a['z'];
                            }
                            $current_sampler_volume = $current_sampler_obj->entity->volume;
                            if($current_sampler_volume == 10){
                                $current_tip_index_type_10 = $current_tip_index;
                                $current_tip_row_type_10 = $current_tip_row;
                                $current_tip_col_type_10 = $current_tip_col;
                            }
                            elseif($current_sampler_volume == 100){
                                $current_tip_index_type_100 = $current_tip_index;
                                $current_tip_row_type_100 = $current_tip_row;
                                $current_tip_col_type_100 = $current_tip_col;
                            }
                            elseif ($current_sampler_volume == 1000){
                                $current_tip_index_type_1000 = $current_tip_index;
                                $current_tip_row_type_1000 = $current_tip_row;
                                $current_tip_col_type_1000 = $current_tip_col;
                            }
                            $pippet .= $trash;
                            $this->getTime($trash_point['x'],$trash_point['y'],$trash_point['z'],-1 ,0,400);
                            $this->getTime(-1,-1,$safe_zone,-1 ,-1);
                            $pippet .= " ; put sampler \n G01 X60 F".$speed_x." \n G04 P100 \n G01 Y".$y." F".$speed_y." \n G04 P100 \n G01 Z".($z-10)." F".$speed_z." \n G04 P100 \n G01 X". $x ." F".$speed_x." \n G04 P100 \n";
                            $pippet .= "G01 Z". ($z) ." F".$speed_z." \n G04 P100 \n G01 U0  F".$speed_z."\n G04 P100 \n G01 V0 F".$speed_z." \n G04 P100 \n G01 X60 F".$speed_x." \n G04 P100 \n";
                            $this->getTime(60,$y,($z-10),-1 ,-1,300);
                            $this->getTime($x,-1,$z,0 ,0,400);
                            $this->getTime(60,-1,-1,-1 ,-1,100);
                            $current_sampler = $sampler->id;
                            if($sampler->x){
                                $x = $sampler->x;
                            }
                            else{
                                $x = $sampler->devicePosition->point_a['x'];
                            }
                            if($sampler->y){
                                $y = $sampler->y;
                            }
                            else{
                                $y = $sampler->devicePosition->point_a['y'];
                            }
                            if($sampler->z){
                                $z = $sampler->z;
                            }
                            else{
                                $z = $sampler->devicePosition->point_a['z'];
                            }
                            $gripper = $sampler->devicePosition->gripper;
                            $rest_position = $sampler->entity->rest_position;
                            $first_position = $sampler->entity->first_position;
                            $second_position = $sampler->entity->second_position;
                            $sampler_volume = $sampler->entity->volume;
                            $sampler_height = $sampler->entity->sampler_height;
                            $sampler_offset = DevicePosition::where('device_id','=',$project->device->id)->meta()->where(function($query) use ($sampler_volume) {
                                $query->where('device_positions_meta.key', '=', 'sampler_volume')->where('device_positions_meta.value', '=', $sampler_volume);
                            })->first();
                            if(!$sampler_offset){
                                return ( ['status'=> '0' , "message" =>"جایگاه نوک سمپلر ".$sampler_volume." تعریف نشده است"] );
                            }
                            $sampler_offset_x = $sampler_offset->point_a['x'];
                            $sampler_offset_y = $sampler_offset->point_a['y'];
                            $sampler_offset_z = $sampler_offset->point_a['z'];
                            $sampler_1microliter = ($first_position - $rest_position) / $sampler_volume;
                            $pippet .= " ; get another sampler \n G01 X60 F".$speed_x." \n G04 P100 \n G01 Y".$y." F".$speed_y." \n G04 P100 \n G01 Z".$z." F".$speed_z." \n G04 P100 \n G01 X". $x ." F".$speed_x." \n G04 P100 \n";
                            $pippet .= "G01 V".$gripper." F".$speed_z." \n G04 P100 \n G01 Z". ($z-10) ." F".$speed_z." \n G04 P100 \n G01 U".$rest_position."  F".$speed_z."\n G04 P100 \n G01 X60 F".$speed_x." \n G04 P100 \n";
                            $this->getTime(60,$y,$z,-1 ,-1,300);
                            $this->getTime($x,-1,($z-10),$gripper ,$rest_position,400);
                            $this->getTime(60,-1,-1,-1 ,-1,100);
                            $trash_flag = 0;
                            if($sampler_volume == 10){
                                $current_tip_index = $current_tip_index_type_10;
                                $current_tip_row = $current_tip_row_type_10;
                                $current_tip_col = $current_tip_col_type_10;
                            }
                            elseif($sampler_volume == 100){
                                $current_tip_index = $current_tip_index_type_100;
                                $current_tip_row = $current_tip_row_type_100;
                                $current_tip_col = $current_tip_col_type_100;
                            }
                            elseif ($sampler_volume == 1000){
                                $current_tip_index = $current_tip_index_type_1000;
                                $current_tip_row = $current_tip_row_type_1000;
                                $current_tip_col = $current_tip_col_type_1000;
                            }
                        }
                        $map = $project->projectMaps()->with('devicePosition')->whereHas('devicePosition.position', function ($query)  {
                            $query->where('type', '=', 0)->orderBy('id');
                        })->with('entity')->whereHas('entity', function ($query) use ($sampler_volume) {
                            $query->meta()->where(function($query) use ($sampler_volume) {
                                $query->where('entities_meta.key', '=', 'tip_volume')->where('entities_meta.value', '=', $sampler_volume);
                            });
                        })->get();



                        if($map){
                            $calibrate_a = $map[0]->devicePosition->point_a;
                            $calibrate_b = $map[0]->devicePosition->point_b;
                            $rack_a = json_decode($map[$current_tip_index]->entity->point_a);
                            $rack_b = json_decode($map[$current_tip_index]->entity->point_b);
                            $rack_c = json_decode($map[$current_tip_index]->entity->point_c);
                            $height = $map[$current_tip_index]->entity->height;
                            $row_interval = $map[$current_tip_index]->entity->row_interval;
                            $col_interval = $map[$current_tip_index]->entity->col_interval;
                            $row = $map[$current_tip_index]->entity->row;
                            $col = $map[$current_tip_index]->entity->col;
                            $height_tip = $map[$current_tip_index]->entity->height_tip;
                            $row = $map[$current_tip_index]->entity->row;
                            $col = $map[$current_tip_index]->entity->col;
                            $point_a = $map[$current_tip_index]->devicePosition->point_a;
                            $point_b = $map[$current_tip_index]->devicePosition->point_b;
                            $position_type = $map[$current_tip_index]->devicePosition->position->type;
                            $calibrate = $this->calibration($project->device,$device_calibrate,$position_type,$point_a,$point_b,$calibrate_a,$calibrate_b,$rack_a,$rack_b,$rack_c,$row,$col );
                            $tip_offset = $map[$current_tip_index]->entity->offset;
                            $offset_x = $sampler_offset_x - $standard_sampler_offset_x;
                            $offset_y = $sampler_offset_y - $standard_sampler_offset_y;
                            $offset_z = $sampler_offset_z - $standard_sampler_offset_z;
                            $final_desc = $this->final_destination($map[$current_tip_index],$calibrate[0],$current_tip_row,$calibrate[4],$current_tip_col,$calibrate[3],0,0,0,$offset_x,$offset_y,$offset_z,$tip_offset);
                            if(!$trash_flag){
                                $safe = 0;
                                if($safe_zone-$height_tip > 0){
                                    $safe = $safe_zone-$height_tip;
                                }
                                $pippet .= " ; get tip \n G01 Y".$final_desc[1]." F".$speed_y." \n G04 P100 \n G01 X". $final_desc[0] ." F".$speed_x." \n G04 P100 \n G01 Z".($final_desc[2] - $tip_offset)." F".$speed_z." \n G04 P300 \n G01 Z".($final_desc[2])." F".$speed_z." \n G01 Z".($safe)." F".$speed_z." \n G04 P100 \n";
                                $this->getTime($final_desc[0],$final_desc[1],$final_desc[2],-1 ,-1,600);
                                $this->getTime(-1,-1,$safe,-1 ,-1,200);
                                $trash_flag = 1;
                            }
                            else{
                                $safe = 0;
                                if($safe_zone-$height_tip > 0){
                                    $safe = $safe_zone-$height_tip;
                                }
                                $pippet .= $trash;
                                $this->getTime($trash_point['x'],$trash_point['y'],$trash_point['z'],-1 ,0,400);
                                $this->getTime(-1,-1,$safe_zone,-1 ,-1);
                                $pippet .= " ; get tip \n G01 Y".$final_desc[1]." F".$speed_y." \n G04 P100 \n G01 X". $final_desc[0] ." F".$speed_x." \n G04 P100 \n G01 Z".($final_desc[2] - $tip_offset)." F".$speed_z." \n G04 P300 \n G01 Z".($final_desc[2])." F".$speed_z." \n G01 Z".($safe)." F".$speed_z." \n G04 P100 \n";
                                $this->getTime($final_desc[0],$final_desc[1],$final_desc[2],-1 ,-1,600);
                                $this->getTime(-1,-1,$safe,-1 ,-1,200);
                            }
                            $current_tip_col++;
                            if($current_tip_col > $col ){
                                $current_tip_row++;
                                $current_tip_col = 1;
                                if($current_tip_row > $row ){
                                    $current_tip_index++;
                                    $current_tip_row = 1;
                                }
                            }
                            $target_a = json_decode($protocol->target->entity->point_a);
                            $target_b = json_decode($protocol->target->entity->point_b);
                            $target_c = json_decode($protocol->target->entity->point_c);
                            $target_row = $protocol->target->entity->row;
                            $target_col = $protocol->target->entity->col;
                            $target_tube_volume = $protocol->target->entity->tube_volume;
                            $target_height_tube = $protocol->target->entity->height_tube;
                            $point_a = $protocol->target->devicePosition->point_a;
                            $point_b = $protocol->target->devicePosition->point_b;
                            $position_type = $protocol->target->devicePosition->position->type;
                            $target_calibrate = $this->calibration($project->device,$device_calibrate,$position_type,$point_a,$point_b,$point_a,$point_b,$target_a,$target_b,$target_c,$target_row,$target_col);
                            $target_selecteds = json_decode($protocol->target_selected);
                            $target_count = count($target_selecteds);
                            foreach( $target_selecteds as $target_selected ){
                                if($tip_change_flag){
                                    $pippet .= $trash;
                                    $this->getTime($trash_point['x'],$trash_point['y'],$trash_point['z'],-1 ,0,400);
                                    $this->getTime(-1,-1,$safe_zone,-1 ,-1);
                                    $row = $map[$current_tip_index]->entity->row;
                                    $col = $map[$current_tip_index]->entity->col;
                                    $point_a = $map[$current_tip_index]->devicePosition->point_a;
                                    $point_b = $map[$current_tip_index]->devicePosition->point_b;
                                    $position_type = $map[$current_tip_index]->devicePosition->position->type;
                                    $calibrate = $this->calibration($project->device,$device_calibrate,$position_type,$point_a,$point_b,$calibrate_a,$calibrate_b,$rack_a,$rack_b,$rack_c,$row,$col );
                                    $tip_offset = $map[$current_tip_index]->entity->offset;
                                    $offset_x = $sampler_offset_x - $standard_sampler_offset_x;
                                    $offset_y = $sampler_offset_y - $standard_sampler_offset_y;
                                    $offset_z = $sampler_offset_z - $standard_sampler_offset_z;
                                    $final_desc = $this->final_destination($map[$current_tip_index],$calibrate[0],$current_tip_row,$calibrate[4],$current_tip_col,$calibrate[3],0,0,0,$offset_x,$offset_y,$offset_z,$tip_offset);

                                    $pippet .= " ; get another tip \n G01 X". $final_desc[0] ." F".$speed_x." \n G04 P100 \n G01 Y".$final_desc[1]." F".$speed_y." \n G04 P100 \n G01 Z".($final_desc[2] - $tip_offset)." F".$speed_z." \n G04 P300 \n G01 Z".($final_desc[2])." F".$speed_z." \n G01 Z".($safe_zone)." F".$speed_z." \n G04 P100 \n";
                                    $this->getTime($final_desc[0],$final_desc[1],$final_desc[2],-1 ,-1,600);
                                    $this->getTime(-1,-1,$safe,-1 ,-1,200);
                                    $current_tip_col++;
                                    if($current_tip_col > $col ){
                                        $current_tip_row++;
                                        $current_tip_col = 1;
                                        if($current_tip_row > $row ){
                                            $current_tip_index++;
                                            $current_tip_row = 1;
                                        }
                                    }
                                }
                                $offset_x = $sampler_offset_x - $standard_sampler_offset_x;
                                $offset_y = $sampler_offset_y - $standard_sampler_offset_y;
                                $offset_z = $sampler_offset_z - $standard_sampler_offset_z;
                                $source_final_desc = $this->final_destination($protocol->target,$target_calibrate[0],$target_selected->row,$target_calibrate[4],$target_selected->col,$target_calibrate[3],$target_height_tube,$target_tube_volume,$protocol->pipet_volume,$offset_x,$offset_y,$offset_z,0);
                                $pippet .= " ; get liquid from source \n G01 X". $source_final_desc[0] ." F".$speed_x." \n G04 P100 \n G01 Y".$source_final_desc[1]." F".$speed_y." \n G04 P100 \n G01 U".$first_position."  F".$speed_z." \n G01 Z".($source_final_desc[2]-$height_tip)." F".$speed_z." \n G04 P100 \n ";
                                $this->getTime($source_final_desc[0],$source_final_desc[1],($source_final_desc[2]-$height_tip),-1 ,$first_position,300);
                                for($j=0;$j<$protocol->pipet_num ; $j++){
                                    $pippet .= "; pippeting \n G01 U".($first_position-($sampler_1microliter * $protocol->pipet_volume))."  F".$speed_z." \n G04 P100 \n G01 U".$second_position."  F".($speed_z+2000)." \n G04 P100 \n";
                                    $this->getTime(-1,-1,-1,-1 ,($first_position-($sampler_1microliter * $protocol->pipet_volume)),100);
                                    $this->getTime(-1,-1,-1,-1 ,$second_position,100);
                                }
                                $safe = 0;
                                if($safe_zone-$height_tip > 0){
                                    $safe = $safe_zone-$height_tip;
                                }
                                $pippet .= "; safezone  G01 Z".($safe)." F".$speed_z." \n G04 P100 \n ";
                                $this->getTime(-1,-1,$safe,-1 ,-1,100);
                                $tip_change_flag = 1;

                        }
                        }else{
                            return ['status'=>'0' , 'message'=>'مپ وجود ندارد'];
                        }


                        $gcode .= $pippet;
                    }

                    // gcode of pause protocol
                    elseif($protocol->entity->position_type_match == 2){
                        $pause = ";pause \n G04 S".$protocol->second." \n ";
                        $this->getTime(-1,-1,-1,-1 ,-1,($protocol->second*1000));
                        $gcode .= $pause;
                    }

                    // gcode of magnet protocol
                    elseif($protocol->entity->position_type_match == 3){
                        if($protocol->magnet_engage){
                            $magnet = ";magnet \n M42 P50 S255 \n G28 W \n G01 W".$protocol->magnet_height."  F".$speed_z." \n G04 S".$protocol->second." \n G28 W \n M42 P50 S0 \n";
                        }
                        else{
                            $magnet = ";magnet \n M42 P50 S255 \n G28 W \n G01 W0  F".$speed_z." \n G04 S".$protocol->second." \n G28 W \n M42 P50 S0 \n";
                        }
                        $this->getTime(-1,-1,-1,-1 ,-1,($protocol->second*1000));
                        $gcode .= $magnet;
                    }

                    // gcode of termoshaker protocol
                    elseif($protocol->entity->position_type_match == 4){
                        $shaker = ";shaker \n M400 \n  M118 Pn2 TT10 Temp80 Sh5 Tsh1 Sp150 \n M0 \n";

                        $gcode .= $shaker;
                    }

                    // gcode of vacuum protocol
                    elseif($protocol->entity->position_type_match == 5){
                        $vacuum = ";vacuum \n M42 P11 S255 \n  G04 S".$protocol->time." \n M42 P11 S0 \n";
                        $this->getTime(-1,-1,-1,-1 ,-1,($protocol->time*1000));
                        $gcode .= $vacuum;
                    }

                    // gcode of uvc protocol
                    elseif($protocol->entity->position_type_match == 6){
                        $uvc = ";uvc \n M42 P52 S255 \n  G04 S".$protocol->time." \n M42 P52 S0 \n";
                        $this->getTime(-1,-1,-1,-1 ,-1,($protocol->time*1000));
                        $gcode .= $uvc;
                    }
                }

                // end of gcode actions
                $sampler_out = $trash;
                $this->getTime($trash_point['x'],$trash_point['y'],$trash_point['z'],-1 ,0,400);
                $this->getTime(-1,-1,$safe_zone,-1 ,-1);
                $green_blink_light = "M150 U255 \n G04 P500 \n M150 U0 \n G04 P500 \n";
                $sampler_out .= ";last action \n G01 X60 F".$speed_x." \n G04 P100 \n G01 Y".$y." F".$speed_y." \n G04 P100 \n G01 Z".($z-10)." F".$speed_z." \n G04 P100 \n G01 X". $x ." F".$speed_x." \n G04 P100 \n";
                $sampler_out .= "G01 Z". ($z) ." F".$speed_z." \n G04 P100 \n G01 U0  F".$speed_z."\n G04 P100 \n G01 V0 F".$speed_z." \n G04 P100 \n G01 X60 F".$speed_x." \n G04 P100 \n G01 Y0 F".$speed_y." \n G04 P100 \n G01 X0 F".$speed_x." \n G04 P100 \n";
                $this->getTime(60,$y,($z-10),-1 ,-1,300);
                $this->getTime($x,-1,$z,0 ,0,400);
                $this->getTime(60,-1,-1,-1 ,-1,100);
                for($i=0; $i<9;$i++){
                    $sampler_out .= $green_blink_light;
                }
                $gcode .= $sampler_out;
                $project->gcode = $gcode;

                // estimate tips
                $current_sampler_obj = ProjectMap::find($current_sampler);
                $current_sampler_volume = $current_sampler_obj->entity->volume;
                if($current_sampler_volume == 10){
                    $current_tip_index_type_10 = $current_tip_index;
                    $current_tip_row_type_10 = $current_tip_row;
                    $current_tip_col_type_10 = $current_tip_col;
                }
                elseif($current_sampler_volume == 100){
                    $current_tip_index_type_100 = $current_tip_index;
                    $current_tip_row_type_100 = $current_tip_row;
                    $current_tip_col_type_100 = $current_tip_col;
                }
                elseif ($current_sampler_volume == 1000){
                    $current_tip_index_type_1000 = $current_tip_index;
                    $current_tip_row_type_1000 = $current_tip_row;
                    $current_tip_col_type_1000 = $current_tip_col;
                }


                if($current_tip_Dimensions_10['row']){
                    $estimate_tip_10 = ($current_tip_index_type_10*$current_tip_Dimensions_10['row']*$current_tip_Dimensions_10['col'])+(($current_tip_row_type_10-1)*$current_tip_Dimensions_10['col'])+$current_tip_col_type_10;
                }
                if($current_tip_Dimensions_100['row']){
                    $estimate_tip_100 = ($current_tip_index_type_100*$current_tip_Dimensions_100['row']*$current_tip_Dimensions_100['col'])+(($current_tip_row_type_100-1)*$current_tip_Dimensions_100['col'])+$current_tip_col_type_100;
                }
                if($current_tip_Dimensions_1000['row']){
                    $estimate_tip_1000 = ($current_tip_index_type_1000*$current_tip_Dimensions_1000['row']*$current_tip_Dimensions_1000['col'])+(($current_tip_row_type_1000-1)*$current_tip_Dimensions_1000['col'])+$current_tip_col_type_1000;
                }


                $project->estimate_tip_10 = $estimate_tip_10;
                $project->estimate_tip_100 = $estimate_tip_100;
                $project->estimate_tip_1000 = $estimate_tip_1000;
                $project->estimate_time = $this->estimate_time;

                $project->save();

                return (['status'=> '1' , "message" =>"جی کد با موفقیت ایجاد شد"  , 'estimate_array' => [ $estimate_tip_10 , $estimate_tip_100 , $estimate_tip_1000 , $this->estimate_time ]] );
                // return $project->gcode;
            }else{
                return ( ['status'=> '0' , "message" =>"جایگاه یا پروسه ای تعریف نشده است."] );
            }
        }else{
            return ( ['status'=> '0' , "message" =>"پروژه وجود ندارد"] );
        }
    }

    public function downloadGcode($id){
        $project = Project::find($id);
        $gcodeFile = 'project_'. $id . '_gcode.gcode';

        File::put(public_path("gcode/".$gcodeFile), $project->gcode);

        return Response::download(public_path("gcode/".$gcodeFile));
    }


    public function calibration($device,$device_calibrate,$position_type,$point_a,$point_b,$calibrate_a,$calibrate_b,$a,$b,$c,$row,$col){
        if($device->id == $device_calibrate->id ){
            $calibrate_x = $point_a['x'] - $calibrate_a['x'];
            $calibrate_y = $point_a['y'] - $calibrate_a['y'];
            $calibrate_z = $point_a['z'] - $calibrate_a['z'];
        }
        else{
            if($position_type==0){
                $tip_standard_calibrate = DevicePosition::where('device_id','=',$device_calibrate->id)->meta()->where(function($query) {
                    $query->where('device_positions_meta.key', '=', 'standard')->where('device_positions_meta.value', '=', 1);
                })->whereHas('position', function ($query)  {
                    $query->where('type', '=', 0)->orderBy('id');
                })->first();
                $standard_calibrate_a = $tip_standard_calibrate->point_a;
                $standard_calibrate_b = $tip_standard_calibrate->point_b;
                $calibrate_x = $point_a['x'] - $calibrate_a['x'];
                $calibrate_y = $point_a['y'] - $calibrate_a['y'];
                $calibrate_z = $point_a['z'] - $calibrate_a['z'];
                $calibrate_x += ($standard_calibrate_a['x'] - $calibrate_a['x']);
                $calibrate_y += ($standard_calibrate_a['y'] - $calibrate_a['y']);
                $calibrate_z += ($standard_calibrate_a['z'] - $calibrate_a['z']);
            }
            else{
                $tip_standard_calibrate = DevicePosition::where('device_id','=',$device_calibrate->id)->whereHas('position', function ($query) use($position_type) {
                    $query->where('type', '=', $position_type)->orderBy('id');
                })->first();
                $standard_calibrate_a = $tip_standard_calibrate->point_a;
                $standard_calibrate_b = $tip_standard_calibrate->point_b;
                $calibrate_x = $point_a['x'] - $standard_calibrate_a['x'];
                $calibrate_y = $point_a['y'] - $standard_calibrate_a['y'];
                $calibrate_z = $point_a['z'] - $standard_calibrate_a['z'];
            }
        }
        $a->x += $calibrate_x;
        $a->y += $calibrate_y;
        $a->z += $calibrate_z;
        $b->x += $calibrate_x;
        $b->y += $calibrate_y;
        $b->z += $calibrate_z;
        $c->x += $calibrate_x;
        $c->y += $calibrate_x;
        $c->z += $calibrate_z;
        $diff_col['x'] = round(($b->x - $a->x)/($col-1),3);
        $diff_col['y'] = round(($b->y - $a->y)/($col-1),3);
        $diff_col['z'] = round(($b->z - $a->z)/($col-1),3);
        $diff_row['x'] = round(($c->x - $b->x)/($row-1),3);
        $diff_row['y'] = round(($c->y - $b->y)/($row-1),3);
        $diff_row['z'] = round(($c->z - $b->z)/($row-1),3);
        return [ $a , $b , $c ,$diff_col ,$diff_row];
    }

    public function final_destination($map_calibrate,$entity_calibrate,$row,$diff_row,$col,$diff_col,$height_entity,$volume_entity,$volume,$offset_x, $offset_y,$offset_z,$tip_offset){
        if($map_calibrate->x){
            $x =  $map_calibrate->x +( ($row-1) * $diff_row['x']) + ( ($col-1) * $diff_col['x']) + $offset_x;
//            $x =  $map_calibrate->x +( ($row-1) * $diff_row['x']) + ( ($col-1) * $diff_col['x']) + ( $col_interval * ($col-1));
        }
        else{
            $x = $entity_calibrate->x + ( ($row-1) * $diff_row['x']) + ( ($col-1) * $diff_col['x']) + $offset_x;
//            $x = $entity_calibrate->x + ( ($row-1) * $diff_row['x']) + ( ($col-1) * $diff_col['x']) + ( $col_interval * ($col-1));
        }
        if($map_calibrate->y){
//            $y =  $map_calibrate->y + ( $row_interval * ($row-1)) + ( ($row-1) * $diff_row['y']) + ( ($col-1) * $diff_col['y']);
            $y =  $map_calibrate->y + ( ($row-1) * $diff_row['y']) + ( ($col-1) * $diff_col['y'])+ $offset_y;
        }
        else{
            $y = $entity_calibrate->y + ( ($row-1) * $diff_row['y']) + ( ($col-1) * $diff_col['y']) + $offset_y ;
//            $y = $entity_calibrate->y + ( $row_interval * ($row-1)) + ( ($row-1) * $diff_row['y']) + ( ($col-1) * $diff_col['y']) ;
        }
        if($volume_entity && $volume){
            $height = round(($height_entity * $volume / $volume_entity),3);
            $height = $height_entity - $height;
        }
        else{
            $height = $height_entity;
        }
        if($map_calibrate->z){
            $z =  $map_calibrate->z + ( ($row-1) * $diff_row['z']) + ( ($col-1) * $diff_col['z']) + $height + $offset_z + $tip_offset;
        }
        else{
            $z = $entity_calibrate->z + ( ($row-1) * $diff_row['z']) + ( ($col-1) * $diff_col['z']) + $height + $offset_z + $tip_offset;
        }
        return [$x ,$y ,$z];
    }

    public function getRackDimensions($project,$volume){
        $map = $project->projectMaps()->with('devicePosition')->whereHas('devicePosition.position', function ($query)  {
            $query->where('type', '=', 0)->orderBy('id');
        })->with('entity')->whereHas('entity', function ($query) use ($volume) {
            $query->meta()->where(function($query) use ($volume)  {
                $query->where('entities_meta.key', '=', 'tip_volume')->where('entities_meta.value', '=', $volume);
            });
        })->first();
        if($map){
            $row = $map->entity->row;
            $col = $map->entity->col;
        }
        else{
            $row = 0;
            $col = 0;
        }
        return ['row' => $row , 'col' =>  $col];
    }

    public function getTime($x = -1 , $y = -1 , $z = -1 , $v = -1 , $u = -1 , $p = 0  ){
        if($x >= 0){
            $diff = abs($this->current_direction['x']-$x);
            $this->current_direction['x'] = $x;
            $this->estimate_time += $diff / $this->speed['x'];
        }
        if($y >= 0){
            $diff = abs($this->current_direction['y']-$y);
            $this->current_direction['y'] = $y;
            $this->estimate_time += $diff / $this->speed['y'];
        }
        if($z >= 0){
            $diff = abs($this->current_direction['z']-$z);
            $this->current_direction['z'] = $z;
            $this->estimate_time += $diff / $this->speed['z'];
        }
        if($u >= 0){
            $diff = abs($this->current_direction['u']-$u);
            $this->current_direction['u'] = $u;
            $this->estimate_time += $diff / $this->speed['z'];
        }
        if($v >= 0){
            $diff = abs($this->current_direction['v']-$v);
            $this->current_direction['v'] = $v;
            $this->estimate_time += $diff / $this->speed['z'];
        }
        if($p > 0){
            $this->estimate_time += ($p/1000);
        }
    }


}
