<?php

/**
 * @file
 * This template handles the layout of the views exposed filter form.
 *
 * Variables available:
 * - $widgets: An array of exposed form widgets. Each widget contains:
 * - $widget->label: The visible label to print. May be optional.
 * - $widget->operator: The operator for the widget. May be optional.
 * - $widget->widget: The widget itself.
 * - $sort_by: The select box to sort the view using an exposed form.
 * - $sort_order: The select box with the ASC, DESC options to define order. May be optional.
 * - $items_per_page: The select box with the available items per page. May be optional.
 * - $offset: A textfield to define the offset of the view. May be optional.
 * - $reset_button: A button to reset the exposed filter applied. May be optional.
 * - $button: The submit button for the form.
 *
 * @ingroup views_templates
 */
?>
<?php if (!empty($q)): ?>
  <?php // This ensures that, if clean URLs are off, the 'q' is added first so that // it shows up first in the URL. print $q; ?>
<?php endif; ?>
<div class="col-md-12">
  <?php if (!empty($widgets['filter-field_geofield_distance'])): ?>
    <div class="col-md-9">
      <p class="hp-filters-label"><?php print $widgets['filter-field_geofield_distance']->description ?></p>
      <?php print $widgets['filter-field_geofield_distance']->widget; ?>
    </div>
  <?php endif; ?>

  <?php if (!empty($widgets['filter-field_hp_contract_type_value'])): ?>
    <div class="col-md-3">
      <?php print $widgets['filter-field_hp_contract_type_value']->widget; ?>
    </div>
  <?php endif; ?>
</div>
<div class="col-md-12">
    <?php if (!empty($widgets['filter-title'])): ?>
      <div class="col-md-9">
        <p class="hp-filters-label by-title"><?php print $widgets['filter-title']->label; ?></p>
        <?php print $widgets['filter-title']->widget; ?>
      </div>
    <?php endif; ?>
</div>
<?php if (!empty($widgets['filter-field_hp_province_value']) || !empty($widgets['filter-capacity_range']) || !empty($widgets['filter-contract_year'])): ?>
  <div class="col-md-12">

    <?php if (!empty($widgets['filter-contract_year'])): ?>
      <div class="col-md-3">
        <?php print $widgets['filter-contract_year']->widget; ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($widgets['filter-month'])): ?>
      <div class="col-md-3">
        <?php print $widgets['filter-month']->widget; ?>
      </div>
    <?php endif; ?>

    <div class="col-md-3">
      <!-- for the 2 left filters-->
    </div>

    <div class="col-md-3 search-button-daytrips">
      <?php print $button; ?>
    </div>

  </div>

<?php endif; ?>
