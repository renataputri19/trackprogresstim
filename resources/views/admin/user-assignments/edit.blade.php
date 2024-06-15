@extends('layouts.main')

@section('title', 'Edit User Assignment')

@section('content')
<div class="container">
    <h2 class="my-4">Edit User Assignment for Task: {{ $assignment->task->name }}</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.user-assignments.update', $assignment->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="user_id">User</label>
            <select name="user_id" id="user_id" class="form-control select2">
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ $assignment->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="user_target">Target for this person</label>
            <input type="number" name="user_target" id="user_target" class="form-control" value="{{ $assignment->target }}" required>
        </div>
        <div class="form-group">
            <label for="progress">Progress</label>
            <input type="number" name="progress" id="progress" class="form-control" value="{{ $assignment->progress }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
@endsection
