@extends('layouts.main')

@section('title', 'Edit User Task')

@section('content')
<div class="container">
    <h2 class="my-4">Edit User Task</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('user_tasks.update', $userTask->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="task_name">Task Name</label>
            <select class="form-control" id="task_name" name="task_name" required>
                @foreach($adminTasks as $adminTask)
                    <option value="{{ $adminTask->task_name }}" @if($adminTask->task_name == $userTask->task_name) selected @endif>{{ $adminTask->task_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="leader_name">Leader Name</label>
            <input type="text" class="form-control" id="leader_name" name="leader_name" value="{{ $userTask->leader_name }}" readonly>
        </div>
        <div class="form-group">
            <label for="start_date">Start Date</label>
            <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $userTask->start_date }}" required>
        </div>
        <div class="form-group">
            <label for="end_date">End Date</label>
            <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $userTask->end_date }}" required>
        </div>
        <div class="form-group">
            <label for="target">Target</label>
            <input type="number" class="form-control" id="target" name="target" value="{{ $userTask->target }}" required>
        </div>
        <div class="form-group">
            <label for="progress">Progress</label>
            <input type="number" class="form-control" id="progress" name="progress" value="{{ $userTask->progress }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button
