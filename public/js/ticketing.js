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

    // Check if we're on a public view page (disable notifications completely on public pages)
    const isPublicPage = window.location.pathname.includes('/public/view/') ||
                        window.location.pathname.includes('/haloIP/public/view/') ||
                        document.querySelector('meta[name="is-public-page"]')?.content === 'true';
    console.log('Is public page:', isPublicPage);

    if (isITStaff && !isPublicPage) {
        console.log('Setting up notifications for IT staff');

        // Notification timing configuration (overridable via window.NotificationConfig or localStorage)
        const NotificationConfig = Object.assign({
            // Minimum 1s cooldown between plays; overridable but enforced >= 1000ms
            cooldownMs: Math.max(parseInt(localStorage.getItem('notifCooldownMs')) || 1000, 1000),
            // Debounce duplicate triggers for the same ticket within this window
            debounceMs: parseInt(localStorage.getItem('notifDebounceMs')) || 1000,
            // Additional delay applied after each successful playback
            deltaMs: parseInt(localStorage.getItem('notifDeltaMs')) || 0,
            // Polling interval for detecting new tickets
            pollMs: parseInt(localStorage.getItem('notifPollMs')) || 5000,
        }, window.NotificationConfig || {});
        window.NotificationConfig = NotificationConfig;

        // Playback queue manager: guarantees single sound per ticket event with cooldown and debouncing
        const SoundPlaybackManager = (function() {
            let queue = [];
            let processing = false;
            let lastPlaybackAt = 0;
            let lastTriggerMap = new Map(); // ticketId -> timestamp
            let notifiedTickets = new Set(JSON.parse(sessionStorage.getItem('notifiedTickets') || '[]'));
            let queueTicketIds = new Set(); // prevent duplicate enqueues for same ticket

            function enqueue(ticketId, createdAt) {
                const now = Date.now();
                const lastTrigger = lastTriggerMap.get(ticketId) || 0;
                if (now - lastTrigger < NotificationConfig.debounceMs) {
                    console.debug('[SoundQueue] Debounced duplicate trigger for ticket', ticketId);
                    return;
                }
                if (queueTicketIds.has(ticketId)) {
                    console.debug('[SoundQueue] Already queued; suppressing duplicate enqueue for ticket', ticketId);
                    return;
                }
                if (notifiedTickets.has(ticketId)) {
                    console.debug('[SoundQueue] Suppressed; ticket already notified', ticketId);
                    return;
                }
                lastTriggerMap.set(ticketId, now);
                queue.push({ ticketId, createdAt });
                queueTicketIds.add(ticketId);
                console.info('[SoundQueue] Enqueued ticket', ticketId, 'queue length =', queue.length);
                processQueue();
            }

            function processQueue() {
                if (processing) return;
                processing = true;

                const runner = () => {
                    if (queue.length === 0) {
                        processing = false;
                        return;
                    }

                    const now = Date.now();
                    const elapsed = now - lastPlaybackAt;
                    const waitNeeded = Math.max(NotificationConfig.cooldownMs, 1000) + (NotificationConfig.deltaMs || 0);
                    if (lastPlaybackAt && elapsed < waitNeeded) {
                        const waitFor = waitNeeded - elapsed;
                        console.debug('[SoundQueue] Cooldown active, waiting', waitFor, 'ms');
                        setTimeout(runner, waitFor);
                        return;
                    }

                    const next = queue.shift();
                    // Remove from queued set now that we're processing it
                    queueTicketIds.delete(next.ticketId);

                    // Safety: skip if somehow already notified
                    if (notifiedTickets.has(next.ticketId)) {
                        console.debug('[SoundQueue] Skipping already-notified ticket', next.ticketId);
                        runner();
                        return;
                    }
                    console.info('[SoundQueue] Playing sound for ticket', next.ticketId);
                    playNotificationSound()
                        .then(() => {
                            lastPlaybackAt = Date.now();
                            notifiedTickets.add(next.ticketId);
                            sessionStorage.setItem('notifiedTickets', JSON.stringify(Array.from(notifiedTickets)));
                            const delta = NotificationConfig.deltaMs || 0;
                            if (delta > 0) {
                                console.debug('[SoundQueue] Applying delta delay', delta, 'ms');
                                setTimeout(runner, delta);
                            } else {
                                // Immediately attempt next, cooldown will still be enforced
                                runner();
                            }
                        })
                        .catch((err) => {
                            console.error('[SoundQueue] Audio play error; continuing with enforced cooldown:', err);
                            lastPlaybackAt = Date.now();
                            const delta = NotificationConfig.deltaMs || 0;
                            setTimeout(runner, delta);
                        });
                };

                runner();
            }

            return {
                enqueue,
                processQueue,
                getQueueSize: () => queue.length,
                getState: () => ({ queueLength: queue.length, lastPlaybackAt, cooldownMs: NotificationConfig.cooldownMs, deltaMs: NotificationConfig.deltaMs })
            };
        })();

        // Unified pending count across all categories/service types
        let previousPendingCountAll = parseInt(sessionStorage.getItem('previousPendingCountAll')) || 0;
        let initializedPendingBaseline = sessionStorage.getItem('pendingBaselineInitialized') === 'true';

        function checkPendingCountAndRefresh() {
            console.log('Checking unified pending count via AJAX...');

            fetch('/api/haloip/pending-count', {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                const currentPendingCountAll = data.pendingCount;
                console.log('Current pending (all categories):', currentPendingCountAll);
                console.log('Previous pending (all categories):', previousPendingCountAll);

                // On first run, baseline the count without playing sound (non-intrusive)
                if (!initializedPendingBaseline) {
                    previousPendingCountAll = currentPendingCountAll;
                    sessionStorage.setItem('previousPendingCountAll', currentPendingCountAll);
                    sessionStorage.setItem('lastNotifiedPendingCountAll', currentPendingCountAll);
                    sessionStorage.setItem('pendingBaselineInitialized', 'true');
                    initializedPendingBaseline = true;
                    console.log('Baseline initialized, no notification on initial load.');
                    return;
                }

                const lastNotifiedPendingCountAll = parseInt(sessionStorage.getItem('lastNotifiedPendingCountAll')) || 0;

                // When count increases, rely on per-ticket detection for sound playback.
                if (currentPendingCountAll > previousPendingCountAll && currentPendingCountAll > lastNotifiedPendingCountAll) {
                    console.debug('Pending count increased; delegating sound playback to per-ticket queue.');
                    sessionStorage.setItem('lastNotifiedPendingCountAll', currentPendingCountAll);

                    // Refresh lists once per detection only if the relevant UI exists
                    const tableContainer = document.querySelector('.haloip-table-container');
                    const tableBody = document.querySelector('.haloip-table tbody');
                    const cardContainer = document.querySelector('.row.mt-4');
                    if ((tableContainer && tableBody) || cardContainer) {
                        updateTicketList();
                    } else {
                        console.debug('Ticket list UI not present; skipping updateTicketList');
                    }
                    if (window.location.pathname.includes('/map-requests')) {
                        updateMapRequestList();
                    }
                }

                // Update previous count for next comparison
                previousPendingCountAll = currentPendingCountAll;
                sessionStorage.setItem('previousPendingCountAll', currentPendingCountAll);
            })
            .catch(error => {
                console.error('Error checking unified pending count:', error);
            });
        }

        // Baseline and per-ticket detection: poll pending tickets and enqueue distinct ticket IDs
        let ticketsBaselineInit = sessionStorage.getItem('ticketsBaselineInit') === 'true';
        let lastSeenCreatedAt = parseInt(sessionStorage.getItem('lastSeenCreatedAt')) || 0;
        let seenPendingTicketIds = new Set(JSON.parse(sessionStorage.getItem('seenPendingTicketIds') || '[]'));

        function hasTicketListUI() {
            const tableContainer = document.querySelector('.haloip-table-container');
            const tableBody = document.querySelector('.haloip-table tbody');
            const cardContainer = document.querySelector('.row.mt-4');
            return (tableContainer && tableBody) || !!cardContainer;
        }

        function detectNewTickets() {
            console.debug('Polling pending tickets for per-event detection...');
            const qs = new URLSearchParams();
            qs.append('status[]', 'pending');

            fetch(`/api/tickets?${qs.toString()}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(r => r.json())
            .then(tickets => {
                if (!Array.isArray(tickets)) {
                    console.warn('Unexpected tickets response format', tickets);
                    return;
                }

                if (!ticketsBaselineInit) {
                    const ids = tickets.map(t => t.id);
                    const maxTs = tickets.reduce((m, t) => Math.max(m, Date.parse(t.created_at) || 0), 0);
                    seenPendingTicketIds = new Set(ids);
                    lastSeenCreatedAt = maxTs;
                    sessionStorage.setItem('seenPendingTicketIds', JSON.stringify(ids));
                    sessionStorage.setItem('lastSeenCreatedAt', String(lastSeenCreatedAt));
                    sessionStorage.setItem('ticketsBaselineInit', 'true');
                    ticketsBaselineInit = true;
                    console.debug('Tickets baseline initialized; suppressing initial sound playback.');
                    return;
                }

                const newItems = [];
                for (const t of tickets) {
                    const ts = Date.parse(t.created_at) || 0;
                    if (!seenPendingTicketIds.has(t.id) || ts > lastSeenCreatedAt) {
                        newItems.push(t);
                    }
                }

                if (newItems.length) {
                    console.info(`Detected ${newItems.length} new ticket(s). Queueing sound playback.`);
                    // Play in chronological order for natural cadence
                    newItems.sort((a, b) => (Date.parse(a.created_at) || 0) - (Date.parse(b.created_at) || 0));
                    for (const t of newItems) {
                        SoundPlaybackManager.enqueue(t.id, t.created_at);
                        seenPendingTicketIds.add(t.id);
                        lastSeenCreatedAt = Math.max(lastSeenCreatedAt, Date.parse(t.created_at) || 0);
                    }
                    sessionStorage.setItem('seenPendingTicketIds', JSON.stringify(Array.from(seenPendingTicketIds)));
                    sessionStorage.setItem('lastSeenCreatedAt', String(lastSeenCreatedAt));

                    // Refresh ticket list when new tickets detected and UI exists
                    if (hasTicketListUI()) {
                        updateTicketList();
                    } else {
                        console.debug('Ticket list UI not present; skipping updateTicketList');
                    }
                } else {
                    console.debug('No new tickets detected in this poll.');
                }
            })
            .catch(err => console.error('Pending tickets poll failed', err));
        }

        // Initial checks and intervals
        detectNewTickets();
        checkPendingCountAndRefresh();
        setInterval(detectNewTickets, window.NotificationConfig.pollMs);
        setInterval(checkPendingCountAndRefresh, window.NotificationConfig.pollMs);

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
                                <a href="/haloIP/public/view/${ticket.public_token}" target="_blank" class="haloip-table-btn haloip-table-btn-outline">
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
                console.debug('Ticket container not found; skipping card update on this page');
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
                                    <a href="/haloIP/public/view/${ticket.public_token}" target="_blank" class="btn ticketing-btn ticketing-btn-outline-primary btn-sm">Link Publik</a>
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
                                <a href="/haloIP/public/view/${mapRequest.public_token}" target="_blank" class="haloip-table-btn haloip-table-btn-outline">
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
                console.debug('Map request container not found; skipping card update on this page');
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
                                    <a href="/haloIP/public/view/${mapRequest.public_token}" target="_blank" class="btn ticketing-btn ticketing-btn-outline-primary btn-sm">Link Publik</a>
                                ` : ''}
                                <a href="/map-requests/${mapRequest.id}" class="btn ticketing-btn ticketing-btn-primary btn-sm">Tangani Permintaan</a>
                            </div>
                        </div>
                    </div>
                `;
                mapRequestContainer.appendChild(mapRequestElement);
            });
        }

        // Intervals are scheduled above using NotificationConfig.pollMs
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

    // Function to play notification sound (returns a Promise)
    function playNotificationSound() {
        const isPublicPage = window.location.pathname.includes('/public/view/') ||
                            window.location.pathname.includes('/haloIP/public/view/') ||
                            document.querySelector('meta[name="is-public-page"]')?.content === 'true';
        if (isPublicPage) {
            console.debug('Notification sound blocked: on public page');
            return Promise.resolve();
        }

        // Global cooldown guard to prevent any duplicate playback within 1 second
        const now = Date.now();
        const minCooldown = Math.max((window.NotificationConfig?.cooldownMs) || 1000, 1000);
        if (window.__lastAudioPlayAt && (now - window.__lastAudioPlayAt) < minCooldown) {
            console.debug('Global guard: skipping audio due to cooldown. Elapsed =', (now - window.__lastAudioPlayAt), 'ms');
            return Promise.resolve();
        }

        const soundPath = '/sounds/notification.mp3';
        console.debug('Attempting to play notification sound from path:', soundPath);

        const audio = new Audio(soundPath);

        // Add event listeners to track loading and playing
        audio.addEventListener('canplaythrough', () => {
            console.debug('Audio can play through, attempting to play...');
        });

        audio.addEventListener('playing', () => {
            console.info('Audio is playing successfully!');
        });

        audio.addEventListener('error', (e) => {
            console.error('Error loading audio:', e);
            // Fallback: vibrate on mobile devices
            try {
                window.navigator.vibrate(200);
                console.debug('Vibration triggered as fallback for load error');
            } catch (vibrateError) {
                console.warn('Vibration not supported:', vibrateError);
            }
        });

        // Try to play the audio and propagate errors
        return audio.play().then(() => {
            window.__lastAudioPlayAt = Date.now();
            console.info('Audio playback started successfully!');
        }).catch(error => {
            console.error('Error playing audio:', error);
            // Fallback: vibrate on mobile devices
            try {
                window.navigator.vibrate(200);
                console.debug('Vibration triggered as fallback for play error');
            } catch (vibrateError) {
                console.warn('Vibration not supported:', vibrateError);
            }
            // Propagate error so the queue can log and continue while enforcing cooldown
            throw error;
        });
    }


});