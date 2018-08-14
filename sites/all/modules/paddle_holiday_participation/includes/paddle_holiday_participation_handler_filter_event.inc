<?php
/**
 * @file
 * Filter based on event.
 */

/**
 * Class paddle_holiday_participation_handler_filter_event.
 */
class paddle_holiday_participation_handler_filter_event extends views_handler_filter_in_operator {
  /**
   * @inheritdoc
   */
  function get_value_options() {
    if (!isset($this->value_options)) {
      $this->value_title = t('Temporary event');
      $this->value_options['hp_evenement'] = t('Temporary event');
    }
  }

  /**
   * Alters Views query when filter is used.
   */
  function query() {
    if (isset($this->view->exposed_data[$this->real_field]) && $this->view->exposed_data[$this->real_field]['hp_evenement'] !== 0) {
      $this->ensure_my_table();
      $field = "$this->table_alias.$this->real_field";
      $this->query->add_where_expression($this->options['group'], "$field = 'hp_evenement'");
    }
  }
}
