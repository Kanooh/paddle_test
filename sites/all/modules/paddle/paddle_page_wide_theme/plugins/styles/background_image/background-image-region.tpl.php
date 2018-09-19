<?php

/**
 * @file
 * Theme implementation to display a pagewide region with a BG image.
 */
?>

<div class="page-wide-container background-image region <?php print $isTransparent; ?>" <?php print drupal_attributes($style_attributes);?>>
  <div class="<?php print $hasPaddingClass; ?> content-wrapper" <?php print drupal_attributes($padding); ?>>
    <?php print $content; ?>
  </div>
</div>
