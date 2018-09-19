<?php
/**
 * @file
 * Template file for groups of additional fields.
 *
 * This template, with the default template file name, has to be in the theme
 * folder before it can be overridden.
 *
 * Variables available:
 * - $css_class: CSS classes to put on the wrapper.
 * - $title: Group label.
 * - $name: Group name.
 * - $content: The actual content.
 *
 * @see paddle_content_manager_additional_fields_content_type_render()
 */
?>
<div class="<?php print $css_class; ?>">
  <?php if ($title): ?>
    <h2><?php print $title; ?></h2>
  <?php endif; ?>

  <?php print render($content); ?>
</div>
