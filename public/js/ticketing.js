document.addEventListener('DOMContentLoaded', function() {
    const getTicketBtn = document.querySelector('.ticketing-btn-success');
    if (getTicketBtn) {
        getTicketBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (!document.querySelector('meta[name="user-logged-in"]')?.content) {
                window.location.href = '/login?redirect=/haloip/tickets/create';
            } else {
                window.location.href = '/haloip/tickets/create';
            }
        });
    }

    // Check if user is IT staff
    const isITStaff = document.querySelector('meta[name="is-it-staff"]')?.content === 'true';
    console.log('Is IT staff:', isITStaff);

    if (isITStaff) {
        console.log('Setting up notifications for IT staff');

        // Initialize the previous counts
        let previousPendingTicketCount = parseInt(sessionStorage.getItem('previousPendingTicketCount')) || 0;
        let previousPendingMapRequestCount = parseInt(sessionStorage.getItem('previousPendingMapRequestCount')) || 0;

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
                const currentPendingTicketCount = data.pendingCount;
                console.log('Current pending tickets:', currentPendingTicketCount);
                console.log('Previous pending tickets:', previousPendingTicketCount);

                // Check if we've already shown a notification for this count
                const lastNotifiedTicketCount = parseInt(sessionStorage.getItem('lastNotifiedTicketCount')) || 0;

                // If there are more pending tickets now than before, and we haven't notified for this count
                if (currentPendingTicketCount > previousPendingTicketCount && currentPendingTicketCount > lastNotifiedTicketCount) {
                    console.log('New tickets detected! Playing notification...');
                    playNotificationSound();
                    showBrowserNotification('Tiket IT Baru', 'Ada tiket IT baru yang menunggu untuk ditangani!');
                    // Update the last notified count
                    sessionStorage.setItem('lastNotifiedTicketCount', currentPendingTicketCount);
                    // Fetch and update the ticket list dynamically if on tickets page
                    if (window.location.pathname.includes('/haloIP') || window.location.pathname.includes('/tickets')) {
                        updateTicketList();
                    }
                }

                // Update the previous count
                previousPendingTicketCount = currentPendingTicketCount;
                sessionStorage.setItem('previousPendingTicketCount', currentPendingTicketCount);
            })
            .catch(error => {
                console.error('Error checking for new tickets:', error);
            });
        }

        // Function to check for new map requests using AJAX
        function checkForNewMapRequests() {
            console.log('Checking for new map requests via AJAX...');

            fetch('/api/map-requests/pending-count', {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                const currentPendingMapRequestCount = data.pendingCount;
                console.log('Current pending map requests:', currentPendingMapRequestCount);
                console.log('Previous pending map requests:', previousPendingMapRequestCount);

                // Check if we've already shown a notification for this count
                const lastNotifiedMapRequestCount = parseInt(sessionStorage.getItem('lastNotifiedMapRequestCount')) || 0;

                // If there are more pending map requests now than before, and we haven't notified for this count
                if (currentPendingMapRequestCount > previousPendingMapRequestCount && currentPendingMapRequestCount > lastNotifiedMapRequestCount) {
                    console.log('New map requests detected! Playing notification...');
                    playNotificationSound();
                    showBrowserNotification('Permintaan Peta Baru', 'Ada permintaan peta baru yang menunggu untuk ditangani!');
                    // Update the last notified count
                    sessionStorage.setItem('lastNotifiedMapRequestCount', currentPendingMapRequestCount);
                    // Fetch and update the map request list dynamically if on map requests page
                    if (window.location.pathname.includes('/map-requests')) {
                        updateMapRequestList();
                    }
                }

                // Update the previous count
                previousPendingMapRequestCount = currentPendingMapRequestCount;
                sessionStorage.setItem('previousPendingMapRequestCount', currentPendingMapRequestCount);
            })
            .catch(error => {
                console.error('Error checking for new map requests:', error);
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

                // Check if we're using the new table layout
                const tableContainer = document.querySelector('.haloip-table-container');
                const tableBody = document.querySelector('.haloip-table tbody');

                if (tableContainer && tableBody) {
                    // Update table layout
                    updateTicketTable(tickets, tableBody, tableContainer);
                } else {
                    // Fallback to old card layout
                    updateTicketCards(tickets);
                }
            })
            .catch(error => {
                console.error('Error fetching updated tickets:', error);
            });
        }

        // Function to update ticket table layout
        function updateTicketTable(tickets, tableBody, tableContainer) {
            if (tickets.length === 0) {
                tableContainer.innerHTML = `
                    <div class="haloip-table-empty">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14,2 14,8 20,8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                        </svg>
                        <p>Tidak ada tiket yang ditemukan.</p>
                    </div>
                `;
                return;
            }

            // Clear current table body
            tableBody.innerHTML = '';

            // Add each ticket as a table row
            tickets.forEach((ticket, index) => {
                const row = document.createElement('tr');
                row.style.animationDelay = `${(index + 1) * 0.05}s`;

                const statusText = ticket.status === 'pending' ? 'Menunggu' :
                                 (ticket.status === 'in_progress' ? 'Sedang Diproses' : 'Selesai');

                const createdDate = new Date(ticket.created_at).toLocaleDateString('id-ID', {
                    day: 'numeric', month: 'long', year: 'numeric'
                });

                const doneDate = ticket.done_at ?
                    new Date(ticket.done_at).toLocaleDateString('id-ID', {
                        day: 'numeric', month: 'long', year: 'numeric'
                    }) : '';

                row.innerHTML = `
                    <td>
                        <div class="haloip-table-code">${ticket.ticket_code}</div>
                        <div class="haloip-table-title">${ticket.title}</div>
                        <div class="haloip-table-meta">${ticket.description.substring(0, 80)}${ticket.description.length > 80 ? '...' : ''}</div>
                    </td>
                    <td>
                        <div class="haloip-table-title">${ticket.requestor?.name ?? 'Pengaju Tidak Diketahui'}</div>
                        <div class="haloip-table-meta">${ticket.ruangan}</div>
                    </td>
                    <td>
                        <div class="haloip-table-status status-${ticket.status}">${statusText}</div>
                        <div class="haloip-table-meta mt-2">
                            <strong>IT Staff:</strong> ${ticket.itStaff?.name ?? 'Belum Ditugaskan'}
                        </div>
                    </td>
                    <td>
                        <div class="haloip-table-meta">
                            <strong>Diajukan:</strong><br>
                            ${createdDate}
                        </div>
                        ${doneDate ? `
                            <div class="haloip-table-meta mt-2">
                                <strong>Selesai:</strong><br>
                                ${doneDate}
                            </div>
                        ` : ''}
                    </td>
                    <td>
                        <div class="haloip-table-actions">
                            ${ticket.requestor_photo ? `
                                <a href="/storage/${ticket.requestor_photo}" target="_blank" class="haloip-table-btn haloip-table-btn-secondary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                        <circle cx="9" cy="9" r="2"></circle>
                                        <path d="M21 15l-3.086-3.086a2 2 0 0 0-2.828 0L6 21"></path>
                                    </svg>
                                    Foto Pengaju
                                </a>
                            ` : ''}
                            ${ticket.it_photo ? `
                                <a href="/storage/${ticket.it_photo}" target="_blank" class="haloip-table-btn haloip-table-btn-secondary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                        <circle cx="9" cy="9" r="2"></circle>
                                        <path d="M21 15l-3.086-3.086a2 2 0 0 0-2.828 0L6 21"></path>
                                    </svg>
                                    Foto IT
                                </a>
                            ` : ''}
                            ${ticket.public_token ? `
                                <a href="/public/view/${ticket.public_token}" target="_blank" class="haloip-table-btn haloip-table-btn-outline">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                                        <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                                    </svg>
                                    Link Publik
                                </a>
                            ` : ''}
                            <a href="/tickets/${ticket.id}" class="haloip-table-btn haloip-table-btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                                </svg>
                                Tangani Tiket
                            </a>
                        </div>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        }

        // Function to update ticket cards (fallback for old layout)
        function updateTicketCards(tickets) {
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
                            <div class="d-flex gap-2 flex-wrap">
                                ${ticket.requestor_photo ? `
                                    <a href="${ticket.requestor_photo}" target="_blank" class="btn ticketing-btn ticketing-btn-secondary btn-sm">Lihat Foto Pengaju</a>
                                ` : ''}
                                ${ticket.it_photo ? `
                                    <a href="${ticket.it_photo}" target="_blank" class="btn ticketing-btn ticketing-btn-secondary btn-sm">Lihat Foto IT</a>
                                ` : ''}
                                ${ticket.public_token ? `
                                    <a href="/public/view/${ticket.public_token}" target="_blank" class="btn ticketing-btn ticketing-btn-outline-primary btn-sm">Link Publik</a>
                                ` : ''}
                                <a href="/tickets/${ticket.id}" class="btn ticketing-btn ticketing-btn-primary btn-sm">Tangani Tiket</a>
                            </div>
                        </div>
                    </div>
                `;
                ticketContainer.appendChild(ticketElement);
            });
        }

        // Function to fetch and update the map request list dynamically
        function updateMapRequestList() {
            console.log('Fetching updated map request list...');

            // Get the current filter parameters from the URL
            const urlParams = new URLSearchParams(window.location.search);
            const month = urlParams.get('month') || '';
            const itStaff = urlParams.get('it_staff') || '';
            const status = urlParams.getAll('status[]');
            const mapType = urlParams.get('map_type') || '';
            const kdkec = urlParams.get('kdkec') || '';
            const zone = urlParams.get('zone') || '';

            // Build the query string for the API request
            const queryString = new URLSearchParams();
            if (month) queryString.append('month', month);
            if (itStaff) queryString.append('it_staff', itStaff);
            if (mapType) queryString.append('map_type', mapType);
            if (kdkec) queryString.append('kdkec', kdkec);
            if (zone) queryString.append('zone', zone);
            status.forEach(s => queryString.append('status[]', s));

            fetch(`/api/map-requests?${queryString.toString()}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(mapRequests => {
                console.log('Updated map requests:', mapRequests);

                // Check if we're using the new table layout
                const tableContainer = document.querySelector('.haloip-table-container');
                const tableBody = document.querySelector('.haloip-table tbody');

                if (tableContainer && tableBody) {
                    // Update table layout
                    updateMapRequestTable(mapRequests, tableBody, tableContainer);
                } else {
                    // Fallback to old card layout
                    updateMapRequestCards(mapRequests);
                }
            })
            .catch(error => {
                console.error('Error fetching updated map requests:', error);
            });
        }

        // Function to update map request table layout
        function updateMapRequestTable(mapRequests, tableBody, tableContainer) {
            if (mapRequests.length === 0) {
                tableContainer.innerHTML = `
                    <div class="haloip-table-empty">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="3 6 9 1 15 6 21 1 21 14 15 9 9 14 3 9"></polygon>
                            <line x1="9" y1="1" x2="9" y2="14"></line>
                            <line x1="15" y1="6" x2="15" y2="9"></line>
                        </svg>
                        <p>Tidak ada permintaan peta yang ditemukan.</p>
                    </div>
                `;
                return;
            }

            // Clear current table body
            tableBody.innerHTML = '';

            // Add each map request as a table row
            mapRequests.forEach((mapRequest, index) => {
                const row = document.createElement('tr');
                row.style.animationDelay = `${(index + 1) * 0.05}s`;

                const statusText = mapRequest.status === 'pending' ? 'Menunggu' :
                                 (mapRequest.status === 'in_progress' ? 'Sedang Diproses' : 'Selesai');

                const mapTypeDisplay = mapRequest.map_type === 'kecamatan' ? 'Peta Kecamatan' : 'Peta Kelurahan';

                let locationDisplay = '';
                if (mapRequest.kdkec && mapRequest.nmkec) {
                    locationDisplay = `${mapRequest.kdkec} - ${mapRequest.nmkec}`;
                    if (mapRequest.kddesa && mapRequest.nmdesa) {
                        locationDisplay += ` / ${mapRequest.kddesa} - ${mapRequest.nmdesa}`;
                    }
                } else if (mapRequest.zone) {
                    locationDisplay = `<strong>Zona:</strong> ${mapRequest.zone}`;
                } else {
                    locationDisplay = '-';
                }

                const createdDate = new Date(mapRequest.created_at).toLocaleDateString('id-ID', {
                    day: 'numeric', month: 'long', year: 'numeric'
                });

                const doneDate = mapRequest.done_at ?
                    new Date(mapRequest.done_at).toLocaleDateString('id-ID', {
                        day: 'numeric', month: 'long', year: 'numeric'
                    }) : '';

                row.innerHTML = `
                    <td>
                        <div class="haloip-table-code">${mapRequest.ticket_code}</div>
                        <div class="haloip-table-title">${mapRequest.title}</div>
                        <div class="haloip-table-meta">${mapRequest.description.substring(0, 80)}${mapRequest.description.length > 80 ? '...' : ''}</div>
                    </td>
                    <td>
                        <div class="haloip-table-title">${mapRequest.requestor?.name ?? 'Pengaju Tidak Diketahui'}</div>
                        <div class="haloip-table-meta">${mapTypeDisplay}</div>
                    </td>
                    <td>
                        <div class="haloip-table-meta">${locationDisplay}</div>
                    </td>
                    <td>
                        <div class="haloip-table-status status-${mapRequest.status}">${statusText}</div>
                        <div class="haloip-table-meta mt-2">
                            <strong>IT Staff:</strong> ${mapRequest.itStaff?.name ?? 'Belum Ditugaskan'}
                        </div>
                    </td>
                    <td>
                        <div class="haloip-table-meta">
                            <strong>Diajukan:</strong><br>
                            ${createdDate}
                        </div>
                        ${doneDate ? `
                            <div class="haloip-table-meta mt-2">
                                <strong>Selesai:</strong><br>
                                ${doneDate}
                            </div>
                        ` : ''}
                    </td>
                    <td>
                        <div class="haloip-table-actions">
                            ${mapRequest.requestor_photo ? `
                                <a href="/storage/${mapRequest.requestor_photo}" target="_blank" class="haloip-table-btn haloip-table-btn-secondary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                        <circle cx="9" cy="9" r="2"></circle>
                                        <path d="M21 15l-3.086-3.086a2 2 0 0 0-2.828 0L6 21"></path>
                                    </svg>
                                    Foto Pengaju
                                </a>
                            ` : ''}
                            ${mapRequest.it_photo ? `
                                <a href="/storage/${mapRequest.it_photo}" target="_blank" class="haloip-table-btn haloip-table-btn-secondary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                        <circle cx="9" cy="9" r="2"></circle>
                                        <path d="M21 15l-3.086-3.086a2 2 0 0 0-2.828 0L6 21"></path>
                                    </svg>
                                    Foto IT
                                </a>
                            ` : ''}
                            ${mapRequest.public_token ? `
                                <a href="/public/view/${mapRequest.public_token}" target="_blank" class="haloip-table-btn haloip-table-btn-outline">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                                        <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                                    </svg>
                                    Link Publik
                                </a>
                            ` : ''}
                            <a href="/map-requests/${mapRequest.id}" class="haloip-table-btn haloip-table-btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                                </svg>
                                Tangani Permintaan
                            </a>
                        </div>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        }

        // Function to update map request cards (fallback for old layout)
        function updateMapRequestCards(mapRequests) {
            const mapRequestContainer = document.querySelector('.row.mt-4');
            if (!mapRequestContainer) {
                console.error('Map request container not found');
                return;
            }

            // Clear the current map request list
            mapRequestContainer.innerHTML = '';

            // If no map requests, show a message
            if (mapRequests.length === 0) {
                mapRequestContainer.innerHTML = `
                    <div class="col-12 text-center">
                        <p>Tidak ada permintaan peta yang ditemukan.</p>
                    </div>
                `;
                return;
            }

            // Add each map request to the DOM
            mapRequests.forEach(mapRequest => {
                const mapRequestElement = document.createElement('div');
                mapRequestElement.className = 'col-md-4 mb-4';
                mapRequestElement.innerHTML = `
                    <div class="card ticket-card">
                        <div class="ticket-body">
                            <div class="ticket-code">${mapRequest.ticket_code}</div>
                            <h5 class="card-title">${mapRequest.title}</h5>
                            <p><strong>Pengaju:</strong> ${mapRequest.requestor?.name ?? 'Pengaju Tidak Diketahui'}</p>
                            <p><strong>Jenis Peta:</strong> ${mapRequest.map_type === 'kecamatan' ? 'Peta Kecamatan' : 'Peta Kelurahan'}</p>
                            ${mapRequest.kdkec && mapRequest.nmkec ? `<p><strong>Kecamatan:</strong> ${mapRequest.kdkec} - ${mapRequest.nmkec}</p>` : ''}
                            ${mapRequest.kddesa && mapRequest.nmdesa ? `<p><strong>Kelurahan:</strong> ${mapRequest.kddesa} - ${mapRequest.nmdesa}</p>` : ''}
                            ${!mapRequest.kdkec && mapRequest.zone ? `<p><strong>Zona:</strong> ${mapRequest.zone}</p>` : ''}
                            <p><strong>Deskripsi:</strong> ${mapRequest.description.substring(0, 100)}${mapRequest.description.length > 100 ? '...' : ''}</p>
                            <p><strong>Status:</strong>
                                <span class="status-${mapRequest.status}">
                                    ${mapRequest.status === 'pending' ? 'Menunggu' : (mapRequest.status === 'in_progress' ? 'Sedang Diproses' : 'Selesai')}
                                </span>
                            </p>
                            <p><strong>Staf IT:</strong> ${mapRequest.itStaff?.name ?? 'Belum Ditugaskan'}</p>
                            <p><strong>Diajukan:</strong> ${new Date(mapRequest.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}</p>
                            ${mapRequest.done_at ? `<p><strong>Selesai:</strong> ${new Date(mapRequest.done_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}</p>` : ''}
                            <div class="d-flex gap-2 flex-wrap">
                                ${mapRequest.requestor_photo ? `
                                    <a href="${mapRequest.requestor_photo}" target="_blank" class="btn ticketing-btn ticketing-btn-secondary btn-sm">Lihat Foto Pengaju</a>
                                ` : ''}
                                ${mapRequest.it_photo ? `
                                    <a href="${mapRequest.it_photo}" target="_blank" class="btn ticketing-btn ticketing-btn-secondary btn-sm">Lihat Foto IT</a>
                                ` : ''}
                                ${mapRequest.public_token ? `
                                    <a href="/public/view/${mapRequest.public_token}" target="_blank" class="btn ticketing-btn ticketing-btn-outline-primary btn-sm">Link Publik</a>
                                ` : ''}
                                <a href="/map-requests/${mapRequest.id}" class="btn ticketing-btn ticketing-btn-primary btn-sm">Tangani Permintaan</a>
                            </div>
                        </div>
                    </div>
                `;
                mapRequestContainer.appendChild(mapRequestElement);
            });
        }

        // Initial checks for new tickets and map requests
        checkForNewTickets();
        checkForNewMapRequests();

        // Poll for new tickets and map requests every 5 seconds
        setInterval(() => {
            checkForNewTickets();
            checkForNewMapRequests();
        }, 5000);
    }

    // Add smooth scroll for pagination
    const paginationLinks = document.querySelectorAll('.ticketing .pagination a');
    paginationLinks.forEach(link => {
        link.addEventListener('click', function() {
            // Don't prevent default - let the pagination work normally
            // The href contains a URL, not a CSS selector, so we can't use querySelector
            // Just let the browser navigate to the pagination URL
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