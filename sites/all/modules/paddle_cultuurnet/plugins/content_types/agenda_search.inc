<?php

$plugin = array(
  'title' => t('Cultuurnet: Agenda search'),
  'no title override' => TRUE,
  'description' => t('Add a Cultuurnet agenda search form.'),
  'category' => t('Paddle Content Manager'),
  'paddle_panes sections' => TRUE,
);

/**
 * Render callback for the agenda search pane.
 */
function paddle_cultuurnet_agenda_search_content_type_render($subtype, $conf, $args, $context) {
  $block = new stdClass();

  $block->content = _block_get_renderable_array(_block_render_blocks(array(block_load('culturefeed_agenda', 'agenda-search-block'))));

  return $block;
}
