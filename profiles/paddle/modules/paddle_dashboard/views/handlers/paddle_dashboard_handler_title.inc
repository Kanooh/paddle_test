<?php
/**
 * @file
 * Views handler to display a more link on Paddle Dashboard views.
 */

class paddle_dashboard_handler_title extends views_handler_field_node {
  /**
   * Overrides views_handler_field::render().
   */
  public function render($values) {
    $value = '<span class="title" title="' . check_plain($this->get_value($values)) . '">' . check_plain($this->get_value($values)) . '</span>';
    return $this->render_link($value, $values);
  }
}
