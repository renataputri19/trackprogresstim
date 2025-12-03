// HaloIP Dashboard JS: extract inline logic, keep functionality
(function(){
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

  function setupAlertClose(){
    document.querySelectorAll('.haloip-alert .haloip-alert-close').forEach(btn => {
      btn.addEventListener('click', function(){
        const p = btn.closest('.haloip-alert');
        if(p){ p.remove(); }
      });
    });
  }

  document.addEventListener('DOMContentLoaded', function(){
    addScreenWidthToLinks('.haloip-pagination-wrapper a');
    attachFilterWidth();
    setupResizeReload();
    setupAlertClose();
  });
})();