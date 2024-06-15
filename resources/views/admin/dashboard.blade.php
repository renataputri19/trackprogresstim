@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')
<div class="container">
    <h2 class="my-4">Dashboard</h2>
    <div id="calendar"></div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let calendarEl = document.getElementById('calendar');

        let calendar = new FullCalendar.Calendar(calendarEl, {
            plugins: [ 'dayGrid', 'interaction', 'timeGrid', 'list' ],
            initialView: 'dayGridMonth',
            events: '{{ route('admin.calendar.events') }}',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listWeek'
            }
        });

        calendar.render();
    });
</script>
@endsection
