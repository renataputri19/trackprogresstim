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
        </div>
        <button id="toggle-columns" class="btn btn-info">Show All Columns</button>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Task Name</th>
                <th class="extra-columns">TIM</th>
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
                    <td>{{ $assignment->task->name }}</td>
                    <td class="extra-columns">{{ $assignment->task->tim }}</td>
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
    
</div>

<!-- Pagination links -->
{{ $assignments->links() }}

<div class="d-flex justify-content-end">
    <a href="{{ url('/') }}" class="btn btn-secondary">Back to Homepage</a>
</div>
@endsection
