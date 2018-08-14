<?php
/**
 * @file
 * paddle_dashboard.pages_default.inc
 */

/**
 * Implements hook_default_page_manager_handlers().
 */
function paddle_dashboard_default_page_manager_handlers() {
  $export = array();

  $handler = new stdClass();
  $handler->disabled = FALSE; /* Edit this to true to make a default handler disabled initially */
  $handler->api_version = 1;
  $handler->name = 'pm_existing_pages_admin_panel_context';
  $handler->task = 'pm_existing_pages';
  $handler->subtask = 'admin';
  $handler->handler = 'panel_context';
  $handler->weight = -29;
  $handler->conf = array(
    'title' => 'Editors',
    'no_blocks' => 0,
    'pipeline' => 'standard',
    'body_classes_to_remove' => '',
    'body_classes_to_add' => '',
    'css_id' => '',
    'css' => '',
    'contexts' => array(),
    'relationships' => array(),
    'access' => array(
      'logic' => 'and',
      'plugins' => array(
        0 => array(
          'name' => 'perm',
          'settings' => array(
            'perm' => 'access paddle dashboard',
          ),
          'context' => 'logged-in-user',
          'not' => FALSE,
        ),
      ),
    ),
  );
  $display = new panels_display();
  $display->layout = 'paddle_dashboard_layout';
  $display->layout_settings = array();
  $display->panel_settings = array(
    'style_settings' => array(
      'default' => NULL,
      'left' => NULL,
      'middle' => NULL,
      'right' => NULL,
    ),
  );
  $display->cache = array();
  $display->title = '';
  $display->uuid = '52df2556-1e18-4add-a598-686011bf18f9';
  $display->content = array();
  $display->panels = array();
    $pane = new stdClass();
    $pane->pid = 'new-161fdc0f-06fd-4a76-ba4c-b8f45acfa943';
    $pane->panel = 'left';
    $pane->type = 'views_panes';
    $pane->subtype = 'paddle_dashboard_status-panel_pane_my_concepts';
    $pane->shown = TRUE;
    $pane->access = array();
    $pane->configuration = array();
    $pane->cache = array();
    $pane->style = array(
      'settings' => NULL,
    );
    $pane->css = array();
    $pane->extras = array();
    $pane->position = 0;
    $pane->locks = array();
    $pane->uuid = '161fdc0f-06fd-4a76-ba4c-b8f45acfa943';
    $display->content['new-161fdc0f-06fd-4a76-ba4c-b8f45acfa943'] = $pane;
    $display->panels['left'][0] = 'new-161fdc0f-06fd-4a76-ba4c-b8f45acfa943';
    $pane = new stdClass();
    $pane->pid = 'new-cedec19c-d967-4eb5-a7aa-a00bf446a563';
    $pane->panel = 'middle';
    $pane->type = 'views_panes';
    $pane->subtype = 'paddle_dashboard_status-panel_pane_mine_to_check';
    $pane->shown = TRUE;
    $pane->access = array();
    $pane->configuration = array();
    $pane->cache = array();
    $pane->style = array(
      'settings' => NULL,
    );
    $pane->css = array();
    $pane->extras = array();
    $pane->position = 0;
    $pane->locks = array();
    $pane->uuid = 'cedec19c-d967-4eb5-a7aa-a00bf446a563';
    $display->content['new-cedec19c-d967-4eb5-a7aa-a00bf446a563'] = $pane;
    $display->panels['middle'][0] = 'new-cedec19c-d967-4eb5-a7aa-a00bf446a563';
    $pane = new stdClass();
    $pane->pid = 'new-7e2e6bd5-4b6f-4f3b-a0d2-3deed9a4966f';
    $pane->panel = 'right';
    $pane->type = 'views_panes';
    $pane->subtype = 'paddle_dashboard_status-panel_pane_all_to_check';
    $pane->shown = TRUE;
    $pane->access = array();
    $pane->configuration = array();
    $pane->cache = array();
    $pane->style = array(
      'settings' => NULL,
    );
    $pane->css = array();
    $pane->extras = array();
    $pane->position = 0;
    $pane->locks = array();
    $pane->uuid = '7e2e6bd5-4b6f-4f3b-a0d2-3deed9a4966f';
    $display->content['new-7e2e6bd5-4b6f-4f3b-a0d2-3deed9a4966f'] = $pane;
    $display->panels['right'][0] = 'new-7e2e6bd5-4b6f-4f3b-a0d2-3deed9a4966f';
    $pane = new stdClass();
    $pane->pid = 'new-9ea07284-6b1d-4def-af7e-8da48f6258d3';
    $pane->panel = 'right';
    $pane->type = 'views_panes';
    $pane->subtype = 'paddle_dashboard_status-panel_pane_all_published';
    $pane->shown = TRUE;
    $pane->access = array();
    $pane->configuration = array();
    $pane->cache = array();
    $pane->style = array(
      'settings' => NULL,
    );
    $pane->css = array();
    $pane->extras = array();
    $pane->position = 1;
    $pane->locks = array();
    $pane->uuid = '9ea07284-6b1d-4def-af7e-8da48f6258d3';
    $display->content['new-9ea07284-6b1d-4def-af7e-8da48f6258d3'] = $pane;
    $display->panels['right'][1] = 'new-9ea07284-6b1d-4def-af7e-8da48f6258d3';
    $pane = new stdClass();
    $pane->pid = 'new-a39f2e94-6b22-4161-bb84-ca232c301867';
    $pane->panel = 'right';
    $pane->type = 'views_panes';
    $pane->subtype = 'paddle_dashboard_status-panel_pane_planned_publications';
    $pane->shown = TRUE;
    $pane->access = array();
    $pane->configuration = array();
    $pane->cache = array();
    $pane->style = array(
      'settings' => NULL,
    );
    $pane->css = array();
    $pane->extras = array();
    $pane->position = 2;
    $pane->locks = array();
    $pane->uuid = 'a39f2e94-6b22-4161-bb84-ca232c301867';
    $display->content['new-a39f2e94-6b22-4161-bb84-ca232c301867'] = $pane;
    $display->panels['right'][2] = 'new-a39f2e94-6b22-4161-bb84-ca232c301867';
  $display->hide_title = PANELS_TITLE_NONE;
  $display->title_pane = '0';
  $handler->conf['display'] = $display;
  $export['pm_existing_pages_admin_panel_context'] = $handler;

  $handler = new stdClass();
  $handler->disabled = FALSE; /* Edit this to true to make a default handler disabled initially */
  $handler->api_version = 1;
  $handler->name = 'pm_existing_pages_admin_panel_context_2';
  $handler->task = 'pm_existing_pages';
  $handler->subtask = 'admin';
  $handler->handler = 'panel_context';
  $handler->weight = -30;
  $handler->conf = array(
    'title' => 'Chief Editors',
    'no_blocks' => 0,
    'pipeline' => 'standard',
    'body_classes_to_remove' => '',
    'body_classes_to_add' => '',
    'css_id' => '',
    'css' => '',
    'contexts' => array(),
    'relationships' => array(),
    'access' => array(
      'logic' => 'and',
      'plugins' => array(
        0 => array(
          'name' => 'perm',
          'settings' => array(
            'perm' => 'access paddle in review dashboard',
          ),
          'context' => 'logged-in-user',
          'not' => FALSE,
        ),
      ),
    ),
  );
  $display = new panels_display();
  $display->layout = 'paddle_dashboard_layout';
  $display->layout_settings = array();
  $display->panel_settings = array(
    'style_settings' => array(
      'default' => NULL,
      'left' => NULL,
      'middle' => NULL,
      'right' => NULL,
    ),
  );
  $display->cache = array();
  $display->title = '';
  $display->uuid = '3e282fe3-de06-454b-81aa-01fbf2ec0b64';
  $display->content = array();
  $display->panels = array();
    $pane = new stdClass();
    $pane->pid = 'new-86bbbb6a-78e9-4b38-999e-22e74aeb86f7';
    $pane->panel = 'left';
    $pane->type = 'views_panes';
    $pane->subtype = 'paddle_dashboard_status-panel_pane_my_concepts';
    $pane->shown = TRUE;
    $pane->access = array();
    $pane->configuration = array();
    $pane->cache = array();
    $pane->style = array(
      'settings' => NULL,
    );
    $pane->css = array();
    $pane->extras = array();
    $pane->position = 0;
    $pane->locks = array();
    $pane->uuid = '86bbbb6a-78e9-4b38-999e-22e74aeb86f7';
    $display->content['new-86bbbb6a-78e9-4b38-999e-22e74aeb86f7'] = $pane;
    $display->panels['left'][0] = 'new-86bbbb6a-78e9-4b38-999e-22e74aeb86f7';
    $pane = new stdClass();
    $pane->pid = 'new-4a77067b-89f1-401a-9f7b-61e96c6d1124';
    $pane->panel = 'left';
    $pane->type = 'views_panes';
    $pane->subtype = 'paddle_dashboard_status-panel_pane_all_to_check';
    $pane->shown = TRUE;
    $pane->access = array();
    $pane->configuration = array();
    $pane->cache = array();
    $pane->style = array(
      'settings' => NULL,
    );
    $pane->css = array();
    $pane->extras = array();
    $pane->position = 1;
    $pane->locks = array();
    $pane->uuid = '4a77067b-89f1-401a-9f7b-61e96c6d1124';
    $display->content['new-4a77067b-89f1-401a-9f7b-61e96c6d1124'] = $pane;
    $display->panels['left'][1] = 'new-4a77067b-89f1-401a-9f7b-61e96c6d1124';
    $pane = new stdClass();
    $pane->pid = 'new-ca22fbf9-cdf1-4eab-ab33-740d1bd2c9fc';
    $pane->panel = 'middle';
    $pane->type = 'views_panes';
    $pane->subtype = 'paddle_dashboard_status-panel_pane_mine_in_review';
    $pane->shown = TRUE;
    $pane->access = array();
    $pane->configuration = array();
    $pane->cache = array();
    $pane->style = array(
      'settings' => NULL,
    );
    $pane->css = array();
    $pane->extras = array();
    $pane->position = 0;
    $pane->locks = array();
    $pane->uuid = 'ca22fbf9-cdf1-4eab-ab33-740d1bd2c9fc';
    $display->content['new-ca22fbf9-cdf1-4eab-ab33-740d1bd2c9fc'] = $pane;
    $display->panels['middle'][0] = 'new-ca22fbf9-cdf1-4eab-ab33-740d1bd2c9fc';
    $pane = new stdClass();
    $pane->pid = 'new-687a3735-81bd-4787-b44e-6c6a352db8c9';
    $pane->panel = 'right';
    $pane->type = 'views_panes';
    $pane->subtype = 'paddle_dashboard_status-panel_pane_all_in_review';
    $pane->shown = TRUE;
    $pane->access = array();
    $pane->configuration = array();
    $pane->cache = array();
    $pane->style = array(
      'settings' => NULL,
    );
    $pane->css = array();
    $pane->extras = array();
    $pane->position = 0;
    $pane->locks = array();
    $pane->uuid = '687a3735-81bd-4787-b44e-6c6a352db8c9';
    $display->content['new-687a3735-81bd-4787-b44e-6c6a352db8c9'] = $pane;
    $display->panels['right'][0] = 'new-687a3735-81bd-4787-b44e-6c6a352db8c9';
    $pane = new stdClass();
    $pane->pid = 'new-ec37737a-d385-4543-a420-ab2e60992aee';
    $pane->panel = 'right';
    $pane->type = 'views_panes';
    $pane->subtype = 'paddle_dashboard_status-panel_pane_all_published';
    $pane->shown = TRUE;
    $pane->access = array();
    $pane->configuration = array();
    $pane->cache = array();
    $pane->style = array(
      'settings' => NULL,
    );
    $pane->css = array();
    $pane->extras = array();
    $pane->position = 1;
    $pane->locks = array();
    $pane->uuid = 'ec37737a-d385-4543-a420-ab2e60992aee';
    $display->content['new-ec37737a-d385-4543-a420-ab2e60992aee'] = $pane;
    $display->panels['right'][1] = 'new-ec37737a-d385-4543-a420-ab2e60992aee';
    $pane = new stdClass();
    $pane->pid = 'new-f124a4bf-310a-46e9-a35c-dc9b0fa076ab';
    $pane->panel = 'right';
    $pane->type = 'views_panes';
    $pane->subtype = 'paddle_dashboard_status-panel_pane_planned_publications';
    $pane->shown = TRUE;
    $pane->access = array();
    $pane->configuration = array();
    $pane->cache = array();
    $pane->style = array(
      'settings' => NULL,
    );
    $pane->css = array();
    $pane->extras = array();
    $pane->position = 2;
    $pane->locks = array();
    $pane->uuid = 'f124a4bf-310a-46e9-a35c-dc9b0fa076ab';
    $display->content['new-f124a4bf-310a-46e9-a35c-dc9b0fa076ab'] = $pane;
    $display->panels['right'][2] = 'new-f124a4bf-310a-46e9-a35c-dc9b0fa076ab';
    $pane = new stdClass();
    $pane->pid = 'new-d453f7eb-1207-45fd-9073-554549cdbff4';
    $pane->panel = 'right';
    $pane->type = 'views_panes';
    $pane->subtype = 'paddle_dashboard_status-panel_pane_planned_unpublications';
    $pane->shown = TRUE;
    $pane->access = array();
    $pane->configuration = array();
    $pane->cache = array();
    $pane->style = array(
      'settings' => NULL,
    );
    $pane->css = array();
    $pane->extras = array();
    $pane->position = 3;
    $pane->locks = array();
    $pane->uuid = 'd453f7eb-1207-45fd-9073-554549cdbff4';
    $display->content['new-d453f7eb-1207-45fd-9073-554549cdbff4'] = $pane;
    $display->panels['right'][3] = 'new-d453f7eb-1207-45fd-9073-554549cdbff4';
    $pane = new stdClass();
    $pane->pid = 'new-544d3fa0-e990-4790-add8-0c15765168f2';
    $pane->panel = 'right';
    $pane->type = 'views_panes';
    $pane->subtype = 'paddle_dashboard_status-panel_pane_all_unpublished';
    $pane->shown = TRUE;
    $pane->access = array();
    $pane->configuration = array();
    $pane->cache = array();
    $pane->style = array(
      'settings' => NULL,
    );
    $pane->css = array();
    $pane->extras = array();
    $pane->position = 4;
    $pane->locks = array();
    $pane->uuid = '544d3fa0-e990-4790-add8-0c15765168f2';
    $display->content['new-544d3fa0-e990-4790-add8-0c15765168f2'] = $pane;
    $display->panels['right'][4] = 'new-544d3fa0-e990-4790-add8-0c15765168f2';
  $display->hide_title = PANELS_TITLE_NONE;
  $display->title_pane = '0';
  $handler->conf['display'] = $display;
  $export['pm_existing_pages_admin_panel_context_2'] = $handler;

  return $export;
}
