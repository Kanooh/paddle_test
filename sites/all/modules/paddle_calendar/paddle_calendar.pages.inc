<?php
/**
 * @file
 * Page callbacks for Calendar paddlet.
 */

/**
 * Page callback to render a view of calendar items for a specific date.
 *
 * @param bool $js
 *   True is js is supported.
 * @param string $dom_id
 *   The dom id of the view which generated the request.
 * @param string $date
 *   The date in format YYYY-MM-DD.
 * @param string|null $tags
 *   A list of tag ids, joined with the operator sign. Null if no tags.
 *
 * @return array
 *   An array of ajax commands.
 */
function paddle_calendar_item_list($js, $dom_id, $date, $tags = NULL) {
  // If no js, quit.
  if (!$js) {
    return array();
  }

  ctools_include('ajax');

  // Get the view and set the correct display.
  // @see view_embed_view().
  $view = views_get_view('calendar_pane');
  if (!$view || !$view->access('day_view') || !$view->set_display('day_view')) {
    return array();
  }

  // Retrieve the header settings, and put the correct formatted date in.
  $header = $view->display_handler->get_option('header');
  $header['area_text_custom']['content'] = format_date(strtotime($date), 'paddle_core_date_medium');
  $view->display_handler->set_option('header', $header);

  // Continue the execution of the view.
  // @see view_embed_view().
  $output = $view->preview('day_view', array($date, $tags));

  // Prepare the selector for later operations.
  $selector = '.view-dom-id-' . $dom_id;

  $commands = array();
  // Remove the old day view, if any. We cannot use dom_id of the day view
  // as it changes if not automatically run by views ajax system.
  $commands[] = ajax_command_remove($selector . ' .view-display-id-day_view');

  // Append the new view, if available.
  if (!empty($output)) {
    $commands[] = ajax_command_append($selector, $output);
  }

  print ajax_render($commands);

  ajax_footer();
  drupal_exit();
}
