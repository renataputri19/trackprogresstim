<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    // Display a listing of the tasks
    public function index()
    {
        // Fetch tasks that belong to the currently authenticated user
        $tasks = Task::where('user_id', Auth::id())->get();
        // Return the view with the tasks data
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

        // Create a new task with the validated data and associate it with the authenticated user
        Task::create([
            'name' => $request->name,
            'team_leader' => $request->team_leader,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'target' => $request->target,
            'progress' => $request->progress,
            'user_id' => Auth::id(), // Associate the task with the currently authenticated user
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Task created successfully!');
    }

    // Show the form for editing the specified task
    public function edit(Task $task)
    {
        // Ensure that the user is authorized to update the task
        $this->authorize('update', $task);
        // Return the view with the task data
        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        // Ensure that the user is authorized to update the task
        $this->authorize('update', $task);
        
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'team_leader' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'target' => 'required|integer|min:1',
            'progress' => 'required|integer|min:0|max:'.$request->input('target'),
        ]);

        $task->update($request->all());

        // Redirect back to the tasks index with a success message
        return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');
    }

    public function destroy(Task $task)
    {
        // Ensure that the user is authorized to delete the task
        $this->authorize('delete', $task);
        // Delete the task
        $task->delete();
        // Redirect back to the tasks index with a success message
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully!');
    }

    public function calendar()
    {
        // Fetch tasks for the logged-in user and format them for FullCalendar
        $tasks = Task::where('user_id', Auth::id())->get(['name as title', 'start_date as start', 'end_date as end']);
        return response()->json($tasks);
    }
}
