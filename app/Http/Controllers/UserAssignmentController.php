<?php

namespace App\Http\Controllers;

use App\Models\UserAssignment;
use App\Models\TasksAssignment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAssignmentController extends Controller
{
    public function index()
    {
        $userAssignments = UserAssignment::with('task', 'user')
            ->where('user_id', Auth::id())
            ->paginate(10);
        return view('user.assignments.index', compact('userAssignments'));
    }

    public function create()
    {
        $tasks = TasksAssignment::all();
        $users = User::all();
        return view('admin.assignments.create', compact('tasks', 'users'));
    }

    public function store(Request $request, $taskId)
    {
        $task = TasksAssignment::findOrFail($taskId);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'user_target' => 'required|integer|min:1',
        ]);

        UserAssignment::create([
            'task_id' => $task->id,
            'user_id' => $validated['user_id'],
            'target' => $validated['user_target'],
            'progress' => 0,
        ]);

        return redirect()->route('admin.tasks.assign', $taskId)->with('success', 'User assigned successfully');
    }

    public function edit($id)
    {
        $assignment = UserAssignment::findOrFail($id);
        $users = User::all();
        return view('admin.assignments.edit', compact('assignment', 'users'));
    }

    public function update(Request $request, $id)
    {
        $assignment = UserAssignment::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'user_target' => 'required|integer|min:1',
            'progress' => 'required|integer|min:0|max:' . $assignment->target,
        ]);

        $assignment->update([
            'user_id' => $validated['user_id'],
            'target' => $validated['user_target'],
            'progress' => $validated['progress'],
        ]);

        // Update the task's total progress
        $assignment->task->progress_total = $assignment->task->userAssignments->sum('progress');
        $assignment->task->save();

        return redirect()->route('admin.tasks.assigned', $assignment->task_id)->with('success', 'Assignment updated successfully');
    }

    public function destroy($id)
    {
        $assignment = UserAssignment::findOrFail($id);
        $assignment->delete();

        return back()->with('success', 'User assignment removed successfully');
    }
}
