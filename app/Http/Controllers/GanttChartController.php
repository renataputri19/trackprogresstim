<?php

namespace App\Http\Controllers;

use App\Models\TasksAssignment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GanttChartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    // Store a new task
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
     * Display the specified resource.
     */
    public function show(TasksAssignment $tasksAssignment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TasksAssignment $tasksAssignment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    // Update an existing task
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
     * Remove the specified resource from storage.
     */
    // Delete a task
    public function destroy($id)
    {
        $task = TasksAssignment::find($id);
        $task->delete();

        return response()->json([
            "action" => "deleted"
        ]);
    }

    
}
