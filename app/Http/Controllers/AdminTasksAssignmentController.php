<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use App\Models\TasksAssignment;
use App\Models\User;
use App\Models\UserAssignment;
use Illuminate\Http\Request;

class AdminTasksAssignmentController extends Controller
{
    public function index()
    {
        $tasks = TasksAssignment::with('leader')->get();
        return view('admin.tasks.index', compact('tasks'));
    }

    public function create()
    {
        $leaders = User::where('is_admin', 1)->get();
        return view('admin.tasks.create', compact('leaders'));
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

        TasksAssignment::create($validated);
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
}


