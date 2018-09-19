<?php
/**
 * @file
 * Template for top news item on the news overview View.
 */
$rendered = $variables['rendered'];
$has_image_class = !empty($rendered['image']) ? 'has-image' : 'no-image';
?>

<div class="<?php print $has_image_class; ?>">
  <div class="pane-section-top">
    <?php print theme('paddle_news_item_info', array('date' => $date)); ?>
  </div>
  <div class="pane-section-body">
    <?php
      if (!empty($rendered['image'])) :
        print '<div class="news-overview-item-image">';
        print $rendered['image'];
        print '</div>';
      endif;
    ?>
    <h3 class="news-overview-item-title"><?php print $rendered['title']; ?></h3>
    <div class="news-overview-item-body"><div class="field-content"><?php print $rendered['body']; ?></div></div>
    <div class="pane-section-bottom"><a href="<?php print $rendered['url'];?>" class="active news-overview-item-url"><?php print t('Read more'); ?><i class="fa fa-chevron-right"></i></a></div>
  </div>
</div>
