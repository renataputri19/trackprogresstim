<?php

namespace App\Http\Controllers;

use App\Models\UserAssignment;
use App\Models\TasksAssignment;
use App\Models\Tim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserTasksController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        // Use 'tim_id' for filtering based on TIM
        $query = UserAssignment::with('task.leader', 'task.tim')->where('user_id', $userId);

        // Filter by TIM (using tim_id now)
        if ($request->filled('tim_id')) {
            $query->whereHas('task', function ($q) use ($request) {
                $q->where('tim_id', $request->tim_id);
            });
        }

        // Filter by task name
        if ($request->filled('task_name')) {
            $query->whereHas('task', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->task_name . '%');
            });
        }

        // Order by TIM and task name
        $assignments = $query->orderBy(TasksAssignment::select('tim_id')->whereColumn('tasks_assignments.id', 'user_assignments.task_id'))
            ->orderBy(TasksAssignment::select('name')->whereColumn('tasks_assignments.id', 'user_assignments.task_id'))
            ->paginate(10);

        // Get unique TIMs for the filter (from the new 'tims' table)
        $tims = Tim::all();

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
