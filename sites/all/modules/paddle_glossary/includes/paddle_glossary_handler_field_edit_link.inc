<?php

/**
 * @file
 * Definition of paddle_glossary_handler_field_edit_link.
 */

/**
 * Field handler to display an edit Glossary definition entity link.
 *
 * @ingroup views_field_handlers
 */
class paddle_glossary_handler_field_edit_link extends views_handler_field_entity {

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
        'ui-icon-edit',
        'ctools-use-modal',
        'glossary-definition-edit-link',
      ),
    );
    return l(t('Edit'), 'admin/content/paddle_glossary_definition/nojs/edit/' . $entity->gdid, array('attributes' => $attributes));
  }
}
