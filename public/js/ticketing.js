document.addEventListener('DOMContentLoaded', function() {
    const getTicketBtn = document.querySelector('.ticketing-btn-success');
    if (getTicketBtn) {
        getTicketBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (!document.querySelector('meta[name="user-logged-in"]')?.content) {
                window.location.href = '/login?redirect=/tickets/create';
            } else {
                window.location.href = '/tickets/create';
            }
        });
    }

    // Add smooth scroll for pagination
    const paginationLinks = document.querySelectorAll('.ticketing .pagination a');
    paginationLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            } else {
                window.location.href = this.getAttribute('href');
            }
        });
    });

    // Prevent dropdown from closing when clicking inside
    const statusDropdown = document.querySelector('#statusDropdown');
    if (statusDropdown) {
        statusDropdown.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }

    // Handle "Select All" checkbox for status dropdown
    const selectAllCheckbox = document.getElementById('status_select_all');
    const statusCheckboxes = document.querySelectorAll('.status-checkbox');

    if (selectAllCheckbox) {
        // Initialize "Select All" state based on individual checkboxes
        const allChecked = Array.from(statusCheckboxes).every(cb => cb.checked);
        selectAllCheckbox.checked = allChecked;

        // Toggle all checkboxes when "Select All" is clicked
        selectAllCheckbox.addEventListener('change', function() {
            statusCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }

    // Update "Select All" checkbox state when individual checkboxes change
    statusCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allChecked = Array.from(statusCheckboxes).every(cb => cb.checked);
            const noneChecked = Array.from(statusCheckboxes).every(cb => !cb.checked);
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = allChecked;
                selectAllCheckbox.indeterminate = !allChecked && !noneChecked;
            }
        });
    });
});