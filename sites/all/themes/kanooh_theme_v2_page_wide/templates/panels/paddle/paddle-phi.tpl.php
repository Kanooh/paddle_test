<?php

/**
 * @file
 * Template for the phi layout.
 *
 * Variables:
 * - $content: An array of content, each item in the array is keyed to one
 *   panel of the layout. This layout supports the following:
 * - A top row in which we provide two sections, being 3/4 and 1/4 of
 * the width of the row.
 * - 4 rows in the middle which each provide two sections, all being equal
 * width (1/4 of the width of the row).
 * - A bottom row which only provides one section, being the full width
 * of the row.
 */
?>
<div class="row paddle-layout-paddle_phi <?php print $classes; ?> <?php print $custom_styles['nested_9_a']; ?>">
  <div class="row">
    <div class="col-md-12  col-lg-9">
      <?php print $content['nested_9_a']; ?>
    </div>
    <div class="col-md-12  col-lg-3">
      <?php print $content['nested_3_b']; ?>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 col-lg-3">
      <?php print $content['nested_3_c']; ?>
    </div>
    <div class="col-md-12 col-lg-3">
      <?php print $content['nested_3_d']; ?>
    </div>
    <div class="col-md-12 col-lg-3">
      <?php print $content['nested_3_e']; ?>
    </div>
    <div class="col-md-12 col-lg-3">
      <?php print $content['nested_3_f']; ?>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 col-lg-3">
      <?php print $content['nested_3_g']; ?>
    </div>
    <div class="col-md-12 col-lg-3">
      <?php print $content['nested_3_h']; ?>
    </div>
    <div class="col-md-12 col-lg-3">
      <?php print $content['nested_3_i']; ?>
    </div>
    <div class="col-md-12 col-lg-3">
      <?php print $content['nested_3_j']; ?>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 col-lg-3">
      <?php print $content['nested_3_k']; ?>
    </div>
    <div class="col-md-12 col-lg-3">
      <?php print $content['nested_3_l']; ?>
    </div>
    <div class="col-md-12 col-lg-3">
      <?php print $content['nested_3_m']; ?>
    </div>
    <div class="col-md-12 col-lg-3">
      <?php print $content['nested_3_n']; ?>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 col-lg-3">
      <?php print $content['nested_3_o']; ?>
    </div>
    <div class="col-md-12 col-lg-3">
      <?php print $content['nested_3_p']; ?>
    </div>
    <div class="col-md-12 col-lg-3">
      <?php print $content['nested_3_q']; ?>
    </div>
    <div class="col-md-12 col-lg-3">
      <?php print $content['nested_3_r']; ?>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <?php print $content['full_s']; ?>
    </div>
  </div>
</div>
