<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Position;
use App\Models\Option;
use Illuminate\Support\Facades\Validator;


class PositionController extends Controller
{
    //
    public function showPosition(){
        $positions = Position::get();

        $position_option = array();
        $position_type = Option::where('key' , 'position_type')->first();
        $position_option = json_decode($position_type['value'] , true);

        $device_option = array();
        $device = Option::where('key' , 'device_type')->first();
        $device_option = json_decode($device['value'] , true);

        return view('positions.showPosition' , compact('positions' , 'position_option' , 'device_option'));
    }

    public function createPosition(){
        $position_option = array();
        $position_type = Option::where('key' , 'position_type')->first();
        $position_option = json_decode($position_type['value'] , true);
        $device_option = array();
        $device = Option::where('key' , 'device_type')->first();
        $device_option = json_decode($device['value'] , true);
        return view('positions.createPosition' , compact('position_option' , 'device_option'));
    }
    
    public function createPositionForm(Request $request){

        $massage = [
            'device_type.required' => 'فیلد نوع دستگاه ضروری است',
            'name.required' => 'نام ضروری  است',
            'type.required' => 'تایپ ضروری است',
        ];

        
            $validatedData = $request->validate([
                'device_type' => 'required',
                'name' => 'required',
                'type' => 'required',
            ] , $massage);
        
         
            $position = new Position();
            $position->device_type = $request->device_type;
            $position->name = $request->name;
            $position->description = $request->description;
            $position->type = $request->type;

            $position->save();

            
            $msg = 'جایگاه با موفقیت ثبت شد';
            return redirect(route('showPosition'))->with('success' , $msg);
    }

    public function editPosition($id){
        
        $position = Position::find($id);

        $position_option = array();
        $position_type = Option::where('key' , 'position_type')->first();
        $position_option = json_decode($position_type['value'] , true);
        $device_option = array();
        $device = Option::where('key' , 'device_type')->first();
        $device_option = json_decode($device['value'] , true);

        return view ('positions.editPosition' , compact('position' , 'position_option' , 'device_option'));

    }

    public function checkEditPosition(Request $request, $id){
        $position = Position::find($id);

        $position->device_type = $request->device_type;
        $position->name = $request->name;
        $position->description = $request->description;
        $position->type = $request->type;

        $position->save();

        $msg = 'جایگاه با موفقیت ویرایش شد';
        return redirect(route('showPosition'))->with('success' , $msg);
    }

    
    public function deletePosition($id){
        
        $postion = Position::find($id);
        $postion->delete();

        $msg = "جایگاه با موفقیت حذف شد";
        return redirect(route('showPosition'))->with('success' , $msg);
    }
}
