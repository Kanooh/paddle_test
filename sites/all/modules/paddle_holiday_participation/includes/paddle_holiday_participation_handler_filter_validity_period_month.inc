<?php
/**
 * @file
 * Filter based on month.
 */

/**
 * Class paddle_holiday_participation_handler_filter_validity_period_month.
 */
class paddle_holiday_participation_handler_filter_validity_period_month extends views_handler_filter_in_operator {
  /**
   * @inheritdoc
   */
  function get_value_options() {
    if (!isset($this->value_options)) {
      $this->value_title = t('Months');
      $this->value_options['1'] = t('January');
      $this->value_options['2'] = t('February');
      $this->value_options['3'] = t('March');
      $this->value_options['4'] = t('April');
      $this->value_options['5'] = t('May');
      $this->value_options['6'] = t('June');
      $this->value_options['7'] = t('July');
      $this->value_options['8'] = t('August');
      $this->value_options['9'] = t('September');
      $this->value_options['10'] = t('October');
      $this->value_options['11'] = t('November');
      $this->value_options['12'] = t('December');

    }
  }

  /**
   * Alters Views query when filter is used.
   */
  function query() {
    if (isset($this->view->exposed_data[$this->real_field]) && !empty($this->value[0])) {
      $this->ensure_my_table();
      $value = $this->value[0];
      $this->query->add_where_expression(
        $this->options['group'],
        "MONTH(field_data_field_hp_validity_period.field_hp_validity_period_value) <= :value",
        array(':value' => $value)
      );

      $this->query->add_where_expression(
        $this->options['group'],
        "MONTH(field_data_field_hp_validity_period.field_hp_validity_period_value2) >= :value",
        array(':value' => $value)
      );
    }
  }
}
