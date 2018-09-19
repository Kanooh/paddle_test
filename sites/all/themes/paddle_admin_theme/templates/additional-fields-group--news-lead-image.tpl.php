<?php
/**
 * @file
 * Template file for groups of additional fields.
 *
 * Variables available:
 * - $css_class: CSS classes to put on the wrapper.
 * - $title: Group label.
 * - $name: Group name.
 * - $content: The actual content.
 *
 * @see paddle_content_manager_additional_fields_content_type_render()
 *
 * Changes:
 * - Puts the title outside the container.
 * - Puts extra CSS classes on the container.
 */
?>
<?php if ($title): ?>
  <h2><?php print $title; ?></h2>
<?php endif; ?>
<div class="<?php print $css_class; ?> row light-gray-background">
  <?php print render($content); ?>
</div>
