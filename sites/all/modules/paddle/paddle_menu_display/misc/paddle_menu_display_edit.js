(function ($) {
  $(document).ready(function(){
    $('#add_new_path_root').click(function(){
      var fieldset = $('.first_empty_path_root_item').clone().removeClass('first_empty_path_root_item');
      var id = fieldset.attr('id');
      var new_id = $('.path_root_pair').length;
      fieldset.attr('id', 'edit-group-' + new_id);
      $('#add_new_path_root').before(fieldset);

      $('#edit-group-' + new_id + ' .path_roots_path').attr('id', 'edit-path-roots-path-' + new_id);
      $('#edit-group-' + new_id + ' .path_roots_path').attr('name', 'path_roots_path_' + new_id);
      $('#edit-group-' + new_id + ' .path_roots_path').val('');

      $('#edit-group-' + new_id + ' .path_roots_root_item').attr('id', 'edit-path-roots-root-item-' + new_id);
      $('#edit-group-' + new_id + ' .path_roots_root_item').attr('name', 'path_roots_root_item_' + new_id);
      $('#edit-group-' + new_id + ' .path_roots_root_item').val('');
    });
  });
})(jQuery);
