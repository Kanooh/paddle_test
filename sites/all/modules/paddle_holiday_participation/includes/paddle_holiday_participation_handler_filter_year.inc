<?php
/**
 * @file
 * Filter year range.
 */

/**
 * paddle_holiday_participation_handler_filter_year.
 */
class paddle_holiday_participation_handler_filter_year extends views_handler_filter {
  /**
   * {@inheritdoc}
   */
  public function option_definition() {
    $options = parent::option_definition();

    $options['operator'] = array('default' => 'within');
    $options['contract_start_year'] = array('default' => '');
    $options['contract_end_year'] = array('default' => '');
    $options['include_endpoints'] = array('default' => TRUE);

    return $options;
  }

  /**
   * Define the operators supported for ranges.
   */
  public function operators() {
    $operators = array(
      'within' => array(
        'title' => t('Range contains'),
        'short' => t('range contains'),
        'method' => 'op_within',
        'values' => 1,
      ),
      'not within' => array(
        'title' => t('Range does not contain'),
        'short' => t('range does not contain'),
        'method' => 'op_within',
        'values' => 1,
      ),
    );

    return $operators;
  }

  /**
   * {@inheritdoc}
   */
  public function operator_options($which = 'title') {
    $options = array();
    foreach ($this->operators() as $key => $value) {
      $options[$key] = $value['title'];
    }

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function options_form(&$form, &$form_state) {
    parent::options_form($form, $form_state);
    $options = array();
    foreach ($this->view->display_handler->get_handlers('field') as $name => $field) {
      $options[$name] = $field->ui_name(TRUE);
    }

    $form['contract_start_year'] = array(
      '#type' => 'select',
      '#title' => t('Contract start year'),
      '#default_value' => $this->options['contract_start_year'],
      '#options' => $options,
    );

    $form['contract_end_year'] = array(
      '#type' => 'select',
      '#title' => t('Contract end year'),
      '#default_value' => $this->options['contract_end_year'],
      '#options' => $options,
    );
  }

  /**
   * {@inheritdoc}
   */
  public function value_form(&$form, &$form_state) {
    $options = array(
      date('Y') => date('Y'),
      date('Y', strtotime('+1 year')) => date('Y', strtotime('+1 year')),
    );

    $form['value'] = array(
      '#type' => 'select',
      '#title' => empty($form_state['exposed']) ? t('Which year?') : '',
      '#options' => $options,
      '#default_value' => $this->value,
      '#attributes' => array('placeholder' => array(t('Which year?'))),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function query() {
    $this->view->_build('field');
    if (!empty($this->value[0])) {
    list($field_from_name, $field_to_name) = $this->get_range_subfields();

      $field_from = $this->view->field[$field_from_name];
      $field_to = $this->view->field[$field_to_name];
      // Always add the table of the selected fields to be sure a table alias
      // exists.
      $field_from->ensure_my_table();
      $field_to->ensure_my_table();

      if (!empty($field_from->field_alias) && !empty($field_to->field_alias)) {
        $field_from_full = "$field_from->table_alias.$field_from->real_field";
        $field_to_full = "$field_to->table_alias.$field_to->real_field";
      }

      $info = $this->operators();
      if (!empty($info[$this->operator]['method'])) {
        $this->{$info[$this->operator]['method']}($field_from_full, $field_to_full);
      }
    }
  }

  /**
   * Operation callback.
   *
   * @var $field_from
   * @var $field_to
   */
  protected function op_within($field_from, $field_to) {
    $operators = array(
      '<',
      '>',
      '<=',
      '>=',
    );

    $include_endpoints = TRUE;
    list($op_left, $op_right) = array_slice($operators, $include_endpoints ? 2 : 0, 2);

    $this->op_within_query($this->operator, $field_from, $field_to, $op_left, $op_right);
  }

  /**
   * Operation query.
   *
   * @var $operator
   * @var $field_from
   * @var $field_to
   * @var $op_left
   * @var $op_right
   */
  protected function op_within_query($operator, $field_from, $field_to, $op_left, $op_right) {
    if ($operator === 'within') {
      $this->query->add_where($this->options['group'], db_and()
        ->condition($field_from, $this->value, $op_left)
        ->condition($field_to, $this->value, $op_right));
    }
    else {
      $this->query->add_where($this->options['group'], db_or()
        ->condition($field_from, $this->value, $op_right)
        ->condition($field_to, $this->value, $op_left));
    }
  }

  /**
   * Returns database field names for FROM and TO values.
   *
   * @return array
   *   FROM and TO values database field names.
   */
  protected function get_range_subfields() {
    $contract_start_year = $this->options['contract_start_year'];
    $contract_end_year = $this->options['contract_end_year'];

    return array(
      $contract_start_year,
      $contract_end_year,
    );
  }

  /**
   * {@inheritdoc}
   */
  public function admin_summary() {
    if ($this->is_a_group()) {
      return t('grouped');
    }
    if (!empty($this->options['exposed'])) {
      return t('exposed');
    }

    $options = $this->operator_options('short');
    $output = check_plain($options[$this->operator]) . ' ' . check_plain($this->value);

    return $output;
  }

}