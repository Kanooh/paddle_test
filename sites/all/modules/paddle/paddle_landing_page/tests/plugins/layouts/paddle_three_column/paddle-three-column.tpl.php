<?php
/**
 * @file
 * Template for a three column layout
 *
 * Variables:
 * - $content: An array of content, each item in the array is keyed to one
 *   panel of the layout. This layout supports the following sections:
 *   - $content['left']: The left panel in the layout.
 *   - $content['middle']: The middle panel in the layout.
 *   - $content['right']: The right panel in the layout.
 */
$content_middle_offset = '';
$content_right_offset = '';
if (empty($content['left']) && !empty($content['middle'])):
  $content_middle_offset = 'offset4';
elseif (empty($content['left']) && empty($content['middle'])):
  $content_right_offset = 'offset8';
elseif (empty($content['middle'])):
  $content_right_offset = 'offset4';
endif;
?>
<div class="row">
  <div class="col-md-12">
   <?php if (!empty($content['left'])):?>
    <div class="col-md-4">
    <div class="panel-panel panel-col">
      <div><?php print $content['left']; ?></div>
    </div>
  </div>
  <?php endif; ?>
  <?php if (!empty($content['middle'])):?>
  <div class="col-md-4 <?php print ' ' . $content_middle_offset; ?>">
    <div class="panel-panel panel-col">
      <div><?php print $content['middle']; ?></div>
    </div>
  </div>
  <?php endif; ?>
  <?php if (!empty($content['right'])):?>
   <div class="col-md-4 <?php print ' ' . $content_right_offset; ?>">
    <div class="panel-panel panel-col">
      <div><?php print $content['right']; ?></div>
    </div>
  </div>
 <?php endif; ?>
</div>
