<?php
/**
 * @file
 * Renders the URL to the file of a Scald atom.
 */

class paddle_scald_handler_field_atom_file_path extends views_handler_field_entity {
  public function render($values) {
    $atom = scald_atom_load($values->entity);

    if ($atom && $atom->provider != 'paddle_scald_youtube') {
      return file_create_url($atom->file_source);
    }

    return '';
  }
}
