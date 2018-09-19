<?php

/**
 * @file
 * Default theme implementation to display a region.
 *
 * Available variables:
 * - $content: The content for this region, typically blocks.
 * - $classes: String of classes that can be used to style contextually through
 *   CSS. It can be manipulated through the variable $classes_array from
 *   preprocess functions. The default values can be one or more of the following:
 *   - region: The current template type, i.e., "theming hook".
 *   - region-[name]: The name of the region with underscores replaced with
 *     dashes. For example, the page_top region would have a region-page-top class.
 * - $region: The name of the region variable as defined in the theme's .info file.
 *
 * Helper variables:
 * - $classes_array: Array of html class attribute values. It is flattened
 *   into a string within the variable $classes.
 * - $is_admin: Flags true when the current user is an administrator.
 * - $is_front: Flags true when presented in the front page.
 * - $logged_in: Flags true when the current user is a logged-in member.
 *
 * @see template_preprocess()
 * @see template_preprocess_region()
 * @see template_process()
 *
 * @ingroup themeable
 */
?>
<?php if ($content): ?>
  <?php if (variable_get('paddle_style_search_placeholder_popup_checkbox')): ?>
    <div class="<?php print $classes; ?>">
    <?php if (variable_get('paddle_style_show_search_box')): ?>
      <a href="#" class="search-pop-up">
        <i class="fa fa-search"></i>
      </a>
      <div id="search-box-holder" class="visuallyhidden"><?php print $variables['elements']['search_api_page_search']['#children']; ?></div>
    <?php endif ?>
  <?php if (!empty($variables['elements']['paddle_menu_display_top_menu'])) : ?>
    <?php print $variables['elements']['paddle_menu_display_top_menu']['#children']; ?>
  <?php endif; ?>
      <?php if (!empty($variables['elements']['locale_language'])) : ?>
        <?php print render($variables['elements']['locale_language']); ?>
      <?php endif; ?>
  </div>
<?php else: ?>
  <div class="<?php print $classes; ?>">
    <?php print render($content); ?>
  </div>
<?php endif; ?>
<?php endif; ?>
