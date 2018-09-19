<?php
/**
 * @file
 * paddle_landing_page.pages_default.inc
 */

/**
 * Implements hook_default_page_manager_handlers().
 */
function paddle_landing_page_default_page_manager_handlers() {
  $export = array();

  $handler = new stdClass();
  $handler->disabled = FALSE; /* Edit this to true to make a default handler disabled initially */
  $handler->api_version = 1;
  $handler->name = 'page_paddle_content_manager_node_view_panel_context_landing_page';
  $handler->task = 'page';
  $handler->subtask = 'paddle_content_manager_node_view';
  $handler->handler = 'panelizer_node';
  $handler->weight = -1;
  $handler->conf = array(
    'title' => 'Panelizer',
    'contexts' => array(),
    'relationships' => array(
      0 => array(
        'identifier' => 'Node current revision',
        'keyword' => 'current_revision',
        'name' => 'current_revision_from_node',
        'context' => 'argument_entity_id:node_1',
        'id' => 1,
      ),
    ),
    'context' => 'relationship_current_revision_from_node_1',
  );
  $export['page_paddle_content_manager_node_view_panel_context_landing_page'] = $handler;

  return $export;
}

/**
 * Implements hook_default_page_manager_pages().
 */
function paddle_landing_page_default_page_manager_pages() {
  $page = new stdClass();
  $page->disabled = FALSE; /* Edit this to true to make a default page disabled initially */
  $page->api_version = 1;
  $page->name = 'paddle_landing_page_admin_panels_content';
  $page->task = 'page';
  $page->admin_title = 'Landing page panels content';
  $page->admin_description = '';
  $page->path = 'admin/content_manager/node/%paddle_landing_page/panels_content';
  $page->access = array(
    'plugins' => array(
      0 => array(
        'name' => 'node_access',
        'settings' => array(
          'type' => 'update',
        ),
        'context' => array(
          0 => 'logged-in-user',
          1 => 'argument_landing_page_current_revision_1',
        ),
        'not' => FALSE,
      ),
    ),
    'logic' => 'and',
    'type' => 'none',
    'settings' => NULL,
  );
  $page->menu = array();
  $page->arguments = array(
    'paddle_landing_page' => array(
      'id' => 1,
      'identifier' => 'Node (current revision): Paddle landing page node ID',
      'name' => 'landing_page_current_revision',
      'settings' => array(),
    ),
  );
  $page->conf = array(
    'admin_paths' => FALSE,
  );
  $page->default_handlers = array();
  $handler = new stdClass();
  $handler->disabled = FALSE; /* Edit this to true to make a default handler disabled initially */
  $handler->api_version = 1;
  $handler->name = 'page_paddle_landing_page_admin_panels_content_http_response';
  $handler->task = 'page';
  $handler->subtask = 'paddle_landing_page_admin_panels_content';
  $handler->handler = 'http_response';
  $handler->weight = -30;
  $handler->conf = array(
    'title' => 'Locked node',
    'contexts' => array(),
    'relationships' => array(),
    'code' => '301',
    'destination' => 'admin/content_manager/node/%paddle_landing_page:nid/view',
    'access' => array(
      'plugins' => array(
        0 => array(
          'name' => 'node_lock',
          'settings' => NULL,
          'context' => 'argument_landing_page_current_revision_1',
          'not' => FALSE,
        ),
      ),
      'logic' => 'and',
    ),
  );
  $page->default_handlers[$handler->name] = $handler;
  $handler = new stdClass();
  $handler->disabled = FALSE; /* Edit this to true to make a default handler disabled initially */
  $handler->api_version = 1;
  $handler->name = 'page_paddle_landing_page_admin_panels_content_panelizer_node';
  $handler->task = 'page';
  $handler->subtask = 'paddle_landing_page_admin_panels_content';
  $handler->handler = 'panelizer_node';
  $handler->weight = -29;
  $handler->conf = array(
    'title' => 'Panelizer',
    'contexts' => array(),
    'relationships' => array(),
    'context' => 'argument_landing_page_current_revision_1',
  );
  $page->default_handlers[$handler->name] = $handler;
  $pages['paddle_landing_page_admin_panels_content'] = $page;

  return $pages;

}
