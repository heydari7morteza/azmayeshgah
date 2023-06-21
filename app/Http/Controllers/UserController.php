<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Exceptions\InvalidOrderException;
use App\Models\User;
use App\Models\Option;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{

    public function userInfo(){


        $user_type = Option::where('key' , 'user_type')->first();
        $user_option = array();
        $user_option = json_decode($user_type['value'] , true);

        $users = User::orderBy('id' , 'desc')->get();
        
        return view('users.user' , compact('users' , 'user_option'));
        
    }



    public function addUserForm(Request $request , User $user){

        $user_type = Option::where('key' , 'user_type')->first();
        $user_option = array();
        $user_option = json_decode($user_type['value'] , true);

        $user = User::find(Auth::user()->id);
        return view('users.addUser' , compact('user_option' , 'user'));
    }

    public function addUser(Request $request , User $user){
        $massage = [
            'name.required' => 'فیلد نام را وارد کنید',
            'type.required' => 'فیلد تایپ را وارد کنید',
            'email.required' => 'فیلد ایمیل را وارد کنید',
            'password.required' => 'نام کاربری را وارد کنید',
            'name.unique' => 'نام شما تکراری است',
            'email.unique' => 'ایمیل شما تکراری است',
        ] ;

        if(!empty($request->password)){
            $validatedData = $request->validate([
                'name' => 'required|unique:users',
                'type' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'min:8|confirmed',
                'password_confirmation' => 'min:8',
            ] , $massage);

            $password = Hash::make($request->password);
            $user->password = $password;
        }else{
            $validatedData = $request->validate([
                'name' => 'required|unique:users',
                'type' => 'required',
                'email' => 'required|unique:users',
            ] , $massage);
        }
         
        $user->name = $request->name;
        $user->type = $request->type;
        $user->email = $request->email;
        $user->save();
        $msg = 'کاربر با موفقیت ثبت شد';

        $users = User::all();
        return redirect(route('userInfo' , compact('users')))->with('success' , $msg);
    }

    public function editUser($id){
        $user = User::find($id);
        $user_type = Option::where('key' , 'user_type')->first();
        $user_option = array();
        $user_option = json_decode($user_type['value'] , true);
        
        return view('users.editUser' , compact('user' , 'user_option'));
        
       
    }





    public function editUserInfo(Request $request, User $user){
        $id = $request->id;
        $user = User::find($id);

        if(!empty($request->password)){
            $validatedData = $request->validate([
                'name' => ['required' ,'unique:users,name,'.$id],
                'type' => 'required',
                'email' => ['required' ,'unique:users,email,'.$id],
                'password' => 'min:8',
                'password_confirmation' => 'min:8',
            ]);

            $password = Hash::make($request->password);
            $user->password = $password;
        }else{
            $validatedData = $request->validate([
                'name' => ['required' ,'unique:users,name,'.$id],
                'type' => 'required',
                'email' => ['required' ,'unique:users,email,'.$id],
            ] );
        }
         
        $user->name = $request->name;
        $user->type = $request->type;
        $user->email = $request->email;

        $user->save();

        $msg = 'کاربر با موفقیت ویرایش شد';

        return redirect(route('userInfo' , compact('user')))->with('success' , $msg);
    }

    public function deleteUser($id){
        $user = User::find($id);
        $msg = 'کاربر با موفقیت حذف شد';
        $user->delete();
        return redirect(route('userInfo'))->with('success' , $msg);
    }

}
