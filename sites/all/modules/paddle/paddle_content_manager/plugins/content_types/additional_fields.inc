<?php
/**
 * @file
 * Plugin to render the edit widgets of a set of predefined entity fields.
 */

/**
 * Plugin definition.
 */
$plugin = array(
  'title' => t('Additional fields'),
  'description' => t('Renders the edit widgets of a set of predefined entity fields'),
  'category' => t('Paddle Content Manager'),
  'required context' => new ctools_context_required(t('Node'), 'node'),
);

/**
 * Render callback for the additional fields plugin.
 */
function paddle_content_manager_additional_fields_content_type_render($subtype, $conf, $args, $context) {
  if (empty($context) || empty($context->data) || !isset($context->form)) {
    return;
  }

  $block = new stdClass();
  $block->subject = '';
  $block->content = array();

  // First render all additional fields that are part of a group.
  $field_groups = module_invoke_all('paddle_content_manager_additional_fields_groups');
  drupal_alter('paddle_content_manager_additional_fields_groups', $field_groups, $context);

  uasort($field_groups, 'drupal_sort_weight');
  foreach ($field_groups as $group_name => $group_info) {
    $content = _paddle_content_manager_additional_fields_render_fields($group_info['fields'], $context);
    if (!empty($content)) {
      $block->content[] = array(
        '#markup' => theme('additional_fields_group', array(
            // @codingStandardsIgnoreStart
            'title' => check_plain($group_info['label']),
            // @codingStandardsIgnoreEnd
            'css_class' => drupal_html_class($group_name),
            'name' => drupal_html_class($group_name),
            'content' => $content,
          )
        ),
        '#weight' => $group_info['weight'],
      );
    }
  }

  // Put the remaining fields at default weight 0, so field groups can position
  // themselves before or after these.
  $field_names = module_invoke_all('paddle_content_manager_additional_fields');
  if ($content = _paddle_content_manager_additional_fields_render_fields($field_names, $context)) {
    $block->content[] = array(
      '#markup' => $content,
      '#weight' => 0,
    );
  }

  // Don't return anything if there are no additional fields.
  return !empty($block->content) ? $block : NULL;
}

/**
 * Render a list of fields.
 *
 * @param array $field_names
 *   Which fields to render.
 * @param object $context
 *   The context holds node data.
 *
 * @return string
 *   The combined rendered HTML.
 */
function _paddle_content_manager_additional_fields_render_fields($field_names, $context) {
  $rendered_fields = '';
  $entity = $context->data;
  $entity_type = 'node';
  $ids = entity_extract_ids($entity_type, $entity);

  foreach ($field_names as $field_name) {
    // Load the entity type's information for this field.
    $field = field_info_instance($entity_type, $field_name, $ids[2]);

    // Do not render if the entity type / bundle does not have this field.
    if (!empty($field)) {
      $rendered_fields .= drupal_render($context->form[$field_name]);
    }
  }

  return $rendered_fields;
}
