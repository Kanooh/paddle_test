<?php
/**
 * @file
 * This template renders the description page of 1 app.
 */
?>

<div class="row">
  <div class="col-md-9" data-lang="<?php print $language; ?>">
    <?php if ($name): ?>
      <h1 class="paddle-apps-paddlet-name title">
        <?php print $name; ?>
      </h1>
    <?php endif; ?>
    <div class="paddle-apps-paddlet clearfix">
      <?php if ($image): ?>
        <div class="col-md-2 paddle-apps-paddlet-image">
          <?php print render($image); ?>
        </div>
      <?php endif; ?>
      <?php if ($detailed_description): ?>
        <div class="paddle-apps-paddlet-description detailed-description">
          <?php print $detailed_description; ?>
        </div>
      <?php elseif ($description): ?>
        <div class="paddle-apps-paddlet-description">
          <?php print $description; ?>
        </div>
      <?php endif; ?>
    </div>
    <?php if (!empty($screenshots)): ?>
      <div class="paddle-apps-paddlet-screenshots clearfix">
        <?php
          // Loops over the screenshots and adds the flex-slider mark-up.
          $slider_items = '';
          foreach($screenshots as $screenshot):
            $slider_items .= '<li><div class="paddle-apps-paddlet-screenshot">' . render($screenshot) . '</div></li>';
          endforeach;
        ?>
         <div class="flexslider"><ul class="slides"><?php print $slider_items; ?></ul></div>
      </div>
    <?php endif; ?>
  </div>
  <?php if (!empty($faq) || !empty($vendor)): ?>
    <div class="col-md-3">
      <?php if (!empty($vendor)): ?>
        <div class="statistics">
          <div class="vendor">
            <h2><?php print t('Statistics'); ?></h2>
            <h4><?php print t('made by'); ?></h4>
            <?php print $vendor; ?>
          </div>
        </div>
      <?php endif; ?>
      <?php if (!empty($faq)): ?>
        <div class="faq">
          <h2>FAQs</h2>
          <?php print $faq; ?>
        </div>
      <?php endif; ?>
    </div>
  <?php endif; ?>
</div>
