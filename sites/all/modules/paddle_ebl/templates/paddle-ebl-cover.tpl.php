<?php

/**
 * @file
 * Template of the cover image pane of the EBL content type.
 */
?>

<div class="pane-ebl-cover">
  <?php if (!empty($issuu_link)) : ?>
    <iframe width="100%" height="750" src="<?php print $issuu_link;?>" frameborder="0" allowfullscreen></iframe>
  <?php endif; ?>
</div>
