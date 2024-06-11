<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::all();
        return view('tasks.index', compact('tasks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'team_leader' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'target' => 'required|integer|min:1',
            'progress' => 'required|integer|min:0|max:'.$request->input('target'),
        ]);

        Task::create($request->all());

        return redirect()->back()->with('success', 'Task created successfully!');
    }

    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'team_leader' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'target' => 'required|integer|min:1',
            'progress' => 'required|integer|min:0|max:'.$request->input('target'),
        ]);

        $task->update($request->all());

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully!');
    }

    // public function calendar()
    // {
    //     // Fetch tasks for the logged-in user and format them for FullCalendar
    //     $tasks = Task::where('user_id', Auth::id())->get(['name as title', 'start_date as start', 'end_date as end']);
    //     return response()->json($tasks);
    // }
}
