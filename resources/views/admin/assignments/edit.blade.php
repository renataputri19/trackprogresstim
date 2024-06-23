@extends('layouts.main')

@section('title', 'Edit User Assignment')

@section('content')
<div class="container">
    <h2 class="my-4">Edit Assignment for Task: {{ $assignment->task->name }}</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.assignments.update', $assignment->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="user_id">Person</label>
            <select name="user_id" id="user_id" class="form-control select2" required>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" @if($user->id == $assignment->user_id) selected @endif>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="target">Target</label>
            <input type="number" class="form-control" id="target" name="target" value="{{ $assignment->task->target }}" readonly>
        </div>
        <div class="form-group">
            <label for="user_target">Target This Person</label>
            <input type="number" class="form-control" id="user_target" name="user_target" value="{{ $assignment->target }}" required>
        </div>
        <div class="form-group">
            <label for="progress">Progress</label>
            <input type="number" class="form-control" id="progress" name="progress" value="{{ $assignment->progress }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
@endsection
