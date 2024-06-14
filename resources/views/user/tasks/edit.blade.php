@extends('layouts.main')

@section('title', 'Edit Task')

@section('content')
<div class="container">
    <h2 class="my-4">Edit Task: {{ $assignment->task->name }}</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('user.tasks.update', $assignment->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="progress">Progress</label>
            <input type="number" class="form-control" id="progress" name="progress" value="{{ $assignment->progress }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection
