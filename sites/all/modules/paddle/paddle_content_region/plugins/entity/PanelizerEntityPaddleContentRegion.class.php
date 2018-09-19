<?php
/**
 * @file
 * Class for the Panelizer paddle_content_region entity plugin.
 */

/**
 * Panelizer Entity paddle_content_region plugin class.
 *
 * Handles paddle_content_region specific functionality for Panelizer.
 * Based on PanelizerEntityNode, as hinted by README.txt.
 *
 * @see PanelizerEntityNode.class.php
 */
class PanelizerEntityPaddleContentRegion extends PanelizerEntityDefault {
  public $entity_admin_root = 'admin/structure/types/manage/%panelizer_node_type';
  public $uses_page_manager = TRUE;
  public $entity_admin_bundle = FALSE;

  /**
   * Checks access rights.
   */
  public function entity_access($op, $entity) {
    // This must be implemented by the extending class.
    return entity_access($op, 'paddle_content_region', $entity);
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
    return t('This Paddle content region');
  }

  /**
   * Displays on the panelize configuration overview page.
   */
  public function entity_bundle_label() {
    return t('Paddle content region');
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
    $handler->name = 'paddle_content_region_panel';
    $handler->task = 'page';
    $handler->subtask = '';
    $handler->label = 'Paddle Content Region page';
    $handler->handler = 'page_paddle_content_region_panel_panelizer_node';
    $handler->weight = -100;
    $handler->conf = array(
      'title' => t('Paddle content region panelizer'),
      'context' => 'argument_entity_id:node_1',
      'access' => array(),
    );
    $handlers['paddle_content_region_page'] = $handler;

    return $handlers;
  }
}
