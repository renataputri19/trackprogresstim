@extends('layouts.main')

@section('title', 'My Tasks')

@section('content')
<div class="container">
    <h2 class="my-4">My Tasks</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Task Name</th>
                <th>Leader Name</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Target Total</th>
                <th>Your Target</th>
                <th>Progress</th>
                <th>Percentage</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assignments as $assignment)
                <tr>
                    <td>{{ $assignment->task->name }}</td>
                    <td>{{ $assignment->task->leader->name }}</td>
                    <td>{{ $assignment->task->start_date }}</td>
                    <td>{{ $assignment->task->end_date }}</td>
                    <td>{{ $assignment->task->target }}</td>
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
</div>
@endsection
