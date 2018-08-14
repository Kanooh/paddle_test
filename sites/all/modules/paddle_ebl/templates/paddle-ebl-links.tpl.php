<?php

/**
 * @file
 * Template of the document links pane of the EBL content type.
 */
?>

<?php if (!empty($documents_list)) : ?>
  <div class="files-section">
    <p><?php print render($documents_list); ?></p>
  </div>
<?php endif; ?>
