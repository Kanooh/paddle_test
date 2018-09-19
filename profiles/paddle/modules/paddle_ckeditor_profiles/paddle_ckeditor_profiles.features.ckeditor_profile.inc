<?php
/**
 * @file
 * paddle_ckeditor_profiles.features.ckeditor_profile.inc
 */

/**
 * Implements hook_ckeditor_profile_defaults().
 */
function paddle_ckeditor_profiles_ckeditor_profile_defaults() {
  $data = array(
    'Full_HTML' => array(
      'name' => 'Full_HTML',
      'settings' => array(
        'ss' => 2,
        'default' => 't',
        'show_toggle' => 'f',
        'uicolor' => 'default',
        'uicolor_user' => 'default',
        'toolbar' => '[
    [\'Maximize\'],
    [\'Source\'],
    [\'Scayt\',\'Undo\',\'Redo\'],
    \'/\',
    [\'Styles\'],
    [\'Bold\',\'Italic\',\'Underline\',\'Strike\',\'-\',\'Subscript\',\'Superscript\'],
    [\'BulletedList\',\'NumberedList\'],
    [\'Indent\',\'Outdent\'],
    [\'JustifyLeft\',\'JustifyCenter\',\'JustifyRight\',\'JustifyBlock\'],
    \'/\',
    [\'PasteText\'],
    [\'OpenScaldLibraryModal\',\'MediaEmbed\'],
    [\'HorizontalRule\'],
    [\'Table\'],
    [\'Anchor\',\'Blockquote\'],
    [\'Link\',\'Unlink\']
]',
        'expand' => 't',
        'width' => '100%',
        'lang' => 'en',
        'auto_lang' => 't',
        'language_direction' => 'default',
        'allowed_content' => 't',
        'extraAllowedContent' => '',
        'enter_mode' => 'p',
        'shift_enter_mode' => 'br',
        'font_format' => 'p;div;pre;address;h1;h2;h3;h4',
        'custom_formatting' => 'f',
        'formatting' => array(
          'custom_formatting_options' => array(
            'indent' => 'indent',
            'breakBeforeOpen' => 'breakBeforeOpen',
            'breakAfterOpen' => 'breakAfterOpen',
            'breakAfterClose' => 'breakAfterClose',
            'breakBeforeClose' => 0,
            'pre_indent' => 0,
          ),
        ),
        'css_mode' => 'theme',
        'css_path' => '',
        'css_style' => 'self',
        'styles_path' => '%hprofiles/paddle/modules/paddle_ckeditor_profiles/js/ckeditor.styles.js',
        'filebrowser' => 'none',
        'filebrowser_image' => '',
        'filebrowser_flash' => '',
        'UserFilesPath' => '%b%f/',
        'UserFilesAbsolutePath' => '%d%b%f/',
        'forcePasteAsPlainText' => 'f',
        'html_entities' => 'f',
        'scayt_autoStartup' => 'f',
        'theme_config_js' => 'f',
        'js_conf' => 'config.allowedContent = true; config.contentsCss = Drupal.settings.paddle_ckeditor_profiles.contentsCss; config.bodyClass = "region-content";config.scayt_sLang = \'nl_NL\';',
        'loadPlugins' => array(
          'ckeditor_link' => array(
            'name' => 'drupal_path',
            'desc' => 'CKEditor Link - A plugin to easily create links to Drupal internal paths',
            'path' => '%base_path%' . drupal_get_path('module', 'ckeditor_link') . '/plugins/link/',
            'buttons' => FALSE,
          ),
          'drupalbreaks' => array(
            'name' => 'drupalbreaks',
            'desc' => 'Plugin for inserting Drupal teaser and page breaks.',
            'path' => '%plugin_dir%drupalbreaks/',
            'buttons' => array(
              'DrupalBreak' => array(
                'label' => 'DrupalBreak',
                'icon' => 'images/drupalbreak.png',
              ),
            ),
            'default' => 't',
          ),
          'mediaembed' => array(
            'name' => 'mediaembed',
            'desc' => 'Plugin for inserting Drupal embeded media',
            'path' => '%plugin_dir%mediaembed/',
            'buttons' => array(
              'MediaEmbed' => array(
                'label' => 'MediaEmbed',
                'icon' => 'images/icon.png',
              ),
            ),
            'default' => 'f',
          ),
          'paddle_scald_atom' => array(
            'name' => 'paddle_scald_atom',
            'desc' => 'Paddle specific scald/ckeditor integration.',
            'path' => '%base_path%profiles/paddle/modules/paddle_scald/ckeditor/paddle_scald_atom/',
            'buttons' => array(
              'OpenScaldLibraryModal' => array(
                'label' => 'OpenScaldLibraryModal',
                'icon' => 'icons/icon.gif',
              ),
            ),
          ),
          'paddle_scald_image' => array(
            'name' => 'paddle_scald_image',
            'desc' => 'Paddle specific image options.',
            'path' => '%base_path%profiles/paddle/modules/paddle_scald/ckeditor/paddle_scald_image/',
            'buttons' => FALSE,
          ),
          'paddle_table_default_values' => array(
            'name' => 'paddle_table_default_values',
            'desc' => 'Paddle default table values.',
            'path' => '%base_path%profiles/paddle/modules/paddle_ckeditor_profiles/js/paddle_table_default_values/',
            'buttons' => FALSE,
          ),
          'paddle_table_additional_properties' => array(
            'name' => 'paddle_table_additional_properties',
            'desc' => 'Paddle additional table properties.',
            'path' => '%base_path%profiles/paddle/modules/paddle_ckeditor_profiles/js/paddle_table_additional_properties/',
            'buttons' => FALSE,
          ),
        ),
      ),
      'input_formats' => array(
        'full_html' => 'Full HTML',
      ),
    ),
  );
  return $data;
}
