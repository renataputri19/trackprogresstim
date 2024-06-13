<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdminTask;
use Illuminate\Support\Facades\Auth; // Ensure this is included

class AdminTaskController extends Controller
{
    public function index()
    {
        $adminTasks = AdminTask::all();
        return view('admin_tasks.index', compact('adminTasks'));
    }

    public function create()
    {
        return view('admin_tasks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'task_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'target' => 'required|integer',
            'progress' => 'integer',
        ]);

        $adminTask = new AdminTask($request->all());
        $adminTask->leader_name = Auth::user()->name; // Set the leader name to the authenticated user's name
        $adminTask->save();

        return redirect()->route('admin_tasks.index')->with('success', 'Task created successfully!');
    }

    public function edit(AdminTask $adminTask)
    {
        return view('admin_tasks.edit', compact('adminTask'));
    }

    public function update(Request $request, AdminTask $adminTask)
    {
        $request->validate([
            'task_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'target' => 'required|integer',
            'progress' => 'integer',
        ]);

        $adminTask->update($request->all());

        return redirect()->route('admin_tasks.index')->with('success', 'Task updated successfully!');
    }

    public function destroy(AdminTask $adminTask)
    {
        $adminTask->delete();

        return redirect()->route('admin_tasks.index')->with('success', 'Task deleted successfully!');
    }
}



