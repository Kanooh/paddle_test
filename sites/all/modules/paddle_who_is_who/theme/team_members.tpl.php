<?php
/**
 * @file
 * Template for the team members.
 */
if (!empty($rendered)) : ?>
  <div class="all-members">
    <?php foreach ($rendered as $contact_person) : ?>
      <a class="member-info" href="<?php print $contact_person['link'] ?>">
        <div class="team-member">
          <div class="team-member-featured-image">
          <?php if (!empty($contact_person['featured_image'])) : ?>
              <?php print render($contact_person['featured_image']); ?>
          <?php endif; ?>
          </div>
          <?php
          if (!empty($contact_person['container'])) :
            print render($contact_person['container']);
          endif;
          ?>
        </div>
      </a>
    <?php endforeach; ?>
  </div>
<?php endif ?>
