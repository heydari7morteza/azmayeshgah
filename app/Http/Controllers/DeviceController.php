<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\User;
use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DeviceController extends Controller
{

    public function createDevice($id){
        $user = User::find($id);
        $device_option = array();
        $option = Option::where('key' , 'device_type')->first();
        $device_option = json_decode($option['value'] , true);;
        return view('device.createDevice'  , compact('id' , 'device_option'));
    }

    public function createDeviceForm(Request $request , $id){

            $device = new Device();
            $user = User::find($id);
            $device->user()->associate($user);
            $device->type = $request->type;
            if($request->standard == 'on'){
                $device->standard = 1;
            }else{
                $device->standard = 0;
            }
            
            $device->save();
            

            if(isset($request->description)){
                $device->description = $request->description;
            }

            $device->save();
            
            $msg = 'دستگاه با موفقیت ثبت شد';

            if(Auth::user()->type == 1){
                return redirect(route('showDevice' , compact('id') , $id))->with('success' , $msg);
            }else{
                return redirect(route('allDevice'))->with('success' , $msg);
            }
        
    }


    public function allDevice(){
        $devices = Device::orderBy('id','desc')->get();
        $device_option = array();
        $option = Option::where('key' , 'device_type')->first();
        $device_option = json_decode($option['value'] , true);
        return view('device.allDevice' , compact('devices' , 'device_option'));
    }

    public function showDevice($id){
        $device_option = array();
        $option = Option::where('key' , 'device_type')->first();
        $device_option = json_decode($option['value'], true);
        $user = User::find($id);
        $id = $user->id;
        $devices = $user->devices;
        if($devices->isNotEmpty()){
            return view('device.device' , compact('devices' , 'id' , 'device_option'));
        }else{
            return view('device.createDevice'  , compact('id', 'device_option'));
        }
    }

    public function editDevice($id){
        $device = Device::find($id);
        $device_option = array();
        $option = Option::where('key' , 'device_type')->first();
        $device_option = json_decode($option['value'] , true);
        return view ('device.edit' , compact('device' , 'device_option'));
    }

    public function editDeviceForm(Request $request , Device $device){
        $device = Device::find($request->id);
        $id = $device->user_id;
        $user = User::find($id);
        $device->user()->associate($user);
        $validatedData = $request->validate([
            'description'=> 'max:300',
        ]);

        $device->type = $request->type;
        $device->description = $request->description;

        if($request->standard == 'on'){
            $device->standard = 1;
        }else{
            $device->standard = 0;
        }
        
       
        $device->save();

        $msg = 'ویرایش انجام شد';
        if(Auth::user()->type == 1){
            return redirect(route('showDevice' , $id))->with('success' , $msg);
        }else{
            return redirect(route('allDevice'))->with('success' , $msg);
        }
        

    }

    public function deleteDevice($id){
        
        $device = Device::find($id);
        $device->delete();

        
        $msg = "دستگاه با موفقیت حذف شد";
        if(Auth::user()->type == 0){
            return redirect(route('showDevice' , $device->user_id))->with('success' , $msg);
        }else{
            return redirect(route('allDevice'))->with('success' , $msg);
        }
    }

}
