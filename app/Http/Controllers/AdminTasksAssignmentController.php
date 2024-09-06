<?php

namespace App\Http\Controllers;

use App\Models\TasksAssignment;
use App\Models\Tim; // Make sure to import the Tim model
use App\Models\User;
use App\Models\UserAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminTasksAssignmentController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $query = TasksAssignment::where('leader_id', $userId);
        
        if ($request->filled('tim_id')) {
            $query->where('tim_id', $request->tim_id);
        }

        if ($request->filled('task_name')) {
            $query->where('name', 'like', '%' . $request->task_name . '%');
        }
    
        $tasks = $query->orderBy('tim_id')->orderBy('name')->paginate(10);

        // Fetch all TIMs for filtering
        $tims = Tim::all();
    
        return view('admin.tasks.index', compact('tasks', 'tims'));
    }

    public function create()
    {
        $user = Auth::user();
        $tims = Tim::all(); // Fetch all TIMs to show in the dropdown for creating tasks
        return view('admin.tasks.create', compact('user', 'tims'));
    }

    public function store(Request $request)
    {
        // Dump the entire request to check what is being sent
        // dd($request->all());
        
        $validated = $request->validate([
            'leader_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'target' => 'required|integer|min:1',
            'tim_id' => 'required|exists:tims,id', // Ensure tim_id is validated
        ]);

        // Log the validated data to check if tim_id is passed
        // dd($validated);
    
        TasksAssignment::create([
            'leader_id' => $validated['leader_id'],
            'name' => $validated['name'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'target' => $validated['target'],
            'progress_total' => 0,
            'tim_id' => $validated['tim_id'], // Save tim_id here
        ]);
    
        return redirect()->route('admin.tasks.index')->with('success', 'Task created successfully');
    }
    

    public function edit(TasksAssignment $task)
    {
        $leaders = User::where('is_admin', 1)->get();
        $tims = Tim::all(); // Fetch all TIMs to edit
        return view('admin.tasks.edit', compact('task', 'leaders', 'tims'));
    }

    public function update(Request $request, TasksAssignment $task)
    {
        $validated = $request->validate([
            'leader_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'target' => 'required|integer|min:1',
            'tim_id' => 'required|exists:tims,id', // Update tim_id validation
        ]);
    
        $task->update($validated);
    
        return redirect()->route('admin.tasks.index')->with('success', 'Task updated successfully');
    }

    public function destroy(TasksAssignment $task, Request $request)
    {
        $task->delete();
    
        return redirect()->route('admin.tasks.index')->with('success', 'Task deleted successfully');
    }

    public function assign($taskId)
    {
        $task = TasksAssignment::with('userAssignments.user')->findOrFail($taskId);
        $users = User::all(); // Fetch all users for dropdown
        $assignments = $task->userAssignments()->paginate(10, ['*'], 'assignments_page');
        return view('admin.assignments.create', compact('task', 'users', 'assignments'));
    }

    public function superadminIndex(Request $request)
    {
        $query = TasksAssignment::with('leader', 'userAssignments.user');
        
        if ($request->filled('tim_id')) {
            $query->where('tim_id', $request->tim_id);
        }
    
        if ($request->filled('task_name')) {
            $query->where('name', 'like', '%' . $request->task_name . '%');
        }
    
        $tasks = $query->orderBy('tim_id')->orderBy('name')->paginate(10);

        // Fetch TIMs for filtering
        $tims = Tim::all();
    
        return view('admin.superadmin.tasks.index', compact('tasks', 'tims'));
    }

    public function superadminCreate()
    {
        $leaders = User::where('is_admin', 1)->get();
        $tims = Tim::all(); // Fetch all TIMs to show in the dropdown for creating tasks
        return view('admin.superadmin.tasks.create', compact('leaders', 'tims'));
    }

    public function superadminStore(Request $request)
    {
        $validated = $request->validate([
            'leader_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'target' => 'required|integer|min:1',
            'tim_id' => 'required|exists:tims,id',
        ]);

        TasksAssignment::create([
            'leader_id' => $validated['leader_id'],
            'name' => $validated['name'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'target' => $validated['target'],
            'progress_total' => 0,
            'tim_id' => $validated['tim_id'], // Update to use the tim_id
        ]);

        return redirect()->route('admin.superadmin.tasks.index')->with('success', 'Task created successfully');
    }

    public function superadminEdit(TasksAssignment $task)
    {
        $leaders = User::where('is_admin', 1)->get();
        $tims = Tim::all(); // Fetch all TIMs to edit
        return view('admin.superadmin.tasks.edit', compact('task', 'leaders', 'tims'));
    }

    public function superadminUpdate(Request $request, TasksAssignment $task)
    {
        $validated = $request->validate([
            'leader_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'target' => 'required|integer|min:1',
            'tim_id' => 'required|exists:tims,id', // Update tim_id validation
        ]);

        $task->update($validated);

        return redirect()->route('admin.superadmin.tasks.index')->with('success', 'Task updated successfully');
    }
       

    public function assignedTasks(Request $request)
    {
        $user = Auth::user();
        $query = UserAssignment::where('user_id', $user->id)->with('task');
    
        if ($request->filled('tim_id')) {
            $query->whereHas('task', function($q) use ($request) {
                $q->where('tim_id', $request->tim_id);
            });
        }
    
        if ($request->filled('task_name')) {
            $query->whereHas('task', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->task_name . '%');
            });
        }
    
        $assignments = $query->orderBy(TasksAssignment::select('tim_id')->whereColumn('tasks_assignments.id', 'user_assignments.task_id'))
                             ->orderBy(TasksAssignment::select('name')->whereColumn('tasks_assignments.id', 'user_assignments.task_id'))
                             ->paginate(10);
    
        // Get unique TIMs for the filter
        $tims = Tim::all();
    
        return view('admin.assigned-tasks.index', compact('assignments', 'tims'));
    }
    
    
    
    
    public function calendarEvents(Request $request)
    {
        $query = TasksAssignment::with('userAssignments');
    
        if ($request->has('tim_id') && $request->tim_id) {
            $query->where('tim_id', $request->tim_id);
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
    
    
    public function ganttChartEvents(Request $request)
    {
        // Get tasks, grouped by TIM
        $tasks = TasksAssignment::with('userAssignments')
            ->when($request->filled('tim_id'), function($query) use ($request) {
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
    
    
    
    
    
    // public function updateGantt(Request $request)
    // {
    //     $task = TasksAssignment::find($request->id);
    //     if ($task) {
    //         $task->name = $request->text;
    //         $task->start_date = $request->start_date;
    //         $task->end_date = Carbon::parse($request->start_date)->addDays($request->duration)->format('Y-m-d');
    //         $task->progress_total = $request->progress * $task->target; // Update total progress
    //         $task->save();
    
    //         return response()->json(['status' => 'success']);
    //     } else {
    //         return response()->json(['status' => 'error', 'message' => 'Task not found']);
    //     }
    // }
    
    
    
    
    
    
    public function dashboard()
    {
        return view('admin.dashboard');
    }
    
    

}


