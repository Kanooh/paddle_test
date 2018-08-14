<?php
/**
 * @file
 * Filter based on moderation state.
 */

class paddle_content_manager_handler_filter_state extends views_handler_filter_in_operator {
  /**
   * @inheritdoc
   */
  function get_value_options() {
    if (!isset($this->value_options)) {
      $this->value_title = t('Moderation status');
      $this->value_options = array_map('check_plain', workbench_moderation_state_labels());
      $this->value_options['scheduled_depublication'] = t('Scheduled for depublication');
      $this->value_options['scheduled'] = t('Scheduled for publication');

      // Do this to leave "Published" as last.
      $published = $this->value_options['published'];
      unset($this->value_options['published']);
      $this->value_options['published'] = $published;
    }
  }

  /**
   * Alters Views query when filter is used.
   */
  function query() {
    // If the filter is "Scheduled for publication" we need some special
    // handling - otherwise allow the default handling.
    if (isset($this->view->exposed_data[$this->real_field]) && $this->view->exposed_data[$this->real_field] == 'scheduled_depublication') {
      $this->ensure_my_table();
      $field = "$this->table_alias.$this->real_field";
      $this->query->add_where_expression($this->options['group'], "$field = 'published'");

      $join = new views_join();
      $join->table = 'scheduler';
      $join->field = 'nid';
      $join->left_table = 'workbench_moderation_node_history';
      $join->left_field = 'nid';
      $join->type = 'LEFT';
      $alias = $this->query->add_relationship('scheduler', $join, 'workbench_moderation_node_history');
      $this->query->add_where_expression($this->options['group'], "$alias.unpublish_on > " . time());
    }
    else {
      parent::query();
    }
  }
}
