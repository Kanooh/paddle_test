<?php

/**
 * @file
 * Template of the contact information pane of the Cirro content type.
 */
?>

<div class="pane-contact-info">
    <div class="logo-section">
        <p><?php print t("This equipment and/or these services are locally provided by the Vlaamse Logo's:"); ?></p>
        <p><?php print l(t('www.vlaamse-logos.be'),
            'http://www.vlaamse-logos.be', array('absolute' => TRUE)); ?></p>
        <p><?php print t('If you would require more information about the equipment or services, please contact the <a href="@logo">Logo</a> of your municipality',
            array('@logo' => 'http://www.vlaamse-logos.be/projecten1/in-de-gemeente/all')) ?></p>
    </div>
  <?php if (!empty($contact_info)) : ?>
    <div class="contact-info-section">
        <p><?php print t("This equipment and/or these services have been developed by:"); ?></p>
          <p><?php print render($contact_info); ?></p>
    </div>
  <?php endif; ?>
</div>
