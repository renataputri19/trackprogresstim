@extends('layouts.main')

@section('title', 'Task Assignments')

@section('content')
<div class="container">
    <h2 class="my-4">Task Assignments</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Leader Name</th>
                <th>Task Name</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Target</th>
                <th>Progress Total</th>
                <th>Percentage</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tasks as $task)
                <tr>
                    <td>{{ $task->leader->name }}</td>
                    <td>{{ $task->name }}</td>
                    <td>{{ $task->start_date }}</td>
                    <td>{{ $task->end_date }}</td>
                    <td>{{ $task->target }}</td>
                    <td>{{ $task->progress_total }}</td>
                    <td>{{ number_format(($task->progress_total / $task->target) * 100, 2) }}%</td>
                    <td>
                        <a href="{{ route('admin.tasks.edit', $task->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('admin.tasks.destroy', $task->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                        <a href="{{ route('admin.tasks.assign', $task->id) }}" class="btn btn-primary btn-sm">Assign</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
