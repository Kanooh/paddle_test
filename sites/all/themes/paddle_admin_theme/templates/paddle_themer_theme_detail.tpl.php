<?php
/**
 * @file
 * This template renders the details of 1 theme.
 */
?>

<div class="paddle-themer-theme theme-selector col-md-4 clearfix <?php if (!empty($class)): print $class; endif; ?>" data-theme-name="<?php print $theme->name; ?>">
  <h3 class="theme-title"><?php print check_plain($theme->human_name); ?></h3>
  <div class="row">
    <div class="paddle-themer-theme-image col-md-6">
      <?php print theme('paddle_themer_theme_image', array('theme' => $theme, 'style_name' => 'paddle_themer_thumbnail')); ?>
    </div>
    <div class="paddle-themer-theme-controls col-md-6">
      <?php
        print theme('item_list', array(
          'items' => $operations,
          'type' => 'ul',
          'attributes' => array(
            'class' => array('paddle-themer-controls-list', 'clearfix'),
          ),
        ));
      ?>
    </div>
  </div>
</div>
