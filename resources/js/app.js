import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';

document.addEventListener('DOMContentLoaded', function() {
    let calendarEl = document.getElementById('calendar');

    if (calendarEl) {
        let calendar = new Calendar(calendarEl, {
            plugins: [ dayGridPlugin, interactionPlugin, timeGridPlugin, listPlugin ],
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
