(function ($) {
Drupal.ajax.prototype.commands.ctools_modal_open = function(ajax, response, status) {
  // Create a drupal ajax object
  var element_settings = {};
  element_settings.url = response.url
  // We use a custom event to avoid events bubbling up from descendants
  // to trigger the modal as well.
  element_settings.event = 'open_modal';
  element_settings.progress = { type: 'throbber' };

  // Drupal's AJAX requires a triggering element.
  // Let's use the body element.
  element = $('body');
  var base = response.url;
  Drupal.ajax[base] = new Drupal.ajax(base, element, element_settings);

  Drupal.CTools.Modal.show(response.modal_type);

  // And trigger the event.
  element.trigger('open_modal');

  // Unbind the event from body so it doesn't fire twice next time.
  element.unbind('open_modal');
};

  Drupal.paddle = Drupal.paddle || {};
  Drupal.paddle.scald = Drupal.paddle.scald || {};

  Drupal.paddle.scald.insertAtomIntoCKEditor = function(ajax, response, status)
  {
    CKEDITOR.instances[response.editor_id].insertHtml(response.html);
  };
  Drupal.ajax.prototype.commands.insert_atom_into_ckeditor = Drupal.paddle.scald.insertAtomIntoCKEditor;

  /**
   * Ajax command to add an atom to an atom select field.
   */
  Drupal.paddle.scald.insertAtomIntoField = function(ajax, response, status)
  {
    // Insert the atom's preview & remove button.
    var field_container = '#' + response.field + '-container';
    $(field_container + ' .selected-items').append(response.html);

    // Let Drupal attach behaviors to our new HTML element.
    var updated_items = $(field_container + ' .selected-items > div');
    Drupal.attachBehaviors(updated_items[updated_items.length-1]);

    // Get the selected atom ids from the hidden field and put them in an array.
    var field = $('#' + response.field);
    var atoms = field.val().split(',');

    // For some reason our split adds an empty string at the beginning of the
    // array in certain cases.
    if (atoms.length >= 1 && atoms[0] === "") {
      atoms.shift();
    }

    // Add the new atom to the end of the array.
    atoms.push(response.atom_id);

    // Merge the array back into a string and put it back in the hidden field.
    field.val(atoms.join(','));
    // Trigger a change event on that hidden input field because those field
    // types don't get that event triggered by default. And indicate which
    // position in which container was changed.
    field.trigger('change', [ $(field_container), atoms.length - 1 ]);

    // If the new number of atoms is higher then or equal to the allowed number,
    // hide the add button if it was visible.
    var cardinality = field.attr('data-cardinality');
    if (atoms.length >= cardinality && cardinality >= 0) {
      $(field_container + ' .add-button').addClass('hidden');
    }
  }
  Drupal.ajax.prototype.commands.insert_atom_into_field = Drupal.paddle.scald.insertAtomIntoField;

  /**
   * Click event handler for the remove button in an atom select field.
   *
   * @param targetElement
   *   The remove button that fired the event.
   *
   * @returns bool
   *   Always returns false to prevent the remove button link from firing.
   */
  Drupal.paddle.scald.removeAtomFromFieldHandler = function(targetElement)
  {
    // Make sure all jQuery functionality is available on the element.
    targetElement = $(targetElement);

    // Get some other elements that we will need.
    var atom = targetElement.parent();
    var atom_container = atom.parent();
    var field_container = atom_container.parent().parent();
    var field = field_container.find('input[type="hidden"].atom-ids')[0];
    field = $(field);

    // Get the position of the atom that has to be removed in the list of atoms.
    var position = atom_container.children().index(atom);

    // Remove the atom at that position from the hidden value field.
    var atom_ids = field.val().split(',');
    atom_ids = jQuery.grep(atom_ids, function(atom_id, index) {
      return index != position;
    });
    field.val(atom_ids.join(','));
    // Trigger a change event on that hidden input field because those field
    // types don't get that event triggered by default. And indicate which
    // position in which container was changed.
    field.trigger('change', [ field_container, position ]);

    // Remove the atom preview & close button.
    atom.remove();

    // If the new number of atoms is lower then the allowed number, make the add
    // button visible if it was hidden.
    var cardinality = field.attr('data-cardinality');
    if (atom_ids.length < cardinality) {
      field_container.find('.add-button').removeClass('hidden');
    }

    return false;
  };

})(jQuery);
