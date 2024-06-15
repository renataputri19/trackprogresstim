@extends('layouts.main')

@section('title', 'Assign User to Task')

@section('content')
<div class="container">
    <h2 class="my-4">Assign User to Task: {{ $task->name }}</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.tasks.assign.store', $task->id) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="user_id">User</label>
            <select name="user_id" id="user_id" class="form-control select2">
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="user_target">Target for this person</label>
            <input type="number" name="user_target" id="user_target" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="progress">Progress</label>
            <input type="number" name="progress" id="progress" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Assign</button>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
@endsection
