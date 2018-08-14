<?php

/**
 * @file
 * Renderer class to override Panels Standard renderer.
 */

class panels_standard_renderer_paddle extends panels_renderer_standard {

  /**
   * {@inheritdoc}
   */
  public function render_pane(&$pane) {
    $return = parent::render_pane($pane);
    // Add the UUID as a data attribute to the rendered pane so our Selenium
    // tests can target them.
    return preg_replace('/<div/', '<div data-pane-uuid="' . $pane->uuid . '"', $return, 1);
  }
}
