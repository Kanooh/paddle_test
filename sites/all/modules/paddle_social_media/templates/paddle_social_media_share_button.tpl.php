<?php
/**
 * @file
 * Template that displays the share buttons for the Paddle Social Media.
 */
?>
<div class="paddle-social-media-share">
  <!-- AddThis Button BEGIN -->
  <div class="addthis_toolbox addthis_default_style addthis_20x20_style">
    <?php foreach ($expanded as $name): ?>
      <a class="addthis_button_<?php print $name; ?>"></a>
    <?php endforeach; ?>
    <?php if (!empty($compat)): ?>
      <div class="compat-dropdown">
        <a href="#" class="compat-dropdown__toggle"><i class="fa fa-plus"></i><span class="visuallyhidden"><?php print t('Show more'); ?></span></a>
        <div class="compat-dropdown__list">
          <?php foreach ($compat as $key => $name): ?>
            <a class="addthis_button_<?php print $key; ?>"> <?php print $name; ?></a>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>
  </div>
  <script type="text/javascript">
    var addthis_config = {
      "data_track_addressbar": false,
      "ui_language": '<?php print $language; ?>',
      "ui_508_compliant": true
    };
  </script>
  <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-54ca1f0143f4df51"></script>
</div>
