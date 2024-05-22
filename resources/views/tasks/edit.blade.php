@extends('layouts.main')

@section('title', 'Edit Task')

@section('content')
<div class="container">
    <h2 class="my-4">Edit Task</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="taskForm" action="{{ route('tasks.update', $task->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Task Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $task->name }}" required>
        </div>
        <div class="form-group">
            <label for="team_leader">Team Leader</label>
            <input type="text" class="form-control" id="team_leader" name="team_leader" value="{{ $task->team_leader }}" required>
        </div>
        <div class="form-group">
            <label for="start_date">Start Date</label>
            <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $task->start_date }}" required>
        </div>
        <div class="form-group">
            <label for="end_date">End Date</label>
            <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $task->end_date }}" required>
            <span id="end_date_error" class="text-danger" style="display: none;">The end date must be after or equal to the start date.</span>
        </div>
        <div class="form-group">
            <label for="target">Target</label>
            <input type="number" class="form-control" id="target" name="target" value="{{ $task->target }}" required>
        </div>
        <div class="form-group">
            <label for="progress">Progress</label>
            <input type="number" class="form-control" id="progress" name="progress" value="{{ $task->progress }}" required>
            <span id="progress_error" class="text-danger" style="display: none;">The progress cannot be greater than the target.</span>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const startDate = document.getElementById('start_date');
        const endDate = document.getElementById('end_date');
        const endDateError = document.getElementById('end_date_error');
        const target = document.getElementById('target');
        const progress = document.getElementById('progress');
        const progressError = document.getElementById('progress_error');
        const taskForm = document.getElementById('taskForm');

        start_date.addEventListener('input', function() {
            if (new Date(endDate.value) < new Date(startDate.value)) {
                endDateError.style.display = 'block';
                taskForm.querySelector('button[type="submit"]').disabled = true;
            } else {
                endDateError.style.display = 'none';
                taskForm.querySelector('button[type="submit"]').disabled = false;
            }
        });
        
        endDate.addEventListener('input', function() {
            if (new Date(endDate.value) < new Date(startDate.value)) {
                endDateError.style.display = 'block';
                taskForm.querySelector('button[type="submit"]').disabled = true;
            } else {
                endDateError.style.display = 'none';
                taskForm.querySelector('button[type="submit"]').disabled = false;
            }
        });

        progress.addEventListener('input', function() {
            if (parseInt(progress.value) > parseInt(target.value)) {
                progressError.style.display = 'block';
                taskForm.querySelector('button[type="submit"]').disabled = true;
            } else {
                progressError.style.display = 'none';
                taskForm.querySelector('button[type="submit"]').disabled = false;
            }
        });

        target.addEventListener('input', function() {
            if (parseInt(progress.value) > parseInt(target.value)) {
                progressError.style.display = 'block';
                taskForm.querySelector('button[type="submit"]').disabled = true;
            } else {
                progressError.style.display = 'none';
                taskForm.querySelector('button[type="submit"]').disabled = false;
            }
        });
    });
</script>
@endsection
