<?php

/**
 * @file
 * Definition of paddle_i18n_handller_filter_missing_translation.
 */

/**
 * Filter by language.
 *
 * This Filter is based on the tmgmt_node_handler_filter_missing_translation
 * filter of the Translation Management Tool module.
 *
 * @ingroup views_filter_handlers
 * @see https://www.drupal.org/project/tmgmt
 */
class paddle_i18n_handler_filter_missing_translation extends views_handler_filter {

  /**
   * {@inheritdoc}
   */
  function query() {
    $this->ensure_my_table();

    // Don't do anything if no language was selected.
    if (!$this->value) {
      return;
    }

    $join = new views_join();
    $join->definition['left_table'] = 'node';
    $join->definition['left_field'] = 'tnid';
    $join->definition['table'] = 'node';
    $join->definition['field'] = 'tnid';
    $join->definition['type'] = 'LEFT';
    $join->construct();

    $join->extra = array(
      array(
        'field' => 'language',
        'value' => $this->value,
      ),
    );

    $table_alias = $this->query->add_table('node', $this->relationship, $join);

    $this->query->add_where_expression($this->options['group'], "{$this->table_alias}.language != :language", array(':language' => $this->value));
    // If tnid equals 0, then there is no translation at all for the node.
    // When tnid does not equal 0, we need to check that there
    // are no nodes available in the given language.
    $this->query->add_where_expression($this->options['group'], "$table_alias.nid IS NULL OR {$this->table_alias}.tnid = 0");
  }

  /**
   * {@inheritdoc}
   */
  function admin_summary() {
    if ($this->is_a_group()) {
      return t('grouped');
    }
    if (!empty($this->options['exposed'])) {
      return t('exposed');
    }

    if (!is_array($this->value)) {
      return;
    }

    $return_value = '';
    foreach ($this->value as $value) {
      if (!empty($return_value)) {
        $return_value .= ' - ';
      }
      $return_value .= $value;
    }

    return $return_value;
  }

  /**
   * {@inheritdoc}
   */
  function value_form(&$form, &$form_state) {
    $options = array();
    foreach (language_list() as $langcode => $language) {
      $options[$langcode] = $language->name;
    }

    // Remove the current language from the list.
    $current_language = i18n_language_content();
    unset($options[$current_language->language]);

    $form['value'] = array(
      '#type' => 'select',
      '#options' => $options,
      '#id' => 'paddle_i18n_missing_target_language',
    );
  }

}
