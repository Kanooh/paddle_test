<?php

/**
 * @file
 * Template of the full width image header of the web universe theme.
 */
?>

<header class="content-header content-header--large u-spacer--large content-header--has-context content-header--has-title content-header--is-page-title">
  <div class="content-header__wrapper">
    <?php if (!empty($header_image)): ?>
      <picture class="content-header__bg">
        <img src="<?php print $header_image; ?>">
      </picture>
    <?php endif; ?>
    <div class="layout layout--wide">
      <div class="content-header__content">
        <?php if (!empty($level_1_item)): ?>
        <h2 class="content-header__context content-header__context--has-link"><a href="<?php print $level_1_item['url']; ?>" class="content-header__context__link"><?php print $level_1_item['text']; ?></a></h2>
        <?php endif; ?>
        <?php if (!empty($level_2_item)): ?>
        <h2 class="content-header__title content-header__title--has-link"><a href="<?php print $level_2_item['url']; ?>" class="content-header__title__link"><?php print $level_2_item['text']; ?></a></h2>
        <?php endif; ?>
        <?php if (!empty($header_title)): ?>
        <h1 class="content-header__title"><span class="content-header__title__content"><?php print $header_title; ?></span></h1>
        <?php endif; ?>
      </div>
    </div>
  </div>
</header>
