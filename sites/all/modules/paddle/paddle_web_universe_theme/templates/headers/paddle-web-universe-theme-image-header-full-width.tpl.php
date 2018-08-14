<?php

/**
 * @file
 * Template of the full width image header of the web universe theme.
 */
?>

<header class="content-header content-header--large u-spacer--large">
  <div class="content-header__wrapper">
    <?php if (!empty($header_image)): ?>
      <picture class="content-header__bg">
        <img src="<?php print $header_image; ?>">
      </picture>
    <?php endif; ?>
    <div class="layout layout--wide">
      <div class="content-header__content">
        <div class="content-header__logo-wrapper">
          <h1 class="content-header__entity-logo content-header__entity-logo">
            <?php if (!empty($header_title_prefix)): ?>
              <span
                class="content-header__entity-logo__prefix"><?php print $header_title_prefix; ?></span>
            <?php endif; ?>
            <?php if (!empty($header_title)): ?>
              <span
                class="content-header__entity-logo__title"><?php print $header_title; ?></span>
            <?php endif; ?>
          </h1>
        </div>
      </div>
    </div>
  </div>
</header>
