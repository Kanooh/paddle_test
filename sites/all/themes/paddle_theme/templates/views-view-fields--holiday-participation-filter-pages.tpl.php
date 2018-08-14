<?php

/**
 * @file
 * Default simple view template to all the fields as a row.
 *
 * - $view: The view in use.
 * - $fields: an array of $field objects. Each one contains:
 *   - $field->content: The output of the field.
 *   - $field->raw: The raw data for the field, if it exists. This is NOT output safe.
 *   - $field->class: The safe class id to use.
 *   - $field->handler: The Views field handler object controlling this field. Do not use
 *     var_export to dump this object, as it can't handle the recursion.
 *   - $field->inline: Whether or not the field should be inline.
 *   - $field->inline_html: either div or span based on the above flag.
 *   - $field->wrapper_prefix: A complete wrapper containing the inline_html to use.
 *   - $field->wrapper_suffix: The closing tag for the wrapper.
 *   - $field->separator: an optional separator that may appear before a field.
 *   - $field->label: The wrap label text to use.
 *   - $field->label_html: The full HTML of the label to use including
 *     configured element type.
 * - $row: The raw result object from the query, with all data it fetched.
 *
 * @ingroup views_templates
 */
?>
<div class="col-md-5">
  <div class="views-field-<?php print $fields['field_hp_province']->class ?>">
    <div class="view-field-hp-left-wrapper">
      <?php if (!empty($fields['field_hp_province'])): ?>
        <div class="province-name">
          <?php print $fields['field_hp_province']->content; ?>
        </div>
      <?php endif; ?>
      <?php if (!empty($fields['field_hp_facilities'])): ?>
        <?php print $fields['field_hp_facilities']->content; ?>
      <?php endif; ?>
    </div>
    <div class="view-field-hp-right-wrapper">
      <?php if (!empty($fields['field_paddle_featured_image'])): ?>
        <?php print $fields['field_paddle_featured_image']->content; ?>
      <?php endif; ?>
    </div>
  </div>
</div>
<div class="col-md-7 views-field-item-wrapper">
  <div class="views-field-title">
    <?php if (!empty($fields['title'])): ?>
      <?php print $fields['title']->content; ?>
    <?php endif; ?>

  </div>
  <div class="views-field-body">
    <?php if (!empty($fields['body'])): ?>
      <?php print $fields['body']->content; ?>
    <?php endif; ?>
  </div>
</div>
