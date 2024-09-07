@extends('layouts.main')

@section('title', 'Task Assignments')

@section('content')
<div class="container">
    <h2 class="my-4">Task Assignments</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <a href="{{ route('admin.tasks.create') }}" class="btn btn-primary">Create Task</a>
            <a href="{{ route('admin.superadmin.tasks.index') }}" class="btn btn-secondary">Superadmin View</a>
            <a href="{{ route('admin.tasks.assigned') }}" class="btn btn-success">My Assigned Tasks</a>
        </div>
        <button id="toggle-columns" class="btn btn-info">Show All Columns</button>
    </div>

    <form method="GET" action="{{ route('admin.tasks.index') }}" class="mb-3" id="filterForm">
        <div class="form-row align-items-end">
            <div class="col">
                <label for="tim">Filter by TIM</label>
                <select name="tim_id" id="tim" class="form-control">
                    <option value="">All TIM</option>
                    @foreach($tims as $tim)
                        <option value="{{ $tim->id }}" {{ request('tim_id') == $tim->id ? 'selected' : '' }}>{{ $tim->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <label for="task_name">Filter by Task Name</label>
                <input type="text" name="task_name" id="task_name" class="form-control" value="{{ request('task_name') }}" placeholder="Search by Task Name">
            </div>
        </div>
    </form>

    
    

    <table class="table table-striped">
        <thead>
            <tr>
                <th>TIM</th>
                <th>Leader Name</th>
                <th>Task Name</th>
                <th class="extra-columns">Start Date</th>
                <th class="extra-columns">End Date</th>
                <th class="extra-columns">Target</th>
                <th class="extra-columns">Progress Total</th>
                <th>Percentage</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tasks as $task)
                <tr>
                    <td>{{ $task->tim ? $task->tim->name : 'No TIM Assigned' }}</td>
                    <td>{{ $task->leader->name }}</td>
                    <td>{{ $task->name }}</td>
                    <td class="extra-columns">{{ $task->start_date }}</td>
                    <td class="extra-columns">{{ $task->end_date }}</td>
                    <td class="extra-columns">{{ $task->target }}</td>
                    <td class="extra-columns">{{ $task->progress_total }}</td>
                    <td>{{ number_format(($task->progress_total / $task->target) * 100, 2) }}%</td>
                    <td>
                        <a href="{{ route('admin.tasks.edit', $task->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('admin.tasks.destroy', $task->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                        <a href="{{ route('admin.tasks.assign', $task->id) }}" class="btn btn-info btn-sm">Assign</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $tasks->appends(request()->query())->links() }}

    <div class="d-flex justify-content-end">
        <a href="{{ url('/') }}" class="btn btn-secondary">Back to Homepage</a>
    </div>
</div>

<script>
    // Trigger the form submit when the TIM dropdown changes
    document.getElementById('tim').addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });

    // Trigger the form submit when typing in the Task Name field (after a short delay)
    let typingTimer;
    const doneTypingInterval = 500; // Time in ms (0.5 seconds)

    document.getElementById('task_name').addEventListener('keyup', function() {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(function() {
            document.getElementById('filterForm').submit();
        }, doneTypingInterval);
    });
</script>

@endsection
