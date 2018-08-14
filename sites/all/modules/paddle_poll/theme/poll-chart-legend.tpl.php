<?php
/**
 * @file
 * Default template to render the legend for a poll chart.
 *
 * Available variables:
 * - $items: array of legend items. Every item is an array of label and color.
 * - $choices: raw unprocessed poll choices;
 * - $colors: list of available colors.
 */
?>
<div class="poll-chart-legend">
  <?php foreach ($items as $item): ?>
    <div class="poll-chart-legend__item">
      <div class="poll-chart-legend__color" style="background-color: <?php print $item['color']; ?>"></div>
      <div class="poll-chart-legend__label"><?php print $item['label']; ?></div>
    </div>
  <?php endforeach; ?>
</div>
