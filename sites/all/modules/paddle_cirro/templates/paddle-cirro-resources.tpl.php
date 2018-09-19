<?php

/**
 * @file
 * Template which displays other resources of the Cirro content type.
 */
?>

<?php if (!empty($resources)) : ?>
  <ul class="pane-resources">
    <?php foreach ($resources as $id => $resource) : ?>
      <li class="pane-resource node-<?php print $id; ?>">
      <?php if (!empty($resource['title'])) : ?>
        <?php print $resource['title']; ?>
      <?php endif; ?>
      <?php if (!empty($resource['summary'])) : ?>
        <?php print render($resource['summary']); ?>
      <?php endif; ?>
      </li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>
