<?php
/**
 * @file
 * Page callbacks for the Reference tracker module.
 */

/**
 * Page callback rendering the references for the current node.
 *
 * @param object $node
 *   The node for which the references should be displayed.
 *
 * @return array
 *   A render array with the references table.
 */
function reference_tracker_node_inbound_references_page($node) {
  drupal_set_title(t('References to @title', array('@title' => $node->title)));

  $build = array();

  $node_references = reference_tracker_get_inbound_references('node', $node->nid);

  if ($node_references) {
    $header = array(
      array(
        'data' => t('Type'),
      ),
      array(
        'data' => t('Title'),
      ),
    );

    $rows = array();

    foreach ($node_references as $entity_type => $references) {
      $entity_info = entity_get_info($entity_type);
      $entities = entity_load($entity_type, $references);

      foreach ($entities as $entity) {
        $entity_label = entity_label($entity_type, $entity);
        $uri = entity_uri($entity_type, $entity);

        list(, , $bundle) = entity_extract_ids($entity_type, $entity);

        $row = array(
          'data' => array(
            array(
              'data' => !empty($entity_info['bundles'][$bundle]) ? $entity_info['bundles'][$bundle]['label'] : '',
              'class' => 'entity-label',
            ),
            array(
              'data' => l($entity_label, $uri['path']),
              'class' => 'entity-link',
            ),
          ),
        );
        drupal_alter('reference_tracker_entity_reference_table_row', $row, $entity_type, $entity);
        $rows[] = $row;
      }

      $build = array(
        '#theme' => 'table',
        '#header' => $header,
        '#rows' => $rows,
      );
    }
  }
  else {
    $build['message'] = array(
      '#markup' => t('No references available.'),
    );
  }

  return $build;
}
