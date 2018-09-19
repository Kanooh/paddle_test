<?php
/**
 * @file
 * paddle_pane_collection.pages_default.inc
 */

/**
 * Implements hook_default_page_manager_pages().
 */
function paddle_pane_collection_default_page_manager_pages() {
  $page = new stdClass();
  $page->disabled = FALSE; /* Edit this to true to make a default page disabled initially */
  $page->api_version = 1;
  $page->name = 'paddle_pane_collection_panel';
  $page->task = 'page';
  $page->admin_title = 'Paddle Pane Collection panel';
  $page->admin_description = '';
  $page->path = 'admin/paddle-pane-collection/edit/%paddle_pane_collection';
  $page->access = array(
    'plugins' => array(),
    'logic' => 'and',
    'type' => 'none',
    'settings' => NULL,
  );
  $page->menu = array();
  $page->arguments = array(
    'paddle_pane_collection' => array(
      'id' => 1,
      'identifier' => 'Paddle Pane Collection: ID',
      'name' => 'entity_id:paddle_pane_collection',
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
  $handler->name = 'panelizer_node';
  $handler->task = 'page';
  $handler->subtask = 'paddle_pane_collection_panel';
  $handler->handler = 'panelizer_node';
  $handler->weight = 0;
  $handler->conf = array(
    'title' => 'Panelizer',
    'contexts' => array(),
    'relationships' => array(),
    'context' => 'argument_entity_id:paddle_pane_collection_1',
    'access' => array(
      'plugins' => array(
        0 => array(
          'name' => 'perm',
          'settings' => array(
            'perm' => 'use panels in place editing',
          ),
          'context' => 'logged-in-user',
        ),
      ),
      'logic' => 'and',
    ),
  );
  $page->default_handlers[$handler->name] = $handler;
  $pages['paddle_pane_collection_panel'] = $page;

  return $pages;

}
