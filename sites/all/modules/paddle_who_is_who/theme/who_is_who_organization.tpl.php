<?php
/**
 * @file
 * Template for the organization view mode of the who-is-who pane.
 */
?>
<?php if (!empty($children) || !empty($team_members)) : ?>
  <div class="who-is-who-pane-organization-view-mode">
    <?php if (!empty($children)) : ?>
      <div class="<?php empty($team_members) ? print 'ou-children float-left' : print 'ou-children'; ?>">
        <?php foreach ($children as $child) : ?>
          <div class="ou-child">
            <h3><?php print $child ?></h3>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif;
    if (!empty($team_members)) : ?>
      <div class="<?php empty($children) ? print 'ou-team-members float-left' : print 'ou-team-members'; ?>">
        <?php foreach ($team_members as $team_member) : ?>
          <div class="ou-team-member">
            <h3><?php print $team_member['title'] ?></h3>
            <?php if (!empty($team_member['function'])) : ?>
              <div class="ou-team-member-function">
                <?php print $team_member['function']; ?>
              </div>
            <?php endif; ?>
            <?php if (!empty($team_member['responsible'])) : ?>
              <div class="ou-team-member-function">
                <?php print $team_member['responsible']; ?>
              </div>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
<?php endif; ?>
