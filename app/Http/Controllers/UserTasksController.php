<?php

namespace App\Http\Controllers;

use App\Models\UserAssignment;
use App\Models\TasksAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserTasksController extends Controller
{
public function index(Request $request)
{
    $userId = Auth::id();
    $query = UserAssignment::with('task.leader')->where('user_id', $userId);

    if ($request->filled('tim')) {
        $query->whereHas('task', function($q) use ($request) {
            $q->where('tim', $request->tim);
        });
    }

    if ($request->filled('task_name')) {
        $query->whereHas('task', function($q) use ($request) {
            $q->where('name', 'like', '%' . $request->task_name . '%');
        });
    }

    $assignments = $query->orderBy(TasksAssignment::select('tim')->whereColumn('tasks_assignments.id', 'user_assignments.task_id'))
                         ->orderBy(TasksAssignment::select('name')->whereColumn('tasks_assignments.id', 'user_assignments.task_id'))
                         ->paginate(10);

    // Get unique TIMs for the filter
    $tims = TasksAssignment::select('tim')->distinct()->get();

    return view('user.tasks.index', compact('assignments', 'tims'));
}


    public function edit($id)
    {
        $assignment = UserAssignment::findOrFail($id);
        return view('user.tasks.edit', compact('assignment'));
    }

    public function update(Request $request, $id)
    {
        $assignment = UserAssignment::findOrFail($id);

        $validated = $request->validate([
            'progress' => 'required|integer|min:0|max:' . $assignment->target,
        ]);

        $assignment->update([
            'progress' => $validated['progress'],
        ]);

        // Update the task's total progress
        $assignment->task->progress_total = $assignment->task->userAssignments->sum('progress');
        $assignment->task->save();

        return redirect()->route('user.tasks.index')->with('success', 'Task updated successfully');
    }

    public function destroy($id)
    {
        $assignment = UserAssignment::findOrFail($id);
        $assignment->delete();

        return back()->with('success', 'Task deleted successfully');
    }

    public function dashboard()
    {
        return view('user.dashboard');
    }
}

