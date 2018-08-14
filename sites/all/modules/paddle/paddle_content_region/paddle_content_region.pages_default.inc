<?php
/**
 * @file
 * paddle_content_region.pages_default.inc
 */

/**
 * Implements hook_default_page_manager_pages().
 */
function paddle_content_region_default_page_manager_pages() {
  $page = new stdClass();
  $page->disabled = FALSE; /* Edit this to true to make a default page disabled initially */
  $page->api_version = 1;
  $page->name = 'paddle_content_region_panel';
  $page->task = 'page';
  $page->admin_title = 'Paddle Content Region panel';
  $page->admin_description = '';
  $page->path = 'admin/content_manager/entity/%paddle_content_region/panels_content';
  $page->access = array(
    'plugins' => array(
      0 => array(
        'name' => 'perm',
        'settings' => array(
          'perm' => 'administer panelizer paddle_content_region paddle_content_region content',
        ),
        'context' => 'logged-in-user',
        'not' => FALSE,
      ),
    ),
    'logic' => 'and',
  );
  $page->menu = array();
  $page->arguments = array(
    'paddle_content_region' => array(
      'id' => 1,
      'identifier' => 'Paddle Content Region: ID',
      'name' => 'entity_id:paddle_content_region',
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
  $handler->name = 'page_paddle_content_region_panel_panelizer_node';
  $handler->task = 'page';
  $handler->subtask = 'paddle_content_region_panel';
  $handler->handler = 'panelizer_node';
  $handler->weight = 0;
  $handler->conf = array(
    'title' => 'Panelizer',
    'contexts' => array(),
    'relationships' => array(),
    'context' => 'argument_entity_id:paddle_content_region_1',
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
  $pages['paddle_content_region_panel'] = $page;

  return $pages;

}
