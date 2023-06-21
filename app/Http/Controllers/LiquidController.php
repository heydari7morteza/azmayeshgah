<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Liquid;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LiquidController extends Controller
{

    
    public function all_liquids(){
        $liquids = Liquid::orderBy('id' , 'desc')->get();
        return view('liquids.all-liquids' ,compact('liquids'));
    }

    public function showLiquids($id){
        $project = Project::find($id);
        $liquids = $project->liquids;
        
        if($liquids->isNotEmpty()){
            return view ('liquids.liquid',compact('liquids' , 'id'));
        }else{
            $project = Project::find($id);
            return view('liquids.createLiquid' , compact('project'));
        }
    }
    public function createLiquid($id){
        $project = Project::find($id);
        return view('liquids.createLiquid' , compact('project' , 'id'));
    }

    public function checkCreateLiquid( Request $request,$id){
        $massage = [
            'name.required' => 'نام را وارد کنید',
            'volume.required' => 'مقدار را وارد کنید',
            'density.required' => 'تراکم را وارد کنید',
        ];


            $validatedData = $request->validate([
                'name' => 'required',
                'volume' => 'required',
                'density' => 'required',
            ] , $massage);

            $liquid = new Liquid();

            $project = Project::find($id);
            $liquid->project()->associate($project);

            $liquid->project_id = $request->project_id;
            $liquid->user_id = $request->user_id;
            $liquid->name = $request->name;
            $liquid->description = $request->description;
            $liquid->volume = $request->volume;
            $liquid->density = $request->density;

            $liquid->save();

            $msg = 'مایع با موفقیت ایجاد شد';


            return redirect(route('showMap' ,$id ))->with('success' , $msg);
            

    }


    public function editLiquid($id){

        $liquid = Liquid::find($id);
        return view('liquids.editLiquid' , compact('liquid'));
    }


    public function checkEditLiquid(Request $request , $id){

        $massage = [
            'name.required' => 'نام را وارد کنید',
            'volume.required' => 'مقدار را وارد کنید',
            'density.required' => 'تراکم را وارد کنید',
        ];


            $validatedData = $request->validate([
                'name' => 'required',
                'volume' => 'required',
                'density' => 'required',
            ] , $massage);

        $liquid = Liquid::find($id);

        $project = Project::find($id);
        $liquid->project()->associate($project);

        $liquid->project_id = $request->project_id;
        $liquid->user_id = $request->user_id;
        $liquid->name = $request->name;

        if(isset($request->description)){
            $liquid->description = $request->description;
        }
        $liquid->volume = $request->volume;
        $liquid->density = $request->density;

        $liquid->save();

        $msg = 'مایع با موفقیت ویرایش شد';

        return redirect(route('showLiquids' , $liquid->project_id))->with('success' , $msg);
    }

    public function deleteLiquid($id){
    $liquid = Liquid::find($id);
    $liquid->delete();

    $msg = 'مایع با موفقیت حذف شد';
        if(!($liquid->project_id)){
            return redirect(route('userShowLiquids' ,  Auth::user()->id))->with('success' , $msg);
        }else{
            return redirect(route('showLiquids' ,  $liquid->project_id))->with('success' , $msg);
        }
        
    }




    // these methods are the basis of user id

    public function userAll_liquids(){
        $liquids = Liquid::orderBy('id' , 'desc')->get();
        return view('user-liquids.all-liquids' ,compact('liquids'));
    }
    public function userShowLiquids($id){
        
        $user = User::find($id);
        $liquids = $user->liquids;
        if($liquids->isNotEmpty()){
            return view ('user-liquids.liquid',compact('liquids' , 'id'));
        }else{
            return view('user-liquids.createLiquid' , compact('id'));
        }
    }
    

    public function userCreateLiquid($id){
        return view('user-liquids.createLiquid' , compact('id'));
    }

    public function userCheckCreateLiquid( Request $request,$id){
        $massage = [
            'name.required' => 'نام را وارد کنید',
            'volume.required' => 'مقدار را وارد کنید',
            'density.required' => 'تراکم را وارد کنید',
        ];


            $validatedData = $request->validate([
                'name' => 'required',
                'volume' => 'required',
                'density' => 'required',
            ] , $massage);

            $liquid = new Liquid();

            $user = User::find($id);
            $liquid->user()->associate($user);

            

            $liquid->project_id = $request->project_id;
            $liquid->user_id = $request->user_id;
            $liquid->name = $request->name;
            $liquid->description = $request->description;
            $liquid->volume = $request->volume;
            $liquid->density = $request->density;

          
            $liquid->save();

            $msg = 'مایع با موفقیت ایجاد شد';

            if(Auth::user()->type == 0){
                return redirect(route('userAll_liquids'))->with('success' , $msg);
            }else{
                return redirect(route('userShowLiquids' , $id))->with('success' , $msg);
            }

    }

    public function userEditLiquid($id){

        $liquid = Liquid::find($id);
        return view('user-liquids.editLiquid' , compact('liquid'));
    }


    public function userCheckEditLiquid(Request $request , $id){

        $massage = [
            'name.required' => 'نام را وارد کنید',
            'volume.required' => 'مقدار را وارد کنید',
            'density.required' => 'تراکم را وارد کنید',
        ];


            $validatedData = $request->validate([
                'name' => 'required',
                'volume' => 'required',
                'density' => 'required',
            ] , $massage);

        $liquid = Liquid::find($id);

        $user = User::find(Auth::user()->id);
        $liquid->user()->associate($user);

        $liquid->project_id = $request->project_id;
        $liquid->user_id = $request->user_id;
        $liquid->name = $request->name;

        
        $liquid->volume = $request->volume;
        $liquid->density = $request->density;
        if(isset($request->description)){
            $liquid->description = $request->description;
        }

       
        $liquid->save();

        $msg = 'مایع با موفقیت ویرایش شد';

        if(Auth::user()->type == 0){
            return redirect(route('userAll_liquids'))->with('success' , $msg);
        }else{
            return redirect(route('userShowLiquids' , $user->id))->with('success' , $msg);
        }
    }

    public function userDeleteLiquid($id){
        $liquid = Liquid::find($id);
        $liquid->delete();
    
        $msg = 'مایع با موفقیت حذف شد';
    
       
        if(Auth::user()->type == 0){
            return redirect(route('userAll_liquids'))->with('success' , $msg);
        }else{
            return redirect(route('userShowLiquids' , Auth::user()->id))->with('success' , $msg);
        }

        }
    

}
