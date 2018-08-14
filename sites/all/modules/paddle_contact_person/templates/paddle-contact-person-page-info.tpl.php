<?php
/**
 * @file
 * Template for the Page view of the Contact Person.
 */
?>
<?php $i = isset($_GET["referenced_organization"]) ? $_GET["referenced_organization"] : (!empty($organizations) ? reset($organizations)['collection_id'] : FALSE) ?>

<?php if (!empty($field_paddle_featured_image)) : ?>
  <div class="paddle-cp paddle-cp-photo">
    <?php print $field_paddle_featured_image; ?>
  </div>
<?php endif; ?>
<h2 class="paddle-cp-page-title"><?php print $full_name; ?></h2>
<?php if (!empty($organizations[$i])) : ?>
  <div class="paddle-cp-contact-info">
    <?php if (!empty($organizations[$i]['function'])) : ?>
    <div class="paddle-cp-cp-function">
        <?php print $organizations[$i]['function']; ?>
    </div>
    <?php endif; ?>
    <?php if (!empty($organizations[$i]['email'])) : ?>
      <div class="paddle-cp paddle-cp-fc-email">
        <i class="fa fa-envelope valigntop"></i>
        <?php print $organizations[$i]['email']; ?>
      </div>
    <?php endif; ?>
    <?php if (!empty($organizations[$i]['phone'])) : ?>
      <div class="paddle-cp paddle-cp-fc-phone-office">
        <i class="fa fa-phone valigntop"></i>
        <?php print $organizations[$i]['phone']; ?>
      </div>
    <?php endif; ?>
    <?php if (!empty($organizations[$i]['mobile'])) : ?>
      <div class="paddle-cp paddle-cp-fc-mobile-office">
        <i class="fa fa-mobile valigntop"></i>
        <?php print $organizations[$i]['mobile']; ?>
      </div>
    <?php endif; ?>
  </div>
  <?php
elseif ((!empty($function)) || !empty($email) || !empty($phone_office) || !empty($mobile_office))  : ?>
<div class="paddle-cp-contact-info">
  <?php if (!empty($function)) : ?>
    <div class="paddle-cp paddle-cp-function">
      <?php print $function; ?>
    </div>
  <?php endif; ?>
  <?php if (!empty($email)) : ?>
    <div class="paddle-cp paddle-cp-email">
      <i class="fa fa-envelope valigntop"></i>
     <?php print $email; ?>
    </div>
  <?php endif; ?>
  <?php if (!empty($phone_office)) : ?>
    <div class="paddle-cp paddle-cp-phone-office">
      <i class="fa fa-phone valigntop"></i>
      <?php print $phone_office; ?>
    </div>
  <?php endif; ?>
  <?php if (!empty($mobile_office)) : ?>
    <div class="paddle-cp paddle-cp-mobile-office">
      <i class="fa fa-mobile valigntop"></i>
      <?php print $mobile_office; ?>
    </div>
  <?php endif; ?>
</div>
<?php endif; ?>
