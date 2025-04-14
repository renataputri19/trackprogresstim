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

    // Check if user is IT staff
    const isITStaff = document.querySelector('meta[name="is-it-staff"]')?.content === 'true';
    console.log('Is IT staff:', isITStaff);

    if (isITStaff) {
        console.log('Setting up notifications for IT staff');

        // Initialize the previous count
        let previousPendingCount = parseInt(sessionStorage.getItem('previousPendingCount')) || 0;

        // Function to check for new tickets using AJAX
        function checkForNewTickets() {
            console.log('Checking for new tickets via AJAX...');

            fetch('/api/tickets/pending-count', {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                const currentPendingCount = data.pendingCount;
                console.log('Current pending tickets:', currentPendingCount);
                console.log('Previous pending tickets:', previousPendingCount);

                // Check if we've already shown a notification for this count
                const lastNotifiedCount = parseInt(sessionStorage.getItem('lastNotifiedCount')) || 0;

                // If there are more pending tickets now than before, and we haven't notified for this count
                if (currentPendingCount > previousPendingCount && currentPendingCount > lastNotifiedCount) {
                    console.log('New tickets detected! Playing notification...');
                    playNotificationSound();
                    showBrowserNotification('Tiket Baru', 'Ada tiket baru yang menunggu untuk ditangani!');
                    // Update the last notified count
                    sessionStorage.setItem('lastNotifiedCount', currentPendingCount);
                    // Fetch and update the ticket list dynamically
                    updateTicketList();
                }

                // Update the previous count
                previousPendingCount = currentPendingCount;
                sessionStorage.setItem('previousPendingCount', currentPendingCount);
            })
            .catch(error => {
                console.error('Error checking for new tickets:', error);
            });
        }

        // Function to fetch and update the ticket list dynamically
        function updateTicketList() {
            console.log('Fetching updated ticket list...');

            // Get the current filter parameters from the URL
            const urlParams = new URLSearchParams(window.location.search);
            const month = urlParams.get('month') || '';
            const itStaff = urlParams.get('it_staff') || '';
            const status = urlParams.getAll('status[]');

            // Build the query string for the API request
            const queryString = new URLSearchParams();
            if (month) queryString.append('month', month);
            if (itStaff) queryString.append('it_staff', itStaff);
            status.forEach(s => queryString.append('status[]', s));

            fetch(`/api/tickets?${queryString.toString()}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(tickets => {
                console.log('Updated tickets:', tickets);
                const ticketContainer = document.querySelector('.row.mt-4');
                if (!ticketContainer) {
                    console.error('Ticket container not found');
                    return;
                }

                // Clear the current ticket list
                ticketContainer.innerHTML = '';

                // If no tickets, show a message
                if (tickets.length === 0) {
                    ticketContainer.innerHTML = `
                        <div class="col-12 text-center">
                            <p>Tidak ada tiket yang ditemukan.</p>
                        </div>
                    `;
                    return;
                }

                // Add each ticket to the DOM
                tickets.forEach(ticket => {
                    const ticketElement = document.createElement('div');
                    ticketElement.className = 'col-md-4 mb-4';
                    ticketElement.innerHTML = `
                        <div class="card ticket-card">
                            <div class="ticket-body">
                                <div class="ticket-code">${ticket.ticket_code}</div>
                                <h5 class="card-title">${ticket.title}</h5>
                                <p><strong>Pengaju:</strong> ${ticket.requestor?.name ?? 'Pengaju Tidak Diketahui'}</p>
                                <p><strong>Ruangan:</strong> ${ticket.ruangan}</p>
                                <p><strong>Deskripsi:</strong> ${ticket.description.substring(0, 100)}${ticket.description.length > 100 ? '...' : ''}</p>
                                <p><strong>Status:</strong>
                                    <span class="status-${ticket.status}">
                                        ${ticket.status === 'pending' ? 'Menunggu' : (ticket.status === 'in_progress' ? 'Sedang Diproses' : 'Selesai')}
                                    </span>
                                </p>
                                <p><strong>Staf IT:</strong> ${ticket.itStaff?.name ?? 'Belum Ditugaskan'}</p>
                                <p><strong>Diajukan:</strong> ${new Date(ticket.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}</p>
                                ${ticket.done_at ? `<p><strong>Selesai:</strong> ${new Date(ticket.done_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}</p>` : ''}
                                <div class="d-flex gap-2">
                                    ${ticket.requestor_photo ? `
                                        <a href="${ticket.requestor_photo}" target="_blank" class="btn ticketing-btn ticketing-btn-secondary">Lihat Foto Pengaju</a>
                                    ` : ''}
                                    ${ticket.it_photo ? `
                                        <a href="${ticket.it_photo}" target="_blank" class="btn ticketing-btn ticketing-btn-secondary">Lihat Foto IT</a>
                                    ` : ''}
                                    <a href="/tickets/${ticket.id}" class="btn ticketing-btn ticketing-btn-primary">Tangani Tiket</a>
                                </div>
                            </div>
                        </div>
                    `;
                    ticketContainer.appendChild(ticketElement);
                });
            })
            .catch(error => {
                console.error('Error fetching updated tickets:', error);
            });
        }

        // Initial check for new tickets
        checkForNewTickets();

        // Poll for new tickets every 5 seconds
        setInterval(checkForNewTickets, 5000);
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

    // Function to play notification sound
    function playNotificationSound() {
        console.log('Attempting to play notification sound...');

        const soundPath = '/sounds/notification.mp3';
        console.log('Trying to play sound from path:', soundPath);

        const audio = new Audio(soundPath);

        // Add event listeners to track loading and playing
        audio.addEventListener('canplaythrough', () => {
            console.log('Audio can play through, attempting to play...');
        });

        audio.addEventListener('playing', () => {
            console.log('Audio is playing successfully!');
        });

        audio.addEventListener('error', (e) => {
            console.error('Error loading audio:', e);
            console.error('Error code:', e.code);
            console.error('Error message:', e.message);
            // Fallback: vibrate on mobile devices
            try {
                window.navigator.vibrate(200);
                console.log('Vibration triggered as fallback');
            } catch (vibrateError) {
                console.log('Vibration not supported:', vibrateError);
            }
        });

        // Try to play the audio
        audio.play().then(() => {
            console.log('Audio playback started successfully!');
        }).catch(error => {
            console.error('Error playing audio:', error);
            // Fallback: vibrate on mobile devices
            try {
                window.navigator.vibrate(200);
                console.log('Vibration triggered as fallback');
            } catch (vibrateError) {
                console.log('Vibration not supported:', vibrateError);
            }
        });
    }

    // Function to show browser notification
    function showBrowserNotification(title, message) {
        if (!('Notification' in window)) {
            console.log('Browser does not support notifications');
            alert(`${title}: ${message}`); // Fallback to alert
            return;
        }

        console.log('Current notification permission:', Notification.permission);

        if (Notification.permission === 'granted') {
            console.log('Permission granted, creating notification...');
            createNotification(title, message);
        } else if (Notification.permission !== 'denied') {
            console.log('Requesting notification permission...');
            Notification.requestPermission().then(permission => {
                console.log('Permission result:', permission);
                if (permission === 'granted') {
                    console.log('Permission granted after request, creating notification...');
                    createNotification(title, message);
                } else {
                    console.log('Notification permission denied by user');
                    alert(`${title}: ${message}`); // Fallback to alert
                }
            }).catch(error => {
                console.error('Error requesting notification permission:', error);
                alert(`${title}: ${message}`); // Fallback to alert
            });
        } else {
            console.log('Notification permission is denied');
            alert(`${title}: ${message}`); // Fallback to alert
        }
    }

    // Helper function to create notification
    function createNotification(title, message) {
        try {
            console.log('Creating notification with title:', title, 'and message:', message);
            const notification = new Notification(title, {
                body: message,
                // icon: '/images/notification-icon.png' // Uncomment if you have an icon
            });

            console.log('Notification created successfully:', notification);

            setTimeout(() => {
                notification.close();
                console.log('Notification closed after 5 seconds');
            }, 5000);

            notification.onclick = function() {
                console.log('Notification clicked');
                window.focus();
                this.close();
            };

            notification.onerror = function(error) {
                console.error('Notification error:', error);
            };
        } catch (error) {
            console.error('Error creating notification:', error);
        }
    }
});