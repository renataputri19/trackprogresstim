@extends('layouts.main')

@section('title', 'Edit Admin Task')

@section('content')
<div class="container">
    <h2 class="my-4">Edit Admin Task</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin_tasks.update', $adminTask->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="task_name">Task Name</label>
            <input type="text" class="form-control" id="task_name" name="task_name" value="{{ $adminTask->task_name }}" required>
        </div>
        <div class="form-group">
            <label for="start_date">Start Date</label>
            <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $adminTask->start_date }}" required>
        </div>
        <div class="form-group">
            <label for="end_date">End Date</label>
            <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $adminTask->end_date }}" required>
        </div>
        <div class="form-group">
            <label for="target">Target</label>
            <input type="number" class="form-control" id="target" name="target" value="{{ $adminTask->target }}" required>
        </div>
        <div class="form-group">
            <label for="progress">Progress</label>
            <input type="number" class="form-control" id="progress" name="progress" value="{{ $adminTask->progress }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection
