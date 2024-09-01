<?php

namespace App\Http\Controllers;

use App\Models\TasksAssignment;
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
        
        if ($request->filled('tim')) {
            $query->where('tim', $request->tim);
        }
    
        if ($request->filled('task_name')) {
            $query->where('name', 'like', '%' . $request->task_name . '%');
        }
    
        $tasks = $query->orderBy('tim')->orderBy('name')->paginate(10);
    
        // Get unique TIMs for the filter
        $tims = TasksAssignment::select('tim')->distinct()->get();
    
        return view('admin.tasks.index', compact('tasks', 'tims'));
    }
    
    
    
    public function create()
    {
        $user = Auth::user();
        return view('admin.tasks.create', compact('user'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'leader_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'target' => 'required|integer|min:1',
            'tim' => 'required|string',
        ]);
    
        TasksAssignment::create([
            'leader_id' => $validated['leader_id'],
            'name' => $validated['name'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'target' => $validated['target'],
            'progress_total' => 0,
            'tim' => $validated['tim'],
        ]);
    
        return redirect()->route('admin.tasks.index')->with('success', 'Task created successfully');
    }

    public function edit(TasksAssignment $task)
    {
        $leaders = User::where('is_admin', 1)->get();
        return view('admin.tasks.edit', compact('task', 'leaders'));
    }

    public function update(Request $request, TasksAssignment $task)
    {
        // Print all request parameters
        // dd($request->all());
        
        $validated = $request->validate([
            'leader_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'target' => 'required|integer|min:1',
            'tim' => 'required|string',
        ]);
    
        $task->update($validated);
    
        return redirect()->route('admin.tasks.index')->with('success', 'Task updated successfully');
    }
    

    public function destroy(TasksAssignment $task, Request $request)
    {
        $task->delete();
        
        if ($request->has('superadmin') && $request->superadmin) {
            return redirect()->route('admin.superadmin.tasks.index')->with('success', 'Task deleted successfully');
        }
    
        return redirect()->route('admin.tasks.index')->with('success', 'Task deleted successfully');
    }
    

    public function assign($taskId)
    {
        $task = TasksAssignment::with('userAssignments.user')->findOrFail($taskId);
        $users = User::all(); // Fetch all users without pagination for dropdown
        $assignments = $task->userAssignments()->paginate(10, ['*'], 'assignments_page');
        return view('admin.assignments.create', compact('task', 'users', 'assignments'));
    }
    
    

    // Superadmin functions
    public function superadminIndex(Request $request)
    {
        $query = TasksAssignment::with('leader', 'userAssignments.user');
        
        if ($request->filled('tim')) {
            $query->where('tim', $request->tim);
        }
    
        if ($request->filled('task_name')) {
            $query->where('name', 'like', '%' . $request->task_name . '%');
        }
    
        $tasks = $query->orderBy('tim')->orderBy('name')->paginate(10);
        
        // Get unique TIMs for the filter
        $tims = TasksAssignment::select('tim')->distinct()->get();
    
        return view('admin.superadmin.tasks.index', compact('tasks', 'tims'));
    }
    
    

    public function superadminCreate()
    {
        $leaders = User::where('is_admin', 1)->get();
        return view('admin.superadmin.tasks.create', compact('leaders'));
    }

    public function superadminStore(Request $request)
    {
        $validated = $request->validate([
            'leader_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'target' => 'required|integer|min:1',
            'tim' => 'required|string',
        ]);

        TasksAssignment::create([
            'leader_id' => $validated['leader_id'],
            'name' => $validated['name'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'target' => $validated['target'],
            'progress_total' => 0,
            'tim' => $validated['tim'],
        ]);

        return redirect()->route('admin.superadmin.tasks.index')->with('success', 'Task created successfully');
    }

    public function superadminEdit(TasksAssignment $task)
    {
        $leaders = User::where('is_admin', 1)->get();
        return view('admin.superadmin.tasks.edit', compact('task', 'leaders'));
    }

    public function superadminUpdate(Request $request, TasksAssignment $task)
    {
        $validated = $request->validate([
            'leader_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'target' => 'required|integer|min:1',
            'tim' => 'required|string',
        ]);

        $task->update($validated);

        return redirect()->route('admin.superadmin.tasks.index')->with('success', 'Task updated successfully');
    }
       

    public function assignedTasks(Request $request)
    {
        $user = Auth::user();
        $query = UserAssignment::where('user_id', $user->id)->with('task');
    
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
    
        return view('admin.assigned-tasks.index', compact('assignments', 'tims'));
    }
    
    
    
    public function calendarEvents(Request $request)
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
    
    public function ganttChartEvents(Request $request)
    {
        $query = TasksAssignment::with('userAssignments');
    
        if ($request->has('tim') && $request->tim) {
            $query->where('tim', $request->tim);
        }
    
        $tasks = $query->get();
    
        $ganttTasks = [];
    
        foreach ($tasks as $task) {
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
                'parent' => 0, // Assuming no parent tasks; use appropriate value if you have parent-child relationships
            ];
        }
    
        return response()->json(['data' => $ganttTasks]);
    }
    
    public function updateGantt(Request $request)
    {
        $task = TasksAssignment::find($request->id);
        if ($task) {
            $task->name = $request->text;
            $task->start_date = $request->start_date;
            $task->end_date = Carbon::parse($request->start_date)->addDays($request->duration)->format('Y-m-d');
            $task->progress_total = $request->progress * $task->target; // Update total progress
            $task->save();
    
            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Task not found']);
        }
    }
    
    
    
    
    
    public function dashboard()
    {
        return view('admin.dashboard');
    }
    
    

}


