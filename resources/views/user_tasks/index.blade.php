@extends('layouts.main')

@section('title', 'User Tasks')

@section('content')
<div class="container">
    <h2 class="my-4">User Tasks</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('user_tasks.create') }}" class="btn btn-primary mb-3">Create Task</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Task Name</th>
                <th>Leader Name</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Target</th>
                <th>Progress</th>
                <th>Percentage</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($userTasks as $userTask)
                <tr>
                    <td>{{ $userTask->task_name }}</td>
                    <td>{{ $userTask->leader_name }}</td>
                    <td>{{ $userTask->start_date }}</td>
                    <td>{{ $userTask->end_date }}</td>
                    <td>{{ $userTask->target }}</td>
                    <td>{{ $userTask->progress }}</td>
                    <td>{{ number_format($userTask->percentage, 2) }}%</td>
                    <td>
                        <a href="{{ route('user_tasks.edit', $userTask->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('user_tasks.destroy', $userTask->id) }}" method="POST" style="display:inline-block;">
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
