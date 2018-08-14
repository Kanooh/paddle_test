<?php
/**
 * @file
 * Template that displays the spotlight version of a news item.
 */
?>
<a href="<?php print $output['link']; ?>">
  <div class="news-item-spotlight" data-news-item-nid="<?php print $nid; ?>">
    <div class="news-item-spotlight-image">
      <?php print $output['image']; ?>
    </div>
    <div class="news-item-content">
      <div class="news-item-header">
        <h5>
          <?php print $output['created']; ?>
        </h5>
        <h4>
          <?php print $output['title']; ?>
        </h4>
      </div>
      <p>
        <?php print $output['text']; ?>
      </p>
    </div>
  </div>
</a>
