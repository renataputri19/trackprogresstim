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
            initialView: 'dayGridMonth', // Gantt chart style works better in time grid view
            events: '/admin/calendar/events',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listWeek'
            },
            eventContent: function(arg) {
                // Create a container for the event
                let eventContainer = document.createElement('div');
                eventContainer.style.display = 'flex';
                eventContainer.style.alignItems = 'center';
                eventContainer.style.width = '100%';
                eventContainer.style.height = '100%';
                eventContainer.style.backgroundColor = arg.event.backgroundColor;
                eventContainer.style.position = 'relative';

                // Create a span for the event title and progress
                let eventTitle = document.createElement('span');
                eventTitle.innerText = `${arg.event.title} - ${arg.event.extendedProps.progress}%`;
                eventTitle.style.zIndex = '2';
                eventTitle.style.color = 'white';
                eventTitle.style.padding = '0 5px';
                eventTitle.style.width = '100%';
                eventTitle.style.textAlign = 'center';

                // Create a container for the progress bar
                let progressBarContainer = document.createElement('div');
                progressBarContainer.style.width = '100%';
                progressBarContainer.style.height = '100%';
                progressBarContainer.style.position = 'absolute';
                progressBarContainer.style.top = '0';
                progressBarContainer.style.left = '0';
                progressBarContainer.style.display = 'flex';
                progressBarContainer.style.alignItems = 'center';

                // Create the progress bar
                let progressBar = document.createElement('div');
                progressBar.style.width = arg.event.extendedProps.progress + '%';
                progressBar.style.height = '100%';
                progressBar.style.backgroundColor = '#28a745';
                progressBar.style.position = 'relative';
                progressBar.style.zIndex = '1';

                // Append the title and progress bar to the event container
                progressBarContainer.appendChild(progressBar);
                eventContainer.appendChild(progressBarContainer);
                eventContainer.appendChild(eventTitle);

                return { domNodes: [eventContainer] };
            }
        });

        calendar.render();
    }
});
