<?php

/**
 * @file
 * Renderer class for our overridden In-Place Editor (IPE) behavior.
 */

class panels_renderer_paddle_content_region extends panels_renderer_paddle {

  /**
   * {@inheritdoc}
   */
  public function render_pane(&$pane) {
    // This implementation prevents panes from being dropped in a non Content
    // Region region, by using adjusted rendering logic.
    $content = $this->render_pane_content($pane);
    if ($this->display->hide_title == PANELS_TITLE_PANE && !empty($this->display->title_pane) && $this->display->title_pane == $pane->pid) {

      // If the user selected to override the title with nothing, and selected
      // this as the title pane, assume the user actually wanted the original
      // title to bubble up to the top but not actually be used on the pane.
      if (empty($content->title) && !empty($content->original_title)) {
        $this->display->stored_pane_title = $content->original_title;
      }
      else {
        $this->display->stored_pane_title = !empty($content->title) ? $content->title : '';
      }
    }

    if (!empty($content->content)) {
      if (!empty($pane->style['style'])) {
        $style = panels_get_style($pane->style['style']);

        if (isset($style) && isset($style['render pane'])) {
          $output = theme($style['render pane'], array(
            'content' => $content,
            'pane' => $pane,
            'display' => $this->display,
            'style' => $style,
            'settings' => $pane->style['settings'],
          ));

          // This could be null if no theme function existed.
          if (isset($output)) {
            return $output;
          }
        }
      }

      // Fallback.
      $output = theme('panels_pane', array(
        'content' => $content,
        'pane' => $pane,
        'display' => $this->display,
      ));
      if (empty($output)) {
        return;
      }

      // If there are region locks, add them.
      if (!empty($pane->locks['type']) && $pane->locks['type'] == 'regions') {
        static $key = NULL;
        $javascript = &drupal_static('drupal_add_js', array());

        // drupal_add_js breaks as we add these, but we can't just lump them
        // together because panes can be rendered independently. So game the
        // system:
        if (empty($key)) {
          $settings['Panels']['RegionLock'][$pane->pid] = $pane->locks['regions'];
          drupal_add_js($settings, 'setting');

          // These are just added via [] so we have to grab the last one
          // and reference it.
          $keys = array_keys($javascript['settings']['data']);
          $key = end($keys);
        }
        else {
          $javascript['settings']['data'][$key]['Panels']['RegionLock'][$pane->pid] = $pane->locks['regions'];
        }

      }

      if (empty($pane->IPE_empty)) {
        // Add an inner layer wrapper to the pane content before placing it into
        // draggable portlet.
        $output = "<div class=\"panels-ipe-portlet-content\">$output</div>";
      }
      else {
        $output = "<div class=\"panels-ipe-portlet-content panels-ipe-empty-pane\">$output</div>";
      }
      // Hand it off to the plugin/theme for placing draggers/buttons.
      $output = theme('panels_ipe_pane_wrapper', array(
        'output' => $output,
        'pane' => $pane,
        'display' => $this->display,
        'renderer' => $this)
      );

      if (!empty($pane->locks['type']) && $pane->locks['type'] == 'immovable') {
        if ($this->plugin['renderer'] == 'panels_renderer_paddle_content_region' && !array_key_exists($pane->panel, paddle_content_region_get_regions($this->display))) {
          // We don't want to be able to drag panes in a non Content Region
          // region. We omit CSS class panels-ipe-portlet-marker.
          return "<div id=\"panels-ipe-paneid-{$pane->pid}\" class=\"panels-ipe-nodrag panels-ipe-portlet-wrapper\" data-pane-uuid=\"{$pane->uuid}\">" . $output . "</div>";
        }

        return "<div id=\"panels-ipe-paneid-{$pane->pid}\" class=\"panels-ipe-nodrag panels-ipe-portlet-wrapper panels-ipe-portlet-marker\" data-pane-uuid=\"{$pane->uuid}\">" . $output . "</div>";
      }

      return "<div id=\"panels-ipe-paneid-{$pane->pid}\" class=\"panels-ipe-portlet-wrapper panels-ipe-portlet-marker\" data-pane-uuid=\"{$pane->uuid}\">" . $output . "</div>";
    }
  }

  /**
   * Render a specific region.
   *
   * Prevent panes from being added to, dragged in and dropped in a non Content
   * Region region, by using adjusted rendering logic.
   *
   * @param string $region_id
   *   ID of the region.
   * @param array $panes
   *   Panes in this region
   *
   * @return string
   *   Rendered region.
   */
  public function render_region($region_id, $panes) {
    if (!array_key_exists($region_id, paddle_content_region_get_regions($this->display))) {
      // Based on panels_renderer_ipe::render_region(), but:
      // - Don't render the button to add a pane.
      // - Don't add the CSS classes to make panes draggable into the region.
      $empty_ph = theme('panels_ipe_placeholder_pane', array('region_id' => $region_id, 'region_title' => $this->plugins['layout']['regions'][$region_id]));
      $control = '<div class="panels-ipe-placeholder">' . $empty_ph . "</div>";
      $output = panels_renderer_editor::render_region($region_id, $panes);
      $output = theme('panels_ipe_region_wrapper', array(
        'output' => $output,
        'region_id' => $region_id,
        'display' => $this->display,
        'controls' => $control,
        'renderer' => $this,
      ));
      return "<div id='panels-ipe-regionid-$region_id' class='panels-ipe-region'>$output</div>";
    }
    else {
      return parent::render_region($region_id, $panes);
    }
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
   * {@inheritdoc}
   */
  protected function processFormSubmission($form_state) {
    // Save the display if the Save or a moderation button was clicked.
    if (!empty($form_state['clicked_button']['#save-display'])) {
      // Saved. Save the cache.
      panels_edit_cache_save($this->cache);

      $node = $this->cache->display->context['panelizer']->data;

      // Set the correct moderation state if a moderation change was requested.
      if (!empty($form_state['clicked_button']['#workbench_moderation_state_new'])) {

        // Set the assignee uid, this will be handled by
        // paddle_content_manager_workbench_moderation_transition().
        // That method also takes care of validation, so don't bother to handle
        // this here.
        $node->assignee_uid = $form_state['values']['assignee_uid'];

        workbench_moderation_moderate($node, $form_state['clicked_button']['#workbench_moderation_state_new']);
      }
      else {
        // Set update message.
        drupal_set_message(t('The changes have been saved.'), 'status');
      }
    }
    else {
      // Cancelled. Clear the cache.
      panels_edit_cache_clear($this->cache);
    }

    $this->commands[] = array(
      'command' => 'endIPE',
      'key' => $this->clean_key,
    );
  }
}
