@extends('layouts.main')

@section('title', 'Task Calendar')

@section('content')
<div class="container">
    <h2 class="my-4">Task Calendar</h2>
    <div id="calendar"></div>
</div>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: @json($tasks),
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            editable: true,
            selectable: true,
            selectHelper: true,
            eventClick: function(info) {
                alert('Task: ' + info.event.title + '\nStart: ' + info.event.start + '\nEnd: ' + info.event.end);
            }
        });

        calendar.render();
    });
</script>
@endsection
