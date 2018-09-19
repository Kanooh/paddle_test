<?php

/**
 * @file
 * Contains the PaddleAppSearchApiEntityDataSourceController class.
 */

/**
 * Represents a datasource for paddle_app entities.
 */
class PaddleAppSearchApiEntityDataSourceController extends SearchApiEntityDataSourceController {
  /**
   * {@inheritdoc}
   */
  public function startTracking(array $indexes) {
    if (!$this->table) {
      return;
    }
    // We first clear the tracking table for all indexes, so we can just insert
    // all items again without any key conflicts.
    $this->stopTracking($indexes);

    if (!empty($this->entityInfo['base table']) && $this->idKey) {
      // Use a subselect, which will probably be much faster than entity_load().

      // Assumes that all entities use the "base table" property and the
      // "entity keys[id]" in the same way as the default controller.
      $table = $this->entityInfo['base table'];

      // We could also use a single insert (with a UNION in the nested query),
      // but this method will be mostly called with a single index, anyways.
      foreach ($indexes as $index) {
        // Select all entity ids.
        $query = db_select($table, 't');
        $query->addField('t', $this->idKey, 'item_id');
        $query->addExpression(':index_id', 'index_id', array(':index_id' => $index->id));
        $query->addExpression('1', 'changed');
        if ($bundles = $this->getIndexBundles($index)) {
          $query->condition($this->bundleKey, $bundles);
        }

        // INSERT ... SELECT ...
        db_insert($this->table)
          ->from($query)
          ->execute();
      }
    }
    else {
      // In the absence of a 'base table', use the slow entity_load().
      SearchApiAbstractDataSourceController::startTracking($indexes);
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function getAllItemIds() {
    return array_keys(entity_load($this->entityType));
  }

}
