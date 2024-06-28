@extends('layouts.main')

@section('title', 'Assign Users to Task')

@section('content')
<div class="container">
    <h2 class="my-4">Assign Users to Task: {{ $task->name }}</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.assignments.store', $task->id) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="user_id">Person</label>
            <select name="user_id" id="user_id" class="form-control select2" required>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
            {{ $users->appends(['assignments_page' => $assignments->currentPage()])->links() }}
        </div>
        <div class="form-group">
            <label for="target">Target</label>
            <input type="number" class="form-control" id="target" name="target" value="{{ $task->target }}" readonly>
        </div>
        <div class="form-group">
            <label for="user_target">Target This Person</label>
            <input type="number" class="form-control" id="user_target" name="user_target" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

    <h3 class="my-4">Current Assignments</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Person</th>
                <th>Target</th>
                <th>Progress</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assignments as $assignment)
                <tr>
                    <td>{{ $assignment->user->name }}</td>
                    <td>{{ $assignment->target }}</td>
                    <td>{{ $assignment->progress }}</td>
                    <td>
                        <a href="{{ route('admin.assignments.edit', $assignment->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('admin.assignments.destroy', $assignment->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $assignments->appends(['users_page' => $users->currentPage()])->links() }}

    <div class="d-flex justify-content-end">
        <a href="{{ route('admin.tasks.index') }}" class="btn btn-secondary">Back</a>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
@endsection
