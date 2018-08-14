<?php

/**
 * @file
 * Feeds alterations for the Paddle VUB Client module.
 */

/**
 * Implements hook_feeds_plugins().
 */
function paddle_vub_client_feeds_plugins() {
  $info = array();

  $info['VUBFeedsHTTPFetcher'] = array(
    'name' => 'VUB HTTP Fetcher',
    'description' => 'Download VUB entities from a URL.',
    'handler' => array(
      'parent' => 'FeedsHTTPFetcher',
      'class' => 'VUBFeedsHTTPFetcher',
      'file' => 'VUBFeedsHTTPFetcher.inc',
      'path' => drupal_get_path('module', 'paddle_vub_client') . '/plugins/feeds',
    ),
  );

  return $info;
}

/**
 * Implements hook_feeds_presave().
 */
function paddle_vub_client_feeds_presave(FeedsSource $source, $entity, $item, $entity_id) {
  if ($source->id === 'vub_client_news_importer') {
    $wrapper = entity_metadata_wrapper('node', $entity);
    // If already imported modules have been unpublished, we do not update them
    // again.
    if (!empty($entity->feeds_item) && property_exists($entity, 'nid') && $entity->status == 0 && !empty($wrapper->field_vub_force_publish) && $wrapper->field_vub_force_publish->value() != TRUE) {
      $entity->feeds_item->skip = TRUE;
    }
    else {
      if (!empty($item['field_vub_tags'])) {
        foreach ($item['field_vub_tags'] as $vub_tag) {
          $server_tid = $vub_tag['id'];
          if (!empty($server_tid)) {
            $taxonomy_voc = 'vub_tags';

            $query = new EntityFieldQuery();
            $query->entityCondition('entity_type', 'taxonomy_term')
              ->entityCondition('bundle', $taxonomy_voc)
              ->fieldCondition('field_vub_server_tid', 'value', $server_tid, '=');

            $result = $query->execute();

            if (isset($result['taxonomy_term'])) {
              $tids = array_keys($result['taxonomy_term']);
              $tid = reset($tids);
              // Check if the term already exists.
              $existing_tids = array();
              $existing_terms = $wrapper->field_vub_tags->value();

              foreach ($existing_terms as $existing_term) {
                $existing_tids[] = $existing_term->tid;
              }

              // Only add the value to the field if it already exists.
              if (!in_array($tid, $existing_tids)) {
                $wrapper->field_vub_tags[] = $tid;
              }
            }
          }
        }
      }
      if (!empty($item['field_paddle_featured_image']['sid'])) {
        // Check first if the scald atom has already been imported. Which means
        // we could just link it.
        $server_sid = $item['field_paddle_featured_image']['sid'];
        $style = '';

        if (!empty($item['field_paddle_featured_image']['style'])) {
          $style = $item['field_paddle_featured_image']['style'];
        }

        $query = new EntityFieldQuery();
        $query->entityCondition('entity_type', 'scald_atom')
          ->fieldCondition('field_vub_server_sid', 'value', $server_sid, '=');

        $result = $query->execute();

        if (!empty($result['scald_atom'])) {
          $sids = array_keys($result['scald_atom']);
          $sid = reset($sids);
        }
        else {
          // We need to retrieve the scald atom from the server.
          $sid = paddle_vub_client_scald_import_scald_atom($server_sid, 'image');
        }

        if (!empty($wrapper->field_paddle_featured_image)) {
          $featured_image = array(
            'sid' => $sid,
            'style' => $style,
          );

          $wrapper->field_paddle_featured_image->set($featured_image);
        }
      }
      elseif (!empty($wrapper->field_paddle_featured_image)) {
        $wrapper->field_paddle_featured_image = array();
      }
    }
  }
}

/**
 * Implements hook_feeds_after_save().
 */
function paddle_vub_client_feeds_after_save(FeedsSource $source, $entity, $item, $entity_id) {
  if (property_exists($entity, 'type') && $entity->type == 'news_item') {
    node_object_prepare($entity);
    $node = node_submit($entity);
    // Link the node to a source translation if there is any.
    if (!empty($item['tnid'])) {
      // First check if the tnid is equal to the nid of the current node.
      if ($item['tnid'] == $item['nid']) {
        $node->tnid = $node->nid;
        node_save($node);
      }
      // Otherwise check the other nodes.
      else {
        $query = new EntityFieldQuery();
        $query->entityCondition('entity_type', 'node')
          ->entityCondition('bundle', 'news_item')
          ->fieldCondition('field_vub_server_nid', 'value', $item['tnid'], '=');

        $result = $query->execute();

        if (!empty($result['node'])) {
          $source_node = reset($result['node']);
          $node->tnid = $source_node->nid;
          node_save($node);
        }
      }
    }
    workbench_moderation_moderate($node, 'published');
  }
}

/**
 * Implements hook_feeds_processor_targets_alter().
 */
function paddle_vub_client_feeds_processor_targets_alter(&$targets, $entity_type, $bundle) {
  if ($entity_type == 'node' && $bundle == 'news_item') {
    // We do not want to create duplicates by reimporting a created node.
    $targets['field_vub_server_nid']['optional_unique'] = TRUE;
    $targets['field_vub_server_nid']['unique_callbacks'] = array('paddle_vub_client_unique_server_nid');
  }
}

/**
 * Unique callback for the field_vub_server_nid field.
 *
 * @param FeedsSource $source
 *   The Feed source.
 * @param string $entity_type
 *   Entity type for the entity to be processed.
 * @param string $bundle
 *   Bundle name for the entity to be processed.
 * @param string $target
 *   A string identifying the unique target on the entity.
 * @param array $values
 *   The unique values to be checked.
 *
 * @return int
 *   The existing entity id, or NULL if no existing entity is found.
 */
function paddle_vub_client_unique_server_nid(FeedsSource $source, $entity_type, $bundle, $target, array $values) {
  list($field_name, $column) = explode(':', $target . ':value');
  // Example for if the target is a field.
  $query = new EntityFieldQuery();
  $result = $query
    ->entityCondition('entity_type', $entity_type)
    ->entityCondition('bundle', $bundle)
    ->fieldCondition($field_name, $column, $values)
    ->execute();

  if (!empty($result[$entity_type])) {
    return key($result[$entity_type]);
  }

  return NULL;
}
