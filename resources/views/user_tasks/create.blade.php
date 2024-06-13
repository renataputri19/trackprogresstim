@extends('layouts.main')

@section('title', 'Create User Task')

@section('content')
<div class="container">
    <h2 class="my-4">Create User Task</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('user_tasks.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="task_name">Task Name</label>
            <select class="form-control" id="task_name" name="task_name" required>
                @foreach($adminTasks as $adminTask)
                    <option value="{{ $adminTask->task_name }}">{{ $adminTask->task_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="leader_name">Leader Name</label>
            <input type="text" class="form-control" id="leader_name" name="leader_name" value="{{ Auth::user()->name }}" readonly>
        </div>
        <div class="form-group">
            <label for="start_date">Start Date</label>
            <input type="date" class="form-control" id="start_date" name="start_date" required>
        </div>
        <div class="form-group">
            <label for="end_date">End Date</label>
            <input type="date" class="form-control" id="end_date" name="end_date" required>
        </div>
        <div class="form-group">
            <label for="target">Target</label>
            <input type="number" class="form-control" id="target" name="target" required>
        </div>
        <div class="form-group">
            <label for="progress">Progress</label>
            <input type="number" class="form-control" id="progress" name="progress" value="0" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection
