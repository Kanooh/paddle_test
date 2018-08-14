<?php

/**
 * @file
 * Template of the materials pane of the Cirro content type.
 */
?>

<div class="pane-materials">
  <?php if (!empty($children)) : ?>
      <div class="pane-cirro-children">
        <?php print t("Included assistance tools in this methodology:"); ?>
          <ul class="listing">
            <?php foreach ($children as $child): ?>
                <li class="pane-cirro-child">
                  <?php print $child ?>
                </li>
            <?php endforeach; ?>
          </ul>
      </div>
  <?php endif; ?>
  <?php if (!empty($links_list)) : ?>
      <div class="links-section">
        <?php print t("Get the included materials/services from the website:"); ?>
          <ul class="listing">
            <?php foreach ($links_list as $delta => $link_item): ?>
              <?php if (!empty($link_item['url'])) : ?>
                    <li>
                        <a href="<?php print $link_item['url']; ?>"> <?php print ($link_item['title'] ? $link_item['title'] : $link_item['url']); ?></a>
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
  <?php if (!empty($files_list)) : ?>
      <div class="files-section">
          <p><?php print t("Download the included material here:"); ?></p>
          <p><?php print render($files_list); ?></p>
      </div>
      <div class="materials-disclaimer">
          <p><?php print t("Some materials and services are not freely available. It is recommended to order them at the contact person of the material/service itself. (See Contact information)"); ?></p>
      </div>
  <?php endif; ?>
</div>
