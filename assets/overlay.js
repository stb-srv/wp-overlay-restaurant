(function(){
  function showOverlay(params){
    var opts = Object.assign({
      overlayTitle: '',
      tile1Title: '',
      tile1Text: '',
      tile2Title: '',
      tile2Text: '',
      tile3Title: '',
      tile3Text: '',
      tile4Title: '',
      tile4Text: '',
      ctaText: '',
      ctaUrl: '#',
      overlayTitleSize: '',
      tileTitleSize: '',
      tileTextSize: ''
    }, params || {});

    var overlay = document.createElement('div');
    overlay.className = 'ffo-overlay';
    if(opts.overlayTitleSize) overlay.style.setProperty('--overlay-title-size', opts.overlayTitleSize);
    if(opts.tileTitleSize) overlay.style.setProperty('--tile-title-size', opts.tileTitleSize);
    if(opts.tileTextSize) overlay.style.setProperty('--tile-text-size', opts.tileTextSize);

    overlay.innerHTML =
      '<div class="ffo-overlay__inner">' +
        '<button class="ffo-overlay__close" aria-label="Close Overlay">&times;</button>' +
        '<h2 class="ffo-overlay__title">' + opts.overlayTitle + '</h2>' +
        '<div class="ffo-overlay__grid">' +
          '<div class="ffo-overlay__tile">' +
            '<h3 class="ffo-overlay__tile-title">' + opts.tile1Title + '</h3>' +
            '<p class="ffo-overlay__tile-text">' + opts.tile1Text + '</p>' +
          '</div>' +
          '<div class="ffo-overlay__tile">' +
            '<h3 class="ffo-overlay__tile-title">' + opts.tile2Title + '</h3>' +
            '<p class="ffo-overlay__tile-text">' + opts.tile2Text + '</p>' +
          '</div>' +
          '<div class="ffo-overlay__tile">' +
            '<h3 class="ffo-overlay__tile-title">' + opts.tile3Title + '</h3>' +
            '<p class="ffo-overlay__tile-text">' + opts.tile3Text + '</p>' +
          '</div>' +
          '<div class="ffo-overlay__tile">' +
            '<h3 class="ffo-overlay__tile-title">' + opts.tile4Title + '</h3>' +
            '<p class="ffo-overlay__tile-text">' + opts.tile4Text + '</p>' +
          '</div>' +
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
      tile1Title: d.tile1Title || '',
      tile1Text: d.tile1Text || '',
      tile2Title: d.tile2Title || '',
      tile2Text: d.tile2Text || '',
      tile3Title: d.tile3Title || '',
      tile3Text: d.tile3Text || '',
      tile4Title: d.tile4Title || '',
      tile4Text: d.tile4Text || '',
      ctaText: d.ctaText || '',
      ctaUrl: d.ctaUrl || '#',
      overlayTitleSize: d.overlayTitleSize || '',
      tileTitleSize: d.tileTitleSize || '',
      tileTextSize: d.tileTextSize || ''
    });
  };
})();
