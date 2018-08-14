<?php
/**
 * @file
 * Views handler to display a more link on Paddle Dashboard views.
 */

class paddle_dashboard_handler_more extends views_handler_field {

  /**
   * Overrides views_handler_field::query().
   */
  public function query() {
    // Empty implementation to override the parent query.
  }

  /**
   * Overrides views_handler_field::admin_summary().
   */
  public function admin_summary() {
    return 'More';
  }

  /**
   * Overrides views_handler_field::option_definition().
   */
  public function option_definition() {
    $options = parent::option_definition();

    $options['path'] = array('default' => '');
    $options['user_query_parameter'] = array('default' => '');
    $options['workbench_moderation_state'] = array('default' => '');

    return $options;
  }

  /**
   * Overrides views_handler_field::options_form().
   */
  public function options_form(&$form, &$form_state) {
    parent::options_form($form, $form_state);

    $form['path'] = array(
      '#title' => t('Path'),
      '#type' => 'textfield',
      '#default_value' => $this->options['path'] ?: '',
      '#required' => TRUE,
      '#description' => t('The path to link to.'),
    );

    $form['user_query_parameter'] = array(
      '#title' => t('User query parameter'),
      '#type' => 'textfield',
      '#default_value' => $this->options['user_query_parameter'] ?: '',
      '#description' => t('Name of the query parameter to pass the UID of the current user with.'),
    );

    // Create the array with the moderation states.
    $options = array();
    foreach (workbench_moderation_states() as $machine_name => $state) {
      // For the scheduled state we require special love because we need to add
      // the scheduled for depublication state as well.
      if ($machine_name == 'scheduled') {
        $options['scheduled_depublication'] = t('Scheduled for depublication');
        $options[$machine_name] = t('Scheduled for publication');
      }
      else {
        $options[$machine_name] = $state->label;
      }
    }
    $form['workbench_moderation_state'] = array(
      '#title' => t('Workbench moderation state'),
      '#type' => 'select',
      '#options' => $options,
      '#default_value' => $this->options['workbench_moderation_state'] ?: '',
      '#description' => t('Workbench moderation state to filter on.'),
    );
  }

  /**
   * Overrides views_handler_field::render().
   */
  public function render($empty = FALSE) {
    $path = $this->options['path'];
    $user_query_parameter = $this->options['user_query_parameter'];
    $workbench_moderation_state = $this->options['workbench_moderation_state'];

    $options = array();

    if ($user_query_parameter) {
      global $user;
      $options['query'][$user_query_parameter] = $user->uid;
    }

    if ($workbench_moderation_state) {
      $options['query']['state'] = $workbench_moderation_state;
    }

    return l(t('More'), $path, $options);
  }
}
