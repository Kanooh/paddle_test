<?php

/**
 * @file
 * CTools content type plugin to embed a top news on the news overview page.
 */

$plugin = array(
  'title' => t('Top news'),
  'no title override' => TRUE,
  'description' => t('Top news.'),
  'single' => TRUE,
  'defaults' => array(),
  'category' => array(t('Miscellaneous'), -10),
  'edit form' => 'top_news_content_type_edit_form',
  'render callback' => 'top_news_content_type_render',
  'required context' => new ctools_context_required(t('Node'), 'node'),
);

/**
 * Render the content type.
 */
function top_news_content_type_render($subtype, $conf, $args, $context) {
  // Return the output as a block.
  $block = new stdClass();
  $block->title = '';
  $block->content = '';

  // Use the "News overview" view to get the latest news item.
  $news_overview_view = views_get_view('news_overview');
  $news_overview_view->set_display('panel_pane_1');
  $news_overview_view->set_items_per_page(1);
  $news_overview_view->set_offset(0);
  $news_overview_view->pre_execute();
  $news_overview_view->execute();

  if (!empty($news_overview_view->result[0]->{'_field_data'}['nid']['entity'])) {
    $node = $news_overview_view->result[0]->{'_field_data'}['nid']['entity'];

    $rendered = array();
    // Prepare the image.
    if (!empty($node->field_paddle_featured_image[LANGUAGE_NONE][0]['sid'])) {
      $atom = scald_atom_load($node->field_paddle_featured_image[LANGUAGE_NONE][0]['sid']);
      $vars = array(
        'style_name' => 'paddle_news_top_news',
        'path' => $atom->file_source,
        'alt' => $atom->field_scald_alt_tag[LANGUAGE_NONE][0]['value'],
        'width' => '',
        'height' => '',
        'attributes' => array('class' => 'featured-image'),
      );
      $rendered['image'] = l(theme_image_style($vars), 'node/' . $node->nid, array('html' => TRUE));
    }

    // Get the other fields.
    $rendered['title'] = l($news_overview_view->result[0]->node_title, 'node/' . $node->nid);
    $rendered['body'] = '';
    if (!empty($news_overview_view->result[0]->field_body[0]['rendered']['#markup'])) {
      $rendered['body'] = strip_tags($news_overview_view->result[0]->field_body[0]['rendered']['#markup']);
    }
    $rendered['url'] = url('node/' . $node->nid);

    $block->content = theme('paddle_news_top_news', array(
      'rendered' => $rendered,
      'date' => $node->created,
    ));

    // Add some semantic information to the pane's CSS classes to aid in
    // testing.
    $block->css_class = 'news-item';
    $block->attributes = array('data-news-item-nid' => $node->nid);
  }

  return $block;
}

/**
 * Edit form.
 */
function top_news_content_type_edit_form($form, &$form_state) {
  return $form;
}
