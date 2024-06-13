<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserTask;
use App\Models\AdminTask;
use Illuminate\Support\Facades\Auth; // Add this line

class UserTaskController extends Controller
{
    public function index()
    {
        $userTasks = UserTask::where('leader_name', Auth::user()->name)->get();
        return view('user_tasks.index', compact('userTasks'));
    }

    public function create()
    {
        $adminTasks = AdminTask::all();
        return view('user_tasks.create', compact('adminTasks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'task_name' => 'required|string|max:255',
            'leader_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'target' => 'required|integer',
            'progress' => 'integer',
        ]);

        UserTask::create($request->all());

        return redirect()->route('user_tasks.index')->with('success', 'Task created successfully!');
    }

    public function edit(UserTask $userTask)
    {
        return view('user_tasks.edit', compact('userTask'));
    }

    public function update(Request $request, UserTask $userTask)
    {
        $request->validate([
            'task_name' => 'required|string|max:255',
            'leader_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'target' => 'required|integer',
            'progress' => 'integer',
        ]);

        $userTask->update($request->all());

        return redirect()->route('user_tasks.index')->with('success', 'Task updated successfully!');
    }

    public function destroy(UserTask $userTask)
    {
        $userTask->delete();

        return redirect()->route('user_tasks.index')->with('success', 'Task deleted successfully!');
    }
}
