<?php

/**
 * @file
 * Template for the Page view of the Contact person.
 */
?>
<?php $i = isset($_GET["referenced_organization"]) ? $_GET["referenced_organization"] : (!empty($organizations) ? reset($organizations)['collection_id'] : FALSE) ?>

<?php if (!empty($organizations)) : ?>
  <?php if(!empty($organizations[$i]['address'])) : ?>
  <h3 class="paddle-cp-heading"><?php print t('Location'); ?></h3>
  <div class="paddle-cp paddle-cp-address">
    <i class="fa fa-home valigntop"></i>
    <div class="paddle-cp-address-container">
      <?php !empty($organizations[$i]['office']) ? print $organizations[$i]['office'] : ''; ?>
      <?php print $organizations[$i]['address']; ?>
    </div>
  </div>
  <?php endif; ?>
  <?php
elseif (!empty($formatted_address)) : ?>
  <h3 class="paddle-cp-heading"><?php print t('Location'); ?></h3>
  <div class="paddle-cp paddle-cp-address">
    <i class="fa fa-home valigntop"></i>
    <div class="paddle-cp-address-container">
      <?php print $formatted_address; ?>
    </div>
  </div>
<?php endif;
if (!empty($website) || !empty($linkedin) || !empty($twitter) || !empty($facebook) || !empty($skype) || !empty($yammer) || !empty($organizations[$i]['link'])) : ?>
  <h3 class="paddle-cp-heading"><?php print t('Online'); ?></h3>
  <div class="paddle-cp paddle-cp-online">
    <?php if (!empty($organizations[$i]['website'])) : ?>
      <div class="paddle-cp paddle-cp-website">
        <i class="fa fa-link valigntop"></i>
        <?php print $organizations[$i]['website']; ?>
      </div>
      <?php
    elseif (!empty($website)) : ?>
      <div class="paddle-cp paddle-cp-website">
        <i class="fa fa-link valigntop"></i>
        <?php print $website; ?>
      </div>
    <?php endif;
    if (!empty($linkedin)) : ?>
      <div class="paddle-cp paddle-cp-linkedin">
        <i class="fa fa-linkedin valigntop"></i>
        <?php print $linkedin; ?>
      </div>
    <?php endif;
    if (!empty($facebook)) : ?>
      <div class="paddle-cp paddle-cp-facebook">
        <i class="fa fa-facebook valigntop"></i>
        <?php print $facebook; ?>
      </div>
    <?php endif;
    if (!empty($skype)) : ?>
        <div class="paddle-cp paddle-cp-skype">
            <i class="fa fa-skype valigntop"></i>
            <?php print $skype; ?>
        </div>
    <?php endif;
    if (!empty($twitter)) : ?>
      <div class="paddle-cp paddle-cp-twitter">
        <i class="fa fa-twitter valigntop"></i>
        <?php print $twitter; ?>
      </div>
    <?php endif;
    if (!empty($yammer)) : ?>
      <div class="paddle-cp paddle-cp-yammer">
        <i class="icon-yammer valigntop"></i>
        <?php print $yammer; ?>
      </div>
    <?php endif; ?>
  </div>
<?php endif;
if (!empty($ou_level_1) || !empty($ou_level_2) || !empty($ou_level_3) || $number_of_organizations > 1 || !empty($organizations[$i]['parents']) || !empty($manager)): ?>
  <?php if(!empty($organizations[$i]['parents']) || !empty($manager)) : ?>
  <h3 class="paddle-cp-heading"><?php print t('Other information'); ?></h3>
  <?php endif;?>
  <?php if (!empty($organizations)) : ?>
    <?php if (!empty($organizations[$i]['manager_link'])) : ?>
      <div class="paddle-cp paddle-cp-manager">
        <span class="label"><?php print t('Managed by'); ?></span>
        <?php print $organizations[$i]['manager_link']; ?>
      </div>
    <?php endif; ?>
  <?php elseif (!empty($manager)) : ?>
    <div class="paddle-cp paddle-cp-manager">
      <span class="label"><?php print t('Managed by'); ?></span>
      <?php print $manager; ?>
    </div>
  <?php endif;
  if (!empty($organizations[$i]['parents'])) : ?>
    <div class="paddle-cp-fc-ou-parents">
      <?php foreach ($organizations[$i]['parents'] as $parent) : ?>
        <div><?php print $parent['link']; ?></div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
  <?php if (!empty($organizations) && $number_of_organizations > 1) : ?>
    <?php if($published_organization_exists === TRUE) : ?>
    <h3 class="paddle-cp-heading"><?php print t('Also works with'); ?></h3>
    <div class="paddle-cp paddle-cp-ous">
      <?php foreach ($organizations as $key => $value) : ?>
        <?php if($key != $i) : ?>
        <?php if(!empty($value['path'])  && !empty($value['show_contact_info_link'])) : ?>
        <div class="paddle-oup paddle-cp-ou">
        <span> <?php print $value['path']; ?>
          <?php print urldecode($value['show_contact_info_link']) ?></div>
        <?php endif; ?>
        <?php endif; ?>
      <?php endforeach; ?>
    <?php endif; ?>
    </div>
  <?php endif;
  if (!empty($ou_level_1) && empty($organizations)) : ?>
    <div class="paddle-cp paddle-cp-ou-levels level-1">
      <?php print $ou_level_1; ?>
    </div>
  <?php endif;
  if (!empty($ou_level_2) && empty($organizations)) : ?>
    <div class="paddle-cp paddle-cp-ou-levels level-1">
      <?php print $ou_level_2; ?>
    </div>
  <?php endif;
  if (!empty($ou_level_3) && empty($organizations)) : ?>
    <div class="paddle-cp paddle-cp-ou-levels level-1">
      <?php print $ou_level_3; ?>
    </div>
  <?php endif;
endif;
?>
