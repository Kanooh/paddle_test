<?php
/**
 * @file
 * Template for a single news item on the news overview View.
 *
 * - $view: The view in use.
 * - $fields: an array of $field objects. Each one contains:
 *   - $field->content: The output of the field.
 *   - $field->raw: The raw data for the field, if it exists. This is NOT
 *     output safe.
 *   - $field->class: The safe class id to use.
 *   - $field->handler: The Views field handler object controlling this field.
 *     Do not use var_export to dump this object, as it can't handle the
 *     recursion.
 *   - $field->inline: Whether or not the field should be inline.
 *   - $field->inline_html: either div or span based on the above flag.
 *   - $field->wrapper_prefix: A complete wrapper containing the inline_html to
 *     use.
 *   - $field->wrapper_suffix: The closing tag for the wrapper.
 *   - $field->separator: an optional separator that may appear before a field.
 *   - $field->label: The wrap label text to use.
 *   - $field->label_html: The full HTML of the label to use including
 *     configured element type.
 * - $row: The raw result object from the query, with all data it fetched.
 *
 * @ingroup views_templates
 */
$url = url('node/' . $row->nid);
?>
<div class="pane-content">
  <div class="pane-section-top">
    <?php
      $image_content = $fields['field_paddle_featured_image']->content;
      $has_image = strpos($image_content, '<img') !== FALSE;
      if ($has_image) :
        print '<div class="news-overview-item-image">';
        print $image_content;
        print '</div>';
      endif;

      // Print more info.
      print theme('paddle_news_item_info', array('date' => $fields['created']->raw));
    ?>
  </div>
  <div class="pane-section-body">
    <h3 class="news-overview-item-title"><?php print $fields['title']->content; ?></h3>
    <div class="news-overview-item-body"><?php print $fields['body']->content; ?></div>
  </div>
  <div class="pane-section-bottom">
    <a href="<?php print $url;?>" class="active news-overview-item-url"><?php print t('Read more');?><i class="fa fa-chevron-right"></i></a>
  </div>
</div>
