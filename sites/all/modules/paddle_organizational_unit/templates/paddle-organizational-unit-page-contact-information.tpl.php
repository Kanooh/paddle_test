<?php

/**
 * @file
 * Template for the Page view of the Organizational Unit.
 */
?>
<?php if (!empty($address_formatted)) : ?>
  <h3 class="paddle-oup-heading"><?php print t('Location'); ?></h3>
  <div class="paddle-oup paddle-oup-address">
    <i class="fa fa-home valigntop"></i>
    <div class="inline-block"><?php print $address_formatted; ?></div>
  </div>
<?php endif;
if (!empty($website) || !empty($linkedin) || !empty($twitter) || !empty($facebook)) : ?>
  <h3 class="paddle-oup-heading"><?php print t('Online'); ?></h3>
  <div class="paddle-oup paddle-oup-online">
    <?php if (!empty($website)) : ?>
      <div class="paddle-oup paddle-oup-website">
        <i class="fa fa-link valigntop"></i>
        <a href="<?php print $website; ?>"><?php print $website_simple; ?></a>
      </div>
    <?php endif;
    if (!empty($linkedin)) : ?>
      <div class="paddle-oup paddle-oup-linkedin">
        <i class="fa fa-linkedin valigntop"></i>
        <a href="<?php print $linkedin; ?>"><?php print $linkedin_simple; ?></a>
      </div>
    <?php endif;
    if (!empty($facebook)) : ?>
      <div class="paddle-oup paddle-oup-facebook">
        <i class="fa fa-facebook valigntop"></i>
        <a href="<?php print $facebook; ?>"><?php print $facebook_simple; ?></a>
      </div>
    <?php endif;
    if (!empty($twitter)) : ?>
      <div class="paddle-oup paddle-oup-twitter">
        <i class="fa fa-twitter valigntop"></i>
        <a href="<?php print $twitter; ?>"><?php print $twitter_simple; ?></a>
      </div>
    <?php endif; ?>
  </div>
<?php endif;
if (!empty($vat_number) || !empty($head_of_unit_ref) || !empty($head_of_unit) || !empty($parent_entities)) : ?>
  <h3 class="paddle-oup-heading"><?php print t('Other information'); ?></h3>
  <div class="paddle-oup paddle-oup-other-info">
    <?php if (!empty($vat_number)) : ?>
      <div class="paddle-oup paddle-oup-vat-number">
        <div class="inline-block"><?php print t('VAT No.: @vat_number', array('@vat_number' => $vat_number)); ?></div>
      </div>
    <?php endif; ?>
    <?php if (!empty($head_of_unit_ref)) : ?>
      <div class="paddle-oup paddle-oup-head-unit">
        <span class="label"><?php print t('Managed by:'); ?></span>
        <?php print $head_of_unit_ref; ?>
      </div>
    <?php elseif (!empty($head_of_unit)) : ?>
      <div class="paddle-oup paddle-oup-head-unit">
        <span class="label"><?php print t('Managed by:'); ?></span>
        <?php print $head_of_unit; ?>
      </div>
    <?php endif; ?>
    <?php if (!empty($parent_entities)) : ?>
      <div class="paddle-oup paddle-oup-parents">
        <?php foreach ($parent_entities as $parent_entity) : ?>
          <div class="paddle-oup paddle-oup-parent-units parent-<?php print $parent_entity['id']; ?>">
            <?php print htmlspecialchars_decode($parent_entity['parent']); ?>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
<?php endif;
