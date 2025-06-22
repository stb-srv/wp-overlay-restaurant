(function(){
  function showOverlay(params){
    var opts = Object.assign({
      overlayTitle: '',
      tile1Content: '',
      tile2Content: '',
      tile3Content: '',
      tile4Content: '',
      ctaText: '',
      ctaUrl: '#'
    }, params || {});

    var overlay = document.createElement('div');
    overlay.className = 'ffo-overlay';

    overlay.innerHTML =
      '<div class="ffo-overlay__inner">' +
        '<button class="ffo-overlay__close" aria-label="Close Overlay">&times;</button>' +
        '<h2 class="ffo-overlay__title">' + opts.overlayTitle + '</h2>' +
        '<div class="ffo-overlay__grid">' +
          '<div class="ffo-overlay__tile">' + opts.tile1Content + '</div>' +
          '<div class="ffo-overlay__tile">' + opts.tile2Content + '</div>' +
          '<div class="ffo-overlay__tile">' + opts.tile3Content + '</div>' +
          '<div class="ffo-overlay__tile">' + opts.tile4Content + '</div>' +
        '</div>' +
        '<a class="ffo-overlay__cta" href="' + opts.ctaUrl + '">' + opts.ctaText + '</a>' +
      '</div>';

    document.body.appendChild(overlay);
    // Ensure CSS transition
    requestAnimationFrame(function(){
      overlay.classList.add('is-visible');
    });

    function remove(){
      overlay.classList.remove('is-visible');
      overlay.addEventListener('transitionend', function(){
        overlay.remove();
      }, { once: true });
    }

    overlay.querySelector('.ffo-overlay__close').addEventListener('click', remove);
    overlay.addEventListener('click', function(e){
      if(e.target === overlay){
        remove();
      }
    });
  }

  window.showOverlay = showOverlay;
  window.showOverlayFromElement = function(el){
    if(!el) return;
    var d = el.dataset;
    showOverlay({
      overlayTitle: d.overlayTitle || '',
      tile1Content: d.tile1Content || '',
      tile2Content: d.tile2Content || '',
      tile3Content: d.tile3Content || '',
      tile4Content: d.tile4Content || '',
      ctaText: d.ctaText || '',
      ctaUrl: d.ctaUrl || '#'
    });
  };
})();
