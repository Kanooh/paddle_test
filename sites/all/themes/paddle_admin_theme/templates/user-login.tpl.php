<?php
/**
 * @file
 * Defines template for user login page
 */
?>
<div class="user-login-form">
  <h2><?php print t('Log in to kañooh'); ?></h2>
  <div class="kanooh-logo"></div>
  <?php if ($messages): ?>
    <?php print $messages; ?>
  <?php endif; ?>
  <?php
    // Split the form.
    print drupal_render($form['name']);
    print drupal_render($form['pass']);
    print drupal_render($form['actions']);
    print drupal_render($form['remember_me']);
    print drupal_render($form['form_build_id']);
    print drupal_render($form['form_id']);
  ?>
  <hr />
  <div class="bttn-wrapper kanooh-mail">
    <span class="info-text"><?php print t("Don't have an account yet?"); ?></span>
    <a id="contact-button" href="mailto:helpmij@kanooh.be?subject=<?php print rawurlencode(variable_get('site_name', '')) . ' - ' . t('Have a question about this Kañooh website?'); ?>">
      <?php print t('Contact this site'); ?>
    </a>
  </div>
  <div class="bttn-wrapper kanooh-mail">
    <span class="info-text"><?php print t("Forgotten your password?"); ?></span>
    <a href="<?php print variable_get('base_url', '') . variable_get('base_path', '') . 'user/password'; ?>"><?php print t("Click here"); ?></a>
  </div>
  <div class="bttn-wrapper kanooh-website">
    <?php print t('<a href="http://www.kanooh.be">Kañooh</a><span class="info-text"> helps the Flemish government building websites that are easy to manage.</span>'); ?>
  </div>
</div>
