<?php

namespace App\Http\Controllers;

use App\Models\Entity;
use App\Models\Option;
use Illuminate\Http\Request;

class EntityController extends Controller
{
    public function Entities(){
        $option = Option::where('key' , 'entity_type')->first();
        $entity_option = array();
        $entity_option = json_decode($option['value'] , true);


        $module = Option::where('key' , 'attribute_protocol_type')->first();
        $module_option = array();
        $module_option = json_decode($module['value'] , true);



        $position = Option::where('key' , 'position_type')->first();
        $position_option = array();
        $position_option = json_decode($position['value'] , true);

        $entities = Entity::orderBy('id' , 'desc')->get();
        return view('entities.entity' , compact('entities' , 'entity_option' , 'position_option' , 'module_option'));

    }

    public function addEntityForm(Request $request){

        $option = Option::where('key' , 'entity_type')->first();
        $entity_option = array();
        $entity_option = json_decode($option['value'] , true);

        $module = Option::where('key' , 'attribute_protocol_type')->first();
        $module_option = array();
        $module_option = json_decode($module['value'] , true);

        $position = Option::where('key' , 'position_type')->first();
        $position_option = array();
        $position_option = json_decode($position['value'] , true);

        return view('entities.addEntity' , compact('position_option' , 'entity_option' , 'module_option'));
    }

    public function addEntity(Request $request){
        $entity = new Entity();
        $massage = [
            'name.required' => 'فیلد نام را وارد کنید',
            'point_a_x.required' => 'فیلد ضروری است',
            'point_a_y.required' => 'فیلد ضروری است',
            'point_a_z.required' =>  'فیلد ضروری است',

            'point_b_x.required' => 'فیلد ضروری است',
            'point_b_y.required' => 'فیلد ضروری است',
            'point_b_z.required' =>  'فیلد ضروری است',

            'point_c_x.required' => 'فیلد ضروری است',
            'point_c_y.required' => 'فیلد ضروری است',
            'point_c_z.required' =>  'فیلد ضروری است',
            'tube_volume.required' =>  'فیلد ضروری است',
            'height_tube.required' =>  'فیلد ضروری است',

            'tip_volume.required' =>  'فیلد ضروری است',
            'height_tip.required' =>  'فیلد ضروری است',
            't_a_x.required' => 'فیلد ضروری است',
            't_a_y.required' => 'فیلد ضروری است',
            't_a_z.required' =>  'فیلد ضروری است',
            't_b_x.required' => 'فیلد ضروری است',
            't_b_y.required' => 'فیلد ضروری است',
            't_b_z.required' =>  'فیلد ضروری است',
            't_c_x.required' => 'فیلد ضروری است',
            't_c_y.required' => 'فیلد ضروری است',
            't_c_z.required' =>  'فیلد ضروری است',

            'col.required' =>  'فیلد ضروری است',
            'row.required' =>  'فیلد ضروری است',
            'falcon_height.required' =>  'فیلد ضروری است',



            'rest_position.required' =>  'فیلد ضروری است',
            'first_position.required' =>  'فیلد ضروری است',
            'second_position.required' =>  'فیلد ضروری است',
            'sampler_height.required' =>  'فیلد ضروری است',
            'volume.required' =>  'فیلد ضروری است',
            'row_module.required' =>  'فیلد ضروری است',
            'col_module.required' =>  'فیلد ضروری است',
        ] ;

        $validatedData = $request->validate([
            'name' => 'required',
        ] , $massage);

        $entity->name = $request->name;
        $entity->type = $request->type;

        if($request->type == 0 || $request->type == 2){
            $validatedData = $request->validate([
                'point_a_x' => 'required',
                'point_a_y' => 'required',
                'point_a_z' => 'required',

                'point_b_x' => 'required',
                'point_b_y' => 'required',
                'point_b_z' => 'required',

                'point_c_x' => 'required',
                'point_c_y' => 'required',
                'point_c_z' => 'required',

                'tube_volume' => 'required',
                'height_tube' => 'required',

                
                'row' => 'required',
                'col' => 'required',
            ] , $massage);


            $a = array();
            $b = array();
            $c = array();

            $a['x']= $request->point_a_x;
            $a['y']= $request->point_a_y;
            $a['z']= $request->point_a_z;

            $b['x']= $request->point_b_x;
            $b['y']= $request->point_b_y;
            $b['z']= $request->point_b_z;

            $c['x']= $request->point_c_x;
            $c['y']= $request->point_c_y;
            $c['z']= $request->point_c_z;

            $entity->point_a = json_encode($a);
            $entity->point_b = json_encode($b);
            $entity->point_c = json_encode($c);
            $entity->tube_volume = $request->tube_volume;
            $entity->height_tube = $request->height_tube;

            $entity->col_interval = $request->col_interval;
            $entity->row_interval = $request->row_interval;
            $entity->height = $request->height;
            $entity->row = $request->row;
            $entity->col = $request->col;

        }


        elseif($request->type == 3){
            $validatedData = $request->validate([
                'falcon_height' => 'required',
            ] , $massage);
            $entity->falcon_height = $request->falcon_height;

        }
        elseif($request->type == 6){

            $validatedData = $request->validate([
                'tip_volume' => 'required',
                'height_tip' => 'required',
                't_a_x' => 'required',
                't_a_y' => 'required',
                't_a_z' => 'required',

                't_b_x' => 'required',
                't_b_y' => 'required',
                't_b_z' => 'required',

                't_c_x' => 'required',
                't_c_y' => 'required',
                't_c_z' => 'required',

                
                't_offset' => 'required',
                't_row' => 'required',
                't_col' => 'required',
            ] , $massage);

            $a = array();
            $b = array();
            $c = array();

            $a['x']= $request->t_a_x;
            $a['y']= $request->t_a_y;
            $a['z']= $request->t_a_z;
            $b['x']= $request->t_b_x;
            $b['y']= $request->t_b_y;
            $b['z']= $request->t_b_z;
            $c['x']= $request->t_c_x;
            $c['y']= $request->t_c_y;
            $c['z']= $request->t_c_z;

            $entity->point_a = json_encode($a);
            $entity->point_b = json_encode($b);
            $entity->point_c = json_encode($c);

            $entity->col_interval = $request->t_col_interval;
            $entity->row_interval = $request->t_row_interval;
            $entity->height = $request->t_height;
            $entity->row = $request->t_row;
            $entity->col = $request->t_col;
            $entity->offset = $request->t_offset;
            $entity->tip_volume = $request->tip_volume;
            $entity->height_tip = $request->height_tip;


        }
        elseif($request->type == 1){
            $validatedData = $request->validate([
                'rest_position' => 'required',
                'first_position' => 'required',
                'second_position' => 'required',
                'sampler_height' => 'required',
                'volume' => 'required',
            ] , $massage);

            $entity->rest_position = $request->rest_position;
            $entity->first_position = $request->first_position;
            $entity->second_position = $request->second_position;
            $entity->sampler_height = $request->sampler_height;
            $entity->volume = $request->volume;

        }elseif($request->type == 4){
            $entity->position_type_match = $request->module_type_match;
        }
        if($entity->type != 4){
            $entity->position_type_match = $request->position_type_match;
        }
        if(isset($request->description)){
            $entity->description = $request->description;
        }
        $entity->save();
        $msg = 'موجودیت با موفقیت ثبت شد';


        $entities = Entity::all();
        return redirect(route('entityInfo' , compact('entities')))->with('success' , $msg);
    }

    public function editEntity($id){

        $entity = Entity::find($id);

        $entity_type = Option::where('key' , 'entity_type')->first();
        $entity_option = array();
        $entity_option = json_decode($entity_type['value'] , true);

        $module = Option::where('key' , 'attribute_protocol_type')->first();
        $module_option = array();
        $module_option = json_decode($module['value'] , true);

        $position = Option::where('key' , 'position_type')->first();
        $position_option = array();
        $position_option = json_decode($position['value'] , true);

        
        return view('entities.editEntity' , compact('entity' , 'position_option' , 'entity_option' , 'module_option'));
    }





    public function editEntityInfo(Request $request){
        $id = $request->id;
        $entity = Entity::find($id);

        $entity->name = $request->name;
        $entity->type = $request->type;
        $massage = [
            'name.required' => 'فیلد نام را وارد کنید',
            'point_a_x.required' => 'فیلد ضروری است',
            'point_a_y.required' => 'فیلد ضروری است',
            'point_a_z.required' =>  'فیلد ضروری است',

            'point_b_x.required' => 'فیلد ضروری است',
            'point_b_y.required' => 'فیلد ضروری است',
            'point_b_z.required' =>  'فیلد ضروری است',

            'point_c_x.required' => 'فیلد ضروری است',
            'point_c_y.required' => 'فیلد ضروری است',
            'point_c_z.required' =>  'فیلد ضروری است',
            'tube_volume.required' =>  'فیلد ضروری است',
            'height_tube.required' =>  'فیلد ضروری است',

            'tip_volume.required' =>  'فیلد ضروری است',
            'height_tip.required' =>  'فیلد ضروری است',
            't_a_x.required' => 'فیلد ضروری است',
            't_a_y.required' => 'فیلد ضروری است',
            't_a_z.required' =>  'فیلد ضروری است',
            't_b_x.required' => 'فیلد ضروری است',
            't_b_y.required' => 'فیلد ضروری است',
            't_b_z.required' =>  'فیلد ضروری است',
            't_c_x.required' => 'فیلد ضروری است',
            't_c_y.required' => 'فیلد ضروری است',
            't_c_z.required' =>  'فیلد ضروری است',
            't_col.required' =>  'فیلد ضروری است',
            't_row.required' =>  'فیلد ضروری است',

            
            'col.required' =>  'فیلد ضروری است',
            'row.required' =>  'فیلد ضروری است',
            'falcon_height.required' =>  'فیلد ضروری است',
            'value.required' =>  'فیلد ضروری است',
            'volume.required' =>  'فیلد ضروری است',

            'rest_position.required' =>  'فیلد ضروری است',
            'first_position.required' =>  'فیلد ضروری است',
            'second_position.required' =>  'فیلد ضروری است',
            'sampler_height.required' =>  'فیلد ضروری است',
            'row_module.required' =>  'فیلد ضروری است',
            'col_module.required' =>  'فیلد ضروری است',
        ] ;

        if($request->type == 0 || $request->type == 2){
            $validatedData = $request->validate([
                'point_a_x' => 'required',
                'point_a_y' => 'required',
                'point_a_z' => 'required',

                'point_b_x' => 'required',
                'point_b_y' => 'required',
                'point_b_z' => 'required',

                'point_c_x' => 'required',
                'point_c_y' => 'required',
                'point_c_z' => 'required',

                'tube_volume' => 'required',
                'height_tube' => 'required',

                
                'row' => 'required',
                'col' => 'required',
            ] , $massage);

            $a = array();
            $b = array();
            $c = array();

            $a['x']= $request->point_a_x;
            $a['y']= $request->point_a_y;
            $a['z']= $request->point_a_z;

            $b['x']= $request->point_b_x;
            $b['y']= $request->point_b_y;
            $b['z']= $request->point_b_z;

            $c['x']= $request->point_c_x;
            $c['y']= $request->point_c_y;
            $c['z']= $request->point_c_z;

            $entity->point_a = json_encode($a);
            $entity->point_b = json_encode($b);
            $entity->point_c = json_encode($c);
            $entity->tube_volume = $request->tube_volume;
            $entity->height_tube = $request->height_tube;

            $entity->col_interval = $request->col_interval;
            $entity->row_interval = $request->row_interval;
            $entity->height = $request->height;
            $entity->row = $request->row;
            $entity->col = $request->col;

        }



        elseif($request->type == 6){

            $validatedData = $request->validate([
                'tip_volume' => 'required',
                'height_tip' => 'required',
                't_a_x' => 'required',
                't_a_y' => 'required',
                't_a_z' => 'required',

                't_b_x' => 'required',
                't_b_y' => 'required',
                't_b_z' => 'required',

                't_c_x' => 'required',
                't_c_y' => 'required',
                't_c_z' => 'required',

                
                't_offset' => 'required',
                't_row' => 'required',
                't_col' => 'required',
            ] , $massage);


            $a = array();
            $b = array();
            $c = array();

            $a['x']= $request->t_a_x;
            $a['y']= $request->t_a_y;
            $a['z']= $request->t_a_z;
            $b['x']= $request->t_b_x;
            $b['y']= $request->t_b_y;
            $b['z']= $request->t_b_z;
            $c['x']= $request->t_c_x;
            $c['y']= $request->t_c_y;
            $c['z']= $request->t_c_z;

            $entity->point_a = json_encode($a);
            $entity->point_b = json_encode($b);
            $entity->point_c = json_encode($c);
            $entity->tip_volume = $request->tip_volume;
            $entity->height_tip = $request->height_tip;

            $entity->col_interval = $request->t_col_interval;
            $entity->row_interval = $request->t_row_interval;
            $entity->height = $request->t_height;
            $entity->offset = $request->t_offset;
            $entity->row = $request->t_row;
            $entity->col = $request->t_col;
        }

        elseif($entity->type == 4){
            $entity->position_type_match = $request->module_type_match;
        }
        elseif($request->type == 3){
            $validatedData = $request->validate([
                'falcon_height' => 'required',
            ] , $massage);

            $entity->falcon_height = $request->falcon_height;
        }

        elseif($request->type == 1){

            $validatedData = $request->validate([
                'rest_position' => 'required',
                'first_position' => 'required',
                'second_position' => 'required',
                'sampler_height' => 'required',
                'position_type_match' => 'required',
                'second_position' => 'required',
                'volume' => 'required',
            ] , $massage);
            $entity->rest_position = $request->rest_position;
            $entity->first_position = $request->first_position;
            $entity->second_position = $request->second_position;
            $entity->sampler_height = $request->sampler_height;
            $entity->position_type_match = $request->position_type_match;
            $entity->volume = $request->volume;
        }

        if($entity->type != 4){
            $entity->position_type_match = $request->position_type_match;
        }

            $entity->description = $request->description;

        $entity->save();

        $msg = 'موجودیت با موفقیت ویرایش شد';

        return redirect(route('entityInfo' , compact('entity')))->with('success' , $msg);
    }

    public function deleteEntity($id){
        $entity = Entity::find($id);
        $msg = 'موجودیت با موفقیت حذف شد';
        $entity->delete();
        return redirect(route('entityInfo'))->with('success' , $msg);
    }
}
