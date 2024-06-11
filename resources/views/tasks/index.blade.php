@extends('layouts.main')

@section('title', 'Task Management')

@section('content')
<div class="container">
    <h2 class="my-4">Task Management</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form for adding tasks -->
    <form id="taskForm" action="{{ route('tasks.store') }}" method="POST" class="mb-5">
        @csrf
        <div class="form-group">
            <label for="name">Task Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="team_leader">Team Leader</label>
            <input type="text" class="form-control" id="team_leader" name="team_leader" required>
        </div>
        <div class="form-group">
            <label for="start_date">Start Date</label>
            <input type="date" class="form-control" id="start_date" name="start_date" required>
        </div>
        <div class="form-group">
            <label for="end_date">End Date</label>
            <input type="date" class="form-control" id="end_date" name="end_date" required>
            <span id="end_date_error" class="text-danger" style="display: none;">The end date must be after or equal to the start date.</span>
        </div>
        <div class="form-group">
            <label for="target">Target</label>
            <input type="number" class="form-control" id="target" name="target" required>
        </div>
        <div class="form-group">
            <label for="progress">Progress</label>
            <input type="number" class="form-control" id="progress" name="progress" required>
            <span id="progress_error" class="text-danger" style="display: none;">The progress cannot be greater than the target.</span>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>


    

    <h3 class="mb-4">Tasks List</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Task Name</th>
                <th>Team Leader</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Target</th>
                <th>Progress</th>
                <th>Percentage</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tasks as $task)
                <tr>
                    <td>{{ $task->name }}</td>
                    <td>{{ $task->team_leader }}</td>
                    <td>{{ $task->start_date }}</td>
                    <td>{{ $task->end_date }}</td>
                    <td>{{ $task->target }}</td>
                    <td>{{ $task->progress }}</td>
                    <td>{{ number_format($task->percentage, 2) }}%</td>
                    <td>
                        <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Calendar to display tasks -->
<div id="calendar"></div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const startDate = document.getElementById('start_date');
        const endDate = document.getElementById('end_date');
        const endDateError = document.getElementById('end_date_error');
        const target = document.getElementById('target');
        const progress = document.getElementById('progress');
        const progressError = document.getElementById('progress_error');
        const taskForm = document.getElementById('taskForm');

        startDate.addEventListener('input', function() {
            if (new Date(endDate.value) < new Date(startDate.value)) {
                endDateError.style.display = 'block';
                taskForm.querySelector('button[type="submit"]').disabled = true;
            } else {
                endDateError.style.display = 'none';
                taskForm.querySelector('button[type="submit"]').disabled = false;
            }
        });
        
        endDate.addEventListener('input', function() {
            if (new Date(endDate.value) < new Date(startDate.value)) {
                endDateError.style.display = 'block';
                taskForm.querySelector('button[type="submit"]').disabled = true;
            } else {
                endDateError.style.display = 'none';
                taskForm.querySelector('button[type="submit"]').disabled = false;
            }
        });

        progress.addEventListener('input', function() {
            if (parseInt(progress.value) > parseInt(target.value)) {
                progressError.style.display = 'block';
                taskForm.querySelector('button[type="submit"]').disabled = true;
            } else {
                progressError.style.display = 'none';
                taskForm.querySelector('button[type="submit"]').disabled = false;
            }
        });

        target.addEventListener('input', function() {
            if (parseInt(progress.value) > parseInt(target.value)) {
                progressError.style.display = 'block';
                taskForm.querySelector('button[type="submit"]').disabled = true;
            } else {
                progressError.style.display = 'none';
                taskForm.querySelector('button[type="submit"]').disabled = false;
            }
        });

        // Initialize FullCalendar
        let calendarEl = document.getElementById('calendar');

        if (calendarEl) {
            let calendar = new FullCalendar.Calendar(calendarEl, {
                plugins: [ FullCalendar.dayGridPlugin, FullCalendar.interactionPlugin, FullCalendar.timeGridPlugin, FullCalendar.listPlugin ],
                initialView: 'timeGridWeek', // You can change this to dayGridMonth, timeGridWeek, listWeek, etc.
                events: '/tasks/events', // URL to fetch events
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listWeek'
                },
                dateClick: function(info) {
                    alert('Clicked on: ' + info.dateStr);
                }
            });
            calendar.render();
        }
    });
</script>
@endsection
