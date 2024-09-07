document.addEventListener('DOMContentLoaded', function() {
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

    

    gantt.init("gantt_here");

    gantt.load("/admin/calendar/gantt-chart", "json");

    var dp = new gantt.dataProcessor("/api");
    dp.init(gantt);
    dp.setTransactionMode("REST");

    
    
// add csrf token


    // const rute = $("#routing-gantt-update").val();

    // gantt.attachEvent("onTaskSave", function(id, task) {
    //     console.log(task);
    //     $.ajax({
    //         url: rute, // Make sure this route exists and points to the correct method
    //         method: "POST",
    //         data: {
    //             id: task.id,
    //             text: task.text,
    //             start_date: task.start_date,
    //             duration: task.duration,
    //             progress: task.progress
    //         },
    //         success: function(response) {
    //             if (response.status === "success") {
    //                 gantt.message({ text: "Task saved successfully", type: "success" });
    //                 gantt.hideLightbox(); // Close the pop-up
    //             } else {
    //                 gantt.message({ text: "Error saving task", type: "error" });
    //             }
    //         },
    //         error: function() {
    //             gantt.message({ text: "Server error", type: "error" });
    //         }
    //     });
    
    //     return false; // Prevent default behavior
    // });
    
    
    gantt.attachEvent("onAfterTaskUpdate", function(id, item){
        gantt.message("Task has been updated!");
        gantt.hideLightbox();
        return true;
    });
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    

    // Handle filtering based on TIM
    // document.getElementById('tim-filter').addEventListener('change', function() {
    //     let tim_id = this.value || ''; // Default to an empty string if no value is selected
    //     gantt.clearAll(); // Clear the existing data
    //     gantt.load("/admin/calendar/gantt-chart?tim_id=" + tim_id); // Load filtered data with tim_id
    // });



    // Optional: Configure tooltip for better UX
    gantt.templates.tooltip_text = function(start, end, task) {
        return `<b>Task:</b> ${task.text}<br/><b>Start date:</b> ${task.start_date}<br/><b>Progress:</b> ${(task.progress * 100).toFixed(2)}%`;
    };

    

    
});