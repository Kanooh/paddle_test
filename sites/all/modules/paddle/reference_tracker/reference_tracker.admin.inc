<?php
/**
 * @file
 * Administrative page callbacks for Reference Tracker module.
 */

/**
 * Configuration form.
 */
function reference_tracker_settings_form($form, &$form_state) {
  $entity_info = entity_get_info();

  // Entity types.
  $form['reference_tracker_disabled_entity_types'] = array(
    '#type' => 'checkboxes',
    '#title' => t('Disabled entity types'),
    '#description' => t('Configure the entity types which should be skipped. Only entity types which are fieldable and have a base table are parseable.'),
    '#options' => array(),
    '#default_value' => variable_get('reference_tracker_disabled_entity_types', array()),
  );
  foreach ($entity_info as $entity_type => $info) {
    if (reference_tracker_is_valid_entity_type($entity_type)) {
      $form['reference_tracker_disabled_entity_types']['#options'][$entity_type] = $info['label'];
    }
  }

  // Extra settings.
  $form['extra_settings'] = array(
    '#type' => 'fieldset',
    '#title' => t('Extra settings'),
  );

  $form['extra_settings']['reference_tracker_local_paths'] = array(
    '#type' => 'textarea',
    '#title' => t('Base paths considered internal'),
    '#default_value' => variable_get('reference_tracker_local_paths'),
    '#description' => t('References to entities are also searched in text values, based upon the "href" attributes in HTML anchors. If this site is available at more than one base path or URL, enter them here, separated by line breaks. For example, if both <code>http://example.com/</code> and <code>http://anotherdomain.org/example/</code> could point to internal entities, you would enter both those URLs here (one on each line).'),
  );

  if (module_exists('workbench_moderation')) {
    $form['extra_settings']['workbench_moderation'] = array(
      '#type' => 'fieldset',
      '#title' => t('Workbench moderation'),
      '#collapsible' => FALSE,
    );
    $form['extra_settings']['workbench_moderation']['reference_tracker_workbench_moderation_track_revision'] = array(
      '#type' => 'radios',
      '#title' => t('Revision to track'),
      '#description' => t('Choose which revision type should be tracked when Workbench Moderation is enabled.'),
      '#options' => array(
        REFERENCE_TRACKER_WORKBENCH_MODERATION_TRACK_CURRENT => t('Latest revision'),
        REFERENCE_TRACKER_WORKBENCH_MODERATION_TRACK_PUBLISHED => t('Published revision'),
        REFERENCE_TRACKER_WORKBENCH_MODERATION_TRACK_BOTH => t('Both latest draft and published revisions'),
      ),
      '#default_value' => variable_get('reference_tracker_workbench_moderation_track_revision', 0),
    );
  }

  if (module_exists('field_collection')) {
    $form['extra_settings']['reference_tracker_field_collection_use_host'] = array(
      '#type' => 'checkbox',
      '#title' => t('Field collection: Treat references as host references'),
      '#description' => t('Field collection are special entities in the sense that they are part of another entity, a host entity. By default, field collections are parsed as if they were stand-alone entities. Enable this option if you wish to consider references made by a field collection to be references made by the host entity. When changing this setting, you will have to rebuild the tracker manually.'),
      '#default_value' => variable_get('reference_tracker_field_collection_use_host'),
    );
  }

  if (module_exists('paragraphs')) {
    $form['extra_settings']['reference_tracker_paragraphs_use_host'] = array(
      '#type' => 'checkbox',
      '#title' => t('Paragraph: Treat references as host references'),
      '#description' => t('Paragraphs are special entities in the sense that they are part of another entity, a host entity. By default, paragraphs are parsed as if they were stand-alone entities. Enable this option if you wish to consider references made by a paragraph to be references made by the host entity. When changing this setting, you will have to rebuild the tracker manually.'),
      '#default_value' => variable_get('reference_tracker_paragraphs_use_host'),
    );
  }

  $form = system_settings_form($form);
  $form['#submit'][] = 'reference_tracker_settings_form_submit';

  return $form;
}

/**
 * Validate handler for the settings form.
 */
function reference_tracker_settings_form_validate($form, &$form_state) {
  $form_state['values']['reference_tracker_disabled_entity_types'] = array_values(array_filter($form_state['values']['reference_tracker_disabled_entity_types']));
}

/**
 * Submit handler for the settings form.
 */
function reference_tracker_settings_form_submit($form, &$form_state) {
  reference_tracker_enabled_entity_types(TRUE);
}

/**
 * Batch rebuild form.
 */
function reference_tracker_rebuild_form($form, &$form_state) {
  // Entity types.
  $form['reference_tracker_entity_types'] = array(
    '#type' => 'checkboxes',
    '#title' => t('Entity types'),
    '#description' => t('Select the entity types you manually want to rebuild.'),
    '#options' => array(),
    '#required' => TRUE,
  );

  $entity_info = entity_get_info();
  $entity_types = reference_tracker_enabled_entity_types();
  foreach ($entity_types as $entity_type) {
    if (isset($entity_info[$entity_type])) {
      $form['reference_tracker_entity_types']['#options'][$entity_type] = $entity_info[$entity_type]['label'];
    }
  }

  // Batch process limit.
  $form['reference_tracker_batch_process_limit'] = array(
    '#type' => 'textfield',
    '#title' => t('Batch process limit'),
    '#description' => t('Enter the batch process limit. This number decides how many entities will be parsed per batch process. Lower this number if you are experiencing performance issues.'),
    '#default_value' => variable_get('reference_tracker_batch_process_limit', REFERENCE_TRACKER_DEFAULT_BATCH_PROCESS_LIMIT),
    '#required' => TRUE,
  );

  $form['actions'] = array('#type' => 'actions');
  $form['actions']['submit'] = array('#type' => 'submit', '#value' => t('Start'));

  return $form;
}

/**
 * Submit handler for the batch rebuild form.
 */
function reference_tracker_rebuild_form_submit($form, &$form_state) {
  $entity_types = array_values(array_filter($form_state['values']['reference_tracker_entity_types']));
  $batch_process_limit = $form_state['values']['reference_tracker_batch_process_limit'];

  $batch = array(
    'title' => t('Rebuilding the Reference Tracker table'),
    'operations' => array(),
    'finished' => 'reference_tracker_rebuild_batch_finish',
    'file' => drupal_get_path('module', 'reference_tracker') . '/reference_tracker.admin.inc',
  );

  foreach ($entity_types as $entity_type) {
    $batch['operations'][] = array('reference_tracker_rebuild_batch_clear', array($entity_type));
    $batch['operations'][] = array(
      'reference_tracker_rebuild_batch_worker',
      array($entity_type, $batch_process_limit),
    );
  }

  batch_set($batch);
}

/**
 * Clear form.
 */
function reference_tracker_clear_form($form, &$form_state) {
  // Entity types.
  $form['reference_tracker_entity_types'] = array(
    '#type' => 'checkboxes',
    '#title' => t('Entity types'),
    '#description' => t('Select the entity types you manually want to clear.'),
    '#options' => array(),
    '#required' => TRUE,
  );

  $entity_info = entity_get_info();
  $entity_types = reference_tracker_enabled_entity_types();
  foreach ($entity_types as $entity_type) {
    if (isset($entity_info[$entity_type])) {
      $form['reference_tracker_entity_types']['#options'][$entity_type] = $entity_info[$entity_type]['label'];
    }
  }

  $form['actions'] = array('#type' => 'actions');
  $form['actions']['submit'] = array('#type' => 'submit', '#value' => t('Start'));

  return $form;
}

/**
 * Submit handler for the clear form.
 */
function reference_tracker_clear_form_submit($form, &$form_state) {
  $entity_types = array_values(array_filter($form_state['values']['reference_tracker_entity_types']));
  reference_tracker_clear($entity_types);
  drupal_set_message(t('Reference Tracker table was cleared for the selected entity types.'), 'status');
}
