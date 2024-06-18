@extends('layouts.main')

@section('title', 'Create Task')

@section('content')
<div class="container">
    <h2 class="my-4">Create Task</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.tasks.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="tim">TIM</label>
            <select class="form-control" id="tim" name="tim">
                <option value="">Select TIM</option>
                <option value="SUBBAGIAN UMUM">SUBBAGIAN UMUM</option>
                <option value="TIM SOSIAL">TIM SOSIAL</option>
                <option value="TIM PRODUKSI">TIM PRODUKSI</option>
                <option value="TIM DISTRIBUSI">TIM DISTRIBUSI</option>
                <option value="TIM NERWILIS">TIM NERWILIS</option>
                <option value="TIM PENGOLAHAN DAN IT">TIM PENGOLAHAN DAN IT</option>
            </select>
        </div>
        <div class="form-group">
            <label for="leader_name">Leader Name</label>
            <input type="text" class="form-control" id="leader_name" name="leader_name" value="{{ $user->name }}" readonly>
            <input type="hidden" name="leader_id" value="{{ $user->id }}">
        </div>
        <div class="form-group">
            <label for="name">Task Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
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
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection
