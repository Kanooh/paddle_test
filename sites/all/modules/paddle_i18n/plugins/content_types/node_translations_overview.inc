<?php

/**
 * @file
 * Plugin to provide a content type for the paddle content manager node summary
 * metadata.
 */

/**
 * Plugin definition.
 */
$plugin = array(
  'title' => t('Node Translations Overview'),
  'description' => t('Renders an overview of the node translations.'),
  'category' => t('Paddle i18n'),
  'required context' => new ctools_context_required(t('Node'), 'node'),
  'defaults' => array(
    'extended' => FALSE,
    'collapsible' => FALSE,
    'horizontal' => FALSE,
  ),
);

/**
 * Render callback.
 */
function paddle_i18n_node_translations_overview_content_type_render($subtype, $conf, $args, $context) {
  ctools_include('modal');
  ctools_modal_add_js();

  $block = new stdClass();
  $block->title = t('Translations');

  $node = $context->data;
  $languages = language_list();
  $tnid = !empty($node->tnid) ? $node->tnid : $node->nid;
  $translations = translation_node_get_translations($tnid);

  $rows = array();
  foreach ($languages as $code => $language) {
    if ($language->language != $node->language && $language->enabled) {
      $translation = !empty($translations[$code]) ? $translations[$code] : array();
      $language = $languages[$code];
      $node_title = $translation ? l($translation->title, "node/{$translation->nid}", array('language' => $language)) : t('Not translated');

      if ($translation) {
        $translation_link = l(t('edit'), "node/{$translation->nid}/edit");
      }
      else {
        $translation_link = l(
          t('add translation'),
          "admin/content_manager/nojs/{$node->type}/add",
          array(
            'attributes' => array(
              'class' => array(
                'ctools-use-modal',
                'create-' . $node->type,
              ),
            ),
            'query' => array('translation' => $node->nid, 'target' => $language->language),
          )
        );
      }
      $rows[] = array(
        'data' => array(
          array('data' => $language->name, 'class' => 'language-name'),
          array('data' => $node_title, 'class' => 'node-link'),
          array('data' => $translation_link, 'class' => 'translation-link'),
        ),
        'data-language' => $code,
      );
    }
  }

  $block->content = theme('table', array('header' => array(), 'rows' => $rows));

  return $block;
}

/**
 * Settings form builder callback.
 */
function paddle_i18n_node_translations_overview_content_type_edit_form($form, &$form_state) {
  return $form;
}

/**
 * Displays the administrative title for a panel pane in the drag & drop UI.
 */
function paddle_i18n_node_translations_overview_content_type_admin_title($subtype, $conf, $context) {
  return t('Node Translations Overview');
}
