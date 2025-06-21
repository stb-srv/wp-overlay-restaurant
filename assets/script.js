jQuery(document).ready(function($){
  var $input    = $('#ffo-search-input');
  var $overlay  = $('#ffo-search-overlay');
  var $results  = $('#ffo-search-results');
  var $source   = $('#ffo-search-source .ffo-item');

  $input.on('input', function(){
    var term = $(this).val().toLowerCase().trim();
    if(term.length){
      $results.empty();
      $source.each(function(){
        var $item = $(this);
        var title = $item.find('.ffo-item-title').text().toLowerCase();
        if(title.indexOf(term) !== -1){
          $results.append($item.clone());
        }
      });
      $overlay.fadeIn(200);
    } else {
      $overlay.fadeOut(200);
    }
  });
  $('#ffo-overlay-close').on('click', function(){ $overlay.fadeOut(200); });
  $overlay.on('click', function(e){ if(e.target === this) $overlay.fadeOut(200); });
});
