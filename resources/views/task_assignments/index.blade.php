@extends('layouts.main')

@section('title', 'Task Assignments')

@section('content')
<div class="container">
    <h2 class="my-4">Task Assignments</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('task_assignments.create') }}" class="btn btn-primary mb-3">Create Task Assignment</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Task Name</th>
                <th>Leader Name</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>User</th>
                <th>Target</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($taskAssignments as $taskAssignment)
                <tr>
                    <td>{{ $taskAssignment->task_name }}</td>
                    <td>{{ $taskAssignment->leader_name }}</td>
                    <td>{{ $taskAssignment->start_date }}</td>
                    <td>{{ $taskAssignment->end_date }}</td>
                    <td>{{ $taskAssignment->user->name }}</td>
                    <td>{{ $taskAssignment->target }}</td>
                    <td>
                        <a href="{{ route('task_assignments.edit', $taskAssignment->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('task_assignments.destroy', $taskAssignment->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
