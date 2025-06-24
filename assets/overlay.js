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
      tileTextSize: '',
      tile1TitleSize: '',
      tile2TitleSize: '',
      tile3TitleSize: '',
      tile4TitleSize: '',
      tile1TitleColor: '',
      tile2TitleColor: '',
      tile3TitleColor: '',
      tile4TitleColor: '',
      tile1TitleFont: '',
      tile2TitleFont: '',
      tile3TitleFont: '',
      tile4TitleFont: ''
    }, params || {});

    var overlay = document.createElement('div');
    overlay.className = 'ffo-overlay';
    if(opts.overlayTitleSize) overlay.style.setProperty('--overlay-title-size', opts.overlayTitleSize);
    if(opts.tileTitleSize) overlay.style.setProperty('--tile-title-size', opts.tileTitleSize);
    if(opts.tileTextSize) overlay.style.setProperty('--tile-text-size', opts.tileTextSize);

    function buildStyle(size, color, font){
      var s = '';
      if(size) s += 'font-size:' + size + ';';
      if(color) s += 'color:' + color + ';';
      if(font) s += 'font-family:' + font + ';';
      return s ? ' style="' + s + '"' : '';
    }

    overlay.innerHTML =
      '<div class="ffo-overlay__inner">' +
        '<button class="ffo-overlay__close" aria-label="Close Overlay">&times;</button>' +
        '<h2 class="ffo-overlay__title">' + opts.overlayTitle + '</h2>' +
        '<div class="ffo-overlay__grid">' +
          '<div class="ffo-overlay__tile">' +
            '<h3 class="ffo-overlay__tile-title"' +
              buildStyle(opts.tile1TitleSize, opts.tile1TitleColor, opts.tile1TitleFont) +
            '>' + opts.tile1Title + '</h3>' +
            '<p class="ffo-overlay__tile-text">' + opts.tile1Text + '</p>' +
          '</div>' +
          '<div class="ffo-overlay__tile">' +
            '<h3 class="ffo-overlay__tile-title"' +
              buildStyle(opts.tile2TitleSize, opts.tile2TitleColor, opts.tile2TitleFont) +
            '>' + opts.tile2Title + '</h3>' +
            '<p class="ffo-overlay__tile-text">' + opts.tile2Text + '</p>' +
          '</div>' +
          '<div class="ffo-overlay__tile">' +
            '<h3 class="ffo-overlay__tile-title"' +
              buildStyle(opts.tile3TitleSize, opts.tile3TitleColor, opts.tile3TitleFont) +
            '>' + opts.tile3Title + '</h3>' +
            '<p class="ffo-overlay__tile-text">' + opts.tile3Text + '</p>' +
          '</div>' +
          '<div class="ffo-overlay__tile">' +
            '<h3 class="ffo-overlay__tile-title"' +
              buildStyle(opts.tile4TitleSize, opts.tile4TitleColor, opts.tile4TitleFont) +
            '>' + opts.tile4Title + '</h3>' +
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
      tileTextSize: d.tileTextSize || '',
      tile1TitleSize: d.tile1TitleSize || '',
      tile2TitleSize: d.tile2TitleSize || '',
      tile3TitleSize: d.tile3TitleSize || '',
      tile4TitleSize: d.tile4TitleSize || '',
      tile1TitleColor: d.tile1TitleColor || '',
      tile2TitleColor: d.tile2TitleColor || '',
      tile3TitleColor: d.tile3TitleColor || '',
      tile4TitleColor: d.tile4TitleColor || '',
      tile1TitleFont: d.tile1TitleFont || '',
      tile2TitleFont: d.tile2TitleFont || '',
      tile3TitleFont: d.tile3TitleFont || '',
      tile4TitleFont: d.tile4TitleFont || ''
    });
  };
})();
