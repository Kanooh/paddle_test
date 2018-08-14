<?php
/**
 * @file
 * paddle_quiz.features.field_instance.inc
 */

/**
 * Implements hook_field_default_field_instances().
 */
function paddle_quiz_field_default_field_instances() {
  $field_instances = array();

  // Exported field_instance: 'field_collection_item-field_paddle_quiz_questions-field_paddle_quiz_answers'
  $field_instances['field_collection_item-field_paddle_quiz_questions-field_paddle_quiz_answers'] = array(
    'bundle' => 'field_paddle_quiz_questions',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'text',
        'settings' => array(),
        'type' => 'text_default',
        'weight' => 1,
      ),
    ),
    'entity_type' => 'field_collection_item',
    'field_name' => 'field_paddle_quiz_answers',
    'label' => 'Answers',
    'required' => 1,
    'settings' => array(
      'better_formats' => array(
        'allowed_formats' => array(
          'full_html' => 'full_html',
          'plain_text' => 'plain_text',
        ),
        'allowed_formats_toggle' => 0,
        'default_order_toggle' => 0,
        'default_order_wrapper' => array(
          'formats' => array(
            'full_html' => array(
              'weight' => -10,
            ),
            'plain_text' => array(
              'weight' => 10,
            ),
          ),
        ),
      ),
      'field_instance_sync' => 0,
      'text_processing' => 0,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'text',
      'settings' => array(
        'size' => 60,
      ),
      'type' => 'text_textfield',
      'weight' => 2,
    ),
  );

  // Exported field_instance: 'field_collection_item-field_paddle_quiz_questions-field_paddle_quiz_correct_answer'
  $field_instances['field_collection_item-field_paddle_quiz_questions-field_paddle_quiz_correct_answer'] = array(
    'bundle' => 'field_paddle_quiz_questions',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'text',
        'settings' => array(),
        'type' => 'text_default',
        'weight' => 2,
      ),
    ),
    'entity_type' => 'field_collection_item',
    'field_name' => 'field_paddle_quiz_correct_answer',
    'label' => 'Correct answer',
    'required' => 1,
    'settings' => array(
      'better_formats' => array(
        'allowed_formats' => array(
          'full_html' => 'full_html',
          'plain_text' => 'plain_text',
        ),
        'allowed_formats_toggle' => 0,
        'default_order_toggle' => 0,
        'default_order_wrapper' => array(
          'formats' => array(
            'full_html' => array(
              'weight' => -10,
            ),
            'plain_text' => array(
              'weight' => 10,
            ),
          ),
        ),
      ),
      'field_instance_sync' => 0,
      'text_processing' => 0,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'text',
      'settings' => array(
        'size' => 60,
      ),
      'type' => 'text_textfield',
      'weight' => 3,
    ),
  );

  // Exported field_instance: 'field_collection_item-field_paddle_quiz_questions-field_paddle_quiz_question'
  $field_instances['field_collection_item-field_paddle_quiz_questions-field_paddle_quiz_question'] = array(
    'bundle' => 'field_paddle_quiz_questions',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'text',
        'settings' => array(),
        'type' => 'text_default',
        'weight' => 0,
      ),
    ),
    'entity_type' => 'field_collection_item',
    'field_name' => 'field_paddle_quiz_question',
    'label' => 'Question',
    'required' => 1,
    'settings' => array(
      'better_formats' => array(
        'allowed_formats' => array(
          'full_html' => 'full_html',
          'plain_text' => 'plain_text',
        ),
        'allowed_formats_toggle' => 0,
        'default_order_toggle' => 0,
        'default_order_wrapper' => array(
          'formats' => array(
            'full_html' => array(
              'weight' => -10,
            ),
            'plain_text' => array(
              'weight' => 10,
            ),
          ),
        ),
      ),
      'field_instance_sync' => 0,
      'text_processing' => 0,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'text',
      'settings' => array(
        'size' => 60,
      ),
      'type' => 'text_textfield',
      'weight' => 0,
    ),
  );

  // Exported field_instance: 'field_collection_item-field_paddle_quiz_questions-field_paddle_quiz_question_image'
  $field_instances['field_collection_item-field_paddle_quiz_questions-field_paddle_quiz_question_image'] = array(
    'bundle' => 'field_paddle_quiz_questions',
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'paddle_scald_atom_field',
        'settings' => array(),
        'type' => 'paddle_scald_atom_image',
        'weight' => 3,
      ),
    ),
    'entity_type' => 'field_collection_item',
    'field_name' => 'field_paddle_quiz_question_image',
    'label' => 'Image',
    'required' => 0,
    'settings' => array(
      'field_instance_sync' => 0,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'paddle_scald_atom_field',
      'settings' => array(
        'allowed_types' => array(
          'file' => 0,
          'image' => 'image',
          'video' => 0,
        ),
      ),
      'type' => 'paddle_scald_atom_image',
      'weight' => 1,
    ),
  );

  // Exported field_instance: 'node-quiz_page-field_paddle_quiz_reference'
  $field_instances['node-quiz_page-field_paddle_quiz_reference'] = array(
    'bundle' => 'quiz_page',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'entityreference',
        'settings' => array(
          'link' => FALSE,
        ),
        'type' => 'entityreference_label',
        'weight' => 9,
      ),
      'diff_standard' => array(
        'label' => 'above',
        'module' => 'entityreference',
        'settings' => array(
          'link' => FALSE,
        ),
        'type' => 'entityreference_label',
        'weight' => 9,
      ),
      'full' => array(
        'label' => 'hidden',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 1,
      ),
      'listing_teaser' => array(
        'label' => 'above',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 0,
      ),
      'listing_title' => array(
        'label' => 'above',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 0,
      ),
      'news_extended' => array(
        'label' => 'above',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 0,
      ),
      'news_short' => array(
        'label' => 'above',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 0,
      ),
      'node_content_pane_full' => array(
        'label' => 'above',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 0,
      ),
      'node_content_pane_summary' => array(
        'label' => 'above',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 0,
      ),
      'teaser' => array(
        'label' => 'above',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 0,
      ),
    ),
    'entity_type' => 'node',
    'field_name' => 'field_paddle_quiz_reference',
    'label' => 'Quiz',
    'required' => 1,
    'settings' => array(
      'field_instance_sync' => 0,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'options',
      'settings' => array(),
      'type' => 'options_buttons',
      'weight' => 12,
    ),
  );

  // Exported field_instance: 'paddle_quiz-paddle_quiz-field_paddle_quiz_btn_next_l'
  $field_instances['paddle_quiz-paddle_quiz-field_paddle_quiz_btn_next_l'] = array(
    'bundle' => 'paddle_quiz',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'text',
        'settings' => array(),
        'type' => 'text_default',
        'weight' => 17,
      ),
    ),
    'entity_type' => 'paddle_quiz',
    'field_name' => 'field_paddle_quiz_btn_next_l',
    'label' => '"Next" button label',
    'required' => 1,
    'settings' => array(
      'better_formats' => array(
        'allowed_formats' => array(
          'full_html' => 'full_html',
          'plain_text' => 'plain_text',
        ),
        'allowed_formats_toggle' => 0,
        'default_order_toggle' => 0,
        'default_order_wrapper' => array(
          'formats' => array(
            'full_html' => array(
              'weight' => -10,
            ),
            'plain_text' => array(
              'weight' => 10,
            ),
          ),
        ),
      ),
      'field_instance_sync' => 0,
      'text_processing' => 0,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'text',
      'settings' => array(
        'size' => 60,
      ),
      'type' => 'text_textfield',
      'weight' => 2,
    ),
  );

  // Exported field_instance: 'paddle_quiz-paddle_quiz-field_paddle_quiz_btn_previous_l'
  $field_instances['paddle_quiz-paddle_quiz-field_paddle_quiz_btn_previous_l'] = array(
    'bundle' => 'paddle_quiz',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'text',
        'settings' => array(),
        'type' => 'text_default',
        'weight' => 16,
      ),
    ),
    'entity_type' => 'paddle_quiz',
    'field_name' => 'field_paddle_quiz_btn_previous_l',
    'label' => '"Previous" button label',
    'required' => 1,
    'settings' => array(
      'better_formats' => array(
        'allowed_formats' => array(
          'full_html' => 'full_html',
          'plain_text' => 'plain_text',
        ),
        'allowed_formats_toggle' => 0,
        'default_order_toggle' => 0,
        'default_order_wrapper' => array(
          'formats' => array(
            'full_html' => array(
              'weight' => -10,
            ),
            'plain_text' => array(
              'weight' => 10,
            ),
          ),
        ),
      ),
      'field_instance_sync' => 0,
      'text_processing' => 0,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'text',
      'settings' => array(
        'size' => 60,
      ),
      'type' => 'text_textfield',
      'weight' => 0,
    ),
  );

  // Exported field_instance: 'paddle_quiz-paddle_quiz-field_paddle_quiz_css'
  $field_instances['paddle_quiz-paddle_quiz-field_paddle_quiz_css'] = array(
    'bundle' => 'paddle_quiz',
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'file',
        'settings' => array(),
        'type' => 'file_default',
        'weight' => 19,
      ),
    ),
    'entity_type' => 'paddle_quiz',
    'field_name' => 'field_paddle_quiz_css',
    'label' => 'Custom CSS',
    'required' => 0,
    'settings' => array(
      'description_field' => 0,
      'field_instance_sync' => 0,
      'file_directory' => 'paddle_quiz_css',
      'file_extensions' => 'css',
      'max_filesize' => '',
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'file',
      'settings' => array(
        'progress_indicator' => 'throbber',
      ),
      'type' => 'file_generic',
      'weight' => 0,
    ),
  );

  // Exported field_instance: 'paddle_quiz-paddle_quiz-field_paddle_quiz_disclaimer'
  $field_instances['paddle_quiz-paddle_quiz-field_paddle_quiz_disclaimer'] = array(
    'bundle' => 'paddle_quiz',
    'default_value' => array(
      0 => array(
        'value' => '',
        'format' => 'full_html',
      ),
    ),
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'text',
        'settings' => array(),
        'type' => 'text_default',
        'weight' => 7,
      ),
    ),
    'entity_type' => 'paddle_quiz',
    'field_name' => 'field_paddle_quiz_disclaimer',
    'label' => 'Disclaimer',
    'required' => 0,
    'settings' => array(
      'better_formats' => array(
        'allowed_formats' => array(
          'full_html' => 'full_html',
          'plain_text' => 0,
        ),
        'allowed_formats_toggle' => 1,
        'default_order_toggle' => 0,
        'default_order_wrapper' => array(
          'formats' => array(
            'full_html' => array(
              'weight' => -10,
            ),
            'plain_text' => array(
              'weight' => 10,
            ),
          ),
        ),
      ),
      'field_instance_sync' => 0,
      'text_processing' => 1,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'text',
      'settings' => array(
        'rows' => 10,
      ),
      'type' => 'text_textarea',
      'weight' => 3,
    ),
  );

  // Exported field_instance: 'paddle_quiz-paddle_quiz-field_paddle_quiz_info_button_l'
  $field_instances['paddle_quiz-paddle_quiz-field_paddle_quiz_info_button_l'] = array(
    'bundle' => 'paddle_quiz',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'text',
        'settings' => array(),
        'type' => 'text_default',
        'weight' => 13,
      ),
    ),
    'entity_type' => 'paddle_quiz',
    'field_name' => 'field_paddle_quiz_info_button_l',
    'label' => 'Button label',
    'required' => 0,
    'settings' => array(
      'better_formats' => array(
        'allowed_formats' => array(
          'full_html' => 'full_html',
          'plain_text' => 'plain_text',
        ),
        'allowed_formats_toggle' => 0,
        'default_order_toggle' => 0,
        'default_order_wrapper' => array(
          'formats' => array(
            'full_html' => array(
              'weight' => -10,
            ),
            'plain_text' => array(
              'weight' => 10,
            ),
          ),
        ),
      ),
      'field_instance_sync' => 0,
      'text_processing' => 0,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'text',
      'settings' => array(
        'size' => 60,
      ),
      'type' => 'text_textfield',
      'weight' => 5,
    ),
  );

  // Exported field_instance:
  // 'paddle_quiz-paddle_quiz-field_paddle_quiz_info_location'
  $field_instances['paddle_quiz-paddle_quiz-field_paddle_quiz_info_location'] = array(
    'bundle' => 'paddle_quiz',
    'default_value' => array(
      0 => array(
        'value' => NULL,
      ),
    ),
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'list',
        'settings' => array(),
        'type' => 'list_default',
        'weight' => 9,
      ),
    ),
    'entity_type' => 'paddle_quiz',
    'field_name' => 'field_paddle_quiz_info_location',
    'label' => 'Location',
    'required' => 0,
    'settings' => array(
      'field_instance_sync' => 0,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'options',
      'settings' => array(),
      'type' => 'options_buttons',
      'weight' => 1,
    ),
  );

  // Exported field_instance:
  // 'paddle_quiz-paddle_quiz-field_paddle_quiz_info_message'
  $field_instances['paddle_quiz-paddle_quiz-field_paddle_quiz_info_message'] = array(
    'bundle' => 'paddle_quiz',
    'default_value' => array(
      0 => array(
        'value' => '',
      ),
    ),
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'text',
        'settings' => array(),
        'type' => 'text_default',
        'weight' => 11,
      ),
    ),
    'entity_type' => 'paddle_quiz',
    'field_name' => 'field_paddle_quiz_info_message',
    'label' => 'Message',
    'required' => 0,
    'settings' => array(
      'better_formats' => array(
        'allowed_formats' => array(
          'full_html' => 'full_html',
          'plain_text' => 0,
        ),
        'allowed_formats_toggle' => 1,
        'default_order_toggle' => 0,
        'default_order_wrapper' => array(
          'formats' => array(
            'full_html' => array(
              'weight' => -10,
            ),
            'plain_text' => array(
              'weight' => 10,
            ),
          ),
        ),
      ),
      'field_instance_sync' => 0,
      'text_processing' => 1,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'text',
      'settings' => array(
        'rows' => 5,
      ),
      'type' => 'text_textarea',
      'weight' => 3,
    ),
  );

  // Exported field_instance:
  // 'paddle_quiz-paddle_quiz-field_paddle_quiz_info_required'
  $field_instances['paddle_quiz-paddle_quiz-field_paddle_quiz_info_required'] = array(
    'bundle' => 'paddle_quiz',
    'default_value' => array(
      0 => array(
        'value' => 0,
      ),
    ),
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'list',
        'settings' => array(),
        'type' => 'list_default',
        'weight' => 20,
      ),
    ),
    'entity_type' => 'paddle_quiz',
    'field_name' => 'field_paddle_quiz_info_required',
    'label' => 'Info required',
    'required' => 0,
    'settings' => array(
      'field_instance_sync' => 0,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'options',
      'settings' => array(
        'display_label' => 1,
      ),
      'type' => 'options_onoff',
      'weight' => 0,
    ),
  );

  // Exported field_instance:
  // 'paddle_quiz-paddle_quiz-field_paddle_quiz_info_title'
  $field_instances['paddle_quiz-paddle_quiz-field_paddle_quiz_info_title'] = array(
    'bundle' => 'paddle_quiz',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'text',
        'settings' => array(),
        'type' => 'text_default',
        'weight' => 10,
      ),
    ),
    'entity_type' => 'paddle_quiz',
    'field_name' => 'field_paddle_quiz_info_title',
    'label' => 'Title',
    'required' => 1,
    'settings' => array(
      'better_formats' => array(
        'allowed_formats' => array(
          'full_html' => 'full_html',
          'plain_text' => 'plain_text',
        ),
        'allowed_formats_toggle' => 0,
        'default_order_toggle' => 0,
        'default_order_wrapper' => array(
          'formats' => array(
            'full_html' => array(
              'weight' => -10,
            ),
            'plain_text' => array(
              'weight' => 10,
            ),
          ),
        ),
      ),
      'field_instance_sync' => 0,
      'text_processing' => 0,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'text',
      'settings' => array(
        'size' => 60,
      ),
      'type' => 'text_textfield',
      'weight' => 2,
    ),
  );

  // Exported field_instance:
  // 'paddle_quiz-paddle_quiz-field_paddle_quiz_info_user'
  $field_instances['paddle_quiz-paddle_quiz-field_paddle_quiz_info_user'] = array(
    'bundle' => 'paddle_quiz',
    'default_value' => array(
      0 => array(
        'value' => 'email',
      ),
    ),
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'list',
        'settings' => array(),
        'type' => 'list_default',
        'weight' => 12,
      ),
    ),
    'entity_type' => 'paddle_quiz',
    'field_name' => 'field_paddle_quiz_info_user',
    'label' => 'User information',
    'required' => 1,
    'settings' => array(
      'field_instance_sync' => 0,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'options',
      'settings' => array(),
      'type' => 'options_buttons',
      'weight' => 4,
    ),
  );

  // Exported field_instance:
  // 'paddle_quiz-paddle_quiz-field_paddle_quiz_questions'
  $field_instances['paddle_quiz-paddle_quiz-field_paddle_quiz_questions'] = array(
    'bundle' => 'paddle_quiz',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'field_collection',
        'settings' => array(
          'add' => 'Add',
          'delete' => 'Delete',
          'description' => TRUE,
          'edit' => 'Edit',
          'view_mode' => 'full',
        ),
        'type' => 'field_collection_view',
        'weight' => 0,
      ),
    ),
    'entity_type' => 'paddle_quiz',
    'field_name' => 'field_paddle_quiz_questions',
    'label' => 'Questions',
    'required' => 1,
    'settings' => array(
      'field_instance_sync' => 0,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 0,
      'module' => 'field_collection',
      'settings' => array(),
      'type' => 'field_collection_embed',
      'weight' => 0,
    ),
  );

  // Exported field_instance: 'paddle_quiz-paddle_quiz-field_paddle_quiz_result_message'
  $field_instances['paddle_quiz-paddle_quiz-field_paddle_quiz_result_message'] = array(
    'bundle' => 'paddle_quiz',
    'default_value' => array(
      0 => array(
        'value' => '',
      ),
    ),
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'text',
        'settings' => array(),
        'type' => 'text_default',
        'weight' => 15,
      ),
    ),
    'entity_type' => 'paddle_quiz',
    'field_name' => 'field_paddle_quiz_result_message',
    'label' => 'Message',
    'required' => 0,
    'settings' => array(
      'better_formats' => array(
        'allowed_formats' => array(
          'full_html' => 'full_html',
          'plain_text' => 0,
        ),
        'allowed_formats_toggle' => 1,
        'default_order_toggle' => 0,
        'default_order_wrapper' => array(
          'formats' => array(
            'full_html' => array(
              'weight' => -10,
            ),
            'plain_text' => array(
              'weight' => 10,
            ),
          ),
        ),
      ),
      'field_instance_sync' => 0,
      'text_processing' => 1,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'text',
      'settings' => array(
        'rows' => 5,
      ),
      'type' => 'text_textarea',
      'weight' => 1,
    ),
  );

  // Exported field_instance: 'paddle_quiz-paddle_quiz-field_paddle_quiz_result_title'
  $field_instances['paddle_quiz-paddle_quiz-field_paddle_quiz_result_title'] = array(
    'bundle' => 'paddle_quiz',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'text',
        'settings' => array(),
        'type' => 'text_default',
        'weight' => 14,
      ),
    ),
    'entity_type' => 'paddle_quiz',
    'field_name' => 'field_paddle_quiz_result_title',
    'label' => 'Title',
    'required' => 1,
    'settings' => array(
      'better_formats' => array(
        'allowed_formats' => array(
          'full_html' => 'full_html',
          'plain_text' => 'plain_text',
        ),
        'allowed_formats_toggle' => 0,
        'default_order_toggle' => 0,
        'default_order_wrapper' => array(
          'formats' => array(
            'full_html' => array(
              'weight' => -10,
            ),
            'plain_text' => array(
              'weight' => 10,
            ),
          ),
        ),
      ),
      'field_instance_sync' => 0,
      'text_processing' => 0,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'text',
      'settings' => array(
        'size' => 60,
      ),
      'type' => 'text_textfield',
      'weight' => 0,
    ),
  );

  // Exported field_instance: 'paddle_quiz-paddle_quiz-field_paddle_quiz_start_button'
  $field_instances['paddle_quiz-paddle_quiz-field_paddle_quiz_start_button'] = array(
    'bundle' => 'paddle_quiz',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'text',
        'settings' => array(),
        'type' => 'text_default',
        'weight' => 3,
      ),
    ),
    'entity_type' => 'paddle_quiz',
    'field_name' => 'field_paddle_quiz_start_button',
    'label' => 'Start button label',
    'required' => 1,
    'settings' => array(
      'better_formats' => array(
        'allowed_formats' => array(
          'full_html' => 'full_html',
          'plain_text' => 'plain_text',
        ),
        'allowed_formats_toggle' => 0,
        'default_order_toggle' => 0,
        'default_order_wrapper' => array(
          'formats' => array(
            'full_html' => array(
              'weight' => -10,
            ),
            'plain_text' => array(
              'weight' => 10,
            ),
          ),
        ),
      ),
      'field_instance_sync' => 0,
      'text_processing' => 0,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'text',
      'settings' => array(
        'size' => 60,
      ),
      'type' => 'text_textfield',
      'weight' => 3,
    ),
  );

  // Exported field_instance: 'paddle_quiz-paddle_quiz-field_paddle_quiz_start_image'
  $field_instances['paddle_quiz-paddle_quiz-field_paddle_quiz_start_image'] = array(
    'bundle' => 'paddle_quiz',
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'paddle_scald_atom_field',
        'settings' => array(),
        'type' => 'paddle_scald_atom_image',
        'weight' => 18,
      ),
    ),
    'entity_type' => 'paddle_quiz',
    'field_name' => 'field_paddle_quiz_start_image',
    'label' => 'Image',
    'required' => 0,
    'settings' => array(
      'field_instance_sync' => 0,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'paddle_scald_atom_field',
      'settings' => array(
        'allowed_types' => array(
          'file' => 0,
          'image' => 'image',
          'video' => 0,
        ),
      ),
      'type' => 'paddle_scald_atom_image',
      'weight' => 0,
    ),
  );

  // Exported field_instance: 'paddle_quiz-paddle_quiz-field_paddle_quiz_start_message'
  $field_instances['paddle_quiz-paddle_quiz-field_paddle_quiz_start_message'] = array(
    'bundle' => 'paddle_quiz',
    'default_value' => array(
      0 => array(
        'value' => '',
        'format' => 'full_html',
      ),
    ),
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'text',
        'settings' => array(),
        'type' => 'text_default',
        'weight' => 2,
      ),
    ),
    'entity_type' => 'paddle_quiz',
    'field_name' => 'field_paddle_quiz_start_message',
    'label' => 'Welcome message',
    'required' => 0,
    'settings' => array(
      'better_formats' => array(
        'allowed_formats' => array(
          'full_html' => 'full_html',
          'plain_text' => 0,
        ),
        'allowed_formats_toggle' => 1,
        'default_order_toggle' => 0,
        'default_order_wrapper' => array(
          'formats' => array(
            'full_html' => array(
              'weight' => -10,
            ),
            'plain_text' => array(
              'weight' => 10,
            ),
          ),
        ),
      ),
      'field_instance_sync' => 0,
      'text_processing' => 1,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'text',
      'settings' => array(
        'rows' => 8,
      ),
      'type' => 'text_textarea',
      'weight' => 2,
    ),
  );

  // Exported field_instance: 'paddle_quiz-paddle_quiz-field_paddle_quiz_start_title'
  $field_instances['paddle_quiz-paddle_quiz-field_paddle_quiz_start_title'] = array(
    'bundle' => 'paddle_quiz',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => 'Leave empty to use the quiz\'s title.',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'text',
        'settings' => array(),
        'type' => 'text_default',
        'weight' => 8,
      ),
    ),
    'entity_type' => 'paddle_quiz',
    'field_name' => 'field_paddle_quiz_start_title',
    'label' => 'Title',
    'required' => 0,
    'settings' => array(
      'better_formats' => array(
        'allowed_formats' => array(
          'full_html' => 'full_html',
          'plain_text' => 'plain_text',
        ),
        'allowed_formats_toggle' => 0,
        'default_order_toggle' => 0,
        'default_order_wrapper' => array(
          'formats' => array(
            'full_html' => array(
              'weight' => -10,
            ),
            'plain_text' => array(
              'weight' => 10,
            ),
          ),
        ),
      ),
      'field_instance_sync' => 0,
      'text_processing' => 0,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'text',
      'settings' => array(
        'size' => 60,
      ),
      'type' => 'text_textfield',
      'weight' => 0,
    ),
  );

  // Exported field_instance: 'paddle_quiz-paddle_quiz-field_paddle_quiz_subtitle'
  $field_instances['paddle_quiz-paddle_quiz-field_paddle_quiz_subtitle'] = array(
    'bundle' => 'paddle_quiz',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'text',
        'settings' => array(),
        'type' => 'text_default',
        'weight' => 1,
      ),
    ),
    'entity_type' => 'paddle_quiz',
    'field_name' => 'field_paddle_quiz_subtitle',
    'label' => 'Subtitle',
    'required' => 0,
    'settings' => array(
      'better_formats' => array(
        'allowed_formats' => array(
          'full_html' => 'full_html',
          'plain_text' => 'plain_text',
        ),
        'allowed_formats_toggle' => 0,
        'default_order_toggle' => 0,
        'default_order_wrapper' => array(
          'formats' => array(
            'full_html' => array(
              'weight' => -10,
            ),
            'plain_text' => array(
              'weight' => 10,
            ),
          ),
        ),
      ),
      'field_instance_sync' => 0,
      'text_processing' => 0,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'text',
      'settings' => array(
        'size' => 60,
      ),
      'type' => 'text_textfield',
      'weight' => 1,
    ),
  );

  // Exported field_instance: 'paddle_quiz-paddle_quiz-field_paddle_quiz_tiebreaker'
  $field_instances['paddle_quiz-paddle_quiz-field_paddle_quiz_tiebreaker'] = array(
    'bundle' => 'paddle_quiz',
    'default_value' => array(
      0 => array(
        'value' => 0,
      ),
    ),
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'list',
        'settings' => array(),
        'type' => 'list_default',
        'weight' => 4,
      ),
    ),
    'entity_type' => 'paddle_quiz',
    'field_name' => 'field_paddle_quiz_tiebreaker',
    'label' => 'Add tiebreaker question',
    'required' => 0,
    'settings' => array(
      'field_instance_sync' => 0,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'options',
      'settings' => array(
        'display_label' => 1,
      ),
      'type' => 'options_onoff',
      'weight' => 0,
    ),
  );

  // Exported field_instance: 'paddle_quiz-paddle_quiz-field_paddle_quiz_tiebreaker_l'
  $field_instances['paddle_quiz-paddle_quiz-field_paddle_quiz_tiebreaker_l'] = array(
    'bundle' => 'paddle_quiz',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => 'This text will appear right next to the input field for the tiebreaker question.',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'text',
        'settings' => array(),
        'type' => 'text_default',
        'weight' => 6,
      ),
    ),
    'entity_type' => 'paddle_quiz',
    'field_name' => 'field_paddle_quiz_tiebreaker_l',
    'label' => 'Tiebreaker label',
    'required' => 0,
    'settings' => array(
      'better_formats' => array(
        'allowed_formats' => array(
          'full_html' => 'full_html',
          'plain_text' => 'plain_text',
        ),
        'allowed_formats_toggle' => 0,
        'default_order_toggle' => 0,
        'default_order_wrapper' => array(
          'formats' => array(
            'full_html' => array(
              'weight' => -10,
            ),
            'plain_text' => array(
              'weight' => 10,
            ),
          ),
        ),
      ),
      'field_instance_sync' => 0,
      'text_processing' => 0,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'text',
      'settings' => array(
        'size' => 60,
      ),
      'type' => 'text_textfield',
      'weight' => 2,
    ),
  );

  // Exported field_instance: 'paddle_quiz-paddle_quiz-field_paddle_quiz_tiebreaker_q'
  $field_instances['paddle_quiz-paddle_quiz-field_paddle_quiz_tiebreaker_q'] = array(
    'bundle' => 'paddle_quiz',
    'default_value' => array(
      0 => array(
        'value' => '',
        'format' => 'full_html',
      ),
    ),
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'text',
        'settings' => array(),
        'type' => 'text_default',
        'weight' => 5,
      ),
    ),
    'entity_type' => 'paddle_quiz',
    'field_name' => 'field_paddle_quiz_tiebreaker_q',
    'label' => 'Tiebreaker question',
    'required' => 0,
    'settings' => array(
      'better_formats' => array(
        'allowed_formats' => array(
          'full_html' => 'full_html',
          'plain_text' => 0,
        ),
        'allowed_formats_toggle' => 1,
        'default_order_toggle' => 0,
        'default_order_wrapper' => array(
          'formats' => array(
            'full_html' => array(
              'weight' => -10,
            ),
            'plain_text' => array(
              'weight' => 10,
            ),
          ),
        ),
      ),
      'field_instance_sync' => 0,
      'text_processing' => 1,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'text',
      'settings' => array(
        'rows' => 5,
      ),
      'type' => 'text_textarea',
      'weight' => 1,
    ),
  );

  // Translatables
  // Included for use with string extractors like potx.
  t('"Next" button label');
  t('"Previous" button label');
  t('Add tiebreaker question');
  t('Answers');
  t('Button label');
  t('Correct answer');
  t('Custom CSS');
  t('Disclaimer');
  t('Image');
  t('Info required');
  t('Leave empty to use the quiz\'s title.');
  t('Location');
  t('Message');
  t('Question');
  t('Questions');
  t('Quiz');
  t('Start button label');
  t('Subtitle');
  t('This text will appear right next to the input field for the tiebreaker question.');
  t('Tiebreaker label');
  t('Tiebreaker question');
  t('Title');
  t('User information');
  t('Welcome message');

  return $field_instances;
}
