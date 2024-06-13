<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaskAssignment;
use App\Models\AdminTask;
use App\Models\User;

class TaskAssignmentController extends Controller
{
    public function index()
    {
        $taskAssignments = TaskAssignment::all();
        return view('task_assignments.index', compact('taskAssignments'));
    }

    public function create()
    {
        $adminTasks = AdminTask::all();
        $users = User::all();
        return view('task_assignments.create', compact('adminTasks', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'task_name' => 'required|string|max:255',
            'leader_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'user_id' => 'required|integer',
            'target' => 'required|integer',
        ]);

        TaskAssignment::create($request->all());

        return redirect()->route('task_assignments.index')->with('success', 'Task assignment created successfully!');
    }

    public function edit(TaskAssignment $taskAssignment)
    {
        $adminTasks = AdminTask::all();
        $users = User::all();
        return view('task_assignments.edit', compact('taskAssignment', 'adminTasks', 'users'));
    }

    public function update(Request $request, TaskAssignment $taskAssignment)
    {
        $request->validate([
            'task_name' => 'required|string|max:255',
            'leader_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'user_id' => 'required|integer',
            'target' => 'required|integer',
        ]);

        $taskAssignment->update($request->all());

        return redirect()->route('task_assignments.index')->with('success', 'Task assignment updated successfully!');
    }

    public function destroy(TaskAssignment $taskAssignment)
    {
        $taskAssignment->delete();

        return redirect()->route('task_assignments.index')->with('success', 'Task assignment deleted successfully!');
    }
}
