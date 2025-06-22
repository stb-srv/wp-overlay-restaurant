jQuery(document).ready(function($){
  function updateVisibility(module){
    var type = module.find('.ffo-layout-type').val();
    if(type === 'fullwidth'){
      module.find('.ffo-field-fullwidth').show();
      module.find('.ffo-field-grid').hide();
    } else {
      module.find('.ffo-field-fullwidth').hide();
      module.find('.ffo-field-grid').show();
    }
  }

  $('#ffo-add-module').on('click', function(e){
    e.preventDefault();
    var index = $('#ffo-modules .ffo-module').length;
    var tpl = $('#ffo-module-template').html().replace(/__index__/g, index);
    $('#ffo-modules').append(tpl);
    updateVisibility($('#ffo-modules .ffo-module').last());
  });
  $(document).on('click', '.ffo-remove-module', function(e){
    e.preventDefault();
    $(this).closest('.ffo-module').remove();
  });
  $(document).on('change', '.ffo-layout-type', function(){
    updateVisibility($(this).closest('.ffo-module'));
  });
  $('#ffo-modules .ffo-module').each(function(){
    updateVisibility($(this));
  });

  function updateLayoutFields(){
    var val = $('#ffo_layout_pattern').val();
    if(val === 'fullwidth-2x2-fullwidth'){
      $('.ffo-fw2x2fw-field').closest('.cmb-row').show();
      $('#ffo-fw2x2fw-fields').show();
    }else{
      $('.ffo-fw2x2fw-field').closest('.cmb-row').hide();
      $('#ffo-fw2x2fw-fields').hide();
    }
  }

  $('#ffo_layout_pattern').on('change', updateLayoutFields);
  updateLayoutFields();
});
