<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Project;
use App\Models\User;
use App\Models\ProjectUser;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        // $projects = Project::all();
        $projects = Project::paginate(5);
        // $projects = DB::table('projects')->paginate(15);
        return response()->json([
            'status' => 'success',
            'projects' => $projects,
        ]);
    }

    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'status' => 'required',
            'deadline' => 'required',
            'assignedManagerId' => 'required'
        ]);

        // we get the manager user
        $assignedManager = User::find($request->assignedManagerId);

        if ($assignedManager->role_id != 2) {
            return response()->json([
                'message' => 'You should assign the project to a manager',
            ]);
        }

        $project = Project::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'deadline' => $request->deadline,
            'created_by' => Auth::user()->name,
        ]);

        // We assign the project to a manager using an Admin account
        ProjectUser::create([
            'user_id'=>$assignedManager->id,
            'project_id'=>$project->id
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Project created successfully',
            'project' => $project,
        ]);
    }

    public function show($id)
    {
        $project = Project::find($id);
        return response()->json([
            'status' => 'success',
            'project' => $project,
        ]);
    }

    public function showManagerProjects($id)
    {
        $manager = User::find($id);
        if ($manager->role_id != 2) {
            return response()->json([
                'message' => 'The given user is not a manager',
            ]);
        }
        $projects = $manager->projects()->latest()->get();
        // dd($projects);
        return response()->json([
            'status' => 'success',
            'projects' => $projects,
        ]);
    }

    

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'status' => 'required',
            'deadline' => 'required',
        ]);

        $project = Project::find($id);
        $project->title = $request->title;
        $project->description = $request->description;
        $project->status = $request->status;
        $project->deadline = $request->deadline;
        $project->created_by = Auth::user()->name;
        $project->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Project updated successfully',
            'project' => $project,
        ]);
    }

    public function destroy($id)
    {
        $project = Project::find($id);
        $project->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Project deleted successfully',
            'project' => $project,
        ]);
    }
}
