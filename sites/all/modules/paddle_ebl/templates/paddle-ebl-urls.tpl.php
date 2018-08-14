<?php

/**
 * @file
 * Template of the external URLs pane of the EBL content type.
 */
?>

<?php if (!empty($links_list)) : ?>
  <div class="links-section">
    <ul class="listing">
      <?php foreach ($links_list as $delta => $link_item): ?>
        <?php if (!empty($link_item['value'])) : ?>
          <li>
            <a
              href="<?php print $link_item['value']; ?>"> <?php print ($link_item['title'] ? $link_item['title'] : $link_item['value']); ?></a>
            <?php if (isset($link_item['attributes']['title']) && !empty($link_item['attributes']['title'])) : ?>
              <br/><span
                class="link-description"><?php print $link_item['attributes']['title']; ?></span>
            <?php endif; ?>
          </li>
        <?php endif; ?>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>
