<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Entity;
use App\Models\ProjectMap;
use App\Models\ProjectProtocol;
use Illuminate\Http\Request;

class ProjectProtocolController extends Controller
{

    public function addProtocol(Request $request){

        $project = Project::find($request->project_id);
        if($project){
            $protocol = Entity::find($request->entity_id);
            if( $protocol->position_type_match == 0 ){
                $projectProtocol = new ProjectProtocol();
                $source = ProjectMap::find($request->source_id);
                $target = ProjectMap::find($request->target_id);
                if($source && $target){
                    $source_selected = json_decode($request->source_selected);
                    $target_selected = json_decode($request->target_selected);
                    $source_liquids = json_decode($source->liquids);
                    $volume_flag = 1;
                   
//                    return isset($request->loop);
                    if( (((count($source_selected) * $request->source_volume) == (count($target_selected) * $request->target_volume)) || isset($request->loop)) && $volume_flag){
                        $projectProtocol->project()->associate($project);
                        $projectProtocol->entity()->associate($protocol);
                        $projectProtocol->source()->associate($source);
                        $projectProtocol->target()->associate($target);
                        $projectProtocol->sequence = $request->sequence;
                        $projectProtocol->save();
                        $projectProtocol->sampler_id = $request->sampler_id;
                        $projectProtocol->source_selected = $request->source_selected;
                        $projectProtocol->target_selected = $request->target_selected;
                        $projectProtocol->source_volume = $request->source_volume;
                        $projectProtocol->target_volume = $request->target_volume;
                        if(isset($request->tip_change)){
                            $projectProtocol->tip_change = 1;
                        }
                        else{
                            $projectProtocol->tip_change = 0;
                        }
                        if(isset($request->loop)){
                            $projectProtocol->loop = 1;
                        }
                        else{
                            $projectProtocol->loop = 0;
                        }
                        $source->liquids = json_encode($source_liquids);;
                        $source->save();
                        $projectProtocol->save();
                        return ['status' => 1 , 'protocol' => $projectProtocol];
                    }
                    else{
                        return ['status' => 0 , 'error' => 'حجم نمونه ها در مبدا با حجم نمونه ها در مقصد مساوی نمی باشد یا حجم انتخابی در مبدا بیشتر از حجم نمونه ها است.' ];
                    }


                }
                else{
                    return ['status' => 0 , 'error' => 'مبدا و مقصد به درستی انتخاب نشده است.'];
                }
            }
            elseif( $protocol->position_type_match == 1 ){
                $projectProtocol = new ProjectProtocol();
                $target = ProjectMap::find($request->target_id);
                if( $target){
                    $target_selected = json_decode($request->target_selected);
                    $volume_flag = 1;
                    if($volume_flag){
                        $projectProtocol->project()->associate($project);
                        $projectProtocol->entity()->associate($protocol);
                        $projectProtocol->target()->associate($target);
                        $projectProtocol->sequence = $request->sequence;
                        $projectProtocol->save();
                        $projectProtocol->sampler_id = $request->sampler_id;
                        $projectProtocol->source_selected = $request->source_selected;
                        $projectProtocol->target_selected = $request->target_selected;
                        $projectProtocol->pipet_num = $request->pipet_num;
                        $projectProtocol->pipet_volume = $request->pipet_volume;
                        $projectProtocol->save();
                        return ['status' => 1 , 'protocol' => $projectProtocol];
                    }
                    else{
                        return ['status' => 0 , 'error' => 'حجم انتخابی در مبدا بیشتر از حجم نمونه ها است.' ];
                    }


                }
                else{
                    return ['status' => 0 , 'error' => 'مبدا و مقصد به درستی انتخاب نشده است.'];
                }
            }
            elseif( $protocol->position_type_match == 2 ){
                $projectProtocol = new ProjectProtocol();
                $projectProtocol->project()->associate($project);
                $projectProtocol->entity()->associate($protocol);
                $projectProtocol->sequence = $request->sequence;
                $projectProtocol->save();
                $projectProtocol->second = $request->second;
                $projectProtocol->save();
                return ['status' => 1 , 'protocol' => $projectProtocol];
            }
            elseif( $protocol->position_type_match == 3 ){
                $projectProtocol = new ProjectProtocol();
                $source = 1;
                if($source){
                    $volume_flag = 1;
                    if( $volume_flag){
                        $projectProtocol->project()->associate($project);
                        $projectProtocol->entity()->associate($protocol);
                        $projectProtocol->sequence = $request->sequence;
                        $projectProtocol->save();
                        $projectProtocol->tube_volume = $request->tube_volume;
                        $projectProtocol->magnet_height = $request->magnet_height;
                        $projectProtocol->second = $request->second;
                        if($request->magnet_engage){
                            $projectProtocol->magnet_engage = 1;
                        }
                        else{
                            $projectProtocol->magnet_engage = 0;
                        }
                        $projectProtocol->save();
                        return ['status' => 1 , 'protocol' => $projectProtocol];
                    }
                    else{
                        return ['status' => 0 , 'error' => 'حجم نمونه ها در مبدا با حجم نمونه ها در مقصد مساوی نمی باشد یا حجم انتخابی در مبدا بیشتر از حجم نمونه ها است.' ];
                    }


                }
                else{
                    return ['status' => 0 , 'error' => 'مبدا و مقصد به درستی انتخاب نشده است.'];
                }
            }
            elseif( $protocol->position_type_match == 4 ){
                $projectProtocol = new ProjectProtocol();
                $target = 1;
                if($target){
                    $volume_flag = 1;
                    if( $volume_flag){
                        $projectProtocol->project()->associate($project);
                        $projectProtocol->entity()->associate($protocol);
                        $projectProtocol->sequence = $request->sequence;
                        $projectProtocol->save();
                        $projectProtocol->type = $request->type;
                        $projectProtocol->mixer_repeat = $request->mixer_repeat;
                        $projectProtocol->mixer_time = $request->mixer_time;
                        $projectProtocol->warmer_time = $request->warmer_time;
                        $projectProtocol->warmer_temperature = $request->warmer_temperature;
                        $projectProtocol->save();
                        return ['status' => 1 , 'protocol' => $projectProtocol];
                    }
                    else{
                        return ['status' => 0 , 'error' => 'حجم نمونه ها در مبدا با حجم نمونه ها در مقصد مساوی نمی باشد یا حجم انتخابی در مبدا بیشتر از حجم نمونه ها است.' ];
                    }


                }
                else{
                    return ['status' => 0 , 'error' => 'مبدا و مقصد به درستی انتخاب نشده است.'];
                }
            }
            elseif( $protocol->position_type_match == 5 ){
                $projectProtocol = new ProjectProtocol();
                $target = 1;
                if($target){
                    $volume_flag = 1;
                    if( $volume_flag){
                        $projectProtocol->project()->associate($project);
                        $projectProtocol->entity()->associate($protocol);
                        $projectProtocol->sequence = $request->sequence;
                        $projectProtocol->save();
                        $projectProtocol->time = $request->time;
                        $projectProtocol->save();
                        return ['status' => 1 , 'protocol' => $projectProtocol];
                    }
                    else{
                        return ['status' => 0 , 'error' => 'حجم نمونه ها در مبدا با حجم نمونه ها در مقصد مساوی نمی باشد یا حجم انتخابی در مبدا بیشتر از حجم نمونه ها است.' ];
                    }


                }
                else{
                    return ['status' => 0 , 'error' => 'مبدا و مقصد به درستی انتخاب نشده است.'];
                }
            }
            elseif( $protocol->position_type_match == 6 ){
                $projectProtocol = new ProjectProtocol();
                $volume_flag = 1;
                if( $volume_flag){
                    $projectProtocol->project()->associate($project);
                    $projectProtocol->entity()->associate($protocol);
                    $projectProtocol->sequence = $request->sequence;
                    $projectProtocol->save();
                    $projectProtocol->time = $request->time;
                    $projectProtocol->save();
                    return ['status' => 1 , 'protocol' => $projectProtocol];
                }
                else{
                    return ['status' => 0 , 'error' => 'حجم نمونه ها در مبدا با حجم نمونه ها در مقصد مساوی نمی باشد یا حجم انتخابی در مبدا بیشتر از حجم نمونه ها است.' ];
                }

            }
            else{
                return ['status' => 0 ];
            }
        }
        else{
            return ['status' => 0 ];
        }
    }


    public function editProtocolShow($id){
        $project_protocol = ProjectProtocol::find($id);
        $project_protocol->entity;
        if($project_protocol){
            return $project_protocol;
        }
    }


    public function editProtocol(Request $request){

        $projectProtocol = ProjectProtocol::find($request->project_protocol_id);
        if($projectProtocol){
            $project = Project::find($request->project_id);
            if($project){
                $protocol = Entity::find($request->entity_id);
                if( $protocol->position_type_match == 0 ){
                    $source = ProjectMap::find($request->source_id);
                    $target = ProjectMap::find($request->target_id);
                    if($source && $target){
                        $db_selected = json_decode($projectProtocol->source_selected);
                        $source_selected = json_decode($request->source_selected);
                        $target_selected = json_decode($request->target_selected);
                        $source_liquids = json_decode($source->liquids);
                        $volume_flag = 1;
                        
                        if( (((count($source_selected) * $request->source_volume) == (count($target_selected) * $request->target_volume)) || isset($request->loop)) && $volume_flag){
                            $projectProtocol->project()->associate($project);
                            $projectProtocol->entity()->associate($protocol);
                            $projectProtocol->source()->associate($source);
                            $projectProtocol->target()->associate($target);
                            $projectProtocol->sequence = $request->sequence;
                            $projectProtocol->save();
                            $projectProtocol->sampler_id = $request->sampler_id;
                            $projectProtocol->source_selected = $request->source_selected;
                            $projectProtocol->target_selected = $request->target_selected;
                            $projectProtocol->source_volume = $request->source_volume;
                            $projectProtocol->target_volume = $request->target_volume;
                            $projectProtocol->loop = $request->loop;
                            if($request->tip_change){
                                $projectProtocol->tip_change = 1;
                            }
                            else{
                                $projectProtocol->tip_change = 0;
                            }
                            if($request->loop){
                                $projectProtocol->loop = 1;
                            }
                            else{
                                $projectProtocol->loop = 0;
                            }
                            $source->liquids = json_encode($source_liquids);;
                            $source->save();
                            $projectProtocol->save();
                            return ['status' => 1 , 'protocol' => $projectProtocol];
                        }
                        else{
                            return ['status' => 0 , 'error' => 'حجم نمونه ها در مبدا با حجم نمونه ها در مقصد مساوی نمی باشد یا حجم انتخابی در مبدا بیشتر از حجم نمونه ها است.' ];
                        }


                    }
                    else{
                        return ['status' => 0 , 'error' => 'مبدا و مقصد به درستی انتخاب نشده است.'];
                    }
                }
                elseif( $protocol->position_type_match == 1 ){
                    $target = ProjectMap::find($request->target_id);
                    if($target){
                        $db_selected = json_decode($projectProtocol->source_selected);
                        $volume_flag = 1;
                        if($volume_flag){
                            $projectProtocol->project()->associate($project);
                            $projectProtocol->entity()->associate($protocol);
                            $projectProtocol->target()->associate($target);
                            $projectProtocol->sequence = $request->sequence;
                            $projectProtocol->save();
                            $projectProtocol->sampler_id = $request->sampler_id;
                            $projectProtocol->target_selected = $request->target_selected;
                            $projectProtocol->target_volume = $request->target_volume;
                            $projectProtocol->pipet_num = $request->pipet_num;
                            $projectProtocol->pipet_volume = $request->pipet_volume;
                            $projectProtocol->save();
                            return ['status' => 1 , 'protocol' => $projectProtocol];
                        }
                        else{
                            return ['status' => 0 , 'error' => 'حجم انتخابی در مبدا بیشتر از حجم نمونه ها است.' ];
                        }


                    }
                    else{
                        return ['status' => 0 , 'error' => 'مبدا و مقصد به درستی انتخاب نشده است.'];
                    }
                }
                elseif( $protocol->position_type_match == 2 ){
                    $projectProtocol->project()->associate($project);
                    $projectProtocol->entity()->associate($protocol);
                    $projectProtocol->sequence = $request->sequence;
                    $projectProtocol->second = $request->second;
                    $projectProtocol->save();
                    return ['status' => 1 , 'protocol' => $projectProtocol];
                }
                elseif( $protocol->position_type_match == 3 ){
                    $source = ProjectMap::find($request->source_id);
                    if($source){
                        $volume_flag = 1;
                        if($volume_flag){
                            $projectProtocol->project()->associate($project);
                            $projectProtocol->entity()->associate($protocol);
                            $projectProtocol->source()->associate($source);
                            $projectProtocol->sequence = $request->sequence;
                            $projectProtocol->save();
                            $projectProtocol->source_selected = $request->source_selected;
                            $projectProtocol->tube_volume = $request->tube_volume;
                            $projectProtocol->magnet_height = $request->magnet_height;
                            $projectProtocol->second = $request->second;
                            if($request->magnet_engage){
                                $projectProtocol->magnet_engage = 1;
                            }
                            else{
                                $projectProtocol->magnet_engage = 0;
                            }
                            $source->save();
                            $projectProtocol->save();
                            return ['status' => 1 , 'protocol' => $projectProtocol];
                        }
                        else{
                            return ['status' => 0 , 'error' => 'حجم نمونه ها در مبدا با حجم نمونه ها در مقصد مساوی نمی باشد یا حجم انتخابی در مبدا بیشتر از حجم نمونه ها است.' ];
                        }


                    }
                    else{
                        return ['status' => 0 , 'error' => 'مبدا و مقصد به درستی انتخاب نشده است.'];
                    }
                }
                elseif( $protocol->position_type_match == 4 ){
                    $target = ProjectMap::find($request->target_id);
                    if($target){
                        $volume_flag = 1;
                        if( $volume_flag){
                            $projectProtocol->project()->associate($project);
                            $projectProtocol->entity()->associate($protocol);
                            $projectProtocol->target()->associate($target);
                            $projectProtocol->sequence = $request->sequence;
                            $projectProtocol->save();
                            $projectProtocol->target_selected = $request->target_selected;
                            $projectProtocol->type = $request->type;
                            $projectProtocol->mixer_repeat = $request->mixer_repeat;
                            $projectProtocol->mixer_time = $request->mixer_time;
                            $projectProtocol->warmer_time = $request->warmer_time;
                            $projectProtocol->warmer_temperature = $request->warmer_temperature;
                            $projectProtocol->save();
                            return ['status' => 1 , 'protocol' => $projectProtocol];
                        }
                        else{
                            return ['status' => 0 , 'error' => 'حجم نمونه ها در مبدا با حجم نمونه ها در مقصد مساوی نمی باشد یا حجم انتخابی در مبدا بیشتر از حجم نمونه ها است.' ];
                        }


                    }
                    else{
                        return ['status' => 0 , 'error' => 'مبدا و مقصد به درستی انتخاب نشده است.'];
                    }
                }
                elseif( $protocol->position_type_match == 5 ){
                    $target = ProjectMap::find($request->target_id);
                    if($target){
                        $volume_flag = 1;
                        if( $volume_flag){
                            $projectProtocol->project()->associate($project);
                            $projectProtocol->entity()->associate($protocol);
                            $projectProtocol->target()->associate($target);
                            $projectProtocol->sequence = $request->sequence;
                            $projectProtocol->save();
                            $projectProtocol->target_selected = $request->target_selected;
                            $projectProtocol->time = $request->time;
                            $projectProtocol->save();
                            return ['status' => 1 , 'protocol' => $projectProtocol];
                        }
                        else{
                            return ['status' => 0 , 'error' => 'حجم نمونه ها در مبدا با حجم نمونه ها در مقصد مساوی نمی باشد یا حجم انتخابی در مبدا بیشتر از حجم نمونه ها است.' ];
                        }


                    }
                    else{
                        return ['status' => 0 , 'error' => 'مبدا و مقصد به درستی انتخاب نشده است.'];
                    }
                }
                elseif( $protocol->position_type_match == 6 ){
                        $volume_flag = 1;
                        if( $volume_flag){
                            $projectProtocol->project()->associate($project);
                            $projectProtocol->entity()->associate($protocol);
                            $projectProtocol->sequence = $request->sequence;
                            $projectProtocol->save();
                            $projectProtocol->time = $request->time;
                            $projectProtocol->save();
                            return ['status' => 1 , 'protocol' => $projectProtocol];
                        }
                        else{
                            return ['status' => 0 , 'error' => 'حجم نمونه ها در مبدا با حجم نمونه ها در مقصد مساوی نمی باشد یا حجم انتخابی در مبدا بیشتر از حجم نمونه ها است.' ];
                        }

                }
                else{
                    return ['status' => 0 ];
                }
            }
            else{
                return ['status' => 0 ];
            }
        }
    }


    public function removeProtocol($id){
        $projectProtocol = ProjectProtocol::find($id);
        if($projectProtocol->entity->position_type_match == 0 || $projectProtocol->entity->position_type_match == 1 ){
            $source_selected = json_decode($projectProtocol->source_selected);
            $liquids = json_decode($projectProtocol->source->liquids)   ;
            foreach ($source_selected as $source_liquid){
                foreach ($liquids as $liquid){
                    if(($source_liquid->col == $liquid->col) && ($source_liquid->row == $liquid->row)){
                        $liquid->volume += $projectProtocol->source_volume;
                    }
                }
            }
            $projectProtocol->source->save();
        }

        $projectProtocol->delete();
        return $projectProtocol;
    }

    public function changeSequence(Request $request){
        foreach($request->sequences as $sequence){
            $projectProtocol = ProjectProtocol::find($sequence['id']);
            if($projectProtocol){
                $projectProtocol->sequence = $sequence['index'];
                $projectProtocol->save();
            }
        }
        return $request->sequences;
    }


}
