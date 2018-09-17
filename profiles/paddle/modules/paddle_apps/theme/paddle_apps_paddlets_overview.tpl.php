<?php
/**
 * @file
 * This template handles the overview of the paddlets.
 *
 * Available variables:
 * - $paddlets: Contains a list of paddlets rendered as teasers.
 */
?>

<div class="paddle_apps_paddlets_overview" data-lang="<?php print $language; ?>">
  <?php if ($paddlets): ?>
    <div class="paddlets">
      <?php print render($paddlets); ?>
    </div>
  <?php endif; ?>
</div>
