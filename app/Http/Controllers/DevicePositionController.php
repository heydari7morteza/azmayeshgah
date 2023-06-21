<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Position;
use App\Models\Device;
use App\Models\Option;
use App\Models\DevicePosition;

class DevicePositionController extends Controller
{
    //
    public function devicePosition($id){
        $device = Device::find($id);
        $device_type = Option::where('key' , 'device_type')->first();
        $device_option = array();
        $device_option = json_decode($device_type['value'] , true);
        $position = Position::all();
        $devicePositions = $device->devicePositions;
        return view('device-position.devicePosition' , compact('devicePositions' , 'position' , 'device' , 'device_option'));
    }

    public function createDevicePosition($id){
        $device = Device::find($id);
        $position_option = array();
        $option = Option::where('key' , 'position_type')->first();
        $position_option = json_decode($option['value'] , true);

        $positions = Position::all();
        return view('device-position.createDevicePosition' , compact('id' , 'positions' , 'position_option'));
    }

    public function checkCreateDevicePosition(Request $request , $id){

        $devicePosition = new DevicePosition();


        $massage = [
            'point_a_x.required' => 'فیلد ضروری است',
            'point_a_y.required' => 'فیلد ضروری است',
            'point_a_z.required' =>  'فیلد ضروری است',

            'g_a_x.required' => 'فیلد ضروری است',
            'g_a_y.required' => 'فیلد ضروری است',
            'g_a_z.required' =>  'فیلد ضروری است',

            'point_b_x.required' => 'فیلد ضروری است',
            'point_b_y.required' => 'فیلد ضروری است',
            'point_b_z.required' =>  'فیلد ضروری است',

            'width.required' => 'فیلد ضروری است',
            'length.required' => 'فیلد ضروری است',
            'gripper_value.required' =>  'فیلد ضروری است',

            'p_a_x.required' => 'فیلد ضروری است',
            'p_a_y.required' => 'فیلد ضروری است',
            'p_a_z.required' =>  'فیلد ضروری است',

            'p_b_x.required' => 'فیلد ضروری است',
            'p_b_y.required' => 'فیلد ضروری است',
            'p_b_z.required' =>  'فیلد ضروری است',

            'p_c_x.required' => 'فیلد ضروری است',
            'p_c_y.required' => 'فیلد ضروری است',
            'p_c_z.required' =>  'فیلد ضروری است',

            'p_d_x.required' => 'فیلد ضروری است',
            'p_d_y.required' => 'فیلد ضروری است',
            'p_d_z.required' =>  'فیلد ضروری است',

            'p_e_x.required' => 'فیلد ضروری است',
            'p_e_y.required' => 'فیلد ضروری است',
            'p_e_z.required' =>  'فیلد ضروری است',

            'p_f_x.required' => 'فیلد ضروری است',
            'p_f_y.required' => 'فیلد ضروری است',
            'p_f_z.required' =>  'فیلد ضروری است',

            'p_g_x.required' => 'فیلد ضروری است',
            'p_g_y.required' => 'فیلد ضروری است',
            'p_g_z.required' =>  'فیلد ضروری است',

            'p_h_x.required' => 'فیلد ضروری است',
            'p_h_y.required' => 'فیلد ضروری است',
            'p_h_z.required' =>  'فیلد ضروری است',

            'p_j_x.required' => 'فیلد ضروری است',
            'p_j_y.required' => 'فیلد ضروری است',
            'p_j_z.required' =>  'فیلد ضروری است',

            'p_i_x.required' => 'فیلد ضروری است',
            'p_i_y.required' => 'فیلد ضروری است',
            'p_i_z.required' =>  'فیلد ضروری است',

            'p_k_x.required' => 'فیلد ضروری است',
            'p_k_y.required' => 'فیلد ضروری است',
            'p_k_z.required' =>  'فیلد ضروری است',

            'p_l_x.required' => 'فیلد ضروری است',
            'p_l_y.required' => 'فیلد ضروری است',
            'p_l_z.required' =>  'فیلد ضروری است',

            'p_m_x.required' => 'فیلد ضروری است',
            'p_m_y.required' => 'فیلد ضروری است',
            'p_m_z.required' =>  'فیلد ضروری است',

            'offset_tip_10.required' => 'فیلد ضروری است',
            'offset_tip_100.required' => 'فیلد ضروری است',
            'offset_tip_1000.required' =>  'فیلد ضروری است',

        ];



        $device = Device::find($id);
        $position = Position::find($request->position_id);


        $devicePosition->device()->associate($device);
        $devicePosition->device_id = $request->device_id;
        $position = Position::find($request->position_id);
        $devicePosition->position()->associate($position);

        if($position->type == 7 ){
        $validatedData = $request->validate([
            'g_a_x' => 'required',
            'g_a_y' => 'required',
            'g_a_z' => 'required',
            'gripper_value' => 'required',

        ] , $massage);
            $a = array();
            $a['x']= $request->g_a_x;
            $a['y']= $request->g_a_y;
            $a['z']= $request->g_a_z;
            $devicePosition->gripper = $request->gripper_value;
            $devicePosition->point_a = $a;
        }
        elseif($position->type == 8 ){
            $validatedData = $request->validate([
                'f_a_x' => 'required',
                'f_a_y' => 'required',
                'f_a_z' => 'required',
                'sampler_volume' => 'required',

            ] , $massage);
            $a = array();
            $a['x']= $request->f_a_x;
            $a['y']= $request->f_a_y;
            $a['z']= $request->f_a_z;
            $devicePosition->sampler_volume = $request->sampler_volume;
            $devicePosition->point_a = $a;
        }
        elseif($position->type == 2){

        $validatedData = $request->validate([

            'width' => 'required',
            'length' => 'required',

            'point_a_x' => 'required',
            'point_a_y' => 'required',
            'point_a_z' => 'required',

            'point_b_x' => 'required',
            'point_b_y' => 'required',
            'point_b_z' => 'required',

            'p_a_x' => 'required',
            'p_a_y' => 'required',
            'p_a_z' =>  'required',

            'p_b_x' => 'required',
            'p_b_y' => 'required',
            'p_b_z' =>  'required',

            'p_c_x' => 'required',
            'p_c_y' => 'required',
            'p_c_z' =>  'required',

            'p_d_x' => 'required',
            'p_d_y' => 'required',
            'p_d_z' =>  'required',

            'p_e_x' => 'required',
            'p_e_y' => 'required',
            'p_e_z' =>  'required',

            'p_f_x' => 'required',
            'p_f_y' => 'required',
            'p_f_z' =>  'required',

            'p_g_x' => 'required',
            'p_g_y' => 'required',
            'p_g_z' =>  'required',

            'p_h_x' => 'required',
            'p_h_y' => 'required',
            'p_h_z' =>  'required',

            'p_i_x' => 'required',
            'p_i_y' => 'required',
            'p_i_z' => 'required',

            'p_j_x' => 'required',
            'p_j_y' => 'required',
            'p_j_z' =>  'required',

            'p_k_x' => 'required',
            'p_k_y' => 'required',
            'p_k_z' =>  'required',

            'p_l_x' => 'required',
            'p_l_y' => 'required',
            'p_l_z' =>  'required',

            'p_m_x' => 'required',
            'p_m_y' => 'required',
            'p_m_z' =>  'required',
        ] , $massage);

            $a = array();
            $b = array();
            $c = array();
            $d = array();
            $e = array();
            $f = array();
            $g = array();
            $h = array();
            $i = array();
            $j = array();
            $k = array();
            $l = array();
            $m = array();

            $a['x']= $request->p_a_x;
            $a['y']= $request->p_a_y;
            $a['z']= $request->p_a_z;

            $b['x']= $request->p_b_x;
            $b['y']= $request->p_b_y;
            $b['z']= $request->p_b_z;

            $c['x']= $request->p_c_x;
            $c['y']= $request->p_c_y;
            $c['z']= $request->p_c_z;

            $d['x']= $request->p_d_x;
            $d['y']= $request->p_d_y;
            $d['z']= $request->p_d_z;

            $e['x']= $request->p_e_x;
            $e['y']= $request->p_e_y;
            $e['z']= $request->p_e_z;

            $f['x']= $request->p_f_x;
            $f['y']= $request->p_f_y;
            $f['z']= $request->p_f_z;

            $g['x']= $request->p_g_x;
            $g['y']= $request->p_g_y;
            $g['z']= $request->p_g_z;

            $h['x']= $request->p_h_x;
            $h['y']= $request->p_h_y;
            $h['z']= $request->p_h_z;

            $i['x']= $request->p_i_x;
            $i['y']= $request->p_i_y;
            $i['z']= $request->p_i_z;

            $j['x']= $request->p_j_x;
            $j['y']= $request->p_j_y;
            $j['z']= $request->p_j_z;

            $k['x']= $request->p_k_x;
            $k['y']= $request->p_k_y;
            $k['z']= $request->p_k_z;

            $l['x']= $request->p_l_x;
            $l['y']= $request->p_l_y;
            $l['z']= $request->p_l_z;

            $m['x']= $request->p_m_x;
            $m['y']= $request->p_m_y;
            $m['z']= $request->p_m_z;

            $devicePosition->p_a = $a;
            $devicePosition->p_b = $b;
            $devicePosition->p_c = $c;

            $devicePosition->p_d = $d;
            $devicePosition->p_e = $e;
            $devicePosition->p_f = $f;

            $devicePosition->p_g = $g;
            $devicePosition->p_h = $h;
            $devicePosition->p_i = $i;

            $devicePosition->p_j = $j;
            $devicePosition->p_k = $k;
            $devicePosition->p_l = $l;
            $devicePosition->p_m = $m;


            $devicePosition->p_a = json_encode($a , true);
            $devicePosition->p_b = json_encode($b , true);
            $devicePosition->p_c = json_encode($c , true);
            $devicePosition->p_d = json_encode($d , true);
            $devicePosition->p_e = json_encode($e , true);
            $devicePosition->p_f = json_encode($f , true);
            $devicePosition->p_g = json_encode($g , true);
            $devicePosition->p_h = json_encode($h , true);
            $devicePosition->p_i = json_encode($i , true);
            $devicePosition->p_j = json_encode($j , true);
            $devicePosition->p_k = json_encode($k , true);
            $devicePosition->p_l = json_encode($l , true);
            $devicePosition->p_m = json_encode($m , true);

            $a = array();
            $b = array();
            $c = array();
            $a['x']= $request->point_a_x;
            $a['y']= $request->point_a_y;
            $a['z']= $request->point_a_z;

            $b['x']= $request->point_b_x;
            $b['y']= $request->point_b_y;
            $b['z']= $request->point_b_z;

            $devicePosition->length = $request->length;
            $devicePosition->width = $request->width;
            $devicePosition->point_a = $a;
            $devicePosition->point_b = $b;
        }elseif($position->type == 6){
            $validatedData = $request->validate([

            'point_a_x' => 'required',
            'point_a_y' => 'required',
            'point_a_z' => 'required',

            'point_b_x' => 'required',
            'point_b_y' => 'required',
            'point_b_z' => 'required',

            'width' => 'required',
            'length' => 'required',

            'offset_tip_10' => 'required',
            'offset_tip_100' => 'required',
            'offset_tip_1000' =>  'required',               
    
            ] , $massage);
            $a = array();
            $b = array();
            $offset = array();
            $a['x']= $request->point_a_x;
            $a['y']= $request->point_a_y;
            $a['z']= $request->point_a_z;
    
            $b['x']= $request->point_b_x;
            $b['y']= $request->point_b_y;
            $b['z']= $request->point_b_z;

            $offset['offset_tip_10']= $request->offset_tip_10;
            $offset['offset_tip_100']= $request->offset_tip_100;
            $offset['offset_tip_1000']= $request->offset_tip_1000;
    
    
           
            $devicePosition->point_a = $a;
            $devicePosition->point_b = $b;
            // $devicePosition->offset = $offset;
            $devicePosition->offset = json_encode($offset , true);
            $devicePosition->length = $request->length;
            $devicePosition->width = $request->width;

        }
            
        else{
            $validatedData = $request->validate([
            'point_a_x' => 'required',
            'point_a_y' => 'required',
            'point_a_z' => 'required',

            'point_b_x' => 'required',
            'point_b_y' => 'required',
            'point_b_z' => 'required',

            'width' => 'required',
            'length' => 'required',

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


        $devicePosition->point_a = $a;
        $devicePosition->point_b = $b;
        $devicePosition->length = $request->length;
        $devicePosition->width = $request->width;

        if($position->type == 0){

            if($request->standard == 'on'){
                $devicePosition->standard = 1;
            }else{
                $devicePosition->standard = 0;
            }
        }
        
        }
        
        $devicePosition->save();

        $msg = 'جایگاه دستگاه با موفقیت ثبت شد';
        return redirect(route('devicePosition' , compact('id')))->with('success' , $msg);
    }

    public function editDevicePosition(Request $request , $id){
        $devicePosition = DevicePosition::find($id);
        $device = $devicePosition->device;
        $position = $devicePosition->position;
        $positions = Position::all();
        return view('device-position.edit-device-position' , compact('devicePosition' , 'device' , 'position' , 'positions'));
    }

    public function checkEditDevicePosition(Request $request ,$id){
        $massage = [
            'gripper_value.required' =>  'فیلد ضروری است',

            'p_a_x.required' => 'فیلد ضروری است',
            'p_a_y.required' => 'فیلد ضروری است',
            'p_a_z.required' =>  'فیلد ضروری است',

            'p_b_x.required' => 'فیلد ضروری است',
            'p_b_y.required' => 'فیلد ضروری است',
            'p_b_z.required' =>  'فیلد ضروری است',

            'p_c_x.required' => 'فیلد ضروری است',
            'p_c_y.required' => 'فیلد ضروری است',
            'p_c_z.required' =>  'فیلد ضروری است',

            'p_d_x.required' => 'فیلد ضروری است',
            'p_d_y.required' => 'فیلد ضروری است',
            'p_d_z.required' =>  'فیلد ضروری است',

            'p_e_x.required' => 'فیلد ضروری است',
            'p_e_y.required' => 'فیلد ضروری است',
            'p_e_z.required' =>  'فیلد ضروری است',

            'p_f_x.required' => 'فیلد ضروری است',
            'p_f_y.required' => 'فیلد ضروری است',
            'p_f_z.required' =>  'فیلد ضروری است',

            'p_g_x.required' => 'فیلد ضروری است',
            'p_g_y.required' => 'فیلد ضروری است',
            'p_g_z.required' =>  'فیلد ضروری است',

            'p_h_x.required' => 'فیلد ضروری است',
            'p_h_y.required' => 'فیلد ضروری است',
            'p_h_z.required' =>  'فیلد ضروری است',

            'p_j_x.required' => 'فیلد ضروری است',
            'p_j_y.required' => 'فیلد ضروری است',
            'p_j_z.required' =>  'فیلد ضروری است',

            'p_i_x.required' => 'فیلد ضروری است',
            'p_i_y.required' => 'فیلد ضروری است',
            'p_i_z.required' =>  'فیلد ضروری است',

            'p_k_x.required' => 'فیلد ضروری است',
            'p_k_y.required' => 'فیلد ضروری است',
            'p_k_z.required' =>  'فیلد ضروری است',

            'p_l_x.required' => 'فیلد ضروری است',
            'p_l_y.required' => 'فیلد ضروری است',
            'p_l_z.required' =>  'فیلد ضروری است',

            'p_m_x.required' => 'فیلد ضروری است',
            'p_m_y.required' => 'فیلد ضروری است',
            'p_m_z.required' =>  'فیلد ضروری است',

        ];

        $devicePosition = DevicePosition::find($id);



        $device = Device::find($id);
        $devicePosition->device()->associate($device);
        $devicePosition->device_id = $request->device_id;
        $devicePosition->position_id = $request->position_id;

        $position = Position::find($request->position_id);

        if($position->type == 2){
            $validatedData = $request->validate([


                'p_a_x' => 'required',
                'p_a_y' => 'required',
                'p_a_z' =>  'required',

                'p_b_x' => 'required',
                'p_b_y' => 'required',
                'p_b_z' =>  'required',

                'p_c_x' => 'required',
                'p_c_y' => 'required',
                'p_c_z' =>  'required',

                'p_d_x' => 'required',
                'p_d_y' => 'required',
                'p_d_z' =>  'required',

                'p_e_x' => 'required',
                'p_e_y' => 'required',
                'p_e_z' =>  'required',

                'p_f_x' => 'required',
                'p_f_y' => 'required',
                'p_f_z' =>  'required',

                'p_g_x' => 'required',
                'p_g_y' => 'required',
                'p_g_z' =>  'required',

                'p_h_x' => 'required',
                'p_h_y' => 'required',
                'p_h_z' =>  'required',

                'p_i_x' => 'required',
                'p_i_y' => 'required',
                'p_i_z' => 'required',

                'p_j_x' => 'required',
                'p_j_y' => 'required',
                'p_j_z' =>  'required',

                'p_k_x' => 'required',
                'p_k_y' => 'required',
                'p_k_z' =>  'required',

                'p_l_x' => 'required',
                'p_l_y' => 'required',
                'p_l_z' =>  'required',

                'p_m_x' => 'required',
                'p_m_y' => 'required',
                'p_m_z' =>  'required',


            ] , $massage);

        $a = array();
        $b = array();
        $c = array();
        $d = array();
        $e = array();
        $f = array();
        $g = array();
        $h = array();
        $i = array();
        $j = array();
        $k = array();
        $l = array();
        $m = array();

        $a['x']= $request->p_a_x;
        $a['y']= $request->p_a_y;
        $a['z']= $request->p_a_z;

        $b['x']= $request->p_b_x;
        $b['y']= $request->p_b_y;
        $b['z']= $request->p_b_z;

        $c['x']= $request->p_c_x;
        $c['y']= $request->p_c_y;
        $c['z']= $request->p_c_z;

        $d['x']= $request->p_d_x;
        $d['y']= $request->p_d_y;
        $d['z']= $request->p_d_z;

        $e['x']= $request->p_e_x;
        $e['y']= $request->p_e_y;
        $e['z']= $request->p_e_z;

        $f['x']= $request->p_f_x;
        $f['y']= $request->p_f_y;
        $f['z']= $request->p_f_z;

        $g['x']= $request->p_g_x;
        $g['y']= $request->p_g_y;
        $g['z']= $request->p_g_z;

        $h['x']= $request->p_h_x;
        $h['y']= $request->p_h_y;
        $h['z']= $request->p_h_z;

        $i['x']= $request->p_i_x;
        $i['y']= $request->p_i_y;
        $i['z']= $request->p_i_z;

        $j['x']= $request->p_j_x;
        $j['y']= $request->p_j_y;
        $j['z']= $request->p_j_z;

        $k['x']= $request->p_k_x;
        $k['y']= $request->p_k_y;
        $k['z']= $request->p_k_z;

        $l['x']= $request->p_l_x;
        $l['y']= $request->p_l_y;
        $l['z']= $request->p_l_z;

        $m['x']= $request->p_m_x;
        $m['y']= $request->p_m_y;
        $m['z']= $request->p_m_z;

        $devicePosition->p_a = $a;
        $devicePosition->p_b = $b;
        $devicePosition->p_c = $c;

        $devicePosition->p_d = $d;
        $devicePosition->p_e = $e;
        $devicePosition->p_f = $f;

        $devicePosition->p_g = $g;
        $devicePosition->p_h = $h;
        $devicePosition->p_i = $i;

        $devicePosition->p_j = $j;
        $devicePosition->p_k = $k;
        $devicePosition->p_l = $l;
        $devicePosition->p_m = $m;


        $devicePosition->p_a = json_encode($a , true);
        $devicePosition->p_b = json_encode($b , true);
        $devicePosition->p_c = json_encode($c , true);
        $devicePosition->p_d = json_encode($d , true);
        $devicePosition->p_e = json_encode($e , true);
        $devicePosition->p_f = json_encode($f , true);
        $devicePosition->p_g = json_encode($g , true);
        $devicePosition->p_h = json_encode($h , true);
        $devicePosition->p_i = json_encode($i , true);
        $devicePosition->p_j = json_encode($j , true);
        $devicePosition->p_k = json_encode($k , true);
        $devicePosition->p_l = json_encode($l , true);
        $devicePosition->p_m = json_encode($m , true);

        $a = array();
        $b = array();

        $a['x']= $request->point_a_x;
        $a['y']= $request->point_a_y;
        $a['z']= $request->point_a_z;
        $b['x']= $request->point_b_x;
        $b['y']= $request->point_b_y;
        $b['z']= $request->point_b_z;

        $devicePosition->point_a = $a;
        $devicePosition->point_b = $b;
        $devicePosition->length = $request->length;
        $devicePosition->width = $request->width;

    }elseif($position->type == 7){
        $validatedData = $request->validate([
            'g_a_x' => 'required',
            'g_a_y' => 'required',
            'g_a_z' => 'required',
            'gripper_value' => 'required',

        ] , $massage);
            $a = array();
            $a['x']= $request->g_a_x;
            $a['y']= $request->g_a_y;
            $a['z']= $request->g_a_z;
            $devicePosition->gripper = $request->gripper_value;
            $devicePosition->point_a = $a;
    }elseif($position->type == 6){
        $validatedData = $request->validate([

        'point_a_x' => 'required',
        'point_a_y' => 'required',
        'point_a_z' => 'required',

        'point_b_x' => 'required',
        'point_b_y' => 'required',
        'point_b_z' => 'required',

        'width' => 'required',
        'length' => 'required',

        'offset_tip_10' => 'required',
        'offset_tip_100' => 'required',
        'offset_tip_1000' =>  'required',               

        ] , $massage);
        $a = array();
        $b = array();
        $offset = array();
        $a['x']= $request->point_a_x;
        $a['y']= $request->point_a_y;
        $a['z']= $request->point_a_z;

        $b['x']= $request->point_b_x;
        $b['y']= $request->point_b_y;
        $b['z']= $request->point_b_z;

        $offset['offset_tip_10']= $request->offset_tip_10;
        $offset['offset_tip_100']= $request->offset_tip_100;
        $offset['offset_tip_1000']= $request->offset_tip_1000;


       
        $devicePosition->point_a = $a;
        $devicePosition->point_b = $b;
        // $devicePosition->offset = $offset;
        $devicePosition->offset = json_encode($offset , true);
        $devicePosition->length = $request->length;
        $devicePosition->width = $request->width;

    }
    elseif($position->type == 8 ){
            $validatedData = $request->validate([
                'f_a_x' => 'required',
                'f_a_y' => 'required',
                'f_a_z' => 'required',
                'sampler_volume' => 'required',

            ] , $massage);
            $a = array();
            $a['x']= $request->f_a_x;
            $a['y']= $request->f_a_y;
            $a['z']= $request->f_a_z;
            $devicePosition->sampler_volume = $request->sampler_volume;
            $devicePosition->point_a = $a;
        }
        else{
        $a = array();
        $b = array();

        $a['x']= $request->point_a_x;
        $a['y']= $request->point_a_y;
        $a['z']= $request->point_a_z;
        $b['x']= $request->point_b_x;
        $b['y']= $request->point_b_y;
        $b['z']= $request->point_b_z;

        $devicePosition->point_a = $a;
        $devicePosition->point_b = $b;
        $devicePosition->length = $request->length;
        $devicePosition->width = $request->width;

        if($position->type == 0){

            if($request->standard == 'on'){
                $devicePosition->standard = 1;
            }else{
                $devicePosition->standard = 0;
            }
        }

    }

        

        $devicePosition->save();

        $device = $devicePosition->device;

        $device_id = $devicePosition->device_id;
        $msg = 'جایگاه دستگاه با موفقیت ویرایش شد';
        return redirect(route('devicePosition', $device_id))->with('success' , $msg);
    }

    public function deleteDevicePosition($id){
        $devicePosition = DevicePosition::find($id);
        $devicePosition->delete();
        $msg = 'حذف با موفقیت انجام شد';

        $device = $devicePosition->device;
        return redirect(route('devicePosition' ,$device->id ))->with('success' , $msg);
    }
}
