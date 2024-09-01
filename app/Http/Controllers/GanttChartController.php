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
    public function store(Request $request)
    {
        //
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
    public function update(Request $request, TasksAssignment $tasksAssignment)
    {

        if ($tasksAssignment) {
            $tasksAssignment->name = $request->text;
            $tasksAssignment->start_date = $request->start_date;
            $tasksAssignment->end_date = Carbon::parse($request->start_date)->addDays($request->duration)->format('Y-m-d');
            $tasksAssignment->progress_total = $request->progress * $tasksAssignment->target; // Update total progress
            $tasksAssignment->save();
    
            return response()->json([
                "action"=> "updated"
            ]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Task not found']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TasksAssignment $tasksAssignment)
    {
        //
    }
}
