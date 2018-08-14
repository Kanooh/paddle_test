<?php
/**
 * @file
 * Renders the path to the video thumbnail of a Scald Video atom.
 */

class paddle_scald_handler_field_video_thumbnail extends views_handler_field_entity {
  public function render($values) {
    $atom = scald_atom_load($values->entity);

    if ($atom && $atom->provider == 'paddle_scald_youtube') {
      return file_create_url($atom->thumbnail_source);
    }

    return '';
  }
}
