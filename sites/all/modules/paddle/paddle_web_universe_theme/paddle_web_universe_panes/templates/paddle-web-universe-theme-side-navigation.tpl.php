<?php

/**
 * @file
 * Template of the side navigation component of the Web Universe.
 */
?>

<?php if (!empty($is_sticky)) : ?>
  <div class="js-sticky--placeholder">
    <nav class="side-navigation js-sticky js-sticky-bound">
      <div class="side-navigation__content">
        <?php print $skip_links; ?>
      </div>
    </nav>
  </div>
<?php else: ?>
  <nav class="side-navigation">
    <div class="side-navigation__content">
      <?php print $skip_links; ?>
    </div>
  </nav>
<?php endif; ?>
