document.addEventListener('DOMContentLoaded', function() {
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    const endDateError = document.getElementById('end_date_error');
    const target = document.getElementById('target');
    const progress = document.getElementById('progress');
    const progressError = document.getElementById('progress_error');
    const taskForm = document.getElementById('taskForm');
    const submitButton = taskForm.querySelector('button[type="submit"]');

    function validateDates() {
        if (new Date(endDate.value) < new Date(startDate.value)) {
            endDateError.style.display = 'block';
            submitButton.disabled = true;
        } else {
            endDateError.style.display = 'none';
            validateForm();
        }
    }

    function validateProgress() {
        if (progress && target) {
            if (parseInt(progress.value) > parseInt(target.value)) {
                progressError.style.display = 'block';
                submitButton.disabled = true;
            } else {
                progressError.style.display = 'none';
                validateForm();
            }
        }
    }

    function validateForm() {
        if (endDateError.style.display === 'none' &&
            (!progressError || progressError.style.display === 'none')) {
            submitButton.disabled = false;
        } else {
            submitButton.disabled = true;
        }
    }

    if (startDate && endDate) {
        startDate.addEventListener('input', validateDates);
        endDate.addEventListener('input', validateDates);
    }

    if (progress && target) {
        progress.addEventListener('input', validateProgress);
        target.addEventListener('input', validateProgress);
    }

    // Initial validation check
    validateDates();
    validateProgress();
});
