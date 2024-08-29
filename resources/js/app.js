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

                // Create a span for the event title
                let eventTitle = document.createElement('span');
                eventTitle.innerText = `${arg.event.title} - ${arg.event.extendedProps.progress}%`;
                eventTitle.style.flex = '1';
                eventTitle.style.zIndex = '2';
                eventTitle.style.color = 'black';
                eventTitle.style.padding = '0 10px';
                eventTitle.style.textAlign = 'left';

                // Create a container for the progress bar
                let progressBarContainer = document.createElement('div');
                progressBarContainer.style.width = '100%';
                progressBarContainer.style.height = '100%';
                progressBarContainer.style.backgroundColor = '#e9ecef';
                progressBarContainer.style.position = 'absolute';
                progressBarContainer.style.top = '0';
                progressBarContainer.style.left = '0';
                progressBarContainer.style.zIndex = '1';

                // Create the progress bar
                let progressBar = document.createElement('div');
                progressBar.style.width = arg.event.extendedProps.progress + '%';
                progressBar.style.height = '100%';
                progressBar.style.backgroundColor = '#28a745';
                progressBar.style.borderRadius = '3px';

                // Append the progress bar to its container
                progressBarContainer.appendChild(progressBar);

                // Append the title and progress bar to the event container
                eventContainer.appendChild(progressBarContainer);
                eventContainer.appendChild(eventTitle);

                return { domNodes: [eventContainer] };
            }
        });

        calendar.render();

        // Filter events based on TIM selection
        document.getElementById('tim-filter').addEventListener('change', function() {
            let tim = this.value;
            calendar.getEventSources().forEach(function(source) {
                source.remove();
            });
            calendar.addEventSource({
                url: '/admin/calendar/events',
                extraParams: {
                    tim: tim
                }
            });
            calendar.refetchEvents();
        });
    }
});

