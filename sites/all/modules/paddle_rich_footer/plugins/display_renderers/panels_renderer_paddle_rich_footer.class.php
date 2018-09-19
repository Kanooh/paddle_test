<?php

/**
 * @file
 * Renderer class for our overridden In-Place Editor (IPE) behavior.
 */

class panels_renderer_paddle_rich_footer extends panels_renderer_paddle {

  /**
   * {@inheritdoc}
   *
   * - Add a message after successful save.
   * - Go back to the overview page.
   */
  public function ajax_save_form($break = NULL) {
    // First do the parent's parent's logic.
    // Don't use the direct parent as that contains Landing Page specific code.
    // @todo Refactor this so it can just use parent::ajax_save_form($break);
    panels_renderer_ipe::ajax_save_form($break);

    $form_saved = FALSE;
    foreach ($this->commands as $command) {
      if ($command['command'] == 'insert' && $command['method'] == 'replaceWith') {
        $form_saved = TRUE;
      }
    }

    // On save.
    if ($form_saved) {
      // Add a message.
      drupal_set_message(t('@what has been updated.', array('@what' => $this->display->get_title())), 'status');

      // Get back to the overview page.
      $this->commands[] = ctools_ajax_command_redirect('admin/paddlet_store/app/paddle_rich_footer/configure');
    }
  }

}
