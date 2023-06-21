<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Liquid;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
class LiquidProjectController extends Controller
{
    

    public function liquidProject($id){
        $liquids = Liquid::where('project_id' , null)->orWhere('project_id' , '=', $id)->get();
        return view('liquid-project.liquidProject' ,compact('liquids' , 'id'));
    }
    
    public function createLiquidProject($id){
        $project = Project::find($id);

        return view('liquid-project.createLiquidProject' , compact('project' , 'id'));
    }

    public function checkCreateLiquidProject( Request $request,$id){
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

            $device = $project->device;
            $liquid->save();

            $msg = 'مایع با موفقیت ایجاد شد';

            
            return redirect(route('showMap' ,$id ))->with('success' , $msg);
    }

    public function editLiquidProject($id){

        $liquid = Liquid::find($id);
        
        return view('liquid-project.editLiquidProject' , compact('liquid'));
    }


    public function checkEditLiquidProject(Request $request , $id){

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

        $user = User::find($liquid->user_id);
        $user_project = $user->liquids;
        $project_id = $user_project->where('project_id', '!=', null)->first();
        
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

        if($project_id){
            return redirect(route('liquidProject' , $project_id->project_id))->with('success' , $msg);
        }else{
            return redirect(route('userShowLiquids' ,  Auth::user()->id))->with('success' , $msg);
        }
       
    }

    public function deleteLiquidProject($id){
    $liquid = Liquid::find($id);
    $liquid->delete();
    $user = User::find($liquid->user_id);
    $user_project = $user->liquids;
    $project_id = $user_project->where('project_id', '!=', null)->first();

    $msg = 'مایع با موفقیت حذف شد';
    if($project_id){
        return redirect(route('liquidProject' , $project_id->project_id))->with('success' , $msg);
    }else{
        return redirect(route('userShowLiquids' ,  Auth::user()->id))->with('success' , $msg);
    }
}

}
