<?php
/**
 * @file
 * CTools content type plugin to embed a publication featured image.
 */

$plugin = array(
  'title' => t('publication lead image'),
  'description' => t('Renders the lead image field for a publication page.'),
  'category' => t('Paddle publication'),
  'required context' => new ctools_context_required(t('Node'), 'node'),
  'render first' => TRUE,
);

/**
 * Render callback.
 */
function paddle_publication_publication_lead_image_content_type_render($subtype, $conf, $args, $context) {
  $block = new stdClass();
  $block->title = '';
  $block->content = '';

  if (isset($conf['paddle_scald_image'])) {
    $atom = scald_atom_load($conf['paddle_scald_image']['sid'][0]);

    if ($atom) {
      $image_style = isset($conf['paddle_scald_image']['style']) ? $conf['paddle_scald_image']['style'] : 'medium';
      $variables = array(
        'atom' => $atom,
        'image_style' => $image_style,
      );
      $block->content = theme('paddle_scald_render_atom', $variables);
    }
  }

  return $block;
}
