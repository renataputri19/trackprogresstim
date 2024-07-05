@extends('layouts.main')

@section('title', 'My Tasks')

@section('content')
<div class="container">
    <h2 class="my-4">My Tasks</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="mb-3">
            <form method="GET" action="{{ route('user.tasks.index') }}">
                <div class="form-row align-items-end">
                    <div class="col">
                        <label for="tim">Filter by TIM</label>
                        <select name="tim" id="tim" class="form-control">
                            <option value="">All TIM</option>
                            @foreach($tims as $tim)
                                <option value="{{ $tim->tim }}" {{ request('tim') == $tim->tim ? 'selected' : '' }}>{{ $tim->tim }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col">
                        <label for="task_name">Filter by Task Name</label>
                        <input type="text" name="task_name" id="task_name" class="form-control" value="{{ request('task_name') }}" placeholder="Search by Task Name">
                    </div>
                    <div class="col">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </div>
            </form>
        </div>
        <button id="toggle-columns" class="btn btn-info">Show All Columns</button>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>TIM</th>
                <th>Task Name</th>
                <th class="extra-columns">Leader Name</th>
                <th class="extra-columns">Start Date</th>
                <th class="extra-columns">End Date</th>
                <th class="extra-columns">Target Total</th>
                <th>Your Target</th>
                <th>Progress</th>
                <th>Percentage</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assignments as $assignment)
                <tr>
                    <td>{{ $assignment->task->tim }}</td>
                    <td>{{ $assignment->task->name }}</td>
                    <td class="extra-columns">{{ $assignment->task->leader->name }}</td>
                    <td class="extra-columns">{{ $assignment->task->start_date }}</td>
                    <td class="extra-columns">{{ $assignment->task->end_date }}</td>
                    <td class="extra-columns">{{ $assignment->task->target }}</td>
                    <td>{{ $assignment->target }}</td>
                    <td>{{ $assignment->progress }}</td>
                    <td>{{ number_format(($assignment->progress / $assignment->target) * 100, 2) }}%</td>
                    <td>
                        <a href="{{ route('user.tasks.edit', $assignment->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('user.tasks.destroy', $assignment->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    {{ $assignments->appends(request()->query())->links() }}
    
    <div class="d-flex justify-content-end">
        <a href="{{ url('/') }}" class="btn btn-secondary">Back to Homepage</a>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
@endsection
