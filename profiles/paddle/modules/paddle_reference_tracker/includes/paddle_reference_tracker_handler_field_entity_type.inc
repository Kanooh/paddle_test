<?php

/**
 * @file
 * Definition of paddle_reference_tracker_handler_field_entity_type.
 */

/**
 * Field handler to render the type of the entity.
 *
 * @ingroup views_field_handlers
 */
class paddle_reference_tracker_handler_field_entity_type extends views_handler_field {

  /**
   * Renders the type.
   */
  public function render($values) {
    $info = entity_get_info($this->get_value($values));

    return !empty($info) ? $info['label'] : '';
  }

}
