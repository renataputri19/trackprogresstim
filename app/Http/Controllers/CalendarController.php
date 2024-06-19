<?php

namespace App\Http\Controllers;

use App\Models\TasksAssignment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function publicCalendarEvents(Request $request)
    {
        $query = TasksAssignment::with('userAssignments');
    
        if ($request->has('tim') && $request->tim) {
            $query->where('tim', $request->tim);
        }
    
        $tasks = $query->get();
        
        $events = [];
    
        foreach ($tasks as $task) {
            $progressPercentage = ($task->progress_total / $task->target) * 100;
    
            $events[] = [
                'title' => $task->name,
                'start' => $task->start_date,
                'end' => Carbon::parse($task->end_date)->addDay()->format('Y-m-d'),
                'color' => '#007bff',
                'extendedProps' => [
                    'progress' => $progressPercentage
                ]
            ];
        }
    
        return response()->json($events);
    }
}
