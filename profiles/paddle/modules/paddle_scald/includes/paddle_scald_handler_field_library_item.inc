<?php
/**
 * @file
 * Renders a full Scald atom.
 */

class paddle_scald_handler_field_library_item extends views_handler_field_entity {
  public function render($values) {
    return array(
      '#theme' => 'paddle_scald_library_atom',
      '#id' => $values->entity,
      '#modal' => FALSE,
      '#library_state' => array(),
    );
  }
}
