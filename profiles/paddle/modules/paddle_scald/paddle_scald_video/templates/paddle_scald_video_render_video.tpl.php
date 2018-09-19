<?php
/**
 * @file
 * Template that renders a video.
 */
?>
<?php if ($wrap) : ?>
  <div class="playable-video">
<?php endif; ?>
  <video controls="controls" width="<?php print $width; ?>" height="<?php print $height; ?>" class="playable-video atom-id-<?php print $atom_id; ?>" poster="<?php print $poster; ?>" preload="none" style="max-width:100%;">
      <source type="video/<?php print $type; ?>" src="<?php print $video_src?>" />
      <?php if (!empty($subtitles)) : ?>
          <track kind="subtitles" src="<?php print $subtitles; ?>" srclang="<?php print $language; ?>" />
      <?php endif; ?>
  </video>
<?php if ($wrap) : ?>
  </div>
<?php endif; ?>
