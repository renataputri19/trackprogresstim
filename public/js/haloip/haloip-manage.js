// HaloIP Manage JS: extract inline logic while preserving behaviors
(function(){
  function updateStatusDropdownText(){
    const dropdownButton = document.getElementById('statusDropdown');
    if(!dropdownButton) return;
    const statusCheckboxes = document.querySelectorAll('.status-checkbox');
    const checkedBoxes = Array.from(statusCheckboxes).filter(cb => cb.checked);
    if (checkedBoxes.length === 0) {
      dropdownButton.textContent = 'Pilih Status';
    } else if (checkedBoxes.length === 1) {
      const statusLabels = { pending: 'Menunggu', in_progress: 'Sedang Diproses', completed: 'Selesai' };
      dropdownButton.textContent = statusLabels[checkedBoxes[0].value] || checkedBoxes[0].value;
    } else {
      dropdownButton.textContent = `${checkedBoxes.length} Status Dipilih`;
    }
  }

  function initStatusControls(){
    updateStatusDropdownText();
    const selectAll = document.getElementById('status_select_all');
    if(selectAll){
      selectAll.addEventListener('change', function(){
        document.querySelectorAll('.status-checkbox').forEach(cb => { cb.checked = selectAll.checked; });
        updateStatusDropdownText();
      });
    }
    document.querySelectorAll('.status-checkbox').forEach(cb => {
      cb.addEventListener('change', function(){
        const allCheckboxes = document.querySelectorAll('.status-checkbox');
        const checkedCheckboxes = document.querySelectorAll('.status-checkbox:checked');
        if(selectAll){ selectAll.checked = allCheckboxes.length === checkedCheckboxes.length; }
        updateStatusDropdownText();
      });
    });
  }

  function addScreenWidthToLinks(selector){
    const links = document.querySelectorAll(selector);
    const sw = window.innerWidth;
    links.forEach(link => {
      try {
        const url = new URL(link.href, window.location.origin);
        url.searchParams.set('screen_width', sw);
        link.href = url.toString();
      } catch (_) { /* noop */ }
    });
  }

  function attachFilterWidth(){
    const forms = document.querySelectorAll('.haloip-filter-modern form');
    const sw = window.innerWidth;
    forms.forEach(form => {
      form.addEventListener('submit', function(){
        let hidden = form.querySelector('input[name="screen_width"]');
        if(!hidden){
          hidden = document.createElement('input');
          hidden.type = 'hidden';
          hidden.name = 'screen_width';
          form.appendChild(hidden);
        }
        hidden.value = sw;
      });
    });
  }

  function setupResizeReload(){
    let timer;
    window.addEventListener('resize', function(){
      clearTimeout(timer);
      timer = setTimeout(function(){
        const newSW = window.innerWidth;
        const currentUrl = new URL(window.location.href);
        const oldSW = parseInt(currentUrl.searchParams.get('screen_width') || newSW, 10);
        const crossed = (oldSW < 1024 && newSW >= 1024) || (oldSW >= 1024 && newSW < 1024);
        if(crossed){
          currentUrl.searchParams.set('screen_width', newSW);
          window.location.href = currentUrl.toString();
        }
      }, 500);
    });
  }

  // Delete confirmation modal logic
  function initDeleteModal(){
    const modal = document.getElementById('haloipDeleteModal');
    if(!modal) return;
    const details = document.getElementById('haloipDeleteDetails');
    const cancelBtn = document.getElementById('haloipDeleteCancel');
    const form = document.getElementById('haloipDeleteForm');
    const deleteUrlBase = modal.dataset.deleteUrlBase || '';
    const deleteButtons = document.querySelectorAll('.haloip-delete-btn');

    function openModal(ticket){
      details.textContent = `Kode: ${ticket.code} • Judul: ${ticket.title}`;
      form.action = `${deleteUrlBase}/${ticket.id}`;
      modal.classList.add('show');
    }
    function closeModal(){
      modal.classList.remove('show');
      details.textContent = '';
      form.action = '';
    }

    deleteButtons.forEach(btn => {
      btn.addEventListener('click', function(e){
        e.preventDefault();
        const ticket = {
          id: this.getAttribute('data-ticket-id'),
          code: this.getAttribute('data-ticket-code'),
          title: this.getAttribute('data-ticket-title')
        };
        openModal(ticket);
      });
    });
    if(cancelBtn){ cancelBtn.addEventListener('click', closeModal); }
    // Close on backdrop click
    modal.addEventListener('click', function(e){
      if(e.target.classList.contains('haloip-modal-backdrop')){ closeModal(); }
    });
  }

  document.addEventListener('DOMContentLoaded', function(){
    initStatusControls();
    addScreenWidthToLinks('.haloip-pagination-wrapper a');
    addScreenWidthToLinks('.haloip-tab');
    attachFilterWidth();
    initDeleteModal();
    initStaffBreakdownAccordion();
  });
})();

// Staff breakdown accordion toggling
function initStaffBreakdownAccordion(){
  const summaryRows = document.querySelectorAll('.haloip-row-expandable');
  if(!summaryRows.length) return;

  function collapseAll(exceptId){
    document.querySelectorAll('.haloip-breakdown-details').forEach(row => {
      if(row.id !== exceptId){
        row.style.display = 'none';
      }
    });
    document.querySelectorAll('.haloip-row-expandable').forEach(r => {
      if(r.getAttribute('data-target') !== exceptId){
        r.setAttribute('aria-expanded', 'false');
        r.classList.remove('haloip-row-expanded');
      }
    });
  }

  function toggleRow(row){
    const targetId = row.getAttribute('data-target');
    if(!targetId) return;
    const details = document.getElementById(targetId);
    if(!details) return;
    const isOpen = details.style.display !== 'none' && details.style.display !== '' ? true : false;
    // Close others for accordion behavior
    collapseAll(targetId);
    if(isOpen){
      details.style.display = 'none';
      row.setAttribute('aria-expanded', 'false');
      row.classList.remove('haloip-row-expanded');
    } else {
      details.style.display = 'table-row';
      row.setAttribute('aria-expanded', 'true');
      row.classList.add('haloip-row-expanded');
    }
  }

  summaryRows.forEach(row => {
    // Make row keyboard accessible
    row.setAttribute('tabindex', '0');
    row.setAttribute('role', 'button');
    row.addEventListener('click', function(){ toggleRow(row); });
    row.addEventListener('keydown', function(e){
      if(e.key === 'Enter' || e.key === ' '){
        e.preventDefault();
        toggleRow(row);
      }
    });
  });
}