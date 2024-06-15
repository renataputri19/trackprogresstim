<?php

namespace App\Http\Controllers;

use App\Models\TasksAssignment;
use App\Models\User;
use App\Models\UserAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminTasksAssignmentController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $tasks = TasksAssignment::where('leader_id', $userId)->get();
        return view('admin.tasks.index', compact('tasks'));
    }
    
    public function create()
    {
        $user = Auth::user();
        return view('admin.tasks.create', compact('user'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'leader_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'target' => 'required|integer|min:1',
        ]);
    
        TasksAssignment::create([
            'leader_id' => $validated['leader_id'],
            'name' => $validated['name'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'target' => $validated['target'],
            'progress_total' => 0,
        ]);
    
        return redirect()->route('admin.tasks.index')->with('success', 'Task created successfully');
    }

    public function edit(TasksAssignment $task)
    {
        $leaders = User::where('is_admin', 1)->get();
        return view('admin.tasks.edit', compact('task', 'leaders'));
    }

    public function update(Request $request, TasksAssignment $task)
    {
        $validated = $request->validate([
            'leader_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'target' => 'required|integer|min:1',
        ]);

        $task->update($validated);
        return redirect()->route('admin.tasks.index')->with('success', 'Task updated successfully');
    }

    public function destroy(TasksAssignment $task)
    {
        $task->delete();
        return redirect()->route('admin.tasks.index')->with('success', 'Task deleted successfully');
    }

    public function assign($taskId)
    {
        $task = TasksAssignment::with('userAssignments.user')->findOrFail($taskId);
        $users = User::all();
        return view('admin.assignments.create', compact('task', 'users'));
    }

    public function superadminIndex()
    {
        $tasks = TasksAssignment::with('leader', 'userAssignments.user')->get();
        return view('admin.superadmin.tasks.index', compact('tasks'));
    }

    public function assignedTasks()
    {
        $user = Auth::user();
        $assignments = UserAssignment::where('user_id', $user->id)->with('task')->get();
    
        return view('admin.assigned-tasks.index', compact('assignments'));
    }
    
    
    
    
    

}


