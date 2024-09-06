@extends('layouts.main')

@section('title', 'Edit Task')

@section('content')
<div class="container">
    <h2 class="my-4">Edit Task</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="taskForm" action="{{ route('admin.tasks.update', $task->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="tim_id">TIM</label>
            <select class="form-control" id="tim_id" name="tim_id" required>
                <option value="" disabled>Select TIM</option>
                @foreach($tims as $tim)
                    <option value="{{ $tim->id }}" {{ $task->tim_id == $tim->id ? 'selected' : '' }}>{{ $tim->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="leader_name">Leader Name</label>
            <input type="text" class="form-control" id="leader_name" name="leader_name" value="{{ $task->leader->name }}" readonly>
            <input type="hidden" name="leader_id" value="{{ $task->leader->id }}">
        </div>
        <div class="form-group">
            <label for="name">Task Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $task->name }}" required>
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
        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="{{ route('admin.tasks.index') }}" class="btn btn-secondary">Back</a>
        
    </form>
</div>
@endsection
