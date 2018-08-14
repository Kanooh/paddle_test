<?php
/**
 * @file
 * API documentation for the Reference tracker module.
 */

/**
 * Decide whether an entity is allowed to be parsed or not.
 *
 * @param string $entity_type
 *   The entity type.
 * @param object $entity
 *   The entity object.
 * @param bool $save
 *   Whether the parsed results are to be saved to the database or not.
 *
 * @return bool
 *   TRUE if this entity may be parsed, FALSE if not.
 */
function hook_reference_tracker_parse_entity($entity_type, $entity, $save) {
  // Do not allow nodes of bundle 'my_bundle' to be parsed.
  if ($entity_type === 'node') {
    list(,, $bundle) = entity_extract_ids($entity_type, $entity);
    if ($bundle === 'my_bundle') {
      return FALSE;
    }
  }

  return TRUE;
}

/**
 * Alter the results for a parsed entity.
 *
 * @param array $references
 *   Associative multidimensional array in the following structure:
 *   array(
 *     field name => array(
 *       language => array(
 *         referenced entity_type => array(
 *           referenced entity id,
 *           referenced entity id,
 *           ...
 *         ),
 *         ...
 *       ),
 *       ...
 *     ),
 *     ...
 *   );
 *   Empty array if no references were found.
 * @param array $context
 *   Associative array with the following key/value pairs:
 *   - 'entity_type': the entity type;
 *   - 'entity': the entity object.
 */
function hook_reference_tracker_entity_references_alter(&$references, $context) {
  // Remove results of a specific field.
  if (!empty($references['field_my_field'])) {
    unset($references['field_my_field']);
  }
}

/**
 * Alter the results for a parsed field.
 *
 * @param array $references
 *   Associative multidimensional array in the following structure:
 *   array(
 *     language => array(
 *       referenced entity_type => array(
 *         referenced entity id,
 *         referenced entity id,
 *         ...
 *       ),
 *       ...
 *     ),
 *     ...
 *   );
 *   Empty array if no references were found.
 * @param array $context
 *   Associative array with the following key/value pairs:
 *   - 'entity_type': the entity type;
 *   - 'entity': the entity object;
 *   - 'field_instance_id': identifier of the field instance;
 *   - 'field_name': name of the field;
 *   - 'value_key': the value key of the value that was parsed;
 *   - 'parser_callback': parser callback that was used for parsing the value;
 *   - 'field_value': the complete value array of the field.
 */
function hook_reference_tracker_parse_field_alter(&$references, $context) {
  // Remove LANGUAGE_NONE results.
  if (!empty($references[LANGUAGE_NONE])) {
    unset($references[LANGUAGE_NONE]);
  }
}

/**
 * Define one or more supported field types.
 *
 * @return array
 *   Return the field type parse info as an associative array in the
 *   following structure:
 *   array(
 *     field type (eg. entityreference) => array(
 *       value key (eg. target_id) => array(
 *         value_type => type (eg. entity_id),
 *         (optional) reference_entity_type => entity type (eg. node),
 *         (optional) reference_entity_type_callback => a function,
 *       ),
 *       ...
 *     ),
 *     ...
 *   )
 */
function hook_reference_tracker_field_types_parse_info() {
  $field_type_parse_info = array();

  $field_type_parse_info['entityreference'] = array(
    'target_id' => array(
      'value_type' => 'entity_id',
      'reference_entity_type_callback' => 'reference_tracker_entityreference_entity_type_callback',
    ),
  );

  $field_type_parse_info['taxonomy_term_reference'] = array(
    'tid' => array(
      'value_type' => 'entity_id',
      'reference_entity_type' => 'taxonomy_term',
    ),
  );

  return $field_type_parse_info;
}

/**
 * Alter field types parse info.
 *
 * This info is collected by the various
 * hook_reference_tracker_field_types_parse_info() hook implementations.
 *
 * @see hook_reference_tracker_field_types_parse_info()
 */
function hook_reference_tracker_field_types_parse_info_alter(&$field_type_parse_info) {
  // Ignore the 'summary' value for text_with_summary field types.
  unset($field_type_parse_info['text_with_summary']['summary']);
}

/**
 * Define one or more supported value types.
 *
 * @return array
 *   Return the value type parse info as an associative array in the
 *   following structure:
 *   array(
 *     value type (eg. entity_id) => array(
 *       parser_callback => a function
 *     ),
 *     ...
 *   )
 *
 *   The parser callback should be a function which receives 2 arguments:
 *   - $value: the value to parse
 *   - $context: Associative array with various information (entity_type,
 *     entity, field_name, etc.)
 */
function hook_reference_tracker_value_types_parse_info() {
  $value_types_parse_info = array();

  $value_types_parse_info['text'] = array(
    'parser_callback' => 'reference_tracker_text_value_parser',
  );

  $value_types_parse_info['entity_id'] = array(
    'parser_callback' => 'reference_tracker_entity_id_value_parser',
  );

  return $value_types_parse_info;
}

/**
 * Alter value types parse info.
 *
 * This info is collected by the various
 * hook_reference_tracker_value_types_parse_info() hook implementations.
 *
 * @see hook_reference_tracker_value_types_parse_info()
 */
function hook_reference_tracker_value_types_parse_info_alter(&$field_type_parse_info) {
  // Use our own custom value type parser for 'text' values.
  $field_type_parse_info['text']['parser_callback'] = 'my_custom_text_value_parser';
}

/**
 * Define one or more supported entity path parts.
 *
 * Entity path parts are used by the 'text' parser to identify references to
 * entities based on a part of their path.
 *
 * @return array
 *   An associative array keyed by the entity type and as value the entity path.
 */
function hook_reference_tracker_entity_path_parts() {
  $entity_path_parts = array(
    'node' => 'node',
    'taxonomy_term' => 'taxonomy/term',
    'user' => 'user',
  );

  return $entity_path_parts;
}

/**
 * Alter entity paths collected by the various.
 *
 * @see hook_reference_tracker_entity_path_parts()
 */
function hook_reference_tracker_entity_path_parts_alter(&$entity_path_parts) {
  // Not interested in user paths.
  unset($entity_path_parts['user']);
}
