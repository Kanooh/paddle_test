<?php
/**
 * @file
 * Defines template for forget password
 */
?>
<div class="user-login-form">
  <h2><?php print t('Request new password'); ?></h2>
  <?php
    // Split the form.
    print drupal_render($form['name']);
    print drupal_render($form['pass']);
    print drupal_render($form['actions']);
    print drupal_render($form['remember_me']);
    print drupal_render($form['form_build_id']);
    print drupal_render($form['form_id']);
    print drupal_render_children($form);
  ?>
  <hr />
  <div class="bttn-wrapper kanooh-website">
    <?php print t('<a href="http://www.kanooh.be">Kañooh</a><span class="info-text"> helps the Flemish government building websites that are easy to manage.</span>'); ?>
  </div>
</div>
