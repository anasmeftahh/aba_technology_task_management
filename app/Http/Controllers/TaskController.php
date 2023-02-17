<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Project;
use App\Models\User;
use App\Models\Task;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $tasks = Task::paginate(5);
        return response()->json([
            'status' => 'success',
            'tasks' => $tasks,
        ]);
    }

    public function store(Request $request)
    {

        $request->validate([
            'project_id' => 'required',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'status' => 'required',
            'assignedMemberId' => 'required'
        ]);

        // we get the member
        $assignedMember = User::find($request->assignedMemberId);

        if ($assignedMember->role_id != 3) {
            return response()->json([
                'message' => 'You should assign the project to a member',
            ]);
        }

        $project = Project::find($request->project_id);

        $project->tasks()->create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'user_id' => $request->assignedMemberId,
            'created_by' => Auth::user()->name,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'task added successfully to the project',
            'project' => $project,
        ]);
    }

    public function show($id)
    {
        $task = Task::find($id);
        return response()->json([
            'status' => 'success',
            'task' => $task,
        ]);
    }

    public function showMembertasks($id)
    {
        $member = User::find($id);
        if ($member->role_id != 3) {
            return response()->json([
                'message' => 'The given user is not a member',
            ]);
        }
        $tasks = $member->tasks()->get();
        return response()->json([
            'status' => 'success',
            'tasks' => $tasks,
        ]);
    }

    

    public function getTaskProgress($id)
    {
        $task = Task::find($id);
        return response()->json([
            'status' => 'success',
            'taskProgress' => $task->status,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'status' => 'required',
        ]);

        $task = Task::find($id);
        $task->title = $request->title;
        $task->description = $request->description;
        $task->status = $request->status;
        $task->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Task updated successfully',
            'task' => $task,
        ]);
    }

    public function destroy($id)
    {
        $task = Task::find($id);
        $task->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Task deleted successfully',
            'task' => $task,
        ]);
    }
}
