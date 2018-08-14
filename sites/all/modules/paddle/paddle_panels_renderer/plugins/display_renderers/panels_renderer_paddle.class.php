<?php

/**
 * @file
 * Renderer class for our overridden In-Place Editor (IPE) behavior.
 */

class panels_renderer_paddle extends panels_renderer_ipe {

  /**
   * {@inheritdoc}
   */
  public function init($plugin, &$display) {
    // Replace 'panelizer' with 'paddle_panels_renderer' so
    // paddle_panels_renderer.module can take over cache handling.
    $display->cache_key = str_replace('panelizer', 'paddle_panels_renderer', $display->cache_key);

    // Force browser to reload the page if Back is hit. Adds 'no-store' to the
    // Drupal defaults to ensure no caching happens in the browser.
    drupal_add_http_header('Cache-Control', 'no-store, no-cache, must-revalidate, pre-check=0, post-check=0');

    parent::init($plugin, $display);
  }

  /**
   * {@inheritdoc}
   */
  public function ajax_select_content($region = NULL, $category = NULL) {
    if (!array_key_exists($region, $this->plugins['layout']['regions'])) {
      ctools_modal_render(t('Error'), t('Invalid input'));
    }

    // Display the content types as an unordered list.
    $output = array();
    $output['content_types'] = array(
      '#theme' => 'item_list',
      '#type' => 'ul',
      '#attributes' => array(
        'class' => array('content-types'),
        'id' => 'content-type-list',
      ),
    );

    // Determine the content type for which the user has permissions.
    $content_types = array();
    foreach ($this->cache->content_types as $type_name => $subtypes) {
      foreach ($subtypes as $subtype_name => $content_type) {
        if (user_access("edit $subtype_name content in landing pages")) {
          $content_types[$subtype_name] = $content_type;
          $content_types[$subtype_name]['url'] = $this->get_url('add-pane', $region, $type_name, $type_name);
        }
      }
    }

    // Allow modules to remove specific content types.
    drupal_alter('panels_renderer_paddle_allowed_content_types', $content_types, $this->display->context);

    if (!count($content_types)) {
      $output = t('There are no content types you may add to this display.');
    }
    else {
      $output = theme('paddle_panels_renderer_content_types_list', array('content_types' => $content_types));
    }
    $this->commands[] = ctools_modal_command_display(t('Add new pane'), $output);
  }

  /**
   * {@inheritdoc}
   */
  public function ajax_save_form($break = NULL) {
    // Cancel if the panel is locked and the user can't or won't break it.
    if ($this->ipe_test_lock('save-form', $break)) {
      return;
    }

    $form_state = $this->prepareFormState();

    if ($this->handleUnsubmittedForm($form_state)) {
      return;
    }

    if ($this->handleBrokenLock()) {
      return;
    }

    $this->handleContentLock($form_state);

    $this->processFormSubmission($form_state);
  }

  /**
   * AJAX entry point to configure Paddle Style plugins for a pane.
   *
   * @param string $type
   *   Either display, region or pane. Only panes are supported at this time.
   * @param int $pid
   *   The pane id, if a pane. The region id, if a region.
   */
  public function ajax_paddle_style($type, $pid = NULL) {
    if ($type != 'pane') {
      ctools_modal_render(t('Error'), t('Styles can currently only be configured for panes.'));
      return;
    }

    ctools_include('content');

    $pane = &$this->display->content[$pid];

    $color_palette = 0;
    if (!empty($pane->extras['color_subpalettes'])) {
      $color_palette = $pane->extras['color_subpalettes']['paddle_color_subpalette'] < paddle_color_palettes_get_subpalettes_count() ? $pane->extras['color_subpalettes']['paddle_color_subpalette'] : 0;
      $pane->extras['color_subpalettes']['paddle_color_subpalette'] = $color_palette;
    }

    $subtype = ctools_content_get_subtype($pane->type, $pane->subtype);
    $title = t('Styles for "!pane"', array('!pane' => $subtype['title']));

    $form_state = array(
      'display' => &$this->display,
      'color_palette' => $color_palette,
      'type' => $type,
      'pid' => $pid,
      'title' => $title,
      'ajax' => TRUE,
      'renderer' => &$this,
      'url' => url($this->get_url('color-palette', $type, $pid), array('absolute' => TRUE)),
    );

    $output = ctools_modal_form_wrapper('paddle_panels_renderer_paddle_style_plugins_form', $form_state);

    // Return the form if it has not yet been submitted.
    if (empty($form_state['executed'])) {
      $this->commands = $output;
      return;
    }

    // If the form has been submitted, store the values in the cache and in the
    // 'extras' property so they can be saved in the database.
    if (!empty($form_state['values'])) {
      $plugin_instances = module_invoke_all('paddle_panels_renderer_pane_styles', $pane);
      foreach ($plugin_instances as $machine_name => $plugin_instance) {
        $this->cache->display->content[$pid]->extras[$machine_name] = $form_state['values'][$machine_name];
        $this->display->content[$pid]->extras[$machine_name] = $form_state['values'][$machine_name];
      }
    }

    panels_edit_cache_set($this->cache);

    $this->commands[] = ctools_modal_command_dismiss();
    $this->command_update_pane($pane);
  }

  /**
   * AJAX entry point to create the controller form for an IPE.
   *
   * We override this to adjust the html markup of the body.
   */
  public function ajax_change_layout($break = NULL) {
    if ($this->ipe_test_lock('change_layout', $break)) {
      return;
    }

    // At this point, we want to save the cache to ensure that we have a lock.
    $this->cache->ipe_locked = TRUE;
    panels_edit_cache_set($this->cache);

    ctools_include('plugins', 'panels');
    ctools_include('common', 'panels');

    $layout_option = 'panels_page';
    if (function_exists('panelizer_get_allowed_layouts_option')) {
      $panelizer_entity_type = '';
      if (!empty($this->display->context['panelizer']->type)) {
        $panelizer_entity_type = $this->display->context['panelizer']->type[2];
      }

      $bundle = '';
      if (!empty($this->display->context['panelizer']->data)) {
        $data = $this->display->context['panelizer']->data;

        // The data of nodes is saved differently as other entities.
        // We need to retrieve the bundle of the entity here.
        if (is_a($data, 'Entity')) {
          /** @var \Entity $data */
          $bundle = $data->bundle();
        }
        else {
          $bundle = $data->type;
        }
      }

      $layout_option = panelizer_get_allowed_layouts_option($panelizer_entity_type, $bundle);
    }
    $layouts = panels_common_get_allowed_layouts($layout_option);

    // Filter out builders.
    $layouts = array_filter($layouts, '_panels_builder_filter');

    // Define the current layout.
    $current_layout = $this->plugins['layout']['name'];

    $output = paddle_panels_renderer_print_layout_links($layouts, $this->get_url('set_layout'), array('attributes' => array('class' => array('use-ajax'))), $current_layout);

    $this->commands[] = ctools_modal_command_display(t('Change layout'), $output);
    $this->commands[] = array(
      'command' => 'IPEsetLockState',
      'key' => $this->clean_key,
      'lockPath' => url($this->get_url('unlock_ipe')),
    );
  }

  /**
   * Set the new layout and reload the page to show the new layout instantly.
   *
   * By calling ctools_ajax_command_reload() before returning.
   * That's the only change compared to the function we're overriding.
   *
   * @see parent::ajax_set_layout()
   */
  public function ajax_set_layout($layout) {
    ctools_include('context');
    ctools_include('display-layout', 'panels');
    $form_state = array(
      'layout' => $layout,
      'display' => $this->display,
      'finish' => t('Save'),
      'no_redirect' => TRUE,
    );

    // Reset the $_POST['ajax_html_ids'] values to preserve
    // proper IDs on form elements when they are rebuilt
    // by the Panels IPE without refreshing the page.
    $_POST['ajax_html_ids'] = array();

    $output = drupal_build_form('panels_change_layout', $form_state);

    // Place the form body in a seperate container.
    $output['form_body'] = array(
      '#type' => 'container',
      '#attributes' => array('class' => array('form-body')),
    );
    foreach ($output as $key => $value) {
      if (in_array($key, array(
        'container',
        '#display',
        'hide',
        'layout_settings',
        'panel',
        'display',
      ))) {
        $output['form_body'][$key] = $output[$key];
        unset($output[$key]);
      }
    }

    // Place the buttons in a seperate container.
    $output['buttons'] = array(
      '#type' => 'container',
      '#attributes' => array('class' => array('form-buttons', 'form-wrapper')),
    );
    $output['buttons']['back'] = $output['back'];
    $output['buttons']['submit'] = $output['submit'];
    unset($output['back']);
    unset($output['submit']);

    $output = drupal_render($output);

    if (!empty($form_state['executed'])) {
      if (isset($form_state['back'])) {
        return $this->ajax_change_layout();
      }

      if (!empty($form_state['clicked_button']['#save-display'])) {
        // Saved. Save the cache.
        panels_edit_cache_save($this->cache);
        $this->display->skip_cache;

        // Since the layout changed, we have to update these things in the
        // renderer in order to get the right settings.
        $layout = panels_get_layout($this->display->layout);
        $this->plugins['layout'] = $layout;
        if (!isset($layout['regions'])) {
          $this->plugins['layout']['regions'] = panels_get_regions($layout, $this->display);
        }

        $this->meta_location = 'inline';

        $this->commands[] = ajax_command_replace("#panels-ipe-display-{$this->clean_key}", panels_render_display($this->display, $this));
        $this->commands[] = ctools_modal_command_dismiss();
        // Force a reload of the current page.
        $this->commands[] = ctools_ajax_command_reload();

        // Display a message.
        drupal_set_message(t('The layout has been changed.'));
        return;
      }
    }

    $this->commands[] = ctools_modal_command_display(t('Change layout'), $output);
  }

  /**
   * Check to see if we have a lock that was broken.
   *
   * @return bool
   *   TRUE if a lock was broken, FALSE otherwise.
   */
  protected function handleBrokenLock() {
    if (empty($this->cache->ipe_locked)) {
      $alert = t('A lock you had has been externally broken, and all your changes have been reverted.');
      $this->commands[] = ajax_command_alert($alert);
      $this->commands[] = array(
        'command' => 'cancelIPE',
        'key' => $this->clean_key,
      );

      return TRUE;
    }

    return FALSE;
  }

  /**
   * Process the actual form submission, depending on which button was clicked.
   *
   * @param array $form_state
   *   The current form state.
   */
  protected function processFormSubmission($form_state) {
    $redirect_path = NULL;

    // Save the display if the Save or a moderation button was clicked.
    if (!empty($form_state['clicked_button']['#save-display'])) {
      // Saved. Save the cache.
      panels_edit_cache_save($this->cache);

      $node = $this->cache->display->context['panelizer']->data;

      // Redirect to the administrative node view.
      $redirect_path = "admin/content_manager/node/{$node->nid}/view";

      // Set the correct moderation state if a moderation change was requested.
      if (!empty($form_state['clicked_button']['#workbench_moderation_state_new'])) {

        // Set the assignee uid, this will be handled by
        // paddle_content_manager_workbench_moderation_transition().
        // That method also takes care of validation, so don't bother to handle
        // this here.
        $node->assignee_uid = $form_state['values']['assignee_uid'];

        workbench_moderation_moderate(
          $node,
          $form_state['clicked_button']['#workbench_moderation_state_new']
        );
      }
      else {
        // Set update message.
        drupal_set_message(t('The page has been updated.'), 'status');

        // Temporarily removed this because it causes a JavaScript confirm
        // message when saving a modified display.
        // @todo Move this to Content Region implementation of Paddle IPE once
        // KANWEBS-1173 is done.
        if (FALSE) {
          // A rerender should fix IDs on added panes as well as ensure style
          // changes are rendered.
          $this->meta_location = 'inline';

          $messages = theme('status_messages');

          $this->commands[] = ajax_command_prepend('#content', $messages);
          // Replace existing messages.
          $this->commands[] = ajax_command_replace('.messages', $messages);
        }
      }
    }
    else {
      // Editing the display was cancelled. Clear the cache.
      panels_edit_cache_clear($this->cache);
    }

    $this->commands[] = array(
      'command' => 'endIPE',
      'key' => $this->clean_key,
    );

    if ($redirect_path) {
      $this->commands[] = ctools_ajax_command_redirect($redirect_path);
    }
  }

  /**
   * Build a basic form state for the ajax save form.
   *
   * @return array
   *   The initial form state for the ajax save form.
   */
  protected function prepareFormState() {
    // Reset the $_POST['ajax_html_ids'] values to preserve proper IDs on form
    // elements when they are rebuilt by the Panels IPE without refreshing the
    // page.
    $_POST['ajax_html_ids'] = array();

    // Build the base of the form state. It will be completed by
    // drupal_build_form().
    $form_state = array(
      'renderer' => $this,
      'display' => &$this->display,
      'content_types' => $this->cache->content_types,
      'rerender' => FALSE,
      'no_redirect' => TRUE,
      // Panels needs this to make sure that the layout gets callbacks.
      'layout' => $this->plugins['layout'],
    );
    return $form_state;
  }

  /**
   * Displays the form when it wasn't submitted yet.
   *
   * @param array $form_state
   *   The current form state.
   *
   * @return bool
   *   TRUE if any action was taken, FALSE if not.
   */
  protected function handleUnsubmittedForm(&$form_state) {
    $output = drupal_build_form('panels_ipe_edit_control_form', $form_state);

    // Display the form if it has not yet been submitted.
    if (empty($form_state['executed'])) {
      // At this point, we want to save the cache to ensure that we have a lock.
      $this->cache->ipe_locked = TRUE;
      panels_edit_cache_set($this->cache);
      $this->commands[] = array(
        'command' => 'initIPE',
        'key' => $this->clean_key,
        'data' => drupal_render($output),
        'lockPath' => url($this->get_url('unlock_ipe')),
      );
      return TRUE;
    }

    return FALSE;
  }

  /**
   * Releases the content lock, necessary depending on the clicked button.
   *
   * @param array $form_state
   *   The current form state.
   *
   * @return bool
   *   TRUE if any action was taken, FALSE if not.
   */
  protected function handleContentLock($form_state) {
    global $user;

    // Release the node lock when saving the display.
    if ($form_state['clicked_button']['#id'] == 'panels-ipe-save' && module_exists('content_lock')) {
      $node = $this->cache->display->context['panelizer']->data;
      if (_content_lock_is_lockable_node($node)) {
        content_lock_release($node->nid, $user->uid);
        return TRUE;
      }
    }

    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function render_pane(&$pane) {
    $return = parent::render_pane($pane);
    // Add the UUID to the rendered pane so our Selenium tests can target them.
    return preg_replace('/<div/', '<div data-pane-uuid="' . $pane->uuid . '"', $return, 1);
  }

  /**
   * Add our javascript and css to the page.
   *
   * We do this only once per page request and thus use add_meta(), as the IPE
   * renderer class does.
   *
   * @todo Unset the IPE toolbar buttons so we don't need to programmatically
   * click a hidden button and directly get on the 'edit' page.
   * Hint: search for: '#href' => $this->get_url('save_form'),
   * Related ticket: KANWEBS-1107.
   */
  public function add_meta() {
    drupal_add_css(drupal_get_path('module', 'paddle_panels_renderer') . '/css/add-pane.css');

    // Add the a JS file to trigger the 'Customize this page' button from IPE
    // and hide it, so we get on the "edit" page directly.
    drupal_add_js(drupal_get_path('module', 'paddle_panels_renderer') . '/js/paddle_panels_renderer_actions.js');
    // By default, don't redirect after save.
    $js_settings = array(
      'paddle_panels_renderer_redirect_after_save' => FALSE,
    );
    drupal_add_js($js_settings, 'setting');

    // Don't skip what the parents would do.
    parent::add_meta();
  }
}
