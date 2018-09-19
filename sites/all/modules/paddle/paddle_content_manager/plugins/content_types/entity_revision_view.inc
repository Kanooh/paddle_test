<?php
/**
 * @file
 * Content type plugin to expose rendered entity revisions, view mode
 * configuration still available.
 *
 * Based on the entity_view plugin of the entity module.
 */

$plugin = array(
  'title' => t('Rendered entity revision'),
  'defaults' => array('view_mode' => 'full'),
  'content type' => 'paddle_content_manager_entity_revision_view_content_type_info',
);

/**
 * Get the entity content type info.
 */
function paddle_content_manager_entity_revision_view_content_type_info($entity_type) {
  $types = paddle_content_manager_entity_revision_view_content_type_content_types();
  if (isset($types[$entity_type])) {
    return $types[$entity_type];
  }
}

/**
 * Implements hook_PLUGIN_content_type_content_types().
 *
 * Rendered entity revision uses entity types machine name as subtype name.
 */
function paddle_content_manager_entity_revision_view_content_type_content_types() {
  $types = array();
  $entities = entity_get_info();

  foreach ($entities as $entity_type => $info) {
    if (!empty($info['entity keys']['revision']) && entity_type_supports($entity_type, 'view')) {
      $types[$entity_type] = array(
        'title' => t('Rendered @entity_type revision', array('@entity_type' => $info['label'])),
        'category' => t('Entity'),
        'required context' => new ctools_context_required(t('Entity'), $entity_type),
      );
    }
  }

  return $types;
}

/**
 * Returns an edit form for a entity.
 *
 * Rendered entity use entity types machine name as subtype name.
 *
 * @see entity_entity_view_get_content_types()
 */
function paddle_content_manager_entity_revision_view_content_type_edit_form($form, &$form_state) {
  return entity_entity_view_content_type_edit_form($form, $form_state);
}

/**
 * Save selected view mode.
 */
function paddle_content_manager_entity_revision_view_content_type_edit_form_submit(&$form, &$form_state) {

  entity_entity_view_content_type_edit_form_submit($form, $form_state);
}

/**
 * Implements hook_PLUGIN_content_type_render().
 *
 * Ctools requires us to return a block.
 *
 * @see ctools_content_render()
 */
function paddle_content_manager_entity_revision_view_content_type_render($entity_type, $conf, $panel_args, $context) {
  if ($context->empty) {
    return array();
  }

  $block = new stdClass();
  $block->module = 'entity';
  $block->delta = $entity_type . '-' . str_replace('-', '_', $conf['view_mode']);

  $entity = $context->data;

  list($entity_id, $vid, $bundle) = entity_extract_ids($entity_type, $entity);

  $entity_revision = entity_revision_load($entity_type, $vid);
  $block->content = entity_view($entity_type, array($entity_id => $entity_revision), $conf['view_mode']);
  return $block;
}

/**
 * Implements hook_PLUGIN_content_type_admin_title().
 *
 * Returns the administrative title for a type.
 */
function paddle_content_manager_entity_revision_view_content_type_admin_title($entity_type, $conf, $contexts) {
  $entity_info = entity_get_info($entity_type);
  $view_mode = $conf['view_mode'];
  if (isset($entity_info['view modes'][$view_mode])) {
    $view_mode = $entity_info['view modes'][$view_mode]['label'];
  }
  return t('Rendered @entity_type revision using view mode "@view_mode"', array('@entity_type' => $entity_info['label'], '@view_mode' => $view_mode));
}
