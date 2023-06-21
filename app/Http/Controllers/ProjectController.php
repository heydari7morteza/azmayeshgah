<?php

namespace App\Http\Controllers;
use App\Models\Project;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    //
    public function showProject($id){

        $device = Device::find($id);

        $projects = $device->projects;
        return view('projects.project' , compact('projects' , 'device'));
    }

    public function allProjects(){
        $projects = Project::orderBy('id' , 'desc')->get();
        return view('projects.allProjects' , compact('projects'));

    }

    public function createProject($id){
        $device = Device::find($id);
        return view('projects.createProject' , compact('device' ,'id'));
    }

    public function checkCreateProject(Request $request , $id){
        $massage = [
            'name.required' => 'نام ضروری  است',
            'speed_x.required' => 'فیلد اجباری است',
            'speed_y.required' => 'فیلد اجباری است',
            'speed_z.required' => 'فیلد اجباری است',
            'safe_zone.required' => 'فیلد اجباری است',
        ];

        
            $validatedData = $request->validate([
                'name' => 'required',
                'speed_x' => 'required',
                'speed_y' => 'required',
                'speed_z' => 'required',
                'safe_zone' => 'required',
            ] , $massage);

            $project = new Project();
            $device = Device::find($request->device_id);
            $project->device()->associate($device);
            $project->device_id = $request->device_id;
            $project->name = $request->name;
            $project->speed_x = $request->speed_x;
            $project->speed_y = $request->speed_y;
            $project->speed_z = $request->speed_z;
            $project->safe_zone = $request->safe_zone;
    
            if(isset($request->description)){
                $project->description = $request->description;
            }
            $project->save();

            
            $msg = 'پروژه با موفقیت ثبت شد';
            if(Auth::user()->type == 1){
                return redirect(route('showProject' , $id))->with('success' , $msg);
            }else{
                return redirect(route('allProjects'))->with('success' , $msg);
            }
            
    }

    public function editProject($id){
        $project = Project::find($id);
        return view('projects.editProject' , compact('project'));

    }

    public function checkEditProject(Request $request,$id){
        $project = Project::find($id);
        $device_id = $project->device_id;

        $device = Device::find($request->device_id);
        $project->device()->associate($device);
        $project->device_id = $request->device_id;
        $project->name = $request->name;
        $project->speed_x = $request->speed_x;
        $project->speed_y = $request->speed_y;
        $project->speed_z = $request->speed_z;
        $project->safe_zone = $request->safe_zone;

        if(isset($request->description)){
            $project->description = $request->description;
        }
        
        
        $project->save();

        $msg = 'پروژه با موفقیت ویرایش شد';

        if(Auth::user()->type == 1){
            return redirect(route('showProject' , $device_id))->with('success' , $msg);
        }else{
            return redirect(route('allProjects'))->with('success' , $msg);
        }

    }

    public function deleteProject($id){
        $project = Project::find($id);
        $device_id = $project->device_id;
        $project->delete();
        $msg = 'پروژه با موفقیت حذف شد';

        if(Auth::user()->type == 1){
            return redirect(route('showProject' , $device_id))->with('success' , $msg);
        }else{
            return redirect(route('allProjects'))->with('success' , $msg);
        }
    }


}
