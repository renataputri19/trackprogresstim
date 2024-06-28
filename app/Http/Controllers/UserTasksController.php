<?php

namespace App\Http\Controllers;

use App\Models\UserAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserTasksController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $assignments = UserAssignment::with('task.leader')->where('user_id', $userId)->paginate(10);
        return view('user.tasks.index', compact('assignments'));
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
}

