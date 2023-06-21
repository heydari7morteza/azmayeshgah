<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Option;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class OptionController extends Controller
{
    //

    public function showOptions(){
        $options = Option::get();
        $user = Auth::user();
       foreach ($options as $option) {
           $option->value = json_decode($option->value , true);
       }
        return view ('options.option' , compact('options' , 'user'));
    }

    public function createOption($id){
        $user = Auth::user();
        $option = Option::find($id);
        return view ('options.createOption' , compact('option', 'user'));
    }
    
    public function checkCreateOption(Request $request , $id){
        $massage = [
            'type.required' => 'نوع را وارد کنید',
            'key.required' => 'کلید را وارد کنید',
            'value.required' => 'مقدار را وارد کنید',
        ];

        
            $validatedData = $request->validate([
                'type' => 'required',
                'key' => 'required',
                'value' => 'required',
            ] , $massage);

            $option = new Option();
            $user = Auth::user();
            $option->user()->associate($user);

            $option->user_id = $request->user_id;
            $option->type = $request->type;
            $option->key = $request->key;
            $values = array();
            foreach($request->key_value as $key => $key_value){
                $value = $request->value[$key];
                array_push($values,new \ArrayObject([$key_value => $value]) );
            }
            $option->value = json_encode($values , JSON_UNESCAPED_UNICODE);
            

            $option->save();

            $msg = 'تنظیمات با موفقیت ایجاد شد';
            $user = Auth::user();
            return redirect(route('showOptions' , $user->id))->with('success' , $msg);
    }

    public function editOption($id){
            $option = Option::find($id);
            $value = json_decode($option->value , true);
            return view('options.editOption' ,compact('option' , 'value'));
    }

    public function checkEditOption(Request $request , $id){
        
        $massage = [
            'type.required' => 'نوع را وارد کنید',
            'key.required' => 'کلید را وارد کنید',
            'value.required' => 'مقدار را وارد کنید',
            'key_value.required' => 'کلید را وارد کنید',
        ];
        
        
        $validatedData = $request->validate([
            'type' => 'required',
            'key' => 'required',
            'value' => 'required',
            'key_value' => 'required',
            ] , $massage);
            
            $option = Option::find($id);
            $user = Auth::user();
            
            $option->user()->associate($user);
            $option->user_id = $request->user_id;
            $option->type = $request->type;
            $option->key = $request->key;
            $values = array();
            
            foreach($request->key_value as $key => $key_value){
                $value = $request->value[$key];
                array_push($values,new \ArrayObject([$key_value => $value]) );
            }
            $option->value = json_encode($values , true);
            

            $option->save();

            $msg = 'تنظیمات با موفقیت ویرایش شد';
            $user = Auth::user();
            return redirect(route('showOptions' , $user->id))->with('success' , $msg);
    }

    public function deleteOption($id){
        $option = Option::find($id);
        $option->delete();

        $user = Auth::user();
        $msg = 'تنظیمات با موفقیت حذف شد';
        return redirect(route('showOptions' , $user->id))->with('success' , $msg);
    }

}
