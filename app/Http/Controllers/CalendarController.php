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
        
        $colors = ['#66b3ff' ,'#9a9cff','#8cd98c'  ];
        $events = [];
        $lastColorIndex = -1;
    
        foreach ($tasks as $task) {
            $progressPercentage = ($task->progress_total / $task->target) * 100;
    
            // Assign a color based on the task ID to ensure consistency
            $colorIndex = $task->id % count($colors);
            
            // Ensure no duplicate colors next to each other
            if ($colorIndex == $lastColorIndex) {
                $colorIndex = ($colorIndex + 1) % count($colors);
            }
            
            $lastColorIndex = $colorIndex;
            $color = $colors[$colorIndex];
    
            $events[] = [
                'title' => $task->name,
                'start' => $task->start_date,
                'end' => Carbon::parse($task->end_date)->addDay()->format('Y-m-d'),
                'color' => $color,
                'extendedProps' => [
                    'progress' => $progressPercentage,
                    'originalStart' => $task->start_date,
                ]
            ];
        }
    
    
        return response()->json($events);
    }
}
