<?php

/**
 * @file
 * Definition of paddle_reference_tracker_handler_field_entity_id_link.
 */

/**
 * Field handler to render the link for the entity.
 *
 * @ingroup views_field_handlers
 */
class paddle_reference_tracker_handler_field_entity_id_link extends views_handler_field {

  /**
   * Renders the link.
   */
  public function render($values) {
    $entity_id = $this->get_value($values);
    $entity_type = $values->{$this->aliases['entity_type']};
    $entity = entity_load_single($entity_type, $entity_id);

    switch ($entity_type) {
      case 'node':
        if (!user_access('manage paddle_content_manager') || !node_access('view', $entity)) {
          return;
        }

        $this->options['alter']['make_link'] = TRUE;
        $this->options['alter']['path'] = 'admin/content_manager/node/' . $entity->nid . '/view';
        return $entity->title;
    }

    return $entity_id;
  }

}
