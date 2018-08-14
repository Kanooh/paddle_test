<?php
/**
 * @file
 * Drush integration for the Reference Tracker module.
 */

/**
 * Implements hook_drush_help().
 */
function reference_tracker_drush_help($section) {
  switch ($section) {
    case 'drush:reference-tracker-rebuild':
      $help  = dt('This will parse all entities for outbound references.');
      $help .= dt('If no entity types are specified, you will be presented with a list of valid entity types to choose from.');
      $help .= dt('To rebuild all entity types, do not specify any entity types, and choose the option "All" from the options presented.');
      return $help;

    case 'drush:reference-tracker-clear':
      $help  = dt('This will clear all entries in the Reference Tracker table.');
      $help .= dt('If no entity types are specified, you will be presented with a list of valid entity types to choose from.');
      $help .= dt('To clear all entries, do not specify any entity types, and choose the option "All" from the options presented.');
      return $help;
  }
}

/**
 * Implements hook_drush_command().
 */
function reference_tracker_drush_command() {
  $items = array();

  $items['reference-tracker-rebuild'] = array(
    'callback' => 'drush_reference_tracker_rebuild',
    'description' => dt('Parse entities for outbound references.'),
    'arguments' => array(
      'entity_types' => dt('A space delimited list of entity types.'),
    ),
    'bootstrap' => DRUSH_BOOTSTRAP_DRUPAL_FULL,
    'aliases' => array('rtr'),
    'options' => array(
      'batch_process_limit' => dt('Amount of entities to be parsed per process.'),
    ),
    'examples' => array(
      'drush rtr node' => dt('Parses all node entities.'),
      'drush rtr' => dt('Will present you a list of entity types to choose from, as well as an option to parse all valid entity types.'),
    ),
  );

  $items['reference-tracker-clear'] = array(
    'callback' => 'drush_reference_tracker_clear',
    'description' => dt('Clear entries from the Reference Tracker table.'),
    'arguments' => array(
      'entity_types' => dt('A space delimited list of entity types.'),
    ),
    'bootstrap' => DRUSH_BOOTSTRAP_DRUPAL_FULL,
    'aliases' => array('rtc'),
    'examples' => array(
      'drush rtc node' => 'Clears all node entries.',
      'drush rtr' => 'Will present you a list of entity types to choose from, as well as an option to clear all entries.',
    ),
  );

  return $items;
}

/**
 * Drush callback for 'reference-tracker-rebuild'.
 */
function drush_reference_tracker_rebuild() {
  $entity_types = func_get_args();
  $enabled_entity_types = reference_tracker_enabled_entity_types();

  // If no entity types were specified, the user is prompted with a list
  // to choose from. If 'all' is given, all entity types are assumed.
  if (empty($entity_types)) {
    $options = array_merge(array('all'), $enabled_entity_types);
    $choice = drush_choice(array_combine($options, $options), 'Enter a number to choose which entity type to rebuild.', '!value');
    if ($choice !== FALSE) {
      if ($choice === 'all') {
        $entity_types = $enabled_entity_types;
      }
      else {
        $entity_types = array($choice);
      }
    }
    else {
      return;
    }
  }
  elseif ($entity_types[0] === 'all') {
    $entity_types = $enabled_entity_types;
  }
  else {
    foreach ($entity_types as $entity_type) {
      if (!in_array($entity_type, $enabled_entity_types)) {
        drush_set_error(dt('Entity type "@entity_type" is invalid.', array('@entity_type' => $entity_type)));
        return;
      }
    }
  }

  // A batch process list can be defined.
  $batch_process_limit = REFERENCE_TRACKER_DEFAULT_BATCH_PROCESS_LIMIT;
  $option = drush_get_option_list('batch_process_limit');
  if (!empty($option[0]) && is_numeric($option[0])) {
    $batch_process_limit = $option[0];
  }

  $batch = array(
    'operations' => array(),
    'finished' => 'reference_tracker_rebuild_batch_finish',
    'title' => dt('Rebuilding the Reference Tracker table.'),
    'init_message' => dt('Started rebuilding the Reference Tracker table.'),
    'progress_message' => dt('Rebuilding the Reference Tracker table.'),
    'error_message' => dt('An error occurred during rebuild of the Reference Tracker table.'),
  );

  foreach ($entity_types as $entity_type) {
    $batch['operations'][] = array('reference_tracker_rebuild_batch_clear', array($entity_type));
    $batch['operations'][] = array(
      'reference_tracker_rebuild_batch_worker',
      array($entity_type, $batch_process_limit),
    );
  }

  batch_set($batch);
  $batch =& batch_get();
  $batch['progressive'] = FALSE;
  drush_backend_batch_process();
}

/**
 * Drush callback for 'reference-tracker-clear'.
 */
function drush_reference_tracker_clear($entity_types = NULL) {
  $entity_types = func_get_args();
  $enabled_entity_types = reference_tracker_enabled_entity_types();

  // If no entity types were specified, the user is prompted with a list
  // to choose from. If 'all' is given, all entity types are assumed.
  if (empty($entity_types)) {
    $options = array_merge(array('all'), $enabled_entity_types);
    $choice = drush_choice(array_combine($options, $options), 'Enter a number to choose which entity type to rebuild.', '!value');
    if ($choice !== FALSE) {
      if ($choice === 'all') {
        $entity_types = $enabled_entity_types;
      }
      else {
        $entity_types = array($choice);
      }
    }
    else {
      return;
    }
  }
  elseif ($entity_types[0] === 'all') {
    $entity_types = $enabled_entity_types;
  }
  else {
    foreach ($entity_types as $entity_type) {
      if (!in_array($entity_type, $enabled_entity_types)) {
        drush_set_error(dt('Entity type "@entity_type" is invalid.', array('@entity_type' => $entity_type)));
        return;
      }
    }
  }

  $count = reference_tracker_clear($entity_types);
  drush_log(dt('@count entries were cleared from the Reference Tracker table.', array('@count' => $count)), 'success');
}
