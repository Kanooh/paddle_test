<?php
/**
 * @file
 * paddle_scald.features.field_instance.inc
 */

/**
 * Implements hook_field_default_field_instances().
 */
function paddle_scald_field_default_field_instances() {
  $field_instances = array();

  // Exported field_instance: 'scald_atom-file-field_paddle_general_tags'.
  $field_instances['scald_atom-file-field_paddle_general_tags'] = array(
    'bundle' => 'file',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'i18n_taxonomy',
        'settings' => array(),
        'type' => 'i18n_taxonomy_term_reference_link',
        'weight' => 2,
      ),
    ),
    'entity_type' => 'scald_atom',
    'field_name' => 'field_paddle_general_tags',
    'label' => 'General tags',
    'required' => 0,
    'settings' => array(
      'field_instance_sync' => 0,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'term_reference_tree',
      'settings' => array(
        'cascading_selection' => 1,
        'filter_view' => '',
        'leaves_only' => 0,
        'max_depth' => '',
        'parent_term_id' => '',
        'select_parents' => 1,
        'start_minimized' => 1,
        'token_display' => '',
        'track_list' => 1,
      ),
      'type' => 'term_reference_tree',
      'weight' => -2,
    ),
  );

  // Exported field_instance:
  // 'scald_atom-file-field_paddle_scald_atom_end_date'.
  $field_instances['scald_atom-file-field_paddle_scald_atom_end_date'] = array(
    'bundle' => 'file',
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'date',
        'settings' => array(
          'format_type' => 'long',
          'fromto' => 'both',
          'multiple_from' => '',
          'multiple_number' => '',
          'multiple_to' => '',
          'show_remaining_days' => FALSE,
        ),
        'type' => 'date_default',
        'weight' => 5,
      ),
    ),
    'entity_type' => 'scald_atom',
    'field_name' => 'field_paddle_scald_atom_end_date',
    'label' => 'Set Removal Date',
    'required' => 0,
    'settings' => array(
      'default_value' => 'blank',
      'default_value2' => 'same',
      'default_value_code' => '',
      'default_value_code2' => '',
      'field_instance_sync' => 0,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'date',
      'settings' => array(
        'increment' => 1,
        'input_format' => 'd/m/Y - H:i:s',
        'input_format_custom' => '',
        'label_position' => 'above',
        'no_fieldset' => 0,
        'text_parts' => array(),
        'year_range' => '-0:+3',
      ),
      'type' => 'date_popup',
      'weight' => 0,
    ),
  );

  // Exported field_instance: 'scald_atom-file-field_paddle_tags'.
  $field_instances['scald_atom-file-field_paddle_tags'] = array(
    'bundle' => 'file',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'i18n_taxonomy',
        'settings' => array(),
        'type' => 'i18n_taxonomy_term_reference_link',
        'weight' => 1,
      ),
    ),
    'entity_type' => 'scald_atom',
    'field_name' => 'field_paddle_tags',
    'label' => 'Tags',
    'required' => 0,
    'settings' => array(
      'field_instance_sync' => 0,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'active_tags',
      'settings' => array(
        'autocomplete_path' => 'taxonomy/autocomplete',
        'mode' => 'single',
        'size' => 60,
      ),
      'type' => 'active_tags_taxonomy_autocomplete',
      'weight' => -3,
    ),
  );

  // Exported field_instance: 'scald_atom-file-field_scald_set_removal_date'.
  $field_instances['scald_atom-file-field_scald_set_removal_date'] = array(
    'bundle' => 'file',
    'default_value' => array(
      0 => array(
        'value' => 0,
      ),
    ),
    'deleted' => 0,
    'description' => 'Check the file removal dates and delete the file when passed.',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'list',
        'settings' => array(),
        'type' => 'list_default',
        'weight' => 4,
      ),
    ),
    'entity_type' => 'scald_atom',
    'field_name' => 'field_scald_set_removal_date',
    'label' => 'Set removal date',
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
      'weight' => -1,
    ),
  );

  // Exported field_instance: 'scald_atom-image-field_paddle_general_tags'.
  $field_instances['scald_atom-image-field_paddle_general_tags'] = array(
    'bundle' => 'image',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'i18n_taxonomy',
        'settings' => array(),
        'type' => 'i18n_taxonomy_term_reference_link',
        'weight' => 14,
      ),
    ),
    'entity_type' => 'scald_atom',
    'field_name' => 'field_paddle_general_tags',
    'label' => 'General tags',
    'required' => 0,
    'settings' => array(
      'field_instance_sync' => 0,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'term_reference_tree',
      'settings' => array(
        'cascading_selection' => 1,
        'filter_view' => '',
        'leaves_only' => 0,
        'max_depth' => '',
        'parent_term_id' => '',
        'select_parents' => 1,
        'start_minimized' => 1,
        'token_display' => '',
        'track_list' => 1,
      ),
      'type' => 'term_reference_tree',
      'weight' => 10,
    ),
  );

  // Exported field_instance: 'scald_atom-image-field_paddle_tags'.
  $field_instances['scald_atom-image-field_paddle_tags'] = array(
    'bundle' => 'image',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'i18n_taxonomy',
        'settings' => array(),
        'type' => 'i18n_taxonomy_term_reference_link',
        'weight' => 13,
      ),
    ),
    'entity_type' => 'scald_atom',
    'field_name' => 'field_paddle_tags',
    'label' => 'Tags',
    'required' => 0,
    'settings' => array(
      'field_instance_sync' => 0,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'active_tags',
      'settings' => array(
        'autocomplete_path' => 'taxonomy/autocomplete',
        'mode' => 'single',
        'size' => 60,
      ),
      'type' => 'active_tags_taxonomy_autocomplete',
      'weight' => 8,
    ),
  );

  // Exported field_instance: 'scald_atom-image-field_scald_alt_tag'.
  $field_instances['scald_atom-image-field_scald_alt_tag'] = array(
    'bundle' => 'image',
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
    'entity_type' => 'scald_atom',
    'field_name' => 'field_scald_alt_tag',
    'label' => 'Alternative text',
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
      'weight' => -3,
    ),
  );

  // Exported field_instance: 'scald_atom-image-field_scald_caption'.
  $field_instances['scald_atom-image-field_scald_caption'] = array(
    'bundle' => 'image',
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
    'entity_type' => 'scald_atom',
    'field_name' => 'field_scald_caption',
    'label' => 'Caption',
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
      'weight' => -1,
    ),
  );

  // Exported field_instance: 'scald_atom-image-field_scald_description'.
  $field_instances['scald_atom-image-field_scald_description'] = array(
    'bundle' => 'image',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'text',
        'settings' => array(),
        'type' => 'text_default',
        'weight' => 6,
      ),
    ),
    'entity_type' => 'scald_atom',
    'field_name' => 'field_scald_description',
    'label' => 'Description',
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
      'text_processing' => 0,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'text',
      'settings' => array(
        'rows' => 5,
      ),
      'type' => 'text_textarea',
      'weight' => -2,
    ),
  );

  // Exported field_instance: 'scald_atom-image-scald_thumbnail'.
  $field_instances['scald_atom-image-scald_thumbnail'] = array(
    'bundle' => 'image',
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 12,
      ),
    ),
    'entity_type' => 'scald_atom',
    'field_name' => 'scald_thumbnail',
    'label' => 'Image',
    'required' => 1,
    'settings' => array(
      'alt_field' => 0,
      'default_image' => 0,
      'field_instance_sync' => 0,
      'file_directory' => 'thumbnails/image',
      'file_extensions' => 'png gif jpg jpeg',
      'max_filesize' => '',
      'max_resolution' => '',
      'min_resolution' => '',
      'title_field' => 0,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'image',
      'settings' => array(
        'manualcrop_crop_info' => 1,
        'manualcrop_default_crop_area' => 1,
        'manualcrop_enable' => 1,
        'manualcrop_inline_crop' => 0,
        'manualcrop_instant_crop' => 0,
        'manualcrop_instant_preview' => 1,
        'manualcrop_keyboard' => 1,
        'manualcrop_maximize_default_crop_area' => 1,
        'manualcrop_require_cropping' => array(),
        'manualcrop_styles_list' => array(
          '2_3' => '2_3',
          '3_2' => '3_2',
          '3_1' => '3_1',
          '3_4' => '3_4',
          '4_3' => '4_3',
          '16_9' => '16_9',
          'square' => 'square',
        ),
        'manualcrop_styles_mode' => 'include',
        'manualcrop_thumblist' => 0,
        'preview_image_style' => 'thumbnail',
        'progress_indicator' => 'throbber',
      ),
      'type' => 'image_image',
      'weight' => 6,
    ),
  );

  // Exported field_instance: 'scald_atom-video-field_paddle_general_tags'.
  $field_instances['scald_atom-video-field_paddle_general_tags'] = array(
    'bundle' => 'video',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'i18n_taxonomy',
        'settings' => array(),
        'type' => 'i18n_taxonomy_term_reference_link',
        'weight' => 3,
      ),
    ),
    'entity_type' => 'scald_atom',
    'field_name' => 'field_paddle_general_tags',
    'label' => 'General tags',
    'required' => 0,
    'settings' => array(
      'field_instance_sync' => 0,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'term_reference_tree',
      'settings' => array(
        'cascading_selection' => 1,
        'filter_view' => '',
        'leaves_only' => 0,
        'max_depth' => '',
        'parent_term_id' => '',
        'select_parents' => 1,
        'start_minimized' => 1,
        'token_display' => '',
        'track_list' => 1,
      ),
      'type' => 'term_reference_tree',
      'weight' => 5,
    ),
  );

  // Exported field_instance: 'scald_atom-video-field_paddle_tags'.
  $field_instances['scald_atom-video-field_paddle_tags'] = array(
    'bundle' => 'video',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'i18n_taxonomy',
        'settings' => array(),
        'type' => 'i18n_taxonomy_term_reference_link',
        'weight' => 2,
      ),
    ),
    'entity_type' => 'scald_atom',
    'field_name' => 'field_paddle_tags',
    'label' => 'Tags',
    'required' => 0,
    'settings' => array(
      'field_instance_sync' => 0,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'active_tags',
      'settings' => array(
        'autocomplete_path' => 'taxonomy/autocomplete',
        'mode' => 'single',
        'size' => 60,
      ),
      'type' => 'active_tags_taxonomy_autocomplete',
      'weight' => 3,
    ),
  );

  // Translatables
  // Included for use with string extractors like potx.
  t('Alternative text');
  t('Caption');
  t('Check the file removal dates and delete the file when passed.');
  t('Description');
  t('General tags');
  t('Image');
  t('Set Removal Date');
  t('Tags');

  return $field_instances;
}
