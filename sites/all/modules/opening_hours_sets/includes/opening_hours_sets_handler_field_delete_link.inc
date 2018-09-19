<?php

/**
 * @file
 * Definition of opening_hours_sets_handler_field_delete_link.
 */

/**
 * Field handler to display a delete opening hours set entity link.
 *
 * @ingroup views_field_handlers
 */
class opening_hours_sets_handler_field_delete_link extends views_handler_field_entity {

  /**
   * Renders the definition.
   */
  public function render($values) {
    $entity = entity_load('opening_hours_set', array($values->ohsid));
    $entity = $entity[$values->ohsid];
    $attributes = array(
      'class' => array(
        'ui-icon',
        'ui-icon-hiddentext',
        'ui-icon-delete',
        'opening-hours-set-delete-link',
      ),
    );
    return l(t('Delete'), 'admin/content/opening_hours_set/delete/' . $entity->ohsid, array('attributes' => $attributes));
  }
}
