@extends('layouts.main')

@section('title', 'Admin Tasks')

@section('content')
<div class="container">
    <h2 class="my-4">Admin Tasks</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('admin_tasks.create') }}" class="btn btn-primary mb-3">Create Task</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Task Name</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Target</th>
                <th>Progress</th>
                <th>Percentage</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($adminTasks as $adminTask)
                <tr>
                    <td>{{ $adminTask->task_name }}</td>
                    <td>{{ $adminTask->start_date }}</td>
                    <td>{{ $adminTask->end_date }}</td>
                    <td>{{ $adminTask->target }}</td>
                    <td>{{ $adminTask->progress }}</td>
                    <td>{{ number_format($adminTask->percentage, 2) }}%</td>
                    <td>
                        <a href="{{ route('admin_tasks.edit', $adminTask->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('admin_tasks.destroy', $adminTask->id) }}" method="POST" style="display:inline-block;">
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
