<?php

namespace App\Http\Controllers;

use App\Models\TasksAssignment;
use App\Models\Tim; // Import Tim model
use Carbon\Carbon;
use Illuminate\Http\Request;

class GanttChartController extends Controller
{
    /**
     * Display the Gantt Chart Dashboard with TIM filter.
     */
    public function index()
    {
        // // Fetch all TIMs from the 'tims' table
        // $tims = Tim::all();

        // // Pass the TIMs to the view
        // return view('admin.dashboard', compact('tims'));
    }

    /**
     * Handle the Gantt chart tasks and filtering by TIM.
     */
    public function ganttChartEvents(Request $request)
    {
        // Get tasks, grouped by TIM
        $tasks = TasksAssignment::with('userAssignments')
            ->when($request->filled('tim_id'), function ($query) use ($request) {
                $query->where('tim_id', $request->tim_id);
            })
            ->get()
            ->groupBy('tim_id'); // Group tasks by TIM id

        $ganttTasks = [];

        // Create parent rows for each TIM
        foreach ($tasks as $timId => $timTasks) {
            $tim = Tim::find($timId); // Get the TIM by its id

            // Add TIM group as a parent row with empty date, duration, and progress fields
            $ganttTasks[] = [
                'id' => 'tim_' . $timId, // Unique id for each TIM group
                'text' => $tim->name, // TIM name
                'start_date' => '', // Empty values for parent row
                'duration' => '', // Empty values for parent row
                'progress' => '', // Empty values for parent row
                'parent' => 0, // Parent is 0 for the top-level group (TIM)
                'open' => true // Keep the TIM group expanded
            ];

            // Add tasks under the TIM group
            foreach ($timTasks as $task) {
                $start = Carbon::parse($task->start_date);
                $end = Carbon::parse($task->end_date);
                $duration = $start->diffInDays($end) + 1; // Ensure duration is calculated correctly

                $progressPercentage = ($task->progress_total / $task->target) * 100;

                $ganttTasks[] = [
                    'id' => $task->id, // Unique identifier for the task
                    'text' => $task->name, // Task name
                    'start_date' => $start->format('Y-m-d H:i'), // Ensure the format is 'YYYY-MM-DD HH:mm'
                    'duration' => $duration, // Duration in days
                    'progress' => $progressPercentage / 100, // Progress as a decimal
                    'parent' => 'tim_' . $timId // The task belongs to the TIM parent row
                ];
            }
        }

        return response()->json(['data' => $ganttTasks]);
    }

    /**
     * Store a newly created task.
     */
    public function store(Request $request)
    {
        $task = new TasksAssignment();

        $task->name = $request->text;
        $task->start_date = $request->start_date;
        $task->end_date = Carbon::parse($request->start_date)->addDays($request->duration - 1)->format('Y-m-d'); // Adjusting end date
        $task->progress_total = $request->has('progress') ? $request->progress * $task->target : 0;
        $task->save();

        return response()->json([
            "action" => "inserted",
            "tid" => $task->id
        ]);
    }

    /**
     * Update an existing task.
     */
    public function update($id, Request $request)
    {
        $task = TasksAssignment::find($id);

        $task->name = $request->text;
        $task->start_date = $request->start_date;
        $task->end_date = Carbon::parse($request->start_date)->addDays($request->duration - 1)->format('Y-m-d'); // Adjusting end date
        $task->progress_total = $request->has('progress') ? $request->progress * $task->target : 0;
        $task->save();

        return response()->json([
            "action" => "updated"
        ]);
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy($id)
    {
        $task = TasksAssignment::find($id);
        $task->delete();

        return response()->json([
            "action" => "deleted"
        ]);
    }
}

