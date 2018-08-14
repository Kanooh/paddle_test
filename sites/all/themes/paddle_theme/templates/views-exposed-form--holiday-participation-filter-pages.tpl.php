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
<?php if (!empty($widgets['filter-field_hp_province_value']) || !empty($widgets['filter-capacity_range']) || !empty($widgets['filter-contract_year']) || !empty($widgets['filter-month'])): ?>
  <div class="col-md-12">
    <?php if (!empty($widgets['filter-field_hp_province_value'])): ?>
      <div class="col-md-3">
        <?php print $widgets['filter-field_hp_province_value']->widget; ?>
      </div>
    <?php endif; ?>

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

    <?php if (!empty($widgets['filter-capacity_range'])): ?>
      <div class="col-md-3">
        <?php print $widgets['filter-capacity_range']->widget; ?>
      </div>
    <?php endif; ?>

  </div>

<?php endif; ?>


<div class="col-md-12">

  <?php if (!empty($widgets['filter-field_hp_facilities_value'])): ?>
    <div class="col-md-3">
        <span class="hp-filters-label"><?php print $widgets['filter-field_hp_facilities_value']->label ?>
   </span>
      <div class="room-board-filter hp_options">
        <?php print $widgets['filter-field_hp_facilities_value']->widget; ?></div>
    </div>
  <?php endif; ?>

  <?php if (!empty($widgets['filter-field_hp_room_and_board_value'])): ?>
    <div class="col-md-3">
        <span class="hp-filters-label"><?php print $widgets['filter-field_hp_room_and_board_value']->label ?>
   </span>
      <div class="hp-room-board-filter hp_options">
        <?php print $widgets['filter-field_hp_room_and_board_value']->widget; ?></div>
    </div>
  <?php endif; ?>
  <?php if (!empty($widgets['filter-field_hp_formula_oh_value'])): ?>
    <div class="col-md-3">
        <span class="hp-filters-label"><?php print $widgets['filter-field_hp_formula_oh_value']->label ?>
   </span>
      <div class="hp-formula-oh-filter hp_options">
        <?php print $widgets['filter-field_hp_formula_oh_value']->widget; ?></div>
    </div>
  <?php endif; ?>

  <?php if (!empty($widgets['filter-field_hp_labels_value'])): ?>
    <div class="col-md-3">
        <span class="hp-filters-label"><?php print $widgets['filter-field_hp_labels_value']->label ?>
   </span>
      <div class="hp-labels-filter hp_options">
        <?php print $widgets['filter-field_hp_labels_value']->widget; ?>
      </div>
    </div>
  <?php endif; ?>

  <div class="col-md-3 search-button">
    <div class="search-button-wrapper">
      <?php print $button; ?>
    </div>
  </div>
</div>
