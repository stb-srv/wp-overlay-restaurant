jQuery(document).ready(function($){
  $('#ffo-add-module').on('click', function(e){
    e.preventDefault();
    var index = $('#ffo-modules .ffo-module').length;
    var tpl = $('#ffo-module-template').html().replace(/__index__/g, index);
    $('#ffo-modules').append(tpl);
  });
  $(document).on('click', '.ffo-remove-module', function(e){
    e.preventDefault();
    $(this).closest('.ffo-module').remove();
  });
});
