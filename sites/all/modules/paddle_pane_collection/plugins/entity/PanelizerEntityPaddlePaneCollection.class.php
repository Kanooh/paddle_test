<?php
/**
 * @file
 * Class for the Panelizer paddle_pane_collection entity plugin.
 */

/**
 * Panelizer Entity paddle_pane_collection plugin class.
 *
 * Handles paddle_pane_collection specific functionality for Panelizer.
 * Based on PanelizerEntityNode, as hinted by README.txt.
 *
 * @see PanelizerEntityNode.class.php
 */
class PanelizerEntityPaddlePaneCollection extends PanelizerEntityDefault {
  public $entity_admin_root = 'admin/structure/types/manage/%panelizer_node_type';
  public $uses_page_manager = TRUE;
  public $entity_admin_bundle = FALSE;

  /**
   * Checks access rights.
   */
  public function entity_access($op, $entity) {
    // This must be implemented by the extending class.
    return entity_access($op, 'paddle_pane_collection', $entity);
  }

  /**
   * Implements the save function for the entity.
   */
  public function entity_save($entity) {
    $entity->save();
  }

  /**
   * Displays on the context configuration page.
   */
  public function entity_identifier($entity) {
    return t('This Paddle pane collection');
  }

  /**
   * Displays on the panelize configuration overview page.
   */
  public function entity_bundle_label() {
    return t('Paddle pane collection');
  }

  /**
   * Implements a delegated hook_page_manager_handlers().
   *
   * This makes sure that all panelized entities have the proper entry
   * in page manager for rendering.
   * It gets called by the Submit button on admin/config/content/panelizer.
   */
  public function hook_default_page_manager_handlers(&$handlers) {
    $handler = new stdClass();
    $handler->disabled = FALSE; /* Edit this to true to make a default handler disabled initially */
    $handler->api_version = 1;
    $handler->name = 'paddle_pane_collection_panel';
    $handler->task = 'page';
    $handler->subtask = '';
    $handler->label = 'Paddle Pane Collection page';
    $handler->handler = 'panelizer_node';
    $handler->weight = -100;
    $handler->conf = array(
      'title' => t('Paddle pane collection panelizer'),
      'context' => 'argument_entity_id:node_1',
      'access' => array(),
    );
    $handlers['paddle_pane_collection_page'] = $handler;

    return $handlers;
  }

  /**
   * Implements a delegated hook_permission().
   *
   * This makes sure that the needed permissions exist.
   */
  public function hook_permission(&$items) {
    $items['administer panelizer paddle_pane_collection paddle_pane_collection content'] = array(
      'title' => t('Paddle Pane Collection Paddle Pane Collection: Administer Panelizer content'),
    );
  }
}
