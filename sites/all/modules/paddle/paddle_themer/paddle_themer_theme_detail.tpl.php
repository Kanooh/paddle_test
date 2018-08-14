<?php
/**
 * @file
 * This template renders the details of 1 theme.
 */
?>

<div class="paddle-themer-theme theme-selector clearfix" data-theme-name="<?php print $theme->name; ?>">
  <div class="paddle-themer-theme-image">
    <?php print theme('paddle_themer_theme_image', array('theme' => $theme, 'style_name' => 'medium')); ?>
  </div>
  <div class="paddle-themer-theme-name">
    <h3>
      <?php print check_plain($theme->human_name); ?>
    </h3>
  </div>
  <div class="paddle-themer-theme-controls">
    <?php
      print theme('item_list', array(
        'items' => $operations,
        'type' => 'ul',
        'attributes' => array(
          'class' => array('paddle-themer-operations', 'clearfix'),
        ),
      ));
    ?>
  </div>
</div>
