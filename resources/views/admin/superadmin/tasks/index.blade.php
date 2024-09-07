@extends('layouts.main')

@section('title', 'All Task Assignments')

@section('content')
<div class="container">
    <h2 class="my-4">All Task Assignments</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <a href="{{ route('admin.superadmin.tasks.create') }}" class="btn btn-primary">Create Task</a>
        </div>
        <button id="toggle-columns" class="btn btn-info">Show All Columns</button>
    </div>

    <form method="GET" action="{{ route('admin.superadmin.tasks.index') }}" class="mb-3" id="superadminFilterForm">
        <div class="form-row align-items-end">
            <div class="col">
                <label for="tim">Filter by TIM</label>
                <select name="tim_id" id="superadmin_tim" class="form-control">
                    <option value="">All TIM</option>
                    @foreach($tims as $tim)
                        <option value="{{ $tim->id }}" {{ request('tim_id') == $tim->id ? 'selected' : '' }}>{{ $tim->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <label for="task_name">Filter by Task Name</label>
                <input type="text" name="task_name" id="superadmin_task_name" class="form-control" value="{{ request('task_name') }}" placeholder="Search by Task Name">
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
                        <a href="{{ route('admin.superadmin.tasks.edit', ['task' => $task->id]) }}" class="btn btn-primary">Edit</a>
                        <form action="{{ route('admin.superadmin.tasks.destroy', ['task' => $task->id, 'superadmin' => true]) }}" method="POST" style="display:inline-block;">
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
        <a href="{{ route('admin.tasks.index') }}" class="btn btn-secondary">Back</a>
    </div>
</div>

<script>
    // Trigger the form submit when the TIM dropdown changes (Superadmin View)
    document.getElementById('superadmin_tim').addEventListener('change', function() {
        document.getElementById('superadminFilterForm').submit();
    });

    // Trigger the form submit when typing in the Task Name field (Superadmin View)
    let superadminTypingTimer;
    const superadminDoneTypingInterval = 500; // Time in ms (0.5 seconds)

    document.getElementById('superadmin_task_name').addEventListener('keyup', function() {
        clearTimeout(superadminTypingTimer);
        superadminTypingTimer = setTimeout(function() {
            document.getElementById('superadminFilterForm').submit();
        }, superadminDoneTypingInterval);
    });
</script>
@endsection
