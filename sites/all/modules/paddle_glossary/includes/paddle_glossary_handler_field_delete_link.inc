<?php

/**
 * @file
 * Definition of paddle_glossary_handler_field_delete_link.
 */

/**
 * Field handler to display a delete Glossary definition entity link.
 *
 * @ingroup views_field_handlers
 */
class paddle_glossary_handler_field_delete_link extends views_handler_field_entity {

  /**
   * Renders the definition.
   */
  public function render($values) {
    $entity = entity_load('paddle_glossary_definition', array($values->gdid));
    $entity = $entity[$values->gdid];
    $attributes = array(
      'class' => array(
        'ui-icon',
        'ui-icon-hiddentext',
        'ui-icon-delete',
        'ctools-use-modal',
        'glossary-definition-delete-link',
      ),
    );
    return l(t('Delete'), 'admin/content/paddle_glossary_definition/nojs/delete/' . $entity->gdid, array('attributes' => $attributes));
  }
}
