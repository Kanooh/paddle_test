<?php

/**
 * @file
 * Template of the related terms of the CIRRO content type.
 */
?>

<div class="pane-terms">
  <div class="pane-content">
    <div class="pane-section-body">
      <?php if (!empty($paddle_cirro_policy_themes)) : ?>
        <p><span class="label"><?php print t('Policy themes'); ?></span><?php print ': ' . $paddle_cirro_policy_themes; ?></p>
      <?php endif; ?>
      <?php if (!empty($paddle_cirro_settings)) : ?>
        <p><span class="label"><?php print t('Settings', array(), array('context' => 'CIRRO')); ?></span><?php print ': ' . $paddle_cirro_settings; ?></p>
      <?php endif; ?>
      <?php if (!empty($paddle_cirro_action_strategies)) : ?>
        <p><span class="label"><?php print t('Action strategies'); ?></span><?php print ': ' . $paddle_cirro_action_strategies; ?></p>
      <?php endif; ?>
    </div>
  </div>
</div>
