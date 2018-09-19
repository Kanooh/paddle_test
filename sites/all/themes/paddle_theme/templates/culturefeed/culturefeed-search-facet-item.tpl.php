<?php
/**
 * @file
 * Template file for a single culturefeed search facet item.
 */

/**
 * @var string $label
 * @var integer $count
 * @var string $url
 * @var boolean $active
 * @var boolean $active_subitem
 */
?>

<div class="facet-label<?php if ($active): ?> active<?php endif; ?>">
  <div class="row">
    <?php if ($active): ?>
      <div class="col-md-8 col-sm-12 col-xs-9">
        <?php print check_plain($label); ?>
      </div>
      <div class="col-md-4 hidden-sm col-xs-3 text-right text-muted">
        <a href="<?php print $url; ?>" class="facet-remove" title="<?php print t('Remove filter'); ?>"><span class="element-invisible"><?php print t('Remove filter'); ?></span>&times;</a>
      </div>
    <?php else: ?>
      <div class="col-md-8 col-sm-12 col-xs-9">
        <a href="<?php print $url; ?>" <?php $active_subitem ? print 'rel="nofollow"' : ''; ?>><?php print check_plain($label); ?></a>
      </div>
      <div class="col-md-4 hidden-sm col-xs-3 text-right text-muted">
        <small class="facet-count">(<?php print $count; ?>)</small>
      </div>
    <?php endif; ?>
  </div>
</div>
