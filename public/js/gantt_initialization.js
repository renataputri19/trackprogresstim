document.addEventListener('DOMContentLoaded', function() {
    gantt.config.date_format = "%Y-%m-%d %H:%i";
    gantt.config.columns = [
        { name: "text", label: "Task Name", width: "*", tree: true },
        { name: "start_date", label: "Start Date", align: "center" },
        { name: "duration", label: "Duration", align: "center" },
        { name: "progress", label: "Progress", align: "center" },
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
    
    
    gantt.attachEvent("onTaskClick", function(id) {
        gantt.showLightbox(id);
        return true;
    });
    
    
    

    // Handle filtering based on TIM
    document.getElementById('tim-filter').addEventListener('change', function() {
        let tim = this.value;
        gantt.clearAll();
        gantt.load("/admin/calendar/gantt-chart?tim=" + tim); // Load filtered data
    });

    // Optional: Configure tooltip for better UX
    gantt.templates.tooltip_text = function(start, end, task) {
        return `<b>Task:</b> ${task.text}<br/><b>Start date:</b> ${task.start_date}<br/><b>Progress:</b> ${(task.progress * 100).toFixed(2)}%`;
    };

    
});