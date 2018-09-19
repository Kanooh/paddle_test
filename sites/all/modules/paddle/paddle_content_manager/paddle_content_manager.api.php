<?php

/**
 * @file
 * Hook provided by the Paddle Content Manager module.
 */

/**
 * A node has been assigned to an user.
 *
 * @param object $node
 *   The node for which an assignee has been set.
 * @param object $assignee
 *   The user the node was assigned to.
 * @param string $new_state
 *   The state the node is being moderated to.
 */
function hook_paddle_content_manager_assignee_set($node, $assignee, $new_state) {
  // Do something.
}

/**
 * Specify the additional fields to be shown on a node edit form.
 *
 * @return string[]
 *   An array of node field names.
 */
function hook_paddle_content_manager_additional_fields() {
  return array('field_name_A', 'field_name_B');
}

/**
 * Group additional form fields.
 *
 * @return array
 *   A nested array with machine name as key and following sub items:
 *   - label (string) Visible group label on the node edit form.
 *   - weight (int) A way to order groups.
 *   - fields (string[]) The fields that need to go in this group.
 */
function hook_paddle_content_manager_additional_fields_groups() {
  return array(
    'group_x' => array(
      'label' => t('Group X'),
      'weight' => 1,
      'fields' => array(
        'field_name_A',
        'field_name_B',
      ),
    ),
  );
}
