<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProjectController extends Controller
{   
    public function index(){
        $projects=Project::with('owner')->get();
        return view('projects.projects_index',compact('projects'));
    }
    public function create(){
        $users = User::all();
        return view('projects.projects_create',compact('users'));
    }

    public function store(Request $request){
         $request->validate(
            [
                'name'=>'required|string|max:255',
                'description'=>'nullable|string',
                'owner_id' => 'required|exists:users,id',
            ]
         );

         Project::create([
            'name'=>$request->name,
            'description'=>$request->description,
            'owner_id'=>$request->owner_id
         ]);

         return redirect()->route('projects.index')->with('success','Project Created Successfully');
    }

    public function edit(Project $project){
         $users = User::all();
        return view('projects.project_edit',compact('project','users'));
    }

    public function update(Request $request,Project $project){
        $request->validate([
           'name'=>'required|string|max:255',
            'description'=>'nullable|string',
            'owner_id' => 'required|exists:users,id',
        ]);
        $project->update($request->only('name','description','owner_id'));

        return redirect()->route('projects.index')->with('success','Project Updated Successfully');
    }

    public function destroy(Project $project){
        $project->delete();
        return redirect()->back()->with('success','Post Deleted Successfully');
    }
}
