document.addEventListener('DOMContentLoaded', function() {
    // Check if the Gantt chart element exists on the page
    const ganttContainer = document.getElementById('gantt_here');
    
    if (ganttContainer) {
        gantt.config.date_format = "%Y-%m-%d %H:%i";
        gantt.config.columns = [
            { name: "text", label: "Task Name", tree: true, width: '*' },  // Enable tree view for TIM groups and tasks
            { name: "start_date", label: "Start Date", align: "center", width: 80, template: function(task) {
                return task.start_date || ''; // Show empty if no start_date (for TIM rows)
            }},
            { name: "duration", label: "Duration", align: "center", width: 60, template: function(task) {
                return task.duration || ''; // Show empty if no duration (for TIM rows)
            }},
            { name: "progress", label: "Progress", align: "center", width: 60, template: function(task) {
                return task.progress ? (task.progress * 100).toFixed(2) + '%' : ''; // Show empty if no progress
            }},
            { name: "add", label: "" }
        ];

        // Initialize the Gantt chart
        gantt.init("gantt_here");

        // Load data for the Gantt chart
        gantt.load("/admin/calendar/gantt-chart", "json");

        var dp = new gantt.dataProcessor("/api");
        dp.init(gantt);
        dp.setTransactionMode("REST");

        gantt.attachEvent("onAfterTaskUpdate", function(id, item){
            gantt.message("Task has been updated!");
            gantt.hideLightbox();
            return true;
        });

        // Set CSRF token for AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Optional: Configure tooltip for better UX
        gantt.templates.tooltip_text = function(start, end, task) {
            return `<b>Task:</b> ${task.text}<br/><b>Start date:</b> ${task.start_date}<br/><b>Progress:</b> ${(task.progress * 100).toFixed(2)}%`;
        };
    }
});
