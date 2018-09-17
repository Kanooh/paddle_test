<?php

/**
 * @file
 * Template for the Opening Hours status list.
 */
?>
<?php if (!empty($nodes)): ?>
  <div class="opening-hours-sets-status-list">
  <?php foreach ($nodes as $node): ?>
    <div class="opening-hours-sets-status-list-node">
      <div class="opening-hours-sets-status-list-node-title">
        <h3><?php echo $node['title']; ?></h3>
      </div>
      <div class="opening-hours-sets-status-list-node-time-container">
        <div class="opening-hours-sets-status-list-node-open">
          <?php echo $node['open']; ?>
        </div>
        <?php if (!empty($node['time'])): ?>
         <div class="opening-hours-sets-status-list-node-time">
            <?php echo $node['time']; ?>
         </div>
        <?php endif; ?>
        <?php if (!empty($node['description'])): ?>
          <div class="opening-hours-sets-status-list-node-description">
            <?php echo $node['description']; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  <?php endforeach; ?>
  </div>
<?php endif; ?>
