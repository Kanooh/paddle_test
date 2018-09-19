<?php

/**
 * @file
 * Theme and preprocess functions for calendar paddlet.
 */

/**
 * Theme function to render the date field in the day display.
 */
function theme_views_view_field__calendar_pane__day_view__field_paddle_calendar_date($variables) {
  // Retrieve the field value.
  $field_name = $variables['field']->definition['field_name'];
  $values = $variables['view']->field[$field_name]->get_value($variables['row']);

  // If no value, don't show anything.
  if (empty($values[0])) {
    return '';
  }

  // This is a single cardinality field.
  $item = $values[0];

  // Retrieve the field settings and date information.
  // @see date_formatter_process()
  $field = field_info_field($field_name);
  $timezone = isset($item['timezone']) ? $item['timezone'] : '';
  $timezone = date_get_timezone($field['settings']['tz_handling'], $timezone);
  $timezone_db = date_get_timezone_db($field['settings']['tz_handling']);
  $db_format = date_type_format($field['type']);

  $dates = array();
  // Loop value and value2 dates to build comparable representations.
  foreach (date_process_values($field) as $processed) {
    $date = new DateObject($item[$processed], $timezone_db, $db_format);
    $date->limitGranularity($field['settings']['granularity']);
    date_timezone_set($date, timezone_open($timezone));

    $dates[$processed]['date'] = date_format_date(
      $date,
      'custom',
      date_limit_format(DATE_FORMAT_DATETIME, array('year', 'month', 'day'))
    );
    $dates[$processed]['time'] = date_format_date(
      $date,
      'custom',
      date_limit_format(DATE_FORMAT_DATETIME, array('hour', 'minute', 'second'))
    );
  }

  // If the dates match, but not the time, it means that the event spans only
  // one day and has different start and end time. We then render normally
  // the field like template_preprocess_views_view_field() does.
  // Note that said preprocess function hasn't run for this views field as
  // we have overridden the theme function.
  // It's also safe to do these checks and rendering as this function only
  // runs for the correct view, display and field thanks to
  // views_handler_field::theme_functions() theme hooks lists.
  if (
    $dates['value']['date'] === $dates['value2']['date'] &&
    $dates['value']['time'] !== $dates['value2']['time']
  ) {
    return $variables['field']->advanced_render($variables['row']);
  }

  return '';
}
